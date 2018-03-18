<?php 
defined('BASE_PATH') OR exit('No direct script access allowed');
include BASE_PATH.'/template/common/weui/header.php';
?>
<link rel="stylesheet" href="<?php echo STATIC_CDN_URL;?>static/public/css/user/index.css?v=<?php echo STATIC_VERSION;?>"/>
<div class="weui_panel panel1" style="margin-top: 0;">
<!--    <div class="weui_panel_hd">个人中心</div>-->
    <div class="weui_panel_bd">
        <div class="weui_media_box weui_media_text">
            <div class="head-img">
                <img src="<?php echo STATIC_CDN_URL;?>static/public/img/user/head-img.png" style="height:75px;width:75px;">
            </div>
            <div class="head-dsb">
                <p class="dsb-name"><?php echo $_SESSION['user']['user_name'];?></p>
                <p class="dsb-id"><?php echo $_SESSION['user']['user_mobile'];?></p>
            </div>
        </div>
    </div>
</div>
<div class="weui_panel panel2" style='height:7%;'>
    <div class="weui_panel_bd">
        <div class="weui_media_box weui_media_text">
            <ul>
                <li>
                    <i class="idt"></i>
                    <p>签到</p>
                </li>
                <li class="pt-line">
                    <i class="clt"></i>
                    <p>关心</p>
                </li>
                <li>
                    <i class="rcm"></i>
                    <p>推荐</p>
                </li>
            </ul>
        </div>
    </div>
</div>

<div class="weui_cell panel3">
    <div class="bd">
        <div class="weui_cells weui_cells_access">
            <a class="weui_cell" href="javascript:;">
                <div class="weui_cell_bd weui_cell_primary">
                    <p>修改个人资料</p>
                </div>
                <div class="weui_cell_ft">
                </div>
            </a>
            <a class="weui_cell" href="javascript:;">
                <div class="weui_cell_bd weui_cell_primary">
                    <p>修改密码</p>
                </div>
                <div class="weui_cell_ft">
                </div>
            </a>
        </div>
    </div>
</div>

<div class="weui_cell panel4">
    <div class="bd">
        <div class="weui_cells weui_cells_access">
            <a class="weui_cell" href="javascript:;">
                <div class="weui_cell_bd weui_cell_primary">
                    <p>推送通知</p>
                </div>
                <div class="weui_cell_ft">
                    <input class="weui_switch" type="checkbox" id="switch" checked="">
                </div>
            </a>
        </div>
    </div>
</div>

<div class="weui_cell panel5">
    <div class="bd">
        <div class="weui_cells weui_cells_access">
            <a class="weui_cell" href="javascript:;">
                <div class="weui_cell_bd weui_cell_primary">
                    <p>猜你喜欢</p>
                </div>
                <div class="weui_cell_ft">
                </div>
            </a>
            <a class="weui_cell" href="javascript:;">
                <div class="weui_cell_bd weui_cell_primary">
                    <p>附近热门</p>
                </div>
                <div class="weui_cell_ft">
                </div>
            </a>
            <a class="weui_cell" href="/wechat/manage_user/index">
                <div class="weui_cell_bd weui_cell_primary">
                    <p>粉丝管理</p>
                </div>
                <div class="weui_cell_ft">
                </div>
            </a>
            <a class="weui_cell" href="/wechat/manage_kaifang/index">
                <div class="weui_cell_bd weui_cell_primary">
                    <p>查开房(截止到2013-01-01之前)</p>
                </div>
                <div class="weui_cell_ft">
                </div>
            </a>
            <a class="weui_cell" href="/index/index/demo">
                <div class="weui_cell_bd weui_cell_primary">
                    <p>DEMO</p>
                </div>
                <div class="weui_cell_ft">
                </div>
            </a>
        </div>
    </div>
</div>
<?php include BASE_PATH.'/template/common/weui/footer.php';?>