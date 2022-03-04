<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	
	
	var a = $('#view_{rand}').bootstable({
		tablename:'word',url:publicmodeurl('worc','getfiledata'),fanye:true,params:{'atype':'shateall'},
		columns:[{
			text:'类型',dataIndex:'fileext',renderer:function(v, d){
				if(!isempt(d.thumbpath))return '<img src="'+d.thumbpath+'" width="24" height="24">';
				var lxs = js.filelxext(v);
				return '<img src="web/images/fileicons/'+lxs+'.gif">';
			}
		},{
			text:'名称',dataIndex:'filename',editor:true,align:'left'
		},{
			text:'大小',dataIndex:'filesizecn',sortable:true
		},{
			text:'创建者',dataIndex:'optname',sortable:true
		},{
			text:'创建时间',dataIndex:'optdt',sortable:true
		},{
			text:'下载次数',dataIndex:'downci',sortable:true
		},{
			text:'',dataIndex:'opt',renderer:function(v,d,oi){
				if(d.ishui=='1')return '已删';
				return '<a href="javascript:;" onclick="showvies{rand}('+oi+',0)">预览</a>&nbsp;<a href="javascript:;" onclick="showvies{rand}('+oi+',1)"><i class="icon-arrow-down"></i></a>';
			}
		}]
	});
	showvies{rand}=function(oi,lx){
		var d=a.getData(oi);
		if(lx==1){
			js.downshow(d.fileid)
		}else{
			js.yulanfile(d.fileid,d.fileext,d.filepath,d.filename);
		}
	}
	var c = {
		reload:function(){
			a.reload();
		},
		daochu:function(){
			a.exceldown(nowtabs.name);
		},
		changlx:function(o1,lx){
			$("button[id^='state{rand}']").removeClass('active');
			$('#state{rand}_'+lx+'').addClass('active');
			var as = ['shateall','shatewfx'];
			a.setparams({'atype':as[lx]},true);
		},
		search:function(){
			var s=get('key_{rand}').value;
			a.setparams({key:s},true);
		}
	};
	js.initbtn(c);
	$('#optionview_{rand}').css('height',''+(viewheight-24)+'px');
});
</script>






<div>
	<table width="100%">
	<tr>
	<td>
		<input class="form-control" style="width:180px" id="key_{rand}"   placeholder="文件名/创建者">
	</td>
	<td style="padding-left:10px">
		<button class="btn btn-default" click="search" type="button">搜索</button> 
	</td>
	<td  width="90%" style="padding-left:10px">
		
		<div id="stewwews{rand}" class="btn-group">
		<button class="btn btn-default active" id="state{rand}_0" click="changlx,0" type="button">所有共享</button>
		<button class="btn btn-default" id="state{rand}_1" click="changlx,1" type="button">我共享的</button>
		</div>	
	</td>
	
	
	<td align="right" nowrap>
		<button class="btn btn-default" click="daochu,1" type="button">导出</button> 
	</td>
	</tr>
	</table>
	
</div>
<div class="blank10"></div>
<div id="view_{rand}"></div>