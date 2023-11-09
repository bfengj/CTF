<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>资产类型</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="../lib/layui-v2.5.5/css/layui.css" media="all">
    <link rel="stylesheet" href="../css/public.css" media="all">
	<link rel="stylesheet" href="/js/lay-module/soulTable.css" media="all">
</head>
<body>
<div class="layuimini-container">
    <div class="layuimini-main">
        <script type="text/html" id="toolbarDemo">
            <div class="layui-btn-container">
                <button class="layui-btn layui-btn-sm data-add-btn" lay-event="add"> 添加 </button>
            </div>
        </script>
		<script type="text/html" id="switchTpl">
		  <input type="checkbox" name="zt" value="{{d.id}}" lay-skin="switch" lay-text="启用|禁用" lay-filter="Changezt" {{ d.status == 1 ? 'checked' : '' }}>
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
            url: '../action.php?mode=getzichanleixing',
            toolbar: '#toolbarDemo',
            defaultToolbar: ['filter', 'exports', 'print', {
                title: '提示',
                layEvent: 'LAYTABLE_TIPS',
                icon: 'layui-icon-tips'
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
                {field: 'name', width: 200, title: '名称', align: "center"},
				{field: 'zcfz', width: 200, title: '分组', align: "center"},
                {field: 'status', width: 100, title: '状态', sort: true, templet: '#switchTpl', align: "center"}
            ]],
            limits: [10, 15, 20, 25, 50, 100],
            limit: 10,
            page: true
			
			
			  ,autoColumnWidth: {
			  	//列宽自动化，cols 中设置的 width 将失效
				//init: true
			  }
			  ,filter: {
				items:['column','data','clearCache'] // 加入了清除缓存按钮
				,cache: true //增加缓存功能，（会导致单元格编辑失效）
				,bottom: false //隐藏底部
			  }
			  ,done: function () {
				soulTable.render(this)
			  }
			  
			  
        });
		


        /**
         * toolbar监听事件
         */
        table.on('toolbar(currentTableFilter)', function (obj) {
            if (obj.event === 'add') {  // 监听添加操作
                var index = layer.open({
                    title: '添加类型',
                    type: 2,
                    shade: 0.2,
                    maxmin:true,
                    shadeClose: true,
                    area: ['50%', '50%'],
                    content: '/page/addlx.html',
                });
                $(window).on("resize", function () {
                    layer.full(index);
                });
            }
        });
		form.on('switch(Changezt)', function(obj){
			$.post("../action.php",{mode:"chengezclxzt",id:this.value,zhi:obj.elem.checked},function(result){
				console.log(result);
				var r=JSON.parse(result);
				if(r.status==1){
					layer.tips('修改成功', obj.othis);
				}else{
					layer.tips('修改失败', obj.othis);
					table.reload('currentTableId', {
						url: '../action.php?mode=getzichanleixing'
					});
				}
			})
		});
    });
</script>
</body>
</html>