<?php
require_once(dirname(__FILE__)."/../include/common.inc.php");


if($dopost=="send"){

	//随机生成6个数字
	class RandChar
	{
		 function getRandChar($length)
		 {
			  $str = null;
			  $strPol = "0123456789abcdefghrjklmnopqrstuvwxyzABCDEFGHRJKLMNOPQRSTUVWXYZ";
			  $max = strlen($strPol)-1;
			  for($i=0;$i<$length;$i++)
			  {
				$str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
			  }
			  return $str;
		 }
	}
	$randCharObj = new RandChar();
	$oldyzm = $randCharObj->getRandChar(4);
	
	if(!empty($uemail)){
		$logo = $cfg_basehost.(empty($cfg_cmspath) ? '/' : $cfg_cmspath)."/skin/1.0/images/logo.png";
		$logo = preg_replace("#http:\/\/#i", '', $logo);
		$logo = 'http://'.preg_replace("#\/\/#", '/', $logo);
		$mailtitle = "注册验证码[{$oldyzm}] - {$cfg_webname}";
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
							<a href=\"{$cfg_basehost}/moban/\" target=\"_blank\" style=\"color:#666;text-decoration:none;font-size:12px;margin-left:30px;\">网站模板</a>
							<a href=\"{$cfg_basehost}/plug-in/\" target=\"_blank\" style=\"color:#666;text-decoration:none;font-size:12px;margin-left:30px;\">CMS插件</a>
							<a href=\"{$cfg_basehost}/activity/\" target=\"_blank\" style=\"color:#666;text-decoration:none;font-size:12px;margin-left:30px;\">官方活动</a>
							<a href=\"{$cfg_basehost}/self-help/\" target=\"_blank\" style=\"color:#666;text-decoration:none;font-size:12px;margin-left:30px;\">自助服务</a>
							</td>
						  </tr>
						</tbody>
					  </table></td>
				  </tr>
				  <tr>
			<td><div style=\"padding:10px 20px;font-size:14px;color:#333333;border-top:1px solid #dbd1ce;\"><p>亲爱的 {$uname} 您好。</p>
		<p>您正{$cfg_webname}注册账号，您的验证码为： </p>
		<p style=\"color:red;font-size:20px;\"><u>{$oldyzm}</u></p>
		<p>验证码5分钟内有效，请牢记。</p>
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
			$smtp->sendmail($uemail,$cfg_webname ,$cfg_smtp_usermail, $mailtitle, $mailbody, $mailtype);
		}
		else
		{
			@mail($cfg_ml->fields['email'], $mailtitle, $mailbody, $headers);
		}
		
		$backyzm = md5($oldyzm);
		$arr = array("son"=>"ok","msg"=>"邮件发送成功，请注意查收","oldyzm"=>$backyzm);
		echo json_encode($arr);
		exit;
	}
	else
	{
		$arr = array("son"=>"err","msg"=>"邮件发送失败");
		echo json_encode($arr);
		exit;
	}
}else{
	exit("error");
}
?>