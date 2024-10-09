<?php
class Closing_job_model extends CI_Model
{
    private $TABLENAME = 'WMS_CLS_JOB';
    public function __construct()
    {
        $this->load->database();
    }

    public function check_Primary($data)
    {
        return $this->db->get_where($this->TABLENAME, $data)->num_rows();
    }

    public function selectOutput($pwhere)
    {
        $this->db->from($this->TABLENAME);
        $this->db->select("RTRIM(CLS_JOBNO) WONO, SUM(CLS_QTY) OUTQT");
        $this->db->where($pwhere);
        $this->db->group_by('CLS_JOBNO');
        $query = $this->db->get();
        return $query->result_array();
    }
}
