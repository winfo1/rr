<?php

class ResourcesController extends AppController {
    /*
     * basic functions
     */

    //<editor-fold defaultstate="collapsed" desc="basic functions">

    public $components = array('Paginator');

    public $paginate = array(
        'limit' => 25,
        'order' => array('Resource.name' => 'asc' )
    );

    public function beforeFilter() {

        parent::beforeFilter();

        $this->Auth->allow('getResources', 'getResourcesAsList');

    }

    public function beforeRender() {

        parent::beforeRender();

        $type = $this->Resource->enum('type');
        $this->set(compact('type'));
    }

    //</editor-fold>

    /*
     * view functions
     */

    //<editor-fold defaultstate="collapsed" desc="view functions">

    public function index() {
        $resources = $this->Paginator->paginate('Resource');
        $this->set(compact('resources'));
    }

    public function add() {
        if ($this->request->is('post')) {
            $this->Resource->create();
            if ($this->Resource->save($this->request->data)) {
                $this->Session->setFlash(__('Die Ressource wurde hinzugefügt'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(
                __('The resource could not be saved. Please, try again.')
            );
        }
    }

    public function edit($id = null) {
        $this->Resource->id = $id;
        if (!$this->Resource->exists()) {
            throw new NotFoundException(__('Invalid resource'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Building->save($this->request->data)) {
                $this->Session->setFlash(__('Die Ressource wurde geändert'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(
                __('The resource could not be saved. Please, try again.')
            );
        } else {
            $this->request->data = $this->Resource->read(null, $id);
        }
    }

    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="backend functions">

    function getResources($id = null) {
        if(isset($id) && is_numeric($id)) {
            $condition = array('Resource.id =' => $id);
        } else {
            $condition = array();
        }

        $list = $this->Resource->find('all', array(
            'conditions' => $condition,
            'order' => array('Resource.name' => 'asc' )
        ));

        return $list;
    }

    function getResourcesAsList($data = null) {
        if(!isset($data)) {
            $data = $this->getResources();
        }

        $result = array();

        foreach ($data as $value) {
            $result[$value['Resource']['id']] = $value['Resource']['name'];
        }

        return $result;
    }

    //</editor-fold>
}