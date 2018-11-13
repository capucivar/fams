<div class="box box-primary">
    <div class="box-body"> 
        <br/> 
        <div class="col-sm-3"></div>
        <div class="col-sm-6">
            <form id="client-form" role="form" class="form-horizontal"> 
                <div class="form-group">
                    <label class="col-sm-3 control-label">手机号</label>
                     <div class="col-sm-9">
                         <input id="phone" name="phone" class="form-control" value="<?= $baseInfo["phone"]?>" readonly="true"> 
                    </div>
                </div> 
                <div class="form-group">
                    <label class="col-sm-3 control-label">验证码</label>
                     <div class="col-sm-9">
                        <div class="input-group"> 
                            <input id="vcode" name="vcode" type="text"  class="form-control">
                            <span class="input-group-addon" id="span_vcode" name="span_vcode" onclick="getVCode();">获取验证码</span> 
                        </div> 
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">旧密码</label>
                    <div class="col-sm-9">
                        <input id="oldpwd" name="oldpwd" type="password" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">新密码</label>
                    <div class="col-sm-9">
                        <input id="newpwd" name="newpwd" type="password" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">确认新密码</label>
                    <div class="col-sm-9">
                        <input id="newpwd2" name="newpwd2" type="password" class="form-control">
                    </div>
                </div>
            </form>
        </div>
        <div class="col-sm-3"></div>
    </div><!-- /.box-body -->
</div><!-- /.box -->

<button type="button" id="btn-save" class="btn btn-primary btn-lg ba-center" onclick="changePwd();">
    修改
</button> 

<script src="/static/js/location.js"></script>
<script type="text/javascript">
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
        time();
        $.post("/ChangePwdC/sendVCode", {}, function (response) {
            if (response.code != "1") {
                baModalTipShow("错误", response.message, "d");
            } else {
                baModalTipShow("提示", "【5分钟有效】" + response.result, "s", function () {
                    baModalTipToggle();
                });
            }
        }); 
    }
    function changePwd(){
        var $btn = $('#btn-save');
        if ($btn.hasClass("disabled")) return;

        $btn.addClass("disabled");
        $btn.html('<i class="fa fa-spinner fa-pulse"></i>提交中'); 
        
        // 验证必填项
        if ($("#vcode").val() == "") {
            baModalTipShow("提示", "请输入6位验证码", "d");
            $btn.html('保存');
            $btn.removeClass("disabled");
            return;
        }
        if ($("#newpwd").val() == "") {
            baModalTipShow("提示", "请输入新密码", "d");
            $btn.html('保存');
            $btn.removeClass("disabled");
            return;
        }
        if ($("#newpwd").val() != $("#newpwd2").val()) {
            baModalTipShow("提示", "新密码与确认密码输入不一致", "d");
            $btn.html('保存');
            $btn.removeClass("disabled");
            return;
        }
        var formData = $("#client-form").serializeArray();
        $.post("/ChangePwdC/changePwd", formData, function (response) {
            if (response.code != "1") {
                baModalTipShow("错误", response.message, "d");
                $btn.html('保存');
                $btn.removeClass("disabled");
                return;
            }
            baModalTipShow("提示", "密码修改成功", "s", function () {
                window.location = "/home/signout";
            });
        });
    }

</script>

