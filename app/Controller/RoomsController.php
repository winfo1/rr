<?php
class RoomsController extends AppController {
    /*
     * basic functions
     */

    //<editor-fold defaultstate="collapsed" desc="basic functions">

    public $components = array('Paginator', 'Search.Prg');

    public $presetVars = true; // using the model configuration

    public $paginate = array(
        'limit' => 25,
        'order' => array('Room.name' => 'asc')
    );

    public $condition;

    public function beforeFilter() {

        parent::beforeFilter();

        $this->Auth->allow('beforeDetailDisplay', 'getRooms', 'getRoomsAsRoomList', 'find');

        if($this->Session->read('Auth.User.role') == 'admin')
        {
            $this->condition = array('Room.organizationalunit_id =' => $this->Session->read('Auth.User.organizationalunit_id'));
        }
        else
        {
            $this->condition = array();
        }
    }

    public function beforeRender() {

        parent::beforeRender();

        $type = $this->Room->Resource->enum('type');
        $this->set(compact('type'));
    }

    public function isAuthorized($user) {

        if ($user['role'] == 'admin') {

            if($this->action === 'add')
                return true;

            if(in_array($this->action, array('edit', 'delete')))
            {
                $id = $this->request->params['pass'][0];

                if ($this->Room->isOwnedThroughOrganizationalUnitBy($id, $this->Session->read('Auth.User.organizationalunit_id')))
                    return true;
            }
        }

        return parent::isAuthorized($user);
    }

    public function beforeDetailDisplay() {
        $buildings = $this->requestAction('/buildings/getBuildingsAsList/0');
        $this->set(compact('buildings'));

        $organizationalunits = $this->requestAction('/organizationalunits/getOrganizationalunitsAsList/0');

        if($this->Session->read('Auth.User.role') == 'admin')
            array_filter($organizationalunits, function ($var) { return ($var == $this->Session->read('Auth.User.organizationalunit_id')); });

        /* was edit
        if($this->Session->read('Auth.User.role') == 'admin')
        {
            $organizationalunit_id = $this->Session->read('Auth.User.organizationalunit_id');
            if(array_key_exists($organizationalunit_id, $organizationalunits))
                $organizationalunits = array($organizationalunit_id => $organizationalunits[$organizationalunit_id]);
        }
        */

        $this->set(compact('organizationalunits'));

        $resources_all = $this->requestAction('/resources/getResources');
        $this->set(compact('resources_all'));
        $resources = $this->requestAction('/resources/getResourcesAsList', array('pass' => array($resources_all)));
        $this->set(compact('resources'));
    }

    //</editor-fold>

    /*
     * view functions
     */

    //<editor-fold defaultstate="collapsed" desc="view functions">

    public function index() {
        $this->paginate = array(
            'conditions' => $this->condition,
            'limit' => 6,
            'order' => array('Room.name' => 'asc' )
        );
        $rooms = $this->Paginator->paginate('Room');
        $this->set(compact('rooms'));
    }

    public function add() {
        $this->beforeDetailDisplay();
        if ($this->request->is('post')) {
            $this->CleanupImages($this->request->data['Roomimage']);
            $this->Room->create();
            if ($this->Room->saveAssociated($this->request->data)) {
                $this->Session->setFlash(__('Der Raum wurde hinzugefügt'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__('Der Raum konnte nicht hinzugefügt werden'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-danger'
            ));
        }
    }

    public function edit($id = null) {
        $this->beforeDetailDisplay();
        $this->Room->id = $id;
        if (!$this->Room->exists()) {
            throw new NotFoundException(__('Raum nicht gefunden'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->CleanupImages($this->request->data['Roomimage']);

            if(array_key_exists('Resource', $this->request->data)) {
                foreach($this->request->data['Resource'] as $key => $value) {
                    if(array_key_exists('delete', $value['ResourcesRoom'])) {
                        unset($this->request->data['Resource'][$key]);
                    }
                }
            }

            foreach($this->request->data['Roomimage'] as $key => $value) {

                if(array_key_exists('image_url', $value))
                {
                    $this->Room->Roomimage->create();
                    $this->Room->Roomimage->set('room_id', $id);
                    $this->Room->Roomimage->set('image_url', $value['image_url']);
                    $this->Room->Roomimage->save();
                }

            }

            if(array_key_exists('Image', $this->request->data))
            {
                foreach($this->request->data['Image'] as $key => $value) {

                    if(array_key_exists('delete', $value))
                    {
                        $this->Room->Roomimage->id = $value['id'];
                        $this->Room->Roomimage->delete();
                    }

                }
            }

            if ($this->Room->save($this->request->data)) {
                $this->Session->setFlash(__('Das Raum wurde geändert'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__('Der Raum konnte nicht geändert werden'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-danger'
            ));
        } else {
            $this->request->data = $this->Room->read(null, $id);
        }
    }

    public function delete($id = null) {
        $this->Room->id = $id;
        if (!$this->Room->exists()) {
            throw new NotFoundException(__('Raum nicht gefunden'));
        }
        if ($this->Room->delete()) {
            $this->Session->setFlash(__('Der Raum wurde gelöscht'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-success'
            ));
            return $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Der Raum konnte nicht gelöscht werden'), 'alert', array(
            'plugin' => 'BoostCake',
            'class' => 'alert-danger'
        ));
        return $this->redirect(array('action' => 'index'));
    }

    public function find() {

        $this->Room->validate = array();
        $this->beforeDetailDisplay();
        $this->Prg->commonProcess();
        $this->Paginator->settings['conditions'] = $this->Room->parseCriteria($this->Prg->parsedParams());
        $this->set('rooms', $this->Paginator->paginate());

        // set view
        if (!isset($this->request->data['Room']['view_tabs']) ) {
            $this->request->data['Room']['view_tabs'] = 'w';
        }

        // set default day
        if (!isset($this->request->data['Room']['day']) ) {
            $this->request->data['Room']['day'] = (new DateTime())->format('Y-m-d');
        }

        // set default start time
        if (!isset($this->request->data['Room']['start_hour']) ) {
            $this->request->data['Room']['start_hour'] = (new DateTime())->format('H:i');
        }

        // set default end time
        if (!isset($this->request->data['Room']['end_hour']) ) {
            $now = new DateTime();
            $diff = $now->diff(new DateTime('tomorrow'));
            $minutes_to_add = min( ($diff->h * 60) + ($diff->i), 60);
            $this->request->data['Room']['end_hour'] = (new DateTime())->modify('+' . $minutes_to_add . 'minutes')->format('H:i');
        }
    }

    //</editor-fold>

    /*
     * backend functions
     */

    //<editor-fold defaultstate="collapsed" desc="backend functions">

    function getRooms($id = null)
    {
        if(isset($id) && is_numeric($id))
        {
            $condition = array('Room.id =' => $id);
        }
        else
        {
            $condition = array();
        }

        $list = $this->Room->find('all', array(
            'conditions' => $condition,
            'contain' => array('Building', 'Organizationalunit'),
            'order' => array('Room.name' => 'asc' )
        ));

        return $list;
    }

    function getRoomsAsRoomList($data = null) {
        if(!isset($data)) {
            $data = $this->getRooms();
        }

        $result = array();

        foreach ($data as $value) {
            $result[$value['Room']['id']] = $value['Room']['name'];
        }

        return $result;
    }

    //</editor-fold>

    /*
     * helper functions
     */

    //<editor-fold defaultstate="collapsed" desc="helper functions">

    private function CleanupImages(array &$images) {

        foreach($images as $key => $value) {

            if((array_key_exists('image_url', $value)) && ($value['image_url']['error'] != 0))
            {
                unset($images[$key]);
            }

        }

    }

    //</editor-fold>
}