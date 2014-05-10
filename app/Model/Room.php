<?php

App::uses('AppModel', 'Model');

App::import('Lib', 'Utils');

class Room extends AppModel {

    /*
     * basic definitions
     */

    //<editor-fold defaultstate="collapsed" desc="basic definitions">

    public $actsAs = array(
        'Search.Searchable',
        'Uploader.Attachment' => array(
            'layout_image_url' => array(
                'transforms' => array(
                    'layout_image_small_url' => array(
                        'class' => 'resize',
                        'nameCallback' => 'transformNameCallback',
                        'append' => '-small',
                        'width' => 250,
                        'height' => 250
                    )
                )
            )
        ),
        'Uploader.FileValidation' => array(
            'image' => array(
                'extension' => array('gif', 'jpg', 'png', 'jpeg'),
                'type' => 'image'
            )
        )
    );

    public $belongsTo = array('Building', 'Organizationalunit');

    public $hasMany = array('Roomimage');

    public $hasAndBelongsToMany = array('Resource');

    public $filterArgs = array(
        'name' => array('type' => 'like'),
        'organizationalunit_id' => array('type' => 'value'),
        'building_id' => array('type' => 'value'),
        'floor' => array('type' => 'value'),
        'number' => array('type' => 'value'),
        'barrier_free' => array('type' => 'value'),
        'seats' => array('type' => 'query', 'method' => 'findBySeats', 'field' => 'Room.seats'),

        'search' => array('type' => 'like', 'field' => 'Room.name'),

        'view_tabs' => array('type' => 'query', 'method' => 'findByDateTime'),
        'start_minutes' => array('type' => 'expression', 'method' => null),
        'duration' => array('type' => 'expression', 'method' => null),
        'day' => array('type' => 'expression', 'method' => null),
        'start_hour' => array('type' => 'expression', 'method' => null),
        'end_hour' => array('type' => 'expression', 'method' => null),

        'filter' => array('type' => 'query', 'method' => 'orConditions')
    );

    public $order = 'name';

    public $virtualFields = array(
        'name' => 'CONCAT(Building.short, Room.floor, ".", Room.number)'
    );

    public $validate = array(
        'seats' => array(
            'min' => array(
                'rule'    => array('hasMin'),
                'message' => 'Dieser Raum muss mindestens 4 Sitze haben'
            )
        )
    );

    //</editor-fold>

    /*
     * validation functions
     */

    //<editor-fold defaultstate="collapsed" desc="validation functions">

    public function hasMin($check) {
        return $check['seats'] >= 4;
    }

    //</editor-fold>

    /*
     * uploader functions
     */

    //<editor-fold defaultstate="collapsed" desc="uploader functions">

    public function beforeUpload($options) {

        $options['finalPath'] = '/img/uploads/';
        $options['uploadDir'] = WWW_ROOT . $options['finalPath'];

        return $options;
    }

    public function beforeTransform($options) {

        $options['finalPath'] = '/img/uploads/' . $options['class'] . '/';
        $options['uploadDir'] = WWW_ROOT . $options['finalPath'];

        return $options;
    }

    public function transformNameCallback($name, $file) {
        return $this->getUploadedFile()->name();
    }

    //</editor-fold>

    /*
     * filter functions
     */

    //<editor-fold defaultstate="collapsed" desc="filter functions">

    public function findBySeats($data = array()) {
        $query = array(
            'Room.seats >='  => $data['seats'],
        );
        return $query;
    }

    public function findByDateTime($data = array()) {
        if($data['view_tabs'] == 'w') {
            // ignore this
            return null;
        }

        $room_id = 0;

        if($data['view_tabs'] == 's') {
            // simple booking-time selection
            $start = (new DateTime())->modify('+' . $data['start_minutes'] . ' minutes');
            $end = Utils::toEndDateTime($start, $data['duration']);
        } else {
            // advanced booking-time selection
            $day = $data['day'];
            $start = Utils::toDateTime($day, $data['start_hour']);
            $end = Utils::toDateTime($day, $data['end_hour']);
        }

        $room_ids = array();

        App::import('Model','Booking');
        $booking = new Booking();
        $blocked = array();
        if ($booking->inUse($start->format('Y-m-d H:i:s'), $end->format('Y-m-d H:i:s'), $room_id, 0, true, $blocked)) {

            // store blocked in array

            foreach($blocked as $booked) {

                $room_ids[] = $booked['Room']['id'];

            }
        }

        $condition = array('NOT' => array('Room.id' => $room_ids));
        return $condition;
    }

    public function orConditions($data = array()) {
        $filter = $data['filter'];
        $condition = array(
            'OR' => array(
                $this->alias . '.title LIKE' => '%' . $filter . '%',
                $this->alias . '.body LIKE' => '%' . $filter . '%',
            ));
        return $condition;
    }

    //</editor-fold>

    /*
     * database functions
     */

    //<editor-fold defaultstate="collapsed" desc="database functions">

    /**
     * @param null $data
     * @return array
     */
    function getRoomsAsList($data = null) {
        if(!isset($data)) {
            $data = $this->getAll();
        }

        $result = array();

        foreach ($data as $value) {
            $result[$value['Room']['id']] = $value['Room']['name'];
        }

        return $result;
    }

    /**
     * @return array
     *
     * faster than getRoomsAsList but, when having
     * getAll data anyway use getRoomsAsList
     * instead
     */
    public function getRoomsFromList() {
        $fields = array('id', 'name');

        $result = $this->find('list', array(
            'fields' => $fields,
            'recursive' => 1, //! important
            'contain' => array('Building')
        ));

        return $result;
    }

    public function isOwnedThroughOrganizationalUnitBy($room_id, $organizationalunit_id) {
        return $this->field('id', array('id' => $room_id, 'organizationalunit_id' => $organizationalunit_id)) === $room_id;
    }

    //</editor-fold>
}