<?php 
/**
 * 我的操作记录
 * 
 */
require_once(dirname(__FILE__)."/config.php");
require_once(DEDEINC."/datalistcp.class.php");
CheckRank(0,0);
$menutype = 'tab-bindsns';

$typemsg = "<p class='tip'>提示：在站窝窝模板网使用过程中有疑问，请选择联系方式跟我们取得联系。</p>";

$dlist = new DataListCP();
$dlist->SetTemplate(DEDEMEMBER."/templets/kefu.htm");    
$dlist->SetSource($sql);
$dlist->Display(); 
