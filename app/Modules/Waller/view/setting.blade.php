<div class="card">
    <div class="card-header">
        Config
    </div>
    <div class="card-body">
        <form method="post" class="row">
            {{csrf_field()}}
            <div class="col-6">
                <h2>
                    Status :
                    @if($statusWaller)
                        <span class="btn-outline-success">Working</span>
                    @else
                        <span class="btn-outline-danger">Stopped</span>
                    @endif
                </h2>
            </div>
            <div class="row">
            <div class="col-6">
                <label for="buy-covering">
                    Covering Buy wall in %:
                </label>
                <input type="text" id="buy-covering" name="buyCovering" class="form-control" value="{{$config['buyCovering'] ?? ''}}">
            </div>
            <div class="col-6">
                <label for="sell-covering">
                    Covering Sell wall in % :
                </label>
                <input type="text" id="sell-covering" name="sellCovering" class="form-control" value="{{$config['sellCovering'] ?? ''}}">
            </div>
            <div class="col-6">
                <label for="spread">
                    Spread :
                </label>
                <input type="text" id="spread" name="spread" class="form-control" value="{{$config['spread'] ?? ''}}">
            </div>
            <div class="col-6">
                <label for="buy-order-amount">
                    Buy order amount in base example USD if BIP-USD :
                </label>
                <input type="text" id="buy-order-amount" name="buyOrderAmount" class="form-control" value="{{$config['buyOrderAmount'] ?? ''}}">
            </div>
            <div class="col-6">
                <label for="pair-wall">
                    Pair :
                </label>
                <input type="text" id="pair-wall" name="pair" class="form-control" value="{{$config['pair'] ?? ''}}">
            </div>
                <div class="col-md-3">
                    <h4>Services</h4>
                    <a href="{{route('systemCtlWaller',['stop','waller'])}}"
                       class="btn btn-outline-secondary fa fa-2x fa-stop text-danger"></a>
                    {{--<button class="btn btn-outline-secondary ml-md-5 fa fa-2x fa-refresh text-info"></button>--}}
                    <a href="{{route('systemCtlWaller',['start','waller'])}}"
                       class="btn btn-outline-secondary ml-md-5 fa fa-2x fa-play text-success"></a>
                    <div class="col-12 mt-3">
                        <p>
                            Waller Daemon
                            : {!! $statusWaller ? '<span class="text-success">Running</span>' : '<span class="text-danger">Stopped</span>' !!}
                        </p>
                    </div>
                </div>
            </div>
            {{--<div class="col-6">--}}
                {{--<label class="mt-3">--}}
                    {{--Exchanges :--}}
                {{--</label>--}}
                {{--<div class="row">--}}
                    {{--<div class="col-3">--}}
                        {{--Bittrex <input type="checkbox" name="exchange[bittrex]" value="1"--}}
                                       {{--@if(in_array('bittrex',$config['exchange'] ?? [])) checked @endif>--}}
                    {{--</div>--}}
                    {{--<div class="col-3">--}}
                        {{--Poloniex <input type="checkbox" name="exchange[poloniex]" value="1"--}}
                                        {{--@if(in_array('poloniex',$config['exchange'] ?? [])) checked @endif>--}}
                    {{--</div>--}}
                    {{--<div class="col-3">--}}
                        {{--Bithumb <input type="checkbox" name="exchange[bithumb]" value="1"--}}
                                       {{--@if(in_array('bithumb',$config['exchange'] ?? [])) checked @endif>--}}
                    {{--</div>--}}
                    {{--<div class="col-3">--}}
                        {{--Kucoin <input type="checkbox" name="exchange[kucoin]" value="1"--}}
                                      {{--@if(in_array('kucoin',$config['exchange'] ?? [])) checked @endif>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--<div class="col-6">--}}
                {{--<label class="mt-3">--}}
                    {{--Market Volume (BTC/ETH/USDT/BNB):--}}
                {{--</label>--}}
                {{--<select id="outputvolume" class="form-control" name="volume">--}}
                    {{--<option value="0">all</option>--}}
                    {{--<option value="5">&gt;5 Marketvolume</option>--}}
                    {{--<option value="10">&gt;10 Marketvolume</option>--}}
                    {{--<option value="20">&gt;20 Marketvolume</option>--}}
                    {{--<option value="30">&gt;30 Marketvolume</option>--}}
                    {{--<option value="40">&gt;40 Marketvolume</option>--}}
                    {{--<option value="50">&gt;50 Marketvolume</option>--}}
                    {{--<option value="100">&gt;100 Marketvolume</option>--}}
                    {{--<option value="200">&gt;200 Marketvolume</option>--}}
                    {{--<option value="300">&gt;300 Marketvolume</option>--}}
                    {{--<option value="400">&gt;400 Marketvolume</option>--}}
                    {{--<option value="500">&gt;500 Marketvolume</option>--}}
                    {{--<option value="1000">&gt;1000 Marketvolume</option>--}}
                    {{--<option value="2000">&gt;2000 Marketvolume</option>--}}
                    {{--<option value="3000">&gt;3000 Marketvolume</option>--}}
                    {{--<option value="4000">&gt;4000 Marketvolume</option>--}}
                    {{--<option value="5000">&gt;5000 Marketvolume</option>--}}
                    {{--<option value="10000">&gt;10000 Marketvolume</option>--}}
                {{--</select>--}}
            {{--</div>--}}

            <div class="col-6 offset-3 mt-3">
                <button type="submit" class="col-12 btn btn-primary">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>


<script>
    $(document).ready(function () {
        $('#outputvolume').val("{{$config['volume'] ?? 0}}");

        $("#pair-wall").autocomplete({
            source: availableTags,
            autoFill: true
        });
    });

</script>