<?php
class Sys_menu extends CI_Model {
	private $tb_name = 'menu';
	
	function __construct(){
		parent::__construct();
	}
	
	/**
	 * 菜单列表
	 * Enter description here ...
	 * @param unknown_type $type
	 */
	function menu_list($type = 0) {
		$status = 1;
		$res = $this->db->select('id,sys_menu,sys_url,parent_id,order,type')
						->from($this->tb_name)
						->where('status', $status);
		if ($type == 0) {
			$res = $this->db->where('type', $type);
		}
		$res = $this->db->order_by('parent_id asc,order asc')
				        ->get();
		return $res;
	}
	
	/**
	 * 菜单添加
	 * Enter description here ...
	 * @param unknown_type $data
	 */
	function insert($data) {
		$this->db->insert($this->tb_name, $data);
		$id = $this->db->insert_id();
		return $id;
	}
	
	/**
	 * 菜单更新
	 * Enter description here ...
	 * @param unknown_type $data
	 * @param unknown_type $where
	 */
	function update($data, $where) {
		return $this->db->update($this->tb_name, $data, $where);
	}
	
	/**
	 * 根据条件查询
	 * @param unknown_type $filed
	 */
	function select_filed($filed, $arr) {
		$res = $this->db->select($filed)
						->from($this->tb_name)
						->where($arr)
						->get();
		return $res;
	}
}