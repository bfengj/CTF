<?php
/**
*	基本接口
* 	请求地址如：http://URL/api.php?m=openbase&openkey=openkey&a=方法名
*/
class openbaseClassAction extends openapiAction
{
	
	/**
	*	例子1：新增模块单据，如新增流程单据等
	*	接口地址：http://URL/api.php?m=openbase&openkey=openkey&a=querydata
	*/
	public function querydataAction()
	{
		$arr 	 = $this->getpostarr();
		if(!$arr)return returnerror('not data');
		
		$modenum = $arr['basemodenum'];
		$adminid = $arr['baseoptid']; 	//提交的用户
		if(isempt($modenum))return returnerror('modenum is empty');
		$uid 	 = $this->getuserid($adminid);
		if($uid==0)return returnerror('['.$adminid.']用户不存在');
		$sm 	 = arrvalue($arr,'baseexplain'); //说明
		unset($arr['basemodenum']);
		unset($arr['baseoptid']);
		
		//此方法在文件：webmain/model/flowModel.php下的querydata方法。
		$mid = m('flow')->querydata($modenum, $arr, $sm); 
		
		return returnsuccess(array(
			'mid' => $mid,
		));
	}
	
	/**
	*	例子2：推送消息到应用中
	*	接口地址：http://URL/api.php?m=openbase&openkey=openkey&a=pushtodo
	*/
	public function pushtodoAction()
	{
		$mid 		= null;		//要推送单据ID
		$modenum	= 'daily'; 	//推送到哪个模块中,daily是工作日报模块
		
		//1、初始化流程
		$flow	= m('flow')->initflow($modenum, $mid);
		
		//2、调用推送方法，调用webmain/model/flow/flow.php 下的push方法
		$receid = '1'; 	//接收人ID,多个,分开，如推送给全部人员写：d1
		$gname	= '';	//推送到哪个应用下，为空，默认是跟当前模块名一样的应用。
		$cont 	= '这是个推送的内容';
		$title 	= '这是个标题'; //可以为空
		$flow->push($receid, $gname, $cont, $title);
		
		return '推送完成';
	}
	
	/**
	*	例子3：向单用户/会话发消息，聊天的。
	*/
	
}