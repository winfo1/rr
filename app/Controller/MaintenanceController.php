<?php

App::uses('AppController', 'Controller');

class MaintenanceController extends AppController {
    /*
     * basic functions
     */

    //<editor-fold defaultstate="collapsed" desc="basic functions">

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('update');
    }

    //</editor-fold>

    public function update() {

        $this->requestAction('/bookings/cleanUp/');

    }
}