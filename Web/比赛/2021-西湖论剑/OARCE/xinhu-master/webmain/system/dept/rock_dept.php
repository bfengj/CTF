<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	
	var companyinfoall=[];
	var a = $('#veiw_{rand}').bootstable({
		tablename:'dept',modenum:'dept',celleditor:true,
		url:js.getajaxurl('data','dept','system'),tree:true,
		columns:[{
			text:'名称',dataIndex:'name',align:'left'
		},{
			text:'所属单位',dataIndex:'companyname'
		},{
			text:'编号',dataIndex:'num',editor:adminid==1
		},{
			text:'负责人',dataIndex:'headman'
		},{
			text:'上级ID',dataIndex:'pid'
		},{
			text:'排序号',dataIndex:'sort',editor:true
		},{
			text:'ID',dataIndex:'id'	
		}],
		itemclick:function(d){
			var bo = false;
			if(companymode && adminid>1 && d.level==2 && d.companyid=='0')bo=true;
			btn(bo,d);
		},
		load:function(d1){
			companyinfoall=d1.carr.companyinfoall;
			if(d1.errmsg)js.alert(d1.errmsg);
		},
		beforeload:function(){
			btn(true, {});
		}
	});
	
	var c = {
		del:function(){
			a.del();
		},
		clickwin:function(o1,lx){
			var d1 = a.changedata,level,les='';
			if(lx==0){
				level = d1.level+1;
			}else{
				level = d1.level;
			}

			var items = [{
				labelText:'名称',name:'name',required:true
			},{
				labelText:'编号',name:'num'
			},{
				name:'headid',type:'hidden'
			},{
				labelText:'负责人',type:'changeuser',changeuser:{
					type:'usercheck',idname:'headid',title:'选择部门负责人'
				},name:'headman',clearbool:true
			},{
				labelText:'上级ID',name:'pid',value:0,type:'number'
			},{
				labelText:'序号',name:'sort',type:'number',value:'0'
			}];
			if(level==2){
				var store = [];
				if(adminid==1)store.push({'id':'0','name':'全部单位'});
				for(var i=0;i<companyinfoall.length;i++)store.push(companyinfoall[i]);
				items.push({
					labelText:'所属单位',name:'companyid',type:'select',value:'0',valuefields:'id',displayfields:'name',store:store
				});
				les=',companyid';
			}
			var h = $.bootsform({
				title:'组织结构',height:400,width:400,
				tablename:'dept',isedit:lx,
				url:js.getajaxurl('publicsave','dept','system'),
				params:{int_filestype:'sort'},
				submitfields:'name,sort,headman,headid,pid,num'+les+'',
				items:items,
				success:function(){
					a.reload();
				}
			});
			if(lx==1){
				h.setValues(a.changedata);
			}
			h.getField('name').focus();
			return h;
		},
		clickdown:function(){
			if(a.changeid==0)return;
			var a1 = this.clickwin(false,0);
			a1.setValue('pid', a.changeid);
		},
		relad:function(){
			a.reload();
		}
	};
	
	function btn(bo,d){
		if(!bo && companymode && d.id==1 && adminid>1)bo=true;//非超级管理员不能修改顶级名称
		get('edit_{rand}').disabled = bo;
		get('down_{rand}').disabled = bo;
		if(d.id==1)bo=true;
		get('del_{rand}').disabled = bo;
	}
	
	js.initbtn(c);
});
</script>

<div>
<ul class="floats">
	<li class="floats50">
		<!--<button class="btn btn-primary" click="clickwin,0" type="button"><i class="icon-plus"></i> 新增</button> &nbsp;--> 
		<button class="btn btn-success" click="clickdown" id="down_{rand}" disabled type="button"><i class="icon-plus"></i> 新增下级</button>
	</li>
	<li class="floats50" style="text-align:right">
		<button class="btn btn-default"  click="relad" type="button">刷新</button> &nbsp; 
		<button class="btn btn-danger" id="del_{rand}" click="del" disabled type="button"><i class="icon-trash"></i> 删除</button> &nbsp; 
		<button class="btn btn-info" id="edit_{rand}" click="clickwin,1" disabled type="button"><i class="icon-edit"></i> 编辑 </button>
	</li>
</ul>
</div>
<div class="blank10"></div>
<div id="veiw_{rand}"></div>
<div class="tishi">1、组织结构必须只能有一个最顶级的，ID必须为1，且不允许删除，不要问为什么，我们也不知道为什么。<br>
2、多单位模式下只能管理自己所属单位的组织机构。<br>
3、只有2级部门才有所属单位可选。</div>