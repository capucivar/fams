<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                <div id="TOOLBAR" class="btn-group">

                    <button title="新增资产" type="button" class="btn btn-default" onclick="newNote();">
                        新增下级
                    </button>
                    <button title="修改资产" type="button" class="btn btn-default" onclick="editNote();">
                        修改
                    </button>
                    <button title="修改资产" type="button" class="btn btn-default" onclick="delNote();">
                        删除
                    </button>
                </div>

                <table id="orgTable">

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
        var $table = $("#orgTable");
        $table.bootstrapTable({
            url:'/OrganizationC/getOrganList',
            striped:true,
            sidePagenation:'server',
            idField:'deptid', //ID字段
            columns:[
                {
                    field: 'ck',
                    checkbox: true
                },{
                    field:'deptname',
                    title:'名称'
                },{
                    field:'deptid',
                    title:'ID'
                }
            ],
            treeShowField: 'deptname',
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
                $("#deptname").attr("value",data.deptname);
            }
        });
    })
    //新增下级节点
    function newNote() {
        var rows = $('#orgTable').bootstrapTable('getSelections');
        if (rows.length < 1){
            baModalTipShow("提示", "请先选择上级节点", "d", function () {
                baModalTipToggle();
            });
            return;
        }

        var content = '<div id="organFormContent">'
            +'<form id="organForm" role="form" class="form-horizontal">'
            +'<div class="form-group">'
            +'<label class="col-sm-4 control-label">部门名称</label>'
            +'<div class="col-sm-6">'
            +'<input id="deptname" name="deptname" type="text" class="form-control" >'
            +'</div>'
            +'</div>'
            +'</form>'
            +'</div>';
        baModalFormShow("新增组织架构信息", content, "i", function () {
            baModalFormToggle();
            var parentid = rows[0]["deptid"];
            var name = $("#deptname").val();
            $.post("/OrganizationC/newOrganInfo", {parentid: parentid,deptname:name}, function (response) {
                if (response.code != "1") {
                    baModalTipShow("错误", response.message, "d");
                } else {
                    baModalTipShow("提示", "添加成功", "s", function () {
                        baModalTipToggle();
                        $('#orgTable').bootstrapTable('refresh');
                    });
                }
            });
        });
    }
    //修改节点
    function editNote() {
        var rows = $('#orgTable').bootstrapTable('getSelections');
        if (rows.length < 1){
            baModalTipShow("提示", "请先选择要修改的节点", "d", function () {
                baModalTipToggle();
            });
            return;
        }
        var deptid = rows[0]["deptid"];
        var deptname = rows[0]["deptname"];
        var content = '<div id="organFormContent">'
            +'<form id="organForm" role="form" class="form-horizontal">'
            +'<div class="form-group">'
            +'<label class="col-sm-4 control-label">部门名称</label>'
            +'<div class="col-sm-6">'
            +'<input id="deptname" name="deptname" type="text" class="form-control" value="'+deptname+'" >'
            +'</div>'
            +'</div>'
            +'</form>'
            +'</div>';
        baModalFormShow("修改组织架构信息", content, "i", function () {
            baModalFormToggle();
            var name = $("#deptname").val();
            $.post("/OrganizationC/updateOrganInfo", {deptid: deptid,deptname:name}, function (response) {
                if (response.code != "1") {
                    baModalTipShow("错误", response.message, "d");
                } else {
                    baModalTipShow("提示", "修改成功", "s", function () {
                        baModalTipToggle();
                        $('#orgTable').bootstrapTable('refresh');
                    });
                }
            });
        });
    }
    //删除节点
    function delNote() {
        var rows = $('#orgTable').bootstrapTable('getSelections');
        if (rows.length < 1){
            baModalTipShow("提示", "请先选择要删除的节点", "d", function () {
                baModalTipToggle();
            });
            return;
        }
        var deptid = rows[0]["deptid"];
        baModalWarningShow("警告", "是否确定删除所选记录", "q", function () {
            baModalWarningToggle();
            $.post("/OrganizationC/delOrgan", {deptid: deptid}, function (response) {
                if (response.code != "1") {
                    baModalTipShow("错误", response.message, "d");
                } else {
                    baModalTipShow("提示", "删除成功", "s", function () {
                        baModalTipToggle();
                        $('#orgTable').bootstrapTable('refresh');
                    });
                }
            });
        });
    }
</script>

