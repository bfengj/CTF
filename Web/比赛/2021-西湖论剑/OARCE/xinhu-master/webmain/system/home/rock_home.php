<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	var lestore = [[0,'第一列'],[1,'第二列'],[2,'第三列'],[3,'第四列']];
	var a = $('#veiw_{rand}').bootstable({
		tablename:'homeitems',celleditor:true,defaultorder:'`row`,`sort`',
		columns:[{
			text:'名称',dataIndex:'name',editor:true
		},{
			text:'编号',dataIndex:'num',editor:true
		},{
			text:'适用对象',dataIndex:'recename'
		},{
			text:'位置列',dataIndex:'row',editor:true,type:'select',store:lestore,renderer:function(v){
				var v1=parseFloat(v);
				return lestore[v1][1];
			}
		},{
			text:'排序号',dataIndex:'sort',editor:true,type:'number'
		},{
			text:'状态',dataIndex:'status',type:'checkbox',editor:true
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
			a.del({url:js.getajaxurl('delhome','{mode}','{dir}')});
		},
		refresh:function(){
			a.reload();
		},
		clickwin:function(o1,lx){
			var h = $.bootsform({
				title:'首页项',height:400,width:400,
				tablename:'homeitems',isedit:lx,
				submitfields:'name,sort,num,receid,recename,row',
				items:[{
					labelText:'名称',name:'name',required:true
				},{
					labelText:'编号',name:'num',required:true
				},{
					labelText:'适用对象',type:'changeuser',changeuser:{
						type:'deptusercheck',idname:'receid',title:'选择人员'
					},name:'recename',clearbool:true
					
				},{
					name:'receid',type:'hidden'
				},{
					labelText:'位置列',name:'row',type:'select',value:'0',store:lestore,valuefields:0,displayfields:1
				},{
					labelText:'序号',name:'sort',type:'number',value:'0'
				}],
				success:function(){
					a.reload();
				}
			});
			if(lx==1){
				h.setValues(a.changedata);
			}
			h.getField('name').focus();
		},
		daoru:function(){
			js.confirm('导入官网默认项，会直接覆盖你的修改哦',function(jg){
				if(jg=='yes')c.daoruss();
			});
		},
		daoruss:function(){
			js.msg('wait','导入中...');
			js.ajax(js.getajaxurl('daordriws','{mode}', '{dir}'),{}, function(d){
				js.msg('success', '导入成功');
				a.reload();
			},'get');
		}
	};
	
	js.initbtn(c);
});
</script>

<div>
<ul class="floats">
	<li class="floats50">
		<button class="btn btn-primary" click="clickwin,0" type="button"><i class="icon-plus"></i> 新增首页项</button>&nbsp; 
		<button class="btn btn-default" click="refresh,0" type="button">刷新</button>&nbsp; 
		<button class="btn btn-default" click="daoru" type="button">导入官网默认项</button>
	</li>
	<li class="floats50" style="text-align:right">
		<button class="btn btn-danger" id="del_{rand}" click="del" disabled type="button"><i class="icon-trash"></i> 删除</button> &nbsp; 
		<button class="btn btn-info" id="edit_{rand}" click="clickwin,1" disabled type="button"><i class="icon-edit"></i> 编辑 </button>
	</li>
</ul>
</div>
<div class="blank10"></div>
<div id="veiw_{rand}"></div>
<div class="tishi">首页项内容显示需要到webmain/home/desktop下创建对应文件编写代码。</div>