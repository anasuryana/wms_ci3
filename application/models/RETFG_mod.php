<?php

class RETFG_mod extends CI_Model {
	private $TABLENAME = "RETFG_TBL";
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

    public function select_cols_where($pcols, $pwhere){
        $this->db->from($this->TABLENAME);
        $this->db->join('MITM_TBL', 'RETFG_ITMCD=MITM_ITMCD', 'LEFT');
        $this->db->join('XVU_RTN', 'RETFG_DOCNO=STKTRND1_DOCNO', 'LEFT');
        $this->db->join('XMCUS', 'RETFG_SUPCD=MCUS_CUSCD', 'LEFT');
        $this->db->join('MSUP_TBL', 'RETFG_SUPCD=MSUP_SUPCD', 'LEFT');
        $this->db->select($pcols);
        $this->db->WHERE($pwhere);        
		$query = $this->db->get();
		return $query->result_array();
    }
    public function select_h_cols_like($plike){
        $this->db->from($this->TABLENAME);        
        $this->db->join('XVU_RTN', 'RETFG_DOCNO=STKTRND1_DOCNO');        
        $this->db->select("RETFG_STRDOC, RETFG_PLANT, RTRIM(MBSG_BSGRP) STKTRND1_BSGRP,RTRIM(STKTRND1_DOCNO) STKTRND1_DOCNO, RTRIM(MBSG_DESC) MBSG_DESC");
        $this->db->like($plike);
        $this->db->group_by("RETFG_STRDOC, RETFG_PLANT, MBSG_BSGRP,STKTRND1_DOCNO, MBSG_DESC");
		$query = $this->db->get();
		return $query->result_array();
    }

    public function lastserialid($pdoc){
        $qry = "select MAX(RETFG_LINE) lser from $this->TABLENAME 
        WHERE RETFG_DOCNO=?";
        $query =  $this->db->query($qry, [$pdoc]);  
        if ($query->num_rows()>0){
            $ret = $query->row();
            return $ret->lser;
        } else {
            return '0';
        }
    }

    public function delete_where($pwhere)
    {          
        $this->db->where($pwhere);
        $this->db->delete($this->TABLENAME);
        return $this->db->affected_rows();
    }

    public function select_lastnodo_patterned($pmonth, $pyear){
        $qry = "select ISNULL(MAX(CONVERT(INT,SUBSTRING(RETFG_STRDOC,1,4))),0)  lser
        from RETFG_TBL where MONTH(RETFG_STRDT) =? and YEAR(RETFG_STRDT)=? ";
        $query =  $this->db->query($qry, [$pmonth, $pyear]);        
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

    public function select_businessgroup(){
        $qry = "SELECT MBSG_BSGRP,MBSG_DESC  FROM XVU_RTN
        inner join (SELECT RETFG_DOCNO FROM RETFG_TBL b GROUP BY RETFG_DOCNO) v2 ON STKTRND1_DOCNO=v2.RETFG_DOCNO
        Group by MBSG_BSGRP,MBSG_DESC ORDER BY 1";
        $query = $this->db->query($qry);
        return $query->result();
    }

    public function selec_where_group($coulmns,$groupColumn,$where){
        $this->db->select($coulmns);
        $this->db->from($this->TABLENAME);
        $this->db->where($where);
        $this->db->group_by($groupColumn);
		$query = $this->db->get();
		return $query->result();
    }
}