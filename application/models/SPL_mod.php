<?php

class SPL_mod extends CI_Model
{
    private $TABLENAME = "SPL_TBL";
    private $SPECIALWO = ['22-5A09-217724501', '22-6A27-F65929-09V', '22-ZB28-219236900', '21-XA08-221093101ES'];
    public function __construct()
    {
        $this->load->database();
    }

    public function select_last_line_doc($doc)
    {
        $qry = "SELECT MAX(SPL_LINEDATA) LASTLINEDATA FROM SPL_TBL WHERE SPL_DOC=?";
        $query = $this->db->query($qry, [$doc]);
        if ($query->num_rows() > 0) {
            $ret = $query->row();
            return $ret->LASTLINEDATA;
        } else {
            return 0;
        }
    }

    public function selectdiff()
    {
        $qry = "select distinct PPSN2_PSNNO from XPPSN2_TBL where PPSN2_PSNNO
        not in (select SPL_DOC from SPL_TBL)";
        $resq = $this->db->query($qry);
        return $resq->result_array();
    }

    public function selectlastno_request($pYear, $pMonth, $data2)
    {
        $qry = " SELECT MAX(CONVERT(INT,RIGHT(SPL_DOC,4))) LASTNO FROM SPL_TBL WHERE SPL_DOC LIKE 'PR-%'
        AND YEAR(SPL_LUPDT)=? AND MONTH(SPL_LUPDT)=? AND SUBSTRING(SPL_DOC,4,3)=?
        ORDER BY 1 DESC";
        $query = $this->db->query($qry, [$pYear, $pMonth, $data2]);
        if ($query->num_rows() > 0) {
            $ret = $query->row();
            return $ret->LASTNO;
        } else {
            return 0;
        }
    }

    public function updatebyId($pdata, $pkey)
    {
        $this->db->where($pkey);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
    }

    public function insert($data)
    {
        $this->db->insert($this->TABLENAME, $data);
        return $this->db->affected_rows();
    }
    public function insert_log($data)
    {
        $this->db->insert("SPL_LOG", $data);
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

    public function xspl_mega($psn, $line, $cat, $fr)
    {
        $qry = "exec xsp_megatowms_spl '$psn', '$line','$cat','$fr'";
        $resq = $this->db->query($qry);
        return $resq->result_array();
    }

    public function xspl_mega_pis($pdocno, $pline, $pmcz)
    {
        $qry = "exec xsp_megatowms_pis ?,?,?";
        $resq = $this->db->query($qry, array($pdocno, $pline, $pmcz));
        return $resq->result_array();
    }

    public function selectWO($pwo)
    {
        $qry = "select XWO.*, CONVERT(bigint,coalesce(LBLTTL,0)) LBLTTL,MITM_ITMD1,PDPP_BSGRP,PDPP_CUSCD,MITM_LBLCLR,COALESCE(MITM_SHTQTY,0) MITM_SHTQTY,MITM_SPQ from
        XWO LEFT JOIN MITM_TBL ON MITM_ITMCD=PDPP_MDLCD
        LEFT JOIN
        ( select SER_DOC,SUM(SER_QTYLOT) LBLTTL from SER_TBL x WHERE SER_DOC LIKE '%$pwo%'
        GROUP BY SER_DOC
        ) v2 on PDPP_WONO=v2.SER_DOC WHERE PDPP_WONO LIKE '%$pwo%'";
        $query = $this->db->query($qry);
        return $query->result_array();
    }
    public function selectWOOpen($pwo)
    {
        $qry = "select XWO.*, CONVERT(bigint,coalesce(LBLTTL,0)) LBLTTL,MITM_ITMD1,PDPP_BSGRP,PDPP_CUSCD,MITM_LBLCLR,COALESCE(MITM_SHTQTY,0) MITM_SHTQTY,MITM_SPQ from
        XWO LEFT JOIN MITM_TBL ON MITM_ITMCD=PDPP_MDLCD
        LEFT JOIN
        ( select SER_DOC,SUM(SER_QTYLOT) LBLTTL from SER_TBL x WHERE SER_DOC LIKE '%$pwo%'
        GROUP BY SER_DOC
        ) v2 on PDPP_WONO=v2.SER_DOC WHERE PDPP_WONO LIKE '%$pwo%'  and PDPP_WORQT!=PDPP_GRNQT AND PDPP_COMFG=0"; #
        $query = $this->db->query($qry);
        return $query->result_array();
    }
    public function selectWOOpen_assy_as_sub($pwo)
    {
        $qry = "select XWO.*, CONVERT(bigint,coalesce(LBLTTL,0)) LBLTTL,MITM_ITMD1,PDPP_BSGRP,PDPP_CUSCD from
        XWO
        LEFT JOIN
        ( select SER_DOC,SUM(SER_QTYLOT) LBLTTL from SER_TBL x WHERE SER_DOC LIKE '%$pwo%' AND SER_REFNO!='SN'
        GROUP BY SER_DOC
        ) v2 on PDPP_WONO=v2.SER_DOC
        inner join v_assy_as_sub on SUBSTRING(PDPP_MDLCD,1,9)=PWOP_BOMPN
        WHERE PDPP_WONO LIKE '%$pwo%' and PDPP_WORQT!=PDPP_GRNQT AND PDPP_COMFG=0";
        $query = $this->db->query($qry);
        return $query->result_array();
    }

    public function selectWOITEM($pwo, $pitem)
    {
        $qry = "select CONVERT(bigint,(PDPP_WORQT-coalesce(LBLTTL,0))) OSTQTY,PDPP_BSGRP,PDPP_CUSCD,PDPP_WORQT,PDPP_GRNQT,PDPP_COMFG,CONVERT(bigint,(PDPP_WORQT-PDPP_GRNQT)) OSTQTYMG,PDPP_ISUDT,RTRIM(PDPP_WONO) PDPP_WONO from
        XWO a LEFT JOIN  ( select SER_DOC,SUM(SER_QTYLOT) LBLTTL from SER_TBL x WHERE SER_ITMID LIKE ? and SER_DOC = ? AND SER_ID=ISNULL(SER_REFNO,SER_ID)
        GROUP BY SER_DOC ) v2 on PDPP_WONO=v2.SER_DOC WHERE PDPP_MDLCD LIKE ? and PDPP_WONO = ? 
        AND PDPP_BSGRP = 'PSI1PPZIEP' 
        ORDER BY PDPP_WONO DESC";
        $query = $this->db->query($qry, ["%" . $pitem . "%", $pwo, "%" . $pitem . "%", $pwo]);
        return $query->result_array();
    }
    public function selectWOITEM_es($pwo, $pitem)
    {
        $qry = "select CONVERT(bigint,(PDPP_WORQT-coalesce(LBLTTL,0))) OSTQTY,PDPP_BSGRP,PDPP_CUSCD,PDPP_WORQT,PDPP_GRNQT,PDPP_COMFG,CONVERT(bigint,(PDPP_WORQT-PDPP_GRNQT)) OSTQTYMG,PDPP_ISUDT,RTRIM(PDPP_WONO) PDPP_WONO from
        XWO a LEFT JOIN  ( select SER_DOC,SUM(SER_QTYLOT) LBLTTL from SER_TBL x WHERE SER_ITMID LIKE ? and SER_DOC like ? AND SER_ID=ISNULL(SER_REFNO,SER_ID)
        GROUP BY SER_DOC ) v2 on PDPP_WONO=v2.SER_DOC WHERE PDPP_MDLCD LIKE ? and PDPP_WONO like ? ORDER BY PDPP_WONO DESC";
        $query = $this->db->query($qry, ["%" . $pitem . "%", $pwo, "%" . $pitem . "%", "%$pwo%"]);
        return $query->result_array();
    }

    public function selectWOITEM_open($pwo, $pitem)
    {
        $qry = "SELECT CONVERT(BIGINT, (PDPP_WORQT - coalesce(LBLTTL, 0))) OSTQTY
                    ,PDPP_BSGRP
                    ,PDPP_CUSCD
                    ,PDPP_WORQT
                    ,PDPP_GRNQT
                    ,PDPP_COMFG
                    ,CONVERT(BIGINT, (PDPP_WORQT - PDPP_GRNQT)) OSTQTYMG
                    ,PDPP_ISUDT
                    ,PDPP_WONO
                FROM XWO a
                LEFT JOIN (
                    SELECT SER_DOC
                        ,SUM(SER_QTYLOT) LBLTTL
                    FROM SER_TBL x
                    WHERE SER_ITMID LIKE ?
                        AND SER_DOC = ?
                        AND SER_ID = ISNULL(SER_REFNO, SER_ID)
                    GROUP BY SER_DOC
                    ) v2 ON PDPP_WONO = v2.SER_DOC
                WHERE PDPP_MDLCD LIKE ?
                    AND PDPP_WONO = ?
                    AND (PDPP_COMFG = '0')
                ORDER BY PDPP_WONO DESC
                ";
        $query = $this->db->query($qry, ["%" . $pitem . "%", $pwo, "%" . $pitem . "%", $pwo]);
        return $query->result_array();
    }

    public function selectby4par($pspl, $pcat, $pline, $pfr)
    {
        $this->db->select("SPL_ORDERNO,SPL_RACKNO, SPL_ITMCD,MITM_SPTNO,  SPL_QTYUSE, SPL_MS, TTLREQ, TTLSCN");
        $this->db->from("(SELECT SPL_ORDERNO,SPL_RACKNO, SPL_ITMCD, MAX(SPL_QTYUSE) SPL_QTYUSE, SPL_MS, SUM(SPL_QTYREQ) TTLREQ
        FROM $this->TABLENAME WHERE SPL_DOC='$pspl' AND SPL_CAT='$pcat' AND SPL_LINE='$pline' AND SPL_FEDR='$pfr'
        GROUP BY SPL_ORDERNO,SPL_RACKNO, SPL_ITMCD,  SPL_MS) a");
        $this->db->join('MITM_TBL b', 'a.SPL_ITMCD=b.MITM_ITMCD');
        $this->db->join(
            "(SELECT SPLSCN_ORDERNO,SPLSCN_ITMCD,SUM(SPLSCN_QTY) TTLSCN
        FROM SPLSCN_TBL WHERE SPLSCN_DOC='$pspl' AND SPLSCN_CAT='$pcat' AND SPLSCN_LINE='$pline' AND SPLSCN_FEDR='$pfr'
        GROUP BY SPLSCN_DOC,SPLSCN_CAT, SPLSCN_LINE, SPLSCN_FEDR,SPLSCN_ORDERNO,SPLSCN_ITMCD) c",
            "a.SPL_ITMCD=c.SPLSCN_ITMCD AND a.SPL_ORDERNO=c.SPLSCN_ORDERNO",
            'left'
        );
        $this->db->order_by('SPL_ORDERNO ASC,SPL_ITMCD ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectby3par($pspl, $pcat, $pline)
    {
        $this->db->select("SPL_ORDERNO,SPL_RACKNO, SPL_ITMCD,MITM_SPTNO,  SPL_QTYUSE, SPL_MS, TTLREQ, TTLSCN");
        $this->db->from("(SELECT SPL_ORDERNO,SPL_RACKNO, SPL_ITMCD, MAX(SPL_QTYUSE) SPL_QTYUSE, SPL_MS, SUM(SPL_QTYREQ) TTLREQ
        FROM $this->TABLENAME WHERE SPL_DOC='$pspl' AND SPL_CAT='$pcat' AND SPL_LINE='$pline'
        GROUP BY SPL_ORDERNO,SPL_RACKNO, SPL_ITMCD,  SPL_MS) a");
        $this->db->join('MITM_TBL b', 'a.SPL_ITMCD=b.MITM_ITMCD');
        $this->db->join(
            "(SELECT SPLSCN_ORDERNO,SPLSCN_ITMCD,SUM(SPLSCN_QTY) TTLSCN
        FROM SPLSCN_TBL WHERE SPLSCN_DOC='$pspl' AND SPLSCN_CAT='$pcat' AND SPLSCN_LINE='$pline'
        GROUP BY SPLSCN_DOC,SPLSCN_CAT, SPLSCN_LINE, SPLSCN_FEDR,SPLSCN_ORDERNO,SPLSCN_ITMCD) c",
            "a.SPL_ITMCD=c.SPLSCN_ITMCD AND a.SPL_ORDERNO=c.SPLSCN_ORDERNO",
            'left'
        );
        $this->db->order_by('SPL_ORDERNO ASC,SPL_ITMCD ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectby4par_result($pspl, $pcat, $pline, $pfr)
    {
        $this->db->select("SPL_MC,SPL_ORDERNO,SPL_RACKNO, SPL_ITMCD,MITM_SPTNO, SPL_PROCD, SPL_QTYUSE, SPL_MS, TTLREQ, 0 TTLSCN,PRISSUDT");
        $this->db->from("(SELECT SPL_PROCD, SPL_MC,SPL_ORDERNO,SPL_RACKNO, SPL_ITMCD, MAX(SPL_QTYUSE) SPL_QTYUSE, SPL_MS, SUM(SPL_QTYREQ) TTLREQ,MIN(CONVERT(DATE,SPL_LUPDT)) PRISSUDT
        FROM $this->TABLENAME WHERE SPL_DOC='$pspl' AND SPL_CAT='$pcat' AND SPL_LINE='$pline' AND SPL_FEDR='$pfr'
        GROUP BY SPL_MC,SPL_ORDERNO,SPL_RACKNO, SPL_ITMCD,  SPL_MS,SPL_PROCD) a");
        $this->db->join('MITM_TBL b', 'a.SPL_ITMCD=b.MITM_ITMCD');
        $this->db->order_by('SPL_PROCD ASC,SPL_ORDERNO ASC,SPL_MC ASC,SPL_ITMCD ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectkitby4par($pspl, $pcat, $pline, $pfr)
    {
        $this->db->select("SPL_PROCD,SPL_ORDERNO,SPL_RACKNO, rtrim(SPL_ITMCD) SPL_ITMCD,MITM_SPTNO, SPL_QTYUSE, SPL_MC, SPL_MS, TTLREQ, TTLSCN, SPL_ITMRMRK,TTLREQ TTLREQB4");
        $this->db->from("(SELECT SPL_PROCD,SPL_ORDERNO,SPL_RACKNO,aliasrack, SPL_ITMCD, max(SPL_QTYUSE) SPL_QTYUSE,SPL_MS, SPL_MC, SUM(SPL_QTYREQ) TTLREQ, 0 TTLSCN , max(SPL_ITMRMRK) SPL_ITMRMRK
        FROM $this->TABLENAME LEFT JOIN (SELECT MSTLOC_CD,MAX(aliasrack) aliasrack FROM vinitlocation GROUP BY MSTLOC_CD) VRAK on SPL_RACKNO=MSTLOC_CD WHERE SPL_DOC='$pspl' AND SPL_CAT='$pcat' AND SPL_LINE='$pline' AND SPL_FEDR='$pfr'
        GROUP BY SPL_PROCD,SPL_ORDERNO,SPL_RACKNO,aliasrack, SPL_ITMCD,  SPL_MC,SPL_MS) a");
        $this->db->join('MITM_TBL b', 'a.SPL_ITMCD=b.MITM_ITMCD', 'LEFT');
        $this->db->order_by('aliasrack,SPL_RACKNO,SPL_ORDERNO,SPL_MC,SPL_ITMCD,SPL_PROCD');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectkitbyPSN($pspl)
    {
        $this->db->select("SPL_PROCD,SPL_ORDERNO,SPL_RACKNO, rtrim(SPL_ITMCD) SPL_ITMCD,rtrim(MITM_SPTNO) MITM_SPTNO, SPL_QTYUSE, SPL_MC, SPL_MS, TTLREQ, TTLSCN, SPL_ITMRMRK,TTLREQ TTLREQB4,SPL_LINE,SPL_CAT,SPL_FEDR");
        $this->db->from("(SELECT SPL_PROCD,RTRIM(SPL_ORDERNO) SPL_ORDERNO,SPL_RACKNO,aliasrack, SPL_ITMCD, max(SPL_QTYUSE) SPL_QTYUSE,SPL_MS, RTRIM(SPL_MC) SPL_MC, SUM(SPL_QTYREQ) TTLREQ, 0 TTLSCN , max(SPL_ITMRMRK) SPL_ITMRMRK,SPL_LINE,SPL_CAT,SPL_FEDR
        FROM $this->TABLENAME LEFT JOIN (SELECT MSTLOC_CD,MAX(aliasrack) aliasrack FROM vinitlocation GROUP BY MSTLOC_CD) VRAK on SPL_RACKNO=MSTLOC_CD WHERE SPL_DOC='$pspl'
        GROUP BY SPL_LINE,SPL_CAT,SPL_FEDR, SPL_PROCD,SPL_ORDERNO,SPL_RACKNO,aliasrack, SPL_ITMCD,  SPL_MC,SPL_MS) a");
        $this->db->join('MITM_TBL b', 'a.SPL_ITMCD=b.MITM_ITMCD', 'LEFT');
        $this->db->order_by('SPL_CAT,SPL_LINE,SPL_FEDR,aliasrack,SPL_RACKNO,SPL_ORDERNO,SPL_MC,SPL_ITMCD,SPL_PROCD');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectkitby4parv($pspl, $pcat, $pline, $pfr)
    {
        $this->db->select("SPL_ORDERNO,SPL_RACKNO, UPPER(SPL_ITMCD) SPL_ITMCD,MITM_SPTNO, SPL_QTYUSE, SPL_MC, SPL_MS, TTLREQ, TTLSCN");
        $this->db->from("(SELECT SPL_ORDERNO,SPL_RACKNO,aliasrack, SPL_ITMCD, max(SPL_QTYUSE) SPL_QTYUSE,SPL_MS, SPL_MC, SUM(SPL_QTYREQ) TTLREQ, 0 TTLSCN
        FROM $this->TABLENAME LEFT JOIN (select MSTLOC_CD,max(aliasrack) aliasrack  from vinitlocation group by MSTLOC_CD) vloc on SPL_RACKNO=MSTLOC_CD
        WHERE SPL_DOC='$pspl' AND SPL_CAT='$pcat' AND SPL_LINE='$pline' AND SPL_FEDR='$pfr'
        GROUP BY SPL_ORDERNO,SPL_RACKNO,aliasrack, SPL_ITMCD,  SPL_MC,SPL_MS) a");
        $this->db->join('MITM_TBL b', 'a.SPL_ITMCD=b.MITM_ITMCD');
        $this->db->order_by('SPL_ORDERNO,SPL_MC,SPL_ITMCD'); //
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectscan_balancing($pspl, $pcat, $pline, $pfr, $pitem)
    {
        $this->db->limit(1);
        $this->db->select("SPL_DOC,SPL_CAT, SPL_LINE, SPL_FEDR, SPL_ORDERNO,SPL_ITMCD, convert(int,TTLREQ) TTLREQ, convert(int,COALESCE(TTLSCN,0)) TTLSCN ");
        $this->db->from(
            "(SELECT SPL_DOC,SPL_CAT, SPL_LINE, SPL_FEDR, SPL_ORDERNO,SPL_ITMCD, sum(SPL_QTYREQ) TTLREQ from $this->TABLENAME
            where SPL_DOC = '$pspl' AND  SPL_CAT='$pcat' AND SPL_LINE='$pline' AND SPL_FEDR='$pfr' AND SPL_ITMCD='$pitem'
            GROUP BY SPL_DOC,SPL_CAT,SPL_LINE,SPL_FEDR,SPL_ORDERNO,SPL_ITMCD) a"
        );
        $this->db->join(
            "(select SPLSCN_DOC,SPLSCN_CAT,SPLSCN_LINE,SPLSCN_FEDR,SPLSCN_ORDERNO,SPLSCN_ITMCD, sum(SPLSCN_QTY) TTLSCN from SPLSCN_TBL
            where SPLSCN_DOC = '$pspl' AND  SPLSCN_CAT='$pcat' AND SPLSCN_LINE='$pline' AND SPLSCN_FEDR='$pfr' and SPLSCN_ITMCD='$pitem'
            GROUP BY SPLSCN_DOC,SPLSCN_CAT,SPLSCN_LINE,SPLSCN_FEDR,SPLSCN_ORDERNO,SPLSCN_ITMCD) v1",
            "a.SPL_DOC=v1.SPLSCN_DOC and a.SPL_CAT=v1.SPLSCN_CAT and a.SPL_LINE=v1.SPLSCN_LINE and a.SPL_FEDR=v1.SPLSCN_FEDR and a.SPL_ORDERNO=v1.SPLSCN_ORDERNO and a.SPL_ITMCD=v1.SPLSCN_ITMCD",
            "left"
        );
        $this->db->where('TTLREQ>COALESCE(TTLSCN,0)');
        $this->db->order_by('SPL_ORDERNO ASC');
        // $this->db->where('SPL_DOC', $pspl)->where('SPL_CAT', $pcat)->where('SPL_LINE', $pline)->where('SPL_FEDR', $pfr);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selecthead($psnno, $line, $fr)
    {
        $qry = "exec xsp_megapsnhead ?, ?, ?";
        $query = $this->db->query($qry, array($psnno, $line, $fr));
        return $query->result_array();
    }

    public function select_pi_head_bypsn($psnno)
    { #pi = picking instruction
        $qry = "exec xsp_megapsnhead_bypsn ?";
        $query = $this->db->query($qry, [$psnno]);
        return $query->result_array();
    }

    public function selecthead_psnonly($psnno)
    {
        $qry = "select PPSN1_WONO from XPPSN1 WHERE PPSN1_PSNNO=? GROUP BY PPSN1_WONO";
        $query = $this->db->query($qry, array($psnno));
        return $query->result_array();
    }

    public function selecthead_nofr($psnno, $line)
    {
        $qry = "exec xsp_megapsnhead_nofr ?, ?";
        $query = $this->db->query($qry, array($psnno, $line));
        return $query->result_array();
    }

    public function select_category($ppsn)
    {
        $this->db->select("SPL_CAT");
        $this->db->from($this->TABLENAME . " a");
        $this->db->where('SPL_DOC', $ppsn);
        $this->db->group_by('SPL_CAT');
        $this->db->order_by('SPL_CAT asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_line($ppsn, $pcat)
    {
        $this->db->select("SPL_LINE");
        $this->db->from($this->TABLENAME . " a");
        $this->db->where('SPL_DOC', $ppsn)->where('SPL_CAT', $pcat);
        $this->db->group_by('SPL_LINE');
        $this->db->order_by('SPL_LINE asc');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function select_fr($ppsn, $pcat, $pline)
    {
        $this->db->select("SPL_FEDR");
        $this->db->from($this->TABLENAME . " a");
        $this->db->where('SPL_DOC', $ppsn)->where('SPL_CAT', $pcat)->where('SPL_LINE', $pline);
        $this->db->group_by('SPL_FEDR');
        $this->db->order_by('SPL_FEDR asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_header_psn($ppsn)
    {
        $qry = "select SPL_DOC, SPL_CAT,SPL_LINE,SPL_FEDR from SPL_TBL where SPL_DOC=?
        group by SPL_DOC, SPL_CAT,SPL_LINE,SPL_FEDR";
        $query = $this->db->query($qry, [$ppsn]);
        return $query->result_array();
    }
    public function select_header_partreq_unapproved($ppsn)
    {
        $qry = "select SPL_DOC, SPL_ITMCD from SPL_TBL where SPL_DOC=? AND SPL_APPRV_TM IS NOT NULL
        group by SPL_DOC, SPL_ITMCD";
        $query = $this->db->query($qry, [$ppsn]);
        return $query->result_array();
    }

    public function select_z_job($pjob)
    {
        $qry = "select PPSN2_PSNNO,PIS3_WONO,TWOR_MDLCD,PPSN2_ITMCAT,PPSN2_LINENO,PPSN2_FR,PPSN2_MCZ, PPSN2_SUBPN,PIS2_QTPER,PPSN2_MSFLG,TWOR_LOTSZ, TWOR_LOTSZ*PIS2_QTPER MYQTREQ ,PDPP_CUSCD
        from  SRVMEGA.PSI_MEGAEMS.dbo.PPSN2_TBL a LEFT JOIN SRVMEGA.PSI_MEGAEMS.dbo.PIS3_TBL b
         on a.PPSN2_DOCNO=b.PIS3_DOCNO and a.PPSN2_LINENO=b.PIS3_LINENO and a.PPSN2_PROCD=b.PIS3_PROCD and
        a.PPSN2_MCZ=b.PIS3_MCZ AND
        PPSN2_SUBPN=(case when PPSN2_MSFLG='M' THEN PIS3_MPART ELSE PIS3_ITMCD END)
        LEFT JOIN SRVMEGA.PSI_MEGAEMS.dbo.PIS2_TBL x on b.PIS3_DOCNO=PIS2_DOCNO and PIS3_WONO=PIS2_WONO AND PIS3_LINENO=PIS2_LINENO AND
        PIS3_FR=PIS2_FR AND PIS3_MCZ=PIS2_MCZ AND PIS3_MPART=PIS2_MPART
        LEFT JOIN SRVMEGA.PSI_MEGAEMS.dbo.TWOR_CIM c on PIS3_WONO=c.TWOR_WONO
        LEFT JOIN SRVMEGA.PSI_MEGAEMS.dbo.PDPP_TBL f on c.TWOR_WONO=f.PDPP_WONO
        WHERE PIS3_WONO in ($pjob)
        GROUP BY PPSN2_PSNNO,PIS3_WONO,TWOR_MDLCD,PPSN2_ITMCAT,PPSN2_LINENO,PPSN2_FR,PPSN2_MCZ, PPSN2_SUBPN,PIS2_QTPER,PPSN2_MSFLG,TWOR_LOTSZ ,PDPP_CUSCD";
        $query = $this->db->query($qry);
        return $query->result_array();
    }
    public function select_z_getpsn_byjob($pjob)
    {
        $qry = "select RTRIM(PPSN1_PSNNO) PPSN1_PSNNO,RTRIM(PPSN1_DOCNO) PPSN1_DOCNO,RTRIM(PPSN1_MDLCD) PPSN1_MDLCD,RTRIM(MAX(PPSN1_BSGRP)) PPSN1_BSGRP FROM XPPSN1 WHERE PPSN1_WONO IN ($pjob)
                AND PPSN1_PSNNO NOT IN ('SP-IEI-2022-02-0590')
                GROUP BY PPSN1_PSNNO,PPSN1_DOCNO,PPSN1_MDLCD";
        $query = $this->db->query($qry);
        return $query->result_array();
    }

    public function select_compare_psnjob_req($pdocno, $pwo)
    {
        // THERE ARE SOME differences between WO Simulation and BOM
        // SO WE NEED TO HANDLE THIS PROBLEM
        // NOTE : ASK PPIC DEPT FOR DETAIL PROBLEM
        if (in_array($pwo, $this->SPECIALWO)) {
            $qry = "SELECT convert(varchar,MBOM_LINE) PIS3_LINENO,'' PIS3_FR,  '' PIS3_PROCD, '' PIS3_MC, '' PIS3_MCZ, PDPP_WORQT*MBOM_PARQT PIS3_REQQTSUM, MBOM_PARQT MYPER, MBOM_BOMPN PIS3_MPART, ISNULL(PDPP_WORQT,SIMQT) PDPP_WORQT, MBOM_BOMPN PIS3_ITMCD
            ,RTRIM(MITM_ITMD1) MITM_ITMD1,rtrim(MITM_SPTNO) MITM_SPTNO,SIMQT,MAX(PDPP_BOMRV) PDPP_BOMRV FROM XMBOM
            inner join XWO ON MBOM_MDLCD=PDPP_MDLCD AND MBOM_BOMRV=PDPP_BOMRV
            LEFT JOIN (SELECT PPSN1_WONO,MAX(PPSN1_SIMQT) SIMQT FROM XPPSN1
                    GROUP BY PPSN1_WONO) VPPSN1 ON PPSN1_WONO=PDPP_WONO
            LEFT JOIN MITM_TBL ON MBOM_BOMPN=MITM_ITMCD
            WHERE PDPP_WONO=?";
            $query = $this->db->query($qry, [$pwo]);
        } else {
            $qry = "select RTRIM(PIS3_LINENO) PIS3_LINENO,RTRIM(PIS3_FR) PIS3_FR,rtrim(UPPER(PIS3_PROCD)) PIS3_PROCD,PIS3_MC,PIS3_MCZ,SUM(PIS3_REQQT) PIS3_REQQTSUM,SUM(PIS3_REQQT)/SIMQT MYPER, RTRIM(PIS3_MPART) PIS3_MPART,ISNULL(PDPP_WORQT,SIMQT) PDPP_WORQT
            ,RTRIM(MITM_ITMD1) MITM_ITMD1,rtrim(MITM_SPTNO) MITM_SPTNO,SIMQT,MAX(PDPP_BOMRV) PDPP_BOMRV from XPIS3
            LEFT JOIN XWO ON PIS3_WONO=PDPP_WONO AND PDPP_BSGRP=PIS3_BSGRP
            LEFT JOIN (SELECT PPSN1_WONO,MAX(PPSN1_SIMQT) SIMQT FROM XPPSN1
            GROUP BY PPSN1_WONO) VPPSN1 ON PPSN1_WONO=PIS3_WONO
            INNER JOIN MITM_TBL ON PIS3_MPART=MITM_ITMCD
            WHERE PIS3_WONO=? and PIS3_DOCNO=?
            GROUP BY PIS3_WONO,PIS3_LINENO,PIS3_MC,PIS3_MCZ,PDPP_WORQT,PDPP_MDLCD,PIS3_FR,PIS3_PROCD,PIS3_MPART,MITM_ITMD1,MITM_SPTNO,SIMQT
            ORDER BY PIS3_MCZ,PIS3_MC,PIS3_PROCD ";
            $query = $this->db->query($qry, [$pwo, $pdocno]);
        }
        return $query->result_array();
    }

    public function select_psnjob_req($pdocno, $pwo)
    {
        // THERE ARE SOME differences between WO Simulation and BOM
        // SO WE NEED TO HANDLE THIS PROBLEM
        // NOTE : ASK PPIC DEPT FOR DETAIL PROBLEM

        if (in_array($pwo, $this->SPECIALWO)) {
            $qry = "SELECT PDPP_MDLCD,PDPP_WONO PIS3_WONO, MBOM_LINE PIS3_LINENO, '' PIS3_FR, '' PIS3_PROCD, '' PIS3_MC, '' PIS3_MCZ, PDPP_WORQT*MBOM_PARQT PIS3_REQQTSUM, MBOM_PARQT MYPER, RTRIM(MBOM_BOMPN) PIS3_ITMCD, RTRIM(MBOM_BOMPN) PIS3_MPART  FROM XMBOM
            inner join XWO ON MBOM_MDLCD=PDPP_MDLCD AND MBOM_BOMRV=PDPP_BOMRV
            WHERE PDPP_WONO=?";
            $query = $this->db->query($qry, [$pwo]);
        } else {
            $qry = "SELECT PPSN1_MDLCD PDPP_MDLCD
                ,RTRIM(PIS3_WONO) PIS3_WONO
                ,RTRIM(PIS3_LINENO) PIS3_LINENO
                ,RTRIM(PIS3_FR) PIS3_FR
                ,UPPER(RTRIM(PIS3_PROCD)) PIS3_PROCD
                ,RTRIM(PIS3_MC) PIS3_MC
                ,RTRIM(PIS3_MCZ) PIS3_MCZ
                ,CASE 
                    WHEN SIMQT != LQT
                        THEN (SUM(PIS3_REQQT) / SIMQT * LQT)
                    ELSE SUM(PIS3_REQQT)
                    END PIS3_REQQTSUM
                ,SUM(PIS3_REQQT) / SIMQT MYPER
                ,max(RTRIM(PIS3_ITMCD)) PIS3_ITMCD
                ,RTRIM(PIS3_MPART) PIS3_MPART
            FROM XPIS3
            LEFT JOIN XWO ON PIS3_WONO = PDPP_WONO and PDPP_BSGRP=PIS3_BSGRP
            LEFT JOIN (
                SELECT PPSN1_WONO
                    ,MAX(PPSN1_SIMQT) SIMQT
                    ,RTRIM(PPSN1_MDLCD) PPSN1_MDLCD
                FROM XPPSN1
                GROUP BY PPSN1_WONO
                    ,PPSN1_MDLCD
                ) VPPSN1 ON PPSN1_WONO = PIS3_WONO
            LEFT JOIN (
                SELECT SER_DOC
                    ,SUM(SER_QTY) LQT
                FROM SER_TBL
                WHERE SER_DOC = ?
                GROUP BY SER_DOC
                ) VSER ON PDPP_WONO = SER_DOC
            WHERE PIS3_WONO = ? and PIS3_DOCNO=?
            GROUP BY PIS3_WONO
                ,PIS3_LINENO
                ,PIS3_MC
                ,PIS3_MCZ
                ,PDPP_WORQT
                ,PPSN1_MDLCD
                ,PIS3_FR
                ,PIS3_PROCD
                ,PIS3_MPART
                ,SIMQT
                ,LQT
            ORDER BY PIS3_MCZ
                ,PIS3_MC
                ,PIS3_PROCD";
            $query = $this->db->query($qry, [$pwo, $pwo, $pdocno]);
        }
        return $query->result_array();
    }
    public function select_psnjob_req_from_CIMS($pwo, $assycode)
    {
        $qry = "SELECT PDPP_MDLCD,PDPP_WONO PIS3_WONO, RTRIM(MBLA_LINENO) PIS3_LINENO, RTRIM(MBLA_FR) PIS3_FR, RTRIM(MBLA_PROCD) PIS3_PROCD, RTRIM(MBLA_MC) PIS3_MC
        , RTRIM(MBLA_MCZ) PIS3_MCZ, PDPP_WORQT*SUM(MBLA_QTY) PIS3_REQQTSUM, SUM(MBLA_QTY) MYPER, MAX(RTRIM(MBLA_SPART)) PIS3_ITMCD, RTRIM(MBLA_ITMCD) PIS3_MPART
                FROM VCIMS_MBLA_TBL
                inner join XWO ON MBLA_MDLCD=PDPP_MDLCD AND MBLA_BOMRV=PDPP_BOMRV
                WHERE PDPP_WONO=? AND MBLA_MDLCD=? and MBLA_LINENO in (select PPSN1_LINENO from XPPSN1 where PDPP_WONO=?)
            GROUP BY PDPP_MDLCD,PDPP_WONO,MBLA_LINENO,MBLA_FR,MBLA_PROCD,MBLA_MC,MBLA_MCZ,MBLA_ITMCD,PDPP_WORQT";
        $query = $this->db->query($qry, [$pwo, $assycode, $pwo]);
        return $query->result_array();
    }
    public function selectRequirementWhenCIMSUpdatesAvailable($pwo, $assycode)
    {
        $qry = "SELECT PDPP_MDLCD,PDPP_WONO PIS3_WONO, RTRIM(MBLA_LINENO) PIS3_LINENO, RTRIM(MBLA_FR) PIS3_FR, RTRIM(MBLA_PROCD) PIS3_PROCD, RTRIM(MBLA_MC) PIS3_MC
        , RTRIM(MBLA_MCZ) PIS3_MCZ, PDPP_WORQT*SUM(MBLA_QTY) PIS3_REQQTSUM, SUM(MBLA_QTY) MYPER, MAX(RTRIM(MBLA_SPART)) PIS3_ITMCD, RTRIM(MBLA_ITMCD) PIS3_MPART
                FROM VCIMS_MBLA_TBL
                inner join XWO ON MBLA_MDLCD=PDPP_MDLCD AND MBLA_BOMRV=PDPP_BOMRV
                inner join (select PPSN1_LINENO,PPSN1_PROCD,PPSN1_FR from XPPSN1 where PPSN1_WONO=?
                group by PPSN1_LINENO,PPSN1_PROCD,PPSN1_FR) v1 on MBLA_LINENO=PPSN1_LINENO and MBLA_PROCD=PPSN1_PROCD and MBLA_FR=PPSN1_FR
                WHERE PDPP_WONO=? AND MBLA_MDLCD=? and MBLA_LINENO in (select PPSN1_LINENO from XPPSN1 where PDPP_WONO=?)
            GROUP BY PDPP_MDLCD,PDPP_WONO,MBLA_LINENO,MBLA_FR,MBLA_PROCD,MBLA_MC,MBLA_MCZ,MBLA_ITMCD,PDPP_WORQT";
        $query = $this->db->query($qry, [$pwo, $pwo, $assycode, $pwo]);
        return $query->result_array();
    }
    public function select_psnjob_req_basepwop($pwo)
    {
        $qry = "select  PPSN1_MDLCD PDPP_MDLCD,RTRIM(PWOP_WONO) PIS3_WONO,'' PIS3_LINENO, '' PIS3_FR
        ,'' PIS3_PROCD, '' PIS3_MC
        ,'' PIS3_MCZ
        ,PWOP_WORQT PIS3_REQQTSUM
        ,PWOP_PER MYPER
        ,max(RTRIM(PWOP_SUBPN)) PIS3_ITMCD,RTRIM(PWOP_BOMPN) PIS3_MPART
		from XPWOP
        LEFT JOIN (SELECT PPSN1_WONO,MAX(PPSN1_SIMQT) SIMQT, RTRIM(PPSN1_MDLCD) PPSN1_MDLCD FROM XPPSN1
        GROUP BY PPSN1_WONO, PPSN1_MDLCD) VPPSN1 ON PPSN1_WONO=PWOP_WONO
        WHERE PWOP_WONO=?
        GROUP BY PWOP_WONO,PWOP_PER,SIMQT,PPSN1_MDLCD,PWOP_WORQT,PWOP_BOMPN";
        $query = $this->db->query($qry, [$pwo]);
        return $query->result_array();
    }
    public function select_psnjob_req_for_simulate($pdocno)
    {
        $qry = "select PIS3_DOCNO SIMNO,PDPP_MDLCD,RTRIM(PIS3_WONO) PIS3_WONO,
        SUM(PIS3_REQQT) PIS3_REQQTSUM
        ,SUM(PIS3_REQQT)/PDPP_WORQT MYPER
        ,CASE WHEN min(RTRIM(PIS3_ITMCD)) =RTRIM(PIS3_MPART) THEN  '' else min(RTRIM(PIS3_ITMCD)) end  PIS3_ITMCD,RTRIM(PIS3_MPART) PIS3_MPART,rtrim(MITM_SPTNO) MITM_SPTNO,PDPP_WORQT*SUM(PIS3_REQQT)/PDPP_WORQT REQQTY, 0 PLOTQTY, 0 PLOTSUBQTY from XPIS3
        INNER JOIN XWO ON PIS3_WONO=PDPP_WONO
		left join XMITM_V on PIS3_MPART=MITM_ITMCD
        WHERE PIS3_DOCNO=?
        GROUP BY PIS3_DOCNO,PIS3_WONO,PDPP_WORQT,PDPP_MDLCD,PIS3_MPART,MITM_SPTNO
	    order by PIS3_WONO,PIS3_MPART";
        $query = $this->db->query($qry, [$pdocno]);
        return $query->result_array();
    }

    public function select_sim_item_stock($pdocno)
    {
        $qry = "select PIS3_MPART ITEMCODE,ISNULL(TSTKQTY,0) TSTKQTY,PIS3_ITMCD ITEMCODESUB,ISNULL(TSTKSUBQTY,0) TSTKSUBQTY from
        (select
                CASE WHEN min(RTRIM(PIS3_ITMCD)) =RTRIM(PIS3_MPART) THEN  '' else min(RTRIM(PIS3_ITMCD)) end  PIS3_ITMCD,RTRIM(PIS3_MPART) PIS3_MPART from XPIS3
                WHERE PIS3_DOCNO IN ($pdocno)
                GROUP BY PIS3_MPART) vg
        LEFT JOIN
        (select ITH_ITMCD,sum(STKQTY) TSTKQTY from (
        select ITH_ITMCD,ITH_WH,CASE WHEN sum(ITH_QTY) <0 THEN 0 ELSE sum(ITH_QTY) END STKQTY from ITH_TBL
        WHERE  ITH_WH IN ('ARWH1','ARWH2','NRWH2')
        group by
        ITH_ITMCD,ITH_WH) vitemstock group by ITH_ITMCD) vitemstockgroup on PIS3_MPART=ITH_ITMCD
		LEFT JOIN
        (select ITH_ITMCD,sum(STKQTY) TSTKSUBQTY from (
        select ITH_ITMCD,ITH_WH,CASE WHEN sum(ITH_QTY) <0 THEN 0 ELSE sum(ITH_QTY) END STKQTY from ITH_TBL
        WHERE  ITH_WH IN ('ARWH1','ARWH2','NRWH2')
        group by
        ITH_ITMCD,ITH_WH) vitemstock group by ITH_ITMCD) vitemstocksubgroup on PIS3_ITMCD=vitemstocksubgroup.ITH_ITMCD";
        $query = $this->db->query($qry);
        return $query->result_array();
    }

    public function select_psnjob_req_peritem($pwo)
    {
        $qry = "SELECT PWOP_WONO, PWOP_BOMPN, SUM(PWOP_WORQT) WORQT, SUM(PWOP_PER) PER FROM XPWOP WHERE PWOP_WONO=?
        GROUP BY PWOP_WONO, PWOP_BOMPN
        ORDER BY PWOP_BOMPN";
        $query = $this->db->query($qry, [$pwo]);
        return $query->result_array();
    }

    public function select_jobper_req($pdocno, $pwo)
    {
        $qry = "select PIS3_WONO,SUM(MYPER) MYPER from
        (select PDPP_MDLCD,PIS3_WONO,PIS3_LINENO,PIS3_FR,PIS3_PROCD,PIS3_MC,PIS3_MCZ,SUM(PIS3_REQQT) PIS3_REQQTSUM,SUM(PIS3_REQQT)/PDPP_WORQT MYPER,max(PIS3_ITMCD) PIS3_ITMCD,PIS3_MPART from XPIS3
                INNER JOIN XWO ON PIS3_WONO=PDPP_WONO
                WHERE PIS3_WONO=? and PIS3_DOCNO=?
                GROUP BY PIS3_WONO,PIS3_LINENO,PIS3_MC,PIS3_MCZ,PDPP_WORQT,PDPP_MDLCD,PIS3_FR,PIS3_PROCD,PIS3_MPART) V1
        GROUP BY PIS3_WONO ";
        $query = $this->db->query($qry, [$pwo, $pdocno]);
        return $query->result_array();
    }

    public function select_jobper_wmscal($pwo, $pdo)
    {
        $qry = "SELECT SERD2_JOB,SUM(SERD2_QTPER) SERD2_QTPER,max(SERD2_SER) SERD2_SER,max(SERD2_FGQTY) SERD2_FGQTY, RTRIM(ISNULL(MAX(SER_RMUSE_COMFG),'-')) SER_RMUSE_COMFG  FROM vserd2_cims SERDA
        left join SER_TBL ON SERD2_SER=SER_ID
        where SERD2_JOB=? and SERD2_SER = (select MAX(SERD2_SER) from SERD2_TBL INNER JOIN DLV_TBL ON SERD2_SER=DLV_SER where SERD2_JOB=SERDA.SERD2_JOB AND DLV_ID=? )
        group by SERD2_JOB";
        $query = $this->db->query($qry, [$pwo, $pdo]);
        return $query->result_array();
    }
    public function select_jobper_wmscal_byseruse($pwo, $pser, $pttluse)
    {
        $qry = "SELECT SERD2_JOB,SUM(SERD2_QTPER) SERD2_QTPER,max(SERD2_SER) SERD2_SER,max(SERD2_FGQTY) SERD2_FGQTY, RTRIM(ISNULL(MAX(SER_RMUSE_COMFG),'-')) SER_RMUSE_COMFG  FROM vserd2_cims SERDA
        left join SER_TBL ON SERD2_SER=SER_ID
        where SERD2_JOB=? and SERD2_SER = ?
        group by SERD2_JOB
        HAVING CONVERT(VARCHAR,SUM(SERD2_QTPER))=?";
        $query = $this->db->query($qry, [$pwo, $pser, $pttluse]);
        return $query->result_array();
    }
    public function select_jobper_wmscal_alike($pwo, $pttluse)
    {
        // $qry = "SELECT SERD2_JOB,SUM(SERD2_QTPER) SERD2_QTPER,SERD2_SER,max(SERD2_FGQTY) SERD2_FGQTY FROM vserd2_cims SERDA
        // where SERD2_JOB=?
        // group by SERD2_JOB, SERD2_SER
        // having SUM(SERD2_QTPER)=?";
        $qry = "SELECT SERD2_JOB,SUM(SERD2_QTPER) SERD2_QTPER,SERD2_SER,max(SERD2_FGQTY) SERD2_FGQTY FROM vserd2_cims SERDA
        where SERD2_JOB=?
        group by SERD2_JOB, SERD2_SER
        HAVING CONVERT(VARCHAR,SUM(SERD2_QTPER)) LIKE ?";
        $query = $this->db->query($qry, [$pwo, "%" . $pttluse . "%"]);
        return $query->result_array();
    }
    public function select_job_wmscal($pwo, $pdo)
    {
        $qry = "SELECT SERDA.*, RTRIM(MITM_ITMD1) MITM_ITMD1, MITM_SPTNO FROM vserd2_cims SERDA LEFT JOIN MITM_TBL ON SERD2_ITMCD=MITM_ITMCD
        where SERD2_JOB=? and SERD2_SER = (select MAX(SERD2_SER) from SERD2_TBL INNER JOIN DLV_TBL ON SERD2_SER=DLV_SER where SERD2_JOB=SERDA.SERD2_JOB AND DLV_ID=? )
        ORDER BY SERD2_MCZ, SERD2_MC, SERD2_PROCD";
        $query = $this->db->query($qry, [$pwo, $pdo]);
        return $query->result_array();
    }
    public function select_job_wmscal_byser($pwo, $pid)
    {
        $qry = "SELECT SERDA.*, RTRIM(MITM_TBL.MITM_ITMD1) MITM_ITMD1, RTRIM(MITM_TBL.MITM_SPTNO) MITM_SPTNO FROM vserd2_cims SERDA LEFT JOIN MITM_TBL ON SERD2_ITMCD=MITM_ITMCD
        where SERD2_JOB=? and SERD2_SER = ?
        ORDER BY SERD2_MCZ, SERD2_MC, SERD2_PROCD";
        $query = $this->db->query($qry, [$pwo, $pid]);
        return $query->result_array();
    }

    public function select_job_wmscalculation_byser($pid)
    {
        $qry = "SELECT V1.*,RTRIM(MITM_SPTNO) MITM_SPTNO, RTRIM(MITM_ITMD1) MITM_ITMD1 FROM
        (SELECT SERD2_LINENO, SERD2_PROCD, SERD2_FR, SERD2_MC,SERD2_MCZ,SERD2_ITMCD,SUM(SERD2_QTY)/MAX(SERD2_FGQTY) SERD2_QTPER, SUM(SERD2_QTY) SERD2_QTY
        FROM SERD2_TBL
        WHERE SERD2_SER=?
        GROUP BY SERD2_LINENO, SERD2_PROCD, SERD2_FR, SERD2_MC,SERD2_MCZ,SERD2_ITMCD) V1
        LEFT JOIN MITM_TBL ON SERD2_ITMCD=MITM_ITMCD
        ORDER BY SERD2_MCZ, SERD2_MC, SERD2_PROCD";
        $query = $this->db->query($qry, [$pid]);
        return $query->result_array();
    }

    public function update_rec_rm($ppsn)
    {
        $qry = "UPDATE SPL_TBL SET SPL_JOBNO_REC_RM ='1' WHERE SPL_DOC in ($ppsn)";
        $this->db->query($qry);
        return $this->db->affected_rows();
    }

    public function deleteby_filter($pwhere)
    {
        $this->db->where($pwhere)->not_like('SPL_DOC', 'PR-', 'after');
        $this->db->delete($this->TABLENAME);
        return $this->db->affected_rows();
    }
    public function delete_partreq_by_filter($pwhere)
    {
        $this->db->where($pwhere);
        $this->db->delete($this->TABLENAME);
        return $this->db->affected_rows();
    }

    public function select_ppsn2($par)
    {
        $this->db->select("PPSN2_DATANO,PPSN2_MC,PPSN2_MCZ,PPSN2_FR,UPPER(RTRIM(PPSN2_SUBPN)) PPSN2_SUBPN,PPSN2_REQQT,PPSN2_PROCD
        ,PPSN2_PACKSZ1,PPSN2_PICKQT1
        ,PPSN2_PACKSZ2,PPSN2_PICKQT2
        ,PPSN2_PACKSZ3,PPSN2_PICKQT3
        ,PPSN2_PACKSZ4,PPSN2_PICKQT4
        ,PPSN2_PACKSZ5,PPSN2_PICKQT5
        ,PPSN2_PACKSZ6,PPSN2_PICKQT6
        ,PPSN2_PACKSZ7,PPSN2_PICKQT7
        ,PPSN2_PACKSZ8,PPSN2_PICKQT8
        ");
        $this->db->from("XPPSN2");
        $this->db->where($par);
        $this->db->order_by('PPSN2_FR ASC,PPSN2_MCZ asc, PPSN2_MC asc, PPSN2_SUBPN ASC, PPSN2_PROCD ASC ');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function select_ppsn2_psno($pPSN)
    {
        $this->db->select("PPSN2_PSNNO");
        $this->db->from("XPPSN2");
        $this->db->where_in("PPSN2_PSNNO", $pPSN);
        $this->db->group_by("PPSN2_PSNNO");
        $query = $this->db->get();
        return $query->result_array();
    }
    public function select_bg_ppsn($pPSN)
    {
        $this->db->select("RTRIM(PPSN1_BSGRP) PPSN1_BSGRP");
        $this->db->from("XPPSN1");
        $this->db->where_in("PPSN1_PSNNO", $pPSN);
        $this->db->group_by("PPSN1_BSGRP");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_ppsn2_forserd($ppsn)
    {
        $this->db->select("UPPER(RTRIM(PPSN2_PSNNO)) PPSN2_PSNNO
        ,UPPER(RTRIM(PPSN2_LINENO)) PPSN2_LINENO
        ,RTRIM(PPSN2_ITMCAT) PPSN2_ITMCAT
        ,RTRIM(PPSN2_DATANO) PPSN2_DATANO
        ,RTRIM(PPSN2_MC) PPSN2_MC
        ,RTRIM(PPSN2_MCZ) PPSN2_MCZ
        ,UPPER(RTRIM(PPSN2_FR)) PPSN2_FR
        ,RTRIM(PPSN2_MSFLG) PPSN2_MSFLG
        ,RTRIM(PPSN2_SUBPN) PPSN2_SUBPN
        ,PPSN2_REQQT
        ,UPPER(RTRIM(PPSN2_PROCD)) PPSN2_PROCD
        ,RTRIM(PPSN2_BSGRP) PPSN2_BSGRP
        ,UPPER(RTRIM(MITM_ITMD1)) MITM_ITMD1");
        $this->db->from("XPPSN2");
        $this->db->join("XMITM_V", "PPSN2_SUBPN=MITM_ITMCD");
        $this->db->where_in("PPSN2_PSNNO", $ppsn);
        $this->db->order_by('PPSN2_PSNNO,PPSN2_LINENO,PPSN2_FR,PPSN2_ITMCAT ASC,PPSN2_MCZ asc, PPSN2_MC asc, PPSN2_SUBPN ASC, PPSN2_PROCD ASC,PPSN2_BSGRP ');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_outstanding($ppsn)
    {
        $qry = "wms_sp_outstanding_psn ?";
        $query = $this->db->query($qry, [$ppsn]);
        return $query->result_array();
    }

    public function select_line_mfg()
    {
        $qry = "exec sp_getline_mfg ''";
        $query = $this->db->query($qry);
        return $query->result_array();
    }

    public function select_but_diff_machine($ppsn, $pcat, $pline, $pfr)
    {
        $qry = "SELECT SPL_DOC, SPL_CAT, SPL_LINE, SPL_FEDR,SPL_ORDERNO,SPL_ITMCD FROM SPL_TBL
        where SPL_DOC = ? and SPL_CAT = ? and SPL_LINE =? and SPL_FEDR = ?
        group by SPL_DOC, SPL_CAT, SPL_LINE, SPL_FEDR,SPL_ORDERNO,SPL_ITMCD
        HAVING COUNT(*)>1";
        $query = $this->db->query($qry, [$ppsn, $pcat, $pline, $pfr]);
        return $query->result_array();
    }
    public function select_but_diff_machine_bypsn($ppsn)
    {
        $qry = "SELECT SPL_DOC, SPL_CAT, SPL_LINE, SPL_FEDR,SPL_ORDERNO,SPL_ITMCD FROM SPL_TBL
        where SPL_DOC = ?
        group by SPL_DOC, SPL_CAT, SPL_LINE, SPL_FEDR,SPL_ORDERNO,SPL_ITMCD
        HAVING COUNT(*)>1";
        $query = $this->db->query($qry, [$ppsn]);
        return $query->result_array();
    }

    public function select_xppsn1_byvar($pwhere)
    {
        $this->db->from("XPPSN1");
        $this->db->where($pwhere);
        $this->db->order_by('PPSN1_WONO ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function select_job_bypsn($psn)
    {
        $qry = "SELECT RTRIM(PPSN1_WONO) PPSN1_WONO, PDPP_WORQT FROM (SELECT PPSN1_WONO FROM XPPSN1 WHERE PPSN1_PSNNO=? GROUP BY PPSN1_WONO) VPSN
                LEFT JOIN XWO ON  PPSN1_WONO=PDPP_WONO";
        $query = $this->db->query($qry, [$psn]);
        return $query->result_array();
    }
    public function select_xppsn2_byvar($pwhere)
    {
        $this->db->select("XPPSN2.*,RTRIM(MITM_ITMD1) MITM_ITMD1,RTRIM(MITM_SPTNO) MITM_SPTNO");
        $this->db->join("MITM_TBL", "PPSN2_SUBPN=MITM_ITMCD");
        $this->db->from("XPPSN2");
        $this->db->where($pwhere);
        $this->db->order_by('PPSN2_MCZ ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_xppsn2_byvar_partin($pwhere, $part, $ppsnno)
    {
        $this->db->select("XPPSN2.*,RTRIM(MITM_ITMD1) MITM_ITMD1,RTRIM(MITM_SPTNO) MITM_SPTNO");
        $this->db->join("MITM_TBL", "PPSN2_SUBPN=MITM_ITMCD");
        $this->db->from("XPPSN2");
        $this->db->where($pwhere)->where_in('PPSN2_SUBPN', $part)->where_in('PPSN2_PSNNO', $ppsnno);
        $this->db->order_by('PPSN2_MCZ ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function select_xppsn2_byvar_($pwhere, $ppsnno)
    {
        $this->db->select("XPPSN2.*,RTRIM(MITM_ITMD1) MITM_ITMD1,RTRIM(MITM_SPTNO) MITM_SPTNO");
        $this->db->join("MITM_TBL", "PPSN2_SUBPN=MITM_ITMCD");
        $this->db->from("XPPSN2");
        $this->db->where($pwhere)->where_in('PPSN2_PSNNO', $ppsnno);
        $this->db->order_by('PPSN2_MCZ ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function select_xppsn2_byvar_like($plike, $ppsnno)
    {
        $this->db->select("XPPSN2.*,RTRIM(MITM_ITMD1) MITM_ITMD1,RTRIM(MITM_SPTNO) MITM_SPTNO");
        $this->db->join("MITM_TBL", "PPSN2_SUBPN=MITM_ITMCD");
        $this->db->from("XPPSN2");
        $this->db->like($plike)->where_in('PPSN2_PSNNO', $ppsnno);
        $this->db->order_by('PPSN2_MCZ ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function select_xppsn2_byvar_like_withException($plike, $ppsnno, $pitemException)
    {
        $this->db->select("XPPSN2.*,RTRIM(MITM_ITMD1) MITM_ITMD1,RTRIM(MITM_SPTNO) MITM_SPTNO");
        $this->db->join("MITM_TBL", "PPSN2_SUBPN=MITM_ITMCD");
        $this->db->from("XPPSN2");
        $this->db->like($plike)->where_in('PPSN2_PSNNO', $ppsnno)->where_not_in("PPSN2_SUBPN", $pitemException);
        $this->db->order_by('PPSN2_MCZ ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_allxppsn2_bypsn($ppsn)
    {
        $this->db->select("XPPSN2.*,RTRIM(MITM_ITMD1) MITM_ITMD1, MITM_SPTNO");
        $this->db->from("XPPSN2");
        $this->db->join("MITM_TBL", "PPSN2_SUBPN=MITM_ITMCD");
        $this->db->where_in('PPSN2_PSNNO', $ppsn)->where("PPSN2_PROCD IS NOT NULL", null, false);
        $this->db->order_by('PPSN2_PSNNO,PPSN2_MCZ,PPSN2_MC,PPSN2_PROCD');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function select_allxppsn2_by_ops_psn($ppsn)
    {
        $this->db->select("XPPSN2.*,RTRIM(MITM_ITMD1) MITM_ITMD1, MITM_SPTNO");
        $this->db->from("XPPSN1");
        $this->db->join("XPPSN2", "PPSN1_PSNNO=PPSN2_PSNNO");
        $this->db->join("MITM_TBL", "PPSN2_SUBPN=MITM_ITMCD");
        $this->db->where_in('PPSN1_OPSNNO', $ppsn);
        $this->db->order_by('PPSN2_PSNNO,PPSN2_MCZ,PPSN2_MC,PPSN2_PROCD');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function select_allxppsn2_bypsn_mcz($ppsn, $pmcz)
    {
        $this->db->from("XPPSN2");
        $this->db->where_in('PPSN2_PSNNO', $ppsn)->where('PPSN2_MCZ', $pmcz);
        $this->db->order_by('PPSN2_MCZ,PPSN2_MC,PPSN2_PROCD');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function select_mcz_xppsn2_bypsn($ppsn)
    {
        $this->db->select("PPSN2_MCZ");
        $this->db->from("XPPSN2");
        $this->db->where_in('PPSN2_PSNNO', $ppsn);
        $this->db->group_by("PPSN2_MCZ");
        $this->db->order_by('PPSN2_MCZ');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_xwo($pcols, $par)
    {
        $this->db->select($pcols);
        $this->db->from("XWO");
        $this->db->where($par);
        $query = $this->db->get();
        return $query->result_array();
    }
    public function select_xwo_delv($pcols, $par)
    {
        $this->db->select($pcols);
        $this->db->from("XWO");
        $this->db->where_in("PDPP_WONO", $par);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_unfinished_calculation($pwo)
    {
        $qry = "select * from
        (select PPSN1_PSNNO from XPPSN1 where PPSN1_WONO in ?
        group by PPSN1_PSNNO) v1 left join
        (select SPLSCN_DOC,max(SPLSCN_LUPDT) LTSSCAN from SPLSCN_TBL
        group by SPLSCN_DOC) v2 on PPSN1_PSNNO=SPLSCN_DOC
        LEFT JOIN
        (SELECT SERD_PSNNO, max(SERD_LUPDT) LTSCAL FROM SERD_TBL
        GROUP BY SERD_PSNNO) v3 on PPSN1_PSNNO=SERD_PSNNO
        WHERE LTSSCAN>LTSCAL";
        $query = $this->db->query($qry, [$pwo]);
        return $query->result_array();
    }

    public function select_bg_not_in_psn()
    {
        $qry = "select RTRIM(MBSG_BSGRP) MBSG_BSGRP,RTRIM(MBSG_DESC) MBSG_DESC from
        (select PIS2_BSGRP from v_sim_not_in_psn
        group by PIS2_BSGRP) vbg
        left join XMBSG_TBL on PIS2_BSGRP=MBSG_BSGRP
        order by MBSG_DESC";
        $query = $this->db->query($qry);
        return $query->result_array();
    }

    public function select_sim_job_not_in_psn($pbg)
    {
        $qry = "select PDPP_WONO,RTRIM(vpis.PIS2_DOCNO) SIMNO,xwo.PDPP_WORQT from
        (select PIS2_WONO,PIS2_BSGRP,PIS2_DOCNO from XPIS2
        group by PIS2_WONO, PIS2_BSGRP,PIS2_DOCNO) vpis
        left join XWO on PIS2_WONO=PDPP_WONO
        right join v_sim_not_in_psn on vpis.PIS2_DOCNO=v_sim_not_in_psn.PIS2_DOCNO
        where vpis.PIS2_BSGRP=? and PDPP_WORQT is not null
        order by vpis.PIS2_DOCNO,PIS2_WONO ";
        $query = $this->db->query($qry, [$pbg]);
        return $query->result_array();
    }
    public function select_sim_only($pbg)
    {
        $qry = "select RTRIM(PIS2_DOCNO) SIMNO from
        v_sim_from_last_month WHERE PIS2_BSGRP = ?
        order by PIS2_DOCNO DESC";
        $query = $this->db->query($qry, [$pbg]);
        return $query->result_array();
    }

    public function select_reffdoc($doc)
    {
        $this->db->select("SPL_REFDOCNO,MAX(SPL_REFDOCCAT) REFDOCCAT");
        $this->db->from("SPL_TBL");
        $this->db->group_by("SPL_REFDOCNO");
        $this->db->where("SPL_DOC", $doc);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectKittingDocWhereReffDoc($doc)
    {
        $this->db->select("SPL_DOC SPL_REFDOCNO,MAX(SPL_REFDOCCAT) REFDOCCAT");
        $this->db->from("SPL_TBL");
        $this->db->group_by("SPL_DOC");
        $this->db->where("SPL_REFDOCNO", $doc);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_partreq_h_bydoc($pdok)
    {
        $qry = "select TOP 200 SPL_DOC,max(SPL_LUPDT) DT, MAX(MSTEMP_FNM) FNM,max(SPL_REFDOCCAT) CTG , MAX(SPL_RMRK) SPL_RMRK
        , MAX(SPL_USRGRP) SPL_USRGRP, MAX(SPL_LINE) SPL_LINE, ISNULL(MAX(SPL_FMDL),'') SPL_FMDL from SPL_TBL
        INNER JOIN MSTEMP_TBL ON SPL_USRID=MSTEMP_ID
        where SPL_DOC LIKE 'PR-%' AND SPL_DOC LIKE ?
        group by SPL_DOC";
        $query = $this->db->query($qry, ['%' . $pdok . '%']);
        return $query->result_array();
    }
    public function select_partreq_h_bypart($pPart)
    {
        $qry = "select TOP 200 SPL_DOC,max(SPL_LUPDT) DT, MAX(MSTEMP_FNM) FNM,max(SPL_REFDOCCAT) CTG , MAX(SPL_RMRK) SPL_RMRK
        , MAX(SPL_USRGRP) SPL_USRGRP, MAX(SPL_LINE) SPL_LINE, ISNULL(MAX(SPL_FMDL),'') SPL_FMDL from SPL_TBL
        INNER JOIN MSTEMP_TBL ON SPL_USRID=MSTEMP_ID
        where SPL_DOC LIKE 'PR-%' AND SPL_ITMCD LIKE ?
        group by SPL_DOC";
        $query = $this->db->query($qry, ['%' . $pPart . '%']);
        return $query->result_array();
    }
    public function select_partreq_d_bydoc($pdok)
    {
        $this->db->from($this->TABLENAME);
        $this->db->order_by("SPL_ITMCD");
        $this->db->where("SPL_DOC", $pdok);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_bg($pitem)
    {
        $this->db->select("PPSN2_BSGRP");
        $this->db->from("XPPSN2");
        $this->db->where_in("PPSN2_SUBPN", $pitem)->where('PPSN2_ACTQT >', 0);
        $this->db->group_by("PPSN2_BSGRP");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selecthead_bypsn($ppsn)
    {
        $this->db->select("PPSN1_WONO,PPSN1_MDLCD,MITM_ITMD1,PPSN1_SIMQT");
        $this->db->from("XPPSN1");
        $this->db->join("XMITM_V", "PPSN1_MDLCD=MITM_ITMCD");
        $this->db->where_in("PPSN1_PSNNO", $ppsn);
        $this->db->group_by("PPSN1_WONO,PPSN1_MDLCD,MITM_ITMD1,PPSN1_SIMQT");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_recap_partreq($pdate1, $pdate2)
    {
        $qry = "select v1.*,SPL_RMRK,SPL_LINE,UPPER(SPL_REFDOCNO) SPL_REFDOCNO,RQSRMRK_DESC,SPL_FMDL from
        (select UPPER(SPLSCN_DOC) SPLSCN_DOC,SPLSCN_ITMCD,SUM(SPLSCN_QTY) SCNQTY,SPLSCN_LOTNO,SPLSCN_DATE from V_SPLSCN_TBLC
        WHERE SPLSCN_DOC LIKE 'PR-%' AND (SPLSCN_DATE >= ? AND SPLSCN_DATE <= ?)
        group by SPLSCN_DOC,SPLSCN_ITMCD,SPLSCN_LOTNO,SPLSCN_DATE) v1 left join
        (select SPL_DOC,SPL_ITMCD,MAX(SPL_RMRK) SPL_RMRK,MAX(SPL_LINE) SPL_LINE, max(SPL_REFDOCNO) SPL_REFDOCNO,ISNULL(max(SPL_FMDL),'') SPL_FMDL from SPL_TBL
        GROUP BY SPL_DOC,SPL_ITMCD) v2 on v1.SPLSCN_DOC=v2.SPL_DOC and v1.SPLSCN_ITMCD=v2.SPL_ITMCD
        LEFT JOIN RQSRMRK_TBL ON ISNULL(SPL_RMRK,'') = RQSRMRK_CD
        ORDER BY SPLSCN_DATE desc, SPLSCN_ITMCD";
        $query = $this->db->query($qry, [$pdate1, $pdate2]);
        return $query->result_array();
    }
    public function select_recap_partreq_business($pdate1, $pdate2, $pbusiness)
    {
        $qry = "select v1.*,SPL_RMRK,SPL_LINE,UPPER(SPL_REFDOCNO) SPL_REFDOCNO,RQSRMRK_DESC,SPL_FMDL from
        (select UPPER(SPLSCN_DOC) SPLSCN_DOC,SPLSCN_ITMCD,SUM(SPLSCN_QTY) SCNQTY,SPLSCN_LOTNO,SPLSCN_DATE from V_SPLSCN_TBLC
        WHERE SPLSCN_DOC LIKE 'PR-%' AND (SPLSCN_DATE >= ? AND SPLSCN_DATE <= ?)
        group by SPLSCN_DOC,SPLSCN_ITMCD,SPLSCN_LOTNO,SPLSCN_DATE) v1 INNER join
        (select SPL_DOC,SPL_ITMCD,MAX(SPL_RMRK) SPL_RMRK,MAX(SPL_LINE) SPL_LINE, max(SPL_REFDOCNO) SPL_REFDOCNO,ISNULL(max(SPL_FMDL),'') SPL_FMDL,MAX(SPL_BG) SPL_BG from SPL_TBL
        GROUP BY SPL_DOC,SPL_ITMCD) v2 on v1.SPLSCN_DOC=v2.SPL_DOC and v1.SPLSCN_ITMCD=v2.SPL_ITMCD AND SPL_BG=?
        LEFT JOIN RQSRMRK_TBL ON ISNULL(SPL_RMRK,'') = RQSRMRK_CD
        ORDER BY SPLSCN_DATE desc, SPLSCN_ITMCD";
        $query = $this->db->query($qry, [$pdate1, $pdate2, $pbusiness]);
        return $query->result_array();
    }

    public function select_all_ppsn2($pcols, $pwhere)
    {
        $this->db->select($pcols);
        $this->db->from("XPPSN2");
        $this->db->join('XMITM_V', "PPSN2_SUBPN=MITM_ITMCD");
        $this->db->where($pwhere);
        $this->db->order_by("PPSN2_MCZ, PPSN2_MC, PPSN2_SUBPN");
        $query = $this->db->get();
        return $query->result_array();
    }
    public function select_where($pcols, $pwhere)
    {
        $this->db->select($pcols);
        $this->db->from($this->TABLENAME);
        $this->db->where($pwhere);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_partreq_status_d($pdoc)
    {
        $qry = "SELECT VREQ.*,ISNULL(SCN,0) SCN,RTRIM(MITM_SPTNO) MITM_SPTNO FROM
        (select SPL_ITMCD,sum(SPL_QTYREQ) REQ from SPL_TBL where SPL_DOC=?
        group by SPL_ITMCD) VREQ
        LEFT JOIN (
        select SPLSCN_ITMCD,sum(SPLSCN_QTY) SCN from SPLSCN_TBL where SPLSCN_DOC=?
        group by SPLSCN_ITMCD) VSCN ON VREQ.SPL_ITMCD=VSCN.SPLSCN_ITMCD
        LEFT JOIN MITM_TBL ON SPL_ITMCD=MITM_ITMCD
        WHERE REQ>ISNULL(SCN,0)
        ORDER BY SPL_ITMCD";
        $query = $this->db->query($qry, [$pdoc, $pdoc]);
        return $query->result_array();
    }

    public function select_bg_partreq($pPSN)
    {
        $this->db->select("RTRIM(SPL_BG) PPSN1_BSGRP");
        $this->db->from($this->TABLENAME);
        $this->db->where_in("SPL_DOC", $pPSN);
        $this->db->group_by("SPL_BG");
        $query = $this->db->get();
        return $query->result_array();
    }
    public function select_bg_psn($pPSN)
    {
        $this->db->select("RTRIM(SPL_BG) SPL_BG,RTRIM(SPL_DOC) SPL_DOC");
        $this->db->from($this->TABLENAME);
        $this->db->where_in("SPL_DOC", $pPSN);
        $this->db->group_by("SPL_BG,SPL_DOC");
        $query = $this->db->get();
        return $query->result_array();
    }
    public function select_bg_item($pitem)
    {
        $this->db->select("RTRIM(SPL_BG) SPL_BG");
        $this->db->from($this->TABLENAME);
        $this->db->where_in("SPL_ITMCD", $pitem);
        $this->db->group_by("SPL_BG");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_machine($pwhere)
    {
        $this->db->select("SPL_MC");
        $this->db->from($this->TABLENAME);
        $this->db->where($pwhere);
        $this->db->group_by("SPL_MC");
        $this->db->order_by("SPL_MC");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_userinfo_group($pcols, $pwhere, $pcolname)
    {
        $this->db->select($pcols);
        $this->db->from($this->TABLENAME . " a");
        $this->db->join('MSTEMP_TBL', $pcolname . "=MSTEMP_ID", 'left');
        $this->db->where($pwhere);
        $this->db->group_by($pcols);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_per_category($ppsn, $pcategories)
    {
        $this->db->select("SPL_DOC,SPL_ITMCD,SUM(SPL_QTYREQ) RQT");
        $this->db->from($this->TABLENAME);
        $this->db->where_in("SPL_DOC", $ppsn)->where_in("SPL_CAT", $pcategories);
        $this->db->group_by("SPL_DOC,SPL_ITMCD");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_booked_spl_diff($ppsn)
    {
        $qry = "wms_sp_booked_spl_diff ?";
        $query = $this->db->query($qry, [$ppsn]);
        return $query->result_array();
    }

    public function select_check_PSN_by_job($pjob)
    {
        $this->db->select("PPSN1_PSNNO");
        $this->db->from("XPPSN1");
        $this->db->like("PPSN1_WONO", $pjob);
        $this->db->group_by("PPSN1_PSNNO");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_ppsn2_byArrayOf_WO_and_part($pWO, $pPart)
    {
        $qry = "SELECT rtrim(PPSN2_PSNNO) PSN, UPPER(RTRIM(PPSN2_SUBPN)) SUBPN,(SUM(PPSN2_ACTQT)-SUM(PPSN2_REQQT)) LOGRTNQT  FROM XPPSN2 WHERE
        PPSN2_PSNNO IN (
        SELECT PPSN1_PSNNO FROM XPPSN1 WHERE PPSN1_WONO IN ($pWO)
        ) AND PPSN2_SUBPN IN ($pPart)
        GROUP BY PPSN2_PSNNO,PPSN2_SUBPN";
        $query = $this->db->query($qry);
        return $query->result_array();
    }

    public function select_wo_byArrayOf_WO($pWO)
    {
        $qry = "SELECT RTRIM(PPSN1_PSNNO) PSN,RTRIM(PPSN1_WONO) WONO FROM XPPSN1 WHERE PPSN1_WONO IN ($pWO)
        GROUP BY PPSN1_PSNNO,PPSN1_WONO";
        $query = $this->db->query($qry);
        return $query->result_array();
    }

    public function select_ppsn2_xwo($wo)
    {
        # returned column
        # PPSN1_PSNNO    PPSN1_WONO    PPSN2_ITMCAT    PPSN2_SUBPN    PPSN2_REQQT    PPSN2_ACTQT    PDPP_WORQT
        $qry = "sp_ppsn2_xwo ?";
        $query = $this->db->query($qry, [$wo]);
        return $query->result_array();
    }
}
