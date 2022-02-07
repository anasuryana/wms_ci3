<?php

class DLVRMSO_mod extends CI_Model {
	private $TABLENAME = "DLVRMSO_TBL";
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

    public function deleteby_filter($pwhere)
    {          
        $this->db->where($pwhere);
        $this->db->delete($this->TABLENAME);
        return $this->db->affected_rows();
    }

    public function updatebyId($pdata, $pkey)
    { 
        $this->db->where($pkey);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
    }

    public function select_lastline($pdoc){
        $qry = "SELECT MAX(DLVRMSO_LINE) lser FROM ".$this->TABLENAME." WHERE DLVRMSO_TXID=?";
        $query = $this->db->query($qry, [$pdoc]);
        if ($query->num_rows()>0){
            $ret = $query->row();
            return $ret->lser;
        } else {
            return 0;
        }
    }

    public function select_where($pcols, $pwhere){
        $this->db->from($this->TABLENAME);        
        $this->db->select($pcols);        
        $this->db->WHERE($pwhere);        
		$query = $this->db->get();
		return $query->result_array();
    }    

    public function select_invoice($ptxid){
        $qry = "SELECT VINV.*,MDEL_ZNAMA,MDEL_ADDRCUSTOMS,DLV_DATE, DLV_INVNO
        ,DLV_SMTINVNO,MCUS_CURCD,DLV_INVDT,DLV_NOPEN,DLV_ITMD1,DLV_ITMSPTNO FROM
        (select DLVRMSO_TXID,DLVRMSO_ITMID,RTRIM(MITM_ITMD1) MITM_ITMD1, DLVRMSO_PRPRC,sum(DLVRMSO_ITMQT) ITMQT, DLVRMSO_PRPRC* SUM(DLVRMSO_ITMQT) AMNT ,RTRIM(MITM_STKUOM) MITM_STKUOM
        from DLVRMSO_TBL LEFT JOIN MITM_TBL ON DLVRMSO_ITMID=MITM_ITMCD
        WHERE DLVRMSO_TXID=?
        group by DLVRMSO_ITMID,MITM_ITMD1, DLVRMSO_PRPRC,DLVRMSO_TXID,MITM_STKUOM) VINV        						
		LEFT JOIN (
        SELECT DLV_ID,MDEL_ZNAMA,MDEL_ADDRCUSTOMS,DLV_DATE,ISNULL(DLV_INVNO,'') DLV_INVNO
        ,ISNULL(DLV_SMTINVNO,'') DLV_SMTINVNO,RTRIM(MCUS_CURCD) MCUS_CURCD,DLV_INVDT,isnull(DLV_NOPEN,'') DLV_NOPEN,DLV_ITMCD,DLV_ITMD1,DLV_ITMSPTNO FROM DLV_TBL 
        LEFT JOIN MDEL_TBL ON DLV_CONSIGN=MDEL_DELCD
        LEFT JOIN MCUS_TBL ON DLV_CUSTCD=MCUS_CUSCD
        WHERE DLV_ID=?
        GROUP BY DLV_ID,MDEL_ZNAMA,MDEL_ADDRCUSTOMS,DLV_DATE,DLV_INVNO,DLV_SMTINVNO,MCUS_CURCD,DLV_INVDT,DLV_NOPEN,DLV_ITMCD,DLV_ITMD1,DLV_ITMSPTNO
        ) VH ON DLVRMSO_TXID=DLV_ID AND DLVRMSO_ITMID=DLV_ITMCD
        ORDER BY DLVRMSO_ITMID";
        $query = $this->db->query($qry, [$ptxid, $ptxid]);
        return $query->result_array();
    }
}