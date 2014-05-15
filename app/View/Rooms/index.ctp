<?php
$fields = array(
    'name' => array(
        'link' => true,
    ),
    'Organizationalunit.name' => array(),
    'Building.name' => array(),
    'Room.floor' => array(),
    'Room.number' => array(),
    'Room.barrier_free' => array(),
    'Room.seats' => array(),
    'created' => array(
        'center' => true,
        'type' => 'datetime',
    ),
    'modified' => array(
        'center' => true,
        'type' => 'datetime',
    )
);

function default_button($value, $mainModel) {
    return $value[$mainModel]['id'];
}

$links = array(
    'subscribe' => array(
        'url' => array('controller' => 'ical', 'action' => 'index', 'room', 'default_button()'),
        'options' => array()),
    'edit' => array(
        'url' => array('action' => 'edit', 'default_button()'),
        'options' => array()),
    'delete' => array(
        'url' => array('action' => 'delete', 'default_button()'),
        'postLink' => true,
        'options' => array()),
);

$options = array(
    'addable' => true,
);

echo $this->element('common' . DS . 'index', compact('data', 'fields', 'links', 'options', 'string'));