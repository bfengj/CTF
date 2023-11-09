<?php
	include_once('../config.php');
	if(isset($_GET['rzqi'])){
		$rzqi = $_GET['rzqi'];
	}else{
		$rzqi = '';
	}
	if(isset($_GET['rzzhi'])){
		$rzzhi = $_GET['rzzhi'];
	}else{
		$rzzhi = '';
	}
	if(isset($_GET['mhss'])){
		$mhss = $_GET['mhss'];
	}else{
		$mhss = '';
	}
	$bm = $_SESSION['bm'];
	if($bm == 0){
		$bm = '';
	}else{
		$bm = " and id=$bm";
	}
	$shanchu = $_SESSION['shanchu'];
	$xiugai = $_SESSION['xiugai'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>资产查询</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="../lib/layui-v2.5.5/css/layui.css" media="all">
    <link rel="stylesheet" href="../css/public.css" media="all">
	<link rel="stylesheet" href="/js/lay-module/soulTable.css" media="all">
	<link rel="stylesheet" href="../lib/font-awesome-4.7.0/css/font-awesome.min.css" media="all">
	<script>
		function showimg(t) {
			layer.open({
				type: 1,
				title: false,
				closeBtn: 0,
				area: '680px',
				skin: 'layui-layer-nobg',
				shadeClose: true,
				content: '<img style="display: inline-block; width: 100%; height: 100%;" src="'+t+'">'
			});
		}
	</script>
	<style>
        .layui-table-cell{
            height:auto !important;
        }
	</style>
</head>
<body>
<div class="layuimini-container">
    <div class="layuimini-main">
        <fieldset class="table-search-fieldset">
            <legend>搜索信息</legend>
            <div style="margin: 10px 10px 10px 10px">
                <form class="layui-form layui-form-pane" action="">
                    <div class="layui-form-item">
						<div class="layui-inline">
							<label class="layui-form-label">入账时间</label>
							<div class="layui-input-inline" style="width: 100px;">
								<input type="text" name="rzqi" id="rzqi" value="<?php echo $rzqi; ?>" autocomplete="off" class="layui-input">
							</div>
							<div class="layui-form-mid">-</div>
							<div class="layui-input-inline" style="width: 100px;">
								<input type="text" name="rzzhi" id="rzzhi" value="<?php echo $rzzhi; ?>" autocomplete="off" class="layui-input">
							</div>
						</div>
                        <div class="layui-inline">
                            <label class="layui-form-label">模糊查询</label>
                            <div class="layui-input-inline">
                                <input type="text" id="mhss" name="mhss" value="<?php echo $mhss; ?>" placeholder="编号、品牌等模糊查询" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <button type="submit" class="layui-btn layui-btn-primary" lay-submit  lay-filter="data-search-btn"><i class="layui-icon"></i> 搜 索</button>
                        </div>
                    </div>
                </form>
            </div>
        </fieldset>
		<?php if($shanchu){ ?>
		<script type="text/html" id="toolbarDemo">
		  <div class="layui-btn-container">
			<button class="layui-btn layui-btn-sm" lay-event="getSelected">删除</button>
		  </div>
		</script>
		<?php } ?>
		<script type="text/html" id="simggs">
			{{# if(d.img != ''){ }}
				<img src="{{d.img}}" style="width:60px;height:25px;" onclick="showimg('{{d.img}}');" />
			{{# } }}
		</script>
		<script type="text/html" id="switchTpl">
			<?php if($xiugai){ ?>
			<i lay-event="baocunxiugai" title="保存修改" class="fa fa-floppy-o"></i>
			&nbsp;&nbsp;
			<i lay-event="xiugai" title="编辑资产" class="fa fa-edit"></i>
			<?php } ?>
			&nbsp;&nbsp;
			<i lay-event="rizhi" title="资产履历" class="fa fa-list-alt"></i>
		</script>
        <table class="layui-hide" id="currentTableId" lay-filter="currentTableFilter"></table>
    </div>
</div>
<script src="../lib/layui-v2.5.5/layui.js" charset="utf-8"></script>
<script src="../js/lay-config.js?v=1.0.4" charset="utf-8"></script>
<script>
    layui.use(['form', 'table','soulTable','laydate','tableEdit'], function () {
        var $ = layui.jquery,//jQuery 
            form = layui.form,//表单
			soulTable = layui.soulTable,//表格拓展
			laydate = layui.laydate,//日期
			tableEdit = layui.tableEdit,//表格编辑
            table = layui.table;//表格
			
		laydate.render({
            elem: '#rzqi'
        });
		laydate.render({
            elem: '#rzzhi'
        });
		
		<?php  
			$sql = "select id,name from zclx where zcfl=1";
			$requ = mysqli_query($con,$sql);
			$z = '';
			while($rs = mysqli_fetch_array($requ)){
				$z.='{name:'.$rs['id'].',value:"'.$rs['name'].'"},';
			}
			$z = rtrim($z,',');
			echo "var zclxarr = [$z];";
		?>
		<?php  
			$sql = "select id,name from zhuangtai where 1=1";
			$requ = mysqli_query($con,$sql);
			$z = '';
			while($rs = mysqli_fetch_array($requ)){
				$z.='{name:'.$rs['id'].',value:"'.$rs['name'].'"},';
			}
			$z = rtrim($z,',');
			echo "var zcztarr = [$z];";
		?>
		<?php  
			$sql = "select id,name from danwei where 1=1 $bm";
			$requ = mysqli_query($con,$sql);
			$z = '';
			while($rs = mysqli_fetch_array($requ)){
				$z.='{name:'.$rs['id'].',value:"'.$rs['name'].'"},';
			}
			$z = rtrim($z,',');
			echo "var bmarr = [$z];";
		?>
		var wlbsarr = [{name:0,value:"未指定"},{name:1,value:"内网"},{name:2,value:"外网"}];

        var cols = table.render({
            elem: '#currentTableId',//绑定表
            url: '../action.php?mode=searchzichandemo&dw=1',//接口
			where: {rzqi:'<?php echo $rzqi; ?>',rzzhi:'<?php echo $rzzhi; ?>',mhss:'<?php echo $mhss; ?>'},//参数
            toolbar: '#toolbarDemo',//表格工具
			id: 'zichanbiao',
            defaultToolbar: ['filter', 'exports', 'print', {//右上角按钮
                title: '导出全部',
                layEvent: 'LAYTABLE_TIPS',
                icon: 'layui-icon-download-circle'
            },{
				title: '帮助',
				layEvent: 'Show_Help',
				icon: 'layui-icon-read'
			}]
			,height:'full-200'
			,overflow: {//内容超出表格设置
				type: 'tips'
				,hoverTime: 300 // 悬停时间，单位ms, 悬停 hoverTime 后才会显示，默认为 0
				,color: 'white' // 字体颜色
				,bgColor: 'blue' // 背景色
				,minWidth: 100 // 最小宽度
				,maxWidth: 500 // 最大宽度
			}
			,contextmenu: {
				// 表头右键菜单配置
				header: [
					{
						name: '重载表格',
						icon: 'layui-icon layui-icon-refresh-1',
						click: function() {
							table.reload(this.id)
						}
					}
				],
				// 表格内容右键菜单配置
				body: [
					{
					   name: '复制',
					   icon: 'layui-icon layui-icon-template',
					   click: function(obj) {
						   soulTable.copy(obj.text)
						   layer.msg('复制成功！') 
					   }
					},
					{
						name: '标记行',
						icon: 'layui-icon layui-icon-rate-half',
						click: function(obj) {
							obj.trElem.css('background', '#01AAED')
							obj.trElem.css('color', 'white')
						}
					}
				]
			}
			,rowDrag: {trigger: 'row', done: function(obj) {}},//拖拽行
            cols: [[
                {type: "checkbox", width: 50, fixed: "left"},
                {field: 'id', width: 80, title: 'ID', sort: true, align: "center"},
                {field: 'zclx', width: 120, title: '资产类型', align: "center", filter: true, event:'zclx',config:{type:'select',data:zclxarr}
					,templet:function (d) {
                        if(d.zclx){
                            if(d.zclx.value){
                                return  d.zclx.value;
                            }
                            return  d.zclx;
                        }
                        return ''
                    }
				},
				{field: 'zcbh', width: 150, title: '资产编号', align: "center", event:'zcbh', config:{type:'input'}},
				{field: 'xlh', width: 150, title: '序列号', align: "center", event:'xlh', config:{type:'input'}},
				{field: 'zczt', width: 120, title: '资产状态', sort: true, align: "center", filter: true, event:'zczt',config:{type:'select',data:zcztarr}
					,templet:function (d) {
                        if(d.zczt){
                            if(d.zczt.value){
                                return  d.zczt.value;
                            }
                            return  d.zczt;
                        }
                        return ''
                    }
				},
				{field: 'bm', width: 120, title: '所属单位', align: "center", filter: true, event:'bm',config:{type:'select',data:bmarr}
					,templet:function (d) {
                        if(d.bm){
                            if(d.bm.value){
                                return  d.bm.value;
                            }
                            return  d.bm;
                        }
                        return ''
                    }
				},
				{field: 'bgr', width: 100, title: '责任人', align: "center", event:'bgr', config:{type:'input'}},
				{field: 'dz', width: 120, title: '存放地点', align: "center", event:'dz', config:{type:'input'}},
				{field: 'pp', width: 110, title: '品牌', align: "center", sort: true, filter: true, event:'pp', config:{type:'input'}},
				{field: 'xh', width: 100, title: '型号', align: "center", sort: true, event:'xh', config:{type:'input'}},
				{field: 'gg', width: 90, title: '规格', align: "center", sort: true, event:'gg', config:{type:'input'}},
				{field: 'cgsj', width: 140, title: '采购时间', align: "center", sort: true, event:'cgsj', config:{type:'date',dateType:'date'}},
				{field: 'rzsj', width: 140, title: '入账时间', align: "center", sort: true, event:'rzsj', config:{type:'date',dateType:'date'}},
				{field: 'zcly', width: 100, title: '资产来源', align: "center", sort: true, event:'zcly', config:{type:'input'}},
				{field: 'zcjz', width: 100, title: '资产价值', align: "center", event:'zcjz', config:{type:'input'}},
				{field: 'zbsc', width: 100, title: '质保时长', align: "center", sort: true, event:'zbsc', config:{type:'input'}},
				{field: 'sysc', width: 100, title: '报废年限', align: "center", sort: true, event:'sysc', config:{type:'input'}},
				{field: 'img', width: 100, title: '资产图片', align: "center", templet: "#simggs"},
				{field: 'wlbs', width: 110, title: '网络标识', align: "center", sort: true, event:'wlbs',config:{type:'select',data:wlbsarr}
					,templet:function (d) {
                        if(d.wlbs){
                            if(d.wlbs.value){
                                return  d.wlbs.value;
                            }
                            return  d.wlbs;
                        }
                        return ''
                    }
				},
				{field: 'ip', width: 120, title: 'IP', align: "center", event:'ip', config:{type:'input'}},
				{field: 'xsq', width: 100, title: '显示器', align: "center", event:'xsq', config:{type:'input'}},
				{field: 'yp', width: 80, title: '硬盘', align: "center", sort: true, event:'yp', config:{type:'input'}},
				{field: 'nc', width: 80, title: '内存', align: "center", sort: true, event:'nc', config:{type:'input'}},
				{field: 'bz', width: 200, title: '备注', align: "center", event:'bz', config:{type:'input'}},
                {fixed: 'right', width: 130, title: '操作', templet: '#switchTpl', align: "center"}
            ]],
            limits: [10, 15, 20, 30, 50, 100],
            limit: 10,
            page: true
			  ,autoColumnWidth: {//宽自动
			  	//init: true
			  }
			  ,filter: {//筛选
				items:['data','clearCache']
				,cache: true
				,bottom: false 
			  }
			  ,done: function () {
				soulTable.render(this)
			  }
        }).config.cols;
		
		var aopTable = tableEdit.aopObj(cols);

		table.on('toolbar(currentTableFilter)', function(obj){
			var checkStatus = table.checkStatus(obj.config.id);
			switch(obj.event){
				case 'LAYTABLE_TIPS':
					location.href="../action.php?mode=downloadzclist&zz=xinxizichan";
				break;
				case 'getSelected':
					var data = checkStatus.data;
					if(data.length > 0){
						var s="";
						for(var i=0;i<data.length;i++){
							s = s + data[i].id + ",";
						}
						s = s.substr(s,s.length - 1);
						layer.confirm('确定要删除吗？', {
							btn: ['确定','取消'], //按钮
							title: '删除询问'
						}, function(){
							layer.confirm('删除后将无法恢复！', {
								btn: ['知道了','吓死我了'], //按钮
								title: '删除询问'
							}, function(){
								layer.confirm('再考虑考虑？', {
									btn: ['好','不'], //按钮
									title: '询问'
								}, function(){
									//考虑考虑，不删了 
									layer.closeAll();
								}, function(){
									$.post("../action.php",{mode:'deletezichan',sjk:'xinxizichan',id:s},function(result){
										console.log(result);
										var r=JSON.parse(result);
										if(r.status==1){
											layer.alert('删除成功', {icon: 1});
											table.reload('zichanbiao', {
												url: '../action.php?mode=searchzichandemo&dw=1'
											});
										}else{
											layer.alert('删除失败', {icon: 2});
										}
									});
								});
							}, function(){
								//取消删除
							});
						}, function(){
							//取消删除
						});
					}
				break;
				case 'Show_Help':
					layer.open({
						type: 1,
						title: false,
						closeBtn: 0,
						area: '680px',
						skin: 'layui-layer-nobg',
						shadeClose: true,
						content: '<h1 style="margin:auto;text-align:center;color:#fff;">没有</h1>'
					});
				break;
			};
		});
		
        aopTable.on('tool(currentTableFilter)', function (obj) {
            var id = obj.data.id;
			console.log(id);
            if (obj.event === 'xiugai') {
                layer.open({
                    title: '编辑资产',
                    type: 2,
                    shade: 0.2,
                    maxmin:true,
                    shadeClose: true,
                    area: ['100%', '100%'],
                    content: '/page/editxxzichan.php?id='+id,
                });
                return false;
            } else if (obj.event === 'rizhi') {
                layer.open({
                    title: '资产履历',
                    type: 2,
                    shade: 0.2,
                    maxmin:true,
                    shadeClose: true,
                    area: ['100%', '100%'],
                    content: '/page/zichanlvli.php?zz=xinxizichan&id='+id,
                });
                return false;
            } else if (obj.event === 'baocunxiugai') {
                var data = obj.data;
				//layer.alert(JSON.stringify(data));
				var zclx = data.zclx.name;
				var zczt = data.zczt.name;
				var bm = data.bm.name;
				var wlbs = data.wlbs.name;
				data.zclx=zclx;
				data.zczt=zczt;
				data.bm=bm;
				data.wlbs=wlbs;
				var d = JSON.stringify(data);
				console.log(d);
				
				d=d.replace(/\'/g,"’");
				$.post("../action.php?mode=xiugaixxzxzc",{id:data.id,zz:1,data:d},function(result){
					console.log(result);
					var r = JSON.parse(result);
					if(r.status==1){
						var index = layer.alert('修改成功',function () {
							layer.close(index);
						});
					}else{
						layer.alert(r.msg);
					}
				});
                return false;
            }else{
				<?php if($xiugai){ ?>
				var field = obj.field; //单元格字段
				var value = obj.value; //修改后的值
				var data = obj.data; //当前行旧数据
				var event = obj.event; //当前单元格事件属性值
				console.log("单元格字段",field,"修改后的值",value,"当前行旧数据",data,"事件",event);
				if(field == 'zcjz' || field == 'zbsc' || field == 'sysc' || field == 'yp' || field == 'nc'){
					if(isNaN(value)){
						layer.msg('数值非法');
					}else{
						var update = {};
						update[field] = value;
						//把value更新到行中
						obj.update(update);
					}
				}else{
					var update = {};
					update[field] = value;
					//把value更新到行中
					obj.update(update);
				}
				<?php  } ?>
			}
        });
    });
</script>
</body>
</html>