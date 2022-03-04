<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params};
	var num = params.num,pid,optlx=0;
	var typeid=0,sspid=0;
	var at = $('#optionview_{rand}').bootstree({
		url:js.getajaxurl('gettreedata','option','system',{'num':'knowtikutype'}),
		columns:[{
			text:'题库分类',dataIndex:'name',align:'left',xtype:'treecolumn'
		}],
		load:function(d){
			if(sspid==0){
				typeid = d.pid;
				sspid = d.pid;
				c.loadfile('0','所有题库');
			}
		},
		itemdblclick:function(d){
			typeid = d.id;
			c.loadfile(d.id,d.name);
		}
	});;
	var modenum='knowtiku';
	var a = $('#view_{rand}').bootstable({
		tablename:modenum,celleditor:false,autoLoad:false,modenum:modenum,fanye:true,params:{atype:'xuexi'},
		columns:[{
			text:'题名',dataIndex:'title',editor:false,align:'left'
		},{
			text:'分类',dataIndex:'typename'
		},{
			text:'类型',dataIndex:'type'
		},{
			text:'A',dataIndex:'ana'
		},{
			text:'B',dataIndex:'anb'
		},{
			text:'C',dataIndex:'anc'
		},{
			text:'D',dataIndex:'and'
		},{
			text:'E',dataIndex:'ane'
		},{
			text:'答案',dataIndex:'answer',renderer:function(v,d,oi){
				return '<a href="javascript:;" onclick="openxiangs(\'知识学习\',\'knowtiku\','+d.id+')">查看</a>';
			}
		}],
		itemdblclick:function(d){
			openxiangs(d.title,modenum, d.id);
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
			this.loadfile('0','所有题库');
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
			<button class="btn btn-default" click="genmu"  type="button">所有题库</button>&nbsp; 
			
		</td>
		
		<td style="padding-left:10px">
		<input class="form-control" style="width:200px" id="key_{rand}"   placeholder="题名/分类">
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
