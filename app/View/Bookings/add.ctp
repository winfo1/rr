<?php setlocale(LC_ALL, 'de_DE@euro', 'de_DE', 'deu_deu'); ?>
<ol class="breadcrumb well well-sm">
    <li><?php echo __('Verwaltung'); ?></li>
    <li><?php echo $this->Html->link('Buchungen', array('action' => 'index')); ?></li>
    <li class="active"><?php echo __('Buchung hinzufügen'); ?></li>
</ol>
<?php echo $this->Form->create('Booking', array(
    'class' => 'well',
    'inputDefaults' => array(
        'div' => 'form-group',
        'wrapInput' => false,
        'class' => 'form-control',
    )
)); ?>
<h1><?php echo __('Buchung hinzufügen'); ?></h1>
<fieldset>
    <legend><?php echo __('Geben Sie hier die Daten der neuen Buchung an'); ?></legend>

    <?php if($this->request->data['Booking']['group_id'] != 0) : ?>
        <div class="alert alert-info">Diese Buchung wird einer verwandten Buchung hinzugefügt (<?php echo $this->Html->link(__('Abbrechen'), array('action' => 'add', 0, $this->request->data['Booking']['room_id']), array('class' => 'alert-link')); ?>).</div>
    <?php endif; ?>

    <?php echo $this->Form->input('name', array(
        'class' => 'form-control typeahead input-block-level',
        'label' => __('Bezeichnung'),
        'placeholder' => 'Bezeichnung'));
    ?>

    <?php echo $this->Form->hidden('view_tabs'); ?>

    <ul class="nav nav-tabs">
        <li style="padding: 10px 15px 10px 0px;"><strong><?php echo __('Zeitpunkt'); ?></strong></li>
        <li class="<?php echo (($this->request->data['Booking']['view_tabs'] == 's') ? 'active' : '')?>"><a href="#simple_time_settings" data-toggle="tab"><?php echo __('Einfach'); ?></a></li>
        <li class="<?php echo (($this->request->data['Booking']['view_tabs'] == 'a') ? 'active' : '')?>"><a href="#advanced_time_settings" data-toggle="tab"><?php echo __('Erweitert'); ?></a></li>
    </ul>

    <div class="tab-content well well-sm">
        <div class="tab-pane fade<?php echo (($this->request->data['Booking']['view_tabs'] == 's') ? ' in active' : '')?>" id="simple_time_settings">
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
        <div class="tab-pane fade<?php echo (($this->request->data['Booking']['view_tabs'] == 'a') ? ' in active' : '')?>" id="advanced_time_settings">
            <div class="form-inline">
                <div class="form-group" style="margin: 5px;">
                    <label for="BookingDayView" style="margin-right: 5px"><?php echo __('Tag'); ?></label>
                    <?php echo $this->Form->hidden('day'); ?>
                    <div class="input-group date form_date col-md-9" data-date-format="d M yyyy" data-link-field="data[Booking][day_view]">
                        <?php
                        echo $this->Form->input('day_view', array(
                            'type' => 'text',
                            'div' => false,
                            'style' => 'width: 100%',
                            'label' => false,
                            'size' => '16',
                            'readonly' => true,
                            'value' => $this->mytime->toReadableDate(strtotime($this->request->data['Booking']['day']))));
                        ?>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    </div>
                </div>
                <div class="form-group" style="margin: 5px;">
                    <label for="BookingStartHour" style="margin-right: 5px"><?php echo __('Startzeit'); ?></label>
                    <div class="input-group clockpicker col-md-8" data-placement="bottom" data-align="left" data-autoclose="true">
                        <?php echo $this->Form->input('start_hour', array('div' => false, 'label' => false, 'placeholder' => 'Startzeit')); ?>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-time"></span>
                        </span>
                    </div>
                </div>
                <div class="form-group" style="margin: 5px;">
                    <label for="BookingEndHour" style="margin-right: 5px"><?php echo __('Endzeit'); ?></label>
                    <div class="input-group clockpicker col-md-8" data-placement="bottom" data-align="left" data-autoclose="true">
                        <?php echo $this->Form->input('end_hour', array('div' => false, 'label' => false, 'placeholder' => 'Ende')); ?>
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
        'options' => $rooms));
    ?>

    <?php echo $this->Form->hidden('group_id'); ?>

    <?php
    $options = array('0' => 'ohne', '1' => 'täglich', '7' => 'wöchenlich', '14' => 'alle 2 Wochen', '28' => 'jeden Monat', '56' => 'jeden 2ten Monat');

    echo $this->Form->input('interval_iteration', array(
        'label' => __('Wiederholung'),
        'default' => array_keys($options)[0],
        'options' => $options));
    ?>

    <div class="form-group" id="BookingIntervalGroup" style="display: none">
        <label class="control-label"><?php echo __('Ende'); ?></label>

        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon">
                    <?php
                    echo $this->Form->input('interval_type', array(
                        'type' => 'radio',
                        'class' => false,
                        'div' => false,
                        'label' => array('style' => 'text-align: left; width: 80px'),
                        'hiddenField' => false,
                        'default' => 'A',
                        'options' => array('A' => ' ' . __('Nach'))));
                    ?>
                </span>
                <?php
                $options = array('1' => '1 Wiederholung');
                for ($i = 2; $i < 10; $i++) {
                    $options[$i] = $i . ' Wiederholungen';
                }

                echo $this->Form->input('interval_count', array(
                    'div' => false,
                    'label' => false,
                    'default' => '3',
                    'options' => $options));
                ?>
            </div>
        </div>
        <div class="form-group">
            <div class="input-group">

                <span class="input-group-addon">
                    <?php
                    echo $this->Form->input('interval_type', array(
                        'type' => 'radio',
                        'class' => false,
                        'div' => false,
                        'label' => array('style' => 'text-align: left; width: 80px'),
                        'hiddenField' => false,
                        'options' => array('B' => ' ' . __('Datum'))));
                    ?>
                </span>

                <div class="input-group date form_end_date" style="width: 100%" data-date-format="d MM yyyy" data-link-field="data[Booking][interval_date]">
                    <?php
                    $default_end_date = strtotime("+2 weeks");
                    echo $this->Form->input('interval_date', array(
                        'type' => 'text',
                        'label' => false,
                        'default' => $this->mytime->toReadableDate($default_end_date, true),
                        'disabled' => true,
                        'readonly' => true));
                    ?>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                </div>
            </div>
            <?php echo $this->Form->hidden('interval_end', array('default' => date('Y-m-d', $default_end_date))); ?>
        </div>
        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon">
                    <?php
                    echo $this->Form->input('interval_type', array(
                        'type' => 'radio',
                        'class' => false,
                        'div' => false,
                        'label' => array('style' => 'text-align: left; width: 80px'),
                        'hiddenField' => false,
                        'options' => array('C' => ' ' . __('Dieses'))));
                    ?>
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
        <?php echo $this->Form->hidden('ignore_booked'); ?>
    </div>
</fieldset>
<?php echo $this->Form->end(array('label' => __('Buchen'), 'class' => 'btn btn-primary btn-lg', 'onclick' => 'validateInterval();return false;')); ?>

<script type="text/javascript">
    var room_details = null;

    var BookingAddForm = $('#BookingAddForm');
	var BookingViewTabs = $('#BookingViewTabs');
	var BookingStartHour = $('#BookingStartHour');
	var BookingEndHour = $('#BookingEndHour');
	var BookingRoomId = $('#BookingRoomId');
    var BookingGroupId = $('#BookingGroupId');
    var BookingIntervalIteration = $('#BookingIntervalIteration');

    bootbox.setDefaults({locale: 'de'});

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
    
    function updateDisabled(v) {
            $('#BookingIntervalCount').prop('disabled', v != 'A');
            $('#BookingIntervalDate').prop('disabled', v != 'B');
            $('#BookingIntervalRange').prop('disabled', v != 'C');
    }
    
    function updateURL() {
    	var view_tabs = BookingViewTabs.val() == 's';
    	var url = rr_base_url + 'bookings/add/' + BookingGroupId.val() + '/' + BookingRoomId.val() + '/';
    	if (!view_tabs)
    	{
    		url += $('#BookingDay').val() + '/';
    		url += BookingStartHour.val().replace(":", "-") + '/';
    		url += BookingEndHour.val().replace(":", "-") + '/';
    	}
        window.history.pushState("", "", url);
        BookingAddForm.attr("action", url);
        
    }

    function validateInterval() {
        if(BookingIntervalIteration.val() != 0) {
            $.post(rr_base_url + 'ajax/check_booked/', BookingAddForm.serialize(), function(data, status) {
                if(status == "success") {
                    var interval_booking = $.parseJSON(data);
                    if(interval_booking.hasErrorInIntervalLoop) {
                        bootbox.confirm(interval_booking.timeLine, function(result) {
                            if(result) {
                                $('#BookingIgnoreBooked').val(true);
                                BookingAddForm.submit();
                            }
                        });
                    } else {
                        BookingAddForm.submit();
                        return true;
                    }
                }
                return false;
            });
        } else {
            BookingAddForm.submit();
            return true;
        }
        return false;
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

    $('.form_date')
    .datetimepicker({
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
        linkFormat: "yyyy-mm-dd"})
    .on('changeDate', function(ev){
        updateURL();
    });

    BookingStartHour.change(function() {
        d = this.value.indexOf(':');
        h1 = this.value.substr(0, d);
        m1 = this.value.substr(d + 1);

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
        updateURL();
    });

    BookingEndHour.change(function() {
        d = this.value.indexOf(':');
        h2 = this.value.substr(0, d);
        m2 = this.value.substr(d + 1);

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
        updateURL();
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

        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
            BookingViewTabs.val($(e.target).attr('href').charAt(1));
            updateURL();
        });

        typeahead.initialize();

        $('.typeahead').typeahead(null, {
                displayKey: 'name',
                source: typeahead.ttAdapter()
            }
        );

        var BookingIntervalGroup = $('#BookingIntervalGroup');
        if(BookingIntervalIteration.val() != 0) {
        	BookingIntervalGroup.slideDown("fast");
        }
        BookingIntervalIteration.change(function () {
            this.value == 0 ? BookingIntervalGroup.hide() : BookingIntervalGroup.slideDown("fast");
        });

        $('input[name="data[Booking][interval_type]"]').click(function () {
            updateDisabled(this.value);
        });
        updateDisabled($('input[name="data[Booking][interval_type]"]:radio:checked').val());

        BookingRoomId.change(function () {
            readDetails(this.value);
            updateURL();
        });

        readDetails(BookingRoomId.val());

        $('#BookingName').focus();
    });

</script>