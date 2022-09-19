<?php
//echo "NullWeather::Pull Weather and Forecast\n";
//Settings::SaveSettingsVar("service::weather_pulled",date("H:i:s"));
Services::Start("NullWeather::EveryMinute");
Services::Log("NullWeather::EveryMinute","PullRemoteWeather::GetLiveWeather");
PullRemoteWeather::GetLiveWeather();
Services::Complete("NullWeather::EveryMinute");
?>