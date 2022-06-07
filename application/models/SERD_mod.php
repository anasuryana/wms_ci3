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
        // $qry  = "SELECT ".implode(',', $pcols)." FROM
        // (
        // SELECT SERD2_PSNNO,SERD2_JOB,SERD2_LINENO,SERD2_PROCD,SERD2_CAT,SERD2_FR,SERD2_MC,SERD2_FGQTY,SERD2_MCZ,SERD2_QTPER,SUM(SERD2_QTY) SERD2_QTYSUM,SERD2_ITMCD,SERD2_SER FROM SERD2_TBL WHERE SERD2_SER=?
        // GROUP BY SERD2_PSNNO,SERD2_JOB,SERD2_LINENO,SERD2_CAT,SERD2_FR,SERD2_MC,SERD2_MCZ,SERD2_QTPER,SERD2_ITMCD,SERD2_FGQTY,SERD2_PROCD,SERD2_SER) VX
        // INNER JOIN
        // (
        // SELECT SERD2_PSNNO,SERD2_JOB,SERD2_LINENO,SERD2_PROCD,SERD2_CAT,SERD2_FR,SERD2_MC,SERD2_MCZ,SERD2_QTPER,SUM(SERD2_QTY) QTYPERMC,SERD2_SER FROM SERD2_TBL WHERE SERD2_SER=?
        // GROUP BY SERD2_PSNNO,SERD2_JOB,SERD2_LINENO,SERD2_CAT,SERD2_FR,SERD2_MC,SERD2_MCZ,SERD2_QTPER,SERD2_PROCD,SERD2_SER
        // ) VXH ON VX.SERD2_PSNNO=VXH.SERD2_PSNNO AND VX.SERD2_JOB=VXH.SERD2_JOB AND VX.SERD2_LINENO=VXH.SERD2_LINENO AND VX.SERD2_CAT=VXH.SERD2_CAT AND VX.SERD2_FR=VXH.SERD2_FR
        // AND VX.SERD2_MC=VXH.SERD2_MC AND VX.SERD2_MCZ=VXH.SERD2_MCZ AND VX.SERD2_QTPER=VXH.SERD2_QTPER AND VX.SERD2_PROCD=VXH.SERD2_PROCD AND VX.SERD2_SER=VXH.SERD2_SER
        // INNER JOIN MITM_TBL ON SERD2_ITMCD=MITM_ITMCD
        // ORDER BY VX.SERD2_MCZ,VX.SERD2_MC,VX.SERD2_PROCD,SERD2_ITMCD";
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
        // $this->db->select("'$pser' SER, SERD2_ITMCD, SUM(SERD2_QTPER) TTLPER, SUM(SERD2_QTPER)*$pqty RMQTY,  SERD2_SER, ");
        $this->db->select("SERD2_ITMCD ITH_ITMCD, SUM(SERD2_QTPER)*$pqty BEFQTY, '$pwh' ITH_WH");
        $this->db->from("vserd2_cims");
        $this->db->where("SERD2_SER",$psersample);
        $this->db->group_by("SERD2_SER,SERD2_ITMCD");
        $query = $this->db->get();
        return $query->result_array();
    }
    public function select_group_byser($psersample){
        // $this->db->select("RTRIM(SERD2_ITMCD) ITH_ITMCD, SUM(SERD2_QTPER) QTPER,SERD2_SER");
        // $this->db->from("vserd2_cims");
        // $this->db->where_in("SERD2_SER",$psersample);
        // $this->db->group_by("SERD2_SER,RTRIM(SERD2_ITMCD)");
        // $query = $this->db->get();
        // return $query->result_array();
        $this->db->select("RTRIM(SERD2_ITMCD) ITH_ITMCD, SUM(SERD2_QTY)/MAX(SERD2_FGQTY) QTPER,SERD2_SER");
        $this->db->from("SERD2_TBL")->join("MITM_TBL", "SERD2_ITMCD=MITM_ITMCD", "LEFT");
        $this->db->where_in("SERD2_SER",$psersample)->where("MITM_MODEL",'0');
        $this->db->group_by("SERD2_SER,RTRIM(SERD2_ITMCD)");
        $query = $this->db->get();
        return $query->result_array();
    }
    public function select_nonnull_rm($pdate){
        #2021-05-01
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
        // $qry = "SELECT RTRIM(SERD2_ITMCD) ITH_ITMCD
        //             ,SUM(RMQTY) BEFQTY
        //             ,0 PLOTQTY
        //             ,ITH_WH
        //             ,ITH_SER
        //             ,'' REMARK
        //         FROM (
        //             SELECT ITH_SER
        //                 ,SUM(ITH_QTY) BEFQTYFG
        //                 ,ITH_WH
        //             FROM v_ith_tblc
        //             WHERE ITH_FORM NOT IN (
        //                     'SASTART'
        //                     ,'SA'
        //                     )
        //                 AND ITH_DATEC < ?
        //                 AND ITH_WH IN (
        //                     'AFWH3'
        //                     ,'QAFG'
        //                     ,'AFWH9SC'
        //                     ,'AWIP1'
        //                     ,'ARSHP'
        //                     )
        //             GROUP BY ITH_WH
        //                 ,ITH_SER
        //             HAVING SUM(ITH_QTY) > 0
        //             ) VBEFFG
        //         LEFT JOIN (
        //             SELECT SERD2_ITMCD
        //                 ,CEILING(SUM(SERD2_QTY) * 2) / 2 RMQTY
        //                 ,SERD2_SER
        //             FROM SERD2_TBL
        //             LEFT JOIN MITM_TBL ON SERD2_ITMCD = MITM_ITMCD
        //             WHERE MITM_MODEL = '0'
        //                 AND SERD2_SER IN (
        //                     SELECT ITH_SER
        //                     FROM v_ith_tblc
        //                     WHERE ITH_FORM NOT IN (
        //                             'SASTART'
        //                             ,'SA'
        //                             )
        //                         AND ITH_DATEC < ?
        //                         AND ITH_WH IN (
        //                             'AFWH3'
        //                             ,'QAFG'
        //                             ,'AFWH9SC'
        //                             ,'AWIP1'
        //                             ,'ARSHP'
        //                             )
        //                     GROUP BY ITH_WH
        //                         ,ITH_SER
        //                     HAVING SUM(ITH_QTY) > 0
        //                     )
        //             GROUP BY SERD2_SER
        //                 ,SERD2_ITMCD
        //             ) v1 ON ITH_SER = SERD2_SER
        //         WHERE SERD2_SER IS NOT NULL
        //         GROUP BY SERD2_ITMCD
        //             ,ITH_WH,ITH_SER
                
        //         UNION ALL
                
        //         SELECT ITH_ITMCD
        //             ,SUM(ITH_QTY) BEFQTYFG
        //             ,0 PLOTQTY
        //             ,ITH_WH
        //             , NULL ITH_SER
        //             ,'' REMARK
        //         FROM v_ith_tblc
        //         WHERE ITH_FORM NOT IN (
        //                 'SASTART'
        //                 ,'SA'
        //                 )
        //             AND ITH_DATEC < ?
        //             AND ITH_WH IN (
        //                 'ARWH0PD'
        //                 ,'ARWH1'
        //                 ,'ARWH2'
        //                 ,'ARWH9SC'
        //                 ,'NRWH2'
        //                 ,'PLANT_NA'
        //                 ,'PLANT1'
        //                 ,'PLANT2'
        //                 ,'QA'
        //                 )
        //         GROUP BY ITH_WH
        //             ,ITH_ITMCD
        //         HAVING SUM(ITH_QTY) > 0";
        
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

    public function select_dedicateList() {
        $qry = "select VC.*,SER_ITMID,'' REMARK from
        (select SERD2_ITMCD,CEILING(SUM(SERD2_QTY)*2)/2 RMQTY,SERD2_SER from SERD2_TBL left join MITM_TBL on SERD2_ITMCD=MITM_ITMCD WHERE MITM_MODEL='0' AND SERD2_SER IN 
                (SELECT ITH_SER FROM v_ith_tblc WHERE  ITH_FORM NOT IN ('SASTART','SA') AND ITH_DATEC<='2021-04-30'
                AND ITH_WH IN ('AFWH3','QAFG','AFWH9SC','AWIP1','ARSHP')
                AND SERD2_ITMCD IN (
                'E12071',
            '218973600',
            '208977800',
            '210496700',
        '211313600',
        '216975700',
        '204695900',
        '210872600',
        '216297000',
        '216294700',
        '210256500',
        '214785200',
        '203488800',
        '214834400',
        '216254800',
        'E08137',
        '213003100',
        '216294500',
        '216292700',
        '214329500',
        '216290100',
        '214834600',
        '216293700',
        '212235600',
        '212835000',
        '212334802',
        '203698500',
        '216281500',
        '210270900',
        '212318803',
        '206325100',
        '208825600',
        '218169800',
        '217524900',
        '208963500',
        '214836100',
        '211472800',
        '214378200',
        '210972000',
        '200241900',
        '216289300',
        '210270400',
        '216282100',
        '213764000',
        '210270500',
        '203204300',
        '216283100',
        '217735300',
        '210308900',
        '211489400',
        '203187200',
        '202834900',
        '216284300',
        '212248700',
        '204857600',
        '211483800',
        '212676300',
        '211384500',
        '214328900',
        '214837200',
        '211869400',
        '204909900',
        '211484100',
        '206313200',
        '216289500',
        '210892600',
        '217758400',
        '216260300',
        '202876200',
        '216291000',
        '213576400',
        '216293800',
        '216257400',
        '210154700',
        '209450700',
        '213397300',
        '216292600',
        '213358200',
        '208652400',
        '216292200',
        '211334400',
        '203149400',
        '211471200',
        '217702701',
        '203404700',
        '214960300',
        '206313100',
        '211492500',
        '212789400',
        '210496600',
        '212676000',
        '215077003',
        '216260000',
        '213061600',
        '204655200',
        '217683000',
        '215337400',
        '213960700',
        '216292100',
        '218320900',
        '216294000',
        '208837000',
        '214836400',
        '214329200',
        '214328300',
        '203375900',
        '215491100',
        '203319200',
        '216293100',
        '205275300',
        '202602800',
        '216294300',
        'E12072-01',
        '208834000',
        '204742200',
        '215491900',
        '214329000',
        '216280700',
        '216288200',
        '216514400',
        '213576500',
        '217560000',
        '216260200',
        '212136300',
        '209217800',
        '218517700',
        '216255100',
        '214264200',
        '204705600',
        '203283700',
        '214839400',
        '212718900',
        '2216926-7',
        '214337300',
        '216255300',
        '212836500',
        '203278100',
        '206311400',
        '216282300',
        '217735200',
        'E01006',
        '214328600',
        '210639100',
        '216295800',
        '216290800',
        '211652700',
        '214972200',
        '216282600',
        '216293900',
        '212900900',
        '217424800',
        '212141200',
        '214330300',
        '216296200',
        '208803800',
        '212879600',
        '213910200',
        '204514800',
        '216289000',
        '212930700',
        '216260500',
        '216780900',
        '203354701',
        '209176700',
        '215381500',
        '216285500',
        '213033500',
        '217735800',
        '210270700',
        '204724500',
        '216296500',
        '216775400',
        '203659600',
        '216282200',
        '213848500',
        '214088102',
        '128237200',
        '214329400',
        '205631400',
        '200735000',
        '214835800',
        '214328500',
        '214328200',
        '209223500',
        '216282000',
        '210493800',
        '210978500',
        '205276400',
        '212136100',
        '214836900',
        '216259100',
        '216287400',
        '210154600',
        '216294600',
        '212848200',
        '203367200',
        '203682200',
        '218291901',
        '216290700',
        '203614000',
        '214836700',
        '205275700',
        '209171000',
        '203700600',
        '216291300',
        '208866400',
        '216293600',
        '206098800',
        '215492600',
        '216284500',
        '209238900',
        '210847700',
        '217735600',
        '208624800',
        '210638900',
        '215873100',
        '209451000',
        '212170900',
        '216259900',
        '214991800',
        '212738100',
        '216292400',
        '212789700',
        '207131100',
        '202733500',
        '216296300',
        '2040408-0',
        '209218900',
        '205274500',
        '217953703',
        '216702300',
        '216287000',
        '210639300',
        '213969800',
        '212629600',
        '214836200',
        '210711000',
        '204661200',
        '215492000',
        '215013100',
        '1035935-4',
        '128463700',
        '216281300',
        '216282400',
        '210991300',
        '203182300',
        '211534500',
        '204693900',
        '209180500',
        '212789600',
        '218168900',
        '214838200',
        '210205900',
        '216290300',
        '216292000',
        '213404100',
        '211640200',
        '203537700',
        '203305400',
        '216259600',
        '216260400',
        '210980300',
        '215480501',
        '216274700',
        '216472400',
        '216257600',
        '212370500',
        '212900800',
        '215888000',
        '212726300',
        '214838000',
        '215480701',
        '204659400',
        '216281600',
        '203698100',
        '157537500',
        '216295000',
        '217736000',
        '209103100',
        '216280800',
        '212322800',
        '212456800',
        '214330100',
        '203698400',
        '216287600',
        '215491200',
        '209187800',
        '202998000',
        '209361600',
        '203337700',
        '216295300',
        '210365200',
        '212899000',
        '211958800',
        'E04694',
        '216283700',
        '216286400',
        '209173100',
        '3022475-7',
        '216286500',
        '214834500',
        '210234000',
        '210639000',
        '201388300',
        '212439400',
        '212261600',
        '218878002',
        '214328400',
        '213277500',
        '216772900',
        '213275100',
        '216281800',
        '216288900',
        '217524800',
        '203710900',
        '210866900',
        '209182900',
        '213768300',
        '208685800',
        '214785100',
        '216284900',
        '217736100',
        '205238100',
        '213628500',
        '211052200',
        '212105100',
        '215480801',
        '215077203',
        '209179300',
        '203439400',
        '210494200',
        '217735500',
        '212235500',
        '205274700',
        '214367300',
        '216292500',
        '214835500',
        '211323000',
        '217167500',
        '204779600',
        '214837600',
        '212611000',
        '209010300',
        '212157600',
        '202768600',
        '212702000',
        '216291400',
        '216286800',
        '212835100',
        '213884600',
        '209103000',
        '157537400',
        '218028900',
        '202571800',
        '214839200',
        '217843700',
        '214329700',
        '218321100',
        '217403800',
        '216379600',
        '215365500',
        '216167200',
        '215480401',
        '212178900',
        '216975500',
        '211381800',
        '216255000',
        '209188200',
        '205275500',
        '216545600',
        '209222300',
        '205301700',
        '216292800',
        '215491800',
        '217386400',
        '210495700',
        '216296400',
        '209178000',
        '205199700',
        '215146700',
        '212883100',
        '217412500',
        '215491500',
        '210942900',
        '209177900',
        '210755600',
        '203215400',
        '212176200',
        '214834300',
        '209175400',
        '211394700',
        '203583800',
        '215548600',
        '212118300',
        '209141200',
        '217843500',
        '209141800'
                )
                GROUP BY ITH_WH,ITH_SER
                HAVING SUM(ITH_QTY)>0) GROUP BY SERD2_SER,SERD2_ITMCD) VC
                LEFT JOIN SER_TBL ON SERD2_SER=SER_ID";
        $query = $this->db->query($qry);
        return $query->result_array();
    }
}