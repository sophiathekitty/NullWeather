<?php
require_once("../../../../includes/main.php");
$data = [];
$data['settings'] = Settings::LoadSettingsPallet("weather_");
OutputJson($data);
?>