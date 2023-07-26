<?php
class User extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Usr_mod');
        $this->load->model('Usergroup_mod');
        $this->load->model('PWPOL_mod');
        $this->load->model('CHGPW_mod');
        $this->load->helper('url_helper'); 
        $this->load->library('session');
        $this->load->helper('security');
        date_default_timezone_set('Asia/Jakarta');
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

    function change()
    {
        header('Content-Type: application/json');
        $currrtime = date('Y-m-d H:i:s');
        $usrid      = $this->input->post('inUsrid');
        $firstname  = $this->input->post('inNMF');
        $lastname   = $this->input->post('inNML');
        $usrgrp     = $this->input->post('inUsrGrp');
        $status     = $this->input->post('insts');
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
        $myar = $hasil==1 ? ['cd' => '1', 'msg' => 'Updated successfully'] : ['cd' => '0', 'msg' => 'Could not be updated'];
        die(json_encode(['status' => $myar]));
    }

    function set()
    {
        $currrtime = date('Y-m-d H:i:s');
        $data = ['lower(MSTEMP_ID)' => strtolower($this->input->post('inUID'))];
        if ($this->Usr_mod->check_Primary($data)>0)
        {
            echo "ada";
        } else {
            $data2 = [
                'MSTEMP_ID' => $this->input->post('inUID'),
                'MSTEMP_PW' => hash('sha256',$this->input->post('inPW')),  
				'MSTEMP_PWHT' => $this->input->post('inPW'),  
                'MSTEMP_FNM' => $this->input->post('inFN'),
                'MSTEMP_LNM' => $this->input->post('inLN'),
                'MSTEMP_REGTM' => $currrtime,
                'MSTEMP_IP' => $this->input->ip_address(),
                'MSTEMP_STS' => FALSE
            ];
            $this->Usr_mod->insert($data2);
            echo json_encode($data);
        }
    }

    function resetpassword()
    {
        $newpw = $this->input->post('innewpw');
        $theid = $this->input->post('inid');
        $data2 = $this->input->post('apptype') === 'webapp' ?  [ 'MSTEMP_PW' => hash('sha256', $newpw), 'MSTEMP_LCHGPWDT' => date('Y-m-d H:i:s')] : [ 'MSTEMP_PWHT' => $newpw ];
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
            [ 'MSTEMP_PW' => hash('sha256',$this->input->post('inPw')), 'MSTEMP_LCHGPWDT' => date('Y-m-d H:i:s') ] 
            : [ 'MSTEMP_PWHT' => $this->input->post('inPw') ];
        $hasila = $this->Usr_mod->updatepassword($datanya,$datakey);        
        echo $hasila>0 ? "berhasil" : "belum berhasil";
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

    function userGroup() {
        header('Content-Type: application/json');
        $rs = $this->Usergroup_mod->selectAll();
        die(json_encode(['data' => $rs]));
    }

    function register(){
        date_default_timezone_set('Asia/Jakarta');
        $currrtime = date('Y-m-d H:i:s');
        $data = ['lower(MSTEMP_ID)' => strtolower($this->input->post('inUsrid'))];
        if ($this->Usr_mod->check_Primary($data)>0)
        {
            echo "ada";
        } else {
            $data2 = [
                'MSTEMP_ID' => $this->input->post('inUsrid'),
                'MSTEMP_PW' => hash('sha256',$this->input->post('inPW')),
                'MSTEMP_PWHT' => $this->input->post('inPWHT'),
                'MSTEMP_FNM' => $this->input->post('inNMF'),
                'MSTEMP_LNM' => $this->input->post('inNML'),
                'MSTEMP_REGTM' => $currrtime,
                'MSTEMP_IP' => $this->input->ip_address(),
                'MSTEMP_ACTSTS' => TRUE,
                'MSTEMP_STS' => TRUE,
                'MSTEMP_ACTTM' => $currrtime,
                'MSTEMP_GRP' => $this->input->post('inUsrGrp')
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

    function form_policy()
    {
        $this->load->view('UserMGR/vpassword_policy');
    }
    function form_lock_policy()
    {
        $this->load->view('UserMGR/vaccount_lock');
    }

    function form_change_password()
    {
        $this->load->view('UserMGR/vchange_password_periodic');
    }    
    
    function password_policy()
    {
        header('Content-Type: application/json');
        $rs = $this->PWPOL_mod->select();
        die(json_encode(['data' => $rs]));
    }

    function search_unregistered()
    {
        header('Content-Type: application/json');
        $searchby = $this->input->get('searchby');
        $search = $this->input->get('search');
        $like = $searchby === 'id' ? ['ID' => $search] : ['user_nicename' => $search];
        $rs = $this->Usr_mod->select_unregistered($like);
        die(json_encode(['data' => $rs]));
    }

    function search_registered()
    {
        header('Content-Type: application/json');
        $searchby = $this->input->get('searchby');
        $search = $this->input->get('search');
        $like = $searchby === 'id' ? ['MSTEMP_ID' => $search] : ["CONCAT(MSTEMP_FNM, ' ' ,MSTEMP_LNM)" => $search];
        $rs = $this->Usr_mod->select_registered($like);
        die(json_encode(['data' => $rs]));
    }

    function set_new_password()
    {        
        header('Content-Type: application/json');
        $rs = $this->PWPOL_mod->select();
        $userid = base64_decode($this->input->post('userid'));
        $confirmed_pw = $this->input->post('npw');
        $confirmed_pw_decrypt = hash('sha256',$confirmed_pw);
        $rschanges_history = $this->CHGPW_mod->select_unpassed($userid);
        $respon = [];
        foreach($rs as $r)
        {
            # validate length
            if(strlen($confirmed_pw)<$r['PWPOL_LENGTH'] && $r['PWPOL_LENGTH']>0)
            {
                $respon = ['cd' => 0, 'msg' => 'at least '.$r['PWPOL_LENGTH']. ' characters required'];
            } else {
                # validate history
                if($r['PWPOL_HISTORY']>0)
                {
                    foreach($rschanges_history as $h)
                    {
                        if($h['CHGPW_VALUE']===$confirmed_pw_decrypt)
                        {
                            $respon = ['cd' => 0, 'msg' => 'please use another password'];
                            break;
                        }
                    }
                    if(empty($respon) )
                    {
                        $respon = ['cd' => 1, 'msg' => 'OK'];
                    }
                } else 
                {                    
                    $respon = ['cd' => 1, 'msg' => 'OK'];
                }
            }                                    
        }
        if(!empty($respon))
        {
            if($respon['cd']===1)
            {
                # set new password
                $this->Usr_mod->updatebyId(['MSTEMP_PW' => $confirmed_pw_decrypt, 'MSTEMP_LCHGPWDT' => date('Y-m-d H:i:s')], $userid);

                # update passed history
                if(count($rschanges_history)===$r['PWPOL_HISTORY'])
                {
                    $this->CHGPW_mod->updatebyId(['CHGPW_USR' => $userid, 'CHGPW_PASSPERIOD' => '0'], ['CHGPW_PASSPERIOD' => '1']);
                }

                # log history
                $newline = $this->CHGPW_mod->lastserialid($userid)+1;
                $this->CHGPW_mod->insert([
                    'CHGPW_USR' => $userid
                    ,'CHGPW_CREATEDAT' => date('Y-m-d H:i:s')
                    ,'CHGPW_CREATEDBY' => $userid
                    ,'CHGPW_VALUE' => $confirmed_pw_decrypt
                    ,'CHGPW_PASSPERIOD' => '0'
                    ,'CHGPW_LINE' => $newline
                ]);
            }
        }
        die(json_encode(['status' => $respon]));
    }

    function set_password_policy()
    {
        header('Content-Type: application/json');
        $key = $this->input->post('key');
        $value = $this->input->post('value');
        $result = 0;
        $newvalue = [];
        switch($key)
        {
            case 'history':
                $newvalue = ['PWPOL_HISTORY' => $value];                
                break;
            case 'max_age':
                $newvalue = ['PWPOL_MAXAGE' => $value];
                break;
            case 'min_length':
                $newvalue = ['PWPOL_LENGTH' => $value];
                break;
        }
        $result = $this->PWPOL_mod->updatebyId($newvalue, ['PWPOL_LINE' => 'A']);
        $respon = $result ? ['cd' => '1', 'msg' => 'OK'] : ['cd' => '0', 'msg' => 'Failed'];
        die(json_encode(['status' => $respon]));
    }

    function getName($puser = null){
        header('Content-Type: application/json');
        if($puser!='-'){
            $puser = $puser.str_repeat('=', strlen($puser)%4);
            $puser = base64_decode($puser);
        }
        $muser = "'".$puser."'";
        $dataItem = $this->Usr_mod->selectNameMany($muser);
        die(json_encode(['data' => $dataItem]));        
    }

    function setgroup(){
        $cid = $this->input->post('inid');
        $cnm = $this->input->post('innm');
        $datac = ['MSTGRP_ID' => strtoupper($cid), 'MSTGRP_NM' => $cnm];
        $sts_o = [];
        if($this->Usergroup_mod->check_Primary($datac)>0)
        {            
            $sts_o[] = ['cd'=> 0, 'dsc' => 'The data is already registered'];
        } else 
        {
            $toret = $this->Usergroup_mod->insert($datac);
            if($toret >0 )
            {
                $sts_o[] = ['cd'=> 1, 'dsc' => 'Added successfully'];
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
