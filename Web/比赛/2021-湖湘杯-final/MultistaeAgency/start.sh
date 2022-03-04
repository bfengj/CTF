echo `cat /proc/sys/kernel/random/uuid  | md5sum |cut -c 1-9` > /tmp/secret/key
su - web -c "/code/bin/web 2>&1  >/code/logs/web.log &"
su - web -c "/code/bin/proxy 2>&1  >/code/logs/proxy.log &"

/code/bin/server 2>&1  >/code/logs/server.log &

tail -f /code/logs/*