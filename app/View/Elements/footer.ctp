<footer>
    <div class="container">
        <?php echo $this->Html->link(
            $this->Html->image('cake.power.gif', array('alt' => 'the rapid development php framework', 'border' => '0')),
            'http://www.cakephp.org/',
            array('target' => '_blank', 'escape' => false)
        );
        ?>
        <div class="pull-right"><?php echo $this->Text->autoLinkEmails('Feedback und Fehler können Sie gerne an ' . Configure::read('display.Support') . ' melden. v' . Configure::read('display.Version')); ?></div>
        <hr />
        <div class="muted credit pull-left">Copyright &copy; <?php echo date('Y'); ?> <?php echo $this->Html->link(Configure::read('display.Orga'), 'http://wiwi.uni-paderborn.de/dep3/betriebswirtschaftliche-informationssysteme-prof-joachim-fischer/', array('target' => '_blank')); ?> | <?php echo $this->Html->link('Department für Wirtschaftsinformatik', 'http://wiwi.uni-paderborn.de/', array('target' => '_blank')); ?> | <?php echo $this->Html->link('Universität Paderborn', 'http://uni-paderborn.de/', array('target' => '_blank')); ?> | <?php echo $this->Html->link('Impressum', array('controller' => 'pages', 'action' => 'display', 'imprint')); ?></div>
        <div class="muted credit pull-right"><?php echo __('realisiert durch'); ?> <?php echo Configure::read('display.Author'); ?></div>
    </div>
</footer>