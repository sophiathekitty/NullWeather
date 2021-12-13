/**
 * view for displaying the forecast
 */
class ForecastView extends View {
    constructor(){
        super(new ForecastCollection(),null,new Template("forecast_hourly","/plugins/NullWeather/templates/items/forecast.html"));
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
                        $("[collection=forecast]").html("");
                        json.forecast.forEach((item,index)=>{
                            //console.log("ForecastView::display",index,item);
                            $(html).appendTo("[collection=forecast]").attr("index",index);
                            //console.log($("[collection=forecast] [index="+index+"]"));
                            $("[collection=forecast] [index="+index+"]").attr("icon",item.icon);
                            $("[collection=forecast] [index="+index+"]").attr("day",item.day);
                            $("[collection=forecast] [index="+index+"]").attr("datetime",item.datetime);
                            // temp
                            $("[collection=forecast] [index="+index+"] [var=temp]").html(item.temp);
                            this.pallet.getColorLerp("temp",item.temp,color=>{
                                $("[collection=forecast] [index="+index+"] [var=temp]").css("color",color);
                            });
                            // hum
                            $("[collection=forecast] [index="+index+"] [var=hum]").html(item.humidity);
                            this.pallet.getColorLerp("hum",item.humidity,color=>{
                                $("[collection=forecast] [index="+index+"] [var=hum]").css("color",color);
                            });
                            // wind
                            $("[collection=forecast] [index="+index+"] [var=wind_speed]").html(item.wind_speed);
                            this.pallet.getColorLerp("wind",item.wind_speed,color=>{
                                $("[collection=forecast] [index="+index+"] [var=wind_speed]").css("color",color);
                            });
                            // clouds
                            $("[collection=forecast] [index="+index+"] [var=clouds]").html(item.clouds);
                            this.pallet.getColorLerp("clouds",item.clouds,color=>{
                                $("[collection=forecast] [index="+index+"] [var=clouds]").css("color",color);
                            });
                            // time
                            $("[collection=forecast] [index="+index+"] [var=time]").html(item.time);
                            this.calendar_pallet.getColor(item.day,color=>{
                                $("[collection=forecast] [index="+index+"] [var=time]").css("color",color);
                            });
                        });
                    });
                }        
            });
        }
    }
    refresh(){
        this.display();
    }
}