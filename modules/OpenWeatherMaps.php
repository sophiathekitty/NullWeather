<?php

class OpenWeatherMap {
    public function PullLiveWeatherData(){
        // am i the main hub? or should i pull from the main hub?
        $main = Settings::LoadSettingsVar("main");
        if($main){
            $weather = $this->PullOpenWeatherMapWeatherApi();
            if(is_null($weather)) return null;
            $weather_logs = new WeatherLogs();
            $weather_logs->Save($weather);
        }
    }
    public function PullLiveForecastData(){
        // am i the main hub? or should i pull from the main hub?
        $main = Settings::LoadSettingsVar("main");
        if($main){
            $forecast = $this->PullOpenWeatherMapForecastApi();
            if(is_null($forecast)) return null;
            $forecast = new Forecast();
        }
    }
    
    private function PullOpenWeatherMapWeatherApi(){
        $api_key = Settings::LoadSettingsVar('weather_api_key');
        if(is_null($api_key)) return null;
        $url = "http://api.openweathermap.org/data/2.5/weather?q=Westminster,US&units=imperial&appid=$api_key";
        $info = file_get_contents($url);
        return $this->OpenWeatherMapApiToNullWeather(json_decode($info));
    }
    private function PullOpenWeatherMapForecastApi(){
        $api_key = Settings::LoadSettingsVar('weather_api_key');
        if(is_null($api_key)) return null;
        $url = "http://api.openweathermap.org/data/2.5/forecast?q=Westminster,US&units=imperial&appid=$api_key";
        $info = file_get_contents($url);
        return json_decode($info);
    }
    
    private function OpenWeatherMapApiToNullWeather($data){
        $weather = [
            "main" => $data->current->weather[0]->main,
            "icon" => $data->current->weather[0]->icon,
            "clouds" => $data->current->clouds->all,
            "temp" => $data->current->main->temp,
            "temp_max" => $data->current->main->temp_max,
            "temp_min" => $data->current->main->temp_min,
            "feels_like" => $data->current->main->feels_like,
            "humidity" => $data->current->main->humidity,
            "pressure" => $data->current->main->pressure,
            "wind_deg" => $data->current->wind->deg,
            "wind_speed" => $data->current->wind->speed,
            "description" => $data->current->weather[0]->description
        ];
        return $weather;
    }
}
?>