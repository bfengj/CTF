function initbodys(){
	if(form('mintou')){
		var val = form('mintou').value;
		if(!val || val=='0')hidetou();
	}
	if(form('issms'))form('issms').checked=false; //每次编辑都取消
	
	//if(ismobile==0)$('#fileupaddbtn').append('&nbsp; <input onclick="addfujian()" value="相关文件用链接放入内容中" type="button" class="webbtn">');
}

//隐藏投票的
function hidetou(){
	$('#tablesub0').parent().parent().hide();
	$('#subtitletou').hide();
	c.fieldshide('startdt');
	c.fieldshide('enddt');
	c.fieldshide('maxtou');
}

//显示投票
function showtou(){
	$('#tablesub0').parent().parent().show();
	$('#subtitletou').show();
	c.fieldsshow('startdt');
	c.fieldsshow('enddt');
	c.fieldsshow('maxtou');
}

function oninputblur(fid,zb){
	if(fid=='mintou'){
		var val = form(fid).value;
		if(val=='' || val=='0'){
			hidetou();	
		}else{
			showtou();
		}
	}
}

function changesubmitbefore(){
	if(!form('mintou'))return;
	var min = form('mintou').value;
	if(min=='' || min=='0'){
		subdataminlen[0] = 0;
	}else{
		subdataminlen[0] = 2; //投票必须2个选项
	}
}
function changesubmit(d){
	if(d.zstart && d.zsend && d.zstart>d.zsend)return '展示截止日期必须大于开始日期';
	if(d.mintou>0){
		if(d.startdt=='')return '投票的开始时间不能为空';
		if(d.enddt=='')return '投票的截止时间不能为空';
		
		if(d.startdt>=d.enddt)return '截止时间必须大于开始时间';
	}
}

function addfujian(){
	var fid = form('fileid').value;
	if(!fid){
		js.msg('msg','没有上传文件');
		return;
	}
	js.ajax('api.php?m=upload&a=filedao',{fileid:fid},function(ret){
		if(ret){
			c.editorobj['content'].appendHtml(ret);
			$('#view_fileidview').html('');
			form('fileid').value='';
		}
	});
}