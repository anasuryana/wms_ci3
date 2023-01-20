<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PO extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('download');
        $this->load->library('session');
        $this->load->library('Code39e128');
        $this->load->model('MDEPT_mod');
        $this->load->model('PO_mod');
        $this->load->model('RCVNI_mod');
        $this->load->model('MSTITM_mod');
        $this->load->model('TREQPARTLKL_mod');
    }
    public function index()
    {
        echo "sorry";
    }
    public function form()
    {
        $rsdept = $this->MDEPT_mod->select_all();
        $strdept = '';
        $strdept1 = '';
        foreach ($rsdept as $r) {
            $strdept .= '<tr><td>' . $r['MDEPT_CD'] . '</td><td>' . $r['MDEPT_NM'] . '</td></tr>';
            $strdept1 .= "'" . $r['MDEPT_CD'] . "',";
        }
        $data['deptl'] = $strdept;
        $data['deptl_1'] = $strdept1;
        $this->load->view('wms/vpo', $data);
    }

    function print() {
        $Rcolor = 0;
        $Gcolor = 201;
        $Bcolor = 250;
        $pser = '';
        if (isset($_COOKIE["PONUM"])) {
            $pser = $_COOKIE["PONUM"];
        } else {
            exit('nothing to be printed');
        }
        $rs = $this->PO_mod->select_detail_where(['PO_NO' => $pser]);
        $rsDiscount = $this->PO_mod->select_discount_detail_where(['PODISC_PONO' => $pser]);
        $_y = 10;
        $requsted_by = '';
        $shipment = '';
        $currency = '';
        $payment_term = '';
        $supplier = '';
        $supplier_address = '';
        $supplier_telno = '';
        $supplier_faxno = '';
        $total_amount = 0;
        $ppn = 0;
        $pph = 0;
        $trans_date = '';
        $required_date = '';
        $shipping_cost = 0;
        $netpayment = 0;
        $poremark = '';
        foreach ($rs as $r) {
            $requsted_by = $r['PO_RQSTBY'];
            $shipment = $r['PO_SHPDLV'];
            $currency = $r['MSUP_SUPCR'];
            $payment_term = $r['PO_PAYTERM'];
            $supplier = $r['MSUP_SUPNM'];
            $supplier_address = RTRIM($r['MSUP_ADDR1']);
            $supplier_telno = $r['MSUP_TELNO'];
            $supplier_faxno = $r['MSUP_FAXNO'];
            $ppn = $r['PO_VAT'] * 1;
            $pph = $r['PO_PPH'] * 1;
            $required_date = date_create($r['PO_REQDT']);
            $required_date = date_format($required_date, 'd/m/Y');
            $trans_date = date_create($r['PO_ISSUDT']);
            $trans_date = date_format($trans_date, 'd/m/Y');
            $shipping_cost = $r['PO_SHPCOST'];
            $poremark = $r['PO_RMRK'];
            break;
        }
        $pdf = new PDF_Code39e128('P', 'mm', 'A4');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetAutoPageBreak(true, 1);
        $pdf->SetMargins(0, 0);
        #company
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->SetXY(160, 299 - $_y);
        $pdf->Cell(15, 4, 'Page ' . $pdf->PageNo() . ' / {nb}', 0, 0, 'C');
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetTextColor(0, 0, 240);
        $pdf->SetXY(6, 22 - $_y);
        $pdf->Cell(50, 4, 'PT. SMT INDONESIA', 0, 0, 'L');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Times', 'IB', 9);
        $pdf->SetXY(6, 26 - $_y);
        $pdf->Cell(50, 4, 'JL. Cisokan 5 Plot 5C-2 EJIP Industrial Park', 0, 0, 'L');
        $pdf->SetXY(6, 30 - $_y);
        $pdf->Cell(80, 4, 'Sukaresmi Cikarang Selatan', 0, 0, 'L');
        $pdf->SetXY(6, 30 - $_y + 4);
        $pdf->Cell(80, 4, 'Kab. Bekasi Jawa Barat', 0, 0, 'L');
        $pdf->SetXY(6, 34 - $_y + 4);
        $pdf->Cell(80, 4, 'Tel.    : +62-21-8970-567 (Hunting), 468,469,470', 0, 0, 'L');
        $pdf->SetFont('Times', 'IB', 8);
        $pdf->SetXY(6, 38 - $_y + 4);
        $pdf->Cell(98, 4, 'Fax.    : +62-21-8970-577 (Purchasing Department / Accounting & Financial Dept)', 0, 0, 'L');
        $pdf->SetFont('Times', 'IB', 9);
        $pdf->SetXY(6, 42 - $_y + 4);
        $pdf->Cell(80, 4, 'Fax.   : +62-21-8975-333 (Manufacturing Department)', 0, 0, 'L');
        #end company
        $pdf->SetFont('Times', 'B', 7);
        $pdf->SetXY(160, 18 - $_y);
        $pdf->Cell(21, 4, 'FORM-FPI-04-01', 0, 0, 'L');
        $pdf->SetXY(160, 22 - $_y);
        $pdf->Cell(21, 4, 'REV.02', 0, 0, 'R');
        $pdf->SetFont('Times', 'IB', 9);
        $pdf->SetXY(146, 30 - $_y);
        $pdf->Cell(35, 4, 'Copy : Accounting Dept.', 0, 0, 'R');
        $pdf->SetFont('Times', 'B', 9);
        $pdf->SetXY(115, 45 - $_y);
        $pdf->Cell(85, 7, '   P / O No. : ' . $pser, 1, 0, 'L');
        $pdf->SetFont('Times', 'B', 9);
        $pdf->SetXY(115, 52 - $_y);
        $pdf->Cell(85, 4, 'DATE / Tanggal : ' . $trans_date, 0, 0, 'C');

        $pdf->SetFont('Times', 'BU', 13);
        $pdf->SetXY(80, 60 - $_y);
        $pdf->Cell(40, 4, 'PURCHASE ORDER', 0, 0, 'L');

        $pdf->SetFont('Times', 'B', 9);
        $pdf->SetXY(6, 68 - $_y);
        $pdf->setFillColor($Rcolor, $Gcolor, $Bcolor);
        $pdf->Cell(40, 5, 'Requested By : ( Name )', 1, 0, 'C', 1);
        $pdf->Cell(60, 5, 'Ship / Delivery Via', 1, 0, 'C', 1);
        $pdf->Cell(40, 5, 'Currency', 1, 0, 'C', 1);
        $pdf->Cell(55, 5, 'Payment Terms', 1, 0, 'C', 1);
        $pdf->SetFont('Times', '', 9);
        $pdf->SetXY(6, 73 - $_y);
        $pdf->Cell(40, 5, $requsted_by, 1, 0, 'C');
        $pdf->Cell(60, 5, $shipment, 1, 0, 'C');
        $pdf->Cell(40, 5, $currency, 1, 0, 'C');
        $pdf->Cell(55, 5, $payment_term, 1, 0, 'C');
        $pdf->SetFont('Times', 'B', 9);
        $pdf->SetXY(6, 78 - $_y);
        $pdf->Cell(40, 5, 'Supplier Name', 1, 0, 'C', 1);
        $pdf->Cell(100, 5, 'Address', 1, 0, 'C', 1);
        $pdf->Cell(27.5, 5, 'Phone No.', 1, 0, 'C', 1);
        $pdf->Cell(27.5, 5, 'Fax. No.', 1, 0, 'C', 1);
        $pdf->SetXY(6, 83 - $_y);
        $pdf->Cell(40, 10, '', 1, 0, 'C');
        $pdf->Cell(100, 10, '', 1, 0, 'C');
        $pdf->Cell(27.5, 10, '', 1, 0, 'C');
        $pdf->Cell(27.5, 10, '', 1, 0, 'C');
        $pdf->SetFont('Times', '', 9);
        $pdf->SetXY(6, 83 - $_y);
        $pdf->MultiCell(40, 4, $supplier, 0, 'L');

        $pdf->GetY();
        $pdf->SetXY(46, 83 - $_y);
        $pdf->MultiCell(100, 4, $supplier_address, 0, "L");
        $pdf->SetXY(146, 83 - $_y);
        $pdf->MultiCell(27.5, 4, $supplier_telno, 0);
        $pdf->SetXY(173.5, 83 - $_y);
        $pdf->MultiCell(27.5, 4, $supplier_faxno, 0);

        $pdf->SetFont('Times', 'B', 9);
        $pdf->SetXY(6, 94 - $_y);
        $pdf->setFillColor($Rcolor, $Gcolor, $Bcolor);
        $pdf->Cell(10, 10, '', 1, 0, 'C', 1); #item
        $pdf->Cell(30, 10, '', 1, 0, 'C', 1); #part number
        $pdf->Cell(60, 10, '', 1, 0, 'C', 1); #part name
        $pdf->Cell(15, 10, '', 1, 0, 'C', 1); #unit measure
        $pdf->Cell(25, 10, '', 1, 0, 'C', 1); #qty
        $pdf->Cell(27.5, 10, '', 1, 0, 'C', 1); #unit price
        $pdf->Cell(27.5, 10, '', 1, 0, 'C', 1); #amount

        $pdf->SetXY(6, 94 - $_y);
        $pdf->Cell(10, 5, 'Item', 0, 0, 'C');
        $pdf->Cell(30, 5, 'Part Number', 0, 0, 'C');
        $pdf->Cell(60, 5, 'Part Name / Description', 0, 0, 'C'); #part name
        $pdf->Cell(15, 5, 'Unit', 0, 0, 'C'); #unit measure
        $pdf->Cell(25, 5, 'Qty', 0, 0, 'C'); #qty
        $pdf->Cell(27.5, 5, 'Unit Price', 0, 0, 'C'); #unit price
        $pdf->Cell(27.5, 5, 'Amount', 0, 0, 'C'); #amount

        $pdf->SetFont('Times', 'BI', 9);
        $pdf->SetXY(6, 98 - $_y);
        $pdf->Cell(10, 5, 'No', 0, 0, 'C');
        $pdf->Cell(30, 5, 'Nomor Barang', 0, 0, 'C');
        $pdf->Cell(60, 5, 'Nama Barang / Keterangan', 0, 0, 'C'); #part name
        $pdf->Cell(15, 5, 'Satuan', 0, 0, 'C'); #unit measure
        $pdf->Cell(25, 5, 'Jumlah', 0, 0, 'C'); #qty
        $pdf->Cell(27.5, 5, 'Harga per satuan', 0, 0, 'C'); #unit price
        $pdf->Cell(27.5, 5, 'Jumlah', 0, 0, 'C'); #amount
        $adata_section = [];
        $adata_department = [];
        $adata_subject = [];

        #discount resume
        $ttldiscount_price = 0;
        $discountlist_distinct = [];
        $discount_msg = '';
        foreach ($rs as $r) {
            if ($r['PO_DISC'] > 0) {
                $isfound = false;
                foreach ($discountlist_distinct as &$t) {
                    if ($r['PO_DISC'] == $t['PO_DISC']) {
                        $t['COUNT']++;
                        $isfound = true;
                        break;
                    }
                }
                unset($t);
                if (!$isfound) {
                    $discountlist_distinct[] = [
                        'PO_DISC' => $r['PO_DISC'],
                        'COUNT' => 1,
                    ];
                }
            }
            $amount = $r['PO_PRICE'] * $r['PO_QTY'];
            $discount_price = $amount * ($r['PO_DISC'] / 100);
            $ttldiscount_price += $discount_price;
        }
        #additional row for discount
        if ($ttldiscount_price > 0) {
            if (count($discountlist_distinct) == 1) {
                foreach ($discountlist_distinct as $a) {
                    if ($a['COUNT'] == count($rs)) {
                        $discount_msg = '1 lot @ ' . ($a['PO_DISC'] * 1) . '%';
                    }
                }
                if ($discount_msg == '') {
                    $discountlist_distinct_row = 1;
                    $discountlist_distinct_row_b4 = '';
                    foreach ($discountlist_distinct as &$b) {
                        $rs_row = 1;
                        $minR = 0;
                        $maxR = 0;
                        foreach ($rs as $a) {
                            if ($b['PO_DISC'] == $a['PO_DISC']) {
                                if ($discountlist_distinct_row_b4 != $discountlist_distinct_row) {
                                    $discountlist_distinct_row_b4 = $discountlist_distinct_row;
                                    $minR = $rs_row;
                                    $maxR = $rs_row;
                                } else {
                                    $maxR = $rs_row;
                                }
                            }
                            $rs_row++;
                        }
                        $b['MIN_Y'] = $minR;
                        $b['MAX_Y'] = $maxR;
                        $discountlist_distinct_row++;
                    }
                    unset($b);
                    $sort = array();
                    foreach ($discountlist_distinct as $k => $v) {
                        $sort['MIN_Y'][$k] = $v['MIN_Y'];
                    }
                    array_multisort($sort['MIN_Y'], SORT_ASC, $discountlist_distinct);
                    foreach ($discountlist_distinct as $n) {
                        if ($n['MAX_Y'] - $n['MIN_Y'] == 0) {
                            $discount_msg .= $n['MAX_Y'] . " @" . ($n['PO_DISC'] * 1) . "%, ";
                        } else {
                            $discount_msg .= $n['MIN_Y'] . "-" . $n['MAX_Y'] . " @" . ($n['PO_DISC'] * 1) . "%, ";
                        }
                    }
                    $discount_msg = substr($discount_msg, 0, strlen($discount_msg) - 2);
                }
            } else {
                $discountlist_distinct_row = 1;
                $discountlist_distinct_row_b4 = '';
                foreach ($discountlist_distinct as &$b) {
                    $rs_row = 1;
                    $minR = 0;
                    $maxR = 0;
                    foreach ($rs as $a) {
                        if ($b['PO_DISC'] == $a['PO_DISC']) {
                            if ($discountlist_distinct_row_b4 != $discountlist_distinct_row) {
                                $discountlist_distinct_row_b4 = $discountlist_distinct_row;
                                $minR = $rs_row;
                                $maxR = $rs_row;
                            } else {
                                $maxR = $rs_row;
                            }
                        }
                        $rs_row++;
                    }
                    $b['MIN_Y'] = $minR;
                    $b['MAX_Y'] = $maxR;
                    $discountlist_distinct_row++;
                }
                unset($b);
                $sort = array();
                foreach ($discountlist_distinct as $k => $v) {
                    $sort['MIN_Y'][$k] = $v['MIN_Y'];
                }
                array_multisort($sort['MIN_Y'], SORT_ASC, $discountlist_distinct);
                foreach ($discountlist_distinct as $n) {
                    if ($n['MAX_Y'] - $n['MIN_Y'] == 0) {
                        $discount_msg .= $n['MAX_Y'] . " @" . ($n['PO_DISC'] * 1) . "%, ";
                    } else {
                        $discount_msg .= $n['MIN_Y'] . "-" . $n['MAX_Y'] . " @" . ($n['PO_DISC'] * 1) . "%, ";
                    }
                }
                $discount_msg = substr($discount_msg, 0, strlen($discount_msg) - 2);
            }
        }
        $ttldiscount_priceSpecial = 0;
        foreach ($rsDiscount as $r) {
            $discount_msg .= $r['PODISC_DESC'];
            $ttldiscount_priceSpecial += $r['PODISC_DISC'];
        }
        #end

        #analyze required sheets before generate PDF
        $_num = 0;
        $ke = 1;
        foreach ($rs as $r) {
            $itemcd = $r['PO_ITMCD'] ? trim($r['PO_ITMCD']) : "NON ITEM";
            $itemcd = stripslashes($itemcd);
            $itemcd = iconv('UTF-8', 'windows-1252', $itemcd);
            $itemname = $r['PO_ITMNM'] ? $r['PO_ITMNM'] : $r['MITM_ITMD1'];
            $itemname = stripslashes($itemname);
            $itemname = iconv('UTF-8', 'windows-1252', $itemname);
            $YExtra1 = 1;
            $YExtra2 = $pdf->GetStringWidth($itemname) > 60 ? 2 : 1;
            if (strpos($itemcd, " ") !== false) {
                if ($pdf->GetStringWidth($itemcd) > 30) {
                    $YExtra1 = 2;
                }
            }
            $rowsneeded = $YExtra1 > $YExtra2 ? $YExtra1 : $YExtra2;
            $_num += $rowsneeded;
            $ke++;
        }
        #end
        if ($_num > 15) {
            $MAXLENGTH_LINE_TO_BOTTOM = 195;
            $pdf->SetFont('Times', '', 9);
            $pdf->SetXY(6, 104 - $_y);
            $pdf->Cell(10, $MAXLENGTH_LINE_TO_BOTTOM, '', 1, 0, 'C');
            $pdf->Cell(30, $MAXLENGTH_LINE_TO_BOTTOM, '', 1, 0, 'C');
            $pdf->Cell(60, $MAXLENGTH_LINE_TO_BOTTOM, '', 1, 0, 'C'); #part name
            $pdf->Cell(15, $MAXLENGTH_LINE_TO_BOTTOM, '', 1, 0, 'C'); #unit measure
            $pdf->Cell(25, $MAXLENGTH_LINE_TO_BOTTOM, '', 1, 0, 'C'); #qty
            $pdf->Cell(27.5, $MAXLENGTH_LINE_TO_BOTTOM, '', 1, 0, 'C'); #unit price
            $pdf->Cell(27.5, $MAXLENGTH_LINE_TO_BOTTOM, '', 1, 0, 'C'); #amount
            $nomor_urut = 1;
            $YStart = (104 - $_y) - 5 + 5;
            foreach ($rs as $r) {
                if ($pdf->GetY() >= 280) {
                    $pdf->AddPage();
                    #company
                    $pdf->SetFont('Arial', 'B', 7);
                    $pdf->SetXY(90, 15 - $_y);
                    $pdf->Cell(15, 4, 'Page ' . $pdf->PageNo() . ' / {nb}', 0, 0, 'C');
                    $pdf->SetFont('Arial', 'B', 10);
                    $pdf->SetXY(6, 22 - $_y);
                    $pdf->SetTextColor(0, 0, 240);
                    $pdf->Cell(50, 4, 'PT. SMT INDONESIA', 0, 0, 'L');
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetFont('Times', 'IB', 9);
                    $pdf->SetXY(6, 26 - $_y);
                    $pdf->Cell(50, 4, 'EJIP Industrial Park Plot 5C - 2', 0, 0, 'L');
                    $pdf->SetXY(6, 30 - $_y);
                    $pdf->Cell(80, 4, 'Cikarang Selatan, Bekasi 17550 INDONESIA', 0, 0, 'L');
                    $pdf->SetXY(6, 34 - $_y);
                    $pdf->Cell(80, 4, 'Tel.    : +62-21-8970-567 (Hunting), 468,469,470', 0, 0, 'L');
                    $pdf->SetFont('Times', 'IB', 8);
                    $pdf->SetXY(6, 38 - $_y);
                    $pdf->Cell(98, 4, 'Fax.    : +62-21-8970-577 (Purchasing Department / Accounting & Financial Dept)', 0, 0, 'L');
                    $pdf->SetFont('Times', 'IB', 9);
                    $pdf->SetXY(6, 42 - $_y);
                    $pdf->Cell(80, 4, 'Fax.   : +62-21-8975-333 (Manufacturing Department)', 0, 0, 'L');
                    #end company
                    $pdf->SetFont('Times', 'B', 7);
                    $pdf->SetXY(160, 18 - $_y);
                    $pdf->Cell(21, 4, 'FORM-FPI-04-01', 0, 0, 'L');
                    $pdf->SetXY(160, 22 - $_y);
                    $pdf->Cell(21, 4, 'REV.02', 0, 0, 'R');
                    $pdf->SetFont('Times', 'IB', 9);
                    $pdf->SetXY(146, 30 - $_y);
                    $pdf->Cell(35, 4, 'Copy : Accounting Dept.', 0, 0, 'R');
                    $pdf->SetFont('Times', 'B', 9);
                    $pdf->SetXY(115, 45 - $_y);
                    $pdf->Cell(85, 7, '   P / O No. : ' . $pser, 1, 0, 'L');
                    $pdf->SetFont('Times', 'B', 9);
                    $pdf->SetXY(115, 52 - $_y);
                    $pdf->Cell(85, 4, 'DATE / Tanggal : ' . $trans_date, 0, 0, 'C');

                    $pdf->SetFont('Times', 'BU', 13);
                    $pdf->SetXY(80, 60 - $_y);
                    $pdf->Cell(40, 4, 'PURCHASE ORDER', 0, 0, 'L');

                    $pdf->SetFont('Times', 'B', 9);
                    $pdf->SetXY(6, 68 - $_y);
                    $pdf->setFillColor($Rcolor, $Gcolor, $Bcolor);
                    $pdf->Cell(40, 5, 'Requested By : ( Name )', 1, 0, 'C', 1);
                    $pdf->Cell(60, 5, 'Ship / Delivery Via', 1, 0, 'C', 1);
                    $pdf->Cell(40, 5, 'Currency', 1, 0, 'C', 1);
                    $pdf->Cell(55, 5, 'Payment Terms', 1, 0, 'C', 1);
                    $pdf->SetFont('Times', '', 9);
                    $pdf->SetXY(6, 73 - $_y);
                    $pdf->Cell(40, 5, $requsted_by, 1, 0, 'C');
                    $pdf->Cell(60, 5, $shipment, 1, 0, 'C');
                    $pdf->Cell(40, 5, $currency, 1, 0, 'C');
                    $pdf->Cell(55, 5, $payment_term, 1, 0, 'C');
                    $pdf->SetFont('Times', 'B', 9);
                    $pdf->SetXY(6, 78 - $_y);
                    $pdf->Cell(40, 5, 'Supplier Name', 1, 0, 'C', 1);
                    $pdf->Cell(100, 5, 'Address', 1, 0, 'C', 1);
                    $pdf->Cell(27.5, 5, 'Phone No.', 1, 0, 'C', 1);
                    $pdf->Cell(27.5, 5, 'Fax. No.', 1, 0, 'C', 1);
                    $pdf->SetXY(6, 83 - $_y);
                    $pdf->Cell(40, 10, '', 1, 0, 'C');
                    $pdf->Cell(100, 10, '', 1, 0, 'C');
                    $pdf->Cell(27.5, 10, '', 1, 0, 'C');
                    $pdf->Cell(27.5, 10, '', 1, 0, 'C');
                    $pdf->SetFont('Times', '', 9);
                    $pdf->SetXY(6, 83 - $_y);
                    $pdf->MultiCell(40, 4, $supplier, 0, 'L');
                    $Yitu = $pdf->GetY();
                    $pdf->SetXY(46, 83 - $_y);
                    $pdf->MultiCell(100, 4, $supplier_address, 0, "L");
                    $pdf->SetXY(146, 83 - $_y);
                    $pdf->MultiCell(27.5, 4, $supplier_telno, 0);
                    $pdf->SetXY(173.5, 83 - $_y);
                    $pdf->MultiCell(27.5, 4, $supplier_faxno, 0);

                    $pdf->SetFont('Times', 'B', 9);
                    $pdf->SetXY(6, 94 - $_y);
                    $pdf->setFillColor($Rcolor, $Gcolor, $Bcolor);
                    $pdf->Cell(10, 10, '', 1, 0, 'C', 1); #item
                    $pdf->Cell(30, 10, '', 1, 0, 'C', 1); #part number
                    $pdf->Cell(60, 10, '', 1, 0, 'C', 1); #part name
                    $pdf->Cell(15, 10, '', 1, 0, 'C', 1); #unit measure
                    $pdf->Cell(25, 10, '', 1, 0, 'C', 1); #qty
                    $pdf->Cell(27.5, 10, '', 1, 0, 'C', 1); #unit price
                    $pdf->Cell(27.5, 10, '', 1, 0, 'C', 1); #amount

                    $pdf->SetXY(6, 94 - $_y);
                    $pdf->Cell(10, 5, 'Item', 0, 0, 'C');
                    $pdf->Cell(30, 5, 'Part Number', 0, 0, 'C');
                    $pdf->Cell(60, 5, 'Part Name / Description', 0, 0, 'C'); #part name
                    $pdf->Cell(15, 5, 'Unit', 0, 0, 'C'); #unit measure
                    $pdf->Cell(25, 5, 'Qty', 0, 0, 'C'); #qty
                    $pdf->Cell(27.5, 5, 'Unit Price', 0, 0, 'C'); #unit price
                    $pdf->Cell(27.5, 5, 'Amount', 0, 0, 'C'); #amount

                    $pdf->SetFont('Times', 'BI', 9);
                    $pdf->SetXY(6, 98 - $_y);
                    $pdf->Cell(10, 5, 'No', 0, 0, 'C');
                    $pdf->Cell(30, 5, 'Nomor Barang', 0, 0, 'C');
                    $pdf->Cell(60, 5, 'Nama Barang / Keterangan', 0, 0, 'C'); #part name
                    $pdf->Cell(15, 5, 'Satuan', 0, 0, 'C'); #unit measure
                    $pdf->Cell(25, 5, 'Jumlah', 0, 0, 'C'); #qty
                    $pdf->Cell(27.5, 5, 'Harga per satuan', 0, 0, 'C'); #unit price
                    $pdf->Cell(27.5, 5, 'Jumlah', 0, 0, 'C'); #amount

                    $pdf->SetFont('Times', '', 9);
                    $pdf->SetXY(6, 104 - $_y);
                    $pdf->Cell(10, 75, '', 1, 0, 'C');
                    $pdf->Cell(30, 75, '', 1, 0, 'C');
                    $pdf->Cell(60, 75, '', 1, 0, 'C'); #part name
                    $pdf->Cell(15, 75, '', 1, 0, 'C'); #unit measure
                    $pdf->Cell(25, 75, '', 1, 0, 'C'); #qty
                    $pdf->Cell(27.5, 75, '', 1, 0, 'C'); #unit price
                    $pdf->Cell(27.5, 75, '', 1, 0, 'C'); #amount
                    $YStart = (104 - $_y) - 5 + 5;
                }
                if ($r['PO_SECTION']) {
                    if (trim($r['PO_SECTION']) != '') {
                        if (!in_array($r['PO_SECTION'], $adata_section)) {
                            $adata_section[] = $r['PO_SECTION'];
                        }
                    }
                }
                if ($r['PO_DEPT']) {
                    if (trim($r['PO_DEPT']) != '') {
                        if (!in_array($r['PO_DEPT'], $adata_department)) {
                            $adata_department[] = $r['PO_DEPT'];
                        }
                    }
                }
                if ($r['PO_SUBJECT']) {
                    if (trim($r['PO_SUBJECT']) != '') {
                        if (!in_array($r['PO_SUBJECT'], $adata_subject)) {
                            $adata_subject[] = $r['PO_SUBJECT'];
                        }
                    }
                }
                $itemcd = $r['PO_ITMCD'] ? $r['PO_ITMCD'] : "NON ITEM";
                $itemname = $r['PO_ITMNM'] ? $r['PO_ITMNM'] : $r['MITM_ITMD1'];
                $itemum = $r['PO_UM'] ? $r['PO_UM'] : $r['MITM_STKUOM'];
                $amount = $r['PO_PRICE'] * $r['PO_QTY'];
                $discount_price = $amount * ($r['PO_DISC'] / 100);
                $finalamount = $amount - $discount_price;
                $pdf->SetXY(6, $YStart);
                $pdf->Cell(10, 5, $nomor_urut++, 0, 0, 'C');
                if (strpos($itemcd, " ") !== false) {

                    $pdf->MultiCell(30, 5, $itemcd, 0, 'L');
                    $YExtra_candidate = $pdf->GetY();
                    $YExtra2 = $YExtra_candidate != $YStart ? $YExtra_candidate - $YStart - 5 : 0;
                } else {
                    $ttlwidth = $pdf->GetStringWidth(trim($itemcd));
                    if ($ttlwidth > 30) {
                        $ukuranfont = 8.5;
                        while ($ttlwidth > 30) {
                            $pdf->SetFont('Times', '', $ukuranfont);
                            $ttlwidth = $pdf->GetStringWidth(trim($itemcd));
                            $ukuranfont = $ukuranfont - 0.5;
                        }
                    }
                    $pdf->Cell(30, 5, $itemcd, 0, 0, 'L');
                }

                $pdf->SetFont('Times', '', 9);
                $pdf->SetXY(46, $YStart);
                $pdf->MultiCell(60, 5, $itemname, 0, 'L');
                $YExtra_candidate = $pdf->GetY();
                $YExtra = $YExtra_candidate != $YStart ? $YExtra = $YExtra_candidate - $YStart - 5 : 0;
                $pdf->SetXY(106, $YStart);
                $pdf->Cell(15, 5, $itemum, 0, 0, 'C');
                $pdf->Cell(25, 5, number_format($r['PO_QTY']), 0, 0, 'R');
                $pdf->Cell(27.5, 5, number_format($r['PO_PRICE'], 2), 0, 0, 'R');
                $pdf->Cell(27.5, 5, number_format($amount, 2), 0, 0, 'R');
                $total_amount += $finalamount;
                $ttldiscount_price += $discount_price;
                $YStart += (5 + $YExtra + $YExtra2);
            }
            $pdf->AddPage();
            #company
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->SetXY(90, 15 - $_y);
            $pdf->Cell(15, 4, 'Page ' . $pdf->PageNo() . ' / {nb}', 0, 0, 'C');
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->SetXY(6, 22 - $_y);
            $pdf->SetTextColor(0, 0, 240);
            $pdf->Cell(50, 4, 'PT. SMT INDONESIA', 0, 0, 'L');
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont('Times', 'IB', 9);
            $pdf->SetXY(6, 26 - $_y);
            $pdf->Cell(50, 4, 'EJIP Industrial Park Plot 5C - 2', 0, 0, 'L');
            $pdf->SetXY(6, 30 - $_y);
            $pdf->Cell(80, 4, 'Cikarang Selatan, Bekasi 17550 INDONESIA', 0, 0, 'L');
            $pdf->SetXY(6, 34 - $_y);
            $pdf->Cell(80, 4, 'Tel.    : +62-21-8970-567 (Hunting), 468,469,470', 0, 0, 'L');
            $pdf->SetFont('Times', 'IB', 8);
            $pdf->SetXY(6, 38 - $_y);
            $pdf->Cell(98, 4, 'Fax.    : +62-21-8970-577 (Purchasing Department / Accounting & Financial Dept)', 0, 0, 'L');
            $pdf->SetFont('Times', 'IB', 9);
            $pdf->SetXY(6, 42 - $_y);
            $pdf->Cell(80, 4, 'Fax.   : +62-21-8975-333 (Manufacturing Department)', 0, 0, 'L');
            #end company
            $pdf->SetFont('Times', 'B', 7);
            $pdf->SetXY(160, 18 - $_y);
            $pdf->Cell(21, 4, 'FORM-FPI-04-01', 0, 0, 'L');
            $pdf->SetXY(160, 22 - $_y);
            $pdf->Cell(21, 4, 'REV.02', 0, 0, 'R');
            $pdf->SetFont('Times', 'IB', 9);
            $pdf->SetXY(146, 30 - $_y);
            $pdf->Cell(35, 4, 'Copy : Accounting Dept.', 0, 0, 'R');
            $pdf->SetFont('Times', 'B', 9);
            $pdf->SetXY(115, 45 - $_y);
            $pdf->Cell(85, 7, '   P / O No. : ' . $pser, 1, 0, 'L');
            $pdf->SetFont('Times', 'B', 9);
            $pdf->SetXY(115, 52 - $_y);
            $pdf->Cell(85, 4, 'DATE / Tanggal : ' . $trans_date, 0, 0, 'C');

            $pdf->SetFont('Times', 'BU', 13);
            $pdf->SetXY(80, 60 - $_y);
            $pdf->Cell(40, 4, 'PURCHASE ORDER', 0, 0, 'L');

            $pdf->SetFont('Times', 'B', 9);
            $pdf->SetXY(6, 68 - $_y);
            $pdf->setFillColor($Rcolor, $Gcolor, $Bcolor);
            $pdf->Cell(40, 5, 'Requested By : ( Name )', 1, 0, 'C', 1);
            $pdf->Cell(60, 5, 'Ship / Delivery Via', 1, 0, 'C', 1);
            $pdf->Cell(40, 5, 'Currency', 1, 0, 'C', 1);
            $pdf->Cell(55, 5, 'Payment Terms', 1, 0, 'C', 1);
            $pdf->SetFont('Times', '', 9);
            $pdf->SetXY(6, 73 - $_y);
            $pdf->Cell(40, 5, $requsted_by, 1, 0, 'C');
            $pdf->Cell(60, 5, $shipment, 1, 0, 'C');
            $pdf->Cell(40, 5, $currency, 1, 0, 'C');
            $pdf->Cell(55, 5, $payment_term, 1, 0, 'C');
            $pdf->SetFont('Times', 'B', 9);
            $pdf->SetXY(6, 78 - $_y);
            $pdf->Cell(40, 5, 'Supplier Name', 1, 0, 'C', 1);
            $pdf->Cell(100, 5, 'Address', 1, 0, 'C', 1);
            $pdf->Cell(27.5, 5, 'Phone No.', 1, 0, 'C', 1);
            $pdf->Cell(27.5, 5, 'Fax. No.', 1, 0, 'C', 1);
            $pdf->SetXY(6, 83 - $_y);
            $pdf->Cell(40, 10, '', 1, 0, 'C');
            $pdf->Cell(100, 10, '', 1, 0, 'C');
            $pdf->Cell(27.5, 10, '', 1, 0, 'C');
            $pdf->Cell(27.5, 10, '', 1, 0, 'C');
            $pdf->SetFont('Times', '', 9);
            $pdf->SetXY(6, 83 - $_y);
            $pdf->MultiCell(40, 4, $supplier, 0, 'L');
            $Yitu = $pdf->GetY();
            $pdf->SetXY(46, 83 - $_y);
            $pdf->MultiCell(100, 4, $supplier_address, 0, "L");
            $pdf->SetXY(146, 83 - $_y);
            $pdf->MultiCell(27.5, 4, $supplier_telno, 0);
            $pdf->SetXY(173.5, 83 - $_y);
            $pdf->MultiCell(27.5, 4, $supplier_faxno, 0);

            $pdf->SetFont('Times', 'B', 9);
            $pdf->SetXY(6, 94 - $_y);
            $pdf->setFillColor($Rcolor, $Gcolor, $Bcolor);
            $pdf->Cell(10, 10, '', 1, 0, 'C', 1); #item
            $pdf->Cell(30, 10, '', 1, 0, 'C', 1); #part number
            $pdf->Cell(60, 10, '', 1, 0, 'C', 1); #part name
            $pdf->Cell(15, 10, '', 1, 0, 'C', 1); #unit measure
            $pdf->Cell(25, 10, '', 1, 0, 'C', 1); #qty
            $pdf->Cell(27.5, 10, '', 1, 0, 'C', 1); #unit price
            $pdf->Cell(27.5, 10, '', 1, 0, 'C', 1); #amount

            $pdf->SetXY(6, 94 - $_y);
            $pdf->Cell(10, 5, 'Item', 0, 0, 'C');
            $pdf->Cell(30, 5, 'Part Number', 0, 0, 'C');
            $pdf->Cell(60, 5, 'Part Name / Description', 0, 0, 'C'); #part name
            $pdf->Cell(15, 5, 'Unit', 0, 0, 'C'); #unit measure
            $pdf->Cell(25, 5, 'Qty', 0, 0, 'C'); #qty
            $pdf->Cell(27.5, 5, 'Unit Price', 0, 0, 'C'); #unit price
            $pdf->Cell(27.5, 5, 'Amount', 0, 0, 'C'); #amount

            $pdf->SetFont('Times', 'BI', 9);
            $pdf->SetXY(6, 98 - $_y);
            $pdf->Cell(10, 5, 'No', 0, 0, 'C');
            $pdf->Cell(30, 5, 'Nomor Barang', 0, 0, 'C');
            $pdf->Cell(60, 5, 'Nama Barang / Keterangan', 0, 0, 'C'); #part name
            $pdf->Cell(15, 5, 'Satuan', 0, 0, 'C'); #unit measure
            $pdf->Cell(25, 5, 'Jumlah', 0, 0, 'C'); #qty
            $pdf->Cell(27.5, 5, 'Harga per satuan', 0, 0, 'C'); #unit price
            $pdf->Cell(27.5, 5, 'Jumlah', 0, 0, 'C'); #amount

            $pdf->SetFont('Times', '', 9);
            $pdf->SetXY(6, 104 - $_y);
            $pdf->Cell(10, 75, '', 1, 0, 'C');
            $pdf->Cell(30, 75, '', 1, 0, 'C');
            $pdf->Cell(60, 75, '', 1, 0, 'C'); #part name
            $pdf->Cell(15, 75, '', 1, 0, 'C'); #unit measure
            $pdf->Cell(25, 75, '', 1, 0, 'C'); #qty
            $pdf->Cell(27.5, 75, '', 1, 0, 'C'); #unit price
            $pdf->Cell(27.5, 75, '', 1, 0, 'C'); #amount
            #footermain
            $total_amount -= $ttldiscount_priceSpecial;
            $ppn_price = $total_amount * $ppn / 100;
            $pph_price = $total_amount * $pph / 100;
            $netpayment = $total_amount + $pph_price + $ppn_price;

            $sdata_section = '';
            foreach ($adata_section as $r) {
                $sdata_section .= $r . ",";
            }
            $sdata_department = '';
            foreach ($adata_department as $r) {
                $sdata_department .= $r . ",";
            }
            $sdata_subject = '';
            foreach ($adata_subject as $r) {
                $sdata_subject .= $r . ",";
            }

            $sdata_section = substr($sdata_section, 0, strlen($sdata_section) - 1);
            $sdata_department = substr($sdata_department, 0, strlen($sdata_department) - 1);
            $sdata_subject = substr($sdata_subject, 0, strlen($sdata_subject) - 1);

            if ($ttldiscount_price > 0 || $discount_msg != '') {
                $pdf->SetXY(6, $YStart);
                $pdf->Cell(10, 5, '', 0, 0, 'C');
                $pdf->Cell(30, 5, '', 0, 0, 'L');
                $pdf->Cell(60, 5, 'Discount (' . $discount_msg . ')', 0, 0, 'L');
                $pdf->Cell(15, 5, '', 0, 0, 'C');
                $pdf->Cell(25, 5, '', 0, 0, 'R');
                $pdf->Cell(27.5, 5, "(" . number_format($ttldiscount_price + $ttldiscount_priceSpecial, 2) . ")", 0, 0, 'R');
                $pdf->Cell(27.5, 5, "(" . number_format($ttldiscount_price + $ttldiscount_priceSpecial, 2) . ")", 0, 0, 'R');
            }
            // #footer
            $pdf->SetXY(6, 180 - $_y);
            $pdf->Cell(10, 5, '', 0, 0, 'C');
            $pdf->Cell(30, 5, '', 0, 0, 'C');
            $pdf->Cell(127.5, 5, 'TOTAL', 1, 0, 'L');
            $pdf->Cell(27.5, 5, number_format($total_amount, 2), 1, 0, 'R');
            $pdf->SetXY(6, 185 - $_y);
            $pdf->Cell(10, 5, '', 0, 0, 'C');
            $pdf->Cell(30, 5, '', 0, 0, 'C');
            $pdf->Cell(127.5, 5, 'PPN ' . $ppn . '%', 1, 0, 'L');
            $pdf->Cell(27.5, 5, number_format($ppn_price, 2), 1, 0, 'R');
            $pdf->SetXY(6, 190 - $_y);
            $pdf->Cell(10, 5, '', 0, 0, 'C');
            $pdf->Cell(30, 5, '', 0, 0, 'C');
            $pdf->Cell(127.5, 5, 'W/H TAX ART 23/26 ' . $pph . ' %', 1, 0, 'L');
            $pdf->Cell(27.5, 5, number_format($pph_price, 2), 1, 0, 'R');
            $pdf->SetXY(6, 195 - $_y);
            $pdf->Cell(10, 5, '', 0, 0, 'C');
            $pdf->Cell(30, 5, '', 0, 0, 'C');
            $pdf->Cell(127.5, 5, 'DELIVERY / SHIPPING COST', 1, 0, 'L');
            $pdf->Cell(27.5, 5, number_format($shipping_cost, 2), 1, 0, 'R');
            $pdf->SetFont('Times', 'B', 9);
            $pdf->SetXY(6, 200 - $_y);
            $pdf->Cell(10, 5, '', 0, 0, 'C');
            $pdf->Cell(30, 5, '', 0, 0, 'C');
            $pdf->Cell(127.5, 5, 'NET PAYMENT ( PAY TO SUPPLIER )', 1, 0, 'L');
            $pdf->Cell(27.5, 5, number_format($netpayment, 2), 1, 0, 'R');
            $pdf->SetXY(6, 205 - $_y);
            $pdf->Cell(10, 5, '', 0, 0, 'C');
            $pdf->Cell(30, 5, '', 0, 0, 'C');
            $pdf->Cell(127.5, 5, 'GRAND TOTAL', 1, 0, 'L');
            $pdf->Cell(27.5, 5, number_format($netpayment + $shipping_cost, 2), 1, 0, 'R');

            $pdf->SetXY(6, 210 - $_y);
            $pdf->setFillColor($Rcolor, $Gcolor, $Bcolor);
            $pdf->Cell(40, 10, '', 1, 0, 'C', 1);
            $pdf->Cell(87.5, 10, '', 1, 0, 'C', 1);
            $pdf->Cell(67.5, 10, '', 1, 0, 'C', 1);

            $pdf->SetXY(6, 210 - $_y);
            $pdf->Cell(40, 5, 'Required Date', 0, 0, 'C');
            $pdf->Cell(87.5, 5, 'Section', 0, 0, 'C');
            $pdf->Cell(67.5, 5, 'Department', 0, 0, 'C');
            $pdf->SetFont('Times', 'BI', 9);
            $pdf->SetXY(6, 214 - $_y);
            $pdf->Cell(40, 5, 'Tanggal Diminta', 0, 0, 'C');
            $pdf->Cell(87.5, 5, 'Seksi', 0, 0, 'C');
            $pdf->Cell(67.5, 5, 'Departemen', 0, 0, 'C');
            $pdf->SetFont('Times', '', 9);
            $pdf->SetXY(6, 220 - $_y);
            $pdf->Cell(40, 5, $required_date, 1, 0, 'C'); #required date
            $pdf->Cell(87.5, 5, $sdata_section, 1, 0, 'C'); #section
            $pdf->Cell(67.5, 5, $sdata_department, 1, 0, 'C'); #department

            #incoming inspection
            $pdf->SetFont('Times', 'B', 8);
            $pdf->SetXY(5, 227 - $_y);
            $pdf->Cell(26, 5, 'INCOMING INSPECTION : YES / NO', 0, 0, 'L');
            $pdf->SetXY(5, 232 - $_y);
            $pdf->SetFont('Times', 'BI', 8);
            $pdf->Cell(26, 5, 'Pemeriksaan Kedatangan : Ya / Tidak', 0, 0, 'L');
            $pdf->SetXY(5, 237 - $_y);
            $pdf->SetFont('Times', 'B', 8);
            $pdf->Cell(26, 5, 'SUBJECT(Kode)', 0, 0, 'L');
            $pdf->SetXY(5, 242 - $_y);
            $pdf->SetFont('Times', 'B', 8);
            $pdf->Cell(26, 5, $sdata_subject, 0, 0, 'L');
            $pdf->SetXY(5, 254 - $_y);
            $pdf->SetFont('Times', 'B', 8);
            $pdf->Cell(26, 5, 'COMMENT/REMARKS', 0, 0, 'L');
            $pdf->SetXY(5, 258 - $_y);
            $pdf->SetFont('Times', 'BI', 8);
            $pdf->Cell(26, 5, 'Komentar / Keterangan', 0, 0, 'L');

            $pdf->SetFont('Times', 'B', 9);
            $pdf->SetXY(55, 228 - $_y);
            $pdf->Cell(120, 10, '', 1, 0, 'C', 1);
            $pdf->Cell(26, 10, '', 1, 0, 'C', 1);
            $pdf->SetXY(55, 228 - $_y);
            $pdf->Cell(120, 5, 'Approved By :', 0, 0, 'C');
            $pdf->Cell(26, 5, 'Checked By', 0, 0, 'C');
            $pdf->SetFont('Times', 'BI', 9);
            $pdf->SetXY(55, 232 - $_y);
            $pdf->Cell(120, 5, 'Disetujui Oleh', 0, 0, 'C');
            $pdf->Cell(26, 5, 'Diperiksa Oleh :', 0, 0, 'C');
            $pdf->SetFont('Times', 'B', 9);
            $pdf->SetXY(55, 238 - $_y);
            $pdf->Cell(20, 5, 'Pres Dir', 1, 0, 'C');
            $pdf->Cell(20, 5, 'VP / GM', 1, 0, 'C');
            $pdf->Cell(20, 5, 'Fin. Mgr', 1, 0, 'C');
            $pdf->Cell(20, 5, 'Pur. Mgr', 1, 0, 'C');
            $pdf->Cell(20, 5, 'Dept. Mgr', 1, 0, 'C');
            $pdf->Cell(20, 5, 'Requestor', 1, 0, 'C');
            $pdf->Cell(26, 25, '', 1, 0, 'C');
            $pdf->SetXY(55, 243 - $_y);
            $pdf->Cell(20, 20, '', 1, 0, 'C');
            $pdf->Cell(20, 20, '', 1, 0, 'C');
            $pdf->Cell(20, 20, '', 1, 0, 'C');
            $pdf->Cell(20, 20, '', 1, 0, 'C');
            $pdf->Cell(20, 20, '', 1, 0, 'C');
            $pdf->Cell(20, 20, '', 1, 0, 'C');

            $Yremark_adj = 3;
            $pdf->Line(5, 260 - $Yremark_adj, 105, 260 - $Yremark_adj);
            $pdf->Line(5, 267 - $Yremark_adj, 105, 267 - $Yremark_adj);
            $pdf->Line(5, 274 - $Yremark_adj, 105, 274 - $Yremark_adj);
            $pdf->Line(5, 281 - $Yremark_adj, 105, 281 - $Yremark_adj);
            $pdf->Line(5, 281 - $Yremark_adj, 105, 281 - $Yremark_adj);
            $pdf->Line(5, 288 - $Yremark_adj, 105, 288 - $Yremark_adj);
            $pdf->Line(5, 295 - $Yremark_adj, 105, 295 - $Yremark_adj);
            $pdf->SetXY(6, 260 - $Yremark_adj);
            $pdf->MultiCell(98, 4, $poremark, 0, 'L');

            $pdf->SetXY(140, 263 - $_y);
            $pdf->Cell(60, 5, 'Please Confirm & Return a Copy', 0, 0, 'C');
            $pdf->SetFont('Times', 'BI', 9);
            $pdf->SetXY(140, 267 - $_y);
            $pdf->Cell(60, 5, 'Mohon Dijelaskan & Kirimkan Salinannya', 0, 0, 'C');
            $pdf->SetFont('Times', 'B', 9);
            $pdf->SetXY(140, 271 - $_y);
            $pdf->Cell(60, 5, 'Confirm By', 0, 0, 'C');
            $pdf->SetFont('Times', 'BI', 9);
            $pdf->SetXY(140, 275 - $_y);
            $pdf->Cell(60, 5, 'Dijelaskan Oleh', 0, 0, 'C');

            $pdf->SetFont('Times', 'B', 9);
            $pdf->SetXY(140, 290 - $_y);
            $pdf->Cell(60, 5, 'Name / Date / Company Stamp', 0, 0, 'C');
            $pdf->SetFont('Times', 'BIU', 9);
            $pdf->SetXY(140, 294 - $_y);
            $pdf->Cell(60, 5, 'Nama / Tanggal / Cap Perusahaan', 0, 0, 'C');
        } else {
            $MAXLENGTH_LINE_TO_BOTTOM = 75;
            $pdf->SetFont('Times', '', 9);
            $pdf->SetXY(6, 104 - $_y);
            $pdf->Cell(10, $MAXLENGTH_LINE_TO_BOTTOM, '', 1, 0, 'C');
            $pdf->Cell(30, $MAXLENGTH_LINE_TO_BOTTOM, '', 1, 0, 'C');
            $pdf->Cell(60, $MAXLENGTH_LINE_TO_BOTTOM, '', 1, 0, 'C'); #part name
            $pdf->Cell(15, $MAXLENGTH_LINE_TO_BOTTOM, '', 1, 0, 'C'); #unit measure
            $pdf->Cell(25, $MAXLENGTH_LINE_TO_BOTTOM, '', 1, 0, 'C'); #qty
            $pdf->Cell(27.5, $MAXLENGTH_LINE_TO_BOTTOM, '', 1, 0, 'C'); #unit price
            $pdf->Cell(27.5, $MAXLENGTH_LINE_TO_BOTTOM, '', 1, 0, 'C'); #amount
            $nomor_urut = 1;
            $YStart = (104 - $_y) - 5 + 5;
            $YExtra = 0;
            $total_amount_test = 0;
            foreach ($rs as $r) {
                if ($pdf->GetY() + $YExtra >= 164) {
                    $pdf->AddPage();
                    #company
                    $pdf->SetFont('Arial', 'B', 10);
                    $pdf->SetXY(6, 22 - $_y);
                    $pdf->SetTextColor(0, 0, 240);
                    $pdf->Cell(50, 4, 'PT. SMT INDONESIA', 0, 0, 'L');
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetFont('Times', 'IB', 9);
                    $pdf->SetXY(6, 26 - $_y);
                    $pdf->Cell(50, 4, 'EJIP Industrial Park Plot 5C - 2', 0, 0, 'L');
                    $pdf->SetXY(6, 30 - $_y);
                    $pdf->Cell(80, 4, 'Cikarang Selatan, Bekasi 17550 INDONESIA', 0, 0, 'L');
                    $pdf->SetXY(6, 34 - $_y);
                    $pdf->Cell(80, 4, 'Tel.    : +62-21-8970-567 (Hunting), 468,469,470', 0, 0, 'L');
                    $pdf->SetFont('Times', 'IB', 8);
                    $pdf->SetXY(6, 38 - $_y);
                    $pdf->Cell(98, 4, 'Fax.    : +62-21-8970-577 (Purchasing Department / Accounting & Financial Dept)', 0, 0, 'L');
                    $pdf->SetFont('Times', 'IB', 9);
                    $pdf->SetXY(6, 42 - $_y);
                    $pdf->Cell(80, 4, 'Fax.   : +62-21-8975-333 (Manufacturing Department)', 0, 0, 'L');
                    #end company
                    $pdf->SetFont('Times', 'B', 7);
                    $pdf->SetXY(160, 18 - $_y);
                    $pdf->Cell(21, 4, 'FORM-FPI-04-01', 0, 0, 'L');
                    $pdf->SetXY(160, 22 - $_y);
                    $pdf->Cell(21, 4, 'REV.02', 0, 0, 'R');
                    $pdf->SetFont('Times', 'IB', 9);
                    $pdf->SetXY(146, 30 - $_y);
                    $pdf->Cell(35, 4, 'Copy : Accounting Dept.', 0, 0, 'R');
                    $pdf->SetFont('Times', 'B', 9);
                    $pdf->SetXY(115, 45 - $_y);
                    $pdf->Cell(85, 7, '   P / O No. : ' . $pser, 1, 0, 'L');
                    $pdf->SetFont('Times', 'B', 9);
                    $pdf->SetXY(115, 52 - $_y);
                    $pdf->Cell(85, 4, 'DATE / Tanggal : ' . $trans_date, 0, 0, 'C');

                    $pdf->SetFont('Times', 'BU', 13);
                    $pdf->SetXY(80, 60 - $_y);
                    $pdf->Cell(40, 4, 'PURCHASE ORDER', 0, 0, 'L');

                    $pdf->SetFont('Times', 'B', 9);
                    $pdf->SetXY(6, 68 - $_y);
                    $pdf->setFillColor($Rcolor, $Gcolor, $Bcolor);
                    $pdf->Cell(40, 5, 'Requested By : ( Name )', 1, 0, 'C', 1);
                    $pdf->Cell(60, 5, 'Ship / Delivery Via', 1, 0, 'C', 1);
                    $pdf->Cell(40, 5, 'Currency', 1, 0, 'C', 1);
                    $pdf->Cell(55, 5, 'Payment Terms', 1, 0, 'C', 1);
                    $pdf->SetFont('Times', '', 9);
                    $pdf->SetXY(6, 73 - $_y);
                    $pdf->Cell(40, 5, $requsted_by, 1, 0, 'C');
                    $pdf->Cell(60, 5, $shipment, 1, 0, 'C');
                    $pdf->Cell(40, 5, $currency, 1, 0, 'C');
                    $pdf->Cell(55, 5, $payment_term, 1, 0, 'C');
                    $pdf->SetFont('Times', 'B', 9);
                    $pdf->SetXY(6, 78 - $_y);
                    $pdf->Cell(40, 5, 'Supplier Name', 1, 0, 'C', 1);
                    $pdf->Cell(100, 5, 'Address', 1, 0, 'C', 1);
                    $pdf->Cell(27.5, 5, 'Phone No.', 1, 0, 'C', 1);
                    $pdf->Cell(27.5, 5, 'Fax. No.', 1, 0, 'C', 1);
                    $pdf->SetXY(6, 83 - $_y);
                    $pdf->Cell(40, 10, '', 1, 0, 'C');
                    $pdf->Cell(100, 10, '', 1, 0, 'C');
                    $pdf->Cell(27.5, 10, '', 1, 0, 'C');
                    $pdf->Cell(27.5, 10, '', 1, 0, 'C');
                    $pdf->SetFont('Times', '', 9);
                    $pdf->SetXY(6, 83 - $_y);
                    $pdf->MultiCell(40, 4, $supplier, 0, 'L');
                    // $pdf->MultiCell(40,4,"tes",1,'L');
                    $Yitu = $pdf->GetY();
                    $pdf->SetXY(46, 83 - $_y);
                    $pdf->MultiCell(100, 4, $supplier_address, 0, "L");
                    $pdf->SetXY(146, 83 - $_y);
                    $pdf->MultiCell(27.5, 4, $supplier_telno, 0);
                    $pdf->SetXY(173.5, 83 - $_y);
                    $pdf->MultiCell(27.5, 4, $supplier_faxno, 0);

                    $pdf->SetFont('Times', 'B', 9);
                    $pdf->SetXY(6, 94 - $_y);
                    $pdf->setFillColor($Rcolor, $Gcolor, $Bcolor);
                    $pdf->Cell(10, 10, '', 1, 0, 'C', 1); #item
                    $pdf->Cell(30, 10, '', 1, 0, 'C', 1); #part number
                    $pdf->Cell(60, 10, '', 1, 0, 'C', 1); #part name
                    $pdf->Cell(15, 10, '', 1, 0, 'C', 1); #unit measure
                    $pdf->Cell(25, 10, '', 1, 0, 'C', 1); #qty
                    $pdf->Cell(27.5, 10, '', 1, 0, 'C', 1); #unit price
                    $pdf->Cell(27.5, 10, '', 1, 0, 'C', 1); #amount

                    $pdf->SetXY(6, 94 - $_y);
                    $pdf->Cell(10, 5, 'Item', 0, 0, 'C');
                    $pdf->Cell(30, 5, 'Part Number', 0, 0, 'C');
                    $pdf->Cell(60, 5, 'Part Name / Description', 0, 0, 'C'); #part name
                    $pdf->Cell(15, 5, 'Unit', 0, 0, 'C'); #unit measure
                    $pdf->Cell(25, 5, 'Qty', 0, 0, 'C'); #qty
                    $pdf->Cell(27.5, 5, 'Unit Price', 0, 0, 'C'); #unit price
                    $pdf->Cell(27.5, 5, 'Amount', 0, 0, 'C'); #amount

                    $pdf->SetFont('Times', 'BI', 9);
                    $pdf->SetXY(6, 98 - $_y);
                    $pdf->Cell(10, 5, 'No', 0, 0, 'C');
                    $pdf->Cell(30, 5, 'Nomor Barang', 0, 0, 'C');
                    $pdf->Cell(60, 5, 'Nama Barang / Keterangan', 0, 0, 'C'); #part name
                    $pdf->Cell(15, 5, 'Satuan', 0, 0, 'C'); #unit measure
                    $pdf->Cell(25, 5, 'Jumlah', 0, 0, 'C'); #qty
                    $pdf->Cell(27.5, 5, 'Harga per satuan', 0, 0, 'C'); #unit price
                    $pdf->Cell(27.5, 5, 'Jumlah', 0, 0, 'C'); #amount

                    $pdf->SetFont('Times', '', 9);
                    $pdf->SetXY(6, 104 - $_y);
                    $pdf->Cell(10, 75, '', 1, 0, 'C');
                    $pdf->Cell(30, 75, '', 1, 0, 'C');
                    $pdf->Cell(60, 75, '', 1, 0, 'C'); #part name
                    $pdf->Cell(15, 75, '', 1, 0, 'C'); #unit measure
                    $pdf->Cell(25, 75, '', 1, 0, 'C'); #qty
                    $pdf->Cell(27.5, 75, '', 1, 0, 'C'); #unit price
                    $pdf->Cell(27.5, 75, '', 1, 0, 'C'); #amount
                    $YStart = (104 - $_y) - 5 + 5;
                }
                if ($r['PO_SECTION']) {
                    if (trim($r['PO_SECTION']) != '') {
                        if (!in_array(strtoupper(trim($r['PO_SECTION'])), $adata_section)) {
                            $adata_section[] = strtoupper(trim($r['PO_SECTION']));
                        }
                    }
                }
                if ($r['PO_DEPT']) {
                    if (trim($r['PO_DEPT']) != '') {
                        if (!in_array(trim($r['PO_DEPT']), $adata_department)) {
                            $adata_department[] = trim($r['PO_DEPT']);
                        }
                    }
                }
                if ($r['PO_SUBJECT']) {
                    if (trim($r['PO_SUBJECT']) != '') {
                        if (!in_array(strtoupper(trim($r['PO_SUBJECT'])), $adata_subject)) {
                            $adata_subject[] = strtoupper(trim($r['PO_SUBJECT']));
                        }
                    }
                }
                $itemcd = $r['PO_ITMCD'] ? trim($r['PO_ITMCD']) : "NON ITEM";
                $itemcd = stripslashes($itemcd);
                $itemcd = iconv('UTF-8', 'windows-1252', $itemcd);
                $itemname = $r['PO_ITMNM'] ? $r['PO_ITMNM'] : $r['MITM_ITMD1'];
                $itemname = stripslashes($itemname);
                $itemname = iconv('UTF-8', 'windows-1252', $itemname);
                $itemum = $r['PO_UM'] ? $r['PO_UM'] : $r['MITM_STKUOM'];
                $amount = $r['PO_PRICE'] * $r['PO_QTY'];
                $discount_price_per = $amount * ($r['PO_DISC'] / 100);
                $finalamount = $amount - $discount_price_per;
                $total_amount_test += $amount;
                $pdf->SetXY(6, $YStart);
                $pdf->Cell(10, 5, $nomor_urut++, 0, 0, 'C');
                if (strpos($itemcd, " ") !== false) {
                    $pdf->MultiCell(30, 5, $itemcd, 0, 'L');
                    $YExtra_candidate = $pdf->GetY();
                    $YExtra2 = $YExtra_candidate != $YStart ? $YExtra_candidate - $YStart - 5 : 0;
                } else {
                    $ttlwidth = $pdf->GetStringWidth($itemcd);
                    if ($ttlwidth > 30) {
                        $ukuranfont = 8.5;
                        while ($ttlwidth > 30) {
                            $pdf->SetFont('Times', '', $ukuranfont);
                            $ttlwidth = $pdf->GetStringWidth(trim($itemcd));
                            $ukuranfont = $ukuranfont - 0.5;
                        }
                    }
                    $pdf->Cell(30, 5, $itemcd, 0, 0, 'L');
                }
                $pdf->SetFont('Times', '', 9);                
                $pdf->SetXY(46, $YStart);               
                $pdf->MultiCell(60, 5, $itemname ."($YStart)", 0, 'L');
                $YExtra_candidate = $pdf->GetY();
                $YExtra = $YExtra_candidate != $YStart ? $YExtra_candidate - $YStart - 5 : 0;
                $pdf->SetXY(106, $YStart);
                $pdf->Cell(15, 5, $itemum, 0, 0, 'C');
                $pdf->Cell(25, 5, number_format($r['PO_QTY']), 0, 0, 'R');
                $pdf->Cell(27.5, 5, number_format($r['PO_PRICE'], 2), 0, 0, 'R');
                $pdf->Cell(27.5, 5, number_format($amount, 2), 0, 0, 'R');
                $total_amount += $finalamount;
                if ($YExtra2 > 0) {
                    $YStart += (5 + $YExtra2+5);
                } else {
                    $YStart += (5 + $YExtra);
                }
            }

            #footermain
            $total_amount -= $ttldiscount_priceSpecial;
            $ppn_price = $total_amount * $ppn / 100;
            $pph_price = $total_amount * $pph / 100;
            $netpayment = $total_amount - $pph_price + $ppn_price;

            $sdata_section = '';
            foreach ($adata_section as $r) {
                $sdata_section .= $r . ",";
            }
            $sdata_department = '';
            foreach ($adata_department as $r) {
                $sdata_department .= $r . ",";
            }
            $sdata_subject = '';
            foreach ($adata_subject as $r) {
                $sdata_subject .= $r . ",";
            }

            $sdata_section = substr($sdata_section, 0, strlen($sdata_section) - 1);
            $sdata_department = substr($sdata_department, 0, strlen($sdata_department) - 1);
            $sdata_subject = substr($sdata_subject, 0, strlen($sdata_subject) - 1);

            #additional row for discount
            if ($ttldiscount_price > 0 || $discount_msg != '') {
                $pdf->SetXY(6, (180 - $_y) - 6);
                $pdf->Cell(10, 5, '', 0, 0, 'C');
                $pdf->Cell(30, 5, '', 0, 0, 'L');
                $pdf->Cell(60, 5, 'Discount (' . $discount_msg . ')', 0, 0, 'L');
                $pdf->Cell(15, 5, '', 0, 0, 'C');
                $pdf->Cell(25, 5, '', 0, 0, 'R');
                $pdf->Cell(27.5, 5, "(" . number_format($ttldiscount_price + $ttldiscount_priceSpecial, 2) . ")", 0, 0, 'R');
                $pdf->Cell(27.5, 5, "(" . number_format($ttldiscount_price + $ttldiscount_priceSpecial, 2) . ")", 0, 0, 'R');
            }
            #footer
            $pdf->SetXY(6, 180 - $_y);
            $pdf->Cell(10, 5, '', 0, 0, 'C');
            $pdf->Cell(30, 5, '', 0, 0, 'C');
            $pdf->Cell(127.5, 5, 'TOTAL', 1, 0, 'L');
            $pdf->Cell(27.5, 5, number_format($total_amount, 2), 1, 0, 'R');
            $pdf->SetXY(6, 185 - $_y);
            $pdf->Cell(10, 5, '', 0, 0, 'C');
            $pdf->Cell(30, 5, '', 0, 0, 'C');
            $pdf->Cell(127.5, 5, 'PPN ' . $ppn . '%', 1, 0, 'L');
            $pdf->Cell(27.5, 5, number_format($ppn_price, 2), 1, 0, 'R');
            $pdf->SetXY(6, 190 - $_y);
            $pdf->Cell(10, 5, '', 0, 0, 'C');
            $pdf->Cell(30, 5, '', 0, 0, 'C');
            $pdf->Cell(127.5, 5, 'W/H TAX ART 23/26 ' . $pph . ' %', 1, 0, 'L');
            $pdf->Cell(27.5, 5, number_format($pph_price, 2), 1, 0, 'R');
            $pdf->SetXY(6, 195 - $_y);
            $pdf->Cell(10, 5, '', 0, 0, 'C');
            $pdf->Cell(30, 5, '', 0, 0, 'C');
            $pdf->Cell(127.5, 5, 'DELIVERY / SHIPPING COST', 1, 0, 'L');
            $pdf->Cell(27.5, 5, number_format($shipping_cost, 2), 1, 0, 'R');
            $pdf->SetFont('Times', 'B', 9);
            $pdf->SetXY(6, 200 - $_y);
            $pdf->Cell(10, 5, '', 0, 0, 'C');
            $pdf->Cell(30, 5, '', 0, 0, 'C');
            $pdf->Cell(127.5, 5, 'NET PAYMENT ( PAY TO SUPPLIER )', 1, 0, 'L');
            $pdf->Cell(27.5, 5, number_format($netpayment, 2), 1, 0, 'R');
            $pdf->SetXY(6, 205 - $_y);
            $pdf->Cell(10, 5, '', 0, 0, 'C');
            $pdf->Cell(30, 5, '', 0, 0, 'C');
            $pdf->Cell(127.5, 5, 'GRAND TOTAL', 1, 0, 'L');
            $pdf->Cell(27.5, 5, number_format($netpayment + $shipping_cost, 2), 1, 0, 'R');

            $pdf->SetXY(6, 210 - $_y);
            $pdf->setFillColor($Rcolor, $Gcolor, $Bcolor);
            $pdf->Cell(40, 10, '', 1, 0, 'C', 1);
            $pdf->Cell(87.5, 10, '', 1, 0, 'C', 1);
            $pdf->Cell(67.5, 10, '', 1, 0, 'C', 1);

            $pdf->SetXY(6, 210 - $_y);
            $pdf->Cell(40, 5, 'Required Date', 0, 0, 'C');
            $pdf->Cell(87.5, 5, 'Section', 0, 0, 'C');
            $pdf->Cell(67.5, 5, 'Department', 0, 0, 'C');
            $pdf->SetFont('Times', 'BI', 9);
            $pdf->SetXY(6, 214 - $_y);
            $pdf->Cell(40, 5, 'Tanggal Diminta', 0, 0, 'C');
            $pdf->Cell(87.5, 5, 'Seksi', 0, 0, 'C');
            $pdf->Cell(67.5, 5, 'Departemen', 0, 0, 'C');
            $pdf->SetFont('Times', '', 9);
            $pdf->SetXY(6, 220 - $_y);
            $pdf->Cell(40, 5, $required_date, 1, 0, 'C'); #required date
            $pdf->Cell(87.5, 5, $sdata_section, 1, 0, 'C'); #section
            $pdf->Cell(67.5, 5, $sdata_department, 1, 0, 'C'); #department

            #incoming inspection
            $pdf->SetFont('Times', 'B', 8);
            $pdf->SetXY(5, 227 - $_y);
            $pdf->Cell(26, 5, 'INCOMING INSPECTION : YES / NO', 0, 0, 'L');
            $pdf->SetXY(5, 232 - $_y);
            $pdf->SetFont('Times', 'BI', 8);
            $pdf->Cell(26, 5, 'Pemeriksaan Kedatangan : Ya / Tidak', 0, 0, 'L');
            $pdf->SetXY(5, 237 - $_y);
            $pdf->SetFont('Times', 'B', 8);
            $pdf->Cell(26, 5, 'SUBJECT(Kode)', 0, 0, 'L');
            $pdf->SetXY(5, 242 - $_y);
            $pdf->SetFont('Times', 'B', 8);
            $pdf->Cell(26, 5, $sdata_subject, 0, 0, 'L');
            $pdf->SetXY(5, 254 - $_y);
            $pdf->SetFont('Times', 'B', 8);
            $pdf->Cell(26, 5, 'COMMENT/REMARKS', 0, 0, 'L');
            $pdf->SetXY(5, 258 - $_y);
            $pdf->SetFont('Times', 'BI', 8);
            $pdf->Cell(26, 5, 'Komentar / Keterangan', 0, 0, 'L');

            $pdf->SetFont('Times', 'B', 9);
            $pdf->SetXY(55, 228 - $_y);
            $pdf->Cell(120, 10, '', 1, 0, 'C', 1);
            $pdf->Cell(26, 10, '', 1, 0, 'C', 1);
            $pdf->SetXY(55, 228 - $_y);
            $pdf->Cell(120, 5, 'Approved By :', 0, 0, 'C');
            $pdf->Cell(26, 5, 'Checked By', 0, 0, 'C');
            $pdf->SetFont('Times', 'BI', 9);
            $pdf->SetXY(55, 232 - $_y);
            $pdf->Cell(120, 5, 'Disetujui Oleh', 0, 0, 'C');
            $pdf->Cell(26, 5, 'Diperiksa Oleh :', 0, 0, 'C');
            $pdf->SetFont('Times', 'B', 9);
            $pdf->SetXY(55, 238 - $_y);
            $pdf->Cell(20, 5, 'Pres Dir', 1, 0, 'C');
            $pdf->Cell(20, 5, 'VP / GM', 1, 0, 'C');
            $pdf->Cell(20, 5, 'Fin. Mgr', 1, 0, 'C');
            $pdf->Cell(20, 5, 'Pur. Mgr', 1, 0, 'C');
            $pdf->Cell(20, 5, 'Dept. Mgr', 1, 0, 'C');
            $pdf->Cell(20, 5, 'Requestor', 1, 0, 'C');
            $pdf->Cell(26, 25, '', 1, 0, 'C');
            $pdf->SetXY(55, 243 - $_y);
            $pdf->Cell(20, 20, '', 1, 0, 'C');
            $pdf->Cell(20, 20, '', 1, 0, 'C');
            $pdf->Cell(20, 20, '', 1, 0, 'C');
            $pdf->Cell(20, 20, '', 1, 0, 'C');
            $pdf->Cell(20, 20, '', 1, 0, 'C');
            $pdf->Cell(20, 20, '', 1, 0, 'C');

            $Yremark_adj = 3;
            $pdf->Line(5, 260 - $Yremark_adj, 105, 260 - $Yremark_adj);
            $pdf->Line(5, 267 - $Yremark_adj, 105, 267 - $Yremark_adj);
            $pdf->Line(5, 274 - $Yremark_adj, 105, 274 - $Yremark_adj);
            $pdf->Line(5, 281 - $Yremark_adj, 105, 281 - $Yremark_adj);
            $pdf->Line(5, 281 - $Yremark_adj, 105, 281 - $Yremark_adj);
            $pdf->Line(5, 288 - $Yremark_adj, 105, 288 - $Yremark_adj);
            $pdf->Line(5, 295 - $Yremark_adj, 105, 295 - $Yremark_adj);
            $pdf->SetXY(6, 260 - $Yremark_adj);
            $pdf->MultiCell(98, 4, $poremark, 0, 'L');

            $pdf->SetXY(140, 263 - $_y);
            $pdf->Cell(60, 5, 'Please Confirm & Return a Copy', 0, 0, 'C');
            $pdf->SetFont('Times', 'BI', 9);
            $pdf->SetXY(140, 267 - $_y);
            $pdf->Cell(60, 5, 'Mohon Dijelaskan & Kirimkan Salinannya', 0, 0, 'C');
            $pdf->SetFont('Times', 'B', 9);
            $pdf->SetXY(140, 271 - $_y);
            $pdf->Cell(60, 5, 'Confirm By', 0, 0, 'C');
            $pdf->SetFont('Times', 'BI', 9);
            $pdf->SetXY(140, 275 - $_y);
            $pdf->Cell(60, 5, 'Dijelaskan Oleh', 0, 0, 'C');

            $pdf->SetFont('Times', 'B', 9);
            $pdf->SetXY(140, 290 - $_y);
            $pdf->Cell(60, 5, 'Name / Date / Company Stamp', 0, 0, 'C');
            $pdf->SetFont('Times', 'BIU', 9);
            $pdf->SetXY(140, 294 - $_y);
            $pdf->Cell(60, 5, 'Nama / Tanggal / Cap Perusahaan', 0, 0, 'C');
        }
        $pdf->Output('I', 'PO.pdf');
    }

    public function remove()
    {
        header('Content-Type: application/json');
        $doc = $this->input->post('docNum');
        $line = $this->input->post('lineId');
        if ($line === '') {
            $myar[] = ['cd' => '1', 'msg' => 'OK..'];
        } else {
            $rspo = $this->PO_mod->select_all(['PO_NO' => $doc, 'PO_LINE' => $line]);
            $received_items = 0;
            foreach ($rspo as $r) {
                if ($r['PO_ITMNM']) {
                    $received_items = $this->RCVNI_mod->check_Primary(['RCVNI_PO' => $doc, 'RCVNI_ITMNM' => $r['PO_ITMNM']]);
                } else {
                    $received_items = $this->RCV_mod->check_Primary(['RCV_PO' => $doc, 'RCV_ITMCD' => $r['PO_ITMCD']]);
                }
                break;
            }
            $ret = $this->PO_mod->delete(['PO_NO' => $doc, 'PO_LINE' => $line]);
            $myar[] = $received_items > 0 ? ['cd' => '0', 'msg' => 'could not delete received item'] : ['cd' => '1', 'msg' => 'OK', 'affected' => $ret];
        }
        die(json_encode(['status' => $myar]));
    }
    public function remove_discount()
    {
        header('Content-Type: application/json');
        $doc = $this->input->post('docNum');
        $line = $this->input->post('lineId');
        $ret = $this->PO_mod->delete_discount(['PODISC_PONO' => $doc, 'PODISC_LINE' => $line]);
        $myar[] = ['cd' => '1', 'msg' => 'OK', 'affected' => $ret];
        die(json_encode(['status' => $myar]));
    }

    public function save()
    {
        header('Content-Type: application/json');
        date_default_timezone_set('Asia/Jakarta');
        $current_datetime_ = date('Y-m-d H:i:s');
        $h_po = $this->input->post('h_po');
        $h_remark = $this->input->post('h_remark');
        $h_pph = $this->input->post('h_pph');
        $h_vat = $this->input->post('h_vat');
        $h_request_by = $this->input->post('h_request_by');
        $h_payterm = $this->input->post('h_payterm');
        $h_req_date = $this->input->post('h_req_date');
        $h_issu_date = $this->input->post('h_issu_date');
        $h_shp = $this->input->post('h_shp');
        $h_shp_cost = str_replace(',', '', $this->input->post('h_shp_cost'));
        $h_supplier = $this->input->post('h_supplier');
        $di_rowid = $this->input->post('di_rowid');
        $di_item = $this->input->post('di_item');
        $di_qty = $this->input->post('di_qty');
        $di_disc = $this->input->post('di_disc');
        $di_section = $this->input->post('di_section');
        $di_dept = $this->input->post('di_dept');
        $di_subject = $this->input->post('di_subject');
        $di_price = $this->input->post('di_price');
        $dni_rowid = $this->input->post('dni_rowid');
        $dni_item = $this->input->post('dni_item');
        $dni_measure = $this->input->post('dni_measure');
        $dni_qty = $this->input->post('dni_qty');
        $dni_price = $this->input->post('dni_price');
        $dni_disc = $this->input->post('dni_disc');
        $dni_dept = $this->input->post('dni_dept');
        $dni_section = $this->input->post('dni_section');
        $dni_subject = $this->input->post('dni_subject');
        $dd_rowid = $this->input->post('dd_rowid');
        $dd_description = $this->input->post('dd_description');
        $dd_disc = $this->input->post('dd_disc');

        $ttlrows_item = is_array($di_rowid) ? count($di_rowid) : 0;
        $ttlrows_Nonitem = is_array($dni_rowid) ? count($dni_rowid) : 0;
        $ttlrows_Discount = is_array($dd_rowid) ? count($dd_rowid) : 0;
        $myar = [];
        $userid = $this->session->userdata('nama');
        $h_pph = $h_pph === '' ? 0 : $h_pph;
        $h_vat = $h_vat === '' ? 0 : $h_vat;
        $h_shp_cost = $h_shp_cost === '' ? 0 : $h_shp_cost;
        if ($this->PO_mod->check_Primary(['PO_NO' => $h_po])) {
            $ttlsaved = 0;
            $ttlupdated = 0;
            $line = $this->PO_mod->select_maxline($h_po) + 1;
            $line_discount = $this->PO_mod->select_discount_maxline($h_po) + 1;
            $saveItem = [];

            #item_mode
            for ($i = 0; $i < $ttlrows_item; $i++) {
                if ($di_rowid[$i] === '') {
                    $saveItem[] = [
                        'PO_NO' => $h_po,
                        'PO_REV' => 0,
                        'PO_REQDT' => $h_req_date,
                        'PO_ISSUDT' => $h_issu_date,
                        'PO_SUPCD' => $h_supplier,
                        'PO_ITMCD' => $di_item[$i],
                        'PO_QTY' => abs($di_qty[$i]),
                        'PO_CRTDT' => $current_datetime_,
                        'PO_CRTBY' => $userid,
                        'PO_PPH' => $h_pph,
                        'PO_VAT' => $h_vat,
                        'PO_LINE' => $line++,
                        'PO_SUBJECT' => $di_subject[$i],
                        'PO_SECTION' => $di_section[$i],
                        'PO_DEPT' => $di_dept[$i],
                        'PO_SHPDLV' => $h_shp,
                        'PO_DISC' => strlen($di_disc[$i]) == 0 ? 0 : $di_disc[$i],
                        'PO_RQSTBY' => $h_request_by,
                        'PO_PAYTERM' => $h_payterm,
                        'PO_RMRK' => $h_remark,
                        'PO_SHPCOST' => $h_shp_cost,
                        'PO_PRICE' => $di_price[$i],
                    ];
                } else {
                    $colupdate = [
                        'PO_REQDT' => $h_req_date,
                        'PO_ISSUDT' => $h_issu_date,
                        'PO_SUPCD' => $h_supplier,
                        'PO_ITMCD' => $di_item[$i],
                        'PO_QTY' => abs($di_qty[$i]),
                        'PO_CRTDT' => $current_datetime_,
                        'PO_CRTBY' => $userid,
                        'PO_PPH' => $h_pph,
                        'PO_VAT' => $h_vat,
                        'PO_SUBJECT' => $di_subject[$i],
                        'PO_SECTION' => $di_section[$i],
                        'PO_DEPT' => $di_dept[$i],
                        'PO_SHPDLV' => $h_shp,
                        'PO_DISC' => strlen($di_disc[$i]) == 0 ? 0 : $di_disc[$i],
                        'PO_RQSTBY' => $h_request_by,
                        'PO_PAYTERM' => $h_payterm,
                        'PO_RMRK' => $h_remark,
                        'PO_SHPCOST' => $h_shp_cost,
                        'PO_PRICE' => $di_price[$i],
                    ];
                    $ttlupdated += $this->PO_mod->update_where($colupdate, ['PO_NO' => $h_po, 'PO_LINE' => $di_rowid[$i]]);
                }
            }
            if (count($saveItem) > 0) {
                $ttlsaved += $this->PO_mod->insertb($saveItem);
            }

            #nonitem_mode
            $saveItem = [];
            for ($i = 0; $i < $ttlrows_Nonitem; $i++) {
                if ($dni_rowid[$i] === '') {
                    $saveItem[] = [
                        'PO_NO' => $h_po,
                        'PO_REV' => 0,
                        'PO_REQDT' => $h_req_date,
                        'PO_ISSUDT' => $h_issu_date,
                        'PO_SUPCD' => $h_supplier,
                        'PO_ITMNM' => $dni_item[$i],
                        'PO_UM' => $dni_measure[$i],
                        'PO_QTY' => abs($dni_qty[$i]),
                        'PO_CRTDT' => $current_datetime_,
                        'PO_CRTBY' => $userid,
                        'PO_PPH' => $h_pph,
                        'PO_VAT' => $h_vat,
                        'PO_LINE' => $line++,
                        'PO_SUBJECT' => $dni_subject[$i],
                        'PO_SECTION' => $dni_section[$i],
                        'PO_DEPT' => $dni_dept[$i],
                        'PO_SHPDLV' => $h_shp,
                        'PO_DISC' => strlen($dni_disc[$i]) == 0 ? 0 : $dni_disc[$i],
                        'PO_RQSTBY' => $h_request_by,
                        'PO_PAYTERM' => $h_payterm,
                        'PO_RMRK' => $h_remark,
                        'PO_SHPCOST' => $h_shp_cost,
                        'PO_PRICE' => $dni_price[$i],
                    ];
                } else {
                    $colupdate = [
                        'PO_REQDT' => $h_req_date,
                        'PO_ISSUDT' => $h_issu_date,
                        'PO_SUPCD' => $h_supplier,
                        'PO_ITMNM' => $dni_item[$i],
                        'PO_UM' => $dni_measure[$i],
                        'PO_QTY' => abs($dni_qty[$i]),
                        'PO_CRTDT' => $current_datetime_,
                        'PO_CRTBY' => $userid,
                        'PO_PPH' => $h_pph,
                        'PO_VAT' => $h_vat,
                        'PO_SUBJECT' => $dni_subject[$i],
                        'PO_SECTION' => $dni_section[$i],
                        'PO_DEPT' => $dni_dept[$i],
                        'PO_SHPDLV' => $h_shp,
                        'PO_DISC' => strlen($dni_disc[$i]) == 0 ? 0 : $dni_disc[$i],
                        'PO_RQSTBY' => $h_request_by,
                        'PO_PAYTERM' => $h_payterm,
                        'PO_RMRK' => $h_remark,
                        'PO_SHPCOST' => $h_shp_cost,
                        'PO_PRICE' => $dni_price[$i],
                    ];
                    $ttlupdated += $this->PO_mod->update_where($colupdate, ['PO_NO' => $h_po, 'PO_LINE' => $dni_rowid[$i]]);
                }
            }
            if (count($saveItem) > 0) {
                $ttlsaved += $this->PO_mod->insertb($saveItem);
            }
            $saveItemDiscount = [];
            for ($i = 0; $i < $ttlrows_Discount; $i++) {
                if ($dd_rowid[$i] === '') {
                    $saveItemDiscount[] = [
                        'PODISC_PONO' => $h_po,
                        'PODISC_REV' => 0,
                        'PODISC_DESC' => $dd_description[$i],
                        'PODISC_DISC' => str_replace(',', '', $dd_disc[$i]),
                        'PODISC_LINE' => $line_discount++,
                    ];
                } else {
                    $this->PO_mod->update_discount_where(
                        [
                            'PODISC_DESC' => $dd_description[$i],
                            'PODISC_DISC' => str_replace(',', '', $dd_disc[$i]),
                        ],
                        ['PODISC_PONO' => $h_po, 'PODISC_LINE' => $dd_rowid[$i]]
                    );
                }
            }
            if (count($saveItemDiscount) > 0) {
                $this->PO_mod->insertb_discount($saveItemDiscount);
            }
            $myar[] = ['cd' => '1', 'msg' => $ttlsaved . ' Saved, ' . $ttlupdated . ' updated', 'doc' => $h_po];
            die(json_encode(['status' => $myar]));
        } else {
            $ttlsaved = 0;
            $adate = explode("-", $h_issu_date);
            $mmonth = $adate[1];
            $myear = $adate[0];
            $display_year = substr($myear, -2);
            $numbr = $this->PO_mod->select_docnum_patterned($myear) + 1;
            $gen_po_num = $display_year . $mmonth . substr('0000' . $numbr, -4);
            $saveItem = [];
            $saveItemDiscount = [];
            #check is item already registered
            for ($i = 0; $i < $ttlrows_item; $i++) {
                if (!$this->MSTITM_mod->check_Primary(['MITM_ITMCD' => $di_item[$i]])) {
                    $myar[] = ['cd' => '0', 'msg' => 'item ' . $di_item[$i] . ' is not registered'];
                    die(json_encode(['status' => $myar]));
                    break;
                }
            }
            #end

            for ($i = 0; $i < $ttlrows_item; $i++) {
                if ($di_price[$i] === '') {
                    $myar[] = ['cd' => '0', 'msg' => 'price is invalid'];
                    die(json_encode(['status' => $myar]));
                    break;
                }
            }
            for ($i = 0; $i < $ttlrows_Nonitem; $i++) {
                if ($dni_price[$i] === '') {
                    $myar[] = ['cd' => '0', 'msg' => 'price is invalid'];
                    die(json_encode(['status' => $myar]));
                    break;
                }
            }

            for ($i = 0; $i < $ttlrows_item; $i++) {
                $saveItem[] = [
                    'PO_NO' => $gen_po_num,
                    'PO_REV' => 0,
                    'PO_REQDT' => $h_req_date,
                    'PO_ISSUDT' => $h_issu_date,
                    'PO_SUPCD' => $h_supplier,
                    'PO_ITMCD' => $di_item[$i],
                    'PO_QTY' => abs($di_qty[$i]),
                    'PO_CRTDT' => $current_datetime_,
                    'PO_CRTBY' => $userid,
                    'PO_PPH' => $h_pph,
                    'PO_VAT' => $h_vat,
                    'PO_LINE' => $i,
                    'PO_SUBJECT' => $di_subject[$i],
                    'PO_SECTION' => $di_section[$i],
                    'PO_DEPT' => $di_dept[$i],
                    'PO_SHPDLV' => $h_shp,
                    'PO_DISC' => strlen($di_disc[$i]) == 0 ? 0 : $di_disc[$i],
                    'PO_RQSTBY' => $h_request_by,
                    'PO_PAYTERM' => $h_payterm,
                    'PO_RMRK' => $h_remark,
                    'PO_SHPCOST' => $h_shp_cost,
                    'PO_PRICE' => $di_price[$i],
                ];
            }
            if (count($saveItem) > 0) {
                $ttlsaved += $this->PO_mod->insertb($saveItem);
            }
            $saveItem = [];
            for ($i = 0; $i < $ttlrows_Nonitem; $i++) {
                $saveItem[] = [
                    'PO_NO' => $gen_po_num,
                    'PO_REV' => 0,
                    'PO_REQDT' => $h_req_date,
                    'PO_ISSUDT' => $h_issu_date,
                    'PO_SUPCD' => $h_supplier,
                    'PO_ITMNM' => $dni_item[$i],
                    'PO_UM' => $dni_measure[$i],
                    'PO_QTY' => abs($dni_qty[$i]),
                    'PO_CRTDT' => $current_datetime_,
                    'PO_CRTBY' => $userid,
                    'PO_PPH' => $h_pph,
                    'PO_VAT' => $h_vat,
                    'PO_LINE' => $i,
                    'PO_SUBJECT' => $dni_subject[$i],
                    'PO_SECTION' => $dni_section[$i],
                    'PO_DEPT' => $dni_dept[$i],
                    'PO_SHPDLV' => $h_shp,
                    'PO_DISC' => strlen($dni_disc[$i]) == 0 ? 0 : $dni_disc[$i],
                    'PO_RQSTBY' => $h_request_by,
                    'PO_PAYTERM' => $h_payterm,
                    'PO_RMRK' => $h_remark,
                    'PO_SHPCOST' => $h_shp_cost,
                    'PO_PRICE' => $dni_price[$i],
                ];
            }
            if (count($saveItem) > 0) {
                $ttlsaved += $this->PO_mod->insertb($saveItem);
            }
            for ($i = 0; $i < $ttlrows_Discount; $i++) {
                $saveItemDiscount[] = [
                    'PODISC_PONO' => $gen_po_num,
                    'PODISC_REV' => 0,
                    'PODISC_DESC' => $dd_description[$i],
                    'PODISC_DISC' => $dd_disc[$i],
                    'PODISC_LINE' => $i,
                ];
            }
            if (count($saveItemDiscount) > 0) {
                $this->PO_mod->insertb_discount($saveItemDiscount);
            }
            $myar[] = ['cd' => '1', 'msg' => 'OK', 'saved' => $ttlsaved, 'doc' => $gen_po_num];
            die(json_encode(['status' => $myar]));
        }
    }

    public function search()
    {
        header('Content-Type: application/json');
        $search = $this->input->get('search');
        $useperiod = $this->input->get('useperiod');
        $searchby = $this->input->get('searchby');
        $date0 = $this->input->get('date0');
        $date1 = $this->input->get('date1');
        if ($useperiod == '0') {
            if ($searchby == '0') {
                $rs = $this->PO_mod->select_header_like(['PO_NO' => $search]);
            } else {
                $rs = $this->PO_mod->select_header_like(['MSUP_SUPNM' => $search]);
            }
        } else {
            if ($searchby == '0') {
                $rs = $this->PO_mod->select_header_period_like($date0, $date1, ['PO_NO' => $search]);
            } else {
                $rs = $this->PO_mod->select_header_period_like($date0, $date1, ['MSUP_SUPNM' => $search]);
            }
        }
        die(json_encode(['data' => $rs]));
    }

    public function detail()
    {
        header('Content-Type: application/json');
        $doc = $this->input->get('doc');
        $rs = $this->PO_mod->select_detail_where(['PO_NO' => $doc]);
        $rs2 = $this->PO_mod->select_discount_detail_where(['PODISC_PONO' => $doc]);
        die(json_encode(['data' => $rs, 'data_discount' => $rs2]));
    }

    public function search_balance()
    {
        header('Content-Type: application/json');
        $search = $this->input->get('search');
        $searchtype = $this->input->get('searchtype');
        $like = ['PO_NO' => $search];
        $rs = $searchtype == '1' ? array_merge($this->PO_mod->select_balance_like($like), $this->PO_mod->select_balance_mega_like($like))
        : $this->PO_mod->select_balance_nonitem_like($like);
        $polist = [];
        foreach ($rs as $r) {
            if (!in_array($r['PO_NO'], $polist)) {
                $polist[] = $r['PO_NO'];
            }
        }
        $rsdisc = $this->PO_mod->select_discount_where_PO_in($polist);
        foreach ($rs as &$r) {
            foreach ($rsdisc as $d) {
                if ($r['PO_NO'] === $d['PODISC_PONO']) {
                    $r['SPECEIALDISC'] = $d['PODISC_DISC'];
                    $r['PO_PRICE'] -= $d['PODISC_DISC'];
                }
            }
        }
        unset($r);
        die(json_encode(['data' => $rs]));
    }

    public function report()
    {
        if (!isset($_COOKIE["PO_DATE1"]) || !isset($_COOKIE["PO_DATE2"])) {
            exit('no data to be found');
        }
        date_default_timezone_set('Asia/Jakarta');
        $cdtfrom = $_COOKIE["PO_DATE1"];
        $cdtto = $_COOKIE["PO_DATE2"];
        $rs = $this->PO_mod->select_report($cdtfrom, $cdtto);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('PURCHASE');
        $sheet->setCellValueByColumnAndRow(1, 1, 'PT. SMT INDONESIA');
        $sheet->setCellValueByColumnAndRow(1, 2, 'Kawasan EJIP Plot 5C2 Cikarang Selatan');
        $sheet->setCellValueByColumnAndRow(1, 3, 'PURCHASE ORDER SUMMARY TRANSACTIONS');
        $sheet->setCellValueByColumnAndRow(1, 4, 'PERIOD : ' . $cdtfrom . ' - ' . $cdtto);
        $sheet->getStyle('A3:A4')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A3:A4')->getFont()->setBold(true)->setSize(12);
        $sheet->mergeCells('A3:W3');
        $sheet->mergeCells('A4:W4');
        $sheet->setCellValueByColumnAndRow(1, 6, 'Trans No.');
        $sheet->setCellValueByColumnAndRow(2, 6, 'Trans Date');
        $sheet->setCellValueByColumnAndRow(3, 6, 'Vender Code');
        $sheet->setCellValueByColumnAndRow(4, 6, 'Vender Name');
        $sheet->setCellValueByColumnAndRow(5, 6, 'Currency');
        $sheet->setCellValueByColumnAndRow(6, 6, 'Remarks');
        $sheet->setCellValueByColumnAndRow(7, 6, 'Shipped Via');
        $sheet->setCellValueByColumnAndRow(8, 6, 'Payment Terms');
        $sheet->setCellValueByColumnAndRow(9, 6, 'PPH %');
        $sheet->setCellValueByColumnAndRow(10, 6, 'VAT');
        $sheet->setCellValueByColumnAndRow(11, 6, 'Dept');
        $sheet->setCellValueByColumnAndRow(12, 6, 'Subject Code');
        $sheet->setCellValueByColumnAndRow(13, 6, 'Subject Name');
        $sheet->setCellValueByColumnAndRow(14, 6, 'Amount Total');
        $sheet->setCellValueByColumnAndRow(15, 6, 'Item Code');
        $sheet->setCellValueByColumnAndRow(16, 6, 'Item Name');
        $sheet->setCellValueByColumnAndRow(17, 6, 'Item Group');
        $sheet->setCellValueByColumnAndRow(18, 6, 'Pch Qty');
        $sheet->setCellValueByColumnAndRow(19, 6, 'Pch Price');
        $sheet->setCellValueByColumnAndRow(20, 6, 'Disc (%)');
        $sheet->setCellValueByColumnAndRow(21, 6, 'Net Amount');
        $sheet->setCellValueByColumnAndRow(22, 6, 'Delivery Date');
        $sheet->setCellValueByColumnAndRow(23, 6, 'Status');
        $sheet->getStyle('A6:W6')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ffcc99');
        $sheet->getStyle('A6:W6')->getFont()->setBold(true);
        $i = 7;
        foreach ($rs as $r) {
            $sheet->setCellValueByColumnAndRow(1, $i, $r['PO_NO']);
            $sheet->setCellValueByColumnAndRow(2, $i, $r['PO_ISSUDT']);
            $sheet->setCellValueByColumnAndRow(3, $i, $r['PO_SUPCD']);
            $sheet->setCellValueByColumnAndRow(4, $i, $r['SUPNM']);
            $sheet->setCellValueByColumnAndRow(5, $i, $r['CURRENCY']);
            $sheet->setCellValueByColumnAndRow(6, $i, $r['PO_RMRK']);
            $sheet->setCellValueByColumnAndRow(7, $i, $r['PO_SHPDLV']);
            $sheet->setCellValueByColumnAndRow(8, $i, $r['PO_PAYTERM']);
            $sheet->setCellValueByColumnAndRow(9, $i, $r['PO_PPH']);
            $sheet->setCellValueByColumnAndRow(10, $i, $r['PO_VAT']);
            $sheet->setCellValueByColumnAndRow(11, $i, 'Dept');
            $sheet->setCellValueByColumnAndRow(12, $i, 'Subject Code');
            $sheet->setCellValueByColumnAndRow(13, $i, 'Subject Name');
            $sheet->setCellValueByColumnAndRow(14, $i, $r['AMOUNT']);
            $sheet->setCellValueByColumnAndRow(15, $i, $r['PO_ITMCD']);
            $sheet->setCellValueByColumnAndRow(16, $i, $r['MITM_ITMD1']);
            $sheet->setCellValueByColumnAndRow(17, $i, 'Item Group');
            $sheet->setCellValueByColumnAndRow(18, $i, $r['TQTY']);
            $sheet->setCellValueByColumnAndRow(19, $i, $r['PO_PRICE']);
            $sheet->setCellValueByColumnAndRow(20, $i, $r['PO_DISC']);
            $sheet->setCellValueByColumnAndRow(21, $i, $r['NETAMOUNT']);
            $sheet->setCellValueByColumnAndRow(22, $i, $r['RCV_RCVDATE']);
            $sheet->setCellValueByColumnAndRow(23, $i, $r['TQTY'] == $r['RCV_QTY'] ? 'Close' : 'Open');
            $i++;
        }
        foreach (range("B", "W") as $r) {
            $sheet->getColumnDimension($r)->setAutoSize(true);
        }
        $stringjudul = "PO $cdtfrom to $cdtto";
        $writer = new Xlsx($spreadsheet);
        $filename = $stringjudul . date(' H i'); //save our workbook as this file name

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    public function form_po()
    {
        $this->load->view('wms/vpo_new');
    }

    public function additional()
    {
        header('Content-Type: application/json');
        $rspayment = $this->PO_mod->select_column_history(['PO_PAYTERM']);
        $rsSHP = $this->PO_mod->select_column_history(['PO_SHPDLV']);
        die(json_encode(['payment_term' => $rspayment, 'shipment' => $rsSHP]));
    }

    public function ost_request()
    {
        header('Content-Type: application/json');
        $rs = $this->TREQPARTLKL_mod->selectall_ost();
        die(json_encode(['data' => $rs]));
    }
}
