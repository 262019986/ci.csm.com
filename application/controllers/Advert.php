<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Advert extends CSM_Controller {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function index() {
		$this->load->view('header');
		$this->load->view('advert/advert');
		$this->load->view('footer');
	}
	
	public function advertList() {
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 20;
		$offset = ($page-1) * $rows;
		$this->load->model('system/Sys_advert');
		$res = $this->Sys_advert->advert_list($offset, $rows);
		$total = $this->Sys_advert->advert_count();
		$dicti_arr = self::dictionaryName();
		$json_str = '{"total":' . $total .',"rows":[';
		$spli = '';
		foreach ($res->result() as $items) {
			$type_name = self::reName($dicti_arr, $items->type_id);
			$json_str .= $spli . '{"id":' . $items->id . ',"title":"' . $items->title . '","img":"'.$items->img.'","note":"' . $items->note . '","sort":' . $items->sort . ',"status":' . $items->status . ',"type_name":"'.$type_name.'"}';
			$spli = ",";
		}
		$json_str .= ']}';
		echo $json_str;
	}
	
	/**
	 * 根据值查对象
	 * Enter description here ...
	 * @param unknown_type $arr
	 * @param unknown_type $val
	 */
	function reName($arr, $val) {
		foreach ($arr as $items) {
			if ($items['dicti_val'] == $val) {
				return $items['dicti_name'];
			}
		}
		return '';
	}
	
	/**
	 * 字典列表
	 * Enter description here ...
	 */
	function dictionaryName() {
		$this->load->model('system/Sys_dictionary');
		$obj = $this->Sys_dictionary->select_filed('dicti_name,dicti_val', array('parent_id'=>5,'status'=>1));
		return $obj->result_array();
	}
	
	public function advertAdd() {
		$res = array();
		$res['flag'] = false;
		$res['msg']  = '操作失败';
		$title = $this->post('tit');
		$type_id = intval($this->post('type_id'));
		$sort = intval($this->post('sort'));
		$note = $this->post('note');
		if (!empty($title) && !empty($type_id)) {
			$this->load->model('system/Sys_advert');
			$data = array(
					'title'=>$title,
					'note'=>$note,
					'status'=>1,
					'sort'=>$sort,
					'type_id' => $type_id,
					'into_time'=>time()
			);
			if ($this->Sys_advert->insert($data)) {
				$res['flag'] = true;
				$res['msg']  = '广告添加成功';
			}
		} else {
			$res['msg']  = '请输入标题与所属';
		}
		echo json_encode($res);
	}
}