<?php
/**
 * weather chart... could probably be updated to use the HourlyChart base class
 */
class WeatherChart extends HourlyChart {

    private static $chart = null;
    /**
     * @return WeatherChart
     */
    private static function GetInstance(){
        if(is_null(WeatherChart::$chart)) WeatherChart::$chart = new WeatherChart();
        return WeatherChart::$chart;
    }
    /**
     * generates a weather hourly chart
     * @return array returns an array containing an hourly weather chart
     */
    public static function Weather(){
        $chart = WeatherChart::GetInstance();
        $data = [];
        $model = new WeatherLogs();
        for($h = 0; $h < 24; $h++){
            $data[$h] = $chart->HourlyAverages(WeatherLogs::LoadWeatherHour($h),$h,$model->DataFields());
        }
        return $data;

        //$data = $chart->WeatherHourlyAveragesLog();
        // looks like it's flattening the main and description fields weird...
        $fields = ["main","description"];
        foreach($fields as $field){
            for($i = 0; $i < count($data); $i++){
                if(isset($data[$i][$field]) && count($data[$i][$field]) > 0){
                    foreach($data[$i][$field] as $value => $count){
                        $data[$i][$field] = $value;
                        break;
                    }
                }
            }    
        }
        return $data;
    }
    /**
     * generates a temperature hourly chart
     * @return array returns an array containing an hourly temperature chart
     */
    public static function Temp(){
        $chart = WeatherChart::GetInstance();
        $data = [];
        for($h = 0; $h < 24; $h++){
            $data[$h] = $chart->HourlyAverages(WeatherLogs::LoadWeatherHour($h),$h,["temp"]);
        }
        return $data;
        //return $chart->WeatherHourlyTempLog();
    }
    /**
     * calculates the ranges of the weather chart
     * @param array a weather chart array
     * @return array a keyed array of ranges for the chart $ranges['temp']['min']
     */
    /*
    public static function Ranges($chart){
        $ranges = [
            'temp' => ['min'=>10000,'max'=>0],
            'hum' => ['min'=>10000,'max'=>0],
            'wind_speed' => ['min'=>10000,'max'=>0],
            'clouds' => ['min'=>10000,'max'=>0]
        ];
        for($i = 0; $i < 24; $i++){
            if($ranges['temp']['min'] > $chart[$i]['temp_min']) $ranges['temp']['min'] = $chart[$i]['temp_min'];
            if($ranges['temp']['max'] < $chart[$i]['temp_max']) $ranges['temp']['max'] = $chart[$i]['temp_max'];

            if($ranges['hum']['min'] > $chart[$i]['min_hum']) $ranges['hum']['min'] = $chart[$i]['min_hum'];
            if($ranges['hum']['max'] < $chart[$i]['max_hum']) $ranges['hum']['max'] = $chart[$i]['max_hum'];

            if($ranges['wind_speed']['min'] > $chart[$i]['min_wind']) $ranges['wind_speed']['min'] = $chart[$i]['min_wind'];
            if($ranges['wind_speed']['max'] < $chart[$i]['max_wind']) $ranges['wind_speed']['max'] = $chart[$i]['max_wind'];

            if($ranges['clouds']['min'] > $chart[$i]['min_clouds']) $ranges['clouds']['min'] = $chart[$i]['min_clouds'];
            if($ranges['clouds']['max'] < $chart[$i]['max_clouds']) $ranges['clouds']['max'] = $chart[$i]['max_clouds'];
        }
        return $ranges;
    }
    */
    /**
     * generates a temperature hourly chart... this would get replaced with the HourlyChart base class... 
     * well i'd need to move the for loop to the static function. it's currently kinda redundant having 
     * both functions....
     * @return array returns an array containing an hourly temperature chart
     */
    /*
    function WeatherHourlyTempLog(){
        $weatherLog = [];
        for($h = 0; $h < 24; $h++){
            $weatherLog[$h] = $this->WeatherHourlyTemp($h);
        }
        return $weatherLog;
    }
    */
    /**
     * generates a weather hourly chart
     * this would get replaced with the HourlyChart base class... 
     * well i'd need to move the for loop to the static function. it's currently kinda redundant having 
     * both functions....
     * @return array returns an array containing an hourly weather chart
     */
    /*
    function WeatherHourlyAveragesLog(){
        $weatherLog = [];
        for($h = 0; $h < 24; $h++){
            $weatherLog[$h] = $this->WeatherHourlyAverage($h);
        }
        return $weatherLog;
    }
    */
    /**
     * calculates the weather average for an hour
     * @param int $hour the hour to search
     * @return array returns an array containing the averages for the hour
     */
    /*
    function WeatherHourlyAverage($hour){
        $data = WeatherLogs::LoadWeatherHour($hour);
        $averages = [      
            "hour" => $hour,  
            "main" => [],
            "icon" => [],
            "clouds" => 0,
            "temp" => 0,
            "temp_max" => 0,
            "temp_min" => 100000,
            "feels_like" => 0,
            "humidity" => 0,
            "pressure" => 0,
            "wind_deg" => 0,
            "wind_speed" => 0,
            "description" => [],
            "count" => count($data)
        ];
        if($hour < 10){
            $averages['hour'] = "0".$hour;
        }
        if(count($data) == 0){
            return $averages;
        }
        $min_hum = 100000;
        $max_hum = 0;
        $min_wind = 100000;
        $max_wind = 0;
        $min_clouds = 100000;
        $max_clouds = 0;
        foreach($data as $h){
            if($averages['main'][$h['main']]){
                $averages['main'][$h['main']]++;
            } else {
                $averages['main'][$h['main']] = 1;
            }
            if($averages['icons'][$h['icon']]){
                $averages['icons'][$h['icon']]++;
            } else {
                $averages['icons'][$h['icon']] = 1;
            }
            if($averages['description'][$h['description']]){
                $averages['description'][$h['description']]++;
            } else {
                $averages['description'][$h['description']] = 1;
            }
            $averages['clouds'] += $h['clouds'];
            $averages['temp'] += $h['temp'];
            if($averages['temp_min'] > $h['temp']){
                $averages['temp_min'] = $h['temp'];
            }
            if($averages['temp_max'] < $h['temp']){
                $averages['temp_max'] = $h['temp'];
            }
            //$averages['temp_max'] += $h['temp_max'];
            //$averages['temp_min'] += $h['temp_min'];
            $averages['feels_like'] += $h['feels_like'];
            $averages['humidity'] += $h['humidity'];
            $averages['pressure'] += $h['pressure'];
            $averages['wind_deg'] += $h['wind_deg'];
            $averages['wind_speed'] += $h['wind_speed'];
            if($min_hum > $h['humidity']){
                $min_hum = $h['humidity'];
            }
            if($max_hum < $h['humidity']){
                $max_hum = $h['humidity'];
            }
            if($min_wind > $h['wind_speed']){
                $min_wind = $h['wind_speed'];
            }
            if($max_wind < $h['wind_speed']){
                $max_wind = $h['wind_speed'];
            }
            if($min_clouds > $h['clouds']){
                $min_clouds = $h['clouds'];
            }
            if($max_clouds < $h['clouds']){
                $max_clouds = $h['clouds'];
            }
        }
    
        $averages['clouds'] = round($averages['clouds']/count($data),2);
        $averages['temp'] = round($averages['temp']/count($data),2);
        //$averages['temp_max'] = round($averages['temp_max']/count($data),2);
        //$averages['temp_min'] = round($averages['temp_min']/count($data),2);
        $averages['feels_like'] = round($averages['feels_like']/count($data),2);
        $averages['humidity'] = round($averages['humidity']/count($data),2);
        $averages['pressure'] = round($averages['pressure']/count($data),2);
        $averages['wind_deg'] = round($averages['wind_deg']/count($data),2);
        $averages['wind_speed'] = round($averages['wind_speed']/count($data),2);
        $averages['max_hum'] = round($max_hum,2);
        $averages['min_hum'] = round($min_hum,2);
        $averages['max_wind'] = round($max_wind,2);
        $averages['min_wind'] = round($min_wind,2);
        $averages['max_clouds'] = round($max_clouds,2);
        $averages['min_clouds'] = round($min_clouds,2);
        $i_count = 0;
        $averages['icon'] = "";
        foreach($averages['icons'] as $key => $value){
            if($value > $i_count){
                $averages['icon'] = $key;
                $i_count = $value;
            }
        }
        unset($averages['icons']);
        return $averages;
    }
    */
    /**
     * calculates the average temperature for an hour
     * @param int $hour the hour to search
     * @return array returns an array containing the averages for the hour
     */
    /*
    function WeatherHourlyTemp($hour){
        $data = WeatherLogs::LoadWeatherHour($hour);
        if($hour < 10){
            $average['hour'] = "0".$hour;
        } else {
            $average['hour'] = $hour;
        }
        $average['temp'] = 0;
        $average['temp_min'] = 1000;
        $average['temp_max'] = 0;
        if(count($data) == 0){
            return $average;
        }
        foreach($data as $h){
            $average['temp'] += $h['temp'];
            if($average['temp_min'] > $h['temp']){
                $average['temp_min'] = $h['temp'];
            }
            if($average['temp_max'] < $h['temp']){
                $average['temp_max'] = $h['temp'];
            }
            //$average['temp_min'] += $h['temp_min'];
            //$average['temp_max'] += $h['temp_max'];
        }
    
        $average['temp'] = round($average['temp']/count($data),2);
        //$average['temp_min'] = round($average['temp_min']/count($data),2);
        //$average['temp_max'] = round($average['temp_max']/count($data),2);
        return $average;
    }
    */
}
?>