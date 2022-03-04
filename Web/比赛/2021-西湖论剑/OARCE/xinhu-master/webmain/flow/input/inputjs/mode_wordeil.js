//流程模块【wordeil.文件传送】下录入页面自定义js页面,初始函数
function initbodys(){
	
}

c.uploadback=function(fid, farr){
	if(fid=='filecontid'){
		var fname = farr.filename;
		if(form('title').value==''){
			form('title').value = fname.replace('.'+farr.fileext+'','')
		}
	}
}