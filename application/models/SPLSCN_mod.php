<?php

class SPLSCN_mod extends CI_Model {
	private $TABLENAME = "SPLSCN_TBL";
	public function __construct()
    {
        $this->load->database();
    }	

    public function insert($data)
    {                
        $this->db->insert($this->TABLENAME,$data);        
        return $this->db->affected_rows();
    }

    public function lastserialid(){       
        $qry = "select TOP 1 substring(SPLSCN_ID, 9, 20) lser from SPLSCN_TBL 
        WHERE convert(date, SPLSCN_LUPDT) = convert(date,GETDATE())
        ORDER BY convert(bigint,SUBSTRING(SPLSCN_ID,9,11)) desc";
        $query =  $this->db->query($qry);        
        if ($query->num_rows()>0){
            $ret = $query->row();
            return $ret->lser;
        } else {
            return '0';
        }
    }

    public function selectby_filter($pwhere){
	
        $this->db->from($this->TABLENAME);        
        $this->db->where($pwhere);
        $this->db->order_by('SPLSCN_FEDR,SPLSCN_LUPDT ASC');
		$query = $this->db->get();
		return $query->result_array();
    }
    public function selectby_ID_whereIn($id){
	
        $this->db->from($this->TABLENAME);        
        $this->db->where_in("SPLSCN_ID",$id);
        $this->db->order_by('SPLSCN_FEDR,SPLSCN_LUPDT ASC');
		$query = $this->db->get();
		return $query->result_array();
    }

    public function selectby_filter_like($pwhere){	
        $this->db->limit(2500);
        $this->db->from($this->TABLENAME);        
        $this->db->like($pwhere);
        $this->db->order_by('SPLSCN_DOC ASC, SPLSCN_CAT ASC, SPLSCN_LINE ASC, SPLSCN_FEDR ASC, SPLSCN_ORDERNO ASC , SPLSCN_ITMCD ASC');
		$query = $this->db->get();
		return $query->result_array();
    }
    public function select_ready_book($plike){	        
        $this->db->from("wms_v_ready_to_book_spl_base");        
        $this->db->like($plike);
		$query = $this->db->get();
		return $query->result_array();
    }
    public function select_ready_book_bywo($pjob){
        $this->db->from("wms_v_ready_to_book_spl_base");
        $this->db->join("(
            SELECT PPSN1_PSNNO FROM XPPSN1 WHERE PPSN1_WONO LIKE '%".$pjob."%'
            GROUP BY PPSN1_PSNNO
            ) VJOB","SPL_DOC=PPSN1_PSNNO");
		$query = $this->db->get();
		return $query->result_array();
    }
    public function selectby_filter_like_wjob($pwhere){	
        $this->db->limit(2500);
        $this->db->select($this->TABLENAME.".*, PPSN1_WONO");
        $this->db->from($this->TABLENAME);  
        $this->db->join("XPPSN1", "SPLSCN_DOC=PPSN1_PSNNO AND SPLSCN_LINE=PPSN1_LINENO AND SPLSCN_FEDR=PPSN1_FR");
        $this->db->like($pwhere);
        $this->db->order_by('SPLSCN_DOC ASC, SPLSCN_CAT ASC, SPLSCN_LINE ASC, SPLSCN_FEDR ASC, SPLSCN_ORDERNO ASC , SPLSCN_ITMCD ASC');
		$query = $this->db->get();
		return $query->result_array();
    }
    public function selectby_kitting_status($pbg){
        $this->db->from("VKITTINGSTATUS");
        $this->db->like("PPSN1_BSGRP", $pbg);
        $this->db->order_by('SPL_DOC ASC');
		$query = $this->db->get();
		return $query->result_array();
    }

    public function selectby_filter_formega_v1($pwhere){
        $this->db->select("concat('1',convert(varchar(30), SPLSCN_LUPDT,12),RIGHT(SPLSCN_ID,4) ) AS SPLSCN_ID,
        SPLSCN_ITMCD,SPLSCN_QTY,(convert(varchar(30), SPLSCN_LUPDT,21)) SPLSCN_LUPDT
        ,SPLSCN_LOTNO,SPLSCN_ORDERNO,SPLSCN_LINE,SPLSCN_FEDR,SPLSCN_USRID");
        $this->db->from($this->TABLENAME);        
        $this->db->where($pwhere);
        $this->db->order_by('SPLSCN_FEDR,SPLSCN_LUPDT ASC');
		$query = $this->db->get();
		return $query->result_array();
    
    }
    
    public function selectby_filter_for_serd($ppsn){
        $this->db->select("UPPER(RTRIM(SPLSCN_DOC)) SPLSCN_DOC
        ,RTRIM(SPLSCN_CAT) SPLSCN_CAT
        ,SPLSCN_LUPDT
        ,SPLSCN_LOTNO
        ,RTRIM(SPLSCN_ORDERNO) SPLSCN_ORDERNO
        ,UPPER(RTRIM(SPLSCN_LINE)) SPLSCN_LINE
        ,RTRIM(SPLSCN_FEDR) SPLSCN_FEDR
        ,RTRIM(UPPER(SPLSCN_ITMCD)) SPLSCN_ITMCD,SPLSCN_QTY");
        $this->db->from($this->TABLENAME);
        $this->db->where_in("SPLSCN_DOC", $ppsn);
        $this->db->order_by('SPLSCN_DOC,SPLSCN_LINE,SPLSCN_FEDR,SPLSCN_LUPDT ASC');
		$query = $this->db->get();
		return $query->result_array();
    }

    public function select_spl_vs_splscn_null($ppsn){

        $qry = "SELECT V1.*,SPLSCN_ITMCD FROM
        (SELECT SPL_DOC,SPL_ORDERNO,SPL_CAT,SPL_ITMCD,SUM(SPL_QTYREQ) REQQT 
            FROM SPL_TBL WHERE SPL_QTYREQ>0 and SPL_DOC IN ($ppsn) AND SPL_ITMCD NOT IN (SELECT MITM_ITMCD FROM  MITM_TBL WHERE MITM_ITMD1 LIKE '%SHIELD PLATE%')
			and SPL_CAT!='SP'
        GROUP BY SPL_DOC,SPL_ORDERNO,SPL_CAT,SPL_ITMCD
        ) V1
        LEFT JOIN 
        (
        SELECT SPLSCN_DOC,SPLSCN_LINE,SPLSCN_ORDERNO,SPLSCN_CAT,SPLSCN_ITMCD,SUM(SPLSCN_QTY) SUPQT FROM SPLSCN_TBL
        WHERE SPLSCN_DOC IN ($ppsn)
        GROUP BY SPLSCN_DOC,SPLSCN_LINE,SPLSCN_FEDR,SPLSCN_ORDERNO,SPLSCN_CAT,SPLSCN_ITMCD
        ) V2 ON SPL_DOC=SPLSCN_DOC  AND SPL_CAT=SPLSCN_CAT AND SPL_ITMCD=SPLSCN_ITMCD
        WHERE SPLSCN_ITMCD IS  NULL
        ORDER BY SPL_ORDERNO";
        $query = $this->db->query($qry);
        return $query->result_array();
    }
    public function select_kittingstatus_byjob($pjob){
        $qry = "sp_kittingstatus_byjob ?";
        $query = $this->db->query($qry, [$pjob]);
        return $query->result_array();
    }
    public function select_supplied_vs_confirmed($doc) {
        $qry = "wms_sp_check_doc ?";
        $query = $this->db->query($qry, [$doc]);
        return $query->result_array();
    }
    public function select_supplied_vs_confirmed_progress($doc) {
        $qry = "wms_sp_check_doc_progress ?";
        $query = $this->db->query($qry, [$doc]);
        return $query->result_array();
    }
    public function select_spl_vs_splscn_null_old($ppsn){
        $qry = "select PPSN2_PSNNO SPL_DOC, PPSN2_LINENO SPL_LINE,PPSN2_FR SPL_FEDR,PPSN2_MCZ SPL_ORDERNO,PPSN2_ITMCAT SPL_CAT
        ,PPSN2_SUBPN SPL_ITMCD,PPSN2_REQQT REQQT from XPPSN2 where PPSN2_PSNNO in ($ppsn) 
        and PPSN2_REQQT>0 and PPSN2_ACTQT=0 ORDER BY PPSN2_MCZ";
        $query = $this->db->query($qry);
        return $query->result_array();
    }
    public function select_spl_vs_splscn_non_fg_as_bom_null($ppsn){
        $qry = "SELECT V1.*,SPLSCN_ITMCD FROM
        (SELECT SPL_DOC,SPL_LINE,SPL_FEDR,SPL_ORDERNO,SPL_CAT,SPL_ITMCD,SUM(SPL_QTYREQ) REQQT 
            FROM SPL_TBL WHERE SPL_DOC IN ($ppsn) AND SPL_ITMCD NOT IN (SELECT MITM_ITMCD FROM  MITM_TBL WHERE MITM_ITMD1 LIKE '%SHIELD PLATE%')
        GROUP BY SPL_DOC,SPL_LINE,SPL_FEDR,SPL_ORDERNO,SPL_CAT,SPL_ITMCD
        ) V1
        LEFT JOIN 
        (
        SELECT SPLSCN_DOC,SPLSCN_LINE,SPLSCN_FEDR,SPLSCN_ORDERNO,SPLSCN_CAT,SPLSCN_ITMCD,SUM(SPLSCN_QTY) SUPQT FROM SPLSCN_TBL
        WHERE SPLSCN_DOC IN ($ppsn)
        GROUP BY SPLSCN_DOC,SPLSCN_LINE,SPLSCN_FEDR,SPLSCN_ORDERNO,SPLSCN_CAT,SPLSCN_ITMCD
        ) V2 ON SPL_DOC=SPLSCN_DOC AND SPL_LINE=SPLSCN_LINE AND SPL_FEDR=SPLSCN_FEDR AND SPL_CAT=SPLSCN_CAT AND SPL_ITMCD=SPLSCN_ITMCD
        LEFT JOIN VFG_AS_BOM ON SPL_ITMCD=PWOP_BOMPN
        WHERE SPLSCN_ITMCD IS NULL AND PWOP_BOMPN IS NULL
        ORDER BY SPL_ORDERNO";
        $query = $this->db->query($qry);
        return $query->result_array();
    }

    public function selectby_filter_formega($pwhere){
		$qry = "SELECT concat('1',convert(varchar(30), SPLSCN_LUPDT,12),RIGHT(SPLSCN_ID,4) ) AS SPLSCN_ID,
			SPLSCN_ITMCD,SPLSCN_QTY,(convert(varchar(30), SPLSCN_LUPDT,21)) SPLSCN_LUPDT
			,SPLSCN_LOTNO,SPLSCN_ORDERNO,SPLSCN_LINE,SPLSCN_FEDR,SPLSCN_USRID,PPSN2_DATANO FROM SPLSCN_TBL a
			inner join XPPSN2 ON SPLSCN_DOC=PPSN2_PSNNO AND SPLSCN_CAT=PPSN2_ITMCAT AND SPLSCN_LINE=PPSN2_LINENO AND SPLSCN_FEDR=PPSN2_FR AND
			SPLSCN_ORDERNO=PPSN2_MCZ AND SPLSCN_ITMCD=PPSN2_SUBPN
            WHERE SPLSCN_DOC=? and SPLSCN_EXPORTED is null and SPLSCN_SAVED='1' and SPLSCN_LINE=? and SPLSCN_CAT=? 
            order by SPLSCN_LUPDT ASC";
		$query = $this->db->query($qry, array($pwhere['SPLSCN_DOC'], $pwhere['SPLSCN_LINE'], $pwhere['SPLSCN_CAT'] ));
		return $query->result_array();		     
    }

    public function deleteby_filter($pwhere)
    {          
        $this->db->where($pwhere);
        $this->db->delete($this->TABLENAME);
        return $this->db->affected_rows();
    }

    public function selecttocompareith($pdoc){       
        $qry = "SELECT TH_DOC,SPL_ITMCD,TTLREQQTY,convert(bigint,COALESCE(TTLSCNQTY,0)) TTLSCNQTY, ABS(convert(bigint,COALESCE(TTLSAVEQTY,0))) TTLSAVEQTY FROM
        (select TH_DOC,SPL_ITMCD,SUM(SPL_QTYREQ) TTLREQQTY from v_spl a 
        group by TH_DOC,SPL_ITMCD) v1
        LEFT JOIN (
        select THSCN_DOC,SPLSCN_ITMCD,SUM(SPLSCN_QTY) TTLSCNQTY FROM
        v_splscn GROUP BY THSCN_DOC,SPLSCN_ITMCD
        ) v2 on v1.TH_DOC=v2.THSCN_DOC AND v1.SPL_ITMCD=v2.SPLSCN_ITMCD
        LEFT JOIN (
        SELECT ITH_DOC,ITH_ITMCD,SUM(ITH_QTY) TTLSAVEQTY FROM ITH_TBL WHERE ITH_FORM='OUT-WH-RM'
        GROUP BY ITH_DOC,ITH_ITMCD
        ) v3 on v1.TH_DOC=v3.ITH_DOC and v1.SPL_ITMCD=v3.ITH_ITMCD
        where TH_DOC='$pdoc' AND coalesce(TTLSCNQTY,0)>0";
		$query = $this->db->query($qry);
		return $query->result_array();
    }

    public function updatebyId($pdata, $pdoc, $pitem)
    {
        $this->db->where('THSCN_DOC',$pdoc)->where('SPLSCN_ITMCD', $pitem);
        $this->db->update('v_splscn', $pdata);
        return $this->db->affected_rows();
    }
    public function update_setsaved($pid)
    {   
        $this->db->set("SPLSCN_SAVED", "1");
        $this->db->where('SPLSCN_ID',$pid);
        $this->db->update($this->TABLENAME);
        return $this->db->affected_rows();
    }

    public function update_exported($pdoc, $pcat, $pline){
        $qry = "UPDATE SPLSCN_TBL SET SPLSCN_EXPORTED = GETDATE() WHERE 
        SPLSCN_DOC=? AND SPLSCN_CAT=? AND SPLSCN_LINE = ?  AND SPLSCN_EXPORTED IS NULL AND SPLSCN_SAVED='1'";
		$this->db->query($qry , array($pdoc, $pcat, $pline) );
        return $this->db->affected_rows();
    }

    public function check_Primary($data)
    {
        return $this->db->get_where($this->TABLENAME,$data)->num_rows();
    }
    
    public function selectunsaved($psnno,$cat, $line, $fr){
        $qry = "select * FROM $this->TABLENAME where SPLSCN_DOC = ? and SPLSCN_CAT = ? and SPLSCN_LINE = ? and SPLSCN_FEDR = ? and (coalesce(SPLSCN_SAVED,'') = '' or  SPLSCN_SAVED=0)";
		$query = $this->db->query($qry, array($psnno, $cat, $line, $fr));
		return $query->result_array();
    }
    public function selectsaved($psnno,$cat, $line, $fr){
        $qry = "select SPLSCN_ORDERNO, UPPER(SPLSCN_ITMCD) SPLSCN_ITMCD,sum(SPLSCN_QTY) TTLSAVED FROM $this->TABLENAME 
        where SPLSCN_DOC = ? and SPLSCN_CAT = ? and SPLSCN_LINE = ? and SPLSCN_FEDR = ? and SPLSCN_SAVED='1'
        GROUP BY SPLSCN_ORDERNO, SPLSCN_ITMCD";
		$query = $this->db->query($qry, array($psnno, $cat, $line, $fr));
		return $query->result_array();
    }
    public function selectsaved_nofr($psnno,$cat, $line){
        $qry = "select SPLSCN_ORDERNO, SPLSCN_ITMCD,sum(SPLSCN_QTY) TTLSAVED FROM $this->TABLENAME 
        where SPLSCN_DOC = ? and SPLSCN_CAT = ? and SPLSCN_LINE = ? and  SPLSCN_SAVED='1'
        GROUP BY SPLSCN_ORDERNO, SPLSCN_ITMCD";
        $query = $this->db->query($qry, array($psnno, $cat, $line));
        return $query->result_array();
    }

    public function selectunexported(){
        $qry = "select TOP 45 SPLSCN_DOC,MAX(SPLSCN_LUPDT) MAXLUPDT from SPLSCN_TBL
        group by SPLSCN_DOC
        ORDER BY 2 DESC";
        $query = $this->db->query($qry);
		return $query->result_array();
    }

    public function select_scanned_bypsn($ppsn){        
        $qry = "select UPPER(SPLSCN_DOC) SPLSCN_DOC,SPLSCN_CAT,SPLSCN_LINE,SPLSCN_FEDR,SPLSCN_ITMCD,SPLSCN_LOTNO,SPLSCN_ORDERNO,(SCNQTY-coalesce(SUPQTY,0)) SCNQTY, SCNTIME from
        (
        SELECT SPLSCN_DOC,SPLSCN_CAT,SPLSCN_LINE,SPLSCN_FEDR,SPLSCN_ITMCD,SPLSCN_LOTNO,SPLSCN_ORDERNO,SUM(SPLSCN_QTY) SCNQTY, MIN(SPLSCN_LUPDT) SCNTIME FROM SPLSCN_TBL
        where SPLSCN_DOC in ($ppsn) 
        GROUP BY SPLSCN_DOC,SPLSCN_CAT,SPLSCN_LINE,SPLSCN_FEDR,SPLSCN_ITMCD,SPLSCN_LOTNO,SPLSCN_ORDERNO
        ) v1 left join
        (
        SELECT SERD_PSNNO,SERD_LINENO,SERD_CAT,SERD_FR,SERD_ITMCD,SERD_LOTNO,SERD_MCZ, SUM(SERD_QTY) SUPQTY FROM SERD_TBL 
        where SERD_PSNNO in ($ppsn) 
        GROUP BY SERD_PSNNO,SERD_LINENO,SERD_CAT,SERD_FR,SERD_ITMCD,SERD_LOTNO,SERD_MCZ) v2 on 
        SPLSCN_DOC=SERD_PSNNO and SPLSCN_CAT=SERD_CAT and SPLSCN_LINE=SERD_LINENO and SPLSCN_FEDR=SERD_FR and SPLSCN_ITMCD=SERD_ITMCD  and SPLSCN_LOTNO = SERD_LOTNO AND SPLSCN_ORDERNO=SERD_MCZ                
        where (SCNQTY-coalesce(SUPQTY,0))>0
		ORDER BY SPLSCN_DOC,SPLSCN_CAT,SPLSCN_LINE,SPLSCN_FEDR,SPLSCN_ITMCD,SPLSCN_ORDERNO,SCNTIME
        ";
        $query = $this->db->query($qry);
		return $query->result_array();
    }

    public function select_scannedwelcat_bypsn($ppsn){     
        #PPSN2_ACTQT => PPSN2_REQQT   
        $qry = "select UPPER(SPLSCN_DOC) SPLSCN_DOC,SPLSCN_CAT,SPLSCN_LINE,SPLSCN_FEDR,SPLSCN_ITMCD,(SPLSCN_QTY-coalesce(SUPQTY,0)) SPLSCN_QTY
        ,SPLSCN_ORDERNO,PPSN2_MC,PPSN2_PROCD,PPSN2_MSFLG, '' SPLSCN_LOTNO, NULL SCNTIME from
        (select RTRIM(PPSN2_PSNNO) SPLSCN_DOC,RTRIM(PPSN2_ITMCAT) SPLSCN_CAT,RTRIM(PPSN2_LINENO) SPLSCN_LINE
        ,RTRIM(PPSN2_FR) SPLSCN_FEDR,RTRIM(PPSN2_SUBPN) SPLSCN_ITMCD,isnull(PPSN2_REQQT,0) SPLSCN_QTY,isnull(PPSN2_RTNQT,0) RETQTY  
		,RTRIM(PPSN2_MCZ) SPLSCN_ORDERNO, RTRIM(PPSN2_MC) PPSN2_MC,RTRIM(PPSN2_PROCD) PPSN2_PROCD, RTRIM(PPSN2_MSFLG) PPSN2_MSFLG from XPPSN2 where PPSN2_PSNNO in ($ppsn)
		)
		v1 left join
        ( SELECT SERD_PSNNO,SERD_LINENO,SERD_CAT,SERD_FR,SERD_ITMCD, SUM(SERD_QTY) SUPQTY,SERD_MC,SERD_MCZ,SERD_PROCD FROM SERD_TBL 
        where SERD_PSNNO in ($ppsn) 
        GROUP BY SERD_PSNNO,SERD_LINENO,SERD_CAT,SERD_FR,SERD_ITMCD,SERD_MCZ,SERD_MC,SERD_PROCD		
		) v2 on 
        SPLSCN_DOC=SERD_PSNNO and SPLSCN_CAT=SERD_CAT and SPLSCN_LINE=SERD_LINENO and SPLSCN_FEDR=SERD_FR and SPLSCN_ITMCD=SERD_ITMCD AND SPLSCN_ORDERNO=SERD_MCZ AND PPSN2_MC=SERD_MC AND PPSN2_PROCD=SERD_PROCD	
        where (SPLSCN_QTY-coalesce(SUPQTY,0))>0
		ORDER BY SPLSCN_ORDERNO";
        $query = $this->db->query($qry);
		return $query->result_array();
    }
    public function select_scannedwelcat_bypsn_sa($ppsn){        
        $qry = "select UPPER(SPLSCN_DOC) SPLSCN_DOC,SPLSCN_CAT,SPLSCN_LINE,SPLSCN_FEDR,SPLSCN_ITMCD,(SPLSCN_QTY-coalesce(SUPQTY,0)) SPLSCN_QTY
        ,SPLSCN_ORDERNO,PPSN2_MC,PPSN2_PROCD,PPSN2_MSFLG, '' SPLSCN_LOTNO, NULL SCNTIME from
        (select RTRIM(PPSN2_PSNNO) SPLSCN_DOC,RTRIM(PPSN2_ITMCAT) SPLSCN_CAT,RTRIM(PPSN2_LINENO) SPLSCN_LINE
        ,RTRIM(PPSN2_FR) SPLSCN_FEDR,RTRIM(PPSN2_SUBPN) SPLSCN_ITMCD,isnull(PPSN2_REQQT,0) SPLSCN_QTY,isnull(PPSN2_RTNQT,0) RETQTY  
		,RTRIM(PPSN2_MCZ) SPLSCN_ORDERNO, RTRIM(PPSN2_MC) PPSN2_MC,RTRIM(PPSN2_PROCD) PPSN2_PROCD, RTRIM(PPSN2_MSFLG) PPSN2_MSFLG from XPPSN2 where PPSN2_PSNNO in ($ppsn)
		)
		v1 left join
        ( SELECT SERD_PSNNO,SERD_LINENO,SERD_CAT,SERD_FR,SERD_ITMCD, SUM(SERD_QTY) SUPQTY,SERD_MC,SERD_MCZ,SERD_PROCD FROM SERD_TBL 
        where SERD_PSNNO in ($ppsn) 
        GROUP BY SERD_PSNNO,SERD_LINENO,SERD_CAT,SERD_FR,SERD_ITMCD,SERD_MCZ,SERD_MC,SERD_PROCD		
		) v2 on 
        SPLSCN_DOC=SERD_PSNNO and SPLSCN_CAT=SERD_CAT and SPLSCN_LINE=SERD_LINENO and SPLSCN_FEDR=SERD_FR and SPLSCN_ITMCD=SERD_ITMCD AND SPLSCN_ORDERNO=SERD_MCZ AND PPSN2_MC=SERD_MC AND PPSN2_PROCD=SERD_PROCD	
        where (SPLSCN_QTY-coalesce(SUPQTY,0))>0
		ORDER BY SPLSCN_ORDERNO";
        $query = $this->db->query($qry);
		return $query->result_array();
    }
    public function select_scannedwelcat_bypsn_peritem($ppsn){       
        $qry = "select PPSN2_PSNNO SPLSCN_DOC,PPSN2_SUBPN SPLSCN_ITMCD,isnull(sum(PPSN2_ACTQT),0) SPLSCN_QTY,isnull(sum(PPSN2_RTNQT),0) RETQTY  
        from XPPSN2 where PPSN2_PSNNO in ($ppsn) GROUP BY PPSN2_PSNNO, PPSN2_SUBPN
        ORDER BY PPSN2_SUBPN";
        $query = $this->db->query($qry);
		return $query->result_array();
    }
	
	public function select_for_return($psn, $cat, $line,$fr, $item){
		$qry = "SELECT SPLSCN_DOC, SPLSCN_CAT, SPLSCN_LINE, SPLSCN_FEDR,SPLSCN_ORDERNO, SPLSCN_ITMCD,SPLSCN_QTY,SPLSCN_LOTNO FROM SPLSCN_TBL
            WHERE SPLSCN_DOC=? AND SPLSCN_CAT=? AND SPLSCN_LINE = ? AND SPLSCN_FEDR = ? AND SPLSCN_ITMCD=?
            GROUP BY SPLSCN_DOC, SPLSCN_CAT, SPLSCN_LINE, SPLSCN_FEDR, SPLSCN_ITMCD,SPLSCN_LOTNO,SPLSCN_QTY,SPLSCN_ORDERNO";
		$query = $this->db->query($qry, [$psn, $cat, $line, $fr, $item]);
		return $query->result_array();
    }
    
    public function select_discrepancy_scanned_vs_newsynchronized($psn){
        $qry = "SELECT PPSN2_PSNNO,SPLSCN_ORDERNO,SPLSCN_ITMCD,TTLSCN,SPLSCN_CAT,SPLSCN_LINE,SPLSCN_FEDR from  XPPSN2 a 
            RIGHT JOIN (SELECT SPLSCN_DOC,SPLSCN_CAT,SPLSCN_FEDR,SPLSCN_LINE,SPLSCN_ORDERNO,SPLSCN_ITMCD,SUM(SPLSCN_QTY) TTLSCN FROM SPLSCN_TBL
            GROUP BY SPLSCN_DOC,SPLSCN_CAT,SPLSCN_FEDR,SPLSCN_LINE,SPLSCN_ORDERNO,SPLSCN_ITMCD) V1 
            ON PPSN2_PSNNO=SPLSCN_DOC 
            AND PPSN2_ITMCAT=SPLSCN_CAT AND PPSN2_FR=SPLSCN_FEDR 
            AND PPSN2_LINENO=SPLSCN_LINE 
            AND PPSN2_SUBPN=SPLSCN_ITMCD
            AND PPSN2_MCZ = SPLSCN_ORDERNO
            WHERE SPLSCN_DOC=?
            AND PPSN2_MCZ IS NULL";
        $query = $this->db->query($qry, [$psn]);
        return $query->result_array();
    }

    public function selectby_partreq_status($pbg){
        $this->db->from("VPARTREQSTATUS");
        $this->db->join("(select SPL_DOC DOC, MAX(RQSRMRK_DESC) RQSRMRK_DESC,MAX(SPL_APPRV_TM) SPL_APPRV_TM,MAX(MSTEMP_FNM) MSTEMP_FNM from SPL_TBL 
        inner join RQSRMRK_TBL on SPL_RMRK=RQSRMRK_CD
        left join MSTEMP_TBL ON SPL_APPRV_BY=MSTEMP_ID
        GROUP BY SPL_DOC) vrmrk", "SPL_DOC=DOC", "LEFT");
        $this->db->like("SPL_BG", $pbg);
        $this->db->select("VPARTREQSTATUS.*,RQSRMRK_DESC,SPL_APPRV_TM, MSTEMP_FNM");
        $this->db->order_by('SPL_DOC ASC');
		$query = $this->db->get();
		return $query->result_array();
    }

    public function select_scanned_partreq_by_reffdoc($pdoc){
        $qry = "SELECT VSCAN.*,RTRIM(MITM_ITMD1) MITM_ITMD1 FROM
        (SELECT SPLSCN_ITMCD,SPLSCN_LOTNO,SUM(SPLSCN_QTY) SCNQTY FROM SPLSCN_TBL 
        WHERE SPLSCN_DOC IN (SELECT SPL_DOC FROM SPL_TBL WHERE SPL_REFDOCNO=? GROUP BY SPL_DOC)
        GROUP BY SPLSCN_ITMCD,SPLSCN_LOTNO) VSCAN
        LEFT JOIN MITM_TBL ON SPLSCN_ITMCD=MITM_ITMCD
        ORDER BY SPLSCN_ITMCD";
        $query = $this->db->query($qry, [$pdoc]);
        return $query->result_array();
    }

    function select_unfully_canceled($doc){
        $qry = "select SPLSCN_TBL.* from SPLSCN_TBL left join 
        SPL_TBL on SPLSCN_DOC=SPL_DOC and SPLSCN_ITMCD=SPL_ITMCD and SPLSCN_ORDERNO=SPL_ORDERNO
        WHERE SPL_DOC is null and SPLSCN_DOC=?";
        $query = $this->db->query($qry, [$doc]);
        return $query->result_array();
    }

    function select_logical_return_byPSN($pPSN){
        $qry = "SELECT RTRIM(PPSN2_PSNNO) PSNNO,RTRIM(PPSN2_SUBPN) ITMCD,sum(PPSN2_ACTQT)-sum(PPSN2_REQQT) LOGRET
        FROM XPPSN2 WHERE PPSN2_PSNNO in ($pPSN)
        AND  (PPSN2_ACTQT-PPSN2_REQQT)>0
        group by PPSN2_PSNNO,PPSN2_SUBPN ORDER BY 1 DESC";
        $query = $this->db->query($qry);
        return $query->result_array();
    }
}