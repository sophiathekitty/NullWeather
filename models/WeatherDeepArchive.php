<?php
/**
 * a monthly archive of weather data for long term storage.
 * looks like it's planning to create a new entry every time it saves a month.
 * might have it use month as the primary key and have it average them together
 * that 
 */
class WeatherArchivesMonthly extends clsModel {
    public $table_name = "WeatherArchivesMonthly";
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
            'Field'=>"month",
            'Type'=>"date",
            'Null'=>"NO",
            'Key'=>"",
            'Default'=>"",
            'Extra'=>""
        ]
    ];
    private static $archives = null;
    /**
     * @return WeatherArchivesMonthly|clsModel
     */
    private static function GetInstance(){
        if(is_null(WeatherArchivesMonthly::$archives)) WeatherArchivesMonthly::$archives = new WeatherArchivesMonthly();
        return WeatherArchivesMonthly::$archives;
    }
    /**
     * save an archive
     * @param array $data the keyed data array to save
     */
    public static function SaveArchive($data){
        $archives = WeatherArchivesMonthly::GetInstance();
        $data = $archives->CleanDataSkipFields("id",$data);
        return $archives->Save($data);
    }
}
if(defined('VALIDATE_TABLES')){
    clsModel::$models[] = new WeatherArchivesMonthly();
}
?>