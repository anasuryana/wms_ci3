<?php

class PPO_mod extends CI_Model {
	private $TABLENAME = "XPPO2";
	public function __construct()
    {
        $this->load->database();
    }

	public function check_Primary($data)
    {        
        return $this->db->get_where($this->TABLENAME,$data)->num_rows();
    }
   

    public function select_os_business(){
        $qry = "SELECT DISTINCT RTRIM(MBSG_BSGRP) MBSG_BSGRP, RTRIM(MBSG_DESC) MBSG_DESC FROM
        (select PPO2_BSGRP,PPO2_SUPCD,PPO2_PONO,PPO2_ITMCD,sum(PPO2_POQTY) POQTY, SUM(PPO2_GRNQT) PPO2_GRNQT from XPPO2
        group by PPO2_BSGRP,PPO2_PONO,PPO2_ITMCD,PPO2_ISUDT,PPO2_SUPCD
        HAVING SUM(PPO2_POQTY) > SUM(PPO2_GRNQT)
        ) V1 LEFT JOIN XMBSG_TBL ON PPO2_BSGRP=MBSG_BSGRP
        order by MBSG_DESC";
        $query = $this->db->query($qry);
        return $query->result();
    }

    public function select_os_supplier($parLike) {
        $this->db->from("(SELECT DISTINCT RTRIM(MBSG_BSGRP) MBSG_BSGRP, RTRIM(MBSG_DESC) MBSG_DESC, RTRIM(MSUP_SUPCD) MSUP_SUPCD
        , RTRIM(MSUP_SUPNM) MSUP_SUPNM,MSUP_ADDR1,MSUP_SUPCR FROM
        (select PPO2_BSGRP,PPO2_SUPCD,PPO2_PONO,PPO2_ITMCD,sum(PPO2_POQTY) POQTY, SUM(PPO2_GRNQT) PPO2_GRNQT from XPPO2
        WHERE PPO2_COMFG='0'
        group by PPO2_BSGRP,PPO2_PONO,PPO2_ITMCD,PPO2_ISUDT,PPO2_SUPCD
        HAVING SUM(PPO2_POQTY) > SUM(PPO2_GRNQT)
        ) V1 LEFT JOIN XMBSG_TBL ON PPO2_BSGRP=MBSG_BSGRP
        LEFT JOIN XMSUP ON PPO2_SUPCD=PPO2_SUPCD) V2");
		$this->db->like($parLike);
        $this->db->order_by("MSUP_SUPNM");
		$query = $this->db->get();
		return $query->result();
    }

    public function select_os_item($parWhere, $parItem) {
        $this->db->from("(select PPO2_BSGRP,PPO2_SUPCD,RTRIM(PPO2_PONO) PPO2_PONO,PPO2_ISUDT,RTRIM(PPO2_ITMCD) PPO2_ITMCD,PPO2_PRPRC,sum(PPO2_POQTY) POQTY, SUM(PPO2_GRNQT) PPO2_GRNQT, sum(PPO2_POQTY)-SUM(PPO2_GRNQT)-ISNULL(PIGN_ICMQT,0) BALQTY from XPPO2
        LEFT JOIN (SELECT PIGN_BSGRP, PIGN_SUPCD, PIGN_PONO, PIGN_ITMCD,SUM(PIGN_ICMQT) PIGN_ICMQT FROM XPIGN GROUP BY PIGN_BSGRP, PIGN_SUPCD, PIGN_PONO, PIGN_ITMCD) V0 ON 
			PPO2_BSGRP = PIGN_BSGRP AND PPO2_SUPCD=PIGN_SUPCD AND PPO2_PONO=PIGN_PONO AND PPO2_ITMCD=PIGN_ITMCD
        WHERE PPO2_COMFG='0' 
        group by PPO2_BSGRP,PPO2_PONO,PPO2_ITMCD,PPO2_ISUDT,PPO2_SUPCD,PIGN_ICMQT,PPO2_PRPRC
        HAVING SUM(PPO2_POQTY) > SUM(PPO2_GRNQT)) V1");
        $this->db->join('MITM_TBL', "PPO2_ITMCD=MITM_ITMCD", "LEFT");
		$this->db->where($parWhere)->where_in("PPO2_ITMCD", $parItem);
        $this->db->select("V1.*, RTRIM(MITM_ITMD1) MITM_ITMD1");
        $this->db->order_by("PPO2_ISUDT");
		$query = $this->db->get();
		return $query->result_array();
    }
}