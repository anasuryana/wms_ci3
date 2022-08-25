<?php

class RLS_mod extends CI_Model {
	private $TABLENAME = "RLSSER_TBL";
	private $TABLENAMERM = "RLS_TBL";
	public function __construct()
    {
        $this->load->database();
    }

	public function insert($data)
    {        
        $this->db->insert($this->TABLENAME,$data);
        return $this->db->affected_rows();
    }
	public function insertRM($data)
    {
        $this->db->insert($this->TABLENAMERM,$data);
        return $this->db->affected_rows();    
    }
	public function insertRMb($data)
    {
        $this->db->insert_batch($this->TABLENAMERM,$data);
        return $this->db->affected_rows();
    }

    public function delete_where($pwhere)
    {          
        $this->db->where($pwhere);
        $this->db->delete($this->TABLENAMERM);
        return $this->db->affected_rows();
    }

    public function checkRM_Primary($data)
    {
        return $this->db->get_where($this->TABLENAMERM,$data)->num_rows();
    }

    public function lastserialidser(){       
        $qry = "select TOP 1 substring(RLSSER_DOC, 13, 3) lser from RLSSER_TBL
        WHERE convert(date, RLSSER_LUPDT) = convert(date,GETDATE())
        ORDER BY convert(bigint,SUBSTRING(RLSSER_DOC,13,3)) desc";
        $query =  $this->db->query($qry);        
        if ($query->num_rows()>0){
            $ret = $query->row();
            return $ret->lser;
        } else {
            return '0';
        }
    }

    public function lastserialid(){       
        $qry = "select TOP 1 substring(RLS_DOC, 12, 3) lser from RLS_TBL
        WHERE convert(date, RLS_LUPDT) = convert(date,GETDATE())
        ORDER BY convert(bigint,SUBSTRING(RLS_DOC,12,3)) desc";
        $query =  $this->db->query($qry);        
        if ($query->num_rows()>0){
            $ret = $query->row();
            return $ret->lser;
        } else {
            return '0';
        }
    }
    public function select_lastline($pdoc){
        $qry = "select MAX(RLS_LINE) lser from RLS_TBL
        WHERE RLS_DOC = ?";
        $query =  $this->db->query($qry, [$pdoc]);
        if ($query->num_rows()>0){
            $ret = $query->row();
            return $ret->lser;
        } else {
            return '0';
        }
    }

    public function updatebyVAR($pdata, $pwhere)
    {        
        $this->db->where($pwhere);
        $this->db->update($this->TABLENAMERM, $pdata);
        return $this->db->affected_rows();
    }

    public function update_ser_where($pdata, $pwhere)
    {        
        $this->db->where($pwhere);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
    }

    public function selectserAllg_byVAR($plike)
	{
        $this->db->limit(10);
        $this->db->select("RLSSER_DOC,RLSSER_DT,COUNT(*) TTLITEM,MAX(RLSSER_REMARK) REMARK");
        $this->db->from($this->TABLENAME);
        $this->db->like($plike);
        $this->db->group_by("RLSSER_DOC,RLSSER_DT");
		$query = $this->db->get();
		return $query->result_array();
    }

    public function selectserAll_by($pwhere)
	{
        $this->db->select("a.*,MITM_SPTNO,ITMLOC_LOC,SER_DOC,SER_ITMID,PNDSER_QTY, MSTEMP_FNM");
        $this->db->from('RLSSER_TBL a');
        $this->db->join('SER_TBL d', 'a.RLSSER_SER=SER_ID');
        $this->db->join('MITM_TBL b', 'SER_ITMID=b.MITM_ITMCD');
        $this->db->join('ITMLOC_TBL c', 'MITM_ITMCD=c.ITMLOC_ITM','LEFT');
        $this->db->join('PNDSER_TBL e', 'RLSSER_REFFSER=PNDSER_SER AND RLSSER_REFFDOC=PNDSER_DOC','LEFT');
        $this->db->join('MSTEMP_TBL f', 'RLSSER_USRID=MSTEMP_ID','LEFT'); 
        $this->db->where($pwhere);
		$query = $this->db->get();
		return $query->result_array();
    }

    public function selectser_by($pwhere)
	{		           
        $this->db->select("RLSSER_DOC,RLSSER_SER,SER_ITMID,SER_DOC,RLSSER_QTY,RLSSER_DT,CONCAT(MSTEMP_FNM,' ', MSTEMP_LNM) FULLNM,MITM_ITMD1");
        $this->db->from('RLSSER_TBL a');
        $this->db->join('SER_TBL d', 'a.RLSSER_SER=SER_ID');
        $this->db->join('MITM_TBL b', 'SER_ITMID=b.MITM_ITMCD'); 
        $this->db->join('MSTEMP_TBL', 'RLSSER_USRID=MSTEMP_ID');
        $this->db->where($pwhere);
		$query = $this->db->get();
		return $query->result_array();
    }

    public function select_rm_balance_by_PNDDoc($pdata){
        $qry = "SELECT VPND.*,CONVERT(INT,ISNULL(RLSQTY,0)) RLSQTY, (PNDQTY-ISNULL(RLSQTY,0)) BALQTY  FROM
        (select PND_DOC,MIN(PND_DT) MINPND_DT,PND_ITMCD,ISNULL(PND_ITMLOT,'') PND_ITMLOT, sum(PND_QTY) PNDQTY from PND_TBL
        group by PND_DOC,PND_ITMCD,PND_ITMLOT) VPND
        LEFT JOIN
        (SELECT RLS_REFFDOC, RLS_ITMCD, RLS_ITMLOT, SUM(RLS_QTY) RLSQTY FROM RLS_TBL
        GROUP BY RLS_REFFDOC, RLS_ITMCD, RLS_ITMLOT) VRLS ON PND_DOC=RLS_REFFDOC AND PND_ITMCD=RLS_ITMCD AND PND_ITMLOT=RLS_ITMLOT
        WHERE PND_DOC LIKE ? 
        ORDER BY PND_DOC , PND_ITMCD";
        $query = $this->db->query($qry, ["%".$pdata."%"]);
        return $query->result_array();
    }
    public function select_rm_balance_by_LotNo($pdata){
        $qry = "SELECT VPND.*,CONVERT(INT,ISNULL(RLSQTY,0)) RLSQTY, (PNDQTY-ISNULL(RLSQTY,0)) BALQTY  FROM
        (select PND_DOC,MIN(PND_DT) MINPND_DT,PND_ITMCD,ISNULL(PND_ITMLOT,'') PND_ITMLOT, sum(PND_QTY) PNDQTY from PND_TBL
        group by PND_DOC,PND_ITMCD,PND_ITMLOT) VPND
        LEFT JOIN
        (SELECT RLS_REFFDOC, RLS_ITMCD, RLS_ITMLOT, SUM(RLS_QTY) RLSQTY FROM RLS_TBL
        GROUP BY RLS_REFFDOC, RLS_ITMCD, RLS_ITMLOT) VRLS ON PND_DOC=RLS_REFFDOC AND PND_ITMCD=RLS_ITMCD AND PND_ITMLOT=RLS_ITMLOT
        WHERE PND_ITMLOT LIKE ?
        ORDER BY PND_DOC , PND_ITMCD";
        $query = $this->db->query($qry, ["%".$pdata."%"]);
        return $query->result_array();
    }
    public function select_rm_balance_only_by_DocItemLotNo($pdoc, $pitem, $plot, $prlsdoc){
        $qry = "SELECT (PNDQTY-ISNULL(RLSQTY,0)) BALQTY  FROM
        (select PND_DOC,MIN(PND_DT) MINPND_DT,PND_ITMCD,ISNULL(PND_ITMLOT,'') PND_ITMLOT, sum(PND_QTY) PNDQTY from PND_TBL
        group by PND_DOC,PND_ITMCD,PND_ITMLOT) VPND
        LEFT JOIN
        (SELECT RLS_REFFDOC, RLS_ITMCD, RLS_ITMLOT, SUM(RLS_QTY) RLSQTY FROM RLS_TBL WHERE RLS_DOC != ?
        GROUP BY RLS_REFFDOC, RLS_ITMCD, RLS_ITMLOT) VRLS ON PND_DOC=RLS_REFFDOC AND PND_ITMCD=RLS_ITMCD AND PND_ITMLOT=RLS_ITMLOT
        WHERE PND_DOC = ? AND PND_ITMCD = ? AND PND_ITMLOT = ?
        ORDER BY PND_DOC , PND_ITMCD";
        $query = $this->db->query($qry, [$prlsdoc, $pdoc, $pitem, $plot]);
        // return $query->result_array();
        if ($query->num_rows()>0){
            $ret = $query->row();
            return $ret->BALQTY;
        } else {
            return 0;
        }
    }
    public function select_rm_where($pwhere){        
        $this->db->from($this->TABLENAMERM);
        $this->db->where($pwhere);
        $this->db->order_by('RLS_ITMCD, RLS_ITMLOT');
		$query = $this->db->get();
		return $query->result_array();
    }

    public function select_rm_rpt_where($pwhere){
        $this->db->from($this->TABLENAMERM);
        $this->db->join("MSTEMP_TBL", "RLS_USRID=MSTEMP_ID");
        $this->db->join("MITM_TBL", "RLS_ITMCD=MITM_ITMCD");
        $this->db->join("ITMLOC_TBL", "ITMLOC_ITM=RLS_ITMCD", "LEFT");
        $this->db->where($pwhere);
        $this->db->select("RLS_DT, RLS_ITMCD, RTRIM(MITM_SPTNO) MITM_SPTNO, RLS_ITMLOT, RLS_QTY, CONCAT(MSTEMP_FNM, ' ', MSTEMP_LNM) FULLNM, ITMLOC_LOC");
        $this->db->order_by('RLS_ITMCD, RLS_ITMLOT');
		$query = $this->db->get();
		return $query->result_array();
    }

    public function select_rm_resume($pdoc){
        $this->db->select("RLS_DOC,RLS_DT,COUNT(*) ITEMCOUNT");
        $this->db->from($this->TABLENAMERM);
        $this->db->like("RLS_DOC", $pdoc);
        $this->db->group_by("RLS_DOC,RLS_DT");
		$query = $this->db->get();
		return $query->result_array();
    }


}