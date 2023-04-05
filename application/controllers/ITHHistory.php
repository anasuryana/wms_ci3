<?php
defined('BASEPATH') or exit('No direct script access allowed');

// use PHPJasper\PHPJasper;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ITHHistory extends CI_Controller
{
    private $FG_RETURN_WH = ['AFWH3RT', 'NFWH4RT'];
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('ITH_mod');
        $this->load->model('RPSAL_INVENTORY_mod');
        $this->load->model('MSTITM_mod');
        $this->load->model('ZRPSTOCK_mod');
        $this->load->model('XITRN_mod');
    }

    public function index()
    {
        echo "sorry";
        // =================
        // $input = 'D:/apache24/htdocs/wms/vendor/geekcom/phpjasper/examples/hello_world.jasper';
        // $output = 'D:/apache24/htdocs/wms/vendor/geekcom/phpjasper/examples';
        // $options = [
        //     'format' => ['pdf', 'rtf']
        // ];
        // $jasper = new PHPJasper;

        // $jasper->process(
        //     $input,
        //     $output,
        //     $options
        // )->execute();
        // =================

        // =================
        // $input = FCPATH. '/vendor/geekcom/phpjasper/examples/hello_world.jrxml';

        // $jasper = new PHPJasper;
        // $jasper->compile($input)->execute();
        // =================

        // $input = FCPATH . '/vendor/geekcom/phpjasper/examples/hello_world_params.jrxml';

        // $jasper = new PHPJasper;
        // $output = $jasper->listParameters($input)->execute();

        // foreach ($output as $parameter_description) {
        //     print $parameter_description . '<pre>';
        // }
    }

    public function getLocations()
    {
        header('Content-Type: application/json');
        $rs = $this->ITH_mod->select_parent_locations();
        die(json_encode(['data' => $rs]));
    }

    public function ith_doc_vs_date()
    {
        header('Content-Type: application/json');
        $doc = $this->input->get('doc');
        $date = $this->input->get('date');
        $rs = $this->ITH_mod->select_doc_vs_datec($doc, $date);
        $tmpTime = strtotime($date . ' +1 days');
        $date0 = date('Y-m-d', $tmpTime);
        $rsTobeSaved = [];
        foreach ($rs as &$r) {
            $r['TIME'] = substr($r['ITH_LUPDT'], 11, 8);
            if ($r['TIME'] >= '00:00:00' && $r['TIME'] <= '07:00:00') {
                $r['TO_LUPDT'] = $date0 . " " . $r['TIME'];
            } else {
                $r['TO_LUPDT'] = $date . " " . $r['TIME'];
            }
            if ($r['ITH_FORM'] === 'OUT-WH-RM') {
                $r['ITH_QTY'] = abs($r['ITH_QTY']);
                $rsTobeSaved[] = $r;
            }
        }
        unset($r);

        die(json_encode(['data' => $rsTobeSaved]));
    }

    public function inventory_saldo_awal()
    {
        header('Content-Type: application/json');
        $date = $this->input->get('date');
        $itemcode = $this->input->get('itemcode');
        $rs = $this->ITH_mod->select_inventory_saldo_awal($date, $itemcode);
        die(json_encode(['data' => $rs]));
    }
    public function inventory_pemasukan()
    {
        header('Content-Type: application/json');
        $datefrom = $this->input->get('date_from');
        $dateto = $this->input->get('date_to');
        $itemcode = $this->input->get('itemcode');
        $rs = $this->ITH_mod->select_inventory_pemasukan($datefrom, $dateto, $itemcode);
        die(json_encode(['data' => $rs]));
    }
    public function inventory_pengeluaran()
    {
        header('Content-Type: application/json');
        $datefrom = $this->input->get('date_from');
        $dateto = $this->input->get('date_to');
        $itemcode = $this->input->get('itemcode');
        $rs = $this->ITH_mod->select_inventory_pengeluaran($datefrom, $dateto, $itemcode);
        die(json_encode(['data' => $rs]));
    }

    public function inventory_all()
    {
        header('Content-Type: application/json');
        $datefrom = $this->input->get('date_from');
        $dateto = $this->input->get('date_to');
        $itemcode = $this->input->get('itemcode');
        $rs_saldo_awal = $this->ITH_mod->select_inventory_saldo_awal($datefrom, $itemcode);
        $rs_pemasukan = $this->ITH_mod->select_inventory_pemasukan($datefrom, $dateto, $itemcode);
        $rs_pengeluaran = $this->ITH_mod->select_inventory_pengeluaran($datefrom, $dateto, $itemcode);
        die(json_encode([
            'saldo_awal' => $rs_saldo_awal, 'pemasukan' => $rs_pemasukan, 'pengeluaran' => $rs_pengeluaran,
        ]));
    }

    public function raw_ith()
    {
        header('Content-Type: application/json');
        $item_location = $this->input->get('item_location');
        $item_code = $this->input->get('item_code');
        $item_event = $this->input->get('item_event');
        $item_date = $this->input->get('item_date');

        $rs = $this->ITH_mod->select_view_all_by([
            'ITH_ITMCD' => $item_code,
            'ITH_WH' => $item_location,
            'ITH_FORM' => $item_event,
            'ITH_DATEC' => $item_date,
        ]);
        die(json_encode(['data' => $rs]));
    }

    public function form_report_compare_exbc()
    {
        $this->load->view('wms_report/vrpt_exbc_vs_stock');
    }

    public function check_inventory_it_inventory()
    {
        header('Content-Type: application/json');
        $date = $this->input->get('date');
        $type = strtoupper($this->input->get('type'));
        $aDate = explode('-', $date);
        $vYear = $aDate[0];
        $vMonth = $aDate[1];
        $rs = $this->RPSAL_INVENTORY_mod->select_column_group_where([$type === 'P' ? 'INV_PHY_DATE' : 'INV_DATE'], ['INV_YEAR' => $vYear, 'INV_MONTH' => $vMonth]);
        $myar = [];
        if (count($rs) <= 1) {
            if (empty($rs)) {
                $myar[] = ['cd' => '1', 'msg' => 'OK'];
            } else {
                foreach ($rs as $r) {
                    if ($type === 'P') {
                        if ($r['INV_PHY_DATE'] === $date || !$r['INV_PHY_DATE']) {
                            $myar[] = ['cd' => '1', 'msg' => 'OK'];
                        } else {
                            $myar[] = ['cd' => '0', 'msg' => 'NOT OK'];
                        }
                    } else {
                        if ($r['INV_DATE'] === $date || !$r['INV_DATE']) {
                            $myar[] = ['cd' => '1', 'msg' => 'OK'];
                        } else {
                            $myar[] = ['cd' => '0', 'msg' => 'NOT OK.'];
                        }
                    }
                }
            }
        } else {
            $myar[] = ['cd' => '0', 'msg' => 'NOT OK..'];
        }
        die(json_encode(['status' => $myar, 'data' => $rs, 'reff_type' => $type, 'countrs' => count($rs)]));
    }

    public function get_stock_detail_as_xls()
    {
        ini_set('max_execution_time', '-1');
        $wh = $_COOKIE["CKPSI_WH"];
        $dt = $_COOKIE["CKPSI_DATE"];
        $thewh = $wh == "AFSMT" ? "AFWH3" : $wh;
        $rs = $this->ITH_mod->select_psi_stock_date_wbg_detail($thewh, $dt);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('stock_detail');
        $sheet->setCellValueByColumnAndRow(1, 1, 'Warehouse');
        $sheet->setCellValueByColumnAndRow(2, 1, 'Item Code');
        $sheet->setCellValueByColumnAndRow(3, 1, 'End Qty');
        $sheet->setCellValueByColumnAndRow(4, 1, 'ID');
        $sheet->setCellValueByColumnAndRow(5, 1, 'Last Update');
        $sheet->setCellValueByColumnAndRow(6, 1, 'Item Description');
        $sheet->setCellValueByColumnAndRow(7, 1, 'Item Name');
        $sheet->setCellValueByColumnAndRow(8, 1, 'Unit Measurement');
        $sheet->setCellValueByColumnAndRow(9, 1, 'Job Number');
        $sheet->setCellValueByColumnAndRow(10, 1, 'Location');
        $sheet->fromArray($rs, null, 'A2');
        $stringjudul = "stock $wh at " . $dt;
        $writer = new Xlsx($spreadsheet);
        $filename = $stringjudul; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function double_unique_tx()
    {
        header('Content-Type: application/json');
        $rs = $this->ITH_mod->select_double_unique_tx();
        die(json_encode(['data' => $rs]));
    }

    public function get_stock_recap_as_xls()
    {
        ini_set('max_execution_time', '-1');
        $bg = $_COOKIE["CKPSI_BG"];
        $wh = $_COOKIE["CKPSI_WH"];
        $dt = $_COOKIE["CKPSI_DATE"];
        $citem = "";
        $rs = [];
        $str_bg = "";
        $main_wh = null;
        $inc_wh = null;
        $preparation_wh = null;
        switch ($wh) {
            case 'NFWH4RT':
                $main_wh = ['AFQART', 'NFWH4RT', 'ARSHPRTN'];
                $inc_wh = ['AFQART', 'NFWH4RT'];
                $preparation_wh = 'ARSHPRTN';
                break;
            case 'AFWH3RT':
                $main_wh = ['AFQART2', 'AFWH3RT', 'ARSHPRTN2'];
                $inc_wh = ['AFQART2', 'AFWH3RT'];
                $preparation_wh = 'ARSHPRTN2';
                break;
        }
        if (strlen(trim($bg)) == 0) {
            $rs = in_array($wh, $this->FG_RETURN_WH) ? $this->ITH_mod->select_psi_stock_date_wbg_fg_rtn($main_wh, $inc_wh, $main_wh, $preparation_wh, $citem, $dt) :
            $this->ITH_mod->select_psi_stock_date_wbg($wh, $citem, $dt);
        } else {
            if (strpos($bg, ",") !== false) {
                $abg = explode(",", $bg);
                $abg_c = count($abg);
                for ($i = 0; $i < $abg_c; $i++) {
                    $str_bg .= "'$abg[$i]',";
                }
                $str_bg = substr($str_bg, 0, strlen($str_bg) - 1);
            } else {
                $str_bg = "'$bg'";
            }
            $rs = in_array($wh, $this->FG_RETURN_WH) ? $this->ITH_mod->select_psi_stock_date_fg_rtn($main_wh, $inc_wh, $main_wh, $preparation_wh, $citem, $str_bg, $dt) :
            $this->ITH_mod->select_psi_stock_date($wh, $citem, $str_bg, $dt);
        }
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('stock_recap');
        $sheet->setCellValueByColumnAndRow(1, 1, 'Warehouse');
        $sheet->setCellValueByColumnAndRow(2, 1, 'Item Code');
        $sheet->setCellValueByColumnAndRow(3, 1, 'Item Name');
        $sheet->setCellValueByColumnAndRow(4, 1, 'SPTNO');
        $sheet->setCellValueByColumnAndRow(5, 1, 'Opening');
        $sheet->setCellValueByColumnAndRow(6, 1, 'IN');
        $sheet->setCellValueByColumnAndRow(7, 1, 'PREPARE');
        $sheet->setCellValueByColumnAndRow(8, 1, 'OUT');
        $sheet->setCellValueByColumnAndRow(9, 1, 'CLOSING');
        $sheet->setCellValueByColumnAndRow(10, 1, 'Unit Measurement');
        $sheet->setCellValueByColumnAndRow(11, 1, 'Category');
        $sheet->fromArray($rs, null, 'A2');
        foreach (range('A', 'K') as $v) {
            $sheet->getColumnDimension($v)->setAutoSize(true);
        }
        //left align
        $rang = "A1:D" . $sheet->getHighestDataRow();
        $sheet->getStyle($rang)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        if ($wh === 'AFSMT') {
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle('resume_category');
            $sheet->setCellValueByColumnAndRow(1, 1, 'Category');
            $sheet->setCellValueByColumnAndRow(2, 1, 'Qty');
            $rscat = $this->MSTITM_mod->select_category();
            foreach ($rscat as &$c) {
                $c['TTL'] = 0;
            }
            unset($c);
            foreach ($rs as $r) {
                foreach ($rscat as &$c) {
                    if ($c['MITM_NCAT'] === $r['MITM_NCAT']) {
                        $c['TTL'] += $r['OUTQTY'];
                    }
                }
                unset($c);
            }
            $i = 2;
            foreach ($rscat as $r) {
                $sheet->setCellValueByColumnAndRow(1, $i, $r['MITM_NCAT']);
                $sheet->setCellValueByColumnAndRow(2, $i, $r['TTL']);
                $i++;
            }
            foreach (range('A', 'B') as $v) {
                $sheet->getColumnDimension($v)->setAutoSize(true);
            }
        }

        $stringjudul = "stock $wh at " . $dt;
        $writer = new Xlsx($spreadsheet);
        $filename = $stringjudul; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function compareStockVSExbc()
    {
        header('Content-Type: application/json');
        $item_code = $this->input->get('item_code');
        $location = [];
        # variable rsMega & rs dipisah , karena ketika saya eksekusi dalam satu query (UNION ALL) maka efeknya
        # memakan waktu lama (mungkin karena beda server atau bagaimana)

        $rsMega = $this->XITRN_mod->selectEXBCReconsiliator($item_code);
        $rsFix = [];
        foreach ($rsMega as $r) {
            if (!in_array($r['ITRN_LOCCD'], $location)) {
                $location[] = $r['ITRN_LOCCD'];
            }

        }
        $rs = $this->ZRPSTOCK_mod->selectStockItemVSBC($item_code);
        foreach ($rs as $r) {
            if (!in_array($r['ITRN_LOCCD'], $location)) {
                $location[] = $r['ITRN_LOCCD'];
            }
        }

        $rsEquipment = $this->ITH_mod->selectPSIEquipment(['ITH_ITMCD' => $item_code]);
        foreach ($rsEquipment as $r) {
            if (!in_array($r['ITH_WH'], $location)) {
                $location[] = $r['ITH_WH'];
            }
        }

        # inisialisasi nilai 'baris dan kolom kanan'
        foreach ($rsMega as $r) {
            $isFound = false;
            foreach ($rsFix as &$n) {
                if ($n['ITRN_ITMCD'] === $r['ITRN_ITMCD']) {
                    $isFound = true;
                    break;
                }
            }
            unset($n);

            if (!$isFound) {
                $row = ['ITRN_ITMCD' => $r['ITRN_ITMCD'], 'SPTNO' => $r['MITM_SPTNO'], 'D1' => $r['MITM_ITMD1']];
                foreach ($location as $l) {
                    $row[$l] = 0;
                }
                $rsFix[] = $row;
            }
        }

        foreach ($rsEquipment as $r) {
            $isFound = false;
            foreach ($rsFix as &$n) {
                if ($n['ITRN_ITMCD'] === $r['ITH_ITMCD']) {
                    $isFound = true;
                    break;
                }
            }
            unset($n);

            if (!$isFound) {
                $row = ['ITRN_ITMCD' => $r['ITH_ITMCD'], 'SPTNO' => $r['MITM_SPTNO'], 'D1' => $r['MITM_ITMD1']];
                foreach ($location as $l) {
                    $row[$l] = 0;
                }
                $rsFix[] = $row;
            }
        }

        #isi nilai
        # -----------------------
        # PART     |    LOKASI?
        # -----------------------
        # PART1    |    SETVALUE?
        # PART2    |    SETVALUE?
        foreach ($rsFix as &$r) {
            foreach ($rsMega as $m) {
                if ($r['ITRN_ITMCD'] === $m['ITRN_ITMCD']) {
                    $r[$m['ITRN_LOCCD']] += $m['RMQT'];
                }
            }
            foreach ($rs as $m) {
                if ($r['ITRN_ITMCD'] === $m['ITRN_ITMCD']) {
                    $r[$m['ITRN_LOCCD']] += $m['RMQT'];
                }
            }
            foreach ($rsEquipment as $m) {
                if ($r['ITRN_ITMCD'] === $m['ITH_ITMCD']) {
                    $r[$m['ITH_WH']] += $m['EQUIPQT'];
                }
            }
        }
        unset($r);
        die(json_encode(['data_location' => $location, 'rsFix' => $rsFix, '$rsEquipment' => $rsEquipment]));
    }
}
