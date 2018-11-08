<div class="box box-primary">
    <div class="box-body"> 
        <br/>
        <div class="col-sm-2"></div>
        <div class="col-sm-7">
            <form id="client-form" role="form" class="form-horizontal"> 
                <div class="form-group">
                    <label class="col-sm-2 control-label">物品编码</label>
                    <div class="col-sm-4">
                        <input id="ANAME" name="ANAME" type="text" class="form-control" >
                    </div>
                    
                    <label class="col-sm-2 control-label">物品名称</label>
                    <div class="col-sm-4">
                        <input id="WXID" name="WXID" type="text" class="form-control"  >
                    </div>
                </div> 
                <div class="form-group">
                    <label class="col-sm-2 control-label">分类</label>
                    <div class="col-sm-10"> 
                        <select id="loc_province" name="areaProvince" class="form-control"> </select> 
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">品牌</label>
                    <div class="col-sm-4">
                        <input id="CELLPHONE" name="CELLPHONE" type="text" class="form-control" >
                    </div>
                     <label class="col-sm-2 control-label">规格</label>
                    <div class="col-sm-4">
                        <input id="UNO" name="UNO" type="text" class="form-control" >
                    </div>
                </div> 
                <div class="form-group">
                    <label class="col-sm-2 control-label">库存数量</label>
                    <div class="col-sm-4">
                        <input id="CELLPHONE" name="CELLPHONE" type="text" class="form-control" >
                    </div>
                     <label class="col-sm-2 control-label">单价</label>
                    <div class="col-sm-4">
                        <input id="UNO" name="UNO" type="text" class="form-control" >
                    </div>
                </div>
                 <div class="form-group">
                    <label class="col-sm-2 control-label">备注</label>
                    <div class="col-sm-10">
                        <input id="REMARK" name="MARK" placeholder="50字以内" type="text" class="form-control">
                    </div>
                </div>
                
            </form>
        </div>
        <div class="col-sm-3"></div>
    </div><!-- /.box-body -->
</div><!-- /.box -->

<button type="button" id="btn-save" class="btn btn-primary btn-lg ba-center" onclick="saveAgent();">
    保存入库
</button>

<script src="/static/js/location.js"></script>
<script type="text/javascript">
    $(function () { 
        var agentData = '<?= $lowerAgent?>';
        var locs = [0, 0, 0];
        if (agentData != "") { 
            var agentObj = JSON.parse(agentData);
            $.each(agentObj, function (k, v) {
                console.info(k);
                console.info(v);
                $("#client-form input[name='" + k + "']").val(v);
            });
            $("#type").val(agentObj["type"]);
            if(agentObj["AREA"]!=null){
                locs = agentObj["AREA"].split(",");
                if(locs.length!=3)
                    locs = [0, 0, 0];
            }
            $("h1").html("修改代理商<small>" + $("h1 small").html() + "</small>");
            $("#ANAME").attr("readonly",true);
            $("#WXID").attr("readonly",true);
            $("#WXNICKNAME").attr("readonly",true);
            $("#CELLPHONE").attr("readonly",true);
            $("#UNO").attr("readonly",true);
            $('select').prop('disabled',true); 
        }
        showLocation(locs[0], locs[1], locs[2]);
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
        if ($("#WXNICKNAME").val() == "") {
            baModalTipShow("提示", "微信昵称不可为空", "d");
            $btn.html('保存');
            $btn.removeClass("disabled");
            return;
        }
        if ($("#CELLPHONE").val() == "") {
            baModalTipShow("提示", "手机号不可为空", "d");
            $btn.html('保存');
            $btn.removeClass("disabled");
            return;
        }
        if ($("#CELLPHONE").val().length!=11) {
            baModalTipShow("提示", "手机号码位数不正确", "d");
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
        $.post("/Agent/saveAgent", formData, function (response) {
            if (response.code != "1") {
                baModalTipShow("错误", response.message, "d");
                $btn.html('保存');
                $btn.removeClass("disabled");
                return;
            }
            baModalTipShow("提示", "保存成功", "s", function () {
                var l = '<?= $agent["LEVEL"]?>';
                var a = '<?= SysDict::$ANGENTLEVEL["admin"]?>';
                var h = '<?= SysDict::$ANGENTLEVEL["head"]?>';
                if(l==a || l==h)
                    window.location = "/AgentAdmin/fagentList";
                else
                    window.location = "/Agent/newAgentInfo/"+$("#UNO").val();
            });
        });
    }
</script>

