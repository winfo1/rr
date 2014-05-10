<?php

App::uses('AppModel', 'Model');

class Resource extends AppModel {

    /*
     * basic definitions
     */

    //<editor-fold defaultstate="collapsed" desc="basic definitions">

    const bool = 0;
    const int = 1;

    public $enum = array(
        'type' => array(
            self::bool => 'bool',
            self::int => 'int'
        )
    );

    public $order = 'name';

    public $validate = array(
        'name' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Der Name wird benötigt'
            ),
            'min_length' => array(
                'rule' => array('minLength', '4'),
                'message' => 'Der Name muss aus mindestens vier Zeichen bestehen'
            )
        )
    );

    //</editor-fold>

    /*
     * database functions
     */

    //<editor-fold defaultstate="collapsed" desc="database functions">

    /**
     * @param null $data
     * @return array
     */
    public function getResourcesAsList($data = null) {
        if(!isset($data)) {
            $data = $this->getAll();
        }

        $result = array();

        foreach ($data as $value) {
            $result[$value['Resource']['id']] = $value['Resource']['name'];
        }

        return $result;
    }

    /**
     * @return array
     *
     * faster than getResourcesAsList but, when having
     * getAll data anyway use getResourcesAsList
     * instead
     */
    public function getResourcesFromList() {
        $fields = array('id', 'name');

        $result = $this->find('list', array('fields' => $fields));

        return $result;
    }

    //</editor-fold>
}