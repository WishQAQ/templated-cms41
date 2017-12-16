<?php   if(!defined('DEDEMEMBER')) exit('dedecms');
/**
 	用户主页
*/
$space = $dsql->GetOne("SELECT * FROM `#@__member` WHERE userid='$uid'");
$mid_space = $space['mid'];

//用户后台数据统计
//下载总数
$dowtj = $dsql->GetOne("SELECT COUNT(id) FROM `#@__member_dows` WHERE mid='$mid_space'");
$dowtj = $dowtj['COUNT(id)'];

//喜欢总数
$likestj = $dsql->GetOne("SELECT COUNT(id) FROM `#@__member_likes` WHERE mid='$mid_space'");
if(!$likestj){
	$likestj = 0;
}else{
	$likestj = $likestj['COUNT(id)'];
}

//收藏总数
$stowstj = $dsql->GetOne("SELECT COUNT(id) FROM `#@__member_stow` WHERE mid='$mid_space'");
$stowstj = $stowstj['COUNT(id)'];

$user = $dsql->GetOne("SELECT * FROM `#@__member` WHERE userid='$uid'");
$username = $user['uname'];
$space_face = $user['face'];

if($type == 'dows' || $type == '')
{
	if($dowtj <= 0)
	{
		$nodata = "<div class='not-found'>$username 还没下载过任何内容。</div>";
	}
	else
	{
		$nodata = '';
	}
	$dows = "class='current-cat'";
}
else if($type == 'likes')
{
	if($likestj <= 0)
	{
		$nodata = "<div class='not-found'>$username 还没喜欢过任何内容。</div>";
	}
	else
	{
		$nodata = '';
	}
	$likes = "class='current-cat'";
}
else if($type == 'stows')
{
	if($stowstj <= 0)
	{
		$nodata = "<div class='not-found'>$username 还没收藏过任何内容。</div>";
	}
	else
	{
		$nodata = '';
	}
	$stows = "class='current-cat'";
}

if($space_face == '')
{	if($user['matt'] == '10'){
		$space_face = $cfg_cmsurl."/images/admin_pic.png";
	}else{
		$space_face = $cfg_cmsurl."/images/noface.gif";
	}
}
$user_bgo = $user['user_bg'];
if($user_bgo == '')
{
	$user_bgo = $cfg_cmsurl."/skin/1.0/images/user-bg.jpg";
}
include_once(DEDEINC.'/arc.memberlistview.class.php');
$dlist = new MemberListview();
$dlist->SetTemplate(DEDEMEMBER."/templets/index_space.htm");
$dlist->Display();
exit();
