<?php
/**
 	用户注册
 */
require_once(dirname(__FILE__)."/config.php");
require_once DEDEINC.'/membermodel.cls.php';
if($cfg_mb_allowreg=='N')
{
    ShowMsg('系统关闭了新用户注册！', "$cfg_basehost",0,8);
    exit();
}

if(!isset($dopost)) $dopost = '';
$step = empty($step)? 1 : intval(preg_replace("/[^\d]/", '', $step));

if($step == 1)
{
    if($cfg_ml->IsLogin())
    {
        if($cfg_mb_reginfo == 'Y')
        {
            //如果启用注册详细信息
            if($cfg_ml->fields['spacesta'] == 0 || $cfg_ml->fields['spacesta'] == 1)
            {
                 ShowMsg("尚未完成详细资料，请完善...", "/i/?type=reg&step=2", 0, 8);
                 exit;
            }
        }
        ShowMsg('你已经登陆系统，无需重新注册！', '/i/',0,8);
        exit();
    }
    if($dopost=='regbase')
    {
        $svali = GetCkVdValue();
        if(preg_match("/1/", $safe_gdopen)){
            if(strtolower($vdcode)!=$svali || $svali=='')
            {
                ResetVdValue();
                ShowMsg('验证码错误！', '-1',0,8);
                exit();
            }
        }
        
        $faqkey = isset($faqkey) && is_numeric($faqkey) ? $faqkey : 0;
        if($safe_faq_reg == '1')
        {
            if($safefaqs[$faqkey]['answer'] != $rsafeanswer || $rsafeanswer=='')
            {
                ShowMsg('验证问题答案错误', '-1',0,8);
                exit();
            }
        }
        
        $userid = trim($userid);
		$uname = $userid;
        $pwd = trim($userpwd);
        $pwdc = trim($userpwdok);
        $rs = CheckUserID($userid, '用户名');
        if($rs != 'ok')
        {
            ShowMsg($rs, '-1');
            exit();
        }
        if(strlen($userid) > 20 || strlen($uname) > 36)
        {
            ShowMsg('你的用户名或用户笔名过长，不允许注册！', '-1',0,8);
            exit();
        }
        if(strlen($userid) < $cfg_mb_idmin || strlen($pwd) < $cfg_mb_pwdmin)
        {
            ShowMsg("你的用户名或密码过短，不允许注册！","-1",0,8);
            exit();
        }
        if($pwdc != $pwd)
        {
            ShowMsg('你两次输入的密码不一致！', '-1',0,8);
            exit();
        }
        
        $uname = HtmlReplace($uname, 1);
        //用户笔名重复检测
        if($cfg_mb_wnameone=='N')
        {
            $row = $dsql->GetOne("SELECT * FROM `#@__member` WHERE uname LIKE '$uname' ");
            if(is_array($row))
            {
                ShowMsg('用户笔名或公司名称不能重复！', '-1',0,8);
                exit();
            }
        }
        if(!CheckEmail($email))
        {
            ShowMsg('Email格式不正确！', '-1',0,8);
            exit();
        }
        
        #api{{
        if(defined('UC_API') && @include_once DEDEROOT.'/uc_client/client.php')
        {
            $uid = uc_user_register($userid, $pwd, $email);
            if($uid <= 0)
            {
                if($uid == -1)
                {
                    ShowMsg("用户名不合法！","-1",0,8);
                    exit();
                }
                elseif($uid == -2)
                {
                    ShowMsg("包含要允许注册的词语！","-1",0,8);
                    exit();
                }
                elseif($uid == -3)
                {
                    ShowMsg("你指定的用户名 {$userid} 已存在，请使用别的用户名！","-1",0,8);
                    exit();
                }
                elseif($uid == -5)
                {
                    ShowMsg("你使用的Email 不允许注册！","-1",0,8);
                    exit();
                }
                elseif($uid == -6)
                {
                    ShowMsg("你使用的Email已经被另一帐号注册，请使其它帐号","-1",0,8);
                    exit();
                }
                else
                {
                    ShowMsg("注册失败！","-1",0,8);
                    exit();
                }
            }
            else
            {
                $ucsynlogin = uc_user_synlogin($uid);
            }
        }
        #/aip}}
        
        if($cfg_md_mailtest=='Y')
        {
            $row = $dsql->GetOne("SELECT mid FROM `#@__member` WHERE email LIKE '$email' ");
            if(is_array($row))
            {
                ShowMsg('你使用的Email已经被另一帐号注册，请使其它帐号！', '-1',0,8);
                exit();
            }
        }
    
        //检测用户名是否存在
        $row = $dsql->GetOne("SELECT mid FROM `#@__member` WHERE userid LIKE '$userid' ");
        if(is_array($row))
        {
            ShowMsg("你指定的用户名 {$userid} 已存在，请使用别的用户名！", "-1",0,8);
            exit();
        }
        if($safequestion==0)
        {
            $safeanswer = '';
        }
        else
        {
            if(strlen($safeanswer)>30)
            {
                ShowMsg('你的新安全问题的答案太长了，请控制在30字节以内！', '-1',0,8);
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
        $pwd = md5($userpwd);
		$mtype = RemoveXSS(HtmlReplace($mtype,1));
		$safeanswer = HtmlReplace($safeanswer);
		$safequestion = HtmlReplace($safequestion);
        
        $inQuery = "INSERT INTO `#@__member` (`mtype` ,`userid` ,`pwd` ,`uname` ,`sex` ,`rank` ,`money` ,`email` ,`scores` ,
        `matt`, `spacesta` ,`face`,`safequestion`,`safeanswer` ,`jointime` ,`joinip` ,`logintime` ,`loginip` )
       VALUES ('$mtype','$userid','$pwd','$uname','$sex','10','$dfmoney','$email','$dfscores',
       '0','-10','','$safequestion','$safeanswer','$jointime','$joinip','$logintime','$loginip'); ";
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
            $rs = $cfg_ml->CheckUser($userid, $userpwd);

            
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
                    $smtp->sendmail($email,$cfg_webname,$cfg_smtp_usermail, $mailtitle, $mailbody, $mailtype);
                }
                else
                {
                    @mail($email, $mailtitle, $mailbody, $headers);
                }
            }//End 邮件验证
            
            if($cfg_mb_reginfo == 'Y' && $spaceSta >=0)
            {
                ShowMsg("完成基本信息的注册，接下来完善详细资料...","/i/?type=reg&step=2",0,8);
                exit();
            } else {
                //require_once(DEDEMEMBER."/templets/reg-new3.htm");
				$gourl = str_replace("*","&",$gourl);
				if(!empty($gourl)){
					$lokurl = $gourl;
				}else{
					$lokurl = $cfg_basehost.$cfg_cmsurl.'/i/';
				}
			    header("Location: $lokurl");
                exit;
            } 
        } else {
            ShowMsg("注册失败，请检查资料是否有误或与管理员联系！", "-1",0,8);
            exit();
        }
    }
    require_once(DEDEMEMBER."/templets/reg-new.htm");
}else {
    if(!$cfg_ml->IsLogin())
    {
        ShowMsg("尚未完成基本信息的注册,请返回重新填写！", "/i/?type=reg",0,8);
        exit;
    } else {
        if($cfg_ml->fields['spacesta'] == 2)
        {
             ShowMsg('你已经登陆系统，无需重新注册！', '/i/',0,8);
             exit;
        }
    }
    $membermodel = new membermodel($cfg_ml->M_MbType);
    $postform = $membermodel->getForm(true);
    if($dopost == 'reginfo')
    {
        //这里完成详细内容填写
        $dede_fields = empty($dede_fields) ? '' : trim($dede_fields);
        $dede_fieldshash = empty($dede_fieldshash) ? '' : trim($dede_fieldshash);
        $modid = empty($modid)? 0 : intval(preg_replace("/[^\d]/",'', $modid));
        
        if(!empty($dede_fields))
        {
            if($dede_fieldshash != md5($dede_fields.$cfg_cookie_encode))
            {
                showMsg('数据校验不对，程序返回', '-1',0,8);
                exit();
            }
        }
        $modelform = $dsql->GetOne("SELECT * FROM #@__member_model WHERE id='$modid' ");
        if(!is_array($modelform))
        {
            showmsg('模型表单不存在', '-1',0,8);
            exit();
        }
        $inadd_f = '';
        if(!empty($dede_fields))
        {
            $fieldarr = explode(';', $dede_fields);
            if(is_array($fieldarr))
            {
                foreach($fieldarr as $field)
                {
                    if($field == '') continue;
                    $fieldinfo = explode(',', $field);
                    if($fieldinfo[1] == 'textdata')
                    {
                        ${$fieldinfo[0]} = FilterSearch(stripslashes(${$fieldinfo[0]}));
                        ${$fieldinfo[0]} = addslashes(${$fieldinfo[0]});
                    }
                    else
                    {
                        if(empty(${$fieldinfo[0]})) ${$fieldinfo[0]} = '';
                        ${$fieldinfo[0]} = GetFieldValue(${$fieldinfo[0]}, $fieldinfo[1],0,'add','','diy', $fieldinfo[0]);
                    }
                    if($fieldinfo[0]=="birthday") ${$fieldinfo[0]}=GetDateMk(${$fieldinfo[0]});
                    $inadd_f .= ','.$fieldinfo[0]." ='".${$fieldinfo[0]}."' ";
                }
            }

        }
		
  
        $query = "UPDATE `{$membermodel->table}` SET `mid`='{$cfg_ml->M_ID}' $inadd_f WHERE `mid`='{$cfg_ml->M_ID}'; ";
        if($dsql->executenonequery($query))
        {
            $dsql->ExecuteNoneQuery("UPDATE `#@__member` SET `spacesta`='2' WHERE `mid`='{$cfg_ml->M_ID}'");
            // 清除缓存
            $cfg_ml->DelCache($cfg_ml->M_ID);
            require_once(DEDEMEMBER."/templets/reg-new3.htm");
            exit;
        }
    }
    require_once(DEDEMEMBER."/templets/reg-new2.htm");
}