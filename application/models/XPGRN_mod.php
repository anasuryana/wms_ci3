<?php 
class XPGRN_mod extends CI_Model {
	private $TABLENAME = "XPGRN_VIEW";
	public function __construct()
    {
        $this->load->database();
    }		

    public function selec_where_group($coulmns,$groupColumn,$where){
        $this->db->select($coulmns);
        $this->db->from($this->TABLENAME);
        $this->db->where($where);
        $this->db->group_by($groupColumn);
		$query = $this->db->get();
		return $query->result();
    }
}