<?php
App::uses('AppModel', 'Model');

class Booking extends AppModel {
    var $belongsTo = array('Room', 'User');

    const active = 'active';
    const planned = 'planned';
    const planning_concurred = 'planning_concurred';
    const planning_rejected = 'planning_rejected';
    const archived = 'archived';

    public $validate = array(
        'name' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Die Beschreibung wird benötigt'
            ),
            'min_length' => array(
                'rule' => array('minLength', '4'),
                'message' => 'Die Beschreibung muss aus mindestens vier Zeichen bestehen'
            )
        ),
        'status' => array(
            'valid' => array(
                'rule' => array('inList', array(self::active, self::planned, self::planning_concurred, self::planning_rejected, self::archived)),
                'message' => 'Bitte einen gültigen Status eintragen',
                'allowEmpty' => false
            )
        )
    );

    public function beforeFind($query) {
        parent::beforeFind($query);

        $this->bindModel(array(
            'belongsTo' => array(
                'Room' => array(
                    'foreignKey' => false,
                    'conditions' => array('Booking.room_id = Room.id')
                ),
                'Building' => array(
                    'foreignKey' => false,
                    'conditions' => array('Room.building_id = Building.id')
                )
            )
        ));
    }

    public $default_names = array(
        'Besprechung',
        'Diss fixe',
        'Jour fixe'
    );

    public function isOwnedBy($booking_id, $user_id) {
        return $this->field('id', array('id' => $booking_id, 'user_id' => $user_id)) === $booking_id;
    }

    public function isOwnedThroughOrganizationalUnitBy($booking_id, $organizationalunit_id) {
        return count($this->find('first', array('conditions' => array('Booking.id' => $booking_id, 'Room.organizationalunit_id' => $organizationalunit_id)))) != 0;
    }

}