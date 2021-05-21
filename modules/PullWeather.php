<?php

class PullRemoteWeather {
    private $openWeatherMap = new OpenWeatherMap();
    public function PullWeather(){
        $weather = null;
        if(Settings::LoadSettingsVar('main')) $weather = $this->openWeatherMap->PullLiveWeatherData();
        if(!is_null($weather)) return $weather;
        return $this->PullNullWeatherApi();
    }
    public function PullForecast(){
        $forecast = null;
        if(Settings::LoadSettingsVar('main')) $forecast = $this->openWeatherMap->PullLiveForecastData();
        if(!is_null($forecast)) return $forecast;
        return $this->PullNullWeatherForecastApi();
    }



    private function PullNullWeatherApi(){
        $hub = Servers::GetHub();
        if(is_null($hub)) return null;
        $url = "http://".$hub['url']."/api/weather/";
        $info = file_get_contents($url);
        $data = json_decode($info,true);
        if(isset($data['weather'])){
            WeatherLogs::LogCurrentWeather($data['weather']);
            return $data['weather'];
        } 
        return null;
    }
    private function PullNullWeatherForecastApi(){
        $hub = Servers::GetHub();
        if(is_null($hub)) return null;
        $url = "http://".$hub['url']."/api/weather/?forecast=true";
        $info = file_get_contents($url);
        $data = json_decode($info,true);
        if(isset($data['forecast'])){
            foreach($data['forecast'] as $forecast){
                Forecast::SaveForecast($forecast);
            }
            return $data['forecast'];
        } 
        return null;
    }

}

?>