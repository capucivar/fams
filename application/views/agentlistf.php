<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                <div id="TOOLBAR" class="btn-group"> 
                    <button title="修改代理商" type="button" class="btn btn-default" onclick="editAgent();">
                        修改 
                    </button>
                    <button title="封号" type="button" class="btn btn-default" onclick="ban();">
                        封号
                    </button>
                    <button title="解绑" type="button" class="btn btn-default" onclick="unban();">
                        解封 
                    </button>
                    <button title="赠送" type="button" class="btn btn-default" onclick="giverc();">
                        赠送 
                    </button>
                </div>
                <table id="mainTable"
                       data-toggle="table"
                       data-url="/AgentAdmin/getfAgentList"
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
                       data-toolbar="#TOOLBAR">
                    <thead>
                    <tr>
                        <th data-field="state" data-checkbox="true"></th>
                        <th data-field="IDSTATE" data-align="center" data-formatter="stateFormatter"> 状态</th>
                        <th data-field="AID" data-align="center"> 代理商ID</th>
                        <th data-field="ANAME" data-align="center"> 姓名</th> 
                        <th data-field="CELLPHONE" data-align="center" > 手机号</th>
                        <th data-field="WXID" data-align="center" > 微信ID</th>
                        <th data-field="WXNICKNAME" data-align="center" > 微信昵称</th>
                        <th data-field="UNO" data-align="center" > 游戏ID</th>
                        <th data-field="ROOMCARD"  data-align="center" > 房卡余额</th>
                        <th data-field="GOLD"  data-align="center"  > 元宝余额</th>
                        <th data-field="LOWERNUM"  data-align="center"  data-formatter="getLinkData" > 发展下级数</th>
                        <th data-field="GAMERNUM"  data-align="center"  > 下级绑定玩家数</th>
                        <th data-field="GAMENUM"  data-align="center"  > 下级绑定玩家组局数</th>
                        <th data-field="CTIME"  data-align="center"  >账号开通时间</th> 
                        <th data-field="LASTLOGIN"  data-align="center" >最后一次登录时间</th>
                        <th data-field="MARK"  data-align="center"  >备注</th> 
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
<script src="/static/js/util.js"></script>

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
 
    function ban(){
        var rows = $('#mainTable').bootstrapTable('getSelections');
        if (rows.length != 1){
            baModalTipShow("提示", "请选择需要封号的数据", "d", function () {
                baModalTipToggle();
            });
            return;
        }
        var html = "请选择封号时长：<br/><select id='date' name='date' type='text' class='form-control' >"
            +"<option value='1'>7天</option>"
            +"<option value='2'>1个月</option>"
            +"<option value='3'>永久</option>"
            +"</select>";
        baModalWarningShow("警告", html, "w", function () { 
            baModalWarningToggle(); 
            $.post("/AgentAdmin/updateValidate", {aid: rows[0]["AID"],val:$("#date").val()}, function (response) {
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
    //解封
    function unban(){
        var rows = $('#mainTable').bootstrapTable('getSelections');
        if (rows.length != 1){
            baModalTipShow("提示", "请选择需要解封的账号", "d", function () {
                baModalTipToggle();
            });
            return;
        }
        var html = "确定要解封账号【"+rows[0]["AID"]+"】吗？";
        baModalWarningShow("警告", html, "w", function () { 
            baModalWarningToggle(); 
            $.post("/AgentAdmin/unban", {aid: rows[0]["AID"]}, function (response) {
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
    
    //赠送房卡
    function giverc(){
        var rows = $('#mainTable').bootstrapTable('getSelections');
        if (rows.length != 1){
            baModalTipShow("提示", "请选择赠送对象", "d", function () {
                baModalTipToggle();
            });
            return;
        }
        window.location = "/AgentAdmin/giveRCard?aid=" + rows[0]["AID"];
    }
    function getLinkData(value, row, index) {
        var url = "/AgentAdmin/sagentList/"+row["AID"];
        return "<a href='"+url+"'>"+value+"</a>";
    } 
</script>

