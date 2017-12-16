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

$row = $dsql->GetOne("SELECT COUNT(aid) FROM `#@__member_stow` WHERE mid='$mid'");
$datatj = $row['COUNT(aid)'];
$typemsg = '';

if($dopost=='')
{
    //$sql = "SELECT * FROM `#@__member_stow` WHERE mid='".$cfg_ml->M_ID."' ORDER BY aid DESC";
	$sql = "select member.*,arc.*,tp.typename,ch.typename as channelname
        from `#@__member_stow` member
        left join `#@__archives` arc on arc.id=member.aid
		left join `#@__arctype` tp on tp.id=arc.typeid
        left join `#@__channeltype` ch on ch.id=arc.channel
        WHERE member.mid='".$cfg_ml->M_ID."' order by member.addtime desc ";
    $dlist = new DataListCP();
    $dlist->pageSize = 20;
    $dlist->SetTemplate(DEDEMEMBER."/templets/my_stows.htm");    
    $dlist->SetSource($sql);
    $dlist->Display(); 
}
else if($dopost=='del')
{
	$backurl = $cfg_cmsurl."/i/?url=my_work&type=stow";
    $ids = preg_replace("#[^0-9,]#", "", $ids);
    $query = "DELETE FROM `#@__member_stow` WHERE id IN($ids) AND mid='{$cfg_ml->M_ID}'";
    $dsql->ExecuteNoneQuery($query);
	$stows = $dsql->ExecuteNoneQuery2("UPDATE `#@__archives` SET stows=stows-1 WHERE id='$aid' ");
    ShowMsg("成功删除指定的收藏记录!","$backurl",0,8);
    exit();
}