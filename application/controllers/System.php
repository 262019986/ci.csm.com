<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class System extends CSM_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->output->enable_profiler(FALSE);
	}
	
	public function index() {
		
	}

	
	/***********************************************************系统菜单*************************************************************/
	
	
	/**
	 * 系统菜单默认页
	 * Enter description here ...
	 */
	public function sysMenuIndex() {
		$this->load->view('header');
		$this->load->view('system/sys_menu');
		$this->load->view('footer');
	}
	
	/**
	 * 系统菜单列表
	 * Enter description here ...
	 */
	public function sysMenuList() {
		$this->load->model('system/Sys_menu');
		$res = $this->Sys_menu->menu_list(1);
		$total = $this->db->affected_rows();
		$json_str = '{"total":' . $total .',"rows":[';
		$spli = '';
		foreach ($res->result() as $items) {
			$json_str .= $spli . '{"id":' . $items->id . ',"sys_menu":"' . $items->sys_menu . '","sys_url":"' . $items->sys_url . '","order":' . $items->order . ',"type":' . $items->type . ',"_parentId":' . $items->parent_id . '}';
			$spli = ",";
		}
		$json_str .= ']}';
		echo $json_str;
	}
	
	/**
	 * 添加系统菜单
	 * Enter description here ...
	 */
	public function sysMenuAdd() {
		$res = array();
		$res['flag']   = false;
		$res['msg'] = '';
		$sys_menu  = $this->post('sys_menu');
		if (!empty($sys_menu)) {
			$sys_url   = $this->post('sys_url');
			$order     = intval($this->post('order'));
			$type      = intval($this->post('type'));
			$parent_id = intval($this->post('parent_id'));
			$data = array(
						'sys_menu'  => $sys_menu,
						'sys_url'   => $sys_url,
						'order'     => $order,
						'type'	    => $type,
						'parent_id' => $parent_id
					);
			$this->load->model('system/Sys_menu');
			$res['id']       = $this->Sys_menu->insert($data);
			$res['sys_menu'] = $sys_menu;
			$res['flag']     = true;
			$res['msg']      = $sys_menu . ' - 添加成功';
			//删除菜单缓存
			self::sysMenuCacheDel();
		} else {
			$res['msg'] = '请输入长度0～10的菜单名称';
		}
		echo json_encode($res);
	}
	
	/**
	 * 删除系统菜单缓存
	 */
	function sysMenuCacheDel() {
		$this->load->library('Mycache');
		return $this->mycache->delete_cache('sys_menu_list');
	}
	
	/**
	 * 更新系统菜单
	 * Enter description here ...
	 */
	public function sysMenuUp() {
		$res = array();
		$res['flag'] = false;
		$res['msg']  = '';
		$id        = intval($this->post('id'));
		$sys_menu  = $this->post('sys_menu');
		$sys_url   = $this->post('sys_url');
		$order     = intval($this->post('order'));
		$type      = intval($this->post('type'));
		if ($type != 0) {
			$type = 1;	//0：代表菜单 1：代表功能
		}
		if (!empty($sys_menu)) {
			$data = array(
					'sys_menu'  => $sys_menu,
					'sys_url'   => $sys_url,
					'order'     => $order,
					'type'	    => $type
				);
			$where = array('id' => $id);
			$this->load->model('system/Sys_menu');
			if ($this->Sys_menu->update($data, $where)) {
				$res['flag'] = true;
				$res['msg']  = $sys_menu . ' - 更新成功';
				//删除菜单缓存
				self::sysMenuCacheDel();
			} else {
				$res['msg'] = '操作失败';
			}
		} else {
			$res['msg'] = '请输入系统菜单';
		}
		echo json_encode($res);
	}
	
	/**
	 * 删除系统菜单
	 * Enter description here ...
	 */
	public function sysMenuDel() {
		$res = array();
		$res['flag'] = false;
		$res['msg']  = '操作失败';
		$status = 0;
		$id = intval($this->post('id'));
		if ($id) {
			$data  = array('status' => $status);
			$where = array('id' => $id);
			$this->load->model('system/Sys_menu');
			if($this->Sys_menu->update($data, $where)) {
				$res['flag'] = true;
				$res['msg']  = '菜单成功删除';
				//删除菜单缓存
				self::sysMenuCacheDel();
			}
		}
		echo json_encode($res);
	}
	
	/**
	 * 菜单类别
	 * Enter description here ...
	 */
	public function sysMenuType() {
		$json_str ='[
			{"id":"0","text":"菜单"},
			{"id":"1","text":"功能"}
		]';
		echo $json_str;
	}
	
	
	/***********************************************************管理员*************************************************************/
	
	
	/**
	 * 管理员默认页
	 */
	public function sysAdminIndex() {
		$this->load->view('header');
		$this->load->view('system/sys_admin');
		$this->load->view('footer');
	}
	
	/**
	 * 管理员列表
	 */
	public function sysAdminList() {
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 20;
		$offset = ($page-1) * $rows;
		$this->load->model('system/Sys_admin');
		$res = $this->Sys_admin->admin_list($offset, $rows);
		$total = $this->Sys_admin->admin_count();
		$json_str = '{"total":' . $total .',"rows":[';
		$spli = '';
		foreach ($res->result() as $items) {
			$json_str .= $spli . '{"id":' . $items->id . ',"sys_user":"' . $items->sys_user . '","sys_pwd":"'.$items->sys_pwd.'","sex":' . $items->sex . ',"telphone":"' . $items->telphone . '","group_id":' . $items->group_id . ',"sys_group":"'.(empty($items->sys_group)?'无':$items->sys_group).'","power":"<a href=\"/index.php/System/sysPowerIndex/0/'.$items->id.'\">查看</a>"}';
			$spli = ",";
		}
		$json_str .= ']}';
		echo $json_str;
	}
	
	/**
	 * 管理员性别列表
	 */
	public function sysAdminSex() {
		$json_str ='[
						{"sex":"0","sex_name":"女"},
						{"sex":"1","sex_name":"男"}
					]';
		echo $json_str;
	}
	
	/**
	 * 更新管理员
	 */
	public function sysAdminUp() {
		$res = array();
		$res['flag'] = false;
		$res['msg']  = '操作失败';
		$id = intval($this->post('id'));
		$sys_user = $this->post('sys_user');
		$sys_pwd = $this->post('sys_pwd') != null ? md5($this->post('sys_pwd')) : null;
		$sex = intval($this->post('sex'));
		$telphone = $this->post('telphone');
		$group_id = intval($this->post('group_id')); 
		if (!empty($sys_user)) {
			//判断管理员是否存在
			$num = 0;
			$this->load->model('system/Sys_admin');
			$obj = $this->Sys_admin->select_filed('count(*) as cout', array('sys_user'=>$sys_user, 'id !='=>$id));
			foreach ($obj->result() as $items) {
				$num = $items->cout;
			}
			if ($num <= 0) {
				$data = array(
							'sys_user'=>$sys_user,
							'sex'=>$sex,
							'telphone'=>$telphone,
							'group_id'=>$group_id
						);
				if (isset($sys_pwd)) {
					$data['sys_pwd'] = $sys_pwd;
				}
				$where = array(
							'id'=>$id
						);
				if ($this->Sys_admin->update($data, $where)) {
					$res['flag'] = true;
					$res['msg']  = '管理员更新成功';
				}
			} else {
				$res['msg']  = $sys_user . ' 管理员已存在';
			}
		} else {
			$res['msg']  = '请输入管理员';
		}
		echo json_encode($res);
	}
	
	/**
	 * 添加管理员
	 */
	public function sysAdminAdd() {
		$res = array();
		$res['flag'] = false;
		$res['msg']  = '操作失败';
		$sys_user = $this->post('sys_user');
		$sys_pwd = $this->post('sys_pwd') != null ? md5($this->post('sys_pwd')) : null;
		$sex = intval($this->post('sex'));
		$telphone = $this->post('telphone');
		$group_id = intval($this->post('group_id'));
		if (!empty($sys_pwd) && !empty($sys_pwd)) {
			//判断管理员是否存在
			$num = 0;
			$this->load->model('system/Sys_admin');
			$obj = $this->Sys_admin->select_filed('count(*) as cout', array('sys_user'=>$sys_user));
			foreach ($obj->result() as $items) {
				$num = $items->cout;
			}
			if ($num <= 0) {
				$data = array(
						'sys_user'=>$sys_user,
						'sex'=>$sex,
						'telphone'=>$telphone,
						'group_id'=>$group_id,
						'sys_pwd' => $sys_pwd
				);
				if ($this->Sys_admin->insert($data)) {
					$res['flag'] = true;
					$res['msg']  = '管理员添加成功';
				}
			} else {
				$res['msg']  = $sys_user . ' 管理员已存在';
			}
		} else {
			$res['msg']  = '请输入管理员与密码';
		}
		echo json_encode($res);
	}
	
	/**
	 * 删除管理员
	 */
	public function sysAdminDel() {
		$res = array();
		$res['flag'] = false;
		$res['msg']  = '操作失败';
		$id = intval($this->post('id'));
		$data = array(
				'status'=>0
		);
		$where = array(
				'id'=>$id
		);
		$this->load->model('system/Sys_admin');
		if ($this->Sys_admin->update($data, $where)) {
			$res['flag'] = true;
			$res['msg']  = '管理员删除成功';
		}
		echo json_encode($res);
	}
	
	
	/***********************************************************管理组*************************************************************/
	
	
	/**
	 * 管理组默认页
	 */
	public function sysGroupIndex() {
		$this->load->view('header');
		$this->load->view('system/sys_group');
		$this->load->view('footer');
	}
	
	/**
	 * 管理组列表
	 */
	public function sysGroupList() {
		$this->load->model('system/Sys_group');
		$res = $this->Sys_group->group_list();
		$total = $this->db->affected_rows();
		$json_str = '{"total":' . $total .',"rows":[';
		$spli = '';
		foreach ($res->result() as $items) {
			$json_str .= $spli . '{"id":' . $items->id . ',"sys_group":"' . $items->sys_group . '","sort":' . $items->sort . ',"info":"<a href=\"/index.php/System/sysPowerIndex/1/'.$items->id.'\">查看</a>","_parentId":' . $items->parent_id . '}';
			$spli = ",";
		}
		$json_str .= ']}';
		echo $json_str;
	}
	
	/**
	 * 添加管理组
	 * Enter description here ...
	 */
	public function sysGroupAdd() {
		$res = array();
		$res['flag']   = false;
		$res['msg'] = '';
		$sys_group  = $this->post('sys_group');
		if (!empty($sys_group)) {
			$sort      = intval($this->post('sort'));
			$parent_id = intval($this->post('parent_id'));
			$data = array(
					'sys_group' => $sys_group,
					'sort'      => $sort,
					'parent_id' => $parent_id
			);
			$this->load->model('system/Sys_group');
			$res['id']        = $this->Sys_group->insert($data);
			$res['sys_group'] = $sys_group;
			$res['flag']      = true;
			$res['msg']       = $sys_group . ' - 添加成功';
		} else {
			$res['msg'] = '请输入长度0～10的管理单位名称';
		}
		echo json_encode($res);
	}
	
	/**
	 * 更新管理组
	 * Enter description here ...
	 */
	public function sysGroupUp() {
		$res = array();
		$res['flag'] = false;
		$res['msg']  = '';
		$id        = intval($this->post('id'));
		$sys_group = $this->post('sys_group');
		$sort      = intval($this->post('sort'));
		if (!empty($sys_group)) {
			$data = array(
					'sys_group' => $sys_group,
					'sort'      => $sort
			);
			$where = array('id' => $id);
			$this->load->model('system/Sys_group');
			if ($this->Sys_group->update($data, $where)) {
				$res['flag'] = true;
				$res['msg']  = $sys_group . ' - 更新成功';
			} else {
				$res['msg'] = '操作失败';
			}
		} else {
			$res['msg'] = '请输入管理单位名称';
		}
		echo json_encode($res);
	}
	
	/**
	 * 删除管理组
	 * Enter description here ...
	 */
	public function sysGroupDel() {
		$res = array();
		$res['flag'] = false;
		$res['msg']  = '操作失败';
		$status = 0;
		$id = intval($this->post('id'));
		if ($id) {
			$data  = array('status' => $status);
			$where = array('id' => $id);
			$this->load->model('system/Sys_group');
			if($this->Sys_group->update($data, $where)) {
				$res['flag'] = true;
				$res['msg']  = '管理单位成功删除';
			}
		}
		echo json_encode($res);
	}
	
	/**
	 * 管理组下拉列表
	 */
	public function sysGroupCombotree() {
		$this->load->model('system/Sys_group');
		$res = $this->Sys_group->group_list();
		$json_str ='[{"id":0,"text":"无"}';
		$json_str .= self::combotreeJson($res->result_array(), 0, ",");
		$json_str .= ']';
		echo $json_str;
	}
	
	/**
	 * 下拉列表json
	 * @param unknown_type $arr
	 * @param unknown_type $parent_id
	 * @param unknown_type $spli
	 */
	function combotreeJson($arr, $parent_id, $spli = "") {
		$json_str = "";
		foreach ($arr as $items) {
			if ($items['parent_id'] == $parent_id) {
				$json_str .= $spli . '{"id":'.$items['id'].',"text":"'.$items['sys_group'].'","children":[';
				$json_str .= self::combotreeJson($arr, $items['id']);
				$json_str .= ']}';
				$spli = ",";
			} elseif ($items['parent_id'] > $parent_id) {
				break;
			}
		}
		return $json_str;
	}
	
	
	/***********************************************************权限*************************************************************/
	
	
	/**
	 * 权限默认页
	 */
	public function sysPowerIndex() {
		$type = intval($this->segment(3, 0));
		$id   = intval($this->segment(4, 0));
		$data['type']  = $type;
		$data['id']    = $id;
		$data['power'] = "";
		$res = "";
		$arr = array("id" => $id);
		if ($type == 0) {
			//管理员权限
			$this->load->model('System/Sys_admin');
			$res = $this->Sys_admin->select_filed('power', $arr);
		} else {
			//管理组权限
			$this->load->model('System/Sys_group');
			$res = $this->Sys_group->select_filed('power', $arr);
		}
		foreach ($res->result() as $items) {
			$data['power'] = $items->power;
		}
		$this->load->view('header');
		$this->load->view('system/sys_power', $data);
		$this->load->view('footer');
	}
	
	/**
	 * 设置权限
	 */
	public function sysPowerSet() {
		$res = array();
		$res['flag'] = false;
		$res['msg']  = '';
		$type = intval($this->post('type'));
		$id   = intval($this->post('id'));
		$power = $this->post('power');
		$data = array(
					'power' => $power
				);
		$where = array(
					'id' => $id
				);
		if ($type == 0) {
			//更新管理员
			$this->load->model('System/Sys_admin');
			$res['flag'] = $this->Sys_admin->update($data, $where);
		} else {
			//更新管理组
			$this->load->model('System/Sys_group');
			$res['flag'] = $this->Sys_group->update($data, $where);
		}
		if ($res['flag']) {
			$res['msg']  = '授权成功';
		} else {
			$res['msg']  = '授权失败';
		}
		echo json_encode($res);
 	}
 	
 	
 	/***********************************************************系统日志*************************************************************/
 	
 	
 	/**
 	 * 系统日志默认页
 	 * Enter description here ...
 	 */
 	public function sysLogIndex() {
 		$this->load->view('header');
		$this->load->view('system/sys_log');
		$this->load->view('footer');
 	}
 	
 	/**
 	 * 日志列表
 	 */
 	public function sysLogList() {
 		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$rows = isset($_GET['rows']) ? intval($_GET['rows']) : 20;
		$offset = ($page-1) * $rows;
		$data = array();
		$stime = $this->post('stime');
		if (!empty($stime)) {
			$data['ta.add_time>='] = strtotime($stime);
		}
		$etime = $this->post('etime');
		if (!empty($etime)) {
			$data['ta.add_time<='] = strtotime($etime);
		}
		$search = $this->post('search');
		if (!empty($search)) {
			$data['tb.sys_user like'] = '%'.$search.'%';
		}
 		$this->load->model('system/Sys_log');
 		$res = $this->Sys_log->log_list($offset, $rows, $data);
 		$total = $this->Sys_log->log_count($data);
		$json_str = '{"total":' . $total .',"rows":[';
		$spli = '';
		foreach ($res->result() as $items) {
			$items->add_time = date('Y-m-d H:i:s', $items->add_time);
			$json_str .= $spli . '{"id":' . $items->id . ',"sys_user":"' . $items->sys_user . '","sys_menu":"'.$items->sys_menu.'","sys_url":"' . $items->sys_url . '","add_time":"' . $items->add_time . '","add_ip":"' . $items->add_ip .'"}';
			$spli = ",";
		}
		$json_str .= ']}';
		echo $json_str;
 	}
 	
 	
	/***********************************************************字典*************************************************************/
	
	
	/**
	 * 字典默认页
	 */
	public function sysDictionaryIndex() {
		$this->load->view('header');
		$this->load->view('system/sys_dictionary');
		$this->load->view('footer');
	}
	
	/**
	 * 字典列表
	 * Enter description here ...
	 */
	public function sysDictionaryList() {
		$this->load->model('system/Sys_dictionary');
		$res = $this->Sys_dictionary->dictionary_list();
		$total = $this->db->affected_rows();
		$json_str = '{"total":' . $total .',"rows":[';
		$spli = '';
		foreach ($res->result() as $items) {
			$json_str .= $spli . '{"id":' . $items->id . ',"dicti_name":"' . $items->dicti_name . '","dicti_val":' . $items->dicti_val . ',"status":'.$items->status.',"_parentId":' . $items->parent_id . '}';
			$spli = ",";
		}
		$json_str .= ']}';
		echo $json_str;
	}
	
	/**
	 * 字典添加
	 * Enter description here ...
	 */
	public function sysDictionaryAdd() {
		$res = array();
		$res['flag']   = false;
		$res['msg'] = '';
		$dicti_name  = $this->post('dicti_name');
		if (!empty($dicti_name)) {
			$dicti_val = intval($this->post('dicti_val'));
			$status    = intval($this->post('status'));
			$parent_id = intval($this->post('parent_id'));
			$data = array(
					'dicti_name' => $dicti_name,
					'dicti_val'  => $dicti_val,
					'parent_id'  => $parent_id,
					'status'     => $status
			);
			$this->load->model('system/Sys_dictionary');
			$res['id']         = $this->Sys_dictionary->insert($data);
			$res['dicti_name'] = $dicti_name;
			$res['flag']       = true;
			$res['msg']        = $dicti_name . ' - 添加成功';
		} else {
			$res['msg'] = '请输入长度1～15的字典名称';
		}
		echo json_encode($res);
	}
	
	/**
	 * 字典状态
	 * Enter description here ...
	 */
	public function sysDictionaryStatus() {
		$json_str ='[
			{"id":"0","text":"关闭"},
			{"id":"1","text":"开启"}
		]';
		echo $json_str;
	}
	
	/**
	 * 更新字典
	 * Enter description here ...
	 */
	public function sysDictionaryUp() {
		$res = array();
		$res['flag'] = false;
		$res['msg']  = '';
		$id          = intval($this->post('id'));
		$dicti_name  = $this->post('dicti_name');
		$dicti_val   = intval($this->post('dicti_val'));
		$status      = intval($this->post('status'));
		if (!empty($dicti_name)) {
			$data = array(
					'dicti_name' => $dicti_name,
					'dicti_val'  => $dicti_val,
					'status'     => $status
				);
			$where = array('id' => $id);
			$this->load->model('system/Sys_dictionary');
			if ($this->Sys_dictionary->update($data, $where)) {
				$res['flag'] = true;
				$res['msg']  = $dicti_name . ' - 更新成功';
			} else {
				$res['msg'] = '操作失败';
			}
		} else {
			$res['msg'] = '请输入字典名称';
		}
		echo json_encode($res);
	}
	
	/**
	 * 删除字典
	 * Enter description here ...
	 */
	public function sysDictionaryDel() {
		$res = array();
		$res['flag'] = false;
		$res['msg']  = '操作失败';
		$status = 0;
		$id = intval($this->post('id'));
		if ($id) {
			$where = array('id' => $id);
			$this->load->model('system/Sys_dictionary');
			if($this->Sys_dictionary->delete($where)) {
				$res['flag'] = true;
				$res['msg']  = '字典成功删除';
			}
		}
		echo json_encode($res);
	}
	
	/**
	 * 根据类别查询字典列表
	 * Enter description here ...
	 */
	public function sysDictionarySel() {
		$parent_id = intval($this->segment(3));
		$this->load->model('system/Sys_dictionary');
		$arr = array("parent_id" => $parent_id);
		$obj = $this->Sys_dictionary->select_filed('dicti_name,dicti_val', $arr);
		$spli = '';
		$json_str = "[{\"id\":0,\"text\":\"请选择\",\"selected\":true},";
		foreach ($obj->result() as $items) {
			$json_str .= $spli . "{\"id\":".$items->dicti_val.",\"text\":\"".$items->dicti_name."\"}";
			$spli = ',';
		}
		$json_str .= "]";
		echo $json_str;
	}
}
