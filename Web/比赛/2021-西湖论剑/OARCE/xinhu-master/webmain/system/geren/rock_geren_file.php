<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params};
	var atype=params.atype;
	var checkeds = adminid==1;
	var a = $('#veiw_{rand}').bootstable({
		tablename:'file',celleditor:true,sort:'id',dir:'desc',modedir:'{mode}:{dir}',params:{'atype':atype},fanye:true,checked:checkeds,
		storebeforeaction:'filebefore',storeafteraction:'fileafter',
		columns:[{
			text:'类型',dataIndex:'fileext',renderer:function(v, d){
				if(!isempt(d.thumbpath))return '<img src="'+d.thumbpath+'" width="24" height="24">';
				var lxs = js.filelxext(v);
				return '<img src="web/images/fileicons/'+lxs+'.gif">';
			}
		},{
			text:'名称',dataIndex:'filename',align:'left',renderer:function(v,d){
				var ss='';
				if(d.status==2)ss='<img title="远程文件" src="web/images/fileicons/html.gif">';
				return ''+v+''+ss+'';
			}
		},{
			text:'大小',dataIndex:'filesizecn',sortable:true
		},{
			text:'上传时间',dataIndex:'adddt',sortable:true
		},{
			text:'创建人',dataIndex:'optname',sortable:true
		},{
			text:'IP',dataIndex:'ip'
		},{
			text:'浏览器',dataIndex:'web'
		},{
			text:'下载次数',dataIndex:'downci',sortable:true,renderer:function(v,d,oi){
				return ''+v+'<a href="javascript:;" onclick="showvies{rand}('+oi+',2)">查看</a>';
			}
		},{
			text:'关联表',dataIndex:'mtype'
		},{
			text:'关联表ID',dataIndex:'mid'
		},{
			text:'ID',dataIndex:'id',sortable:true
		},{
			text:'关联模块',dataIndex:'mknum'
		},{
			text:'',dataIndex:'opt',renderer:function(v,d,oi){
				if(d.status=='0'){
					return '已删';
				}else{
					return '<a href="javascript:;" onclick="showvies{rand}('+oi+',0)">预览</a>&nbsp;<a href="javascript:;" onclick="showvies{rand}('+oi+',1)"><i class="icon-arrow-down"></i></a>';
				}
			}
		}],
		itemclick:function(){
			btn(false);
		},
		itemdblclick:function(d){
			c.openlogs(d);
		}
	});
	
	showvies{rand}=function(oi,lx){
		var d=a.getData(oi);
		if(lx==2){
			c.openlogs(d);
			return;
		}
		if(lx==1){
			js.downshow(d.id,d.filenum)
		}else{
			js.yulanfile(d.id,d.fileext,d.filepath,d.filename,d.filenum);
		}
	}
	
	var c = {
		del:function(){
			a.del({url:js.getajaxurl('delfile','{mode}','{dir}'),checked:checkeds});
		},
		search:function(){
			var s=get('key_{rand}').value;
			a.setparams({key:s,dt1:get('dt1_{rand}').value,dt2:get('dt2_{rand}').value},true);
		},
		openlogs:function(d){
			addtabs({name:'文件操作记录','num':'files'+d.id+'',url:'system,geren,files,fileid='+d.id+',filename='+jm.base64encode(d.filename)+''});
		},
		clickdt:function(o1, lx){
			$(o1).rockdatepicker({initshow:true,view:'date',inputid:'dt'+lx+'_{rand}'});
		}
	};
	
	function btn(bo){
		//get('del_{rand}').disabled = bo;
	}
	js.initbtn(c);
	a.settishi('<div class="tishi">提示：上传的文件可能会在某些单据上，删除请谨慎。</div>');
});
</script>


<div>


<table width="100%"><tr>
	<td>
		<input class="form-control" style="width:180px" id="key_{rand}"   placeholder="文件名/创建人/关联表">
	</td>
	<td nowrap>&nbsp;上传日期&nbsp;</td>
	<td nowrap>
		<div style="width:150px"  class="input-group">
			<input placeholder="" readonly class="form-control" id="dt1_{rand}" >
			<span class="input-group-btn">
				<button class="btn btn-default" click="clickdt,1" type="button"><i class="icon-calendar"></i></button>
			</span>
		</div>
	</td>
	<td nowrap>&nbsp;至&nbsp;</td>
	<td nowrap>
		<div style="width:150px"  class="input-group">
			<input placeholder="" readonly class="form-control" id="dt2_{rand}" >
			<span class="input-group-btn">
				<button class="btn btn-default" click="clickdt,2" type="button"><i class="icon-calendar"></i></button>
			</span>
		</div>
	</td>
	<td style="padding-left:10px">
		<button class="btn btn-default" click="search" type="button">搜索</button> 
	</td>
	
	
	
	<td width="80%"></td>
	<td align="right" nowrap>
	
		<button class="btn btn-danger" id="del_{rand}" click="del" type="button"><i class="icon-trash"></i> 删除</button>
	</td>
</tr>
</table>
</div>
<div class="blank10"></div>
<div id="veiw_{rand}"></div>