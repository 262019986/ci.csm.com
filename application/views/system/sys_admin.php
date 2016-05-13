<body style="padding:0px">
<table id="dg" title="管理员列表"
			data-options="
				iconCls: 'icon-ok',
				rownumbers:true,
				singleSelect:true,
				collapsible:true,
				pagination:true,
				url:'/index.php/System/sysAdminList',
				method:'get',
				pageSize: 20,
				border:false,
				toolbar:'#tb',
				fitColumns: true
				/*onDblClickRow: onClickRow*/
				">
		<thead>
			<tr>
				<th data-options="field:'id',width:80">id</th>
				<th data-options="field:'sys_user',width:100,editor:'text',required:true">管理员</th>
				<th data-options="field:'sys_pwd',width:200,editor:'text',required:true">密码</th>
				<th data-options="field:'sex',width:80,
						formatter:sexType,editor:{
							type:'combobox',
							options:{
								valueField:'sex',
								textField:'sex_name',
								url:'/index.php/System/sysAdminSex',
								required:true,
								missingMessage:'请选择性别'
							}
						}">性别</th>
				<th data-options="field:'telphone',width:150,editor:'text'">联系电话</th>
				<th data-options="field:'group_id',width:100,
							formatter:function(value,row){
								return row.sys_group;
							},editor:{
								type:'combotree',
								options:{
									valueField:'id',
									textField:'text',
									url:'/index.php/System/sysGroupCombotree',
									required:true,
									missingMessage:'请选择分组'
								}
							}">分组</th>
				<th data-options="field:'power',width:60">权限</th>
			</tr>
		</thead>
	</table>
	<div id="tb" style="height:auto;padding:5px">
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="append()">添加</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-edit',plain:true" onclick="edit()">编辑</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="accept()">保存</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-undo',plain:true" onclick="reject()">取消</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" onclick="removeit()">删除</a>
	</div>
	<script type="text/javascript">
		//类型格式化
		function sexType(value){
	    	if (value == 0){
		    	return "女";
	    	} else {
		    	return "男";
	    	}
		}
		$(function(){
			var pager = $('#dg').datagrid().datagrid('getPager');	// get the pager of datagrid		
		})
		//窗口变化事件
		$(window).resize(function(){
			$('#dg').datagrid('resize');
		});
		var editIndex = undefined;
		//结束编辑
		function endEditing(){
			if (editIndex == undefined){
				return true;
			}
			if ($('#dg').datagrid('validateRow', editIndex)){
				var ed = $('#dg').datagrid('getEditor', {index:editIndex,field:'sex'});
				var sex_name = $(ed.target).combobox('getText');
				$('#dg').datagrid('getRows')[editIndex]['sex'] = sex_name;
				var old_pwd = $('#dg').datagrid('getRows')[editIndex]['sys_pwd'];
				$('#dg').datagrid('endEdit', editIndex);
				//更新数据
				var row = $('#dg').datagrid('getRows')[editIndex];
				var id = row['id'];
				var sys_user = row['sys_user'];
				var sys_pwd =row['sys_pwd'];
				var sex = row['sex'];
				var telphone = row['telphone'];
				var group_id = row['group_id'];
				submit(id, sys_user, sys_pwd, sex, telphone, group_id, old_pwd);
				editIndex = undefined;
				return true;
			} else {
				return false;
			}
		}
		//单击事件
		function onClickRow(index){
			if (editIndex != index){
				if (endEditing()){
					$('#dg').datagrid('selectRow', index)
							.datagrid('beginEdit', index);
					editIndex = index;
				} else {
					$('#dg').datagrid('selectRow', editIndex);
				}
			}
		}
		//编辑
		function edit(){
			var index = $('#dg').datagrid('getRowIndex', $('#dg').datagrid('getSelected')); 
			$('#dg').datagrid('selectRow', index)
			.datagrid('beginEdit', index);
			editIndex = index;
		}
		//添加
		function append(){
			if (endEditing()){
				$('#dg').datagrid('appendRow',{status:'P'});
				editIndex = $('#dg').datagrid('getRows').length-1;
				$('#dg').datagrid('selectRow', editIndex)
						.datagrid('beginEdit', editIndex);
			}
		}
		//删除
		function removeit(){
			var row = $('#dg').treegrid('getSelected');
			if (row){
				$.messager.confirm('友情提示', '确定删除?', function(r) {
					if (r) {
						var index = $('#dg').datagrid('getRowIndex', row); 
						$('#dg').datagrid('cancelEdit', index).datagrid('deleteRow', index); 
						var id = row.id
						$.ajax({
							type:"POST",
							url:"/index.php/System/sysAdminDel",
							data:"id="+id+"&tmp="+Math.random(),
							dataType:"json",
							success:function(arr){ 
								$('#dg').datagrid('reload');
								if (arr.flag) {
									suc_slide("成功提示", arr.msg);
								} else {  
									fail_alert("错误提示", arr.msg);
								}
							}
						});
					}
				});
			}
			editIndex = undefined;
		}
		//保存
		function accept(){
			if (endEditing()){
				$('#dg').datagrid('acceptChanges');
			}
		}
		//取消
		function reject(){
			$('#dg').datagrid('rejectChanges');
			editIndex = undefined;
		}
		//提交
		function submit(id, sys_user, sys_pwd, sex, telphone, group_id, old_pwd) {
			if (id != "" && id != null) {
				//更新
				if (old_pwd == sys_pwd) {
					sys_pwd = "";
				}
				submit_up(id, sys_user, sys_pwd, sex, telphone, group_id);
			} else {
				//添加
				submit_add(sys_user, sys_pwd, sex, telphone, group_id);
			}
		}
		//更新
		function submit_up(id, sys_user, sys_pwd, sex, telphone, group_id) {
			$.ajax({
				type:"POST",
				url:"/index.php/System/sysAdminUp",
				data:"id="+id+"&sys_user="+sys_user+"&sys_pwd="+sys_pwd+"&sex="+sex+"&telphone="+telphone+"&group_id="+group_id+"&tmp="+Math.random(),
				dataType:"json",
				success:function(arr) {
					$('#dg').datagrid('reload');  
					if (arr.flag) {
						suc_slide("成功提示", arr.msg);
					} else { 
						fail_alert("错误提示", arr.msg);
					}
				}
			});
		}
		//添加
		function submit_add(sys_user, sys_pwd, sex, telphone, group_id) {
			$.ajax({
				type:"POST",
				url:"/index.php/System/sysAdminAdd",
				data:"sys_user="+sys_user+"&sys_pwd="+sys_pwd+"&sex="+sex+"&telphone="+telphone+"&group_id="+group_id+"&tmp="+Math.random(),
				dataType:"json",
				success:function(arr) {
					$('#dg').datagrid('reload');  
					if (arr.flag) {
						suc_slide("成功提示", arr.msg);
					} else {
						fail_alert("错误提示", arr.msg);
					}
				}
			});
		}
	</script>
</body>