<?php
require_once("../../../../../includes/main.php");
$data = [];
$data['weather'] = PullRemoteWeather::GetLiveWeather();
OutputJson($data);
?>