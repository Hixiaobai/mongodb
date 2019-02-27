
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Hi小白</title>
  <link rel="stylesheet" type="text/css" href="lib/layui/css/layui.css">
  <script type="text/javascript" src="lib/layui/layui.js"></script>
  <style type="text/css">
  	.layui-table-page{
  		text-align:right;
  	}
  </style>
</head>
<body>
  <div class="layui-container">
  <div class="layui-row" style="margin-top: 50px;">
    
    <div class="layui-row">
      <div class="layui-col-md4">
        <button class="layui-btn layui-btn-primary" id="add">
          <i class="layui-icon">&#xe608;</i> 添加
        </button>
      </div>
      <div class="layui-col-md4 layui-col-md-offset3">
         <input id="search" type="text" name="search" placeholder="请输入您要搜索的内容" autocomplete="off" class="layui-input">
      </div>
      <div class="layui-col-md1">
        <button class="layui-btn layui-btn-fluid" id="btn">
          <i class="layui-icon">&#xe615;</i> 搜索
        </button>
      </div>
    </div>
    <table class="layui-hide" id="test" lay-filter="demo">
      	<script type="text/html" id="barDemo">
		  <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>
		  <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
		  <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
		</script>
    </table>
    <!-- 分页 -->
    <div id="test1" style="text-align: right;"></div>
  </div>
</div>  
<script type="text/javascript" src="lib/js/min.js"></script>
</body>
</html>