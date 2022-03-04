/**
	rockdatepicker 时间选择插件
	caratename：chenxihu
	caratetime：2014-05-13 21:40:00
	email:qqqq2900@126.com
	homepage:www.xh829.com
*/

(function ($) {
	js.onchangedate = function(){}; //选择时间回调
	function rockdatepicker(elet, options){
		var obj		= $(elet);
		var can		= options;
		var rand	= js.getrand(); 
		var me		= this;
		var timeas	= null;
		this.rand	= rand;
		
		this.nY		= 2014;//当前月份
		this.nm		= 5;
		this.marr	= [31,28,31,30,31,30,31,31,30,31,30,31];
		this.weekarr= ['日','一','二','三','四','五','六'];
		
		//初始化
		this.init	= function(){
			this.initdevvalue();
			obj[can.trigger](function(){
				me.setcontent();
				return false;
			});
			if(can.initshow){
				me.setcontent();
			};
			obj.attr('rockdatepickerbool','true');
			if(!can.editable)elet.readOnly =true;
		};
		this.initdevvalue	= function(){
			if(can.view=='date')can.format='Y-m-d';
			if(can.view=='year')can.format='Y';
			if(can.view=='month')can.format='Y-m';
			if(can.view=='datetime')can.format='Y-m-d H:i:s';
			if(can.view=='time')can.format='H:i:s';
			if(can.formats)can.format = can.formats;
			var lx		= can.format;
			if(lx=='H:i:00'||lx=='H:i'||lx=='i:s')can.view='time';
			var minv	= can.mindate;
			if(isempt(minv))minv= obj.attr('mindate');
			if(isempt(minv))minv= '1930-01-01 00:00:00';//最小时间
			can.mindate	= minv;
			var maxv	= can.maxdate;
			if(isempt(maxv))maxv= obj.attr('maxdate');
			if(isempt(maxv))maxv= '2050-12-31 23:59:59';//最大时间
			can.maxdate	= maxv;
			
			this.max	= this.shijienges(can.maxdate)
			this.min	= this.shijienges(can.mindate);
		};
		this.showtoday	= function(){
			this.todatetext	= this.formdt('now');
			this.todate		= this.shijienges(this.todatetext);
			var val			= obj.val();
			if(can.inputid!='')val=$('#'+can.inputid+'').val();
			if(can.view.indexOf('date')>-1 && val){
				if(!this.isdate(val))val	= this.todatetext;
			}
			if(can.view=='time'){
				if(val==''){val=js.now('now');}else{val=js.now('Y-m-d')+' '+val;}
			}
			if(val=='')val 	= this.formdt(can.view=='datetime' ? 'Y-m-d H:i:00' : can.format, val);
			this.nowtext= val;
			this.now 	= this.shijienges(val);
		};
		this.shijienges	= function(sj){
			var Y=2014,m=1,d=1,H=0,i=0,s=0,ss1,ss2,ss3,total=0;
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
		this.sa	= function(v){
			v		= parseFloat(v);
			var v1	= ''+v+'';
			if(v<10)v1='0'+v+'';
			return v1;
		};
		this.createbasic	= function(w, h){
			var s= '';
			s+= '<div class="rockdatepicker" id="rockdatepicker_'+rand+'" style="width:'+w+'px;height:'+h+'px;overflow:hidden;position:absolute;display:none;"></div>';
			$('body').prepend(s);
			
			var oac	= $('#rockdatepicker_'+rand+'');
			oac.show();
			this.setweizhi();
			setTimeout(function(){js.addbody(rand, 'remove', 'rockdatepicker_'+rand+''); },100);
			return oac;
		};
		this.setView	= function(vis){
			can.view = vis;
			this.initdevvalue();
		};
		this.setcontent	= function(){
			this.showtoday();
			$("div[class='rockdatepicker']").remove();
			if(can.view =='month' || can.format=='Y-m'){
				this.createmontview(1);
				return false;
			}
			if(can.view =='year' || can.format=='Y'){
				this.createmontview(0);
				return false;
			}
			
			var s= '',oi=0,w=270,h=278;
			if(can.view!='time'){
				s+='	<div style="background:#eeeeee;height:30px;overflow:hidden">';
				s+='	<table border="0" width="100%" cellspacing="0" cellpadding="0"><tr>';
				s+='		<td style="padding:0px 4px" class="td00" tdaddclick="-y" title="上一年">〈<td>';
				s+='		<td height="30" style="padding:0px 5px" nowrap><span class="rockdatepicker_span" id="rockdatepicker_year'+rand+'">2014</span>年<td>';
				s+='		<td style="padding:0px 4px" class="td00" tdaddclick="y" title="下一年">〉<td>';
				s+='		<td style="padding:0px 2px"><td>';
				s+='		<td style="padding:0px 4px" class="td00" tdaddclick="-m" title="上个月">〈<td>';
				s+='		<td height="30" style="padding:0px 5px" nowrap><span lass="rockdatepicker_span" id="rockdatepicker_month'+rand+'">06</span>月<td>';
				s+='		<td style="padding:0px 4px" class="td00" tdaddclick="m" title="下个月">〉<td>';
				s+='		<td style="padding:0px 4px" width="30%">&nbsp;<td>';
				s+='		<td style="padding:0px 4px" class="td00" nowrap tdaddclick="today" title="当月">&nbsp;当月&nbsp;<td>';
				s+='	</tr></table>';
				s+='	</div>';
				
				s+='	<div style="height:188px;overflow:hidden" id="rockdatepicker_table'+rand+'" >';
				s+='	<table border="0" class="rockdatepicker_table"  style="border-collapse:collapse" width="100%" cellspacing="0" cellpadding="0">';
				s+='	<tr height="30" bgcolor="#dedede">';
				for(var d=0; d<7; d++){
					s+='<td align="center" width="14.28%">'+this.weekarr[d]+'</td>';
				}
				s+='	</tr>';
				for(var r=1; r<=6; r++){
					s+='<tr height="26">';
						for(var d=1; d<=7; d++){
							oi++;
							s+='<td align="center" xu="'+oi+'" temp="nr">'+oi+'</td>';
						}
					s+='</tr>';
				}
				s+='	</table>';
				s+='	</div>';
			}else{
				s+='<div id="rockdatepicker_table'+rand+'" style="height:140px;overflow:hidden">';
				s+='</div>';
				w = 220;h=200;
			}
			
			s+='	<div style="line-height:30px">&nbsp; <font color="#888888">选择：</font><span id="rockdatepicker_span'+rand+'"></span>';
			s+='		<span><input min="0" max="23" onfocus="js.focusval=this.value"  onblur="js.number(this)" id="rockdatepicker_input_h'+rand+'" style="width:24px;text-align:center;height:20px;line-height:16px;border:1px #cccccc solid;background:none" value="00" maxlength="2"></span>:';
			s+='		<span><input min="0" max="59" onfocus="js.focusval=this.value"  onblur="js.number(this)" id="rockdatepicker_input_i'+rand+'" style="width:24px;text-align:center;height:20px;line-height:16px;border:1px #cccccc solid;background:none" value="00" maxlength="2"></span>:';
			s+='		<span><input min="0" max="59" onfocus="js.focusval=this.value"  onblur="js.number(this)" id="rockdatepicker_input_s'+rand+'" style="width:24px;text-align:center;height:20px;line-height:16px;border:1px #cccccc solid;background:none" value="00" maxlength="2"></span>';
			s+=		'</div>';
			s+='	<div style="height:30px;overflow:hidden;text-align:right;background:#eeeeee;line-height:28px">';
			s+='		<a href="javascript:;" class="a" id="rockdatepicker_clear'+rand+'">清空</a>&nbsp; ';
			s+='		<a href="javascript:;" class="a" id="rockdatepicker_now'+rand+'">现在</a>&nbsp; ';
			s+='		<a href="javascript:;" class="a" id="rockdatepicker_queding'+rand+'">确定</a>&nbsp; ';
			s+='		<a href="javascript:;" class="a" id="rockdatepicker_close'+rand+'">关闭</a>&nbsp; ';
			s+='	</div>';
			
			
			var oac	= this.createbasic(w,h);
			oac.html(s);
			
			this.objtd	= oac.find("td[temp='nr']");
			oac.find("td[tdaddclick]").click(function(){
				me.changedatec($(this));
			});
			this.objtd.click(function(){
				me.tdclick(this);
			});
			this.setcontentinit();
			$('#rockdatepicker_close'+rand+'').click(function(){
				me.hidemenu();
			});
			$('#rockdatepicker_queding'+rand+'').click(function(){
				me.queding();
			});
			$('#rockdatepicker_now'+rand+'').click(function(){
				me.quenow();
			});
			$('#rockdatepicker_clear'+rand+'').click(function(){
				me.queclear();
			});
			$('#rockdatepicker_table'+rand+'').dblclick(function(){
				me.queding();
			});
			$('#rockdatepicker_table'+rand+'').mouseover(function(){
				me.hidefudong();
			});
			if(can.view!='time'){
				this.setcontentinit();
				this.addcale(this.now.Y, this.now.m);
			}else{
				$('#rockdatepicker_span'+rand+'').hide();
			}				
			this.shetispannvel(0);
		};
		this.shetispannvel	= function(lx){
			$('#rockdatepicker_span'+rand+'').html(''+this.now.Y+'-'+this.sa(this.now.m)+'-'+this.sa(this.now.d)+'');
			var ho	= $('#rockdatepicker_input_h'+rand+'');
			var io	= $('#rockdatepicker_input_i'+rand+'');
			var so	= $('#rockdatepicker_input_s'+rand+'');
			ho.val(this.sa(this.now.H));
			io.val(this.sa(this.now.i));
			so.val(this.sa(this.now.s));
			
			if(can.format.indexOf('H')<0){
				get('rockdatepicker_input_h'+rand+'').disabled=true;
			}else{
				if(lx==0)this.shetispannvelbulr('h');
			}		
			if(can.format.indexOf('i')<0){
				get('rockdatepicker_input_i'+rand+'').disabled=true;
			}else{
				if(lx==0)this.shetispannvelbulr('i');
			}	
			
			if(can.format.indexOf('s')<0){
				get('rockdatepicker_input_s'+rand+'').disabled=true;
			}else{
				if(lx==0)this.shetispannvelbulr('s');
			}			
		};
		this.shetispannvelbulr	= function(lx){
			var o	= $('#rockdatepicker_input_'+lx+''+rand+'');
			o.blur(function(){
				me.blurnum(this);
			});
			o.focus(function(){
				me.foucsnum(this);
			});
		};
		this.setcontentinit = function(){
			$('#rockdatepicker_year'+rand+'').parent().click(function(){
				me.changeyear(this);
			});
			$('#rockdatepicker_month'+rand+'').parent().click(function(){
				me.changemonth(this);
			});
		};
		//选择年的
		this.changeyear=function(o1){
			this.hidefudong();
			var o = $(o1);
			var off = o.offset();
			var s='<div class="rockdatepicker_fudong" id="rockdatepicker_fudong'+rand+'" style="left:'+(off.left-this.mleft-5)+'px;top:'+((off.top-this.mtop)+25)+'px;height:200px;width:70px">';
			var xuoi	= 0,oi=0;
			for(var i=this.max.Y; i>=this.min.Y; i--){
				oi++;
				var cls= '';
				if(i==this.Y){
					cls='div01';
					xuoi = oi;
				}	
				s+='<div class="'+cls+'">'+i+'</div>';
			}
			s+='</div>';
			$('#rockdatepicker_'+rand+'').append(s);
			$('#rockdatepicker_fudong'+rand+'').scrollTop(xuoi*20);
			$('#rockdatepicker_fudong'+rand+'').find('div').click(function(){
				me.changeyeara(this);
			});
		};
		//选择年的
		this.changemonth=function(o1){
			this.hidefudong();
			var o = $(o1);
			var off = o.offset();
			var s='<div class="rockdatepicker_fudong" id="rockdatepicker_fudong'+rand+'" style="left:'+(off.left-this.mleft-5)+'px;top:'+((off.top-this.mtop)+25)+'px;height:200px;width:60px">';
			var xuoi	= 0,oi=0;
			for(var i=1; i<=12; i++){
				oi++;
				var cls= '';
				if(i==this.m){
					cls='div01';
					xuoi = oi;
				}	
				s+='<div class="'+cls+'">'+i+'</div>';
			}
			s+='</div>';
			$('#rockdatepicker_'+rand+'').append(s);
			$('#rockdatepicker_fudong'+rand+'').scrollTop(xuoi*20);
			$('#rockdatepicker_fudong'+rand+'').find('div').click(function(){
				me.changemontha(this);
			});
		};
		this.hidefudong		= function(){
			$('#rockdatepicker_fudong'+rand+'').remove();
		};
		this.changeyeara	= function(o1){
			$('#rockdatepicker_year'+rand+'').html($(o1).html());
			this.selchagnge();
		};
		this.changemontha	= function(o1){
			$('#rockdatepicker_month'+rand+'').html($(o1).html());
			this.selchagnge();
		};
		this.selchagnge=function(){
			var Y=parseFloat($('#rockdatepicker_year'+rand+'').html());
			var m=parseFloat($('#rockdatepicker_month'+rand+'').html());
			this.addcale(Y,m);
			me.hidefudong();
		};
		this.setweizhi = function(){
			var off		= obj.offset();;
			if(can.inputid != '')off = $('#'+can.inputid+'').offset();
			var o		= $('#rockdatepicker_'+rand+'');
			var nh		= get('rockdatepicker_'+rand+'').clientHeight,
				nw		= get('rockdatepicker_'+rand+'').clientWidth,
				t		= off.top+can.top,
				dy		= t+nh-winHb()-$(document).scrollTop(),
				l		= off.left+can.left,
				jl		= l+nw-winWb(),
				jl1		= 5;
			if($('body,html').height()>winHb())jl1=22;
			jl=jl+jl1;
			if(dy>0)t=t-dy-5;
			if(jl>0)l=l-jl;
			this.mleft	= l;
			this.mtop	= t;
			o.css({'left':''+l+'px','top':''+t+'px'});
		};
		//单元格单击
		this.tdclick	= function(o){
			var o1	= $(o);
			var d	= o1.text();
			if(isempt(d))return;
			this.now.Y	= parseFloat(this.Y);
			this.now.m	= parseFloat(this.m);
			this.now.d	= parseFloat(d);
			this.objtd.removeClass();
			this.objtd.addClass('td00');
			o.className='td01';
			this.shetispannvel(1);
		};
		//确定
		this.queding	= function(){
			var jg	= $('#rockdatepicker_span'+rand+'').html();
			if(can.view=='time')jg=js.now('Y-m-d');
			var ho	= get('rockdatepicker_input_h'+rand+'');
			var io	= get('rockdatepicker_input_i'+rand+'');
			var so	= get('rockdatepicker_input_s'+rand+'');
			if(ho)if(!ho.disabled)jg+=' '+ho.value+'';
			if(io)if(!io.disabled)jg+=':'+io.value+'';
			if(so)if(!so.disabled)jg+=':'+so.value+'';
			var val	= jg;
			if(this.isdate(val)){
				val=this.formdt(can.format, val);
			}	
			this.setValue(val);
		};
		this.quenow = function(){
			var val = this.formdt(can.format);
			this.setValue(val);
		};
		this.setValue 	= function(v){
			var nobj = false;
			if(can.inputid!=''){
				nobj = get(can.inputid);
				$('#'+can.inputid+'').val(v).focus();;
			}else{
				nobj = elet;
				obj.val(v).focus();;
			}
			if(nobj){
				js.onchangedate(nobj.name, nobj, v, this);
			}
			can.itemsclick(v, this);
			this.hidemenu();
		};
		this.getValue 	= function(){
			var s = '';
			if(can.inputid!=''){
				s = $('#'+can.inputid+'').val();;
			}else{
				s = obj.val();
			}
			return s;
		};
		this.queclear	= function(){
			this.setValue('');
		};
		//单击
		this.itemsclick = function(o,event){
			
		};
		this.hidemenu	= function(){
			$('#rockdatepicker_'+rand+'').remove();
		};
		this.changedatec= function(o){
			var lx	= o.attr('tdaddclick');
			if(lx=='m'){
				this.plftmonth(1);
			}
			if(lx=='-m'){
				this.plftmonth(-1);
			}
			if(lx=='y'){
				this.plftyear(1);
			}
			if(lx=='-y'){
				this.plftyear(-1);
			}
			if(lx =='today'){
				this.addcale(this.todate.Y,this.todate.m);
			}
		};
		//上个月
		this.plftmonth=function(lx)
		{
			var Y=parseFloat(this.Y),m=parseFloat(this.m);
			m=m+lx;
			if(m==0)m=12;
			if(m==13)m=1;
			if(m==12&&lx==-1)Y--;
			if(m==1&&lx==1)Y++;
			this.addcale(Y,m);
		};
		this.plftyear=function(lx){
			var Y=parseFloat(this.Y)+lx;
			this.addcale(Y,this.m);
		};
		
		this.formdt=function(type,sj){
			return js.now(type, sj);
		};
		this.isdate	= function(sj){
			if(!sj)return false;
			var 	bo = /^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}/.test(sj);
			return bo;
		};
		this.addcale	= function(Y,m){
			this.objtd.removeClass();
			this.objtd.html('');
			me.Y=parseFloat(Y);
			me.m=parseFloat(m);
			var first	= ''+Y+'-'+m+'-01';
			var stuat	= me.formdt('Y-m-w',first);
			stuat=stuat.split('-');
			var year	= parseFloat(stuat[0]);
			var month	= parseFloat(stuat[1]);
			var maxday	= me.marr[month-1];//这个月最大天数
			if(year%4==0&&month==2)maxday=29;//判断是不是轮年
			if(month<10)month='0'+month;
			var ic=parseFloat(stuat[2]);
			var maic=1;
			var xq	= 0,nic=ic-1;
			var xqarr=[0,0,0,0,0,0,0];
			var cls	= '';
			for(var i=0;i<maxday;i++){
				maic=i+ic;
				var o	= me.objtd[maic];
				var d	= i+1;
				o.innerHTML=''+d+'';
				cls		= 'td00';
				if(d== this.now.d)cls='td01';
				o.className	= cls;
			}
			$('#rockdatepicker_year'+rand+'').html(Y);
			$('#rockdatepicker_month'+rand+'').html(month);
		};
		this.focusval	= 0;
		this.blurnum		= function(o){
			var o1	= $(o);
			var val	= o.value;
			var mi	= parseFloat(o1.attr('min'));
			var ma	= parseFloat(o1.attr('max'));
			if(isNaN(val)||!val)val=this.focusval;
			val=parseFloat(val);
			if(val<mi)val=mi;
			if(val>ma)val=ma;
			o.value=this.sa(val);
			this.setoutshow=setTimeout("$('#rockdatepicker_spanselfaei"+rand+"').remove();",200);
		};
		this.foucsnum=function(o){
			clearTimeout(this.setoutshow);
			this.focusval	= o.value;
			var o1	= $(o);
			var mi	= parseFloat(o1.attr('min'));
			var ma	= parseFloat(o1.attr('max'));
			o.select();
			$('#rockdatepicker_spanselfaei'+rand+'').remove();
			var s='<div style="bottom:52px;position:absolute;right:1px;padding:2px;border:1px #cccccc solid;background-color:#ffffff;font-size:14px;text-align:left" id="rockdatepicker_spanselfaei'+rand+'">';
			this.inputhis=o;
			for(var a=mi;a<=ma;a++){
				var ai	= this.sa(a);
				if(ai==o.value)ai='<span style="color:#ff0000">'+ai+'</span>';
				s+='<font style="margin:2px">'+ai+'</font>';
				if((a+1)%10==0)s+='<br>';
			}
			s+='</div>';
			$('#rockdatepicker_'+rand+'').append(s);
			$('#rockdatepicker_spanselfaei'+rand+'').find('font').click(function(){
				var x	= $(this).text();
				o.value	= x;
			});
		};
			
		
		this.createmontview	= function(lx){
			var w	= 220,w1=109;
			if(lx==0){
				w=130;
				w1=w;
			}	
			var oac	= this.createbasic(w,270);
			var	s	= '';
			s+='<table border="0" width="100%" id="rockdatepicker_table'+rand+'" cellspacing="0" cellpadding="0"><tr valign="top">';
			s+=' <td width="'+w1+'"><div align="center" style="line-height:30px;background:#eeeeee"><a href="javascript:" id="rockdatepicker_yearshang'+rand+'" onclick="return false" class="a02">←</a>&nbsp; 年份&nbsp; <a href="javascript:" id="rockdatepicker_yearxia'+rand+'" onclick="return false" class="a02">→</a> </div><div id="rockdatepicker_yearlist'+rand+'" style="line-height:30px;height:180px;overflow:hidden" align="center"></div></td>';
			if(lx == 1){
				s+=' <td width="2" bgcolor="#cccccc"></td>';
				s+=' <td width="109"><div align="center" style="line-height:30px;background:#eeeeee">月份</div><div id="rockdatepicker_monthlist'+rand+'" style="line-height:30px" align="center"></div></td>';
			}
			s+='</tr></table>';
			s+='<div style="line-height:30px">&nbsp; <font color="#888888">选择：</font><span id="rockdatepicker_span'+rand+'">'+this.now.Y+'-0'+this.now.d+'</span></div>';
			s+='<div style="height:30px;overflow:hidden;text-align:right;background:#eeeeee;line-height:28px">';
			s+='	<a href="javascript:" onclick="return false" class="a" id="rockdatepicker_clear'+rand+'">清空</a>';
			s+='	<a href="javascript:" onclick="return false" class="a" id="rockdatepicker_now'+rand+'">现在</a>';
			s+='	<a href="javascript:" onclick="return false" class="a" id="rockdatepicker_queding'+rand+'">确定</a>';
			s+='	<a href="javascript:" onclick="return false" class="a" id="rockdatepicker_close'+rand+'">关闭</a>';
			s+='</div>';
			oac.html(s);
			$('#rockdatepicker_close'+rand+'').click(function(){
				me.hidemenu();
			});
			$('#rockdatepicker_queding'+rand+'').click(function(){
				me.queding();
			});
			$('#rockdatepicker_now'+rand+'').click(function(){
				me.quenow();
			});
			$('#rockdatepicker_clear'+rand+'').click(function(){
				me.queclear();
			});
			$('#rockdatepicker_yearshang'+rand+'').click(function(){
				me.montviewyear(me.montviewyearmin-1,1);
			});
			$('#rockdatepicker_yearxia'+rand+'').click(function(){
				me.montviewyear(me.montviewyearax+1,2);
			});
			$('#rockdatepicker_table'+rand+'').dblclick(function(){
				me.queding();
			});
			if(lx == 1)this.montviewmonth();
			this.montviewyear(this.now.Y,0);
			this.showviewffwfwe();
		};
		this.montviewmonth	= function(){
			var s	= '';
			for(var i=1; i<=12; i++){
				var oi	= ''+i+'';
				if(i<10)oi	= '0'+i+'';
				var cls	= 'a02';
				if(i==this.now.m)cls='a03';
				var deval	= parseFloat(''+this.now.Y+''+this.sa(i)+'01000000');
				if(deval<this.min.total || deval>this.max.total)cls+=' not';
				s+='<a href="javascript:" onclick="return false" class="'+cls+'">'+oi+'月</a> ';
				if(i%2==0)s+='<br>';
			}
			if(s=='')return false;
			var oss	= $('#rockdatepicker_monthlist'+rand+'');
			oss.html(s);
			oss.find('a').click(function(){
				me.montviewyearcheng(this,1);
			});
		};
		this.montviewyear	= function(y,lx){
			var min	= y - 5;
			var max	= y + 6;
			if(lx==1){
				max	= y;
				min	= y-11;
			}
			if(lx==2){
				min	= y;
				max	= y+11;
			}
			if(min<this.min.Y)min	= this.min.Y;
			if(max>this.max.Y)max	= this.max.Y;
			var oi	= 0,s='',cls='';
			for(var i=min; i<=max; i++){
				if(oi==0)this.montviewyearmin	= i;
				this.montviewyearax	= i;
				cls	= 'a02';
				if(i==this.now.Y)cls='a03';
				oi++;
				s+='<a href="javascript:" onclick="return false" class="'+cls+'">'+i+'</a> ';
				if(oi%2==0)s+='<br>';
			}
			if(s=='')return false;
			var oss	= $('#rockdatepicker_yearlist'+rand+'');
			oss.html(s);
			oss.find('a').click(function(){
				me.montviewyearcheng(this,0);
			});
		};
		this.montviewyearcheng	= function(o1,lx){
			if(o1.className.indexOf('not')>-1)return false;
			var ossa	= $(o1).parent().find('a');
			for(var i=0;i<ossa.length;i++){
				var cls	= ossa[i].className.replace('a03','a02');
				ossa[i].className	= cls;
			}
			o1.className='a03';
			var val	= o1.innerHTML.replace('月','');
			if(lx==0){
				this.now.Y	= parseFloat(val);
				if(get('rockdatepicker_monthlist'+rand+''))this.montviewmonth();
			}
			if(lx==1){
				this.now.m	= parseFloat(val);
			}
			this.showviewffwfwe();
		};
		this.showviewffwfwe=function(){
			var m	= this.now.m;
			var y	= this.now.Y;
			if(m<10)m='0'+m+'';
			var s	= ''+y+'-'+m+'';
			if(!get('rockdatepicker_monthlist'+rand+''))s=y;
			$('#rockdatepicker_span'+rand+'').html(s);
		}
	};
	
	$.fn.rockdatepicker = function(options){
		var defaultVal = {
			left:2,top:28,width:0,autohide:true,
			itemsclick:function(){},onshow:function(){},initshow:false,removebo:false,
			trigger:'click',editable:false,inputid:'',format:'Y-m-d',formats:'',maxdate:'',mindate:'',view:'date'
		};
		
		var o	= $(this);
		if(o.attr('rockdatepickerbool')=='true')return false;
		var can		= $.extend({}, defaultVal, options);
		var aobj	= new rockdatepicker(this, can);
		aobj.init();
		return aobj;
	};
})(jQuery); 