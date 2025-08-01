<?php

class Pages extends CI_Controller
{
    private $m_grupid = '';
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Usr_mod');
        $this->load->model('Usrlog_mod');
        $this->load->model('PWPOL_mod');
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

    public function gen_pw($pw)
    {
        $tes = hash('sha256', $pw);
        echo $tes;
    }

    public function getip()
    {
        echo $_SERVER['REMOTE_ADDR'];
    }

    public function l()
    {
        #log user accessed
        date_default_timezone_set('Asia/Jakarta');
        $currrtime = date('Y-m-d H:i:s');
        $dloghis = $this->Usrlog_mod->selectLastOne();
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
            'USRLOG_IP' => $_SERVER['REMOTE_ADDR'],
        ];
        $ret = $this->Usrlog_mod->insert($data_log);
        $myar = [];
        if ($ret > 0) {
            $myar[] = ['cd' => 1, 'msg' => 'OK'];
        } else {
            $myar[] = ['cd' => 0, 'msg' => 'not ok'];
        }
        $Calc_lib = new RMCalculator();
        $res = $Calc_lib->sync_item_description1();
        $res_newitem = $Calc_lib->sync_new_item();
        $res_newsupplier = $Calc_lib->sync_supplier();
        $res_newcustomer = $Calc_lib->sync_customer();
        $Calc_lib->sync_new_itemGrade();
        die(json_encode(['status' => $myar
            , 'status_i1' => $res
            , 'status_newitem' => $res_newitem
            , 'supplier' => $res_newsupplier
            , 'customer' => $res_newcustomer,
        ]));
    }

    public function login()
    {
        header('Content-Type: application/json');
        date_default_timezone_set('Asia/Jakarta');
        
        $currrtime = date('Y-m-d H:i:s');
        $respon = [];
        $this->form_validation->set_rules('inputUserid', 'Username', 'trim|required|xss_clean');
        $this->form_validation->set_rules('inputPassword', 'inPass', 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $respon = ['message' => 'sorry please try again'];
        } else {
            $username = $this->input->post('inputUserid');
            if ($this->session->userdata('status') == "login") {
                $respon = ['message' => 'OK', 'tokennya' => base64_encode($username)];
                die(json_encode($respon));
            }
            $this->m_userid = $username;
            $password = $this->input->post('inputPassword');
            $where = [
                'MSTEMP_ID' => $username,
                'MSTEMP_PW' => hash('sha256', $password),
                'MSTEMP_ACTSTS' => true,
                'MSTEMP_STS' => true,
            ];

            $dlogses = $this->Usr_mod->cek_login($where);
            $dloghis = $this->Usrlog_mod->selectLastOne();
            $fname = '';

            $idlog = '';
            $day_after_change_pw = 0;
            foreach ($dlogses as $r) {
                $fname = $r['MSTEMP_FNM'];
                $day_after_change_pw = $r['DAY_AFTER_CHANGE_PW'];
                $this->m_grupid = $r['MSTEMP_GRP'];
            }
            foreach ($dloghis as $dloghis) {
                $idlog = $dloghis['idnew'];
            }
            $data_log = [
                'USRLOG_ID' => $idlog,
                'USRLOG_USR' => $username,
                'USRLOG_GRP' => $this->m_grupid,
                'USRLOG_TYP' => $dlogses ? 'LGIN' : 'LGFLR',
                'USRLOG_TM' => $currrtime,
                'USRLOG_IP' => $_SERVER['REMOTE_ADDR'],
            ];

            if (strlen($fname) > 0) {
                $rsPWPolicy = $this->PWPOL_mod->select();
                $shouldChangePassword = false;
                foreach ($rsPWPolicy as $r) {
                    if ($day_after_change_pw > $r['PWPOL_MAXAGE']) {
                        $shouldChangePassword = true;                        
                    }
                }
                $this->Usrlog_mod->insert($data_log);

                if (!$shouldChangePassword) {
                    $data_session = [
                        'nama' => $username,
                        'status' => "login",
                        'sfname' => $fname,
                        'gid' => $this->m_grupid,
                    ];
                    $this->session->set_userdata($data_session);
                    $respon = ['message' => 'OK', 'tokennya' => base64_encode($username)];
                } else {
                    $respon = ['message' => 'Need to change password', 'redirect_url' => base_url("change_password?uid=" . base64_encode($username))];
                }
            } else {
                $this->Usrlog_mod->insert($data_log);
                $respon = ['message' => 'sorry please try again, check user id and password'];
            }
        }
        die(json_encode($respon));
    }

    public function loginPerApp()
    {
        $allowedList = ['http://localhost:3000', 'http://localhost/scan-doc/', 'http://192.168.0.29:8080', 'http://localhost'];
        if (in_array($_SERVER['HTTP_ORIGIN'], $allowedList)) {
            header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
        }
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Accept');
        date_default_timezone_set('Asia/Jakarta');
        $post = json_decode($this->security->xss_clean($this->input->raw_input_stream));
        $username = $post->userid;
        $password = $post->userpassword;
        $where = [
            'MSTEMP_ID' => $username,
            'MSTEMP_PW' => hash('sha256', $password),
            'MSTEMP_ACTSTS' => true,
            'MSTEMP_STS' => true,
        ];

        $dlogses = $this->Usr_mod->cek_login($where);
        $fname = '';
        foreach ($dlogses as $dlog) {
            $fname = $dlog['MSTEMP_FNM'];
            $this->m_grupid = $dlog['MSTEMP_GRP'];
        }
        $response = strlen($fname) > 0 ? ['cd' => 1, 'msg' => 'go ahead', 'userinfo' => ['FIRSTNAME' => $fname, 'GROUP' => $this->m_grupid, 'USERID' => $username]]
        : ['cd' => 0, 'msg' => 'not found', 'post' => $post];
        die(json_encode(['status' => $response]));
    }

    public function IsAlreadyLogged()
    {
        if ($this->session->userdata('status')) {
            redirect('home');
        }
    }

    public function logout()
    {
        date_default_timezone_set('Asia/Jakarta');
        $currrtime = date('Y-m-d H:i:s');
        $dloghis = $this->Usrlog_mod->selectLastOne();
        $idlog = '';
        foreach ($dloghis as $dloghis) {
            $idlog = $dloghis['idnew'];
        }
        $data_log = [
            'USRLOG_ID' => $idlog,
            'USRLOG_USR' => $this->session->userdata('nama'),
            'USRLOG_GRP' => $this->session->userdata('gid'),
            'USRLOG_TYP' => 'LGOUT',
            'USRLOG_TM' => $currrtime,
            'USRLOG_IP' => $_SERVER['REMOTE_ADDR'],
        ];
        $this->Usrlog_mod->insert($data_log);
        $this->session->unset_userdata('status');
        redirect(base_url(""));
    }

}
