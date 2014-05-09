<?php
App::uses('Booking', 'Model');

App::uses('CakeEmail', 'Network/Email');
App::uses('Validation', 'Utility');

App::import('Lib', 'Utils');

class BookingsController extends AppController
{
    /*
     * basic functions
     */

    //<editor-fold defaultstate="collapsed" desc="basic functions">

    public $components = array('Paginator');

    public $paginate = array(
        'limit' => 25,
        'order' => array('Booking.startdatetime' => 'asc')
    );

    public function beforeFilter()
    {
        parent::beforeFilter();

        $this->Auth->allow('beforeDetailDisplay', 'getBookings', 'getBookingsNames', 'view',                       'cleanUp', 'isBefore', 'edit_silent_status');
    }

    public function isAuthorized($user) {

        if(in_array($this->action, array('add'))) {
            return true;
        }

        if(in_array($this->action, array('edit', 'delete'))) {
            if($this->action == 'delete')
                $id = $this->request->params['pass'][1];
            else
                $id = $this->request->params['pass'][0];

            if ($this->Booking->isOwnedBy($id, $this->Session->read('Auth.User.id')))
                return true;
        }

        if ($user['role'] == 'admin') {

            if($this->action === 'add')
                return true;

            if(in_array($this->action, array('accept', 'edit', 'reject')))
            {
                $id = $this->request->params['pass'][0];

                if ($this->Booking->isOwnedThroughOrganizationalUnitBy($id, $this->Session->read('Auth.User.organizationalunit_id')))
                    return true;
            }
        }

        return parent::isAuthorized($user);
    }
    
    public function beforeDetailDisplay() {

        $rooms_all = $this->requestAction('/rooms/getRooms');
        $this->set(compact('rooms_all'));
        $rooms = $this->requestAction('/rooms/getRoomsAsRoomList', array('pass' => array($rooms_all)));
        $this->set(compact('rooms'));
    }

    //</editor-fold>

    /*
     * view functions
     */

    //<editor-fold defaultstate="collapsed" desc="view functions">

    public function index($view = null) {
        if(isset($view) && ($view == 'table')) {
            $bookings = $this->Paginator->paginate('Booking');
            $this->set(compact('bookings'));
        }
    }

    public function accept($id = null)
    {
        $this->Booking->id = $id;
        if (!$this->Booking->exists()) {
            throw new NotFoundException(__('Buchung nicht gefunden'));
        }

        $this->request->data = $this->Booking->find('first', array(
            'conditions' => array('Booking.id' => $id),
            'contain' => array('Room', 'User')
        ));

        $blocked = array();

        if (!$this->Booking->inUse($this->request->data['Booking']['startdatetime'], $this->request->data['Booking']['enddatetime'], $this->request->data['Booking']['room_id'], 0, true, $blocked)) {
            $this->Booking->set('status', Booking::active);

            if ($this->Booking->save()) {
                $this->Session->setFlash(__('Die Buchung wurde aktiviert'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
                return $this->redirect(array('action' => 'index'));
            }
        } else {
            $me = ($this->Auth->user('id') == $blocked[0]['User']['id']);

            $m = $me ? __('Sie haben den Raum bereits für diesen Zeitraum gebucht') : sprintf(__('Zu diesem Zeitpunkt ist der Raum bereits durch %s gebucht'), $blocked[0]['User']['username']);

            $this->Session->setFlash($m, 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-danger'
            ));
            return $this->redirect(array('action' => 'index'));
        }

        $this->Session->setFlash(__('Die Buchung konnte nicht aktiviert werden'), 'alert', array(
            'plugin' => 'BoostCake',
            'class' => 'alert-danger'
        ));
        return $this->redirect(array('action' => 'index'));
    }

    /**
     * @param null $room_id          i.e. 1
     * @param null $str_day          i.e. 2014-05-01
     * @param null $str_start_time   i.e. 19-14
     * @param null $str_end_time     i.e. 20-14
     */
    public function add($room_id = null, $str_day = null, $str_start_time = null, $str_end_time = null)
    {
        // set available rooms
        $rooms = $this->requestAction('/rooms/getRoomsAsRoomList');
        $this->set(compact('rooms'));

        // set default room
        $rooms_keys = array_keys($rooms);
        if (!isset($room_id) || !in_array($room_id, $rooms_keys)) {
            if (count($rooms) > 0)
                $room_id = $rooms_keys[0];
            else
                $room_id = 0;
        }
        $this->set(compact('room_id'));

        // easy view
        $view_tabs = 's';

        // set default day
        if (isset($str_day) ) {
            try {
                $day = (new DateTime($str_day))->format('Y-m-d');
                $view_tabs = 'a';
            } catch (\Exception $e) {
                $day = (new DateTime())->format('Y-m-d');
            }
        } else {
            $day = (new DateTime())->format('Y-m-d');
        }
        $this->set(compact('day'));

        // set default start time
        if (isset($str_start_time) && preg_match("/^[0-9]{1,2}-[0-9]{1,2}$/", $str_start_time)) {
            $start_hour = str_replace('-', ':', $str_start_time);
            $view_tabs = 'a';
        } else {
            $start_hour = (new DateTime())->format('H:i');
        }
        $this->set(compact('start_hour'));

        // set default end time
        if (isset($str_end_time) && preg_match("/^[0-9]{1,2}-[0-9]{1,2}$/", $str_end_time)) {
            $end_hour = str_replace('-', ':', $str_end_time);
            $view_tabs = 'a';
        } else {
            $end_hour = Utils::toEndDateTime(new DateTime(), 60)->format('H:i');
        }
        $this->set(compact('end_hour'));

        // set view
        $this->set(compact('view_tabs'));

        if ($this->request->is('post')) {

            $room_id = $this->request->data['Booking']['room_id'];

            if($this->request->data['Booking']['view_tabs'] == 's') {
                // simple booking-time selection
                $start = (new DateTime())->modify('+' . $this->request->data['Booking']['start_minutes'] . ' minutes');
                $end = Utils::toEndDateTime($start, $this->request->data['Booking']['duration'], $this->request->data['Booking']['duration']);
            } else {
                // advanced booking-time selection
                $day = $this->request->data['Booking']['day'];
                $start = Utils::toDateTime($day, $this->request->data['Booking']['start_hour']);
                $end = Utils::toDateTime($day, $this->request->data['Booking']['end_hour']);
                $this->request->data['Booking']['duration'] = strval(Utils::getDiffInMin($start, $end));
            }

            $room = $this->requestAction('/rooms/getRooms/' . $room_id);
            $approval_horizon = $room[0]['Organizationalunit']['approval_horizon'];
            $approval_horizon_max_date = (new DateTime())->modify('+' . $approval_horizon . ' week');

            $interval_booking = null;
            $interval_iteration = $this->request->data['Booking']['interval_iteration'];

            $group_id = 0;
            if ($interval_iteration) {
                $group_id = $this->Booking->getNextAutoIncrement();

                $interval_type = $this->request->data['Booking']['interval_type'];
                $interval_count = 0;

                switch ($interval_type) {

                    case 'A': // after
                        $interval_count = $this->request->data['Booking']['interval_count'];

                        $interval_end = (new DateTime($end->format('Y-m-d H:i:s')))->modify('+' . $interval_iteration * $interval_count . ' day');
                        break;
                    case 'B': // date
                        $interval_end = new DateTime($this->request->data['Booking']['interval_end']);
                        $interval_count = $this->getIntervalCountFromEndDate($end, $interval_end, $interval_iteration);
                        break;
                    case 'C': // semester/year
                        $interval_range = $this->request->data['Booking']['interval_range'];
                        $interval_end = null;

                        switch ($interval_range) {

                            case '1': // semester end
                                $semester = $this->requestAction('/semesters/getActiveSemester');

                                if (!$semester) {
                                    $semester = $this->requestAction('/semesters/getNextSemester');
                                    $interval_end = new DateTime($semester['Semester']['start'] . ' 23:59:59');
                                } else {
                                    $interval_end = new DateTime($semester['Semester']['end'] . ' 23:59:59');
                                }

                                break;
                            case '2': // next semester start
                                $semester = $this->requestAction('/semesters/getNextSemester');

                                $interval_end = new DateTime($semester['Semester']['start'] . ' 23:59:59');
                                $interval_end->modify('-1 day');
                                break;
                            case '3': // next semester end
                                $semester = $this->requestAction('/semesters/getNextSemester');

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
                $hasErrorInIntervalLoop = false;
                $blocked = array();

                for ($i = 1; $i <= $interval_count; $i++) {

                    $interval_start_date = clone $start;
                    $interval_start_date->modify('+' . $interval_iteration * $i . ' day');
                    $interval_booking[$i]['start_date'] = $interval_start_date;

                    $interval_end_date = clone $end;
                    $interval_end_date->modify('+' . $interval_iteration * $i . ' day');
                    $interval_booking[$i]['end_date'] = $interval_end_date;

                    $interval_booking[$i]['status'] = $this->getStatusFromDate($approval_horizon, $interval_end_date, $approval_horizon_max_date);

                    if ($this->Booking->inUse($interval_start_date->format('Y-m-d H:i:s'), $interval_end_date->format('Y-m-d H:i:s'), $room_id, 0, true, $blocked)) {
                        $hasErrorInIntervalLoop = true;
                        break;
                    }
                }

                if (!$hasErrorInIntervalLoop) {
                    for ($i = 1; $i <= $interval_count; $i++) {

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
                } else {
                    if (count($blocked) == 1) {
                        $me = ($this->Auth->user('id') == $blocked[0]['User']['id']);

                        $m = $me ? __('Sie haben den Raum bereits für diesen Zeitraum gebucht') : sprintf(__('Zu diesem Zeitpunkt ist der Raum bereits durch %s gebucht'), $blocked[0]['User']['username']);

                        return $this->Session->setFlash($m, 'alert', array(
                            'plugin' => 'BoostCake',
                            'class' => 'alert-danger'
                        ));
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

                        return $this->Session->setFlash($m, 'alert', array(
                            'plugin' => 'BoostCake',
                            'class' => 'alert-danger'
                        ));
                    }
                }
            }

            $blocked = array();

            if (!$this->Booking->inUse($start->format('Y-m-d H:i:s'), $end->format('Y-m-d H:i:s'), $room_id, 0, true, $blocked)) {
                $this->request->data['Booking']['user_id'] = $this->Auth->user('id');
                $this->request->data['Booking']['group_id'] = $group_id;
                $this->request->data['Booking']['status'] = $this->getStatusFromDate($approval_horizon, $end, $approval_horizon_max_date);
                $this->request->data['Booking']['startdatetime'] = $start->format('Y-m-d H:i:s');
                $val = strftime('%d %B %Y - %H:%M', $start->getTimestamp());
                if(WIN)
                    $val = utf8_encode($val);
                $this->request->data['Booking']['start'] = $val;
                $this->request->data['Booking']['enddatetime'] = $end->format('Y-m-d H:i:s');
                $val = strftime('%d %B %Y - %H:%M', $end->getTimestamp());
                if(WIN)
                    $val = utf8_encode($val);
                $this->request->data['Booking']['end'] = $val;

                $this->Booking->create();
                if ($this->Booking->save($this->request->data)) {
                	
                    $this->Session->setFlash(__('Die Buchung wurde angenommen'), 'alert', array(
                        'plugin' => 'BoostCake',
                        'class' => 'alert-success'
                    ));
                    
                    $this->emailAdmin($this->Booking->id, $this->request->data, $room[0], $interval_booking);

                    return $this->redirect(array('controller' => 'pages', 'action' => 'display', 'home'));
                }
            } else {
                $me = ($this->Auth->user('id') == $blocked[0]['User']['id']);

                $m = $me ? __('Sie haben den Raum bereits für diesen Zeitraum gebucht') : sprintf(__('Zu diesem Zeitpunkt ist der Raum bereits durch %s gebucht'), $blocked[0]['User']['username']);

                return $this->Session->setFlash($m, 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-danger'
                ));
            }
            $this->Session->setFlash(__('Die Buchung konnte nicht gespeichert werden. Versuchen Sie es erneut'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-danger'
            ));
        }
    }

    public function edit($id = null) {
        $this->Booking->id = $id;
        if (!$this->Booking->exists()) {
            throw new NotFoundException(__('Buchung nicht gefunden'));
        }
        
        if ($this->request->is('post') || $this->request->is('put')) {

            if (array_key_exists('submit_all', $this->request->data)) {
                $hasErrorInIntervalLoop = false;

				$groups = array();
        		if ($this->request->data['Booking']['group_id'] != 0) {
            		$groups = $this->getBookingsGroupNames($this->request->data['Booking']['group_id']);
            		$this->set(compact('groups'));
        		}

                $blocked = array();
                foreach ($groups as $group) {
                    if ($group['Booking']['id'] != $id) {
                        // what to do when not first !!

                        $group_start = new DateTime($group['Booking']['startdatetime']);

                        $group_end = new DateTime($group['Booking']['enddatetime']);

                        // $group['Booking']['startdatetime']

                        if ($this->Booking->inUse($group['Booking']['startdatetime'], $group['Booking']['enddatetime'], $this->request->data['Booking']['room_id'], $group['Booking']['id'], true, $blocked))
                        {
                            // TODO: group edit
                        }
                    }
                }
            }

            $blocked = array();
            if ($this->Booking->inUse($this->request->data['Booking']['startdatetime'], $this->request->data['Booking']['enddatetime'], $this->request->data['Booking']['room_id'], $id, true, $blocked))
            {
                return $this->Session->setFlash(__('Diese Buchung kann nicht in dem neuen Zeitraum stattfinden, da dort bereits andere Buchungen sind.'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-danger'
                ));
            }

            if ($this->Booking->save($this->request->data)) {
                if (array_key_exists('submit_all', $this->request->data)) {


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
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(
                __('The user could not be saved. Please, try again.')
            );
        }

        $this->view($id);
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
                    return $this->redirect(array('action' => 'index'));
                }
                $this->Session->setFlash(__('Buchung konnte nicht gelöscht werden'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-danger'
                ));
                break;

            case 'group':

                $groups = $this->getBookingsGroupNames($id);
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
                    return $this->redirect(array('action' => 'index'));
                }
                $this->Session->setFlash(__('Buchungen konnten nicht gelöscht werden'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-danger'
                ));
                break;

        }
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
            return $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Buchung konnte nicht abgelehnt werden'), 'alert', array(
            'plugin' => 'BoostCake',
            'class' => 'alert-danger'
        ));
        return $this->redirect(array('action' => 'index'));
    }

    public function view($id = null) {
        $this->Booking->id = $id;
        if (!$this->Booking->exists()) {
            throw new NotFoundException(__('Buchung nicht gefunden'));
        }

        $this->beforeDetailDisplay();

        $this->request->data = $this->Booking->find('first', array(
            'conditions' => array('Booking.id' => $id),
            'contain' => array('Room', 'User')
        ));

        if ($this->request->data['Booking']['group_id'] != 0) {
            $groups = $this->getBookingsGroupNames($this->request->data['Booking']['group_id']);
            $this->set(compact('groups'));
        }
    }

    //</editor-fold>

    /*
     * backend functions
     */

    //<editor-fold defaultstate="collapsed" desc="backend functions">

    public function getBookings($filterType = 'all', $filterID = '0')
    {
        $conditions = array();

        switch ($filterType) {
            case 'room':
                $conditions['Booking.room_id'] = $filterID;
                break;
            case 'ou':
            case 'organizationalunit':
                $conditions['Room.organizationalunit_id'] = $filterID;
                break;
            default:
                ;
        }

        return $this->Booking->find('all', array(
            'conditions' => $conditions
        ));
    }

    public function getBookingsNames()
    {
        $list = $this->Booking->find('list', array(
            'fields' => array('Booking.Name')
        ));

        return array_unique(array_merge($this->Booking->default_names, $list));
    }

    public function getBookingsGroupNames($group_id)
    {
        $tree = $this->Booking->find('threaded', array(
            'conditions' => array('Booking.group_id' => $group_id),
            'fields' => array('Booking.id', 'Booking.room_id', 'Booking.name', 'Booking.startdatetime', 'Booking.enddatetime'),
            'order' => array('Booking.startdatetime' => 'asc')
        ));

        return $tree;
    }

    public function cleanUp()
    {
        $organizationalunits = $this->requestAction('/organizationalunits/getOrganizationalunits');

        $bookings = $this->Booking->find('all', array(
            'conditions' => array('Booking.status !=' => Booking::archived)
        ));

        $concurred = array();

        $inConcurredList = function ($booking_id, $concurred_list) {

            foreach($concurred_list as $concurred)
            {
                foreach($concurred as $value)
                {
                    if($booking_id == $value['Booking']['id'])
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

            foreach ($organizationalunits as $organizationalunit)
            {
                if($organizationalunit['Organizationalunit']['id'] == $organizational_unit_id)
                {
                    $approval_horizon = $organizationalunit['Organizationalunit']['approval_horizon'];
                    $approval_automatic = $organizationalunit['Organizationalunit']['approval_automatic'];

                    break;
                }
            }

            $approval_horizon_max_date = new DateTime();
            $approval_horizon_max_date->modify('+' . $approval_horizon . ' week');

            if($end < $now)
            {
                $this->edit_silent_status($booking['Booking']['id'], Booking::archived);
            }
            else if($approval_automatic && ($status == Booking::planned) && $this->isBefore($start, $approval_horizon_max_date) && !$inConcurredList($booking['Booking']['id'], $concurred))
            {
                $blocked = array();
                if ($this->Booking->inUse($booking['Booking']['startdatetime'], $booking['Booking']['enddatetime'], $booking['Booking']['room_id'], $booking['Booking']['id'], false, $blocked))
                {
                    $blocked_block = array($booking);
                    foreach ($blocked as $value) {
                        $this->edit_silent_status($value['Booking']['id'], Booking::planning_concurred);
                        $blocked_block[] = $value;
                    }
                    $this->edit_silent_status($booking['Booking']['id'], Booking::planning_concurred);

                    $concurred[] = $blocked_block;
                }
                else
                {
                    $this->edit_silent_status($booking['Booking']['id'], Booking::active);
                }
            }
        }

        if(count($concurred) > 0)
        {
            // TODO: send email to admin
        }

        return $bookings;
    }

    //</editor-fold>

    /*
     * helper functions
     */

    //<editor-fold defaultstate="collapsed" desc="helper functions">

    private function add_silent($room_id, $user_id, $group_id, $name, $status, $startdatetime, $enddatetime, &$id)
    {
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

    private function edit_silent($id, $room_id, $name, $startdatetime, $enddatetime)
    {
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

    private function edit_silent_status($id, $status)
    {
        $this->Booking->id = $id;
        $this->Booking->set('status', $status);

        if ($this->Booking->save()) {
            $this->Booking->clear(); // needed?
            return true;
        }
        return false;
    }

    private function delete_silent($id)
    {
        $this->Booking->id = $id;

        if ($this->Booking->delete()) {
            $this->Booking->clear(); // needed?
            return true;
        }
        return false;
    }

    /*
    * $this->emailAdmin($this->Booking->id, $this->request->data, $room[0], $interval_booking);
    */

    private function emailAdmin($id, $data, $room, $interval_booking)
    {
        $admins = $this->requestAction('/users/getUsersConfig/' . $room['Room']['organizationalunit_id']);

        foreach ($admins as $admin) {

            if (($admin['User']['role'] == 'admin') && (($admin['User']['admin_email_every_booking']) || ($admin['User']['admin_email_every_booking_plan']))) {
                if (!Validation::email($admin['User']['emailaddress'])) {
                    return $this->Session->setFlash(__('Es wurde keine E-Mail an den/die Verwalter dieses Raumes geschickt, weil dieser keine E-Mail-Adresse in seinen Profileinstellungen hinterlegt hat. Informieren Sie ihn Bitte darüber'), 'alert', array(
                        'plugin' => 'BoostCake',
                        'class' => 'alert-info'
                    ), 'info');
                }

                $title = Configure::read('display.Short') . ': ';

                if ($admin['User']['admin_email_every_booking']) {
                    $title .= 'Reservering von ' . $this->Session->read('Auth.User.username') . ' für ' . $room['Room']['name'] . ' am ' . $data['Booking']['start'] . ($data['Booking']['full_time'] ? ' (ganztägig)' : '');

/*

                $this->layout = 'emails/text/default';
                $this->set('id', $id);
                $this->set('interval_booking', $interval_booking);
                $this->set('room', $room);
                $this->set('admin', $admin);
                $this->set('data', $data);
                $this->set('email_heading', 'Welcome to My App');
                return $this->render('/emails/text/admin_active');
                
*/

                    $email = new CakeEmail('smtp');
                    $email->template('admin_active', 'default')
                        ->replyTo(Configure::read('display.Support'))
                        ->to($admin['User']['emailaddress'])
                        ->subject($title)
                        ->viewVars(array('id' => $id, 'data' => $data, 'admin' => $admin, 'room' => $room, 'interval_booking' => $interval_booking))
                        ->helpers(array('Html', 'Text', 'Time'))
                        ->send();
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
                        $title .= 'Planungsreservierung von ' . $this->Session->read('Auth.User.username') . ' für ' . $room['Room']['name'] . ' beginnend mit ' . $first_plan['start_date'] . ($data['Booking']['full_time'] ? ' (ganztägig)' : '');

                        $Email = new CakeEmail('smtp');
                        $Email->template('admin_planed', 'default')
                            ->replyTo(Configure::read('display.Support'))
                            ->to($admin['User']['emailaddress'])
                            ->subject($title)
                            ->viewVars(array('id' => $id, 'data' => $data, 'admin' => $admin, 'room' => $room, 'interval_booking' => $interval_booking))
                            ->helpers(array('Html', 'Text', 'Time'))
                            ->send();
                    }
                }
            }


        }

        return ;
    }

    private function getIntervalCountFromEndDate(DateTime $end, DateTime $interval_end, $interval_iteration, $interval_precise_end = false)
    {
        $days_diff = $interval_end->diff($end)->days;

        if ($interval_precise_end)
            return (int)floor($days_diff / $interval_iteration);
        else
            return (int)ceil($days_diff / $interval_iteration);
    }

    private function getStatusFromDate($approval_horizon, DateTime $interval_date, DateTime $max_date)
    {
        if (is_null($approval_horizon) || ($approval_horizon == '-1'))
            return Booking::active;
        elseif ($approval_horizon == '0')
            return Booking::planned;
        else {
            return $this->isBefore($interval_date, $max_date) ? Booking::active : Booking::planned;
        }
    }

    private function isBefore(DateTime $before, DateTime $after)
    {
        return date($before->format('Y-m-d')) < date($after->format('Y-m-d'));
    }
    //</editor-fold>

}