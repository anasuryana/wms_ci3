<?php

class RPSAL_BCSTOCK_mod extends CI_Model
{
    private $TABLENAME = "RPSAL_BCSTOCK";
    private $DBUse;
    public function __construct()
    {
        $this->load->database();
        $this->DBUse = $this->load->database('rpcust', true);
    }

    public function insert($data)
    {

        $this->DBUse->insert($this->TABLENAME, $data);
        return $this->DBUse->affected_rows();
    }

    public function insertb($data)
    {
        $this->DBUse->insert_batch($this->TABLENAME, $data);
        return $this->DBUse->affected_rows();
    }

    public function check_Primary($data)
    {
        return $this->DBUse->get_where($this->TABLENAME, $data)->num_rows();
    }

    public function updatebyVAR($pdata, $pwhere)
    {
        $this->DBUse->where($pwhere)->where("deleted_at IS NULL", null, false);        
        $this->DBUse->update($this->TABLENAME, $pdata);
        return $this->DBUse->affected_rows();
    }

    public function select_column_group_where($pColumns, $pWhere)
    {
        $this->DBUse->select($pColumns);
        $this->DBUse->from($this->TABLENAME);
        $this->DBUse->where($pWhere)->where("deleted_at IS NOT NULL", null, false);
        $this->DBUse->group_by($pColumns);
        $query = $this->DBUse->get();
        return $query->result_array();
    }

    public function selectAllOrderbyIdWhere($pColumns,$pWhere)
    {
        $this->DBUse->select($pColumns);
        $this->DBUse->from($this->TABLENAME);
        $this->DBUse->where($pWhere)->where("deleted_at IS NULL", null, false);
        $this->DBUse->order_by('id');
        $query = $this->DBUse->get();
        return $query->result_array();
    }

    public function deletebyID($parr)
    {
        $this->DBUse->where($parr);
        $this->DBUse->delete($this->TABLENAME);
        return $this->DBUse->affected_rows();
    }

    public function selectDiscrepancy($itemCode)
    {
        $qry = "SELECT VBC.*
                    ,BOMQT
                    ,RESPONSEQT + BOMQT BAL
                    ,FGQT
                    ,BOMQT / FGQT PER
                    ,DELVPURPOSE
                FROM (
                    SELECT RPSTOCK_REMARK
                        ,RPSTOCK_ITMNUM
                        ,sum(RPSTOCK_QTY) RESPONSEQT
                    FROM ZRPSAL_BCSTOCK
                    WHERE RPSTOCK_QTY < 0
                        AND RPSTOCK_ITMNUM = ?
                    GROUP BY RPSTOCK_REMARK
                        ,RPSTOCK_ITMNUM
                    ) VBC
                LEFT JOIN (
                    SELECT DLV_ID
                        ,SERD2_ITMCD
                        ,SUM(SERD2_FGQTY) FGQT
                        ,SUM(SERD2_QTY) BOMQT
                        ,MAX(DLV_PURPOSE) DELVPURPOSE
                    FROM DLV_TBL
                    LEFT JOIN SERD2_TBL ON DLV_SER = SERD2_SER
                    WHERE SERD2_ITMCD = ?
                    GROUP BY DLV_ID
                        ,SERD2_ITMCD
                    ) VDELV ON RPSTOCK_REMARK = DLV_ID
                    AND RPSTOCK_ITMNUM = SERD2_ITMCD
                WHERE ISNULL(RESPONSEQT, 0) + ISNULL(BOMQT, 0) != 0
                ORDER BY RPSTOCK_REMARK";
        $query = $this->db->query($qry, [$itemCode, $itemCode]);
        return $query->result_array();
    }
}
