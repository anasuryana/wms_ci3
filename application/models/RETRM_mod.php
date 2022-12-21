<?php
class RETRM_mod extends CI_Model
{
    private $TABLENAME = "RETRM_TBL";
    public function __construct()
    {
        $this->load->database();
    }

    public function lastserialid()
    {
        $qry = "select ISNULL(MAX(CONVERT(INT,SUBSTRING(RETRM_DOC,9,4))),0) lser 
        from " . $this->TABLENAME . " where CONVERT(DATE,RETRM_CREATEDAT)=CONVERT(DATE, GETDATE())";
        $query =  $this->db->query($qry);
        if ($query->num_rows() > 0) {
            $ret = $query->row();
            return $ret->lser;
        } else {
            return 0;
        }
    }

    public function selectAll()
    {
        $query = $this->db->from($this->TABLENAME)->get();
        return $query->result_array();
    }
    public function select_where($columns, $where)
    {
        $this->db->select($columns);
        $query = $this->db->from($this->TABLENAME)->where($where)->get();
        return $query->result_array();
    }

    public function check_Primary($data)
    {
        return $this->db->get_where($this->TABLENAME, $data)->num_rows();
    }

    public function insert($data)
    {
        $this->db->insert($this->TABLENAME, $data);
        return $this->db->affected_rows();
    }

    public function delete_where($PWHERE)
    {
        $this->db->where($PWHERE);
        $this->db->delete($this->TABLENAME);
        return $this->db->affected_rows();
    }

    public function selectWithRack($pwhere)
    {
        $this->db->select("A.*,ITMLOC_LOC,RTRIM(MITM_SPTNO) SPTNO,RTRIM(MITM_ITMD1) ITMD1");
        $query = $this->db->from($this->TABLENAME . " A")
            ->join("(SELECT ITMLOC_ITM,MAX(ITMLOC_LOC) ITMLOC_LOC from ITMLOC_TBL
        GROUP BY ITMLOC_ITM) VLOC", "RETRM_ITMCD=ITMLOC_ITM", "LEFT")
            ->join("MITM_TBL", "A.RETRM_ITMCD=MITM_ITMCD", "LEFT")
            ->where($pwhere)->get();
        return $query->result_array();
    }

    public function selectWithRackAndLike($pwhere, $plike)
    {
        $this->db->select("A.*,ITMLOC_LOC,RTRIM(MITM_SPTNO) SPTNO,RTRIM(MITM_ITMD1) ITMD1");
        $query = $this->db->from($this->TABLENAME . " A")
            ->join("(SELECT ITMLOC_ITM,MAX(ITMLOC_LOC) ITMLOC_LOC,MAX(ITMLOC_BG) ITMLOC_BG from ITMLOC_TBL
        GROUP BY ITMLOC_ITM) VLOC", "RETRM_ITMCD=ITMLOC_ITM", "LEFT")
            ->join("MITM_TBL", "A.RETRM_ITMCD=MITM_ITMCD", "LEFT")
            ->where($pwhere)->like($plike)->get();
        return $query->result_array();
    }
}
