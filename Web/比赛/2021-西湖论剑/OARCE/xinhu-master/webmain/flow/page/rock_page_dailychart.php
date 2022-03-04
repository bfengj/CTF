<?php
/**
*	模块：daily.工作日报统计分析
*	来源：http://www.rockoa.com/
*/
defined('HOST') or die ('not access');
?>
<script>
$(document).ready(function(){
	var myChart = [],darr=[];
	var c = {
		getparams:function(xu,tfid,nas,chatlx){
			var cans = {
				tablename:'todo',url:js.getajaxurl('flowtotal','flowopt','flow'),modenum:'finfybx',
				params:{atype:'all',total_fields:tfid,total_type:'sum|money'},xuhao:xu,chatlx:chatlx,
				columns:[{
					text:nas,dataIndex:'name'
				},{
					text:'金额',dataIndex:'value'
				},{
					text:'比例',dataIndex:'bili'
				}],
				load:function(a){
					c.loadcharts(this.xuhao,this.chatlx);
				}
			};
			return cans;
		},
		reload:function(o1,lx){
			darr[lx].reload();
		},
		loadcharts:function(oi,tlx){
			if(!tlx)tlx='pie';
			var rows = darr[oi].getData('rows'),i,len=rows.length,v;
			var xAxis=[],data=[];
			for(i=0;i<len;i++){
				if(rows[i].name!='合计'){
					xAxis.push(rows[i].name);
					v = rows[i].value;if(v=='')v=0;
					data.push({value:parseFloat(v),name:rows[i].name});
				}
			}

			if(!myChart[oi])myChart[oi] = echarts.init(get('viewchats'+oi+'_{rand}'));
			var option = {
				title: {
					text: '',
					left: 'center'
				},
				tooltip : {
					trigger: 'item',
					formatter: "{b} : {c}人 ({d}%)"
				},
				series: [{
					name: '人数',
					type: tlx,
					data: data
				}]
			};
			if(tlx!='pie'){
				option.xAxis={data: xAxis};
				option.yAxis={type : 'value'};
			}
			myChart[oi].setOption(option);
		},
		search:function(){
			var cnas = {
				'dt':get('dt1_{rand}').value
			};
			darr[0].setparams(cnas, true);
			darr[1].setparams(cnas, true);
		}
	};

	darr[1] = $('#view1_{rand}').bootstable({
		tablename:'dailyfx',url:publicmodeurl('daily'),storebeforeaction:'bmztweixiedatabefore',storeafteraction:'bmztweixiedataafter',
		columns:[{
			text:'部门',dataIndex:'name'
		},{
			text:'未写人数',dataIndex:'value'
		}],
		load:function(a){
			c.loadcharts(1,'pie');
		}
	});
	
	darr[0] = $('#view0_{rand}').bootstable({
		tablename:'dailyfx',url:publicmodeurl('daily'),storebeforeaction:'ztweixiedatabefore',storeafteraction:'ztweixiedataafter',
		columns:[{
			text:'部门',dataIndex:'deptname',align:'left',sortable:true
		},{
			text:'姓名',dataIndex:'name',sortable:true
		},{
			text:'应写',dataIndex:'totaly',sortable:true
		},{
			text:'已写',dataIndex:'totalx',sortable:true
		},{
			text:'未写',dataIndex:'totalw',sortable:true
		}],
		load:function(d){
			$('#zttiel0_{rand}').html('日期['+d.ztdt+']未写日报人员');
			$('#zttiel1_{rand}').html('日期['+d.ztdt+']根据部门统计未写人员');
		}
	});
	
	js.initbtn(c);
});
</script>

<div>
	<table width="100%">
	<tr>
	<td nowrap>日期&nbsp;</td>
	<td>
		<input onclick="js.datechange(this,'date')" style="width:110px" readonly class="form-control datesss" id="dt1_{rand}" >
	</td>
	<td  style="padding-left:10px">
		<button class="btn btn-default" click="search" type="button">查看统计</button>
	</td>
	<td width="90%">
		
	</td>
	<td align="right" nowrap>
		
	</td>
	</tr>
	</table>
	
</div>
<div class="blank10"></div>
<div align="left">
	<table  border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr valign="top">
		
		<td width="50%">
			<div align="left" style="min-width:300px" class="list-group">
			<div class="list-group-item  list-group-item-info">
				<i class="icon-bar-chart"></i> <span id="zttiel0_{rand}">昨天未写人员</span>
				<span style="float:right" ><a click="reload,0"><i class="icon-refresh"></i></a></span>
			</div>
			<div id="view0_{rand}"></div>
			</div>
			
		</td>
		
		<td style="padding-left:10px;">
			<div align="left" class="list-group">
			<div class="list-group-item  list-group-item-success">
				<i class="icon-bar-chart"></i> <span id="zttiel1_{rand}">根据部门统计已写未写人员</span>
				<span style="float:right" ><a click="reload,1"><i class="icon-refresh"></i></a></span>
			</div>
			<div id="view1_{rand}"></div>
			<div id="viewchats1_{rand}" style="width:100%;height:250px;border:1px #dddddd solid;border-top:0px"></div>
			</div>
			
			
			
		</td>
		
		
		
	</tr>
	</table>
</div>