function initbodys(){
	form('applydt').readOnly=true;
	
	
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
					temp_aid:d.name,
					aid:d.value
				});
				$(form('count'+nam[2]+'')).attr('max', d.stock);
			}else{
				oii--;
				if(i==0){
					this.setrowdata(nam[0],nam[1],{
						temp_aid:'',
						aid:'0'
					});
				}
			}	
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