//流程模块【custxiao.销售】下录入页面自定义js页面,初始函数
function initbodys(){
	
	//记录原来选择的
	c.daossdts=[];
	c.onselectdatabefore=function(){
		this.daossdts = this.getsubdata(0);
	}
	
	//这个是很复杂的叠加关系，时间久了谁也不知道是干嘛用的
	c.onselectdataall=function(fid,seld,sna,sid){
		if(!seld || !sna)return;
		var da = [];
		if(!seld[0]){
			da[0]=seld;
		}else{
			da = seld;
		}
		var nam = this.getxuandoi(fid),snua;
		var dao=this.daossdts,i,j,bo,d,oi=parseFloat(nam[1]),oii=-1;
		for(i=0;i<da.length;i++){
			d  = da[i];
			bo = false;
			for(j=0;j<dao.length;j++)if(dao[j].aid==d.value)bo=true;
			oii++;
			if(!bo){
				if(oii>0){
					snua= ''+nam[3]+''+nam[0]+'_'+(oi+oii)+'';
					if(!form(snua) || form(snua).value!=''){
						nam = this.insertrow(0,{},true);
					}else{
						nam[1]=parseFloat(nam[1])+1;
					}
				}
				this.setrowdata(nam[0],nam[1],{
					unit:d.unit,
					price:d.price,
					temp_aid:d.name,
					aid:d.value
				});
				
			}else{
				oii--;
				if(i==0){
					this.setrowdata(nam[0],nam[1],{
						unit:'',
						price:'0',
						temp_aid:'',
						aid:'0'
					});
				}
			}	
		}
	}
	
	$(form('custractid')).change(function(){
		var val = this.value,txt='';
		custractidchange(val);
	});
	
	c.onselectdatabefore=function(fid){
		if(fid=='custname'){
			var val=form('custractid').value;
			if(val>'0' && form('custname').value!='')return '客户已关联合同不需要重新选择';
		}
	}
}

function changesubmit(){
	if(get('tablesub0')){
		var da = c.getsubdata(0),d1;
		for(var i=0;i<da.length;i++){
			d1 = da[i];
			if(!d1.aid)return '行['+(i+1)+']必须选择物品';
			if(d1.count<=0)return '行['+(i+1)+']数量必须大于0';
		}
	}
}


function eventaddsubrows(xu,oj){
	c.setrowdata(xu,oj,{
		aid:'0'
	});
}

function custractidchange(v){
	if(v=='' || v=='0'){
		form('custid').value='';
		form('custname').value='';
		return;
	}
	js.ajax(geturlact('ractchange'),{ractid:v},function(a){
		form('custid').value=a.custid;
		form('custname').value=a.custname;
		form('money').value=a.money;
	},'get,json');
}