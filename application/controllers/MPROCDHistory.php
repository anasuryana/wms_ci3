<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MPROCDHistory extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('MPROCD_mod');
    }   

    function search()
    {
        header('Content-Type: application/json');
        $search = $this->input->get('search');
        $rs = $this->MPROCD_mod->select_header(['MITM_ITMCD' => $search]);
        die(json_encode(['data' => $rs]));
    }
    
    function toHTMLSpreadsheet()
    {
        header('Content-Type: application/json');
        $assy_codes = $this->input->post('assycodes');
        $rsActive = $this->MPROCD_mod->select_active($assy_codes);
        $rs = $this->MPROCD_mod->select_row();
        foreach($assy_codes as $r)
        {
            $rs[] = [
                'MODEL' => '',
                'ASSY_CODE' => $r,
                'ASSY_TYPE' => '',
                'P1' => '',
                'P2' => '',
                'P3' => '',
                'P4' => '',
                'SMT-AV#LINE' => '',
                'SMT-AV#CT' => '',
                'SMT-RD#LINE' => '',
                'SMT-RD#CT' => '',
                'HDP#LINE' => '',
                'HDP#CT' => '',
                'PROCESS1#LINE' => '',
                'PROCESS1#CT' => '',
                'PROCESS2#LINE' => '',
                'PROCESS2#CT' => '',
                'SMT-HW#LINE' => '',
                'SMT-HW#CT' => '',
                'SMT-HWADD#LINE' => '',
                'SMT-HWADD#CT' => '',
                'SMT-SP#LINE' => '',
                'SMT-SP#CT' => '',
            ];
        } 
        $rs2 = [];

        foreach($rsActive as $r)
        {
            foreach($rs as &$i)
            {
                if($r['MPROCD_MDLCD'] === $i['ASSY_CODE'])
                {
                    switch($r['MPROCD_SEQNO'])
                    {
                        case 1:
                            $i['P1'] = $r['MPROCD_PRCD'];
                            if($r['MPROCD_PRCD'] === 'SMT-AB')
                            {
                                if(empty($i['PROCESS1#LINE'])) {
                                    $i['PROCESS1#LINE'] = $r['MPROCD_SLINE'];
                                    $i['PROCESS1#CT'] = number_format($r['MPROCD_CT'],1);
                                } else {
                                    $temp = $i;
                                    $temp['PROCESS1#LINE'] = $r['MPROCD_SLINE'];
                                    $temp['PROCESS1#CT'] = number_format($r['MPROCD_CT'],1);
                                    $rs2[] = $temp;
                                }
                            }
                            break;
                        case 2:
                            $i['P2'] = $r['MPROCD_PRCD'];                            
                            break;
                        case 3:
                            $i['P3'] = $r['MPROCD_PRCD'];
                            break;
                        case 4:
                            $i['P4'] = $r['MPROCD_PRCD'];
                            break;
                    }

                    #define process 1                    

                    #start hand work
                    if($r['MPROCD_PRCD'] === 'SMT-HW') 
                    {
                        if(empty($i['SMT-HW#LINE'])) {
                            $i['SMT-HW#LINE'] = $r['MPROCD_SLINE'];
                            $i['SMT-HW#CT'] = number_format($r['MPROCD_CT'],1);
                        } else {
                            $rs2[] = [$i];
                        }
                    }                    
                }
            }
            unset($i);
        }
        $rs = array_merge($rs, $rs2);
        exit(json_encode(['data' => $rs, 'data2' => $rs2 ]));
    }
}