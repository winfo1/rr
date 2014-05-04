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
            <label for="RoomRessourceList"><?php echo __('Ressourcen'); ?></label>
            <div class="well well-sm">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th><?php echo $this->Form->checkbox('all', array('name' => 'CheckAll', 'id' => 'CheckAll')); ?></th>
                        <th><?php echo __('Name'); ?></th>
                        <th><?php echo __('Typ'); ?></th>
                        <th><?php echo __('Wert'); ?></th>
                        <th><?php echo __('Aktionen'); ?></th>
                    </tr>
                    </thead>
                    <tbody id="RoomResources">
                    </tbody>
                </table>
                <?php
                echo $this->Form->button(__('Hinzufügen'), array('type' => 'button', 'class' => 'btn btn-primary btn-sm', 'escape' => false, 'data-toggle' => 'modal', 'data-target' => '#addResource'));
                ?>
            </div>
        </div>
    </fieldset>
    <?php echo $this->Form->end(array('label' => __('Hinzufügen'), 'class' => 'btn btn-primary btn-lg')); ?>

    <div class="modal fade" id="addResource" tabindex="-1" role="dialog" aria-labelledby="addResourceLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="addResourceLabel"><?php echo __('Ressource diesem Raum  hinzufügen'); ?></h4>
                </div>
                <div class="modal-body">
                    <?php
                    echo $this->Form->input('Resource.new.resource_id', array('label' => __('Name'), 'options' => $resources));
                    echo $this->Form->input('Resource.new.value', array('label' => __('Wert')));
                    ?>
                </div>
                <div class="modal-footer">
                    <?php
                    echo $this->Form->button(__('Hinzufügen'), array('type' => 'button', 'class' => 'btn btn-primary', 'escape' => false, 'onclick' => 'return addResource($(\'#ResourceNewResourceId\').val(),$(\'#ResourceNewValue\').val())'));
                    echo $this->Form->button(__('Abbrechen'), array('type' => 'button', 'class' => 'btn btn-default', 'escape' => false, 'data-dismiss' => 'modal'));
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

    <script type="text/javascript">
        var room_resource_index = 1;
        var room_resources = <?php echo json_encode($resources_all); ?>;
        var room_resource_types = <?php echo json_encode($type); ?>;

        function getResource(id) {
            var r = null;
            $.each(room_resources, function(index, value) {
                if(id == value.Resource.id) {
                    r = value.Resource;
                }
            });
            return r;
        }

        function getResourceTypeName(typeid) {
            return room_resource_types[typeid];
        }

        function addResource(id, value) {

            var res = getResource(id);

            var new_room_resource = '<tr id="ResourceIdNew' + room_resource_index + '">' +
                '<input type="hidden" name="data[Resource][resource_id][new][' + room_resource_index + ']" value="' + id + '"/>' +
                '<input type="hidden" name="data[Resource][value][new][' + room_resource_index + ']" value="' + value + '"/>' +
                '<td></td>' +
                '<td>' + res.name + '</td>' +
                '<td class="text-center">' + getResourceTypeName(res.type) + '</td>' +
                '<td class="text-center">' + value +  '</td>' +
                '<td>&nbsp;<?php echo $this->Form->button(__('Löschen'), array('type' => 'button', 'class' => 'btn btn-danger btn-sm', 'escape' => false, 'onclick' => 'return delResource(\\\'ResourceIdNew\' + room_resource_index + \'\\\')')); ?></td></tr>';

            $(new_room_resource).appendTo('#RoomResources');

            room_resource_index++;

            $('#addResource').modal('hide');
        }

        function editResource(id) {

        }

        function delResource(id) {
            $('#' + id).remove();
        }

        $('#RoomOrganizationalunitId').focus();
    </script>