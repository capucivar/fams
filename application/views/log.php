
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body"> 
                <div class="col-sm-3">
                    <select id="LTYPE" name="LTYPE" class="form-control" onchange="refreshTbl()">
                        <option value="">全部</option>
                        <?php
                            if($agent["LEVEL"]==SysDict::$ANGENTLEVEL["two"]){//二级代理
                        ?> 
                        <option value="<?= SysDict::$LOGTYPE["rctransfer_player"]?>">转账给玩家</option>
                            <option value="<?= SysDict::$LOGTYPE["get_transfer_card"]?>">上级代理转账</option>
                        <?php
                            }else if($agent["LEVEL"]==SysDict::$ANGENTLEVEL["one"]){//一级代理商
                        ?>
                        <option value="<?= SysDict::$LOGTYPE["rcharge"]?>">房卡充值</option>
                        <option value="<?= SysDict::$LOGTYPE["rctransfer"]?>">转账给代理商</option>
                        <?php 
                            }
                        ?>
                    </select>
                </div> 

                <table id="RCLogTable"
                       data-toggle="table"
                       data-url="/LogC/getRcLogList"
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
                       data-toolbar="#TOOLBAR" >
                    <thead>
                    <tr>  
                        <th data-field="TYPE" data-align="center" data-formatter="dataFormatter"> 操作类型</th>
                        <?php if($agent["LEVEL"]==SysDict::$ANGENTLEVEL["two"]){
                            echo '<th data-field="UNO" data-align="center"> 玩家ID</th>';
                            echo '<th data-field="NICKNAME" data-align="center"> 昵称</th>';
                        }?> 
                        <th data-field="TOAID" data-align="center"> 代理商ID</th>
                        <th data-field="NUM" data-align="center"> 房卡数量</th> 
                        <th data-field="CTIME" data-align="center" class="col-sm-2" > 时间</th>
                        <th data-field="ID" data-align="center" > 流水号</th>
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
    function refreshTbl() { 
        var type = $("#LTYPE").val(); 
        var url = "/LogC/getRcLogList?t="+type;
        var aa = $("#RCLogTable"); 
        $('#RCLogTable').bootstrapTable('refresh',{url:url});
    }
    function dataFormatter(value, row, index){
        switch(value){
            case '<?= SysDict::$LOGTYPE["get_transfer_card"]?>'://玩家代理商收到转账的房卡
                return "上级代理转账";
            break;
            case '<?= SysDict::$LOGTYPE["rctransfer_player"]?>'://玩家代理商转给玩家
                return "转账给玩家";
            break;
            case '<?= SysDict::$LOGTYPE["rctransfer"]?>'://渠道代理商转给玩家代理商
                return "转账给代理商";
            break;
            case '<?= SysDict::$LOGTYPE["rcharge"]?>':
                return "房卡充值";
            break; 
            case '<?= SysDict::$LOGTYPE["get_sys_give_card"]?>':
                return "获得平台赠送";
            break;
            
        }
    }
    
</script>

