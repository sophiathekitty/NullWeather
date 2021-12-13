/**
 * "name": "forecast_hourly",
 * "item_name": "forecast",
 * "chart_name": "weather_chart",
 * "type": "hourly_chart",
 * "api":"/plugins/NullWeather/api/forecast/logs?hourly=1",
 * "cache_time":5,
 * "item_id":"hour"
 */
class ForecastChartData extends HourlyChart {
    constructor(){
        super("forecast_log","forecast","weather_chart","/plugins/NullWeather/api/forecast/logs?hourly=1");
    }
}
