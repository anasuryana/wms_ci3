<?php

class Menu extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('menu_mod');
        $this->load->model('useracc_mod');
        $this->load->model('usergroup_mod');
        $this->load->helper('url_helper');
        $this->load->helper('form');
        $this->load->library('session');
        $this->load->helper('security');
        $this->load->library('form_validation');
    }

    private function ishasChild(&$pmenu, $pidmenu) {
		$rsinside = [];
		foreach($pmenu as &$n) {
			if($n['MENU_PRNT'] == $pidmenu) {
				if($n['USED'] == '0') {
					$obj2 = [
						'id' => $n['MENU_ID']
						,'text' => $n['MENU_NAME']
						,'iconCls' => $n['MENU_ICON']
						,'state' => $n['MENU_STT']
						,'url' => $n['MENU_URL']
						,'desc' => $n['MENU_DSCRPTN']
					];
					$n['USED'] = '1';
					$rschild2 = $this->ishasChild($pmenu, $n['MENU_ID']);
					if(count($rschild2)>0) {
						$obj2['children'] = $rschild2;
					}
					$rsinside[] = $obj2;
				}
			}
		}
		unset($n);
		return $rsinside;
	}

    public function index()
    {        
        header('Content-Type: application/json');        
        $rs = $this->useracc_mod->menu_rules($this->session->userdata('gid'));                
        $rsfix = [];
		foreach($rs as &$r) {
			if ($r['USED'] == '0' ) {							
				$obj = [
					'id' => $r['MENU_ID']
					,'text' => $r['MENU_NAME']
					,'iconCls' => $r['MENU_ICON']
					,'state' => $r['MENU_STT']
				];
				$rschild = $this->ishasChild($rs, $r['MENU_ID']);
				if(count($rschild)>0) {
					$obj['children'] = $rschild;
				}
				$rsfix[] = $obj;
				$r['USED'] = '1';
			}
		}
		unset($r);
        die(json_encode($rsfix));        
    }

    function access(){
        $data['lusergroup'] = $this->usergroup_mod->selectAll();
        $this->load->view('UserMGR/vaccessmenu', $data);
    }

    function setAccess(){
        date_default_timezone_set('Asia/Jakarta');
		$currrtime 	= date('Y-m-d H:i:s');
        $grid = $this->input->get('inID');
        $mnid = $this->input->get('inNM');
        $usrlg = $this->session->userdata('nama');
        
        $sql2   ='';
        $cmnu   = explode(',', $mnid);
        for($i=0;$i<count($cmnu); $i++){
            $sql2 .="('".$grid."','".$cmnu[$i]."','".$usrlg."','".$currrtime."','".$currrtime."'),";
        }
        $sql2   = substr($sql2,0,strlen($sql2)-1);
        $this->useracc_mod->insert($grid,$sql2);
    }

    function getAll(){
        header('Content-Type: application/json');
        $laccess = $this->useracc_mod->selectAll();
        echo json_encode($laccess);
        
    }

    function getdefault(){
        header('Content-Type: application/json');
        $laccess = $this->session->userdata('gid')=='ADMIN' ? $amenu = $this->menu_mod->menu() : $amenu = $this->menu_mod->menu_setting();
        echo json_encode($laccess);
       
    }

    function setting(){
        header('Content-Type: application/json');                       
        $rs = $this->session->userdata('gid')=='ADMIN' ?  $this->menu_mod->menu() : $this->menu_mod->menu_setting();
        $rsfix = [];
		foreach($rs as &$r) {
			if ($r['USED'] == '0' ) {
				$obj = [
					'id' => $r['MENU_ID']
					,'text' => $r['MENU_NAME']
					,'iconCls' => $r['MENU_ICON']
					,'state' => $r['MENU_STT']
				];				
				$rschild = $this->ishasChild($rs, $r['MENU_ID']);
				if(count($rschild)>0) {
					$obj['children'] = $rschild;
				}
				$rsfix[] = $obj;
				$r['USED'] = '1';
			}
		}
		unset($r);
        die(json_encode($rsfix)); 
    }
}
