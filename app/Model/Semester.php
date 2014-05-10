<?php

App::uses('AppModel', 'Model');

class Semester extends AppModel {

    /*
     * basic definitions
     */

    //<editor-fold defaultstate="collapsed" desc="basic definitions">

    public $order = 'end desc';

    public $validate = array(
        'end' => array(
            'bigger_as_start' => array(
                'rule'    => array('isBiggerAsStart', 'start'),
                'message' => 'Das Enddatum muss größer als das Startdatum sein',
            ),
            'bigger_as_month' => array(
                'rule'    => array('isBiggerAsMonth', 'start'),
                'message' => 'Das Semester muss länger als ein Monat sein',
            ),
            'smaller_as_year' => array(
                'rule'    => array('isSmallerAsYear', 'start'),
                'message' => 'Das Semester darf nicht länger als ein Jahr sein',
            )
        )
    );

    //</editor-fold>

    /*
     * validation functions
     */

    //<editor-fold defaultstate="collapsed" desc="validation functions">

    public function isBiggerAsStart($field = array(), $compare_field = null) {
        foreach($field as $value){
            $v1 = $value;
            $v2 = $this->data[$this->name][$compare_field];
            if($v1 <= $v2) {
                return false;
            } else {
                continue;
            }
        }
        return true;
    }

    public function isBiggerAsMonth($field = array(), $compare_field = null) {
        foreach($field as $value){
            $v1 = new DateTime($value);
            $v2 = new DateTime($this->data[$this->name][$compare_field]);

            if($v1->diff($v2)->days <= 30) {
                return false;
            } else {
                continue;
            }
        }
        return true;
    }

    public function isSmallerAsYear($field = array(), $compare_field = null) {
        foreach($field as $value){
            $v1 = new DateTime($value);
            $v2 = new DateTime($this->data[$this->name][$compare_field]);

            if($v1->diff($v2)->days >= 365) {
                return false;
            } else {
                continue;
            }
        }
        return true;
    }

    //</editor-fold>

    /*
     * database functions
     */

    //<editor-fold defaultstate="collapsed" desc="database functions">

    public function getActiveSemester() {
        $now = date('Y-m-d');

        $semester = $this->find('first', array(
            'conditions' => array(
                array(
                    'Semester.start <=' => $now,
                    'Semester.end >=' => $now
                ),
            )
        ));

        return $semester;
    }

    public function getNextSemester() {
        $now = date('Y-m-d');
        $this->virtualFields['closestdate'] = 'ABS(DATEDIFF(Semester.end, ' . $now . '))';

        $semester = $this->find('first', array(
            'conditions' => array(
                array(
                    'Semester.end >=' => $now
                )
            ),
            'order' => array('Semester.closestdate' => 'ASC')
        ));

        return $semester;
    }

    //</editor-fold>

}