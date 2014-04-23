<?php

class Semester extends AppModel {

    public $validate = array(
        'end' => array(
            'bigger_as_start' => array(
                'rule'    => array('isBiggerAsStart', 'start'),
                'message' => 'Das Enddatum muss größer als das Startdatum sein',
                'on' => 'enddate'
            ),
            'bigger_as_month' => array(
                'rule'    => array('isBiggerAsMonth', 'start'),
                'message' => 'Das Semester muss länger als ein Monat sein',
                'on' => 'enddate'
            ),
            'smaller_as_year' => array(
                'rule'    => array('isSmallerAsYear', 'start'),
                'message' => 'Das Semester darf nicht länger als ein Jahr sein',
                'on' => 'enddate'
            )
        )
    );

    function isBiggerAsStart($field=array(), $compare_field=null )
    {
        foreach( $field as $key => $value ){
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

    function isBiggerAsMonth($field=array(), $compare_field=null )
    {
        foreach( $field as $key => $value ){
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

    function isSmallerAsYear($field=array(), $compare_field=null )
    {
        foreach( $field as $key => $value ){
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

}