<?php
require_once("../../../../includes/main.php");
$data = [];
$data['sunrise'] = Settings::LoadSettingsVar("sunrise_txt","6:00");
$data['sunset'] = Settings::LoadSettingsVar("sunset_txt","18:00");
$data['time_of_day'] = "night";
if(TimeOfDay::IsDayTime()) $data['time_of_day'] = "day";
if(TimeOfDay::IsMorning()) $data['time_of_day'] = "morning";
if(TimeOfDay::IsEvening()) $data['time_of_day'] = "evening";
OutputJson($data);
?>