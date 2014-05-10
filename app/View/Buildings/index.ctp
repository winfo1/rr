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

echo $this->element('common' . DS . 'index', compact('data', 'string', 'fields'));