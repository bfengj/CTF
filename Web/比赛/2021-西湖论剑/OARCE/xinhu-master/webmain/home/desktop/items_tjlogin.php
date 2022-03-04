<?php 
/**
*	桌面首页项(登录统计)
*/
defined('HOST') or die ('not access');

?>
<script>

homeobject.show_tjlogin_list=function(arr){
	if(typeof(echarts)=='undefined')return;
	if(!this.myCharttjlogina)this.myCharttjlogina = echarts.init(get('tjlogin_list{rand}'));
	console.log(arr);
	var option = {
		tooltip: {
			trigger: 'item',
			formatter: "{b}: {c}人 ({d}%)"
		},
		legend: {
			orient: 'vertical',
			x: 'right',
			data:arr.data
		},
		series: [
			{
		
				type:'pie',
				radius: ['50%', '70%'],
				itemStyle:{
					normal:{
						color:function(params){
							return params.data.color;
						}
					}
				},
				data:arr.rows
			}
		]
	};
	this.myCharttjlogina.setOption(option);
}
</script>
<div class="panel panel-default">
  <div class="panel-heading">
	<div class="panel-title"><?=$itemnowname?>(<?=$rock->date?>)</div>
  </div>
  <div class="panel-body">
		<div id="tjlogin_list{rand}" style="height:250px;"></div>
  </div>
</div>