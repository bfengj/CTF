<?php
//检查登录状态
    session_start();
	if(!isset($_SESSION['user'])){die('请登录！！！');}
	include_once("../config.php");
   		$juese = $_SESSION['juese'];
   		$sql="select value from juese where id='$juese'";
   		$requ=mysqli_query($con,$sql);
   		$rs=mysqli_fetch_array($requ);
   		$value=explode(",",$rs['value']);
   		if (in_array('17',$value)== false) 
   		{
    	die('您没有权限访问该页面！！！');
   		}

	if(isset($_GET['id'])){
		$id = $_GET['id'];
	}else{
		die('参数错误');
	}
	include_once("../config.php");
	$sql = "select name,value,bm from juese where id=$id";
	$requ = mysqli_query($con,$sql);
	$rs = mysqli_fetch_array($requ);
	$name = $rs['name'];
	$qxbm = $rs['bm'];
	$v = $rs['value'];
	$v = rtrim($v,',');
	$v = explode(",", $v);
	mysqli_free_result($requ);
	
	require_once('PHPTree.class.php');
	$sql = "select id,pid,title from system_menu where status=1 order by sort desc";
	$requ = mysqli_query($con,$sql);
	$menuList = array();
	while($rs = mysqli_fetch_array($requ)){
		if (in_array($rs['id'], $v)){
			$chk = true;
		}else{
			$chk = false;
		}

		$a=array("id"=>$rs['id'],"pId"=>$rs['pid'],"name"=>$rs['title'],"checked"=>$chk);
		array_push($menuList,$a);
	}
	$r = PHPTree::makeTreeForHtml($menuList,array(
		'expanded' => true
	));
	//echo json_encode($r);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>添加角色</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="../lib/layui-v2.5.5/css/layui.css" media="all">
    <link rel="stylesheet" href="../css/public.css" media="all">
	<link rel="stylesheet" href="../js/ztree/css/zTreeStyle/zTreeStyle.css" type="text/css">
	<script src="../lib/jquery-3.4.1/jquery-3.4.1.min.js" charset="utf-8"></script>
	<script type="text/javascript" src="../js/ztree/js/jquery.ztree.core.js"></script>
	<script type="text/javascript" src="../js/ztree/js/jquery.ztree.excheck.js"></script>
    <style>
        body {
            background-color: #ffffff;
        }
    </style>
	<SCRIPT type="text/javascript">
		<!--
		var setting = {
			check: {
				enable: true,
				chkboxType : { "Y" : "ps", "N" : "ps" }
			},
			data: {
				simpleData: {
					enable: true
				}
			}
		};

		var zNodes = <?php echo json_encode($r); ?>;
		
		$(document).ready(function(){
			$.fn.zTree.init($("#treeDemo"), setting, zNodes);
		});
		//-->
	</SCRIPT>
</head>
<body>
<div class="layui-form layuimini-form">
    <div class="layui-form-item">
        <label class="layui-form-label required">名称</label>
        <div class="layui-input-block">
            <input type="text" name="username" lay-verify="required" lay-reqtext="名称不能为空" placeholder="请输入名称" value="<?php echo $name; ?>" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label required">权限</label>
        <div class="layui-input-block">
			<ul id="treeDemo" class="ztree"></ul>
        </div>
    </div>
	<!--
	<div class="layui-form-item">
		<label class="layui-form-label">权限部门</label>
		<div class="layui-input-inline">
			<select name="qxbm" lay-verify="required" lay-search="">
				<option value="0" <?php if($qxbm == 0){echo 'selected=""';} ?>>全局</option>
				<?php
					$sql = "select id,name from danwei where status=1";
					$requ = mysqli_query($con,$sql);
					while($rs = mysqli_fetch_array($requ)){
						if($rs['id'] == $qxbm){$xz='selected=""';}else{$xz='';}
						echo '<option '.$xz.' value="'.$rs['id'].'">'.$rs['name'].'</option>';
					}
				?>
			</select>
		</div>
	</div>
	-->
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit lay-filter="saveBtn">确认保存</button>
        </div>
    </div>
</div>
<script src="../lib/layui-v2.5.5/layui.js" charset="utf-8"></script>
<script>
    layui.use(['form'], function () {
        var form = layui.form,
            layer = layui.layer,
            $ = layui.$;

        //监听提交
        form.on('submit(saveBtn)', function (data) {
			
			
			
			var qx = '';
			var zTree = $.fn.zTree.getZTreeObj("treeDemo");
			var checkedNodes = zTree.getCheckedNodes();
			console.log(checkedNodes);
			//alert("nodes:" + JSON.stringify(checkedNodes));  //查看
			for(var i=0;i<checkedNodes.length;i++){
				qx = qx + checkedNodes[i].id + ',';
			}
			
			$.post("../action.php?mode=editjuese",{id:<?php echo $id; ?>,u:data.field.username,value:qx,qxbm:0},function(res){
				var r = JSON.parse(res);
				if(r.status==1){
					var index = layer.alert('修改成功', {
						title: '信息'
					}, function () {
						// 关闭弹出层
						layer.close(index);
						var iframeIndex = parent.layer.getFrameIndex(window.name);
						parent.layer.close(iframeIndex);
						//parent.location.reload();  
						parent.layui.table.reload('currentTableId', {
							url: '../action.php?mode=getjueselist'
						});
					});
				}else{
					layer.msg(r.msg);
				}
			});
            return false;
        });

    });
</script>
</body>
</html>