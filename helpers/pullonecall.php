<?php
require_once("../../../includes/main.php");
$OpenWeatherMap = new OpenWeatherMap();
OutputJson($OpenWeatherMap->PullOneCallApi());
?>