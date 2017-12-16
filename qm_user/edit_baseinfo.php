<?php
/**
 	修改详细资料
 */
require_once(dirname(__FILE__)."/config.php");
CheckRank(0,0);
$menutype = 'tab-profile';
if(!isset($dopost)) $dopost = '';

$pwd2=(empty($pwd2))? "" : $pwd2;
$row=$dsql->GetOne("SELECT  * FROM `#@__member` WHERE mid='".$cfg_ml->M_ID."'");
$face = $row['face'];
$mybg = $row['user_bg'];
if($mybg == '')
{
	$mybg = "<span>默认背景</span>";
}
else
{
	$mybg = "<a href='".$mybg."' target='_blank'><img src='".$mybg."' style='width:200px;height:36px;' /></a>";
}

if($dopost=='save')
{
    $svali = GetCkVdValue();

    if(strtolower($vdcode) != $svali || $svali=='')
    {
        ReSETVdValue();
        ShowMsg('验证码错误！','-1');
        exit();
    }
    if(!is_array($row) || $row['pwd'] != md5($oldpwd))
    {
        ShowMsg('你输入的旧密码错误或没填写，不允许修改资料！','-1');
        exit();
    }
    if($userpwd != $userpwdok)
    {
        ShowMsg('你两次输入的新密码不一致！','-1');
        exit();
    }
    if($userpwd=='')
    {
        $pwd = $row['pwd'];
    }
    else
    {
        $pwd = md5($userpwd);
        $pwd2 = substr(md5($userpwd),5,20);
    }
    $addupquery = '';
    
    #api{{
    if(defined('UC_API') && @include_once DEDEROOT.'/uc_client/client.php')
    {
        $emailnew = $email != $row['email'] ? $email : '';
        $ucresult = uc_user_edit($cfg_ml->M_LoginID, $oldpwd, $userpwd, $emailnew);        
    }
    #/aip}}
    
    //修改安全问题或Email
    if($email != $row['email'] || ($newsafequestion != 0 && $newsafeanswer != ''))
    {
        if($row['safequestion']!=0 && ($row['safequestion'] != $safequestion || $row['safeanswer'] != $safeanswer))
        {
            ShowMsg('你的旧安全问题及答案不正确，不能修改Email或安全问题！','-1');
            exit();
        }

        //修改Email
		if($email != $row['email'])
        {
            if(!CheckEmail($email))
            {
                ShowMsg('Email格式不正确！','-1');
                exit();
            }
            else
            {
                $addupquery .= ",email='$email'";
            }
        }

        //修改安全问题
        if($newsafequestion != 0 && $newsafeanswer != '')
        {
            if(strlen($newsafeanswer) > 30)
            {
                ShowMsg('你的新安全问题的答案太长了，请保持在30字节以内！','-1');
                exit();
            }
            else
            {
			    $newsafequestion = HtmlReplace($newsafequestion,1);
			    $newsafeanswer = HtmlReplace($newsafeanswer,1);
                $addupquery .= ",safequestion='$newsafequestion',safeanswer='$newsafeanswer'";
            }
        }
    }

    //修改uname
    if($uname != $row['uname'])
    {
        $rs = CheckUserID($uname,'昵称或公司名称',FALSE);
        if($rs!='ok')
        {
            ShowMsg($rs,'-1');
            exit();
        }
        $addupquery .= ",uname='$uname'";
    }
    
    //性别
    if( !in_array($sex, array('男','女','保密')) )
    {
        ShowMsg('请选择正常的性别！','-1');
        exit();    
    }
    
    $query1 = "UPDATE `#@__member` SET pwd='$pwd',sex='$sex'{$addupquery} where mid='".$cfg_ml->M_ID."' ";
    $dsql->ExecuteNoneQuery($query1);

    //如果是管理员，修改其后台密码
    if($cfg_ml->fields['matt']==10 && $pwd2!="")
    {
        $query2 = "UPDATE `#@__admin` SET pwd='$pwd2' where id='".$cfg_ml->M_ID."' ";
        $dsql->ExecuteNoneQuery($query2);
    }
    // 清除会员缓存
    $cfg_ml->DelCache($cfg_ml->M_ID);
    ShowMsg('成功更新你的基本资料！','edit_baseinfo.php',0,5000);
    exit();
}else if($dopost=='newsave'){

	//修改邮箱
	$row2 = $dsql->GetOne("SELECT email FROM `#@__member` WHERE email LIKE '$email' ");
    if(is_array($row2))
    {
       ShowMsg('你使用的邮箱已存在，请使其它邮箱！', '-1');
       exit();
    }
	else if($email != $row['email'])
	{
       if(!CheckEmail($email))
		{
            ShowMsg('邮箱格式不正确！','-1');
            exit();
        }			
    }
	else if($user_bg != '')
	{
	$maxlength = $cfg_max_user_bg * 1024;
    $userdir = $cfg_user_dir.'/'.$cfg_ml->M_ID;
    if(!preg_match("#^".$userdir."#", $olduser_bg))
    {
        $olduser_bg = '';
    }
    if(is_uploaded_file($user_bg))
    {
        if(@filesize($_FILES['user_bg']['tmp_name']) > $maxlength)
        {
            ShowMsg("你上传的背景图像超过了系统限制大小：$cfg_max_user_bg K！", '-1');
            exit();
        }
        //删除旧图片（防止文件扩展名不同，如：原来的是gif，后来的是jpg）
        if(preg_match("#\.(jpg|gif|png)$#i", $olduser_bg) && file_exists($cfg_basedir.$olduser_bg))
        {
            @unlink($cfg_basedir.$olduser_bg);
        }
        //上传新工图片
        $user_bg = MemberUploads('user_bg', $olduser_bg, $cfg_ml->M_ID, 'image', 'mybg');
    }
    else
    {
        $user_bg = $olduser_bg;
    }
	}
	$dsql->ExecuteNoneQuery2("update #@__member set uname='$uname',email='$email',user_tel='$user_tel',sin_web='$sin_web',ten_web='$ten_web',user_qq='$user_qq',user_bg='$user_bg',qianming='$qianming',zlbm='$zlbm' where mid='".$cfg_ml->M_ID."'");	
    //如果是管理员，修改其后台密码
    if($cfg_ml->fields['matt']==10 && $pwd2!="")
    {
        $query2 = "UPDATE `#@__admin` SET pwd='$pwd2' where id='".$cfg_ml->M_ID."' ";
        $dsql->ExecuteNoneQuery($query2);
    }
    // 清除会员缓存
    $cfg_ml->DelCache($cfg_ml->M_ID);
    ShowMsg('成功更新你的基本资料！','-1');
    exit();
}
include_once(DEDEINC.'/arc.memberlistview.class.php');
$dlist = new MemberListview();
$dlist->SetTemplate(DEDEMEMBER."/templets/edit_baseinfo.htm");
$dlist->Display();