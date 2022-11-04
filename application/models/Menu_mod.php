<?php 
class Menu_mod extends CI_Model {
    public function __construct()
    {
        $this->load->database();        
    }

    public function menu()
    {
        $this->db->select("*, '0' USED");
        $this->db->from('MENU_TBL');
		$this->db->where('MENU_APP', 'WMS');
        $this->db->order_by('MENU_ID ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function menu_setting()
    {
        $this->db->select("*, '0' USED");
        $this->db->from('MENU_TBL');
		$this->db->where('MENU_APP', 'WMS')->where_not_in("MENU_ID", ["XB","XD"]);
        $this->db->order_by('MENU_ID ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
}
