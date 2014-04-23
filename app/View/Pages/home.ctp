<div class="container">
    <div class="jumbotron">
        <h1>Willkommen zur <?php echo Configure::read('display.Name'); ?>!</h1>
        <p class="lead">Dieser einfache Dienst ermöglicht es Ihnen Räume des Departments für Wirtschaftsinformatik zu buchen.</p>
        <?php if($this->Session->check('Auth.User.username')) : ?>
            <p><?php echo $this->Html->link('Loslegen', array('controller' => 'bookings', 'action' => 'add'), array('class' => 'btn btn-lg btn-success', 'role' => 'button')); ?></p>
        <?php else : ?>
            <p><?php echo $this->Html->link('Mitmachen', array('controller' => 'users', 'action' => 'register'), array('class' => 'btn btn-lg btn-success', 'role' => 'button')); ?></p>

        <?php endif; ?>
    </div>
</div>