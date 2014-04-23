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

        'tags' => array('type' => 'subquery', 'method' => 'findByTags', 'field' => 'Room.id'),
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


    public function findByTags($data = array()) {
        $this->Tagged->Behaviors->attach('Containable', array('autoFields' => false));
        $this->Tagged->Behaviors->attach('Search.Searchable');
        $query = $this->Tagged->getQuery('all', array(
            'conditions' => array('Tag.name'  => $data['tags']),
            'fields' => array('foreign_key'),
            'contain' => array('Tag')
        ));
        return $query;
    }

    public function findBySeats($data = array()) {

        $query = array(
            'Room.seats >='  => $data['seats'],
        );
        return $query;
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
}