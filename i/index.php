<?php
/**
 	跳转
 */

require_once(dirname(__FILE__)."/../include/common.inc.php");

if($type == 'login')
{
	require_once(dirname(__FILE__)."/../".$cfg_memberurl."/login.php");
}
else if($type == 'exit')
{
	$fmdo = "weblogin";
	$dopost = "exit";
	require_once(dirname(__FILE__)."/../".$cfg_memberurl."/index_do.php");	
}
else if($type == 'do')
{
	require_once(dirname(__FILE__)."/../".$cfg_memberurl."/index_do.php");	
}
else if($type == 'buy_action')
{
	require_once(dirname(__FILE__)."/../".$cfg_memberurl."/buy_action.php");	
}
else if($type == 'userreg')
{
	require_once(dirname(__FILE__)."/../".$cfg_memberurl."/reg_new.php");
}
else if($url == 'my_work' || $totalresult)
{
	require_once(dirname(__FILE__)."/../".$cfg_memberurl."/my.php");	
}
else if($url == 'del')
{
	require_once(dirname(__FILE__)."/../".$cfg_memberurl."/del.php");	
}
else if($type == 'search' || $a == 'search')
{
	require_once(dirname(__FILE__)."/../".$cfg_phpurl."/search.php");	
}
else if($g == 'dow')
{
	require_once(dirname(__FILE__)."/../".$cfg_phpurl."/d.php");	
}
else if($type == 'reg')
{
	require_once(dirname(__FILE__)."/../".$cfg_memberurl."/reg_new.php");	
}
else
{
	require_once(dirname(__FILE__)."/../".$cfg_memberurl."/index.php");	
}

?>