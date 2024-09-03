<?php

class ENGBOMSTX_mod extends CI_Model {
	private $TABLENAME = "ENG_BOMSTX";
	public function __construct()
    {
        $this->load->database();
    }

    public function check_Primary($data)
    {
        return $this->db->get_where($this->TABLENAME,$data)->num_rows();
    }

    public function selectColumnsWhere($pColumns,$pwhere)
	{
        $this->db->select($pColumns);
        $this->db->from($this->TABLENAME);
        $this->db->where($pwhere);
		$query = $this->db->get();
		return $query->result_array();
    }

    public function selectColumnsWhereModelInAndPartIn($pColumns,$pModels,$pParts)
	{
        $this->db->select($pColumns);
        $this->db->from($this->TABLENAME);
        $this->db->where_in('MODEL_CODE', $pModels);
        $this->db->where_in('MAIN_PART_CODE	',$pParts);
        $query = $this->db->group_start()
            ->where('SUB !=', '', NULL)            
            ->or_where('SUB1 !=', '', NULL)
            ->group_end()
            ->get();
		return $query->result_array();
    }
}