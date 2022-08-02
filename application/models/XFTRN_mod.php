<?php 
class XFTRN_mod extends CI_Model {
	private $TABLENAME = "XFTRN_TBL";
	public function __construct()
    {
        $this->load->database();
    }		

    public function selectall(){
        $this->db->from($this->TABLENAME);        
		$query = $this->db->get();
		return $query->result();
    }
    public function select_where($pcols,$pwhere){
        $this->db->select($pcols);
        $this->db->from($this->TABLENAME)
        ->join("MITM_TBL", "FTRN_ITMCD=MITM_ITMCD")
        ->where($pwhere)
        ->order_by("FTRN_ISUDT");        
		$query = $this->db->get();
		return $query->result_array();
    }
    public function select_stock($pDate, $pLocation) {
        $this->db->select('FTRN_ITMCD ITEMCD');
        $this->db->from($this->TABLENAME)        
        ->where("FTRN_LOCCD", $pLocation)
        ->where("FTRN_ISUDT<=", $pDate)
        ->order_by("FTRN_ISUDT");
		$query = $this->db->get();
		return $query->result_array();
    }

    public function check_Primary($data)
    {        
        return $this->db->get_where($this->TABLENAME,$data)->num_rows();		
	}
}