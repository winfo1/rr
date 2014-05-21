<?php

App::uses('AppController', 'Controller');

class ApplicationController extends AppController {

    /*
     * basic definitions
     */

    //<editor-fold defaultstate="collapsed" desc="basic definitions">

    public $components = array('Paginator');

    public $paginate = array(
        'limit' => 15,
    );

    //</editor-fold>

    /*
     * view functions
     */

    //<editor-fold defaultstate="collapsed" desc="view functions">

    public function index() {
        $this->Paginator->settings = $this->paginate;
        $data = $this->Paginator->paginate($this->modelClass);
        $this->set(compact('data'));
    }

    //</editor-fold>

}