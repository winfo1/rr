<?php

App::uses('ApplicationController', 'Controller');

App::uses('Organizationalunit', 'Model');

class OrganizationalunitsController extends ApplicationController {

    /*
     * basic definitions
     */

    //<editor-fold defaultstate="collapsed" desc="basic definitions">

    //</editor-fold>

    /*
     * basic functions
     */

    //<editor-fold defaultstate="collapsed" desc="basic functions">

    protected function _addStrings() {
        parent::_addStrings();

        $this->string['Organizationalunit.title'] = __('Verwaltung der Organisationseinheiten');
        $this->string['Organizationalunit.add-text'] = __('Es existieren noch keine Organisationseinheiten. Jetzt die erste Organisationseinheit');
    }

    //</editor-fold>

    /*
     * view functions
     */

    //<editor-fold defaultstate="collapsed" desc="view functions">

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
        if($this->request->is('get')) {
            throw new MethodNotAllowedException();
        }
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