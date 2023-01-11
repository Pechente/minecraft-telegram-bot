# Minecraft Server Telegram Notification Bot

This bot notifies a group chat, channel or private chat in Telegram of players joining or leaving your Minecraft server.

![Minecraft Telegram Bot](https://i.imgur.com/uYDPugw.png)

The goal of this script is to incentivise players to join their friends on your server without having to open up a website or the game itself. The script can easily be run on any webserver and currently supports both English and German language output.

## Features

1. **Easily deployable script that will work on any webserver.**
2. **Multiple events within a short time span will be merged into a single message.**
3. **The script works with any Minecraft server, whether it's spigot or the plain Java server without the need to install any additional plugins.**
4. **Support for multiple languages (currently English, German, Russian and Polish)**

## How to set up this script?

### Requirements

- PHP with curl and mbstring

### Enable query property on server.properties

Make sure that the [query property](https://minecraft.gamepedia.com/Server.properties) is enabled in your Minecraft server settings.

### Create Telegram Bot with BotFather

Create your Telegram Bot, save its token for later and add it to the group, super-group or channel your bot should be posting the updates to.

### Get ID of the (super-)group or channel 

**⚠️ Info:** _I can’t really recommend the add-some-channel-id-info-bot-way, because they didn’t work properly for me and were just broken._

Login to https://web.telegram.com and open up the wanted channel or group. The id is part of the website url which is a bit different for each of the different kinds of chat types (group, channel or super group). 

**[For Channels →](https://gist.github.com/mraaroncruz/e76d19f7d61d59419002db54030ebe35)**

**For Groups:**

Same as for channels, but way simpler. The id (in the browser url) is the last part of the url and starts also with a dash. For example:

Browser Url: `https://web.telegram.org/z/#-9693613342`
Your Group Id would be: `-9693613342`

**For Super-Groups:**

*The group id from the url didn’t work as expected for me. I’ve found out that I was using a so called super-group (all users can write and stuff) and its id wasn’t working. But the telegram api suggested me the right id after I called this php script.*

### Clone this repo and make `cache`-file writable

```shell
git clone https://github.com/Coderwelsch/minecraft-telegram-bot.git
```

Then change the permissions of the cache file in this repo like:

```
chmod 755 cache
```

### Create your config.php

The config.php holds your telegram and minecraft server configs. Copy the file from the template:

```shell
cp config.php.sample config.php
```

### Create Crontab / Execute the script

To let run the script every few seconds you can add a script to `crontab`. I did it like this, which is possibly not the best solution (because of the `cd` command):

1. Run `crontab -e`
1. Add a new entry like:
`* * * * * cd /YOUR_REPO_LOCATION/minecraft-telegram-bot; php index.php`

Every time the script is called, it will compare who’s currently online to who was online the last time the cript was executed. If changes are detected, the script will dispatch a message on Telegram. It's therefore recommended to call the script in intervals using bash or a cron job / webcron. I find that an interval between 5 - 15 seconds works best.

_Notice: The shorter the interval, the quicker the messages get posted. The longer the interval, the slower the bot will post updates to your chat but it will also post more events in a single message, therefore avoiding spam when multiple users join or leave within a short time frame. Figure out what works best for you._

### Troubleshooting

If the bot doesn't work, just visit index.php manually on your webserver and check the output. Messages to chat will be mirrored and possible errors will be displayed alongside it. Make sure to reload it to see changes as there's no auto-reload feature on index.php itself.

## Credits

This script is based on [Pechente/minecraft-telegram-bot](https://github.com/Pechente/minecraft-telegram-bot) and was forked by me because of the author’s afk-ness.
