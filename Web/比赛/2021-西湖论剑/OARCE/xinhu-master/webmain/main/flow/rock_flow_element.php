<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params}
	var bools=false,modeid=0,moders={};
	if(params.mid)modeid=params.mid;
	var a = $('#view_{rand}').bootstable({
		tablename:'flow_element',celleditor:true,
		params:{mid:modeid},
		url:publicstore('{mode}','{dir}'),storeafteraction:'elementafter',storebeforeaction:'elementbefore',
		columns:[{
			text:'名称',dataIndex:'name',editor:true
		},{
			text:'对应字段',dataIndex:'fields'
		},{
			text:'分类',dataIndex:'iszb',renderer:function(v,d){
				var s='<font color=#ff6600>主表</font>';
				if(v>0)s='第'+d.iszb+'个子表';
				return s;
			}
		},{
			text:'元素类型,<a target="_blank" href="<?=URLY?>view_element.html">说明</a>',dataIndex:'fieldstype'
		},{
			text:'默认值',dataIndex:'dev',editor:true
		},{
			text:'排序号',dataIndex:'sort',editor:true,sortable:true
		},{
			text:'录入列',dataIndex:'islu',type:'checkbox',editor:true,sortable:true,editorbefore:function(d){
				if(d.islu=='0'){js.msg('msg','打开录入项需要用编辑');return false;}else{return true;}
			}
		},{
			text:'必填',dataIndex:'isbt',type:'checkbox',editor:true,sortable:true
		},{
			text:'展示',dataIndex:'iszs',type:'checkbox',editor:true,sortable:true
		},{
			text:'列表列',dataIndex:'islb',type:'checkbox',editor:true,sortable:true
		},{
			text:'列排序',dataIndex:'ispx',type:'checkbox',editor:true,sortable:true
		},{
			text:'可搜索',dataIndex:'issou',type:'checkbox',editor:true,sortable:true
		},{
			text:'可统计',dataIndex:'istj',type:'checkbox',editor:true,sortable:true
		},{
			text:'唯一值',dataIndex:'isonly',type:'checkbox',editor:true,sortable:true
		},{
			text:'可导入',dataIndex:'isdr',type:'checkbox',editor:true,sortable:true
		},{
			text:'对齐',dataIndex:'isalign',type:'select',editor:true,sortable:true,renderer:function(v,d){
				var s='<font color="#888888">居中</font>';
				if(v==1)s='<font color="#ff6600">居左</font>';
				if(v==2)s='居右';
				return s;
			},store:js.arraystr('0|居中,1|居左,2|居右')
		},{
			text:'数据源',dataIndex:'data'
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
			if(typeof(zzzfieldsarr)=='undefined')zzzfieldsarr={};
			zzzfieldsarr[moders.table] = a.fieldsarr;
			fieldstypearr = a.fieldstypearr;
		}
	});
	function btn(bo, d){
		get('edit_{rand}').disabled = bo;
		get('del_{rand}').disabled = bo;
	}
	function btnsss(bo){
		get('add_{rand}').disabled = bo;
		get('lu_{rand}').disabled = bo;
		get('lum_{rand}').disabled = bo;
		get('luc_{rand}').disabled = bo;
		get('luz_{rand}').disabled = bo;
		get('luzz_{rand}').disabled = bo;
		get('luzs_{rand}').disabled = bo;
		get('changelieb_{rand}').disabled = bo;
	}
	var c = {
		del:function(){
			a.del();
		},
		reload:function(){
			a.reload();
		},
		clickwin:function(o1,lx){
			if(modeid==0)return;
			var icon='plus',name='新增['+moders.name+']的元素',id=0;
			if(lx==1){
				id = a.changeid;
				icon='edit';
				name='编辑['+moders.name+']的元素';
			};
			guanelementedit = a;
			addtabs({num:'flowelement'+id+'',url:'main,flow,elementedit,id='+id+',mid='+modeid+',table='+moders.table+'',icons:icon,name:name});
		},
		changemode:function(){
			modeid=this.value;
			a.setparams({mid:modeid},true);
			var bo = (modeid==0);
			btnsss(bo);
		},
		//录入
		lulu:function(o1,lx){
			if(lx==1){
				var url = js.getajaxurl('@lum','input','flow',{num:moders.num});
				js.open(url, 380,500);
			}else{
				var url = js.getajaxurl('@lu','input','flow',{num:moders.num});
				js.open(url, 800,450);
			}
		},
		inputs:function(o1,lx){
			var url='?m=flow&d=main&a=input&setid='+moders.id+'&atype=0';
			js.open(url,980,530);
		},
		zhanshi:function(o1,lx){
			var url='?m=flow&d=main&a=inputzs&setid='+moders.id+'&atype='+lx+'';
			js.open(url,980,530);
		},
		rexuhao:function(){
			if(modeid==0)return;
			js.ajax(js.getajaxurl('rexuhao','{mode}','{dir}'),{modeid:modeid},function(){
				a.reload();
			},'get','','刷新中...,刷新成功');
		},
		changelieb:function(){
			if(modeid==0)return;
			js.ajax(js.getajaxurl('changelieb','{mode}','{dir}'),{modeid:modeid},function(s){
				js.msg('success','生成成功路径：'+s+'');
			},'get','','生成中...,生成成功');
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
		<button class="btn btn-default" id="changelieb_{rand}" disabled click="changelieb" type="button">生成列表页</button>
		</div>
	</td>
	<td align="right" nowrap>
		<button class="btn btn-default" click="rexuhao" type="button">刷新序号</button>&nbsp; 
		<button class="btn btn-warning" id="add_{rand}" disabled click="clickwin,0" type="button">新增</button>&nbsp; 
		<button class="btn btn-info" id="edit_{rand}" click="clickwin,1" disabled type="button">编辑</button>&nbsp; 
		<button class="btn btn-danger" click="del" disabled id="del_{rand}" type="button">删除</button>
	</td>
	</tr>
	</table>
	
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
<div class="tishi">
1、PC端录入页布局：设置电脑PC端录入页面的布局的。<br>
2、PC端展示：设置电脑PC端单据详情页面展示的样子。<br>
3、手机展示：设置手机上单据详情页面展示的样子。<br>
</div>
