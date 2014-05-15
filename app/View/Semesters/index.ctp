<?php
$fields = array(
    'short' => array(
        'link' => true,
    ),
    'start' => array(
        'center' => true,
        'type' => 'datetime',
    ),
    'end' => array(
        'center' => true,
        'type' => 'datetime',
    ),
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