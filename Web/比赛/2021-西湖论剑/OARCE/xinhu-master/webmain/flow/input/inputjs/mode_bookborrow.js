function initbodys(){
	$(form('bookid')).change(function(){
		var val = this.value,txt='';
		if(val!=''){
			txt = this.options[this.selectedIndex].text;
		}
		form('bookname').value=txt;
	});
}

function changesubmit(d){
	if(d.yjdt<=d.jydt)return '预计归还必须大于借阅日期';
}