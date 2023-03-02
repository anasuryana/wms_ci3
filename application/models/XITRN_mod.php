<?php 
class XITRN_mod extends CI_Model {
	private $TABLENAME = "XITRN_TBL";
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
        ->join("MITM_TBL", "ITRN_ITMCD=MITM_ITMCD")
        ->where($pwhere)
        ->order_by("ITRN_ISUDT");
		$query = $this->db->get();
		return $query->result_array();
    }

    public function select_stock($pDate, $pLocation) {
        $this->db->select('ITRN_ITMCD ITEMCD');
        $this->db->from($this->TABLENAME)        
        ->where("ITRN_LOCCD", $pLocation)
        ->where("ITRN_ISUDT<=", $pDate)
        ->order_by("ITRN_ISUDT");        
		$query = $this->db->get();
		return $query->result_array();
    }

    public function selectEXBCReconsiliator($item)
    {
        $qry = "SELECT '' SER_ITMID
                    ,'' ITH_SER
                    ,RTRIM(ITRN_LOCCD) ITRN_LOCCD					
                    ,RTRIM(ITRN_ITMCD) ITRN_ITMCD
                    ,RTRIM(MITM_SPTNO) MITM_SPTNO
                    ,RTRIM(MITM_ITMD1) MITM_ITMD1
                    ,SUM(IOQT) RMQT
                FROM XITRN_TBL
                LEFT JOIN XMITM_V ON ITRN_ITMCD=MITM_ITMCD
                WHERE ITRN_LOCCD != 'ARWH9SC'
                    AND ITRN_ITMCD like ?
                GROUP BY ITRN_LOCCD
                    ,ITRN_ITMCD
                    ,MITM_SPTNO
                    ,MITM_ITMD1";
        $query =  $this->db->query($qry, ['%'.$item.'%']);
        return $query->result_array();
    }

    public function check_Primary($data)
    {        
        return $this->db->get_where($this->TABLENAME,$data)->num_rows();		
	}
}