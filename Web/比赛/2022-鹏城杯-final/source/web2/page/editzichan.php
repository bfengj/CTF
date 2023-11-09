<?php
	include_once("../config.php");
	$id=$_GET['id'];
	$sjk = $_GET['zz'];
	if($sjk=='wgbzichan'){$zz=3;}else{$zz=2;}
	$sql = "select * from $sjk where id=$id";
	$requ = mysqli_query($con,$sql);
	$rs = mysqli_fetch_array($requ);
	$zcbh = $rs['zcbh'];
	$xlh = $rs['xlh'];
	$zczt = $rs['zczt'];
	$bm = $rs['bm'];
	$bgr = $rs['bgr'];
	$dz = $rs['dz'];
	$cgsj = $rs['cgsj'];
	$cgsj = date("Y-m-d",$cgsj);
	$rzsj = $rs['rzsj'];
	$rzsj = date("Y-m-d",$rzsj);
	$zbsc = $rs['zbsc'];
	$sysc = $rs['sysc'];
	$pp = $rs['pp'];
	$xh = $rs['xh'];
	$zcly = $rs['zcly'];
	$zcjz = $rs['zcjz'];
	$gg = $rs['gg'];
	$bz = $rs['bz'];
	$img = $rs['img'];
	$zclx = $rs['zclx'];
	$bmz = $_SESSION['bm'];
	if($bm != $bmz && $bmz!=0){
		die('无权限');
	}
	if($bmz == 0){
		$bmz = '';
	}else{
		$bmz = " and id=$bmz";
	}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>编辑资产</title>
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
							$sql = "select id,name from zclx where status=1 and zcfl=$zz";
							$requ = mysqli_query($con,$sql);
							while($rs = mysqli_fetch_array($requ)){
								if($rs['id']==$zclx){$xy='selected=""';}else{$xy='';}
								echo '<option '.$xy.' value="'.$rs['id'].'">'.$rs['name'].'</option>';
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
								if($rs['id']==$zczt){$xy='selected=""';}else{$xy='';}
								echo '<option '.$xy.' value="'.$rs['id'].'">'.$rs['name'].'</option>';
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
					<input type="text" name="zcbh" value="<?php echo $zcbh; ?>" autocomplete="off" class="layui-input">
				</div>
			</div>
			<div class="layui-inline">
				<label class="layui-form-label">序列号*</label>
				<div class="layui-input-inline">
					<input type="text" name="xlh" value="<?php echo $xlh; ?>" lay-verify="required" autocomplete="off" class="layui-input">
				</div>
			</div>
		</div>
		<div class="layui-form-item">
			<div class="layui-inline">
				<label class="layui-form-label">保管人*</label>
				<div class="layui-input-inline">
					<input type="text" name="bgr" value="<?php echo $bgr; ?>" lay-verify="required" autocomplete="off" class="layui-input">
				</div>
			</div>
			<div class="layui-inline">
				<label class="layui-form-label">单位*</label>
				<div class="layui-input-inline">
					<select name="bm" lay-verify="required" lay-search="">
						<?php
							$sql = "select id,name from danwei where status=1 $bmz";
							$requ = mysqli_query($con,$sql);
							while($rs = mysqli_fetch_array($requ)){
								if($rs['id']==$bm){$xy='selected=""';}else{$xy='';}
								echo '<option '.$xy.' value="'.$rs['id'].'">'.$rs['name'].'</option>';
							}
						?>
					</select>
				</div>
			</div>
			<div class="layui-inline">
				<label class="layui-form-label">地址*</label>
				<div class="layui-input-inline">
					<input type="text" name="dz" value="<?php echo $dz; ?>" lay-verify="required" autocomplete="off" class="layui-input">
				</div>
			</div>
		</div>
		<div class="layui-form-item">
		   <div class="layui-inline">
				<label class="layui-form-label">采购日期</label>
				<div class="layui-input-inline">
					<input type="text" name="cgsj" id="cgsj" value="<?php echo $cgsj; ?>" lay-verify="date" autocomplete="off" class="layui-input">
				</div>
			</div>
		   <div class="layui-inline">
				<label class="layui-form-label">入账日期</label>
				<div class="layui-input-inline">
					<input type="text" name="rzsj" id="rzsj" value="<?php echo $rzsj; ?>" lay-verify="date" autocomplete="off" class="layui-input">
				</div>
			</div>
		</div>
		<div class="layui-form-item">
			<div class="layui-inline">
				<label class="layui-form-label">质保时长</label>
				<div class="layui-input-inline">
					<input type="number" name="zbsc" value="<?php echo $zbsc; ?>" lay-verify="number" autocomplete="off" class="layui-input">
				</div>
			</div>
			<div class="layui-inline">
				<label class="layui-form-label">报废年限</label>
				<div class="layui-input-inline">
					<input type="number" name="sysc" value="<?php echo $sysc; ?>" lay-verify="number" autocomplete="off" class="layui-input">
				</div>
			</div>
		</div>
		<div class="layui-form-item">
			<div class="layui-inline">
				<label class="layui-form-label">品牌*</label>
				<div class="layui-input-inline">
					<input type="text" name="pp" value="<?php echo $pp; ?>" lay-verify="required" autocomplete="off" class="layui-input">
				</div>
			</div>
			<div class="layui-inline">
				<label class="layui-form-label">型号</label>
				<div class="layui-input-inline">
					<input type="text" name="xh" value="<?php echo $xh; ?>" autocomplete="off" class="layui-input">
				</div>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">规格</label>
			<div class="layui-input-block">
				<input type="text" name="gg" value="<?php echo $gg; ?>" autocomplete="off" class="layui-input">
			</div>
		</div>
		<div class="layui-form-item">
			<div class="layui-inline">
				<label class="layui-form-label">资产来源</label>
				<div class="layui-input-inline">
					<input type="text" name="zcly" value="<?php echo $zcly; ?>" autocomplete="off" class="layui-input">
				</div>
			</div>
			<div class="layui-inline">
				<label class="layui-form-label">资产价值</label>
				<div class="layui-input-inline">
					<input type="text" name="zcjz" value="<?php echo $zcjz; ?>" lay-verify="huobi" autocomplete="off" class="layui-input">
				</div>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">备注</label>
			<div class="layui-input-block">
				<input type="text" name="bz" value="<?php echo $bz; ?>" autocomplete="off" class="layui-input">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">资产图片</label>
			<button type="button" class="layui-btn layui-btn-normal" id="upzcimg">替换图片</button>
		</div>
		<div class="layui-form-item" id="imgdiv" <?php if(empty($img)){echo 'style="display:none;"';} ?>>
			<label class="layui-form-label">图片预览</label>
			<img style="width:100px;height:60px;" id="zcimg" src="<?php echo $img; ?>">（双击图片清除）
			<input type="hidden" name="img" id="img" value="<?php echo $img; ?>" autocomplete="off" class="layui-input">
		</div>


		<div class="layui-form-item">
			<div class="layui-input-block">
				<button class="layui-btn" lay-submit="" lay-filter="demo1">修改</button>
				<button type="reset" class="layui-btn layui-btn-primary">重置</button>
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
			$.post("../action.php?mode=xiugaizc",{id:<?php echo $id; ?>,sjk:'<?php echo $sjk; ?>',data:d},function(result){
				console.log(result);
				var r = JSON.parse(result);
				if(r.status==1){
					var index = layer.alert('修改成功',function () {
						layer.close(index);
						var iframeIndex = parent.layer.getFrameIndex(window.name);
						parent.layer.close(iframeIndex);
						//parent.location.reload();  
						parent.layui.table.reload('currentTableId', {
							url: '../action.php?mode=searchzichan&dw=<?php echo $zz; ?>'
						});
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


    });
</script>

</body>
</html>