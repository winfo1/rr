<div class="container">
    <ol class="breadcrumb well well-sm">
        <li><?php echo __('Verwaltung'); ?></li>
        <li><?php echo $this->Html->link('Benutzer', array('action' => 'index')); ?></li>
        <li class="active"><?php echo __('Benutzer bearbeiten'); ?></li>
    </ol>
    <?php echo $this->Form->create('User', array(
        'class' => 'well',
        'inputDefaults' => array(
            'div' => 'form-group',
            'wrapInput' => false,
            'class' => 'form-control',
        )
    )); ?>
    <h1><?php echo ($my) ? __('Profil bearbeiten') : __('Benutzer bearbeiten') ?></h1>
    <fieldset>
        <legend>
            <?php echo ($my) ? __('Hier können Sie Ihre Daten bearbeiten') : __('Hier können Sie die Daten des Benutzers bearbeiten') ?>
        </legend>

        <?php echo $this->Form->input('organizationalunit_id', array(
            'label' => __('Organisationseinheit'),
            'disabled' => ($this->request->data['User']['organizationalunit_verified'] == 1),
            'options' => $organizationalunits));
        ?>

        <?php if($this->Session->read('Auth.User.role') == 'root') echo $this->Form->input('role', array('label' => __('Rechte'), 'options' => array('user' => 'Benutzer', 'admin' => 'Admin', 'root' => 'Root'))); ?>

        <?php echo $this->Form->input('emailaddress', array('label' => __('E-Mail-Adresse'), 'placeholder' => __('E-Mail-Adresse'))); ?>

		<?php echo $this->Form->input('phonenumber', array('label' => __('Telefonnummer'), 'placeholder' => __('Telefonnummer'))); ?>

        <legend>
            <?php echo __('E-Mail Optionen'); ?>
        </legend>
        <h5><strong><?php echo ($my) ? __('E-Mails für meine Buchungen') : __('E-Mails für die Buchungen des Benutzers') ?></strong></h5>
        <?php
        echo $this->Form->input('user_email_if_active', array(
            'type' => 'checkbox',
            'div' => 'checkbox',
            'class' => 'form-group',
            'label' => 'bei jeder Buchung'));

        echo $this->Form->input('user_email_if_active_gets_rejected', array(
            'type' => 'checkbox',
            'div' => 'checkbox',
            'class' => 'form-group',
            'label' => 'bei jeder Buchung die abgelehnt wird'));

        echo $this->Form->input('user_email_if_planned', array(
            'type' => 'checkbox',
            'div' => 'checkbox',
            'class' => 'form-group',
            'label' => 'bei jeder Planung'));

        echo $this->Form->input('user_email_if_plan_gets_active', array(
            'type' => 'checkbox',
            'div' => 'checkbox',
            'class' => 'form-group',
            'label' => 'bei jeder Planung die aktiv wird'));

        echo $this->Form->input('user_email_if_plan_gets_rejected', array(
            'type' => 'checkbox',
            'div' => 'checkbox',
            'class' => 'form-group',
            'label' => 'bei jeder Planung die abgelehnt wird'));

        ?>
        <?php if(in_array($this->Session->read('Auth.User.role'), array('admin', 'root'))) : ?>
        <div class="clearfix"></div>
        <h5><strong><?php $s = ($my) ? __('E-Mails für meine Raum Überwachung') : __('E-Mails für die Raum Überwachung des Benutzers') ; echo $s; ?></strong></h5>
        <?php
        echo $this->Form->input('admin_email_every_booking', array(
        'type' => 'checkbox',
        'div' => 'checkbox',
        'class' => 'form-group',
        'label' => 'bei jeder Buchung'));

        echo $this->Form->input('admin_email_every_booking_plan', array(
        'type' => 'checkbox',
        'div' => 'checkbox',
        'class' => 'form-group',
        'label' => 'bei jeder Planung'));

        ?>
        <?php endif; ?>
    </fieldset>
    <?php echo $this->Form->end(array('label' => __('Ändern'), 'class' => 'btn btn-primary btn-lg')); ?>
</div>

    <script type="text/javascript">
        $('#UserOrganizationalunitId').focus();
    </script>