<?php
$page = '';
if(isset($this->params['pass'][0]))
    $page = $this->params['pass'][0];
$controller = $this->params['controller'];
$action = $this->params['action'];
?>
<div id="header">
    <nav class="navbar navbar-default" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#"><?php echo Configure::read('display.Name'); ?></a>
            </div>

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
            </div>
        </div>
    </nav>
</div>