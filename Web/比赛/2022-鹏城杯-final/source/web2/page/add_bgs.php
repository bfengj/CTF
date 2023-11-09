<?php
	include_once("../config.php");
	$sql = "select value from config where title='bgsauto'";
	$requ = mysqli_query($con,$sql);
	$rs = mysqli_fetch_array($requ);
	$xxat = $rs['value'];
	$bm = $_SESSION['bm'];
	if($bm == 0){
		$bm = '';
	}else{
		$bm = " and id=$bm";
	}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>添加办公室资产</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="../lib/layui-v2.5.5/css/layui.css" media="all">
    <link rel="stylesheet" href="../css/public.css" media="all">
</head>
<body>
<div class="layuimini-container">
    <div class="layuimini-main">
	<form class="layui-form" action="" lay-filter="example">
		<div class="layui-form-item">
			<div class="layui-inline">
				<label class="layui-form-label">资产类型*</label>
				<div class="layui-input-inline">
					<select name="zclx" lay-verify="required" lay-search="" lay-filter="changeleibie">
						<?php
							$sql = "select id,name from zclx where status=1 and zcfl=2";
							$requ = mysqli_query($con,$sql);
							while($rs = mysqli_fetch_array($requ)){
								echo '<option value="'.$rs['id'].'">'.$rs['name'].'</option>';
							}
						?>
					</select>
				</div>
			</div>
			<div class="layui-inline">
				<label class="layui-form-label">资产状态*</label>
				<div class="layui-input-inline">
					<select name="zczt" lay-verify="required" lay-search="">
						<?php
							$sql = "select id,name from zhuangtai where status=1";
							$requ = mysqli_query($con,$sql);
							while($rs = mysqli_fetch_array($requ)){
								echo '<option value="'.$rs['id'].'">'.$rs['name'].'</option>';
							}
						?>
					</select>
				</div>
			</div>
		</div>
		<div class="layui-form-item">
			<div class="layui-inline">
				<label class="layui-form-label">资产编号</label>
				<div class="layui-input-inline">
					<input type="text" name="zcbh" autocomplete="off" class="layui-input">
				</div>
			</div>
			<div class="layui-inline">
				<label class="layui-form-label">序列号*</label>
				<div class="layui-input-inline">
					<input type="text" name="xlh" lay-verify="required" autocomplete="off" class="layui-input">
				</div>
			</div>
		</div>
		<div class="layui-form-item">
			<div class="layui-inline">
				<label class="layui-form-label">保管人*</label>
				<div class="layui-input-inline">
					<input type="text" name="bgr" lay-verify="required" autocomplete="off" class="layui-input">
				</div>
			</div>
			<div class="layui-inline">
				<label class="layui-form-label">单位*</label>
				<div class="layui-input-inline">
					<select name="bm" lay-verify="required" lay-search="">
						<?php
							$sql = "select id,name from danwei where status=1 $bm";
							$requ = mysqli_query($con,$sql);
							while($rs = mysqli_fetch_array($requ)){
								echo '<option value="'.$rs['id'].'">'.$rs['name'].'</option>';
							}
						?>
					</select>
				</div>
			</div>
			<div class="layui-inline">
				<label class="layui-form-label">地址*</label>
				<div class="layui-input-inline">
					<input type="text" name="dz" lay-verify="required" autocomplete="off" class="layui-input">
				</div>
			</div>
		</div>
		<div class="layui-form-item">
		   <div class="layui-inline">
				<label class="layui-form-label">采购日期</label>
				<div class="layui-input-inline">
					<input type="text" name="cgsj" id="cgsj" value="<?php echo date("Y-m-d",time()); ?>" lay-verify="date" autocomplete="off" class="layui-input">
				</div>
			</div>
		   <div class="layui-inline">
				<label class="layui-form-label">入账日期</label>
				<div class="layui-input-inline">
					<input type="text" name="rzsj" id="rzsj" value="<?php echo date("Y-m-d",time()); ?>" lay-verify="date" autocomplete="off" class="layui-input">
				</div>
			</div>
		</div>
		<div class="layui-form-item">
			<div class="layui-inline">
				<label class="layui-form-label">质保时长</label>
				<div class="layui-input-inline">
					<input type="number" name="zbsc" value="0" lay-verify="number" autocomplete="off" class="layui-input">
				</div>
			</div>
			<div class="layui-inline">
				<label class="layui-form-label">报废年限</label>
				<div class="layui-input-inline">
					<input type="number" name="sysc" value="0" lay-verify="number" autocomplete="off" class="layui-input">
				</div>
			</div>
		</div>
		<div class="layui-form-item">
			<div class="layui-inline">
				<label class="layui-form-label">品牌*</label>
				<div class="layui-input-inline">
					<input type="text" name="pp" lay-verify="required" autocomplete="off" class="layui-input">
				</div>
			</div>
			<div class="layui-inline">
				<label class="layui-form-label">型号</label>
				<div class="layui-input-inline">
					<input type="text" name="xh" autocomplete="off" class="layui-input">
				</div>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">规格</label>
			<div class="layui-input-block">
				<input type="text" name="gg" autocomplete="off" class="layui-input">
			</div>
		</div>
		<div class="layui-form-item">
			<div class="layui-inline">
				<label class="layui-form-label">资产来源</label>
				<div class="layui-input-inline">
					<input type="text" name="zcly" value="自购" autocomplete="off" class="layui-input">
				</div>
			</div>
			<div class="layui-inline">
				<label class="layui-form-label">资产价值</label>
				<div class="layui-input-inline">
					<input type="text" name="zcjz" value="0.00" lay-verify="huobi" autocomplete="off" class="layui-input">
				</div>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">备注</label>
			<div class="layui-input-block">
				<input type="text" name="bz" autocomplete="off" class="layui-input">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">资产图片</label>
			<button type="button" class="layui-btn layui-btn-normal" id="upzcimg">上传图片</button>
		</div>
		<div class="layui-form-item" style="display:none;" id="imgdiv">
			<label class="layui-form-label">图片预览</label>
			<img style="width:100px;height:60px;" id="zcimg">（双击图片清除）
			<input type="hidden" name="img" id="img" autocomplete="off" class="layui-input">
		</div>
		
		
		<div class="layui-form-item">
			<div class="layui-input-block">
				<button class="layui-btn" lay-submit="" lay-filter="demo1">立即提交</button>
				<button type="reset" class="layui-btn layui-btn-primary">重置</button>
				<button type="button" class="layui-btn layui-btn-danger" id="drfromexcel">从Excel导入</button>
			</div>
		</div>
	</form>
    </div>
</div>

<script src="../lib/layui-v2.5.5/layui.js" charset="utf-8"></script>
<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
<script>
    layui.use(['form', 'laydate', 'upload'], function () {
        var form = layui.form
            , layer = layui.layer
            , laydate = layui.laydate
			, upload = layui.upload
			, $ = layui.$;

        laydate.render({
            elem: '#cgsj'
        });
        laydate.render({
            elem: '#rzsj'
        });

        form.verify({
            huobi: [
                /^(([1-9]\d*)|0)(\.\d{1,2})?$/
                , '请正确输入资产价值'
            ]
        });
		
		$("#zcimg").dblclick(function(){
			$("#zcimg").attr("src","");
			$("#img").val('');
			$("#imgdiv").hide();
		});
		$("#drfromexcel").click(function(){
			var index = layer.open({
				title: '导入',
				type: 2,
				shade: 0.2,
				maxmin:true,
				shadeClose: true,
				area: ['100%', '100%'],
				content: '/page/daoru.php?zz=2',
			});
		});
		
		  var uploadInst = upload.render({
			elem: '#upzcimg'
			,url: '../upload.php'
			,done: function(res){
				//var r = JSON.stringify(res[0]);
				//console.log(r);
				if(res[0].status==1){
					//location.reload();
					$("#zcimg").attr("src","/uploads/" + res[0].file);
					$("#img").val("/uploads/" + res[0].file);
					$("#imgdiv").show();
				}else{
					layer.alert(res[0].msg);
				}
			}
			,error: function(){

			}
		  });
		

        form.on('submit(demo1)', function (data) {
			var d = JSON.stringify(data.field);
			d=d.replace(/\'/g,"’");
			console.log(d);
			$.post("../action.php?mode=addwgbgszc",{zz:2,data:d},function(result){
				console.log(result);
				var r = JSON.parse(result);
				if(r.status==1){
					var index = layer.alert('添加成功',function () {
						layer.close(index);
						$("#zcimg").attr("src","");
						$("#img").val('');
						$("#imgdiv").hide();
						<?php 
						if($xxat==0){
							echo '$("#addxzxform")[0].reset();';
							echo 'form.render();';
						}
						?>
					});
				}else{
					layer.alert(r.msg);
				}
			});
            return false;
        });
		
	  form.on('select(changeleibie)', function(data){
		console.log(data);
	  });
<?php
	if(isset($_SESSION['addlishi']) && $xxat==1){
		$d = $_SESSION['addlishi'];
		$d = json_decode($d);
		$zclx = $d->zclx;
		if(empty($zclx)){$zclx='0';}
		echo "form.val('example', {
				'zclx': ".$zclx."
				,'zczt': ".$d->zczt."
				,'bm': ".$d->bm."
				,'dz': '".$d->dz."'
				,'zcbh': '".$d->zcbh."'
				,'xlh': '".$d->xlh."'
				,'cgsj': '".$d->cgsj."'
				,'rzsj': '".$d->rzsj."'
				,'pp': '".$d->pp."'
				,'xh': '".$d->xh."'
				,'gg': '".$d->gg."'
				,'zcly': '".$d->zcly."'
				,'zcjz': '".$d->zcjz."'
				,'zbsc':".$d->zbsc."
				,'sysc':".$d->sysc."
			})";
	}
?>

		
    });
</script>

</body>
</html>