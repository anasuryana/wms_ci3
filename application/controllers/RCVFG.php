<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RCVFG extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->library('code128');		
		$this->load->model('ITH_mod');					
	}
	public function index()
	{
		echo "sorry";
    }	
}