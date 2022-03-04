function initbodys(){
	$(form('modeid')).change(function(){
		var val = this.value,txt='';
		if(val!=''){
			txt = this.options[this.selectedIndex].text;
		}
		form('modename').value=txt;
		form('tonum').length=1;
		gettonumsel(val);
	});
}
function gettonumsel(sid){
	js.ajax(geturlact('gettonum'),{modeid:sid},function(a){
		js.setselectdata(form('tonum'), a);
	},'get,json');	
}