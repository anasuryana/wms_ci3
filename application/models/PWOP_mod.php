<?php

class PWOP_mod extends CI_Model {
	private $TABLENAME = "XPWOP";
	public function __construct()
    {
        $this->load->database();
    }

	public function check_Primary($data)
    {        
        return $this->db->get_where($this->TABLENAME,$data)->num_rows();
    }

    public function select_exact_byVAR($pwhere)
	{
        $this->db->select('RTRIM(MITM_ITMCD) MITM_ITMCD,RTRIM(MITM_ITMD1) MITM_ITMD1');
        $this->db->from($this->TABLENAME . " a");
        $this->db->join('MITM_TBL', 'PWOP_BOMPN=MITM_ITMCD','LEFT');
        $this->db->group_by('MITM_ITMCD,MITM_ITMD1');
        $this->db->where($pwhere);
		$query = $this->db->get();
		return $query->result_array();
    }

    public function select_mainsub($pwo){
        $this->db->select('RTRIM(PWOP_BOMPN) PWOP_BOMPN,RTRIM(PWOP_SUBPN) PWOP_SUBPN');
        $this->db->from($this->TABLENAME . " a");        
        $this->db->where("PWOP_WONO", $pwo);
		$query = $this->db->get();
		return $query->result_array();
    }
    public function select_mainsub_byModelArray($pModel){
        $this->db->select('RTRIM(PWOP_BOMPN) PWOP_BOMPN,RTRIM(PWOP_SUBPN) PWOP_SUBPN');
        $this->db->from($this->TABLENAME . " a");
        $this->db->where_in("PWOP_MDLCD", $pModel);
        $this->db->group_by("PWOP_BOMPN,PWOP_SUBPN");
		$query = $this->db->get();
		return $query->result_array();
    }

    public function isMainUnit($pitemcode){
        return $this->db->get_where("(select PWOP_MDLCD from XPWOP left join XMITM_V on PWOP_BOMPN=MITM_ITMCD
        where MITM_MODEL=1
        group by PWOP_MDLCD) V1",['PWOP_MDLCD' => $pitemcode])->num_rows();
    }
}