<?php
/**
 * long term archive of pollution data for different days of the week during different months
 */
class PollutionDeepArchive extends clsModel {
    public $hourly_type = "double";
    public $table_name = "PollutionDeepArchive";
    public $fields = [
        [
            'Field'=>"id",
            'Type'=>"int(11)",
            'Null'=>"NO",
            'Key'=>"PRI",
            'Default'=>"",
            'Extra'=>"auto_increment"
        ],[
            'Field'=>"day_of_week",
            'Type'=>"tinyint(4)",
            'Null'=>"NO",
            'Key'=>"Index",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"month",
            'Type'=>"tinyint(4)",
            'Null'=>"NO",
            'Key'=>"Index",
            'Default'=>"",
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
     * @return PollutionDeepArchive|clsModel
     */
    private static function GetInstance(){
        if(is_null(PollutionDeepArchive::$instance)) PollutionDeepArchive::$instance = new PollutionDeepArchive();
        return PollutionDeepArchive::$instance;
    }
    /**
     * loads all the archived pollution data
     * @return array an array of keyed arrays of table data $rows[0]['id']
     */
    public static function LoadPollution(){
        $instance = PollutionDeepArchive::GetInstance();
        return $instance->LoadAll();
    }
    /**
     * loads the pollution data for a specific day of the week during a specific month
     * @param int $day_of_week the day of the week (1-7)
     * @param int $month the month (1-12)
     * @return array a keyed array of table data $row['id']
     */
    public static function LoadPollutionDate($day_of_week,$month){
        $instance = PollutionDeepArchive::GetInstance();
        return $instance->LoadWhere(['day_of_week'=>$day_of_week,'month'=>$month]);
    }
    /**
     * save the pollution data
     * @param array $pollution the keyed array of table data. must include $pollution['day_of_week'] and $pollution['month']
     * @return array the $pollution data loaded from the database for save validation purposes
     */
    public static function SavePollution($pollution){
        $instance = PollutionDeepArchive::GetInstance();
        $pollution = $instance->CleanData($pollution);
        $data = PollutionDeepArchive::LoadPollutionDate($pollution['day_of_week'],$pollution['month']);
        if(is_null($data)){
            $instance->Save($pollution,['day_of_week'=>$pollution['day_of_week'],'month'=>$pollution['month']]);
        } else {
            $instance->Save($pollution);
        }
        return PollutionDeepArchive::LoadPollutionDate($pollution['day_of_week'],$pollution['month']);
    }
}
if(defined('VALIDATE_TABLES')){
    clsModel::$models[] = new PollutionDeepArchive();
}
?>