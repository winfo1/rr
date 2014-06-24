<?php

setlocale(LC_ALL, 'de_DE@euro', 'de_DE', 'deu_deu');

/**
 * Class MyTime
 */
class MyTime {

    /**
     * @param bool $long_month
     * @return string
     */
    private static function _getMonth($long_month = false) {
        return $long_month ? '%B' : '%b';
    }

    /**
     * @param $date
     * @param bool $long_month
     * @return string
     */
    public static function toReadableDate($date, $long_month = false) {
        if (WIN) {
            return utf8_encode(strftime('%#d '. MyTime::_getMonth($long_month) .' %Y', $date));
        } else {
            return strftime('%e '. MyTime::_getMonth($long_month) .' %Y', $date);
        }
    }

    /**
     * @param $datetime
     * @param bool $long_month
     * @return string
     */
    public static function toReadableDateTime($datetime, $long_month = false) {
        if (WIN) {
            return utf8_encode(strftime('%#d '. MyTime::_getMonth($long_month) .' %Y - %H:%M', $datetime));
        } else {
            return strftime('%e '. MyTime::_getMonth($long_month) .' %Y - %H:%M', $datetime);
        }
    }
}