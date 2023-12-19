<?php

class ITH_mod extends CI_Model
{
    private $TABLENAME = "ITH_TBL";
    private $TABLENAME_BIN = "ITH_BIN";
    public function __construct()
    {
        $this->load->database();
    }
    public function selectAll($pid)
    {
        $this->db->from('vstock a');
        $this->db->select("ITH_ITMCD,MITM_SPTNO,MITM_ITMD1,MITM_STKUOM, MITM_MODEL,ttl");
        $this->db->join('MITM_TBL b', "a.ITH_ITMCD=b.MITM_ITMCD");
        $this->db->like('ITH_ITMCD', $pid);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_minus_stock()
    {
        $this->db->from('VMINUS_STOCK');
        $this->db->order_by('ITH_WH,ITH_ITMCD');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectbin_history($plike)
    {
        $this->db->from($this->TABLENAME_BIN . " A");
        $this->db->select("A.*,CONCAT(MSTEMP_FNM, ' ', MSTEMP_LNM) PIC");
        $this->db->join("MSTEMP_TBL", "ITH_USRBIN=MSTEMP_ID");
        $this->db->like($plike);
        $this->db->order_by("ITH_LUPDT");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectAll_by($pwhere)
    {
        $this->db->select("a.*,b.*,CONCAT(MSTEMP_FNM , ' ', MSTEMP_LNM) PIC");
        $this->db->from($this->TABLENAME . ' a');
        $this->db->join('MITM_TBL b', 'a.ITH_ITMCD=b.MITM_ITMCD');
        $this->db->join('MSTEMP_TBL c', 'ITH_USRID=MSTEMP_ID', 'left');
        $this->db->where($pwhere);
        $this->db->order_by('ITH_LUPDT ASC,ITH_FORM DESC,ITH_WH, ITH_QTY ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function select_view_all_by($pwhere)
    {
        $this->db->select("a.*,b.*,CONCAT(MSTEMP_FNM , ' ', MSTEMP_LNM) PIC");
        $this->db->from('v_ith_tblc a');
        $this->db->join('MITM_TBL b', 'a.ITH_ITMCD=b.MITM_ITMCD');
        $this->db->join('MSTEMP_TBL c', 'ITH_USRID=MSTEMP_ID', 'left');
        $this->db->where($pwhere);
        $this->db->order_by('ITH_LUPDT ASC,ITH_FORM DESC,ITH_WH, ITH_QTY ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function selectViewAllWhereLikeWHIn($pwhere, $plike, $pWarehouse)
    {
        $this->db->select("a.*,b.*,CONCAT(MSTEMP_FNM , ' ', MSTEMP_LNM) PIC");
        $this->db->from('v_ith_tblc a');
        $this->db->join('MITM_TBL b', 'a.ITH_ITMCD=b.MITM_ITMCD');
        $this->db->join('MSTEMP_TBL c', 'ITH_USRID=MSTEMP_ID', 'left');
        $this->db->where_in("ITH_WH", $pWarehouse);
        $this->db->where($pwhere);
        $this->db->like($plike);
        $this->db->order_by('ITH_LUPDT ASC,ITH_FORM DESC,ITH_WH, ITH_QTY ASC');
        $query = $this->db->get();
        return $query->result_array();
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
    public function insertb_bin($data)
    {
        $this->db->insert_batch($this->TABLENAME_BIN, $data);
        return $this->db->affected_rows();
    }

    public function insert_incdo($data)
    {
        $qry = "INSERT INTO ITH_TBL (ITH_ITMCD, ITH_DATE, ITH_FORM, ITH_DOC,ITH_QTY,
        ITH_WH,ITH_LUPDT,ITH_USRID)
        VALUES(?,?,?,?,?,?,GETDATE(),?)";
        $this->db->query($qry, [
            $data['ITH_ITMCD'], $data['ITH_DATE'], $data['ITH_FORM'], $data['ITH_DOC'], $data['ITH_QTY'],
            $data['ITH_WH'], $data['ITH_USRID'],
        ]);
        return $this->db->affected_rows();
    }

    public function insert_spl($data)
    {
        $qry = "INSERT INTO ITH_TBL (ITH_ITMCD, ITH_DATE, ITH_FORM, ITH_DOC,ITH_QTY,
        ITH_WH,ITH_LUPDT,ITH_USRID,ITH_REMARK)
        VALUES(?,CONVERT(DATE,GETDATE()),?,?,?,?,GETDATE(),?,?)";
        $this->db->query($qry, [
            $data['ITH_ITMCD'], $data['ITH_FORM'], $data['ITH_DOC'], $data['ITH_QTY'],
            $data['ITH_WH'], $data['ITH_USRID'], $data['ITH_REMARK'],
        ]);
        return $this->db->affected_rows();
    }
    public function insert_ret($data)
    {
        $qry = "INSERT INTO ITH_TBL (ITH_ITMCD, ITH_DATE, ITH_FORM, ITH_DOC,ITH_QTY,
        ITH_WH,ITH_LUPDT,ITH_USRID,ITH_REMARK)
        VALUES(?,?,?,?,?,?,?,?,?)";
        $this->db->query($qry, array(
            $data['ITH_ITMCD'], $data['ITH_DATE'], $data['ITH_FORM'], $data['ITH_DOC'], $data['ITH_QTY'],
            $data['ITH_WH'], $data['ITH_LUPDT'], $data['ITH_USRID'], $data['ITH_REMARK'],
        ));
        return $this->db->affected_rows();
    }

    public function insert_pending($data)
    {
        $qry = "INSERT INTO ITH_TBL (ITH_ITMCD, ITH_DATE, ITH_FORM, ITH_DOC,ITH_QTY,
        ITH_WH,ITH_LUPDT,ITH_USRID)
        VALUES(?,?,?,?,?,?,GETDATE(),?)";
        $this->db->query($qry, array(
            $data['ITH_ITMCD'], $data['ITH_DATE'], $data['ITH_FORM'], $data['ITH_DOC'], $data['ITH_QTY'],
            $data['ITH_WH'], $data['ITH_USRID'],
        ));
        return $this->db->affected_rows();
    }
    public function insert_rls($data)
    {
        $qry = "INSERT INTO ITH_TBL (ITH_ITMCD, ITH_DATE, ITH_FORM, ITH_DOC,ITH_QTY,
        ITH_WH,ITH_SER,ITH_LUPDT,ITH_USRID)
        VALUES(?,?,?,?,?,?,?,GETDATE(),?)";
        $this->db->query($qry, array(
            $data['ITH_ITMCD'], $data['ITH_DATE'], $data['ITH_FORM'], $data['ITH_DOC'], $data['ITH_QTY'],
            $data['ITH_WH'], $data['ITH_SER'], $data['ITH_USRID'],
        ));
        return $this->db->affected_rows();
    }
    public function insert_disposerm($data)
    {
        $qry = "INSERT INTO ITH_TBL (ITH_ITMCD, ITH_DATE, ITH_FORM, ITH_DOC,ITH_QTY,
        ITH_WH,ITH_LUPDT,ITH_USRID)
        VALUES(?,?,?,?,?,?,getdate(),?)";
        $this->db->query($qry, array(
            $data['ITH_ITMCD'], $data['ITH_DATE'], $data['ITH_FORM'], $data['ITH_DOC'], $data['ITH_QTY'],
            $data['ITH_WH'], $data['ITH_USRID'],
        ));
        return $this->db->affected_rows();
    }
    public function insert_disposefg($data)
    {
        $qry = "INSERT INTO ITH_TBL (ITH_SER,ITH_ITMCD, ITH_DATE, ITH_FORM, ITH_DOC,ITH_QTY,
        ITH_WH,ITH_LUPDT,ITH_USRID)
        VALUES(?,?,?,?,?,?,?,getdate(),?)";
        $this->db->query($qry, array(
            $data['ITH_SER'], $data['ITH_ITMCD'], $data['ITH_DATE'], $data['ITH_FORM'], $data['ITH_DOC'], $data['ITH_QTY'],
            $data['ITH_WH'], $data['ITH_USRID'],
        ));
        return $this->db->affected_rows();
    }
    public function insert_delivery($data)
    {
        $qry = "INSERT INTO ITH_TBL (ITH_SER,ITH_ITMCD, ITH_DATE, ITH_FORM, ITH_DOC,ITH_QTY,
        ITH_WH,ITH_LUPDT,ITH_USRID)
        VALUES(?,?,?,?,?,?,?,getdate(),?)";
        $this->db->query($qry, array(
            $data['ITH_SER'], $data['ITH_ITMCD'], $data['ITH_DATE'], $data['ITH_FORM'], $data['ITH_DOC'], $data['ITH_QTY'],
            $data['ITH_WH'], $data['ITH_USRID'],
        ));
        return $this->db->affected_rows();
    }

    public function insert_cancel_kitting_out($data)
    {
        $qry = "INSERT INTO ITH_TBL (ITH_ITMCD, ITH_DATE, ITH_FORM, ITH_DOC,ITH_QTY,
        ITH_WH,ITH_LUPDT,ITH_USRID)
        VALUES(?,?,'CANCELING-RM-PSN-OUT',?,?,?,getdate(),?)";
        $this->db->query($qry, array(
            $data['ITH_ITMCD'], $data['ITH_DATE'], $data['ITH_DOC'], $data['ITH_QTY'], $data['ITH_WH'],
            $data['ITH_USRID'],
        ));
        return $this->db->affected_rows();
    }

    public function insert_cancel_kitting_in($data)
    {
        $qry = "INSERT INTO ITH_TBL (ITH_ITMCD, ITH_DATE, ITH_FORM, ITH_DOC,ITH_QTY,
        ITH_WH,ITH_LUPDT,ITH_USRID)
        VALUES(?,?,'CANCELING-RM-PSN-IN',?,?,
        ?,getdate(),?)";
        $this->db->query($qry, array(
            $data['ITH_ITMCD'], $data['ITH_DATE'], $data['ITH_DOC'], $data['ITH_QTY'],
            $data['ITH_WH'], $data['ITH_USRID'],
        ));
        return $this->db->affected_rows();
    }

    public function update_exported_fg_wh($pser)
    {
        $qry = "update ITH_TBL set ITH_EXPORTED='1' WHERE ITH_WH='AFWH3' AND ITH_QTY > 0 AND ITH_FORM='INC-WH-FG' AND ITH_SER=?";
        $this->db->query($qry, array($pser));
        return $this->db->affected_rows();
    }
    public function update_exported_qcsa_wh($pser)
    {
        $qry = "update ITH_TBL set ITH_EXPORTED='1' WHERE ITH_WH='AWIP1' AND ITH_QTY > 0 AND ITH_FORM='INC' AND ITH_SER=?";
        $this->db->query($qry, array($pser));
        return $this->db->affected_rows();
    }

    public function check_Primary($data)
    {
        return $this->db->get_where($this->TABLENAME, $data)->num_rows();
    }

    public function updatebyId($pwhere, $pval)
    {
        $this->db->where($pwhere);
        $this->db->update($this->TABLENAME, $pval);
        return $this->db->affected_rows();
    }

    public function selectSVDQTYbyDOCg($pdoc)
    {
        $this->db->select("ITH_ITMCD,ABS(SUM(ITH_QTY)) SAVEDQTY");
        $this->db->from($this->TABLENAME . " a");
        $this->db->where("ITH_DOC", $pdoc)->where("ITH_FORM", "OUT-WH-RM");
        $this->db->group_by("ITH_DOC,ITH_ITMCD");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectqtyperdocitemday($pdoc, $pitem, $pdt, $pform)
    {
        $this->db->select("abs(ITH_QTY) ITH_QTY");
        $this->db->from($this->TABLENAME . " a");
        $this->db->where("ITH_DOC", $pdoc)->where("ITH_ITMCD", $pitem)->where("ITH_DATE", $pdt)->where("ITH_FORM", $pform);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $ret = $query->row();
            return $ret->ITH_QTY;
        } else {
            return '0';
        }
        return $query->result_array();
    }

    public function selecttodayscanqa()
    {
        $this->db->limit(10);
        $this->db->from("v_infoscntoday_qa");
        $this->db->order_by('ITH_LUPDT DESC');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function selecttodayscanwhrtn()
    {
        $this->db->limit(10);
        $this->db->from("v_infoscntoday_whrtn");
        $this->db->order_by('ITH_LUPDT DESC');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function selecttodayscanWIP()
    {
        $this->db->limit(10);
        $this->db->from("v_infoscntoday_wip");
        $this->db->order_by('ITH_LUPDT DESC');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function selecttodayscanprd()
    {
        $this->db->limit(10);
        $this->db->from("v_infoscntoday_prd");
        $this->db->order_by('ITH_LUPDT DESC');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function selecttodayscanwh()
    {
        $this->db->limit(10);
        $this->db->from("v_infoscntoday_wh");
        $this->db->order_by('ITH_LUPDT DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectincfgloc($pin)
    {
        $qry = "SELECT a.ITH_SER,ITH_LOC FROM ITH_TBL a INNER JOIN
        (SELECT ITH_SER, MAX(ITH_LUPDT) LUPDT FROM ITH_TBL WHERE ITH_SER in ($pin)
        GROUP BY ITH_SER) v1 on a.ITH_LUPDT=v1.LUPDT AND a.ITH_SER=v1.ITH_SER
        WHERE ITH_QTY>0"; //ITH_FORM='INC-WH-FG'
        $query = $this->db->query($qry);
        return $query->result_array();
    }

    public function selecttxmonthbefore($pdate, $wh)
    {
        $qry = "SELECT VITH.*,RTRIM(MITM_ITMD1) MITM_ITMD1 , RTRIM(MITM_SPTNO) MITM_SPTNO FROM (select ITH_ITMCD,abs(ISNULL(SUM(CASE WHEN ITH_QTY<0 THEN ITH_QTY END),0)) ITH_QTYOUT
        ,ISNULL(SUM(CASE WHEN ITH_QTY>0 THEN ITH_QTY END),0) ITH_QTYIN
        from v_ith_tblc WHERE ITH_WH=? and ITH_DATEC<=?
        group by ITH_ITMCD) VITH
        LEFT JOIN MITM_TBL ON ITH_ITMCD=MITM_ITMCD
        ORDER BY MITM_ITMCD";
        $query = $this->db->query($qry, [$wh, $pdate]);
        return $query->result_array();
    }

    public function select_where($Pwhere)
    {
        $this->db->from($this->TABLENAME);
        $this->db->where($Pwhere);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_view_where($Pwhere)
    {
        $this->db->from('v_ith_tblc');
        $this->db->where($Pwhere);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_view_where_and_locationIn($Pwhere, $location)
    {
        $this->db->from('v_ith_tblc');
        $this->db->where($Pwhere)->where_in('ITH_WH', $location);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_psi_stock($wh, $item, $pbg)
    {
        $qry = "select ITH_WH,ITH_ITMCD,MITM_ITMD1,MITM_SPTNO,SUM(ITH_QTY) STOCKQTY,MITM_STKUOM from ITH_TBL a inner join MITM_TBL b on a.ITH_ITMCD=b.MITM_ITMCD
        left join v_mitm_bsgroup on ITH_ITMCD=PDPP_MDLCD
        WHERE ITH_WH='$wh' AND ITH_ITMCD like '$item%' and PDPP_BSGRP in ($pbg)
        AND ITH_FORM NOT IN ('SASTART','SA')
        GROUP BY ITH_ITMCD,ITH_WH,MITM_SPTNO,MITM_STKUOM,MITM_ITMD1
        ORDER BY ITH_ITMCD ASC";
        $query = $this->db->query($qry);
        return $query->result_array();
    }

    public function select_psi_stock_date($wh, $item, $pbg, $pdate)
    {
        $whout = $wh == 'AFWH3' || $wh == 'AFSMT' ? 'ARSHP' : $wh;
        $whclosing = $wh == 'AFSMT' ? 'AFWH3' : $wh;
        $closingcolumn = $wh == 'AFSMT' ? "(STOCKQTY+ISNULL(PRPQTY,0))" : "STOCKQTY";
        $qry = "SELECT ITH_WH
                ,VSTOCK.ITH_ITMCD
                ,MITM_ITMD1
                ,MITM_SPTNO
                ,BEFQTY
                ,INQTY
                ,PRPQTY
                ,ABS(OUTQTY) OUTQTY
                ,$closingcolumn STOCKQTY
                ,MITM_STKUOM
                ,UPPER(ISNULL(MITM_NCAT, '')) MITM_NCAT
            FROM (
                SELECT ITH_WH
                    ,ITH_ITMCD
                    ,RTRIM(MITM_ITMD1) MITM_ITMD1
                    ,RTRIM(MITM_SPTNO) MITM_SPTNO
                    ,SUM(ITH_QTY) STOCKQTY
                    ,RTRIM(MITM_STKUOM) MITM_STKUOM
                    ,MITM_NCAT
                    ,SUM(CASE
                            WHEN ITH_DATEC < ?
                                THEN ITH_QTY
                            END) BEFQTY
                FROM v_ith_tblc a
                LEFT JOIN MITM_TBL b ON a.ITH_ITMCD = b.MITM_ITMCD
                LEFT JOIN v_mitm_bsgroup ON ITH_ITMCD = PDPP_MDLCD
                WHERE ITH_WH = '$whclosing'
                    AND MITM_ITMCD LIKE ?
                    AND PDPP_BSGRP IN ($pbg)
                    AND ITH_FORM NOT IN (
                        'SASTART'
                        ,'SA'
                        )
                    AND ITH_DATEC <= ?
                GROUP BY ITH_ITMCD
                    ,ITH_WH
                    ,MITM_SPTNO
                    ,MITM_STKUOM
                    ,MITM_ITMD1
                    ,MITM_NCAT
                ) VSTOCK
            LEFT JOIN (
                SELECT ITH_ITMCD
                    ,SUM(ITH_QTY) INQTY
                FROM v_ith_tblc a
                WHERE ITH_WH = '$whclosing'
                    AND ITH_ITMCD LIKE ?
                    AND ITH_FORM NOT IN (
                        'SPLIT-FG-LBL'
                        ,'JOIN_IN'
                        ,'JOIN_OUT'
                        ,'CANCEL-SHIP'
                        ,'TRFIN-FG'
                        )
                    AND ITH_FORM NOT IN (
                        'SASTART'
                        ,'SA'
                        )
                    AND ITH_DATEC = ?
                    AND ITH_QTY > 0
                GROUP BY ITH_ITMCD
                ) VIN ON VSTOCK.ITH_ITMCD = VIN.ITH_ITMCD
            LEFT JOIN (
                SELECT ITH_ITMCD
                    ,SUM(ITH_QTY) OUTQTY
                FROM v_ith_tblc a
                WHERE ITH_WH = '$whout'
                    AND ITH_ITMCD LIKE ?
                    AND ITH_FORM NOT IN (
                        'SPLIT-FG-LBL'
                        ,'JOIN_IN'
                        ,'JOIN_OUT'
                        ,'CANCEL-SHIP'
                        )
                    AND ITH_FORM NOT IN (
                        'SASTART'
                        ,'SA'
                        )
                    AND ITH_DATEC = ?
                    AND ITH_QTY < 0
                GROUP BY ITH_ITMCD
                ) VOUT ON VSTOCK.ITH_ITMCD = VOUT.ITH_ITMCD
            LEFT JOIN (
                SELECT ITH_ITMCD
                    ,SUM(ITH_QTY) PRPQTY
                FROM v_ith_tblc a
                WHERE ITH_WH = 'ARSHP'
                    AND ITH_ITMCD LIKE ?
                    AND ITH_FORM NOT IN (
                        'SASTART'
                        ,'SA'
                        )
                    AND ITH_DATEC <= ?
                GROUP BY ITH_ITMCD
                ) VPREP ON VSTOCK.ITH_ITMCD = VPREP.ITH_ITMCD
            ORDER BY VSTOCK.ITH_ITMCD ASC";
        $query = $this->db->query($qry, [$pdate, '%' . $item . '%', $pdate, '%' . $item . '%', $pdate, '%' . $item . '%', $pdate, '%' . $item . '%', $pdate]);
        return $query->result_array();
    }
    public function selectPSIStockAtDateByDescripton($wh, $item, $PBG, $pdate)
    {
        $whout = $wh == 'AFWH3' || $wh == 'AFSMT' ? 'ARSHP' : $wh;
        $whclosing = $wh == 'AFSMT' ? 'AFWH3' : $wh;
        $CLOSINGCOLUMN = $wh == 'AFSMT' ? "(STOCKQTY+ISNULL(PRPQTY,0))" : "STOCKQTY";
        $qry = "SELECT ITH_WH
                ,VSTOCK.ITH_ITMCD
                ,MITM_ITMD1
                ,MITM_SPTNO
                ,BEFQTY
                ,INQTY
                ,PRPQTY
                ,ABS(OUTQTY) OUTQTY
                ,$CLOSINGCOLUMN STOCKQTY
                ,MITM_STKUOM
                ,UPPER(ISNULL(MITM_NCAT, '')) MITM_NCAT
            FROM (
                SELECT ITH_WH
                    ,ITH_ITMCD
                    ,RTRIM(MITM_ITMD1) MITM_ITMD1
                    ,RTRIM(MITM_SPTNO) MITM_SPTNO
                    ,SUM(ITH_QTY) STOCKQTY
                    ,RTRIM(MITM_STKUOM) MITM_STKUOM
                    ,MITM_NCAT
                    ,SUM(CASE
                            WHEN ITH_DATEC < ?
                                THEN ITH_QTY
                            END) BEFQTY
                FROM v_ith_tblc a
                LEFT JOIN MITM_TBL b ON a.ITH_ITMCD = b.MITM_ITMCD
                LEFT JOIN v_mitm_bsgroup ON ITH_ITMCD = PDPP_MDLCD
                WHERE ITH_WH = '$whclosing'
                    AND MITM_ITMD1 LIKE ?
                    AND PDPP_BSGRP IN ($PBG)
                    AND ITH_FORM NOT IN (
                        'SASTART'
                        ,'SA'
                        )
                    AND ITH_DATEC <= ?
                GROUP BY ITH_ITMCD
                    ,ITH_WH
                    ,MITM_SPTNO
                    ,MITM_STKUOM
                    ,MITM_ITMD1
                    ,MITM_NCAT
                ) VSTOCK
            LEFT JOIN (
                SELECT ITH_ITMCD
                    ,SUM(ITH_QTY) INQTY
                FROM v_ith_tblc a
                WHERE ITH_WH = '$whclosing'
                    AND ITH_FORM NOT IN (
                        'SPLIT-FG-LBL'
                        ,'JOIN_IN'
                        ,'JOIN_OUT'
                        ,'CANCEL-SHIP'
                        ,'TRFIN-FG'
                        )
                    AND ITH_FORM NOT IN (
                        'SASTART'
                        ,'SA'
                        )
                    AND ITH_DATEC = ?
                    AND ITH_QTY > 0
                GROUP BY ITH_ITMCD
                ) VIN ON VSTOCK.ITH_ITMCD = VIN.ITH_ITMCD
            LEFT JOIN (
                SELECT ITH_ITMCD
                    ,SUM(ITH_QTY) OUTQTY
                FROM v_ith_tblc a
                WHERE ITH_WH = '$whout'
                    AND ITH_FORM NOT IN (
                        'SPLIT-FG-LBL'
                        ,'JOIN_IN'
                        ,'JOIN_OUT'
                        ,'CANCEL-SHIP'
                        )
                    AND ITH_FORM NOT IN (
                        'SASTART'
                        ,'SA'
                        )
                    AND ITH_DATEC = ?
                    AND ITH_QTY < 0
                GROUP BY ITH_ITMCD
                ) VOUT ON VSTOCK.ITH_ITMCD = VOUT.ITH_ITMCD
            LEFT JOIN (
                SELECT ITH_ITMCD
                    ,SUM(ITH_QTY) PRPQTY
                FROM v_ith_tblc a
                WHERE ITH_WH = 'ARSHP'
                    AND ITH_FORM NOT IN (
                        'SASTART'
                        ,'SA'
                        )
                    AND ITH_DATEC <= ?
                GROUP BY ITH_ITMCD
                ) VPREP ON VSTOCK.ITH_ITMCD = VPREP.ITH_ITMCD
            ORDER BY VSTOCK.ITH_ITMCD ASC";
        $query = $this->db->query($qry, [$pdate, '%' . $item . '%', $pdate, $pdate, $pdate, $pdate]);
        return $query->result_array();
    }
    public function select_psi_stock_date_fg_rtn($main_wh, $inc_wh, $out_wh, $preparation_wh, $item, $pbg, $pdate)
    {
        $s_main_wh = "'" . implode("','", $main_wh) . "'";
        $s_inc_wh = "'" . implode("','", $inc_wh) . "'";
        $s_out_wh = "'" . implode("','", $out_wh) . "'";
        $qry = "SELECT VSTOCK.ITH_ITMCD,MITM_ITMD1,MITM_SPTNO,BEFQTY,INQTY, PRPQTY,abs(OUTQTY) OUTQTY, ISNULL(STOCKQTY,0) STOCKQTY, MITM_STKUOM,UPPER(ISNULL(MITM_NCAT,'')) MITM_NCAT FROM
        (select ITH_ITMCD,RTRIM(MITM_ITMD1) MITM_ITMD1,RTRIM(MITM_SPTNO) MITM_SPTNO,SUM(ITH_QTY) STOCKQTY,RTRIM(MITM_STKUOM) MITM_STKUOM,MITM_NCAT from v_ith_tblc a inner join MITM_TBL b on a.ITH_ITMCD=b.MITM_ITMCD
                left join v_mitm_bsgroup on ITH_ITMCD=PDPP_MDLCD
                WHERE ITH_WH in ($s_main_wh) AND ITH_ITMCD like '%$item%' and PDPP_BSGRP IN ($pbg)
                AND ITH_FORM NOT IN ('SASTART','SA') and ITH_DATEC<='$pdate'
                GROUP BY ITH_ITMCD,MITM_SPTNO,MITM_STKUOM,MITM_ITMD1,MITM_NCAT) VSTOCK
        LEFT JOIN
        (select ITH_ITMCD,SUM(ITH_QTY) BEFQTY from v_ith_tblc a
                WHERE ITH_WH in ($s_main_wh) AND ITH_ITMCD like '%$item%'
                AND ITH_FORM NOT IN ('SASTART','SA') and ITH_DATEC< '$pdate'
                GROUP BY ITH_ITMCD) VBEF ON VSTOCK.ITH_ITMCD=VBEF.ITH_ITMCD
        LEFT JOIN
        (select ITH_ITMCD,SUM(ITH_QTY) INQTY from v_ith_tblc a
                WHERE ITH_WH in ($s_inc_wh) AND ITH_ITMCD like '%$item%' AND ITH_FORM IN ('INC-WHRTN-FG-C','INC')
                AND ITH_FORM NOT IN ('SASTART','SA') and ITH_DATEC= '$pdate' AND ITH_QTY>0
                GROUP BY ITH_ITMCD) VIN ON VSTOCK.ITH_ITMCD=VIN.ITH_ITMCD
        LEFT JOIN
        (select ITH_ITMCD,SUM(ITH_QTY) OUTQTY from v_ith_tblc a
                WHERE ITH_WH in ($s_out_wh) AND ITH_ITMCD like '%$item%' AND ITH_FORM NOT IN ('CONVERT-OUT',
                        'OUT-PEN-FG',
                        'OUT-USE',
                        'OUT-SHP-FG',
                        'ADJ-OUT',
                        'ADJ-I-OUT',
                        'ADJ-O-OUT',
                        'OUT-SCR-RTN',
                        'SPLIT-CNV-FG-OUT',
                        'OUT-C')
                AND ITH_FORM NOT IN ('SASTART','SA') and ITH_DATEC= '$pdate' AND ITH_QTY<0
                GROUP BY ITH_ITMCD) VOUT ON VSTOCK.ITH_ITMCD=VOUT.ITH_ITMCD
        LEFT JOIN
        (select ITH_ITMCD,SUM(ITH_QTY) PRPQTY from v_ith_tblc a
                WHERE ITH_WH='$preparation_wh' AND ITH_ITMCD like '%$item%'
                AND ITH_FORM NOT IN ('SASTART','SA') and ITH_DATEC<= '$pdate'
                GROUP BY ITH_ITMCD) VPREP ON VSTOCK.ITH_ITMCD=VPREP.ITH_ITMCD
                ORDER BY VSTOCK.ITH_ITMCD ASC";
        $query = $this->db->query($qry);
        return $query->result_array();
    }

    public function select_compare_inventory($wh, $pdate)
    {
        $whclosing = $wh == 'AFSMT' ? 'AFWH3' : $wh;
        $closingcolumn = $wh == 'AFSMT' ? "(STOCKQTY+ISNULL(PRPQTY,0))" : "STOCKQTY";
        if (in_array($whclosing, ['AFWH9SC', 'AFWH3', 'AWIP1', 'QAFG', 'NFWH9SC', 'NFWH4RT'])) {
            $qry = "SELECT ITH_WH,ISNULL(ITH_ITMCD,ITRN_ITMCD) ITH_ITMCD,MITM_ITMD1,MITM_SPTNO, STOCKQTY, MITM_STKUOM,MGAQTY,ITRN_ITMCD,MGMITM_SPTNO,MGMITM_STKUOM,MGMITM_ITMD1 FROM(
                SELECT ITH_WH,VSTOCK.ITH_ITMCD,MITM_ITMD1,MITM_SPTNO, $closingcolumn STOCKQTY, MITM_STKUOM FROM
                    (select ITH_WH,ITH_ITMCD,RTRIM(MITM_ITMD1) MITM_ITMD1,RTRIM(MITM_SPTNO) MITM_SPTNO,SUM(ITH_QTY) STOCKQTY,RTRIM(MITM_STKUOM) MITM_STKUOM from v_ith_tblc a inner join MITM_TBL b on a.ITH_ITMCD=b.MITM_ITMCD
                            WHERE ITH_WH='$whclosing' AND ITH_FORM NOT IN ('SASTART','SA') and ITH_DATEC<='$pdate'
                            GROUP BY ITH_ITMCD,ITH_WH,MITM_SPTNO,MITM_STKUOM,MITM_ITMD1) VSTOCK
                    LEFT JOIN
                    (select ITH_ITMCD,SUM(ITH_QTY) PRPQTY from v_ith_tblc a
                            WHERE ITH_WH='ARSHP' AND ITH_FORM NOT IN ('SASTART','SA') and ITH_DATEC<= '$pdate'
                            GROUP BY ITH_ITMCD) VPREP ON VSTOCK.ITH_ITMCD=VPREP.ITH_ITMCD) VWMS
                FULL JOIN (SELECT  RTRIM(FTRN_ITMCD) ITRN_ITMCD,SUM(CASE WHEN FTRN_IOFLG = '1' THEN FTRN_TRNQT ELSE -1*FTRN_TRNQT END) MGAQTY
                , RTRIM(MITM_SPTNO) MGMITM_SPTNO, RTRIM(MITM_STKUOM) MGMITM_STKUOM, RTRIM(MITM_ITMD1) MGMITM_ITMD1
                        FROM XFTRN_TBL
                        LEFT JOIN XMITM_V ON FTRN_ITMCD=MITM_ITMCD
                        WHERE FTRN_ISUDT<='$pdate' AND FTRN_LOCCD='$whclosing'
                        GROUP BY FTRN_ITMCD,MITM_SPTNO,MITM_STKUOM,MITM_ITMD1) VMEGA ON ITH_ITMCD=ITRN_ITMCD
                        ORDER BY ITH_ITMCD ASC";
        } else {
            $qry = "SELECT ITH_WH,ISNULL(ITH_ITMCD,ITRN_ITMCD) ITH_ITMCD,MITM_ITMD1,MITM_SPTNO, ISNULL(STOCKQTY,0) STOCKQTY, MITM_STKUOM,ISNULL(MGAQTY,0) MGAQTY,ITRN_ITMCD,MGMITM_SPTNO,MGMITM_STKUOM,MGMITM_ITMD1 FROM(
                SELECT ITH_WH,VSTOCK.ITH_ITMCD,MITM_ITMD1,MITM_SPTNO, STOCKQTY, MITM_STKUOM FROM
                    (select ITH_WH,ITH_ITMCD,RTRIM(MITM_ITMD1) MITM_ITMD1,RTRIM(MITM_SPTNO) MITM_SPTNO,SUM(ITH_QTY) STOCKQTY,RTRIM(MITM_STKUOM) MITM_STKUOM from v_ith_tblc a inner join MITM_TBL b on a.ITH_ITMCD=b.MITM_ITMCD
                            WHERE ITH_WH='$whclosing' AND ITH_FORM NOT IN ('SASTART','SA') and ITH_DATEC<='$pdate'
                            GROUP BY ITH_ITMCD,ITH_WH,MITM_SPTNO,MITM_STKUOM,MITM_ITMD1) VSTOCK
                    ) VWMS
                            FULL OUTER JOIN (SELECT  RTRIM(ITRN_ITMCD) ITRN_ITMCD,SUM(CASE WHEN ITRN_IOFLG = '1' THEN ITRN_TRNQT ELSE 0-ITRN_TRNQT END) MGAQTY
                , RTRIM(MITM_SPTNO) MGMITM_SPTNO, RTRIM(MITM_STKUOM) MGMITM_STKUOM, RTRIM(MITM_ITMD1) MGMITM_ITMD1
                        FROM XITRN_TBL
                        LEFT JOIN XMITM_V ON ITRN_ITMCD=MITM_ITMCD
                        WHERE ITRN_ISUDT<='$pdate' AND ITRN_LOCCD='$whclosing'
                        GROUP BY ITRN_ITMCD,MITM_SPTNO,MITM_STKUOM,MITM_ITMD1) VMEGA ON ITH_ITMCD=ITRN_ITMCD
                        ORDER BY ITH_ITMCD ASC";
        }
        $query = $this->db->query($qry);
        return $query->result_array();
    }
    public function select_compare_inventory_machine($wh, $pdate)
    {
        $qry = "SELECT ITH_WH,RTRIM(ITH_ITMCD) ITH_ITMCD,MITM_ITMD1,MITM_SPTNO, STOCKQTY, MITM_STKUOM FROM(
            SELECT ITH_WH,VSTOCK.ITH_ITMCD,MITM_ITMD1,MITM_SPTNO, STOCKQTY, MITM_STKUOM FROM
                (select ITH_WH,ITH_ITMCD,RTRIM(MITM_ITMD1) MITM_ITMD1,RTRIM(MITM_SPTNO) MITM_SPTNO,SUM(ITH_QTY) STOCKQTY,RTRIM(MITM_STKUOM) MITM_STKUOM from v_ith_tblc a inner join MITM_TBL b on a.ITH_ITMCD=b.MITM_ITMCD
                        WHERE ITH_WH='$wh' AND ITH_FORM NOT IN ('SASTART','SA') and ITH_DATEC<='$pdate'
                        GROUP BY ITH_ITMCD,ITH_WH,MITM_SPTNO,MITM_STKUOM,MITM_ITMD1) VSTOCK
                ) VWMS
                    ORDER BY ITH_ITMCD ASC";
        $query = $this->db->query($qry);
        return $query->result_array();
    }
    public function select_compare_inventory_fg_rtn($wh, $pdate)
    {
        $qry = "SELECT ISNULL(ITH_ITMCD,ITRN_ITMCD) ITH_ITMCD ,MITM_ITMD1,MITM_SPTNO, STOCKQTY, MITM_STKUOM,MGAQTY,ITRN_ITMCD,MGMITM_SPTNO,MGMITM_STKUOM,MGMITM_ITMD1 FROM(
            SELECT VSTOCK.ITH_ITMCD,MITM_ITMD1,MITM_SPTNO, STOCKQTY STOCKQTY, MITM_STKUOM FROM
                (select ITH_ITMCD,RTRIM(MITM_ITMD1) MITM_ITMD1,RTRIM(MITM_SPTNO) MITM_SPTNO,SUM(ITH_QTY) STOCKQTY,RTRIM(MITM_STKUOM) MITM_STKUOM from v_ith_tblc a inner join MITM_TBL b on a.ITH_ITMCD=b.MITM_ITMCD
                        WHERE ITH_WH IN ('$wh','AFQART','ARSHPRTN') AND ITH_FORM NOT IN ('SASTART','SA') and ITH_DATEC<='$pdate'
                        GROUP BY ITH_ITMCD,MITM_SPTNO,MITM_STKUOM,MITM_ITMD1) VSTOCK
            ) VWMS
            FULL JOIN (SELECT  RTRIM(FTRN_ITMCD) ITRN_ITMCD,SUM(CASE WHEN FTRN_IOFLG = '1' THEN FTRN_TRNQT ELSE -1*FTRN_TRNQT END) MGAQTY
            , RTRIM(MITM_SPTNO) MGMITM_SPTNO, RTRIM(MITM_STKUOM) MGMITM_STKUOM, RTRIM(MITM_ITMD1) MGMITM_ITMD1
                    FROM XFTRN_TBL
                    LEFT JOIN XMITM_V ON FTRN_ITMCD=MITM_ITMCD
                    WHERE FTRN_ISUDT<='$pdate' AND FTRN_LOCCD IN ('$wh')
                    GROUP BY FTRN_ITMCD,MITM_SPTNO,MITM_STKUOM,MITM_ITMD1) VMEGA ON ITH_ITMCD=ITRN_ITMCD
                    ORDER BY ITH_ITMCD ASC";
        $query = $this->db->query($qry);
        return $query->result_array();
    }
    public function select_compare_inventory_fg_fresh($wh, $pdate)
    {
        $qry = "SELECT ISNULL(ITH_ITMCD,ITRN_ITMCD) ITH_ITMCD ,MITM_ITMD1,MITM_SPTNO, STOCKQTY, MITM_STKUOM,MGAQTY,ITRN_ITMCD,MGMITM_SPTNO,MGMITM_STKUOM,MGMITM_ITMD1 FROM(
            SELECT VSTOCK.ITH_ITMCD,MITM_ITMD1,MITM_SPTNO, STOCKQTY STOCKQTY, MITM_STKUOM FROM
                (select ITH_ITMCD,RTRIM(MITM_ITMD1) MITM_ITMD1,RTRIM(MITM_SPTNO) MITM_SPTNO,SUM(ITH_QTY) STOCKQTY,RTRIM(MITM_STKUOM) MITM_STKUOM from v_ith_tblc a inner join MITM_TBL b on a.ITH_ITMCD=b.MITM_ITMCD
                        WHERE ITH_WH IN ('$wh','ARSHP') AND ITH_FORM NOT IN ('SASTART','SA') and ITH_DATEC<='$pdate'
                        GROUP BY ITH_ITMCD,MITM_SPTNO,MITM_STKUOM,MITM_ITMD1) VSTOCK
            ) VWMS
            FULL JOIN (SELECT  RTRIM(FTRN_ITMCD) ITRN_ITMCD,SUM(CASE WHEN FTRN_IOFLG = '1' THEN FTRN_TRNQT ELSE -1*FTRN_TRNQT END) MGAQTY
            , RTRIM(MITM_SPTNO) MGMITM_SPTNO, RTRIM(MITM_STKUOM) MGMITM_STKUOM, RTRIM(MITM_ITMD1) MGMITM_ITMD1
                    FROM XFTRN_TBL
                    LEFT JOIN XMITM_V ON FTRN_ITMCD=MITM_ITMCD
                    WHERE FTRN_ISUDT<='$pdate' AND FTRN_LOCCD IN ('$wh')
                    GROUP BY FTRN_ITMCD,MITM_SPTNO,MITM_STKUOM,MITM_ITMD1) VMEGA ON ITH_ITMCD=ITRN_ITMCD
                    ORDER BY ITH_ITMCD ASC";
        $query = $this->db->query($qry);
        return $query->result_array();
    }
    public function select_compare_inventory_fg_qa($wh, $pdate)
    {
        $qry = "SELECT ISNULL(ITH_ITMCD,ITRN_ITMCD) ITH_ITMCD,MITM_ITMD1,MITM_SPTNO, STOCKQTY, MITM_STKUOM,MGAQTY,ITRN_ITMCD,MGMITM_SPTNO,MGMITM_STKUOM,MGMITM_ITMD1 FROM(
            SELECT VSTOCK.ITH_ITMCD,MITM_ITMD1,MITM_SPTNO, STOCKQTY STOCKQTY, MITM_STKUOM FROM
                (select ITH_ITMCD,RTRIM(MITM_ITMD1) MITM_ITMD1,RTRIM(MITM_SPTNO) MITM_SPTNO,SUM(ITH_QTY) STOCKQTY,RTRIM(MITM_STKUOM) MITM_STKUOM from v_ith_tblc a inner join MITM_TBL b on a.ITH_ITMCD=b.MITM_ITMCD
                        WHERE ITH_WH IN ('$wh') AND ITH_FORM NOT IN ('SASTART','SA') and ITH_DATEC<='$pdate'
                        GROUP BY ITH_ITMCD,MITM_SPTNO,MITM_STKUOM,MITM_ITMD1) VSTOCK
            ) VWMS
            FULL JOIN (SELECT  RTRIM(FTRN_ITMCD) ITRN_ITMCD,SUM(CASE WHEN FTRN_IOFLG = '1' THEN FTRN_TRNQT ELSE -1*FTRN_TRNQT END) MGAQTY
            , RTRIM(MITM_SPTNO) MGMITM_SPTNO, RTRIM(MITM_STKUOM) MGMITM_STKUOM, RTRIM(MITM_ITMD1) MGMITM_ITMD1
                    FROM XFTRN_TBL
                    LEFT JOIN XMITM_V ON FTRN_ITMCD=MITM_ITMCD
                    WHERE FTRN_ISUDT<='$pdate' AND FTRN_LOCCD IN ('$wh')
                    GROUP BY FTRN_ITMCD,MITM_SPTNO,MITM_STKUOM,MITM_ITMD1) VMEGA ON ITH_ITMCD=ITRN_ITMCD
                    ORDER BY ITH_ITMCD ASC";
        $query = $this->db->query($qry);
        return $query->result_array();
    }
    public function select_compare_inventory_fg_rtn_asset($wh, $pdate)
    {
        $qry = "SELECT ISNULL(ITH_ITMCD,ITRN_ITMCD) ITH_ITMCD,MITM_ITMD1,MITM_SPTNO, STOCKQTY, MITM_STKUOM,MGAQTY,ITRN_ITMCD,MGMITM_SPTNO,MGMITM_STKUOM,MGMITM_ITMD1 FROM(
            SELECT VSTOCK.ITH_ITMCD,MITM_ITMD1,MITM_SPTNO, STOCKQTY STOCKQTY, MITM_STKUOM FROM
                (select ITH_ITMCD,RTRIM(MITM_ITMD1) MITM_ITMD1,RTRIM(MITM_SPTNO) MITM_SPTNO,SUM(ITH_QTY) STOCKQTY,RTRIM(MITM_STKUOM) MITM_STKUOM from v_ith_tblc a inner join MITM_TBL b on a.ITH_ITMCD=b.MITM_ITMCD
                        WHERE ITH_WH IN ('$wh','AFQART2','ARSHPRTN2') AND ITH_FORM NOT IN ('SASTART','SA') and ITH_DATEC<='$pdate'
                        GROUP BY ITH_ITMCD,MITM_SPTNO,MITM_STKUOM,MITM_ITMD1) VSTOCK
            ) VWMS
            FULL JOIN (SELECT  RTRIM(FTRN_ITMCD) ITRN_ITMCD,SUM(CASE WHEN FTRN_IOFLG = '1' THEN FTRN_TRNQT ELSE -1*FTRN_TRNQT END) MGAQTY
            , RTRIM(MITM_SPTNO) MGMITM_SPTNO, RTRIM(MITM_STKUOM) MGMITM_STKUOM, RTRIM(MITM_ITMD1) MGMITM_ITMD1
                    FROM XFTRN_TBL
                    LEFT JOIN XMITM_V ON FTRN_ITMCD=MITM_ITMCD
                    WHERE FTRN_ISUDT<='$pdate' AND FTRN_LOCCD IN ('$wh')
                    GROUP BY FTRN_ITMCD,MITM_SPTNO,MITM_STKUOM,MITM_ITMD1) VMEGA ON ITH_ITMCD=ITRN_ITMCD
                    ORDER BY ITH_ITMCD ASC";
        $query = $this->db->query($qry);
        return $query->result_array();
    }

    public function select_psi_stock_date_wbg($wh, $item, $pdate)
    {
        $qry = "wms_sp_std_stock_wbg ?, ?, ?";
        $query = $this->db->query($qry, [$wh, $item, $pdate]);
        return $query->result_array();
    }

    public function selectPSIStockDateWBGByDescription($wh, $itemDescription, $pdate)
    {
        $qry = "wms_spSelectStockWBGByDescription ?, ?, ?";
        $query = $this->db->query($qry, [$wh, $itemDescription, $pdate]);
        return $query->result_array();
    }

    public function select_psi_stock_date_wbg_fg_rtn($main_wh, $inc_wh, $out_wh, $preparation_wh, $item, $pdate)
    {
        $s_main_wh = "'" . implode("','", $main_wh) . "'";
        $s_inc_wh = "'" . implode("','", $inc_wh) . "'";
        $s_out_wh = "'" . implode("','", $out_wh) . "'";
        $qry = "SELECT VSTOCK.ITH_ITMCD,RTRIM(MITM_ITMD1) MITM_ITMD1,RTRIM(MITM_SPTNO) MITM_SPTNO,BEFQTY,INQTY, PRPQTY,abs(OUTQTY) OUTQTY,
         ISNULL(STOCKQTY,0) STOCKQTY, MITM_STKUOM,UPPER(ISNULL(MITM_NCAT,'')) MITM_NCAT,LUPDT FROM
            (select ITH_ITMCD,MITM_ITMD1,MITM_SPTNO,SUM(ITH_QTY) STOCKQTY,MITM_STKUOM,MITM_NCAT,MAX(ITH_LUPDT) LUPDT FROM v_ith_tblc a INNER JOIN MITM_TBL b on a.ITH_ITMCD=b.MITM_ITMCD
            WHERE ITH_WH in ($s_main_wh) AND ITH_ITMCD LIKE '%$item%'
            AND ITH_FORM NOT IN ('SASTART','SA') and ITH_DATEC<= '$pdate'
            GROUP BY ITH_ITMCD,MITM_SPTNO,MITM_STKUOM,MITM_ITMD1,MITM_NCAT) VSTOCK
        LEFT JOIN
        (SELECT ITH_ITMCD,SUM(ITH_QTY) BEFQTY FROM v_ith_tblc a
                WHERE ITH_WH in ($s_main_wh) AND ITH_ITMCD LIKE '%$item%'
                AND ITH_FORM NOT IN ('SASTART','SA') AND ITH_DATEC< '$pdate'
                GROUP BY ITH_ITMCD) VBEF ON VSTOCK.ITH_ITMCD=VBEF.ITH_ITMCD
        LEFT JOIN
        (SELECT ITH_ITMCD,SUM(ITH_QTY) INQTY FROM v_ith_tblc a
                WHERE ITH_WH in ($s_inc_wh) AND ITH_ITMCD LIKE '%$item%' AND ITH_FORM NOT IN ('INC-WHRTN-FG-C','INC')
                AND ITH_FORM NOT IN ('SASTART','SA') AND ITH_DATEC='$pdate' AND ITH_QTY>0
                GROUP BY ITH_ITMCD) VIN ON VSTOCK.ITH_ITMCD=VIN.ITH_ITMCD
        LEFT JOIN
        (SELECT ITH_ITMCD,SUM(ITH_QTY) OUTQTY FROM v_ith_tblc a
                WHERE ITH_WH IN ($s_out_wh) AND ITH_ITMCD LIKE CONCAT('%','$item','%') AND ITH_FORM NOT IN ('CONVERT-OUT',
                        'OUT-PEN-FG',
                        'OUT-USE',
                        'OUT-SHP-FG',
                        'ADJ-OUT',
                        'ADJ-I-OUT',
                        'ADJ-O-OUT',
                        'OUT-SCR-RTN',
                        'SPLIT-CNV-FG-OUT',
                        'OUT-C')
                AND ITH_FORM NOT IN ('SASTART','SA') AND ITH_DATEC='$pdate' AND ITH_QTY<0
                GROUP BY ITH_ITMCD) VOUT ON VSTOCK.ITH_ITMCD=VOUT.ITH_ITMCD
        LEFT JOIN
        (SELECT ITH_ITMCD,SUM(ITH_QTY) PRPQTY FROM v_ith_tblc a
                WHERE ITH_WH='$preparation_wh' AND ITH_ITMCD LIKE CONCAT('%','$item','%')
                AND ITH_FORM NOT IN ('SASTART','SA') AND ITH_DATEC<='$pdate'
                GROUP BY ITH_ITMCD) VPREP ON VSTOCK.ITH_ITMCD=VPREP.ITH_ITMCD
                ORDER BY VSTOCK.ITH_ITMCD ASC";
        $query = $this->db->query($qry);
        return $query->result_array();
    }

    public function select_abnormal_kitting_tx($pDate)
    {
        $qry = "wms_sp_abnormal_kitting_tx ?";
        $query = $this->db->query($qry, [$pDate]);
        return $query->result_array();
    }

    public function select_abnormal_kitting_tx_detail($docs, $items, $date)
    {
        $this->db->from('v_ith_tblc');
        $this->db->where_in("ITH_DOC", $docs)->where_in("ITH_ITMCD", $items)
            ->where("ITH_DATEC", $date)
            ->not_like("ITH_FORM", "RET")
            ->order_by("ITH_LUPDT");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_double_unique_tx()
    {
        return $this->db->from('wms_v_double_unique_tx')->get()->result_array();
    }

    public function select_psi_stock_date_wbg_query($wh, $item, $pdate)
    {
        $outwh = $wh === 'AFWH3' || $wh === 'AFSMT' ? 'ARSHP' : $wh;
        $closingwh = $wh === 'AFSMT' ? 'AFWH3' : $wh;
        $qry = "SELECT ITH_WH,VSTOCK.ITH_ITMCD,RTRIM(MITM_ITMD1) MITM_ITMD1,RTRIM(MITM_SPTNO) MITM_SPTNO,BEFQTY,INQTY, PRPQTY,abs(OUTQTY) OUTQTY,
        CASE WHEN '$wh'='AFSMT' THEN (STOCKQTY+ISNULL(PRPQTY,0)) ELSE
         STOCKQTY END STOCKQTY, MITM_STKUOM   FROM
            (select ITH_WH,ITH_ITMCD,MITM_ITMD1,MITM_SPTNO,SUM(ITH_QTY) STOCKQTY,MITM_STKUOM FROM v_ith_tblc a INNER JOIN MITM_TBL b on a.ITH_ITMCD=b.MITM_ITMCD

            WHERE ITH_WH='$closingwh' AND ITH_ITMCD = '$item'
            AND ITH_FORM NOT IN ('SASTART','SA') and ITH_DATEC<= '$pdate'
            GROUP BY ITH_ITMCD,ITH_WH,MITM_SPTNO,MITM_STKUOM,MITM_ITMD1) VSTOCK
        LEFT JOIN
        (SELECT ITH_ITMCD,SUM(ITH_QTY) BEFQTY FROM v_ith_tblc a
                WHERE ITH_WH='$closingwh' AND ITH_ITMCD = '$item'
                AND ITH_FORM NOT IN ('SASTART','SA') AND ITH_DATEC< '$pdate'
                GROUP BY ITH_ITMCD) VBEF ON VSTOCK.ITH_ITMCD=VBEF.ITH_ITMCD
        LEFT JOIN
        (SELECT ITH_ITMCD,SUM(ITH_QTY) INQTY FROM v_ith_tblc a
                WHERE ITH_WH='$closingwh' AND ITH_ITMCD = '$item' AND ITH_FORM NOT IN ('SPLIT-FG-LBL','JOIN_IN', 'JOIN_OUT', 'CANCEL-SHIP','TRFIN-FG')
                AND ITH_FORM NOT IN ('SASTART','SA') AND ITH_DATEC= '$pdate' AND ITH_QTY>0
                GROUP BY ITH_ITMCD) VIN ON VSTOCK.ITH_ITMCD=VIN.ITH_ITMCD
        LEFT JOIN
        (SELECT ITH_ITMCD,SUM(ITH_QTY) OUTQTY FROM v_ith_tblc a
                WHERE ITH_WH='$outwh' AND ITH_ITMCD = '$item' AND ITH_FORM NOT IN ('SPLIT-FG-LBL','JOIN_IN', 'JOIN_OUT','CANCEL-SHIP')
                AND ITH_FORM NOT IN ('SASTART','SA') AND ITH_DATEC= '$pdate' AND ITH_QTY<0
                GROUP BY ITH_ITMCD) VOUT ON VSTOCK.ITH_ITMCD=VOUT.ITH_ITMCD
        LEFT JOIN
        (SELECT ITH_ITMCD,SUM(ITH_QTY) PRPQTY FROM v_ith_tblc a
                WHERE ITH_WH='ARSHP' AND ITH_ITMCD = '$item'
                AND ITH_FORM NOT IN ('SASTART','SA') AND ITH_DATEC<= '$pdate'
                GROUP BY ITH_ITMCD) VPREP ON VSTOCK.ITH_ITMCD=VPREP.ITH_ITMCD
                ORDER BY VSTOCK.ITH_ITMCD ASC";

        $query = $this->db->query($qry);
        return $query->result_array();
    }
    public function select_psi_stock_date_wbg_detail($wh, $pdate)
    {
        $qry = "SELECT V1.*,RTRIM(MITM_ITMD1) MITM_ITMD1,RTRIM(MITM_SPTNO) MITM_SPTNO,MITM_STKUOM,SER_DOC,ITH_LOC FROM
        (select ITH_WH,ITH_ITMCD,SUM(ITH_QTY) STOCKQTY,ITH_SER,MAX(ITH_LUPDT) ITH_LUPDT
                from v_ith_tblc a
                WHERE ITH_WH=?
                AND ITH_FORM NOT IN ('SASTART','SA') and ITH_DATEC<= ?
                GROUP BY ITH_ITMCD,ITH_WH,ITH_SER
                HAVING SUM(ITH_QTY)>0) V1
                inner join MITM_TBL on V1.ITH_ITMCD=MITM_ITMCD
                LEFT JOIN ITH_TBL B ON V1.ITH_SER=B.ITH_SER AND V1.ITH_LUPDT=B.ITH_LUPDT AND B.ITH_QTY>0
        LEFT JOIN SER_TBL ON V1.ITH_SER=SER_ID
        ORDER BY ITH_ITMCD ASC";
        $query = $this->db->query($qry, [$wh, $pdate]);
        return $query->result_array();
    }

    public function select_id_willbepending_list_bydoc($pwh, $pdoc)
    {
        $qry = "sp_idpendd_list ?, ?  ";
        $query = $this->db->query($qry, array($pwh, $pdoc));
        return $query->result_array();
    }

    public function select_id_willbepending_list_byitem($pwh, $pitem)
    {
        $qry = "sp_idpendd_list_byitem ?, ?  ";
        $query = $this->db->query($qry, array($pwh, $pitem));
        return $query->result_array();
    }

    public function select_id_willbepending_list_byser($pwh, $pser)
    {
        $qry = "sp_idpendd_list_byser ?, ?  ";
        $query = $this->db->query($qry, array($pwh, $pser));
        return $query->result_array();
    }

    public function select_scanned_pend($doc, $ser)
    {
        $qry = "select ITH_SER,SUM(ITH_QTY) ITH_QTY FROM ITH_TBL WHERE ITH_DOC =? AND ITH_SER = ? and ITH_WH='QAFG'
        GROUP BY ITH_SER ";
        $query = $this->db->query($qry, array($doc, $ser));
        return $query->result_array();
    }

    public function select_stock_scrap_rm()
    {
        $qry = "sp_getstock_scrap ";
        $query = $this->db->query($qry);
        return $query->result_array();
    }
    public function select_stock_scrap_fg()
    {
        $qry = "sp_getstock_scrap_fg ";
        $query = $this->db->query($qry);
        return $query->result_array();
    }
    public function lastsdoc_dispose()
    {
        $qry = "select TOP 1 substring(ITH_DOC, 12, 3) lser from ITH_TBL
        WHERE convert(date, ITH_DATE) = convert(date,GETDATE()) AND ITH_FORM='OUT-SCR-RM'
        ORDER BY convert(bigint,SUBSTRING(ITH_DOC,12,3)) desc";
        $query = $this->db->query($qry);
        if ($query->num_rows() > 0) {
            $ret = $query->row();
            return $ret->lser;
        } else {
            return '0';
        }
    }
    public function lastsdoc_fg_dispose()
    {
        $qry = "select TOP 1 substring(ITH_DOC, 13, 3) lser from ITH_TBL
        WHERE convert(date, ITH_DATE) = convert(date,GETDATE()) AND ITH_FORM='OUT-SCR-FG'
        ORDER BY convert(bigint,SUBSTRING(ITH_DOC,13,3)) desc";
        $query = $this->db->query($qry);
        if ($query->num_rows() > 0) {
            $ret = $query->row();
            return $ret->lser;
        } else {
            return '0';
        }
    }

    //report
    public function selectincfg($pjob, $psts, $pbg)
    {
        $qry = "exec sp_rincoming_fg ?, ? , ? ";
        $query = $this->db->query($qry, [$pjob, $psts, $pbg]);
        return $query->result_array();
    }
    public function select_KKA_MEGA_FG($pdate1, $pdate2)
    {
        $qry = "wms_sp_kka_mega_fg ?, ?";
        $query = $this->db->query($qry, [$pdate1, $pdate2]);
        return $query->result_array();
    }
    public function select_KKA_MEGA_FG_RTN($pdate1, $pdate2)
    {
        $qry = "wms_sp_kka_mega_fg_rtn ?, ?";
        $query = $this->db->query($qry, [$pdate1, $pdate2]);
        return $query->result_array();
    }
    public function select_KKA_MEGA_RM($pdate1, $pdate2)
    {
        $qry = "wms_sp_kka_mega_rm ?, ?";
        $query = $this->db->query($qry, [$pdate1, $pdate2]);
        return $query->result_array();
    }
    public function selectincfg_with_revision($pjob, $psts, $pbg, $prevision)
    {
        $qry = "sp_rincoming_fg_with_rev ?, ? , ? , ?";
        $query = $this->db->query($qry, [$pjob, $psts, $pbg, $prevision]);
        return $query->result_array();
    }
    public function selectincfgrtn($pbg, $pdocno, $pitem, $psts)
    {
        $qry = "sp_rincoming_fgrtn ?, ? , ?, ? ";
        $query = $this->db->query($qry, [$pbg, $pdocno, $pitem, $psts]);
        return $query->result_array();
    }
    public function selectincfg_prd_qc($pjob, $psts, $pbg)
    {
        $qry = "exec sp_rincoming_fg_prd_qc ?, ?  ,?";
        $query = $this->db->query($qry, array($pjob, $psts, $pbg));
        return $query->result_array();
    }
    public function selectincfg_mega($pjob, $pdt1, $pdt2)
    {
        $qry = "exec sp_rincoming_fg_mega ?, ? ,? ";
        $query = $this->db->query($qry, array($pjob, $pdt1, $pdt2));
        return $query->result_array();
    }

    public function select_output_prd($dtfrom, $dtto, $assyno, $pbg)
    {
        $qry = "SELECT ITH_ITMCD,MITM_ITMD1, ITH_QTY,SER_LOTNO, SER_DOC,ITH_QTY,ITH_SER,concat(MSTEMP_FNM, ' ', MSTEMP_LNM) PIC,ITH_LUPDT,PDPP_BSGRP,SER_RMRK FROM ITH_TBL inner join SER_TBL
        on ITH_SER=SER_ID INNER JOIN MITM_TBL ON ITH_ITMCD=MITM_ITMCD
        INNER JOIN MSTEMP_TBL ON ITH_USRID=MSTEMP_ID
        INNER JOIN XWO ON SER_DOC=PDPP_WONO
        WHERE ITH_WH='ARPRD1' AND (ITH_LUPDT BETWEEN ? AND ?)
        AND ITH_QTY > 0 AND ITH_FORM='INC-PRD-FG' AND ITH_ITMCD LIKE ? and PDPP_BSGRP IN ($pbg)
        order by ITH_LUPDT ASC ";
        $query = $this->db->query($qry, [$dtfrom, $dtto, '%' . $assyno . '%']);
        return $query->result_array();
    }
    public function select_output_prd_byjob($dtfrom, $dtto, $pjob, $pbg)
    {
        $qry = "SELECT ITH_ITMCD,MITM_ITMD1, ITH_QTY,SER_LOTNO, SER_DOC,ITH_QTY,ITH_SER,concat(MSTEMP_FNM, ' ', MSTEMP_LNM) PIC,ITH_LUPDT,PDPP_BSGRP,SER_RMRK FROM ITH_TBL inner join SER_TBL
        on ITH_SER=SER_ID INNER JOIN MITM_TBL ON ITH_ITMCD=MITM_ITMCD
        INNER JOIN MSTEMP_TBL ON ITH_USRID=MSTEMP_ID
        INNER JOIN XWO ON SER_DOC=PDPP_WONO
        WHERE ITH_WH='ARPRD1' AND (ITH_LUPDT BETWEEN ? AND ?)
        AND ITH_QTY > 0 AND ITH_FORM='INC-PRD-FG' AND ITH_DOC LIKE ? and PDPP_BSGRP IN ($pbg)
        order by ITH_LUPDT ASC ";
        $query = $this->db->query($qry, [$dtfrom, $dtto, '%' . $pjob . '%']);
        return $query->result_array();
    }

    public function select_output_qc($dtfrom, $dtto, $assyno, $pbg)
    {
        $qry = "SELECT ITH_ITMCD
                    ,MITM_ITMD1
                    ,SUM(ITH_QTY) ITH_QTY
                    ,SER_LOTNO
                    ,SER_DOC
                    ,ITH_SER
                    ,CONCAT (
                        MSTEMP_FNM
                        ,' '
                        ,MSTEMP_LNM
                        ) PIC
                    ,ITH_LUPDT
                    ,SER_BSGRP PDPP_BSGRP
                    ,ISNULL(SER_RMRK, '') SER_RMRK
                FROM ITH_TBL
                INNER JOIN SER_TBL ON ITH_SER = SER_ID
                INNER JOIN MITM_TBL ON ITH_ITMCD = MITM_ITMCD
                INNER JOIN MSTEMP_TBL ON ITH_USRID = MSTEMP_ID
                WHERE ITH_WH = 'ARQA1'
                    AND (
                        ITH_LUPDT BETWEEN ?
                            AND ?
                        )
                    AND ITH_FORM = 'INC-QA-FG'
                    AND ITH_ITMCD LIKE ?
                    AND SER_BSGRP IN ($pbg)
                GROUP BY ITH_ITMCD
                    ,MITM_ITMD1
                    ,SER_LOTNO
                    ,SER_DOC
                    ,ITH_SER
                    ,CONCAT (
                        MSTEMP_FNM
                        ,' '
                        ,MSTEMP_LNM
                        )
                    ,ITH_LUPDT
                    ,SER_BSGRP
                    ,SER_RMRK
                    ,SER_CAT
                ORDER BY ITH_LUPDT ASC
            ";

        $query = $this->db->query($qry, array($dtfrom, $dtto, '%' . $assyno . '%'));
        return $query->result_array();
    }
    public function select_output_qcsa($dtfrom, $dtto, $assyno, $pbg)
    {
        $qry = "SELECT ITH_ITMCD,MITM_ITMD1, sum(ITH_QTY) ITH_QTY,SER_LOTNO, SER_DOC,ITH_SER,concat(MSTEMP_FNM, ' ', MSTEMP_LNM) PIC,ITH_LUPDT,PDPP_BSGRP,COALESCE(SER_RMRK,'') SER_RMRK FROM ITH_TBL inner join SER_TBL
        on ITH_SER=SER_ID INNER JOIN MITM_TBL ON ITH_ITMCD=MITM_ITMCD
        INNER JOIN MSTEMP_TBL ON ITH_USRID=MSTEMP_ID
        INNER JOIN XWO ON SER_DOC=PDPP_WONO
        WHERE ITH_WH='AWIP1' AND (ITH_LUPDT BETWEEN ? AND ?)
         AND ITH_FORM='INC' AND ITH_ITMCD LIKE ?  and PDPP_BSGRP IN ($pbg)
        group by ITH_ITMCD,MITM_ITMD1, SER_LOTNO, SER_DOC,ITH_SER,concat(MSTEMP_FNM, ' ', MSTEMP_LNM) ,ITH_LUPDT,PDPP_BSGRP,SER_RMRK
        order by ITH_LUPDT ASC ";
        $query = $this->db->query($qry, array($dtfrom, $dtto, '%' . $assyno . '%'));
        return $query->result_array();
    }
    public function select_output_prdsa($dtfrom, $dtto, $assyno, $pbg)
    {
        $qry = "SELECT ITH_ITMCD,MITM_ITMD1, sum(ITH_QTY) ITH_QTY,SER_LOTNO, SER_DOC,ITH_SER,concat(MSTEMP_FNM, ' ', MSTEMP_LNM) PIC,ITH_LUPDT,PDPP_BSGRP,COALESCE(SER_RMRK,'') SER_RMRK FROM ITH_TBL inner join SER_TBL
        on ITH_SER=SER_ID INNER JOIN MITM_TBL ON ITH_ITMCD=MITM_ITMCD
        INNER JOIN MSTEMP_TBL ON ITH_USRID=MSTEMP_ID
        INNER JOIN XWO ON SER_DOC=PDPP_WONO
        WHERE ITH_WH='ARPRD1' AND (ITH_LUPDT BETWEEN ? AND ?)
         AND ITH_FORM='INC' AND ITH_ITMCD LIKE ?  and PDPP_BSGRP IN ($pbg) AND SUBSTRING(ITH_SER, 1, 1)='3'
        group by ITH_ITMCD,MITM_ITMD1, SER_LOTNO, SER_DOC,ITH_SER,concat(MSTEMP_FNM, ' ', MSTEMP_LNM) ,ITH_LUPDT,PDPP_BSGRP,SER_RMRK
        order by ITH_LUPDT ASC ";
        $query = $this->db->query($qry, [$dtfrom, $dtto, '%' . $assyno . '%']);
        return $query->result_array();
    }
    public function select_output_qc_byjob($dtfrom, $dtto, $pjob, $pbg)
    {
        $qry = "SELECT ITH_ITMCD,MITM_ITMD1, sum(ITH_QTY) ITH_QTY,SER_LOTNO, SER_DOC,ITH_SER,concat(MSTEMP_FNM, ' ', MSTEMP_LNM) PIC,ITH_LUPDT,SER_BSGRP PDPP_BSGRP
        ,ISNULL(SER_RMRK,'') SER_RMRK
        FROM ITH_TBL inner join SER_TBL
        on ITH_SER=SER_ID INNER JOIN MITM_TBL ON ITH_ITMCD=MITM_ITMCD
        INNER JOIN MSTEMP_TBL ON ITH_USRID=MSTEMP_ID
        WHERE ITH_WH='ARQA1' AND (ITH_LUPDT BETWEEN ? AND ?)
         AND ITH_FORM='INC-QA-FG' AND ITH_DOC LIKE ?  and SER_BSGRP IN ($pbg)
        group by ITH_ITMCD,MITM_ITMD1, SER_LOTNO, SER_DOC,ITH_SER,concat(MSTEMP_FNM, ' ', MSTEMP_LNM) ,ITH_LUPDT,SER_BSGRP,SER_RMRK,SER_CAT
        order by ITH_LUPDT ASC ";
        $query = $this->db->query($qry, array($dtfrom, $dtto, '%' . $pjob . '%'));
        return $query->result_array();
    }
    public function select_output_qcsa_byjob($dtfrom, $dtto, $pjob, $pbg)
    {
        $qry = "SELECT ITH_ITMCD,MITM_ITMD1, sum(ITH_QTY) ITH_QTY,SER_LOTNO, SER_DOC,ITH_SER,concat(MSTEMP_FNM, ' ', MSTEMP_LNM) PIC,ITH_LUPDT,PDPP_BSGRP,COALESCE(SER_RMRK,'') SER_RMRK FROM ITH_TBL inner join SER_TBL
        on ITH_SER=SER_ID INNER JOIN MITM_TBL ON ITH_ITMCD=MITM_ITMCD
        INNER JOIN MSTEMP_TBL ON ITH_USRID=MSTEMP_ID
        INNER JOIN XWO ON SER_DOC=PDPP_WONO
        WHERE ITH_WH='AWIP1' AND (ITH_LUPDT BETWEEN ? AND ?)
         AND ITH_FORM='INC' AND ITH_DOC LIKE ?  and PDPP_BSGRP IN ($pbg)
        group by ITH_ITMCD,MITM_ITMD1, SER_LOTNO, SER_DOC,ITH_SER,concat(MSTEMP_FNM, ' ', MSTEMP_LNM) ,ITH_LUPDT,PDPP_BSGRP,SER_RMRK
        order by ITH_LUPDT ASC ";
        $query = $this->db->query($qry, [$dtfrom, $dtto, '%' . $pjob . '%']);
        return $query->result_array();
    }
    public function select_output_prdsa_byjob($dtfrom, $dtto, $pjob, $pbg)
    {
        $qry = "SELECT ITH_ITMCD,MITM_ITMD1, sum(ITH_QTY) ITH_QTY,SER_LOTNO, SER_DOC,ITH_SER,concat(MSTEMP_FNM, ' ', MSTEMP_LNM) PIC,ITH_LUPDT,PDPP_BSGRP,COALESCE(SER_RMRK,'') SER_RMRK FROM ITH_TBL inner join SER_TBL
        on ITH_SER=SER_ID INNER JOIN MITM_TBL ON ITH_ITMCD=MITM_ITMCD
        INNER JOIN MSTEMP_TBL ON ITH_USRID=MSTEMP_ID
        INNER JOIN XWO ON SER_DOC=PDPP_WONO
        WHERE ITH_WH='ARPRD1' AND (ITH_LUPDT BETWEEN ? AND ?)
         AND ITH_FORM='INC' AND ITH_DOC LIKE ?  and PDPP_BSGRP IN ($pbg)  AND SUBSTRING(ITH_SER, 1, 1)='3'
        group by ITH_ITMCD,MITM_ITMD1, SER_LOTNO, SER_DOC,ITH_SER,concat(MSTEMP_FNM, ' ', MSTEMP_LNM) ,ITH_LUPDT,PDPP_BSGRP,SER_RMRK
        order by ITH_LUPDT ASC ";
        $query = $this->db->query($qry, [$dtfrom, $dtto, '%' . $pjob . '%']);
        return $query->result_array();
    }
    public function select_output_wh_byassy($dtfrom, $dtto, $assyno)
    {
        $qry = "SELECT ITH_ITMCD,MITM_ITMD1, ITH_QTY,SER_LOTNO, SER_DOC,ITH_SER,concat(MSTEMP_FNM, ' ', MSTEMP_LNM) PIC,ITH_LUPDT,ITH_LOC, PDPP_BSGRP
        FROM ITH_TBL inner join SER_TBL
        on ITH_SER=SER_ID INNER JOIN MITM_TBL ON ITH_ITMCD=MITM_ITMCD
        INNER JOIN MSTEMP_TBL ON ITH_USRID=MSTEMP_ID
        INNER JOIN XWO ON SER_DOC=PDPP_WONO
        WHERE ITH_WH='AFWH3' AND (ITH_LUPDT BETWEEN ? AND ?)
         AND ITH_FORM='INC-WH-FG' AND ITH_ITMCD LIKE ?
        order by ITH_LUPDT ASC ";
        $query = $this->db->query($qry, [$dtfrom, $dtto, '%' . $assyno . '%']);
        return $query->result_array();
    }
    public function select_output_whrtn_byassy($dtfrom, $dtto, $assyno)
    {
        $qry = "SELECT ITH_ITMCD,MITM_ITMD1, USEQTY ITH_QTY,SER_LOTNO, vra.ITH_DOC SER_DOC,ITH_SER,concat(MSTEMP_FNM, ' ', MSTEMP_LNM) PIC,ITH_LUPDT, MBSG_BSGRP
        FROM ITH_TBL inner join SER_TBL
        on ITH_SER=SER_ID INNER JOIN MITM_TBL ON ITH_ITMCD=MITM_ITMCD
        INNER JOIN MSTEMP_TBL ON ITH_USRID=MSTEMP_ID
        left join (select ITH_REMARK EXTLBL, ABS(SUM(ITH_QTY)) USEQTY,ITH_DOC from ITH_TBL
        WHERE  ITH_WH='AFQART' AND ITH_QTY<0 AND ITH_REMARK IS NOT NULL
        GROUP BY ITH_REMARK,ITH_DOC) vra on ITH_SER=EXTLBL
        left join XVU_RTN on vra.ITH_DOC=STKTRND1_DOCNO
        WHERE (ITH_LUPDT BETWEEN ? AND ?)
         AND ITH_FORM='INC-WHRTN-FG' AND ITH_ITMCD LIKE ?
        order by ITH_LUPDT ASC ";
        $query = $this->db->query($qry, [$dtfrom, $dtto, '%' . $assyno . '%']);
        return $query->result_array();
    }
    public function select_output_wh_byjob($dtfrom, $dtto, $pjob)
    {
        $qry = "SELECT ITH_ITMCD,MITM_ITMD1, SUM(ITH_QTY) ITH_QTY,SER_LOTNO, SER_DOC,ITH_SER,concat(MSTEMP_FNM, ' ', MSTEMP_LNM) PIC,ITH_LUPDT,ITH_LOC, PDPP_BSGRP
         FROM ITH_TBL inner join SER_TBL
        on ITH_SER=SER_ID INNER JOIN MITM_TBL ON ITH_ITMCD=MITM_ITMCD
        INNER JOIN MSTEMP_TBL ON ITH_USRID=MSTEMP_ID
        INNER JOIN XWO ON SER_DOC=PDPP_WONO
        WHERE ITH_WH='AFWH3' AND (ITH_LUPDT BETWEEN ? AND ?)
         AND ITH_FORM='INC-WH-FG' AND SER_DOC LIKE ?
        GROUP BY ITH_ITMCD,MITM_ITMD1, SER_LOTNO, SER_DOC,ITH_SER,concat(MSTEMP_FNM, ' ', MSTEMP_LNM) ,ITH_LUPDT,ITH_LOC, PDPP_BSGRP
        order by ITH_LUPDT ASC ";
        $query = $this->db->query($qry, [$dtfrom, $dtto, '%' . $pjob . '%']);
        return $query->result_array();
    }
    public function select_output_whrtn_byjob($dtfrom, $dtto, $pjob)
    {
        $qry = "SELECT ITH_ITMCD,MITM_ITMD1, USEQTY ITH_QTY,SER_LOTNO, vra.ITH_DOC SER_DOC,ITH_SER,concat(MSTEMP_FNM, ' ', MSTEMP_LNM) PIC,ITH_LUPDT, MBSG_BSGRP
        FROM ITH_TBL inner join SER_TBL
        on ITH_SER=SER_ID INNER JOIN MITM_TBL ON ITH_ITMCD=MITM_ITMCD
        INNER JOIN MSTEMP_TBL ON ITH_USRID=MSTEMP_ID
        left join (select ITH_REMARK EXTLBL, ABS(SUM(ITH_QTY)) USEQTY,ITH_DOC from ITH_TBL
        WHERE  ITH_WH='AFQART' AND ITH_QTY<0 AND ITH_REMARK IS NOT NULL
        GROUP BY ITH_REMARK,ITH_DOC) vra on ITH_SER=EXTLBL
        left join XVU_RTN on vra.ITH_DOC=STKTRND1_DOCNO
        WHERE (ITH_LUPDT BETWEEN ? AND ?)
         AND ITH_FORM='INC-WHRTN-FG' AND vra.ITH_DOC LIKE ?
        order by ITH_LUPDT ASC ";
        $query = $this->db->query($qry, [$dtfrom, $dtto, '%' . $pjob . '%']);
        return $query->result_array();
    }
    public function select_output_wh_byreff($dtfrom, $dtto, $preff)
    {
        $qry = "SELECT ITH_ITMCD,MITM_ITMD1, SUM(ITH_QTY) ITH_QTY,SER_LOTNO, SER_DOC,ITH_SER,concat(MSTEMP_FNM, ' ', MSTEMP_LNM) PIC,ITH_LUPDT,ITH_LOC, PDPP_BSGRP
        FROM ITH_TBL inner join SER_TBL
        on ITH_SER=SER_ID INNER JOIN MITM_TBL ON ITH_ITMCD=MITM_ITMCD
        INNER JOIN MSTEMP_TBL ON ITH_USRID=MSTEMP_ID
        INNER JOIN XWO ON SER_DOC=PDPP_WONO
        WHERE ITH_WH='AFWH3' AND (ITH_LUPDT BETWEEN ? AND ?)
         AND ITH_FORM='INC-WH-FG' AND ITH_SER LIKE ?
        GROUP BY ITH_ITMCD,MITM_ITMD1, SER_LOTNO, SER_DOC,ITH_SER,concat(MSTEMP_FNM, ' ', MSTEMP_LNM) ,ITH_LUPDT,ITH_LOC, PDPP_BSGRP
        order by ITH_LUPDT ASC ";
        $query = $this->db->query($qry, array($dtfrom, $dtto, '%' . $preff . '%'));
        return $query->result_array();
    }
    public function select_output_whrtn_byreff($dtfrom, $dtto, $preff)
    {
        $qry = "SELECT ITH_ITMCD,MITM_ITMD1, USEQTY ITH_QTY,SER_LOTNO, vra.ITH_DOC SER_DOC,ITH_SER,concat(MSTEMP_FNM, ' ', MSTEMP_LNM) PIC,ITH_LUPDT, MBSG_BSGRP
        FROM ITH_TBL inner join SER_TBL
        on ITH_SER=SER_ID INNER JOIN MITM_TBL ON ITH_ITMCD=MITM_ITMCD
        INNER JOIN MSTEMP_TBL ON ITH_USRID=MSTEMP_ID
        left join (select ITH_REMARK EXTLBL, ABS(SUM(ITH_QTY)) USEQTY,ITH_DOC from ITH_TBL
        WHERE  ITH_WH='AFQART' AND ITH_QTY<0 AND ITH_REMARK IS NOT NULL
        GROUP BY ITH_REMARK,ITH_DOC) vra on ITH_SER=EXTLBL
        left join XVU_RTN on vra.ITH_DOC=STKTRND1_DOCNO
        WHERE (ITH_LUPDT BETWEEN ? AND ?)
         AND ITH_FORM='INC-WHRTN-FG' AND ITH_SER LIKE ?
        order by ITH_LUPDT ASC ";
        $query = $this->db->query($qry, [$dtfrom, $dtto, '%' . $preff . '%']);
        return $query->result_array();
    }

    public function select_output_wh_byassy_bg($dtfrom, $dtto, $assyno, $pbg)
    {
        $qry = "SELECT ITH_ITMCD,MITM_ITMD1, ITH_QTY,SER_LOTNO, SER_DOC,ITH_SER,concat(MSTEMP_FNM, ' ', MSTEMP_LNM) PIC,ITH_LUPDT,ITH_LOC, PDPP_BSGRP
        FROM ITH_TBL inner join SER_TBL
        on ITH_SER=SER_ID INNER JOIN MITM_TBL ON ITH_ITMCD=MITM_ITMCD
        LEFT JOIN MSTEMP_TBL ON ITH_USRID=MSTEMP_ID
        LEFT JOIN XWO ON SER_DOC=PDPP_WONO  AND SER_BSGRP=PDPP_BSGRP
        WHERE ITH_WH='AFWH3' AND (ITH_LUPDT BETWEEN ? AND ?)
         AND ITH_FORM='INC-WH-FG' AND ITH_ITMCD LIKE ? and PDPP_BSGRP = ?
        order by ITH_LUPDT ASC ";
        $query = $this->db->query($qry, [$dtfrom, $dtto, '%' . $assyno . '%', $pbg]);
        return $query->result_array();
    }
    public function select_output_whrtn_byassy_bg($dtfrom, $dtto, $assyno, $pbg)
    {
        $qry = "SELECT ITH_ITMCD,MITM_ITMD1, USEQTY ITH_QTY,SER_LOTNO, vra.ITH_DOC SER_DOC,ITH_SER,concat(MSTEMP_FNM, ' ', MSTEMP_LNM) PIC,ITH_LUPDT, MBSG_BSGRP
        FROM ITH_TBL inner join SER_TBL
        on ITH_SER=SER_ID INNER JOIN MITM_TBL ON ITH_ITMCD=MITM_ITMCD
        INNER JOIN MSTEMP_TBL ON ITH_USRID=MSTEMP_ID
        left join (select ITH_REMARK EXTLBL, ABS(SUM(ITH_QTY)) USEQTY,ITH_DOC from ITH_TBL
        WHERE  ITH_WH='AFQART' AND ITH_QTY<0 AND ITH_REMARK IS NOT NULL
        GROUP BY ITH_REMARK,ITH_DOC) vra on ITH_SER=EXTLBL
        left join XVU_RTN on vra.ITH_DOC=STKTRND1_DOCNO
        WHERE (ITH_LUPDT BETWEEN ? AND ?)
         AND ITH_FORM IN ('INC-WHRTN-FG','INC-WHRTN-FG-C') AND ITH_ITMCD LIKE ? and MBSG_BSGRP = ?
        order by ITH_LUPDT ASC ";
        $query = $this->db->query($qry, [$dtfrom, $dtto, '%' . $assyno . '%', $pbg]);
        return $query->result_array();
    }

    public function select_output_wh_byjob_bg($dtfrom, $dtto, $pjob, $pbg)
    {
        $qry = "SELECT ITH_ITMCD,MITM_ITMD1, ITH_QTY,SER_LOTNO, SER_DOC,ITH_QTY,ITH_SER,concat(MSTEMP_FNM, ' ', MSTEMP_LNM) PIC,ITH_LUPDT,ITH_LOC, PDPP_BSGRP
         FROM ITH_TBL inner join SER_TBL
        on ITH_SER=SER_ID INNER JOIN MITM_TBL ON ITH_ITMCD=MITM_ITMCD
        LEFT JOIN MSTEMP_TBL ON ITH_USRID=MSTEMP_ID
        LEFT JOIN XWO ON SER_DOC=PDPP_WONO  AND SER_BSGRP=PDPP_BSGRP
        WHERE ITH_WH='AFWH3' AND (ITH_LUPDT BETWEEN ? AND ?)
         AND ITH_FORM='INC-WH-FG' AND SER_DOC LIKE ? and PDPP_BSGRP = ?
        order by ITH_LUPDT ASC ";
        $query = $this->db->query($qry, [$dtfrom, $dtto, '%' . $pjob . '%', $pbg]);
        return $query->result_array();
    }
    public function select_output_whrtn_byjob_bg($dtfrom, $dtto, $pjob, $pbg)
    {
        $qry = "SELECT ITH_ITMCD,MITM_ITMD1, USEQTY ITH_QTY,SER_LOTNO, vra.ITH_DOC SER_DOC,ITH_SER,concat(MSTEMP_FNM, ' ', MSTEMP_LNM) PIC,ITH_LUPDT, MBSG_BSGRP
        FROM ITH_TBL inner join SER_TBL
        on ITH_SER=SER_ID INNER JOIN MITM_TBL ON ITH_ITMCD=MITM_ITMCD
        INNER JOIN MSTEMP_TBL ON ITH_USRID=MSTEMP_ID
        left join (select ITH_REMARK EXTLBL, ABS(SUM(ITH_QTY)) USEQTY,ITH_DOC from ITH_TBL
        WHERE  ITH_WH='AFQART' AND ITH_QTY<0 AND ITH_REMARK IS NOT NULL
        GROUP BY ITH_REMARK,ITH_DOC) vra on ITH_SER=EXTLBL
        left join XVU_RTN on vra.ITH_DOC=STKTRND1_DOCNO
        WHERE (ITH_LUPDT BETWEEN ? AND ?)
         AND ITH_FORM IN ('INC-WHRTN-FG','INC-WHRTN-FG-C') AND vra.ITH_DOC LIKE ? and MBSG_BSGRP = ?
        order by ITH_LUPDT ASC ";
        $query = $this->db->query($qry, [$dtfrom, $dtto, '%' . $pjob . '%', $pbg]);
        return $query->result_array();
    }

    public function select_output_wh_byreff_bg($dtfrom, $dtto, $preff, $pbg)
    {
        $qry = "SELECT ITH_ITMCD,MITM_ITMD1, ITH_QTY,SER_LOTNO, SER_DOC,ITH_QTY,ITH_SER,concat(MSTEMP_FNM, ' ', MSTEMP_LNM) PIC,ITH_LUPDT,ITH_LOC, PDPP_BSGRP
        FROM ITH_TBL inner join SER_TBL
        on ITH_SER=SER_ID INNER JOIN MITM_TBL ON ITH_ITMCD=MITM_ITMCD
        LEFT JOIN MSTEMP_TBL ON ITH_USRID=MSTEMP_ID
        LEFT JOIN XWO ON SER_DOC=PDPP_WONO  AND SER_BSGRP=PDPP_BSGRP
        WHERE ITH_WH='AFWH3' AND (ITH_LUPDT BETWEEN ? AND ?)
         AND ITH_FORM='INC-WH-FG' AND ITH_SER LIKE ? and PDPP_BSGRP = ?
        order by ITH_LUPDT ASC ";
        $query = $this->db->query($qry, [$dtfrom, $dtto, '%' . $preff . '%', $pbg]);
        return $query->result_array();
    }
    public function select_output_whrtn_byreff_bg($dtfrom, $dtto, $preff, $pbg)
    {
        $qry = "SELECT ITH_ITMCD,MITM_ITMD1, USEQTY ITH_QTY,SER_LOTNO, vra.ITH_DOC SER_DOC,ITH_SER,concat(MSTEMP_FNM, ' ', MSTEMP_LNM) PIC,ITH_LUPDT, MBSG_BSGRP
        FROM ITH_TBL inner join SER_TBL
        on ITH_SER=SER_ID INNER JOIN MITM_TBL ON ITH_ITMCD=MITM_ITMCD
        INNER JOIN MSTEMP_TBL ON ITH_USRID=MSTEMP_ID
        left join (select ITH_REMARK EXTLBL, ABS(SUM(ITH_QTY)) USEQTY,ITH_DOC from ITH_TBL
        WHERE  ITH_WH='AFQART' AND ITH_QTY<0 AND ITH_REMARK IS NOT NULL
        GROUP BY ITH_REMARK,ITH_DOC) vra on ITH_SER=EXTLBL
        left join XVU_RTN on vra.ITH_DOC=STKTRND1_DOCNO
        WHERE (ITH_LUPDT BETWEEN ? AND ?)
         AND ITH_FORM IN ('INC-WHRTN-FG','INC-WHRTN-FG-C') AND ITH_SER LIKE ? and MBSG_BSGRP = ?
        order by ITH_LUPDT ASC ";
        $query = $this->db->query($qry, [$dtfrom, $dtto, '%' . $preff . '%', $pbg]);
        return $query->result_array();
    }

    public function select_output_wh_byassy_bgxp($dtfrom, $dtto, $assyno, $pbg)
    {
        $qry = "SELECT ITH_ITMCD,MITM_ITMD1, ITH_QTY,SER_LOTNO, SER_DOC,ITH_QTY,ITH_SER,concat(MSTEMP_FNM, ' ', MSTEMP_LNM) PIC,ITH_LUPDT,ITH_LOC, PDPP_BSGRP,convert(int,SER_QTY) SER_QTY
        FROM ITH_TBL inner join SER_TBL
        on ITH_SER=SER_ID INNER JOIN MITM_TBL ON ITH_ITMCD=MITM_ITMCD
        INNER JOIN MSTEMP_TBL ON ITH_USRID=MSTEMP_ID
        LEFT JOIN XWO ON SER_DOC=PDPP_WONO and ITH_ITMCD=PDPP_MDLCD AND SER_BSGRP=PDPP_BSGRP
        WHERE ITH_WH='AFWH3' AND (ITH_LUPDT BETWEEN ? AND ?)
        AND ITH_QTY > 0 AND ITH_FORM='INC-WH-FG' AND ITH_ITMCD LIKE ? and PDPP_BSGRP = ? and ITH_EXPORTED IS NULL
        order by ITH_LUPDT ASC ";
        $query = $this->db->query($qry, array($dtfrom, $dtto, '%' . $assyno . '%', $pbg));
        return $query->result_array();
    }

    public function select_output_wh_byassy_bgxp_test($dtfrom, $dtto, $assyno, $pbg)
    {
        $qry = "SELECT ITH_ITMCD,MITM_ITMD1, ITH_QTY,SER_LOTNO, SER_DOC,ITH_QTY,ITH_SER,concat(MSTEMP_FNM, ' ', MSTEMP_LNM) PIC,ITH_LUPDT,ITH_LOC, PDPP_BSGRP,convert(int,SER_QTY) SER_QTY
        FROM ITH_TBL inner join SER_TBL
        on ITH_SER=SER_ID INNER JOIN MITM_TBL ON ITH_ITMCD=MITM_ITMCD
        INNER JOIN MSTEMP_TBL ON ITH_USRID=MSTEMP_ID
        INNER JOIN XWO ON SER_DOC=PDPP_WONO
        WHERE ITH_WH='AFWH3' AND (ITH_LUPDT BETWEEN ? AND ?)
        AND ITH_QTY > 0 AND ITH_FORM='INC-WH-FG' AND ITH_ITMCD LIKE ? and PDPP_BSGRP = ?
        order by ITH_LUPDT ASC ";
        $query = $this->db->query($qry, array($dtfrom, $dtto, '%' . $assyno . '%', $pbg));
        return $query->result_array();
    }

    public function select_output_wh_byjob_bgxp($dtfrom, $dtto, $pjob, $pbg)
    {
        $qry = "SELECT ITH_ITMCD,MITM_ITMD1, ITH_QTY,SER_LOTNO, SER_DOC,ITH_QTY,ITH_SER,concat(MSTEMP_FNM, ' ', MSTEMP_LNM) PIC,ITH_LUPDT,ITH_LOC, PDPP_BSGRP,convert(int,SER_QTY) SER_QTY
         FROM ITH_TBL inner join SER_TBL
        on ITH_SER=SER_ID INNER JOIN MITM_TBL ON ITH_ITMCD=MITM_ITMCD
        INNER JOIN MSTEMP_TBL ON ITH_USRID=MSTEMP_ID
        LEFT JOIN XWO ON SER_DOC=PDPP_WONO and  ITH_ITMCD=PDPP_MDLCD AND SER_BSGRP=PDPP_BSGRP
        WHERE ITH_WH='AFWH3' AND (ITH_LUPDT BETWEEN ? AND ?)
         AND ITH_FORM='INC-WH-FG' AND SER_DOC LIKE ? and PDPP_BSGRP = ? and ITH_EXPORTED IS NULL
        order by ITH_LUPDT ASC ";
        $query = $this->db->query($qry, array($dtfrom, $dtto, '%' . $pjob . '%', $pbg));
        return $query->result_array();
    }

    public function select_output_wh_byreff_bgxp($dtfrom, $dtto, $preff, $pbg)
    {
        $qry = "SELECT ITH_ITMCD,MITM_ITMD1, ITH_QTY,SER_LOTNO, SER_DOC,ITH_QTY,ITH_SER,concat(MSTEMP_FNM, ' ', MSTEMP_LNM) PIC,ITH_LUPDT,ITH_LOC, PDPP_BSGRP,convert(int,SER_QTY) SER_QTY
        FROM ITH_TBL inner join SER_TBL
        on ITH_SER=SER_ID INNER JOIN MITM_TBL ON ITH_ITMCD=MITM_ITMCD
        LEFT JOIN MSTEMP_TBL ON ITH_USRID=MSTEMP_ID
        LEFT JOIN XWO ON SER_DOC=PDPP_WONO and ITH_ITMCD=PDPP_MDLCD AND SER_BSGRP=PDPP_BSGRP
        WHERE ITH_WH='AFWH3' AND (ITH_LUPDT BETWEEN ? AND ?)
         AND ITH_FORM='INC-WH-FG' AND ITH_SER LIKE ? and PDPP_BSGRP = ? and ITH_EXPORTED IS NULL
        order by ITH_LUPDT ASC ";
        $query = $this->db->query($qry, [$dtfrom, $dtto, '%' . $preff . '%', $pbg]);
        return $query->result_array();
    }

    public function select_output_qcsa_byreff_bgxp($dtfrom, $dtto, $pbg)
    {
        $qry = "SELECT ITH_ITMCD,MITM_ITMD1, ITH_QTY,SER_LOTNO, SER_DOC,ITH_QTY,ITH_SER,concat(MSTEMP_FNM, ' ', MSTEMP_LNM) PIC,ITH_LUPDT,ITH_LOC, PDPP_BSGRP
        FROM ITH_TBL inner join SER_TBL
        on ITH_SER=SER_ID INNER JOIN MITM_TBL ON ITH_ITMCD=MITM_ITMCD
        INNER JOIN MSTEMP_TBL ON ITH_USRID=MSTEMP_ID
        INNER JOIN XWO ON SER_DOC=PDPP_WONO
        WHERE ITH_WH='AWIP1' AND (ITH_LUPDT BETWEEN ? AND ?)
         AND ITH_FORM='INC' and PDPP_BSGRP = ? and ITH_EXPORTED IS NULL
        order by ITH_LUPDT ASC ";
        $query = $this->db->query($qry, [$dtfrom, $dtto, $pbg]);
        return $query->result_array();
    }

    public function select_qcwh_unscan()
    {
        $qry = "exec sp_qcwh_unscan ";
        $query = $this->db->query($qry);
        return $query->result_array();
    }

    public function select_active_ser($pser)
    {
        $qry = "exec sp_laststatus_ser ?";
        $query = $this->db->query($qry, array($pser));
        return $query->result_array();
    }

    public function deletebyID($parr)
    {
        $this->db->where($parr);
        $this->db->delete($this->TABLENAME);
        return $this->db->affected_rows();
    }

    public function deletePRD($pid)
    {
        $this->db->where('ITH_SER', $pid)->where('ITH_QTY < 0')->where('ITH_WH', 'ARPRD1');
        $this->db->delete($this->TABLENAME);
        return $this->db->affected_rows();
    }

    public function tobin($puser, $pser)
    {
        $qry = "INSERT INTO ITH_BIN SELECT *,'DELETE', ?, getdate() FROM ITH_TBL WHERE ITH_SER=?";
        $this->db->query($qry, array($puser, $pser));
        return $this->db->affected_rows();
    }
    public function tobin_backdate($puser, $pser, $pform)
    {
        $qry = "INSERT INTO ITH_BIN SELECT *,'BACKDATE', ?, getdate() FROM ITH_TBL WHERE ITH_SER=? AND ITH_FORM=?";
        $this->db->query($qry, [$puser, $pser, $pform]);
        return $this->db->affected_rows();
    }

    public function tobin_info($info, $puser, $pser)
    {
        $qry = "INSERT INTO ITH_BIN SELECT *,?, ?, getdate() FROM ITH_TBL WHERE ITH_SER=?";
        $this->db->query($qry, array($info, $puser, $pser));
        return $this->db->affected_rows();
    }

    public function select_fg_location_ploc($pitem)
    {
        $qry = "select ITH_LOC,SER_ITMID,SUM(ITH_QTY) TTLQTY, COUNT(*) TTLLBL, MAX(LTS_TIME) XTIME from vr_vis_fg
        where SER_ITMID LIKE ?
        group by ITH_LOC , SER_ITMID ";
        $query = $this->db->query($qry, array('%' . $pitem . '%'));
        return $query->result_array();
    }

    public function selectstock_ser($pser)
    {
        $qry = "exec sp_getstock_ser_wh ?";
        $query = $this->db->query($qry, $pser);
        return $query->result_array();
    }

    public function selectstock_ser_rtn($pser)
    {
        $qry = "wms_sp_getstock_ser_wh_rtn ?";
        $query = $this->db->query($qry, $pser);
        return $query->result_array();
    }

    public function select_qcwh_unscan_recap()
    {
        $qry = "exec sp_qcwh_unscan_recap ";
        $query = $this->db->query($qry);
        return $query->result_array();
    }
    public function select_qcwh_unscan_recap_lastscan()
    {
        $qry = "exec sp_qcwh_unscan_recap_lastscan ";
        $query = $this->db->query($qry);
        return $query->result_array();
    }

    public function select_txhistory_bef($pwh, $passy, $pdt1)
    {
        $qry = "SELECT UPPER(V2.ITH_ITMCD) ITH_ITMCD,RTRIM(MITM_ITMD1) MITM_ITMD1,ISNULL(BALQTY,0) BALQTY,RTRIM(MITM_STKUOM) UOM FROM
        (SELECT ITH_ITMCD,SUM(ITH_QTY) BALQTY
                FROM v_ith_tblc
                WHERE ITH_WH=? and ITH_ITMCD like ? and ITH_DATEC < ?
                GROUP BY ITH_ITMCD) V1
        RIGHT JOIN (
            SELECT ITH_ITMCD from v_ith_tblc
            WHERE ITH_WH=? and ITH_ITMCD like ?
            GROUP BY ITH_ITMCD
        ) V2 ON V1.ITH_ITMCD=V2.ITH_ITMCD
        LEFT JOIN MITM_TBL ON V2.ITH_ITMCD=MITM_ITMCD
        WHERE V2.ITH_ITMCD!=''
        ORDER BY V2.ITH_ITMCD";
        $query = $this->db->query($qry, [$pwh, "%$passy%", $pdt1, $pwh, "%$passy%"]);
        return $query->result_array();
    }
    public function selectTXHistoryBeforeByItemDescription($pwh, $passy, $pdt1)
    {
        $qry = "SELECT UPPER(ITH_ITMCD) ITH_ITMCD,RTRIM(MITM_ITMD1) MITM_ITMD1,ISNULL(BALQTY,0) BALQTY,RTRIM(MITM_STKUOM) UOM FROM
        (SELECT ITH_ITMCD,SUM(ITH_QTY) BALQTY,MITM_ITMD1,MITM_STKUOM
                FROM v_ith_tblc
                LEFT JOIN MITM_TBL ON ITH_ITMCD=MITM_ITMCD
                WHERE ITH_WH=? and MITM_ITMD1 like ? and ITH_DATEC < ?
                GROUP BY ITH_ITMCD,MITM_ITMD1,MITM_STKUOM) V1
        ORDER BY ITH_ITMCD";
        $query = $this->db->query($qry, [$pwh, "%$passy%", $pdt1]);
        return $query->result_array();
    }

    public function select_available_wo($pDate, $pLocation)
    {
        $qry = "SELECT SER_DOC FROM
        (select ITH_SER, sum(ITH_QTY) STKQT from v_ith_tblc
        where ITH_DATEC<=?
        and ITH_WH=?
        group by ITH_SER
        having sum(ITH_QTY) > 0
        ) VITH LEFT JOIN SER_TBL ON ITH_SER=SER_ID
        GROUP BY SER_DOC
        ORDER BY SER_DOC";
        $query = $this->db->query($qry, [$pDate, $pLocation]);
        return $query->result_array();
    }

    public function select_txhistory_bef_parent($pwh, $passy, $pdt1)
    {
        $qry = "select VMEGA.*,WQT from
        (SELECT  RTRIM(ITRN_ITMCD) ITRN_ITMCD,SUM(CASE WHEN ITRN_IOFLG = '1' THEN ITRN_TRNQT ELSE -1*ITRN_TRNQT END) MGAQTY
                                FROM XITRN_TBL
                                WHERE ITRN_ISUDT<'$pdt1' AND ITRN_LOCCD='$pwh' AND ITRN_ITMCD=?
                                GROUP BY ITRN_ITMCD) VMEGA
        LEFT JOIN
        (
        SELECT ITH_ITMCD,SUM(ITH_QTY) WQT FROM v_ith_tblc WHERE ITH_DATEC<'$pdt1' AND ITH_WH='$pwh' AND ITH_ITMCD=?
        GROUP BY ITH_ITMCD
        ) VWMS ON ITRN_ITMCD=ITH_ITMCD
        ORDER BY ITRN_ITMCD";
        $query = $this->db->query($qry, [$passy, $passy]);
        return $query->result_array();
    }

    public function select_txhistory_bef_parent_fg($pwh, $passy, $pdt1)
    {
        $qry = "select VMEGA.*,WQT from
        (SELECT  RTRIM(FTRN_ITMCD) ITRN_ITMCD,SUM(CASE WHEN FTRN_IOFLG = '1' THEN FTRN_TRNQT ELSE -1*FTRN_TRNQT END) MGAQTY
                                FROM XFTRN_TBL
                                WHERE FTRN_ISUDT<'$pdt1' AND FTRN_LOCCD='$pwh' AND FTRN_ITMCD=?
                                GROUP BY FTRN_ITMCD) VMEGA
        LEFT JOIN
        (
        SELECT ITH_ITMCD,SUM(ITH_QTY) WQT FROM v_ith_tblc WHERE ITH_DATEC<'$pdt1' AND ITH_WH='$pwh' AND ITH_ITMCD=?
        GROUP BY ITH_ITMCD
        ) VWMS ON ITRN_ITMCD=ITH_ITMCD
        ORDER BY ITRN_ITMCD";
        $query = $this->db->query($qry, [$passy, $passy]);
        return $query->result_array();
    }
    public function select_txhistory_bef_parent_fg_with_additional_wh($pwh, $passy, $pdt1, $awh)
    {
        $additional_wh = "'" . implode("','", $awh) . "'";
        $qry = "select ISNULL(ITRN_ITMCD,ITH_ITMCD) ITRN_ITMCD,ISNULL(MGAQTY,0) MGAQTY,WQT from
        (SELECT  RTRIM(FTRN_ITMCD) ITRN_ITMCD,SUM(CASE WHEN FTRN_IOFLG = '1' THEN FTRN_TRNQT ELSE -1*FTRN_TRNQT END) MGAQTY
                                FROM XFTRN_TBL
                                WHERE FTRN_ISUDT<'$pdt1' AND FTRN_LOCCD='$pwh' AND FTRN_ITMCD=?
                                GROUP BY FTRN_ITMCD) VMEGA
        FULL JOIN
        (
        SELECT ITH_ITMCD,SUM(ITH_QTY) WQT FROM v_ith_tblc WHERE ITH_DATEC<'$pdt1' AND ITH_WH IN ($additional_wh) AND ITH_ITMCD=?
        GROUP BY ITH_ITMCD
        ) VWMS ON ITRN_ITMCD=ITH_ITMCD
        ORDER BY ITRN_ITMCD";
        $query = $this->db->query($qry, [$passy, $passy]);
        return $query->result_array();
    }
    public function select_txhistory_customs_bef_d1($pitemcd, $pdt1)
    {
        $qry = "SELECT RTRIM(RPSTOCK_ITMNUM) RPSTOCK_ITMNUM,RPSTOCK_NOAJU,RPSTOCK_BCNUM,RPSTOCK_QTY BALQTY,RTRIM(MITM_ITMD1) MITM_ITMD1,RPSTOCK_DOC,FORMAT(IODATE,'dd-MMM-yy') INCDATE FROM ZRPSAL_BCSTOCK
        LEFT JOIN MITM_TBL ON RPSTOCK_ITMNUM=MITM_ITMCD
        WHERE RPSTOCK_TYPE='INC' AND RPSTOCK_ITMNUM LIKE ? AND IODATE>=? AND deleted_at IS NULL
        ORDER BY IODATE,RPSTOCK_NOAJU,1";
        $query = $this->db->query($qry, ["%$pitemcd%", $pdt1]);
        return $query->result_array();
    }
    public function select_txhistory_customs_bef_d1_byaju($paju)
    {
        $qry = "SELECT RTRIM(RPSTOCK_ITMNUM) RPSTOCK_ITMNUM,RPSTOCK_NOAJU,RPSTOCK_BCNUM,SUM(RPSTOCK_QTY) BALQTY,RTRIM(MITM_ITMD1) MITM_ITMD1,RPSTOCK_DOC,FORMAT(MIN(IODATE),'dd-MMM-yy') INCDATE FROM ZRPSAL_BCSTOCK
        LEFT JOIN MITM_TBL ON RPSTOCK_ITMNUM=MITM_ITMCD
        WHERE RPSTOCK_TYPE!='INC-DO' AND RPSTOCK_NOAJU LIKE ?
        GROUP BY RTRIM(RPSTOCK_ITMNUM),RPSTOCK_NOAJU,RPSTOCK_BCNUM,MITM_ITMD1,RPSTOCK_DOC
        ORDER BY MIN(IODATE),RPSTOCK_NOAJU,1";
        $query = $this->db->query($qry, ["%$paju%"]);
        return $query->result_array();
    }
    public function select_txhistory_customs_bef_d1_bydaftar($pdaftar, $pdt1)
    {
        $qry = "SELECT RTRIM(RPSTOCK_ITMNUM) RPSTOCK_ITMNUM,RPSTOCK_NOAJU,RPSTOCK_BCNUM,SUM(RPSTOCK_QTY) BALQTY,RTRIM(MITM_ITMD1) MITM_ITMD1,RPSTOCK_DOC,FORMAT(MIN(IODATE),'dd-MMM-yy') INCDATE FROM ZRPSAL_BCSTOCK
        LEFT JOIN MITM_TBL ON RPSTOCK_ITMNUM=MITM_ITMCD
        WHERE RPSTOCK_TYPE!='INC-DO' AND RPSTOCK_BCNUM LIKE ? AND IODATE>=?
        GROUP BY RTRIM(RPSTOCK_ITMNUM),RPSTOCK_NOAJU,RPSTOCK_BCNUM,MITM_ITMD1,RPSTOCK_DOC
        ORDER BY MIN(IODATE),RPSTOCK_NOAJU,1";
        $query = $this->db->query($qry, ["%$pdaftar%", $pdt1]);
        return $query->result_array();
    }

    public function select_txhistory($pwh, $passy, $pdt1, $pdt2)
    {
        $qry = "SELECT UPPER(ITH_ITMCD) ITH_ITMCD,rtrim(MITM_ITMD1) MITM_ITMD1,ITH_FORM,ITH_DOC,FORMAT(ITH_DATEC,'dd-MMM-yy') ITH_DATEKU,
                ISNULL(SUM(CASE WHEN ITH_QTY>0 THEN ITH_QTY END),0) INCQTY ,
                ISNULL(SUM(CASE WHEN ITH_QTY<0 THEN ITH_QTY END),0) OUTQTY ,
                0 ITH_BAL,MAX(ITH_LUPDT) ITH_LUPDT,MIN(ITH_REMARK) ITH_REMARK ,
                ITH_DATEC
            FROM v_ith_tblc LEFT JOIN MITM_TBL ON ITH_ITMCD=MITM_ITMCD
            WHERE ITH_WH=? and ITH_ITMCD like ? AND  ITH_DATEC between ? and ?
                GROUP BY ITH_ITMCD, rtrim(MITM_ITMD1),ITH_DATEC,ITH_FORM,ITH_DOC,FORMAT(ITH_LUPDT, 'yyyy-MM-dd HH:mm')
                ORDER BY ITH_ITMCD,ITH_DATEC ,FORMAT(ITH_LUPDT, 'yyyy-MM-dd HH:mm'), 6 DESC";
        $query = $this->db->query($qry, [$pwh, "%$passy%", $pdt1, $pdt2]);
        return $query->result_array();
    }

    public function selectTXHistoryByDescription($pwh, $passy, $pdt1, $pdt2)
    {
        $qry = "SELECT UPPER(ITH_ITMCD) ITH_ITMCD
                    ,rtrim(MITM_ITMD1) MITM_ITMD1
                    ,ITH_FORM
                    ,ITH_DOC
                    ,FORMAT(ITH_DATEC, 'dd-MMM-yy') ITH_DATEKU
                    ,ISNULL(SUM(CASE
                                WHEN ITH_QTY > 0
                                    THEN ITH_QTY
                                END), 0) INCQTY
                    ,ISNULL(SUM(CASE
                                WHEN ITH_QTY < 0
                                    THEN ITH_QTY
                                END), 0) OUTQTY
                    ,0 ITH_BAL
                    ,MAX(ITH_LUPDT) ITH_LUPDT
                    ,MIN(ITH_REMARK) ITH_REMARK
                    ,ITH_DATEC
                FROM v_ith_tblc
                LEFT JOIN MITM_TBL ON ITH_ITMCD = MITM_ITMCD
                WHERE ITH_WH = ?
                    AND MITM_ITMD1 LIKE ?
                    AND ITH_DATEC BETWEEN ?
                        AND ?
                GROUP BY ITH_ITMCD
                    ,rtrim(MITM_ITMD1)
                    ,ITH_DATEC
                    ,ITH_FORM
                    ,ITH_DOC
                    ,FORMAT(ITH_LUPDT, 'yyyy-MM-dd HH:mm')
                ORDER BY ITH_ITMCD
                    ,ITH_DATEC
                    ,FORMAT(ITH_LUPDT, 'yyyy-MM-dd HH:mm')
                    ,6 DESC";
        $query = $this->db->query($qry, [$pwh, "%$passy%", $pdt1, $pdt2]);
        return $query->result_array();
    }

    public function select_txhistory_parent($pwh, $passy, $pdt1, $pdt2)
    {
        $qry = "SELECT ISNULL(ITRN_ITMCD, ITH_ITMCD) ITRN_ITMCD
                    ,ISNULL(ISUDT, ITH_DATEC) ISUDT
                    ,ISNULL(MGAQTY, 0) MGAQTY
                    ,WQT
                FROM (
                    SELECT UPPER(RTRIM(ITRN_ITMCD)) ITRN_ITMCD
                        ,CONVERT(DATE, ITRN_ISUDT) ISUDT
                        ,SUM(CASE
                                WHEN ITRN_IOFLG = '1'
                                    THEN ITRN_TRNQT
                                ELSE - 1 * ITRN_TRNQT
                                END) MGAQTY
                    FROM XITRN_TBL
                    WHERE (
                            ITRN_ISUDT BETWEEN '$pdt1'
                                AND '$pdt2'
                            )
                        AND ITRN_LOCCD = '$pwh'
                        AND ITRN_ITMCD = ?
                    GROUP BY ITRN_ITMCD
                        ,ITRN_ISUDT
                    ) VMEGA
                FULL JOIN (
                    SELECT UPPER(RTRIM(ITH_ITMCD)) ITH_ITMCD
                        ,ITH_DATEC
                        ,SUM(ITH_QTY) WQT
                    FROM v_ith_tblc
                    WHERE (
                            ITH_DATEC BETWEEN '$pdt1'
                                AND '$pdt2'
                            )
                        AND ITH_WH = '$pwh'
                        AND ITH_ITMCD = ?
                    GROUP BY ITH_ITMCD
                        ,ITH_DATEC
                    ) VWMS ON ITRN_ITMCD = ITH_ITMCD
                    AND ISUDT = ITH_DATEC
                ORDER BY ISUDT";
        $query = $this->db->query($qry, [$passy, $passy]);
        return $query->result_array();
    }
    public function select_txhistory_parent_fg($pwh, $passy, $pdt1, $pdt2)
    {
        $qry = "select ISNULL(ITRN_ITMCD,ITH_ITMCD) ITRN_ITMCD,ISNULL(ISUDT,ITH_DATEC) ISUDT ,ISNULL(MGAQTY,0) MGAQTY,WQT from
        (SELECT  RTRIM(FTRN_ITMCD) ITRN_ITMCD,CONVERT(DATE,FTRN_ISUDT) ISUDT,SUM(CASE WHEN FTRN_IOFLG = '1' THEN FTRN_TRNQT ELSE -1*FTRN_TRNQT END) MGAQTY
                                FROM XFTRN_TBL
                                WHERE (FTRN_ISUDT BETWEEN '$pdt1' AND '$pdt2') AND FTRN_LOCCD='$pwh' AND FTRN_ITMCD=?
                                GROUP BY FTRN_ITMCD,FTRN_ISUDT) VMEGA
        FULL JOIN
        (
        SELECT ITH_ITMCD,ITH_DATEC,SUM(ITH_QTY) WQT FROM v_ith_tblc WHERE (ITH_DATEC BETWEEN '$pdt1' AND '$pdt2') AND ITH_WH='$pwh' AND ITH_ITMCD=?
        GROUP BY ITH_ITMCD,ITH_DATEC
        ) VWMS ON ITRN_ITMCD=ITH_ITMCD AND ISUDT=ITH_DATEC
        ORDER BY ISUDT";
        $query = $this->db->query($qry, [$passy, $passy]);
        return $query->result_array();
    }
    public function select_txhistory_parent_fg_with_additional_wh($pwh, $passy, $pdt1, $pdt2, $awh)
    {
        $additional_wh = "'" . implode("','", $awh) . "'";
        $qry = "select ISNULL(ITRN_ITMCD,ITH_ITMCD) ITRN_ITMCD,ISNULL(ISUDT,ITH_DATEC) ISUDT ,ISNULL(MGAQTY,0) MGAQTY,WQT from
        (SELECT  RTRIM(FTRN_ITMCD) ITRN_ITMCD,CONVERT(DATE,FTRN_ISUDT) ISUDT,SUM(CASE WHEN FTRN_IOFLG = '1' THEN FTRN_TRNQT ELSE -1*FTRN_TRNQT END) MGAQTY
                                FROM XFTRN_TBL
                                WHERE (FTRN_ISUDT BETWEEN '$pdt1' AND '$pdt2') AND FTRN_LOCCD='$pwh' AND FTRN_ITMCD=?
                                GROUP BY FTRN_ITMCD,FTRN_ISUDT) VMEGA
        FULL JOIN
        (
        SELECT ITH_ITMCD,ITH_DATEC,SUM(ITH_QTY) WQT FROM v_ith_tblc WHERE (ITH_DATEC BETWEEN '$pdt1' AND '$pdt2') AND ITH_WH IN ($additional_wh) AND ITH_ITMCD=?
        GROUP BY ITH_ITMCD,ITH_DATEC
        ) VWMS ON ITRN_ITMCD=ITH_ITMCD AND ISUDT=ITH_DATEC
        ORDER BY ISUDT";
        $query = $this->db->query($qry, [$passy, $passy]);
        return $query->result_array();
    }
    public function select_txhistory_customs($plike, $pdate)
    {
        $this->db->select("RTRIM(RPSTOCK_ITMNUM) ITMCD,'' RPSTOCK_ITMNUM,RPSTOCK_NOAJU,'' DAFTAR,ISNULL(SUM(CASE WHEN RPSTOCK_QTY>0 THEN RPSTOCK_QTY END),0) INCQTY ,
        ISNULL(SUM(CASE WHEN RPSTOCK_QTY<0  THEN RPSTOCK_QTY END),0) OUTQTY,'' MITM_ITMD1,RPSTOCK_REMARK DOC,FORMAT(IODATE,'dd-MMM-yy') IODATE, 0 BAL,0 HEADER, '' AJU,RPSTOCK_DOC , '' MUSED
        ,MIN(RPSTOCK_BCTYPE) BCTYPE");
        $this->db->from("ZRPSAL_BCSTOCK");
        $this->db->like($plike)->where("IODATE <=", $pdate)->where("deleted_at is null", null, false);
        $this->db->where_not_in('RPSTOCK_TYPE', ['INC-DO', 'INC', 'INC-SCR']);
        $this->db->group_by("RTRIM(RPSTOCK_ITMNUM),RPSTOCK_NOAJU,RPSTOCK_BCNUM,RPSTOCK_DOC,IODATE,RPSTOCK_REMARK");
        $this->db->order_by("FORMAT(IODATE,'yyyy-MM-dd'), RPSTOCK_NOAJU,RPSTOCK_ITMNUM");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_confirmdate($pdoc)
    {
        $this->db->from("(SELECT ITH_DOC,MAX(ITH_LUPDT) ITH_LUPDT FROM ITH_TBL
        WHERE ITH_WH='ARSHP' AND ITH_QTY<0 AND ITH_FORM='OUT-SHP-FG'
        GROUP BY ITH_DOC) VRS");
        $this->db->where("ITH_DOC", $pdoc);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectbyunique($preffno)
    {
        $this->db->select("ITH_TBL.*,CONCAT(MSTEMP_FNM, ' ', MSTEMP_LNM) PIC");
        $this->db->from($this->TABLENAME);
        $this->db->join("MSTEMP_TBL", "ITH_USRID=MSTEMP_ID", "LEFT");
        $this->db->where("ITH_SER", $preffno);
        $this->db->order_by("ITH_DATE,ITH_LUPDT,ITH_LINE,ITH_QTY");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectPSIEquipment($pLike, $date)
    {
        $this->db->select("ITH_WH
        ,RTRIM(ITH_ITMCD) ITH_ITMCD
        ,RTRIM(MITM_SPTNO) MITM_SPTNO
        ,RTRIM(MITM_ITMD1) MITM_ITMD1
        ,SUM(ITH_QTY) EQUIPQT");
        $this->db->from('v_ith_tblc');
        $this->db->join("MITM_TBL", "ITH_ITMCD = MITM_ITMCD", "LEFT");
        $this->db->where('ITH_DATEC <=', $date);
        $this->db->where_in("ITH_WH", ['ENGEQUIP'
            , 'ENGLINEEQUIP'
            , 'MFG1EQUIP'
            , 'MFG2EQUIP'
            , 'PPICEQUIP'
            , 'PRCSCREQUIP'
            , 'PSIEQUIP'
            , 'QAEQUIP'
            , 'FCTEQUIP'
            , 'ICTEQUIP',
        ]);
        $this->db->like($pLike);
        $this->db->group_by("ITH_ITMCD,ITH_WH,MITM_SPTNO,MITM_ITMD1");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectlocation_fg($pitem)
    {
        $qry = "
        select LOC = STUFF((select distinct ',' + ITH_LOC from (
            SELECT v1.*
                    ,ITH_LOC
                    ,SER_ITMID
                FROM (
                    SELECT ITH_WH
                        ,ITH_SER
                        ,SUM(ITH_QTY) ITH_QTY
                        ,MAX(ITH_LUPDT) LTS_TIME
                        ,MAX(isnull(ITH_LINE, '')) ITH_LINE
                    FROM ITH_TBL
                    WHERE ITH_WH = 'AFWH3' and ITH_ITMCD=?
                    GROUP BY ITH_WH
                        ,ITH_SER
                    HAVING SUM(ITH_QTY) > 0
                    ) v1
                LEFT JOIN ITH_TBL a ON v1.ITH_SER = a.ITH_SER
                    AND a.ITH_WH = v1.ITH_WH
                    AND isnull(v1.LTS_TIME, GETDATE()) = isnull(a.ITH_LUPDT, GETDATE())
                    AND a.ITH_QTY > 0
                LEFT JOIN SER_TBL ON a.ITH_SER = SER_ID

            ) vd where  ITH_LOC is not null for XML PATH('')
            ),1,1,'')
        ";
        $query = $this->db->query($qry, [$pitem]);
        return $query->result_array();
    }
    public function selectlocation_fg_NFWH4RT($pitem)
    {
        $qry = "select LOC = STUFF((select distinct ',' + ITH_LOC from vr_vis_fg_NFWH4RT vd where vd.SER_ITMID=? and ITH_LOC is not null for XML PATH('')),1,1,'')";
        $query = $this->db->query($qry, [$pitem]);
        return $query->result_array();
    }
    public function selectlocation_fg_AFWH3RT($pitem)
    {
        $qry = "select LOC = STUFF((select distinct ',' + ITH_LOC from vr_vis_fg_AFWH3RT vd where vd.SER_ITMID=? and ITH_LOC is not null for XML PATH('')),1,1,'')";
        $query = $this->db->query($qry, [$pitem]);
        return $query->result_array();
    }

    public function select_ser_stock($pser, $pwh)
    {
        $qry = "SELECT ITH_SER, SUM(ITH_QTY) TSOCK FROM ITH_TBL WHERE ITH_SER=? AND ITH_WH=?
            GROUP BY ITH_SER HAVING SUM(ITH_QTY)>0";
        $query = $this->db->query($qry, [$pser, $pwh]);
        return $query->result_array();
    }

    public function insert_si_scan($pitem, $pform, $pdoc, $pqty, $pwh, $ploc, $pser, $puserid)
    {
        $qry = "INSERT INTO ITH_TBL (ITH_ITMCD, ITH_DATE, ITH_FORM, ITH_DOC,ITH_QTY,
        ITH_WH,ITH_LOC,ITH_SER,ITH_LINE,ITH_LUPDT,ITH_USRID)
        VALUES(?,CONVERT(DATE, GETDATE()),?,?,?,
        ?,?,? ,dbo.fun_ithline(), GETDATE(),?)";
        $this->db->query($qry, [
            $pitem, $pform, $pdoc, $pqty,
            $pwh, $ploc, $pser, $puserid,
        ]);
        return $this->db->affected_rows();
    }
    public function insert_si_scan_with_remark($pitem, $pform, $pdoc, $pqty, $pwh, $ploc, $pser, $puserid, $premark)
    {
        $qry = "INSERT INTO ITH_TBL (ITH_ITMCD, ITH_DATE, ITH_FORM, ITH_DOC,ITH_QTY,
        ITH_WH,ITH_LOC,ITH_SER,ITH_LINE,ITH_LUPDT,ITH_USRID,ITH_REMARK)
        VALUES(?,CONVERT(DATE, GETDATE()),?,?,?,
        ?,?,? ,dbo.fun_ithline(), GETDATE(),?, ?)";
        $this->db->query($qry, [
            $pitem, $pform, $pdoc, $pqty,
            $pwh, $ploc, $pser, $puserid, $premark,
        ]);
        return $this->db->affected_rows();
    }

    public function select_rm_null_bo_zeroed($pdate)
    {
        $qry = "SELECT ITH_SER,BEFQTYFG,ITH_WH,SER_DOC,  MAX(SERD2_SER_SMP) SERD2SER_SMP FROM
            (SELECT VBEFFG.*,SER_ITMID,SER_DOC FROM
                (SELECT ITH_SER,SUM(ITH_QTY) BEFQTYFG, ITH_WH FROM v_ith_tblc WHERE  ITH_FORM NOT IN ('SASTART','SA') AND ITH_DATEC<=?
                AND ITH_WH IN ('AFWH3','AWIP1')
                GROUP BY ITH_WH,ITH_SER
                HAVING SUM(ITH_QTY)>0) VBEFFG
                LEFT JOIN (SELECT SERD2_SER FROM SERD2_TBL GROUP BY SERD2_SER) VSERD ON ITH_SER= VSERD.SERD2_SER
                LEFT JOIN SER_TBL ON ITH_SER=SER_ID
                WHERE SERD2_SER IS NULL
            ) VH1
            LEFT JOIN (SELECT SERD2_SER SERD2_SER_SMP, MAX(SERD2_JOB) SERD2JOB FROM SERD2_TBL GROUP BY SERD2_SER) VSERDSMP ON SER_DOC=SERD2JOB
            where SER_DOC NOT like '%-C%'
        GROUP BY  ITH_SER,BEFQTYFG,ITH_WH,SER_DOC";
        $query = $this->db->query($qry, [$pdate]);
        return $query->result_array();
    }

    public function select_rm_null_bo_zeroed_combined($pdate)
    {
        $qry = "SELECT SERD2_ITMCD ITH_ITMCD,SUM(SERD2_QTY) BEFQTY, 0 PLOTQTY, ITH_WH, '' REMARK FROM
        (SELECT ITH_SER,BEFQTYFG,ITH_WH,SER_DOC,  MAX(SERD2_SER_SMP) SERD2SER_SMP FROM
                (SELECT VBEFFG.*,SER_ITMID,SER_DOC FROM
                    (SELECT ITH_SER,SUM(ITH_QTY) BEFQTYFG, ITH_WH FROM v_ith_tblc WHERE  ITH_FORM NOT IN ('SASTART','SA') AND ITH_DATEC<=?
                    AND ITH_WH IN ('AFWH3','AWIP1')
                    GROUP BY ITH_WH,ITH_SER
                    HAVING SUM(ITH_QTY)>0) VBEFFG
                    LEFT JOIN (SELECT SERD2_SER FROM SERD2_TBL GROUP BY SERD2_SER) VSERD ON ITH_SER= VSERD.SERD2_SER
                    LEFT JOIN SER_TBL ON ITH_SER=SER_ID
                    WHERE SERD2_SER IS NULL
                    ) VH1
                    LEFT JOIN (SELECT SERD2_SER SERD2_SER_SMP, MAX(SERD2_JOB) SERD2JOB FROM SERD2_TBL GROUP BY SERD2_SER) VSERDSMP ON SER_DOC=SERD2JOB
                where SER_DOC like '%-C%'
                GROUP BY  ITH_SER,BEFQTYFG,ITH_WH,SER_DOC)
        VJ LEFT JOIN SERC_TBL ON ITH_SER=SERC_NEWID
        LEFT JOIN SERD2_TBL ON SERC_COMID=SERD2_SER
        GROUP BY SERD2_ITMCD,ITH_WH";
        $query = $this->db->query($qry, [$pdate]);
        return $query->result_array();
    }

    public function select_rm_in_fg($item, $lotno, $stock)
    {
        $qry = "wms_sp_rm_in_fg ?, ?, ?";
        $query = $this->db->query($qry, [$item, $lotno, $stock]);
        return $query->result_array();
    }

    public function select_RMFromDeliveredFG($pdate1, $pdate2)
    {
        $qry = "SELECT SERD2_ITMCD ITH_ITMCD
                ,SUM(SERD2_QTY) BEFQTY
            FROM (
                SELECT ITH_SER
                FROM v_ith_tblc
                WHERE ITH_DATEC BETWEEN ?
                        AND ?
                    AND ITH_WH = 'ARSHP'
                    AND ITH_QTY < 0
                GROUP BY ITH_SER
                ) VOUTFG
            LEFT JOIN SERD2_TBL ON ITH_SER = SERD2_SER
            LEFT JOIN SER_TBL ON ITH_SER=SER_ID
            WHERE SER_DOC NOT LIKE '%-C%'
            GROUP BY SERD2_ITMCD
            ORDER BY 1";
        $query = $this->db->query($qry, [$pdate1, $pdate2]);
        return $query->result_array();
    }
    public function select_RMFromDeliveredFG_CJ($pdate1, $pdate2)
    {
        #CJ = combined job
        $qry = "SELECT SERD2_ITMCD ITH_ITMCD
                    ,SUM(SERD2_QTY) BEFQTY
                FROM (
                    SELECT ITH_SER
                    FROM v_ith_tblc
                    WHERE ITH_DATEC between ? and ?
                        AND ITH_WH = 'ARSHP'
                        AND ITH_QTY < 0
                    GROUP BY ITH_SER
                    ) VOUTFG
                LEFT JOIN SER_TBL ON ITH_SER=SER_ID
                INNER JOIN SERC_TBL ON ITH_SER = SERC_NEWID
                LEFT JOIN SERD2_TBL ON SERC_COMID=SERD2_SER
                WHERE SER_DOC LIKE '%-C%'
                GROUP BY SERD2_ITMCD
                ORDER BY 1";
        $query = $this->db->query($qry, [$pdate1, $pdate2]);
        return $query->result_array();
    }

    public function select_deliv_invo($pdate1, $pdate2, $bigrup)
    {
        $qry = "SELECT ITH_DATEC,ITH_DOC,DLV_CUSTDO,DLV_CONSIGN,NOMAJU,NOMPEN,INVDT,DLV_INVNO,DLV_SMTINVNO,ITH_ITMCD,ITMDESCD,DLVPRC_CPO,DLVPRC_QTY,DLVPRC_PRC,(DLVPRC_QTY*DLVPRC_PRC) AMOUNT,DLV_SMTINVNO,DLV_SPPBDOC  FROM
        (select ITH_DATEC,max(DLV_CONSIGN) DLV_CONSIGN,max(DLV_INVDT) INVDT,ITH_ITMCD,MAX(RTRIM(MITM_ITMD1)) ITMDESCD,ITH_DOC,ABS(SUM(ITH_QTY)) DELQT,MAX(DLV_INVNO) DLV_INVNO, MAX(DLV_SMTINVNO) DLV_SMTINVNO
            ,MAX(DLV_ZNOMOR_AJU) NOMAJU,max(DLV_NOPEN) NOMPEN,MAX(DLV_CUSTDO) DLV_CUSTDO,MAX(DLV_SPPBDOC) DLV_SPPBDOC from v_ith_tblc
        LEFT JOIN DLV_TBL ON ITH_SER=DLV_SER
        LEFT JOIN MITM_TBL ON ITH_ITMCD=MITM_ITMCD
        where (ITH_DATEC between ? and ?) and ITH_WH IN ('ARSHP','ARSHPRTN2') AND ITH_FORM='OUT-SHP-FG'
        AND DLV_BSGRP IN ($bigrup)
        GROUP BY ITH_ITMCD,ITH_DOC,ITH_DATEC,MITM_ITMD1) VDELV
        LEFT JOIN
        (
        SELECT DLVPRC_TXID,SER_ITMID,DLVPRC_PRC,SUM(DLVPRC_QTY) DLVPRC_QTY,DLVPRC_CPO FROM DLVPRC_TBL
        LEFT JOIN SER_TBL ON DLVPRC_SER=SER_ID
        GROUP BY DLVPRC_TXID,SER_ITMID,DLVPRC_PRC,DLVPRC_CPO
        ) VPRC ON ITH_ITMCD=SER_ITMID AND ITH_DOC=DLVPRC_TXID

        UNION
        SELECT ISNULL(SHIPDATE,DLV_DATE) ITH_DATEC,DLV_ID ITH_DOC,DLV_CUSTDO, DLV_CONSIGN, NOMAJU,
        NOMPEN, INVDT,DLV_INVNO,DLV_SMTINVNO,DLVRMSO_ITMID ITH_ITMCD, RTRIM(MITM_ITMD1) ITMDESCD,DLVRMSO_CPO DLVPRC_CPO,DLVPRC_QTY,DLVRMSO_PRPRC DLVPRC_PRC, DLVPRC_QTY* DLVRMSO_PRPRC AMOUNT,DLV_SMTINVNO,DLV_SPPBDOC
         FROM
        (select DLV_DATE,DLV_ID,DLV_CUSTDO,DLV_CONSIGN,DLV_ZNOMOR_AJU NOMAJU,
        DLV_NOPEN NOMPEN, DLV_INVDT INVDT,DLV_INVNO,DLV_SMTINVNO,MDEL_ZNAMA,MDEL_ADDRCUSTOMS,DLV_BCTYPE,
        DLV_ZJENIS_TPB_TUJUAN,DLV_RPDATE TGLPEN,DLV_PURPOSE,MAX(DLV_SPPBDOC) DLV_SPPBDOC  from DLV_TBL
        left join MDEL_TBL on DLV_CONSIGN=MDEL_DELCD
        WHERE DLV_SER='' AND DLV_BSGRP IN ($bigrup)
        GROUP BY DLV_DATE,DLV_ID,DLV_CUSTDO,DLV_CONSIGN,DLV_ZNOMOR_AJU,
        DLV_NOPEN, DLV_INVDT,DLV_INVNO,DLV_SMTINVNO,MDEL_ZNAMA,MDEL_ADDRCUSTOMS,DLV_BCTYPE,
        DLV_ZJENIS_TPB_TUJUAN,DLV_RPDATE,DLV_PURPOSE) VDLV
        INNER join (
        SELECT DLVRMSO_TXID,DLVRMSO_CPO,DLVRMSO_ITMID,RTRIM(MITM_ITMD1) MITM_ITMD1,sum(DLVRMSO_ITMQT) DLVPRC_QTY,DLVRMSO_PRPRC,rtrim(MITM_STKUOM) MITM_STKUOM FROM DLVRMSO_TBL
        LEFT JOIN MITM_TBL ON DLVRMSO_ITMID=MITM_ITMCD
        group by DLVRMSO_TXID,DLVRMSO_ITMID,MITM_ITMD1,DLVRMSO_PRPRC,MITM_STKUOM,DLVRMSO_CPO) vselect
        on DLV_ID=DLVRMSO_TXID
        LEFT JOIN (SELECT ITH_DOC, MAX(ITH_DATEC) SHIPDATE FROM v_ith_tblc WHERE ITH_FORM='OUT-SHP-RM' GROUP BY ITH_DOC) VITH ON DLV_ID=ITH_DOC
        WHERE DLVRMSO_CPO IS NOT NULL AND ISNULL(NOMPEN,'')!='' AND SHIPDATE BETWEEN ? AND ?
        ORDER BY ITH_DATEC, ITH_DOC, ITH_ITMCD";
        $query = $this->db->query($qry, [$pdate1, $pdate2, $pdate1, $pdate2]);
        return $query->result_array();
    }
    public function select_deliv_part_to3rdparty($pdate1, $pdate2, $bigrup)
    {
        $qry = "SELECT DLV_DATE ITH_DATEC,DLV_ID ITH_DOC,DLV_CUSTDO, DLV_CONSIGN, NOMAJU,
        NOMPEN, INVDT,DLV_INVNO,DLV_SMTINVNO,DLVRMDOC_ITMID ITH_ITMCD, RTRIM(MITM_ITMD1) ITMDESCD,DLVPRC_QTY,DLVRMSO_PRPRC DLVPRC_PRC, DLVPRC_QTY* DLVRMSO_PRPRC AMOUNT,DLV_DSCRPTN,
        DLV_LOCFR,DLV_BSGRP,DLV_DATE,DLV_PARENTDOC,DLV_RPRDOC,MSUP_SUPCD,MSUP_SUPNM,MSUP_SUPCR,MITM_SPTNO
         FROM
        (select DLV_BCDATE,DLV_ID,DLV_CUSTDO,DLV_CONSIGN,DLV_ZNOMOR_AJU NOMAJU,
        DLV_NOPEN NOMPEN, DLV_INVDT INVDT,DLV_INVNO,DLV_SMTINVNO,MDEL_ZNAMA,MDEL_ADDRCUSTOMS,DLV_BCTYPE,
        DLV_ZJENIS_TPB_TUJUAN,DLV_RPDATE TGLPEN,DLV_PURPOSE,max(DLV_DSCRPTN) DLV_DSCRPTN,max(DLV_LOCFR) DLV_LOCFR,max(DLV_BSGRP) DLV_BSGRP,MAX(DLV_DATE) DLV_DATE
        ,MAX(DLV_PARENTDOC) DLV_PARENTDOC,MAX(DLV_RPRDOC) DLV_RPRDOC,MAX(MSUP_SUPCD) MSUP_SUPCD,MAX(MSUP_SUPNM) MSUP_SUPNM,MAX(MSUP_SUPCR) MSUP_SUPCR from DLV_TBL
        left join MDEL_TBL on DLV_CONSIGN=MDEL_DELCD
        LEFT JOIN (SELECT MSUP_SUPCD,MAX(MSUP_SUPNM) MSUP_SUPNM, MAX(MSUP_SUPCR) MSUP_SUPCR FROM v_supplier_customer_union group by MSUP_SUPCD) V3RD ON DLV_CUSTCD=MSUP_SUPCD
        WHERE DLV_RPDATE IS NOT NULL AND DLV_LOCFR not in ('PSIEQUIP') AND DLV_DSCRPTN NOT LIKE '%DIJUAL%' AND (DLV_DATE between ? and ?) AND DLV_SER='' AND DLV_BSGRP IN ($bigrup)
        GROUP BY DLV_BCDATE,DLV_ID,DLV_CUSTDO,DLV_CONSIGN,DLV_ZNOMOR_AJU,
        DLV_NOPEN, DLV_INVDT,DLV_INVNO,DLV_SMTINVNO,MDEL_ZNAMA,MDEL_ADDRCUSTOMS,DLV_BCTYPE,
        DLV_ZJENIS_TPB_TUJUAN,DLV_RPDATE,DLV_PURPOSE) VDLV
        INNER join (
        SELECT DLVRMDOC_TXID,DLVRMDOC_ITMID,RTRIM(MITM_ITMD1) MITM_ITMD1,sum(DLVRMDOC_ITMQT) DLVPRC_QTY,DLVRMDOC_PRPRC DLVRMSO_PRPRC,rtrim(MITM_STKUOM) MITM_STKUOM,RTRIM(MITM_SPTNO) MITM_SPTNO FROM DLVRMDOC_TBL
        LEFT JOIN MITM_TBL ON DLVRMDOC_ITMID=MITM_ITMCD
        group by DLVRMDOC_TXID,DLVRMDOC_ITMID,MITM_ITMD1,DLVRMDOC_PRPRC,MITM_STKUOM,MITM_SPTNO) vselect
        on DLV_ID=DLVRMDOC_TXID
        ORDER BY ITH_DATEC, ITH_DOC, ITH_ITMCD";
        $query = $this->db->query($qry, [$pdate1, $pdate2]);
        return $query->result_array();
    }

    public function select_itemtotal($pdoc, $pwh)
    {
        $qry = "SELECT RTRIM(ITH_ITMCD) ITH_ITMCD,SUM(ITH_QTY) ITH_QTY,ITH_DOC FROM ITH_TBL WHERE ITH_DOC=? AND ITH_WH=?
        GROUP BY ITH_ITMCD,ITH_DOC";
        $query = $this->db->query($qry, [$pdoc, $pwh]);
        return $query->result_array();
    }

    public function select_out_wip($pdate)
    {
        $qry = "SELECT SER_ID,SER_DOC,SER_BSGRP PDPP_BSGRP
        ,CASE WHEN SER_BSGRP = 'PSI1PPZIEP' THEN 'PLANT1'
        WHEN SER_BSGRP = 'PSI2PPZADI' THEN 'PLANT2'
        WHEN SER_BSGRP = 'PSI2PPZSTY' THEN 'PLANT2'
        WHEN SER_BSGRP = 'PSI2PPZOMI' THEN 'PLANT2'
        WHEN SER_BSGRP = 'PSI2PPZTDI' THEN 'PLANT2'
        WHEN SER_BSGRP = 'PSI2PPZINS' THEN 'PLANT_NA'
        WHEN SER_BSGRP = 'PSI2PPZOMC' THEN 'PLANT_NA'
        WHEN SER_BSGRP = 'PSI2PPZSSI' THEN 'PLANT_NA'
        END OUTWH,SERD2_ITMCD, SUM(SERD2_QTY) QTY
        ,ITH_LUPDT
        FROM v_ith_tblc A
        LEFT JOIN SER_TBL ON ITH_SER=SER_ID
        LEFT JOIN SERD2_TBL ON SER_ID=SERD2_SER
        LEFT JOIN
        (SELECT ITH_REMARK WORID FROM v_ith_tblc WHERE ITH_FORM='WOR'  AND ITH_DATEC=?
        GROUP BY ITH_REMARK) VWOR ON SER_ID=WORID
        LEFT JOIN MITM_TBL ON SERD2_ITMCD=MITM_ITMCD
        WHERE MITM_MODEL='0' AND ITH_FORM='INC-WH-FG' AND ITH_DATEC = ?
        and SERD2_ITMCD is not null AND WORID IS NULL
        and SER_BSGRP is not null
        GROUP BY SER_ID,SER_DOC,SER_BSGRP,SERD2_ITMCD,ITH_LUPDT";
        $query = $this->db->query($qry, [$pdate, $pdate]);
        return $query->result_array();
    }
    public function select_out_wip_fromsubassy($pdate)
    {
        $qry = "SELECT SERML_COMID,SER_DOC,PDPP_BSGRP
        ,CASE WHEN PDPP_BSGRP = 'PSI1PPZIEP' THEN 'PLANT1'
        WHEN PDPP_BSGRP = 'PSI2PPZADI' THEN 'PLANT2'
        WHEN PDPP_BSGRP = 'PSI2PPZSTY' THEN 'PLANT2'
        WHEN PDPP_BSGRP = 'PSI2PPZOMI' THEN 'PLANT2'
        WHEN PDPP_BSGRP = 'PSI2PPZTDI' THEN 'PLANT2'
        WHEN PDPP_BSGRP = 'PSI2PPZINS' THEN 'PLANT_NA'
        WHEN PDPP_BSGRP = 'PSI2PPZOMC' THEN 'PLANT_NA'
        WHEN PDPP_BSGRP = 'PSI2PPZSSI' THEN 'PLANT_NA'
        END OUTWH,SERD2_ITMCD, SUM(SERD2_QTY) QTY
        ,ITH_LUPDT
        FROM v_ith_tblc A
        LEFT JOIN SER_TBL ON ITH_SER=SER_ID
        INNER JOIN XWO ON SER_DOC=PDPP_WONO AND SER_ITMID=PDPP_MDLCD
        inner join VSUBASSY on SER_ITMID=PWOP_MDLCD
        LEFT JOIN SERML_TBL ON SER_ID=SERML_NEWID
        LEFT JOIN SERD2_TBL ON SERML_COMID=SERD2_SER
        LEFT JOIN
        (SELECT ITH_REMARK WORID FROM v_ith_tblc WHERE ITH_FORM='WOR'  AND ITH_DATEC=?
        GROUP BY ITH_REMARK) VWOR ON SERML_COMID=WORID
        LEFT JOIN MITM_TBL ON SERD2_ITMCD=MITM_ITMCD
        WHERE MITM_MODEL='0' AND ITH_FORM='INC-WH-FG' AND ITH_DATEC = ?
        and SERD2_ITMCD is not null AND WORID IS NULL
        GROUP BY SERML_COMID,SER_DOC,PDPP_BSGRP,SERD2_ITMCD,ITH_LUPDT";
        $query = $this->db->query($qry, [$pdate, $pdate]);
        return $query->result_array();
    }

    public function select_out_wip_zeroed($pdate)
    {
        $qry = "SELECT SER_ID,SER_DOC,PDPP_BSGRP
        ,CASE WHEN PDPP_BSGRP = 'PSI1PPZIEP' THEN 'PLANT1'
        WHEN PDPP_BSGRP = 'PSI2PPZADI' THEN 'PLANT2'
        WHEN PDPP_BSGRP = 'PSI2PPZSTY' THEN 'PLANT2'
        WHEN PDPP_BSGRP = 'PSI2PPZOMI' THEN 'PLANT2'
        WHEN PDPP_BSGRP = 'PSI2PPZTDI' THEN 'PLANT2'
        WHEN PDPP_BSGRP = 'PSI2PPZINS' THEN 'PLANT_NA'
        WHEN PDPP_BSGRP = 'PSI2PPZOMC' THEN 'PLANT_NA'
        WHEN PDPP_BSGRP = 'PSI2PPZSSI' THEN 'PLANT_NA'
        END OUTWH,SERD2_ITMCD, SUM(SERD2_QTY) QTY,SER_QTYLOT
        ,ITH_LUPDT,SERD2_SER_SMP
        FROM v_ith_tblc A
        LEFT JOIN SER_TBL ON ITH_SER=SER_ID
        LEFT JOIN XWO ON SER_DOC=PDPP_WONO AND SER_ITMID=PDPP_MDLCD
        LEFT JOIN SERD2_TBL ON SER_ID=SERD2_SER
        LEFT JOIN
        (SELECT ITH_REMARK WORID FROM v_ith_tblc WHERE ITH_FORM='WOR'  AND ITH_DATEC=?
        GROUP BY ITH_REMARK) VWOR ON SER_ID=WORID
        LEFT JOIN (SELECT max(SERD2_SER) SERD2_SER_SMP, SERD2_JOB SERD2JOB FROM SERD2_TBL GROUP BY SERD2_JOB) VSERDSMP ON SER_DOC=SERD2JOB
        WHERE ITH_FORM='INC-WH-FG' AND ITH_DATEC = ?
        and SERD2_ITMCD is null AND WORID IS NULL AND PDPP_BSGRP IS NOT NULL
        GROUP BY SER_ID,SER_DOC,PDPP_BSGRP,SERD2_ITMCD,ITH_LUPDT,SERD2_SER_SMP,SER_QTYLOT";
        $query = $this->db->query($qry, [$pdate, $pdate]);
        return $query->result_array();
    }
    public function select_out_wip_zeroed_by_reffno($preffno)
    {
        $qry = "SELECT SER_ID,SER_DOC,PDPP_BSGRP
        ,CASE WHEN PDPP_BSGRP = 'PSI1PPZIEP' THEN 'PLANT1'
        WHEN PDPP_BSGRP = 'PSI2PPZADI' THEN 'PLANT2'
        WHEN PDPP_BSGRP = 'PSI2PPZSTY' THEN 'PLANT2'
        WHEN PDPP_BSGRP = 'PSI2PPZOMI' THEN 'PLANT2'
        WHEN PDPP_BSGRP = 'PSI2PPZTDI' THEN 'PLANT2'
        WHEN PDPP_BSGRP = 'PSI2PPZINS' THEN 'PLANT_NA'
        WHEN PDPP_BSGRP = 'PSI2PPZOMC' THEN 'PLANT_NA'
        WHEN PDPP_BSGRP = 'PSI2PPZSSI' THEN 'PLANT_NA'
        END OUTWH,SERD2_ITMCD, SUM(SERD2_QTY) QTY,SER_QTYLOT
        ,ITH_LUPDT,SERD2_SER_SMP
        FROM v_ith_tblc A
        LEFT JOIN SER_TBL ON ITH_SER=SER_ID
        LEFT JOIN XWO ON SER_DOC=PDPP_WONO AND SER_ITMID=PDPP_MDLCD
        LEFT JOIN SERD2_TBL ON SER_ID=SERD2_SER
        LEFT JOIN
        (SELECT ITH_REMARK WORID FROM v_ith_tblc WHERE ITH_FORM='WOR'  AND ITH_SER=?
        GROUP BY ITH_REMARK) VWOR ON SER_ID=WORID
        LEFT JOIN (SELECT max(SERD2_SER) SERD2_SER_SMP, SERD2_JOB SERD2JOB FROM SERD2_TBL GROUP BY SERD2_JOB) VSERDSMP ON SER_DOC=SERD2JOB
        WHERE ITH_FORM='INC-WH-FG' AND ITH_SER = ?
        and SERD2_ITMCD is null AND WORID IS NULL
        GROUP BY SER_ID,SER_DOC,PDPP_BSGRP,SERD2_ITMCD,ITH_LUPDT,SERD2_SER_SMP,SER_QTYLOT";
        $query = $this->db->query($qry, [$preffno, $preffno]);
        return $query->result_array();
    }

    public function select_confirmdate_psn($psn)
    {
        $qry = "SELECT * FROM ITH_TBL WHERE ITH_FORM='INC-RET' AND ITH_DOC LIKE ?";
        $query = $this->db->query($qry, ['%' . $psn . '%']);
        return $query->result_array();
    }

    public function select_fordispose($plike, $pdate0)
    {
        $this->db->select("ITH_ITMCD,MITMGRP_ITMCD,RTRIM(MAX(MITM_ITMD1)) ITMNM,SUM(ITH_QTY) STKQTY");
        $this->db->from('v_ith_tblc');
        $this->db->join('MITM_TBL', "ITH_ITMCD=MITM_ITMCD", "LEFT");
        $this->db->join('MITMGRP_TBL', "ITH_ITMCD=MITMGRP_ITMCD_GRD", "LEFT");
        $this->db->where("ITH_WH", "ARWH9SC")
            ->where("ITH_DATEC <=", $pdate0)
            ->like($plike);
        $this->db->group_by("ITH_ITMCD,MITMGRP_ITMCD");
        $this->db->order_by("ITH_ITMCD");
        $query = $this->db->get();
        return $query->result_array();
    }
    public function select_fordispose_v2($plike, $pdate0)
    {
        $this->db->select("RTRIM(ITH_ITMCD) PART_CODE,MITMGRP_ITMCD,RTRIM(MAX(MITM_ITMD1)) ITMNM,SUM(ITH_QTY) QTY");
        $this->db->from('v_ith_tblc');
        $this->db->join('MITM_TBL', "ITH_ITMCD=MITM_ITMCD", "LEFT");
        $this->db->join('MITMGRP_TBL', "ITH_ITMCD=MITMGRP_ITMCD_GRD", "LEFT");
        $this->db->where("ITH_WH", "ARWH9SC")
            ->where("ITH_DATEC <=", $pdate0)
            ->like($plike);
        $this->db->group_by("ITH_ITMCD,MITMGRP_ITMCD");
        $this->db->having("SUM(ITH_QTY)>", 0);
        $this->db->order_by("ITH_ITMCD");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_doc_vs_datec($pDoc, $pDate)
    {
        $this->db->from('v_ith_tblc');
        $this->db->like("ITH_DOC", $pDoc, 'after')
            ->where_in("SUBSTRING(ITH_DOC,1,3)", ['SP-','PR-'])
            ->where_not_in("ITH_FORM", ['INC-RET', 'OUT-RET'])
            ->where("ITH_DATEC !=", $pDate);
        $this->db->order_by("ITH_LUPDT");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_doc_vs_datec_about_change_date_of_cancel($pDoc, $pDate)
    {
        $this->db->from('v_ith_tblc');
        $this->db->like("ITH_DOC", $pDoc, 'after')
            ->like("ITH_DOC", 'SP-')
            ->where_in("ITH_FORM", ['CANCELING-RM-PSN-IN'])
            ->where("ITH_DATEC !=", $pDate);
        $this->db->order_by("ITH_LUPDT");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_doc_vs_datec_about_change_date_of_return($pDoc, $pDate)
    {
        $this->db->from('v_ith_tblc');
        $this->db->like("ITH_DOC", $pDoc, 'after')
            ->where_in("ITH_FORM", ['INC-RET'])
            ->where("ITH_DATEC !=", $pDate);
        $this->db->order_by("ITH_LUPDT");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_doc_vs_datec_withIn_items($pDoc, $pDate, $pItems)
    {
        $this->db->from('v_ith_tblc');
        $this->db->like("ITH_DOC", $pDoc, 'after')
            ->like("ITH_DOC", 'SP-')
            ->where_not_in("ITH_FORM", ['INC-RET', 'OUT-RET'])
            ->where("ITH_DATEC !=", $pDate)
            ->where_in("ITH_ITMCD", $pItems);
        $this->db->order_by("ITH_LUPDT");
        $query = $this->db->get();
        return $query->result_array();
    }
    public function select_doc_vs_datec_withIn_itemsAndDates($pDoc, $pDate, $pItems, $pDates)
    {
        $this->db->from('v_ith_tblc');
        $this->db->like("ITH_DOC", $pDoc, 'after')
            ->where_in("SUBSTRING(ITH_DOC,1,3)", ['SP-','PR-'])
            ->where_not_in("ITH_FORM", ['INC-RET', 'OUT-RET'])
            ->where("ITH_DATEC !=", $pDate)
            ->where_in("ITH_ITMCD", $pItems)
            ->where_in("ITH_LUPDT", $pDates);
        $this->db->order_by("ITH_LUPDT");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectForCancellingwithIn_items($pDoc, $pDate, $pItems, $pDates)
    {
        $this->db->from('v_ith_tblc');
        $this->db->like("ITH_DOC", $pDoc, 'after')
            ->like("ITH_DOC", 'SP-')
            ->where_in("ITH_FORM", ['CANCELING-RM-PSN-IN'])
            ->where("ITH_DATEC !=", $pDate)
            ->where_in("ITH_ITMCD", $pItems)
            ->where_in("ITH_LUPDT", $pDates);
        $this->db->order_by("ITH_LUPDT");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectForReturningwithIn_items($pDoc, $pDate, $pItems, $pDates, $pRemarks)
    {
        $this->db->from('v_ith_tblc');
        $this->db->like("ITH_DOC", $pDoc, 'after')
            ->where_in("ITH_FORM", ['INC-RET'])
            ->where("ITH_DATEC !=", $pDate)
            ->where_in("ITH_ITMCD", $pItems)
            ->where_in("ITH_REMARK", $pRemarks)
            ->where_in("ITH_LUPDT", $pDates);
        $this->db->order_by("ITH_LUPDT");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectStockWhereItemIn($items, $warehouse)
    {
        $this->db->from('v_ith_tblc');
        $this->db->where_in("ITH_ITMCD", $items)->where("ITH_WH", $warehouse);
        $this->db->group_by("ITH_ITMCD");
        $this->db->select("RTRIM(UPPER(ITH_ITMCD)) ITH_ITMCD,SUM(ITH_QTY) SQT, SUM(ITH_QTY) BACKUP_SQT");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function update_kitting_date($PSN, $newDate, $newDateTime, $oldDateTime, $itemcd)
    {
        $qry = "UPDATE ITH_TBL SET ITH_DATE=?,ITH_LUPDT=? where ITH_DOC LIKE ?
        AND ITH_FORM NOT IN ('INC-RET','OUT-RET')
        AND LEFT(RTRIM(CONVERT(DATETIMEOFFSET, ITH_LUPDT)), 19)=?
        AND ITH_ITMCD=?";
        $this->db->query($qry, [$newDate, $newDateTime, $PSN . '%', $oldDateTime, $itemcd]);
        return $this->db->affected_rows();
    }

    public function update_cancel_kitting_date($PSN, $newDate, $newDateTime, $oldDateTime, $itemcd)
    {
        $qry = "UPDATE ITH_TBL SET ITH_DATE=?,ITH_LUPDT=? where ITH_DOC LIKE ?
        AND ITH_FORM IN ('CANCELING-RM-PSN-IN','CANCELING-RM-PSN-OUT')
        AND ITH_LUPDT=?
        AND ITH_ITMCD=?";
        $this->db->query($qry, [$newDate, $newDateTime, $PSN . '%', $oldDateTime, $itemcd]);
        return $this->db->affected_rows();
    }
    public function update_return_kitting_date($PSN, $newDate, $newDateTime, $oldDateTime, $itemcd, $remark)
    {
        $qry = "UPDATE ITH_TBL SET ITH_DATE=?,ITH_LUPDT=? where ITH_DOC LIKE ?
        AND ITH_FORM IN ('INC-RET','OUT-RET')
        AND ITH_LUPDT=?
        AND ITH_ITMCD=?
        AND ITH_REMARK=?";
        $this->db->query($qry, [$newDate, $newDateTime, $PSN . '%', $oldDateTime, $itemcd, $remark]);
        return $this->db->affected_rows();
    }

    public function select_fordispose_fromfg($pdate0)
    {
        $qry = "SELECT RTRIM(SERD2_ITMCD) ITH_ITMCD,MITMGRP_ITMCD,SUM(RMQT) STKQTY FROM
        (SELECT ITH_SER,SUM(ITH_QTY) FGQT FROM v_ith_tblc WHERE ITH_DATEC<=? AND ITH_WH='AFWH9SC'
        GROUP BY ITH_SER
        HAVING SUM(ITH_QTY) >0) VFG
        LEFT JOIN (
        select SERD2_SER,SERD2_ITMCD,sum(SERD2_QTY) RMQT from SERD2_TBL group by SERD2_SER,SERD2_ITMCD
        ) VSERD ON ITH_SER=SERD2_SER
        LEFT JOIN MITMGRP_TBL ON SERD2_ITMCD=MITMGRP_ITMCD_GRD
        GROUP BY SERD2_ITMCD,MITMGRP_ITMCD
        ORDER BY SERD2_ITMCD";
        $query = $this->db->query($qry, [$pdate0]);
        return $query->result_array();
    }
    public function select_fordispose_fromfg_serial($pdate0)
    {
        $qry = "SELECT SER_ITMID,RTRIM(SERD2_ITMCD) ITH_ITMCD,MITMGRP_ITMCD,SUM(RMQT) STKQTY FROM
        (SELECT ITH_SER,SUM(ITH_QTY) FGQT FROM v_ith_tblc WHERE ITH_DATEC<=? AND ITH_WH='AFWH9SC'
        GROUP BY ITH_SER
        HAVING SUM(ITH_QTY) >0) VFG
        LEFT JOIN (
        select SERD2_SER,SERD2_ITMCD,sum(SERD2_QTY) RMQT from SERD2_TBL group by SERD2_SER,SERD2_ITMCD
        ) VSERD ON ITH_SER=SERD2_SER
        LEFT JOIN MITMGRP_TBL ON SERD2_ITMCD=MITMGRP_ITMCD_GRD
        LEFT JOIN SER_TBL ON ITH_SER=SER_ID
        GROUP BY SERD2_ITMCD,MITMGRP_ITMCD,SER_ITMID
        ORDER BY SERD2_ITMCD";
        $query = $this->db->query($qry, [$pdate0]);
        return $query->result_array();
    }

    public function select_balanceRA()
    {
        $this->db->select("ITH_SER,ITH_DOC,MAX(ITH_ITMCD) ITMCD,SUM(ITH_QTY) BALQT,SUM(CASE WHEN ITH_QTY>0 THEN ITH_QTY ELSE 0 END) INCQT ");
        $this->db->from($this->TABLENAME);
        $this->db->where("ITH_WH", "AFQART");
        $this->db->group_by("ITH_SER,ITH_DOC");
        $this->db->having("SUM(ITH_QTY) > ", 0);
        $this->db->order_by("ITH_DOC");
        $query = $this->db->get();
        return $query->result();
    }
    public function select_slow_moving_fg()
    {
        $this->db->select("wms_v_fg_slow_moving.*,RTRIM(MITM_ITMD1) MITM_ITMD1");
        $this->db->from("wms_v_fg_slow_moving");
        $this->db->join("MITM_TBL", "ITH_ITMCD=MITM_ITMCD", "LEFT");
        $this->db->join("v_mitm_bsgroup", "ITH_ITMCD=PDPP_MDLCD", "LEFT");
        $this->db->order_by("ITH_ITMCD");
        $query = $this->db->get();
        return $query->result();
    }
    public function select_slow_moving_fg_bg($pbg)
    {
        $this->db->select("wms_v_fg_slow_moving.*,RTRIM(MITM_ITMD1) MITM_ITMD1");
        $this->db->from("wms_v_fg_slow_moving");
        $this->db->join("MITM_TBL", "ITH_ITMCD=MITM_ITMCD", "LEFT");
        $this->db->join("v_mitm_bsgroup", "ITH_ITMCD=PDPP_MDLCD", "LEFT");
        $this->db->where_in("PDPP_BSGRP", $pbg);
        $this->db->order_by("ITH_ITMCD");
        $query = $this->db->get();
        return $query->result();
    }
    public function select_discrepancy_prd_qc()
    {
        $this->db->from("wms_v_discrepancy_prd_qc");
        $this->db->where("YEAR(LUPDT)>", 2021);
        $this->db->order_by("LUPDT");
        $query = $this->db->get();
        return $query->result();
    }
    public function select_WO_PRD_uncalculated()
    {
        $this->db->from("WMS_V_WOPRD_UNCALCULATED");
        $query = $this->db->get();
        return $query->result();
    }

    public function select_spl_booked($pbookid)
    {
        $qry = "SELECT ITH_DOC,ITH_ITMCD,SUM(ITH_QTY),ITH_REMARK FROM ITH_TBL WHERE ITH_FORM='BOOK-SPL-1' AND ITH_DOC=?
        GROUP BY ITH_DOC,ITH_ITMCD,ITH_REMARK";
        $query = $this->db->query($qry, [$pbookid]);
        return $query->result_array();
    }

    public function select_wo_side_detail($pdate, $passycode, $ppsn)
    {
        $qry = "SELECT XWO.*,ISNULL(ITHQT,0) ITHQT,RTRIM(PWOP_BOMPN) PWOP_BOMPN,RTRIM(PWOP_SUBPN) PWOP_SUBPN,PWOP_PER, (PDPP_WORQT-ISNULL(ITHQT,0)) * PWOP_PER NEEDQTY, 0 PLOTQTY,PPSN1_PSNNO FROM XWO
        LEFT JOIN (SELECT ITH_DOC,ITH_ITMCD,SUM(ITH_QTY) ITHQT FROM v_ith_tblc LEFT JOIN XWO ON ITH_DOC=PDPP_WONO AND ITH_ITMCD=PDPP_MDLCD WHERE ITH_WH='AFWH3' AND ITH_FORM='INC-WH-FG' AND ITH_DATEC<=?  GROUP BY ITH_DOC,ITH_ITMCD) VITHQC ON PDPP_WONO=ITH_DOC AND PDPP_MDLCD=ITH_ITMCD
        LEFT JOIN XPWOP ON PDPP_WONO=PWOP_WONO
        LEFT JOIN (select PPSN1_WONO,RTRIM(MAX(PPSN1_PSNNO)) PPSN1_PSNNO from XPPSN1 WHERE PPSN1_PSNNO IN ($ppsn) group by PPSN1_WONO) VPSN ON PDPP_WONO=PPSN1_WONO
        WHERE PDPP_MDLCD IN	($passycode) AND PDPP_WORQT!=isnull(ITHQT,0) AND abs(DATEDIFF(MONTH,  PDPP_ISUDT,?))<=2
        AND PPSN1_WONO IS NOT NULL
        ORDER BY PPSN1_PSNNO DESC,PDPP_WONO DESC";
        $query = $this->db->query($qry, [$pdate, $pdate]);
        return $query->result_array();
    }
    public function select_wo_side_detail_BGOther($pdate, $passycode, $ppsn)
    {
        $qry = "SELECT XWO.*,ISNULL(ITHQT,0) ITHQT,RTRIM(PWOP_BOMPN) PWOP_BOMPN,RTRIM(PWOP_SUBPN) PWOP_SUBPN,PWOP_PER, (PDPP_WORQT-ISNULL(ITHQT,0)) * PWOP_PER NEEDQTY, 0 PLOTQTY,PPSN1_PSNNO FROM XWO
        LEFT JOIN (SELECT ITH_DOC,ITH_ITMCD,SUM(ITH_QTY) ITHQT FROM v_ith_tblc LEFT JOIN XWO ON ITH_DOC=PDPP_WONO AND ITH_ITMCD=PDPP_MDLCD WHERE ITH_WH='ARQA1' AND ITH_FORM='INC-QA-FG' AND ITH_DATEC<=?  GROUP BY ITH_DOC,ITH_ITMCD) VITHQC ON PDPP_WONO=ITH_DOC AND PDPP_MDLCD=ITH_ITMCD
        LEFT JOIN XPWOP ON PDPP_WONO=PWOP_WONO
        LEFT JOIN (select PPSN1_WONO,RTRIM(MAX(PPSN1_PSNNO)) PPSN1_PSNNO from XPPSN1 WHERE PPSN1_PSNNO IN ($ppsn) group by PPSN1_WONO) VPSN ON PDPP_WONO=PPSN1_WONO
        WHERE PDPP_MDLCD IN	($passycode) AND PDPP_WORQT!=isnull(ITHQT,0) AND abs(DATEDIFF(MONTH,  PDPP_ISUDT,?))<=2
        AND PPSN1_WONO IS NOT NULL
        ORDER BY PPSN1_PSNNO DESC,PDPP_WONO DESC";
        $query = $this->db->query($qry, [$pdate, $pdate]);
        return $query->result_array();
    }
    public function select_wo_side_detail_byPSN($pdate, $ppsn)
    {
        $qry = "SELECT XWO.*,ISNULL(ITHQT,0) ITHQT,RTRIM(PWOP_BOMPN) PWOP_BOMPN,RTRIM(PWOP_SUBPN) PWOP_SUBPN,PWOP_PER, (PDPP_WORQT-ISNULL(ITHQT,0)) * PWOP_PER NEEDQTY, 0 PLOTQTY,PPSN1_PSNNO FROM XWO
        LEFT JOIN (SELECT ITH_DOC,ITH_ITMCD,SUM(ITH_QTY) ITHQT FROM v_ith_tblc LEFT JOIN XWO ON ITH_DOC=PDPP_WONO AND ITH_ITMCD=PDPP_MDLCD WHERE ITH_WH='AFWH3' AND ITH_FORM='INC-WH-FG' AND ITH_DATEC<=?  GROUP BY ITH_DOC,ITH_ITMCD) VITHQC ON PDPP_WONO=ITH_DOC AND PDPP_MDLCD=ITH_ITMCD
        LEFT JOIN XPWOP ON PDPP_WONO=PWOP_WONO
        LEFT JOIN (select PPSN1_WONO,RTRIM(MAX(PPSN1_PSNNO)) PPSN1_PSNNO from XPPSN1 WHERE PPSN1_PSNNO IN ($ppsn) group by PPSN1_WONO) VPSN ON PDPP_WONO=PPSN1_WONO
        WHERE PDPP_WORQT!=isnull(ITHQT,0) AND abs(DATEDIFF(MONTH,  PDPP_ISUDT,?))<=2
        AND PPSN1_WONO IS NOT NULL
        ORDER BY PPSN1_PSNNO DESC,PDPP_WONO DESC";
        $query = $this->db->query($qry, [$pdate, $pdate]);
        return $query->result_array();
    }
    public function select_wo_side_detail_byPSN_BGOther($pdate, $ppsn)
    {
        $qry = "SELECT XWO.*,ISNULL(ITHQT,0) ITHQT,RTRIM(PWOP_BOMPN) PWOP_BOMPN,RTRIM(PWOP_SUBPN) PWOP_SUBPN,PWOP_PER, (PDPP_WORQT-ISNULL(ITHQT,0)) * PWOP_PER NEEDQTY, 0 PLOTQTY,PPSN1_PSNNO FROM XWO
        LEFT JOIN (SELECT ITH_DOC,ITH_ITMCD,SUM(ITH_QTY) ITHQT FROM v_ith_tblc LEFT JOIN XWO ON ITH_DOC=PDPP_WONO AND ITH_ITMCD=PDPP_MDLCD WHERE ITH_WH='ARQA1' AND ITH_FORM='INC-QA-FG' AND ITH_DATEC<=?  GROUP BY ITH_DOC,ITH_ITMCD) VITHQC ON PDPP_WONO=ITH_DOC AND PDPP_MDLCD=ITH_ITMCD
        LEFT JOIN XPWOP ON PDPP_WONO=PWOP_WONO
        LEFT JOIN (select PPSN1_WONO,RTRIM(MAX(PPSN1_PSNNO)) PPSN1_PSNNO from XPPSN1 WHERE PPSN1_PSNNO IN ($ppsn) group by PPSN1_WONO) VPSN ON PDPP_WONO=PPSN1_WONO
        WHERE PDPP_WORQT!=isnull(ITHQT,0) AND abs(DATEDIFF(MONTH,  PDPP_ISUDT,?))<=2
        AND PPSN1_WONO IS NOT NULL
        ORDER BY PPSN1_PSNNO DESC,PDPP_WONO DESC";
        $query = $this->db->query($qry, [$pdate, $pdate]);
        return $query->result_array();
    }
    public function select_wo_side_detail_open($pdate, $passycode)
    {
        $qry = "SELECT XWO.*,ISNULL(ITHQT,0) ITHQT,RTRIM(PWOP_BOMPN) PWOP_BOMPN,RTRIM(PWOP_SUBPN) PWOP_SUBPN,PWOP_PER, (PDPP_WORQT-ISNULL(ITHQT,0)) * PWOP_PER NEEDQTY, 0 PLOTQTY FROM XWO
        LEFT JOIN (SELECT ITH_DOC,ITH_ITMCD,SUM(ITH_QTY) ITHQT FROM v_ith_tblc LEFT JOIN XWO ON ITH_DOC=PDPP_WONO AND ITH_ITMCD=PDPP_MDLCD WHERE ITH_WH='AFWH3' AND ITH_FORM='INC-WH-FG' AND ITH_DATEC<=?  GROUP BY ITH_DOC,ITH_ITMCD) VITHQC ON PDPP_WONO=ITH_DOC AND PDPP_MDLCD=ITH_ITMCD
        LEFT JOIN XPWOP ON PDPP_WONO=PWOP_WONO
        WHERE PDPP_MDLCD IN
        (
            $passycode
        ) AND PDPP_WORQT!=isnull(ITHQT,0) AND PDPP_COMFG='0'
        AND abs(DATEDIFF(MONTH,  PDPP_ISUDT,'2022-05-19'))<=2
        ORDER BY ISSUEDATE DESC";
        $query = $this->db->query($qry, [$pdate]);
        return $query->result_array();
    }

    public function select_critical_FGStock($pdate, $pFGs)
    {
        $qry = "SELECT RTRIM(ITH_ITMCD) ITH_ITMCD,
        isnull(SUM(CASE WHEN ITH_WH='ARQA1' THEN ITH_QTY END),0) QCQT,
        isnull(SUM(CASE WHEN ITH_WH='AFWH3' THEN ITH_QTY END),0) FGQT
        FROM v_ith_tblc
        LEFT JOIN SER_TBL ON ITH_SER = SER_ID
        WHERE ITH_WH IN ('ARPRD1','AFWH3','ARQA1') AND ITH_ITMCD IN ($pFGs)
        AND ITH_DATEC<=?
        GROUP BY ITH_ITMCD ORDER BY 1";
        $query = $this->db->query($qry, [$pdate]);
        return $query->result_array();
    }

    public function select_wip_balance($pDate, $pWarehouse, $pItems)
    {
        $qry = "SELECT * FROM
        (SELECT RTRIM(ITRN_ITMCD) ITRN_ITMCD,
         SUM( CASE WHEN ITRN_LOCCD='ARWH1' THEN
                CASE WHEN ITRN_IOFLG = '1' THEN ITRN_TRNQT
                ELSE 0-ITRN_TRNQT END
            ELSE
             0 END)  ARWHQTY,
        SUM( CASE WHEN ITRN_LOCCD='PLANT1' THEN
                CASE WHEN ITRN_IOFLG = '1' THEN ITRN_TRNQT
                ELSE 0-ITRN_TRNQT END
            ELSE
            0 END) MGAQTY
                FROM XITRN_TBL
                WHERE ITRN_ISUDT<=? AND ITRN_LOCCD in (?,'ARWH1')
                AND ITRN_ITMCD IN ($pItems)
        GROUP BY ITRN_ITMCD) VMEGA
        WHERE MGAQTY>0
        ORDER BY 1";
        $query = $this->db->query($qry, [$pDate, $pWarehouse]);
        return $query->result_array();
    }
    public function select_allwip_plant2($pDate, $pItems)
    {
        $qry = "SELECT * FROM
        (SELECT RTRIM(ITRN_ITMCD) ITRN_ITMCD,
         SUM( CASE WHEN ITRN_LOCCD='ARWH2' THEN
                CASE WHEN ITRN_IOFLG = '1' THEN ITRN_TRNQT
                ELSE 0-ITRN_TRNQT END
            ELSE
             0 END)  ARWHQTY,
        SUM( CASE WHEN ITRN_LOCCD='PLANT2' THEN
                CASE WHEN ITRN_IOFLG = '1' THEN ITRN_TRNQT
                ELSE 0-ITRN_TRNQT END
            ELSE
            0 END) MGAQTY
                FROM XITRN_TBL
                WHERE ITRN_ISUDT<=? AND ITRN_LOCCD in ('PLANT2','ARWH2')
                AND ITRN_ITMCD IN ($pItems)
        GROUP BY ITRN_ITMCD) VMEGA
        WHERE MGAQTY>0
        ORDER BY 1";
        $query = $this->db->query($qry, [$pDate]);
        return $query->result_array();
    }
    public function select_allwip_plant1_byBG($pDate, $pBG)
    {
        $qry = "wms_sp_rmstock_plant1 ?, ?";
        $query = $this->db->query($qry, [$pBG, $pDate]);
        return $query->result_array();
    }
    public function select_allwip_plant2_byBG($pDate, $pBG)
    {
        $qry = "wms_sp_rmstock ?, ?";
        $query = $this->db->query($qry, [$pBG, $pDate]);
        return $query->result_array();
    }
    public function select_allwip_plant2_byBG_and_Part($pDate, $pBG, $pParts)
    {
        $qry = "SELECT *,(ARWH+NRWH2+ARWH0PD+PLANT2+QA) STOCK FROM
        (SELECT RTRIM(ITRN_ITMCD) ITRN_ITMCD,RTRIM(MITM_ITMD1) ITMD1,RTRIM(MITM_SPTNO) MITM_SPTNO,
            SUM( CASE WHEN ITRN_LOCCD='ARWH2' THEN
                CASE WHEN ITRN_IOFLG = '1' THEN ITRN_TRNQT
                ELSE -1*ITRN_TRNQT END
            ELSE
                0 END)  ARWH,
            SUM( CASE WHEN ITRN_LOCCD='NRWH2' THEN
                    CASE WHEN ITRN_IOFLG = '1' THEN ITRN_TRNQT
                    ELSE -1*ITRN_TRNQT END
                ELSE
                    0 END)  NRWH2,
            SUM( CASE WHEN ITRN_LOCCD='ARWH0PD' THEN
                    CASE WHEN ITRN_IOFLG = '1' THEN ITRN_TRNQT
                    ELSE -1*ITRN_TRNQT END
                ELSE
                    0 END)  ARWH0PD,
            SUM( CASE WHEN ITRN_LOCCD='PLANT2' THEN
                    CASE WHEN ITRN_IOFLG = '1' THEN ITRN_TRNQT
                    ELSE -1*ITRN_TRNQT END
                ELSE
                0 END) PLANT2,
            SUM( CASE WHEN ITRN_LOCCD='QA' THEN
                    CASE WHEN ITRN_IOFLG = '1' THEN ITRN_TRNQT
                    ELSE -1*ITRN_TRNQT END
                ELSE
                0 END) QA,
            '' JOB,
            '' PSN,
            0 JOBUNIT,
            0 QTYPCS,
            0 LOGRTN
                FROM XITRN_TBL
                LEFT JOIN XMITM_V ON ITRN_ITMCD=MITM_ITMCD
                WHERE ITRN_ISUDT<=? AND ITRN_LOCCD in ('PLANT2','ARWH2','QA','ARWH0PD','NRWH2')
                AND ITRN_BSGRP=? AND ITRN_ITMCD IN ($pParts)
        GROUP BY ITRN_ITMCD,MITM_ITMD1,MITM_SPTNO) VMEGA
        ORDER BY 1";
        $query = $this->db->query($qry, [$pDate, $pBG]);
        return $query->result_array();
    }

    public function select_allwip_plant1_byBG_and_Part($pDate, $pBG, $pParts)
    {
        $qry = "SELECT *,(ARWH+NRWH2+ARWH0PD+PLANT2+QA) STOCK FROM
        (SELECT RTRIM(ITRN_ITMCD) ITRN_ITMCD,RTRIM(MITM_ITMD1) ITMD1,RTRIM(MITM_SPTNO) MITM_SPTNO,
            SUM( CASE WHEN ITRN_LOCCD='ARWH1' THEN
                CASE WHEN ITRN_IOFLG = '1' THEN ITRN_TRNQT
                ELSE -1*ITRN_TRNQT END
            ELSE
                0 END)  ARWH,
        SUM( CASE WHEN ITRN_LOCCD='NRWH2' THEN
                CASE WHEN ITRN_IOFLG = '1' THEN ITRN_TRNQT
                ELSE -1*ITRN_TRNQT END
            ELSE
                0 END)  NRWH2,
        SUM( CASE WHEN ITRN_LOCCD='ARWH0PD' THEN
                CASE WHEN ITRN_IOFLG = '1' THEN ITRN_TRNQT
                ELSE -1*ITRN_TRNQT END
            ELSE
                0 END)  ARWH0PD,
        SUM( CASE WHEN ITRN_LOCCD='PLANT1' THEN
                CASE WHEN ITRN_IOFLG = '1' THEN ITRN_TRNQT
                ELSE -1*ITRN_TRNQT END
            ELSE
            0 END) PLANT2,
        SUM( CASE WHEN ITRN_LOCCD='QA' THEN
                CASE WHEN ITRN_IOFLG = '1' THEN ITRN_TRNQT
                ELSE -1*ITRN_TRNQT END
            ELSE
            0 END) QA,
            '' JOB,
            '' PSN,
            0 JOBUNIT,
            0 QTYPCS,
            0 LOGRTN
                FROM XITRN_TBL
                LEFT JOIN XMITM_V ON ITRN_ITMCD=MITM_ITMCD
                WHERE ITRN_ISUDT<=? AND ITRN_LOCCD in ('PLANT1','ARWH1','QA','ARWH0PD')
                AND ITRN_BSGRP=? AND ITRN_ITMCD IN ($pParts)
        GROUP BY ITRN_ITMCD,MITM_ITMD1,MITM_SPTNO) VMEGA
        ORDER BY 1";
        $query = $this->db->query($qry, [$pDate, $pBG]);
        return $query->result_array();
    }

    public function select_fg($date, $bg)
    {
        $qry = "wms_sp_fgstock ?, ?";
        $query = $this->db->query($qry, [$bg, $date]);
        return $query->result_array();
    }
    public function select_fg_byItemCodeArray($date, $bg, $aItems)
    {
        $qry = "SELECT V1.*,RTRIM(MITM_ITMD1) ITMD1 FROM
        (
            SELECT ITH_ITMCD
            ,ISNULL(SUM(CASE WHEN ITH_WH='ARPRD1' THEN ITH_QTY END),0) LOC_PRD
            ,ISNULL(SUM(CASE WHEN ITH_WH='ARQA1' THEN ITH_QTY END),0) LOC_QC
            ,ISNULL(SUM(CASE WHEN ITH_WH='AFWH3' THEN ITH_QTY END),0) LOC_AFWH3
            ,ISNULL(SUM(CASE WHEN ITH_WH='ARSHP' THEN ITH_QTY END),0) LOC_ARSHP
            ,ISNULL(SUM(CASE WHEN ITH_WH='QAFG' THEN ITH_QTY END),0) LOC_QAFG
            ,ISNULL(SUM(CASE WHEN ITH_WH='AFQART' THEN ITH_QTY END),0) LOC_AFQART
            ,ISNULL(SUM(CASE WHEN ITH_WH='AFQART2' THEN ITH_QTY END),0) LOC_AFQART2
            ,ISNULL(SUM(CASE WHEN ITH_WH='NFWH4RT' THEN ITH_QTY END),0) LOC_NFWH4RT
            ,ISNULL(SUM(CASE WHEN ITH_WH='AFWH3RT' THEN ITH_QTY END),0) LOC_AFWH3RT
            ,ISNULL(SUM(CASE WHEN ITH_WH='ARSHPRTN' THEN ITH_QTY END),0) LOC_ARSHPRTN
            ,ISNULL(SUM(CASE WHEN ITH_WH='ARSHPRTN2' THEN ITH_QTY END),0) LOC_ARSHPRTN2
            FROM v_ith_tblc
            LEFT JOIN SER_TBL ON ITH_SER=SER_ID
            WHERE ITH_WH IN ('ARPRD1','ARQA1','AFWH3','ARSHP','QAFG','AFQART','AFQART2','NFWH4RT','AFWH3RT','ARSHPRTN','ARSHPRTN2')
            AND ITH_ITMCD IN (
                select SER_ITMID SSO2_MDLCD from SER_TBL where SER_BSGRP=?
                group by SER_ITMID
                )
            AND ITH_DATEC<=?
            AND ITH_ITMCD IN ($aItems)
            AND ISNULL(SER_RMRK,'') not like '%scrap%'
            AND ISNULL(SER_RMRK,'') not like '%sample%'
            GROUP BY ITH_ITMCD
        ) V1
        LEFT JOIN MITM_TBL ON ITH_ITMCD=MITM_ITMCD";
        $query = $this->db->query($qry, [$bg, $date]);
        return $query->result_array();
    }

    public function select_psn_period($pdate1, $pdate2, $pItems)
    {
        $qry = "SELECT SUBSTRING(ITRN_DOCNO,1,19) DOC FROM XITRN_TBL WHERE ITRN_ITMCD in ($pItems)
        AND (ITRN_ISUDT BETWEEN '$pdate1' AND '$pdate2')
        AND ITRN_DOCCD='TRF'
        AND SUBSTRING(ITRN_DOCNO,1,19) NOT IN (SELECT SUBSTRING(ITRN_DOCNO,1,19) FROM XITRN_TBL WHERE ITRN_ITMCD in ($pItems)
                                                AND (ITRN_ISUDT BETWEEN '$pdate1' AND '$pdate2')
                                                AND ITRN_DOCNO LIKE '%SP-IEI%'  and ITRN_DOCNO like '%R%'
                                                GROUP BY SUBSTRING(ITRN_DOCNO,1,19))
        GROUP BY SUBSTRING(ITRN_DOCNO,1,19)";
        $query = $this->db->query($qry);
        return $query->result_array();
    }
    public function select_psn_period_byBG($pdate1, $pdate2, $pBG)
    {
        $qry = "SELECT VHEAD.* FROM
        (SELECT SUBSTRING(ITRN_DOCNO,1,19) DOC FROM XITRN_TBL WHERE ITRN_BSGRP=?
                AND (ITRN_ISUDT BETWEEN '$pdate1' AND '$pdate2')
                AND ITRN_DOCCD='TRF'
                GROUP BY SUBSTRING(ITRN_DOCNO,1,19)) VHEAD
        LEFT JOIN (
        SELECT SUBSTRING(ITRN_DOCNO,1,19) DOCD FROM XITRN_TBL WHERE ITRN_BSGRP=?
                                                        AND (ITRN_ISUDT BETWEEN '$pdate1' AND '$pdate2')
                                                        AND ITRN_DOCNO LIKE '%SP-%'  and ITRN_DOCNO like '%R%'
                                                        GROUP BY SUBSTRING(ITRN_DOCNO,1,19)
        ) VDETAIL ON VHEAD.DOC=VDETAIL.DOCD
        WHERE DOCD IS NULL AND SUBSTRING(DOC,1,3)!='TRF' and DOC not like '%SP-MAT%'";
        $query = $this->db->query($qry, [$pBG, $pBG]);
        return $query->result_array();
    }
    public function select_psn_period_byBG_and_Parts($pdate1, $pdate2, $pBG, $pParts)
    {
        $qry = "SELECT VHEAD.* FROM
        (SELECT SUBSTRING(ITRN_DOCNO,1,19) DOC FROM XITRN_TBL WHERE ITRN_BSGRP=?
                AND (ITRN_ISUDT BETWEEN '$pdate1' AND '$pdate2')
                AND ITRN_DOCCD='TRF'
                AND ITRN_ITMCD IN ($pParts)
                GROUP BY SUBSTRING(ITRN_DOCNO,1,19)) VHEAD
        LEFT JOIN (
        SELECT SUBSTRING(ITRN_DOCNO,1,19) DOCD FROM XITRN_TBL WHERE ITRN_BSGRP=?
                                                        AND (ITRN_ISUDT BETWEEN '$pdate1' AND '$pdate2')
                                                        AND ITRN_DOCNO LIKE '%SP-%'  and ITRN_DOCNO like '%R%'

                                                        GROUP BY SUBSTRING(ITRN_DOCNO,1,19)
        ) VDETAIL ON VHEAD.DOC=VDETAIL.DOCD
        WHERE DOCD IS NULL AND SUBSTRING(DOC,1,3)!='TRF' and DOC not like '%SP-MAT%'";
        $query = $this->db->query($qry, [$pBG, $pBG]);
        return $query->result_array();
    }
    public function select_psn_return_period($pdate1, $pdate2, $pItems)
    {
        $qry = "SELECT SUBSTRING(ITRN_DOCNO,1,19) DOC FROM XITRN_TBL WHERE ITRN_ITMCD in ($pItems)
        AND (ITRN_ISUDT BETWEEN ? AND ?)
        AND ITRN_DOCNO LIKE '%SP-IEI%'  and ITRN_DOCNO like '%R%'
        GROUP BY SUBSTRING(ITRN_DOCNO,1,19)";
        $query = $this->db->query($qry, [$pdate1, $pdate2]);
        return $query->result_array();
    }

    public function select_parent_locations(): array
    {
        $qry = "SELECT A.*,ISNULL(MSTLOCG_NM,'-') LOCATIONNM FROM
        (select RTRIM(ITRN_LOCCD) LOCATIONCD from XITRN_TBL
        group by ITRN_LOCCD
        UNION
        select RTRIM(FTRN_LOCCD) LOCATIONCD from XFTRN_TBL
        group by FTRN_LOCCD
        UNION
        SELECT RTRIM(MSTLOCG_ID) LOCATIONCD FROM MSTLOCG_TBL WHERE MSTLOCG_ID='PSIEQUIP'
        ) A LEFT JOIN MSTLOCG_TBL ON LOCATIONCD=MSTLOCG_ID
        ORDER BY 1";
        $query = $this->db->query($qry);
        return $query->result_array();
    }

    public function select_unconfirmed_psn($psn)
    {
        $qry = "wms_sp_unconfirmed_psn ?";
        $query = $this->db->query($qry, [$psn]);
        return $query->result_array();
    }

    public function select_inventory_saldo_awal($date, $itemcode)
    {
        $qry = "SELECT
                            it3a.ITH_ITMCD,
                            convert(integer,COALESCE(SUM(it3a.ITH_QTY),0)) AS ITH_QTY,
                            ITH_WH
                        FROM PSI_WMS.dbo.v_ith_tblc it3a
                        WHERE it3a.ITH_DATEC < ?
                        and ITH_ITMCD=?
                        AND it3a.ITH_WH IN (
                            'AFWH3',
                            'QAFG',
                            'AWIP1',
                            'ARSHP',
                            'ARSHPRTN',
                            'ARSHPRTN2',
                            'AFQART',
                            'AFQART2',
                            'AFWH3RT',
                            'NFWH4RT')
                        GROUP BY
                            it3a.ITH_ITMCD,ITH_WH";
        $query = $this->db->query($qry, [$date, $itemcode]);
        return $query->result_array();
    }

    public function select_inventory_pemasukan($datefrom, $dateto, $itemcode)
    {
        $qry = "SELECT
                    it3a.ITH_ITMCD,
                    COALESCE(SUM(it3a.ITH_QTY),0) AS ITH_QTY,
                    ITH_WH
                FROM PSI_WMS.dbo.v_ith_tblc it3a
                WHERE it3a.ITH_DATEC >= ? AND it3a.ITH_DATEC <= ?
                AND it3a.ITH_QTY > 0
                AND it3a.ITH_WH IN ('AFWH3','AWIP1','AFQART','AFQART2','NFWH4RT')
                AND it3a.ITH_FORM IN (
                    'INC-WH-FG',
                    'CONVERT-IN',
			        'INC',
			        'ADJ-INC',
			        'ADJ-I-INC',
			        'ADJ-O-INC',
			        'ADJ-IN',
			        'INC-WHRTN-FG-C'
                )
                AND (
                    CASE WHEN it3a.ITH_FORM = 'SPLIT-FG-LBL'
                    THEN
                        CASE WHEN it3a.ITH_REMARK = 'AFT-SPLIT'
                        THEN 1
                        ELSE 0 END
                    ELSE 1 END
                ) = 1
                AND ITH_ITMCD=?
                GROUP BY
                    it3a.ITH_ITMCD,ITH_WH";
        $query = $this->db->query($qry, [$datefrom, $dateto, $itemcode]);
        return $query->result_array();
    }

    public function select_inventory_pengeluaran($datefrom, $dateto, $itemcode)
    {
        $qry = "SELECT it3a.ITH_ITMCD,
                        COALESCE(SUM(it3a.ITH_QTY),0) AS ITH_QTY,
                        ITH_WH
                    FROM PSI_WMS.dbo.v_ith_tblc it3a
                    WHERE it3a.ITH_DATEC >= ? AND it3a.ITH_DATEC <= ?
                    AND it3a.ITH_WH IN (
                        'AFWH3',
                        'AFQART',
                        'AFQART2',
                        'AWIP1',
                        'QAFG',
                        'ARSHP',
                        'ARSHPRTN',
                        'ARSHPRTN2',
                        'AFWH4RT',
                        'NFWH4RT'
                    )
                    AND it3a.ITH_FORM IN (
                        'CONVERT-OUT',
                        'OUT-PEN-FG',
                        'OUT-USE',
                        'OUT-SHP-FG',
                        'ADJ-OUT',
                        'ADJ-I-OUT',
                        'ADJ-O-OUT',
                        'OUT-SCR-RTN',
                        'SPLIT-CNV-FG-OUT',
                        'OUT-C'
                    )
                    AND (
                        CASE WHEN it3a.ITH_FORM = 'SPLIT-FG-LBL'
                        THEN
                            CASE WHEN it3a.ITH_REMARK = 'AFT-SPLIT'
                            THEN 1
                            ELSE 0 END
                        ELSE 1 END
                    ) = 1
                    and ITH_ITMCD=?
                    GROUP BY
                        it3a.ITH_ITMCD,ITH_WH";
        $query = $this->db->query($qry, [$datefrom, $dateto, $itemcode]);
        return $query->result_array();
    }

    public function select_split_production($jobs)
    {
        $s_job = "'" . implode("','", $jobs) . "'";
        $qry = "SELECT
                    VALLQC.*
                FROM
                    (
                    SELECT
                        *
                    FROM
                        ITH_TBL
                    WHERE
                        ITH_DOC IN ($s_job)
                        AND ITH_WH IN ('ARQA1','AFWH3')
                        AND ITH_FORM IN ('INC-WH-FG','INC-QA-FG')
                    ) VALLQC
                    LEFT JOIN (
                    SELECT
                        *
                    FROM
                        ITH_TBL
                    WHERE
                        ITH_DOC IN ($s_job)
                        AND ITH_WH = 'ARPRD1'
                        AND ITH_FORM = 'SPLIT-FG-LBL'
                    ) VPRDSPLIT ON VALLQC.ITH_SER = VPRDSPLIT.ITH_SER
                WHERE
                    VPRDSPLIT.ITH_SER IS NOT NULL";
        $query = $this->db->query($qry);
        return $query->result_array();
    }

    public function select_report_dispose202212()
    {
        $qry = "SELECT VITH.*,RTRIM(SERD2_ITMCD) SERD2_ITMCD,SERD2_QTY,RTRIM(RM.MITM_ITMD1) RMDESC,RTRIM(FG.MITM_ITMD1) FGDESC
         FROM
        (SELECT ITH_SER,ITH_ITMCD,SUM(ITH_QTY) LQT FROM v_ith_tblc WHERE ITH_DATEC<='2022-12-02'
        AND ITH_WH='AFWH9SC'
        GROUP BY ITH_SER,ITH_ITMCD
        HAVING SUM(ITH_QTY)>0) VITH
        LEFT JOIN SERD2_TBL ON ITH_SER=SERD2_SER
        LEFT JOIN MITM_TBL RM ON SERD2_ITMCD=RM.MITM_ITMCD
        LEFT JOIN MITM_TBL FG ON ITH_ITMCD=FG.MITM_ITMCD
        ORDER BY ITH_SER,ITH_ITMCD,SERD2_ITMCD";
        $query = $this->db->query($qry);
        return $query->result_array();
    }

    public function selectLastTransactionBeforeDate($date, $item, $form)
    {
        $this->db->limit(1)
            ->from($this->TABLENAME)
            ->where('ITH_DATE <', $date)
            ->where('ITH_ITMCD', $item)
            ->where('ITH_FORM', $form)
            ->order_by('ITH_LUPDT DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectUnFixedFGCalculation($WO)
    {
        $qry = "SELECT
                    *
                FROM
                    (
                    SELECT
                        SER_ID,
                        SER_DOC,
                        SER_QTY,
                        SER_ITMID,
                        SUM(PER) TTLPER
                    FROM
                        (
                        SELECT
                            SER_ID,
                            SER_DOC,
                            SER_QTY,
                            SER_ITMID,
                            SERD2_ITMCD,
                            SUM(SERD2_QTY) RMQT,
                            SUM(SERD2_QTY) / MAXQT PER
                        FROM
                            (
                            select
                                ITH_SER,
                                ITH_WH,
                                SUM(ITH_QTY) SQT,
                                MAX(ITH_QTY) MAXQT
                            from
                                ITH_TBL
                            WHERE
                                ITH_WH IN ('QAFG', 'ARSHP', 'AFWH3','ARSHP', 'AWIP1')
                            GROUP BY
                                ITH_SER,
                                ITH_WH
                            HAVING
                                SUM(ITH_QTY) > 0
                            ) V1
                            left join SER_TBL ON ITH_SER = SER_ID
                            LEFT JOIN SERD2_TBL ON SER_ID = SERD2_SER
                        GROUP BY
                            SER_ID,
                            SER_DOC,
                            SER_QTY,
                            SER_ITMID,
                            SERD2_ITMCD,
                            MAXQT
                        ) V2
                    GROUP BY
                        SER_ID,
                        SER_DOC,
                        SER_ITMID,
                        SER_QTY
                    ) V3
                    LEFT JOIN WOH_TBL ON SER_DOC = WOH_CD
                WHERE
                    ISNULL(TTLPER, 0) != ISNULL(WOH_TTLUSE, 0)
                AND SER_DOC LIKE ?
                ORDER BY
                SER_DOC";
        $query = $this->db->query($qry, [$WO . '%']);
        return $query->result_array();
    }
}
