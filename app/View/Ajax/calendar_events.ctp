<?php

$out = array();
foreach($bookings as $booking) {
    $out[] = array(
        'id' => $booking['Booking']['id'],
        'title' => $booking['Room']['name'] . ' ' . $booking['Booking']['name'] . ' (' . $booking['User']['username'] . ')',
        'url' =>  $this->Html->url(array('controller' => 'bookings', 'action' => 'view', $booking['Booking']['id'])),
        'class' => $this->status->toCalendar($booking['Booking']['status']),
        'start' => strtotime($booking['Booking']['startdatetime']) . '000',
        'end' => strtotime($booking['Booking']['enddatetime']) . '000'
    );
}

echo json_encode(array('success' => 1, 'result' => $out));

?>