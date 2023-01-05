<?php

class MSTCUS_mod extends CI_Model
{
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
        $this->db->join('ZJNSTPB_TBL', 'MCUS_TPBTYPE=KODE_JENIS_TPB', 'left');
        $this->db->like('MCUS_CUSCD', $pid);
        $query = $this->db->get($this->TABLENAME, 10);
        return $query->result();
    }
    public function selectbynm_lk($pid)
    {
        $this->db->select("MCUS_CUSCD,MCUS_CURCD,MCUS_CUSNM,MCUS_ABBRV,MCUS_ADDR1,MCUS_TPBTYPE,URAIAN_JENIS_TPB,MCUS_TAXREG,MCUS_TPBNO");
        $this->db->join('ZJNSTPB_TBL', 'MCUS_TPBTYPE=KODE_JENIS_TPB', 'left');
        $this->db->like('MCUS_CUSNM', $pid);
        $query = $this->db->get($this->TABLENAME, 10);
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
        $this->db->join('ZJNSTPB_TBL', 'MCUS_TPBTYPE=KODE_JENIS_TPB', 'left');
        $this->db->like('MCUS_ABBRV', $pid);
        $query = $this->db->get($this->TABLENAME, 10);
        return $query->result();
    }

    public function sync()
    {
        $qry = "INSERT INTO MCUS_TBL (
         MCUS_CUSCD,
         MCUS_CURCD,
         MCUS_CUSNM,
         MCUS_ABBRV,		
         MCUS_CTRCD,		
         MCUS_PTERM,
         MCUS_TAXCD,
         MCUS_ADDR1,
         MCUS_ADDR2,
         MCUS_ADDR3,
         MCUS_ADDR4,
         MCUS_ADDR5,
         MCUS_ADDR6,
         MCUS_ARECD,
         MCUS_TELNO,
         MCUS_TELNO2,
         MCUS_FAXNO,
         MCUS_FAXNO2,
         MCUS_TELEX,
         MCUS_EMAIL,
         MCUS_PIC,
         MCUS_PIC2,		
         MCUS_REM,		
         MCUS_USRID,
         MCUS_CRLMT,
         MCUS_TAXREG,
         MCUS_TRADE,	
         MCUS_PRGID
         )
         select m.MCUS_CUSCD,
         m.MCUS_CURCD,
         m.MCUS_CUSNM,
         m.MCUS_ABBRV,		
         m.MCUS_CTRCD,		
         m.MCUS_PTERM,
         m.MCUS_TAXCD,
         m.MCUS_ADDR1,
         m.MCUS_ADDR2,
         m.MCUS_ADDR3,
         m.MCUS_ADDR4,
         m.MCUS_ADDR5,
         m.MCUS_ADDR6,
         m.MCUS_ARECD,
         m.MCUS_TELNO,
         m.MCUS_TELNO2,
         m.MCUS_FAXNO,
         m.MCUS_FAXNO2,
         m.MCUS_TELEX,
         m.MCUS_EMAIL,
         m.MCUS_PIC,
         m.MCUS_PIC2,		
         m.MCUS_REM,		
         m.MCUS_USRID,
         m.MCUS_CRLMT,
         m.MCUS_TAXREG,
         m.MCUS_TRADE,	
         m.MCUS_PRGID from XMCUS m left join MCUS_TBL w on m.MCUS_CUSCD=w.MCUS_CUSCD
         where w.MCUS_CUSCD is null";
        $this->db->query($qry);
        return $this->db->affected_rows();
    }

    public function selectbyad_lk($pid)
    {
        $this->db->select("MCUS_CUSCD,MCUS_CURCD,MCUS_CUSNM,MCUS_ABBRV,MCUS_ADDR1,MCUS_TPBTYPE,URAIAN_JENIS_TPB,MCUS_TAXREG,MCUS_TPBNO");
        $this->db->join('ZJNSTPB_TBL', 'MCUS_TPBTYPE=KODE_JENIS_TPB', 'left');
        $this->db->like('MCUS_ADDR1', $pid);
        $query = $this->db->get($this->TABLENAME, 10);
        return $query->result();
    }
    public function insert($data)
    {
        $this->db->insert($this->TABLENAME, $data);
        return $this->db->affected_rows();
    }
    public function updatebyId($pdata, $pkey)
    {
        $this->db->where('MCUS_CUSCD', $pkey);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
    }
    public function check_Primary($data)
    {
        return $this->db->get_where($this->TABLENAME, $data)->num_rows();
    }
}
