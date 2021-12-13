<?php
require_once("../../../../../includes/main.php");
$data = [];
$data['forecast'] = ForecastChart::ForecastAverages();
$data['ranges'] = HourlyChart::Ranges($data['forecast'],new Forecast());
OutputJson($data);
?>