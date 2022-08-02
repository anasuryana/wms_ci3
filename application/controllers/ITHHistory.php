<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ITHHistory extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('ITH_mod');		
	}

	public function index()
	{
		echo "sorry";
	}

    public function getLocations() {
        header('Content-Type: application/json');
        $rs = $this->ITH_mod->select_parent_locations();
        die(json_encode(['data' => $rs]));
    }	

}