<?php setlocale(LC_ALL, 'de_DE@euro', 'de_DE', 'deu_deu'); ?>
Sehr geehrte(r) <?php echo $data['User']['username']; ?>,

Es wurde folgende Buchung freigegeben:

Name: <?php echo $data['Booking']['name']; ?>

Raum: <?php echo $data['Room']['name']; ?>

Startzeit: <?php echo MyTime::toReadableDateTime(strtotime($data['Booking']['startdatetime']), true); ?>

Endzeit: <?php echo MyTime::toReadableDateTime(strtotime($data['Booking']['enddatetime']), true); ?>


Ansicht: <?php echo $this->Html->url(array('controller' => 'bookings', 'action' => 'view', $data['Booking']['id']), true); ?>

Bearbeiten: <?php echo $this->Html->url(array('controller' => 'bookings', 'action' => 'edit', $data['Booking']['id']), true); ?>