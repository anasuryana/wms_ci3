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
        $qry = "SELECT ITEM PART_CODE,sum(TQTY) QTY FROM 
        (select ISNULL(MITMGRP_ITMCD,PART_CODE) ITEM,SUM(QTY) TQTY from DISPOSEDRAFT 
        LEFT JOIN MITMGRP_TBL ON PART_CODE=MITMGRP_ITMCD_GRD        
        GROUP BY MITMGRP_ITMCD,PART_CODE) VITEM
        WHERE ITEM IN (
            '128385000',
            '128430000',
            '218773700',
            '4220029800',
            '8069156400',
            'E06166',
            'E09573',
            'E12394',
            'M81417-05',
            'M81422-06',
            'M81511-01',
            'M89024-02'         
        )
        GROUP BY ITEM
        ORDER BY ITEM";        
		$query = $this->db->query($qry);
        return $query->result_array();
    }
    
    public function select_resume_fg($pDate){
        $qry = "SELECT ITH_ITMCD,RTRIM(SERD2_ITMCD) PART_CODE,round(SUM(RMQT),0) QTY FROM
        (select ITH_WH,ITH_ITMCD,MITM_ITMD1,MITM_SPTNO,SUM(ITH_QTY) STOCKQTY,MITM_STKUOM,ITH_SER,SER_DOC,MAX(ITH_LUPDT) ITH_LUPDT
                from v_ith_tblc a 
                INNER join MITM_TBL b on a.ITH_ITMCD=b.MITM_ITMCD
                LEFT JOIN SER_TBL ON ITH_SER=SER_ID
                WHERE ITH_WH='AFWH9SC' AND ITH_FORM NOT IN ('SASTART','SA') and ITH_DATEC<=?
                GROUP BY ITH_ITMCD,ITH_WH,MITM_SPTNO,MITM_STKUOM,MITM_ITMD1,ITH_SER,SER_DOC
                HAVING SUM(ITH_QTY)>0) VDETAIL
        LEFT JOIN (SELECT SERD2_SER,SERD2_ITMCD,SUM(SERD2_QTY) RMQT FROM SERD2_TBL GROUP BY SERD2_SER,SERD2_ITMCD) VSERD2 ON ITH_SER=SERD2_SER
        GROUP BY ITH_ITMCD,SERD2_ITMCD
        ORDER BY ITH_ITMCD,SERD2_ITMCD ASC";        
		$query = $this->db->query($qry, [$pDate]);
        return $query->result_array();
    }
}