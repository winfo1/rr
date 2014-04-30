<?php setlocale(LC_ALL, 'de_DE@euro', 'de_DE', 'deu_deu'); ?>

<?php if(isset($groups) && (count($groups) > 1)) : ?>
<ul class="nav nav-pills nav-stacked col-md-2">
    <?php $count=0; ?>
    <?php foreach($groups as $group): ?>
        <?php $count ++; ?>
        <li class="<?php echo (($this->request->data['Booking']['id'] == $group['Booking']['id']) ? 'active' : '')?>"><?php echo $this->Html->link($this->Time->format('d.m', $group['Booking']['startdatetime']) . ' ' . $group['Booking']['name'] . ' (' . $count . ')', array('action' => 'view', $group['Booking']['id'])); ?></li>
    <?php endforeach; ?>
    <?php unset($group); ?>
</ul>
<?php endif; ?>

<div class="container">
    <div class="bookings form">
        <?php echo $this->Form->create('Booking'); ?>
        <fieldset>
            <div class="page-header">
                <?php if(
                    ($this->Session->read('Auth.User.role') == 'root') ||
                    (($this->Session->read('Auth.User.role') == 'admin') && ($this->request->data['User']['organizationalunit_id'] == $this->request->data['Room']['organizationalunit_id'])) ||
                    ((in_array($this->Session->read('Auth.User.role'), array('user', 'admin'))) && ($this->Session->read('Auth.User.id') == $this->request->data['Booking']['user_id']))) : ?>
                <div class="pull-right form-inline">
                    <div class="btn-group">
                        <?php echo $this->Html->link(__('<span class="glyphicon glyphicon glyphicon-transfer"></span> Bearbeiten'), array('action'=> 'edit', $this->params['pass'][0]), array('class' => 'btn btn-default', 'escape' => false)); ?>
                    </div>
                </div>
                <?php endif; ?>

                <h3><?php echo __('Buchung ansehen'); ?></h3>
            </div>

            <div class="twitter-typeahead" style="position: relative; display: inline-block; direction: ltr;">
                <div class="form-group">
                    <label for="BookingName"><?php echo __('Bezeichnung'); ?></label>
                    <?php
                    echo $this->Form->input('name', array(
                        'type'  => 'text',
                        'class' => 'form-control typeahead input-block-level',
                        'label' => false,
                        'disabled' => true));
                    ?>
                </div>
            </div>
            <div class="form-group" id="Room">
                <label for="BookingRoomId" class="control-label"><?php echo __('Raum'); ?></label>

                <?php
                echo $this->Form->input('room_id', array(
                    'class' => 'form-control',
                    'label' => false,
                    'disabled' => true,
                    'options' => $rooms));
                ?>
            </div>

            <div class="form-group">
                <label for="BookingStart" class="control-label"><?php echo __('Startzeit'); ?></label>

                <div class="input-group date form_datetime" data-date-format="dd MM yyyy - HH:ii" data-link-field="data[Booking][start]">
                    <?php
                    $val = strftime('%d %B %Y - %H:%M', $this->Time->gmt($this->request->data['Booking']['startdatetime']));
                    if(WIN)
                        $val = utf8_encode($val);
                    echo $this->Form->input('start', array(
                        'type' => 'text',
                        'class' => 'form-control',
                        'label' => false,
                        'readonly' => true,
                        'disabled' => true,
                        'value' => $val));
                    ?>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                </div>
                <input type="hidden" name="data[Booking][startdatetime]" id="BookingStartDateTime" value="<?php echo $this->request->data['Booking']['startdatetime']; ?>" />
            </div>

            <div class="form-group">
                <label for="BookingEnd" class="control-label"><?php echo __('Endzeit'); ?></label>

                <div class="input-group date form_end_date col-md-12" data-date-format="dd MM yyyy - HH:ii" data-link-field="data[Booking][BookingEnd]">
                    <?php
                    $val = strftime('%d %B %Y - %H:%M', $this->Time->gmt($this->request->data['Booking']['enddatetime']));
                    if(WIN)
                        $val = utf8_encode($val);
                    echo $this->Form->input('end', array(
                        'type'  => 'text',
                        'class' => 'form-control',
                        'label' => false,
                        'readonly' => true,
                        'disabled' => true,
                        'value' => $val));
                    ?>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                </div>
                <input type="hidden" name="data[Booking][enddatetime]" id="BookingEndDateTime" value="<?php echo $this->request->data['Booking']['enddatetime']; ?>" />
            </div>
            
            <div class="form-group">
                <label for="UserName"><?php echo __('Gebucht von'); ?></label>
                <?php
                echo $this->Form->input('data[User][username]', array(
                        'class' => 'form-control',
                        'label' => false,
                        'disabled' => true,
                        'value' => $this->request->data['User']['username']));
                ?>
                <div style="margin-top: 15px;" class="well col-md-6 col-md-offset-3">
                	<table class="table">
                		<thead>
                			<tr>
                				<th>Eigenschaft</th>
                				<th>Wert</th>
                			</tr>
                		</thead>
                		<tbody>
                			<tr>
                				<td><?php echo $this->Html->link('E-Mail', 'mailto:' . $this->request->data['User']['emailaddress']); ?></td>
                				<td><?php echo $this->request->data['User']['emailaddress']; ?></td>
                			</tr>
                			<tr>
                				<td><?php echo $this->Html->link('Telefonnummer', 'tel:' . $this->request->data['User']['phonenumber']); ?></td>
                				<td><?php echo $this->request->data['User']['phonenumber']; ?></td>
                			</tr>
                		</tbody>
                	</table>
                </div>
            </div>
        </fieldset>
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

    $(".form_datetime").datetimepicker({
        language: 'de',
        weekStart: 1,
        todayBtn: 0,
        autoclose: 1,
        todayHighlight: 1,
        startView: 0,
        forceParse: 0,
        pickerPosition: 'bottom-left',
        startDate: "<?php echo $this->request->data['Booking']['startdatetime']; ?>",
        endDate: "<?php echo $this->request->data['Booking']['enddatetime']; ?>",
        linkField: "BookingStartDateTime",
        linkFormat: "yyyy-mm-dd hh:ii:ss"
    });

    $(".form_end_date").datetimepicker({
        language: 'de',
        weekStart: 1,
        todayBtn: 0,
        autoclose: 1,
        todayHighlight: 1,
        startView: 0,
        forceParse: 0,
        pickerPosition: 'bottom-left',
        startDate: "<?php echo $this->request->data['Booking']['startdatetime']; ?>",
        endDate: "<?php echo $this->request->data['Booking']['enddatetime']; ?>",
        linkField: "BookingEndDateTime",
        linkFormat: "yyyy-mm-dd hh:ii:ss"
    });

    $(document).ready(function () {

        readDetails($('#BookingRoomId').val());
    });

</script>