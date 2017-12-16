<?php
require_once(dirname(__FILE__)."/../include/common.inc.php");
?>
document.write("<?php if($type=='pinglun'){ $row = $db->GetOne("select count(*) as fc from #@__feedback where aid='{$aid}'");   if(!is_array($row)){   echo "0";   }else {   echo $row['fc'];   } }else{ $row = $dsql->GetOne("select * from #@__archives where id='$aid'"); } echo $row['dows'];  ?>"); 

