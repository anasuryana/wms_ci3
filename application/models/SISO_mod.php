<?php
class SISO_mod extends CI_Model {
	private $TABLENAME = "SISO_TBL";
	public function __construct()
    {
        $this->load->database();
    }	
	
	public function insert($data)
    {        
        $this->db->insert($this->TABLENAME,$data);
        return $this->db->affected_rows();
    }
	public function insertb($data)
    {        
        $this->db->insert_batch($this->TABLENAME,$data);
        return $this->db->affected_rows();
    }

    public function insertb_temp($data)
    {        
        $this->db->insert_batch('TEMPFSO_TBL',$data);
        return $this->db->affected_rows();
    }

    public function select_lastsisoline($pdoc){
        // $qry = "select max(convert(bigint,SUBSTRING(SISO_FLINE,16,4))) lline from SISO_TBL where SISO_HLINE=?";
        $qry = "select max(SISO_LINE) lline from SISO_TBL where SISO_HLINE=?";
        $query =  $this->db->query($qry, [$pdoc]);
        if ($query->num_rows()>0){
            $ret = $query->row();
            return $ret->lline;
        } else {
            return '0';
        }
    }

    public function selectSObyYear($pbg, $pcust, $pyear, $pmonth){
        $qry = "select SSO2_CPONO,SSO2_ISUDT from XSSO2 where SSO2_BSGRP=? AND SSO2_CUSCD=? and SSO2_CPONO like '%SME%'
        AND YEAR(SSO2_ISUDT)=? AND MONTH(SSO2_ISUDT)=?
        group by SSO2_CPONO, SSO2_ISUDT
        ORDER BY SSO2_ISUDT";
		$query =  $this->db->query($qry, [$pbg, $pcust, $pyear, $pmonth]);
		return $query->result_array();
    }


    public function selectall_like($plike){
        $this->db->select("SISO_HLINE,SISO_CPONO,SISO_SOLINE,SISO_QTY,SI_ITMCD,SI_QTY,SSO2_SLPRC,SISO_FLINE");
		$this->db->from($this->TABLENAME." a");
		$this->db->join("SI_TBL b", "SISO_HLINE=SI_LINENO");
		$this->db->join("XSSO2", "SISO_CPONO=SSO2_CPONO AND SISO_SOLINE=SSO2_SOLNO","left");
        $this->db->like($plike);        
		$query = $this->db->get();
		return $query->result_array();
    }

    public function selectall_where($pwhere){
        $this->db->select("SISO_HLINE,SISO_CPONO,SISO_SOLINE,SISO_QTY,SI_ITMCD,SI_QTY,SSO2_SLPRC,SISO_FLINE,SI_BSGRP,SI_CUSCD");
		$this->db->from($this->TABLENAME." a");
		$this->db->join("SI_TBL b", "SISO_HLINE=SI_LINENO");
		$this->db->join("XSSO2", "SISO_CPONO=SSO2_CPONO AND SISO_SOLINE=SSO2_SOLNO","left");
        $this->db->where($pwhere); 
		$query = $this->db->get();
		return $query->result_array();
    }
    public function selectall_where_txid($pTXID){
        $this->db->select("SISO_HLINE,SISO_CPONO,SISO_SOLINE,SISO_QTY,SI_ITMCD,SI_QTY,SSO2_SLPRC,SISO_FLINE,SI_BSGRP,SI_CUSCD");
		$this->db->from($this->TABLENAME." a");
		$this->db->join("SI_TBL b", "SISO_HLINE=SI_LINENO");
		$this->db->join("(SELECT SISCN_LINENO FROM SISCN_TBL INNER JOIN DLV_TBL ON SISCN_SER=DLV_SER WHERE DLV_ID='$pTXID' GROUP BY SISCN_LINENO) c", "SI_LINENO=SISCN_LINENO");
		$this->db->join("XSSO2", "SISO_CPONO=SSO2_CPONO AND SISO_SOLINE=SSO2_SOLNO","left");        
		$query = $this->db->get();
		return $query->result_array();
    }

    public function deleteby_filter($pwhere)
    {          
        $this->db->where($pwhere);
        $this->db->delete($this->TABLENAME);
        return $this->db->affected_rows();
    }

    public function delete_H_in($arrayHeader)
    {          
        $this->db->where_in("SISO_HLINE", $arrayHeader);
        $this->db->delete($this->TABLENAME);
        return $this->db->affected_rows();
    }
    public function deletetempby_filter()
    {          
        $this->db->where('TEMPFSO_NO is not null',null);
        $this->db->delete('TEMPFSO_TBL');
        return $this->db->affected_rows();
    }
    
    public function selectgreatersothansi(){                
        $qry = "exec sp_getgreatersothansi";        
		$query =  $this->db->query($qry);    
		return $query->result_array();
    }
    
    public function selecttempfifoso(){
        $qry = "SELECT V1.*, SSO2_ORDQT FROM
        (SELECT TEMPFSO_NO,TEMPFSO_LINE,TEMPFSO_MDL,SUM(TEMPFSO_PLOTQTY) PLOTQTY FROM TEMPFSO_TBL 
        GROUP BY TEMPFSO_NO,TEMPFSO_LINE,TEMPFSO_MDL)
        V1 LEFT JOIN XSSO2 ON TEMPFSO_NO=SSO2_CPONO AND TEMPFSO_LINE=SSO2_SOLNO AND TEMPFSO_MDL=SSO2_MDLCD
        WHERE PLOTQTY> SSO2_ORDQT";        
		$query =  $this->db->query($qry);    
		return $query->result_array();
    }

    public function updatebyId($pdata, $pkey)
    { 
        $this->db->where($pkey);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
    }

    public function select_currentPlot($pdoc){
        $qry = "SELECT SISO_HLINE,SSO2_SLPRC,SUM(SISO_QTY) PLOTQTY,SISO_SOLINE,SISO_CPONO,MAX(SCNQT) SCNQT,RTRIM(MAX(ITMID)) ITMID,MAX(SSO2_BSGRP) SSO2_BSGRP FROM SISO_TBL
        LEFT JOIN XSSO2 ON SISO_CPONO=SSO2_CPONO AND SSO2_SOLNO=SISO_SOLINE
		LEFT JOIN (SELECT SISCN_LINENO,SUM(SISCN_SERQTY) SCNQT,UPPER(max(SER_ITMID)) ITMID FROM SISCN_TBL left join SER_TBL on SISCN_SER=SER_ID WHERE SISCN_SER IN (SELECT DLV_SER FROM DLV_TBL WHERE DLV_ID=?) GROUP BY SISCN_LINENO)
		VX ON SISO_HLINE=SISCN_LINENO
        WHERE SISO_QTY>0 AND SISO_HLINE IN (SELECT SISCN_LINENO FROM SISCN_TBL WHERE SISCN_SER IN (SELECT DLV_SER FROM DLV_TBL WHERE DLV_ID=?) GROUP BY SISCN_LINENO)
        GROUP BY SISO_HLINE,SSO2_SLPRC,SSO2_MDLCD,SISO_SOLINE,SISO_CPONO";
        $query =  $this->db->query($qry, [$pdoc,$pdoc]);    
		return $query->result_array();
    }
    public function select_currentPrice($txid, $itemcd){
        $qry = "select SISO_TBL.*,SSO2_SLPRC from SISO_TBL left join XSSO2 on SISO_CPONO=SSO2_CPONO and SISO_SOLINE=XSSO2.SSO2_SOLNO where SISO_QTY>0 AND SISO_HLINE in (
            select SISCN_LINENO from SISCN_TBL left join DLV_TBL on SISCN_SER=DLV_SER
                left join SER_TBL on SISCN_SER=SER_ID
                where DLV_ID=? and SER_ITMID=?
            )";
        $query =  $this->db->query($qry, [$txid,$itemcd]);
		return $query->result_array();
    }
}