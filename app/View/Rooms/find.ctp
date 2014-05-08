<?php setlocale(LC_ALL, 'de_DE@euro', 'de_DE', 'deu_deu'); ?>
<div class="container">
    <?php echo $this->Form->create('Room', array(
        'url' => array_merge(array('action' => 'find'), $this->params['pass']),
        'class' => 'well',
        'inputDefaults' => array(
            'div' => 'form-group',
            'wrapInput' => false,
            'class' => 'form-control',
        )
    )); ?>
    <h1><?php echo __('Raum suchen'); ?></h1>
    <fieldset>
        <legend><?php echo __('Geben Sie hier die Daten des zu suchenden Raumes an'); ?></legend>

        <?php echo $this->Form->input('name', array('label' => __('Name'), 'placeholder' => __('Name'))); ?>

        <?php echo $this->Form->input('organizationalunit_id', array('label' => __('Organisationseinheit'), 'options' => $organizationalunits, 'empty' => __('(Bitte auswählen)'))); ?>

        <?php echo $this->Form->input('building_id', array('label' => __('Gebäude'), 'options' => $buildings, 'empty' => __('(Bitte auswählen)'))); ?>

        <?php echo $this->Form->input('floor', array('label' => __('Etage'), 'placeholder' => __('Etage'))); ?>

        <?php echo $this->Form->input('number', array('label' => __('Nummer'), 'placeholder' => __('Nummer'))); ?>

        <?php echo $this->Form->input('barrier_free', array('label' => __('Barrierefrei'), 'options' => array('1' => 'ja', '0' => 'nein'), 'empty' => __('(Bitte auswählen)'))); ?>

        <?php echo $this->Form->input('seats', array('label' => __('Sitze'), 'placeholder' => __('Sitze'))); ?>

        <?php echo $this->Form->hidden('view_tabs'); ?>

        <ul class="nav nav-tabs">
            <li style="padding: 10px 15px 10px 0px;"><strong><?php echo __('Zeitpunkt'); ?></strong></li>
            <li class="<?php echo (($this->request->data['Room']['view_tabs'] == 'w') ? 'active' : '')?>"><a href="#without_time_settings" data-toggle="tab"><?php echo __('Ohne'); ?></a></li>
            <li class="<?php echo (($this->request->data['Room']['view_tabs'] == 's') ? 'active' : '')?>"><a href="#simple_time_settings" data-toggle="tab"><?php echo __('Einfach'); ?></a></li>
            <li class="<?php echo (($this->request->data['Room']['view_tabs'] == 'a') ? 'active' : '')?>"><a href="#advanced_time_settings" data-toggle="tab"><?php echo __('Erweitert'); ?></a></li>
        </ul>

        <div class="tab-content well well-sm">
            <div class="tab-pane fade<?php echo (($this->request->data['Room']['view_tabs'] == 'w') ? ' in active' : '')?>" id="without_time_settings">
                <?php echo __('Der Zeitpunkt wird bei der Suche nicht berücksichtigt'); ?>
            </div>
            <div class="tab-pane fade<?php echo (($this->request->data['Room']['view_tabs'] == 's') ? ' in active' : '')?>" id="simple_time_settings">
                <div class="form-inline">
                    <div class="form-group">
                        <label><?php echo __('Startzeit'); ?></label>
                        <div class="btn-group" data-toggle="buttons" style="margin: 5px">
                            <label class="btn btn-default active"><input type="radio" name="data[Room][start_minutes]" value="0" checked><?php echo __('jetzt'); ?></label>
                            <label class="btn btn-default"><input type="radio" name="data[Room][start_minutes]" value="15"><?php echo __('in 15 Min.'); ?></label>
                            <label class="btn btn-default"><input type="radio" name="data[Room][start_minutes]" value="30"><?php echo __('in 30 Min.'); ?></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><?php echo __('Dauer'); ?></label>
                        <div class="btn-group" data-toggle="buttons" style="margin: 5px">
                            <label class="btn btn-default active"><input type="radio" name="data[Room][duration]" value="30" checked><?php echo __('für 30 Min.'); ?></label>
                            <label class="btn btn-default"><input type="radio" name="data[Room][duration]" value="60"><?php echo __('für 1 Std.'); ?></label>
                            <label class="btn btn-default"><input type="radio" name="data[Room][duration]" value="120"><?php echo __('für 2 Std.'); ?></label>
                            <label class="btn btn-default"><input type="radio" name="data[Room][duration]" value="1440"><?php echo __('ganztägig'); ?></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade<?php echo (($this->request->data['Room']['view_tabs'] == 'a') ? ' in active' : '')?>" id="advanced_time_settings">
                <div class="form-inline">
                    <div class="form-group">
                        <label for="RoomDayView"><?php echo __('Tag'); ?></label>
                        <div class="input-group date form_date col-md-2" data-date-format="d M yyyy" data-link-field="data[Room][day_view]">
                            <?php
                            $val = strftime((WIN ? '%#d' : '%e') . ' %b %Y', strtotime($this->request->data['Room']['day']));
                            if(WIN)
                                $val = utf8_encode($val);
                            echo $this->Form->input('day_view', array(
                                'type' => 'text',
                                'div' => false,
                                'style' => 'width: 100%',
                                'label' => false,
                                'size' => '16',
                                'readonly' => true,
                                'value' => $val));
                            ?>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                        <?php echo $this->Form->hidden('day', array('value' => $this->request->data['Room']['day'])); ?>
                        <label for="RoomStartHour"><?php echo __('Startzeit'); ?></label>
                        <div class="input-group clockpicker col-md-2" data-placement="bottom" data-align="left" data-autoclose="true">
                            <?php echo $this->Form->input('start_hour', array('div' => false, 'label' => false, 'placeholder' => 'Startzeit', 'value' => $this->request->data['Room']['start_hour'])); ?>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-time"></span>
                            </span>
                        </div>
                        <label for="RoomEndHour"><?php echo __('Endzeit'); ?></label>
                        <div class="input-group clockpicker col-md-2" data-placement="bottom" data-align="left" data-autoclose="true">
                            <?php echo $this->Form->input('end_hour', array('div' => false, 'label' => false, 'placeholder' => 'Ende', 'value' => $this->request->data['Room']['end_hour'])); ?>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-time"></span>
                            </span>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <?php // echo $this->Form->input('Resource.0.id', array('label' => __('Ressourcen'), 'options' => $resources, 'empty' => __('(Bitte auswählen)'))); ?>

    </fieldset>
    <?php echo $this->Form->end(array('label' => __('Suchen'), 'class' => 'btn btn-primary btn-lg')); ?>


    <h1><?php echo __('Suchergebnis'); ?></h1>
    <?php if(isset($rooms) && (count($rooms) > 1)) : ?>
    <table class="table table-hover">
        <thead>
        <tr>
            <th><?php echo $this->Form->checkbox('all', array('name' => 'CheckAll', 'id' => 'CheckAll')); ?></th>
            <th><?php echo $this->Paginator->sort('name', 'Name'); ?></th>
            <th><?php echo $this->Paginator->sort('Organizationalunit.name', 'Organisationseinheit'); ?></th>
            <th><?php echo $this->Paginator->sort('Building.name', 'Gebäude'); ?></th>
            <th><?php echo $this->Paginator->sort('floor', 'Etage'); ?></th>
            <th><?php echo $this->Paginator->sort('number', 'Nummer'); ?></th>
            <th><?php echo $this->Paginator->sort('barrier_free', 'Barrierefrei'); ?></th>
            <th><?php echo $this->Paginator->sort('seats', 'Sitze'); ?></th>
            <th><?php echo __('Aktionen'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php $count = 0; ?>
        <?php foreach ($rooms as $room): ?>
            <?php $count++; ?>
            <tr>
                <td><?php echo $this->Form->checkbox('Room.id.' . $room['Room']['id']); ?></td>
                <td><?php echo $this->Html->link($room['Room']['name'], array('controller' => 'bookings', 'action' => 'add', $room['Room']['id']), array('escape' => false)); ?></td>
                <td class="text-center"><?php echo $room['Organizationalunit']['name']; ?></td>
                <td class="text-center"><?php echo $room['Building']['name']; ?></td>
                <td class="text-center"><?php echo $room['Room']['floor']; ?></td>
                <td class="text-center"><?php echo $room['Room']['number']; ?></td>
                <td class="text-center"><?php echo $room['Room']['barrier_free']; ?></td>
                <td class="text-center"><?php echo $room['Room']['seats']; ?></td>
                <td><?php echo $this->Html->link(__('Buchen'), array('controller' => 'bookings', 'action' => 'add', $room['Room']['id'])); ?></td>
            </tr>
        <?php endforeach; ?>
        <?php unset($room); ?>
        </tbody>
    </table>

    <ul class="pagination pull-left">
        <?php
        echo $this->Paginator->first('《', array('class' => '', 'tag' => 'li'), null, array('class' => 'disabled', 'tag' => 'li'));
        echo $this->Paginator->prev('〈', array('class' => '', 'tag' => 'li'), null, array('class' => 'disabled', 'tag' => 'li'));
        echo $this->Paginator->numbers(array('tag' => 'li', 'separator' => '', 'currentClass' => 'active', 'currentTag' => 'a'));
        echo $this->Paginator->next('〉', array('class' => '', 'tag' => 'li'), null, array('class' => 'disabled', 'tag' => 'li'));
        echo $this->Paginator->last('》', array('class' => '', 'tag' => 'li'), null, array('class' => 'disabled', 'tag' => 'li'));
        ?>
    </ul>
    <?php else : ?>
        <?php echo __('keine Räume nach den entsprechenden Kriterien gefunden'); ?>
    <?php endif; ?>
</div>

    <script type="text/javascript">
        $('.form_date').datetimepicker({
            language: 'de',
            weekStart: 1,
            todayBtn: 1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            minView: 2,
            forceParse: 0,
            pickerPosition: 'bottom-left',
            linkField: "RoomDay",
            linkFormat: "yyyy-mm-dd"
        });

        $('#RoomStartHour').change(function() {
            d = this.value.indexOf(':');
            h1 = this.value.substr(0, d);
            m1 = this.value.substr(d + 1);

            var RoomEndHour = $('#RoomEndHour');
            d = RoomEndHour.val().indexOf(':');
            h2 = RoomEndHour.val().substr(0, d);
            m2 = RoomEndHour.val().substr(d + 1);

            if(h1 > h2) {
                if(m1 > m2) {
                    RoomEndHour.val(h1 + ':' + m1);
                } else {
                    RoomEndHour.val(h1 + ':' + m2);
                }
            } if((h1 == h2) && (m1 > m2)) {
                RoomEndHour.val(h2 + ':' + m1);
            }
        });

        $('#RoomEndHour').change(function() {
            d = this.value.indexOf(':');
            h2 = this.value.substr(0, d);
            m2 = this.value.substr(d + 1);

            var RoomStartHour = $('#RoomStartHour');
            d = RoomStartHour.val().indexOf(':');
            h1 = RoomStartHour.val().substr(0, d);
            m1 = RoomStartHour.val().substr(d + 1);

            if(h1 > h2) {
                if(m1 > m2) {
                    this.value = h1 + ':' + m1;
                } else {
                    this.value = h1 + ':' + m2;
                }
            } if((h1 == h2) && (m1 > m2)) {
                this.value = h2 + ':' + m1;
            }
        });

        $('.clockpicker').clockpicker();

        $(document).ready(function () {

            var RoomViewTabs = $('#RoomViewTabs');

            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
                RoomViewTabs.val($(e.target).attr('href').charAt(1));
            });

            $('#RoomName').focus();
        });
    </script>