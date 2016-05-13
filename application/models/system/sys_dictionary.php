<?php
class Sys_dictionary extends CI_Model {
	private $tb_name = 'dictionary';
	
	function __construct(){
		parent::__construct();
	}
	
	/**
	 * 列表
	 * Enter description here ...
	 * @param unknown_type $type
	 */
	function dictionary_list() {
		$status = 1;
		$res = $this->db->select('id,dicti_name,dicti_val,parent_id,status')
						->from($this->tb_name)
						->order_by('parent_id asc,id asc')
						->get();
		return $res;
	}
	
	/**
	 * 添加
	 * Enter description here ...
	 * @param unknown_type $data
	 */
	function insert($data) {
		$this->db->insert($this->tb_name, $data);
		$id = $this->db->insert_id();
		return $id;
	}
	
	/**
	 * 更新
	 * Enter description here ...
	 * @param unknown_type $data
	 * @param unknown_type $where
	 */
	function update($data, $where) {
		return $this->db->update($this->tb_name, $data, $where);
	}
	
	/**
	 * 删除
	 * Enter description here ...
	 * @param unknown_type $where
	 */
	function delete($where) {
		return $this->db->delete($this->tb_name, $where);
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