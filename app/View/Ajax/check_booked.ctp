<?php

$v = '';
$v .= '<div class="page-header">';
$v .= '<h1>Wiederholungen teilweise belegt</h1>';
$v .= '<h4>Trotzdem mit den noch verfügbaren Terminen fortfahren?</h4>';
$v .= '</div>';
$v .= $this->Timeline->create();

$blocked = array();
$blocked_text = '';

$hasErrorInIntervalLoop = false;

for ($i = 1; $i <= count($interval_booking); $i++) {

    if ($interval_booking[$i]['in_use']) {
        $hasErrorInIntervalLoop = true;
    }

    $blocked_text =
        '<strong>' . $interval_booking[$i]['blocked'][0]['Booking']['name'] . '</strong><br>' .
        (new DateTime($interval_booking[$i]['blocked'][0]['Booking']['startdatetime']))->format('H:i') . ' - ' .
        (new DateTime($interval_booking[$i]['blocked'][0]['Booking']['enddatetime']))->format('H:i') . ' von ' .
        $interval_booking[$i]['blocked'][0]['User']['username'];

    $v .= $this->Timeline->addEvent(
        'Wiederholung ' . $i,
        $this->mytime->toReadableDate($interval_booking[$i]['start_date']->getTimestamp(), true) . ' ' . $interval_booking[$i]['start_date']->format('H:i') . '-' . $interval_booking[$i]['end_date']->format('H:i'),
        ($interval_booking[$i]['in_use']) ? 'glyphicon-remove' : 'glyphicon-ok',
        ($interval_booking[$i]['in_use']) ? 'danger' : 'success',
        ($interval_booking[$i]['in_use']) ? $blocked_text : null
    );
}

$v .= $this->Timeline->end();

echo json_encode(array('hasErrorInIntervalLoop' => $hasErrorInIntervalLoop, 'timeLine' => $v));