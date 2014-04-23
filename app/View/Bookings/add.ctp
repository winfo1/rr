<?php setlocale(LC_ALL, 'de_DE@euro', 'de_DE', 'deu_deu'); ?>
<div class="container">
    <div class="bookings form">
        <?php echo $this->Form->create('Booking'); ?>
        <h1><?php echo __('Raum buchen'); ?></h1>
        <fieldset>
            <legend><?php echo __('Geben Sie hier die Daten der neuen Buchung an'); ?></legend>

            <div class="twitter-typeahead" style="position: relative; display: inline-block; direction: ltr;">
                <div class="form-group">
                    <label for="BookingName"><?php echo __('Bezeichnung'); ?></label>
                    <?php
                    echo $this->Form->input('name', array(
                        'type'  => 'text',
                        'class' => 'form-control typeahead input-block-level',
                        'label' => false,
                        'placeholder' => 'Bezeichnung'));
                    ?>
                </div>
            </div>
            <div class="form-group" id="Room">
                <label for="BookingRoomId" class="control-label"><?php echo __('Raum'); ?></label>

                <?php
                echo $this->Form->input('room_id', array(
                    'class' => 'form-control',
                    'label' => false,
                    'selected' => $room_id,
                    'options' => $rooms));
                ?>
            </div>

            <div class="form-group">
                <label for="BookingStart" class="control-label"><?php echo __('Startzeit'); ?></label>

                <div class="input-group date form_datetime" data-date-format="dd MM yyyy - HH:ii" data-link-field="data[Booking][start]">
                    <?php
                    $val = strftime('%d %B %Y - %H:%M');
                    if(WIN)
                        $val = utf8_encode($val);
                    echo $this->Form->input('start', array(
                        'type' => 'text',
                        'class' => 'form-control',
                        'label' => false,
                        'readonly' => true,
                        'value' => $val));
                    ?>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                </div>
                <input type="hidden" name="data[Booking][startdatetime]" id="BookingStartDateTime" value="<?php echo date('Y-m-d H:i:s'); ?>" />
            </div>

            <div class="form-group">
                <label for="BookingDuration" class=" control-label"><?php echo __('Dauer'); ?></label>

                <div class="input-group">
                <span class="input-group-addon">

                    <label style="text-align: left; width: 80px">
                        <input type="hidden" name="data[Booking][full_time]" id="BookingFullTime_" value="0"/>
                        <input type="checkbox" name="data[Booking][full_time]" id="BookingFullTime" value="1" onclick="$(BookingDuration).toggleDisabled();"> ganztägig
                    </label>

                </span>
                    <?php
                    $options = array('15' => '15 Minuten', '30' => '30 Minuten', '60' => '1 Stunde', '120' => '2 Stunden', '180' => '3 Stunden');

                    echo $this->Form->input('duration', array(
                        'class' => 'form-control',
                        'label' => false,
                        'options' => $options));
                    ?>
                </div>
            </div>


            <div class="form-group">
                <label for="BookingIntervalIteration" class="control-label"><?php echo __('Wiederholung'); ?></label>
                <?php
                $options = array('0' => 'ohne', '1' => 'täglich', '7' => 'wöchenlich', '14' => 'alle 2 Wochen', '28' => 'jeden Monat', '56' => 'jeden 2ten Monat');

                echo $this->Form->input('interval_iteration', array(
                    'class' => 'form-control',
                    'label' => false,
                    'selected' => '0',
                    'options' => $options));
                ?>
            </div>

            <div class="form-group" id="BookingIntervalGroup" style="display: none">
                <label class=" control-label"><?php echo __('Ende'); ?></label>

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
                            'class' => 'form-control',
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
                                'class' => 'form-control',
                                'label' => false,
                                'disabled' => true,
                                'readonly' => true,
                                'value' => $val));
                            ?>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                        </div>
                    </div>
                    <input type="hidden" name="data[Booking][interval_end]" id="BookingIntervalEnd" value="<?php echo date('Y-m-d', strtotime("+2 weeks")); ?>"/>
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
                            'class' => 'form-control',
                            'label' => false,
                            'disabled' => true,
                            'options' => $options));
                        ?>
                    </div>
                </div>

            </div>
        </fieldset>
        <?php echo $this->Form->end(array('label' => __('Buchen'), 'class' => 'btn btn-default btn-lg')); ?>
    </div>
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

    function addImageToGallery(l, i)
    {
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

    $(".form_datetime").datetimepicker({
        language: 'de',
        weekStart: 1,
        todayBtn: 1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        pickerPosition: 'bottom-left',
        linkField: "BookingStartDateTime",
        linkFormat: "yyyy-mm-dd hh:ii:ss"
    });

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