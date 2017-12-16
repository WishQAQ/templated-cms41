<?php
header("Content-Type: text/html;charset=utf-8");
require_once(dirname(__FILE__)."/../include/common.inc.php");
global $dsql;
if(isset($queryString)) {
	$queryString = $queryString;
	if(strlen($queryString) >0) {
		$dsql->SetQuery("SELECT id,title,click FROM #@__archives WHERE title LIKE '%$queryString%' and arcrank=0 order by click desc LIMIT 10");
		$dsql->Execute();
		while ($result = $dsql->GetArray()) {
			$bb=$result["title"];
			$bb=str_ireplace($queryString, '<font color=\'red\'>'.$queryString.'</font>', $bb);
			echo '<li><a class="jr" href="'.$cfg_cmsurl.'/moban/'.$result["id"].'.html" onClick="fill(\''.$result["title"].'\');" target="_blank">'.$bb.'</a></li>';
		}
	} else {
	} 
} else {
	echo '参数为空！!';
}