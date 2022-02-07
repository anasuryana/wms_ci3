<?php

class MCONA_mod extends CI_Model {
	private $TABLENAME = "MCONA_TBL";	
	public function __construct()
    {
        $this->load->database();
    }
	public function insert($data){
        $this->db->insert($this->TABLENAME,$data);
        return $this->db->affected_rows();
    }
    public function insertb($data)
    {        
        $this->db->insert_batch($this->TABLENAME,$data);
        return $this->db->affected_rows();
    }
    public function delete($pwhere){
        $this->db->where($pwhere);
        $this->db->delete($this->TABLENAME);
        return $this->db->affected_rows();
    }
    public function check_Primary($data)
    {        
        return $this->db->get_where($this->TABLENAME,$data)->num_rows();
    }
    public function lastserialid($pdoc){
        $qry = "select MAX(MCONA_LINE) lser from $this->TABLENAME 
        WHERE MCONA_DOC=?";
        $query =  $this->db->query($qry, [$pdoc]);  
        if ($query->num_rows()>0){
            $ret = $query->row();
            return $ret->lser;
        } else {
            return '0';
        }
    }
    public function update_where($pdata, $pwhere)
    {
        $this->db->where($pwhere);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();        
    }
    public function select_header($pLike){
        $this->db->select("MCONA_DOC,MCONA_DATE,MCONA_DUEDATE
        ,MAX(MCONA_BSGRP) MCONA_BSGRP
        ,MAX(MCONA_CUSCD) MCONA_CUSCD
        ,MAX(MCONA_KNDJOB) MCONA_KNDJOB
        ,MAX(MCONA_LCNSNUM) MCONA_LCNSNUM
        ,MAX(MCONA_LCNSDT) MCONA_LCNSDT
        ,MAX(MCUS_CURCD) MCUS_CURCD
        ,RTRIM(MAX(MCUS_CURCD)) MCUS_CURCD
        ,RTRIM(MAX(MCUS_CUSNM)) MCUS_CUSNM
        ");
		$this->db->from($this->TABLENAME);
		$this->db->like($pLike);
        $this->db->join("XMCUS", "MCONA_CUSCD=MCUS_CUSCD", "left");
        $this->db->group_by("MCONA_DOC,MCONA_DATE,MCONA_DUEDATE");
		$this->db->order_by('MCONA_DOC,MCONA_DATE,MCONA_DUEDATE');
		$query = $this->db->get();
		return $query->result_array();
    }
    public function select_detail($pLike){
        $this->db->select("MCONA_DOC,MCONA_DATE,MCONA_DUEDATE,MCONA_ITMCD
        ,MCONA_QTY,MCONA_LINE,MCONA_REMARK,MCONA_ITMTYPE,MCONA_PERPRC,MCONA_BALQTY");
		$this->db->from($this->TABLENAME);
		$this->db->like($pLike);        
		$this->db->order_by('MCONA_DOC,MCONA_DATE,MCONA_DUEDATE,MCONA_ITMCD');
		$query = $this->db->get();
		return $query->result_array();
    }

    public function select_1col($pcol) {
        $this->db->select($pcol);
        $this->db->from($this->TABLENAME);
        $this->db->where("MCONA_KNDJOB !=",'');
        $this->db->group_by($pcol);
        $this->db->order_by($pcol);
		$query = $this->db->get();
		return $query->result();
    }

    public function select_plot($pwhere) {
        $this->db->select("MCONA_BSGRP,MCONA_CUSCD,MCONA_DOC,MCONA_DATE");
        $this->db->from($this->TABLENAME);
        $this->db->where($pwhere);
        $this->db->group_by("MCONA_BSGRP,MCONA_CUSCD,MCONA_DOC,MCONA_DATE");
        $this->db->order_by("MCONA_DATE");
		$query = $this->db->get();
		return $query->result_array();
    }

    public function select_rm_report_m($doc) {
        $qry = "SELECT V1.*,RTRIM(MITM_ITMD1) MITM_ITMD1,MITM_HSCD,RTRIM(MITM_STKUOM) MITM_STKUOM  FROM
        (select MCONA_ITMCD,SUM(MCONA_QTY) MCONA_QTY from MCONA_TBL 
        WHERE MCONA_DOC=? AND MCONA_ITMTYPE='0'
        GROUP BY MCONA_ITMCD) V1 
        LEFT JOIN MITM_TBL ON MCONA_ITMCD=MITM_ITMCD";
        $query =  $this->db->query($qry, [$doc]);
        return $query->result_array();
    }

    public function select_rm_report_d($doc) {
        $qry = "SELECT V1.*,ISNULL(RCV_QTY,0) RCV_QTY,RCV_DONO,RCV_BCDATE,RCV_BCNO FROM
        (select MCONA_ITMCD,SUM(MCONA_QTY) MCONA_QTY from MCONA_TBL 
        WHERE MCONA_DOC=? AND MCONA_ITMTYPE='0'
        GROUP BY MCONA_ITMCD) V1 
        INNER JOIN
        (SELECT RCV_DONO,MAX(RCV_BCDATE) RCV_BCDATE,RCV_BCNO,RCV_ITMCD,SUM(RCV_QTY) RCV_QTY FROM RCV_TBL
        WHERE RCV_CONA=?
        GROUP BY RCV_ITMCD,RCV_DONO,RCV_BCNO) V2 ON V1.MCONA_ITMCD=RCV_ITMCD
        ORDER BY RCV_BCDATE,RCV_DONO";
        $query =  $this->db->query($qry, [$doc, $doc]);
        return $query->result_array();
    }

    public function select_fg_report_m($doc) {
        $qry = "SELECT V1.*,RTRIM(MITM_ITMD1) MITM_ITMD1,MITM_HSCD,RTRIM(MITM_STKUOM) MITM_STKUOM  FROM
        (select MCONA_ITMCD,SUM(MCONA_QTY) MCONA_QTY from MCONA_TBL 
        WHERE MCONA_DOC=? AND MCONA_ITMTYPE='1'
        GROUP BY MCONA_ITMCD) V1 
        LEFT JOIN MITM_TBL ON MCONA_ITMCD=MITM_ITMCD";
        $query =  $this->db->query($qry, [$doc]);
        return $query->result_array();
    }

    public function select_fg_report_d($doc) {
        $qry = "SELECT V1.*,ISNULL(DLV_QTY,0) DLV_QTY,DLV_ID,DLV_BCDATE,DLV_NOAJU  FROM
        (select MCONA_ITMCD,SUM(MCONA_QTY) MCONA_QTY from MCONA_TBL 
        WHERE MCONA_DOC=? AND MCONA_ITMTYPE='1'
        GROUP BY MCONA_ITMCD) V1 
        INNER JOIN
        (SELECT DLV_ID,MAX(DLV_BCDATE) DLV_BCDATE,DLV_NOAJU,SER_ITMID,SUM(DLV_QTY) DLV_QTY FROM DLV_TBL LEFT JOIN SER_TBL ON DLV_SER=SER_ID
        WHERE DLV_CONA=?
        GROUP BY SER_ITMID,DLV_ID,DLV_NOAJU) V2 ON V1.MCONA_ITMCD=SER_ITMID
        ORDER BY DLV_BCDATE,DLV_ID";
        $query =  $this->db->query($qry, [$doc, $doc]);
        return $query->result_array();
    }
}