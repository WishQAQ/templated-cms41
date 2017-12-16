<?php
/**
 	修改密码
 */
require_once(dirname(__FILE__)."/config.php");
CheckRank(0,0);
$menutype = 'config';
if(!isset($dopost)) $dopost = '';

$pwd2=(empty($pwd2))? "" : $pwd2;
$row=$dsql->GetOne("SELECT  * FROM `#@__member` WHERE mid='".$cfg_ml->M_ID."'");
$face = $row['face'];
if($dopost=='save')
{
    
	$oldpwd = $pass;
	$userpwd = $pass1;
	$userpwdok = $pass2;
	
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
    
    
    $query1 = "UPDATE `#@__member` SET pwd='$pwd'{$addupquery} where mid='".$cfg_ml->M_ID."' ";
    $dsql->ExecuteNoneQuery($query1);

    //如果是管理员，修改其后台密码
    if($cfg_ml->fields['matt']==10 && $pwd2!="")
    {
        $query2 = "UPDATE `#@__admin` SET pwd='$pwd2' where id='".$cfg_ml->M_ID."' ";
        $dsql->ExecuteNoneQuery($query2);
    }
    // 清除会员缓存
    $cfg_ml->DelCache($cfg_ml->M_ID);
	
	$time = date('Y-m-d H:i:s',time());
	$logo = $cfg_basehost.(empty($cfg_cmspath) ? '/' : $cfg_cmspath)."/skin/1.0/images/logo.png";
	$logo = preg_replace("#http:\/\/#i", '', $logo);
	$logo = 'http://'.preg_replace("#\/\/#", '/', $logo);
    $mailtitle = "您于{$time}在{$cfg_webname}进行了密码修改操作";
    $mailbody .= "<table width=\"800\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#FBF8F1\" style=\"border-radius:5px; overflow:hidden; border-top:4px solid #00c3b6; border-right:1px solid #dbd1ce; border-bottom:1px solid #dbd1ce; border-left:1px solid #dbd1ce;\">
		<tbody>
		  <tr>
			<td><table width=\"800\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" height=\"48\">
				<tbody>
				  <tr>
					<td width=\"74\" height=\"26\" border=\"0\" align=\"center\" valign=\"middle\" style=\"padding-left:20px;\">
					<a href=\"{$cfg_basehost}/\" target=\"_blank\"><img style=\"vertical-align:middle;\" src=\"{$logo}\" height=\"35\" border=\"0\"></a></td>
					<td width=\"703\" height=\"48\" colspan=\"2\" align=\"right\" valign=\"middle\" style=\"color:#ffffff; padding-right:20px;\">
					<a href=\"{$cfg_basehost}/\" target=\"_blank\" style=\"color:#666;text-decoration:none;font-size:12px;margin-left:30px;\">首页</a>
					<a href=\"{$cfg_basehost}/{$cfg_phpurl}/list.php?tid=7\" target=\"_blank\" style=\"color:#666;text-decoration:none;font-size:12px;margin-left:30px;\">网站模板</a>
					<a href=\"{$cfg_basehost}/moban/\" target=\"_blank\" style=\"color:#666;text-decoration:none;font-size:12px;margin-left:30px;\">CMS插件</a>
					<a href=\"{$cfg_basehost}/plug-in/\" target=\"_blank\" style=\"color:#666;text-decoration:none;font-size:12px;margin-left:30px;\">官方活动</a>
					<a href=\"{$cfg_basehost}/self-help/\" target=\"_blank\" style=\"color:#666;text-decoration:none;font-size:12px;margin-left:30px;\">自助服务</a>
					</td>
				  </tr>
				</tbody>
			  </table></td>
		  </tr>
		  <tr>
	<td><div style=\"padding:10px 20px;font-size:14px;color:#333333;border-top:1px solid #dbd1ce;\"><p>{$cfg_ml->fields['uname']} 您好。</p>
<p>您于{$time}在{$cfg_webname}进行了密码修改操作，请确认是否是本人操作，如若不是，请联系{$cfg_webname}管理员</p>
<p style=\"padding:10px 0;margin-top:30px;margin-bottom:0;color:#a8979a;font-size:12px;border-top:1px dashed #dbd1ce;\">此为系统邮件，请勿回复！</p></div></td></tr>
    </tbody>
  </table>";
          
    $headers = "From: ".$cfg_adminemail."\r\nReply-To: ".$cfg_adminemail;
    if($cfg_sendmail_bysmtp == 'Y' && !empty($cfg_smtp_server))
    {
        $mailtype = 'HTML';
        require_once(DEDEINC.'/mail.class.php');
        $smtp = new smtp($cfg_smtp_server,$cfg_smtp_port,true,$cfg_smtp_usermail,$cfg_smtp_password);
        $smtp->debug = false;
        $smtp->sendmail($cfg_ml->fields['email'],$cfg_webname ,$cfg_smtp_usermail, $mailtitle, $mailbody, $mailtype);
    }
    else
    {
        @mail($cfg_ml->fields['email'], $mailtitle, $mailbody, $headers);
    }
    ShowMsg('密码修改成功！','-1');
    exit();
}
include_once(DEDEINC.'/arc.memberlistview.class.php');
$dlist = new MemberListview();
$dlist->SetTemplate(DEDEMEMBER."/templets/edit_pwd.htm");
$dlist->Display();
exit();