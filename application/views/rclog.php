<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                <div id="TOOLBAR" class="btn-group"> 
                    <select id="LEVEL" name="LEVEL" class="form-control" onchange="refreshTbl()" style="width: 200px; float: left;">
                        <option value="">全部</option>
                        <option value="<?= SysDict::$ANGENTLEVEL["sys"]?>">平台</option>
                        <option value="<?= SysDict::$ANGENTLEVEL["one"]?>">渠道代理商</option>
                        <option value="<?= SysDict::$ANGENTLEVEL["two"]?>">玩家代理商</option>
                        <option value="<?= SysDict::$ANGENTLEVEL["player"]?>">玩家</option>
                    </select>
                    
                    <!--筛选：开始日期-->
                    <div class='input-group date' id='datetimepicker1' style="width: 200px; float: left; margin-left: 10px">  
                        <input type='text' class="form-control" id="sdate" name="sdate" placeholder="开始日期" />  
                        <span class="input-group-addon">  
                            <span class="glyphicon glyphicon-calendar"></span>  
                        </span>  
                    </div>
                    <!--筛选：结束日期-->
                    <div class='input-group date' id='datetimepicker2' style="width: 200px; float: left;margin-left: 10px">  
                        <input type='text' class="form-control" id="edate" name="edate" placeholder="结束日期" />  
                        <span class="input-group-addon">  
                            <span class="glyphicon glyphicon-calendar"></span>  
                        </span>  
                    </div>
                    
                </div>
                <table id="mainTable"
                       data-toggle="table"
                       data-url="/AgentAdminRC/rcloglist"
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
                       data-export-options='{ "fileName": "酒鬼棋牌房卡记录" }'
                       data-toolbar="#TOOLBAR">
                    <thead>
                    <tr> 
<!--                        <th data-field="CTIME" data-align="center" > 发生时间</th>
                        <th data-field="TYPE" data-align="center" > 类型</th>
                        <th data-field="UNO" data-align="center"> 玩家ID</th>
                        <th data-field="GNAME" data-align="center" > 玩家昵称</th> 
                        <th data-field="PAID"  data-align="center">绑定代理商ID</th>
                        <th data-field="AID" data-align="center" > 代理商ID</th>
                        <th data-field="LEVEL" data-align="center" > 代理商等级</th> 
                        <th data-field="NUM" data-align="center" > 房卡数</th>
                        <th data-field="RNO" data-align="center" > 房间号</th>-->
                        <th data-field="CTIME" data-align="center" > 发生时间</th>
                        <th data-field="TYPE" data-align="center"  data-formatter="dataFormatter"> 类型</th>
                        <th data-field="UNO" data-align="center"> 玩家ID</th>
                        <th data-field="WXID" data-align="center" > 玩家昵称</th> 
                        <th data-field="PAID"  data-align="center">绑定代理商ID</th>
                        <th data-field="AID" data-align="center" > 代理商ID</th>
                        <th data-field="LEVEL" data-align="center" data-formatter="levelFormatter"> 代理商等级</th> 
                        <th data-field="NUM" data-align="center" > 房卡数</th>
                        <th data-field="RNO" data-align="center" > 房间号</th>
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

<link href="/static/bootstrap/css/bootstrap-datetimepicker.min.css" rel="stylesheet" /> 
<script src="/static/bootstrap/js/bootstrap-datetimepicker.min.js"></script>  
<script src="/static/bootstrap/js/bootstrap-datetimepicker.zh-CN.js"></script> 

<script type="text/javascript">
    $(function () {
        var picker1 = $('#datetimepicker1').datetimepicker({
            language: 'zh-CN',
            format: 'yyyy-mm-dd',
            minView: "month",
            todayBtn:  1,
            autoclose: 1
        });
        var picker2 = $('#datetimepicker2').datetimepicker({
            language: 'zh-CN',
            format: 'yyyy-mm-d',
            minView: "month",
            todayBtn:  1,
            autoclose: 1
        }); 
        //动态设置最大值  
        picker1.on('change', function (e) {  
            picker2.datetimepicker('setStartDate',e.target.value); 
            if($("#edate").val()!="")
                refreshTbl();
        });
        picker2.on('change', function (e) {  
            if($("#sdate").val()==""){
                baModalTipShow("提示", "请先选择开始日期", "d");
                $("#edate").val("");
            }
            refreshTbl();
        }); 
        
        $('#mainTable').bootstrapTable('hideColumn', 'PID'); 
    }); 
    
    function refreshTbl() { 
        var s = $("#sdate").val(); 
        var e = $("#edate").val(); 
        var level = $("#LEVEL").val();
        var url = "/AgentAdminRC/rcloglist?a=1";
        if(s!="" && e!="")
            url+="&s="+s+"&e="+e;
        if(level!="")
            url+="&l="+level;
        $('#mainTable').bootstrapTable('refresh',{url:url});
    }
    
    function dataFormatter(value, row, index){ 
        switch (value) {  
            case "<?= SysDict::$LOGTYPE["derc"]?>":
                return "房卡消费";
            case "<?= SysDict::$LOGTYPE["rcharge"]?>":
                return "代理商充值";
            case "<?= SysDict::$LOGTYPE["rctransfer"]?>":
                return "房卡转账";
            case "<?= SysDict::$LOGTYPE["sys_new_card"]?>":
                return "平台生成赠送房卡";
            case "<?= SysDict::$LOGTYPE["gamerpay"]?>":
                return "玩家充值";
            case "<?= SysDict::$LOGTYPE["giverc"]?>":
                return "赠送给玩家";
            case "<?= SysDict::$LOGTYPE["rctransfer_player"]?>":
                return "房卡转账";
            case "<?= SysDict::$LOGTYPE["get_transfer_card"]?>":
                return "收到房卡转账";
            case "<?= SysDict::$LOGTYPE["get_transfer_card_player"]?>":
                return "收到房卡转账";
            case "<?= SysDict::$LOGTYPE["sys_give_card"]?>":
                return "平台赠送";
            case "<?= SysDict::$LOGTYPE["get_sys_give_card"]?>":
                return "获得赠送";
        }
    }
    function levelFormatter(value, row, index){ 
        switch (value) {  
            case "<?= SysDict::$ANGENTLEVEL["admin"]?>":
                return "系统管理员";
            case "<?= SysDict::$ANGENTLEVEL["head"]?>":
                return "总代";
            case "<?= SysDict::$ANGENTLEVEL["one"]?>":
                return "渠道代理商";
            case "<?= SysDict::$ANGENTLEVEL["two"]?>":
                return "玩家代理商";
            case "<?= SysDict::$ANGENTLEVEL["sys"]?>":
                return "平台";
            case null:
                return "玩家";
        }
    }
</script>

