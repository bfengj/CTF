<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params};
	var num = params.num,mid,modeid=0,bools=false;
	
	function btn(bo){
		get('edit_{rand}').disabled = bo;
		get('del_{rand}').disabled = bo;
	}
	
	var a = $('#view_{rand}').bootstable({
		tablename:'flow_menu',celleditor:true,sort:'sort',dir:'asc',url:publicstore('{mode}','{dir}'),
		storeafteraction:'flowmenuafter',params:{'mid':-1},storebeforeaction:'flowmenubefore',
		columns:[{
			text:'类型',dataIndex:'type'
		},{
			text:'编号',dataIndex:'num',editor:true
		},{
			text:'显示名称',dataIndex:'name',editor:true
		},{
			text:'动作名',dataIndex:'actname',editor:true
		},{
			text:'状态名',dataIndex:'statusname',editor:true
		},{
			text:'状态值',dataIndex:'statusvalue',editor:true
		},{
			text:'状态颜色',dataIndex:'statuscolor',editor:true
		},{
			text:'说明',dataIndex:'explain',editor:true
		},{
			text:'排序号',dataIndex:'sort',editor:true
		},{
			text:'状态',dataIndex:'status',type:'checkbox',editor:true,sortable:true
		},{
			text:'写入日志',dataIndex:'islog',type:'checkbox',editor:true,sortable:true
		},{
			text:'写说明',dataIndex:'issm',type:'checkbox',editor:true,sortable:true
		},{
			text:'显示在详情页',dataIndex:'iszs',type:'checkbox',editor:true,sortable:true
		},{
			text:'ID',dataIndex:'id'
		}],
		load:function(a){
			if(!bools){
				var s = '<option value="0">-选择模块-</option>',len=a.flowarr.length,i,csd,types='';
				for(i=0;i<len;i++){
					csd = a.flowarr[i];
					if(types!=csd.type){
						if(types!='')s+='</optgroup>';
						s+='<optgroup label="'+csd.type+'">';
					}
					s+='<option value="'+csd.id+'">'+csd.name+'</option>';
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
	var c = {
		reload:function(){
			a.reload();
		},
		del:function(){
			a.del();
		},
		clickwin:function(o1,lx){
			var icon='plus',name='新增操作菜单',id=0;
			if(lx==1){
				id = a.changeid;
				icon='edit';
				name='编辑操作菜单';
			};
			guanflowmenulist = a;
			addtabs({num:'flowmenu'+id+'',url:'main,flow,menuedit,id='+id+',setid='+modeid+',',icons:icon,name:name});
		},
		changemode:function(){
			modeid=this.value;
			a.setparams({mid:modeid},true);
			var bo = (modeid==0);
			get('add_{rand}').disabled = bo;
		}
	};
	js.initbtn(c);
	$('#mode_{rand}').change(c.changemode);
});
</script>

<table width="100%">
<tr>
<td align="left">
	<button class="btn btn-primary" click="clickwin,0" disabled id="add_{rand}" type="button"><i class="icon-plus"></i> 新增操作菜单</button>
</td>
<td  style="padding-left:10px;">
	<button class="btn btn-default" click="reload" type="button">刷新</button>
</td>
<td style="padding-left:10px;">
	<select style="width:200px" id="mode_{rand}" class="form-control" ><option value="0">-选择模块-</option></select>
</td>
<td width="80%"></td>
<td align="right" nowrap>
	<button class="btn btn-info" id="edit_{rand}" click="clickwin,1" disabled type="button"><i class="icon-edit"></i> 编辑 </button> &nbsp; 
			<button class="btn btn-danger" id="del_{rand}" disabled click="del" type="button"><i class="icon-trash"></i> 删除</button>
</td>
</tr>
</table>

<div class="blank10"></div>
<div id="view_{rand}"></div>
<div class="tishi">此功能设置的是对应单据操作菜单，如pc桌面版，客户端打开应用显示数据，右键显示操作菜单的。</div>
