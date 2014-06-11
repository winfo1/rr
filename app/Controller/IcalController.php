<?php

App::uses('AppController', 'Controller');

class IcalController extends AppController
{
    var $helpers = array('Html', 'Text', 'ICal');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('index');
    }

    public function beforeRender() {
        $this->autoLayout = false;
        $this->layout = 'ajax';
    }

    public function index($filterType = 'all', $filterID = '0') {
        $this->loadModel('Booking');
        $bookings = $this->Booking->getBookings($filterType, $filterID);
        $this->set(compact('bookings'));
    }
}
?>