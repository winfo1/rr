<div class="container">
    <?php echo $this->Form->create('Room', array(
        'type' => 'file',
        'class' => 'well',
        'inputDefaults' => array(
            'div' => 'form-group',
            'wrapInput' => false,
            'class' => 'form-control',
        )
    )); ?>
    <h1><?php echo __('Raum hinzufügen'); ?></h1>
    <fieldset>
        <legend><?php echo __('Geben Sie hier die Daten des neuen Raumes an'); ?></legend>

        <?php echo $this->Form->input('organizationalunit_id', array('label' => __('Organisationseinheit'), 'options' => $organizationalunits)); ?>

        <?php echo $this->Form->input('building_id', array('label' => __('Gebäude'), 'options' => $buildings)); ?>

        <?php echo $this->Form->input('floor', array('label' => __('Etage'), 'placeholder' => __('Etage'))); ?>

        <?php echo $this->Form->input('number', array('label' => __('Nummer'), 'placeholder' => __('Nummer'))); ?>

        <div class="form-group">
            <label for="RoomImageUrl">Grundriss Bilddatei</label>
            <?php
            echo $this->Form->input('layout_image_url', array(
                'type' => 'file',
                'div' => '',
                'class' => '',
                'label' => false
            ));
            ?>
            <p class="help-block">Es sind nur Bilddateien erlaubt. Das Bild wird mit dem Klick auf Hinzufügen
                hochgeladen.</p>
        </div>

        <?php
        echo $this->Form->input('barrier_free', array(
            'type' => 'checkbox',
            'div' => 'checkbox well well-sm',
            'class' => 'form-group',
            'label' => 'Barrierefrei'
        ));
        ?>

        <?php echo $this->Form->input('seats', array('label' => __('Sitze'), 'placeholder' => __('Sitze'))); ?>

        <div class="form-group">
            <label for="RoomimageImageUrl">Fotos Bilddateien</label>
            <?php
            echo $this->Form->input('Roomimage..image_url', array(
                'type' => 'file',
                'div' => '',
                'class' => '',
                'label' => false,
                'multiple'
            ));
            ?>
            <p class="help-block">Es sind nur Bilddateien erlaubt. Die Bilder werden mit dem Klick auf Hinzufügen
                hochgeladen.</p>
        </div>
        <div class="form-group">
            <label for="RoomRessourceList">Ressourcen</label>
            <div class="well well-sm">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th><?php echo $this->Form->checkbox('all', array('name' => 'CheckAll', 'id' => 'CheckAll')); ?></th>
                        <th><?php echo __('Name'); ?></th>
                        <th><?php echo __('Typ'); ?></th>
                        <th><?php echo __('Wert'); ?></th>
                        <th>Aktionen</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <?php
                echo $this->Form->button(__('Hinzufügen'), array('type' => 'button', 'class' => 'btn btn-primary btn-sm', 'escape' => false, 'data-toggle' => 'modal', 'data-target' => '#addRessource'));
                ?>
            </div>
        </div>
    </fieldset>
    <?php echo $this->Form->end(array('label' => __('Hinzufügen'), 'class' => 'btn btn-primary btn-lg')); ?>

    <div class="modal fade" id="addRessource" tabindex="-1" role="dialog" aria-labelledby="addRessourceLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Ressource hinzufügen</h4>
                </div>
                <div class="modal-body">
                    <?php
                    echo $this->Form->input('', array('label' => __('Name'), 'options' => $resources));
                    echo $this->Form->input('', array('label' => __('Wert')));
                    ?>
                </div>
                <div class="modal-footer">
                    <?php
                    echo $this->Form->button(__('Hinzufügen'), array('type' => 'button', 'class' => 'btn btn-primary', 'escape' => false, 'onclick' => 'return addRessource()'));
                    echo $this->Form->button(__('Abbrechen'), array('type' => 'button', 'class' => 'btn btn-default', 'escape' => false, 'data-dismiss' => 'modal'));
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

    <script type="text/javascript">
        $('#RoomOrganizationalunitId').focus();

        function addRessource() {

            alert('Diese Funktionen müssen noch implementiert werden!');

        }
    </script>