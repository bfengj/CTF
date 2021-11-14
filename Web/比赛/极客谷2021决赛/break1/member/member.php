<?php
/**
 * $Author: BEESCMS $
 * ============================================================================
 * 网站地址: http://www.beescms.com
 * 您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
*/

define('CMS','true');
include('../includes/init.php');
require_once('../includes/fun.php');
require_once('../includes/lib.php');
$action=isset($_REQUEST['action'])?trim($_REQUEST['action']):(($_SESSION['member_login']&&$_SESSION['member_id'])?'main':'login');
$lang=isset($_REQUEST['lang'])?trim($_REQUEST['lang']):'cn';
if(file_exists(LANG_PATH.'lang_'.$lang.'.php')){include(LANG_PATH.'lang_'.$lang.'.php');}//语言包缓存,数组$language
$_confing=get_confing($lang);
if(file_exists(DATA_PATH.'cache_cate/cate_list_'.$lang.'.php')){include(DATA_PATH.'cache_cate/cate_list_'.$lang.'.php');}//当前语言下的栏目
$tpl->template_dir=(IS_MB)?TP_PATH.$_confing['phone_template'].'/':TP_PATH.$_confing['web_template'].'/';//设置模板
$tpl->template_lang=$lang;
$tpl->template_is_cache=0;
$tpl->assign('lang',$lang);
$tpl->assign('act',$action);
//ajax登录
if($action=='ajax_login'){
	$user=fl_html(fl_value($_REQUEST['user']));
	$password=fl_html(fl_value($_REQUEST['password']));
	$code=fl_html(fl_value($_REQUEST['code']));
	if(!empty($_sys['safe_open'])){
		foreach($_sys['safe_open'] as $k=>$v){
		if($v=='2'){
			if($code!=$_SESSION['code']){die("{'login':'0','info':'{$language['member_msg2']}'}");}
		}
		}
		}
	if(empty($user)||empty($password)){die("{'login':'0','info':'{$language['member_smg3']}'}");}
	$password=md5($password);
	$sql="select*from ".DB_PRE."member where member_user='{$user}' and member_password='{$password}'";
	if(!$GLOBALS['mysql']->fetch_rows($sql)){die("{'login':0,'info':'{$language['member_msg3']}'}");}
	$rel=$GLOBALS['mysql']->fetch_asc($sql);
	if($rel[0]['is_disable']){die("{'login':'0','info':'{$language['member_msg4']}'}");}
	$_SESSION['member_user']=$rel[0]['member_user'];
	$_SESSION['member_login']="true";
	$_SESSION['member_id']=$rel[0]['id'];
	$_SESSION['member_purview']=$rel[0]['member_purview'];
	$m_time=time();
	$_SESSION['m_time']=$m_time;
	$m_ip=fl_html(fl_value(get_ip()));
	$_SESSION['m_ip']=$m_ip;
	$m_count=$rel[0]['member_count']+1;
	$sql="update ".DB_PRE."member set member_count={$m_count} where id=".$rel[0]['id'];
	unset($rel);
	$GLOBALS['mysql']->query($sql);
	$str=$_SESSION['member_user']."&nbsp;{$language['member_wel']}&nbsp;<a href=\"".CMS_SELF."member/member.php?action=main&lang=".$lang."\">".$language['member_msg28']."</a>|<a href=\"".CMS_SELF."/member/member.php?action=out&lang=".$lang."\">{$language['member_out']}</a>";
	die("{'login':'1','info':'".$str."'}");
}
//登录状态
if($action=='is_ajax_login'){
	$joson='';
	if(!empty($_SESSION['member_user'])&&!empty($_SESSION['member_id'])&&!empty($_SESSION['member_login'])){
		$str=$_SESSION['member_user']."&nbsp;{$language['member_wel']}&nbsp;<a href=\"".CMS_SELF."member/member.php?action=main&lang=".$lang."\">".$language['member_msg28']."</a>|<a href=\"".CMS_SELF."member/member.php?action=out&lang=".$lang."\">{$language['member_out']}</a>";
		$joson="{'login':'1','info':'".$str."'}";
	}
	die($joson);
}
//ajax退出
if($action=='ajajx_out'){
	$sql="update ".DB_PRE."member set member_time='{$_SESSION['m_time']}',member_ip='{$_SESSION['m_ip']}' where id={$_SESSION['member_id']}";
	$GLOBALS['mysql']->query($sql);
	$_SESSION['member_user']=array();
	$_SESSION['member_login']='';
	$_SESSION['member_id']='';
	$_SESSION['member_purview']='';
	$_SESSION['m_time']='';
	$_SESSION['m_ip']='';
	unset($_SESSION);
	$str='<form name="form1" action="member.php" method="post">';
	$str.='<label>'.$language['member_login_user'].':<input type="text" id="ajax_user" name="user" style="width:100px" /></label>';
	$str.='<label>'.$language['member_login_pass'].':<input type="password" id="ajax_password" name="password" style="width:100px" /></label>';
	$str.='<label>'.$language['member_login_code'].':<input type="text" name="code" id="ajax_code" style="width:50px" /><img src="{path plus/}/code.php" name="code" border="0" id="code" style="display:block; float:right;cursor:pointer; margin-left:5px; display:inline"/></label>';
	$str.='<label><input type="hidden" id="ajax_lang" value="{print $lang/}" name="lang" /><input type="button" style="border:0; margin-left:5px; display:inline; padding:0" src="{path template/}/images/login_input2.gif" name="go" id="ajax_login" /></label>';
	$str.='<label><a href="">'.$language['member_regist'].'</a></label>';
	$str.='</form>';
	$str.='<div class="clear"></div>';
	$json="{'out':'1','info':'".$str."'}";
}
//用户登录
if($action=='login'){
	$url=$language['member_msg29'];
	$is_code=0;
	if(!empty($_sys['safe_open'])){
		$is_code=in_array('2',$_sys['safe_open'])?1:0;
	}
	$tpl->assign('position',get_dy_position($url));//位置
	$tpl->assign('is_code',$is_code);
	$tpl->display('member_login');
}
//用户登录验证
elseif($action=='save_login'){
	$user=fl_html(fl_value($_POST['user']));
	$password=fl_html(fl_value($_POST['password']));
	$code=$_POST['code'];
		if(!empty($_sys['safe_open'])){
		foreach($_sys['safe_open'] as $k=>$v){
		if($v=='2'){
			if($code!=$_SESSION['code']){die("<script type=\"text/javascript\">alert('{$language['member_msg2']}');history.go(-1);</script>");}
		}
		}
		}
	if(empty($user)||empty($password)){die("<script type=\"text/javascript\">alert('{$language['member_smg3']}');history.go(-1);</script>");}
	$password=md5($password);
	$sql="select*from ".DB_PRE."member where member_user='{$user}' and member_password='{$password}'";
	if(!$GLOBALS['mysql']->fetch_rows($sql)){die("<script type=\"text/javascript\">alert('{$language['member_msg3']}');history.go(-1);</script>");}
	$rel=$GLOBALS['mysql']->fetch_asc($sql);
	if($rel[0]['is_disable']){die("<script type=\"text/javascript\">alert('{$language['member_msg4']}');history.go(-1);</script>");}
	$_SESSION['member_user']=$rel[0]['member_user'];
	$_SESSION['member_login']='true';
	$_SESSION['member_id']=$rel[0]['id'];
	$_SESSION['member_purview']=$rel[0]['member_purview'];
	$m_time=time();
	$_SESSION['m_time']=$m_time;
	$m_ip=fl_html(fl_value(get_ip()));
	$_SESSION['m_ip']=$m_ip;
	$m_count=$rel[0]['member_count']+1;
	$sql="update ".DB_PRE."member set member_count={$m_count} where id=".$rel[0]['id'];
	unset($rel);
	$GLOBALS['mysql']->query($sql);
	header("location:?action=main&lang=".$lang);
}
//用户注册
elseif($action=='regist'){
	$url=$language['member_msg30'];
	$tpl->assign('position',get_dy_position($url));//位置
	if(!$_sys['web_member'][0]){
		die("<script type=\"text/javascript\">alert('{$language['member_msg5']}');history.go(-1);</script>");
	}
	$is_code=0;
	if(!empty($_sys['safe_open'])){
		$is_code=in_array('2',$_sys['safe_open'])?1:0;
	}
	$tpl->assign('is_code',$is_code);
	$tpl->display('member_login');
}
//用户注册处理
elseif($action=='save_reg'){
	$user=fl_html(fl_value($_POST['user']));
	$password=fl_html(fl_value($_POST['password']));
	$password2=fl_html(fl_value($_POST['password2']));
	$nich=fl_html(fl_value($_POST['nich']));
	$mail=fl_html(fl_value($_POST['mail']));
	$code=fl_html(fl_value($_POST['code']));
	if(!$_sys['web_member'][0]){
		die("<script type=\"text/javascript\">alert('{$language['member_msg5']}');history.go(-1);</script>");
	}
	if(!check_str($user,'/^[a-zA-Z][a-zA-Z0-9]{3,15}$/')){die("<script type=\"text/javascript\">alert('{$language['member_msg6']}');history.go(-1);</script>");}
	if(!check_str($nich,'/^[a-zA-Z][a-zA-Z0-9]{3,15}$/')){die("<script type=\"text/javascript\">alert('{$language['member_msg7']}');history.go(-1);</script>");}
	if(empty($password)||empty($password2)){die("<script type=\"text/javascript\">alert('{$language['member_msg8']}');history.go(-1);</script>");}
	if($password!=$password2){die("<script type=\"text/javascript\">alert('{$language['member_msg9']}');history.go(-1);</script>");}
	if(!check_str($mail,'/^[0-9a-z]+@(([0-9a-z]+)[.])+[a-z]{2,3}$/')){die("<script type=\"text/javascript\">alert('{$language['member_msg10']}');history.go(-1);</script>");}
	if(!empty($_sys['member_no_name'])){$no_name=explode('|',$_sys['member_no_name']);}
	if(is_array($no_name)){
		if(in_array($user,$no_name)){die("<script type=\"text/javascript\">alert('【".$user."】{$language['member_msg11']}');history.go(-1);</script>");}
	}

	if(!empty($_sys['safe_open'])){
		foreach($_sys['safe_open'] as $k=>$v){
		if($v=='1'){
			if($code!=$_SESSION['code']){die("<script type=\"text/javascript\">alert('{$language['member_msg2']}');history.go(-1);</script>");}
		}
		}
		}
	
	
	$sql="select id from ".DB_PRE."member where member_user='{$user}'";
	if($GLOBALS['mysql']->fetch_rows($sql)){die($language['member_msg12']);}
	if(!$_sys['member_mail'][0]){
	$sql="select id from ".DB_PRE."member where member_mail='{$mail}'";
	if($GLOBALS['mysql']->fetch_rows($sql)){die($mail.$language['member_msg13']);}
	}
	$addtime=time();
	$password=md5($password);
	$sql="insert into ".DB_PRE."member (member_user,member_password,member_nich,member_mail,member_purview) values ('{$user}','{$password}','{$nich}','{$mail}',1)";
	$GLOBALS['mysql']->query($sql);
	$last_id=$GLOBALS['mysql']->insert_id();
	$ip=fl_html(fl_value(get_ip()));
	$sql="update ".DB_PRE."member set member_time='{$addtime}',member_ip='{$ip}' where id={$last_id}";
	$GLOBALS['mysql']->query($sql);
	die("<script type=\"text/javascript\">alert('{$language['member_msg14']}');location.href='member.php?action=login&lang=".$lang."';</script>");
}
//用户中心
elseif($action=='main'){
	$url=$language['member_msg28'];
	$tpl->assign('position',get_dy_position($url));//位置
	if(empty($_SESSION['member_user'])||empty($_SESSION['member_id'])||empty($_SESSION['member_login'])){die('<script type="text/javascript">location.href=\'?action=login&lang='.$lang.'\';</script>');}
	$purview=$language['member_msg31'];
	if($_SESSION['member_purview']){
		$sql="select member_group_name from ".DB_PRE."member_group where id={$_SESSION['member_purview']}";
		$rel=$GLOBALS['mysql']->fetch_asc($sql);
		$purview=$rel[0]['member_group_name'];
		unset($rel);
	}
	$sql="select*from ".DB_PRE."member where id=".intval($_SESSION['member_id']);
	$rel=$GLOBALS['mysql']->fetch_asc($sql);
	$sql="select count(*) as ask,member from ".DB_PRE."ask where member=".$rel[0]['id']." group by member";
	$arr=$GLOBALS['mysql']->fetch_asc($sql);
	$ask_count=isset($arr[0]['ask'])?$arr[0]['ask']:'';
	unset($arr);
	$tpl->assign('ask_count',$ask_count);
	$tpl->assign('login_time',date('Y-m-d H:m:s',$rel[0]['member_time']));
	$tpl->assign('login_ip',$rel[0]['member_ip']);
	$tpl->assign('login_count',$rel[0]['member_count']);
	$tpl->assign('purview',$purview);
	$tpl->assign('member',$_SESSION['member_user']);
	$tpl->display('member_login');
}
//用户信息
elseif($action=='info'){
	$url=$language['member_msg28'];
	$tpl->assign('position',get_dy_position($url));//位置
	if(empty($_SESSION['member_user'])||empty($_SESSION['member_id'])||empty($_SESSION['member_login'])){die('<script type="text/javascript">location.href=\'?action=login&lang='.$lang.'\';</script>');}
	$sql="select*from ".DB_PRE."member where id=".$_SESSION['member_id'];
	$rel=$GLOBALS['mysql']->fetch_asc($sql);
	if(!empty($rel[0]['member_birth'])){$arr=explode('-',$rel[0]['member_birth']);}
	$tpl->assign('year',isset($arr['0'])?$arr['0']:'');
	$tpl->assign('month',isset($arr['1'])?$arr['1']:'');
	$tpl->assign('day',isset($arr['2'])?$arr['2']:'');
	$tpl->assign('info',$rel[0]);
	$tpl->display('member_login');
}
//处理用户信息
elseif($action=='save_info'){
	if(empty($_SESSION['member_user'])||empty($_SESSION['member_id'])||empty($_SESSION['member_login'])){die('<script type="text/javascript">location.href=\'?action=login&lang='.$lang.'\';</script>');}
	$birthdayYear=fl_html(fl_value(intval($_POST['birthdayYear'])));
	$birthdayMonth=fl_html(fl_value(intval($_POST['birthdayMonth'])));
	$birthdayDay=fl_html(fl_value(intval($_POST['birthdayDay'])));
	$sex=fl_html(fl_value(intval($_POST['sex'])));
	$sex=empty($sex)?0:$sex;
	$mail=fl_html(fl_value($_POST['mail']));
	$qq=fl_html(fl_value($_POST['qq']));
	$tel=fl_html(fl_value($_POST['tel']));
	$phone=fl_html(fl_value($_POST['phone']));
	$submit=$_POST['submit'];
	if(!empty($qq)){
	if(!check_str($qq,'/^[1-9][0-9]*$/')){die("<script type=\"text/javascript\">alert('{$language['member_msg15']}');history.go(-1);</script>");}
	}
	if(!empty($phone)){
	if(!check_str($phone,'/^[1-9][0-9]*$/')){die("<script type=\"text/javascript\">alert('{$language['member_msg16']}');history.go(-1);</script>");}
	}
	if(empty($submit)){die("<script type=\"text/javascript\">alert('{$language['member_msg17']}');history.go(-1);</script>");}
	$birth=$birthdayYear.'-'.$birthdayMonth.'-'.$birthdayDay;
	$sql="update ".DB_PRE."member set member_tel='{$tel}',member_phone='{$phone}',member_birth='{$birth}',member_sex =".$sex.",member_qq='{$qq}' where id={$_SESSION['member_id']}";
	$GLOBALS['mysql']->query($sql);
	die("<script type=\"text/javascript\">alert('{$language['member_msg18']}');history.go(-1);</script>");
}
//添加咨询
elseif($action=='add_ask'){
	$url=$language['member_msg28'];
	$tpl->assign('position',get_dy_position($url));//位置
	if(empty($_SESSION['member_user'])||empty($_SESSION['member_id'])||empty($_SESSION['member_login'])){die('<script type="text/javascript">location.href=\'?action=login&lang='.$lang.'\';</script>');}
	$tpl->assign('member_id',$_SESSION['member_id']);
	$tpl->display('member_login');
}
//处理咨询
elseif($action=='save_add_ask'){
	if(empty($_SESSION['member_user'])||empty($_SESSION['member_id'])||empty($_SESSION['member_login'])){die('<script type="text/javascript">location.href=\'?action=login&lang='.$lang.'\';</script>');}
	$member_id=fl_html(fl_value(intval($_POST['member_id'])));
	$title=fl_html(fl_value($_POST['title']));
	$content=fl_html(fl_value($_POST['content']));
	if(empty($member_id)){die("{$language['msg_info10']}");}
	if(empty($title)||empty($content)){die("{$language['member_msg19']}");}
	$addtime=time();
	$sql="insert into ".DB_PRE."ask (title,content,addtime,member) values ('{$title}','{$content}','{$addtime}',{$member_id})";
	$GLOBALS['mysql']->query($sql);
	die("<script type=\"text/javascript\">alert('{$language['member_msg20']}');history.go(-1);</script>");
}
//修改咨询
elseif($action=='xg_ask'){
	$url=$language['member_msg28'];
	$tpl->assign('position',get_dy_position($url));//位置
	if(empty($_SESSION['member_user'])||empty($_SESSION['member_id'])||empty($_SESSION['member_login'])){die('<script type="text/javascript">location.href=\'?action=login&lang='.$lang.'\';</script>');}
	$id=intval(fl_value($_GET['id']));
	$member_id=intval(fl_value($_GET['member_id']));
	if(empty($id)){die("<script type=\"text/javascript\">alert('{$language['msg_info10']}');history.go(-1);</script>");}
	$sql="select*from ".DB_PRE."ask where id={$id} and member={$member_id}";
	if(!$GLOBALS['mysql']->fetch_rows($sql)){die("<script type=\"text/javascript\">alert('{$language['member_msg21']}');history.go(-1);</script>");}
	$rel=$GLOBALS['mysql']->fetch_asc($sql);
	if(!empty($rel[0]['reply'])){die("<script type=\"text/javascript\">alert('{$language['member_msg22']}');history.go(-1);</script>");}
	$tpl->assign('ask',$rel[0]);
	$tpl->display('member_login');
}
//处理修改咨询
elseif($action=='save_xg_ask'){
	$url=$language['member_msg28'];
	$tpl->assign('position',get_dy_position($url));//位置
	if(empty($_SESSION['member_user'])||empty($_SESSION['member_id'])||empty($_SESSION['member_login'])){die('<script type="text/javascript">location.href=\'?action=login&lang='.$lang.'\';</script>');}
	$id=intval(fl_value($_POST['id']));
	$member_id=intval(fl_value($_POST['member_id']));
	$content=fl_html(fl_value($_POST['content']));
	if(empty($id)||empty($member_id)){die("<script type=\"text/javascript\">alert('{$language['msg_info10']}');history.go(-1);</script>");}
	if(empty($content)){die("<script type=\"text/javascript\">alert('{$language['member_msg23']}');history.go(-1);</script>");}
	$sql="update ".DB_PRE."ask set content='{$content}' where id={$id}";
	$GLOBALS['mysql']->query($sql);
	die("<script type=\"text/javascript\">alert('{$language['member_msg24']}');history.go(-1);</script>");
}
//用户咨询列表
elseif($action=='ask'){
	$url=$language['member_msg28'];
	$tpl->assign('position',get_dy_position($url));//位置
	if(empty($_SESSION['member_user'])||empty($_SESSION['member_id'])||empty($_SESSION['member_login'])){die('<script type="text/javascript">location.href=\'?action=login&lang='.$lang.'\';</script>');}
	$sql="select*from ".DB_PRE."ask where member=".intval($_SESSION['member_id'])." order by addtime desc";
	$rel=$GLOBALS['mysql']->fetch_asc($sql);
	$tpl->assign('ask_list',$rel);
	$tpl->display('member_login');
}
//显示咨询
elseif($action=='show_ask'){
	$url=$language['member_msg28'];
	$tpl->assign('position',get_dy_position($url));//位置
	if(empty($_SESSION['member_user'])||empty($_SESSION['member_id'])||empty($_SESSION['member_login'])){die('<script type="text/javascript">location.href=\'?action=login&lang='.$lang.'\';</script>');}
	$id=intval($_GET['id']);
	$member_id=intval(fl_value($_GET['member_id']));
	if(empty($id)||empty($member_id)){die("<script type=\"text/javascript\">alert('{$language['msg_info10']}');history.go(-1);</script>");}
	$sql="select*from ".DB_PRE."ask where id={$id} and member={$member_id}";
	if(!$GLOBALS['mysql']->fetch_rows($sql)){die("<script type=\"text/javascript\">alert('{$language['member_msg21']}');history.go(-1);</script>");}
	$rel=$GLOBALS['mysql']->fetch_asc($sql);
	$tpl->assign('ask',$rel[0]);
	$tpl->display('member_login');
}
//用户收藏
elseif($action=='coll'){
	$url=$language['member_msg28'];
	$tpl->assign('position',get_dy_position($url));//位置
	if(empty($_SESSION['member_user'])||empty($_SESSION['member_id'])||empty($_SESSION['member_login'])){die('<script type="text/javascript">location.href=\'?action=login&lang='.$lang.'\';</script>');}
	$maintb=DB_PRE."maintb";
	$page=empty($_GET['page'])?1:intval($_GET['page']);
	$filt="member_id=".intval($_SESSION['member_id']);
	$pagesize=20;
	$pagenum=($page-1)*$pagesize;
	$query='&id='.$_SESSION['member_id'].'&action=coll&lang='.$lang;
	$total_num=$GLOBALS['mysql']->fetch_rows("select m.id from ".DB_PRE."collect as m where {$filt}");
	$total_page=ceil($total_num/$pagesize);
	$sql="select e.*,m.title from ".DB_PRE."collect as e left join {$maintb} as m on e.arc_id=m.id where {$filt} group by e.id order by e.addtime desc limit {$pagenum},{$pagesize}";
	$rel=$GLOBALS['mysql']->fetch_asc($sql);
	$page=page('member.php',$page,$query,$total_num,$total_page);
	$tpl->assign('coll',$rel);
	$tpl->assign('page',$page);
	$tpl->display('member_login');
}
//删除收藏
elseif($action=='del_coll'){
	$url=$language['member_msg28'];
	$tpl->assign('position',get_dy_position($url));//位置
	if(empty($_SESSION['member_user'])||empty($_SESSION['member_id'])||empty($_SESSION['member_login'])){die('<script type="text/javascript">location.href=\'?action=login&lang='.$lang.'\';</script>');}
	$id=empty($id)?'':intval($id);
	if(empty($id)){die("<script type=\"text/javascript\">alert('{$language['msg_info10']}');history.go(-1);</script>");}
	$sql="delete from ".DB_PRE."collect where id={$id}";
	$GLOBALS['mysql']->query($sql);
	die("<script type=\"text/javascript\">alert('{$language['member_msg25']}');history.go(-1);</script>");
}
//添加收藏
elseif($action=='add_coll'){

}
//修改密码
elseif($action=='password'){
	$url=$language['member_msg28'];
	$tpl->assign('position',get_dy_position($url));//位置
	if(empty($_SESSION['member_user'])||empty($_SESSION['member_id'])||empty($_SESSION['member_login'])){die('<script type="text/javascript">location.href=\'?action=login&lang='.$lang.'\';</script>');}
	$tpl->display('member_login');
}
//处理密码
elseif($action=='save_password'){
	if(empty($_SESSION['member_user'])||empty($_SESSION['member_id'])||empty($_SESSION['member_login'])){die('<script type="text/javascript">location.href=\'?action=login&lang='.$lang.'\';</script>');}
	$password_use=trim(fl_html(fl_value($_POST['password_use'])));
	$password_new=trim(fl_html(fl_value($_POST['password_new'])));
	$password_new2=trim(fl_html(fl_value($_POST['password_new2'])));
	if(empty($password_use)||empty($password_new)||empty($password_new2)){die("<script type=\"text/javascript\">alert('{$language['member_msg8']}');history.go(-1);</script>");}
	$sql="select member_password  from ".DB_PRE."member where id=".$_SESSION['member_id'];
	$rel=$GLOBALS['mysql']->get_row($sql);
	if(md5($password_use)!=$rel){die("<script type=\"text/javascript\">alert('{$language['member_msg26']}');history.go(-1);</script>");}
	if($password_new!=$password_new2){die("<script type=\"text/javascript\">alert('{$language['member_msg9']}');history.go(-1);</script>");}
	$sql="update ".DB_PRE."member set member_password='".md5($password_new)."' where id=".$_SESSION['member_id'];
	$GLOBALS['mysql']->query($sql);
	die("<script type=\"text/javascript\">alert('{$language['member_msg18']}');history.go(-1);</script>");
}
//注销登录
elseif($action=='out'){
	$sql="update ".DB_PRE."member set member_time='{$_SESSION['m_time']}',member_ip='{$_SESSION['m_ip']}' where id={$_SESSION['member_id']}";
	$GLOBALS['mysql']->query($sql);
	$_SESSION['member_user']=array();
	$_SESSION['member_login']='';
	$_SESSION['member_id']='';
	$_SESSION['member_purview']='';
	$_SESSION['m_time']='';
	$_SESSION['m_ip']='';
	unset($_SESSION);
	die("<script type=\"text/javascript\">alert('{$language['member_msg27']}');location.href='?lang=".$lang."';</script>");
}

?>
