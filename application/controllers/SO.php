<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SO extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
        $this->load->helper('download');
        $this->load->library('session');
        $this->load->model('XBGROUP_mod');
        $this->load->model('DELV_mod');
        $this->load->model('XSO_mod');
        $this->load->model('SO_mod');
	}
	public function index()
	{
		echo "sorry";
    }
    public function vcreate(){
        $data['lbg'] = $this->XBGROUP_mod->selectall();
        $data['ldeliverycode'] = $this->DELV_mod->select_delv_code();
        $this->load->view('wms/vso', $data);
    }

    public function xget_customer_so(){
        $cbg = $this->input->get('inbg');
		$rs = $this->XSO_mod->selectcustomer_so($cbg);
		$myar = [];
		if(count($rs)>0){
			array_push($myar, ['cd' => '1' , 'msg' => 'go ahead']);
		} else {
			array_push($myar, ['cd' => '0' , 'msg' => 'not found']);
		}
		die('{"status" : '.json_encode($myar).', "data": '.json_encode($rs).'}');
    }

    public function getlinkitemtemplate(){
        $murl   = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
        $murl   .= "://".$_SERVER['HTTP_HOST'];
        echo $murl."/wms/SO/downloadtemplate";
    }

    function downloadtemplate(){
        $theurl = 'assets/userxls_template/tmpl_so.xls' ;
        force_download($theurl , null );
        echo $theurl;
    }
        
    public function import(){
        header('Content-Type: application/json');
        date_default_timezone_set('Asia/Jakarta');
		$currrtime = date('Y-m-d H:i:s');
        $cbg     	= $this->input->post('inbg');
        $ccust     	= $this->input->post('incust');
        $cconsig     	= $this->input->post('inconsig');
        $cdo     	= $this->input->post('inso');
        $citemcode  = $this->input->post('initemcode');
		$corderdate    = $this->input->post('inorderdate');
		$cqty    = intval(str_replace(',','',$this->input->post('inqty'))) ;
		$cdelsch    = $this->input->post('indelsch');
				
		$crowid    	= $this->input->post('inrowid');
		$datac 		= ['SO_NO' => $cdo, 'SO_ORDRDT' => $corderdate, 'SO_ITEMCD' => $citemcode];
        $myar 		= array();
        // if($this->SO_mod->check_Primary($datac)==0){                      
			$datas = [
				'SO_NO' => $cdo
				,'SO_ORDRDT' => $corderdate
				,'SO_ORDRQT' => $cqty
				,'SO_ITEMCD' => $citemcode
				,'SO_BG' => $cbg
				,'SO_CUSCD' => $ccust
				,'SO_DELCD' => $cconsig
				,'SO_DELSCH' => $cdelsch
				,'SO_LINENO' => $cdo."-".$crowid
				,'SO_LINE' => $crowid
				,'SO_LUPDT' => $currrtime
				,'SO_USRID' => $this->session->userdata('nama')
            ];
            $toret = $this->SO_mod->insert($datas);
            
			if($toret>0){
				$anar = array("indx" => $crowid, "status"=> 'Saved');
			} else {
				$anar = array("indx" => $crowid, "status"=> 'Could not save');
			}            
        // } else {
        //     $datau = ['SO_ORDRQT' => $cqty];
        //     $retupdate = $this->SO_mod->updatebyId($datau, $datac);
        //     if($retupdate>0){
        //         $anar = array("indx" => $crowid, "status"=> 'updated');
        //     } else {
        //         $anar = array("indx" => $crowid, "status"=> 'could not update'); 
        //     }
        // }
        array_push($myar, $anar);
        echo json_encode($myar);
    }
    
    public function search(){
        header('Content-Type: application/json');
        $csearch = $this->input->get('insearch');
        $csearchby = $this->input->get('insearchby');        
        $myar = [];
        $rs = $csearchby==='doc' ? $this->SO_mod->select_g_like(['SO_NO' => $csearch]) : $this->SO_mod->select_g_like(['MCUS_CUSNM' => $csearch]);        
        $myar[] = count($rs) >0 ? ['cd' => 1, 'msg' => 'go ahead'] : ['cd' => 0, 'msg' => 'not found'];        
        die(json_encode(['status' => $myar, 'data' => $rs]));
    }

    public function searchsocontent(){
        header('Content-Type: application/json');
        $cso = $this->input->get('inso');
        $rs = $this->SO_mod->select_all_where(['SO_NO' => $cso]);        
        $myar[] = count($rs) > 0 ? ['cd' => 1, 'msg' => 'go ahead'] : ['cd' => 0, 'msg' => 'go ahead'];         
        die(json_encode(['status' => $myar, 'data' => $rs]));
    }

    public function remove(){
        header('Content-Type: application/json');
        $cinline = $this->input->get('inline');
        $myar = [];        
        if(trim($cinline)==''){
            array_push($myar, ['cd' => 0, 'msg' => 'nothing to be deleted']);
        } else {
            $retdel = $this->SO_mod->delete_where(['SO_LINE' => $cinline]);            
            $myar[] = $retdel>0 ? ['cd' => 1, 'msg' => 'deleted'] : ['cd' => 0, 'msg' => 'could not delete'];            
        }
        die(json_encode(['status' => $myar]));
    }

    public function set(){
        header('Content-Type: application/json');
        date_default_timezone_set('Asia/Jakarta');
        $currrtime = date('Y-m-d H:i:s');
        $cbg = $this->input->post('inbg');
        $ccust = $this->input->post('incust');
        $cdoc = $this->input->post('indoc');
        $cconsig = $this->input->post('inconsig');
        $ca_item = $this->input->post('ina_item');
        $ca_ord_dt = $this->input->post('ina_ord_dt');
        $ca_ord_qt = $this->input->post('ina_ord_qty');
        $ca_sch_dt = $this->input->post('ina_sch_dt');
        $ca_line = $this->input->post('ina_line');
        $ttlrows = is_array($ca_item) ? count($ca_item) : 0;
        $ttlsaved = 0;
        $ttledited = 0;
        $myar = [];
        for($i=0;$i<$ttlrows;$i++){
            if($ca_line[$i]==''){
                $idx = $this->SO_mod->lastserialid($cdoc)+1;
                $datas = [
                    'SO_NO' => $cdoc
                    ,'SO_ORDRDT' => $ca_ord_dt[$i]
                    ,'SO_ORDRQT' => str_replace(',','',$ca_ord_qt[$i])
                    ,'SO_ITEMCD' => $ca_item[$i]
                    ,'SO_BG' => $cbg
                    ,'SO_CUSCD' => $ccust
                    ,'SO_DELCD' => $cconsig
                    ,'SO_DELSCH' => $ca_sch_dt[$i]
                    ,'SO_LINENO' => $cdoc."-".$idx
                    ,'SO_LINE' => $idx
                    ,'SO_LUPDT' => $currrtime
                    ,'SO_USRID' => $this->session->userdata('nama')
                ];
                $retins = $this->SO_mod->insert($datas);
                $ttlsaved+=$retins;
            } else {
                $dataw = ['SO_LINE' => $ca_line[$i] , 'SO_NO' => $cdoc];
                if($this->SO_mod->check_Primary($dataw)>0 ){
                    $datau = ['SO_ORDRQT' => $ca_ord_qt[$i], 'SO_LUPDT' => $currrtime, 'SO_USRID' => $this->session->userdata('nama')];
                    $retupdate = $this->SO_mod->updatebyId($datau, $dataw);
                    $ttledited+=$retupdate;
                }
            }
        }
        $myar[] = ['cd'=> 1, 'msg'=> "$ttlsaved saved, $ttledited updated"];
        die(json_encode(['status' => $myar]));        
    }

    public function outstanding_mega(){
        header('Content-Type: application/json');
        $bg = $this->input->post('bg');
        $cuscd = $this->input->post('cuscd');        
        $items = $this->input->post('itemcd');
        $columns = ['RTRIM(SSO2_CPONO) SSO2_CPONO' , 'SSO2_ORDQT','SSO2_DELQT', 'SSO2_DELCD', 'RTRIM(SSO2_MDLCD) SSO2_MDLCD', 'SSO2_SLPRC','SSO2_SOLNO'];
		$where = ['SSO2_BSGRP' => $bg, 'SSO2_CUSCD' => $cuscd, 'SSO2_CPOTYPE' => 'CPO' , 'SSO2_COMFG' => '0'];
		$rs = $this->XSO_mod->selectbyVAR_inmodel($columns,$where, $items);
        // $rs[] =  ['SSO2_CPONO' => 'PO1', 'SSO2_ORDQT' => 200, 'SSO2_DELQT' => 0, 'SSO2_MDLCD' => '212206000', 'SSO2_SLPRC' => 0.233, 'SSO2_SOLNO' => 'PO1_LINE' ];
        // $rs[] =  ['SSO2_CPONO' => 'PO1', 'SSO2_ORDQT' => 200, 'SSO2_DELQT' => 0, 'SSO2_MDLCD' => '219306400', 'SSO2_SLPRC' => 0.233, 'SSO2_SOLNO' => 'PO1_LINE' ];
        die(json_encode(['data' => $rs]));
    }
    public function outstanding(){
        header('Content-Type: application/json');
        $bg = $this->input->post('bg');
        $cuscd = $this->input->post('cuscd');
        $consignee = $this->input->post('consignee');
        $items = $this->input->post('itemcd');
        $rs = $this->SO_mod->select_ost_items(['SO_BG' => $bg, 'SO_CUSCD' => $cuscd, 'SO_DELCD' => $consignee], $items);
        die(json_encode(['data' => $rs]));
    }

    public function form_outstanding(){
        $this->load->view('wms_report/vform_so_int_outstanding');
    }

    public function outstanding_int(){
        header('Content-Type: application/json');
        $itemcd  = $this->input->get('item');
        $rs = $this->SO_mod->select_ost_so_int_byitem($itemcd);
        die(json_encode(['data' => $rs]));
    }

    public function plot_so_mega() {
        date_default_timezone_set('Asia/Jakarta');
		$current_date = date('Y-m-d');
		$current_time = date('H:i:s');
		$fixdate = $current_date;
		$fixY = substr($fixdate,0,4);
		$fixM = substr($fixdate,5,2);
		if($current_time<'07:00:00') {
			$fixdate = strtotime($current_date. '-1 days');
			$fixdate = date('Y-m-d', $fixdate);
		}
        header('Content-Type: application/json');
        $docplot = $this->input->get('doc');
        $rs = $this->DELV_mod->select_reqPlot_SIBase($docplot);
        $bg = '';
        $cuscd = '';        
        $items = [];
        foreach($rs as $r) {
            $bg = $r['DLV_BSGRP'];
            $cuscd = $r['DLV_CUSTCD'];            
            if(!in_array($r['SER_ITMID'], $items)) {
                $items[] = $r['SER_ITMID'];
            }
        }
        $columns = ['RTRIM(SSO2_CPONO) SSO2_CPONO' , 'SSO2_ORDQT','SSO2_DELQT', 'SSO2_DELCD', 'RTRIM(SSO2_MDLCD) SSO2_MDLCD', 'SSO2_SLPRC','SSO2_SOLNO','(SSO2_ORDQT-SSO2_DELQT) BALQT'];
		$where = ['SSO2_BSGRP' => $bg, 'SSO2_CUSCD' => $cuscd, 'SSO2_CPOTYPE' => 'CPO' , 'SSO2_COMFG' => '0'];
        $rsos = $this->XSO_mod->selectbyVAR_inmodel($columns,$where, $items);
        $rsplot = [];
        $SOFocus = '';
        foreach($rs as &$r) { 
            $balFOC = $r['DLVQTY']-$r['PLOTQTY'];
            if($balFOC>0) {
                foreach($rsos as &$o) {
                    $balFOC = $r['DLVQTY']-$r['PLOTQTY'];
                    if($r['SER_ITMID']===$o['SSO2_MDLCD'] && $balFOC>0 && $o['BALQT'] >0) {                        
                        $PLTQT = $balFOC;
                        if($balFOC>$o['BALQT']) {
                            $r['PLOTQTY'] += $o['BALQT'];
                            $PLTQT = $o['BALQT'];
                            $o['BALQT'] = 0;
                        } else {
                            $o['BALQT'] -= $balFOC;
                            $r['PLOTQTY'] += $balFOC;
                        }
                        $SOFocus = $o['SSO2_CPONO'];
                        $rsplot[] = [
                            'SO_NO' => $o['SSO2_CPONO'],
                            'SO_LINE' => $o['SSO2_SOLNO'],
                            'SISCN_LINE' => $r['SISCN_LINENO'],
                            'ASSYCODE' => $o['SSO2_MDLCD'],
                            'SO_PRICE' => $o['SSO2_SLPRC'],
                            'REQQT' => $r['DLVQTY'],
                            'PLTQT' => $PLTQT
                        ];
                    }
                }
                unset($o);
            }
        }
        unset($r);

        #set price for assy that have no sales order
        foreach($rs as $r) {
            $balFOC = $r['DLVQTY']-$r['PLOTQTY'];
            if($r['PLOTQTY']==0) {
                if($bg=='PSI1PPZIEP' && $cuscd=='SME007U'){ 
                    $rs_mst_price = $this->XSO_mod->select_latestprice_period($bg, $cuscd,"'".$r['SER_ITMID']."'", $fixY,$fixM);
                } else {
                    $rs_mst_price = $this->XSO_mod->select_latestprice($bg, $cuscd,"'".$r['SER_ITMID']."'");

                }
                foreach($rs_mst_price as $n) {
                    $rsplot[] = [
                        'SO_NO' => $SOFocus,
                        'SO_LINE' => 'X',
                        'SISCN_LINE' => $r['SISCN_LINENO'],
                        'ASSYCODE' => $r['SER_ITMID'],
                        'SO_PRICE' => substr($n['MSPR_SLPRC'],0,1) =='.' ? '0'.$n['MSPR_SLPRC'] : $n['MSPR_SLPRC'],
                        'REQQT' => $r['DLVQTY'],
                        'PLTQT' => 1
                    ];
                    break;
                }
            }
        }
        #end
        die(json_encode(['data' => $rsplot, "rs" => $rs, "rsos" => $rsos]));
    }

    public function plot_so() {
        header('Content-Type: application/json');
        $docplot = $this->input->get('doc');        
        $rs = $this->DELV_mod->select_reqPlot($docplot);
        $bg = '';
        $cuscd = '';
        $consignee = '';
        foreach($rs as $r) {
            $bg = $r['DLV_BSGRP'];
            $cuscd = $r['DLV_CUSTCD'];
            $consignee = $r['DLV_CONSIGN'];
            break;
        }
        $rsos = $this->SO_mod->select_ost_withouttxid($bg, $cuscd, $consignee, $docplot);
        $rsplot = [];
        foreach($rs as &$r) {            
            $balFOC = $r['DLVQTY']-$r['PLOTQTY'];
            if($balFOC>0) {
                foreach($rsos as &$o) {
                    $balFOC = $r['DLVQTY']-$r['PLOTQTY'];
                    if($r['SER_ITMID']===$o['SO_ITEMCD'] && $balFOC>0 && $o['BALQT'] >0) {
                        $PLTQT = $balFOC;
                        if($balFOC>$o['BALQT']) {
                            $r['PLOTQTY'] += $o['BALQT'];
                            $PLTQT = $o['BALQT'];
                            $o['BALQT'] = 0;
                        } else {
                            $o['BALQT'] -= $balFOC;
                            $r['PLOTQTY'] += $balFOC;
                        }
                        $rsplot[] = [
                            'SO_NO' => $o['SO_NO'],
                            'ASSYCODE' => $o['SO_ITEMCD'],
                            'REQQT' => $r['DLVQTY'],
                            'PLTQT' => $PLTQT
                        ];
                    }
                }
                unset($o);
            }
        }
        unset($r);
        die(json_encode(['data' => $rsplot]));
    }

    function plot_so_manually(){
        header('Content-Type: application/json');
        $docplot = $this->input->get('doc');
        $so_no = $this->input->get('so_no');
        $so_item = $this->input->get('so_item');
        $bg = '';
        $cuscd = '';
        $consignee = '';
        $rs = $this->DELV_mod->select_reqPlot($docplot);
        foreach($rs as $r) {
            $bg = $r['DLV_BSGRP'];
            $cuscd = $r['DLV_CUSTCD'];
            $consignee = $r['DLV_CONSIGN'];
            break;
        }
        $rsos = $this->SO_mod->select_ost_withouttxid($bg, $cuscd, $consignee, $docplot);
        $rsplot = [];
        foreach($rs as &$r) {  
            if($r['SER_ITMID']===$so_item) {
                $balFOC = $r['DLVQTY']-$r['PLOTQTY'];
                if($balFOC>0) {
                    foreach($rsos as &$o) {
                        $balFOC = $r['DLVQTY']-$r['PLOTQTY'];
                        if($r['SER_ITMID']===$o['SO_ITEMCD'] && $balFOC>0 && $o['BALQT'] >0 && $o['SO_NO']===$so_no) {
                            $PLTQT = $balFOC;
                            if($balFOC>$o['BALQT']) {
                                $r['PLOTQTY'] += $o['BALQT'];
                                $PLTQT = $o['BALQT'];
                                $o['BALQT'] = 0;
                            } else {
                                $o['BALQT'] -= $balFOC;
                                $r['PLOTQTY'] += $balFOC;
                            }
                            $rsplot[] = [
                                'SO_NO' => $o['SO_NO'],
                                'ASSYCODE' => $o['SO_ITEMCD'],
                                'REQQT' => $r['DLVQTY'],
                                'PLTQT' => $PLTQT
                            ];
                        }
                    }
                    unset($o);
                }
            }
        }
        unset($r);
        die(json_encode(['data' => $rsplot]));
    }
   
    public function plot_somega_manually(){
        header('Content-Type: application/json');
        $docplot = $this->input->get('doc');
        $sono = $this->input->get('so_no');
        $soitem = $this->input->get('so_item');
        $soline = $this->input->get('so_line');
        $plottedqty = $this->input->get('plotqty');
        $rs = $this->DELV_mod->select_reqPlot_SIBase_withItem($docplot, $soitem);
        $bg = '';
        $cuscd = '';        
        $items[] = $soitem;
        foreach($rs as $r) {
            $bg = $r['DLV_BSGRP'];
            $cuscd = $r['DLV_CUSTCD'];            
            // if(!in_array($r['SER_ITMID'], $items)) {
            //     $items[] = $r['SER_ITMID'];
            // }
        }
        $columns = ['RTRIM(SSO2_CPONO) SSO2_CPONO' , 'SSO2_ORDQT','SSO2_DELQT', 'SSO2_DELCD', 'RTRIM(SSO2_MDLCD) SSO2_MDLCD', 'SSO2_SLPRC','SSO2_SOLNO','(SSO2_ORDQT-SSO2_DELQT) BALQT'];
		$where = ['SSO2_BSGRP' => $bg, 'SSO2_CUSCD' => $cuscd, 'SSO2_CPOTYPE' => 'CPO' , 'SSO2_COMFG' => '0', 'SSO2_CPONO' => $sono, 'SSO2_SOLNO' => $soline];
        $rsos = $this->XSO_mod->selectbyVAR_inmodel($columns,$where, $items);
        $rsplot = [];
        $SOFocus = '';
        foreach($rs as &$r) { 
            if($r['SER_ITMID']==$soitem){
                $r['PLOTQTY']=$plottedqty;
                break;
            }
        }
        unset($r);

        foreach($rs as &$r) { 
            $balFOC = $r['DLVQTY']-$r['PLOTQTY'];
            if($balFOC>0) {
                foreach($rsos as &$o) {
                    $balFOC = $r['DLVQTY']-$r['PLOTQTY'];
                    if($r['SER_ITMID']===$o['SSO2_MDLCD'] && $balFOC>0 && $o['BALQT'] >0) {                        
                        $PLTQT = $balFOC;
                        if($balFOC>$o['BALQT']) {
                            $r['PLOTQTY'] += $o['BALQT'];
                            $PLTQT = $o['BALQT'];
                            $o['BALQT'] = 0;
                        } else {
                            $o['BALQT'] -= $balFOC;
                            $r['PLOTQTY'] += $balFOC;
                        }
                        $SOFocus = $o['SSO2_CPONO'];
                        $rsplot[] = [
                            'SO_NO' => $o['SSO2_CPONO'],
                            'SO_LINE' => $o['SSO2_SOLNO'],
                            'SISCN_LINE' => $r['SISCN_LINENO'],
                            'ASSYCODE' => $o['SSO2_MDLCD'],
                            'SO_PRICE' => $o['SSO2_SLPRC'],
                            'REQQT' => $r['DLVQTY'],
                            'PLTQT' => $PLTQT
                        ];
                    }
                }
                unset($o);
            }
        }
        unset($r);

        #set price for assy that have no sales order
        foreach($rs as $r) {
            $balFOC = $r['DLVQTY']-$r['PLOTQTY'];
            if($balFOC>0) {
                $rs_mst_price = $this->XSO_mod->select_latestprice($bg, $cuscd,"'".$r['SER_ITMID']."'");
                foreach($rs_mst_price as $n) {
                    $rsplot[] = [
                        'SO_NO' => $SOFocus,
                        'SO_LINE' => 'X',
                        'SISCN_LINE' => $r['SISCN_LINENO'],
                        'ASSYCODE' => $r['SER_ITMID'],
                        'SO_PRICE' => substr($n['MSPR_SLPRC'],0,1) =='.' ? '0'.$n['MSPR_SLPRC'] : $n['MSPR_SLPRC'],
                        'REQQT' => $r['DLVQTY'],
                        'PLTQT' => 1
                    ];
                    break;
                }
            }
        }
        #end
        die(json_encode(['data' => $rsplot, "rs" => $rs, "rsos" => $rsos]));
    }

}