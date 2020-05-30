#/bin/bash bash

if [ $1 = "ticker" ]; then
    PID=$(ps aux | grep 'daemon:ticker' | grep -v grep | awk '{print $2}')
        if [[ -z $PID ]]; then
            php artisan daemon:ticker &>/dev/null &
        fi
fi
if [ $1 = "signals" ]; then
    PID=$(ps aux | grep 'daemon:signals' | grep -v grep | awk '{print $2}')
        if [[ -z $PID ]]; then
            php artisan daemon:signals &>/dev/null &
        fi
fi
if [ $1 = "orders" ]; then
    PID=$(ps aux | grep 'daemon:orders' | grep -v grep | awk '{print $2}')
        if [[ -z $PID ]]; then
            php artisan daemon:orders &>/dev/null &
        fi
fi
if [ $1 = "waller" ]; then
    PID=$(ps aux | grep 'daemon:waller' | grep -v grep | awk '{print $2}')
        if [[ -z $PID ]]; then
            php artisan daemon:waller &>/dev/null &
        fi
fi
