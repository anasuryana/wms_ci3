<?php

class RCV_mod extends CI_Model {
	private $TABLENAME = "RCV_TBL";
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

    public function deletebyDOandItem($pdo, $pitem)
    {          
        $this->db->where('RCV_DONO', $pdo)->where('RCV_ITMCD', $pitem);
        $this->db->delete($this->TABLENAME);
        return $this->db->affected_rows();
    }

    public function deletebyID($parr){        
        $this->db->where($parr);
        $this->db->delete($this->TABLENAME);
        return $this->db->affected_rows();
	}

    public function deletebyDO($pdo)
    {          
        $this->db->where('RCV_DONO', $pdo);
        $this->db->delete($this->TABLENAME);
        return $this->db->affected_rows();
    }

    public function check_Primary($data)
    {
        return $this->db->get_where($this->TABLENAME,$data)->num_rows();
    }

    public function updatebyId($pdata, $pdo, $pitem)
    {           
        $this->db->where('RCV_DONO',$pdo)->where('RCV_ITMCD', $pitem);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();        
    }

    public function updatebyId_new($pdata, $pdo, $pitem, $pqty)
    {   
        $qry = "update top (1) RCV_TBL SET RCV_HSCD=?, RCV_BM=?, RCV_PPN=?,RCV_PPH=?, RCV_ZNOURUT=? 
        WHERE RCV_DONO=? AND RCV_ITMCD=? AND RCV_QTY=? and ISNULL(RCV_ZNOURUT,'') =''";
        $this->db->query($qry , [$pdata['RCV_HSCD'], $pdata['RCV_BM'], $pdata['RCV_PPN'], $pdata['RCV_PPH'], $pdata['RCV_ZNOURUT'], $pdo, $pitem, $pqty] );        
        return $this->db->affected_rows();
    }

    public function updatebyId_new_no_qty($pdata, $pdo, $pitem)
    {   
        $qry = "update top (1) RCV_TBL SET RCV_HSCD=?, RCV_BM=?, RCV_PPN=?,RCV_PPH=?, RCV_ZNOURUT=? 
        WHERE RCV_DONO=? AND RCV_ITMCD=? AND ISNULL(RCV_ZNOURUT,'') =''";
        $this->db->query($qry , [$pdata['RCV_HSCD'], $pdata['RCV_BM'], $pdata['RCV_PPN'], $pdata['RCV_PPH'], $pdata['RCV_ZNOURUT'], $pdo, $pitem] );        
        return $this->db->affected_rows();
    }

    public function updatebyId_withqty($pdata, $pdo, $pitem, $pqty)
    {        
        $this->db->where('RCV_DONO',$pdo)->where('RCV_ITMCD', $pitem)->where('RCV_QTY');
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
    }

    public function updatebyId_withgrlno($pdata, $pdo, $pitem, $pgrlno)
    {        
        $this->db->where('RCV_DONO',$pdo)->where('RCV_ITMCD', $pitem)->where('RCV_GRLNO', $pgrlno);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
    }

    public function updatebyDO($pdata, $pdo)
    {        
        $this->db->where('RCV_DONO',$pdo);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
    }

    public function updatebyVAR($pdata, $pvar)
    {        
        $this->db->where($pvar);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
    }

    public function select_cols_where($pcols, $pwhere){
        $this->db->limit(1);
        $this->db->from($this->TABLENAME);
        $this->db->join('MITM_TBL', 'RCV_ITMCD=MITM_ITMCD', 'LEFT');
        $this->db->select($pcols);
        $this->db->WHERE($pwhere);
        $this->db->not_like("RCV_DONO",'-RNK');
		$query = $this->db->get();
		return $query->result();
    }    


	public function selectbyid($pid)
	{       
        $this->db->select("RCV_DONO,RCV_PO,RCV_RCVDATE,RCV_SUPID,MSUP_SUPNM,MSUP_SUPCR");
        $this->db->join('MSUP_TBL b', "a.RCV_SUPID=b.MSUP_SUPCD");         
        $this->db->like('RCV_DONO', $pid);
        $this->db->group_by('RCV_DONO,RCV_PO,RCV_RCVDATE,RCV_SUPID,MSUP_SUPNM,MSUP_SUPCR');
		$query = $this->db->get($this->TABLENAME.' a', 10);
		return $query->result();
    }

    public function selectbyYM($pyear, $pmonth) //YearMonth
	{
        $this->db->select("RCV_DONO,RCV_PO,RCV_RCVDATE,RCV_SUPCD,MSUP_SUPNM,MSUP_SUPCR,RCV_BCTYPE,RCV_BCNO");
        $this->db->join('MSUP_TBL b', "a.RCV_SUPCD=b.MSUP_SUPCD");         
        $this->db->where('year(RCV_RCVDATE)', $pyear)->where('month(RCV_RCVDATE)', $pmonth)->where('RCV_RPNO IS NOT NULL',NULL,FALSE)->where('RCV_RPNO !=', '');
        $this->db->group_by('RCV_DONO,RCV_PO,RCV_RCVDATE,RCV_SUPCD,MSUP_SUPNM,MSUP_SUPCR,RCV_BCTYPE,RCV_BCNO');
		$query = $this->db->get($this->TABLENAME.' a');
		return $query->result();
    }
    public function selectbyYM_open($pyear, $pmonth) //YearMonth open
	{
        $this->db->select("RCV_DONO,max(RCV_PO) RCV_PO,RCV_RCVDATE,RCV_SUPCD,MSUP_SUPNM,MSUP_SUPCR,RCV_BCTYPE,RCV_BCNO");
        $this->db->join('MSUP_TBL b', "a.RCV_SUPCD=b.MSUP_SUPCD", "LEFT");
        $this->db->join('(select RCVSCN_DONO,sum(RCVSCN_QTY) TTLSCN from RCVSCN_TBL
        GROUP BY RCVSCN_DONO) v1', "RCV_DONO=RCVSCN_DONO", "LEFT");
        $this->db->where('year(RCV_RCVDATE)', $pyear)->where('month(RCV_RCVDATE)', $pmonth)->where('RCV_RPNO IS NOT NULL',NULL,FALSE)->where('RCV_RPNO !=', '');
        $this->db->group_by('RCV_DONO,RCV_RCVDATE,RCV_SUPCD,MSUP_SUPNM,MSUP_SUPCR,RCV_BCTYPE,RCV_BCNO,TTLSCN');
        $this->db->having('SUM(RCV_QTY) > COALESCE(TTLSCN,0)');
		$query = $this->db->get($this->TABLENAME.' a');
		return $query->result();
    }

    public function selectbydo($pid)
	{
        $this->db->from($this->TABLENAME.' a');
        $this->db->select("MITM_ITMCD,MITM_SPTNO,RCV_PO,RCV_RCVDATE,RCV_QTY,RCV_AMT");   
        $this->db->join('MITM_TBL b', "REPLACE(a.RCV_ITMCD,CHAR(13)+CHAR(10),'')=b.MITM_ITMCD");     
        $this->db->WHERE('RCV_DONO', $pid);
		$query = $this->db->get();
		return $query->result();
    }
    public function select_where($pcols, $pwhere)
	{
        $this->db->from($this->TABLENAME.' a');
        $this->db->select($pcols);
        $this->db->join("MITM_TBL","RCV_ITMCD=MITM_ITMCD","LEFT");
        $this->db->WHERE($pwhere);
        $this->db->order_by("RCV_ZNOURUT,RCV_ITMCD");
		$query = $this->db->get();
		return $query->result_array();
    }

    public function select_where_group($pcols, $pwhere)
	{
        $this->db->from($this->TABLENAME.' a');
        $this->db->select($pcols);
        $this->db->WHERE($pwhere);
        $this->db->group_by($pcols);
		$query = $this->db->get();
		return $query->result_array();
    }
    public function select_like_group($pcols, $plike)
	{
        $this->db->from($this->TABLENAME.' a');
        $this->db->select($pcols);
        $this->db->join("v_supplier_customer_union", "RCV_SUPCD=MSUP_SUPCD", "LEFT");
        $this->db->like($plike);
        $this->db->group_by($pcols);
		$query = $this->db->get();
		return $query->result_array();
    }

    public function select_do_only(){
        $qry = "SELECT RCV_DONO FROM  (
            select RCV_DONO from RCV_TBL GROUP BY RCV_DONO)
            V1 LEFT JOIN
            (
            SELECT RPSTOCK_DOC FROM PSI_RPCUST.dbo.RPSAL_BCSTOCK group by RPSTOCK_DOC
            ) V2 ON RCV_DONO=RPSTOCK_DOC
            WHERE RPSTOCK_DOC IS NULL AND RCV_DONO NOT LIKE '%-RNK%'";
        $query = $this->db->query($qry);		
		return $query->result();
    }
    
    public function selectBCField($pdo){
        $this->db->from($this->TABLENAME.' a');
        $this->db->select("RCV_RPNO,RCV_RPDATE,RCV_BCTYPE,RCV_BCNO,RCV_BCDATE,RCV_TPB");
        $this->db->WHERE('RCV_DONO', $pdo);
        $this->db->group_by('RCV_RPNO,RCV_RPDATE,RCV_BCTYPE,RCV_BCNO,RCV_BCDATE,RCV_TPB');
		$query = $this->db->get();
		return $query->result();
    }

    public function selectBCField_in($pdo){
        $this->db->from($this->TABLENAME.' a');
        $this->db->select("RCV_DONO,RCV_RPNO,RCV_RPDATE,RCV_RCVDATE,RCV_BCTYPE,RCV_BCNO,RCV_BCDATE,MSUP_SUPNM");
        $this->db->join('MSUP_TBL b', 'a.RCV_SUPCD=b.MSUP_SUPCD', 'left');
        $this->db->where_in('RCV_DONO', $pdo);
        $this->db->group_by('RCV_DONO,RCV_RPNO,RCV_RPDATE,RCV_RCVDATE,RCV_BCTYPE,RCV_BCNO,RCV_BCDATE,MSUP_SUPNM');
		$query = $this->db->get();
		return $query->result();
    }

    public function selectscan_balancing($pwhere){
        
        $this->db->from('vrcv_tblg a');
        $this->db->select("RCV_ITMCD, RCV_QTY, RCVSCNQTY SCAN_QTY,RCV_WH,MSTLOCG_NM");
        $this->db->join('(SELECT RCVSCN_DONO,RCVSCN_ITMCD,SUM(RCVSCN_QTY) RCVSCNQTY FROM RCVSCN_TBL GROUP BY RCVSCN_DONO,RCVSCN_ITMCD) b', "a.RCV_DONO=b.RCVSCN_DONO and a.RCV_ITMCD=b.RCVSCN_ITMCD", 'LEFT');
        $this->db->join('MSTLOCG_TBL c', "a.RCV_WH=c.MSTLOCG_ID", 'LEFT');     
        $this->db->where($pwhere);
        $query = $this->db->get();        
		return $query->result_array();
    }

    public function selectbalancebyDOITEM($pdo, $pitem){
        $qry = "SELECT v1.* FROM
        (SELECT RCV_ITMCD, RCV_QTY, COALESCE(SUM(RCVSCN_QTY),0) SCAN_QTY,RCV_WH,MSTLOCG_NM, max(RCVSCN_LUPDT) LTSSCANTIME 
		FROM (select RCV_DONO,RCV_ITMCD,SUM(RCV_QTY) RCV_QTY,RCV_WH FROM RCV_TBL
		GROUP BY RCV_DONO,RCV_ITMCD,RCV_WH) a 
                LEFT JOIN RCVSCN_TBL b ON a.RCV_DONO=b.RCVSCN_DONO and a.RCV_ITMCD=b.RCVSCN_ITMCD
                LEFT JOIN MSTLOCG_TBL c ON a.RCV_WH=c.MSTLOCG_ID 
                WHERE RCV_DONO='$pdo' AND RCV_ITMCD='$pitem'
                GROUP BY RCV_DONO, RCV_ITMCD,RCV_QTY, RCV_WH,MSTLOCG_NM
                ) v1 ";
        $query = $this->db->query($qry);
		return $query->result_array();
    }
    
    public function selectscan_balancingv2($pdo){        
        $qry = "SELECT v1.*,COALESCE(ITHQTY,0) ITHQTY FROM
        (SELECT RCV_ITMCD, RCV_QTY, COALESCE(SUM(RCVSCN_QTY),0) SCAN_QTY,RCV_WH,MSTLOCG_NM, max(RCVSCN_LUPDT) LTSSCANTIME 
		FROM (select RCV_DONO,RCV_ITMCD,SUM(RCV_QTY) RCV_QTY,RCV_WH FROM RCV_TBL
		GROUP BY RCV_DONO,RCV_ITMCD,RCV_WH) a 
                LEFT JOIN RCVSCN_TBL b ON a.RCV_DONO=b.RCVSCN_DONO and a.RCV_ITMCD=b.RCVSCN_ITMCD
                LEFT JOIN MSTLOCG_TBL c ON a.RCV_WH=c.MSTLOCG_ID 
                WHERE RCV_DONO='".$pdo."'  
                GROUP BY RCV_DONO, RCV_ITMCD,RCV_QTY, RCV_WH,MSTLOCG_NM
                ) v1
        LEFT JOIN (
        SELECT ITH_ITMCD,COALESCE(sum(ITH_QTY),0) ITHQTY FROM ITH_TBL
        WHERE ITH_DOC='".$pdo."' and ITH_FORM='INC-DO'
        GROUP BY ITH_DOC, ITH_ITMCD) v2 ON v1.RCV_ITMCD=v2.ITH_ITMCD ORDER BY v1.RCV_ITMCD";
        $query = $this->db->query($qry);
		return $query->result_array();
    }

    public function MGSelectDO($pdo){
        $qry = "SELECT top 100 V1.*,COALESCE(TTLITEMIN,0) TTLITEMIN,ISNULL(RCV_HSCD,'') RCV_HSCD
        , ISNULL(RCV_BM,0) RCV_BM,ISNULL(RCV_PPN,0) RCV_PPN, ISNULL(RCV_PPH,0) RCV_PPH,CONVERT(DATE,PGRN_RCVDT) PGRN_RCVDT,ISNULL(RTRIM(PNGR_INVNO),'') PNGR_INVNO FROM 
        (SELECT PGRN_SUPNO,MSUP_SUPNM,COUNT(*) TTLITEM,max(PGRN_RCVDT) PGRN_RCVDT,PGRN_BSGRP FROM XPGRN_VIEW a
        GROUP BY PGRN_SUPNO,MSUP_SUPNM,PGRN_BSGRP) v1
        left join
        (SELECT RCV_DONO,COUNT(*) TTLITEMIN,MIN(RCV_HSCD) RCV_HSCD, MIN(RCV_BM) RCV_BM
        , MIN(RCV_PPN) RCV_PPN, MIN(RCV_PPH) RCV_PPH,RCV_BSGRP FROM RCV_TBL b        
        GROUP BY RCV_DONO,RCV_BSGRP) v2 ON v1.PGRN_SUPNO=v2.RCV_DONO AND PGRN_BSGRP=RCV_BSGRP
        LEFT JOIN XPNGR ON PGRN_SUPNO=PNGR_SUPNO AND PNGR_BSGRP=PGRN_BSGRP
        where PGRN_SUPNO like '%$pdo%'
        ORDER BY v1.PGRN_RCVDT DESC";
        $query = $this->db->query($qry);
		return $query->result_array();
    }
    public function SelectDO1($pdo){
        $qry = "SELECT RCV_DONO,COUNT(*) TTLITEMIN,MIN(RCV_HSCD) RCV_HSCD, MIN(RCV_BM) RCV_BM
        , MIN(RCV_PPN) RCV_PPN, MIN(RCV_PPH) RCV_PPH,RCV_BSGRP,MSUP_SUPCD,MSUP_SUPNM,MIN(RCV_INVNO) RCV_INVNO
		,MIN(RCV_BCDATE) RCV_BCDATE,MAX(RCV_BCTYPE) RCV_BCTYPE,MAX(RCV_ZSTSRCV) RCV_ZSTSRCV,MAX(RCV_RPNO) RCV_RPNO
        ,MAX(RCV_BCNO) RCV_BCNO,MAX(RCV_RPDATE) RCV_RPDATE,MAX(RCV_RCVDATE) RCV_RCVDATE,MAX(RCV_TPB) RCV_TPB,MAX(RCV_KPPBC) RCV_KPPBC
		,MAX(RCV_NW) RCV_NW,MAX(RCV_GW) RCV_GW,MAX(RCV_CONA) RCV_CONA,MAX(RCV_DUEDT) RCV_DUEDT,MAX(RCV_CONADT) RCV_CONADT
		FROM RCV_TBL b  
		left join MITM_TBL on RCV_ITMCD=MITM_ITMCD
		LEFT JOIN (SELECT MSUP_SUPCD,MAX(MSUP_SUPNM) MSUP_SUPNM FROM v_supplier_customer_union GROUP BY MSUP_SUPCD ) S ON RCV_SUPCD=S.MSUP_SUPCD
		where MITM_MODEL='6' AND RCV_DONO like ?
        GROUP BY RCV_DONO,RCV_BSGRP,MSUP_SUPCD,MSUP_SUPNM";
        $query = $this->db->query($qry, ['%'.$pdo.'%']);
		return $query->result_array();
    }
    public function SelectDO2($pdo){
        $qry = "SELECT RCV_DONO,COUNT(*) TTLITEMIN,MIN(RCV_HSCD) RCV_HSCD, MIN(RCV_BM) RCV_BM
        , MIN(RCV_PPN) RCV_PPN, MIN(RCV_PPH) RCV_PPH,RCV_BSGRP,MSUP_SUPCD,S.MSUP_SUPNM,MIN(RCV_INVNO) RCV_INVNO
		,MIN(RCV_BCDATE) RCV_BCDATE,MAX(RCV_BCTYPE) RCV_BCTYPE,MAX(RCV_ZSTSRCV) RCV_ZSTSRCV,MAX(RCV_RPNO) RCV_RPNO
        ,MAX(RCV_BCNO) RCV_BCNO,MAX(RCV_RPDATE) RCV_RPDATE,MAX(RCV_RCVDATE) RCV_RCVDATE,MAX(RCV_TPB) RCV_TPB,MAX(RCV_KPPBC) RCV_KPPBC
		,MAX(RCV_NW) RCV_NW,MAX(RCV_GW) RCV_GW,MAX(RCV_CONA) RCV_CONA,MAX(RCV_DUEDT) RCV_DUEDT,MAX(RCV_WH) RCV_WH
		FROM RCV_TBL b          
		left join MITM_TBL on RCV_ITMCD=MITM_ITMCD
		LEFT JOIN v_supplier_customer_union S ON RCV_SUPCD=S.MSUP_SUPCD
		where MITM_MODEL='0' AND RCV_DONO like ?
        GROUP BY RCV_DONO,RCV_BSGRP,MSUP_SUPCD,S.MSUP_SUPNM";
        $query = $this->db->query($qry, ['%'.$pdo.'%']);
		return $query->result_array();
    }
    public function MGSelectDO_return_fg($pdo){        
        $qry = "SELECT TOP 100 XVU_RTN.*
                    ,COALESCE(TTLITEMIN, 0) TTLITEMIN
                    ,ISNULL(RCV_HSCD, '') RCV_HSCD
                    ,ISNULL(RCV_BM, 0) RCV_BM
                    ,ISNULL(RCV_PPN, 0) RCV_PPN
                    ,ISNULL(RCV_PPH, 0) RCV_PPH
                    ,MBSG_BSGRP
                    ,RCV_SUPCD,isnull(MSUP_SUPNM,'') MSUP_SUPNM
                FROM XVU_RTN
                LEFT JOIN (
                    SELECT RCV_DONO
                        ,COUNT(*) TTLITEMIN
                        ,MIN(RCV_HSCD) RCV_HSCD
                        ,MIN(RCV_BM) RCV_BM
                        ,MIN(RCV_PPN) RCV_PPN
                        ,MIN(RCV_PPH) RCV_PPH
                        ,MAX(RCV_SUPCD) RCV_SUPCD
                    FROM RCV_TBL b
                    GROUP BY RCV_DONO
                ) v2 ON STKTRND1_DOCNO = v2.RCV_DONO
                LEFT JOIN (
                    SELECT MSUP_SUPCD,MAX(MSUP_SUPNM) MSUP_SUPNM FROM v_supplier_customer_union GROUP BY MSUP_SUPCD
                ) VSUP ON isnull(RCV_SUPCD,'')=MSUP_SUPCD
                WHERE STKTRND1_DOCNO LIKE ? ORDER BY ISUDT DESC";
        $query = $this->db->query($qry, ['%'.$pdo.'%']);
		return $query->result_array();
    }
    public function MGSelectDOSup($pdo, $psup){
        $qry = "SELECT top 100 V1.*,COALESCE(TTLITEMIN,0) TTLITEMIN,ISNULL(RCV_HSCD,'') RCV_HSCD
        , ISNULL(RCV_BM,0) RCV_BM,ISNULL(RCV_PPN,0) RCV_PPN, ISNULL(RCV_PPH,0) RCV_PPH,CONVERT(DATE,PGRN_RCVDT) PGRN_RCVDT,ISNULL(RTRIM(PNGR_INVNO),'') PNGR_INVNO  FROM 
        (SELECT PGRN_SUPNO,MSUP_SUPNM,COUNT(*) TTLITEM,max(PGRN_RCVDT) PGRN_RCVDT,PGRN_BSGRP FROM XPGRN_VIEW a 
        where PGRN_SUPCD = ?
        GROUP BY PGRN_SUPNO,MSUP_SUPNM,PGRN_BSGRP) v1
        left join
        (SELECT RCV_DONO,COUNT(*) TTLITEMIN,MIN(RCV_HSCD) RCV_HSCD, MIN(RCV_BM) RCV_BM
        , MIN(RCV_PPN) RCV_PPN, MIN(RCV_PPH) RCV_PPH,RCV_BSGRP FROM RCV_TBL b        
        GROUP BY RCV_DONO,RCV_BSGRP) v2 ON v1.PGRN_SUPNO=v2.RCV_DONO AND PGRN_BSGRP=RCV_BSGRP
        LEFT JOIN XPNGR ON PGRN_SUPNO=PNGR_SUPNO AND PGRN_BSGRP=PNGR_BSGRP
        where PGRN_SUPNO like ? 
        ORDER BY v1.PGRN_RCVDT DESC";
        $query = $this->db->query($qry, [$psup,'%'.$pdo.'%']);
		return $query->result_array();
    }
    public function MGSelectDOSup_return_fg($pdo, $psup){
        $qry = "SELECT V1.*,COALESCE(TTLITEMIN,0) TTLITEMIN,ISNULL(RCV_HSCD,'') RCV_HSCD
        , ISNULL(RCV_BM,0) RCV_BM,ISNULL(RCV_PPN,0) RCV_PPN, ISNULL(RCV_PPH,0) RCV_PPH,RCV_SUPCD,isnull(MSUP_SUPNM,'') MSUP_SUPNM FROM
        (select STKTRND1_DOCNO,MBSG_DESC,MBSG_BSGRP,COUNT(*) TTLITEM,ISUDT from XVU_RTN where MBSG_BSGRP=?
        GROUP BY STKTRND1_DOCNO,MBSG_DESC,MBSG_BSGRP,ISUDT) V1
        left join
                (SELECT RCV_DONO,COUNT(*) TTLITEMIN,MIN(RCV_HSCD) RCV_HSCD, MIN(RCV_BM) RCV_BM
                , MIN(RCV_PPN) RCV_PPN, MIN(RCV_PPH) RCV_PPH,max(RCV_SUPCD) RCV_SUPCD FROM RCV_TBL b        
                GROUP BY RCV_DONO) v2 ON v1.STKTRND1_DOCNO=v2.RCV_DONO
		LEFT JOIN (
            SELECT MSUP_SUPCD,MAX(MSUP_SUPNM) MSUP_SUPNM FROM v_supplier_customer_union GROUP BY MSUP_SUPCD
        ) VSUP ON isnull(RCV_SUPCD,'')=MSUP_SUPCD
        where STKTRND1_DOCNO LIKE ?";
        $query = $this->db->query($qry, [$psup,'%'.$pdo.'%']);
		return $query->result_array();
    }

    public function MGSelectDO_date($pdo , $pdate1 , $pdate2){
        $qry = "SELECT V1.*,COALESCE(TTLITEMIN,0) TTLITEMIN,ISNULL(RCV_HSCD,'') RCV_HSCD
        , ISNULL(RCV_BM,0) RCV_BM,ISNULL(RCV_PPN,0) RCV_PPN, ISNULL(RCV_PPH,0) RCV_PPH,CONVERT(DATE, PGRN_RCVDT) PGRN_RCVDT,ISNULL(RTRIM(PNGR_INVNO),'') PNGR_INVNO  FROM 
        (SELECT PGRN_SUPNO,MSUP_SUPNM,COUNT(*) TTLITEM,max(PGRN_RCVDT) PGRN_RCVDT,PGRN_BSGRP FROM XPGRN_VIEW a
        GROUP BY PGRN_SUPNO,MSUP_SUPNM,PGRN_BSGRP) v1
        left join
        (SELECT RCV_DONO,COUNT(*) TTLITEMIN,MIN(RCV_HSCD) RCV_HSCD, MIN(RCV_BM) RCV_BM
        , MIN(RCV_PPN) RCV_PPN, MIN(RCV_PPH) RCV_PPH,RCV_BSGRP FROM RCV_TBL b        
        GROUP BY RCV_DONO,RCV_BSGRP) v2 ON v1.PGRN_SUPNO=v2.RCV_DONO AND PGRN_BSGRP=RCV_BSGRP
        LEFT JOIN XPNGR ON PGRN_SUPNO=PNGR_SUPNO AND PGRN_BSGRP=PNGR_BSGRP
        where PGRN_SUPNO like ? and CONVERT(DATE, PGRN_RCVDT) BETWEEN ? AND ?
        order by v1.PGRN_RCVDT desc, v2.TTLITEMIN , PGRN_SUPNO";
        $query = $this->db->query($qry, ['%'.$pdo.'%', $pdate1, $pdate2]);
		return $query->result_array();
    }
    public function SelectDO_date1($pdo , $pdate1 , $pdate2){
        $qry = "SELECT RCV_DONO,COUNT(*) TTLITEMIN,MIN(RCV_HSCD) RCV_HSCD, MIN(RCV_BM) RCV_BM
        , MIN(RCV_PPN) RCV_PPN, MIN(RCV_PPH) RCV_PPH,RCV_BSGRP,MSUP_SUPCD,MSUP_SUPNM,MIN(RCV_INVNO) RCV_INVNO
		,MIN(RCV_BCDATE) RCV_BCDATE,MAX(RCV_BCTYPE) RCV_BCTYPE,MAX(RCV_ZSTSRCV) RCV_ZSTSRCV,MAX(RCV_RPNO) RCV_RPNO,
        MAX(RCV_BCNO) RCV_BCNO,MAX(RCV_RPDATE) RCV_RPDATE,MAX(RCV_RCVDATE) RCV_RCVDATE,MAX(RCV_TPB) RCV_TPB,MAX(RCV_KPPBC) RCV_KPPBC
		,MAX(RCV_NW) RCV_NW,MAX(RCV_GW) RCV_GW,MAX(RCV_CONA) RCV_CONA,MAX(RCV_DUEDT) RCV_DUEDT,MAX(RCV_CONADT) RCV_CONADT
        FROM RCV_TBL b  
		left join MITM_TBL on RCV_ITMCD=MITM_ITMCD
		LEFT JOIN (SELECT MSUP_SUPCD,MAX(MSUP_SUPNM) MSUP_SUPNM FROM v_supplier_customer_union GROUP BY MSUP_SUPCD ) S ON RCV_SUPCD=S.MSUP_SUPCD
		where MITM_MODEL='6' AND RCV_DONO like ? and RCV_BCDATE between ? and ?
        GROUP BY RCV_DONO,RCV_BSGRP,MSUP_SUPCD,MSUP_SUPNM
        ORDER BY RCV_BCDATE";
        $query = $this->db->query($qry, ['%'.$pdo.'%', $pdate1, $pdate2]);
		return $query->result_array();
    }
    public function SelectDO_date2($pdo , $pdate1 , $pdate2){
        $qry = "SELECT RCV_DONO,COUNT(*) TTLITEMIN,MIN(RCV_HSCD) RCV_HSCD, MIN(RCV_BM) RCV_BM
        , MIN(RCV_PPN) RCV_PPN, MIN(RCV_PPH) RCV_PPH,RCV_BSGRP,MSUP_SUPCD,S.MSUP_SUPNM,MIN(RCV_INVNO) RCV_INVNO
		,MIN(RCV_BCDATE) RCV_BCDATE,MAX(RCV_BCTYPE) RCV_BCTYPE,MAX(RCV_ZSTSRCV) RCV_ZSTSRCV,MAX(RCV_RPNO) RCV_RPNO,
        MAX(RCV_BCNO) RCV_BCNO,MAX(RCV_RPDATE) RCV_RPDATE,MAX(RCV_RCVDATE) RCV_RCVDATE,MAX(RCV_TPB) RCV_TPB,MAX(RCV_KPPBC) RCV_KPPBC
		,MAX(RCV_NW) RCV_NW,MAX(RCV_GW) RCV_GW,MAX(RCV_CONA) RCV_CONA,MAX(RCV_DUEDT) RCV_DUEDT,MAX(RCV_WH) RCV_WH
        FROM RCV_TBL b  
        left join XPGRN_VIEW ON RCV_DONO=PGRN_SUPNO
		left join MITM_TBL on RCV_ITMCD=MITM_ITMCD
		LEFT JOIN v_supplier_customer_union S ON RCV_SUPCD=S.MSUP_SUPCD
		where MITM_MODEL='0' AND RCV_DONO like ? and RCV_BCDATE between ? and ?
        AND PGRN_SUPNO IS NULL
        GROUP BY RCV_DONO,RCV_BSGRP,MSUP_SUPCD,S.MSUP_SUPNM
        ORDER BY RCV_BCDATE";
        $query = $this->db->query($qry, ['%'.$pdo.'%', $pdate1, $pdate2]);
		return $query->result_array();
    }

    public function MGSelectDO_date_return_fg($pdo , $pdate1 , $pdate2){
        $qry = "SELECT V1.*,COALESCE(TTLITEMIN,0) TTLITEMIN,ISNULL(RCV_HSCD,'') RCV_HSCD
        , ISNULL(RCV_BM,0) RCV_BM,ISNULL(RCV_PPN,0) RCV_PPN, ISNULL(RCV_PPH,0) RCV_PPH,RCV_SUPCD,isnull(MSUP_SUPNM,'') MSUP_SUPNM FROM
        (select STKTRND1_DOCNO,MBSG_DESC,MBSG_BSGRP,COUNT(*) TTLITEM, ISUDT from XVU_RTN
        GROUP BY STKTRND1_DOCNO,MBSG_DESC,MBSG_BSGRP,ISUDT) V1
        left join
                (SELECT RCV_DONO,COUNT(*) TTLITEMIN,MIN(RCV_HSCD) RCV_HSCD, MIN(RCV_BM) RCV_BM
                , MIN(RCV_PPN) RCV_PPN, MIN(RCV_PPH) RCV_PPH,MAX(RCV_SUPCD) RCV_SUPCD FROM RCV_TBL b                  
                GROUP BY RCV_DONO) v2 ON v1.STKTRND1_DOCNO=v2.RCV_DONO
        LEFT JOIN (
            SELECT MSUP_SUPCD,MAX(MSUP_SUPNM) MSUP_SUPNM FROM v_supplier_customer_union GROUP BY MSUP_SUPCD
        ) VSUP ON isnull(RCV_SUPCD,'')=MSUP_SUPCD
        where STKTRND1_DOCNO LIKE ? and (CONVERT(DATE, ISUDT) BETWEEN ? AND ?) 
        ORDER BY ISUDT DESC";
        $query = $this->db->query($qry, ['%'.$pdo.'%', $pdate1, $pdate2]);
		return $query->result_array();
    }

    public function MGSelectDO_dateSup($pdo , $pdate1 , $pdate2, $psup){
        $qry = "SELECT V1.*,COALESCE(TTLITEMIN,0) TTLITEMIN,ISNULL(RCV_HSCD,'') RCV_HSCD
        , ISNULL(RCV_BM,0) RCV_BM,ISNULL(RCV_PPN,0) RCV_PPN, ISNULL(RCV_PPH,0) RCV_PPH,CONVERT(DATE, PGRN_RCVDT) PGRN_RCVDT,ISNULL(RTRIM(PNGR_INVNO),'') PNGR_INVNO  FROM 
        (SELECT PGRN_SUPNO,MSUP_SUPNM,COUNT(*) TTLITEM,max(PGRN_RCVDT) PGRN_RCVDT,PGRN_BSGRP  FROM XPGRN_VIEW a where PGRN_SUPCD=?
        GROUP BY PGRN_SUPNO,MSUP_SUPNM,PGRN_BSGRP) v1
        left join
        (SELECT RCV_DONO,COUNT(*) TTLITEMIN,MIN(RCV_HSCD) RCV_HSCD, MIN(RCV_BM) RCV_BM
        , MIN(RCV_PPN) RCV_PPN, MIN(RCV_PPH) RCV_PPH,RCV_BSGRP FROM RCV_TBL b        
        GROUP BY RCV_DONO, RCV_BSGRP) v2 ON v1.PGRN_SUPNO=v2.RCV_DONO AND PGRN_BSGRP=RCV_BSGRP
        LEFT JOIN XPNGR ON PGRN_SUPNO=PNGR_SUPNO AND PGRN_BSGRP=PNGR_BSGRP
        where PGRN_SUPNO like ? and (CONVERT(DATE, PGRN_RCVDT) BETWEEN ? AND ?) 
        order by v1.PGRN_RCVDT desc, v2.TTLITEMIN asc";
        $query = $this->db->query($qry, [$psup,'%'.$pdo.'%', $pdate1, $pdate2]);
		return $query->result_array();
    }
    public function MGSelectDO_dateSup_return_fg($pdo , $pdate1 , $pdate2, $psup){
        $qry = "SELECT V1.*,COALESCE(TTLITEMIN,0) TTLITEMIN,ISNULL(RCV_HSCD,'') RCV_HSCD
        , ISNULL(RCV_BM,0) RCV_BM,ISNULL(RCV_PPN,0) RCV_PPN, ISNULL(RCV_PPH,0) RCV_PPH,RCV_SUPCD,isnull(MSUP_SUPNM,'') MSUP_SUPNM FROM
        (select STKTRND1_DOCNO,MBSG_DESC,MBSG_BSGRP,COUNT(*) TTLITEM,ISUDT from XVU_RTN where MBSG_BSGRP=?
        GROUP BY STKTRND1_DOCNO,MBSG_DESC,MBSG_BSGRP,ISUDT) V1
        left join
                (SELECT RCV_DONO,COUNT(*) TTLITEMIN,MIN(RCV_HSCD) RCV_HSCD, MIN(RCV_BM) RCV_BM
                , MIN(RCV_PPN) RCV_PPN, MIN(RCV_PPH) RCV_PPH,MAX(RCV_SUPCD) RCV_SUPCD FROM RCV_TBL b        
                GROUP BY RCV_DONO) v2 ON v1.STKTRND1_DOCNO=v2.RCV_DONO
		LEFT JOIN (
            SELECT MSUP_SUPCD,MAX(MSUP_SUPNM) MSUP_SUPNM FROM v_supplier_customer_union GROUP BY MSUP_SUPCD
        ) VSUP ON isnull(RCV_SUPCD,'')=MSUP_SUPCD
        where STKTRND1_DOCNO LIKE ? and (CONVERT(DATE, ISUDT) BETWEEN ? AND ?)  ";
        $query = $this->db->query($qry,[$psup,'%'.$pdo.'%', $pdate1, $pdate2]);
		return $query->result_array();
    }

    public function SelectDO_split($pdo){
        $qry = "SELECT RCV_DONO,MSUP_SUPNM FROM RCV_TBL b left JOIN MSUP_TBL a
        on RCV_SUPCD=MSUP_SUPCD
        WHERE RCV_DONO like ? AND RCV_DONO NOT LIKE '%-RNK'
        GROUP BY RCV_DONO,MSUP_SUPNM";
        $query = $this->db->query($qry, array("%$pdo%"));
		return $query->result_array();
    }

    public function MGSelectDObyItem($pitem){
        $qry = "SELECT top 100 V1.*,COALESCE(TTLITEMIN,0) TTLITEMIN,ISNULL(RCV_HSCD,'') RCV_HSCD
        , ISNULL(RCV_BM,0) RCV_BM,ISNULL(RCV_PPN,0) RCV_PPN, ISNULL(RCV_PPH,0) RCV_PPH,CONVERT(DATE,PGRN_RCVDT) PGRN_RCVDT,ISNULL(RTRIM(PNGR_INVNO),'') PNGR_INVNO  FROM 
        (SELECT PGRN_SUPNO,MSUP_SUPNM,COUNT(*) TTLITEM,max(PGRN_RCVDT) PGRN_RCVDT,PGRN_BSGRP FROM XPGRN_VIEW a where PGRN_ITMCD like '%".$pitem."%'
        GROUP BY PGRN_SUPNO,MSUP_SUPNM,PGRN_BSGRP ) v1
        left join
        (SELECT RCV_DONO,COUNT(*) TTLITEMIN,MIN(RCV_HSCD) RCV_HSCD, MIN(RCV_BM) RCV_BM
        , MIN(RCV_PPN) RCV_PPN, MIN(RCV_PPH) RCV_PPH,RCV_BSGRP FROM RCV_TBL b        
        GROUP BY RCV_DONO,RCV_BSGRP) v2 ON v1.PGRN_SUPNO=v2.RCV_DONO AND PGRN_BSGRP=RCV_BSGRP
        LEFT JOIN XPNGR ON PGRN_SUPNO=PNGR_SUPNO AND PGRN_BSGRP=PNGR_BSGRP
        ORDER BY v1.PGRN_RCVDT DESC";
        $query = $this->db->query($qry);
		return $query->result_array();
    }
    public function SelectDObyItem1($pitem){
        $qry = "SELECT RCV_DONO,COUNT(*) TTLITEMIN,MIN(RCV_HSCD) RCV_HSCD, MIN(RCV_BM) RCV_BM
        , MIN(RCV_PPN) RCV_PPN, MIN(RCV_PPH) RCV_PPH,RCV_BSGRP,MSUP_SUPCD,MSUP_SUPNM,MIN(RCV_INVNO) RCV_INVNO
		,MIN(RCV_BCDATE) RCV_BCDATE,MAX(RCV_BCTYPE) RCV_BCTYPE,MAX(RCV_ZSTSRCV) RCV_ZSTSRCV,MAX(RCV_RPNO) RCV_RPNO
        ,MAX(RCV_BCNO) RCV_BCNO,MAX(RCV_RPDATE) RCV_RPDATE,MAX(RCV_RCVDATE) RCV_RCVDATE,MAX(RCV_TPB) RCV_TPB,MAX(RCV_KPPBC) RCV_KPPBC
		,MAX(RCV_NW) RCV_NW,MAX(RCV_GW) RCV_GW,MAX(RCV_CONA) RCV_CONA,MAX(RCV_DUEDT) RCV_DUEDT,MAX(RCV_CONADT) RCV_CONADT
		FROM RCV_TBL b  
		left join MITM_TBL on RCV_ITMCD=MITM_ITMCD
		LEFT JOIN (SELECT MSUP_SUPCD,MAX(MSUP_SUPNM) MSUP_SUPNM FROM v_supplier_customer_union GROUP BY MSUP_SUPCD ) S ON RCV_SUPCD=S.MSUP_SUPCD
		where MITM_MODEL='6' AND MITM_ITMD1 like ?
        GROUP BY RCV_DONO,RCV_BSGRP,MSUP_SUPCD,MSUP_SUPNM";
        $query = $this->db->query($qry, ['%'.$pitem.'%']);
		return $query->result_array();
    }
    public function SelectDObyItem2($pitem){
        $qry = "SELECT RCV_DONO,COUNT(*) TTLITEMIN,MIN(RCV_HSCD) RCV_HSCD, MIN(RCV_BM) RCV_BM
        , MIN(RCV_PPN) RCV_PPN, MIN(RCV_PPH) RCV_PPH,RCV_BSGRP,MSUP_SUPCD,MSUP_SUPNM,MIN(RCV_INVNO) RCV_INVNO
		,MIN(RCV_BCDATE) RCV_BCDATE,MAX(RCV_BCTYPE) RCV_BCTYPE,MAX(RCV_ZSTSRCV) RCV_ZSTSRCV,MAX(RCV_RPNO) RCV_RPNO
        ,MAX(RCV_BCNO) RCV_BCNO,MAX(RCV_RPDATE) RCV_RPDATE,MAX(RCV_RCVDATE) RCV_RCVDATE,MAX(RCV_TPB) RCV_TPB,MAX(RCV_KPPBC) RCV_KPPBC
		,MAX(RCV_NW) RCV_NW,MAX(RCV_GW) RCV_GW,MAX(RCV_CONA) RCV_CONA,MAX(RCV_DUEDT) RCV_DUEDT,MAX(RCV_WH) RCV_WH
		FROM RCV_TBL b  
		left join MITM_TBL on RCV_ITMCD=MITM_ITMCD
		LEFT JOIN v_supplier_customer_union S ON RCV_SUPCD=S.MSUP_SUPCD
		where MITM_MODEL='0' AND RCV_ITMCD like ?
        GROUP BY RCV_DONO,RCV_BSGRP,MSUP_SUPCD,MSUP_SUPNM";
        $query = $this->db->query($qry, ['%'.$pitem.'%']);
		return $query->result_array();
    }
    public function MGSelectDObyItem_return_fg($pitem){
        $qry = "SELECT top 100 V1.*,COALESCE(TTLITEMIN,0) TTLITEMIN,ISNULL(RCV_HSCD,'') RCV_HSCD
        , ISNULL(RCV_BM,0) RCV_BM,ISNULL(RCV_PPN,0) RCV_PPN, ISNULL(RCV_PPH,0) RCV_PPH,RCV_SUPCD,isnull(MSUP_SUPNM,'') MSUP_SUPNM FROM
        (select STKTRND1_DOCNO,MBSG_DESC,MBSG_BSGRP,COUNT(*) TTLITEM, ISUDT from XVU_RTN_D where STKTRND2_ITMCD like ?
        GROUP BY STKTRND1_DOCNO,MBSG_DESC,MBSG_BSGRP,ISUDT) V1
        left join
                (SELECT RCV_DONO,COUNT(*) TTLITEMIN,MIN(RCV_HSCD) RCV_HSCD, MIN(RCV_BM) RCV_BM
                , MIN(RCV_PPN) RCV_PPN, MIN(RCV_PPH) RCV_PPH,MAX(RCV_SUPCD) RCV_SUPCD FROM RCV_TBL b        
                GROUP BY RCV_DONO) v2 ON v1.STKTRND1_DOCNO=v2.RCV_DONO
		LEFT JOIN (
            SELECT MSUP_SUPCD,MAX(MSUP_SUPNM) MSUP_SUPNM FROM v_supplier_customer_union GROUP BY MSUP_SUPCD
        ) VSUP ON isnull(RCV_SUPCD,'')=MSUP_SUPCD";
        $query = $this->db->query($qry, ['%'.$pitem.'%']);
		return $query->result_array();
    }

    public function MGSelectDObyItemSup($pitem, $psup){
        $qry = "SELECT top 100 V1.*,COALESCE(TTLITEMIN,0) TTLITEMIN,ISNULL(RCV_HSCD,'') RCV_HSCD
        , ISNULL(RCV_BM,0) RCV_BM,ISNULL(RCV_PPN,0) RCV_PPN, ISNULL(RCV_PPH,0) RCV_PPH ,CONVERT(DATE,PGRN_RCVDT) PGRN_RCVDT,ISNULL(RTRIM(PNGR_INVNO),'') PNGR_INVNO FROM 
        (SELECT PGRN_SUPNO,MSUP_SUPNM,COUNT(*) TTLITEM ,max(PGRN_RCVDT) PGRN_RCVDT,PGRN_BSGRP FROM XPGRN_VIEW a where PGRN_ITMCD like ? and PGRN_SUPCD= ?
        GROUP BY PGRN_SUPNO,MSUP_SUPNM,PGRN_BSGRP ) v1
        left join
        (SELECT RCV_DONO,COUNT(*) TTLITEMIN,MIN(RCV_HSCD) RCV_HSCD, MIN(RCV_BM) RCV_BM
        , MIN(RCV_PPN) RCV_PPN, MIN(RCV_PPH) RCV_PPH,RCV_BSGRP FROM RCV_TBL b        
        GROUP BY RCV_DONO,RCV_BSGRP) v2 ON v1.PGRN_SUPNO=v2.RCV_DONO AND PGRN_BSGRP=RCV_BSGRP
        LEFT JOIN XPNGR ON PGRN_SUPNO=PNGR_SUPNO AND PGRN_BSGRP=PNGR_BSGRP
        ORDER BY v1.PGRN_RCVDT DESC";
        $query = $this->db->query($qry, ['%'.$pitem.'%',$psup]);
		return $query->result_array();
    }
    public function MGSelectDObyItemSup_return_fg($pitem, $psup){
        $qry = "SELECT top 100 V1.*,COALESCE(TTLITEMIN,0) TTLITEMIN,ISNULL(RCV_HSCD,'') RCV_HSCD
        , ISNULL(RCV_BM,0) RCV_BM,ISNULL(RCV_PPN,0) RCV_PPN, ISNULL(RCV_PPH,0) RCV_PPH,RCV_SUPCD,isnull(MSUP_SUPNM,'') MSUP_SUPNM FROM
        (select STKTRND1_DOCNO,MBSG_DESC,MBSG_BSGRP,COUNT(*) TTLITEM, ISUDT from XVU_RTN_D 
		where STKTRND2_ITMCD like ? and STKTRND1_BSGRP=?
        GROUP BY STKTRND1_DOCNO,MBSG_DESC,MBSG_BSGRP,ISUDT) V1
        left join
                (SELECT RCV_DONO,COUNT(*) TTLITEMIN,MIN(RCV_HSCD) RCV_HSCD, MIN(RCV_BM) RCV_BM
                , MIN(RCV_PPN) RCV_PPN, MIN(RCV_PPH) RCV_PPH,MAX(RCV_SUPCD) RCV_SUPCD FROM RCV_TBL b        
                GROUP BY RCV_DONO) v2 ON v1.STKTRND1_DOCNO=v2.RCV_DONO
		LEFT JOIN (
            SELECT MSUP_SUPCD,MAX(MSUP_SUPNM) MSUP_SUPNM FROM v_supplier_customer_union GROUP BY MSUP_SUPCD
        ) VSUP ON isnull(RCV_SUPCD,'')=MSUP_SUPCD";
        $query = $this->db->query($qry, ['%'.$pitem.'%',$psup]);
		return $query->result_array();
    }

    public function SelectDObyItem_split($pitem){
        $qry = "SELECT RCV_DONO,MSUP_SUPNM FROM RCV_TBL b INNER JOIN MSUP_TBL a
        on RCV_SUPCD=MSUP_SUPCD
        WHERE RCV_ITMCD like ? AND RCV_DONO NOT LIKE '%-RNK'
        GROUP BY RCV_DONO,MSUP_SUPNM";
        $query = $this->db->query($qry, array("%$pitem%"));
		return $query->result_array();
    }

    public function select_rank($pdo){
        $qry = "SELECT RCV_PO, RTRIM(RCV_ITMCD) RCV_ITMCD,PGRELED_ITMCD,PGRELED_GRADE,PGRELED_GRDQT,RCV_RCVDATE,RCV_SUPCD,RCV_RPNO,RCV_WH,PGRELED_GRLNO,RCV_BSGRP,RCV_BCDATE,RCV_RPDATE,RCV_BCTYPE,RCV_BCNO FROM RCV_TBL 
        INNER JOIN XPGRELED_VIEW ON RCV_DONO=PGRELED_SUPNO AND RCV_GRLNO=PGRELED_GRLNO
        WHERE RCV_DONO=?
        ORDER BY RCV_ITMCD ASC , PGRELED_ITMCD";
        $query = $this->db->query($qry, array($pdo));
        return $query->result_array();
    }

    public function MGSelectDODetail($pwhere){ 
        $this->db->from('XPGRN_VIEW a');
        $this->db->select("PGRN_LOCCD,PGRN_POUOM,MITM_ITMD1,PGRN_ITMCD,PGRN_SUPCR,MSUP_SUPNM,PGRN_SUPCD,PGRN_RCVDT,PGRN_PONO,PGRN_PRPRC,SUM(PGRN_ROKQT) PGRN_ROKQT,PGRN_GRLNO,SUM(PGRN_AMT) PGRN_AMT, ISNULL(PNGR_INVNO,'') PNGR_INVNO");
        $this->db->join('MITM_TBL b', 'a.PGRN_ITMCD=b.MITM_ITMCD', 'left');
        $this->db->join('XPNGR c', 'PGRN_SUPNO=PNGR_SUPNO AND PGRN_BSGRP=PNGR_BSGRP', 'left');
        $this->db->where($pwhere);
        $this->db->group_by("PGRN_LOCCD,PGRN_POUOM,MITM_ITMD1,PGRN_ITMCD,PGRN_SUPCR,MSUP_SUPNM,PGRN_SUPCD,PGRN_RCVDT,PGRN_PONO,PGRN_PRPRC,PGRN_GRLNO,PNGR_INVNO");
        $this->db->order_by('PGRN_ITMCD,PGRN_GRLNO');
        $query = $this->db->get();
		return $query->result_array();
    }

    public function MGSelectDODetail_formigration(){
        //MEGA SOURCE
        // $qry = "SELECT RTRIM(PGRN_LOCCD) PGRN_LOCCD,RTRIM(PGRN_ITMCD) PGRN_ITMCD,PGRN_SUPCR,RTRIM(PGRN_SUPCD) PGRN_SUPCD,PGRN_RCVDT,PGRN_PONO
        // ,PGRN_PRPRC,SUM(PGRN_ROKQT) PGRN_ROKQT,PGRN_GRLNO,SUM(PGRN_AMT) PGRN_AMT,RTRIM(PGRN_SUPNO) PGRN_SUPNO
		// ,MIGAJU,MIGNOPEN,MIGTGLPEN,PGRN_BSGRP
        //     FROM XPGRN_VIEW
		// 	RIGHT JOIN (SELECT MIGDO,MIGAJU,MIGNOPEN,MIGTGLPEN FROM MIGDO
		// 					GROUP BY MIGDO,MIGAJU,MIGNOPEN,MIGTGLPEN) VMIG ON PGRN_SUPNO=MIGDO
		// 	WHERE MIGDO IS NOT NULL
        //     GROUP BY PGRN_LOCCD,PGRN_POUOM,PGRN_ITMCD,PGRN_SUPCR,MSUP_SUPNM,PGRN_SUPCD,PGRN_RCVDT,PGRN_PONO,PGRN_PRPRC,PGRN_GRLNO,PGRN_SUPNO,MIGAJU,MIGNOPEN,MIGTGLPEN,PGRN_BSGRP";
        //ICS SOURCE
        $qry = "SELECT isnull(ITMLOC_LOCG, 'ARWH2') PGRN_LOCCD
        ,RTRIM(item_code) PGRN_ITMCD
        ,CASE 
            WHEN vendor_code = 'STX'
                THEN 'SME006U'
            WHEN vendor_code = 'NITTO'
                THEN 'NAI045U'
            WHEN vendor_code = 'SPACE'
                THEN 'SPA001U'
            WHEN vendor_code = 'HARMONICS'
                THEN 'HAR002U'
            WHEN vendor_code = 'KYOSHA'
                THEN 'KYO003U'
            WHEN vendor_code = 'TAMANO'
                THEN 'TAM001U'
            WHEN vendor_code = 'TOTOKU'
                THEN 'TOT001U'
            ELSE vendor_code
            END PGRN_SUPCD
        ,trans_date PGRN_RCVDT
        ,po_no PGRN_PONO
        ,net_price PGRN_PRPRC
        ,SUM(rcv_qty) PGRN_ROKQT
        ,seq_num PGRN_GRLNO
        ,SUM(rcv_qty) * net_price PGRN_AMT
        ,RTRIM(delivery_no) PGRN_SUPNO
        ,MIGAJU
        ,MIGNOPEN
        ,MIGTGLPEN
        ,ISNULL(ITMLOC_BG, 'PSI1PPZIEP') PGRN_BSGRP
        ,custom_category
    FROM (
        SELECT po_no
            ,delivery_no
            ,trans_date
            ,custom_no
            ,custom_category
            ,a.custom_doc
            ,a.vendor_code
            ,vendor_name
            ,item_code
            ,net_price
            ,rcv_qty
            ,b.seq_num
        FROM tr_pch_rcv_head a
        LEFT JOIN (
            SELECT trans_no
                ,po_no
                ,seq_num
                ,rcv_qty
                ,net_price
                ,item_code
            FROM tr_pch_rcv_det
            
            UNION
            
            SELECT trans_no
                ,po_no
                ,seq_num
                ,rcv_qty
                ,net_price
                ,item_code
            FROM tr_pch_rcv_det3
            ) b ON a.trans_no = b.trans_no
        LEFT JOIN ms_vendor c ON a.vendor_code = c.vendor_code
        LEFT JOIN (
            SELECT RCV_TBL.RCV_DONO
                ,RCV_ITMCD
            FROM RCV_TBL
            GROUP BY RCV_DONO
                ,RCV_ITMCD
            ) VWMS ON delivery_no = RCV_DONO
            AND item_code = RCV_ITMCD
        WHERE RCV_ITMCD IS NULL
            AND trans_date <= '2021-04-30'
            AND delivery_no != ''
            AND item_code IS NOT NULL
        ) VICS
    RIGHT JOIN (
        SELECT MIGDO
            ,MIGAJU
            ,MIGNOPEN
            ,MIGTGLPEN
        FROM MIGDO
        GROUP BY MIGDO
            ,MIGAJU
            ,MIGNOPEN
            ,MIGTGLPEN
        ) VMIG ON delivery_no = MIGDO
    LEFT JOIN (
        SELECT ITMLOC_ITM
            ,MAX(ITMLOC_LOCG) ITMLOC_LOCG
            ,COUNT(*) TTL
            ,MAX(ITMLOC_BG) ITMLOC_BG
        FROM ITMLOC_TBL
        GROUP BY ITMLOC_ITM
        ) VLOC ON item_code = ITMLOC_ITM
    WHERE MIGDO IS NOT NULL
        AND vendor_code != 'IEI'
    GROUP BY item_code
        ,trans_date
        ,po_no
        ,net_price
        ,seq_num
        ,vendor_code
        ,custom_category
        ,delivery_no
        ,MIGAJU
        ,MIGNOPEN
        ,MIGTGLPEN
        ,ITMLOC_LOCG
        ,ITMLOC_BG
    ";
        $resq = $this->db->query($qry);
		return $resq->result_array();
    }

    public function DOList_formigration(){
        $qry = "SELECT MIGDO,MIGAJU,MIGNOPEN,MIGTGLPEN,SUBSTRING(MIGAJU,5,2) MIGBCTYPE,CONCAT(SUBSTRING(MIGAJU,1,4),'00') KPPBC FROM MIGDO
        GROUP BY MIGDO,MIGAJU,MIGNOPEN,MIGTGLPEN";
        $resq = $this->db->query($qry);
		return $resq->result_array();
    }

    public function MGSelectDODetailReturn($pdo){ 
        // $this->db->from('XSTKTRND1 a');
        // $this->db->select('RTRIM(STKTRND1_LOCCDFR) STKTRND1_LOCCDFR,RTRIM(MITM_STKUOM) MITM_STKUOM,rtrim(MITM_ITMD1) MITM_ITMD1,RTRIM(STKTRND2_ITMCD) STKTRND2_ITMCD,RTRIM(MBSG_CURCD) MBSG_CURCD,RTRIM(MBSG_DESC) MBSG_DESC,
        // RTRIM(MBSG_BSGRP) MBSG_BSGRP,CONVERT(DATE,STKTRND1_ISUDT) ISUDT,STKTRND2_PRICE,SUM(STKTRND2_TRNAM) AMT, SUM(STKTRND2_TRNQT) RETQT,MAX(PLANTRTN) PLANT');
        // $this->db->join('XSTKTRND2 c', 'a.STKTRND1_RQRLSNO=c.STKTRND2_RQRLSNO', 'left');
        // $this->db->join('MITM_TBL b', 'STKTRND2_ITMCD=b.MITM_ITMCD', 'left');
        // $this->db->join('XMBSG_TBL', 'STKTRND1_BSGRP=MBSG_BSGRP', 'left');
        // $this->db->join('(SELECT RETFG_DOCNO, MAX(RETFG_CONSIGN) PLANTRTN FROM RETFG_TBL GROUP BY RETFG_DOCNO) VRTN', 'STKTRND1_DOCNO=RETFG_DOCNO', 'left');
        // $this->db->where('STKTRND1_DOCNO', $pdo);
        // $this->db->group_by("STKTRND1_LOCCDFR,MITM_STKUOM,MITM_ITMD1,STKTRND2_ITMCD,MBSG_CURCD,MBSG_DESC,MBSG_BSGRP,STKTRND1_ISUDT,STKTRND2_PRICE");
        // $this->db->order_by('STKTRND2_ITMCD');
        // $query = $this->db->get();
		// return $query->result_array();
        $this->db->from('XVU_RTN a');
        $this->db->select('RTRIM(STKTRND1_LOCCDFR) STKTRND1_LOCCDFR,RTRIM(MITM_STKUOM) MITM_STKUOM,rtrim(MITM_ITMD1) MITM_ITMD1,RTRIM(STKTRND2_ITMCD) STKTRND2_ITMCD,RTRIM(MBSG_CURCD) MBSG_CURCD
        ,RTRIM(a.MBSG_DESC) MBSG_DESC,
                RTRIM(a.MBSG_BSGRP) MBSG_BSGRP,CONVERT(DATE,a.ISUDT) ISUDT,c.STKTRND2_PRICE,SUM(STKTRND2_TRNAM) AMT, SUM(STKTRND2_TRNQT) RETQT,MAX(PLANTRTN) PLANT');
        $this->db->join('XVU_RTN_D c', 'a.STKTRND1_DOCNO=c.STKTRND1_DOCNO', 'left');
        $this->db->join('MITM_TBL b', 'STKTRND2_ITMCD=b.MITM_ITMCD', 'left');        
        $this->db->join('(SELECT RETFG_DOCNO, MAX(RETFG_CONSIGN) PLANTRTN FROM RETFG_TBL GROUP BY RETFG_DOCNO) VRTN', 'a.STKTRND1_DOCNO=RETFG_DOCNO', 'left');
        $this->db->where('a.STKTRND1_DOCNO', $pdo);
        $this->db->group_by("STKTRND1_LOCCDFR,MITM_STKUOM,MITM_ITMD1,STKTRND2_ITMCD,MBSG_CURCD,a.MBSG_DESC,a.MBSG_BSGRP,a.ISUDT,STKTRND2_PRICE");
        $this->db->order_by('STKTRND2_ITMCD');
        $query = $this->db->get();
		return $query->result_array();

    }

    public function MGSelectDODetail_cols($pdo, $pcols){
        $this->db->select($pcols);
        $this->db->from('XPGRN_VIEW a');        
        $this->db->join('MITM_TBL b', 'a.PGRN_ITMCD=b.MITM_ITMCD', 'left');
        $this->db->where('PGRN_SUPNO', $pdo);
        $this->db->group_by("PGRN_LOCCD,PGRN_POUOM,MITM_ITMD1,PGRN_ITMCD,PGRN_SUPCR,MSUP_SUPNM,PGRN_SUPCD,PGRN_RCVDT,PGRN_PONO,PGRN_PRPRC,PGRN_GRLNO");
        $this->db->order_by('PGRN_ITMCD,PGRN_GRLNO');
        $query = $this->db->get();
		return $query->result_array();
    }

    public function SelectDODetail_split($pdo){ 
        $this->db->from('RCV_TBL a'); 
        $this->db->select("MITM_STKUOM,MITM_ITMD1,RCV_ITMCD,MSUP_SUPCR,RCV_RCVDATE,RCV_PO,sum(RCV_QTY) RCV_QTY");
        $this->db->join('MITM_TBL b', 'a.RCV_ITMCD=b.MITM_ITMCD', 'left');
        $this->db->join('MSUP_TBL', 'RCV_SUPCD=MSUP_SUPCD', 'left');
        $this->db->where('RCV_DONO', $pdo);
        $this->db->group_by("MITM_STKUOM,MITM_ITMD1,RCV_ITMCD,MSUP_SUPCR,RCV_RCVDATE,RCV_PO");
        $this->db->order_by('RCV_ITMCD asc');
        $query = $this->db->get();
		return $query->result_array();
    }

    public function selectbypar($par){
        $this->db->from($this->TABLENAME);
        $this->db->where($par);  
        $query = $this->db->get();
		return $query->result_array();
    }

    public function selectprog_scan($pdo){
        $qry = "exec xsp_progress_scndo '$pdo'";
		$resq = $this->db->query($qry);
		return $resq->result_array();
    }

    public function selectprog_save($pdo){
        $qry = "exec xsp_progress_scndosave '$pdo'";
		$resq = $this->db->query($qry);
		return $resq->result_array();
    }
    public function select_sp_searchdo_all($pdo){
        $qry = "exec sp_searchdo_all ?";
		$resq = $this->db->query($qry, array($pdo));
		return $resq->result_array();
    }
    public function select_sp_searchdo_open($pdo){
        $qry = "exec sp_searchdo_open ?";
		$resq = $this->db->query($qry, array($pdo));
		return $resq->result_array();
    }

    public function select_sp_report_inc_pab($pdoctype, $ptpbtype, $pitmcd, $psup, $pdate0, $pdate1, $pnoaju, $ptujuan){
        $qry = "sp_report_inc_pab ?, ?, ? , ? , ?, ? , ? , ?";
		$resq = $this->db->query($qry, [$pdoctype, $ptpbtype, $pitmcd,$psup, $pdate0, $pdate1 , $pnoaju,$ptujuan]);
		return $resq->result_array();
    }

    public function selectDO($pdo){       
        $qry = "select v1.*, CONVERT(bigint,coalesce(LBLTTL,0)) LBLTTL from
        (SELECT RCV_DONO,RCV_ITMCD,MITM_SPTNO,SUM(RCV_QTY) RCV_QTY FROM RCV_TBL a INNER JOIN MITM_TBL b on a.RCV_ITMCD=b.MITM_ITMCD
        WHERE RCV_DONO LIKE '%$pdo%'
        GROUP BY RCV_DONO,RCV_ITMCD,MITM_SPTNO)
         v1
                LEFT JOIN
                ( select SER_DOC,SER_ITMID,SUM(SER_QTY) LBLTTL from SER_TBL x WHERE SER_DOC LIKE '%$pdo%'
                GROUP BY SER_DOC,SER_ITMID
                ) v2 on v1.RCV_DONO=v2.SER_DOC AND v1.RCV_ITMCD=v2.SER_ITMID";
		$query = $this->db->query($qry);
		return $query->result_array();
    }

    public function selectDOunscanbutHasBC(){        
        $qry = "select
                    RCV_DONO RPSTOCK_DOC
                from
                    (
                        select
                            RCV_DONO
                        , SUM(RCV_QTY) RCVQTY
                        from
                            RCV_TBL
                        where
                            RCV_RPDATE<='2021-09-03'
                        group by
                            RCV_DONO
                            having sum(RCV_QTY) >0
                    )
                    v1
                    left join
                        (
                            SELECT
                                RCVSCN_DONO
                            , SUM(RCVSCN_QTY) RCVSCNQTY
                            FROM
                                RCVSCN_TBL
                            GROUP BY
                                RCVSCN_DONO
                        )
                        V2
                        on
                            RCV_DONO= RCVSCN_DONO
                where
                    RCVQTY    !=ISNULL(RCVSCNQTY,0)
                    and RCVQTY!=0";
		$query = $this->db->query($qry);
		return $query->result_array();
    }

    public function select_bcstock($pdate){
        $qry = "SELECT RPSTOCK_DOC,RPSTOCK_BCDATE,RTRIM(RPSTOCK_ITMNUM) RPSTOCK_ITMNUM,SUM(RPSTOCK_QTY) INCSTOCK,SUM(RPSTOCK_QTY) CURRENTSTOCK FROM ZRPSAL_BCSTOCK 
        WHERE RPSTOCK_BCDATE<= ? AND RPSTOCK_TYPE='INC' AND deleted_at is null
        GROUP BY RPSTOCK_DOC,RTRIM(RPSTOCK_ITMNUM),RPSTOCK_BCDATE
        ORDER BY RPSTOCK_BCDATE DESC,RPSTOCK_DOC DESC";
        $query = $this->db->query($qry, [$pdate]);
		return $query->result_array();
    }

    public function select_do_formigration($pitem, $pdate){
        $qry = "SELECT V1.*
        FROM (
            SELECT RTRIM(PGRN_SUPNO) PGRN_SUPNO
                ,RTRIM(MSUP_SUPNM) MSUP_SUPNM
                ,RTRIM(PGRN_ITMCD) PGRN_ITMCD
                ,convert(DATE, max(PGRN_RCVDT)) PGRN_RCVDT
                ,sum(PGRN_ROKQT) TTLRCV  
                ,sum(PGRN_ROKQT) CURRENTSTOCK              
            FROM XPGRN_VIEW a
            WHERE PGRN_ITMCD IN ($pitem)
                AND PGRN_SUPCD NOT LIKE '%dummy%'               
                AND convert(DATE, PGRN_RCVDT) <= ?
            GROUP BY PGRN_SUPNO
                ,MSUP_SUPNM
                ,PGRN_ITMCD
                ,PGRN_SUPCD
            HAVING sum(PGRN_ROKQT) > 0
            ) v1
        LEFT JOIN (
            SELECT RCV_DONO
                ,COUNT(*) TTLITEMIN
                ,MIN(RCV_HSCD) RCV_HSCD
                ,MIN(RCV_BM) RCV_BM
                ,MIN(RCV_PPN) RCV_PPN
                ,MIN(RCV_PPH) RCV_PPH
            FROM RCV_TBL b
            GROUP BY RCV_DONO
            ) v2 ON v1.PGRN_SUPNO = v2.RCV_DONO
        WHERE isnull(TTLITEMIN, 0) = 0
        ORDER BY PGRN_ITMCD
            ,v1.PGRN_RCVDT DESC ,PGRN_SUPNO DESC
        ";
        $query = $this->db->query($qry, [$pdate]);
		return $query->result_array();
    }

    public function select_customer($pwhere) {       
        $this->db->select("V1.*,RTRIM(MCUS_CUSNM) MCUS_CUSNM,RTRIM(MCUS_ADDR1) MCUS_ADDR1,RTRIM(MCUS_ABBRV) MCUS_ABBRV, RTRIM(MCUS_CURCD) MCUS_CURCD");
        $this->db->from("(SELECT RTRIM(PGRN_BSGRP) PGRN_BSGRP,RTRIM(PGRN_CUSCD) PGRN_CUSCD FROM XPGRN_VIEW WHERE PGRN_CUSCD!=''
        GROUP BY PGRN_BSGRP,PGRN_CUSCD) V1");
        $this->db->join("XMCUS", "PGRN_CUSCD=MCUS_CUSCD");
        $this->db->like($pwhere);
        $this->db->order_by("MCUS_CUSNM");
        $query = $this->db->get();
		return $query->result_array();
    }

    public function select_ics($aItem_code, $period) {
        $this->db->from("tr_pch_rcv_head a");
        $this->db->join("tr_pch_rcv_det b", "a.trans_no=b.trans_no");
        $this->db->join("ms_vendor c", "a.vendor_code=c.vendor_code");
        $this->db->join("(select RCV_TBL.RCV_DONO FROM RCV_TBL GROUP BY RCV_DONO) VWMS", "delivery_no=RCV_DONO", "left");
        $this->db->where_in("item_code", $aItem_code)->where('trans_date <=', $period);
        $this->db->where("RCV_DONO is null", null, false);
        $this->db->order_by("trans_date DESC");
        $this->db->select("trans_date,delivery_no, item_code RPSTOCK_ITMNUM,rcv_qty,rcv_qty CURRENTSTOCK");
        $query = $this->db->get();
		return $query->result_array();
    }
    public function select_ics3($aItem_code, $period) {
        $this->db->from("tr_pch_rcv_head a");
        $this->db->join("tr_pch_rcv_det3 b", "a.trans_no=b.trans_no");
        $this->db->join("ms_vendor c", "a.vendor_code=c.vendor_code");
        $this->db->join("(select RCV_TBL.RCV_DONO FROM RCV_TBL GROUP BY RCV_DONO) VWMS", "delivery_no=RCV_DONO", "left");
        $this->db->where_in("item_code", $aItem_code)->where('trans_date <=', $period)->where_not_in('delivery_no', ['']);
        $this->db->where("RCV_DONO is null", null, false);
        $this->db->order_by("trans_date DESC");
        $this->db->select("trans_date,delivery_no, item_code RPSTOCK_ITMNUM,rcv_qty,rcv_qty CURRENTSTOCK");
        $query = $this->db->get();
		return $query->result_array();
    }

    public function select_lastLinePerDO($pDO) {
        $this->db->from($this->TABLENAME);
        $this->db->where('RCV_DONO', $pDO);
        $this->db->select("MAX(CONVERT(INT,RCV_GRLNO)) LASTLINE");
        $query = $this->db->get();
        return $query->num_rows()>0 ? $query->row()->LASTLINE : 0;
    }

    public function select_ajudo_byAJU_in($paju)
	{
        $this->db->from($this->TABLENAME.' a');
        $this->db->select("RCV_DONO,RCV_RPNO,RCV_ITMCD");
        $this->db->where_in('RCV_RPNO', $paju)->where('RCV_HSCD', NULL);
        $this->db->group_by("RCV_DONO,RCV_RPNO,RCV_ITMCD");
        $this->db->order_by("RCV_RPNO,RCV_ITMCD");
		$query = $this->db->get();
		return $query->result();
    }

    public function update_itemcustoms_prop_by_itemcd($pitmcd, $phscode) {
        $qry = "UPDATE RCV_TBL SET RCV_HSCD=? WHERE RCV_ITMCD=? AND ISNULL(RCV_HSCD,'')=''";
        $this->db->query($qry, [$phscode, $pitmcd]);
		return $this->db->affected_rows();
    }

    public function select_hscode($pitem)
	{
        $this->db->from($this->TABLENAME.' a');
        $this->db->select("RTRIM(RCV_ITMCD) RCV_ITMCD, MAX(RCV_HSCD) RCV_HSCD");
        $this->db->where_in('RCV_ITMCD', $pitem);
        $this->db->group_by("RCV_ITMCD");        
		$query = $this->db->get();
		return $query->result_array();
    }

    public function select_balanceEXBC($pitems = []) {
        $this->db->from('VBALEXBC');
        $this->db->where_in('ITMNUM', $pitems);
        $this->db->order_by("RCV_BCDATE,ITMNUM");
		$query = $this->db->get();
		return $query->result_array();
    }
    public function select_balanceEXBC_byPK($pitems = []) {
        $this->db->from('VBALEXBC');
        $this->db->where_in('RCV_CONA', $pitems);
        $this->db->order_by("RCV_BCDATE,ITMNUM");
		$query = $this->db->get();
		return $query->result_array();
    }
    public function select_balanceEXBC_like($pitems = [], $plike = []) {
        $this->db->from('VBALEXBC');
        $this->db->where_in('ITMNUM', $pitems)->like($plike)->where("PRICE IS NOT NULL", NULL);
        $this->db->order_by("RCV_BCDATE,ITMNUM,RPSTOCK_NOAJU");
		$query = $this->db->get();
		return $query->result_array();
    }
    
    public function select_balanceEXBC_fromBook($pitems = [], $plike = []) {
        $this->db->select("rtrim(RPSTOCK_ITMNUM) ITMNUM,abs(RPSTOCK_QTY) STK,RPSTOCK_NOAJU,RPSTOCK_BCNUM,RPSTOCK_DOC,RPSTOCK_BCDATE RCV_BCDATE,RCV_PRPRC PRICE");
        $this->db->from('ZRPSAL_BCSTOCK');
        $this->db->join("(SELECT RCV_DONO,RCV_RPNO,RCV_ITMCD,RCV_PRPRC,RCV_BCNO FROM RCV_TBL GROUP BY RCV_DONO,RCV_RPNO,RCV_ITMCD,RCV_PRPRC,RCV_BCNO) VX"
            ,"RPSTOCK_ITMNUM=RCV_ITMCD AND RPSTOCK_NOAJU=RCV_RPNO AND RPSTOCK_BCNUM=RCV_BCNO AND RPSTOCK_DOC=RCV_DONO"            
            ,"left");
        $this->db->where_in('RPSTOCK_ITMNUM', $pitems)->like($plike);
        $this->db->order_by("RPSTOCK_BCDATE,ITMNUM,RPSTOCK_NOAJU");
		$query = $this->db->get();
		return $query->result_array();
    }

    public function select_balanceEXBC_fromSCRBook($pdocs){
        $this->db->select("rtrim(RPSTOCK_ITMNUM) ITMNUM,abs(sum(RPSTOCK_QTY)) STK");
        $this->db->from('ZRPSAL_BCSTOCK');
        $this->db->where_in('RPSTOCK_REMARK', $pdocs);
        $this->db->group_by("RPSTOCK_ITMNUM");
        $this->db->order_by("RPSTOCK_ITMNUM");
		$query = $this->db->get();
		return $query->result_array();
    }
    public function select_balanceEXBC_fromSCRBook_detail($pdocs){
        $this->db->select("RPSTOCK_BCTYPE,RPSTOCK_BCNUM,RPSTOCK_BCDATE,RPSTOCK_DOC,RPSTOCK_NOAJU,rtrim(RPSTOCK_ITMNUM) ITMNUM,abs(sum(RPSTOCK_QTY)) STK,RCV_PRPRC");
        $this->db->from('ZRPSAL_BCSTOCK');
        $this->db->join('(SELECT RCV_ITMCD,RCV_DONO,max(RCV_PRPRC) RCV_PRPRC FROM RCV_TBL GROUP BY RCV_ITMCD,RCV_DONO) VRCV',"RPSTOCK_DOC=RCV_DONO AND RPSTOCK_ITMNUM=RCV_ITMCD","LEFT");
        $this->db->where_in('RPSTOCK_REMARK', $pdocs);
        $this->db->group_by("RPSTOCK_BCTYPE,RPSTOCK_BCNUM,RPSTOCK_BCDATE,RPSTOCK_DOC,RPSTOCK_NOAJU,RPSTOCK_ITMNUM,RCV_PRPRC");
        $this->db->order_by("RPSTOCK_ITMNUM");
		$query = $this->db->get();
		return $query->result_array();
    }

    public function select_for_rmrtn_bytxid($pdoc){
        $qry = "select RCV_DONO,RCV_RPNO,RTRIM(RCV_ITMCD) RCV_ITMCD,RCV_ZNOURUT,RCV_QTY,RCV_QTY DOCQTY,RCV_BCDATE,RCV_BCTYPE,RCV_KPPBC,RCV_PRPRC,RCV_HSCD,RCV_BM,MSUP_SUPCR from DLVRMDOC_TBL 
        left join RCV_TBL ON DLVRMDOC_DO=RCV_DONO AND DLVRMDOC_AJU=RCV_RPNO AND DLVRMDOC_ITMID=RCV_ITMCD
        LEFT JOIN 
		(
            SELECT MSUP_SUPCD,MAX(MSUP_SUPNM) MSUP_SUPNM, MAX(MSUP_SUPCR) MSUP_SUPCR FROM v_supplier_customer_union
            GROUP BY MSUP_SUPCD
		) vc ON RCV_SUPCD=MSUP_SUPCD
        where DLVRMDOC_TXID=?
        GROUP BY RCV_DONO,RCV_RPNO,RCV_ITMCD,RCV_ZNOURUT,RCV_QTY,RCV_BCDATE,RCV_BCTYPE,RCV_KPPBC,RCV_PRPRC,RCV_HSCD,RCV_BM,MSUP_SUPCR";
        $query = $this->db->query($qry, [$pdoc]);
		return $query->result_array();
    }    

    public function select_itemtotal($pdoc){
        $qry = "SELECT RTRIM(RCV_ITMCD) RCV_ITMCD,SUM(RCV_QTY) RCV_QTY FROM RCV_TBL LEFT JOIN MITM_TBL ON RCV_ITMCD=MITM_ITMCD WHERE RCV_DONO=? AND MITM_MODEL!='1'
        GROUP BY RCV_ITMCD";
        $query = $this->db->query($qry, [$pdoc]);
        return $query->result_array();
    }

    public function select_ostso($pwhere){
        $this->db->from("(select RCV_DONO,RCV_BCDATE,RCV_BSGRP,RCV_SUPCD from RCV_TBL left join MITM_TBL on RCV_ITMCD=MITM_ITMCD
        where isnull(RCV_INVNOACT,'')='' and MITM_MODEL='6'
        group by RCV_DONO,RCV_BCDATE,RCV_BSGRP,RCV_SUPCD) v");
        $this->db->where($pwhere);    
		$query = $this->db->get();
		return $query->result_array();
    }
    public function select_pltinv($pwhere){
        $this->db->from("(select RCV_DONO,RCV_BCDATE,RCV_BSGRP,RCV_SUPCD,RCV_INVNOACT from RCV_TBL left join MITM_TBL on RCV_ITMCD=MITM_ITMCD
        where isnull(RCV_INVNOACT,'')!='' and MITM_MODEL='6'
        group by RCV_DONO,RCV_BCDATE,RCV_BSGRP,RCV_SUPCD,RCV_INVNOACT) v");
        $this->db->where($pwhere);    
		$query = $this->db->get();
		return $query->result_array();
    }

    public function qry($query){
        $query = $this->db->query($query);        
        return $query->result_array();
    }

    public function select_deliv_invo($pdate1, $pdate2, $bigrup) {
		$qry = "SELECT RTRIM(PGRN_SUPCD) PGRN_SUPCD,RTRIM(XPGRN_VIEW.MSUP_SUPNM) MSUP_SUPNM,RTRIM(PGRN_GRLNO) PGRN_GRLNO,RTRIM(PGRN_SUPNO) PGRN_SUPNO,PNGR_INVNO,RTRIM(PGRN_PONO) PGRN_PONO,RTRIM(PGRN_ITMCD) PGRN_ITMCD,
        RTRIM(MITM_SPTNO) MITM_SPTNO, RTRIM(MITM_ITMD1) MITM_ITMD1,PGRN_RCVDT,PGRN_ROKQT,MITM_STKUOM,PGRN_CURCD,PGRN_PRPRC,PGRN_AMT,PGRN_LOCCD
        FROM XPGRN_VIEW LEFT JOIN XPNGR ON PGRN_SUPNO=PNGR_SUPNO AND PGRN_BSGRP=PNGR_BSGRP
        LEFT JOIN XMITM_V ON PGRN_ITMCD=MITM_ITMCD
        WHERE PGRN_SUPCD NOT LIKE '%DUMMY%' AND PGRN_BSGRP in ($bigrup) AND PGRN_RCVDT BETWEEN ? AND ?
        ORDER BY PGRN_SUPCD";
		$query = $this->db->query($qry, [$pdate1, $pdate2]);
		return $query->result_array();
	}
    
}