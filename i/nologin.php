<?php
/**
	用户未登录显示
**/
require_once(dirname(__FILE__)."/../include/common.inc.php");
require_once(DEDEINC."/memberlogin.class.php");
$ml = new MemberLogin();
$mid = $ml->M_ID;


if(preg_match("/2/",$safe_gdopen)){
	$vdcodeval = "<p class='vdcode'><label class='icon'></label><input class='input-control' type='text' id='vdcode' name='vdcode' placeholder='验证码' style='width:72%;text-transform:uppercase;'><img id='vdimgck' align='absmiddle' onclick=this.src=this.src+'?' style='cursor:pointer;' alt='看不清？点击更换' class='vdcodeimg' src='".$cfg_cmsurl."/include/vdimgck.php'/></p>";
	$vdcodeval2 = "<p class='vdcode_reg'><label class='icon'></label><input class='input-control' type='text' id='vdcode_reg' name='vdcode_reg' placeholder='验证码' style='width:72%;text-transform:uppercase;'><img id='vdcode_reg' align='absmiddle' onclick=this.src=this.src+'?' style='cursor:pointer;' class='vdcodeimg' alt='看不清？点击更换' src='".$cfg_cmsurl."/include/vdimgck.php'/></p>";
}

if($mid == 0)
{
	echo "document.getElementById('loginreg').innerHTML=\"<div><a data-sign='0' id='user-signin' class='user-signin'>登录</a></div><div><a data-sign='1' id='user-reg' class='user-reg'>注册</a></div>\";";
	echo "document.getElementById('signin-toggle').innerHTML=\"<a data-sign='0' id='signin-icon' class='user-signin' aria-controls='#sign' aria-expanded='false'><i class='fa fa-user'></i></a>\";";
	if($cfg_mb_allowreg == 'Y')
	{
		echo "document.getElementById('sign').innerHTML=\"<div class='part loginPart'><form id='login' onsubmit='return Dologin();'><input type='hidden' name='type' value='do'><input type='hidden' name='fmdo' value='login'><input type='hidden' name='dopost' value='login'><input type='hidden' name='keeptime' value='604800'><div id='register-active' class='switch'><i class='fa fa-toggle-on'></i>切换注册</div><h3>登录<p class='status'></p></h3><p class='username'><label class='icon' for='username'><i class='fa fa-user'></i></label><input class='input-control' id='username' type='text' placeholder='请输入用户名' name='userid'></p><p class='password'><label class='icon' for='password'><i class='fa fa-lock'></i></label><input class='input-control' id='password' type='password' placeholder='请输入密码' name='pwd'></p>".$vdcodeval."<p class='safe'><label class='remembermetext' for='rememberme'><input name='rememberme' type='checkbox' checked='checked' id='rememberme' class='rememberme' value='forever'>记住我的登录</label><a class='lost' href='$cfg_cmsurl/i/?type=resetpassword'>忘记密码？</a></p><p><input class='submit' type='submit' value='登录' name='submit'></p><a class='close'><i class='fa fa-times'></i></a></form><div class='other-sign'><p>您也可以使用第三方帐号登录</p><div class='qq_login'><a rel='nofollow' class='qqlogin' onclick='qqlogin()'><i class='fa fa-qq'></i>QQ帐号登录</a></div></div></div><div class='part registerPart'><form id='register' onsubmit='return Doreg();'><input type='hidden' name='fmdo1' value='reg'><input type='hidden' name='qmold' id='qmold' value=''><div id='login-active' class='switch'><i class='fa fa-toggle-off'></i>切换登录</div><h3>注册<p class='status'></p></h3><p class='user_name'><label class='icon' for='user_name'><i class='fa fa-user'></i></label><input class='input-control' id='user_name' type='text' name='user_name' placeholder='输入英文登录名'></p><p class='user_pass'><label class='icon' for='user_pass'><i class='fa fa-lock'></i></label><input class='input-control' id='user_pass' type='password' name='user_pass' placeholder='密码最小长度为6'></p><p class='user_pass2'><label class='icon' for='user_pass2'><i class='fa fa-retweet'></i></label><input class='input-control' type='password' id='user_pass2' name='user_pass2' placeholder='再次输入密码'></p><p class='user_email'><label class='icon' for='user_email'><i class='fa fa-envelope'></i></label><input class='input-control' id='user_email' type='email' name='user_email' placeholder='输入常用邮箱'><a id='send_email'></a></p><p class='user_yzm' style='display:none;'><label class='icon' for='user_yzm'><i class='fa fa-key'></i></label><input class='input-control' id='user_yzm' type='text' name='user_yzm' placeholder='输入邮箱验证码'></p>".$vdcodeval2."<p><input class='submit' type='submit' value='注册' name='submit'></p><a class='close'><i class='fa fa-times'></i></a></form></div>\";";
		
	}
	else
	{
		echo "document.getElementById('sign').innerHTML=\"<div class='part loginPart'><form id='login' onsubmit='return Dologin();' method='post'><input type='hidden' name='type' value='do'><input type='hidden' name='fmdo' value='login'><input type='hidden' name='dopost' value='login'><input type='hidden' name='keeptime' value='604800'><div id='register-active' class='switch'><i class='fa fa-toggle-on'></i>切换注册</div><h3>登录<p class='status'></p></h3><p><label class='icon' for='username'><i class='fa fa-user'></i></label><input class='input-control' id='username' type='text' placeholder='请输入用户名' name='username'><label id='username-error' class='error' for='username'></label></p><p><label class='icon' for='password'><i class='fa fa-lock'></i></label><input class='input-control' id='password' type='password' placeholder='请输入密码' name='password'></p>".$vdcodeval."<p class='safe'><label class='remembermetext' for='rememberme'><input name='rememberme' type='checkbox' checked='checked' id='rememberme' class='rememberme' value='forever'>记住我的登录</label><a class='lost' href='$cfg_cmsurl/i/?type=resetpassword'>忘记密码？</a></p><p><input class='submit' type='submit' value='登录' name='submit'></p><a class='close'><i class='fa fa-times'></i></a></form><div class='other-sign'><p>您也可以使用第三方帐号登录</p><div class='qq_login'><a rel='nofollow' class='qqLogin' onclick='qqlogin()'><i class='fa fa-qq'></i>QQ帐号登录</a></div></div></div><div class='part registerPart' style='height:500px;text-align:center;overflow:hidden;padding-top:200px;'><form id='register'><p>管理员设定了不允许新会员注册</p><div id='login-active' class='switch'><i class='fa fa-toggle-off'></i>切换登录</div><a class='close'><i class='fa fa-times'></i></a></form></div>\";";
	}
	
}
else
{
	$uface = $ml->fields['face'];
	if($uface=='')
	{
		$uface = $cfg_cmsurl.'/images/noface.gif';
	}
	echo "document.getElementById('loginreg').innerHTML=\"<div class='user-admin'><a id='user-admin' href='$cfg_cmsurl/i/' title='控制台'>控制台<i class='fa fa-caret-down'></i></a></div><div class='user-avatar'><img src='$uface' class='avatar' alt='{$ml->M_UserName}' width='30' height='30'></div><div class='user-panel'><a class='username' href='$cfg_cmsurl/i/'>{$ml->M_UserName}</a><a href='$cfg_cmsurl/i/?type=buy'><i class='fa fa-shopping-cart'></i>金币充值</a><a href='$cfg_cmsurl/i/?url=my_work&type=dows'><i class='fa fa-dows'></i>下载记录</a><a href='$cfg_cmsurl/i/?type=editinfo'><i class='fa fa-cog'></i>编辑资料</a><a id='user-logout' onclick='return Doexit();'><i class='fa fa-sign-out'></i>退出帐号</a><!--a><i class='fa fa-pencil-square-o'></i>分享模板</a--></div>\";";
	echo "document.getElementById('sign').innerHTML=\"\";";
	echo "document.getElementById('signin-toggle').innerHTML=\"<a onclick='return Doexit();'><i class='fa fa-sign-in'></i></a>\";";
}

?>
