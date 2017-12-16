<?php
/**
 	用户评论AJAX登录后显示
 */
require_once(dirname(__FILE__).'/../qm_user/config.php');
AjaxHead();
$uid  = $cfg_ml->M_LoginID;
$face = $cfg_ml->fields['face'] == '' ? $GLOBALS['cfg_memberurl'].'/images/nopic.gif' : $cfg_ml->fields['face'];
?>
<div class="comment-from-main">
<div id="avatar"><img src="<?php echo $face;?>" class="avatar" alt="<?php echo $cfg_ml->M_UserName;?>" width="44" height="44" /></div>
<div class="comment-form-box">
<textarea rows="8" cols="100%" name="msg" id="msg" placeholder="说点什么吧..."></textarea>
</div>
</div>
<p class="logged-in-as">Hello<a href="<?php echo $cfg_cmsurl;?>/i/?uid=<?php echo $uid;?>" target="_blank"> <?php echo $cfg_ml->M_UserName;?> </a>！<a onclick="return Doexit();" title="退出当前帐号">退出</a></p>
<p class="form-submit"><input name="submit" type="button" id="submit" class="submit" value="我要评论" onclick="PostComment()" /></p>

