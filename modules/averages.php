<?php
/**
 * calculates the averages for the weather and forecast data
 * could probably just extend the HourlyChart class and have it do the averaging with the full data sets instead of the hour sets
 * and maybe i could have the HourlyChart do the sorted list of strings paired with their count
 */
class WeatherAverages{
    /**
     * calculates the averages of the forecast data
     */
    public static function ForecastWeatherAverages(){
        $logs = Forecast::LoadForecast();
        return WeatherAverages::CalculateAverageWeatherLogs($logs);
    }
    /**
     * calculates the averages of the weather data
     */
    public static function GetWeatherAverages(){
        $logs = WeatherLogs::AllWeather();
        return WeatherAverages::CalculateAverageWeatherLogs($logs);
    }
    /**
     * calculates the averages of the weather on a specific date
     * @param string $date the date YYYY-MM-DD
     */    
    public static function WeatherAveragesDate($date){
        $logs = WeatherLogs::Date($date);
        return WeatherAverages::CalculateAverageWeatherLogs($logs);
    }
    /**
     * combines the weather and forecast data and finds the averages
     */
    public static function CombinedWeatherAverages(){
        $logs = Forecast::LoadForecast();
        $logs = array_merge($logs,WeatherLogs::AllWeather());
        return WeatherAverages::CalculateAverageWeatherLogs($logs);
    }
    /**
     * calculates the averages for the weather and forecast data.
     * i have a generic version of this in the HourlyChart class. 
     * i could make that version include arrays of strings
     * @param array $data the weather/forecast data to be averaged
     */
    public static function CalculateAverageWeatherLogs($data){
        $averages = [      
            "main" => [],
            "icon" => [],
            "clouds" => 0,
            "temp" => 0,
            "temp_max" => 0,
            "temp_min" => 0,
            "feels_like" => 0,
            "humidity" => 0,
            "pressure" => 0,
            "wind_deg" => 0,
            "wind_speed" => 0,
            "description" => [],
            "count" => count($data)
        ];
        if(count($data) == 0){
            return $averages;
        }
        $min_temp = 100000;
        $max_temp = 0;
        $min_hum = 100000;
        $max_hum = 0;
        $min_wind = 100000;
        $max_wind = 0;
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
            $averages['temp_max'] += $h['temp_max'];
            if($max_temp < $h['temp_max']) $max_temp = $h['temp_max'];
            $averages['temp_min'] += $h['temp_min'];
            if($min_temp > $h['temp_min']) $min_temp = $h['temp_min'];
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
        }
    
        $averages['clouds'] = round($averages['clouds']/count($data),2);
        $averages['temp'] = round($averages['temp']/count($data),2);
        //$averages['temp_max'] = round($averages['temp_max']/count($data),2);
        //$averages['temp_min'] = round($averages['temp_min']/count($data),2);
        $averages['temp_max'] = $max_temp;
        $averages['temp_min'] = $min_temp;
        $averages['feels_like'] = round($averages['feels_like']/count($data),2);
        $averages['humidity'] = round($averages['humidity']/count($data),2);
        $averages['pressure'] = round($averages['pressure']/count($data),2);
        $averages['wind_deg'] = round($averages['wind_deg']/count($data),2);
        $averages['wind_speed'] = round($averages['wind_speed']/count($data),2);
        $averages['max_hum'] = round($max_hum,2);
        $averages['min_hum'] = round($min_hum,2);
        $averages['max_wind'] = round($max_wind,2);
        $averages['min_wind'] = round($min_wind,2);
        $i_count = 0;
        $averages['icon'] = "";
        foreach($averages['icons'] as $key => $value){
            if($value > $i_count){
                $averages['icon'] = $key;
                $i_count = $value;
            }
        }
        $main = [];
        $icons = [];
        $descriptions = [];
        foreach($averages['main'] as $key => $value){
            $icon = ['name'=>$key,'count'=>$value,'percent'=>round($value/count($data),5)];
            array_push($main,$icon);
        }
        foreach($averages['icons'] as $key => $value){
            $icon = ['name'=>$key,'count'=>$value,'percent'=>round($value/count($data),5)];
            array_push($icons,$icon);
        }
        foreach($averages['description'] as $key => $value){
            $icon = ['name'=>$key,'count'=>$value,'percent'=>round($value/count($data),5)];
            array_push($descriptions,$icon);
        }
        usort($icons,'sort_by_percent');
        usort($main,'sort_by_percent');
        usort($descriptions,'sort_by_percent');
        $averages['icons'] = $icons;
        $averages['main'] = $main;
        $averages['description'] = $descriptions;
        return $averages;
    }
    
}
/**
 * $icons[0]['percent'];
 * usort($icons,'sort_by_percent');
 */
function sort_by_percent($a, $b) {
	if($a['percent'] == $b['percent']){ return 0 ; }
	return ($a['percent'] < $b['percent']) ? 1 : -1;
}
?>