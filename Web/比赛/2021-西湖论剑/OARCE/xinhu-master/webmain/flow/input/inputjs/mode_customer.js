//初始函数
function initbodys(){
	
	c.onselectdata['sheng'] = function(d){
		if(!d.shengname)return;
		if(form('sheng'))form('sheng').value = d.shengname;
		if(form('shi'))form('shi').value = d.cityname;
	}
}

//地图选择
c.onselectmap=function(sna,res){
	var info = res.addressinfo;
	if(form('sheng'))form('sheng').value = info.province;
	if(form('shi'))form('shi').value = info.city;
	var dz = info.town;
	dz+=(info.streetNumber)?info.streetNumber:info.street;
	form(sna).value=dz;
}