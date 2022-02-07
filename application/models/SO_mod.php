<?php

class SO_mod extends CI_Model {
	private $TABLENAME = "SO_TBL";
	public function __construct()
    {
        $this->load->database();
    }

    public function insert($data)
    {        
        $this->db->insert($this->TABLENAME,$data);
        return $this->db->affected_rows();
    }

    public function lastserialid($pdoc){
        $qry = "select MAX(SO_LINE) lser from $this->TABLENAME 
        WHERE SO_NO=?";
        $query =  $this->db->query($qry, [$pdoc]);  
        if ($query->num_rows()>0){
            $ret = $query->row();
            return $ret->lser;
        } else {
            return 0;
        }
    }

    public function updatebyId($pdata, $pkeys)
    {
        $this->db->where($pkeys);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
    }

    public function check_Primary($data)
    {        
        return $this->db->get_where($this->TABLENAME,$data)->num_rows();
    }

    public function select_g_like($plike){
        $this->db->select('SO_NO,SO_BG,SO_CUSCD,SO_DELCD,MBSG_DESC,MCUS_CUSNM,MAX(SO_ORDRDT) SO_ORDRDT');
        $this->db->from($this->TABLENAME);
        $this->db->join('XMBSG_TBL', 'SO_BG=MBSG_BSGRP');
        $this->db->join('XMCUS', 'SO_CUSCD=MCUS_CUSCD');
        $this->db->like($plike);        
        $this->db->group_by('SO_NO,SO_BG,SO_CUSCD,SO_DELCD,MBSG_DESC,MCUS_CUSNM');
        $this->db->order_by('SO_ORDRDT');
		$query = $this->db->get();
		return $query->result_array();
    }
    public function select_all_where($pwhere){        
        $this->db->from($this->TABLENAME);        
        $this->db->where($pwhere);
        $this->db->order_by('SO_ITEMCD');
		$query = $this->db->get();
		return $query->result_array();
    }
    public function select_ost($pwhere){        
        $this->db->from('V_SO_OST');        
        $this->db->where($pwhere);
        $this->db->order_by('SO_ORDRDT,SO_ITEMCD');
		$query = $this->db->get();
		return $query->result_array();
    }
    public function select_ost_items($pwhere, $pItems){
        $this->db->from('V_SO_OST');        
        $this->db->where($pwhere)->where_in("SO_ITEMCD", $pItems);
        $this->db->order_by('SO_ORDRDT,SO_ITEMCD');
		$query = $this->db->get();
		return $query->result_array();
    }

    public function select_ost_withouttxid($pbg, $pcus, $pconsign, $ptxid) {
        $qry = "SELECT V1.*,ISNULL(TTLUSEQT,0) TTLUSEQT, (ORDQT-ISNULL(TTLUSEQT,0)) BALQT  FROM
        (select SO_NO,SO_BG,SO_CUSCD,SO_ITEMCD,SUM(SO_ORDRQT) ORDQT,SO_DELCD,MAX(SO_ORDRDT) SO_ORDRDT from SO_TBL
        GROUP BY SO_NO,SO_BG,SO_CUSCD,SO_ITEMCD,SO_DELCD) V1
        LEFT JOIN 
        (
            SELECT DLVSO_CPONO,DLVSO_ITMCD,SUM(DLVSOQT) TTLUSEQT,DLV_BSGRP,DLV_CUSTCD,DLV_CONSIGN FROM
            (SELECT DLV_ID,DLV_BSGRP, DLV_CUSTCD, DLV_CONSIGN FROM DLV_TBL
            WHERE DLV_ID!=?
            GROUP BY DLV_ID,DLV_BSGRP, DLV_CUSTCD, DLV_CONSIGN) VDELV
            INNER JOIN
            (
            SELECT DLVSO_DLVID,DLVSO_CPONO,DLVSO_ITMCD, SUM(DLVSO_QTY) DLVSOQT FROM DLVSO_TBL GROUP BY DLVSO_DLVID, DLVSO_ITMCD,DLVSO_CPONO
            ) VDELVSO ON DLV_ID=DLVSO_DLVID
            GROUP BY DLVSO_CPONO,DLVSO_ITMCD,DLV_BSGRP,DLV_CUSTCD,DLV_CONSIGN
        ) V2 ON SO_NO=DLVSO_CPONO AND SO_ITEMCD=DLVSO_ITMCD and SO_BG=DLV_BSGRP AND SO_CUSCD=DLV_CUSTCD AND SO_DELCD=DLV_CONSIGN
        WHERE ORDQT!=ISNULL(TTLUSEQT,0) and SO_BG=? and SO_CUSCD=? and SO_DELCD =?
        ORDER BY SO_ORDRDT,SO_ITEMCD";
         $query = $this->db->query($qry, [$ptxid, $pbg, $pcus, $pconsign ]);
         return $query->result_array();
    }

    public function delete_where($pwhere){          
        $this->db->where($pwhere);
        $this->db->delete($this->TABLENAME);
        return $this->db->affected_rows();        
    }

    public function select_ost_so_int_byitem($pitem){
        $qry ="SELECT VH1.*,DELQT,SO_ORDRQT-ISNULL(DELQT,0) OSTQT,RTRIM(MITM_ITMD1) ITMDESC FROM 
        (select SO_NO,SO_CUSCD,SO_DELCD,SO_ITEMCD,SUM(SO_ORDRQT) SO_ORDRQT,MIN(SO_ORDRDT) SO_ORDRDT from SO_TBL
        GROUP BY SO_NO,SO_ITEMCD,SO_CUSCD,SO_DELCD
        ) VH1
        LEFT JOIN
        (SELECT DLVSO_ITMCD,DLVSO_CPONO,DLV_CONSIGN,DLV_CUSTCD, SUM(DLVSO_QTY) DELQT FROM DLVSO_TBL 
        LEFT JOIN (SELECT DLV_ID,DLV_CONSIGN,DLV_CUSTCD FROM DLV_TBL GROUP BY DLV_ID,DLV_CONSIGN,DLV_CUSTCD) VDLV
        ON DLVSO_DLVID=DLV_ID
        GROUP BY DLVSO_ITMCD,DLVSO_CPONO,DLV_CONSIGN,DLV_CUSTCD) VH2 ON VH1.SO_NO=VH2.DLVSO_CPONO AND VH1.SO_CUSCD=VH2.DLV_CUSTCD
        AND VH1.SO_DELCD=VH2.DLV_CONSIGN AND VH1.SO_ITEMCD=VH2.DLVSO_ITMCD
        LEFT JOIN MITM_TBL ON VH1.SO_ITEMCD=MITM_ITMCD
        WHERE (SO_ORDRQT-ISNULL(DELQT,0))>0 AND VH1.SO_ITEMCD=?
        ORDER BY SO_ORDRDT";
        $query = $this->db->query($qry, [$pitem]);
        return $query->result_array();
    }
}