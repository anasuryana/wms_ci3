<?php

class PSV_mod extends CI_Model 
{
   private $TABLENAME = "STK_TBL";
   private $DBUse = NULL;
   public function __construct()
   {
      $this->load->database();
      $this->DBUse = $this->load->database('iei',TRUE);
   }
   
   public function insert($data)
   {
      $this->DBUse->insert($this->TABLENAME,$data);
      return $this->DBUse->insert_id();
   }
   
   public function selectAll()
   {
      $query = $this->DBUse->get($this->TABLENAME);
      return $query->result_array();
   }

   public function select_where($pcols, $pwhere)
   {
      $this->DBUse->select($pcols);
      $this->DBUse->from($this->TABLENAME);
      $this->DBUse->where($pwhere);
      $query = $this->DBUse->get();
      return $query->result_array();
   }

   public function check_Primary($data)
   {
      return $this->DBUse->get_where($this->TABLENAME,$data)->num_rows();
   }

   public function insert_stock()
   {
      $qry = "INSERT INTO SRVPSV.PSV.dbo.STK_TBL
               SELECT 
               GETDATE() AS_OF_DATE_TIME,
               RTRIM(FTRN_ITMCD) PART_CODE,
               RTRIM(FTRN_BSGRP) PLANT,
               RTRIM(FTRN_LOCCD) STORAGE_LOCATION,
               sum(IOQT) AVAILABLE_STOCK,
               0 BLOCKED_STOCK,
               MAX(FTRN_LUPDT) LAST_MODIFIED
               FROM XFTRN_TBL
               WHERE FTRN_ISUDT<=CONVERT(DATE,GETDATE()) AND FTRN_BSGRP='PSI1PPZIEP' AND FTRN_LOCCD='AFWH3'
               GROUP BY FTRN_ITMCD,FTRN_BSGRP,FTRN_LOCCD
         ";
      $this->db->query($qry);
      return $this->db->affected_rows();
   }

   function delete_stock()
   {
      $qry = "DELETE FROM STK_TBL
              WHERE AS_OF_DATE_TIME=(SELECT min(AS_OF_DATE_TIME) FROM STK_TBL)";
      $this->DBUse->query($qry);
      return $this->DBUse->affected_rows();
   }

   function truncate_stock()
   {
      $qry = "TRUNCATE TABLE ".$this->TABLENAME;
      $this->DBUse->query($qry);
      return $this->DBUse->affected_rows();
   }

   function reindex()
   {
      $qry = "ALTER INDEX PK__STK_TBL__0837B67847244174 on STK_TBL
               REBUILD";
      $this->DBUse->query($qry);
   }
}