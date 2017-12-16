<?php
/**
	模板详情页下载记录JSON
**/

$cfg_NotPrintHead = false;
header("Content-Type: text/html; charset=utf-8");
include_once (dirname(__FILE__)."/../include/common.inc.php");

include_once (dirname(__FILE__)."/../include/json.class.php");

$page = intval($pageNum);
$tj = $dsql->GetOne("SELECT COUNT(aid) FROM `#@__addonstore`");
$total = $tj['COUNT(aid)'];//总记录数
$pageSize = 8; //每页显示数
$totalPage = ceil($total/$pageSize); //总页数
$startPage = $page*$pageSize;
$arr['total'] = $total;
$arr['pageSize'] = $pageSize;
$arr['totalPage'] = $totalPage;
	
//$wheresql = " WHERE op.arcid > 0 AND op.product='archive'";
$sql = "SELECT op.*,ms.*,typeurl.* FROM `#@__addonstore` op
        LEFT JOIN `#@__archives` ms ON ms.id = op.aid
		LEFT JOIN `#@__arctype` typeurl ON typeurl.id = ms.typeid
        $wheresql order by ms.pubdate DESC LIMIT  $startPage,$pageSize";
$dsql->SetQuery($sql);
$dsql->Execute('me');
while ($row = $dsql->GetArray('me')) {
	if($row['dows']==''){
		$row['dows'] = 0;
	}
	
	if($row['stows']==''){
		$row['stows'] = 0;
	}
	
	if($row['litpic']==''){
		$row['litpic'] = $cfg_cmsurl.'/images/defaultpic.gif';
	}
	
	$row['arcurl'] = GetFileUrl($row['aid'],$row['typeid'],$row['senddate'],$row['title'],$row['ismake'],$row['arcrank'],$row['namerule'],$row['typedir'],$row['money'],$row['filename'],$row['moresite'],$row['siteurl'],$row['sitepath']);
	
	$row['typeurl'] = GetTypeUrl($row['typeid'],$row['typedir'],$row['isdefault'],$row['defaultname'],$row['ispart'],$row['namerule2'],$row['moresite'],$row['siteurl'],$row['sitepath']);
	
	$arr['list'][] = array(
	'title' => $row['title'],
	'arcurl' => $row['arcurl'],
	'dows' => $row['dows'],
	'stows' => $row['stows'],
	'typeurl' => $row['typeurl'],
	'typename' => $row['typename'],
	'click' => $row['click'],
	'litpic' => $row['litpic'],
	'pubdate' => date('Y-m-d H:i',$row['pubdate'])
	);
}

$json = new Services_JSON(SERVICES_JSON_SUPPRESS_ERRORS);
echo json_encode($arr);

?>