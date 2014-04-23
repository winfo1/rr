<?php

class Group extends AppModel {
    public $actsAs = array('Acl' => array('type' => 'requester'));

    public function parentNode() {
        return null;
    }
}