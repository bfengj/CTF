<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params};
	var num = params.num,pid,optlx=0;
	var typeid=0,sspid=0;
	var at = $('#optionview_{rand}').bootstree({
		url:js.getajaxurl('gettreedata','option','system',{'num':'knowledgetype'}),
		columns:[{
			text:'知识分类',dataIndex:'name',align:'left',xtype:'treecolumn',width:'79%'
		},{
			text:'ID',dataIndex:'id',width:'20%'
		}],
		load:function(d){
			if(sspid==0){
				typeid = d.pid;
				sspid = d.pid;
				c.loadfile('0','所有知识');
			}
		},
		itemdblclick:function(d){
			typeid = d.id;
			c.loadfile(d.id,d.name);
		}
	});;
	
	var a = $('#view_{rand}').bootstable({
		tablename:'knowledge',celleditor:true,autoLoad:false,modenum:'knowledge',
		columns:[{
			text:'标题',dataIndex:'title',editor:false,align:'left'
		},{
			text:'分类',dataIndex:'typename'
		},{
			text:'添加时间',dataIndex:'adddt',sortable:true
		},{
			text:'操作时间',dataIndex:'optdt',sortable:true
		},{
			text:'操作人',dataIndex:'optname',sortable:true
		},{
			text:'排序',dataIndex:'sort',sortable:true
		},{
			text:'ID',dataIndex:'id'
		},{
			text:'',dataIndex:'opt',renderer:function(v,d,oi){
				return '<a href="javascript:;" onclick="openxiangs(\'知识\',\'knowledge\','+d.id+')">查看</a>';
			}
		}],
		itemdblclick:function(d){
			openxiangs(d.title,'knowledge', d.id);
		}
	});


	var c = {
		reload:function(){
			at.reload();
		},
		loadfile:function(spd,nsd){
			$('#megss{rand}').html(nsd);
			a.setparams({'typeid':spd}, true);
		},
		genmu:function(){
			typeid = sspid;
			at.changedata={};
			this.loadfile('0','所有知识');
		},
		daochu:function(){
			a.exceldown();
		},
		search:function(){
			var s=get('key_{rand}').value;
			a.setparams({key:s},true);
		}
	};
	js.initbtn(c);
	$('#optionview_{rand}').css('height',''+(viewheight-70)+'px');
});
</script>


<table width="100%">
<tr valign="top">
<td width="220">
	<div style="border:1px #cccccc solid">
	  <div id="optionview_{rand}" style="height:400px;overflow:auto;"></div>
	  <div  class="panel-footer">
		<a href="javascript:" click="reload" onclick="return false"><i class="icon-refresh"></i></a>
	  </div>
	</div>  
</td>
<td width="10" nowrap><div style="width:10px">&nbsp;</div></td>
<td>	
	<div>
	<table width="100%"><tr>
		<td align="left" nowrap>
			<button class="btn btn-default" click="genmu"  type="button">所有知识</button>&nbsp; 
			
		</td>
		
		<td style="padding-left:10px">
		<input class="form-control" style="width:180px" id="key_{rand}"   placeholder="标题/分类">
		</td>
		<td style="padding-left:10px">
			<button class="btn btn-default" click="search" type="button">搜索</button> 
		</td>
		<td width="90%">
			&nbsp;&nbsp;<span id="megss{rand}"></span>
		</td>
		<td align="right">
			<button class="btn btn-default"  click="daochu" type="button">导出</button>
		</td>
	</tr></table>
	</div>
	<div class="blank10"></div>
	<div id="view_{rand}"></div>
</td>
</tr>
</table>
