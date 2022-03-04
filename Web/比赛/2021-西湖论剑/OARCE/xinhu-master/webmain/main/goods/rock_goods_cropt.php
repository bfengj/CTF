<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	

	
	var a = $('#view_{rand}').bootstable({
		tablename:'goodm',fanye:true,dir:'desc',sort:'a.id',storebeforeaction:'croptbeforeshow',storeafteraction:'croptaftershow',	url:publicstore('{mode}','{dir}'),
		columns:[{
			text:'单号',dataIndex:'sericnum'
		},{
			text:'申请人',dataIndex:'uname'
		},{
			text:'申请人部门',dataIndex:'udeptname'
		},{
			text:'类型',dataIndex:'type',sortable:true
		},{
			text:'申请日期',dataIndex:'applydt',sortable:true
		},{
			text:'操作时间',dataIndex:'optdt'
		},{
			text:'说明',dataIndex:'explain',align:'left'
		},{
			text:'出入库状态',dataIndex:'state'
		},{
			text:'',dataIndex:'opt',renderer:function(v,d){
				var v='<a href="javascript:;" onclick="rukuope{rand}('+d.id+','+d.typev+')">去操作</a>';
				return v;
			}
		}]
	});
	var c = {
		search:function(){
			var s=get('key_{rand}').value;
			a.setparams({key:s},true);
		}
	};
	
	rukuope{rand}=function(id,kind){
		var lex = ['领用出库','采购入库','销售出库','调拨入库','归还入库','退货入库'];
		var typa= [1,0,1,0,0,0];
		var kina= [0,0,1,3,1,4]; //从数据选项中的来的
		var type = typa[kind]; //0入库,1出库
		addtabs({url:'main,goods,churuku,type='+type+',mid='+id+',kind='+kina[kind]+',kindname='+lex[kind]+'','num':'rukuopt'+id+'',name:''+id+'.'+lex[kind]+''});
	}
	
	js.initbtn(c);
});
</script>


<div>
<table width="100%"><tr>

	<td >
	<input class="form-control" style="width:200px" id="key_{rand}"   placeholder="申请人/单号">
	</td>
	<td style="padding-left:10px">
		<button class="btn btn-default" click="search" type="button">搜索</button> 
	</td>
	
	<td width="90%">
		&nbsp;&nbsp;<span id="megss{rand}"></span>
	</td>
	<td align="right" nowrap>
		
	</td>
</tr></table>
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>