<?php
App::uses('AppModel', 'Model');

class Booking extends AppModel {

    /*
     * basic definitions
     */

    //<editor-fold defaultstate="collapsed" desc="basic definitions">

    public $belongsTo = array('Room', 'User');

    const active = 'active';
    const active_denied = 'active_denied';
    const planned = 'planned';
    const planning_concurred = 'planning_concurred';
    const planning_rejected = 'planning_rejected';
    const archived = 'archived';

    public $order = 'startdatetime';

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
                'rule' => array('inList', array(self::active, self::active_denied, self::planned, self::planning_concurred, self::planning_rejected, self::archived)),
                'message' => 'Bitte einen gültigen Status eintragen',
                'allowEmpty' => false
            )
        )
    );

    public $default_names = array(
        'Besprechung',
        'Diss fixe',
        'Jour fixe',
        'Masterthesis'
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

    //</editor-fold>

    /*
     * database functions
     */

    //<editor-fold defaultstate="collapsed" desc="database functions">

    /**
     * @return array
     */
    public function getNames() {
        $list = $this->find('list', array(
            'fields' => array($this->name . '.Name')
        ));

        return array_unique(array_merge($this->default_names, $list));
    }

    /**
     * @param string $filterType
     * @param string $filterID
     * @return mixed
     */
    public function getBookings($filterType = 'all', $filterID = '0') {
        $conditions = array();

        switch ($filterType) {
            case 'room':
                if ($filterID != 0) {
                    $conditions['Booking.room_id'] = $filterID;
                }
                break;
            case 'ou':
            case 'organizationalunit':
                if ($filterID != 0) {
                    $conditions['Room.organizationalunit_id'] = $filterID;
                }
                break;
            default:
                ;
        }

        return $this->find('all', array(
            'conditions' => $conditions
        ));
    }

    /**
     * @param $group_id
     * @return array
     */
    public function getBookingsGroupNames($group_id) {
        $tree = $this->find('threaded', array(
            'conditions' => array($this->name . '.group_id' => $group_id),
            'fields' => array($this->name . '.id', $this->name . '.room_id', $this->name . '.name', $this->name . '.startdatetime', $this->name . '.enddatetime')
        ));

        return $tree;
    }

    public function isOwnedBy($booking_id, $user_id) {
        return $this->field('id', array('id' => $booking_id, 'user_id' => $user_id)) === $booking_id;
    }

    public function isOwnedThroughOrganizationalUnitBy($booking_id, $organizationalunit_id) {
        return count($this->find('first', array('conditions' => array('Booking.id' => $booking_id, 'Room.organizationalunit_id' => $organizationalunit_id)))) != 0;
    }

    public function inUse($start_time, $end_time, $room_id = 0, $ignore_booking_id = 0, $only_active = true, &$blocked) {
        $conditions = array(
            'OR' => array(
                array('Booking.startdatetime <=' => $start_time,
                    'Booking.enddatetime >=' => $end_time
                ),
                array('Booking.startdatetime <=' => $end_time,
                    'Booking.enddatetime >=' => $start_time
                )
            )
        );

        if($room_id > 0)
            $conditions['Booking.room_id'] = $room_id;

        if($ignore_booking_id > 0)
            $conditions['Booking.id !='] = $ignore_booking_id;

        if($only_active)
            $conditions['Booking.status'] = Booking::active;
        else
            $conditions['Booking.status'] = array(Booking::active, Booking::planned);

        $blocked = $this->find('all', array(
            'conditions' => $conditions,
            'contain' => array('Room', 'User')
        ));

        return (count($blocked) != 0);
    }

    //</editor-fold>

}