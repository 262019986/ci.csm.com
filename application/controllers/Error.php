<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Error extends CSM_Controller {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function index() {
		$this->load->view('header');
		$this->load->view('error');
		$this->load->view('footer');
	}
}
?>