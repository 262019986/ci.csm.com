<body class="easyui-layout">
	<div data-options="region:'north',border:false" style="height:30px; text-align:right; padding:10px;">管理员：<?php echo $sys_user;?>;<a style="padding-left:10px" href="javascript:void(0)" onclick="exit()">安全退出</a></div>
	<div data-options="region:'west',split:true,title:'菜单导航'" style="width:200px;">
		<!-- 左边导航 -->
		<div class="easyui-accordion" style="border:0px;" data-options="fit:true">
			<?php echo $menu_str;?>
		</div>
	</div>
	<div data-options="region:'east',split:true,collapsed:true,title:'快捷方式'" style="width:100px;padding:10px;">east region</div>
	<div data-options="region:'south',border:false" style="height:50px;background:#A9FACD;padding:10px;">south region</div>
	<div data-options="region:'center'">	
		<!-- 中间内容 -->		
		<div id="tt" class="easyui-tabs" data-options="fit:true, border:false">
		</div>
	</div>
	<div id="mm" class="easyui-menu cs-tab-menu">
		<div id="mm-tabupdate">刷新</div>
		<div class="menu-sep"></div>
		<div id="mm-tabclose">关闭</div>
		<div id="mm-tabcloseother">关闭其他</div>
		<div id="mm-tabcloseall">关闭全部</div>
	</div>
	<script type="text/javascript">
		function formatterNode(node){
			var s = node.text;
			if (node.children){
				if (node.children.length > 0) {
					s += '&nbsp;<span>(' + node.children.length + ')</span>';
				}					
			}
			return s;
		}
		
		var index = 0;

		/**
		 * 创建iframe框架
		 */
		function createFrame(url) {
			return '<iframe id="ifr_'+index+'" src="'+url+'" style="width:100%;height:100%;" frameborder="0" scrolling="auto"></iframe>';
		}
		
		/**
		 * 添加标签
		 */
		function addPanel(subtitle, url){
			if (!$('#tt').tabs('exists', subtitle)) {
				index++;
				$('#tt').tabs('add',{
					title: subtitle,
					content: createFrame(url),
					closable: true
				});
			} else {
				$('#tt').tabs('select', subtitle);
			}
			bindPanel();
		}
		
		/**
		 * 删除标签
		 */
		function removePanel(){
			var tab = $('#tt').tabs('getSelected');
			if (tab){
				var index = $('#tt').tabs('getTabIndex', tab);
				$('#tt').tabs('close', index);
			}
		}
		
		/**
		 * 绑定标签右键
		 */
		function bindPanel() {
			$(".tabs-inner").bind('contextmenu', function(e) {
				$('#mm').menu('show', {
					left : e.pageX,
					top : e.pageY
				});
				var subtitle = $(this).children(".tabs-closable").text();
				$('#mm').data('currtab', subtitle);
				$('#tt').tabs('select', subtitle);
				return false;
			});
		}

		/**
		 * 绑定右键菜单事件
		 */
		function tabEven() {
			//刷新
			$('#mm-tabupdate').click(function(){
				var currTab = $('#tt').tabs('getSelected');
				var url = $(currTab.panel('options').content).attr('src');
				if(url != undefined && currTab.panel('options').title != '主页') {
					$('#tt').tabs('update', {
						tab:currTab,
						options:{
							content:createFrame(url)
						}
					})
				}
			});
			//关闭当前
			$('#mm-tabclose').click(function(){
				var currtab_title = $('#mm').data("currtab");
				$('#tt').tabs('close', currtab_title);
			});
			//全部关闭
			$('#mm-tabcloseall').click(function(){
				$('.tabs-inner span').each(function(i, n){
					var t = $(n).text();
					if(t != '主页') {
						$('#tt').tabs('close', t);
					}
				});
			});
			//关闭除当前之外的tab
			$('#mm-tabcloseother').click(function(){
				var prevall = $('.tabs-selected').prevAll();
				var nextall = $('.tabs-selected').nextAll();		
				if(prevall.length > 0){
					prevall.each(function(i, n){
						var t = $('a:eq(0) span', $(n)).text();
						if(t != '主页') {
							$('#tt').tabs('close', t);
						}
					});
				}
				if(nextall.length > 0) {
					nextall.each(function(i,n){
						var t = $('a:eq(0) span', $(n)).text();
						if(t != '主页') {
							$('#tt').tabs('close', t);
						}
					});
				}
				return false;
			});	
		}
		//安全退出
		function exit() {
			$.messager.confirm('友情提示', '确定退出?', function(r) {
				if (r) {
					$.ajax({
						type:"POST",
						url:"/index.php/Welcome/sys_exit",
						data:"tmp="+Math.random(),
						success:function(msg){ 
							window.location.href = '/index.php/Login/index';
						}
					});
				}
			});
		}

		/**
		 * 加载内容
		 */
		$(function() {
			tabEven();
		});	
	</script>
</body>