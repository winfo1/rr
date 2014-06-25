<?php

App::uses('AppController', 'Controller');

App::uses('Booking', 'Model');

App::uses('CakeEmail', 'Network/Email');
App::uses('Validation', 'Utility');

App::import('Lib', 'MyTime');
App::import('Lib', 'Utils');

class BookingsController extends AppController {

    /*
     * basic definitions
     */

    //<editor-fold defaultstate="collapsed" desc="basic definitions">

    public $components = array('Paginator');

    public $paginate = array(
        'limit' => 25,
    );

    public function beforeFilter() {
        parent::beforeFilter();

        $this->Auth->allow('beforeDetailDisplay', 'getBookings', 'view', 'buildInterval', 'cleanUp', 'isBefore', 'edit_silent_status');
    }

    //</editor-fold>

    /*
     * basic functions
     */

    //<editor-fold defaultstate="collapsed" desc="basic functions">

    public function isAuthorized($user) {

        if (in_array($this->action, array('add'))) {
            return true;
        }

        if (in_array($this->action, array('edit', 'delete'))) {
            if ($this->action == 'delete')
                $id = $this->request->params['pass'][1];
            else
                $id = $this->request->params['pass'][0];

            if ($this->Booking->isOwnedBy($id, $this->Session->read('Auth.User.id')))
                return true;
        }

        if ($user['role'] == 'admin') {

            if ($this->action === 'add')
                return true;

            if (in_array($this->action, array('accept', 'edit', 'reject'))) {
                $id = $this->request->params['pass'][0];

                if ($this->Booking->isOwnedThroughOrganizationalUnitBy($id, $this->Session->read('Auth.User.organizationalunit_id')))
                    return true;
            }
        }

        return parent::isAuthorized($user);
    }

    public function beforeDetailDisplay() {
        $rooms_all = $this->Booking->Room->getAll();
        $this->set(compact('rooms_all'));
        $rooms = $this->Booking->Room->getRoomsAsList($rooms_all);
        $this->set(compact('rooms'));
    }

    //</editor-fold>

    /*
     * view functions
     */

    //<editor-fold defaultstate="collapsed" desc="view functions">

    public function index($view = null) {
        if (isset($view) && ($view == 'table')) {
            $this->Paginator->settings = $this->paginate;
            $data = $this->Paginator->paginate('Booking');
            $this->set(compact('data'));
        }
    }

    public function accept($id = null) {
        $this->Booking->id = $id;
        if (!$this->Booking->exists()) {
            throw new NotFoundException(__('Buchung nicht gefunden'));
        }

        $this->request->data = $this->Booking->getAll($id)[0];

        $blocked = array();

        if (!$this->Booking->inUse($this->request->data['Booking']['startdatetime'], $this->request->data['Booking']['enddatetime'], $this->request->data['Booking']['room_id'], 0, true, $blocked)) {
            $this->Booking->set('status', Booking::active);

            if ($this->Booking->save()) {
                $this->Session->setFlash(__('Die Buchung wurde aktiviert'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
                $this->emailUser_plan_to_active($this->request->data);
                $this->redirect(array('action' => 'index'));
                return true;
            }
        } else {
            $me = ($this->Auth->user('id') == $blocked[0]['User']['id']);

            $m = $me ? __('Sie haben den Raum bereits für diesen Zeitraum gebucht') : sprintf(__('Zu diesem Zeitpunkt ist der Raum bereits durch %s gebucht'), $blocked[0]['User']['username']);

            $this->Session->setFlash($m, 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-danger'
            ));
            return false;
        }

        $this->Session->setFlash(__('Die Buchung konnte nicht aktiviert werden'), 'alert', array(
            'plugin' => 'BoostCake',
            'class' => 'alert-danger'
        ));
        return false;
    }

    /**
     * @param null $room_id i.e. 1
     * @param null $day i.e. 2014-05-01
     * @param null $start_hour i.e. 19-14
     * @param null $end_hour i.e. 20-14
     * @return bool
     */
    public function add($room_id = null, $day = null, $start_hour = null, $end_hour = null) {
        // set available rooms
        $rooms = $this->Booking->Room->getRoomsFromList();
        $this->set(compact('rooms'));

        // set default room
        $rooms_keys = array_keys($rooms);
        if (!isset($room_id) || !in_array($room_id, $rooms_keys)) {
            if (array_key_exists('Booking', $this->request->data) && array_key_exists('room_id', $this->request->data['Booking']))
                $room_id = $this->request->data['Booking']['room_id'];
            if (!isset($room_id) || !in_array($room_id, $rooms_keys)) {
                if (count($rooms) > 0)
                    $room_id = $rooms_keys[0];
                else
                    $room_id = 0;
            }
        }
        $this->request->data['Booking']['room_id'] = $room_id;

        // easy view
        $view_tabs = $this->request->data['Booking']['view_tabs'];

        // set default day
        if (!isset($day)) {
            if (array_key_exists('Booking', $this->request->data) && array_key_exists('day', $this->request->data['Booking']))
                $day = $this->request->data['Booking']['day'];
            if (!isset($day)) {
                $day = (new DateTime())->format('Y-m-d');
            } else {
                try {
                    $day = (new DateTime($day))->format('Y-m-d');
                    if(!isset($view_tabs)) {
                        $view_tabs = 'a';
                    }
                } catch (\Exception $e) {
                    $day = (new DateTime())->format('Y-m-d');
                }
            }
        } else {
            try {
                $day = (new DateTime($day))->format('Y-m-d');
                if(!isset($view_tabs)) {
                    $view_tabs = 'a';
                }
            } catch (\Exception $e) {
                $day = (new DateTime())->format('Y-m-d');
            }
        }
        $this->request->data['Booking']['day'] = $day;

        // set default start hour
        if (isset($start_hour) && preg_match('/^[0-9]{1,2}-[0-9]{1,2}$/', $start_hour)) {
            $start_hour = str_replace('-', ':', $start_hour);
            if(!isset($view_tabs)) {
                $view_tabs = 'a';
            }
        } else {
            if (array_key_exists('Booking', $this->request->data) && array_key_exists('start_hour', $this->request->data['Booking']))
                $start_hour = $this->request->data['Booking']['start_hour'];
            if (isset($start_hour) && preg_match('/^[0-9]{1,2}:[0-9]{1,2}$/', $start_hour)) {
                if(!isset($view_tabs)) {
                    $view_tabs = 'a';
                }
            } else {
                $start_hour = (new DateTime())->format('H:i');
            }
        }
        $this->request->data['Booking']['start_hour'] = $start_hour;

        // set default end hour
        if (isset($end_hour) && preg_match('/^[0-9]{1,2}-[0-9]{1,2}$/', $end_hour)) {
            $end_hour = str_replace('-', ':', $end_hour);
            if(!isset($view_tabs)) {
                $view_tabs = 'a';
            }
        } else {
            if (array_key_exists('Booking', $this->request->data) && array_key_exists('end_hour', $this->request->data['Booking']))
                $end_hour = $this->request->data['Booking']['end_hour'];
            if (isset($end_hour) && preg_match('/^[0-9]{1,2}:[0-9]{1,2}$/', $end_hour)) {
                if(!isset($view_tabs)) {
                    $view_tabs = 'a';
                }
            } else {
                $end_hour = Utils::toEndDateTime(new DateTime(), 60)->format('H:i');
            }
        }
        $this->request->data['Booking']['end_hour'] = $end_hour;

        // set view
        if(!isset($view_tabs)) {
            $view_tabs = 's';
        }
        $this->request->data['Booking']['view_tabs'] = $view_tabs;
        // $this->set(compact('view_tabs'));

        if ($this->request->is('post')) {

            $room_id = $this->request->data['Booking']['room_id'];

            if ($this->request->data['Booking']['view_tabs'] == 's') {
                // simple booking-time selection
                $start = (new DateTime())->modify('+' . $this->request->data['Booking']['start_minutes'] . ' minutes');
                $end = Utils::toEndDateTime($start, $this->request->data['Booking']['duration']);
                // $end = Utils::toEndDateTime($start, $this->request->data['Booking']['duration'], $this->request->data['Booking']['duration']);
            } else {
                // advanced booking-time selection
                $day = $this->request->data['Booking']['day'];
                $start = Utils::toDateTime($day, $this->request->data['Booking']['start_hour']);
                $end = Utils::toDateTime($day, $this->request->data['Booking']['end_hour']);
                // $this->request->data['Booking']['duration'] = strval(Utils::getDiffInMin($start, $end));
            }

            $room = $this->Booking->Room->getAll($room_id);
            $approval_horizon = $room[0]['Organizationalunit']['approval_horizon'];
            $approval_horizon_max_date = (new DateTime())->modify('+' . $approval_horizon . ' week');

            $interval_booking = null;
            $interval_iteration = $this->request->data['Booking']['interval_iteration'];

            $ignore_booked = $this->request->data['Booking']['ignore_booked'];

            $group_id = 0;
            if ($interval_iteration) {
                $group_id = $this->Booking->getNextAutoIncrement();
                $interval_type = $this->request->data['Booking']['interval_type'];

                $interval_value = array();
                switch ($interval_type) {

                    case 'A': // after
                        $interval_value['interval_count'] = $this->request->data['Booking']['interval_count'];
                        break;
                    case 'B': // date
                        $interval_value['interval_end'] = $this->request->data['Booking']['interval_end'];
                        break;
                    case 'C': // semester/year
                        $interval_value['interval_range'] = $this->request->data['Booking']['interval_range'];
                }

                $interval_booking = $this->buildInterval($start, $end, $room_id, $interval_type, $interval_iteration, $interval_value, $approval_horizon, $approval_horizon_max_date);
                $hasErrorInIntervalLoop = false;
                $blocked = array();

                for ($i = 1; $i <= count($interval_booking); $i++) {

                    if ($interval_booking[$i]['in_use']) {
                        $hasErrorInIntervalLoop = true;
                        $blocked = $interval_booking[$i]['blocked'];
                        break;
                    }
                }

                if (!$hasErrorInIntervalLoop || $ignore_booked) {
                    for ($i = 1; $i <= count($interval_booking); $i++) {
                        if(!$interval_booking[$i]['in_use']) {

                            $interval_booking_id = 0;

                            $this->add_silent(
                                $room_id,
                                $this->Auth->user('id'),
                                $group_id,
                                $this->request->data['Booking']['name'],
                                $interval_booking[$i]['status'],
                                $interval_booking[$i]['start_date']->format('Y-m-d H:i:s'),
                                $interval_booking[$i]['end_date']->format('Y-m-d H:i:s'),
                                $interval_booking_id
                            );

                            $interval_booking[$i]['id'] = $interval_booking_id;
                        }
                    }
                } else {
                    if (count($blocked) == 1) {
                        $me = ($this->Auth->user('id') == $blocked[0]['User']['id']);

                        $m = $me ? __('Sie haben den Raum bereits für diesen Zeitraum gebucht') : sprintf(__('Zu diesem Zeitpunkt ist der Raum bereits durch %s gebucht'), $blocked[0]['User']['username']);

                        $this->Session->setFlash($m, 'alert', array(
                            'plugin' => 'BoostCake',
                            'class' => 'alert-danger'
                        ));
                        return false;
                    } else {
                        $m = 'Zu diesem Zeitpunkt haben bereits mehrere Personen diesen Raum reserviert. Darunter ';

                        $first = true;
                        foreach ($blocked as $value) {

                            if ($first) {
                                $first = false;
                            } else {
                                $m .= ', ';
                            }

                            $m .= $value['User']['username'] . ' (von ' . $value['Booking']['startdatetime'] . ' bis ' . $value['Booking']['enddatetime'] . ')';

                        }
                        $m .= '. ';

                        $this->Session->setFlash($m, 'alert', array(
                            'plugin' => 'BoostCake',
                            'class' => 'alert-danger'
                        ));
                        return false;
                    }
                }
            }

            $blocked = array();

            if (!$this->Booking->inUse($start->format('Y-m-d H:i:s'), $end->format('Y-m-d H:i:s'), $room_id, 0, true, $blocked)) {
                $status = $this->getStatusFromDate($approval_horizon, $end, $approval_horizon_max_date);
                $this->request->data['Booking']['user_id'] = $this->Auth->user('id');
                $this->request->data['Booking']['group_id'] = $group_id;
                $this->request->data['Booking']['status'] = $status;
                $this->request->data['Booking']['startdatetime'] = $start->format('Y-m-d H:i:s');
                $this->request->data['Booking']['start'] = MyTime::toReadableDateTime($start->getTimestamp(), true);
                $this->request->data['Booking']['enddatetime'] = $end->format('Y-m-d H:i:s');
                $this->request->data['Booking']['end'] = MyTime::toReadableDateTime($end->getTimestamp(), true);

                $this->Booking->create();
                if ($this->Booking->save($this->request->data)) {

                    $this->Session->setFlash(__('Die Buchung wurde angenommen'), 'alert', array(
                        'plugin' => 'BoostCake',
                        'class' => 'alert-success'
                    ));

                    $this->request->data['Booking']['id'] = $this->Booking->id;

                    $this->emailAdmin($this->request->data, $room[0], $interval_booking);

                    if ($status == Booking::active) {
                        $this->emailUser_active($this->request->data);
                    } elseif ($status == Booking::planned) {
                        $this->emailUser_planned($this->request->data);
                    }

                    $this->redirect(array('action' => 'view', $this->Booking->id));
                    return true;
                }
            } else {
                $me = ($this->Auth->user('id') == $blocked[0]['User']['id']);

                $m = $me ? __('Sie haben den Raum bereits für diesen Zeitraum gebucht') : sprintf(__('Zu diesem Zeitpunkt ist der Raum bereits durch %s gebucht'), $blocked[0]['User']['username']);

                $this->Session->setFlash($m, 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-danger'
                ));
                return false;
            }
            $this->Session->setFlash(__('Die Buchung konnte nicht gespeichert werden. Versuchen Sie es erneut'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-danger'
            ));
        }
        return true;
    }

    public function edit($id = null) {
        $this->Booking->id = $id;
        if (!$this->Booking->exists()) {
            throw new NotFoundException(__('Buchung nicht gefunden'));
        }

        if ($this->request->is('post') || $this->request->is('put')) {

            $groups = array();
            if (array_key_exists('submit_all', $this->request->data)) {
                $hasErrorInIntervalLoop = false;

                if ($this->request->data['Booking']['group_id'] != 0) {
                    $groups = $this->Booking->getBookingsGroupNames($this->request->data['Booking']['group_id']);
                    $this->set(compact('groups'));
                }

                $diff = 0;
                foreach ($groups as $group) {
                    if ($group['Booking']['id'] == $id) {

                        $group_start = new DateTime($group['Booking']['startdatetime']);
                        $group_end = new DateTime($group['Booking']['enddatetime']);

                        $diff = Utils::getDiffInMin($group_start, $group_end);
                    }
                }

                foreach ($groups as $group) {
                    if ($group['Booking']['id'] != $id) {

                        $group_start = new DateTime($group['Booking']['startdatetime']);
                        $group_start_new = clone $group_start;
                        $group_start_new->modify($diff . ' minutes');
                        $group['Booking']['startdatetime_old'] = $group['Booking']['startdatetime'];
                        $group['Booking']['startdatetime'] = $group_start_new->format('Y-m-d H:i:s');

                        $group_end = new DateTime($group['Booking']['enddatetime']);
                        $group_end_new = clone $group_end;
                        $group_end_new->modify($diff . ' minutes');
                        $group['Booking']['enddatetime_old'] = $group['Booking']['enddatetime'];
                        $group['Booking']['enddatetime'] = $group_end_new->format('Y-m-d H:i:s');

                        $blocked = array();
                        $group['Booking']['in_use'] = $this->Booking->inUse($group['Booking']['startdatetime'], $group['Booking']['enddatetime'], $this->request->data['Booking']['room_id'], $group['Booking']['id'], true, $blocked);
                        if($group['Booking']['in_use']) {
                            $hasErrorInIntervalLoop = true;
                        }
                        $group['Booking']['blocked'] = $blocked;
                    }
                }

                if($hasErrorInIntervalLoop) {
                    $this->Session->setFlash(__('Diese Buchung kann nicht in dem neuen Zeitraum stattfinden, da dort bereits andere Buchungen sind.'), 'alert', array(
                        'plugin' => 'BoostCake',
                        'class' => 'alert-danger'
                    ));
                    return false;
                }
            }

            $blocked = array();
            if ($this->Booking->inUse($this->request->data['Booking']['startdatetime'], $this->request->data['Booking']['enddatetime'], $this->request->data['Booking']['room_id'], $id, true, $blocked)) {
                $this->Session->setFlash(__('Diese Buchung kann nicht in dem neuen Zeitraum stattfinden, da dort bereits andere Buchungen sind.'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-danger'
                ));
                return false;
            }

            if ($this->Booking->save($this->request->data)) {
                if (array_key_exists('submit_all', $this->request->data)) {

                    foreach ($groups as $group) {
                        if ($group['Booking']['id'] != $id) {
                            $this->edit_silent($group['Booking']['id'], $this->request->data['Booking']['room_id'], $this->request->data['Booking']['name'], $group['Booking']['startdatetime'], $group['Booking']['enddatetime']);
                        }
                    }

                    $this->Session->setFlash(__('Die Buchungen wurden geändert'), 'alert', array(
                        'plugin' => 'BoostCake',
                        'class' => 'alert-success'
                    ));
                } else {
                    $this->Session->setFlash(__('Die Buchung wurde geändert'), 'alert', array(
                        'plugin' => 'BoostCake',
                        'class' => 'alert-success'
                    ));
                }
                $this->redirect(array('action' => 'index'));
                return true;
            }
            $this->Session->setFlash(__('Die Buchung konnte nicht geändert werden'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-danger'
            ));
            return false;
        }

        $this->view($id);
        return true;
    }

    public function delete($type = 'id', $id = null) {
        switch ($type) {
            default:
            case 'id':

                $this->Booking->id = $id;
                if (!$this->Booking->exists()) {
                    throw new NotFoundException(__('Buchung nicht gefunden'));
                }
                if ($this->Booking->delete()) {
                    $this->Session->setFlash(__('Buchung storniert'), 'alert', array(
                        'plugin' => 'BoostCake',
                        'class' => 'alert-success'
                    ));
                    $this->redirect(array('action' => 'index'));
                    return true;
                }
                $this->Session->setFlash(__('Buchung konnte nicht gelöscht werden'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-danger'
                ));
                break;

            case 'group':

                $groups = $this->Booking->getBookingsGroupNames($id);
                if (count($groups) > 0) {
                    $count = 0;
                    foreach ($groups as $group) {
                        if ($this->delete_silent($group['Booking']['id']))
                            $count++;
                    }
                    $this->Session->setFlash(sprintf(__('Es wurden %d Buchungen erfolgreich gelöscht'), $count), 'alert', array(
                        'plugin' => 'BoostCake',
                        'class' => 'alert-success'
                    ));
                    $this->redirect(array('action' => 'index'));
                    return true;
                }
                $this->Session->setFlash(__('Buchungen konnten nicht gelöscht werden'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-danger'
                ));
                break;

        }
        return false;
    }

    public function deny($id = null) {
        $this->Booking->id = $id;
        if (!$this->Booking->exists()) {
            throw new NotFoundException(__('Buchung nicht gefunden'));
        }
        $this->Booking->set('status', Booking::active_denied);
        if ($this->Booking->save()) {
            $this->Session->setFlash(__('Buchung verweigert'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-success'
            ));

            $this->emailUser_deny($this->Booking->getAll($id)[0]);

            $this->redirect(array('action' => 'index'));
            return true;
        }
        $this->Session->setFlash(__('Buchung konnte nicht verweigert werden'), 'alert', array(
            'plugin' => 'BoostCake',
            'class' => 'alert-danger'
        ));
        return false;
    }

    public function reject($id = null) {
        $this->Booking->id = $id;
        if (!$this->Booking->exists()) {
            throw new NotFoundException(__('Buchung nicht gefunden'));
        }
        $this->Booking->set('status', Booking::planning_rejected);
        if ($this->Booking->save()) {
            $this->Session->setFlash(__('Buchung abgelehnt'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-success'
            ));

            $this->emailUser_reject($this->Booking->getAll($id)[0]);

            $this->redirect(array('action' => 'index'));
            return true;
        }
        $this->Session->setFlash(__('Buchung konnte nicht abgelehnt werden'), 'alert', array(
            'plugin' => 'BoostCake',
            'class' => 'alert-danger'
        ));
        return false;
    }

    public function view($id = null) {
        $this->request->data = $this->Booking->getAll($id)[0];

        if (!isset($this->request->data) || count($this->request->data) == 0) {
            throw new NotFoundException(__('Buchung nicht gefunden'));
        }

        $this->beforeDetailDisplay();

        if ($this->request->data['Booking']['group_id'] != 0) {
            $groups = $this->Booking->getBookingsGroupNames($this->request->data['Booking']['group_id']);
            $this->set(compact('groups'));
        }
    }

    //</editor-fold>

    /*
     * backend functions
     */

    //<editor-fold defaultstate="collapsed" desc="backend functions">

    public function buildInterval(DateTime $start, DateTime $end, $room_id, $interval_type, $interval_iteration, $interval_value, $approval_horizon, $approval_horizon_max_date) {
        $interval_count = 0;
        switch ($interval_type) {
            case 'A': // after
                $interval_count = $interval_value['interval_count'];
                // $interval_end = (new DateTime($end->format('Y-m-d H:i:s')))->modify('+' . $interval_iteration * $interval_count . ' day');
                break;
            case 'B': // date
                $interval_end = new DateTime($interval_value['interval_end']);
                $interval_count = $this->getIntervalCountFromEndDate($end, $interval_end, $interval_iteration);
                break;
            case 'C': // semester/year
                $interval_range = $interval_value['interval_range'];
                $interval_end = null;
                $this->loadModel('Semester');

                switch ($interval_range) {

                    case '1': // semester end
                        $semester = $this->Semester->getActiveSemester();

                        if (!$semester) {
                            $semester = $this->Semester->getNextSemester();
                            $interval_end = new DateTime($semester['Semester']['start'] . ' 23:59:59');
                        } else {
                            $interval_end = new DateTime($semester['Semester']['end'] . ' 23:59:59');
                        }

                        break;
                    case '2': // next semester start
                        $semester = $this->Semester->getNextSemester();

                        $interval_end = new DateTime($semester['Semester']['start'] . ' 23:59:59');
                        $interval_end->modify('-1 day');
                        break;
                    case '3': // next semester end
                        $semester = $this->Semester->getNextSemester();

                        $interval_end = new DateTime($semester['Semester']['end'] . ' 23:59:59');
                        break;
                    case '4': // year
                        $interval_end = new DateTime(date('Y') . '-12-31 23:59:59');
                        break;
                }
                $interval_count = $this->getIntervalCountFromEndDate($end, $interval_end, $interval_iteration, true);
                break;
        }

        $interval_booking = array();
        $blocked = array();
        for ($i = 1; $i <= $interval_count; $i++) {

            $interval_start_date = clone $start;
            $interval_start_date->modify('+' . $interval_iteration * $i . ' day');
            $interval_booking[$i]['start_date'] = $interval_start_date;

            $interval_end_date = clone $end;
            $interval_end_date->modify('+' . $interval_iteration * $i . ' day');
            $interval_booking[$i]['end_date'] = $interval_end_date;

            $interval_booking[$i]['status'] = $this->getStatusFromDate($approval_horizon, $interval_end_date, $approval_horizon_max_date);

            $interval_booking[$i]['in_use'] = $this->Booking->inUse($interval_start_date->format('Y-m-d H:i:s'), $interval_end_date->format('Y-m-d H:i:s'), $room_id, 0, true, $blocked);
            $interval_booking[$i]['blocked'] = $blocked;
        }

        return $interval_booking;
    }

    public function cleanUp() {
        $organizationalunits = $this->Booking->Room->Organizationalunit->getAll();

        $bookings = $this->Booking->find('all', array(
            'conditions' => array('Booking.status !=' => Booking::archived)
        ));

        $concurred = array();

        $inConcurredList = function ($booking_id, $concurred_list) {

            foreach ($concurred_list as $concurred) {
                foreach ($concurred as $value) {
                    if ($booking_id == $value['Booking']['id'])
                        return true;
                }
            }

            return false;
        };

        foreach ($bookings as $booking) {

            $start = new DateTime($booking['Booking']['startdatetime']);
            $end = new DateTime($booking['Booking']['enddatetime']);
            $now = new DateTime();
            $now->format('Y-m-d H:i:s');
            $status = $booking['Booking']['status'];
            $organizational_unit_id = $booking['Room']['organizationalunit_id'];

            $approval_horizon = 0;
            $approval_automatic = 0;

            foreach ($organizationalunits as $organizationalunit) {
                if ($organizationalunit['Organizationalunit']['id'] == $organizational_unit_id) {
                    $approval_horizon = $organizationalunit['Organizationalunit']['approval_horizon'];
                    $approval_automatic = $organizationalunit['Organizationalunit']['approval_automatic'];

                    break;
                }
            }

            $approval_horizon_max_date = new DateTime();
            $approval_horizon_max_date->modify('+' . $approval_horizon . ' week');

            if ($end < $now) {
                $this->edit_silent_status($booking['Booking']['id'], Booking::archived);
            } else if ($approval_automatic && ($status == Booking::planned) && $this->isBefore($start, $approval_horizon_max_date) && !$inConcurredList($booking['Booking']['id'], $concurred)) {
                $blocked = array();
                if ($this->Booking->inUse($booking['Booking']['startdatetime'], $booking['Booking']['enddatetime'], $booking['Booking']['room_id'], $booking['Booking']['id'], false, $blocked)) {
                    $blocked_block = array($booking);
                    foreach ($blocked as $value) {
                        $this->edit_silent_status($value['Booking']['id'], Booking::planning_concurred);
                        $blocked_block[] = $value;
                    }
                    $this->edit_silent_status($booking['Booking']['id'], Booking::planning_concurred);

                    $concurred[] = $blocked_block;
                } else {
                    $this->edit_silent_status($booking['Booking']['id'], Booking::active);
                    $this->emailUser_plan_to_active($booking);
                }
            }
        }

        if (count($concurred) > 0) {
            // TODO: send email to admin
        }

        return $bookings;
    }

    //</editor-fold>

    /*
     * helper functions
     */

    //<editor-fold defaultstate="collapsed" desc="helper functions">

    private function add_silent($room_id, $user_id, $group_id, $name, $status, $startdatetime, $enddatetime, &$id) {
        $this->Booking->create();
        $this->Booking->set('room_id', $room_id);
        $this->Booking->set('user_id', $user_id);
        $this->Booking->set('group_id', $group_id);
        $this->Booking->set('name', $name);
        $this->Booking->set('status', $status);
        $this->Booking->set('startdatetime', $startdatetime);
        $this->Booking->set('enddatetime', $enddatetime);

        if ($this->Booking->save()) {
            $id = $this->Booking->id;
            $this->Booking->clear(); // needed?
            return true;
        }
        return false;
    }

    private function edit_silent($id, $room_id, $name, $startdatetime, $enddatetime) {
        $this->Booking->id = $id;
        $this->Booking->set('room_id', $room_id);
        $this->Booking->set('name', $name);
        $this->Booking->set('startdatetime', $startdatetime);
        $this->Booking->set('enddatetime', $enddatetime);

        if ($this->Booking->save()) {
            $this->Booking->clear(); // needed?
            return true;
        }
        return false;
    }

    private function edit_silent_status($id, $status) {
        $this->Booking->id = $id;
        $this->Booking->set('status', $status);

        if ($this->Booking->save()) {
            $this->Booking->clear(); // needed?
            return true;
        }
        return false;
    }

    private function delete_silent($id) {
        $this->Booking->id = $id;

        if ($this->Booking->delete()) {
            $this->Booking->clear(); // needed?
            return true;
        }
        return false;
    }

    /*
    * $this->emailAdmin($this->request->data, $room[0], $interval_booking);
    */

    private function emailAdmin($data, $room, $interval_booking) {
        $admins = $this->Booking->User->getUsersFromOrganizationalUnitId($room['Room']['organizationalunit_id']);

        foreach ($admins as $admin) {

            if (($admin['User']['role'] == 'admin') && (($admin['User']['admin_email_every_booking']) || ($admin['User']['admin_email_every_booking_plan']))) {
                if (!Validation::email($admin['User']['emailaddress'])) {
                    $this->Session->setFlash(__('Es wurde keine E-Mail an einen Verwalter dieses Raumes geschickt, weil dieser keine E-Mail-Adresse in seinen Profileinstellungen hinterlegt hat. Informieren Sie ihn Bitte darüber'), 'alert', array(
                        'plugin' => 'BoostCake',
                        'class' => 'alert-info'
                    ), 'info');
                    break;
                }

                $title = Configure::read('display.Short') . ': ';

                if ($admin['User']['admin_email_every_booking']) {
                    $title .= 'Reservering von ' . $this->Session->read('Auth.User.username') . ' für ' . $room['Room']['name'] . ' am ' . $data['Booking']['start'];

                    if (Configure::read('debug') > 2) {
                        $this->layout = 'emails/text/default';
                        $this->set('room', $room);
                        $this->set('admin', $admin);
                        $this->set('data', $data);
                        $this->set('email_heading', 'Welcome to My App');
                        $this->set('interval_booking', $interval_booking);
                        return $this->render('/emails/text/admin_active');
                    } else {
                        $email = new CakeEmail('smtp');
                        $email->template('admin_active', 'default')
                            ->replyTo(Configure::read('display.Support'))
                            ->to($admin['User']['emailaddress'])
                            ->subject($title)
                            ->viewVars(array('data' => $data, 'admin' => $admin, 'room' => $room, 'interval_booking' => $interval_booking))
                            ->helpers(array('Html', 'Text', 'Time', 'MyTime'))
                            ->send();
                    }

                } elseif (isset($interval_booking)) {
                    $hasplan = false;
                    $first_plan = null;

                    foreach ($interval_booking as $value) {

                        if ($value['status'] == Booking::planned) {
                            $hasplan = true;
                            $first_plan = $value;
                            break;
                        }
                    }

                    if ($hasplan) {
                        $title .= 'Planungsreservierung von ' . $this->Session->read('Auth.User.username') . ' für ' . $room['Room']['name'] . ' beginnend mit ' . $first_plan['start_date'];

                        if (Configure::read('debug') > 2) {
                            $this->layout = 'emails/text/default';
                            $this->set('room', $room);
                            $this->set('admin', $admin);
                            $this->set('data', $data);
                            $this->set('email_heading', 'Welcome to My App');
                            $this->set('interval_booking', $interval_booking);
                            return $this->render('/emails/text/admin_planed');
                        } else {
                            $Email = new CakeEmail('smtp');
                            $Email->template('admin_planed', 'default')
                                ->replyTo(Configure::read('display.Support'))
                                ->to($admin['User']['emailaddress'])
                                ->subject($title)
                                ->viewVars(array('data' => $data, 'admin' => $admin, 'room' => $room, 'interval_booking' => $interval_booking))
                                ->helpers(array('Html', 'Text', 'Time', 'MyTime'))
                                ->send();
                        }

                    }
                }
            }


        }

        return true;
    }

    private function emailUser_active($data) {

        $title = Configure::read('display.Short') . ': ';

        if ($data['User']['user_email_if_active']) {

            $title .= 'Erstellung und Freigabe der Buchung für den ' . MyTime::toReadableDateTime(strtotime($data['Booking']['startdatetime']), true);

            if (Configure::read('debug') > 2) {
                $this->layout = 'emails/text/default';
                $this->set('data', $data);
                return $this->render('/emails/text/user_active');
            } else {
                $email = new CakeEmail('smtp');
                $email->template('user_active', 'default')
                    ->replyTo(Configure::read('display.Support'))
                    ->to($data['User']['emailaddress'])
                    ->subject($title)
                    ->viewVars(array('data' => $data))
                    ->helpers(array('Html', 'Text', 'Time', 'MyTime'))
                    ->send();
            }

        }
        return true;
    }

    private function emailUser_planned($data) {

        $title = Configure::read('display.Short') . ': ';

        if ($data['User']['user_email_if_planned']) {

            $title .= 'Erstellung und Planung der Buchung für den ' . MyTime::toReadableDateTime(strtotime($data['Booking']['startdatetime']), true);

            if (Configure::read('debug') > 2) {
                $this->layout = 'emails/text/default';
                $this->set('data', $data);
                return $this->render('/emails/text/user_planned');
            } else {
                $email = new CakeEmail('smtp');
                $email->template('user_planned', 'default')
                    ->replyTo(Configure::read('display.Support'))
                    ->to($data['User']['emailaddress'])
                    ->subject($title)
                    ->viewVars(array('data' => $data))
                    ->helpers(array('Html', 'Text', 'Time', 'MyTime'))
                    ->send();
            }

        }
        return true;
    }

    private function emailUser_plan_to_active($data) {

        $title = Configure::read('display.Short') . ': ';

        if ($data['User']['user_email_if_plan_gets_active']) {

            $title .= 'Freigabe der Buchung vom ' . MyTime::toReadableDateTime(strtotime($data['Booking']['startdatetime']), true);

            if (Configure::read('debug') > 2) {
                $this->layout = 'emails/text/default';
                $this->set('data', $data);
                return $this->render('/emails/text/user_approval');
            } else {
                $email = new CakeEmail('smtp');
                $email->template('user_approval', 'default')
                    ->replyTo(Configure::read('display.Support'))
                    ->to($data['User']['emailaddress'])
                    ->subject($title)
                    ->viewVars(array('data' => $data))
                    ->helpers(array('Html', 'Text', 'Time', 'MyTime'))
                    ->send();
            }

        }
        return true;
    }

    private function emailUser_deny($data) {

        $title = Configure::read('display.Short') . ': ';

        if ($data['User']['user_email_if_active_gets_rejected']) {

            $title .= 'Verweigerung der Buchung vom ' . MyTime::toReadableDateTime(strtotime($data['Booking']['startdatetime']), true);

            if (Configure::read('debug') > 2) {
                $this->layout = 'emails/text/default';
                $this->set('data', $data);
                return $this->render('/emails/text/user_deny');
            } else {
                $email = new CakeEmail('smtp');
                $email->template('user_deny', 'default')
                    ->replyTo(Configure::read('display.Support'))
                    ->to($data['User']['emailaddress'])
                    ->subject($title)
                    ->viewVars(array('data' => $data))
                    ->helpers(array('Html', 'Text', 'Time', 'MyTime'))
                    ->send();
            }

        }
        return true;
    }

    private function emailUser_reject($data) {

        $title = Configure::read('display.Short') . ': ';

        if ($data['User']['user_email_if_plan_gets_rejected']) {

            $title .= 'Ablehnung der Planung vom ' . MyTime::toReadableDateTime(strtotime($data['Booking']['startdatetime']), true);

            if (Configure::read('debug') > 2) {
                $this->layout = 'emails/text/default';
                $this->set('data');
                return $this->render('/emails/text/user_reject');
            } else {
                $email = new CakeEmail('smtp');
                $email->template('user_reject', 'default')
                    ->replyTo(Configure::read('display.Support'))
                    ->to($data['User']['emailaddress'])
                    ->subject($title)
                    ->viewVars(array('data' => $data))
                    ->helpers(array('Html', 'Text', 'Time', 'MyTime'))
                    ->send();
            }

        }
        return true;
    }


    private function getIntervalCountFromEndDate(DateTime $end, DateTime $interval_end, $interval_iteration, $interval_precise_end = false) {
        $days_diff = $interval_end->diff($end)->days;

        if ($interval_precise_end)
            return (int)floor($days_diff / $interval_iteration);
        else
            return (int)ceil($days_diff / $interval_iteration);
    }

    /**
     * @param $approval_horizon
     * @param DateTime $interval_date
     * @param DateTime $max_date
     * @return string
     */
    private function getStatusFromDate($approval_horizon, DateTime $interval_date, DateTime $max_date) {
        if (is_null($approval_horizon) || ($approval_horizon == '-1'))
            return Booking::active;
        elseif ($approval_horizon == '0')
            return Booking::planned;
        else {
            return $this->isBefore($interval_date, $max_date) ? Booking::active : Booking::planned;
        }
    }

    /**
     * @param DateTime $before
     * @param DateTime $after
     * @return bool
     */
    private function isBefore(DateTime $before, DateTime $after) {
        return date($before->format('Y-m-d')) < date($after->format('Y-m-d'));
    }

    //</editor-fold>

}