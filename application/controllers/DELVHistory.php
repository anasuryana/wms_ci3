<?php
defined('BASEPATH') or exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DELVHistory extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('download');
        $this->load->library('session');
        $this->load->model('SISO_mod');
        $this->load->model('DELV_mod');
        $this->load->model('ITH_mod');
        $this->load->model('RPSAL_BCSTOCK_mod');
        $this->load->model('DisposeDraft_mod');
        $this->load->model('ZRPSTOCK_mod');
        $this->load->model('MEXRATE_mod');
    }
    public function index()
    {
        echo "sorry";
    }

    public function priceFG()
    {
        header('Content-Type: application/json');
        $itemcd = $this->input->post('itemcd');
        $reffno = $this->input->post('reffno');
        $reffnostring = is_array($reffno) ? "'" . implode("','", $reffno) . "'" : "''";
        $rs = $this->SISO_mod->select_currentPrice_byReffno($reffnostring, $itemcd);
        die(json_encode(['data' => $rs]));
    }

    public function form_reconsiliator()
    {
        $this->load->view('wms/vreconsiliator_exbc');
    }

    public function reconsiliator()
    {
        header('Content-Type: application/json');
        $itemcd = $this->input->get('itemcd');
        $rs = $this->RPSAL_BCSTOCK_mod->selectDiscrepancy($itemcd);
        die(json_encode(['data' => $rs]));
    }

    public function reconsiliator2()
    {
        header('Content-Type: application/json');
        $itemcd = $this->input->get('itemcd');
        $doc = $this->input->get('doc');
        $columns = ['RPSTOCK_REMARK', 'RPSTOCK_ITMNUM', 'RPSTOCK_NOAJU', 'RPSTOCK_QTY', 'id'];
        $rs = $this->RPSAL_BCSTOCK_mod->selectAllOrderbyIdWhere($columns, ['RPSTOCK_ITMNUM' => $itemcd, 'RPSTOCK_REMARK' => $doc]);
        $rs3 = $this->DELV_mod->selectJoinSERLike(['DLV_ID' => $doc, 'SER_DOC' => '-C']);
        die(json_encode(['data' => $rs, 'dataser' => $rs3]));
    }

    public function DisposeFGPreparation()
    {
        header('Content-Type: application/json');
        $RSSubOnly = $this->DELV_mod->selectCalculationSubOnlyBySerID([
            'GHSWX6UWE81I1NPI',
            'GHSWX6UWE31I28TO',
            'GHSWX6UWE51II2CQ',
            'GHSWX6UWDZ1I2K1G',
            'GHSWX6UWE71I3EI1',
        ]);
        $RSMaterialOnly = $this->DELV_mod->selectCalculationSerRmOnlyBySerID(['GHSWX6UWE81I1NPI',
            'GHSWX6UWE31I28TO',
            'GHSWX6UWE51II2CQ',
            'GHSWX6UWDZ1I2K1G',
            'GHSWX6UWE71I3EI1']);
        $rsRM = array_merge($RSSubOnly, $RSMaterialOnly);
        die(json_encode(['data' => $rsRM]));

    }

    public function dispose_report_multilayer()
    {
        header('Content-Type: application/json');
        $rsRM = $this->DisposeDraft_mod->select_detail_fg_202212_additional();
        $rsEXBC = $this->ZRPSTOCK_mod->select_columns_where(
            [
                "rtrim(RPSTOCK_ITMNUM) ITMNUM", "ABS(RPSTOCK_QTY) BCQTY", "RPSTOCK_DOC", "RPSTOCK_NOAJU", "RPSTOCK_BCNUM", "RPSTOCK_BCDATE", "RPSTOCK_BCTYPE", 'URUT', 'RCV_HSCD',
                'RCV_BM', 'RCV_PPN', 'RCV_PPH',
            ],
            ["RPSTOCK_REMARK" => "DISD2212_FG_ADDITIONAL"]
        );
        $rsfix = [];
        foreach ($rsRM as &$r) {
            $r['QTYPLOT'] = 0;
            foreach ($rsEXBC as &$k) {
                $reqPlot = $r['QTY'] - $r['QTYPLOT'];
                if ($r['PART_CODE'] === $k['ITMNUM'] && $reqPlot > 0 && $k['BCQTY'] > 0) {
                    $theqty = $reqPlot;
                    if ($reqPlot > $k['BCQTY']) {
                        $r['QTYPLOT'] += $k['BCQTY'];
                        $theqty = $k['BCQTY'];
                        $k['BCQTY'] = 0;
                    } else {
                        $k['BCQTY'] -= $reqPlot;
                        $r['QTYPLOT'] += $reqPlot;
                    }
                    $rsfix[] = [
                        'ASSY_DESCRIPTION' => $r['FGDESC'],
                        'ASSY_CODE' => $r['ITH_ITMCD'],
                        'ASSY_QTY' => $r['LQT'],
                        'ASSY_KEY' => $r['ITH_SER'],
                        'ITEM_DESCRIPTION' => $r['RMDESC'],
                        'ITEM_CODE' => $r['PART_CODE'],
                        'USE' => $r['QTY'] / $r['LQT'],
                        'ITEM_QTY' => $theqty,
                        'AJU' => $k['RPSTOCK_NOAJU'],
                        'BCNO' => $k['RPSTOCK_BCNUM'],
                        'BCDATE' => $k['RPSTOCK_BCDATE'],
                        'ITEM_HSCD' => $k['RCV_HSCD'],
                        'BM' => $k['RCV_BM'],
                        'PPN' => $k['RCV_PPN'],
                        'PPH' => $k['RCV_PPH'],
                        'URUT' => $k['URUT'],
                    ];
                    if ($r['QTY'] == $r['QTYPLOT']) {
                        break;
                    }
                }
            }
            unset($k);
        }
        die(json_encode(['rsfix' => $rsfix]));
    }

    public function dr_pab_out_as_excel()
    {
        $bctype = isset($_COOKIE["RP_PAB_DOCTYPE"]) ? $_COOKIE["RP_PAB_DOCTYPE"] : '';
        $jenis_tpb_tujuan = isset($_COOKIE["RP_PAB_TPBTYPE"]) ? $_COOKIE["RP_PAB_TPBTYPE"] : '';
        $itemcd = isset($_COOKIE["RP_PAB_ITMCD"]) ? $_COOKIE["RP_PAB_ITMCD"] : '';
        $cdate0 = isset($_COOKIE["RP_PAB_DATE0"]) ? $_COOKIE["RP_PAB_DATE0"] : '';
        $cdate1 = isset($_COOKIE["RP_PAB_DATE1"]) ? $_COOKIE["RP_PAB_DATE1"] : '';
        $nomaju = isset($_COOKIE["RP_PAB_NOAJU"]) ? $_COOKIE["RP_PAB_NOAJU"] : '';
        $tujuan_pengiriman = isset($_COOKIE["RP_PAB_RCVSTATUS"]) ? $_COOKIE["RP_PAB_RCVSTATUS"] : '';
        $itemtype = isset($_COOKIE["RP_PAB_ITMTYPE"]) ? $_COOKIE["RP_PAB_ITMTYPE"] : '';

        $where = ['TGLPEN >=' => $cdate0, 'TGLPEN <= ' => $cdate1];
        if ($nomaju != '') {
            $where['NOMAJU'] = $nomaju;
        }
        if ($bctype != '-') {
            $where['DLV_BCTYPE'] = $bctype;
        }
        if ($itemcd != '') {
            $where['SER_ITMID'] = $itemcd;
        }
        if ($tujuan_pengiriman != '-') {
            $where['DLV_PURPOSE'] = $tujuan_pengiriman;
        }
        if ($jenis_tpb_tujuan != '-') {
            $where['DLV_ZJENIS_TPB_TUJUAN'] = $jenis_tpb_tujuan;
        }
        $like = [];
        $rs = [];
        if ($itemtype != '-') {
            if ($itemtype == '10') {
                $where['MITM_MODEL'] = '1';
                $like['DLV_ID'] = 'RTN';
                $rs = $this->DELV_mod->select_out_pabean_like($where, $like);
            } elseif ($itemtype == '11') {
                $where['MITM_MODEL'] = '1';
                $like['DLV_ID'] = 'RTN';
                $rs = $this->DELV_mod->select_out_pabean_notlike($where, $like);
            } else {
                $where['MITM_MODEL'] = $itemtype;
                $rs = $this->DELV_mod->select_out_pabean($where);
            }
        } else {
            $rs = $this->DELV_mod->select_out_pabean($where);
        }

        $stringjudul = 'PEMBUKUAN KELUAR ' . str_replace('-', '/', $cdate0) . ' - ' . str_replace('-', '/', $cdate1);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('RESUME');
        $sheet->mergeCells('C1:O1');
        $sheet->setCellValueByColumnAndRow(3, 1, 'Pembukuan Keluar');
        $sheet->getStyle('C1')->getAlignment()->setHorizontal('center');
        $sheet->setCellValueByColumnAndRow(2, 2, 'PT. SMT INDONESIA');
        $sheet->setCellValueByColumnAndRow(2, 3, 'Kawasan EJIP Plot 5C2 Cikarang Selatan');
        $sheet->setCellValueByColumnAndRow(2, 4, 'PERIOD : ' . $cdate0 . ' to ' . $cdate1);
        $sheet->getStyle('R2')->getAlignment()->setHorizontal('right');

        $sheet->setCellValueByColumnAndRow(1, 6, 'NO');
        $sheet->mergeCells('B6:C6');
        $sheet->setCellValueByColumnAndRow(2, 6, 'PENGAJUAN ');
        $sheet->mergeCells('D6:E6');
        $sheet->setCellValueByColumnAndRow(4, 6, 'PENDAFTARAN');
        $sheet->setCellValueByColumnAndRow(6, 6, 'NO. URUT');

        $sheet->mergeCells('H6:H7');
        $sheet->setCellValueByColumnAndRow(7, 6, 'URAIAN JENIS BARANG');
        $sheet->mergeCells('I6:I7');
        $sheet->setCellValueByColumnAndRow(8, 6, 'KODE BARANG');
        $sheet->mergeCells('J6:J7');
        $sheet->setCellValueByColumnAndRow(9, 6, 'HS CODE');
        $sheet->mergeCells('K6:K7');
        $sheet->setCellValueByColumnAndRow(10, 6, 'JUMLAH');
        $sheet->mergeCells('L6:L7');
        $sheet->setCellValueByColumnAndRow(11, 6, 'SATUAN');
        $sheet->setCellValueByColumnAndRow(12, 6, 'HARGA');
        $sheet->mergeCells('M6:M7');
        $sheet->setCellValueByColumnAndRow(13, 6, 'Valuta');
        $sheet->setCellValueByColumnAndRow(14, 6, 'NILAI PABEAN');
        $sheet->mergeCells('O6:P6');
        $sheet->setCellValueByColumnAndRow(15, 6, 'TPB TUJUAN');
        $sheet->getStyle('B6:R7')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('B6:R7')->getAlignment()->setVertical('center');
        $sheet->setCellValueByColumnAndRow(17, 6, 'NO. INVOICE');
        $sheet->setCellValueByColumnAndRow(18, 6, 'NO. INVOICE SMT');
        $sheet->setCellValueByColumnAndRow(19, 6, 'NO. DO');
        $sheet->setCellValueByColumnAndRow(20, 6, 'Keterangan');
        $sheet->setCellValueByColumnAndRow(21, 6, 'Keterangan');

        $sheet->setCellValueByColumnAndRow(2, 7, 'NOMOR');
        $sheet->setCellValueByColumnAndRow(3, 7, 'TANGGAL ');
        $sheet->setCellValueByColumnAndRow(4, 7, 'NOMOR');
        $sheet->setCellValueByColumnAndRow(5, 7, 'TANGGAL');
        $sheet->setCellValueByColumnAndRow(6, 7, 'BARANG');
        $sheet->setCellValueByColumnAndRow(15, 7, 'NAMA');
        $sheet->setCellValueByColumnAndRow(16, 7, 'ALAMAT');

        $y = 8;
        $mnomor = 0;
        $mnomorin = 0;
        $jmlpab = 0;
        $jmlberat = 0;
        $mnomordis = $mnomorpab = $mnomorpabdis = $mdatepabdis = $msup = $msupdis = $malam = $malamdis = $mdo = $msppb = $nopen = '';
        $mtanggalDaftar = $mtanggalDaftardis = $mnomorpendaftaran = '';
        $flgcolor = '';
        foreach ($rs as $r) {
            if ($mnomorpab != $r['NOMAJU']) {
                $flgcolor = 'b';
                $mnomorpab = $r['NOMAJU'];
                $mnomorpendaftaran = $r['NOMPEN'];
                $mnomor++;
                $mnomordis = $mnomor;
                $mnomorpabdis = $mnomorpab;
                $mdatepabdis = $r['DLV_BCDATE'];
                $mtanggalDaftar = $r['TGLPEN'];
                $mtanggalDaftardis = $mtanggalDaftar;
                $mnomorin = 1;
                $msup = $r['MDEL_ZNAMA'];
                $msupdis = trim($msup);
                $malam = $r['MDEL_ADDRCUSTOMS'];
                $malamdis = $malam;
                $mdo = $r['DLV_ID'];
                $msppb = $r['DLV_SPPBDOC'];
                $nomorInv = $r['DLV_SMTINVNO'];
                $nomorInvDis = $nomorInv;
                $nomorInvSMT = $r['DLV_INVNO'];
                $nomorInvSMTDis = $nomorInvSMT;
            } else {
                $flgcolor = 'w';
                $mnomorin++;
                $mnomordis = '';
                $mdatepabdis = '';
                $nomorInvDis = '';
                $nomorInvSMTDis = '';
                if ($mdo != $r['DLV_ID']) {
                    $mdo = $r['DLV_ID'];
                    $msup = $r['MDEL_ZNAMA'];
                    $malam = $r['MDEL_ADDRCUSTOMS'];
                    $msupdis = trim($msup);
                    $malamdis = $malam;
                } else {
                    // $msupdis='';
                    $malamdis = '';
                }
            }
            $sheet->setCellValueByColumnAndRow(1, $y, $mnomordis);
            if ($flgcolor == 'w') {
                $sheet->getStyle('B' . $y)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
                $sheet->getStyle('N' . $y)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
                $sheet->getStyle('T' . $y)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
            }
            $sheet->setCellValueByColumnAndRow(2, $y, $mnomorpabdis);
            $sheet->setCellValueByColumnAndRow(3, $y, $mdatepabdis);
            $sheet->setCellValueByColumnAndRow(4, $y, $mnomorpendaftaran);
            $sheet->setCellValueByColumnAndRow(5, $y, $mtanggalDaftardis);
            $sheet->setCellValueByColumnAndRow(6, $y, $mnomorin);
            $sheet->setCellValueByColumnAndRow(7, $y, $r['MITM_ITMD1']);
            $sheet->setCellValueByColumnAndRow(8, $y, $r['SER_ITMID']);
            $sheet->setCellValueByColumnAndRow(9, $y, $r['MITM_HSCD']);
            $sheet->setCellValueByColumnAndRow(10, $y, $r['DLVPRC_QTY']);
            $sheet->setCellValueByColumnAndRow(11, $y, $r['MITM_STKUOM']);
            $sheet->setCellValueByColumnAndRow(12, $y, $r['DLVPRC_PRC']);
            $sheet->setCellValueByColumnAndRow(13, $y, $r['VALUTA']);
            $sheet->setCellValueByColumnAndRow(14, $y, $r['AMOUNT']);
            $sheet->setCellValueByColumnAndRow(15, $y, $msupdis);
            $sheet->setCellValueByColumnAndRow(16, $y, $malamdis);
            $sheet->setCellValueByColumnAndRow(17, $y, $nomorInvDis);
            $sheet->setCellValueByColumnAndRow(18, $y, $nomorInvSMTDis);
            $sheet->setCellValueByColumnAndRow(19, $y, $mdo);
            $sheet->setCellValueByColumnAndRow(21, $y, $msppb);
            $y++;
        }

        $sheet->getStyle('A8:I' . $y)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('J8:J' . $y)->getAlignment()->setHorizontal('right');
        $sheet->getStyle('K8:K' . $y)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('L8:L' . $y)->getAlignment()->setHorizontal('right');
        $sheet->getStyle('M8:M' . $y)->getAlignment()->setHorizontal('right');
        $sheet->getStyle('H8:H' . $y)->getAlignment()->setHorizontal('left');

        $sheet->mergeCells('H' . ($y + 1) . ':J' . ($y + 1));
        $sheet->getStyle('H' . ($y + 1) . ':H' . ($y + 1))->getAlignment()->setHorizontal('center');
        $sheet->setCellValueByColumnAndRow(8, ($y + 1), 'Jumlah');
        $sheet->setCellValueByColumnAndRow(13, ($y + 1), number_format($jmlpab, 2));
        $sheet->setCellValueByColumnAndRow(14, ($y + 1), number_format($jmlberat, 2));

        $sheet->getStyle('A2:A4')->getFont()->setBold(true);
        $sheet->getStyle('B1:B1')->getFont()->setBold(true);
        $BStyle = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                ],
            ],
        ];
        $sheet->getStyle('A6:U' . $y)->applyFromArray($BStyle);
        $sheet->freezePane('F8');
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        foreach (range("C", "U") as $r) {
            $sheet->getColumnDimension($r)->setAutoSize(true);
        }
        $sheet->getStyle('A6:U7')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('d4d4d4');
        $writer = new Xlsx($spreadsheet);
        $filename = $stringjudul . date(' H i'); //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function report_summary_inv_as_xls()
    {
        $bsgrp = '';
        $pdate1 = '';
        $pdate2 = '';
        if (isset($_COOKIE["CKPSI_BG"])) {
            $bsgrp = $_COOKIE["CKPSI_BG"];
        } else {
            exit('nothing to be exported');
        }
        $pdate1 = $_COOKIE["CKPSI_DATE1"];
        $pdate2 = $_COOKIE["CKPSI_DATE2"];
        $sbgroup = $bsgrp;
        $rs = $this->ITH_mod->select_deliv_invo($pdate1, $pdate2, $sbgroup);
        $kurs = $this->MEXRATE_mod->select_period($pdate1, $pdate2);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('RESUME');
        $sheet->setCellValueByColumnAndRow(1, 1, 'SALES');
        $sheet->setCellValueByColumnAndRow(1, 2, 'PERIOD : ' . str_replace('-', '/', $pdate1) . ' - ' . str_replace('-', '/', $pdate2));
        $sheet->setCellValueByColumnAndRow(1, 3, 'BUSINESS : ' . str_replace("'", "", $sbgroup));

        $sheet->setCellValueByColumnAndRow(1, 4, 'SHIP DATE');
        $sheet->setCellValueByColumnAndRow(2, 4, 'DO NO');
        $sheet->setCellValueByColumnAndRow(3, 4, 'Customer DO NO');
        $sheet->setCellValueByColumnAndRow(4, 4, 'DELIVERY CODE');
        $sheet->setCellValueByColumnAndRow(5, 4, 'No. Aju');
        $sheet->setCellValueByColumnAndRow(6, 4, 'Nopen');
        $sheet->setCellValueByColumnAndRow(7, 4, 'SPPB No');
        $sheet->setCellValueByColumnAndRow(8, 4, 'Invoice Date (Confirmation)');
        $sheet->setCellValueByColumnAndRow(9, 4, 'Invoice Date (Document)');
        $sheet->setCellValueByColumnAndRow(10, 4, 'NO. Invoice STX');
        $sheet->setCellValueByColumnAndRow(11, 4, 'No. Invoice SMT');
        $sheet->setCellValueByColumnAndRow(12, 4, 'MODEL CODE');
        $sheet->setCellValueByColumnAndRow(13, 4, 'MODEL DESCRIPTION');
        $sheet->setCellValueByColumnAndRow(14, 4, 'CPO NO');
        $sheet->setCellValueByColumnAndRow(15, 4, 'SHIP QTY');
        $sheet->setCellValueByColumnAndRow(16, 4, 'SALES PRICE');
        $sheet->setCellValueByColumnAndRow(17, 4, 'AMOUNT');
        $sheet->setCellValueByColumnAndRow(21, 4, 'Kurs');
        $sheet->setCellValueByColumnAndRow(22, 4, 'AMOUNT IDR');
        $inx = 5;
        foreach ($rs as $r) {
            $kursValue = 1;
            foreach($kurs as $k) {
                if($r['ITH_DATEC'] == $k['MEXRATE_DT']) {
                    $kursValue = $k['MEXRATE_VAL'];
                    break;
                }
            }
            $sheet->setCellValueByColumnAndRow(1, $inx, $r['ITH_DATEC']);
            $sheet->setCellValueByColumnAndRow(2, $inx, $r['ITH_DOC']);
            $sheet->setCellValueByColumnAndRow(3, $inx, $r['DLV_CUSTDO']);
            $sheet->setCellValueByColumnAndRow(4, $inx, $r['DLV_CONSIGN']);
            $sheet->setCellValueByColumnAndRow(5, $inx, $r['NOMAJU']);
            $sheet->setCellValueByColumnAndRow(6, $inx, $r['NOMPEN']);
            $sheet->setCellValueByColumnAndRow(7, $inx, $r['DLV_SPPBDOC']);
            $sheet->setCellValueByColumnAndRow(8, $inx, $r['ITH_DATEC']);
            $sheet->setCellValueByColumnAndRow(9, $inx, $r['INVDT']);
            $sheet->setCellValueByColumnAndRow(10, $inx, $r['DLV_INVNO']);
            $sheet->setCellValueByColumnAndRow(11, $inx, $r['DLV_SMTINVNO']);
            $sheet->setCellValueByColumnAndRow(12, $inx, $r['ITH_ITMCD']);
            $sheet->setCellValueByColumnAndRow(13, $inx, $r['ITMDESCD']);
            $sheet->setCellValueByColumnAndRow(14, $inx, $r['DLVPRC_CPO']);
            $sheet->setCellValueByColumnAndRow(15, $inx, $r['DLVPRC_QTY']);
            $sheet->setCellValueByColumnAndRow(16, $inx, $r['DLVPRC_PRC']);
            $sheet->setCellValueByColumnAndRow(17, $inx, "=ROUND(" . $r['AMOUNT'] . ",2)");
            $sheet->setCellValueByColumnAndRow(21, $inx, $kursValue);
            $sheet->setCellValueByColumnAndRow(22, $inx, substr($r['DLV_CUSTCD'], -1) === 'U' ? $r['AMOUNT']*$kursValue : $r['AMOUNT']);
            $inx++;
        }
        foreach (range('A', 'P') as $r) {
            $sheet->getColumnDimension($r)->setAutoSize(true);
        }
        $rang = "P1:P" . $inx;
        $sheet->getStyle($rang)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $sheet->getStyle('U1:V'. $inx)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
        $dodis = $pdate1 . " to " . $pdate2;
        $stringjudul = "Summary Sales Invoice " . $dodis;
        $writer = new Xlsx($spreadsheet);
        $filename = $stringjudul; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
    public function report_summary_return_as_xls()
    {
        $bsgrp = '';
        $pdate1 = '';
        $pdate2 = '';
        if (isset($_COOKIE["CKPSI_BG"])) {
            $bsgrp = $_COOKIE["CKPSI_BG"];
        } else {
            exit('nothing to be exported');
        }
        $pdate1 = $_COOKIE["CKPSI_DATE1"];
        $pdate2 = $_COOKIE["CKPSI_DATE2"];
        $sbgroup = $bsgrp;
        $rs = $this->ITH_mod->select_deliv_part_to3rdparty($pdate1, $pdate2, $sbgroup);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('RESUME');
        $sheet->setCellValueByColumnAndRow(1, 1, 'RETURN');
        $sheet->setCellValueByColumnAndRow(1, 2, 'PERIOD : ' . str_replace('-', '/', $pdate1) . ' - ' . str_replace('-', '/', $pdate2));
        $sheet->setCellValueByColumnAndRow(1, 3, 'BUSINESS : ' . str_replace("'", "", $sbgroup));

        $sheet->setCellValueByColumnAndRow(1, 4, 'Business');
        $sheet->setCellValueByColumnAndRow(2, 4, 'Trans Date');
        $sheet->setCellValueByColumnAndRow(3, 4, 'TX ID');
        $sheet->setCellValueByColumnAndRow(4, 4, 'Invoice');
        $sheet->setCellValueByColumnAndRow(5, 4, 'MEGA Doc');
        $sheet->setCellValueByColumnAndRow(6, 4, 'Consignee');
        $sheet->setCellValueByColumnAndRow(7, 4, 'Reff Document');
        $sheet->setCellValueByColumnAndRow(8, 4, 'Description');
        $sheet->setCellValueByColumnAndRow(9, 4, 'Item Code');
        $sheet->setCellValueByColumnAndRow(10, 4, 'Item Name');
        $sheet->setCellValueByColumnAndRow(11, 4, 'Return Qty');
        $sheet->setCellValueByColumnAndRow(12, 4, 'Location From');
        $sheet->setCellValueByColumnAndRow(13, 4, 'Price');
        $sheet->setCellValueByColumnAndRow(14, 4, 'Amount');
        $sheet->setCellValueByColumnAndRow(15, 4, '3rd Party');
        $inx = 5;
        foreach ($rs as $r) {
            $sheet->setCellValueByColumnAndRow(1, $inx, $r['DLV_BSGRP']);
            $sheet->setCellValueByColumnAndRow(2, $inx, $r['DLV_DATE']);
            $sheet->setCellValueByColumnAndRow(3, $inx, $r['ITH_DOC']);
            $sheet->setCellValueByColumnAndRow(4, $inx, $r['DLV_SMTINVNO']);
            $sheet->setCellValueByColumnAndRow(5, $inx, $r['DLV_PARENTDOC']);
            $sheet->setCellValueByColumnAndRow(6, $inx, $r['DLV_CONSIGN']);
            $sheet->setCellValueByColumnAndRow(7, $inx, $r['DLV_RPRDOC']);
            $sheet->setCellValueByColumnAndRow(8, $inx, $r['DLV_DSCRPTN']);
            $sheet->setCellValueByColumnAndRow(9, $inx, $r['ITH_ITMCD']);
            $sheet->setCellValueByColumnAndRow(10, $inx, $r['ITMDESCD']);
            $sheet->setCellValueByColumnAndRow(11, $inx, $r['DLVPRC_QTY']);
            $sheet->setCellValueByColumnAndRow(12, $inx, $r['DLV_LOCFR']);
            $sheet->setCellValueByColumnAndRow(13, $inx, $r['DLVPRC_PRC']);
            $sheet->setCellValueByColumnAndRow(14, $inx, $r['AMOUNT']);
            $sheet->setCellValueByColumnAndRow(15, $inx, $r['MSUP_SUPCR']);
            $inx++;
        }
        foreach (range('B', 'N') as $r) {
            $sheet->getColumnDimension($r)->setAutoSize(true);
        }
        $rang = "M1:M" . $inx;
        $sheet->getStyle($rang)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $dodis = $pdate1 . " to " . $pdate2;
        $stringjudul = "Summary Part Return " . $dodis;
        $writer = new Xlsx($spreadsheet);
        $filename = $stringjudul; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function report_summary_inv()
    {
        header('Content-Type: application/json');
        $pdate1 = $this->input->get('date1');
        $pdate2 = $this->input->get('date2');
        $cbgroup = $this->input->get('bsgrp');
        $sbgroup = "";
        if (is_array($cbgroup)) {
            for ($i = 0; $i < count($cbgroup); $i++) {
                $sbgroup .= "'$cbgroup[$i]',";
            }
            $sbgroup = substr($sbgroup, 0, strlen($sbgroup) - 1);
            if ($sbgroup == '') {
                $sbgroup = "''";
            }
        } else {
            $sbgroup = "''";
        }
        $rs = $this->ITH_mod->select_deliv_invo($pdate1, $pdate2, $sbgroup);
        die(json_encode(['data' => $rs]));
    }

    public function report_summary_part_return()
    {
        header('Content-Type: application/json');
        $pdate1 = $this->input->get('date1');
        $pdate2 = $this->input->get('date2');
        $cbgroup = $this->input->get('bsgrp');
        $sbgroup = "";
        if (is_array($cbgroup)) {
            for ($i = 0; $i < count($cbgroup); $i++) {
                $sbgroup .= "'$cbgroup[$i]',";
            }
            $sbgroup = substr($sbgroup, 0, strlen($sbgroup) - 1);
            if ($sbgroup == '') {
                $sbgroup = "''";
            }
        } else {
            $sbgroup = "''";
        }
        $rs = $this->ITH_mod->select_deliv_part_to3rdparty($pdate1, $pdate2, $sbgroup);
        die(json_encode(['data' => $rs]));
    }

    public function reportDisposeRM()
    {
        ini_set('max_execution_time', '-1');
        $rsRM = $this->DisposeDraft_mod->selectDraft('P-DEC22-JAN23');
        $ScrapDocs = ['22/11/28/0003',
        '22/11/29/0004',
        '22/12/01/0001',
        '22/12/05/0001',
        '22/12/07/0001',
        '22/12/07/0004',
        '22/12/12/0006',
        '22/12/15/0003',
        '22/12/15/0004',
        '22/12/16/0003',
        '22/12/17/0001',
        '22/12/19/0003',
        '22/12/20/0007',
        '22/12/21/0002',
        '22/12/27/0002',
        '22/12/27/0005',
        '22/12/27/0007',
        '22/12/27/0009',
        '22/12/29/0001',
        '22/12/29/0002',
        '22/12/29/0003',
        '23/01/02/0001',
        '23/01/02/0002',
        '23/01/04/0002',
        '23/01/09/0002',
        '23/01/10/0001',
        '23/01/10/0003',
        '23/01/10/0004',
        '23/01/12/0001',
        '23/01/12/0002',
        '23/01/12/0003',
        '23/01/16/0003',
        '23/01/17/0001',
        '23/01/17/0002',
        '23/01/18/0002',
        '23/01/19/0001',
        '23/01/19/0002',
        '23/01/20/0001',
        '23/01/23/0001',
        '23/01/25/0005',
        '23/01/26/0007',
        '23/01/30/0001',
        '23/02/01/0001',
        '23/02/02/0002',
        '23/02/06/0001',
        '23/02/08/0002',
        '23/02/08/0003',
        '23/02/08/0004',
        '23/02/09/0001',
        '23/02/09/0002',
        '23/02/09/0004',
        '23/02/09/0005',
        '23/02/10/0001',
        '23/02/10/0002',
        '23/02/13/0003',
        '23/02/14/0004',
        '23/02/14/0006',
        '23/02/15/0001',
        '23/02/15/0003',
        '23/02/17/0004',
        '23/02/20/0004',
        '23/02/21/0001',
        '23/02/22/0006',
        '23/02/23/0003',
        '23/02/23/0004',
        '23/02/23/0005',
        '23/02/27/0011',
        '23/03/01/0003',
        '23/03/01/0004',
        '23/03/01/0005',
        '23/03/06/0003',
        '23/03/06/0002',
        '23/03/06/0004',
        '23/03/07/0004',
        '23/03/13/0001',
        '23/03/13/0002',
        '23/03/13/0003',
        '23/03/14/0002',
        '23/03/15/0003',
        '23/03/15/0004',
        '23/03/17/0001',
        '23/03/20/0002',
        '23/03/21/0003',
        '23/03/24/0001',
        '23/03/24/0003',
        '23/03/24/0013',
        '23/03/27/0014',
        '23/03/27/0015',
        '23/03/27/0016',
        '23/03/28/0002',
        '23/03/28/0003',
        '23/03/28/0004',
        '23/03/30/0004',
        '23/03/30/0006',
        '23/03/30/0007',
        '23/03/30/0008',
        '23/03/31/0008',
        '23/04/03/0009',
        '23/04/03/0010',
        '23/04/04/0003',
        '23/04/04/0004',
        '23/04/05/0001',
        '23/04/10/0001',
        '23/04/13/0001',
        '23/04/17/0001',
        '23/04/18/0001',
        '23/04/18/0002',
        '23/04/27/0006',
        '23/04/27/0007',
        '23/04/28/0008',
        '23/04/28/0009',
        '23/05/02/0008',
        '23/05/02/0009',
        '23/05/02/0010',
        '23/05/03/0009',
        '23/05/04/0014',
        '23/05/04/0016',
        '23/05/05/0011',
        '23/05/05/0015',
        '23/05/05/0018',
        '23/05/05/0024',
        '23/05/08/0001',
        '23/05/09/0003',
        '23/05/12/0002',
        '23/05/17/0002',
        '23/05/17/0003',
        '23/05/17/0004',
        '23/05/19/0002',
        '23/05/22/0001',
        '23/05/22/0002',
        '23/05/22/0003',
        '23/05/22/0004',
        '23/05/26/0001',
        '23/05/26/0003',
        '23/05/29/0002',
        '23/05/30/0001',
        '23/05/31/0001',
        '23/06/05/0001',
        '23/06/06/0002',
        '23/06/12/0001',
        '23/06/12/0002',
        '23/06/12/0003',
        '23/06/12/0004',
        '23/06/13/0001',
        '23/06/14/0005',
        '23/06/19/0001',
        '23/06/19/0002',
        '23/06/19/0003',
        '23/06/23/0001',
        '23/06/23/0002',
        '23/06/23/0003',
        '23/06/23/0004',
        'P-DEC22-JAN23'
        ];
        
        $rsEXBC = $this->ZRPSTOCK_mod->select_group_columns_where_remark_in(
            [
                "UPPER(rtrim(RPSTOCK_ITMNUM)) ITMNUM", "SUM(ABS(RPSTOCK_QTY)) BCQTY", "SUM(ABS(RPSTOCK_QTY)) BCBAKQTY", "RPSTOCK_DOC", "RPSTOCK_NOAJU", "RPSTOCK_BCNUM", "RPSTOCK_BCDATE", "RPSTOCK_BCTYPE",
                "RCV_BM", "RCV_PPN", "RCV_PPH", "URUT", "RPSTOCK_REMARK",
            ],
            $ScrapDocs
        );

        $rsfix = [];
        foreach ($rsRM as &$r) {
            $r['QTYPLOT'] = 0;
            foreach ($rsEXBC as &$k) {
                $reqPlot = $r['QTY'] - $r['QTYPLOT'];
                if ($r['PART_CODE'] === $k['ITMNUM'] && $reqPlot > 0 && $k['BCQTY'] > 0) {
                    $theqty = $reqPlot;
                    if ($reqPlot > $k['BCQTY']) {
                        $r['QTYPLOT'] += $k['BCQTY'];
                        $theqty = $k['BCQTY'];
                        $k['BCQTY'] = 0;
                    } else {
                        $k['BCQTY'] -= $reqPlot;
                        $r['QTYPLOT'] += $reqPlot;
                    }

                    $_isFound = false;
                    foreach($rsfix as &$s) {
                        if($s['ITEM_CODE'] === $r['PART_CODE']
                        && $s['AJU'] === $k['RPSTOCK_NOAJU']
                        && $s['BCNO'] === $k['RPSTOCK_BCNUM']
                        && $s['URUT'] === $k['URUT']
                        ) {
                            $s['BCQT'] += $theqty;
                            $_isFound = true;
                            break;
                        }
                    }
                    unset($s);

                    if(!$_isFound) {
                        $rsfix[] = [                        
                            'ITEM_DESCRIPTION' => $r['RMDESC'],
                            'ITEM_CODE' => $r['PART_CODE'],                        
                            'UNITMEASUREMENT' => $r['RMUOM'],
                            'BCQT' => $theqty,
                            'BCTYPE' => $k['RPSTOCK_BCTYPE'],
                            'BCNO' => $k['RPSTOCK_BCNUM'],
                            'BCDATE' => $k['RPSTOCK_BCDATE'],
                            'URUT' => $k['URUT'],
                            'AJU' => $k['RPSTOCK_NOAJU'],
                            'BM' => $k['RCV_BM'],
                            'PPN' => $k['RCV_PPN'],
                            'PPH' => $k['RCV_PPH'],
                        ];
                    }

                    if ($r['QTY'] == $r['QTYPLOT']) {
                        break;
                    }
                }
            }
            unset($k);
        }
        unset($r);      
        
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('RESUME');
        $sheet->fromArray(array_keys($rsfix[0]), null, 'A1');
        $sheet->fromArray($rsfix, null, 'A2');

        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('TRACE_REQ');
        $sheet->fromArray(array_keys($rsRM[0]), null, 'A1');
        $sheet->fromArray($rsRM, null, 'A2');

        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('TRACE_EXBC');
        $sheet->fromArray(array_keys($rsEXBC[0]), null, 'A1');
        $sheet->fromArray($rsEXBC, null, 'A2');

        $stringjudul = "DISD2310_RM";
        $writer = new Xlsx($spreadsheet);
        $filename = $stringjudul; //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
}
