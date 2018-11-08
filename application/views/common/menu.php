<!-- 总代 -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="/static/img/avatar04.png" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><?= $agent["AID"] ?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">

            <li class="treeview">
                <a href="/Home/account">
                    <i class="fa fa-user"></i> <span>首页</span> 
                </a>
            </li>
            <li class="treeview">
                <a href="/AssetC/index">
                    <i class="fa fa-user"></i> <span>资产入库</span> 
                </a>
            </li>
            <li class="treeview">
                <a href="">
                    <i class="fa fa-users"></i> <span>资产管理</span>  <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="/AssetC/type"><i class="fa fa-user"></i>资产类别管理</a></li>
                    <li><a href=""><i class="fa fa-user"></i>仓库管理</a></li>
                 </ul>
            </li> 
            <li class="treeview">
                <a href="">
                    <i class="fa fa-users"></i> <span>资产盘点</span>  <i class="fa fa-angle-left pull-right"></i>
                </a> 
            </li>
            <li class="treeview">
                <a href="">
                    <i class="fa fa-users"></i> <span>资产领用</span>  <i class="fa fa-angle-left pull-right"></i>
                </a> 
            </li>  
            <li class="treeview">
                <a href="">
                    <i class="fa fa-gamepad"></i> <span>用户管理</span>  <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href=""><i class="fa fa-gamepad"></i>组织架构管理</a></li>
                    <li><a href=""><i class="fa fa-gamepad"></i>用户管理</a></li> 
                     <li><a href=""><i class="fa fa-gamepad"></i>用户角色管理</a></li>  
                 </ul>
            </li>  
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>

