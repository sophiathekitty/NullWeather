<?php
require_once("../../../../../includes/main.php");
$data = [];
$data['pollution'] = PullRemoteWeather::GetLivePollution();
OutputJson($data);
?>