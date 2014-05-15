<?php

App::uses('ApplicationController', 'Controller');

App::uses('Semester', 'Model');

class SemestersController extends ApplicationController {
    /*
     * basic functions
     */

    //<editor-fold defaultstate="collapsed" desc="basic functions">

    //</editor-fold>

    /*
     * basic functions
     */

    //<editor-fold defaultstate="collapsed" desc="basic functions">

    protected function _addStrings() {
        parent::_addStrings();

        $this->string['Semester.title'] = __('Semesterverwaltung');
        $this->string['Semester.add-text'] = __('Es existieren noch kein Semester. Jetzt das erste Semester');
    }

    //</editor-fold>

    /*
     * view functions
     */

    //<editor-fold defaultstate="collapsed" desc="view functions">

    public function add() {
        if ($this->request->is('post')) {
            $this->Semester->create();
            if ($this->Semester->save($this->request->data)) {
                $this->Session->setFlash(__('Das Semester wurde hinzugefügt'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
                $this->redirect(array('action' => 'index'));
                return true;
            }
            $this->Session->setFlash(__('Das Semester konnte nicht hinzugefügt werden'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-danger'
            ));
            return false;
        }
        return true;
    }

    public function edit($id = null) {
        $this->Semester->id = $id;
        if (!$this->Semester->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Semester->save($this->request->data)) {
                $this->Session->setFlash(__('Das Semester wurde geändert'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
                $this->redirect(array('action' => 'index'));
                return true;
            }
            $this->Session->setFlash(__('Das Semester konnte nicht geändert werden'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-danger'
            ));
            return false;
        } else {
            $this->request->data = $this->Semester->read(null, $id);
            return true;
        }
    }

    public function delete($id = null) {
        if($this->request->is('get')) {
            throw new MethodNotAllowedException();
        }
        $this->Semester->id = $id;
        if (!$this->Semester->exists()) {
            throw new NotFoundException(__('Semester nicht gefunden'));
        }
        if ($this->Semester->delete()) {
            $this->Session->setFlash(__('Das Semester wurde gelöscht'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-success'
            ));
            $this->redirect(array('action' => 'index'));
            return true;
        }
        $this->Session->setFlash(__('Das Semester konnte nicht gelöscht werden'), 'alert', array(
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