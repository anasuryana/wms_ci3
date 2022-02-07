<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Comcat extends CI_Controller {
	public function __construct()
	{
		parent::__construct();		
		$this->load->helper('url');
		$this->load->library('session');		
	}
	public function index()
	{
		echo "sorry";
	}

	public function create(){		
		$this->load->view('wms/vcomcat');
    }	
       
}
