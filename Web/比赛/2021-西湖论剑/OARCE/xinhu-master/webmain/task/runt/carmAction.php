<?php
class carmClassAction extends runtAction
{
	//车辆提醒，每天运行
	public function runAction()
	{
		return m('flow')->initflow('carms')->todocarms($this->runrs['todoid']);
	}
	
}