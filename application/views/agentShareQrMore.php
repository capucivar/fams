<div id="myCarousel" class="panel carousel slide pad_010 b_k" >
      <div class=" carousel-inner bor_btm text-center"> 
          <?php 
            foreach ($qrfiles as $key => $value) {
                $active = $key==1?"active":"";
          ?>
            <div class="item <?= $active ?>" >
                <div class="pic">
                    <img src="/<?= $value ?>" height="400px"> 
                </div>
            </div>
          <?php
            }
          ?> 
       <!-- 轮播（Carousel）导航 --> 
       <a class="carousel-control left" style="line-height: 400px"  href="#myCarousel" data-slide="prev" >&lsaquo;</a>
       <a class="carousel-control right" style="line-height: 400px" href="#myCarousel" data-slide="next" >&rsaquo;</a>      
        <div class="panel-body">
          长按图片保存到相册
        </div>
    </div> 
</div>