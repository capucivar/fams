<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                <div id="TOOLBAR" class="btn-group">

                    <button id="newBtn" title="新增类别" type="button" class="btn btn-default" onclick="newNote();">
                        新增类别
                    </button>
                    <button title="修改类别" type="button" class="btn btn-default" onclick="editNote();">
                        修改
                    </button>
                    <button title="删除类别" type="button" class="btn btn-default" onclick="delNote();">
                        删除
                    </button>
                </div>

                <table id="typeTable">

                </table>

            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col -->
</div><!-- /.row -->

<link href="/static/plugins/bootstrap-treeview/css/bootstrap-theme.css" rel="stylesheet">
<link href="/static/plugins/bootstrap-treeview/css/bootstrap-table.css" rel="stylesheet">
<link href="/static/plugins/bootstrap-treeview/css/jquery.treegrid.min.css" rel="stylesheet">
<script type="text/javascript" src="/static/plugins/bootstrap-treeview/js/bootstrap3.3.7.min.js"></script>
<script type="text/javascript" src="/static/plugins/bootstrap-treeview/js/bootstrap-table.js"></script>
<script type="text/javascript" src="/static/plugins/bootstrap-treeview/js/bootstrap-table-zh-CN.js"></script>
<script type="text/javascript" src="/static/plugins/bootstrap-treeview/js/bootstrap-table-treegrid.js"></script>
<script type="text/javascript" src="/static/plugins/bootstrap-treeview/js/jquery.treegrid.min.js"></script>


<script type="text/javascript">
    $(function () {
        var $table = $("#typeTable");
        $table.bootstrapTable({
            url:'/AssetTypeC/getAssetType',
            striped:true,
            sidePagenation:'server',
            idField:'typeid', //ID字段
            columns:[
                {
                    field: 'ck',
                    checkbox: true
                },{
                    field:'typename',
                    title:'类别名称'
                },{
                    field:'typecode',
                    title:'编号'
                }
            ],
            treeShowField: 'typename',
            parentIdField: 'parentid',//上级ID
            singleSelect:true,
            clickToSelect:true,
            onLoadSuccess: function(data) {
                $table.treegrid({
                    //initialState: 'expandable',//收缩
                    treeColumn: 1,//指明第几列数据改为树形
                    expanderExpandedClass: 'glyphicon glyphicon-triangle-bottom',
                    expanderCollapsedClass: 'glyphicon glyphicon-triangle-right',
                    onChange: function() {
                        $table.bootstrapTable('resetWidth');
                    }
                });
            },
            onClickRow: function (data) {
                var level = data._level;
                if (level>0)
                    $('#newBtn').prop('disabled', true);
                else
                    $('#newBtn').prop('disabled', false);
            }
        });
    })
    //新增类别
    function newNote() {
        var rows = $('#typeTable').bootstrapTable('getSelections');
        var typename = "-";
        if (rows.length > 0){
            typename = rows[0]["typename"];
        }
        var content = '上级类别：'+typename
            +'<form id="typeForm" role="form" class="form-horizontal">'
            +'<div class="form-group">'
            +'<label class="col-sm-4 control-label">类别名称</label>'
            +'<div class="col-sm-6"> <input id="typename" name="typename" type="text" class="form-control" > </div>'
            +'</div>'
            +'<div class="form-group">'
            +'<label class="col-sm-4 control-label">类别编码</label>'
            +'<div class="col-sm-6"> <input id="typecode" name="typecode" type="text" class="form-control" > </div>'
            +'</div>'
            +'</form>' ;
        baModalFormShow("新增资产类别", content, "i", function () {
            console.log("弹窗调用");
            baModalFormToggle();
            var parentid = rows.length>0?rows[0]["typeid"]:0;
            var typename = $("#typename").val();
            var typecode = $("#typecode").val();
            $.post("/AssetTypeC/newType", {parentid: parentid,typename:typename,typecode:typecode}, function (response) {
                if (response.code != "1") {
                    baModalTipShow("错误", response.message, "d");
                } else {
                    baModalTipShow("提示", "添加成功", "s", function () {
                        baModalTipToggle();
                        $('#typeTable').bootstrapTable('refresh');
                    });
                }
            });
        });
    }
    //修改节点
    function editNote() {
        var rows = $('#typeTable').bootstrapTable('getSelections');
        if (rows.length < 1){
            baModalTipShow("提示", "请先选择要修改的类别", "d", function () {
                baModalTipToggle();
            });
            return;
        }
        var typeid = rows[0]["typeid"];
        var typename = rows[0]["typename"];
        var typecode = rows[0]["typecode"];
        var content = ''
            +'<form id="typeForm" role="form" class="form-horizontal"> <input id="typeid" name="typeid" type="text" class="form-control" style="display: none" value="'+typeid+'" >'
            +'<div class="form-group">'
            +'<label class="col-sm-4 control-label">类别名称</label>'
            +'<div class="col-sm-6"> <input id="typename" name="typename" type="text" class="form-control" value="'+typename+'" > </div>'
            +'</div>'
            +'<div class="form-group">'
            +'<label class="col-sm-4 control-label">类别编码</label>'
            +'<div class="col-sm-6"> <input id="typecode" name="typecode" type="text" class="form-control" value="'+typecode+'"> </div>'
            +'</div>'
            +'</form>' ;
        baModalFormShow("修改资产类别信息", content, "i", function () {
            baModalFormToggle();
            var typeid = $("#typeid").val();
            var typename = $("#typename").val();
            var typecode = $("#typecode").val();
            $.post("/AssetTypeC/updateType", {typeid:typeid,typename:typename,typecode:typecode}, function (response) {
                if (response.code != "1") {
                    baModalTipShow("错误", response.message, "d");
                } else {
                    baModalTipShow("提示", "修改成功", "s", function () {
                        baModalTipToggle();
                        $('#typeTable').bootstrapTable('refresh');
                    });
                }
            });
        });
    }
    //删除节点
    function delNote() {
        var rows = $('#typeTable').bootstrapTable('getSelections');
        if (rows.length < 1){
            baModalTipShow("提示", "请先选择要删除的类别", "d", function () {
                baModalTipToggle();
            });
            return;
        }
        var typeid = rows[0]["typeid"];
        baModalWarningShow("警告", "是否确定删除所选类别", "q", function () {
            baModalWarningToggle();
            $.post("/AssetTypeC/delType", {typeid: typeid}, function (response) {
                if (response.code != "1") {
                    baModalTipShow("错误", response.message, "d");
                } else {
                    baModalTipShow("提示", "删除成功", "s", function () {
                        baModalTipToggle();
                        $('#typeTable').bootstrapTable('refresh');
                    });
                }
            });
        });
    }
</script>

