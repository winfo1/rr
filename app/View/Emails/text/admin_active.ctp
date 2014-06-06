<?php setlocale(LC_ALL, 'de_DE@euro', 'de_DE', 'deu_deu'); ?>
Sehr geehrte(r) <?php echo $admin['User']['username']; ?>,

Es wurde folgende Buchung aufgegeben:

Name: <?php echo $data['Booking']['name']; ?>

Raum: <?php echo $room['Room']['name']; ?>

Benutzer: <?php echo $this->Session->read('Auth.User.username'); ?>

Startzeit: <?php echo $data['Booking']['start']; ?> 

Dauer: <?php echo $data['Booking']['duration']; ?> Minuten

Endzeit: <?php echo $data['Booking']['end']; ?>


Ansicht: <?php echo $this->Html->url(array('controller' => 'bookings', 'action' => 'view', $data['Booking']['id']), true); ?>

Bearbeiten: <?php echo $this->Html->url(array('controller' => 'bookings', 'action' => 'edit', $data['Booking']['id']), true); ?>


<?php if (count($interval_booking) > 1) : $count = 0; ?>

Intervall Buchungen (<?php echo count($interval_booking); ?>):
<?php foreach ($interval_booking as $key => $value): $count++; ?>

Buchung <?php echo $count ?>:

Status: <?php echo $value['status']; ?>

Startzeit: <?php echo MyTime::toReadableDateTime($value['start_date']->getTimestamp(), true); ?>

Endzeit: <?php echo MyTime::toReadableDateTime($value['end_date']->getTimestamp(), true); ?>


Ansicht: <?php echo $this->Html->url(array('controller' => 'bookings', 'action' => 'view', $value['id']), true); ?>

Bearbeiten: <?php echo $this->Html->url(array('controller' => 'bookings', 'action' => 'edit', $value['id']), true); ?>

<?php endforeach; ?>
<?php unset($group); ?>
<?php endif; ?>