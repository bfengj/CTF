<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	var a = $('#veiw_{rand}').bootstable({
		tablename:'todo',checked:true,fanye:true,statuschange:false,
		url:js.getajaxurl('publicstore','{mode}','{dir}'),defaultorder:'`id` desc',
		storeafteraction:'totaldaetods',storebeforeaction:'beforetotaldaetods',
		columns:[{
			text:'类型',dataIndex:'title',sortable:true,renderer:function(v,d){
				var s = v;
				if(d.status==1)s='<font color=#aaaaaa>'+v+'</font>';
				return s;
			}
		},{	
			text:'信息内容',align:'left',dataIndex:'mess',renderer:function(v,d, oi){
				var s = v;
				if(d.status==1)s='<font color=#aaaaaa>'+v+'</font>';
				if(!isempt(d.modenum) && d.mid>0){
					s+=' <a href="javascript:;" onclick="chsksse{rand}('+oi+')">[查看]</a>';
				}
				return s;
			}
		},{
			text:'时间',dataIndex:'optdt',sortable:true,renderer:function(v,d){
				var s = v;
				if(d.status==1)s='<font color=#aaaaaa>'+v+'</font>';
				return s;
			}
		},{
			text:'状态',dataIndex:'status',sortable:true,renderer:function(v){
				var s = '<font color=red>未读</font>';
				if(v==1)s='<font color=#aaaaaa>已读</font>';
				return s;
			}
		}],
		itemclick:function(d,oi, o1){
			//if(d.status==0)c.yidchuls(d.id, true);
		},
		load:function(){
			$('#guestbook_wd').html(''+a.getData('wdtotal'))
		}
	});
	chsksse{rand}=function(oi){
		var d=a.getData(oi);
		openxiangs(d.title,d.modenum,d.mid);
	}
	var c = {
		del:function(){
			a.del({checked:true});
		},
		yidu:function(o1, lx){
			var s = a.getchecked();
			if(s==''){
				js.msg('msg','没有选中行');
				return;
			}
			this.yidchuls(s, true);
		},
		yidchuls:function(s,lxs){
			if(lxs)js.msg('wait','处理中...');
			$.post(js.getajaxurl('todoyd','geren','system'),{s:s}, function(){
				if(lxs){
					js.msg('success','处理成功');
					a.reload()
				}
			});
		},
		daochu:function(){
			a.exceldown();
		}
	};

	
	js.initbtn(c);
});
</script>

<div>
<ul class="floats">
	<li class="floats50">
		<button class="btn btn-success" click="yidu,1"  type="button">标为已读</button>
	</li>
	<li class="floats50" style="text-align:right">
		<button class="btn btn-default"  click="daochu" type="button">导出</button>&nbsp;
		<button class="btn btn-danger" click="del" type="button"><i class="icon-trash"></i> 删除</button>
	</li>
</ul>
</div>
<div class="blank10"></div>
<div id="veiw_{rand}"></div>
