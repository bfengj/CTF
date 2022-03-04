/**
*	无刷新上传
*	createname：雨中磐石
*	homeurl：http://www.rockoa.com/
*	Copyright (c) 2016 rainrock (xh829.com)
*	Date:2016-01-01
*/

(function ($) {
	maxupgloble = 0;
	function rockupload(opts){
		var me 		= this;
		var opts	= js.apply({inputfile:'',initpdbool:false,initremove:true,uptype:'*',maxsize:5,onchange:function(){},onchangebefore:function(){},upurl:'',onprogress:function(){},urlparams:{},updir:'',onsuccess:function(){},quality:0.7,xu:0,fileallarr:[],autoup:true,oldids:'',
		onerror:function(){},fileidinput:'fileid',
		onabort:function(){},
		allsuccess:function(){}
		},opts);
		this._init=function(){
			for(var a in opts)this[a]=opts[a];
			//加载最大可上传大小
			if(maxupgloble==0)$.getJSON(js.apiurl('login','getmaxup'),function(res){
				try{
				if(res.code==200){
					var maxup = parseFloat(res.data.maxup);
					me.maxsize= maxup;
					maxupgloble = maxup;
				}}catch(e){}
			});
			if(maxupgloble>0)this.maxsize= maxupgloble;
			
			if(!this.autoup)return;
			if(this.initremove){
				$('#'+this.inputfile+'').parent().remove();
				var s='<form style="display:none;height:0px;width:0px" name="form_'+this.inputfile+'"><input type="file" id="'+this.inputfile+'"></form>';
				$('body').append(s);
			}
			$('#'+this.inputfile+'').change(function(){
				me.change(this);
			});
		};
		this.reset=function(){
			if(!this.autoup)return;
			var fids = 'form_'+this.inputfile+'';
			if(document[fids])document[fids].reset();
		};
		this.setparams=function(ars){
			this.oparams = js.apply({uptype:this.uptype}, ars);
			this.uptype=this.oparams.uptype;
		};
		this.setuptype=function(lx){
			this.uptype = lx;
		};
		this.setupurl=function(ul){
			this.upurl = ul;
		};
		this.click=function(ars){
			if(this.upbool)return;
			this.setparams(ars);
			get(this.inputfile).click();
		};
		this.clear=function(){
			this.fileallarr = [];
			this.filearr	= {};
			this.xu 		= 0;
			$('#'+this.fileview+'').html('');
		};
		this.change=function(o1){
			if(!o1.files){
				js.msg('msg','当前浏览器不支持上传1');
				return;
			}
			
			var f = o1.files[0];
			if(!f || f.name=='/')return;
			var a = {filename:f.name,filesize:f.size,filesizecn:js.formatsize(f.size)};
			if(a.filesize<=0){
				js.msg('msg',''+f.name+'不存在');
				return;
			}
			if(this.isfields(a))return;
			if(f.size>this.maxsize*1024*1024){
				this.reset();
				js.msg('msg','文件不能超过'+this.maxsize+'MB,当前文件'+a.filesizecn+'');
				return;
			}
			var filename = f.name;
			var fileext	 = filename.substr(filename.lastIndexOf('.')+1).toLowerCase();
			if(!this.uptype)this.uptype='*';
			if(this.uptype=='image')this.uptype='jpg,gif,png,bmp,jpeg';
			if(this.uptype=='word')this.uptype='doc,docx,pdf,xls,xlsx,ppt,pptx,txt';
			if(this.uptype!='*'){
				var upss=','+this.uptype+',';
				if(upss.indexOf(','+fileext+',')<0){
					js.msg('msg','禁止文件类型,请选择'+this.uptype+'');
					return;
				}
			}
			var nstr 	 = this.onchangebefore(f);
			if(nstr){js.msg('msg',nstr);return;}
			
			a.fileext	 = fileext;
			a.isimg		 = js.isimg(fileext);
			if(a.isimg)a.imgviewurl = this.getimgview(o1);
			a.xu		 = this.xu;
			a.f 		 = f;
			for(var i in this.oparams)a[i]=this.oparams[i];
			this.filearr = a;
			var zc=this.fileallarr.push(a);
			
			//如果是图片压缩一下超过1M
			if(f.size>1024*1024 && a.isimg && this.quality<1){
				this.compressimg(a.imgviewurl,f,function(nf){
					a.filesize 	 = nf.size;
					a.filesizecn = js.formatsize(nf.size);
					me.fileallarr[zc-1].f = nf;
					me.nnonchagn(a, nf, zc);
				});
			}else{
				this.nnonchagn(a, f, zc);
			}
		};
		this.nnonchagn=function(a,f,zc){
			this.xu++;
			this.onchange(a);
			this.reset();
			if(!this.autoup){
				var s='<div style="padding:3px;font-size:14px;border-bottom:1px #dddddd solid"><font>'+a.filename+'</font>('+a.filesizecn+')&nbsp;<span style="color:#ff6600" id="'+this.fileview+'_'+a.xu+'"></span>&nbsp;<a oi="'+(zc-1)+'" id="gm'+this.fileview+'_'+a.xu+'" href="javascript:;">改名</a>&nbsp;<a onclick="$(this).parent().remove()" href="javascript:;">×</a></div>';
				$('#'+this.fileview+'').append(s);
				$('#gm'+this.fileview+'_'+a.xu+'').click(function(){
					me.s_gaiming(this);
				});
				return;
			}
			this._startup(f);
		};
		this.s_gaiming=function(o1){
			var o,oi,one,fa;
			o  = $(o1);
			oi = parseFloat($(o1).attr('oi'));
			fa = this.fileallarr[oi];
			one= o.parent().find('font').html().replace('.'+fa.fileext+'','');
			if(get('confirm_main')){
				var nr = prompt('新文件名', one);
				if(nr){
					var newfie = nr+'.'+fa.fileext;
					o.parent().find('font').html(newfie);
					me.fileallarr[oi].filename=newfie;
				}
			}else{
				js.prompt('修改文件名','新文件名', function(jg,nr){
					if(jg=='yes' && nr){
						var newfie = nr+'.'+fa.fileext;
						o.parent().find('font').html(newfie);
						me.fileallarr[oi].filename=newfie;
					}
				}, one);
			}
		};
		this.compressimg=function(path,fobj,call){
			var img = new Image();
            img.src = path;
			if(!call)call=function(){};
			img.onload = function(){
				var that = this;
                var w = that.width,
                    h = that.height,
                    scale = w / h;
                var quality = me.quality;//压缩图片质量
                var canvas = document.createElement('canvas');
                var ctx = canvas.getContext('2d');
                var anw = document.createAttribute("width");
                anw.nodeValue = w;
                var anh = document.createAttribute("height");
                anh.nodeValue = h;
                canvas.setAttributeNode(anw);
                canvas.setAttributeNode(anh);
                ctx.drawImage(that, 0, 0, w, h);
				var base64 = canvas.toDataURL(fobj.type, quality);
				var nfobj  = me.base64toblob(base64);
				call(nfobj);
			}
		};
		this.base64toblob=function(urlData){
			var arr = urlData.split(','), mime = arr[0].match(/:(.*?);/)[1],
				bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
			while(n--){
				u8arr[n] = bstr.charCodeAt(n);
			}
			return new Blob([u8arr], {type:mime});
		};
		this.getimgview=function(o1){
			try{
				return URL.createObjectURL(o1.files.item(0));
			}catch(e){return false;}
		};
		this.isfields=function(a){
			var bo = false,i,d=this.fileallarr;
			for(i=0;i<d.length;i++){
				if(this.fileviewxu(d[i].xu) && d[i].filename==a.filename && d[i].filesize==a.filesize){
					return true;
				}
			}
			return bo;
		};
		this.sendbase64=function(nr,ocs){
			this.filearr=js.apply({filename:'截图.png',filesize:0,filesizecn:'',isimg:true,fileext:'png'}, ocs);
			this._startup(false, nr);
		};
		this.start=function(){
			return this.startss(0);
		};
		this.startss=function(oi){
			if(oi>=this.xu){
				var ids=''+this.oldids+'';
				var a = this.fileallarr;
				for(var i=0;i<a.length;i++)if(a[i].id)ids+=','+a[i].id+'';
				if(ids!='' && ids.substr(0,1)==',')ids=ids.substr(1);
				try{if(form(this.fileidinput))form(this.fileidinput).value=ids;}catch(e){};
				this.allsuccess(this.fileallarr, ids); //必须全部才触发
				return false;
			}
			this.nowoi = oi;
			var f=this.fileallarr[oi];
			if(!f || !this.fileviewxu(f.xu)){
				return this.startss(this.nowoi+1);
			}
			this.filearr = f;
			this.onsuccessa=function(f,str){
				var dst= js.decode(str);
				if(dst.id){
					this.fileallarr[this.nowoi].id=dst.id;
					this.fileallarr[this.nowoi].filepath=dst.filepath;
				}else{
					js.msg('msg', str);
					this.fileviewxu(this.nowoi, '<font color=red>失败1</font>');
				}
				this.startss(this.nowoi+1);
			}
			this.onprogressa=function(f,bil){
				this.fileviewxu(this.nowoi, ''+bil+'%');
			}
			this.onerror=function(){
				this.fileviewxu(this.nowoi, '<font color=red>失败0</font>');
				this.startss(this.nowoi+1);
			}
			this._startup(f.f);
			return true;
		};
		this.fileviewxu=function(oi,st){
			if(typeof(st)=='string')$('#'+this.fileview+'_'+oi+'').html(st);
			return get(''+this.fileview+'_'+oi+'');
		};
		//初始化文件防止重复上传
		this._initfile=function(f){
			var a 	= this.filearr,d={'filesize':a.filesize,'fileext':a.fileext};
			if(!a.isimg)d.filename=jm.base64encode(a.filename);
			var url = js.apiurl('upload','initfile', d);
			$.getJSON(url, function(ret){
				if(ret.success){
					var bstr = ret.data;
					me.upbool= false;
					me.onsuccess(a,bstr);
				}else{
					me._startup(f,false,true);
				}
			});
		};
		this._startup=function(fs, nr, bos){
			this.upbool = true;
			if(this.initpdbool && fs && !bos){this._initfile(fs);return;}
			try{var xhr = new XMLHttpRequest();}catch(e){js.msg('msg','当前浏览器不支持2');return;}
			this.urlparams.maxsize = this.maxsize;
			if(this.updir)this.urlparams.updir=this.updir;
			var url = this.upurl;
			if(!this.upurl)url = js.apiurl('upload','upfile', this.urlparams);
			xhr.open('POST', url, true); 
			xhr.onreadystatechange = function(){me._statechange(this);};
			xhr.upload.addEventListener("progress", function(evt){me._onprogress(evt, this);}, false);  
			xhr.addEventListener("load", function(){me._onsuccess(this);}, false);  
			xhr.addEventListener("error", function(){me._error(false,this);}, false); 
			if(nr)fs = this.base64toblob(nr);
			var fd = new FormData();  
			fd.append('file', fs, this.filearr.filename); 
			xhr.send(fd);
			this.xhr = xhr;
			this.upurl	= '';
		};
		this.onsuccessa=function(){
			
		};
		this._onsuccess=function(o){
			this.upbool = false;
			var bstr 	= o.response; 
			if(bstr.indexOf('id')<0 || o.status!=200){
				this._error(bstr);
			}else{
				this.onsuccessa(this.filearr,bstr,o);
				this.onsuccess(this.filearr,bstr,o);
			}
		};
		this._error=function(ts,xr){
			this.upbool = false;
			if(!ts)ts='上传内部错误';
			this.onerror(ts);
		};
		this._statechange=function(o){
			
		};
		this.onprogressa=function(){
			
		};
		this._onprogress=function(evt){
			var loaded 	= evt.loaded;  
			var tot 	= evt.total;  
			var per 	= Math.floor(100*loaded/tot);
			this.onprogressa(this.filearr,per, evt);
			this.onprogress(this.filearr,per, evt);
		};
		this.abort=function(){
			this.xhr.abort();
			this.upbool = false;
			this.onabort();
		};
		this._init();
	}
	
	
	$.rockupload = function(options){
		var cls  = new rockupload(options,false);
		return cls;
	}
	
})(jQuery); 