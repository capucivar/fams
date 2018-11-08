<div class="box box-primary">
    <div class="box-body">
        <h3 class="box-title text-center">发布公告</h3>

        <br/>

        <div class="col-sm-3"></div>
        <div class="col-sm-6">
            <form id="client-form" role="form" class="form-horizontal">
                <input id="NID" name="NID" type="text" hidden="hidden">
                <div class="form-group">
                    <label class="col-sm-3 control-label">公告内容</label>
                    <div class="col-sm-9"> 
                        <textarea id="CONTENT" name="CONTENT" class="form-control" rows="3"></textarea> 
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">发布级别</label>
                    <div class="col-sm-9">
                        <select id="NLEVEL" name="NLEVEL" class="form-control">
                            <option value="0">请选择</option>
                            <option value="1">系统公告</option>
                            <option value="2">发给下级</option>
                        </select>
                    </div>
                </div>
                
            </form>
        </div>
        <div class="col-sm-3"></div>
    </div><!-- /.box-body -->
</div><!-- /.box -->

<button type="button" id="btn-save" class="btn btn-primary btn-lg ba-center" onclick="saveNotice();">
    保存
</button>

<script src="/static/js/location.js"></script>
<script type="text/javascript">
    $(function () {  
        var noticeData = '<?= $notice?>';
        if (noticeData != "") {
            var agentObj = JSON.parse(noticeData);
            $("h1").html("修改公告<small>" + $("h1 small").html() + "</small>");
            $("#NID").val(agentObj["NID"]); 
            $("#CONTENT").val(agentObj["CONTENT"]); 
            $("#NLEVEL").val(agentObj["NLEVEL"]); 
        } 
    });
    
    function saveNotice() {
        var $btn = $('#btn-save');
        if ($btn.hasClass("disabled")) return;

        $btn.addClass("disabled");
        $btn.html('<i class="fa fa-spinner fa-pulse"></i>提交中');

        // 验证必填项
        if ($("#CONTENT").val() == "") {
            baModalTipShow("提示", "请填写公告内容", "d");
            $btn.html('保存');
            $btn.removeClass("disabled");
            return;
        }
        if ($("#NLEVEL").val() == "0") {
            baModalTipShow("提示", "请选择公告级别", "d");
            $btn.html('保存');
            $btn.removeClass("disabled");
            return;
        }

        // 提交表单
        var formData = $("#client-form").serializeArray();
        $.post("/NoticeC/saveNotice", formData, function (response) {
            if (response.code != "1") {
                baModalTipShow("错误", response.message, "d");
                $btn.html('保存');
                $btn.removeClass("disabled");
                return;
            } 
            baModalTipShow("提示", "保存成功", "s", function () {
                window.location = "/NoticeC";
            });
        });
    }
 
</script>

