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

echo $this->element('common' . DS . 'index', compact('data', 'string', 'fields'));