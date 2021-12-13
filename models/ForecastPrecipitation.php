<?php
/**
 * hourly forecast
 */
class ForecastPrecipitation extends clsModel {
    public $table_name = "ForecastPrecipitation";
    public $fields = [
        [
            'Field'=>"datetime",
            'Type'=>"datetime",
            'Null'=>"NO",
            'Key'=>"PRI",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"precipitation",
            'Type'=>"double",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ]
    ];
    private static $forecast = null;
    /**
     * @return ForecastPrecipitation|clsModel
     */
    private static function GetForecastInstance(){
        if(is_null(ForecastPrecipitation::$forecast)) ForecastPrecipitation::$forecast = new ForecastPrecipitation();
        return ForecastPrecipitation::$forecast;
    }
    /**
     * load all the forecast data
     * @return array an array of forecast data $forecast[0]['temp']
     */
    public static function LoadForecast(){
        $forecast = ForecastPrecipitation::GetForecastInstance();
        return $forecast->LoadAll();
    }
    /**
     * saves a forecast row to the database
     * @param array $forecast the forecast entry data array $forecast['temp]
     */
    public static function SaveForecast($forecast){
        Forecast::Prune();
        $instance = ForecastPrecipitation::GetForecastInstance();
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
        $forecast = ForecastPrecipitation::GetForecastInstance();
        $forecast->PruneField('datetime',0);
    }
}
if(defined('VALIDATE_TABLES')){
    clsModel::$models[] = new ForecastPrecipitation();
}
?>