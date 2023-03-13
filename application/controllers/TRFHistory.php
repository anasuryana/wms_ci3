<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TRFHistory extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('TRF_mod');
    }

    public function index()
    {
        die("sorry");
    }

    function search(){
        header('Content-Type: application/json');
        $search = $this->input->get('search');
        $searchBy = $this->input->get('searchBy');
        $RS = [];
        $Like = [];
        switch($searchBy){
            case 'ic':
                $Like = ['MITM_ITMCD' => $search];break;
            case 'in':
                $Like = ['MITM_ITMD1' => $search];break;
        }
        $RS = $this->TRF_mod->selectHeaderLike($Like);
        die(json_encode(['data' => $RS]));
    }
    
    function getDetail(){
        header('Content-Type: application/json');
        $doc = $this->input->get('doc');
        $RS = $this->TRF_mod->selectDetailWhere(['TRFD_DOC' => $doc]);
        die(json_encode(['data' => $RS]));
    }
}
