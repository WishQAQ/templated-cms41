<?php
/**
 * 生成所有页面
 *
 * @version        $Id: makehtml_all.php 1 8:48 2010年7月13日Z tianya $
 * @package        DedeCMS.Administrator
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
require_once(dirname(__FILE__)."/config.php");
require_once(DEDEINC."/channelunit.func.php");
$action = (empty($action) ? '' : $action);

if($action=='')
{
    require_once(DEDEADMIN."/templets/makehtml_all.htm");
    exit();
}
else if($action=='make')
{
    //step = 1 更新主页、step = 2 更新内容、step = 3 更新栏目
    if(empty($step)) $step = 1;

    //更新文档前优化数据
    /*-------------------
    function _1_OptimizeData1()
    ---------------------*/
    if($step==1)
    {
        $starttime = GetMkTime($starttime);
        $mkvalue = ($uptype=='time' ? $starttime : $startid);
        OptimizeData($dsql);
        ShowMsg("完成数据优化，现在开始更新文档！","makehtml_all.php?action=make&step=2&uptype=$uptype&mkvalue=$mkvalue");
        exit();
    }
    //更新文档
    /*-------------------
    function _2_MakeArchives()
    ---------------------*/
    else if($step==2)
    {
        include_once(DEDEADMIN."/makehtml_archives_action.php");
        exit();
    }
    //更新主页
    /*-------------------------
    function _3_MakeHomePage()
    -------------------*/
    if($step==3)
    {
        include_once(DEDEINC."/arc.partview.class.php");
        $pv = new PartView();
        $row = $pv->dsql->GetOne("SELECT * FROM `#@__homepageset` ");
		$templet = str_replace("{style}", $cfg_df_style,$row['templet']);
		$homeFile = DEDEADMIN.'/'.$row['position'];
		$homeFile = str_replace("\\", '/', $homeFile);
		$homeFile = preg_replace("#\/{1,}#" ,'/', $homeFile);
		if($row['showmod'] == 1)
		{
			$pv->SetTemplet($cfg_basedir.$cfg_templets_dir.'/'.$templet);
			$pv->SaveToHtml($homeFile);
			$pv->Close();
		} else {
			if (file_exists($homeFile)) @unlink($homeFile);
		}
        ShowMsg("完成更新所有文档，现在开始更新模板在线演示！","makehtml_all.php?action=make&step=3.1&uptype=$uptype&mkvalue=$mkvalue&dptype=url&typeid=7");
        exit();
    }
	else if($step==3.1)
    {
		require_once(DEDEINC."/demo_url.php");
		$est1 = ExecTime();
		$startid  = (empty($startid)  ? -1  : $startid);
		$endid    = (empty($endid)    ? 0  : $endid);
		$startdd  = (empty($startdd)  ? 0  : $startdd);
		$pagesize = (empty($pagesize) ? 20 : $pagesize);
		$totalnum = (empty($totalnum) ? 0  : $totalnum);
		$typeid   = (empty($typeid)   ? 0  : $typeid);
		$seltime  = (empty($seltime)  ? 0  : $seltime);
		$stime    = (empty($stime)    ? '' : $stime );
		$etime    = (empty($etime)    ? '' : $etime);
		$sstime   = (empty($sstime)   ? 0  : $sstime); 
		$mkvalue  = (empty($mkvalue)  ? 0  : $mkvalue);
		
		$isremote  = (empty($isremote)? 0  : $isremote);
		$serviterm = empty($serviterm)? "" : $serviterm;
		
		//一键更新传递的参数
		if(!empty($uptype))
		{
			if($uptype!='time') $startid = $mkvalue;
			else $t1 = $mkvalue;
		}
		else
		{
			$uptype = '';
		}
		
		//获取条件
		$idsql = '';
		$gwhere = ($startid==-1 ? " WHERE arcrank=0 " : " WHERE id>=$startid AND arcrank=0 ");
		if($endid > $startid && $startid > 0) $gwhere .= " AND id <= $endid ";
		
		if($typeid!=0) {
			$ids = GetSonIds($typeid);
			$gwhere .= " AND typeid in($ids) ";
		}
		
		if($idsql=='') $idsql = $gwhere;
		
		if($seltime==1)
		{
			$t1 = GetMkTime($stime);
			$t2 = GetMkTime($etime);
			$idsql .= " And (senddate >= $t1 And senddate <= $t2) ";
		}
		else if(isset($t1) && is_numeric($t1))
		{
			$idsql .= " And senddate >= $t1 ";
		}
		
		//统计记录总数
		if($totalnum==0)
		{
			$row = $dsql->GetOne("SELECT COUNT(*) AS dd FROM `#@__arctiny` $idsql");
			$totalnum = $row['dd'];
			//清空缓存
			$dsql->ExecuteNoneQuery("DELETE FROM `#@__arccache` ");
		}
		
		//获取记录，并生成HTML
		if($totalnum > $startdd+$pagesize) 
		{
			$limitSql = " LIMIT $startdd,$pagesize";
		}
		else {
			$limitSql = " LIMIT $startdd,".($totalnum - $startdd);
		}
		
		$tjnum = $startdd;
		if(empty($sstime)) $sstime = time();
		
		//如果生成数量大于500，并且没选栏目，按栏目排序生成
		if($totalnum > 500 && empty($typeid)) 
		{
			$dsql->Execute('out',"SELECT id FROM `#@__arctiny` $idsql ORDER BY typeid ASC $limitSql");
		} else {
			$dsql->Execute('out',"SELECT id FROM `#@__arctiny` $idsql $limitSql");
		}
		if($cfg_remote_site=='Y' && $isremote=="1")
		{    
			if($serviterm!="")
			{
				list($servurl, $servuser, $servpwd) = explode(',', $serviterm);
				$config = array( 'hostname' => $servurl, 'username' => $servuser, 
								 'password' => $servpwd,'debug' => 'TRUE');
			} else {
				$config=array();
			}
			if(!$ftp->connect($config)) exit('Error:None FTP Connection!');
		}
		
		while($row=$dsql->GetObject('out'))
		{
			$tjnum++;
			$id = $row->id;
			$ac = new Archives($id);
			$rurl = $ac->MakeHtml($isremote);
		}
		
		$t2 = ExecTime();
		$t2 = ($t2 - $est1);
		$ttime = time() - $sstime;
		$ttime = number_format(($ttime / 60),2);
		ShowMsg("完成更新所有文档，现在开始更新模板大图演示！","makehtml_all.php?action=make&step=3.2&uptype=$uptype&mkvalue=$mkvalue&typeid=7");
        exit();
    }else if($step==3.2)
    {
		require_once(DEDEINC."/demo_pic.php");
		$est1 = ExecTime();
		$startid  = (empty($startid)  ? -1  : $startid);
		$endid    = (empty($endid)    ? 0  : $endid);
		$startdd  = (empty($startdd)  ? 0  : $startdd);
		$pagesize = (empty($pagesize) ? 20 : $pagesize);
		$totalnum = (empty($totalnum) ? 0  : $totalnum);
		$typeid   = (empty($typeid)   ? 0  : $typeid);
		$seltime  = (empty($seltime)  ? 0  : $seltime);
		$stime    = (empty($stime)    ? '' : $stime );
		$etime    = (empty($etime)    ? '' : $etime);
		$sstime   = (empty($sstime)   ? 0  : $sstime); 
		$mkvalue  = (empty($mkvalue)  ? 0  : $mkvalue);
		
		$isremote  = (empty($isremote)? 0  : $isremote);
		$serviterm = empty($serviterm)? "" : $serviterm;
		
		//一键更新传递的参数
		if(!empty($uptype))
		{
			if($uptype!='time') $startid = $mkvalue;
			else $t1 = $mkvalue;
		}
		else
		{
			$uptype = '';
		}
		
		//获取条件
		$idsql = '';
		$gwhere = ($startid==-1 ? " WHERE arcrank=0 " : " WHERE id>=$startid AND arcrank=0 ");
		if($endid > $startid && $startid > 0) $gwhere .= " AND id <= $endid ";
		
		if($typeid!=0) {
			$ids = GetSonIds($typeid);
			$gwhere .= " AND typeid in($ids) ";
		}
		
		if($idsql=='') $idsql = $gwhere;
		
		if($seltime==1)
		{
			$t1 = GetMkTime($stime);
			$t2 = GetMkTime($etime);
			$idsql .= " And (senddate >= $t1 And senddate <= $t2) ";
		}
		else if(isset($t1) && is_numeric($t1))
		{
			$idsql .= " And senddate >= $t1 ";
		}
		
		//统计记录总数
		if($totalnum==0)
		{
			$row = $dsql->GetOne("SELECT COUNT(*) AS dd FROM `#@__arctiny` $idsql");
			$totalnum = $row['dd'];
			//清空缓存
			$dsql->ExecuteNoneQuery("DELETE FROM `#@__arccache` ");
		}
		
		//获取记录，并生成HTML
		if($totalnum > $startdd+$pagesize) 
		{
			$limitSql = " LIMIT $startdd,$pagesize";
		}
		else {
			$limitSql = " LIMIT $startdd,".($totalnum - $startdd);
		}
		
		$tjnum = $startdd;
		if(empty($sstime)) $sstime = time();
		
		//如果生成数量大于500，并且没选栏目，按栏目排序生成
		if($totalnum > 500 && empty($typeid)) 
		{
			$dsql->Execute('out',"SELECT id FROM `#@__arctiny` $idsql ORDER BY typeid ASC $limitSql");
		} else {
			$dsql->Execute('out',"SELECT id FROM `#@__arctiny` $idsql $limitSql");
		}
		if($cfg_remote_site=='Y' && $isremote=="1")
		{    
			if($serviterm!="")
			{
				list($servurl, $servuser, $servpwd) = explode(',', $serviterm);
				$config = array( 'hostname' => $servurl, 'username' => $servuser, 
								 'password' => $servpwd,'debug' => 'TRUE');
			} else {
				$config=array();
			}
			if(!$ftp->connect($config)) exit('Error:None FTP Connection!');
		}
		
		while($row=$dsql->GetObject('out'))
		{
			$tjnum++;
			$id = $row->id;
			$ac = new Archives($id);
			$rurl = $ac->MakeHtml($isremote);
		}
		
		$t2 = ExecTime();
		$t2 = ($t2 - $est1);
		$ttime = time() - $sstime;
		$ttime = number_format(($ttime / 60),2);
        ShowMsg("完成更新所有文档，现在开始更新栏目页！","makehtml_all.php?action=make&step=4&uptype=$uptype&mkvalue=$mkvalue");
        exit();
    }
    //更新栏目
    /*-------------------
    function _4_MakeCatalog()
    --------------------*/
    else if($step==4)
    {
        $mkvalue = intval($mkvalue);
        $typeidsok = $typeids = array();
        $adminID = $cuserLogin->getUserID();
        $mkcachefile = DEDEDATA."/mkall_cache_{$adminID}.php";
        if($uptype=='all' || empty($mkvalue))
        {
            ShowMsg("不需要进行初处理，现更新所有栏目！", "makehtml_list_action.php?gotype=mkallct");
            exit();
        }
        else
        {
            if($uptype=='time')
            {
                $query = "SELECT  DISTINCT typeid From `#@__arctiny` WHERE senddate >=".GetMkTime($mkvalue)." AND arcrank>-1";
            }
            else
            {
                $query = "SELECT DISTINCT typeid From `#@__arctiny` WHERE id>=$mkvalue AND arcrank>-1";
            }
            $dsql->SetQuery($query);
            $dsql->Execute();
            while($row = $dsql->GetArray())
            {
                $typeids[$row['typeid']] = 1;
            }

            foreach($typeids as $k=>$v)
            {
                $vs = array();
                $vs = GetParentIds($k);
                if( !isset($typeidsok[$k]) )
                {
                    $typeidsok[$k] = 1;
                }
                foreach($vs as $k=>$v)
                {
                    if(!isset($typeidsok[$v]))
                    {
                        $typeidsok[$v] = 1;
                    }
                }
            }
        }
        $fp = fopen($mkcachefile,'w') or die("无法写入缓存文件：{$mkcachefile} 所以无法更新栏目！");
        if(count($typeidsok)>0)
        {
            fwrite($fp,"<"."?php\r\n");
            $i = -1;
            foreach($typeidsok as $k=>$t)
            {
                if($k!='')
                {
                    $i++;
                    fwrite($fp, "\$idArray[$i]={$k};\r\n");
                }
            }
            fwrite($fp,"?".">");
            fclose($fp);
            ShowMsg("完成栏目缓存处理，现转向更新栏目！","makehtml_list_action.php?gotype=mkall");
            exit();
        }
        else
        {
            fclose($fp);
            ShowMsg("没有可更新的栏目，现在作最后数据优化！","makehtml_all.php?action=make&step=10");
            exit();
        }
    }
    //成功状态
    /*-------------------
    function _10_MakeAllOK()
    --------------------*/
    else if($step==10)
    {
        $adminID = $cuserLogin->getUserID();
        $mkcachefile = DEDEDATA."/mkall_cache_{$adminID}.php";
        @unlink($mkcachefile);
        OptimizeData($dsql);
        ShowMsg("完成所有文件的更新！","javascript:;");
        exit();
    }//make step

} //action=='make'

/**
 *  优化数据
 *
 * @access    public
 * @param     object  $dsql  数据库对象
 * @return    void
 */
function OptimizeData($dsql)
{
    global $cfg_dbprefix;
    $tptables = array("{$cfg_dbprefix}archives","{$cfg_dbprefix}arctiny");
    $dsql->SetQuery("SELECT maintable,addtable FROM `#@__channeltype` ");
    $dsql->Execute();
    while($row = $dsql->GetObject())
    {
        $addtable = str_replace('#@__',$cfg_dbprefix,$row->addtable);
        if($addtable!='' && !in_array($addtable,$tptables)) $tptables[] = $addtable;
    }
    $tptable = '';
    foreach($tptables as $t) $tptable .= ($tptable=='' ? "`{$t}`" : ",`{$t}`" );
    $dsql->ExecuteNoneQuery(" OPTIMIZE TABLE $tptable; ");
}