/**
 * "name": "weather_hourly",
 * "item_name": "weather_log",
 * "chart_name": "weather_chart",
 * "type": "hourly_chart",
 * "api":"/plugins/NullWeather/api/weather/logs?hourly=1",
 * "cache_time":5,
 * "item_id":"hour"
 */
class WeatherChartData extends HourlyChart {
    constructor(){
        super("weather_log","weather_log","weather_chart","/plugins/NullWeather/api/weather/logs?hourly=1");
    }
}