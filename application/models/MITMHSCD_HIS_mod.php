<?php 
class MITMHSCD_HIS_mod extends CI_Model {
    private $TABLENAME = "MITMHSCD_HIS_TBL";
    public function __construct()
    {
        $this->load->database();
    }

    public function insert($data)
    {
        $this->db->insert($this->TABLENAME,$data);
        return $this->db->affected_rows();
    }
    public function lastserialid($pdoc){
        $qry = "select MAX(MITMHSCD_HIS_LINE) lser from $this->TABLENAME 
        WHERE MITMHSCD_HIS_ITMCD=?";
        $query =  $this->db->query($qry, [$pdoc]);  
        if ($query->num_rows()>0){
            $ret = $query->row();
            return $ret->lser;
        } else {
            return 0;
        }
    }
}
