<?php
//检查登录状态
	session_start();
	if(!isset($_SESSION['user'])){die('请登录！！！');}
	include_once('../config.php');
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
</head>
<body>
<div class="layuimini-container">
    <div class="layuimini-main">
		<script type="text/html" id="simggs">
			<div>
				<img src="{{d.img}}" width="80px" height="auto" onclick="showimg('{{d.img}}');" />
			</div>
		</script>
		<script type="text/html" id="switchTpl">
			<?php if($xiugai){ ?>
			<i lay-event="xiugai" title="编辑资产" class="fa fa-edit"></i>
			&nbsp;&nbsp;
			<?php } ?>
			<i lay-event="rizhi" title="资产履历" class="fa fa-list-alt"></i>
		</script>
        <table class="layui-hide" id="currentTableId" lay-filter="currentTableFilter"></table>
    </div>
</div>
<script src="../lib/layui-v2.5.5/layui.js" charset="utf-8"></script>
<script src="../js/lay-config.js?v=1.0.4" charset="utf-8"></script>
<script>


    layui.use(['form', 'table','soulTable'], function () {
        var $ = layui.jquery,
            form = layui.form,
			soulTable = layui.soulTable,
            table = layui.table;

        table.render({
            elem: '#currentTableId',
            url: '../action.php?mode=searchzichan&dw=2',
            toolbar: '#toolbarDemo',
            defaultToolbar: ['filter', 'exports', 'print', {
                title: '导出全部',
                layEvent: 'LAYTABLE_TIPS',
                icon: 'layui-icon-download-circle'
            }]
			,height:'full-200'
        ,overflow: {
            type: 'tips'
            ,hoverTime: 300 // 悬停时间，单位ms, 悬停 hoverTime 后才会显示，默认为 0
            ,color: 'white' // 字体颜色
            ,bgColor: 'blue' // 背景色
            ,minWidth: 100 // 最小宽度
            ,maxWidth: 500 // 最大宽度
        }
	  ,rowDrag: {trigger: 'row', done: function(obj) {
			//拖拽行
			// 完成时（松开时）触发
			// 如果拖动前和拖动后无变化，则不会触发此方法
			console.log(obj.row) // 当前行数据
			console.log(obj.cache) // 改动后全表数据
			console.log(obj.oldIndex) // 原来的数据索引
			console.log(obj.newIndex) // 改动后数据索引
		}},	
            cols: [[
                //{type: "checkbox", width: 50, fixed: "left"},
                {field: 'id', width: 80, title: 'ID', sort: true, align: "center"},
                {field: 'zclx', width: 120, title: '资产类型', align: "center", filter: true},
				{field: 'zcbh', width: 150, title: '资产编号', align: "center"},
				{field: 'xlh', width: 150, title: '序列号', align: "center"},
				{field: 'zczt', width: 120, title: '资产状态', align: "center", filter: true},
				{field: 'bm', width: 120, title: '所属单位', align: "center", filter: true},
				{field: 'bgr', width: 100, title: '责任人', align: "center"},
				{field: 'dz', width: 120, title: '存放地点', align: "center"},
				{field: 'pp', width: 100, title: '品牌', align: "center", filter: true},
				{field: 'xh', width: 100, title: '型号', align: "center"},
				{field: 'gg', width: 90, title: '规格', align: "center"},
				{field: 'cgsj', width: 140, title: '采购时间', align: "center"},
				{field: 'rzsj', width: 140, title: '入账时间', align: "center"},
				{field: 'zcly', width: 90, title: '资产来源', align: "center"},
				{field: 'zcjz', width: 100, title: '资产价值', align: "center"},
				{field: 'zbsc', width: 90, title: '质保时长', align: "center"},
				{field: 'sysc', width: 90, title: '报废年限', align: "center"},
				{field: 'img', width: 120, title: '资产图片', align: "center", templet: "#simggs"},
				{field: 'bz', width: 200, title: '备注', align: "center"},
                {fixed: 'right', width: 100, title: '操作', sort: true, templet: '#switchTpl', align: "center"}
            ]],
            limits: [10, 15, 20, 25, 50, 100],
            limit: 10,
            page: true
			
			
			  ,autoColumnWidth: {
			  	init: true
			  }
			  ,filter: {
				items:['column','data','clearCache']
				,cache: true
				,bottom: false 
			  }
			  ,done: function () {
				soulTable.render(this)
			  }
			  
			  
        });
		
        table.on('tool(currentTableFilter)', function (obj) {
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
                    content: '/page/editzichan.php?zz=bgszichan&id='+id,
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
                    content: '/page/zichanlvli.php?zz=bgszichan&id='+id,
                });
                return false;
            }
        });

		  table.on('toolbar(currentTableFilter)', function(obj){
			var checkStatus = table.checkStatus(obj.config.id);
			switch(obj.event){
			  case 'LAYTABLE_TIPS':
				location.href="../action.php?mode=downloadzclist&zz=bgszichan";
			  break;
			};
		  });
    });
	



</script>
</body>
</html>