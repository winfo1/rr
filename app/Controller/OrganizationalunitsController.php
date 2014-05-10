<?php

App::uses('Organizationalunit', 'Model');

class OrganizationalunitsController extends AppController {

    /*
     * basic definitions
     */

    //<editor-fold defaultstate="collapsed" desc="basic definitions">

    public $components = array('Paginator');

    public $paginate = array(
        'limit' => 15,
    );

    public function beforeFilter() {
        parent::beforeFilter();
    }

    //</editor-fold>

    /*
     * view functions
     */

    //<editor-fold defaultstate="collapsed" desc="view functions">

    public function index() {
        $this->Paginator->settings = $this->paginate;
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
                $this->redirect(array('action' => 'index'));
                return true;
            }
            $this->Session->setFlash(__('Die Organisationseinheit konnte nicht hinzugefügt werden'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-danger'
            ));
            return false;
        }
        return true;
    }

    public function edit($id = null) {
        $this->Organizationalunit->id = $id;
        if (!$this->Organizationalunit->exists()) {
            throw new NotFoundException(__('Organisationseinheit nicht gefunden'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Organizationalunit->save($this->request->data)) {
                $this->Session->setFlash(__('Die Organisationseinheit wurde geändert'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
                $this->redirect(array('action' => 'index'));
                return true;
            }
            $this->Session->setFlash(__('Die Organisationseinheit konnte nicht geändert werden'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-danger'
            ));
            return false;
        } else {
            $this->request->data = $this->Organizationalunit->read(null, $id);
            return true;
        }
    }

    public function delete($id = null) {
        $this->Organizationalunit->id = $id;
        if (!$this->Organizationalunit->exists()) {
            throw new NotFoundException(__('Organisationseinheit nicht gefunden'));
        }
        if ($this->Organizationalunit->delete()) {
            $this->Session->setFlash(__('Die Organisationseinheit wurde gelöscht'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-success'
            ));
            $this->redirect(array('action' => 'index'));
            return true;
        }
        $this->Session->setFlash(__('Die Organisationseinheit konnte nicht gelöscht werden'), 'alert', array(
            'plugin' => 'BoostCake',
            'class' => 'alert-danger'
        ));
        return false;
    }

    //</editor-fold>

    /*
     * backend functions
     */

    //<editor-fold defaultstate="collapsed" desc="backend functions">

    //</editor-fold>

}