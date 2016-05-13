<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CSM_Controller {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function index() {
		$this->load->view('header');
		$this->load->view('login');
		$this->load->view('footer');
	}
	
	/**
	 * 登录
	 * Enter description here ...
	 */
	public function login() {
		$res = array();
		$res['flag'] = false;
		$res['msg']  = '';
		$sys_user = $this->post('sys_user');
		$sys_pwd  = $this->post('sys_pwd');
		if (!empty($sys_user) && !empty($sys_pwd)) {
			$pwd       = "";
			$id        = "";
			$power     = "";
			$power_arr = array();
			$group_id  = "";
			$super     = "";
			$this->load->model('system/Sys_admin');
			$data  = $this->Sys_admin->select_filed('id,sys_pwd,super,group_id,power', array('sys_user'=>$sys_user));
			foreach ($data->result() as $items) {
				$id        = $items->id;
				$pwd       = $items->sys_pwd;
				$power_arr = explode("|", $items->power);
				$group_id  = $items->group_id;
				$super     = $items->super;
			}
			if ($pwd == md5($sys_pwd)) {
				//成功
				$res['flag'] = true;
				$res['msg']  = '成功登录';
				//是否超管
				if ($super != 1) {
					//获取管理组权限
					$group_power_arr = array();
					if (!empty($group_id)) {
						$this->load->model('system/Sys_group');
						$group_data = $this->Sys_group->select_filed('power', array('id'=>$group_id));
						foreach ($group_data->result() as $items) {
							$group_power_arr = explode("|", $items->power);
						}
					}
					//权限合并
					$power_arr = array_merge($power_arr, $group_power_arr);
					//权限去重
					$power_arr = array_unique($power_arr);
					$power = implode("|", $power_arr);
				} else {
					$power = "all";
				}
				//设置cookie
				$this->setCookie(array('sys_user_id'=>$id, 'sys_user'=>$sys_user, 'sys_user_power'=>$power));
			} else {
				$res['msg'] = '登录失败';
			}
		} else {
			$res['msg'] = '请输入管理员/密码';
		}
		echo json_encode($res);
	}
}
?>