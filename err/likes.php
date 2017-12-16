<?php

/***关注喜欢收藏等可用此程序无刷新操作
****站窝窝网络出品 2015-08-23 请保留此信息
***/

require_once(dirname(__FILE__)."/../include/common.inc.php");
require_once(DEDEINC."/memberlogin.class.php");
$ml = new MemberLogin();
//php 批量过滤post,get敏感数据
if (get_magic_quotes_gpc()) {
$_GET = stripslashes_array($_GET);
$_POST = stripslashes_array($_POST);
}
 
function stripslashes_array(&$array) {
while(list($key,$var) = each($array)) {
if ($key != 'argc' && $key != 'argv' && (strtoupper($key) != $key || ''.intval($key) == "$key")) {
if (is_string($var)) {
$array[$key] = stripslashes($var);
}
if (is_array($var))  {
$array[$key] = stripslashes_array($var);
}
}
}
return $array;
}
//--------------------------
 
// 替换HTML尾标签,为过滤服务
//--------------------------
function lib_replace_end_tag($str)
{
if (empty($str)) return false;
$str = htmlspecialchars($str);
$str = str_replace( '/', "", $str);
$str = str_replace("\\", "", $str);
$str = str_replace("&gt", "", $str);
$str = str_replace("&lt", "", $str);
$str = str_replace("<SCRIPT>", "", $str);
$str = str_replace("</SCRIPT>", "", $str);
$str = str_replace("<script>", "", $str);
$str = str_replace("</script>", "", $str);
$str=str_replace("select","select",$str);
$str=str_replace("join","join",$str);
$str=str_replace("union","union",$str);
$str=str_replace("where","where",$str);
$str=str_replace("insert","insert",$str);
$str=str_replace("delete","delete",$str);
$str=str_replace("update","update",$str);
$str=str_replace("like","like",$str);
$str=str_replace("drop","drop",$str);
$str=str_replace("create","create",$str);
$str=str_replace("modify","modify",$str);
$str=str_replace("rename","rename",$str);
$str=str_replace("alter","alter",$str);
$str=str_replace("cas","cast",$str);
$str=str_replace("&","&",$str);
$str=str_replace(">",">",$str);
$str=str_replace("<","<",$str);
$str=str_replace(" ",chr(32),$str);
$str=str_replace(" ",chr(9),$str);
$str=str_replace("    ",chr(9),$str);
$str=str_replace("&",chr(34),$str);
$str=str_replace("'",chr(39),$str);
$str=str_replace("<br />",chr(13),$str);
$str=str_replace("''","'",$str);
$str=str_replace("css","'",$str);
$str=str_replace("CSS","'",$str);
 
return $str;
 
}
if(is_numeric($id)){

$row = $dsql->GetOne("select * from #@__archives where id='$id'");
if($type == 'stows')
{
	if($ml->M_ID == 0)
	{
		echo "<i class='fa fa-shopping-cart_stows'></i>请先登录";
		exit();
	}
	$id = $id;
	$arctitle = $row['title']; 
	$arclitpic = $row['litpic'];
	$addtime = time();
	$mid = $ml->M_ID;
	if(!isset($id) || empty($id)) exit;
	$row = $dsql->GetOne("select mid from #@__member_stow where aid='$id' And mid='$mid'");
	if($row['mid'] != "$mid"){
		$dsql->ExecuteNoneQuery2("update #@__archives set stows=stows+1 where id='$id'");
		$dsql->ExecuteNoneQuery("insert into #@__member_stow (mid,aid,addtime) values ('".$ml->M_ID."','$id','$addtime')");
		$row = $dsql->GetOne("select stows from #@__archives where id='$id'");
		$stows = $row['stows'];
		echo "<i class='fa fa-shopping-cart_stows'></i>已收藏 ($stows)";
	}else{
		/*注销取消收藏
		$dsql->ExecNoneQuery("Delete From #@__member_stow where aid='$id'");
		$dsql->ExecuteNoneQuery2("update #@__archives set stows=stows-1 where id='$id'");
		*/
		$row = $dsql->GetOne("select stows from #@__archives where id='$id'");
		$stows = $row['stows'];
		echo "<i class='fa fa-shopping-cart_stows'></i>已收藏 ($stows)";
	}
	exit();
}
else if($type == 'likes')
{
	if($ml->M_ID == 0)
	{
		echo "<a class='heart-this loved'><span class='heart-text'>请先登录</spa></a><div class='loading-line'></div>";
		exit();
	}
	$id = $id;
	$addtime = time();
	$mid = $ml->M_ID;
	if(!isset($id) || empty($id)) exit;
	$row = $dsql->GetOne("select mid from #@__member_likes where aid='$id' And mid='$mid'");
	if($row['mid'] != "$mid"){
		$dsql->ExecuteNoneQuery2("update #@__archives set likes=likes+1 where id='$id'");
		$dsql->ExecuteNoneQuery("insert into #@__member_likes (mid,aid,addtime) values ('".$ml->M_ID."','$id','$addtime')");
		$row = $dsql->GetOne("select likes from #@__archives where id='$id'");
		$likes = $row['likes'];
		echo "<a class='heart-this'><i class='fa fa-heart'></i><span class='heart-text'>已喜欢</span> <span class='heart-no'>($likes)</span></a><div class='loading-line'></div>";
	}else{
		/*注销取消喜欢
		$dsql->ExecNoneQuery("Delete From #@__member_likes where aid='$id'");
		$dsql->ExecuteNoneQuery2("update #@__archives set likes=likes-1 where id='$id'");
		*/
		$row = $dsql->GetOne("select likes from #@__archives where id='$id'");
		$likes = $row['likes'];
		echo "<a class='heart-this'><i class='fa fa-heart'></i><span class='heart-text'>已喜欢</span> <span class='heart-no'>($likes)</span></a><div class='loading-line'></div>";
	}
	exit();
}
}else{
	ShowMsg("非法操作！","javascript:window.close();");
	exit(0);
}
?>
