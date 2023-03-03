<?php

class DLVRMDOC_mod extends CI_Model
{
    private $TABLENAME = "DLVRMDOC_TBL";
    public function __construct()
    {
        $this->load->database();
    }

    public function insert($data)
    {
        $this->db->insert($this->TABLENAME, $data);
        return $this->db->affected_rows();
    }

    public function insertb($data)
    {
        $this->db->insert_batch($this->TABLENAME, $data);
        return $this->db->affected_rows();
    }

    public function deleteby_filter($pwhere)
    {
        $this->db->where($pwhere);
        $this->db->delete($this->TABLENAME);
        return $this->db->affected_rows();
    }

    public function updatebyId($pdata, $pkey)
    {
        $this->db->where($pkey);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
    }

    public function select_lastline($pdoc)
    {
        $qry = "SELECT MAX(DLVRMDOC_LINE) lser FROM " . $this->TABLENAME . " WHERE DLVRMDOC_TXID=?";
        $query = $this->db->query($qry, [$pdoc]);
        if ($query->num_rows() > 0) {
            $ret = $query->row();
            return $ret->lser;
        } else {
            return 0;
        }
    }

    public function select_where($pcols, $pwhere)
    {
        $this->db->from($this->TABLENAME);
        $this->db->select($pcols);
        $this->db->join('(SELECT RCV_DONO,RCV_RPNO,RCV_BCDATE,MAX(RCV_BCTYPE) RCV_BCTYPE FROM RCV_TBL GROUP BY RCV_DONO,RCV_RPNO,RCV_BCDATE) VRCV', "DLVRMDOC_AJU=RCV_RPNO AND DLVRMDOC_DO=RCV_DONO", "LEFT");
        $this->db->WHERE($pwhere);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_invoice($ptxid)
    {
        $qry = "wms_sp_invoice_rm_rtn ?";
        $query = $this->db->query($qry, [$ptxid]);
        return $query->result_array();
    }
    public function select_invoice_posting($ptxid)
    {
        $qry = "wms_sp_select_invoice_posting ?";
        $query = $this->db->query($qry, [$ptxid]);
        return $query->result_array();
    }
}
