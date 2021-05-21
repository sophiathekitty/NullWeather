<?php
require_once("../../../../../includes/main.php");
$data = [];
$data['forecast'] = PullRemoteWeather::GetLiveForecast();
OutputJson($data);
?>