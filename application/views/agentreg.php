<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>酒鬼棋牌</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="/static/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link href="<?= CDN_URL ?>/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet">
    <!-- Ionicons -->
    <link href="<?= CDN_URL ?>/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet">
    <!-- Theme style -->
    <link rel="stylesheet" href="/static/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="/static/css/skins/_all-skins.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/static/plugins/iCheck/flat/blue.css">
    <!-- Morris chart -->
    <link rel="stylesheet" href="/static/plugins/morris/morris.css">
    <!-- jvectormap -->
    <link rel="stylesheet" href="/static/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <!-- Date Picker -->
    <link rel="stylesheet" href="/static/plugins/datepicker/datepicker3.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="/static/plugins/daterangepicker/daterangepicker-bs3.css">
    <!-- toastr -->
    <link rel="stylesheet" href="/static/plugins/toastr/toastr.min.css">
    <!-- jQuery 2.1.4 -->
    <script src="<?= CDN_URL ?>/jquery/2.1.4/jquery.min.js"></script>

    <!-- Page Script-->
    <link rel="stylesheet" href="/static/css/main.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition skin-blue sidebar-mini"> 
<!--content-->
<div id="div_reg" name="div_reg" >
<div class="box box-primary">
    <div class="box-body">
        <h3 id="title" class="box-title text-center">注册成为代理商</h3>
        <br/> 
        <div class="col-sm-3"></div>
        <div class="col-sm-6">
            <form id="client-form" role="form" class="form-horizontal">
                <input id="PAID" name="PAID" value="<?= $paid?>" type="text" hidden="hidden" >
                <div class="form-group">
                    <label class="col-sm-3 control-label">代理商名称</label>
                    <div class="col-sm-9">
                        <input id="ANAME" name="ANAME" type="text" class="form-control" >
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-3 control-label">微信ID</label>
                    <div class="col-sm-9">
                        <input id="WXID" name="WXID" type="text" class="form-control"  >
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">手机号</label>
                    <div class="col-sm-9">
                        <input id="CELLPHONE" name="CELLPHONE" type="text" class="form-control" >
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">游戏ID</label>
                    <div class="col-sm-9">
                        <input id="UNO" name="UNO" type="text" class="form-control" >
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">所在地区</label>
                    <div class="col-sm-3">
                        <select id="loc_province" name="areaProvince" class="form-control" > </select>
                    </div>
                    <div class="col-sm-3">
                        <select id="loc_city" name="areaCity" class="form-control" > </select>
                    </div>
                    <div class="col-sm-3">
                        <select id="loc_town" name="areaTown" class="form-control" > </select>
                    </div>
                </div>
                
                 <div class="form-group">
                    <label class="col-sm-3 control-label">备注</label>
                    <div class="col-sm-9">
                        <input id="REMARK" name="MARK" placeholder="50字以内" type="text" class="form-control">
                    </div>
                </div>
            </form>  
        </div> 
    </div><!-- /.box-body -->
</div><!-- /.box --> 
<button type="button" id="btn-save" class="btn btn-primary btn-lg ba-center" onclick="saveAgent();">
    保存
</button>
</div>

<div id="div_reg_ok" name="div_reg_ok" >
<div class="box box-primary">
    <div class="box-body">
        <h3 id="title" class="box-title text-center">已成功注册为代理商</h3>
        <br/> 
        <div class="col-sm-3"></div>
        <div class="col-sm-6">
             <form id="result-form" role="form" class="form-horizontal"> 
                <div class="form-group">
                    <label class="col-sm-3 control-label">代理商ID：</label>
                    <div id="AID" class="col-sm-9"></div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">初始密码：</label>
                    <div id="PWD" class="col-sm-9"></div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">代理商邀请码：</label>
                    <div id="CODE" class="col-sm-9 "></div>
                </div>  
            </form>
        </div> 
    </div><!-- /.box-body -->
</div><!-- /.box --> 
<button type="button" id="btn-save" class="btn btn-primary btn-lg ba-center" onclick="javascript:window.location.href='/home/login'">
    去登陆
</button>
</div>


<div class="modal fade" id="tipModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index: 1151 !important;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="tipModalTitle">
                    提示标题
                </h4>
            </div>
            <div class="modal-body" id="tipModalContent">
                提示内容
            </div>
            <div class="modal-footer">
                <button id="tipModalBtn" type="button" class="btn btn-primary">
                    确定
                </button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /. Tip modal -->

<!-- Warning Modal -->
<div class="modal fade" id="warningModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index: 1150 !important;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="warningModalTitle">
                    提示标题
                </h4>
            </div>
            <div class="modal-body" id="warningModalContent">
                提示内容
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" onclick="$('#warningModal').modal('toggle');">
                    取消
                </button>
                <button id="warningModalBtn" type="button" class="btn btn-primary">
                    确定
                </button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /. Tip modal --> 

<footer class="main-footer">
    <div class="pull-right hidden-xs">
        Page rendered in <strong>{elapsed_time}</strong> seconds. <b>Version</b> 0.0.1
    </div>

    <strong>Copyright &copy; 2015-2016 <a href="#">酒鬼棋牌</a>.</strong> All rights reserved.
</footer>  
<!-- jQuery UI 1.11.4 -->
<script src="<?= CDN_URL ?>/jqueryui/1.11.4/jquery-ui.js"></script>
<!-- Bootstrap 3.3.5 -->
<script src="/static/bootstrap/js/bootstrap.min.js"></script>
<!-- Morris.js charts -->
<script src="<?= CDN_URL ?>/raphael/2.2.1/raphael.min.js"></script>
<script src="/static/plugins/morris/morris.min.js"></script>
<!-- Sparkline -->
<script src="/static/plugins/sparkline/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="/static/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="/static/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="/static/plugins/knob/jquery.knob.js"></script>
<!-- daterangepicker -->
<script src="<?= CDN_URL ?>/moment.js/2.10.2/moment.min.js"></script>
<script src="/static/plugins/daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="/static/plugins/datepicker/bootstrap-datepicker.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<!--<script src="/static/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>-->
<!-- Slimscroll -->
<script src="/static/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="/static/plugins/fastclick/fastclick.min.js"></script>
<!-- AdminLTE App -->
<script src="/static/js/app.min.js"></script>
<!-- Toastr script -->
<script src="/static/plugins/toastr/toastr.min.js"></script>
  
</body>
</html>


<script type="text/javascript">
    //显示提示窗口
    function baModalTipShow(title, content, arg, okOnClickCallBack) {
        $('#tipModalTitle').html(baGetInfoArgs(arg) + title);
        $('#tipModalContent').html(content);
        $("#tipModalBtn").unbind("click");
        $('#tipModalBtn').click(function () {
            if (typeof okOnClickCallBack == "function") {
                okOnClickCallBack();
            } else {
                baModalTipToggle();
            }
        });

        $('#tipModal').modal({backdrop: 'static', keyboard: false});
    }

    //切换提示窗口显示
    function baModalTipToggle() {
        $('#tipModal').modal("hide");
    }

    //显示警告窗口
    function baModalWarningShow(title, content, arg, okOnClickCallBack) {
        $('#warningModalTitle').html(baGetInfoArgs(arg) + title);
        $('#warningModalContent').html(content);
        $("#warningModalBtn").unbind("click");
        $('#warningModalBtn').click(function () {
            if (typeof okOnClickCallBack == "function") {
                okOnClickCallBack();
            } else {
                alert("没实现警告弹窗的回调方法");
            }
        });

        $('#warningModal').modal({backdrop: 'static', keyboard: false});
    }

    //切换警告窗口显示
    function baModalWarningToggle() {
        $('#warningModal').modal("hide");
    }

    //获取提示标志
    function baGetInfoArgs(arg) {
        switch (arg) {
            case 'w':
                return "<i class='fa fa-exclamation-triangle'></i>&nbsp;&nbsp;";
            case 'q':
                return "<i class='fa fa-question-circle'></i>&nbsp;&nbsp;";
            case 'i':
                return "<i class='fa fa-info-circle'></i>&nbsp;&nbsp;";
            case 'd':
                return "<i class='fa fa-times-circle-o'></i>&nbsp;&nbsp;";
            case 's':
                return "<i class='fa fa-check-circle-o'></i>&nbsp;&nbsp;";
            default:
                return "";
        }
    }

    //显示加载信息窗口
    function baModalLoadingShow(content) {
        $('#loadingModalContent').html('<i class="fa fa-spinner fa-pulse"></i>&nbsp;' + content);

        $('#loadingModal').modal({backdrop: 'static', keyboard: false});
    }

    //切换加载窗口显示
    function baModalLoadingToggle() {
        $('#loadingModal').modal("hide");
    }
</script>

<script src="/static/js/location.js"></script>
<script type="text/javascript">
    $(function () {  
        var isreg = "<?= $isreg?>";
        if(isreg!=""){
            $("#div_reg").hide();
            $("#div_reg_ok").show();
            var agent = JSON.parse('<?= $agent?>'); 
            $("#AID").text(agent.AID);
            $("#PWD").text("888888");
            $("#CODE").text(agent.SHARECODE);
            
        }else{
            $("#div_reg").show();
            $("#div_reg_ok").hide();
            var locs = [0, 0, 0]; 
            showLocation(locs[0], locs[1], locs[2]);
        }
    });
    
    function saveAgent() {
        var $btn = $('#btn-save');
        if ($btn.hasClass("disabled")) return;

        $btn.addClass("disabled");
        $btn.html('<i class="fa fa-spinner fa-pulse"></i>提交中');

        // 验证必填项
        if ($("#WXID").val() == "") {
            baModalTipShow("提示", "微信号不可为空", "d");
            $btn.html('保存');
            $btn.removeClass("disabled");
            return;
        }
        if ($("#CELLPHONE").val() == "" || $("#CELLPHONE").val().length!=11) {
            baModalTipShow("提示", "请填写正确的手机号码", "d");
            $btn.html('保存');
            $btn.removeClass("disabled");
            return;
        }
        if ($("#UNO").val() == "") {
            baModalTipShow("提示", "游戏ID不可为空", "d");
            $btn.html('保存');
            $btn.removeClass("disabled");
            return;
        }

        // 提交表单
        var formData = $("#client-form").serializeArray();
        $.post("/Agent/doRegAgent", formData, function (response) {
            if (response.code != "1"){ 
                baModalTipShow("错误", response.message, "d");
                $btn.html('保存');
                $btn.removeClass("disabled");
                return;
            }
            baModalTipShow("提示", "开通成功", "d", function () {
                baModalTipToggle();
                $("#div_reg").hide(); 
                $("#div_reg_ok").show(); 
                $("#AID").text(response.result.AID);
//                $("#PWD").text(response.result.PASSWORD);
                $("#PWD").text("888888");
                $("#CODE").text(response.result.SHARECODE);
            });
        });
    }
    
    function getVCode(){
        var $span = $('#span_vcode');
        if ($span.hasClass("disabled")) return;  
        $.post("/Agent/sendVCode", {phone:$("#CELLPHONE").val()}, function (response) {
            if (response.code != "1") {
                baModalTipShow("错误", response.message, "d");
            } else {
                baModalTipShow("提示", "【5分钟有效】" + response.result, "s", function () {
                    baModalTipToggle();
                });
            }
        }); 
    }
</script>
 