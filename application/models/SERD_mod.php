<?php

class SERD_mod extends CI_Model {
	private $TABLENAME = "SERD_TBL";
	private $TABLENAME2 = "SERD2_TBL";
	public function __construct()
    {
        $this->load->database();
    }	

    public function insert($data)
    {                
        $this->db->insert($this->TABLENAME,$data);        
        return $this->db->affected_rows();
    }

    public function insert2($data)
    {                
        $this->db->insert($this->TABLENAME2,$data);
        return $this->db->affected_rows();
    }

    public function deletebyID_label($parr){        
        $this->db->where($parr);
        $this->db->delete($this->TABLENAME2);
        return $this->db->affected_rows();
    }

    public function deletebyID_label_undelivered($pSER) {
        $qry = "DELETE SERD2_TBL where SERD2_SER = ?
        and SERD2_SER not in (
        select ITH_SER from ITH_TBL where ITH_SER=? and ITH_WH='ARSHP' AND ITH_QTY<0
        GROUP BY ITH_SER )";
		$this->db->query($qry, [$pSER, $pSER]);
        return $this->db->affected_rows();
    }
    public function deletebyID_label_unused($pSER) {
        $qry = "DELETE SERD2_TBL where SERD2_SER = ?
        and SERD2_SER not in (
        select ITH_SER from ITH_TBL where ITH_SER=? and ITH_WH='AWIP1' AND ITH_QTY<0 AND ITH_FORM='OUT-USE'
        GROUP BY ITH_SER )";
		$this->db->query($qry, [$pSER, $pSER]);
        return $this->db->affected_rows();
    }
    public function deletebyID($parr){        
        $this->db->where($parr);
        $this->db->delete($this->TABLENAME);
        return $this->db->affected_rows();
    }

    public function check_Primary($data)
    {        
        return $this->db->get_where($this->TABLENAME,$data)->num_rows();
    }

    public function check_Primary_label($data)
    {        
        return $this->db->get_where($this->TABLENAME2,$data)->num_rows();
    }

    public function select_calculatedlabel_where($pcols,$pwhere)
    {      
        $qry = "SELECT ".implode(',', $pcols)." FROM vserd2_cims WHERE SERD2_SER=? ORDER BY SERD2_MCZ,SERD2_MC,SERD2_PROCD,SERD2_ITMCD";
         $query = $this->db->query($qry, [$pwhere['SERD2_SER'] ]);
         return $query->result_array();
    }

    public function select_fgqty($preffno){
        $this->db->select("SERD2_FGQTY");
        $this->db->from($this->TABLENAME2);
        $this->db->where("SERD2_SER",$preffno);
        $this->db->group_by("SERD2_FGQTY");
        $query = $this->db->get();
        return $query->result_array();
    }
    public function select_fgqty_in($preffno){
        $this->db->select("SERD2_FGQTY,SERD2_SER");
        $this->db->from($this->TABLENAME2);
        $this->db->where_in("SERD2_SER",$preffno);
        $this->db->group_by("SERD2_FGQTY,SERD2_SER");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_calculatedlabel_where_in($pcols,$pserlist)
    {
        $qry  = "SELECT ".implode(',', $pcols)." FROM
        (
        SELECT SERD2_PSNNO,SERD2_JOB,SERD2_LINENO,SERD2_PROCD,SERD2_CAT,SERD2_FR,SERD2_MC,SERD2_FGQTY,SERD2_MCZ,SERD2_QTPER,SUM(SERD2_QTY) SERD2_QTYSUM,SERD2_ITMCD FROM SERD2_TBL WHERE SERD2_SER in ($pserlist) 
        GROUP BY SERD2_PSNNO,SERD2_JOB,SERD2_LINENO,SERD2_CAT,SERD2_FR,SERD2_MC,SERD2_MCZ,SERD2_QTPER,SERD2_ITMCD,SERD2_FGQTY,SERD2_PROCD) VX
        INNER JOIN
        (
        SELECT SERD2_PSNNO,SERD2_JOB,SERD2_LINENO,SERD2_PROCD,SERD2_CAT,SERD2_FR,SERD2_MC,SERD2_MCZ,SERD2_QTPER,SUM(SERD2_QTY) QTYPERMC FROM SERD2_TBL WHERE SERD2_SER in ($pserlist) 
        GROUP BY SERD2_PSNNO,SERD2_JOB,SERD2_LINENO,SERD2_CAT,SERD2_FR,SERD2_MC,SERD2_MCZ,SERD2_QTPER,SERD2_PROCD
        ) VXH ON VX.SERD2_PSNNO=VXH.SERD2_PSNNO AND VX.SERD2_JOB=VXH.SERD2_JOB AND VX.SERD2_LINENO=VXH.SERD2_LINENO AND VX.SERD2_CAT=VXH.SERD2_CAT AND VX.SERD2_FR=VXH.SERD2_FR
        AND VX.SERD2_MC=VXH.SERD2_MC AND VX.SERD2_MCZ=VXH.SERD2_MCZ AND VX.SERD2_QTPER=VXH.SERD2_QTPER AND VX.SERD2_PROCD=VXH.SERD2_PROCD
        INNER JOIN MITM_TBL ON SERD2_ITMCD=MITM_ITMCD
        ORDER BY VX.SERD2_MCZ,VX.SERD2_MC,VX.SERD2_PROCD,SERD2_ITMCD";
         $query = $this->db->query($qry);
         return $query->result_array();
    }

    public function select_calculatedlabel_where_resume($pids){
        $qry = "select SERD2_SER,COUNT(*) COUNTRM from
        (select SERD2_SER,SERD2_ITMCD from SERD2_TBL where SERD2_SER in  ($pids)
        group by SERD2_SER,SERD2_ITMCD) V1
        GROUP BY SERD2_SER";
        $query = $this->db->query($qry);
		return $query->result_array();        
    }

    public function select_calculatedlabel_byJob($pjob){
        $qry = "select SERD2_SER,COUNT(*) COUNTRM from
        (select SERD2_SER,SERD2_ITMCD from SERD2_TBL where SERD2_JOB in  ($pjob)
        group by SERD2_SER,SERD2_ITMCD) V1
        GROUP BY SERD2_SER";
        $query = $this->db->query($qry);
		return $query->result_array();
    }

    public function insertb($data)
    {        
        $this->db->insert_batch($this->TABLENAME,$data);
        return $this->db->affected_rows();
    }
    public function insertb2($data)
    {
        $this->db->insert_batch($this->TABLENAME2,$data);
        return $this->db->affected_rows();
    }

    public function selectall_where_psn_in($ppsn){        
        $this->db->from($this->TABLENAME);
        $this->db->where_in("SERD_PSNNO",$ppsn);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectall_where_psn_in_except_job($ppsn, $pjob_except){
        $this->db->from($this->TABLENAME);
        $this->db->where_in("SERD_PSNNO",$ppsn)->where_not_in("SERD_JOB" ,$pjob_except );
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selecth_byjob($pjob,$pqtyser){        
        $qry = "select  SERD_LINENO,SERD_PROCD, MAX(SERD_CAT) SERD_CAT, SERD_FR, SERD_JOB, SERD_QTPER, SERD_MC,SERD_MCZ, ($pqtyser*SERD_QTPER) SERREQQTY, 0 SUPSERQTY,SERD_MPART from SERD_TBL WHERE SERD_JOB=?
        GROUP BY SERD_LINENO,  SERD_FR, SERD_JOB, SERD_QTPER, SERD_MC,SERD_MCZ,SERD_PROCD, SERD_MPART";
        $query = $this->db->query($qry, [$pjob]);
		return $query->result_array();
    }
    public function selectd_byjob($pjob){            
        $qry = "select SERD_PSNNO, SERD_LINENO,SERD_PROCD, SERD_CAT, SERD_FR, SERD_JOB, SERD_QTPER, SERD_ITMCD,ISNULL(SERD_LOTNO,'') SERD_LOTNO,(SERD_QTY-COALESCE(SERD2_QTY,0))  SERD_QTY
        ,SERD_MSCANTM,SERD_MC,SERD_MCZ,SERD_MPART from 
		(select SERD_PSNNO, SERD_LINENO,SERD_PROCD, SERD_CAT, SERD_FR, SERD_JOB, SERD_QTPER, SERD_ITMCD,ISNULL(SERD_LOTNO,'') SERD_LOTNO,sum(SERD_QTY) SERD_QTY
        ,max(SERD_MSCANTM) SERD_MSCANTM,SERD_MC,SERD_MCZ,SERD_MPART from SERD_TBL
		group by SERD_PSNNO, SERD_LINENO,SERD_PROCD, SERD_CAT, SERD_FR, SERD_JOB, SERD_QTPER, SERD_ITMCD,ISNULL(SERD_LOTNO,'')
        ,SERD_MC,SERD_MCZ,SERD_MPART
		) v0 LEFT JOIN 
        (select SERD2_PSNNO, SERD2_LINENO, SERD2_CAT, SERD2_FR, SERD2_JOB, SERD2_QTPER, SERD2_ITMCD,SERD2_LOTNO,CASE WHEN SUM(SERD2_QTY)=0 THEN SERD2_QTPER*MAX(SERD2_FGQTY) ELSE SUM(SERD2_QTY) END SERD2_QTY,SERD2_MC,SERD2_MCZ,SERD2_PROCD from SERD2_TBL
        WHERE SERD2_JOB=?
        GROUP BY SERD2_PSNNO, SERD2_LINENO,SERD2_CAT,  SERD2_FR, SERD2_JOB, SERD2_QTPER, SERD2_ITMCD, SERD2_LOTNO,SERD2_MC,SERD2_MCZ,SERD2_PROCD ) v1
        on SERD_PSNNO=SERD2_PSNNO and SERD_LINENO=SERD2_LINENO and SERD_CAT=SERD2_CAT and SERD_FR=SERD2_FR and SERD_QTPER=SERD2_QTPER and
        SERD_ITMCD=SERD2_ITMCD and SERD_LOTNO=SERD2_LOTNO AND SERD_MC=SERD2_MC AND SERD_MCZ=SERD2_MCZ AND SERD_PROCD=SERD2_PROCD
		AND SERD_JOB=SERD2_JOB
        WHERE  SERD_JOB=? AND (SERD_QTY-COALESCE(SERD2_QTY,0))>0
        order by SERD_MC,SERD_MCZ,SERD_ITMCD,SERD_MSCANTM asc, SERD_LOTNO ASC";
        $query = $this->db->query($qry, [$pjob,$pjob]);
		return $query->result_array();
    }

    public function selectH_RM_byser($pser){
        $qry = "select SER_ITMID, SERD2_ITMCD,MITM_ITMD1,MITM_HSCD,SERD2_LOTNO,SERD2_QTY,MITM_STKUOM,MITM_HSCD from SERD2_TBL
        INNER JOIN SER_TBL ON SERD2_SER=SER_ID
        INNER JOIN MITM_TBL ON SERD2_ITMCD=MITM_ITMCD
        WHERE SERD2_SER IN ($pser)";
        $query = $this->db->query($qry);
        return $query->result_array();
    }

    public function select_uncalculated(){
        $qry = "SELECT SER_DOC FROM
        (SELECT SER_DOC FROM
        (select ITH_SER,SUM(ITH_QTY) STKQTY from ITH_TBL where ITH_WH='AFWH3'
        GROUP BY ITH_SER
        HAVING SUM(ITH_QTY)>0 ) V1
        LEFT JOIN SER_TBL ON V1.ITH_SER=SER_ID
        GROUP BY SER_DOC) VD1
        LEFT JOIN
        (SELECT SERD_JOB FROM SERD_TBL 
        GROUP BY SERD_JOB) VD2 ON VD1.SER_DOC=VD2.SERD_JOB
        inner join XWO on SER_DOC=PDPP_WONO
        WHERE SERD_JOB IS NULL AND SER_DOC NOT LIKE '%-16%'
        ";
         $query = $this->db->query($qry);
         return $query->result_array();
    }

    public function select_perlabel_resume_item($pser){
        $qry = "select count(*) TTLRM from
        (SELECT SERD2_ITMCD FROM SERD2_TBL WHERE SERD2_SER=?
                GROUP BY SERD2_ITMCD) V1";
         $query = $this->db->query($qry, [$pser]);
        if ($query->num_rows()>0){
            $ret = $query->row();
            return $ret->TTLRM;
        } else {
            return 0;
        }        
    }

    public function select_reff_cutoff_not_calculated($pdate){
        $qry = "SELECT VSTK.*,SER_DOC,SER_QTY FROM
        (SELECT ITH_SER,SUM(ITH_QTY) STKQTY FROM v_ith_tblc WHERE ITH_DATEC<=? AND ITH_WH='AFWH3'
        group by ITH_SER
        having sum(ITH_QTY) >0) VSTK
        LEFT JOIN SERD2_TBL ON ITH_SER=SERD2_SER
        LEFT JOIN SER_TBL ON ITH_SER=SER_ID
        WHERE SERD2_SER IS NULL
        ORDER BY ITH_SER";
        $query = $this->db->query($qry, [$pdate]);
        return $query->result_array();
    }

    public function select_group_byser_tes($pser, $pqty, $psersample, $pwh){        
        $this->db->select("SERD2_ITMCD ITH_ITMCD, SUM(SERD2_QTPER)*$pqty BEFQTY, '$pwh' ITH_WH");
        $this->db->from("vserd2_cims");
        $this->db->where("SERD2_SER",$psersample);
        $this->db->group_by("SERD2_SER,SERD2_ITMCD");
        $query = $this->db->get();
        return $query->result_array();
    }
    public function select_group_byser($psersample){  
        $this->db->select("RTRIM(SERD2_ITMCD) ITH_ITMCD, SUM(SERD2_QTY)/MAX(SERD2_FGQTY) QTPER,SERD2_SER");
        $this->db->from("SERD2_TBL")->join("MITM_TBL", "SERD2_ITMCD=MITM_ITMCD", "LEFT");
        $this->db->where_in("SERD2_SER",$psersample)->where("MITM_MODEL",'0');
        $this->db->group_by("SERD2_SER,RTRIM(SERD2_ITMCD)");
        $query = $this->db->get();
        return $query->result_array();
    }
    public function select_nonnull_rm($pdate){        
        $qry = "SELECT RTRIM(SERD2_ITMCD) ITH_ITMCD, SUM(RMQTY) BEFQTY,0 PLOTQTY ,ITH_WH, '' REMARK FROM
		(SELECT ITH_SER,SUM(ITH_QTY) BEFQTYFG, ITH_WH FROM v_ith_tblc WHERE  ITH_FORM NOT IN ('SASTART','SA') AND ITH_DATEC<=?
		AND ITH_WH IN ('AFWH3','QAFG','AFWH9SC','AWIP1','ARSHP')
		GROUP BY ITH_WH,ITH_SER
		HAVING SUM(ITH_QTY)>0) VBEFFG
		LEFT JOIN (select SERD2_ITMCD,CEILING(SUM(SERD2_QTY)*2)/2 RMQTY,SERD2_SER from SERD2_TBL left join MITM_TBL on SERD2_ITMCD=MITM_ITMCD WHERE MITM_MODEL='0' AND SERD2_SER IN 
        (SELECT ITH_SER FROM v_ith_tblc WHERE  ITH_FORM NOT IN ('SASTART','SA') AND ITH_DATEC<=?
		AND ITH_WH IN ('AFWH3','QAFG','AFWH9SC','AWIP1','ARSHP')
		GROUP BY ITH_WH,ITH_SER
		HAVING SUM(ITH_QTY)>0) GROUP BY SERD2_SER,SERD2_ITMCD) v1 ON ITH_SER=SERD2_SER
		WHERE SERD2_SER IS NOT NULL
		group by SERD2_ITMCD,ITH_WH	
        UNION ALL
        SELECT ITH_ITMCD,SUM(ITH_QTY) BEFQTYFG, 0 PLOTQTY, ITH_WH,'' REMARK FROM v_ith_tblc WHERE  ITH_FORM NOT IN ('SASTART','SA') AND ITH_DATEC<=?
		AND ITH_WH IN ('ARWH0PD','ARWH1','ARWH2','ARWH9SC','NRWH2','PLANT_NA','PLANT1','PLANT2','QA')
		GROUP BY ITH_WH,ITH_ITMCD
		HAVING SUM(ITH_QTY)>0 ";
        $query = $this->db->query($qry, [$pdate, $pdate, $pdate]);
        return $query->result_array();        
    }    

    public function updatebyId($pdata, $pkey)
    {        
        $this->db->where($pkey);
        $this->db->update($this->TABLENAME2, $pdata);
        return $this->db->affected_rows();
    }

    public function select_nonnull_rm_subassy($pdate){
        $qry = "SELECT RTRIM(SERD2_ITMCD) ITH_ITMCD, SUM(RMQTY) BEFQTY,0 PLOTQTY ,ITH_WH, 'XSUBASSY' REMARK
        FROM (
            SELECT VBEFFG.*
                ,SERML_COMID
            FROM (
                SELECT ITH_SER
                    ,SUM(ITH_QTY) BEFQTYFG
                    ,ITH_WH
                FROM v_ith_tblc
                WHERE ITH_FORM NOT IN (
                        'SASTART'
                        ,'SA'
                        )
                    AND ITH_DATEC <= ?
                    AND ITH_WH IN (
                        'AFWH3'
                        ,'QAFG'
                        ,'AFWH9SC'
                        ,'AWIP1'
                        ,'ARSHP'
                        )
                GROUP BY ITH_WH
                    ,ITH_SER
                HAVING SUM(ITH_QTY) > 0
                ) VBEFFG
            INNER JOIN SERML_TBL ON ITH_SER = SERML_NEWID
            ) VBEFFG1
        LEFT JOIN (
            SELECT SERD2_ITMCD
                ,SUM(SERD2_QTY) RMQTY
                ,SERD2_SER
            FROM SERD2_TBL
            WHERE SERD2_SER IN (
                    SELECT SERML_COMID
                    FROM (
                        SELECT ITH_SER
                            ,SUM(ITH_QTY) BEFQTYFG
                            ,ITH_WH
                        FROM v_ith_tblc
                        WHERE ITH_FORM NOT IN (
                                'SASTART'
                                ,'SA'
                                )
                            AND ITH_DATEC <= ?
                            AND ITH_WH IN (
                                'AFWH3'
                                ,'QAFG'
                                ,'AFWH9SC'
                                ,'AWIP1'
                                ,'ARSHP'
                                )
                        GROUP BY ITH_WH
                            ,ITH_SER
                        HAVING SUM(ITH_QTY) > 0
                        ) VBEFFG
                    INNER JOIN SERML_TBL ON ITH_SER = SERML_NEWID
                    )
            GROUP BY SERD2_SER
                ,SERD2_ITMCD
            ) v1 ON SERML_COMID = SERD2_SER
        GROUP BY RTRIM(SERD2_ITMCD), ITH_WH";
        $query = $this->db->query($qry, [$pdate, $pdate]);
        return $query->result_array();
       
    }

    public function selectbyVAR_with_cols($pwhere, $pcols)
	{   
        $this->db->select($pcols);
        $this->db->from($this->TABLENAME2);
        $this->db->join("MITM_TBL", "SERD2_ITMCD=MITM_ITMCD");
        $this->db->like($pwhere);
		$query = $this->db->get();
		return $query->result_array();
    }

    function select_calculation_perID($pStringID, $pID, $pJob, $pQTY) {
        $qry = "SELECT SERD2_PSNNO,SERD2_LINENO,SERD2_PROCD,SERD2_CAT,SERD2_FR,SERD2_QTPER,SERD2_MC,SERD2_MCZ,SERD2_ITMCD,SUM(SERD2_QTY) SERD2_QTY,SERD2_LOTNO,SERD2_MSCANTM,SERD2_REMARK,'$pID' SERD2_SER, '$pJob' SERD2_JOB, $pQTY SERD2_FGQTY FROM SERD2_TBL WHERE SERD2_SER IN ($pStringID)
            GROUP BY SERD2_PSNNO,SERD2_LINENO,SERD2_PROCD,SERD2_CAT,SERD2_FR,SERD2_QTPER,SERD2_MC,SERD2_MCZ,SERD2_ITMCD,SERD2_LOTNO,SERD2_MSCANTM,SERD2_REMARK
            ORDER BY SERD2_MCZ";
        $query = $this->db->query($qry);
        return $query->result_array();
    }
}