<?php
require_once("../../../../includes/main.php");
$data = [];
$data['forecast'] = Forecast::LoadForecast();
OutputJson($data);
?>