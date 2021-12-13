<?php
echo "NullWeather::Pull Weather and Forecast\n";
Settings::SaveSettingsVar("service::weather_pulled",date("H:i:s"));
PullRemoteWeather::GetLiveWeather();
?>