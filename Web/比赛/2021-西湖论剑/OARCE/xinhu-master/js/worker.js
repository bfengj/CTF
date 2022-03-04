/**
*	网址：www.rockoa.com
*	异步url使用
*/

var queue = {
	addqueuearr:[],
	yunoi:0,
	yunbool:false,
	callback:false,

	//添加返回对应序号
	add:function(cans){
		if(!cans)cans={};
		var url = cans.url;
		if(!url)return -1;
		cans.url = url;
		this.addqueuearr.push(cans);
		if(!this.yunbool)this.runqueue(this.yunoi);//开始运行
		return this.addqueuearr.length-1;
	},
	//运行
	runqueue:function(i){
		this.yunbool = true;
		var da = this.addqueuearr[i];
		if(!da)return;
		$.ajax({
			type:'get',url:da.url,
			success:function(str){
				if(da.success)da.success(str, da, i);
				queue.nextqueue();
			},
			error:function(e){
				var str = '处理出错:'+e.responseText+''
				if(da.error)da.error(str, da, i);
				queue.nextqueue();
			}
		});
	},
	clearqueue:function(){
		this.addqueuearr=[];
		this.yunoi=0;
		this.yunbool=false;
		this.callback=false;
	},
	nextqueue:function(){
		this.addqueuearr[this.yunoi]=false;
		var len = this.addqueuearr.length;
		var oi = this.yunoi+1;
		this.yunoi=oi;
		if(oi<len){
			this.runqueue(oi);
		}else{
			this.yunbool=false;
			this.callback=false;
		}
	},
	//发送一组地址:
	addlist:function(darr,funb, lxs){
		var oi=0,zong=darr.length,i,bers;
		if(!lxs)lxs='处理';
		this.callback=false;
		if(!funb)funb=function(){};
		if(zong>0)js.msg('wait',''+lxs+'中(<span id="chulsss">0%</span>)...',0);
		for(i=0;i<zong;i++){
			bers = function(str){
				oi++;
				var bili = (oi/zong)*100;
				$('#chulsss').html(''+js.float(bili)+'%');
				if(bili==100){
					js.msg('success',''+lxs+'完成');
					funb();
				}
				if(queue.callback){
					queue.callback(str, bili);
				}
			};
			queue.add({url:darr[i],success:bers,error:bers});
		}
	}
}