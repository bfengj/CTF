<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	var a = $('#view_{rand}').bootstable({
		tablename:'kqdw',celleditor:true,fanye:true,url:publicstore('{mode}','{dir}'),storebeforeaction:'kqdwbefore',
		columns:[{
			text:'规则名称',dataIndex:'name',align:'left',editor:true
		},{
			text:'经度',dataIndex:'location_x',editor:true
		},{
			text:'纬度',dataIndex:'location_y',editor:true
		},{
			text:'经纬度',dataIndex:'xuanz',renderer:function(v,d,i){
				return ''+v+'&nbsp;<a href="javascript:;" onclick="changeweizhi{rand}('+i+')">选择位置</a>';
			}
		},{
			text:'位置名称',dataIndex:'address',editor:true
		},{
			text:'允许误差(米)',dataIndex:'precision',editor:true
		},{
			text:'无固定地点',dataIndex:'iswgd',editor:true,type:'checkbox'
		},{
			text:'拍照打卡',dataIndex:'ispz',editor:true,type:'checkbox'
		},{
			text:'关联其他位置ID',dataIndex:'dwids',editor:true
		},{
			text:'ID',dataIndex:'id'
		}],
		itemclick:function(){
			btn(false);
		},
		beforeload:function(){
			btn(true);
		}
	});
	
	function btn(bo){
		get('del_{rand}').disabled = bo;
		get('edit_{rand}').disabled = bo;
	}
	
	var c = {
		del:function(){
			a.del({url:js.getajaxurl('kqdwdkdatadel','{mode}','{dir}')});
		},
		clickwin:function(o1,lx){
			var gzdata = [{id:'',name:''}];
			var das    = a.getData();
			for(var i in das)gzdata.push(das[i]);
			var h = $.bootsform({
				title:'位置',height:380,width:400,
				tablename:'kqdw',isedit:lx,
				submitfields:'name,address,precision,dwids',
				items:[{
					labelText:'名称',name:'name',required:true
				},{
					labelText:'位置名称',name:'address',required:true
				},{
					labelText:'允许误差(米)',name:'precision',type:'number',value:0
				},{
					labelText:'关联其他地点',name:'dwids',type:'select',valuefields:'id',displayfields:'name',store:gzdata
				}],
				success:function(){
					a.reload();
				}
			});
			if(lx==1)h.setValues(a.changedata);
			h.getField('name').focus();
		},
		search:function(){
			var s=get('key_{rand}').value;
			a.setparams({key:s},true);
		}
	};
	
	changeweizhi{rand}=function(oi){
		var d = a.getData(oi);
		var url = '?m=kaoqin&d=main&a=locationchange';
		if(!isempt(d.location_x))url+='&location_x='+d.location_x+'&location_y='+d.location_y+'&scale='+d.scale+'';
		openxiangs('选择位置',url,'','backshow{rand}');
	}
	backshow{rand}=function(d){
		d.id = a.changeid;
		js.ajax(js.getajaxurl('savaweizz','{mode}','{dir}'),d, function(s){
			a.reload();
		},'post');
	}
	js.initbtn(c);
});
</script>
<div>
<table width="100%"><tr>
	<td nowrap>
		<button class="btn btn-primary" click="clickwin,0" type="button"><i class="icon-plus"></i> 新增</button>
	</td>
	<td  style="padding-left:10px">
		<input class="form-control" style="width:200px" id="key_{rand}"  placeholder="规则/位置名称">
	</td>
	<td  style="padding-left:10px">
		<button class="btn btn-default" click="search" type="button">搜索</button>
	</td>
	<td width="100%"></td>
	<td align="right" nowrap>
		<button class="btn btn-info" id="edit_{rand}" click="clickwin,1" disabled type="button"><i class="icon-edit"></i> 编辑 </button> &nbsp; 
		<button class="btn btn-danger" id="del_{rand}" click="del" disabled type="button"><i class="icon-trash"></i> 删除</button>
		
	</td>
</tr></table>
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
<div class="tishi">位置必须从地图上选择哦，无固定地点选中了：任何地点都可以考勤打卡，关联其他位置ID：填写当前页面记录的ID，多个,分开。</div>
