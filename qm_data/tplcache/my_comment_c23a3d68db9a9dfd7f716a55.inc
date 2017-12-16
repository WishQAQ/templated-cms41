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
  <title>我的评论_<?php echo $GLOBALS['cfg_webname']; ?></title> 
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
  .not-found{ text-align:center;}
  .pageList{ margin-top:20px; text-align:center;}
  .pageList span, .pageList a, .pageList strong{background-color:#f5f5f5;padding:10px 20px;border-radius:4px;display:inline-block;margin: 0 5px;vertical-align:top;color:#333;box-shadow:0 1px 1px rgba(0,0,0,.1);-webkit-transition: .15s all linear;transition: .15s all linear; border:1px solid #e1e1e1;}
  .pageList a:hover{background-color:#00c3b6; color:#FFF; border:1px solid #00c3b6;}
  .my-list td{ border-top:1px solid #eee; border-right:1px solid #eee;}
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
       <div class="dashboard-wrapper select-wbi"> 
        <h3>我的评论（<?php echo $datatj;?>）</h3> 
        <table class="widefat"> 
         <thead> 
          <tr> 
           <th scope="col">时间</th> 
           <th scope="col">文档</th> 
           <th scope="col">评论内容</th> 
           <th scope="col">管理</th>
          </tr> 
         </thead> 
         <?php
$atts = array();
$atts['tagname'] = 'datalist';
$blockValue = $this->refObj->GetArcList($atts,$this->refObj,$fields); 
if(is_array($blockValue)){
foreach( $blockValue as $key=>$fields )
{
?>
         <tbody>
          <tr class="my-list"> 
           <td><?php echo MyDate('Y-m-d',$fields['dtime']); ?></td>
           <td><?php echo $fields['arctitle']; ?></td>
           <td><?php echo $fields['msg']; ?></td>
           <td><a href="<?php echo $GLOBALS['cfg_cmsurl']; ?>/i/?url=my_work&type=comment&dopost=del&ids=<?php echo $fields['id']; ?>&aid=<?php echo $fields['aid']; ?>">删除</a></td>
          </tr>
         </tbody> 
         <?php
}
}?>
        </table> 
        <div class="pageList"><?php
$atts = array();
$atts['tagname'] = 'pagelist';
$atts['listsize'] = '5';
 echo $this->refObj->GetPageList($atts,$this->refObj,$fields); 
?>
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