<?php

$this->Ical->create(Configure::read('display.Short'), 'Alle Buchungen', 'Europe/Berlin');

foreach($bookings as $booking)
{
    $this->Ical->addEvent(
        $booking['Booking']['startdatetime'],
        $booking['Booking']['enddatetime'],
        $booking['Booking']['name'],
        $booking['User']['username']."\n\n".$this->html->url('/bookings/view/'.$booking['Booking']['id'], true),
        array('UID'=>$booking['Booking']['id'], 'attach'=>$this->html->url('/bookings/view/'.$booking['Booking']['id'], true), 'organizer'=>$booking['User']['emailaddress'], 'location'=>$booking['Room']['name']));
}
$this->Ical->render();

?>