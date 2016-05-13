<body style="padding:0px" onload="loadChecked()">
	<table id="tg" class="easyui-treegrid" title="权限列表"
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
				toolbar:'#tb'
			">
		<thead>
			<tr>
				<th data-options="field:'sys_menu',width:100,formatter:fromatCheck">菜单</th>
				<th data-options="field:'sys_url',width:80">调用方法</th>
			</tr>
		</thead>
	</table>
	<div id="tb" style="padding:5px;height:auto">
		<a href="javascript:void(0)" onclick="winlocation()" class="easyui-linkbutton" iconCls="icon-back" plain="true">返回</a>
		<a href="javascript:void(0)" onclick="getChecked()" class="easyui-linkbutton" iconCls="icon-add" plain="true">授权</a>
		<input type="hidden" id="key_id" value="<?php echo $id;?>" />
		<input type="hidden" id="type" value="<?php echo $type;?>" />
		<input type="hidden" id="power" value="<?php echo $power;?>" />
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
		//复选框格式化
		function fromatCheck(value, rowData){
            return "<input type='checkbox' id='chk_"+rowData.id+"' value='"+rowData.id+"' onclick='checkboxCli("+rowData.id+")'>"+rowData.sys_menu;
		}
		//窗口变化事件
		$(window).resize(function(){
			$('#tg').treegrid('resize');
		});

		//复选框选中事件
		function checkboxCli(check_id) {
			var cb = '#chk_'+check_id;
			//查找子级元素
		    var children = $("#tg").treegrid("getChildren", check_id);
		    for(var i=0; i < children.length; i++){
		    	$('#chk_'+children[i].id)[0].checked = $(cb)[0].checked;   
			}
			//查找父级
			if ($(cb)[0].checked) {
				//复选框选中
			    var parent = $("#tg").treegrid("getParent", check_id);
			    if (parent) {
				    $('#chk_'+parent.id)[0].checked = true;
					//循环查父级元素
				    while(parent) {
				    	parent = $("#tg").treegrid("getParent", parent.id);
				    	if (parent) {
						    $('#chk_'+parent.id)[0].checked = true;
				    	}
					}
			    }
			} else {
				//复选框取消
				var parent = $("#tg").treegrid("getParent", check_id);
				if (parent) {
					children = $("#tg").treegrid("getChildren", parent.id);
					var flag = true;
					for(var i=0; i < children.length; i++){
				    	if ($('#chk_'+children[i].id)[0].checked) {
				    		flag = false;
				    		break;
						}   
					}
					if (flag) {
						$('#chk_'+parent.id)[0].checked = false;
					}
					while(parent) {
						parent = $("#tg").treegrid("getParent", parent.id);
						if (parent) {
							children = $("#tg").treegrid("getChildren", parent.id);
							flag = true;
							for(var i=0; i < children.length; i++){
						    	if ($('#chk_'+children[i].id)[0].checked) {
						    		flag = false;
						    		break;
								}   
							}
							if (flag) {
								$('#chk_'+parent.id)[0].checked = false;
							}
						}
					}
				}
			}
		}
		//获取选中的值
		function getChecked() {
			var str = "";
			var split = "";
			$("input:checked").each(function(){
		        var id = this.value;
		        str += split + id;
		        split = "|";
		    });
			var type = $("#type").val();
			var id = $("#key_id").val();
			$.ajax({
				type:"POST",
				url:"/index.php/System/sysPowerSet",
				data:"type="+type+"&id="+id+"&power="+str+"&tmp="+Math.random(),
				dataType:"json",
				success:function(arr) {
					if (arr.flag) {  
						suc_slide("成功提示", arr.msg);
					} else {
						fail_alert("错误提示", arr.msg);
					}
				}
			});
		}
		//载入授权
		function loadChecked() {
			setTimeout(function(){
				var power = $("#power").val();
				var arr = power.split("|");
				for(var i = 0; i < arr.length; i++) {
					$('#chk_'+arr[i]).attr('checked', true);
				}
			},500);
		}
		//页面跳转
		function winlocation() {
			var type = $("#type").val();
			if (type == 0) {
				window.location.href="/index.php/System/sysAdminIndex";
			} else {
				window.location.href="/index.php/System/sysGroupIndex";
			}
		}
		
	</script>
</body>