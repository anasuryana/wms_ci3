<?php

class MEXRATE_mod extends CI_Model
{
   private $TABLENAME = "MEXRATE_TBL";
   public function __construct()
   {
      $this->load->database();
   }

   public function selectAll()
   {
      $this->db->select("MEXRATE_CURR,MEXRATE_TYPE, MEXRATE_DT,MEXRATE_VAL");
      $query = $this->db->get($this->TABLENAME);
      return $query->result();
   }
   public function select_where($where)
   {
      $this->db->select("MEXRATE_CURR,MEXRATE_TYPE, MEXRATE_DT,MEXRATE_VAL");
      $this->db->where($where);
      $query = $this->db->get($this->TABLENAME);
      return $query->result();
   }
   public function selectfor_posting($pdate, $pcurr)
   {
      $this->db->select("MEXRATE_CURR,MEXRATE_TYPE, MEXRATE_DT,MEXRATE_VAL");
      $this->db->where('MEXRATE_VAL > ', 0)
         ->where("MEXRATE_CURR", $pcurr)
         ->where("MEXRATE_DT", $pdate);
      $query = $this->db->get($this->TABLENAME);
      return $query->result();
   }
   public function selectfor_posting_in($pdate, $pcurr)
   {
      $this->db->select("MEXRATE_CURR,MEXRATE_TYPE, MEXRATE_DT,MEXRATE_VAL");
      $this->db->where('MEXRATE_VAL > ', 0)
         ->where_in("MEXRATE_CURR", $pcurr)
         ->where_in("MEXRATE_DT", $pdate);
      $query = $this->db->get($this->TABLENAME);
      return $query->result();
   }

   public function insert($data)
   {
      $this->db->insert($this->TABLENAME, $data);
      return $this->db->affected_rows();
   }
   public function updatebyId($pdata, $pwhere)
   {
      $this->db->where($pwhere);
      $this->db->update($this->TABLENAME, $pdata);
      return $this->db->affected_rows();
   }
   public function check_Primary($data)
   {
      return $this->db->get_where($this->TABLENAME, $data)->num_rows();
   }
   function select_minimum_lastupdate($where)
   {
      $this->db->select("CONVERT(DATE,MIN(MEXRATE_LUPDT)) MEXRATE_LUPDT")->where($where);
      $query = $this->db->get($this->TABLENAME);
      return $query->result_array();
   }
}
