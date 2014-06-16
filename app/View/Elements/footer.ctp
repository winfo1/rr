<footer>
    <div class="container">
        <?php echo $this->Html->link(
            $this->Html->image('cake.power.gif', array('alt' => 'the rapid development php framework', 'border' => '0')),
            'http://www.cakephp.org/',
            array('target' => '_blank', 'escape' => false, 'data-toggle' => 'tooltip', 'title' => 'CakePHP v' . Configure::version())
        );
        ?>
        <div class="pull-right"><?php $v = 'v' . Configure::read('display.Version'); echo $this->Text->autoLinkEmails('Feedback und Fehler können Sie gerne an ' . Configure::read('display.Support') . ' melden.') . ' ' .  $this->Html->link($v, 'https://github.com/winfo1/rr/releases/tag/' . $v, array('target' => '_blank')); ?></div>
        <hr />
        <div class="muted credit pull-left">Copyright &copy; <?php echo date('Y'); ?> <?php echo $this->Html->link(Configure::read('display.Orga'), Configure::read('display.Website'), array('target' => '_blank')); ?> | <?php echo $this->Html->link(Configure::read('display.Department.Name'), Configure::read('display.Department.Website'), array('target' => '_blank')); ?> | <?php echo $this->Html->link(Configure::read('display.University.Name'), Configure::read('display.University.Website'), array('target' => '_blank')); ?> | <?php echo $this->Html->link(__('Impressum'), array('controller' => 'pages', 'action' => 'display', 'imprint')); ?></div>
        <div class="muted credit pull-right"><?php echo __('realisiert durch'); ?> <?php echo Configure::read('display.Author'); ?></div>
    </div>
</footer>