/**
 * main weather injector
 */
/*
var weather = new WeatherView();
var weather_settings = new WeatherSettings();
var weather_section = new WeatherSection();
var weather_daytime = new DaytimeInfoView();
var refreshWeather;
*/
var weather = new WeatherController();
$(document).ready(function(){
    weather.ready();
    // inject null weather stuff
    /*
    weather.build();
    weather_section.build();
    weather_settings.display();
    weather_daytime.display();
    refreshWeather = setInterval(RefreshWeatherData,100000);
    */
});
/*
function RefreshWeatherData(){
    weather.refresh();
    weather_settings.display();
    weather_daytime.display();
    weather_section.display();
}
*/