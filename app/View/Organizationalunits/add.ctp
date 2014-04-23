<div class="container">
    <?php echo $this->Form->create('Organizationalunit', array(
        'class' => 'well',
        'inputDefaults' => array(
            'div' => 'form-group',
            'wrapInput' => false,
            'class' => 'form-control',
        )
    )); ?>
    <h1><?php echo __('Organisationseinheit hinzufügen'); ?></h1>
    <fieldset>
        <legend><?php echo __('Geben Sie hier die Daten der neuen Organisationseinheit an'); ?></legend>

        <?php echo $this->Form->input('short', array('label' => __('Abkürzung'), 'placeholder' => __('Abkürzung'))); ?>

        <?php echo $this->Form->input('name', array('label' => __('Name'), 'placeholder' => __('Name'))); ?>

        <?php echo $this->Form->input('description', array('type' => 'textarea', 'label' => __('Beschreibung'), 'placeholder' => __('Beschreibung'))); ?>

        <div class="form-group">
            <div class="alert alert-info" id="HorizonInfo">Bei dem Wert ´-1´ werden alle Buchungen direkt freigegeben, bei ´0´ werden alle geplant.</div>
            <?php echo $this->Form->input('approval_horizon', array(
                'div' => false,
                'label' => __('Wochenhorizont für Planung der Räume'),
                'placeholder' => __('Wochenhorizont für Planung der Räume z.B. 3 (für 3 Wochen)')));
            ?>
            <div class="checkbox">
                <?php
                echo $this->Form->input('approval_automatic', array(
                    'type' => 'checkbox',
                    'div' => false,
                    'class' => 'form-group',
                    'label' => 'geplante Buchungen im Horizont automatisch freigeben',
                    'checked' => true
                ));
                ?>
            </div>
        </div>
    </fieldset>
    <?php echo $this->Form->end(array('label' => __('Hinzufügen'), 'class' => 'btn btn-primary btn-lg')); ?>
</div>

    <script type="text/javascript">
        $('#OrganizationalunitShort').focus();
    </script>