<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>系统管理</title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>plugins/jquery-easyui-1.3.6/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>plugins/jquery-easyui-1.3.6/themes/icon.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>plugins/jquery-easyui-1.3.6/demo/demo.css">
	<script type="text/javascript" src="<?php echo base_url();?>plugins/jquery-easyui-1.3.6/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>plugins/jquery-easyui-1.3.6/jquery.easyui.min.js"></script>
	<style>
	/*去掉tab滚动条*/
	.panel div{
		overflow:hidden;
	}
	</style>
	<script type="text/javascript">
	//成功的信息提示
	function suc_slide(myTitle, myMsg){
		$.messager.show({
			title:myTitle,
			msg:myMsg,
			timeout:5000,
			showType:'slide'
		});
	}
	//错误的信息提示
	function fail_alert(myTitle, myMsg) {
		if (myMsg == "" || myMsg == null) {
			myMsg = "权限不足，无法操作";
		}
		$.messager.alert(myTitle, myMsg, 'error');
	}
	</script>
</head>