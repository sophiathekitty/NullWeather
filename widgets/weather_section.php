<?php 
require_once("../../../includes/main.php");
$forecast_hourly = ForecastStamp(Forecast::LoadForecast());
$forecast_daily = ForecastStamp(ForecastDaily::LoadForecast());
$weather_chart = WeatherChart::Weather();
//$weather_ranges = HourlyChart::Ranges($data['weather_log'],new WeatherLogs());
$forecast_chart = ForecastChart::ForecastAverages();
//$forecast_ranges = HourlyChart::Ranges($data['forecast'],new Forecast());
$pollution_chart = PollutionChart::PollutionHourlyChart();
?>
<section id="weather" show="temp" focus="outdoors" class="main">
    <nav class="filters" chart="weather_chart">
        <a href="#" filter="temp">Temperature</a>
        <a href="#" filter="humidity">Humidity</a>
        <a href="#" filter="wind">Wind</a>
        <a href="#" filter="clouds">Clouds</a>
        <a href="#" filter="dust">Dust</a>
    </nav>
    <nav class="toggle_temperature focus" chart="weather_chart">
        <a href="#" focus="indoors">Indoors</a>
        <a href="#" focus="outdoors">Outdoors</a>
    </nav>
    <div class="forecast_holder">
        <div id="forecast" collection="forecast">
            <?php foreach($forecast_hourly as $forecast) { ?><div class="forecast" datetime="<?=$forecast['datetime'];?>" icon="<?=$forecast['icon'];?>" day="<?=$forecast['day'];?>">
                <div class="temp extra" var="temp" pallet_name="weather" pallet_color="temp" pallet_lerp="true" unit="fahrenheit"><?=$forecast['temp'];?></div>
                <div class="humidity extra" var="hum" pallet_name="weather" pallet_color="hum" pallet_lerp="true" unit="percent"><?=$forecast['hum'];?></div>
                <div class="wind extra" var="wind_speed" pallet_name="weather" pallet_color="wind" pallet_lerp="true" unit="MilesPerHour"><?=$forecast['wind_speed'];?></div>
                <div class="clouds extra" var="clouds" pallet_name="weather" pallet_color="clouds" unit="percent"><?=$forecast['clouds'];?></div>
                <div class="time" var="time"><?=$forecast['time'];?></div>
            </div><?php }?>
        </div>
    </div>
    <div class="forecast_holder">
        <div id="forecast_daily" collection="forecast_daily">
            <?php foreach($forecast_daily as $forecast) { ?><div class="forecast_daily" datetime="<?=$forecast['datetime'];?>" icon="<?=$forecast['icon'];?>" day="<?=$forecast['day'];?>">
                <div class="temp extra" var="temp_max" pallet_name="weather" pallet_color="temp" pallet_lerp="true" unit="fahrenheit"><?=$forecast['temp_max'];?></div>
                <div class="temp extra" var="temp_min" pallet_name="weather" pallet_color="temp" pallet_lerp="true"  unit="fahrenheit"><?=$forecast['temp_min'];?></div>
                <div class="humidity extra" var="hum" pallet_name="weather" pallet_color="hum" pallet_lerp="true" unit="percent"><?=$forecast['hum'];?></div>
                <div class="wind extra" var="wind_speed" pallet_name="weather" pallet_color="wind" pallet_lerp="true" unit="MilesPerHour"><?=$forecast['wind_speed'];?></div>
                <div class="clouds extra" var="clouds" pallet_name="weather" pallet_color="clouds" unit="percent"><?=$forecast['clouds'];?></div>
                <div class="description" var="description"></div>
                <div class="day" var="day"><?=$forecast['day'];?></div>
            </div><?php } ?>
        </div>
    </div>
    <div class="weather_chart_holder">
        <div id="weather_chart" class="weather_chart">
            <div class="time_bar"></div>
            <?php $i = 0; foreach($weather_chart as $hour) { ?><div class="hour" hour="<?=$hour['hour'];?>" hour_txt="<?=Times24ToTime12Short($hour['hour'])?>">
                <div class="icon bool" model="weather_hourly" var="icon" icon="<?=$hour['icon'];?>"></div>
                <div class="bar graph">
                    <div class="temp forecast" model="forecast_hourly" var="temp" var_min="temp_min" var_max="temp_max" pallet_name="weather" pallet_color="temp" pallet_lerp="true" style="top:<?=map(-10,120,100,0,$hour['temp_max']+1);?>%; bottom:<?=map(120,-10,100,0,$hour['temp_min']-1)?>%; background-color:<?=interpolateColor(Colors::GetColor("temp_".floor($hour['temp']/10)),Colors::GetColor("temp_".ceil($hour['temp']/10)),($hour['temp']/10)-floor($hour['temp']/10));?>" title="Recent -- <?=Times24ToTime12Short($hour['hour']);?>\nTemp: <?=round($hour['temp']);?>° | <?=round($hour['temp_max']);?>° / <?=round($hour['temp_min']);?>°"></div>
                    <div class="temp weather" model="weather_hourly" var="temp" var_min="temp_min" var_max="temp_max" pallet_name="weather" pallet_color="temp" pallet_lerp="true" style="top:<?=map(-10,120,100,0,$forecast_chart[$i]['temp_max']+1);?>%; bottom:<?=map(120,-10,100,0,$forecast_chart[$i]['temp_min']-1)?>%; background-color:<?=interpolateColor(Colors::GetColor("temp_".floor($forecast_chart[$i]['temp']/10)),Colors::GetColor("temp_".ceil($forecast_chart[$i]['temp']/10)),($forecast_chart[$i]['temp']/10)-floor($forecast_chart[$i]['temp']/10));?>" title="Forecast -- <?=Times24ToTime12Short($hour['hour']);?>\nTemp: <?=round($forecast_chart[$i]['temp']);?>° | <?=round($forecast_chart[$i]['temp_max']);?>° / <?=round($forecast_chart[$i]['temp_min']);?>°"></div>
                    <div class="humidity" model="weather_hourly" var="hum" var_min="min_hum" var_max="max_hum" pallet_name="weather" pallet_color="hum" pallet_lerp="true" style="top:<?=map(0,100,100,0,$hour['hum_max']+1);?>%; bottom:<?=map(100,0,100,0,$hour['hum_min']-1)?>%; background-color:<?=interpolateColor(Colors::GetColor("hum_0"),Colors::GetColor("hum_1"),$hour['humidity']/100);?>" title="Recent -- <?=Times24ToTime12Short($hour['hour']);?>\nHum: <?=round($hour['humidity']);?>% | <?=round($hour['humidity_max']);?>% / <?=round($hour['humidity_min']);?>%"></div>
                    <div class="wind" model="weather_hourly" var="wind_speed" var_min="min_wind" var_max="max_wind" pallet_name="weather" pallet_color="wind" pallet_lerp="true" style="top:<?=map(0,100,100,0,$hour['wind_speed_max']+1);?>%; bottom:<?=map(100,0,100,0,$hour['wind_speed_min']-1)?>%; background-color:<?=interpolateColor(Colors::GetColor("wind_0"),Colors::GetColor("wind_1"),$hour['wind_speed']/100);?>" title="Recent -- <?=Times24ToTime12Short($hour['hour']);?>\nWind Speed: <?=round($hour['wind_speed']);?>mph | <?=round($hour['wind_speed_max']);?>mph / <?=round($hour['wind_speed_min']);?>mph"></div>
                    <div class="clouds" model="weather_hourly" var="clouds" var_min="min_clouds" var_max="max_clouds" style="top:<?=map(0,100,100,0,$hour['clouds_max']+1);?>%; bottom:<?=map(100,0,100,0,$hour['clouds_min']-1)?>%; background-color:<?=interpolateColor(Colors::GetColor("clouds_0"),Colors::GetColor("clouds_1"),$hour['clouds']/100);?>" title="Recent -- <?=Times24ToTime12Short($hour['hour']);?>\nClouds: <?=round($hour['clouds']);?>% | <?=round($hour['clouds_max']);?>% / <?=round($hour['clouds_min']);?>%"></div>
                    <div class="coarse_dust" model="pollution_hourly" var="pm10" var_min="pm10_min" var_max="pm10_max" style="top:<?=map(0,100,100,0,$pollution_chart[$i]['pm10_max']+1);?>%; bottom:<?=map(100,0,100,0,$pollution_chart[$i]['pm10_min']-1)?>%;" title="Recent -- <?=Times24ToTime12Short($hour['hour']);?>\nFine: <?=round($pollution_chart[$i]['pm2_5']);?> | <?=round($pollution_chart[$i]['pm2_5_max']);?> / <?=round($pollution_chart[$i]['pm2_5_min']);?>\nCoarse: <?=round($pollution_chart[$i]['pm10']);?> | <?=round($pollution_chart[$i]['pm10_max']);?> / <?=round($pollution_chart[$i]['pm10_min']);?>"></div>
                    <div class="fine_dust" model="pollution_hourly" var="pm2_5" var_min="pm2_5_min" var_max="pm2_5_max" style="top:<?=map(0,100,100,0,$pollution_chart[$i]['pm2_5_max']+1);?>%; bottom:<?=map(100,0,100,0,$pollution_chart[$i]['pm2_5_min']-1)?>%;" title="Recent -- <?=Times24ToTime12Short($hour['hour']);?>\nFine: <?=round($pollution_chart[$i]['pm2_5']);?> | <?=round($pollution_chart[$i]['pm2_5_max']);?> / <?=round($pollution_chart[$i]['pm2_5_min']);?>\nCoarse: <?=round($pollution_chart[$i]['pm10']);?> | <?=round($pollution_chart[$i]['pm10_max']);?> / <?=round($pollution_chart[$i]['pm10_min']);?>"></div>
                </div>
                <div class="humidity extra" model="weather_hourly" var="humidity" unit="percent"><?=round($hour['humidity']);?></div>
                <div class="temperature" model="weather_hourly" var="temp" unit="fahrenheit"><?=round($hour['temp']);?></div>
                <div class="wind extra" model="weather_hourly" var="wind_speed" unit="MilesPerHour"><?=round($hour['wind_speed']);?></div>
                <div class="clouds extra" model="weather_hourly" var="clouds" unit="percent"><?=round($hour['clouds']);?></div>
                <div class="dust extra" model="pollution_hourly" var="dust" unit=""><?=round(($pollution_chart[$i]['pm2_5']+$pollution_chart[$i]['pm10'])/2);?></div>
            </div><?php $i++; } ?>
        </div>
    </div>
</section>