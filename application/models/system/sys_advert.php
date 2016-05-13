<?php
class Sys_advert extends CI_Model {
	private $tb_name = 'advert';
	
	function __construct(){
		parent::__construct();
	}
	
	/**
	 * 获取广告数
	 */
	function advert_count() {
		$this->db->from($this->tb_name);
		return $this->db->count_all_results();
	}
	
	/**
	 * 广告列表
	 * Enter description here ...
	 * @param unknown_type $type
	 */
	function advert_list($start = 0, $limit = 20) {
		$res = $this->db->select('id,title,img,note,status,sort,type_id')
						->from($this->tb_name)
		                ->order_by('type_id asc,sort asc')
						->limit($limit, $start)
				        ->get();
		return $res;
	}
	
	/**
	 * 广告添加
	 * Enter description here ...
	 * @param unknown_type $data
	 */
	function insert($data) {
		$this->db->insert($this->tb_name, $data);
		$id = $this->db->insert_id();
		return $id;
	}
	
	/**
	 * 广告更新
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