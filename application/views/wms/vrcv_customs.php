<style>
    .screenshot-image {
        width: 150px;
        height: 90px;
        border-radius: 4px;
        border: 2px solid whitesmoke;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.1);
        position: absolute;
        bottom: 5px;
        left: 10px;
        background: white;
        object-fit: contain;
    }

    .display-cover {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 70%;
        margin: 5% auto;
        position: relative;
    }

    video {
        width: 100%;
        background: rgba(0, 0, 0, 0.2);
    }

    .video-options {
        position: absolute;
        left: 20px;
        top: 30px;
    }

    .controls {
        position: absolute;
        right: 20px;
        top: 20px;
        display: flex;
    }

    .controls>button {
        width: 45px;
        height: 45px;
        text-align: center;
        border-radius: 100%;
        margin: 0 6px;
        background: transparent;
    }

    .controls>button:hover svg {
        color: white !important;
    }

    @media (min-width: 300px) and (max-width: 400px) {
        .controls {
            flex-direction: column;
        }

        .controls button {
            margin: 5px 0 !important;
        }
    }

    .controls>button>svg {
        height: 20px;
        width: 18px;
        text-align: center;
        margin: 0 auto;
        padding: 0;
    }

    .controls button:nth-child(1) {
        border: 2px solid #D2002E;
    }

    .controls button:nth-child(1) svg {
        color: #D2002E;
    }

    .controls button:nth-child(2) {
        border: 2px solid #008496;
    }

    .controls button:nth-child(2) svg {
        color: #008496;
    }

    .controls button:nth-child(3) {
        border: 2px solid #00B541;
    }

    .controls button:nth-child(3) svg {
        color: #00B541;
    }

    .controls>button {
        width: 45px;
        height: 45px;
        text-align: center;
        border-radius: 100%;
        margin: 0 6px;
        background: transparent;
    }

    .controls>button:hover svg {
        color: white;
    }
</style>
<div style="padding: 10px">
    <div class="container-fluid">
        <div class="row" id="rcvcustoms_stack0">
            <div class="col-md-12 mb-1 text-center">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="rcvcustoms_mode" onclick="rcvcustoms_mode_eC(this)" id="rcvcustoms_mode0" value="0" checked>
                    <label class="form-check-label" for="rcvcustoms_mode0">Direct & Indirect Material</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="rcvcustoms_mode" onclick="rcvcustoms_mode_eC(this)" id="rcvcustoms_mode1" value="1">
                    <label class="form-check-label" for="rcvcustoms_mode1">Machine & Spare Part</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="rcvcustoms_mode" onclick="rcvcustoms_mode_eC(this)" id="rcvcustoms_mode2" value="2">
                    <label class="form-check-label" for="rcvcustoms_mode2">FG Return</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="rcvcustoms_mode" onclick="rcvcustoms_mode_eC(this)" id="rcvcustoms_mode3" value="3">
                    <label class="form-check-label" for="rcvcustoms_mode3">Material Sample</label>
                </div>
            </div>
        </div>
        <div id="rcvcustoms_div_mode0" class="rcvcustoms_div_mode">
            <div class="row" id="rcvcustoms_stack1">
                <div class="col-md-5 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">DO Number</label>
                        <input type="text" class="form-control" id="rcvcustoms_docnoorigin" ondblclick="rcvcustoms_docnoorigin_eDC()" readonly disabled>
                        <button class="btn btn-primary" id="rcvcustoms_btnmod"><i class="fas fa-search"></i></button>
                    </div>
                </div>
                <div class="col-md-3 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">Doc Type</label>
                        <select id="rcvcustoms_typedoc" class="form-select">
                            <option value="23">BC 2.3</option>
                            <option value="27">BC 2.7</option>
                            <option value="40">BC 4.0</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">Receiving Status</label>
                        <select id="rcvcustoms_zsts" class="form-select">
                        </select>
                    </div>
                </div>
            </div>
            <div class="row" id="rcvcustoms_stack2">
                <div class="col-md-4 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text" style="cursor:pointer" ondblclick="rcvcustoms_noaju_lbl_eCK()">NoAju</label>
                        <input type="text" class="form-control" id="rcvcustoms_noaju" maxlength="26" autocomplete="off" onkeyup="rcvcustoms_noaju_eKeyUp(event)">
                    </div>
                </div>
                <div class="col-md-2 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text" onclick="tesTese()">NoPen</label>
                        <input type="text" class="form-control" id="rcvcustoms_regno" maxlength="8">
                    </div>
                </div>
                <div class="col-md-3 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">Doc. Date</label>
                        <input type="text" class="form-control" id="rcvcustoms_dd">
                    </div>
                </div>
                <div class="col-md-3 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">Rcv. Date</label>
                        <input type="text" class="form-control" id="rcvcustoms_rcvdate">
                    </div>
                </div>
            </div>
            <div class="row" id="rcvcustoms_stack3">
                <div class="col-md-6 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">TPB Type</label>
                        <select id="rcvcustoms_typetpb" class="form-select">
                            <?php foreach ($ltpb_type as $r) {?>
                                <option value="<?=$r['KODE_JENIS_TPB']?>"><?=$r['URAIAN_JENIS_TPB']?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">KPPBC</label>
                        <select id="rcvcustoms_kppbc" class="form-select">
                            <?php
                                $toprint = '';
                                foreach ($officelist as $r) {
                                    $toprint .= '<option value="' . $r['KODE_KANTOR'] . '">' . $r['URAIAN_KANTOR'] . '</option>';
                                }
                                echo $toprint;
                            ?>
                        </select>
                        <label class="input-group-text">...</label>
                    </div>
                </div>
            </div>
            <div class="row" id="rcvcustoms_stack4">
                <div class="col-md-4 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">Total Amount</label>
                        <input type="text" class="form-control" id="rcvcustoms_amount">
                    </div>
                </div>
                <div class="col-md-3 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">Total Qty</label>
                        <input type="text" class="form-control" id="rcvcustoms_qty" readonly disabled>
                    </div>
                </div>
                <div class="col-md-2 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">Net Weight</label>
                        <input type="text" class="form-control" id="rcvcustoms_NW">
                    </div>
                </div>
                <div class="col-md-3 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">Gross Weight</label>
                        <input type="text" class="form-control" id="rcvcustoms_GW">
                    </div>
                </div>
            </div>
            <div class="row" id="rcvcustoms_stack5">
                <div class="col-md-5 mb-1">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Business Group</span>
                        <select class="form-select" id="rcvcustoms_businessgroup" required disabled>
                            <?=$lbg?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">Invoice</label>
                        <input type="text" class="form-control" id="rcvcustoms_invoice" readonly disabled>
                    </div>
                </div>
                <div class="col-md-4 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">Contract</label>
                        <input type="text" class="form-control" id="rcvcustoms_contractnum" readonly disabled>
                        <button class="btn btn-primary" id="rcvcustoms_btn_find_contract" data-bs-toggle="modal" data-bs-target="#RCVCUSTOMS_CONA"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>
            <div class="row" id="rcvcustoms_stack7">
                <div class="col-md-9 mb-1">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Supplier Name</span>
                        <input type="text" class="form-control" id="rcvcustoms_supplier_name" readonly disabled>
                    </div>
                </div>
                <div class="col-md-3 mb-1">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Currency</span>
                        <input type="text" class="form-control" id="rcvcustoms_supplier_currency" readonly disabled>
                    </div>
                </div>
            </div>
            <div class="row" id="rcvcustoms_stack6">
                <div class="col-md-4 mb-1">
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-primary" id="rcvcustoms_save" title="Save"><i class="fas fa-save"></i></button>
                        <button class="btn btn-success" id="rcvcustoms_hscode" title="Update HS Code">Update from xls</button>
                        <div class="btn-group btn-group-sm" role="group">
                            <button id="rcvcustoms_btnTPB_direct" title="TPB Operations" class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">TPB</button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="rcvcustoms_btnmkemasan_eCK(this)"><i class="fa-solid fa-box"></i> Kemasan</a></li>
                                <li><a id="rcvcustoms_btn_posting_direct" class="dropdown-item" href="#" onclick="rcvcustoms_btn_posting_eCK(this)"><i class="fas fa-clone"></i> Posting</a></li>
                                <!-- <li><a id="rcvcustoms_btnAmendModal" class="dropdown-item" href="#" onclick="rcvcustoms_btnAmendModalonClick(this)"><i class="fas fa-file-pen"></i> Changes Submission</a></li> -->
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-1 text-center">                    
                    <button class="btn btn-primary btn-sm" onclick="rcvcustoms_show_docs_modal()">Documents</button>
                </div>
                <div class="col-md-4 mb-1 text-end">
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-info" id="rcvcustoms_info" ><i class="fas fa-circle-info"></i></button>
                        <button class="btn btn-success" id="rcvcustoms_sync" title="Synchronize" onclick="rcvcustoms_sync_eCK()"><i class="fas fa-sync"></i></button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="table-responsive" id="rcvcustoms_divku">
                        <table id="rcvcustoms_tbl" class="table table-sm table-striped table-hover table-bordered caption-top" style="width:100%;cursor:pointer;font-size:75%">
                            <caption id="rcvcustoms_lbltbl">-</caption>
                            <thead class="table-light">
                                <tr class="text-center">
                                    <th>Status</th> <!-- 0 -->
                                    <th>NoUrut</th> <!-- 1 -->
                                    <th class="d-none">PO No</th> <!-- 2 -->                                    
                                    <th>Item Code</th> <!-- 3 -->
                                    <th>Item Name</th> <!-- 4 -->
                                    <th>QTY</th> <!-- 5 -->
                                    <th>UM</th> <!-- 6 -->
                                    <th>Price</th> <!-- 7 -->
                                    <th>Amount</th> <!-- 8 -->
                                    <th>Net Weight per UM</th> <!-- 9 -->
                                    <th class="d-none">WH</th> <!-- 10 -->
                                    <th class="d-none">GRLNO</th> <!-- 11 -->
                                    <th>HS Code</th> <!-- 12 -->
                                    <th title="Bea Masuk">BM</th> <!-- 13 -->
                                    <th>PPN</th> <!-- 14 -->
                                    <th>PPH</th> <!-- 15 -->
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div id="rcvcustoms_div_mode1" class="rcvcustoms_div_mode d-none">
            <div class="row" id="rcvcustoms_stack1_1">
                <div class="col-md-5 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">DO Number</label>
                        <input type="text" class="form-control" id="rcvcustoms_docnoorigin_1">
                        <button class="btn btn-primary" id="rcvcustoms_btnmod_1" data-bs-toggle="modal" data-bs-target="#RCVCUSTOMS_DTLMOD_1"><i class="fas fa-search"></i></button>
                    </div>
                </div>
                <div class="col-md-3 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">Doc Type</label>
                        <select id="rcvcustoms_typedoc_1" class="form-select" onchange="rcvcustoms_typedoc_1_eChange(this)">
                            <option value="23">BC 2.3</option>
                            <option value="27">BC 2.7</option>
                            <option value="40">BC 4.0</option>
                            <option value="262">BC 2.6.2</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">Receiving Status</label>
                        <select id="rcvcustoms_zsts_1" class="form-select">
                        </select>
                    </div>
                </div>
            </div>
            <div class="row" id="rcvcustoms_stack2_1">
                <div class="col-md-6 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">Tax Invoice</label>
                        <input type="text" class="form-control" id="rcvcustoms_tax_invoice" onkeyup="rcvcustoms_tax_invoice_eKeyUp(event)">
                    </div>
                </div>
                <div class="col-md-6 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">NoAju</label>
                        <input type="text" class="form-control" id="rcvcustoms_noaju_1" maxlength="26" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="row" id="rcvcustoms_stack2_1_1">
                <div class="col-md-4 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">NoPen</label>
                        <input type="text" class="form-control" id="rcvcustoms_regno_1" maxlength="8">
                        <button class="btn btn-primary" onclick="rcvcustoms_btnsync_eCK(this)"><i class="fas fa-sync"></i></button>
                    </div>
                </div>
                <div class="col-md-4 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">Doc. Date</label>
                        <input type="text" class="form-control" id="rcvcustoms_dd_1">
                    </div>
                </div>
                <div class="col-md-4 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">Rcv. Date</label>
                        <input type="text" class="form-control" id="rcvcustoms_rcvdate_1">
                    </div>
                </div>
            </div>
            <div class="row" id="rcvcustoms_stack3_1">
                <div class="col-md-6 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">TPB Type</label>
                        <select id="rcvcustoms_typetpb_1" class="form-select">
                            <?php foreach ($ltpb_type as $r) {?>
                                <option value="<?=trim($r['KODE_JENIS_TPB'])?>"><?=$r['URAIAN_JENIS_TPB']?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">KPPBC</label>
                        <select id="rcvcustoms_kppbc_1" class="form-select">
                            <?php
                                $toprint = '';
                                foreach ($officelist as $r) {
                                    $toprint .= '<option value="' . $r['KODE_KANTOR'] . '">' . $r['URAIAN_KANTOR'] . '</option>';
                                }
                                echo $toprint;
                            ?>
                        </select>
                        <label class="input-group-text">...</label>
                    </div>
                </div>
            </div>
            <div class="row" id="rcvcustoms_stack4_1">
                <div class="col-md-4 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">Total Amount</label>
                        <input type="text" class="form-control" id="rcvcustoms_amount_1" readonly>
                    </div>
                </div>
                <div class="col-md-4 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">Net Weight TTL</label>
                        <input type="text" class="form-control" id="rcvcustoms_NW_1">
                    </div>
                </div>
                <div class="col-md-4 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">Gross Weight TTL</label>
                        <input type="text" class="form-control" id="rcvcustoms_GW_1">
                    </div>
                </div>
            </div>
            <div class="row" id="rcvcustoms_stack5_1">
                <div class="col-md-4 mb-1">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Business Group</span>
                        <select class="form-select" id="rcvcustoms_businessgroup_1" required>
                            <?=$lbg?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">Supplier</label>
                        <input type="text" class="form-control" id="rcvcustoms_supplier_1" readonly disabled>
                        <button class="btn btn-primary" id="rcvcustoms_btn_find_supplier_1" onclick="rcvcustoms_th_sup_eC(this)"><i class="fas fa-search"></i></button>
                    </div>
                </div>
                <div class="col-md-4 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">Shipper</label>
                        <input type="text" class="form-control" id="rcvcustoms_shipper_1" readonly disabled>
                        <button class="btn btn-primary" id="rcvcustoms_btn_find_shipper_1" onclick="rcvcustoms_btn_find_shipper_1_onClick(this)"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>
            <div class="row" id="rcvcustoms_stack5_1_1">
                <div class="col-md-2 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">Invoice</label>
                        <input type="text" class="form-control" id="rcvcustoms_invoicenum_1">
                    </div>
                </div>
                <div class="col-md-3 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">Contract</label>
                        <input type="text" class="form-control" id="rcvcustoms_contractnum_1" onkeyup="rcvcustoms_contractnum_1_eKeyUp(event)">
                    </div>
                </div>
                <div class="col-md-3 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">Contract Date</label>
                        <input type="text" class="form-control" id="rcvcustoms_contractdate_1" readonly>
                    </div>
                </div>
                <div class="col-md-4 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">Contract Due Date</label>
                        <input type="text" class="form-control" id="rcvcustoms_contractduedate_1" readonly>
                    </div>
                </div>
            </div>
            <div class="row" id="rcvcustoms_stack6_1">
                <div class="col-md-6 mb-1">
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-primary" id="rcvcustoms_new_1" onclick="rcvcustoms_new_1_eCK()" title="New"><i class="fas fa-file"></i></button>
                        <button class="btn btn-outline-primary" id="rcvcustoms_save_1" onclick="rcvcustoms_save_1_eCK(this)" title="Save"><i class="fas fa-save"></i></button>
                        <div class="btn-group btn-group-sm" role="group">
                            <button id="rcvcustoms_btnTPB" title="TPB Operations" class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">TPB</button>
                            <ul class="dropdown-menu">
                                <li><a id="rcvcustoms_btn_mkemasan" class="dropdown-item" href="#" onclick="rcvcustoms_btnmkemasan_eCK(this)"><i class="fa-solid fa-box"></i> Kemasan</a></li>
                                <li><a id="rcvcustoms_btn_posting" class="dropdown-item" href="#" onclick="rcvcustoms_btn_posting_eCK(this)"><i class="fas fa-clone"></i> Posting</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-1 text-end">
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-primary" id="rcvcustoms_plus_1" title="Add from PO" onclick="rcvcustoms_plus_PO_1_eC()">Add from PO</button>
                        <button class="btn btn-outline-primary" id="rcvcustoms_plus_1" title="Add" onclick="rcvcustoms_plus_1_eC()"><i class="fas fa-plus"></i></button>
                        <button class="btn btn-outline-warning" id="rcvcustoms_minus_1" title="Remove" onclick="rcvcustoms_minusrow('rcvcustoms_tbl_1')"><i class="fas fa-minus"></i></button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="table-responsive" id="rcvcustoms_divku_1">
                        <table id="rcvcustoms_tbl_1" class="table table-sm table-hover table-bordered" style="width:100%;font-size:75%">
                            <thead class="table-light">
                                <tr>
                                    <th class="d-none">GRLNO</th> <!-- 0 -->
                                    <th>NoUrut</th> <!-- 1 -->
                                    <th>PO No</th> <!-- 2 -->
                                    <th>Asset Status</th> <!-- 3 -->
                                    <th>Item Code</th> <!-- 4 -->
                                    <th>Item Name</th> <!-- 5 -->
                                    <th class="text-end">QTY</th> <!-- 6 -->
                                    <th title="Unit Measurement">UM</th> <!-- 7 -->
                                    <th class="text-end">Price</th> <!-- 8 -->
                                    <th class="text-end">Amount</th> <!-- 9 -->
                                    <th class="text-end">Net Weight</th> <!-- 10 -->
                                    <th class="text-end">Gross Weight</th> <!-- 11 -->
                                    <th class="text-center">HS Code</th> <!-- 12 -->
                                    <th class="text-end" title="Bea Masuk">BM</th> <!-- 13 -->
                                    <th class="text-end">PPN</th> <!-- 14 -->
                                    <th class="text-end">PPH</th> <!-- 15 -->
                                    <th class="text-center">to Location</th> <!-- 16 -->
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div id="rcvcustoms_div_mode2" class="rcvcustoms_div_mode d-none">
            <div class="row" id="rcvcustoms_fg_stack0">
                <div class="col-md-5 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">DO Number</label>
                        <input type="text" class="form-control" id="rcvcustoms_fg_docnoorigin" readonly disabled>
                        <button class="btn btn-primary" id="rcvcustoms_fg_btnmod"><i class="fas fa-search"></i></button>
                    </div>
                </div>
                <div class="col-md-3 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">Doc Type</label>
                        <select id="rcvcustoms_fg_typedoc" class="form-select">
                            <option value="23">BC 2.3</option>
                            <option value="27">BC 2.7</option>
                            <option value="40">BC 4.0</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">Receiving Status</label>
                        <select id="rcvcustoms_fg_zsts" class="form-select">
                        </select>
                    </div>
                </div>
            </div>
            <div class="row" id="rcvcustoms_fg_stack1">
                <div class="col-md-4 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">NoAju</label>
                        <input type="text" class="form-control" id="rcvcustoms_fg_noaju" maxlength="26">
                    </div>
                </div>
                <div class="col-md-2 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">NoPen</label>
                        <input type="text" class="form-control" id="rcvcustoms_fg_regno" maxlength="6">
                    </div>
                </div>
                <div class="col-md-3 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">Doc. Date</label>
                        <input type="text" class="form-control" id="rcvcustoms_fg_dd">
                    </div>
                </div>
                <div class="col-md-3 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">Rcv. Date</label>
                        <input type="text" class="form-control" id="rcvcustoms_fg_rcvdate">
                    </div>
                </div>
            </div>
            <div class="row" id="rcvcustoms_fg_stack2">
                <div class="col-md-6 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">TPB Type</label>
                        <select id="rcvcustoms_fg_typetpb" class="form-select">
                            <?php foreach ($ltpb_type as $r) {?>
                                <option value="<?=$r['KODE_JENIS_TPB']?>"><?=$r['URAIAN_JENIS_TPB']?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">KPPBC</label>
                        <select id="rcvcustoms_fg_kppbc" class="form-select">
                            <?php
                                $toprint = '';
                                foreach ($officelist as $r) {
                                    $toprint .= '<option value="' . $r['KODE_KANTOR'] . '">' . $r['URAIAN_KANTOR'] . '</option>';
                                }
                                echo $toprint;
                            ?>
                        </select>
                        <label class="input-group-text">...</label>
                    </div>
                </div>
            </div>
            <div class="row" id="rcvcustoms_fg_stack3">
                <div class="col-md-4 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">Total Amount</label>
                        <input type="text" class="form-control" id="rcvcustoms_fg_amount">
                    </div>
                </div>
                <div class="col-md-4 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">Net Weight</label>
                        <input type="text" class="form-control" id="rcvcustoms_fg_NW">
                    </div>
                </div>
                <div class="col-md-4 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">Gross Weight</label>
                        <input type="text" class="form-control" id="rcvcustoms_fg_GW">
                    </div>
                </div>
            </div>
            <div class="row" id="rcvcustoms_fg_stack5">
                <div class="col-md-4 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">Contract</label>
                        <input type="text" class="form-control" id="rcvcustoms_fg_contractnum" list="rcvcustoms_fg_contractnum_dl">
                        <datalist id="rcvcustoms_fg_contractnum_dl">
                        </datalist>
                    </div>
                </div>
                <div class="col-md-4 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">Invoice</label>
                        <input type="text" class="form-control" id="rcvcustoms_fg_invoice">
                    </div>
                </div>
                <div class="col-md-4 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">Return From</label>
                        <input type="text" class="form-control" id="rcvcustoms_fg_supplier_2" readonly>
                        <input type="hidden" id="rcvcustoms_fg_supcd" readonly>
                    </div>
                </div>
            </div>
            <div class="row" id="rcvcustoms_fg_stack6">
                <div class="col-md-9 mb-1">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Customer Name</span>
                        <input type="text" class="form-control" id="rcvcustoms_fg_customer_name" readonly disabled>
                    </div>
                </div>
                <div class="col-md-3 mb-1">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Currency</span>
                        <input type="text" class="form-control" id="rcvcustoms_fg_customer_currency" readonly disabled>
                    </div>
                </div>
                <input type="hidden" id="rcvcustoms_fg_bisgrup">
            </div>
            <div class="row" id="rcvcustoms_fg_stack4">
                <div class="col-md-6 mb-3">
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-primary" id="rcvcustoms_fg_save" title="Save"><i class="fas fa-save"></i></button>
                        <button class="btn btn-success" id="rcvcustoms_fg_hscode" title="Update HS Code" data-bs-toggle="modal" data-bs-target="#RCVCUSTOMS_IMPORTDATA">Update from xls</button>
                        <button class="btn btn-outline-success" id="rcvcustoms_fg_save_as_xls" onclick="rcvcustoms_fg_save_as_xls_eCK()" title="Save as excel"><i class="fas fa-file-excel"></i></button>
                    </div>
                </div>
                <div class="col-md-6 mb-3 text-end">
                    <span class="badge bg-info" id="rcvcustoms_fg_lbltbl"></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="table-responsive" id="rcvcustoms_fg_divku">
                        <table id="rcvcustoms_fg_tbl" class="table table-sm table-striped table-hover table-bordered" style="width:100%;cursor:pointer;font-size:75%">
                            <thead class="table-light">
                                <tr class="text-center">
                                    <th>Status</th> <!-- 0 -->
                                    <th>NoUrut</th> <!-- 1 -->
                                    <th class="d-none">PO No</th> <!-- 2 -->
                                    <th>Item Code</th> <!-- 3 -->
                                    <th>Item Name</th> <!-- 4 -->
                                    <th>QTY</th> <!-- 5 -->
                                    <th>UM</th> <!-- 6 -->
                                    <th>Price</th> <!-- 7 -->
                                    <th>Amount</th> <!-- 8 -->
                                    <th>Net Weight per UM</th> <!-- 9 -->
                                    <th class="d-none">WH</th> <!-- 10 -->
                                    <th class="d-none">GRLNO</th> <!-- 11 -->
                                    <th>HS Code</th> <!-- 12 -->
                                    <th title="Bea Masuk">BM</th> <!-- 13 -->
                                    <th>PPN</th> <!-- 14 -->
                                    <th>PPH</th> <!-- 15 -->
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div id="rcvcustoms_div_mode3" class="rcvcustoms_div_mode d-none">
            <div class="row" id="rcvcustoms_stack1_2">
                <div class="col-md-5 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">DO Number</label>
                        <input type="text" class="form-control" id="rcvcustoms_docnoorigin_2">
                        <button class="btn btn-primary" id="rcvcustoms_btnmod_2" data-bs-toggle="modal" data-bs-target="#RCVCUSTOMS_DTLMOD_2"><i class="fas fa-search"></i></button>
                    </div>
                </div>
                <div class="col-md-3 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">Doc Type</label>
                        <select id="rcvcustoms_typedoc_2" class="form-select">
                            <option value="23">BC 2.3</option>
                            <option value="27">BC 2.7</option>
                            <option value="40">BC 4.0</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">Receiving Status</label>
                        <select id="rcvcustoms_zsts_2" class="form-select">
                        </select>
                    </div>
                </div>
            </div>
            <div class="row" id="rcvcustoms_stack2_2">
                <div class="col-md-4 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">NoAju</label>
                        <input type="text" class="form-control" id="rcvcustoms_noaju_2" maxlength="26" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-2 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">NoPen</label>
                        <input type="text" class="form-control" id="rcvcustoms_regno_2" maxlength="8">
                    </div>
                </div>
                <div class="col-md-3 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">Doc. Date</label>
                        <input type="text" class="form-control" id="rcvcustoms_dd_2">
                    </div>
                </div>
                <div class="col-md-3 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">Rcv. Date</label>
                        <input type="text" class="form-control" id="rcvcustoms_rcvdate_2">
                    </div>
                </div>
            </div>
            <div class="row" id="rcvcustoms_stack3_2">
                <div class="col-md-6 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">TPB Type</label>
                        <select id="rcvcustoms_typetpb_2" class="form-select">
                            <?php foreach ($ltpb_type as $r) {?>
                                <option value="<?=$r['KODE_JENIS_TPB']?>"><?=$r['URAIAN_JENIS_TPB']?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">KPPBC</label>
                        <select id="rcvcustoms_kppbc_2" class="form-select">
                            <?php
                                $toprint = '';
                                foreach ($officelist as $r) {
                                    $toprint .= '<option value="' . $r['KODE_KANTOR'] . '">' . $r['URAIAN_KANTOR'] . '</option>';
                                }
                                echo $toprint;
                            ?>
                        </select>
                        <label class="input-group-text">...</label>
                    </div>
                </div>
            </div>
            <div class="row" id="rcvcustoms_stack4_2">
                <div class="col-md-4 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">Total Amount</label>
                        <input type="text" class="form-control" id="rcvcustoms_amount_2" readonly>
                    </div>
                </div>
                <div class="col-md-4 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">Net Weight</label>
                        <input type="text" class="form-control" id="rcvcustoms_NW_2">
                    </div>
                </div>
                <div class="col-md-4 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">Gross Weight</label>
                        <input type="text" class="form-control" id="rcvcustoms_GW_2">
                    </div>
                </div>
            </div>
            <div class="row" id="rcvcustoms_stack5_2">
                <div class="col-md-6 mb-1">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Business Group</span>
                        <select class="form-select" id="rcvcustoms_businessgroup_2" required>
                            <?=$lbg?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">Supplier</label>
                        <input type="text" class="form-control" id="rcvcustoms_supplier_2" readonly>
                        <button class="btn btn-primary" id="rcvcustoms_btn_find_supplier_2" onclick="rcvcustoms_th_sup_eC(this)"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>
            <div class="row" id="rcvcustoms_stack5_1_2">
                <div class="col-md-6 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">Invoice</label>
                        <input type="text" class="form-control" id="rcvcustoms_invoicenum_2">
                    </div>
                </div>
                <div class="col-md-6 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text">Warehouse</span>
                        <select class="form-select" id="rcvcustoms_cmb_locTo">
                            <option value="-">-</option>
                            <?=$llocto?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row" id="rcvcustoms_stack6_2">
                <div class="col-md-6 mb-1">
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-primary" id="rcvcustoms_new_2" onclick="rcvcustoms_new_2_eCK()" title="New"><i class="fas fa-file"></i></button>
                        <button class="btn btn-outline-primary" id="rcvcustoms_save_2" onclick="rcvcustoms_save_2_eCK()" title="Save"><i class="fas fa-save"></i></button>
                        <div class="btn-group btn-group-sm" role="group">
                            <button id="rcvcustoms_btnTPB_2" title="TPB Operations" class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">TPB</button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="rcvcustoms_btnmkemasan_eCK(this)"><i class="fa-solid fa-box"></i> Kemasan</a></li>
                                <li><a id="rcvcustoms_btn_posting_2" class="dropdown-item" href="#" onclick="rcvcustoms_btn_posting_eCK(this)"><i class="fas fa-clone"></i> Posting</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-1 text-end">
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-primary" id="rcvcustoms_plus_2" title="Add" onclick="rcvcustoms_plus_2_eC()"><i class="fas fa-plus"></i></button>
                        <button class="btn btn-outline-warning" id="rcvcustoms_minus_2" title="Remove" onclick="rcvcustoms_minusrow('rcvcustoms_tbl_2')"><i class="fas fa-minus"></i></button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="table-responsive" id="rcvcustoms_divku_2">
                        <table id="rcvcustoms_tbl_2" class="table table-sm table-hover table-bordered" style="width:100%;font-size:75%">
                            <thead class="table-light">
                                <tr>
                                    <th class="d-none">GRLNO</th> <!-- 0 -->
                                    <th>NoUrut</th> <!-- 1 -->
                                    <th>PO No</th> <!-- 2 -->
                                    <th>Item Code</th> <!-- 3 -->
                                    <th>Item Name</th> <!-- 4 -->
                                    <th class="text-end">QTY</th> <!-- 5 -->
                                    <th title="Unit Measurement">UM</th> <!-- 6 -->
                                    <th class="text-end">Price</th> <!-- 7 -->
                                    <th class="text-end">Amount</th> <!-- 8 -->
                                    <th class="text-center">HS Code</th> <!-- 9 -->
                                    <th class="text-end" title="Bea Masuk">BM</th> <!-- 10 -->
                                    <th class="text-end">PPN</th> <!-- 11 -->
                                    <th class="text-end">PPH</th> <!-- 12 -->
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="RCVCUSTOMS_DTLMOD">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">MEGA's DO List</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Search</label>
                            <input type="text" class="form-control" id="rcvcustoms_txt_search">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <select id="rcvcustoms_monthfilter" class="form-select">
                                <option value="01">January</option>
                                <option value="02">February</option>
                                <option value="03">March</option>
                                <option value="04">April</option>
                                <option value="05">May</option>
                                <option value="06">June</option>
                                <option value="07">July</option>
                                <option value="08">August</option>
                                <option value="09">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                            <div class="input-group-text">
                                <input type="checkbox" class="form-check-input" id="rcvcustoms_ck">
                            </div>
                            <input type="number" class="form-control" id="rcvcustoms_year" maxlength="4">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Supplier</label>
                            <select id="rcvcustoms_supfilter" class="form-select">
                                <?=$lsupplier?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Date</label>
                            <input type="text" class="form-control" id="rcvcustoms_datefilter" readonly>
                            <button class="btn btn-secondary" id="rcvcustoms_btn_filterdate" onclick="rcvcustoms_btn_filterdate_e_click()"><i class="fas fa-backspace"></i> </button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col text-center">
                        <div class="form-check-inline">
                            <label class="form-check-label">
                                Search by
                            </label>
                        </div>
                        <div class="form-check-inline">
                            <input type="radio" id="rcvcustoms_rad_do" class="form-check-input" name="optradio" value="do" checked>
                            <label class="form-check-label" for="rcvcustoms_rad_do">
                                DO No
                            </label>
                        </div>
                        <div class="form-check-inline">
                            <input type="radio" id="rcvcustoms_rad_item" class="form-check-input" name="optradio" value="item">
                            <label class="form-check-label" for="rcvcustoms_rad_item">
                                Item Code
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col text-end mb-1">
                        <span class="badge bg-info" id="lblinfo_rcvcustoms_tbldono"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" id="rcvcustoms_divku_search">
                        <div class="table-responsive">
                            <table id="rcvcustoms_tbldono" class="table table-hover table-sm table-bordered" style="width:100%;cursor:pointer;font-size:75%">
                                <thead class="table-light">
                                    <tr>
                                        <th>DO No</th>
                                        <th>Date</th>
                                        <th>Supplier</th>
                                        <th class="text-end">Status</th>
                                        <th class="text-center">HSCODE</th>
                                        <th class="text-end">BM</th>
                                        <th class="text-end">PPN</th>
                                        <th class="text-end">PPH</th>
                                        <th>Business</th>
                                        <th>Invoice</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="RCVCUSTOMS_ModalAmend">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Changes Submission</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link active" id="nav-input-tab" data-bs-toggle="tab" data-bs-target="#nav-input" type="button" role="tab">Submission</button>
                        <button class="nav-link" id="nav-history-tab" data-bs-toggle="tab" data-bs-target="#nav-history" type="button" role="tab">History</button>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-input" role="tabpanel" aria-labelledby="nav-input-tab" tabindex="0">
                        <div class="container-fluid mt-2 border-start border-bottom rounded-start">
                            <div class="row">
                                <div class="col-md-6 mb-1">
                                    <label class="form-label">Delivery Order Code <span class="badge bg-secondary">OLD</span></label>
                                    <div class="input-group input-group-sm mb-1">
                                        <input type="text" class="form-control" id="rcvcustoms_ModalTxtDO" readonly disabled>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-1">
                                    <label class="form-label">Delivery Order Code <span class="badge bg-info">New</span></label>
                                    <div class="input-group input-group-sm mb-1">
                                        <input type="text" class="form-control" id="rcvcustoms_ModalTxtDONew" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-1">
                                    <label class="form-label">Nomor Pendaftaran <span class="badge bg-secondary">OLD</span></label>
                                    <div class="input-group input-group-sm mb-1">
                                        <input type="text" class="form-control" id="rcvcustoms_ModalTxtNopen" readonly disabled>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-1">
                                    <label class="form-label">Nomor Pendaftaran <span class="badge bg-info">New</span></label>
                                    <div class="input-group input-group-sm mb-1">
                                        <input type="text" class="form-control" id="rcvcustoms_ModalTxtNopenNew" onclick="this.select()">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-1">
                                    <label class="form-label">Tanggal Pendaftaran <span class="badge bg-secondary">OLD</span></label>
                                    <div class="input-group input-group-sm mb-1">
                                        <input type="text" class="form-control" id="rcvcustoms_ModalTxtTglpen" readonly disabled>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-1">
                                    <label class="form-label">Tanggal Pendaftaran <span class="badge bg-info">New</span></label>
                                    <div class="input-group input-group-sm mb-1">
                                        <input type="text" class="form-control" id="rcvcustoms_ModalTxtTglpenNew">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-history" role="tabpanel" aria-labelledby="nav-history-tab" tabindex="1">
                        <div class="container-fluid mt-2 border-start border-bottom rounded-start">
                            <div class="row">
                                <div class="col">
                                    <div class="table-responsive" id="coaReportTabelContainer">
                                        <table id="coaReportTabel" class="table table-sm table-striped table-bordered table-hover">
                                            <thead class="table-light align-middle">
                                                <tr class="text-center">
                                                    <th >Date</th>
                                                    <th >Document</th>
                                                    <th >User</th>
                                                    <th >Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-sm">Submit</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="RCVCUSTOMS_DTLMOD_1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">DO List</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Search</label>
                            <select id="rcvcustoms_searchfilter_1" class="form-select" onchange="document.getElementById('rcvcustoms_txt_search_1').focus()">
                                <option value="do">DO Number</option>
                                <option value="in">Item Name</option>
                                <option value="po">PO Number</option>
                            </select>
                            <input type="text" class="form-control" id="rcvcustoms_txt_search_1" onkeypress="rcvcustoms_txt_search_1_eKP(event)">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <select id="rcvcustoms_monthfilter_1" class="form-select" onchange="document.getElementById('rcvcustoms_year_1').focus()">
                                <option value="01">January</option>
                                <option value="02">February</option>
                                <option value="03">March</option>
                                <option value="04">April</option>
                                <option value="05">May</option>
                                <option value="06">June</option>
                                <option value="07">July</option>
                                <option value="08">August</option>
                                <option value="09">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                            <div class="input-group-text">
                                <input type="checkbox" class="form-check-input" checked id="rcvcustoms_ck_1">
                            </div>
                            <input type="number" class="form-control" id="rcvcustoms_year_1" maxlength="4">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col" id="rcvcustoms_tbldono_1_div">
                        <table id="rcvcustoms_tbldono_1" class="table table-hover table-sm table-bordered" style="width:100%;font-size:75%">
                            <thead class="table-light">
                                <tr>
                                    <th>DO No</th>
                                    <th>Date</th>
                                    <th>Supplier</th>
                                    <th class="text-center">HSCODE</th>
                                    <th class="text-end">BM</th>
                                    <th class="text-end">PPN</th>
                                    <th class="text-end">PPH</th>
                                    <th>Business</th>
                                    <th>Invoice</th>
                                    <th>Nomor Pendaftaran</th>
                                    <th>Jenis Dokumen</th>
                                    <th>Tax Invoice</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="RCVCUSTOMS_DTLMOD_2">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">DO List</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Search</label>
                            <select id="rcvcustoms_searchfilter_2" class="form-select" onchange="document.getElementById('rcvcustoms_txt_search_2').focus()">
                                <option value="do">DO Number</option>
                                <option value="in">Item Name</option>
                            </select>
                            <input type="text" class="form-control" id="rcvcustoms_txt_search_2" onkeypress="rcvcustoms_txt_search_2_eKP(event)">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <select id="rcvcustoms_monthfilter_2" class="form-select" onchange="document.getElementById('rcvcustoms_year_2').focus()">
                                <option value="01">January</option>
                                <option value="02">February</option>
                                <option value="03">March</option>
                                <option value="04">April</option>
                                <option value="05">May</option>
                                <option value="06">June</option>
                                <option value="07">July</option>
                                <option value="08">August</option>
                                <option value="09">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                            <div class="input-group-text">
                                <input type="checkbox" class="form-check-input" checked id="rcvcustoms_ck_2">
                            </div>
                            <input type="number" class="form-control" id="rcvcustoms_year_2" maxlength="4">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col" id="rcvcustoms_tbldono_2_div">
                        <table id="rcvcustoms_tbldono_2" class="table table-hover table-sm table-bordered" style="width:100%;font-size:75%">
                            <thead class="table-light">
                                <tr>
                                    <th>DO No</th>
                                    <th>Date</th>
                                    <th>Supplier</th>
                                    <th class="text-center">HSCODE</th>
                                    <th class="text-end">BM</th>
                                    <th class="text-end">PPN</th>
                                    <th class="text-end">PPH</th>
                                    <th>Business</th>
                                    <th>Invoice</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="RCVCUSTOMS_PROGRESS">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title"><i class="fas fa-info text-info"></i></h4>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-1 text-center">
                        Please wait ...
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-1 text-center">
                        <i class="fas fa-spinner fa-spin fa-7x text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="RCVCUSTOMS_IMPORTDATA">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Updating from xls</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-1">
                        <div class="input-group">
                            <button title="Download a Template File (*.xls File)" id="rcvcustoms_btn_download" class="btn btn-outline-success btn-sm"><i class="fas fa-file-download"></i></button>
                            <input type="file" id="rcvcustoms_xlf_new" class="form-control">
                            <button id="rcvcustoms_btn_startimport" class="btn btn-primary btn-sm">Start Importing</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-1">
                        <div class="progress">
                            <div id="rcvcustoms_lblsaveprogress" class="progress-bar progress-bar-success progress-bar-animated progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                                <span class="sr-only">0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="RCVCUSTOMS_CONA">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Document List</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div class="input-group input-group-sm mb-1">
                            <span class="input-group-text">Search</span>
                            <input type="text" class="form-control" id="rcvcustoms_txtsearchPK" onkeypress="rcvcustoms_txtsearchPK_eKP(event)" maxlength="15" required placeholder="...">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col text-end mb-1">
                        <span class="badge bg-info" id="rcvcustoms_tblbox_lblinfo"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="table-responsive" id="rcvcustoms_tblbox_div">
                            <table id="rcvcustoms_tblbox" class="table table-sm table-striped table-bordered table-hover" style="width:100%">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">Number</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="RCVCUSTOMS_SUPPLIER">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Supplier List</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div class="input-group input-group-sm mb-1">
                            <span class="input-group-text">Search</span>
                            <input type="text" class="form-control" id="rcvcustoms_txtsearchSUP" onkeypress="rcvcustoms_txtsearchSUP_eKP(event)" maxlength="15" required placeholder="...">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col text-end mb-1">
                        <span class="badge bg-info" id="rcvcustoms_tblsup_lblinfo"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="table-responsive" id="rcvcustoms_tblsup_div">
                            <table id="rcvcustoms_tblsup" class="table table-sm table-striped table-bordered table-hover" style="width:100%">
                                <thead class="table-light">
                                    <tr>
                                        <th>Code</th>
                                        <th>Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="RCVCUSTOMS_SHIPPER">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Shipper List</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div class="input-group input-group-sm mb-1">
                            <span class="input-group-text">Search</span>
                            <input type="text" class="form-control" id="rcvcustomsSearchShipper" onkeypress="rcvcustomsSearchShipperOnKeyPress(event)" maxlength="15" required placeholder="...">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="table-responsive" id="rcvcustomsTblShipperDiv">
                            <table id="rcvcustomsTblShipper" class="table table-sm table-striped table-bordered table-hover" style="width:100%">
                                <thead class="table-light">
                                    <tr>
                                        <th>Code</th>
                                        <th>Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="RCVCUSTOMS_INTERNALITEM">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Item List</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div class="input-group input-group-sm mb-1">
                            <span class="input-group-text">Search</span>
                            <select id="rcvcustoms_NALITM_searchby" class="form-select" onchange="rcvcustoms_NALITM_searchby_eCh()">
                                <option value="ic">Internal Code</option>
                                <option value="cc">Customer Code</option>
                                <option value="in">Item Name</option>
                            </select>
                            <input type="text" class="form-control" id="rcvcustoms_txtsearchNALITM" onkeypress="rcvcustoms_txtsearchNALITM_eKP(event)" maxlength="40" required placeholder="...">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="table-responsive" id="rcvcustoms_tblNALITM_div">
                            <table id="rcvcustoms_tblNALITM" class="table table-sm table-striped table-bordered table-hover" style="width:100%">
                                <thead class="table-light">
                                    <tr>
                                        <th>Internal Code</th>
                                        <th>Customer Code</th>
                                        <th>Item Name</th>
                                        <th>Unit Measurement</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- fg return modal -->
<div class="modal fade" id="rcvcustoms_fg_DTLMOD">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Document List</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Search by</label>
                            <select id="rcvcustoms_fg_searchby" class="form-select" onchange="document.getElementById('rcvcustoms_fg_txt_search').focus()">
                                <option value="ra">RA Number</option>
                                <option value="do">DO Number</option>
                            </select>
                            <input type="text" class="form-control" id="rcvcustoms_fg_txt_search">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <select id="rcvcustoms_fg_monthfilter" class="form-select">
                                <option value="01">January</option>
                                <option value="02">February</option>
                                <option value="03">March</option>
                                <option value="04">April</option>
                                <option value="05">May</option>
                                <option value="06">June</option>
                                <option value="07">July</option>
                                <option value="08">August</option>
                                <option value="09">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                            <div class="input-group-text">
                                <input type="checkbox" class="form-check-input" checked id="rcvcustoms_fg_ck">
                            </div>
                            <input type="number" class="form-control" id="rcvcustoms_fg_year" maxlength="4">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Business</label>
                            <select id="rcvcustoms_fg_supfilter" class="form-select">
                                <?=$lbg?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Date</label>
                            <input type="text" class="form-control" id="rcvcustoms_fg_datefilter" readonly>
                            <button class="btn btn-secondary" id="rcvcustoms_fg_btn_filterdate" onclick="rcvcustoms_fg_btn_filterdate_e_click()"><i class="fas fa-backspace"></i> </button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col text-end mb-1">
                        <span class="badge bg-info" id="lblinfo_rcvcustoms_fg_tbldono"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col" id="rcvcustoms_fg_divku_search">
                        <table id="rcvcustoms_fg_tbldono" class="table table-hover table-sm table-bordered" style="width:100%;font-size:75%">
                            <thead class="table-light">
                                <tr>
                                    <th>RA Number</th>
                                    <th>Date</th>
                                    <th>Business</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">HSCODE</th>
                                    <th class="text-end">BM</th>
                                    <th class="text-end">PPN</th>
                                    <th class="text-end">PPH</th>
                                    <th class="d-none">BISGRUP</th>
                                    <th>Customer</th>
                                    <th>DO</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="rcvcustoms_fg_PROGRESS">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title"><i class="fas fa-info text-info"></i></h4>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-1 text-center">
                        Please wait ...
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-1 text-center">
                        <i class="fas fa-spinner fa-spin fa-7x text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="rcvcustoms_POBAL_Mod">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">PO List</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div class="input-group input-group-sm mb-1">
                            <span class="input-group-text">PO No</span>
                            <input type="text" class="form-control" id="rcvcustoms_po_txtsearch" onkeypress="rcvcustoms_po_txtsearch_eKP(event)" maxlength="44" required placeholder="...">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="table-responsive" id="rcvcustoms_po_tbl_div">
                            <table id="rcvcustoms_po_tbl" class="table table-sm table-striped table-bordered table-hover" style="width:100%">
                                <thead class="table-light">
                                    <tr>
                                        <th rowspan="2" class="align-middle">Document Number</th>
                                        <th rowspan="2" class="align-middle">Supplier</th>
                                        <th colspan="2" class="text-center">Date</th>
                                        <th rowspan="2" class="align-middle">Item Code</th>
                                        <th rowspan="2" class="align-middle">Item Name</th>
                                        <th colspan="2" class="align-middle text-center">Qty</th>
                                        <th rowspan="2" class="align-middle text-center">Price</th>
                                        <th rowspan="2" class="align-middle text-center d-none">SUPPLIERCODE</th>
                                        <th rowspan="2" class="align-middle text-center d-none">stkuom</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">Required</th>
                                        <th class="text-center">Delivery</th>
                                        <th class="text-center">Required</th>
                                        <th class="text-center">Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-sm" data-bs-dismiss="modal" id="rcvcustoms_po_btnuse" onclick="rcvcustoms_po_btnuse_eCK()">Use</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="rcvcustoms_KEMASAN_Mod">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Kemasan</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div class="input-group input-group-sm mb-1">
                            <span class="input-group-text">Nomor</span>
                            <input type="text" class="form-control" id="rcvcustoms_pkg_nomor" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="input-group input-group-sm mb-1">
                            <span class="input-group-text">Jumlah</span>
                            <input type="text" class="form-control" id="rcvcustoms_pkg_jumlah" maxlength="3" required>
                            <select class="form-select" id="rcvcustoms_pkg_kodejenis" required onchange="document.getElementById('rcvcustoms_pkg_btnuse').focus()">
                                <?=$lkemasan?>
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <button type="button" class="btn btn-primary btn-sm" id="rcvcustoms_pkg_btnuse" onclick="rcvcustoms_pkg_btnuse_eCK()"><i class="fas fa-plus"></i> </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="table-responsive" id="rcvcustoms_pkg_tbl_div">
                            <table id="rcvcustoms_pkg_tbl" class="table table-sm table-striped table-bordered table-hover" style="width:100%">
                                <thead class="table-light">
                                    <tr>
                                        <th class="d-none">idrow</th>
                                        <th class="text-end">Jumlah</th>
                                        <th>Kode</th>
                                        <th>Uraian</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="rcvcustoms_QR_Mod">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">QR Scanner</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-1" id="rcvcustoms_reader">

                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Result</label>
                            <textarea class="form-control" id="rcvcustoms_reader_ta" rows="3"> </textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-sm" data-bs-dismiss="modal" id="rcvcustoms_qr_btnuse" onclick="rcvcustoms_qr_btnuse_eCK()">Use</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="rcvcustoms_TESE">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Read from Image</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div class="display-cover">
                            <video autoplay></video>
                            <canvas class="d-none"></canvas>
                        </div>
                        <img id="rcvcustoms_img" class="screenshot-image" alt="">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col">
                            <div class="input-group input-group-sm mb-1">
                                <label class="input-group-text">Select camera</label>
                                <select id="rcvcustoms_camlist" class="form-select">
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-primary btn-sm" id="rcvcustoms_qr_btntry" onclick="rcvcustoms_qr_btntry_eCK()">try</button>
                                <button type="button" class="btn btn-primary btn-sm" id="rcvcustoms_qr_btntake" onclick="rcvcustoms_qr_btntake_eCK()">take</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="RCVCUSTOMS_LOCATION">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Location</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group input-group-sm mb-1">
                            <span class="input-group-text">Warehouse</span>
                            <select class="form-select" id="rcvcustoms_modal_cmb_locTo">
                                <option value="-">-</option>
                                <?=$machineLocation?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" id="rcvcustoms_btn_set_location" onclick="rcvcustoms_btn_set_locationOnClick()" title="OK">OK</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="RCVCUSTOMS_DOCUMENTS">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Documents</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <img id="rcvcustoms_image" style="border: 1px solid silver; width: 320px; height: 240px" />
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-primary" onclick="rcvcustoms_get_image()">Paste</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label for="rcvcustoms_nomor_faktur_pajak" class="form-label">Nomor Faktur Pajak</label>
                        <input type="text" class="form-control" id="rcvcustoms_nomor_faktur_pajak" maxlength="40">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" onclick="rcvcustoms_btn_save_docs()" title="Save"><i class="fas fa-save"></i></button>
            </div>
        </div>
    </div>
</div>
<div id='rcvcustoms_contextmenu'></div>
<script>
    var rcvcustoms_suppliercode = ''
    var rcvcustoms_shippercode = ''
    var rcvcustoms_selected_row = 0;
    var rcvcustoms_selected_col = 1;
    var rcvcustoms_selected_table = ''
    
    var rcvcustoms_currentmonth = (new Date().getMonth() + 1).toString() < 10 ? '0' + (new Date().getMonth() + 1).toString() : (new Date().getMonth() + 1).toString()
    document.getElementById('rcvcustoms_monthfilter').value = rcvcustoms_currentmonth
    document.getElementById('rcvcustoms_monthfilter_1').value = rcvcustoms_currentmonth

    function rcvcustoms_sync_eCK() {
        const btn = document.getElementById('rcvcustoms_sync')
        btn.disabled = true
        btn.innerHTML = `<i class="fas fa-spinner fa-spin"></i>`
        $.ajax({
            type: "GET",
            url: `<?=$_ENV['APP_INTERNAL_API']?>receive/synchronize`,
            dataType: "JSON",
            success: function(response) {
                let ttlrows = response.dataDO.length
                if (ttlrows > 0) {
                    alertify.success(`${ttlrows} synchronized`)
                } else {
                    alertify.message(`${ttlrows} synchronized`)
                }
                btn.disabled = false
                btn.innerHTML = `<i class="fas fa-sync"></i>`
            },
            error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow)
                btn.disabled = false
                btn.innerHTML = `<i class="fas fa-sync"></i>`
            }
        })
    }

    function rcvcustoms_save_1_eCK(pthis) {
        const mdo = document.getElementById('rcvcustoms_docnoorigin_1').value.trim()
        const mbctype = $("#rcvcustoms_typedoc_1").val()
        const zstsrcv = document.getElementById('rcvcustoms_zsts_1').value
        const mnoaju = $("#rcvcustoms_noaju_1").val()
        const mregno = $("#rcvcustoms_regno_1").val()
        const mdate = rcvcustoms_dd_1.value
        const mrcvdate = rcvcustoms_rcvdate_1.value
        const mtpb = $("#rcvcustoms_typetpb_1").val()
        const mkppbc = $("#rcvcustoms_kppbc_1").val()
        const m_amt = numeral($("#rcvcustoms_amount_1").val()).value()
        const m_nw = numeral($("#rcvcustoms_NW_1").val()).value()
        const m_gw = numeral($("#rcvcustoms_GW_1").val()).value()
        const mbisgrup = document.getElementById('rcvcustoms_businessgroup_1').value
        const mconaNum = document.getElementById('rcvcustoms_contractnum_1').value
        const mconaDate = document.getElementById('rcvcustoms_contractdate_1').value
        const mconaDueDate = document.getElementById('rcvcustoms_contractduedate_1').value
        const minvNo = document.getElementById('rcvcustoms_invoicenum_1').value
        const mtax_invoice = document.getElementById('rcvcustoms_tax_invoice').value
        const supplier = rcvcustoms_suppliercode
        const shipper = rcvcustoms_shippercode
        if (mrcvdate < mdate) {
            alertify.warning('receive date could not be less than document date')
            return
        }
        if (mdo.length == 0) {
            alertify.message("DO number could not be empty")
            return
        }
        let d_grlno = []
        let d_nourut = []
        let d_pono = []
        let d_asset_status = []
        let d_itemcode = []
        let d_qty = []
        let d_price = []
        let d_hscode = []
        let d_bm = []
        let d_ppn = []
        let d_pph = []
        let d_prNW = []
        let d_prGW = []
        let d_pkg_idrow = []
        let d_pkg_jml = []
        let d_pkg_kd = []
        let d_location = []

        let mytable = document.getElementById('rcvcustoms_tbl_1').getElementsByTagName('tbody')[0]
        let mtrlength = mytable.getElementsByTagName('tr').length
        for (let i = 0; i < mtrlength; i++) {
            const itmcode = mytable.rows[i].cells[4].innerText.trim().replace(/\n+/g, '')
            const price = mytable.rows[i].cells[8].innerText.replace(/\n+/g, '')
            const location = mytable.rows[i].cells[16].innerText.replace(/\n+/g, '')
            if (price == '-') {
                alertify.warning('Price is not valid')
                return
            }
            if (itmcode.length) {
                d_grlno.push(mytable.rows[i].cells[0].innerText.replace(/\n+/g, ''))
                d_nourut.push(mytable.rows[i].cells[1].innerText.replace(/\n+/g, ''))
                d_pono.push(mytable.rows[i].cells[2].innerText.replace(/\n+/g, ''))
                d_asset_status.push(mytable.rows[i].cells[3].innerText.replace(/\n+/g, ''))
                d_itemcode.push(itmcode)
                d_qty.push(mytable.rows[i].cells[6].innerText.replace(/\n+/g, ''))
                d_price.push(price)
                d_prNW.push(mytable.rows[i].cells[10].innerText.replace(/\n+/g, ''))
                d_prGW.push(mytable.rows[i].cells[11].innerText.replace(/\n+/g, ''))
                d_hscode.push(mytable.rows[i].cells[12].innerText.replace(/\n+/g, ''))
                d_bm.push(mytable.rows[i].cells[13].innerText.replace(/\n+/g, ''))
                d_ppn.push(mytable.rows[i].cells[14].innerText.replace(/\n+/g, ''))
                d_pph.push(mytable.rows[i].cells[15].innerText.replace(/\n+/g, ''))
                if(location.length <= 1 ) {
                    alertify.warning('Location is required')
                    return
                }
                d_location.push(location)
            }
        }

        if (d_itemcode.length == 0) {
            alertify.message('there is no item')
            return
        }
        mytable = document.getElementById('rcvcustoms_pkg_tbl').getElementsByTagName('tbody')[0]
        mtrlength = mytable.getElementsByTagName('tr').length
        for (let i = 0; i < mtrlength; i++) {
            d_pkg_idrow.push(mytable.rows[i].cells[0].innerText)
            d_pkg_jml.push(mytable.rows[i].cells[1].innerText)
            d_pkg_kd.push(mytable.rows[i].cells[2].innerText)
        }
        if (confirm("Are you sure ?")) {
            pthis.disabled = true
            $.ajax({
                type: "POST",
                url: "<?=base_url('RCV/saveManually')?>",
                data: {
                    h_do: mdo,
                    h_bctype: mbctype,
                    h_bcstatus: zstsrcv,
                    h_aju: mnoaju,
                    h_nopen: mregno,
                    h_date_bc: mdate,
                    h_date_rcv: mrcvdate,
                    h_type_tpb: mtpb,
                    h_kppbc: mkppbc,
                    h_amount: m_amt,
                    h_nw: m_nw,
                    h_gw: m_gw,
                    h_bisgrup: mbisgrup,
                    h_cona: mconaNum,
                    h_supcd: supplier,
                    h_shippercd: shipper,
                    h_mconaDate: mconaDate,
                    h_mconaDueDate: mconaDueDate,
                    h_minvNo: minvNo,
                    h_tax_invoice: mtax_invoice,
                    h_pl_num: rcvcustoms_pkg_nomor.value,
                    d_grlno: d_grlno,
                    d_nourut: d_nourut,
                    d_pono: d_pono,
                    d_itemcode: d_itemcode,
                    d_qty: d_qty,
                    d_price: d_price,
                    d_hscode: d_hscode,
                    d_bm: d_bm,
                    d_ppn: d_ppn,
                    d_pph: d_pph,
                    d_prNW: d_prNW,
                    d_prGW: d_prGW,
                    d_pkg_idrow: d_pkg_idrow,
                    d_pkg_jml: d_pkg_jml,
                    d_pkg_kd: d_pkg_kd,
                    d_location: d_location
                },
                dataType: "JSON",
                success: function(response) {
                    pthis.disabled = false
                    if (response.status[0].cd == 1) {
                        alertify.success(response.status[0].msg)
                        rcvuctoms_getdetail1({
                            pdo: mdo,
                            psupcd: supplier,
                            pAJU: mnoaju
                        })
                    } else {
                        alertify.message(response.status[0].msg)
                    }
                },
                error: function(xhr, xopt, xthrow) {
                    pthis.disabled = false
                    alertify.error(xthrow)
                }
            });
        }
    }

    function rcvcustoms_save_2_eCK() {
        const mdo = document.getElementById('rcvcustoms_docnoorigin_2').value.trim()
        const mbctype = $("#rcvcustoms_typedoc_2").val()
        const zstsrcv = document.getElementById('rcvcustoms_zsts_2').value
        const mnoaju = $("#rcvcustoms_noaju_2").val()
        const mregno = $("#rcvcustoms_regno_2").val()
        const mdate = $("#rcvcustoms_dd_2").val()
        const mrcvdate = $("#rcvcustoms_rcvdate_2").val()
        const mtpb = $("#rcvcustoms_typetpb_2").val()
        const mkppbc = $("#rcvcustoms_kppbc_2").val()
        const m_amt = numeral($("#rcvcustoms_amount_2").val()).value()
        const m_nw = numeral($("#rcvcustoms_NW_2").val()).value()
        const m_gw = numeral($("#rcvcustoms_GW_2").val()).value()
        const mbisgrup = document.getElementById('rcvcustoms_businessgroup_2').value
        const mwarehouse = document.getElementById('rcvcustoms_cmb_locTo')
        const minvNo = document.getElementById('rcvcustoms_invoicenum_2').value
        const supplier = rcvcustoms_suppliercode
        if (mdo.length == 0) {
            alertify.message("DO number could not be empty")
            return
        }
        let d_grlno = []
        let d_nourut = []
        let d_pono = []
        let d_itemcode = []
        let d_qty = []
        let d_price = []
        let d_hscode = []
        let d_bm = []
        let d_ppn = []
        let d_pph = []

        let mytable = document.getElementById('rcvcustoms_tbl_2').getElementsByTagName('tbody')[0]
        const mtrlength = mytable.getElementsByTagName('tr').length
        if (mwarehouse.value === '-') {
            alertify.warning('Please select warehouse')
            mwarehouse.focus()
            return
        }
        for (let i = 0; i < mtrlength; i++) {
            const nomorUrut = mytable.rows[i].cells[1].innerText.trim().replace(/\n+/g, '')
            const itmcode = mytable.rows[i].cells[3].innerText.trim().replace(/\n+/g, '')
            const price = mytable.rows[i].cells[7].innerText.replace(/\n+/g, '')
            if (nomorUrut.length === 0) {
                alertify.warning("Nomor Urut is required")
                mytable.rows[i].cells[1].focus()
                return
            }
            if (numeral(nomorUrut).value() <= 0) {
                alertify.warning("Nomor Urut is not valid")
                mytable.rows[i].cells[1].focus()
                return
            }
            if (price === '-') {
                alertify.warning("Price value should be numerical")
                mytable.rows[i].cells[7].focus()
                return
            }
            if (itmcode.length) {
                if (numeral(price).value() === 0) {
                    alertify.warning("Price could not be 0")
                    mytable.rows[i].cells[7].focus()
                    return
                }
                d_grlno.push(mytable.rows[i].cells[0].innerText.replace(/\n+/g, ''))
                d_nourut.push(mytable.rows[i].cells[1].innerText.replace(/\n+/g, ''))
                d_pono.push(mytable.rows[i].cells[2].innerText.replace(/\n+/g, ''))
                d_itemcode.push(itmcode)
                d_qty.push(mytable.rows[i].cells[5].innerText.replace(/\n+/g, ''))
                d_price.push(price)
                d_hscode.push(mytable.rows[i].cells[9].innerText.replace(/\n+/g, ''))
                d_bm.push(mytable.rows[i].cells[10].innerText.replace(/\n+/g, ''))
                d_ppn.push(mytable.rows[i].cells[11].innerText.replace(/\n+/g, ''))
                d_pph.push(mytable.rows[i].cells[12].innerText.replace(/\n+/g, ''))
            }
        }
        if (d_itemcode.length == 0) {
            alertify.message('there is no item')
            return
        }
        if (confirm("Are you sure ?")) {
            $.ajax({
                type: "POST",
                url: "<?=base_url('RCV/saveManually2')?>",
                data: {
                    h_do: mdo,
                    h_bctype: mbctype,
                    h_bcstatus: zstsrcv,
                    h_aju: mnoaju,
                    h_nopen: mregno,
                    h_date_bc: mdate,
                    h_date_rcv: mrcvdate,
                    h_type_tpb: mtpb,
                    h_kppbc: mkppbc,
                    h_amount: m_amt,
                    h_nw: m_nw,
                    h_gw: m_gw,
                    h_bisgrup: mbisgrup,
                    h_supcd: supplier,
                    h_minvNo: minvNo,
                    h_warehouse: mwarehouse.value,
                    d_grlno: d_grlno,
                    d_nourut: d_nourut,
                    d_pono: d_pono,
                    d_itemcode: d_itemcode,
                    d_qty: d_qty,
                    d_price: d_price,
                    d_hscode: d_hscode,
                    d_bm: d_bm,
                    d_ppn: d_ppn,
                    d_pph: d_pph
                },
                dataType: "JSON",
                success: function(response) {
                    if (response.status[0].cd == 1) {
                        alertify.success(response.status[0].msg)
                        rcvuctoms_getdetail2(mdo)
                    } else {
                        alertify.message(response.status[0].msg)
                    }
                },
                error: function(xhr, xopt, xthrow) {
                    alertify.error(xthrow)
                }
            });
        }
    }

    function rcvcustoms_new_1_eCK() {
        rcvcustoms_btn_find_supplier_1.disabled = false
        document.getElementById('rcvcustoms_tbl_1').getElementsByTagName('tbody')[0].innerHTML = ""
        rcvcustoms_selected_row = 0
        document.getElementById('rcvcustoms_docnoorigin_1').value = ''
        document.getElementById('rcvcustoms_noaju_1').value = ''
        document.getElementById('rcvcustoms_regno_1').value = ''
        document.getElementById('rcvcustoms_amount_1').value = ''
        document.getElementById('rcvcustoms_NW_1').value = ''
        document.getElementById('rcvcustoms_GW_1').value = ''
        document.getElementById('rcvcustoms_supplier_1').value = ''
        document.getElementById('rcvcustoms_shipper_1').value = ''
        document.getElementById('rcvcustoms_contractnum_1').value = ''
        document.getElementById('rcvcustoms_contractdate_1').value = ''
        document.getElementById('rcvcustoms_contractduedate_1').value = ''
        document.getElementById('rcvcustoms_docnoorigin_1').readOnly = false
        const btnTPB = document.getElementById('rcvcustoms_btnTPB')
        btnTPB.disabled = false
        btnTPB.innerHTML = 'TPB'
    }

    function rcvcustoms_new_2_eCK() {
        document.getElementById('rcvcustoms_tbl_2').getElementsByTagName('tbody')[0].innerHTML = ""
        rcvcustoms_selected_row = 0
        document.getElementById('rcvcustoms_docnoorigin_2').value = ''
        document.getElementById('rcvcustoms_noaju_2').value = ''
        document.getElementById('rcvcustoms_regno_2').value = ''
        document.getElementById('rcvcustoms_amount_2').value = ''
        document.getElementById('rcvcustoms_NW_2').value = ''
        document.getElementById('rcvcustoms_GW_2').value = ''
        document.getElementById('rcvcustoms_supplier_2').value = ''
        document.getElementById('rcvcustoms_cmb_locTo').value = '-'

        document.getElementById('rcvcustoms_docnoorigin_2').readOnly = false
    }

    $("#RCVCUSTOMS_INTERNALITEM").on('shown.bs.modal', function() {
        document.getElementById('rcvcustoms_txtsearchNALITM').focus()
    })
    $("#RCVCUSTOMS_SHIPPER").on('shown.bs.modal', function() {
        document.getElementById('rcvcustomsSearchShipper').focus()
    })
    $("#RCVCUSTOMS_DTLMOD_1").on('shown.bs.modal', function() {
        document.getElementById('rcvcustoms_txt_search_1').focus()
    })
    $("#RCVCUSTOMS_DTLMOD_2").on('shown.bs.modal', function() {
        document.getElementById('rcvcustoms_txt_search_2').focus()
    })
    $("#rcvcustoms_POBAL_Mod").on('shown.bs.modal', function() {
        document.getElementById('rcvcustoms_po_txtsearch').focus()
    })
    $("#rcvcustoms_KEMASAN_Mod").on('shown.bs.modal', function() {
        document.getElementById('rcvcustoms_pkg_jumlah').focus()
    })

    function rcvcustoms_NALITM_searchby_eCh() {
        document.getElementById('rcvcustoms_txtsearchNALITM').focus()
    }

    function rcvcustoms_minusrow(ptable) {
        let mytable = document.getElementById(ptable).getElementsByTagName('tbody')[0]
        const mtr = mytable.getElementsByTagName('tr')[rcvcustoms_selected_row]
        const mylineid = mtr.getElementsByTagName('td')[0].innerText.trim()
        if (mylineid !== '') {
            if (confirm("Are you sure ?")) {
                const docnum = ptable == 'rcvcustoms_tbl_2' ? document.getElementById('rcvcustoms_docnoorigin_2').value : document.getElementById('rcvcustoms_docnoorigin_1').value
                $.ajax({
                    type: "post",
                    url: "<?=base_url('RCV/removemanual')?>",
                    data: {
                        lineId: mylineid,
                        docNum: docnum
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.status[0].cd === '1') {
                            alertify.success(response.status[0].msg)
                            mtr.remove()
                        } else {
                            alertify.message(response.status[0].msg)
                        }
                    },
                    error: function(xhr, xopt, xthrow) {
                        alertify.error(xthrow)
                    }
                })
            }
        } else {
            mtr.remove()
            rcvcustoms_selected_row = mytable.rows.length - 1
        }
    }
    var rcvcustoms_tablefokus = ''

    function rcvcustoms_txtsearchNALITM_eKP(e) {
        if (e.key === 'Enter') {
            const searchBy = document.getElementById('rcvcustoms_NALITM_searchby').value
            const search = document.getElementById('rcvcustoms_txtsearchNALITM').value
            const lblwait = '<tr><td colspan="4" class="text-center">Please wait</td></tr>'
            document.getElementById('rcvcustoms_tblNALITM').getElementsByTagName('tbody')[0].innerHTML = lblwait
            $.ajax({
                type: "GET",
                url: "<?=base_url('MSTITM/search_internal_item')?>",
                data: {
                    searchBy: searchBy,
                    search: search
                },
                dataType: "JSON",
                success: function(response) {
                    const ttlrows = response.data.length
                    if (ttlrows > 0) {
                        let mydes = document.getElementById("rcvcustoms_tblNALITM_div")
                        let myfrag = document.createDocumentFragment()
                        let mtabel = document.getElementById("rcvcustoms_tblNALITM")
                        let cln = mtabel.cloneNode(true);
                        myfrag.appendChild(cln)
                        let tabell = myfrag.getElementById("rcvcustoms_tblNALITM")
                        let tableku2 = tabell.getElementsByTagName("tbody")[0]
                        let newrow, newcell, newText
                        let myitmttl = 0;
                        tableku2.innerHTML = ''
                        for (let i = 0; i < ttlrows; i++) {
                            newrow = tableku2.insertRow(-1)
                            newcell = newrow.insertCell(0)
                            newcell.style.cssText = "cursor:pointer"
                            newcell.onclick = () => {
                                if (response.data[i].MITM_STKUOM.length == 0) {
                                    alertify.message('Unit Measurement could not be empty')
                                    return
                                }
                                let isfound = false
                                let ttlrowstarget = document.getElementById(rcvcustoms_tablefokus).getElementsByTagName('tbody')[0].rows.length
                                for (let k = 0; k < ttlrowstarget; k++) {
                                    if (rcvcustoms_tablefokus == 'rcvcustoms_tbl_1') {
                                        if (document.getElementById(rcvcustoms_tablefokus).getElementsByTagName('tbody')[0].rows[k].cells[4].innerHTML === response.data[i].MITM_ITMCD) {
                                            isfound = true
                                            break
                                        }
                                    } else {
                                        if (document.getElementById(rcvcustoms_tablefokus).getElementsByTagName('tbody')[0].rows[k].cells[3].innerHTML === response.data[i].MITM_ITMCD) {
                                            isfound = true
                                            break
                                        }
                                    }
                                    
                                }
                                if (!isfound) {
                                    if (rcvcustoms_tablefokus == 'rcvcustoms_tbl_1') {
                                        document.getElementById(rcvcustoms_tablefokus).getElementsByTagName('tbody')[0].rows[rcvcustoms_selected_row].cells[4].innerHTML = response.data[i].MITM_ITMCD.trim()
                                        document.getElementById(rcvcustoms_tablefokus).getElementsByTagName('tbody')[0].rows[rcvcustoms_selected_row].cells[5].innerHTML = response.data[i].MITM_ITMD1
                                        document.getElementById(rcvcustoms_tablefokus).getElementsByTagName('tbody')[0].rows[rcvcustoms_selected_row].cells[7].innerHTML = response.data[i].MITM_STKUOM
                                    } else {
                                        document.getElementById(rcvcustoms_tablefokus).getElementsByTagName('tbody')[0].rows[rcvcustoms_selected_row].cells[3].innerHTML = response.data[i].MITM_ITMCD.trim()
                                        document.getElementById(rcvcustoms_tablefokus).getElementsByTagName('tbody')[0].rows[rcvcustoms_selected_row].cells[4].innerHTML = response.data[i].MITM_ITMD1
                                        document.getElementById(rcvcustoms_tablefokus).getElementsByTagName('tbody')[0].rows[rcvcustoms_selected_row].cells[6].innerHTML = response.data[i].MITM_STKUOM
                                    }
                                    $("#RCVCUSTOMS_INTERNALITEM").modal('hide')
                                } else {
                                    alertify.message('already added')
                                }
                            }
                            newcell.innerHTML = response.data[i].MITM_ITMCD.trim()
                            newcell = newrow.insertCell(1)
                            newcell.innerHTML = response.data[i].MITM_ITMCDCUS
                            newcell = newrow.insertCell(2)
                            newcell.innerHTML = response.data[i].MITM_ITMD1
                            newcell = newrow.insertCell(3)
                            newcell.innerHTML = response.data[i].MITM_STKUOM
                        }
                        mydes.innerHTML = ''
                        mydes.appendChild(myfrag)
                    } else {
                        document.getElementById('rcvcustoms_tblNALITM').getElementsByTagName('tbody')[0].innerHTML = `<tr><td colspan="4" class="text-center">Not found</td></tr>`
                    }
                },
                error: function(xhr, ajaxOptions, throwError) {
                    alertify.error(xthrow)
                }
            })
        }
    }

    function rcvcustoms_addrow_MS(ptable) {
        let mytbody = document.getElementById(ptable).getElementsByTagName('tbody')[0]
        let newrow, newcell
        newrow = mytbody.insertRow(-1)
        newrow.onclick = (event) => {
            rcvcustoms_tbl_tbody_tr_eC(event);
        }
        newcell = newrow.insertCell(0)
        newcell.classList.add('d-none')
        newcell.innerHTML = ''

        newcell = newrow.insertCell(1)
        newcell.contentEditable = true

        newcell = newrow.insertCell(2)
        newcell.contentEditable = true
        newcell.innerHTML = '-'

        newcell = newrow.insertCell(3)
        newcell.title = `double click for showing search dialog`
        newcell.classList.add('table-info')
        newcell.onclick = () => {
            $("#RCVCUSTOMS_INTERNALITEM").modal('show')
        }
        newcell.style.cssText = "cursor:pointer"
        newcell.innerHTML = ''

        newcell = newrow.insertCell(4)
        newcell.classList.add('table-info')
        newcell.innerHTML = '-'

        newcell = newrow.insertCell(5)
        newcell.contentEditable = true
        newcell.classList.add('text-end')
        newcell.innerHTML = '0'

        newcell = newrow.insertCell(6)
        newcell.classList.add('table-info')
        newcell.innerHTML = '-'

        newcell = newrow.insertCell(7)
        newcell.classList.add('text-end')
        newcell.contentEditable = true
        newcell.innerHTML = '-'

        newcell = newrow.insertCell(8)
        newcell.classList.add('text-end', 'table-info')
        newcell.innerHTML = '-'


        newcell = newrow.insertCell(9)
        newcell.title = 'HSCode'
        newcell.contentEditable = true
        newcell.innerHTML = '-'

        newcell = newrow.insertCell(10)
        newcell.title = 'BM'
        newcell.classList.add('text-end')
        newcell.contentEditable = true
        newcell.innerHTML = '-'

        newcell = newrow.insertCell(11)
        newcell.title = 'PPN'
        newcell.classList.add('text-end')
        newcell.contentEditable = true
        newcell.innerHTML = '-'

        newcell = newrow.insertCell(12)
        newcell.title = 'PPH'
        newcell.classList.add('text-end')
        newcell.contentEditable = true
        newcell.innerHTML = '-'
    }

    function rcvcustoms_addrow(ptable) {
        let mytbody = document.getElementById(ptable).getElementsByTagName('tbody')[0]
        let newrow, newcell
        newrow = mytbody.insertRow(-1)
        newrow.onclick = (event) => {
            rcvcustoms_tbl_tbody_tr_eC(event);
        }
        newcell = newrow.insertCell(0)
        newcell.classList.add('d-none')
        newcell.innerHTML = ''

        newcell = newrow.insertCell(1)
        newcell.contentEditable = true

        newcell = newrow.insertCell(2)
        newcell.contentEditable = true
        newcell.innerHTML = '-'

        newcell = newrow.insertCell(3)
        newcell.title = `double click for showing search dialog`
        newcell.classList.add('table-info')
        newcell.onclick = () => {
            $("#RCVCUSTOMS_INTERNALITEM").modal('show')
        }
        newcell.style.cssText = "cursor:pointer"
        newcell.innerHTML = ''

        newcell = newrow.insertCell(4)
        newcell.classList.add('table-info')
        newcell.innerHTML = '-'

        newcell = newrow.insertCell(5)
        newcell.contentEditable = true
        newcell.classList.add('text-end')
        newcell.innerHTML = '0'

        newcell = newrow.insertCell(6)
        newcell.classList.add('table-info')
        newcell.innerHTML = '-'

        newcell = newrow.insertCell(7)
        newcell.classList.add('text-end')
        newcell.contentEditable = true
        newcell.innerHTML = '-'

        newcell = newrow.insertCell(8)
        newcell.classList.add('text-end', 'table-info')
        newcell.innerHTML = '-'

        newcell = newrow.insertCell(9)
        newcell.title = 'Net weight'
        newcell.classList.add('text-end')
        newcell.contentEditable = true
        newcell.innerHTML = 0
        newcell = newrow.insertCell(10)
        newcell.classList.add('text-end')
        newcell.title = 'Gross weight'
        newcell.contentEditable = true
        newcell.innerHTML = '-'
        newcell = newrow.insertCell(11)
        newcell.title = 'HSCode'
        newcell.contentEditable = true
        newcell.innerHTML = '-'

        newcell = newrow.insertCell(12)
        newcell.title = 'BM'
        newcell.classList.add('text-end')
        newcell.contentEditable = true
        newcell.innerHTML = '-'

        newcell = newrow.insertCell(13)
        newcell.title = 'PPN'
        newcell.classList.add('text-end')
        newcell.contentEditable = true
        newcell.innerHTML = '-'

        newcell = newrow.insertCell(14)
        newcell.title = 'PPH'
        newcell.classList.add('text-end')
        newcell.contentEditable = true
        newcell.innerHTML = '-'
    }

    function rcvcustoms_addrow_machine(ptable) {
        let mytbody = document.getElementById(ptable).getElementsByTagName('tbody')[0]
        let newrow, newcell
        newrow = mytbody.insertRow(-1)
        newrow.onclick = (event) => {
            rcvcustoms_tbl_tbody_tr_eC(event);
        }
        newcell = newrow.insertCell(0)
        newcell.classList.add('d-none')
        newcell.innerHTML = ''

        newcell = newrow.insertCell(1)
        newcell.contentEditable = true

        newcell = newrow.insertCell(2)
        newcell.contentEditable = true
        newcell.innerHTML = '-'

        newcell = newrow.insertCell(3)

        newcell = newrow.insertCell(4)
        newcell.title = `double click for showing search dialog`
        newcell.classList.add('table-info')
        newcell.onclick = () => {
            $("#RCVCUSTOMS_INTERNALITEM").modal('show')
        }
        newcell.style.cssText = "cursor:pointer"
        newcell.innerHTML = ''

        newcell = newrow.insertCell(5)
        newcell.classList.add('table-info')
        newcell.innerHTML = '-'

        newcell = newrow.insertCell(6)
        newcell.contentEditable = true
        newcell.classList.add('text-end')
        newcell.innerHTML = '0'

        newcell = newrow.insertCell(7)
        newcell.classList.add('table-info')
        newcell.innerHTML = '-'

        newcell = newrow.insertCell(8)
        newcell.classList.add('text-end')
        newcell.contentEditable = true
        newcell.innerHTML = '-'

        newcell = newrow.insertCell(9)
        newcell.classList.add('text-end', 'table-info')
        newcell.innerHTML = '-'

        newcell = newrow.insertCell(10)
        newcell.title = 'Net weight'
        newcell.classList.add('text-end')
        newcell.contentEditable = true
        newcell.innerHTML = 0
        newcell = newrow.insertCell(11)
        newcell.classList.add('text-end')
        newcell.title = 'Gross weight'
        newcell.contentEditable = true
        newcell.innerHTML = '-'
        newcell = newrow.insertCell(12)
        newcell.title = 'HSCode'
        newcell.contentEditable = true
        newcell.innerHTML = '-'

        newcell = newrow.insertCell(13)
        newcell.title = 'BM'
        newcell.classList.add('text-end')
        newcell.contentEditable = true
        newcell.innerHTML = '-'

        newcell = newrow.insertCell(14)
        newcell.title = 'PPN'
        newcell.classList.add('text-end')
        newcell.contentEditable = true
        newcell.innerHTML = '-'

        newcell = newrow.insertCell(15)
        newcell.title = 'PPH'
        newcell.classList.add('text-end')
        newcell.contentEditable = true
        newcell.innerHTML = '-'

        newcell = newrow.insertCell(16)
        newcell.style.cssText = "cursor:pointer"
        newcell.classList.add('text-center', 'table-info')
        newcell.title = 'to Location'
        newcell.onclick = (event) => {
            rcvcustoms_modal_cmb_locTo.value = event.target.innerText
            $("#RCVCUSTOMS_LOCATION").modal('show')
        }
    }

    function rcvcustoms_plus_1_eC() {
        rcvcustoms_tablefokus = 'rcvcustoms_tbl_1'
        rcvcustoms_addrow_machine('rcvcustoms_tbl_1')
        let mytbody = document.getElementById('rcvcustoms_tbl_1').getElementsByTagName('tbody')[0]
        if (rcvcustoms_selected_row < 0) {
            rcvcustoms_selected_row = 0
        }
        if (rcvcustoms_selected_row != mytbody.rows.length - 1) {
            mytbody.rows[rcvcustoms_selected_row].classList.remove('table-active')
        }
        rcvcustoms_selected_row = mytbody.rows.length - 1
        mytbody.rows[rcvcustoms_selected_row].classList.add('table-active')
    }

    function rcvcustoms_plus_2_eC() {
        rcvcustoms_tablefokus = 'rcvcustoms_tbl_2'
        rcvcustoms_addrow_MS('rcvcustoms_tbl_2')
        let mytbody = document.getElementById('rcvcustoms_tbl_2').getElementsByTagName('tbody')[0]
        if (rcvcustoms_selected_row < 0) {
            rcvcustoms_selected_row = 0
        }
        if (rcvcustoms_selected_row != mytbody.rows.length - 1) {
            mytbody.rows[rcvcustoms_selected_row].classList.remove('table-active')
        }
        rcvcustoms_selected_row = mytbody.rows.length - 1
        mytbody.rows[rcvcustoms_selected_row].classList.add('table-active')
    }

    function rcvcustoms_mode_eC(pthis) {
        let comp = document.getElementsByClassName('rcvcustoms_div_mode')
        const compLength = comp.length
        for (let i = 0; i < compLength; i++) {
            if ('rcvcustoms_div_mode' + pthis.value === comp[i].id) {
                comp[i].classList.remove('d-none')
            } else {
                comp[i].classList.add('d-none')
            }
        }
        if (pthis.id == 'rcvcustoms_mode3') {
            rcvcustoms_tablefokus = 'rcvcustoms_tbl_2'
        } else {
            rcvcustoms_tablefokus = 'rcvcustoms_tbl_1'
        }
    }

    function rcvcustoms_docnoorigin_eDC() {
        document.getElementById('rcvcustoms_docnoorigin').readOnly = false
    }

    function rcvcustoms_tbl_tbody_tr_eC(e) {
        if (rcvcustoms_selected_row != e.srcElement.parentElement.rowIndex - 1) {
            if (typeof e.srcElement.parentElement.parentElement.rows[rcvcustoms_selected_row] !== 'undefined') {
                e.srcElement.parentElement.parentElement.rows[rcvcustoms_selected_row].classList.remove('table-active')
            } else {
                rcvcustoms_selected_row--;
            }
        }
        rcvcustoms_selected_row = e.srcElement.parentElement.rowIndex - 1
        if (isNaN(rcvcustoms_selected_row)) {
            rcvcustoms_selected_row = 0
        }
        e.srcElement.parentElement.parentElement.rows[rcvcustoms_selected_row].classList.add('table-active')
        const ptablefocus = e.srcElement.parentElement.parentElement.offsetParent.id
        rcvcustoms_selected_table = ptablefocus
        rcvcustoms_selected_col = e.srcElement.cellIndex
    }

    function rcvcustoms_txtsearchSUP_eKP(e) {
        if (e.key === 'Enter') {
            const txt = document.getElementById('rcvcustoms_txtsearchSUP')
            txt.readOnly = true
            document.getElementById('rcvcustoms_tblsup').getElementsByTagName('tbody')[0].innerHTML = ''
            $.ajax({
                type: "GET",
                url: "<?=base_url('MSTSUP/search_union')?>",
                data: {
                    searchKey: txt.value
                },
                dataType: "JSON",
                success: function(response) {
                    txt.readOnly = false
                    if (response.status[0].cd === 1) {
                        const ttlrows = response.data.length
                        let mydes = document.getElementById("rcvcustoms_tblsup_div")
                        let myfrag = document.createDocumentFragment()
                        let mtabel = document.getElementById("rcvcustoms_tblsup")
                        let cln = mtabel.cloneNode(true);
                        myfrag.appendChild(cln)
                        let tabell = myfrag.getElementById("rcvcustoms_tblsup")
                        let tableku2 = tabell.getElementsByTagName("tbody")[0]
                        let newrow, newcell, newText
                        let myitmttl = 0;
                        tableku2.innerHTML = ''
                        for (let i = 0; i < ttlrows; i++) {
                            newrow = tableku2.insertRow(-1)
                            newcell = newrow.insertCell(0)
                            newcell.style.cssText = "cursor:pointer"
                            newcell.innerHTML = response.data[i].MSUP_SUPCD
                            newcell.onclick = () => {
                                $("#RCVCUSTOMS_SUPPLIER").modal('hide');
                                rcvcustoms_suppliercode = response.data[i].MSUP_SUPCD
                                if (rcvcustoms_triggerfrom == 'rcvcustoms_btn_find_supplier_2') {
                                    document.getElementById('rcvcustoms_supplier_2').value = response.data[i].MSUP_SUPNM
                                } else if (rcvcustoms_triggerfrom == 'rcvcustoms_fg_btn_find_supplier_2') {
                                    document.getElementById('rcvcustoms_fg_supplier_2').value = response.data[i].MSUP_SUPNM
                                } else {
                                    document.getElementById('rcvcustoms_supplier_1').value = response.data[i].MSUP_SUPNM
                                }
                            }
                            newcell = newrow.insertCell(1)
                            newcell.innerHTML = response.data[i].MSUP_SUPNM
                        }
                        mydes.innerHTML = '';
                        mydes.appendChild(myfrag);
                    } else {
                        alertify.message(response.status[0].msg)
                    }
                },
                error: function(xhr, ajaxOptions, throwError) {
                    txt.readOnly = false
                }
            })
        }
    }
    var rcvcustoms_triggerfrom = ''

    function rcvcustoms_th_sup_eC(pthis) {
        rcvcustoms_triggerfrom = pthis.id
        $("#RCVCUSTOMS_SUPPLIER").modal('show')
    }
    $("#RCVCUSTOMS_SUPPLIER").on('shown.bs.modal', function() {
        document.getElementById('rcvcustoms_txtsearchSUP').focus()
    })
    $("#rcvcustoms_divku").css('height', $(window).height() -
        document.getElementById('rcvcustoms_stack0').offsetHeight -
        document.getElementById('rcvcustoms_stack1').offsetHeight -
        document.getElementById('rcvcustoms_stack2').offsetHeight -
        document.getElementById('rcvcustoms_stack3').offsetHeight -
        document.getElementById('rcvcustoms_stack4').offsetHeight -
        document.getElementById('rcvcustoms_stack5').offsetHeight -
        document.getElementById('rcvcustoms_stack6').offsetHeight -
        document.getElementById('rcvcustoms_stack7').offsetHeight -
        100);

    var rcvcustoms_selcol = '0';

    $("#rcvcustoms_monthfilter").change(function(e) {
        document.getElementById('rcvcustoms_txt_search').focus();
    });
    $("#rcvcustoms_ck").click(function(e) {
        document.getElementById('rcvcustoms_monthfilter').disabled = !document.getElementById('rcvcustoms_ck').checked;
        document.getElementById('rcvcustoms_year').disabled = !document.getElementById('rcvcustoms_ck').checked;
        document.getElementById('rcvcustoms_txt_search').focus();
    })
    $("#rcvcustoms_ck_1").click(function(e) {
        document.getElementById('rcvcustoms_monthfilter_1').disabled = !document.getElementById('rcvcustoms_ck_1').checked;
        document.getElementById('rcvcustoms_year_1').disabled = !document.getElementById('rcvcustoms_ck_1').checked;
        document.getElementById('rcvcustoms_txt_search_1').focus();
    });
    $("#rcvcustoms_ck_2").click(function(e) {
        document.getElementById('rcvcustoms_monthfilter_2').disabled = !document.getElementById('rcvcustoms_ck_2').checked;
        document.getElementById('rcvcustoms_year_2').disabled = !document.getElementById('rcvcustoms_ck_2').checked;
        document.getElementById('rcvcustoms_txt_search_2').focus();
    });
    document.getElementById('rcvcustoms_year').value = new Date().getFullYear()
    document.getElementById('rcvcustoms_year_1').value = new Date().getFullYear()
    document.getElementById('rcvcustoms_year_2').value = new Date().getFullYear()
    $("#rcvcustoms_rad_do").click(function(e) {
        document.getElementById('rcvcustoms_txt_search').focus();
    });
    $("#rcvcustoms_rad_item").click(function(e) {
        document.getElementById('rcvcustoms_txt_search').focus();
    });
    $("#RCVCUSTOMS_PROGRESS").on('shown.bs.modal', function() {
        rcvcustoms_e_save();
    });
    $("#RCVCUSTOMS_CONA").on('shown.bs.modal', function() {
        document.getElementById('rcvcustoms_txtsearchPK').focus()
    });
    $("#RCVCUSTOMS_IMPORTDATA").on('shown.bs.modal', function() {
        document.getElementById('rcvcustoms_lblsaveprogress').style.width = "0%";
        document.getElementById('rcvcustoms_lblsaveprogress').innerText = "0%";
        document.getElementById('rcvcustoms_xlf_new').value = "";
    });
    $("#rcvcustoms_hscode").click(function(e) {
        $("#RCVCUSTOMS_IMPORTDATA").modal('show');
    });
    $("#rcvcustoms_btn_download").click(function(e) {
        window.open(`<?=$_ENV['APP_INTERNAL_API']?>receive/download-template`, '_blank');       
    });
    $("#rcvcustoms_btn_startimport").click(function(e) {
        if (document.getElementById('rcvcustoms_xlf_new').files.length == 0) {
            alert('please select file to upload');
        } else {
            var fileUpload = $("#rcvcustoms_xlf_new")[0];
            //Validate whether File is valid Excel file.
            var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
            if (regex.test(fileUpload.value.toLowerCase())) {
                if (typeof(FileReader) != "undefined") {
                    var reader = new FileReader();
                    //For Browsers other than IE.
                    if (reader.readAsBinaryString) {
                        console.log('saya perambaan selain IE');
                        reader.onload = function(e) {
                            rcvcustoms_ProcessExcel(e.target.result);
                        };
                        reader.readAsBinaryString(fileUpload.files[0]);
                    } else {
                        //For IE Browser.
                        reader.onload = function(e) {
                            var data = "";
                            var bytes = new Uint8Array(e.target.result);
                            for (var i = 0; i < bytes.byteLength; i++) {
                                data += String.fromCharCode(bytes[i]);
                            }
                            rcvcustoms_ProcessExcel(data);
                        };
                        reader.readAsArrayBuffer(fileUpload.files[0]);
                    }
                } else {
                    alert("This browser does not support HTML5.");
                }
            } else {
                alert("Please upload a valid Excel file.");
            }
        }
    });
    var rcvcustoms_ttlxls = 0;
    var rcvcustoms_ttlxls_savd = 0;

    function rcvcustoms_ProcessExcel(data) {
        //Read the Excel File data.
        var workbook = XLSX.read(data, {
            type: 'binary'
        });

        //Fetch the name of First Sheet.
        var firstSheet = workbook.SheetNames[0];
        //Read all rows from First Sheet into an JSON array.
        var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);


        //Add the data rows from Excel file.
        rcvcustoms_ttlxls = excelRows.length;
        rcvcustoms_ttlxls_savd = 0;
        for (let i = 0; i < excelRows.length; i++) {
            let mdo = excelRows[i].DO;
            let mqty = numeral(excelRows[i].QTY).value();
            let mitemcd = excelRows[i].ITEMCODE;
            let mhscode = excelRows[i].HSCODE;
            let mppn = excelRows[i].PPN;
            let mpph = excelRows[i].PPH;
            let mnomor_urut = excelRows[i].NOMOR_URUT;
            let mbm = excelRows[i].BM;
            let NET_WEIGHT_PER_ITEM = excelRows[i].NET_WEIGHT_PER_ITEM;

            $.ajax({
                type: "post",
                url: "<?=base_url('RCV/import')?>",
                data: {
                    indo: mdo,
                    initemcode: mitemcd,
                    inhscode: mhscode,
                    inppn: mppn,
                    inpph: mpph,
                    innourut: mnomor_urut,
                    inbm: mbm,
                    inrowid: i,
                    inqty: mqty,
                    NET_WEIGHT_PER_ITEM : NET_WEIGHT_PER_ITEM
                },
                dataType: "json",
                success: function(response) {
                    rcvcustoms_ttlxls_savd++;
                    let dis = parseInt(((rcvcustoms_ttlxls_savd) / rcvcustoms_ttlxls) * 100) + "%";
                    document.getElementById('rcvcustoms_lblsaveprogress').style.width = dis;
                    document.getElementById('rcvcustoms_lblsaveprogress').innerText = dis;
                },
                error: function(xhr, xopt, xthrow) {
                    alertify.error(xthrow);
                }
            });
        }
    };

    function rcvcustoms_e_save() {
        const mbctype = $("#rcvcustoms_typedoc").val()
        const mdate = $("#rcvcustoms_dd").val()
        const mrcvdate = $("#rcvcustoms_rcvdate").val()
        const mtpb = $("#rcvcustoms_typetpb").val()
        const mdo = $("#rcvcustoms_docnoorigin").val()
        const mconaNum = document.getElementById('rcvcustoms_contractnum').value
        const mbisgrup = document.getElementById('rcvcustoms_businessgroup').value
        const mnoaju = $("#rcvcustoms_noaju").val()
        const mregno = $("#rcvcustoms_regno").val()
        const m_nw = numeral($("#rcvcustoms_NW").val()).value()
        const m_gw = numeral($("#rcvcustoms_GW").val()).value()
        const m_amt = numeral($("#rcvcustoms_amount").val()).value()
        let mkppbc = $("#rcvcustoms_kppbc").val()
        let zstsrcv = document.getElementById('rcvcustoms_zsts').value
        let ar_nourut = [];
        let ar_pono = [];
        let ar_item = [];
        let ar_qty = [];
        let ar_price = [];
        let ar_amt = [];
        let ar_wh = [];
        let ar_grlno = [];
        let ar_hscode = [];
        let ar_bm = [];
        let ar_ppn = [];
        let ar_pph = [];
        let perNW = [];
        if(mkppbc.value == '')
        {
            $("#RCVCUSTOMS_PROGRESS").modal('hide');
            alertify.warning('KPPBC is required')
            rcvcustoms_kppbc.focus()
            return
        }

        let tables = $("#rcvcustoms_tbl tbody");
        tables.find('tr').each(function(i) {
            let $tds = $(this).find('td'),
                rnourut = $tds.eq(1).text(),
                rpo = $tds.eq(2).text(),
                ritem = $tds.eq(3).text(),
                rqty = $tds.eq(5).text(),
                rprc = $tds.eq(7).text(),
                ramt = $tds.eq(8).text(),
                _perNW = $tds.eq(9).text();
                rwh = $tds.eq(10).text();
            rgrlno = $tds.eq(11).text();
            rhscode = $tds.eq(12).text();
            rbm = $tds.eq(13).text();
            rppn = $tds.eq(14).text();
            rpph = $tds.eq(15).text();
            if (ritem != '') {
                ar_nourut.push(rnourut);
                ar_pono.push(rpo.trim());

                ar_item.push(ritem);
                ar_qty.push(numeral(rqty).value());
                ar_price.push(rprc);
                ar_amt.push(ramt);
                ar_wh.push(rwh);
                ar_grlno.push(rgrlno);
                ar_hscode.push(rhscode);
                ar_bm.push(rbm);
                ar_ppn.push(rppn);
                ar_pph.push(rpph);
                perNW.push(_perNW);
            }
        });


        $.ajax({
            type: "post",
            url: "<?=base_url('RCV/updateBCDoc')?>",
            data: {
                inbctype: mbctype,
                inbcno: mnoaju,
                inbcdate: mdate,
                inrcvdate: mrcvdate,
                inwh: ar_wh,
                inpo: ar_pono,
                indo: mdo,
                inconaNum: mconaNum,
                intpb: mtpb,
                inregno: mregno,
                inNW: m_nw,
                inGW: m_gw,
                insupcd: rcvcustoms_suppliercode,
                initm: ar_item,
                inqty: ar_qty,
                inprice: ar_price,
                inamt: ar_amt,
                inttl_amt: m_amt,
                inkppbc: mkppbc,
                ingrlno: ar_grlno,
                inhscode: ar_hscode,
                instsrcv: zstsrcv,
                inbm: ar_bm,
                inppn: ar_ppn,
                inpph: ar_pph,
                innomorurut: ar_nourut,
                inbisgrup: mbisgrup,
                perNW: perNW,
            },
            dataType: "json",
            success: function(response) {
                alertify.message(response[0].msg);
                let mitem = document.getElementById('rcvcustoms_docnoorigin').value;
                MGGetDODetail(mitem.trim());
                WMSGetDODetail(mitem.trim());
                if (response[0].cd == '0') {
                    location.reload();
                }
                $("#RCVCUSTOMS_PROGRESS").modal('hide');
            },
            error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow);
                $("#RCVCUSTOMS_PROGRESS").modal('hide');
            }
        });
    }
    $("#rcvcustoms_save").click(function() {
        let mytable = document.getElementById('rcvcustoms_tbl').getElementsByTagName('tbody')[0]
        const mtrlength = mytable.getElementsByTagName('tr').length
        if (mtrlength <= 5) {
            for (let i = 0; i < mtrlength; i++) {
                const nomorUrut = mytable.rows[i].cells[1].innerText.trim().replace(/\n+/g, '')
                if (nomorUrut.length === 0) {
                    alertify.warning("Nomor Urut is required")
                    mytable.rows[i].cells[1].focus()
                    return
                }
                if (numeral(nomorUrut).value() <= 0) {
                    alertify.warning("Nomor Urut is not valid")
                    mytable.rows[i].cells[1].focus()
                    return
                }
            }
        }
        let dono = document.getElementById('rcvcustoms_docnoorigin').value;
        let mnoaju = document.getElementById('rcvcustoms_noaju').value;
        let mnopen = document.getElementById('rcvcustoms_regno').value;

        if (dono.trim() == '') {
            alertify.warning('Please select DO first');
            document.getElementById('rcvcustoms_btnmod').focus();
            return;
        }
        if (mnoaju.trim().length != 26) {
            alertify.warning('NoAju must be 26 digit');
            rcvcustoms_noaju.focus()
            return;
        }
        if (rcvcustoms_zsts.value === '-' && rcvcustoms_typedoc.value !== '23') {
            alertify.warning('Receiving Status could not be empty')
            rcvcustoms_zsts.focus()
            return
        }
        if (mnoaju.substr(4, 2) != rcvcustoms_typedoc.value) {
            alertify.warning("Document type is not equal with nomor aju")
            return
        }
        if(rcvcustoms_kppbc.value == '')
        {
            $("#RCVCUSTOMS_PROGRESS").modal('hide');
            alertify.warning('KPPBC is required')
            rcvcustoms_kppbc.focus()
            return
        }        

        let isValidationOk = true

        if (mnopen.trim().length == 6) {
            let tables = $("#rcvcustoms_tbl tbody");
            tables.find('tr').each(function(i) {
                let $tds = $(this).find('td'),
                    rnourut = $tds.eq(1).text(),
                    rpo = $tds.eq(3).text(),
                    rsup = $tds.eq(5).text(),
                    rcurr = $tds.eq(7).text(),
                    ritem = $tds.eq(8).text(),
                    rqty = $tds.eq(10).text(),
                    rprc = $tds.eq(12).text(),
                    ramt = $tds.eq(13).text(),
                    rwh = $tds.eq(14).text();
                rgrlno = $tds.eq(15).text();
                rhscode = $tds.eq(16).text();
                rbm = $tds.eq(17).text().trim();
                rppn = $tds.eq(18).text().trim();
                rpph = $tds.eq(19).text().trim();
                if (ritem != '') {
                    if(isNaN(rbm)) {
                        isValidationOk = false
                        $tds.eq(17).focus()
                        alertify.warning('BM is invalid')
                        return
                    }
                    if(isNaN(rppn)) {
                        isValidationOk = false
                        $tds.eq(18).focus()
                        alertify.warning('PPN is invalid')
                        return
                    }
                    if(isNaN(rpph)) {
                        isValidationOk = false
                        $tds.eq(19).focus()
                        alertify.warning('PPH is invalid')
                        return
                    }
                }
            });

            if(!isValidationOk){
                return
            }
            let mymodal = new bootstrap.Modal(document.getElementById("RCVCUSTOMS_PROGRESS"), {
                backdrop: 'static',
                keyboard: false
            });
            mymodal.show();
        } else {
            alertify.warning('NoPen must be 6 digit');
            document.getElementById('rcvcustoms_regno').focus()
            return;
        }
    });

    $('#rcvcustoms_supfilter').change(function() {
        document.getElementById('rcvcustoms_txt_search').focus();
    });

    $("#rcvcustoms_datefilter").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    })

    $("#rcvcustoms_ModalTxtTglpenNew").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    })

    function rcvcustoms_btn_filterdate_e_click() {
        document.getElementById('rcvcustoms_datefilter').value = "";
        document.getElementById('rcvcustoms_txt_search').focus()
    }

    $("#rcvcustoms_dd").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    })
    $("#rcvcustoms_rcvdate").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    })

    function getdocBC(pdo) {
        $.ajax({
            type: "get",
            url: "<?=base_url('RCV/getBCField')?>",
            data: {
                indo: pdo
            },
            dataType: "json",
            success: function(response) {
                if (response.length > 0) {
                    $("#rcvcustoms_typedoc").val(response[0].RCV_BCTYPE)
                    $("#rcvcustoms_docnoorigin").val(response[0].RCV_BCNO)
                    $("#rcvcustoms_typetpb").val(response[0].RCV_TPB)
                }
            },
            error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow);
            }
        });
    }


    $('#rcvcustoms_tbl tbody').on('click', 'td', function() {
        rcvcustoms_selcol = $(this).index();
    });
    $("#rcvcustoms_btnmod").click(function(e) {
        e.preventDefault();
        $("#RCVCUSTOMS_DTLMOD").modal('show');
    });
    $("#RCVCUSTOMS_DTLMOD").on('shown.bs.modal', function() {
        $("#rcvcustoms_txt_search").focus();
    });
    $("#rcvcustoms_txt_search").keypress(function(e) {
        if (e.key === 'Enter') {
            const mval = $(this).val()
            const mby = document.getElementById('rcvcustoms_rad_do').checked ? document.getElementById('rcvcustoms_rad_do').value : document.getElementById('rcvcustoms_rad_item').value
            const mpermonth = document.getElementById('rcvcustoms_ck').checked ? 'y' : 'n'
            const myear = document.getElementById('rcvcustoms_year').value
            const mmonth = document.getElementById('rcvcustoms_monthfilter').value
            const msuplier = document.getElementById('rcvcustoms_supfilter').value
            const mdatefilter = document.getElementById('rcvcustoms_datefilter').value
            const mbisgrup = document.getElementById('rcvcustoms_businessgroup').value

            $("#lblinfo_rcvcustoms_tbldono").text("Please wait...")
            $.ajax({
                type: "get",
                url: "<?=base_url('RCV/MGGetDO')?>",
                data: {
                    inid: mval,
                    inby: mby,
                    inpermonth: mpermonth,
                    inyear: myear,
                    inmonth: mmonth,
                    insup: msuplier,
                    indatefilter: mdatefilter,
                    inbisgrup: mbisgrup
                },
                dataType: "json",
                success: function(response) {
                    let mydes = document.getElementById("rcvcustoms_divku_search");
                    let myfrag = document.createDocumentFragment();
                    let cln = rcvcustoms_tbldono.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("rcvcustoms_tbldono");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    tableku2.innerHTML = '';
                    let ttlrows = response.length;
                    let tohtml = '';
                    for (let i = 0; i < ttlrows; i++) {
                        newrow = tableku2.insertRow(-1)
                        newrow.onclick = function(event) {
                            document.getElementById('rcvcustoms_businessgroup').value = response[i].PGRN_BSGRP
                            document.getElementById('rcvcustoms_invoice').value = response[i].PNGR_INVNO
                            $("#rcvcustoms_docnoorigin").val(response[i].PGRN_SUPNO);
                            MGGetDODetail(response[i].PGRN_SUPNO);

                            $("#RCVCUSTOMS_DTLMOD").modal('hide');
                            WMSGetDODetail(response[i].PGRN_SUPNO);

                            rcvcustoms_supplier_name.value = response[i].MSUP_SUPNM
                            rcvcustoms_supplier_currency.value = response[i].MSUP_SUPCR
                        }
                        newcell = newrow.insertCell(0)
                        if(response[i].PNGR_INVNO.trim().length > 1) {
                            newcell.classList.add('table-success')
                        }
                        newcell.innerHTML = response[i].PGRN_SUPNO

                        newcell = newrow.insertCell(-1)
                        newcell.innerHTML = response[i].PGRN_RCVDT
                        newcell = newrow.insertCell(-1)
                        newcell.innerHTML = response[i].MSUP_SUPNM

                        newcell = newrow.insertCell(-1)
                        newcell.classList.add('text-end')
                        newcell.innerHTML = numeral(response[i].TTLITEMIN / response[i].TTLITEM * 100).format(',') + "% synchronized"

                        newcell = newrow.insertCell(-1)
                        newcell.innerHTML = response[i].RCV_HSCD
                        newcell = newrow.insertCell(-1)
                        newcell.innerHTML = response[i].RCV_BM
                        newcell = newrow.insertCell(-1)
                        newcell.innerHTML = response[i].RCV_PPN
                        newcell = newrow.insertCell(-1)
                        newcell.innerHTML = response[i].RCV_PPH
                        newcell = newrow.insertCell(-1)
                        newcell.innerHTML = response[i].PGRN_BSGRP
                        newcell = newrow.insertCell(-1)
                        newcell.innerHTML = response[i].PNGR_INVNO
                    }
                    mydes.innerHTML = ''
                    mydes.appendChild(myfrag)
                    $("#lblinfo_rcvcustoms_tbldono").text("");
                },
                error: function(xhr, xopt, xthrow) {
                    alertify.error(xthrow);
                }
            });
        }
    });
    var rcvcustoms_rcv_status = '-'

    function vrcv_e_getstsrcv(mid, pval) {
        $.ajax({
            type: "get",
            url: "<?=base_url('RCV/zgetsts_rcv')?>",
            data: {
                inid: mid,
                instst: pval
            },
            dataType: "json",
            success: function(response) {
                let str = '<option value="-">-</option>';
                if (response.status[0].cd != '0') {
                    let ttlrows = response.data.length;
                    for (let i = 0; i < ttlrows; i++) {
                        str += '<option value="' + response.data[i].KODE_TUJUAN_PENGIRIMAN + '">' + response.data[i].URAIAN_TUJUAN_PENGIRIMAN + '</option>';
                    }
                }
                document.getElementById('rcvcustoms_zsts').innerHTML = str;
                document.getElementById('rcvcustoms_zsts').value = response.status[0].reff;

            },
            error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow);
            }
        });
    }

    function WMSGetDODetail(pdo) {
        const mbisgrup = document.getElementById('rcvcustoms_businessgroup').value
        $.ajax({
            type: "get",
            url: "<?=base_url('RCV/WMSGetDODetail')?>",
            data: {
                indo: pdo,
                inbisgrup: mbisgrup
            },
            dataType: "json",
            success: function(response) {
                let ttlrows = response.length;
                if (ttlrows > 0) {
                    let m_nw = '';
                    let m_gw = '';
                    let m_ttlamt = '';
                    let mstsrcv = '-';

                    document.getElementById('rcvcustoms_contractnum').value = response[0].RCV_CONA
                    if (response[0].RCV_ZSTSRCV) {
                        mstsrcv = response[0].RCV_ZSTSRCV
                        vrcv_e_getstsrcv(response[0].RCV_BCTYPE, mstsrcv)
                    } else {
                        mstsrcv = '-'
                    }
                    rcvcustoms_rcv_status = mstsrcv
                    for (let i = 0; i < ttlrows; i++) {
                        if (response[i].RCV_NW) {
                            m_nw = response[i].RCV_NW.substring(0, 1) == '.' ? '0' + response[i].RCV_NW : response[i].RCV_NW;
                        } else {
                            m_nw = 0;
                        }
                        if (response[i].RCV_GW) {
                            m_gw = response[i].RCV_GW.substring(0, 1) == '.' ? '0' + response[i].RCV_GW : response[i].RCV_GW;
                        } else {
                            m_gw = 0;
                        }
                        if (response[i].RCV_TTLAMT) {
                            m_ttlamt = response[i].RCV_TTLAMT.substring(0, 1) == '.' ? '0' + response[i].RCV_TTLAMT : response[i].RCV_TTLAMT;
                        } else {
                            m_ttlamt = 0;
                        }

                    }

                    $("#rcvcustoms_typedoc").val(response[0].RCV_BCTYPE);
                    $("#rcvcustoms_noaju").val(response[0].RCV_RPNO);
                    $("#rcvcustoms_regno").val(response[0].RCV_BCNO);
                    $("#rcvcustoms_dd").datepicker('update', response[0].RCV_RPDATE);
                    $("#rcvcustoms_rcvdate").datepicker('update', response[0].RCV_RCVDATE);
                    $("#rcvcustoms_typetpb").val(response[0].RCV_TPB);
                    $("#rcvcustoms_kppbc").val(response[0].RCV_KPPBC);
                    $("#rcvcustoms_NW").val(m_nw);
                    $("#rcvcustoms_GW").val(m_gw);

                    if(rcvcustoms_noaju.value.trim().length==0){
                        rcvcustoms_save.disabled = false
                        // rcvcustoms_hscode.disabled = false
                    } else {
                        rcvcustoms_save.disabled = true
                        // rcvcustoms_hscode.disabled = true
                    }
                } else {
                    $("#rcvcustoms_typedoc").val('');
                    $("#rcvcustoms_noaju").val('');
                    $("#rcvcustoms_regno").val('');
                    $("#rcvcustoms_typetpb").val('');
                    $("#rcvcustoms_kppbc").val('');
                    $("#rcvcustoms_NW").val('');
                    $("#rcvcustoms_GW").val('');

                    rcvcustoms_save.disabled = false
                    // rcvcustoms_hscode.disabled = false
                }
            },
            error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow);
            }
        });
    }

    function MGGetDODetail(pdo) {
        $("#rcvcustoms_lbltbl").text("Please wait . . .");
        const mbisgrup = document.getElementById('rcvcustoms_businessgroup').value
        $.ajax({
            type: "get",
            url: "<?=base_url('RCV/MGGetDODetail')?>",
            data: {
                indo: pdo,
                inbisgrup: mbisgrup
            },
            dataType: "json",
            success: function(response) {
                let ttlrows = response.length;
                let tohtml = '';
                let strprice = '';
                let stramt = '';
                let ttlAmount = 0
                let ttlQty = 0
                $("#rcvcustoms_lbltbl").text(ttlrows + " row(s) found");
                for (let i = 0; i < ttlrows; i++) {
                    rcvcustoms_suppliercode = response[i].PGRN_SUPCD
                    strprice = response[i].PGRN_PRPRC;
                    if (strprice.substring(0, 1) == '.') {
                        strpirce = '0' + response[i].PGRN_PRPRC;
                    } else {
                        strpirce = response[i].PGRN_PRPRC;
                    }
                    stramt = response[i].PGRN_AMT;
                    if (stramt.substring(0, 1) == '.') {
                        stramt = '0' + response[i].PGRN_AMT;
                    } else {
                        stramt = response[i].PGRN_AMT;
                    }
                    ttlAmount += Number(stramt)
                    ttlQty += Number(response[i].PGRN_ROKQT)
                    tohtml += "<tr style='cursor:pointer'>" +
                        "<td>" + response[i].SYNC_STS + "</td>" +
                        "<td contenteditable='true'>" + response[i].NOURUT + "</td>" +
                        "<td class='d-none'>" + response[i].PGRN_PONO + "</td>" +                        
                        "<td>" + response[i].PGRN_ITMCD + "</td>" +
                        "<td>" + response[i].MITM_ITMD1 + "</td>" +
                        "<td class='text-end'>" + numeral(response[i].PGRN_ROKQT).format(',') + "</td>" +
                        "<td>" + response[i].PGRN_POUOM + "</td>" +
                        "<td class='text-end'>" + strpirce + "</td>" +
                        "<td class='text-end'>" + stramt + "</td>" +
                        "<td class='text-end receive-detail-nw' contenteditable='true'>"+ response[i].RCV_PRNW +"</td>" +
                        "<td class='d-none'>" + response[i].PGRN_LOCCD + "</td>" +
                        "<td class='d-none'>" + response[i].PGRN_GRLNO + "</td>" +
                        "<td contenteditable='true'>" + response[i].HSCD + "</td>" +
                        "<td contenteditable='true'>" + response[i].BEAMASUK + "</td>" +
                        "<td contenteditable='true'>" + response[i].PPN + "</td>" +
                        "<td contenteditable='true'>" + response[i].PPH + "</td>" +
                        "</tr>";
                }
                $("#rcvcustoms_tbl tbody").html(tohtml);
                document.getElementById('rcvcustoms_amount').value = numeral(ttlAmount).format('0,0.00')
                document.getElementById('rcvcustoms_qty').value = numeral(ttlQty).format('0,0')
                Inputmask({
                    'alias': 'decimal',
                    'groupSeparator': ',',
                }).mask(document.getElementsByClassName("receive-detail-nw"));
            },
            error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow);
                $("#rcvcustoms_lbltbl").text("");
            }
        });
    }

    function rcvuctoms_getdetail1(pdata) {
        $("#RCVCUSTOMS_DTLMOD_1").modal('hide')
        $.ajax({
            type: "GET",
            url: "<?=base_url('RCV/GetDODetail1')?>",
            data: {
                do: pdata.pdo,
                supcd: pdata.psupcd,
                nomorAju: pdata.pAJU
            },
            dataType: "json",
            success: function(response) {
                let ttlrows = response.data.length
                let mydes = document.getElementById("rcvcustoms_divku_1")
                let myfrag = document.createDocumentFragment()
                let mtabel = document.getElementById("rcvcustoms_tbl_1")
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln)
                let tabell = myfrag.getElementById("rcvcustoms_tbl_1")
                let tabelHead = tabell.getElementsByTagName('thead')[0]
                let tableku2 = tabell.getElementsByTagName("tbody")[0]
                let newrow, newcell, newText
                let myitmttl = 0;
                tableku2.innerHTML = ''
                let ttlamount = 0
                for (let i = 0; i < ttlrows; i++) {
                    newrow = tableku2.insertRow(-1)
                    newrow.onclick = (event) => {
                        rcvcustoms_tbl_tbody_tr_eC(event)
                    }
                    newcell = newrow.insertCell(0)
                    newcell.classList.add('d-none')
                    newcell.innerHTML = response.data[i].RCV_GRLNO
                    newcell = newrow.insertCell(1)
                    newcell.contentEditable = true
                    newcell.innerHTML = response.data[i].RCV_ZNOURUT
                    newcell = newrow.insertCell(2)
                    newcell.contentEditable = true
                    newcell.innerHTML = response.data[i].RCV_PO

                    newcell = newrow.insertCell(3)
                    newcell.classList.add('text-center')
                    if(response.data[i].PO_SUBJECT) {
                        if(['O', 'E'].includes(response.data[i].PO_SUBJECT)) {
                            newcell.innerHTML = `<span class="badge bg-warning fa-beat">ASSET 💰</span>`
                        } else {
                            newcell.innerHTML = `<span class="badge bg-info">NON ASSET</span>`
                        }
                    }

                    newcell = newrow.insertCell(4)
                    newcell.title = `double click for showing search dialog`
                    newcell.classList.add('table-info')
                    newcell.onclick = () => {
                        $("#RCVCUSTOMS_INTERNALITEM").modal('show')
                    }
                    newcell.style.cssText = "cursor:pointer"
                    newcell.innerHTML = response.data[i].RCV_ITMCD
                    newcell = newrow.insertCell(5)
                    newcell.classList.add('table-info')
                    newcell.innerHTML = response.data[i].MITM_ITMD1
                    newcell = newrow.insertCell(6)
                    newcell.contentEditable = true
                    newcell.classList.add('text-end')
                    newcell.innerHTML = response.data[i].RCV_QTY
                    newcell = newrow.insertCell(7)
                    newcell.classList.add('table-info')
                    newcell.innerHTML = response.data[i].MITM_STKUOM
                    newcell = newrow.insertCell(8)
                    newcell.classList.add('text-end')
                    newcell.contentEditable = true
                    newcell.innerHTML = response.data[i].RCV_PRPRC
                    newcell = newrow.insertCell(9)
                    newcell.classList.add('text-end', 'table-info')
                    newcell.innerHTML = numeral(response.data[i].RCV_PRPRC * response.data[i].RCV_QTY).format('0,0.00')
                    newcell = newrow.insertCell(10)
                    newcell.classList.add('text-end')
                    newcell.title = tabelHead.rows[0].cells[9].innerText
                    newcell.contentEditable = true
                    newcell.innerHTML = response.data[i].RCV_PRNW
                    newcell = newrow.insertCell(11)
                    newcell.classList.add('text-end')
                    newcell.title = tabelHead.rows[0].cells[10].innerText
                    newcell.contentEditable = true
                    newcell.innerHTML = response.data[i].RCV_PRGW
                    newcell = newrow.insertCell(12)
                    newcell.title = tabelHead.rows[0].cells[11].innerText
                    newcell.contentEditable = true
                    newcell.innerHTML = response.data[i].RCV_HSCD
                    newcell = newrow.insertCell(13)
                    newcell.title = tabelHead.rows[0].cells[12].innerText
                    newcell.classList.add('text-end')
                    newcell.contentEditable = true
                    newcell.innerHTML = response.data[i].RCV_BM
                    newcell = newrow.insertCell(14)
                    newcell.classList.add('text-end')
                    newcell.contentEditable = true
                    newcell.innerHTML = response.data[i].RCV_PPN
                    newcell = newrow.insertCell(15)
                    newcell.classList.add('text-end')
                    newcell.contentEditable = true
                    newcell.innerHTML = response.data[i].RCV_PPH

                    newcell = newrow.insertCell(16)
                    newcell.style.cssText = "cursor:pointer"
                    newcell.classList.add('text-center', 'table-info')
                    newcell.title = 'to Location'
                    newcell.onclick = (event) => {
                        rcvcustoms_modal_cmb_locTo.value = event.target.innerText
                        $("#RCVCUSTOMS_LOCATION").modal('show')
                    }
                    newcell.innerHTML = response.data[i].RCV_WH
                    ttlamount += (response.data[i].RCV_PRPRC * response.data[i].RCV_QTY)
                }
                mydes.innerHTML = ''
                mydes.appendChild(myfrag)
                document.getElementById('rcvcustoms_amount_1').value = ttlamount
                //load data kemasan
                ttlrows = response.pkg.length
                mydes = document.getElementById("rcvcustoms_pkg_tbl_div")
                myfrag = document.createDocumentFragment()
                mtabel = document.getElementById("rcvcustoms_pkg_tbl")
                cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln)
                tabell = myfrag.getElementById("rcvcustoms_pkg_tbl")
                tabelHead = tabell.getElementsByTagName('thead')[0]
                tableku2 = tabell.getElementsByTagName("tbody")[0]
                tableku2.innerHTML = ''
                if (ttlrows > 0) {
                    rcvcustoms_pkg_nomor.value = response.pkg[0].RCVPKG_DOC
                }
                for (let i = 0; i < ttlrows; i++) {
                    newrow = tableku2.insertRow(-1)
                    newrow.addEventListener("contextmenu", function(e) {
                        rcvcustoms_rowsObj.ridx = e.target.parentNode.rowIndex
                        rcvcustoms_rowsObj.ids = e.target.parentNode.cells[0].innerText
                        rcvcustoms_contextMenu.open(e)
                        e.preventDefault()
                    })
                    newcell = newrow.insertCell(0)
                    newcell.classList.add('d-none')
                    newcell.innerHTML = response.pkg[i].RCVPKG_LINE
                    newcell = newrow.insertCell(1)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = response.pkg[i].RCVPKG_JUMLAH_KEMASAN
                    newcell.contentEditable = true
                    newcell = newrow.insertCell(2)
                    newcell.innerHTML = response.pkg[i].RCVPKG_KODE_JENIS_KEMASAN
                    newcell = newrow.insertCell(3)
                    newcell.innerHTML = response.pkg[i].URAIAN_KEMASAN
                }
                mydes.innerHTML = ''
                mydes.appendChild(myfrag)
            }
        });
    }

    function rcvuctoms_getdetail2(pdo) {
        $("#RCVCUSTOMS_DTLMOD_2").modal('hide')
        $.ajax({
            type: "GET",
            url: "<?=base_url('RCV/GetDODetail2')?>",
            data: {
                do: pdo
            },
            dataType: "json",
            success: function(response) {
                const ttlrows = response.data.length
                let mydes = document.getElementById("rcvcustoms_divku_2")
                let myfrag = document.createDocumentFragment()
                let mtabel = document.getElementById("rcvcustoms_tbl_2")
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln)
                let tabell = myfrag.getElementById("rcvcustoms_tbl_2")
                let tableku2 = tabell.getElementsByTagName("tbody")[0]
                let newrow, newcell, newText
                let myitmttl = 0;
                tableku2.innerHTML = ''
                let ttlamount = 0
                for (let i = 0; i < ttlrows; i++) {
                    newrow = tableku2.insertRow(-1)
                    newrow.onclick = (event) => {
                        rcvcustoms_tbl_tbody_tr_eC(event);
                    }
                    newcell = newrow.insertCell(0)
                    newcell.classList.add('d-none')
                    newcell.innerHTML = response.data[i].RCV_GRLNO
                    newcell = newrow.insertCell(1)
                    newcell.contentEditable = true
                    newcell.innerHTML = response.data[i].RCV_ZNOURUT
                    newcell = newrow.insertCell(2)
                    newcell.contentEditable = true
                    newcell.innerHTML = response.data[i].RCV_PO
                    newcell = newrow.insertCell(3)
                    newcell.title = `double click for showing search dialog`
                    newcell.classList.add('table-info')
                    newcell.onclick = () => {
                        $("#RCVCUSTOMS_INTERNALITEM").modal('show')
                    }
                    newcell.style.cssText = "cursor:pointer"
                    newcell.innerHTML = response.data[i].RCV_ITMCD
                    newcell = newrow.insertCell(4)
                    newcell.classList.add('table-info')
                    newcell.innerHTML = response.data[i].MITM_ITMD1
                    newcell = newrow.insertCell(5)
                    newcell.contentEditable = true
                    newcell.classList.add('text-end')
                    newcell.innerHTML = response.data[i].RCV_QTY
                    newcell = newrow.insertCell(6)
                    newcell.classList.add('table-info')
                    newcell.innerHTML = response.data[i].MITM_STKUOM
                    newcell = newrow.insertCell(7)
                    newcell.classList.add('text-end')
                    newcell.contentEditable = true
                    newcell.innerHTML = response.data[i].RCV_PRPRC
                    newcell = newrow.insertCell(8)
                    newcell.classList.add('text-end')
                    newcell.classList.add('table-info')
                    newcell.innerHTML = numeral(response.data[i].RCV_PRPRC * response.data[i].RCV_QTY).format('0,0.00')
                    newcell = newrow.insertCell(9)
                    newcell.contentEditable = true
                    newcell.innerHTML = response.data[i].RCV_HSCD
                    newcell = newrow.insertCell(10)
                    newcell.classList.add('text-end')
                    newcell.contentEditable = true
                    newcell.innerHTML = response.data[i].RCV_BM
                    newcell = newrow.insertCell(11)
                    newcell.classList.add('text-end')
                    newcell.contentEditable = true
                    newcell.innerHTML = response.data[i].RCV_PPN
                    newcell = newrow.insertCell(12)
                    newcell.classList.add('text-end')
                    newcell.contentEditable = true
                    newcell.innerHTML = response.data[i].RCV_PPH
                    ttlamount += (response.data[i].RCV_PRPRC * response.data[i].RCV_QTY)
                }
                mydes.innerHTML = ''
                mydes.appendChild(myfrag)
                document.getElementById('rcvcustoms_amount_2').value = numeral(ttlamount).format('0,0.00')
            }
        });
    }

    function rcvcustoms_initReceivingStatus(bctype) {
        $.ajax({
            type: "get",
            url: "<?=base_url('RCV/zgetsts_rcv')?>",
            data: {
                inid: bctype
            },
            dataType: "json",
            success: function(response) {
                let str = '<option value="-">-</option>';
                if (response.status[0].cd != '0') {
                    let ttlrows = response.data.length;
                    for (let i = 0; i < ttlrows; i++) {
                        str += '<option value="' + response.data[i].KODE_TUJUAN_PENGIRIMAN + '">' + response.data[i].URAIAN_TUJUAN_PENGIRIMAN + '</option>';
                    }
                }
                document.getElementById('rcvcustoms_zsts').innerHTML = str;

            },
            error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow);
            }
        });
    }
    $("#rcvcustoms_typedoc").change(function() {
        rcvcustoms_initReceivingStatus(rcvcustoms_typedoc.value)
        rcvcustoms_zsts.focus()
    })

    $("#rcvcustoms_typedoc_2").change(function() {
        const mid = document.getElementById('rcvcustoms_typedoc_2').value;
        $.ajax({
            type: "get",
            url: "<?=base_url('RCV/zgetsts_rcv')?>",
            data: {
                inid: mid
            },
            dataType: "json",
            success: function(response) {
                let str = '<option value="-">-</option>';
                if (response.status[0].cd != '0') {
                    let ttlrows = response.data.length;
                    for (let i = 0; i < ttlrows; i++) {
                        str += '<option value="' + response.data[i].KODE_TUJUAN_PENGIRIMAN + '">' + response.data[i].URAIAN_TUJUAN_PENGIRIMAN + '</option>';
                    }
                }
                document.getElementById('rcvcustoms_zsts_2').innerHTML = str
                document.getElementById('rcvcustoms_zsts_2').focus()
            },
            error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow);
            }
        });
    });

    $('#rcvcustoms_dd').datepicker()
        .on('changeDate', function(e) {
            $("#rcvcustoms_typetpb").focus();
        });
    $('#rcvcustoms_datefilter').datepicker()
        .on('changeDate', function(e) {
            $("#rcvcustoms_txt_search").focus();
        });
    $("#rcvcustoms_typetpb").change(function() {
        $("#rcvcustoms_NW").focus();
    });

    function rcvcustoms_txtsearchPK_eKP(e) {
        if (e.key === 'Enter') {
            const msearchKey = document.getElementById('rcvcustoms_txtsearchPK').value.trim()
            document.getElementById('rcvcustoms_tblbox').getElementsByTagName('tbody')[0].innerHTML = ''
            $.ajax({
                type: "GET",
                url: "<?=base_url('MCONA/search')?>",
                data: {
                    searchBy: '0',
                    searchKey: msearchKey
                },
                dataType: "JSON",
                success: function(response) {
                    const ttlrows = response.data.length
                    document.getElementById('rcvcustoms_tblbox_lblinfo').innerHTML = `${ttlrows} row(s) found`
                    if (response.status[0].cd === 1) {
                        let mydes = document.getElementById("rcvcustoms_tblbox_div")
                        let myfrag = document.createDocumentFragment()
                        let mtabel = document.getElementById("rcvcustoms_tblbox")
                        let cln = mtabel.cloneNode(true);
                        myfrag.appendChild(cln)
                        let tabell = myfrag.getElementById("rcvcustoms_tblbox")
                        let tableku2 = tabell.getElementsByTagName("tbody")[0]
                        let newrow, newcell, newText
                        let myitmttl = 0;
                        tableku2.innerHTML = ''
                        for (let i = 0; i < ttlrows; i++) {
                            newrow = tableku2.insertRow(-1)
                            newcell = newrow.insertCell(0)
                            newcell.style.cssText = "cursor:pointer"
                            newcell.onclick = () => {
                                rcvcustoms_load_data(response.data[i].MCONA_DOC)
                            }
                            newcell.innerHTML = response.data[i].MCONA_DOC
                        }
                        mydes.innerHTML = '';
                        mydes.appendChild(myfrag);
                    } else {
                        alertify.message(response.status[0].msg)
                    }
                },
                error: function(xhr, xopt, xthrow) {
                    alertify.error(xthrow);
                }
            });
        }
    }

    function rcvcustoms_load_data(pdoc) {
        document.getElementById('rcvcustoms_contractnum').value = pdoc
        $('#RCVCUSTOMS_CONA').modal('hide')
    }
</script>
<!-- script fg rtn -->
<script>
    //set default value
    document.getElementById('rcvcustoms_fg_kppbc').value = '050900'
    document.getElementById('rcvcustoms_fg_typedoc').value = '27'
    rcvcustoms_fg_typdoc_oCH('6')
    document.getElementById('rcvcustoms_fg_monthfilter').value = rcvcustoms_currentmonth
    //end

    $("#rcvcustoms_fg_divku").css('height', $(window).height() -
        document.getElementById('rcvcustoms_stack0').offsetHeight -
        document.getElementById('rcvcustoms_stack1').offsetHeight -
        document.getElementById('rcvcustoms_stack2').offsetHeight -
        document.getElementById('rcvcustoms_stack3').offsetHeight -
        document.getElementById('rcvcustoms_stack4').offsetHeight -
        document.getElementById('rcvcustoms_stack5').offsetHeight -
        150);

    var rcvcustoms_fg_selcol = '0';

    $("#rcvcustoms_fg_monthfilter").change(function(e) {
        document.getElementById('rcvcustoms_fg_txt_search').focus();
    });
    $("#rcvcustoms_fg_ck").click(function(e) {
        document.getElementById('rcvcustoms_fg_monthfilter').disabled = !document.getElementById('rcvcustoms_fg_ck').checked;
        document.getElementById('rcvcustoms_fg_year').disabled = !document.getElementById('rcvcustoms_fg_ck').checked;
        document.getElementById('rcvcustoms_fg_txt_search').focus();
    });
    document.getElementById('rcvcustoms_fg_year').value = new Date().getFullYear();


    $("#rcvcustoms_fg_PROGRESS").on('shown.bs.modal', function() {
        rcvcustoms_fg_e_save();
    });

    $("#rcvcustoms_fg_btn_download").click(function(e) {
        $.ajax({
            type: "get",
            url: "<?=base_url('RCV/getlinkitemtemplate')?>",
            dataType: "text",
            success: function(response) {
                window.open(response, '_blank');
                alertify.message("<i>Start downloading...</i>");
            },
            error: function(xhr, ajaxOptions, throwError) {
                alert(throwError);
            }
        });
    });

    var rcvcustoms_fg_ttlxls = 0;
    var rcvcustoms_fg_ttlxls_savd = 0;

    function rcvcustoms_fg_e_save() {
        var mbctype = $("#rcvcustoms_fg_typedoc").val();
        var mdate = $("#rcvcustoms_fg_dd").val();
        var mrcvdate = $("#rcvcustoms_fg_rcvdate").val();
        var mtpb = $("#rcvcustoms_fg_typetpb").val();
        var mdo = $("#rcvcustoms_fg_docnoorigin").val();
        var mnoaju = $("#rcvcustoms_fg_noaju").val();
        var mregno = $("#rcvcustoms_fg_regno").val();
        let m_nw = numeral($("#rcvcustoms_fg_NW").val()).value();
        let m_gw = numeral($("#rcvcustoms_fg_GW").val()).value();
        let m_amt = numeral($("#rcvcustoms_fg_amount").val()).value();
        let mkppbc = $("#rcvcustoms_fg_kppbc").val();
        let zstsrcv = document.getElementById('rcvcustoms_fg_zsts').value;
        const cona = document.getElementById('rcvcustoms_fg_contractnum').value
        const invoice = document.getElementById('rcvcustoms_fg_invoice').value
        var ar_nourut = [];
        var ar_pono = [];
        var ar_item = [];
        var ar_qty = [];
        var ar_price = [];
        var ar_amt = [];
        let ar_wh = [];
        let ar_grlno = [];
        let ar_hscode = [];
        let ar_bm = [];
        let ar_ppn = [];
        let ar_pph = [];
        let perNW = [];

        let tables = $("#rcvcustoms_fg_tbl tbody")
        tables.find('tr').each(function(i) {
            let $tds = $(this).find('td'),
                rnourut = $tds.eq(1).text(),
                rpo = $tds.eq(2).text(),
                ritem = $tds.eq(3).text(),
                _perNW = $tds.eq(9).text(),
                rqty = $tds.eq(5).text(),
                rprc = $tds.eq(7).text(),
                ramt = $tds.eq(8).text(),
                rwh = $tds.eq(10).text();
            rgrlno = $tds.eq(11).text();
            rhscode = $tds.eq(12).text();
            rbm = $tds.eq(13).text();
            rppn = $tds.eq(14).text();
            rpph = $tds.eq(15).text();
            if (ritem != '') {
                ar_nourut.push(rnourut);
                ar_pono.push(rpo.trim());
                ar_item.push(ritem);
                ar_qty.push(numeral(rqty).value());
                ar_price.push(rprc);
                ar_amt.push(ramt);
                ar_wh.push(rwh);
                ar_grlno.push(rgrlno);
                ar_hscode.push(rhscode);
                ar_bm.push(rbm);
                ar_ppn.push(rppn);
                ar_pph.push(rpph);
                perNW.push(_perNW);
            }
        });
        $.ajax({
            type: "post",
            url: "<?=base_url('RCV/updateBCDoc')?>",
            data: {
                inbctype: mbctype,
                inbcno: mnoaju,
                inbcdate: mdate,
                inrcvdate: mrcvdate,
                inwh: ar_wh,
                inpo: ar_pono,
                indo: mdo,
                intpb: mtpb,
                inregno: mregno,
                inNW: m_nw,
                inGW: m_gw,
                insupcd: rcvcustoms_suppliercode,
                initm: ar_item,
                inqty: ar_qty,
                inprice: ar_price,
                inamt: ar_amt,
                inttl_amt: m_amt,
                inkppbc: mkppbc,
                ingrlno: ar_grlno,
                inhscode: ar_hscode,
                instsrcv: zstsrcv,
                inbm: ar_bm,
                inppn: ar_ppn,
                inpph: ar_pph,
                innomorurut: ar_nourut,
                inconaNum: cona,
                invoice: invoice,
                perNW: perNW,
                inbisgrup: rcvcustoms_fg_bisgrup.value,
            },
            dataType: "json",
            success: function(response) {
                alertify.message(response[0].msg);
                let mitem = document.getElementById('rcvcustoms_fg_docnoorigin').value;
                MGGetDODetail_FGRET(mitem.trim());
                WMSGetDODetail_FGRET(mitem.trim());
                if (response[0].cd == '0') {
                    location.reload();
                }
                $("#rcvcustoms_fg_PROGRESS").modal('hide');
            },
            error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow);
            }
        });
    }
    $("#rcvcustoms_fg_save").click(function() {
        let dono = document.getElementById('rcvcustoms_fg_docnoorigin').value;
        let mnoaju = document.getElementById('rcvcustoms_fg_noaju').value;
        let mnopen = document.getElementById('rcvcustoms_fg_regno').value;
        const customername = document.getElementById('rcvcustoms_fg_supplier_2')
        if (customername.value.trim().length < 5) {
            alertify.warning('Please select "return from" first')
            customername.focus()
            return
        }

        if (dono.trim() == '') {
            alertify.warning('Please select DO first');
            document.getElementById('rcvcustoms_fg_btnmod').focus();
            return;
        }
        if (mnoaju.trim().length != 26) {
            alertify.warning('NoAju must be 26 digit');
            document.getElementById('rcvcustoms_fg_noaju').focus()
            return;
        }
        if (mnopen.trim().length != 6) {
            alertify.warning('NoPen must be 6 digit');
            document.getElementById('rcvcustoms_fg_regno').focus()
            return;
        }
        let mymodal = new bootstrap.Modal(document.getElementById("rcvcustoms_fg_PROGRESS"), {
            backdrop: 'static',
            keyboard: false
        });
        mymodal.show();
    });


    $('#rcvcustoms_fg_supfilter').change(function() {
        document.getElementById('rcvcustoms_fg_txt_search').focus();
    });
    $("#rcvcustoms_fg_datefilter").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });

    function rcvcustoms_fg_btn_filterdate_e_click() {
        document.getElementById('rcvcustoms_fg_datefilter').value = "";
        document.getElementById('rcvcustoms_fg_txt_search').focus();
    }

    $("#rcvcustoms_fg_dd").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });
    $("#rcvcustoms_fg_rcvdate").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });


    $('#rcvcustoms_fg_tbl tbody').on('click', 'td', function() {
        rcvcustoms_fg_selcol = $(this).index();
    });
    $("#rcvcustoms_fg_btnmod").click(function(e) {
        e.preventDefault();
        $("#rcvcustoms_fg_DTLMOD").modal('show');
    });
    $("#rcvcustoms_fg_DTLMOD").on('shown.bs.modal', function() {
        $("#rcvcustoms_fg_txt_search").focus();
    });
    $("#rcvcustoms_fg_txt_search").keypress(function(e) {
        if (e.which == 13) {
            let mval = $(this).val()
            let mby = document.getElementById('rcvcustoms_fg_searchby').value
            let mpermonth = document.getElementById('rcvcustoms_fg_ck').checked ? 'y' : 'n'
            let myear = document.getElementById('rcvcustoms_fg_year').value
            let mmonth = document.getElementById('rcvcustoms_fg_monthfilter').value
            let msuplier = document.getElementById('rcvcustoms_fg_supfilter').value
            let mdatefilter = document.getElementById('rcvcustoms_fg_datefilter').value

            $("#lblinfo_rcvcustoms_fg_tbldono").text("Please wait...");
            $.ajax({
                type: "get",
                url: "<?=base_url('RCV/MGGetDOReturn')?>",
                data: {
                    inid: mval,
                    inby: mby,
                    inpermonth: mpermonth,
                    inyear: myear,
                    inmonth: mmonth,
                    insup: msuplier,
                    indatefilter: mdatefilter
                },
                dataType: "json",
                success: function(response) {
                    let ttlrows = response.length;
                    let mydes = document.getElementById("rcvcustoms_fg_divku_search")
                    let myfrag = document.createDocumentFragment()
                    let mtabel = document.getElementById("rcvcustoms_fg_tbldono")
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln)
                    let tabell = myfrag.getElementById("rcvcustoms_fg_tbldono")
                    let tableku2 = tabell.getElementsByTagName("tbody")[0]
                    let newrow, newcell, newText
                    let myitmttl = 0;
                    tableku2.innerHTML = ''
                    for (let i = 0; i < ttlrows; i++) {
                        newrow = tableku2.insertRow(-1)
                        newcell = newrow.insertCell(0)
                        newcell.innerHTML = response[i].STKTRND1_DOCNO
                        newcell.style.cssText = `cursor:pointer`
                        newcell.onclick = () => {
                            if (response[i].SUPNO == '') {
                                alertify.warning('DO Number could not be empty')
                                return
                            }
                            rcvcustoms_fg_bisgrup.value = response[i].MBSG_BSGRP
                            rcvcustoms_suppliercode = response[i].RETFG_SUPCD
                            $("#rcvcustoms_fg_docnoorigin").val(response[i].SUPNO);
                            $("#rcvcustoms_fg_supplier_2").val(response[i].MSUP_SUPNM);
                            MGGetDODetail_FGRET(response[i].STKTRND1_DOCNO);
                            $("#rcvcustoms_fg_DTLMOD").modal('hide');
                            WMSGetDODetail_FGRET(response[i].SUPNO);
                            document.getElementById('rcvcustoms_fg_contractnum').value = response[i].RCV_CONA
                            document.getElementById('rcvcustoms_fg_invoice').value = response[i].STKTRND1_DOCNO

                            rcvcustoms_fg_customer_name.value = response[i].MSUP_SUPNM
                            rcvcustoms_fg_customer_currency.value = ''
                            
                        }
                        newcell = newrow.insertCell(1)
                        newcell.innerHTML = response[i].ISUDT
                        newcell = newrow.insertCell(2)
                        newcell.innerHTML = response[i].MBSG_DESC
                        newcell = newrow.insertCell(3)
                        newcell.innerHTML = numeral(response[i].TTLITEMIN / response[i].TTLITEM * 100).format(',') + "% synchronized"
                        newcell = newrow.insertCell(4)
                        newcell.innerHTML = response[i].RCV_HSCD
                        newcell = newrow.insertCell(5)
                        newcell.innerHTML = response[i].RCV_BM
                        newcell = newrow.insertCell(6)
                        newcell.innerHTML = response[i].RCV_PPN
                        newcell = newrow.insertCell(7)
                        newcell.innerHTML = response[i].RCV_PPH
                        newcell = newrow.insertCell(8)
                        newcell.classList.add('d-none')
                        newcell.innerHTML = response[i].MBSG_BSGRP
                        newcell = newrow.insertCell(9)
                        newcell.innerHTML = response[i].MSUP_SUPNM
                        newcell = newrow.insertCell(10)
                        newcell.innerHTML = response[i].SUPNO
                    }
                    mydes.innerHTML = ''
                    mydes.appendChild(myfrag)
                    $("#lblinfo_rcvcustoms_fg_tbldono").text("");
                },
                error: function(xhr, xopt, xthrow) {
                    alertify.error(xthrow);
                }
            })
        }
    })

    function vrcv_fg_e_getstsrcv(mid, pval) {
        $.ajax({
            type: "get",
            url: "<?=base_url('RCV/zgetsts_rcv')?>",
            data: {
                inid: mid,
                instst: pval
            },
            dataType: "json",
            success: function(response) {
                let str = '<option value="-">-</option>';
                if (response.status[0].cd != '0') {
                    let ttlrows = response.data.length;
                    for (let i = 0; i < ttlrows; i++) {
                        str += '<option value="' + response.data[i].KODE_TUJUAN_PENGIRIMAN + '">' + response.data[i].URAIAN_TUJUAN_PENGIRIMAN + '</option>';
                    }
                }
                document.getElementById('rcvcustoms_fg_zsts').innerHTML = str;
                document.getElementById('rcvcustoms_fg_zsts').value = response.status[0].reff;
                document.getElementById('rcvcustoms_fg_zsts').focus();

            },
            error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow);
            }
        });
    }

    function vrcv_equip_e_getstsrcv(mid, pval, pid_component) {
        $.ajax({
            type: "get",
            url: "<?=base_url('RCV/zgetsts_rcv')?>",
            data: {
                inid: mid,
                instst: pval
            },
            dataType: "json",
            success: function(response) {
                let str = '<option value="-">-</option>';
                if (response.status[0].cd != '0') {
                    let ttlrows = response.data.length;
                    for (let i = 0; i < ttlrows; i++) {
                        str += '<option value="' + response.data[i].KODE_TUJUAN_PENGIRIMAN + '">' + response.data[i].URAIAN_TUJUAN_PENGIRIMAN + '</option>';
                    }
                }
                document.getElementById(pid_component).innerHTML = str;
                document.getElementById(pid_component).value = response.status[0].reff;
                document.getElementById(pid_component).focus();

            },
            error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow);
            }
        });
    }

    function WMSGetDODetail_FGRET(pdo) {
        $.ajax({
            type: "get",
            url: "<?=base_url('RCV/WMSGetDODetail')?>",
            data: {
                indo: pdo,
                inbisgrup : rcvcustoms_fg_bisgrup.value
            },
            dataType: "json",
            success: function(response) {
                let ttlrows = response.length;
                if (ttlrows > 0) {
                    let m_nw = '';
                    let m_gw = '';
                    let m_ttlamt = '';
                    let mstsrcv = '-';
                    for (let i = 0; i < 1; i++) {
                        if (response[i].RCV_ZSTSRCV) {
                            mstsrcv = response[i].RCV_ZSTSRCV;
                            vrcv_fg_e_getstsrcv(response[i].RCV_BCTYPE, mstsrcv);
                        } else {
                            mstsrcv = '-';
                        }
                    }
                    for (let i = 0; i < ttlrows; i++) {
                        if (response[i].RCV_NW) {
                            m_nw = response[i].RCV_NW.substring(0, 1) == '.' ? '0' + response[i].RCV_NW : response[i].RCV_NW;
                        } else {
                            m_nw = 0;
                        }
                        if (response[i].RCV_GW) {
                            m_gw = response[i].RCV_GW.substring(0, 1) == '.' ? '0' + response[i].RCV_GW : response[i].RCV_GW;
                        } else {
                            m_gw = 0;
                        }
                        if (response[i].RCV_TTLAMT) {
                            m_ttlamt = response[i].RCV_TTLAMT.substring(0, 1) == '.' ? '0' + response[i].RCV_TTLAMT : response[i].RCV_TTLAMT;
                        } else {
                            m_ttlamt = 0;
                        }

                        $("#rcvcustoms_fg_typedoc").val(response[i].RCV_BCTYPE);
                        $("#rcvcustoms_fg_noaju").val(response[i].RCV_RPNO);
                        $("#rcvcustoms_fg_regno").val(response[i].RCV_BCNO);
                        $("#rcvcustoms_fg_dd").datepicker('update', response[i].RCV_RPDATE);
                        $("#rcvcustoms_fg_rcvdate").datepicker('update', response[i].RCV_RCVDATE);
                        $("#rcvcustoms_fg_typetpb").val(response[i].RCV_TPB);
                        $("#rcvcustoms_fg_kppbc").val(response[i].RCV_KPPBC);
                        $("#rcvcustoms_fg_NW").val(m_nw)
                        $("#rcvcustoms_fg_GW").val(m_gw)
                    }
                } else {

                    $("#rcvcustoms_fg_noaju").val('');
                    $("#rcvcustoms_fg_regno").val('')
                    $("#rcvcustoms_fg_NW").val('');
                    $("#rcvcustoms_fg_GW").val('');
                }
            },
            error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow);
            }
        });
    }

    function MGGetDODetail_FGRET(pdo) {
        $("#rcvcustoms_fg_lbltbl").text("Please wait . . .");
        $.ajax({
            type: "get",
            url: "<?=base_url('RCV/MGGetDODetailReturn')?>",
            data: {
                indo: pdo
            },
            dataType: "json",
            success: function(response) {
                let ttlrows = response.length;
                let tohtml = '';
                let strprice = '';
                let stramt = '';
                let ttlAmount = 0
                $("#rcvcustoms_fg_lbltbl").text(ttlrows + " row(s) found");
                for (let i = 0; i < ttlrows; i++) {
                    strprice = response[i].STKTRND2_PRICE;
                    if (strprice.substring(0, 1) == '.') {
                        strpirce = '0' + response[i].STKTRND2_PRICE;
                    } else {
                        strpirce = response[i].STKTRND2_PRICE;
                    }
                    stramt = response[i].AMT;
                    if (stramt.substring(0, 1) == '.') {
                        stramt = '0' + response[i].AMT;
                    } else {
                        stramt = response[i].AMT;
                    }
                    ttlAmount += Number(stramt)
                    tohtml += "<tr style='cursor:pointer'>" +
                        "<td>" + response[i].SYNC_STS + "</td>" +
                        "<td contenteditable='true'>" + response[i].NOURUT + "</td>" +
                        "<td class='d-none'></td>" +
                        "<td>" + response[i].STKTRND2_ITMCD + "</td>" +
                        "<td>" + response[i].MITM_ITMD1 + "</td>" +
                        "<td class='text-end'>" + numeral(response[i].RETQT).format(',') + "</td>" +
                        "<td>" + response[i].MITM_STKUOM + "</td>" +
                        "<td class='text-end'>" + strpirce + "</td>" +
                        "<td class='text-end'>" + stramt + "</td>" +
                        "<td class='receive-detail-nw' contenteditable='true'>" + response[i].RCV_PRNW + "</td>" +
                        "<td class='d-none'>" + response[i].STKTRND1_LOCCDFR + "</td>" +
                        "<td class='d-none'>" + response[i].THELINE + "</td>" +
                        "<td contenteditable='true'>" + response[i].HSCD + "</td>" +
                        "<td contenteditable='true'>" + response[i].BEAMASUK + "</td>" +
                        "<td contenteditable='true'>" + response[i].PPN + "</td>" +
                        "<td contenteditable='true'>" + response[i].PPH + "</td>" +
                        "</tr>";
                }
                $("#rcvcustoms_fg_tbl tbody").html(tohtml)
                document.getElementById('rcvcustoms_fg_amount').value = numeral(ttlAmount).format('0,0.00')

                Inputmask({
                    'alias': 'decimal',
                    'groupSeparator': ',',
                }).mask(document.getElementsByClassName("receive-detail-nw"));
            },
            error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow);
                $("#rcvcustoms_fg_lbltbl").text("");
            }
        });
    }

    function rcvcustoms_fg_typdoc_oCH(reffvalue) {
        const mid = document.getElementById('rcvcustoms_fg_typedoc').value
        $.ajax({
            type: "get",
            url: "<?=base_url('RCV/zgetsts_rcv')?>",
            data: {
                inid: mid
            },
            dataType: "json",
            success: function(response) {
                let str = '<option value="-">-</option>';
                if (response.status[0].cd != '0') {
                    let ttlrows = response.data.length;
                    for (let i = 0; i < ttlrows; i++) {
                        str += '<option value="' + response.data[i].KODE_TUJUAN_PENGIRIMAN + '">' + response.data[i].URAIAN_TUJUAN_PENGIRIMAN + '</option>';
                    }
                }
                document.getElementById('rcvcustoms_fg_zsts').innerHTML = str;
                document.getElementById('rcvcustoms_fg_zsts').focus()
                if (reffvalue.length !== 0) {
                    document.getElementById('rcvcustoms_fg_zsts').value = reffvalue
                }
            },
            error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow);
            }
        })
    }
    $("#rcvcustoms_fg_typedoc").change(function() {
        rcvcustoms_fg_typdoc_oCH('')
    });

    $('#rcvcustoms_fg_dd').datepicker()
        .on('changeDate', function(e) {
            $("#rcvcustoms_fg_typetpb").focus();
        });
    $('#rcvcustoms_fg_datefilter').datepicker()
        .on('changeDate', function(e) {
            $("#rcvcustoms_fg_txt_search").focus();
        });
    $("#rcvcustoms_fg_typetpb").change(function() {
        $("#rcvcustoms_fg_NW").focus();
    })

    function rcvcustoms_fg_save_as_xls_eCK() {
        let mnoaju = document.getElementById('rcvcustoms_fg_noaju').value
        if (mnoaju.length !== 26) {
            alertify.warning('Nomor Aju is not found')
            return
        }
        Cookies.set('RP_PAB_NOAJU', mnoaju, {
            expires: 365
        })
        window.open("<?=base_url('laporan_pembukuan_masuk_xlsx')?>", '_blank')
    }
    $("#rcvcustoms_contractdate_1").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    })
    $("#rcvcustoms_contractduedate_1").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    })
    $("#rcvcustoms_rcvdate_1").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    })
    $("#rcvcustoms_rcvdate_2").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    })
    $("#rcvcustoms_dd_1").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    })
    $("#rcvcustoms_dd_2").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    })

    function rcvcustoms_txt_search_1_eKP(e) {
        if (e.key === 'Enter') {
            const mval = document.getElementById('rcvcustoms_txt_search_1').value
            const mby = document.getElementById('rcvcustoms_searchfilter_1').value
            const mpermonth = document.getElementById('rcvcustoms_ck_1').checked ? 'y' : 'n'
            const myear = document.getElementById('rcvcustoms_year_1').value
            const mmonth = document.getElementById('rcvcustoms_monthfilter_1').value
            const tbl = document.getElementById('rcvcustoms_tbldono_1').getElementsByTagName('tbody')[0]
            tbl.innerHTML = `<tr><td colspan="12" class="text-center">Please wait</td></tr>`
            $.ajax({
                type: "GET",
                url: "<?=base_url('RCV/GetDO1')?>",
                data: {
                    inid: mval,
                    inby: mby,
                    inpermonth: mpermonth,
                    inyear: myear,
                    inmonth: mmonth
                },
                dataType: "JSON",
                success: function(response) {
                    const ttlrows = response.data.length
                    let mydes = document.getElementById("rcvcustoms_tbldono_1_div")
                    let myfrag = document.createDocumentFragment()
                    let mtabel = document.getElementById("rcvcustoms_tbldono_1")
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln)
                    let tabell = myfrag.getElementById("rcvcustoms_tbldono_1")
                    let tableku2 = tabell.getElementsByTagName("tbody")[0]
                    let newrow, newcell, newText
                    let myitmttl = 0;
                    tableku2.innerHTML = ''
                    for (let i = 0; i < ttlrows; i++) {
                        newrow = tableku2.insertRow(-1)
                        newcell = newrow.insertCell(0)
                        newcell.style.cssText = "cursor:pointer"
                        newcell.onclick = function() {
                            rcvuctoms_getdetail1({
                                pdo: response.data[i].RCV_DONO,
                                psupcd: response.data[i].MSUP_SUPCD,
                                pAJU: response.data[i].RCV_RPNO
                            })
                            document.getElementById('rcvcustoms_docnoorigin_1').value = response.data[i].RCV_DONO
                            document.getElementById('rcvcustoms_typedoc_1').value = response.data[i].RCV_BCTYPE
                            vrcv_equip_e_getstsrcv(response.data[i].RCV_BCTYPE, response.data[i].RCV_ZSTSRCV, 'rcvcustoms_zsts_1')
                            document.getElementById('rcvcustoms_noaju_1').value = response.data[i].RCV_RPNO
                            document.getElementById('rcvcustoms_regno_1').value = response.data[i].RCV_BCNO
                            $("#rcvcustoms_dd_1").datepicker('update', response.data[i].RCV_RPDATE);
                            $("#rcvcustoms_rcvdate_1").datepicker('update', response.data[i].RCV_RCVDATE);
                            document.getElementById('rcvcustoms_typetpb_1').value = response.data[i].RCV_TPB
                            document.getElementById('rcvcustoms_kppbc_1').value = response.data[i].RCV_KPPBC
                            document.getElementById('rcvcustoms_NW_1').value = response.data[i].RCV_NW
                            document.getElementById('rcvcustoms_GW_1').value = response.data[i].RCV_GW
                            document.getElementById('rcvcustoms_businessgroup_1').value = response.data[i].RCV_BSGRP
                            document.getElementById('rcvcustoms_supplier_1').value = response.data[i].MSUP_SUPNM
                            rcvcustoms_suppliercode = response.data[i].MSUP_SUPCD
                            document.getElementById('rcvcustoms_invoicenum_1').value = response.data[i].RCV_INVNO
                            document.getElementById('rcvcustoms_contractnum_1').value = response.data[i].RCV_CONA
                            document.getElementById('rcvcustoms_contractdate_1').value = response.data[i].RCV_CONADT
                            document.getElementById('rcvcustoms_contractduedate_1').value = response.data[i].RCV_DUEDT
                            document.getElementById('rcvcustoms_tax_invoice').value = response.data[i].RCV_TAXINVOICE
                            rcvcustoms_shippercode = response.data[i].SHIPPERCD
                            rcvcustoms_shipper_1.value = response.data[i].SHIPPERNM
                        }
                        newcell.innerHTML = response.data[i].RCV_DONO
                        newcell = newrow.insertCell(1)
                        newcell.innerHTML = response.data[i].RCV_BCDATE
                        newcell = newrow.insertCell(2)
                        newcell.innerHTML = response.data[i].MSUP_SUPNM
                        newcell = newrow.insertCell(3)
                        newcell.innerHTML = response.data[i].RCV_HSCD
                        newcell = newrow.insertCell(4)
                        newcell.classList.add('text-end')
                        newcell.innerHTML = response.data[i].RCV_BM
                        newcell = newrow.insertCell(5)
                        newcell.classList.add('text-end')
                        newcell.innerHTML = response.data[i].RCV_PPN
                        newcell = newrow.insertCell(6)
                        newcell.classList.add('text-end')
                        newcell.innerHTML = response.data[i].RCV_PPH
                        newcell = newrow.insertCell(7)
                        newcell.innerHTML = response.data[i].RCV_BSGRP
                        newcell = newrow.insertCell(8)
                        newcell.innerHTML = response.data[i].RCV_INVNO
                        newcell = newrow.insertCell(9)
                        newcell.innerHTML = response.data[i].RCV_BCNO
                        newcell = newrow.insertCell(10)
                        newcell.innerHTML = response.data[i].RCV_BCTYPE
                        newcell = newrow.insertCell(11)
                        newcell.innerHTML = response.data[i].RCV_TAXINVOICE
                    }
                    mydes.innerHTML = ''
                    mydes.appendChild(myfrag)
                },
                error: function(xhr, xopt, xthrow) {
                    alertify.error(xthrow)
                    tbl.innerHTML = `<tr><td colspan="10" class="text-center">${xthrow}</td></tr>`
                }
            })
        }
    }

    function rcvcustoms_txt_search_2_eKP(e) {
        if (e.key == 'Enter') {
            const mval = document.getElementById('rcvcustoms_txt_search_2').value
            const mby = document.getElementById('rcvcustoms_searchfilter_2').value
            const mpermonth = document.getElementById('rcvcustoms_ck_2').checked ? 'y' : 'n'
            const myear = document.getElementById('rcvcustoms_year_2').value
            const mmonth = document.getElementById('rcvcustoms_monthfilter_2').value
            const tbl = document.getElementById('rcvcustoms_tbldono_2').getElementsByTagName('tbody')[0]
            tbl.innerHTML = `<tr><td colspan="10" class="text-center">Please wait</td></tr>`
            $.ajax({
                type: "GET",
                url: "<?=base_url('RCV/GetDO2')?>",
                data: {
                    inid: mval,
                    inby: mby,
                    inpermonth: mpermonth,
                    inyear: myear,
                    inmonth: mmonth
                },
                dataType: "JSON",
                success: function(response) {
                    const ttlrows = response.data.length
                    let mydes = document.getElementById("rcvcustoms_tbldono_2_div")
                    let myfrag = document.createDocumentFragment()
                    let mtabel = document.getElementById("rcvcustoms_tbldono_2")
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln)
                    let tabell = myfrag.getElementById("rcvcustoms_tbldono_2")
                    let tableku2 = tabell.getElementsByTagName("tbody")[0]
                    let newrow, newcell, newText
                    let myitmttl = 0;
                    tableku2.innerHTML = ''
                    for (let i = 0; i < ttlrows; i++) {
                        newrow = tableku2.insertRow(-1)
                        newcell = newrow.insertCell(0)
                        newcell.style.cssText = "cursor:pointer"
                        newcell.onclick = function() {
                            rcvuctoms_getdetail2(response.data[i].RCV_DONO)
                            document.getElementById('rcvcustoms_docnoorigin_2').value = response.data[i].RCV_DONO
                            document.getElementById('rcvcustoms_typedoc_2').value = response.data[i].RCV_BCTYPE
                            vrcv_equip_e_getstsrcv(response.data[i].RCV_BCTYPE, response.data[i].RCV_ZSTSRCV, 'rcvcustoms_zsts_2')
                            document.getElementById('rcvcustoms_noaju_2').value = response.data[i].RCV_RPNO
                            document.getElementById('rcvcustoms_regno_2').value = response.data[i].RCV_BCNO
                            $("#rcvcustoms_dd_2").datepicker('update', response.data[i].RCV_RPDATE);
                            $("#rcvcustoms_rcvdate_2").datepicker('update', response.data[i].RCV_RCVDATE);
                            document.getElementById('rcvcustoms_typetpb_2').value = response.data[i].RCV_TPB
                            document.getElementById('rcvcustoms_kppbc_2').value = response.data[i].RCV_KPPBC
                            document.getElementById('rcvcustoms_NW_2').value = response.data[i].RCV_NW
                            document.getElementById('rcvcustoms_GW_2').value = response.data[i].RCV_GW
                            document.getElementById('rcvcustoms_businessgroup_2').value = response.data[i].RCV_BSGRP
                            document.getElementById('rcvcustoms_supplier_2').value = response.data[i].MSUP_SUPNM
                            rcvcustoms_suppliercode = response.data[i].MSUP_SUPCD
                            document.getElementById('rcvcustoms_invoicenum_2').value = response.data[i].RCV_INVNO
                            document.getElementById('rcvcustoms_cmb_locTo').value = response.data[i].RCV_WH
                        }
                        newcell.innerHTML = response.data[i].RCV_DONO
                        newcell = newrow.insertCell(1)
                        newcell.innerHTML = response.data[i].RCV_BCDATE
                        newcell = newrow.insertCell(2)
                        newcell.innerHTML = response.data[i].MSUP_SUPNM
                        newcell = newrow.insertCell(3)
                        newcell.innerHTML = response.data[i].RCV_HSCD
                        newcell = newrow.insertCell(4)
                        newcell.innerHTML = response.data[i].RCV_BM
                        newcell = newrow.insertCell(5)
                        newcell.innerHTML = response.data[i].RCV_PPN
                        newcell = newrow.insertCell(6)
                        newcell.innerHTML = response.data[i].RCV_PPH
                        newcell = newrow.insertCell(7)
                        newcell.innerHTML = response.data[i].RCV_BSGRP
                        newcell = newrow.insertCell(8)
                        newcell.innerHTML = response.data[i].RCV_INVNO
                    }
                    mydes.innerHTML = ''
                    mydes.appendChild(myfrag)
                },
                error: function(xhr, xopt, xthrow) {
                    alertify.error(xthrow)
                    tbl.innerHTML = `<tr><td colspan="10" class="text-center">${xthrow}</td></tr>`
                }
            })
        }
    }

    function rcvcustoms_plus_PO_1_eC() {
        $("#rcvcustoms_POBAL_Mod").modal("show")
    }

    function rcvcustoms_po_txtsearch_eKP(e) {
        if (e.key === 'Enter') {
            e.target.readOnly = true
            let mtabel = document.getElementById("rcvcustoms_po_tbl")
            mtabel.getElementsByTagName("tbody")[0].innerHTML = '<tr><td colspan="11" class="text-center">Please wait</td></tr>'
            $.ajax({
                url: "<?=base_url('PO/search_balance')?>",
                data: {
                    search: e.target.value,
                    searchtype: '1'
                },
                dataType: "json",
                success: function(response) {
                    e.target.readOnly = false
                    const ttlrows = response.data.length
                    let mydes = document.getElementById("rcvcustoms_po_tbl_div")
                    let myfrag = document.createDocumentFragment()
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln)
                    let tabell = myfrag.getElementById("rcvcustoms_po_tbl")
                    let tableku2 = tabell.getElementsByTagName("tbody")[0]
                    let newrow, newcell, newText
                    let myitmttl = 0;
                    tableku2.innerHTML = ''
                    for (let i = 0; i < ttlrows; i++) {
                        let qtyreq = numeral(response.data[i].PO_QTY).value()
                        let qtybal = qtyreq - numeral(response.data[i].RCVQTY).value()
                        if (qtybal > 0) {
                            newrow = tableku2.insertRow(-1)
                            newcell = newrow.insertCell(0)
                            newcell.innerHTML = response.data[i].PO_NO
                            newcell = newrow.insertCell(1)
                            newcell.innerHTML = response.data[i].MSUP_SUPNM
                            newcell = newrow.insertCell(2)
                            newcell.innerHTML = response.data[i].PO_REQDT
                            newcell = newrow.insertCell(3)
                            newcell.innerHTML = response.data[i].PO_ISSUDT
                            newcell = newrow.insertCell(4)
                            newcell.innerHTML = response.data[i].PO_ITMCD
                            newcell = newrow.insertCell(5)
                            newcell.innerHTML = response.data[i].MITM_ITMD1
                            newcell = newrow.insertCell(6)
                            newcell.classList.add('text-end')
                            newcell.innerHTML = numeral(qtyreq).format('0,0.00')
                            newcell = newrow.insertCell(7)
                            newcell.classList.add('text-end')
                            newcell.innerHTML = numeral(qtybal).format('0,0.00')
                            newcell = newrow.insertCell(8)
                            newcell.classList.add('text-end')
                            newcell.innerHTML = response.data[i].PO_PRICE
                            newcell = newrow.insertCell(9)
                            newcell.classList.add('d-none')
                            newcell.innerHTML = response.data[i].PO_SUPCD
                            newcell = newrow.insertCell(10)
                            newcell.classList.add('d-none')
                            newcell.innerHTML = response.data[i].MITM_STKUOM
                        }
                    }
                    mydes.innerHTML = ''
                    mydes.appendChild(myfrag)
                },
                error: function(xhr, xopt, xthrow) {
                    e.target.readOnly = false
                    alertify.error(xthrow)
                    mtabel.getElementsByTagName("tbody")[0].innerHTML = `<tr><td colspan="11" class="text-center">[${xthrow}], please try again or contact administrator</td></tr>`
                }
            })
        }
    }

    function rcvcustoms_po_btnuse_eCK() {
        let mtbl = document.getElementById('rcvcustoms_po_tbl')
        let tableku2 = mtbl.getElementsByTagName("tbody")[0]
        let mtbltr = tableku2.getElementsByTagName('tr')
        let ttlrows = mtbltr.length
        let aPO = []
        let aItem = []
        let aItemName = []
        let aqty = []
        let aprice = []
        let aUM = []
        let isexist = false
        let supplierName = ''
        for (let i = 0; i < ttlrows; i++) {
            let qtynya = numeral(tableku2.rows[i].cells[7].innerText).value()
            if (qtynya > 0) {
                supplierName = tableku2.rows[i].cells[1].innerText
                rcvcustoms_suppliercode = tableku2.rows[i].cells[9].innerText
                isexist = true
                aPO.push(tableku2.rows[i].cells[0].innerText)
                aItem.push(tableku2.rows[i].cells[4].innerText)
                aItemName.push(tableku2.rows[i].cells[5].innerText)
                aqty.push(qtynya)
                aprice.push(numeral(tableku2.rows[i].cells[8].innerText).value())
                aUM.push(tableku2.rows[i].cells[10].innerText)
            }
        }
        if (isexist) {
            rcvcustoms_btn_find_supplier_1.disabled = true
            document.getElementById('rcvcustoms_supplier_1').value = supplierName
            let tabell = document.getElementById("rcvcustoms_tbl_1")
            let tableku2 = tabell.getElementsByTagName("tbody")[0]
            let newrow, newcell;
            for (let i = 0; i < ttlrows; i++) {
                newrow = tableku2.insertRow(-1)
                newrow.onclick = (event) => {
                    rcvcustoms_tbl_tbody_tr_eC(event)
                }
                newcell = newrow.insertCell(0)
                newcell.classList.add('d-none')

                newcell = newrow.insertCell(1)
                newcell.contentEditable = true

                newcell = newrow.insertCell(2)
                newcell.contentEditable = true
                newcell.innerHTML = aPO[i]

                newcell = newrow.insertCell(3)

                newcell = newrow.insertCell(4)
                newcell.title = `click for showing search dialog`
                newcell.classList.add('table-info')
                newcell.onclick = () => {
                    $("#RCVCUSTOMS_INTERNALITEM").modal('show')
                }
                newcell.style.cssText = "cursor:pointer"
                newcell.innerHTML = aItem[i]

                newcell = newrow.insertCell(5)
                newcell.classList.add('table-info')
                newcell.innerHTML = aItemName[i]

                newcell = newrow.insertCell(6)
                newcell.contentEditable = true
                newcell.classList.add('text-end')
                newcell.innerHTML = aqty[i]

                newcell = newrow.insertCell(7)
                newcell.classList.add('table-info')
                newcell.innerHTML = aUM[i]

                newcell = newrow.insertCell(8)
                newcell.classList.add('text-end')
                newcell.innerHTML = aprice[i]

                newcell = newrow.insertCell(9)
                newcell.classList.add('text-end', 'table-info')
                newcell.innerHTML = '-'

                newcell = newrow.insertCell(10)
                newcell.classList.add('text-end')
                newcell.title = 'net weight'
                newcell.contentEditable = true
                newcell.innerHTML = '-'
                newcell = newrow.insertCell(11)
                newcell.classList.add('text-end')
                newcell.title = 'gross weight'
                newcell.contentEditable = true
                newcell.innerHTML = '-'
                newcell = newrow.insertCell(12)
                newcell.title = 'HS Code'
                newcell.contentEditable = true
                newcell.innerHTML = '-'

                newcell = newrow.insertCell(13)
                newcell.classList.add('text-end')
                newcell.contentEditable = true
                newcell.innerHTML = '-'

                newcell = newrow.insertCell(14)
                newcell.classList.add('text-end')
                newcell.contentEditable = true
                newcell.innerHTML = '-'

                newcell = newrow.insertCell(15)
                newcell.classList.add('text-end')
                newcell.contentEditable = true
                newcell.innerHTML = '-'

                newcell = newrow.insertCell(16)
                newcell.style.cssText = "cursor:pointer"
                newcell.classList.add('text-center', 'table-info')
                newcell.title = 'to Location' 
                newcell.innerText = 'PSIEQUIP'
                newcell.onclick = (event) => {
                    rcvcustoms_modal_cmb_locTo.value = event.target.innerText
                    $("#RCVCUSTOMS_LOCATION").modal('show')
                }
            }
        }
        document.getElementById('rcvcustoms_po_tbl').getElementsByTagName('tbody')[0].innerHTML = ''
        document.getElementById('rcvcustoms_po_txtsearch').value = ''
    }

    var rcvcustoms_ta_result = document.getElementById('rcvcustoms_reader_ta')

    function rcvcustoms_noaju_lbl_eCK() {
        $("#rcvcustoms_QR_Mod").modal('show')
    }

    function onScanSuccess(decodedText, decodedResult) {
        // Handle on success condition with the decoded text or result.
        console.log(`Scan result: ${decodedText}`, decodedResult);
        rcvcustoms_ta_result.value = decodedText
    }

    var html5QrcodeScanner = new Html5QrcodeScanner(
        "rcvcustoms_reader", {
            fps: 10,
            qrbox: 250
        });
    html5QrcodeScanner.render(onScanSuccess);

    function rcvcustoms_typedoc_1_eChange(c) {
        if (c.value === '40') {
            rcvcustoms_kppbc_1.value = '050900'
        }
        $.ajax({
            type: "get",
            url: "<?=base_url('RCV/zgetsts_rcv')?>",
            data: {
                inid: c.value
            },
            dataType: "json",
            success: function(response) {
                let str = '<option value="-">-</option>';
                if (response.status[0].cd != '0') {
                    let ttlrows = response.data.length;
                    for (let i = 0; i < ttlrows; i++) {
                        str += '<option value="' + response.data[i].KODE_TUJUAN_PENGIRIMAN + '">' + response.data[i].URAIAN_TUJUAN_PENGIRIMAN + '</option>';
                    }
                }
                document.getElementById('rcvcustoms_zsts_1').innerHTML = str
                if (c.value === '40') {
                    rcvcustoms_zsts_1.disabled = rcvcustoms_tax_invoice.value.trim().length>0 ? true : false
                    rcvcustoms_typetpb_1.disabled = true
                    rcvcustoms_typetpb_1.value = '1'
                    switch (rcvcustoms_tax_invoice.value.substr(0, 2)) {
                        case '01':
                            rcvcustoms_zsts_1.value = 4
                            break;
                        case '07':
                            rcvcustoms_zsts_1.value = 1
                            break;
                        default:
                            rcvcustoms_zsts_1.value = 5
                    }
                } else {
                    rcvcustoms_zsts_1.disabled = false
                    rcvcustoms_typetpb_1.disabled = false
                    document.getElementById('rcvcustoms_zsts_1').focus()
                }

            },
            error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow);
            }
        })
    }

    function rcvcustoms_btn_posting_eCK(c) {
        let txDOC = document.getElementById('rcvcustoms_docnoorigin_1')
        let cmbBCDOCType = document.getElementById('rcvcustoms_typedoc_1')
        let txNomorAJU = document.getElementById('rcvcustoms_noaju_1')
        let btnTPB = document.getElementById('rcvcustoms_btnTPB')
        const cmbBCReceivingStatus = document.getElementById('rcvcustoms_zsts_1')
        const txNomorPEN = document.getElementById('rcvcustoms_regno_1')
        const cmbTPBType = document.getElementById('rcvcustoms_typetpb_1')
        const cmbKPPBC = document.getElementById('rcvcustoms_kppbc_1')
        const tbl = document.getElementById('rcvcustoms_tbl_1').getElementsByTagName("tbody")[0]
        const ttlrows = tbl.getElementsByTagName('tr').length
        switch (c.id) {
            case 'rcvcustoms_btn_posting_direct':
                txDOC = document.getElementById('rcvcustoms_docnoorigin')
                cmbBCDOCType = document.getElementById('rcvcustoms_typedoc')
                txNomorAJU = document.getElementById('rcvcustoms_noaju')
                btnTPB = document.getElementById('rcvcustoms_btnTPB_direct')
                break
            case 'rcvcustoms_btn_posting_2':
                txDOC = document.getElementById('rcvcustoms_docnoorigin_2')
                cmbBCDOCType = document.getElementById('rcvcustoms_typedoc_2')
                txNomorAJU = document.getElementById('rcvcustoms_noaju_2')
                btnTPB = document.getElementById('rcvcustoms_btnTPB_2')
                break
        }
        //validation
        if (txDOC.value.trim().length <= 3) {
            alertify.warning('DO Number is required')
            txDOC.focus()
            return
        }
        if (cmbBCDOCType.value != '40') {
            alertify.message('BC 40 only')
            return
        }
        if (cmbBCReceivingStatus.value == '-') {
            alertify.warning('Receiving Status is required')
            cmbBCReceivingStatus.focus()
            return
        }
        if (txNomorAJU.value.trim().length < 26) {
            alertify.warning('Nomor Aju is not valid')
            txNomorAJU.focus()
            return
        }

        btnTPB.disabled = true
        btnTPB.innerHTML = "Please wait"
        const data = {
            DONum: txDOC.value,
            BCType: cmbBCDOCType.value,
            aju: txNomorAJU.value,
        }
        rcvcustoms_prepare_posting(data, btnTPB)
    }
    document.getElementById('rcvcustoms_businessgroup_1').value = "PSIOTHERS"

    function rcvcustoms_prepare_posting(pdata, psender) {
        const btn = document.getElementById(psender.id)
        $.ajax({
            type: "POST",
            url: "<?=base_url('RCV/posting')?>" + pdata.BCType,
            data: {
                donum: pdata.DONum,
                aju: pdata.aju
            },
            dataType: "json",
            success: function(response) {
                if (response.status[0].cd == '1') {
                    alertify.success(response.status[0].msg)
                } else {
                    alertify.message(response.status[0].msg)
                }
                btn.disabled = false
                btn.innerHTML = 'TPB'
            },
            error: function(xhr, xopt, xthrow) {
                btn.disabled = false
                btn.innerHTML = 'TPB'
                alertify.error(xthrow);
            }
        })
    }

    function rcvcustoms_btnmkemasan_eCK() {
        $("#rcvcustoms_KEMASAN_Mod").modal('show')
    }

    function rcvcustoms_pkg_btnuse_eCK() {
        const txJumlah = document.getElementById('rcvcustoms_pkg_jumlah')
        const cmbKodeJenis = document.getElementById('rcvcustoms_pkg_kodejenis')
        if (isNaN(txJumlah.value)) {
            alertify.warning("Jumlah is invalid")
            return
        } else {
            const tbl = document.getElementById('rcvcustoms_pkg_tbl').getElementsByTagName('tbody')[0]
            let newrow = tbl.insertRow(-1)
            newrow.addEventListener("contextmenu", function(e) {
                rcvcustoms_rowsObj.ridx = e.target.parentNode.rowIndex
                rcvcustoms_rowsObj.ids = e.target.parentNode.cells[0].innerText
                rcvcustoms_contextMenu.open(e)
                e.preventDefault()
            })
            let newcell = newrow.insertCell(0)
            newcell.classList.add('d-none')
            newcell = newrow.insertCell(1)
            newcell.innerText = txJumlah.value
            newcell.classList.add('text-end')
            newcell = newrow.insertCell(2)
            newcell.innerText = cmbKodeJenis.value
            newcell = newrow.insertCell(3)
            newcell.innerText = cmbKodeJenis.options[cmbKodeJenis.selectedIndex].text
        }
    }

    var rcvcustoms_rowsObj = {}
    var rcvcustoms_contextMenu = jSuites.contextmenu(document.getElementById('rcvcustoms_contextmenu'), {
        items: [{
            title: '<span class="fas fa-trash text-warning"></span> Delete',
            onclick: function() {
                if (rcvcustoms_rowsObj.ids.length > 0) {
                    if (!confirm("Are you sure ?")) {
                        return
                    }
                    document.getElementById('rcvcustoms_pkg_tbl').rows[rcvcustoms_rowsObj.ridx].remove()
                    const txtdoc = document.getElementById('rcvcustoms_docnoorigin_1')
                    const txtAju = document.getElementById('rcvcustoms_noaju_1')
                    $.ajax({
                        type: "POST",
                        url: "<?=base_url('RCV/remove_pkg')?>",
                        data: {
                            rowid: rcvcustoms_rowsObj.ids,
                            aju: txtAju.value
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.status[0].cd == 1) {
                                alertify.success(response.status[0].msg)
                            } else {
                                alertify.message(response.status[0].msg)
                            }
                        },
                        error: function(xhr, xopt, xthrow) {
                            alertify.error(xthrow)
                        }
                    })
                } else {
                    document.getElementById('rcvcustoms_pkg_tbl').rows[rcvcustoms_rowsObj.ridx].remove()
                }
            },
            tooltip: 'Delete selected item',
        }],
        onclick: function() {
            rcvcustoms_contextMenu.close(false);
        }
    })

    function rcvcustoms_btnsync_eCK(p) {
        let docnum = document.getElementById('rcvcustoms_docnoorigin_1').value;
        $.ajax({
            type: "get",
            url: "<?=base_url('RCV/get_info_pendaftaran')?>",
            data: {
                insj: docnum
            },
            dataType: "json",
            success: function(response) {
                if (response.status[0].cd != '0') {
                    if (response.data[0].NOMOR_DAFTAR.length == 6) {
                        document.getElementById('rcvcustoms_regno_1').value = response.data[0].NOMOR_DAFTAR
                        alertify.success("OK");
                    } else {
                        alertify.message('NOMOR PENDAFTARAN is not recevied yet');
                    }
                } else {
                    alertify.message(response.status[0].msg);
                }
            },
            error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow);
            }
        });
    }

    async function getCameraSelection() {
        const cameraOptions = document.getElementById('rcvcustoms_camlist')
        const devices = await navigator.mediaDevices.enumerateDevices();
        const videoDevices = devices.filter(device => device.kind === 'videoinput');
        console.log(devices)
        const options = videoDevices.map(videoDevice => {
            return `<option value="${videoDevice.deviceId}">${videoDevice.label}</option>`;
        });
        cameraOptions.innerHTML = options.join('');
    };

    function tesTese() {
        if ('mediaDevices' in navigator && 'getUserMedia' in navigator.mediaDevices) {
            navigator.mediaDevices.getUserMedia({
                    audio: false,
                    video: true
                })
                .then(function(stream) {
                    getCameraSelection()
                    $("#rcvcustoms_TESE").modal('show')
                })
                .catch(function(err) {
                    alertify.message('could not access camera')
                });
        } else {
            alertify.message('sorry we could not found your camera')
        }
    }

    var video = document.querySelector('video');
    var screenshotImage = document.getElementById('rcvcustoms_img');
    var canvas = document.querySelector('canvas');

    function rcvcustoms_qr_btntry_eCK() {
        if (streamStarted) {
            video.play();
            return;
        }
        const cameraOptions = document.getElementById('rcvcustoms_camlist')
        if ('mediaDevices' in navigator && navigator.mediaDevices.getUserMedia) {
            const updatedConstraints = {
                ...constraints,
                deviceId: {
                    exact: cameraOptions.value
                }
            };
            startStream(updatedConstraints);
        }
    }
    var streamStarted = false;
    var constraints = {
        video: {
            width: {
                min: 1280,
                ideal: 1920,
                max: 2560,
            },
            height: {
                min: 720,
                ideal: 1080,
                max: 1440
            },
        }
    };
    async function startStream(constraints) {
        const stream = await navigator.mediaDevices.getUserMedia(constraints);
        handleStream(stream);
    };

    function handleStream(stream) {
        video.srcObject = stream
        streamStarted = true;
    };

    function rcvcustoms_qr_btntake_eCK() {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
        screenshotImage.src = canvas.toDataURL();
        console.log(Tesseract)
        const worker = Tesseract.createWorker({
            logger: m => console.log(m), // Add logger here
        });
        (async () => {
            await worker.load();
            await worker.loadLanguage('eng');
            await worker.initialize('eng');
            const {
                data: {
                    text
                }
            } = await worker.recognize(canvas.toDataURL('image/png'));
            console.log('datanya adalah')
            const match = /\r|\n/.exec(text)
            if (match) {
                const listdata = text.split(/\r?\n/)
                console.log(listdata)
            }
            console.log(text);
            await worker.terminate();
        })();
    };

    function rcvcustoms_tax_invoice_eKeyUp(e) {
        if (rcvcustoms_typedoc_1.value === '40') {
            rcvcustoms_zsts_1.disabled = e.target.value.trim().length>0 ? true : false
            switch (e.target.value.substr(0, 2)) {
                case '01':
                    rcvcustoms_zsts_1.value = 4
                    break;
                case '07':
                    rcvcustoms_zsts_1.value = 1
                    break;
                default:
                    rcvcustoms_zsts_1.value = 5
            }
        }
    }

    function rcvcustoms_noaju_eKeyUp(e) {
        if (e.target.value.trim().length >= 6) {
            const temp = e.target.value.trim().substr(4, 2)
            rcvcustoms_typedoc.value = temp
            if (rcvcustoms_rcv_status === '-') {
                rcvcustoms_initReceivingStatus(rcvcustoms_typedoc.value)
            } else {
                vrcv_e_getstsrcv(temp, rcvcustoms_rcv_status)
            }
        }
    }

    function rcvcustoms_contractnum_1_eKeyUp(e) {
        if (rcvcustoms_typedoc_1.value === '40' && e.target.value.trim().length > 0 && rcvcustoms_tax_invoice.value.trim().length === 0) {
            rcvcustoms_zsts_1.value = 2
        }
    }

    function rcvcustoms_btn_find_shipper_1_onClick(pElement){
        $("#RCVCUSTOMS_SHIPPER").modal('show')
    }

    function rcvcustomsSearchShipperOnKeyPress(e){
        if (e.key === 'Enter') {
            e.target.readOnly = true
            $.ajax({
                type: "GET",
                url: "<?=base_url('MSTSUP/search_union')?>",
                data: {
                    searchKey: e.target.value
                },
                dataType: "JSON",
                success: function(response) {
                    e.target.readOnly = false
                    if (response.status[0].cd === 1) {
                        const ttlrows = response.data.length
                        let mydes = document.getElementById("rcvcustomsTblShipperDiv")
                        let myfrag = document.createDocumentFragment()
                        let mtabel = document.getElementById("rcvcustomsTblShipper")
                        let cln = mtabel.cloneNode(true);
                        myfrag.appendChild(cln)
                        let tabell = myfrag.getElementById("rcvcustomsTblShipper")
                        let tableku2 = tabell.getElementsByTagName("tbody")[0]
                        let newrow, newcell, newText
                        let myitmttl = 0;
                        tableku2.innerHTML = ''
                        for (let i = 0; i < ttlrows; i++) {
                            newrow = tableku2.insertRow(-1)
                            newcell = newrow.insertCell(0)
                            newcell.style.cssText = "cursor:pointer"
                            newcell.innerHTML = response.data[i].MSUP_SUPCD
                            newcell.onclick = () => {
                                $("#RCVCUSTOMS_SHIPPER").modal('hide');
                                rcvcustoms_shippercode = response.data[i].MSUP_SUPCD
                                rcvcustoms_shipper_1.value = response.data[i].MSUP_SUPNM
                            }
                            newcell = newrow.insertCell(1)
                            newcell.innerHTML = response.data[i].MSUP_SUPNM
                        }
                        mydes.innerHTML = '';
                        mydes.appendChild(myfrag);
                    } else {
                        alertify.message(response.status[0].msg)
                    }
                },
                error: function(xhr, ajaxOptions, throwError) {
                    e.target.readOnly = false
                }
            })
        }
    }

    function rcvcustoms_btnAmendModalonClick() {
        if(rcvcustoms_docnoorigin.value.trim().length === 0) {
            alertify.warning('Please select DO Number')
            return
        }
        rcvcustoms_ModalTxtDO.value = rcvcustoms_docnoorigin.value
        rcvcustoms_ModalTxtDONew.value = rcvcustoms_docnoorigin.value

        rcvcustoms_ModalTxtNopen.value = rcvcustoms_regno.value
        rcvcustoms_ModalTxtNopenNew.value = rcvcustoms_regno.value

        rcvcustoms_ModalTxtTglpen.value = rcvcustoms_dd.value
        $("#rcvcustoms_ModalTxtTglpenNew").datepicker('update', rcvcustoms_dd.value)


        $("#RCVCUSTOMS_ModalAmend").modal('show')
    }

    function rcvcustoms_btn_set_locationOnClick() {
        document.getElementById(rcvcustoms_tablefokus).getElementsByTagName('tbody')[0].rows[rcvcustoms_selected_row].cells[rcvcustoms_tablefokus=='rcvcustoms_tbl_1' ? 16 : 15].innerHTML = rcvcustoms_modal_cmb_locTo.value
        $("#RCVCUSTOMS_LOCATION").modal('hide')
    }

    function rcvcustoms_show_docs_modal() {
        const selectedTab = document.querySelector('input[name="rcvcustoms_mode"]:checked').value
        console.log({selectedTab : selectedTab})
        $("#RCVCUSTOMS_DOCUMENTS").modal('show')
    }

    function rcvcustoms_get_image() {
        ClipboardUtils.readImage(function(data, error) {
            if (error) {
                console.error(error);
                return;
            }
            if (data) {
                rcvcustoms_image.src = data;
                $.ajax({
                    type: "POST",
                    url: "<?=$_ENV['APP_INTERNAL_API']?>receive/parse-image",
                    data: {'gambarnya' : data},
                    dataType: "json",
                    success: function (response) {
                        
                    }
                });
                return;
            }
            console.log('Image bitmap is not avaialble - copy it to clipboard.');
        });
    }
</script>