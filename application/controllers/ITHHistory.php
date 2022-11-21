<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ITHHistory extends CI_Controller
{
	private $FG_RETURN_WH = ['AFWH3RT', 'NFWH4RT'];
	public function __construct()
	{
		parent::__construct();
		$this->load->model('ITH_mod');
		$this->load->model('RPSAL_INVENTORY_mod');
		$this->load->model('MSTITM_mod');
	}

	public function index()
	{
		echo "sorry";
	}

	public function getLocations()
	{
		header('Content-Type: application/json');
		$rs = $this->ITH_mod->select_parent_locations();
		die(json_encode(['data' => $rs]));
	}

	function ith_doc_vs_date()
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

	function inventory_saldo_awal()
	{
		header('Content-Type: application/json');
		$date = $this->input->get('date');
		$itemcode = $this->input->get('itemcode');
		$rs = $this->ITH_mod->select_inventory_saldo_awal($date, $itemcode);
		die(json_encode(['data' => $rs]));
	}
	function inventory_pemasukan()
	{
		header('Content-Type: application/json');
		$datefrom = $this->input->get('date_from');
		$dateto = $this->input->get('date_to');
		$itemcode = $this->input->get('itemcode');
		$rs = $this->ITH_mod->select_inventory_pemasukan($datefrom, $dateto, $itemcode);
		die(json_encode(['data' => $rs]));
	}
	function inventory_pengeluaran()
	{
		header('Content-Type: application/json');
		$datefrom = $this->input->get('date_from');
		$dateto = $this->input->get('date_to');
		$itemcode = $this->input->get('itemcode');
		$rs = $this->ITH_mod->select_inventory_pengeluaran($datefrom, $dateto, $itemcode);
		die(json_encode(['data' => $rs]));
	}

	function inventory_all()
	{
		header('Content-Type: application/json');
		$datefrom = $this->input->get('date_from');
		$dateto = $this->input->get('date_to');
		$itemcode = $this->input->get('itemcode');
		$rs_saldo_awal = $this->ITH_mod->select_inventory_saldo_awal($datefrom, $itemcode);
		$rs_pemasukan = $this->ITH_mod->select_inventory_pemasukan($datefrom, $dateto, $itemcode);
		$rs_pengeluaran = $this->ITH_mod->select_inventory_pengeluaran($datefrom, $dateto, $itemcode);
		die(json_encode([
			'saldo_awal' => $rs_saldo_awal, 'pemasukan' => $rs_pemasukan, 'pengeluaran' => $rs_pengeluaran
		]));
	}

	function raw_ith()
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

	function check_inventory_it_inventory()
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
		$sheet->setCellValueByColumnAndRow(3, 1, 'Item Name');
		$sheet->setCellValueByColumnAndRow(4, 1, 'SPTNO');
		$sheet->setCellValueByColumnAndRow(5, 1, 'End Qty');
		$sheet->setCellValueByColumnAndRow(6, 1, 'Unit Measurement');
		$sheet->setCellValueByColumnAndRow(7, 1, 'ID');
		$sheet->setCellValueByColumnAndRow(9, 1, 'Job Number');
		$sheet->fromArray($rs, NULL, 'A2');
		$stringjudul = "stock $wh at " . $dt;
		$writer = new Xlsx($spreadsheet);
		$filename = $stringjudul; //save our workbook as this file name

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
	}

	function double_unique_tx()
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
		$main_wh = NULL;
		$inc_wh = NULL;
		$preparation_wh = NULL;
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
			$rs = in_array($wh, $this->FG_RETURN_WH) ?  $this->ITH_mod->select_psi_stock_date_wbg_fg_rtn($main_wh, $inc_wh, $main_wh, $preparation_wh, $citem, $dt) :
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
		$sheet->fromArray($rs, NULL, 'A2');
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
}
