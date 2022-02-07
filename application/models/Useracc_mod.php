<?php 
class Useracc_mod extends CI_Model {
    private $TABLENAME = 'EMPACCESS_TBL';
    public function __construct()
    {
        $this->load->database();
    }

    public function insert($grid,$psql2)
    {        
        $this->db->where('EMPACCESS_GRPID', $grid);
        $this->db->delete($this->TABLENAME);        
        $sql = "INSERT INTO ".$this->TABLENAME." VALUES ".$psql2;
        $query = $this->db->query($sql);
    }

    public function selectAll()
    {
        $this->db->select('EMPACCESS_GRPID,EMPACCESS_MENUID');
        $this->db->from($this->TABLENAME);
        $this->db->order_by('EMPACCESS_GRPID ASC,EMPACCESS_MENUID ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function menu_rules($gid)
    {
        $sql = "SELECT a.MENU_ID,MENU_DSCRPTN,MENU_NAME,MENU_PRNT,ISNULL(MENU_URL,'') MENU_URL,MENU_ICON,MENU_STT, '0' USED from 
        MENU_TBL a inner join EMPACCESS_TBL b on a.MENU_ID=b.EMPACCESS_MENUID
                 where EMPACCESS_GRPID='".$gid."' order by a.MENU_ID asc";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
}
