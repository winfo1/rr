<div class="container">
    <?php echo $this->Form->create('Resource', array(
        'class' => 'well',
        'inputDefaults' => array(
            'div' => 'form-group',
            'wrapInput' => false,
            'class' => 'form-control',
        )
    )); ?>
    <h1><?php echo __('Ressource hinzufügen'); ?></h1>
    <fieldset>
        <legend><?php echo __('Geben Sie hier die Daten der neuen Ressource an'); ?></legend>

        <?php echo $this->Form->input('name', array('label' => __('Name'), 'placeholder' => __('Name'))); ?>

        <?php echo $this->Form->input('type', array('label' => __('Typ'), 'options' => $type)); ?>

        <?php echo $this->Form->input('description', array('type' => 'textarea', 'label' => __('Beschreibung'), 'placeholder' => __('Beschreibung'))); ?>
    </fieldset>
    <?php echo $this->Form->end(array('label' => __('Hinzufügen'), 'class' => 'btn btn-primary btn-lg')); ?>
</div>

    <script type="text/javascript">
        $('#ResourceName').focus();
    </script>