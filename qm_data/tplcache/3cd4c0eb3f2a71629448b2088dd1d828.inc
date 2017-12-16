<li class="group"> 
        <div class="item"> 
         <div class="thumb"> 
          <a href="[field:arcurl/]" title="[field:title/]"> <img width="210" height="135" alt="[field:title/]" src="[field:picname/]" /> </a> 
         </div> 
         <div class="item-main"> 
          <div class="title">
           <h2><a href="[field:arcurl/]" rel="bookmark" title="[field:title/]">[field:title/]</a></h2>
          </div> 
          <div class="meta"> 
           <div class="excerpt">
            [field:description function="cn_substr(@me,240)"/]...
           </div> 
           <div class="data"> 
            <time class="time"> <i class="fa fa-calendar"></i> [field:mid function='uname(@me)'/] 发布于 [field:pubdate function="FloorTime(@me)"/]
             </time> 
            <span class="heart-num"><i class="fa fa-heart"></i>[field:likes/]</span>
            <span><i class="fa fa-fire"></i>[field:click/]</span> 
            <span class="comment-num"> <a href="[field:arcurl/]#respond" class="comments-link"><i class="fa fa-comment"></i>[field:pinglun/]</a> </span> 
           </div> 
           <div class="author">
            <a [field:mid function='userurl(@me)'/] title=" [field:mid function='uname(@me)'/]的主页" rel="author"> <img src="[field:mid function='face(@me)'/]" class="avatar" alt="[field:writer/]" width="40" height="40" /> </a> 
           </div> 
          </div> 
         </div> 
        </div> </li>