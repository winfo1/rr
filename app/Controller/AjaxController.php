<?php

App::uses('AppController', 'Controller');

App::import('Lib', 'Utils');

class AjaxController extends AppController {

    /*
     * basic functions
     */

    //<editor-fold defaultstate="collapsed" desc="basic functions">

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('booking_names', 'calendar_events', 'room_details');
    }

    public function beforeRender() {
        $this->autoLayout = false;
        $this->layout = 'ajax';
        
        $this->response->disableCache();
    }
    
    public function isAuthorized($user) {
        return true;
    }

    //</editor-fold>

    public function booking_names() {
        $this->loadModel('Booking');
        $bookings = $this->Booking->getNames();
        $this->set(compact('bookings'));
    }

    public function calendar_events($filterType = 'all', $filterID = '0') {
        $this->loadModel('Booking');
        $bookings = $this->Booking->getBookings($filterType, $filterID);
        $this->set(compact('bookings'));
    }

    public function check_booked() {

        $data = true;

        $room_id = $this->request->data['Booking']['room_id'];

        if ($this->request->data['Booking']['view_tabs'] == 's') {
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

        $this->loadModel('Room');
        $room = $this->Room->getAll($room_id);
        $approval_horizon = $room[0]['Organizationalunit']['approval_horizon'];
        $approval_horizon_max_date = (new DateTime())->modify('+' . $approval_horizon . ' week');

        $interval_booking = null;
        $interval_iteration = $this->request->data['Booking']['interval_iteration'];

        if ($interval_iteration) {
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

            $interval_booking = $this->requestAction('/bookings/buildInterval', array('pass' => array($start, $end, $room_id, $interval_type, $interval_iteration, $interval_value, $approval_horizon, $approval_horizon_max_date)));
            $hasErrorInIntervalLoop = false;
            $blocked = array();

            for ($i = 1; $i <= count($interval_booking); $i++) {

                if ($interval_booking[$i]['in_use']) {
                    $hasErrorInIntervalLoop = true;
                    $blocked = $interval_booking[$i]['blocked'];
                    break;
                }
            }

            $data = !$hasErrorInIntervalLoop;
        }

        $this->set(compact('data'));
    }

    public function room_details($id) {
        $this->loadModel('Room');
        $details = $this->Room->getAll($id);
        $this->set(compact('details'));
    }

}