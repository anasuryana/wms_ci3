<?php

class DLVPRC_mod extends CI_Model {
	private $TABLENAME = "DLVPRC_TBL";
	public function __construct()
    {
        $this->load->database();
    }
	
	public function insert($data)
    {        
        $this->db->insert($this->TABLENAME,$data);
        return $this->db->affected_rows();
    }
	public function insertb($data)
    {        
        $this->db->insert_batch($this->TABLENAME,$data);
        return $this->db->affected_rows();
    }
    
	public function check_Primary($data)
    {        
        return $this->db->get_where($this->TABLENAME,$data)->num_rows();
    }

    public function deleteby_filter($pwhere)
    {
        $this->db->where($pwhere);
        $this->db->delete($this->TABLENAME);
        return $this->db->affected_rows();
    }

    public function select_deliv_invo($pdate1, $pdate2, $bigrup) {
		$qry = "SELECT ITH_DATEC,ITH_DOC,DLV_CUSTDO,DLV_CONSIGN,NOMAJU,NOMPEN,INVDT,DLV_INVNO,DLV_SMTINVNO,ITH_ITMCD,ITMDESCD,DLVPRC_CPO,DLVPRC_QTY,DLVPRC_PRC,(DLVPRC_QTY*DLVPRC_PRC) AMOUNT  FROM 
		(select ITH_DATEC,max(DLV_CONSIGN) DLV_CONSIGN,max(DLV_INVDT) INVDT,ITH_ITMCD,MAX(RTRIM(MITM_ITMD1)) ITMDESCD,ITH_DOC,ABS(SUM(ITH_QTY)) DELQT,MAX(DLV_INVNO) DLV_INVNO, MAX(DLV_SMTINVNO) DLV_SMTINVNO,MAX(DLV_ZNOMOR_AJU) NOMAJU,max(DLV_NOPEN) NOMPEN,MAX(DLV_CUSTDO) DLV_CUSTDO from v_ith_tblc 
		LEFT JOIN DLV_TBL ON ITH_SER=DLV_SER
		LEFT JOIN MITM_TBL ON ITH_ITMCD=MITM_ITMCD
		where (ITH_DATEC between ? and ?) and ITH_WH='ARSHP' AND ITH_FORM='OUT-SHP-FG'
		AND DLV_BSGRP IN ($bigrup)		
		GROUP BY ITH_ITMCD,ITH_DOC,ITH_DATEC,MITM_ITMD1) VDELV
		LEFT JOIN
		(
		SELECT DLVPRC_TXID,SER_ITMID,DLVPRC_PRC,SUM(DLVPRC_QTY) DLVPRC_QTY,DLVPRC_CPO FROM DLVPRC_TBL 
		LEFT JOIN SER_TBL ON DLVPRC_SER=SER_ID
		GROUP BY DLVPRC_TXID,SER_ITMID,DLVPRC_PRC,DLVPRC_CPO
		) VPRC ON ITH_ITMCD=SER_ITMID AND ITH_DOC=DLVPRC_TXID
		ORDER BY ITH_DATEC, ITH_DOC, ITH_ITMCD";
		$query = $this->db->query($qry, [$pdate1, $pdate2]);
		return $query->result_array();
	}
}