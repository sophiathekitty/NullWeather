<?php

class WeatherColor {
    

    function WeatherColor($weather) {
        return $weather;
        if(isset($weather['temp'])){
            $weather['temp_color'] = "#".$this->TemperatureColor($weather['temp']);
        }
        if(isset($weather['temp_max'])){
            $weather['temp_max_color'] = "#".$this->TemperatureColor($weather['temp_max']);
        }
        if(isset($weather['temp_min'])){
            $weather['temp_min_color'] = "#".$this->TemperatureColor($weather['temp_min']);
        }
        if(isset($weather['temp_max_average'])){
            $weather['temp_max_average_color'] = "#".$this->TemperatureColor($weather['temp_max_average']);
        }
        if(isset($weather['temp_min_average'])){
            $weather['temp_min_average_color'] = "#".$this->TemperatureColor($weather['temp_min_average']);
        }
        if(isset($weather['temp_max_range'])){
            $weather['temp_max_range_color'] = "#".$this->TemperatureColor($weather['temp_max_range']);
        }
        if(isset($weather['temp_min_range'])){
            $weather['temp_min_range_color'] = "#".$this->TemperatureColor($weather['temp_min_range']);
        }


        if(isset($weather['hum'])){
            $weather['hum_color'] = "#".$this->HumColor($weather['hum']);
        }
        if(isset($weather['temp_max'])){
            $weather['hum_max_color'] = "#".$this->HumColor($weather['hum_max']);
        }
        if(isset($weather['hum_min'])){
            $weather['hum_min_color'] = "#".$this->HumColor($weather['hum_min']);
        }
        if(isset($weather['max_temp'])){
            $weather['hum_max_color'] = "#".$this->HumColor($weather['max_hum']);
        }
        if(isset($weather['min_hum'])){
            $weather['hum_min_color'] = "#".$this->HumColor($weather['min_hum']);
        }
        if(isset($weather['hum_max_average'])){
            $weather['hum_max_average_color'] = "#".$this->HumColor($weather['hum_max_average']);
        }
        if(isset($weather['hum_min_average'])){
            $weather['hum_min_average_color'] = "#".$this->HumColor($weather['temp_min_average']);
        }
        if(isset($weather['hum_max_range'])){
            $weather['hum_max_range_color'] = "#".$this->HumColor($weather['hum_max_range']);
        }
        if(isset($weather['hum_min_range'])){
            $weather['hum_min_range_color'] = "#".$this->HumColor($weather['temp_min_range']);
        }


        if(isset($weather['wind_speed'])){
            $weather['wind_color'] = "#".$this->WindColor($weather['wind_speed']);
        }
        if(isset($weather['max_wind'])){
            $weather['wind_max_color'] = "#".$this->WindColor($weather['max_wind']);
        }
        if(isset($weather['min_min'])){
            $weather['wind_min_color'] = "#".$this->WindColor($weather['min_wind']);
        }
        return $weather;
    }




    function TemperatureColor($temp){
        $temp_colors = [
            Colors::GetColor('temp_0','weather','#8900d3'),
            Colors::GetColor('temp_1','weather','#8900d3'),
            Colors::GetColor('temp_2','weather','#6300e9'),
            Colors::GetColor('temp_3','weather','#4600fd'),
            Colors::GetColor('temp_4','weather','#4814f9'),
            Colors::GetColor('temp_5','weather','#4a80f4'),
            Colors::GetColor('temp_6','weather','#4cb6f2'),
            Colors::GetColor('temp_7','weather','#e1db51'),
            Colors::GetColor('temp_8','weather','#dec21d'),
            Colors::GetColor('temp_9','weather','#e5921c'),
            Colors::GetColor('temp_10','weather','#f32a1a'),
            Colors::GetColor('temp_11','weather','#aa0000')
        ];
        $min = floor((float)$temp/10);
        $max = $min+1;
        if($min < 0){
            $min = 0;
        }
        if($max >= count($temp_colors)){
            $max = count($temp_colors)-1;
        }
        $amount = ($temp - ($min*10))/10;
        //echo "{ $amount || $temp || $min || $max }";
        return ColorLerp($temp_colors[$min],$temp_colors[$max],$amount);
    }
    function TemperatureBackgroundColor($temp){
        $color = $this->TemperatureColor($temp);
        return $color."ee";
    }

    function HumColor($hum){
        $hum_colors = [
            Colors::GetColor('hum_min','weather','#b1c577'),
            Colors::GetColor('hum_max','weather','#054a7f')
        ];
        $amount = $hum / 100;
        return ColorLerp($hum_colors[0],$hum_colors[1],$amount);
    }
    function HumBackgroundColor($hum){
        $color = $this->HumColor($hum);
        return $color."ee";
    }
    function WindColor($wind){
        $wind_colors = [
            Colors::GetColor('wind_min','weather','#dcf8e7'),
            Colors::GetColor('wind_max','weather','#372402')
        ];
        $amount = $wind / 50;
        return ColorLerp($wind_colors[0],$wind_colors[1],$amount);
    }
    function WindBackgroundColor($wind){
        $color = $this->WindColor($wind);
        return $color."ee";
    }
}

?>