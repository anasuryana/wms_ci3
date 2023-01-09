<?php

class MSPP_mod extends CI_Model
{
    private $TABLENAME = "XMSPP_HIS";
    public function __construct()
    {
        $this->load->database();
    }
    public function selectAll()
    {
        $query = $this->db->get($this->TABLENAME);
        return $query->result_array();
    }
    public function select_byvar_for_calc($passycode, $pjob)
    {
        $qry = "wms_sp_fg ?, ?";
        $query = $this->db->query($qry, [$passycode, $pjob]);
        return $query->result_array();
    }
    public function select_byvar($pwhere)
    {
        $this->db->select('A.*, RTRIM(XSUB.MITM_SPTNO) XSUBNAME');
        $this->db->from($this->TABLENAME . " A");
        $this->db->join('MITM_TBL XSUB', 'MSPP_SUBPN=XSUB.MITM_ITMCD', 'LEFT');
        $this->db->where($pwhere);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_byvar_group_num_rows($pwhere)
    {
        $this->db->group_by("MSPP_BOMPN, MSPP_SUBPN");
        $this->db->from($this->TABLENAME);
        $this->db->where($pwhere);
        $this->db->select('MSPP_BOMPN, MSPP_SUBPN');
        $query = $this->db->get();
        return $query->num_rows();
    }
    public function select_all_byvar_group($pwhere)
    {
        $this->db->group_by("MSPP_BOMPN, MSPP_SUBPN");
        $this->db->from($this->TABLENAME);
        $this->db->where($pwhere);
        $this->db->select('MSPP_BOMPN, MSPP_SUBPN');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function select_all_byvar_ENG($pwhere)
    {
        $this->db->from("ENG_STXSPL");
        $this->db->where($pwhere);
        $this->db->select("REPLACE(PRTCD_M,'-','') MSPP_BOMPN,REPLACE(PRTCD_S,'-','') MSPP_SUBPN");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function check_Primary($data)
    {
        return $this->db->get_where($this->TABLENAME, $data)->num_rows();
    }

    public function select_sub($pbompn, $pmdl)
    {
        $qry = "select MSPP_SUBPN
		from XMSPP_HIS where MSPP_BOMPN=? AND MSPP_BOMPN!=MSPP_SUBPN and MSPP_MDLCD=?
		group by MSPP_BOMPN,MSPP_SUBPN";
        $query = $this->db->query($qry, [$pbompn, $pmdl]);
        return $query->result_array();
    }
}
