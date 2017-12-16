		// JavaScript Document

		var curPage = 1; //当前页码
        var total,pageSize,totalPage;
		
        //获取数据
        function getData(page){ 
            $.ajax({
                type: 'POST',
                url: '/404/arclist.php',
				data: {'pageNum':page-1},
                dataType:'json',
                beforeSend:function(){
                    $("#newlist").append("<div id='loading'><img src='/images/loadinglit.gif' /><br>加载中...</div>");
                },
                success:function(json){
                    $("#newlist").empty();
                    total = json.total; //总记录数
                    pageSize = json.pageSize; //每页显示条数
                    curPage = page; //当前页
                    totalPage = json.totalPage; //总页数
                    var li = "";
                    var list = json.list;
                    $.each(list,function(index,array){ //遍历json数据列
						li += '<li class="group"><div class="item"><div class="thumb">';
						li += "<a href='"+array['arcurl']+"' target='_blank' title='"+array['title']+"'><img width='280' height='180' alt='"+array['title']+"' src='"+array['litpic']+"' /></a></div>";
						li += '<div class="meta"><div class="title">';
						li += "<h2><a href='"+array['arcurl']+"' target='_blank' rel='bookmark' title='[field:title/]'>"+array['title']+"</a></h2></div>";
						li += '<div class="extra"><i class="fa fa-bookmark"></i>';
						li += "<a href='"+array['typeurl']+"' target='_blank' rel='category tag'>"+array['typename']+"</a><span>"+array['click']+"<i class='fa fa-fire'></i></span></div></div>";
						li += '<div class="data"><time class="time"><i class="fa fa-date"></i>'+array['pubdate']+'</time>';
						li += "<span class='comment-num' title='"+array['dows']+"人下载'><i class='fa fa-dows'></i>"+array['dows']+"</span>";
						li += "<span class='heart-num' title='"+array['stows']+"人收藏'><i class='fa fa-shopping-cart_stows'></i>"+array['stows']+"</span>";
						li += '</div></div></li>';
                    });
                    $("#newlist").append(li);
                },
                complete:function(){ //生成分页条
                    getPageBar();
                },
                error:function(){
                    alert("数据加载失败");
                }
            });
        }
        //获取分页条
        function getPageBar(){
            //页码大于最大页数
            if(curPage>totalPage) curPage=totalPage;
            //页码小于1
            if(curPage<1) curPage=1;
            //pageStr = "<a>共"+total+"条"+curPage+" / "+totalPage+"</a>";
			pageStr = "";
            var pagehtml = "换一批 <i class='fa fa-chevron-circle-right'></i>";
            //如果是第一页
            if(curPage==1){
                //pageStr += "<span>首页</span><span>上一页</span>";
				pageStr += "";
            }else{
                pageStr += "";
            }
            
            //如果是最后页
            if(curPage>=totalPage){
                //pageStr += "<span>下一页</span><span>尾页</span>";
            }else{
                pageStr += "<a href='javascript:void(0)' rel='"+(parseInt(curPage)+1)+"'>"+pagehtml+"</a>";
            }
                
            $("#pagecount").html(pageStr);
        }
        $(function(){
            getData(1);
            $("#pagecount a").live('click',function(){
                var rel = $(this).attr("rel");
                if(rel){
                    getData(rel);
                }
	});
});