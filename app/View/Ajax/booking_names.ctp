<?php

$out = array();
foreach($bookings as $booking) {
    $out[] = array(
        'name' => $booking
    );
}

echo json_encode($out);

?>