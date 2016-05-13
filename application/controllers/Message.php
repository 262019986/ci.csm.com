<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Message extends CSM_Controller {
	
	public function index() {
		$this->load->view('header');
		$this->load->view('message/index');
		$this->load->view('footer');
	}
}