/**
*	异步通信处理
*/
var anayrun = {
	init:function(){
		
	},
	send:function(url,cans){
		var lx = (url.indexOf('?')<0) ? '?' : '&';
		url+=''+lx+'rnd='+js.getrand()+'';
		if(!cans)cans={};
		for(var i in cans)url+='&'+i+'='+cans[i]+'';
		$.ajax({
			url:url,
			type:'get',
			success:function(str){
				if(str!='success'){
					anayrun.senderror(str, url);
				}
			},
			error:function(e){
				anayrun.senderror(e.responseText, url);
			}
		});
	},
	senderror:function(str, url){
		notifyobj.show({
			title:'JS异步处理',
			type:'error',
			body:'地址：'+url+'，处理出错：'+str+''
		});
	}
};