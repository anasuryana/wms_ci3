<?php
class XMBOM_mod extends CI_Model
{
    private $TABLENAME = "XMBOM";
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
    public function selectVersionWhereItemIn($model)
    {
        $this->db->select("RTRIM(MBOM_MDLCD) MBOM_MDLCD,MAX(MBOM_BOMRV) MBOM_BOMRV");
        $this->db->where_in("MBOM_MDLCD", $model);
        $this->db->from($this->TABLENAME);
        $this->db->group_by("MBOM_MDLCD");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_for_simulation($assyCode, $qty)
    {
        $qry = "select CONCAT('BOM-RV-',MBOM_BOMRV) SIMNO
        , RTRIM(MBOM_MDLCD) PDPP_MDLCD
        , '-' PIS3_WONO
        , MBOM_PARQT*$qty REQQTY
        , MBOM_PARQT MYPER
        , RTRIM(MBOM_BOMPN) PIS3_MPART
        , '-' PIS3_ITMCD
        , RTRIM(MITM_SPTNO) MITM_SPTNO
        , 0 PLOTQTY
        , 0 PLOTSUBQTY
        from XMBOM
        LEFT JOIN MITM_TBL ON MBOM_BOMPN=MITM_ITMCD
        where MBOM_MDLCD=?
        AND MBOM_BOMRV=(SELECT MAX(MBOM_BOMRV) FROM XMBOM WHERE MBOM_MDLCD=?)
        order by MBOM_BOMPN";
        $query = $this->db->query($qry, [$assyCode, $assyCode]);
        return $query->result_array();
    }

    public function select_sim_item_stock($assyCode)
    {
        $qry = "select PIS3_MPART ITEMCODE,ISNULL(TSTKQTY,0) TSTKQTY from
        (select RTRIM(MBOM_BOMPN) PIS3_MPART from XMBOM
            where MBOM_MDLCD=?
            AND MBOM_BOMRV=(SELECT MAX(MBOM_BOMRV) FROM XMBOM WHERE MBOM_MDLCD=?)
            GROUP BY MBOM_BOMPN
        ) vg
        LEFT JOIN
        (select ITH_ITMCD,sum(STKQTY) TSTKQTY from (
        select ITH_ITMCD,ITH_WH,CASE WHEN sum(ITH_QTY) <0 THEN 0 ELSE sum(ITH_QTY) END STKQTY from ITH_TBL
        WHERE  ITH_WH IN ('ARWH1')
        group by
        ITH_ITMCD,ITH_WH) vitemstock group by ITH_ITMCD) vitemstockgroup on PIS3_MPART=ITH_ITMCD";
        $query = $this->db->query($qry, [$assyCode, $assyCode]);
        return $query->result_array();
    }
}
