<?php
require_once("../../../../includes/main.php");
$data = [];
$data['minutely'] = ForecastPrecipitation::LoadForecast();
$data['forecast'] = ForecastStamp(Forecast::LoadForecast());
$data['daily'] = ForecastStamp(ForecastDaily::LoadForecast());
OutputJson($data);
?>