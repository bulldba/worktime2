<?php
function timediff( $begin_time, $end_time ) {
    if ( $begin_time < $end_time ) {
        $starttime = $begin_time;
        $endtime = $end_time;
    } else {
        $starttime = $end_time;
        $endtime = $begin_time;
    }
    $timediff = $endtime - $starttime;

    $days = intval( $timediff / 86400 );
    $remain = $timediff % 86400;
    $hours = intval( $remain / 3600 );
    $remain = $remain % 3600;
    $mins = intval( $remain / 60 );
    $secs = $remain % 60;

    return ($days > 0 ? $days . '天 ' : '') . ($hours > 0 ? $hours . '小时 ' : '') . ( $mins > 0 ? $mins . '分' : '' );
}
