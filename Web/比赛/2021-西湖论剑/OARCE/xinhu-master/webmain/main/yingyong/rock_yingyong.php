<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	var a = $('#view_{rand}').bootstable({
		tablename:'im_group',where:'and type=2',celleditor:true,
		modenum:'yingyong',storeafteraction:'yingyongafter',storebeforeaction:'yingyongbefore',modedir:'{mode}:{dir}',
		columns:[{
			text:'图标',dataIndex:'face',align:'left',renderer:function(v,d){
				var s='';
				s='<div>';
				s+='<img src="'+v+'" align="absmiddle" width="24" height="24">';
				s+='&nbsp; '+d.name+'';
				s+='</div>';
				return s;
			}
		},{
			text:'分类',dataIndex:'types',editor:true,sortable:true
		},{
			text:'图标颜色',dataIndex:'iconcolor',editor:true
		},{
			text:'编号',dataIndex:'num'
		},{
			text:'排序号',dataIndex:'sort',editor:true,sortable:true
		},{
			text:'地址',dataIndex:'url'
		},{
			text:'状态',dataIndex:'valid',type:'checkbox',editor:true,sortable:true
		},{
			text:'可用人员',dataIndex:'recename',renderer:function(v){
				if(!v)v='全体人员';
				return v;
			}
		},{
			text:'类型',dataIndex:'yylx',renderer:function(v){
				var as=['全部','仅桌面版','仅手机'];
				return as[v];
			}
		},{
			text:'操作',dataIndex:'opt',align:'left',renderer:function(v,d){
				var s='<a href="javascript:;" onclick="yingyongedit('+d.id+',\''+d.name+'\')">编辑</a>';
				if(d.num)s+='&nbsp;<a href="javascript:;" onclick="yingyongmenu('+d.id+',\''+d.name+'\')">菜单</a>';
				return s;
			}
		},{
			text:'ID',dataIndex:'id'	
		}],
		itemclick:function(){
			btn(false);
		}
	});
	
	function btn(bo){
		get('del_{rand}').disabled = bo;
		if(!bo && a.changedata.leave!=1)bo=true;
		//get('adds_{rand}').disabled = bo;
	}
	
	var c = {
		clickwin:function(o1,lx){
			var pid=0;
			listyingyongobj = a;
			addtabs({num:'yingyongedit0',url:'main,yingyong,edit,id=0,pid='+pid+'',name:'新增应用'});
		},
		del:function(){
			a.del({check:function(lx){if(lx=='yes')btn(true)}});
		},
		reload:function(){
			a.reload();
		}
	}
	
	yingyongedit=function(id,name){
		listyingyongobj = a;
		addtabs({num:'yingyongedit'+id+'',url:'main,yingyong,edit,id='+id+'',name:'应用['+name+']'});
	}
	yingyongmenu=function(id,name){
		addtabs({num:'yingyongmenu'+id+'',url:'main,yingyong,menu,mid='+id+'',name:'应用['+name+']菜单'});
	}
	
	js.initbtn(c);
});
</script>
<div>
<ul class="floats">
	<li class="floats50">
		<button class="btn btn-primary" click="clickwin,0" type="button"><i class="icon-plus"></i> 新增应用</button> &nbsp; 
	</li>
	<li class="floats50" style="text-align:right">
		
		<button class="btn btn-default" click="reload" type="button">刷新</button> &nbsp; 
		<button class="btn btn-danger" id="del_{rand}" click="del" disabled type="button"><i class="icon-trash"></i> 删除</button>
	</li>
</ul>
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>
