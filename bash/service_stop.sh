#/bin/bash bash

if [ $1 = "ticker" ]; then
    kill $(ps aux | grep 'daemon:ticker' | grep -v grep | awk '{print $2}')
fi

if [ $1 = "signals" ]; then
PID=$(ps aux | grep 'daemon:signals' | grep -v grep | awk '{print $2}')
echo $PID
if [[ -z $PID ]]; then
        echo "already stoped"
        else
        kill $PID
        echo "stop"
    fi
fi

if [ $1 = "orders" ]; then
    kill $(ps aux | grep 'daemon:orders' | grep -v grep | awk '{print $2}')
fi


echo " stopped"

