/**
*	录入js额外文件，要共用到详情页里的
*/

var inputtwo={
	selectdatadata:{}, //保存数据源
	selectdata:function(s1,ced,fid,tit,zbis){
		if(isedit==0)return;
		if(!tit)tit='请选择...';
		if(s1.indexOf('[SQL]')==0){js.msg('msg','此元素类型的不支持数据源是SQL的');return;}
		var a1 = s1.split(','),idobj=false,acttyle='act';
		var fids = a1[1];
		if(fids){
			if(zbis>=1){//说明是子表
				var gezs = this.getxuandoi(fid);
				fids+=gezs[2];
			}
			idobj=form(fids);
		}
		var gcan,dass,i,befs
		gcan = {'act':a1[0],'actstr':jm.base64encode(s1),'acttyle':acttyle,'sysmodenum':modenum,'sysmid':mid};
		dass = this.selectdatadata[fid];
		befs = this.onselectdatabefore(fid,zbis,s1);
		if(befs){
			if(typeof(befs)=='string'){js.msg('msg',befs);return;}
			if(typeof(befs)=='object'){
				dass=[];
				for(i in befs)gcan[i]=befs[i];
			}
		}
		$.selectdata({
			data:dass,title:tit,fid:fid,
			url:geturlact('getselectdata', gcan),
			checked:ced, nameobj:form(fid),idobj:idobj,
			onloaddata:function(a){
				c.selectdatadata[fid]=a;
			},
			onselect:function(seld,sna,sid){
				c.onselectdataall(this.fid,seld,sna,sid);
				if(c.onselectdata[this.fid])c.onselectdata[this.fid](seld,sna,sid);
			}
		});
	},
	selectdataclear:function(fid,s1,zbis){
		if(form(fid))form(fid).value='';
		var a1 = s1.split(',');
		var fids = a1[1];
		if(fids){
			if(zbis>=1){
				var gezs = this.getxuandoi(fid);
				fids+=gezs[2];
			}
			if(form(fids))form(fids).value='';
		}
	},
	
	//编辑器
	htmlediter:function(fid){
		var items = [
			'forecolor', 'hilitecolor', 'bold', 'italic', 'underline','removeformat','|',
			'fontname', 'fontsize','quickformat', '|', 
			'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist','insertunorderedlist', '|',
			'image', 'link','unlink','|',
			'undo','source','clearhtml','fullscreen'
		];
		if(ismobile==1)items=['forecolor', 'hilitecolor', 'bold', 'italic','|','source','clearhtml','fullscreen'];
		var oethed  = this.htmlediteritems(fid);
		if(oethed){
			var kx = 0,i;
			if(oethed[0]=='clear'){items=[];kx=1;oethed.push('fullscreen')}
			for(i=kx;i<oethed.length;i++)items.push(oethed[i]);
		}
		var cans  = {
			resizeType : 0,
			allowPreviewEmoticons : false,
			allowImageUpload : true,
			formatUploadUrl:false,
			allowFileManager:true,
			uploadJson:'?m=upload&a=upimg&d=public',
			minWidth:'300px',height:'250',
			items : items
		};
		this.editorobj[fid] = KindEditor.create("[name='"+fid+"']", cans);
	},

	//初始上传框
	filearr:{},
	initupssa:{},
	initinput:function(){
		if(isedit==0){
			$('div[tsye="img"]').hide();
			$("div[id$='_divadd']").hide();
		}
		var o,o1,sna,i,tsye,uptp,tdata,farr=alldata.filearr,far,allfid='',allsna={};
		var o = $('div[id^="filed_"]');
		for(i=0;i<o.length;i++){
			o1 = o[i];sna= $(o1).attr('tnam');tsye=$(o1).attr('tsye');tdata=$(o1).attr('tdata');
			var val = form(sna).value;
			if(tsye=='img'){
				var val1 = data[''+sna+'_view'];
				if(!val1)val1=val;
				if(val1)get('imgview_'+sna+'').src=val1;
			}
			if(tsye=='file' && val){
				if(farr){
					var fid,f,vals=','+val+',';
					for(fid in farr){
						f = farr[fid];
						if(!f || vals.indexOf(','+f.id+',')<0)continue;
						this.showfileup(sna, f);
					}
					this.showupid(sna);//多文件上传显示id
				}else{
					allfid+=','+val+'';
					allsna[sna]=val;
				}
			}
		}
		if(allfid){
			js.ajax('api.php?m=upload&a=afileinfo',{allfid:allfid.substr(1)},function(farr){
				var sna,fid,f,vals;
				if(farr)for(sna in allsna){
					vals = ','+allsna[sna]+',';
					for(fid in farr){
						f = farr[fid];
						if(!f || vals.indexOf(','+f.id+',')<0)continue;
						c.showfileup(sna, f);
					}
					c.showupid(sna);
				}
			},'get,json');
		}
		if(ismobile==1){
			$('div[tmp="mobilezbiao"]').css('width',''+($(window).width()-12)+'px');
		}
	},
	initupss:function(sna){
		if(isedit==0 || this.initupssa[sna])return;
		var o,o1,sna,tsye,uptp='image';
		o1 = get('filed_'+sna+'');tsye=$(o1).attr('tsye');tdata=$(o1).attr('tdata');
		if(tsye=='file'){
			uptp='*';
			if(!isempt(tdata))uptp=tdata;
		}
		this.initupssa[sna]=$.rockupload({
			'inputfile':'filed_'+sna+'_inp',
			'initremove':false,'uptype':uptp,
			'urlparams':{'sysmodenum':modenum,'sysmid':mid},
			'oparams':{sname:sna,snape:tsye},
			'onsuccess':function(f,gstr){
				var sna= f.sname,tsye=f.snape,d=js.decode(gstr);
				if(tsye=='img'){
					get('imgview_'+sna+'').src = d.filepath;
					form(sna).value=d.filepath;
					c.upimages(sna,d.id,false, d.autoup);
				}else if(tsye=='file'){
					$('#meng_'+c.uprnd+'').remove();
					$('#up_'+c.uprnd+'').attr('upid_'+sna+'',d.id);
					c.upfbo = false;
					c.filearr['f'+d.id+''] = f;
					c.showupid(sna);//显示ID	
				}
				c.uploadback(sna, f);
			},
			'onprogress':function(f,bl){
				var sna= f.sname,tsye=f.snape;
				if(tsye=='file'){
					$('#meng_'+c.uprnd+'').html(''+bl+'%');
				}
			},
			onchange:function(f){
				var sna= f.sname,tsye=f.snape;
				if(tsye=='file'){
					var flx = js.filelxext(f.fileext);
					c.uprnd = js.getrand();
					c.upfbo = true;
					var s='<div onclick="c.clickupfile(this,\''+sna+'\')" id="up_'+c.uprnd+'" title="'+f.filename+'('+f.filesizecn+')"  class="upload_items">';
					if(f.isimg){
						s+='<img class="imgs" src="'+f.imgviewurl+'">'
					}else{
						s+='<div class="upload_items_items"><img src="web/images/fileicons/'+flx+'.gif" alian="absmiddle"> ('+f.filesizecn+')<br>'+f.filename+'</div>';
					}
					s+='<div id="meng_'+c.uprnd+'" class="upload_items_meng" style="font-size:16px">0%</div></div>';
					$('#'+sna+'_divadd').before(s);
				}else if(tsye=='img'){
					js.loading('上传中...');
				}
			}
		});
	},
	showfileup:function(sna, f){
		var s = '';
		s='<div onclick="c.clickupfile(this,\''+sna+'\')" title="'+f.filename+'('+f.filesizecn+')" upid_'+sna+'="'+f.id+'" class="upload_items">';
		if(js.isimg(f.fileext)){
			s+='<img class="imgs" src="'+f.thumbpath+'">';
		}else{
			s+='<div class="upload_items_items"><img src="web/images/fileicons/'+js.filelxext(f.fileext)+'.gif" alian="absmiddle"> ('+f.filesizecn+')<br>'+f.filename+'</div>';
		}
		s+='</div>';
		$('#'+sna+'_divadd').before(s);
		this.filearr['f'+f.id+''] = f;
	},
	upimages:function(fid,fileid,bs, lbu){
		if(!bs){
			if(lbu!=1){js.unloading();return;}
			js.loading('等待上传完成...');
			setTimeout("c.upimages('"+fid+"','"+fileid+"', true)",3000);
		}else{
			js.ajax(geturlact('upimagepath'),{fileid:fileid,fid:fid},function(ret){
				js.unloading();
				var da = ret.data;
				if(da.path)form(da.fid).value=da.path;
			},'get,json');
		}
	},
	//多文件点击上传
	uploadfilei:function(sna){
		if(isedit==0)return;
		var ts = this.uploadfileibefore(sna);
		if(ts){js.msg('msg',ts);return;}
		if(this.upfbo){js.msg('msg','请等待上传完成在添加');return;}
		this.initupss(sna);
		get('filed_'+sna+'_inp').click();
	},
	//上传完成
	showupid:function(sna){
		var os = $('div[upid_'+sna+']'),fvid='';
		for(var i=0;i<os.length;i++){
			fvid+=','+$(os[i]).attr('upid_'+sna+'')+'';
		}
		if(fvid!='')fvid=fvid.substr(1);
		form(sna).value=fvid;
	},
	//上传文件点击
	clickupfile:function(o1,sna, xs){
		this.yuobj = o1;
		var o = $(o1);
		var fid = o.attr('upid_'+sna+'');
		if(isempt(fid))return;
		var f = this.filearr['f'+fid+''];if(!f)return;
		if(isedit==0 || xs){
			js.alertclose();
			this.loadicons();
			js.fileopt(fid,0);
		}else{
			var fileext = f.fileext,oflx=',doc,docx,ppt,pptx,xls,xlsx,',s1='';
			if(oflx.indexOf(','+fileext+',')>-1)s1='&nbsp; <a style="color:blue" href="javascript:;" onclick="js.alertclose();js.fileopt('+fid+',2)">在线编辑</a>';
			js.confirm('确定要<font color=red>删除文件</font>：'+o1.title+'吗？<a style="color:blue" href="javascript:;" onclick="js.alertclose();js.downshow('+fid+',\'abc\')">下载</a>&nbsp; <a style="color:blue" href="javascript:;" onclick="c.clickupfile(c.yuobj,\''+sna+'\', true)">预览</a>'+s1+'',function(jg){
				if(jg=='yes'){
					o.remove();
					c.showupid(sna);
					$.get(js.getajaxurl('delfile','upload','public',{id:fid}));
				}
			});
		}
	},
	uploadimgclear:function(fid){
		get('imgview_'+fid+'').src='images/noimg.jpg';
		form(fid).value='';
	},
	
	//2020-09-02新增地图上选择位置
	selectmap:function(sna,snall,fna,iszb){
		var hei = winHb()-150;
		var url = 'https://map.qq.com/api/js?v=2.exp&libraries=convertor,geometry&key=55QBZ-JGYLO-BALWX-SZE4H-5SV5K-JCFV7&callback=c.showmap';
		js.tanbody('selectmap','选择['+fna+']',winWb()-((ismobile==1)?5:80),hei,{
			html:'<div style="padding:5px"><input onkeyup="if(event.keyCode==13)c.selectmapsou(this)" type="text" placeholder="输入城市区号来定位如：0592" class="inputs"></div><div id="selectmap" style="height:'+(hei-20)+'px;overflow:hidden"></div>',
			btn:[{text:'确定'}]
		});
		this.selectmapdata={sna:sna,snall:snall};
		this._temsel=[24.51036967209648,118.17883729934692,12];
		if(snall && form(snall) && form(snall).value)this._temsel = form(snall).value.split(',');
		if(!this.showmapbo){js.importjs(url);}else{this.showmap()}
		$('#selectmap_btn0').click(function(){
			c.selectmapque();
			js.tanclose('selectmap');
		});
	},
	selectmapclear:function(sna,snall){
		if(form(sna))form(sna).value='';
		if(snall && form(snall))form(snall).value='';
	},
	showmapbo:false,
	showmap:function(){
		this.showmapbo=true;
		var center = new qq.maps.LatLng(parseFloat(this._temsel[0]),parseFloat(this._temsel[1]));
		map = new qq.maps.Map(get('selectmap'),{
			center: center,
			zoom: parseFloat(this._temsel[2])
		});
		qq.maps.event.addListener(map, 'click', function(event) {
			marker.setPosition(event.latLng);
		});
		marker = new qq.maps.Marker({
			position: center,
			map: map,
			draggable:true,
			title:'点地图确定位置'
		});
	},
	selectmapsou:function(o1){
		var val = o1.value;
		if(!val || isNaN(val))return;
		if(!this.citylocation)this.citylocation = new qq.maps.CityService({
			complete : function(result){
				map.setCenter(result.detail.latLng);
			}
		});
		this.citylocation.searchCityByAreaCode(val);
	},
	selectmapque:function(){
		var as = marker.getPosition();
		var x 	= as.getLat();
		var y 	= as.getLng();
		var zoom = map.getZoom();
		this.selectmapdata.lat=x;
		this.selectmapdata.lng=y;
		this.selectmapdata.zoom=zoom;
		js.msg('wait','确定搜索地址...');
		this.geocoder(x,y);
	},
	//搜索位置
	geocoder:function(lat,lng, jid){
		if(!this.geocoderObj){
			this.geocoderObj 	= new qq.maps.Geocoder();
			this.geocoderObj.setComplete(function(result){
				var d1 = c.selectmapdata;
				d1.address = result.detail.address;
				d1.addressinfo = result.detail.addressComponents;
				js.msg();
				var sna = d1.sna;
				if(form(sna))form(sna).value=d1.address+'|'+d1.lat+','+d1.lng+'';
				var sna1 = d1.snall;
				if(sna1 && form(sna1))form(sna1).value=''+d1.lat+','+d1.lng+','+d1.zoom+'';
				c.onselectmap(sna,d1);
			});
			this.geocoderObj.setError(function() {
				js.msg('msg','搜索地址失败');
			});
		}
		var center 	= new qq.maps.LatLng(lat, lng);
		this.geocoderObj.getAddress(center);
	},
	xuanfile:function(fid,lx,fname,o1){
		if(!fname)fname='';
		$.selectdata({
			title:fname+'(模版选择)',fid:fid,
			url:'api.php?m=upload&a=getmfile&fenlei='+jm.base64encode(lx)+'',
			checked:false,nameobj:false,idobj:false,
			onselect:function(seld,sna,sid){
				if(sid)c.xuanfiles(this.fid,sid,fname,o1);
			}
		});
	},
	xuanfiles:function(fid,sid,fname,o1){
		js.loading('生成文件中...');if(!fname)fname='';
		js.ajax('api.php?m=upload&a=getmfilv',{fileid:sid,fname:jm.base64encode(fname)},function(ret){
			if(ret.success){
				js.unloading();
				c.showfileup(fid,ret.data);
				c.showupid(fid);
				if(o1)$(o1).remove();
			}else{
				js.msgerror(ret.msg);
			}
		},'get,json');
	}
}