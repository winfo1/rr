<?php
/**
 *
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
 * @package       app.View.Errors
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
?>

<div class="container-sm">
    <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <strong><?php echo __d('cake', 'Fehler'); ?>: </strong>
        <?php printf(
            __d('cake', 'Die angeforderte Adresse %s wurde auf diesem Server nicht gefunden'),
            "<strong>'{$url}'</strong>"
        ); ?>
    </div>
</div>


<div class="container">

    <div class="page-header">
        <h1>
            <?php echo $name; ?>
        </h1>
        <h4>
            Diese Seite existiert nicht oder wurde entfernt.
        </h4>
    </div>
    <a href="<?php echo $this->Html->url(array('action' => 'index')); ?>" class="btn btn-primary btn-lg" role="button">Zurück</a>

    <?php
    if (Configure::read('debug') > 0):
        echo $this->element('exception_stack_trace');
    endif;
    ?>
</div>