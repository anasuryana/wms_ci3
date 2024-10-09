<?php

class SER_mod extends CI_Model
{
    private $TABLENAME = "SER_TBL";
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

    public function deletebyID($parr)
    {
        $this->db->where($parr);
        $this->db->delete($this->TABLENAME);
        return $this->db->affected_rows();
    }
    public function check_Primary($data)
    {
        return $this->db->get_where($this->TABLENAME, $data)->num_rows();
    }

    public function lastserialid($modeltype, $prodt)
    {
        $qry = "exec sp_lastser @typemodel=" . $modeltype . ", @prodt='" . $prodt . "'";
        $query =  $this->db->query($qry);
        if ($query->num_rows() > 0) {
            $ret = $query->row();
            return $ret->lser;
        } else {
            return '0';
        }
    }
    public function lastserialid_ihour($modeltype, $prodt)
    {
        $qry = "exec sp_lastser_ihour @typemodel=" . $modeltype . ", @prodt='" . $prodt . "'";
        $query =  $this->db->query($qry);
        if ($query->num_rows() > 0) {
            $ret = $query->row();
            return $ret->lser;
        } else {
            return '0';
        }
    }

    public function selectbyVARg($pwhere)
    {
        $this->db->select('SER_DOC,UPPER(SER_ITMID) SER_ITMID,MITM_LBLCLR');
        $this->db->from($this->TABLENAME);
        $this->db->join('MITM_TBL', 'SER_ITMID=MITM_ITMCD');
        $this->db->like($pwhere);
        $this->db->group_by('SER_DOC,SER_ITMID,MITM_LBLCLR');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectWOByIDIn($IDs){
        $this->db->select('SER_DOC');
        $this->db->from($this->TABLENAME);
        $this->db->where_in('SER_ID',$IDs);
        $this->db->group_by('SER_DOC');
        $query = $this->db->get();
        return $query->result_array();
    }    

    public function selectDOCbyVARg($pwhere)
    {
        $this->db->select('SER_DOC');
        $this->db->from($this->TABLENAME);
        $this->db->like($pwhere);
        $this->db->group_by('SER_DOC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectbyVAR($pwhere)
    {
        $this->db->from($this->TABLENAME);
        $this->db->like($pwhere);
        $query = $this->db->get();
        return $query->result_array();
    }
    public function select_where($pwhere)
    {
        $this->db->from($this->TABLENAME);
        $this->db->where($pwhere);
        $query = $this->db->get();
        return $query->result_array();
    }
    public function selectbyVAR_with_cols($pwhere, $pcols)
    {
        $this->db->select($pcols);
        $this->db->from($this->TABLENAME);
        $this->db->like($pwhere);
        $query = $this->db->get();
        return $query->result_array();
    }

    function select_tools_ser($pSer_id)
    {
        $qry = "SELECT SER_ITMID, SER_QTY,ITH_QTY,SER_ID FROM SER_TBL 
        LEFT JOIN (SELECT DISTINCT ITH_SER, ABS(ITH_QTY) ITH_QTY FROM ITH_TBL WHERE ITH_REMARK='AFT-SPLIT') V1 ON SER_ID=ITH_SER
        WHERE SER_REFNO=? AND SER_REFNO!=SER_ID";
        $query = $this->db->query($qry, [$pSer_id]);
        return $query->result_array();
    }
    public function select_joinITH_byVAR_with_cols($pwhere, $pcols)
    {
        $this->db->select($pcols);
        $this->db->from($this->TABLENAME);
        $this->db->join("(SELECT DISTINCT ITH_SER, ABS(ITH_QTY) ITH_QTY FROM ITH_TBL WHERE ITH_REMARK='AFT-SPLIT') V1", "SER_ID=ITH_SER", "LEFT");
        $this->db->like($pwhere);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_checklistprintstatus_label($pjob, $pitem)
    {
        $qry = "select SER_TBL.*, convert(int,SER_QTY-isnull(v1.ITH_QTY,0)) BALQTY, convert(int,SER_QTY+isnull(v2.ITH_QTY,0)) BALJNTQTY,VLOC.ITH_WH from SER_TBL 
        left join
		(SELECT ITH_SER,ITH_QTY FROM ITH_TBL WHERE isnull(ITH_WH,'')='AWIP1' and isnull(ITH_QTY,0)>0 ) V1 on SER_ID=v1.ITH_SER
		left join
		(SELECT * FROM ITH_TBL WHERE isnull(ITH_WH,'')='AWIP1' and isnull(ITH_FORM,'')='OUT-USE') V2 on SER_ID=v2.ITH_SER
        LEFT JOIN 
		(
		select ITH_SER,ITH_WH,sum(ITH_QTY) STKQT from ith_tbl where ITH_SER is not null		
		group by ITH_SER,ITH_WH
		having sum(ITH_QTY)>0
		) VLOC ON SER_ID=VLOC.ITH_SER
        where SUBSTRING(SER_ID,1,1)='3' AND SER_DOC=? and SER_ITMID=? ORDER BY SER_ID";
        $query = $this->db->query($qry, [$pjob, $pitem]);
        return $query->result_array();
    }

    public function selectbyVAR_where($pwhere)
    {
        $this->db->from($this->TABLENAME);
        $this->db->where($pwhere);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_exact_byVAR($pwhere)
    {
        $this->db->select("a.*,ISNULL(MITM_SPQ,0) MITM_SPQ,ISNULL(MITM_SHTQTY,0) MITM_SHTQTY
        ,MITM_ITMD1,PDPP_BSGRP, ISNULL(SER_DOCTYPE,'') DOCTYPE");
        $this->db->from($this->TABLENAME . " a");
        $this->db->join('MITM_TBL', 'SER_ITMID=MITM_ITMCD', 'LEFT');
        $this->db->join('XWO', 'SER_DOC=PDPP_WONO', 'LEFT');
        $this->db->where($pwhere);
        $query = $this->db->get();
        return $query->result_array();
    }
    public function select_master_delivery($pser)
    {
        $this->db->select("SER_DOC,DLV_ID");
        $this->db->from($this->TABLENAME . " a");
        $this->db->join('MITM_TBL', 'SER_ITMID=MITM_ITMCD', 'LEFT');
        $this->db->join('DLV_TBL', 'SER_ID=DLV_SER', 'LEFT');
        $this->db->where("SER_ID", $pser);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_SER_byVAR($pwhere)
    {
        $this->db->select("a.*,ISNULL(MITM_SPQ,0) MITM_SPQ,ISNULL(MITM_SHTQTY,0) MITM_SHTQTY
        ,MITM_ITMD1,ISNULL(SER_DOCTYPE,'') DOCTYPE");
        $this->db->from($this->TABLENAME . " a");
        $this->db->join('MITM_TBL', 'SER_ITMID=MITM_ITMCD', 'LEFT');
        $this->db->where($pwhere);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectBCField_in($pser)
    {
        $this->db->select("SER_ID,SER_ITMID,MITM_ITMD1,SER_PRDDT,SER_DOC,SER_PRDLINE,SER_PRDSHFT,SER_QTY,SER_SHEET,MCUS_CUSNM,MITM_STKUOM,SER_GRADE MBOM_GRADE,PDPP_BSGRP,SER_RMRK");
        $this->db->from($this->TABLENAME . ' a');
        $this->db->join('MITM_TBL b', 'a.SER_ITMID=b.MITM_ITMCD', 'LEFT');
        $this->db->join('XWO c', 'a.SER_DOC=c.PDPP_WONO', 'LEFT');
        $this->db->join('MCUS_TBL d', 'c.PDPP_CUSCD=d.MCUS_CUSCD', 'LEFT');
        $this->db->where_in('SER_ID', $pser);
        $query = $this->db->get();
        return $query->result();
    }
    public function selectBC_RTN_Field_in($pdoc, $pid)
    {
        $this->db->select("SER_ID,SER_ITMID,MITM_ITMD1,SER_PRDDT,SER_DOC,SER_PRDLINE,SER_PRDSHFT,SER_QTY,SER_SHEET,MITM_STKUOM,SER_RMRK, RETFG_STRDT,RETFG_RMRK");
        $this->db->from($this->TABLENAME . ' a');
        $this->db->join('MITM_TBL b', 'a.SER_ITMID=b.MITM_ITMCD', 'LEFT');
        $this->db->join('RETFG_TBL', 'SER_DOC=RETFG_DOCNO AND SER_PRDLINE=RETFG_LINE');
        $this->db->where('SER_DOC', $pdoc)->where_in("SER_PRDLINE", $pid);
        $query = $this->db->get();
        return $query->result();
    }
    public function selectBC_RTN_Field_byid_in($pser)
    {
        $this->db->select("SER_ID,SER_ITMID,MITM_ITMD1,SER_PRDDT,SER_DOC,SER_PRDLINE,SER_PRDSHFT,SER_QTY,SER_SHEET,MITM_STKUOM,SER_RMRK, RETFG_STRDT,RETFG_RMRK");
        $this->db->from($this->TABLENAME . ' a');
        $this->db->join('MITM_TBL b', 'a.SER_ITMID=b.MITM_ITMCD', 'LEFT');
        $this->db->join('RETFG_TBL', 'SER_DOC=RETFG_DOCNO AND SER_PRDLINE=RETFG_LINE');
        $this->db->where_in('SER_ID', $pser);
        $query = $this->db->get();
        return $query->result();
    }
    public function selectBCField_in_nomega($pser)
    {
        $this->db->select("SER_ID,SER_ITMID,MITM_ITMD1,SER_PRDDT,SER_DOC,SER_PRDLINE,SER_PRDSHFT,SER_QTY,SER_SHEET,'' MCUS_CUSNM,MITM_STKUOM,SER_GRADE MBOM_GRADE, '' PDPP_BSGRP");
        $this->db->from($this->TABLENAME . ' a');
        $this->db->join('MITM_TBL b', 'a.SER_ITMID=b.MITM_ITMCD', 'LEFT');
        $this->db->where_in('SER_ID', $pser);
        $query = $this->db->get();
        return $query->result();
    }
    public function selectBCFieldRM_in($pser)
    {
        $this->db->select("SER_ID,SER_ITMID,MITM_ITMD1,SER_PRDDT,SER_DOC,SER_QTY,MITM_STKUOM,SER_LOTNO,MITM_SPTNO,SER_USRID,MSTEMP_FNM,SER_ROHS,MMADE_NM");
        $this->db->from($this->TABLENAME . ' a');
        $this->db->join('MITM_TBL b', 'a.SER_ITMID=b.MITM_ITMCD', 'inner');
        $this->db->join('MSTEMP_TBL c', 'a.SER_USRID=c.MSTEMP_ID', 'inner');
        $this->db->join('MMADE_TBL d', 'a.SER_CNTRYID=d.MMADE_CD', 'left');
        $this->db->where_in('SER_ID', $pser);
        $query = $this->db->get();
        return $query->result();
    }

    public function select_joblbl_ost()
    {
        $qry = "exec sp_joblbl_ost";
        $query = $this->db->query($qry);
        return $query->result_array();
    }
    public function select_conversion_test($pdate, $pdate2, $pmodel, $pAJU)
    {
        $qry = "wms_sp_conversion_test ?, ?, ?, ?";
        $query = $this->db->query($qry, [$pdate, $pdate2, $pmodel, $pAJU]);
        return $query->result_array();
    }
    public function select_joblbl_ost_pitem($pitem)
    {
        $qry = "exec sp_joblbl_ost_byitem ?";
        $query = $this->db->query($qry, array($pitem));
        return $query->result_array();
    }

    public function select_sususan_bahan_baku($location)
    {
        $qry = "sp_check_sususan_bahan_baku ?";
        $query = $this->db->query($qry, [$location]);
        return $query->result_array();
    }
    public function select_sususan_bahan_baku_by_job($pdoc)
    {
        $qry = "sp_check_sususan_bahan_baku_by_job ?";
        $query = $this->db->query($qry, [$pdoc]);
        return $query->result_array();
    }
    public function select_sususan_bahan_baku_by_txid($pdoc)
    {
        $qry = "sp_check_sususan_bahan_baku_by_txid ?";
        $query = $this->db->query($qry, [$pdoc]);
        return $query->result_array();
    }
    public function select_sususan_bahan_baku_by_txid_v2($pdoc)
    {
        $qry = "sp_check_sususan_bahan_baku_by_txid_v2 ?";
        $query = $this->db->query($qry, [$pdoc]);
        return $query->result_array();
    }
    public function select_sususan_bahan_baku_by_txid_nonmcz($pdoc)
    {
        $qry = "sp_check_sususan_bahan_baku_by_txid_nonmcz ?";
        $query = $this->db->query($qry, [$pdoc]);
        return $query->result_array();
    }
    public function select_sususan_bahan_baku_filter_items($pwhere)
    {
        $this->db->from('vcheck_sususan_bahan_baku');
        $this->db->where_in('SER_ITMID', $pwhere);
        $this->db->order_by('SER_DOC');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function select_combine_byAju_and_FG($pAju, $pFG)
    {
        $this->db->from("DLV_TBL");
        $this->db->join("SERD2_TBL A", "DLV_SER=A.SERD2_SER", "LEFT");
        $this->db->join("SER_TBL", "DLV_SER=SER_ID", "LEFT");
        $this->db->join("SERC_TBL", "DLV_SER=SERC_NEWID", "LEFT");
        $this->db->join("SERD2_TBL B", "SERC_COMID=B.SERD2_SER", "LEFT");
        $this->db->join("MITM_TBL", "B.SERD2_ITMCD=MITM_ITMCD", "LEFT");
        $this->db->join("(
            SELECT PPSN1_WONO
                ,PPSN1_BOMRV
            FROM XPPSN1
            GROUP BY PPSN1_WONO
                ,PPSN1_BOMRV
            ) VPSN", "SERC_COMJOB=PPSN1_WONO", "LEFT");
        $this->db->where_in('SER_ITMID', $pFG)->where_in("DLV_ZNOMOR_AJU", $pAju)->where("A.SERD2_SER is null", null, false);
        $this->db->group_by("DLV_ZNOMOR_AJU,SER_ITMID,B.SERD2_ITMCD,PPSN1_BOMRV,MITM_ITMD1,DLV_SER,DLV_QTY");
        $this->db->select("DLV_ZNOMOR_AJU,SER_ITMID,B.SERD2_ITMCD,PPSN1_BOMRV,sum(B.SERD2_QTY) RMQT,DLV_QTY DLVQT,sum(B.SERD2_QTY)/DLV_QTY PER,RTRIM(MITM_ITMD1) PARTDESCRIPTION,DLV_SER");
        $this->db->order_by('DLV_ZNOMOR_AJU');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function select_sususan_bahan_baku_filter_jobsitems($pjobs, $pitems)
    {
        $this->db->from('vcheck_sususan_bahan_baku');
        $this->db->where_in('SER_DOC', $pjobs)->where_in('SER_ITMID', $pitems);
        $this->db->order_by('SER_DOC');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function select_sususan_bahan_baku_filter_jobs($pwhere)
    {
        $this->db->from('vcheck_sususan_bahan_baku');
        $this->db->where_in('SER_DOC', $pwhere);
        $this->db->order_by('SER_DOC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function updatebyId($pdata, $pkey)
    {
        $this->db->where('SER_ID', $pkey);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
    }

    public function updatebySER($pdata, $pSer)
    {
        $this->db->where_in('SER_ID', $pSer);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
    }

    public function selectsync()
    {
        $qry = "select RTRIM(PDPP_MDLCD) PDPP_MDLCD,RTRIM(PDPP_WONO) PDPP_WONO,PDPP_WORQT,PDPP_GRNQT,TTLCARD,CONVERT(BIGINT,(PDPP_GRNQT-isnull(TTLCARD,0))) ADDQTY from XWO 
        left join
        ( select SER_DOC,sum(SER_QTYLOT) TTLCARD from SER_TBL
        group by SER_DOC
        ) v1 on PDPP_WONO=SER_DOC         
        where PDPP_COMFG='0' 
        AND PDPP_BSGRP='PSI1PPZIEP' 
        AND PDPP_GRNQT > ISNULL(TTLCARD,0)";
        $query = $this->db->query($qry);
        return $query->result_array();
    }

    public function select_whcd_rtn($pser)
    {
        $qry = "SELECT TOP 1 RTRIM(STKTRND1_LOCCDFR) STKTRND1_LOCCDFR,A.SER_ITMID,A.SER_QTY,B.SER_DOC FROM SER_TBL A
            LEFT JOIN SER_TBL B ON A.SER_REFNO=B.SER_ID
            LEFT JOIN XVU_RTN ON  B.SER_DOC=STKTRND1_DOCNO
            WHERE A.SER_ID=?";
        $query = $this->db->query($qry, [$pser]);
        return $query->result_array();
    }

    public function select_qcunconform($pbg)
    {
        $this->db->from('vqc_unconform');
        $this->db->where('PDPP_BSGRP', $pbg);
        $this->db->order_by('SER_LUPDT ASC');
        $query = $this->db->get();
        return $query->result();
    }

    public function todel()
    {
        //top 1000 vx.*,REF
        $qry = "SELECT TOP 1000 ITH_SER FROM
        (select v1.*,ITH_LOC from
        (select ITH_ITMCD,ITH_WH,ITH_SER,SUM(ITH_QTY) ITH_QTY, MAX(ITH_LUPDT) LTIME,isnull(max(ITH_LINE),'') MXLINE from ITH_TBL where  ITH_WH='AFWH3'
        group by ITH_WH,ITH_SER,ITH_ITMCD
        having sum(ITH_QTY) >0) v1
        inner join ITH_TBL b on v1.ITH_SER=b.ITH_SER and LTIME=ITH_LUPDT 
        where b.ith_qty>0 ) vx
        left join 
         st062020 on vx.ITH_SER=REF
         WHERE REF IS NULL AND SUBSTRING(ITH_sER,1,1)!='1'";
        $query = $this->db->query($qry);
        return $query->result_array();
    }

    public function select_balance_ref_rad($pdoc, $pitem)
    {
        $qry = "SELECT SER_ID,SER_DOC,SER_QTY,ISNULL(USEQTY,0) USEQTY, (SER_QTY+ISNULL(USEQTY,0)) BALQTY FROM
        (SELECT * FROM SER_TBL WHERE SUBSTRING(SER_ID,1,1) ='4') V1
        LEFT JOIN 
        (
        SELECT ITH_SER,SUM(ITH_QTY) USEQTY FROM ITH_TBL WHERE ITH_WH='AFQART' AND ITH_QTY<0 GROUP BY ITH_SER 
        ) V2 ON V1.SER_ID=V2.ITH_SER
        WHERE (SER_QTY+ISNULL(USEQTY,0)) >0 AND SER_DOC=? AND SER_ITMID=?";
        $query = $this->db->query($qry, [$pdoc, $pitem]);
        return $query->result_array();
    }

    function select_fgrtn_oldassy_newassy($newReff)
    {
        $qry = "SELECT V1.*,RTRIM(SER_ITMID) OLDFG FROM
        (SELECT RTRIM(SER_ITMID) NEWFG,SER_REFNO FROM SER_TBL WHERE SER_ID=?) V1
        LEFT JOIN SER_TBL ON V1.SER_REFNO=SER_TBL.SER_ID";
        $query = $this->db->query($qry, [$newReff]);
        return $query->result_array();
    }

    public function select_jm($preffno)
    {
        $this->db->select("WMS_ChkPcb.*, CONCAT(MSTEMP_FNM, ' ', MSTEMP_LNM) FULLNM");
        $this->db->from('WMS_ChkPcb');
        $this->db->join('MSTEMP_TBL', "cPic=MSTEMP_ID", "left");
        $this->db->where('Box_id', $preffno);
        $this->db->order_by('cdate ASC');
        $query = $this->db->get();
        return $query->result();
    }

    public function select_combine($preffno)
    {
        $this->db->select("SERC_TBL.*, CONCAT(MSTEMP_FNM, ' ', MSTEMP_LNM) FULLNM,SERD2_SER");
        $this->db->from("SERC_TBL");
        $this->db->join('MSTEMP_TBL', "SERC_USRID=MSTEMP_ID", "left");
        $this->db->join('(SELECT SERD2_SER FROM SERD2_TBL GROUP BY SERD2_SER) VSERD', "SERC_COMID=SERD2_SER", "left");
        $this->db->where('SERC_NEWID', $preffno);
        $this->db->order_by('SERC_LUPDT ASC');
        $query = $this->db->get();
        return $query->result();
    }

    public function select_validate_emergency($preffno)
    {
        $this->db->select("SER_ID,ISNULL(VITHDLV.ITH_SER,VITHWIP.ITH_SER) DLVSER,SERD2_SER,isnull(SERC_COMQTY,TQTY) TQTY");
        $this->db->from($this->TABLENAME);
        $this->db->join("(SELECT * FROM ITH_TBL WHERE ITH_SER='" . $preffno . "' AND ITH_FORM like '%OUT-SHP%') VITHDLV", "SER_ID=VITHDLV.ITH_SER", "left");
        $this->db->join("(SELECT * FROM ITH_TBL WHERE ITH_SER='" . $preffno . "' AND ITH_FORM like '%OUT-USE%') VITHWIP", "SER_ID=VITHWIP.ITH_SER", "left");
        $this->db->join("(SELECT TOP 1 ITH_SER,ABS(ITH_QTY) TQTY FROM ITH_TBL WHERE ITH_SER='" . $preffno . "') VITH", "SER_ID=VITH.ITH_SER", "left");
        $this->db->join("(SELECT SERD2_SER FROM SERD2_TBL WHERE SERD2_SER='" . $preffno . "' GROUP BY SERD2_SER) VSERD2", "SER_ID=VSERD2.SERD2_SER", "left");
        $this->db->join('SERC_TBL', "SER_ID=SERC_COMID", "left");
        $this->db->where('SER_ID', $preffno);
        $query = $this->db->get();
        return $query->result();
    }

    function select_split_need_fix($pYear, $pMonth)
    {
        $qry = "SELECT * FROM ITH_TBL WHERE ITH_SER IN (
            SELECT ITH_SER FROM
                (
                    SELECT * FROM v_ith_tblc WHERE YEAR(ITH_DATEC) = ?
                    AND MONTH(ITH_DATEC) = ?
                    AND ITH_FORM LIKE '%SPLIT%' AND ITH_REMARK='WIL-SPLIT'
                    AND ITH_WH='AFWH3'
                ) V1 LEFT JOIN SER_TBL ON ITH_SER=SER_REFNO
                WHERE ITH_ITMCD!=SER_ITMID
                GROUP BY ITH_SER
            ) AND ITH_FORM LIKE '%SPLIT%' AND ITH_REMARK='WIL-SPLIT'
                    AND ITH_WH='AFWH3'";
        $query = $this->db->query($qry, [$pYear, $pMonth]);
        return $query->result_array();
    }

    public function selectOutput($pwhere)
    {
        $this->db->from($this->TABLENAME);
        $this->db->select("RTRIM(SER_DOC) WONO, SUM(SER_QTY) OUTQT");
        $this->db->where($pwhere);
        $this->db->group_by('SER_DOC');
        $query = $this->db->get();
        return $query->result_array();
    }
}
