<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TXBridge {
	protected $CI;
	public function __construct()
	{
		$this->CI = &get_instance();
		$this->CI->load->library('session');
		$this->CI->load->model('XITRN_mod');
        $this->CI->load->model('ITH_mod');
	}

    public function syncParentDocument($data) {
		$prefixList = ['TRF','ADJ'];
		$doc = $data['DOC'];
		$doc = strtoupper($doc);
		$rs = in_array(substr($doc,0,3), $prefixList)  ? $this->CI->XITRN_mod->select_where(['RTRIM(ITRN_ITMCD) ITRN_ITMCD'
				, 'RTRIM(MITM_ITMD1) MITM_ITMD1'
				, 'RTRIM(MITM_SPTNO) MITM_SPTNO'
				, 'RTRIM(ITRN_REFNO1) ITRN_REFNO1'
				, 'RTRIM(ITRN_LOCCD) ITRN_LOCCD'
				, 'IOQT'
				, 'CONVERT(date,ITRN_ISUDT) ITRN_ISUDT'
				, 'RTRIM(ITRN_USRID) USRID'
				]
				,['ITRN_DOCNO' => $doc]) : [];
		$rsSave = [];
		foreach($rs as $r) {
			$rsSave[] = [
				'ITH_ITMCD' => $r['ITRN_ITMCD']
				,'ITH_DATE' => $r['ITRN_ISUDT']
				,'ITH_FORM' => $r['IOQT']*1 > 0 ? 'TRFIN-RM' : 'TRFOUT-RM'
				,'ITH_DOC' => $doc
				,'ITH_QTY' => 1*$r['IOQT']
				,'ITH_WH' => $r['ITRN_LOCCD']
				,'ITH_REMARK' => $r['ITRN_REFNO1']
				,'ITH_LUPDT' => $r['ITRN_ISUDT']." 09:00:00"
				,'ITH_USRID' => $r['USRID']
			];
		}
        if($this->CI->ITH_mod->check_Primary(['ITH_DOC' => $doc])) {
            $myar = ['cd' => '1', 'msg' => 'Already Inserted'];
        } else {
            if(!empty($rsSave)) {
                $myar = $this->CI->ITH_mod->insertb($rsSave) ? ['cd' => '1', 'msg' => 'Inserted'] : ['cd' => '0', 'msg' => 'Sorry'];
            } else {
                $myar = ['cd' => '0', 'msg' => 'nothing to be inserted'];
            }
        }
		return ['data' => $rsSave, 'status' => $myar];
	}
}