<?php
	include_once("../config.php");
	$zz = $_GET['zz'];
	$bm = $_SESSION['bm'];
	if($bm == 0){
		$bm = '';
	}else{
		$bm = " and id=$bm";
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>资产导入</title>
    <link rel="stylesheet" href="../lib/layui-v2.5.5/css/layui.css" media="all">
    <link rel="stylesheet" href="../css/public.css" media="all">
</head>
<body>
<div class="layuimini-container">
    <div class="layuimini-main">

        <blockquote class="layui-elem-quote">
            导入的Excel文件需要按照固定格式（下载示例查看）编辑。<br>
			表格中的“资产类型”、“资产状态”、“部门”、“网络标识”等内容不能直接填写文字，需要文字内容在系统中对应的ID值，具体值可在下方查询。<br>
			仅支持xls和xlsx格式文件导入。需要下方表格中指定有效数据所在行。<br>
            <a href="/demo.xlsx" target="_blank" class="layui-btn layui-btn-danger">下载示例</a>
        </blockquote>
		
		
		<form class="layui-form" action="">
			<div class="layui-form-item">
				<div class="layui-inline">
					<label class="layui-form-label">资产类型</label>
					<div class="layui-input-inline">
						<select name="zclx" lay-verify="required" lay-search="" lay-filter="changeleibie">
							<?php
								$sql = "select id,name from zclx where status=1 and zcfl=$zz";
								$requ = mysqli_query($con,$sql);
								while($rs = mysqli_fetch_array($requ)){
									echo '<option value="'.$rs['id'].'">'.$rs['name'].' → ID：'.$rs['id'].'</option>';
								}
							?>
						</select>
					</div>
				</div>
				<div class="layui-inline">
					<label class="layui-form-label">资产状态</label>
					<div class="layui-input-inline">
						<select name="zczt" lay-verify="required" lay-search="">
							<?php
								$sql = "select id,name from zhuangtai where status=1";
								$requ = mysqli_query($con,$sql);
								while($rs = mysqli_fetch_array($requ)){
									echo '<option value="'.$rs['id'].'">'.$rs['name'].' → ID：'.$rs['id'].'</option>';
								}
							?>
						</select>
					</div>
				</div>
				<div class="layui-inline">
					<label class="layui-form-label">单位</label>
					<div class="layui-input-inline">
						<select name="bm" lay-verify="required" lay-search="">
							<?php
								$sql = "select id,name from danwei where status=1 $bm";
								$requ = mysqli_query($con,$sql);
								while($rs = mysqli_fetch_array($requ)){
									echo '<option value="'.$rs['id'].'">'.$rs['name'].' → ID：'.$rs['id'].'</option>';
								}
							?>
						</select>
					</div>
				</div>
				<div class="layui-inline">
					<label class="layui-form-label">网络标识</label>
					<div class="layui-input-inline">
						<select name="wlbs" lay-verify="required" lay-search="">
							<option value="0" selected="">未分配 → ID:0</option>
							<option value="1">内网 → ID:1</option>
							<option value="2">外网 → ID:2</option>
						</select>
					</div>
				</div>
			</div>
			<hr>
			<div class="layui-form-item">
				<div class="layui-inline">
					<label class="layui-form-label">上传文件</label>
					<button type="button" class="layui-btn layui-btn-primary" id="upload4"><i class="layui-icon"></i>Excel文件</button>
					<input type="hidden" name="upifle" id="upifle" />
				</div>
			</div>
			
			<div class="layui-form-item" >
				<div class="layui-inline">
					<label class="layui-form-label">起始行</label>
					<div class="layui-input-inline">
						<input type="number" name="qi" id="qi" value="2" lay-verify="number" autocomplete="off" class="layui-input">
					</div>
				</div>
				<div class="layui-inline">
					<label class="layui-form-label">终止行</label>
					<div class="layui-input-inline">
						<input type="number" name="zhi" id="zhi" autocomplete="off" class="layui-input">
					</div>
				</div>
			</div>
			<div class="layui-form-item">
				<div class="layui-input-block">
					<button class="layui-btn" lay-submit="" lay-filter="demo1">导入</button>
				</div>
			</div>
		</form>
		<div id="upinfo">
		
		
		</div>
    </div>
</div>
<script src="../lib/layui-v2.5.5/layui.js" charset="utf-8"></script>
<script src="../lib/jquery-3.4.1/jquery-3.4.1.min.js" charset="utf-8"></script>
<script>
    layui.use(['form', 'upload'], function () {
        var form = layui.form
            , layer = layui.layer
			, upload = layui.upload;

		  upload.render({
			elem: '#upload4'
			,url: '/upfile.php'
			,accept: 'file'
			,exts: 'xls|xlsx'
			,done: function(res){
				if(res[0].status==1){
					layer.alert('上传成功');
					$("#upifle").val(res[0].file);
				}else{
					layer.alert(res[0].msg);
				}
			}
		  });
        form.on('submit(demo1)', function (data) {
			var f = $("#upifle").val();
			var q = $("#qi").val();
			var z = $("#zhi").val();
			if(f == ''){
				layer.alert("请上传文件");
				return false;
			}
			if(q == '' || q < 1){
				layer.alert("起始行非法");
				return false;
			}
			$.post("../action.php?mode=daoru",{zz:<?php echo $zz; ?>,q:q,z:z,f:f},function(result){
				console.log(result);
				$("#upinfo").append(result);
				
			});
            return false;
        });

	});
</script>
</body>
</html>