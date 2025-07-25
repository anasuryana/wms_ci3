<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TRF extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('ITH_mod');
        $this->load->model('MSTLOCG_mod');
        $this->load->model('SERD_mod');
        $this->load->model('TRF_mod');
        $this->load->model('TRFSET_mod');
        $this->load->model('XSTKTRND1_mod');
        date_default_timezone_set('Asia/Jakarta');
    }
    public function index()
    {
        die("sorry");
    }

    public function getBalanceTransferByPO()
    {
        header('Content-Type: application/json');
        $PONumber = $this->input->get('PONumber');
        $LocationFR = $this->input->get('LocationFR');
        $RS = $this->TRF_mod->selectBalanceTransferByPO($PONumber);
        $ItemDistinct = [];
        foreach ($RS as $r) {
            if (!in_array($r['PO_ITMCD'], $ItemDistinct)) {
                $ItemDistinct[] = $r['PO_ITMCD'];
            }
        }
        if (!empty($ItemDistinct)) {
            # set maksimal transfer qty = stock qty berjalan
            $RSStock = $this->ITH_mod->selectStockWhereItemIn($ItemDistinct, $LocationFR);
            foreach ($RS as &$r) {
                foreach ($RSStock as $s) {
                    if ($r['PO_ITMCD'] === $s['ITH_ITMCD']) {
                        if ($r['BALQT'] > $s['SQT']) {
                            $r['BALQT'] = $s['SQT'];
                        } else {

                        }
                        break;
                    }
                }
            }
            unset($r);
        }
        die(json_encode(['data' => $RS]));
    }

    public function form_part()
    {
        $todiswh = $FromWH = '';
        $rs = $this->MSTLOCG_mod->selectall_where_CODE_in(['ENGEQUIP',
            'ENGLINEEQUIP',
            'MFG1EQUIP',
            'MFG2EQUIP',
            'PPICEQUIP',
            'PSIEQUIP',
            'QAEQUIP',
            'FCTEQUIP',
            'ICTEQUIP',
            'HRDEQUIP',
            'ACCEQUIP'
        ]);
        $rsFromWH = $this->TRFSET_mod->selectColumnWhere(['MSTLOCG_ID', 'MSTLOCG_NM'], ['TRFSET_APPROVER' => $this->session->userdata('nama')]);
        $_listOfLocationTobeMerged = [];

        foreach($rsFromWH as $r) {
            $isFound = false;
            foreach($rs as $f) {
                if($r['MSTLOCG_ID'] === $f['MSTLOCG_ID']) {
                    $isFound = true;
                    break;
                }
            }

            if(!$isFound) {
                $_listOfLocationTobeMerged[] = ['MSTLOCG_ID' => $r['MSTLOCG_ID'], 'MSTLOCG_NM' => $r['MSTLOCG_NM']];
            }
        }

        if(!empty($_listOfLocationTobeMerged)) {
            $rs = array_merge($rs, $_listOfLocationTobeMerged);
        }

        foreach ($rs as $r) {
            $todiswh .= '<option value="' . $r['MSTLOCG_ID'] . '">' . $r['MSTLOCG_NM'] . ' (' . $r['MSTLOCG_ID'] . ')</option>';
        }

        foreach ($rsFromWH as $r) {
            $FromWH .= '<option value="' . $r['MSTLOCG_ID'] . '">' . $r['MSTLOCG_NM'] . ' (' . $r['MSTLOCG_ID'] . ')</option>';
        }
        $data['lwh'] = $todiswh;
        $data['fwh'] = $FromWH;

        $this->load->view('wms/vtransfer_part', $data);
    }

    public function form_FG()
    {
        $this->load->view('wms/vtransfer_FG');
    }

    public function form_sync()
    {
        $this->load->view('wms/vsync_transfer');
    }

    public function form_setting()
    {
        $this->load->view('wms/vtrf_set');
    }

    public function form_indirect_part()
    {
        $todiswh = '';
        $rs = $this->MSTLOCG_mod->selectall_where_CODE_in([
            'AIWH1',
            'PLANT1',
            'PLANT2',
            'ARWH1',
            'ARWH2',
        ]);
        foreach ($rs as $r) {
            $todiswh .= '<option value="' . $r['MSTLOCG_ID'] . '">' . $r['MSTLOCG_NM'] . ' (' . $r['MSTLOCG_ID'] . ')</option>';
        }
        $data['lwh'] = $todiswh;
        $this->load->view('wms/vtransfer_indirect_part', $data);
    }

    public function saveDraft()
    {
        # Format Nomor Dokumen
        # WTRF-YYM0000
        header('Content-Type: application/json');
        $this->checkSession();
        $CurrentDateTime = date('Y-m-d H:i:s');
        $doc = $this->input->post('doc');
        $docDate = $this->input->post('docDate');
        $frLoc = $this->input->post('frLoc');
        $toLoc = $this->input->post('toLoc');
        $LineID = $this->input->post('LineID');
        $LineItemCode = $this->input->post('LineItemCode');
        $LineItemQty = $this->input->post('LineItemQty');
        $LineRefDocument = $this->input->post('LineRefDocument');
        $TotalRows = is_array($LineItemCode) ? count($LineItemCode) : 0;
        $RSOK = [];
        $RSResume = [];
        $Array_docDate = explode("-", $docDate);
        $MonthIssue = $Array_docDate[1];
        $YearIssue = $Array_docDate[0];
        $RSStock = [];
        $RSStockUnreceive = [];
        if (strlen($doc) === 0) {
            for ($i = 0; $i < $TotalRows; $i++) {
                $isFound = false;
                foreach ($RSResume as &$r) {
                    if ($r['ITEMCD'] === strtoupper($LineItemCode[$i])) {
                        $r['ITEMQT'] += $LineItemQty[$i];
                    }
                }
                unset($r);

                if (!$isFound) {
                    $RSResume[] = ['ITEMCD' => strtoupper($LineItemCode[$i]), 'ITEMQT' => $LineItemQty[$i]];
                }
            }

            # periksa stok
            $RSStock = !empty($LineItemCode) ? $this->ITH_mod->selectStockWhereItemIn($LineItemCode, $frLoc) : [];
            $RSStockUnreceive = $this->TRF_mod->selectStockUnReceive($LineItemCode, $frLoc);
            if (!empty($RSStock)) {
                # jadikan qty draft sebagai pengurang stock
                foreach ($RSStockUnreceive as &$r) {
                    foreach ($RSStock as &$n) {
                        if ($r['TRFD_ITEMCD'] === $n['ITH_ITMCD'] && $r['DQT'] > 0) {
                            $n['SQT'] -= $r['DQT'];
                            $r['DQT'] = 0;
                        }
                    }
                    unset($n);
                }
                unset($r);
            }

            $isStockEnough = true;
            foreach ($RSResume as $r) {
                if($RSStock) {
                    foreach ($RSStock as $s) {
                        if ($r['ITEMCD'] === $s['ITH_ITMCD'] && $s['SQT'] < $r['ITEMQT']) {
                            $isStockEnough = false;
                            $response[] = ['cd' => '0', 'msg' => 'Stock is not enough for ' . $s['ITH_ITMCD']];
                        }
                    }
                    if (!$isStockEnough) {
                        break;
                    }
                } else {
                    $response[] = ['cd' => '0', 'msg' => 'Stock is not enough for ' . $r['ITEMCD']];
                }
            }
            if ($isStockEnough) {
                # buat nomor transaksi
                $RSLOrder = $this->TRF_mod->selectLastOrder(date('m'), date('Y'));
                $LOrder = 0;
                $NewDocument = '';
                foreach ($RSLOrder as $r) {
                    $_year = date('y');
                    $_month = date('m') * 1;
                    $_monthDisplay = '';
                    $_newOrder = $r->LORDER + 1;
                    $_newOrderDisplay = substr('0000' . $_newOrder, -4);
                    $LOrder = $r->LORDER + 1;
                    switch ($_month) {
                        case 10:
                            $_monthDisplay = 'X';
                            break;
                        case 11:
                            $_monthDisplay = 'Y';
                            break;
                        case 12:
                            $_monthDisplay = 'Z';
                            break;
                        default:
                            $_monthDisplay = $_month;
                    }
                    $NewDocument = 'WTRF-' . $_year . $_monthDisplay . $_newOrderDisplay;
                }

                # simpan Header
                $TobeSaved = [
                    'TRFH_DOC' => $NewDocument,
                    'TRFH_CREATED_DT' => $CurrentDateTime,
                    'TRFH_CREATED_BY' => $this->session->userdata('nama'),
                    'TRFH_ISSUE_DT' => $docDate,
                    'TRFH_LOC_FR' => $frLoc,
                    'TRFH_LOC_TO' => $toLoc,
                    'TRFH_ORDER' => $LOrder,
                ];
                $AffectedRows = $this->TRF_mod->insert($TobeSaved);
                if ($AffectedRows) {
                    $TobeSaved_d = [];
                    $RSAutoConform = [];
                    $ITH_FORM_INC = $ITH_FORM_OUT = '';

                    if(str_contains($toLoc, 'SCR')) {
                        $ITH_FORM_INC = 'INC-SCR-'.$toLoc;
                        $ITH_FORM_OUT = 'OUT-WH-SCR-'.$frLoc;
                    } else {
                        $ITH_FORM_INC = 'TRF-INC';
                        $ITH_FORM_OUT = 'TRF-OUT';
                    }

                    for ($i = 0; $i < $TotalRows; $i++) {
                        $_TobeSaved = [
                            'TRFD_DOC' => $NewDocument,
                            'TRFD_ITEMCD' => $LineItemCode[$i],
                            'TRFD_QTY' => $LineItemQty[$i],
                            'TRFD_REFFERENCE_DOCNO' => $LineRefDocument[$i],
                            'TRFD_CREATED_BY' => $this->session->userdata('nama'),
                            'TRFD_CREATED_DT' => $CurrentDateTime,
                        ];
                        if (strtoupper(trim($LineItemCode[$i])) === 'N2GAS' || str_contains($toLoc, 'SCR') ) {
                            $RSAutoConform[] = [
                                'ITH_ITMCD' => $LineItemCode[$i],
                                'ITH_DATE' => $docDate,
                                'ITH_FORM' => $ITH_FORM_INC,
                                'ITH_DOC' => $NewDocument,
                                'ITH_QTY' => $LineItemQty[$i] * 1,
                                'ITH_WH' => $toLoc,
                                'ITH_LUPDT' => $docDate . date(' H:i:s'),
                                'ITH_USRID' => $this->session->userdata('nama'),
                            ];
                            $RSAutoConform[] = [
                                'ITH_ITMCD' => $LineItemCode[$i],
                                'ITH_DATE' => $docDate,
                                'ITH_FORM' => $ITH_FORM_OUT,
                                'ITH_DOC' => $NewDocument,
                                'ITH_QTY' => $LineItemQty[$i] * -1,
                                'ITH_WH' => $frLoc,
                                'ITH_LUPDT' => $docDate . date(' H:i:s'),
                                'ITH_USRID' => $this->session->userdata('nama'),
                            ];
                            $_TobeSaved['TRFD_RECEIVE_BY'] = $this->session->userdata('nama');
                            $_TobeSaved['TRFD_RECEIVE_DT'] = $CurrentDateTime;
                        }
                        $TobeSaved_d[] = $_TobeSaved;
                    }
                    if (!empty($TobeSaved_d)) {
                        # simpan Detail
                        $this->TRF_mod->insertb($TobeSaved_d);

                        # simpan data autoconform jika perlu
                        if (!empty($RSAutoConform)) {
                            $this->ITH_mod->insertb($RSAutoConform);

                            if(strtoupper(trim($LineItemCode[0])) === 'N2GAS') {
                                # cari transaksi sebelumnya untuk dikeluarkan
                                $RSLastTransaction = $this->ITH_mod->selectLastTransactionBeforeDate($docDate, 'N2GAS', 'INC-DO');
                                foreach ($RSLastTransaction as &$r) {
                                    $r['ITH_FORM'] = 'OUT-USE';
                                    $r['ITH_DATE'] = $docDate;
                                    $r['ITH_QTY'] = $r['ITH_QTY'] * -1;
                                    $r['ITH_WH'] = $toLoc;
                                    $r['ITH_LUPDT'] = $docDate . date(' H:i:s');
                                }
                                unset($r);
                                $this->ITH_mod->insertb($RSLastTransaction);
                            }
                        }

                        $response[] = ['cd' => '1', 'msg' => 'OK', 'reff' => $NewDocument];
                        $RSOK = $this->TRF_mod->selectDetailWhere(['TRFD_DOC' => $NewDocument]);
                    }
                } else {
                    $response[] = ['cd' => '0', 'msg' => 'Could not save Header file'];
                }
            }
        } else {
            $response[] = ['cd' => '0', 'msg' => 'something wrong happen, please refresh browser'];
        }
        die(json_encode(['status' => $response, 'data' => $RSOK, '$RSStock' => $RSStock
            , '$RSStockUnreceive' => $RSStockUnreceive
            , '$RSResume' => $RSResume])
        );
    }

    public function updateDraft()
    {
        header('Content-Type: application/json');
        $this->checkSession();
        $CurrentDateTime = date('Y-m-d H:i:s');
        $doc = $this->input->post('doc');
        $docDate = $this->input->post('docDate');
        $frLoc = $this->input->post('frLoc');
        $toLoc = $this->input->post('toLoc');
        $LineID = $this->input->post('LineID');
        $LineItemCode = $this->input->post('LineItemCode');
        $LineItemQty = $this->input->post('LineItemQty');
        $LineRefDocument = $this->input->post('LineRefDocument');
        $RSStock = !empty($LineItemCode) ? $this->ITH_mod->selectStockWhereItemIn([$LineItemCode], $frLoc) : [];
        $AffectedRows = 0;
        foreach ($RSStock as $r) {
            if ($r['SQT'] >= $LineItemQty) {
                $AffectedRows += $this->TRF_mod->updatebyId(['TRFD_LINE' => $LineID]
                    , ['TRFD_ITEMCD' => $LineItemCode
                        , 'TRFD_QTY' => $LineItemQty
                        , 'TRFD_REFFERENCE_DOCNO' => $LineRefDocument
                        , 'TRFD_LAST_UPDATED_BY' => $this->session->userdata('nama')
                        , 'TRFD_UPDATED_DT' => $CurrentDateTime]);
            }
        }
        $response[] = $AffectedRows ? ['cd' => '1', 'msg' => 'Updated'] : ['cd' => '0', 'msg' => 'Could not update'];
        $this->TRF_mod->updateHeaderWhere(['TRFH_DOC' => $doc]
            , ['TRFH_ISSUE_DT' => $docDate, 'TRFH_LOC_FR' => $frLoc, 'TRFH_LOC_TO' => $toLoc]);
        $RSOK = $this->TRF_mod->selectDetailWhere(['TRFD_DOC' => $doc]);
        die(json_encode(['status' => $response, 'data' => $RSOK]));
    }

    public function removeDraft()
    {
        header('Content-Type: application/json');
        $this->checkSession();
        $doc = $this->input->post('doc');
        $lineId = $this->input->post('lineId');
        $respon = [];
        $AffectedRows = $this->TRF_mod->updatebyId(['TRFD_LINE' => $lineId, 'TRFD_DOC' => $doc],
            ['TRFD_DELETED_BY' => $this->session->userdata('nama'), 'TRFD_DELETED_DT' => date('Y-m-d H:i:s')]);
        $respon[] = $AffectedRows ? ['cd' => '1', 'msg' => 'OK'] : ['cd' => '0', 'msg' => 'sorry'];
        die(json_encode(['status' => $respon]));
    }

    public function checkSession()
    {
        $myar = [];
        if ($this->session->userdata('status') != "login") {
            $myar[] = ["cd" => "0", "msg" => "Session is expired please reload page"];
            exit(json_encode(['status' => $myar]));
        }
    }

    public function ApprovalList()
    {
        header('Content-Type: application/json');
        $warehouse = $this->input->get('warehouse');
        $RS = $this->TRFSET_mod->selectDetailWhere(['TRFSET_WH' => $warehouse]);
        die(json_encode(['data' => $RS]));
    }

    public function ApprovalSet()
    {
        header('Content-Type: application/json');
        $CurrentDateTime = date('Y-m-d H:i:s');
        $id = $this->input->post('id');
        $wh = $this->input->post('wh');
        $RS = $this->TRFSET_mod->selectDetailWhere(['TRFSET_APPROVER' => $id, 'TRFSET_WH' => $wh]);
        $respon = [];
        if (empty($RS)) {
            $RSTobeSaved = [
                'TRFSET_WH' => $wh,
                'TRFSET_APPROVER' => $id,
                'TRFSET_CREATED_BY' => $this->session->userdata('nama'),
                'TRFSET_CREATED_DT' => $CurrentDateTime,
            ];

            $AffectedRows = $this->TRFSET_mod->insert($RSTobeSaved);
            if ($AffectedRows) {
                $respon[] = ['cd' => '1', 'msg' => 'Saved'];
            } else {
                $respon[] = ['cd' => '0', 'msg' => 'Could not be saved'];
            }
        } else {
            $respon[] = ['cd' => '0', 'msg' => 'Could not be saved.'];
        }
        $RSCurrentPIC = $this->TRFSET_mod->selectDetailWhere(['TRFSET_WH' => $wh]);
        die(json_encode(['status' => $respon, 'data' => $RSCurrentPIC]));
    }

    public function revoke()
    {
        header('Content-Type: application/json');
        $this->checkSession();
        $id = $this->input->post('id');
        $wh = $this->input->post('wh');
        $CurrentDateTime = date('Y-m-d H:i:s');
        $AffectedRows = $this->TRFSET_mod->updatebyId(['TRFSET_DELETED_BY' => $this->session->userdata('nama'), 'TRFSET_DELETED_DT' => $CurrentDateTime],
            ['TRFSET_LINE' => $id]);
        $respon = [];
        $respon[] = $AffectedRows ? ['cd' => '1', 'msg' => 'OK'] : ['cd' => '1', 'msg' => 'OK'];
        $RSCurrentPIC = $this->TRFSET_mod->selectDetailWhere(['TRFSET_WH' => $wh]);
        die(json_encode(['data' => $RSCurrentPIC, 'status' => $respon]));
    }

    public function conformTransfer()
    {
        header('Content-Type: application/json');
        $this->checkSession();
        $doc = $this->input->post('doc');
        $item = $this->input->post('item');
        $rs = $this->TRF_mod->selectOpenForIDWhereDoc($this->session->userdata('nama'), $doc);
        $ITH_FORM_INC = $ITH_FORM_OUT = '';
        foreach ($rs as $r) {
            $this->TRF_mod->updatebyId([
                'TRFD_DOC' => $doc
                , 'TRFD_ITEMCD' => $r['TRFD_ITEMCD']
                ]
                , ['TRFD_RECEIVE_BY' => $this->session->userdata('nama')
                    , 'TRFD_RECEIVE_DT' => date('Y-m-d H:i:s')]);

            
            if(str_contains($r['TRFH_LOC_TO'], 'SCR')) {
                $ITH_FORM_INC = 'INC-SCR-'.$r['TRFH_LOC_TO'];
                $ITH_FORM_OUT = 'OUT-WH-SCR-'.$r['TRFH_LOC_FR'];
            } else {
                $ITH_FORM_INC = 'TRF-INC';
                $ITH_FORM_OUT = 'TRF-OUT';
            }

            $RsToBeSaved[] = [
                'ITH_ITMCD' => $r['TRFD_ITEMCD'],
                'ITH_DATE' => date('Y-m-d'),
                'ITH_FORM' => $ITH_FORM_INC,
                'ITH_DOC' => $r['TRFH_DOC'],
                'ITH_QTY' => $r['TTLQTY'] * 1,
                'ITH_WH' => $r['TRFH_LOC_TO'],
                'ITH_LUPDT' => date('Y-m-d H:i:s'),
                'ITH_USRID' => $this->session->userdata('nama'),
            ];
            $RsToBeSaved[] = [
                'ITH_ITMCD' => $r['TRFD_ITEMCD'],
                'ITH_DATE' => date('Y-m-d'),
                'ITH_FORM' => $ITH_FORM_OUT,
                'ITH_DOC' => $r['TRFH_DOC'],
                'ITH_QTY' => $r['TTLQTY'] * -1,
                'ITH_WH' => $r['TRFH_LOC_FR'],
                'ITH_LUPDT' => date('Y-m-d H:i:s'),
                'ITH_USRID' => $this->session->userdata('nama'),
            ];
        }
        if (!empty($RsToBeSaved)) {
            $this->ITH_mod->insertb($RsToBeSaved);
            $myar[] = ['cd' => 1, 'msg' => 'OK'];
        } else {
            $myar[] = ['cd' => 0, 'msg' => 'could not be processed'];
        }
        $rs = $this->TRF_mod->selectOpenForID($this->session->userdata('nama'));
        die(json_encode(['data' => $rs, 'status' => $myar]));
    }

    public function conformReject()
    {
        header('Content-Type: application/json');
        $this->checkSession();
        $doc = $this->input->post('doc');
        $item = $this->input->post('item');
        $rs = $this->TRF_mod->selectOpenForIDWhereItem($this->session->userdata('nama'), $item, $doc);
        $AffectedRows = 0;
        foreach ($rs as $r) {
            $AffectedRows = $this->TRF_mod->rejectbyId(['TRFD_DOC' => $doc
                , 'TRFD_ITEMCD' => $r['TRFD_ITEMCD']]
                , [
                    'TRFD_DELETED_BY' => $this->session->userdata('nama')
                    , 'TRFD_DELETED_DT' => date('Y-m-d H:i:s')
                    , 'TRFD_REFFERENCE_DOCNO' => 'REJECT-TRF'
                 ]
            );
        }
        if ($AffectedRows) {
            $myar[] = ['cd' => 1, 'msg' => 'OK'];
        } else {
            $myar[] = ['cd' => 0, 'msg' => 'could not be processed'];
        }
        $rs = $this->TRF_mod->selectOpenForID($this->session->userdata('nama'));
        die(json_encode(['data' => $rs, 'status' => $myar]));
    }
}
