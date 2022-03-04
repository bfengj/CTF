openxieeditfile=function(d,d1){
	if(!d1.officebj){
		js.msg('msg','移动端在线编辑需要用“在线编辑服务”，请到“系统设置”下设置');
	}else{
		js.fileopt(d.fileid,2);
	}
}