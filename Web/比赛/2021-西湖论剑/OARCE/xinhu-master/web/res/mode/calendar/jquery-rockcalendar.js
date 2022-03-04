(function ($) {

	var jierixiuxi='',jierishangban='';
	
	function calendarclass(element, options){
		
		var obj = element;
		var can = options;
		var me  = this,
			rand= js.getrand();
		jierixiuxi=can.jierixiuxi;jierishangban=can.jierishangban;
		this.onclick = can.onclick;
		this.mid	= '';
		this.week	= ['日','一','二','三','四','五','六'];
		this.obj	= [];
		this.Y		= 2014;
		this.m		= 1;
		this.nY		= 2014;
		this.nm		= 1;
		this.nd		= 29;
		this.marr	= [31,28,31,30,31,30,31,31,30,31,30,31];
		this.max	= 0;
		this.w		= 0;
		this.nobj	= null;
		this.sterma	= {};
		this.dayobj	= [];
		this.feastarr	= {'0101':'元旦','0214':'情人节','一月初一':'春节','一月十五':'元宵节','七月初七':'七夕','五月初五':'端午节','0501':'劳动节','0601':'儿童节','1001':'国庆节','1111':'光棍日','八月十五':'中秋节','十二月三十':'除夕','十二月初八':'腊八','0801':'建军节','0701':'建党日','0401':'愚人节','0504':'五四青年节','0308':'妇女节','1225':'圣诞节','1224':'平安夜','1031':'万圣节','九月初九':'重阳节','0910':'教师节','0504':'青年节','0312':'植树节','0314':'白色情人节','0315':'消费者权益日','十二月廿三':'小年','0305':'学雷锋日','0422':'世界地球日','0920':'国际爱牙日','0918':'九一八事变','1213':'南京大屠杀日','1201':'世界艾滋病日','1203':'国际残疾人日','0815':'日本投降日'};
		this.tsarr	= {'1144':'感恩节','0520':'母亲节','0630':'父亲节'};
		
		var tgString = '甲乙丙丁戊己庚辛壬癸';
		var dzString = '子丑寅卯辰巳午未申酉戌亥';
		var bool	 = false;	
		
		
		
		this.showYue=function(y,m)
		{
			var g1=4,g2=0;
			var y1=1901,y2=1;
			var jy = y-y1;
			var jm = jy*12+m;
			var a1 = (jm+g1)%10,a2 = (jm+g2)%12;
			return tgString.charAt(a1)+dzString.charAt(a2);
		}
		
		this.showDay=function(y,m,d)
		{
			var g1=5,g2=3;
			var y1=1901,y2=1;
			var jm=0;
			var jd1 = js.now('time','1901-01-01');
			var jd2 = js.now('time',''+y+'-'+m+'-'+d+'');
			jm	= (jd2-jd1)/1000/24/3600;
			
			var a1 = (jm+g1)%10,a2 = (jm+g2)%12;
			return tgString.charAt(a1)+dzString.charAt(a2);
		}
		
		this.init = function(){
			var id   = obj.attr('id');
			this.mid = id;
			var s	= '',bo1r='0';
			if(can.bordercolor!='')bo1r='1';
			s+='<div id="calmain_div'+this.mid+'" style="height:'+can.height+'px" class="jquery-calendar">';
			s+='<table width="100%" border="'+bo1r+'" style="border-collapse:collapse;border-color:'+can.bordercolor+'" height="100%" cellspacing="0"  cellpadding="0">';
			s+='<tr>';
			for(var i=0;i<7;i++){
				s+='<td class="thtext" style="background-color:'+can.headerbgcolor+'" align="center">'+this.week[i]+'</td>';
			}
			s+='</tr>';
			var x	= 0,w = 100/7;
			var h   = can.height-26;
			for(var j=1;j<=6;j++){
				s+='<tr>';
				for(var i=0;i<7;i++){
					x++;
					s+='<td align="'+can.align+'" height="'+(h/6)+'px" valign="'+can.valign+'" width="'+w+'%" class="tdtext" id="calcontabc'+x+'_'+id+'" temp="'+x+'"></td>';
				}
				s+='</tr>';
			} 
			s+='</table></div>';
			obj.html(s);
			var me	= this;
			for(var i=1;i<=42;i++){
				var no	= get('calcontabc'+i+'_'+id+'');
				this.obj[i]=no;
				no.onclick=function(){me.click(this)}
			}
			this.nowmonth(can.month);//当月
		}
		this.nowmonth=function(nmot)
		{
			var dt	= js.now().split('-');
			this.Y	= parseFloat(dt[0]);
			this.m	= parseFloat(dt[1]);
			if(nmot){
				var nmots = nmot.split('-');
				this.Y	= parseFloat(nmots[0]);
				this.m	= parseFloat(nmots[1]);
			}
			this.nd	= parseFloat(dt[2]);
			this.nY	= this.Y;
			this.nm	= this.m;
			this.setcalend();
			return false;
		}
		this.jieqishow = function()
		{
			var sterma={};
			for(var j=1900;j<=2099;j++){
				var gY = j;
				var str	= '';
				for(var i=1;i<=12;i++){
					gm = i;
					var tmp1=lunar.sTerm(gY, gm*2-2);
					var tmp2=lunar.sTerm(gY, gm*2-1);
					sterma[''+this.sa(gm)+''+this.sa(tmp1)+'']=lunar.solarTerm[gm*2-2];
					sterma[''+this.sa(gm)+''+this.sa(tmp2)+'']=lunar.solarTerm[gm*2-1];
					str+='@'+tmp1+','+tmp2+'';
				}
				str = str.substr(1);
				str	= "jieResel["+(j-1900)+"] = \""+str+"\";";
				$('body').append(str);
			}
		}
		this.setcalend=function()
		{
			can.changemonthbefore(this.Y, this.m, this);
			var gY = this.Y,gm = this.m,j1=0;
			this.max=this.marr[gm-1];
			if(gY%4==0&&gm==2)this.max=29;
			this.w	= parseFloat(js.now('w',''+gY+'-'+gm+'-01'));
			var mx	= this.max+this.w;
			var offstaa	= $('#calmain_div'+this.mid+'').offset();
			$("span[temp='showcaentt5eaee"+this.mid+"']").remove();
			//当月24节气名称
			var tmp1=lunar.sTerm(gY, gm*2-2);
			var tmp2=lunar.sTerm(gY, gm*2-1);
			this.sterma={};
			//this.sterma[''+this.sa(gm)+''+this.sa(tmp1)+'']=lunar.solarTerm[gm*2-2];
			//this.sterma[''+this.sa(gm)+''+this.sa(tmp2)+'']=lunar.solarTerm[gm*2-1];

			this.nobj=null;
			var xqarr=[0,0,0,0,0,0,0];
			var oci	 = this.w;
			var savt = '';
			for(var i1=1+this.w;i1<=mx; i1++){
				xqarr[oci]++;
				
				j1++;
				var col1='',col2='#aaaaaa';
				var day	= ''+gY+'-'+this.sa(gm)+'-'+this.sa(j1)+'';
				
				var lun	= lunar.iconv(gY,gm,j1);
				var s2	= lun[2];
				var sn	= s2;
				if(s2=='初一'){
					s2	 =lun[1];
					col2 = '#419900';
				}
				var jie		= '';
				var jiec	= this.getJie(gY,gm,j1,lun[1]+sn);
				//var jiec1	= this.sterma[''+this.sa(gm)+''+this.sa(j1)+''];
				var jiec1	= lun[3];
				var jiec2	= this.tsarr[''+this.sa(gm)+''+xqarr[oci]+''+oci+''];//年第几个星期
				
				if(j1==this.nd){
					this.nobj=this.obj[i1];
					this.obj[i1].style.backgroundColor=can.selbgcolor;
					this.changetoday(j1);
				}else{
					this.obj[i1].style.backgroundColor='';
				}
				
				if(jiec!='')jie+=','+jiec+'';
				
				//节气
				if(jiec1){
					if(jiec1!='清明'){
						col2='#006699';
					}
					jie+=','+jiec1+'';
				}
				if(jiec2){
					this.sterma[''+this.sa(gm)+''+this.sa(j1)+'']=jiec2;
					jie+=','+jiec2+'';
				}
				if(jie!=''){
					jie	= jie.substr(1);
					s2	= jie;
					if(col2=='#aaaaaa')col2= '#ff0000';
					savt+='|'+gY+'-'+gm+'-'+j1+':'+jie+'';
				}
				if(i1%7==0)col1='#ff6600';
				if((i1-1)%7==0)col1='#ff6600';
				this.obj[i1].innerHTML=this.getSpanAcc(j1,s2,col1,col2,day, i1, false);
				
				//是不是休息的
				var xiuval = '';
				if(jierixiuxi.indexOf(day)>=0)xiuval='休';
				if(jierishangban.indexOf(day)>=0)xiuval='班';
				if(xiuval!=''){
					var coac = '#419900';
					if(xiuval=='班')coac='#888888';
					var nest = '<span temp="showcaentt5eaee'+this.mid+'" style="font-size:12px;left:1px;top:1px;background-color:'+coac+';filter:Alpha(Opacity=100);opacity:1;padding:2px;color:#ffffff;position:absolute">'+xiuval+'</span>';
					$(this.obj[i1]).append(nest);
				}
				oci++;
				if(oci==7)oci=0;
			};
			
			if(!can.fillot){
				can.changemonth(this.Y, this.m, this);
				return;
			}	
			//填充其余的
			var lY	= gY,lm	= gm-1,lx=0,lxu=0;
			if(lm==0){
				lY	= lY-1;
				lm	= 12;
			}
			lx	= this.marr[lm-1];
			if(lY%4==0 && lm==2)lx++;//闰年2月29天
			for(var i=this.w;i>=1;i--){
				var day	= ''+lY+'-'+this.sa(lm)+'-'+this.sa(lx)+'';
				
				var lun	= lunar.iconv(lY,lm,lx);
				var s2	= lun[2];
				this.obj[i].innerHTML=this.getSpanAcc(lx,s2,'#cccccc','#cccccc', day,i, true);	
				this.obj[i].style.backgroundColor='';
				
				//是不是休息的
				var xiuval = '';
				if(jierixiuxi.indexOf(day)>=0)xiuval='休';
				if(jierishangban.indexOf(day)>=0)xiuval='班';
				if(xiuval!=''){
					var coac = '#419900';
					if(xiuval=='班')coac='#888888';
					var nest = '<span temp="showcaentt5eaee'+this.mid+'" style="font-size:12px;left:1px;top:1px;background-color:'+coac+';filter:Alpha(Opacity=50);opacity:0.5;padding:2px;color:#ffffff;position:absolute">'+xiuval+'</span>';
					$(this.obj[i]).append(nest);
				}
				lx--;
			}
			
			lm	= gm+1;lY	= gY;
			if(lm==13){
				lY	= lY+1;
				lm	= 1;
			}
			for(var i=j1+1+this.w;i<=42; i++){
				lxu++;
				var day	= ''+lY+'-'+this.sa(lm)+'-'+this.sa(lxu)+'';
				var lun	= lunar.iconv(lY,lm,lxu);
				var s2	= lun[2];
				this.obj[i].innerHTML=this.getSpanAcc(lxu,s2,'#cccccc','#cccccc', day,i, true);	
				this.obj[i].style.backgroundColor='';
				
				//是不是休息的
				var xiuval = '';
				if(jierixiuxi.indexOf(day)>=0)xiuval='休';
				if(jierishangban.indexOf(day)>=0)xiuval='班';
				if(xiuval!=''){
					var coac = '#419900';
					if(xiuval=='班')coac='#888888';
					var nest = '<span temp="showcaentt5eaee'+this.mid+'" style="font-size:12px;position:absolute;left:1px;top:1px;background-color:'+coac+';filter:Alpha(Opacity=50);opacity:0.5;padding:2px;color:#ffffff;">'+xiuval+'</span>';
					$(this.obj[i]).append(nest);
				}
			}
			can.changemonth(this.Y, this.m, this);
		}
		
		this.changetoday=function(d)
		{
			var gY = this.Y,gm = this.m;
			var day = js.now('Y年m月d日 星期W',''+gY+'-'+gm+'-'+d+'');
			var lun	= lunar.iconv(gY, gm, d);
			//alert(day);
			$('#leftday').html(this.sa(d));
			$('#changedate').html(''+day+'');
			$('#lunanday').html(''+gY+'年 农历 '+lun[1]+''+lun[2]+'');
			//什么月
			var yue1 = this.showYue(gY,gm);
			//什么日
			var day1 = this.showDay(gY,gm,d);
			$('#lunanday1').html(''+lun[0]+' '+yue1+'月 '+day1+'日');
			
			var jie		= '';
			var jiec	= this.getJie(gY,gm,d,lun[1]+lun[2]);
			var jiec1	= this.sterma[''+this.sa(gm)+''+this.sa(d)+''];
			
			if(jiec!='')jie+=','+jiec+'';
			if(jiec1)jie+=','+jiec1+'';
			if(jie!=''){
				jie	= jie.substr(1);
			}else{jie='&nbsp;'}
			this.onclick(gY,gm,d, day, lun, jie);
		};

		this.click=function(o1)
		{
			var d 	= parseFloat($(o1).attr('temp'));
			var dc	= d-this.w;
			var da	= $(this.obj[d]).find('span[dt]:eq(0)').html();
			if(!da)return;
				da 	= da.split(',');
			this.nd	= parseFloat(da[0]);
			if(dc<=0){
				if(can.boofan)this.fanmonth(-1);
				return ;
			}
			if(dc>this.max){
				if(can.boofan)this.fanmonth(1);
				return;
			}
			if(this.nobj!=null)this.nobj.style.backgroundColor='';
			this.obj[d].style.backgroundColor=can.selbgcolor;
			this.nobj = this.obj[d];
			this.changetoday(dc);
		};

		this.getSpanAcc=function(s1,s2,col1,col2, day, oi, lbo)
		{
			if(s2.indexOf('国际')==0 || s2.indexOf('世界')==0)col2='#419900';
			var s = '<div><font color='+col1+'>'+s1+'</font><font style="font-size:11px" color='+col2+'>,'+s2+'</font></div>';
			var sq 	= can.renderer(day, s, s1,s2,col1,col2, oi,this);
			if(sq)s = sq;
				  s+= '<span style="display:none" dt="'+s1+'">'+s1+','+day+'</span>';
			this.dayobj[oi]={day:day,d:s1}; 
			if(!can.overShow && lbo)return '';
			return s;
		};
		this.getFistdt	= function(){
			var d = this.dayobj[1];
			return d.day;	
		};
		this.getLastdt	= function(){
			var d = this.dayobj[42];
			return d.day;	
		};
		this.fanyear = function(oi)
		{
			this.Y=this.Y+oi;
			this.setcalend();
			return false;
		};
		
		this.showjie  = function(o,yeas)
		{
			var val	= o.value;
			if(val=='')return;
			this.m	= parseFloat(val);
			this.Y	= yeas;
			this.setcalend();
		};
		
		this.fanmonth = function(oi)
		{
			oi		= parseFloat(oi);
			this.m = this.m+oi;
			if(this.m==0){
				this.m = 12;
				this.Y = this.Y-1;
			}
			if(this.m==13){
				this.m = 1;
				this.Y = this.Y+1;
			}
			this.setcalend();
			return false;
		};
		this.nextMonth = function(){
			this.fanmonth(1);
		};
		this.lastMonth = function(){
			this.fanmonth(-11);
		};
		this.getJie	= function(y,m,d,nr)
		{
			var s1	= this.sa(m)+this.sa(d),s2	= nr;
			var a	= this.feastarr;
			var s	= '';
			if(a[s1]){
				var s3	= a[s1];
				if(y<1949&&m==10&&d==1)s3='';
				if(s3)s+=','+s3+'';
			}
			if(a[s2]){
				s+=','+a[s2]+'';
			}
			if(s!='')s=s.substr(1);
			return s;
		};
		this.sa = function(s)
		{
			s	= ''+s+'';
			if(s.length<=1)s='0'+s+'';
			return s;
		};
		this.changemonth	= function(y1,m1)
		{
			this.Y = parseFloat(y1);
			this.m = parseFloat(m1);
			this.setcalend();
			return false;
		};
		this.setbgcolor		= function(oi, col){
			if(!col)col='';
			this.obj[oi].style.backgroundColor=col;
		};
		this.setMonth		= function(mon)
		{
			var a = mon.split('-');
			this.changemonth(a[0], a[1]);
		}
	}

	$.fn.rockcalendar = function(options){
		var defaultVal = {
			height:400,selbgcolor:'#D3FFF6',month:'',
			fillot:true,renderer:function(){return ''},align:'left',valign:'top',
			changemonth:function(){},boofan:true,onclick:function(){},jierixiuxi:'',jierishangban:'',headerbgcolor:'',
			bordercolor:'',
			overShow:true,
			changemonthbefore:function(){}
		};
		var can		= $.extend({}, defaultVal, options);
		var clsa 	= new calendarclass($(this), can);
		clsa.init();
		return clsa;
	};
		
})(jQuery);	