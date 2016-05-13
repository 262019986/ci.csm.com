<body>
	<div id="w" class="easyui-window" title="友情提示" data-options="iconCls:'icon-tip'" style="width:435px;height:auto;padding:10px;">
		<div style="padding:10px 60px 20px 60px">
			<div style="text-align:center;padding:5px;margin-top:15px;font-size:14px;">
		 		权限不足，无法操作
		 	</div>
		   	<div style="text-align:center;padding:5px;margin-top:15px;">
		    	<a href="javascript:void(0)" class="easyui-linkbutton" onclick="history.go(-1);">返回上一级</a>
		    </div>
	    </div>
	</div>
	<script type="text/javascript">
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
		</script>
</body>