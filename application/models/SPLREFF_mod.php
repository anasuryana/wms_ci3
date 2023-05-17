<?php

class SPLREFF_mod extends CI_Model {
	private $TABLENAME = "SPLREFF_TBL";
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
    public function selectby_filter_like($pwhere){	
        $this->db->limit(2500);
        $this->db->from($this->TABLENAME);        
        $this->db->like($pwhere);
        $this->db->select("SPLREFF_DOC SPLSCN_DOC , SPLREFF_ITMCAT SPLSCN_CAT, SPLREFF_LINEPRD SPLSCN_LINE,SPLREFF_FEDR SPLSCN_FEDR , SPLREFF_MCZ SPLSCN_ORDERNO, SPLREFF_ACT_PART SPLSCN_ITMCD, SPLREFF_ACT_LOTNUM SPLSCN_LOTNO
        , SPLREFF_ACT_QTY SPLSCN_QTY, SPLREFF_CREATEDAT SPLSCN_LUPDT");
        $this->db->order_by('SPLREFF_CREATEDAT DESC,SPLREFF_DOC ASC, SPLREFF_ITMCAT ASC, SPLREFF_LINEPRD ASC, SPLREFF_FEDR ASC, SPLREFF_MCZ ASC , SPLREFF_REQ_PART ASC');
		$query = $this->db->get();
		return $query->result_array();
    }    
    public function check_Primary($data)
    {        
        return $this->db->get_where($this->TABLENAME,$data)->num_rows();
    }
    public function select_maxline($pwhere) {
        $this->db->select("max(SPLREFF_LINE) MAXLINE");
        $this->db->where($pwhere);
        $this->db->from($this->TABLENAME);
        $query = $this->db->get();
        if ($query->num_rows()>0){
            $ret = $query->row();
            return $ret->MAXLINE;
        } else {
            return 0;
        }
        return $query->result_array();
    }

    public function select_mainsub($psnlist = []){
        $this->db->from($this->TABLENAME);
        $this->db->where_in("SPLREFF_DOC", $psnlist);
        $this->db->select("SPLREFF_REQ_PART,SPLREFF_ACT_PART");
        $this->db->group_by("SPLREFF_REQ_PART,SPLREFF_ACT_PART");
		$query = $this->db->get();
		return $query->result_array();
    }
    public function select_mainsub_mega($psnlist = []){
        $this->db->from("XPPSN2");
        $this->db->join("(select PPNS1_PSNNO,MAX(PPSN1_OPSNNO) PPSN1_OPSNNO from XPPSN1 GROUP BY PPNS1_PSNNO) PPSN1","PPSN2_PSNNO=PPSN1_PSNNO","LEFT");
        $this->db->where_in("PPSN1_OPSNNO", $psnlist);
        $this->db->select("RTRIM(PPSN2_SUBPN) PPSN2_SUBPN,PPSN2_ACTQT");        
		$query = $this->db->get();
		return $query->result_array();
    }

    public function select_forcalculation($psnlist = []){
        $this->db->from($this->TABLENAME);
        $this->db->where_in("SPLREFF_DOC", $psnlist);
        $this->db->select("'' AS PPSN2_DATANO, SPLREFF_FEDR AS SPLSCN_FEDR
        ,SPLREFF_ACT_PART AS SPLSCN_ITMCD
        ,SPLREFF_ACT_QTY AS SPLSCN_QTY
        ,SPLREFF_ACT_LOTNUM AS SPLSCN_LOTNO
        ,SPLREFF_MCZ AS SPLSCN_ORDERNO
        ,SPLREFF_LINEPRD AS SPLSCN_LINE
        ,'' AS PPSN2_MC
        ,'' AS PPSN2_PROCD
        ,SPLREFF_ITMCAT AS SPLSCN_CAT
        ,SPLREFF_DOC AS SPLSCN_DOC
        ,SPLREFF_CREATEDAT SCNTIME
        ,'' AS PPSN2_MSFLG");        
		$query = $this->db->get();
		return $query->result_array();
    }
    public function select_additional_PSN($psnlist = []){
        $this->db->from("XPPSN1");
        $this->db->join("XPPSN2", "PPSN1_PSNNO=PPSN2_PSNNO");
        $this->db->where_in("PPSN1_OPSNNO", $psnlist);
        $this->db->select("PPSN2_DATANO
        ,RTRIM(ISNULL(PPSN1_FR,'')) SPLSCN_FEDR
        ,RTRIM(ISNULL(PPSN2_SUBPN,'')) SPLSCN_ITMCD
        ,ISNULL(PPSN2_ACTQT,0) SPLSCN_QTY
        ,'' SPLSCN_LOTNO
        ,RTRIM(ISNULL(PPSN2_MCZ,'')) SPLSCN_ORDERNO
        ,RTRIM(ISNULL(PPSN1_LINENO,'')) SPLSCN_LINE
        ,RTRIM(ISNULL(PPSN2_MC,'')) PPSN2_MC
        ,RTRIM(ISNULL(PPSN2_PROCD,'')) PPSN2_PROCD
        ,RTRIM(ISNULL(PPSN2_ITMCAT,'')) SPLSCN_CAT
        ,RTRIM(ISNULL(PPSN2_PSNNO,'')) SPLSCN_DOC
        ,'' SCNTIME
        ,RTRIM(ISNULL(PPSN2_MSFLG,'')) PPSN2_MSFLG");
		$query = $this->db->get();
		return $query->result_array();
    }

    public function select_saved($psnlist = []){
        $this->db->from($this->TABLENAME);
        $this->db->where_in("SPLREFF_DOC", $psnlist);
        $this->db->select("SPLREFF_REQ_PART, A.MITM_SPTNO ADESC, SPLREFF_ITMCAT,SPLREFF_LINEPRD,SPLREFF_FEDR,SPLREFF_MCZ
        ,SPLREFF_REQ_QTY,SPLREFF_ACT_PART, B.MITM_SPTNO BDESC, SPLREFF_ACT_QTY,SPLREFF_ACT_LOTNUM,SPLREFF_LINE");
        $this->db->join("MITM_TBL A","SPLREFF_REQ_PART=A.MITM_ITMCD");
        $this->db->join("MITM_TBL B","SPLREFF_ACT_PART=B.MITM_ITMCD");
		$query = $this->db->get();
		return $query->result_array();
    }

    public function select_to_ith($psnlist = []){
        $this->db->from($this->TABLENAME);
        $this->db->where_in("SPLREFF_DOC", $psnlist)->where("SPLREFF_SAVED IS NULL", NULL, FALSE);
        $this->db->select("SPLREFF_DATE, SPLREFF_DOC,SPLREFF_ACT_PART,SPLREFF_ITMCAT,SPLREFF_LINEPRD,SPLREFF_FEDR,SUM(SPLREFF_ACT_QTY) TTLSCN,SPL_BG,WHOUT");
        $this->db->join("(select SPL_DOC,SPL_BG,CASE 
        WHEN SPL_BG='PSI1PPZIEP' THEN 'ARWH1'
        WHEN SPL_BG='PSI2PPZADI' THEN 'ARWH2'
        WHEN SPL_BG='PSI2PPZSTY' THEN 'ARWH2'
        WHEN SPL_BG='PSI2PPZOMI' THEN 'ARWH2'
        WHEN SPL_BG='PSI2PPZTDI' THEN 'ARWH2'
        WHEN SPL_BG='PSI2PPZINS' THEN 'NRWH2'
        WHEN SPL_BG='PSI2PPZOMC' THEN 'NRWH2'			
        WHEN SPL_BG='PSI2PPZSSI' THEN 'NRWH2'
        END AS WHOUT from SPL_TBL
        group by SPL_DOC,SPL_BG) VSPL_BG", "SPLREFF_DOC=SPL_DOC", "LEFT");
        $this->db->group_by("SPLREFF_DATE, SPLREFF_DOC,SPLREFF_ACT_PART,SPLREFF_ITMCAT,SPLREFF_LINEPRD,SPLREFF_FEDR,SPL_BG,WHOUT");
        $query = $this->db->get();
		return $query->result_array();
    }

    public function updatebyVAR($pdata, $pwhere)
    {        
        $this->db->where($pwhere);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
    }
}