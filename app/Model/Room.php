<?php

App::uses('AppModel', 'Model');

class Room extends AppModel {
    public $belongsTo = array('Building', 'Organizationalunit');

    public $hasMany = array('Roomimage');

    public $hasAndBelongsToMany = array('Resource');

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

    function hasMin($check) {
        return $check['seats'] >= 4;
    }

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

    public $filterArgs = array(
        'name' => array('type' => 'like'),
        'organizationalunit_id' => array('type' => 'value'),
        'building_id' => array('type' => 'value'),
        'floor' => array('type' => 'value'),
        'number' => array('type' => 'value'),
        'barrier_free' => array('type' => 'value'),
        'seats' => array('type' => 'query', 'method' => 'findBySeats', 'field' => 'Room.seats'),

        'search' => array('type' => 'like', 'field' => 'Room.name'),

        'view_tabs' => array('type' => 'subquery', 'method' => 'findByDateTime'),
        'start_minutes' => array('type' => 'expression'),
        'duration' => array('type' => 'expression'),
        'day' => array('type' => 'expression'),
        'start_hour' => array('type' => 'expression'),
        'end_hour' => array('type' => 'expression'),


        'filter' => array('type' => 'query', 'method' => 'orConditions')
    );


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

    public function findBySeats($data = array()) {
        $query = array(
            'Room.seats >='  => $data['seats'],
        );
        return $query;
    }

    public function findByDateTime($data = array()) {
        if($data['view_tabs'] == 'w') {
            // ignore this
            return ;
        }

        $room_id = 0;

        if($data['view_tabs'] == 's') {
            // simple booking-time selection
            $start = (new DateTime())->modify('+' . $data['start_minutes'] . ' minutes');
            $diff = $start->diff(new DateTime('tomorrow'));
            $minutes_to_add = min( ($diff->h * 60) + ($diff->i), $data['duration']);
            $end = clone $start;
            $end->modify('+' . $minutes_to_add . ' minutes');
        } else {
            // advanced booking-time selection
            $day = $data['day'];
            $start = $this->StrToDateTime($day, $data['start_hour']);
            $end = $this->StrToDateTime($day, $data['end_hour']);
        }

        App::import('Model','Booking');
        $booking = new Booking();
        $blocked = array();
        if ($booking->inUse($start->format('Y-m-d H:i:s'), $end->format('Y-m-d H:i:s'), $room_id, 0, true, $blocked)) {

            // TODO: remove blocked from search list

            foreach($blocked as $booked) {
                // $booked['Room']
            }
        }

        return ;
    }

    public function orConditions($data = array()) {
        $filter = $data['filter'];
        $cond = array(
            'OR' => array(
                $this->alias . '.title LIKE' => '%' . $filter . '%',
                $this->alias . '.body LIKE' => '%' . $filter . '%',
            ));
        return $cond;
    }

    public function isOwnedThroughOrganizationalUnitBy($room_id, $organizationalunit_id) {
        return $this->field('id', array('id' => $room_id, 'organizationalunit_id' => $organizationalunit_id)) === $room_id;
    }




    /**
     * @param $str_day
     * @param $str_hour
     * @return DateTime
     */
    private function StrToDateTime($str_day, $str_hour) {
        preg_match('/(\d+)\:(\d+)/', $str_hour, $match);
        return (new DateTime($str_day))->setTime($match[1], $match[2]);
    }
}