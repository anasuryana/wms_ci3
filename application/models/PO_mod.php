<?php

class PO_mod extends CI_Model
{
    private $TABLENAME = "PO_TBL";
    private $TABLENAME0 = "PO0_TBL";
    private $TABLENAME_DISCOUNT = "PODISC_TBL";
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
    public function insertb_discount($data)
    {
        $this->db->insert_batch($this->TABLENAME_DISCOUNT, $data);
        return $this->db->affected_rows();
    }
    public function insertBatchHeader($data)
    {
        $this->db->insert_batch($this->TABLENAME0, $data);
        return $this->db->affected_rows();
    }
    public function delete($pwhere)
    {
        $this->db->where($pwhere);
        $this->db->delete($this->TABLENAME);
        return $this->db->affected_rows();
    }
    public function delete_discount($pwhere)
    {
        $this->db->where($pwhere);
        $this->db->delete($this->TABLENAME_DISCOUNT);
        return $this->db->affected_rows();
    }
    public function delete_header($pwhere)
    {
        $this->db->where($pwhere);
        $this->db->delete($this->TABLENAME0);
        return $this->db->affected_rows();
    }
    public function select_all()
    {
        $this->db->from($this->TABLENAME);
        $query = $this->db->get();
        return $query->result_array();
    }
    public function check_Primary($data)
    {
        return $this->db->get_where($this->TABLENAME, $data)->num_rows();
    }
    public function checkPrimaryHeader($data)
    {
        return $this->db->get_where($this->TABLENAME0, $data)->num_rows();
    }

    public function update_where($pdata, $pwhere)
    {
        $this->db->where($pwhere);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
    }

    public function update_discount_where($pdata, $pwhere)
    {
        $this->db->where($pwhere);
        $this->db->update($this->TABLENAME_DISCOUNT, $pdata);
        return $this->db->affected_rows();
    }

    public function update_header_where($pdata, $pwhere)
    {
        $this->db->where($pwhere);
        $this->db->update($this->TABLENAME0, $pdata);
        return $this->db->affected_rows();
    }

    public function select_docnum_patterned($pyear)
    {
        $qry = "select ISNULL(MAX(CONVERT(INT,SUBSTRING(PO_NO,5,4))),0) lser
        from " . $this->TABLENAME . " where YEAR(PO_ISSUDT)=?";
        $query = $this->db->query($qry, [$pyear]);
        if ($query->num_rows() > 0) {
            $ret = $query->row();
            return $ret->lser;
        } else {
            return '0';
        }
    }

    public function select_header_where($pwhere)
    {
        $this->db->select("PO_NO,PO_RMRK,PO_PPH,PO_VAT,PO_SUPCD,MSUP_SUPNM,MSUP_SUPCR,PO_RQSTBY,PO_PAYTERM,PO_REQDT,PO_ISSUDT,PO_SHPDLV,PO_SHPCOST,MAX(PO_DEPT) PO_DEPT");
        $this->db->from($this->TABLENAME);
        $this->db->where($pwhere);
        $this->db->join("(select MSUP_SUPCD,MAX(MSUP_SUPNM) MSUP_SUPNM,MAX(MSUP_SUPCR) MSUP_SUPCR FROM v_supplier_customer_union GROUP BY MSUP_SUPCD) V1", "PO_SUPCD=MSUP_SUPCD", "left");
        $this->db->group_by("PO_NO,PO_RMRK,PO_PPH,PO_VAT,PO_SUPCD,MSUP_SUPNM,MSUP_SUPCR,PO_RQSTBY,PO_PAYTERM,PO_REQDT,PO_ISSUDT,PO_SHPDLV,PO_SHPCOST");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_header_like($plike)
    {
        $this->db->select("PO_NO,PO_RMRK,PO_PPH,PO_VAT,PO_SUPCD,MSUP_SUPNM,MSUP_SUPCR,PO_RQSTBY,PO_PAYTERM,PO_REQDT,PO_ISSUDT,PO_SHPDLV,PO_SHPCOST,MAX(PO_DEPT) PO_DEPT,,ISNULL(PO0_ISCUSTOMS,'-') PO0_ISCUSTOMS");
        $this->db->from($this->TABLENAME);
        $this->db->join("(select MSUP_SUPCD,rtrim(MAX(MSUP_SUPNM)) MSUP_SUPNM,MAX(MSUP_SUPCR) MSUP_SUPCR FROM v_supplier_customer_union GROUP BY MSUP_SUPCD) V1", "PO_SUPCD=MSUP_SUPCD", "left");
        $this->db->join($this->TABLENAME0, "PO_NO=PO0_NO", "LEFT");
        $this->db->like($plike);
        $this->db->group_by("PO_NO,PO_RMRK,PO_PPH,PO_VAT,PO_SUPCD,MSUP_SUPNM,MSUP_SUPCR,PO_RQSTBY,PO_PAYTERM,PO_REQDT,PO_ISSUDT,PO_SHPDLV,PO_SHPCOST,PO0_ISCUSTOMS");
        $this->db->order_by("PO_ISSUDT");
        $query = $this->db->get();
        return $query->result_array();
    }
    public function select_header_period_like($pdate0, $pdate1, $plike)
    {
        $this->db->select("PO_NO,PO_RMRK,PO_PPH,PO_VAT,PO_SUPCD,MSUP_SUPNM,MSUP_SUPCR,PO_RQSTBY,PO_PAYTERM,PO_REQDT,PO_ISSUDT,PO_SHPDLV,PO_SHPCOST,MAX(PO_DEPT) PO_DEPT,ISNULL(PO0_ISCUSTOMS,'-') PO0_ISCUSTOMS");
        $this->db->from($this->TABLENAME);
        $this->db->join("(select MSUP_SUPCD,rtrim(MAX(MSUP_SUPNM)) MSUP_SUPNM,MAX(MSUP_SUPCR) MSUP_SUPCR FROM v_supplier_customer_union GROUP BY MSUP_SUPCD) V1", "PO_SUPCD=MSUP_SUPCD", "left");
        $this->db->join($this->TABLENAME0, "PO_NO=PO0_NO", "LEFT");
        $this->db->like($plike)->where("PO_ISSUDT>=", $pdate0)->where("PO_ISSUDT<=", $pdate1);
        $this->db->group_by("PO_NO,PO_RMRK,PO_PPH,PO_VAT,PO_SUPCD,MSUP_SUPNM,MSUP_SUPCR,PO_RQSTBY,PO_PAYTERM,PO_REQDT,PO_ISSUDT,PO_SHPDLV,PO_SHPCOST");
        $this->db->order_by("PO_ISSUDT");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_detail_where($pwhere)
    {
        $this->db->select($this->TABLENAME . ".*, RTRIM(MITM_ITMD1) MITM_ITMD1,MITM_STKUOM,MSUP_SUPNM,MSUP_SUPCR,MSUP_ADDR1,MSUP_TELNO,MSUP_FAXNO");
        $this->db->from($this->TABLENAME);
        $this->db->join("MITM_TBL", "PO_ITMCD=MITM_ITMCD", "LEFT");
        $this->db->join("MSUP_TBL", "PO_SUPCD=MSUP_SUPCD", "left");
        $this->db->where($pwhere);
        $this->db->order_by("PO_DISC DESC,PO_LINE, PO_ITMNM, MITM_ITMD1");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_discount_detail_where($pwhere)
    {
        $this->db->from($this->TABLENAME_DISCOUNT);
        $this->db->where($pwhere);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_discount_where_PO_in($pPO)
    {
        $this->db->from($this->TABLENAME_DISCOUNT);
        $this->db->where_in("PODISC_PONO", $pPO);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_maxline($pdoc)
    {
        $this->db->select("MAX(PO_LINE) LLINE");
        $this->db->from($this->TABLENAME);
        $this->db->where("PO_NO", $pdoc);
        return $this->db->get()->row()->LLINE;
    }
    public function select_discount_maxline($pdoc)
    {
        $this->db->select("MAX(PODISC_LINE) LLINE");
        $this->db->from($this->TABLENAME_DISCOUNT);
        $this->db->where("PODISC_PONO", $pdoc);
        $query = $this->db->get();
        return $query->row()->LLINE == '' ? 0 : $query->row()->LLINE;
    }

    public function select_balance_like($plike)
    {
        $this->db->select($this->TABLENAME . ".*, RTRIM(MITM_ITMD1) MITM_ITMD1,MITM_STKUOM,MSUP_SUPNM,MSUP_SUPCR,MSUP_ADDR1,MSUP_TELNO,MSUP_FAXNO,ISNULL(RCVQTY,0) RCVQTY, 0 SPECIALDISC");
        $this->db->from($this->TABLENAME);
        $this->db->join("PO0_TBL", "PO_NO=PO0_NO", "LEFT");
        $this->db->join("MITM_TBL", "PO_ITMCD=MITM_ITMCD", "LEFT");
        $this->db->join("MSUP_TBL", "PO_SUPCD=MSUP_SUPCD", "LEFT");
        $this->db->join("(SELECT RCV_PO,RCV_ITMCD,SUM(RCV_QTY) RCVQTY FROM RCV_TBL GROUP BY RCV_PO,RCV_ITMCD) VRCV", "PO_NO=RCV_PO AND PO_ITMCD=RCV_ITMCD", "LEFT");
        $this->db->like($plike);
        $this->db->where("ISNULL(RCVQTY,0) < PO_QTY", null, false);
        $this->db->where("PO_ITMCD is not null", null, false);        
        $this->db->order_by("PO_REQDT");
        $query = $this->db->get();
        return $query->result_array();
    }
    public function select_balance_mega_like($plike)
    {
        $this->db->from('v_mega_po');
        $this->db->like($plike);
        $this->db->order_by("PO_REQDT");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_balance_nonitem_like($plike)
    {
        $this->db->select("VPO_RESUME.*,RTRIM(PO_ITMNM) MITM_ITMD1,PO_UM MITM_STKUOM,MSUP_SUPNM,MSUP_SUPCR,MSUP_ADDR1,MSUP_TELNO,MSUP_FAXNO,ISNULL(RCVQTY,0) RCVQTY");
        $this->db->from('VPO_RESUME');
        $this->db->join("MSUP_TBL", "PO_SUPCD=MSUP_SUPCD", "LEFT");
        $this->db->join("PO0_TBL", "PO_NO=PO0_NO", "LEFT");
        $this->db->join("(SELECT RCVNI_PO,RCVNI_ITMNM,SUM(RCVNI_QTY) RCVQTY FROM RCVNI_TBL GROUP BY RCVNI_PO,RCVNI_ITMNM) VRCV", "PO_NO=RCVNI_PO AND PO_ITMNM=RCVNI_ITMNM", "LEFT");
        $this->db->like($plike)->where("ISNULL(RCVQTY,0) < PO_QTY", null, false)->where("PO_ITMCD is null", null, false);
        $this->db->order_by("PO_REQDT");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_report($pdate0, $pdate1)
    {
        $qry = "SELECT VPOITEM.*,ISNULL(RCV_QTY,0) RCV_QTY,RCV_RCVDATE,SUPNM,CURRENCY FROM
        (select PO_NO,PO_ISSUDT,PO_SUPCD,PO_RMRK,PO_SHPDLV,PO_PAYTERM,PO_PPH,PO_VAT,SUM(PO_QTY)*PO_PRICE AMOUNT, PO_ITMCD,RTRIM(MITM_ITMD1) MITM_ITMD1, '' ITMGROUP
        ,SUM(PO_QTY) TQTY,RTRIM(MITM_STKUOM) MITM_STKUOM,PO_PRICE,SUM(PO_DISC) PO_DISC
        ,(SUM(PO_QTY)*PO_PRICE)-SUM(PO_QTY)*PO_PRICE*SUM(PO_DISC)/100 NETAMOUNT from PO_TBL
        LEFT JOIN MITM_TBL ON PO_ITMCD=MITM_ITMCD
        where (PO_ISSUDT BETWEEN ? AND ? ) AND PO_ITMCD IS NOT NULL
        GROUP BY PO_NO,PO_ISSUDT,PO_SUPCD,PO_RMRK,PO_SHPDLV,PO_PAYTERM,PO_PPH,PO_VAT,PO_PRICE, PO_ITMCD,MITM_ITMD1,MITM_STKUOM) VPOITEM
        LEFT JOIN
        (
        SELECT RCV_PO,RCV_ITMCD,SUM(RCV_QTY) RCV_QTY,MAX(RCV_RCVDATE) RCV_RCVDATE FROM RCV_TBL GROUP BY RCV_PO,RCV_ITMCD
        ) VRCV ON PO_NO=RCV_PO AND PO_ITMCD=RCV_ITMCD
        LEFT JOIN (
            SELECT MSUP_SUPCD,MAX(MSUP_SUPNM) SUPNM, MAX(MSUP_SUPCR) CURRENCY FROM v_supplier_customer_union GROUP BY  MSUP_SUPCD
        ) VSUP ON PO_SUPCD=MSUP_SUPCD
        UNION ALL

        SELECT VPOITEM.*,ISNULL(RCV_QTY,0) RCV_QTY,RCV_RCVDATE,SUPNM,CURRENCY FROM
        (select PO_NO,PO_ISSUDT,PO_SUPCD,PO_RMRK,PO_SHPDLV,PO_PAYTERM,PO_PPH,PO_VAT,SUM(PO_QTY)*PO_PRICE AMOUNT, 'NON ITEM' PO_ITMCD,PO_ITMNM MITM_ITMD1, '' ITMGROUP
        ,SUM(PO_QTY) TQTY,PO_UM MITM_STKUOM,PO_PRICE,SUM(PO_DISC) PO_DISC
        ,(SUM(PO_QTY)*PO_PRICE)-SUM(PO_QTY)*PO_PRICE*SUM(PO_DISC)/100 NETAMOUNT from PO_TBL
        where (PO_ISSUDT BETWEEN ? AND ? ) AND PO_ITMCD IS NULL
        GROUP BY PO_NO,PO_ISSUDT,PO_SUPCD,PO_RMRK,PO_SHPDLV,PO_PAYTERM,PO_PPH,PO_VAT,PO_PRICE, PO_ITMNM,PO_UM) VPOITEM
        LEFT JOIN
        (
        SELECT RCVNI_PO,RCVNI_ITMNM,SUM(RCVNI_QTY) RCV_QTY,MAX(RCVNI_RCVDATE) RCV_RCVDATE FROM RCVNI_TBL GROUP BY RCVNI_PO,RCVNI_ITMNM
        ) VRCV ON PO_NO=RCVNI_PO AND MITM_ITMD1=RCVNI_ITMNM
        LEFT JOIN (
            SELECT MSUP_SUPCD,MAX(MSUP_SUPNM) SUPNM, MAX(MSUP_SUPCR) CURRENCY FROM v_supplier_customer_union GROUP BY  MSUP_SUPCD
        ) VSUP ON PO_SUPCD=MSUP_SUPCD";

        $query = $this->db->query($qry, [$pdate0, $pdate1, $pdate0, $pdate1]);
        return $query->result_array();
    }

    public function select_column_history($pcols)
    {
        $this->db->select($pcols);
        $this->db->from($this->TABLENAME);
        $this->db->where("PO_SHPDLV !=", "");
        $this->db->group_by($pcols);
        $query = $this->db->get();
        return $query->result_array();
    }
}
