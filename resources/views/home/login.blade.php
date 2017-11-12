<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <!-- 新 Bootstrap 核心 CSS 文件 -->
    <title>登录</title>
    <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/buz/style.css')}}" />
    <!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
    <script src="https://cdn.bootcss.com/jquery/2.1.1/jquery.min.js"></script>

    <!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
    <!--<script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>-->
</head>
<body >
<div class="yhy-header">
    <div class="yhy-wrapper">
        <a href="javascript:;" class="header-logo">
            <img src="img/logo.png" />
        </a>
        <div class="header-txt">商户管理后台</div>
    </div>
</div>
<div class="yhy-loginBody ">
    <div class="yhy-wrapper bgwhite mt20">
        <img src="img/login-img.jpg" class="login-img"/>
        <div class="login-plate">
            <div class="login-txt">
                用户登录
            </div>
            <span class="login-errmsg" id="errmsg"></span>
            <div class="login-form">
                <form>
                    <div class="login-group">
                        <label class="label-txt">用户名：</label>
                        <input type="text" name="username" class="input-txt" id="login_user"/>
                    </div>
                    <div class="login-group">
                        <label class="label-txt">密码：</label>
                        <input type="text" name="userpassword" class="input-txt" id="login_pwd"/>
                    </div>
                    <div class="login-group">
                        <label class="label-txt">验证码：</label>
                        <input type="text" name="usercode" class="input-txt codes" id="validcode"/>
                        <img  src="img/code.png" class="img-code"/>
                    </div>
                    <div class="login-group login-box">
                        <a href="javascript:;" class="login-btn" id="loginbtn">登录</a>
                        <a href="javascript:;" class="login-btn">订单查询</a>
                    </div>
                </form>
            </div>

            <div class="link-box">
                <a href="">免费接入</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <a href="forget/forgetPwdChange.html">忘记密码</a>
            </div>
        </div>
    </div>
</div>
</body>
<script>
    $(function () {
        $("#loginbtn").click(function () {
            //				alert();
            var login_user = $("#login_user").val();   //用户名
            var passwd = $("#login_pwd").val();   //密码
            if (checkCount()) {
                $(this).text("登录中...");
                $.ajax({
                    url: "http://buz.payment.com/api/auth/login",
                    data: { username: login_user, password: passwd },
                    type: 'post',
                    success:function(e){
                        console.log(e);
                        if(e.code == 0){
                            var taken = e.data.taken;
                            localStorage.setItem('taken',taken);
                        }

                    }


                });



            }
        })
    });
    function OrderieHandler(event) {
        event = event ? event : (window.event ? window.event : null);
        if (event.keyCode == 13 || event.which == 13) {
            $("#loginbtn").click();
            // route : window.location.href = '{{route('buz.register')}}';
            window.location.href = domain + 'buz.register';
        }
    }

    function checkCount() {
        var login_user = $("#login_user").val();   //用户名
        var passwd = $("#login_pwd").val();   //密码
        var vcode = $("#validcode").val();   //验证码
        if (login_user == '') {
            $("#errmsg").html("请输入正确的用户名！");
            $("#login_user").focus();
            return false;
        }

        else if (passwd == '') {
            $("#errmsg").html("用户名或密码错误！");
            $("#login_pwd").focus();
            return false;
        }
        else if (vcode == '') {

            $("#errmsg").html("请输入正确的验证码!");
            $("#validcode").focus();
            return false;
        }
        return true;
    }





    if ('' != '') {
        $("#errmsg").html('');
    }


</script>

</html>
