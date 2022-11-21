<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PSV extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('download');
        $this->load->library('session');
        $this->load->model('PSV_mod');
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        echo "sorry";
    }

    public function store()
    {
        header('Content-Type: application/json');
        log_message('error', $_SERVER['REMOTE_ADDR'] . ',PSV,start Store');
        $result = $this->PSV_mod->insert_stock();
        log_message('error', $_SERVER['REMOTE_ADDR'] . ',PSV,finish Store');
        $response = $result ? ['cd' => '1', 'msg' => 'Stored successfully'] : ['cd' => '0', 'msg' => 'Failed to store'];
        die(json_encode(['data' => $response]));
    }

    function reindex()
    {
        ini_set('max_execution_time', '-1');
        log_message('error', $_SERVER['REMOTE_ADDR'] . ',PSV,start Reindex');
        header('Content-Type: application/json');
        $this->PSV_mod->reindex();
        log_message('error', $_SERVER['REMOTE_ADDR'] . ',PSV,finish Reindex');
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
        log_message('error', $_SERVER['REMOTE_ADDR'] . ',PSV,start delete');
        $this->PSV_mod->delete_stock();
        log_message('error', $_SERVER['REMOTE_ADDR'] . ',PSV,finish delete');
        $response = ['cd' => '1', 'msg' => 'done, delete'];
        die(json_encode(['data' => $response]));
    }

    function delete_stock_period()
    {
        header('Content-Type: application/json');
        $rs = $this->PSV_mod->select_all_period();
        $period_ = NULL;
        $status = [];
        if (count($rs) > 1) {
            foreach ($rs as $r) {
                $period_ = $r['AS_OF_DATE_TIME'];
                break;
            }
            log_message('error', $_SERVER['REMOTE_ADDR'] . ',PSV,start delete @ period ' . $period_);
            $this->PSV_mod->delete_stock_where_period($period_);
            log_message('error', $_SERVER['REMOTE_ADDR'] . ',PSV,finish delete @ period ' . $period_);
            $status = ['cd' => 1, 'msg' => 'OK'];
        } else {
            log_message('error', $_SERVER['REMOTE_ADDR'] . ',PSV,no need to delete @ period ');
            $status = ['cd' => 1, 'msg' => 'No need to be deleted'];
        }
        die(json_encode(["status" => $status, 'data' => $rs, 'tobe_deleted_period' => $period_]));
    }

    function data()
    {
        header('Content-Type: application/json');
        $rs = $this->PSV_mod->selectAll();
        die(json_encode(['data' => $rs]));
    }
}
