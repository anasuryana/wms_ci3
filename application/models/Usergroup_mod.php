<?php 
class Usergroup_mod extends CI_Model {
    private $TABLENAME = 'MSTGRP_TBL';
    public function __construct()
    {
        $this->load->database();
    }

    public function insert($data)
    {        
        return $this->db->insert($this->TABLENAME,$data);
    }

    public function selectAll()
    {
        $this->db->order_by('MSTGRP_NM asc');
        $query = $this->db->get($this->TABLENAME);
        return $query->result_array();
    }

    public function selectbyID($pid)
    {        
        $this->db->where('MSTGRP_ID', $pid);
        $query = $this->db->get($this->TABLENAME);
        return $query->result_array();
    }

    public function cek_login($where)
    {
        $query = $this->db->get_where($this->TABLENAME,$where);
        return $query->result_array();
    }

    public function check_Primary($data)
    {       
        return $this->db->get_where($this->TABLENAME,$data)->num_rows();
    }

    public function updatebyId($pdata, $pkey)
    {
        $this->db->where('MSTGRP_ID', $pkey);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
    }
}
