<?php 
defined('BASE_PATH') OR exit('No direct script access allowed');
include BASE_PATH.'/template/common/weui/header.php';
?>
<style>
    .weui_cell:before {content:'';border:none;}
</style>
<div class="weui_panel weui_panel_access">
    <div class="weui_panel_hd" style='display:none;'><?php echo $title;?></div>
    <div class="weui_panel_bd" id="bill">
        <?php
        $STATIC_CDN_URL = STATIC_CDN_URL;
        $productCostMoney = $productSaleMoney = $productRealMoney = $productNum = 0;
        foreach($billProduct as $_product){
            $productNum += $_product['product_num'];
            
            $_tmp = bcmul($_product['product_cost_money'], $_product['product_num'], 2);
            $productCostMoney = bcadd($productCostMoney, $_tmp, 2);//成本价
            
            $_tmp = bcmul($_product['product_sale_money'], $_product['product_num'], 2);
            $productSaleMoney = bcadd($productSaleMoney, $_tmp, 2);//原价
            
            $_tmp = bcmul($_product['product_real_money'], $_product['product_num'], 2);
            $productRealMoney = bcadd($productRealMoney, $_tmp, 2);//售价
            $_imgSrc = empty($_product['product_image']) ? '' : str_replace(CDN_URL_PLACEHOLDER, IMG_CDN_URL, $_product['product_image']);
            
            $_extra = '';
            
            if(BaseModel::isAdmin()){//管理员可随时修改价格、数量
                $_extra .= '<div class="weui_cell" style="padding:0;position:absolute;left:0px;top:20px;">
                    <div class="weui_cell_hd">
                        <label class="weui_label" style="font-weight:400;width:auto;">市价</label>
                    </div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input product_sale_money" type="number" placeholder="请输入市价" value="'. $_product['product_sale_money'] .'" style="width:5rem;vertical-align: top;position: absolute;top: -2px;color:#000;" bill_code="'. $bill['bill_code'] .'" product_id="'. $_product['product_id'] .'">
                    </div>
                </div>';
                $_extra .= '<div class="weui_cell" style="padding:0;position:absolute;left:8.5rem;top:20px;">
                    <div class="weui_cell_hd">
                        <label class="weui_label" style="font-weight:400;width:auto;">售价</label>
                    </div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input product_real_money" type="number" placeholder="请输入售价" value="'. $_product['product_real_money'] .'" style="width:5rem;vertical-align: top;position: absolute;top: -2px;color:#000;" bill_code="'. $bill['bill_code'] .'" product_id="'. $_product['product_id'] .'">
                    </div>
                </div>';
            
                $_extra .= '<div class="weui_cell" style="padding:0;position:absolute;top:3.9rem;">
                    <div class="weui_cell_hd">
                        <label class="weui_label" style="font-weight:400;width:auto;">成本</label>
                    </div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input product_cost_money" type="number" placeholder="请输入成本" value="'. $_product['product_cost_money'] .'" style="width:5rem;vertical-align: top;position: absolute;top: -2px;color:#000;" bill_code="'. $bill['bill_code'] .'" product_id="'. $_product['product_id'] .'">
                    </div>
                </div>';
            
                $_extra .= '<div class="weui_cell" style="padding:0;position:absolute;right:4rem;top:20px;">
                    <div class="weui_cell_hd">
                        <label class="weui_label" style="font-weight:400;width:auto;">数量</label>
                    </div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input product_num" type="number" placeholder="请输入数量" value="'. $_product['product_num'] .'" style="width:5rem;vertical-align: top;position: absolute;top: -2px;color:#000;" bill_code="'. $bill['bill_code'] .'" product_id="'. $_product['product_id'] .'">
                    </div>
                </div>';
            }else if(in_array($bill['bill_status'], ['CHECKED', 'PAID', 'POST'])){//客户,已确认单据,客户不可修改数量和售价
                $_extra .= '<div class="weui_cell" style="padding:0;position:absolute;top:20px;">
                    <div class="weui_cell_hd">
                        <label class="weui_label" style="font-weight:400;width:auto;">售价</label>
                    </div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input product_real_money" type="number" placeholder="请输入售价" value="'. $_product['product_real_money'] .'" style="width:5rem;vertical-align: top;position: absolute;top: -2px;color:#000;" bill_code="'. $bill['bill_code'] .'" product_id="'. $_product['product_id'] .'" disabled>
                    </div>
                </div>';
            
                $_extra .= '<div class="weui_cell" style="padding:0;position:absolute;right:4rem;top:20px;">
                    <div class="weui_cell_hd">
                        <label class="weui_label" style="font-weight:400;width:auto;">数量</label>
                    </div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input product_num" type="number" placeholder="请输入数量" value="'. $_product['product_num'] .'" style="width:5rem;vertical-align: top;position: absolute;top: -2px;color:#000;" bill_code="'. $bill['bill_code'] .'" product_id="'. $_product['product_id'] .'" disabled>
                    </div>
                </div>';
            }else{//客户,未确认单据,可修改数量
                $_extra .= '<div class="weui_cell" style="padding:0;position:absolute;right:4rem;top:20px;">
                    <div class="weui_cell_hd">
                        <label class="weui_label" style="font-weight:400;width:auto;">数量</label>
                    </div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input product_num" type="number" placeholder="请输入数量" value="'. $_product['product_num'] .'" style="width:5rem;vertical-align: top;position: absolute;top: -2px;color:#000;" bill_code="'. $bill['bill_code'] .'" product_id="'. $_product['product_id'] .'">
                    </div>
                </div>';
            }
            
            echo '<div class="weui_media_box weui_media_appmsg bill_product">
                <div class="weui_media_hd" product_id="'. $_product['product_id'] .'">
                    <img class="lazy weui_media_appmsg_thumb" data-original="'. $_imgSrc .'" onerror="this.src=\''. $STATIC_CDN_URL.$staticDir .'images/qrcode_for_gh_a103c9f558fa_258.jpg\'">
                </div>
                <div class="weui_media_bd" style="height:auto;line-height:0;">
                    <h4 class="weui_media_title" style="margin: 0px;">
                        <input class="weui_input product_name" type="text" placeholder="请输入名称" value="'. $_product['product_name'] .'" style="width:100%;vertical-align: top;position: absolute;top: 11px;color:#000;font-size:14px;" bill_code="'. $bill['bill_code'] .'" product_id="'. $_product['product_id'] .'" '. ($_product['product_id']==0 && BaseModel::isAdmin() ? '' : 'disabled') .'/>
                    </h4>
                    <div class="weui_media_desc" style="height: 60px;width: 100%;margin: 0;position:relative;">
                        '.$_extra.'
                    </div>
                </div>
            </div>';
        }
        
        if(BaseModel::isAdmin()){
            $bill['bill_discount_money'] = $bill['bill_discount_money'] ? $bill['bill_discount_money'] : '0';
            $_payMoney = bcsub($productRealMoney, $bill['bill_discount_money'], 2);
            
            $_billStatus = '';
            foreach(BILL_STATUS_HINT as $_status=>$_hint){
                $_billStatus .= '<option value="'. $_status .'" '. ($bill['bill_status']===$_status ? 'selected' : '') .'>'. $_hint .'</option>';
            }

            $_billExpress = '';
            $_expressConf = get_var_from_conf('kdniao');
            foreach($_expressConf as $_zhName=>$_com){
                $_billExpress .= '<option value="'. $_com .'" '. ($bill['express_com']===$_com ? 'selected' : '') .'>'. $_zhName .'</option>';
            }

            $_billAddress = '<option value="">请选择</option><option value="-1">新增收货地址</option>';
            foreach($addressList as $_address){
                $_selected = '';
                if($bill['address_id']){
                    $_selected = $bill['address_id']===$_address['id'] ? 'selected' : '';
                }else{
                    $_selected = $_address['address_default'] ? 'selected' : '';
                }
                $_billAddress .= '<option value="'. $_address['id'] .'" '. $_selected .'>'. $_address['address_detail'] .'</option>';
            }
            
            echo <<<EOF
<div class="weui_media_box weui_media_appmsg">
    <div class="weui_media_hd" style="height:auto;line-height:0;display:none;">
        <span class="weui_desc_extra">总计</span>
    </div>
    <div class="weui_media_bd">
        <p class="weui_media_desc" style="height: 40px;width: 100%;margin: 0;position:relative;">
            <span class="weui_desc_extra bill_cost_money_total" style="line-height:3;font-size:13px;display:none;">成本{$productCostMoney}</span>
            <span class="weui_desc_extra bill_sale_money_total" style="line-height:3;font-size:13px;position:absolute;">市价{$productSaleMoney}</span>
            <span class="weui_desc_extra bill_real_money_total" style="line-height:3;position:absolute;left:8.5rem;font-size:13px;">售价{$productRealMoney}</span>
            <span class="weui_desc_extra bill_number_total" style="position:absolute;line-height:3;right:4rem;font-size:13px;">{$productNum}件</span>
        </p>
        <p class="weui_media_desc" style="height: 40px;width: 100%;margin: 0;position:relative;">
            <span class="weui_desc_extra bill_real_money_total" style="line-height:3;font-size:13px;padding:0;">售价{$productRealMoney}</span>
            <span class="weui_desc_extra" style="line-height:3;font-size:13px;padding:0;">-</span>
            <span class="weui_desc_extra" style="line-height:3;font-size:13px;width:6rem;padding:0;">优惠<input type="number" class="bill_discount_money" value="{$bill['bill_discount_money']}"style="position: absolute;border: none;width: 4rem;color:#000;" bill_code="{$bill['bill_code']}"/></span>
            <span class="weui_desc_extra" style="line-height:3;font-size:13px;padding:0;">=</span>
            <span class="weui_desc_extra bill_pay_money_total" style="line-height:3;font-size:13px;color:red;padding:0;">应收{$_payMoney}</span>
        </p>
    </div>
</div>
<div style="border-top: solid 1px #eee;">
    <div class="hd" style="display:none;">
        <h1 class="page_title">Radio</h1>
    </div>
    <div class="bd">
        <div class="weui_cells_title" style="display:none;">订单状态</div>
        <div class="weui_cells weui_cells_radio">
            <div class="weui_cell weui_cell_select weui_select_after">
                <div class="weui_cell_hd" style="font-size:14px;">
                    订单状态
                </div>
                <div class="weui_cell_bd weui_cell_primary" style="font-size:14px;">
                    <select class="weui_select bill_status" name="bill_status" bill_code="{$bill['bill_code']}">
                        {$_billStatus}
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
<div style="border-top: solid 1px #eee;display:none;">
    <div class="hd" style="display:none;">
        <h1 class="page_title">Radio</h1>
    </div>
    <div class="bd">
        <div class="weui_cells_title" style="display:none;">收货地址</div>
        <div class="weui_cells weui_cells_radio">
            <div class="weui_cell weui_cell_select weui_select_after">
                <div class="weui_cell_hd">
                    收货地址
                </div>
                <div class="weui_cell_bd weui_cell_primary">
                    <select class="weui_select address_id" name="address_id" bill_code="{$bill['bill_code']}" user_id="{$bill['user_id']}">
                        {$_billAddress}
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="weui_cell weui_cell_select weui_select_before">
    <div class="weui_cell_hd">
        <select class="weui_select express_com" name="express_com" bill_code="{$bill['bill_code']}">
            {$_billExpress}
        </select>
    </div>
    <div class="weui_cell_bd weui_cell_primary">
        <input class="weui_input express_num" type="tel" placeholder="请输入单号" value="{$bill['express_num']}" name="express_num" bill_code="{$bill['bill_code']}">
    </div>
</div>
EOF;
        }else if(in_array($bill['bill_status'], ['CHECKED', 'PAID', 'POST'])){
            $_payMoney = bcsub($productRealMoney, $bill['bill_discount_money'], 2);
            
            $_billExpress = '';
            $_expressConf = get_var_from_conf('kdniao');
            foreach($_expressConf as $_zhName=>$_com){
                $_billExpress .= '<option value="'. $_com .'" '. ($bill['express_com']===$_com ? 'selected' : '') .'>'. $_zhName .'</option>';
            }
        
            $_billAddress = '';
            if($bill['address_id']){
                foreach($billAddressList as $_address){
                    if($bill['address_id']==$_address['id']){
                        $_billAddress = $_address['address_province'].' '.$_address['address_city'] .' '. $_address['address_detail'];
                    }
                }
            }
            echo <<<EOF
<div class="weui_cell weui_cell_select weui_select_after">
    <div class="weui_cell_hd">
        <select class="weui_select" disabled>
            {$_billExpress}
        </select>
    </div>
    <div class="weui_cell_bd weui_cell_primary">
        <input class="weui_input" type="tel" value="{$bill['express_num']}" disabled>
    </div>
</div>
<div class="weui_cell weui_cell_select weui_select_after" style="display:none;">
    <div class="weui_cell_hd">
        <select class="weui_select" disabled>
            <option value="">收货地址</option>
        </select>
    </div>
    <div class="weui_cell_bd weui_cell_primary">
        <input class="weui_input" type="text" value="{$_billAddress}" disabled>
    </div>
</div>
<div class="weui_media_box weui_media_appmsg">
    <div class="weui_media_hd" style="height:auto;line-height:0;display:none;">
        <span class="weui_desc_extra">总计</span>
    </div>
    <div class="weui_media_bd">
        <p class="weui_media_desc" style="height: 40px;width: 100%;margin: 0;position:relative;">
            <span class="weui_desc_extra bill_real_money_total" style="line-height:3;position:absolute;font-size:13px;">售价{$productRealMoney}</span>
            <span class="weui_desc_extra bill_number_total" style="position:absolute;line-height:3;right:4rem;font-size:13px;">{$productNum}件</span>
        </p>
        <p class="weui_media_desc" style="height: 40px;width: 100%;margin: 0;position:relative;">
            <span class="weui_desc_extra bill_real_money_total" style="line-height:3;font-size:13px;padding:0;">售价{$productRealMoney}</span>
            <span class="weui_desc_extra" style="line-height:3;font-size:13px;padding:0;">-</span>
            <span class="weui_desc_extra" style="line-height:3;font-size:13px;width:6rem;padding:0;">优惠<input type="number" class="bill_discount_money" value="{$bill['bill_discount_money']}"style="position: absolute;border: none;width: 4rem;color:#000;" bill_code="{$bill['bill_code']}" disabled/></span>
            <span class="weui_desc_extra" style="line-height:3;font-size:13px;padding:0;">=</span>
            <span class="weui_desc_extra bill_pay_money_total" style="line-height:3;font-size:13px;color:red;padding:0;">应付{$_payMoney}</span>
        </p>
    </div>
</div>
EOF;
        }else{
            $_billAddress = '<option value="">请选择</option><option value="-1">新增收货地址</option>';
            foreach($addressList as $_address){
                $_selected = '';
                if($bill['address_id']){
                    $_selected = $bill['address_id']===$_address['id'] ? 'selected' : '';
                }else{
                    $_selected = $_address['address_default'] ? 'selected' : '';
                }
                
                $_billAddress .= '<option value="'. $_address['id'] .'" '. $_selected .'>'. $_address['address_detail'] .'</option>';
            }
            
            echo <<<EOF
<div style="border-top: solid 1px #eee;display:none;">
    <div class="hd" style="display:none;">
        <h1 class="page_title">Radio</h1>
    </div>
    <div class="bd">
        <div class="weui_cells_title" style="display:none;">收货地址</div>
        <div class="weui_cells weui_cells_radio">
            <div class="weui_cell weui_cell_select weui_select_after">
                <div class="weui_cell_hd">
                    收货地址
                </div>
                <div class="weui_cell_bd weui_cell_primary">
                    <select class="weui_select address_id" name="address_id" bill_code="{$bill['bill_code']}" user_id="{$bill['user_id']}">
                        {$_billAddress}
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
EOF;
        }
        ?>
            </div>
        </div>
        <div class="weui_cells weui_cells_form">
            <div class="weui_cell">
                <div class="weui_cell_bd weui_cell_primary">
                    <?php
                    if(BaseModel::isAdmin() || $bill['bill_status']==='INIT'){
                        echo '<textarea class="weui_textarea" placeholder="请输入订单备注" rows="3" name="bill_memo" id="bill_memo" bill_code="'. $bill['bill_code'] .'"  style="font-size:14px;">'. $bill['bill_memo'] .'</textarea>';
                    }else{
                        echo '<textarea class="weui_textarea" placeholder="请输入订单备注" rows="3" name="bill_memo" id="bill_memo" bill_code="'. $bill['bill_code'] .'" disabled  style="font-size:14px;">'. $bill['bill_memo'] .'</textarea>';
                    }
                    ?>
                    
                </div>
            </div>
        </div>
        <div class="weui_media_box weui_media_appmsg">
            <div class="weui_media_bd">
                <p class="weui_media_desc" style="height: 40px;width: 100%;margin: 0;position:relative;display: block;">
                    <?php
                    if((BaseModel::isAdmin() && $bill['bill_status']!=='CANCEL') || $bill['bill_status']==='INIT'){
                        echo '<span class="weui_btn weui_btn_mini weui_btn_warn cancel_bill" style="float: left;display: block;" bill_code="'. $bill['bill_code'] .'">取消订单</span>';
                    }
                    ?>
                    <span class="weui_btn weui_btn_mini weui_btn_primary update_bill" style="float: right;display: block;margin:0;">确定</span>
                    <?php
                    if(BaseModel::isAdmin()){
                        echo '<span class="weui_btn weui_btn_mini weui_btn_primary add_bak" style="float: right;display: block;margin:0 5px 0 0;" bill_code="'. $bill['bill_code'] .'">+替补</span>';
                    }
                    
                    if($bill['express_com'] && $bill['express_num']){
                        echo '<span class="weui_btn weui_btn_mini weui_btn_primary express_detail" style="float: right;display: block;margin:0 5px 0 0;" bill_code="'. $bill['bill_code'] .'">查物流</span>';
                    }else if(BaseModel::isAdmin()){
                        //echo '<span class="weui_btn weui_btn_mini weui_btn_primary express_order" style="float: right;display: block;margin:0 5px 0 0;" bill_code="'. $bill['bill_code'] .'">寄件</span>';
                    }
                    ?>
                </p>
            </div>
        </div>
    </div>
</div>
<?php include BASE_PATH.'/template/common/weui/footer.php';?>
<script>
$(function(){
    var openInWechat = navigator.userAgent.toLowerCase().match(/MicroMessenger/i)=="micromessenger" ? true : false;
    if(openInWechat){
        wx.ready(function(){
            wx.onMenuShareTimeline({
                title: '<?php echo '【琳玲港货】为我代购了['.$billProduct[0]['product_name'].']共'.$bill['bill_product_num'].'件港货，省心省事！';?>', // 分享标题
                link: '<?php echo BASE_URL.'/shop/product/detail?product_id='.$billProduct[0]['product_id'];?>', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                imgUrl: '<?php echo str_replace(CDN_URL_PLACEHOLDER, IMG_CDN_URL, $bill['bill_image']);?>', // 分享图标
                success: function () {
                // 用户确认分享后执行的回调函数
                    layer.toast('分享成功');
                },
                cancel: function () {
                // 用户取消分享后执行的回调函数
                    layer.toast('取消分享');
                }
            });

            wx.onMenuShareAppMessage({
                title: '<?php echo '【琳玲港货】'.KISSBABY_DESCRIPTION;?>', // 分享描述
                desc: '<?php echo '我购买了['.$billProduct[0]['product_name'].']共'.$bill['bill_product_num'].'件港货，省心省事！';?>', // 分享标题
                link: '<?php echo BASE_URL.'/shop/product/detail?product_id='.$billProduct[0]['product_id'];?>', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                imgUrl: '<?php echo str_replace(CDN_URL_PLACEHOLDER, IMG_CDN_URL, $bill['bill_image']);?>', // 分享图标
                type: 'link', // 分享类型,music、video或link，不填默认为link
                dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                success: function () {
                // 用户确认分享后执行的回调函数
                    layer.toast('分享成功');
                },
                cancel: function () {
                // 用户取消分享后执行的回调函数
                    layer.toast('取消分享');
                }
            });
            
            wx.onMenuShareQQ({
                title: '<?php echo '【琳玲港货】'.KISSBABY_DESCRIPTION;?>', // 分享描述
                desc: '<?php echo '我购买了['.$billProduct[0]['product_name'].']共'.$bill['bill_product_num'].'件港货，省心省事！';?>', // 分享标题
                link: '<?php echo BASE_URL.'/shop/product/detail?product_id='.$billProduct[0]['product_id'];?>', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                imgUrl: '<?php echo str_replace(CDN_URL_PLACEHOLDER, IMG_CDN_URL, $bill['bill_image']);?>', // 分享图标
                success: function () {
                    // 用户确认分享后执行的回调函数
                    layer.toast('分享成功');
                },
                cancel: function () {
                    // 用户取消分享后执行的回调函数
                    layer.toast('取消分享');
                }
            });
            
            wx.onMenuShareWeibo({
                title: '<?php echo '【琳玲港货】'.KISSBABY_DESCRIPTION;?>', // 分享描述
                desc: '<?php echo '我购买了['.$billProduct[0]['product_name'].']共'.$bill['bill_product_num'].'件港货，省心省事！';?>', // 分享标题
                link: '<?php echo BASE_URL.'/shop/product/detail?product_id='.$billProduct[0]['product_id'];?>', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                imgUrl: '<?php echo str_replace(CDN_URL_PLACEHOLDER, IMG_CDN_URL, $bill['bill_image']);?>', // 分享图标
                success: function () {
                    // 用户确认分享后执行的回调函数
                    layer.toast('分享成功');
                },
                cancel: function () {
                    // 用户取消分享后执行的回调函数
                    layer.toast('取消分享');
                }
            });
            
            wx.onMenuShareQZone({
                title: '<?php echo '【琳玲港货】'.KISSBABY_DESCRIPTION;?>', // 分享描述
                desc: '<?php echo '我购买了['.$billProduct[0]['product_name'].']共'.$bill['bill_product_num'].'件港货，省心省事！';?>', // 分享标题
                link: '<?php echo BASE_URL.'/shop/product/detail?product_id='.$billProduct[0]['product_id'];?>', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                imgUrl: '<?php echo str_replace(CDN_URL_PLACEHOLDER, IMG_CDN_URL, $bill['bill_image']);?>', // 分享图标
                success: function () {
                    // 用户确认分享后执行的回调函数
                    layer.toast('分享成功');
                },
                cancel: function () {
                    // 用户取消分享后执行的回调函数
                    layer.toast('取消分享');
                }
            });
        });
    }
    
    function refreshBill(){
        var products = $('#bill .bill_product');
        var billTotalNumber = 0;
        var billCostMoneyTotal = 0;
        var billSaleMoneyTotal = 0;
        var billRealMoneyTotal = 0;
        for(var i=0,len=products.length;i<len;i++){
            var obj = products.eq(i);
            var productNum = obj.find('.product_num').val();
            billTotalNumber = productNum - 0 + billTotalNumber;

            var productCostMoney = obj.find('.weui_media_desc').find('.product_cost_money').val();
            billCostMoneyTotal = productCostMoney*productNum + billCostMoneyTotal;

            var productSaleMoney = obj.find('.weui_media_desc').find('.product_sale_money').val();
            billSaleMoneyTotal = productSaleMoney*productNum + billSaleMoneyTotal;

            var productRealMoney = obj.find('.weui_media_desc').find('.product_real_money').val();
            billRealMoneyTotal = productRealMoney*productNum + billRealMoneyTotal;
            
        }
        
        $('#bill .bill_number_total').html(billTotalNumber + '件');
        $('#bill .bill_cost_money_total').html('成本'+new Number(billCostMoneyTotal).toFixed(2));
        $('#bill .bill_sale_money_total').html('市价'+new Number(billSaleMoneyTotal).toFixed(2));
        $('#bill .bill_real_money_total').html('售价'+new Number(billRealMoneyTotal).toFixed(2));
        $('#bill .bill_pay_money_total').html('应收'+new Number(billRealMoneyTotal-$('#bill .bill_discount_money').val()).toFixed(2));
    }
    
    $('.update_bill').on('click', function(){
       location.href = '/shop/bill/index'; 
    });

    $('#bill').on('keyup', '.product_num', function(){
        var tmp = $(this).val();
        if(tmp<0){
            layer.error('购买数量非法');
            return false;
        }
    });
    
    $('#bill').on('blur', '.product_num', function(){
        var _this = this;
        var param = {};
        var tmp = $(this).attr('bill_code');
        if(!tmp){
            layer.error('订单号非法');
            return false;
        }
        param.bill_code = tmp;
        
        var tmp = $(this).attr('product_id');
        if(!tmp){
            layer.error('商品id非法');
            return false;
        }
        param.product_id = tmp;
        
        var tmp = $(this).val();
        if(tmp<0){
            layer.error('商品数量非法');
            return false;
        }
        param.product_num = tmp;
        layer.loading(true);

        $.ajax({
            url:'/shop/bill/updateproductnum',
            dataType:'json',
            data:param,
            type:'post',
            success:function(data, xhr){
                layer.loading(false);
                if(!data){
                    layer.error('请求失败,请稍后再试...');
                    return false;
                }

                if(data.rtn!=0){
                    layer.error(data.error_msg);
                    return false;
                }

                refreshBill();
            }
        });
        return false;
    });

    $('#bill').on('keyup', '.product_sale_money', function(){
        var productSaleMoney = $(this).val();
        if(productSaleMoney<0){
            layer.error('市场价不能小于0');
            return false;
        }
        
        var productRealMoney = $(this).parent('.product_list').find('.product_real_money').val();
        if(productSaleMoney - productRealMoney < 0){
            layer.error('市场价不能小于成交价');
            return false;
        }
        
        var productCostMoney = $(this).parent('.product_list').find('.product_cost_money').val();
        if(productSaleMoney - productCostMoney < 0){
            layer.error('市场价不能小于成本价');
            return false;
        }
    });

    $('#bill').on('blur', '.product_sale_money', function(){
        var productSaleMoney = $(this).val();
        if(productSaleMoney<0){
            layer.error('市场价不能小于0');
            return false;
        }
        
        var productRealMoney = $(this).parent('.product_list').find('.product_real_money').val();
        if(productSaleMoney - productRealMoney < 0){
            layer.error('市场价不能小于成交价');
            return false;
        }
        
        var productCostMoney = $(this).parent('.product_list').find('.product_cost_money').val();
        if(productSaleMoney - productCostMoney < 0){
            layer.error('市场价不能小于成本价');
            return false;
        }
        var param = {"product_sale_money":productSaleMoney};
        var tmp = $(this).attr('bill_code');
        if(!tmp){
            layer.error('订单号非法');
            return false;
        }
        param.bill_code = tmp;
        
        var tmp = $(this).attr('product_id');
        if(!tmp){
            layer.error('商品id非法');
            return false;
        }
        param.product_id = tmp;
        
        layer.loading(true);

        $.ajax({
            url:'/shop/bill/updateproductprice',
            dataType:'json',
            data:param,
            type:'post',
            success:function(data, xhr){
                layer.loading(false);
                if(!data){
                    layer.error('请求失败,请稍后再试...');
                    return false;
                }

                if(data.rtn!=0){
                    layer.error(data.error_msg);
                    return false;
                }

                refreshBill();
            }
        });
        return false;
    });

    $('#bill').on('keyup', '.product_real_money', function(){
        var productRealMoney = $(this).val();
        if(productRealMoney<0){
            layer.error('成交价不能小于0');
            return false;
        }
        
        var productSaleMoney = $(this).parent('.product_list').find('.product_sale_money').val();
        if(productRealMoney -  productSaleMoney > 0){
            layer.error('成交价不能大于市场价');
            return false;
        }
        
        var productCostMoney = $(this).parent('.product_list').find('.product_cost_money').val();
        if(productRealMoney - productCostMoney < 0){
            layer.error('成交价不能小于于成本价');
            return false;
        }
    });

    $('#bill').on('blur', '.product_real_money', function(){
        var productRealMoney = $(this).val();
        if(productRealMoney<0){
            layer.error('成交价不能小于0');
            return false;
        }
        
        var productSaleMoney = $(this).parent('.product_list').find('.product_sale_money').val();
        if(productRealMoney -  productSaleMoney > 0){
            layer.error('成交价不能大于市场价');
            return false;
        }
        
        var productCostMoney = $(this).parent('.product_list').find('.product_cost_money').val();
        if(productRealMoney - productCostMoney < 0){
            layer.error('成交价不能小于于成本价');
            return false;
        }
        var param = {"product_real_money":productRealMoney};
        var tmp = $(this).attr('bill_code');
        if(!tmp){
            layer.error('订单号非法');
            return false;
        }
        param.bill_code = tmp;
        
        var tmp = $(this).attr('product_id');
        if(!tmp){
            layer.error('商品id非法');
            return false;
        }
        param.product_id = tmp;
        
        layer.loading(true);

        $.ajax({
            url:'/shop/bill/updateproductprice',
            dataType:'json',
            data:param,
            type:'post',
            success:function(data, xhr){
                layer.loading(false);
                if(!data){
                    layer.error('请求失败,请稍后再试...');
                    return false;
                }

                if(data.rtn!=0){
                    layer.error(data.error_msg);
                    return false;
                }

                refreshBill();
            }
        });
        return false;
    });

    $('#bill').on('keyup', '.product_cost_money', function(){
        var productCostMoney = $(this).val();
        if(productCostMoney<0){
            layer.error('成本不能小于0');
            return false;
        }
        
        var productSaleMoney = $(this).parent('.product_list').find('.product_sale_money').val();
        if(productCostMoney -  productSaleMoney > 0){
            layer.error('成本不能大于市场价');
            return false;
        }
        
        var productRealMoney = $(this).parent('.product_list').find('.product_real_money').val();
        if(productRealMoney - productCostMoney < 0){
            layer.error('成本不能大于售价');
            return false;
        }
    });

    $('#bill').on('blur', '.product_cost_money', function(){
        var productCostMoney = $(this).val();
        if(productCostMoney<0){
            layer.error('成本不能小于0');
            return false;
        }
        
        var productSaleMoney = $(this).parent('.product_list').find('.product_sale_money').val();
        if(productCostMoney -  productSaleMoney > 0){
            layer.error('成本不能大于市场价');
            return false;
        }
        
        var productRealMoney = $(this).parent('.product_list').find('.product_real_money').val();
        if(productRealMoney - productCostMoney < 0){
            layer.error('成本不能大于售价');
            return false;
        }
        var param = {"product_cost_money":productCostMoney};
        var tmp = $(this).attr('bill_code');
        if(!tmp){
            layer.error('订单号非法');
            return false;
        }
        param.bill_code = tmp;
        
        var tmp = $(this).attr('product_id');
        if(!tmp){
            layer.error('商品id非法');
            return false;
        }
        param.product_id = tmp;
        
        layer.loading(true);

        $.ajax({
            url:'/shop/bill/updateproductprice',
            dataType:'json',
            data:param,
            type:'post',
            success:function(data, xhr){
                layer.loading(false);
                if(!data){
                    layer.error('请求失败,请稍后再试...');
                    return false;
                }

                if(data.rtn!=0){
                    layer.error(data.error_msg);
                    return false;
                }

                refreshBill();
            }
        });
        return false;
    });

    $('.express_num').on('blur', function(){
        var expressCom = $('.express_com').val();
        if(expressCom.length<2){
            layer.error('物流公司错误');
            return false;
        }
        
        var expressNum = $(this).val();
        if(expressNum && expressNum.length<5){
            layer.error('物流单号长度错误');
            return false;
        }
        
        var param = {"express_num":expressNum, "express_com":expressCom};
        var tmp = $(this).attr('bill_code');
        if(!tmp){
            layer.error('订单号非法');
            return false;
        }
        param.bill_code = tmp;
        
        layer.loading(true);

        $.ajax({
            url:'/shop/bill/updateexpress',
            dataType:'json',
            data:param,
            type:'post',
            success:function(data, xhr){
                layer.loading(false);
                if(!data){
                    layer.error('请求失败,请稍后再试...');
                    return false;
                }

                if(data.rtn!=0){
                    layer.error(data.error_msg);
                    return false;
                }

                layer.toast('成功');
            }
        });
        return false;
    });

    $('.express_com').on('blur', function(){
        var expressCom = $('.express_com').val();
        if(expressCom.length<2){
            layer.error('物流公司错误');
            return false;
        }
        
        var param = {"express_com":expressCom};
        var tmp = $(this).attr('bill_code');
        if(!tmp){
            layer.error('订单号非法');
            return false;
        }
        param.bill_code = tmp;
        
        layer.loading(true);

        $.ajax({
            url:'/shop/bill/updateexpress',
            dataType:'json',
            data:param,
            type:'post',
            success:function(data, xhr){
                layer.loading(false);
                if(!data){
                    layer.error('请求失败,请稍后再试...');
                    return false;
                }

                if(data.rtn!=0){
                    layer.error(data.error_msg);
                    return false;
                }

                layer.toast('成功');
            }
        });
        return false;
    });
    
    $('#bill').on('blur', '.bill_discount_money', function(){
        var param = {};
        var tmp = $(this).attr('bill_code');
        if(!tmp){
            layer.error('订单号非法');
            return false;
        }
        param.bill_code = tmp;
        
        var billDiscountMoney = $(this).val();
        
        var billRealMoney = $('#bill').find('.bill_real_money_total').eq(0).html().replace(/[^\d\.]/ig, '');
        var billPayMoney = billRealMoney - billDiscountMoney;
        if(billPayMoney < 0){
            layer.error('优惠金额不能大于成交价');
            return false;
        }
        param.bill_discount_money = new Number(billDiscountMoney).toFixed(2);
        
        layer.loading(true);
        $.ajax({
            url:'/shop/bill/updatediscountmoney',
            dataType:'json',
            data:param,
            type:'post',
            success:function(data, xhr){
                layer.loading(false);
                if(!data){
                    layer.error('请求失败,请稍后再试...');
                    return false;
                }

                if(data.rtn!=0){
                    layer.error(data.error_msg);
                    return false;
                }

                $('#bill').find('.bill_pay_money_total').html('应收(￥'+ new Number(billPayMoney).toFixed(2) +')');
                layer.toast('成功');
            }
        });
    });
    
    $('.cancel_bill').on('click', function(){
        var _this = this;
        layer.confirm('确定要取消订单吗？', function(){
            var param = {};
            var tmp = $(_this).attr('bill_code');
            if(!tmp){
                layer.error('操作非法');
                return false;
            }
            param.bill_code = tmp;
            
            layer.loading(true);
            $.ajax({
                url:'/shop/bill/cancel',
                dataType:'json',
                data:param,
                type:'post',
                success:function(data, xhr){
                    layer.loading(false);
                    if(!data){
                        layer.error('请求失败,请稍后再试...');
                        return false;
                    }

                    if(data.rtn!=0){
                        layer.error(data.error_msg);
                        return false;
                    }
                    
                    location.href = '/shop/bill/index';
                }
            });
        });
        return false;
    });
    
    $('#bill').on('change', '.bill_status', function(){
        var _this = this;
        var param = {};
        var tmp = $(this).attr('bill_code');
        if(!tmp){
            layer.error('订单号非法');
            return false;
        }
        param.bill_code = tmp;
        param.bill_status = $(this).val();
        layer.loading(true);

        $.ajax({
            url:'/shop/bill/updatestatus',
            dataType:'json',
            data:param,
            type:'post',
            success:function(data, xhr){
                layer.loading(false);
                if(!data){
                    layer.error('请求失败,请稍后再试...');
                    return false;
                }

                if(data.rtn!=0){
                    layer.error(data.error_msg);
                    return false;
                }
                
                layer.toast('成功', function(){location.reload();});
            }
        });
        return false;
    });

    $('#bill_memo').on('blur', function(){
        var billMemo = $(this).val();
        
        var param = {"bill_memo":billMemo};
        var tmp = $(this).attr('bill_code');
        if(!tmp){
            layer.error('订单号非法');
            return false;
        }
        param.bill_code = tmp;
        
        layer.loading(true);
        $.ajax({
            url:'/shop/bill/updatememo',
            dataType:'json',
            data:param,
            type:'post',
            success:function(data, xhr){
                layer.loading(false);
                if(!data){
                    layer.error('请求失败,请稍后再试...');
                    return false;
                }

                if(data.rtn!=0){
                    layer.error(data.error_msg);
                    return false;
                }

                layer.toast('已更新订单备注');
            }
        });
        return false;
    });

    $('#bill').on('blur', '.product_name', function(){
        var productName = $(this).val();
        if(!productName){
            layer.error('商品名称不能为空');
            return false;
        }
        
        var param = {"product_name":productName};
        var tmp = $(this).attr('bill_code');
        if(!tmp){
            layer.error('订单号非法');
            return false;
        }
        param.bill_code = tmp;
        
        var tmp = $(this).attr('product_id');
        if(!tmp){
            layer.error('只能更新替身产品的名称');
            return false;
        }
        param.product_id = tmp;
        
        layer.loading(true);
        $.ajax({
            url:'/shop/bill/updateproductname',
            dataType:'json',
            data:param,
            type:'post',
            success:function(data, xhr){
                layer.loading(false);
                if(!data){
                    layer.error('请求失败,请稍后再试...');
                    return false;
                }

                if(data.rtn!=0){
                    layer.error(data.error_msg);
                    return false;
                }

                layer.toast('已更新商品名称');
            }
        });
        return false;
    });

    $('.add_bak').on('click', function(){
        var tmp = $(this).attr('bill_code');
        if(!tmp){
            layer.error('订单号非法');
            return false;
        }
        var param = {"bill_code":tmp};
        
        layer.loading(true);
        $.ajax({
            url:'/shop/bill/addbak',
            dataType:'json',
            data:param,
            type:'post',
            success:function(data, xhr){
                layer.loading(false);
                if(!data){
                    layer.error('请求失败,请稍后再试...');
                    return false;
                }

                if(data.rtn!=0){
                    layer.error(data.error_msg);
                    return false;
                }

                layer.toast('成功', function(){location.reload();});
            }
        });
        return false;
    });
    
    $('.express_detail').on('click', function(){
        var billCode = $(this).attr('bill_code');
        if(!billCode){
            layer.error('订单id非法');
            return false;
        }
        
        location.href = '/shop/bill/express?bill_code='+billCode;
        return false;
    });
    
    $('.bill_product').on('click', '.weui_media_hd', function(){
        var productId = $(this).attr('product_id');
        if(!productId){
            return false;
        }
        
        location.href = '/shop/product/detail?product_id='+productId;
        return false;
    });
    
    $('.address_id').on('change', function(){
        var addressId = $(this).val();
        if(addressId==-1){
            var userId = $(this).attr('user_id');
            if(!userId){
                layer.error('用户id非法');
                return false;
            }

            location.href = '/shop/account/addressupdate?user_id='+userId;
            return false;
        }
        
        var tmp = $(this).attr('bill_code');
        if(!tmp){
            layer.error('订单号非法');
            return false;
        }
        var param = {"bill_code":tmp, "address_id":addressId};
        
        layer.loading(true);
        $.ajax({
            url:'/shop/bill/updateaddress',
            dataType:'json',
            data:param,
            type:'post',
            success:function(data, xhr){
                layer.loading(false);
                if(!data){
                    layer.error('请求失败,请稍后再试...');
                    return false;
                }

                if(data.rtn!=0){
                    layer.error(data.error_msg);
                    return false;
                }

                layer.toast('成功');
            }
        });
        return false;
    });
    
    $('.express_order').on('click', function(){
        var _this = this;
        var param = {};
        var tmp = $(this).attr('bill_code');
        if(!tmp){
            layer.error('订单号非法');
            return false;
        }
        param.bill_code = tmp;
        layer.loading(true);

        $.ajax({
            url:'/shop/bill/expressorder',
            dataType:'json',
            data:param,
            type:'post',
            success:function(data, xhr){
                layer.loading(false);
                if(!data){
                    layer.error('请求失败,请稍后再试...');
                    return false;
                }

                if(data.rtn!=0){
                    layer.error(data.error_msg);
                    return false;
                }

                layer.toast('成功', function(){location.reload();});
            }
        });
        return false;
    });
});
</script>
