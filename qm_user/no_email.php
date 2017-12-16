<?php
/**
 	未验证邮件
 */
require_once(dirname(__FILE__)."/config.php");

$uid=empty($uid)? "" : RemoveXSS($uid); 
$menutype = 'no_emaeil';


$row = $dsql->GetOne("SELECT * FROM `#@__member` WHERE mid='{$cfg_ml->M_ID}' ");
$email = $row['email'];

?>

<!DOCTYPE html>
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
  <title>邮箱验证_<?php echo $cfg_webname; ?></title> 
  <!--[if lt IE 9]>
    <script src="<?php echo $cfg_cmsurl; ?>/skin/1.0/js/html5.js" type="text/javascript"></script>
    <![endif]--> 
  <link rel="stylesheet" href="<?php echo $cfg_cmsurl; ?>/skin/1.0/css/font-awesome.min.css?ver=1.0" type="text/css" media="all" /> 
  <link rel="stylesheet" href="<?php echo $cfg_cmsurl; ?>/skin/1.0/css/style.css?ver=1.0" type="text/css" media="all" /> 
  <link rel="stylesheet" href="<?php echo $cfg_cmsurl; ?>/skin/1.0/css/user_style.css?ver=1.0" type="text/css" media="all" /> 
  <script type="text/javascript" src="<?php echo $cfg_cmsurl; ?>/skin/1.0/js/jquery.min.js?ver=1.0"></script> 
  <script type="text/javascript" src="<?php echo $cfg_cmsurl; ?>/skin/1.0/js/jquery-migrate.min.js?ver=1.0"></script> 
  <script type="text/javascript" src="<?php echo $cfg_cmsurl; ?>/skin/1.0/js/m.js?ver=1.0"></script>
  <script language="javascript" type="text/javascript" src="<?php echo $cfg_cmsurl; ?>/include/dedeajax2.js"></script>
  <style type="text/css">
  #header{background-color: #323232;}
  </style>
 </head> 
 <body>
 <?php pasterTempletDiy("head.htm"); ?>
    <div id="container" class="container"> 
   <div class="content page dashboard centralnav"> 
    <div id="primary" class="primary" role="main"> 
     <div class="validate"> 
      <div class="tip">
       您暂无权限使用<?php echo $cfg_webname; ?>后台服务功能，请先验证您的电子邮箱！
      </div> 
      <form method="post" id="user-form" action="<?php echo $cfg_cmsurl; ?>/i/">
      <input type="hidden" name="type" value="do" />
      <input type="hidden" name="fmdo" value="websendMail" />
       <ul id="email-form" class="email-form"> 
        <li><input type="text" id="user_email" class="regular-text" placeholder="请输入邮箱" name="user_email" required value="<?php echo $email; ?>" /></li> 
        <li><label></label><input data-apply="0" id="email-verify" class="regular-button" type="submit" value="获取验证邮件" name="submit" /></li> 
       </ul> 
      </form>
      <div class="status-tip">
       输入您的邮箱后，系统会自动发送一封邮件至您的邮箱，请在10分钟内完成验证。
       <p>如果您已经验证成功，请点击<a href="javascript:location.reload()">刷新</a>本页面。</p>
      </div> 
     </div> 
    </div> 
   </div>
  </div>
  <?php pasterTempletDiy("footer.htm"); ?>
 </body>
</html>