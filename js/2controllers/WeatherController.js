/**
 * the controller that should handle the buttons for the weather section
 * it's also handling refreshing weather views controlled by this controller
 * - WeatherSection
 * - WeatherView
 * - WeatherSettings
 * - DaytimeInfoView
 */
class WeatherController extends Controller {
    constructor(debug = false){
        if(debug) console.log("WeatherController::Constructor");
        super(new WeatherSection(),debug);
        this.weather = new WeatherView();
        this.settings = new  WeatherSettings();
        this.daytime = new DaytimeInfoView();
        // make sure child views have a reference back to this controller
        //this.view.controller = this;
        this.settings.controller = this;
        //console.log("WeatherController::Constructor",this.settings.controller);
        this.first_ready = true;
    }
    ready(){
        if(this.first_ready){
            if(this.debug) console.log("WeatherController::Ready");
            this.view.build();
            this.weather.build();
            this.settings.build();
            this.daytime.display();
            this.interval = setTimeout(this.refresh.bind(this),this.view.refresh_rate*View.refresh_ratio);
            this.first_ready = false;
        }
    }
    addSectionEvents(){
        if(this.debug) console.log("WeatherController::AddSectionEvents");
        this.click("section#weather .filters a",e=>{
            // click filter options
            e.preventDefault();
            if(this.debug) console.log("WeatherController::FiltersClicked",$(e.currentTarget).attr("filter"));
            $("section#weather").attr("show",$(e.currentTarget).attr("filter"));
        });
    }
    addSettingsEvents(){
        if(this.debug) console.log("WeatherController::AddSettingsEvents");
        this.click("section#settings[plugin=NullWeather] li",e=>{
            e.preventDefault();
            var key = $(e.currentTarget).attr("key");
            var val = $("[plugin=NullWeather] [var="+$(e.currentTarget).attr("key")+"]").attr("val");
            if(this.debug) console.log("WeatherController::SettingsClicked",key,val);
            if($(e.currentTarget).hasClass("editing")){
                // save?
            } else {
                if(key == "weather_api_key"){
                    $("[plugin=NullWeather] [var="+key+"]").html("<input name=\""+key+"\" type=\"text\" value=\""+val+"\" />");
                    $("[plugin=NullWeather] [var="+key+"] input").focus();
                    $(e.currentTarget).addClass("editing");
                } else if($("[plugin=NullWeather] [var="+key+"]").hasClass("bool")){
                    if(this.debug) console.log("is a bool");
                    var new_val = 1;
                    if(Number(val) == 1) new_val = 0;
                    Settings.saveVar(key,new_val,e=>{
                        if(this.debug) console.log("WeatherController::Settings - Save Complete",key,e);
                        //$("[plugin=NullWeather] [var="+key+"]").html(new_val);
                        $("[plugin=NullWeather] [var="+key+"]").attr("val",new_val);
                    },e=>{
                        if(this.debug) console.log("WeatherController::Settings - Save Error",key,e);
                    },e=>{
                        if(this.debug) console.log("WeatherController::Settings - Save Failed",key,e);
                    });

                } else if($("[plugin=NullWeather] [var="+key+"]").attr("options")){
                    var options = $("[plugin=NullWeather] [var="+key+"]").attr("options").split(",");
                    if(this.debug) console.log("has options",options[0]);
                    var new_val = options[0];
                    if(val == options[0]) {
                        new_val = options[1];
                    }
                    Settings.saveVar(key,new_val,e=>{
                        if(this.debug) console.log("WeatherController::Settings - Save Complete",key,e);
                        $("[plugin=NullWeather] [var="+key+"]").html(new_val);
                        $("[plugin=NullWeather] [var="+key+"]").attr("val",new_val);
                    },e=>{
                        $("[plugin=NullWeather] [var="+key+"]").html("error");
                        if(this.debug) console.log("WeatherController::Settings - Save Error",key,e);
                    },e=>{
                        $("[plugin=NullWeather] [var="+key+"]").html("failed");
                        if(this.debug) console.log("WeatherController::Settings - Save Failed",key,e);
                    });
        
                } else if(key == "weather_city"){
                    $("[plugin=NullWeather] [var="+key+"]").html("<input name=\""+key+"\" type=\"text\" value=\""+val+"\" />");
                    //$("[plugin=NullWeather] [var="+key+"] input").val(val);
                    $("[plugin=NullWeather] [var="+key+"] input").focus();
                    $(e.currentTarget).addClass("editing");
                } else {
                    $("[plugin=NullWeather] [var="+key+"]").html("<input name=\""+key+"\" type=\"number\" value=\""+val+"\" step=\"1\" />");
                    //$("[plugin=NullWeather] [var="+key+"] input").val(val);
                    $("[plugin=NullWeather] [var="+key+"] input").focus();
                    $(e.currentTarget).addClass("editing");
                }
            }
        });
        this.listenForEvent("focusout", "[plugin=NullWeather]", "input", e=>{
            var key = $(e.currentTarget).attr("name");
            var val = $(e.currentTarget).val();
            var val_old = $("[plugin=NullWeather] [var="+key+"]").attr("val");
            if(this.debug) console.log("WeatherController::SettingsUnfocused",key,val,val_old);
            $("[plugin=NullWeather] [key="+key+"]").removeClass("editing");
            Settings.saveVar(key,val,e=>{
                console.log("WeatherController::Settings - Save Complete",key,e);
                if(key == "weather_api_key"){
                    $("[plugin=NullWeather] [var="+key+"]").html("{show key}");
                } else {
                    $("[plugin=NullWeather] [var="+key+"]").html(val);
                }
                $("[plugin=NullWeather] [var="+key+"]").attr("val",val);
            },e=>{
                $("[plugin=NullWeather] [var="+key+"]").html("[error]");
                if(this.debug) console.log("WeatherController::Settings - Save Error",key,e);
            },e=>{
                $("[plugin=NullWeather] [var="+key+"]").html("[failed]");
                if(this.debug) console.log("WeatherController::Settings - Save Failed",key,e);
            });
        });
    }
    refresh(){
        clearTimeout(this.interval);
        this.view.refresh();
        this.weather.display();
        this.settings.display();
        this.daytime.display();
        this.interval = setTimeout(this.refresh.bind(this),this.view.refresh_rate*View.refresh_ratio);
        View.refresh_ratio += 0.01;
    }
}