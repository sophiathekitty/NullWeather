<?php

class PullRemoteWeather {
    public  static $weather = null;
    public static function GetInstance(){
        if(is_null(PullRemoteWeather::$weather)) PullRemoteWeather::$weather = new PullRemoteWeather();
        return PullRemoteWeather::$weather;
    }
    public static function GetLiveWeather(){
        $weather = PullRemoteWeather::GetInstance();
        $recent = WeatherLogs::RecentWeather(MinutesToSeconds(Settings::LoadSettingsVar('weather_pull_delay',5)));
        if(count($recent)) return WeatherLogs::CurrentWeather();
        return $weather->PullWeather();
    }
    public static function GetLiveForecast(){
        echo "PullRemoteWeather::GetLiveForecast()\n";
        $weather = PullRemoteWeather::GetInstance();
        return $weather->PullForecast();
    }

    
    private $openWeatherMap = null;
    public function __construct()
    {
        $this->openWeatherMap = new OpenWeatherMap();
    }
    public function PullWeather(){
        echo "PullRemoteWeather::PullWeather()\n";
        $weather = null;
        if(Settings::LoadSettingsVar('main')) $weather = $this->openWeatherMap->PullLiveWeatherData();
        if(!is_null($weather)) return $weather;
        return $this->PullNullWeatherApi();
    }
    public function PullForecast(){
        echo "PullRemoteWeather->PullForecast()\n";
        $forecast = null;
        if(Settings::LoadSettingsVar('main')) $forecast = $this->openWeatherMap->PullLiveForecastData();
        if(!is_null($forecast)) return $forecast;
        return $this->PullNullWeatherForecastApi();
    }



    private function PullNullWeatherApi(){
        echo "PullRemoteWeather::PullNullWeatherApi()\n";
        $hub = Servers::GetHub();
        //print_r($hub);
        if(is_null($hub)) return null;
        if($hub['type'] == "old_hub")
            $url = "http://".$hub['url']."/api/weather/";
        else
            $url = "http://".$hub['url']."/plugins/NullWeather/api/weather/";
        $info = file_get_contents($url);
        $data = json_decode($info,true);
        //print_r($data['daytime']);
        if(isset($data['daytime'])){
            // save daytime data
            echo "save sunrise and sunset data to settings?\n";
            Settings::SaveSettingsVar("sunrise_time",$data['daytime']['sunrise']);
            Settings::SaveSettingsVar("sunset_time",$data['daytime']['sunset']);

            Settings::SaveSettingsVar("sunrise_txt",date("H:i",$data['daytime']['sunrise']));
            Settings::SaveSettingsVar("sunset_txt",date("H:i",$data['daytime']['sunset']));
        }
        if(isset($data['weather'])){
            WeatherLogs::LogCurrentWeather($data['weather']);
            return $data['weather'];
        } 
        
        return null;
    }
    private function PullNullWeatherForecastApi(){
        echo "PullRemoteWeather::PullNullWeatherForecastApi()\n";
        $hub = Servers::GetHub();
        //print_r($hub);
        if(is_null($hub)) return null;
        if($hub['type'] == "old_hub")
            $url = "http://".$hub['url']."/api/weather/?forecast=true";
        else
            $url = "http://".$hub['url']."/plugins/NullWeather/api/forecast/";
        echo "$url\n";
        $info = file_get_contents($url);
        $data = json_decode($info,true);
        //print_r($data);
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