<?php
/**
 * hourly forecast
 */
class Forecast extends clsModel {
    public $table_name = "Forecast";
    public $fields = [
        [
            'Field'=>"datetime",
            'Type'=>"datetime",
            'Null'=>"NO",
            'Key'=>"PRI",
            'Default'=>"",
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
    private static $forecast = null;
    /**
     * @return Forecast|clsModel
     */
    private static function GetForecastInstance(){
        if(is_null(Forecast::$forecast)) Forecast::$forecast = new Forecast();
        return Forecast::$forecast;
    }
    /**
     * load all the forecast data
     * @return array an array of forecast data $forecast[0]['temp']
     */
    public static function LoadForecast(){
        $forecast = Forecast::GetForecastInstance();
        return $forecast->LoadAllWhere(null,['datetime'=>'ASC']);
    }
    /**
     * load the next two days of forecast data
     * @return array an array of forecast data $forecast[0]['temp']
     */
    public static function LoadUpcomingForecast(){
        $forecast = Forecast::GetForecastInstance();
        return $forecast->LoadFieldBefore("datetime",date("Y-m-d H:i:s",time()+DaysToSeconds(2)));
    }
    /**
     * loads all the forecast data for an hour of the day
     * @return array an array of forecast data $forecast[0]['temp']
     */
    public static function LoadForecastHour($h){
        $forecast = Forecast::GetForecastInstance();
        return $forecast->LoadFieldHour('datetime',$h);
    }
    /**
     * load forecast data for specific day
     * @return array an array of forecast data $forecast[0]['temp']
     */
    public static function LoadForecastDay($date){
        $forecast = Forecast::GetForecastInstance();
        return $forecast->LoadFieldBetween('datetime',$date." 00:00:00",$date." 23:59:59");
    }
    /**
     * saves a forecast row to the database
     * @param array $forecast the forecast entry data array $forecast['temp]
     */
    public static function SaveForecast($forecast){
        Forecast::Prune();
        $instance = Forecast::GetForecastInstance();
        $forecast = $instance->CleanData($forecast);
        $row = $instance->LoadWhere(['datetime'=>$forecast['datetime']]);
        if(is_null($row)){
            $instance->Save($forecast);
        } else {
            $instance->Save($forecast,['datetime'=>$forecast['datetime']]);
        }
    }
    /**
     * prunes the forecast data that's past the current time
     */
    public static function Prune(){
        $forecast = Forecast::GetForecastInstance();
        $forecast->PruneField('datetime',HoursToSeconds(0));
    }
}
if(defined('VALIDATE_TABLES')){
    clsModel::$models[] = new Forecast();
}
?>