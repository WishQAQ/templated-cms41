<?php
/**
	喜欢和收藏未登录和已登录状态显示
**/
require_once(dirname(__FILE__)."/../include/common.inc.php");
require_once(DEDEINC."/memberlogin.class.php");
$ml = new MemberLogin();

/******************************************收藏 stows******************************************/
$row = $dsql->GetOne("select * from #@__archives where id='$aid'");
$stows = $row['stows'];
$s = $dsql->GetOne("select * from #@__member_stow where aid='$aid' and mid='$ml->M_ID'");
if($row['title'] == $s['title'])
{
	echo "document.getElementById('stows').innerHTML=\"<i class='fa fa-shopping-cart_stows'></i>已收藏 ($stows)\"";
}
else
{
	echo "document.getElementById('stows').innerHTML=\"<i class='fa fa-shopping-cart_stows'></i>收藏 ($stows)\"";
}


?>

<?
if($row['dows'] <= 0)
{
	echo "document.getElementById('list_dows').innerHTML=\"<span>暂无下载记录</span>\"";
}

?>