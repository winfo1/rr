<?php

App::uses('ApplicationController', 'Controller');

App::uses('Building', 'Model');

class BuildingsController extends ApplicationController {

    /*
     * basic definitions
     */

    //<editor-fold defaultstate="collapsed" desc="basic definitions">

    //</editor-fold>

    /*
     * view functions
     */

    //<editor-fold defaultstate="collapsed" desc="view functions">

    public function add() {
        if ($this->request->is('post')) {
            $this->Building->create();
            if ($this->Building->save($this->request->data)) {
                $this->Session->setFlash(__('Das Gebäude wurde hinzugefügt'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
                $this->redirect(array('action' => 'index'));
                return true;
            }
            $this->Session->setFlash(__('Das Gebäude konnte nicht hinzugefügt werden'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-danger'
            ));
            return false;
        }
        return true;
    }

    public function edit($id = null) {
        $this->Building->id = $id;
        if (!$this->Building->exists()) {
            throw new NotFoundException(__('Gebäude nicht gefunden'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Building->save($this->request->data)) {
                $this->Session->setFlash(__('Das Gebäude wurde geändert'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
                $this->redirect(array('action' => 'index'));
                return true;
            }
            $this->Session->setFlash(__('Das Gebäude konnte nicht geändert werden'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-danger'
            ));
            return false;
        } else {
            $this->request->data = $this->Building->read(null, $id);
            return true;
        }
    }

    public function delete($id = null) {
        $this->Building->id = $id;
        if (!$this->Building->exists()) {
            throw new NotFoundException(__('Gebäude nicht gefunden'));
        }
        if ($this->Building->delete()) {
            $this->Session->setFlash(__('Das Gebäude wurde gelöscht'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-success'
            ));
            $this->redirect(array('action' => 'index'));
            return true;
        }
        $this->Session->setFlash(__('Das Gebäude konnte nicht gelöscht werden'), 'alert', array(
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