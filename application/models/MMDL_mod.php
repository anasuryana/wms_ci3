<?php
class MMDL_mod extends CI_Model {
	private $TABLENAME = "MMDL_TBL";
	public function __construct()
    {
        $this->load->database();
    }
    
    public function select_where($columns, $where = NULL)
	{
        $this->db->select($columns);
		$query = $this->db->from($this->TABLENAME)->where($where)->get();
		return $query->result_array();
    }
    public function select_all($columns)
	{
        $this->db->select($columns);
		$query = $this->db->from($this->TABLENAME)->get();
		return $query->result_array();
    }

    public function check_Primary($data)
    {        
        return $this->db->get_where($this->TABLENAME,$data)->num_rows();
	}

    public function insert($data)
    {        
        $this->db->insert($this->TABLENAME,$data);
        return $this->db->affected_rows();
    }

    public function delete_where($puser, $pdate, $where)
    { 
        $this->db->where($where);
        $this->db->update($this->TABLENAME, ['MITMSA_DELDT' => $pdate, 'MITMSA_DELBY' => $puser]);        
        return $this->db->affected_rows();
	}
}