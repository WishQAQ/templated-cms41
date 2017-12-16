// JavaScript Document

var cmsurl = $("#cmsurl").val();
var phpurl = $("#phpurl").val();
var aid = $("#aid").val();
var memberurl = $("#memberurl").val();

function LoadCommets(page)
{
	var taget_obj = document.getElementById('commetcontent');
	var waithtml = "<div class='loding-cont'><img src='"+cmsurl+"/images/loadinglit.gif' /><p>评论加载中...</p></div>";
	var myajax = new DedeAjax(taget_obj, true, true, '', 'x', waithtml);
	myajax.SendGet2(phpurl+"/feedback_ajax.php?dopost=getlist&aid="+aid+"&page="+page);
	DedeXHTTP = null;
	$.ajax({type: 'POST',url: phpurl+"/feedback_ajax.php",
		data: "dopost=nuus&aid="+aid,
		dataType: 'html',
		success: function(result){
			$("#contnuus").html(result);
		}
	});
}
function PostComment()
{
	var msg = $("#msg").val();
	if(msg=='')
	{
		alert("评论内容不能为空！");
		$("#msg").focus();
		return;
	}
	if(msg.length > 500)
	{
			alert("你的评论是不是太长了？请填写500字以内的评论。");
			$("#msg").focus();
			return;
	}
	$("#msg").val('');
	$.ajax({type: 'POST',url: phpurl+"/feedback_ajax.php",
		data: "dopost=send&comtype=comments&fid=0&msg="+msg+"&aid="+aid,
		dataType: 'html',
		success: function(result){
			$("#msg_err").html(result);
			$("#conterr").css("display","block");
		}
	});
}
function shuaxin()
{
	$("#conterr").html("");
	$("#conterr").css("display","none");
	return;
}

LoadCommets(1);