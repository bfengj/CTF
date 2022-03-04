<?php defined('HOST') or die('not access');?>
<script >
$(document).ready(function(){
	{params}
	var modenum = params.modenum;
	var c={
		headers:'',
		yulan:function(){
			var cont = mobjs.val(),s='',a,a1,i,j,oi=0;
			s+='<table class="basetable" border="1">';
			s+='<tr><td></td>'+this.headers+'</tr>';
			a = cont.split('\n');
			for(i=0;i<a.length;i++){
				if(a[i]){
					oi++;
					a1 = a[i].split('	');
					s+='<tr>';
					s+='<td>'+oi+'</td>';
					for(j=0;j<a1.length;j++)s+='<td>'+a1[j]+'</td>';
					s+='</tr>';
				}
			}
			s+='</table>';
			$('#showview_{rand}').html(s);
		},
		init:function(){
			var vis = 'msgview_{rand}';
			js.setmsg('初始化中...','', vis);
			js.ajax(publicmodeurl(modenum,'initdaoru'),{'modenum':modenum},function(ret){
				js.setmsg('','', vis);
				c.initshow(ret);
			},'get,json');
		},
		initshow:function(ret){
			this.bitian='';
			this.headers='';
			var i,len=ret.length,d;
			for(i=0;i<len;i++){
				d=ret[i];
				this.headers+='<td>';
				if(d.isbt=='1'){
					this.bitian+=','+d.fields+'';
					this.headers+='<font color=red>*</font>';
				}
				this.headers+=''+d.name+'</td>';
			}
			this.yulan();
		},
		insrtss:function(){
			var val = mobjs.val();
			mobjs.val(val+'	');
			mobjs.focus();
		},
		saveadd:function(o1){
			var val = mobjs.val();
			var vis = 'msgview_{rand}';
			if(isempt(val)){
				js.setmsg('没有输入任何东西','', vis);
				return;
			}
			js.setmsg('处理中...','', vis);
			o1.disabled=true;
			js.ajax(js.getajaxurl('daorudata','{mode}','{dir}'),{importcont:val,'modenum':modenum},function(ds){
				if(ds.success){
					js.setmsg(ds.data,'green', vis);
					try{window['managelist'+modenum+''].reload()}catch(e){}
				}else{
					js.setmsg(ds.msg+'','red', vis);
					o1.disabled=false;
				}
			},'post,json',function(s){
				js.setmsg(s,'red', vis);
				o1.disabled=false;
			});
		},
		downxz:function(){
			var url = '?m=input&a=daoruexcel&d=flow&modenum='+modenum+'';
			js.open(url);
		},
		addfile:function(){
			js.upload('_daorufile{rand}',{maxup:'1','title':'选择Excel文件',uptype:'xls,xlsx','urlparams':'noasyn:yes'});
		},
		backup:function(fid){
			var o1 = get('upbtn{rand}');
			o1.disabled=true;
			o1.value='文件读取中...';
			js.ajax(js.getajaxurl('readxls','{mode}','{dir}'),{fileid:fid,'modenum':modenum},function(ret){
				if(ret.success){
					o1.value='读取成功';
					mobjs.val(ret.data);
					c.yulan();
				}else{
					js.msg('msg', ret.msg);
					o1.value='读取失败';
				}
				o1.disabled=false;
			},'get,json',function(s){
				js.msg('msg', s);
				o1.value=s;
				o1.disabled=false;
			});
		}
	}
	var mobjs = $('#maincont_{rand}');
	mobjs.keyup(function(){
		c.yulan();
	});

	
	js.initbtn(c);
	c.init();
	
	_daorufile{rand}=function(a,xid){
		var f = a[0];
		c.backup(f.id);
	}
	
});
</script>

<div align="left">
<div>请下面表格格式在Excel中添加数据，并复制到下面文本框中，也可以手动输入，<a click="downxz" href="javascript:;">[下载Excel模版]</a>。<br>多行代表多记录，整行字段用	分开，<a click="insrtss" href="javascript:;">插入间隔符</a></div>
<div style="padding:5px 0px"><input type="button" id="upbtn{rand}" click="addfile" class="btn btn-primary" value="选择Excel文件..."></div>
<div><textarea style="height:250px;" id="maincont_{rand}" class="form-control"></textarea></div>

<div id="showview_{rand}"></div>
<div style="padding:10px 0px"><a click="yulan" href="javascript:;">[预览]</a>&nbsp; &nbsp; <button class="btn btn-success" click="saveadd" type="button">确定导入</button>&nbsp; <span id="msgview_{rand}"></span></div>
<div class="tishi">请严格按照规定格式添加，否则数据将错乱哦，导入的字段可到[流程模块→表单元素管理]下设置，更多可查看<a href="<?=URLY?>view_daoru.html" target="_blank">[帮助]</a>。</div>
</div>