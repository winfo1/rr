<?php

class Utils {

    /**
     * @param $str_day   i.e. 2014-05-09
     * @param $str_hour  i.e. 18:49
     * @return DateTime
     */
    public static function toDateTime($str_day, $str_hour) {
        preg_match('/(\d+)\:(\d+)/', $str_hour, $match);
        return (new DateTime($str_day))->setTime($match[1], $match[2]);
    }

    /**
     * @param DateTime $start
     * @param DateTime $end
     * @return int
     */
    public static function getDiffInMin(DateTime $start, DateTime $end) {
        $diff = $start->diff($end);
        return ($diff->h * 60) + ($diff->i);
    }

    /**
     * @param DateTime $start
     * @param $duration
     * @param $minutes_to_add
     * @return DateTime
     */
    public static function toEndDateTime(DateTime $start, $duration, &$minutes_to_add = null) {
        $min_to_add = min(Utils::getDiffInMin($start, new DateTime('tomorrow')), $duration);
        $end = clone $start;
        $end->modify('+' . $min_to_add . ' minutes');
        if ($minutes_to_add !== NULL) {
            $minutes_to_add = $min_to_add;
        }
        return $end;
    }

    /**
     * @param $str
     * @param $needle
     * @return bool
     */
    public static function startsWith($str, $needle){
        return substr($str, 0, strlen($needle)) === $needle;
    }

    /**
     * @param $str
     * @param $needle
     * @return bool
     */
    public static function endsWith($str, $needle){
        $length = strlen($needle);
        return !$length || substr($str, - $length) === $needle;
    }
}