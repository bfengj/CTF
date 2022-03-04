<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params}
	var atype=params.atype;
	if(!atype)atype='all';
	
	var col1 = [{
		text:'部门',dataIndex:'deptname',align:'left',sortable:true
	},{
		text:'姓名',dataIndex:'name',sortable:true
	},{
		text:'职位',dataIndex:'ranking'
	},{
		text:'人员状态',dataIndex:'state'
	}];
	var col2 = [{
		text:'调休(时)',dataIndex:'tiaoxiu'
	}];
	function getcolumns(a1,a2,a3){
		var a4 = [].concat(a1,a2,a3);
		return a4;
	}
	var colemsn = getcolumns(col1, col2,[]);
	var a = $('#view_{rand}').bootstable({
		tablename:'userinfo',celleditor:true,fanye:true,params:{'atype':atype},modedir:'{mode}:{dir}',storeafteraction:'kqtotalafterjiashow',storebeforeaction:'kqtotalbeforeshow',
		columns:colemsn,
		itemclick:function(){
			
		},
		loadbefore:function(d){
			var cs4=[],i;
			for(i=0;i<d.kqkind.length;i++){
				cs4.push({text:d.kqkind[i].name.substr(2)+'(时)',dataIndex:'total'+i+''});
			}
			if(cs4.length>0){
				var cols = getcolumns(col1,col2,cs4);
				cols.push({text:'',notexcel:true,dataIndex:'xingxid',renderer:function(v,d){
					return '<a href="javascript:;" onclick="show{rand}mx('+d.id+',\''+d.name+'\')">明细</a>';
				}});
				a.setColumns(cols);
			}
		}
	});
	var c = {
		search:function(){
			var s=get('key_{rand}').value;
			a.setparams({key:s,month:get('dt1_{rand}').value},true);
		},
		clickdt:function(o1, lx){
			$(o1).rockdatepicker({initshow:true,view:'date',inputid:'dt'+lx+'_{rand}'});
		},
		daochu:function(o1){
			publicdaochuobj({
				'objtable':a,
				'modename':'',
				'btnobj':o1
			});
		},
		//订阅
		dingyue:function(){
			js.subscribe({
				title:''+nowtabs.name+'',
				objtable:a,
				params:{'key':get('key_{rand}').value}
			});
		}
	};
	
	js.initbtn(c);
	
	show{rand}mx=function(id,na){
		addtabs({name:'假期统计明细('+na+')',url:'main,kaoqin,totalmx,uid='+id+'',num:'totalmx'+id+''});
	}
});
</script>
<div>
<table width="100%"><tr>
	<td nowrap>
		<div style="width:150px"  class="input-group">
			<input placeholder="截止至日期" readonly class="form-control" id="dt1_{rand}" >
			<span class="input-group-btn">
				<button class="btn btn-default" click="clickdt,1" type="button"><i class="icon-calendar"></i></button>
			</span>
		</div>
	</td>
	<td  style="padding-left:10px">
		<input class="form-control" style="width:150px" id="key_{rand}"   placeholder="姓名/部门">
	</td>
	<td  style="padding-left:10px">
		<button class="btn btn-default" click="search" type="button">搜索</button>
	</td>
	<td  style="padding-left:10px">
		<button class="btn btn-default" click="dingyue" type="button">订阅此统计表</button>
	</td>
	<td  style="padding-left:5px">
		
	</td>
	<td width="80%"></td>
	<td align="right" nowrap>
		<button class="btn btn-default" click="daochu" type="button">导出 <i class="icon-angle-down"></i></button>
	</td>
</tr></table>
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
<div class="tishi">人员是从档案表里读取仅显示需要考勤的，根据截止日期来统计的，是统计表kqinfo上字段status为0,1。</div>
