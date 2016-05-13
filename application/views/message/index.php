<body style="padding:0px">
<table id="dg" title="整站的列表"
			data-options="
				iconCls: 'icon-ok',
				rownumbers:true,
				singleSelect:true,
				collapsible:true,
				pagination:true,
				url:'/index.php/System/sysLogList',
				method:'get',
				pageSize: 20,
				//pageList: [1,5,10,15],
				border:false,
				toolbar:'#tb',
				fitColumns: true
				/*onDblClickRow: onClickRow*/
				">
		<thead>
			<tr>
				<th data-options="field:'sys_user',width:50">管理员</th>
				<th data-options="field:'sys_menu',width:80">操作</th>
				<th data-options="field:'sys_url',width:200">调用方法</th>
				<th data-options="field:'add_time',width:100">时间</th>
				<th data-options="field:'add_ip',width:100">IP</th>
			</tr>
		</thead>
	</table>
	<div id="tb" style="padding:5px;height:auto">
		<div>
			时间 从: <input id="stime" class="easyui-datetimebox" style="width:180px">
			到: <input id="etime" class="easyui-datetimebox" style="width:180px">
			标题: 
			<input class="easyui-validatebox textbox" id="search" name="search" type="text" style="width: 300px"></input>
			<a class="easyui-linkbutton" iconCls="icon-search" onclick="mysearch()">Search</a>
		</div>
	</div>
	<script type="text/javascript">
		$(function(){
			var pager = $('#dg').datagrid().datagrid('getPager');	// get the pager of datagrid		
		})
		//窗口变化事件
		$(window).resize(function(){
			$('#dg').datagrid('resize');
		});
		//搜索
		function mysearch() {
			var stime = $("#stime").datetimebox("getValue")
			var etime = $("#etime").datetimebox("getValue")
			var search = $("#search").val();
			$.ajax({
				type:"POST",
				url:"/index.php/System/sysLogList",
				data:"stime="+stime+"&etime="+etime+"&search="+search+"&tmp="+Math.random(),
				success:function(msg) {
						var data = JSON.parse(msg);
						$('#dg').datagrid('loadData', data);
					}
				});
		}
	</script>
</body>