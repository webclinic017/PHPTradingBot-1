#/bin/bash bash

echo "daemon"
while sleep 1
do
PID=$(ps aux | grep 'daemon:ticker' | grep -v grep | awk '{print $2}')
if [[ -z $PID ]]; then
    php artisan daemon:ticker &>/dev/null &
fi

PID=$(ps aux | grep 'daemon:signals' | grep -v grep | awk '{print $2}')
if [[ -z $PID ]]; then
    php artisan daemon:signals &>/dev/null &
fi

PID=$(ps aux | grep 'daemon:orders' | grep -v grep | awk '{print $2}')
if [[ -z $PID ]]; then
    php artisan daemon:orders &>/dev/null &
fi

#    PID=$(ps aux | grep 'ssh -D 1337' | grep -v grep | awk '{print $2}')
#    if [[ -z $PID ]]; then
#        ssh -D 1337 -f -C -q -N root@149.28.135.20
#    fi

    done
