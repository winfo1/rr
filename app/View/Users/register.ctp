<div class="container">
    <?php echo $this->Form->create('User', array(
        'class' => 'well',
        'inputDefaults' => array(
            'div' => 'form-group',
            'wrapInput' => false,
            'class' => 'form-control',
        )
    )); ?>
    <h1><?php echo __('Registrierung'); ?></h1>
    <fieldset>
        <legend>
            <?php echo __('Geben Sie Ihren Benutzername und Ihr Kennwort ein'); ?>
        </legend>

        <?php echo $this->Form->input('username', array('label' => __('Benutzername'), 'placeholder' => __('Benutzername'))); ?>

        <?php echo $this->Form->input('password', array('label' => __('Passwort'), 'placeholder' => __('Passwort'))); ?>

        <?php echo $this->Form->input('password_re', array('type'=>'password', 'label' => __('Passwort erneut eingeben'), 'placeholder' => __('Passwort erneut eingeben'))); ?>
    </fieldset>
    <?php echo $this->Form->end(array('label' => __('Registrieren'), 'class' => 'btn btn-primary btn-lg')); ?>
</div>

    <script type="text/javascript">
        $('#UserUsername').focus();
    </script>