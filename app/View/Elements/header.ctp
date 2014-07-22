<nav class="navbar navbar-default" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <?php echo $this->Html->link(Configure::read('display.Name'), array('controller' => 'pages', 'action' => 'display', 'home'), array('class' => 'navbar-brand')); ?>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><?php echo $this->Html->link('<span class="glyphicon glyphicon-plus glyphicon-responsive" title="Buchen"></span> <span class="text-responsive">Buchen</span>', array('controller' => 'bookings', 'action' => 'add'), array('escape' => false)); ?></li>
                <li><?php echo $this->Html->link('<span class="glyphicon glyphicon-calendar glyphicon-responsive" title="Buchungen"></span> <span class="text-responsive">Buchungen</span>', array('controller' => 'bookings', 'action' => 'index'), array('escape' => false)); ?></li>
                <li><?php echo $this->Html->link('<span class="glyphicon glyphicon-search glyphicon-responsive" title="Suchen"></span> <span class="text-responsive">Suchen</span>', array('controller' => 'rooms', 'action' => 'find'), array('escape' => false)); ?></li>
                <?php if(($username != null) && (in_array($role, array('admin', 'root')))) : ?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-cog glyphicon-responsive" title="Verwaltung"></span> <span class="text-responsive">Verwaltung</span> <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php if($role == 'root') : ?>
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
                <?php if($username != null) : ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo h($username); ?> <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><?php echo $this->Html->link('Profil bearbeiten', array('controller' => 'users', 'action' => 'edit', 'my')); ?></li>
                            <?php if($role == 'user') : ?>
                                <li><?php echo $this->Html->link('Verwaltungsstatus beantragen', array('controller' => 'users', 'action' => 'upgrade')); ?></li>
                            <?php endif; ?>
                            <li class="divider"></li>
                            <li><?php echo $this->Html->link('Abmelden', array('controller' => 'users', 'action' => 'logout')); ?></li>
                        </ul>
                    </li>
                <?php else : ?>
                    <li><?php echo $this->Html->link('<span class="glyphicon glyphicon-log-in glyphicon-responsive" title="Anmelden"></span> <span class="text-responsive">Anmelden</span>', array('controller' => 'users', 'action' => 'login'), array('escape' => false)); ?></li>
                    <li class="divider-vertical divider-responsive"></li>
                    <li><?php echo $this->Html->link('<span class="glyphicon glyphicon-new-window glyphicon-responsive" title="Registrieren"></span> <span class="text-responsive">Registrieren</span>', array('controller' => 'users', 'action' => 'register'), array('escape' => false)); ?></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>