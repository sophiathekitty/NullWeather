<?php

class ForecastChart {
    private static $log = null;
    private static function GetInstance(){
        if(is_null(ForecastChart::$log)) ForecastChart::$log = new ForecastChart();
        return ForecastChart::$log;
    }
    public static function Averages(){
        $log = ForecastChart::GetInstance();
        return $log->ForecastHourlyAveragesLog();
    }
    public static function Temps(){
        $log = ForecastChart::GetInstance();
        return $log->ForecastHourlyTempLog();
    }
    function ForecastHourlyTempLog(){
        $weatherLog = [];
        for($h = 0; $h < 24; $h++){
            $weatherLog[$h] = $this->ForecastHourlyTemp($h);
        }
        return $weatherLog;
    }

    function ForecastHourlyAveragesLog(){
        $weatherLog = [];
        for($h = 0; $h < 24; $h++){
            $weatherLog[$h] = $this->ForecastHourlyAverage($h);
        }
        return $weatherLog;
    }

    function ForecastHourlyAverage($hour){
        $data = Forecast::LoadForecastHour($hour);
        if(count($data) == 0){
            // is the previous hour 0, 3, 6, 9, 12, 15, 18, 21
            $data = Forecast::LoadForecastHour($hour-1);
            /*if(count($data) > 0){
                //$data = GetForecastForHour($hour-1);
                // if 22 we need 21,21, 0
                if($hour+2 > 23){
                    $data = GetForecastForHour(0);
                    $data = GetForecastForHour(0);
                } else {
                    $data = GetForecastForHour($hour+2);
                    $data = GetForecastForHour($hour+2);
                }
            }*/
        }
        if(count($data) == 0){
            // if next hour is 3, 6, 9, 12, 15, 18, 21
            $data = Forecast::LoadForecastHour($hour+1);
            /*if(count($data) > 0){
                //$data = GetForecastForHour($hour+1);
                $data = GetForecastForHour($hour-2);
                $data = GetForecastForHour($hour-2);
            }*/
        }
        // if hour is 23
        if(count($data) == 0){
            //$data = GetForecastForHour(0);
            $data = Forecast::LoadForecastHour(0);
            //$data = GetForecastForHour(21);
            //$data = GetForecastForHour(21);
        }
        //$data = array_merge($data,GetWeatherForHour($hour));
        $averages = [      
            "hour" => $hour,  
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
        if($hour < 10){
            $averages['hour'] = "0".$hour;
        }
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
        return $averages;
    }


    function ForecastHourlyTemp($hour){
        $data = Forecast::LoadForecastHour($hour);
        if(count($data) == 0){
            $data = Forecast::LoadForecastHour($hour-1);
        }
        if(count($data) == 0){
            $data = Forecast::LoadForecastHour($hour+1);
        }
        if($hour < 10){
            $average['hour'] = "0".$hour;
        } else {
            $average['hour'] = $hour;
        }
        $average['temp'] = 0;
        $average['temp_min'] = 0;
        $average['temp_max'] = 0;
        if(count($data) == 0){
            return $average;
        }
        foreach($data as $h){
            $average['temp'] += $h['temp'];
            $average['temp_min'] += $h['temp_min'];
            $average['temp_max'] += $h['temp_max'];
        }

        $average['temp'] = round($average['temp']/count($data),2);
        $average['temp_min'] = round($average['temp_min']/count($data),2);
        $average['temp_max'] = round($average['temp_max']/count($data),2);
        return $average;
    }
}

?>