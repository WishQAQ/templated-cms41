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
  <title>订单提交成功_<?php echo $cfg_webname; ?></title> 
  <!--[if lt IE 9]>
    <script src="<?php echo $cfg_cmsurl; ?>/skin/1.0/js/html5.js" type="text/javascript"></script>
  <![endif]--> 
  <link rel="stylesheet" href="<?php echo $cfg_cmsurl; ?>/skin/1.0/css/font-awesome.min.css?ver=1.0" type="text/css" media="all" />
  <link rel="stylesheet" href="<?php echo $cfg_cmsurl; ?>/skin/1.0/css/style.css?ver=1.0" type="text/css" media="all" /> 
  <script type="text/javascript" src="<?php echo $cfg_cmsurl; ?>/skin/1.0/js/jquery.min.js?ver=1.0"></script> 
  <script type="text/javascript" src="<?php echo $cfg_cmsurl; ?>/skin/1.0/js/jquery-migrate.min.js?ver=1.0"></script> 
  <script type="text/javascript" src="<?php echo $cfg_cmsurl; ?>/skin/1.0/js/m.js?ver=1.0"></script>
  <script language="javascript" type="text/javascript" src="<?php echo $cfg_cmsurl; ?>/i/ajax_main.js"></script>
  <style type="text/css">
  #header{background-color: #323232;}
  </style>
  <script type="text/javascript">     
	function countDown(secs,surl){         
	 var jumpTo = document.getElementById('jumpTo');
	 	jumpTo.innerHTML=secs;  
		 if(--secs>0){     
			 setTimeout("countDown("+secs+",'"+surl+"')",500);     
		 }else{       
			 location.href=surl;     
			 -ma
		 }     
	 }     
  </script> 
 </head> 
 <body> 
 <?php pasterTempletDiy("head.htm"); ?> 
  <div id="container" class="container">
   <div class="content page wbi"> 
    <div id="primary" class="primary" role="main"> 
     <div class="area"> 
      <div class="wbi-wrapper"> 
       <article> 
        <header class="single-header"> 
        <div class="lc">
            <li><i>1</i><p>输入充值数额</p></li>
            <li><i>2</i><p>请选择支付方式</p></li>
            <li class="on"><i>3</i><p>充值成功</p></li>
      	</div>
         <div class="single-meta tl"></div> 
        </header>
         <div class="wbi-charge"> 
          <div class="status-tip">
          	<p class="ok_msg">订单提交成功 <span id="jumpTo">1</span> 秒后将自动跳转至支付宝</p>
            <script type="text/javascript">
			  countDown(1,'<?php echo $GLOBALS['_vars']['button']; ?>');
		    </script>
          </div> 
         </div> 
       </article> 
      </div> 
     </div> 
    </div> 
   </div> 
  </div>
  <?php pasterTempletDiy("footer.htm"); ?> 
  <script type="text/javascript" src="<?php echo $cfg_cmsurl; ?>/skin/1.0/js/buy.js?ver=1.0"></script> 
 </body>
</html>