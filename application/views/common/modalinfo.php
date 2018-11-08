<!-- Info Modal -->
<div class="modal fade" id="tipModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index: 1151 !important;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="tipModalTitle">
                    提示标题
                </h4>
            </div>
            <div class="modal-body" id="tipModalContent">
                提示内容
            </div>
            <div class="modal-footer">
                <button id="tipModalBtn" type="button" class="btn btn-primary">
                    确定
                </button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /. Tip modal -->

<!-- Warning Modal -->
<div class="modal fade" id="warningModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index: 1150 !important;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="warningModalTitle">
                    提示标题
                </h4>
            </div>
            <div class="modal-body" id="warningModalContent">
                提示内容
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" onclick="$('#warningModal').modal('toggle');">
                    取消
                </button>
                <button id="warningModalBtn" type="button" class="btn btn-primary">
                    确定
                </button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /. Tip modal -->

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index: 1150 !important;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body" id="loadingModalContent">
                提示内容
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /. Loading modal -->

<script type="text/javascript">
    //显示提示窗口
    function baModalTipShow(title, content, arg, okOnClickCallBack) {
        $('#tipModalTitle').html(baGetInfoArgs(arg) + title);
        $('#tipModalContent').html(content);
        $("#tipModalBtn").unbind("click");
        $('#tipModalBtn').click(function () {
            if (typeof okOnClickCallBack == "function") {
                okOnClickCallBack();
            } else {
                baModalTipToggle();
            }
        });

        $('#tipModal').modal({backdrop: 'static', keyboard: false});
    }

    //切换提示窗口显示
    function baModalTipToggle() {
        $('#tipModal').modal("hide");
    }

    //显示警告窗口
    function baModalWarningShow(title, content, arg, okOnClickCallBack) {
        $('#warningModalTitle').html(baGetInfoArgs(arg) + title);
        $('#warningModalContent').html(content);
        $("#warningModalBtn").unbind("click");
        $('#warningModalBtn').click(function () {
            if (typeof okOnClickCallBack == "function") {
                okOnClickCallBack();
            } else {
                alert("没实现警告弹窗的回调方法");
            }
        });

        $('#warningModal').modal({backdrop: 'static', keyboard: false});
    }

    //切换警告窗口显示
    function baModalWarningToggle() {
        $('#warningModal').modal("hide");
    }

    //获取提示标志
    function baGetInfoArgs(arg) {
        switch (arg) {
            case 'w':
                return "<i class='fa fa-exclamation-triangle'></i>&nbsp;&nbsp;";
            case 'q':
                return "<i class='fa fa-question-circle'></i>&nbsp;&nbsp;";
            case 'i':
                return "<i class='fa fa-info-circle'></i>&nbsp;&nbsp;";
            case 'd':
                return "<i class='fa fa-times-circle-o'></i>&nbsp;&nbsp;";
            case 's':
                return "<i class='fa fa-check-circle-o'></i>&nbsp;&nbsp;";
            default:
                return "";
        }
    }

    //显示加载信息窗口
    function baModalLoadingShow(content) {
        $('#loadingModalContent').html('<i class="fa fa-spinner fa-pulse"></i>&nbsp;' + content);

        $('#loadingModal').modal({backdrop: 'static', keyboard: false});
    }

    //切换加载窗口显示
    function baModalLoadingToggle() {
        $('#loadingModal').modal("hide");
    }
</script>