<?php setlocale(LC_ALL, 'de_DE@euro', 'de_DE', 'deu_deu'); ?>
Sehr geehrte(r) <?php echo $admin['User']['username']; ?>,

Es wurde folgende Buchung aufgegeben:

Name: <?php echo $data['Booking']['name']; ?>

Raum: <?php echo $room['Room']['name']; ?>

Benutzer: <?php echo $this->Session->read('Auth.User.username'); ?>

Startzeit: <?php echo $data['Booking']['start']; ?> 

Dauer: <?php echo $data['Booking']['duration']; ?> Minuten

Endzeit: <?php echo $data['Booking']['end']; ?>


Ansicht: <?php echo $this->Html->url(array('controller' => 'bookings', 'action' => 'view', $id)); ?>

Bearbeiten: <?php echo $this->Html->url(array('controller' => 'bookings', 'action' => 'view', $id)); ?>


<?php if (count($interval_booking) > 1) : $count = 0; ?>

Intervall Buchungen (<?php echo count($interval_booking); ?>):
<?php foreach ($interval_booking as $key => $value): $count++; ?>

Buchung <?php echo $count ?>:

Startzeit: <?php echo utf8_encode(strftime('%d %B %Y - %H:%M', $value['start_date']->getTimestamp())); ?>

Endzeit: <?php echo utf8_encode(strftime('%d %B %Y - %H:%M', $value['end_date']->getTimestamp())); ?>

Status: <?php echo $value['status']; ?>

<?php endforeach; ?>
<?php unset($group); ?>
<?php endif; ?>