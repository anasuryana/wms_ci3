<?php

class DLVRMDOC_mod extends CI_Model
{
    private $TABLENAME = "DLVRMDOC_TBL";
    public function __construct()
    {
        $this->load->database();
    }

    public function insert($data)
    {
        $this->db->insert($this->TABLENAME, $data);
        return $this->db->affected_rows();
    }

    public function insertb($data)
    {
        $this->db->insert_batch($this->TABLENAME, $data);
        return $this->db->affected_rows();
    }

    public function deleteby_filter($pwhere)
    {
        $this->db->where($pwhere);
        $this->db->delete($this->TABLENAME);
        return $this->db->affected_rows();
    }

    public function updatebyId($pdata, $pkey)
    {
        $this->db->where($pkey);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
    }

    public function select_lastline($pdoc)
    {
        $qry = "SELECT MAX(DLVRMDOC_LINE) lser FROM " . $this->TABLENAME . " WHERE DLVRMDOC_TXID=?";
        $query = $this->db->query($qry, [$pdoc]);
        if ($query->num_rows() > 0) {
            $ret = $query->row();
            return $ret->lser;
        } else {
            return 0;
        }
    }

    public function select_where($pcols, $pwhere)
    {
        $this->db->from($this->TABLENAME);
        $this->db->select($pcols);
        $this->db->join('(SELECT RCV_DONO,RCV_RPNO,RCV_BCDATE,MAX(RCV_BCTYPE) RCV_BCTYPE FROM RCV_TBL GROUP BY RCV_DONO,RCV_RPNO,RCV_BCDATE) VRCV', "DLVRMDOC_AJU=RCV_RPNO AND DLVRMDOC_DO=RCV_DONO", "LEFT");
        $this->db->WHERE($pwhere);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_invoice($ptxid)
    {
        $qry = "SELECT VINV.*,MDEL_ZNAMA,MDEL_ADDRCUSTOMS,DLV_DATE, DLV_INVNO
        ,DLV_SMTINVNO,MCUS_CURCD,DLV_INVDT,DLV_NOPEN,DLV_ITMD1,DLV_ITMSPTNO,DLV_BCTYPE FROM
        (select DLVRMDOC_TXID,DLVRMDOC_ITMID,RTRIM(MITM_ITMD1) MITM_ITMD1, DLVRMDOC_PRPRC,sum(DLVRMDOC_ITMQT) ITMQT, DLVRMDOC_PRPRC* SUM(DLVRMDOC_ITMQT) AMNT ,RTRIM(MITM_STKUOM) MITM_STKUOM,DLVRMDOC_TYPE,DLVRMDOC_NOPEN
        ,DLVRMDOC_AJU,DLVRMDOC_DO,MITM_ITMCDCUS from DLVRMDOC_TBL 
        LEFT JOIN MITM_TBL ON DLVRMDOC_ITMID=MITM_ITMCD
        WHERE DLVRMDOC_TXID=?
        group by DLVRMDOC_ITMID,MITM_ITMD1, DLVRMDOC_PRPRC,DLVRMDOC_TXID,MITM_STKUOM,DLVRMDOC_TYPE,DLVRMDOC_NOPEN,DLVRMDOC_AJU,DLVRMDOC_DO,MITM_ITMCDCUS) VINV
        LEFT JOIN (
        SELECT DLV_ID,MDEL_ZNAMA,MDEL_ADDRCUSTOMS,DLV_DATE,ISNULL(DLV_INVNO,'') DLV_INVNO
        ,ISNULL(DLV_SMTINVNO,'') DLV_SMTINVNO,RTRIM(MCUS_CURCD) MCUS_CURCD,DLV_INVDT,isnull(DLV_NOPEN,'') DLV_NOPEN,DLV_ITMCD
        ,DLV_ITMD1,DLV_ITMSPTNO,max(DLV_BCTYPE) DLV_BCTYPE FROM DLV_TBL 
        LEFT JOIN MDEL_TBL ON DLV_CONSIGN=MDEL_DELCD
        LEFT JOIN (select MSUP_SUPCD MCUS_CUSCD, max(MSUP_SUPCR) MCUS_CURCD from v_supplier_customer_union group by MSUP_SUPCD) vcust ON DLV_CUSTCD=MCUS_CUSCD
        WHERE DLV_ID=?
        GROUP BY DLV_ID,MDEL_ZNAMA,MDEL_ADDRCUSTOMS,DLV_DATE,DLV_INVNO,DLV_SMTINVNO,MCUS_CURCD,DLV_INVDT,DLV_NOPEN,DLV_ITMCD,DLV_ITMD1,DLV_ITMSPTNO
        ) VH ON DLVRMDOC_TXID=DLV_ID AND DLVRMDOC_ITMID=DLV_ITMCD
        ORDER BY DLVRMDOC_ITMID";
        $query = $this->db->query($qry, [$ptxid, $ptxid]);
        return $query->result_array();
    }
    public function select_invoice_posting($ptxid)
    {
        $qry = "SELECT VINV.*,MDEL_ZNAMA,MDEL_ADDRCUSTOMS,DLV_DATE, DLV_INVNO
        ,DLV_SMTINVNO,MCUS_CURCD,DLV_INVDT,DLV_NOPEN,DLV_ITMD1,DLV_ITMSPTNO FROM
        (select DLVRMDOC_TXID,DLVRMDOC_ITMID,RTRIM(MITM_ITMD1) MITM_ITMD1, DLVRMDOC_PRPRC,sum(DLVRMDOC_ITMQT) ITMQT, DLVRMDOC_PRPRC* SUM(DLVRMDOC_ITMQT) AMNT ,RTRIM(MITM_STKUOM) MITM_STKUOM,DLVRMDOC_DO
        from DLVRMDOC_TBL LEFT JOIN MITM_TBL ON DLVRMDOC_ITMID=MITM_ITMCD
        WHERE DLVRMDOC_TXID=?
        group by DLVRMDOC_ITMID,MITM_ITMD1, DLVRMDOC_PRPRC,DLVRMDOC_TXID,MITM_STKUOM,DLVRMDOC_DO) VINV
        LEFT JOIN (
        SELECT DLV_ID,MDEL_ZNAMA,MDEL_ADDRCUSTOMS,DLV_DATE,ISNULL(DLV_INVNO,'') DLV_INVNO
        ,ISNULL(DLV_SMTINVNO,'') DLV_SMTINVNO,RTRIM(MCUS_CURCD) MCUS_CURCD,DLV_INVDT,isnull(DLV_NOPEN,'') DLV_NOPEN,DLV_ITMCD,DLV_ITMD1,DLV_ITMSPTNO FROM DLV_TBL 
        LEFT JOIN MDEL_TBL ON DLV_CONSIGN=MDEL_DELCD
        LEFT JOIN MCUS_TBL ON DLV_CUSTCD=MCUS_CUSCD
        WHERE DLV_ID=?
        GROUP BY DLV_ID,MDEL_ZNAMA,MDEL_ADDRCUSTOMS,DLV_DATE,DLV_INVNO,DLV_SMTINVNO,MCUS_CURCD,DLV_INVDT,DLV_NOPEN,DLV_ITMCD,DLV_ITMD1,DLV_ITMSPTNO
        ) VH ON DLVRMDOC_TXID=DLV_ID AND DLVRMDOC_ITMID=DLV_ITMCD
        ORDER BY DLVRMDOC_ITMID";
        $query = $this->db->query($qry, [$ptxid, $ptxid]);
        return $query->result_array();
    }
}
