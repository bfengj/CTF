<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params}
	var modenum = 'hrsalary';
	var a = $('#view_{rand}').bootstable({
		params:{'atype':'my'},fanye:true,modenum:modenum,modedir:'{mode}:{dir}',statuschange:false,
		columns:[{
			text:'部门',dataIndex:'udeptname',sortable:true
		},{
			text:'人员',dataIndex:'uname',sortable:true
		},{
			text:'职位',dataIndex:'ranking'
		},{
			text:'月份',dataIndex:'month',sortable:true
		},{
			text:'基本工资',dataIndex:'base',sortable:true
		},{
			text:'实发工资',dataIndex:'money',sortable:true
		},{
			text:'状态',dataIndex:'statustext',renderer:function(v,d){
				return v+','+d.ispay;
			}
		},{
			text:'核算人',dataIndex:'optname'
		},{
			text:'核算时间',dataIndex:'optdt'
		}],
		itemclick:function(){
			btn(false);
		},
		itemdblclick:function(){
			c.view();
		},
		beforeload:function(){
			btn(true);
		}
	});
	
	function btn(bo){
		get('xiang_{rand}').disabled = bo;
	}
	
	var c = {
		reload:function(){
			a.reload();
		},
		view:function(){
			var d=a.changedata;
			openxiangs('薪资',modenum,d.id);
		},
		search:function(){
			a.setparams({
				dt:get('dt2_{rand}').value
			},true);
		},
		daochu:function(){
			a.exceldown(nowtabs.name);
		},
		clickwin:function(o1,lx){
			var id=0;
			if(lx==1)id=a.changeid;
			openinput('薪资', modenum,id);
		},
		clickdt:function(o1, lx){
			$(o1).rockdatepicker({initshow:true,view:'month',inputid:'dt'+lx+'_{rand}'});
		}
	};
	js.initbtn(c);
});
</script>
<div>
	<table width="100%">
	<tr>
	<td>
		<div style="width:140px"  class="input-group">
			<input placeholder="月份" readonly class="form-control" id="dt2_{rand}" >
			<span class="input-group-btn">
				<button class="btn btn-default" click="clickdt,2" type="button"><i class="icon-calendar"></i></button>
			</span>
		</div>	
	</td>
	<td style="padding-left:10px">
		<button class="btn btn-default" click="search" type="button">搜索</button> 
	</td>
	<td width="80%" style="padding-left:10px">
		
	</td>
	<td align="right" nowrap>
		<button class="btn btn-default" id="xiang_{rand}" click="view" disabled type="button">详情</button> &nbsp; 
		<button class="btn btn-default" click="daochu,1" type="button">导出</button> 
	</td>
	</tr>
	</table>
	
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
