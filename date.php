<?php
$last_time = "2020-03-22 16:06:37";
$now_time = "2020-03-23 16:06:37";
$today = explode(" ", $now_time);
$last_day = explode(" ", $last_time);
$d1 = strtotime($last_day[0]);
$d2 = strtotime($today[0]);
$diff = $d2-$d1;
$diff = $diff/(60);
echo $today[0].PHP_EOL;
echo $diff.PHP_EOL;