<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                <div id="TOOLBAR" class="btn-group">
                    <button title="绑定码分享" type="button" class="btn btn-default" onclick="inviteCode()">
                        绑定码分享
                    </button>
<!--                    <a title="新增用户" type="button" class="btn btn-default" href="/UserC/newUser">
                        <i class="glyphicon glyphicon-plus"></i>
                    </a>-->
                    <button title="修改" type="button" class="btn btn-default" onclick="editUser();">
                        修改
                    </button>
                    <button title="解绑" type="button" class="btn btn-default" onclick="unbind();">
                        解绑 
                    </button>
                    <button title="封号" type="button" class="btn btn-default" onclick="ban();">
                        封号
                    </button>
<!--                    <button title="删除用户" type="button" class="btn btn-default" onclick="delUser();">
                        <i class="glyphicon glyphicon-trash"></i>
                    </button> -->
                    <button title="转房卡给用户" type="button" class="btn btn-default" onclick="transferRCard();">
                        转账
                    </button>
<!--                    <button title="封号申请" type="button" class="btn btn-default" onclick="forbidden();">
                        <i class="fa fa-remove"></i>
                    </button>-->
                </div>
                <table id="mainTable"
                       data-toggle="table"
                       data-url="/UserC/getUserList"
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
                        <th data-field="PID" data-align="center"> </th> 
                        <th data-field="UNO" data-align="center" > 玩家ID</th> 
                        <th data-field="NICKNAME" data-align="center" > 微信昵称</th>
                        <th data-field="ROOMCARD" data-align="center" > 房卡余额</th> 
                        <th data-field="LASTLOGIN" data-align="center" > 最后登录时间</th>
                        <th data-field="REMARK" data-align="center" > 备注</th> 
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
    $(function () {
        $('#mainTable').bootstrapTable('hideColumn', 'PID'); 
    });
    
    function inviteCode(){
        window.location = "/UserC/inviteCode";
    }
    function editUser() {
        var rows = $('#mainTable').bootstrapTable('getSelections');
        if (rows.length != 1){
            baModalTipShow("提示", "请选择需要修改的玩家", "d", function () {
                baModalTipToggle();
            });
            return;
        } 
        window.location = "/UserC/newUser?pid=" + rows[0]["PID"];
    }

    function delUser() {
        var rows = $('#mainTable').bootstrapTable('getSelections');
        if (rows.length != 1){
            baModalTipShow("提示", "请选择需要删除的玩家", "d", function () {
                baModalTipToggle();
            });
            return;
        }

        baModalWarningShow("警告", "是否确定删除所选记录", "q", function () {
            baModalWarningToggle();
            $.post("/UserC/delUser", {pid: rows[0]["PID"]}, function (response) {
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
    function forbidden() {
        var rows = $('#mainTable').bootstrapTable('getSelections');
        if (rows.length != 1) return;

        baModalWarningShow("警告", "是否确定申请禁止玩家【"+rows[0]["PID"]+"】的账号", "q", function () {
            baModalWarningToggle();
            $.post("/UserC/closeAccount", {pid: rows[0]["PID"]}, function (response) {
                if (response.code != "1") {
                    baModalTipShow("错误", response.message, "d");
                } else {
                    baModalTipShow("提示", "申请已提交", "s", function () {
                        baModalTipToggle();
                        $('#mainTable').bootstrapTable('refresh');
                    });
                }
            });
        });
    }
    //转移房卡
    function transferRCard(){
        var rows = $('#mainTable').bootstrapTable('getSelections');
        if (rows.length != 1){
            baModalTipShow("提示", "请选择转账对象", "d", function () {
                baModalTipToggle();
            });
            return;
        } 
        window.location = "/UserC/transferRCard?pid=" + rows[0]["PID"];
    }
    
    function unbind() { 
        baModalTipShow("警告", "您没有权限解绑账号，请联系客服", "w", function () {
            baModalTipToggle();
        });
    }
    
    function ban(){ 
        baModalTipShow("警告", "您没有权限封号，请联系客服", "w", function () {
            baModalTipToggle();
        });
    }
    
</script>

