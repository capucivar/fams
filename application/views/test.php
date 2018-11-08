<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                <div id="TOOLBAR" class="btn-group"> 
                    <button title="修改代理商" type="button" class="btn btn-default" onclick="edit();">
                        修改 
                    </button> 
                </div>
                <table id="mainTable"
                       data-toggle="table"
                       data-url="/TestC/get"
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
                        <th data-field="RCID" data-align="center"> RCID </th>
                        <th data-field="OWNER" data-align="center"> OWNER</th>
                        <th data-field="TYPE" data-align="center" > TYPE</th>  
                        <th data-field="CTIME" data-align="center" > CTIME</th> 
                        <th data-field="MARK"  data-align="center">MARK</th> 
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
    $(function () {
        $('#mainTable').bootstrapTable('hideColumn', 'PID'); 
    });
    function edit() {
        var rows = $('#mainTable').bootstrapTable('getSelections'); 
        if (rows.length <= 0){
            baModalTipShow("提示", "请选择!", "d", function () {
                baModalTipToggle();
            });
            return;
        } 
//        rows.forEach(function(value,index,array){ 
//            alert(value["RCID"]);
//        });
//        window.location = "/TestC/update?pid=" + rows[0]["PID"];
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
            $.post("/AgentAdmin/updateGamerValidate", {pid: rows[0]["PID"],val:$("#date").val()}, function (response) {
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
        var html = "确定要解封账号【"+rows[0]["UNO"]+"】吗？";
        baModalWarningShow("警告", html, "w", function () { 
            baModalWarningToggle(); 
            $.post("/AgentAdmin/unbanGamer", {pid: rows[0]["PID"]}, function (response) {
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
    
    function unbind(){
        var rows = $('#mainTable').bootstrapTable('getSelections');
        if (rows.length != 1){
            baModalTipShow("提示", "请选择需要解绑的数据", "d", function () {
                baModalTipToggle();
            });
            return;
        }
        var content="解绑之后玩家只能通过平台直充房卡，请确认是否进行解绑？";
        baModalWarningShow("警告", content, "w", function () { 
            baModalWarningToggle(); 
            $.post("/AgentAdmin/unbind", {pid: rows[0]["PID"]}, function (response) {
                 if (response.code != "1") {
                     baModalTipShow("错误", response.message, "d");
                 } else {
                     baModalTipShow("提示", response.message, "s", function () {
                         baModalTipToggle();
                         $('#mainTable').bootstrapTable('refresh');
                     });
                 }
             });
        }); 
    }
</script>

