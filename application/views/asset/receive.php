<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                <div id="TOOLBAR" class="btn-group">  
                    <button title="新增领用单" type="button" class="btn btn-default" onclick="javascript:window.location.href='/ReceiveC/newform'">
                        新增领用单
                    </button>
                    <button title="废除领用单" type="button" class="btn btn-default" onclick="del();">
                        废除领用单
                    </button>
                    <button title="资产归还" type="button" class="btn btn-default" onclick="back();">
                        资产归还
                    </button>
                </div>
                <table id="mainTable"
                       data-toggle="table"
                       data-url="/ReceiveC/getList"
                       data-pagination="true"
                       data-side-pagination="client"
                       data-page-list="[5, 10, 20, 50, 100, 200]"
                       data-search="true"
                       data-show-refresh="true"
                       data-show-columns="true"
                       data-advanced-search="false"
                       data-single-select="true"
                       data-id-table="advancedTable"
                       data-click-to-select="true"
                       data-show-export="true"
                       data-export-types="['csv']"
                       data-export-options='{ "fileName": "资产领用单" }'
                       data-toolbar="#TOOLBAR">
                    <thead>
                    <tr>
                        <th data-field="state" data-checkbox="true"></th>
                        <th data-field="formid" data-align="center" > ID</th>
                        <th data-field="assetid" data-align="center" > AID</th>
                        <th data-field="assetcode" data-align="center" > 物品编码</th>
                        <th data-field="assetname" data-align="center" > 物品名称</th>
                        <th data-field="brand"  data-align="center"  > 品牌</th>
                        <th data-field="size"  data-align="center"  >规格</th> 
                        <th data-field="isdisposable" data-align="center" data-formatter="yihaoFormatter">是否易耗</th>
                        <th data-field="username" data-align="center" > 领取人</th>
                        <th data-field="num"  data-align="center"  >领取数量</th> 
                        <th data-field="ctime" data-align="center"> 领取日期</th>
                        <th data-field="status" data-align="center" data-formatter="stateFormatter">状态</th>
                        <th data-field="note"  data-align="center"  >备注</th> 
                    </tr>
                    </thead>
                </table>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col -->
</div><!-- /.row -->

<!-- Bootstrap TABES SCRIPT -->
<link href="/static/plugins/bootstrap-table/bootstrap-table.css" rel="stylesheet">
<script src="/static/plugins/bootstrap-table/bootstrap-table.js" type="text/javascript"></script>
<script src="/static/plugins/bootstrap-table/locale/bootstrap-table-zh-CN.js" type="text/javascript"></script>
<script src="/static/plugins/bootstrap-table/extensions/toolbar/bootstrap-table-toolbar.js" type="text/javascript"></script>

<script src="/static/plugins/bootstrap-table/extensions/export/bootstrap-table-export.js"></script>
<script src="/static/plugins/bootstrap-table/extensions/export/tableExport.js"></script>

<script type="text/javascript">
    $(function () {
        $('#mainTable').bootstrapTable('hideColumn', 'formid'); 
        $('#mainTable').bootstrapTable('hideColumn', 'assetid'); 
    }); 
    function yihaoFormatter(value, row, index) {
        switch(value){
            case "0":
                return "否";
            case "1":
                return "是";
        }
    }
    function stateFormatter(value, row, index) {
        switch(value){
            case "0":
                return "-";
            case "1":
                return "已领取";
            case "2":
                return "已归还";
        }
    }
    function editAsset() {
        var rows = $('#mainTable').bootstrapTable('getSelections');
        if (rows.length != 1){
            baModalTipShow("提示", "请选择需要修改的资产", "d", function () {
                baModalTipToggle();
            });
            return;
        }
        window.location = "/AssetC/newAsset?id=" + rows[0]["assetid"];
    }

    function del() {
        var rows = $('#mainTable').bootstrapTable('getSelections');
        if (rows.length != 1){
            baModalTipShow("提示", "请选择需要删除的数据", "d", function () {
                baModalTipToggle();
            });
            return;
        }
        baModalWarningShow("警告", "是否确定删除所选数据", "q", function () {
            baModalWarningToggle();
            $.post("/ReceiveC/delete", {formid: rows[0]["formid"]}, function (response) {
                if (response.code != "1") {
                    baModalTipShow("错误", response.message, "d");
                } else {
                    baModalTipShow("提示", "操作成功", "s", function () {
                        baModalTipToggle();
                        $('#mainTable').bootstrapTable('refresh');
                    });
                }
            });
        });
    }
    function back() {
        var rows = $('#mainTable').bootstrapTable('getSelections');
        if (rows.length != 1){
            baModalTipShow("提示", "请选择需要归还的资产", "d", function () {
                baModalTipToggle();
            });
            return;
        } 
        if(rows[0]["isdisposable"]=="1"){
            baModalTipShow("提示", "所选资产为易耗品，不能归还", "d", function () {
                baModalTipToggle();
            });
            return;
        }
        baModalWarningShow("警告", "确定归还所选资产？", "q", function () {
            baModalWarningToggle();
            var formid = rows[0]["formid"];
            var assetid = rows[0]["assetid"];
            var num = rows[0]["num"];
            $.post("/ReceiveC/back", {formid: formid,assetid:assetid,num:num}, function (response) {
                if (response.code != "1") {
                    baModalTipShow("错误", response.message, "d");
                } else {
                    baModalTipShow("提示", "操作成功", "s", function () {
                        baModalTipToggle();
                        $('#mainTable').bootstrapTable('refresh');
                    });
                }
            });
        });
    }
</script>

