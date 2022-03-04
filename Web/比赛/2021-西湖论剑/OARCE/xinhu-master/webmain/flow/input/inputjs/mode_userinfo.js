//初始函数
function initbodys(){
	var disarr = 'state,workdate,syenddt,positivedt,quitdt,companyid'.split(',');
	if(js.request('optlx')=='my'){
		for(var i=0;i<disarr.length;i++)if(form(disarr[i]))form(disarr[i]).disabled=true;
	}
	form('jiguan').readOnly=false;
}

function changesubmit(){
	return {'optlx':js.request('optlx')};
}

