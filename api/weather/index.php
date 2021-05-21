<?php
require_once("../../../../includes/main.php");
$data = [];
$data['weather'] = WeatherLogs::CurrentWeather();
OutputJson($data);
?>