<?php

class OrganizationalunitsController extends AppController {
    /*
     * basic functions
     */

    //<editor-fold defaultstate="collapsed" desc="basic functions">

    public $components = array('Paginator');

    public $paginate = array(
        'limit' => 25,
        'order' => array('Organizationalunit.short' => 'asc' )
    );

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('getOrganizationalunits', 'getOrganizationalunitsAsList');
    }

    //</editor-fold>

    public function index() {
        $organizationalunits = $this->Paginator->paginate('Organizationalunit');
        $this->set(compact('organizationalunits'));
    }

    public function add() {
        if ($this->request->is('post')) {
            $this->Organizationalunit->create();
            if ($this->Organizationalunit->save($this->request->data)) {
                $this->Session->setFlash(__('Die Organisationseinheit wurde hinzugefügt'), 'alert', array(
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
        $this->Organizationalunit->id = $id;
        if (!$this->Organizationalunit->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Organizationalunit->save($this->request->data)) {
                $this->Session->setFlash(__('Die Organisationseinheit wurde geändert'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(
                __('The user could not be saved. Please, try again.')
            );
        } else {
            $this->request->data = $this->Organizationalunit->read(null, $id);
        }
    }

    function getOrganizationalunits($id = null)
    {
        if(isset($id) && is_numeric($id))
        {
            $condition = array('Organizationalunit.id =' => $id);
        }
        else
        {
            $condition = array();
        }

        $list = $this->Organizationalunit->find('all', array(
            'conditions' => $condition,
            'order' => array('Organizationalunit.short' => 'asc')
        ));

        return $list;
    }

    function getOrganizationalunitsAsList($short = true)
    {
        $all = $this->getOrganizationalunits();

        $result = array();

        foreach ($all as $organizationalunit)
        {
            if($short)
                $value = $organizationalunit['Organizationalunit']['short'];
            else
                $value = $organizationalunit['Organizationalunit']['name'];

            $result[$organizationalunit['Organizationalunit']['id']] = $value;
        }

        return $result;
    }
}