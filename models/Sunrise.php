<?php
/**
 * hourly Sunrise
 */
class Sunrise extends clsModel {
    public $table_name = "Sunrise";
    public $fields = [
        [
            'Field'=>"date",
            'Type'=>"date",
            'Null'=>"NO",
            'Key'=>"PRI",
            'Default'=>"",
            'Extra'=>""
        ],[
            'Field'=>"sunrise",
            'Type'=>"time",
            'Null'=>"YES",
            'Key'=>"",
            'Default'=>null,
            'Extra'=>""
        ],[
            'Field'=>"sunset",
            'Type'=>"time",
            'Null'=>"YES",
            'Key'=>"",
            'Default'=>null,
            'Extra'=>""
        ],[
            'Field'=>"sunrise_time",
            'Type'=>"int(11)",
            'Null'=>"YES",
            'Key'=>"",
            'Default'=>null,
            'Extra'=>""
        ],[
            'Field'=>"sunset_time",
            'Type'=>"int(11)",
            'Null'=>"YES",
            'Key'=>"",
            'Default'=>null,
            'Extra'=>""
        ],[
            'Field'=>"moonrise",
            'Type'=>"time",
            'Null'=>"YES",
            'Key'=>"",
            'Default'=>null,
            'Extra'=>""
        ],[
            'Field'=>"moonset",
            'Type'=>"time",
            'Null'=>"YES",
            'Key'=>"",
            'Default'=>null,
            'Extra'=>""
        ],[
            'Field'=>"moonrise_time",
            'Type'=>"int(11)",
            'Null'=>"YES",
            'Key'=>"",
            'Default'=>null,
            'Extra'=>""
        ],[
            'Field'=>"moonset_time",
            'Type'=>"int(11)",
            'Null'=>"YES",
            'Key'=>"",
            'Default'=>null,
            'Extra'=>""
        ],[
            'Field'=>"moon_phase",
            'Type'=>"float",
            'Null'=>"YES",
            'Key'=>"",
            'Default'=>null,
            'Extra'=>""
        ]
    ];
    private static $Sunrise = null;
    /**
     * @return Sunrise|clsModel
     */
    private static function GetSunriseInstance(){
        if(is_null(Sunrise::$Sunrise)) Sunrise::$Sunrise = new Sunrise();
        return Sunrise::$Sunrise;
    }
    /**
     * load today's sunrise and sunset and moon rises and moon sets
     * @return array an array of Sunrise data $Sunrise['moonrise']
     */
    public static function LoadToday(){
        $today = Sunrise::LoadDate(date("Y-m-d"));
        if((int)$today['moonset_time'] < (int)$today['moonrise_time']){
            $yesterday = Sunrise::LoadDate(date("Y-m-d",time()-DaysToSeconds(1)));
            $tomorrow = Sunrise::LoadDate(date("Y-m-d",time()+DaysToSeconds(1)));
            if(!is_null($yesterday)){
                $today['moonrise_yesterday'] = $yesterday['moonrise'];
                $today['moonrise_time_yesterday'] = $yesterday['moonrise_time'];    
            }
            if(!is_null($tomorrow)){
                $today['moonset_tomorrow'] = $tomorrow['moonset'];
                $today['moonset_time_tomorrow'] = $tomorrow['moonset_time'];    
            }
        }
        $now = time();
        $today['sun_percent'] = 0;
        if(!is_null($today['sunrise']) && !is_null($today['sunset'])){
            $sunrise = strtotime(date("Y-m-d ").$today['sunrise']);
            $sunset = strtotime(date("Y-m-d ").$today['sunset']);
            if($sunrise < $now && $now < $sunset){
                $today['sun_percent'] = ($now-$sunrise)/($sunset-$sunrise);
            }
        }
        $today['moon_percent'] = 0;
        if(!is_null($today['moonrise']) && !is_null($today['moonset'])){
            if(isset($today['moonrise_yesterday'])) $moonrise_yesterday = strtotime(date("Y-m-d ",time()-DaysToSeconds(1)).$today['moonrise_yesterday']);
            $moonset = strtotime(date("Y-m-d ").$today['moonset']);
            $moonrise = strtotime(date("Y-m-d ").$today['moonrise']);
            if(isset($today['moonset_tomorrow'])) $moonset_tomorrow = strtotime(date("Y-m-d ",time()+DaysToSeconds(1)).$today['moonset_tomorrow']);
            if($now < $moonset && isset($today['moonrise_yesterday']) && $moonrise_yesterday < $now){
                // morning moon
                $today['moon_percent'] = ($now-$moonrise_yesterday)/($moonset-$moonrise_yesterday);
            }
            if($moonrise < $now && isset($today['moonset_tomorrow']) && $now < $moonset_tomorrow){
                // evening moon
                $today['moon_percent'] = ($now-$moonrise)/($moonset_tomorrow-$moonrise);
            }
            if($moonrise < $now && $now < $moonset){
                // moon rises and sets in the same day?
                $today['moon_percent'] = ($now-$moonrise)/($moonset-$moonrise);
            }
        }
        return $today;
    }
    /**
     * load all the Sunrise data for date
     * @return array an array of Sunrise data $Sunrise['moonrise']
     */
    public static function LoadDate($date){
        $Sunrise = Sunrise::GetSunriseInstance();
        return $Sunrise->LoadWhere(['date'=>$date]);
    }
    /**
     * save the current sunrise and sunset
     * @param int $sunrise_time the integer time for the sunrise
     * @param int $sunset_time the integer time for the sunset
     * @return array save report
     */
    public static function SaveCurrentSunrise($sunrise_time,$sunset_time){
        return Sunrise::SaveSunriseDay(date("Y-m-d"),$sunrise_time,$sunset_time);
    }
    /**
     * save the current sunrise and sunset
     * @param string $date the date of this data date("Y-m-d")
     * @param int $sunrise_time the integer time for the sunrise
     * @param int $sunset_time the integer time for the sunset
     * @return array save report
     */
    public static function SaveSunriseDay($date,$sunrise_time,$sunset_time){
        return Sunrise::SaveSunriseData([
            "date"=>$date,
            "sunrise"=>date("H:i:s",$sunrise_time),
            "sunset"=>date("H:i:s",$sunset_time),
            "sunrise_time"=>$sunrise_time,
            "sunset_time"=>$sunset_time
        ]);
    }
    /**
     * save the moonrise and moonset and moon phase
     * @param string $date the date of this data date("Y-m-d")
     * @param int $moonrise_time the integer time for the moonrise
     * @param int $moonset_time the integer time for the moonset
     * @param float $moon_phase the float percentage of the moon's phase
     * @return array save report
     */
    public static function SaveMoonrise($date,$moonrise_time,$moonset_time,$moon_phase){
        if((int)$moonrise_time == 0){
            $moonrise_time = strtotime($date." 23:59:59");
        }
        if((int)$moonset_time == 0){
            $moonset_time = strtotime($date." 00:00:00");
        }
        return Sunrise::SaveSunriseData([
            "date"=>$date,
            "moonrise"=>date("H:i:s",$moonrise_time),
            "moonset"=>date("H:i:s",$moonset_time),
            "moonrise_time"=>$moonrise_time,
            "moonset_time"=>$moonset_time,
            "moon_phase"=>$moon_phase
        ]);
    }
    /**
     * saves a Sunrise row to the database
     * @param array $Sunrise the Sunrise entry data array $Sunrise['sunrise']
     * @return array save report
     */
    public static function SaveSunriseData($Sunrise){
        Sunrise::Prune();
        $instance = Sunrise::GetSunriseInstance();
        $Sunrise = $instance->CleanData($Sunrise);
        if($Sunrise['date'] == date("Y-m-d")){
            // cache today's data to the settings table for stuff that might use that
            if(isset($Sunrise["sunrise_time"])){
                Settings::SaveSettingsVar("sunrise_time",$Sunrise["sunrise_time"]);
                Settings::SaveSettingsVar("sunset_time",$Sunrise["sunset_time"]);
                Settings::SaveSettingsVar("sunrise_txt",date("H:i",$Sunrise["sunrise_time"]));
                Settings::SaveSettingsVar("sunset_txt",date("H:i",$Sunrise["sunset_time"]));        
            }
            if(isset($Sunrise["moonrise_time"])){
                Settings::SaveSettingsVar("moonrise_time",$Sunrise["moonrise_time"]);
                Settings::SaveSettingsVar("moonset_time",$Sunrise["moonset_time"]);
                Settings::SaveSettingsVar("moonrise_txt",date("H:i",$Sunrise["moonrise_time"]));
                Settings::SaveSettingsVar("moonset_txt",date("H:i",$Sunrise["moonset_time"]));
                Settings::SaveSettingsVar("moon_phase",$Sunrise["moon_phase"]);
            }    
        }
        $row = $instance->LoadWhere(['date'=>$Sunrise['date']]);
        if(is_null($row)){
            $instance->Save($Sunrise);
        } else {
            $instance->Save($Sunrise,['date'=>$Sunrise['date']]);
        }
    }
    /**
     * prunes the Sunrise data that's past the current time
     */
    public static function Prune(){
        $Sunrise = Sunrise::GetSunriseInstance();
        $Sunrise->PruneField('date',DaysToSeconds(2));
    }
}
if(defined('VALIDATE_TABLES')){
    clsModel::$models[] = new Sunrise();
}
?>