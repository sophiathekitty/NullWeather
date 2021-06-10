<?php
require_once("../../../../../includes/main.php");
$data = [];
$data['forecast'] = ForecastChart::Averages();
$data['ranges'] = ForecastChart::Ranges($data['forecast']);
OutputJson($data);
?>