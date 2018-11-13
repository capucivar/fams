
<div class="box box-primary">
    <div class="box-body">
        <br/>
        <div class="col-sm-2"></div>
        <div class="col-sm-7">
            <form id="userForm" role="form" class="form-horizontal">
                <input id="userid" name="userid" type="text" class="form-control" value="<?=$user['userid']?>" style="display: none;" >

                <div class="form-group">
                    <label class="col-sm-2 control-label">员工编号</label>
                    <div class="col-sm-4">
                        <input id="usercode" name="usercode" type="text" class="form-control" value="<?=$user['usercode']?>" readonly>
                    </div>

                    <label class="col-sm-2 control-label">员工姓名</label>
                    <div class="col-sm-4">
                        <input id="username" name="username" type="text" class="form-control"  value="<?=$user['username']?>"  >
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">所在部门</label>
                    <div class="col-sm-10">
                        <select class="selectpicker" data-live-search="true" id="deptid" name="deptid" onchange="selectOnchange(this)">
                            <option value="0" >请选择</option>
                        </select>&nbsp;&nbsp;
                        <select class="selectpicker" data-live-search="true" id="deptid2" name="deptid2" >
                            <option value="0" >请选择</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">联系电话</label>
                    <div class="col-sm-4">
                        <input id="phone" name="phone" type="text" class="form-control" value="<?=$user['phone']?>"  >
                    </div>
                    <label class="col-sm-2 control-label">邮箱</label>
                    <div class="col-sm-4">
                        <input id="email" name="email" type="text" class="form-control" value="<?=$user['email']?>"  >
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">性别</label>
                    <div class="col-sm-4">
                        <select class="selectpicker" data-live-search="true" id="gender" name="gender" >
                            <option value="0" <?= $user['gender']==0?"selected":""?> >未知</option>
                            <option value="1" <?= $user['gender']==1?"selected":""?> >男</option>
                            <option value="2" <?= $user['gender']==2?"selected":""?> >女</option>
                        </select>&nbsp;
                    </div>
                    <label class="col-sm-2 control-label">用户角色</label>
                    <div class="col-sm-4">
                        <select class="selectpicker" data-live-search="true" id="isadmin" name="isadmin" >
                            <option value="0" <?= $user['isadmin']==0?"selected":""?> >普通用户</option>
                            <option value="1" <?= $user['isadmin']==1?"selected":""?> >管理员</option>
                        </select>&nbsp;
                    </div>
                </div>

            </form>
        </div>
        <div class="col-sm-3"></div>
    </div><!-- /.box-body -->
</div><!-- /.box -->

<button type="button" id="btn-save" class="btn btn-primary btn-lg ba-center" onclick="saveUser();">
    保存
</button>


<link href="/static/plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet">
<script type="text/javascript" src="/static/plugins/bootstrap-select/bootstrap-select.js"></script>

<script type="text/javascript">
    var deptid = <?= empty($user["deptid"])?0:$user["deptid"]?>;
    var deptid2 = <?= empty($user["deptid2"])?0:$user["deptid2"]?>;
    var phone = <?= empty($user["phone"])?0:$user["phone"]?>;
    $(function () {
        $("#deptid").selectpicker({
            width:260
        });

        $("#deptid2").selectpicker({
            width:260
        });
        bindDeptLevelOne();
        bindDeptLevelTwo(deptid);
    });
    function bindDeptLevelOne() {
        $.post("/OrganizationC/getLevelOne", {}, function (response) {
            var data = JSON.parse(response);
            $.each(data.data, function (i, n) {
                var selected="";
                if (deptid==n.deptid)
                    selected=" selected";
                $("#deptid").append(" <option value='"+ n.deptid +"' "+ selected+" >" + n.deptname + "</option>");
            })
            $("#deptid").selectpicker('refresh');
        });
    }
    function bindDeptLevelTwo(parentid) {
        $("#deptid2").empty();
        $("#deptid2").append('<option value="0">请选择</option>');
        $.post("/OrganizationC/getLevelTwo", {parentid:parentid}, function (response) {
            var data = JSON.parse(response);
            $.each(data.data, function (i, n) {
                var selected="";
                if (deptid2==n.deptid)
                    selected=" selected";
                $("#deptid2").append(" <option value='"+ n.deptid +"' "+ selected+" >" + n.deptname + "</option>");
            })
            $("#deptid2").selectpicker('refresh');
        });

    }
    function selectOnchange(obj) {
        var id = obj.value;
        bindDeptLevelTwo(id);
        if ($("#userid").val()!="")
            return;
    }
    function saveUser() {
        var $btn = $('#btn-save');
        if ($btn.hasClass("disabled")) return;

        $btn.addClass("disabled");
        $btn.html('<i class="fa fa-spinner fa-pulse"></i>提交中');

        // 验证必填项
        if ($("#usercode").val() == 0) {
            baModalTipShow("提示", "请输入员工编号", "d");
            $btn.html('保存');
            $btn.removeClass("disabled");
            return;
        }
        if ($("#username").val() == "") {
            baModalTipShow("提示", "请输入员工姓名", "d");
            $btn.html('保存');
            $btn.removeClass("disabled");
            return;
        }

        if ($("#deptPid").val() == "") {
            baModalTipShow("提示", "请选择部门", "d");
            $btn.html('保存');
            $btn.removeClass("disabled");
            return;
        }
        if ($("#phone").val() == "") {
            baModalTipShow("提示", "请输入联系电话", "d");
            $btn.html('保存');
            $btn.removeClass("disabled");
            return;
        }
        if ($("#email").val() == "") {
            baModalTipShow("提示", "请输入邮箱", "d");
            $btn.html('保存');
            $btn.removeClass("disabled");
            return;
        }

        // 提交表单
        var formData = $("#userForm").serializeArray();
        formData[formData.length]={name:"phone2",value:phone};
//        formData[formData.length]={name:"typeid2",value:$("#assetCType").val()==0?$("#assetCType").val():$("#assetType").val()};
//        formData[formData.length]={name:"isdisposable",value:$("#isdisposable").is(":checked")?1:0};
        $.post("/UserC/saveUser", formData, function (response) {
            if (response.code != "1") {
                baModalTipShow("错误", response.message, "d");
                $btn.html('保存');
                $btn.removeClass("disabled");
                return;
            }
            baModalTipShow("提示", "保存成功", "s", function () {
                window.location = "/UserC";
            });
        });
    }
</script>

