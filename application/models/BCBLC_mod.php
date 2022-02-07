<?php

class BCBLC_mod extends CI_Model {	
	public function __construct()
    {
        $this->load->database();
    }
	public function selectAll()
	{		
        $DBUse = $this->load->database('rpcust',TRUE);
        $DBUse->select("KODE_DOKUMEN, KODE_TUJUAN_PENGIRIMAN, URAIAN_TUJUAN_PENGIRIMAN");
		$query = $DBUse->get($this->TABLENAME);
		return $query->result_array();
	}

    public function select_balance($pitem){        
        $qry  = "SELECT 
            rb.RPSTOCK_BCDATE,
            rb.RPSTOCK_BCTYPE,
            rb.RPSTOCK_BCNUM,
            RTRIM(rb.RPSTOCK_ITMNUM) RPSTOCK_ITMNUM,
            SUM(rb.RPSTOCK_QTY) AS TOTALINC,
            ro.TOTALOUT,
            ro.RPSTOCK_DOC AS OUT_DOC
        FROM PSI_RPCUST.dbo.RPSAL_BCSTOCK rb
        LEFT JOIN 
            (
                SELECT 
                    RPSTOCK_DOC,
                    RPSTOCK_BCTYPE,
                    RPSTOCK_BCNUM,
                    RPSTOCK_BCDATE,
                    RPSTOCK_ITMNUM,
                    RPSTOCK_DOINC,
                    SUM(RPSTOCK_QTY) AS TOTALOUT
                FROM PSI_RPCUST.dbo.RPSAL_BCSTOCK
                WHERE RPSTOCK_DOINC <> ''
                AND RPSTOCK_TYPE = 'INC-DO'
                GROUP BY
                    RPSTOCK_DOC,
                    RPSTOCK_BCTYPE,
                    RPSTOCK_BCNUM,
                    RPSTOCK_BCDATE,
                    RPSTOCK_ITMNUM,
                    RPSTOCK_DOINC
            ) ro ON ro.RPSTOCK_DOINC = rb.RPSTOCK_DOC
            AND ro.RPSTOCK_ITMNUM = rb.RPSTOCK_ITMNUM
        WHERE rb.RPSTOCK_TYPE = 'INC' and rb.RPSTOCK_ITMNUM like ? AND rb.RPSTOCK_BCDATE IS NOT NULL
        GROUP BY 
            rb.RPSTOCK_BCTYPE,
            rb.RPSTOCK_BCNUM,
            rb.RPSTOCK_BCDATE,
            rb.RPSTOCK_ITMNUM,
            ro.TOTALOUT,
            ro.RPSTOCK_DOC";
        $DBUse = $this->load->database('rpcust',TRUE);
        $query = $DBUse->query($qry, ["%$pitem%"] );
        return $query->result_array();
    }
}