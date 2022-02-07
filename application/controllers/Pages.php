<?php

class Pages extends CI_Controller {    
    private $m_grupid='';
    // private $m_userid='';
    // private $m_userid='';
    // private $m_userid='';
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Usr_mod');
        $this->load->model('Usrlog_mod');
        $this->load->helper('url_helper');
        $this->load->helper('form');
        $this->load->helper('security');
        $this->load->library('session');        
        $this->load->library('form_validation');
        $this->load->library('RMCalculator');
    }

    public function index()
    {
		$this->IsAlreadyLogged();
        $this->load->view('halaman/vlogin');
    }
	
	public function gen_pw($pw){
		$tes = hash('sha256',$pw);
		echo $tes;
    }
    
    function getip(){
        echo $_SERVER['REMOTE_ADDR'];
    }

    function l(){
        #log user accessed
        date_default_timezone_set('Asia/Jakarta');
		$currrtime 	= date('Y-m-d H:i:s');
        $dloghis  = $this->Usrlog_mod->selectLastOne();   
        foreach ($dloghis as $dloghis) {
            $idlog = $dloghis['idnew'];
        }
        $username = $this->session->userdata('nama');
        $menu = $this->input->post('inmenu');
        $url = $this->input->post('inurl');
        $data_log = [
            'USRLOG_ID' => $idlog,
            'USRLOG_USR' => $username,            
            'USRLOG_TYP' => 'MENU',
            'USRLOG_MENU' => $menu,
            'USRLOG_URL' => $url,
            'USRLOG_TM' => $currrtime,
            'USRLOG_IP' => $_SERVER['REMOTE_ADDR']
        ];
        $ret = $this->Usrlog_mod->insert($data_log);
        $myar = [];
        if($ret>0){
            $myar[] = ['cd' => 1, 'msg' => 'OK'];            
        } else {
            $myar[] = ['cd' => 0, 'msg' => 'not ok'];
        }
        $Calc_lib = new RMCalculator();
        $res = $Calc_lib->sync_item_description1();
        $res_newitem = $Calc_lib->sync_new_item();
        $res_newsupplier = $Calc_lib->sync_supplier();
        $Calc_lib->sync_new_itemGrade();
        die('{"status":'.json_encode($myar).',"status_i1":'.$res
            .', "status_newitem":'.$res_newitem
            .', "supplier":'.$res_newsupplier.'}');
    }

    public function login()
    {
        
        date_default_timezone_set('Asia/Jakarta');
        $currrtime = date('Y-m-d H:i:s');
        $this->form_validation->set_rules('inputUserid', 'Username', 'trim|required|xss_clean');
        $this->form_validation->set_rules('inputPassword', 'inPass', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE)
        {            
            $data = array(
                'error_message' => 'wOOow'
            );                    
            redirect(base_url("Home"));
        } else {
            
            $username = $this->input->post('inputUserid');
            $this->m_userid=$username;
            $password = $this->input->post('inputPassword');
            $where = array(
                'MSTEMP_ID' => $username,
                'MSTEMP_PW' => hash('sha256',$password),
                'MSTEMP_ACTSTS' => true,
                'MSTEMP_STS' => true
            );
            echo json_encode($where)."<br>";
          
            $dlogses  = $this->Usr_mod->cek_login($where);
            $dloghis  = $this->Usrlog_mod->selectLastOne();        
            $fname    = '';
            
            $idlog    = '';
            foreach ($dlogses as $dlog) {
                $fname = $dlog['MSTEMP_FNM'];
                $this->m_grupid = $dlog['MSTEMP_GRP'];
            }
            foreach ($dloghis as $dloghis) {
                $idlog = $dloghis['idnew'];
            }
            $data_log = [
                'USRLOG_ID' => $idlog,
                'USRLOG_USR' => $username,
                'USRLOG_GRP' => $this->m_grupid,
                'USRLOG_TYP' => 'LGIN',
                'USRLOG_TM' => $currrtime,
                'USRLOG_IP' => $_SERVER['REMOTE_ADDR']
            ];

            
            if(strlen($fname) > 0) {
                $this->Usrlog_mod->insert($data_log);
                $data_session = array(
                'nama' => $username,
                'status' => "login",
                'sfname' => $fname,
                'gid' => $this->m_grupid
                );
                
                $this->session->set_userdata($data_session);
                redirect(base_url("home"));
            } else {
                
                $data = array(
                    'error_message' => 'Invalid Username or Password'
                );
                //$this->load->view('halaman/vface', $data);
                redirect(base_url("home"));
            }
        }
    }
	
	function IsAlreadyLogged()
    {
        if ($this->session->userdata('status'))
        {
            redirect('home');
        }
    }

    public  function logout()
    {   
        date_default_timezone_set('Asia/Jakarta');
        $currrtime = date('Y-m-d H:i:s');
        $dloghis    = $this->Usrlog_mod->selectLastOne();
        $idlog      = '';
        foreach ($dloghis as $dloghis) {
            $idlog = $dloghis['idnew'];
        }
        $data_log = array(
            'USRLOG_ID' => $idlog,
            'USRLOG_USR' => $this->session->userdata('nama'),
            'USRLOG_GRP' => $this->session->userdata('gid'),
            'USRLOG_TYP' => 'LGOUT',
            'USRLOG_TM' => $currrtime,
            'USRLOG_IP' => $_SERVER['REMOTE_ADDR']
        );
        $this->Usrlog_mod->insert($data_log);
        $this->session->unset_userdata('status');
        redirect(base_url(""));
	}	
	
}