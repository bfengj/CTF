<?php 
/**
*	桌面首页显示
*/
defined('HOST') or die ('not access');
?>
<script>
var todocontent = '',homeobject={},homenums=<?=json_encode($homearrs)?>;
</script>

<div style="padding:10px">

<?php
if(in_array('kjrko',$homearrs)){
	$paths = ''.ROOT_PATH.'/'.P.'/home/desktop/items_kjrko.php';
	if(file_exists($paths))include_once($paths);
}
?>

<div align="left">
	<table style="background:none" border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr valign="top">
		
		<?php
		$bili = 100 / count($homeitems);
		echo '<td width="'.$bili.'%">';
		if(isset($homeitems[0]))foreach($homeitems[0] as $hi=>$hrs){
			$itemnowname = $hrs['name'];
			$paths = ''.ROOT_PATH.'/'.P.'/home/desktop/items_'.$hrs['num'].'.php';
			if(file_exists($paths))include_once($paths);
		}
		
		echo '</td>';
		
		for($i=1;$i<=3;$i++)if(isset($homeitems[$i])){
			echo '<td width="'.$bili.'%" style="padding-left:20px;">';
			foreach($homeitems[$i] as $hi=>$hrs){
				$itemnowname = $hrs['name'];
				$paths = ''.ROOT_PATH.'/'.P.'/home/desktop/items_'.$hrs['num'].'.php';
				if(file_exists($paths))include_once($paths);
			}
			echo '</td>';
		}
		?>		
	</tr>
	</table>	
</div>

<?php
$paths = ''.ROOT_PATH.'/'.P.'/home/desktop/footer.php';
if(file_exists($paths))include_once($paths);
?>
	
</div>

<script>
$(document).ready(function(){
	var optdt = '',loadci=0, taskarr={}, miao=200,reimtitle='REIM';
	var c= {
		itot:function(rlx){
			clearTimeout(this.tims);
			var nums = '',i;
			for(i=0;i<homenums.length;i++){
				nums+=','+homenums[i]+'';
			}
			if(!rlx)rlx='';
			var url  = js.getajaxurl('gettotal','index','home', {atype:rlx,loadci:loadci,optdt:optdt,nums:nums.substr(1)});
			$('#refresh_text').html(this.bd2('5Yi35paw57uf6K6h5LitLi4u'));
			js.ajaxbool =false;
			js.ajax(url,{},function(da){
				c.itots(da);
			},'get,json');
			homeobject.refresh=function(){c.refresh();};
		},
		init:function(){
			this.itot();
			var i,nust;
			for(i=0;i<homenums.length;i++){
				nust = homenums[i];
				if(homeobject[''+nust+'_init'])homeobject[''+nust+'_init']();
			}
		},
		refresh:function(){
			this.itot();
		},
		bd2:function(s){
			return jm.base64decode(s);
		},
		shumiao:function(oi){
			clearTimeout(this.tims);
			if(oi<=0){
				this.itot();
			}else{
				$('#refresh_text').html(this.bd2('ezB956eS5ZCO5Yi35pawJmd0OyZndDs:').replace('{0}', oi));
				this.tims = setTimeout(function(){c.shumiao(oi-1)},1000);
			}
			if(homeobject.showtime)homeobject.showtime();
		},
		auther:function(a){
			if(HOST==this.bd2('MTI3LjAuMC4x') || HOST==this.bd2('bG9jYWxob3N0'))return;
			var akey = a.authkey,usedt = a.usedt;
			if(!usedt)return '5peg5pWI6K6.6Zeu';
			if(usedt>=js.now())return;
			if(!akey)return '6K!35YWI562!5o6I57O757uf5Zyo5L2.55So77yMPGEgaHJlZj0iaHR0cDovL3d3dy5yb2Nrb2EuY29tL3ZpZXdfYXV0aGVyLmh0bWwiIHRhcmdldD0iX2JsYW5rIj7luK7liqk8L2E!';
			
		},
		itots:function(a){
			miao = a.miaoshu;
			if(a.tanwidth)js.winiframewidth=a.tanwidth;
			this.shumiao(miao);
			loadci++;
			optdt = a.optdt;
			if(loadci==1){
				jm.setJmstr(jm.base64decode(a.showkey));
				admintoken = a.token;
				if(homeobject.showicons)homeobject.showicons(a.menuarr);
				var mts = this.auther(a);
				if(mts)js.alert(this.bd2(mts));
			}
			var oi,i,nust;
			for(i=0;i<homenums.length;i++){
				nust = homenums[i];
				if(a[''+nust+'arr']){
					if(homeobject['show'+nust+'list'])homeobject['show'+nust+'list'](a[''+nust+'arr']);
					if(homeobject['show_'+nust+'_list'])homeobject['show_'+nust+'_list'](a[''+nust+'arr']);
				}
			}
			if(a.reimstotal=='0')a.reimstotal='';
			$('#reim_stotal').html(a.reimstotal+'');
			try{resizewh();}catch(e){}
			if(a.reimstotal!='' && a.notodo!='1'){
				notifyobj.show({
					icon:'images/todo.png',title:''+reimtitle+'提醒',rand:'reimto',
					body:'未读'+reimtitle+'消息('+a.reimstotal+')条',
					click:function(){
						openreim();
					}
				});
			}
			menubadge = a.total;
			showmenubadge();
			var s=a.msgar[0],s1=a.msgar[1];
			if(s!=''){
				todocontent = s;
				var tx = this.opennewtx(1);
				if(tx=='0'  && a.notodo!='1'){
					$('#tishidivshow').fadeIn();
					$('#tishicontent').html(s);
					notifyobj.showpopup(s1,{icon:'images/todo.png',rand:'systodo',title:this.bd2('57O757uf5o!Q6YaS'),click:function(b){
						opentixiangs();
						return true;
					}});
				}
			}
			if(a.editpass==0)this.showp();
		}
	}
	
	
	c.opennewtx=function(lx){
		return '0';
	}
	c.showp = function(){
		loadmenu = clickmenu=function(){
			js.msgerror(this.bd2('6K!35YWI5L!u5pS55a!G56CB5ZCO5Zyo5L2.55So'));
		}
		this.shumiao=function(){};
		js.alert(this.bd2('57O757uf5byA5ZCv5by65Yi25L!u5pS55a!G56CB77yM6K!35YWI5L!u5pS55ZCO5Zyo5L2.55So'),this.bd2('5L!u5pS55a!G56CB5o!Q56S6'), function(){
			addtabs({num:'grcog',url:'system,geren,cog,stype=pass',hideclose:true,name:c.bd2('5L!u5pS55a!G56CB'),icons:'lock'});
		});
	}
	js.initbtn(c);
	c.init();
	
	opentixiangs=function(){
		opentixiang();
		hideTishi();
		return false;
	}
	hideTishi=function(){
		$('#tishidivshow').fadeOut();
		return false;
	}

	openmobile=function(){
		js.tanbody('loginmobile','登录手机版', 300,200,{
			html:'<div  style="height:160px;padding:5px" align="center"><div><img id="logeweerew" src="images/logo.png" width="130" height="130"></div><div>5分钟内直接扫一扫即可登录</div></div>'
		});
		var surl = js.getajaxurl('getqrcode','index','home'),surls = js.getajaxurl('getqrcores','index','home');
		$.get(surls,function(ass){
			if(ass!='ok'){
				$('#logeweerew').parent().html('<div style="padding:10px 20px;text-align:left">未开启gd库，不能生成二维码，<br>可手机浏览器输入地址:<br>'+ass+'</div>');
			}else{
				get('logeweerew').src=surl;
			}
		});
	}
	
	homereload=function(rlx){
		c.itot(rlx);
	}
	
	openreim=function(o1){
		$('#reim_stotal').html('');
		var str = reimtitle;
		if(o1)str=strreplace($(o1).text());
		var ops = js.openrun('reim','winfocus');
		if(!ops){
			js.cliendsend('focus',{},false,function(){
				js.confirm('可能没有使用'+str+'的PC客户端，是否打开网页版的？',function(jg){
					if(jg=='yes'){
						js.open('?d=reim',260,530,'reim');
					}
				});
				return true;
			});
		}else{
			js.open('?d=reim',260,530,'reim');
		}
	}
	
	notifyobj=new notifyClass({
		title:'系统提醒',
		sound:'web/res/sound/todo.ogg',
		sounderr:'',
		soundbo:true,
		showbool:true
	});
});
</script>