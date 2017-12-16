
<?php
/**
	模板详情页下载记录JSON
**/

$cfg_NotPrintHead = false;
header("Content-Type: text/html; charset=utf-8");
include_once (dirname(__FILE__)."/../include/common.inc.php");

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



error_reporting(E_ALL || ~E_NOTICE);
include_once (dirname(__FILE__)."/../include/json.class.php");

if(is_numeric($aid)){


	$page = intval($pageNum);
	$tj = $dsql->GetOne("SELECT COUNT(id) FROM `#@__member_dows` WHERE aid=$aid");
	$total = $tj['COUNT(id)'];//总记录数
	
	$pageSize = 20; //每页显示数
	$totalPage = ceil($total/$pageSize); //总页数
	
	$startPage = $page*$pageSize;
	$arr['total'] = $total;
	$arr['pageSize'] = $pageSize;
	$arr['totalPage'] = $totalPage;
	
	
	$dsql->SetQuery("select aid,title,userid,uname,mid,money,addtime,user_face,user_dj from `#@__member_dows` WHERE aid=$aid order by id desc limit $startPage,$pageSize");
	$dsql->Execute('me');
	
	while ($row = $dsql->GetArray('me')) {
			if($row['user_face'] == ""){
			$row['user_face'] = "$cfg_cmsurl/images/noface.gif";
			}
			 $arr['list'][] = array(
			'id' => $row['id'],
			'title' => $row['title'],
			'userid' => $row['userid'],
			'uname' => $row['uname'],
			'mid' => $row['mid'],
			'money' => $row['money'],
			'dtime' => date('Y-m-d H:i',$row['addtime']),
			'face' => $row['user_face'],
			'cmsurl' => $cfg_cmsurl,
			'user_dj' => $row['user_dj'],
		 );
	}
	
	$json = new Services_JSON(SERVICES_JSON_SUPPRESS_ERRORS);
	echo json_encode($arr);
}else{
	ShowMsg("非法操作！","javascript:window.close();");
	exit(0);
}	
?>