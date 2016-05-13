<?php
class CSM_Controller extends CI_Controller {
	public function __construct() {
		parent::__construct();
		if (self::is_login()) {
			$bl = self::is_power();
			if ($bl) {
				//有权限
				self::sys_log($bl, true, 1);
			} else {
				//没权限
				self::error_jump();
			}
		} else {
			//未登录
			echo "<script type='text/javascript'>top.location.href='/index.php/Login/index'</script>";
			exit();
		}
	}
	
	/**
	 * 开启日志
	 * Enter description here ...
	 * @param unknown_type $menu_id
	 * @param unknown_type $flag
	 * @param unknown_type $log_type_id
	 */
	function sys_log($menu_id, $flag = FALSE, $log_type_id = 1) {
		//开启日志
		if ($flag && is_numeric($menu_id)) {
			$admin_id = self::getCookie('sys_user_id');
			$add_time =	time();
			$add_ip   = $this->input->ip_address();
			$data = array(
				'admin_id'    => $admin_id,
				'menu_id'     => $menu_id,
				'add_time' 	  => $add_time,
				'add_ip'      => $add_ip,
				'log_type_id' => $log_type_id
			);
			$this->load->model('system/Sys_log');
			return $this->Sys_log->insert($data);
		} else {
			return false;
		}
	}
	
	/**
	 * 错误跳转
	 * Enter description here ...
	 */
	function error_jump() {
		if($_POST) {
			//弹框提示，没有权限操作
			echo 0;	
		} else {
			//跳转页面
			echo "<script type='text/javascript'>window.location.href='/index.php/Error/index'</script>";
		}			
		exit();
	}
	
	/**
	 * 判断是否登录
	 * Enter description here ...
	 */
	function is_login() {
		$seg = strtolower(self::segment(1));
		if ($seg != 'login') {
			$sys_user_id    = self::getCookie('sys_user_id');
			$sys_user       = self::getCookie('sys_user');
			$sys_user_power = self::getCookie('sys_user_power');
			$sign = $sys_user_id . $sys_user . $sys_user_power;
			if ($sys_user_id && $sys_user && self::cookie_complete($sign)) {
				return true;
			} else {
				return false;
			}
		} else {
			return true;
		}
	}
	
	/**
	 * 判断是否有权限
	 * Enter description here ...
	 */
	function is_power() {
		$sys_user_power = self::getCookie('sys_user_power');
		if ($sys_user_power != "all") {
			//其它管理员
			$seg_0 = "index.php";
			$seg_1 = strtolower(self::segment(1));
			$seg_2 = strtolower(self::segment(2));
			$seg_str = "/" . $seg_0 . "/" . $seg_1 . "/" . $seg_2;
			$sys_user_power_arr = explode("|", $sys_user_power);
			$this->load->model('system/Sys_menu');
			$res = $this->Sys_menu->select_filed('id', array("lower(sys_url)"=>$seg_str));
			$bl = true;
			foreach ($res->result() as $items) {
				if (in_array($items->id, $sys_user_power_arr)) {
					$bl = $items->id;
				} else {
					$bl = false;
				}
			}
			return $bl;
		} else {
			//超级管理员
			return true;
		}
	}
	
	/**
	 * 获取post参数
	 * Enter description here ...
	 * @param unknown_type $filed
	 */
	function post($filed) {
		return $this->input->post($filed);
	}
	
	/**
	 * 获取get参数
	 * Enter description here ...
	 * @param unknown_type $num
	 * @param unknown_type $value
	 */
	function segment($num, $value = 0) {
		return $this->uri->segment($num, $value);
	}
	
	/**
	 * 设置cookie
	 * Enter description here ...
	 * @param unknown_type $arr：数组
	 * @param unknown_type $my_key：密钥
	 */
	function setCookie($arr, $encrypt_key = NULL) {
		$encrypt_key = self::encrpyt_key($encrypt_key);
		$this->load->library('Myencrypt');
		$sign = "";
		$seconds = 3600;
		foreach ($arr as $key=>$value) {
			$sign .= $value;
			set_cookie($key, $this->myencrypt->auto_code($value, 'ENCODE', $encrypt_key), $seconds);
		}
		$sign .= $encrypt_key;
		set_cookie('sign', self::sign_encrypt($sign, $encrypt_key), $seconds);
		return true;
	}
	
	/**
	 * 获取cookie
	 * Enter description here ...
	 * @param unknown_type $name
	 * @param unknown_type $encrypt_key
	 */
	function getCookie($name, $encrypt_key = NULL) {
		$encrypt_key = self::encrpyt_key($encrypt_key);
		$this->load->library('Myencrypt');
		$coki = get_cookie($name, TRUE);
		return $this->myencrypt->auto_code($coki, 'DECODE', $encrypt_key);
	}
	
	/**
	 * 加密密钥
	 * Enter description here ...
	 * @param unknown_type $key
	 */
	function encrpyt_key($key = NULL) {
		$key = is_null($key) ? $this->config->item('encryption_key') : $key;
		return $key;
	}
	
	/**
	 * sign加密
	 * Enter description here ...
	 * @param unknown_type $sign
	 */
	function sign_encrypt($sign, $key) {
		return hash_hmac("sha1", $sign, $key);
	}
	
	/**
	 * 检验cookie完整性
	 * Enter description here ...
	 * @param unknown_type $str
	 */
	function cookie_complete($str, $key = NULL) {
		$cokie_sign = get_cookie('sign', TRUE);
		if ($cokie_sign) {
			//获取config配置文件的参数值
			$key = self::encrpyt_key($key);
			$sign = $str . $key;
			$sign = self::sign_encrypt($sign, $key);
			if ($sign == $cokie_sign) {
				return true;
			} else {
				return false;
			}	
		} else {
			return false;
		}
	}
}
