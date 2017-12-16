<?php
/*
	AJAX登录信息调用
*/
require_once(dirname(__FILE__)."/../qm_user/config.php");
AjaxHead();
if($myurl == '') exit('');

?>

<div class="user-admin"><a id="user-admin" href="<?php echo $cfg_cmsurl; ?>/i/" title="控制台">控制台<i class="fa fa-caret-down"></i></a></div>
<div class="user-avatar"><img src="<?php echo $cfg_ml->fields['face']; ?>" class="avatar" alt="<?php echo $cfg_ml->M_UserName; ?>" width="30" height="30"></div>
<div class="user-panel">
<a href="<?php echo $cfg_cmsurl;?>/i/?uid=<?php echo $cfg_ml->M_LoginID;?>" class="username"><?php echo $cfg_ml->M_UserName; ?></a>
<a href="/i/?type=buy"><i class="fa fa-shopping-cart"></i>金币充值</a>
<a href="/i/?url=my_work&type=dows"><i class="fa fa-dows"></i>下载记录</a>
<a href="/i/?type=editinfo"><i class="fa fa-cog"></i>编辑资料</a>
<a id="user-logout" onclick="Doexit();"><i class="fa fa-sign-out"></i>退出帐号</a>
<!--a href="javascript:alert('未启用');"><i class="fa fa-pencil-square-o"></i>发布文章</a-->
</div>

