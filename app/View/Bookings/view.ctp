<?php setlocale(LC_ALL, 'de_DE@euro', 'de_DE', 'deu_deu'); ?>

<div class="container">
<?php
echo $this->Form->create('Booking', array());

$start = new DateTime($this->request->data['Booking']['startdatetime']);
$end = new DateTime($this->request->data['Booking']['enddatetime']);
?>
    <fieldset>
        <div class="page-header">
            <h1>
                <?php echo $this->request->data['Booking']['name']; ?>
                <?php if(
                    ($this->Session->read('Auth.User.role') == 'root') ||
                    (($this->Session->read('Auth.User.role') == 'admin') && ($this->request->data['User']['organizationalunit_id'] == $this->request->data['Room']['organizationalunit_id'])) ||
                    ((in_array($this->Session->read('Auth.User.role'), array('user', 'admin'))) && ($this->Session->read('Auth.User.id') == $this->request->data['Booking']['user_id']))) : ?>
                    <?php echo $this->Html->link('<span class="glyphicon glyphicon-edit"></span> ' . __('Bearbeiten'), array('action'=> 'edit', $this->params['pass'][0]), array('class' => 'btn btn-default ', 'escape' => false)); ?>
                <?php endif; ?>
            </h1>
            <h4>
                <?php
                $val = strftime('%d %B %Y', $start->getTimestamp());
                if(WIN)
                    $val = utf8_encode($val);

                echo $val . ' ' . $start->format('H:i') . '-' . $end->format('H:i'); ?>
            </h4>
            <h3>
                <span class="label label-<?php echo $this->status->toBootstrap($this->request->data['Booking']['status']); ?>" style=""><?php echo $this->request->data['Booking']['status']; ?></span>
                <span class="label label-default">
                    <a class="btn-link" data-toggle="modal" data-target="#ModalRoom" style="font-weight:bold; color:white;">
                        <?php echo $rooms[$this->request->data['Booking']['room_id']]; ?>
                    </a>
                </span>
                <span class="pull-right">
                    <span class="label label-default">
                        <a class="btn-link" data-toggle="modal" data-target="#ModalUser" style="font-weight:bold; color:white;">
                            <?php echo $this->request->data['User']['username']; ?>
                        </a>
                    </span>
                </span>
            </h3>
        </div>

        <?php if(isset($groups) && (count($groups) > 1)) : ?>
        <div class="row">
            <div class="col-md-9">
                <div id="calendar"></div>
            </div>
            <div class="col-md-3">
                <h4><?php echo __('Verwandte Buchungen'); ?></h4>
                <ul class="nav nav-list">
                <?php $count=0; ?>
                <?php foreach($groups as $group): ?>
                    <?php $count ++; ?>
                    <li class="<?php echo (($this->request->data['Booking']['id'] == $group['Booking']['id']) ? 'active' : '')?>"><?php echo $this->Html->link($this->Time->format('d.m', $group['Booking']['startdatetime']) . ' ' . $group['Booking']['name'] . ' (' . $count . ')', array('action' => 'view', $group['Booking']['id'])); ?></li>
                <?php endforeach; ?>
                <?php unset($group); ?>
                </ul>
            </div>
        </div>
        <?php else : ?>
        <div id="calendar"></div>
        <?php endif; ?>
        <div class="push"></div>
    </fieldset>
</div>

<div class="modal fade" id="ModalRoom" tabindex="-1" role="dialog" aria-labelledby="ModalRoomLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="ModalRoomLabel"><?php echo __('Rauminformationen'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="well">
                    <table class="table" style="table-layout: fixed; word-wrap: break-word;">
                        <thead>
                        <tr>
                            <th>Eigenschaft</th>
                            <th>Wert</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Sitze</td>
                            <td><?php echo $this->request->data['Room']['seats']; ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Schließen'); ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalUser" tabindex="-1" role="dialog" aria-labelledby="ModalUserLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="ModalUserLabel"><?php echo __('Benutzerinformationen'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="well">
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
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Schließen'); ?></button>
            </div>
        </div>
    </div>
</div>

    <script type="text/javascript">
        $(document).ready(function () {
            "use strict";
            var options = {
                language: 'de-DE',
                events_source: rr_base_url + 'ajax/calendar_events/room/' + <?php echo $this->request->data['Booking']['room_id']; ?>,
                view: 'day',
                day: '<?php echo (new DateTime($this->request->data['Booking']['startdatetime']))->format('Y-m-d'); ?>',
                tmpl_path: rr_base_url + 'tmpls/',
                tmpl_cache: true
            };

            var calendar = $('#calendar').calendar(options);
        });
    </script>