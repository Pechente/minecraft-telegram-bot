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

1. Make sure [query is enabled](https://minecraft.gamepedia.com/Server.properties) in your Minecraft server settings.
2. Create your Telegram Bot, note its token for later and add it to the group or channel your bot should be posting the updates to.
3. Get the ID of the chat you just added this bot to. This can easily be done by inviting @ChannelIdBot to the same chat and removing it afterwards.
4. Clone this repository to a webserver.
5. **IMPORTANT:** Make sure the file _cache_ is writable by your web server by changing it's access rights to 755 or 777.
6. Rename _config.php.sample_ to _config.php_ and fill in the settings.
7. Every time the script is called, it will compare who's currently online to who was online the last time the cript was executed. If changes are detected, the script will dispatch a message on Telegram. It's therefore recommended to call the script in intervals using bash or a cron job / webcron. I find that an interval between 5 - 15 seconds works best.

_Notice: The shorter the interval, the quicker the messages get posted. The longer the interval, the slower the bot will post updates to your chat but it will also post more events in a single message, therefore avoiding spam when multiple users join or leave within a short time frame. Figure out what works best for you._

### Troubleshooting

If the bot doesn't work, just visit index.php manually on your webserver and check the output. Messages to chat will be mirrored and possible errors will be displayed alongside it. Make sure to reload it to see changes as there's no auto-reload feature on index.php itself.

## Credits

This script is based on [PHP Minecraft Query by xPaw](https://github.com/xPaw/PHP-Minecraft-Query) and [TelegramBotPHP by Eleirbag89](https://github.com/Eleirbag89/TelegramBotPHP).
