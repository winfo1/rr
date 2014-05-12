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
    'edit.button' => array(
        'url' => array('action' => 'edit', 'default_button()'),
        'options' => array()),
    'delete.button' => array(
    'url' => array('action' => 'delete', 'default_button()'),
    'options' => array()),
);

$string['title'] = __('Semesterverwaltung');
$string['short'] = __('Abkürzung');
$string['start'] = __('Start');
$string['end'] = __('Ende');
$string['created'] = __('Erstellt');
$string['modified'] = __('Letzte Änderung');
$string['action'] = __('Aktionen');
$string['edit.button'] = __('Bearbeiten');
$string['delete.button'] = __('Löschen');
$string['add.text'] = __('Es existieren noch kein Semester. Jetzt das erste Semester');
$string['add.button'] = __('Hinzufügen');

echo $this->element('common' . DS . 'index', compact('data', 'fields', 'links', 'string'));