<?php
require_once("../../../../../includes/main.php");
$data = [];
$data['forecast'] = [
    ForecastDay(date("Y-m-d",time()+DaysToSeconds(1))),
    ForecastDay(date("Y-m-d",time()+DaysToSeconds(2))),
    ForecastDay(date("Y-m-d",time()+DaysToSeconds(3))),
    ForecastDay(date("Y-m-d",time()+DaysToSeconds(4))),
    ForecastDay(date("Y-m-d",time()+DaysToSeconds(5)))
];
//$data['forecast_daily'] = ForecastDaily::LoadForecast();
$data['forecast_daily'] = ForecastStamp(ForecastDaily::LoadForecast());
//print_r($data);
OutputJson($data);
?>