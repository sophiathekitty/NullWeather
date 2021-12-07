<?php
/**
 * class for generating an hourly chart of pollution data
 */
class PollutionChart extends HourlyChart {
    private static $instance;
    /**
     * @return PollutionChart|HourlyChart
     */
    private static function GetInstance(){
        if(is_null(PollutionChart::$instance)) PollutionChart::$instance = new PollutionChart();
        return PollutionChart::$instance;
    }
    /**
     * generate an hourly chart of all pollution data
     * @return array an array containing the hourly chart
     */
    public static function PollutionHourlyChart(){
        $instance = PollutionChart::GetInstance();
        $pollution = new Pollution();
        $fields = $pollution->DataFields();
        $chart = [];
        for($h = 0; $h < 24; $h++){
            $chart[] = $instance->HourlyAverages(Pollution::LoadHour($h),$h,$fields);
        }
        return $chart;
    }
    /**
     * generate an hourly chart of just the dust fields
     * @return array an array containing the hourly chart
     */
    public static function DustHourlyChart(){
        $instance = PollutionChart::GetInstance();
        $chart = [];
        for($h = 0; $h < 24; $h++){
            $chart[] = $instance->HourlyAverages(Pollution::LoadHour($h),$h,["pm2_5","pm10"]);
        }
        return $chart;
    }
}
?>