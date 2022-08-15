<?php
class User extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Usr_mod');
        $this->load->model('Usergroup_mod');        
        $this->load->helper('url_helper'); 
        $this->load->library('session');
        $this->load->helper('security');        
    }

    public function index()
    {
        $this->IsAlreadyLogged();
        $data['lusergroup'] = $this->Usergroup_mod->selectAll();
        $this->load->view('UserMGR/vuser',$data);
    }

    function vedit(){
        $data['lusergroup'] = $this->Usergroup_mod->selectAll();
        $this->load->view('UserMGR/vuser_edit',$data);
    }

    function change(){
        date_default_timezone_set('Asia/Jakarta');
        $currrtime = date('Y-m-d H:i:s');
        $usrid      = $this->input->get('inUsrid');
        $firstname  = $this->input->get('inNMF');
        $lastname   = $this->input->get('inNML');
        $usrgrp     = $this->input->get('inUsrGrp');
        $status     = $this->input->get('insts');
        $data2      = [
            'MSTEMP_FNM' => $firstname,
            'MSTEMP_LNM' => $lastname,
            'MSTEMP_GRP' => $usrgrp,
            'MSTEMP_STS' => $status
        ];
        if($status=='0'){
            $data2['MSTEMP_DACTTM'] = $currrtime;
        }
        $hasil = $this->Usr_mod->updatebyId($data2, $usrid);
        if($hasil==1){
            echo "Updated successfully";
        } else {
            echo "mmm";
        }
    }

    function set()
    {
        date_default_timezone_set('Asia/Jakarta');
        $currrtime = date('Y-m-d H:i:s');
        $data = array(
            'lower(MSTEMP_ID)' => strtolower($this->input->post('inUID'))
        );
        if ($this->Usr_mod->check_Primary($data)>0)
        {
            echo "ada";
        } else {
            $data2 = array(
                'MSTEMP_ID' => $this->input->post('inUID'),
                'MSTEMP_PW' => hash('sha256',$this->input->post('inPW')),  
				'MSTEMP_PWHT' => $this->input->post('inPW'),  
                'MSTEMP_FNM' => $this->input->post('inFN'),
                'MSTEMP_LNM' => $this->input->post('inLN'),
                'MSTEMP_REGTM' => $currrtime,
                'MSTEMP_IP' => $this->input->ip_address(),
                'MSTEMP_STS' => FALSE
            );
            $this->Usr_mod->insert($data2);
            echo json_encode($data);
        }
    }

    function resetpassword(){
        $newpw = $this->input->post('innewpw');
        $theid = $this->input->post('inid');
        $data2 = $this->input->post('apptype') === 'webapp' ?  [ 'MSTEMP_PW' => hash('sha256', $newpw) ] : [ 'MSTEMP_PWHT' => $newpw ];
        $datakey = [ 'lower(MSTEMP_ID)' => strtolower($theid) ];
        $toret = $this->Usr_mod->updatepassword($data2, $datakey);
        echo $toret;
    }
    
    function viewAll_reged(){
        header('Content-Type: application/json');
        $dataItem = $this->Usr_mod->selectAll_reged();        
        die('{"data":'.json_encode($dataItem).'}');
    }

    function getpw_sts()
    {
        $data = [
            'lower(MSTEMP_ID)' => strtolower($this->input->get('inUID')),
            'MSTEMP_PW' => hash('sha256',$this->input->get('inPw'))
        ];
        $hasil = $this->Usr_mod->cek_pw($data);
        echo $hasil;
    }

    function getunapproved()
    {   
        header('Content-Type: application/json');
        $hasil = $this->Usr_mod->selectunapproved();
        echo json_encode($hasil);
    }
    
    function setnewpw()
    {
        $datakey = [
            'lower(MSTEMP_ID)' => strtolower($this->input->post('inUID'))
        ];
        $datanya = $this->input->post('apptype') === 'webapp' ? 
            [ 'MSTEMP_PW' => hash('sha256',$this->input->post('inPw')) ] 
            : [ 'MSTEMP_PWHT' => $this->input->post('inPw') ];
        $hasila = $this->Usr_mod->updatepassword($datanya,$datakey);
        if ($hasila>0){
            echo "berhasil";
        } else {
            echo "belum berhasil";
        }
    } 

    function getactivated(){
        date_default_timezone_set('Asia/Jakarta');
        $currrtime = date('Y-m-d H:i:s');
        $usrid = $this->input->get('inUsr');
        $group = $this->input->get('inGroup');
        $data1 = [
            'MSTEMP_GRP' => $group,
            'MSTEMP_ACTSTS' => true,
            'MSTEMP_STS' => true,
            'MSTEMP_ACTTM' => $currrtime
        ];
        $hasila = $this->Usr_mod->updatebyId($data1,$usrid);
        echo $hasila;
    }

    function vregister(){
        $data['lusergroup'] = $this->Usergroup_mod->selectAll();
        $this->load->view('UserMGR/vuser_reg',$data);
    }

    function register(){
        date_default_timezone_set('Asia/Jakarta');
        $currrtime = date('Y-m-d H:i:s');
        $data = ['lower(MSTEMP_ID)' => strtolower($this->input->get('inUsrid'))];
        if ($this->Usr_mod->check_Primary($data)>0)
        {
            echo "ada";
        } else {
            $data2 = [
                'MSTEMP_ID' => $this->input->get('inUsrid'),
                'MSTEMP_PW' => hash('sha256',$this->input->get('inPW')),
                'MSTEMP_PWHT' => $this->input->get('inPW'),
                'MSTEMP_FNM' => $this->input->get('inNMF'),
                'MSTEMP_LNM' => $this->input->get('inNML'),
                'MSTEMP_REGTM' => $currrtime,
                'MSTEMP_IP' => $this->input->ip_address(),
                'MSTEMP_ACTSTS' => TRUE,
                'MSTEMP_STS' => TRUE,
                'MSTEMP_ACTTM' => $currrtime,
                'MSTEMP_GRP' => $this->input->get('inUsrGrp')
            ];
            $h = $this->Usr_mod->insert($data2);
            echo $h;
        }
    }
    function getuserbyName(){
        $usrnm = $this->input->get('inUsr');
        $rsUser = $this->Usr_mod->selectbyName($usrnm);
        echo json_encode($rsUser);
    }

    function getmanyUserInfo(){
        $usrID = $this->input->post('inUsr');
        $wherec = '';
        if (is_array($usrID)){
            for ($i=0;$i<count($usrID);$i++){
                $wherec.="'".$usrID[$i]."',";
            }
            $wherec = substr($wherec,0,-1);
            if($wherec!=''){
                $rsp = $this->Usr_mod->selectNameMany($wherec);
                echo json_encode($rsp);
            } else {
                echo '[]';
            }
        } else 
        {
            echo '[]';
        }
        
    }

    function getName($puser = null){
        header('Content-Type: application/json');
        if($puser!='-'){
            $puser = $puser.str_repeat('=', strlen($puser)%4);
            $puser = base64_decode($puser);
        }
        $muser = "'".$puser."'";
        $dataItem = $this->Usr_mod->selectNameMany($muser);
        echo '{"data":';
        echo json_encode($dataItem);
        echo '}';
    }

    function setgroup(){
        $cid = $this->input->post('inid');
        $cnm = $this->input->post('innm');
        $datac = array('MSTGRP_ID' => strtoupper($cid), 'MSTGRP_NM' => $cnm);
        $sts_o = array();
        if($this->Usergroup_mod->check_Primary($datac)>0){            
            array_push($sts_o, array('cd'=> 0, 'dsc' => 'The data is already registered'));            
        } else {
            $toret = $this->Usergroup_mod->insert($datac);
            if($toret >0 ){
                array_push($sts_o, array('cd'=> 1, 'dsc' => 'Added successfully'));                     
            }
        }
        echo json_encode($sts_o);
    }

    function form_activity() {
        $this->load->view('wms_report/rm_user_activity');
    }

    function getUserActivity() {
        header('Content-Type: application/json');        
        $cyear = $this->input->get('year');
        $aPeriod = [];
        $aUsers = [];
        $rs = $this->Usr_mod->select_kitting($cyear);
        foreach($rs as $r) {
            if(!in_array($r['YEARMONTH'], $aPeriod)) {
                $aPeriod[] = $r['YEARMONTH'];
            }
            if(!in_array($r['FULLNAME'], $aUsers)) {
                $aUsers[] = $r['FULLNAME'];
            }
        }        
        $datasets = [];
        foreach($aUsers as $u) {
            $period = [];
            foreach( $aPeriod as $p) {
                $isfound = false;
                $theval = 0;
                foreach($rs as $r) {
                    if($u==$r['FULLNAME'] && $p==$r['YEARMONTH']) {
                        $theval = (int)$r['TTLSCN'];
                        $isfound = true;
                        break;
                    }
                }
                $period[] = $isfound ? $theval : 0;
            }
            $datasets[] = [
                'label' => $u
                ,'data' => $period
                ,'tension' => 0.1
                ,'borderColor' => "rgb(".random_int(0,255).",".random_int(0,255).",".random_int(0,255).")"
            ];
        }
        die(json_encode(['user' => $aUsers, 'labels' => $aPeriod, 'datasets' => $datasets]));
    }

	function IsAlreadyLogged()
    {
        if ($this->session->userdata('status'))
        {
            //
        } else {
            redirect(base_url(""));
        }
    }    
}
