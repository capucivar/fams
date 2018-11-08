<?php
    foreach ($msg as $val) {
?>
    <div class="list-group">
        <a href="#" class="list-group-item"> 
            <h4 class="list-group-item-heading" >封号申请<span style="font-size: 10px">（<?= $val["CTIME"]?>）</span></h4>
          <hr />
          <p class="list-group-item-text">代理商【<?= $val["ALAID"] ?>】申请关闭玩家账号【<?= $val["ALPID"] ?>】</p>
          <span style="display:block; line-height: 40px; text-align: right;">查看详情 >></span>
        </a>
      </div> 
<?php
    }
?>
<div class="btn-group" role="group" aria-label="...">
    <button type="button" class="btn btn-default" onclick="page(1)">上一页</button>
  <button type="button" class="btn btn-default" onclick="page(2)">下一页</button>
</div>

<script type="text/javascript">
    function GetQueryString(name){
         var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
         var r = window.location.search.substr(1).match(reg);
         if(r!=null)return  unescape(r[2]); return 0;
    }
    function page(flg){
        var page = parseInt(GetQueryString("p"));
        var total = <?= $total ?>;
        if(flg==1){  
            page = page<=1?1:page-1; 
        }else if(flg==2){ 
            page = page>=total?page:page+1; 
        }
        window.location = "/MessageC?p=" + page;
    }
</script>