<?php

class MSTCUS_mod extends CI_Model {
	private $TABLENAME = "MCUS_TBL";
	public function __construct()
    {
        $this->load->database();
    }
	public function selectAll()
	{
		$this->db->select("MCUS_CUSCD,MCUS_CURCD,MCUS_CUSNM,MCUS_ABBRV,MCUS_ADDR1");
		$query = $this->db->get($this->TABLENAME);
		return $query->result();
	}

	public function selectbycd_lk($pid)
	{
		$this->db->select("MCUS_CUSCD,MCUS_CURCD,MCUS_CUSNM,MCUS_ABBRV,MCUS_ADDR1,MCUS_TPBTYPE,URAIAN_JENIS_TPB,MCUS_TAXREG,MCUS_TPBNO");
		$this->db->join('ZJNSTPB_TBL' , 'MCUS_TPBTYPE=KODE_JENIS_TPB', 'left');
		$this->db->like('MCUS_CUSCD', $pid);
		$query = $this->db->get($this->TABLENAME,10);
		return $query->result();
	}
	public function selectbynm_lk($pid)
	{
		$this->db->select("MCUS_CUSCD,MCUS_CURCD,MCUS_CUSNM,MCUS_ABBRV,MCUS_ADDR1,MCUS_TPBTYPE,URAIAN_JENIS_TPB,MCUS_TAXREG,MCUS_TPBNO");
		$this->db->join('ZJNSTPB_TBL' , 'MCUS_TPBTYPE=KODE_JENIS_TPB', 'left');
		$this->db->like('MCUS_CUSNM', $pid);
		$query = $this->db->get($this->TABLENAME,10);
		return $query->result();
	}
	public function selectbynm_notlk($pid)
	{
		$this->db->select("MCUS_CUSCD,MCUS_CURCD,MCUS_CUSNM,MCUS_ABBRV,MCUS_ADDR1");
		$this->db->not_like('MCUS_CUSNM', $pid);
		$query = $this->db->get($this->TABLENAME);
		return $query->result();
	}
	public function selectbyab_lk($pid)
	{
		$this->db->select("MCUS_CUSCD,MCUS_CURCD,MCUS_CUSNM,MCUS_ABBRV,MCUS_ADDR1,MCUS_TPBTYPE,URAIAN_JENIS_TPB,MCUS_TAXREG,MCUS_TPBNO");
		$this->db->join('ZJNSTPB_TBL' , 'MCUS_TPBTYPE=KODE_JENIS_TPB', 'left');
		$this->db->like('MCUS_ABBRV', $pid);
		$query = $this->db->get($this->TABLENAME,10);
		return $query->result();
	}
	public function selectbyad_lk($pid)
	{
		$this->db->select("MCUS_CUSCD,MCUS_CURCD,MCUS_CUSNM,MCUS_ABBRV,MCUS_ADDR1,MCUS_TPBTYPE,URAIAN_JENIS_TPB,MCUS_TAXREG,MCUS_TPBNO");
		$this->db->join('ZJNSTPB_TBL' , 'MCUS_TPBTYPE=KODE_JENIS_TPB', 'left');
		$this->db->like('MCUS_ADDR1', $pid);
		$query = $this->db->get($this->TABLENAME,10);
		return $query->result();
	}
	public function insert($data)
    {
        $this->db->insert($this->TABLENAME,$data);
        return $this->db->affected_rows();
	}
	public function updatebyId($pdata, $pkey)
    {        
        $this->db->where('MCUS_CUSCD',$pkey);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
	}
	public function check_Primary($data)
    {        
        return $this->db->get_where($this->TABLENAME,$data)->num_rows();
	}
}