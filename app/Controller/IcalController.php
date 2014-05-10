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

        $bookings = $this->requestAction('/bookings/getBookings', array('pass' => array($filterType, $filterID)));

        $this->set(compact('bookings'));

    }
}
?>