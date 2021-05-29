<?php
require_once("../../../../../includes/main.php");
$data = "clear";

$forecast = Forecast::LoadUpcomingForecast();
$rain = 0; $snow = 0;
foreach($forecast as $f){
    if($f['main'] == "Rain") $rain++;
    if($f['main'] == "Snow") $snow++;
}
Settings::SaveSettingsVar('forecast_rain_percent',$rain/count($forecast));
if($rain/count($forecast) > (float)Settings::LoadSettingsVar('forecast_threshold',0.2)) $data = "rain";
if($snow/count($forecast) > (float)Settings::LoadSettingsVar('forecast_threshold',0.2)) $data = "snow";
OutputJson($data);
?>