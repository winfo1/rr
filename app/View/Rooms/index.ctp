<?php
$fields = array(
    'name' => array(
        'link' => true,
    ),
    'Organizationalunit.name' => array(),
    'Building.name' => array(),
    'floor' => array(),
    'number' => array(),
    'barrier_free' => array(),
    'seats' => array(),
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
    'subscribe.button' => array(
        'url' => array('controller' => 'ical', 'action' => 'index', 'room', 'default_button()'),
        'options' => array()),
    'edit.button' => array(
        'url' => array('action' => 'edit', 'default_button()'),
        'options' => array()),
    'delete.button' => array(
        'url' => array('action' => 'delete', 'default_button()'),
        'options' => array()),
);

$string['title'] = __('Verwaltung der Räume');
$string['name'] = __('Name');
$string['Organizationalunit.name'] = __('Organisationseinheit');
$string['Building.name'] = __('Gebäude');
$string['floor'] = __('Etage');
$string['number'] = __('Nummer');
$string['barrier_free'] = __('Barrierefrei');
$string['seats'] = __('Sitze');
$string['created'] = __('Erstellt');
$string['modified'] = __('Letzte Änderung');
$string['action'] = __('Aktionen');
$string['subscribe.button'] = __('Abonnieren');
$string['edit.button'] = __('Bearbeiten');
$string['delete.button'] = __('Löschen');
$string['add.text'] = __('Es existieren noch kein Raum. Jetzt den ersten Raum');
$string['add.button'] = __('Hinzufügen');

echo $this->element('common' . DS . 'index', compact('data', 'fields', 'links', 'string'));