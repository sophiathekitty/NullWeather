<?php
/**
 * pollution logs
 */
class Pollution extends clsModel {
    public $table_name = "Pollution";
    public $fields = [
        [
            'Field'=>"created",
            'Type'=>"datetime",
            'Null'=>"NO",
            'Key'=>"PRI",
            'Default'=>"current_timestamp()",
            'Extra'=>""
        ],[
            'Field'=>"aqi",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"0",
            'Extra'=>""
        ],[
            'Field'=>"co",
            'Type'=>"double",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"no",
            'Type'=>"double",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"no2",
            'Type'=>"double",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"o3",
            'Type'=>"double",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"so2",
            'Type'=>"double",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"pm2_5",
            'Type'=>"double",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"pm10",
            'Type'=>"double",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"nh3",
            'Type'=>"double",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ]
    ];
    private static $instance = null;
    /**
     * @return Pollution|clsModel
     */
    private static function GetInstance(){
        if(is_null(Pollution::$instance)) Pollution::$instance = new Pollution();
        return Pollution::$instance;
    }
    /**
     * load all the pollution logs data
     * @return array an array of keyed arrays of table data $pollution[0]['aqi']
     */
    public static function LoadPollution(){
        $instance = Pollution::GetInstance();
        return $instance->LoadAll();
    }
    /**
     * load the pollution data for an hour during the day
     * @param int $h the hour (0-23)
     * @return array an array of keyed arrays of table data $pollution[0]['aqi']
     */
    public static function LoadHour($h){
        $instance = Pollution::GetInstance();
        return $instance->LoadFieldHour('created',$h);
    }
    /**
     * load the pollution data for a date
     * @param string $date the date to load "YYYY-MM-DD"
     * @return array an array of keyed arrays of table data $pollution[0]['aqi']
     */
    public static function LoadDay($date){
        $instance = Pollution::GetInstance();
        return $instance->LoadFieldBetween('created',$date." 00:00:00",$date." 23:59:59");
    }
    /**
     * load the latest pollution row
     * @return array returns a keyed array of table data $pollution['aqi'] 
     */
    public static function LoadCurrentPollution(){
        $instance = Pollution::GetInstance();
        return $instance->LoadMostRecentlyCreated();
    }
    /**
     * save the pollution data to the database
     * @param array $pollution a keyed array of table data $pollution['aqi']
     * @return array returns the current pollution to validate save
     */
    public static function SavePollution($pollution){
        Pollution::Prune();
        $instance = Pollution::GetInstance();
        $pollution = $instance->CleanData($pollution);
        //$pollution['created'] = date("Y-m-d H:i:s");
        $instance->Save($pollution);
        Settings::SaveSettingsVar("PollutionModel::Saved",$pollution['created'].date(" H:i:s ").clsDB::$db_g->get_err());
        return Pollution::LoadCurrentPollution();
    }
    /**
     * prunes the pollution table by the weather_log_days SettingsVar
     */
    public static function Prune(){
        $instance = Pollution::GetInstance();
        $instance->PruneField('created',DaysToSeconds(Settings::LoadSettingsVar('weather_log_days',5)));
    }
}
if(defined('VALIDATE_TABLES')){
    clsModel::$models[] = new Pollution();
}
?>