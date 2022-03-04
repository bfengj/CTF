<?php
//日程待办
class flow_scheduldClassModel extends flowModel
{
	protected function flownexttodo($type){
		
		if($type=='daiban'){
			$btn[] = array(
				'key' 	=> 1,
				'name' 	=> '已完成',
				'replace_name' => '已完成',
				'color' 	=> 'blue',
				'is_bold' 	=> true
			);
			$btn[] = array(
				'key' 	=> 2,
				'name' 	=> '未完成',
				'replace_name' => '未完成',
				'color' 	=> 'red'
			);
			$this->flowweixinarr = array(
				//'task_id' 	=> ''.$this->mtable.'_'.$this->id.'',
				//'btn'		=> $btn
			);
			$cont = '发起人：{optname}'.chr(10).'时间：{startdt}';
			if(!isempt($this->rs['explain']))$cont.= '\n说明：{explain}';
			$cont.= '\n请尽快去处理';
			return array(
				'title' => '日程待办:{title}',
				'cont'	=> $cont
			);
		}
	}
}