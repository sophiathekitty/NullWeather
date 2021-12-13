<?php
/**
 * class for handling OpenWeatherMap apis
 */
class OpenWeatherMap {
    /**
     * pulls live weather data from OpenWeatherMap (only if this is the main hub) and logs current weather
     * @return array|null returns an array of current weather or null
     */
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
    /**
     * pulls live 5 day (every 3 hours) forecast data from OpenWeatherMap (only if this is the main hub)
     * @return array|null returns an array of forecast data or null
     */
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
    /**
     * pull the one call api
     * @todo finish testing and make it so only works for main hub
     */
    public function PullOneCallApi(){
        //echo "OpenWeatherMap::PullOneCallApi()\n";
        return $this->PullOpenWeatherMapOneCall();
    }
    /**
     * pull the air pollution api
     * @todo finish testing and make it so only works for main hub
     */
    public function PullLiveAirPollutionData(){
        // 
        echo "OpenWeatherMap::PullLiveAirPollutionData()\n";
        $pollution = $this->PullOpenWeatherMapAirPollutionApi();
        print_r($pollution);
    }
    /**
     * can actually call the api
     * @return bool return true if has api_key and is main hub
     */
    public function CanCallApi(){
        $api_key = Settings::LoadSettingsVar('weather_api_key');
        if(is_null($api_key)) return false;
        $main = Settings::LoadSettingsVar("main");
        $hub = Servers::GetHub();
        return ($main || is_null($hub));
    }
    /**
     * has it been long enough to pull weather api
     * @return bool returns true if we can pull the weather api
     */
    private function CanPullWeatherApi(){
        $recent = WeatherLogs::RecentWeather(MinutesToSeconds(Settings::LoadSettingsVar('weather_pull_delay',1)));
        if(count($recent)) return false;
        return true;
    }
    /**
     * has it been long enough to pull weather api
     * @return bool returns true if we can pull the weather api
     */
    private function CanPullOneCallApi(){
        $bool = Settings::LoadSettingsVar('weather_one_call',1);
        return ($bool == 1 || $bool == "1");
    }

    /**
     * pulls the current weather api: https://openweathermap.org/current
     * @return array returns an array of weather data $weather['temp']
     */
    private function PullOpenWeatherMapWeatherApi(){
        $api_key = Settings::LoadSettingsVar('weather_api_key');
        if(is_null($api_key)) return null;
        if(!$this->CanPullWeatherApi()) return null;
        $city = Settings::LoadSettingsVar('weather_city',"Westminster,US");
        $units = Settings::LoadSettingsVar('weather_units',"imperial");
        $url = "http://api.openweathermap.org/data/2.5/weather?q=$city&units=$units&appid=$api_key";
        $info = file_get_contents($url);
        $data = json_decode($info);
        // grab sunrise data
        print_r($data);
    return $this->OpenWeatherMapApiToNullWeather($data);
    }
    /**
     * pulls the 5 Day / 3 Hour Forecast api: https://openweathermap.org/forecast5
     * @return array the forecast data array $forecast[0]['temp']
     */
    private function PullOpenWeatherMapForecastApi(){
        $api_key = Settings::LoadSettingsVar('weather_api_key');
        if(is_null($api_key)) return null;
        $city = Settings::LoadSettingsVar('weather_city',"Denver,CO,US");
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
    /**
     * pulls the air pollution api: https://openweathermap.org/api/air-pollution
     * @return array returns a keyed array of pollution data $pollution['aqi']
     */
    private function PullOpenWeatherMapAirPollutionApi(){
        $api_key = Settings::LoadSettingsVar('weather_api_key');
        if(is_null($api_key)) return null;
        $lat = Settings::LoadSettingsVar("weather_lat");
        $lon = Settings::LoadSettingsVar("weather_lon");
        if(is_null($lat) || is_null($lon)){
            $geo = $this->PullOpenWeatherMapGeoCodingApi();
            $lat = $geo['lat'];
            $lon = $geo['lon'];
        }
        $url = "http://api.openweathermap.org/data/2.5/air_pollution?lat=$lat&lon=$lon&appid=$api_key";
        $info = file_get_contents($url);
        $data = json_decode($info);
        print_r($data);
        return $this->OpenWeatherMapApiToNullPollution($data);
        //Pollution::SavePollution($pollution);
        //return $pollution;
    }
    /**
     * look up lat and lon for city geocoding api: https://openweathermap.org/api/geocoding-api
     * uses weather_address SettingsVar
     * @return array a keyed array ['lat'=>$lat,'lon'=>$lon]
     */
    private function PullOpenWeatherMapGeoCodingApi(){
        $api_key = Settings::LoadSettingsVar('weather_api_key');
        if(is_null($api_key)) return null;
        $city = Settings::LoadSettingsVar('weather_city',"Denver,CO,US");
        $url = "http://api.openweathermap.org/geo/1.0/direct?q=$city&limit=1&appid=$api_key";
        $info = file_get_contents($url);
        $data = json_decode($info);
        Settings::SaveSettingsVar('weather_lat',$data[0]->lat);
        Settings::SaveSettingsVar('weather_lon',$data[0]->lon);
        return ['lat'=>$data[0]->lat,'lon'=>$data[0]->lon];
    }
    /**
     * pulls the one call api: https://openweathermap.org/api/one-call-api
     * @todo needs to processes the raw data into something i can feed into the weather and forecast models
     */
    private function PullOpenWeatherMapOneCall(){
        $api_key = Settings::LoadSettingsVar('weather_api_key');
        if(is_null($api_key)) return null;
        if(!$this->CanPullOneCallApi()) return null;
        $lat = Settings::LoadSettingsVar("weather_lat");
        $lon = Settings::LoadSettingsVar("weather_lon");
        if(is_null($lat) || is_null($lon)){
            $geo = $this->PullOpenWeatherMapGeoCodingApi();
            $lat = $geo['lat'];
            $lon = $geo['lon'];
        }
        $units = Settings::LoadSettingsVar('weather_units',"imperial");
        $url = "http://api.openweathermap.org/data/2.5/onecall?lat=$lat&lon=$lon&units=$units&appid=$api_key";
        $info = file_get_contents($url);
        $data = json_decode($info);
        // needs to parse data
        //print_r($data);
        $oneCall = [];
        $oneCall['current'] = $this->OpenWeatherMapApiToNullWeather($data);
        WeatherLogs::LogCurrentWeather($oneCall['current']);
        // set sunrise and sunset SettingsVar
        Settings::SaveSettingsVar("sunrise_time",strtotime($oneCall['current']['sunrise']));
        Settings::SaveSettingsVar("sunset_time",strtotime($oneCall['current']['sunset']));

        Settings::SaveSettingsVar("sunrise_txt",date("H:i",strtotime($oneCall['current']['sunrise'])));
        Settings::SaveSettingsVar("sunset_txt",date("H:i",strtotime($oneCall['current']['sunset'])));

        $oneCall['minutely'] = [];
        foreach($data->minutely as $m){
            $precipitation = $this->OpenWeatherMapApiToNullPrecipitation($m);
            ForecastPrecipitation::SaveForecast($precipitation);
            $oneCall['minutely'][] = $precipitation;
        }
        $oneCall['hourly'] = [];
        foreach($data->hourly as $h){
            $forecast = $this->OpenWeatherMapForecastToNullForecast($h);
            Forecast::SaveForecast($forecast);
            $oneCall['hourly'][] = $forecast;
        }
        $oneCall['daily'] = [];
        Settings::SaveSettingsVar("moonrise_time",$data->daily[0]->moonrise);
        Settings::SaveSettingsVar("moonset_time",$data->daily[0]->moonset);

        Settings::SaveSettingsVar("moonrise_txt",date("H:i",$data->daily[0]->moonrise));
        Settings::SaveSettingsVar("moonset_txt",date("H:i",$data->daily[0]->moonset));

        Settings::SaveSettingsVar("moon_phase",$data->daily[0]->moon_phase);    
        
        foreach($data->daily as $d){
            $daily = $this->OpenWeatherMapDailyForecastToNullForecast($d);
            ForecastDaily::SaveForecast($daily);
            $oneCall['daily'][] = $daily;
        }
        return $oneCall;
    }
    /**
     * OpenWeatherMap json to Null data array
     */
    /**
     * converts the json object of OpenWeatherMap Forecast items into a Null Forecast data array
     * @param object $data the object for an individual forecast item $data->weather[0]->main
     * @return array an associated/keyed array of forecast data $forecast['main']
     */
    private function OpenWeatherMapForecastToNullForecast($data){
        if(isset($data->main)){
            // every 3 hours forecast
            return $this->OpenWeatherMapOldForecastToNullForecast($data);
        }

        $forecast = [
            "main" => $data->weather[0]->main,
            "icon" => $data->weather[0]->icon,
            "clouds" => $data->clouds,
            "temp" => $data->temp,
            "feels_like" => $data->feels_like,
            "humidity" => $data->humidity,
            "pressure" => $data->pressure,
            "wind_deg" => $data->wind_deg,
            "wind_speed" => $data->wind_speed,
            "wind_gust" => $data->wind_gust,
            "dew_point" => $data->dew_point,
            "uvi" => $data->uvi,
            "visibility" => $data->visibility,
            "pop" => $data->pop,
            "description" => $data->weather[0]->description,
            "datetime" => date("Y-m-d H:i:s",$data->dt)
        ];
        return $forecast;
    }
    /**
     * converts the json object of OpenWeatherMap Forecast items into a Null Forecast data array
     * @param object $data the object for an individual forecast item $data->weather[0]->main
     * @return array an associated/keyed array of forecast data $forecast['main']
     */
    private function OpenWeatherMapOldForecastToNullForecast($data){
        if(isset($data->temp)){
            // daily forecast
            return $this->OpenWeatherMapDailyForecastToNullForecast($data);
        }
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
    /**
     * converts the json object of OpenWeatherMap Daily Forecast items into a Null Forecast data array
     * @param object $data the object for an individual forecast item $data->weather[0]->main
     * @return array an associated/keyed array of forecast data $forecast['main']
     */
    private function OpenWeatherMapDailyForecastToNullForecast($data){
        if(isset($data->main)){
            // every 3 hours forecast
            return $this->OpenWeatherMapOldForecastToNullForecast($data);
        }
        $forecast = [
            "main" => $data->weather[0]->main,
            "icon" => $data->weather[0]->icon,
            "clouds" => $data->clouds,
            "temp_day" => $data->temp->day,
            "temp_night" => $data->temp->night,
            "temp_eve" => $data->temp->eve,
            "temp_morn" => $data->temp->morn,
            "temp_max" => $data->temp->max,
            "temp_min" => $data->temp->min,
            "feels_like_day" => $data->feels_like->day,
            "feels_like_night" => $data->feels_like->night,
            "feels_like_eve" => $data->feels_like->eve,
            "feels_like_morn" => $data->feels_like->morn,
            "humidity" => $data->humidity,
            "pressure" => $data->pressure,
            "wind_deg" => $data->wind_deg,
            "wind_speed" => $data->wind_speed,
            "wind_gust" => $data->wind_gust,
            "moon_phase" => $data->moon_phase,
            "description" => $data->weather[0]->description,
            "sunrise" => date("Y-m-d H:i:s",$data->sunrise),
            "sunset" => date("Y-m-d H:i:s",$data->sunset),
            "moonrise" => date("Y-m-d H:i:s",$data->moonrise),
            "moonset" => date("Y-m-d H:i:s",$data->moonset),
            "datetime" => date("Y-m-d H:i:s",$data->dt)
        ];
        return $forecast;
    }
    /**
     * converts the json object of OpenWeatherMap Weather data into NullWeather data array
     * @param object $data the object from the weather api $data->current->weather[0]->main
     * @return array an associated/keyed array of weather data $weather['main']
     */
    private function OpenWeatherMapApiToNullWeather($data){
        if(isset($data->current->main)) return $this->OpenWeatherMainMapApiToNullWeather($data);
        $weather = [
            "main" => $data->current->weather[0]->main,
            "icon" => $data->current->weather[0]->icon,
            "clouds" => $data->current->clouds,
            "temp" => $data->current->temp,
            "feels_like" => $data->current->feels_like,
            "humidity" => $data->current->humidity,
            "pressure" => $data->current->pressure,
            "wind_deg" => $data->current->wind_deg,
            "wind_speed" => $data->current->wind_speed,
            "wind_gust" => $data->current->wind_gust,
            "dew_point" => $data->current->dew_point,
            "uvi" => $data->current->uvi,
            "visibility" => $data->current->visibility,
            "sunrise" => date("Y-m-d H:i:s",$data->current->sunrise),
            "sunset" => date("Y-m-d H:i:s",$data->current->sunset),
            "datetime" => date("Y-m-d H:i:s",$data->current->dt),
            "description" => $data->current->weather[0]->description
        ];
        return $weather;
    }
    /**
     * converts the json object of OpenWeatherMap Weather data into NullWeather data array
     * @todo need to do testing
     * @param object $data the object from the weather api $data->current->weather[0]->main
     * @return array an associated/keyed array of weather data $weather['main']
     */
    private function OpenWeatherMainMapApiToNullWeather($data){
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
            "sunrise" => date("Y-m-d H:i:s",$data->current->sys->sunrise),
            "sunset" => date("Y-m-d H:i:s",$data->current->sys->sunset),
            "datetime" => date("Y-m-d H:i:s",$data->current->dt),
            "description" => $data->current->weather[0]->description
        ];
        return $weather;
    }
    /**
     * converts the json object of OpenWeatherMap Pollution data into Null Pollution data array
     * @param object $data the object from the weather api data->list[0]->main->aqi
     * @return array the associated/keyed data array $pollution['aqi']
     */
    private function OpenWeatherMapApiToNullPollution($data){
        $pollution = [
            "created" => date("Y-m-d H:i:s",$data->list[0]->dt),
            "aqi" => $data->list[0]->main->aqi,
            "co" => $data->list[0]->components->co,
            "no" => $data->list[0]->components->no,
            "no2" => $data->list[0]->components->no2,
            "o3" => $data->list[0]->components->o3,
            "so2" => $data->list[0]->components->so2,
            "pm2_5" => $data->list[0]->components->pm2_5,
            "pm10" => $data->list[0]->components->pm10,
            "nh3" => $data->list[0]->components->nh3
                ];
        return $pollution;
    }
    /**
     * converts the json object of OpenWeatherMap minutely precipitation item into Null Precipitation data array
     * @param object $data the object from the weather api $data->precipitation
     * @return array the associated/keyed data array $precipitation['precipitation']
     */
    private function OpenWeatherMapApiToNullPrecipitation($data){
        $precipitation = [
            "datetime" => date("Y-m-d H:i:s",$data->dt),
            "precipitation" => $data->precipitation
                ];
        return $precipitation;
    }
}
?>