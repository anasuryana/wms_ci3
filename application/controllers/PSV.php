<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PSV extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('download');
        $this->load->library('session');
        $this->load->model('PSV_mod');
    }

    public function index()
    {
        echo "sorry";
    }

    public function store()
    {
        header('Content-Type: application/json');
        $result = $this->PSV_mod->insert_stock();
        $response = $result ? ['cd' => '1', 'msg' => 'Stored successfully'] : ['cd' => '0', 'msg' => 'Failed to store'];
        die(json_encode(['data' => $response]));
    }

    function reindex()
    {
        ini_set('max_execution_time', '-1');
        header('Content-Type: application/json');
        $this->PSV_mod->reindex();
        $response = ['cd' => '1', 'msg' => 'done, reindex'];
        die(json_encode(['data' => $response]));
    }

    function truncate()
    {
        ini_set('max_execution_time', '-1');
        header('Content-Type: application/json');
        $this->PSV_mod->truncate_stock();
        $response = ['cd' => '1', 'msg' => 'done, truncate'];
        die(json_encode(['data' => $response]));
    }  
    
    function delete_stock()
    {
        ini_set('max_execution_time', '-1');
        header('Content-Type: application/json');
        $this->PSV_mod->delete_stock();
        $response = ['cd' => '1', 'msg' => 'done, delete'];
        die(json_encode(['data' => $response]));        
    }
    
    function data()
    {
        header('Content-Type: application/json');
        $rs = $this->PSV_mod->selectAll();
        die(json_encode(['data' => $rs]));
    }
}