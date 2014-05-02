<div class="container">
<?php echo $this->Form->create('Booking', array()); ?>
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
            <h3>
                <span class="label label-<?php echo $this->status->toBootstrap($this->request->data['Booking']['status']); ?>" style=""><?php echo $this->request->data['Booking']['status']; ?></span>
                <span class="label label-default btn-lg"><?php echo $rooms[$this->request->data['Booking']['room_id']]; ?></span>
                <span class="pull-right">
                    <span class="label label-default"><?php echo $this->request->data['User']['username']; ?></span>
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