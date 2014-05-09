<?php

App::uses('AppModel', 'Model');

class Building extends AppModel {

    /**
     * @param null $id
     * @return array
     */
    public function getBuildings($id = null) {
        if(isset($id) && is_numeric($id)) {
            $condition = array('Building.id' => $id);
        } else {
            $condition = array();
        }

        return $this->find('all', array(
            'conditions' => $condition,
            'order' => array('Building.short' => 'asc')
        ));
    }

    /**
     * @param null $data
     * @param bool $short
     * @return array
     */
    public function getBuildingsAsList($data = null, $short = true) {
        if(!isset($data)) {
            $data = $this->getBuildings();
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

}