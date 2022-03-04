/**
*	rockdatepicker 时间选择插件-手机版本使用
*	caratename：雨中磐石(rainrock)
*	caratetime：2017-06-19 21:40:00
*	email:admin@rockoa.com
*	homepage:www.rockoa.com
*/

(function ($) {
	js.onchangedate = function(){}; //选择时间回调
	function rockdatepicker_mobile(options){
		
		var me = this;
		this.marr	= [31,28,31,30,31,30,31,31,30,31,30,31];
		this.weekarr= ['日','一','二','三','四','五','六'];
		
		//初始化
		this.init	= function(){
			for(var i in options)this[i]=options[i];
			this.inputarr = ['year','month','day','hour','miners','miao'];
			this.inputkey = ['Y','m','d','H','i','s'];
			this.initdevvalue();
			this.showtoday();
			this.setcontent();
		};
		this.initdevvalue	= function(){
			if(this.view=='date')this.format='Y-m-d';
			if(this.view=='year')this.format='Y';
			if(this.view=='month')this.format='Y-m';
			if(this.view=='datetime')this.format='Y-m-d H:i:s';
			if(this.view=='time')this.format='H:i:s';
			if(this.formats)this.format = this.formats;
			var lx		= this.format;
			if(lx=='H:i:00'||lx=='H:i'||lx=='i:s')this.view='time';
			var minv	= this.mindate;
			if(isempt(minv))minv= '1930-01-01 00:00:00';//最小时间
			this.mindate	= minv;
			var maxv	= this.maxdate;
			if(isempt(maxv))maxv= '2050-12-31 23:59:59';//最大时间
			this.maxdate	= maxv;
			
			this.max	= this.shijienges(this.maxdate)
			this.min	= this.shijienges(this.mindate);
		};
		this.showtoday	= function(){
			var val			= this.value;
			if(this.inputid!='')val=$('#'+this.inputid+'').val();
			if(this.inputobj)val=this.inputobj.value;
			if(this.view.indexOf('date')>-1 && val){
				if(!this.isdate(val))val	= js.now('now');
			}
			if(this.view=='time'){
				if(val==''){val=js.now('now');}else{val=js.now('Y-m-d')+' '+val;}
			}
			if(val=='')val 	= js.now(this.view=='datetime' ? 'Y-m-d H:i:00' : this.format, val);
			this.nowtext= val;
			this.now 	= this.shijienges(val);
		};
		this.isdate	= function(sj){
			if(!sj)return false;
			var 	bo = /^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}/.test(sj);
			return bo;
		};
		this.setcontent=function(){
			var h = $('body,html').height();
			if(h<winHb())h=winHb();
		
			var s = '<div pickermobile="qx" id="pickermobile_div0" style="background:rgba(0,0,0,0.5);width:100%;height:'+h+'px;z-index:9;position:absolute;text-align:center;left:0px;top:0px">';
			var inst = 'background:none;border:none;padding:10px 8px;color:#1389D3;font-size:20px';
			s+='</div>';
			s+='<div id="pickermobile_div1" style="width:100%;background:white;position:fixed;top:30%;z-index:10;">';
			s+='<div style="margin:5px;"><table width="100%"><tr>';
			
			if(this.view != 'time'){
				s+='	<td style="padding:5px" align="center">';
				s+='		<div><input type="button" value="＋"  pickermobile="y1" style="'+inst+'"></div>';
				s+='		<div><select id="pickermobile_input_year" style="width:100%;height:30px">'+this.selectoption(this.min.Y,this.max.Y,this.now.Y)+'</select></div>';
				s+='		<div><input type="button" value="－"  pickermobile="y2" style="'+inst+'"></div>';
				s+='	</td>';
				
				s+='	<td style="padding:5px" align="center">';
				s+='		<div><input type="button" value="＋"  pickermobile="m1" style="'+inst+'"></div>';
				s+='		<div><select id="pickermobile_input_month" style="width:100%;height:30px">'+this.selectoption(1,12,this.now.m)+'</select></div>';
				s+='		<div><input type="button" value="－"  pickermobile="m2" style="'+inst+'"></div>';
				s+='	</td>';
				
				s+='	<td style="padding:5px" align="center">';
				s+='		<div><input type="button" value="＋"  pickermobile="d1" style="'+inst+'"></div>';
				s+='		<div><select id="pickermobile_input_day" style="width:100%;height:30px">'+this.selectoption(1,31,this.now.d)+'</select></div>';
				s+='		<div><input type="button" value="－"  pickermobile="d2" style="'+inst+'"></div>';
				s+='	</td>';
				s+='	<td>日</td>';
			}
			if(this.view=='datetime' || this.view=='time'){
				
				s+='	<td style="padding:5px" align="center">';
				s+='		<div><input type="button" value="＋"  pickermobile="h1" style="'+inst+'"></div>';
				s+='		<div><select id="pickermobile_input_hour" style="width:100%;height:30px">'+this.selectoption(0,23,this.now.H)+'</select></div>';
				s+='		<div><input type="button" value="－"  pickermobile="h2" style="'+inst+'"></div>';
				s+='	</td>';
				
				s+='	<td>:</td>';
				s+='	<td style="padding:5px" align="center">';
				s+='		<div><input type="button" value="＋"  pickermobile="i1" style="'+inst+'"></div>';
				s+='		<div><select id="pickermobile_input_miners" style="width:100%;height:30px">'+this.selectoption(0,59,this.now.i)+'</select></div>';
				s+='		<div><input type="button" value="－"  pickermobile="i2" style="'+inst+'"></div>';
				s+='	</td>';
			
			}
			s+='</tr>';
			
			s+='</table></div>';
			s+='<div style="padding-bottom:20px"><table width="100%"><tr><td width="25%" align="center"><input type="button" value="清空"  pickermobile="qk" style="background:none;border:none;padding:5px 10px;color:#888888"></td><td width="25%" align="center"><input type="button" value="现在"  pickermobile="now" style="background:none;border:none;padding:5px 10px;color:#1389D3"></td><td width="25%" align="center"><input type="button" value="确定" pickermobile="ok"  style="background:none;border:none;padding:5px 10px;color:#1389D3"></td><td width="25%" align="center"><input type="button" value="取消" pickermobile="qx"  style="background:none;border:none;padding:5px 10px;color:#888888"></td></tr></table></div>';
			s+='</div>';
			$('body').append(s);
			$('[pickermobile]').click(function(){
				var lx = $(this).attr('pickermobile');
				me.clickbtn(lx);
			});
			this._changeday();
			$('#pickermobile_input_year').change(function(){
				me._changeday();
			});
			$('#pickermobile_input_month').change(function(){
				me._changeday();
			});
		};
		this.shijienges	= function(sj){
			var Y=2017,m=1,d=17,H=0,i=0,s=0,ss1,ss2,ss3,total=0;
			ss1 = sj.split(' ');
			ss2	= ss1[0].split('-');
			Y	= parseFloat(ss2[0]);
			if(ss2.length>1)m= parseFloat(ss2[1]);
			if(ss2.length>2)d= parseFloat(ss2[2]);
			if(ss1[1]){
				ss3	= ss1[1].split(':');
				H	= parseFloat(ss3[0]);
				i	= parseFloat(ss3[1]);
				if(ss3.length>2)s= parseFloat(ss3[2]);
			}
			total	= parseFloat(''+Y+''+this.sa(m)+''+this.sa(d)+''+this.sa(H)+''+this.sa(i)+''+this.sa(s)+'');
			return {Y:Y,m:m,d:d,H:H,i:i,s:s,total:total};
		};
		this._changeday=function(){
			var o = get('pickermobile_input_day');
			if(!o)return;
			var Y = parseFloat(get('pickermobile_input_year').value);
			var m = parseFloat(get('pickermobile_input_month').value);
			var def= parseFloat(o.value);
			var max	= me.marr[m-1];//这个月最大天数
			if(Y%4==0&&m==2)max=29;//判断是不是轮年
			if(def>max)def=max;
			o.length = 0;
			var oi;
			for(var i=1;i<=max;i++){
				oi = this.sa(i);
				o.options.add(new Option(oi,oi));
			}
			o.value = this.sa(def);
		};
		this.cancal=function(){
			$('#pickermobile_div0').remove();
			$('#pickermobile_div1').remove();
		};
		this.clickbtn=function(lx){
			if(lx=='qx')this.cancal();
			if(lx=='y1'||lx=='y2')this.addjian('year', lx, this.min.Y,this.max.Y);
			if(lx=='m1'||lx=='m2')this.addjian('month', lx, 1,12);
			if(lx=='d1'||lx=='d2')this.addjian('day', lx, 1,31);
			if(lx=='h1'||lx=='h2')this.addjian('hour', lx, 0,23);
			if(lx=='i1'||lx=='i2')this.addjian('miners', lx, 0,59);
			if(lx=='now')this.getnow();
			if(lx=='ok')this.queding();
			if(lx=='qk')this.clearo();
		};
		this.addjian=function(inp, lx,min,max){
			var jg =1;if(lx.indexOf('2')>0)jg=-1;
			var o = get('pickermobile_input_'+inp+'');
			if(!o)return;
			var ye = parseFloat(o.value);
			var jgs= ye+jg;
			if(jgs<min)jgs=max;
			if(jgs>max)jgs=min;
			o.value = this.sa(jgs);
			if(inp=='year'||inp=='month')this._changeday();
		};
		this.queding=function(){
			var Y=2017,m=1,d=17,H=16,i=26,s=0,o;
			var val = this.format;
			o = get('pickermobile_input_year');if(o)Y=o.value;
			o = get('pickermobile_input_month');if(o)m=o.value;
			o = get('pickermobile_input_day');if(o)d=o.value;
			o = get('pickermobile_input_hour');if(o)H=o.value;
			o = get('pickermobile_input_miners');if(o)i=o.value;
			val = val.replace('Y', this.sa(Y));
			val = val.replace('m', this.sa(m));
			val = val.replace('d', this.sa(d));
			val = val.replace('H', this.sa(H));
			val = val.replace('i', this.sa(i));
			val = val.replace('s', this.sa(s));
			var nobj = false;
			if(this.inputid&&get(this.inputid)){
				nobj = get(this.inputid);
			}
			if(this.inputobj){
				nobj = this.inputobj;
			}
			if(nobj){
				nobj.value=val;
				nobj.focus();
				js.onchangedate(nobj.name, nobj, val, this);
			}
			this.cancal();
		};
		this.clearo=function(){
			var val='',nobj = false;
			if(this.inputid&&get(this.inputid)){
				nobj = get(this.inputid);
			}
			if(this.inputobj){
				nobj = this.inputobj;
			}
			if(nobj){
				nobj.value=val;
				nobj.focus();
				js.onchangedate(nobj.name, nobj, val, this);
			}
			this.cancal();
		};
		this.sa	= function(v){
			v		= parseFloat(v);
			var v1	= ''+v+'';
			if(v<10)v1='0'+v+'';
			return v1;
		};
		this.getnow=function(){
			var now = this.shijienges(js.now('now')),mon= this.inputarr,mons= this.inputkey,i,o;
			for(i=0;i<mon.length;i++){
				o = get('pickermobile_input_'+mon[i]+'');
				if(o)o.value = this.sa(now[mons[i]]);
			}
			this.queding();
		};
		this.selectoption=function(min,max,dev){
			var s='',oi,sel;
			for(var i=min;i<=max;i++){
				oi = this.sa(i);
				sel= (dev && oi==this.sa(dev))?'selected':'';
				s='<option '+sel+' value="'+oi+'">'+oi+'</option>'+s+'';
			}
			return s;
		}
	};
	
	$.rockdatepicker_mobile = function(options){
		var defaultVal = {
			itemsclick:function(){},onshow:function(){},
			inputid:'',
			value:'',inputobj:false,
			format:'Y-m-d',view:'datetime'
		};
		
		var can		= $.extend({}, defaultVal, options);
		var aobj	= new rockdatepicker_mobile(can);
		aobj.init();
		return aobj;
	};
})(jQuery); 