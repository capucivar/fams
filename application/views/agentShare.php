<script src="/static/js/clipboard.min.js"></script>
<div class="panel panel-default">
    <form id="mform" role="form" class="form-horizontal">
        <div class="panel-body">
            <textarea id="CONTENT" name="CONTENT" class="form-control" rows="3" placeholder="分享推广内容说明" ><?= $scontent?></textarea>
        </div>
    </form>
    <div class="panel text-center">
<!--        <a class="margin-r-5" href="">保存</a>-->
        <button type="button" id="btn-save" class="btn btn-primary btn-lg ba-center" onclick="save();">
            保存
        </button>
    </div>
    
    <div class="panel-body">
      您的推广二维码： 
    </div>
    <div class="panel text-center">
        <img src="/<?= $qr ?>"/>
        <a class="margin-r-5" href="/agent/more">更多模板</a>
    </div> 
    <div class="panel-body">
        您的推广链接：<?=$url?> 
<!--        <a class="margin-r-5" id="copyBtn" href="javascript:copy()">复制</a>-->
    </div> 
</div> 
 
<script type="text/javascript">
    function save(){
        var $btn = $('#btn-save');
        if ($btn.hasClass("disabled")) return;
        $btn.addClass("disabled");
        $btn.html('<i class="fa fa-spinner fa-pulse"></i>提交中');
        if ($("#CONTENT").val() == "") {
            baModalTipShow("提示", "请填写分享推广内容说明", "d");
            $btn.html('保存');
            $btn.removeClass("disabled");
            return;
        }
        var formData = $("#mform").serializeArray();
        $.post("/Agent/saveContent", formData, function (response) {
            if (response.code != "1") {
                baModalTipShow("错误", response.message, "d");
                $btn.html('保存');
                $btn.removeClass("disabled");
                return;
            } 
            baModalTipShow("提示", "保存成功", "s", function () {
                window.location = "/Agent/share";
            });
        });
    }
    function copy(){
        var clipboard = new Clipboard("#copyBtn");
        clipboard.on('success', function(e) {
            alert("已复制");
        });

        clipboard.on('error', function(e) {
            alert("请重试");
        });
    }
</script>