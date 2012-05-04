var debugEl;
var pr_img;

function vote(c,id)
{
	var obj=newAjaxObject();
	obj.onreadystatechange=function()
	{
		if(obj.readyState==4)
		{
			$('vote'+id).innerHTML = obj.responseText;
			$('votebuttons'+id).innerHTML = "";
		}
	}
	obj.open('GET', 'vote.php?c='+c+'&i='+id, true);
	obj.send(null);
}

function switchTab(tabElement)
{
	$(tabElement).siblings('.tab-active').removeClass('tab-active').addClass('tab'); 
	$(tabElement).addClass('tab-active').removeClass('tab');
}

function initialize()
{
	pr_img = $(".pressrelease-image img")[0];
	if(pr_img != null)
	{
		var real_width;
		$("<img/>")
			.attr("src", $(pr_img).attr("src"))
			.load(function() 
			{
				real_width = this.width;
				if(real_width < 900)
				{
					$(pr_img).removeAttr("width");
				}
			});
	}
}

/*function get_filler()
{
	// Dirty hack to avoid the 'press releases' section resizing when switching tabs
	return "<div class=\"item\" style=\"visibility: hidden; height: 32px;\">placeholder</div> <div class=\"item\" style=\"visibility: hidden; height: 32px;\">placeholder</div> <div class=\"item\" style=\"visibility: hidden; height: 32px;\">placeholder</div>";
}*/

function replyToComment(element)
{
	var el = $(element).parent().parent().parent().children('.c-reply');
	var itemid = trim(el.text());
	el.html("<div class=\"c-reply-header\">^</div><form method=\"post\" action=\"/" + var_section + "/item/" + var_id + "/comments/post/" + itemid + "/\"><input type=\"text\" name=\"name\" value=\"Anonymous\" class=\"c-inline\"><textarea name=\"body\" class=\"c-inline\"></textarea><div class=\"button\"><button type=\"submit\" name=\"submit\">Post reply</button></div></form>");
	el.css({'display':'block'});
	return false;
}

function trim(value) 
{
  value = value.replace(/^\s+/,''); 
  value = value.replace(/\s+$/,'');
  return value;
}

function voteUp(id, token)
{
	$('.votebuttons'+id).load("/process.vote.php?id="+id+"&vote=up&token="+token);
	$('.votecount'+id).html(parseInt($('.votecount'+id).html()) + 1);
	return false;
}

function voteDown(id, token)
{
	$('.votebuttons'+id).load("/process.vote.php?id="+id+"&vote=down&token="+token);
	$('.votecount'+id).html(parseInt($('.votecount'+id).html()) - 1);
	return false;
}

$(function(){
	initialize();
});

