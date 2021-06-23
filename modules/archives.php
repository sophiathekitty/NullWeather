<?php
/**
 * handles archiving weather logs and compressing the data for long term storage
 */
class WeatherArchiver {
    /**
     * create an archive with today's data
     */
    public static function ArchiveWeatherLogsToday(){
        return WeatherArchiver::ArchiveWeatherLogsDate(date("Y-m-d"));
    }
    /**
     * create an archive with yesterday's data
     */
    public static function ArchiveWeatherLogsYesterday(){
        return WeatherArchiver::ArchiveWeatherLogsDate(date("Y-m-d",time()-DaysToSeconds(1)));
    }
    /**
     * create an archive with data on a specific date
     * @param string $date the date you want to archive
     */
    public static function ArchiveWeatherLogsDate($date){
        $weather = WeatherAverages::WeatherAveragesDate($date);
        //print_r($weather);
        $weather['sunrise'] = Settings::LoadSettingsVar("sunrise_txt","6:00");
        $weather['sunset'] = Settings::LoadSettingsVar("sunset_txt","18:00");
        $archive = WeatherArchivesDaily::SaveArchive($weather);
        //print_r($archive);
        return $archive;
    }
}
?>