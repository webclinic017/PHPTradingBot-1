<nav class="navbar navbar-expand-lg navbar-light navbar-laravel">
    <a class="navbar-brand mr-5" href="{{url('/')}}">{{ config('app.name', 'Laravel') }}</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        @auth
            <ul class="navbar-nav mr-auto">
                <li class="nav-item"><a class="nav-link {{ Request::is('signals*') ? 'active' : '' }}"
                                        href="{{route('signals')}}">Signals</a></li>
                <li class="nav-item"><a class="nav-link {{ Request::is('positions*') ? 'active' : '' }}"
                                        href="{{route('positions')}}">Positions</a></li>
                <li class="nav-item"><a class="nav-link {{ Request::is('history*') ? 'active' : '' }}"
                                        href="{{route('sortHistory',['sell_date','desc'])}}">History</a></li>
                @if(!empty(\App\Modules::getMenus()))
                    @foreach(\App\Modules::getMenus() as $menu)
                        <li class="nav-item"><a
                                    class="nav-link {{ Request::is('modules/page/'.$menu['route'].'*') ? 'active' : '' }}"
                                    href="{{route($menu['route'])}}">{{$menu['text']}}</a></li>
                    @endforeach
                @endif
                <li class="nav-item"><a class="nav-link {{ Request::is('system*') ? 'active' : '' }}"
                                        href="{{route('system')}}">System</a></li>
                <li class="nav-item"><a class="nav-link {{ Request::is('modules') ? 'active' : '' }}"
                                        href="{{route('modules')}}">Modules</a></li>
            </ul>
        @endauth
        <ul class="navbar-nav ml-auto">
            @guest
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                </li>
                @if (Route::has('register'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                    </li>
                @endif
            @else
                {{--recent orders--}}
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle ordersDropdown" href="#" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        <i class="fa fa-list-alt"></i> Closed Orders
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown"
                         id="recentOrdersMenuContainer">
                        <a class="dropdown-item" href="#">
                            Loading ...
                        </a>
                    </div>
                </li>
                {{--favorite symbols--}}
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        <i class="fa fa-star"></i> Favorites
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown"
                         id="FavoriteSymbolsMenuContainer">

                    </div>
                </li>
                {{--profile --}}
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->name }} <span class="caret"></span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
            @endguest
        </ul>
    </div>
</nav>

<script>

    var favorites = @auth {!! json_encode(unserialize(\Illuminate\Support\Facades\Auth::user()->favorites)) !!} @elseauth [] @endauth ;

    function updateMenuFavorites() {
        var container = $("#FavoriteSymbolsMenuContainer");
        var html = "";

        $.each(favorites, function (key, value) {
            html += "<a class=\"dropdown-item\" href=\"/positions/" + value + "/show\">\n" +
                "                            <div class=\"menu-symbol\">\n" +
                "                                " + value + "\n" +
                "                            </div>\n" +
                "                        </a>";
        });
        if (html === "") {
            html = "<a class=\"dropdown-item\" href=\"#\">\n" +
                "                            <div class=\"menu-symbol\">\n" +
                "                                Empty\n" +
                "                            </div>\n" +
                "                        </a>";
        }
        container.html(html);
    }

    function cancelEdit() {
        var redirectUrl = "{{route('positions')}}";
        document.location.href = redirectUrl;
    }

    function savePosition() {
        $("#savePositionBtn").attr('disabled', 'disabled');
        var url = "{{route('savePosition')}}";
        var redirectUrl = "{{route('positions')}}";
        var orderId = "{{isset($order) ? $order->id : ''}}";
        var pair = document.getElementById("pair").value;
        var quantity = document.getElementById("quantity").value;
        var tp = document.getElementById("tp").value ? document.getElementById("tp").value : "-";
        var ttp = document.getElementById("ttp").value ? document.getElementById("ttp").value : "-";
        var sl = document.getElementById("sl").value ? document.getElementById("sl").value : "-";
        var tsl = document.getElementById("tsl").value ? document.getElementById("tsl").value : "-";

        axios.post(url, {
            id: orderId,
            symbol: pair,
            tp: tp,
            sl: sl,
            ttp: ttp,
            tsl: tsl
        }).then(function (response) {
            document.location.href = redirectUrl;
        }).catch(function (error) {
            console.log(error);
        });
    }

    function openPosition(type) {
        var pair = document.getElementById("pair").value;
        var quantity = document.getElementById("quantity").value;
        var tp = document.getElementById("tp").value ? document.getElementById("tp").value : "-";
        var ttp = document.getElementById("ttp").value ? document.getElementById("ttp").value : "-";
        var sl = document.getElementById("sl").value ? document.getElementById("sl").value : "-";
        var tsl = document.getElementById("tsl").value ? document.getElementById("tsl").value : "-";

        var url = '/positions/new/'+type+'/' + pair + "/" + quantity + "/" + tp + "/" + sl + "/" + ttp + "/" + tsl;
        window.location.href = url;
    }

    function openTV(symbol) {
        document.getElementById("tradingview_b42a6").innerHTML = '';
            var chart = LightweightCharts.createChart(document.getElementById("tradingview_b42a6"), {
            layout: {
                textColor: '#d1d4dc',
                backgroundColor: '#000000',
            },
            priceScale: {
                scaleMargins: {
                    top: 0.30,
                    bottom: 0.25,
                },
                borderVisible: true,
            },
            crosshair: {
                vertLine: {
                    width: 5,
                    color: 'rgba(224, 227, 235, 0.1)',
                    style: 0,
                },
                horzLine: {
                    visible: true,
                    labelVisible: true,
                },
            },
            grid: {
                vertLines: {
                    color: 'rgba(42, 46, 57, 0)',
                },
                horzLines: {
                    color: 'rgba(42, 46, 57, 0)',
                },
            },
            localization: {
                priceFormatter: function(price) {
                    // add $ sign before price
                    return  price;
                },
            },
            timeScale: {
                timeVisible: true,
                secondsVisible: true,
            },
        });
        var areaSeries = chart.addAreaSeries({
            topColor: 'rgba(38, 198, 218, 0.56)',
            bottomColor: 'rgba(38, 198, 218, 0.04)',
            lineColor: 'rgba(38, 198, 218, 1)',
            lineWidth: 2,
            crossHairMarkerVisible: true,
        });
        var symbolHistory = [];
        var currentArea = {
            value:null,
            time: -1,
        };
        axios.get('/tickers/'+symbol+'/history/-1').then(function (response) {
            symbolHistory = response.data;
            areaSeries.setData(symbolHistory);
            currentArea = {
                value:symbolHistory[0].value,
                time: symbolHistory[0].time,
            };
            var inputlp = document.getElementById("lp");
            inputlp.text = document.getElementById("lp");
            setInterval(function() {
                axios.get('/tickers/'+symbol+'/history/'+currentArea.time).then(function (response) {
                    var lastHistory = response.data;
                    console.log(lastHistory);

                    lastHistory.forEach(function (item, index) {
                        console.log(item);
                        currentArea.time = item.time;
                        currentArea.value = item.value;
                        areaSeries.update(currentArea);
                    });

                }).then(function (error) {
                    console.log(error);
                });

            }, 10000);
        }).then(function (error) {
            console.log(error);
        });


        document.getElementById("tradingview_b42a6").style.position = 'relative';

        var legend = document.createElement('div');
        legend.classList.add('legend');
        document.getElementsByClassName("tradingSymbol")[0].appendChild(legend);

        var firstRow = document.createElement('div');
        firstRow.innerText = symbol+' Real Time';
        firstRow.style.color = 'white';
        legend.appendChild(firstRow);

        function pad(n) {
            var s = ('0' + n);
            return s.substr(s.length - 2);
        }

        chart.subscribeCrosshairMove((param) => {
            if (param.time) {
            const price = param.seriesPrices.get(areaSeries);
            firstRow.innerText = symbol+' Real Time' + '  ' + price.toFixed(6);
        }
    else {
            firstRow.innerText = symbol+' Real Time';
        }
    });
//        var chart = LightweightCharts.createChart(document.body, {
//            crosshair: {
//                mode: LightweightCharts.CrosshairMode.Normal,
//            },
//        });

//        var candleSeries = chart.addCandlestickSeries();
//        console.log(symbolHistory);
//        var data = [
//
//            { time: '2018-10-23', open: 56.09, high: 57.47, low: 56.09, close: 57.21 }
//        ];
//
//        candleSeries.setData(data);




        if (symbol) {
            $(".toggleFavorite").attr('data-symbol', symbol);
            $(".tradingSymbol").html(symbol);
            var isFavorite = false;

            $.each(favorites, function (key, value) {
                if (value === symbol) {
                    isFavorite = true;
                }
            });

            if (isFavorite) {
                $(".toggleFavorite").attr('class', 'fa toggleFavorite fa-star');
            }
            else {
                $(".toggleFavorite").attr('class', 'fa toggleFavorite fa-star-o');
            }

        }

    }

    $(function () {
        updateMenuFavorites();
        window.availableTags = {!! json_encode(\App\BithumbTradeHelper::getSymbols()) !!};
        $("#pair").autocomplete({
            source: availableTags,
            autoFill: true,
            select: function (event, ui) {   //must be cleared with function parameter
                var pair = ui.item.label;
                openTV(pair);
            }
        });
    });

    $(document).ready(function () {
        $(".ordersDropdown").click(function () {
            axios.get('/loadRecentOrders').then(function (response) {
                html = "";
                $.each(response.data, function (key, order) {
                    var color = order.pl > 0 ? "text-success" : "text-danger";
                    html += "<a class=\"dropdown-item\" href=\"/positions/" + order.id + "\">\n" +
                        "                            <div class=\""+ color +" menu-order\">\n" +
                        "                                " + order.symbol + " ("+ Math.round(order.pl * 100) / 100 +") \n" +
                        "                            </div>\n" +
                        "                        </a>";
                });
                $("#recentOrdersMenuContainer").html(html);
            }).then(function (error) {
                console.log(error);
            });
        });
    });


</script>