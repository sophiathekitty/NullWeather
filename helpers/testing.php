<?php
require_once("../../../includes/main.php");
//print_r(PullRemoteWeather::GetLiveForecast());
$OpenWeatherMap = new OpenWeatherMap();
//$OpenWeatherMap->PullOneCallApi();
//$OpenWeatherMap->PullLiveAirPollutionData();
OutputJson($OpenWeatherMap->PullOneCallApi());
?>