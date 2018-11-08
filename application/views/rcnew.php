<!--生成赠送房卡-->
<div class="box box-primary">
    <div class="box-body">
        <h3 class="box-title text-center"></h3> 
        <br/> 
        <div class="col-sm-3"></div>
        <div class="col-sm-6">
            <form id="rc-form" role="form" class="form-horizontal"> 
                <div class="form-group">
                    <label class="col-sm-3 control-label">生成数量</label>
                    <div class="col-sm-9">
                        <input id="NUM" name="NUM" type="number" class="form-control" >
                    </div>
                </div> 
                <div class="form-group">
                    <label class="col-sm-3 control-label">所在地区</label>
                    <div class="col-sm-3">
                        <select id="loc_province" name="areaProvince" class="form-control"> </select>
                    </div>
                    <div class="col-sm-3">
                        <select id="loc_city" name="areaCity" class="form-control" > </select>
                    </div>
                    <div class="col-sm-3">
                        <select id="loc_town" name="areaTown" class="form-control" > </select>
                    </div>
                </div>
                
                 <div class="form-group">
                    <label class="col-sm-3 control-label">原因</label>
                    <div class="col-sm-9">
                        <input id="MARK" name="MARK" placeholder="50字以内" type="text" class="form-control">
                    </div>
                </div>
                
            </form>
        </div>
        <div class="col-sm-3"></div>
    </div><!-- /.box-body -->
</div><!-- /.box -->

<button type="button" id="btn-save" class="btn btn-primary btn-lg ba-center" onclick="save();">
    保存
</button>

<script src="/static/js/location.js"></script>
<script type="text/javascript">
    $(function () {
        showLocation(0, 0, 0);
    });
    
    function save() {
        var $btn = $('#btn-save');
        if ($btn.hasClass("disabled")) return;

        $btn.addClass("disabled");
        $btn.html('<i class="fa fa-spinner fa-pulse"></i>提交中'); 
        
        // 验证必填项
        if ($("#RCOUNT").val() == "") {
            baModalTipShow("提示", "请填写生成数量", "d");
            $btn.html('保存');
            $btn.removeClass("disabled");
            return;
        }
//        if ($("#loc_province").val() == "" || $("#loc_city").val() == "" || $("#loc_town").val() == "") {
//            baModalTipShow("提示", "请选择地区", "d");
//            $btn.html('保存');
//            $btn.removeClass("disabled");
//            return;
//        }
        if ($("#MARK").val() == "") {
            baModalTipShow("提示", "请填写生成原因", "d");
            $btn.html('保存');
            $btn.removeClass("disabled");
            return;
        } 
        // 提交表单
        var formData = $("#rc-form").serializeArray();
        $.post("/AgentAdminRC/save", formData, function (response) {
            if (response.code != "1") {
                baModalTipShow("错误", response.message, "d");
                $btn.html('保存');
                $btn.removeClass("disabled");
                return;
            }
            baModalTipShow("提示", "保存成功"+response.message, "s", function () { 
                window.location = "/AgentAdminRC/rcnnew";
            });
        });
    }
</script>


