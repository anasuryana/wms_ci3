<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class WO extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('XWO_mod');
        $this->load->model('XMBOM_mod');
        $this->load->helper('url_helper');
        $this->load->library('session');
        $this->load->helper('security');
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        die('sorry');
    }

    public function form_suggester()
    {
        $this->load->view('wms_report/vwo_suggester');
    }
    public function form_entry()
    {
        $this->load->view('wms/vwo_output');
    }
    public function form_report()
    {
        $this->load->view('wms_report/vwo_output_daily');
    }
    public function form_cost_report()
    {
        $this->load->view('wms_report/vwo_cost');
    }

    public function checkSimulation()
    {
        $ReffList = $this->input->post('ReffList');
        $JobList = $this->input->post('JobList');
        $AssyCodeList = $this->input->post('AssyCodeList');
        $InputLine = strtoupper($this->input->post('Line'));
        $TotalData = is_array($ReffList) ? count($ReffList) : 0;

        // $NOT_FOUND = 0;
        // $FOUND_BUT_NOT_SAME_LINE = 1;
        // $OK = 3;

        $Requirements = [];
        for ($i = 0; $i < $TotalData; $i++) {
            $Requirements[] = [
                'WO' => strtoupper($ReffList[$i]),
                'Job' => strtoupper($JobList[$i]),
                'AssyCode' => strtoupper($AssyCodeList[$i]),
                'PlannedLine' => null,
                'Status' => null,
                'SimCode' => null,
            ];
        }

        $strWOList = is_array($ReffList) ? "'" . implode("','", $ReffList) . "'" : "''";
        $WOs = $this->XWO_mod->selectWOSIM($strWOList);

        foreach ($Requirements as &$r) {
            foreach ($WOs as $w) {
                if ($w['PDPP_WONO'] === $r['WO'] && $r['Status'] !== 'OK') {
                    $r['PlannedLine'] .= $w['PIS1_LINENO'] . ',';
                    $r['SimCode'] = $w['PIS1_DOCNO'];
                    if (str_contains($w['PIS1_LINENO'], $InputLine)) {
                        $r['Status'] = 'OK';
                        break;
                    } else {
                        $r['Status'] = $w['PIS1_WONO'] ? 'FOUND BUT DIFFERENT LINE' : 'NOT SIMULATED';
                    }
                }
            }
            if (!$r['Status']) {
                $r['Status'] = 'NOT FOUND';
            }
        }
        unset($r);

        die(json_encode(['data' => $Requirements, '$WOs' => $WOs]));
    }

    public function suggestProcess()
    {
        $woReff = $this->input->post('woReff');
        $woAssy = $this->input->post('woAssy');
        $woProdQty = $this->input->post('woProdQty');
        $outputType = $this->input->post('outputType');
        $status = [];
        $RS = [];
        $woAssyStr = "''";
        $woAssyStrTest = "''";
        $dataFix = [];
        $RSMegaBom = [];
        $_woMegaRev = [];

        $_modelCode = [];
        $_modelVersion = [];
        $_modelProcess = [];
        $RSAlternativeLine = [];

        $woFlagPassOK = [];
        if (is_array($woReff) && is_array($woAssy)) {
            $CounReff = count($woReff);

            foreach ($woReff as $r) {
                $woFlagPassOK[] = 0;
            }
            if ($CounReff === count($woAssy)) {
                $uniqueAssy = array_unique($woAssy);
                # implementasi fungsi untuk membuang karakter 'quot' di tiap elemen dari array
                $woAssyTest = array_map(function ($value) {
                    return $this->UnnecessaryCharRemover($value);
                }, $uniqueAssy);

                # ubah dari array ke format string, untuk keperluan query :)
                $woAssyStr = "'" . implode("','", $woAssyTest) . "'";
                $RS = $this->XWO_mod->selectProcess($woAssyStr);

                # ambil versi data bom revisi terakhir dari MEGA
                $RSMegaBom = $this->XMBOM_mod->selectVersionWhereItemIn($uniqueAssy);

                # buat deretan dar field 'MEGAREV' sesuai dengan deretan input, agar ketika diiterasi sekali jalan
                foreach ($woAssy as $n) {
                    foreach ($RSMegaBom as $y) {
                        if ($n == $y['MBOM_MDLCD']) {
                            $_woMegaRev[] = $y['MBOM_BOMRV'];
                            break;
                        }
                    }
                }

                if (count($_woMegaRev) != $CounReff) {
                    # ketika ada assy code yang belum terdaftar
                    for ($i = 0; $i < $CounReff; $i++) {
                        $isExist = 0;
                        foreach ($RS as $r) {
                            if ($woAssy[$i] === $r['MBLA_MDLCD']) {
                                $_megabom = '';
                                foreach ($RSMegaBom as $z) {
                                    if ($r['MBLA_MDLCD'] === $z['MBOM_MDLCD']) {
                                        $_megabom = $z['MBOM_BOMRV'];
                                        break;
                                    }
                                }

                                switch ($r['XLINE']) {
                                    case 'SMT-S1':
                                        $lineCode = 'SMT-PS1';
                                        break;
                                    default:
                                        $lineCode = $r['XLINE'];
                                }
                                $toPush = [
                                    'RefNo' => $woReff[$i],
                                    'ProdLine' => $lineCode,
                                    'Process' => $r['MBLA_PROCD'],
                                    'AssyCode' => $woAssy[$i],
                                    'AssyRev' => $r['MBLA_BOMRV'],
                                    'ProdQty' => $woProdQty[$i],
                                    'Group' => $r['MBLA_ITMCD'],
                                    'MEGALatestRev' => $_megabom,
                                    'Model' => $r['MITM_ITMD1'] . "_",
                                    'ModelType' => $r['TYPE_FIX'],
                                ];

                                $dataFix[] = $toPush;
                                $isExist = 1;

                                if ($r['CNT'] > 1) {
                                    if (!in_array($r['MBLA_MDLCD'], $_modelCode)) {
                                        $_modelCode[] = $r['MBLA_MDLCD'];
                                    }
                                    if (!in_array($r['MBLA_PROCD'], $_modelProcess)) {
                                        $_modelProcess[] = $r['MBLA_PROCD'];
                                    }
                                    if (!in_array($r['MBLA_BOMRV'], $_modelVersion)) {
                                        $_modelVersion[] = $r['MBLA_BOMRV'];
                                    }
                                }
                            }
                        }
                        $woFlagPassOK[$i] = $isExist;
                    }

                    # tambahkan yang belum terdaftar ke daftar data yang akan ditampilkan
                    for ($i = 0; $i < $CounReff; $i++) {
                        if ($woFlagPassOK[$i] == 0) {
                            $dataFix[] = [
                                'RefNo' => $woReff[$i],
                                'ProdLine' => '???',
                                'Process' => '???',
                                'AssyCode' => $woAssy[$i],
                                'AssyRev' => '???',
                                'ProdQty' => $woProdQty[$i],
                                'Group' => '???',
                                'MEGALatestRev' => '??',
                                'Model' => '???',
                                'ModelType' => '???',
                            ];
                        }
                    }
                } else {
                    # olah
                    for ($i = 0; $i < $CounReff; $i++) {
                        foreach ($RS as $r) {
                            if ($woAssy[$i] === $r['MBLA_MDLCD']) {
                                switch ($r['XLINE']) {
                                    case 'SMT-S1':
                                        $lineCode = 'SMT-PS1';
                                        break;
                                    default:
                                        $lineCode = $r['XLINE'];
                                }
                                $toPush = [
                                    'RefNo' => $woReff[$i],
                                    'ProdLine' => $lineCode,
                                    'Process' => $r['MBLA_PROCD'],
                                    'AssyCode' => $woAssy[$i],
                                    'AssyRev' => $r['MBLA_BOMRV'],
                                    'ProdQty' => $woProdQty[$i],
                                    'Group' => $r['MBLA_ITMCD'],
                                    'MEGALatestRev' => $_woMegaRev[$i],
                                    'Model' => $r['MITM_ITMD1'],
                                    'ModelType' => $r['TYPE_FIX'],
                                ];

                                $dataFix[] = $toPush;

                                if ($r['CNT'] > 1) {
                                    if (!in_array($r['MBLA_MDLCD'], $_modelCode)) {
                                        $_modelCode[] = $r['MBLA_MDLCD'];
                                    }
                                    if (!in_array($r['MBLA_PROCD'], $_modelProcess)) {
                                        $_modelProcess[] = $r['MBLA_PROCD'];
                                    }
                                    if (!in_array($r['MBLA_BOMRV'], $_modelVersion)) {
                                        $_modelVersion[] = $r['MBLA_BOMRV'];
                                    }
                                }
                            }
                        }
                    }
                }
                # cari alternatif line
                if (!empty($_modelCode) && !empty($_modelProcess) && !empty($_modelVersion)) {
                    $RSAlternativeLine = $this->XWO_mod->selectLineWhereInModelProcessAndVersion($_modelCode, $_modelProcess, $_modelVersion);
                } else {
                    $RSAlternativeLine = [];
                }
            } else {
                $status[] = ['cd' => 0, 'msg' => 'array input size should be same'];
            }
        } else {
            $status[] = ['cd' => 0, 'msg' => 'array is required'];
        }
        if (strtolower($outputType) === 'json') {
            header('Content-Type: application/json');
            die(json_encode(['status' => $status, 'data' => $RS, '$dataFix' => $dataFix, '$RSMegaBom' => $RSMegaBom, '$RSAlternativeLine' => $RSAlternativeLine]));
        } else {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('result');
            $sheet->setCellValueByColumnAndRow(1, 1, 'Upload Work Order' . count($_woMegaRev) . "!=" . $CounReff . "rsbom" . count($RSMegaBom));
            $sheet->setCellValueByColumnAndRow(1, 2, 'Reference No');
            $sheet->setCellValueByColumnAndRow(2, 2, 'Production Line');
            $sheet->setCellValueByColumnAndRow(3, 2, 'Process');
            $sheet->setCellValueByColumnAndRow(4, 2, 'Assy Code');
            $sheet->setCellValueByColumnAndRow(5, 2, 'Assy Rev.');
            $sheet->setCellValueByColumnAndRow(6, 2, 'Production Qty.');
            $sheet->setCellValueByColumnAndRow(7, 2, 'Production Date');
            $sheet->setCellValueByColumnAndRow(8, 2, 'Kitting Date');
            $sheet->setCellValueByColumnAndRow(9, 2, 'WO Due Date');
            $sheet->setCellValueByColumnAndRow(10, 2, 'Shift');
            $sheet->setCellValueByColumnAndRow(11, 2, 'Group');
            $sheet->setCellValueByColumnAndRow(12, 2, "MEGA's Latest Bom Rev.");
            $sheet->setCellValueByColumnAndRow(13, 2, "Model");
            $sheet->getStyle('A2:R2')->getFont()->setBold(true);
            $y = 3;
            foreach ($dataFix as $r) {
                $startColumn = 14;
                foreach ($RSAlternativeLine as $a) {
                    if (($r['AssyCode'] === $a['MDLCD'] && $r['Process'] === $a['MBLA_PROCD'] && $r['AssyRev']) && $r['ProdLine'] != $a['ALTLINE']) {
                        $sheet->setCellValueByColumnAndRow($startColumn, 2, 'Alternative Line');
                        $sheet->setCellValueByColumnAndRow($startColumn, $y, $a['ALTLINE']);
                        $startColumn++;
                    }
                }
                $sheet->setCellValueByColumnAndRow(1, $y, $r['RefNo']);
                $sheet->setCellValueByColumnAndRow(2, $y, $r['ProdLine']);
                $sheet->setCellValueByColumnAndRow(3, $y, $r['Process']);
                $sheet->setCellValueByColumnAndRow(4, $y, $r['AssyCode']);
                $sheet->setCellValueByColumnAndRow(5, $y, $r['AssyRev']);
                $sheet->setCellValueByColumnAndRow(6, $y, $r['ProdQty']);
                $sheet->setCellValueByColumnAndRow(11, $y, $r['Group']);
                $sheet->setCellValueByColumnAndRow(12, $y, $r['MEGALatestRev']);
                $sheet->setCellValueByColumnAndRow(13, $y, $r['Model']);

                $_JobType = substr($r['RefNo'], 4, 1);
                if ($r['ModelType'] && $r['ModelType'][0] != $_JobType && $r['ProdLine']!='???') {
                    $sheet->getStyle('A' . $y . ':M' . $y)->getFill()->setFillType(Fill::FILL_SOLID);
                    $sheet->getStyle('A' . $y . ':M' . $y)->getFill()->getStartColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_DARKRED);
                    $sheet->getComment('A' . $y)->getText()->createTextRun("Tipe Assycode " . $r['ModelType'] . ", sedangkan Job " . $_JobType);
                }
                $y++;
            }
            foreach (range('A', 'R') as $r) {
                $sheet->getColumnDimension($r)->setAutoSize(true);
            }
            $sheet->freezePane('A3');
            $stringjudul = "wo template " . date('Y-m-d H:i:s');
            $writer = new Xlsx($spreadsheet);
            $filename = $stringjudul;
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
        }
    }

    public function UnnecessaryCharRemover($element)
    {
        return str_replace(['"', "'"], "", $element);
    }

    public function ProcessHistory()
    {
        $woAssy = $this->input->post('woAssy');
        $outputType = $this->input->post('outputType');
        $status = $RS = [];
        if (is_array($woAssy)) {
            $uniqueAssy = array_unique($woAssy);
            # implementasi fungsi untuk membuang karakter 'quot' di tiap elemen dari array
            $woAssyTest = array_map(function ($value) {
                return $this->UnnecessaryCharRemover($value);
            }, $uniqueAssy);
            $woAssyStr = "'" . implode("','", $woAssyTest) . "'";
            $RS = $this->XWO_mod->selectHistory($woAssyStr);

            # ambil versi data bom revisi terakhir dari MEGA
            $RSMegaBom = $this->XMBOM_mod->selectVersionWhereItemIn($uniqueAssy);

            # tempel versi
            foreach ($RS as &$r) {
                $MegaBomVersion = null;
                foreach ($RSMegaBom as $m) {
                    if ($r['MBLA_MDLCD'] === $m['MBOM_MDLCD']) {
                        $MegaBomVersion = $m['MBOM_BOMRV'];
                        break;
                    }
                }
                $r['MEGABOMREV'] = $MegaBomVersion;
            }
            unset($r);
            $status[] = ['cd' => 1, 'msg' => 'ok'];
        } else {
            $status[] = ['cd' => 0, 'msg' => 'array is required'];
        }

        if (strtolower($outputType) === 'json') {
            header('Content-Type: application/json');
            die(json_encode(['status' => $status, 'data' => $RS]));
        } else {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('result');
            $sheet->setCellValueByColumnAndRow(1, 1, 'History');
            $sheet->setCellValueByColumnAndRow(1, 2, 'Model Name');
            $sheet->setCellValueByColumnAndRow(2, 2, 'Assy Code');
            $sheet->setCellValueByColumnAndRow(3, 2, 'Process');
            $sheet->setCellValueByColumnAndRow(4, 2, 'Assy Rev.');
            $sheet->setCellValueByColumnAndRow(5, 2, 'Line');
            $sheet->setCellValueByColumnAndRow(6, 2, 'Group');
            $sheet->setCellValueByColumnAndRow(7, 2, 'Mega Bom Latest Rev');
            $y = 3;
            foreach ($RS as $r) {
                $sheet->setCellValueByColumnAndRow(1, $y, $r['MITM_ITMD1']);
                $sheet->setCellValueByColumnAndRow(2, $y, $r['MBLA_MDLCD']);
                $sheet->setCellValueByColumnAndRow(3, $y, $r['MBLA_PROCD']);
                $sheet->setCellValueByColumnAndRow(4, $y, $r['MBLA_BOMRV']);
                $sheet->setCellValueByColumnAndRow(5, $y, $r['XLINE']);
                $sheet->setCellValueByColumnAndRow(6, $y, $r['MBLA_ITMCD']);
                $sheet->setCellValueByColumnAndRow(7, $y, $r['MEGABOMREV']);
                $y++;
            }
            foreach (range('A', 'R') as $r) {
                $sheet->getColumnDimension($r)->setAutoSize(true);
            }
            $sheet->freezePane('A3');
            $stringjudul = "process history " . date('Y-m-d H:i:s');
            $writer = new Xlsx($spreadsheet);
            $filename = $stringjudul;
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
        }
    }
}
