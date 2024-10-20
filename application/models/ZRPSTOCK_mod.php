<?php

class ZRPSTOCK_mod extends CI_Model
{
    private $TABLENAME = "ZRPSAL_BCSTOCK";
    public function __construct()
    {
        $this->load->database();
    }
    public function select_byTXID($pTXID)
    {
        $this->db->where("RPSTOCK_REMARK", $pTXID)->where("deleted_at", null);
        $this->db->select("RPSTOCK_BCTYPE,RPSTOCK_BCNUM,RPSTOCK_BCDATE");
        $this->db->group_by("RPSTOCK_BCTYPE,RPSTOCK_BCNUM,RPSTOCK_BCDATE");
        $this->db->order_by("RPSTOCK_BCDATE,RPSTOCK_BCNUM");
        $query = $this->db->get($this->TABLENAME);
        return $query->result_array();
    }

    public function selectColumnsWhereRemarkIn($paramRemark)
    {
        $this->db->where_in("RPSTOCK_REMARK", $paramRemark);
        $this->db->select("RPSTOCK_REMARK,RPSTOCK_DOC,RPSTOCK_NOAJU,UPPER(RTRIM(RPSTOCK_ITMNUM)) RPSTOCK_ITMNUM,RCV_PRPRC,RPSTOCK_BCTYPE, ABS(SUM(RPSTOCK_QTY)) BCQT, RCV_HSCD, RPSTOCK_BCNUM, BM, PPN, PPH");
        $this->db->join('(select RCV_RPNO,RCV_DONO,RCV_ITMCD,max(RCV_PRPRC) RCV_PRPRC, MAX(RCV_HSCD) RCV_HSCD, MAX(RCV_BM) BM, MAX(RCV_PPN) PPN, MAX(RCV_PPH) PPH from RCV_TBL group by RCV_RPNO,RCV_DONO,RCV_ITMCD) v1', 'RPSTOCK_NOAJU=RCV_RPNO AND RPSTOCK_DOC=RCV_DONO and RPSTOCK_ITMNUM=RCV_ITMCD', 'LEFT');
        $this->db->group_by("RPSTOCK_REMARK,RPSTOCK_DOC,RPSTOCK_NOAJU,RPSTOCK_ITMNUM,RCV_PRPRC, RPSTOCK_BCTYPE, RCV_HSCD, RPSTOCK_BCNUM, BM, PPN, PPH,RPSTOCK_BCDATE");
        $this->db->order_by('RPSTOCK_BCDATE');
        $query = $this->db->get($this->TABLENAME);
        return $query->result_array();
    }

    public function check_Primary($data)
    {
        return $this->db->get_where($this->TABLENAME, $data)->num_rows();
    }

    public function select_all_where($pwhere)
    {
        $this->db->from("ZRPSAL_BCSTOCK");
        $this->db->where($pwhere);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_columns_where($columns, $pwhere)
    {
        $this->db->select($columns);
        $this->db->from("ZRPSAL_BCSTOCK");
        $this->db->join("(SELECT RCV_ITMCD,RCV_RPNO,MAX(RCV_HSCD) RCV_HSCD,MAX(RCV_BM) RCV_BM, MAX(RCV_PPN) RCV_PPN, MAX(RCV_PPH) RCV_PPH, MAX(RCV_ZNOURUT) URUT FROM RCV_TBL GROUP BY RCV_ITMCD,RCV_RPNO) V2", "RPSTOCK_ITMNUM=V2.RCV_ITMCD AND RPSTOCK_NOAJU=V2.RCV_RPNO");
        $this->db->where($pwhere);
        $query = $this->db->get();
        return $query->result_array();
    }
    public function select_columns_where_remark_in($columns, $Remarks)
    {
        $this->db->select($columns);
        $this->db->from("ZRPSAL_BCSTOCK");
        $this->db->join("(SELECT RCV_ITMCD,RCV_RPNO,MAX(RCV_HSCD) RCV_HSCD,MAX(RCV_BM) RCV_BM, MAX(RCV_PPN) RCV_PPN, MAX(RCV_PPH) RCV_PPH, MAX(RCV_ZNOURUT) URUT FROM RCV_TBL GROUP BY RCV_ITMCD,RCV_RPNO) V2", "RPSTOCK_ITMNUM=V2.RCV_ITMCD AND RPSTOCK_NOAJU=V2.RCV_RPNO");
        $this->db->where_in('RPSTOCK_REMARK', $Remarks);
        $this->db->order_by('RPSTOCK_BCDATE asc');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function select_group_columns_where_remark_in($columns, $Remarks)
    {
        $this->db->select($columns);
        $this->db->from("ZRPSAL_BCSTOCK");
        // $this->db->join("(SELECT RCV_ITMCD,RCV_RPNO,MAX(RCV_HSCD) RCV_HSCD,MAX(RCV_BM) RCV_BM, MAX(RCV_PPN) RCV_PPN, MAX(RCV_PPH) RCV_PPH, MAX(RCV_ZNOURUT) URUT FROM RCV_TBL GROUP BY RCV_ITMCD,RCV_RPNO) V2", "RPSTOCK_ITMNUM=V2.RCV_ITMCD AND RPSTOCK_NOAJU=V2.RCV_RPNO");
        $this->db->join("(SELECT RCV_ITMCD,RCV_BCDATE,RCV_BCNO,MAX(RCV_HSCD) RCV_HSCD,MAX(RCV_BM) RCV_BM, MAX(RCV_PPN) RCV_PPN, MAX(RCV_PPH) RCV_PPH, MAX(RCV_ZNOURUT) URUT FROM RCV_TBL GROUP BY RCV_ITMCD,RCV_BCDATE,RCV_BCNO) V2", "RPSTOCK_ITMNUM=V2.RCV_ITMCD AND RPSTOCK_BCDATE=V2.RCV_BCDATE AND RPSTOCK_BCNUM=RCV_BCNO");
        $this->db->where_in('RPSTOCK_REMARK', $Remarks);
        $this->db->group_by("RPSTOCK_ITMNUM,
        RPSTOCK_DOC, RPSTOCK_NOAJU, RPSTOCK_BCNUM, RPSTOCK_BCDATE, RPSTOCK_BCTYPE,
        RPSTOCK_REMARK,RCV_BM, RCV_PPN, RCV_PPH, URUT");
        $this->db->order_by('RPSTOCK_BCDATE asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_all_where_imod($pwhere)
    {
        $DBUse = $this->load->database('rpcust', true);
        $DBUse->from("RPSAL_BCSTOCK");
        $DBUse->where($pwhere);
        $query = $DBUse->get();
        return $query->result_array();
    }

    public function updatebyId($pwhere, $pval)
    {
        $DBUse = $this->load->database('rpcust', true);
        $DBUse->where($pwhere);
        $DBUse->update("RPSAL_BCSTOCK", $pval);
        return $DBUse->affected_rows();
    }

    public function query($qry)
    {
        $DBUse = $this->load->database('rpcust', true);
        $query = $DBUse->query($qry);
        return $query->result_array();
    }

    public function select_doubled_transaction($pPartCode, $pWO, $pOkIdSample)
    {
        $qry = "SELECT DLV_ID,SER_QTY,COUNT(*) TTLROWS, PLOTQT,PEROK*SER_QTY THEPLOTOK, (PLOTQT-PEROK*SER_QTY)*COUNT(*) DIFF FROM SER_TBL
        RIGHT JOIN (select SERD2_SER,SERD2_ITMCD,sum(SERD2_QTY) PLOTQT
                    from SERD2_TBL where SERD2_ITMCD=? AND SERD2_JOB=?
                    group by SERD2_SER,SERD2_ITMCD) V1 ON SER_ID=SERD2_SER
        LEFT JOIN DLV_TBL ON SERD2_SER=DLV_SER
        LEFT JOIN (
            select VOK_1.*,PLOTOKQT/SER_QTY PEROK from
            (select SERD2_SER,SERD2_ITMCD,SUM(SERD2_QTY) PLOTOKQT
            from SERD2_TBL where SERD2_SER=? AND SERD2_ITMCD=?
            group by SERD2_SER,SERD2_ITMCD) VOK_1
            LEFT JOIN SER_TBL ON SERD2_SER=SER_ID
        ) VOK ON V1.SERD2_ITMCD=VOK.SERD2_ITMCD
        WHERE PLOTQT/SER_QTY!=PEROK AND SER_QTY>0
        GROUP BY DLV_ID,SER_QTY,PEROK,PLOTQT,PLOTOKQT
        ORDER BY DLV_ID";
        $query = $this->db->query($qry, [$pPartCode, $pWO, $pOkIdSample, $pPartCode]);
        return $query->result_array();
    }

    public function selectStockItemVSBC($item, $date)
    {
        $qry = "SELECT '' SER_ITMID
                    ,'' ITH_SER
                    ,ITH_WH	ITRN_LOCCD
                    ,SERD2_ITMCD ITRN_ITMCD
                    ,SUM(SERD2_QTY) RMQT
                FROM (
                    SELECT ITH_SER
                        ,ITH_WH
                    FROM v_ith_tblc
                    WHERE ITH_WH IN (
                            'QAFG'
                            ,'AFWH3'
                            ,'ARSHP'
                            ,'AWIP1'
                            )
                    AND ITH_DATEC <= ?
                    GROUP BY ITH_SER
                        ,ITH_WH
                    HAVING SUM(ITH_QTY) > 0
                    ) V1
                LEFT JOIN SERD2_TBL ON ITH_SER = SERD2_SER
                LEFT JOIN SER_TBL ON ITH_SER = SER_ID
                LEFT JOIN DLV_TBL ON SER_ID=DLV_sER
                LEFT JOIN (SELECT RPSTOCK_REMARK FROM ZRPSAL_BCSTOCK GROUP BY RPSTOCK_REMARK) VBC ON DLV_ID=RPSTOCK_REMARK
                WHERE SERD2_ITMCD LIKE ? AND RPSTOCK_REMARK IS NULL
                GROUP BY
                    ITH_WH
                    ,SERD2_ITMCD

                UNION ALL

                SELECT ''
                    ,''
                    ,'EXBC'
                    ,UPPER(RTRIM(RPSTOCK_ITMNUM)) ITRN_ITMCD
                    ,SUM(RPSTOCK_QTY) RMQT
                FROM ZRPSAL_BCSTOCK
                WHERE RPSTOCK_ITMNUM LIKE ? AND IODATE <= ?
                GROUP BY RPSTOCK_ITMNUM";
        $query = $this->db->query($qry, [$date, '%' . $item . '%', '%' . $item . '%', $date]);
        return $query->result_array();
    }

    public function select_booked_dispose_202212()
    {
        $qry = "SELECT RTRIM(RPSTOCK_ITMNUM) ITMNUM, RPSTOCK_BCNUM,RPSTOCK_NOAJU,RPSTOCK_BCDATE
        ,PPN,PPH,BM,ABS(RPSTOCK_QTY) BCQT,URUT,HSCD
        FROM ZRPSAL_BCSTOCK
        LEFT JOIN  (
        SELECT RCV_RPNO,RCV_BCNO,RCV_DONO,RCV_ITMCD, MAX(RCV_PPN) PPN,MAX(RCV_PPH) PPH, MAX(RCV_BM) BM,MAX(RCV_ZNOURUT) URUT,MAX(RCV_HSCD) HSCD FROM RCV_TBL
        GROUP BY RCV_RPNO,RCV_BCNO,RCV_DONO,RCV_ITMCD
        ) VRCV ON RPSTOCK_NOAJU=RCV_RPNO AND RPSTOCK_BCNUM=RCV_BCNO AND RPSTOCK_DOC=RCV_DONO
        AND RPSTOCK_ITMNUM=RCV_ITMCD
        WHERE RPSTOCK_REMARK='DISD2212_FG'";
        $query = $this->db->query($qry);
        return $query->result_array();
    }

    public function selectBookedDispose($doc)
    {
        $qry = "SELECT RTRIM(RPSTOCK_ITMNUM) ITMNUM, RPSTOCK_BCNUM,RPSTOCK_NOAJU,RPSTOCK_BCDATE
        ,PPN,PPH,BM,ABS(RPSTOCK_QTY) BCQT,URUT,HSCD
        FROM ZRPSAL_BCSTOCK
        LEFT JOIN  (
        SELECT RCV_RPNO,RCV_BCNO,RCV_DONO,RCV_ITMCD, MAX(RCV_PPN) PPN,MAX(RCV_PPH) PPH, MAX(RCV_BM) BM,MAX(RCV_ZNOURUT) URUT,MAX(RCV_HSCD) HSCD FROM RCV_TBL
        GROUP BY RCV_RPNO,RCV_BCNO,RCV_DONO,RCV_ITMCD
        ) VRCV ON RPSTOCK_NOAJU=RCV_RPNO AND RPSTOCK_BCNUM=RCV_BCNO AND RPSTOCK_DOC=RCV_DONO
        AND RPSTOCK_ITMNUM=RCV_ITMCD
        WHERE RPSTOCK_REMARK=?";
        $query = $this->db->query($qry, [$doc]);
        return $query->result_array();
    }
}
