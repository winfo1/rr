<div class="container">
    <?php echo $this->Form->create('User', array(
        'class' => 'well',
        'inputDefaults' => array(
            'div' => 'form-group',
            'wrapInput' => false,
            'class' => 'form-control',
        )
    )); ?>
    <h1><?php echo __('Benutzer hinzufügen'); ?></h1>
    <fieldset>
        <legend>
            <?php echo __('Geben Sie hier die Daten des neuen Benutzers an'); ?>
        </legend>

        <?php echo $this->Form->input('organizationalunit_id', array('label' => __('Organisationseinheit'), 'options' => $organizationalunits)); ?>

        <?php echo $this->Form->input('username', array('label' => __('Benutzername'), 'placeholder' => __('Benutzername'))); ?>

        <?php echo $this->Form->input('password', array('label' => __('Passwort'), 'placeholder' => __('Passwort'))); ?>

        <?php echo $this->Form->input('role', array('label' => __('Rechte'), 'options' => array('user' => 'Benutzer', 'admin' => 'Admin', 'root' => 'Root'))); ?>

        <?php echo $this->Form->input('emailaddress', array('label' => __('E-Mail-Adresse'), 'placeholder' => __('E-Mail-Adresse'))); ?>

    </fieldset>
    <?php echo $this->Form->end(array('label' => __('Hinzufügen'), 'class' => 'btn btn-primary btn-lg')); ?>
</div>

<script type="text/javascript">
    $('#UserOrganizationalunitId').focus();
</script>