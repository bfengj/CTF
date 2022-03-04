<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params}
	var bools=false,modeid=0,moders={};
	if(params.mid)modeid=params.mid;
	var a = $('#view_{rand}').bootstable({
		tablename:'flow_modetpl',celleditor:true,
		params:{mid:modeid},
		url:publicstore('{mode}','{dir}'),storeafteraction:'modetpl_after',storebeforeaction:'modetpl_before',
		columns:[{
			text:'模版名称',dataIndex:'tplname',editor:true
		},{
			text:'模版编号',dataIndex:'tplnum'
		},{
			text:'适用对象',dataIndex:'recename'
		},{
			text:'说明',dataIndex:'explain',editor:true,type:'textarea'
		},{
			text:'排序号',dataIndex:'sort',editor:true,type:'number'
		},{
			text:'状态',dataIndex:'status',editor:true,type:'checkbox'
		}],
		itemclick:function(d){
			btn(false, d);
		},
		beforeload:function(){
			btn(true);
		},
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
				s+='</optgroup>';
				$('#mode_{rand}').html(s);
				if(modeid>0){
					get('mode_{rand}').value=modeid;
					btnsss(false);
				}
			}
			bools=true;
			if(!a.moders)return;
			moders = a.moders;
		}
	});
	function btn(bo, d){
		get('edit_{rand}').disabled = bo;
		get('del_{rand}').disabled = bo;
	}
	function btnsss(bo){
		get('add_{rand}').disabled = bo;
		//get('lu_{rand}').disabled = bo;
		//get('lum_{rand}').disabled = bo;
		//get('luc_{rand}').disabled = bo;
		//get('luz_{rand}').disabled = bo;
		//get('luzz_{rand}').disabled = bo;
		//get('luzs_{rand}').disabled = bo;
	}
	var c = {
		del:function(){
			a.del();
		},
		reload:function(){
			a.reload();
		},
		changemode:function(){
			modeid=this.value;
			a.setparams({mid:modeid},true);
			var bo = (modeid==0);
			btnsss(bo);
		},
		clickwin:function(o1,lx){
			if(modeid==0)return;
			var icon='plus',name='新增模块多模版',id=0;
			if(lx==1){
				id = a.changeid;
				icon='edit';
				name='编辑模块多模版';
			};
			guanmodetpledit = a;
			addtabs({num:'modetpledit'+id+'',url:'main,flow,modetpledit,id='+id+',mid='+modeid+'',icons:icon,name:name});
		}
	};
	js.initbtn(c);
	$('#mode_{rand}').change(c.changemode);
	get('add_{rand}').disabled = (modeid==0);
});
</script>

<div>
	<table width="100%">
	<tr>
	<td align="left">
		<select id="mode_{rand}" style="width:180px" class="form-control" ><option value="0">-选择模块-</option></select>
	</td>
	<td align="left" style="padding-left:10px;">
		<button class="btn btn-default" click="reload" type="button">刷新</button>
	</td>
	<td width="80%" align="left" nowrap style="padding-left:10px;">
		<div class="btn-group">
		<button class="btn btn-default" id="luc_{rand}" disabled click="inputs,0" type="button">PC端录入页布局</button>
		<button class="btn btn-default" id="luz_{rand}" disabled click="zhanshi,0" type="button">PC端展示</button>
		<button class="btn btn-default" id="luzz_{rand}" disabled click="zhanshi,1" type="button">手机展示</button>
		<button class="btn btn-default" id="luzs_{rand}" disabled click="zhanshi,2" type="button">打印布局</button>
		<button class="btn btn-default" id="lu_{rand}" disabled click="lulu,0" type="button">PC录入页</button>
		<button class="btn btn-default" id="lum_{rand}" disabled click="lulu,1" type="button">手机录入页</button>
		</div>
	</td>
	<td align="right" nowrap>
		<button class="btn btn-warning" id="add_{rand}" disabled click="clickwin,0" type="button">新增</button>&nbsp; 
		<button class="btn btn-info" id="edit_{rand}" click="clickwin,1" disabled type="button">编辑</button>&nbsp; 
		<button class="btn btn-danger" click="del" disabled id="del_{rand}" type="button">删除</button>
	</td>
	</tr>
	</table>
	
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
<div class="tishi">此功能暂未开发，[流程模块列表]对应模块需要开启支持多模版，才会在这里显示！</div>