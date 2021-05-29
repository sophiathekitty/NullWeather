<?php

class Forecast extends clsModel {
    public $table_name = "Forecast";
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
            'Field'=>"datetime",
            'Type'=>"datetime",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ]
    ];
    public static $forecast = null;
    public static function GetForecastInstance(){
        if(is_null(Forecast::$forecast)) Forecast::$forecast = new Forecast();
        return Forecast::$forecast;
    }
    public static function LoadForecast(){
        $forecast = Forecast::GetForecastInstance();
        return $forecast->LoadAll();
    }
    public static function LoadUpcomingForecast(){
        $forecast = Forecast::GetForecastInstance();
        return $forecast->LoadFieldBefore("datetime",date("Y-m-d H:i:s",time()+DaysToSeconds(2)));
    }
    public static function LoadForecastHour($h){
        $forecast = Forecast::GetForecastInstance();
        return $forecast->LoadFieldHour('datetime',$h);
    }
    public static function SaveForecast($forecast){
        Forecast::Prune();
        $instance = Forecast::GetForecastInstance();
        $row = $instance->LoadWhere(['datetime'=>$forecast['datetime']]);
        if(is_null($row)){
            $instance->Save($forecast);
        } else {
            $instance->Save($forecast,['datetime'=>$forecast['datetime']]);
        }
    }
    public static function Prune(){
        $forecast = Forecast::GetForecastInstance();
        $forecast->PruneField('datetime',0);
    }
}
if(defined('VALIDATE_TABLES')){
    clsModel::$models[] = new Forecast();
}
?>