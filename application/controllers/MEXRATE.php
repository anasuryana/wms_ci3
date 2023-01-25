<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MEXRATE extends CI_Controller
{

   public function __construct()
   {
      parent::__construct();
      $this->load->model('MEXRATE_mod');
      $this->load->helper('url');
      $this->load->library('session');
      date_default_timezone_set('Asia/Jakarta');
   }
   public function index()
   {
      echo "sorry";
   }

   public function create()
   {
      $this->load->view('wms/vmexrate');
   }

   public function getAll()
   {
      header('Content-Type: application/json');
      $year = $this->input->get('inYear');
      $rs = $this->MEXRATE_mod->select_where(['YEAR(MEXRATE_DT)' => $year]);
      echo '{"data":';
      echo json_encode($rs);
      echo '}';
   }

   public function set()
   {
      $currrtime = date('Y-m-d H:i:s');
      $current_date = date('Y-m-d');
      $ccurr   = $this->input->post('incurr');
      $ctype   = $this->input->post('intype');
      $cdate1   = $this->input->post('indate');
      $cval   = $this->input->post('inval');

      $date = date_create($cdate1);
      $choosedate = date_format(date_create($cdate1), 'Y-m-d');
      if ($current_date >= $choosedate) {
         for ($i = 0; $i < 6; $i++) {
            $cdate = date_format($date, "Y-m-d");

            $datac    = [
               'MEXRATE_CURR' => $ccurr,
               'MEXRATE_TYPE' => $ctype,
               'MEXRATE_DT' => $cdate
            ];
            if ($this->MEXRATE_mod->check_Primary($datac) == 0) {
               $datas = [
                  'MEXRATE_CURR' => $ccurr,
                  'MEXRATE_TYPE' => $ctype,
                  'MEXRATE_DT' => $cdate,
                  'MEXRATE_VAL' => $cval,
                  'MEXRATE_LUPDT' => $currrtime,
                  'MEXRATE_USRID' => $this->session->userdata('nama')
               ];
               $toret = $this->MEXRATE_mod->insert($datas);
            } else {
               $datau = [
                  'MEXRATE_VAL' => $cval,
                  'MEXRATE_LUPDT' => $currrtime,
                  'MEXRATE_USRID' => $this->session->userdata('nama')
               ];
               $datak = [
                  'MEXRATE_CURR' => $ccurr,
                  'MEXRATE_TYPE' => $ctype,
                  'MEXRATE_DT' => $cdate
               ];
               $toret = $this->MEXRATE_mod->updatebyId($datau, $datak);
            }
            date_add($date, date_interval_create_from_date_string("1 days"));
         }
      } else {
         $datac    = [
            'MEXRATE_CURR' => $ccurr,
            'MEXRATE_TYPE' => $ctype,
            'MEXRATE_DT' => $cdate1
         ];
         if ($this->MEXRATE_mod->check_Primary($datac) == 0) {
            $datas = [
               'MEXRATE_CURR' => $ccurr,
               'MEXRATE_TYPE' => $ctype,
               'MEXRATE_DT' => $cdate1,
               'MEXRATE_VAL' => $cval,
               'MEXRATE_LUPDT' => $currrtime,
               'MEXRATE_USRID' => $this->session->userdata('nama')
            ];
            $toret = $this->MEXRATE_mod->insert($datas);
         } else {
            $datau = [
               'MEXRATE_VAL' => $cval,
               'MEXRATE_LUPDT' => $currrtime,
               'MEXRATE_USRID' => $this->session->userdata('nama')
            ];
            $datak = [
               'MEXRATE_CURR' => $ccurr,
               'MEXRATE_TYPE' => $ctype,
               'MEXRATE_DT' => $cdate1
            ];
            $toret = $this->MEXRATE_mod->updatebyId($datau, $datak);
         }
      }
      if ($toret > 0) {
         echo "Updated successfully";
      }
   }

   function lastupdate()
   {
      header('Content-Type: application/json');
      $aju_dt = $this->input->get('tanggal_aju');
      $strtime = strtotime($aju_dt);
      $respon = ['cd' => 1, 'msg' => 'OK'];
      if (date('w', $strtime) === '3') {
         #is wednesday
         $rs = $this->MEXRATE_mod->select_minimum_lastupdate(['MEXRATE_DT' => $aju_dt]);
         if (empty($rs)) {
            $respon = ['cd' => 0, 'msg' => 'Exchange rate is required'];
         } else {
            foreach ($rs as $r) {
               if (!$r['MEXRATE_LUPDT']) {
                  $respon = ['cd' => 0, 'msg' => 'Exchange rate is required.'];
               } else {
                  if ($r['MEXRATE_LUPDT'] !== $aju_dt) {
                     $respon = ['cd' => 0, 'msg' => 'Exchange rate updates is required'];
                  }
               }
            }
         }
      }
      die(json_encode(['status' => $respon]));
   }
}
