/**
 * pollution chart data?
 */
class PollutionChartData extends HourlyChart {
    constructor(){
        super("pollution","pollution","weather_chart","/plugins/NullWeather/api/pollution/logs?hourly=1");
    }
}