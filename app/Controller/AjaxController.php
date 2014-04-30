<?php

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

        $bookings = $this->requestAction('/bookings/getBookingsNames');

        $this->set(compact('bookings'));
    }

    public function calendar_events($filterType = 'all', $filterID = '0') {

        $bookings = $this->requestAction('/bookings/getBookings', array('pass' => array($filterType, $filterID)));

        $this->set(compact('bookings'));

    }

    public function room_details($id) {

        $details = $this->requestAction('/rooms/getRooms/' . $id);

        $this->set(compact('details'));

    }

}