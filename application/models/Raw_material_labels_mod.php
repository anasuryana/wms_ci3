<?php
class Raw_material_labels_mod extends CI_Model
{
    private $TABLENAME = "raw_material_labels";
    public function __construct()
    {
        $this->load->database();
    }

    public function selectAll()
    {
        $query = $this->db->from($this->TABLENAME)->get();
        return $query->result_array();
    }
    public function select_where($columns, $where)
    {
        $this->db->select($columns);
        $query = $this->db->from($this->TABLENAME)->where($where)->get();
        return $query->result_array();
    }

    public function check_Primary($data)
    {
        return $this->db->get_where($this->TABLENAME, $data)->num_rows();
    }

    public function updatebyId($pdata, $pkey)
    {
        $this->db->where('code', $pkey);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
    }

}
