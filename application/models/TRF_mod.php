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
        $this->db->where("MONTH(TRFH_CREATED_DT)", $Month)->where("YEAR(TRFH_CREATED_DT)", $Year);
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

    public function selectHeaderWhere($where)
    {
        $this->db->from($this->TABLENAME);
        $this->db->where($where);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectDetailWhere($where)
    {
        $this->db->from($this->TABLENAME_D);
        $this->db->where($where)->where("TRFD_DELETED_DT is null", null, false);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectDetailUnconformWhere($where)
    {
        $this->db->select("TRFD_ITEMCD,RTRIM(MITM_ITMD1) MITM_ITMD1,TRFD_QTY,TRFD_DOC");
        $this->db->from($this->TABLENAME_D);
        $this->db->join('MITM_TBL', 'TRFD_ITEMCD=MITM_ITMCD','left');
        $this->db->where($where)->where("TRFD_DELETED_DT is null", null, false);
        $this->db->where($where)->where("TRFD_RECEIVE_DT is null", null, false);
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
        $this->db->select("TRFH_DOC,TRFH_ISSUE_DT,CONCAT(P1.MSTEMP_FNM, ' ', P1.MSTEMP_LNM) PIC,TRFH_LOC_FR,TRFH_LOC_TO,TRFH_APPROVED_DT,TRFH_CREATED_DT,TRFH_CREATED_BY,MAX(TRFD_REFFERENCE_DOCNO) TRFD_REFFERENCE_DOCNO");
        $this->db->order_by("TRFH_ISSUE_DT");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function updatebyId($pwhere, $pval)
    {
        $this->db->where($pwhere);
        $this->db->update($this->TABLENAME_D, $pval);
        return $this->db->affected_rows();
    }

    public function rejectbyId($pwhere, $pval)
    {
        $this->db->where($pwhere)->where("TRFD_DELETED_DT is null", null, false);
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
            ->where("TRFD_RECEIVE_DT is null", null, false)
            ->where_in("TRFD_ITEMCD", $Item);
        $this->db->group_by("TRFD_ITEMCD");
        $this->db->select("UPPER(TRFD_ITEMCD) TRFD_ITEMCD,SUM(TRFD_QTY) DQT,SUM(TRFD_QTY) BACKUP_DQT");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectOpenAll()
    {
        $qry = "SELECT TRFH_DOC,TRFH_LOC_FR,MSTLOCG_NM,MAX(ISNULL(TRFD_UPDATED_DT,TRFD_CREATED_DT)) LUPDTD,COUNT(*) TTLITEM FROM TRFH_TBL LEFT JOIN TRFD_TBL ON TRFH_DOC=TRFD_DOC
        LEFT JOIN MSTLOCG_TBL TFR ON  TRFH_LOC_FR=TFR.MSTLOCG_ID
                WHERE TRFD_DELETED_DT IS NULL
                GROUP BY TRFH_DOC,TRFH_LOC_FR,MSTLOCG_NM";
        $query = $this->db->query($qry);
        return $query->result_array();
    }

    public function selectOpenForID($UserId)
    {
        $qry = "SELECT TRFH_DOC,TRFH_LOC_FR,TFR.MSTLOCG_NM,TFRTO.MSTLOCG_NM MSTLOCG_NMTO,MAX(ISNULL(TRFD_UPDATED_DT,TRFD_CREATED_DT)) LUPDTD,COUNT(*) TTLITEM 
        FROM TRFH_TBL LEFT JOIN TRFD_TBL ON TRFH_DOC=TRFD_DOC
        LEFT JOIN MSTLOCG_TBL TFR ON TRFH_LOC_FR=TFR.MSTLOCG_ID
        LEFT JOIN MSTLOCG_TBL TFRTO ON TRFH_LOC_TO=TFRTO.MSTLOCG_ID
        WHERE TRFD_DELETED_DT IS NULL AND TRFH_LOC_TO IN (SELECT TRFSET_WH FROM TRFSET_TBL WHERE TRFSET_APPROVER=? AND TRFSET_DELETED_DT IS NULL)
                AND TRFD_RECEIVE_DT IS NULL
                GROUP BY TRFH_DOC,TRFH_LOC_FR,TFR.MSTLOCG_NM,TFRTO.MSTLOCG_NM";
        $query = $this->db->query($qry, [$UserId]);
        return $query->result_array();
    }

    public function selectOpenToFollowForID($UserId)
    {
        $qry = "SELECT TRFH_DOC,TRFH_LOC_TO,TTO.MSTLOCG_NM,TFROM.MSTLOCG_NM MSTLOCG_NMFROM,MAX(ISNULL(TRFD_UPDATED_DT,TRFD_CREATED_DT)) LUPDTD,COUNT(*) TTLITEM FROM TRFH_TBL LEFT JOIN TRFD_TBL ON TRFH_DOC=TRFD_DOC
        LEFT JOIN MSTLOCG_TBL TTO ON  TRFH_LOC_TO=TTO.MSTLOCG_ID
        LEFT JOIN MSTLOCG_TBL TFROM ON  TRFH_LOC_FR=TFROM.MSTLOCG_ID
                WHERE TRFD_DELETED_DT IS NULL AND TRFH_LOC_FR IN (SELECT TRFSET_WH FROM TRFSET_TBL WHERE TRFSET_APPROVER=? AND TRFSET_DELETED_DT IS NULL)
                AND TRFD_RECEIVE_DT IS NULL
                GROUP BY TRFH_DOC,TRFH_LOC_TO,TTO.MSTLOCG_NM,TFROM.MSTLOCG_NM";
        $query = $this->db->query($qry, [$UserId]);
        return $query->result_array();
    }

    public function selectOpenForIDWhereItem($UserId, $Item, $Doc)
    {
        $qry = "SELECT TRFH_DOC,TRFD_ITEMCD,TRFH_LOC_FR,TRFH_LOC_TO,SUM(TRFD_QTY) TTLQTY FROM TRFH_TBL LEFT JOIN TRFD_TBL ON TRFH_DOC=TRFD_DOC
        LEFT JOIN MSTLOCG_TBL TFR ON  TRFH_LOC_FR=TFR.MSTLOCG_ID
                WHERE TRFD_DELETED_DT IS NULL AND TRFH_LOC_TO IN (SELECT TRFSET_WH FROM TRFSET_TBL WHERE TRFSET_APPROVER=?)
                AND TRFD_ITEMCD = ? AND TRFH_DOC=? AND TRFD_RECEIVE_DT IS NULL
                GROUP BY TRFH_DOC,TRFH_LOC_FR,TRFH_LOC_TO,MSTLOCG_NM,TRFD_ITEMCD";
        $query = $this->db->query($qry, [$UserId, $Item, $Doc]);
        return $query->result_array();
    }

    function selectBalanceTransferByPO($PONumber){
        $qry = "SELECT VPO.*
                    ,TRFQT
                    ,RTRIM(MITM_ITMD1) ITMD
                    ,TTLPOQT-ISNULL(TRFQT,0) BALQT
                FROM (
                    SELECT PO_NO
                        ,UPPER(PO_ITMCD) PO_ITMCD
                        ,sum(PO_QTY) TTLPOQT
                    FROM PO_TBL
                    WHERE PO_ITMCD IS NOT NULL AND PO_NO = ?
                    GROUP BY PO_NO
                        ,PO_ITMCD
                    ) VPO
                LEFT JOIN (
                    SELECT TRFD_REFFERENCE_DOCNO
                        ,TRFD_ITEMCD
                        ,SUM(TRFD_QTY) TRFQT
                    FROM TRFD_TBL
                    WHERE TRFD_REFFERENCE_DOCNO = ?
                    GROUP BY TRFD_REFFERENCE_DOCNO
                        ,TRFD_ITEMCD
                    ) VTRF ON VPO.PO_NO = VTRF.TRFD_REFFERENCE_DOCNO
                    AND VPO.PO_ITMCD = VTRF.TRFD_REFFERENCE_DOCNO
                LEFT JOIN MITM_TBL ON PO_ITMCD=MITM_ITMCD";
        $query = $this->db->query($qry, [$PONumber, $PONumber]);
        return $query->result_array();
    }
}
