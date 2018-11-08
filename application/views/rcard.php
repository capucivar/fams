<div class="box box-primary">
    <div class="box-body">
        <h3 class="box-title text-center">房卡充值</h3>

        <br/>

        <div class="col-sm-3"></div>
        <div class="col-sm-6">
            <form id="mForm" role="form" class="form-horizontal" method="post" action="/RoomcardC/go"> 
                <div class="form-group">
                    <label class="col-sm-3 control-label">账号</label>
                    <div class="col-sm-8">
                        <input id="AID" name="AID" type="text" class="form-control" readonly="true" value="<?= $aid ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">充值金额</label> 
                    <div class="col-sm-4">
                        <div class="input-group"> 
                            <input id="cardnum" name="cardnum"  type="number" class="form-control" onchange="changeMoney();">
                            <span class="input-group-addon">房卡</span>
                        </div> 
                    </div>
                    <div class="col-sm-4">
                        <div class="input-group"> 
                            <input id="money" name="money" type="number" min="1" class="form-control"  readonly="true">
                            <span class="input-group-addon">元</span>
                        </div> 
                    </div>
                </div> 
                <div class="form-group">
                    <label class="col-sm-3 control-label"></label>
                    <div class="col-sm-8">
                    比例：1张房卡=1人民币<br/>
                    充值数量不能低于1000张<br/>
                    充值数量为1000的整数倍<br/> 
                    <b class="text-red">您的充值折扣为40%</b>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">实际付款</label>
                    <div class="col-sm-8">
                        <div class="input-group"> 
                            <input id="pay" name="pay" type="text" class="form-control" readonly="true" >
                            <span class="input-group-addon">元</span>
                        </div>
                    </div>
                </div> 
                
            </form>
        </div>
        <div class="col-sm-3"></div>
    </div><!-- /.box-body -->
</div><!-- /.box -->

<button type="button" id="btn-save" class="btn btn-primary btn-lg ba-center" onclick="recharge()">
    去充值
</button> 

<script type="text/javascript">
    
    function changCard(){
        var money = $("#money").val();
        var num = money * 8;
        $("#cardnum").val(num);
        var pay = money * 0.85;
        $("#pay").val(pay); 
    } 
    function changeMoney(){
        var num = $("#cardnum").val();
        if(num<1000){
            baModalTipShow("提示", "充值数量不能低于1000张", "d");
            return;
        }
        if(num%1000!=0){
            baModalTipShow("提示", "充值数量为1000的整数倍", "d");
            return;
        }
        var money = num * 1;  
        $("#money").val(num);
        var pay = money * 0.4;
        $("#pay").val(pay); 
    } 
    function recharge() {
        var $btn = $('#btn-save');
        if ($btn.hasClass("disabled")) return;

        $btn.addClass("disabled");
        $btn.html('<i class="fa fa-spinner fa-pulse"></i>提交中');
        
        // 验证必填项
        var num = $("#cardnum").val();
        if(num<1000){
            baModalTipShow("提示", "充值数量不能低于1000张", "d");
            return;
        }
        if(num%1000!=0){
            baModalTipShow("提示", "充值数量为1000的整数倍", "d");
            return;
        }
        
        if ($("#money").val() == "") {
            baModalTipShow("提示", "充值金额为空", "d");
            $btn.html('保存');
            $btn.removeClass("disabled");
            return;
        }
        if ($("#pay").val() == "") {
            baModalTipShow("提示", "实际支付金额为空", "d");
            $btn.html('保存');
            $btn.removeClass("disabled");
            return;
        }
//        if ($("#money").val()%100!=0) {
//            baModalTipShow("提示", "充值金额为100的整数倍", "d");
//            $btn.html('保存');
//            $btn.removeClass("disabled");
//            return;
//        }
        $("#mForm").submit(); 
    }
    
</script>

