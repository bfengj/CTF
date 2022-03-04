<?php 
/**
*	桌面首页项(考勤打卡)
*/
defined('HOST') or die ('not access');

?>

<div align="left"  class="list-group">
	<div class="list-group-item  list-group-item-default">
		<i class="icon-time"></i> <?=$itemnowname?>
		<a style="float:right;TEXT-DECORATION:none" href="javascript:;" onclick="moredakajili()">考勤表&gt;&gt;</a>
	</div>
	<div  class="list-group-item">
		<table style="background:none">
		<tr>
			<td width="190">
			<div  align="center">
				<div id="nowtimess" style="font-size:30px">17:16:23</div>
				<div class="blank10"></div>
				<div id="nowtimess1" style="color:#888888">2017年01月17日(星期三)</div>
				<div class="blank10"></div>
				<div><input type="button" id="dabtn{rand}" value="打卡" class="btn btn-success"> &nbsp; <button type="button" onclick="homereload('daka')" class="btn btn-default">刷新</button></div>
			</div>
			</td>
			<td>
				<div class="wrap">今日打卡：<span id="dktime{rand}"></span></div>
				<div id="daklistdtr">
				<div class="blank5"></div>
				<div>上班</div>
				<div class="blank5"></div>
				<div>下班</div>
				</div>
			</td>
		</tr>
	</table>
	</div>
</div>


<script>
//初始化
homeobject.kqdk_init=function(){
	$('#dabtn{rand}').click(function(){
		adddakas(this);
	});
	this.timeshowcishu = 0;
	function adddaka(o,dacs){
		js.ajax('api.php?m=kaoqin&a=adddkjl',dacs, function(d){
			if(d.code==200){
				js.alert('打卡成功：'+d.data+'');
				o.value = '打卡成功';
				homeobject.timeshowcishu=0;
				homereload();
			}else{
				js.msg('msg',d.msg);
				o.disabled = false;
				o.value='重试打卡';
			}
		},'get,json');
	}
	function adddakas(o){
		o.disabled = true;o.value='打卡中...';
		js.cliendsend('getipmac',{},function(ret){
			adddaka(o,{ip:ret.ip,mac:ret.mac});
		},function(){
			adddaka(o,{});
			return true;
		});
	}
}

//数秒显示时间
homeobject.showtime=function(){
	var time = js.serverdt('Y年m月d日(星期W) H:i:s').split(' ');
	$('#nowtimess').html(time[1]);
	$('#nowtimess1').html(time[0]);
	this.timeshowcishu++;
	if(this.timeshowcishu==10){
		var o = get('dabtn{rand}');
		o.disabled=false;
	}
}
homeobject.show_kqdk_list=function(a){
	var sbarr = a.sbarr;
	var s = '',i;
	for(i=0;i<sbarr.length;i++){
		s+='<div class="blank10"></div><div>'+sbarr[i].name+'('+sbarr[i].stime.substr(0,5)+'→'+sbarr[i].etime.substr(0,5)+')：'+sbarr[i].state+'</div>';
	}
	$('#daklistdtr').html(s);
	var dkarr = a.dkarr;
	var s = '',i,oi=1;
	for(i=0;i<dkarr.length;i++){
		s+=','+dkarr[i].dktime+'';
		oi++;
	}
	if(s!='')s=s.substr(1);
	$('#dktime{rand}').html(s);
	get('dabtn{rand}').value='第'+oi+'次打卡';
}
moredakajili=function(){
	addtabs({url:'main,kaoqin,geren',name:'我的考勤表',num:'mykqbiao'});
}
</script>