<?php
//    print_r($asset);
?>
<div class="box box-primary">
    <div class="box-body"> 
        <br/>
        <div class="col-sm-2"></div>
        <div class="col-sm-7">
            <form id="assetForm" role="form" class="form-horizontal">
                <input id="assetid" name="assetid" type="text" class="form-control" value="<?=$asset['assetid']?>" style="display: none;" >
                <div class="form-group">
                    <label class="col-sm-2 control-label">分类</label>
                    <div class="col-sm-10">
                        <select class="selectpicker" data-live-search="true" id="assetType" name="assetType" onchange="selectOnchange(this)">
                            <option value="0" >请选择</option>
                        </select>&nbsp;&nbsp;
                        <select class="selectpicker" data-live-search="true" id="assetCType" name="assetCType" onchange="selectOnchange2(this)">
                            <option value="0" >请选择</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">物品编码</label>
                    <div class="col-sm-4">
                        <input id="assetcode" name="assetcode" type="text" class="form-control" value="<?=$asset['assetcode']?>" >
                    </div>
                    
                    <label class="col-sm-2 control-label">物品名称</label>
                    <div class="col-sm-4">
                        <input id="assetname" name="assetname" type="text" class="form-control"  value="<?=$asset['assetname']?>"  >
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">品牌</label>
                    <div class="col-sm-4">
                        <input id="brand" name="brand" type="text" class="form-control" value="<?=$asset['brand']?>"  >
                    </div>
                     <label class="col-sm-2 control-label">规格</label>
                    <div class="col-sm-4">
                        <input id="size" name="size" type="text" class="form-control" value="<?=$asset['size']?>"  >
                    </div>
                </div> 
                <div class="form-group">
                    <label class="col-sm-2 control-label">库存数量</label>
                    <div class="col-sm-4">
                        <input id="storenum" name="storenum" type="text" class="form-control" value="<?=$asset['storenum']?>"  >
                    </div>
                     <label class="col-sm-2 control-label">单价</label>
                    <div class="col-sm-4">
                        <input id="unitprice" name="unitprice" type="number" class="form-control" value="<?=$asset['unitprice']?>"  >
                    </div>
                </div>
                 <div class="form-group">
                    <label class="col-sm-2 control-label">备注</label>
                    <div class="col-sm-10">
                        <input id="note" name="note" placeholder="50字以内" type="text" class="form-control" value="<?=$asset['note']?>" >
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">是否易耗品</label>
                    <div class="col-sm-10">
                        <input type="checkbox" id="isdisposable" >
                    </div>
                </div>
            </form>
        </div>
        <div class="col-sm-3"></div>
    </div><!-- /.box-body -->
</div><!-- /.box -->

<button type="button" id="btn-save" class="btn btn-primary btn-lg ba-center" onclick="saveAsset();">
    保存入库
</button>


<link href="/static/plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">
<script type="text/javascript" src="/static/plugins/bootstrap-select/bootstrap-select.js"></script>

<script type="text/javascript">
    var typeid = <?= empty($asset["typeid"])?0:$asset["typeid"]?>;
    var typeid2 = <?= empty($asset["typeid2"])?0:$asset["typeid2"]?>;
    var selectLevel = typeid==typeid2?1:2;
    var isdisposable = <?= empty($asset["isdisposable"])?0:$asset["isdisposable"]?>;
    $(function () {
        $("#assetType").selectpicker({
            width:260
        });

        $("#assetCType").selectpicker({
            width:260
        });
        bindTypelist();
        bindChildTypelist(typeid2);
        if (isdisposable=="1")
            $("#isdisposable").attr("checked",true);
    });
    function bindTypelist() {
        $.post("/AssetC/getParentType", {}, function (response) {
            var data = JSON.parse(response);
            $.each(data.rows, function (i, n) {
                var selected="";
                if (typeid2==n.typeid)
                    selected=" selected";
                $("#assetType").append(" <option value='"+ n.typeid +"' "+ selected+" >" + n.typename + "</option>");
            })
            $("#assetType").selectpicker('refresh');
        });
    }
    function bindChildTypelist(parentid) {
        $("#assetCType").empty();
        $("#assetCType").append('<option value="0">请选择</option>');
        $.post("/AssetC/getChildType", {parentid:parentid}, function (response) {
            var data = JSON.parse(response);
            $.each(data.rows, function (i, n) {
                var selected="";
                if (selectLevel==2 && typeid==n.typeid)
                    selected=" selected";
                $("#assetCType").append(" <option value='"+ n.typeid +"' "+ selected+" >" + n.typename + "</option>");
            })
            $("#assetCType").selectpicker('refresh');
        });

    }
    function selectOnchange(obj) {
        var id = obj.value;
        bindChildTypelist(id);
        if ($("#assetid").val()!="")
            return;
        $.post("/AssetC/getAssetCode", {typeid:id}, function (response) {
            $("#assetcode").val(response);
        });
    }
    function selectOnchange2(obj) {
        if ($("#assetid").val()!="")
            return;
        var id = obj.value;
        $.post("/AssetC/getAssetCode", {typeid:id}, function (response) {
            $("#assetcode").val(response);
        });
    }
    function saveAsset() {
        var $btn = $('#btn-save');
        if ($btn.hasClass("disabled")) return;

        $btn.addClass("disabled");
        $btn.html('<i class="fa fa-spinner fa-pulse"></i>提交中');

        // 验证必填项
        if ($("#assetType").val() == 0) {
            baModalTipShow("提示", "请选择类别", "d");
            $btn.html('保存');
            $btn.removeClass("disabled");
            return;
        } 
        if ($("#assetcode").val() == "") {
            baModalTipShow("提示", "请输入资产编码", "d");
            $btn.html('保存');
            $btn.removeClass("disabled");
            return;
        }
        if ($("#assetname").val() == "") {
            baModalTipShow("提示", "请输入资产名称", "d");
            $btn.html('保存');
            $btn.removeClass("disabled");
            return;
        }
        if ($("#brand").val() == "") {
            baModalTipShow("提示", "请输入品牌", "d");
            $btn.html('保存');
            $btn.removeClass("disabled");
            return;
        }
        if ($("#size").val() == "") {
            baModalTipShow("提示", "请输入规格", "d");
            $btn.html('保存');
            $btn.removeClass("disabled");
            return;
        }
        if ($("#storenum").val() == "") {
            baModalTipShow("提示", "请输入库存数量", "d");
            $btn.html('保存');
            $btn.removeClass("disabled");
            return;
        }
        if ($("#unitprice").val() == "") {
            baModalTipShow("提示", "请输入单价", "d");
            $btn.html('保存');
            $btn.removeClass("disabled");
            return;
        }

        // 提交表单
        var formData = $("#assetForm").serializeArray();
        formData[formData.length]={name:"typeid",value:$("#assetCType").val()==0?$("#assetType").val():$("#assetCType").val()};
        formData[formData.length]={name:"typeid2",value:$("#assetCType").val()==0?$("#assetCType").val():$("#assetType").val()};
        formData[formData.length]={name:"isdisposable",value:$("#isdisposable").is(":checked")?1:0};
        $.post("/AssetC/saveAsset", formData, function (response) {
            if (response.code != "1") {
                baModalTipShow("错误", response.message, "d");
                $btn.html('保存');
                $btn.removeClass("disabled");
                return;
            }
            baModalTipShow("提示", "保存成功", "s", function () {
                window.location = "/AssetC";
            });
        });
    }
</script>

