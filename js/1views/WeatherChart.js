/**
 * view for building the weather chart. also handles the forecast 
 */
class WeatherChartView extends HourlyView{
    constructor(){
        super(new WeatherChartData(),null,new Template("weather_hour","/plugins/NullWeather/templates/items/hour.html"));
        this.forecast = new ForecastChartData();
        this.pollution = new PollutionChartData();
        this.pallet = ColorPallet.getPallet("weather");
    }
    display(){
        // do sunrise stuff
        var offset = 0;
        Settings.loadVar('sunrise_time',sunrise_time=>{
            var sunrise_date = new Date(sunrise_time*1000);
            $("#weather_chart").get(0).style.setProperty("--sunrise_start",this.DateToDayPercent(sunrise_date,offset-2));
            $("#weather_chart").get(0).style.setProperty("--sunrise",this.DateToDayPercent(sunrise_date,offset));
            $("#weather_chart").get(0).style.setProperty("--sunrise_end",this.DateToDayPercent(sunrise_date,offset+2));
        });
        Settings.loadVar('sunset_time',sunset_time=>{
            var sunset_date = new Date(sunset_time*1000);
            $("#weather_chart").get(0).style.setProperty("--sunset_start",this.DateToDayPercent(sunset_date,offset-2));
            $("#weather_chart").get(0).style.setProperty("--sunset",this.DateToDayPercent(sunset_date,offset));
            $("#weather_chart").get(0).style.setProperty("--sunset_end",this.DateToDayPercent(sunset_date,offset+2));
        });
        if(this.debug) console.log("WeatherChartView::Display",this.model,this.forecast);
        if(this.model && this.forecast){
            this.mappers = {};
            var temp_max = 0;
            var temp_min = 10000;

            var hum_max = 0;
            var hum_min = 10000;
            
            var feels_like_max = 0;
            var feels_like_min = 10000;
            
            var wind_speed_max = 0;
            var wind_speed_min = 10000;

            var wind_deg_max = 0;
            var wind_deg_min = 10000;
            
            var clouds_max = 0;
            var clouds_min = 10000;

            var pressure_max = 0;
            var pressure_min = 10000;
            this.model.getData(json=>{
                // calculate the min and max for weather
                if(json.ranges.clouds_max > clouds_max) clouds_max = json.ranges.clouds_max;
                if(json.ranges.clouds_min < clouds_min) clouds_min = json.ranges.clouds_min;

                if(json.ranges.temp_max > temp_max) temp_max = json.ranges.temp_max;
                if(json.ranges.temp_min < temp_min) temp_min = json.ranges.temp_min;

                if(json.ranges.feels_like_max > feels_like_max) feels_like_max = json.ranges.feels_like_max;
                if(json.ranges.feels_like_min < feels_like_min) feels_like_min = json.ranges.feels_like_min;

                if(json.ranges.humidity_max > hum_max) hum_max = json.ranges.humidity_max;
                if(json.ranges.humidity_min < hum_min) hum_min = json.ranges.humidity_min;
                
                if(json.ranges.pressure_max > pressure_max) pressure_max = json.ranges.pressure_max;
                if(json.ranges.pressure_min < pressure_min) pressure_min = json.ranges.pressure_min;

                if(json.ranges.wind_speed_max > wind_speed_max) wind_speed_max = json.ranges.wind_speed_max;
                if(json.ranges.wind_speed_min < wind_speed_min) wind_speed_min = hour.wind_speed_min;

                if(json.ranges.wind_deg_max > wind_deg_max) wind_deg_max = json.ranges.wind_deg_max;
                if(json.ranges.wind_deg_min < wind_deg_min) wind_deg_min = json.ranges.wind_deg_min;
            });
            this.forecast.getData(json=>{
                // calculate the min and max for forecast
                if(json.ranges.clouds_max > clouds_max) clouds_max = json.ranges.clouds_max;
                if(json.ranges.clouds_min < clouds_min) clouds_min = json.ranges.clouds_min;

                if(json.ranges.temp_max > temp_max) temp_max = json.ranges.temp_max;
                if(json.ranges.temp_min < temp_min) temp_min = json.ranges.temp_min;

                if(json.ranges.feels_like_max > feels_like_max) feels_like_max = json.ranges.feels_like_max;
                if(json.ranges.feels_like_min < feels_like_min) feels_like_min = json.ranges.feels_like_min;

                if(json.ranges.humidity_max > hum_max) hum_max = json.ranges.humidity_max;
                if(json.ranges.humidity_min < hum_min) hum_min = json.ranges.humidity_min;
                
                if(json.ranges.pressure_max > pressure_max) pressure_max = json.ranges.pressure_max;
                if(json.ranges.pressure_min < pressure_min) pressure_min = json.ranges.pressure_min;

                if(json.ranges.wind_speed_max > wind_speed_max) wind_speed_max = json.ranges.wind_speed_max;
                if(json.ranges.wind_speed_min < wind_speed_min) wind_speed_min = hour.wind_speed_min;

                if(json.ranges.wind_deg_max > wind_deg_max) wind_deg_max = json.ranges.wind_deg_max;
                if(json.ranges.wind_deg_min < wind_deg_min) wind_deg_min = json.ranges.wind_deg_min;
            });
            
            this.mappers["clouds"] = new ReMapper(1,100);
            this.mappers["temp"] = new ReMapper(-10,120);
            this.mappers["feels_like"] = new ReMapper(-10,120);
            this.mappers["humidity"] = new ReMapper(0,100);
            this.mappers["pressure"] = new ReMapper(pressure_min,pressure_max);
            this.mappers["wind_deg"] = new ReMapper(wind_deg_min,wind_deg_max);
            this.mappers["wind_speed"] = new ReMapper(0,100);
            this.mappers["dust"] = new ReMapper(0,50);
        }
        // weather charts
        if(this.model){
            this.model.getData(json=>{
                json.weather_log.forEach(hour=>{
                    $("#weather_chart [hour="+hour.hour+"] [model=weather_hourly][var=icon]").attr("icon",hour.icon);
                    // temp
                    $("#weather_chart [hour="+hour.hour+"] [model=weather_hourly][var=temp]").html(Math.round(hour.temp));
                    this.pallet.getColorLerp("temp",hour.temp,color=>{
                        $("#weather_chart [hour="+hour.hour+"] [model=weather_hourly][var=temp]").css("color",color);
                    });
                    // hum
                    $("#weather_chart [hour="+hour.hour+"] [model=weather_hourly][var=humidity]").html(Math.round(hour.humidity));
                    this.pallet.getColorLerp("hum",hour.humidity,color=>{
                        $("#weather_chart [hour="+hour.hour+"] [model=weather_hourly][var=humidity]").css("color",color);
                    });
                    // wind
                    $("#weather_chart [hour="+hour.hour+"] [model=weather_hourly][var=wind_speed]").html(Math.round(hour.wind_speed));
                    this.pallet.getColorLerp("wind",hour.temp,color=>{
                        $("#weather_chart [hour="+hour.hour+"] [model=weather_hourly][var=wind_speed]").css("color",color);
                    });
                    // cloud
                    $("#weather_chart [hour="+hour.hour+"] [model=weather_hourly][var=clouds]").html(Math.round(hour.clouds));
                    this.pallet.getColorLerp("clouds",hour.clouds,color=>{
                        $("#weather_chart [hour="+hour.hour+"] [model=weather_hourly][var=clouds]").css("color",color);
                    });
                    // bars
                    this.hour_bars(hour,"weather_hourly","Recent");
                });
            });
        }
        // forecast charts
        if(this.forecast){
            this.forecast.getData(json=>{
                json.forecast.forEach(hour=>{
                    this.hour_bars(hour,"forecast_hourly","Forecast","66");
                });
            });
        }
        // dust charts
        if(this.pollution){
            this.pollution.getData(json=>{
                json.pollution.forEach(hour=>{
                    var hours = Number(hour.hour);
                    var am = "am";
                    if(hours > 12){
                        am = "pm";
                        hours -= 12;
                    }
                    if(hours == 12){
                        am = "pm";
                    }
                    if(hours == 0){
                        hours = 12;
                    }            
                    // fine dust
                    $("#weather_chart [hour="+hour.hour+"] .bar.graph [var=pm2_5]").attr("title","Dust -- "+hours+am+"\nFine: "+Math.round(hour.pm2_5)+" | "+Math.round(hour.pm2_5_max)+" / "+Math.round(hour.pm2_5_min)+"\nCoarse: "+Math.round(hour.pm10)+" | "+Math.round(hour.pm10_max)+" / "+Math.round(hour.pm10_min));
                    $("#weather_chart [hour="+hour.hour+"] .bar.graph [var=pm2_5]").css("top",this.mappers.dust.max_mapper(hour.pm2_5_max)+"%");
                    $("#weather_chart [hour="+hour.hour+"] .bar.graph [var=pm2_5]").css("bottom",this.mappers.dust.min_mapper(hour.pm2_5_min)+"%");
                    // coarse dust
                    $("#weather_chart [hour="+hour.hour+"] .bar.graph [var=pm10]").attr("title","Dust -- "+hours+am+"\nFine: "+Math.round(hour.pm2_5)+" | "+Math.round(hour.pm2_5_max)+" / "+Math.round(hour.pm2_5_min)+"\nCoarse: "+Math.round(hour.pm10)+" | "+Math.round(hour.pm10_max)+" / "+Math.round(hour.pm10_min));
                    $("#weather_chart [hour="+hour.hour+"] .bar.graph [var=pm10]").css("top",this.mappers.dust.max_mapper(hour.pm10_max)+"%");
                    $("#weather_chart [hour="+hour.hour+"] .bar.graph [var=pm10]").css("bottom",this.mappers.dust.min_mapper(hour.pm10_min)+"%");
                    
                    $("#weather_chart [hour="+hour.hour+"] [var=dust]").html(Math.round((hour.pm2_5+hour.pm10)/2));
                });
            });
        }
    }
    hour_bars(hour,model,title,alpha = ""){
        var mdl = ""
        if(model) mdl = "[model="+model+"]";
        $("#weather_chart [hour="+hour.hour+"] .bar.graph [var=temp]"+mdl).html("");
        $("#weather_chart [hour="+hour.hour+"] .bar.graph [var=humidity]"+mdl).html("");
        $("#weather_chart [hour="+hour.hour+"] .bar.graph [var=wind_speed]"+mdl).html("");
        $("#weather_chart [hour="+hour.hour+"] .bar.graph [var=clouds]"+mdl).html("");
        var hours = Number(hour.hour);
        var am = "am";
        if(hours > 12){
            am = "pm";
            hours -= 12;
        }
        if(hours == 12){
            am = "pm";
        }
        if(hours == 0){
            hours = 12;
        }
        // temp
        $("#weather_chart [hour="+hour.hour+"] .bar.graph [var=temp]"+mdl).attr("title",title+" -- "+hours+am+"\nTemp: "+Math.round(hour.temp)+"° | "+Math.round(hour.temp_max)+"° / "+Math.round(hour.temp_min)+"°");
        $("#weather_chart [hour="+hour.hour+"] .bar.graph [var=temp]"+mdl).css("top",this.mappers.temp.max_mapper(hour.temp_max)+"%");
        $("#weather_chart [hour="+hour.hour+"] .bar.graph [var=temp]"+mdl).css("bottom",this.mappers.temp.min_mapper(hour.temp_min)+"%");
        this.pallet.getColorLerp("temp",hour.temp,color=>{
            $("#weather_chart [hour="+hour.hour+"] .bar.graph [var=temp]"+mdl).css("background-color",color);
        },alpha);
        // hum
        $("#weather_chart [hour="+hour.hour+"] .bar.graph [var=hum]"+mdl).attr("title",title+" -- "+hours+am+"\nHumidity: "+Math.round(hour.humidity)+"% | "+Math.round(hour.humidity_max)+"% / "+Math.round(hour.humidity_min)+"%");
        $("#weather_chart [hour="+hour.hour+"] .bar.graph [var=hum]"+mdl).css("top",this.mappers.humidity.max_mapper(hour.humidity_max)+"%");
        $("#weather_chart [hour="+hour.hour+"] .bar.graph [var=hum]"+mdl).css("bottom",this.mappers.humidity.min_mapper(hour.humidity_min)+"%");
        this.pallet.getColorLerp("hum",hour.humidity,color=>{
            $("#weather_chart [hour="+hour.hour+"] .bar.graph [var=hum]"+mdl).css("background-color",color);
        },alpha);
        // wind
        $("#weather_chart [hour="+hour.hour+"] .bar.graph [var=wind_speed]"+mdl).attr("title",title+" -- "+hours+am+"\nWind: "+Math.round(hour.wind_speed)+"mph | "+Math.round(hour.wind_speed_max)+"mph / "+Math.round(hour.wind_speed_min)+"mph");
        $("#weather_chart [hour="+hour.hour+"] .bar.graph [var=wind_speed]"+mdl).css("top",this.mappers.wind_speed.max_mapper(hour.wind_speed_max)+"%");
        $("#weather_chart [hour="+hour.hour+"] .bar.graph [var=wind_speed]"+mdl).css("bottom",this.mappers.wind_speed.min_mapper(hour.wind_speed_min)+"%");
        this.pallet.getColorLerp("wind",hour.wind_speed,color=>{
            $("#weather_chart [hour="+hour.hour+"] .bar.graph [var=wind_speed]"+mdl).css("background-color",color);
        },alpha);
        // clouds
        $("#weather_chart [hour="+hour.hour+"] .bar.graph [var=clouds]"+mdl).attr("title",title+" -- "+hours+am+"\nClouds: "+Math.round(hour.clouds)+"% | "+Math.round(hour.clouds_max)+"% / "+Math.round(hour.clouds_min)+"%");
        $("#weather_chart [hour="+hour.hour+"] .bar.graph [var=clouds]"+mdl).css("top",this.mappers.clouds.max_mapper(hour.clouds_max)+"%");
        $("#weather_chart [hour="+hour.hour+"] .bar.graph [var=clouds]"+mdl).css("bottom",this.mappers.clouds.min_mapper(hour.clouds_min)+"%");
        this.pallet.getColorLerp("clouds",hour.humidity,color=>{
            $("#weather_chart [hour="+hour.hour+"] .bar.graph [var=clouds]"+mdl).css("background-color",color);
        },alpha);
    }
}