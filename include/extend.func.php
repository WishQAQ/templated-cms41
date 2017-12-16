<?php
function litimgurls($imgid=0)
{
    global $lit_imglist,$dsql;
    //获取附加表
    $row = $dsql->GetOne("SELECT c.addtable FROM #@__archives AS a LEFT JOIN #@__channeltype AS c 
                                                            ON a.channel=c.id where a.id='$imgid'");
    $addtable = trim($row['addtable']);
    
    //获取图片附加表imgurls字段内容进行处理
    $row = $dsql->GetOne("Select imgurls From `$addtable` where aid='$imgid'");
    
    //调用inc_channel_unit.php中ChannelUnit类
    $ChannelUnit = new ChannelUnit(2,$imgid);
    
    //调用ChannelUnit类中GetlitImgLinks方法处理缩略图
    $lit_imglist = $ChannelUnit->GetlitImgLinks($row['imgurls']);
    
    //返回结果
    return $lit_imglist;
}

/*织织网工作室（www.wwwcms.net）字符过滤函数*/
function wwwcms_filter($str,$stype="inject") {
	if ($stype=="inject")  {
		$str = str_replace(
		       array( "select", "insert", "update", "delete", "alter", "cas", "union", "into", "load_file", "outfile", "create", "join", "where", "like", "drop", "modify", "rename", "'", "/*", "*", "../", "./"),
			   array("","","","","","","","","","","","","","","","","","","","","",""),
			   $str);
	} else if ($stype=="xss") {
		$farr = array("/\s+/" ,
		              "/<(\/?)(script|META|STYLE|HTML|HEAD|BODY|STYLE |i?frame|b|strong|style|html|img|P|o:p|iframe|u|em|strike|BR|div|a|TABLE|TBODY|object|tr|td|st1:chsdate|FONT|span|MARQUEE|body|title|\r\n|link|meta|\?|\%)([^>]*?)>/isU", 
					  "/(<[^>]*)on[a-zA-Z]+\s*=([^>]*>)/isU",
					  );
		$tarr = array(" ",
		              "",
					  "\\1\\2",
					  ); 
		$str = preg_replace($farr, $tarr, $str);
		$str = str_replace(
		       array( "<", ">", "'", "\"", ";", "/*", "*", "../", "./"),
			   array("&lt;","&gt;","","","","","","",""),
			   $str);
	}
	return $str;
}


/**
 *  自定义字段筛选
 *
 * @access    public
 * @param     string  $fieldset  字段列表
 * @param     string  $loadtype  载入类型
 * @return    string
 */
 
function AddFilter($channelid, $type=1, $fieldsnamef, $defaulttid, $loadtype='autofield')
{
	global $tid,$dsql,$id;
	$tid = $defaulttid ? $defaulttid : $tid;
	if ($id!="")
	{
		$tidsq = $dsql->GetOne(" Select typeid From `#@__archives` where id='$id' ");
		$tid = $tidsq["typeid"];
	}
	$nofilter = (isset($_REQUEST['TotalResult']) ? "&TotalResult=".$_REQUEST['TotalResult'] : '').(isset($_REQUEST['PageNo']) ? "&PageNo=".$_REQUEST['PageNo'] : '');
	$filterarr = wwwcms_filter(stripos($_SERVER['REQUEST_URI'], "list.php?tid=") ? str_replace($nofilter, '', $_SERVER['REQUEST_URI']) : $GLOBALS['cfg_cmsurl']."/plus/list.php?tid=".$tid);
    $cInfos = $dsql->GetOne(" Select * From  `#@__channeltype` where id='$channelid' ");
	$fieldset=$cInfos['fieldset'];
	$dtp = new DedeTagParse();
    $dtp->SetNameSpace('field','<','>');
    $dtp->LoadSource($fieldset);
    $dede_addonfields = '';
    if(is_array($dtp->CTags))
    {
        foreach($dtp->CTags as $tida=>$ctag)
        {
            $fieldsname = $fieldsnamef ? explode(",", $fieldsnamef) : explode(",", $ctag->GetName());
			if(($loadtype!='autofield' || ($loadtype=='autofield' && $ctag->GetAtt('autofield')==1)) && in_array($ctag->GetName(), $fieldsname) )
            {
                $href1 = explode($ctag->GetName().'=', $filterarr);
				$href2 = explode('&', $href1[1]);
				$fields_value = $href2[0];
				switch ($type) {
					case 1:
						$dede_addonfields .= (preg_match("/&".$ctag->GetName()."=/is",$filterarr,$regm) ? '<li><a href="'.str_replace("&".$ctag->GetName()."=".$fields_value,"",$filterarr).'">全部</a></li>' : '<li class="current-cat"><a>全部</a></li>');
					
						$addonfields_items = explode(",",$ctag->GetAtt('default'));
						for ($i=0; $i<count($addonfields_items); $i++)
						{
							$href = stripos($filterarr,$ctag->GetName().'=') ? str_replace("=".$fields_value,"=".urlencode($addonfields_items[$i]),$filterarr) : $filterarr.'&'.$ctag->GetName().'='.urlencode($addonfields_items[$i]);//echo $href;
							$dede_addonfields .= ($fields_value!=urlencode($addonfields_items[$i]) ? '<li><a title="'.$addonfields_items[$i].'" href="'.$href.'">'.$addonfields_items[$i].'</a></li>' : '<li class="current-cat"><a>'.$addonfields_items[$i].'</a></li>');
						}
					break;
				}
            }
        }
    }
	echo $dede_addonfields;
}
//列表页调用会员头像
function face($mid)
{
global $dsql;
if($mid == 1){
	$face = '/images/admin_pic.png';
	return $face;
}
if($mid <> 0){
$row = $dsql->GetOne("select * from #@__member where mid = '$mid'");
if($row['face'] == ''){
$face = '/images/noface.gif';
}else{
$face = $row['face'];
}
}
return $face;
}
//列表页调用会员昵称
function uname($mid)
{
global $dsql;
if($mid <> 0){
$row = $dsql->GetOne("select * from #@__member where mid = '$mid'");
if($mid == '1'){
	$uname = "管理员";	
}else{
	$uname = $row['uname'];
}
}
return $uname;
}

//列表页调用会员空间链接
function userurl($mid)
{
global $dsql;
if($mid <> 0){
$row = $dsql->GetOne("select * from #@__member where mid = '$mid'");
$userid = $row['userid'];
if($mid == '1'){
	$userurl = "target='_blank' href='/i/?uid=$userid'";
}
}
return $userurl;
}

// 获取图集图片[新增的功能]
function Getimgs($aid, $imgwith = 400, $imgheight = 300, $num = 0){ 
global $cfg_basedir;
global $dsql; 
$imgurls = ''; 
$row = $dsql -> getone("Select imgurls From `#@__addonstore` where aid='$aid'"); 
$imgurls = $row['imgurls']; 
preg_match_all("/{dede:img (.*)}(.*){\/dede:img/isU", $imgurls, $wordcount); 
$count = count($wordcount[2]); 
if ($num > $count || $num == 0){ 
$num = $count; 
} 
for($i = 0;$i < $num;$i++){ 
$imglist .= "" . trim($wordcount[2][$i]) . ""; 
} 
if($num > 0){
	return $imglist;
}else{
 $row = $dsql -> getone("Select litpic From `#@__archives` where id='$aid'");
 if($row['litpic'] == ''){
    $row['litpic'] = $cfg_cmsurl.'/demo/plus/images/no-demopic.png';
 }
 return  $row['litpic'];
} 
}

function GetTotalArc($tid)   
{       
global $dsql;       
$sql = GetSonIds($tid);       
$row = $dsql->GetOne("Select count(id) as dd From `#@__archives` where typeid in({$sql})"); 
return $row['dd']; }

//搜索结果页调用自定义字段
function Search_addfields($id,$result){
global $dsql;
$row4 = $dsql->GetOne("SELECT * FROM `#@__addonstore` where aid='$id'");
//dede_addonveryhuo 请修改为您自己的表名称
$name=$row4[$result];
return $name;
}