@extends('layouts.buz.layout')
@section('content')

        <div class="row">
            <div class="col-sm-2 col-lg-3"></div>
            <div class="col-xs-12 col-sm-4 col-lg-2">
                <div class="commonIndex">
                    <h4>
                        <cite class="circle">安</cite>
                        大风云-安卓
                    </h4>
                    <div class="index-w">
                        <h3 class="fontsize2">0.00</h3>
                        <p>今日交易额（元）</p>
                        <h3 class="fontsize2">0</h3>
                        <p>今日订单数</p>
                    </div>
                    <div class="row">
                        <div class="col-xs-4 col-xs-push-1">
                            <a href="app/query.html">
                                <i class="iconfontM"></i>
                                <p>支付</p>
                            </a>
                        </div>
                        <div class="col-xs-4">
                            <a href="#">
                                <i class="iconfontF"></i>
                                <p>分析</p>
                            </a>
                        </div>
                        <div class="col-xs-4 col-xs-pull-1">
                            <a href="app/appsetting.html">
                                <i class="iconfontS"></i>
                                <p>设置</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-lg-2">
                <div class="commonIndex">
                    <h4>
                        <cite class="circle">安</cite>
                        大风云-安卓
                    </h4>
                    <div class="index-w">
                        <h3 class="fontsize2">0.00</h3>
                        <p>今日交易额（元）</p>
                        <h3 class="fontsize2">0</h3>
                        <p>今日订单数</p>
                    </div>
                    <div class="row">
                        <div class="col-xs-4 col-xs-push-1">
                            <a href="app/query.html">
                                <i class="iconfontM"></i>
                                <p>支付</p>
                            </a>
                        </div>
                        <div class="col-xs-4">
                            <a href="#">
                                <i class="iconfontF"></i>
                                <p>分析</p>
                            </a>
                        </div>
                        <div class="col-xs-4 col-xs-pull-1">
                            <a href="app/appsetting">
                                <i class="iconfontS"></i>
                                <p>设置</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-4 col-lg-2">
                <div class="commonIndex commonW">
                    <img src="{{asset('image/add.png')}}" width="50px" height="50px">
                </div>
            </div>
        </div>
    @endsection()