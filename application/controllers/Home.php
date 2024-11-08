<?php

class Home extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url_helper');
        $this->load->library('session');
        $this->load->model('MSTLOCG_mod');
        $this->load->model('PWPOL_mod');
        $this->load->model('Usr_mod');
        if ($this->session->userdata('status') != "login") {
            redirect(base_url(""));
        }
    }

    public function index()
    {
        $data['judul'] = 'Home';
        $data['sapaDia'] = $this->session->userdata('sfname');
        $data['sapaDiaID'] = $this->session->userdata('nama');
        $data['wms_usergroup_id'] = $this->session->userdata('gid');
        $RSWHRM = $this->MSTLOCG_mod->selectall();
        $StrWHRM = '';
        foreach ($RSWHRM as $r) {
            $StrWHRM .= '<option value="' . $r['MSTLOCG_ID'] . '">' . $r['MSTLOCG_NM'] . '</option>';
        }
        $data['lwh'] = $StrWHRM;
        $RSWHFG = $this->MSTLOCG_mod->selectall_by_dedict('FGWH');
        $StrWHFG = '';
        foreach ($RSWHFG as $r) {
            $StrWHFG .= '<option value="' . $r['MSTLOCG_ID'] . '">' . $r['MSTLOCG_NM'] . '</option>';
        }
        $data['lwhfg'] = $StrWHFG;

        $rsPWPolicy = $this->PWPOL_mod->select();
        foreach($rsPWPolicy as $r){
            $data['PASSWORD_MININUM_LENGTH'] = $r['PWPOL_LENGTH'];
        }

        $data['EXPIRE_IN'] = $this->passwordAges($data['sapaDiaID'])['expireIn'];

        $this->load->view('templet/header', $data);
        $this->load->view('vhome', $data);
        $this->load->view('templet/footer', $data);
    }

    public function getinfo()
    {
        $tipe = '';
        if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
            $tipe = '1';
        } elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
            $tipe = '2';
        } else {
            $ip = $_SERVER["REMOTE_ADDR"];
            $tipe = '3';
        }
        $host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
        echo $host;
    }

    public function form_dashboard()
    {
        $this->load->view('vdashboard');
    }

    private function passwordAges($userId) {
        $data = $this->Usr_mod->cek_login(['MSTEMP_ID' => $userId]);
        $rsPWPolicy = $this->PWPOL_mod->select();

        $days = 0;
        foreach($data as $r) {            
            foreach($rsPWPolicy as $p) {
                $days = $p['PWPOL_MAXAGE']-$r['DAY_AFTER_CHANGE_PW'];
            }
        }
        return ['expireIn' => $days];
    }
}
