<?php
/**
	邮件发送
**/
require_once(dirname(__FILE__)."/../include/common.inc.php");
require_once(DEDEINC.'/memberlogin.class.php');
$cfg_ml = new MemberLogin();

$mailtitle = "$email_title";
$mailbody .= "$email_body";
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