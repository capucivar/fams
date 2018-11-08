
<link href="http://cdn.bootcss.com/bootstrap-select/2.0.0-beta1/css/bootstrap-select.css" rel="stylesheet">
<link href="http://cdn.bootcss.com/bootstrap-select/2.0.0-beta1/css/bootstrap-select.min.css" rel="stylesheet">
<script src="http://cdn.bootcss.com/bootstrap-select/2.0.0-beta1/js/bootstrap-select.js"></script>
<script src="http://cdn.bootcss.com/bootstrap-select/2.0.0-beta1/js/bootstrap-select.min.js"></script>


<div class="box box-primary">
    <div class="box-body">
        <h3 class="box-title text-center">用户信息</h3>

        <br/>
 
        <div class="col-sm-3"></div>
        <div class="col-sm-6">
            <form id="client-form" role="form" class="form-horizontal">
                <input id="PID" name="PID" type="text" hidden="hidden">
                <input id="GID" name="GID" type="text" hidden="hidden">
<!--                <div class="form-group">
                    <label class="col-sm-3 control-label">选择玩家</label>
                    <div class="col-sm-9">
                        <select id="player" class="selectpicker show-tick form-control" onchange="selectChange()" data-live-search="true">
                            <option value="0" selected >请选择</option>
                        </select>
                    </div>
                </div>-->
                <input id="GID" name="GID" type="hidden" class="form-control" >
                <div class="form-group">
                    <label class="col-sm-3 control-label">游戏ID</label>
                    <div class="col-sm-9">
                        <input id="UNO" name="UNO" type="text" class="form-control" readonly>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">微信ID</label>
                    <div class="col-sm-9">
                        <input id="WXID" name="WXID" type="text" class="form-control" readonly>
                    </div>
                </div> 
                
                <div class="form-group">
                    <label class="col-sm-3 control-label">手机号</label>
                    <div class="col-sm-9">
                        <input id="CELLPHONE" name="CELLPHONE" type="text" class="form-control" readonly>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-3 control-label">备注</label>
                    <div class="col-sm-9">
                        <input id="REMARK" name="REMARK" placeholder="50字以内" type="text" class="form-control">
                    </div>
                </div>
                
            </form>
        </div>
        <div class="col-sm-3"></div>
    </div><!-- /.box-body -->
</div><!-- /.box -->

<button type="button" id="btn-save" class="btn btn-primary btn-lg ba-center" onclick="saveUser();">
    保存
</button>
 
<script type="text/javascript">
    $(function () {  
//        var gusers = ' ';//$gusers
//        if (gusers != "") { 
//            var gusersObj = JSON.parse(gusers);
//            $.each(gusersObj, function (k, v) { 
//                console.info(v.name);
//                var option = '<option value="' + v.id + '">' + v.name + '</option>';
//                $("#player").append(option);
//            });
//        }
        
        var agentData = '<?= $user ?>';
        if (agentData != "") {
            var agentObj = JSON.parse(agentData);
            $.each(agentObj, function (k, v) { 
                $("#client-form input[name='" + k + "']").val(v);
//                if(k=="GID"){
//                    $("#player").val(v);
//                }
            });       
            $("h1").html("修改用户<small>" + $("h1 small").html() + "</small>");
        }
    });
    
    function saveUser() {
        var $btn = $('#btn-save');
        if ($btn.hasClass("disabled")) return;

        $btn.addClass("disabled");
        $btn.html('<i class="fa fa-spinner fa-pulse"></i>提交中');

        // 验证必填项
        if ($("#GID").val() == "") {
            baModalTipShow("提示", "游戏ID不可为空", "d");
            $btn.html('保存');
            $btn.removeClass("disabled");
            return;
        }
        if ($("#WXID").val() == "") {
            baModalTipShow("提示", "微信号不可为空", "d");
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

        // 提交表单
        var formData = $("#client-form").serializeArray();
        $.post("/UserC/saveUser", formData, function (response) {
            if (response.code != "1") {
                baModalTipShow("错误", response.message, "d");
                $btn.html('保存');
                $btn.removeClass("disabled");
                return;
            } 
            baModalTipShow("提示", "保存成功", "s", function () {
//                window.location = "/UserC";
                location.reload();
            });
        });
    }
    
    function selectChange(){ 
        $("#GID").val($('#player option:selected').val());
        $("#WXID").val($('#player option:selected').text());
    }
</script>

