<?php 
/**
*	桌面首页项(新闻资讯)
*/
defined('HOST') or die ('not access');

?>
<script>
morenewss=function(){
	addtabs({num:'news',url:'flow,page,news,atype=my',icons:'volume-up',name:'新闻资讯'});
}
homeobject.show_news_list=function(a){
	var s='',a1,i,typa = a.typearr,act,c1;
	typa.unshift({'name':'新闻'});
	for(i=0;i<typa.length;i++){
		act='';
		c1 = (i==0)?'0':'1';
		if(i==0)act='active';
		s+='<li tab="'+i+'" onclick="homeobject.clicknewstabs('+i+',\''+typa[i].name+'\')" class="'+act+'"><a style="TEXT-DECORATION:none; border-top:0; border-left-width:'+c1+'px;border-radius:0">'+typa[i].name+'</a></li>';
	}
	$('#newstabs_{rand}').html(s);
	this.newsrows	 = [];
	this.newsrows[0] = a.rows;
	this.shownewsss(a.rows);
}
homeobject.clicknewstabs=function(oi,na){
	if(get('losnew{rand}'))return;
	var o1 = $('#newstabs_{rand}');
	o1.find('li').removeClass('active');
	o1.find('li[tab="'+oi+'"]').addClass('active');
	var das = this.newsrows[oi];
	if(typeof(das)=='undefined'){
		$('#newstabs_{rand}').after('<div id="losnew{rand}" style="margin:10px" align="center"><img src="images/mloading.gif"></div>');
		js.ajax(publicmodeurl('news','getnews'),{typename:na},function(ret){
			homeobject.newsrows[oi]=ret;
			homeobject.shownewsss(ret);
		},'get,json');
	}else{
		this.shownewsss(das);
	}
}
homeobject.shownewsss=function(das){
	var i,s='',len=das.length,a1,c1,c2;
	$('#homenewslist{rand} a[temp]').remove();
	$('#losnew{rand}').remove();
	for(i=0;i<len;i++){
		a1 = das[i];
		c1 = (i>0) ? '1' : '0';
		c2 = (i==len-1) ? '0' : '1';
		if(!isempt(a1.fengmian)){
			s+='<a onclick="openxiangs(\''+a1.typename+'\',\'news\',\''+a1.id+'\');" style="TEXT-DECORATION:none;border-top-width:'+c1+'px;border-bottom-width:'+c2+'px;border-right:0;border-left:0" temp="list" class="list-group-item">';
			s+='<table style="background:none"><tr>';
			s+='<td style="padding-right:10px"><div style="height:80px;display:table-cell;overflow:hidden;vertical-align:middle"><img src="'+a1.fengmian+'" width="100"></div></td>';
			s+='<td><h5 class="text-left">'+a1.title+'</h5><div><small>'+a1.smallcont+'</small></div></td>';
			s+='</tr></table>';
			s+='</a>';
		}else{
			s+='<a onclick="openxiangs(\''+a1.typename+'\',\'news\',\''+a1.id+'\');" style="TEXT-DECORATION:none;border-right:0;border-left:0;border-bottom-width:'+c2+'px;border-top-width:'+c1+'px" temp="list" class="list-group-item">◇【'+a1.typename+'】'+a1.title+'</a>';
		}
	}
	$('#homenewslist{rand}').append(s);
}
</script>

<div class="panel panel-danger">
  <div class="panel-heading">
	<h3  class="panel-title"><i class="icon-globe"></i> <?=$itemnowname?>
	<a style="float:right;TEXT-DECORATION:none" href="javascript:;" onclick="morenewss()">更多&gt;&gt;</a>
	</h3>
  </div>
  <div class="panel-body" id="homenewslist{rand}" style="padding:0px">
	
	<ul class="nav nav-tabs" id="newstabs_{rand}"></ul>


  </div>
</div>