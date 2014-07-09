<style type="text/css">
    body {
        background: url(<?php echo $this->Html->url('/img/q-building-3.jpg'); ?>) no-repeat center center fixed;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
    }
</style>
<div class="jumbotron well">
    <h1><?php echo sprintf(__('Willkommen zur %s!'), Configure::read('display.Name')); ?></h1>
    <p class="lead"><?php echo __('Dieser einfache Dienst ermöglicht es Ihnen, Räume des Departments Wirtschaftsinformatik zu buchen.'); ?></p>
    <?php if($this->Session->check('Auth.User.username')) : ?>
        <p><?php echo $this->Html->link('Loslegen', array('controller' => 'bookings', 'action' => 'add'), array('class' => 'btn btn-lg btn-success', 'role' => 'button')); ?></p>
    <?php else : ?>
        <p><?php echo $this->Html->link('Mitmachen', array('controller' => 'users', 'action' => 'register'), array('class' => 'btn btn-lg btn-success', 'role' => 'button')); ?></p>
    <?php endif; ?>
</div>