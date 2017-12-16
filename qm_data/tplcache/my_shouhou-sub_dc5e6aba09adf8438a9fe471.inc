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
  <title>售后工单填写_<?php echo $GLOBALS['cfg_webname']; ?></title> 
  <!--[if lt IE 9]>
    <script src="<?php echo $GLOBALS['cfg_cmsurl']; ?>/skin/1.0/js/html5.js" type="text/javascript"></script>
  <![endif]--> 
  <link rel="stylesheet" href="<?php echo $GLOBALS['cfg_cmsurl']; ?>/skin/1.0/css/font-awesome.min.css?ver=1.0" type="text/css" media="all" /> 
  <link rel="stylesheet" href="<?php echo $GLOBALS['cfg_cmsurl']; ?>/skin/1.0/css/style.css?ver=1.0" type="text/css" media="all" /> 
  <link rel="stylesheet" href="<?php echo $GLOBALS['cfg_cmsurl']; ?>/skin/1.0/css/dashboard.css?ver=1.0" type="text/css" media="all" /> 
  <script type="text/javascript" src="<?php echo $GLOBALS['cfg_cmsurl']; ?>/skin/1.0/js/jquery.min.js?ver=1.0"></script> 
  <script type="text/javascript" src="<?php echo $GLOBALS['cfg_cmsurl']; ?>/skin/1.0/js/jquery-migrate.min.js?ver=1.0"></script> 
  <script type="text/javascript" src="<?php echo $GLOBALS['cfg_cmsurl']; ?>/skin/1.0/js/m.js"></script> 
  <script language="javascript" type="text/javascript" src="<?php echo $GLOBALS['cfg_cmsurl']; ?>/i/ajax_main.js"></script>
  <style type="text/css">
  #header{background-color: #323232;}
  .dashboard .gird li .box{margin:0;margin-right:10px;}
  .dashboard-header{padding-bottom:10px; margin-bottom:10px; border-bottom:1px dotted #dedede;}
  .not-found{text-align:center;}
  .dashboard .page-wrapper ul label{width:56px;}
  .dashboard .page-wrapper ul .regular-text.large{width:521px;}
  .dashboard .page-wrapper ul li.submit-block{text-align:left; margin-top:10px;}
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
        <p class="sub-title">售后工单填写</p>
        <p class="tip">提示：请根据您需要售后的实际情况填写工单，工单提交后客服会在24小时内处理（周末除外）。</p>
       </div> 
       <div class="dashboard-wrapper select-newWorks"> 
       <div> 
        <form class="post-form" action="<?php echo $GLOBALS['cfg_cmsurl']; ?>/i/gongdan-sub.php" method="POST" autocomplete="off">
          <input type="hidden" name="dopost" value="sub_ok" />
          <input type="hidden" name="aid" value="<?php echo $ids;?>" />
         <ul> 
         <li> <label for="post-cat">售后模板<small class="required">*</small></label> 
         <input name="arctitle" type="text" class="regular-text large" value="<?php echo $row['title'];?>" readonly /></li>
          <li> <label for="post-cat">工单标题<small class="required">*</small></label> 
          <input type="text" name="title" class="regular-text large" minlength="2" placeholder="工单标题" required value="" /> </li>
          <li> <label for="post-cat">分类目录<small class="required">*</small></label> 
           <div class="select-wrap"> 
            <select name="type" id="post-cat" class="post-cat"> 
                <option value="其它">— 请选择分类 —</option>
                <option class="level-0" value="模板修改">模板修改</option>
                    <option class="level-1" value="增加模块">—增加模块</option>
                    <option class="level-1" value="图片修改">—图片修改</option>
                    <option class="level-1" value="文字修改">—文字修改</option>
                    <option class="level-1" value="BUG修复">—BUG修复</option>
                <option class="level-0" value="程序修改">程序修改</option>
                    <option class="level-1" value="功能新增">—功能新增</option>
                    <option class="level-1" value="程序修复">—程序修复</option>
                <option class="level-0" value="其它">其它</option>
            </select> 
           </div> </li>  
          <li> <label for="post-cat">详细描述<small class="required">*</small></label> 
          <textarea name="description" id="description" class="description" placeholder="工单描述" required rows="5" cols="30"></textarea> </li> 
          <li class="submit-block"> <label for="post-cat"></label> <input class="regular-button" type="submit" value="提交" /></li> 
         </ul> 
        </form> 
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
  <script type="text/javascript" src="<?php echo $GLOBALS['cfg_cmsurl']; ?>/skin/1.0/js/functions.min.js?ver=1.0"></script> 
  <script type="text/javascript" src="<?php echo $GLOBALS['cfg_cmsurl']; ?>/skin/1.0/js/comment-reply.min.js?ver=1.0"></script> 
  <script type="text/javascript" src="<?php echo $GLOBALS['cfg_cmsurl']; ?>/skin/1.0/js/ajax-comments.min.js?ver=1.0"></script> 
  <script type="text/javascript">
	/* <![CDATA[ */
	var ajax_dashboard_object = {"ajaxurl":"","redirecturl":""};
	var heartbeat_object = {"nonce":""};
	var relove_object = {"nonce":""};
	/* ]]> */
  </script> 
  <script type="text/javascript" src="<?php echo $GLOBALS['cfg_cmsurl']; ?>/skin/1.0/js/dashboard.min.js?ver=1.0"></script> 
 </body>
</html>