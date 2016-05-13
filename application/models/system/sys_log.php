<?php
class Sys_log extends CI_Model {
	private $tb_name = 'log';
	
	function __construct(){
		parent::__construct();
	}
	
	/**
	 * 获取日志条数
	 */
	function log_count($data = array()) {
		$this->db->from($this->tb_name.' as ta');
		if (!empty($data)) {
			$this->db->join('admin as tb', 'ta.admin_id=tb.id');
			$this->db->where($data);
		}
		return $this->db->count_all_results();
	}
	
	/**
	 * 日志列表
	 * Enter description here ...
	 * @param unknown_type $start
	 * @param unknown_type $limit
	 */
	function log_list($start = 0, $limit = 20, $data = array()) {
		$res = $this->db->select('ta.id,ta.add_time,ta.add_ip,tb.sys_user,tc.sys_menu,tc.sys_url')
		                ->from($this->tb_name.' as ta')
		                ->join('admin as tb', 'ta.admin_id=tb.id')
		                ->join('menu as tc', 'ta.menu_id=tc.id')
		                ->order_by('ta.id desc')
		                ->limit($limit, $start);
		if (!empty($data)) {
			$res = $this->db->where($data);
		}
		$res = $this->db->get();
		return $res;
	}
	
	/**
	 * 日志添加
	 * Enter description here ...
	 * @param unknown_type $data
	 */
	function insert($data) {
		$this->db->insert($this->tb_name, $data);
		$id = $this->db->insert_id();
		return $id;
	}
}