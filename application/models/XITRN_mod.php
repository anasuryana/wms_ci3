<?php
class XITRN_mod extends CI_Model
{
    private $TABLENAME = "XITRN_TBL";
    public function __construct()
    {
        $this->load->database();
    }

    public function selectall()
    {
        $this->db->from($this->TABLENAME);
        $query = $this->db->get();
        return $query->result();
    }
    public function select_where($pcols, $pwhere)
    {
        $this->db->select($pcols);
        $this->db->from($this->TABLENAME)
            ->join("MITM_TBL", "ITRN_ITMCD=MITM_ITMCD")
            ->where($pwhere)
            ->order_by("ITRN_ISUDT");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_stock($pDate, $pLocation)
    {
        $this->db->select('ITRN_ITMCD ITEMCD');
        $this->db->from($this->TABLENAME)
            ->where("ITRN_LOCCD", $pLocation)
            ->where("ITRN_ISUDT<=", $pDate)
            ->order_by("ITRN_ISUDT");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectEXBCReconsiliator($item, $date)
    {
        $qry = "SELECT
                    '' SER_ITMID,
                    '' ITH_SER,
                    ITRN_LOCCD,
                    ITRN_ITMCD,
                    MAX(RTRIM(MITM_SPTNO)) MITM_SPTNO,
                    MAX(RTRIM(MITM_ITMD1)) MITM_ITMD1,
                    SUM(RMQT1) RMQT
                FROM
                    (
                    SELECT
                        ITRN_LOCCD,
                        ISNULL(MITMGRP_ITMCD, ITRN_ITMCD) ITRN_ITMCD,
                        SUM(RMQT) RMQT1
                    FROM
                        OPENQUERY(
                        [SRVMEGA],
                        'SELECT
                    RTRIM(ITRN_LOCCD) ITRN_LOCCD,
                    RTRIM(ITRN_ITMCD) ITRN_ITMCD,
                    SUM(CASE WHEN ITRN_IOFLG = ''1'' THEN ITRN_TRNQT ELSE -1*ITRN_TRNQT END) AS RMQT
                FROM
                    PSI_MEGAEMS.dbo.ITRN_TBL
                    LEFT JOIN PSI_MEGAEMS.dbo.MITM_TBL ON ITRN_ITMCD = MITM_ITMCD
                WHERE
                    ITRN_LOCCD != ''ARWH9SC''
                    AND ITRN_ITMCD LIKE ''%$item%''
                    AND ITRN_ISUDT <= ''$date''
                GROUP BY
                    ITRN_LOCCD,
                    ITRN_ITMCD
                    '
                        ) A
                        LEFT JOIN MITMGRP_TBL ON MITMGRP_ITMCD_GRD = ITRN_ITMCD
                    GROUP BY
                        ITRN_LOCCD,
                        ITRN_ITMCD,
                        MITMGRP_ITMCD
                    ) V1
                    LEFT JOIN MITM_TBL ON ITRN_ITMCD = MITM_ITMCD
                GROUP BY
                    ITRN_LOCCD,
                    ITRN_ITMCD";
        $query = $this->db->query($qry);
        return $query->result_array();
    }

    public function check_Primary($data)
    {
        return $this->db->get_where($this->TABLENAME, $data)->num_rows();
    }
}
