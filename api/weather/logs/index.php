<?php
require_once("../../../../../includes/main.php");
$data = [];
$data['weather_log'] = WeatherChart::Weather();
$data['ranges'] = HourlyChart::Ranges($data['weather_log'],new WeatherLogs());
OutputJson($data);
?>