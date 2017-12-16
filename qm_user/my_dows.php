<?php 
/**
 * 我的操作记录
 * 
 */
require_once(dirname(__FILE__)."/config.php");
require_once(DEDEINC."/datalistcp.class.php");
CheckRank(0,0);
$menutype = 'mydede';
$menutype_son = 'op';
setcookie("ENV_GOBACK_URL",GetCurUrl(),time()+3600,"/");
if(!isset($dopost)) $dopost = '';

/**
 *  获取状态
 *
 * @param     string  $sta  状态ID
 * @return    string
 */

$row = $dsql->GetOne("SELECT COUNT(aid) FROM `#@__member_dows` WHERE mid='$mid'");
$datatj = $row['COUNT(aid)'];
$typemsg = '<p class="tip">注：免费及免登录下载类型的模板不添加到下载记录中</p>';

if($dopost=='')
{
    $sql = "SELECT * FROM `#@__member_dows` WHERE mid='".$cfg_ml->M_ID."' ORDER BY aid DESC";
    $dlist = new DataListCP();
    $dlist->pageSize = 20;
    $dlist->SetTemplate(DEDEMEMBER."/templets/my_dows.htm");    
    $dlist->SetSource($sql);
    $dlist->Display(); 
}
else if($dopost=='del')
{
	$backurl = $cfg_cmsurl."/i/?url=my_work&type=dows";
    $ids = preg_replace("#[^0-9,]#", "", $ids);
    $query = "DELETE FROM `#@__member_dows` WHERE id IN($ids) AND mid='{$cfg_ml->M_ID}'";
    $dsql->ExecuteNoneQuery($query);
	$dows = $dsql->ExecuteNoneQuery2("UPDATE `#@__archives` SET dows=dows-1 WHERE id='$aid' ");
    ShowMsg("成功删除指定的下载记录!","$backurl",0,8);
    exit();
}