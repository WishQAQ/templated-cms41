<?php
/**
 *
 * 下载
 *
 * @version        $Id: download.php 1 15:38 2010年7月8日Z tianya $
 * @package        DedeCMS.Site
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/../include/common.inc.php");
require_once(DEDEINC."/channelunit.class.php");
if(!isset($open)) $open = 0;

//读取链接列表
if($open==0)
{
    $aid = (isset($aid) && is_numeric($aid)) ? $aid : 0;
    if($aid==0) exit(' Request Error! ');

    $arcRow = GetOneArchive($aid);
    if($arcRow['aid']=='')
    {
        ShowMsg('无法获取未知文档的信息!','-1');
        exit();
    }
	
    extract($arcRow, EXTR_SKIP);
    $cu = new ChannelUnit($arcRow['channel'],$aid);
    if(!is_array($cu->ChannelFields))
    {
        ShowMsg('获取文档信息失败！','-1');
        exit();
    }
    $vname = '';
    foreach($cu->ChannelFields as $k=>$v)
    {
        if($v['type']=='softlinks'){ $vname=$k; break; }
    }
    $row = $dsql->GetOne("SELECT $vname FROM `".$cu->ChannelInfos['addtable']."` WHERE aid='$aid'");

    include_once(DEDEINC.'/taglib/channel/softlinks.lib.php');
    $ctag = '';
    $downlinks = ch_softlinks($row[$vname], $ctag, $cu, '', TRUE);
	$temp = str_replace("~size~",$link,$tempStr);
    require_once(DEDETEMPLATE."/{$cfg_df_style}/dow-list.htm");
    exit();
}
/*------------------------
//提供素材给用户下载(旧模式)
function getSoft_old()
------------------------*/
else if($open==1)
{
    //更新下载次数
    $id = isset($id) && is_numeric($id) ? $id : 0;
    $link = base64_decode(urldecode($link));
    $hash = md5($link);
    $rs = $dsql->ExecuteNoneQuery2("UPDATE `#@__downloads` SET downloads = downloads + 1 WHERE hash='$hash' ");
    if($rs <= 0)
    {
        $query = " INSERT INTO `#@__downloads`(`hash`,`id`,`downloads`) VALUES('$hash','$id',1); ";
        $dsql->ExecNoneQuery($query);
    }
    header("location:$link");
    exit();
}
/*------------------------
//提供素材给用户下载(新模式)
function getSoft_new()
------------------------*/
else if($open==2)
{
    $id = intval($id);
    //获得附加表信息
    $row = $dsql->GetOne("SELECT ch.addtable,arc.mid FROM `#@__arctiny` arc LEFT JOIN `#@__channeltype` ch ON ch.id=arc.channel WHERE arc.id='$id' ");
    if(empty($row['addtable']))
    {
        ShowMsg('找不到所需要的素材资源！', 'javascript:;');
        exit();
    }
    $mid = $row['mid'];
    
    //读取连接列表、下载权限信息
    $row = $dsql->GetOne("SELECT softlinks,daccess,needmoney FROM `{$row['addtable']}` WHERE aid='$id' ");
    if(empty($row['softlinks']))
    {
        ShowMsg('找不到所需要的素材资源！', 'javascript:;');
        exit();
    }
    $softconfig = $dsql->GetOne("SELECT * FROM `#@__softconfig` ");
    $needRank = $softconfig['dfrank'];
    $needMoney = $softconfig['dfywboy'];
    if($softconfig['argrange']==0)
    {
        $needRank = $row['daccess'];
        $needMoney = $row['needmoney'];
    }
    
    //分析连接列表
    require_once(DEDEINC.'/dedetag.class.php');
    $softUrl = '';
    $islocal = 0;
    $dtp = new DedeTagParse();
    $dtp->LoadSource($row['softlinks']);
    if( !is_array($dtp->CTags) )
    {
        $dtp->Clear();
        ShowMsg('找不到所需要的素材资源！', 'javascript:;');
        exit();
    }
    foreach($dtp->CTags as $ctag)
    {
        if($ctag->GetName()=='link')
        {
            $link = trim($ctag->GetInnerText());
            $islocal = $ctag->GetAtt('islocal');
            //分析本地链接
            if(!isset($firstLink) && $islocal==1) $firstLink = $link;
            if($islocal==1 && $softconfig['islocal'] != 1) continue;
            
            //支持http,迅雷下载,ftp,flashget
            if(!preg_match("#^http:\/\/|^thunder:\/\/|^ftp:\/\/|^flashget:\/\/#i", $link))
            {
                 $link = $cfg_mainsite.$link;
            }
            $dbhash = substr(md5($link), 0, 24);
            if($uhash==$dbhash) $softUrl = $link;
        }
    }
    $dtp->Clear();
    if($softUrl=='' && $softconfig['ismoresite']==1 
    && $softconfig['moresitedo']==1 && trim($softconfig['sites'])!='' && isset($firstLink))
    {
        $firstLink = preg_replace("#http:\/\/([^\/]*)\/#i", '/', $firstLink);
        $softconfig['sites'] = preg_replace("#[\r\n]{1,}#", "\n", $softconfig['sites']);
        $sites = explode("\n", trim($softconfig['sites']));
        foreach($sites as $site)
        {
            if(trim($site)=='') continue;
            list($link, $serverName) = explode('|', $site);
            $link = trim( preg_replace("#\/$#", "", $link) ).$firstLink;
            $dbhash = substr(md5($link), 0, 24);
            if($uhash == $dbhash) $softUrl = $link;
        }
    }
    if( $softUrl == '' )
    {
        ShowMsg('找不到所需要的素材资源！', 'javascript:;');
        exit();
    }
    //-------------------------
    // 读取文档信息，判断权限
    //-------------------------
    $arcRow = GetOneArchive($id);
    if($arcRow['aid']=='')
    {
        ShowMsg('无法获取未知文档的信息!','-1');
        exit();
    }
	
    extract($arcRow, EXTR_SKIP);
    require_once(DEDEINC.'/memberlogin.class.php');
    $cfg_ml = new MemberLogin();
	if($cfg_ml->M_ID==0)
	{
            $errtype = 1;
			$msgtitle = "您还没有登录，不能下载：{$arctitle} ！";
            $moremsg = "您还没有登录，不能下载：{$arctitle}，请先登录。";
            include_once(DEDETEMPLATE."/{$cfg_df_style}/dow-list-msg.htm");
			exit();
	}

	$sql = "SELECT aid,money FROM `#@__member_operation` WHERE buyid='ARCHIVE".$id."' AND mid='".$cfg_ml->M_ID."'";
    $row = $dsql->GetOne($sql);
    //未购买过此文章
    if( !is_array($row) )
	{
		$dneedMoney = "本次下载花费金币：<font color='red'>{$needMoney}</font>个";
	}
	else
	{
		$dneedMoney = "本次下载花费金币：<font color='red'>0</font>个，您之前购买过此素材，享免费特权";
	}
	//如果需要的金币大于等于200 
	//那么扣费成功后将发送下载信息给已绑定邮箱的会员
	//需要到后台开启此功能 （站窝窝网络扩展）
	if($cfg_sendemail == 'Y')
	{
		if($needMoney >= "$cfg_send_email_money")
		{	
			$tqm = $dsql->GetOne("SELECT panpwd FROM `#@__addonmaterial` WHERE aid='$aid' ");
			$panpwd = $tqm['panpwd'];
			if($panpwd == ''){
				$panpwd = '';
			}else{
				$panpwd = "解压密码：<font color=red>".$panpwd."</font>";
			}
			$url = $cfg_basehost.(empty($cfg_cmspath) ? '/' : $cfg_cmspath).$cfg_cmsurl."/i/?url=my_work&type=dows";
			$url = preg_replace("#http:\/\/#i", '', $url);
			$url = 'http://'.preg_replace("#\/\/#i", '/', $url);
			$logo = $cfg_basehost.(empty($cfg_cmspath) ? '/' : $cfg_cmspath)."/skin/1.0/images/logo.png";
			$logo = preg_replace("#http:\/\/#i", '', $logo);
			$logo = 'http://'.preg_replace("#\/\/#", '/', $logo);
			$times = time();
			$time = date('Y-m-d H:i:s',$times);
			$email_title = "您在{$cfg_webname}下载了 {$arctitle}";
			$email_body = "<table width=\"800\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#FBF8F1\" style=\"border-radius:5px; overflow:hidden; border-top:4px solid #00c3b6; border-right:1px solid #dbd1ce; border-bottom:1px solid #dbd1ce; border-left:1px solid #dbd1ce;\">
				<tbody>
				  <tr>
					<td><table width=\"800\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" height=\"48\">
						<tbody>
						  <tr>
							<td width=\"74\" height=\"26\" border=\"0\" align=\"center\" valign=\"middle\" style=\"padding-left:20px;\">
							<a href=\"{$cfg_basehost}/\" target=\"_blank\" ><img style=\"vertical-align:middle;\" src=\"{$logo}\" height=\"35\" border=\"0\"></a></td>
							<td width=\"703\" height=\"48\" colspan=\"2\" align=\"right\" valign=\"middle\" style=\"color:#ffffff; padding-right:20px;\">
							<a href=\"{$cfg_basehost}/\" target=\"_blank\" style=\"color:#666;text-decoration:none;font-size:12px;margin-left:30px;\">首页</a>
					<a href=\"{$cfg_basehost}/moban/\" target=\"_blank\" style=\"color:#666;text-decoration:none;font-size:12px;margin-left:30px;\">网站素材</a>
					<a href=\"{$cfg_basehost}/plug-in/\" target=\"_blank\" style=\"color:#666;text-decoration:none;font-size:12px;margin-left:30px;\">CMS插件</a>
					<a href=\"{$cfg_basehost}/activity/\" target=\"_blank\" style=\"color:#666;text-decoration:none;font-size:12px;margin-left:30px;\">官方活动</a>
					<a href=\"{$cfg_basehost}/self-help/\" target=\"_blank\" style=\"color:#666;text-decoration:none;font-size:12px;margin-left:30px;\">自助服务</a>
							</td>
						  </tr>
						</tbody>
					  </table></td>
				  </tr>
				  <tr>
			<td><div style=\"padding:10px 20px;font-size:14px;color:#333333;border-top:1px solid #dbd1ce;\"><p>尊敬的 {$cfg_ml->fields['uname']} 您好。</p>
		<p>您于 {$time} 在{$cfg_webname}下载了{$arctitle}，{$dneedMoney}，{$panpwd} 感谢您对我们的支持！</p>
		<p>点此查看详情：<a href=\"{$url}\" target=\"_blank\">{$url}</a></p>
		<p>如果以上链接无法访问，请将该网址复制并粘贴至新的浏览器窗口中。</p>
		<p>有疑问您可联系客服QQ：{$cfg_zxkf} </p>
		<p style=\"padding:10px 0;margin-top:30px;margin-bottom:0;color:#a8979a;font-size:12px;border-top:1px dashed #dbd1ce;\">此为系统邮件，请勿回复！</p></div></td></tr>
			</tbody>
		  </table>";
		require_once(dirname(__FILE__)."/../".$cfg_phpurl."/send_email.php");
		}
	}
	
	//更新用户下载信息（站窝窝网络扩展）
	$row = $dsql->GetOne("SELECT * FROM #@__member WHERE mid='".$cfg_ml->M_ID."'");
	$face = $row['face'];
	$user_type = $row['rank'];
	if($user_type == 10)
	{
		$user_dj = "普通会员";
	}else if($user_type == 100){
		$user_dj = "高级会员";
	}
	$litpic = $litpic;
	$dsql->ExecuteNoneQuery("insert into #@__member_dows (aid,userid,uname,money,addtime,mid,title,user_face,litpic,user_dj) values ('$aid','".$cfg_ml->M_LoginID."','".$cfg_ml->M_UserName."','$needMoney','".time()."','".$cfg_ml->M_ID."','$arctitle','$face','$litpic','$user_dj')");
				
	$dows = $dsql->ExecuteNoneQuery2("UPDATE `#@__archives` SET dows=dows+1 WHERE id='$aid' ");
	
	//处理需要下载权限的素材
    if($needRank>0 || $needMoney>0)
    {

        $arclink = $arcurl;
        $arctitle = $title;
        $arcLinktitle = "<a href=\"{$arcurl}\"><u>".$arctitle."</u></a>";
        $pubdate = GetDateTimeMk($pubdate);
    
        //会员级别不足
        if(($needRank>1 && $cfg_ml->M_Rank < $needRank && $mid != $cfg_ml->M_ID))
        {
            $dsql->Execute('me' , "SELECT * FROM `#@__arcrank` ");
            while($row = $dsql->GetObject('me'))
            {
                $memberTypes[$row->rank] = $row->membername;
            }
			$errtype = 2;
            $memberTypes[0] = "游客";
            $msgtitle = "你没有权限下载素材：{$arctitle}！";
            $moremsg = "这个素材需要 <font color='red'>".$memberTypes[$needRank]."</font> 才能下载，你目前是：<font color='red'>".$memberTypes[$cfg_ml->M_Rank]."</font> ！";
            include_once(DEDETEMPLATE."/{$cfg_df_style}/dow-list-msg.htm");
            exit();
        }

        //以下为正常情况，自动扣点数
        //如果文章需要金币，检查用户是否浏览过本文档
        if($needMoney > 0  && $mid != $cfg_ml->M_ID)
        {
            $sql = "SELECT aid,money FROM `#@__member_operation` WHERE buyid='ARCHIVE".$id."' AND mid='".$cfg_ml->M_ID."'";
            $row = $dsql->GetOne($sql);
            //未购买过此文章
            if( !is_array($row) )
            {
                //没有足够的金币
                if( $needMoney > $cfg_ml->M_Money || $cfg_ml->M_Money=='')
                {
                    $errtype = 3;
					$msgtitle = "你没有权限下载素材：{$arctitle}！";
                    $moremsg = "这个素材需要 <font color='red'>".$needMoney." 金币</font> 才能下载，你目前拥有金币：<font color='red'>".$cfg_ml->M_Money." 个</font> ！";
                    include_once(DEDETEMPLATE."/{$cfg_df_style}/dow-list-msg.htm");
                    exit(0);
                }
				
				//有足够金币，记录用户信息
                $inquery = "INSERT INTO `#@__member_operation`(arcid,mid,oldinfo,money,mtime,buyid,product,pname,sta)
                  VALUES ('$aid','".$cfg_ml->M_ID."','$arctitle','-".$needMoney."','".time()."', 'ARCHIVE".$id."', 'archive','下载素材', 2); ";
                //记录定单
                if( !$dsql->ExecuteNoneQuery($inquery) )
                {
                    ShowMsg('记录定单失败, 请返回', '-1');
                    exit(0);
                }
				else
				{
					//扣除金币
                	$dsql->ExecuteNoneQuery("UPDATE `#@__member` SET money = money - $needMoney WHERE mid='".$cfg_ml->M_ID."'");
				}
            }
        }
    }
    //更新下载次数
    $hash = md5($softUrl);
    $rs = $dsql->ExecuteNoneQuery2("UPDATE `#@__downloads` SET downloads = downloads+1 WHERE hash='$hash' ");
    if($rs <= 0)
    {
        $query = " INSERT INTO `#@__downloads`(`hash`, `id`, `downloads`) VALUES('$hash', '$id', 1); ";
        $dsql->ExecNoneQuery($query);
    }
    header("location:{$softUrl}");
    exit();
}//opentype=2