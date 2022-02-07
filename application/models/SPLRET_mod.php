<?php

class SPLRET_mod extends CI_Model {
	private $TABLENAME = "RETSCN_TBL";
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
        $qry = "select TOP 1 substring(RETSCN_ID, 9, 20) lser from RETSCN_TBL 
        WHERE convert(date, RETSCN_LUPDT) = convert(date,GETDATE())
        ORDER BY convert(bigint,SUBSTRING(RETSCN_ID,9,11)) desc";
        $query =  $this->db->query($qry);        
        if ($query->num_rows()>0){
            $ret = $query->row();
            return $ret->lser;
        } else {
            return '0';
        }
    }    

    public function select_psn($pyear,$pmonth){
        $this->db->select("RETSCN_SPLDOC");
        $this->db->from($this->TABLENAME);
        $this->db->where("YEAR(RETSCN_LUPDT)",$pyear)->where("MONTH(RETSCN_LUPDT)", $pmonth);
        $this->db->group_by('RETSCN_SPLDOC');
        $this->db->order_by('RETSCN_SPLDOC asc');
        $query = $this->db->get();
        return $query->result_array();
    }   

    public function selectby_filter($pwhere){
        $this->db->select("a.*,b.*, RTRIM(MITM_SPTNO) MITM_SPTNO, RTRIM(ISNULL(RETSCN_HOLD,'0'))  FLG_HOLD");
        $this->db->from($this->TABLENAME." a");
        $this->db->join("MMADE_TBL b", "a.RETSCN_CNTRYID=b.MMADE_CD");
        $this->db->join("MITM_TBL", "RETSCN_ITMCD=MITM_ITMCD");
        $this->db->where($pwhere);
        $this->db->order_by('RETSCN_LUPDT DESC');
		$query = $this->db->get();
		return $query->result_array();
    }

    public function selectfor_analyze($pwhere){
        $this->db->from($this->TABLENAME." a");
        $this->db->join("MMADE_TBL b", "a.RETSCN_CNTRYID=b.MMADE_CD");
        $this->db->where($pwhere);
        $this->db->order_by('RETSCN_ORDERNO ASC');
		$query = $this->db->get();
		return $query->result_array();
    }


    public function selectspl_sup_ret($pdoc , $pcat, $pline, $pfedr){
        $qry = "exec sp_splvssupvsret ?, ?, ?, ?";
		$query = $this->db->query($qry, [$pdoc, $pcat,$pline,$pfedr ]);
		return $query->result();
    }

    public function selectspl_sup_ret_psnonly($pdoc){
        $qry = "exec sp_splvssupvsret_psnonly ?";
        $query = $this->db->query($qry, array($pdoc));
        return $query->result();
    }


    public function selecttocompareith($pdoc){
        $qry = "SELECT THSCN_DOC,RETSCN_ITMCD,convert(bigint,COALESCE(TTLSCNQTY,0)) TTLSCNQTY, ABS(convert(bigint,COALESCE(TTLSAVEQTY,0))) TTLSAVEQTY FROM
        (
        select THSCN_DOC,RETSCN_ITMCD,SUM(RETSCN_QTYAFT) TTLSCNQTY FROM
        v_splret GROUP BY THSCN_DOC,RETSCN_ITMCD
        ) v1
        LEFT JOIN (
        SELECT ITH_DOC,ITH_ITMCD,SUM(ITH_QTY) TTLSAVEQTY FROM ITH_TBL WHERE ITH_FORM='OUT-PRD-RM'
        GROUP BY ITH_DOC,ITH_ITMCD
        ) v3 on v1.THSCN_DOC=v3.ITH_DOC and v1.RETSCN_ITMCD=v3.ITH_ITMCD
        where THSCN_DOC='$pdoc' AND coalesce(TTLSCNQTY,0)>0";
		$query = $this->db->query($qry);
		return $query->result_array();
    }

    public function selectbyid_in($pwhere){
        $qry = "SELECT a.*,MITM_SPTNO,MMADE_NM,MSTEMP_FNM FROM RETSCN_TBL a INNER JOIN MITM_TBL b on a.RETSCN_ITMCD=b.MITM_ITMCD 
        INNER JOIN MMADE_TBL c on a.RETSCN_CNTRYID=c.MMADE_CD INNER JOIN MSTEMP_TBL d on a.RETSCN_USRID=d.MSTEMP_ID
        WHERE RETSCN_ID in ($pwhere)";
		$query = $this->db->query($qry);
		return $query->result();
    }

    public function selectformega($pwhere){
        $this->db->select("RETSCN_ITMCD,CONVERT(bigint,SUM(RETSCN_QTYAFT)) RETQTY");
        $this->db->from($this->TABLENAME);  
        $this->db->where($pwhere);
        $this->db->group_by("RETSCN_ITMCD");
        $this->db->order_by('RETSCN_ITMCD ASC');
		$query = $this->db->get();
		return $query->result_array();
    }

    public function select_partreq_formega($ppsn){
        $qry = "SELECT VRET.*,SPL_REFDOCNO FROM
        (select RETSCN_DATE,RETSCN_SPLDOC,RETSCN_ITMCD,SUM(RETSCN_QTYAFT) RETQTY from V_RETSCN_TBLC 
        WHERE SUBSTRING(RETSCN_SPLDOC,1,3)='PR-' AND ISNULL(RETSCN_HOLD,'0')='0' AND RETSCN_SPLDOC=?
        GROUP BY RETSCN_DATE,RETSCN_SPLDOC,RETSCN_ITMCD) VRET
        LEFT JOIN
        (
        SELECT SPL_DOC,SPL_ITMCD,MAX(UPPER(SPL_REFDOCNO)) SPL_REFDOCNO FROM SPL_TBL WHERE SPL_DOC=?
        GROUP BY SPL_DOC,SPL_ITMCD) VSPL ON RETSCN_SPLDOC=SPL_DOC AND RETSCN_ITMCD=SPL_ITMCD";
		$query =  $this->db->query($qry, [$ppsn, $ppsn]);
		return $query->result_array();
    }
    public function selectsp_vs_ret($doc, $cat, $line, $fedr, $item){
        $qry = "EXEC sp_splvsret '$doc', '$cat', '$line', '$fedr', '$item'";
		$query =  $this->db->query($qry);
		return $query->result_array();
    }

    public function selectsp_vs_ret_nofr($doc, $cat, $line,  $item){
        $qry = "EXEC sp_splvsret_nofr ?, ?, ?,  ?";
        $query =  $this->db->query($qry , [$doc, $cat, $line, $item]);
        return $query->result_array();
    }
    public function select_balance_peritem($doc, $line, $cat,  $item){
        $qry = "EXEC sp_getreturnbalance_peritem ?, ?, ?,  ?";
        $query =  $this->db->query($qry , [$doc, $line, $cat, $item]);
        return $query->result_array();
    }
    public function select_wh_doc($doc){
        $qry = "select ITH_WH from ITH_TBL where ITH_DOC like ? and ITH_FORM like '%RET%'  group by ITH_WH";
        $query =  $this->db->query($qry , ["%".$doc."%"]);
        return $query->result_array();
    }

    public function updatebyVars($pdata, $pwhere)
    {        
        $this->db->where($pwhere);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
	}

    public function update_as_holdrelease($pkey, $pstatus)
    {        
        $qry =  "UPDATE RETSCN_TBL SET RETSCN_HOLD=? WHERE (RETSCN_ID=? AND RETSCN_SAVED IS NULL)  ";
        $this->db->query($qry, [$pstatus,$pkey]);
        return $this->db->affected_rows();        
    }
    public function updatebyId($pdata, $pkey)
    {        
        $qry =  "UPDATE RETSCN_TBL SET RETSCN_QTYAFT=? , RETSCN_SAVED='1', RETSCN_LUPDT=getdate(),RETSCN_CNFRMDT=? WHERE (RETSCN_ID=? AND RETSCN_SAVED IS NULL AND ISNULL(RETSCN_HOLD,'0')!='1' )  ";//AND (RETSCN_SAVED IS NULL OR RETSCN_SAVED='0')
        $this->db->query($qry, [$pdata['RETSCN_QTYAFT'],$pdata['RETSCN_CNFRMDT'],$pkey ]);
        return $this->db->affected_rows();        
    }
    
    public function delete_unsaved($pwhere)
    {    
        $qry = "DELETE FROM RETSCN_TBL WHERE RETSCN_ID=? AND COALESCE(RETSCN_SAVED,'0') ='0'";
        $this->db->query($qry, [$pwhere] );
        return $this->db->affected_rows();
    }

    public function select_resume($date1, $date2){
        // $qry = "SELECT V1.*,UPPER(SPL_REFDOCNO) REFFDOC FROM (select RETSCN_DATE,RETSCN_SPLDOC,RETSCN_ITMCD,SUM(RETSCN_QTYAFT) RTNQTY  from V_RETSCN_TBLC 
        // WHERE RETSCN_SPLDOC like 'PR-%' AND RETSCN_DATE BETWEEN ? AND ?
        // GROUP BY RETSCN_DATE,RETSCN_SPLDOC,RETSCN_ITMCD ) V1
		// LEFT JOIN (
		// 	SELECT SPL_DOC,SPL_ITMCD,MAX(SPL_REFDOCNO) SPL_REFDOCNO FROM SPL_TBL 
		// 	WHERE SPL_DOC like 'PR-%' GROUP BY SPL_DOC,SPL_ITMCD		
		// ) V2 ON V1.RETSCN_SPLDOC=SPL_DOC AND RETSCN_ITMCD=SPL_ITMCD 
        // ORDER BY 1,2,3";
        $qry = "SELECT V1.*,UPPER(SPL_REFDOCNO) REFFDOC FROM (SELECT ITH_DATEC RETSCN_DATE,SUBSTRING(ITH_DOC,1,19) RETSCN_SPLDOC, ITH_ITMCD RETSCN_ITMCD,SUM(ITH_QTY) RTNQTY 
        FROM v_ith_tblc WHERE ITH_FORM='INC-RET' AND ITH_DOC LIKE '%PR-%' AND ITH_DATEC BETWEEN ? AND ?
        GROUP BY ITH_DATEC,SUBSTRING(ITH_DOC,1,19), ITH_ITMCD ) V1
                LEFT JOIN (
                    SELECT SPL_DOC,SPL_ITMCD,MAX(SPL_REFDOCNO) SPL_REFDOCNO FROM SPL_TBL 
                    WHERE SPL_DOC like 'PR-%' GROUP BY SPL_DOC,SPL_ITMCD		
                ) V2 ON V1.RETSCN_SPLDOC=SPL_DOC AND RETSCN_ITMCD=SPL_ITMCD 
                ORDER BY 1,2,3";
        $query =  $this->db->query($qry , [$date1, $date2]);
        return $query->result_array();
    }

    public function select_returnConfirmation($pdate1, $pdate2,$pbg) {        
        $qry = "SELECT VREQ.*,RETSCN_CNFRMDT,ISNULL(VRET.RETQTY,0) RETQTY,SUPQTY,RTRIM(MITM_SPTNO) MITM_SPTNO,VPPSN.MITM_ITMD1 
            FROM (SELECT SPL_DOC VREQPSN, RTRIM(SPL_LINE) SPL_LINE ,SPL_ITMCD,SUM(SPL_QTYREQ) REQQTY FROM SPL_TBL 
            WHERE SPL_DOC LIKE 'SP-%' AND SPL_BG IN ($pbg) AND SPL_DOC IN (SELECT RETSCN_SPLDOC FROM RETSCN_TBL WHERE RETSCN_CNFRMDT BETWEEN ? AND ?
            AND ISNULL(RETSCN_HOLD,'0')!='1' AND RETSCN_SPLDOC LIKE 'SP-%' GROUP BY RETSCN_SPLDOC)
            GROUP BY SPL_DOC,SPL_LINE,SPL_ITMCD) VREQ
            LEFT JOIN
            (select MAX(RETSCN_CNFRMDT) RETSCN_CNFRMDT,RETSCN_SPLDOC,RETSCN_LINE,RETSCN_ITMCD,SUM(RETSCN_QTYAFT) RETQTY from RETSCN_TBL 
            WHERE ISNULL(RETSCN_HOLD,'0')!='1' AND RETSCN_SPLDOC LIKE 'SP-%' and RETSCN_CNFRMDT BETWEEN ? AND ?
            GROUP BY RETSCN_ITMCD ,RETSCN_SPLDOC,RETSCN_LINE) VRET
            ON VRET.RETSCN_SPLDOC=VREQ.VREQPSN AND VRET.RETSCN_LINE=VREQ.SPL_LINE AND VRET.RETSCN_ITMCD=SPL_ITMCD        
            LEFT JOIN
            (SELECT SPLSCN_DOC VSUPPSN,RTRIM(SPLSCN_LINE) SPLSCN_LINE,SPLSCN_ITMCD,SUM(SPLSCN_QTY) SUPQTY FROM SPLSCN_TBL 
            WHERE SPLSCN_DOC LIKE 'SP-%'
            GROUP BY SPLSCN_DOC,SPLSCN_LINE,SPLSCN_ITMCD) VSUP
            ON VREQ.VREQPSN=VSUP.VSUPPSN AND VREQ.SPL_LINE=VSUP.SPLSCN_LINE AND VREQ.SPL_ITMCD=VSUP.SPLSCN_ITMCD        
            LEFT JOIN MITM_TBL ON SPL_ITMCD=MITM_ITMCD
            LEFT JOIN (
            SELECT PPSN1_PSNNO,MAX(PPSN1_MDLCD) PPSN1_MDLCD,RTRIM(MAX(MITM_ITMD1)) MITM_ITMD1 FROM XPPSN1 
            LEFT JOIN XMITM_V ON PPSN1_MDLCD=MITM_ITMCD
            WHERE ISNULL(PPSN1_MDLCD,'')!='' GROUP BY PPSN1_PSNNO
            ) VPPSN ON VREQ.VREQPSN=PPSN1_PSNNO
            WHERE REQQTY!=ISNULL(SUPQTY,0)
            ORDER BY RETSCN_CNFRMDT,SPL_ITMCD";
        $query =  $this->db->query($qry , [$pdate1, $pdate2,$pdate1, $pdate2]);
        return $query->result_array();
    }
    public function select_returnConfirmation_PR($pdate1, $pdate2,$pbg) {
        $qry = "SELECT VREQ.*,RETSCN_CNFRMDT,ISNULL(VRET.RETQTY,0) RETQTY,SUPQTY,RTRIM(MITM_SPTNO) MITM_SPTNO,VPPSN.MITM_ITMD1 
            FROM (SELECT SPL_DOC VREQPSN, RTRIM(SPL_LINE) SPL_LINE ,SPL_ITMCD,SUM(SPL_QTYREQ) REQQTY FROM SPL_TBL 
            WHERE SPL_DOC LIKE 'PR-%' AND SPL_BG IN ($pbg) AND SPL_DOC IN (SELECT RETSCN_SPLDOC FROM RETSCN_TBL WHERE RETSCN_CNFRMDT BETWEEN ? AND ?
            AND ISNULL(RETSCN_HOLD,'0')!='1' AND RETSCN_SPLDOC LIKE 'PR-%' GROUP BY RETSCN_SPLDOC)
            GROUP BY SPL_DOC,SPL_LINE,SPL_ITMCD) VREQ
            LEFT JOIN
            (select MAX(RETSCN_CNFRMDT) RETSCN_CNFRMDT,RETSCN_SPLDOC,RETSCN_LINE,RETSCN_ITMCD,SUM(RETSCN_QTYAFT) RETQTY from RETSCN_TBL 
            WHERE ISNULL(RETSCN_HOLD,'0')!='1' AND RETSCN_SPLDOC LIKE 'PR-%' and RETSCN_CNFRMDT BETWEEN ? AND ?
            GROUP BY RETSCN_ITMCD ,RETSCN_SPLDOC,RETSCN_LINE) VRET
            ON VRET.RETSCN_SPLDOC=VREQ.VREQPSN AND VRET.RETSCN_LINE=VREQ.SPL_LINE AND VRET.RETSCN_ITMCD=SPL_ITMCD        
            LEFT JOIN
            (SELECT SPLSCN_DOC VSUPPSN,RTRIM(SPLSCN_LINE) SPLSCN_LINE,SPLSCN_ITMCD,SUM(SPLSCN_QTY) SUPQTY FROM SPLSCN_TBL 
            WHERE SPLSCN_DOC LIKE 'PR-%'
            GROUP BY SPLSCN_DOC,SPLSCN_LINE,SPLSCN_ITMCD) VSUP
            ON VREQ.VREQPSN=VSUP.VSUPPSN AND VREQ.SPL_LINE=VSUP.SPLSCN_LINE AND VREQ.SPL_ITMCD=VSUP.SPLSCN_ITMCD        
            LEFT JOIN MITM_TBL ON SPL_ITMCD=MITM_ITMCD
            LEFT JOIN (
            SELECT PPSN1_PSNNO,MAX(PPSN1_MDLCD) PPSN1_MDLCD,RTRIM(MAX(MITM_ITMD1)) MITM_ITMD1 FROM XPPSN1 
            LEFT JOIN XMITM_V ON PPSN1_MDLCD=MITM_ITMCD
            WHERE ISNULL(PPSN1_MDLCD,'')!='' GROUP BY PPSN1_PSNNO
            ) VPPSN ON VREQ.VREQPSN=PPSN1_PSNNO
            WHERE REQQTY!=ISNULL(SUPQTY,0)
            ORDER BY RETSCN_CNFRMDT,SPL_ITMCD";
        $query =  $this->db->query($qry , [$pdate1, $pdate2,$pdate1, $pdate2]);
        return $query->result_array();
    }
        
}