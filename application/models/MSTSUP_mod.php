<?php

class MSTSUP_mod extends CI_Model
{
    private $TABLENAME = "MSUP_TBL";
    public function __construct()
    {
        $this->load->database();
    }
    public function check_Primary($data)
    {
        return $this->db->get_where($this->TABLENAME, $data)->num_rows();
    }
    public function updatebyId($pdata, $pkey)
    {
        $this->db->where('MSUP_SUPCD', $pkey);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
    }
    public function update_where($pdata, $pwhere)
    {
        $this->db->where($pwhere);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
    }
    public function selectAll()
    {
        $this->db->select("MSUP_SUPCD,MSUP_SUPCR,MSUP_SUPNM,MSUP_ABBRV,MSUP_ADDR1");
        $query = $this->db->get($this->TABLENAME);
        return $query->result();
    }
    public function select3()
    {
        $this->db->select("MSUP_SUPCD,MSUP_SUPCR,MSUP_SUPNM");
        $query = $this->db->get($this->TABLENAME);
        return $query->result();
    }

    public function select_like($plike)
    {
        $this->db->select("MSUP_SUPCD,MSUP_SUPCR,MSUP_SUPNM,MSUP_ABBRV,RTRIM(MSUP_ADDR1) MSUP_ADDR1,MSUP_TELNO,MSUP_FAXNO,RTRIM(MSUP_TAXREG) MSUP_TAXREG,RTRIM(MSUP_PTERM) MSUP_PTERM");
        $this->db->like($plike);
        $query = $this->db->get($this->TABLENAME, 10);
        return $query->result();
    }

    public function selectbyName($pname)
    {
        $this->db->select("MSUP_SUPCD,MSUP_SUPCR,MSUP_SUPNM");
        $this->db->like('lower(MSUP_SUPNM)', $pname);
        $query = $this->db->get($this->TABLENAME, 10);
        return $query->result();
    }

    public function insert($data)
    {
        $this->db->insert($this->TABLENAME, $data);
        return $this->db->affected_rows();
    }

    public function select_union($plike)
    {
        $this->db->select("MSUP_SUPCD,MSUP_SUPNM,MSUP_SUPCR");
        $this->db->like($plike);
        $query = $this->db->get("v_supplier_customer_union", 100);
        return $query->result();
    }

    public function select_where_id_in($arrayCD)
    {
        $this->db->select("RTRIM(MSUP_SUPCD) SUPCD,RTRIM(MSUP_SUPNM) SUPNM,MSUP_SUPCR");
        $this->db->where_in("MSUP_SUPCD", $arrayCD);
        $query = $this->db->get($this->TABLENAME);
        return $query->result_array();
    }

    public function sync()
    {
        $qry = "
		INSERT INTO MSUP_TBL (
		MSUP_SUPCD,
		MSUP_SUPCR,
		MSUP_SUPNM,
		MSUP_ABBRV,
		MSUP_SUPTY,
		MSUP_CTRCD,
		MSUP_PTERM,
		MSUP_TAXCD,
		MSUP_ADDR1,
		MSUP_ADDR2,
		MSUP_ADDR3,
		MSUP_ADDR4,
		MSUP_ADDR5,
		MSUP_ADDR6,
		MSUP_ARECD,
		MSUP_TELNO,
		MSUP_TELNO2,
		MSUP_FAXNO,
		MSUP_FAXNO2,
		MSUP_TELEX,
		MSUP_EMAIL,
		MSUP_PIC,
		MSUP_PIC2,
		MSUP_LPODT,
		MSUP_REM,
		MSUP_REVISE,
		MSUP_PAYTY,
		MSUP_PLTDAY,
		MSUP_AIRLT,
		MSUP_SPFG,
		MSUP_LUPDT,
		MSUP_POTO,
		MSUP_POCUR,
		MSUP_SACTY,
		MSUP_USRID,
		MSUP_CRLMT,
		MSUP_TAXREG,
		MSUP_TRADE,
		MSUP_SNMCD,
		MSUP_AMTDEC,
		MSUP_AMTROUND,
		MSUP_PAYEECD,
		MSUP_TOBANKMSG,
		MSUP_SUPNM1,
		MSUP_PRGID
		)
		select m.* from XMSUP m left join MSUP_TBL w on m.MSUP_SUPCD=w.MSUP_SUPCD
		where w.MSUP_SUPCD is null";
        $this->db->query($qry);
        return $this->db->affected_rows();
    }

    public function update_based_on_parent()
    {
        $qry = "UPDATE A
		SET A.MSUP_ADDR1=B.MSUP_ADDR1,A.MSUP_ADDR2=B.MSUP_ADDR2,A.MSUP_TELNO=B.MSUP_TELNO,A.MSUP_PIC=B.MSUP_PIC
		from MSUP_TBL A
		left join XMSUP B on A.MSUP_SUPCD=B.MSUP_SUPCD";
        $this->db->query($qry);
        return $this->db->affected_rows();
    }
}
