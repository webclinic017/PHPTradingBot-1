# PHPTradingBot

__Warning! Software under Active Development, use it your own risk!__

PHPTradingBot is a modular platform written in php using Laravel to automatically trade on popuplar cryptocurrency exchanges
Now support Bithumb! Thanks from minter community!

To start using it need Docker download here https://www.docker.com/products/docker-desktop

# Features

  - Trade Signals API 
  - Floating StopLoss/TakeProfit (Trailing)
  - Bithumb Exchange support
  - Training mode support
  - BIP-USDT


# Screenshots

#### Positions Page (Market Terminal)
![Alt text]( public/images/Market_Terminal.png?raw=true "Market Terminal")
#### Order History Page
![Alt text](public/images/History_page.png?raw=true "Order History")
#### System Settings Page
![Alt text]( public/images/system_pref.png?raw=true "System Settings")
#### Modules Page
![Alt text]( public/images/Moduls_page.png?raw=true "Custom Modules")
#### Favorits feature
![Alt text]( public/images/favorits_feature.png?raw=true " Favorits")
#### Favorits feature
![Alt text]( public/images/favorits_feature.png?raw=true " Favorits")


### Installation

Enter these commands to install 

```sh
 git clone https://github.com/MNTShop/PHPTradingBot.git
 cd PHPTradingBot/laradock
 cp env-example .env
 docker-compose up -d nginx mysql redis
 
 docker-compose exec --user=laradock workspace bash
 cp .env.example .env
 php artisan key:generate
 php artisan migrate
```

Take a rest first install may get long time, but in future you need only this command :
```
 cd PHPTradingBot/laradock
 docker-compose up -d nginx mysql redis

```
and deploy not be a long.

Verify the deployment by navigating to your server address in your preferred browser.

```sh
http://localhost/
```

```
Login:admin@admin.com
Password : superSecret
```

If you have any trouble with install, feel free ask here https://t.me/mntshop_official_group .

### Todos

 - More exchanges support
 - More Trade Signal provider support

License
----

MIT


**Free Software, Hell Yeah!**
