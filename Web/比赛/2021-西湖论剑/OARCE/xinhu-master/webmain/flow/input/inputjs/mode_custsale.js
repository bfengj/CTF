function initbodys(){
	$(form('custid')).change(function(){
		var val = this.value,txt='';
		if(val!=''){
			txt = this.options[this.selectedIndex].text;
		}
		form('custname').value=txt;
	});
}