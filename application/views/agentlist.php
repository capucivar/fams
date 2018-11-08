<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                <div id="TOOLBAR" class="btn-group">
                    <!--
                    <a title="新增代理商" type="button" class="btn btn-default" href="/Agent/newAgent">
                        <i class="glyphicon glyphicon-plus">新增</i> 
                    </a>-->
                    <button title="修改代理商" type="button" class="btn btn-default" onclick="editAgent();">
                        修改 <!-- <i class="glyphicon glyphicon-edit"></i> -->
                    </button>
                    <button title="解绑" type="button" class="btn btn-default" onclick="unbind();">
                        解绑
<!--                        <i class="glyphicon glyphicon-trash"></i>-->
                    </button>
                    <button title="封号" type="button" class="btn btn-default" onclick="ban();">
                        封号
                    </button>
                    
<!--                    <button title="转房卡给下级代理商" type="button" class="btn btn-default" onclick="transferRCard();">
                        转账 
                    </button>-->
<!--                    <button title="分享推广" type="button" class="btn btn-default" onclick="mShare()">
                        分享推广
                    </button>-->
                </div>
                <table id="mainTable"
                       data-toggle="table"
                       data-url="/Agent/getAgentList"
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
                        <th data-field="AID" data-align="center"> 代理商ID</th>
                        <th data-field="ANAME" data-align="center"> 姓名</th> 
                        <th data-field="CELLPHONE" data-align="center" > 手机号</th>
                        <th data-field="WXID" data-align="center" > 微信ID</th>
                        <th data-field="UNO" data-align="center" > 游戏ID</th>
                        <th data-field="ROOMCARD"  data-align="center"  > 房卡余额</th>
                        <th data-field="LASTLOGIN"  data-align="center"  >最后一次登录时间</th>
                        <th data-field="MARK"  data-align="center"  >备注</th>
<!--                        <th data-field="AREA" data-align="center" data-formatter="areaFormatter"> 所在地区</th>-->
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
    
    function editAgent() {
        var rows = $('#mainTable').bootstrapTable('getSelections');
        if (rows.length != 1){
            baModalTipShow("提示", "请选择需要修改的供应商", "d", function () {
                baModalTipToggle();
            });
            return;
        }
        window.location = "/Agent/newAgent?aid=" + rows[0]["AID"];
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
    //转移房卡
    function transferRCard(){
        var rows = $('#mainTable').bootstrapTable('getSelections');
        if (rows.length != 1){
            baModalTipShow("提示", "请选择转账对象", "d", function () {
                baModalTipToggle();
            });
            return;
        }
        window.location = "/Agent/transferRCard?aid=" + rows[0]["AID"];
    }
    function areaFormatter(value, row, index) {
        return getLocationName(value);
    }
    //分享推广
    function mShare(){
        window.location = "/Agent/share";
    }
</script>

