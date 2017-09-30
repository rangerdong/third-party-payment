<!<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>认证资料</title>
    <link rel="stylesheet" href="{{asset('vendor/layui/css/layui.css')}}">
</head>
<body>

<div class="layui-form">
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
        <legend>实名资料</legend>
    </fieldset>
    <div class="layui-form-item">
        <label class="layui-form-label">真实姓名</label>
        <div class="layui-input-block">
            <input type="text" name="realname" value="{{$profile->realname}}" readonly="true" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">身份证号码</label>
        <div class="layui-input-block">
            <input type="text" name="idcard" value="{{$profile->idcard}}" readonly="true" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">手持证件照</label>
        <div class="layui-input-block">
            <img src="{{$profile->img_id_hand}}" class="image">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">身份证正面</label>
        <div class="layui-input-block">
            <img src="{{$profile->img_id_front}}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">身份证反面</label>
        <div class="layui-input-block">
            <img src="{{$profile->img_id_back}}">
        </div>
    </div>
    @if($profile->property == 1)
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
        <legend>企业资料</legend>
    </fieldset>
    <div class="layui-form-item">
        <label class="layui-form-label">认证企业名</label>
        <div class="layui-input-block">
            <input type="text" name="enterprise" value="{{$profile->enterprise}}" readonly="true" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">企业所在地</label>
        <div class="layui-input-block">
            <input type="text" name="full_addr" value="{{$profile->city->province . $profile->city->city . $profile->address}}" readonly="true" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">营业执照照片</label>
        <div class="layui-input-block">
            <img src="{{$profile->img_license}}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">税务许可证</label>
        <div class="layui-input-block">
            <img src="{{$profile->img_tax}}">
        </div>
    </div>
        @if($profile->img_permit)
            <div class="layui-form-item">
                <label class="layui-form-label">文网文辅助文件</label>
                <div class="layui-input-block">
                    <img src="{{$profile->img_permit}}">
                </div>
            </div>
        @endif
    @endif
</div>


</body>
</html>
