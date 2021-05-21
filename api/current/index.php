<?php
require_once("../../../../includes/main.php");
$data = [];
$data['weather'] = WeatherLogs::CurrentWeather();
$data['forecast'] = Forecast::LoadForecast();
OutputJson($data);
?>