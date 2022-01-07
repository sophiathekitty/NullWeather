/**
 * this view handles building the main weather section view
 * it includes the views for the weather chart and the hourly and daily forecasts
 */
class WeatherSection extends View {
    /**
     * make this view a WeatherSection... lol i dunno
     * sets up this View to use the weather section template
     * and adds hourly, daily, and weather chart views
     */
    constructor(){
        super(null,new Template("weather_section","/plugins/NullWeather/templates/sections/weather.html"));
        this.hourly = new ForecastView();
        this.daily = new ForecastDailyView();
        this.chart = new WeatherChartView();
    }
    /**
     * inject the weather section
     */
    build(){
        if(this.template){
            this.template.getData(html=>{
                $(html).appendTo("div.contents");
                $(html).appendTo(".app main");
                $("<a href=\"#rooms\" section=\"rooms\">rooms</a>").appendTo("nav.sections");
                $("<a href=\"#weather\" section=\"weather\">weather</a>").appendTo("nav.sections");
                this.hourly.build();
                this.daily.build();
                this.chart.build();
                if(this.controller){
                    this.controller.addSectionEvents();
                }
            });
        }
    }
    display(){
        this.hourly.display();
        this.daily.display();
        this.chart.display();
    }
    refresh(){
        this.display();
    }
}