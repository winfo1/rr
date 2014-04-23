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
    <h1><?php echo __('Raum bearbeiten'); ?></h1>
    <fieldset>
        <legend><?php echo __('Hier können Sie die Daten des Raumes ändern'); ?></legend>

        <?php echo $this->Form->input('organizationalunit_id', array('label' => __('Organisationseinheit'), 'options' => $organizationalunits)); ?>

        <?php echo $this->Form->input('building_id', array('label' => __('Gebäude'), 'options' => $buildings)); ?>

        <?php echo $this->Form->input('floor', array('label' => __('Etage'), 'placeholder' => __('Etage'))); ?>

        <?php echo $this->Form->input('number', array('label' => __('Nummer'), 'placeholder' => __('Nummer'))); ?>

        <div class="form-group">
            <label for="RoomImageUrl">Grundriss Bilddatei</label>
            <div class="clearfix"></div>
            <?php
            $layout_image_url = $this->request->data['Room']['layout_image_url'];
            if(strlen($layout_image_url) > 5)
            {
                echo $this->Html->link(
                    $this->Html->image($this->request->data['Room']['layout_image_small_url'], array('alt' => 'Grundriss', 'class' => 'img-thumbnail img-responsive pull-right')),
                    $layout_image_url,
                    array(
                        'title' => 'Grundriss',
                        'escapeTitle' => false,
                        'data-toggle' => 'lightbox',
                        'data-title' => 'Grundriss'
                    )
                );
            }

            echo $this->Form->input('layout_image_url', array(
                'type' => 'file',
                'div' => '',
                'class' => '',
                'label' => false
            ));
            ?>
            <p class="help-block">Es sind nur Bilddateien erlaubt. Das Bild wird mit dem Klick auf Ändern hochgeladen und ggf. durch das aktuelle ersetzt.</p>
        </div>
        <div class="clearfix"></div>

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
            <p class="help-block">Es sind nur Bilddateien erlaubt. Die Bilder werden mit dem Klick auf Ändern hochgeladen und hinzugefügt.</p>

            <?php

            $count=0;
            foreach($this->request->data['Roomimage'] as $roomimage) {

                echo '<div class="col-xs-3" data-toggle="buttons">';

                echo $this->Form->hidden('Image.' . $count . '.id', array('value' => $roomimage['id']));
                echo $this->Form->hidden('Image.' . $count . '.room_id', array('value' => $roomimage['room_id']));

                echo $this->Html->link(
                    $this->Html->image($roomimage['image_small_url'], array('alt' => 'Foto', 'class' => 'img-thumbnail img-responsive')),
                    $roomimage['image_url'],
                    array(
                        'title' => 'Foto',
                        'escapeTitle' => false,
                        'data-toggle' => 'lightbox',
                        'data-gallery' => 'multiimages',
                        'data-title' => 'Foto'
                    )
                );

                echo '<label class="btn btn-danger" style="width: 100%"><input name="data[Image][' . $count . '][delete]" type="checkbox">Löschen</label>';

                $count++;

                echo '</div>';

            }
            ?>
            <div class="clearfix"></div>
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
                    <?php $count = 0; ?>
                    <?php foreach ($this->request->data['Resource'] as $resource): ?>
                        <?php $count++; ?>
                        <tr>
                            <td><?php echo $this->Form->checkbox('Building.id.' . $resource['ResourcesRoom']['id']); ?></td>
                            <td><?php echo $this->Html->link($resource['name'], array('action' => 'edit', $resource['ResourcesRoom']['id']), array('escape' => false)); ?></td>
                            <td class="text-center"><?php echo $type[$resource['type']]; ?></td>
                            <td class="text-center"><?php echo $resource['ResourcesRoom']['value']; ?></td>
                            <td><?php

                                echo $this->Html->link("Bearbeiten", array('action' => 'edit', $resource['ResourcesRoom']['id']));

                                echo ' | ';

                                echo $this->Html->link("Löschen", array('action' => 'edit', $resource['ResourcesRoom']['id']));

                                ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php unset($resource); ?>
                    </tbody>
                </table>
                <?php
                echo $this->Form->button(__('Hinzufügen'), array('type' => 'button', 'class' => 'btn btn-primary btn-sm', 'escape' => false, 'data-toggle' => 'modal', 'data-target' => '#addRessource'));
                ?>
            </div>
        </div>
    </fieldset>
    <?php echo $this->Form->end(array('label' => __('Ändern'), 'class' => 'btn btn-primary btn-lg')); ?>

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