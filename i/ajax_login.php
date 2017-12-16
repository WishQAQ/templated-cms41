<?php
require_once(dirname(__FILE__)."/../qm_user/config.php");
if(empty($dopost)) $dopost = '';
if(empty($fmdo)) $fmdo = '';

/*********************
function check_email()
*******************/
if($fmdo=='login')
{
    //用户登录
    if($dopost=="login")
    {
		if(CheckUserID($userid,'',false)!='ok')
        {
			if($myset=='ajax'){
				$arr = array("son"=>"err","msg"=>"你输入的用户名 {$userid} 不合法","foid"=>"username");
				echo json_encode($arr);
				exit;
			}
            ShowMsg("你输入的用户名 {$userid} 不合法！","-1");
            exit();
        }
        
		if(!isset($vdcode))
        {
            $vdcode = '';
        }
        $svali = GetCkVdValue();
        if(preg_match("/2/",$safe_gdopen)){
            if(strtolower($vdcode)!=$svali || $svali=='')
            {
                ResetVdValue();
                if($myset=='ajax'){
					$arr = array("son"=>"err","msg"=>"验证码错误","foid"=>"vdcode");
					echo json_encode($arr);
					exit;
				}
				ShowMsg('验证码错误！', 'index.php');
                exit();
            }
            
        }
		
        //检查帐号
        $rs = $cfg_ml->CheckUser($userid,$pwd);  
        if($rs==0)
        {
			if($myset=='ajax'){
				$arr = array("son"=>"err","msg"=>"用户名不存在","foid"=>"username");
				echo json_encode($arr);
				exit;
			}
            ShowMsg("用户名不存在！", "-1", 0, 2000);
            exit();
        }
        else if($rs==-1) {
			if($myset=='ajax'){
				$arr = array("son"=>"err","msg"=>"密码错误","foid"=>"password");
				echo json_encode($arr);
				exit;
			}
            ShowMsg("密码错误！", "-1", 0, 2000);
            exit();
        }
        else if($rs==-2) {
			if($myset=='ajax'){
				$arr = array("son"=>"err","msg"=>"管理员帐号不允许从前台登录","foid"=>"username");
				echo json_encode($arr);
				exit;
			}
            ShowMsg("管理员帐号不允许从前台登录！", "-1", 0, 2000);
            exit();
        }
        else
        {
            // 清除会员缓存
            $cfg_ml->DelCache($cfg_ml->M_ID);
            if(empty($gourl) || preg_match("#action|_do#i", $gourl))
            {
				if($myset=='ajax'){
					$arr = array("son"=>"ok","msg"=>"登录成功，即将为你刷新","foid"=>"username");
					echo json_encode($arr);
					exit;
				}
			    ShowMsg("成功登录，5秒钟后转向系统主页...","index.php",0,2000);
            }
            else
            {
                $gourl = str_replace('^','&',$gourl);
				if($myset=='ajax')die('6');
                ShowMsg("成功登录，现在转向指定页面...",$gourl,0,2000);
            }
            exit();
        }
    }
    //退出登录
    else if($dopost=="exit")
    {
		$cfg_ml->ExitCookie();
		if($myset=='ajax')die('7');
        ShowMsg("成功退出登录！","index.php",0,2000);
        exit();
    }
}
else if($fmdo=='reg')
{

	if($step == 1)
	{
		require_once DEDEINC.'/membermodel.cls.php';
		if($cfg_mb_allowreg=='N')
		{
			if($myset=='ajax'){
				$arr = array("son"=>"err","msg"=>"系统关闭了新用户注册","foid"=>"user_name");
				echo json_encode($arr);
				exit;
			}
			ShowMsg('系统关闭了新用户注册！', '/i/');
			exit();
		}
		
		if(!isset($dopost)) $dopost = '';
		$step = empty($step)? 1 : intval(preg_replace("/[^\d]/", '', $step));
		if($dopost=='ajax_reg')
		{
			
			$mtype = '个人';
			$userid = trim($user_name);
			$user_uname = $userid;
			$pwd = trim($user_pass);
			$pwdc = trim($user_pass2);
			$rs = CheckUserID($userid, '用户名');
			
			if($rs != 'ok')
			{
				if($myset=='ajax_reg'){
					$arr = array("son"=>"err","msg"=>$rs,"foid"=>"user_name");
					echo json_encode($arr);
					exit;
				}
				ShowMsg($rs, '-1');
				exit();
			}
			
			if(strlen($userid) > 20 || strlen($user_uname) > 36)
			{
				if($myset=='ajax_reg'){
					$arr = array("son"=>"err","msg"=>"登录名不能大于20位","foid"=>"user_name");
					echo json_encode($arr);
					exit;
				}
				ShowMsg('你的用户名或用户笔名过长，不允许注册！', '-1');
				exit();
			}
			
			if(strlen($userid) < $cfg_mb_idmin)
			{
				if($myset=='ajax_reg'){
					$arr = array("son"=>"err","msg"=>"登录名不能小于".$cfg_mb_idmin."位","foid"=>"user_name");
					echo json_encode($arr);
					exit;
				}
				ShowMsg("你的用户名过短，不允许注册！","-1");
				exit();
			}
			
			
			//检测用户名是否存在
			$row = $dsql->GetOne("SELECT mid FROM `#@__member` WHERE userid LIKE '$userid' ");
			if(is_array($row))
			{
				if($myset=='ajax_reg'){
					$arr = array("son"=>"err","msg"=>"登录名已存在","foid"=>"user_name");
					echo json_encode($arr);
					exit;
				}
				ShowMsg("你指定的用户名 {$user_uname} 已存在，请使用别的用户名！", "-1");
				exit();
			}
			
			if($cfg_md_mailtest=='Y')
			{
				$row = $dsql->GetOne("SELECT mid FROM `#@__member` WHERE email LIKE '$user_email' ");
				if(is_array($row))
				{
					if($myset=='ajax_reg'){
						$arr = array("son"=>"err","msg"=>"邮箱已存在","foid"=>"user_email");
						echo json_encode($arr);
						exit;
					}
					ShowMsg('你使用的邮箱已经被另一帐号注册，请使其它邮箱！', '-1');
					exit();
				}
			}
			
			$mdyzm = md5($user_yzm);
			if(empty($user_yzm)){
				if($myset=='ajax_reg'){
					$arr = array("son"=>"err","msg"=>"请填写邮箱验证码","foid"=>"user_yzm");
					echo json_encode($arr);
					exit;
				}
			}
			else if($mdyzm != $qmold)
			{
				if($myset=='ajax_reg'){
					$arr = array("son"=>"err","msg"=>"邮箱验证码错误q:{$qmold}|o:{$mdyzm}","foid"=>"user_yzm");
					echo json_encode($arr);
					exit;
				}
				ShowMsg('邮箱验证码错误！', '-1');
				exit();
			}
			
			if(strlen($pwd) < $cfg_mb_pwdmin)
			{
				if($myset=='ajax_reg'){
					$arr = array("son"=>"err","msg"=>"密码不能小于".$cfg_mb_pwdmin."位","foid"=>"user_pass");
					echo json_encode($arr);
					exit;
				}
				ShowMsg("你的用户名或密码过短，不允许注册！","-1");
				exit();
			}
			
			$svali = GetCkVdValue();
			if(preg_match("/1/", $safe_gdopen)){
				if(strtolower($vdcode_reg)!=$svali || $svali=='')
				{
					ResetVdValue();
					if($myset=='ajax_reg'){
						$arr = array("son"=>"err","msg"=>"验证码错误","foid"=>"vdcode_reg");
						echo json_encode($arr);
						exit;
					}
					ShowMsg('验证码错误！', '-1');
					exit();
				}
			}
			
			//会员的默认金币
			$dfscores = 0;
			$dfmoney = 0;
			$dfrank = $dsql->GetOne("SELECT money,scores FROM `#@__arcrank` WHERE rank='10' ");
			if(is_array($dfrank))
			{
				$dfmoney = $dfrank['money'];
				$dfscores = $dfrank['scores'];
			}
			$jointime = time();
			$logintime = time();
			$joinip = GetIP();
			$loginip = GetIP();
			$pwd = md5($pwd);
			$mtype = RemoveXSS(HtmlReplace($mtype,1));
			$safeanswer = HtmlReplace($safeanswer);
			$safequestion = HtmlReplace($safequestion);
			
			$spaceSta = ($cfg_mb_spacesta < 0 ? $cfg_mb_spacesta : 0);
			
			$inQuery = "INSERT INTO `#@__member` (`mtype` ,`userid` ,`pwd` ,`uname` ,`sex` ,`rank` ,`money` ,`email` ,`scores` ,
			`matt`, `spacesta` ,`face`,`safequestion`,`safeanswer` ,`jointime` ,`joinip` ,`logintime` ,`loginip` )
		   VALUES ('$mtype','$userid','$pwd','$user_uname','$sex','10','$dfmoney','$user_email','$dfscores',
		   '0','$spaceSta','','$safequestion','$safeanswer','$jointime','$joinip','$logintime','$loginip'); ";
			if($dsql->ExecuteNoneQuery($inQuery))
			{
				$mid = $dsql->GetLastID();
		
				//写入默认会员详细资料
				if($mtype=='个人'){
					$space='person';
				}else if($mtype=='企业'){
					$space='company';
				}else{
					$space='person';
				}
		
				//写入默认统计数据
				$membertjquery = "INSERT INTO `#@__member_tj` (`mid`,`article`,`album`,`archives`,`homecount`,`pagecount`,`feedback`,`friend`,`stow`)
					   VALUES ('$mid','0','0','0','0','0','0','0','0'); ";
				$dsql->ExecuteNoneQuery($membertjquery);
		
				//写入默认空间配置数据
				$spacequery = "INSERT INTO `#@__member_space`(`mid` ,`pagesize` ,`matt` ,`spacename` ,`spacelogo` ,`spacestyle`, `sign` ,`spacenews`)
						VALUES('{$mid}','10','0','{$uname}的空间','','$space','',''); ";
				$dsql->ExecuteNoneQuery($spacequery);
		
				//写入其它默认数据
				$dsql->ExecuteNoneQuery("INSERT INTO `#@__member_flink`(mid,title,url) VALUES('$mid','织梦内容管理系统','http://www.dedecms.com'); ");
				
				$membermodel = new membermodel($mtype);
				$modid=$membermodel->modid;
				$modid = empty($modid)? 0 : intval(preg_replace("/[^\d]/",'', $modid));
				$modelform = $dsql->getOne("SELECT * FROM #@__member_model WHERE id='$modid' ");
				
				if(!is_array($modelform))
				{
					showmsg('模型表单不存在', '-1');
					exit();
				}else{
					$dsql->ExecuteNoneQuery("INSERT INTO `{$membermodel->table}` (`mid`) VALUES ('{$mid}');");
				}
				
				//----------------------------------------------
				//模拟登录
				//---------------------------
				$cfg_ml = new MemberLogin(7*3600);
				$rs = $cfg_ml->CheckUser($userid, $user_pass);
	
				
				//邮件验证
				if($cfg_mb_spacesta==-10)
				{
					$userhash = md5($cfg_cookie_encode.'-'.$mid);
					$url = $cfg_basehost.(empty($cfg_cmspath) ? '/' : $cfg_cmspath)."/i/?type=do&fmdo=checkMail&mid={$mid}&userhash={$userhash}&do=1";
					$url = preg_replace("#http:\/\/#i", '', $url);
					$url = 'http://'.preg_replace("#\/\/#", '/', $url);
					$logo = $cfg_basehost.(empty($cfg_cmspath) ? '/' : $cfg_cmspath)."/skin/1.0/images/logo.png";
					$logo = preg_replace("#http:\/\/#i", '', $logo);
					$logo = 'http://'.preg_replace("#\/\/#", '/', $logo);
					$mailtitle = "欢迎加入{$cfg_webname}，请确认验证邮箱";
					$mailbody .= "<table width=\"800\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#FBF8F1\" style=\"border-radius:5px; overflow:hidden; border-top:4px solid #00c3b6; border-right:1px solid #dbd1ce; border-bottom:1px solid #dbd1ce; border-left:1px solid #dbd1ce;\">
			<tbody>
			  <tr>
		<td><div style=\"padding:10px 20px;font-size:14px;color:#333333;border-top:1px solid #dbd1ce;\"><p>亲爱的 {$user_uname} 您好。</p>
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
						$smtp->sendmail($user_email,$cfg_webname,$cfg_smtp_usermail, $mailtitle, $mailbody, $mailtype);
					}
					else
					{
						@mail($user_email, $mailtitle, $mailbody, $headers);
					}
				}//End 邮件验证
				
				if($cfg_mb_reginfo == 'Y' && $spaceSta >=0){
					ShowMsg("完成基本信息的注册，接下来完善详细资料...","/i/?type=do&fmdo=user&dopost=regnew&step=2",0,1000);
					exit();
				}else{
					if($myset=='ajax_reg'){
						$arr = array("son"=>"ok","msg"=>"注册成功并已登录","foid"=>"user_name");
						echo json_encode($arr);
						exit;
					}
					ShowMsg("注册成功并已登录，点击确定后返回注册前的页面","-1");
					require_once(DEDEMEMBER."/templets/reg-new3.htm");
					exit;
				} 
			} else {
				if($myset=='ajax_reg'){
					$arr = array("son"=>"err1","msg"=>"注册失败 请联系管理员","foid"=>"user_name");
					echo json_encode($arr);
					exit;
				}
				ShowMsg("注册失败，请检查资料是否有误或与管理员联系！", "-1");
				exit();
			}
		}
	}
}
else
{
    ShowMsg("本页面禁止返回!","index.php");
}