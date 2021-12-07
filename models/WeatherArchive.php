<?php
/**
 * Daily weather archives
 */
class WeatherArchivesDaily extends clsModel {
    public $table_name = "WeatherArchivesDaily";
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
            'Field'=>"sunrise",
            'Type'=>"time",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"sunset",
            'Type'=>"time",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"created",
            'Type'=>"date",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ]
    ];
    private static $archives = null;
    /**
     * @return WeatherArchivesDaily|clsModel
     */
    private static function GetInstance(){
        if(is_null(WeatherArchivesDaily::$archives)) WeatherArchivesDaily::$archives = new WeatherArchivesDaily();
        return WeatherArchivesDaily::$archives;
    }
    /**
     * save a new archive
     * @param array $data the data array to be saved
     * @return array save report ['last_insert_id'=>$id,'error'=>clsDB::$db_g->get_err(),'sql'=>$sql,'row'=>$row]
     */
    public static function SaveArchive($data){
        WeatherArchivesDaily::Prune();
        $archives = WeatherArchivesDaily::GetInstance();
        $data = $archives->CleanDataSkipFields("id",$data);
        return $archives->Save($data);
    }
    /**
     * prunes archives older than weather_archive_weeks
     */
    public static function Prune(){
        $instance = WeatherArchivesDaily::GetInstance();
        $instance->PruneField('created',WeeksToDays(Settings::LoadSettingsVar('weather_archive_weeks',5)));
    }
}
if(defined('VALIDATE_TABLES')){
    clsModel::$models[] = new WeatherArchivesDaily();
}
?>