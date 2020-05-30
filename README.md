# PHPTradingBot


PHPTradingBot is a modular platform written in php using Laravel to automatically trade on popuplar cryptocurrency exchanges
Now support Bithumb! Thanks from minter community!

To start using it need Docker download here https://www.docker.com/products/docker-desktop

# Features

  - Trade Signals API 
  - Floating StopLoss/TakeProfit
  - Bithumb Exchange support


# Screenshots
#### Signals Page
![Alt text]( "Signals")
#### Order History Page
![Alt text]( "Order History")
#### System Settings Page'
![Alt text]( 'https://github.com/MNTShop/PHPTradingBot/blob/master/public/images/system_pref.png?raw=true' "System Settings")
#### Modules Page
![Alt text]( "Custom Modules")



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
