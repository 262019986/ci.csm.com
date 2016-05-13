<body style="padding:0px">
<table id="dg" title="广告列表"
			data-options="
				iconCls: 'icon-ok',
				rownumbers:true,
				singleSelect:true,
				collapsible:true,
				pagination:true,
				url:'/index.php/Advert/advertList',
				method:'get',
				pageSize: 20,
				border:false,
				toolbar:'#tb',
				fitColumns: true
				/*onDblClickRow: onClickRow*/
				">
		<thead>
			<tr>
				<th data-options="field:'id',width:30">id</th>
				<th data-options="field:'title',width:100">标题</th>
				<th data-options="field:'img',width:100">图片</th>
				<th data-options="field:'note',width:100">备注</th>
				<th data-options="field:'type_name',width:100">归属</th>
				<th data-options="field:'sort',width:50">排序</th>
				<th data-options="field:'status',width:50,formatter:statusType">状态</th>
			</tr>
		</thead>
	</table>
	<div id="tb" style="height:auto;padding:5px">
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="">添加</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-edit',plain:true" onclick="">编辑</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="">保存</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-undo',plain:true" onclick="">取消</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" onclick="">删除</a>
	</div>
	<script type="text/javascript">
		//类型格式化
		function statusType(value){
	    	if (value == 0){
		    	return "关闭";
	    	} else {
		    	return "开启";
	    	}
		}		
		$(function(){
			var pager = $('#dg').datagrid().datagrid('getPager');	// get the pager of datagrid		
		})
		//窗口变化事件
		$(window).resize(function(){
			$('#dg').datagrid('resize');
		});
	</script>
</body>