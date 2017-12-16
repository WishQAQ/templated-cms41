<?php
/*********************** 模板演示-小柒 *************************/
require_once(dirname(__FILE__)."/../../include/common.inc.php");

$row = $dsql->GetOne("SELECT * FROM #@__addonstore WHERE aid = $aid");
$web_url = $row['demo_url'];
if($aid != '')
{
	echo "document.getElementById('demo_web').innerHTML=\"<iframe src='".$web_url."' width='100%' height='100%' frameborder='0' id='pageFrame'></iframe>\"";
	exit;
}

?>