<body>
	<div id="w" class="easyui-window" title="管理员登录" data-options="iconCls:'icon-tip'" style="width:435px;height:auto;padding:10px;">
		<div style="padding:10px 60px 20px 60px">
		    <form id="ff" method="post">
		    	<table cellpadding="5">
		    		<tr>
		    			<td>管理员:</td>
		    			<td><input class="easyui-validatebox textbox" type="text" style="height:22px;width:150px;" name="sys_user" data-options="required:true,missingMessage:'请输入管理员'" maxlength="30"></input></td>
		    		</tr>
		    		<tr>
		    			<td>密码:</td>
		    			<td><input class="easyui-validatebox textbox" type="password" style="height:22px;width:150px;" name="sys_pwd" maxlength="30" data-options="required:true,missingMessage:'请输入密码'"></input></td>
		    		</tr>
		    	</table>
		    </form>
		    <div style="text-align:center;padding:5px;margin-top:15px;">
		    	<a href="javascript:void(0)" class="easyui-linkbutton" onclick="submitForm()">登 录</a>
		    	<a href="javascript:void(0)" class="easyui-linkbutton" onclick="clearForm()" style="margin-left:10px">清 除</a>
		    </div>
	    </div>
	</div>
	<script type="text/javascript">
		//表单提交
		function submitForm(){
			$('#ff').form('submit');
		}
		//表单取消
		function clearForm(){
			$('#ff').form('clear');
		}
		/**
		 * 水平居中left
		 * @param x:元素的宽度
		 * @returns {Number}
		 */
		function HorCenter(x) {
			return (document.documentElement.clientWidth-x)/2;
		}
		/**
		 * 垂直居中top
		 * @param y:元素的高度
		 * @returns {Number}
		 */
		function VerCenter(y) {
			return (document.documentElement.clientHeight-y)/2+document.documentElement.scrollTop+document.body.scrollTop;
		}
		//窗口变化事件
		$(window).resize(function(){
			$('#w').window('resize',{left:HorCenter($('#w').width()),top:VerCenter($('#w').height())});
		});
		//页面载入绑定表单的form事件
		$(function() {
			$('#ff').form({
				url:'/index.php/Login/login',
				onSubmit: function(){
					// 做某些检查
					// 返回 false 来阻止提交
					return $("#ff").form('validate');
				},success:function(data){
					var arr = JSON.parse(data);  
					if (arr.flag) {
						window.location.href = "/index.php/Welcome/index";
						//suc_slide("成功提示", arr.msg);
					} else {
						fail_alert("错误提示", arr.msg);
					}
				}
			}); 
		});
		//回车登录
		$(document).keydown(function(e){ 
			var key_flag = true;
			if (key_flag) {
				var curKey = e.which; 
				if(curKey == 13){
					submitForm();
				}
			} 
		}); 	
		</script>
</body>