<?php
require_once("../../../../includes/main.php");
$data = [];
$data['forecast'] = ForecastStamp(Forecast::LoadForecast());
OutputJson($data);
?>