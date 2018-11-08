<div class="box box-primary">
    <div class="box-body">
<!--        <h3 class="box-title text-center">转账</h3>-->

        <br/>

        <div class="col-sm-3"></div>
        <div class="col-sm-6">
            <form id="client-form" role="form" class="form-horizontal"> 
                <div class="form-group">
                    <label class="col-sm-3 control-label">代理商ID</label>
                     <div class="col-sm-8">
                        <div class="input-group"> 
                            <input id="AID" name="AID" type="text"  class="form-control">
                            <span class="input-group-addon" id="span_vcode" name="span_vcode" onclick="getData();">搜索</span> 
                        </div> 
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"> 代理商信息</label>
                    <div class="col-sm-8">
                        <div class="panel-body">  
                            姓名：<label id="ANAME"><?= !empty($lowerAgent)?$lowerAgent["ANAME"]:""?></label><br/>
                            手机：<label id="CELLPHONE"><?= !empty($lowerAgent)?$lowerAgent["CELLPHONE"]:""?></label><br/>
                            微信：<label id="WXID"><?= !empty($lowerAgent)?$lowerAgent["WXID"]:""?></label><br/>
                            
                          </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-3 control-label">房卡数量</label> 
                    <div class="col-sm-8">
                        <div class="input-group"> 
                            <input id="cardnum" name="cardnum"  type="number" class="form-control">
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
    
    function changCard(){
        var money = $("#money").val();
        var num = money * 8;
        $("#cardnum").val(num);
        var pay = money * 0.85;
        $("#pay").val(pay); 
    }
    
    function transferRCard() {
        var $btn = $('#btn-save');
        if ($btn.hasClass("disabled")) return;

        $btn.addClass("disabled");
        $btn.html('<i class="fa fa-spinner fa-pulse"></i>转账中');

        // 验证必填项 
        if ($("#AID").val() == "") {
            baModalTipShow("提示", "请输入收款方的账号ID", "d");
            $btn.html('转账');
            $btn.removeClass("disabled");
            return;
        }
        if ($("#cardnum").val() == "") {
            baModalTipShow("提示", "转让房卡数量不可为空", "d");
            $btn.html('转账');
            $btn.removeClass("disabled");
            return;
        }

        // 提交表单
        $msg = "确认转账给用户【"+$("#AID").val()+"】"+ "<font color='red'>"+$("#cardnum").val()+"</font> 张房卡吗？";
        baModalWarningShow("警告", $msg, "q", function () {
            baModalWarningToggle();
            var formData = $("#client-form").serializeArray();
            $.post("/Agent/doTransferRCard", formData, function (response) {
                if (response.code != "1") {
                    baModalTipShow("错误", response.message, "d");
                    $btn.html('转账');
                    $btn.removeClass("disabled");
                    return;
                } 
                baModalTipShow("提示", "转账成功", "s", function () {
                    window.location = "/Agent/agentList";
                });
            });
        }); 
        $btn.html('转账');
        $btn.removeClass("disabled");
    }
    
    function getData(){
        var id = $("#AID").val(); 
        $.post("/Agent/getAgentById", {aid:id}, function (response) {
//                var aname = response.result.ANAME;
//                var cellphone = response.result.CELLPHONE;
//                var wxid = response.result.WXID;
                $("#ANAME").text(response.result.ANAME);
                $("#CELLPHONE").text(response.result.CELLPHONE);
                $("#WXID").text(response.result.WXID);
            });
    }
</script>

