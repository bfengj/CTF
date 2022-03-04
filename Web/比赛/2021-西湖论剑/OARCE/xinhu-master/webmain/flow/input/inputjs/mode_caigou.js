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
}


function eventaddsubrows(xu,oj){
	c.setrowdata(xu,oj,{
		aid:'0'
	});
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


//触发事件最新不需要
function oninputblur22(na,zb,obj){
	if(zb==0)return;
	if(na=='temp_aid')changeaidtssk(obj);
}

//最新弃用
function changeaidtssk(o1){
	var nam = c.getxuandoi(o1.name);
	var val = form('aid'+nam[2]+'').value;
	js.ajax(geturlact('getgoods'),{aid:val},function(d){
		c.setrowdata(nam[0],nam[1],d);
		c.inputblur(form('money'), 0);
	},'get,json');
}