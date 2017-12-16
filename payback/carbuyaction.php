<?php

require_once (dirname(__FILE__) . "/../include/common.inc.php");
require_once DEDEINC.'/memberlogin.class.php';

if($cfg_mb_open == 'N')
{
    ShowMsg("系统关闭了会员功能，因此你无法访问此页面！","$cfg_basehost",0,8);
    exit();
}
$cfg_ml = new MemberLogin();

if($dopost=='return')
{
	if($code=='alipay')
	{
		$row = $dsql->GetOne("SELECT * FROM #@__member_operation WHERE buyid = '$out_trade_no'");
		$money = $row['money'];
		$mid_ok = $row['mid'];
		
		if($cfg_ml->M_ID != $mid_ok)
		{
			ShowMsg("充值失败，充值的账户和当前登录账户不一致", "$cfg_basehost", 0,8);
			exit();
		}
		else if($row['sta']==2)
		{
			ShowMsg("您的订单已经处理，请不要重复提交!", "$cfg_basehost", 0,8);
			exit();
		}
		else if($total_fee==$money)
		{    
			$money_ok = $money;
			$dsql->ExecuteNoneQuery("UPDATE `#@__member_operation` SET sta = 2, money = '+".$money_ok."' WHERE buyid='$out_trade_no'");
			$dsql->ExecuteNoneQuery("UPDATE `#@__member` SET money = money + '$money_ok' WHERE `mid`='$mid_ok'");
			ShowMsg("成功充值了".$money_ok."金币到您的账户！", "".$cfg_cmsurl."/i/?url=my_work&type=payopp", 0,8);
			exit();
		}
	}
}
else
{
	ShowMsg("数据错误", "$cfg_basehost", 0,8);
}

?>