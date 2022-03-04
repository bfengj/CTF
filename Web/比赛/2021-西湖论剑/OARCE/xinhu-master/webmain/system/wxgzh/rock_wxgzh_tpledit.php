<?php if(!defined('HOST'))die('not access');?>
<script >
$(document).ready(function(){
	{params}
	var id = params.id;
	
	var c  = {
		init:function(){
			js.ajax(js.getajaxurl('gettpledit','{mode}','{dir}',{id:id}),false,function(ret){
				c.showdatas(ret);
			},'get,json');
			$('#mosel_{rand}').change(function(){
				c.moselchang(this.value);
			});
		},
		save:function(){
			var soi = get('mosel_{rand}').value;
			if(soi==''){
				js.msg('msg','请选择关联OA系统模版');
				return;
			}
			var i,len=this.farr.length,fid,s1='',v1,c1;
			if(len==0){
				js.msg('msg','无法设置没有参数的模版消息');
				return;
			}
			for(i=0;i<len;i++){
				fid = this.farr[i];
				v1  = get('selectcan{rand}_'+i+'').value;
				c1  = get('selectcol{rand}_'+i+'').value;
				if(v1==''){
					js.msg('msg','请设置参数'+fid+'的值');
					get('selectcan{rand}_'+i+'').focus();
					return;
				}
				s1+=',"'+fid+'":{"value":"'+v1+'","color":"'+c1+'"}';
			}
			s1='{'+s1.substr(1)+'}';
			js.msg('wait','处理中...');
			js.ajax(js.getajaxurl('savetpledit','{mode}','{dir}'),{
				'modename' : this.marr[soi].title,
				'modeparams':s1,
				'id':id
			},function(){
				js.msg('success','保存成功');
			});
		},
		showdatas:function(ret){
			get('tplid_{rand}').value = ret.data.title;
			get('tpl_cont{rand}').value = ret.data.content;
			var i,s='',fid,v1,c1;
			s='<table class="table table-striped table-bordered" ><tr><th>参数</th><th><font color=red>*</font>设置参数</th><th>显示颜色</th></tr>';
			this.farr = [];
			this.marr = ret.marr;
			var sdt = [],xsl='';
			for(i=0;i<this.marr.length;i++){
				sdt.push({'value':i,'name':this.marr[i].title});
				if(this.marr[i].title==ret.data.modename)xsl=''+i+'';
			}
			js.setselectdata(get('mosel_{rand}'),sdt,'value');
			get('mosel_{rand}').value=xsl;
			this.moselchang(xsl);
			var csnse = {};
			if(!isempt(ret.data.modeparams))csnse=js.decode(ret.data.modeparams);
			for(i=0;i<ret.farr.length;i++){
				fid = ret.farr[i].replace('{','').replace('.DATA','');
				this.farr.push(fid);
				v1='';
				c1='';
				if(csnse[fid]){
					v1=csnse[fid].value;
					c1=csnse[fid].color;
				}
				s+='<tr><td>'+fid+'</td><td><input value="'+v1+'" onfocus="welecewete{rand}(this,1)" id="selectcan{rand}_'+i+'" style="width:300px" class="form-control"></td><td><input  id="selectcol{rand}_'+i+'" value="'+c1+'"  class="form-control" maxlength="7" style="width:100px" ></td></tr>';
			}
			s+='</table>';
			$('#setview{rand}').html(s);
			
		},
		moselchang:function(v){
			var s = '',v1;
			if(v!=''){
				var da = this.marr[v].params;
				for(v1 in da)s+='<a href="javascript:;" onclick="welecewete{rand}(\''+v1+'\',0)">＋'+da[v1]+'('+v1+')</a> &nbsp;';
			}
			$('#moselv_{rand}').html(s);
		}
	};
	js.initbtn(c);
	c.init();
	welecewete{rand}=function(ov,lx){
		if(lx==1){
			c.selobj = ov;
		}else{
			if(c.selobj)c.selobj.value=''+c.selobj.value+'{'+ov+'}';
		}
	}
});
</script>


<div align="left">
<div  style="padding:10px;">
	
	
		
		<table cellspacing="0" width="700" border="0" cellpadding="0">
		<tr>
			<td  align="right">模版标题：</td>
			<td class="tdinput"><input id="tplid_{rand}" readonly class="form-control"></td>
		</tr>
		
		<tr>
			<td  align="right" width="180">模版内容：</td>
			<td class="tdinput">
			<textarea id="tpl_cont{rand}" readonly style="height:150px" class="form-control"></textarea>
			</td>
		</tr>
		<tr>
			<td  align="right"><font color=red>*</font> 关联OA系统模版：</td>
			<td class="tdinput"><select id="mosel_{rand}" style="width:200px" class="form-control"><option value="">-请选择-</option></select><div id="moselv_{rand}"></div></td>
		</tr>
		<tr>
			<td  align="right" width="180">设置模版参考：</td>
			<td class="tdinput" id="setview{rand}">
			
				
			
			</td>
		</tr>
		
		<tr>
			<td  align="right"></td>
			<td style="padding:15px 0px" colspan="3" align="left"><button click="save" class="btn btn-success" type="button"><i class="icon-save"></i>&nbsp;保存</button>
			</span>
		</td>
		</tr>
		

	
</div>
</div>