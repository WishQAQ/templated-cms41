<?php 
/**
 * 工单操作
 * 
 */
require_once(dirname(__FILE__)."/../qm_user/config.php");
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

if($dopost=='sub_ok')
{
	$dsql->ExecuteNoneQuery("insert into #@__member_gongdan (mid,aid,title,addtime,type,money,description,sta) values ('".$cfg_ml->M_ID."','$aid','$title','".time()."','$type','0','$description',0)");
    $dsql->ExecuteNoneQuery($query);
    ShowMsg("工单提交成功，等到客服处理!","/i/",0,8);
    exit();
}
else
{
	ShowMsg("错误提交，请返回!","$cfg_basehost",0,8);
    exit();
}