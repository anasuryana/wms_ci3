<?php

class PNDSCN_mod extends CI_Model {
	private $TABLENAME = "PNDSCN_TBL";
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
	
	public function updatebyId($pdata, $pkeys)
    {        
        $this->db->where($pkeys);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
    }
    
    public function lastserialid(){       
        $qry = "select TOP 1 substring(PNDSCN_ID, 9, 9) lser from PNDSCN_TBL 
        WHERE convert(date, PNDSCN_LUPDT) = convert(date,GETDATE())
        ORDER BY convert(bigint,SUBSTRING(PNDSCN_ID,9,9)) desc";
        $query =  $this->db->query($qry);
        if ($query->num_rows()>0){
            $ret = $query->row();
            return $ret->lser;
        } else {
            return '0';
        }
    }

    public function selectby_filter($pwhere){
        $this->db->from($this->TABLENAME);        
        $this->db->where($pwhere);
        $this->db->order_by('PNDSCN_LUPDT DESC');
		$query = $this->db->get();
		return $query->result_array();
    }

    public function deleteby_filter($pwhere)
    {          
        $this->db->where($pwhere);
        $this->db->delete($this->TABLENAME);
        return $this->db->affected_rows();
    }    

    public function selectunsaved($pdo){        
        $qry = "SELECT * FROM $this->TABLENAME where (PNDSCN_DOC=?) AND (PNDSCN_SAVED IS NULL OR PNDSCN_SAVED='0' OR PNDSCN_SAVED='') ";
        $query = $this->db->query($qry, array($pdo));
		return $query->result_array();
    }

    public function select_grp_doc_item($pdoc){        
        $qry = "select PNDSCN_DOC,PNDSCN_ITMCD,PNDSCN_LOTNO,count(*) TTLBOX from PNDSCN_TBL
        WHERE  PNDSCN_DOC LIKE ?
        group by PNDSCN_DOC,PNDSCN_ITMCD,PNDSCN_LOTNO ";
        $query = $this->db->query($qry, array("%".$pdoc."%"));
		return $query->result_array();
    }
    public function select_grp_doc_item_byreff($preff){
        $qry = "select PNDSCN_DOC,PNDSCN_ITMCD,PNDSCN_LOTNO,count(*) TTLBOX from PNDSCN_TBL
        WHERE  PNDSCN_SER LIKE ?
        group by PNDSCN_DOC,PNDSCN_ITMCD,PNDSCN_LOTNO ";
        $query = $this->db->query($qry, array("%".$preff."%"));
		return $query->result_array();
    }
    public function select_fg_scn_vs_rls($pdoc){
        $qry = "select PNDSCN_DOC,PNDSCN_ITMCD,PNDSCN_LOTNO,MITM_ITMD1, PNDSCN_SER ,PNDSCN_QTY,ISNULL(RLSQTY,0) RLSQTY from PNDSCN_TBL
        INNER JOIN MITM_TBL ON PNDSCN_ITMCD=MITM_ITMCD
        LEFT JOIN (SELECT RLSSER_REFFDOC,RLSSER_REFFSER,SUM(RLSSER_QTY) RLSQTY FROM RLSSER_TBL WHERE RLSSER_REFFDOC = ? GROUP BY RLSSER_REFFDOC,RLSSER_REFFSER) vrls ON PNDSCN_DOC=RLSSER_REFFDOC AND PNDSCN_SER=RLSSER_REFFSER
        WHERE  PNDSCN_DOC = ? ";
        $query = $this->db->query($qry, array($pdoc, $pdoc));
		return $query->result_array();
    }
    public function select_fg_scn_vs_rls_with_reffno($pdoc,$preffno){        
        $qry = "select PNDSCN_DOC,PNDSCN_ITMCD,PNDSCN_LOTNO,MITM_ITMD1, PNDSCN_SER ,PNDSCN_QTY,ISNULL(RLSQTY,0) + ISNULL(RLSSER_QTY,0) RLSQTY from PNDSCN_TBL
        INNER JOIN MITM_TBL ON PNDSCN_ITMCD=MITM_ITMCD
        LEFT JOIN (SELECT A.RLSSER_REFFDOC,A.RLSSER_REFFSER,SUM(VXRLSQTY) RLSQTY 
		FROM RLSSER_TBL A 
		LEFT JOIN (SELECT SER_REFNO, RLSSER_QTY VXRLSQTY FROM SER_TBL LEFT JOIN RLSSER_TBL ON SER_ID=RLSSER_SER  WHERE SER_ID!=SER_REFNO) VX ON A.RLSSER_REFFSER=SER_REFNO
		WHERE RLSSER_REFFDOC = ? GROUP BY A.RLSSER_REFFDOC,A.RLSSER_REFFSER) vrls 		
		ON PNDSCN_DOC=RLSSER_REFFDOC AND PNDSCN_SER=RLSSER_REFFSER
		LEFT JOIN RLSSER_TBL ON PNDSCN_SER=RLSSER_SER AND PNDSCN_DOC=RLSSER_TBL.RLSSER_REFFDOC
        WHERE  PNDSCN_DOC = ?  and PNDSCN_SER=?";
        $query = $this->db->query($qry, [$pdoc, $pdoc, $preffno]);
		return $query->result_array();
    }
}