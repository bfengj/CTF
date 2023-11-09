<?php
//检查登录状态
	session_start();
	if(!isset($_SESSION['user'])){die('请登录！！！');}
	include_once("../config.php");
	$zz = $_GET['zz'];
	$cs = array("","xxzxauto","bgsauto","wgbauto");
	$zz = $cs[$zz];
	$sql = "select value from config where title='$zz'";
	$requ = mysqli_query($con,$sql);
	$rs = mysqli_fetch_array($requ);
	$ziz = $rs['value'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>参数</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="../lib/layui-v2.5.5/css/layui.css" media="all">
    <link rel="stylesheet" href="../css/public.css" media="all">
</head>
<body>
<div class="layuimini-container">
    <div class="layuimini-main">
		<form class="layui-form" action="">
			<div class="layui-form-item">
				<label class="layui-form-label">连续录入</label>
				<div class="layui-input-block">
					<input type="checkbox" <?php if($ziz){ echo 'checked=""'; } ?> name="open" lay-skin="switch" lay-filter="switchTest" lay-text="ON|OFF">
					
				</div>
			</div>
		</form>
    </div>
</div>

<script src="../lib/layui-v2.5.5/layui.js" charset="utf-8"></script>
<script>
    layui.use(['form'], function () {
        var form = layui.form
			,$ = layui.jquery
            , layer = layui.layer;

        //监听指定开关
        form.on('switch(switchTest)', function (data) {
			if(this.checked){
				var v = 1;
			}else{
				var v = 0;
			}
			console.log(v);
			$.post("../action.php",{mode:'xgcs',sjk:'<?php echo $zz; ?>',v:v},function(res){
				console.log(res);
				var r=JSON.parse(res);
				if(r.status==1){
					layer.tips('修改成功', data.othis);
				}else{
					layer.tips('修改失败', data.othis);
					location.reload();
				}
				
			});
        });


    });
</script>

</body>
</html>