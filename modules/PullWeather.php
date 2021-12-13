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
    /**
     * pulls live forecast data
     * @return array returns the latest forecast data
     */
    public static function GetLivePollution(){
        echo "PullRemoteWeather::GetLivePollution()\n";
        $weather = PullRemoteWeather::GetInstance();
        $pollution = $weather->PullPollution();
        print_r($pollution);
        Settings::SaveSettingsVar("Services::Weather::Pollution",date("Y-m-d H:i:s"));
        Pollution::SavePollution($pollution);
        return $pollution;
    }
    /**
     * pulls live forecast data
     * @return array returns the latest forecast data
     */
    public static function GetOneCall(){
        echo "PullRemoteWeather::GetOneCall()\n";
        $weather = PullRemoteWeather::GetInstance();
        return $weather->PullOneCall();
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
     * if main will attempt to pull from open weather map otherwise will pull from null api
     * @return array current forecast data
     */
    public function PullPollution(){
        echo "PullRemoteWeather->PullPollution()\n";
        $pollution = null;
        if(Settings::LoadSettingsVar('main')) $pollution = $this->openWeatherMap->PullLiveAirPollutionData();
        if(!is_null($pollution)) return $pollution;
        return $this->PullNullPollutionApi();
    }
    /**
     * if main will attempt to pull from open weather map otherwise will pull from null api
     * @return array one call api response or null forecast api
     */
    public function PullOneCall(){
        echo "PullRemoteWeather->PullOneCall()\n";
        $oneCall = null;
        if(Settings::LoadSettingsVar('main')) $oneCall = $this->openWeatherMap->PullOneCallApi();
        if(!is_null($oneCall)) return $oneCall;
        return $this->PullNullAllInOneApi();
    }
    /**
     * null api functions
     */
    /**
     * pull weather data from null api and saves it to the database
     * @return array returns the current weather data
     */
    private function PullNullWeatherApi(){
        echo "PullRemoteWeather->PullNullWeatherApi()\n";
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
            $data['weather']['sunrise'] = date("Y-m-d ").date("H:i:s",$data['daytime']['sunrise']);
            $data['weather']['sunset'] = date("Y-m-d ").date("H:i:s",$data['daytime']['sunset']);
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
        echo "PullRemoteWeather->PullNullWeatherForecastApi()\n";
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
        /**
     * pull pollution data from null api and saves it to the database
     * @return array returns the current pollution data
     */
    private function PullNullPollutionApi(){
        echo "PullRemoteWeather->PullNullPollutionApi()\n";
        $hub = Servers::GetHub();
        //print_r($hub);
        if(is_null($hub)) return null;
        if($hub['type'] == "old_hub")
            $url = "http://".$hub['url']."/api/weather/?pollution=1";
        else
            $url = "http://".$hub['url']."/plugins/NullWeather/api/weather/pollution/";
        $info = file_get_contents($url);
        $data = json_decode($info,true);
        //print_r($data['daytime']);
        if(isset($data['pollution'])){
            Pollution::SavePollution($data['pollution']);
            return $data['pollution'];
        } 
        
        return null;
    }
    /**
     * pull weather data from null api and saves it to the database
     * @return array returns the current weather and forecast data
     */
    private function PullNullAllInOneApi(){
        echo "PullRemoteWeather->PullNullAllInOneApi()\n";
        $hub = Servers::GetHub();
        //print_r($hub);
        if(is_null($hub)) return null;
        if($hub['type'] == "old_hub")
            $url = "http://".$hub['url']."/api/weather/";
        else
            $url = "http://".$hub['url']."/plugins/NullWeather/api/current/";
        $info = file_get_contents($url);
        $data = json_decode($info,true);
        //print_r($data['daytime']);
        // parse null weather all in one apis....
        if(isset($data['daytime'])){
            // save daytime data
            echo "save sunrise and sunset data to settings?\n";
            Settings::SaveSettingsVar("sunrise_time",$data['daytime']['sunrise']);
            Settings::SaveSettingsVar("sunset_time",$data['daytime']['sunset']);

            Settings::SaveSettingsVar("sunrise_txt",date("H:i",$data['daytime']['sunrise']));
            Settings::SaveSettingsVar("sunset_txt",date("H:i",$data['daytime']['sunset']));
            $data['weather']['sunrise'] = date("Y-m-d ").date("H:i:s",$data['daytime']['sunrise']);
            $data['weather']['sunset'] = date("Y-m-d ").date("H:i:s",$data['daytime']['sunset']);
        }
        if(isset($data['weather'])){
            echo "save current weather\n";
            WeatherLogs::LogCurrentWeather($data['weather']);
        } 
        if(isset($data['forecast'])){
            echo "save forecast\n";
            foreach($data['forecast'] as $forecast){
                Forecast::SaveForecast($forecast);
            }
        } 
        return $data;
    }

}

?>