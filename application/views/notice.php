<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                <div id="TOOLBAR" class="btn-group">
                    <a title="发布公告" type="button" class="btn btn-default" href="/NoticeC/notice">
                        <i class="glyphicon glyphicon-plus"></i>
                    </a>
                    <button title="修改公告" type="button" class="btn btn-default" onclick="editNotice();">
                        <i class="glyphicon glyphicon-edit"></i>
                    </button>
                    <button title="删除公告" type="button" class="btn btn-default" onclick="delNotice();">
                        <i class="glyphicon glyphicon-trash"></i>
                    </button>
                </div>
                <table id="mainTable"
                       data-toggle="table"
                       data-url="/NoticeC/noticeList"
                       data-pagination="true"
                       data-side-pagination="server"
                       data-page-list="[5, 10, 20, 50, 100, 200]"
                       data-search="true"
                       data-show-refresh="true"
                       data-show-columns="true"
                       data-advanced-search="false"
                       data-single-select="true"
                       data-id-table="advancedTable"
                       data-click-to-select="true"
                       data-toolbar="#TOOLBAR">
                    <thead>
                    <tr>
                        <th data-field="state" data-checkbox="true"></th>
                        <th data-field="CONTENT" data-align="center"> 公告</th>
                        <th data-field="CTIME"class="col-sm-2" data-align="center"> 发布时间</th>
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

<script src="/static/js/location_dict.js"></script>

<script type="text/javascript">
    
    function editNotice() {
        var rows = $('#mainTable').bootstrapTable('getSelections');
        if (rows.length != 1) return;

        window.location = "/NoticeC/notice?nid=" + rows[0]["NID"];
    }

    function delNotice() {
        var rows = $('#mainTable').bootstrapTable('getSelections');
        if (rows.length != 1) return;

        baModalWarningShow("警告", "是否确定删除所选记录", "q", function () {
            baModalWarningToggle();
            $.post("/NoticeC/delNotice", {nid: rows[0]["NID"]}, function (response) {
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

