<?php
require_once("../../../../../includes/main.php");
$data = [];
$data['pollution'] = PollutionChart::PollutionHourlyChart();
$data['ranges'] = HourlyChart::Ranges($data['pollution'],new Pollution());
OutputJson($data);
?>