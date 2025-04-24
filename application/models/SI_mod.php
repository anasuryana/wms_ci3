<?php

class SI_mod extends CI_Model
{
    private $TABLENAME = "SI_TBL";
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

    public function check_Primary($data)
    {
        return $this->db->get_where($this->TABLENAME, $data)->num_rows();
    }

    public function select_lastsiline($pdoc)
    {
        // $qry = "select max(convert(bigint,SUBSTRING(SI_LINENO,13,4))) lline from SI_TBL where SI_DOC=?";
        $qry = "select max(SI_LINE) lline from SI_TBL where SI_DOC=?";
        $query = $this->db->query($qry, [$pdoc]);
        if ($query->num_rows() > 0) {
            $ret = $query->row();
            return $ret->lline;
        } else {
            return '0';
        }
    }

    public function selectAll_by($pwhere)
    {
        $this->db->select("a.*,MITM_ITMD1,STOCKQTY,MBSG_DESC,MCUS_CUSNM");
        $this->db->from($this->TABLENAME . " a");
        $this->db->join("XMCUS b", "a.SI_CUSCD=b.MCUS_CUSCD", 'left');
        $this->db->join("MITM_TBL c", "SI_ITMCD=MITM_ITMCD");
        $this->db->join("V_STOCK_FG_AFWH3 d", "SI_ITMCD=ITH_ITMCD", "LEFT");
        $this->db->join("XMBSG_TBL e", "SI_BSGRP=MBSG_BSGRP", 'left');
        $this->db->where($pwhere);
        $this->db->order_by('SI_OTHRMRK ASC, SI_DOCREFFETA ASC , SI_ITMCD ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectAll_AFWH3RT_by($pwhere)
    {
        $this->db->select("a.*,MITM_ITMD1,STOCKQTY,MBSG_DESC,MCUS_CUSNM");
        $this->db->from($this->TABLENAME . " a");
        $this->db->join("XMCUS b", "a.SI_CUSCD=b.MCUS_CUSCD", 'left');
        $this->db->join("MITM_TBL c", "SI_ITMCD=MITM_ITMCD");
        $this->db->join("V_STOCK_FG_AFWH3RT d", "SI_ITMCD=ITH_ITMCD", "LEFT");
        $this->db->join("XMBSG_TBL e", "SI_BSGRP=MBSG_BSGRP", 'left');
        $this->db->where($pwhere);
        $this->db->order_by('SI_OTHRMRK ASC, SI_DOCREFFETA ASC , SI_ITMCD ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function selectAll_NFWH4RT_by($pwhere)
    {
        $this->db->select("a.*,MITM_ITMD1,STOCKQTY,MBSG_DESC,MCUS_CUSNM");
        $this->db->from($this->TABLENAME . " a");
        $this->db->join("XMCUS b", "a.SI_CUSCD=b.MCUS_CUSCD", 'left');
        $this->db->join("MITM_TBL c", "SI_ITMCD=MITM_ITMCD");
        $this->db->join("V_STOCK_FG_NFWH4RT d", "SI_ITMCD=ITH_ITMCD", "LEFT");
        $this->db->join("XMBSG_TBL e", "SI_BSGRP=MBSG_BSGRP", 'left');
        $this->db->where($pwhere);
        $this->db->order_by('SI_OTHRMRK ASC, SI_DOCREFFETA ASC , SI_ITMCD ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectAllWithoutLocation_by($pwhere)
    {
        $this->db->select("a.*,MITM_ITMD1");
        $this->db->from($this->TABLENAME . " a");
        $this->db->join("MCUS_TBL b", "a.SI_CUSCD=b.MCUS_CUSCD");
        $this->db->join("MITM_TBL c", "SI_ITMCD=MITM_ITMCD");
        $this->db->where($pwhere);
        $this->db->order_by('SI_OTHRMRK ASC, SI_DOCREFFETA ASC , SI_ITMCD ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function selectItem_by($pwhere)
    {
        $this->db->select("SI_ITMCD");
        $this->db->from($this->TABLENAME . " a");
        $this->db->where($pwhere);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function lastserialid()
    {
        $qry = "select TOP 1 substring(SI_DOC, 11, 20) lser from SI_TBL
        WHERE convert(date, SI_LUPDT) = convert(date,GETDATE())
        ORDER BY convert(bigint,SUBSTRING(SI_DOC,11,20)) desc";
        $query = $this->db->query($qry);
        if ($query->num_rows() > 0) {
            $ret = $query->row();
            return $ret->lser;
        } else {
            return '0';
        }
    }

    public function selectAllg_byDATEVAR($pwhere, $plike)
    {
        $qry = "select SI_DOC,SI_CUSCD,MCUS_CUSNM,MAX(SI_OTHRMRK) SI_OTHRMRK,ISNULL(MAX(SI_WH),'AFWH3') SI_WH  from $this->TABLENAME  a
        INNER JOIN MCUS_TBL b on a.SI_CUSCD=b.MCUS_CUSCD
        WHERE convert(date, SI_LUPDT) = '$pwhere' AND SI_DOC LIKE '%$plike%' and SI_BSGRP = 'PSI1PPZIEP' AND SI_HRMRK NOT LIKE '%PATCO%' AND SI_HRMRK NOT LIKE '%MEI%'
        GROUP BY SI_DOC,SI_CUSCD,MCUS_CUSNM
        ";
        $query = $this->db->query($qry);
        return $query->result_array();
    }
    public function selectAllg_byDATEVARoth($pwhere, $plike)
    {
        $qry = "select SI_DOC,SI_CUSCD,MCUS_CUSNM,SI_BSGRP,MBSG_DESC,ISNULL(MAX(SI_WH),'AFWH3') SI_WH from $this->TABLENAME  a
        LEFT JOIN MCUS_TBL b on a.SI_CUSCD=b.MCUS_CUSCD
        left join XMBSG_TBL c on SI_BSGRP=MBSG_BSGRP
        WHERE convert(date, SI_LUPDT) = '$pwhere' AND SI_DOC LIKE '%$plike%' and MCUS_CUSNM NOT LIKE '%EPSON%' and isnull(SI_OTHRMRK,'') not in ('D116','D120','D114')
        GROUP BY SI_DOC,SI_CUSCD,MCUS_CUSNM, SI_BSGRP, MBSG_DESC
        ";
        $query = $this->db->query($qry);
        return $query->result_array();
    }
    public function selectAllg_byDATEVARCUS($pwhere, $plike)
    {
        $qry = "select SI_DOC,SI_CUSCD,MCUS_CUSNM,MAX(SI_OTHRMRK) SI_OTHRMRK, ISNULL(MAX(SI_WH),'AFWH3') SI_WH from $this->TABLENAME  a
        INNER JOIN MCUS_TBL b on a.SI_CUSCD=b.MCUS_CUSCD
        WHERE convert(date, SI_LUPDT) = '$pwhere' AND MCUS_CUSNM LIKE '%$plike%' and SI_BSGRP = 'PSI1PPZIEP' AND SI_HRMRK NOT LIKE '%PATCO%' AND SI_HRMRK NOT LIKE '%MEI%'
        GROUP BY SI_DOC,SI_CUSCD,MCUS_CUSNM
        ";
        $query = $this->db->query($qry);
        return $query->result_array();
    }
    public function selectAllg_byDATEVARCUSoth($pwhere, $plike)
    {
        $qry = "select SI_DOC,SI_CUSCD,MCUS_CUSNM,SI_BSGRP,MBSG_DESC,ISNULL(MAX(SI_WH),'AFWH3') SI_WH from $this->TABLENAME  a
        LEFT JOIN MCUS_TBL b on a.SI_CUSCD=b.MCUS_CUSCD
        left join XMBSG_TBL c on SI_BSGRP=MBSG_BSGRP
        WHERE convert(date, SI_LUPDT) = '$pwhere' AND MCUS_CUSNM LIKE '%$plike%' AND MCUS_CUSNM NOT LIKE '%EPSON%' and SI_OTHRMRK not in ('D116','D120','D114')
        GROUP BY SI_DOC,SI_CUSCD,MCUS_CUSNM,SI_BSGRP ,MBSG_DESC
        ";
        $query = $this->db->query($qry);
        return $query->result_array();
    }
    public function selectAllg_byVAR($plike)
    {
        $this->db->limit(55);
        $this->db->select("SI_DOC,SI_CUSCD,MCUS_CUSNM,MAX(SI_OTHRMRK) SI_OTHRMRK, ISNULL(MAX(SI_WH),'AFWH3') SI_WH");
        $this->db->from($this->TABLENAME . " a");
        $this->db->join("MCUS_TBL b", "a.SI_CUSCD=b.MCUS_CUSCD");
        $this->db->where('SI_FRUSER is not null', null, false);
        $this->db->like($plike)->where('SI_BSGRP', "PSI1PPZIEP");
        $this->db->not_like('SI_HRMRK', "MEI");
        $this->db->group_by("SI_DOC,SI_CUSCD,MCUS_CUSNM");
        $query = $this->db->get();
        return $query->result_array();
    }
    public function selectAllg_byVARoth($plike)
    {
        $this->db->limit(55);
        $this->db->select("SI_DOC,SI_CUSCD,MCUS_CUSNM,SI_BSGRP,MBSG_DESC,ISNULL(MAX(SI_WH),'AFWH3') SI_WH");
        $this->db->from($this->TABLENAME . " a");
        $this->db->join("MCUS_TBL b", "a.SI_CUSCD=b.MCUS_CUSCD", 'LEFT');
        $this->db->join("XMBSG_TBL c", "a.SI_BSGRP=MBSG_BSGRP", 'LEFT');
        $this->db->like($plike)->not_like('MCUS_CUSNM', "EPSON");
        $this->db->where_not_in("isnull(SI_OTHRMRK,'')", ['D116', 'D120', 'D114']);
        $this->db->group_by("SI_DOC,SI_CUSCD,MCUS_CUSNM,SI_BSGRP,MBSG_DESC");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function updatebyId($pdata, $pkey)
    {
        $this->db->where($pkey);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
    }

    public function selectScanbySI($psi)
    {
        $this->db->select("SI_DOCREFF,SI_ITMCD,SI_MDL,SI_REQDT,SI_QTY,CONVERT(BIGINT,COALESCE(TTLSCN,0)) TTLSCN,COALESCE(TTLLBL,0) TTLLBL,SI_LINENO");
        $this->db->from($this->TABLENAME . " a");
        $this->db->join("(select SISCN_LINENO, SUM(SISCN_SERQTY) TTLSCN,count(SISCN_SERQTY) TTLLBL FROM SISCN_TBL x INNER JOIN SER_TBL y ON x.SISCN_SER=y.SER_ID
        WHERE SISCN_DOC='$psi' GROUP BY SISCN_LINENO) b", "a.SI_LINENO=b.SISCN_LINENO ", "LEFT");
        $this->db->where("SI_DOC", $psi);
        $this->db->order_by('SI_HRMRK ASC, SI_ITMCD ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectscan_balancing($psi, $pitem)
    {
        $this->db->limit(1);
        $this->db->select("SI_DOCREFF,SI_ITMCD,SI_MDL,SI_REQDT,SI_QTY,CONVERT(BIGINT,COALESCE(TTLSCN,0)) TTLSCN,COALESCE(TTLLBL,0) TTLLBL");
        $this->db->from($this->TABLENAME . " a");
        $this->db->join("(select SISCN_DOC, SISCN_DOCREFF,SER_ITMID,SUM(SISCN_SERQTY) TTLSCN,count(SISCN_SERQTY) TTLLBL FROM SISCN_TBL x INNER JOIN SER_TBL y ON x.SISCN_SER=y.SER_ID
        WHERE SISCN_DOC='$psi' GROUP BY SISCN_DOC, SISCN_DOCREFF,SER_ITMID) b", "a.SI_DOC=b.SISCN_DOC AND a.SI_DOCREFF=b.SISCN_DOCREFF AND a.SI_ITMCD=b.SER_ITMID", "LEFT");
        $this->db->where("SI_DOC", $psi)->where("SI_ITMCD", $pitem)->where("SI_QTY>COALESCE(TTLSCN,0)");
        $this->db->order_by('SI_HRMRK ASC, SI_ITMCD ASC, SI_QTY DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectserfg_sugg($pitemcd, $pwh)
    {
        $qry = "EXEC sp_serfg_suggestion ?, ?";
        $query = $this->db->query($qry, array($pitemcd, $pwh));
        return $query->result_array();
    }

    public function selectserfg_sugg_all($pitemcd)
    {
        $qry = "EXEC sp_serfg_suggestion_all '$pitemcd'";
        $query = $this->db->query($qry);
        return $query->result_array();
    }

    public function selectserfg_sugg_alt($pitemcd, $pser, $pwh)
    {
        $qry = "EXEC sp_serfg_suggestion_alt ?, ? , ?";
        $query = $this->db->query($qry, array($pitemcd, $pser, $pwh));
        return $query->result_array();
    }

    public function select_lupdt_bydoc($pdoc)
    {
        $qry = "SELECT convert(date,max(SI_LUPDT)) LUPDT FROM SI_TBL WHERE SI_DOC= ?";
        $query = $this->db->query($qry, [$pdoc]);
        return $query->result_array();
    }

    public function deleteby_filter($pwhere)
    {
        $this->db->where($pwhere);
        $this->db->delete($this->TABLENAME);
        return $this->db->affected_rows();
    }

    public function select_customer_like($pwhere)
    {
        $this->db->select("MCUS_CUSCD,MCUS_CURCD,MCUS_CUSNM,MCUS_ABBRV,MCUS_ADDR1");
        $this->db->from("MCUS_TBL");
        $this->db->like($pwhere);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_customer_shipping_info_like($pwhere)
    {
        $this->db->select("MCUS_CUSCD,MCUS_CURCD,MCUS_CUSNM,MCUS_ABBRV,MCUS_ADDR1");
        $this->db->from("v_bg_cust_si");
        $this->db->join("MCUS_TBL", "SI_CUSCD=MCUS_CUSCD", "left");
        $this->db->like($pwhere);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_outgoing_wh_byassy_bg($pbg, $pdtfrom, $pdtto, $passy)
    {
        $qry = "SELECT SI_ITMCD,RTRIM(MITM_ITMD1) MITM_ITMD1,SI_DOC,isnull(SISCN_SERQTY,0) SISCN_SERQTY,SER_DOC,SISCN_SER,SI_DOCREFFETA,SISCN_LUPDT,SI_BSGRP,DLV_ID, ISNULL(SI_OTHRMRK,SI_HRMRK) SI_OTHRMRK,ITH_LUPDT,SER_RMRK FROM SI_TBL
        LEFT JOIN MITM_TBL ON SI_ITMCD=MITM_ITMCD
        LEFT JOIN SISCN_TBL ON SI_LINENO=SISCN_LINENO
        LEFT JOIN SER_TBL ON SISCN_SER=SER_ID
        LEFT JOIN DLV_TBL ON SISCN_SER=DLV_SER
        LEFT JOIN (SELECT ITH_SER,ITH_LUPDT FROM ITH_TBL WHERE ITH_FORM='OUT-SHP-FG' and ITH_WH in ('ARSHP','ARSHPRTN','ARSHPRTN2')) VSHP ON DLV_SER=ITH_SER
        WHERE SI_QTY>0 AND SI_BSGRP=? AND (SI_LUPDT BETWEEN ? AND ? )
        AND SI_ITMCD LIKE ?
        order by SI_DOCREFFETA, SISCN_LUPDT";
        $query = $this->db->query($qry, [$pbg, $pdtfrom, $pdtto, "%" . $passy . "%"]);
        return $query->result_array();
    }

    public function select_outgoing_wh_byjob_bg($pbg, $pdtfrom, $pdtto, $pjob)
    {
        $qry = "SELECT SI_ITMCD,RTRIM(MITM_ITMD1) MITM_ITMD1,SI_DOC,isnull(SISCN_SERQTY,0) SISCN_SERQTY,SER_DOC,SISCN_SER,SI_DOCREFFETA,SISCN_LUPDT,SI_BSGRP,DLV_ID, ISNULL(SI_OTHRMRK,SI_HRMRK) SI_OTHRMRK,ITH_LUPDT,SER_RMRK  FROM SI_TBL
        LEFT JOIN MITM_TBL ON SI_ITMCD=MITM_ITMCD
        LEFT JOIN SISCN_TBL ON SI_LINENO=SISCN_LINENO
        LEFT JOIN SER_TBL ON SISCN_SER=SER_ID
        LEFT JOIN DLV_TBL ON SISCN_SER=DLV_SER
        LEFT JOIN (SELECT ITH_SER,ITH_LUPDT FROM ITH_TBL WHERE ITH_FORM='OUT-SHP-FG' and ITH_WH in ('ARSHP','ARSHPRTN','ARSHPRTN2')) VSHP ON DLV_SER=ITH_SER
        WHERE SI_QTY>0 AND SI_BSGRP=? AND (SI_LUPDT BETWEEN ? AND ? )
        AND SER_DOC LIKE ?
        order by SI_DOCREFFETA, SISCN_LUPDT";
        $query = $this->db->query($qry, [$pbg, $pdtfrom, $pdtto, "%" . $pjob . "%"]);
        return $query->result_array();
    }

    public function select_outgoing_wh_byreff_bg($pbg, $pdtfrom, $pdtto, $preff)
    {
        $qry = "SELECT SI_ITMCD,RTRIM(MITM_ITMD1) MITM_ITMD1,SI_DOC,isnull(SISCN_SERQTY,0) SISCN_SERQTY,SER_DOC,SISCN_SER,SI_DOCREFFETA,SISCN_LUPDT,SI_BSGRP,DLV_ID, ISNULL(SI_OTHRMRK,SI_HRMRK) SI_OTHRMRK,ITH_LUPDT,SER_RMRK FROM SI_TBL
        LEFT JOIN MITM_TBL ON SI_ITMCD=MITM_ITMCD
        LEFT JOIN SISCN_TBL ON SI_LINENO=SISCN_LINENO
        LEFT JOIN SER_TBL ON SISCN_SER=SER_ID
        LEFT JOIN DLV_TBL ON SISCN_SER=DLV_SER
        LEFT JOIN (SELECT ITH_SER,ITH_LUPDT FROM ITH_TBL WHERE ITH_FORM='OUT-SHP-FG' and ITH_WH in ('ARSHP','ARSHPRTN','ARSHPRTN2')) VSHP ON DLV_SER=ITH_SER
        WHERE SI_QTY>0 AND SI_BSGRP=? AND (SI_LUPDT BETWEEN ? AND ? )
        AND SISCN_SER LIKE ?
        order by SI_DOCREFFETA, SISCN_LUPDT";
        $query = $this->db->query($qry, [$pbg, $pdtfrom, $pdtto, "%" . $preff . "%"]);
        return $query->result_array();
    }

    public function select_outgoing_wh_bySI_bg($pbg, $pdtfrom, $pdtto, $pSI)
    {
        $qry = "SELECT SI_ITMCD,RTRIM(MITM_ITMD1) MITM_ITMD1,SI_DOC,isnull(SISCN_SERQTY,0) SISCN_SERQTY,SER_DOC,SISCN_SER,SI_DOCREFFETA,SISCN_LUPDT,SI_BSGRP,DLV_ID, ISNULL(SI_OTHRMRK,SI_HRMRK) SI_OTHRMRK,ITH_LUPDT,SER_RMRK  FROM SI_TBL
        LEFT JOIN MITM_TBL ON SI_ITMCD=MITM_ITMCD
        LEFT JOIN SISCN_TBL ON SI_LINENO=SISCN_LINENO
        LEFT JOIN SER_TBL ON SISCN_SER=SER_ID
        LEFT JOIN DLV_TBL ON SISCN_SER=DLV_SER
        LEFT JOIN (SELECT ITH_SER,ITH_LUPDT FROM ITH_TBL WHERE ITH_FORM='OUT-SHP-FG' and ITH_WH in ('ARSHP','ARSHPRTN','ARSHPRTN2')) VSHP ON DLV_SER=ITH_SER
        WHERE SI_QTY>0 AND SI_BSGRP=? AND (SI_LUPDT BETWEEN ? AND ? )
        AND SI_DOC LIKE ?
        order by SI_DOCREFFETA, SISCN_LUPDT";
        $query = $this->db->query($qry, [$pbg, $pdtfrom, $pdtto, "%" . $pSI . "%"]);
        return $query->result_array();
    }

    public function select_outgoing_wh_byTXID_bg($pbg, $pdtfrom, $pdtto, $pTXID)
    {
        $qry = "SELECT SI_ITMCD,RTRIM(MITM_ITMD1) MITM_ITMD1,SI_DOC,isnull(SISCN_SERQTY,0) SISCN_SERQTY,SER_DOC,SISCN_SER,SI_DOCREFFETA,SISCN_LUPDT,SI_BSGRP,DLV_ID, ISNULL(SI_OTHRMRK,SI_HRMRK) SI_OTHRMRK,ITH_LUPDT,SER_RMRK FROM SI_TBL
        LEFT JOIN MITM_TBL ON SI_ITMCD=MITM_ITMCD
        LEFT JOIN SISCN_TBL ON SI_LINENO=SISCN_LINENO
        LEFT JOIN SER_TBL ON SISCN_SER=SER_ID
        LEFT JOIN DLV_TBL ON SISCN_SER=DLV_SER
        LEFT JOIN (SELECT ITH_SER,ITH_LUPDT FROM ITH_TBL WHERE ITH_FORM='OUT-SHP-FG' and ITH_WH in ('ARSHP','ARSHPRTN','ARSHPRTN2')) VSHP ON DLV_SER=ITH_SER
        WHERE SI_QTY>0 AND SI_BSGRP=? AND (SI_LUPDT BETWEEN ? AND ? )
        AND DLV_ID LIKE ?
        order by SI_DOCREFFETA, SISCN_LUPDT";
        $query = $this->db->query($qry, [$pbg, $pdtfrom, $pdtto, "%" . $pTXID . "%"]);
        return $query->result_array();
    }

    public function select_location($pbg, $pcust)
    {
        $qry = "SELECT SI_OTHRMRK FROM
        (SELECT ISNULL(SI_OTHRMRK,SI_HRMRK) SI_OTHRMRK FROM SI_TBL
                WHERE SI_QTY>0 AND SI_BSGRP=? AND SI_CUSCD=?) V1
                GROUP BY SI_OTHRMRK
                ORDER BY SI_OTHRMRK";
        $query = $this->db->query($qry, [$pbg, $pcust]);
        return $query->result_array();
    }

    public function select_wh($pser)
    {
        $qry = "select isnull(SI_WH,'AFWH3') SI_WH from SISCN_TBL inner join SI_TBL on SISCN_LINENO=SI_LINENO where SISCN_SER=?";
        $query = $this->db->query($qry, $pser);
        return $query->result_array();
    }

    public function select_wh_top1($pdoc)
    {
        $qry = "select TOP 1 isnull(SI_WH,'AFWH3') SI_WH from SI_TBL WHERE SI_DOC = ?";
        $query = $this->db->query($qry, $pdoc);
        // return $query->result_array();
        if ($query->num_rows() > 0) {
            $ret = $query->row();
            return $ret->SI_WH;
        } else {
            return '??';
        }
    }
    public function select_wh_top1_byser($pser)
    {
        $qry = "SELECT TOP 1 ITH_WH,UPPER(ITH_ITMCD) ITH_ITMCD FROM ITH_TBL WHERE ITH_SER=? AND ITH_WH='AFWH3'";
        $query = $this->db->query($qry, $pser);
        return $query->result_array();
    }
    public function select_wh_top1_byreffno($preffno)
    {
        $qry = "select TOP 1 isnull(SI_WH,'AFWH3') SI_WH from SI_TBL LEFT JOIN SISCN_TBL ON SI_LINENO=SISCN_LINENO
        WHERE SISCN_SER=?";
        $query = $this->db->query($qry, $preffno);
        // return $query->result_array();
        if ($query->num_rows() > 0) {
            $ret = $query->row();
            return $ret->SI_WH;
        } else {
            return '??';
        }
    }

    public function select_wh_by_txid($pdoc)
    {
        $this->db->select("SI_WH");
        $this->db->from("DLV_TBL");
        $this->db->join("SISCN_TBL", "DLV_SER=SISCN_SER", "left");
        $this->db->join("SI_TBL", "SISCN_LINENO=SI_LINENO", "left");
        $this->db->where("DLV_ID", $pdoc);
        $query = $this->db->get();
        // return $query->result_array();

        if ($query->num_rows() > 0) {
            $ret = $query->row();
            return $ret->SI_WH;
        } else {
            return '??';
        }
    }
}
