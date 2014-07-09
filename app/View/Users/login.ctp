<style type="text/css">
    body {
        background: url(/img/q-building-1.jpg) no-repeat center center fixed;
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
    <h1><?php echo __('Anmeldung'); ?></h1>
    <fieldset>
        <legend>
            <?php echo __('Geben Sie Ihren Benutzername und Ihr Kennwort ein'); ?>
        </legend>

        <?php echo $this->Form->input('username', array('label' => __('Benutzername'), 'placeholder' => __('Benutzername'))); ?>

        <?php echo $this->Form->input('password', array('label' => __('Passwort'), 'placeholder' => __('Passwort'))); ?>

        <?php
        echo $this->Form->input('auto_login', array(
            'type' => 'checkbox',
            'div' => 'checkbox well well-sm',
            'class' => 'form-group',
            'label' => 'Angemeldet bleiben',
            'title' => __('Erstellt ein Cookie und speichert dieses Login für 4 Wochen.'),
            'checked' => true
        ));

        /*
        echo $this->Form->input('auto_login', array(
            'type' => 'checkbox',
            'before' => $this->Form->label('auto_login', 'Angemeldet bleiben'),
            'div' => 'form-group',
            'class' => false,
            'label' => false,
            'data-on-text' => 'Ja',
            'data-on-color' => 'success',
            'data-off-text' => 'Nein',
            'data-off-color' => 'danger',
            //'title' => __('Erstellt ein Cookie und speichert dieses Login für 4 Wochen.'),
            'checked' => true
        ));
         */
        ?>
    </fieldset>
    <?php echo $this->Form->end(array('label' => __('Anmelden'), 'class' => 'btn btn-primary btn-lg')); ?>
</div>

    <script type="text/javascript">
        $('#UserUsername').focus();
    </script>