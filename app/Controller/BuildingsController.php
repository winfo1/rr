<?php

class BuildingsController extends AppController {
    /*
     * basic functions
     */

    //<editor-fold defaultstate="collapsed" desc="basic functions">

    public $components = array('Paginator');

    public $paginate = array(
        'limit' => 25,
        'order' => array('Building.short' => 'asc' )
    );

    public function beforeFilter() {

        parent::beforeFilter();

        $this->Auth->allow('getBuildingsAsAll', 'getBuildingsAsList');
    }

    //</editor-fold>

    /*
     * view functions
     */

    //<editor-fold defaultstate="collapsed" desc="view functions">

    public function index() {
        $buildings = $this->Paginator->paginate('Building');
        $this->set(compact('buildings'));
    }

    public function add() {
        if ($this->request->is('post')) {
            $this->Building->create();
            if ($this->Building->save($this->request->data)) {
                $this->Session->setFlash(__('Das Gebäude wurde hinzugefügt'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(
                __('The user could not be saved. Please, try again.')
            );
        }
    }

    public function edit($id = null) {
        $this->Building->id = $id;
        if (!$this->Building->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Building->save($this->request->data)) {
                $this->Session->setFlash(__('Das Gebäude wurde geändert'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(
                __('The user could not be saved. Please, try again.')
            );
        } else {
            $this->request->data = $this->Building->read(null, $id);
        }
    }

    //</editor-fold>

    /*
     * backend functions
     */

    //<editor-fold defaultstate="collapsed" desc="backend functions">

    function getBuildings()
    {
        return $this->Building->find( 'list', array(
            'fields' => array('id', 'short'),
            'order' => array('Building.short' => 'asc' )
        ));
    }

    function getBuildingsAsAll()
    {
        return $this->Building->find('all', array(
            'order' => array('Building.short' => 'asc')
        ));
    }

    function getBuildingsAsList($short = true)
    {
        $all = $this->getBuildingsAsAll();

        $result = array();

        foreach ($all as $building)
        {
            if($short)
                $value = $building['Building']['short'];
            else
                $value = $building['Building']['name'];

            $result[$building['Building']['id']] = $value;
        }

        return $result;
    }

    //</editor-fold>
}