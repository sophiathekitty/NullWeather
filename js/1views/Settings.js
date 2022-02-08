/**
 * View for displaying weather settings
 */
class WeatherSettings extends View {
    constructor(){
        super(new SettingsPallet("weather","/plugins/NullWeather/api/settings/"));
    }
    build(){
        // no build only display lol
        if(this.controller){
            this.controller.addSettingsEvents();
        }
        this.display();
    }
    display(){
        if(this.model){
            this.model.getData(json=>{
                if(this.debug) console.log(json);
                
                if(json.settings.weather_api_key){
                    if(!$("#settings [key=weather_api_key]").hasClass("editing")){
                        $("#settings [var=weather_api_key]").html("{show key}");
                        $("#settings [var=weather_api_key]").attr("val",json.settings.weather_api_key);    
                    }
                }else{
                    if(!$("#settings [key=weather_api_key]").hasClass("editing")){
                        $("#settings [var=weather_api_key]").html("{add key}");
                    }
                }
                if(json.settings.weather_city){
                    if(!$("#settings [key=weather_city]").hasClass("editing")){
                        $("#settings [var=weather_city]").html(json.settings.weather_city);
                        $("#settings [var=weather_city]").attr("val",json.settings.weather_city);
                    }
                }else{
                    if(!$("#settings [key=weather_city]").hasClass("editing")){
                        $("#settings [var=weather_city]").html("{add city}");
                    }
                }
                if(json.settings.weather_log_days){
                    if(!$("#settings [key=weather_log_days]").hasClass("editing")){
                        $("#settings [var=weather_log_days]").html(json.settings.weather_log_days);
                        $("#settings [var=weather_log_days]").attr("val",json.settings.weather_log_days);
                    }
                }
                if(json.settings.weather_archive_weeks){
                    if(!$("#settings [key=weather_archive_weeks]").hasClass("editing")){
                        $("#settings [var=weather_archive_weeks]").html(json.settings.weather_archive_weeks);
                        $("#settings [var=weather_archive_weeks]").attr("val",json.settings.weather_archive_weeks);
                    }
                }
                if(json.settings.weather_pull_delay){
                    if(!$("#settings [key=weather_pull_delay]").hasClass("editing")){
                        $("#settings [var=weather_pull_delay]").html(json.settings.weather_pull_delay);
                        $("#settings [var=weather_pull_delay]").attr("val",json.settings.weather_pull_delay);
                    }
                }
                if(json.settings.weather_units){
                    if(!$("#settings [key=weather_units]").hasClass("editing")){
                        $("#settings [var=weather_units]").html(json.settings.weather_units);
                        $("#settings [var=weather_units]").attr("val",json.settings.weather_units);
                    }
                }
                if(json.settings.weather_one_call){
                    if(!$("#settings [key=weather_one_call]").hasClass("editing")){
                        $("#settings [var=weather_one_call]").attr("val",json.settings.weather_one_call);
                    }
                }
            });
        }
    }
}