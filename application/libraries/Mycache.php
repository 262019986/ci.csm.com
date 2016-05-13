<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mycache {
	protected $CI;
	
	public function __construct() {
		$this->CI = &get_instance();
		$this->CI->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
	}
	
	/**
	 * 获取缓存
	 * Enter description here ...
	 * @param unknown_type $file
	 */
	public function get_cache($file) {
		$my_ci = $this->CI;
		$cache_file = $my_ci->cache->get($file);
		return $cache_file;
	}
	
	/**
	 * 设置缓存
	 * Enter description here ...
	 * @param unknown_type $file
	 * @param unknown_type $result
	 * @param unknown_type $time
	 */
	public function save_cache($file, $result, $time = 60) {
		$my_ci = $this->CI;
		return $my_ci->cache->save($file, $result, $time);
	}
	
	/**
	 * 删除缓存
	 * Enter description here ...
	 * @param unknown_type $file
	 */
	public function delete_cache($file) {
		$my_ci = $this->CI;
		return $my_ci->cache->delete($file);
	}
	
	/**
	 * 清除所有缓存
	 * Enter description here ...
	 */
	public function clean_cache() {
		$my_ci = $this->CI;
		return $my_ci->cache->clean();
	}
}
?>