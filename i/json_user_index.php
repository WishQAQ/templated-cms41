<?php
/**
	用户主页的收藏下载和喜欢数据展示
**/
$cfg_NotPrintHead = false;
header("Content-Type: text/html; charset=utf-8");
include_once (dirname(__FILE__)."/../include/common.inc.php");
error_reporting(E_ALL || ~E_NOTICE);
include_once (dirname(__FILE__)."/../include/json.class.php");

$aid = $aid;

$page = intval($_POST['pageNum']);
$userid = $dsql->GetOne("SELECT * FROM `#@__member` WHERE userid='$uid'");
$mid = $userid['mid'];

if($type == '' || $type == 'dows'){
	$typedb = "member_dows";
}else if($type == 'likes'){
	$typedb = "member_likes";
}else if($type == 'stows'){
	$typedb = "member_stow";
}
$tj = $dsql->GetOne("SELECT COUNT(aid) FROM `#@__$typedb`");
$total = $tj['COUNT(aid)'];//总记录数

$pageSize = 20; //每页显示数
$totalPage = ceil($total/$pageSize); //总页数

$startPage = $page*$pageSize;
$arr['total'] = $total;
$arr['pageSize'] = $pageSize;
$arr['totalPage'] = $totalPage;


if($type == '' || $type == 'dows')
{
	$dsql->SetQuery("select aid,addtime,title,litpic from `#@__$typedb` WHERE mid='$mid' order by id desc limit $startPage,$pageSize");
	$dsql->Execute('me');
	while ($row = $dsql->GetArray('me')) 
	{	
		if($row['litpic'] == "")
		{
		$row['litpic'] = $cfg_cmsurl."/images/defaultpic.gif";
		}
		 $arr['list'][] = array(
	 	'id' => $row['aid'],
		'senddate' => date('Y-m-d H:i',$row['addtime']),
		'title' => $row['title'],
		'litpic' => $row['litpic'],
		 );
	}
	
}
else if($type == 'likes')
{
	$dsql->SetQuery("select aid,addtime,title,litpic from `#@__$typedb` WHERE mid='$mid' order by id desc limit $startPage,$pageSize");
	$dsql->Execute('me');
	while ($row = $dsql->GetArray('me')) 
	{
		if($row['litpic'] == "")
		{
		$row['litpic'] = $cfg_cmsurl."/images/defaultpic.gif";
		}
		 $arr['list'][] = array(
	 	'id' => $row['aid'],
		'senddate' => date('Y-m-d H:i',$row['addtime']),
		'title' => $row['title'],
		'litpic' => $row['litpic'],
		 );
	}
}
else if($type == 'stows')
{
	$dsql->SetQuery("select aid,addtime,title,litpic from `#@__$typedb` WHERE mid='$mid' order by id desc limit $startPage,$pageSize");
	$dsql->Execute('me');
	while ($row = $dsql->GetArray('me')) 
	{
		if($row['litpic'] == "")
		{
		$row['litpic'] = $cfg_cmsurl."/images/defaultpic.gif";
		}
		 $arr['list'][] = array(
	 	'id' => $row['aid'],
		'senddate' => date('Y-m-d H:i',$row['addtime']),
		'title' => $row['title'],
		'litpic' => $row['litpic'],
	 	);
	}
}

$json = new Services_JSON(SERVICES_JSON_SUPPRESS_ERRORS);
echo json_encode($arr);

?>