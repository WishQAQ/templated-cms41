<?php
/**
 	用户主页列表
 */
require_once(dirname(__FILE__)."/../include/common.inc.php");

$row = $dsql->GetOne("SELECT  * FROM `#@__member` WHERE userid='$uid'");
$qianming = $row['qianming'];
if($qianming == ''){
	$qianming = "这家伙很懒，没有签名！";
}
if($row['zlbm'] == 'N' || $row['zlbm'] == '0' || $row['zlbm'] == '')
{
	$user_qq = $row['user_qq'];
	if($user_qq == ''){
		$user_qq = "";
	}else{
		$user_qq = "<span><i class='fa fa-qq'></i>$user_qq</span>";
	}
	$sin_web = $row['sin_web'];
	if($sin_web == ''){
		$sin_web = "";
	}else{
		$sin_web = "<span><a target='_blank' href='$sin_web'><i class='fa fa-weibo'></i>$sin_web</a></span>";
	}
	$ten_web = $row['ten_web'];
	if($ten_web == ''){
		$ten_web = "";
	}else{
		$ten_web = "<span><a target='_blank' href='$ten_web'><i class='fa fa-tencent-weibo'></i>$ten_web</a></span>";
	}
$user_zl = "<li class='user-description'> $qianming </li><li class='user-meta'> ".$user_qq."".$sin_web."".$ten_web."<span><i class='fa fa-calendar-o'></i>".date('Y-m-d H:i:s',$row['jointime'])."</span> </li> ";
}
?>
document.getElementById("index_space").innerHTML="<?php echo $user_zl; ?>";