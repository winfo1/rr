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
                            <?php
							echo $this->Form->input('start_minutes', array(
								'type' => 'radio',
								'class' => 'btn btn-default',
								'div' => false,
								'legend' => false,
								'hiddenField' => false,
								'default' => '0',
								'options' => array('0' => __('jetzt'), '15' => __('in 15 Min.'), '30' => __('in 30 Min.'))));
							?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><?php echo __('Dauer'); ?></label>
                        <div class="btn-group" data-toggle="buttons" style="margin: 5px">
                            <?php
							echo $this->Form->input('duration', array(
								'type' => 'radio',
								'class' => 'btn btn-default',
								'div' => false,
								'legend' => false,
								'hiddenField' => false,
								'default' => '30',
								'options' => array('30' => __('für 30 Min.'), '60' => __('für 1 Std.'), '120' => __('für 2 Std.'), '1440' => __('ganztägig'))));
							?>
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
                            echo $this->Form->input('day_view', array(
                                'type' => 'text',
                                'div' => false,
                                'style' => 'width: 100%',
                                'label' => false,
                                'size' => '16',
                                'readonly' => true,
                                'value' => $this->mytime->toReadableDate(strtotime($this->request->data['Room']['day']))));
                            ?>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                        <?php echo $this->Form->hidden('day'); ?>
                        <label for="RoomStartHour"><?php echo __('Startzeit'); ?></label>
                        <div class="input-group clockpicker col-md-2" data-placement="bottom" data-align="left" data-autoclose="true">
                            <?php echo $this->Form->input('start_hour', array('div' => false, 'label' => false, 'placeholder' => 'Startzeit')); ?>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-time"></span>
                            </span>
                        </div>
                        <label for="RoomEndHour"><?php echo __('Endzeit'); ?></label>
                        <div class="input-group clockpicker col-md-2" data-placement="bottom" data-align="left" data-autoclose="true">
                            <?php echo $this->Form->input('end_hour', array('div' => false, 'label' => false, 'placeholder' => 'Ende')); ?>
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

    <?php
    $fields = array(
        'name' => array(),
        'Organizationalunit.name' => array(),
        'Building.name' => array(),
        'Room.floor' => array(),
        'Room.number' => array(),
        'Room.barrier_free' => array(),
        'Room.seats' => array(),
        'created' => array(
            'center' => true,
            'type' => 'datetime',
        ),
        'modified' => array(
            'center' => true,
            'type' => 'datetime',
        )
    );

    function add_booking_button($value, $mainModel) {
        return $value[$mainModel]['id'];
    }

    $links = array(
        'Booking.add' => array(
            'url' => array('controller' => 'bookings', 'action' => 'add', 0, 'add_booking_button()'),
            'options' => array(),
        ),
    );

    $options = array(
        'container' => false,
    );

    echo $this->element('common' . DS . 'index', compact('data', 'fields', 'links', 'options', 'string'));

    ?>
</div>

    <script type="text/javascript">
		var RoomStartHour = $('#RoomStartHour');
		var RoomEndHour = $('#RoomEndHour');
    
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

        RoomStartHour.change(function() {
            d = this.value.indexOf(':');
            h1 = this.value.substr(0, d);
            m1 = this.value.substr(d + 1);

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

        RoomEndHour.change(function() {
            d = this.value.indexOf(':');
            h2 = this.value.substr(0, d);
            m2 = this.value.substr(d + 1);

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

            $('.well #RoomName').focus();
        });
    </script>