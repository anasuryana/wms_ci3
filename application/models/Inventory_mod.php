<?php

class Inventory_mod extends CI_Model {
	private $TABLENAME = "WMS_Inv";
	private $TABLENAMERM = "WMS_InvRM";
	public function __construct()
    {
        $this->load->database();
    }
	public function selectAll()
	{
        $this->db->select("SER_ITMID CASSYNO,CONCAT(RTRIM(MSTEMP_FNM),' ', RTRIM(LTRIM(MSTEMP_LNM))) FULLNAME,SER_DOC,CLOTNO,CLOC,REFNO,CQTY,CMODEL,CDATE");
        $this->db->from($this->TABLENAME." a");
        $this->db->join('MSTEMP_TBL b', 'cPic=MSTEMP_ID','left');    
        $this->db->join('SER_TBL c', 'REFNO=SER_ID','left');    
        $this->db->order_by('CDATE ASC');
        $query = $this->db->get();
        return $query->result_array();
	}
	public function selectAll_rm()
	{	        
        $this->db->select("CPARTCODE,CONCAT(RTRIM(MSTEMP_FNM),' ', RTRIM(LTRIM(MSTEMP_LNM))) FULLNAME,RTRIM(MITM_SPTNO) MITM_SPTNO,CLOTNO,CLOC,CQTY,CDATE");
        $this->db->from($this->TABLENAMERM." a");
        $this->db->join('MSTEMP_TBL b', 'cPic=MSTEMP_ID','left');
        $this->db->join('MITM_TBL c', 'CPARTCODE=MITM_ITMCD','left');    
        $this->db->order_by('CDATE ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    
	public function selectAll_rm_group($pwhere)
	{	        
        $this->db->select("CPARTCODE,RTRIM(MITM_SPTNO) MITM_SPTNO,CLOC,sum(CQTY) CQTY");
        $this->db->from($this->TABLENAMERM." a");        
        $this->db->join('MITM_TBL c', 'CPARTCODE=MITM_ITMCD','left');
        $this->db->like($pwhere);
        $this->db->group_by("CPARTCODE,MITM_SPTNO,CLOC");
        $this->db->order_by('CPARTCODE,CLOC');
        $query = $this->db->get();
        return $query->result_array();
    }
	public function selectAll_rm_group_with_bisgrup($plike,$pbisgrup)
	{	        
        $this->db->select("CPARTCODE,RTRIM(MITM_SPTNO) MITM_SPTNO,CLOC,sum(CQTY) CQTY");
        $this->db->from($this->TABLENAMERM." a");        
        $this->db->join('MITM_TBL c', 'CPARTCODE=MITM_ITMCD','left');
        $this->db->join('ITMLOC_TBL d', 'CLOC=ITMLOC_LOC AND CPARTCODE=ITMLOC_ITM','left');
        $this->db->where('ITMLOC_BG', $pbisgrup);
        $this->db->like($plike);
        $this->db->group_by("CPARTCODE,MITM_SPTNO,CLOC");
        $this->db->order_by('CPARTCODE,CLOC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectAll_unscanned_like_with_bisgrup($plike, $pbisgrup) {
        $this->db->select("ITMLOC_ITM,RTRIM(MITM_SPTNO) SPTNO,ITMLOC_LOC");
        $this->db->from("ITMLOC_TBL");
        $this->db->join('(SELECT cPartCode,cLoc FROM WMS_InvRM GROUP BY cPartCode,cLoc) V1', 'ITMLOC_ITM=cPartCode','left');
        $this->db->join('MITM_TBL', 'ITMLOC_ITM=MITM_ITMCD','left');
        $this->db->where_not_in("ITMLOC_LOCG", ['AFWH3']);
        $this->db->where("cLoc IS NULL",NULL, FALSE);
        $this->db->where('ITMLOC_BG', $pbisgrup);
        $this->db->like($plike);
        $this->db->order_by("ITMLOC_ITM");
        $query = $this->db->get();
        return $query->result_array();
    }

	public function selectAll_rm_rack_like_group($pitem, $plot, $prack)
	{	        
        $this->db->select("CPARTCODE,RTRIM(MITM_SPTNO) MITM_SPTNO,CLOC,sum(CQTY) CQTY");
        $this->db->from($this->TABLENAMERM." a");        
        $this->db->join('MITM_TBL c', 'CPARTCODE=MITM_ITMCD','left');
        $this->db->join('vinitlocation d', 'CLOC=MSTLOC_CD','left');
        $this->db->like('CPARTCODE', $pitem)->like('CLOTNO', $plot)
        ->like('CLOC', $prack[0], 'after')->like('CLOC', $prack[1], 'before');
        $this->db->group_by("CPARTCODE,MITM_SPTNO,CLOC,");
        $this->db->order_by('CPARTCODE,CLOC');
        $query = $this->db->get();
        return $query->result_array();
    }
	public function selectAll_rm_rack_like_group_with_bisgrup($pitem, $plot, $prack, $pbisgrup)
	{	        
        $this->db->select("CPARTCODE,RTRIM(MITM_SPTNO) MITM_SPTNO,CLOC,sum(CQTY) CQTY");
        $this->db->from($this->TABLENAMERM." a");        
        $this->db->join('MITM_TBL c', 'CPARTCODE=MITM_ITMCD','left');
        $this->db->join('ITMLOC_TBL d', 'CLOC=ITMLOC_LOC AND CPARTCODE=ITMLOC_ITM','left');
        $this->db->where('ITMLOC_BG', $pbisgrup);
        $this->db->like('CPARTCODE', $pitem)->like('CLOTNO', $plot)
        ->like('CLOC', $prack[0], 'after')->like('CLOC', $prack[1], 'before');
        $this->db->group_by("CPARTCODE,MITM_SPTNO,CLOC");
        $this->db->order_by('CPARTCODE,CLOC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectAll_unscanned_rack_like_with_bisgrup($prack, $pbisgrup) {
        $this->db->select("ITMLOC_ITM,RTRIM(MITM_SPTNO) SPTNO,ITMLOC_LOC");
        $this->db->from("ITMLOC_TBL");
        $this->db->join('(SELECT cPartCode,cLoc FROM WMS_InvRM GROUP BY cPartCode,cLoc) V1', 'ITMLOC_ITM=cPartCode','left');
        $this->db->join('MITM_TBL', 'ITMLOC_ITM=MITM_ITMCD','left');
        $this->db->where_not_in("ITMLOC_LOCG", ['AFWH3']);
        $this->db->where("cLoc IS NULL",NULL, FALSE);
        $this->db->where('ITMLOC_BG', $pbisgrup);
        $this->db->like('ITMLOC_LOC', $prack[0], 'after')->like('ITMLOC_LOC', $prack[1], 'before');
        $this->db->order_by("ITMLOC_ITM");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function select_unscanned() {
        $this->db->select("ITMLOC_ITM,RTRIM(MITM_SPTNO) SPTNO,ITMLOC_LOC");
        $this->db->from("ITMLOC_TBL");
        $this->db->join('(SELECT cPartCode,cLoc FROM WMS_InvRM GROUP BY cPartCode,cLoc) V1', 'ITMLOC_ITM=cPartCode','left');
        $this->db->join('MITM_TBL', 'ITMLOC_ITM=MITM_ITMCD','left');
        $this->db->where_not_in("ITMLOC_LOCG", ['AFWH3']);
        $this->db->where("cLoc IS NULL",NULL, FALSE);
        $this->db->order_by("ITMLOC_ITM");
        $query = $this->db->get();
        return $query->result_array();
    }
    
    
	public function selectAll_rm_like($plike)
	{	        
        $this->db->select("CPARTCODE,CONCAT(RTRIM(MSTEMP_FNM),' ', RTRIM(LTRIM(MSTEMP_LNM))) FULLNAME,RTRIM(MITM_SPTNO) MITM_SPTNO,CLOTNO,CLOC,CQTY,CDATE");
        $this->db->from($this->TABLENAMERM." a");
        $this->db->join('MSTEMP_TBL b', 'cPic=MSTEMP_ID','left');
        $this->db->join('MITM_TBL c', 'CPARTCODE=MITM_ITMCD','left');
        $this->db->like($plike);
        $this->db->order_by('CLOC, CDATE ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
	public function selectAll_rm_like_with_bisgrup($plike, $pbisgrup)
	{	        
        $this->db->select("CPARTCODE,CONCAT(RTRIM(MSTEMP_FNM),' ', RTRIM(LTRIM(MSTEMP_LNM))) FULLNAME,RTRIM(MITM_SPTNO) MITM_SPTNO,CLOTNO,CLOC,CQTY,CDATE");
        $this->db->from($this->TABLENAMERM." a");
        $this->db->join('MSTEMP_TBL b', 'cPic=MSTEMP_ID','left');
        $this->db->join('MITM_TBL c', 'CPARTCODE=MITM_ITMCD','left');
        $this->db->join('ITMLOC_TBL d', 'CLOC=ITMLOC_LOC AND CPARTCODE=ITMLOC_ITM','left');
        $this->db->where('ITMLOC_BG', $pbisgrup);
        $this->db->like($plike);
        $this->db->order_by('CLOC, CDATE ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
	public function selectAll_rm_rack_like($pitem, $plot, $prack)
	{	        
        $this->db->select("CPARTCODE,CONCAT(RTRIM(MSTEMP_FNM),' ', RTRIM(LTRIM(MSTEMP_LNM))) FULLNAME,RTRIM(MITM_SPTNO) MITM_SPTNO,CLOTNO,CLOC,CQTY,CDATE");
        $this->db->from($this->TABLENAMERM." a");
        $this->db->join('MSTEMP_TBL b', 'cPic=MSTEMP_ID','left');
        $this->db->join('MITM_TBL c', 'CPARTCODE=MITM_ITMCD','left');
        $this->db->like('CPARTCODE', $pitem)->like('CLOTNO', $plot)
        ->like('CLOC', $prack[0], 'after')->like('CLOC', $prack[1], 'before');
        $this->db->order_by('CLOC, CDATE ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
	public function selectAll_rm_rack_like_with_bisgrup($pitem, $plot, $prack, $pbisgrup)
	{	        
        $this->db->select("CPARTCODE,CONCAT(RTRIM(MSTEMP_FNM),' ', RTRIM(LTRIM(MSTEMP_LNM))) FULLNAME,RTRIM(MITM_SPTNO) MITM_SPTNO,CLOTNO,CLOC,CQTY,CDATE");
        $this->db->from($this->TABLENAMERM." a");
        $this->db->join('MSTEMP_TBL b', 'cPic=MSTEMP_ID','left');
        $this->db->join('MITM_TBL c', 'CPARTCODE=MITM_ITMCD','left');
        $this->db->join('ITMLOC_TBL d', 'CLOC=ITMLOC_LOC AND CPARTCODE=ITMLOC_ITM','left');
        $this->db->where('ITMLOC_BG', $pbisgrup);
        $this->db->like('CPARTCODE', $pitem)->like('CLOTNO', $plot)
        ->like('CLOC', $prack[0], 'after')->like('CLOC', $prack[1], 'before');
        $this->db->order_by('CLOC, CDATE ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    
    
    public function delete_rm($pwhere)
    {    
        $qry = "DELETE FROM $this->TABLENAMERM WHERE CPARTCODE=? AND CDATE=? AND CLOC=?";
        $this->db->query($qry, [$pwhere['CPARTCODE'], $pwhere['CDATE'], $pwhere['CLOC'] ] );
        return $this->db->affected_rows();
    }

    public function delete_all_rm()
    {
        $qry = "TRUNCATE TABLE $this->TABLENAMERM";
        $this->db->query($qry);
        return $this->db->affected_rows();
    }

    public function delete_all_fg()
    {    
        $qry = "TRUNCATE TABLE $this->TABLENAME";
        $this->db->query($qry);
        return $this->db->affected_rows();
    }
    public function insert_fg_for_backup($period)
    {    
        $qry = "INSERT INTO INVENTORY_TBL SELECT *,? FROM $this->TABLENAME";
        $this->db->query($qry, [$period]);
        return $this->db->affected_rows();
    }
    public function insert_rm_for_backup($period)
    {    
        $qry = "INSERT INTO INVENTORY_TBL (ASSYNO,CLOTNO,CQTY,CLOC,CDATE,CPIC,CPERIOD)
        SELECT cPartCode,cLotNo,cQty,cLoc,cDate,cPic,? FROM $this->TABLENAMERM";
        $this->db->query($qry, [$period]);
        return $this->db->affected_rows();
    }
}