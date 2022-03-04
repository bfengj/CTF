/**
*	定位文件
*	创建人：雨中磐石(rainrock)
*/

//jssdk回调过来的
js.jssdkcall  = function(bo){
	js.dw.start();//开始定位
}
var openfrom = '';
function initApp(){
	js.dw.start();
}
js.dw = {
	
	//开始定位
	init:function(isgzh){
		var dws = navigator.userAgent;
		if(dws.indexOf('REIMPLAT')>0)return;
		if(openfrom=='nppandroid' || openfrom=='nppios')return;
		if(isgzh==1){
			js.jssdkwxgzh();
		}else{
			js.jssdkwixin();
		}
	},

	dwbool:false,
	dwtimeer:false,
	ondwcall:function(){},
	ondwstart:function(){},
	ondwerr:function(){},
	ondwwait:function(){return false},
	
	start:function(){
		if(this.dwbool)return;
		this.dwbool = true;
		this.chaoshi();
		this.ondwstart(js.jssdkstate);
		if(js.jssdkstate != 1){
			this.htmldingw(0);
		}else{
			this.wxdingw();
		}
	},
	
	//定位等待
	wait:function(msg){
		var bo = this.ondwwait(msg);
		if(!bo)js.msg('wait',msg);
	},
	
	chaoshi:function(){
		clearTimeout(this.dwtimeer);
		this.dwtimeer = setTimeout(function(){
			var msg = '定位超时，请重新定位';
			js.msg('msg', msg);
			js.dw.ondwerr(msg);
			js.jssdkstate = 2;
			js.dw.dwbool=false;
		},20*1000);
	},
	clearchao:function(){
		clearTimeout(this.dwtimeer);
		this.dwbool = false;
	},
	
	//html5定位
	htmldingw:function(lx){
		var msg;
		if(appobj1('startLocation','appbacklocation')){
			this.wait('原生app定位中...');
			return;
		}
		
		if(api.startLocation){
			js.msg();
			if(api.systemType=='ios'){
				this.wait(''+api.systemType+'APP定位中...');
				api.startLocation({},function(ret,err){
					js.dw.appLocationSuc(ret,err);
				});
				return;
			}else if(lx==0){
				this.wait(''+api.systemType+'百度地图定位中...');
				if(!this.baiduLocation)this.baiduLocation = api.require('baiduLocation');
				if(this.baiduLocation){
					this.baiduLocation.startLocation({
						autoStop: false
					}, function(ret, err) {
						js.dw.baiduLocationSuc(ret,err);
					});
				}else{
					if(!this.bmLocation)this.bmLocation = api.require('bmLocation');
					if(this.bmLocation){
						this.bmLocation.configManager({
							coordinateType:'BMK09LL',accuracy:'hight_accuracy'
						});
						this.bmLocation.singleLocation({reGeocode:false},function(ret,err){
							var dtes = {};
							dtes.status = ret.status;
							if(ret.status){
								dtes.longitude = ret.location.longitude;
								dtes.latitude = ret.location.latitude;
							}
							js.dw.baiduLocationSuc(dtes,err);
						});
					}
				}
				return;
			}
		}
		
		if(!navigator.geolocation){
			msg = '不支持浏览器定位';
			js.msg('msg',msg);
			this.clearchao();
			js.dw.ondwerr(msg);
		}else{
			this.wait('浏览器定位中...');
			navigator.geolocation.getCurrentPosition(this.showPosition,this.showError,{
				enableHighAccuracy: true,
				timeout: 19000,
				maximumAge: 3000
			});
		}
	},
	
	
	//微信定位
	wxdingw:function(){
		var msg = '微信定位中...';
		if(js.isqywx)msg='企业微信定位中...';
		this.wait(msg);
		wx.getLocation({
			type: 'gcj02',
			success: function (res,err){
				js.dw.dwsuccess(res,err);
			},
			error:function(){
				js.jssdkstate = 2;
				js.dw.dwbool=false;
				js.dw.start(); 
			}
		});
	},
	appLocationSuc:function(ret,err){
		if(ret.status){
			if(!ret.accuracy)ret.accuracy = 200;
			this.dwsuccess(ret);
		}else{
			this.dwshibai(err.msg);
		}
	},
	
	baiduLocationSuc:function(ret,err){
		if(ret.status && ret.latitude){
			this.wait('定位成功，获取位置信息...');
			if(!ret.accuracy)ret.accuracy = 200;
			var center 		= new qq.maps.LatLng(ret.latitude,ret.longitude);
			this.translate(center, ret.accuracy, 3);
		}else{
			this.dwshibai('定位失败，检查是否给APP开定位权限');
		}
	},
	dwshibai:function(msg){
		this.clearchao();
		js.setmsg('');
		js.msg('msg', msg);
		this.ondwerr(msg);
	},
	dwsuccess:function(res){
		this.wait('定位成功，获取位置信息...');
		this.clearchao();
		var lat 	= parseFloat(res.latitude); // 纬度，浮点数，范围为90 ~ -90
        var lng 	= parseFloat(res.longitude); // 经度，浮点数，范围为180 ~ -180。
        var jid 	= parseFloat(res.accuracy); // 位置精度
		this.geocoder(lat,lng, jid);
	},
		
	showError:function (error){
		js.dw.clearchao();
		js.setmsg('');
		var msg='无法定位';
		switch(error.code){
		case error.PERMISSION_DENIED:
			msg="用户拒绝对获取地理位置的请求。"
			break;
		case error.POSITION_UNAVAILABLE:
			msg="位置信息是不可用的。"
			break;
		case error.TIMEOUT:
			msg="请求用户地理位置超时。"
			break;
		case error.UNKNOWN_ERROR:
			msg="未知错误。"
			break;
		}
		//var url = 'http://www.rockoa.com/view_wxgzh.html';
		//js.wx.alert('无法定位？请看<a href="'+url+'">[帮助设置]</a>');
		js.msg('msg', msg);
		js.dw.ondwerr(msg);
	},
	
	showPosition:function(position){
		var res 		= position.coords;
		var latitude 	= res.latitude;
		var longitude 	= res.longitude;
		var accuracy 	= parseFloat(res.accuracy);
		var center 		= new qq.maps.LatLng(parseFloat(latitude), parseFloat(longitude));
		js.dw.translate(center, accuracy, 1);
	},
	
	//坐标转化type1原始
	translate:function(center, accuracy, type){
		qq.maps.convertor.translate(center,type,function(res){
			var latitude 	= res[0].lat;
			var longitude 	= res[0].lng;
			if(latitude==0 || latitude==0){
				js.dw.dwshibai('无法获取位置信息失败');
			}else{
				js.dw.dwsuccess({
					latitude:latitude,
					longitude:longitude,
					accuracy:accuracy
				});
			}
		});	
	},
	
	//搜索位置
	geocoder:function(lat,lng, jid){
		if(!this.geocoderObj)this.geocoderObj 	= new qq.maps.Geocoder();
		var center 	= new qq.maps.LatLng(lat, lng);
		this.geocoderObj.getAddress(center);
		this.geocoderObj.setComplete(function(result){
			var address = result.detail.address;
			var dzarr 	= result.detail.addressComponents;
			//address 	= ''+dzarr.province+''+dzarr.city+''+dzarr.district+''+dzarr.street+'';
			//if(dzarr.streetnumber)address+=dzarr.streetnumber;
			
			//范围内地址
			var near = result.detail.nearPois,dist = 500,naddress,addressinfo;
			for(var i=0;i<near.length;i++){
				if(near[i].dist<dist){
					dist 	 = near[i].dist;
					naddress = ''+near[i].name+'('+near[i].address+')';
				}
			}
			if(dist<500)address = naddress;
			addressinfo = ''+address;
			if(jid>0)addressinfo+='(精确'+jid+'米)';
			js.msg();
			js.dw.ondwcall({
				latitude:lat,
				longitude:lng,
				accuracy:jid,
				address:address,
				addressinfo:addressinfo,
				detail:result.detail,
				center:center
			});
		});
		
		this.geocoderObj.setError(function() {
			//var msg = '无法获取位置';js.msg('msg', msg);js.dw.ondwerr(msg);
			js.msg();
			js.dw.ondwcall({
				latitude:lat,
				longitude:lng,
				accuracy:jid,
				address:'未知位置',
				addressinfo:'定位成功未知位置',
				detail:'未知位置',
				center:center
			});
		});
	}
};

//原生app定位中
appbacklocation=function(res){
	var latitude 	= res.latitude;
	var longitude 	= res.longitude;
	var accuracy 	= parseFloat(res.accuracy);
	js.dw.dwsuccess({
		latitude:latitude,
		longitude:longitude,
		accuracy:accuracy
	});
}