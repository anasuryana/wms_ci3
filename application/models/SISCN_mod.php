<?php

class SISCN_mod extends CI_Model
{
    private $TABLENAME = "SISCN_TBL";
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

    public function updatebyId($pdata, $pkey)
    {
        $this->db->where($pkey);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
    }
    public function updatebyId_serin($pdata, $pkey, $pser)
    {
        $this->db->where($pkey)->where_in('SISCN_SER', $pser);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
    }

    public function selectAll_by($pwhere)
    {
        $this->db->from($this->TABLENAME . " a");
        $this->db->join("SER_TBL b", "a.SISCN_SER=b.SER_ID");
        $this->db->where($pwhere);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectColumnsWhere($pcolumns, $pwhere)
    {
        $this->db->select($pcolumns);
        $this->db->from($this->TABLENAME);
        $this->db->join("DLV_TBL", "SISCN_SER=DLV_SER", "LEFT");
        $this->db->where($pwhere);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_trace($pwhere)
    {
        $this->db->select("SISCN_DOC,SISCN_SER,SISCN_SERQTY,ISNULL(SISCN_PLLT,'-') SISCN_PLLT,SISCN_LUPDT,CONCAT(MSTEMP_FNM,' ',MSTEMP_LNM) PIC");
        $this->db->from($this->TABLENAME . " a");
        $this->db->join("MSTEMP_TBL b", "a.SISCN_USRID=b.MSTEMP_ID", "left");
        $this->db->where($pwhere);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectAll_byserin($pwhere, $pser)
    {
        $this->db->from($this->TABLENAME . " a");
        $this->db->join("SER_TBL b", "a.SISCN_SER=b.SER_ID");
        $this->db->where($pwhere)->where_in('SISCN_SER', $pser);
        $query = $this->db->get();
        return $query->result_array();
    }
    public function selectAllg_by($psi)
    {
        $this->db->select("SER_ITMID,sum(SISCN_SERQTY) TTLQTY,COUNT(*) REMARK,SI_OTHRMRK");
        $this->db->from($this->TABLENAME . " a");
        $this->db->join("SER_TBL b", "a.SISCN_SER=b.SER_ID");
        $this->db->join("SI_TBL c", "a.SISCN_LINENO=c.SI_LINENO");
        $this->db->where('SISCN_DOC', $psi);
        $this->db->group_by('SER_ITMID,SI_OTHRMRK');
        $this->db->order_by('SI_OTHRMRK ASC,SER_ITMID asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_serah_terima($psi)
    {
        $qry = "SELECT V1.*,TTLQTY,SCAN_DATE,MBSG_DESC,MCUS_CUSNM,SI_HRMRK,RTRIM(MITM_ITMD1) MITM_ITMD1  FROM
    (SELECT SI_ITMCD,SISCN_SERQTY,COUNT(*) TTLBOX, MIN(CONVERT(DATE,SISCN_LUPDT)) SCAN_DATE,MAX(SI_BSGRP) BSGRP, MAX(SI_CUSCD) MCUS, MAX(SI_HRMRK) SI_HRMRK,isnull(RTRIM(SER_GRADE),'') MBOM_GRADE, max(SER_RMRK) SER_RMRK
    FROM SISCN_TBL
    LEFT JOIN SI_TBL ON SISCN_LINENO=SI_LINENO
    LEFT JOIN SER_TBL ON SISCN_SER=SER_ID
    WHERE SISCN_DOC=?
   GROUP BY SI_ITMCD,SISCN_SERQTY,isnull(RTRIM(SER_GRADE),'')) V1
   LEFT JOIN
     (SELECT SI_ITMCD,SUM(SISCN_SERQTY) TTLQTY,isnull(SER_GRADE,'') MBOM_GRADE FROM SISCN_TBL
    LEFT JOIN SI_TBL ON SISCN_LINENO=SI_LINENO
    LEFT JOIN SER_TBL ON SISCN_SER=SER_ID
    WHERE SISCN_DOC=?
    GROUP BY SI_ITMCD,isnull(SER_GRADE,'')) V2 ON V1.SI_ITMCD=V2.SI_ITMCD and isnull(v1.MBOM_GRADE,'')=isnull(V2.MBOM_GRADE,'')
    LEFT JOIN XMBSG_TBL ON BSGRP=MBSG_BSGRP
    LEFT JOIN MCUS_TBL ON MCUS=MCUS_CUSCD
    LEFT JOIN MITM_TBL ON V1.SI_ITMCD=MITM_ITMCD
    ORDER BY V1.SI_ITMCD,MBOM_GRADE,SISCN_SERQTY DESC";
        $query = $this->db->query($qry, [$psi, $psi]);
        return $query->result_array();
    }

    public function select_serah_terima_rtn($psi)
    {
        $qry = "SELECT VDET.*,TTLQTY,MCUS_CUSNM,MBSG_DESC,RTRIM(MITM_ITMD1) MITM_ITMD1,isnull(RCV_RPNO,'') RCV_RPNO,ISNULL(RCV_BCNO,'') RCV_BCNO,PERBOX,BOX,SI_WH  FROM
        (SELECT SI_ITMCD, ITH_DOC SER_DOC,sum(INTQTY) INTQTY, MIN(CONVERT(DATE,SISCN_LUPDT)) SCAN_DATE
        , MAX(SI_BSGRP) SI_BSGRP, MAX(SI_CUSCD) SI_CUSCD,max(SISCN_SER) AX_SER,MAX(SI_HRMRK) RETFG_PLANT,SISCN_SERQTY,min(OLDITEM) OLDITEM
        FROM SI_TBL INNER JOIN SISCN_TBL ON SI_LINENO=SISCN_LINENO
        LEFT JOIN (SELECT ITH_REMARK EXTLBL, ABS(sum(ITH_QTY)) INTQTY, ITH_DOC,min(ITH_ITMCD) OLDITEM FROM ITH_TBL WHERE ITH_QTY<0 AND ITH_WH in ('AFQART','AFQART2') group by ITH_REMARK, ITH_DOC) VMAP ON SISCN_SER=EXTLBL
        WHERE SI_DOC=?
        GROUP BY SI_ITMCD, ITH_DOC,SISCN_SERQTY) VDET
        LEFT JOIN
        (
        SELECT SI_ITMCD,SUM(SISCN_SERQTY) TTLQTY,MAX(SI_WH) SI_WH FROM SI_TBL INNER JOIN SISCN_TBL
        ON SI_LINENO=SISCN_LINENO
        WHERE SISCN_DOC=?
        GROUP BY SI_ITMCD ) VTTLQTY ON VDET.SI_ITMCD=VTTLQTY.SI_ITMCD
		LEFT JOIN
        (
        SELECT SI_ITMCD,SISCN_SERQTY PERBOX ,count(*) BOX FROM SI_TBL INNER JOIN SISCN_TBL
        ON SI_LINENO=SISCN_LINENO
        WHERE SISCN_DOC=?
        GROUP BY SI_ITMCD ,SISCN_SERQTY) VTTbox ON VDET.SI_ITMCD=VTTbox.SI_ITMCD and vdet.SISCN_SERQTY=VTTbox.PERBOX
        LEFT JOIN XMBSG_TBL ON SI_BSGRP=MBSG_BSGRP
        LEFT JOIN MCUS_TBL ON SI_CUSCD=MCUS_CUSCD
        LEFT JOIN (SELECT RETFG_DOCNO FROM RETFG_TBL GROUP BY RETFG_DOCNO) VRCV ON VDET.SER_DOC=RETFG_DOCNO
        LEFT JOIN MITM_TBL ON VTTLQTY.SI_ITMCD=MITM_ITMCD
        left join (SELECT RCV_INVNO, RCV_RPNO,RCV_BCNO FROM RCV_TBL GROUP BY RCV_INVNO,RCV_RPNO, RCV_BCNO) VRCVCUST ON SER_DOC=RCV_INVNO
        order by SI_ITMCD,SISCN_SERQTY,PERBOX DESC"; #order by SI_ITMCD,AX_SER, PERBOX DESC
        $query = $this->db->query($qry, [$psi, $psi, $psi]);
        return $query->result_array();
    }

    public function select_exbc_fgrtn($ptxid)
    {
        $qry = "SELECT VDET.*
                ,RTRIM(MITM_ITMD1) MITM_ITMD1
                ,RTRIM(MITM_SPTNO) MITM_SPTNO
                ,RTRIM(MITM_STKUOM) MITM_STKUOM
                ,MITM_NWG* INTQTY NWG
                ,isnull(RCV_RPNO, '') RCV_RPNO
                ,ISNULL(RCV_BCNO, '') RCV_BCNO
                ,ISNULL(RCV_KPPBC, '') RCV_KPPBC
                ,ISNULL(RCV_BCTYPE, '') RCV_BCTYPE
                ,ISNULL(RCV_ZNOURUT, '0') RCV_ZNOURUT
                ,ISNULL(RCV_PRPRC, 0) RCV_PRPRC
                ,ISNULL(RCV_HSCD, 0) RCV_HSCD
                ,ISNULL(RCV_BM, 0) RCV_BM
                ,RCV_RPDATE
                ,PERBOX
                ,BOX
            FROM (
                SELECT SI_ITMCD
                    ,ITH_DOC SER_DOC
                    ,sum(INTQTY) INTQTY
                    ,MIN(CONVERT(DATE, SISCN_LUPDT)) SCAN_DATE
                    ,max(SISCN_SER) AX_SER
                    ,MAX(SI_HRMRK) RETFG_PLANT
                    ,SISCN_SERQTY
                    ,min(OLDITEM) OLDITEM
                FROM SI_TBL
                INNER JOIN SISCN_TBL ON SI_LINENO = SISCN_LINENO
                LEFT JOIN (
                    SELECT ITH_REMARK EXTLBL
                        ,ABS(sum(ITH_QTY)) INTQTY
                        ,ITH_DOC
                        ,min(ITH_ITMCD) OLDITEM
                    FROM ITH_TBL
                    WHERE ITH_QTY < 0 AND ITH_WH in ('AFQART','AFQART2')
                    GROUP BY ITH_REMARK
                        ,ITH_DOC
                    ) VMAP ON SISCN_SER = EXTLBL
                WHERE SISCN_SER IN (SELECT DLV_SER FROM DLV_TBL WHERE DLV_ID=?)
                GROUP BY SI_ITMCD
                    ,ITH_DOC
                    ,SISCN_SERQTY
                ) VDET
            LEFT JOIN (
                SELECT SI_ITMCD
                    ,SISCN_SERQTY PERBOX
                    ,count(*) BOX
                FROM SI_TBL
                INNER JOIN SISCN_TBL ON SI_LINENO = SISCN_LINENO
                WHERE SISCN_SER IN (SELECT DLV_SER FROM DLV_TBL WHERE DLV_ID=?)
                GROUP BY SI_ITMCD
                    ,SISCN_SERQTY
                ) VTTbox ON VDET.SI_ITMCD = VTTbox.SI_ITMCD
                AND vdet.SISCN_SERQTY = VTTbox.PERBOX
            LEFT JOIN (
                SELECT RETFG_DOCNO
                FROM RETFG_TBL
                GROUP BY RETFG_DOCNO
                ) VRCV ON VDET.SER_DOC = RETFG_DOCNO
            LEFT JOIN MITM_TBL ON VDET.SI_ITMCD = MITM_ITMCD
            LEFT JOIN (
                SELECT RCV_INVNO
                    ,RCV_RPNO
                    ,RCV_BCNO
                    ,RCV_KPPBC
                    ,RCV_BCTYPE
                    ,RCV_RPDATE
                    ,isnull(MAX(RCV_ZNOURUT),0) RCV_ZNOURUT
                    ,MAX(RCV_PRPRC) RCV_PRPRC
                    ,RCV_ITMCD
                    ,MAX(RCV_HSCD) RCV_HSCD
                    ,MAX(RCV_BM) RCV_BM
                FROM RCV_TBL
                GROUP BY RCV_INVNO
                    ,RCV_RPNO
                    ,RCV_BCNO
                    ,RCV_KPPBC
                    ,RCV_BCTYPE
                    ,RCV_RPDATE
                    ,RCV_ITMCD
                ) VRCVCUST ON SER_DOC = RCV_INVNO AND OLDITEM=RCV_ITMCD
            ORDER BY SI_ITMCD
                ,SISCN_SERQTY
                ,PERBOX DESC
            ";
        $query = $this->db->query($qry, [$ptxid, $ptxid]);
        return $query->result_array();
    }

    public function select_serah_terima_asp($psi)
    {
        $qry = "SELECT V1.*,SCAN_DATE,MBSG_DESC,MCUS_CUSNM,SI_HRMRK,RTRIM(MITM_ITMD1) MITM_ITMD1,ISNULL(RTRIM(MBOM_GRADE),'') MBOM_GRADE  FROM
        (SELECT SI_ITMCD, 1 SISCN_SERQTY, SUM(SISCN_SERQTY) TTLBOX, MIN(CONVERT(DATE,SISCN_LUPDT)) SCAN_DATE,MAX(SI_BSGRP) BSGRP, MAX(SI_CUSCD) MCUS, MAX(SI_HRMRK) SI_HRMRK,isnull(MBOM_GRADE,'') MBOM_GRADE, SUM(SISCN_SERQTY) TTLQTY, max(SER_RMRK) SER_RMRK  FROM SISCN_TBL
        LEFT JOIN SI_TBL ON SISCN_LINENO=SI_LINENO
		LEFT JOIN SER_TBL ON SISCN_SER=SER_ID
		LEFT JOIN XWO ON SER_DOC=PDPP_WONO
        WHERE SISCN_DOC=?
        GROUP BY SI_ITMCD,isnull(MBOM_GRADE,'')) V1
        LEFT JOIN XMBSG_TBL ON BSGRP=MBSG_BSGRP
        LEFT JOIN MCUS_TBL ON MCUS=MCUS_CUSCD
        LEFT JOIN MITM_TBL ON V1.SI_ITMCD=MITM_ITMCD
        ORDER BY V1.SI_ITMCD DESC";
        $query = $this->db->query($qry, [$psi]);
        return $query->result_array();
    }

    public function selectAll_for_delivery_by_si($psi, $cust, $pbg, $pstrloc)
    {
        $qry = "select SISCN_SER,SER_ITMID,MITM_ITMD1,SI_MDL, SI_DOC,SI_DOCREFF,SI_DOCREFFDT,SISCN_SERQTY,SI_DOCREFFDT,isnull(SISOPRC,0) SI_DOCREFFPRC,SER_DOC,ISNULL(SI_OTHRMRK,'') SI_OTHRMRK
        ,SISO_SOLINE,ISNULL(MITM_BOXTYPE,'') MITM_BOXTYPE from $this->TABLENAME  a
        INNER JOIN SER_TBL b on a.SISCN_SER=b.SER_ID
        INNER JOIN MITM_TBL d on b.SER_ITMID=d.MITM_ITMCD
        LEFT JOIN SI_TBL c ON a.SISCN_LINENO=SI_LINENO
        LEFT JOIN VSISO_PRICE  ON SI_LINENO=SISO_HLINE
        LEFT JOIN DLV_TBL ON SER_ID=DLV_SER
        WHERE SISCN_DOC LIKE ? AND SI_CUSCD=? AND  DLV_SER IS NULL
        and SI_BSGRP =?
        and isnull(SI_OTHRMRK,'') like ? AND SISCN_PLLT IS NOT NULL
        ORDER BY SISCN_DOC ASC, SISCN_DOCREFF ASC, SER_ITMID ASC ";
        $query = $this->db->query($qry, ['%' . $psi . '%', $cust, $pbg, '%' . $pstrloc . '%']);
        return $query->result_array();
    }
    public function selectAll_for_delivery_by_itemcode($pitem, $cust, $pbg, $pstrloc)
    {
        $qry = "select SISCN_SER,SER_ITMID,MITM_ITMD1,SI_MDL,SI_DOC,SI_DOCREFF, SI_DOCREFFDT,SISCN_SERQTY,SI_DOCREFFDT,ISNULL(SISOPRC,0) SI_DOCREFFPRC,SER_DOC,ISNULL(SI_OTHRMRK,'') SI_OTHRMRK
        ,SISO_SOLINE,MITM_BOXTYPE from $this->TABLENAME  a
        INNER JOIN SER_TBL b on a.SISCN_SER=b.SER_ID
        INNER JOIN MITM_TBL d on b.SER_ITMID=d.MITM_ITMCD
        LEFT JOIN SI_TBL c ON a.SISCN_LINENO=SI_LINENO
        LEFT JOIN VSISO_PRICE  ON SI_LINENO=SISO_HLINE
        LEFT JOIN DLV_TBL ON SER_ID=DLV_SER
        WHERE SER_ITMID LIKE ? AND SI_CUSCD=? AND  DLV_SER IS NULL and SI_BSGRP = ?
        and isnull(SI_OTHRMRK,'') like ?
        ORDER BY SISCN_DOC ASC, SISCN_DOCREFF ASC, SER_ITMID ASC ";
        $query = $this->db->query($qry, ['%' . $pitem . '%', $cust, $pbg, '%' . $pstrloc . '%']);
        return $query->result_array();
    }

    public function selectAll_for_delivery_by_itemname($pitem, $cust, $pbg, $pstrloc)
    {
        $qry = "select SISCN_SER,SER_ITMID,MITM_ITMD1,SI_MDL,SI_DOC,SI_DOCREFF, SI_DOCREFFDT,SISCN_SERQTY,SI_DOCREFFDT,ISNULL(SISOPRC,0) SI_DOCREFFPRC,SER_DOC,ISNULL(SI_OTHRMRK,'') SI_OTHRMRK
        ,SISO_SOLINE,MITM_BOXTYPE from $this->TABLENAME  a
        INNER JOIN SER_TBL b on a.SISCN_SER=b.SER_ID
        INNER JOIN MITM_TBL d on b.SER_ITMID=d.MITM_ITMCD
        LEFT JOIN SI_TBL c ON a.SISCN_LINENO=SI_LINENO
        LEFT JOIN VSISO_PRICE  ON SI_LINENO=SISO_HLINE
        LEFT JOIN DLV_TBL ON SER_ID=DLV_SER
        WHERE MITM_ITMD1 LIKE ? AND SI_CUSCD=? AND  DLV_SER IS NULL AND SI_BSGRP = ?
        and isnull(SI_OTHRMRK,'') like ?
        ORDER BY SISCN_DOC ASC, SISCN_DOCREFF ASC, SER_ITMID ASC ";
        $query = $this->db->query($qry, ['%' . $pitem . '%', $cust, $pbg, '%' . $pstrloc . '%']);
        return $query->result_array();
    }

    public function select_groupbyline($pline)
    {
        $qry = "SELECT SISCN_LINENO,SUM(SISCN_SERQTY) SCNQTY FROM SISCN_TBL WHERE SISCN_LINENO = ? GROUP BY SISCN_LINENO";
        $query = $this->db->query($qry, array($pline));
        return $query->result_array();
    }

    public function lastserialid()
    {
        $qry = "select TOP 1 coalesce(SISCN_PLLT,'0') lser from SISCN_TBL
        WHERE convert(date, SISCN_LUPDT) = convert(date,GETDATE())
        ORDER BY convert(bigint,coalesce(SISCN_PLLT,'0')) desc";
        $query = $this->db->query($qry);
        if ($query->num_rows() > 0) {
            $ret = $query->row();
            return $ret->lser;
        } else {
            return '0';
        }
    }

    public function deleteby_filter($pwhere)
    {
        $this->db->where($pwhere);
        $this->db->delete($this->TABLENAME);
        return $this->db->affected_rows();
    }

    public function selectso_byser($pid)
    {
        $qry = "SELECT V1.*,V2.*,SI_ITMCD,SI_BSGRP,SI_CUSCD FROM
        (select SISCN_LINENO,sum(SISCN_SERQTY) SCNQTY
        from SISCN_TBL where SISCN_SER in ($pid)
        group by SISCN_LINENO) V1
        LEFT JOIN
        (SELECT SISO_HLINE,SSO2_SLPRC,SUM(SISO_QTY) PLOTQTY,MAX(SISO_SOLINE) SISO_SOLINE FROM SISO_TBL
        LEFT JOIN XSSO2 ON SISO_CPONO=SSO2_CPONO AND SSO2_SOLNO=SISO_SOLINE
        WHERE SISO_QTY>0
        GROUP BY SISO_HLINE,SSO2_SLPRC,SSO2_MDLCD) V2 ON SISCN_LINENO=SISO_HLINE
		LEFT JOIN SI_TBL ON SISCN_LINENO=SI_LINENO";
        $query = $this->db->query($qry);
        return $query->result_array();
    }

    public function selectso_bydo($pDO)
    {
        $qry = "SELECT V1.*,V2.*,SI_ITMCD,SI_BSGRP,SI_CUSCD FROM
        (select SISCN_LINENO,sum(SISCN_SERQTY) SCNQTY
        from SISCN_TBL INNER JOIN DLV_TBL ON SISCN_SER=DLV_SER where DLV_ID = ?
        group by SISCN_LINENO) V1
        LEFT JOIN
        (SELECT SISO_HLINE,SSO2_SLPRC,SUM(SISO_QTY) PLOTQTY,MAX(SISO_SOLINE) SISO_SOLINE FROM SISO_TBL
        LEFT JOIN XSSO2 ON SISO_CPONO=SSO2_CPONO AND SSO2_SOLNO=SISO_SOLINE
        WHERE SISO_QTY>0
        GROUP BY SISO_HLINE,SSO2_SLPRC,SSO2_MDLCD) V2 ON SISCN_LINENO=SISO_HLINE
		LEFT JOIN SI_TBL ON SISCN_LINENO=SI_LINENO";
        $query = $this->db->query($qry, [$pDO]);
        return $query->result_array();
    }

    public function select_forsetPrice($pdoc)
    {
        $qry = "SELECT V1.*,V2.*,UPPER(SI_ITMCD) SI_ITMCD,SI_BSGRP,SI_CUSCD FROM
        (select SISCN_LINENO,sum(SISCN_SERQTY) SCNQTY ,SISCN_SER
        from SISCN_TBL where SISCN_SER in  (SELECT DLV_SER FROM DLV_TBL WHERE DLV_ID=?)
        group by SISCN_LINENO,SISCN_SER) V1
        LEFT JOIN
        (SELECT SISO_HLINE,SSO2_SLPRC,SUM(SISO_QTY) PLOTQTY,MAX(SISO_SOLINE) SISO_SOLINE,max(SISO_CPONO) CPO FROM SISO_TBL
        LEFT JOIN XSSO2 ON SISO_CPONO=SSO2_CPONO AND SSO2_SOLNO=SISO_SOLINE
        WHERE SISO_QTY>0
        GROUP BY SISO_HLINE,SSO2_SLPRC,SSO2_MDLCD) V2 ON SISCN_LINENO=SISO_HLINE
		LEFT JOIN SI_TBL ON SISCN_LINENO=SI_LINENO";
        $query = $this->db->query($qry, [$pdoc]);
        return $query->result_array();
    }
}
