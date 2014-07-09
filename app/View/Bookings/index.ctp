<h1><?php echo __('Verwaltung der Buchungen'); ?></h1>

<?php if ((isset($this->params['pass'][0])) && ($this->params['pass'][0] == 'table')) : ?>
    <div class="page-header">
        <div class="pull-right form-inline">
            <div class="btn-group">
                <?php echo $this->Html->link('<span class="glyphicon glyphicon glyphicon-transfer"></span> ' . __('Kalenderansicht'), array('action' => 'index'), array('class' => 'btn btn-default', 'escape' => false)); ?>
                <?php echo $this->Html->link('<span class="glyphicon glyphicon-calendar"></span> ' . __('Abonnieren'), array('controller' => 'ical', 'action' => 'index'), array('class' => 'btn btn-default', 'escape' => false)); ?>
            </div>
        </div>

        <h3><?php echo __('Alle Buchungen'); ?></h3>
    </div>
    <table class="table table-hover">
        <thead>
        <tr>
            <th><?php echo $this->Form->checkbox('all', array('name' => 'CheckAll', 'id' => 'CheckAll')); ?></th>
            <th><?php echo $this->Paginator->sort('status', 'Status'); ?>  </th>
            <th><?php echo $this->Paginator->sort('name', 'Bezeichnung'); ?>  </th>
            <th><?php echo $this->Paginator->sort('Room.name', 'Raum'); ?>  </th>
            <th><?php echo $this->Paginator->sort('startdatetime', 'Startzeit'); ?></th>
            <th><?php echo $this->Paginator->sort('enddatetime', 'Endzeit'); ?></th>
            <th><?php echo $this->Paginator->sort('created', 'Erstellt'); ?></th>
            <th><?php echo $this->Paginator->sort('modified', 'Letzte Änderung'); ?></th>
            <th><?php echo __('Aktionen'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php $count = 0; ?>
        <?php foreach ($data as $booking): ?>
            <?php $count++; ?>
            <tr>
                <td><?php echo $this->Form->checkbox('Booking.id.' . $booking['Booking']['id']); ?></td>
                <td><span class="label label-<?php echo $this->status->toBootstrap($booking['Booking']['status']); ?>"><?php echo $booking['Booking']['status']; ?></span>
                </td>
                <td><?php

                    if (
                        ($this->Session->read('Auth.User.role') == 'root') ||
                        (($this->Session->read('Auth.User.role') == 'admin') && ($booking['User']['organizationalunit_id'] == $booking['Room']['organizationalunit_id'])) ||
                        ((in_array($this->Session->read('Auth.User.role'), array('user', 'admin'))) && ($this->Session->read('Auth.User.id') == $booking['Booking']['user_id']))
                    ) {
                        echo $this->Html->link($booking['Booking']['name'] ? : '<em>(keine Bezeichnung)</em>', array('action' => 'edit', $booking['Booking']['id']), array('escape' => false));
                    } else {
                        echo $this->Html->link($booking['Booking']['name'] ? : '<em>(keine Bezeichnung)</em>', array('action' => 'view', $booking['Booking']['id']), array('escape' => false));
                    }

                    ?></td>
                <td class="text-center"><?php echo $booking['Room']['name']; ?></td>
                <td class="text-center"><?php echo $this->Time->niceShort($booking['Booking']['startdatetime']); ?></td>
                <td class="text-center"><?php echo $this->Time->niceShort($booking['Booking']['enddatetime']); ?></td>
                <td class="text-center"><?php echo $this->Time->niceShort($booking['Booking']['created']); ?></td>
                <td class="text-center"><?php echo $this->Time->niceShort($booking['Booking']['modified']); ?></td>
                <td><?php

                    echo $this->Html->link("Ansicht", array('action' => 'view', $booking['Booking']['id']));

                    if ($this->Session->check('Auth.User.username')) {
                        if (
                            ($this->Session->read('Auth.User.role') == 'root') ||
                            (($this->Session->read('Auth.User.role') == 'admin') && ($booking['User']['organizationalunit_id'] == $booking['Room']['organizationalunit_id'])) ||
                            ((in_array($this->Session->read('Auth.User.role'), array('user', 'admin'))) && ($this->Session->read('Auth.User.id') == $booking['Booking']['user_id']))
                        ) {
                            echo ' | ';

                            echo $this->Html->link("Bearbeiten", array('action' => 'edit', $booking['Booking']['id']));
                        }

                        if (
                            ($this->Session->read('Auth.User.role') == 'root') ||
                            (($this->Session->read('Auth.User.role') == 'admin') && ($this->Session->read('Auth.User.organizationalunit_id') == $booking['Room']['organizationalunit_id']))
                        ) {
                            if (in_array($booking['Booking']['status'], array(Booking::planned, Booking::planning_concurred)) && ($booking['Booking']['status'] != Booking::planning_rejected)) {
                                echo ' | ';

                                echo $this->Html->link("Absagen", array('action' => 'reject', $booking['Booking']['id']));
                            } else if(in_array($booking['Booking']['status'], array(Booking::active)) && ($booking['Booking']['status'] != Booking::active_denied)) {
                                echo ' | ';

                                echo $this->Html->link("Verweigern", array('action' => 'deny', $booking['Booking']['id']));
                            }

                            if (in_array($booking['Booking']['status'], array(Booking::active_denied, Booking::planned, Booking::planning_rejected))) {
                                echo ' | ';

                                echo $this->Html->link("Zusagen", array('action' => 'accept', $booking['Booking']['id']));
                            }
                        }

                        if (
                            ($this->Session->read('Auth.User.role') == 'root') ||
                            ((in_array($this->Session->read('Auth.User.role'), array('user', 'admin'))) && ($this->Session->read('Auth.User.id') == $booking['Booking']['user_id']))
                        ) {
                            echo ' | ';
                            echo $this->Html->link("Löschen", array('action' => 'delete', 'id', $booking['Booking']['id']));
                        }
                    }
                    ?></td>
            </tr>
        <?php endforeach; ?>
        <?php unset($booking); ?>
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

    <?php
    echo $this->Html->link(__('Hinzufügen'), array('action' => 'add'), array('class' => 'btn btn-default', 'style' => 'margin-left: 5px'));
    ?>
<?php else : ?>
    <div class="page-header">
        <div class="pull-right form-inline">
            <div class="btn-group">
                <button class="btn btn-primary" data-calendar-nav="prev">《</button>
                <button class="btn btn-default" data-calendar-nav="today"><?php echo __('Heute'); ?></button>
                <button class="btn btn-primary" data-calendar-nav="next">》</button>
            </div>
            <div class="btn-group">
                <button class="btn btn-info" data-calendar-view="year"><?php echo __('Jahr'); ?></button>
                <button class="btn btn-info active" data-calendar-view="month"><?php echo __('Monat'); ?></button>
                <button class="btn btn-info" data-calendar-view="week"><?php echo __('Woche'); ?></button>
                <button class="btn btn-info" data-calendar-view="day"><?php echo __('Tag'); ?></button>
            </div>
            <div class="btn-group">
                <?php echo $this->Html->link('<span class="glyphicon glyphicon glyphicon-transfer"></span> ' . __('Tabellenansicht'), array('action' => 'index', 'table'), array('class' => 'btn btn-default', 'escape' => false)); ?>
                <?php echo $this->Html->link('<span class="glyphicon glyphicon-calendar"></span> ' . __('Abonnieren'), array('controller' => 'ical', 'action' => 'index'), array('class' => 'btn btn-default', 'escape' => false)); ?>
            </div>
        </div>

        <h3>&nbsp;</h3>
    </div>
    <div class="row">
        <div class="col-md-9">
            <div id="calendar"></div>

            <div class="push"></div>
            <?php echo $this->Html->link(__('Hinzufügen'), array('action' => 'add'), array('class' => 'btn btn-default btn-lg')); ?>
        </div>
        <div class="col-md-3">
            <h4><?php echo __('Buchungen'); ?></h4>
            <ul id="eventlist" class="nav nav-list"></ul>
        </div>
    </div>
    <div class="clearfix"></div>
    <script type="text/javascript">
        (function($) {

            "use strict";

            var options = {
                language: 'de-DE',
                events_source: rr_base_url + 'ajax/calendar_events/' + '<?php echo implode('/', $this->params['pass']) ?>' ,
                view: 'month',
                tmpl_path: rr_base_url + 'tmpls/',
                tmpl_cache: true,
                onAfterEventsLoad: function(events) {
                    if(!events) {
                        return;
                    }
                    var list = $('#eventlist');
                    list.html('');

                    $.each(events, function(key, val) {
                        if(!(val.class == 'event-warning')) {
                            $(document.createElement('li'))
                                .html('<a href="' + val.url + '">' + val.title + '</a>')
                                .appendTo(list);
                        }
                    });
                },
                onAfterViewLoad: function(view) {
                    $('.page-header h3').text(this.getTitle());
                    $('.btn-group button').removeClass('active');
                    $('button[data-calendar-view="' + view + '"]').addClass('active');
                },
                classes: {
                    months: {
                        general: 'label'
                    }
                }
            };

            var calendar = $('#calendar').calendar(options);

            $('.btn-group button[data-calendar-nav]').each(function() {
                var $this = $(this);
                $this.click(function() {
                    calendar.navigate($this.data('calendar-nav'));
                });
            });

            $('.btn-group button[data-calendar-view]').each(function() {
                var $this = $(this);
                $this.click(function() {
                    calendar.view($this.data('calendar-view'));
                });
            });

        }(jQuery));
    </script>
<?php endif; ?>