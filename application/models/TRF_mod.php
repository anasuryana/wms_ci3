<?php

class TRF_mod extends CI_Model
{
    private $TABLENAME = "TRFH_TBL";
    private $TABLENAME_D = "TRFD_TBL";
    public function __construct()
    {
        $this->load->database();
    }
    public function selectLastOrder($Month, $Year)
    {
        $this->db->select("ISNULL(MAX(TRFH_ORDER),0) LORDER");
        $this->db->where("MONTH(TRFH_ISSUE_DT)", $Month)->where("YEAR(TRFH_ISSUE_DT)", $Year);
        $query = $this->db->get($this->TABLENAME);
        return $query->result();
    }
    public function insert($data)
    {
        $this->db->insert($this->TABLENAME, $data);
        return $this->db->affected_rows();
    }

    public function insertb($data)
    {
        $this->db->insert_batch($this->TABLENAME_D, $data);
        return $this->db->affected_rows();
    }

    public function selectDetailWhere($where)
    {
        $this->db->from($this->TABLENAME_D);
        $this->db->where($where)->where("TRFD_DELETED_DT is null", null, false);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectHeaderLike($like)
    {
        $this->db->from($this->TABLENAME);
        $this->db->join($this->TABLENAME_D, "TRFH_DOC=TRFD_DOC", "left");
        $this->db->join("MSTEMP_TBL P1", "TRFH_CREATED_BY=P1.MSTEMP_ID", "left");
        $this->db->join("MITM_TBL", "TRFD_ITEMCD=MITM_ITMCD", "left");
        $this->db->like($like)->where("TRFD_DELETED_DT is null", null, false);
        $this->db->group_by("TRFH_DOC,TRFH_ISSUE_DT,P1.MSTEMP_FNM,P1.MSTEMP_LNM,TRFH_LOC_FR,TRFH_LOC_TO,TRFH_APPROVED_DT,TRFH_CREATED_DT,TRFH_CREATED_BY");
        $this->db->select("TRFH_DOC,TRFH_ISSUE_DT,CONCAT(P1.MSTEMP_FNM, ' ', P1.MSTEMP_LNM) PIC,TRFH_LOC_FR,TRFH_LOC_TO,TRFH_APPROVED_DT,TRFH_CREATED_DT,TRFH_CREATED_BY");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function updatebyId($pwhere, $pval)
    {
        $this->db->where($pwhere);
        $this->db->update($this->TABLENAME_D, $pval);
        return $this->db->affected_rows();
    }

    public function updateHeaderWhere($pwhere, $pval)
    {
        $this->db->where($pwhere);
        $this->db->update($this->TABLENAME, $pval);
        return $this->db->affected_rows();
    }

    public function selectStockWithoutIDRowIn($idrow, $Item)
    {
        $this->db->from($this->TABLENAME);
        $this->db->join($this->TABLENAME_D, "TRFH_DOC=TRFD_DOC", "left");
        $this->db->where("TRFD_DELETED_DT is null", null, false)
            ->where("TRFH_APPROVED_DT is null", null, false)
            ->where("TRFD_ITEMCD", $Item)
            ->where_not_in("TRFD_LINE", $idrow);
        $this->db->group_by("TRFD_ITEMCD");
        $this->db->select("TRFD_ITEMCD,SUM(TRFD_QTY) DQT");
        $query = $this->db->get();
        return $query->result_array();
    }
    public function selectStockUnReceive($Item)
    {
        $this->db->from($this->TABLENAME);
        $this->db->join($this->TABLENAME_D, "TRFH_DOC=TRFD_DOC", "left");
        $this->db->where("TRFD_DELETED_DT is null", null, false)
            ->where("TRFH_APPROVED_DT is null", null, false)
            ->where_in("TRFD_ITEMCD", $Item);
        $this->db->group_by("TRFD_ITEMCD");
        $this->db->select("TRFD_ITEMCD,SUM(TRFD_QTY) DQT,SUM(TRFD_QTY) BACKUP_DQT");
        $query = $this->db->get();
        return $query->result_array();
    }
}
