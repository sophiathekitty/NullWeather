/**
 * view for displaying the forecast
 */
class ForecastDailyView extends View {
    constructor(){
        super(new ForecastDailyCollection(),null,new Template("forecast_daily","/plugins/NullWeather/templates/items/daily.html"));
        this.pallet = ColorPallet.getPallet("weather");
        this.calendar_pallet = ColorPallet.getPallet("calendar");
    }
    build(){
        this.display();
    }
    display(){
        if(this.model){
            this.model.getData(json=>{
                if(this.item_template){
                    this.item_template.getData(html=>{
                        $("[collection=forecast_daily]").html("");
                        if('forecast_daily' in json){
                            json.forecast_daily.forEach((item,index)=>{
                                //console.log("ForecastView::display",index,item);
                                $(html).appendTo("[collection=forecast_daily]").attr("index",index);
                                //console.log($("[collection=forecast_daily] [index="+index+"]"));
                                $("[collection=forecast_daily] [index="+index+"]").attr("icon",item.icon);
                                $("[collection=forecast_daily] [index="+index+"]").attr("day",item.day);
                                $("[collection=forecast_daily] [index="+index+"]").attr("datetime",item.datetime);
                                // temp
                                $("[collection=forecast_daily] [index="+index+"] [var=temp_max]").html(Math.round(item.temp_max));
                                this.pallet.getColorLerp("temp",item.temp_max,color=>{
                                    $("[collection=forecast_daily] [index="+index+"] [var=temp_max]").css("color",color);
                                });
                                $("[collection=forecast_daily] [index="+index+"] [var=temp_min]").html(Math.round(item.temp_min));
                                this.pallet.getColorLerp("temp",item.temp_min,color=>{
                                    $("[collection=forecast_daily] [index="+index+"] [var=temp_min]").css("color",color);
                                });
                                // hum
                                $("[collection=forecast_daily] [index="+index+"] [var=hum]").html(Math.round(item.humidity));
                                this.pallet.getColorLerp("hum",item.humidity,color=>{
                                    $("[collection=forecast_daily] [index="+index+"] [var=hum]").css("color",color);
                                });
                                // wind
                                $("[collection=forecast_daily] [index="+index+"] [var=wind_speed]").html(Math.round(item.wind_speed));
                                this.pallet.getColorLerp("wind",item.wind_speed,color=>{
                                    $("[collection=forecast_daily] [index="+index+"] [var=wind_speed]").css("color",color);
                                });
                                // clouds
                                $("[collection=forecast_daily] [index="+index+"] [var=clouds]").html(Math.round(item.clouds));
                                this.pallet.getColorLerp("clouds",item.clouds,color=>{
                                    $("[collection=forecast_daily] [index="+index+"] [var=clouds]").css("color",color);
                                });
                                // description
                                $("[collection=forecast_daily] [index="+index+"] [var=description]").html(item.description);
                                // time
                                $("[collection=forecast_daily] [index="+index+"] [var=day]").html(item.day);
                                this.calendar_pallet.getColor(item.day,color=>{
                                    //console.log("DailyForecast::Display::day_color",item.day,color);
                                    $("[collection=forecast_daily] [index="+index+"] [var=day]").css("color",color);
                                });
                                //console.log(this.calendar_pallet);
                            });    
                        } else {
                            if(this.debug) console.error("DailyForecastView::display--json.daily_forecast missing",json);
                        }
                    });
                }        
            });
        }
    }
    refresh(){
        this.display();
    }
}
