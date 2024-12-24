<?php

class DELV_mod extends CI_Model
{
    private $TABLENAME = "DLV_TBL";
    private $VIEWUNION = "(select * from wms_v_outpabean where isnull(NOMPEN,'')!='' union
    select * from wms_v_outpabean_rtn where SER_ITMID is not null and isnull(NOMPEN,'')!='' union
    select * from wms_v_outpabean_oth where isnull(NOMPEN,'')!='' union
    select * from wms_v_outpabean_scr where isnull(NOMPEN,'')!='') v1";
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
    public function insertb_pkg($data)
    {
        $this->db->insert_batch('DLV_PKG_TBL', $data);
        return $this->db->affected_rows();
    }
    public function insert_address($data)
    {
        $this->db->insert('MDEL_TBL', $data);
        return $this->db->affected_rows();
    }

    public function check_Primary($data)
    {
        return $this->db->get_where($this->TABLENAME, $data)->num_rows();
    }
    public function check_Primary_address($data)
    {
        return $this->db->get_where("MDEL_TBL", $data)->num_rows();
    }

    public function updatebyVAR($pdata, $pwhere)
    {
        $this->db->where($pwhere);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
    }

    public function updatebySER($pdata, $pSer)
    {
        $this->db->where_in('DLV_SER', $pSer);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
    }

    public function updatebyVAR_DELCD($pdata, $pwhere)
    {
        $this->db->where($pwhere);
        $this->db->update('MDEL_TBL', $pdata);
        return $this->db->affected_rows();
    }

    public function updatebyVAR_pkg($pdata, $pwhere)
    {
        $this->db->where($pwhere);
        $this->db->update('DLV_PKG_TBL', $pdata);
        return $this->db->affected_rows();
    }

    public function selectDLVColumnsWhere($columns , $pWhere)
    {
        $this->db->select($columns);
        $this->db->from($this->TABLENAME);
        $this->db->join('SER_TBL', 'DLV_SER=SER_ID', "LEFT");
        $this->db->where($pWhere);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectDELCD_where($pwhere)
    {
        $this->db->from("MDEL_TBL");
        $this->db->where($pwhere);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_bytx($ptxid)
    {
        $this->db->limit(101);
        $this->db->select("DLV_ID,DLV_DATE,DLV_DSCRPTN,DLV_CUSTCD,RTRIM(MCUS_CUSNM) MCUS_CUSNM,DLV_INVNO,DLV_INVDT,DLV_SMTINVNO,DLV_TRANS,DLV_RMRK,RTRIM(MCUS_CURCD) MCUS_CURCD,MSTTRANS_TYPE,DLV_CONSIGN,DLV_RPLCMNT,DLV_VAT,
        DLV_KNBNDLV,ISNULL(DLV_FROMOFFICE,'-') DLV_FROMOFFICE ,crtd.MSTEMP_FNM DLV_CRTD,MAX(DLV_CRTDTM) DLV_CRTDTM,DLV_LUPDT,lupd.MSTEMP_FNM DLV_USRID,
        appr.MSTEMP_FNM DLV_APPRV,max(DLV_APPRVTM) DLV_APPRVTM,post.MSTEMP_FNM DLV_POST,DLV_POSTTM,
        isnull(DLV_BCTYPE,'-') DLV_BCTYPE,ISNULL(DLV_NOAJU,'') DLV_NOAJU,ISNULL(DLV_NOPEN,'') DLV_NOPEN,COALESCE(DLV_DESTOFFICE,'-') DLV_DESTOFFICE
        ,DLV_PURPOSE,DLV_BSGRP,ISNULL(DLV_CUSTDO,'') DLV_CUSTDO, ISNULL(MAX(DLVH_PEMBERITAHU),'') DLVH_PEMBERITAHU, ISNULL(MAX(DLVH_JABATAN),'') DLVH_JABATAN
        ,ISNULL(DLV_ZJENIS_TPB_ASAL,'-') DLV_ZJENIS_TPB_ASAL, ISNULL(DLV_ZJENIS_TPB_TUJUAN,'-') DLV_ZJENIS_TPB_TUJUAN,DLV_BCDATE
        ,isnull(DLV_ZSKB,'') DLV_ZSKB, isnull(DLV_ZKODE_CARA_ANGKUT,'-') DLV_ZKODE_CARA_ANGKUT,DLV_ZTANGGAL_SKB,ISNULL(DLV_CONA,'') DLV_CONA
        ,DLV_RPDATE, MAX(SI_WH) SI_WH,MAX(DLV_ZNOMOR_AJU) DLV_ZNOMOR_AJU,ISNULL(MAX(DLV_SPPBDOC),'') DLV_SPPBDOC");
        $this->db->from($this->TABLENAME . " a");
        $this->db->join("MCUS_TBL b", "a.DLV_CUSTCD=b.MCUS_CUSCD");
        $this->db->join("MSTTRANS_TBL c", "a.DLV_TRANS=c.MSTTRANS_ID", "LEFT");
        $this->db->join("MSTEMP_TBL crtd", "a.DLV_CRTD=crtd.MSTEMP_ID", "LEFT");
        $this->db->join("MSTEMP_TBL lupd", "a.DLV_USRID=lupd.MSTEMP_ID", "left");
        $this->db->join("MSTEMP_TBL appr", "a.DLV_APPRV=appr.MSTEMP_ID", "left");
        $this->db->join("MSTEMP_TBL post", "a.DLV_POST=post.MSTEMP_ID", "left");
        $this->db->join("SISCN_TBL", "a.DLV_SER=SISCN_SER", "left");
        $this->db->join("SI_TBL", "SISCN_LINENO=SI_LINENO", "left");
        $this->db->join("DLVH_TBL", "DLV_ID=DLVH_ID", "left");
        $this->db->like("DLV_ID", $ptxid)->where("ISNULL(DLV_SER,'') !=", "");
        $this->db->group_by("DLV_ID,DLV_DATE,DLV_DSCRPTN,DLV_CUSTCD,MCUS_CUSNM,DLV_INVNO,DLV_INVDT,DLV_SMTINVNO,DLV_TRANS,DLV_RMRK,MCUS_CURCD,MSTTRANS_TYPE,DLV_CONSIGN,DLV_RPLCMNT,DLV_VAT,DLV_KNBNDLV,DLV_FROMOFFICE
        ,crtd.MSTEMP_FNM,DLV_LUPDT,lupd.MSTEMP_FNM ,appr.MSTEMP_FNM,post.MSTEMP_FNM,DLV_POSTTM,DLV_BCTYPE,DLV_NOAJU,DLV_NOPEN,DLV_DESTOFFICE,DLV_PURPOSE,DLV_BSGRP,DLV_CUSTDO
        ,DLV_ZJENIS_TPB_ASAL,DLV_ZJENIS_TPB_TUJUAN,DLV_BCDATE,DLV_ZSKB,DLV_ZKODE_CARA_ANGKUT,DLV_ZTANGGAL_SKB,DLV_CONA,DLV_RPDATE");
        $this->db->order_by("DLV_DATE DESC, DLV_ID desc");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_bytx_rm($ptxid)
    {
        $this->db->limit(101);
        $this->db->select("DLV_ID,DLV_DATE,DLV_DSCRPTN,DLV_CUSTCD,MCUS_CUSNM,DLV_INVNO,DLV_INVDT,DLV_SMTINVNO,DLV_TRANS,MAX(DLV_RMRK) DLV_RMRK,MCUS_CURCD,MSTTRANS_TYPE,DLV_CONSIGN,DLV_RPLCMNT,DLV_VAT,
        DLV_KNBNDLV,ISNULL(DLV_FROMOFFICE,'-') DLV_FROMOFFICE ,crtd.MSTEMP_FNM DLV_CRTD,MAX(DLV_CRTDTM) DLV_CRTDTM,max(DLV_LUPDT) DLV_LUPDT,max(lupd.MSTEMP_FNM)    DLV_USRID,
        appr.MSTEMP_FNM DLV_APPRV,max(DLV_APPRVTM) DLV_APPRVTM,post.MSTEMP_FNM DLV_POST,DLV_POSTTM,
        isnull(DLV_BCTYPE,'-') DLV_BCTYPE,ISNULL(DLV_NOAJU,'') DLV_NOAJU
        ,ISNULL(DLV_NOPEN,'') DLV_NOPEN,COALESCE(DLV_DESTOFFICE,'-') DLV_DESTOFFICE,
        DLV_PURPOSE,DLV_BSGRP,ISNULL(DLV_CUSTDO,'') DLV_CUSTDO
        ,ISNULL(DLV_ZJENIS_TPB_ASAL,'-') DLV_ZJENIS_TPB_ASAL, ISNULL(DLV_ZJENIS_TPB_TUJUAN,'-') DLV_ZJENIS_TPB_TUJUAN,DLV_BCDATE
        ,isnull(DLV_ZSKB,'') DLV_ZSKB, isnull(DLV_ZKODE_CARA_ANGKUT,'-') DLV_ZKODE_CARA_ANGKUT,DLV_ZTANGGAL_SKB,ISNULL(DLV_CONA,'') DLV_CONA,DLV_RPDATE
        ,ISNULL(MAX(DLV_LOCFR),'') DLV_LOCFR,ISNULL(MAX(DLV_RPRDOC),'') DLV_RPRDOC, ISNULL(MAX(DLV_PARENTDOC),'') DLV_PARENTDOC,RPSTOCK_REMARK
        ,ISNULL(MAX(DLV_ZNOMOR_AJU),'') DLV_ZNOMOR_AJU
        ,ISNULL(MAX(DLVH_PEMBERITAHU),'') DLVH_PEMBERITAHU
        ,ISNULL(MAX(DLVH_JABATAN),'') DLVH_JABATAN");
        $this->db->from($this->TABLENAME . " a");
        $this->db->join("(SELECT MSUP_SUPCD MCUS_CUSCD,MAX(MSUP_SUPNM) MCUS_CUSNM, MAX(MSUP_SUPCR) MCUS_CURCD FROM v_supplier_customer_union GROUP BY MSUP_SUPCD) b", "a.DLV_CUSTCD=b.MCUS_CUSCD", "LEFT");
        $this->db->join("MSTTRANS_TBL c", "a.DLV_TRANS=c.MSTTRANS_ID", "left");
        $this->db->join("MSTEMP_TBL crtd", "a.DLV_CRTD=crtd.MSTEMP_ID", "LEFT");
        $this->db->join("MSTEMP_TBL lupd", "a.DLV_USRID=lupd.MSTEMP_ID", "left");
        $this->db->join("MSTEMP_TBL appr", "a.DLV_APPRV=appr.MSTEMP_ID", "left");
        $this->db->join("MSTEMP_TBL post", "a.DLV_POST=post.MSTEMP_ID", "left");
        $this->db->join("DLVH_TBL", "DLV_ID=DLVH_ID", "left");
        $this->db->join("(SELECT RPSTOCK_REMARK FROM ZRPSAL_BCSTOCK GROUP BY RPSTOCK_REMARK) VEXBC", "a.DLV_ID=RPSTOCK_REMARK", "left");
        $this->db->like("DLV_ID", $ptxid)->where("ISNULL(DLV_SER,'')", "");
        $this->db->group_by("DLV_ID,DLV_DATE,DLV_DSCRPTN,DLV_CUSTCD,MCUS_CUSNM,DLV_INVNO,DLV_INVDT,DLV_SMTINVNO,DLV_TRANS,MCUS_CURCD,MSTTRANS_TYPE,DLV_CONSIGN,DLV_RPLCMNT,DLV_VAT,DLV_KNBNDLV,DLV_FROMOFFICE
        ,crtd.MSTEMP_FNM,appr.MSTEMP_FNM,post.MSTEMP_FNM,DLV_POSTTM,DLV_BCTYPE,DLV_NOAJU,DLV_NOPEN,DLV_DESTOFFICE,DLV_PURPOSE,DLV_BSGRP,DLV_CUSTDO
        ,DLV_ZJENIS_TPB_ASAL,DLV_ZJENIS_TPB_TUJUAN,DLV_BCDATE,DLV_ZSKB,DLV_ZKODE_CARA_ANGKUT,DLV_ZTANGGAL_SKB,DLV_CONA,DLV_RPDATE,RPSTOCK_REMARK");
        $this->db->order_by("DLV_DATE DESC, DLV_ID desc");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_bytx_period($ptxid, $pmonth, $pyear)
    {
        $this->db->limit(9999);
        $this->db->select("DLV_ID,DLV_DATE,DLV_DSCRPTN,DLV_CUSTCD,RTRIM(MCUS_CUSNM) MCUS_CUSNM,DLV_INVNO,DLV_INVDT,DLV_SMTINVNO,DLV_TRANS,DLV_RMRK,RTRIM(MCUS_CURCD) MCUS_CURCD,MSTTRANS_TYPE,DLV_CONSIGN,DLV_RPLCMNT,DLV_VAT,
        DLV_KNBNDLV,ISNULL(DLV_FROMOFFICE,'-') DLV_FROMOFFICE ,crtd.MSTEMP_FNM DLV_CRTD,MAX(DLV_CRTDTM) DLV_CRTDTM,max(DLV_LUPDT) DLV_LUPDT,max(lupd.MSTEMP_FNM) DLV_USRID,
        appr.MSTEMP_FNM DLV_APPRV,max(DLV_APPRVTM) DLV_APPRVTM,post.MSTEMP_FNM DLV_POST,DLV_POSTTM
        ,ISNULL(DLV_BCTYPE,'-') DLV_BCTYPE,ISNULL(DLV_NOAJU,'') DLV_NOAJU
        ,ISNULL(DLV_NOPEN,'') DLV_NOPEN,COALESCE(DLV_DESTOFFICE,'-') DLV_DESTOFFICE
        ,DLV_PURPOSE,DLV_BSGRP,ISNULL(DLV_CUSTDO,'') DLV_CUSTDO, ISNULL(MAX(DLVH_PEMBERITAHU),'') DLVH_PEMBERITAHU, ISNULL(MAX(DLVH_JABATAN),'') DLVH_JABATAN
        ,ISNULL(DLV_ZJENIS_TPB_ASAL,'-') DLV_ZJENIS_TPB_ASAL, ISNULL(DLV_ZJENIS_TPB_TUJUAN,'-') DLV_ZJENIS_TPB_TUJUAN,DLV_BCDATE
        ,isnull(DLV_ZSKB,'') DLV_ZSKB, isnull(DLV_ZKODE_CARA_ANGKUT,'-') DLV_ZKODE_CARA_ANGKUT,DLV_ZTANGGAL_SKB,ISNULL(DLV_CONA,'') DLV_CONA,DLV_RPDATE,MAX(SI_WH) SI_WH,ISNULL(MAX(DLV_SPPBDOC),'') DLV_SPPBDOC");
        $this->db->from($this->TABLENAME . " a");
        $this->db->join("MCUS_TBL b", "a.DLV_CUSTCD=b.MCUS_CUSCD");
        $this->db->join("MSTTRANS_TBL c", "a.DLV_TRANS=c.MSTTRANS_ID");
        $this->db->join("MSTEMP_TBL crtd", "a.DLV_CRTD=crtd.MSTEMP_ID", "LEFT");
        $this->db->join("MSTEMP_TBL lupd", "a.DLV_USRID=lupd.MSTEMP_ID", "left");
        $this->db->join("MSTEMP_TBL appr", "a.DLV_APPRV=appr.MSTEMP_ID", "left");
        $this->db->join("MSTEMP_TBL post", "a.DLV_POST=post.MSTEMP_ID", "left");
        $this->db->join("SISCN_TBL", "a.DLV_SER=SISCN_SER", "left");
        $this->db->join("SI_TBL", "SISCN_LINENO=SI_LINENO", "left");
        $this->db->join("DLVH_TBL", "DLV_ID=DLVH_ID", "left");
        $this->db->like("DLV_ID", $ptxid)->where("ISNULL(DLV_SER,'') !=", "")->where("MONTH(DLV_CRTDTM)", $pmonth)->where("YEAR(DLV_CRTDTM)", $pyear);
        $this->db->group_by("DLV_ID,DLV_DATE,DLV_DSCRPTN,DLV_CUSTCD,MCUS_CUSNM,DLV_INVNO,DLV_INVDT,DLV_SMTINVNO,DLV_TRANS,DLV_RMRK,MCUS_CURCD,MSTTRANS_TYPE,DLV_CONSIGN,DLV_RPLCMNT,DLV_VAT,DLV_KNBNDLV,DLV_FROMOFFICE
        ,crtd.MSTEMP_FNM,appr.MSTEMP_FNM,post.MSTEMP_FNM,DLV_POSTTM,DLV_BCTYPE,DLV_NOAJU,DLV_NOPEN,DLV_DESTOFFICE,DLV_PURPOSE,DLV_BSGRP,DLV_CUSTDO
        ,DLV_ZJENIS_TPB_ASAL,DLV_ZJENIS_TPB_TUJUAN,DLV_BCDATE,DLV_ZSKB,DLV_ZKODE_CARA_ANGKUT,DLV_ZTANGGAL_SKB,DLV_CONA,DLV_RPDATE");
        $this->db->order_by("DLV_DATE DESC, DLV_ID desc");
        $query = $this->db->get();
        return $query->result_array();
    }
    public function select_bytx_period_rm($ptxid, $pmonth, $pyear)
    {
        $this->db->limit(9999);
        $this->db->select("DLV_ID,DLV_DATE,DLV_DSCRPTN,DLV_CUSTCD,MCUS_CUSNM,DLV_INVNO,DLV_INVDT,DLV_SMTINVNO,DLV_TRANS,MAX(DLV_RMRK) DLV_RMRK,MCUS_CURCD,MSTTRANS_TYPE,DLV_CONSIGN,DLV_RPLCMNT,DLV_VAT,
        DLV_KNBNDLV,ISNULL(DLV_FROMOFFICE,'-') DLV_FROMOFFICE ,crtd.MSTEMP_FNM DLV_CRTD,MAX(DLV_CRTDTM) DLV_CRTDTM,max(DLV_LUPDT) DLV_LUPDT,max(lupd.MSTEMP_FNM) DLV_USRID,
        appr.MSTEMP_FNM DLV_APPRV,max(DLV_APPRVTM) DLV_APPRVTM,post.MSTEMP_FNM DLV_POST,DLV_POSTTM
        ,ISNULL(DLV_BCTYPE,'-') DLV_BCTYPE,ISNULL(DLV_NOAJU,'') DLV_NOAJU
        ,ISNULL(DLV_NOPEN,'') DLV_NOPEN
        ,COALESCE(DLV_DESTOFFICE,'-') DLV_DESTOFFICE
        ,DLV_PURPOSE,DLV_BSGRP,ISNULL(DLV_CUSTDO,'') DLV_CUSTDO
        ,ISNULL(DLV_ZJENIS_TPB_ASAL,'-') DLV_ZJENIS_TPB_ASAL, ISNULL(DLV_ZJENIS_TPB_TUJUAN,'-') DLV_ZJENIS_TPB_TUJUAN,DLV_BCDATE
        ,isnull(DLV_ZSKB,'') DLV_ZSKB, isnull(DLV_ZKODE_CARA_ANGKUT,'-') DLV_ZKODE_CARA_ANGKUT,DLV_ZTANGGAL_SKB,ISNULL(DLV_CONA,'') DLV_CONA,DLV_RPDATE
        ,ISNULL(MAX(DLV_LOCFR),'') DLV_LOCFR,ISNULL(MAX(DLV_RPRDOC),'') DLV_RPRDOC, ISNULL(MAX(DLV_PARENTDOC),'') DLV_PARENTDOC,RPSTOCK_REMARK
        ,ISNULL(MAX(DLV_ZNOMOR_AJU),'') DLV_ZNOMOR_AJU
        ,ISNULL(MAX(DLVH_PEMBERITAHU),'') DLVH_PEMBERITAHU
        ,ISNULL(MAX(DLVH_JABATAN),'') DLVH_JABATAN");
        $this->db->from($this->TABLENAME . " a");
        $this->db->join("(SELECT MSUP_SUPCD MCUS_CUSCD,MAX(MSUP_SUPNM) MCUS_CUSNM, MAX(MSUP_SUPCR) MCUS_CURCD FROM v_supplier_customer_union GROUP BY MSUP_SUPCD) b", "a.DLV_CUSTCD=b.MCUS_CUSCD", "LEFT");
        $this->db->join("MSTTRANS_TBL c", "a.DLV_TRANS=c.MSTTRANS_ID", "LEFT");
        $this->db->join("MSTEMP_TBL crtd", "a.DLV_CRTD=crtd.MSTEMP_ID", "LEFT");
        $this->db->join("MSTEMP_TBL lupd", "a.DLV_USRID=lupd.MSTEMP_ID", "left");
        $this->db->join("MSTEMP_TBL appr", "a.DLV_APPRV=appr.MSTEMP_ID", "left");
        $this->db->join("MSTEMP_TBL post", "a.DLV_POST=post.MSTEMP_ID", "left");
        $this->db->join("DLVH_TBL", "DLV_ID=DLVH_ID", "left");
        $this->db->join("(SELECT RPSTOCK_REMARK FROM ZRPSAL_BCSTOCK GROUP BY RPSTOCK_REMARK) VEXBC", "a.DLV_ID=RPSTOCK_REMARK", "left");
        $this->db->like("DLV_ID", $ptxid)->where("ISNULL(DLV_SER,'')", "")->where("MONTH(DLV_CRTDTM)", $pmonth)->where("YEAR(DLV_CRTDTM)", $pyear);
        $this->db->group_by("DLV_ID,DLV_DATE,DLV_DSCRPTN,DLV_CUSTCD,MCUS_CUSNM,DLV_INVNO,DLV_INVDT,DLV_SMTINVNO,DLV_TRANS,MCUS_CURCD,MSTTRANS_TYPE,DLV_CONSIGN,DLV_RPLCMNT,DLV_VAT,DLV_KNBNDLV,DLV_FROMOFFICE
        ,crtd.MSTEMP_FNM,appr.MSTEMP_FNM,post.MSTEMP_FNM,DLV_POSTTM,DLV_BCTYPE,DLV_NOAJU,DLV_NOPEN,DLV_DESTOFFICE,DLV_PURPOSE,DLV_BSGRP,DLV_CUSTDO
        ,DLV_ZJENIS_TPB_ASAL,DLV_ZJENIS_TPB_TUJUAN,DLV_BCDATE,DLV_ZSKB,DLV_ZKODE_CARA_ANGKUT,DLV_ZTANGGAL_SKB,DLV_CONA,DLV_RPDATE,RPSTOCK_REMARK");
        $this->db->order_by("DLV_DATE DESC, DLV_ID desc");
        $query = $this->db->get();
        return $query->result_array();
    }
    public function select_bydt($ptxdt)
    {
        $this->db->limit(9999);
        $this->db->select("DLV_ID,DLV_DATE,DLV_DSCRPTN,DLV_CUSTCD,RTRIM(MCUS_CUSNM) MCUS_CUSNM,DLV_INVNO,DLV_INVDT,DLV_SMTINVNO,DLV_TRANS,DLV_RMRK,RTRIM(MCUS_CURCD) MCUS_CURCD,MSTTRANS_TYPE,DLV_CONSIGN,DLV_RPLCMNT,DLV_VAT,
        DLV_KNBNDLV,ISNULL(DLV_FROMOFFICE,'-') DLV_FROMOFFICE
        ,crtd.MSTEMP_FNM DLV_CRTD,MAX(DLV_CRTDTM) DLV_CRTDTM,DLV_LUPDT,lupd.MSTEMP_FNM DLV_USRID, appr.MSTEMP_FNM DLV_APPRV,max(DLV_APPRVTM) DLV_APPRVTM,
        post.MSTEMP_FNM DLV_POST,DLV_POSTTM,isnull(DLV_BCTYPE,'-') DLV_BCTYPE
        ,ISNULL(DLV_NOAJU,'') DLV_NOAJU
        ,ISNULL(DLV_NOPEN,'') DLV_NOPEN, ISNULL(MAX(DLVH_PEMBERITAHU),'') DLVH_PEMBERITAHU, ISNULL(MAX(DLVH_JABATAN),'') DLVH_JABATAN
        ,COALESCE(DLV_DESTOFFICE,'-') DLV_DESTOFFICE
        ,DLV_PURPOSE,DLV_BSGRP,ISNULL(DLV_CUSTDO,'') DLV_CUSTDO
        ,ISNULL(DLV_ZJENIS_TPB_ASAL,'-') DLV_ZJENIS_TPB_ASAL, ISNULL(DLV_ZJENIS_TPB_TUJUAN,'-') DLV_ZJENIS_TPB_TUJUAN,DLV_BCDATE
        ,isnull(DLV_ZSKB,'') DLV_ZSKB,isnull(DLV_ZKODE_CARA_ANGKUT,'-') DLV_ZKODE_CARA_ANGKUT,DLV_ZTANGGAL_SKB,ISNULL(DLV_CONA,'') DLV_CONA,DLV_RPDATE,MAX(SI_WH) SI_WH,ISNULL(MAX(DLV_SPPBDOC),'') DLV_SPPBDOC");
        $this->db->from($this->TABLENAME . " a");
        $this->db->join("MCUS_TBL b", "a.DLV_CUSTCD=b.MCUS_CUSCD");
        $this->db->join("MSTTRANS_TBL c", "a.DLV_TRANS=c.MSTTRANS_ID");
        $this->db->join("MSTEMP_TBL crtd", "a.DLV_CRTD=crtd.MSTEMP_ID");
        $this->db->join("MSTEMP_TBL lupd", "a.DLV_USRID=lupd.MSTEMP_ID", "left");
        $this->db->join("MSTEMP_TBL appr", "a.DLV_APPRV=appr.MSTEMP_ID", "left");
        $this->db->join("MSTEMP_TBL post", "a.DLV_POST=post.MSTEMP_ID", "left");
        $this->db->join("SISCN_TBL", "a.DLV_SER=SISCN_SER", "left");
        $this->db->join("SI_TBL", "SISCN_LINENO=SI_LINENO", "left");
        $this->db->join("DLVH_TBL", "DLV_ID=DLVH_ID", "left");
        $this->db->where("DLV_DATE", $ptxdt)->where("ISNULL(DLV_SER,'') !=", "");
        $this->db->group_by("DLV_ID,DLV_DATE,DLV_DSCRPTN,DLV_CUSTCD,MCUS_CUSNM,DLV_INVNO,DLV_INVDT,DLV_SMTINVNO,DLV_TRANS,DLV_RMRK,MCUS_CURCD,MSTTRANS_TYPE,DLV_CONSIGN,DLV_RPLCMNT,DLV_VAT,DLV_KNBNDLV,DLV_FROMOFFICE
        ,crtd.MSTEMP_FNM,DLV_LUPDT,lupd.MSTEMP_FNM ,appr.MSTEMP_FNM,post.MSTEMP_FNM,DLV_POSTTM,DLV_BCTYPE,DLV_NOAJU,DLV_NOPEN,DLV_DESTOFFICE,DLV_PURPOSE,DLV_BSGRP,DLV_CUSTDO
        ,DLV_ZJENIS_TPB_ASAL,DLV_ZJENIS_TPB_TUJUAN,DLV_BCDATE,DLV_ZSKB,DLV_ZKODE_CARA_ANGKUT,DLV_ZTANGGAL_SKB,DLV_CONA,DLV_RPDATE");
        $this->db->order_by("DLV_ID desc,DLV_DATE desc");
        $query = $this->db->get();
        return $query->result_array();
    }
    public function select_bydt_rm($ptxdt)
    {
        $this->db->limit(9999);
        $this->db->select("DLV_ID,DLV_DATE,DLV_DSCRPTN,DLV_CUSTCD,MCUS_CUSNM,DLV_INVNO,DLV_INVDT,DLV_SMTINVNO,DLV_TRANS,MAX(DLV_RMRK) DLV_RMRK,MCUS_CURCD,MSTTRANS_TYPE,DLV_CONSIGN,DLV_RPLCMNT,DLV_VAT,
        DLV_KNBNDLV,ISNULL(DLV_FROMOFFICE,'-') DLV_FROMOFFICE
        ,crtd.MSTEMP_FNM DLV_CRTD,MAX(DLV_CRTDTM) DLV_CRTDTM,DLV_LUPDT,lupd.MSTEMP_FNM DLV_USRID, appr.MSTEMP_FNM DLV_APPRV,max(DLV_APPRVTM) DLV_APPRVTM,
        post.MSTEMP_FNM DLV_POST,DLV_POSTTM,isnull(DLV_BCTYPE,'-') DLV_BCTYPE
        ,ISNULL(DLV_NOAJU,'') DLV_NOAJU
        ,ISNULL(DLV_NOPEN,'') DLV_NOPEN
        ,ISNULL(DLV_DESTOFFICE,'-') DLV_DESTOFFICE,DLV_PURPOSE,DLV_BSGRP,ISNULL(DLV_CUSTDO,'') DLV_CUSTDO
        ,ISNULL(DLV_ZJENIS_TPB_ASAL,'-') DLV_ZJENIS_TPB_ASAL, ISNULL(DLV_ZJENIS_TPB_TUJUAN,'-') DLV_ZJENIS_TPB_TUJUAN,DLV_BCDATE
        ,isnull(DLV_ZSKB,'') DLV_ZSKB,isnull(DLV_ZKODE_CARA_ANGKUT,'-') DLV_ZKODE_CARA_ANGKUT,DLV_ZTANGGAL_SKB,ISNULL(DLV_CONA,'') DLV_CONA,DLV_RPDATE
        ,ISNULL(MAX(DLV_LOCFR),'') DLV_LOCFR,ISNULL(MAX(DLV_RPRDOC),'') DLV_RPRDOC, ISNULL(MAX(DLV_PARENTDOC),'') DLV_PARENTDOC,RPSTOCK_REMARK
        ,ISNULL(MAX(DLV_ZNOMOR_AJU),'') DLV_ZNOMOR_AJU
        ,ISNULL(MAX(DLVH_PEMBERITAHU),'') DLVH_PEMBERITAHU
        ,ISNULL(MAX(DLVH_JABATAN),'') DLVH_JABATAN");
        $this->db->from($this->TABLENAME . " a");
        $this->db->join("(SELECT MSUP_SUPCD MCUS_CUSCD,MAX(MSUP_SUPNM) MCUS_CUSNM, MAX(MSUP_SUPCR) MCUS_CURCD FROM v_supplier_customer_union GROUP BY MSUP_SUPCD) b", "a.DLV_CUSTCD=b.MCUS_CUSCD", "LEFT");
        $this->db->join("MSTTRANS_TBL c", "a.DLV_TRANS=c.MSTTRANS_ID", "LEFT");
        $this->db->join("MSTEMP_TBL crtd", "a.DLV_CRTD=crtd.MSTEMP_ID", "LEFT");
        $this->db->join("MSTEMP_TBL lupd", "a.DLV_USRID=lupd.MSTEMP_ID", "left");
        $this->db->join("MSTEMP_TBL appr", "a.DLV_APPRV=appr.MSTEMP_ID", "left");
        $this->db->join("MSTEMP_TBL post", "a.DLV_POST=post.MSTEMP_ID", "left");
        $this->db->join("DLVH_TBL", "DLV_ID=DLVH_ID", "left");
        $this->db->join("(SELECT RPSTOCK_REMARK FROM ZRPSAL_BCSTOCK GROUP BY RPSTOCK_REMARK) VEXBC", "a.DLV_ID=RPSTOCK_REMARK", "left");
        $this->db->where("DLV_DATE", $ptxdt)->where("ISNULL(DLV_SER,'')", "");
        $this->db->group_by("DLV_ID,DLV_DATE,DLV_DSCRPTN,DLV_CUSTCD,MCUS_CUSNM,DLV_INVNO,DLV_INVDT,DLV_SMTINVNO,DLV_TRANS,MCUS_CURCD,MSTTRANS_TYPE,DLV_CONSIGN,DLV_RPLCMNT,DLV_VAT,DLV_KNBNDLV,DLV_FROMOFFICE
        ,crtd.MSTEMP_FNM,DLV_LUPDT,lupd.MSTEMP_FNM ,appr.MSTEMP_FNM,post.MSTEMP_FNM,DLV_POSTTM,DLV_BCTYPE,DLV_NOAJU,DLV_NOPEN,DLV_DESTOFFICE,DLV_PURPOSE,DLV_BSGRP,DLV_CUSTDO
        ,DLV_ZJENIS_TPB_ASAL,DLV_ZJENIS_TPB_TUJUAN,DLV_BCDATE,DLV_ZSKB,DLV_ZKODE_CARA_ANGKUT,DLV_ZTANGGAL_SKB,DLV_CONA,DLV_RPDATE,RPSTOCK_REMARK");
        $this->db->order_by("DLV_ID desc,DLV_DATE desc");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_bycustomer($ptxcus)
    {
        $this->db->limit(9999);
        $this->db->select("DLV_ID,DLV_DATE,DLV_DSCRPTN,DLV_CUSTCD,RTRIM(MCUS_CUSNM) MCUS_CUSNM,DLV_INVNO,DLV_INVDT,DLV_SMTINVNO,DLV_TRANS,DLV_RMRK,RTRIM(MCUS_CURCD) MCUS_CURCD,MSTTRANS_TYPE,DLV_CONSIGN,DLV_RPLCMNT,DLV_VAT,
        DLV_KNBNDLV,ISNULL(DLV_FROMOFFICE,'-') DLV_FROMOFFICE
        ,crtd.MSTEMP_FNM DLV_CRTD,MAX(DLV_CRTDTM) DLV_CRTDTM,DLV_LUPDT,lupd.MSTEMP_FNM DLV_USRID, appr.MSTEMP_FNM DLV_APPRV,max(DLV_APPRVTM) DLV_APPRVTM,post.MSTEMP_FNM DLV_POST,DLV_POSTTM,isnull(DLV_BCTYPE,'-') DLV_BCTYPE
        ,ISNULL(DLV_NOAJU,'') DLV_NOAJU
        ,ISNULL(DLV_NOPEN,'') DLV_NOPEN, ISNULL(MAX(DLVH_PEMBERITAHU),'') DLVH_PEMBERITAHU, ISNULL(MAX(DLVH_JABATAN),'') DLVH_JABATAN
        ,ISNULL(DLV_DESTOFFICE,'-') DLV_DESTOFFICE,DLV_PURPOSE,DLV_BSGRP,ISNULL(DLV_CUSTDO,'') DLV_CUSTDO
        ,ISNULL(DLV_ZJENIS_TPB_ASAL,'-') DLV_ZJENIS_TPB_ASAL, ISNULL(DLV_ZJENIS_TPB_TUJUAN,'-') DLV_ZJENIS_TPB_TUJUAN,DLV_BCDATE
        ,isnull(DLV_ZSKB,'') DLV_ZSKB,isnull(DLV_ZKODE_CARA_ANGKUT,'-') DLV_ZKODE_CARA_ANGKUT,DLV_ZTANGGAL_SKB,ISNULL(DLV_CONA,'') DLV_CONA,DLV_RPDATE,MAX(SI_WH) SI_WH,ISNULL(MAX(DLV_SPPBDOC),'') DLV_SPPBDOC");
        $this->db->from($this->TABLENAME . " a");
        $this->db->join("MCUS_TBL b", "a.DLV_CUSTCD=b.MCUS_CUSCD");
        $this->db->join("MSTTRANS_TBL c", "a.DLV_TRANS=c.MSTTRANS_ID");
        $this->db->join("MSTEMP_TBL crtd", "a.DLV_CRTD=crtd.MSTEMP_ID");
        $this->db->join("MSTEMP_TBL lupd", "a.DLV_USRID=lupd.MSTEMP_ID", "left");
        $this->db->join("MSTEMP_TBL appr", "a.DLV_APPRV=appr.MSTEMP_ID", "left");
        $this->db->join("MSTEMP_TBL post", "a.DLV_POST=post.MSTEMP_ID", "left");
        $this->db->join("SISCN_TBL", "a.DLV_SER=SISCN_SER", "left");
        $this->db->join("SI_TBL", "SISCN_LINENO=SI_LINENO", "left");
        $this->db->join("DLVH_TBL", "DLV_ID=DLVH_ID", "left");
        $this->db->like("MCUS_CUSNM", $ptxcus)->where("ISNULL(DLV_SER,'') !=", "");
        $this->db->group_by("DLV_ID,DLV_DATE,DLV_DSCRPTN,DLV_CUSTCD,MCUS_CUSNM,DLV_INVNO,DLV_INVDT,DLV_SMTINVNO,DLV_TRANS,DLV_RMRK,MCUS_CURCD,MSTTRANS_TYPE,DLV_CONSIGN,DLV_RPLCMNT,DLV_VAT,DLV_KNBNDLV,DLV_FROMOFFICE
        ,crtd.MSTEMP_FNM,DLV_LUPDT,lupd.MSTEMP_FNM ,appr.MSTEMP_FNM,post.MSTEMP_FNM,DLV_POSTTM,DLV_BCTYPE,DLV_NOAJU,DLV_NOPEN,DLV_DESTOFFICE,DLV_PURPOSE,DLV_BSGRP,DLV_CUSTDO
        ,DLV_ZJENIS_TPB_ASAL,DLV_ZJENIS_TPB_TUJUAN,DLV_BCDATE,DLV_ZSKB,DLV_ZKODE_CARA_ANGKUT,DLV_ZTANGGAL_SKB,DLV_CONA,DLV_RPDATE");
        $this->db->order_by("DLV_DATE desc,DLV_ID desc");
        $query = $this->db->get();
        return $query->result_array();
    }
    public function select_bycustomer_rm($ptxcus)
    {
        $this->db->limit(9999);
        $this->db->select("DLV_ID,DLV_DATE,DLV_DSCRPTN,DLV_CUSTCD,MCUS_CUSNM,DLV_INVNO,DLV_INVDT,DLV_SMTINVNO,DLV_TRANS,MAX(DLV_RMRK) DLV_RMRK,MCUS_CURCD,MSTTRANS_TYPE,DLV_CONSIGN,DLV_RPLCMNT,DLV_VAT,
        DLV_KNBNDLV,ISNULL(DLV_FROMOFFICE,'-') DLV_FROMOFFICE
        ,crtd.MSTEMP_FNM DLV_CRTD,MAX(DLV_CRTDTM) DLV_CRTDTM,DLV_LUPDT,lupd.MSTEMP_FNM DLV_USRID, appr.MSTEMP_FNM DLV_APPRV,max(DLV_APPRVTM) DLV_APPRVTM,post.MSTEMP_FNM DLV_POST,DLV_POSTTM
        ,isnull(DLV_BCTYPE,'-') DLV_BCTYPE
        ,ISNULL(DLV_NOAJU,'') DLV_NOAJU
        ,ISNULL(DLV_NOPEN,'') DLV_NOPEN
        ,ISNULL(DLV_DESTOFFICE,'-') DLV_DESTOFFICE
        ,DLV_PURPOSE
        ,DLV_BSGRP
        ,ISNULL(DLV_CUSTDO,'') DLV_CUSTDO
        ,ISNULL(DLV_ZJENIS_TPB_ASAL,'-') DLV_ZJENIS_TPB_ASAL
        ,ISNULL(DLV_ZJENIS_TPB_TUJUAN,'-') DLV_ZJENIS_TPB_TUJUAN,DLV_BCDATE
        ,ISNULL(DLV_ZSKB,'') DLV_ZSKB,isnull(DLV_ZKODE_CARA_ANGKUT,'-') DLV_ZKODE_CARA_ANGKUT,DLV_ZTANGGAL_SKB,ISNULL(DLV_CONA,'') DLV_CONA,DLV_RPDATE
        ,ISNULL(MAX(DLV_LOCFR),'') DLV_LOCFR,ISNULL(MAX(DLV_RPRDOC),'') DLV_RPRDOC, ISNULL(MAX(DLV_PARENTDOC),'') DLV_PARENTDOC,RPSTOCK_REMARK
        ,ISNULL(MAX(DLV_ZNOMOR_AJU),'') DLV_ZNOMOR_AJU
        ,ISNULL(MAX(DLVH_PEMBERITAHU),'') DLVH_PEMBERITAHU
        ,ISNULL(MAX(DLVH_JABATAN),'') DLVH_JABATAN");
        $this->db->from($this->TABLENAME . " a");
        $this->db->join("(SELECT MSUP_SUPCD MCUS_CUSCD,MAX(MSUP_SUPNM) MCUS_CUSNM, MAX(MSUP_SUPCR) MCUS_CURCD FROM v_supplier_customer_union GROUP BY MSUP_SUPCD) b", "a.DLV_CUSTCD=b.MCUS_CUSCD", "LEFT");
        $this->db->join("MSTTRANS_TBL c", "a.DLV_TRANS=c.MSTTRANS_ID", "LEFT");
        $this->db->join("MSTEMP_TBL crtd", "a.DLV_CRTD=crtd.MSTEMP_ID", "LEFT");
        $this->db->join("MSTEMP_TBL lupd", "a.DLV_USRID=lupd.MSTEMP_ID", "left");
        $this->db->join("MSTEMP_TBL appr", "a.DLV_APPRV=appr.MSTEMP_ID", "left");
        $this->db->join("MSTEMP_TBL post", "a.DLV_POST=post.MSTEMP_ID", "left");
        $this->db->join("DLVH_TBL", "DLV_ID=DLVH_ID", "left");
        $this->db->like("MCUS_CUSNM", $ptxcus)->where("ISNULL(DLV_SER,'')", "");
        $this->db->join("(SELECT RPSTOCK_REMARK FROM ZRPSAL_BCSTOCK GROUP BY RPSTOCK_REMARK) VEXBC", "a.DLV_ID=RPSTOCK_REMARK", "left");
        $this->db->group_by("DLV_ID,DLV_DATE,DLV_DSCRPTN,DLV_CUSTCD,MCUS_CUSNM,DLV_INVNO,DLV_INVDT,DLV_SMTINVNO,DLV_TRANS,MCUS_CURCD,MSTTRANS_TYPE,DLV_CONSIGN,DLV_RPLCMNT,DLV_VAT,DLV_KNBNDLV,DLV_FROMOFFICE
        ,crtd.MSTEMP_FNM,DLV_LUPDT,lupd.MSTEMP_FNM ,appr.MSTEMP_FNM,post.MSTEMP_FNM,DLV_POSTTM,DLV_BCTYPE,DLV_NOAJU,DLV_NOPEN,DLV_DESTOFFICE,DLV_PURPOSE,DLV_BSGRP,DLV_CUSTDO
        ,DLV_ZJENIS_TPB_ASAL,DLV_ZJENIS_TPB_TUJUAN,DLV_BCDATE,DLV_ZSKB,DLV_ZKODE_CARA_ANGKUT,DLV_ZTANGGAL_SKB,DLV_CONA,DLV_RPDATE,RPSTOCK_REMARK");
        $this->db->order_by("DLV_DATE desc,DLV_ID desc");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_det_byid($pid)
    {
        $this->db->select("DLV_SER,SISCN_DOC,SISCN_DOCREFF,UPPER(SER_ITMID) SER_ITMID,
        RTRIM(MITM_ITMD1) MITM_ITMD1,SI_MDL,isnull(SI_OTHRMRK,'') SI_OTHRMRK,ISNULL(MITM_BOXTYPE,'-') MITM_BOXTYPE,
        SISCN_SERQTY,SI_DOCREFFDT,DLV_DATE,SER_DOC, DLV_CRTDTM, CONVERT(DATE, DLV_CRTDTM) CRTDD, ISNULL(SI_WH,'AFWH3') SI_WH,SI_BSGRP,SI_CUSCD,COUNTRM");
        $this->db->from($this->TABLENAME . " a");
        $this->db->join("MCUS_TBL b", "a.DLV_CUSTCD=b.MCUS_CUSCD");
        $this->db->join("SISCN_TBL c", "a.DLV_SER=c.SISCN_SER");
        $this->db->join("SER_TBL d", "a.DLV_SER=d.SER_ID");
        $this->db->join("MITM_TBL e", "d.SER_ITMID=e.MITM_ITMCD");
        $this->db->join("SI_TBL f", "c.SISCN_LINENO=f.SI_LINENO ");
        $this->db->join("(select SERD2_SER,COUNT(DISTINCT SERD2_ITMCD) COUNTRM from SERD2_TBL WHERE SERD2_SER IN (SELECT DLV_SER FROM DLV_TBL WHERE DLV_ID='$pid')
        group by SERD2_SER) v1", "DLV_SER=SERD2_SER", "LEFT");
        $this->db->where("DLV_ID", $pid);
        $this->db->order_by("DLV_ID desc,SER_ITMID, SI_DOCREFFDT asc,  SISCN_SERQTY DESC");
        $query = $this->db->get();
        return $query->result_array();
    }
    public function select_det_rm_byid($pid)
    {
        $qry = "SELECT VDELV.*,ITH_DOC,BCQT,MITM_MODEL FROM
        (select DLV_ITMCD,DLV_ID,sum(DLV_QTY) DLV_QTY,MAX(DLV_LOCFR) DLV_LOCFR from DLV_TBL where DLV_ID=? AND DLV_SER=''
        group by DLV_ITMCD,DLV_ID) VDELV
        LEFT JOIN
        (SELECT ITH_ITMCD,ITH_DOC FROM ITH_TBL WHERE ITH_WH in ('ARWH0PD','PSIEQUIP') AND ITH_FORM='OUT-SHP-RM'
        ) VITH ON VDELV.DLV_ID=VITH.ITH_ITMCD AND VDELV.DLV_ITMCD=VITH.ITH_ITMCD
        LEFT JOIN (
		SELECT DLVRMDOC_ITMID,DLVRMDOC_TXID,SUM(DLVRMDOC_ITMQT) BCQT FROM DLVRMDOC_TBL GROUP BY DLVRMDOC_ITMID,DLVRMDOC_TXID
		) VBC ON DLV_ITMCD=DLVRMDOC_ITMID AND DLVRMDOC_TXID=DLV_ID
        LEFT JOIN MITM_TBL ON DLV_ITMCD=MITM_ITMCD
        ORDER BY DLV_ID,DLV_ITMCD";
        $query = $this->db->query($qry, [$pid]);
        return $query->result_array();
    }
    public function select_det_byid_rm($pid)
    {
        $this->db->select("DLV_ITMCD,RTRIM(MITM_ITMD1) MITM_ITMD1,DLV_QTY,DLV_DATE
        , DLV_CRTDTM, CONVERT(DATE, DLV_CRTDTM) CRTDD,DLV_RMRK,DLV_LINE, DLV_ITMD1, DLV_ITMSPTNO");
        $this->db->from($this->TABLENAME . " a");
        $this->db->join("(SELECT MSUP_SUPCD MCUS_CUSCD,MAX(MSUP_SUPNM) MCUS_CUSNM, MAX(MSUP_SUPCR) MCUS_CURCD FROM v_supplier_customer_union GROUP BY MSUP_SUPCD) b", "a.DLV_CUSTCD=b.MCUS_CUSCD", "LEFT");
        $this->db->join("MITM_TBL e", "DLV_ITMCD=e.MITM_ITMCD");
        $this->db->where("DLV_ID", $pid);
        $this->db->order_by("DLV_ID desc,DLV_ITMCD");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_det_byid_p($pid)
    {
        $this->db->select("DLV_SER,SISCN_DOC,SISCN_DOCREFF,rtrim(SER_ITMID) SER_ITMID,SER_DOC,RTRIM(MITM_ITMD1) MITM_ITMD1,SI_MDL,SISCN_SERQTY,SI_DOCREFFDT,DLV_INVNO, DLV_INVDT,DLV_SMTINVNO,
        DLV_DATE,DLV_INVNO, DLV_SMTINVNO,MCUS_CURCD,DLV_NOPEN,MCUS_ADDR1,MITM_STKUOM,DLV_DSCRPTN, RTRIM(MCUS_CUSNM) MCUS_CUSNM,(MITM_GWG*SISCN_SERQTY) BERATKOTOR,(MITM_NWG*SISCN_SERQTY) BERATBERSIH,
        coalesce(DLV_BCTYPE,'') DLV_BCTYPE,COALESCE(DLV_NOAJU,'') DLV_NOAJU,DLV_DESTOFFICE,DLV_PURPOSE,RTRIM(MCUS_TAXREG) MCUS_TAXREG,MCUS_TPBNO,DLV_TRANS,MSTTRANS_TYPE,
        concat(MSTEMP_FNM, ' ', MSTEMP_LNM) NAMA_TTD,MSTGRP_NM, MITM_NWG, MITM_GWG,MITM_HSCD, MDEL_DELNM, MDEL_ADDRCUSTOMS,ISNULL(MDEL_ATTN,'') ATTN
        ,DLV_CONSIGN");
        $this->db->from($this->TABLENAME . " a");
        $this->db->join("MCUS_TBL b", "a.DLV_CUSTCD=b.MCUS_CUSCD");
        $this->db->join("SISCN_TBL c", "a.DLV_SER=c.SISCN_SER");
        $this->db->join("SER_TBL d", "a.DLV_SER=d.SER_ID");
        $this->db->join("MITM_TBL e", "d.SER_ITMID=e.MITM_ITMCD");
        $this->db->join("SI_TBL f", "c.SISCN_LINENO=f.SI_LINENO");
        $this->db->join("MSTTRANS_TBL g", "a.DLV_TRANS=g.MSTTRANS_ID");
        $this->db->join("MSTEMP_TBL h", "a.DLV_POST=h.MSTEMP_ID", "left");
        $this->db->join("MSTGRP_TBL i", "h.MSTEMP_GRP=i.MSTGRP_ID", "left");
        $this->db->join("MDEL_TBL", "DLV_CONSIGN=MDEL_DELCD", "left");
        $this->db->where("DLV_ID", $pid);
        $this->db->order_by("DLV_ID desc,DLV_DATE desc,SISCN_DOCREFF asc, SI_DOCREFFDT asc, SER_ITMID asc");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_unconfirmed()
    {
        $this->db->from("vdlv_unconfirmed");
        $this->db->order_by("DLV_DATE ASC,DLV_ID ASC");
        $query = $this->db->get();
        return $query->result();
    }

    public function deleteby_filter($pwhere)
    {
        $this->db->where($pwhere);
        $this->db->delete($this->TABLENAME);
        return $this->db->affected_rows();
    }

    public function select_delv_code()
    {
        $this->db->select("MDEL_DELCD");
        $this->db->from("MDEL_TBL");
        $query = $this->db->get();
        return $query->result();
    }
    public function select_delv_code_where($Pwhere)
    {
        $this->db->select("MDEL_DELCD");
        $this->db->where($Pwhere);
        $this->db->from("MDEL_TBL");
        $query = $this->db->get();
        return $query->result();
    }
    public function select_group($pcols, $pcolsGroup, $Pwhere)
    {
        $this->db->select($pcols);
        $this->db->where($Pwhere);
        $this->db->join("SER_TBL", "DLV_SER=SER_ID", "LEFT");
        $this->db->group_by($pcolsGroup);
        $this->db->from($this->TABLENAME);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_delv_code_master()
    {
        $this->db->from("MDEL_TBL");
        $this->db->order_by("MDEL_DELCD");
        $query = $this->db->get();
        return $query->result();
    }

    public function select_lastnodo_patterned($pmonth, $pyear)
    {
        $qry = "select ISNULL(MAX(CONVERT(INT,SUBSTRING(DLV_ID,1,4))),0)  lser
        from DLV_TBL where MONTH(DLV_DATE) =? and YEAR(DLV_DATE)=? and DLV_ISPTTRND='1' ";
        $query = $this->db->query($qry, [$pmonth, $pyear]);
        if ($query->num_rows() > 0) {
            $ret = $query->row();
            return $ret->lser;
        } else {
            return '0';
        }
    }
    public function select_lastno_address()
    {
        $qry = "select ISNULL(MAX(CONVERT(INT,MDEL_TXCD)),1) lser from MDEL_TBL";
        $query = $this->db->query($qry);
        if ($query->num_rows() > 0) {
            $ret = $query->row();
            return $ret->lser;
        } else {
            return '1';
        }
    }
    public function select_lastnodo_patterned_V2($pmonth, $pyear, $flagmonth)
    {
        $qry = "select ISNULL(MAX(CONVERT(INT,SUBSTRING(DLV_ID,1,4))),0) lser
        from DLV_TBL where MONTH(DLV_DATE) =? and YEAR(DLV_DATE)=? and DLV_ISPTTRND='1' AND DLV_ID LIKE '%$flagmonth%'";
        $query = $this->db->query($qry, [$pmonth, $pyear]);
        if ($query->num_rows() > 0) {
            $ret = $query->row();
            return $ret->lser;
        } else {
            return '0';
        }
    }
    public function select_lastnodo_patterned_V3($pmonth, $pyear, $flagmonth)
    {
        $qry = "select ISNULL(MAX(CONVERT(INT,SUBSTRING(DLV_ID,6,4))),0) lser
        from DLV_TBL where MONTH(DLV_BCDATE) =? and YEAR(DLV_BCDATE)=? and DLV_ISPTTRND='2'
        AND SUBSTRING(DLV_ID,5,1)='" . $flagmonth . "'";
        $query = $this->db->query($qry, [$pmonth, $pyear]);
        if ($query->num_rows() > 0) {
            $ret = $query->row();
            return $ret->lser;
        } else {
            return '0';
        }
    }

    public function select_invoice_bydono($pdo)
    {
        $qry = "exec SP_INVOICE_BY_DONO ?";
        $query = $this->db->query($qry, [$pdo]);
        return $query->result_array();
    }

    public function select_packinglist_bydono($pdo)
    {
        $qry = "exec SP_PACKINGLIST_BY_DONO ?";
        $query = $this->db->query($qry, [$pdo]);
        return $query->result_array();
    }

    public function selectAllg_by($pdo)
    {
        $this->db->select("SI_ITMCD,sum(SISCN_SERQTY) TTLQTY,COUNT(*) REMARK,isnull(SI_OTHRMRK ,'') SI_OTHRMRK, MAX(DLV_CUSTDO) DLV_CUSTDO,MAX(rtrim(SO)) SO, MIN(SI_DOC) SI_DOC");
        $this->db->from("SISCN_TBL a");
        $this->db->join("SI_TBL c", "a.SISCN_LINENO=c.SI_LINENO");
        $this->db->join("DLV_TBL d", "a.SISCN_SER=DLV_SER");
        $this->db->join("(select SISO_HLINE,MAX(SISO_CPONO) SO from SISO_TBL
        group by SISO_HLINE) VSO", "SISCN_LINENO=SISO_HLINE", "left");
        $this->db->where('DLV_ID', $pdo);
        $this->db->group_by('SI_ITMCD,SI_OTHRMRK');
        $this->db->order_by('SI_OTHRMRK ASC,SI_ITMCD asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_bom($pdo)
    {
        $qry = "sp_getbomfrom_DO ?";
        $query = $this->db->query($qry, [$pdo]);
        return $query->result_array();
    }
    public function select_bom_subassy($pdo)
    {
        $qry = "SELECT SER_ITMID,SERD2_FGQTY,SERD2_ITMCD,VX.SERD2_SER,SER_DOC,SERD2_QTPER MYPER,DLV_SER FROM
        DLV_TBL INNER JOIN
			SERML_TBL ON DLV_SER=SERML_NEWID
			LEFT JOIN
            (
            SELECT  SERD2_FGQTY,sum(SERD2_QTY)/SERD2_FGQTY SERD2_QTPER,SUM(SERD2_QTY) SERD2_QTYSUM,SERD2_ITMCD,SERD2_SER  FROM SERD2_TBL
			where SERD2_SER in (select SERML_COMID from DLV_TBL INNER JOIN SERML_TBL ON DLV_SER=SERML_NEWID WHERE DLV_ID=?)
            GROUP BY SERD2_QTPER,SERD2_ITMCD,SERD2_FGQTY,SERD2_SER
			) VX
            ON SERML_COMID=SERD2_SER
            LEFT JOIN SER_TBL ON SERD2_SER=SER_ID
            WHERE DLV_ID=?
            ORDER BY SER_ID, SERD2_ITMCD";
        $query = $this->db->query($qry, [$pdo, $pdo]);
        return $query->result_array();
    }

    public function select_delivery_unconfirmed()
    {
        $this->db->from("v_delivery_unconfirmed");
        $this->db->order_by('DLV_BCDATE ASC,DLV_ID asc');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function select_delivery_rm_unconfirmed()
    {
        $this->db->from("v_delivery_rm_unconfirmed");
        $this->db->order_by('DLV_BCDATE ASC,DLV_ID asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    #,DLV_PURPOSE
    public function select_header_bydo($pdo)
    {
        $this->db->select("DLV_BCDATE,isnull(DLV_NOAJU,'-') DLV_NOAJU,RTRIM(DLV_BSGRP) DLV_BSGRP,RTRIM(ISNULL(DLV_CUSTDO,'')) DLV_CUSTDO,MCUS_CURCD,DLV_ZJENIS_TPB_ASAL
        ,DLV_ZJENIS_TPB_TUJUAN,isnull(DLV_BCTYPE,'-') DLV_BCTYPE,isnull(DLV_INVNO,'') DLV_INVNO
        ,isnull(DLV_CONSIGN,'-') DLV_CONSIGN,MDEL_ADDRCUSTOMS,ISNULL(MDEL_ZSKEP,'') MDEL_ZSKEP,MDEL_ZNAMA
        ,isnull(DLV_ZKODE_CARA_ANGKUT,'-') DLV_ZKODE_CARA_ANGKUT,ISNULL(DLV_ZSKB,'') DLV_ZSKB,ISNULL(DLV_FROMOFFICE,'-') DLV_FROMOFFICE
        ,isnull(DLV_ZID_MODUL,'-') DLV_ZID_MODUL,isnull(DLV_TRANS,'-') DLV_TRANS
        ,isnull(MSTTRANS_TYPE,'') MSTTRANS_TYPE,DLV_INVDT,RTRIM(isnull(MDEL_ZTAX,'')) MCUS_TAXREG
        ,isnull(DLV_PURPOSE,'-') DLV_PURPOSE,DLV_DESTOFFICE, ISNULL(MAX(DLVH_PEMBERITAHU),'') DLVH_PEMBERITAHU, ISNULL(MAX(DLVH_JABATAN),'') DLVH_JABATAN
        ,ISNULL(DLV_CONA,'') DLV_CONA, MCONA_DATE,max(DLV_ZNOMOR_AJU) DLV_ZNOMOR_AJU");
        $this->db->from($this->TABLENAME);
        $this->db->join('MCUS_TBL', 'DLV_CUSTCD=MCUS_CUSCD', 'LEFT');
        $this->db->join('MDEL_TBL', 'DLV_CONSIGN=MDEL_DELCD', 'LEFT');
        $this->db->join("MSTTRANS_TBL", "DLV_TRANS=MSTTRANS_ID", "LEFT");
        $this->db->join("DLVH_TBL", "DLV_ID=DLVH_ID", "LEFT");
        $this->db->join("(SELECT MCONA_DOC,MCONA_DATE FROM MCONA_TBL GROUP BY MCONA_DOC,MCONA_DATE) VCONA", "ISNULL(DLV_CONA,'')=MCONA_DOC", "LEFT");
        $this->db->where('DLV_ID', $pdo);
        $this->db->group_by("DLV_BCDATE,DLV_NOAJU,DLV_BSGRP,DLV_CUSTDO,MCUS_CURCD,DLV_ZJENIS_TPB_ASAL
        ,DLV_ZJENIS_TPB_TUJUAN,DLV_BCTYPE,DLV_INVNO,DLV_CONSIGN,MDEL_ADDRCUSTOMS,MDEL_ZSKEP
        ,MDEL_ZNAMA,DLV_ZKODE_CARA_ANGKUT,DLV_ZSKB,DLV_FROMOFFICE,DLV_ZID_MODUL
        ,DLV_TRANS,MSTTRANS_TYPE,DLV_INVDT,MCUS_TAXREG,DLV_PURPOSE,DLV_DESTOFFICE,DLV_CONA,MCONA_DATE,MDEL_ZTAX");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_item_per_price($pdo)
    {
        $qry = "SELECT DLV_ID,DLVQTY SISOQTY,SSO2_SLPRC,RTRIM(SI_ITMCD) SSO2_MDLCD,(DLVQTY*SSO2_SLPRC) CIF,(DLVQTY*isnull(MITM_NWG,0.123)) NWG,(DLVQTY*isnull(MITM_GWG,0.123)) GWG,MITM_HSCD,
        rtrim(MITM_STKUOM) MITM_STKUOM, 0 SISOQTY_X,RTRIM(MITM_ITMD1) MITM_ITMD1,SISO_SOLINE,SI_BSGRP,SI_CUSCD  FROM
        (SELECT DLV_ID,SISCN_LINENO,SUM(DLV_QTY) DLVQTY FROM DLV_TBL
        INNER JOIN SISCN_TBL ON DLV_SER=SISCN_SER
        WHERE DLV_ID=?
        GROUP BY DLV_ID,SISCN_LINENO) V1
        LEFT JOIN
        (SELECT SISO_HLINE,SSO2_SLPRC,SUM(SISO_QTY) SISOQTY,MAX(SISO_SOLINE) SISO_SOLINE  FROM SISO_TBL
        LEFT JOIN XSSO2 ON SISO_CPONO=SSO2_CPONO AND SISO_SOLINE=SSO2_SOLNO
        WHERE SISO_QTY>0
        GROUP BY SISO_HLINE,SSO2_SLPRC,SSO2_MDLCD
        ) V2 ON SISCN_LINENO=SISO_HLINE
        LEFT JOIN SI_TBL ON SISCN_LINENO=SI_LINENO
        LEFT JOIN MITM_TBL ON SI_ITMCD=MITM_ITMCD
        ORDER BY SI_ITMCD";
        $query = $this->db->query($qry, [$pdo]);
        return $query->result_array();
    }
    public function select_item_per_price_V2($pdo)
    {
        $qry = "SELECT DLV_ID
                    ,DLVQTY SISOQTY
                    ,UPPER(RTRIM(SI_ITMCD)) SSO2_MDLCD
                    ,(DLVQTY * isnull(MITM_NWG, 0.123)) NWG
                    ,(DLVQTY * isnull(MITM_GWG, 0.123)) GWG
                    ,RTRIM(ISNULL(MITM_HSCD, '')) MITM_HSCD
                    ,rtrim(MITM_STKUOM) MITM_STKUOM
                    ,0 SISOQTY_X
                    ,RTRIM(MITM_ITMD1) MITM_ITMD1
                    ,RTRIM(MITM_ITMD2) MITM_ITMD2
                    ,SI_BSGRP
                    ,SI_CUSCD
                    ,MITM_BM
					,MITM_PPN
					,MITM_PPH
                    ,isnull(MITM_NWG, 0.123) MITM_NWG
                FROM (
                    SELECT DLV_ID
                        ,SISCN_LINENO
                        ,SUM(DLV_QTY) DLVQTY
                    FROM DLV_TBL
                    INNER JOIN SISCN_TBL ON DLV_SER = SISCN_SER
                    WHERE DLV_ID = ?
                    GROUP BY DLV_ID
                        ,SISCN_LINENO
                    ) V1
                LEFT JOIN SI_TBL ON SISCN_LINENO = SI_LINENO
                LEFT JOIN MITM_TBL ON SI_ITMCD = MITM_ITMCD
                ORDER BY SI_ITMCD
                ";
        $query = $this->db->query($qry, [$pdo]);
        return $query->result_array();
    }

    public function select_item_per_price_one($pdo)
    {
        $qry = "SELECT DLV_ID,SISOQTY,SSO2_SLPRC,RTRIM(SSO2_MDLCD) SSO2_MDLCD,(SISOQTY*SSO2_SLPRC) CIF,(SISOQTY*MITM_NWG) NWG,(SISOQTY*MITM_GWG) GWG,MITM_HSCD,
        rtrim(MITM_STKUOM) MITM_STKUOM, 0 SISOQTY_X,RTRIM(MITM_ITMD1) MITM_ITMD1  FROM
        (SELECT DLV_ID,SISCN_LINENO,SUM(DLV_QTY) DLVQTY FROM DLV_TBL
        INNER JOIN SISCN_TBL ON DLV_SER=SISCN_SER
        WHERE DLV_ID=?
        GROUP BY DLV_ID,SISCN_LINENO) V1
        LEFT JOIN
        (SELECT SISO_HLINE,SSO2_SLPRC,SSO2_MDLCD,SUM(SISO_QTY) SISOQTY  FROM SISO_TBL
        LEFT JOIN XSSO2 ON SISO_CPONO=SSO2_CPONO AND SISO_SOLINE=SSO2_SOLNO
        GROUP BY SISO_HLINE,SSO2_SLPRC,SSO2_MDLCD
        ) V2 ON SISCN_LINENO=SISO_HLINE
        LEFT JOIN MITM_TBL ON SSO2_MDLCD=MITM_ITMCD
        ORDER BY SSO2_MDLCD";
        $query = $this->db->query($qry, [$pdo]);
        return $query->result_array();
    }

    public function select_shipping_for_mega($pdo)
    {
        $qry = "SELECT * FROM
        (select DLVPRC_TXID,SER_ITMID,SUM(DLVPRC_QTY) PLTQTY,DLVPRC_CPO,DLVPRC_CPOLINE  from DLVPRC_TBL
        LEFT JOIN SER_TBL ON DLVPRC_SER=SER_ID
        where DLVPRC_TXID=?
        GROUP BY DLVPRC_TXID,SER_ITMID,DLVPRC_CPO,DLVPRC_CPOLINE)
        VITEM LEFT JOIN (
        SELECT DLV_ID,MAX(DLV_BCDATE) DLV_BCDATE FROM DLV_TBL GROUP BY DLV_ID
        )  VDELV ON DLVPRC_TXID=DLV_ID";
        $query = $this->db->query($qry, [$pdo]);
        return $query->result_array();
    }

    public function select_dlv_ser_rm($pdo)
    {
        $this->db->select("vserd2_cims.*, DLV_QTY B4MINS, 0 PRICEFOR,0 QTYFOR,SER_ITMID,0 PRICEGROUP");
        $this->db->from("DLV_TBL");
        $this->db->join('vserd2_cims', 'DLV_SER=SERD2_SER', 'INNER');
        $this->db->join('SER_TBL', 'DLV_SER=SER_ID', 'LEFT');
        $this->db->where('DLV_ID', $pdo);
        $this->db->order_by('DLV_SER');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectJoinSERLike($Like)
    {
        $this->db->select("SER_DOC,SER_ID");
        $this->db->from("DLV_TBL");
        $this->db->join('SER_TBL', 'DLV_SER=SER_ID', 'LEFT');
        $this->db->like($Like);
        $this->db->order_by('DLV_SER');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_dlv_ser_rm_only($pdo)
    {
        $this->db->select("UPPER(SER_ITMID) SER_ITMID,SERD2_FGQTY,SERD2_SER,LOTNO,RTRIM(SERD2_ITMCD) SERD2_ITMCD,ISNULL(MITMGRP_ITMCD,'') ITMGR,CEILING(SERD2_QTPER*2)/2 SERD2_QTPER");
        $this->db->from("DLV_TBL");
        $this->db->join('(SELECT SERD2_SER,SERD2_ITMCD,SERD2_FGQTY,SUM(SERD2_QTY)/SERD2_FGQTY SERD2_QTPER, MAX(SERD2_LOTNO) LOTNO FROM SERD2_TBL GROUP BY SERD2_SER,SERD2_ITMCD,SERD2_FGQTY) V1', 'DLV_SER=SERD2_SER', 'INNER');
        $this->db->join('SER_TBL', 'DLV_SER=SER_ID', 'LEFT');
        $this->db->join('VFG_AS_BOM', 'SERD2_ITMCD=PWOP_BOMPN', 'LEFT');
        $this->db->join('MITMGRP_TBL', 'SERD2_ITMCD=MITMGRP_ITMCD_GRD', 'LEFT');
        $this->db->where('DLV_ID', $pdo)->where("PWOP_BOMPN", null);
        $this->db->order_by('DLV_SER');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function selectCalculationSerRmOnlyBySerID($arraySerID)
    {
        $this->db->select("RTRIM(SERD2_ITMCD) PART_CODE,ISNULL(MITMGRP_ITMCD,'') ITMGR,SUM(RMQT) QTY");
        $this->db->from("SER_TBL");
        $this->db->join('(SELECT SERD2_SER,SERD2_ITMCD,SUM(SERD2_QTY) RMQT FROM SERD2_TBL GROUP BY SERD2_SER,SERD2_ITMCD,SERD2_FGQTY) V1', 'SER_ID=SERD2_SER', 'INNER');        
        $this->db->join('VFG_AS_BOM', 'SERD2_ITMCD=PWOP_BOMPN', 'LEFT');
        $this->db->join('MITMGRP_TBL', 'SERD2_ITMCD=MITMGRP_ITMCD_GRD', 'LEFT');
        $this->db->where_in('SER_ID', $arraySerID)->where("PWOP_BOMPN", null);
        $this->db->group_by("SERD2_ITMCD,MITMGRP_ITMCD");
        $query = $this->db->get();
        return $query->result_array();
    }
    public function select_dlv_ser_sub_only($pdo)
    {
        $this->db->select("UPPER(SER_ITMID) SER_ITMID,SERD2_FGQTY,DLV_SER SERD2_SER,LOTNO,RTRIM(SERD2_ITMCD) SERD2_ITMCD,ISNULL(MITMGRP_ITMCD,'') ITMGR,SERD2_QTPER");
        $this->db->from("DLV_TBL");
        $this->db->join('SERML_TBL', 'DLV_SER=SERML_NEWID', 'LEFT');
        $this->db->join('(SELECT SERD2_SER,SERD2_ITMCD,SERD2_FGQTY,SUM(SERD2_QTY)/SERD2_FGQTY SERD2_QTPER, MAX(SERD2_LOTNO) LOTNO FROM SERD2_TBL GROUP BY SERD2_SER,SERD2_ITMCD,SERD2_FGQTY) V1', 'SERML_COMID=SERD2_SER');
        $this->db->join('SER_TBL', 'DLV_SER=SER_ID', 'LEFT');
        $this->db->join('MITMGRP_TBL', 'SERD2_ITMCD=MITMGRP_ITMCD_GRD', 'LEFT');
        $this->db->where('DLV_ID', $pdo);
        $this->db->order_by('DLV_SER');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function selectCalculationSubOnlyBySerID($arraySerID)
    {
        $this->db->select("RTRIM(SERD2_ITMCD) PART_CODE,ISNULL(MITMGRP_ITMCD,'') ITMGR,sum(RMQT) QTY");
        $this->db->from("SER_TBL");
        $this->db->join('SERML_TBL', 'SER_ID=SERML_NEWID', 'LEFT');
        $this->db->join('(SELECT SERD2_SER,SERD2_ITMCD,SUM(SERD2_QTY) RMQT FROM SERD2_TBL GROUP BY SERD2_SER,SERD2_ITMCD) V1', 'SERML_COMID=SERD2_SER');
        $this->db->join('MITMGRP_TBL', 'SERD2_ITMCD=MITMGRP_ITMCD_GRD', 'LEFT');
        $this->db->where_in('SER_ID', $arraySerID);
        $this->db->group_by("SERD2_ITMCD,MITMGRP_ITMCD");
        $this->db->order_by('SERD2_ITMCD');        
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_job($pdo)
    {
        $this->db->select("SER_DOC,SER_ITMID,rtrim(MITM_ITMD1) MITM_ITMD1");
        $this->db->from("DLV_TBL");
        $this->db->join("SER_TBL", "DLV_SER=SER_ID");
        $this->db->join("MITM_TBL", "SER_ITMID=MITM_ITMCD");
        $this->db->where('DLV_ID', $pdo);
        $this->db->group_by("SER_DOC,SER_ITMID,MITM_ITMD1");
        $this->db->order_by("SER_DOC");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_searchv($pdo)
    {
        $this->db->select("DLV_ID,MBSG_DESC, MCUS_CUSNM");
        $this->db->from("DLV_TBL");
        $this->db->join("XMBSG_TBL", "DLV_BSGRP=MBSG_BSGRP");
        $this->db->join("MCUS_TBL", "DLV_CUSTCD=MCUS_CUSCD");
        $this->db->like('DLV_ID', $pdo);
        $this->db->group_by("DLV_ID,MBSG_DESC, MCUS_CUSNM");
        $this->db->order_by("DLV_ID");
        $query = $this->db->get();
        return $query->result_array();
    }
    public function select_searchv_bydate($pdo, $pdate)
    {
        $this->db->select("DLV_ID,MBSG_DESC, MCUS_CUSNM");
        $this->db->from("DLV_TBL");
        $this->db->join("XMBSG_TBL", "DLV_BSGRP=MBSG_BSGRP");
        $this->db->join("MCUS_TBL", "DLV_CUSTCD=MCUS_CUSCD");
        $this->db->like('DLV_ID', $pdo)->where("DLV_BCDATE", $pdate);
        $this->db->group_by("DLV_ID,MBSG_DESC, MCUS_CUSNM");
        $this->db->order_by("DLV_ID");
        $query = $this->db->get();
        return $query->result_array();
    }
    public function select_searchv_bymonth($pdo, $pmonth, $pyear)
    {
        $this->db->select("DLV_ID,MBSG_DESC, MCUS_CUSNM");
        $this->db->from("DLV_TBL");
        $this->db->join("XMBSG_TBL", "DLV_BSGRP=MBSG_BSGRP");
        $this->db->join("MCUS_TBL", "DLV_CUSTCD=MCUS_CUSCD");
        $this->db->like('DLV_ID', $pdo)->where("month(DLV_BCDATE)", $pmonth)->where("year(DLV_BCDATE)", $pyear);
        $this->db->group_by("DLV_ID,MBSG_DESC, MCUS_CUSNM");
        $this->db->order_by("DLV_ID");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_dlv_ser_rm_byreff($pser)
    {
        $this->db->select("vserd2_cims.*, SERD2_FGQTY B4MINS, 0 PRICEFOR,0 QTYFOR,SER_ITMID,0 PRICEGROUP");
        $this->db->from('vserd2_cims');
        $this->db->join('SER_TBL', 'SERD2_SER=SER_ID', 'LEFT');
        $this->db->where_in('SERD2_SER', $pser);
        $this->db->order_by('SERD2_SER');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function select_dlv_ser_rm_byreff_forpost($pser)
    {
        // $this->db->select("SERD2_SER,LOTNO,SERD2_ITMCD,ISNULL(MITMGRP_ITMCD,'') ITMGR,SERD2_QTPER,SERD2_FGQTY,SERD2_FGQTY B4MINS, 0 PRICEFOR,0 QTYFOR,ISNULL(Y.SER_ITMID,X.SER_ITMID)  SER_ITMID,0 PRICEGROUP");
        $this->db->select("SERD2_SER,LOTNO,SERD2_ITMCD,ISNULL(MITMGRP_ITMCD,'') ITMGR,SERD2_QTPER,SERD2_FGQTY,SERD2_FGQTY B4MINS, 0 PRICEFOR,0 QTYFOR,,CASE WHEN Z.SER_ITMID IS NOT NULL THEN Z.SER_ITMID
        ELSE ISNULL(Y.SER_ITMID,X.SER_ITMID)
        END
        SER_ITMID,0 PRICEGROUP");
        $this->db->from('(SELECT SERD2_SER,SERD2_ITMCD,SERD2_FGQTY,SUM(SERD2_QTY)/SERD2_FGQTY SERD2_QTPER, MAX(SERD2_LOTNO) LOTNO FROM SERD2_TBL GROUP BY SERD2_SER,SERD2_ITMCD,SERD2_FGQTY) V1');
        $this->db->join('SER_TBL X', 'SERD2_SER=SER_ID', 'LEFT');
        $this->db->join('MITMGRP_TBL', 'SERD2_ITMCD=MITMGRP_ITMCD_GRD', 'LEFT');
        $this->db->join('SERC_TBL', 'SERD2_SER=SERC_COMID', 'LEFT');
        $this->db->join('SER_TBL Y', 'SERC_NEWID=Y.SER_ID', 'LEFT');
        $this->db->join('SER_TBL Z', 'SERC_NEWID=Z.SER_REFNO', 'LEFT');
        $this->db->where_in('SERD2_SER', $pser);
        $this->db->order_by('SERD2_SER');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_dlv_ser_rm_null($pdo)
    {
        $this->db->select("vserd2_cims.*, DLV_QTY B4MINS, 0 PRICEFOR,0 QTYFOR,SER_ITMID,0 PRICEGROUP,DLV_SER");
        $this->db->from("DLV_TBL");
        $this->db->join('vserd2_cims', 'DLV_SER=SERD2_SER', 'LEFT');
        $this->db->join('SER_TBL', 'DLV_SER=SER_ID', 'LEFT');
        $this->db->where('DLV_ID', $pdo)->where('SERD2_SER IS NULL', null);
        $this->db->order_by('DLV_SER');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_dlv_ser_rm_null_v1($pdo)
    {
        $this->db->select("DLV_SER,SER_REFNO");
        $this->db->from("DLV_TBL");
        $this->db->join('SERD2_TBL', 'DLV_SER=SERD2_SER', 'LEFT');
        $this->db->join('SER_TBL', 'DLV_SER=SER_ID', 'LEFT');
        $this->db->where('DLV_ID', $pdo)->where('SERD2_SER IS NULL', null);
        $this->db->order_by('DLV_SER');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_for_delete($pwhere)
    {
        $this->db->select("DLV_SER");
        $this->db->from($this->TABLENAME);
        $this->db->join('SER_TBL', 'DLV_SER=SER_ID', 'LEFT');
        $this->db->where($pwhere)->where('DLV_RPDATE IS NULL', null, false);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_rm_null($pdo)
    {
        $qry = "SELECT DLV_SER,DLV_QTY,SER_ITMID,SER_DOC,SER_REFNO FROM DLV_TBL left join SERD2_TBL on DLV_SER=SERD2_SER
        LEFT JOIN SER_TBL ON DLV_SER=SER_ID
        where SERD2_SER is null  AND DLV_ID=?
        ORDER BY  SER_ITMID, SER_DOC";
        $query = $this->db->query($qry, [$pdo]);
        return $query->result_array();
    }

    public function select_consign_history($pwhere)
    {
        $this->db->from($this->TABLENAME);
        $this->db->select("DLV_CONSIGN");
        $this->db->where($pwhere)->where('DLV_CONSIGN !=', '-');
        $this->db->group_by("DLV_CONSIGN");
        $this->db->order_by("DLV_CONSIGN");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_pkg($pdo)
    {
        $this->db->from("DLV_PKG_TBL");
        $this->db->where('DLV_PKG_DOC', $pdo);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_lastline($pdoc)
    {
        $qry = "SELECT MAX(DLV_LINE) lser FROM $this->TABLENAME WHERE DLV_ID=?";
        $query = $this->db->query($qry, [$pdoc]);
        if ($query->num_rows() > 0) {
            $ret = $query->row();
            return $ret->lser;
        } else {
            return 0;
        }
    }
    public function select_lastline_pkg($pdoc)
    {
        $qry = "SELECT MAX(DLV_PKG_LINE) lser FROM DLV_PKG_TBL WHERE DLV_PKG_DOC=?";
        $query = $this->db->query($qry, [$pdoc]);
        if ($query->num_rows() > 0) {
            $ret = $query->row();
            return $ret->lser;
        } else {
            return 0;
        }
    }

    public function select_header_rm($pdoc)
    {
        $this->db->select("MAX(DLV_ZJENIS_TPB_ASAL) DLV_ZJENIS_TPB_ASAL
        ,max(DLV_ZJENIS_TPB_TUJUAN) DLV_ZJENIS_TPB_TUJUAN
        ,MAX(DLV_FROMOFFICE) DLV_FROMOFFICE
        ,max(DLV_NOAJU) DLV_NOAJU
        ,max(DLV_NOPEN) DLV_NOPEN
        ,max(DLV_ZKODE_CARA_ANGKUT) DLV_ZKODE_CARA_ANGKUT
        ,MAX(DLV_ZSKB) DLV_ZSKB");
        $this->db->from($this->TABLENAME);
        $this->db->where('DLV_ID', $pdoc);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function deleteby_filter_pkg($pwhere)
    {
        $this->db->where($pwhere);
        $this->db->delete('DLV_PKG_TBL');
        return $this->db->affected_rows();
    }

    public function selectRMForPostingCeisa($pdoc)
    {
        $this->db->select("DLV_ITMCD, DLV_QTY, RTRIM(MITM_ITMD1) MITM_ITMD1, MITM_HSCD
        , RTRIM(MITM_STKUOM) MITM_STKUOM, (DLV_QTY*isnull(MITM_NWG,0.123)) NWG, DLV_BCDATE
        , DLV_CONA, DLV_PURPOSE, DLV_BCTYPE, DLV_ZNOMOR_AJU, MDEL_ADDRCUSTOMS, MCUS_TAXREG
        , MDEL_ZNAMA, MSTTRANS_TYPE, DLV_TRANS, DLV_ZJENIS_TPB_ASAL,DLV_INVNO,DLV_INVDT");
        $this->db->from($this->TABLENAME);
        $this->db->join("MITM_TBL", "DLV_ITMCD=MITM_ITMCD");
        $this->db->join('MCUS_TBL', 'DLV_CUSTCD=MCUS_CUSCD', 'LEFT');
        $this->db->join("MSTTRANS_TBL", "DLV_TRANS=MSTTRANS_ID", "LEFT");
        $this->db->join('MDEL_TBL', 'DLV_CONSIGN=MDEL_DELCD', 'LEFT');
        $this->db->where('DLV_ID', $pdoc);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_reqPlot($pdoc)
    {
        $qry = "select DLV_BSGRP,DLV_CONSIGN,DLV_CUSTCD,UPPER(SER_ITMID) SER_ITMID,sum(DLV_QTY) DLVQTY, 0 PLOTQTY from DLV_TBL
        left join SER_TBL on DLV_SER=SER_ID
        where DLV_ID=?
        group by SER_ITMID,DLV_BSGRP,DLV_CONSIGN,DLV_CUSTCD";
        $query = $this->db->query($qry, [$pdoc]);
        return $query->result_array();
    }
    public function select_reqPlot_SIBase($pdoc)
    {
        $qry = "select DLV_BSGRP,DLV_CONSIGN,DLV_CUSTCD,UPPER(SER_ITMID) SER_ITMID,sum(DLV_QTY) DLVQTY, 0 PLOTQTY,SISCN_LINENO from DLV_TBL
        left join SER_TBL on DLV_SER=SER_ID
        LEFT JOIN SISCN_TBL ON DLV_SER=SISCN_SER
        where DLV_ID=?
        group by SER_ITMID,DLV_BSGRP,DLV_CONSIGN,DLV_CUSTCD,SISCN_LINENO";
        $query = $this->db->query($qry, [$pdoc]);
        return $query->result_array();
    }
    public function select_reqPlot_SIBase_withItem($pdoc, $item)
    {
        $qry = "select DLV_BSGRP,DLV_CONSIGN,DLV_CUSTCD,SER_ITMID,sum(DLV_QTY) DLVQTY, 0 PLOTQTY,SISCN_LINENO from DLV_TBL
        left join SER_TBL on DLV_SER=SER_ID
        LEFT JOIN SISCN_TBL ON DLV_SER=SISCN_SER
        where DLV_ID=? AND SER_ITMID=?
        group by SER_ITMID,DLV_BSGRP,DLV_CONSIGN,DLV_CUSTCD,SISCN_LINENO";
        $query = $this->db->query($qry, [$pdoc, $item]);
        return $query->result_array();
    }

    public function select_DO41($pdoc)
    {
        $qry = "SELECT VDELV.*,TTLALLDLV,MTTL,RTRIM(MITM_ITMD1) MITM_ITMD1,RCV_BCNO,RTRIM(MITM_STKUOM) MITM_STKUOM  FROM
            (select DLV_CONA,SER_ITMID,SUM(DLV_QTY) TTLDLV from DLV_TBL
            LEFT JOIN SER_TBL ON DLV_SER=SER_ID
            where DLV_ID=?
            GROUP BY DLV_CONA,SER_ITMID) VDELV
            LEFT JOIN
            (SELECT DLV_CONA ,SER_ITMID,SUM(SER_QTY) TTLALLDLV FROM DLV_TBL
            LEFT JOIN SER_TBL ON DLV_SER=SER_ID
            WHERE ISNULL(DLV_CONA,'')  !=''
            GROUP BY DLV_CONA,SER_ITMID) VCONA
            ON VDELV.DLV_CONA=VCONA.DLV_CONA AND VDELV.SER_ITMID=VCONA.SER_ITMID
            LEFT JOIN
                (select MCONA_DOC,MCONA_ITMCD,SUM(MCONA_QTY) MTTL from MCONA_TBL WHERE MCONA_ITMTYPE='1'
                GROUP BY MCONA_DOC,MCONA_ITMCD) VMASTER
                ON VDELV.DLV_CONA=VMASTER.MCONA_DOC AND VDELV.SER_ITMID=VMASTER.MCONA_ITMCD
            LEFT JOIN (
                select min(RCV_BCNO) RCV_BCNO,RCV_CONA from RCV_TBL where isnull(RCV_CONA,'')!='' group by RCV_CONA
            ) VEXBC ON MCONA_DOC=RCV_CONA
            LEFT JOIN MITM_TBL ON VDELV.SER_ITMID=MITM_ITMCD
            ORDER BY SER_ITMID";
        $query = $this->db->query($qry, [$pdoc]);
        return $query->result_array();
    }

    public function select_plotted_so_mega($pdoc)
    {
        $qry = "SELECT MAX(DLV_BSGRP) DLV_BSGRP,MAX(DLV_CUSTCD) DLV_CUSTCD ,SISO_CPONO,SISO_SOLINE,SISCN_LINENO, SER_ITMID,SUM(DLV_QTY) DLVTQT,MAX(SISO_QTY) SISTQT,SSO2_SLPRC from DLV_TBL
        left join SISCN_TBL on DLV_SER=SISCN_SER
        LEFT JOIN SER_TBL ON DLV_SER=SER_ID
        left join (SELECT SISO_HLINE,SISO_CPONO,SISO_SOLINE,SSO2_SLPRC,SUM(SISO_QTY) SISO_QTY FROM SISO_TBL
                    LEFT JOIN XSSO2 ON SISO_CPONO=SSO2_CPONO AND SISO_SOLINE=SSO2_SOLNO
                    GROUP BY SISO_HLINE,SISO_CPONO,SISO_SOLINE,SSO2_SLPRC
                    ) VPRICE ON SISCN_LINENO=SISO_HLINE
        where DLV_ID=?
        GROUP BY SISO_CPONO,SISO_SOLINE,SER_ITMID,SSO2_SLPRC,SISCN_LINENO
        ORDER BY SER_ITMID";
        $query = $this->db->query($qry, [$pdoc]);
        return $query->result_array();
    }
    public function select_plotted_so_mega_V2($pdoc)
    {
        $qry = "SELECT MAX(DLV_BSGRP) DLV_BSGRP,MAX(DLV_CUSTCD) DLV_CUSTCD ,SISO_CPONO,SISO_SOLINE,SISCN_LINENO, SER_ITMID,SUM(DLV_QTY) DLVTQT,MAX(SISO_QTY) SISTQT,SSO2_SLPRC from DLV_TBL
        left join SISCN_TBL on DLV_SER=SISCN_SER
        LEFT JOIN SER_TBL ON DLV_SER=SER_ID
        left join (SELECT SISO_HLINE,SISO_CPONO,SISO_SOLINE,SSO2_SLPRC,SUM(SISO_QTY) SISO_QTY FROM SISO_TBL
                    LEFT JOIN XSSO2 ON SISO_CPONO=SSO2_CPONO AND SISO_SOLINE=SSO2_SOLNO
                    GROUP BY SISO_HLINE,SISO_CPONO,SISO_SOLINE,SSO2_SLPRC
                    ) VPRICE ON SISCN_LINENO=SISO_HLINE
        where DLV_ID=?
        GROUP BY SISO_CPONO,SISO_SOLINE,SER_ITMID,SSO2_SLPRC,SISCN_LINENO
        ORDER BY SER_ITMID";
        $query = $this->db->query($qry, [$pdoc]);
        return $query->result_array();
    }

    public function select_pertxid_rmOnly($ptxid)
    {
        $qry = "select RTRIM(SERD2_ITMCD) SERD2_ITMCD,ROUND(SUM(SERD2_QTY),0) DLVQT,ISNULL(MITMGRP_ITMCD,'') ITMGR from DLV_TBL
        INNER JOIN SERD2_TBL ON DLV_SER=SERD2_SER
        LEFT JOIN VFG_AS_BOM ON SERD2_ITMCD=PWOP_BOMPN
        LEFT JOIN MITMGRP_TBL ON SERD2_ITMCD=MITMGRP_ITMCD_GRD
        WHERE DLV_ID=? AND PWOP_BOMPN IS NULL
        GROUP BY SERD2_ITMCD,MITMGRP_ITMCD";
        $query = $this->db->query($qry, [$ptxid]);
        return $query->result_array();
    }

    public function select_pertxid_subOnly($ptxid)
    {
        $qry = "select SERD2_ITMCD,SUM(SERD2_QTY) DLVQT,ISNULL(MITMGRP_ITMCD,'') ITMGR from DLV_TBL
        LEFT JOIN SERML_TBL ON DLV_SER=SERML_NEWID
        INNER JOIN SERD2_TBL ON SERML_COMID=SERD2_SER
        LEFT JOIN MITMGRP_TBL ON SERD2_ITMCD=MITMGRP_ITMCD_GRD
        WHERE DLV_ID=?
        GROUP BY SERD2_ITMCD,MITMGRP_ITMCD";
        $query = $this->db->query($qry, [$ptxid]);
        return $query->result_array();
    }

    public function select_pertxid_byser($pser)
    {
        $this->db->select("SERD2_ITMCD,sum(DLVQT) DLVQT,ISNULL(MITMGRP_ITMCD,'') ITMGR");
        $this->db->from('(SELECT SERD2_ITMCD,SERD2_SER,SUM(SERD2_QTY) DLVQT FROM SERD2_TBL GROUP BY SERD2_ITMCD,SERD2_SER) V1');
        $this->db->join('MITMGRP_TBL', 'SERD2_ITMCD=MITMGRP_ITMCD_GRD', 'LEFT');
        $this->db->where_in('SERD2_SER', $pser);
        $this->db->group_by("SERD2_ITMCD,MITMGRP_ITMCD");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_serah_terima_asp($psi)
    {
        $qry = "SELECT V1.*,SCAN_DATE,MBSG_DESC,MCUS_CUSNM,SI_HRMRK,RTRIM(MITM_ITMD1) MITM_ITMD1,TTLBOX*MITM_NWG MITM_NWG, TTLBOX* MITM_BOXWEIGHT MITM_GWG,1 TTLBARIS,RTRIM(MITM_ITMD2) MITM_ITMD2  FROM
        (SELECT SI_ITMCD, 1 SISCN_SERQTY, SUM(SISCN_SERQTY) TTLBOX, MIN(CONVERT(DATE,SISCN_LUPDT)) SCAN_DATE,MAX(SI_BSGRP) BSGRP, MAX(SI_CUSCD) MCUS, MAX(SI_HRMRK) SI_HRMRK,SUM(SISCN_SERQTY) TTLQTY, max(SER_RMRK) SER_RMRK  FROM SISCN_TBL
        LEFT JOIN SI_TBL ON SISCN_LINENO=SI_LINENO
		LEFT JOIN SER_TBL ON SISCN_SER=SER_ID
		INNER JOIN DLV_TBL ON SISCN_SER=DLV_SER
        WHERE DLV_ID=?
        GROUP BY SI_ITMCD) V1
        LEFT JOIN XMBSG_TBL ON BSGRP=MBSG_BSGRP
        LEFT JOIN MCUS_TBL ON MCUS=MCUS_CUSCD
        LEFT JOIN MITM_TBL ON V1.SI_ITMCD=MITM_ITMCD
        ORDER BY V1.SI_ITMCD DESC";
        $query = $this->db->query($qry, [$psi]);
        return $query->result_array();
    }

    public function select_wh_top1_bydoc($pdoc)
    {
        $qry = "SELECT TOP 1 ITH_WH,ITH_ITMCD FROM ITH_TBL WHERE ITH_SER=(select top 1 DLV_SER from DLV_TBL WHERE DLV_ID=?) AND ITH_WH='AFWH3'";
        $query = $this->db->query($qry, [$pdoc]);
        return $query->result_array();
    }

    public function select_rm_rtn_bydoc($pdoc)
    {
        $qry = "SELECT SERRC_BOMPN,ISNULL(MITMGRP_ITMCD,'') ITMGR,SERRC_NASSYCD,SUM(SERRC_BOMPNQTY) BOMPNQT
        FROM SERRC_TBL
        LEFT JOIN MITMGRP_TBL ON SERRC_BOMPN=MITMGRP_ITMCD_GRD
        WHERE SERRC_SERX IN (
            SELECT DLV_SER FROM DLV_TBL
            LEFT JOIN SER_TBL ON DLV_SER=SER_ID
            WHERE DLV_ID=?
        ) AND SERRC_BOMPN !=''
        GROUP BY SERRC_BOMPN,SERRC_NASSYCD,MITMGRP_ITMCD";
        $query = $this->db->query($qry, [$pdoc]);
        return $query->result_array();
    }

    public function select_for_do_rm_rtn($pdoc)
    {
        $qry = "select DLV_ITMCD,RTRIM(MITM_ITMD1) MITM_ITMD1,DLV_QTY,MDEL_ADDRCUSTOMS,MDEL_ZNAMA,DLV_DSCRPTN
        ,DLV_BCTYPE,ISNULL(DLV_NOPEN,'') DLV_NOPEN,DLV_INVNO,DLV_DATE,rtrim(MITM_STKUOM) MITM_STKUOM,DLV_ITMD1,DLV_ITMSPTNO,MITM_SPTNO from DLV_TBL
                left join MITM_TBL on DLV_ITMCD=MITM_ITMCD
                LEFT JOIN MDEL_TBL ON DLV_CONSIGN=MDEL_DELCD
                where DLV_ID=?";
        $query = $this->db->query($qry, [$pdoc]);
        return $query->result_array();
    }
    public function select_for_do_rm_rtn_v1($pdoc)
    {
        $qry = "wms_sp_DO_RM_rtn ?";
        $query = $this->db->query($qry, [$pdoc]);
        return $query->result_array();
    }

    public function select_for_pkg_rm_rtn($pdoc)
    {
        $qry = "wms_sp_packaging_rm_rtn ?";
        $query = $this->db->query($qry, [$pdoc]);
        return $query->result_array();
    }

    public function selectBookeEXCBByTXID($pdoc)
    {
        $qry = "wms_sp_select_booked_exbc_by_txid ?";
        $query = $this->db->query($qry, [$pdoc]);
        return $query->result();
    }

    public function select_for_rm_h($pdoc)
    {
        $qry = "SELECT DLV_ID,ISNULL(DLV_PURPOSE,'-') DLV_PURPOSE,ISNULL(DLV_CONSIGN,'-') DLV_CONSIGN
        ,ISNULL(DLV_INVNO,'') DLV_INVNO,DLV_BCDATE,ISNULL(DLV_BCTYPE,'-') DLV_BCTYPE,DLV_NOAJU,DLV_BSGRP,DLV_CUSTDO
        ,MCUS_CURCD,MAX(DLV_DSCRPTN) DLV_DSCRPTN,MAX(DLV_DESTOFFICE) DLV_DESTOFFICE
        ,MAX(DLV_ZJENIS_TPB_ASAL) DLV_ZJENIS_TPB_ASAL,MAX(DLV_ZJENIS_TPB_TUJUAN) DLV_ZJENIS_TPB_TUJUAN
        ,RTRIM(isnull(MAX(MDEL_ZTAX),'')) MCUS_TAXREG,MAX(MDEL_ZNAMA) MDEL_ZNAMA, MAX(MDEL_ADDRCUSTOMS) MDEL_ADDRCUSTOMS
        ,MAX(MDEL_ZSKEP) MDEL_ZSKEP,MAX(MSTTRANS_TYPE) MSTTRANS_TYPE, MAX(DLV_TRANS) DLV_TRANS
        ,MAX(DLV_INVDT) DLV_INVDT,MAX(DLV_LOCFR) DLV_LOCFR,max(DLV_ZKODE_CARA_ANGKUT) DLV_ZKODE_CARA_ANGKUT,ISNULL(MAX(DLV_CONA),'') DLV_CONA FROM DLV_TBL
        LEFT JOIN (SELECT MSUP_SUPCD MCUS_CUSCD,MAX(MSUP_SUPNM) MCUS_CUSNM, MAX(MSUP_SUPCR) MCUS_CURCD FROM v_supplier_customer_union GROUP BY MSUP_SUPCD) b ON DLV_CUSTCD=MCUS_CUSCD
        LEFT JOIN MDEL_TBL ON DLV_CONSIGN=MDEL_DELCD
        LEFT JOIN MSTTRANS_TBL ON DLV_TRANS=MSTTRANS_ID
        WHERE DLV_ID=?
        GROUP BY DLV_ID,DLV_PURPOSE,DLV_CONSIGN,DLV_INVNO,DLV_BCDATE,DLV_BCTYPE,DLV_NOAJU,DLV_BSGRP,DLV_CUSTDO,MCUS_CURCD";
        $query = $this->db->query($qry, [$pdoc]);
        return $query->result_array();
    }

    public function select_where($Pwhere)
    {
        $this->db->from($this->TABLENAME);
        $this->db->where($Pwhere);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_itemtotal($pdoc)
    {
        $qry = "SELECT DLV_ITMCD,SUM(DLV_QTY) DLV_QTY,DLV_RPRDOC FROM DLV_TBL WHERE DLV_ID=?
        GROUP BY DLV_ITMCD,DLV_RPRDOC";
        $query = $this->db->query($qry, [$pdoc]);
        return $query->result_array();
    }

    public function select_out_pabean($pwhere)
    {
        $this->db->from($this->VIEWUNION);
        $this->db->where($pwhere);
        $this->db->order_by("DLV_BCDATE,DLV_ID");
        $query = $this->db->get();
        return $query->result_array();
    }
    public function select_out_pabean_like($pwhere, $plike)
    {
        $this->db->from($this->VIEWUNION);
        $this->db->where($pwhere)->like($plike);
        $this->db->order_by("DLV_BCDATE,DLV_ID");
        $query = $this->db->get();
        return $query->result_array();
    }
    public function select_out_pabean_notlike($pwhere, $plike)
    {
        $this->db->from($this->VIEWUNION);
        $this->db->where($pwhere)->not_like($plike);
        $this->db->order_by("DLV_BCDATE,DLV_ID");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_top1_with_columns_where($pColumns, $pWhere)
    {
        $this->db->limit(1);
        $this->db->select($pColumns);
        $this->db->from($this->TABLENAME);
        $this->db->where($pWhere);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectPostedDocument($pColumns, $pWhere)
    {
        $this->db->limit(1);
        $this->db->select($pColumns);
        $this->db->join("MCUS_TBL", "DLV_CUSTCD=MCUS_CUSCD", "LEFT");
        $this->db->join("MSUP_TBL", "DLV_CUSTCD=MSUP_SUPCD", "LEFT");
        $this->db->from($this->TABLENAME);
        $this->db->where($pWhere)->where('DLV_POST IS NOT NULL', null, false);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectDocument($pColumns, $pWhere)
    {
        $this->db->limit(1);
        $this->db->select($pColumns);
        $this->db->join("MCUS_TBL", "DLV_CUSTCD=MCUS_CUSCD", "LEFT");
        $this->db->join("MSUP_TBL", "DLV_CUSTCD=MSUP_SUPCD", "LEFT");
        $this->db->from($this->TABLENAME);
        $this->db->where($pWhere);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_aftersales($pdoc, $pfg)
    {
        $qry = "select vdet.*,DELQT, RMQT/DELQT*3 STKQTY,MITMGRP_ITMCD from
        (select DLV_ID,SER_ITMID,SERD2_ITMCD ITH_ITMCD,sum(SERD2_QTY) RMQT FROM DLV_TBL LEFT JOIN SER_TBL ON DLV_SER=SER_ID
        left join SERD2_TBL on DLV_SER=SERD2_SER
        WHERE DLV_ID IN (?) AND SER_ITMID=?
        GROUP BY DLV_ID,SER_ITMID,SERD2_ITMCD) vdet
        left join (
        SELECT DLV_ID,SER_ITMID,SUM(DLV_QTY) DELQT FROM DLV_TBL LEFT JOIN SER_TBL ON DLV_SER=SER_ID
        WHERE DLV_ID IN (?) AND SER_ITMID=?
        GROUP BY DLV_ID,SER_ITMID
        ) vhead
        on vdet.DLV_ID=vhead.DLV_ID and vdet.SER_ITMID=vhead.SER_ITMID
        left join MITMGRP_TBL on ITH_ITMCD=MITMGRP_ITMCD_GRD";
        $query = $this->db->query($qry, [$pdoc, $pfg, $pdoc, $pfg]);
        return $query->result_array();
    }

    public function update_zprice($ptxid, $pdate)
    {
        $qry = "UPDATE R SET
        DLVRMDOC_ZPRPRC = isnull((select TOP 1 MEXRATE_VAL from MEXRATE_TBL where MEXRATE_DT=? and MEXRATE_CURR=MSUP_SUPCR)*DLVRMDOC_PRPRC,DLVRMDOC_ZPRPRC)
        from DLVRMDOC_TBL R
        left join ( select RCV_DONO,RCV_ITMCD,RCV_RPNO,RCV_BCNO,RCV_SUPCD,MSUP_SUPCR from RCV_TBL
                        left join (select MSUP_SUPCD,max(MSUP_SUPCR) MSUP_SUPCR from v_supplier_customer_union group by MSUP_SUPCD) vsup on RCV_SUPCD=MSUP_SUPCD
                    group by RCV_DONO,RCV_ITMCD,RCV_RPNO,RCV_BCNO,RCV_SUPCD,MSUP_SUPCR
                ) vrcv on
        DLVRMDOC_DO=RCV_DONO and DLVRMDOC_ITMID=RCV_ITMCD and DLVRMDOC_AJU=RCV_RPNO and DLVRMDOC_NOPEN=RCV_BCNO
        WHERE DLVRMDOC_TXID=?";
        $this->db->query($qry, [$pdate, $ptxid]);
        return $this->db->affected_rows();
    }
}
