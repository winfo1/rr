<?php

App::uses('AppModel', 'Model');

class Organizationalunit extends AppModel {

    /*
     * basic definitions
     */

    //<editor-fold defaultstate="collapsed" desc="basic definitions">

    public $order = 'short';

    //</editor-fold>

    /*
     * database functions
     */

    //<editor-fold defaultstate="collapsed" desc="database functions">

    /**
     * @param null $data
     * @param bool $short
     * @return array
     */
    public function getOrganizationalunitsAsList($data = null, $short = true) {
        if(!isset($data)) {
            $data = $this->getAll();
        }

        $result = array();

        foreach ($data as $value) {
            if($short)
                $buf = $value['Organizationalunit']['short'];
            else
                $buf = $value['Organizationalunit']['name'];

            $result[$value['Organizationalunit']['id']] = $buf;
        }

        return $result;
    }

    /**
     * @param bool $short
     * @return array
     *
     * faster than getOrganizationalunitsAsList but, when having
     * getAll data anyway use getOrganizationalunitsAsList
     * instead
     */
    public function getOrganizationalunitsFromList($short = true) {
        $fields = array('id');
        if($short)
            $fields[] = 'short';
        else
            $fields[] = 'name';

        $result = $this->find('list', array('fields' => $fields));

        return $result;
    }

    //</editor-fold>

}