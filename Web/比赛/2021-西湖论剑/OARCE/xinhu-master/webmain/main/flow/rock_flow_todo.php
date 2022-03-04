<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params};
	var num = params.num,mid,modeid=0,bools=false;
	
	function btn(bo){
		get('edit_{rand}').disabled = bo;
		get('del_{rand}').disabled = bo;
	}
	
	var changearr={'boturn':'提交时','boedit':'编辑时','bochang':'字段改变时','bodel':'删除时','bozuofei':'作废时','botong':'步骤处理通过时','bobutong':'步骤处理不通过时','bofinish':'处理完成时','bozhui':'追加说明时','bozhuan':'转办时','botask':'计划任务','boping':'评论时','bohuiz':'回执确认时'};
	
	var a = $('#view_{rand}').bootstable({
		tablename:'flow_todo',celleditor:true,modedir:'{mode}:{dir}',
		storeafteraction:'flowtodoafter',storebeforeaction:'flowtodobefore',
		columns:[{
			text:'模块',dataIndex:'modename'
		},{
			text:'通知标题',dataIndex:'name',editor:true
		},{
			text:'触发条件',dataIndex:'whereidstr'
		},{
			text:'触发类型',dataIndex:'changeopt',align:'left',renderer:function(v,d, oi){
				var s='&nbsp;',f;
				for(f in changearr)if(d[f]=='1'){
					s+=''+changearr[f]+'';
					if(f=='botask'){
						if(d.remindrs)s+='('+d.remindrs.ratecont+')';
						s+='<a onclick="setpnvlsls{rand}('+oi+')" href="javascript:;">[提醒时间]</a>';
					}
					s+=';';
				}
				return s;
			}
		},{
			text:'编号',dataIndex:'num',editor:true
		}/*,{
			text:'变化字段',dataIndex:'changefields'
		},{
			text:'处理步骤Id',dataIndex:'changecourse'
		}*/,{
			text:'说明',dataIndex:'explain',editor:true
		},{
			text:'状态',dataIndex:'status',type:'checkbox',editor:true,sortable:true
		},{
			text:'通知给',dataIndex:'recename',renderer:function(v,d){
				var s='&nbsp;';
				if(d.toturn=='1')s+='提交人;';
				if(d.tocourse=='1')s+='流程所有参与人;';
				if(d.tosuper=='1')s+='直属上级;';
				if(!isempt(v))s+=''+v+';';
				return s;
			}
		},{
			text:'通知给字段',dataIndex:'todofields'
		},{
			text:'通知内容摘要',dataIndex:'summary'
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
			guanflowtodowherelist = [a.wherelist,a.fielslist,a.courselist];
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
			if(modeid==0){
				js.msg('msg','请先选择模块');
				return;
			}
			var moen= get('mode_{rand}');
			var nd  = moen.options[moen.selectedIndex].text;
			var icon='plus',name='新增['+nd+']单据通知',id=0;
			if(lx==1){
				id = a.changeid;
				icon='edit';
				name='编辑['+nd+']单据通知';
			};
			guanflowtodolist = a;
			addtabs({num:'flowtodo'+id+'',url:'main,flow,todoedit,id='+id+',setid='+modeid+',',icons:icon,name:name});
		},
		changemode:function(){
			modeid=this.value;
			a.setparams({mid:modeid},true);
			var bo = (modeid==0);
			get('add_{rand}').disabled = bo;
		},
		setpinlv:function(oi){
			var d = a.getData(oi);
			var mid = d.remindrs ? d.remindrs.id : '0';
			openinput('提醒设置','remind',''+mid+'&def_modenum=flowtodo&def_mid='+d.id+'&def_explain=basejm_'+jm.base64encode(d.summary)+'', 'reslsds{rand}');
		}
	};
	js.initbtn(c);
	$('#mode_{rand}').change(c.changemode);
	
	setpnvlsls{rand}=function(oi){
		c.setpinlv(oi);
	}
	reslsds{rand}=function(){
		c.reload();
	}
});
</script>

<table width="100%">
<tr>
<td align="left">
	<button class="btn btn-primary" click="clickwin,0" disabled id="add_{rand}" type="button"><i class="icon-plus"></i> 新增单据通知</button>
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
<div class="tishi">此功能设置的当流程单据操作时触发通知给人员，当触发类型是计划任务时，该记录需要设置提醒频率。</div>
