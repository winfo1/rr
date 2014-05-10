<?php

App::uses('User', 'Model');

class UsersController extends AppController {

    /*
     * basic definitions
     */

    //<editor-fold defaultstate="collapsed" desc="basic definitions">

    public $components = array('Paginator');

    public $paginate = array(
        'limit' => 15,
    );

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('beforeDetailDisplay', 'register', 'login', 'logout');
    }

    //</editor-fold>

    /*
     * basic functions
     */

    //<editor-fold defaultstate="collapsed" desc="basic functions">

    public function isAuthorized($user) {

        if($this->action === 'edit')
        {
            $id = $this->request->params['pass'][0];

            if(in_array($id, array('my', $this->Session->read('Auth.User.id'))))
                return true;
        }

        if ($user['role'] == User::user) {

            if($this->action === 'upgrade')
                return true;
        }

        return parent::isAuthorized($user);
    }

    public function beforeDetailDisplay() {
        $organizationalunits = $this->User->Organizationalunit->getOrganizationalunitsFromList(false);
        $this->set(compact('organizationalunits'));
    }

    //</editor-fold>

    /*
     * view functions
     */

    //<editor-fold defaultstate="collapsed" desc="view functions">

    public function index() {
        $this->Paginator->settings = $this->paginate;
        $users = $this->Paginator->paginate('User');
        $this->set(compact('users'));
    }

    public function login() {
        if ($this->Session->check('Auth.User.id')) {
            $this->Session->setFlash(__('Sie sind bereits angemeldet'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-warning'
            ));
            $this->redirect($this->referer());
            return true;
        } elseif ($this->request->is('post')) {
            if ($this->Auth->login()) {
                $this->redirect($this->Auth->redirect());
                return true;
            }
            $this->Session->setFlash(__('Der Benutzername oder das Passwort ist falsch. Versuchen Sie es erneut'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-danger'
            ));
            return false;
        }
        return false;
    }

    public function logout() {
        $this->Session->destroy();
        $this->redirect($this->Auth->logout());
        return true;
    }

    public function view($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Benutzer nicht gefunden'));
        }
        $this->set('user', $this->User->read(null, $id));
    }

    public function add() {
        if ($this->request->is('post')) {
			if(($this->request->data['User']['role'] == User::admin) && ($this->request->data['User']['organizationalunit_id'] == 0)) {
                $this->Session->setFlash(__('Der Benutzer konnte nicht hinzugefügt werden, da Benutzer mit der Rolle Admin einer Org.Einheit angehören müssen'), 'alert', array(
					'plugin' => 'BoostCake',
					'class' => 'alert-danger'
				));
                return false;
			}
            $this->User->create();
            $this->request->data['User']['group_id'] = 0;
            if($this->request->data['User']['role'] == User::admin) {
            	$this->request->data['User']['organizationalunit_fixed'] = 1;
            	$this->request->data['User']['organizationalunit_verified'] = 1;
            }
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('Der Benutzer wurde hinzugefügt'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
                $this->redirect(array('action' => 'index'));
                return true;
            }
            $this->Session->setFlash(__('Der Benutzer konnte nicht hinzugefügt werden'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-danger'
            ));
            return false;
        }
        $this->beforeDetailDisplay();
        return true;
    }

    public function register() {
        if ($this->request->is('post')) {
            $this->User->create();
            $this->request->data['User']['group_id'] = 0;
            $this->request->data['User']['role'] = User::user;
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('Sie haben sich erfolgreich registriert'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
                $this->Auth->login();
                $this->redirect(array('controller' => 'pages', 'action' => 'display', 'home'));
                return true;
            }
            $this->Session->setFlash(__('Die Registrierung konnte nicht abgeschlossen werden. Versuchen Sie es erneut'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-danger'
            ));
            return false;
        }
        return true;
    }

    public function edit($id = null) {
        $my = ($id == 'my');
        $this->set(compact('my'));

        $this->beforeDetailDisplay();

        $id = ($my ? $this->Session->read('Auth.User.id') : $id);
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Benutzer nicht gefunden'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->User->save($this->request->data)) {
                $m = ($my) ? __('Ihr Profil wurde aktualisiert') : __('Der Benutzer wurde geändert');
                $this->Session->setFlash($m, 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
                $this->redirect(array('controller' => 'pages', 'action' => 'display', 'home'));
                return true;
            }
            $this->request->data['User']['organizationalunit_verified'] = $this->User->field('organizationalunit_verified');

            $m = ($my) ? __('Ihr Profil konnte nicht aktualisiert werden') : __('Der Benutzer konnte nicht geändert werden');
            $this->Session->setFlash($m, 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-danger'
            ));
            return false;
        } else {
            $this->request->data = $this->User->read(null, $id);
            unset($this->request->data['User']['password']);
            return true;
        }
    }

    public function delete($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Benutzer nicht gefunden'));
        }
        if ($this->User->delete()) {
            $this->Session->setFlash(__('Der Benutzer wurde gelöscht'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-success'
            ));
            $this->redirect(array('action' => 'index'));
            return true;
        }
        $this->Session->setFlash(__('Der Benutzer konnte nicht gelöscht werden'), 'alert', array(
            'plugin' => 'BoostCake',
            'class' => 'alert-danger'
        ));
        return false;
    }

    public function upgrade() {
        $this->User->id = $this->Session->read('Auth.User.id');
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Benutzer nicht gefunden'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['User']['organizationalunit_fixed'] = '1';
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(sprintf(__('Der Verwaltungsstatus für ´%s´ wurde beantragt'), $this->request->data['Organizationalunit']['name']), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
                $this->redirect(array('controller' => 'pages', 'action' => 'display', 'home'));
                return true;
            }
            $this->Session->setFlash(__('Der Verwaltungsstatus konnte nicht beantragt werden'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-danger'
            ));
            return false;
        } else {
            $this->request->data = $this->User->read(null, $this->User->id);
            unset($this->request->data['User']['password']);

            $error = false;

            if($this->request->data['User']['organizationalunit_fixed'] == 1)
            {
                $error = true;
                $this->Session->setFlash(__('Der Verwaltungsstatus wurde bereits beantragt'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-danger'
                ));
            }
            elseif($this->request->data['User']['organizationalunit_id'] == 0)
            {
                $error = true;
                $this->Session->setFlash(__('Der Verwaltungsstatus kann nur beantragt werden, wenn Sie sich einer Organisationseinheit zugeordnet haben'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-danger'
                ));
                $this->Session->setFlash(__('In Ihren Profileinstellungen können Sie die Organisationseinheit hinterlegen. Gehen Sie dazu auf Ihren Benutzernamen und dort ´Profil bearbeiten´'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-info'
                ), 'info');
            } elseif(strlen($this->request->data['User']['emailaddress']) < 5)
            {
                $error = true;
                $this->Session->setFlash(__('Der Verwaltungsstatus kann nur beantragt werden, wenn Sie eine E-Mail-Adresse angegeben haben'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-danger'
                ));
                $this->Session->setFlash(__('In Ihren Profileinstellungen können Sie die E-Mail-Adresse hinterlegen. Gehen Sie dazu auf Ihren Benutzernamen und dort ´Profil bearbeiten´'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-info'
                ), 'info');
            }
            $this->set(compact('error'));
            return true;
        }
    }

    public function do_upgrade($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Benutzer nicht gefunden'));
        }
        $this->User->set('organizationalunit_verified', '1');
        $this->User->set('role', User::admin);
        if ($this->User->save()) {
            $this->User->clear(); // needed?
            $this->Session->setFlash(__('Der Benutzer wurde freigegeben'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-success'
            ));
            $this->redirect(array('action' => 'index'));
            return true;
        }
        $this->Session->setFlash(__('Der Benutzer konnte nicht freigegeben werden'), 'alert', array(
            'plugin' => 'BoostCake',
            'class' => 'alert-danger'
        ));
        return false;
    }

    //</editor-fold>

    /*
     * backend functions
     */

    //<editor-fold defaultstate="collapsed" desc="backend functions">

    //</editor-fold>
}