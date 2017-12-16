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

$row = $dsql->GetOne("SELECT COUNT(aid) FROM `#@__feedback` WHERE mid='$mid'");
$datatj = $row['COUNT(aid)'];

if($dopost=='')
{
    $sql = "SELECT * FROM `#@__feedback` WHERE mid='".$cfg_ml->M_ID."' ORDER BY aid DESC";
    $dlist = new DataListCP();
    $dlist->pageSize = 20;
    $dlist->SetTemplate(DEDEMEMBER."/templets/my_comment.htm");    
    $dlist->SetSource($sql);
    $dlist->Display(); 
}
else if($dopost=='del')
{
	$backurl = $cfg_cmsurl."/i/?url=my_work&type=comment";
    $ids = preg_replace("#[^0-9,]#", "", $ids);
    $query = "DELETE FROM `#@__feedback` WHERE id IN($ids) AND mid='{$cfg_ml->M_ID}'";
    $dsql->ExecuteNoneQuery($query);
	$pinglun = $dsql->ExecuteNoneQuery2("UPDATE `#@__archives` SET pinglun=pinglun-1 WHERE id='$aid' ");
    ShowMsg("成功删除指定的评论记录!","$backurl",0,8);
    exit();
}