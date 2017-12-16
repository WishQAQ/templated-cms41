<?php
/**
 * 我的操作记录
 * 
 */
require_once(dirname(__FILE__)."/config.php");
CheckRank(0,0);
require_once(DEDEINC."/datalistcp.class.php");
setcookie("ENV_GOBACK_URL",GetCurUrl(),time()+3600,"/");
$uid = $cfg_ml->M_LoginID;

//查询会员mid
$row = $dsql->GetOne("SELECT * FROM `#@__member` WHERE userid='$uid'");
$mid = $row['mid'];


if($type == 'stow'){
	require_once(dirname(__FILE__)."/my_stows.php");
	exit();
}else if($type == 'likes'){
	require_once(dirname(__FILE__)."/my_likes.php");
	exit();
}else if($type == 'dows'){
	require_once(dirname(__FILE__)."/my_dows.php");
	exit();
}else if($type == 'comment'){
	require_once(dirname(__FILE__)."/my_comment.php");
	exit();
}else if($type == 'payopp'){
	require_once(dirname(__FILE__)."/my_payopp.php");
	exit();
}else if($type == 'kefu'){
	require_once(dirname(__FILE__)."/kefu.php");
	exit();
}else if($type == 'my_shouhou'){
	require_once(dirname(__FILE__)."/my_shouhou.php");
	exit();
}else{
	require_once(dirname(__FILE__)."/my_dows.php");
	exit();
}