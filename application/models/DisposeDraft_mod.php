<?php

class DisposeDraft_mod extends CI_Model {
	private $TABLENAME = "?";
	public function __construct()
    {
        $this->load->database();
    }	
    
    public function select_resume_rm(){
        // $qry = "SELECT ITEM PART_CODE,sum(TQTY) QTY FROM 
        // (select ISNULL(MITMGRP_ITMCD,PART_CODE) ITEM,SUM(QTY) TQTY from DISPOSEDRAFT 
        // LEFT JOIN MITMGRP_TBL ON PART_CODE=MITMGRP_ITMCD_GRD        
        // GROUP BY MITMGRP_ITMCD,PART_CODE) VITEM
        // WHERE ITEM NOT IN (
        //     '128385000',
        //     '128430000',
        //     '218773700',
        //     '4220029800',
        //     '8069156400',
        //     'E06166',
        //     'E09573',
        //     'E12394',
        //     'M81417-05',
        //     'M81422-06',
        //     'M81511-01',
        //     'M89024-02',
        //     '2039838-2I',
        //     '216315200'
        // )
        // GROUP BY ITEM
        // ORDER BY ITEM";
        // $qry = "SELECT ITEM PART_CODE,sum(TQTY) QTY FROM 
        // (select ISNULL(MITMGRP_ITMCD,PART_CODE) ITEM,SUM(QTY) TQTY from DISPOSEDRAFT 
        // LEFT JOIN MITMGRP_TBL ON PART_CODE=MITMGRP_ITMCD_GRD        
        // GROUP BY MITMGRP_ITMCD,PART_CODE) VITEM
        // WHERE ITEM IN (
        //     '128385000',
        //     '128430000',
        //     '218773700',
        //     '4220029800',
        //     '8069156400',
        //     'E06166',
        //     'E09573',
        //     'E12394',
        //     'M81417-05',
        //     'M81422-06',
        //     'M81511-01',
        //     'M89024-02'         
        // )
        // GROUP BY ITEM
        // ORDER BY ITEM";        
        $qry = "SELECT ITEM PART_CODE,sum(TQTY) QTY FROM 
        (select ISNULL(MITMGRP_ITMCD,PART_CODE) ITEM,SUM(QTY) TQTY from DISPOSEDRAFT 
        LEFT JOIN MITMGRP_TBL ON PART_CODE=MITMGRP_ITMCD_GRD        
        GROUP BY MITMGRP_ITMCD,PART_CODE) VITEM
        WHERE ITEM IN (
            '2039838-2',
            '211395100'            
        )
        GROUP BY ITEM
        ORDER BY ITEM";
		$query = $this->db->query($qry);
        return $query->result_array();
    }

    public function select_arwh9sc($date){
        $qry = "SELECT ITEM PART_CODE,sum(TQTY) QTY FROM 
        (select ISNULL(MITMGRP_ITMCD,ITH_ITMCD) ITEM,SUM(ITH_QTY) TQTY from v_ith_tblc 
        LEFT JOIN MITMGRP_TBL ON ITH_ITMCD=MITMGRP_ITMCD_GRD        
		WHERE ITH_DATEC<=? AND ITH_WH='ARWH9SC'
        GROUP BY MITMGRP_ITMCD,ITH_ITMCD) VITEM
        GROUP BY ITEM
        ORDER BY ITEM";
        $query = $this->db->query($qry, [$date]);
        return $query->result_array();
    }

    public function select_resume_rm_additional1(){
        $qry = "SELECT ITEM PART_CODE,sum(TQTY) QTY FROM 
        (select ISNULL(MITMGRP_ITMCD,PART_CODE) ITEM,SUM(QTY) TQTY from DISPOSEDRAFT2
        LEFT JOIN MITMGRP_TBL ON PART_CODE=MITMGRP_ITMCD_GRD        
        GROUP BY MITMGRP_ITMCD,PART_CODE) VITEM        
        GROUP BY ITEM
        ORDER BY ITEM";        
		$query = $this->db->query($qry);
        return $query->result_array();
    }
    
    public function select_resume_fg($pDate){
        $qry = "SELECT ITH_ITMCD,RTRIM(SERD2_ITMCD) PART_CODE,round(SUM(RMQT),0) QTY,RTRIM(RM.MITM_ITMD1) RMDESC FROM
        (select ITH_WH,ITH_ITMCD,MITM_ITMD1,MITM_SPTNO,SUM(ITH_QTY) STOCKQTY,MITM_STKUOM,ITH_SER,SER_DOC,MAX(ITH_LUPDT) ITH_LUPDT
                from v_ith_tblc a 
                INNER join MITM_TBL b on a.ITH_ITMCD=b.MITM_ITMCD
                LEFT JOIN SER_TBL ON ITH_SER=SER_ID
                WHERE ITH_WH='AFWH9SC' AND ITH_FORM NOT IN ('SASTART','SA') and ITH_DATEC<=?
                GROUP BY ITH_ITMCD,ITH_WH,MITM_SPTNO,MITM_STKUOM,MITM_ITMD1,ITH_SER,SER_DOC
                HAVING SUM(ITH_QTY)>0) VDETAIL
        LEFT JOIN (SELECT SERD2_SER,SERD2_ITMCD,SUM(SERD2_QTY) RMQT FROM SERD2_TBL GROUP BY SERD2_SER,SERD2_ITMCD) VSERD2 ON ITH_SER=SERD2_SER
        LEFT JOIN MITM_TBL RM ON SERD2_ITMCD=MITM_ITMCD
        GROUP BY ITH_ITMCD,SERD2_ITMCD,VDETAIL.MITM_SPTNO, RM.MITM_ITMD1
        ORDER BY ITH_ITMCD,SERD2_ITMCD ASC";        
		$query = $this->db->query($qry, [$pDate]);
        return $query->result_array();
    }
    public function select_detail_fg($pDate){
        $qry = "SELECT ITH_ITMCD,RTRIM(SERD2_ITMCD) PART_CODE,round(SUM(RMQT),0) QTY
        ,RTRIM(RM.MITM_ITMD1) RMDESC
        ,RTRIM(VDETAIL.MITM_STKUOM) FGUOM
        ,RTRIM(RM.MITM_STKUOM) RMUOM
        ,VDETAIL.MITM_ITMD1 FGDESC
        ,ITH_SER
        ,STOCKQTY FROM
        (select ITH_WH,ITH_ITMCD,RTRIM(MITM_ITMD1) MITM_ITMD1,MITM_SPTNO,SUM(ITH_QTY) STOCKQTY,MITM_STKUOM,ITH_SER,SER_DOC,MAX(ITH_LUPDT) ITH_LUPDT
                from v_ith_tblc a 
                INNER join MITM_TBL b on a.ITH_ITMCD=b.MITM_ITMCD
                LEFT JOIN SER_TBL ON ITH_SER=SER_ID
                WHERE ITH_WH='AFWH9SC' AND ITH_FORM NOT IN ('SASTART','SA') and ITH_DATEC<=?
                GROUP BY ITH_ITMCD,ITH_WH,MITM_SPTNO,MITM_STKUOM,MITM_ITMD1,ITH_SER,SER_DOC
                HAVING SUM(ITH_QTY)>0) VDETAIL
        LEFT JOIN (SELECT SERD2_SER,SERD2_ITMCD,SUM(SERD2_QTY) RMQT FROM SERD2_TBL GROUP BY SERD2_SER,SERD2_ITMCD) VSERD2 ON ITH_SER=SERD2_SER
        LEFT JOIN MITM_TBL RM ON SERD2_ITMCD=MITM_ITMCD
        GROUP BY ITH_ITMCD,SERD2_ITMCD, RM.MITM_ITMD1,ITH_SER,STOCKQTY,VDETAIL.MITM_ITMD1, VDETAIL.MITM_STKUOM,RM.MITM_STKUOM
        ORDER BY ITH_ITMCD,ITH_SER,SERD2_ITMCD ASC";        
		$query = $this->db->query($qry, [$pDate]);
        return $query->result_array();
    }
    public function select_detail_fg_202212_additional(){
        $qry = "SELECT SER_ITMID ITH_ITMCD
        ,RTRIM(SERD2_ITMCD) PART_CODE
        ,round(SUM(RMQT), 0) QTY
        ,RTRIM(RM.MITM_ITMD1) RMDESC
        ,RTRIM(VDETAIL.MITM_STKUOM) FGUOM
        ,RTRIM(RM.MITM_STKUOM) RMUOM
        ,VDETAIL.MITM_ITMD1 FGDESC
        ,SER_ID ITH_SER
        ,SER_QTY LQT
    FROM (
        SELECT SER_ID
            ,SER_QTY
            ,SER_ITMID
            ,MITM_STKUOM
            ,MITM_ITMD1
        FROM SER_TBL
        LEFT JOIN MITM_TBL ON SER_ITMID = MITM_ITMCD
        WHERE SER_ID IN (
                'GHSWX6UWE81I1NPI'
                ,'GHSWX6UWE31I28TO'
                ,'GHSWX6UWE51II2CQ'
                ,'GHSWX6UWDZ1I2K1G'
                ,'GHSWX6UWE71I3EI1'
                )
        ) VDETAIL
    LEFT JOIN (
        SELECT SERD2_SER
            ,SERD2_ITMCD
            ,SUM(SERD2_QTY) RMQT
        FROM SERD2_TBL
        LEFT JOIN MITM_TBL ON SERD2_ITMCD=MITM_ITMCD WHERE MITM_MODEL='0'
        GROUP BY SERD2_SER
            ,SERD2_ITMCD
        ) VSERD2 ON SER_ID = SERD2_SER
    LEFT JOIN MITM_TBL RM ON SERD2_ITMCD = RM.MITM_ITMCD
    GROUP BY SER_ITMID
        ,SERD2_ITMCD
        ,RM.MITM_ITMD1
        ,SER_ID
        ,SER_QTY
        ,VDETAIL.MITM_ITMD1
        ,VDETAIL.MITM_STKUOM
        ,RM.MITM_STKUOM
    
    union all
    
    SELECT SER_ITMID ITH_ITMCD
        ,RTRIM(SERD2_ITMCD) PART_CODE
        ,round(SUM(RMQT), 0) QTY
        ,RTRIM(RM.MITM_ITMD1) RMDESC
        ,RTRIM(VDETAIL.MITM_STKUOM) FGUOM
        ,RTRIM(RM.MITM_STKUOM) RMUOM
        ,VDETAIL.MITM_ITMD1 FGDESC
        ,SER_ID ITH_SER
        ,SER_QTY STOCKQTY
    FROM (
        SELECT SERML_NEWID SER_ID
            ,SERML_COMID 
            ,MITM_STKUOM
            ,MITM_ITMD1
            ,SER_QTY
            ,SER_ITMID
        FROM SERML_TBL
        LEFT JOIN SER_TBL ON SERML_NEWID = SER_ID
        LEFT JOIN MITM_TBL ON SER_ITMID = MITM_ITMCD
        WHERE SERML_NEWID IN (
                'GHSWX6UWE81I1NPI'
                ,'GHSWX6UWE31I28TO'
                ,'GHSWX6UWE51II2CQ'
                ,'GHSWX6UWDZ1I2K1G'
                ,'GHSWX6UWE71I3EI1'
                )	
        ) VDETAIL
    LEFT JOIN (
        SELECT SERD2_SER
            ,SERD2_ITMCD
            ,SUM(SERD2_QTY) RMQT
        FROM SERD2_TBL
        GROUP BY SERD2_SER
            ,SERD2_ITMCD
        ) VSERD2 ON SERML_COMID = SERD2_SER
    LEFT JOIN MITM_TBL RM ON SERD2_ITMCD = RM.MITM_ITMCD
    GROUP BY SER_ITMID
        ,SERD2_ITMCD
        ,RM.MITM_ITMD1
        ,SER_ID
        ,SER_QTY
        ,VDETAIL.MITM_ITMD1
        ,VDETAIL.MITM_STKUOM
        ,RM.MITM_STKUOM";        
		$query = $this->db->query($qry);
        return $query->result_array();
    }
    public function select_resume_fg_dedicated($pSER){
        $qry = "SELECT ITH_ITMCD,RTRIM(SERD2_ITMCD) PART_CODE,round(SUM(RMQT),0) QTY
        ,RTRIM(RM.MITM_ITMD1) RMDESC
        ,RTRIM(VDETAIL.MITM_STKUOM) FGUOM
        ,RTRIM(RM.MITM_STKUOM) RMUOM
        ,VDETAIL.MITM_ITMD1 FGDESC
        ,ITH_SER
        ,STOCKQTY FROM
        (select '' ITH_WH,SER_ITMID ITH_ITMCD,MITM_ITMD1,MITM_SPTNO,SER_QTY STOCKQTY,MITM_STKUOM,SER_ID ITH_SER,SER_DOC,SER_LUPDT ITH_LUPDT
                from SER_TBL a 
                INNER join MITM_TBL b on a.SER_ITMID=b.MITM_ITMCD
                WHERE SER_ID IN ($pSER)
                ) VDETAIL
        LEFT JOIN (SELECT SERD2_SER,SERD2_ITMCD,SUM(SERD2_QTY) RMQT FROM SERD2_TBL GROUP BY SERD2_SER,SERD2_ITMCD) VSERD2 ON ITH_SER=SERD2_SER
        LEFT JOIN MITM_TBL RM ON SERD2_ITMCD=MITM_ITMCD
        GROUP BY ITH_ITMCD,SERD2_ITMCD, RM.MITM_ITMD1,ITH_SER,STOCKQTY,VDETAIL.MITM_ITMD1, VDETAIL.MITM_STKUOM,RM.MITM_STKUOM
        ORDER BY ITH_ITMCD,ITH_SER,SERD2_ITMCD ASC";        
		$query = $this->db->query($qry);
        return $query->result_array();
    }
    public function select_resume_fg_dedicated_per_assy($pSER){
        $qry = "SELECT V1.*,STOCKQTY FROM
        (SELECT ITH_ITMCD,UPPER(RTRIM(SERD2_ITMCD)) PART_CODE,round(SUM(RMQT),0) QTY
                ,RTRIM(RM.MITM_ITMD1) RMDESC
                ,RTRIM(VDETAIL.MITM_STKUOM) FGUOM
                ,RTRIM(RM.MITM_STKUOM) RMUOM
                ,VDETAIL.MITM_ITMD1 FGDESC
                ,CONCAT(ITH_ITMCD,RTRIM(SERD2_ITMCD)) ITH_SER
                FROM
                (select '' ITH_WH,SER_ITMID ITH_ITMCD,MITM_ITMD1,MITM_SPTNO,MITM_STKUOM,SER_ID ITH_SER,SER_DOC,SER_LUPDT ITH_LUPDT
                        from SER_TBL a 
                        INNER join MITM_TBL b on a.SER_ITMID=b.MITM_ITMCD
                        WHERE SER_ID IN ($pSER)
                        ) VDETAIL
                LEFT JOIN (SELECT SERD2_SER,ISNULL(MITMGRP_ITMCD,SERD2_ITMCD) SERD2_ITMCD,SUM(SERD2_QTY) RMQT FROM SERD2_TBL 
                    LEFT JOIN MITMGRP_TBL ON SERD2_ITMCD=MITMGRP_ITMCD_GRD GROUP BY SERD2_SER,SERD2_ITMCD,MITMGRP_ITMCD) VSERD2 ON ITH_SER=SERD2_SER
                LEFT JOIN MITM_TBL RM ON SERD2_ITMCD=MITM_ITMCD
                GROUP BY ITH_ITMCD,SERD2_ITMCD, RM.MITM_ITMD1,VDETAIL.MITM_ITMD1, VDETAIL.MITM_STKUOM,RM.MITM_STKUOM)
        V1
        LEFT JOIN
        (
        SELECT SER_ITMID, SUM(SER_qTY) STOCKQTY
        FROM SER_TBL
        WHERE SER_ID IN ($pSER)
        GROUP BY SER_ITMID
        ) V2 ON V1.ITH_ITMCD=V2.SER_ITMID
        ORDER BY ITH_ITMCD,PART_CODE ASC";        
		$query = $this->db->query($qry);
        return $query->result_array();
    }

    public function select_resume_fg_dedicated_per_ser($pSER){
        $qry = "SELECT V1.*,STOCKQTY,ID_TRANS FROM
        (SELECT ITH_ITMCD,UPPER(RTRIM(SERD2_ITMCD)) PART_CODE,round(SUM(RMQT),0) QTY
                ,RTRIM(RM.MITM_ITMD1) RMDESC
                ,RTRIM(VDETAIL.MITM_STKUOM) FGUOM
                ,RTRIM(RM.MITM_STKUOM) RMUOM
                ,RTRIM(VDETAIL.MITM_ITMD1) FGDESC
                ,ITH_SER
                FROM
                (select '' ITH_WH,SER_ITMID ITH_ITMCD,MITM_ITMD1,MITM_SPTNO,MITM_STKUOM,SER_ID ITH_SER,SER_DOC,SER_LUPDT ITH_LUPDT
                        from SER_TBL a 
                        INNER join MITM_TBL b on a.SER_ITMID=b.MITM_ITMCD
                        WHERE SER_ID IN ($pSER)
                        ) VDETAIL
                LEFT JOIN (SELECT SERD2_SER,ISNULL(MITMGRP_ITMCD,SERD2_ITMCD) SERD2_ITMCD,SUM(SERD2_QTY) RMQT FROM SERD2_TBL 
                    LEFT JOIN MITMGRP_TBL ON SERD2_ITMCD=MITMGRP_ITMCD_GRD GROUP BY SERD2_SER,SERD2_ITMCD,MITMGRP_ITMCD) VSERD2 ON ITH_SER=SERD2_SER
                LEFT JOIN MITM_TBL RM ON SERD2_ITMCD=MITM_ITMCD
                GROUP BY ITH_ITMCD,SERD2_ITMCD,ITH_SER, RM.MITM_ITMD1,VDETAIL.MITM_ITMD1, VDETAIL.MITM_STKUOM,RM.MITM_STKUOM)
        V1
        LEFT JOIN
        (
        SELECT SER_ITMID, SUM(SER_qTY) STOCKQTY
        FROM SER_TBL
        WHERE SER_ID IN ($pSER)
        GROUP BY SER_ITMID
        ) V2 ON V1.ITH_ITMCD=V2.SER_ITMID
		LEFT JOIN (
		SELECT REF_NO,ID_TRANS FROM ZRPSCRAP_HIST WHERE REF_NO IN ($pSER) AND IS_CONFIRMED IS NOT NULL
		) V3 ON ITH_SER=REF_NO
        ORDER BY ITH_ITMCD,PART_CODE ASC";        
		$query = $this->db->query($qry);
        return $query->result_array();
    }

    public function selectDraft($remark){
        $qry = "SELECT * from DISPOSEDRAFT WHERE REMARK=?";
        $query = $this->db->query($qry, [$remark]);
        return $query->result_array();
    }
}