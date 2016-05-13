<body style="padding:0px">
	<table id="tg" class="easyui-treegrid" title="菜单列表"
			data-options="
				iconCls: 'icon-ok',
				rownumbers: true,
				animate: true,
				collapsible: true,
				fitColumns: true,
				url: '/index.php/System/sysMenuList',
				method: 'get',
				idField: 'id',
				treeField: 'sys_menu',
				/*
				loadFilter: pagerFilter,
				pagination: true,
				pageSize: 10,
				pageList: [2,10,20,50],
				fit:true,
				*/
				border:false,
				toolbar:'#tb',
				onAfterEdit:function(row,changes){
					afterSave(row);
				}
			">
		<thead>
			<tr>
				<th data-options="field:'sys_menu',width:100,editor:'text'">菜单</th>
				<th data-options="field:'sys_url',width:80,editor:'text'">地址</th>
				<th data-options="field:'order',width:80,editor:'numberbox'">排序</th>
				<th data-options="field:'type',width:80,formatter:formatType,editor:{
							type:'combobox',
							options:{
								valueField:'id',
								textField:'text',
								url:'/index.php/System/sysMenuType',
								required:true,
								missingMessage:'请选择类别'
							}
						}">类别</th>
			</tr>
		</thead>
	</table>
	<div id="tb" style="padding:5px;height:auto">
		<a href="javascript:void(0)" onclick="dialog_open()" class="easyui-linkbutton" iconCls="icon-add" plain="true">添加</a>
		<a href="javascript:void(0)" onclick="edit()" class="easyui-linkbutton" iconCls="icon-edit" plain="true">编辑</a>
		<a href="javascript:void(0)" onclick="save()" class="easyui-linkbutton" iconCls="icon-save" plain="true">保存</a>
		<a href="javascript:void(0)" onclick="cancel()" class="easyui-linkbutton" iconCls="icon-undo" plain="true">取消</a>
		<a href="javascript:void(0)" onclick="del()" class="easyui-linkbutton" iconCls="icon-remove" plain="true">删除</a>
	</div>
	<div id="dlg" class="easyui-dialog" title="添加菜单" data-options="iconCls:'icon-save',modal:true,closed:true" style="width:400px;height:auto;padding:10px">
		<div style="padding:10px 60px 20px 60px">
		    <form id="ff" method="post">
		    	<table cellpadding="5">
		    		<tr>
		    			<td>系统菜单:</td>
		    			<td><input class="easyui-validatebox textbox" type="text" name="sys_menu" data-options="required:true,missingMessage:'请输入长度0~10的系统菜单名称'" maxlength="10"></input></td>
		    		</tr>
		    		<tr>
		    			<td>菜单地址:</td>
		    			<td><input class="easyui-validatebox textbox" type="text" name="sys_url" maxlength="50"></input></td>
		    		</tr>
		    		<tr>
		    			<td>排序:</td>
		    			<td><input class="easyui-numberbox textbox" name="order" maxlength="5"></input></td>
		    		</tr>
		    		<tr>
		    			<td>类别:</td>
		    			<td>
		    				<select class="easyui-combobox" name="type"><option value="0">菜单</option><option value="1">功能</option></select>
		    				<input id="parent_id" type="hidden" name="parent_id" value="0" />
		    			</td>
		    		</tr>
		    	</table>
		    </form>
		    <div style="text-align:center;padding:5px">
		    	<a href="javascript:void(0)" class="easyui-linkbutton" onclick="submitForm()">保 存</a>
		    	<a href="javascript:void(0)" class="easyui-linkbutton" onclick="clearForm()">清 除</a>
		    </div>
	    </div>
	</div>
	<script type="text/javascript">
		//类型格式化
		function formatType(value){
	    	if (value == 0){
		    	return "菜单";
	    	} else {
		    	return "功能";
	    	}
		}
		
		var editingId;
		//编辑
		function edit(){
			if (editingId != undefined){
				$('#tg').treegrid('select', editingId);
				return;
			}
			var row = $('#tg').treegrid('getSelected');
			if (row){
				editingId = row.id
				$('#tg').treegrid('beginEdit', editingId);
			}
		}
		//保存
		function save(){
			if (editingId != undefined){
				var t = $('#tg');
				t.treegrid('endEdit', editingId);
				editingId = undefined;
				var persons = 0;
				var rows = t.treegrid('getChildren');
				for(var i=0; i<rows.length; i++){
					var p = parseInt(rows[i].persons);
					if (!isNaN(p)){
						persons += p;
					}
				}
				var frow = t.treegrid('getFooterRows')[0];
				frow.persons = persons;
				t.treegrid('reloadFooter');
			}
		}
		//保存后，数据更新
		function afterSave(obj) {
			var sysMenu = obj.sys_menu;
			var sysUrl = obj.sys_url;
			var order = obj.order;
			var type = obj.type;
			$.ajax({
				type:"POST",
				url:"/index.php/System/sysMenuUp",
				data:"id="+editingId+"&sys_menu="+sysMenu+"&sys_url="+sysUrl+"&order="+order+"&type="+type+"&tmp="+Math.random(),
				dataType:"json",
				success:function(arr){ 
					$('#tg').treegrid('reload');  
					if (arr.flag) {
						suc_slide("成功提示", arr.msg);
					} else {
						fail_alert("错误提示", arr.msg);
					}
				}
			});
		}
		//取消
		function cancel(){
			if (editingId != undefined){
				$('#tg').treegrid('cancelEdit', editingId);
				editingId = undefined;
			}
		}
		//删除
		function del() {
			var row = $('#tg').treegrid('getSelected');
			if (row){
				$.messager.confirm('友情提示', '确定删除?', function(r) {
					if (r) {
						var id = row.id
						$.ajax({
							type:"POST",
							url:"/index.php/System/sysMenuDel",
							data:"id="+id+"&tmp="+Math.random(),
							dataType:"json",
							success:function(arr){ 
								$('#tg').treegrid('reload'); 
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
		}
		//分页
		function pagerFilter(data){
            if ($.isArray(data)){    // is array  
                data = {  
                    total: data.length,  
                    rows: data  
                }  
            }
            var dg = $(this);  
			var state = dg.data('treegrid');
            var opts = dg.treegrid('options');  
            var pager = dg.treegrid('getPager');  
            pager.pagination({  
                onSelectPage:function(pageNum, pageSize){  
                    opts.pageNumber = pageNum;  
                    opts.pageSize = pageSize;  
                    pager.pagination('refresh',{  
                        pageNumber:pageNum,  
                        pageSize:pageSize  
                    });  
                    dg.treegrid('loadData',data);  
                }  
            });  
            if (!data.topRows){  
            	data.topRows = [];
            	data.childRows = [];
            	for(var i=0; i<data.rows.length; i++){
            		var row = data.rows[i];
            		row._parentId ? data.childRows.push(row) : data.topRows.push(row);
            	}
				data.total = (data.topRows.length);
            }  
            var start = (opts.pageNumber-1)*parseInt(opts.pageSize);  
            var end = start + parseInt(opts.pageSize);  
			data.rows = $.extend(true,[],data.topRows.slice(start, end).concat(data.childRows));
			return data;
		}
		//表单提交
		function submitForm(){
			$('#ff').form('submit');
		}
		//表单取消
		function clearForm(){
			$('#ff').form('clear');
		}
		//页面载入绑定表单的form事件
		$(function() {
			$('#ff').form({
				url:'/index.php/System/sysMenuAdd',
				onSubmit: function(){
					// 做某些检查
					// 返回 false 来阻止提交
					return $("#ff").form('validate');
				},success:function(data){
					var arr = JSON.parse(data);  
					$('#tg').treegrid('reload'); 
					if (arr.flag) {
						$('#dlg').dialog('close');
						suc_slide("成功提示", arr.msg);
					} else {
						fail_alert("错误提示", arr.msg);
					}
				}
			}); 
		});	
		//窗口变化事件
		$(window).resize(function(){
			$('#tg').treegrid('resize');
		});
		//弹出添加层
		function dialog_open() {
			var row = $('#tg').treegrid('getSelected');
			if (row){
				$("#parent_id").val(row.id);
			}
			$('#dlg').dialog('open');
		}
	</script>
</body>