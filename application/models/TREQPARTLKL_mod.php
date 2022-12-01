<?php

class TREQPARTLKL_mod extends CI_Model
{
    private $TABLENAME = "TReqPartLkl";
    private $DBUse;
    public function __construct()
    {
        $this->load->database();
        $this->DBUse = $this->load->database('SPAREPART', TRUE);
    }

    public function insert($data)
    {
        $this->DBUse->insert($this->TABLENAME, $data);
        return $this->DBUse->affected_rows();
    }
    public function check_Primary($data)
    {
        $DBUse = $this->load->database('SPAREPART', TRUE);
        return $DBUse->get_where($this->TABLENAME, $data)->num_rows();
    }

    public function selectall()
    {
        $this->DBUse->from($this->TABLENAME);
        $query = $this->DBUse->get();
        return $query->result();
    }

    public function selectall_ost()
    {
        $this->DBUse->from($this->TABLENAME);
        $this->DBUse->where("CPONO IS NULL", NULL, FALSE)->where("cStatus", 'OPEN');
        $query = $this->DBUse->get();
        return $query->result_array();
    }

    public function updatebyId($pwhere, $pval)
    {
        $this->DBUse->where($pwhere);
        $this->DBUse->update($this->TABLENAME, $pval);
        return $this->DBUse->affected_rows();
    }
}
