<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	var  bools = false,mid=0,flowarr=[];
	function btn(bo){
		get('edit_{rand}').disabled = bo;
		get('del_{rand}').disabled = bo;
	}

	var a = $('#view_{rand}').bootstable({
		tablename:'flow_where',storeafteraction:'flowwhereafter',storebeforeaction:'flowwherebefore',celleditor:true,defaultorder:'id desc',
		url:publicstore('{mode}','{dir}'),fanye:true,
		columns:[{
			text:'模块名称',dataIndex:'modename'
		},{
			text:'模块id',dataIndex:'setid',sortable:true
		},{
			text:'名称',dataIndex:'name',editor:true
		},{
			text:'分组',dataIndex:'pnum',editor:true,sortable:true
		},{
			text:'编号',dataIndex:'num',editor:true
		},{
			text:'主表条件(SQL条件)',dataIndex:'wheresstr',align:'left',renderer:function(v){
				var s='&nbsp;';
				if(!isempt(v))s=jm.base64decode(v);
				return s;
			}
		},{
			text:'人员',dataIndex:'recename'
		},{
			text:'人员除外',dataIndex:'nrecename'
		},{
			text:'说明',dataIndex:'explain',editor:true
		},{
			text:'排序号',dataIndex:'sort',editor:true,sortable:true
		},{
			text:'列表页显示',dataIndex:'islb',type:'checkbox',editor:true,sortable:true
		},{
			text:'状态',dataIndex:'status',type:'checkbox',editor:true,sortable:true
		},{
			text:'ID',dataIndex:'id',sortable:true
		},{
			text:'',dataIndex:'opt',renderer:function(v,d,oi){
				var s='&nbsp;';
				if(!isempt(d.num)){
					s='<a href="javascript:;" onclick="chakan{rand}('+oi+')">查看</a>';
				}
				return s;
			}
		}],
		itemclick:function(d){
			mid=d.setid;
			btn(false);
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
				$('#mode_{rand}').html(s);
				flowarr = a.flowarr;
			}
			bools=true;
		}
	});
	var c = {
		reload:function(){
			a.reload();
		},
		del:function(){
			a.del();
		},
		gettext:function(){
			var o = get('mode_{rand}');
			return o.options[o.selectedIndex].text;
		},
		clickwin:function(o1,lx){
			var icon='plus',name='新增条件',id=0;
			if(lx==1){
				id = a.changeid;
				icon='edit';
				name='编辑条件';
			}else{
				if(mid==0){
					js.msg('msg','请先选择模块');
					return;
				}
				name='新增['+this.gettext()+']的条件';
			}
			guanflowwherelist = a;
			addtabs({num:'flowwhere'+id+'',url:'main,flow,whereedit,id='+id+',setid='+mid+'',icons:icon,name:name});
		},
		changemode:function(){
			mid=this.value;
			if(mid==0){
				a.search('');
			}else{
				a.search("and `setid`="+mid+"");
			}
		},
		searchs:function(){
			var val = get('key_{rand}').value;
			if(val){
				var oi=-1,i,nud='';
				for(i=0;i<flowarr.length;i++){
					if(flowarr[i].name.indexOf(val)>-1){
						oi = i;
						nud=flowarr[i];
						break;
					}
				}
				if(oi==-1){
					js.msg('msg','没有找到相关模块“'+val+'”');
				}else{
					mid = nud.id;
					get('mode_{rand}').value = mid;
					a.search("and `setid`="+mid+"");
				}
			}
		}
	};
	js.initbtn(c);
	$('#mode_{rand}').change(c.changemode);
	
	chakan{rand}=function(oi){
		var d = a.getData(oi);
		if(isempt(d.pnum))d.pnum='';
		addtabs({num:'flowviewset'+d.id+'',url:'flow,page,'+d.modenum+',atype='+d.num+',pnum='+d.pnum+'',name:d.name});
	}
});
</script>


<table width="100%">
<tr>
<td align="left">
	<button class="btn btn-primary" click="clickwin,0" id="add_{rand}" type="button"><i class="icon-plus"></i> 新增条件</button>
</td>
<td style="padding-left:10px;">
	<select style="width:200px" id="mode_{rand}" class="form-control" ><option value="0">-选择模块-</option></select>
</td>
<td width="80%" style="padding-left:8px">


<div class="input-group" style="width:200px">
	<input class="form-control" id="key_{rand}" placeholder="模块名称/编号">
	<span class="input-group-btn">
		<button class="btn btn-default" click="searchs" type="button"><i class="icon-search"></i></button>
	</span>
</div>


</td>
<td align="right" nowrap>
	<button class="btn btn-info" id="edit_{rand}" click="clickwin,1" disabled type="button"><i class="icon-edit"></i> 编辑 </button> &nbsp; 
		<button class="btn btn-danger" id="del_{rand}" disabled click="del" type="button"><i class="icon-trash"></i> 删除</button>
		
</td>
</tr>
</table>

<div class="blank10"></div>
<div id="view_{rand}"></div>
<div class="tishi">列表页显示：会在生成列表页面上显示的，需要设置编号</div>