<?php
include_once("../config.php");
$sql = "select id,name from juese where status=1";
$requ = mysqli_query($con,$sql);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>添加用户</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="../lib/layui-v2.5.5/css/layui.css" media="all">
    <link rel="stylesheet" href="../css/public.css" media="all">
    <style>
        body {
            background-color: #ffffff;
        }
    </style>
</head>
<body>
<div class="layui-form layuimini-form">
    <div class="layui-form-item">
        <label class="layui-form-label required">名称</label>
        <div class="layui-input-block">
            <input type="text" name="username" lay-verify="required" lay-reqtext="名称不能为空" placeholder="请输入名称" value="" class="layui-input">
        </div>
    </div>
	<div class="layui-form-item">
		<label class="layui-form-label">选择角色</label>
		<div class="layui-input-block">
			<select name="juse">
				<?php 
					$x = '';
					while($rs = mysqli_fetch_array($requ)){
						if($x==''){$xz = 'selected=""';$x='1';}else{$xz = '';}
						echo '<option value="'.$rs['id'].'" '.$xz.'>'.$rs['name'].'</option>';
					}
				?>
			</select>
		</div>
	</div>
	<div class="layui-form-item">
		<label class="layui-form-label">权限部门</label>
		<div class="layui-input-inline">
			<select name="qxbm" lay-verify="required" lay-search="">
				<option value="0" selected="">全局</option>
				<?php
					$sql = "select id,name from danwei where status=1";
					$requ = mysqli_query($con,$sql);
					while($rs = mysqli_fetch_array($requ)){
						echo '<option value="'.$rs['id'].'">'.$rs['name'].'</option>';
					}
				?>
			</select>
		</div>
	</div>
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
			$.post("../action.php?mode=addyonghu",{name:data.field.username,js:data.field.juse,qxbm:data.field.qxbm},function(res){
				var r = JSON.parse(res);
				if(r.status==1){
					var index = layer.alert('添加成功。初始密码：111111', {
						title: '信息'
					}, function () {
						// 关闭弹出层
						layer.close(index);
						var iframeIndex = parent.layer.getFrameIndex(window.name);
						parent.layer.close(iframeIndex);
						//parent.location.reload();  
						parent.layui.table.reload('currentTableId', {
							url: '../action.php?mode=getuserlist'
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