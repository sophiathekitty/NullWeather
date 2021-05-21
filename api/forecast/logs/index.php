<?php
require_once("../../../../../includes/main.php");
$data = [];
$data['forecast'] = ForecastChart::Averages();
OutputJson($data);
?>