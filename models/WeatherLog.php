<?php
define('WeatherPlugin',true);

class WeatherLogs extends clsModel {
    public $table_name = "WeatherLogs";
    public $fields = [
        [
            'Field'=>"created",
            'Type'=>"datetime",
            'Null'=>"NO",
            'Key'=>"PRI",
            'Default'=>"current_timestamp()",
            'Extra'=>""
        ],[
            'Field'=>"sunrise",
            'Type'=>"datetime",
            'Null'=>"YES",
            'Key'=>"",
            'Default'=>null,
            'Extra'=>""
        ],[
            'Field'=>"sunset",
            'Type'=>"datetime",
            'Null'=>"YES",
            'Key'=>"",
            'Default'=>null,
            'Extra'=>""
        ],[
            'Field'=>"main",
            'Type'=>"varchar(10)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"icon",
            'Type'=>"varchar(10)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"clouds",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"0",
            'Extra'=>""
        ],[
            'Field'=>"temp",
            'Type'=>"double",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"temp_max",
            'Type'=>"double",
            'Null'=>"YES",
            'Key'=>"",
            'Default'=>null,
            'Extra'=>""
        ],[
            'Field'=>"temp_min",
            'Type'=>"double",
            'Null'=>"YES",
            'Key'=>"",
            'Default'=>null,
            'Extra'=>""
        ],[
            'Field'=>"feels_like",
            'Type'=>"double",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"humidity",
            'Type'=>"double",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"pressure",
            'Type'=>"double",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"wind_deg",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"wind_speed",
            'Type'=>"double",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"description",
            'Type'=>"varchar(100)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ]
    ];
    public static $logs = null;
    /**
     * @return WeatherLogs|clsModel
     */
    public static function GetInstance(){
        if(is_null(WeatherLogs::$logs)) WeatherLogs::$logs = new WeatherLogs();
        return WeatherLogs::$logs;
    }
    /**
     * loads all the weather data
     * @return array an array of all the weather data $weather[0]['temp']
     */
    public static function AllWeather(){
        $weather = WeatherLogs::GetInstance();
        return $weather->LoadAll();
    }
    /**
     * loads the current weather
     * @return array returns keyed array of current weather $weather['temp]
     */
    public static function CurrentWeather(){
        $weather = WeatherLogs::GetInstance();
        return $weather->LoadWhere(null,['created'=>"DESC"]);
    }
    /**
     * loads recent weather
     * @param int $seconds how far back in seconds to load
     * @return array|null returns recent weather array or null if no weather log within time frame $weather[0]['temp']
     */
    public static function RecentWeather($seconds){
        $weather = WeatherLogs::GetInstance();
        $rows = $weather->LoadFieldAfter('created',date("Y-m-d H:i:s",time()-$seconds));
        if(count($rows)) return $rows;
        return null;
    }
    /**
     * loads weather on date
     * @param string $date the date string to load YYYY-MM-DD
     * @return array|null returns array of weather from date $weather[0]['temp'] 
     */
    public static function Date($date){
        $weather = WeatherLogs::GetInstance();
        $rows = $weather->LoadFieldBetween('created',"$date 00:00:00","$date 23:59:59");
        if(count($rows)) return $rows;
        return null;
    }
    /**
     * load weather from hour of day
     * @param int $h the hour to load
     * @return array array of weather data for the specified hour of the day
     */
    public static function LoadWeatherHour($h){
        $weather = WeatherLogs::GetInstance();
        return $weather->LoadFieldHour('created',$h);
    }
    /**
     * save the current weather data if it's been weather_log_delay since pulling
     * @param array $weather the keyed weather array $weather['temp']
     * @param array save report
     */
    public static function LogCurrentWeather($weather){
        $weather_logs = WeatherLogs::GetInstance();
        WeatherLogs::Prune();
        $weather = $weather_logs->CleanData($weather);
        return $weather_logs->Save($weather);
    }
    /**
     * prune the weather data
     */
    public static function Prune(){
        $weather = WeatherLogs::GetInstance();
        $weather->PruneField('created',DaysToSeconds(Settings::LoadSettingsVar('weather_log_days',5)));
    }
}
if(defined('VALIDATE_TABLES')){
    clsModel::$models[] = new WeatherLogs();
}
?>