<?php

class MSTITM_mod extends CI_Model {
	private $TABLENAME = "MITM_TBL";
	private $TABLENAMEGRADE = "MITMGRP_TBL";	
	private $VIEW_SUBASSY = "VSUBASSY";
	private $VIEW_FG_AS_BOM = "VFG_AS_BOM";
	public function __construct()
    {
        $this->load->database();
    }
	public function selectAll()
	{
		$this->db->select("MITM_ITMCD,MITM_ITMD1,MITM_ITMD2,MITM_MODEL,MITM_SPTNO");
		$query = $this->db->get($this->TABLENAME);
		return $query->result();
	}
	public function selectAllGrade($pColumns)
	{
		$this->db->select($pColumns);
		$query = $this->db->get($this->TABLENAMEGRADE);
		return $query->result_array();
	}
	
	public function select_fg(){
		$this->db->limit(10000);
		$this->db->select("MITM_ITMCD");
		$this->db->where('MITM_MODEL', '1');
		$query = $this->db->get($this->TABLENAME);
		return $query->result();
	}

	public function selectnamebyid($pid){
		$this->db->select("MITM_SPTNO");
		$this->db->where('MITM_ITMCD', $pid);
		$query = $this->db->get($this->TABLENAME);
		return $query->result_array();
	}

	public function selectAll_subassy(){		
		$query = $this->db->get($this->VIEW_SUBASSY);
		return $query->result_array();
	}

	public function check_Primary_subassy($data)
    {        
        return $this->db->get_where($this->VIEW_SUBASSY,$data)->num_rows();
	}
	public function check_Primary_FG_AS_BOM($data)
    {        
        return $this->db->get_where($this->VIEW_FG_AS_BOM,$data)->num_rows();
	}
	
	public function selectbyid($pid)
	{
		$this->db->select("RTRIM(MITM_ITMCD) MITM_ITMCD,RTRIM(MITM_ITMD1) MITM_ITMD1,RTRIM(MITM_ITMD2) MITM_ITMD2, RTRIM(MITM_SPTNO) MITM_SPTNO");
		$this->db->where('MITM_ITMCD', $pid);
		$query = $this->db->get($this->TABLENAME);
		return $query->result();
	}
	public function selectbyid_mega($pid)
	{
		$this->db->select("MITM_ITMCD,MITM_ITMD1,MITM_ITMD2,MITM_SPTNO");
		$this->db->where('MITM_ITMCD', $pid);
		$query = $this->db->get('XMITM_TBL');
		return $query->result();
	}
	
	public function selectbyid_lk($pid)
	{
		$this->db->select("MITM_ITMCD,MITM_ITMD1,MITM_ITMD2,MITM_SPTNO,MITM_MODEL,MITM_INDIRMT,MITM_HSCD,MITM_HSCODET, COALESCE(MITM_GWG,0) MITM_GWG, COALESCE(MITM_NWG,0) MITM_NWG,
		MITM_SPQ,MITM_BOXTYPE,COALESCE(MITM_MXHEIGHT,0) MITM_MXHEIGHT,COALESCE(MITM_MXLENGTH,0) MITM_MXLENGTH, COALESCE(MITM_LBLCLR,'') MITM_LBLCLR, coalesce(MITM_SHTQTY,0) MITM_SHTQTY
		,ISNULL(MITM_NCAT,'') MITM_NCAT, MMDL_NM");
		$this->db->join("MMDL_TBL", "MITM_MODEL=MMDL_CD","LEFT");
		$this->db->like('MITM_ITMCD', $pid);
		$query = $this->db->get($this->TABLENAME,1000);
		return $query->result();
	}
	public function selectbynm_lk($pid)
	{
		$this->db->select("MITM_ITMCD,MITM_ITMD1,MITM_ITMD2,MITM_SPTNO,MITM_MODEL,MITM_INDIRMT,MITM_HSCD,MITM_HSCODET, COALESCE(MITM_GWG,0) MITM_GWG, COALESCE(MITM_NWG,0) MITM_NWG,
		MITM_SPQ,MITM_BOXTYPE,COALESCE(MITM_MXHEIGHT,0) MITM_MXHEIGHT,COALESCE(MITM_MXLENGTH,0) MITM_MXLENGTH, COALESCE(MITM_LBLCLR,'') MITM_LBLCLR, coalesce(MITM_SHTQTY,0) MITM_SHTQTY
		,ISNULL(MITM_NCAT,'') MITM_NCAT");
		$this->db->like('MITM_ITMD1', $pid);
		$query = $this->db->get($this->TABLENAME,10);
		return $query->result();
	}

	public function selectbyspt_lk($pid)
	{
		$this->db->select("MITM_ITMCD,MITM_ITMD1,MITM_ITMD2,MITM_SPTNO,MITM_MODEL,MITM_INDIRMT,MITM_HSCD,MITM_HSCODET, COALESCE(MITM_GWG,0) MITM_GWG, COALESCE(MITM_NWG,0) MITM_NWG,
		MITM_SPQ,MITM_BOXTYPE,COALESCE(MITM_MXHEIGHT,0) MITM_MXHEIGHT,COALESCE(MITM_MXLENGTH,0) MITM_MXLENGTH, COALESCE(MITM_LBLCLR,'') MITM_LBLCLR, coalesce(MITM_SHTQTY,0) MITM_SHTQTY
		,ISNULL(MITM_NCAT,'') MITM_NCAT");
		$this->db->like('MITM_SPTNO', $pid);
		$query = $this->db->get($this->TABLENAME,10);
		return $query->result();
	}
	public function selectby_lk($plike)
	{
		$this->db->select("MITM_ITMCD,MITM_ITMD1,MITM_ITMD2,MITM_SPTNO,MITM_MODEL,MITM_INDIRMT,MITM_HSCD,MITM_HSCODET, COALESCE(MITM_GWG,0) MITM_GWG, COALESCE(MITM_NWG,0) MITM_NWG,
		MITM_SPQ,MITM_BOXTYPE,COALESCE(MITM_MXHEIGHT,0) MITM_MXHEIGHT,COALESCE(MITM_MXLENGTH,0) MITM_MXLENGTH, COALESCE(MITM_LBLCLR,'') MITM_LBLCLR, coalesce(MITM_SHTQTY,0) MITM_SHTQTY,
		ISNULL(MITM_NCAT,'') MITM_NCAT, MMDL_NM, ISNULL(MITM_ITMCDCUS,'-') MITM_ITMCDCUS");
		$this->db->join("MMDL_TBL", "MITM_MODEL=MMDL_CD","LEFT");
		$this->db->like($plike);
		$query = $this->db->get($this->TABLENAME,500);
		return $query->result();
	}

	public function selectdiff(){
		$qry = "select rtrim(MITM_ITMCD) MITM_ITMCD  from XMITM_V
		where MITM_ITMCD not in (select MITM_ITMCD from MITM_TBL)";
		$resq = $this->db->query($qry);
		return $resq->result_array();
	}
	public function selectdiff_grade(){
		$qry = "select v1.* from
		(select RTRIM(PGRELED_LEDGRP) PGRELED_LEDGRP , RTRIM(PGRELED_ITMCD) PGRELED_ITMCD from XPGRELED_VIEW
		GROUP BY PGRELED_LEDGRP , PGRELED_ITMCD) v1
		left join MITMGRP_TBL on PGRELED_LEDGRP=MITMGRP_ITMCD and PGRELED_ITMCD=MITMGRP_ITMCD_GRD
		WHERE MITMGRP_ITMCD IS NULL";
		$resq = $this->db->query($qry);
		return $resq->result_array();
	}

	public function selectother(){
		$qry = "select MITM_ITMCD from MITM_TBL
		where MITM_ITMCD not in (select MITM_ITMCD from XMITM_V)";
		$resq = $this->db->query($qry);
		return $resq->result_array();
	}

	public function update_all_d1(){
		$qry = "UPDATE MITM_TBL SET MITM_TBL.MITM_ITMD1=p.MITM_ITMD1, MITM_TBL.MITM_SPTNO=p.MITM_SPTNO
		,MITM_TBL.MITM_STKUOM=p.MITM_STKUOM,MITM_TBL.MITM_INDIRMT=p.MITM_INDIRMT
		FROM MITM_TBL s
		INNER JOIN XMITM_V p on s.MITM_ITMCD=p.MITM_ITMCD
		WHERE s.MITM_ITMD1!= p.MITM_ITMD1 or s.MITM_SPTNO!=p.MITM_SPTNO or s.MITM_STKUOM!=p.MITM_STKUOM
		or s.MITM_INDIRMT!=p.MITM_INDIRMT";
		$resq = $this->db->query($qry);
        return $this->db->affected_rows();
	}
	public function selectboxes($pkeys){
		$qry = "select DISTINCT MITM_BOXTYPE from MITM_TBL WHERE MITM_BOXTYPE LIKE ?";
		$resq = $this->db->query($qry , array('%'.$pkeys.'%'));
		return $resq->result_array();
	}
	public function select_bom_mega_cims($passy, $prev){
		$qry = "SELECT VALL.*,MG.MITM_SPTNO MG_SPTNO,CM.MITM_SPTNO CM_SPTNO FROM
		(SELECT VCIMS.*,MBOM_BOMPN,MBOM_PARQT,MBOM_MDLCD,MBOM_BOMRV FROM 
		(SELECT MBOM_MDLCD MBLA_MDLCD, MBOM_BOMRV MBLA_BOMRV,MBOM_ITMCD MBLA_MITMCD,COUNT(*) CIMSPER FROM VCIMS_MBOM_TBL WHERE MBOM_MDLCD=? AND MBOM_BOMRV=? AND MBOM_LOCCD!=''
		group by MBOM_MDLCD,MBOM_BOMRV,MBOM_ITMCD) VCIMS
		FULL OUTER JOIN (SELECT * FROM XMBOM WHERE MBOM_MDLCD=? AND MBOM_BOMRV=?) VMEGA ON MBLA_MDLCD=MBOM_MDLCD AND MBLA_BOMRV=MBOM_BOMRV AND MBLA_MITMCD=MBOM_BOMPN
		) VALL
		LEFT JOIN XMITM_V MG ON ISNULL(MBOM_BOMPN,'')=MG.MITM_ITMCD
		LEFT JOIN XMITM_V CM ON ISNULL(MBLA_MITMCD,'')=CM.MITM_ITMCD
		order by MBOM_BOMPN,MBLA_MITMCD";
		$resq = $this->db->query($qry , [$passy, $prev,$passy, $prev]);
		return $resq->result_array();
	}

	public function insertsync($pid)
    {        
		$qry = "INSERT INTO MITM_TBL (MITM_ITMCD,MITM_ITMD1
			  ,MITM_ITMD2
			  ,MITM_ITMTY
			  ,MITM_ITMFG
			  ,MITM_MODEL
			  ,MITM_STKUOM
			  ,MITM_MOQ
			  ,MITM_SPQ
			  ,MITM_MINLV
			  ,MITM_MAXLV
			  ,MITM_DICHK
			  ,MITM_BUFPC
			  ,MITM_FRRTE
			  ,MITM_PLTDAY
			  ,MITM_ETALT
			  ,MITM_SUPCD
			  ,MITM_SUPCR
			  ,MITM_SPTNO
			  ,MITM_PUPRC
			  ,MITM_MKECD
			  ,MITM_GRSWG
			  ,MITM_NETWG
			  ,MITM_WGTUM
			  ,MITM_STRUOM
			  ,MITM_LBCTL
			  ,MITM_RJPAT
			  ,MITM_NOUBL
			  ,MITM_LIFE
			  ,MITM_SPQFG
			  ,MITM_LUPDT
			  ,MITM_REM1
			  ,MITM_REM2
			  ,MITM_REM3
			  ,MITM_FSFLG
			  ,MITM_HSCD
			  ,MITM_SECCD
			  ,MITM_PSNCD
			  ,MITM_USRID
			  ,MITM_HUBFG
			  ,MITM_EHSUP
			  ,MITM_ROHS1
			  ,MITM_ROHS2
			  ,MITM_ROHS3
			  ,MITM_ROHS4
			  ,MITM_ROHS5
			  ,MITM_ROHS6
			  ,MITM_ROHSALL
			  ,MITM_ROHSNOW
			  ,MITM_PCAT1
			  ,MITM_PCAT2
			  ,MITM_STXCD
			  ,MITM_PGRP
			  ,MITM_ITMKY
			  ,MITM_MNMCD
			  ,MITM_LOTNOCTL
			  ,MITM_C3LBCTL
			  ,MITM_CEIFG
			  ,MITM_CEIAPPLYNO
			  ,MITM_CEIAPPLYDT
			  ,MITM_ACTIVE
			  ,MITM_RQRLSNO
			  ,MITM_PRGID
			  ,MITM_ORIGIN
			  ,MITM_TARIFF
			  ,MITM_CIMITMCD
			  ,MITM_GRADE
			  ,MITM_ISLED
			  ,MITM_LEDGRP
			  ,MITM_PODCD
			  ,MITM_POUOM
			  ,MITM_INDIRMT)
		
		SELECT  MITM_ITMCD, MITM_ITMD1
			  ,MITM_ITMD2
			  ,MITM_ITMTY
			  ,MITM_ITMFG
			  ,MITM_MODEL
			  ,MITM_STKUOM
			  ,MITM_MOQ
			  ,MITM_SPQ
			  ,MITM_MINLV
			  ,MITM_MAXLV
			  ,MITM_DICHK
			  ,MITM_BUFPC
			  ,MITM_FRRTE
			  ,MITM_PLTDAY
			  ,MITM_ETALT
			  ,MITM_SUPCD
			  ,MITM_SUPCR
			  ,MITM_SPTNO
			  ,MITM_PUPRC
			  ,MITM_MKECD
			  ,MITM_GRSWG
			  ,MITM_NETWG
			  ,MITM_WGTUM
			  ,MITM_STRUOM
			  ,MITM_LBCTL
			  ,MITM_RJPAT
			  ,MITM_NOUBL
			  ,MITM_LIFE
			  ,MITM_SPQFG
			  ,MITM_LUPDT
			  ,MITM_REM1
			  ,MITM_REM2
			  ,MITM_REM3
			  ,MITM_FSFLG
			  ,MITM_HSCD
			  ,MITM_SECCD
			  ,MITM_PSNCD
			  ,MITM_USRID
			  ,MITM_HUBFG
			  ,MITM_EHSUP
			  ,MITM_ROHS1
			  ,MITM_ROHS2
			  ,MITM_ROHS3
			  ,MITM_ROHS4
			  ,MITM_ROHS5
			  ,MITM_ROHS6
			  ,MITM_ROHSALL
			  ,MITM_ROHSNOW
			  ,MITM_PCAT1
			  ,MITM_PCAT2
			  ,MITM_STXCD
			  ,MITM_PGRP
			  ,MITM_ITMKY
			  ,MITM_MNMCD
			  ,MITM_LOTNOCTL
			  ,MITM_C3LBCTL
			  ,MITM_CEIFG
			  ,MITM_CEIAPPLYNO
			  ,MITM_CEIAPPLYDT
			  ,MITM_ACTIVE
			  ,MITM_RQRLSNO
			  ,MITM_PRGID
			  ,MITM_ORIGIN
			  ,MITM_TARIFF
			  ,MITM_CIMITMCD
			  ,MITM_GRADE
			  ,MITM_ISLED
			  ,MITM_LEDGRP
			  ,MITM_PODCD
			  ,MITM_POUOM
			  ,MITM_INDIRMT FROM XMITM_V WHERE MITM_ITMCD='".$pid."' ";
		$resq = $this->db->query($qry);
        return $this->db->affected_rows();
	}

	public function insertGrade($data) {
		$this->db->insert($this->TABLENAMEGRADE,$data);
        return $this->db->affected_rows();
	}
	public function insert($data)
    {        
        $this->db->insert($this->TABLENAME,$data);
        return $this->db->affected_rows();
    }
	public function check_Primary($data)
    {        
        return $this->db->get_where($this->TABLENAME,$data)->num_rows();
	}
	public function check_PrimaryGrade($data)
    {        
        return $this->db->get_where($this->TABLENAMEGRADE,$data)->num_rows();
	}
	
	public function updatebyId($pdata, $pkey)
    { 
        $this->db->where('MITM_ITMCD',$pkey);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
	}
	
	public function selectfgbyid_lk($pid)
	{
		$this->db->select("MITM_ITMCD,MITM_ITMD1,MITM_ITMD2,MITM_SPTNO,MITM_MODEL,MITM_INDIRMT,MITM_HSCD,MITM_HSCODET");
		$this->db->like('MITM_ITMCD', $pid)->where('MITM_MODEL', '1');
		$query = $this->db->get($this->TABLENAME,10);
		return $query->result();
	}
	public function selectfgbynm_lk($pid)
	{
		$this->db->select("MITM_ITMCD,MITM_ITMD1,MITM_ITMD2,MITM_SPTNO,MITM_MODEL,MITM_INDIRMT,MITM_HSCD,MITM_HSCODET");
		$this->db->like('MITM_ITMD1', $pid)->where('MITM_MODEL', '1');;
		$query = $this->db->get($this->TABLENAME,10);
		return $query->result();
	}

	public function selectfgbyspt_lk($pid)
	{
		$this->db->select("MITM_ITMCD,MITM_ITMD1,MITM_ITMD2,MITM_SPTNO,MITM_MODEL,MITM_INDIRMT,MITM_HSCD,MITM_HSCODET");
		$this->db->like('MITM_SPTNO', $pid)->where('MITM_MODEL', '1');;
		$query = $this->db->get($this->TABLENAME,10);
		return $query->result();
	}

	public function selecthscodemega(){
		$qry = "select MITM_ITMCD,MITM_HSCD,MITM_SPTNO,MITM_ITMD1,MITM_STKUOM from XMITM_V
		";
		$resq = $this->db->query($qry);
		return $resq->result_array();
	}

	public function z_selectshtqty(){
		$qry = "select * from LABELSHEET";
		$resq = $this->db->query($qry);
		return $resq->result_array();
	}

	public function select_item_fg(){
		$this->db->select("rtrim(MITM_ITMCD) MITM_ITMCD");
		$this->db->where('MITM_MODEL', '1');
		$query = $this->db->get($this->TABLENAME);
		return $query->result();
	}

	public function select_item_oldsys()
	{			
		$query = $this->db->get('msitem_oldsys');
		return $query->result();
	}

	public function select_fg_exim($pwhere){
		$this->db->select("rtrim(MITM_ITMCD) MITM_ITMCD, RTRIM(MITM_ITMD1) MITM_ITMD1, MITM_GWG, MITM_NWG
		, ISNULL(MITM_HSCD,'') MITM_HSCD, MITM_BM, MITM_PPN, MITM_PPH,MITM_BOXWEIGHT");
		$this->db->where('MITM_MODEL', '1')->like($pwhere);
		$this->db->order_by("MITM_ITMCD");
		$query = $this->db->get($this->TABLENAME);
		return $query->result_array();
	}
	public function select_rm_exim($pwhere){
		$this->db->select("rtrim(MITM_ITMCD) MITM_ITMCD, RTRIM(MITM_ITMD1) MITM_ITMD1, MITM_GWG, MITM_NWG
		, ISNULL(MITM_HSCD,'') MITM_HSCD, MITM_BM, MITM_PPN, MITM_PPH");
		$this->db->where_in('MITM_MODEL', [0,8,6])->like($pwhere);
		$this->db->order_by("MITM_ITMCD");
		$query = $this->db->get($this->TABLENAME);
		return $query->result_array();
	}

	public function select_columns_like($columns, $like) {
		$this->db->select($columns);
		$this->db->like($like);
		$this->db->order_by('MITM_ITMCD');
		$query = $this->db->get($this->TABLENAME);
		return $query->result();
	}

	public function select_category(){
		$this->db->select("ISNULL(MITM_NCAT,'') MITM_NCAT");
		$this->db->group_by('MITM_NCAT');
		$this->db->order_by("MITM_NCAT");
		$query = $this->db->get($this->TABLENAME);
		return $query->result_array();
	}
	public function select_forcustoms($pitems){
		$this->db->select("rtrim(MITM_ITMCD) MITM_ITMCD,MITM_HSCD,MITM_BM,MITM_PPN,MITM_PPH,RTRIM(MITM_SPTNO) MITM_SPTNO,RTRIM(MITM_ITMD1) MITM_ITMD1");
		$this->db->where_in("MITM_ITMCD", $pitems);
		$query = $this->db->get($this->TABLENAME);
		return $query->result_array();
	}

	public function select_lastnopm($pconsign){
		$qry = "select ISNULL(MAX(MITM_PMNO),0)+1 LPMNO from MITM_TBL where MITM_MODEL='6' 
		AND MITM_ITMCD LIKE 'PM%' AND YEAR(MITM_PMREGDT)=YEAR(GETDATE())
		and MITM_PMCONSIGN=?";
		$query =  $this->db->query($qry, [$pconsign]);
		if ($query->num_rows()>0){
            $ret = $query->row();
            return $ret->LPMNO;
        } else {
            return 1;
        }		        
	}
}