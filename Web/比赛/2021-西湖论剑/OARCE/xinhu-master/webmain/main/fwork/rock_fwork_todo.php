<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	var bools=false;
	var a = $('#view_{rand}').bootstable({
		tablename:'flow_todos',fanye:true,checked:true,
		url:publicstore('{mode}','{dir}'),
		storeafteraction:'flowtodosafter',storebeforeaction:'flowtodosbefore',
		columns:[{
			text:'模块',dataIndex:'modename'
		},{
			text:'提醒时间',dataIndex:'adddt',sortable:true
		},{
			text:'摘要',dataIndex:'summary',align:'left',width:300
		},{
			text:'操作人',dataIndex:'optname'
		},{
			text:'单据操作时间',dataIndex:'optdt'
		},{
			text:'状态',dataIndex:'isread',sortable:true
		}],
		celldblclick:function(){
			c.view();
		},
		load:function(a){
			if(!bools){
				var s = '<option value="">-选择模块-</option>',len=a.flowarr.length,i,csd,types='';
				for(i=0;i<len;i++){
					csd = a.flowarr[i];
					if(types!=csd.type){
						if(types!='')s+='</optgroup>';
						s+='<optgroup label="'+csd.type+'">';
					}
					s+='<option value="'+csd.num+'">'+csd.name+'</option>';
					types = csd.type;
				}
				$('#mode_{rand}').html(s);
			}
			bools=true;
		},
		itemclick:function(){
			btn(false);
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
			openxiangs(d.modename,d.modenum,d.mid,'opegs{rand}');
		},
		search:function(){
			a.setparams({
				dt1:get('dt1_{rand}').value,
				modeid:get('mode_{rand}').value
			},true);
		},
		clickdt:function(o1, lx){
			$(o1).rockdatepicker({initshow:true,view:'date',inputid:'dt'+lx+'_{rand}'});
		},
		daochu:function(){
			a.exceldown(nowtabs.name);
		},
		setss:function(){
			addtabs({name:'单据提醒设置',num:'searchremind',url:'flow,page,remind,atype=my',icons:'cog'});
		},
		del:function(){
			a.del({checked:true,url:js.getajaxurl('deltodo','{mode}','{dir}')});
		}
	};
	js.initbtn(c);
	$('#mode_{rand}').change(function(){
		c.search();
	});
	opegs{rand}=function(){
		c.reload();
	}
	
	
});
</script>
<div>
	<table width="100%">
	<tr>
	<td  style="padding-right:10px">
		<button class="btn btn-default" click="setss" type="button"><i class="icon-cog"></i> 单据提醒设置</button>
	</td>
	<td nowrap>
		<select style="width:150px" id="mode_{rand}" class="form-control" ><option value="">-所有模块-</option></select>	
	</td>
	<td  style="padding-left:10px">
		<div style="width:140px"  class="input-group">
			<input placeholder="提醒日期" readonly class="form-control" id="dt1_{rand}" >
			<span class="input-group-btn">
				<button class="btn btn-default" click="clickdt,1" type="button"><i class="icon-calendar"></i></button>
			</span>
		</div>
	</td>
	<td  style="padding-left:10px">
		
	</td>
	
	<td  style="padding-left:10px">
		<button class="btn btn-default" click="search" type="button">搜索</button>
	</td>
	<td  width="80%" style="padding-left:10px">
		
	</td>
	
	
	<td align="right" nowrap>
		<button class="btn btn-default" id="xiang_{rand}" click="view" disabled type="button">详情</button> &nbsp; <button class="btn btn-default" click="daochu,1" type="button">导出</button>&nbsp; 
		<button class="btn btn-danger" click="del" type="button"><i class="icon-trash"></i> 删除</button>
	</td>
	</tr>
	</table>
	
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
