<?php

$history = "2020-03-18 22:30:41";
$today = date("Y-m-d ");
$explode = explode(" ", $history);
$history_day = $explode[0];
$d1 = strtotime($history_day);
$d2 = strtotime($today);
$diff = $d2-$d1;
$diff = $diff/(60*60*24);
$check = floor($diff);
echo $check . PHP_EOL;
?>