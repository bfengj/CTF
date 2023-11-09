<head>
    <link rel="stylesheet" href="../lib/layui-v2.5.5/css/layui.css" media="all">
	<script src="../lib/layui-v2.5.5/layui.js" charset="utf-8"></script>
	<script src="../js/lay-config.js?v=1.0.4" charset="utf-8"></script>

	<style>
		td{text-align:center;}
	
	</style>
	<script>



    layui.use('layer', function () {
        //var $ = layui.jquery;
	});

		function showimg(t) {
			layer.open({
				type: 1,
				title: false,
				closeBtn: 0,
				area: ['680px', '460px'],
				skin: 'layui-layer-nobg',
				shadeClose: true,
				content: '<img style="display: inline-block; width: 100%; height: 100%;" src="'+t+'">'
			});
		}
	</script>

</head>
<?php
//检查登录状态
	session_start();
	if(!isset($_SESSION['user'])){die('请登录！！！');}
include_once("../config.php");
$id = $_GET['id'];
$sjk = $_GET['zz'];
$sql = "select ll from $sjk where id=$id";
$requ = mysqli_query($con,$sql);
if(!mysqli_num_rows($requ)){
	die('无数据');
}
$rs = mysqli_fetch_array($requ);
$ll = $rs['ll'];
$ll = json_decode($ll);

?>
<table border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#8e8e8e">
<tr>
	<td>操作时间</td>
	<?php
		foreach($ll as $v){
			echo '<td>'.$v->time.'</td>';
		}
	?>
</tr>
<tr>
	<td>操作者</td>
	<?php
		foreach($ll as $v){
			echo '<td>'.$v->user.'</td>';
		}
	?>
</tr>
<tr>
	<td>动作</td>
	<?php
		foreach($ll as $v){
			echo '<td>'.$v->act.'</td>';
		}
	?>
</tr>
<tr>
	<td>资产类型</td>
	<?php
		foreach($ll as $v){
			$k = $v->new;
			$id=$k->zclx;
			$sql = "select name from zclx where id=$id";
			$requ = mysqli_query($con,$sql);
			$rs = mysqli_fetch_array($requ);
			echo '<td>'.$rs['name'].'</td>';
		}
	?>
</tr>
<tr>
	<td>资产状态</td>
	<?php
		foreach($ll as $v){
			$k = $v->new;
			$id=$k->zczt;
			$sql = "select name from zhuangtai where id=$id";
			$requ = mysqli_query($con,$sql);
			$rs = mysqli_fetch_array($requ);
			echo '<td>'.$rs['name'].'</td>';
		}
	?>
</tr>
<tr>
	<td>资产编号</td>
	<?php
		foreach($ll as $v){
			$k = $v->new;
			echo '<td>'.$k->zcbh.'</td>';
		}
	?>
</tr>
<tr>
	<td>序列号</td>
	<?php
		foreach($ll as $v){
			$k = $v->new;
			echo '<td>'.$k->xlh.'</td>';
		}
	?>
</tr>
<tr>
	<td>保管人</td>
	<?php
		foreach($ll as $v){
			$k = $v->new;
			echo '<td>'.$k->bgr.'</td>';
		}
	?>
</tr>
<tr>
	<td>部门</td>
	<?php
		foreach($ll as $v){
			$k = $v->new;
			$id=$k->bm;
			$sql = "select name from danwei where id=$id";
			$requ = mysqli_query($con,$sql);
			$rs = mysqli_fetch_array($requ);
			echo '<td>'.$rs['name'].'</td>';
		}
	?>
</tr>
<tr>
	<td>地址</td>
	<?php
		foreach($ll as $v){
			$k = $v->new;
			echo '<td>'.$k->dz.'</td>';
		}
	?>
</tr>
<tr>
	<td>采购时间</td>
	<?php
		foreach($ll as $v){
			$k = $v->new;
			echo '<td>'.$k->cgsj.'</td>';
		}
	?>
</tr>
<tr>
	<td>入账时间</td>
	<?php
		foreach($ll as $v){
			$k = $v->new;
			echo '<td>'.$k->rzsj.'</td>';
		}
	?>
</tr>
<tr>
	<td>质保时长</td>
	<?php
		foreach($ll as $v){
			$k = $v->new;
			echo '<td>'.$k->zbsc.'</td>';
		}
	?>
</tr>
<tr>
	<td>报废年限</td>
	<?php
		foreach($ll as $v){
			$k = $v->new;
			echo '<td>'.$k->sysc.'</td>';
		}
	?>
</tr>
<tr>
	<td>品牌</td>
	<?php
		foreach($ll as $v){
			$k = $v->new;
			echo '<td>'.$k->pp.'</td>';
		}
	?>
</tr>
<tr>
	<td>型号</td>
	<?php
		foreach($ll as $v){
			$k = $v->new;
			echo '<td>'.$k->xh.'</td>';
		}
	?>
</tr>
<tr>
	<td>规格</td>
	<?php
		foreach($ll as $v){
			$k = $v->new;
			echo '<td>'.$k->gg.'</td>';
		}
	?>
</tr>
<tr>
	<td>资产来源</td>
	<?php
		foreach($ll as $v){
			$k = $v->new;
			echo '<td>'.$k->zcly.'</td>';
		}
	?>
</tr>
<tr>
	<td>资产价值</td>
	<?php
		foreach($ll as $v){
			$k = $v->new;
			echo '<td>'.$k->zcjz.'</td>';
		}
	?>
</tr>
<tr>
	<td>资产图片</td>
	<?php
		foreach($ll as $v){
			$k = $v->new;
			$img = $k->img;
			if(!empty($img)){
				echo '<td><img onclick="<?php include('."'".$img."'".") ?>" width="80px" height="auto" src="'.$img.'" /></td>';
				//echo '<td><img onclick="showimg('."'".$img."'".');" width="80px" height="auto" src="'.$img.'" /></td>';
			}else{
				echo '<td></td>';
			}
		}
	?>
</tr>
<?php 
if($sjk == 'xinxizichan'){
?>
<tr>
	<td>网络标识</td>
	<?php
		foreach($ll as $v){
			$k = $v->new;
			$id = $k->wlbs;
			$bs=array("未指定","内网","外网");
			echo '<td>'.$bs[$id].'</td>';
		}
	?>
</tr>
<tr>
	<td>IP</td>
	<?php
		foreach($ll as $v){
			$k = $v->new;
			echo '<td>'.$k->ip.'</td>';
		}
	?>
</tr>
<tr>
	<td>显示器</td>
	<?php
		foreach($ll as $v){
			$k = $v->new;
			echo '<td>'.$k->xsq.'</td>';
		}
	?>
</tr>
<tr>
	<td>硬盘</td>
	<?php
		foreach($ll as $v){
			$k = $v->new;
			echo '<td>'.$k->yp.'</td>';
		}
	?>
</tr>
<tr>
	<td>内存</td>
	<?php
		foreach($ll as $v){
			$k = $v->new;
			echo '<td>'.$k->nc.'</td>';
		}
	?>
</tr>
<?php
}
?>
<tr>
	<td>备注</td>
	<?php
		foreach($ll as $v){
			$k = $v->new;
			echo '<td>'.$k->bz.'</td>';
		}
	?>
</tr>

</table>