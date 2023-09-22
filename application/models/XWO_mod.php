<?php

class XWO_mod extends CI_Model
{
    private $TABLENAME = "XWO";
    private $TABLENAME2 = "VCIMS_MBLA_TBL";
    public function __construct()
    {
        $this->load->database();
    }

    public function check_Primary($data)
    {
        return $this->db->get_where($this->TABLENAME, $data)->num_rows();
    }

    public function select_cols_where_wono_in($columns, $wono)
    {
        $this->db->select($columns);
        $this->db->from($this->TABLENAME);
        $this->db->where_in("PDPP_WONO", $wono);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectLineWhereInModelProcessAndVersion($models, $models_process, $models_versions)
    {
        $this->db->select("RTRIM(MBLA_LINENO) ALTLINE,RTRIM(MBLA_MDLCD) MDLCD,RTRIM(MBLA_PROCD) MBLA_PROCD");
        $this->db->from($this->TABLENAME2);
        $this->db->where_in("MBLA_MDLCD", $models)
            ->where_in("MBLA_BOMRV", $models_versions)
            ->where_in("MBLA_PROCD", $models_process)
            ->where_not_in("MBLA_LINENO", ['SMT-S1', 'SMT-S3']);
        $this->db->group_by("MBLA_LINENO,MBLA_MDLCD,MBLA_PROCD");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectProcessDetail($passyCode)
    {
        $qry = "SELECT RTRIM(A.MBLA_MDLCD) MBLA_MDLCD
                    ,A.MBLA_BOMRV
                    ,RTRIM(MBLA_LINENO) PIS3_LINENO
                    ,RTRIM(MBLA_FR) PIS3_FR
                    ,RTRIM(A.MBLA_PROCD) PIS3_PROCD
                    ,RTRIM(MBLA_MC) PIS3_MC
                    ,RTRIM(MBLA_MCZ) PIS3_MCZ
                    ,SUM(MBLA_QTY) MYPER
                    ,MAX(RTRIM(MBLA_SPART)) PIS3_ITMCD
                    ,RTRIM(MBLA_ITMCD) PIS3_MPART
                FROM VCIMS_MBLA_TBL A
                INNER JOIN (
                    SELECT MBLA_MDLCD
                        ,MBLA_PROCD
                        ,MBLA_BOMRV
                        ,MAX(MBLA_LINENO) MXLINE
                    FROM VCIMS_MBLA_TBL
                    WHERE MBLA_MDLCD IN (
                        $passyCode
                            )
                    GROUP BY MBLA_MDLCD
                        ,MBLA_PROCD
                        ,MBLA_BOMRV
                    ) v1 ON MBLA_LINENO = v1.MXLINE
                    AND A.MBLA_PROCD = v1.MBLA_PROCD
                    AND A.MBLA_BOMRV = v1.MBLA_BOMRV
                INNER JOIN (
                    SELECT MBLA_MDLCD
                        ,MAX(MBLA_BOMRV) LTSRV
                    FROM VCIMS_MBLA_TBL
                    WHERE MBLA_MDLCD IN (
                        $passyCode
                            )
                    GROUP BY MBLA_MDLCD
                    ) v2 ON A.MBLA_MDLCD = v2.MBLA_MDLCD
                    AND A.MBLA_BOMRV = v2.LTSRV
                WHERE A.MBLA_MDLCD IN (
                    $passyCode
                        )
                GROUP BY A.MBLA_MDLCD
                    ,A.MBLA_BOMRV
                    ,MBLA_LINENO
                    ,MBLA_FR
                    ,A.MBLA_PROCD
                    ,MBLA_MC
                    ,MBLA_MCZ
                    ,MBLA_ITMCD
                ORDER BY A.MBLA_MDLCD";
        $query = $this->db->query($qry);
        return $query->result_array();
    }

    public function selectProcess($passyCode)
    {
        $qry = "SELECT MBLA_MDLCD
                    ,MBLA_BOMRV
                    ,MBLA_PROCD
                    ,COUNT(*) CNT
                    ,MAX(MBLA_LINENO) XLINE
                    ,MBLA_ITMCD
                    ,RTRIM(MITM_ITMD1) MITM_ITMD1
                FROM (
                    SELECT RTRIM(A.MBLA_MDLCD) MBLA_MDLCD
                        ,MBLA_BOMRV
                        ,RTRIM(MBLA_PROCD) MBLA_PROCD
                        ,RTRIM(MBLA_LINENO) MBLA_LINENO
                        ,MAX(RTRIM(V2.MBLA_ITMCD)) MBLA_ITMCD
                    FROM VCIMS_MBLA_TBL A
                    INNER JOIN (
                        SELECT MBLA_MDLCD
                            ,MAX(MBLA_BOMRV) LTSRV
                            ,MAX(CASE WHEN MITM_ITMCAT='PCB' THEN MBLA_ITMCD END) MBLA_ITMCD
                        FROM VCIMS_MBLA_TBL
                        LEFT JOIN XMITM_VCIMS ON MBLA_ITMCD=MITM_ITMCD
                        WHERE MBLA_MDLCD IN (
                            $passyCode
                                )
                        GROUP BY MBLA_MDLCD
                        ) v2 ON A.MBLA_MDLCD = v2.MBLA_MDLCD
                        AND A.MBLA_BOMRV = v2.LTSRV
                    WHERE A.MBLA_MDLCD IN (
                        $passyCode
                            )
                    GROUP BY MBLA_BOMRV
                        ,A.MBLA_MDLCD
                        ,MBLA_PROCD
                        ,MBLA_LINENO
                    ) V1
                LEFT JOIN MITM_TBL ON MBLA_MDLCD=MITM_ITMCD
                GROUP BY MBLA_MDLCD
                    ,MBLA_BOMRV
                    ,MBLA_PROCD
                    ,MBLA_ITMCD
                    ,MITM_ITMD1
                ORDER BY MBLA_BOMRV";
        $query = $this->db->query($qry);
        return $query->result_array();
    }

    public function selectHistory($passyCode)
    {
        $qry = "SELECT VMAIN.*
                    ,CNT
                    ,MBLA_ITMCD
                FROM (
                    SELECT RTRIM(MITM_ITMD1) MITM_ITMD1
                        ,RTRIM(MBLA_MDLCD) MBLA_MDLCD
                        ,RTRIM(MBLA_PROCD) MBLA_PROCD
                        ,MBLA_BOMRV
                        ,MBLA_LINENO XLINE
                    FROM VCIMS_MBLA_TBL
                    LEFT JOIN MITM_TBL M ON MBLA_MDLCD = M.MITM_ITMCD
                    LEFT JOIN XMITM_VCIMS MCIMS ON MBLA_ITMCD = MCIMS.MITM_ITMCD
                    WHERE MBLA_MDLCD IN ($passyCode)
                    GROUP BY MBLA_MDLCD
                        ,MBLA_BOMRV
                        ,MBLA_PROCD
                        ,MITM_ITMD1
                        ,MBLA_LINENO
                    ) VMAIN
                LEFT JOIN (
                    SELECT RTRIM(MBLA_MDLCD) MBLA_MDLCD
                        ,MBLA_BOMRV
                        ,RTRIM(MBLA_PROCD) MBLA_PROCD
                        ,COUNT(*) CNT
                    FROM (
                        SELECT MBLA_MDLCD
                            ,MBLA_BOMRV
                            ,MBLA_PROCD
                            ,MBLA_LINENO
                        FROM VCIMS_MBLA_TBL
                        WHERE MBLA_MDLCD IN ($passyCode)
                        GROUP BY MBLA_MDLCD
                            ,MBLA_BOMRV
                            ,MBLA_PROCD
                            ,MBLA_LINENO
                        ) VLINE
                    GROUP BY MBLA_MDLCD
                        ,MBLA_BOMRV
                        ,MBLA_PROCD
                    ) VSECOND ON VMAIN.MBLA_MDLCD = VSECOND.MBLA_MDLCD
                    AND VMAIN.MBLA_BOMRV = VSECOND.MBLA_BOMRV
                    AND VMAIN.MBLA_PROCD = VSECOND.MBLA_PROCD
                    LEFT JOIN (
                        select MBLA_MDLCD,RTRIM(MBLA_ITMCD) MBLA_ITMCD from  VCIMS_MBLA_TBL
                        left join XMITM_VCIMS on MBLA_ITMCD=MITM_ITMCD
                        where MITM_ITMCAT='PCB'
                        GROUP BY MBLA_MDLCD,MBLA_ITMCD
                    ) VITMCD ON VMAIN.MBLA_MDLCD=VITMCD.MBLA_MDLCD
                    ORDER BY MBLA_MDLCD,MBLA_BOMRV,MBLA_PROCD
                ";
        $query = $this->db->query($qry);
        return $query->result_array();
    }

    public function selectDifferentAssyType($wo = [])
    {
        $this->db->from('DIFFERENT_TYPE_ASSY_WO');
        $this->db->where_in("PDPP_WONO", $wo);
        $query = $this->db->get();
        return $query->result_array();
    }
}
