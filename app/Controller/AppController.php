<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
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
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

    public $components = array(
        'Auth' => array(
            'loginRedirect' => array(
                'controller' => 'pages',
                'action' => 'display',
                'home'
            ),
            'logoutRedirect' => array(
                'controller' => 'pages',
                'action' => 'display',
                'home'
            ),
            'authorize' => array('Controller'),
            'flash' => array(
                'element' => 'alert',
                'key' => 'auth',
                'params' => array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-error'
                )
            )
        ),
        // 'Security',
        'Session',
        'Utility.AutoLogin' => array(
            'cookieName' => 'rememberMe',
            'expires' => '+4 weeks'
        ),
        'DebugKit.Toolbar'
    );

    public $helpers = array(
        'AssetCompress.AssetCompress',
        'MyTime',
        'Session',
        'Html' => array('className' => 'BoostCake.BoostCakeHtml'),
        'Form' => array('className' => 'BoostCake.BoostCakeForm'),
        'Paginator' => array('className' => 'BoostCake.BoostCakePaginator'),
        'Status',
        'Timeline'
    );

    public $string = array();

    public function beforeFilter() {
        $this->Auth->allow('index', 'view');
    }

    public function beforeRender() {
        parent::beforeRender();

        $string = $this->string;
        $this->set(compact('string'));
    }

    public function isAuthorized($user) {
        // Admin can access every action
        if (isset($user['role']) && $user['role'] === 'root') {
            return true;
        }

        // Default deny
        return false;
    }

    public function __construct($request = null, $response = null) {
        parent::__construct($request, $response);

        $this->_addStrings();
    }

    protected function _addStrings() {
        $this->string['actions'] = __('Aktionen');
        $this->string['add'] = __('Hinzufügen');
        $this->string['created'] = __('Erstellt');
        $this->string['delete'] = __('Löschen');
        $this->string['edit'] = __('Bearbeiten');
        $this->string['end'] = __('Ende');
        $this->string['name'] = __('Name');
        $this->string['modified'] = __('Letzte Änderung');
        $this->string['search_result'] = __('Suchergebnis');
        $this->string['short'] = __('Abkürzung');
        $this->string['start'] = __('Start');
        $this->string['subscribe'] = __('Abonnieren');
    }
}
