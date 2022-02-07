<?php

class RPSAL_INVENTORY_mod extends CI_Model {
	private $TABLENAME = "RPSAL_INVENTORY";
    private $DBUse;
	public function __construct()
    {
        $this->load->database();
        $this->DBUse = $this->load->database('rpcust',TRUE);
    }	

    public function insert($data)
    {   
        
        $this->DBUse->insert($this->TABLENAME,$data);        
        return $this->DBUse->affected_rows();
    }

    public function insertb($data)
    {        
        $this->DBUse->insert_batch($this->TABLENAME,$data);
        return $this->DBUse->affected_rows();
    }

    public function check_Primary($data)
    {
        return $this->DBUse->get_where($this->TABLENAME,$data)->num_rows();
    }

    public function select_compare_where($pwhere)
	{
        $this->DBUse->select("INV_ITMNUM,SUM(INV_QTY) INV_QTY");
        $this->DBUse->from($this->TABLENAME);
        $this->DBUse->where($pwhere);
        $this->DBUse->group_by("INV_ITMNUM");
		$query = $this->DBUse->get();
		return $query->result_array();
    }

    public function updatebyVAR($pdata, $pwhere)
    {        
        $this->DBUse->where($pwhere);
        $this->DBUse->update($this->TABLENAME, $pdata);
        return $this->DBUse->affected_rows();
    }
}