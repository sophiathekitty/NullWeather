<?php
require_once("../../../../../includes/main.php");
$data = [];
$data['weather_log'] = WeatherChart::Weather();
$data['ranges'] = WeatherChart::Ranges($data['weather_log']);
OutputJson($data);
?>