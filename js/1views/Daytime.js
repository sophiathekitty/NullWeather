class DaytimeInfoView extends View {
    constructor(){
        super(new SunriseData());
    }
    build(){
        this.display();
    }
    refresh(){
        this.display();
    }
    display(){
        if(this.debug) console.log("DaytimeInfoView::display");
        if(this.model){
            this.model.getData(json=>{
                if(this.debug) console.log("DaytimeInfoView",json);
                $(".value[var=sunrise]").html(this.nicerTimeString(json.sunrise));
                $(".value[var=sunset]").html(this.nicerTimeString(json.sunset));
                $(".value[var=time_of_day]").html(json.time_of_day);
                $(".value[var=moon_out]").attr("val",json.moon_out);
                $(".value[var=moonrise]").html(this.nicerTimeString(json.moonrise));
                $(".value[var=moonset]").html(this.nicerTimeString(json.moonset));
                var phase = "new moon";
                if(json.moon_phase < 1) phase = "waning crescent";
                if(json.moon_phase < 0.75) phase = "waning gibbous";
                if(json.moon_phase < 0.50) phase = "waxing gibbous";
                if(json.moon_phase < 0.25) phase = "waxing crescent";
                if(json.moon_phase == 0.25) phase = "first quarter";
                if(json.moon_phase == 0.50) phase = "full moon";
                if(json.moon_phase == 0.75) phase = "last quarter";
                if(json.moon_phase == 0 || json.moon_phase == 1) phase = "new moon";
                $(".value[var=moon_phase]").html(phase);
                $(".moon").attr("phase",json.moon_phase);
                $(".moon").attr("stage",phase);
                $(".moon .disc").css("transform","rotateY("+(360-(360*json.moon_phase))+"deg)");
                
            });
        }
    }
    /**
     * 
     * @param {string} min_sec MM:SS
     */
    nicerTimeString(min_sec){
        var time_array = min_sec.split(":");
        var hour = Number(time_array[0]);
        var min = Number(time_array[1]);
        var am = "am";
        if(hour == 12) am = "pm";
        if(hour == 24){
            hour = 12;
        }
        if(hour > 12){
            am = "pm";
            hour -= 12;
        }
        if(hour == 0){
            hour = 12;
        }
        return hour+":"+min+am;
    }
}