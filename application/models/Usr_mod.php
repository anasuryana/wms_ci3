<?php 
class Usr_mod extends CI_Model {
    private $TABLENAME = 'MSTEMP_TBL';
    public function __construct()
    {
        $this->load->database();
    }

    public function insert($data)
    {        
        $this->db->insert($this->TABLENAME,$data);
        return $this->db->affected_rows();
    }

    public function check_Primary($data)
    {       
        return $this->db->get_where($this->TABLENAME,$data)->num_rows();
    }

    public function selectAll_reged()
    {
        $this->db->select('MSTEMP_ID,MSTEMP_FNM,MSTEMP_LNM,MSTEMP_REGTM,MSTEMP_GRP,MSTGRP_NM,MSTEMP_STS');
        $this->db->from($this->TABLENAME);
        $this->db->join('MSTGRP_TBL', 'MSTGRP_TBL.MSTGRP_ID='.$this->TABLENAME.'.MSTEMP_GRP');
        $this->db->where('MSTEMP_ID !=', 'ane')->where('MSTEMP_ID !=', 'ane_')->where('MSTEMP_STS',1);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function selectAll()
    {
        $query = $this->db->get($this->TABLENAME);
        return $query->result_array();
    }

    public function cek_login($where)
    {
        $query = $this->db->get_where($this->TABLENAME,$where);
        return $query->result_array();
    }


    public function cek_pw($where)
    {
        $query = $this->db->get_where($this->TABLENAME,$where);
        return $query->num_rows();
    }

    public function updatepassword($pdata, $pkey)
    {
        $this->db->where($pkey);
        $this->db->update($this->TABLENAME,$pdata);
        return $this->db->affected_rows();
    }

    function selectunapproved(){
        $qry  = "select * from ".$this->TABLENAME." where coalesce(MSTEMP_ACTSTS,0)=0";
        $query = $this->db->query($qry);
        return $query->result_array();
        
    }

    public function updatebyId($pdata, $pkey)
    {
        $this->db->where('MSTEMP_ID', $pkey);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
    }

    public function selectbyName($pkey){
        $this->db->select('MSTEMP_ID,MSTEMP_FNM,MSTEMP_LNM,MSTGRP_NM');
        $this->db->from($this->TABLENAME);
        $this->db->join('MSTGRP_TBL', 'MSTGRP_TBL.MSTGRP_ID='.$this->TABLENAME.'.MSTEMP_GRP');
        $this->db->like('lower(MSTEMP_FNM)',strtolower($pkey));
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectNameMany($pwhere){
        $qry = "select MSTEMP_ID,concat(MSTEMP_FNM, ' ' , MSTEMP_LNM) NAMA from ".$this->TABLENAME." 
        where MSTEMP_ID in (".$pwhere.")";
        $query = $this->db->query($qry);
        return $query->result_array();
    }

    public function select_NPSI_user_where($pwhere){
        $this->db->select('MSTEMP_FNM,MSTEMP_LNM,PSIDEPT');
        $this->db->from("VWMSPSI_USERS");
        $this->db->where($pwhere);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_kitting($pyear){
        $qry = "SELECT YEARMONTH,CONCAT(MSTEMP_FNM,' ',MSTEMP_LNM) FULLNAME,count(*) TTLSCN FROM
        (select SPLSCN_USRID,concat(year(SPLSCN_LUPDT),'-',RIGHT(CONCAT('00',MONTH(SPLSCN_LUPDT)),2) ) YEARMONTH from  SPLSCN_TBL WHERE SPLSCN_USRID NOT IN ('1','ane') AND YEAR(SPLSCN_LUPDT) = ?) V1
        LEFT JOIN MSTEMP_TBL ON V1.SPLSCN_USRID=MSTEMP_ID
        GROUP BY YEARMONTH,SPLSCN_USRID,MSTEMP_FNM,MSTEMP_LNM
        ORDER BY YEARMONTH,TTLSCN DESC";
        $query = $this->db->query($qry, [$pyear]);
        return $query->result_array();
    }

}