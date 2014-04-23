<?php
class RoomimagesController extends AppController {
    /*
     * basic functions
     */

    //<editor-fold defaultstate="collapsed" desc="basic functions">

    //</editor-fold>

    /*
     * view functions
     */

    //<editor-fold defaultstate="collapsed" desc="view functions">

    public function add($room_id = null, $files = array()) {

        if ($this->request->is('post')) {

            if($room_id != null)
            {
                $id = null;
                return $this->add_silent($room_id, $files, $id);
            }

        }

    }

    //</editor-fold>

    /*
     * helper functions
     */

    //<editor-fold defaultstate="collapsed" desc="helper functions">

    private function add_silent($room_id, $files, &$id)
    {
        $this->Roomimage->create();
        $this->Roomimage->set('room_id', $room_id);
        foreach ($files as $file) {
            $this->Roomimage->set('image_url', $file);
        }

        if ($this->Roomimage->save()) {
            $id = $this->Roomimage->id;
            $this->Roomimage->clear(); // needed?
            return true;
        }
        return false;
    }

    //</editor-fold>

}