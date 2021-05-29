<?php

class OpenWeatherMap {
    public function PullLiveWeatherData(){
        echo "OpenWeatherMap::PullLiveWeatherData()\n";
        // am i the main hub? or should i pull from the main hub?
        $main = Settings::LoadSettingsVar("main");
        $hub = Servers::GetHub();
        // grab live data from open weather map if i'm main or i there's no hub
        if($main || is_null($hub)){
            $weather = $this->PullOpenWeatherMapWeatherApi();
            if(is_null($weather)) return null;
            WeatherLogs::LogCurrentWeather($weather);
            return $weather;
        }
        return null;
    }
    public function PullLiveForecastData(){
        // am i the main hub? or should i pull from the main hub?
        $main = Settings::LoadSettingsVar("main");
        $hub = Servers::GetHub();
        if($main || is_null($hub)){
            $forecast = $this->PullOpenWeatherMapForecastApi();
            if(is_null($forecast)) return null;
            return $forecast;
        }
        return null;
    }
    
    private function PullOpenWeatherMapWeatherApi(){
        $api_key = Settings::LoadSettingsVar('weather_api_key');
        if(is_null($api_key)) return null;
        $city = Settings::LoadSettingsVar('weather_city',"Westminster,US");
        $units = Settings::LoadSettingsVar('weather_units',"imperial");
        $url = "http://api.openweathermap.org/data/2.5/weather?q=$city&units=$units&appid=$api_key";
        $info = file_get_contents($url);
        return $this->OpenWeatherMapApiToNullWeather(json_decode($info));
    }
    private function PullOpenWeatherMapForecastApi(){
        $api_key = Settings::LoadSettingsVar('weather_api_key');
        if(is_null($api_key)) return null;
        $city = Settings::LoadSettingsVar('weather_city',"Westminster,US");
        $units = Settings::LoadSettingsVar('weather_units',"imperial");
        $url = "http://api.openweathermap.org/data/2.5/forecast?q=$city&units=$units&appid=$api_key";
        $info = file_get_contents($url);
        $data = json_decode($info);
        $forecast = [];
        foreach($data->list as $d){
            $f = $this->OpenWeatherMapForecastToNullForecast($d);
            $forecast[] = $f;
            Forecast::SaveForecast($f);
        }
        return $forecast;
    }
    private function PullOpenWeatherMapOneCall(){
        $api_key = Settings::LoadSettingsVar('weather_api_key');
        if(is_null($api_key)) return null;
        $city = Settings::LoadSettingsVar('weather_city',"Westminster,US");
        $units = Settings::LoadSettingsVar('weather_units',"imperial");
        $url = "http://api.openweathermap.org/data/2.5/onecall?q=$city&units=$units&appid=$api_key";
        $info = file_get_contents($url);
        $data = json_decode($info);
    }
    private function OpenWeatherMapForecastToNullForecast($data){
        $forecast = [
            "main" => $data->weather[0]->main,
            "icon" => $data->weather[0]->icon,
            "clouds" => $data->clouds->all,
            "temp" => $data->main->temp,
            "temp_max" => $data->main->temp_max,
            "temp_min" => $data->main->temp_min,
            "feels_like" => $data->main->feels_like,
            "humidity" => $data->main->humidity,
            "pressure" => $data->main->pressure,
            "wind_deg" => $data->wind->deg,
            "wind_speed" => $data->wind->speed,
            "description" => $data->weather[0]->description,
            "datetime" => $data->dt_txt
        ];
        return $forecast;
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