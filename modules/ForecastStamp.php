<?php
/**
 * rounds the values to whole numbers and adds day of week (Mon) and time (5am)
 * @param array $forecast the forecast data to be parsed
 */
function ForecastStamp($forecast){
    $days = ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"];
    for($i = 0; $i < count($forecast); $i++){
        $forecast[$i]['clouds'] = round($forecast[$i]['clouds']);
        $forecast[$i]['temp'] = round($forecast[$i]['temp']);
        $forecast[$i]['temp_max'] = round($forecast[$i]['temp_max']);
        $forecast[$i]['temp_min'] = round($forecast[$i]['temp_min']);
        $forecast[$i]['wind_speed'] = round($forecast[$i]['wind_speed']);
        $forecast[$i]['feels_like'] = round($forecast[$i]['feels_like']);
        $forecast[$i]['humidity'] = round($forecast[$i]['humidity']);
        $forecast[$i]['pressure'] = round($forecast[$i]['pressure']);
        $forecast[$i]['wind_deg'] = round($forecast[$i]['wind_deg']);

        $forecast[$i]['time'] = date("ga",strtotime($forecast[$i]['datetime']));
        $forecast[$i]['day'] = $days[date("w",strtotime($forecast[$i]['datetime']))];
    }
    return $forecast;
}
/**
 * does an average of the hourly forecast data for a daily forecast
 * once i start pulling the one call this might not be needed
 * @param string $date the date to create a forecast average of
 */
function ForecastDay($date){
    $forecast = Forecast::LoadForecastDay($date);
    $averages = [
        "date" => $date,
        "main" => [],
        "icons_day" => [],
        "icons_night" => [],
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
        "count" => count($forecast)
    ];
    $min_temp = 100000;
    $max_temp = 0;
    $min_hum = 100000;
    $max_hum = 0;
    $min_wind = 100000;
    $max_wind = 0;
    foreach($forecast as $h){
        if($averages['main'][$h['main']]){
            if($h['main'] != "Clear" && $h['main'] != "Clouds")
            $averages['main'][$h['main']]++;
        } else {
            $averages['main'][$h['main']] = 1;
        }
        if(strpos($h['icon'],"d") > 0){
            if($averages['icons_day'][$h['icon']]){
                $averages['icons_day'][$h['icon']]++;
            } else {
                $averages['icons_day'][$h['icon']] = 1;
            }    
        } else {
            if($averages['icons_night'][$h['icon']]){
                $averages['icons_night'][$h['icon']]++;
            } else {
                $averages['icons_night'][$h['icon']] = 1;
            }    
        }
        if($averages['description'][$h['description']]){
            if($h['main'] != "Clear" && $h['main'] != "Clouds")
            $averages['description'][$h['description']]++;
        } else {
            $averages['description'][$h['description']] = 1;
            if($h['main'] != "Clear" && $h['main'] != "Clouds")
            $averages['description'][$h['description']]++;
        }
        $averages['clouds'] += $h['clouds'];
        $averages['temp'] += $h['temp'];
        $averages['temp_max'] += $h['temp_max'];
        if($max_temp < $h['temp_max']) $max_temp = (float)$h['temp_max'];
        $averages['temp_min'] += $h['temp_min'];
        if($min_temp > $h['temp_min']) $min_temp = (float)$h['temp_min'];
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

    $averages['clouds'] = round($averages['clouds']/count($forecast),2);
    $averages['temp'] = round($averages['temp']/count($forecast),2);
    //$averages['temp_max'] = round($averages['temp_max']/count($data),2);
    //$averages['temp_min'] = round($averages['temp_min']/count($data),2);
    $averages['temp_max'] = $max_temp;
    $averages['temp_min'] = $min_temp;
    $averages['feels_like'] = round($averages['feels_like']/count($forecast),2);
    $averages['humidity'] = round($averages['humidity']/count($forecast),2);
    $averages['pressure'] = round($averages['pressure']/count($forecast),2);
    $averages['wind_deg'] = round($averages['wind_deg']/count($forecast),2);
    $averages['wind_speed'] = round($averages['wind_speed']/count($forecast),2);
    $averages['max_hum'] = round($max_hum,2);
    $averages['min_hum'] = round($min_hum,2);
    $averages['max_wind'] = round($max_wind,2);
    $averages['min_wind'] = round($min_wind,2);
    $i_count = 0;
    $averages['icon_day'] = "";
    foreach($averages['icons_day'] as $key => $value){
        if($value > $i_count){
            $averages['icon_day'] = $key;
            $i_count = $value;
        }
    }
    unset($averages['icons_day']);
    $i_count = 0;
    $averages['icon_night'] = "";
    foreach($averages['icons_night'] as $key => $value){
        if($value > $i_count){
            $averages['icon_night'] = $key;
            $i_count = $value;
        }
    }
    unset($averages['icons_night']);

    $i_count = 0;
    $main = "";
    foreach($averages['main'] as $key => $value){
        if($value > $i_count){
            $main = $key;
            $i_count = $value;
            if(strtolower($key) == "rain" || strtolower($key) == "snow")
            $averages['precipitation_chance'] = round(($value-0.5)/count($forecast)*100);
            else
            $averages['precipitation_chance'] = 0;

        }
    }
    $averages['main'] = $main;

    $i_count = 0;
    $main = "";
    foreach($averages['description'] as $key => $value){
        if($value > $i_count){
            $main = $key;
            $i_count = $value;
        }
    }
    $averages['description'] = $main;

    return $averages;
}
?>