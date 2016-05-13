<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CSM_Controller {
	
	protected $sysMenuArr = array();
	protected $sysUserPowerArr = array();
	
	public function __construct() {
		parent::__construct();
		$this->sysMenuArr = self::sysMenuRes();
		$this->sysUserPowerArr = explode("|", $this->getCookie('sys_user_power'));
	}
	
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{	
		$data['menu_str'] = self::sysMenuList();
		$data['sys_user'] = $this->getCookie('sys_user');
		$this->load->view('header');
		$this->load->view('welcome_message', $data);
		$this->load->view('footer');
	}
	
	/**
	 * 系统菜单
	 * Enter description here ...
	 */
	function sysMenuList() {
		$menu_str = "";
		$res      = $this->sysMenuArr;
		$powerArr = $this->sysUserPowerArr;
		foreach ($res as $items) {
			if ($items->parent_id == 0) {
				if ($powerArr[0] == "all") {
					//超管权限
					$menu_str .= self::menuStr($items);
				} else {
					if (in_array($items->id, $powerArr)) {
						$menu_str .= self::menuStr($items);
					}
				}
			} else {
				break;
			}
		}
		return $menu_str;
	}
	
	/**
	 * 第一级菜单
	 * Enter description here ...
	 * @param unknown_type $menu
	 * @param unknown_type $id
	 */
	function menuStr($items) {
		$menu_str = "<div title=\"".$items->sys_menu."\" data-options=\"iconCls:'icon-search'\" style=\"padding:0px\">";
		$menu_str .= self::treeSysMenu($items->id);
		$menu_str .= "</div>";
		return $menu_str;
	}
	
	/**
	 * 查询系统菜单
	 */
	function sysMenuRes() {
		$this->load->library('Mycache');
		$sys_menu_list = $this->mycache->get_cache('sys_menu_list');
		if (!$sys_menu_list) {
			$this->load->model('system/Sys_menu');
			$sys_menu_list = $this->Sys_menu->menu_list()->result();
			$this->mycache->save_cache('sys_menu_list', $sys_menu_list, 86400);
		}
		return $sys_menu_list;
	}
	
	/**
	 * 系统树形菜单
	 * @param unknown_type $parent_id
	 */
	function treeSysMenu($parent_id) {
		return "<ul class=\"easyui-tree\" data-options=\"url: '/index.php/Welcome/treeMenuData/".$parent_id."',method: 'get',animate: true,lines:true,formatter:formatterNode\"></ul>";
	}
	
	/**
	 * 系统树形菜单输出
	 * Enter description here ...
	 * @param unknown_type $parent_id
	 */
	public function treeMenuData($parent_id) {
		$tree_str = '[';
		$tree_str .= self::treeMenuDataFor($parent_id);
		$tree_str .=']';
		echo $tree_str;
	}
	
	/**
	 * 系统菜单数据
	 * Enter description here ...
	 * @param unknown_type $parent_id
	 */
	function treeMenuDataFor($parent_id) {
		$tree_str = '';
		$split    = '';
		$res      = $this->sysMenuArr;
		$powerArr = $this->sysUserPowerArr;
		foreach ($res as $items) {
			if ($items->parent_id == $parent_id) {
				if ($powerArr[0] == "all") {
					//超管
					$tree_str .= self::treeStr($items, $split);
					$split = ",";
				} else {
					if (in_array($items->id, $powerArr)) {
						$tree_str .= self::treeStr($items, $split);
						$split = ",";
					}
				}
			} elseif ($items->parent_id > $parent_id) {
				break;
			}
		}
		return $tree_str;
	}
	
	/**
	 * 子菜单
	 * Enter description here ...
	 * @param unknown_type $items
	 * @param unknown_type $split
	 */
	function treeStr($items, $split) {
		$tree_str = "";
		if (!empty($items->sys_url)) {
			$tree_str .= $split . '{"id":'.$items->id.',"text":"<a href=\"javascript:void(0)\" onclick=\"addPanel(\''.$items->sys_menu.'\', \''.$items->sys_url.'\')\">'.$items->sys_menu.'</a>","children":[';
		} else {
			$tree_str .= $split . '{"id":'.$items->id.',"text":"'.$items->sys_menu.'","children":[';
		}
		$tree_str .= self::treeMenuDataFor($items->id);
		$tree_str .= ']}';
		return $tree_str;
	}
	
	/**
	 * 退出系统
	 * Enter description here ...
	 */
	public function sys_exit() {
		delete_cookie('sys_user');
		delete_cookie('sys_user_id');
		delete_cookie('sys_user_power');
		delete_cookie('sign');
		echo 1;
	}
}
