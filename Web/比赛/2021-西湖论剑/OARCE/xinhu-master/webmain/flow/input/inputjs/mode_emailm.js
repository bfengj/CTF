function initbodys(){
	$(form('type')).change(function(){changestype(1)});
	changestype(0);
	get('btnchange_recename').onclick=function(){
		changeuserss(0);
	}
	get('btnchange_ccname').onclick=function(){
		changeuserss(1);
	}
	//读取转发邮件的内容
	var zfid = js.request('zfid');
	var zflx = js.request('zflx');
	if(mid==0&&zfid){
		js.ajax(geturlact('getzfcont',{zfid:zfid,zflx:zflx}),false,function(a){
			
			form('type').value = a.type; //外发还是 其他
			//转发
			if(a.zflx==0){
				form('title').value='转发：'+a.title+'';
				form('content').value=a.content;
				if(c.editorobj['content']){
					c.editorobj['content'].html(a.content);
				}else{
					form('content').value=a.content.replace(/<br>/g,"\n");
				}
				js.downupshow(a.filers,'fileidview');
				if(ismobile==1 && form('fileid'))f.fileobj.oldids=form('fileid').value;
			}
			//回复
			if(a.zflx==1){
				submitparams.huiid = a.id;
				form('title').value='回复：'+a.title+'';
				form('recename').value = a.sendname;
				form('receid').value = a.sendid;
				if(a.type==1){
					if(c.editorobj['content']){
						c.editorobj['content'].html(a.content);
					}else{
						form('content').value=a.content.replace(/<br>/g,"\n");
					}
				}
			}
		},'get,json');
	}
	if(mid==0){
		form('isturn').checked=true;
	}
	get('AltS').value='发送(S)';
	
	if(ismobile==0)$('#fileupaddbtn').append('&nbsp; <input onclick="addfujian()" value="相关文件用链接放入内容中" type="button" class="webbtn">');
}
var txlbool=false,txldata=[];
function changestype(lx){
	var val = form('type').value;
	if(lx==1){
		js.changeclear('changeccname');
		js.changeclear('changerecename');
	}
}
function changeuserss(lx){
	var val = form('type').value;
	if(val==1){
		var ss1 = 'receid',ss2='recename',tit='选择收信人';
		if(lx==1){
			var ss1 = 'ccid',ss2='ccname';
			tit='抄送给';
		}
		c.selectdata('getvcard,'+ss1+'', true, ss2, tit);
		return;
	}
	if(lx==0){
		js.changeuser('changerecename','changedeptusercheck');
	}else{
		js.changeuser('changeccname','changeusercheck');
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