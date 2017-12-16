<?php
if(!isset($GLOBALS['_vars'])) $GLOBALS['_vars'] = array(); 
$fields = array();
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" lang="zh-CN">
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" lang="zh-CN">
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html lang="zh-CN">
 <!--<![endif]-->
 <head> 
  <meta charset="UTF-8" /> 
  <meta name="keywords" content="" /> 
  <meta name="description" content="" /> 
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" /> 
  <meta name="renderer" content="webkit" /> 
  <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
  <meta content="yes" name="apple-mobile-web-app-capable">
  <meta content="black" name="apple-mobile-web-app-status-bar-style">
  <meta content="telephone=no" name="format-detection">
  <title>用户中心_<?php echo $GLOBALS['cfg_webname']; ?></title> 
  <!--[if lt IE 9]>
    <script src="<?php echo $GLOBALS['cfg_cmsurl']; ?>/skin/1.0/js/html5.js" type="text/javascript"></script>
  <![endif]--> 
  <link rel="stylesheet" href="<?php echo $GLOBALS['cfg_cmsurl']; ?>/skin/1.0/css/font-awesome.min.css?ver=1.0" type="text/css" media="all" /> 
  <link rel="stylesheet" href="<?php echo $GLOBALS['cfg_cmsurl']; ?>/skin/1.0/css/style.css?ver=1.0" type="text/css" media="all" /> 
  <link rel="stylesheet" href="<?php echo $GLOBALS['cfg_cmsurl']; ?>/skin/1.0/css/user_style.css?ver=1.0" type="text/css" media="all" /> 
  <script type="text/javascript" src="<?php echo $GLOBALS['cfg_cmsurl']; ?>/skin/1.0/js/jquery.min.js?ver=1.0"></script> 
  <script type="text/javascript" src="<?php echo $GLOBALS['cfg_cmsurl']; ?>/skin/1.0/js/jquery-migrate.min.js?ver=1.0"></script> 
  <script type="text/javascript" src="<?php echo $GLOBALS['cfg_cmsurl']; ?>/skin/1.0/js/m.js?ver=1.0"></script>
  <script language="javascript" type="text/javascript" src="<?php echo $GLOBALS['cfg_cmsurl']; ?>/i/ajax_main.js"></script>
  <style type="text/css">
  #header{background-color: #323232;}
  </style>
 </head> 
 <body>
 <?php pasterTempletDiy("head.htm"); ?>
  <div id="container" class="container">
   <div class="content page dashboard space centralnav"> 
    <div id="primary" class="primary" role="main"> 
     <div class="area"> 
      <div class="page-wrapper"> 
       <div class="dashboard-main"> 
        <div class="dashboard-header">
         <p class="sub-title">用户中心</p>
         <p class="tip">亲爱的 <i class="uname"><?php echo $cfg_ml->M_UserName; ?></i> 您好，欢迎访问<?php echo $GLOBALS['cfg_webname']; ?>用户中心</p>
        </div> 
        <div class="dashboard-wrapper select-index"> 
         <div class="briefly"> 
          <ul> 
          <!--li class="post"><div class="visual"><i class="fa fa-tasks"></i></div><div class="number"><?php echo $pms['nums']; ?><span>短消息</span></div><div class="more"><a href="javascript:alert('未启用短消息');">查看更多<i class="fa fa-arrow-circle-right"></i></a></div></li--> 
          <li class="post"><div class="visual"><i class="fa fa-bug"></i></div><div class="number"><?php echo $data_gongdan; ?><span>我的工单</span></div><div class="more"><a href="<?php echo $cfg_cmsurl; ?>/i/?url=my_work&type=my_shouhou&dopost=list">查看更多<i class="fa fa-arrow-circle-right"></i></a></div></li>
           <li class="photo"> 
            <div class="visual">
             <i class="fa fa-heartbeat"></i>
            </div> 
            <div class="number"><?php echo $data_dows; ?><span>下载过</span></div> 
            <div class="more">
             <a href="<?php echo $cfg_cmsurl; ?>/i/?url=my_work&type=dows">查看更多<i class="fa fa-arrow-circle-right"></i></a>
            </div> </li> 
           <li class="credit"> 
            <div class="visual">
             <i class="fa fa-credit-card"></i>
            </div> 
            <div class="number"><?php echo $money; ?>.00<span>剩余金币</span></div> 
            <div class="more">
             <a href="<?php echo $cfg_cmsurl; ?>/i/?url=my_work&type=payopp">查看更多<i class="fa fa-arrow-circle-right"></i></a>
            </div> </li> 
           <li class="comments"> 
            <div class="visual">
             <i class="fa fa-comments"></i>
            </div> 
            <div class="number"><?php echo $data_feedback; ?><span>评论数量</span></div> 
            <div class="more">
             <a href="<?php echo $cfg_cmsurl; ?>/i/?url=my_work&type=comment">查看更多<i class="fa fa-arrow-circle-right"></i></a>
            </div> </li> 
          </ul> 
         </div> 
         <div class="summary" style="display:none;"> 
          <div class="box"> 
           <div class="title">我的最近发布</div> 
           <ul> 
            <li><span class="post-time">2015-09-05</span>[正常]<a href="#" title="企业模板">企业模板</a></li>
            <li>您还没发布过任何内容。</li> 
           </ul> 
          </div> 
          <div class="box"> 
           <div class="title">我的最近草稿</div> 
           <ul> 
            <li><span class="post-time">2015-09-05</span>[审核中]<a href="#" title="大气HTML5模板">大气HTML5模板</a></li>
            <li>暂无未发布文章或模板。</li> 
           </ul> 
          </div> 
         </div> 
         <div class="fast-navigation"> 
          <div class="nav-title">快捷菜单</div> 
          <ul> 
           <li><a href="<?php echo $GLOBALS['cfg_cmsurl']; ?>/i/?uid=<?php echo $cfg_ml->M_LoginID; ?>"><i class="fa fa-home"></i>我的主页</a></li> 
           <li><a href="<?php echo $GLOBALS['cfg_cmsurl']; ?>/i/?url=my_work&type=payopp"><i class="fa fa-diamond"></i>消费明细</a></li> 
           <!--li><a href="javascript:alert('开发中');"><i class="fa fa-shopping-cart"></i>我的订单</a></li--> 
           <li><a href="<?php echo $GLOBALS['cfg_cmsurl']; ?>/i/?url=my_work&type=my_shouhou&dopost=list"><i class="fa fa-bug"></i>模板售后</a></li>
           <!--li><a href="javascript:alert('开发中');"><i class="fa fa-pencil-square-o"></i>发布文章</a></li-->
           <li><a href="<?php echo $GLOBALS['cfg_cmsurl']; ?>/i/?url=my_work&type=dows"><i class="fa fa fa-dows"></i>下载记录</a></li>  
           <!--li><a href="javascript:alert('开发中');"><i class="fa fa-upload"></i>上传模板</a></li--> 
           <li><a href="<?php echo $GLOBALS['cfg_cmsurl']; ?>/i/?url=my_work&type=stow"><i class="fa fa-shopping-cart_stows"></i>收藏夹</a></li>
           <li><a href="<?php echo $GLOBALS['cfg_cmsurl']; ?>/i/?type=editinfo"><i class="fa fa-cog"></i>修改资料</a></li> 
           <li><a href="<?php echo $GLOBALS['cfg_cmsurl']; ?>/i/?type=editface"><i class="fa fa-user-secret"></i>修改头像</a></li> 
           <li class="tab-index"><a href="<?php echo $cfg_cmsurl; ?>/i/?type=buy"><i class="fa fa-shopping-cart"></i>充值金币</a></li>
           <li><a href="<?php echo $GLOBALS['cfg_cmsurl']; ?>/i/?type=exit"><i class="fa fa-sign-out"></i>退出登录</a></li> 
          </ul> 
         </div> 
         <div class="fast-navigation"> 
          <div class="nav-title">最新模板</div> 
          <ul id="Comment">
          </ul> 
          <div class="feel-go"><a href="<?php echo $cfg_cmsurl; ?>/moban/" target="_blank" title="更多">更多模板 <i class="fa fa-chevron-circle-right"></i></a></div>
         </div>
        </div> 
       </div> 
      </div> 
     </div> 
    <?php include(DEDEMEMBER."/templets/menu.php"); ?>
    </div> 
   </div> 
  </div>
  <?php pasterTempletDiy("footer.htm"); ?>
  <script type="text/javascript" language="javascript" src="<?php echo $GLOBALS['cfg_cmsurl']; ?>/i/add_index_ajax.php?channel=4"></script>
 </body>
</html>