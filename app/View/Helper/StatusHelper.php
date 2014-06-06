<?php

class StatusHelper extends AppHelper{

    public function toCalendar($status)
    {
        switch ($status) {
            case Booking::active:
                return 'event-success';
            case Booking::planned:
                return 'event-info';
            case Booking::active_denied:
            case Booking::planning_concurred:
            case Booking::planning_rejected:
                return 'event-important';
            case Booking::archived:
                return 'event-warning';
        }
    }

    public function toBootstrap($status)
    {
        switch($status)
        {
            case Booking::active:
                return 'success';
            case Booking::planned:
                return 'primary';
            case Booking::active_denied:
            case Booking::planning_concurred:
            case Booking::planning_rejected:
                return 'danger';
            case Booking::archived:
                return 'warning';
        }
    }
}