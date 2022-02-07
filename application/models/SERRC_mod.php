<?php

class SERRC_mod extends CI_Model {
	private $TABLENAME = "SERRC_TBL";
	public function __construct()
    {
        $this->load->database();
    }

    public function insert($data)
    {        
        $this->db->insert($this->TABLENAME,$data);
        return $this->db->affected_rows();
    }

    public function check_Primary($data)
    {
        return $this->db->get_where($this->TABLENAME,$data)->num_rows();
    }

    public function lastserialid($pdoc){
        $qry = "select MAX(SERRC_LINE) lser from $this->TABLENAME 
        WHERE SERRC_SER=?";
        $query =  $this->db->query($qry, [$pdoc]);  
        if ($query->num_rows()>0){
            $ret = $query->row();
            return $ret->lser;
        } else {
            return '0';
        }
    }

    public function select_balanceqalabel($pqalabel, $pdoc){
        $this->db->from($this->TABLENAME.' a');
        $this->db->where("SERRC_SER", $pqalabel)->where('SERRC_DOCST !=', $pdoc);
        $this->db->group_by("SERRC_SER, SERRC_JM");
        $this->db->select("SERRC_JM");
		$query = $this->db->get();
		return $query->result_array();
    }
    public function select_balanceqalabel_wjm($pqalabel, $pdoc){
        $qry = "select SER_ITMID,ORIQTY-SUM(isnull(SERRC_SERXQTY,0)) BALQTY,MIN(SERRC_JM) SERRC_JM from
        (select SER_ITMID,SERRC_SERX,SERRC_SERXQTY,max(SER_QTY) ORIQTY,MIN(SERRC_JM) SERRC_JM from SERRC_TBL
        left join SER_TBL on SERRC_SER=SER_ID
        where SERRC_SER=?
        AND SERRC_DOCST != ?
        GROUP BY SERRC_SERX,SERRC_SERXQTY,SER_ITMID) v1
        GROUP BY ORIQTY,SER_ITMID";
        $query = $this->db->query($qry, [$pqalabel,$pdoc]);
		return $query->result_array();        
    }

    public function select_xbalance_v1($pqalabel, $pdoc) { //hybrid
        $qry = "SELECT SUM(BALQT) BALQTY FROM
        (
        SELECT COUNT(*) BALQT FROM
        (SELECT * FROM
        (select SERRC_JM from SERRC_TBL 
                where SERRC_SER=? AND SERRC_JM!='' AND SERRC_DOCST!= ?
        GROUP BY SERRC_JM)
        VJM) V1
        UNION  ALL
        SELECT SERRC_SERXQTY FROM
        (select SUM(SERRC_SERXQTY) SERRC_SERXQTY from SERRC_TBL 
                where SERRC_SER=? AND SERRC_JM='' AND SERRC_DOCST!= ?
        GROUP BY SERRC_JM)
        VNOJM
        ) VX";

        $query = $this->db->query($qry, [$pqalabel,$pdoc,$pqalabel,$pdoc]);
        return $query->result_array();
    }
    public function select_balanceqalabel_with_jm($pqalabel, $pdoc){
        $qry = "select SER_ITMID,SER_QTY-ISNULL(OUTQTY,0) BALQTY, SERRC_JM from
        SER_TBL LEFT JOIN 
        ( SELECT SERRC_SER,count(*) OUTQTY,MIN(SERRC_JM) SERRC_JM FROM (
        select SERRC_SER,SERRC_JM from SERRC_TBL 
        where SERRC_SER=? and SERRC_DOCST!= ?
        group by SERRC_SER,SERRC_JM) v1 group by SERRC_SER) v2 ON SER_ID=SERRC_SER
        WHERE SER_ID=? ";
        $query = $this->db->query($qry, [$pqalabel,$pdoc, $pqalabel]);
		return $query->result_array();      
    }    

    public function update_where($pdata, $pwhere)
    {
        $this->db->where($pwhere);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
    }

    public function select_where($pwhere){
        $this->db->select("a.*, RTRIM(b.MITM_ITMD1) MITM_ITMD1,SER_ITMID,SER_QTY, RTRIM(d.MITM_ITMD1) ASSYD1");
        $this->db->from($this->TABLENAME.' a');
        $this->db->join('MITM_TBL b', 'a.SERRC_BOMPN=b.MITM_ITMCD', 'left');
        $this->db->join('SER_TBL c', 'SERRC_SER=SER_ID');
        $this->db->join('MITM_TBL d', 'SER_ITMID=d.MITM_ITMCD', 'left');
        $this->db->where($pwhere);
        $this->db->order_by("SER_ITMID, SERRC_JM");
		$query = $this->db->get();
		return $query->result_array();
    }

    public function selectjm_where($p){
        $this->db->group_by("SERRC_LOTNO");
        $this->db->select("SERRC_LOTNO");
        $this->db->from($this->TABLENAME.' a');
        $this->db->where($p);
		$query = $this->db->get();
		return $query->result_array();
    }

    public function select_out_usage($pdoc){
        $qry = "select SERRC_SER,count(*) OUTQTY,SER_DOC,SERRC_SERX,SER_ITMID from 
		(select SERRC_SER,SERRC_JM,SERRC_SERX from SERRC_TBL where SERRC_DOCST = ?
		group by SERRC_SER,SERRC_JM,SERRC_SERX) vjm
		left join SER_TBL ON SERRC_SER=SER_ID
		group by SERRC_SER,SER_DOC,SERRC_SERX,SER_ITMID";
        $query = $this->db->query($qry, [$pdoc]);
		return $query->result_array();
    }

    public function select_where_group($pwhere){
        $this->db->group_by("SERRC_SERX,SERRC_SER,SERRC_NASSYCD,SERRC_SERXQTY,SERRC_SERXRAWTXT,SER_DOC,SER_ITMID,SER_QTY");
        $this->db->select("SERRC_SERX,SERRC_SER,SERRC_NASSYCD,ISNULL(SERRC_SERXQTY,0) SERRC_SERXQTY,SERRC_SERXRAWTXT,SER_DOC,SER_ITMID,SER_QTY,RTRIM(min(SERRC_JM)) SERRC_JM
        ,MAX(XVU_RTN.MBSG_BSGRP) MBSG_BSGRP");//,
        $this->db->from($this->TABLENAME.' a');
        $this->db->join("SER_TBL", "SERRC_SER=SER_ID");
        $this->db->join("XVU_RTN", "SER_DOC=STKTRND1_DOCNO", "LEFT");
        $this->db->where($pwhere);
		$query = $this->db->get();
		return $query->result_array();
    }
    public function select_balance($pwhere){
        $this->db->group_by("SERRC_SERX,SERRC_SER,SERRC_NASSYCD,SERRC_SERXQTY,SERRC_SERXRAWTXT,SER_DOC,SER_ITMID,SER_QTY");
        $this->db->select("SERRC_SERX,SERRC_SER,SERRC_NASSYCD,ISNULL(SERRC_SERXQTY,0) SERRC_SERXQTY,SERRC_SERXRAWTXT,SER_DOC,SER_ITMID,SER_QTY");
        $this->db->from($this->TABLENAME.' a');
        $this->db->join("SER_TBL", "SERRC_SER=SER_ID");
        $this->db->where($pwhere);
		$query = $this->db->get();
		return $query->result_array();
    }

    public function select_qajm_where_group($pwhere){
        $this->db->group_by("SERRC_SER,SERRC_JM");
        $this->db->select("SERRC_SER,SERRC_JM");
        $this->db->from($this->TABLENAME.' a');        
        $this->db->where($pwhere);
		$query = $this->db->get();
		return $query->result_array();
    }

    public function deletebyID($parr){        
        $this->db->where($parr);
        $this->db->delete($this->TABLENAME);
        return $this->db->affected_rows();
    }

    public function select_lastnodo_patterned($pmonth, $pyear){
        $qry = "select ISNULL(MAX(CONVERT(INT,SUBSTRING(SERRC_DOCST,1,3))),0)  lser
        from $this->TABLENAME where MONTH(SERRC_DOCSTDT) =? and YEAR(SERRC_DOCSTDT)=? ";
        $query =  $this->db->query($qry, [$pmonth, $pyear]);        
        if ($query->num_rows()>0){
            $ret = $query->row();
            return $ret->lser;
        } else {
            return '0';
        }
    }

    public function select_ra_byst($pdoc){
        $qry = "select SER_DOC from SERRC_TBL    
        inner join SER_TBL ON SERRC_SER=SER_ID    
        where SERRC_DOCST = ?
        group by SER_DOC";
        $query = $this->db->query($qry, [$pdoc]);
		return $query->result_array();
    }

    

    public function select_serahterima_h_byst($pdok){
        $qry = "select SERRC_DOCST,max(SERRC_DOCSTDT) DT, MAX(MSTEMP_FNM) FNM  from SERRC_TBL
        INNER JOIN MSTEMP_TBL ON SERRC_USRID=MSTEMP_ID        
        where SERRC_DOCST LIKE ?
        group by SERRC_DOCST";
        $query = $this->db->query($qry, ['%'.$pdok.'%']);
		return $query->result_array();
    }
    public function select_serahterima_h_bysl($pdok){
        $qry = "select SERRC_DOCST,max(SERRC_DOCSTDT) DT, MAX(MSTEMP_FNM) FNM  from SERRC_TBL
        INNER JOIN MSTEMP_TBL ON SERRC_USRID=MSTEMP_ID        
        where SERRC_SER LIKE ?
        group by SERRC_DOCST";
        $query = $this->db->query($qry, ['%'.$pdok.'%']);
		return $query->result_array();
    }
    public function select_serahterima_h_bydo($pdok){
        $qry = "select SERRC_DOCST,max(SERRC_DOCSTDT) DT, MAX(MSTEMP_FNM) FNM  from SERRC_TBL
        INNER JOIN MSTEMP_TBL ON SERRC_USRID=MSTEMP_ID  
        INNER JOIN SER_TBL ON SERRC_SER=SER_ID
        where SER_DOC LIKE ?
        group by SERRC_DOCST";
        $query = $this->db->query($qry, ['%'.$pdok.'%']);
		return $query->result_array();
    }
    public function select_serahterima_d($pdok){
        // $qry = "select SERRC_DOCST,SERRC_DOCSTDT,SER_TBL.SER_DOC,SER_PRDDT,SER_ITMID,SERRC_JM,RTRIM(A.MITM_ITMD1) MITM_ITMD1,PLANT,SERRC_BOMPN,SERRC_NASSYCD,RMRK, rtrim(B.MITM_SPTNO) MITM_SPTNO,SERRC_LOC  from SERRC_TBL
        // inner join SER_TBL on SERRC_SER=SER_ID
        // INNER JOIN MSTEMP_TBL ON SERRC_USRID=MSTEMP_ID
        // inner join MITM_TBL A on SER_ITMID=MITM_ITMCD
        // inner join (
        // select SER_DOC,SER_PRDLINE,MAX(RETFG_CONSIGN) PLANT,MAX(RETFG_RMRK) RMRK from SERRC_TBL
        // inner join SER_TBL on SERRC_SER=SER_ID
        // INNER JOIN MSTEMP_TBL ON SERRC_USRID=MSTEMP_ID
        // inner join MITM_TBL on SER_ITMID=MITM_ITMCD
        // INNER JOIN RETFG_TBL ON SER_DOC=RETFG_DOCNO AND SER_PRDLINE=RETFG_LINE
        // group by SER_DOC, SER_PRDLINE
        // ) V1 ON SER_TBL.SER_DOC=V1.SER_DOC AND SER_TBL.SER_PRDLINE=V1.SER_PRDLINE
        // LEFT JOIN MITM_TBL B ON ISNULL(SERRC_BOMPN,'')=B.MITM_ITMCD WHERE SERRC_DOCST = ? ORDER BY SERRC_JM";
        // $qry = "SELECT VX.*,SERRC_LOC FROM 
        // (select SERRC_DOCST,SERRC_DOCSTDT,SER_TBL.SER_DOC,SER_PRDDT,SER_ITMID,SERRC_JM,RTRIM(A.MITM_ITMD1) MITM_ITMD1,PLANT,SERRC_BOMPN,SERRC_NASSYCD,RMRK, rtrim(B.MITM_SPTNO) MITM_SPTNO,sum(SERRC_BOMPNQTY) RMQTY  from SERRC_TBL
        //         inner join SER_TBL on SERRC_SER=SER_ID
        //         INNER JOIN MSTEMP_TBL ON SERRC_USRID=MSTEMP_ID
        //         inner join MITM_TBL A on SER_ITMID=MITM_ITMCD
        //         inner join (
        //         select SER_DOC,SER_PRDLINE,MAX(RETFG_CONSIGN) PLANT,MAX(RETFG_RMRK) RMRK from SERRC_TBL
        //         inner join SER_TBL on SERRC_SER=SER_ID
        //         INNER JOIN MSTEMP_TBL ON SERRC_USRID=MSTEMP_ID
        //         inner join MITM_TBL on SER_ITMID=MITM_ITMCD
        //         INNER JOIN RETFG_TBL ON SER_DOC=RETFG_DOCNO AND SER_PRDLINE=RETFG_LINE
        //         group by SER_DOC, SER_PRDLINE
        //         ) V1 ON SER_TBL.SER_DOC=V1.SER_DOC AND SER_TBL.SER_PRDLINE=V1.SER_PRDLINE
        //         LEFT JOIN MITM_TBL B ON ISNULL(SERRC_BOMPN,'')=B.MITM_ITMCD WHERE SERRC_DOCST = ?
        //         group by  SERRC_DOCST,SERRC_DOCSTDT,SER_TBL.SER_DOC,SER_PRDDT,SER_ITMID,SERRC_JM,rtrim(A.MITM_ITMD1),PLANT,SERRC_BOMPN,SERRC_NASSYCD,RMRK, rtrim(B.MITM_SPTNO)) VX
        //         INNER JOIN 
        //         (SELECT SERRC_JM,SERRC_BOMPN,SERRC_LOC FROM SERRC_TBL) VD ON VX.SERRC_JM=VD.SERRC_JM and vx.SERRC_BOMPN=vd.SERRC_BOMPN
        //         ORDER BY SER_ITMID,SERRC_JM";
        $qry = "SELECT VX.*,SERRC_LOC FROM 
        (select SERRC_DOCST,SERRC_DOCSTDT,SER_TBL.SER_DOC,SER_PRDDT,SER_ITMID,SERRC_JM,RTRIM(A.MITM_ITMD1) MITM_ITMD1,PLANT,SERRC_BOMPN,SERRC_NASSYCD,RMRK, rtrim(B.MITM_SPTNO) MITM_SPTNO,sum(SERRC_BOMPNQTY) RMQTY,SERRC_LOC,sum(SERRC_SERXQTY) SERRC_SERXQTY  from SERRC_TBL
                inner join SER_TBL on SERRC_SER=SER_ID
                INNER JOIN MSTEMP_TBL ON SERRC_USRID=MSTEMP_ID
                inner join MITM_TBL A on SER_ITMID=MITM_ITMCD
                inner join (
                select SER_DOC,SER_PRDLINE,MAX(RETFG_CONSIGN) PLANT,MAX(RETFG_RMRK) RMRK from SERRC_TBL
                inner join SER_TBL on SERRC_SER=SER_ID
                INNER JOIN MSTEMP_TBL ON SERRC_USRID=MSTEMP_ID
                inner join MITM_TBL on SER_ITMID=MITM_ITMCD
                INNER JOIN RETFG_TBL ON SER_DOC=RETFG_DOCNO AND SER_PRDLINE=RETFG_LINE
                group by SER_DOC, SER_PRDLINE
                ) V1 ON SER_TBL.SER_DOC=V1.SER_DOC AND SER_TBL.SER_PRDLINE=V1.SER_PRDLINE
                LEFT JOIN MITM_TBL B ON ISNULL(SERRC_BOMPN,'')=B.MITM_ITMCD WHERE SERRC_DOCST = ?
                group by  SERRC_DOCST,SERRC_DOCSTDT,SER_TBL.SER_DOC,SER_PRDDT,SER_ITMID,SERRC_JM,rtrim(A.MITM_ITMD1),PLANT,SERRC_BOMPN,SERRC_NASSYCD,RMRK, rtrim(B.MITM_SPTNO),SERRC_LOC) VX                
                ORDER BY SER_DOC,SER_ITMID,SERRC_JM";
        $query = $this->db->query($qry, [$pdok]);
		return $query->result_array();
    }
}