<?php
/**
 	用户中心首页
 */
require_once(dirname(__FILE__)."/config.php");

$uid=empty($uid)? "" : RemoveXSS($uid); 
if(empty($action)) $action = '';
if(empty($aid)) $aid = '';

$menutype = 'tab-index';

if($row['spacesta'] == '-10'){
	require_once(dirname(__FILE__)."/no_email.php");
	exit();
}
if($type == 'editinfo'){
	require_once(DEDEMEMBER.'/edit_baseinfo.php');
	exit();
}else if($type == 'editface'){
	require_once(DEDEMEMBER.'/edit_face.php');
	exit();
}else if($type == 'editpwd'){
	require_once(DEDEMEMBER.'/edit_pwd.php');
	exit();
}else if($type == 'resetpassword'){
	require_once(DEDEMEMBER.'/resetpassword.php');
	exit();
}else if($type == 'buy'){
	require_once(DEDEMEMBER.'/buy.php');
	exit();
}
//会员后台
if($uid==''){
    $iscontrol = 'yes';
    if(!$cfg_ml->IsLogin())
    {
       //ShowMsg("抱歉，您还未登陆，请先登陆后才能访问用户中心。","{$cfg_cmsurl}/i/?type=login",0,8);
	   $lokurl = $cfg_basehost.$cfg_cmsurl;
	   header("Location: $lokurl");
    }
    else
    {
        $minfos = $dsql->GetOne("SELECT * FROM `#@__member_tj` WHERE mid='".$cfg_ml->M_ID."'; ");
        $minfos['totaluse'] = $cfg_ml->GetUserSpace();
        $minfos['totaluse'] = number_format($minfos['totaluse']/1024/1024,2);
        if($cfg_mb_max > 0) {
            $ddsize = ceil( ($minfos['totaluse']/$cfg_mb_max) * 100 );
        }
        else {
            $ddsize = 0;
        }

        require_once(DEDEINC.'/channelunit.func.php');

        /** 我的收藏 **/
        $favorites = array();
        $dsql->Execute('fl',"SELECT * FROM `#@__member_stow` WHERE mid='{$cfg_ml->M_ID}'  LIMIT 5");
        while($arr = $dsql->GetArray('fl'))
        {
            $favorites[] = $arr;
        }
        
        /** 有没新短信 **/
        $pms = $dsql->GetOne("SELECT COUNT(*) AS nums FROM #@__member_pms WHERE toid='{$cfg_ml->M_ID}' AND `hasview`=0 AND folder = 'inbox'"); 
		
		/** 有没下载记录 **/
        $data_dows = $dsql->GetOne("SELECT COUNT(id) FROM #@__member_dows WHERE mid='{$cfg_ml->M_ID}'");    
        $data_dows = $data_dows['COUNT(id)'];
		
		/** 有没评论记录 **/
        $data_feedback = $dsql->GetOne("SELECT COUNT(id) FROM #@__feedback WHERE mid='{$cfg_ml->M_ID}'");    
        $data_feedback = $data_feedback['COUNT(id)'];
		
		/** 有没工单记录 **/
        $data_gongdan = $dsql->GetOne("SELECT COUNT(id) FROM #@__member_gongdan WHERE mid='{$cfg_ml->M_ID}'");    
        $data_gongdan = $data_gongdan['COUNT(id)'];
		
        /** 查询会员状态 **/
        $moodmsg = $dsql->GetOne("SELECT * FROM #@__member_msg WHERE mid='{$cfg_ml->M_ID}' ORDER BY dtime desc");    

        $dpl = new DedeTemplate();
        $tpl = dirname(__FILE__)."/templets/index.htm";
        $dpl->LoadTemplate($tpl);
        $dpl->display();
    }
exit();
}
else
{
	require_once(DEDEMEMBER.'/index_space.php');
	exit();
}