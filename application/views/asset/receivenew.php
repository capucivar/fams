
<div class="box box-primary">
    <div class="box-body"> 
        <br/>
        <div class="col-sm-2"></div>
        <div class="col-sm-7">
            <form id="mForm" role="form" class="form-horizontal"> 
                <div class="form-group">
                    <label class="col-sm-2 control-label">物品名称</label>
                    <div class="col-sm-10">
                        <select class="selectpicker" data-live-search="true" id="assetid" name="assetid" >
                            <option value="0" >请选择</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">领取人</label>
                    <div class="col-sm-10">
                        <select class="selectpicker" data-live-search="true" id="userid" name="userid" >
                            <option value="0" >请选择</option>
                        </select> 
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">领取数量</label>
                    <div class="col-sm-10">
                        <input id="num" name="num" type="number" class="form-control" value="<?=$form['num']?>"  >
                    </div> 
                </div>  
                 <div class="form-group">
                    <label class="col-sm-2 control-label">领取说明</label>
                    <div class="col-sm-10">
                        <input id="note" name="note" placeholder="50字以内" type="text" class="form-control" value="<?=$form['note']?>" >
                    </div>
                </div> 
            </form>
        </div> 
    </div><!-- /.box-body -->
</div><!-- /.box -->

<button type="button" id="btn-save" class="btn btn-primary btn-lg ba-center" onclick="save();">
    保存
</button>


<link href="/static/plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">
<script type="text/javascript" src="/static/plugins/bootstrap-select/bootstrap-select.js"></script>

<script type="text/javascript"> 
    $(function () { 
        bindAssetlist();
        bindUserlist(); 
    });
    //绑定物品名称
    function bindAssetlist() {
        $.post("/AssetC/getAsset", {}, function (response) {
            var data = JSON.parse(response);
            $.each(data.data, function (i, n) { 
                var selected="";
                $("#assetid").append(" <option value='"+ n.assetid +"' "+ selected+" >" + n.assetname + "（ _ " + n.typename+"）" + "</option>");
            });
            $("#assetid").selectpicker('refresh');
        });
    }
    function bindUserlist() {
        $.post("/UserC/getUserList", {}, function (response) {
            var data = JSON.parse(response);
            $.each(data.data, function (i, n) { 
                var selected="";
                $("#userid").append(" <option value='"+ n.userid +"' "+ selected+" >" + n.usercode +"_"+ n.username + "</option>");
            });
            $("#userid").selectpicker('refresh');
        }); 
    }
    function save() {
        var $btn = $('#btn-save');
        if ($btn.hasClass("disabled")) return;

        $btn.addClass("disabled");
        $btn.html('<i class="fa fa-spinner fa-pulse"></i>提交中');

        // 验证必填项
        if ($("#assetid").val() == 0) {
            baModalTipShow("提示", "请选择物品", "d");
            $btn.html('保存');
            $btn.removeClass("disabled");
            return;
        } 
        if ($("#userid").val() == "") {
            baModalTipShow("提示", "请选择领用人", "d");
            $btn.html('保存');
            $btn.removeClass("disabled");
            return;
        }
        if ($("#num").val() == "") {
            baModalTipShow("提示", "请输入领取数量", "d");
            $btn.html('保存');
            $btn.removeClass("disabled");
            return;
        } 

        // 提交表单
        var formData = $("#mForm").serializeArray(); 
        $.post("/ReceiveC/save", formData, function (response) {
            if (response.code != "1") {
                baModalTipShow("错误", response.message, "d");
                $btn.html('保存');
                $btn.removeClass("disabled");
                return;
            }
            baModalTipShow("提示", "保存成功", "s", function () {
                window.location = "/ReceiveC";
            });
        });
    }
</script>

