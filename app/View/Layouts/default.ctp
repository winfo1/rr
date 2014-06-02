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
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

setlocale(LC_ALL, 'de_DE@euro', 'de_DE', 'deu_deu');

$rrDescription = __('die schnelle und einfache RaumReservierung');

$page = '';
if(isset($this->params['pass'][0]))
    $page = $this->params['pass'][0];
$controller = $this->params['controller'];
$action = $this->params['action'];
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
        <?php echo Configure::read('display.Short'); ?>:
		<?php echo $rrDescription; ?>:
		<?php echo $title_for_layout; ?>
	</title>
	<?php
    $this->Html->meta('favicon.ico', '/favicon.ico', array('type' => 'icon', 'inline' => false));

    $this->AssetCompress->addCss('bootstrap.css');
    $this->AssetCompress->addCss('bootstrap.user.css');
    $this->AssetCompress->addCss('bootstrap-calendar.css');
    $this->AssetCompress->addCss('bootstrap-clockpicker.css');
    $this->AssetCompress->addCss('bootstrap-datetimepicker.css');
    $this->AssetCompress->addCss('bootstrap-lightbox.css');

    $this->AssetCompress->addScript('jquery.js');
    $this->AssetCompress->addScript('jquery.user.js');
    $this->AssetCompress->addScript('typeahead.bundle.js');
    $this->AssetCompress->addScript('bootstrap.js');
    $this->AssetCompress->addScript('bootstrap-clockpicker.js');
    $this->AssetCompress->addScript('bootstrap-lightbox.js');
    $this->AssetCompress->addScript('underscore.js');
    $this->AssetCompress->addScript('serhioromano/calendar.js');
    $this->AssetCompress->addScript('serhioromano/language/de-DE.js');
    $this->AssetCompress->addScript('smalot/bootstrap-datetimepicker.js');
    $this->AssetCompress->addScript('smalot/locales/bootstrap-datetimepicker.de.js');

    echo $this->fetch('meta') . "\r\n";

    if (Configure::read('debug') > 0) {
        echo "\t" . $this->AssetCompress->includeCss(array('raw' => true)) . "\r\n";
    } else {
        echo "\t" . $this->AssetCompress->includeCss() . "\r\n";
    }


    if (Configure::read('debug') > 0) {
        echo "\t" . $this->AssetCompress->includeJs(array('raw' => true)) . "\r\n";
    } else {
        echo "\t" . $this->AssetCompress->includeJs() . "\r\n";
    }

	?>
</head>
<body>
    <script type="text/javascript">
        var rr_base_url = '<?php echo Router::url('/', true); ?>';
    </script>
    <div class="wrapper">
        <div id="container">
            <div id="header">
                <nav class="navbar navbar-default" role="navigation">
                    <div class="container-fluid">
                        <!-- Brand and toggle get grouped for better mobile display -->
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <a class="navbar-brand" href="#"><?php echo Configure::read('display.Name'); ?></a>
                        </div>

                        <!-- Collect the nav links, forms, and other content for toggling -->
                        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                            <ul class="nav navbar-nav">
                                <li class="<?php echo (($controller == 'pages' && $page == 'home') ? 'active' : '')?>"><?php echo $this->Html->link('Home', array('controller' => 'pages', 'action' => 'display', 'home')); ?></li>
                                <li class="<?php echo (($controller == 'bookings' && $action == 'add') ? 'active' : '')?>"><?php echo $this->Html->link('Buchen', array('controller' => 'bookings', 'action' => 'add')); ?></li>
                                <li class="<?php echo (($controller == 'bookings' && $action == 'index') ? 'active' : '')?>"><?php echo $this->Html->link('Buchungen', array('controller' => 'bookings', 'action' => 'index')); ?></li>
                                <li class="<?php echo (($controller == 'rooms' && $action == 'find') ? 'active' : '')?>"><?php echo $this->Html->link('Suchen', array('controller' => 'rooms', 'action' => 'find')); ?></li>
                                <?php if($this->Session->check('Auth.User.username') && (in_array($this->Session->read('Auth.User.role'), array('admin', 'root')))) : ?>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Verwaltung <b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <?php if($this->Session->check('Auth.User.username') && ($this->Session->read('Auth.User.role') == 'root')) : ?>
                                        <li><?php echo $this->Html->link('Benutzer', array('controller' => 'users', 'action' => 'index')); ?></li>
                                        <li><?php echo $this->Html->link('Gebäude', array('controller' => 'buildings', 'action' => 'index')); ?></li>
                                        <li><?php echo $this->Html->link('Org.einheiten', array('controller' => 'organizationalunits', 'action' => 'index')); ?></li>
                                        <li><?php echo $this->Html->link('Ressourcen', array('controller' => 'resources', 'action' => 'index')); ?></li>
                                        <li><?php echo $this->Html->link('Semester', array('controller' => 'semesters', 'action' => 'index')); ?></li>
                                        <?php endif; ?>
                                        <li><?php echo $this->Html->link('Räume', array('controller' => 'rooms', 'action' => 'index')); ?></li>
                                    </ul>
                                <?php endif; ?>
                            </ul>

                            <?php echo $this->Form->create('Room', array(
                                'url' => array('action' => 'find'),
                                'class' => 'navbar-form navbar-left',
                                'inputDefaults' => array(
                                    'div' => 'form-group',
                                    'wrapInput' => false,
                                    'class' => 'form-control',
                                )
                            )); ?>
                            <?php echo $this->Form->input('name', array('label' => false, 'placeholder' => __('Raum'), 'style' => 'width:74px')); ?>
                            <?php echo $this->Form->end(array('label' => __('Suchen'), 'div' => false, 'class' => 'btn btn-default')); ?>

                            <ul class="nav navbar-nav navbar-right">

                                <?php if($this->Session->check('Auth.User.username')) : ?>
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $this->Session->read('Auth.User.username'); ?> <b class="caret"></b></a>
                                        <ul class="dropdown-menu">
                                            <li><?php echo $this->Html->link('Profil bearbeiten', array('controller' => 'users', 'action' => 'edit', 'my')); ?></li>
                                            <?php if($this->Session->check('Auth.User.username') && ($this->Session->read('Auth.User.role') == 'user')) : ?>
                                            <li><?php echo $this->Html->link('Verwaltungsstatus beantragen', array('controller' => 'users', 'action' => 'upgrade')); ?></li>
                                            <?php endif; ?>
                                            <li class="divider"></li>
                                            <li><?php echo $this->Html->link('Abmelden', array('controller' => 'users', 'action' => 'logout')); ?></li>
                                        </ul>
                                    </li>
                                <?php else : ?>
                                    <li><?php echo $this->Html->link('Anmelden', array('controller' => 'users', 'action' => 'login')); ?></li>
                                    <li class="divider-vertical"></li>
                                    <li><?php echo $this->Html->link('Registieren', array('controller' => 'users', 'action' => 'register')); ?></li>
                                <?php endif; ?>
                            </ul>
                        </div><!-- /.navbar-collapse -->
                    </div><!-- /.container-fluid -->
                </nav>
            </div>
            <div class="container-sm">
                <?php echo $this->Session->flash(); ?>
                <?php echo $this->Session->flash('info'); ?>
            </div>
            <div id="content">
                <?php echo $this->fetch('content'); ?>
            </div>
        </div>
        <div class="push"></div>
    </div>
    <?php echo $this->element('sql_dump'); ?>
    <?php echo $this->element('footer', array(), array('cache' => true)); ?>

    <script type="text/javascript">
        $(document).delegate('*[data-toggle="lightbox"]', 'click', function(event) {
            event.preventDefault();
            $(this).ekkoLightbox();
        });
    </script>

    <?php

    echo $this->Js->writeBuffer();
    ?>
</body>
</html>
