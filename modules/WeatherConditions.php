<?php
/**
 * some static functions for checking weather conditions
 */
class WeatherCondition {
    /**
     * is it currently raining or going to maybe be raining in the near future
     * @return bool returns true if it's currently raining or if there's rain in the upcoming forecast
     */
    public static function IsRainy(){
        $weather = WeatherLogs::CurrentWeather();
        if($weather){
            if($weather['main'] == "Rain") return true;
        }
        $forecast = Forecast::LoadUpcomingForecast();
        foreach($forecast as $f){
            if($f['main'] == "Rain"){
                return true;
            }
        }
        return false;
    }
    /**
     * is currently raining. current weather might not be accurate
     * @return bool returns true if current weather is Rain
     */
    public static function IsRaining(){
        $weather = WeatherLogs::CurrentWeather();
        if($weather){
            return ($weather['main'] == "Rain");
        }
        return false;
    }
    /**
     * has it been snowing or even is it going to snow
     * @return bool returns true if it's snowed recently or will snow soon
     */
    public static function IsSnowy(){
        $weather = WeatherLogs::RecentWeather(DaysToSeconds(3));
        foreach($weather as $w){
            if($w['main'] == "Snow") return true;
        }
        $forecast = Forecast::LoadUpcomingForecast();
        foreach($forecast as $f){
            if($f['main'] == "Snow"){
                return true;
            }
        }
        return false;
    }
    /**
     * is the current weather snow
     * @return bool returns true when current weather is snow
     */
    public static function IsSnowing(){
        $weather = WeatherLogs::CurrentWeather();
        if($weather){
            return ($weather['main'] == "Snow");
        }
        return false;
    }
    /**
     * is it not stormy out right now
     * @return bool returns true if current weather is clear or clouds
     */
    public static function IsClear(){
        $weather = WeatherLogs::CurrentWeather();
        if($weather){
            return ($weather['main'] == "Clear" || $weather['main'] == "Clouds");
        }
        return false;
    }
}
?>