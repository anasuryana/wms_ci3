<?php 
class CHGPW_mod extends CI_Model {
    private $TABLENAME = 'CHGPW_TBL';
    public function __construct()
    {
        $this->load->database();
    }

    public function insert($data)
    {        
        $this->db->insert($this->TABLENAME,$data);
        return $this->db->affected_rows();
    }

    public function check_Primary($data)
    {
        return $this->db->get_where($this->TABLENAME,$data)->num_rows();
    }

    public function select_unpassed($userid)
    {
        $query = $this->db->get_where($this->TABLENAME,['CHGPW_USR' => $userid, 'CHGPW_PASSPERIOD' => '0']);
        return $query->result_array();
    }

    public function updatebyId($pwhere, $pval)
    {        
        $this->db->where($pwhere);
        $this->db->update($this->TABLENAME, $pval);
        return $this->db->affected_rows();
    }

    public function lastserialid($userid)
    {
        $qry = "select MAX(CHGPW_LINE) lser from $this->TABLENAME 
        WHERE CHGPW_USR=?";
        $query =  $this->db->query($qry, [$userid]);
        if ($query->num_rows()>0){
            $ret = $query->row();
            return $ret->lser;
        } else {
            return 0;
        }
    }
}