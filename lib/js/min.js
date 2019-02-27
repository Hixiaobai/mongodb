layui.use(['jquery', 'table'], function(){
		var $ = layui.$ //重点处
    	,layer = layui.layer;
	    var table = layui.table;
	    table.render({
	    elem: '#test'
	    ,url:'/plug-in/MongoApi.php?pram=getData'
	    ,cols: [[
	      {field:'id', title: 'ID', align:'center', width:50}
	      ,{field:'title', title: '标题', align:'center', width:200}
	      ,{field:'coutent', title: '内容', align:'center'}
	      ,{field:'_id', title: '_id', hide:true}
	      ,{field:'time', title: '时间', align:'center', width:200}
	      ,{fixed: 'right', title:'操作', toolbar: '#barDemo', width:170, align:'center'}

	    ]]
	    ,page: true
	    ,theme:'page'
	    ,align:'center'
	    ,text:'没有查询到数据，请稍后再试！'
	  });

	  //监听工具条
	  table.on('tool(demo)', function(obj){
	    var data = obj.data;
	    if(obj.event === 'detail'){
	    	$.get('/plug-in/MongoApi.php?pram=getData','_id='+data._id.$oid,function(res){
	    		if(res.code != 0){
	    			layer.msg(res.msg);
	    		}
			    layer.open({
			        type: 1, 
			        title:'标题:' + res.data[0]['title'],
			        content: res.data[0]['coutent'],
			        area: ['800px', '400px'],
			        offset: '50px'
			    }); 
	    	},'json')
	    } else if(obj.event === 'del'){
	      layer.open({
			  content: '是否确认删除?'
			  ,btn: ['确认', '取消']
			  ,yes: function(index, layero){
			    $.get('/plug-in/MongoApi.php?pram=del','_id='+data._id.$oid,function(res){
			    	console.log(res)
			    	layer.msg(res.msg);
			    	if(res.code == 0){
			    		obj.del();
			    	}
			    	
			    },'json')
			    layer.close(index);
			  }
			  ,btn2: function(index, layero){
			    //按钮【按钮二】的回调
			    layer.close(index);
			    //return false 开启该代码可禁止点击该按钮关闭
			  }
			  ,cancel: function(){ 
			    //右上角关闭回调
			    
			  }
			});
	    } else if(obj.event === 'edit'){
	    	layer.open({
		        type: 2, 
		        title:'修改内容',
		        content: '/view/edit.html', //这里content是一个URL，如果你不想让iframe出现滚动条，你还可以content: ['http://sentsin.com', 'no']
		        area: ['800px', '400px'],
		        offset: '50px',
		        success: function(layero, index){
		        	$.get('/plug-in/MongoApi.php?pram=getData','_id='+data._id.$oid,function(res){
					    var body = layer.getChildFrame('body', index);
					    var iframeWin = window[layero.find('iframe')[0]['name']]; //
					    $(body.find('input')[0]).val(res.data[0]['title']);
					    $(body.find('input')[1]).val(res.data[0].coutent);
					    $(body.find('input')[2]).val(res.data[0]._id.$oid);
		        	},'json');
			    }
		    }); 
	    }
	  });

	});

  //主动加载jquery模块
  layui.use(['jquery', 'layer', 'table'], function(){ 
    var $ = layui.$ //重点处
    ,layer = layui.layer;
    
    // 添加页面
    $('#add').click(function(){
      layer.open({
        type: 2, 
        title:'添加内容',
        content: '/view/add.html', //这里content是一个URL，如果你不想让iframe出现滚动条，你还可以content: ['http://sentsin.com', 'no']
        area: ['800px', '400px'],
        offset: '50px'

      }); 
    })

    $('#btn').click(function(){
    	let data = $('#search').val();
    	if(data.length == 0){
    		layer.msg('搜索内容不能为空')
    	}else{
		    var table = layui.table;
		    table.render({
		    elem: '#test'
		    ,url:'/plug-in/MongoApi.php?pram=query&title=' + data
		    ,cols: [[
		      {field:'id', title: 'ID', align:'center'}
		      ,{field:'title', title: '标题', align:'center'}
		      ,{field:'coutent', title: '内容', align:'center'}
		      ,{field:'_id', title: '_id', hide:true}
		      ,{field:'time', title: '时间', align:'center'}
		      ,{fixed: 'right', title:'操作', toolbar: '#barDemo', width:170, align:'center'}

		    ]]
		    ,page: true
		    ,theme:'page'
		    ,align:'center'
		    ,text:'没有查询到数据，请稍后再试！'
		  });
    	}
    	
    });

});