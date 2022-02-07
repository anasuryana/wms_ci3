<?php

class RCVSCN_mod extends CI_Model {
	private $TABLENAME = "RCVSCN_TBL";
	public function __construct()
    {
        $this->load->database();
    }

    public function check_Primary($data)
    {
        return $this->db->get_where($this->TABLENAME,$data)->num_rows();
    }

    public function insert($data)
    {                
        $this->db->insert($this->TABLENAME,$data);        
        return $this->db->affected_rows();
    }

    public function lastserialid(){       
        $qry = "select TOP 1 substring(RCVSCN_ID, 9, 20) lser from RCVSCN_TBL 
        WHERE convert(date, RCVSCN_LUPDT) = convert(date,GETDATE())
        ORDER BY convert(bigint,SUBSTRING(RCVSCN_ID,9,11)) desc";
        $query =  $this->db->query($qry);        
        if ($query->num_rows()>0){
            $ret = $query->row();
            return $ret->lser;
        } else {
            return '0';
        }
    }

    public function select_DOCUST_byLOT($plot){
        $qry = "SELECT V1.* FROM
        (SELECT RCV_DONO,RCV_ITMCD,RCV_SUPCD,MSUP_SUPNM FROM RCV_TBL A
        LEFT JOIN XMSUP B ON A.RCV_SUPCD=MSUP_SUPCD
        GROUP BY RCV_DONO,RCV_ITMCD,RCV_SUPCD,MSUP_SUPNM) V1
        INNER JOIN
        (
        SELECT RCVSCN_DONO,RCVSCN_ITMCD FROM RCVSCN_TBL 
        where RCVSCN_LOTNO like ?        
        GROUP BY RCVSCN_DONO,RCVSCN_ITMCD
        ) V2 ON RCV_DONO=RCVSCN_DONO and RCV_ITMCD=RCVSCN_ITMCD";
        $query = $this->db->query($qry , array('%'.$plot.'%'));
        return $query->result_array();
    }
    public function select_DOCUST_byDO($pdo){
        $qry = "SELECT V1.* FROM
        (SELECT RCV_DONO,RCV_ITMCD,RCV_SUPCD,MSUP_SUPNM FROM RCV_TBL A
        LEFT JOIN XMSUP B ON A.RCV_SUPCD=MSUP_SUPCD
        GROUP BY RCV_DONO,RCV_ITMCD,RCV_SUPCD,MSUP_SUPNM) V1
        INNER JOIN
        (
        SELECT RCVSCN_DONO,RCVSCN_ITMCD FROM RCVSCN_TBL 
        where RCVSCN_DONO like ?        
        GROUP BY RCVSCN_DONO,RCVSCN_ITMCD
        ) V2 ON RCV_DONO=RCVSCN_DONO and RCV_ITMCD=RCVSCN_ITMCD";
        $query = $this->db->query($qry , array('%'.$pdo.'%'));
        return $query->result_array();
    }

    public function selectby_filter($pwhere){
        $this->db->from($this->TABLENAME);        
        $this->db->where($pwhere);
        $this->db->order_by('RCVSCN_LUPDT DESC');
		$query = $this->db->get();
		return $query->result_array();
    }

    public function selectby_filter_orderby_itemlot($pwhere){
        $this->db->select("RCVSCN_ID,RCVSCN_ITMCD,RCVSCN_QTY,RCVSCN_LOTNO,MITM_SPTNO");
        $this->db->from($this->TABLENAME);  
        $this->db->join('MITM_TBL', 'RCVSCN_ITMCD=MITM_ITMCD');    
        $this->db->where($pwhere);
        $this->db->order_by('RCVSCN_ITMCD ASC,RCVSCN_LOTNO asc');
		$query = $this->db->get();
		return $query->result_array();
    }

    public function selectby_filter_like($pwhere){
        $this->db->from($this->TABLENAME);        
        $this->db->like($pwhere);
        $this->db->order_by('RCVSCN_LUPDT DESC');
		$query = $this->db->get();
		return $query->result_array();
    }

    public function deleteby_filter($pwhere)
    {          
        $this->db->where($pwhere);
        $this->db->delete($this->TABLENAME);
        return $this->db->affected_rows();
    }

    public function updatebyId($pdata, $pdo, $pitem)
    {
        $this->db->where('RCVSCN_DONO',$pdo)->where('RCVSCN_ITMCD', $pitem);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
    }
    public function updatebyrowId($rowid, $pdata)
    {
        $this->db->where('RCVSCN_ID',$rowid);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
    }
    public function update_unsaved($pdo,$pid)
    {
        $qry = "UPDATE $this->TABLENAME SET RCVSCN_SAVED='1' WHERE RCVSCN_ID = ? AND RCVSCN_DONO = ?";
		$this->db->query($qry , array($pid, $pdo));
        return $this->db->affected_rows();
    }
    public function select_unsaved($pdo)
    {
        $qry = "SELECT RCVSCN_ID,RCVSCN_ITMCD,RCVSCN_QTY FROM $this->TABLENAME WHERE RCVSCN_DONO = ? AND (COALESCE(RCVSCN_SAVED,'')='' OR RCVSCN_SAVED='0' OR RCVSCN_SAVED=' ') ";
		$query = $this->db->query($qry , array($pdo));
        return $query->result_array();
    }

    public function select_discrepancy_h()
    {
        $qry = "exec sp_inc_discreapancy_h";
		$query = $this->db->query($qry );
        return $query->result_array();
    }
    public function select_discrepancy($pdo)
    {
        $qry = "exec sp_inc_discreapancy ?";
		$query = $this->db->query($qry, array($pdo) );
        return $query->result_array();
    }
    
}