<?php
if(!isset($GLOBALS['_vars'])) $GLOBALS['_vars'] = array(); 
$fields = array();
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" lang="zh-CN">
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" lang="zh-CN">
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html lang="zh-CN">
 <!--<![endif]-->
 <head> 
  <meta charset="UTF-8" /> 
  <meta name="keywords" content="" /> 
  <meta name="description" content="" /> 
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" /> 
  <meta name="renderer" content="webkit" /> 
  <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
  <meta content="yes" name="apple-mobile-web-app-capable">
  <meta content="black" name="apple-mobile-web-app-status-bar-style">
  <meta content="telephone=no" name="format-detection">
  <title>充值中心_<?php echo $cfg_webname; ?></title> 
  <!--[if lt IE 9]>
    <script src="<?php echo $cfg_cmsurl; ?>/skin/1.0/js/html5.js" type="text/javascript"></script>
  <![endif]--> 
  <link rel="stylesheet" href="<?php echo $cfg_cmsurl; ?>/skin/1.0/css/font-awesome.min.css?ver=1.0" type="text/css" media="all" />
  <link rel="stylesheet" href="<?php echo $cfg_cmsurl; ?>/skin/1.0/css/style.css?ver=1.0" type="text/css" media="all" /> 
  <script type="text/javascript" src="<?php echo $cfg_cmsurl; ?>/skin/1.0/js/jquery.min.js?ver=1.0"></script> 
  <script type="text/javascript" src="<?php echo $cfg_cmsurl; ?>/skin/1.0/js/jquery-migrate.min.js?ver=1.0"></script> 
  <script type="text/javascript" src="<?php echo $cfg_cmsurl; ?>/skin/1.0/js/m.js?ver=1.0"></script>
  <script language="javascript" type="text/javascript" src="<?php echo $cfg_cmsurl; ?>/i/ajax_main.js"></script>
  <script type="text/javascript">
  		$(function () {
			$('#buylist tr')['click'](function () {
				$('.checked')['removeClass']('checked');
				$(this)['addClass']('checked');
				var price = $(this).find("#radio #jiage").val();
				$(this).find("#radio input[name='pid']").attr("checked",true);
				$('#total-price strong').html(price);
				$("#ordermoney").val("");
			});
			$('input').bind('input propertychange', function() { 
				$('#total-price strong').html(this.value+".00");
			});
		});
  </script>
  <style type="text/css">
  #header{background-color: #323232;}
  .table-div{width:60%;margin:10px auto 50px;font-size:14px;}
  .table-div .table-list{border:1px solid #00c3b6;}
  .table-div .table-list tr th, .table-list tr td{padding:10px;border:1px solid #E0E4E4;}
  .table-div .table-list tr th{background:#f1f1f1;}
  .table-div .table-list tr.all{background:#F7F7F7;}
  .wbi #primary .wbi-charge{padding:10px 0;}
  .wbi #primary .status-tip{padding:10px 0 0;}
  </style>
 </head> 
 <body> 
  <?php pasterTempletDiy("head.htm"); ?> 
  <div id="container" class="container">
   <div class="content page wbi"> 
    <div id="primary" class="primary" role="main"> 
     <div class="area"> 
      <div class="wbi-wrapper"> 
       <article> 
        <header class="single-header"> 
        <div class="lc">
            <li class="on"><i>1</i><p>输入充值数额</p></li>
            <li><i>2</i><p>请选择支付方式</p></li>
            <li><i>3</i><p>充值成功</p></li>
      	</div>
         <div class="single-meta tl"></div> 
        </header>
        <form id="wbi-form" action="<?php echo $cfg_cmsurl; ?>/i/" method="POST"> 
        <input type="hidden" name="type" value="buy_action" />
        <input type="hidden" name="product" value="card" />
         <div class="table-div">
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="table-list">
                <tbody id="buylist">
                    <tr>
                        <th>选择</th>
                        <th>类型</th>
                        <th>实际到账金币</th>
                        <th>价格</th>
                    </tr>
                    <?php echo $moneycards?>
                    <tr>
                        <td colspan="4">
                            <div class="wbi-charge"> 
                              <div class="charge-form"> 
                               <label class="form-label" for="order_quantity">
                               <span>自定义金额：</span><input type="text" class="form-input" id="ordermoney" name="moneys" maxlength="6" style="width:180px;" /></label> 
                              </div> 
                              <div class="status-tip">请输入1-100000之间的任意整数</div> 
                           </div>
                        </td>
                      </tr>
                </tbody>
            </table>
         </div>
         <div class="wbi-footer"> 
          <div class="checkout">
           <p>注：金币用于购买<?php echo $cfg_webname; ?>平台上的虚拟商品和增值服务</p>
           <button id="pay-submit2" type="button" onclick="location='<?php echo $cfg_cmsurl;?>/i/'">返回</button>
           <button id="pay-submit" type="submit">下一步</button>
           <div id="total-price">
            应付：
            <strong>0</strong> 元
           </div>
          </div> 
         </div> 
        </form>
       </article> 
      </div> 
     </div> 
    </div> 
   </div> 
  </div>
  <?php pasterTempletDiy("footer.htm"); ?>
  <script type="text/javascript" src="<?php echo $cfg_cmsurl; ?>/skin/1.0/js/buy.js?ver=1.0"></script> 
 </body>
</html>