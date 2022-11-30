<?php
require_once("../../../../includes/main.php");
$data = [];
$data['weather'] = WeatherLogs::CurrentWeather();
$data['forecast'] = Forecast::LoadForecast();
$data['pollution'] = Pollution::LoadCurrentPollution();
$data['daytime'] = Sunrise::LoadToday();
OutputJson($data);
?>