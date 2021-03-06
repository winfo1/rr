<?php setlocale(LC_ALL, 'de_DE@euro', 'de_DE', 'deu_deu'); ?>
<ol class="breadcrumb well well-sm">
    <li><?php echo __('Verwaltung'); ?></li>
    <li><?php echo $this->Html->link('Buchungen', array('action' => 'index')); ?></li>
    <li class="active"><?php echo __('Buchung bearbeiten'); ?></li>
</ol>
<?php echo $this->Form->create('Booking', array(
    'class' => 'well',
    'inputDefaults' => array(
        'div' => 'form-group',
        'wrapInput' => false,
        'class' => 'form-control',
    )
)); ?>
<h1><?php echo __('Buchung ändern'); ?></h1>
<fieldset>
    <legend><?php echo __('Hier können Sie die Daten des Buchung bearbeiten'); ?></legend>

    <?php if(isset($groups) && (count($groups) > 1)) : ?>
    <div class="row">
        <div class="col-md-9">
    <?php endif; ?>

    <?php echo $this->Form->input('name', array(
        'class' => 'form-control typeahead input-block-level',
        'label' => __('Bezeichnung'),
        'placeholder' => 'Bezeichnung'));
    ?>

    <?php echo $this->Form->input('room_id', array(
        'div' => array('div' => 'form-group', 'id' => 'Room'),
        'label' => __('Raum'),
        'options' => $rooms));
    ?>

    <div class="form-group">
        <label for="BookingStart" class="control-label"><?php echo __('Startzeit'); ?></label>

        <div class="input-group date form_datetime" data-date-format="d MM yyyy - HH:ii" data-link-field="data[Booking][start]">
            <?php
            echo $this->Form->input('start', array(
                'type' => 'text',
                'div' => false,
                'class' => 'form-control',
                'label' => false,
                'readonly' => true,
                'value' => $this->mytime->toReadableDateTime((new DateTime($this->request->data['Booking']['startdatetime']))->getTimestamp(), true)));
            ?>
            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
        </div>
        <?php echo $this->Form->hidden('startdatetime'); ?>
    </div>

    <div class="form-group">
        <label for="BookingEnd" class="control-label"><?php echo __('Endzeit'); ?></label>

        <div class="input-group date form_end_date col-md-12" data-date-format="d MM yyyy - HH:ii" data-link-field="data[Booking][BookingEnd]">
            <?php
            echo $this->Form->input('end', array(
                'type'  => 'text',
                'div' => false,
                'class' => 'form-control',
                'label' => false,
                'readonly' => true,
                'value' => $this->mytime->toReadableDateTime((new DateTime($this->request->data['Booking']['enddatetime']))->getTimestamp(), true)));
            ?>
            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
        </div>
        <?php echo $this->Form->hidden('enddatetime'); ?>
    </div>
    <?php if(isset($groups) && (count($groups) > 1)) : ?>
        </div>
        <?php echo $this->Form->hidden('group_id'); ?>
        <div class="col-md-3">
            <h4><?php echo __('Verwandte Buchungen'); ?></h4>
            <ul class="nav nav-pills nav-stacked">
                <?php $count=0; ?>
                <?php foreach($groups as $group): ?>
                    <?php $count ++; ?>
                    <li class="<?php echo (($this->request->data['Booking']['id'] == $group['Booking']['id']) ? 'active' : '')?>"><?php echo $this->Html->link($this->Time->format('d.m', $group['Booking']['startdatetime']) . ' ' . $group['Booking']['name'] . ' (' . $count . ')', array('action' => 'edit', $group['Booking']['id'])); ?></li>
                <?php endforeach; ?>
                <?php unset($group); ?>
            </ul>
            <div style="padding: 10px 15px;">
                <?php echo $this->Html->link(__('Hinzufügen'), array('controller' => 'bookings', 'action' => 'add', $this->request->data['Booking']['group_id'], $this->request->data['Booking']['room_id']), array('class' => 'btn btn-primary')); ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</fieldset>
<div class="submit">
    <div class="btn-group">
        <?php

        echo $this->Form->button('<i class="glyphicon glyphicon-pencil"></i> ' .__('Ändern'), array('type' => 'submit', 'name' => 'submit', 'class' => 'btn btn-primary btn-lg', 'escape' => false, 'div' => false));

        if(isset($groups) && (count($groups) > 1))
        {
            echo $this->Form->button('<i class="glyphicon glyphicon-pencil"></i> ' .__('Alle ändern'), array('type' => 'submit', 'name' => 'submit_all', 'class' => 'btn btn-default btn-lg', 'escape' => false, 'div' => false));
        }

        ?>
    </div>

    <div class="btn-group">
        <?php
        if(
            ($this->Session->read('Auth.User.role') == 'root') ||
            (($this->Session->read('Auth.User.role') == 'admin') && ($this->Session->read('Auth.User.organizationalunit_id') == $this->request->data['Room']['organizationalunit_id'])))
        {
            if (in_array($this->request->data['Booking']['status'], array(Booking::planned, Booking::planning_concurred)) && ($this->request->data['Booking']['status'] != Booking::planning_rejected)) {
                echo $this->Html->link('<i class="glyphicon glyphicon-remove-sign"></i> ' . __('Absagen'), array('action' => 'reject', $this->params['pass'][0]), array('class' => 'btn btn-danger btn-sm', 'escape' => false, 'div' => false));
            } else if(in_array($this->request->data['Booking']['status'], array(Booking::active)) && ($this->request->data['Booking']['status'] != Booking::active_denied)) {
                echo $this->Html->link('<i class="glyphicon glyphicon-remove-sign"></i> ' . __('Verweigern'), array('action' => 'deny', $this->params['pass'][0]), array('class' => 'btn btn-danger btn-sm', 'escape' => false, 'div' => false));
            }

            if(in_array($this->request->data['Booking']['status'], array(Booking::active_denied, Booking::planned, Booking::planning_rejected)))
            {
                echo $this->Html->link('<i class="glyphicon glyphicon-ok-sign"></i> ' . __('Zusagen'), array('action' => 'accept', $this->params['pass'][0]), array('class' => 'btn btn-success btn-sm', 'escape' => false, 'div' => false));
            }
        }

        if(
            ($this->Session->read('Auth.User.role') == 'root') ||
            ((in_array($this->Session->read('Auth.User.role'), array('user', 'admin'))) && ($this->Session->read('Auth.User.id') == $this->request->data['Booking']['user_id'])))
        {
            echo $this->Html->link('<i class="glyphicon glyphicon-remove"></i> ' . __('Löschen'), array('action' => 'delete', 'id', $this->params['pass'][0]), array('class' => 'btn btn-danger btn-sm', 'escape' => false, 'div' => false));


            if(isset($groups) && (count($groups) > 1))
            {
                echo $this->Html->link('<i class="glyphicon glyphicon-remove"></i> ' . __('Alle löschen'), array('action' => 'delete', 'group', $this->request->data['Booking']['group_id']), array('class' => 'btn btn-danger btn-sm', 'escape' => false, 'div' => false));
            }
        }
        ?>
    </div>
</div>
<?php echo $this->Form->end(); ?>

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
        startView: 0,
        forceParse: 0,
        pickerPosition: 'bottom-left',
        linkField: "BookingStartdatetime",
        linkFormat: "yyyy-mm-dd hh:ii:ss"
    });

    $(".form_end_date").datetimepicker({
        language: 'de',
        weekStart: 1,
        todayBtn: 1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 0,
        forceParse: 0,
        pickerPosition: 'bottom-left',
        linkField: "BookingEnddatetime",
        linkFormat: "yyyy-mm-dd hh:ii:ss"
    });

    $(document).ready(function () {

        var BookingRoomId = $('#BookingRoomId');

        typeahead.initialize();

        $('.typeahead').typeahead(null, {
                displayKey: 'name',
                source: typeahead.ttAdapter()
            }
        );

        BookingRoomId.change(function () {
            readDetails(this.value);
        });

        readDetails(BookingRoomId.val());

        $('#BookingName').focus();
    });

</script>