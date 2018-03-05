<?php 
defined('BASE_PATH') OR exit('No direct script access allowed');
include $viewPath.'header.php';
?>
<div id="banner" class="banner" style="display:none;">
	<div class="container">
		<div class="banner_desc">
			<h1>琳玲Dai购</h1>
			<h2>最贴心的生活助手</h2>
			<div class="button">
                <a href="javascritp:void(0)" class="hvr-shutter-out-horizontal">Shop Now</a>
            </div>
		</div>
	</div>
</div>
<div class="content_top">
	<h3 class="m_1" style="display:none;">活动商品</h3>
	<div class="container" id="container">
	   <div class="box_1">
	       <div class="col-md-7-bak">
               <?php 
                for($i=0,$len=count($productList); $i<$len; $i++){
                    if(($i+1)%3==0){
                        echo '<div class="section group">';
                    }

                    $_product = $productList[$i];

                    $_imgSrc = empty($_product['product_image'][0]) ? '' : $_product['product_image'][0];
                    $_extra = !empty($_SESSION['user']['user_type']) && $_SESSION['user']['user_type']==='admin' ? '<span><span class="amount">会员价:$'.$_product['product_vip_price'].'</span></span>' : '';
                    echo <<<EOF
<div class="col_1_of_3 span_1_of_3">
    <div class="shop-holder">
         <div class="product-img">
            <a href="/shop/product/detail?product_id={$_product['product_id']}">
                <img width_bak="225" height_bak="265" data-original="{$_imgSrc}" src="{$staticDir}images/default.png" class="lazy img-responsive"  alt="item4">
            </a>
            <a href="javascript:void(0);" class="button "></a>
        </div>
    </div>
    <div class="shop-content" style="height: 50px;margin-top: .7rem;">
            <div><a href="/shop/product/detail?product_id={$_product['product_id']}" rel="tag" style="display: block;height: 20px;overflow: hidden;">{$_product['product_name']}</a></div>
            <h3><a href="/shop/product/detail?product_id={$_product['product_id']}">Non-charac</a></h3>
            {$_extra}
    </div>
</div>
EOF;
                    if(($i+1)%3==0){
                        echo '<div class="clearfix"></div></div>';
                    }
                }
                ?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
</div>

<script>
    $(function(){
        $(document).scroll(function(){
            var tmp = $(document).height() / ($(document).scrollTop() + window.innerHeight);
            if(tmp < 1.05 ){
                console.log(tmp);
            }  
        });
    })
</script>
<?php include $viewPath.'footer.php';?>