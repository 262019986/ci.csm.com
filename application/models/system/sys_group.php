<?php
class Sys_group extends CI_Model {
	private $tb_name = 'group';
	
	function __construct(){
		parent::__construct();
	}
	
	/**
	 * 管理组列表
	 * Enter description here ...
	 * @param unknown_type $type
	 */
	function group_list() {
		$status = 1;
		$res = $this->db->select('id,sys_group,parent_id,sort')
						->from($this->tb_name)
						->where('status', $status)
						->order_by('parent_id asc,sort asc')
						->get();
		return $res;
	}
	
	/**
	 * 管理组添加
	 * Enter description here ...
	 * @param unknown_type $data
	 */
	function insert($data) {
		$this->db->insert($this->tb_name, $data);
		$id = $this->db->insert_id();
		return $id;
	}
	
	/**
	 * 管理组更新
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
?>