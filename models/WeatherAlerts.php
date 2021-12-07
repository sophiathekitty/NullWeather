<?php
/**
 * hourly forecast
 */
class WeatherAlerts extends clsModel {
    public $table_name = "WeatherAlerts";
    public $fields = [
        [
            'Field'=>"id",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"PRI",
            'Default'=>"",
            'Extra'=>"auto_increment"
        ],[
            'Field'=>"sender_name",
            'Type'=>"varchar(50)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"event",
            'Type'=>"varchar(100)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"start",
            'Type'=>"datetime",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"end",
            'Type'=>"datetime",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"description",
            'Type'=>"text",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"tags",
            'Type'=>"varchar(255)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ]
    ];
    private static $instance = null;
    /**
     * @return WeatherAlerts|clsModel
     */
    private static function GetInstance(){
        if(is_null(WeatherAlerts::$instance)) WeatherAlerts::$instance = new WeatherAlerts();
        return WeatherAlerts::$instance;
    }
    /**
     * load all the forecast data
     * @return array an array of forecast data $forecast[0]['temp']
     */
    public static function LoadAlerts(){
        $forecast = WeatherAlerts::GetInstance();
        return $forecast->LoadAll();
    }
    /**
     * saves a forecast row to the database
     * @param array $data the weather alert entry data array $alert['event']
     */
    public static function SaveAlert($alert){
        Forecast::Prune();
        $instance = WeatherAlerts::GetInstance();
        $alert = $instance->CleanData($alert);
        $row = $instance->LoadWhere(['event'=>$alert['event'],'start'=>$alert['start']]);
        if(is_null($row)){
            $instance->Save($alert);
        } else {
            $instance->Save($alert,['event'=>$alert['event'],'start'=>$alert['start']]);
        }
    }
    /**
     * prunes the the alerts that have ended
     */
    public static function Prune(){
        $forecast = WeatherAlerts::GetInstance();
        $forecast->PruneField('end',0);
    }
}
if(defined('VALIDATE_TABLES')){
    clsModel::$models[] = new WeatherAlerts();
}
?>