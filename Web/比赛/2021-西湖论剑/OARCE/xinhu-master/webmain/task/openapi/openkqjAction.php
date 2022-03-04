<?php
/**
*	群英考勤机
* 	请求地址如：http://127.0.0.1/api.php?m=opendkq&openkey=key
*/
class openkqjClassAction extends openapiAction
{
	private $snid = 0; //设备号ID
	
	public function initAction()
	{
		$this->display= false;
		$this->getpostdata();
	}
	
	public function indexAction()
	{
		echo 'ok';
	}
	
	//考勤机的使用
	public function apiAction()
	{
	 	//print_r($_SERVER);
		$path 	= arrvalue($GLOBALS,'_paths', $_SERVER['REQUEST_URI']);
		if(isempt($path))return '';
		$patha 	= explode('/', $path);
		
		$acta	= explode('?', $patha[count($patha)-1]);
		$act 	= $acta[0];
		$data 	= array();
		$num	= $this->get('sn'); //设备号
		if(!$num)return 'notdata';
		$dbs 	= m('kqjsn');
		$snid	= (int)$dbs->getmou('id',"`num`='$num'");
		if($snid==0)$snid = $dbs->insert(array(
			'num' => $num,
			'optdt' => $this->rock->now,
			'status' => 1
		));
		
		$this->snid = $snid;

		//考勤机请求
		if($act=='get'){
			$data= m('kqjcmd')->getcmd($this->snid); //向考勤机发送命令
		}
		
		//推送来的
		if($act=='post' && $this->postdata!=''){
			$data= m('kqjcmd')->postdata($this->snid, $this->postdata);
		}
		
		//设备上获取服务器时间
		if($act=='unixtime'){
			$this->rock->debugs(json_encode($_GET),'unixtime');
			$data['timezone'] = 'UTC';
			$data['unixtime'] = time()-8*3600; //由于北京时间多8小时所有要减
			$data['datetime'] = date('Y-m-d H:i:s', $data['unixtime']);
		}
		
		$barr['status'] = 1;
		$barr['info']   = 'ok';
		$barr['data']	= $data;
		return $barr;
	}
	
	//推送过来的数据
	//[{"id":"5056928","data":"return","return":[{"id":"0","result":"0"}]},{"id":"8993137","data":"return","return":[{"id":"0","result":"0"}]},{"id":"1275640","data":"return","return":[{"id":"0","result":"0"}]},{"id":"8085763","data":"return","return":[{"id":"0","result":"0"}]},{"id":"3896216","data":"return","return":[{"id":"0","result":"0"}]},{"id":"5036770","data":"return","return":[{"id":"0","result":"0"}]},{"id":"3554609","data":"return","return":[{"id":"0","result":"0"}]},{"id":"2144747","data":"return","return":[{"id":"0","result":"0"}]},{"id":"235805","data":"return","return":[{"id":"0","result":"0"}]},{"id":"186003","data":"return","return":[{"id":"0","result":"0"}]}]
}	