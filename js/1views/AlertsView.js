class WeatherAlertsView extends View {
    constructor(){
        super(
            new WeatherAlertsCollection(),
            new Template("weather_alerts","/plugins/NullWeather/templates/elements/alerts.html"),
            new Template("weather_alerts","/plugins/NullWeather/templates/items/alerts.html")
            );
    }
    build(){
        if(this.template){
            this.template.getData(html=>{
                $(html).appendTo("header");
                this.display();
            });
        }
    }
    display(){
        if(this.model && this.item_template){
            this.item_template.getData(html=>{
                this.model.getData(json=>{
                    $("header .alerts").html("");
                    json.alerts.forEach(weather_alert=>{
                        $(html).appendTo("header .alerts").attr("alert_id",weather_alert.id);
                        $("[alert_id="+weather_alert.id+"]").html(weather_alert.event);
                        $("[alert_id="+weather_alert.id+"]").attr("title",weather_alert.description);
                    })
                })
            });
        }
    }
    refresh(){

    }
}