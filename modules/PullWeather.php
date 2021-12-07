<?php
/**
 * pulls the weather for either OpenWeatherMap or the main hub
 */
class PullRemoteWeather {
    private static $weather = null;
    private static function GetInstance(){
        if(is_null(PullRemoteWeather::$weather)) PullRemoteWeather::$weather = new PullRemoteWeather();
        return PullRemoteWeather::$weather;
    }
    /**
     * pulls weather if it's been longer than weather_pull_delay since last pulled
     * @return array returns the current weather
     */
    public static function GetLiveWeather(){
        $weather = PullRemoteWeather::GetInstance();
        $recent = WeatherLogs::RecentWeather(MinutesToSeconds(Settings::LoadSettingsVar('weather_pull_delay',5)));
        if(count($recent)) return WeatherLogs::CurrentWeather();
        return $weather->PullWeather();
    }
    /**
     * pulls live forecast data
     * @return array returns the latest forecast data
     */
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
    /**
     * if main will attempt to pull from open weather map otherwise will pull from null api
     * @return array current weather data
     */
    public function PullWeather(){
        echo "PullRemoteWeather::PullWeather()\n";
        $weather = null;
        if(Settings::LoadSettingsVar('main')) $weather = $this->openWeatherMap->PullLiveWeatherData();
        if(!is_null($weather)) return $weather;
        return $this->PullNullWeatherApi();
    }
    /**
     * if main will attempt to pull from open weather map otherwise will pull from null api
     * @return array current forecast data
     */
    public function PullForecast(){
        echo "PullRemoteWeather->PullForecast()\n";
        $forecast = null;
        if(Settings::LoadSettingsVar('main')) $forecast = $this->openWeatherMap->PullLiveForecastData();
        if(!is_null($forecast)) return $forecast;
        return $this->PullNullWeatherForecastApi();
    }


    /**
     * pull weather data from null api and saves it to the database
     * @return array returns the current weather data
     */
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
    /**
     * pull forecast data from null api and saves it to the database
     * @return array returns the current forecast data
     */
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