#!/bin/bash

#if pgrep redis-server
#        then
#                pgrep redis-server | xargs kill
#        else
#                echo "Redis server is already stopped -- Continuing"
#fi

#redis-server >> /home/steam/Steam/servers/discordbot/logs/redis-server.log &

#echo "Redis restarted"


if pgrep npm
	then
		pgrep node | xargs kill
	else
		echo "Bots are already stopped -- Continuing"
fi

cd /home/steam/Steam/servers/discordbot/ZBot-Discord && npm run bot >> /home/steam/Steam/servers/discordbot/logs/zbot.log &
cd /home/steam/Steam/servers/discordbot/Gravebot && npm start >> /home/steam/Steam/servers/discordbot/logs/gravebot.log &

echo "Bots restarted"

exit 1
