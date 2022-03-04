js.tanbody=function(act,title,w,h,can1){
	var H	= (document.body.scrollHeight<winHb())?winHb()-5:document.body.scrollHeight;
	var W	= document.documentElement.scrollWidth+document.body.scrollLeft;
	if(!this.tanbodyindex)this.tanbodyindex=80;
	this.tanbodyindex++;
	var l=(winWb()-w)*0.5,t=(winHb()-h-20)*0.5;
	var s = '',mid	= ''+act+'_main',i,d;
	var can	= js.applyIf(can1,{html:'',btn:[]});
	if(w>winWb())w=winWb()-50;
	var s = '<div id="'+mid+'" style="position:fixed;background-color:#ffffff;left:'+l+'px;width:'+w+'px;top:'+t+'px;box-shadow:0px 0px 10px rgba(0,0,0,0.3);border-radius:5px">';
	s+='	<div style="-moz-user-select:none;-webkit-user-select:none;user-select:none;border-bottom:1px #eeeeee solid">';
	s+='		<table border="0" width="100%" cellspacing="0" cellpadding="0"><tr>';
	s+='			<td height="50" style="font-size:16px; font-weight:bold;color:#000000; padding-left:10px" width="100%" onmousedown="js.move(\''+mid+'\')" id="'+act+'_title">'+title+'</td>';
	s+='			<td><div  id="'+act+'_spancancel1" style="padding:0px 8px;height:50px;line-height:45px;overflow:hidden;cursor:pointer;color:gray;" onclick="js.tanclose(\''+act+'\')">✖</div></td>';
	s+='		</tr></table>';
	s+='	</div>';
	s+='	<div id="'+act+'_content" style="height">'+can.html+'</div>';
	s+='	<div id="'+act+'_bbar" style="height:60px;overflow:hidden;line-height:60px;padding:0px 10px;background:#f1f1f1;border-radius:0px 0px 5px 5px" align="right">&nbsp;<span id="msgview_'+act+'"></span>';
	for(i=0; i<can.btn.length; i++){
		d = can.btn[i];
		if(!d.bgcolor)d.bgcolor='';
		s+='<button type="button" oi="'+i+'" style="border-radius:5px;padding:8px 15px;margin-left:10px;background:'+d.bgcolor+'" id="'+act+'_btn'+i+'" class="webbtn">'+d.text+'</button>';
	}
	s+='		<button type="button" id="'+act+'_spancancel" onclick="js.tanclose(\''+act+'\')" style="border-radius:5px;padding:8px 15px;background:gray;margin-left:10px" class="webbtn">取消</button>';
	s+='		';
	s+='	</div>';
	s+='</div>';
	var str = '<div id="amain_'+act+'" oncontextmenu="return false" style="position:absolute;height:'+H+'px;width:'+W+'px;background:rgba(0,0,0,0.3);z-index:'+this.tanbodyindex+';left:0px;top:0px">'+s+'</div>';
	$('body').append(str);
	if(can.closed=='none'){
		$('#'+act+'_spancancel').remove();
		$('#'+act+'_spancancel1').remove();
	}
	if(can.bbar=='none'){
		var o1d = $('#'+act+'_bbar');
		o1d.html('');
		o1d.css({'height':'5px','background':'white'});
	}
	this.resizetan(act);
}
js.alert=function(msg,tit,fun,slx){
	if(!tit)tit='系统提示';
	var btn=[{text:'确定'}],act = 'alert';
	if(get(''+act+'_main'))act=''+act+''+this.getrand()+'';
	if(slx)btn.push({text:'取消',bgcolor:'gray'});
	this.tanbody(act,tit,400,300,{closed:'none',html:'<div style="padding:20px">'+msg+'</div>',btn:btn});
	$('#'+act+'_btn0').click(function(){
		var val = $('#rockpromptinput').val(),fbo=true;
		if(fun)fbo=fun('yes', val, act);
		if(fbo!==false)js.tanclose(act);
	});
	$('#'+act+'_btn1').click(function(){
		var val = $('#rockpromptinput').val();
		js.tanclose(act);
		if(fun)fun('no', val, act);
	});
	$('#'+act+'_spancancel1').remove();
}
js.alertclose=function(){
	this.tanclose('alert');
}
js.confirm=function(msg,fun,tit){
	js.alert(msg,fun,tit,1);
}
js.prompt=function(tit,msg,fun, txt,lxs){
	if(!txt)txt='';
	var msg1 = '<div>'+msg+'</div><div style="margin-top:5px">';
	if(!lxs)msg1 +='<input id="rockpromptinput" autocomplete="off" class="input" style="width:95%;border-radius:5px" value="'+txt+'" type="input">';
	if(lxs)msg1 +='<textarea id="rockpromptinput" autocomplete="off" class="input" style="width:95%;border-radius:5px;height:80px" type="input">'+txt+'</textarea>';
	msg1 += '</div>'
	js.alert(msg1,fun,tit,2);
	setTimeout("get('rockpromptinput').focus()",100);
}
js.resizetan=function(act){
	var mid	= ''+act+'_main';
	var o1  = $('#'+mid+'');
	var h1 = o1.height();
	var w1 = o1.width();	
	var l=(winWb()-w1)*0.5,t=(winHb()-h1-10)*0.5;if(t<0)t=5;
	o1.css({'left':''+l+'px','top':''+t+'px'});
}
js.tanclose=function(act){
	$('#amain_'+act+'').remove();
}