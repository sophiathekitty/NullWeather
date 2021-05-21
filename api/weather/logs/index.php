<?php
require_once("../../../../../includes/main.php");
$data = [];
$data['weather_log'] = WeatherChart::Weather();
OutputJson($data);
?>