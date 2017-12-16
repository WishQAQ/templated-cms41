<?
//404.php
require_once(dirname(__FILE__)."/../include/common.inc.php");
require_once DEDEINC."/arc.partview.class.php";

$GLOBALS['_arclistEnv'] = '404';
$row['templet'] = MfTemplet('404.htm');
$pv = new PartView();
$pv->SetTemplet($cfg_basedir . "/404/" . $row['templet']);

$pv->Display();
exit();
