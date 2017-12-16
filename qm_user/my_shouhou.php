<?php 
/**
 * 操作
 * 
 * @version        $Id: search.php 1 8:38 2010年7月9日Z tianya $
 * @package        DedeCMS.Member
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
require_once(DEDEINC."/datalistcp.class.php");
CheckRank(0,0);
$menutype = 'tab-shouhou';

setcookie("ENV_GOBACK_URL",GetCurUrl(),time()+3600,"/");
if(!isset($dopost)) $dopost = '';

/**
 *  获取状态
 *
 * @param     string  $sta  状态ID
 * @return    string
 */

function GetSta($sta){
    if($sta==0) return '<font color=red>未处理</font>';
    else if($sta==1) return '<font color=#00c3b6>处理中...</font>';
    else return '<font color=green>已完成</font>';
}


if($dopost=='')
{
    $sql = "SELECT * FROM `#@__member_dows` WHERE mid='".$cfg_ml->M_ID."' ORDER BY aid DESC";
	$row1 = $dsql->GetOne("SELECT COUNT(id) FROM `#@__member_gongdan` WHERE sta='1' AND mid='".$cfg_ml->M_ID."'");
	$gdsj = $row1['COUNT(id)'];
	$row2 = $dsql->GetOne("SELECT COUNT(id) FROM `#@__member_gongdan` WHERE mid='".$cfg_ml->M_ID."'");
	$gdsj2 = $row2['COUNT(id)'];
    $dlist = new DataListCP();
    $dlist->pageSize = 20;
    $dlist->SetTemplate(DEDEMEMBER."/templets/my_shouhou.htm");    
    $dlist->SetSource($sql);
    $dlist->Display(); 
	exit();
}
else if($dopost=='sub')
{
	$ids = preg_replace("#[^0-9,]#", "", $ids);
	$row3 = $dsql->GetOne("SELECT sta FROM `#@__member_gongdan` WHERE aid='$ids'");
	if($row3['sta']!='')
	{
		ShowMsg("此工单正在处理中，请不要重复申请!","-1");
		exit();
	}
	$row = $dsql->GetOne("SELECT * FROM `#@__member_dows` WHERE aid='$ids'");
	$dlist = new DataListCP();
	$dlist->pageSize = 20;
    $dlist->SetTemplate(DEDEMEMBER."/templets/my_shouhou-sub.htm");    
    $dlist->SetSource($sql);
    $dlist->Display(); 
    exit();
}
else if($dopost=='list')
{
    $sql = "SELECT * FROM `#@__member_gongdan` WHERE mid='".$cfg_ml->M_ID."' ORDER BY id DESC";
    $dlist = new DataListCP();
    $dlist->pageSize = 20;
    $dlist->SetTemplate(DEDEMEMBER."/templets/my_shouhou-list.htm");    
    $dlist->SetSource($sql);
    $dlist->Display(); 
	exit();
}
else if($dopost=='del')
{
    $row = $dsql->GetOne("SELECT sta FROM `#@__member_gongdan` WHERE id='$ids'");
	if($row['sta']=='1')
	{
		ShowMsg("对不起，正在处理中的工单不能删除!","-1");
		exit();
	}
	$query = "DELETE FROM `#@__member_gongdan` WHERE id IN($ids) AND mid='{$cfg_ml->M_ID}'";
    $dsql->ExecuteNoneQuery($query);
    ShowMsg("成功删除指定的工单记录!","/i/?url=my_work&type=my_shouhou&dopost=list",0,8);
	exit();
}