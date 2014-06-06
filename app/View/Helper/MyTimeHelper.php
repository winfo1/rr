<?php

App::import('Lib', 'MyTime');

class MyTimeHelper extends AppHelper {

    public function toReadableDate($date, $long_month = false) {
        return MyTime::toReadableDate($date, $long_month);
    }

    public function toReadableDateTime($datetime, $long_month = false) {
        return MyTime::toReadableDateTime($datetime, $long_month);
    }
}