<?php

class TPB_HEADER_imod extends CI_Model {
	private $TABLENAME = "tpb_header";
	public function __construct()
    {
        $this->load->database();
	}
	
	public function insert($data)
    {
		$DBUse = $this->load->database('ceisadb',TRUE);
        $DBUse->insert($this->TABLENAME,$data);
        return $DBUse->insert_id();
	}
	
	public function selectAll()
	{		
        $DBUse = $this->load->database('ceisadb',TRUE);        
		$query = $DBUse->get($this->TABLENAME);
		return $query->result_array();
	}

	public function select_newid(){
		$DBUse = $this->load->database('ceisadb',TRUE);  
		
        $DBUse->from($this->TABLENAME);  
        $DBUse->select('MAX(ID) ID');
		$query = $DBUse->get();
		// return $query->result();

		if ($query->num_rows()>0){
            $ret = $query->row();
            return $ret->ID;
        } else {
            return '0';
        }
	}

	public function select_where($pcols, $pwhere){
		$DBUse = $this->load->database('ceisadb',TRUE);
		$DBUse->select($pcols);
        $DBUse->from($this->TABLENAME);
        $DBUse->where($pwhere);
		$query = $DBUse->get();
		return $query->result_array();
	}

	public function check_Primary($data)
    {
		$DBUse = $this->load->database('ceisadb',TRUE);
        return $DBUse->get_where($this->TABLENAME,$data)->num_rows();
    }

	public function deleteby_filter($pwhere)
    {        
        $DBUse = $this->load->database('ceisadb',TRUE);   
        $DBUse->where($pwhere);
        $DBUse->delete($this->TABLENAME);
        return $DBUse->affected_rows();
    }
}