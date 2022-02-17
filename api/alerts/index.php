<?php
require_once("../../../../includes/main.php");
$data = [];
$data['alerts'] = WeatherAlerts::LoadAlerts();
OutputJson($data);
?>