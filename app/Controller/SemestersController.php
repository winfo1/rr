<?php

class SemestersController extends AppController {
    /*
     * basic functions
     */

    //<editor-fold defaultstate="collapsed" desc="basic functions">

    public $components = array('Paginator');

    public $paginate = array(
        'limit' => 25,
        'order' => array('Semester.short' => 'asc' )
    );

    //</editor-fold>

    public function index() {
        $semesters = $this->Paginator->paginate('Semester');
        $this->set(compact('semesters'));
    }

    public function add() {
        if ($this->request->is('post')) {
            $this->Semester->create();
            if ($this->Semester->save($this->request->data)) {
                $this->Session->setFlash(__('Das Semester wurde hinzugefügt'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
                return $this->redirect(array('action' => 'index'));
            }

            foreach ($this->Semester->validationErrors as $field => $error) {
                if($field == 'start')
                    $this->Semester->validationErrors["startdate"] = $error;
                elseif($field == 'end')
                    $this->Semester->validationErrors["enddate"] = $error;
            }

            $this->Session->setFlash(__('Das Semester konnte nicht hinzugefügt werden'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-danger'
            ));
        }
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
                return $this->redirect(array('action' => 'index'));
            }

            foreach ($this->Semester->validationErrors as $field => $error) {
                if($field == 'start')
                    $this->Semester->validationErrors["startdate"] = $error;
                elseif($field == 'end')
                    $this->Semester->validationErrors["enddate"] = $error;
            }

            $this->Session->setFlash(__('Das Semester konnte nicht geändert werden'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-danger'
            ));
        } else {
            $this->request->data = $this->Semester->read(null, $id);
        }
    }

    public function delete($id = null) {
        $this->Semester->id = $id;
        if (!$this->Semester->exists()) {
            throw new NotFoundException(__('Semester nicht gefunden'));
        }
        if ($this->Semester->delete()) {
            $this->Session->setFlash(__('Das Semester wurde gelöscht'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-success'
            ));
            return $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Das Semester konnte nicht gelöscht werden'), 'alert', array(
            'plugin' => 'BoostCake',
            'class' => 'alert-danger'
        ));
        return $this->redirect(array('action' => 'index'));
    }

    public function getActiveSemester() {

        $semester = $this->Semester->find('first', array(
            'conditions' => array(
                    array(
                        'Semester.start <=' => date('Y-m-d'),
                        'Semester.end >=' => date('Y-m-d')
                ),
            )
        ));

        return $semester;
    }

    public function getNextSemester() {

        $now = date('Y-m-d');

        $this->Semester->virtualFields['closestdate'] = "ABS(DATEDIFF(Semester.end, $now))";

        $semester = $this->Semester->find('first', array(
            'conditions' => array(
                    array(
                        'Semester.end >=' => date('Y-m-d')
                )
            ),
            'order' => array('Semester.closestdate' => 'ASC')
        ));

        return $semester;
    }

}