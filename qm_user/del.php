<?php

/***关注喜欢收藏等可用此程序无刷新操作
****站窝窝网络出品 2015-08-23 请保留此信息
***/

require_once(dirname(__FILE__)."/config.php");
require_once(DEDEINC."/memberlogin.class.php");
$ml = new MemberLogin();
$mid = $ml->M_ID;

if($type != '' || $id == ''){
	if(!isset($id) || empty($id))
	{
		ShowMsg('错误操作！','-1');
		exit;
	}
	$dsql->ExecNoneQuery("Delete From #@__member_$type where aid='$id'");
	$dsql->ExecuteNoneQuery2("update #@__archives set $type=$type-1 where id='$id'");
	echo "<script>alert('删除成功');location.href='".$_SERVER["HTTP_REFERER"]."'</script>";
}else{
	ShowMsg('错误操作！','-1');
	exit();
}

?>
