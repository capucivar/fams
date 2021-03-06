<div class="row">
    <div class="col-md-6" style="width: 100%">

        <!-- Profile Image -->
        <div class="box box-primary">
            <div class="box-body box-profile">
                <img class="profile-user-img img-responsive img-circle" src="/static/img/avatar04.png" alt="User profile picture">
<!--                <h3 class="profile-username text-center">客户名称</h3>-->
                <br/>
                <ul class="list-group list-group-unbordered"> 
                    <li class="list-group-item">
                        <b>姓名</b> 
                        <a class="pull-right"><?= $baseInfo["username"] ?></a>
                    </li>
                    <li class="list-group-item">
                        <b>性别</b> 
                        <a class="pull-right">
                            <?= SysDict::GET_GENDER($baseInfo["gender"])?>
                        </a>
                    </li>
                    <li class="list-group-item">
                        <b>所在部门</b>
                        <a class="pull-right"><?= $baseInfo["deptname"] ?></a>
                    </li>
                    
                    <li class="list-group-item">
                        <b>电话</b>
                        <a class="pull-right"><?= $baseInfo["phone"] ?></a>
                    </li>   
                    <li class="list-group-item">
                        <b>邮箱</b> <a class="pull-right"><?= $baseInfo["email"] ?></a>
                    </li> 
                    <li class="list-group-item">
                        <b>最近一次登录时间</b> <a class="pull-right"><?= $baseInfo["logintime"] ?></a>
                    </li>

                </ul> 
            </div><!-- /.box-body -->
        </div><!-- /.box --> 
        
    </div>
</div><!-- /.row -->


