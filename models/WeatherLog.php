<?php
define('WeatherPlugin',true);

class WeatherLogs extends clsModel {
    public $table_name = "WeatherLogs";
    public $fields = [
        [
            'Field'=>"id",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"PRI",
            'Default'=>"",
            'Extra'=>"auto_increment"
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
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"temp_min",
            'Type'=>"double",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
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
        ],[
            'Field'=>"created",
            'Type'=>"datetime",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"current_timestamp()",
            'Extra'=>""
        ]
    ];
    public static $logs = null;
    public static function GetInstance(){
        if(is_null(WeatherLogs::$logs)) WeatherLogs::$logs = new WeatherLogs();
        return WeatherLogs::$logs;
    }
    public static function AllWeather(){
        $weather = WeatherLogs::GetInstance();
        return $weather->LoadAll();
    }
    public static function CurrentWeather(){
        $weather = WeatherLogs::GetInstance();
        return $weather->LoadWhere(null,['created'=>"DESC"]);
    }
    public static function RecentWeather($seconds){
        $weather = WeatherLogs::GetInstance();
        $rows = $weather->LoadFieldAfter('created',date("Y-m-d H:i:s",time()-$seconds));
        if(count($rows)) return $rows;
        return null;
    }
    public static function Date($date){
        $weather = WeatherLogs::GetInstance();
        $rows = $weather->LoadFieldBetween('created',"$date 00:00:00","$date 23:59:59");
        if(count($rows)) return $rows;
        return null;
    }
    public static function LoadWeatherHour($h){
        $weather = WeatherLogs::GetInstance();
        return $weather->LoadFieldHour('created',$h);
    }
    public static function LogCurrentWeather($weather){
        $weather_logs = WeatherLogs::GetInstance();
        $current = WeatherLogs::RecentWeather(MinutesToSeconds(Settings::LoadSettingsVar('weather_log_delay',10)));
        if(is_null($current)) {
            WeatherLogs::Prune();
            $weather_logs->Save($weather);
        }
    }
    public static function Prune(){
        $weather = WeatherLogs::GetInstance();
        $weather->PruneField('created',DaysToSeconds(Settings::LoadSettingsVar('weather_log_days',5)));
    }
}
if(defined('VALIDATE_TABLES')){
    clsModel::$models[] = new WeatherLogs();
}
?>