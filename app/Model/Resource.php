<?php

App::uses('AppModel', 'Model');

class Resource extends AppModel {

    const bool = 0;
    const int = 1;

    public $enum = array(
        'type' => array(
            self::bool => 'bool',
            self::int => 'int'
        )
    );

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
}