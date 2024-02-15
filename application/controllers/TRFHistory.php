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

    public function search()
    {
        header('Content-Type: application/json');
        $columnMap = ['MITM_ITMCD', 'MITM_ITMD1', 'TRFH_DOC'];
        $search = $this->input->get('search');
        $searchBy = $this->input->get('searchBy');
        $RS = [];
        $Like = [$columnMap[$searchBy] => $search];
        $RS = $this->TRF_mod->selectHeaderLike($Like);
        die(json_encode(['data' => $RS]));
    }

    public function getDetail()
    {
        header('Content-Type: application/json');
        $doc = $this->input->get('doc');
        $RS = $this->TRF_mod->selectDetailWhere(['TRFD_DOC' => $doc]);
        die(json_encode(['data' => $RS]));
    }

    public function getDetailUnconform()
    {
        header('Content-Type: application/json');
        $doc = $this->input->get('doc');
        $header = $this->TRF_mod->selectHeaderWhere(['TRFH_DOC' => $doc]);
        $RS = $this->TRF_mod->selectDetailUnconformWhere(['TRFD_DOC' => $doc]);

        foreach($header as $r) {
            $created_at = $r['TRFH_CREATED_DT'];
        }
        
        die(json_encode(['data' => $RS, 'created_at' => $created_at]));
    }

    public function openDocuments()
    {
        header('Content-Type: application/json');
        $rs = $this->TRF_mod->selectOpenForID($this->session->userdata('nama'));
        $rsToFollow = $this->TRF_mod->selectOpenToFollowForID($this->session->userdata('nama'));
        $needToApproveQty = 0;
        $needToApproveDateTime = '-';
        $needToFollowQty = 0;
        $needToFollowDateTime = '-';
        foreach ($rs as $r) {
            $needToApproveQty++;
            $needToApproveDateTime = $r['LUPDTD'];
        }
        foreach ($rsToFollow as $r) {
            $needToFollowQty++;
            $needToFollowDateTime = $r['LUPDTD'];
        }
        $data = [
            'TO_APPROVE_QTY' => $needToApproveQty,
            'TO_APPROVE_DATETIME' => $needToApproveDateTime,
            'TO_FOLLOW_QTY' => $needToFollowQty,
            'TO_FOLLOW_DATETIME' => $needToFollowDateTime,
        ];
        die(json_encode(['data' => $data, 'rs' => $rs, 'rs2' => $rsToFollow]));
    }
}
