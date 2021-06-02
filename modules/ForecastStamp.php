<?php
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
?>