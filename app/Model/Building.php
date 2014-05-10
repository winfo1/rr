<?php

App::uses('AppModel', 'Model');

class Building extends AppModel {

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
    public function getBuildingsAsList($data = null, $short = true) {
        if(!isset($data)) {
            $data = $this->getAll();
        }

        $result = array();

        foreach ($data as $value) {
            if($short)
                $buf = $value['Building']['short'];
            else
                $buf = $value['Building']['name'];

            $result[$value['Building']['id']] = $buf;
        }

        return $result;
    }

    /**
     * @param bool $short
     * @return array
     *
     * faster than getBuildingsAsList but, when having
     * getBuildings data anyway use getBuildingsAsList
     * instead
     */
    public function getBuildingsFromList($short = true) {
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