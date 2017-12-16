<div class="aside"> 
    <div class="user-avatar"> 
       <a href="<?php echo $cfg_cmsurl; ?>/i/?type=editface"><img src="<?php echo $cfg_ml->fields['face']; ?>" class="avatar" alt="<?php echo $cfg_ml->M_UserName; ?>" width="100" height="100" /></a> 
      <h2><?php echo $cfg_ml->M_UserName; ?></h2> 
    </div> 
    <div class="menus"> 
     <ul> 
        <li class="tab-wbi <?php if($menutype=='tab-index') echo 'active';?>"><a href="<?php echo $cfg_cmsurl; ?>/i/" style="border-bottom: 1px solid #E5E5E5;"><i class="fa fa-home"></i>首页中心</a></li>
        <li class="tab-wbi <?php if($type=='payopp') echo 'active';?>"><a href="<?php echo $cfg_cmsurl; ?>/i/?url=my_work&type=payopp"><i class="fa fa-diamond"></i>消费明细</a></li>
        <li class="tab-index"><a href="<?php echo $cfg_cmsurl; ?>/i/?type=buy"><i class="fa fa-shopping-cart"></i>充值金币</a></li>
        <!--li class="tab-works <?php if($menutype=='tab-works') echo 'active';?>"><a href="javascript:alert('开发中');"><i class="fa fa-cube"></i>文章作品</a></li-->
        <li class="tab-stows <?php if($type=='stow') echo 'active';?>"><a href="<?php echo $cfg_cmsurl; ?>/i/?url=my_work&type=stow"><i class="fa fa-shopping-cart_stows"></i>收藏管理</a></li>
        <!--li class="tab-likes <?php if($type=='likes') echo 'active';?>"><a href="<?php echo $cfg_cmsurl; ?>/i/?url=my_work&type=likes"><i class="fa fa-heart"></i>我的喜欢</a></li-->
        <li class="tab-dows <?php if($type=='dows') echo 'active';?>"><a href="<?php echo $cfg_cmsurl; ?>/i/?url=my_work&type=dows"><i class="fa fa fa-dows"></i>下载管理</a></li>
        <li class="tab-comment <?php if($type=='comment') echo 'active';?>"><a href="<?php echo $cfg_cmsurl; ?>/i/?url=my_work&type=comment"><i class="fa fa-comments"></i>我的评论</a></li>
        <!--li class="tab-newWorks <?php if($menutype=='tab-newWorks') echo 'active';?>"><a href="javascript:alert('开发中');"><i class="fa fa-pencil-square-o"></i>发布文章</a></li>
        <li class="tab-newPhoto <?php if($menutype=='tab-newPhoto') echo 'active';?>"><a href="javascript:alert('开发中');"><i class="fa fa-upload"></i>上传模板</a></li>
        <li class="tab-order <?php if($menutype=='tab-order') echo 'active';?>"><a href="javascript:alert('开发中');"><i class="fa fa-shopping-cart"></i>订单管理</a></li-->
        <li class="tab-profile <?php if($menutype=='tab-profile') echo 'active';?>"><a href="<?php echo $cfg_cmsurl; ?>/i/?type=editinfo"><i class="fa fa-cog"></i>编辑资料</a></li>
        <li class="tab-avatar <?php if($menutype=='tab-avatar') echo 'active';?>"><a href="<?php echo $cfg_cmsurl; ?>/i/?type=editface"><i class="fa fa-user-secret"></i>修改头像</a></li>
        <li class="tab-security <?php if($menutype=='tab-security') echo 'active';?>"><a href="<?php echo $cfg_cmsurl; ?>/i/?type=editpwd"><i class="fa fa-key"></i>修改密码</a></li>
        <li class="tab-bindsns <?php if($menutype=='tab-shouhou') echo 'active';?>"><a href="<?php echo $cfg_cmsurl; ?>/i/?url=my_work&type=my_shouhou"><i class="fa fa-bug"></i>模板售后</a></li>
        <li class="tab-bindsns <?php if($menutype=='tab-bindsns') echo 'active';?>"><a href="<?php echo $cfg_cmsurl; ?>/i/?url=my_work&type=kefu"><i class="fa fa-phone"></i>客服中心</a></li>
    </ul> 
  </div> 
</div>