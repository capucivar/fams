<!DOCTYPE html >
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> 云玺固定资产管理系统 </title>
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
<form class="login-box">
    <div class="login-logo">
        <a href="/"> 云玺固定资产管理系统</a>
    </div><!-- /.login-logo -->

    <div class="login-box-body">
        <p class="login-box-msg">输入账号密码进行登录 </p>
        <div id="errorLabel" hidden="hidden" class="control-label has-error">
            <label class="control-label">
                <i class="fa fa-times-circle-o"></i> 
				<span id="errText"></span><!-- 请输入正确的账号和密码 -->
            </label>
        </div> 
        <div id="userNameDiv" class="form-group has-feedback">
            <input id="userName" type="text" class="form-control" placeholder="账号"/>
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
        <div id="passwordDiv" class="form-group has-feedback">
            <input id="pwd" type="password" class="form-control" placeholder="密码"/>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="row">
            <div class="col-xs-8">
                <div class="checkbox icheck">
                    <label>
                        <input id="rememberMe" type="checkbox"> 记住我
                    </label>
                </div>
            </div>
            <div class="col-xs-4">
                <button type="button" id="btnLogin" onclick="doLogin()" class="btn btn-primary btn-block btn-flat">登录</button>
            </div>
        </div>
    </div><!-- /.login-box-body -->

</form><!-- /.login-box -->

<!-- jQuery 2.1.4 -->
<script src="<?= CDN_URL ?>/jquery/2.1.4/jquery.min.js"></script>
<!-- Bootstrap 3.3.5 -->
<script src="/static/bootstrap/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="/static/plugins/iCheck/icheck.min.js"></script>
<script>
    $(function () {
        $('#rememberMe').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%'
        });

        document.onkeydown = function (e) {
            var ev = document.all ? window.event : e;
            if (ev.keyCode == 13) {
                doLogin();
            }
        };
    });
    function doLogin() {
        var userName = $('#userName').val();
        var pwd = $('#pwd').val();
        var rmm = $("#rememberMe").is(":checked");
        if(userName==""){ 
			showErr("请输入账号");
            return false;
        }
        if(pwd==""){ 
			showErr("请输入密码");
            return false;
        }
        var reg = /^\d{6}\b/;
        if (!reg.test(userName)) {
            showErr("请输入正确的账号");
            return false;
        };
        var $btnLogin = $('#btnLogin');
        $btnLogin.addClass("disabled");
        $btnLogin.html('<i class="fa fa-spinner fa-pulse"></i>登录中');
        $.post("/LoginC/doLogin", {userName: userName, pwd: pwd, rmm: rmm}, function (data) {
            if (data.code != "1") {
                $('#userNameDiv').addClass("has-error");
                $('#passwordDiv').addClass("has-error");
                $btnLogin.removeClass("disabled");
                $btnLogin.html("登录");
                $('#errorLabel').show();
            } else { 
                window.location = "/Home";
            }
        });
    }
	
	function showErr(errMsg){
		$('#userNameDiv').addClass("has-error");
		$('#errText')[0].innerHTML = errMsg ;
		$('#errorLabel').show();
	}
</script>
</body>
</html>
