<?php
$fields = array(
    'short' => array(
        'link' => true,
    ),
    'name' => array(),
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

$string['title'] = __('Gebäudeverwaltung');
$string['short'] = __('Abkürzung');
$string['name'] = __('Name');
$string['created'] = __('Erstellt');
$string['modified'] = __('Letzte Änderung');
$string['action'] = __('Aktionen');
$string['edit.button'] = __('Bearbeiten');
$string['delete.button'] = __('Löschen');
$string['add.text'] = __('Es existieren noch keine Gebäude. Jetzt das erste Gebäude');
$string['add.button'] = __('Hinzufügen');

echo $this->element('common' . DS . 'index', compact('data', 'fields', 'links', 'string'));