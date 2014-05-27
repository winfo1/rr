<div class="container">
    <ol class="breadcrumb well well-sm">
        <li><?php echo __('Verwaltung'); ?></li>
        <li><?php echo $this->Html->link('Gebäude', array('action' => 'index')); ?></li>
        <li class="active"><?php echo __('Gebäude bearbeiten'); ?></li>
    </ol>
    <?php echo $this->Form->create('Building', array(
        'class' => 'well',
        'inputDefaults' => array(
            'div' => 'form-group',
            'wrapInput' => false,
            'class' => 'form-control',
        )
    )); ?>
    <h1><?php echo __('Gebäude bearbeiten'); ?></h1>
    <fieldset>
        <legend><?php echo __('Hier können Sie die Daten des Gebäudes bearbeiten'); ?></legend>

        <?php echo $this->Form->input('short', array('label' => __('Abkürzung'), 'placeholder' => __('Abkürzung'))); ?>

        <?php echo $this->Form->input('name', array('label' => __('Name'), 'placeholder' => __('Name'))); ?>

        <?php echo $this->Form->input('description', array('type' => 'textarea', 'label' => __('Beschreibung'), 'placeholder' => __('Beschreibung'))); ?>
    </fieldset>
    <?php echo $this->Form->end(array('label' => __('Ändern'), 'class' => 'btn btn-primary btn-lg')); ?>
</div>

    <script type="text/javascript">
        $('#BuildingShort').focus();
    </script>