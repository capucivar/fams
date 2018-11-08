<div class="box box-primary">
    <div class="box-body">
        <h3 class="box-title text-center">转账给玩家</h3>

        <br/>

        <div class="col-sm-3"></div>
        <div class="col-sm-6">
            <form id="client-form" role="form" class="form-horizontal"> 
                <input id="PID" name="PID" type="text" hidden="hidden">
                <div class="form-group">
                    <label class="col-sm-3 control-label">游戏玩家ID</label>
                    <div class="col-sm-8">
                        <input id="UNO" name="UNO" type="text" class="form-control" readonly="true" >
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-3 control-label">微信昵称</label>
                    <div class="col-sm-8">
                        <input id="NICKNAME" name="NICKNAME" type="text" class="form-control" readonly="true" >
                    </div>
                </div>
<!--                <div class="form-group">
                    <label class="col-sm-3 control-label">手机号</label>
                    <div class="col-sm-8">
                        <input id="CELLPHONE" name="CELLPHONE" type="text" class="form-control" readonly="true" >
                    </div>
                </div>-->
                <div class="form-group">
                    <label class="col-sm-3 control-label">房卡数量</label> 
                    <div class="col-sm-8">
                        <div class="input-group"> 
                            <input id="cardnum" name="cardnum"  type="number" class="form-control" >
                            <span class="input-group-addon">房卡</span>
                        </div> 
                    </div>
                </div>  
            </form>
        </div>
        <div class="col-sm-3"></div>
    </div><!-- /.box-body -->
</div><!-- /.box -->
<button type="button" id="btn-save" class="btn btn-primary btn-lg ba-center" onclick="transferRCard();">
    转账
</button>

<script src="/static/js/location.js"></script>
<script type="text/javascript"> 
    $(function () { 
        var agentData = '<?= $user ?>';
        if (agentData != "") {
            var agentObj = JSON.parse(agentData);
            $.each(agentObj, function (k, v) {
                console.info(k);
                console.info(v);
                $("#client-form input[name='" + k + "']").val(v);
            });
        } 
    });

    function transferRCard() {
        var $btn = $('#btn-save');
        if ($btn.hasClass("disabled")) return;

        $btn.addClass("disabled");
        $btn.html('<i class="fa fa-spinner fa-pulse"></i>转账中');

        // 验证必填项 
        if ($("#cardnum").val() == "") {
            baModalTipShow("提示", "房卡数量不可为空", "d");
            $btn.html('转账');
            $btn.removeClass("disabled");
            return;
        }
        
        // 提交表单 
        var msg = "请确认以下转账信息<br/><span style='font-size:10px'>&nbsp;&nbsp;&nbsp;&nbsp;转账账号："+$("#GID").val()
                +"<br/>&nbsp;&nbsp;&nbsp;&nbsp;微信ID："+$("#WXID").val() 
                +"<br/>&nbsp;&nbsp;&nbsp;&nbsp;转账房卡数量："+$("#cardnum").val()+"</span>";
        
        baModalWarningShow("警告", msg, "q", function () {
            baModalWarningToggle();
            var formData = $("#client-form").serializeArray();
            $.post("/UserC/doTransferRCard", formData, function (response) {
                if (response.code != "1") {
                    baModalTipShow("错误", response.message, "d");
                    $btn.html('转账');
                    $btn.removeClass("disabled");
                    return;
                }
                baModalTipShow("提示", "转账成功", "s", function () {
                    window.location = "/UserC";
                });
            });
        });
        $btn.html('转账');
        $btn.removeClass("disabled");
         
    }
</script>

