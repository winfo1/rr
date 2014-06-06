<?php

$this->Ical->create(Configure::read('display.Short'), 'Alle Buchungen', 'Europe/Berlin');

foreach ($bookings as $booking) {
    $url = $this->Html->url(array('controller' => 'bookings', 'action' => 'view', $booking['Booking']['id']), true);
    $this->Ical->addEvent(
        $booking['Booking']['startdatetime'],
        $booking['Booking']['enddatetime'],
        $booking['Booking']['name'],
        $booking['User']['username'] . "\n\n" . $url,
        array('UID' => $booking['Booking']['id'],
            'attach' => $url,
            'organizer' => $booking['User']['emailaddress'],
            'url' => $url,
            'location' => $booking['Room']['name']));
}
$this->Ical->render();

?>