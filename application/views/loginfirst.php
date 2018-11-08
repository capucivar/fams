<!DOCTYPE html >
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> 酒鬼棋牌代理商平台 </title>
    <!--Tell the browser to be responsive to screen width-->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!--Bootstrap 3.3.5-->
    <link rel="stylesheet" href="/static/bootstrap/css/bootstrap.min.css">
    <!--Font Awesome-->
    <link href="<?= CDN_URL ?>/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet">
    <!--Ionicons -->
    <link href="<?= CDN_URL ?>/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet">
    <!--Theme style-->
    <link rel="stylesheet" href="/static/css/AdminLTE.min.css">
    <!--iCheck -->
    <link rel="stylesheet" href="/static/plugins/iCheck/square/blue.css">

    <!--Page Script -->
    <link rel="stylesheet" href="/static/css/main.css">

    <!--HTML5 Shim and Respond . js IE8 support of HTML5 elements and media queries-->
    <!--WARNING: Respond . js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition login-page">
    <form class="login-box" id="mForm">
<!--    <div class="login-logo">
        <a href="/"><b>酒鬼棋牌</b> 代理商平台</a>
    </div> -->

    <div class="login-box-body">
        <p class="login-box-msg">首次登陆请修改密码 </p>
        <div id="errorLabel" hidden="hidden" class="control-label has-error">
            <label class="control-label">
                <i class="fa fa-times-circle-o"></i> 请输入正确的账号和密码
            </label>
        </div>
        <div id="userNameDiv" class="form-group has-feedback">
            <input id="AID" name="AID" type="text" hidden value="<?= $aid ?>" /> 
            <input id="CELLPHONE" name="CELLPHONE" type="text" class="form-control" placeholder="手机号"/>
            <span class="glyphicon glyphicon-phone form-control-feedback"></span>
        </div> 
        <div class="row form-group has-feedback" >
            <div class="col-xs-12"> 
                <div class="input-group"> 
                    <input id="VCODE" name="VCODE" type="text"  class="form-control" placeholder="验证码">
                    <span class="input-group-addon" id="span_vcode" name="span_vcode" onclick="getVCode();">获取验证码</span> 
                </div>  
            </div> 
        </div>
        
        <div id="passwordDiv" class="form-group has-feedback">
            <input id="NEWPWD" name="NEWPWD" type="password" class="form-control" placeholder="新密码"/>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div id="passwordDiv" class="form-group has-feedback">
            <input id="NEWPWD2" name="NEWPWD2" type="password" class="form-control" placeholder="再次输入新密码"/>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="row">
            <div class="col-xs-8"> 
            </div>
            <div class="col-xs-4">
                <button type="button" id="btnChange" onclick="doChange()" class="btn btn-primary btn-block btn-flat">确认</button>
            </div>
        </div> 
    </div><!-- /.login-box-body -->

</form><!-- /.login-box -->

<!-- jQuery 2.1.4 -->
<script src="<?= CDN_URL ?>/jquery/2.1.4/jquery.min.js"></script>
<!-- Bootstrap 3.3.5 -->
<script src="/static/bootstrap/js/bootstrap.min.js"></script> 
<script> 
    var wait=60;
    function time(){
        var $span = $('#span_vcode'); 
        if (wait == 0) {
            $span.removeClass("disabled"); 
            $span.html('获取验证码'); 
            wait = 60;
         }else {
             $span.addClass("disabled");
             $span.html("重新发送(" + wait + ")");
             wait--;
             setTimeout(function(){ time() }, 1000);
         }
    }
    
    function getVCode(){
        var $span = $('#span_vcode');
        if ($span.hasClass("disabled")) return;  
        var phone = $('#CELLPHONE').val();
        var phone2 = '<?= $cell ?>';
        if(phone!=phone2){
            baModalTipShow("提示", "手机号码与系统预留手机号不一致", "d");
            $btn.html('确定');
            $btn.removeClass("disabled");
            return;
        }
        time();
        $.post("/ChangePwdC/sendVCode", {phone:phone}, function (response) {
            if (response.code != "1") {
                baModalTipShow("错误", response.message, "d");
            } else {
                baModalTipShow("提示", "【5分钟有效】" + response.result, "s", function () {
                    baModalTipToggle();
                });
            }
        }); 
    }
    
    function doChange() {
        var phone = $('#CELLPHONE').val();
        var pwd = $('#NEWPWD').val();
        var pwd2 = $('#NEWPWD2').val(); 
        var $btn = $('#btnChange');
        $btn.addClass("disabled");
        $btn.html('<i class="fa fa-spinner fa-pulse"></i>修改中');
        if(phone.length!=11){
            baModalTipShow("提示", "请输入11位手机号码", "d");
            $btn.html('确定');
            $btn.removeClass("disabled");
            return;
        }
        if(pwd==""){
            baModalTipShow("提示", "请输入新密码", "d");
            $btn.html('确定');
            $btn.removeClass("disabled");
            return;
        }
        if(pwd!=pwd2){
            baModalTipShow("提示", "两次密码不一致", "d");
            $btn.html('确定');
            $btn.removeClass("disabled");
            return;
        }
        var formData = $("#mForm").serializeArray();
        $.post("/home/doChangepwd", formData, function (response) {
            if (response.code != "1") {
                baModalTipShow("错误", response.message, "d");
                $btn.html('保存');
                $btn.removeClass("disabled");
                return;
            } else {
                window.location = "/home/account";
            }
        });
    }
</script>
</body>
</html>
