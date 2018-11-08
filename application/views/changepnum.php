<div class="box box-primary">
    <div class="box-body">
        <h3 class="box-title text-center">修改手机号</h3>
        <br/> 
        <div class="col-sm-3"></div>
        <div class="col-sm-6">
            <form id="client-form" role="form" class="form-horizontal"> 
                <input id="CELLPHONE" name="CELLPHONE" type="hidden" value="<?= $CELLPHONE?>" >
                 
                
                <div class="form-group">
                    <label class="col-sm-3 control-label">新手机号</label>
                    <div class="col-sm-9">
                        <input id="NEWCELLPHONE" name="NEWCELLPHONE" class="form-control">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-3 control-label">验证码</label>
                     <div class="col-sm-9">
                        <div class="input-group"> 
                            <input id="VCODE" name="VCODE" type="text"  class="form-control">
                            <span class="input-group-addon" id="span_vcode" name="span_vcode" onclick="getVCode();">获取验证码</span> 
                        </div> 
                    </div>
                </div> 
                
            </form>
        </div>
        <div class="col-sm-3"></div>
    </div><!-- /.box-body -->
</div><!-- /.box -->

<button type="button" id="btn-save" class="btn btn-primary btn-lg ba-center" onclick="changePnum();">
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
        var formData = $("#client-form").serializeArray();
        $.post("/ChangePnumC/sendVCode", formData, function (response) {
            if (response.code != "1") {
                baModalTipShow("错误", response.message, "d");
            } else {
                baModalTipShow("提示", "【5分钟有效】" + response.result, "s", function () {
                    baModalTipToggle();
                });
            }
        }); 
    }
    function changePnum(){
        var $btn = $('#btn-save');
        if ($btn.hasClass("disabled")) return;

        $btn.addClass("disabled");
        $btn.html('<i class="fa fa-spinner fa-pulse"></i>提交中'); 
        
        var phone = $("#NEWCELLPHONE").val();
        if (phone.length!=11 || !(/^1[34578]\d{9}$/.test(phone))) {
            baModalTipShow("提示", "请输入有效的手机号", "d");
            $btn.html('保存');
            $btn.removeClass("disabled");
            return;
        }
        
        // 验证必填项 
        if ($("#VCODE").val().length != 6) {
            baModalTipShow("提示", "请输入6位验证码", "d");
            $btn.html('保存');
            $btn.removeClass("disabled");
            return;
        }
        
        var formData = $("#client-form").serializeArray();
        $.post("/ChangePnumC/changePnum", formData, function (response) {
            if (response.code != "1") {
                baModalTipShow("错误", response.message, "d");
                $btn.html('保存');
                $btn.removeClass("disabled");
                return;
            }
            baModalTipShow("提示", "手机号修改成功", "s", function () {
                window.location = "/Home";
            });
        });
    }

</script>

