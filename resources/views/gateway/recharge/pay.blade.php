<!
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>充值测试</title>
    <link href="{{asset('vendor/layui/css/layui.css')}}"  rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="{{asset('vendor/layui/layui.js')}}"></script>
</head>
<body>
<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
    <legend>充值测试</legend>
</fieldset>

<form class="layui-form" method="post">
    <div class="layui-form-item">
        <label class="layui-form-label">appid</label>
        <div class="layui-input-block">
            <input type="text" name="app_id" value="WEBf707ece37d069c2afb991c6756fda" placeholder="请输入应用号" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">商户编号</label>
        <div class="layui-input-block">
            <input type="text" name="mch_code" value="D0748934DE20CC1" placeholder="请输入商户号" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">商户订单号</label>
        <div class="layui-input-block">
            <input type="text" name="mch_no" placeholder="请输入商户订单号" value="{{date('YmdHis').substr(time(), -3)}}" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">订单金额</label>
        <div class="layui-input-block">
            <input type="text" name="order_amt" placeholder="请输入订单金额" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">加密方式</label>
        <div class="layui-input-block">
            <input type="text" name="sign_type" value="md5" placeholder="请输入加密方式" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">充值方式</label>
        <div class="layui-input-block">
            <select name="recharge_type">
                <option value="weixin">微信扫码</option>
                <option value="alipay">支付宝扫码</option>
                <option value="bank">在线银行</option>
                <option value="weixin_wap">微信wap</option>
                <option value="alipay_wap">支付宝wap</option>
                <option value="bank_wap">银联在线</option>
            </select>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">银行编码</label>
        <div class="layui-input-block">
            <select name="bank_code">
                @foreach(\App\Lib\BankMap::getMap() as $key => $map)
                    <option value="{{$key}}">{{$map}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">订单生成时间</label>
        <div class="layui-input-block">
            <input type="text" name="order_time" placeholder="请输入订单生成时间" value="{{date('YmdHis')}}" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">商品描述</label>
        <div class="layui-input-block">
            <textarea name="body" placeholder="请输入商品描述" class="layui-textarea">111</textarea>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">异步通知地址</label>
        <div class="layui-input-block">
            <input type="text" name="callback_url" value="{{route('page.callback')}}" placeholder="请输入异步通知地址" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">同步跳转地址</label>
        <div class="layui-input-block">
            <input type="text" name="return_url" value="http://www.baidu.com" placeholder="请输入同步跳转地址" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="" lay-filter="demo1">立即提交</button>
        </div>
    </div>

</form>

<script>
    layui.use(['form', 'layedit', 'laydate'], function(){
        var form = layui.form
            ,layer = layui.layer
            ,layedit = layui.layedit
            ,laydate = layui.laydate;

        //日期
        laydate.render({
            elem: '#date'
        });
        laydate.render({
            elem: '#date1'
        });

        //创建一个编辑器
        var editIndex = layedit.build('LAY_demo_editor');

        //自定义验证规则
        form.verify({
            title: function(value){
                if(value.length < 5){
                    return '标题至少得5个字符啊';
                }
            }
            ,pass: [/(.+){6,12}$/, '密码必须6到12位']
            ,content: function(value){
                layedit.sync(editIndex);
            }
        });






    });
</script>
</body>
</html>
