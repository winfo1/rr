<?php
App::uses('AppModel', 'Model');

App::uses('SimplePasswordHasher', 'Controller/Component/Auth');

class User extends AppModel {
    public $belongsTo = array('Group', 'Organizationalunit');

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
                'message' => 'Dies ist keine strukturell gültige E-Mail-Addresse'
            )
        )
    );

    /**
     * Before isUniqueUsername
     * @param array $options
     * @return boolean
     */
    function isUniqueUsername($check) {

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

    public function beforeSave($options = array()) {
        if (isset($this->data[$this->alias]['password'])) {
            $passwordHasher = new SimplePasswordHasher();
            $this->data[$this->alias]['password'] = $passwordHasher->hash(
                $this->data[$this->alias]['password']
            );
        }
        return true;
    }


}