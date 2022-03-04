<?php 
/**
*	桌面首页项(考勤统计)
*/
defined('HOST') or die ('not access');

?>
<script>

homeobject.show_kqtotal_list=function(arr){
	if(typeof(echarts)=='undefined')return;
	if(!this.myChartkqtotal)this.myChartkqtotal = echarts.init(get('kqtotal_list{rand}'));
	var option = {
		tooltip: {
			trigger: 'item',
			formatter: "{a} <br/>{b}: {c} ({d}%)"
		},
		legend: {
			orient: 'vertical',
			x: 'right',
			data:arr.data
		},
		series: [
			{
				name:'出勤情况',
				type:'pie',
				radius: ['50%', '70%'],
				itemStyle:{
					normal:{
						//color:function(params){
						//	return params.data.color;
						//}
					}
				},
				data:arr.rows
			}
		]
	};
	this.myChartkqtotal.setOption(option);
}
</script>
<div class="panel panel-default">
  <div class="panel-heading">
	<div class="panel-title"><?=$itemnowname?>(<?=$rock->date?>)</div>
  </div>
  <div class="panel-body">
		<div id="kqtotal_list{rand}" style="height:250px;"></div>
  </div>
</div>