<?php
/**
	用户登录
 */
require_once(dirname(__FILE__)."/config.php");
if($cfg_ml->IsLogin())
{
    ShowMsg('你已经登陆系统，无需重新注册！', '/i/',0,8);
    exit();
}
require_once(dirname(__FILE__)."/templets/login.htm");