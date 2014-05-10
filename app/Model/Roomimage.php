<?php

App::uses('AppModel', 'Model');

class Roomimage extends AppModel {

    /*
     * basic definitions
     */

    //<editor-fold defaultstate="collapsed" desc="basic definitions">

    public $actsAs = array(
        'Uploader.Attachment' => array(
            'image_url' => array(
                'transforms' => array(
                    'image_small_url' => array(
                        'class' => 'resize',
                        'nameCallback' => 'transformNameCallback',
                        'append' => '-small',
                        'width' => 250,
                        'height' => 250
                    )
                )
            )
        ),
        'Uploader.FileValidation' => array(
            'image' => array(
                'extension' => array('gif', 'jpg', 'png', 'jpeg'),
                'type' => 'image'
            )
        )
    );

    public $belongsTo = array('Room');

    //</editor-fold>

    /*
     * uploader functions
     */

    //<editor-fold defaultstate="collapsed" desc="uploader functions">

    public function beforeUpload($options) {

        $options['finalPath'] = '/img/uploads/';
        $options['uploadDir'] = WWW_ROOT . $options['finalPath'];

        return $options;
    }

    public function beforeTransform($options) {

        $options['finalPath'] = '/img/uploads/' . $options['class'] . '/';
        $options['uploadDir'] = WWW_ROOT . $options['finalPath'];

        return $options;
    }

    public function transformNameCallback($name, $file) {
        return $this->getUploadedFile()->name();
    }

    //</editor-fold>

}