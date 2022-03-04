/**
*	来自：信呼开发团队
*	作者：磐石(rainrock)
*	网址：http://www.rockoa.com/
*	修改时间：2020-03-20
*	移动端应用主js文件，请不要去修改
*/

var myScroll=false,yy={
	sousoukey:'',
	onshowdata:function(){},
	loadci:0,
	searchparams:{},
	resizehei:function(){
		var hei= this.getheight();
		if(agentlx==0){
			var ob = this.showobj.css({'height':''+hei+'px'});
			return ob;
		}
	},
	getheight:function(ss){
		return this.geth(ss);
	},
	scrollnew:function(){
		var top = $(document).scrollTop();
		if(top>50){
			if(!get('backtuodiv')){
				var s = '<div id="backtuodiv" onclick="js.backtop()" style="position:fixed;right:5px;bottom:10px;width:30px;height:30px; background:rgba(0,0,0,0.4);z-index:9;border-radius:50%;font-size:14px;color:white;text-align:center;line-height:30px"><i class="icon-angle-up"></i></div>';
				$('body').append(s);
			}
		}else{
			$('#backtuodiv').remove();
		}
	},
	loadshow:function(){
		var url = location.href,arr = json.menu;
		var urla= url.split('#'),darr = this.getfirstnum(arr);
		var dkey= darr[0];
		if(urla[1])dkey = urla[1];
		this.getdata(dkey,1);
		if(darr[1]>-1){
			var tit = arr[darr[1]].name;
			if(darr[2]>-1)tit = arr[darr[1]].submenu[darr[2]].name;
			this.showtabstr(darr[1], tit);
		}
	},
	getfirstnum:function(d){
		var dbh = 'def',bh='',a = d[0],i,len,lens,subs;
		if(a){
			bh = a.url;
			if(a.submenu[0])bh=a.submenu[0].url;
		}
		try{
			var site = sessionStorage.getItem(''+json.num+'_event');
			if(site)bh = site;
		}catch(e){}
		
		if(isempt(bh))bh=dbh;
		len = d.length;
		var goi = -1,goj=-1;
		for(i=0;i<len;i++){
			subs = d[i].submenu;
			lens = subs.length;
			if(goi>-1)break;
			if(lens>0){
				for(var j=0;j<lens;j++){
					if(subs[j].url==bh){
						goi = i;
						goj = j;
						break;
					}
				}
			}else{
				if(d[i].url==bh){
					goi = i;
					break;
				}
			}
		}
		return [bh,goi,goj];
	},
	showtabstr:function(oi, tit){
		$('[temp="tablx"]').removeClass('active');
		$('[temp="tablx"]:eq('+oi+')').addClass('active');
		$('[temp="taby"]').css({'color':'','border-top':''});
		$('[temp="taby"]:eq('+oi+')').css({'color':maincolor,'border-top':'1px '+maincolor+' solid'});
		$('[temp="taby"]:eq('+oi+')').find('font').html(tit);
		this.settitle(tit);
	},
	clickmenu:function(oi,o1){
		if(o1.className.indexOf('disabled')>0)return;
		var sid='menushoess_'+oi+'';
		if(get(sid)){
			$('#'+sid+'').remove();
			return;
		}
		$("div[id^='menushoess']").remove();
		var a = json.menu[oi],slen=a.submenu.length,i,a1;
		this.menuname1 = a.name;
		this.menuname2 = '';
		if(slen<=0){
			this.clickmenus(a,oi);
		}else{
			if(agentlx==0){
				var o=$(o1),w=1/json.menu.length*100;
				var s='<div id="'+sid+'" style="position:fixed;z-index:5;left:'+(o.offset().left)+'px;bottom:50px; background:white;width:'+w+'%" class="menulist r-border-r r-border-l">';
				for(i=0;i<slen;i++){
					a1=a.submenu[i];
					s+='<div onclick="yy.clickmenua('+oi+','+i+')" class="r-border-t" style="color:'+a1.color+';">'+a1.name+'</div>';
				}
				s+='</div>';
				$('body').append(s);
			}
			if(agentlx==1){
				var da = [];
				for(i=0;i<slen;i++){
					a1=a.submenu[i];
					a1.oi = oi;
					a1.i = i;
					da.push(a1);
				}
				js.showmenu({
					data:da,
					width:150,
					onclick:function(d){
						yy.clickmenua(d.oi,d.i);
					}
				});
			}
		}
	},
	seuser:function(){
		$('#searsearch_bar').addClass('weui_search_focusing');
		$('#s_inp').focus();
	},
	sqxs:function(){
		$('#s_inp').blur();
		$('#searsearch_bar').removeClass('weui_search_focusing');
	},
	scle:function(){
		$('#s_inp').val('').focus();
	},
	sous:function(){
		var key = $('#s_inp').blur().val();
		this.keysou(key);
	},
	clickmenua:function(i,j){
		var a = json.menu[i].submenu[j];
		this.menuname2 = a.name;
		this.clickmenus(a,i);
	},
	onclickmenu:function(a){
		return true;
	},
	
	settitle:function(tit){
		document.title = tit;
		$('#header_title').html(tit);
		js.setapptitle();
	},
	
	clickmenus:function(a,oi){
		$("div[id^='menushoess']").remove();
		if(!this.onclickmenu(a))return;
		var tit = this.menuname1;
		if(this.menuname2!='')tit=this.menuname2;
		if(a.type==0){
			this.sqxs();
			this.sousoukey='';
			this.clickevent(a);
			this.showtabstr(oi, tit);
		}
		if(a.type==1){
			var url=a.url,amod=this.num;
			if(url.substr(0,3)=='add'){
				if(url!='add')amod=url.replace('add_','');
				url='index.php?a=lum&m=input&d=flow&num='+amod+'&show=we';
			}
			js.location(url);
		}
	},
	clickevent:function(a){
		this.getdata(a.url, 1);return;
		if(agentlx==1){
			js.location('#'+a.url+'');
		}else{
			this.getdata(a.url, 1);
		}
	},
	data:[],
	_showstotal:function(d){
		var d1,v,s,o1;
		for(d1 in d){
			v=d[d1];
			if(v==0)v='';
			o1= $('#'+d1+'_stotal');
			o1.html(v);
		}
	},
	regetdata:function(o,p){
		var mo = 'mode';
		if(o){
			o.innerHTML='<img src="images/loading.gif" align="absmiddle">';
			mo = 'none';
		}
		this.getdata(this.nowevent,p, mo);
	},
	
	reload:function(){
		this.getdata(this.nowevent,this.nowpage);
	},
	search:function(cans){
		if(!cans)cans={};
		this.searchparams=cans;
		this.getdata(this.nowevent,1, '', cans);
	},
	keysou:function(key){
		if(this.sousoukey == key)return;
		this.sousoukey = key;
		this.regetdata(false,1);
	},
	xiang:function(oi){
		var d = this.data[oi-1];
		if(d.xiangurl){
			js.location(d.xiangurl+'&show=we');
			return;
		}
		var ids = d.id,nus=d.modenum,modne=d.modename;
		if(!ids)return;
		if(!nus||nus=='undefined')nus = this.num;
		var url='task.php?a=x&num='+nus+'&mid='+ids+'&show=we';
		js.location(url);
	},
	suboptmenu:{},
	showmenu:function(oi){
		var a = this.data[oi-1],ids = a.id,i;
		var nus=a.modenum;if(!nus||nus=='undefined')nus = this.num;
		if(a.type=='applybill' && nus){
			var url='index.php?a=lum&m=input&d=flow&num='+nus+'&show=we';
			js.location(url);return;
		}
		if(!ids)return;
		this.tempid 	= ids;
		this.tempnum 	= nus;
		this.temparr 	= {oi:oi,da:a};
		var da = [{name:this.bd6('6K!m5oOF'),lx:998,oi:oi}];
		var subdata = this.suboptmenu[''+nus+'_'+ids+''];
		if(typeof(subdata)=='object'){
			for(i=0;i<subdata.length;i++)da.push(subdata[i]);
		}else{
			da.push({name:'<img src="images/loadings.gif" align="absmiddle"> '+this.bd6('5Yqg6L296I!c5Y2V5LitLi4u')+'',lx:999});
			this.loadoptnum(nus,ids);
		}
		js.showmenu({
			data:da,
			width:150,
			onclick:function(d){
				yy.showmenuclick(d);
			}
		});
		this.suboptmenu={};
	},
	loadoptnum:function(nus,id){
		js.ajax('agent','getoptnum',{num:nus,mid:id},function(ret){
			yy.suboptmenu[''+nus+'_'+id+'']=ret;
			yy.showmenu(yy.temparr.oi);
		},'none',false,function(estr){
			yy.suboptmenu[''+nus+'_'+id+'']=[];
			yy.showmenu(yy.temparr.oi);
		});
	},
	getupgurl:function(str){
		if(str.substr(0,4)=='http')return str;
		var a1 = str.split('|'),lx = a1[0],mk = a1[1],cs=a1[2];
		var url= '';
		if(lx=='add')url='?a=lum&m=input&d=flow&num='+mk+'';
		if(lx=='xiang')url='task.php?a=x&num='+mk+'';
		if(cs)url+='&'+cs;
		return url;
	},
	showmenuclick:function(d){
		d.num=this.num;d.mid=this.tempid;
		d.modenum = this.tempnum;
		var lx = d.lx;if(!lx)lx=0;
		if(lx==999)return;
		if(lx==998){this.xiang(d.oi);return;}
		if(lx==996){this.xiang(this.temparr.oi);return;}
		this.changdatsss = d;
		if(lx==2 || lx==3){
			var clx='changeuser';if(lx==3)clx='changeusercheck';
			$('body').chnageuser({
				'changetype':clx,
				'titlebool':get('header_title'),
				'onselect':function(sna,sid){
					yy.xuanuserok(sna,sid);
				}
			});
			return;
		}
		if(lx==5){
			var upg = d.upgcont;
			if(isempt(upg)){
				js.msg('msg',this.bd6('5rKh5pyJ6K6!572u5omT5byA55qE5pON5L2c5Zyw5Z2A'));
			}else{
				var url = this.getupgurl(upg);
				js.location(url);
			}
			return;
		}
		if(lx==7){
			var upg = d.upgcont;
			if(isempt(upg)){
				js.msg('msg',this.bd6('5rKh5pyJ6K6!572u6Ieq5a6a5LmJ5pa55rOV'));
			}else{
				if(!window[upg]){
					js.msg('msg',this.bd6('6K6!572u55qE5pa55rOV4oCcezB94oCd5LiN5a2Y5ZyoJw::').replace('{0}',upg));
				}else{
					window[upg](this.temparr.da,d);
				}
			}
			return;
		}
		if(lx==1 || lx==9 || lx==10 || lx==13 || lx==15 || lx==16 || lx==17){
			var bts = (d.issm==1)?'必填':'选填';
			js.wx.prompt(d.name,'请输入['+d.name+']说明('+bts+')：',function(text){
				if(!text && d.issm==1){
					js.msg('msg','没有输入['+d.name+']说明');
				}else{
					yy.showmenuclicks(d, text);
				}
			});
			return;
		}
		if(lx==14){
			var url='index.php?a=lum&m=input&d=flow&num=remind&mid='+d.djmid+'&def_modenum='+d.modenum+'&def_mid='+d.mid+'&def_explain=basejm_'+jm.base64encode(d.smcont)+'&show=we';
			js.location(url);
			return;
		}
		if(lx==18){
			var url='index.php?a=lum&m=input&d=flow&num=receipt&mid='+d.djmid+'&def_modenum='+d.modenum+'&def_mid='+d.mid+'&def_modename=basejm_'+jm.base64encode(d.modename)+'&def_explain=basejm_'+jm.base64encode(d.smcont)+'&show=we';
			js.location(url);
			return;
		}
		if(lx==11){
			var url='index.php?a=lum&m=input&d=flow&num='+d.modenum+'&mid='+d.mid+'&show=we';
			js.location(url);
			return;
		}
		this.showmenuclicks(d,'');
	},
	xuanuserok:function(nas,sid){
		if(!sid)return;
		var d = this.changdatsss,sm='';
		d.changename 	= nas; 
		d.changenameid  = sid; 
		this.showmenuclicks(d,sm);
	},
	showmenuclicks:function(d, sm){
		if(!sm)sm='';
		d.sm = sm;
		for(var i in d)if(d[i]==null)d[i]='';
		js.ajax('index','yyoptmenu',d,function(ret){
			yy.suboptmenu[''+d.modenum+'_'+d.mid+'']=false;
			yy.getdata(yy.nowevent, 1);
		});	
	},
	showdata:function(a){
		this.overend = true;
		var s='',i,len=a.rows.length,d,st='',oi;
		$('#showblank').remove();
		$('#notrecord').remove();
		if(typeof(a.stotal)=='object')this._showstotal(a.stotal);
		if(a.page==1){
			this.showobj.html('');
			this.data=[];
		}
		for(i=0;i<len;i++){
			d=a.rows[i];
			oi=this.data.push(d);
			if(d.showtype=='line' && d.title){
				s='<div class="contline">'+d.title+'</div>';
			}else{
				if(!d.statuscolor)d.statuscolor='';
				st='';
				if(d.ishui==1)st='color:#aaaaaa;';
				s='<div style="'+st+'" class="r-border contlist">';
				if(d.title){
					if(d.face){
						s+='<div onclick="yy.showmenu('+oi+')" class="face"><img src="'+d.face+'" align="absmiddle">'+d.title+'</div>';
					}else{
						s+='<div onclick="yy.showmenu('+oi+')" class="tit">'+d.title+'</div>';
					}
				}
				if(d.optdt)s+='<div class="dt">'+d.optdt+'</div>';
				if(d.picurl)s+='<div onclick="yy.showmenu('+oi+')" class="imgs"><img src="'+d.picurl+'" width="100%"></div>';
				if(d.cont)s+='<div  onclick="yy.showmenu('+oi+')" class="cont">'+d.cont.replace(/\n/g,'<br>')+'</div>';
				if(d.id && d.modenum && !d.noshowopt){
					s+='<div class="xq r-border-t"><font onclick="yy.showmenu('+oi+')">操作<i class="icon-angle-down"></i></font><span onclick="yy.xiang('+oi+')">详情&gt;&gt;</span>';
					s+='</div>';
				}
				if(d.xiangurl){
					s+='<div class="xq r-border-t" onclick="yy.xiang('+oi+')"><font>详情&gt;&gt;</font></div>';
				}
				if(d.statustext)s+='<div style="background-color:'+d.statuscolor+';opacity:0.7" class="zt">'+d.statustext+'</div>';
				s+='</div>';
			}
			this.showobj.append(s);
		}
		var count=a.count;
		if(count==0)count=len;
		if(count>0){
			this.nowpage = a.page;
			s = '<div class="showblank" id="showblank">共'+count+'条记录';
			if(a.maxpage>1)s+=',当前'+a.maxpage+'/'+a.page+'页';
			if(a.page<a.maxpage){
				s+=', <a id="showblankss" onclick="yy.regetdata(this,'+(a.page+1)+')" href="javascript:;">点击加载</a>';
				this.overend = false;
			}
			s+= '</div>';
			this.showobj.append(s);
			if(a.count==0)$('#showblank').html('');
		}else{
			this.showobj.html('<div class="notrecord" id="notrecord">暂无记录</div>');
		}
		this.onshowdata(a);
	},
	scrollEndevent:function(){
		yy.regetdata(get('showblankss'),yy.nowpage+1);
	},
	clad:function(){
		var str = this.bd6('5bqU55So6aaW6aG15pi!56S6');
		if(json.iscy==1)str=this.bd6('5Y!W5raI5bqU55So6aaW6aG15pi!56S6');
		if(apicloud){
			api.actionSheet({
				title: this.bd6('6YCJ5oup6I!c5Y2V'),
				cancelTitle: this.bd6('5Y!W5raI'),
				buttons: [str,this.bd6('5YWz6Zet5bqU55So')]
			}, function(ret, err) {
				var index = ret.buttonIndex;
				if(index==1)yy.addchangying();
				if(index==2)js.back();
			});
		}else{
			js.showmenu({
				data:[{name:str,lx:1}],
				width:170,
				onclick:function(d){
					if(d.lx==1)yy.addchangying();
				}
			});
		}
	},
	addchangying:function(){
		js.ajax('indexreim','shecyy',{yynum:json.num},function(ret){
			json.iscy = ret.iscy;
			js.wx.msgok(ret.msg, false, 1);
		},'mode', false,false, 'get');
	},
	init:function(){
		for(var i in js.main)this[i]=js.main[i];
		this.zhuinit();
		this.num = json.num;
		this.showobj = $('#mainbody');
		$('.weui_navbar').click(function(){return false;});
		$('body').click(function(){
			$("div[id^='menushoess']").remove();
		});
		this.resizehei();
		$(window).resize(function(){yy.resizehei();});
		if(agentlx==1){$(window).scroll(function(){yy.scrollnew();});}
		if(!this.checkyz()){this.clickmenu=this.showdata=function(){};return;}
	},
	getdata:function(st,p, mo, cas){
		this.getdatamain(st,p, mo, cas);
	}
}