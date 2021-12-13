<?php
require_once("../../../../includes/main.php");
$data = [];
$data['sunrise'] = Settings::LoadSettingsVar("sunrise_txt","6:00");
$data['sunset'] = Settings::LoadSettingsVar("sunset_txt","18:00");
$data['time_of_day'] = "night";
if(TimeOfDay::IsDayTime()) $data['time_of_day'] = "day";
if(TimeOfDay::IsMorning()) $data['time_of_day'] = "morning";
if(TimeOfDay::IsEvening()) $data['time_of_day'] = "evening";
$data['moonrise'] = Settings::LoadSettingsVar("moonrise_txt","18:00");
$data['moonset'] = Settings::LoadSettingsVar("moonset_txt","6:00");
$data['moon_phase'] = Settings::LoadSettingsVar("moon_phase","0");
$data['moon_out'] = 0;
if(TimeOfDay::MoonOut()) $data['moon_out'] = 1;
OutputJson($data);
?>