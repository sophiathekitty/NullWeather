# NullWeather

a pluging for handling the weather features of my null stuff... so i can make it easier to reuse blocks of code

## clone repo

```bash
cd \var\www\html\plugins
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
