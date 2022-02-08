/**
 * a custom view for displaying the current weather stamp
 */
class WeatherView extends View {

    constructor(){
        super(new WeatherData(),new Template("weather_stamp","/plugins/NullWeather/templates/header/stamp.html"));
        this.pallet = ColorPallet.getPallet("weather");
        this.chart = new HourlyChart("weather_hourly","weather_log","weather_chart","/plugins/NullWeather/api/weather/logs?hourly=1");
    }
    build(){
        if(this.template){
            this.template.getData(html=>{
                // inject the template where it should go?
                $(html).appendTo("#stamp");
                $(html).appendTo(".app main");
                this.display();
            });
        }
    }
    display(){
        if(this.model){
            this.model.getData(json=>{
                if(this.debug) console.log(json);
                if(json.weather){
                    // populate weather data
                    $(".weather_stamp").attr("title","Clouds: "+json.weather.clouds+"%\n"+json.weather.main+"\n"+json.weather.description+"\nDust: "+Math.round(json.pollution.pm10)+"\nFine Dust: "+Math.round(json.pollution.pm2_5));
                    $(".weather_stamp").attr("icon",json.weather.icon);
                    $(".weather_stamp").attr("weather",json.weather.Main);
                    // temp
                    $(".weather_stamp [var=temp]").html(Math.round(json.weather.temp));
                    this.pallet.getColorLerp("temp",json.weather.temp,color=>{
                        $(".weather_stamp [var=temp]").css("color",color);
                    });
                    // feels like
                    $(".weather_stamp [var=feels_like]").html(Math.round(json.weather.feels_like));
                    this.pallet.getColorLerp("temp",json.weather.feels_like,color=>{
                        $(".weather_stamp [var=feels_like]").css("color",color);
                    });
                    // humidity
                    $(".weather_stamp [var=humidity]").html(Math.round(json.weather.humidity));
                    this.pallet.getColorLerp("hum",json.weather.humidity,color=>{
                        $(".weather_stamp [var=humidity]").css("color",color);
                    });
                    // wind speed
                    $(".weather_stamp [var=wind_speed]").html(Math.round(json.weather.wind_speed));
                    this.pallet.getColorLerp("wind",json.weather.wind_speed,color=>{
                        $(".weather_stamp [var=wind_speed]").css("color",color);
                    });
                    var wind_speed_to_care_about = 12;
                    $(".weather_stamp [var=feels_like]").show();
                    $(".weather_stamp [var=humidity]").hide();
                    $(".weather_stamp [var=wind_speed]").hide();
                    if(json.weather.humidity > 50){
                        $(".weather_stamp [var=feels_like]").hide();
                        $(".weather_stamp [var=humidity]").show();
                        $(".weather_stamp [var=wind_speed]").hide();
                    }
                    if(json.weather.wind_speed > wind_speed_to_care_about){
                        $(".weather_stamp [var=feels_like]").hide();
                        $(".weather_stamp [var=humidity]").hide();
                        $(".weather_stamp [var=wind_speed]").show();
                    }
                    var rain_expected = 0;
                    var snow_expected = 0;
                    var wind_expected = 0;
                    var smoke_expected = 0;
                    var extreme_expected = 0;
                    var clouds_expected = 0;
                    json.forecast.forEach(forecast =>{
                        if(forecast.main == "Rain" || forecast.description == "rain") rain_expected++;
                        if(forecast.main == "Snow" || forecast.description == "snow") snow_expected++;
                        if(forecast.main == "Smoke" || forecast.description == "smoke") smoke_expected++;
                        if(forecast.description == "extreme") extreme_expected++;
                        if(forecast.main == "Clouds") clouds_expected++;
                        if(forecast.wind_speed > wind_speed_to_care_about) wind_expected++;
                    });
            
                    $(".weather_stamp [var=rain]").attr("expected",rain_expected);
                    $(".weather_stamp [var=snow]").attr("expected",snow_expected);
                    $(".weather_stamp [var=smoke]").attr("expected",smoke_expected);
                    $(".weather_stamp [var=extreme]").attr("expected",extreme_expected);
                    $(".weather_stamp [var=wind]").attr("expected",wind_expected);
                    $(".weather_stamp [var=clouds]").attr("expected",clouds_expected);
                }
            });
        }
        if(this.chart){
            // color the weather pixel chart
            this.chart.getData(json=>{
                json.weather_log.forEach(hour=>{
                    //console.log(hour);
                    this.pallet.getColorLerp("temp",hour.temp,color=>{
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
                        $(".weather_stamp .temp_chart [hour="+hour.hour+"]").css("background-color",color);
                        $(".weather_stamp .temp_chart [hour="+hour.hour+"]").attr("title","Outdoors -- "+hours+am+"\nTemp: "+Math.round(hour.temp)+"° | "+Math.round(hour.temp_max)+"° / "+Math.round(hour.temp_min)+"°\nHum: "+Math.round(hour.humidity)+"% | "+Math.round(hour.humidity_max)+"% / "+Math.round(hour.humidity_min)+"%");
                    });
                });
            });
        }
    }
}