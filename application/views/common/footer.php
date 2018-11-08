</section>  <!--/.content -->
</div> <!--/.content-wrapper -->

<footer class="main-footer">
    <div class="pull-right hidden-xs">
        Page rendered in <strong>{elapsed_time}</strong> seconds. <b>Version</b> 0.0.1
    </div>

    <strong>Copyright &copy; 2015-2016 <a href="#">固定资产</a>.</strong> All rights reserved.
</footer>

</div><!-- ./wrapper -->

<div id="loading" class="spinner">
    <div class="bounce1"></div>
    <div class="bounce2"></div>
    <div class="bounce3"></div>
</div>

<!-- jQuery UI 1.11.4 -->
<script src="<?= CDN_URL ?>/jqueryui/1.11.4/jquery-ui.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.5 -->
<script src="/static/bootstrap/js/bootstrap.min.js"></script>
<!-- Morris.js charts -->
<script src="<?= CDN_URL ?>/raphael/2.2.1/raphael.min.js"></script>
<script src="/static/plugins/morris/morris.min.js"></script>
<!-- Sparkline -->
<script src="/static/plugins/sparkline/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="/static/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="/static/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="/static/plugins/knob/jquery.knob.js"></script>
<!-- daterangepicker -->
<script src="<?= CDN_URL ?>/moment.js/2.10.2/moment.min.js"></script>
<script src="/static/plugins/daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="/static/plugins/datepicker/bootstrap-datepicker.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<!--<script src="/static/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>-->
<!-- Slimscroll -->
<script src="/static/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="/static/plugins/fastclick/fastclick.min.js"></script>
<!-- AdminLTE App -->
<script src="/static/js/app.min.js"></script>
<!-- Toastr script -->
<script src="/static/plugins/toastr/toastr.min.js"></script>

<script language="javascript" type="text/javascript">
    $(function () {
        $('#loading').hide();
        $('.wrapper').removeClass("hidden");

        var $menus = $(".sidebar-menu .treeview a");
        var requestUrl = '<?= $_SERVER["REQUEST_URI"]?>';
        if (requestUrl == "/") {
            $menus = [$menus[1]];
        }
        for (var i = 0; i < $menus.length; i++) {
            var href = $($menus[i]).attr("href");
            if (href.indexOf(requestUrl) < 0) continue;
            $($menus[i]).parents(".treeview").addClass("active"); 
            $($menus[i]).parent().addClass("active");
        } 
    });
</script>

</body>
</html>