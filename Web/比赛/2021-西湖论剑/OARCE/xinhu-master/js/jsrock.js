//js下的扩展，如计算日期差等

/**
*	计算2个日期时间差
*/
js.datediff=function(lx, start, end)
{
	var time1 = this.now('time', start)*0.001,time2;
	time2 = (end)?this.now('time', end) : new Date().getTime();
	time2 = time2*0.001;
	var jg 	  = 0;
	if(lx=='d'){
		jg 	 = 	time2-time1;
		jg	 = Math.ceil(jg/3600/24);
	}
	if(lx=='H'){
		jg 	 = time2-time1;
		jg	 = Math.ceil(jg/3600);
	}
	if(lx=='i'){
		jg 	 = time2-time1;
		jg	 = Math.ceil(jg/60);
	}
	if(lx=='s'){
		jg 	 = time2-time1;
	}
	return jg;
}

/**
*	日期相加
*/
js.adddate=function(dt,lx,v,type)
{
	var time1 = (dt) ? this.now('time', dt) : new Date().getTime();
	var jg 	  = 0;
	if(lx=='d')jg=v*3600*24;
	if(lx=='H')jg=v*3600;
	if(lx=='i')jg=v*60;
	if(lx=='s')jg=v;
	time1 = time1 + (jg * 1000);
	return this.now(type,time1);
}