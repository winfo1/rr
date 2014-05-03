<?php setlocale(LC_ALL, 'de_DE@euro', 'de_DE', 'deu_deu'); ?>
<div class="container">
<?php echo $this->Form->create('Booking', array(
    'class' => 'well',
    'inputDefaults' => array(
        'div' => 'form-group',
        'wrapInput' => false,
        'class' => 'form-control',
    )
)); ?>
    <h1><?php echo __('Raum buchen'); ?></h1>
    <fieldset>
        <legend><?php echo __('Geben Sie hier die Daten der neuen Buchung an'); ?></legend>

        <?php echo $this->Form->input('name', array(
            'class' => 'form-control typeahead input-block-level',
            'label' => __('Bezeichnung'),
            'placeholder' => 'Bezeichnung'));
        ?>

        <?php echo $this->Form->hidden('view_tabs', array('value' => $view_tabs)); ?>

        <ul class="nav nav-tabs">
            <li class="<?php echo (($view_tabs == 's') ? 'active' : '')?>"><a href="#simple_time_settings" data-toggle="tab"><?php echo __('Einfach'); ?></a></li>
            <li class="<?php echo (($view_tabs == 'a') ? 'active' : '')?>"><a href="#advanced_time_settings" data-toggle="tab"><?php echo __('Erweitert'); ?></a></li>
        </ul>

        <div class="tab-content well well-sm">
            <div class="tab-pane fade<?php echo (($view_tabs == 's') ? ' in active' : '')?>" id="simple_time_settings">
                <div class="form-inline">
                    <div class="form-group">
                        <label><?php echo __('Startzeit'); ?></label>
                        <div class="btn-group" data-toggle="buttons" style="margin: 5px">
                            <label class="btn btn-default active"><input type="radio" name="data[Booking][start_minutes]" value="0" checked><?php echo __('jetzt'); ?></label>
                            <label class="btn btn-default"><input type="radio" name="data[Booking][start_minutes]" value="15"><?php echo __('in 15 Min.'); ?></label>
                            <label class="btn btn-default"><input type="radio" name="data[Booking][start_minutes]" value="30"><?php echo __('in 30 Min.'); ?></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><?php echo __('Dauer'); ?></label>
                        <div class="btn-group" data-toggle="buttons" style="margin: 5px">
                            <label class="btn btn-default active"><input type="radio" name="data[Booking][duration]" value="30" checked><?php echo __('für 30 Min.'); ?></label>
                            <label class="btn btn-default"><input type="radio" name="data[Booking][duration]" value="60"><?php echo __('für 1 Std.'); ?></label>
                            <label class="btn btn-default"><input type="radio" name="data[Booking][duration]" value="120"><?php echo __('für 2 Std.'); ?></label>
                            <label class="btn btn-default"><input type="radio" name="data[Booking][duration]" value="1440"><?php echo __('ganztägig'); ?></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade<?php echo (($view_tabs == 'a') ? ' in active' : '')?>" id="advanced_time_settings">
                <div class="form-inline">
                    <div class="form-group">
                        <label for="BookingDayView"><?php echo __('Tag'); ?></label>
                        <div class="input-group date form_date col-md-2" data-date-format="dd MM yyyy" data-link-field="data[Booking][day_view]">
                            <?php
                            $val = strftime('%d %B %Y', strtotime($day));
                            if(WIN)
                                $val = utf8_encode($val);
                            echo $this->Form->input('day_view', array(
                                'type' => 'text',
                                'div' => false,
                                'class' => 'form-control',
                                'style' => 'width: 100%',
                                'label' => false,
                                'size' => '16',
                                'readonly' => true,
                                'value' => $val));
                            ?>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                        <?php echo $this->Form->hidden('day', array('value' => $day)); ?>
                        <label for="BookingStartHour"><?php echo __('Startzeit'); ?></label>
                        <div class="input-group clockpicker col-md-2" data-placement="bottom" data-align="left" data-autoclose="true">
                            <?php echo $this->Form->input('start_hour', array('div' => false, 'label' => false, 'placeholder' => 'Startzeit', 'value' => $start_hour)); ?>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-time"></span>
                            </span>
                        </div>
                        <label for="BookingEndHour"><?php echo __('Endzeit'); ?></label>
                        <div class="input-group clockpicker col-md-2" data-placement="bottom" data-align="left" data-autoclose="true">
                            <?php echo $this->Form->input('end_hour', array('div' => false, 'label' => false, 'placeholder' => 'Ende', 'value' => $end_hour)); ?>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-time"></span>
                            </span>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <?php echo $this->Form->input('room_id', array(
            'div' => array('div' => 'form-group', 'id' => 'Room'),
            'label' => __('Raum'),
            'selected' => $room_id,
            'options' => $rooms));
        ?>

        <?php
        $options = array('0' => 'ohne', '1' => 'täglich', '7' => 'wöchenlich', '14' => 'alle 2 Wochen', '28' => 'jeden Monat', '56' => 'jeden 2ten Monat');

        echo $this->Form->input('interval_iteration', array(
            'label' => __('Wiederholung'),
            'selected' => array_keys($options)[0],
            'options' => $options));
        ?>

        <div class="form-group" id="BookingIntervalGroup" style="display: none">
            <label class="control-label"><?php echo __('Ende'); ?></label>

            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon">
                        <label style="text-align: left; width: 80px">
                            <input type="radio" name="data[Booking][interval_type]" value="A" checked=""> Nach
                        </label>
                    </span>
                    <?php
                    $options = array('1' => '1 Wiederholung');
                    for ($i = 2; $i < 10; $i++) {
                        $options[$i] = $i . ' Wiederholungen';
                    }

                    echo $this->Form->input('interval_count', array(
                        'div' => false,
                        'label' => false,
                        'selected' => '3',
                        'options' => $options));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">

                    <span class="input-group-addon">
                        <label style="text-align: left; width: 80px">
                            <input type="radio" name="data[Booking][interval_type]" value="B"> Datum
                        </label>
                    </span>

                    <div class="input-group date form_end_date" style="width: 100%" data-date-format="dd MM yyyy" data-link-field="data[Booking][interval_date]">
                        <?php
                        $val = strftime('%d %B %Y', strtotime("+2 weeks"));
                        if(WIN)
                            $val = utf8_encode($val);
                        echo $this->Form->input('interval_date', array(
                            'type' => 'text',
                            'label' => false,
                            'disabled' => true,
                            'readonly' => true,
                            'value' => $val));
                        ?>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
                <?php echo $this->Form->hidden('interval_end', array('value' => date('Y-m-d', strtotime("+2 weeks")))); ?>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon">
                        <label style="text-align: left; width: 80px">
                            <input type="radio" name="data[Booking][interval_type]" value="C"> Dieses
                        </label>
                    </span>
                    <?php
                    $options = array('1' => 'Semester', '2' => 'anfang nächstes Semester', '3' => 'nächstes Semester', '4' => 'Jahr');

                    echo $this->Form->input('interval_range', array(
                        'div' => false,
                        'label' => false,
                        'disabled' => true,
                        'options' => $options));
                    ?>
                </div>
            </div>

        </div>
    </fieldset>
    <?php echo $this->Form->end(array('label' => __('Buchen'), 'class' => 'btn btn-primary btn-lg')); ?>
</div>

<script type="text/javascript">
    var room_details = null;

    function readDetails(id) {

        $.get(rr_base_url + 'ajax/room_details/' + id, function(data, status){
            if(status == "success")
                room_details = $.parseJSON(data);
            else
                room_details = null;

            updateDetails();
        });
    }

    function updateDetails() {

        if(room_details != null)
        {
            var approval_horizon = room_details[0].Organizationalunit.approval_horizon;
            var text;

            switch (approval_horizon) {
                case '-1':
                    text = '';
                    break;
                case '0':
                    text = 'Alle Buchungen müssen erst freigeschaltet werden.';
                    break;
                default:
                    var approval_horizon_text = (approval_horizon > 1) ? 'n' : '';
                    var approval_automatic = room_details[0].Organizationalunit.approval_automatic;
                    var approval_automatic_text = (approval_automatic == 1) ? 'Dies geschieht automatisch.' : 'Diese werden manuell freigegeben.';

                    text = 'Alle Buchungen für diesen Raum, die über den zeitlichen Horizont von ' + approval_horizon + ' Woche' + approval_horizon_text + ' hinausgehen, müssen erst freigeschaltet werden. ' + approval_automatic_text;
                    break;
            }

            var HorizonInfo = $("#HorizonInfo");
            if (HorizonInfo.length > 0){

                if (text != '')
                    HorizonInfo.html(text);
                else
                    HorizonInfo.remove();
            }
            else
            {
                if(text != '') $('<div class="alert alert-info" id="HorizonInfo">' + text + '</div>').prependTo('#Room');
            }

            var room_layout_image_small_url = room_details[0].Room.layout_image_small_url;
            var room_layout_image_url = room_details[0].Room.layout_image_url;
            var room_images = room_details[0].Roomimage;
            var ImageGallery = $("#ImageGallery");

            if (ImageGallery.length > 0) {

                if ((room_images.length > 0) || (room_layout_image_url))
                    ImageGallery.empty();
                else
                    ImageGallery.remove();
            }
            else
            {
                if((room_images.length > 0) || (room_layout_image_url)) $('<div style="margin-top: 5px;" id="ImageGallery"></div>').appendTo('#Room');
            }

			if (room_layout_image_url)
            	addImageToGallery(room_layout_image_url, room_layout_image_small_url);

            for(var i in room_images)
            {
                addImageToGallery(room_images[i].image_url, room_images[i].image_small_url);
            }

            var room_seats = room_details[0].Room.seats;
            var room_resource = room_details[0].Resource;
            var additional_info = '<tr><td>Sitze</td><td>' + room_seats +  '</td></tr>';
            $.each(room_resource, function(index, value) {
                additional_info += '<tr><td>' + value.name + '</td><td>' + value.ResourcesRoom.value +  '</td></tr>';
            });
            var AdditionalInfo = $("#AdditionalInfo");
            if (AdditionalInfo.length > 0){

                AdditionalInfo.html(additional_info);
            }
            else
            {
                $('<div style="margin-top: 15px;" class="well col-md-6 col-md-offset-3"><table class="table"><thead><tr><th>Eigenschaft</th><th>Wert</th></tr></thead><tbody id="AdditionalInfo">' + additional_info + '</tbody></table></div><div class="clearfix"></div>').appendTo('#Room');
            }
        }
    }

    function addImageToGallery(l, i) {
        var img = $('<img />').attr({
            'class': 'img-thumbnail img-responsive',
            'src': rr_base_url + i,
            'alt':'Foto',
            'width': 150,
            'height': 150,
            'style': 'margin: 5px;'
        }).appendTo($('#ImageGallery'));

        img.wrap($('<a >').attr({
            'href': rr_base_url + l,
            'title': 'Foto',
            'data-toggle': 'lightbox',
            'data-gallery': 'multiimages',
            'data-title': 'Foto'
        }));
    }

    var typeahead = new Bloodhound({
        datumTokenizer: function(d) { return d.tokens; },
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        limit: 10,
        remote: { 
        	url: rr_base_url + 'ajax/booking_names',
        	ajax: { cache: false }
        }
    });

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
        linkField: "BookingDay",
        linkFormat: "yyyy-mm-dd"
    });

    $('#BookingStartHour').change(function() {
        d = this.value.indexOf(':');
        h1 = this.value.substr(0, d);
        m1 = this.value.substr(d + 1);

        var BookingEndHour = $('#BookingEndHour');
        d = BookingEndHour.val().indexOf(':');
        h2 = BookingEndHour.val().substr(0, d);
        m2 = BookingEndHour.val().substr(d + 1);

        if(h1 > h2) {
            if(m1 > m2) {
                BookingEndHour.val(h1 + ':' + m1);
            } else {
                BookingEndHour.val(h1 + ':' + m2);
            }
        } if((h1 == h2) && (m1 > m2)) {
            BookingEndHour.val(h2 + ':' + m1);
        }
    });

    $('#BookingEndHour').change(function() {
        d = this.value.indexOf(':');
        h2 = this.value.substr(0, d);
        m2 = this.value.substr(d + 1);

        var BookingStartHour = $('#BookingStartHour');
        d = BookingStartHour.val().indexOf(':');
        h1 = BookingStartHour.val().substr(0, d);
        m1 = BookingStartHour.val().substr(d + 1);

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

    $('.form_end_date').datetimepicker({
        language: 'de',
        weekStart: 1,
        todayBtn: 1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0,
        pickerPosition: 'bottom-left',
        linkField: "BookingIntervalEnd",
        linkFormat: "yyyy-mm-dd"
    });

    $(document).ready(function () {

        var BookingViewTabs = $('#BookingViewTabs');

        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
            BookingViewTabs.val($(e.target).attr('href').charAt(1));
        });

        var BookingRoomId = $('#BookingRoomId');

        typeahead.initialize();

        $('.typeahead').typeahead(null, {
                displayKey: 'name',
                source: typeahead.ttAdapter()
            }
        );

        $('#BookingIntervalIteration').change(function () {
            this.value == 0 ? $('#BookingIntervalGroup').hide() : $('#BookingIntervalGroup').slideDown("fast");
        });

        $('input[name="data[Booking][interval_type]"]').click(function () {
            $('#BookingIntervalCount').prop('disabled', this.value != 'A');
            $('#BookingIntervalDate').prop('disabled', this.value != 'B');
            $('#BookingIntervalRange').prop('disabled', this.value != 'C');
        });

        BookingRoomId.change(function () {
            readDetails(this.value);
        });

        readDetails(BookingRoomId.val());

        $('#BookingName').focus();
    });

</script>