<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                <div id="TOOLBAR" class="btn-group"> 
                    
                    <button title="新增资产" type="button" class="btn btn-default" onclick="javascript:window.location.href='/AssetC/newAsset'">
                        新增 
                    </button> 
                    <button title="修改资产" type="button" class="btn btn-default" onclick="editAsset();">
                        修改 
                    </button>
                </div>
                <table id="mainTable"
                       data-toggle="table"
                       data-url="/AssetC/getAsset"
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
                       data-export-options='{ "fileName": "固定资产数据" }'
                       data-toolbar="#TOOLBAR">
                    <thead>
                    <tr>
                        <th data-field="state" data-checkbox="true"></th>
                        <th data-field="assetid" data-align="center" > 资产ID</th>
                        <th data-field="assetcode" data-align="center" > 物品编码</th>
                        <th data-field="assetname" data-align="center" > 资产名称</th>
                        <th data-field="typename" data-align="center" > 分类</th>
                        <th data-field="brand"  data-align="center"  > 品牌</th>
                        <th data-field="size"  data-align="center"  >规格</th>
                        <th data-field="unitprice"  data-align="center"  >单价</th>
                        <th data-field="storenum"  data-align="center"  >库存数量</th>
                        <th data-field="isdisposable" data-align="center"> 是否易耗品</th>
                        <th data-field="ctime" data-align="center"> 入库日期</th>
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
        $('#mainTable').bootstrapTable('hideColumn', 'assetid');
    });
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

    function delAgent() {
        var rows = $('#mainTable').bootstrapTable('getSelections');
        if (rows.length != 1){
            baModalTipShow("提示", "请选择需要删除的供应商", "d", function () {
                baModalTipToggle();
            });
            return;
        }
        baModalWarningShow("警告", "是否确定删除所选记录", "q", function () {
            baModalWarningToggle();
            $.post("/Agent/delAgent", {aid: rows[0]["AID"]}, function (response) {
                if (response.code != "1") {
                    baModalTipShow("错误", response.message, "d");
                } else {
                    baModalTipShow("提示", "删除成功", "s", function () {
                        baModalTipToggle();
                        $('#mainTable').bootstrapTable('refresh');
                    });
                }
            });
        });
    }
</script>

