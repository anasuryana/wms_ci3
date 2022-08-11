<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SOHistory extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
        $this->load->library('session');        
        $this->load->model('SO_mod');
	}

	public function index()
	{
		echo "sorry";
    }
    
    function so_list() {
        header('Content-Type: application/json');
        $searchby = $this->input->get('searchby');
        $search = $this->input->get('search');        
        switch ($searchby) {
            case 'so':
                $condition = ['SO_NO' => $search];break;
            case 'ic':
                $condition = ['SO_ITEMCD' => $search];break;
            case 'in':
                $condition = ['MITM_ITMD1' => $search];break;
            default:
                $condition = ['SO_NO' => $search];
        }
        $rs = $this->SO_mod->select_soexim_h_like($condition);
        die(json_encode(['data' => $rs]));
    }

    function soplot_vs_dlv() {
        header('Content-Type: application/json');
        $doc = $this->input->post('doc');
        $rsso = $this->SO_mod->select_soexim_master(['SO_NO' => $doc]);
        $rsplot = $this->SO_mod->select_soeximplot_vs_dlv(['DLVSO_CPONO' => $doc]);
        $rstoret = [];
        foreach($rsso as &$r) {
            $rstoret[] = ['ITMCD' => $r['SO_ITEMCD'], 'ITMD1' => $r['ITMD1'], 'DOC' => '', 'DATE' => '', 'DLVQT' => '', 'BALANCE' => $r['ORDQT'], 'STATUS' => 'OK'];            
            foreach($rsplot as $k) {
                if($r['SO_ITEMCD']===$k['DLVSO_ITMCD']) {
                    $r['ORDQT']-=$k['PLOTQTY'];
                    $rstoret[] = ['ITMCD' => '', 'ITMD1' => '', 'DOC' => $k['DLVSO_DLVID'], 'DATE' => $k['TGLPEN'], 'DLVQT' => $k['PLOTQTY'], 'BALANCE' => $r['ORDQT'] , 'STATUS' => $k['TGLPEN'] ? 'OK' : '?' ];
                }
            }
        }
        unset($r);
        die(json_encode(['data' => $rstoret, 'rsso' => $rsso, 'rsplot' => $rsplot]));
    }


}