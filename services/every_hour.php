<?php
//echo "NullWeather::Pull Pollution\n";
Services::Start("NullWeather::EveryHour");
Services::Log("NullWeather::EveryHour","PullRemoteWeather::GetLivePollution");
PullRemoteWeather::GetLivePollution();
Services::Log("NullWeather::EveryHour","PullRemoteWeather::GetOneCall");
PullRemoteWeather::GetOneCall();
Services::Log("NullWeather::EveryHour","PullRemoteWeather::GetLiveForecast");
PullRemoteWeather::GetLiveForecast();
Services::Complete("NullWeather::EveryHour");
?>