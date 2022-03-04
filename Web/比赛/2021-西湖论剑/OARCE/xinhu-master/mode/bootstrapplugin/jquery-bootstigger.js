/**
	bootsform
*/

(function ($) {
	
	function bootstigger(element, options){
		var obj		= element;
		var can		= options;
		var rand	= can.rand; 
		var me		= this;
		
		
		//初始化
		this.init	= function(){
			this.mainidss = 'main_'+rand+'';
			if(can.namedisplay=='')can.namedisplay=''+can.name+'_text';
			var s = '<div   class="input-group">';
			s += '	<input readonly class="form-control form-select" name="'+can.namedisplay+'" placeholder="'+can.placeholder+'" id="display_'+rand+'">';
			s += '	<input type="hidden" id="value_'+rand+'" name="'+can.name+'" >';
			s += '	<span class="input-group-btn">';
			if(can.clearbool){
				s += ' <button class="btn btn-default" id="downclear_'+rand+'" type="button"><i class="icon-remove"></i></button>';
			}
			s += '		<button class="btn btn-default" id="down_'+rand+'" type="button"><i class="icon-caret-down"></i></button>';
			s += '	</span>';
			s += '</div>';
			
			var s1 = '<div id="div_'+rand+'" style="position:relative;">'+s+'</div>';
			obj.html(s1);
			
			$('#down_'+rand+'').click(function(){
				me._down(this);
			});
			if(can.clearbool){
				$('#downclear_'+rand+'').click(function(){
					me._downclear(this);
				});
			}
			this.setDefault(can.value, can.data);
			this._loadbool = false;
			this._fistload(0);//自动加载
		};
		
		this._down = function(o1){
			var o = $(o1);//
			if(!get(this.mainidss)){
				var s = '<div >';
				s += '<table width="100%"><tr>';
				s += '	<td><input id="keysou_'+rand+'" style="height:32px;line-height:20px;background:white;border:1px #cccccc solid;margin:5px;padding:3px;width:100%" ></td>';
				s += '		<td><button id="keysoubtn_'+rand+'" style="height:32px;background:white;border:1px #cccccc solid;padding:0px 10px" type="button"><i class="icon-search"></i></button></td>';
				s += '	</tr></table>';
				s += '</div>';
				var w = can.width;
				if(w==0)w = obj.width()-2;
				var s1 = '<div id="'+this.mainidss+'" style="position:absolute;z-index:120;background:white;left:1px;top:33px;padding:0px;width:'+w+'px;border:1px #dddddd solid;box-shadow:0 0 5px rgba(0, 0, 0, 0.3);display:none">';
				s1+=s;
				s1+='<div id="list_'+rand+'" class="select-list" style="overflow-y:auto"></div>';
				s1+='</div>';
				$('#div_'+rand+'').append(s1);
				this._showdata(can.data);
				js.addbody(rand, 'hide', this.mainidss);
				if(!this._loadbool)this._fistload(1);
				$('#keysou_'+rand+'').keyup(function(e){
					me._soukeysss(this, e);
					return false;
				});
				$('#keysoubtn_'+rand+'').click(function(){
					me._soukeybtn(this);
					return false;
				});
			}
			$('#'+this.mainidss+'').toggle();
			if(get(this.mainidss).style.display!='none'){
				get('keysou_'+rand+'').focus();
				this._setselcol();
				this._showeizhi();
			}
		};
		
		this._showdata = function(a){
			var s = '',i,val1,s1,len  = a.length;
			this.nowdata = a;
			for(i=0; i<len && i<can.maxlist; i++){
				val1 = a[i][can.valuefields];
				s+= '<div oi="'+i+'" val="'+val1+'" class="div01">';
				if(can.checked){
					s+='<input type="checkbox" val="'+val1+'" name="checkbox_'+rand+'" value="'+i+'"> ';
				}
				s1= can.renderer(a[i], this);
				if(!s1){
					s+= a[i][can.displayfields];
				}else{
					s+= s1;
				}
				s+= '</div>';
			}
			var o = $('#list_'+rand+'');
			o.html(s);
			o.find("div.div01").click(function(){
				me._itemsclick(this);
			});
			if(can.checked){
				o.find("input[type='checkbox']").click(function(){
					me._itemscheckclick(this);
				});
			}
			var h = can.height,h1 = i*can.listheight;
			if(h1>h)h1 = h;
			o.css('height',''+h1+'px');
			this._setselcol();
		};
		this._showeizhi= function(){
			var o,jg,t;
			o 	= $('#'+this.mainidss+'');
			jg  = $('#div_'+rand+'').offset().top+o.height()-winHb()+10;
			t   = 33;
			if(jg>0)t = 0-jg;
			o.css('top',''+t+'px');
		};
		this._setselcol= function(){
			var vals = this.getValue(),i,o1,val1;
			var o = $('#list_'+rand+'').find("div[val]");
			o.removeClass();
			o.addClass('div01');
			var o2 	= $('#list_'+rand+'').find("input[type='checkbox']");
			o2.attr('checked', false);
			if(vals!=''){
				var a 	= vals.split(',');
				for(i=0; i<a.length; i++){
					o1	 = $('#list_'+rand+'').find("div[val='"+a[i]+"']");
					o1.removeClass();
					o1.addClass('div02');
				}   				vals = ','+vals+',';
				for(i=0; i<o2.length; i++){
					o1 	 = o2[i];
					val1 = $(o1).attr('val');
					if(vals.indexOf(','+val1+',')>-1)o1.checked=true;
				}
			}
		};
		this._cenghide = function(){
			$('#'+this.mainidss+'').hide();
		};
		this._itemsclick = function(o1){
			if(can.checked)return;
			var o = $(o1);
			var oi = parseInt(o.attr('oi'));
			var d = this.nowdata[oi],
				nae = d[can.displayfields],
				val = d[can.valuefields];
			$('#display_'+rand+'').val(nae);
			$('#value_'+rand+'').val(val);
			$('#display_'+rand+'').focus();
			can.itemsclick(d, this, oi);
			this._cenghide();
		};
		this._itemscheckclick = function(o1){
			var s = js.getchecked('checkbox_'+rand+'');
			var nae='',val='',i,s1,d,oi;
			if(s!=''){
				s1 = s.split(',');
				for(i=0;i<s1.length;i++){
					oi = s1[i];
					d  = this.nowdata[oi];
					nae+=','+d[can.displayfields];
					val+=','+d[can.valuefields];
				}
			}
			if(nae!=''){
				nae = nae.substr(1);
				val = val.substr(1);
			}
			$('#display_'+rand+'').val(nae);
			$('#value_'+rand+'').val(val);
			this._setselcol();
		};
		//设置默认值
		this.setDefault = function(vs1,a){
			var nae = can.display,
				val = vs1,
				i,d;
			var sval = ','+val+',',vslss,oi=0;
			for(i=0; i<a.length; i++){
				d = a[i];vslss = ','+d[can.valuefields]+',';
				if(sval.indexOf(vslss)>-1){
					if(!can.checked){
						nae = d[can.displayfields];
						break;
					}else{
						if(oi==0)nae='';
						nae+=','+d[can.displayfields];
						oi++;
					}
				}
			}
			if(nae.substr(0,1)==',')nae=nae.substr(1);
			$('#display_'+rand+'').val(nae);
			$('#value_'+rand+'').val(val);
		};
		this.getValue = function(){
			return $('#value_'+rand+'').val();
		};
		this.getRawValue = function(){
			return $('#display_'+rand+'').val();
		};
		this.setValue = function(val){
			this.setDefault(val, can.data);
		};
		this.setRawValue = function(v, v1){
			$('#display_'+rand+'').val(v);
			$('#value_'+rand+'').val(v1);
		};
		this._load = function(url){
			if(isempt(url))return;
			if(get('list_'+rand+'')){
				$('#list_'+rand+'').html('<div align="center" style="padding:20px"><img src="images/mloading.gif"></div>');
			}
			$.post(url,{key:$('#keysou_'+rand+'').val()},function(da){
				var a = js.decode(da);
				me._loadbool = true;
				me._loadback(a);
			});
		};
		
		//搜索
		this._soukeysss = function(o1, e){
			var code = e.keyCode;
			if(code==13){
				this._soukeybtn();
			}else{
				this._soukey();
			}
			//38 39 40 41
			//js.msg('msg',code)
		}
		this._soukey = function(){
			clearTimeout(this._soukeytime);
			this._soukeytime = setTimeout(function(){
				me._soukey1();
			},300);
		};
		this._soukey1 = function(){
			var d=[],i,bo,k,d1,
				val = strreplace($('#keysou_'+rand+'').val()),
				a = can.data;
			if(a.length<=0 || this._oldvalue==val)return;
			if(!isempt(val)){
				val = val.toLowerCase();
				for(i=0; i<a.length; i++){
					if(d.length>10)break;
					d1 = a[i];bo = false;
					for(k in d1){
						if(!isempt(d1[k])){
						if(d1[k].toLowerCase().indexOf(val)==0){
							bo = true;break;
						}};
					}
					if(bo)d.push(d1);
				}
			}else{
				d = a;
			}
			this._showdata(d);
			this._oldvalue = val;
		};
		this._soukeybtn = function(){
			if(can.url==''){
				this._soukey();
			}else{
				this._fistload(1);
			}
		};
		
		this._loadback = function(a){
			this.setData(a);
		};
		this._fistload = function(lx){
			if(!can.autoLoad && lx==0 )return;
			if(can.url=='')return;
			this._load(can.url);
		};
		this._downclear = function(){
			this.setValue('');
			$('#display_'+rand+'').focus();
		};
		
		
		this.reload = function(url){
			if(!url)url = can.url;
			this._load(can.url);
		};
		this.setData = function(a, naf, nafid){
			can.data = a;
			if(get('list_'+rand+'')){
				this._showdata(a);
				this.setValue(this.getValue());
			}
			if(naf)can.displayfields=naf;
			if(nafid)can.valuefields=nafid;
		};
		
		this.setDisabled = function(bo){
			get('down_'+rand+'').disabled = bo;
			get('display_'+rand+'').disabled = bo;
			if(can.clearbool)get('downclear_'+rand+'').disabled = bo;
		};
		this.hide = function(){
			obj.hide();
		};
		this.show = function(){
			obj.show();
		};
		this.setVisible=function(bo){
			if(bo){
				this.show();
			}else{
				this.hide();
			}
		};
	};
	
	
	$.fn.bootstigger	= function(options){
		var defaultVal = {
			items:[],rand:js.getrand(),name:'',namedisplay:'',data:[],
			displayfields:'name',valuefields:'name',
			renderer:function(){return ''},listheight:38,
			itemsclick:function(){},height:300,width:0,placeholder:'',
			maxlist:1000,//最多列表数
			checked:false,
			clearbool:false,
			value:'',display:'',
			autoLoad:false,url:'',load:function(){}
		};
		var can = $.extend({}, defaultVal, options);
		clsa = new bootstigger($(this), can);
		clsa.init();
		return clsa;
	};
})(jQuery);