<?php
/**
 * Application model for CakePHP.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {

    public $actsAs = array('Utility.Enumerable');

    public $locale = 'de_de';

    /*
     * database functions
     */

    //<editor-fold defaultstate="collapsed" desc="database functions">

    /**
     * @param null $id
     * @return array
     */
    function getAll($id = null) {
        if(isset($id) && is_numeric($id)) {
            $condition = array($this->name . '.id' => $id);
        } else {
            $condition = array();
        }

        return $this->find('all', array(
            'conditions' => $condition,
        ));
    }

    //</editor-fold>

    function getNextAutoIncrement(){

        $table = Inflector::tableize($this->name);

        $query = "SELECT Auto_increment FROM information_schema.tables AS nextID WHERE table_name='$this->tablePrefix$table'";

        $db = ConnectionManager::getDataSource($this->useDbConfig);

        $result = $db->query($query);

        return $result[0]['nextID']['Auto_increment'];
    }
}
