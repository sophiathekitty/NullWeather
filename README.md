# NullWeather

This is a plugin for [NullHub](https://github.com/sophiathekitty/NullHub) that handles weather and forecast data. It uses [Open Weather Map](https://openweathermap.org/) for pulling live weather and forecast data. It also handles syncing weather and forecast data from the main NullHub to other Null devices.

***notice*** This plugin is still under heavy construction. I have a lot of the features working in the previous version of the null home project. but i still need to verify that all the backend stuff is working. and i'm still figuring out how i'm going to get plugins dynamically included in the new Null Hub app interface. i think i'm going to try to get a nice index page for the plugin that will show the weather and forecast data. and hopefully do so in a way that will make it possible to dynamically inject into the Hub app interface... i'm still using the legacy hub while the new NullHub isn't feature complete enough to replace it... so mostly i've been using this plugin to sync weather data from the legacy hub to devices that display the data. but hopefully by the next commit i should have validated that all the syncing is working and also all the OpenWeatherMap APIs are working. but i probably won't verify that it can actually sync using the new null weather apis until i'm using the new null hub as my main hub.

## Supported Open Weather Map APIs

all the supported APIs are available with the free plan from Open Weather Map. i'm currently in the process of replacing the 5 Day every 3 hours forecast with the one call api.

* [Current Weather](https://openweathermap.org/current) - pulled once a minute on main hub
* [Air Pollution](https://openweathermap.org/api/air-pollution) - pulled once an hour on main hub
* [5 Day / 3 Hour Forecast](https://openweathermap.org/forecast5) - pulled once an hour on main hub (legacy)
* [One Call API](https://openweathermap.org/api/one-call-api) - pulled once an hour (contains minutely, hourly, daily forecasts. will replace other Forecast api)

## Setup

this setup is for production devices. the idea is to have it pull updates from this repo so all the null devices on the network can stay up to date with hopefully stable code. however some changes may still require some manual fixes for the database.

### clone repo

```bash
cd /var/www/html/plugins
```

```bash
git clone https://github.com/sophiathekitty/NullWeather.git
```

### setup cron job

```bash
sudo crontab -e
```

```Apache config
3 * * * * sh /var/www/html/plugins/NullWeather/gitpull.sh
```

### adding an open weather map api key

i'm planning on adding this to the plugin's index page. which can be found by clicking the "view" link on the Null Hub landing page. until then you'll need to add it manually to the Settings table using phpMyAdmin. the setting's name should be ```weather_api_key```

### additional inclusions

* weather icons made with icons from: [Game-icons.net](https://game-icons.net/)
* moon phase css based on: [CSS moon phases](https://codepen.io/shamir/pen/YGbbNX)
