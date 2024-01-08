<?php

class SCR_mod extends CI_Model {
	private $TABLENAME = "SCR_TBL";
	public function __construct()
    {
        $this->load->database();
    }	

	public function insert($data)
    {        
        $this->db->insert($this->TABLENAME,$data);
        return $this->db->affected_rows();
    }
	public function insertser($data)
    {        
        $this->db->insert("SCRSER_TBL",$data);
        return $this->db->affected_rows();
    }
    public function insertb($data)
    {        
        $this->db->insert_batch($this->TABLENAME,$data);
        return $this->db->affected_rows();
    }
	public function check_Primary($data)
    {        
        return $this->db->get_where($this->TABLENAME,$data)->num_rows();
	}
	
	public function updatebyId($pdata, $pkeys)
    {        
        $this->db->where($pkeys);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
    }
    public function lastserialid(){       
        $qry = "select TOP 1 substring(SCR_DOC, 12, 3) lser from SCR_TBL
        WHERE convert(date, SCR_LUPDT) = convert(date,GETDATE())
        ORDER BY convert(bigint,SUBSTRING(SCR_DOC,12,3)) desc";
        $query =  $this->db->query($qry);        
        if ($query->num_rows()>0){
            $ret = $query->row();
            return $ret->lser;
        } else {
            return '0';
        }
    }

    public function lastserialidser(){       
        $qry = "select TOP 1 substring(SCRSER_DOC, 13, 3) lser from SCRSER_TBL
        WHERE convert(date, SCRSER_LUPDT) = convert(date,GETDATE())
        ORDER BY convert(bigint,SUBSTRING(SCRSER_DOC,13,3)) desc";
        $query =  $this->db->query($qry);        
        if ($query->num_rows()>0){
            $ret = $query->row();
            return $ret->lser;
        } else {
            return '0';
        }
    }

    public function selectAllg_byVAR($plike)
	{
        $this->db->limit(10);
        $this->db->select("SCR_DOC,SCR_DT,COUNT(*) TTLITEM,MAX(SCR_REMARK) REMARK");
        $this->db->from($this->TABLENAME);        
        $this->db->like($plike);
        $this->db->group_by("SCR_DOC,SCR_DT");
		$query = $this->db->get();
		return $query->result_array();
    }
    public function selectserAllg_byVAR($plike)
	{
        $this->db->limit(100);
        $this->db->select("SCRSER_DOC,SCRSER_DT,COUNT(*) TTLITEM,MAX(SCRSER_REMARK) REMARK");
        $this->db->from("SCRSER_TBL");
        $this->db->join("PNDSCN_TBL", "SCRSER_SER=PNDSCN_SER","left");
        $this->db->like($plike);
        $this->db->group_by("SCRSER_DOC,SCRSER_DT");
        $this->db->order_by("SCRSER_DOC DESC");
		$query = $this->db->get();
		return $query->result_array();
    }
    public function selectvspend($pdocpen,$pitem, $plot)
	{        
        $this->db->select("(PND_QTY-SCR_QTY) OSQTY");
        $this->db->from("(SELECT SCR_REFFDOC,SCR_ITMLOT,SCR_ITMCD,SUM(SCR_QTY) SCR_QTY FROM SCR_TBL GROUP BY SCR_REFFDOC,SCR_ITMLOT,SCR_ITMCD ) a");
        $this->db->join("PND_TBL b","a.SCR_ITMCD=b.PND_ITMCD AND a.SCR_REFFDOC=b.PND_DOC AND a.SCR_ITMLOT=b.PND_ITMLOT");
        $this->db->where("SCR_REFFDOC", $pdocpen)->where("SCR_ITMCD", $pitem)->where("SCR_ITMLOT", $plot );        
		$query = $this->db->get();
		return $query->result_array();
    }
    public function selectservspend($pdocpen,$pser)
	{        
        $this->db->select("(PNDSER_QTY-SCRSER_QTY) OSQTY");
        $this->db->from("(SELECT SCRSER_REFFDOC,SCRSER_SER,SUM(SCRSER_QTY) SCRSER_QTY FROM SCRSER_TBL GROUP BY SCRSER_REFFDOC,SCRSER_SER) a");
        $this->db->join("PNDSER_TBL b","a.SCRSER_SER=b.PNDSER_SER AND a.SCRSER_REFFDOC=b.PNDSER_DOC");
        $this->db->where("SCRSER_REFFDOC", $pdocpen)->where("SCRSER_SER", $pser);
		$query = $this->db->get();
		return $query->result_array();
    }
    public function selectvspend_edit($pdoc, $pdocpen,$pitem, $plot)
	{        
        $this->db->select("(PND_QTY-SCR_QTY) OSQTY,SCR_QTY, PND_QTY");
        $this->db->from("(SELECT SCR_REFFDOC,SCR_ITMLOT,SCR_ITMCD,SUM(SCR_QTY) SCR_QTY FROM SCR_TBL WHERE SCR_DOC!='$pdoc' GROUP BY SCR_REFFDOC,SCR_ITMLOT,SCR_ITMCD ) a");
        $this->db->join("PND_TBL b","a.SCR_ITMCD=b.PND_ITMCD AND a.SCR_REFFDOC=b.PND_DOC AND a.SCR_ITMLOT=b.PND_ITMLOT");
        $this->db->where("SCR_REFFDOC", $pdocpen)->where("SCR_ITMCD", $pitem)->where("SCR_ITMLOT", $plot );        
		$query = $this->db->get();
		return $query->result_array();
    }

    public function selectAll_by($pwhere)
	{		   
        $this->db->select("a.*,MITM_ITMD1,ITMLOC_LOC,PND_QTY,MSTEMP_FNM");
        $this->db->from($this->TABLENAME." a");        
        $this->db->join('MITM_TBL b', 'a.SCR_ITMCD=b.MITM_ITMCD');
        $this->db->join('ITMLOC_TBL c', 'a.SCR_ITMCD=c.ITMLOC_ITM','LEFT');
        $this->db->join('PND_TBL d', 'a.SCR_ITMCD=d.PND_ITMCD AND a.SCR_REFFDOC=d.PND_DOC AND a.SCR_ITMLOT=d.PND_ITMLOT');
        $this->db->join('MSTEMP_TBL e', 'SCR_USRID=MSTEMP_ID','LEFT');
        $this->db->where($pwhere);
		$query = $this->db->get();
		return $query->result_array();
    }
   

    public function selectscan_balancingv2($pdo){        
        $qry = "SELECT v1.*,COALESCE(ITHQTY,0) ITHQTY FROM
        (SELECT SCR_ITMCD, SCR_QTY, COALESCE(SUM(SCRSCN_QTY),0) SCAN_QTY, max(SCRSCN_LUPDT) LTSSCANTIME 
		FROM SCR_TBL a 
                LEFT JOIN SCRSCN_TBL b ON a.SCR_DOC=b.SCRSCN_DOC and a.SCR_ITMCD=b.SCRSCN_ITMCD                
                WHERE SCR_DOC='".$pdo."'  
                GROUP BY SCR_DOC, SCR_ITMCD,SCR_QTY
                ) v1
        LEFT JOIN (
        SELECT ITH_ITMCD,COALESCE(sum(ITH_QTY),0) ITHQTY FROM ITH_TBL 
        WHERE ITH_DOC='".$pdo."' AND ITH_FORM='INC-SCR-RM'
        GROUP BY ITH_DOC, ITH_ITMCD) v2 ON v1.SCR_ITMCD=v2.ITH_ITMCD";
        $query = $this->db->query($qry);
		return $query->result_array();
    }

    public function selectprog_scan($pdo){
        $qry = "exec xsp_progress_scnscr '$pdo'";
		$resq = $this->db->query($qry);
		return $resq->result_array();
    }

    public function selectprog_save($pdo){
        $qry = "exec xsp_progress_scnscrsave '$pdo'";
		$resq = $this->db->query($qry);
		return $resq->result_array();
    }

    public function selectbalancebyDOITEM($pdo, $pitem){
        $qry = "SELECT v1.* FROM
        (SELECT SCR_ITMCD, SCR_QTY, COALESCE(SUM(SCRSCN_QTY),0) SCAN_QTY, max(SCRSCN_LUPDT) LTSSCANTIME 
		FROM (select SCR_DOC,SCR_ITMCD,SUM(SCR_QTY) SCR_QTY FROM SCR_TBL
		GROUP BY SCR_DOC,SCR_ITMCD) a 
                LEFT JOIN SCRSCN_TBL b ON a.SCR_DOC=b.SCRSCN_DOC and a.SCR_ITMCD=b.SCRSCN_ITMCD                
                WHERE SCR_DOC='$pdo' AND SCR_ITMCD='$pitem'
                GROUP BY SCR_DOC, SCR_ITMCD,SCR_QTY
                ) v1 ";
        $query = $this->db->query($qry);
		return $query->result_array();
    }

    public function selectscan_balancing($pwhere){        
        $this->db->from($this->TABLENAME.' a');
        $this->db->select("SCR_ITMCD, SCR_QTY, COALESCE(SUM(SCRSCN_QTY),0) SCAN_QTY, max(SCRSCN_LUPDT) LTSSCANTIME");
        $this->db->join('SCRSCN_TBL b', "a.SCR_DOC=b.SCRSCN_DOC and a.SCR_ITMCD=b.SCRSCN_ITMCD", 'LEFT');         
        $this->db->where($pwhere);
        $this->db->group_by('SCR_DOC, SCR_ITMCD,SCR_QTY');
        $this->db->having('COALESCE(SUM(SCRSCN_QTY),0)<SCR_QTY');
        $query = $this->db->get();        
		return $query->result_array();
    }

    public function deleteby_filter($pwhere)
    {          
        $this->db->where($pwhere);
        $this->db->delete($this->TABLENAME);
        return $this->db->affected_rows();
    }
    public function deleteserby_filter($pwhere)
    {          
        $this->db->where($pwhere);
        $this->db->delete("SCRSER_TBL");
        return $this->db->affected_rows();
    }

    public function selectserAll_by($pwhere)
	{		   
        $this->db->select("a.*,MITM_SPTNO,ITMLOC_LOC,SER_DOC,SER_ITMID,ITH_LOC,PNDSER_QTY, MSTEMP_FNM");
        $this->db->from('SCRSER_TBL a');
        $this->db->join('SER_TBL d', 'a.SCRSER_SER=SER_ID');
        $this->db->join('MITM_TBL b', 'SER_ITMID=b.MITM_ITMCD');
        $this->db->join('ITMLOC_TBL c', 'MITM_ITMCD=c.ITMLOC_ITM','LEFT');
        $this->db->join('PNDSER_TBL e', 'SCRSER_SER=PNDSER_SER AND SCRSER_REFFDOC=PNDSER_DOC','LEFT');
        $this->db->join('MSTEMP_TBL f', 'SCRSER_USRID=MSTEMP_ID','LEFT');
        $this->db->join("(select v1.ITH_SER,ITH_LOC FROM
        (SELECT ITH_SER, MAX(ITH_LUPDT) LUPDT FROM ITH_TBL WHERE ITH_SER IS NOT NULL 
        GROUP BY ITH_SER) v1 INNER JOIN ITH_TBL a on v1.ITH_SER=a.ITH_SER AND LUPDT=ITH_LUPDT
        WHERE  ITH_FORM NOT IN ('OUT-QA-FG','INC-SHP-FG') and ITH_QTY>0) v1", 'SCRSER_SER=ITH_SER',"LEFT");
        $this->db->where($pwhere);
		$query = $this->db->get();
		return $query->result_array();
    }

    public function select_scrapreport_balance(){
        $this->db->group_by("DOC_NO");
        $this->db->from('ZRPSCRAP_HIST');
        $this->db->where('IS_DONE', 1);
        $this->db->select("DOC_NO,SUM(QTY) SCRQTY");
		$query = $this->db->get();
		return $query->result_array();
    }
}