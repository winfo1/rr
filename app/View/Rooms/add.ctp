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
            <label for="RoomImageUrl"><?php echo __('Grundriss Bilddatei'); ?></label>
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
                <?php echo $this->Form->button(__('Hinzufügen'), array('type' => 'button', 'class' => 'btn btn-primary btn-sm', 'style' => 'margin-top: 20px;', 'escape' => false, 'data-toggle' => 'modal', 'data-target' => '#addResource')); ?>
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

    <div class="modal fade" id="editResource" tabindex="-1" role="dialog" aria-labelledby="editResourceLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="editResourceLabel"><?php echo __('Ressource für diesem Raum bearbeiten'); ?></h4>
                </div>
                <div class="modal-body">
                    <?php
                    echo $this->Form->hidden('Resource.edit.c');
                    echo $this->Form->input('Resource.edit.resource_id', array('label' => __('Name'), 'disabled' => true, 'options' => $resources));
                    echo $this->Form->input('Resource.edit.value', array('label' => __('Wert')));
                    ?>
                </div>
                <div class="modal-footer">
                    <?php
                    echo $this->Form->button(__('Ändern'), array('type' => 'button', 'class' => 'btn btn-primary', 'escape' => false, 'onclick' => 'return editResource($(\'#ResourceEditC\').val(),$(\'#ResourceEditResourceId\').val(),$(\'#ResourceEditValue\').val())'));
                    echo $this->Form->button(__('Abbrechen'), array('type' => 'button', 'class' => 'btn btn-default', 'escape' => false, 'data-dismiss' => 'modal'));
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

    <script type="text/javascript">
        var room_resource_c = 0;
        var room_resources = <?php echo json_encode($resources_all); ?>;
        var room_resource_types = <?php echo json_encode($type); ?>;
        var room_resource_used = [];

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

            if($.inArray(id, room_resource_used) != -1) {
                alert('Diese Ressource "' + res.name + '" wurde schon verwendet und kann somit nicht erneut hinzugefügt werden!');
                return false;
            }

            if(!valResource(getResourceTypeName(res.type), value)) {
                alert('Diese Ressource "' + res.name + '" kann keinen Wert in dem Format "' + value + '" speichern!');
                return false;
            }

            room_resource_used.push(id);

            var new_room_resource = '<tr id="Resource' + room_resource_c + '">' +
                '<input type="hidden" name="data[Resource][' + room_resource_c + '][id]" value="' + id + '" id="Resource' + room_resource_c + 'Id"/>' +
                '<input type="hidden" name="data[Resource][' + room_resource_c + '][ResourcesRoom][resource_id]" value="' + id + '" id="Resource' + room_resource_c + 'ResourcesRoomResourceId"/>' +
                '<input type="hidden" name="data[Resource][' + room_resource_c + '][ResourcesRoom][value]" value="' + value + '" id="Resource' + room_resource_c + 'ResourcesRoomValue"/>' +
                '<td><input type="checkbox" /></td>' +
                '<td>' + res.name + '</td>' +
                '<td class="text-center">' + getResourceTypeName(res.type) + '</td>' +
                '<td id="Resource' + room_resource_c + 'Display" class="text-center">' + value +  '</td>' +
                '<td><?php

                echo $this->Form->button(__('Bearbeiten'), array('type' => 'button', 'class' => 'btn btn-default btn-sm', 'escape' => false, 'data-c' => '\' + room_resource_c + \'', 'data-toggle' => 'modal', 'data-target' => '#editResource'));

                echo '&nbsp;';

                echo $this->Form->button(__('Löschen'), array('type' => 'button', 'class' => 'btn btn-danger btn-sm', 'escape' => false, 'onclick' => 'return delResource(\' + room_resource_c + \')'));

                ?></td></tr>';

            $(new_room_resource).appendTo('#RoomResources');

            room_resource_c++;

            $('#addResource').modal('hide');

            return true;
        }

        function editResource(c, id, value) {

            var res = getResource(id);

            if(!valResource(getResourceTypeName(res.type), value)) {
                alert('Diese Ressource "' + res.name + '" kann keinen Wert in dem Format "' + value + '" speichern!');
                return false;
            }

            $('#Resource' + c + 'ResourcesRoomValue').val(value);
            $('#Resource' + c + 'Display').text(value);

            $('#editResource').modal('hide');

            return true;
        }

        function delResource(id) {
            $('#Resource' + id).remove();
        }

        function valResource(type, value) {

            switch(type) {
                case 'bool':
                    return value.match(/^(0|1)$/i);
                case 'int':
                    return $.isNumeric(value);
            }
            return false;
        }

        $(document).on('click', 'button[data-c]', function () {
            var c = $(this).data('c');
            var id = $('#Resource' + c + 'Id').val();
            var value = $('#Resource' + c + 'ResourcesRoomValue').val();

            $('.modal-body #ResourceEditC').val(c);
            $('.modal-body #ResourceEditResourceId').val(id);
            $('.modal-body #ResourceEditValue').val(value);
        });

        $('#RoomOrganizationalunitId').focus();
    </script>