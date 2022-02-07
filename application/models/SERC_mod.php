<?php

class SERC_mod extends CI_Model {
	private $TABLENAME = "SERC_TBL";
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

    public function select_job_group($pnewid){
        $this->db->select('SERC_COMJOB');
        $this->db->from($this->TABLENAME);
        $this->db->where("SERC_NEWID", $pnewid);
        $this->db->group_by("SERC_COMJOB");
		$query = $this->db->get();
		return $query->result_array();
    }
    public function select_cols_where_id($pcols, $pid){
        $this->db->select($pcols);
        $this->db->from($this->TABLENAME);
        $this->db->where("SERC_NEWID", $pid);        
		$query = $this->db->get();
		return $query->result_array();
    }

    public function select_combined_jobs_definition($pjob){
        $this->db->select('SERC_COMJOB');
        $this->db->from($this->TABLENAME);
        $this->db->join("SER_TBL", "SERC_NEWID=SER_ID", "LEFT");
        $this->db->where("SER_DOC", $pjob);
        $this->db->group_by("SERC_COMJOB");
		$query = $this->db->get();
		return $query->result_array();
    }
    public function select_combined_jobs_definition_byreff($preff){
        $this->db->select('SERC_COMJOB');
        $this->db->from($this->TABLENAME);
        $this->db->join("SER_TBL", "SERC_NEWID=SER_ID", "LEFT");
        $this->db->where("SER_ID", $preff);
        $this->db->group_by("SERC_COMJOB");
		$query = $this->db->get();
		return $query->result_array();
    }

    public function select_exact_byVAR($pwhere)
	{
        $this->db->select('MCUS_CUSNM,MBOM_GRADE,PDPP_BSGRP');
        $this->db->from($this->TABLENAME . " a");        
        $this->db->join('XWO c', 'a.SERC_COMJOB=c.PDPP_WONO', 'LEFT');
        $this->db->join('MCUS_TBL d', 'c.PDPP_CUSCD=d.MCUS_CUSCD', 'LEFT');
        $this->db->where($pwhere);
		$query = $this->db->get();
		return $query->result_array();
    }
    
    public function select_byVAR($pwhere)
	{
        $this->db->select('a.*,MCUS_CUSNM,MBOM_GRADE');
        $this->db->from($this->TABLENAME . " a");        
        $this->db->join('XWO c', 'a.SERC_COMJOB=c.PDPP_WONO', 'LEFT');
        $this->db->join('MCUS_TBL d', 'c.PDPP_CUSCD=d.MCUS_CUSCD', 'LEFT');
        $this->db->like($pwhere);
		$query = $this->db->get();
		return $query->result_array();
    }
}