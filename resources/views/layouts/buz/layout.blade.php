<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>个人中心--基本信息</title>
    <!-- Mobile specific metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!-- Force IE9 to render in normal mode -->
    <!--[if IE]><meta http-equiv="x-ua-compatible" content="IE=9" /><![endif]-->
    <meta name="author" content="SuggeElson" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="application-name" content="sprFlat admin template" />
    <meta name="msapplication-TileColor" content="#3399cc" />
    <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/buz/style.css')}}" />
    <!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
    <script src="https://cdn.bootcss.com/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    @section('assets')
    @show
</head>
<body>
<!-- Start #header -->
<div id="header">
    <div class="container-fluid">
        <div class="navbar">
            <div class="navbar-header">

                <button type="button" class="navbar-toggle" data-toggle="collapse"
                        data-target="#sidebar">
                    <span>左侧导航</span>
                </button>
                <button type="button" class="navbar-toggle" data-toggle="collapse"
                        data-target="#navbar-collapse">
                    <span class="sr-only">切换导航</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand col-xs-6" href="index.html">
                    <img src="{{asset('image/logo1.png')}}" width="150px" height="60px" style="margin-top:10px ; margin-left: 30px;" />
                </a>
            </div>
            <nav class="top-nav collapse navbar-collapse " role="navigation" id="navbar-collapse">
                <ul class="nav navbar-nav pull-right col-md-10 col-sm-8">
                    <li id="toggle-sidebar-li" class="on pull-left">
                        <a href="#" id="toggle-sidebar">首页</a>
                    </li>
                    <li id="toggle-sidebar-li" class="pull-left">
                        <a href="settlement/historical.html" id="toggle-sidebar">结算管理</a>
                    </li>
                    <li id="toggle-sidebar-li" class="pull-left">
                        <a href="#" id="toggle-sidebar"  data-toggle="modal" data-target="#djWordDown">对接文档下载</a>
                    </li>
                    <li class="pull-right">
                        <i class="m-zhxx "></i>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    <!-- Start .header-inner -->
</div>
<div class="yhy-content col-sm-12">
@section('content')
@show
</div>
<!-- 模态框对接文档下载 -->
<div class="modal fade" id="djWordDown" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">对接文档下载</h4>
            </div>
            <div class="modal-body">
                <ul class="jswddj">
                    <li>商户接入文档下载</li>
                    <li>PC demo下载</li>
                    <li>h5下载</li>
                </ul>
            </div>
        </div>
    </div>
</div>
</body>
</html>
