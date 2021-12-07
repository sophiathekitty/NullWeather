<?php
/**
 * an archive of the daily pollution averages and ranges
 */
class PollutionArchive extends clsModel {
    public $hourly_type = "double";
    public $table_name = "PollutionArchive";
    public $fields = [
        [
            'Field'=>"created",
            'Type'=>"date",
            'Null'=>"NO",
            'Key'=>"PRI",
            'Default'=>"current_timestamp()",
            'Extra'=>""
        ],[
            'Field'=>"name",
            'Type'=>"varchar(10)",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"0",
            'Extra'=>""
        ]
    ];
    private static $instance = null;
    /**
     * @return PollutionArchive|clsModel
     */
    private static function GetInstance(){
        if(is_null(PollutionArchive::$instance)) PollutionArchive::$instance = new PollutionArchive();
        return PollutionArchive::$instance;
    }
    /**
     * loads all the daily averages archive data
     * @return array an array of keyed arrays of table data $pollution[0]['co']
     */
    public static function LoadPollution(){
        $instance = PollutionArchive::GetInstance();
        return $instance->LoadAll();
    }
    public static function LoadPollutionDate($date){
        $instance = PollutionArchive::GetInstance();
        return $instance->LoadWhere(['created'=>$date]);
    }
    /**
     * save pollution data
     * @param array a keyed array of table data $pollution['co']
     * @return array the saved $pollution array loaded from the database to validate saves
     */
    public static function SavePollution($pollution){
        Pollution::Prune();
        $instance = PollutionArchive::GetInstance();
        $pollution = $instance->CleanData($pollution);
        if(isset($pollution['created'])){
            $old = PollutionArchive::LoadPollutionDate($pollution['created']);
            if(is_null($old)){
                $instance->Save($pollution);
            } else{
                $instance->Save($pollution,['created'=>$pollution['created']]);
            }
        } else {
            $instance->Save($pollution);
        }
        
    }
    /**
     * prunes the archive table by weather_archive_weeks SettingsVar
     */
    public static function Prune(){
        $instance = PollutionArchive::GetInstance();
        $instance->PruneField('created',WeeksToDays(Settings::LoadSettingsVar('weather_archive_weeks',5)));
    }
}
if(defined('VALIDATE_TABLES')){
    clsModel::$models[] = new PollutionArchive();
}
?>