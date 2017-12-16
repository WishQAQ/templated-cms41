<?php
/**
 	index_do.php 登录 注册 退出等核心配置
*/
require_once(dirname(__FILE__)."/config.php");
if(empty($dopost)) $dopost = '';
if(empty($fmdo)) $fmdo = '';

/*********************
function check_email()
*******************/

$gourl = str_replace("*","&",$gourl);

if($fmdo=='sendMail')
{
    if(!CheckEmail($cfg_ml->fields['email']) )
    {
        ShowMsg('你的邮箱格式有错误！', '-1');
        exit();
    }
    if($cfg_ml->fields['spacesta'] != -10)
    {
        ShowMsg('你的帐号不在邮件验证状态，本操作无效！', '-1');
        exit();
    }
    $userhash = md5($cfg_cookie_encode.'-'.$cfg_ml->fields['mid']);
	$url = $cfg_basehost.(empty($cfg_cmspath) ? '/' : $cfg_cmspath).$cfg_cmsurl."/i/?type=do&fmdo=checkMail&mid={$cfg_ml->fields['mid']}&userhash={$userhash}&do=1";
	$url = preg_replace("#http:\/\/#i", '', $url);
	$url = 'http://'.preg_replace("#\/\/#i", '/', $url);
	$logo = $cfg_basehost.(empty($cfg_cmspath) ? '/' : $cfg_cmspath)."/skin/1.0/images/logo.png";
	$logo = preg_replace("#http:\/\/#i", '', $logo);
	$logo = 'http://'.preg_replace("#\/\/#", '/', $logo);
    $mailtitle = "欢迎加入{$cfg_webname}，请确认验证邮箱";
    $mailbody .= "<table width=\"800\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#FBF8F1\" style=\"border-radius:5px; overflow:hidden; border-top:4px solid #00c3b6; border-right:1px solid #dbd1ce; border-bottom:1px solid #dbd1ce; border-left:1px solid #dbd1ce;\">
		<tbody>
		  <tr>
	<td><div style=\"padding:10px 20px;font-size:14px;color:#333333;border-top:1px solid #dbd1ce;\"><p>亲爱的 {$cfg_ml->fields['uname']} 您好。</p>
<p>请点击以下链接验证你的邮箱地址，验证后就可以使用{$cfg_webname}的所有功能啦! </p>
<p><a href=\"{$url}\" target=\"_blank\">{$url}</a></p>
<p>如果以上链接无法访问，请将该网址复制并粘贴至新的浏览器窗口中。</p>
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
    ShowMsg("成功发送邮件，请稍后登录你的邮箱进行接收！","$cfg_basehost",0,8);
    exit();
}else if($fmdo=='addemail'){
		$query1 = "UPDATE `#@__member` SET email='".$user_email."' where mid='".$cfg_ml->fields['mid']."' ";
		$dsql->ExecuteNoneQuery($query1);
		if($query1){
			ShowMsg("更换成功", "/i/?type=do&?fmdo=websendMail&user_email=$user_email",0,8);
		}else{
			ShowMsg("更换失败", "-1");
		}
		exit();
}else if($fmdo=='zhpwd'){
    if(!CheckEmail($tjemail) )
    {
        ShowMsg('你的邮箱格式有错误！', '-1');
        exit();
    }
	$row = $dsql->GetOne("SELECT * FROM `#@__member` WHERE email='{$tjemail}' ");
	$email = $row['email'];
	$userid = $row['userid'];
	$usermid = $row['mid'];
	$token = md5($row['mid'].$row['userid'].$row['pwd']);
	if($email == '' || $tjemail != "$email"){
		ShowMsg('此邮箱没有注册！','-1');
		exit();
	}
	$url = $cfg_basehost.(empty($cfg_cmspath) ? '/' : $cfg_cmspath).$cfg_cmsurl."/i/?type=do&fmdo=xgpwd&email=".$email."&token=".$token;
	$url = preg_replace("#http:\/\/#i", '', $url);
	$url = 'http://'.preg_replace("#\/\/#i", '/', $url);
	$logo = $cfg_basehost.(empty($cfg_cmspath) ? '/' : $cfg_cmspath)."/skin/1.0/images/logo.png";
	$logo = preg_replace("#http:\/\/#i", '', $logo);
	$logo = 'http://'.preg_replace("#\/\/#", '/', $logo);
    $mailtitle = "{$userid}您正在{$cfg_webname}进行修改密码操作";
    $mailbody .= "<table width=\"800\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#FBF8F1\" style=\"border-radius:5px; overflow:hidden; border-top:4px solid #00c3b6; border-right:1px solid #dbd1ce; border-bottom:1px solid #dbd1ce; border-left:1px solid #dbd1ce;\">
		<tbody>
		  <tr>
	<td><div style=\"padding:10px 20px;font-size:14px;color:#333333;border-top:1px solid #dbd1ce;\">
<p>有人要求重设如下帐号的密码：</p>
<p><a href=\"{$cfg_basehost}/\" target=\"_blank\">{$cfg_basehost}/</a></p>
<p>用户名：{$userid}</p>
<p>若这不是您本人要求的，请忽略本邮件，一切如常。</p>
<p>要重置您的密码，请打开下面的链接：</p>
<p><a href=\"{$url}\" target=\"_blank\">{$url}</a></p>
<p>如果以上链接无法访问，请将该网址复制并粘贴至新的浏览器窗口中。</p>
<p style=\"padding:10px 0;margin-top:30px;margin-bottom:0;color:#a8979a;font-size:12px;border-top:1px dashed #dbd1ce;\">此为系统邮件，请勿回复！</p></div></td></tr>
    </tbody>
  </table>";
          
    $headers = "From: ".$cfg_adminemail."\r\nReply-To: ".$cfg_adminemail;
    if($cfg_sendmail_bysmtp == 'Y' && !empty($cfg_smtp_server)){
        $mailtype = 'HTML';
        require_once(DEDEINC.'/mail.class.php');
        $smtp = new smtp($cfg_smtp_server,$cfg_smtp_port,true,$cfg_smtp_usermail,$cfg_smtp_password);
        $smtp->debug = false;
        $smtp->sendmail($email,$cfg_webname ,$cfg_smtp_usermail, $mailtitle, $mailbody, $mailtype);
    }else{
        @mail($email, $mailtitle, $mailbody, $headers);
    }
	ShowMsg("成功发送邮件，请按照邮件提示操作！","$cfg_basehost",0,8);
    exit();
}else if($fmdo=='addemail'){
		$query1 = "UPDATE `#@__member` SET email='".$user_email."' where mid='".$cfg_ml->fields['mid']."' ";
		$dsql->ExecuteNoneQuery($query1);
		if($query1){
			ShowMsg("更换成功", "/i/?type=do&?fmdo=websendMail&user_email=$user_email",0,8);
		}else{
			ShowMsg("更换失败", "-1");
		}
		exit();
}else if($fmdo=='websendMail'){
    $e_m = $dsql->GetOne("SELECT mid FROM `#@__member` WHERE email='{$user_email}' ");
	if(!CheckEmail($user_email) ){
        ShowMsg('你的邮箱格式有错误！', '-1');
        exit();
    }else if($cfg_ml->fields['spacesta'] != -10){
        ShowMsg('你的帐号不在邮件验证状态，本操作无效！','-1');
        exit();
    }else if(!$e_m){
		$dsql->ExecuteNoneQuery("UPDATE `#@__member` SET email='$user_email' WHERE mid='".$cfg_ml->fields['mid']."' ");
		//ShowMsg("此邮箱不存在","-1",0,8);
		//exit();
	}
    $userhash = md5($cfg_cookie_encode.'-'.$cfg_ml->fields['mid']);
	$url = $cfg_basehost.(empty($cfg_cmspath) ? '/' : $cfg_cmspath).$cfg_cmsurl."/i/?type=do&fmdo=checkMail&mid={$cfg_ml->fields['mid']}&userhash={$userhash}&do=1&email=$user_email";
	$url = preg_replace("#http:\/\/#i", '', $url);
	$url = 'http://'.preg_replace("#\/\/#i", '/', $url);
	$logo = $cfg_basehost.(empty($cfg_cmspath) ? '/' : $cfg_cmspath)."/skin/1.0/images/logo.png";
	$logo = preg_replace("#http:\/\/#i", '', $logo);
	$logo = 'http://'.preg_replace("#\/\/#", '/', $logo);
    $mailtitle = "请确认验证邮箱 - $cfg_webname";
    $mailbody .= "<table width=\"800\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#FBF8F1\" style=\"border-radius:5px; overflow:hidden; border-top:4px solid #00c3b6; border-right:1px solid #dbd1ce; border-bottom:1px solid #dbd1ce; border-left:1px solid #dbd1ce;\">
		<tbody>
		  <tr>
	<td><div style=\"padding:10px 20px;font-size:14px;color:#333333;border-top:1px solid #dbd1ce;\"><p>亲爱的 {$cfg_ml->fields['uname']} 您好。</p>
<p>请点击以下链接确认绑定你的邮箱地址，绑定并验证后就可以使用{$cfg_webname}的所有功能啦! </p>
<p><a href=\"{$url}\" target=\"_blank\">{$url}</a></p>
<p>如果以上链接无法访问，请将该网址复制并粘贴至新的浏览器窗口中。</p>
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
        $smtp->sendmail($user_email,$cfg_webname ,$cfg_smtp_usermail, $mailtitle, $mailbody, $mailtype);
    }
    else
    {
        @mail($user_email, $mailtitle, $mailbody, $headers);
    }
    ShowMsg("成功发送邮件，请稍后登录你的邮箱进行接收！","$cfg_basehost",0,8);
    exit();
}
else if($fmdo=='checkMail')
{
    $mid = intval($mid);
    if(empty($mid))
    {
        ShowMsg('你的效验串不合法！', '-1');
        exit();
    }
    $row = $dsql->GetOne("SELECT * FROM `#@__member` WHERE mid='$mid' ");
    $needUserhash = md5($cfg_cookie_encode.'-'.$mid);
    if($needUserhash != $userhash)
    {
        ShowMsg('你的效验串不合法！', '-1');
        exit();
    }
	else if($row['spacesta'] != -10)
    {
        ShowMsg('你的帐号不在邮件验证状态，本操作无效！', '/',0,8);
        exit();
    }
	else
	{
		$dsql->ExecuteNoneQuery("UPDATE `#@__member` SET spacesta='2', email='$email' WHERE mid='$mid' ");
		// 清除会员缓存
		$cfg_ml->DelCache($mid);
		$cfg_ml = new MemberLogin(7*3600);
		$rs = $cfg_ml->CheckUser($userid, $userpwd);
		ShowMsg('操作成功，请重新登录系统！', '/',0,8);
		exit();
	}
}
//修改密码
else if($fmdo=='xgpwd')
{
    $row = $dsql->GetOne("SELECT * FROM `#@__member` WHERE email='{$email}' ");
	$username = $row['userid'];
	$mt = md5($row['mid'].$row['userid'].$row['pwd']);
    if($mt == $token)
	{
		require_once(dirname(__FILE__)."/templets/resetpassword2.htm");
	}
	else
	{
		ShowMsg('验证不通过，本操作无效！', '/',0,8);
		exit();
	}
    
}
else if($fmdo == 'xgpwd2')
{
	if($xgpwd != '')
	{
		$newpwd = md5($xgpwd);
		//$row = $dsql->ExecuteNoneQuery("UPDATE `#@__member` SET pwd=$newpwd WHERE mid='{$mid}' ");
		$query1 = "UPDATE `#@__member` SET pwd='$newpwd' where email='{$email}' ";
		$dsql->ExecuteNoneQuery($query1);
		if($query1)
		{
		ShowMsg('操作成功,请重新登录','/',0,8);
		exit();
		}
	}else{
		ShowMsg('密码不能为空','-1');
		exit();
	}
	
}
/*********************
function Case_user()
*******************/
else if($fmdo=='user')
{

    //检查用户名是否存在
    if($dopost=="checkuser")
    {
        AjaxHead();
        $msg = '';
        $uid = trim($uid);
        if($cktype==0)
        {
            $msgtitle='用户笔名';
        }
        else
        {
            #api{{
            if(defined('UC_API') && @include_once DEDEROOT.'/uc_client/client.php')
            {
                $ucresult = uc_user_checkname($uid);
                if($ucresult > 0)
                {
                    echo "<font color='#4E7504'><b>√用户名可用</b></font>";
                }
                elseif($ucresult == -1)
                {
                    echo "<font color='red'><b>×用户名不合法</b></font>";
                }
                elseif($ucresult == -2)
                {
                    echo "<font color='red'><b>×包含要允许注册的词语</b></font>";
                }
                elseif($ucresult == -3)
                {
                    echo "<font color='red'><b>×用户名已经存在</b></font>";
                }
                exit();
            }
            #/aip}}            
            $msgtitle='用户名';
        }
        if($cktype!=0 || $cfg_mb_wnameone=='N') {
            $msg = CheckUserID($uid, $msgtitle);
        }
        else {
            $msg = CheckUserID($uid, $msgtitle, false);
        }
        if($msg=='ok')
        {
            $msg = "<font color='#4E7504'><b>√{$msgtitle}可以使用</b></font>";
        }
        else
        {
            $msg = "<font color='red'><b>×{$msg}</b></font>";
        }
        echo $msg;
        exit();
    }

    //检查email是否存在
    else  if($dopost=="checkmail")
    {
        AjaxHead();
        
        #api{{
        if(defined('UC_API') && @include_once DEDEROOT.'/uc_client/client.php')
        {
            $ucresult = uc_user_checkemail($email);
            if($ucresult > 0) {
                echo "<font color='#4E7504'><b>√可以使用</b></font>";
            } elseif($ucresult == -4) {
                echo "<font color='red'><b>×Email 格式有误！</b></font>";
            } elseif($ucresult == -5) {
                echo "<font color='red'><b>×Email 不允许注册！</b></font>";
            } elseif($ucresult == -6) {
                echo "<font color='red'><b>×该 Email 已经被注册！</b></font>";
            }
            exit();
        }
        #/aip}}    
        
        if($cfg_md_mailtest=='N')
        {
            $msg = "<font color='#4E7504'><b>√可以使用</b></font>";
        }
        else
        {
            if(!CheckEmail($email))
            {
                $msg = "<font color='#4E7504'><b>×Email格式有误</b></font>";
            }
            else
            {
                 $row = $dsql->GetOne("SELECT mid FROM `#@__member` WHERE email LIKE '$email' LIMIT 1");
                 if(!is_array($row)) {
                     $msg = "<font color='#4E7504'><b>√可以使用</b></font>";
                 }
                 else {
                     $msg = "<font color='red'><b>×Email已经被另一个帐号占用！</b></font>";
                 }
            }
        }
        echo $msg;
        exit();
    }

    //引入注册页面
    else if($dopost=="regnew")
    {
        $step = empty($step)? 1 : intval(preg_replace("/[^\d]/",'', $step));
        require_once(dirname(__FILE__)."/reg_new.php");
        exit();
    }
  /***************************
  //积分换金币
  function money2s() {  }
  ***************************/
    else if($dopost=="money2s")
    {
        CheckRank(0,0);
        if($cfg_money_scores==0)
        {
            ShowMsg('系统禁用了积分与金币兑换功能！', '-1');
            exit();
        }
        $money = empty($money) ? "" : abs(intval($money));
        if(empty($money))
        {
            ShowMsg('您没指定要兑换多少金币！', '-1');
            exit();
        }
        
        $needscores = $money * $cfg_money_scores;
        if($cfg_ml->fields['scores'] < $needscores )
        {
            ShowMsg('您积分不足，不能换取这么多的金币！', '-1');
            exit();
        }
        $litmitscores = $cfg_ml->fields['scores'] - $needscores;
        
        //保存记录
        $mtime = time();
        $inquery = "INSERT INTO `#@__member_operation`(`buyid` , `pname` , `product` , `money` , `mtime` , `pid` , `mid` , `sta` ,`oldinfo`)
           VALUES ('ScoresToMoney', '积分换金币操作', 'stc' , '0' , '$mtime' , '0' , '{$cfg_ml->M_ID}' , '0' , '用 {$needscores} 积分兑了换金币：{$money} 个'); ";
        $dsql->ExecuteNoneQuery($inquery);
        //修改积分与金币值
        $dsql->ExecuteNoneQuery("UPDATE `#@__member` SET `scores`=$litmitscores, money= money + $money  WHERE mid='".$cfg_ml->M_ID."' ");
        
        // 清除会员缓存
        $cfg_ml->DelCache($cfg_ml->M_ID);
        ShowMsg('成功兑换指定量的金币！', '-1');
        exit();
    }
	
}

/*********************
function login()
*******************/
else if($fmdo=='user_login')
{
    //用户登录
    if($dopost=="login")
    {
        if(!isset($vdcode))
        {
            $vdcode = '';
        }
        $svali = GetCkVdValue();
        if(preg_match("/2/",$safe_gdopen)){
            if(strtolower($vdcode)!=$svali || $svali=='')
            {
                ResetVdValue();
                ShowMsg('验证码错误！', '-1');
                exit();
            }
            
        }
        if(CheckUserID($userid,'',false)!='ok')
        {
            ResetVdValue();
            ShowMsg("你输入的用户名 {$userid} 不合法！","-1");
            exit();
        }
        if($pwd=='')
        {
            ResetVdValue();
            ShowMsg("密码不能为空！","-1");
            exit();
        }

        //检查帐号
        $rs = $cfg_ml->CheckUser($userid,$pwd);  
        
        #api{{
        if(defined('UC_API') && @include_once DEDEROOT.'/uc_client/client.php')
        {
            //检查帐号
            list($uid, $username, $password, $email) = uc_user_login($userid, $pwd);
            if($uid > 0) {
                $password = md5($password);
                //当UC存在用户,而CMS不存在时,就注册一个    
                if(!$rs) {
                    //会员的默认金币
                    $row = $dsql->GetOne("SELECT `money`,`scores` FROM `#@__arcrank` WHERE `rank`='10' ");
                    $scores = is_array($row) ? $row['scores'] : 0;
                    $money = is_array($row) ? $row['money'] : 0;
                    $logintime = $jointime = time();
                    $loginip = $joinip = GetIP();
                    $res = $dsql->ExecuteNoneQuery("INSERT INTO #@__member SET `mtype`='个人',`userid`='$username',`pwd`='$password',`uname`='$username',`sex`='男' ,`rank`='10',`money`='$money', `email`='$email', `scores`='$scores', `matt`='0', `face`='',`safequestion`='0',`safeanswer`='', `jointime`='$jointime',`joinip`='$joinip',`logintime`='$logintime',`loginip`='$loginip';");
                    if($res) {
                        $mid = $dsql->GetLastID();
                        $data = array
                        (
                        0 => "INSERT INTO `#@__member_person` SET `mid`='$mid', `onlynet`='1', `sex`='男', `uname`='$username', `qq`='', `msn`='', `tel`='', `mobile`='', `place`='', `oldplace`='0' ,
                                 `birthday`='1980-01-01', `star`='1', `income`='0', `education`='0', `height`='160', `bodytype`='0', `blood`='0', `vocation`='0', `smoke`='0', `marital`='0', `house`='0',
                       `drink`='0', `datingtype`='0', `language`='', `nature`='', `lovemsg`='', `address`='',`uptime`='0';",
                        1 => "INSERT INTO `#@__member_tj` SET `mid`='$mid',`article`='0',`album`='0',`archives`='0',`homecount`='0',`pagecount`='0',`feedback`='0',`friend`='0',`stow`='0';",
                        2 => "INSERT INTO `#@__member_space` SET `mid`='$mid',`pagesize`='10',`matt`='0',`spacename`='{$uname}的空间',`spacelogo`='',`spacestyle`='person', `sign`='',`spacenews`='';",
                        3 => "INSERT INTO `#@__member_flink` SET `mid`='$mid', `title`='织梦内容管理系统', `url`='http://www.dedecms.com';"
                        );                        
                        foreach($data as $val) $dsql->ExecuteNoneQuery($val);
                    }
                }
                $rs = 1;
                $row = $dsql->GetOne("SELECT `mid`, `pwd` FROM #@__member WHERE `userid`='$username'");
                if(isset($row['mid']))
                {
                    $cfg_ml->PutLoginInfo($row['mid']);
                    if($password!=$row['pwd']) $dsql->ExecuteNoneQuery("UPDATE #@__member SET `pwd`='$password' WHERE mid='$row[mid]'");
                }
                //生成同步登录的代码
                $ucsynlogin = uc_user_synlogin($uid);
            } else if($uid == -1) {
                //当UC不存在该用而CMS存在,就注册一个.
                if($rs) {
                    $row = $dsql->GetOne("SELECT `email` FROM #@__member WHERE userid='$userid'");                    
                    $uid = uc_user_register($userid, $pwd, $row['email']);
                    if($uid > 0) $ucsynlogin = uc_user_synlogin($uid);
                } else {
                    $rs = -1;
                }
            } else {
                $rs = -1;
            }
        }
        #/aip}}        
        
        if($rs==0)
        {
            ResetVdValue();
            ShowMsg("用户名不存在！", "-1");
            exit();
        }
        else if($rs==-1) {
            ResetVdValue();
            ShowMsg("密码错误！", "-1");
            exit();
        }
        else if($rs==-2) {
            ResetVdValue();
            ShowMsg("管理员帐号不允许从前台登录！", "-1");
            exit();
        }
        else
        {
            // 清除会员缓存
            $cfg_ml->DelCache($cfg_ml->M_ID);
            if(empty($gourl) || preg_match("#action|_do#i", $gourl))
            {
                ShowMsg("登录成功","/i/",0,8);
            }
            else
            {
                ShowMsg("登录成功",$gourl,0,8);
            }
            exit();
        }
    }

    //退出登录
    else if($dopost=="exit")
    {
        $cfg_ml->ExitCookie();
        #api{{
        if(defined('UC_API') && @include_once DEDEROOT.'/uc_client/client.php')
        {
            $ucsynlogin = uc_user_synlogout();
        }
        #/aip}}
        ShowMsg("成功退出登录！","$cfg_basehost",0,8);
        exit();
    }
}
else if($fmdo=='weblogin')
{
    //用户登录
    if($dopost=="weblogin")
    {
		
        if(CheckUserID($username,'',false)!='ok')
        {
            ResetVdValue();
            echo "<script>alert('你输入的用户名 {$username} 不合法');location.href='".$_SERVER["HTTP_REFERER"]."'</script>";
			//ShowMsg("你输入的用户名 {$userid} 不合法！","index.php");
            exit();
        }
        if($password=='')
        {
            ResetVdValue();
            echo "<script>alert('密码不能为空');location.href='".$_SERVER["HTTP_REFERER"]."'</script>";
			//ShowMsg("密码不能为空！","-1",0,2000);
            exit();
        }
        //检查帐号
        $rs = $cfg_ml->CheckUser($username,$password);
        if($rs==0)
        {
            ResetVdValue();
            echo "<script>alert('用户名不存在');location.href='".$_SERVER["HTTP_REFERER"]."'</script>";
			//ShowMsg("用户名不存在！", "-1", 0, 2000);
            exit();
        }
        else if($rs==-1) {
            ResetVdValue();
            echo "<script>alert('密码错误');location.href='".$_SERVER["HTTP_REFERER"]."'</script>";
			//ShowMsg("密码错误！", "-1", 0, 2000);
            exit();
        }
        else if($rs==-2) {
            ResetVdValue();
            echo "<script>alert('管理员帐号不允许从前台登录');location.href='".$_SERVER["HTTP_REFERER"]."'</script>";
			//ShowMsg("管理员帐号不允许从前台登录！", "-1", 0, 2000);
            exit();
        }
        else
        {
            // 清除会员缓存
            $cfg_ml->DelCache($cfg_ml->M_ID);
            if(empty($gourl) || preg_match("#action|_do#i", $gourl))
            {
                echo "<script>alert('登录成功，点击确定后返回登录前的页面！');location.href='".$_SERVER["HTTP_REFERER"]."'</script>";
				//ShowMsg("成功登录，5秒钟后转向系统主页...","index.php",0,2000);
            }
            else
            {
                echo "<script>alert('登录成功，点击确定后返回登录前的页面！');location.href='".$_SERVER["HTTP_REFERER"]."'</script>";
                //ShowMsg("成功登录，现在转向指定页面...",$gourl,0,2000);
            }
            exit();
        }
    }

    //退出登录
    else if($dopost=="exit")
    {
        $cfg_ml->ExitCookie();
        /*echo "<script>alert('成功退出登录，点击确定后返回退出前的页面！');location.href='".$_SERVER["HTTP_REFERER"]."'</script>";*/
		ShowMsg("成功退出登录！","$cfg_basehost",0,8);
        exit();
    }
}
else if($fmdo=='qq_login')
{
    //用户登录
    if($dopost=="qq_login")
    {
		$userid = $u;
		$row = $dsql->GetOne("SELECT * FROM `#@__member` WHERE userid='$userid'");
		$pwds = $row['pwd'];
        if(CheckUserID($userid,'',false)!='ok')
        {
            ResetVdValue();
            ShowMsg("你输入的用户名 {$userid} 不合法！","-1");
            exit();
        }
        if($pwds=='')
        {
            ResetVdValue();
            ShowMsg("密码不能为空！","-1",0,2000);
            exit();
        }
        //检查帐号
        $rs = $cfg_ml->CheckUser($userid,$pwds);
        if($rs==0)
        {
            ResetVdValue();
			ShowMsg("用户名不存在！", "-1");
            exit();
        }
        else if($rs==-1) {
            ResetVdValue();
			ShowMsg("密码错误！", "-1");
            exit();
        }
        else if($rs==-2) {
            ResetVdValue();
			ShowMsg("管理员帐号不允许从前台登录！", "-1");
            exit();
        }
        else
        {
            // 清除会员缓存
            $cfg_ml->DelCache($cfg_ml->M_ID);
            if(empty($gourl) || preg_match("#action|_do#i", $gourl))
            {
				ShowMsg("成功登录，5秒钟后转向网站主页...","$cfg_basehost",0,8);
            }
            else
            {
                ShowMsg("成功登录，5秒钟后转向网站主页...","$cfg_basehost",0,8);
            }
            exit();
        }
    }
}
/*********************
function moodmsg()
*******************/
else if($fmdo=='moodmsg')
{
    //用户登录
    if($dopost=="sendmsg")
    {
        if(!empty($content))
        {
        $ip = GetIP();
        $dtime = time();
          $ischeck = ($cfg_mb_msgischeck == 'Y')? 0 : 1;
          if($cfg_soft_lang == 'gb2312')
          {
              $content = utf82gb(nl2br($content));
          } 
          $content = cn_substrR(HtmlReplace($content,1),360);
          //对表情进行解析
          $content = addslashes(preg_replace("/\[face:(\d{1,2})\]/is","<img src='".$cfg_cmsurl."/templets/images/smiley/\\1.gif' style='cursor: pointer; position: relative;'>",$content));
          $content = RemoveXSS($content);
            $inquery = "INSERT INTO `#@__member_msg`(`mid`,`userid`,`ip`,`ischeck`,`dtime`, `msg`)
                   VALUES ('{$cfg_ml->M_ID}','{$cfg_ml->M_LoginID}','$ip','$ischeck','$dtime', '$content'); ";
            $rs = $dsql->ExecuteNoneQuery($inquery);
            if(!$rs)
            {
                $output['type'] = 'error';
                $output['data'] = '更新失败,请重试.';
                exit();
            }
            $output['type'] = 'success';
            if($cfg_soft_lang == 'gb2312')
            {
              $content = utf82gb(nl2br($content));
            } 
            $output['data'] = stripslashes($content);
            exit(json_encode($output));
        }
    }
}

else
{
    ShowMsg("本页面禁止返回!","/i/",0,8);
}