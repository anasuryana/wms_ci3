<?php

class PND_mod extends CI_Model
{
    private $TABLENAME = "PND_TBL";
    public function __construct()
    {
        $this->load->database();
    }

    public function insert($data)
    {
        $this->db->insert($this->TABLENAME, $data);
        return $this->db->affected_rows();
    }
    public function insertser($data)
    {
        $this->db->insert('PNDSER_TBL', $data);
        return $this->db->affected_rows();
    }
    public function insertb($data)
    {
        $this->db->insert_batch($this->TABLENAME, $data);
        return $this->db->affected_rows();
    }
    public function insertbser($data)
    {
        $this->db->insert_batch('PNDSER_TBL', $data);
        return $this->db->affected_rows();
    }
    public function check_Primary($data)
    {
        return $this->db->get_where($this->TABLENAME, $data)->num_rows();
    }
    public function check_Primary_ser($data)
    {
        return $this->db->get_where('PNDSER_TBL', $data)->num_rows();
    }

    public function updatebyId($pdata, $pkeys)
    {
        $this->db->where($pkeys);
        $this->db->update($this->TABLENAME, $pdata);
        return $this->db->affected_rows();
    }

    public function updateserbyId($pdata, $pkeys)
    {
        $this->db->where($pkeys);
        $this->db->update('PNDSER_TBL', $pdata);
        return $this->db->affected_rows();
    }
    public function lastserialid()
    {
        $qry = "select TOP 1 substring(PND_DOC, 12, 3) lser from PND_TBL
        WHERE convert(date, PND_LUPDT) = convert(date,GETDATE())
        ORDER BY convert(bigint,SUBSTRING(PND_DOC,12,3)) desc";
        $query = $this->db->query($qry);
        if ($query->num_rows() > 0) {
            $ret = $query->row();
            return $ret->lser;
        } else {
            return '0';
        }
    }
    public function lastserialidser()
    {
        $qry = "select TOP 1 substring(PNDSER_DOC, 13, 3) lser from PNDSER_TBL
        WHERE convert(date, PNDSER_LUPDT) = convert(date,GETDATE())
        ORDER BY convert(bigint,SUBSTRING(PNDSER_DOC,13,3)) desc";
        $query = $this->db->query($qry);
        if ($query->num_rows() > 0) {
            $ret = $query->row();
            return $ret->lser;
        } else {
            return '0';
        }
    }

    public function selectAllg_byVAR($plike)
    {
        $this->db->limit(10);
        $this->db->select("PND_DOC,PND_DT,COUNT(*) TTLITEM,MAX(PND_REMARK) REMARK");
        $this->db->from($this->TABLENAME);
        $this->db->like($plike);
        $this->db->group_by("PND_DOC,PND_DT");
        $query = $this->db->get();
        return $query->result_array();
    }
    public function selectserAllg_byVAR($plike)
    {        
        $this->db->select("PNDSER_DOC,PNDSER_DT,COUNT(*) TTLITEM,MAX(PNDSER_REMARK) REMARK");
        $this->db->from("PNDSER_TBL");
        $this->db->like($plike);
        $this->db->group_by("PNDSER_DOC,PNDSER_DT");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectAll_by($pwhere)
    {
        $this->db->select("a.*,MITM_SPTNO,ITMLOC_LOC,MSTEMP_FNM");
        $this->db->from($this->TABLENAME . ' a');
        $this->db->join('MITM_TBL b', 'a.PND_ITMCD=b.MITM_ITMCD', 'LEFT');
        $this->db->join('ITMLOC_TBL c', 'a.PND_ITMCD=c.ITMLOC_ITM', 'LEFT');
        $this->db->join('MSTEMP_TBL d', 'PND_USRID=MSTEMP_ID', 'LEFT');
        $this->db->where($pwhere);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectserAll_by($pwhere)
    {
        $this->db->select("a.*,MITM_SPTNO,MITM_ITMD1,ITMLOC_LOC,SER_DOC,d.SER_ITMID,ITH_LOC,MSTEMP_FNM");
        $this->db->from('PNDSER_TBL a');
        $this->db->join('SER_TBL d', 'a.PNDSER_SER=SER_ID');
        $this->db->join('MITM_TBL b', 'SER_ITMID=b.MITM_ITMCD');
        $this->db->join('ITMLOC_TBL c', 'MITM_ITMCD=c.ITMLOC_ITM', 'LEFT');
        $this->db->join('MSTEMP_TBL f', 'PNDSER_USRID=MSTEMP_ID', 'LEFT');
        $this->db->join("vr_vis_fg", 'PNDSER_SER=ITH_SER', 'LEFT');
        $this->db->join("(select RLSSER_REFFDOC, RLSSER_SER from RLSSER_TBL GROUP BY RLSSER_REFFDOC, RLSSER_SER) vrel", 'PNDSER_SER=RLSSER_SER AND PNDSER_DOC=RLSSER_REFFDOC', 'LEFT');
        $this->db->where($pwhere);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectAll_like_by($pwhere)
    {
        $this->db->select("a.*,MITM_SPTNO,ITMLOC_LOC, coalesce(ITH_QTY,0) RELQTY, COALESCE(SCR_QTY,0) SCRQTY ");
        $this->db->from($this->TABLENAME . ' a');
        $this->db->join('MITM_TBL b', 'a.PND_ITMCD=b.MITM_ITMCD');
        $this->db->join('ITMLOC_TBL c', 'a.PND_ITMCD=c.ITMLOC_ITM', 'LEFT');
        $this->db->join('v_releasedrm d', 'a.PND_DOC=d.ITH_DOC AND a.PND_ITMCD=d.ITH_ITMCD AND a.PND_ITMLOT=d.ITH_REMARK', 'LEFT');
        $this->db->join('v_scraprm e', 'a.PND_DOC=e.SCR_REFFDOC AND a.PND_ITMCD=e.SCR_ITMCD AND a.PND_ITMLOT=e.SCR_ITMLOT', 'LEFT');
        $this->db->like($pwhere);
        $query = $this->db->get();
        return $query->result_array();
    }
    public function selectserAll_like_by($pwhere)
    {
        $this->db->select("a.*,SER_ITMID,MITM_SPTNO,ITMLOC_LOC,SER_DOC,coalesce(RLSQTY,0) RELQTY, coalesce(SCRSER_QTY,0) SCRSER_QTY ");
        $this->db->from('PNDSER_TBL a');
        $this->db->join('SER_TBL d', 'a.PNDSER_SER=SER_ID');
        $this->db->join('MITM_TBL b', 'SER_ITMID=b.MITM_ITMCD');
        $this->db->join('ITMLOC_TBL c', 'MITM_ITMCD=c.ITMLOC_ITM', 'LEFT');
        $this->db->join('v_releasedfg e', 'a.PNDSER_DOC=e.RLSSER_REFFDOC AND a.PNDSER_SER=e.RLSSER_REFFSER', 'LEFT');
        $this->db->join('SCRSER_TBL f', 'a.PNDSER_DOC=f.SCRSER_REFFDOC AND a.PNDSER_SER=f.SCRSER_SER', 'LEFT');
        $this->db->like($pwhere);
        $query = $this->db->get();
        return $query->result_array();
    }
    public function selectserAll_exact_by($pwhere)
    {
        $this->db->select("a.*,SER_ITMID,MITM_SPTNO,ITMLOC_LOC,SER_DOC,coalesce(RLSQTY,0) RELQTY, coalesce(SCRSER_QTY,0) SCRSER_QTY, COALESCE(v1.ITH_QTY,0) PNDSAVEDQTY ");
        $this->db->from('PNDSER_TBL a');
        $this->db->join('SER_TBL d', 'a.PNDSER_SER=SER_ID');
        $this->db->join('MITM_TBL b', 'SER_ITMID=b.MITM_ITMCD');
        $this->db->join('ITMLOC_TBL c', 'MITM_ITMCD=c.ITMLOC_ITM', 'LEFT');
        $this->db->join('v_releasedfg e', 'a.PNDSER_DOC=e.RLSSER_REFFDOC AND a.PNDSER_SER=e.RLSSER_REFFSER', 'LEFT');
        $this->db->join('SCRSER_TBL f', 'a.PNDSER_DOC=f.SCRSER_REFFDOC AND a.PNDSER_SER=f.SCRSER_SER', 'LEFT');
        $this->db->join("(SELECT ITH_SER, SUM(ITH_QTY) ITH_QTY FROM ITH_TBL WHERE ITH_DOC='$pwhere[PNDSER_DOC]' AND ITH_WH='QAFG'
        GROUP BY ITH_SER) v1", "PNDSER_SER=v1.ITH_SER ", 'LEFT');
        $this->db->where($pwhere);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function selectscan_balancingv2($pdo)
    {
        $qry = "SELECT v1.*
                    ,COALESCE(ITHQTY, 0) ITHQTY
                FROM (
                    SELECT PND_ITMCD
                        ,PND_QTY
                        ,COALESCE(PNDSCN_QTY, 0) SCAN_QTY
                        ,PNDSCN_LUPDT LTSSCANTIME
                    FROM (
                        SELECT PND_DOC,PND_ITMCD
                            ,SUM(PND_QTY) PND_QTY
                        FROM PND_TBL
                        WHERE PND_DOC = ?
                        GROUP BY PND_DOC
                            ,PND_ITMCD
                        ) a
                    LEFT JOIN (
                        SELECT PNDSCN_DOC
                            ,PNDSCN_ITMCD
                            ,sum(PNDSCN_QTY) PNDSCN_QTY
                            ,max(PNDSCN_LUPDT) PNDSCN_LUPDT
                        FROM PNDSCN_TBL
                        WHERE PNDSCN_DOC = ?
                        GROUP BY PNDSCN_DOC
                            ,PNDSCN_ITMCD
                        ) b ON a.PND_DOC = b.PNDSCN_DOC
                        AND a.PND_ITMCD = b.PNDSCN_ITMCD
                    ) v1
                LEFT JOIN (
                    SELECT ITH_ITMCD
                        ,COALESCE(sum(ITH_QTY), 0) ITHQTY
                    FROM ITH_TBL
                    WHERE ITH_DOC = '" . $pdo . "'
                        AND ITH_FORM = 'INC-PEN-RM'
                    GROUP BY ITH_DOC
                        ,ITH_ITMCD
                    ) v2 ON v1.PND_ITMCD = v2.ITH_ITMCD
                ";
        $query = $this->db->query($qry, [$pdo, $pdo]);
        return $query->result_array();
    }

    public function selectprog_scan($pdo)
    {
        $qry = "xsp_progress_scnpnd ?";
        $resq = $this->db->query($qry, [$pdo]);
        return $resq->result_array();
    }

    public function selectprog_save($pdo)
    {
        $qry = "xsp_progress_scnpndsave ?";
        $resq = $this->db->query($qry, [$pdo]);
        return $resq->result_array();
    }

    public function selectbalancebyDOITEM($pdo, $pitem)
    {
        $qry = "SELECT v1.* FROM
        (SELECT PND_ITMCD, PND_QTY, COALESCE(SUM(PNDSCN_QTY),0) SCAN_QTY, max(PNDSCN_LUPDT) LTSSCANTIME
		FROM (select PND_DOC,PND_ITMCD,SUM(PND_QTY) PND_QTY FROM PND_TBL
		GROUP BY PND_DOC,PND_ITMCD) a
                LEFT JOIN PNDSCN_TBL b ON a.PND_DOC=b.PNDSCN_DOC and a.PND_ITMCD=b.PNDSCN_ITMCD
                WHERE PND_DOC='$pdo' AND PND_ITMCD='$pitem'
                GROUP BY PND_DOC, PND_ITMCD,PND_QTY
                ) v1 ";
        $query = $this->db->query($qry);
        return $query->result_array();
    }

    public function selectscan_balancing($pwhere)
    {
        $this->db->from($this->TABLENAME . ' a');
        $this->db->select("PND_ITMCD, PND_QTY, COALESCE(SUM(PNDSCN_QTY),0) SCAN_QTY, max(PNDSCN_LUPDT) LTSSCANTIME");
        $this->db->join('PNDSCN_TBL b', "a.PND_DOC=b.PNDSCN_DOC and a.PND_ITMCD=b.PNDSCN_ITMCD", 'LEFT');
        $this->db->where($pwhere);
        $this->db->group_by('PND_DOC, PND_ITMCD,PND_QTY');
        $this->db->having('COALESCE(SUM(PNDSCN_QTY),0)<PND_QTY');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function deleteby_filter($pwhere)
    {
        $this->db->where($pwhere);
        $this->db->delete("PNDSER_TBL");
        return $this->db->affected_rows();
    }
}
