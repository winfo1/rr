<?php

class TimelineHelper extends AppHelper
{

    public function create()
    {
        return '<ul class="timeline">';
    }

    public function addEvent($title, $time, $glyphicon = 'glyphicon-check', $status = '', $body = null, $left = true)
    {
        $v = '';
        $v .= '<li' . (($left) ? '' : ' class="timeline-inverted"') . '>' . "\r\n";
        $v .= "\t" . '<div class="timeline-badge' . (($status == '') ? '' : (' ' . $status)) . '"><i class="glyphicon ' . $glyphicon . '"></i></div>' . "\r\n";
        $v .= "\t\t" . '<div class="timeline-panel">' . "\r\n";
        $v .= "\t\t\t" . '<div class="timeline-heading">' . "\r\n";
        $v .= "\t\t\t\t" . '<h4 class="timeline-title">' . $title . '</h4>' . "\r\n";
        $v .= "\t\t\t\t" . '<p><small class="text-muted"><i class="glyphicon glyphicon-time"></i> ' . $time . '</small></p>' . "\r\n";
        $v .= "\t\t\t" . '</div>' . "\r\n";
        if (isset($body)) {
            $v .= "\t\t\t" . '<div class="timeline-body">' . "\r\n";
            $v .= "\t\t\t\t" . $body . "\r\n";
            $v .= "\t\t\t" . '</div>' . "\r\n";
        }
        $v .= "\t\t" . '</div>' . "\r\n";
        $v .= "\t" . '</div>' . "\r\n";
        $v .= '</li>' . "\r\n";

        return $v;
    }

    public function end()
    {
        return '</ul>';
    }
}