<?php

App::uses('Resource', 'Model');

class ResourcesController extends AppController {

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
        $this->Paginator->settings = $this->paginate;
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
                $this->redirect(array('action' => 'index'));
                return true;
            }
            $this->Session->setFlash(__('Die Ressource konnte nicht hinzugefügt werden'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-danger'
            ));
            return false;
        }
        return true;
    }

    public function edit($id = null) {
        $this->Resource->id = $id;
        if (!$this->Resource->exists()) {
            throw new NotFoundException(__('Ressource nicht gefunden'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Resource->save($this->request->data)) {
                $this->Session->setFlash(__('Die Ressource wurde geändert'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
                $this->redirect(array('action' => 'index'));
                return true;
            }
            $this->Session->setFlash(__('Die Ressource konnte nicht geändert werden'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-danger'
            ));
            return false;
        } else {
            $this->request->data = $this->Resource->read(null, $id);
            return true;
        }
    }

    public function delete($id = null) {
        $this->Resource->id = $id;
        if (!$this->Resource->exists()) {
            throw new NotFoundException(__('Ressource nicht gefunden'));
        }
        if ($this->Resource->delete()) {
            $this->Session->setFlash(__('Die Ressource wurde gelöscht'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-success'
            ));
            $this->redirect(array('action' => 'index'));
            return true;
        }
        $this->Session->setFlash(__('Die Ressource konnte nicht gelöscht werden'), 'alert', array(
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