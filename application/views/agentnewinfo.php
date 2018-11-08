<div class="box box-primary">
    <div class="box-body">
        <h3 class="box-title text-center">玩家代理商已经成功开通</h3>

        <br/>

        <div class="col-sm-3"></div>
        <div class="col-sm-6">
            <form id="client-form" role="form" class="form-horizontal"> 
                <div class="form-group">
                    <label class="col-sm-3 control-label" >代理商名称</label> 
                    <div class="col-sm-9" id="ANAME" name="ANAME">--</div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" >初始密码</label> 
                    <div class="col-sm-9" >888888</div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" >邀请码</label> 
                    <div class="col-sm-9"  id="SHARECODE" name="SHARECODE">--</div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">微信ID</label>
                    <div class="col-sm-9"  id="WXID" name="WXID">--</div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">微信昵称</label>
                    <div class="col-sm-9"  id="WXNICKNAME" name="WXNICKNAME">--</div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">手机号</label>
                    <div class="col-sm-9"  id="CELLPHONE" name="CELLPHONE">--</div>
                </div> 
                <div class="form-group">
                    <label class="col-sm-3 control-label">游戏ID</label>
                    <div class="col-sm-9"  id="UNO" name="UNO">--</div> 
                </div> 
                
            </form>
        </div>
        <div class="col-sm-3"></div>
    </div><!-- /.box-body -->
</div><!-- /.box -->

<button type="button" id="btn-save" class="btn btn-primary btn-lg ba-center" onclick="javascript:window.location.href='/Agent/agentList'">
    返回
</button>

<script src="/static/js/location.js"></script>
<script type="text/javascript">
    $(function () { 
        var agentData = '<?= $mAgent?>'; 
        if (agentData != "") { 
            var agentObj = JSON.parse(agentData);
            $.each(agentObj, function (k, v) {
                console.info(k+":"+v); 
                $("#client-form div[name='" + k + "']").text(v);
            }); 
        } 
    });
    
</script>

