<div class="container">
    <?php echo $this->Form->create('User'); ?>
    <h1><?php echo __('Verwaltungsstatus beantragen'); ?></h1>
    <fieldset>
        <legend>
            <?php echo __('Hier können Sie den Verwaltungsstatus beantragen um Räume zu verwalten'); ?>
        </legend>
        <div class="well"><?php echo __('Mit dem Verwaltungsstatus können Sie in Ihrer Organisationseinheit neue Räume hinzufügen und bearbeiten, sowie dessen Buchungen verwalten. Nachdem Sie dieses Formular abgeschickt haben, wird Ihre Identität geprüft. Dies kann in der Regel ein bis zwei Tage dauern.'); ?></div>

        <input type="hidden" name="data[Organizationalunit][name]" value="<?php echo $this->request->data['Organizationalunit']['name']; ?>"/>

        <div class="form-group">
            <div class="checkbox">
                <?php
                echo $this->Form->input('admin_email_every_booking', array(
                    'label' => 'Hiermit bestätige ich die Beantragung des Verwaltungsstatus',
                    'checked' => false,
                    'disabled' => $error,
                    'type' => 'checkbox',
                    'onclick'=> '$(UserApply).toggleDisabled();'));
                ?>
            </div>
        </div>

    </fieldset>
    <?php echo $this->Form->end(array('label' => __('Beantragen'), 'class' => 'btn btn-primary', 'disabled' => true, 'div' => false, 'id' => 'UserApply')); ?>
</div>