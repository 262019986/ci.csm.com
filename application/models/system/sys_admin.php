<?php
class Sys_admin extends CI_Model {
	private $tb_name = 'admin';
	
	function __construct(){
		parent::__construct();
	}
	
	/**
	 * 获取管理员人数
	 */
	function admin_count() {
		$status = 1;
		$this->db->where('status', $status)
		         ->from($this->tb_name);
		return $this->db->count_all_results();
	}
	
	/**
	 * 管理员列表
	 * Enter description here ...
	 * @param unknown_type $type
	 */
	function admin_list($start = 0, $limit = 20) {
		$status = 1;
		$res = $this->db->select('admin.id,sys_user,sys_pwd,sex,telphone,group_id,sys_group')
						->from($this->tb_name)
						->join('group', $this->tb_name.'.group_id=group.id', 'left')
						->where('admin.status', $status)
						->where('super', 0)
		                ->order_by('admin.id desc')
						->limit($limit, $start)
				        ->get();
		return $res;
	}
	
	/**
	 * 管理员添加
	 * Enter description here ...
	 * @param unknown_type $data
	 */
	function insert($data) {
		$this->db->insert($this->tb_name, $data);
		$id = $this->db->insert_id();
		return $id;
	}
	
	/**
	 * 管理员更新
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