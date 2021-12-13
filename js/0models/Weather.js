/**
 * loads current weather (all in one) api
 * /plugins/NullWeather/api/current
 */
class WeatherData extends Model {
    constructor(){
        super("weather","/plugins/NullWeather/api/current","/plugins/NullWeather/api/current");
    }
}