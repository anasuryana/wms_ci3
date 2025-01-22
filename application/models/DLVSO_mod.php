<?php

class DLVSO_mod extends CI_Model {
	private $TABLENAME = "DLVSO_TBL";
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

    public function updatebyVAR($pdata, $pwhere)
    {        
        $this->db->where($pwhere);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
    }

    public function deletebyID($parr){        
        $this->db->where($parr);
        $this->db->delete($this->TABLENAME);
        return $this->db->affected_rows();
    }

    public function select_SO($pwhere)
	{        
        $this->db->select("DLVSO_CPONO");
        $this->db->from($this->TABLENAME);
        $this->db->group_by('DLVSO_CPONO');
        $this->db->where($pwhere);
		$query = $this->db->get();
		return $query->result_array();
    }

    public function select_bytxid($ptxid) {
        $qry = "SELECT V1.*,DLVSO_CPONO,DLVSO_ITMCD,PLOTQTY FROM
        (select DLV_BSGRP,DLV_CONSIGN,DLV_CUSTCD,SER_ITMID,sum(DLV_QTY) DLVQTY  from DLV_TBL 
                left join SER_TBL on DLV_SER=SER_ID
                where DLV_ID=?
                group by SER_ITMID,DLV_BSGRP,DLV_CONSIGN,DLV_CUSTCD) V1
        LEFT JOIN 
        (
            SELECT DLVSO_DLVID,DLVSO_CPONO,DLVSO_ITMCD,SUM(DLVSO_QTY) PLOTQTY FROM DLVSO_TBL WHERE DLVSO_DLVID=?
            GROUP BY DLVSO_CPONO,DLVSO_DLVID,DLVSO_ITMCD
        ) V2 ON SER_ITMID=DLVSO_ITMCD
        WHERE ISNULL(PLOTQTY,0)>0";
        $query = $this->db->query($qry, [$ptxid, $ptxid]);
        return $query->result_array();
    }

    public function select_for_DO($ptxid) {
        $qry = "SELECT V1.*
                    ,DLVSO_CPONO
                    ,DLVSO_ITMCD
                    ,PLOTQTY
                    ,RTRIM(MITM_ITMD1) MITM_ITMD1
                    ,RTRIM(MITM_ITMD2) MITM_ITMD2
                    ,RTRIM(MITM_STKUOM) MITM_STKUOM
                    ,PLOTQTMAIN
                    ,ORDQT
                FROM (
                    SELECT DLV_BSGRP
                        ,DLV_CONSIGN
                        ,DLV_CUSTCD
                        ,SER_ITMID
                        ,sum(DLV_QTY) DLVQTY
                    FROM DLV_TBL
                    LEFT JOIN SER_TBL ON DLV_SER = SER_ID
                    WHERE DLV_ID = ?
                    GROUP BY SER_ITMID
                        ,DLV_BSGRP
                        ,DLV_CONSIGN
                        ,DLV_CUSTCD
                    ) V1
                INNER JOIN (
                    SELECT DLVSO_DLVID
                        ,DLVSO_CPONO
                        ,DLVSO_ITMCD
                        ,SUM(DLVSO_QTY) PLOTQTY
                    FROM DLVSO_TBL
                    WHERE DLVSO_DLVID = ?
                    GROUP BY DLVSO_CPONO
                        ,DLVSO_DLVID
                        ,DLVSO_ITMCD
                    ) V2 ON SER_ITMID = DLVSO_ITMCD
                LEFT JOIN (
                    SELECT VMSO.*
                        ,ISNULL(PLOTQT, 0) PLOTQTMAIN
                    FROM (
                        SELECT SO_NO
                            ,SO_ITEMCD
                            ,SUM(SO_ORDRQT) ORDQT
                        FROM SO_TBL
                        GROUP BY SO_NO
                            ,SO_ITEMCD
                        ) VMSO
                    LEFT JOIN (
                        SELECT DLVSO_CPONO
                            ,DLVSO_ITMCD
                            ,SUM(DLVSO_QTY) PLOTQT
                        FROM DLVSO_TBL
                        GROUP BY DLVSO_CPONO
                            ,DLVSO_ITMCD
                        ) VMSO_U ON VMSO.SO_NO = DLVSO_CPONO
                        AND SO_ITEMCD = DLVSO_ITMCD
                    ) VMAIN ON DLVSO_CPONO = SO_NO
                    AND VMAIN.SO_ITEMCD = V2.DLVSO_ITMCD
                LEFT JOIN MITM_TBL ON DLVSO_ITMCD = MITM_ITMCD
                ORDER BY DLVSO_ITMCD";
        $query = $this->db->query($qry, [$ptxid, $ptxid]);
        return $query->result_array();
    }
}