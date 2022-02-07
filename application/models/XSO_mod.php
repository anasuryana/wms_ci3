<?php

class XSO_mod extends CI_Model {
	private $TABLENAME = "XSSO2";
	public function __construct()
    {
        $this->load->database();
    }		

    public function selectall(){
        $this->db->from($this->TABLENAME);
		$query = $this->db->get();
		return $query->result();
    }

    public function selectbyVAR($cols, $dataw){
        $this->db->select($cols);
        $this->db->from($this->TABLENAME);
        $this->db->where($dataw);
		$query = $this->db->get();
		return $query->result();
    }
    public function selectbyVAR_inmodel($cols, $dataw, $models){
        $this->db->select($cols);
        $this->db->from($this->TABLENAME);        
        $this->db->where($dataw);
        $this->db->where_in('SSO2_MDLCD', $models);
        $this->db->order_by('SSO2_MDLCD ASC,SSO2_DELDT ASC,SSO2_CPONO ASC, SSO2_SOLNO ASC');
		$query = $this->db->get();
		return $query->result_array();
    }
    public function selectbyVAR_inmodel_lastso($cols, $models){
        $this->db->select($cols);
        $this->db->from($this->TABLENAME." A");
        $this->db->where("YEAR(SSO2_ISUDT)=YEAR(GETDATE())", NULL);
        $this->db->where("MONTH(SSO2_ISUDT)=MONTH(GETDATE())", NULL);
        // $this->db->where("SSO2_ISUDT=(select max(SSO2_ISUDT) FROM XSSO2 B WHERE B.SSO2_MDLCD=A.SSO2_MDLCD)", NULL);
        $this->db->where_in('SSO2_MDLCD', $models);
        $this->db->order_by('SSO2_MDLCD ASC,SSO2_DELDT ASC,SSO2_CPONO ASC, SSO2_SOLNO ASC');
		$query = $this->db->get();
		return $query->result_array();
    }

    public function select_latestprice($pbg, $pcustomer, $models){
        $qry = "SELECT	A.MSPR_BSGRP, A.MSPR_CUSCD, A.MSPR_CURCD, MCUS_CUSNM, UPPER(rtrim(A.MSPR_ITMCD)) MSPR_ITMCD, A.MSPR_BOMRV,
                    A.MSPR_EFFDT, MITM_ITMD1 AS ITMCD_DESC, A.MSPR_SLPRC
                FROM	SRVMEGA.PSI_MEGAEMS.dbo.MSPR_TBL A
                LEFT JOIN SRVMEGA.PSI_MEGAEMS.dbo.MCUS_TBL	ON	MCUS_CURCD = A.MSPR_CURCD AND MCUS_CUSCD = A.MSPR_CUSCD
                LEFT JOIN SRVMEGA.PSI_MEGAEMS.dbo.MITM_TBL	ON	MITM_ITMCD = A.MSPR_ITMCD
                LEFT JOIN SRVMEGA.PSI_MEGAEMS.dbo.MBSG_TBL	ON	MBSG_BSGRP = A.MSPR_BSGRP
                WHERE	A.MSPR_BSGRP = ?
                AND A.MSPR_ITMCD in ($models)
                AND		MSPR_CUSCD = ?
                AND		A.MSPR_EFFDT = (SELECT	MAX(B.MSPR_EFFDT)
                                        FROM	SRVMEGA.PSI_MEGAEMS.dbo.MSPR_TBL B
                                        WHERE	B.MSPR_BSGRP = A.MSPR_BSGRP
                                        AND		B.MSPR_CUSCD = A.MSPR_CUSCD
                                        AND		B.MSPR_CURCD = A.MSPR_CURCD
                                        AND		B.MSPR_ITMCD = A.MSPR_ITMCD
                                        AND		B.MSPR_BOMRV = A.MSPR_BOMRV)
		ORDER BY MSPR_BSGRP, MSPR_CUSCD, MSPR_CURCD, MSPR_ITMCD, MSPR_BOMRV, MSPR_EFFDT";
        $query =  $this->db->query($qry, [$pbg, $pcustomer]);
		return $query->result_array();
    }
    public function select_latestprice_period($pbg, $pcustomer, $models, $pyear, $pmonth){
        $qry = "SELECT	A.MSPR_BSGRP, A.MSPR_CUSCD, A.MSPR_CURCD, MCUS_CUSNM, UPPER(rtrim(A.MSPR_ITMCD)) MSPR_ITMCD, A.MSPR_BOMRV,
                    A.MSPR_EFFDT, MITM_ITMD1 AS ITMCD_DESC, A.MSPR_SLPRC
                FROM XMSPR A
                LEFT JOIN SRVMEGA.PSI_MEGAEMS.dbo.MCUS_TBL	ON	MCUS_CURCD = A.MSPR_CURCD AND MCUS_CUSCD = A.MSPR_CUSCD
                LEFT JOIN SRVMEGA.PSI_MEGAEMS.dbo.MITM_TBL	ON	MITM_ITMCD = A.MSPR_ITMCD
                LEFT JOIN SRVMEGA.PSI_MEGAEMS.dbo.MBSG_TBL	ON	MBSG_BSGRP = A.MSPR_BSGRP
                WHERE	A.MSPR_BSGRP = ?
                AND A.MSPR_ITMCD in ($models)
                AND		MSPR_CUSCD = ?
                AND		A.MSPR_EFFDT = (SELECT	MAX(B.MSPR_EFFDT)
                                        FROM	XMSPR B
                                        WHERE	B.MSPR_BSGRP = A.MSPR_BSGRP
                                        AND		B.MSPR_CUSCD = A.MSPR_CUSCD
                                        AND		B.MSPR_CURCD = A.MSPR_CURCD
                                        AND		B.MSPR_ITMCD = A.MSPR_ITMCD
                                        AND		B.MSPR_BOMRV = A.MSPR_BOMRV
                                        AND YEAR(B.MSPR_EFFDT)=? AND MONTH(B.MSPR_EFFDT)=?)
		ORDER BY MSPR_BSGRP, MSPR_CUSCD, MSPR_CURCD, MSPR_ITMCD, MSPR_BOMRV, MSPR_EFFDT";
        $query =  $this->db->query($qry, [$pbg, $pcustomer, $pyear, $pmonth]);
		return $query->result_array();
    }
    public function select_latestprice_bymodels($models){
        $qry = "SELECT	A.MSPR_BSGRP, A.MSPR_CUSCD, A.MSPR_CURCD, MCUS_CUSNM, rtrim(A.MSPR_ITMCD) MSPR_ITMCD, A.MSPR_BOMRV,
                    A.MSPR_EFFDT, MITM_ITMD1 AS ITMCD_DESC, A.MSPR_SLPRC
                FROM	SRVMEGA.PSI_MEGAEMS.dbo.MSPR_TBL A
                LEFT JOIN SRVMEGA.PSI_MEGAEMS.dbo.MCUS_TBL	ON	MCUS_CURCD = A.MSPR_CURCD AND MCUS_CUSCD = A.MSPR_CUSCD
                LEFT JOIN SRVMEGA.PSI_MEGAEMS.dbo.MITM_TBL	ON	MITM_ITMCD = A.MSPR_ITMCD
                LEFT JOIN SRVMEGA.PSI_MEGAEMS.dbo.MBSG_TBL	ON	MBSG_BSGRP = A.MSPR_BSGRP
                WHERE	A.MSPR_ITMCD in ($models)                
                AND		A.MSPR_EFFDT = (SELECT	MAX(B.MSPR_EFFDT)
                                        FROM	SRVMEGA.PSI_MEGAEMS.dbo.MSPR_TBL B
                                        WHERE	B.MSPR_BSGRP = A.MSPR_BSGRP
                                        AND		B.MSPR_CUSCD = A.MSPR_CUSCD
                                        AND		B.MSPR_CURCD = A.MSPR_CURCD
                                        AND		B.MSPR_ITMCD = A.MSPR_ITMCD
                                        AND		B.MSPR_BOMRV = A.MSPR_BOMRV)
		ORDER BY MSPR_BSGRP, MSPR_CUSCD, MSPR_CURCD, MSPR_ITMCD, MSPR_BOMRV, MSPR_EFFDT";
        $query =  $this->db->query($qry);
		return $query->result_array();
    }

    public function select_epro_customer(){
        $this->db->from('XEPROCUSTOMER_V');
        $this->db->order_by('MCUS_CUSNM asc');
		$query = $this->db->get();
		return $query->result();
    }

    public function selectcustomer_ost_so($pbg)
	{		
        $qry = "exec sp_customer_ost_so ?";
		$query =  $this->db->query($qry, [$pbg]);
		return $query->result_array();
    }
    public function selectcustomer_so($pbg)
	{		
        $qry = "exec sp_customer_so ?";        
		$query =  $this->db->query($qry, [$pbg]);    
		return $query->result_array();
    }

    public function select_ost_so_perItem(){
        $qry = "select rtrim(SSO2_MDLCD) MDLCD,sum(SSO2_ORDQT) ORDQT, SUM(SSO2_DELQT) DELQT,sum(SSO2_ORDQT)-SUM(SSO2_DELQT) OSTQT, MAX(SSO2_SLPRC) SLPRC
        from XSSO2 where SSO2_CFMPRC='Y' and SSO2_COMFG!='1' AND SSO2_CPOTYPE='CPO'
        group by SSO2_MDLCD
        ORDER BY 1";
        $query =  $this->db->query($qry);
		return $query->result_array();
    }

    public function select_model($pwhere){
        $this->db->select('SSO2_MDLCD,MITM_ITMD1,MITM_STKUOM'); 
        $this->db->from($this->TABLENAME);
        $this->db->join("MITM_TBL", "SSO2_MDLCD=MITM_ITMCD");
        $this->db->group_by("SSO2_MDLCD, MITM_ITMD1,MITM_STKUOM");
        $this->db->where($pwhere);
		$query = $this->db->get();
		return $query->result_array();
    }
   
}