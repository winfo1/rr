<?php

App::uses('AppModel', 'Model');

App::uses('SimplePasswordHasher', 'Controller/Component/Auth');

class User extends AppModel {

    /*
     * basic definitions
     */

    //<editor-fold defaultstate="collapsed" desc="basic definitions">

    public $belongsTo = array('Group', 'Organizationalunit');

    public $order = 'username';

    const root = 'root';
    const admin = 'admin';
    const user = 'user';

    public $validate = array(
        'username' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Ein Benutzername wird benötigt'
            ),
            'unique' => array(
                'rule'    => array('isUniqueUsername'),
                'message' => 'Dieser Benutzername wird bereits verwendet'
            )
        ),
        'password' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Ein Passwort wird benötigt'
            ),
            'min_length' => array(
                'rule' => array('minLength', '6'),
                'message' => 'Ein Passwort muss aus mindestens sechs Zeichen bestehen'
            )
        ),
        'password_re' => array(
            'required' => array(
                'rule' => array('equalToField', 'password'),
                'message' => 'Beide Passwortfelder müssen ausgefüllt werden und übereinstimmen'
            )
        ),
        'role' => array(
            'valid' => array(
                'rule' => array('inList', array(self::root, self::admin, self::user)),
                'message' => 'Please enter a valid role',
                'allowEmpty' => false
            )
        ),
        'emailaddress' => array(
            'email' => array(
                'rule' => 'email',
                'message' => 'Dies ist keine gültige E-Mail-Addresse'
            )
        )
    );

    //</editor-fold>

    /*
     * basic functions
     */

    //<editor-fold defaultstate="collapsed" desc="basic functions">

    public function beforeSave($options = array()) {
        if (isset($this->data[$this->alias]['password'])) {
            $passwordHasher = new SimplePasswordHasher();
            $this->data[$this->alias]['password'] = $passwordHasher->hash(
                $this->data[$this->alias]['password']
            );
        }
        return true;
    }

    //</editor-fold>

    /*
     * validation functions
     */

    //<editor-fold defaultstate="collapsed" desc="validation functions">

    /**
     * Before isUniqueUsername
     * @param $check
     * @return boolean
     */
    public function isUniqueUsername($check) {

        $username = $this->find(
            'first',
            array(
                'fields' => array(
                    'User.id',
                    'User.username'
                ),
                'conditions' => array(
                    'User.username' => $check['username']
                )
            )
        );

        if(!empty($username)){
            if($this->data[$this->alias]['id'] == $username['User']['id']){
                return true;
            }else{
                return false;
            }
        }else{
            return true;
        }
    }

    function equalToField($array, $field) {
        return strcmp($this->data[$this->alias][key($array)], $this->data[$this->alias][$field]) == 0;
    }

    //</editor-fold>

    /*
     * database functions
     */

    //<editor-fold defaultstate="collapsed" desc="database functions">

    public function getUsersFromOrganizationalUnitId($organizationalunit_id) {
        $list = $this->find('all', array(
            'conditions' => array(
                'AND' => array(
                    'User.organizationalunit_id' => $organizationalunit_id,
                    'User.organizationalunit_verified' => 1
                )
            )
        ));

        return $list;
    }

    //</editor-fold>

}