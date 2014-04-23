<?php setlocale(LC_ALL, 'de_DE@euro', 'de_DE', 'deu_deu'); ?>
Sehr geehrte(r) <?php echo $admin['User']['username']; ?>,

Es wurde folgende Planungsbuchung aufgegeben:

Name: <?php echo $data['Booking']['name']; ?>

Raum: <?php echo $room['Room']['name']; ?>

Benutzer: <?php echo $this->Session->read('Auth.User.username'); ?>


<?php if (count($interval_booking) > 1) : $count = 0; ?>

Intervall Buchungen:
<?php foreach ($interval_booking as $key => $value): $count++; ?>
<?php if ($value['status'] == Booking::planned) : ?>

Buchung <?php echo $count ?>:

Startzeit: <?php echo utf8_encode(strftime('%d %B %Y - %H:%M', $value['start_date']->getTimestamp())); ?>

Endzeit: <?php echo utf8_encode(strftime('%d %B %Y - %H:%M', $value['end_date']->getTimestamp())); ?>

Status: <?php echo $value['status']; ?>

<?php endif; ?>
<?php endforeach; ?>
<?php unset($group); ?>
<?php endif; ?>