<?php
require_once("../../../../../includes/main.php");
$data = [];
$data['forecast'] = [
    ForecastDay(date("Y-m-d",time()+DaysToSeconds(1))),
    ForecastDay(date("Y-m-d",time()+DaysToSeconds(2))),
    ForecastDay(date("Y-m-d",time()+DaysToSeconds(3)))
];

OutputJson($data);
?>