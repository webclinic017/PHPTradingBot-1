# PHPTradingBot


PHPTradingBot is a modular platform written in php using Laravel to automatically trade on popuplar cryptocurrency exchanges
Now support Bithumb! Thanks from minter community!

To start using it need Docker download here https://www.docker.com/products/docker-desktop

# Features

  - Trade Signals API 
  - Floating StopLoss/TakeProfit
  - Bithumb Exchange support


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
 docker-compose up -d nginx mysql redis

```


Verify the deployment by navigating to your server address in your preferred browser.

```sh
http://localhost/
```
Login:admin@admin.com
Password : superSecret
### Todos

 - More exchanges support
 - More Trade Signal provider support

License
----

MIT


**Free Software, Hell Yeah!**
