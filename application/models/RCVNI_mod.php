<?php

class RCVNI_mod extends CI_Model {
	private $TABLENAME = "RCVNI_TBL";	
	public function __construct()
    {
        $this->load->database();
    }
	public function insert($data){
        $this->db->insert($this->TABLENAME,$data);
        return $this->db->affected_rows();
    }
    public function insertb($data)
    {        
        $this->db->insert_batch($this->TABLENAME,$data);
        return $this->db->affected_rows();
    }
    public function insertb_discount($data)
    {        
        $this->db->insert_batch($this->TABLENAME_DISCOUNT,$data);
        return $this->db->affected_rows();
    }
    public function delete($pwhere){
        $this->db->where($pwhere);
        $this->db->delete($this->TABLENAME);
        return $this->db->affected_rows();
    }
    public function delete_discount($pwhere){
        $this->db->where($pwhere);
        $this->db->delete($this->TABLENAME_DISCOUNT);
        return $this->db->affected_rows();
    }
    public function select_all() {        
        $this->db->from($this->TABLENAME);        
		$query = $this->db->get();
		return $query->result_array();
    }
    public function select_where($pwhere) {
        $this->db->from($this->TABLENAME); 
        $this->db->join("(SELECT PO_NO,PO_ITMNM,PO_UM,PO_PRICE FROM PO_TBL GROUP BY PO_NO,PO_ITMNM,PO_UM,PO_PRICE) VPO", "RCVNI_PO=PO_NO AND RCVNI_ITMNM=PO_ITMNM");
        $this->db->where($pwhere);
		$query = $this->db->get();
		return $query->result_array();
    }
    public function check_Primary($data)
    {        
        return $this->db->get_where($this->TABLENAME,$data)->num_rows();
    }
  
    public function update_where($pdata, $pwhere)
    {
        $this->db->where($pwhere);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();        
    }
    public function select_maxline($pdoc){
        $this->db->select("MAX(RCVNI_LINE) LLINE");
        $this->db->from($this->TABLENAME);
        $this->db->where("RCVNI_DO", $pdoc);
		return $this->db->get()->row()->LLINE;
    }

    public function select_header($plike){        
        $this->db->from($this->TABLENAME);
        $this->db->join("(SELECT PO_NO,MSUP_SUPCD,MSUP_SUPNM FROM PO_TBL LEFT JOIN
                            (select MSUP_SUPCD,rtrim(MAX(MSUP_SUPNM)) MSUP_SUPNM,MAX(MSUP_SUPCR) MSUP_SUPCR FROM v_supplier_customer_union GROUP BY MSUP_SUPCD) V1 ON PO_SUPCD=MSUP_SUPCD
                        GROUP BY PO_NO,MSUP_SUPCD,MSUP_SUPNM) VPO
        ","RCVNI_PO=PO_NO");        
        $this->db->group_by("RCVNI_DO,MSUP_SUPNM,MSUP_SUPCD");
        $this->db->select("RCVNI_DO,MSUP_SUPNM,MSUP_SUPCD,max(RCVNI_RCVDATE) RCVDT");
        $this->db->like($plike);
		$query = $this->db->get();
		return $query->result_array();
    }

    function select_receiving_like($pLike, $pDateFrom, $pDateTo) {
        $this->db->from("wms_v_receiving_list");
        $this->db->like($pLike)->where("RCV_RCVDATE >=", $pDateFrom)->where("RCV_RCVDATE <=", $pDateTo);
        $this->db->order_by("RCV_RCVDATE");
		$query = $this->db->get();
		return $query->result_array();
    }
}