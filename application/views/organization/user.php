<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                <div id="TOOLBAR" class="btn-group">

                    <button title="新增员工" type="button" class="btn btn-default" onclick="javascript:window.location.href='/UserC/newUser'">
                        新增
                    </button>
                    <button title="修改员工" type="button" class="btn btn-default" onclick="editUser();">
                        修改
                    </button>
                    <button title="删除员工" type="button" class="btn btn-default" onclick="delUser();">
                        删除
                    </button>
                </div>
                <table id="mainTable"
                       data-toggle="table"
                       data-url="/UserC/getUserList"
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
                        <th data-field="userid" data-align="center"> userid</th>
                        <th data-field="usercode" data-align="center"> 员工编号</th>
                        <th data-field="username" data-align="center"> 姓名</th>
                        <th data-field="gender" data-align="center" > 性别</th>
                        <th data-field="phone" data-align="center" > 电话</th>
                        <th data-field="email" data-align="center" > 邮箱</th>
                        <th data-field="isadmin"  data-align="center"  > 是否为管理员</th>
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
        $('#mainTable').bootstrapTable('hideColumn', 'userid');
    });
    function editUser() {
        var rows = $('#mainTable').bootstrapTable('getSelections');
        if (rows.length != 1){
            baModalTipShow("提示", "请选择需要修改的员工", "d", function () {
                baModalTipToggle();
            });
            return;
        }
        window.location = "/UserC/newUser?id=" + rows[0]["userid"];
    }
    function delUser() {
        var rows = $('#mainTable').bootstrapTable('getSelections');
        if (rows.length != 1){
            baModalTipShow("提示", "请选择需要删除的员工", "d", function () {
                baModalTipToggle();
            });
            return;
        }
        var userid = rows[0]["userid"];
        baModalWarningShow("警告", "是否确定删除所选记录", "q", function () {
            baModalWarningToggle();
            $.post("/UserC/delUser", {userid: userid}, function (response) {
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

