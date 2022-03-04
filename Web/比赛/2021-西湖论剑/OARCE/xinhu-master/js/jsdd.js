/**
*	钉钉jssdk授权
*/
js.jssdkddcall  = function(bo){
	
}
js.jssdkstate	= 0;
js.ddjsimport	= function(funs){
	var wxurl = 'https://g.alicdn.com/dingding/open-develop/1.5.1/dingtalk.js';
	if(!funs)funs=function(){};
	$.getScript(wxurl,function(){
		funs();
	});
}

//鉴权 
js.jssdkdingding = function(qxlist,afe){
	if(!afe)js.ddjsimport(function(){
		js.jssdkdingding(qxlist, true);
	});
	if(!afe)return;
	var surl= location.href;
	if(!qxlist)qxlist= ['runtime.info','device.geolocation.get','biz.util.open','biz.user.get','biz.contact.choose','biz.telephone.call','biz.ding.post'];
	var agentid=js.request('agentid');
	$.getJSON('api.php?m=login&a=ddsign&url='+jm.base64encode(surl)+'&agentid='+agentid+'',function(ret){
		ret = ret.data;//js.getarr(ret);
		if(ret.corpId==''|| !ret)return js.jssdkddcall(false);;
		js.ddcorpId = ret.corpId;
		js.ddqiyeid = ret.qiyeid;
		dd.config({
			agentId: ret.agentId,
			corpId: ret.corpId,
			timeStamp:ret.timestamp,
			nonceStr: ret.nonceStr,
			signature: ret.signature,
			jsApiList:qxlist
		});
		dd.ready(function(){
			if(js.jssdkstate==0)js.jssdkstate = 1;
			js.jssdkddcall(true);
		});
		dd.error(function(err){
			alert('dd error: ' + JSON.stringify(err));
			js.jssdkstate = 2;
			js.jssdkddcall(false);
		});
	});
}