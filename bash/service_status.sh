#/bin/bash bash

if [ $1 = "ticker" ]; then
    PID=$(ps aux | grep 'daemon:ticker' | grep -v grep | awk '{print $2}')
        if [[ -z $PID ]]; then
            echo "0"
            else
            echo "1"
        fi
fi
if [ $1 = "signals" ]; then
    PID=$(ps aux | grep 'daemon:signals' | grep -v grep | awk '{print $2}')
        if [[ -z $PID ]]; then
            echo "0"
            else
            echo "1"
        fi
fi
if [ $1 = "orders" ]; then
    PID=$(ps aux | grep 'daemon:orders' | grep -v grep | awk '{print $2}')
        if [[ -z $PID ]]; then
            echo "0"
            else
            echo "1"
        fi
fi
