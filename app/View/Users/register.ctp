<style type="text/css">
    body {
        background: url(/img/q-building-2.jpg) no-repeat center center fixed;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
    }
</style>
<div class="container container-small transparent">
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
            <?php echo __('Legen Sie einen Benutzernamen und Kennwort fest'); ?>
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