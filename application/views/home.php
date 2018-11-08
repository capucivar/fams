<div class="row">
    <div class="col-md-6" style="width: 100%">

        <!-- Profile Image -->
        <div class="box box-primary">
            <div class="box-body box-profile">
                
                <div class="panel panel-default">
                    <div class="panel-body">
                      <?= $notice ?>
                    </div>
                  </div>
                
                <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                        <b>代理商ID</b>
                        <a class="pull-right"><?= $agent["AID"] ?></a>
                    </li> 
                    <li class="list-group-item">
                        <b>房卡余额</b> <a class="pull-right"><?= $agent["ROOMCARD"] ?></a>
                    </li>
                    <?php
                        if($agent["LEVEL"]==SysDict::$ANGENTLEVEL["one"]){
                    ?>
                    <li class="list-group-item">
                        <b>下级代理商数</b> <a class="pull-right"><?= $mCount ?></a>
                    </li>
                    <?php
                        }else if($agent["LEVEL"]==SysDict::$ANGENTLEVEL["two"]){
                    ?>
                    <li class="list-group-item">
                        <b>绑定玩家数</b> <a class="pull-right"><?= $mCount ?></a>
                    </li> 
                    <li class="list-group-item">
                        <b>代开房卡收益</b> <a class="pull-right"><?= $earnings ?></a>
                    </li>
                    <?php }?>
                    <li class="list-group-item">
                        <b>最近一次登录时间</b> <a class="pull-right"><?= $agent["LASTLOGIN"] ?></a>
                    </li>

                </ul> 
            </div><!-- /.box-body -->
        </div><!-- /.box --> 
        
    </div>
</div><!-- /.row -->


