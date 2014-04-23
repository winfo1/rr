<div class="container">
    <?php echo $this->Form->create('Building', array(
        'class' => 'well',
        'inputDefaults' => array(
            'div' => 'form-group',
            'wrapInput' => false,
            'class' => 'form-control',
        )
    )); ?>
    <h1><?php echo __('Gebäude hinzufügen'); ?></h1>
    <fieldset>
        <legend><?php echo __('Geben Sie hier die Daten des neuen Gebäudes an'); ?></legend>

        <?php echo $this->Form->input('short', array('label' => __('Abkürzung'), 'placeholder' => __('Abkürzung'))); ?>

        <?php echo $this->Form->input('name', array('label' => __('Name'), 'placeholder' => __('Name'))); ?>

        <?php echo $this->Form->input('description', array('type' => 'textarea', 'label' => __('Beschreibung'), 'placeholder' => __('Beschreibung'))); ?>
    </fieldset>
    <?php echo $this->Form->end(array('label' => __('Hinzufügen'), 'class' => 'btn btn-primary btn-lg')); ?>
</div>

    <script type="text/javascript">
        $('#BuildingShort').focus();
    </script>