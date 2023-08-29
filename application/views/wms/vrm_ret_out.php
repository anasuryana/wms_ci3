<div style="padding: 10px">
    <div class="container-fluid">
        <div class="row">
            <div class="col" id="retrm-div-alert">

            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text">TX ID</span>
                    <input type="text" class="form-control" id="retrm_out_inc_txt_DO" required readonly placeholder="Autonumber">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#retrm_out_MODSAVED"><i class="fas fa-search"></i></button>
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Customer DO</span>
                    <input type="text" class="form-control" id="retrm_out_inc_txt_customerDO" required title="Customer Delivery Order">
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Customs Date</label>
                    <input type="text" class="form-control" id="retrm_out_inc_customs_date" readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">TX Status</span>
                    <input type="text" class="form-control" id="retrm_out_status" required readonly>
                    <span class="input-group-text" id="retrm_out_lbl_status">
                    </span>
                </div>
            </div>
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" title="Do Date">DO date</span>
                    <input type="text" class="form-control" id="retrm_out_txt_DOdate" required readonly title="Business Management">
                </div>
            </div>
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Inv No</span>
                    <input type="text" class="form-control" id="retrm_out_txt_invno" required>
                </div>
            </div>
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">SMT INV.NO</span>
                    <input type="text" class="form-control" id="retrm_out_txt_invsmt" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text">Business Group</span>
                    <select class="form-select" id="retrm_out_inc_cmb_bg" onchange="retrm_out_inc_cmb_bg_e_change()" title="Business Management">
                        <option value="-">-</option>
                        <?php
foreach ($lbg as $r) {
    ?>
                            <option value="<?=trim($r->MBSG_BSGRP)?>"><?=$r->MBSG_DESC?></option>
                        <?php
}
?>
                    </select>
                </div>
            </div>
            <div class="col-md-5 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">3rd Party</span>
                    <input type="text" class="form-control" id="retrm_out_inc_custname" required readonly title="Business Management">
                    <button class="btn btn-outline-primary" id="retrm_out_inc_btnfindmodcust" onclick="retrm_out_inc_btnfindmodcust_eC()"><i class="fas fa-search"></i></button>
                </div>
            </div>
            <div class="col-md-2 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Currency</span>
                    <input type="text" readonly class="form-control" id="retrm_out_inc_curr">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Consignee</span>
                    <select id="retrm_out_inc_consignee" class="form-select" title="Business Management">
                        <option value="-">-</option>
                        <?php
foreach ($ldeliverycode as $r) {
    ?>
                            <option value="<?=trim($r->MDEL_DELCD)?>"><?=$r->MDEL_DELCD?></option>
                        <?php
}
?>
                    </select>
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Transport</span>
                    <select class="form-select" id="retrm_out_inc_txt_transport" required>
                        <option value="-">-</option>
                        <?php
$todis = "";
foreach ($lplatno as $r) {
    $todis .= "<option value='" . $r->MSTTRANS_ID . "_" . $r->MSTTRANS_TYPE . "'>$r->MSTTRANS_ID</option>";
}
echo $todis;
?>
                    </select>
                    <input type="text" class="form-control" id="retrm_out_inc_txt_transporttype" required readonly>
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Customs Document</span>
                    <select class="form-select" id="retrm_out_cmb_bcdoc">
                        <option value="-">-</option>
                        <option value="25">BC 2.5</option>
                        <option value="27">BC 2.7</option>
                        <option value="30">BC 3.0</option>
                        <option value="41">BC 4.1</option>
                        <option value="261">BC 2.6.1</option>
                    </select>
                    <button class="btn btn-primary btn-sm" id="retrm_out_btn_customs" onclick="retrm_out_btn_customs_eC()"><span style="color: Yellow;"><i class="fas fa-book"></i></span></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Description</span>
                    <select class="form-select" id="retrm_out_description" title="Business Management">
                        <option value="DIKEMBALIKAN">DIKEMBALIKAN</option>
                        <option value="DIKEMBALIKAN NG">DIKEMBALIKAN NG</option>
                        <option value="DIJUAL">DIJUAL</option>
                        <option value="DISPOSE">DISPOSE</option>
                        <option value="DIPERBAIKI">DIPERBAIKI</option>
                        <option value="LAINNYA">LAINNYA</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">MEGA Doc.</span>
                    <input type="text" class="form-control" id="retrm_out_parentdoc" maxlength="50">
                </div>
            </div>
            <div class="col-md-5 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Reff Document</span>
                    <input type="text" class="form-control" id="retrm_out_rprdoc" maxlength="50">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-1">
                <div class="btn-group btn-group-sm">
                    <button title="Add new" class="btn btn-outline-primary" type="button" id="retrm_out_btn_new" onclick="retrm_out_btn_new_eC()"><i class="fas fa-file"></i></button>
                    <button title="Save" class="btn btn-primary" type="button" id="retrm_out_btn_save" onclick="retrm_out_btn_save_eC()"><i class="fas fa-save"></i></button>
                    <button title="Approve" class="btn btn-success" type="button" id="retrm_out_btn_appr" onclick="retrm_out_btn_appr_eC()">Approve</button>
                    <div class="btn-group btn-group-sm" role="group">
                        <button title="TPB Operations" class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="retrm_out_btn_tpb">TPB</button>
                        <ul class="dropdown-menu" aria-labelledby="retrm_out_tpb_btn">
                            <li><a id="retrm_out_btn_post" class="dropdown-item" href="#" onclick="retrm_out_btn_book_eC()">EXBC Booking</a></li>
                            <li><a id="retrm_out_btn_post" class="dropdown-item" href="#" onclick="retrm_out_btn_post_eC()"><i class="fas fa-clone"></i> Posting</a></li>
                            <li><a id="retrm_out_btn_post_cancel" onclick="retrm_out_btn_post_cancel_eCK()" class="dropdown-item disabled" href="#"><i class="fas fa-ban" style="color: red"></i> Cancel</a></li>
                        </ul>
                    </div>
                    <button title="Print" class="btn btn-outline-primary" type="button" id="retrm_out_btn_print" onclick="retrm_out_btn_print_eCK()"><i class="fas fa-print"></i></button>
                    <div class="btn-group btn-group-sm" role="group">
                        <button title="Export to ..." class="btn btn-outline-primary dropdown-toggle" type="button" id="retrm_out_btn_export" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-file-export"></i></button>
                        <ul class="dropdown-menu" aria-labelledby="retrm_out_btn_export">
                            <li><a class="dropdown-item" href="#" onclick="retrm_out_btn_toxls_e_click()"><i class="fas text-success fa-file-excel"></i> XLS</a></li>
                            <li><a class="dropdown-item" href="#" onclick="retrm_out_btn_tostx_xls()"><i class="fas fa-file-excel text-success"></i> Invoice,PL,DO</a></li>
                            <li><a class="dropdown-item" href="#" onclick="retrm_out_btn_to_ceisa40(this)">CEISA 4.0</a></li>
                        </ul>
                    </div>
                    <button title="User's log" class="btn btn-outline-info" id="retrm_out_btn_showinfo" onclick="retrm_out_btn_showinfo_e_click()"><i class="fas fa-info-circle"></i></button>
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Location From</span>
                    <select class="form-select" id="retrm_out_cmb_locfrom">
                        <option value="-">-</option>
                        <?=$llocfrom?>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1" id="retrm_out_div_infoAfterPost">

            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#retrm_out_it_itemdetail" type="button" role="tab" aria-controls="home" aria-selected="true">Item Resume</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#retrm_out_it_packlist" type="button" role="tab" aria-controls="profile" aria-selected="false">Packing List</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#retrm_out_it_donprc" type="button" role="tab" aria-controls="contact" aria-selected="false">DO/Price Reference</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="so-tab" data-bs-toggle="tab" data-bs-target="#retrm_out_it_salesorder" type="button" role="tab" aria-controls="salesorder" aria-selected="false">Sales Order</button>
                    </li>
                    <li class="nav-item" role="scrap">
                        <button class="nav-link" id="scr-tab" data-bs-toggle="tab" data-bs-target="#retrm_out_it_scrap" type="button" role="tab" aria-controls="scrap" aria-selected="false">Scrap Document</button>
                    </li>
                    <li class="nav-item" role="limbah_barang">
                        <button class="nav-link" id="limbah_barang-tab" data-bs-toggle="tab" data-bs-target="#retrm_out_it_limbah_barang" type="button" role="tab" aria-controls="limbah_barang" aria-selected="false">Limbah Barang</button>
                    </li>
                    <li class="nav-item" role="limbah_bahan_baku">
                        <button class="nav-link" id="limbah_bahan_baku-tab" data-bs-toggle="tab" data-bs-target="#retrm_out_it_limbah_bahan_baku" type="button" role="tab" aria-controls="limbah_bahan_baku" aria-selected="false">Limbah Bahan Baku</button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="retrm_out_it_itemdetail" role="tabpanel" aria-labelledby="home-tab">
                        <div class="container-fluid">
                            <div class="row mt-1">
                                <div class="col-md-6 mb-1">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" id="retrm_out_inc_btn_shortcut">Shortcut</button>
                                        <ul class="dropdown-menu">
                                            <li><a id="retrm_out_inc_btn_add_fromMega" class="dropdown-item" href="#" onclick="retrm_out_inc_btn_add_fromMega_eCK()">Add from MEGA</a></li>
                                            <li><a id="retrm_out_inc_btn_add_fromPK" class="dropdown-item" href="#" onclick="retrm_out_inc_btn_add_fromPK_eCK()">Add from PK</a></li>
                                            <li><a id="retrm_out_inc_btn_add_fromSCR" class="dropdown-item" href="#" onclick="retrm_out_inc_btn_add_fromSCR_eCK()">Add from Scrap</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-1 text-end">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-primary" id="retrm_out_inc_btn_add" onclick="retrm_out_inc_btn_add_e_click()"><i class="fas fa-plus"></i></button>
                                        <button type="button" class="btn btn-warning" id="retrm_out_inc_btn_minus" onclick="retrm_out_inc_minusrow()"><i class="fas fa-minus"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-1">
                                    <div class="table-responsive" id="retrm_out_inc_divku" onpaste="retrm_out_inc_e_pastecol1(event)">
                                        <table id="retrm_out_inc_tbl" class="table table-striped table-bordered table-sm table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="d-none">IDLINE</th>
                                                    <th>Item Id</th>
                                                    <th class="text-end">Qty</th>
                                                    <th>Remark</th>
                                                    <th class="bg-info">Item Description</th>
                                                    <th class="bg-info">Item Name</th>
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
                    <div class="tab-pane fade" id="retrm_out_it_packlist" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="container-fluid">
                            <div class="row mt-1">
                                <div class="col-md-6 mb-1">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-primary" id="retrm_out_inc_btncopyFromResume" onclick="retrm_out_inc_btncopyFromResume_eCK()">Copy from DO/Price</button>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-1 text-end">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-primary" id="retrm_out_inc_btn_add_packing" onclick="retrm_out_inc_btnadd()"><i class="fas fa-plus"></i></button>
                                        <button type="button" class="btn btn-warning" id="retrm_out_inc_btn_min_packing" onclick="retrm_out_inc_minusrow1()"><i class="fas fa-minus"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-1">
                                    <div class="table-responsive" id="retrm_out_inc_divku_packing">
                                        <table id="retrm_out_inc_tbl_packing" class="table table-striped table-bordered table-sm table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="d-none" title=""></th>
                                                    <th class="d-none text-end" title="Panjang">P</th>
                                                    <th class="d-none text-end" title="Lebar">L</th>
                                                    <th class="d-none text-end" title="Tinggi">T</th>
                                                    <th>Item Id</th>
                                                    <th class="text-end">Qty</th>
                                                    <th class="text-end">Net Weight</th>
                                                    <th class="text-end">Gross Weight</th>
                                                    <th>Measurement</th>
                                                    <th>Item Type</th>
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
                    <div class="tab-pane fade" id="retrm_out_it_donprc" role="tabpanel" aria-labelledby="contact-tab">
                        <div class="container-fluid">
                            <div class="row mt-1">
                                <div class="col-md-6 mb-1">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-primary" id="retrm_out_donprc_fifo" onclick="retrm_out_donprc_fifo_eCK()">FIFO</button>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-1 text-end">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-primary" id="retrm_out_donprc_tbl_add" onclick="retrm_out_donprc_tbl_add_eCK()"><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-1">
                                    <div class="table-responsive" id="retrm_out_donprc_div">
                                        <table id="retrm_out_donprc_tbl" class="table table-striped table-bordered table-sm table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="d-none">IDLINE</th>
                                                    <th class="d-none">NOAJU</th>
                                                    <th>NOPEN</th>
                                                    <th>DO Id</th>
                                                    <th>Incoming Date</th>
                                                    <th>Item Id</th>
                                                    <th class="text-end">Qty</th>
                                                    <th class="text-end">Price</th>
                                                    <th class="text-center">BC Type</th>
                                                    <th class="text-center">...</th>
                                                    <th>Item Type</th>
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
                    <div class="tab-pane fade" id="retrm_out_it_salesorder" role="tabpanel" aria-labelledby="so-tab">
                        <div class="container-fluid">
                            <div class="row mt-1">
                                <div class="col-md-6 mb-1">

                                </div>
                                <div class="col-md-6 mb-1 text-end">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-primary" id="retrm_out_so_tbl_add" onclick="retrm_out_so_tbl_add_eCK()"><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-1">
                                    <div class="table-responsive" id="retrm_out_so_div">
                                        <table id="retrm_out_so_tbl" class="table table-striped table-bordered table-sm table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="d-none">Lineid</th>
                                                    <th>SO Id</th>
                                                    <th>SO Line</th>
                                                    <th>Item Id</th>
                                                    <th class="text-end">Qty</th>
                                                    <th class="text-end">Price</th>
                                                    <th class="text-center">...</th>
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
                    <div class="tab-pane fade" id="retrm_out_it_scrap" role="tabpanel" aria-labelledby="scr-tab">
                        <div class="container-fluid">
                            <div class="row mt-1">
                                <div class="col-md-6 mb-1">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-primary" id="retrm_out_scr_tbl_add" onclick="retrm_out_scr_tbl_add_eCK()"><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-1 text-end">

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-1">
                                    <div class="table-responsive" id="retrm_out_scr_div">
                                        <table id="retrm_out_scr_tbl" class="table table-striped table-bordered table-sm table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="d-none">Line</th>
                                                    <th>Scrap Document</th>
                                                    <th class="text-center">...</th>
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
                    <div class="tab-pane fade" id="retrm_out_it_limbah_barang" role="tabpanel" aria-labelledby="scr-tab">
                        <div class="container-fluid">
                            <div class="row mt-1">
                                <div class="col-md-6 mb-1">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-primary" id="retrm_out_limbah_barang_tbl_copy" onclick="retrm_out_limbah_barang_tbl_copy_eCK()">Copy from resume</button>
                                        <button type="button" class="btn btn-primary" id="retrm_out_limbah_barang_tbl_add" onclick="retrm_out_limbah_barang_tbl_add_eCK()"><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-1 text-end">

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-1">
                                    <div class="table-responsive" id="retrm_out_limbah_barang_div">
                                        <table id="retrm_out_limbah_barang_tbl" class="table table-striped table-bordered table-sm table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="d-none">Line</th>
                                                    <th>Item Id</th>
                                                    <th class="text-end">Item Qty</th>
                                                    <th class="text-end">Price</th>
                                                    <th class="text-center">...</th>
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
        </div>
    </div>
</div>
<div class="modal fade" id="retrm_out_MODPRINT">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Document Type</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-1">
                        <ul class="list-group text-center">
                            <li class="list-group-item">
                                <input class="form-check-input" type="checkbox" value="1" id="retrm_out_ckDO">
                                <label class="form-check-label" for="retrm_out_ckDO">Delivery Order</label>
                            </li>
                            <li class="list-group-item">
                                <input class="form-check-input" type="checkbox" value="1" id="retrm_out_ckINV">
                                <label class="form-check-label" for="retrm_out_ckINV">Invoice</label>
                            </li>
                            <li class="list-group-item">
                                <input class="form-check-input" type="checkbox" value="1" id="retrm_out_ckPL">
                                <label class="form-check-label" for="retrm_out_ckPL">Packing List</label>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-1 text-center">
                        <button class="btn btn-sm btn-primary" title="Print" id="retrm_out_btnprintseldocs" onclick="retrm_out_btnprintseldocs_eCK()"><i class="fas fa-print"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="retrm_out_MODSAVED">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Transaction List</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div class="input-group input-group-sm mb-1">
                            <span class="input-group-text">Search</span>
                            <select id="retrm_out_txsrchby" class="form-select" onchange="document.getElementById('retrm_out_txtxtsearch').focus()">
                                <option value="tx">TX ID</option>
                                <option value="txdate">TX Date</option>
                                <option value="cusnm">Customer</option>
                            </select>
                            <input type="text" class="form-control" id="retrm_out_txtxtsearch" onkeypress="retrm_out_txtxtsearch_eKP(event)" maxlength="25" required placeholder="...">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">
                                <input type="checkbox" class="form-check-input" checked id="retrm_out_ckperiod" onclick="document.getElementById('retrm_out_txtxtsearch').focus()">
                            </label>
                            <select id="retrm_out_monthfilter" class="form-select" onchange="document.getElementById('retrm_out_txtxtsearch').focus()">
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
                            <input type="number" class="form-control" id="retrm_out_year" maxlength="4">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <span class="badge bg-info" id="retrm_out_txtbl_lblinfo"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="table-responsive" id="retrm_out_txdivku">
                            <table id="retrm_out_txtbl" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer;font-size:81%">
                                <thead class="table-light">
                                    <tr>
                                        <th>TX ID</th><!-- 0 -->
                                        <th class="d-none">TX Date</th><!-- 1 -->
                                        <th>Description</th><!-- 2 -->
                                        <th class="d-none">Customer ID</th><!-- 3 -->
                                        <th>3rd Party Name</th><!-- 4 -->
                                        <th>Invoice</th><!-- 5 -->
                                        <th>Invoice SMT</th><!-- 6 -->
                                        <th>Transportation</th><!-- 7 -->
                                        <th>Remark</th><!-- 8 -->
                                        <th class="d-none">Currency</th><!-- 9 -->
                                        <th class="d-none">Transportation Type</th><!-- 10 -->
                                        <th>Consignee</th><!-- 11 -->
                                        <th class="d-none">is_replacement</th><!-- 12 -->
                                        <th class="d-none">is_vat</th><!-- 13 -->
                                        <th class="d-none">is_kanbandelivery</th><!-- 14 -->
                                        <th class="d-none">KANTOR_ASAL</th><!-- 15 -->
                                        <th class="d-none">Created By</th><!-- 16 -->
                                        <th class="d-none">Created Time</th><!-- 17 -->
                                        <th class="d-none">Last update By</th><!-- 18 -->
                                        <th class="d-none">Last update Time</th><!-- 19 -->
                                        <th class="d-none">Approved By</th><!-- 20 -->
                                        <th class="d-none">Approved Time</th><!-- 21 -->
                                        <th class="d-none">Posted By</th><!-- 22 -->
                                        <th class="d-none">Posted Time</th><!-- 23 -->
                                        <th class="d-none">NOAJU</th><!-- 24 -->
                                        <th class="d-none">BCTYPE</th><!-- 25 -->
                                        <th class="d-none">NOPEN</th><!-- 26 -->
                                        <th class="d-none">KANTORTUJUAN</th><!-- 27 -->
                                        <th class="d-none">TUJUANPENGIRIMAN</th><!-- 28 -->
                                        <th class="d-none">INVDATE</th><!-- 29 -->
                                        <th>Business Group</th><!-- 30 -->
                                        <th>Customer DO</th>
                                        <th class="d-none">TPB ASAL</th>
                                        <th class="d-none">TPB TUJUAN</th>
                                        <th>Customs Date</th>
                                        <th class="d-none">SKB</th>
                                        <th class="d-none">Cara_angkut</th>
                                        <th class="d-none">TANGGAL SKB</th>
                                        <th>Status</th>
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
<div class="modal fade" id="retrm_out_MODCUS">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Data List</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div class="input-group input-group-sm mb-1">
                            <span class="input-group-text">Search</span>
                            <input type="text" class="form-control" id="retrm_out_txtsearchcus" onkeypress="retrm_out_txtsearchcus_eKP(event)" maxlength="15" required placeholder="...">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <span class="input-group-text">Search by</span>
                            <select id="retrm_out_srchby" class="form-select" onchange="document.getElementById('retrm_out_txtsearchcus').focus()">
                                <option value="nm">Name</option>
                                <option value="cd">Code</option>
                                <option value="ad">Address</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="table-responsive" id="retrm_out_tblcus_div">
                            <table id="retrm_out_tblcus" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer">
                                <thead class="table-light">
                                    <tr>
                                        <th>Code</th>
                                        <th>Currency</th>
                                        <th>Name</th>
                                        <th>Address</th>
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
<div class="modal fade" id="retrm_out_MODPK">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Data List</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div class="input-group input-group-sm mb-1">
                            <span class="input-group-text">Contract Number</span>
                            <input type="text" class="form-control" id="retrm_out_cpk_txtpk" onkeypress="retrm_out_cpk_txtpk_eKP(event)">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="table-responsive" id="retrm_out_cpk_tblpk_div">
                            <table id="retrm_out_cpk_tblpk" class="table table-sm table-bordered table-hover" style="width:100%">
                                <thead class="table-light">
                                    <tr>
                                        <th rowspan="2" class="align-middle">Part Code</th>
                                        <th rowspan="2" class="align-middle">Part Name</th>
                                        <th colspan="2" class="text-center">Qty</th>
                                        <th rowspan="2" class="align-middle d-none">NOAJU</th> <!-- 4 -->
                                        <th rowspan="2" class="align-middle d-none">NOPEN</th> <!-- 5 -->
                                        <th rowspan="2" class="align-middle d-none">DO ID</th> <!-- 6 -->
                                        <th rowspan="2" class="align-middle d-none">DO DATE</th> <!-- 7 -->
                                        <th rowspan="2" class="align-middle d-none">PRICE</th> <!-- 8 -->
                                        <th rowspan="2" class="align-middle d-none">BCTYPE</th> <!-- 9 -->
                                        <th rowspan="2" class="align-middle d-none">WH</th> <!-- 10 -->
                                    </tr>
                                    <tr>
                                        <th class="text-center">Balance</th>
                                        <th class="text-center">Return</th>
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
                <button type="button" class="btn btn-primary btn-sm" id="retrm_out_cpk_btnConfirm" onclick="retrm_out_cpk_btnConfirm_eCK()">Apply</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="retrm_out_MODSCR">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Data List</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div class="input-group input-group-sm mb-1">
                            <span class="input-group-text">Search</span>
                            <input type="text" class="form-control" id="retrm_out_cscr_txtsearch" onkeypress="retrm_out_cscr_txtsearch_eKP(event)">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="input-group input-group-sm mb-1">
                            <span class="input-group-text">Date</span>
                            <input type="text" class="form-control" id="retrm_out_scr_period_search" readonly onchange="document.getElementById('retrm_out_cscr_txtsearch').focus()">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="table-responsive" id="retrm_out_cscr_tbl_div">
                            <table id="retrm_out_cscr_tbl" class="table table-sm table-bordered table-hover" style="width:100%">
                                <thead class="table-light">
                                    <tr>
                                        <th rowspan="2" class="align-middle">Part Code</th>
                                        <th rowspan="2" class="align-middle">Part Name</th>
                                        <th colspan="2" class="text-center">Qty</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">Balance</th>
                                        <th class="text-center">Dispose</th>
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
                <button type="button" class="btn btn-primary btn-sm" id="retrm_out_cscr_btnConfirm" onclick="retrm_out_cscr_btnConfirm_eCK()">Apply</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="retrm_out_MODSCRDOC">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Data List</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div class="input-group input-group-sm mb-1">
                            <span class="input-group-text">Search</span>
                            <input type="text" class="form-control" id="retrm_out_scrdoc_txtsearch" onkeypress="retrm_out_scrdoc_txtsearch_eKP(event)">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="table-responsive" id="retrm_out_scrdoc_tbl_div">
                            <table id="retrm_out_scrdoc_tbl" class="table table-sm table-bordered table-hover" style="width:100%">
                                <thead class="table-light">
                                    <tr>
                                        <th class="align-middle text-center">ID TRANS</th>
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
<div class="modal fade" id="retrm_out_SUPPLIER">
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
                            <input type="text" class="form-control" id="retrm_out_txtsearchSUP" onkeypress="retrm_out_txtsearchSUP_eKP(event)" maxlength="15" required placeholder="...">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col text-end mb-1">
                        <span class="badge bg-info" id="retrm_out_tblsup_lblinfo"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="table-responsive" id="retrm_out_tblsup_div">
                            <table id="retrm_out_tblsup" class="table table-sm table-striped table-bordered table-hover" style="width:100%">
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
<div class="modal fade" id="retrm_out_inc_CUSTOMSMOD">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title text-info">Dokumen BC 2.7</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Nomor Pengajuan</label>
                            <input type="text" id="retrm_out_inc_txt_noaju" class="form-control" maxlength="6">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Nomor Pendaftaran</label>
                            <input type="text" id="retrm_out_inc_txt_nopen" class="form-control" maxlength="6" readonly>
                            <button class="btn btn-primary" id="retrm_out_inc_btn_sync_pendaftaran" onclick="retrm_out_inc_btn_sync_pendaftaran_e_click(this)" title="Get Nomor & Tanggal Pendaftaran from CEISA"><i class="fas fa-sync"></i></button>
                        </div>
                    </div>
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Tanggal Pendaftaran</label>
                            <input type="text" id="retrm_out_inc_txt_tglpen" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Kantor Asal</label>
                            <select class="form-select" id="retrm_out_inc_fromoffice">
                                <?php
$tohtml = "<option value='-'>-</option>";
foreach ($ldestoffice as $r) {
    $tohtml .= "<option value='$r[KODE_KANTOR]'>$r[URAIAN_KANTOR]</option>";
}
echo $tohtml;
?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Kantor Tujuan</label>
                            <select class="form-select" id="retrm_out_inc_destoffice">
                                <?php
$tohtml = "<option value='-'>-</option>";
foreach ($ldestoffice as $r) {
    $tohtml .= "<option value='$r[KODE_KANTOR]'>$r[URAIAN_KANTOR]</option>";
}
echo $tohtml;
?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Jenis TPB Asal</label>
                            <select class="form-select" id="retrm_out_inc_cmb_jenisTPB">
                                <option value="-">-</option>
                                <?php
$tohtml = "";
foreach ($lkantorpabean as $r) {
    $tohtml .= "<option value='" . trim($r['KODE_JENIS_TPB']) . "'>$r[URAIAN_JENIS_TPB]</option>";
}
echo $tohtml;
?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Jenis TPB Tujuan</label>
                            <select class="form-select" id="retrm_out_inc_cmb_jenisTPBtujuan">
                                <option value="-">-</option>
                                <?php
$tohtml = "";
foreach ($lkantorpabean as $r) {
    $tohtml .= "<option value='" . trim($r['KODE_JENIS_TPB']) . "'>$r[URAIAN_JENIS_TPB]</option>";
}
echo $tohtml;
?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Tujuan Pengiriman</label>
                            <select class="form-select" id="retrm_out_inc_cmb_tujuanpengiriman">
                                <option value="-">-</option>
                                <?php
$tohtml = "";
foreach ($ltujuanpengiriman as $r) {
    if (trim($r['KODE_DOKUMEN']) == '27') {
        $tohtml .= "<option value='" . trim($r['KODE_TUJUAN_PENGIRIMAN']) . "'>$r[URAIAN_TUJUAN_PENGIRIMAN]</option>";
    }
}
echo $tohtml;
?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Contract No</label>
                            <input type="text" id="retrm_out_inc_txt_nokontrak" class="form-control" maxlength="50">
                        </div>
                    </div>
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Contract Date</label>
                            <input type="text" id="retrm_out_inc_txt_tglkontrak" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Pemberitahu</label>
                            <input type="text" class="form-control retrm_nama_pemberitahu" maxlength="50" value="GUSTI AYU KETUT Y">
                        </div>
                    </div>
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Jabatan</label>
                            <input type="text" class="form-control retrm_jabatan_pemberitahu" maxlength="50" value="J.SUPERVISOR">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col">
                            <button type="button" class="btn btn-primary btn-sm" id="retrm_out_inc_z_btn_relink" onclick="retrm_out_inc_z_btn_relink_e_click(this)">Re-link IT Inventory</button>
                        </div>
                        <div class="col text-end">
                            <button type="button" class="btn btn-primary btn-sm" id="retrm_out_inc_z_btn_save" onclick="retrm_out_inc_z_btn_save_e_click()">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="retrm_out_inc_CUSTOMSMOD41">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title text-info">Dokumen BC 4.1</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Nomor Pengajuan</label>
                            <input type="text" id="retrm_out_inc_txt_noaju41" class="form-control" maxlength="6">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Nomor Pendaftaran</label>
                            <input type="text" id="retrm_out_inc_txt_nopen41" class="form-control" maxlength="6" readonly>
                            <button class="btn btn-primary" id="retrm_out_inc_btn_sync_pendaftaran41" onclick="retrm_out_inc_btn_sync_pendaftaran41_e_click(this)" title="Get Nomor & Tanggal Pendaftaran from CEISA"><i class="fas fa-sync"></i></button>
                        </div>
                    </div>
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Tanggal Pendaftaran</label>
                            <input type="text" id="retrm_out_inc_txt_tglpen41" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Kantor Pabean</label>
                            <select class="form-select" id="retrm_out_inc_fromoffice41">
                                <?php
$tohtml = "<option value='-'>-</option>";
foreach ($ldestoffice as $r) {
    $tohtml .= "<option value='$r[KODE_KANTOR]'>$r[URAIAN_KANTOR]</option>";
}
echo $tohtml;
?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Jenis TPB</label>
                            <select class="form-select" id="retrm_out_inc_cmb_jenisTPB41">
                                <option value="-">-</option>
                                <?php
$tohtml = "";
foreach ($lkantorpabean as $r) {
    $tohtml .= "<option value='" . trim($r['KODE_JENIS_TPB']) . "'>$r[URAIAN_JENIS_TPB]</option>";
}
echo $tohtml;
?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Tujuan Pengiriman</label>
                            <select class="form-select" id="retrm_out_inc_cmb_tujuanpengiriman41">
                                <option value="-">-</option>
                                <?php
$tohtml = "";
foreach ($ltujuanpengiriman as $r) {
    if (trim($r['KODE_DOKUMEN']) == '41') {
        $tohtml .= "<option value='" . trim($r['KODE_TUJUAN_PENGIRIMAN']) . "'>$r[URAIAN_TUJUAN_PENGIRIMAN]</option>";
    }
}
echo $tohtml;
?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Contract No</label>
                            <input type="text" id="retrm_out_inc_txt_nokontrak41" class="form-control" maxlength="50">
                        </div>
                    </div>
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Contract Date</label>
                            <input type="text" id="retrm_out_inc_txt_tglkontrak41" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Pemberitahu</label>
                            <input type="text" class="form-control retrm_nama_pemberitahu" maxlength="50" value="GUSTI AYU KETUT Y">
                        </div>
                    </div>
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Jabatan</label>
                            <input type="text" class="form-control retrm_jabatan_pemberitahu" maxlength="50" value="J.SUPERVISOR">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col">
                            <button type="button" class="btn btn-primary btn-sm" id="retrm_out_inc_z_btn_relink41" onclick="retrm_out_inc_z_btn_relink_e_click(this)">Re-link IT Inventory</button>
                        </div>
                        <div class="col text-end">
                            <button type="button" class="btn btn-primary btn-sm" id="retrm_out_inc_z_btn_save41" onclick="retrm_out_inc_z_btn_save41_e_click()">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="retrm_out_inc_CUSTOMSMOD261">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title text-info">Dokumen BC 2.6.1</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Nomor Pengajuan</label>
                            <input type="text" id="retrm_out_inc_txt_noaju261" class="form-control" maxlength="6">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Nomor Pendaftaran</label>
                            <input type="text" id="retrm_out_inc_txt_nopen261" class="form-control" maxlength="6" readonly>
                            <button class="btn btn-primary" id="retrm_out_inc_btn_sync_pendaftaran261" onclick="retrm_out_inc_btn_sync_pendaftaran261_e_click(this)" title="Get Nomor & Tanggal Pendaftaran from CEISA"><i class="fas fa-sync"></i></button>
                        </div>
                    </div>
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Tanggal Pendaftaran</label>
                            <input type="text" id="retrm_out_inc_txt_tglpen261" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Kantor Pabean</label>
                            <select class="form-select" id="retrm_out_inc_fromoffice261">
                                <?php
$tohtml = "<option value='-'>-</option>";
foreach ($ldestoffice as $r) {
    $tohtml .= "<option value='$r[KODE_KANTOR]'>$r[URAIAN_KANTOR]</option>";
}
echo $tohtml;
?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Tujuan Pengiriman</label>
                            <select class="form-select" id="retrm_out_inc_cmb_tujuanpengiriman261">
                                <option value="-">-</option>
                                <?php
$tohtml = "";
foreach ($ltujuanpengiriman as $r) {
    if (trim($r['KODE_DOKUMEN']) == '261') {
        $tohtml .= "<option value='" . trim($r['KODE_TUJUAN_PENGIRIMAN']) . "'>$r[URAIAN_TUJUAN_PENGIRIMAN]</option>";
    }
}
echo $tohtml;
?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Contract No</label>
                            <input type="text" id="retrm_out_inc_txt_nokontrak261" class="form-control" maxlength="50">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Jenis Sarana Pengangkut</label>
                            <select class="form-select" id="retrm_out_inc_jenis_saranapengangkut261">
                                <?php
$tohtml = "<option value='-'>-</option>";
foreach ($lwaytransport as $r) {
    $tohtml .= "<option value='$r[KODE_CARA_ANGKUT]'>$r[URAIAN_CARA_ANGKUT]</option>";
}
echo $tohtml;
?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Pemberitahu</label>
                            <input type="text" class="form-control retrm_nama_pemberitahu" maxlength="50" value="GUSTI AYU KETUT Y">
                        </div>
                    </div>
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Jabatan</label>
                            <input type="text" class="form-control retrm_jabatan_pemberitahu" maxlength="50" value="J.SUPERVISOR">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-sm" id="retrm_out_inc_z_btn_save261" onclick="retrm_out_inc_z_btn_save261_e_click()">Save changes</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="retrm_out_inc_CUSTOMSMOD30">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title text-info">Dokumen BC 3.0</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Nomor Pengajuan</label>
                            <input type="text" id="retrm_out_inc_txt_noaju30" class="form-control" maxlength="26">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Nomor Pendaftaran</label>
                            <input type="text" id="retrm_out_inc_txt_nopen30" class="form-control" maxlength="6">
                        </div>
                    </div>
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Tanggal Pendaftaran</label>
                            <input type="text" id="retrm_out_inc_txt_tglpen30" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Pemberitahu</label>
                            <input type="text" class="form-control retrm_nama_pemberitahu" maxlength="50" value="GUSTI AYU KETUT Y">
                        </div>
                    </div>
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Jabatan</label>
                            <input type="text" class="form-control retrm_jabatan_pemberitahu" maxlength="50" value="J.SUPERVISOR">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-sm" id="retrm_out_inc_z_btn_save30" onclick="retrm_out_inc_z_btn_save30_e_click()">Save changes</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="retrm_out_inc_CUSTOMSMOD25">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title text-info">Dokumen BC 2.5</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Nomor Pengajuan</label>
                            <input type="text" id="retrm_out_inc_txt_noaju25" class="form-control" maxlength="6">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Nomor Pendaftaran</label>
                            <input type="text" id="retrm_out_inc_txt_nopen25" class="form-control" maxlength="6" readonly>
                            <button class="btn btn-primary" id="retrm_out_inc_btn_sync_pendaftaran25" onclick="retrm_out_inc_btn_sync_pendaftaran25_e_click(this)" title="Get Nomor & Tanggal Pendaftaran from CEISA"><i class="fas fa-sync"></i></button>
                        </div>
                    </div>
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Tanggal Pendaftaran</label>
                            <input type="text" id="retrm_out_inc_txt_tglpen25" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Kantor Pabean</label>
                            <select class="form-select" id="retrm_out_inc_fromoffice25">
                                <?php
$tohtml = "<option value='-'>-</option>";
foreach ($ldestoffice as $r) {
    $tohtml .= "<option value='$r[KODE_KANTOR]'>$r[URAIAN_KANTOR]</option>";
}
echo $tohtml;
?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Jenis TPB Asal</label>
                            <select class="form-select" id="retrm_out_inc_cmb_jenisTPB25">
                                <option value="-">-</option>
                                <?php
$tohtml = "";
foreach ($lkantorpabean as $r) {
    $tohtml .= "<option value='" . trim($r['KODE_JENIS_TPB']) . "'>$r[URAIAN_JENIS_TPB]</option>";
}
echo $tohtml;
?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Surat Keterangan Bebas (SKB) PPh</label>
                            <input type="text" id="retrm_out_inc_txt_noskb" class="form-control" maxlength="100">
                        </div>
                    </div>
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Tanggal SKB</label>
                            <input type="text" id="retrm_out_inc_txt_noskb_tgl" class="form-control" maxlength="10" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Jenis Saran Pengangkut</label>
                            <select class="form-select" id="retrm_out_inc_jenis_saranapengangkut25">
                                <?php $tohtml = "<option value='-'>-</option>";
foreach ($lwaytransport as $r) {
    $tohtml .= "<option value='$r[KODE_CARA_ANGKUT]'>$r[URAIAN_CARA_ANGKUT]</option>";
}
echo $tohtml;
?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Pemberitahu</label>
                            <input type="text" class="form-control retrm_nama_pemberitahu" maxlength="50" value="GUSTI AYU KETUT Y">
                        </div>
                    </div>
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Jabatan</label>
                            <input type="text" class="form-control retrm_jabatan_pemberitahu" maxlength="50" value="J.SUPERVISOR">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col">
                            <button type="button" class="btn btn-primary btn-sm" id="retrm_out_inc_z_btn_relink25" onclick="retrm_out_inc_z_btn_relink_e_click(this)">Re-link IT Inventory</button>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-primary btn-sm" id="retrm_out_inc_z_btn_save25" onclick="retrm_out_inc_z_btn_save25_e_click()">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="retrm_out_inc_PROGRESS">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title"><i class="fas fa-info text-info"></i> </h4>

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
                        <h3> <span id="retrm_out_inc_span_timer">00:00:00</span> </h3>
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
<div class="modal fade" id="retrm_out_inc_PROGRESS_BOOK">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title"><i class="fas fa-info text-info"></i> </h4>

            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-1 text-center">
                        Booking ...
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-1 text-center">
                        <i class="fas fa-sync fa-spin fa-7x text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="retrm_out_inc_MODINFO">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Info</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-1 p-1">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Created by</span>
                            <input type="text" id="retrm_out_inc_txt_createdby" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-1 p-1">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Created Time</span>
                            <input type="text" id="retrm_out_inc_txt_createdtime" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-1 p-1">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text" title="Last Update by">LU by</span>
                            <input type="text" id="retrm_out_inc_txt_luby" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-1 p-1">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text" title="Last Update Time">LU Time</span>
                            <input type="text" id="retrm_out_inc_txt_lutime" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-1 p-1">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Approved by</span>
                            <input type="text" id="retrm_out_inc_txt_apprby" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-1 p-1">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Approved Time</span>
                            <input type="text" id="retrm_out_inc_txt_apprtime" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-1 p-1">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Posted by</span>
                            <input type="text" id="retrm_out_inc_txt_postedby" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-1 p-1">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Posted Time</span>
                            <input type="text" id="retrm_out_inc_txt_postedtime" class="form-control" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="retrm_out_MODDOPRC">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Search</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div class="input-group input-group-sm mb-1">
                            <span class="input-group-text">Item</span>
                            <select id="retrm_out_doprc_selitem" class="form-select" onchange="retrm_out_doprc_selitem_eCH()">
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="input-group input-group-sm mb-1">
                            <span class="input-group-text">Search by</span>
                            <select id="retrm_out_doprc_searchby" class="form-select" onchange="document.getElementById('retrm_out_txt_doprc_search').focus()">
                                <option value="do">DO</option>
                                <option value="prc">Price</option>
                                <option value="itemname">Item Name</option>
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="input-group input-group-sm mb-1">
                            <span class="input-group-text">Search</span>
                            <input type="text" class="form-control" id="retrm_out_txt_doprc_search" onkeypress="retrm_out_txt_doprc_search_eKP(event)" maxlength="50" required placeholder="...">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="table-responsive" id="retrm_out_doprc_div">
                            <table id="retrm_out_doprc_tbl" class="table table-sm table-striped table-bordered table-hover" style="width:100%;font-size:81%">
                                <thead class="table-light">
                                    <tr>
                                        <th class="d-none">NOAJU</th>
                                        <th>NOPEN</th>
                                        <th>DO Id</th>
                                        <th>Incoming Date</th>
                                        <th>Item Id</th>
                                        <th>Item Name</th>
                                        <th class="text-end">Price</th>
                                        <th class="text-end">Balance Qty</th>
                                        <th>BC Type</th>
                                        <th>Supplier</th>
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
<div class="modal fade" id="retrm_out_MODSOPRC">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Search</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div class="input-group input-group-sm mb-1">
                            <span class="input-group-text">Item</span>
                            <select id="retrm_out_soprc_selitem" class="form-select" onchange="document.getElementById('retrm_out_txt_soprc_search').focus()">
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="input-group input-group-sm mb-1">
                            <span class="input-group-text">Search by</span>
                            <select id="retrm_out_soprc_searchby" class="form-select" onchange="document.getElementById('retrm_out_txt_soprc_search').focus()">
                                <option value="so">SO</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-1">
                        <button class="btn btn-outline-primary btn-sm" type="button" id="retrm_out_btn_sorm" onclick="retrm_out_btn_sorm_eCK()">Search</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="table-responsive" id="retrm_out_soprc_div">
                            <table id="retrm_out_soprc_tbl" class="table table-sm table-striped table-bordered table-hover" style="width:100%;font-size:81%">
                                <thead class="table-light">
                                    <tr>
                                        <th>SO Id</th>
                                        <th>SO Line</th>
                                        <th>Item Id</th>
                                        <th class="text-end">Price</th>
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
<div class="modal fade" id="retrm_out_PROGRESS_CNCL">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title"><i class="fas fa-info text-info"></i> </h4>

            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-1 text-center">
                        Canceling ...
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-1 text-center">
                        <i class="fas fa-sync fa-spin fa-7x text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var retrm_out_inc_selected_table = ''
    var retrm_out_inc_selected_row = ''
    var retrm_out_inc_selected_col = ''
    var retrm_out_inc_selected_row1 = ''
    var retrm_out_inc_selected_col1 = ''
    var retrm_out_inc_custcd = '-'
    var retrm_out_inc_g_string = (new Date().getMonth() + 1).toString()
    var retrm_out_inc_seconds = 0
    var retrm_out_inc_minutes = 0
    var retrm_out_inc_hours = 0
    var retrm_out_inc_t
    var retrm_out_inc_timerspan = document.getElementById('retrm_out_inc_span_timer')
    var retrm_rs_booked = []

    retrm_out_inc_g_string = retrm_out_inc_g_string < 10 ? '0' + retrm_out_inc_g_string : retrm_out_inc_g_string.toString()
    document.getElementById('retrm_out_monthfilter').value = retrm_out_inc_g_string
    document.getElementById('retrm_out_year').value = new Date().getFullYear()

    function retrm_out_add() {
        retrm_out_inc_seconds++;
        if (retrm_out_inc_seconds >= 60) {
            retrm_out_inc_seconds = 0;
            retrm_out_inc_minutes++;
            if (retrm_out_inc_minutes >= 60) {
                retrm_out_inc_minutes = 0;
                retrm_out_inc_hours++;
            }
        }

        retrm_out_inc_timerspan.innerText = (retrm_out_inc_hours ? (retrm_out_inc_hours > 9 ? retrm_out_inc_hours : "0" + retrm_out_inc_hours) : "00") +
            ":" + (retrm_out_inc_minutes ? (retrm_out_inc_minutes > 9 ? retrm_out_inc_minutes : "0" + retrm_out_inc_minutes) : "00") +
            ":" + (retrm_out_inc_seconds > 9 ? retrm_out_inc_seconds : "0" + retrm_out_inc_seconds)

        retrm_out_timer()
    }

    function retrm_out_btn_post_cancel_eCK() {
        let msj = document.getElementById('retrm_out_inc_txt_DO')
        if (msj.value.trim().length === 0) {
            msj.focus()
            alertify.message('TX ID is required')
            return
        }
        if (!confirm('Are you sure ?')) {
            return;
        }
        let mymodal = new bootstrap.Modal(document.getElementById("retrm_out_PROGRESS_CNCL"), {
            backdrop: 'static',
            keyboard: false
        });
        mymodal.show()
        $.ajax({
            type: "POST",
            url: "<?=base_url('DELV/cancelposting')?>",
            data: {
                msj: msj.value
            },
            dataType: "JSON",
            success: function(response) {
                alertify.message(response.status[0].msg)
                document.getElementById('retrm_out_btn_post_cancel').classList.add('disabled')
                document.getElementById('retrm_out_status').value = "Approved"
                mymodal.hide()
            },
            error: function(xhr, xopt, xthrow) {
                document.getElementById('retrm_out_btn_post_cancel').classList.add('disabled')
                mymodal.hide()
                alertify.error(xthrow)
            }
        })
    }

    function retrm_out_inc_btn_sync_pendaftaran_e_click(pElement) {
        pElement.disabled = true
        pElement.innerHTML = `<i class="fas fa-sync fa-spin"></i>`
        const itemcode = document.getElementById('retrm_out_inc_txt_DO').value;
        $.ajax({
            type: "get",
            url: "<?=base_url('DELV/get_info_pendaftaran')?>",
            data: {
                insj: itemcode
            },
            dataType: "json",
            success: function(response) {
                pElement.disabled = false
                pElement.innerHTML = `<i class="fas fa-sync"></i>`
                if (response.status[0].cd != '0') {
                    if (response.data[0].NOMOR_DAFTAR.length == 6) {
                        document.getElementById('retrm_out_inc_txt_nopen').value = response.data[0].NOMOR_DAFTAR;
                        document.getElementById('retrm_out_inc_txt_tglpen').value = response.data[0].TANGGAL_DAFTAR.substr(0, 10);
                        alertify.success("OK");
                    } else {
                        alertify.message('NOMOR PENDAFTARAN is not recevied yet');
                    }
                } else {
                    alertify.message(response.status[0].msg);
                }
            },
            error: function(xhr, xopt, xthrow) {
                pElement.disabled = false
                pElement.innerHTML = `<i class="fas fa-sync"></i>`
                alertify.error(xthrow);
            }
        })
    }

    function retrm_out_inc_btn_sync_pendaftaran41_e_click(pElement) {
        pElement.disabled = true
        pElement.innerHTML = `<i class="fas fa-sync fa-spin"></i>`
        const itemcode = document.getElementById('retrm_out_inc_txt_DO').value;
        $.ajax({
            type: "get",
            url: "<?=base_url('DELV/get_info_pendaftaran')?>",
            data: {
                insj: itemcode
            },
            dataType: "json",
            success: function(response) {
                pElement.disabled = false
                pElement.innerHTML = `<i class="fas fa-sync"></i>`
                if (response.status[0].cd != '0') {
                    if (response.data[0].NOMOR_DAFTAR.length == 6) {
                        document.getElementById('retrm_out_inc_txt_nopen41').value = response.data[0].NOMOR_DAFTAR;
                        document.getElementById('retrm_out_inc_txt_tglpen41').value = response.data[0].TANGGAL_DAFTAR.substr(0, 10);
                        alertify.success("OK");
                    } else {
                        alertify.message('NOMOR PENDAFTARAN is not recevied yet');
                    }
                } else {
                    alertify.message(response.status[0].msg);
                }
            },
            error: function(xhr, xopt, xthrow) {
                pElement.disabled = false
                pElement.innerHTML = `<i class="fas fa-sync"></i>`
                alertify.error(xthrow);
            }
        })
    }

    function retrm_out_inc_btn_sync_pendaftaran261_e_click(pElement) {
        pElement.disabled = true
        pElement.innerHTML = `<i class="fas fa-sync fa-spin"></i>`
        const itemcode = document.getElementById('retrm_out_inc_txt_DO').value;
        $.ajax({
            type: "get",
            url: "<?=base_url('DELV/get_info_pendaftaran')?>",
            data: {
                insj: itemcode
            },
            dataType: "json",
            success: function(response) {
                pElement.disabled = false
                pElement.innerHTML = `<i class="fas fa-sync"></i>`
                if (response.status[0].cd != '0') {
                    if (response.data[0].NOMOR_DAFTAR.length == 6) {
                        document.getElementById('retrm_out_inc_txt_nopen261').value = response.data[0].NOMOR_DAFTAR
                        document.getElementById('retrm_out_inc_txt_tglpen261').value = response.data[0].TANGGAL_DAFTAR.substr(0, 10)
                        alertify.success("OK");
                    } else {
                        alertify.message('NOMOR PENDAFTARAN is not recevied yet');
                    }
                } else {
                    alertify.message(response.status[0].msg);
                }
            },
            error: function(xhr, xopt, xthrow) {
                pElement.disabled = false
                pElement.innerHTML = `<i class="fas fa-sync"></i>`
                alertify.error(xthrow)
            }
        })
    }

    function retrm_out_inc_btn_sync_pendaftaran25_e_click(pElement) {
        pElement.disabled = true
        pElement.innerHTML = `<i class="fas fa-sync fa-spin"></i>`
        const itemcode = document.getElementById('retrm_out_inc_txt_DO').value;
        $.ajax({
            type: "get",
            url: "<?=base_url('DELV/get_info_pendaftaran')?>",
            data: {
                insj: itemcode
            },
            dataType: "json",
            success: function(response) {
                pElement.disabled = false
                pElement.innerHTML = `<i class="fas fa-sync"></i>`
                if (response.status[0].cd != '0') {
                    if (response.data[0].NOMOR_DAFTAR.length == 6) {
                        document.getElementById('retrm_out_inc_txt_nopen25').value = response.data[0].NOMOR_DAFTAR;
                        document.getElementById('retrm_out_inc_txt_tglpen25').value = response.data[0].TANGGAL_DAFTAR.substr(0, 10);
                        alertify.success("OK");
                    } else {
                        alertify.message('NOMOR PENDAFTARAN is not recevied yet');
                    }
                } else {
                    alertify.message(response.status[0].msg);
                }
            },
            error: function(xhr, xopt, xthrow) {
                pElement.disabled = false
                pElement.innerHTML = `<i class="fas fa-sync"></i>`
                alertify.error(xthrow);
            }
        })
    }

    function retrm_out_donprc_fifo_eCK() {
        let mtbl = document.getElementById('retrm_out_inc_tbl')
        let tableku2 = mtbl.getElementsByTagName("tbody")[0]
        let mtbltr = tableku2.getElementsByTagName('tr')
        let ttlrows = mtbltr.length
        let mtbl_ = document.getElementById('retrm_out_donprc_tbl')
        let tableku2_ = mtbl_.getElementsByTagName("tbody")[0]
        let mtbltr_ = tableku2_.getElementsByTagName('tr')
        let ttlrows_ = mtbltr_.length
        if (ttlrows_ > 0) {
            if (confirm("This action will reset current FIFO, are you sure ?")) {
                const doc = document.getElementById('retrm_out_inc_txt_DO').value
                if (doc.length > 0) {
                    $.ajax({
                        type: "POST",
                        url: "<?=base_url('DELV/remove_delv_rm_per_txid')?>",
                        data: {
                            txid: doc
                        },
                        dataType: "json",
                        success: function(response) {
                            alertify.message('OK, please do FIFO again')
                            tableku2_.innerHTML = ''
                        },
                        error: function(xhr, xopt, xthrow) {
                            alertify.error(xthrow)
                        }
                    })
                } else {
                    alertify.message('OK, please do FIFO again !')
                    tableku2_.innerHTML = ''
                }
            }
        } else {
            const tblfifo = document.getElementById('retrm_out_donprc_fifo')
            if (ttlrows) {
                let iteml = []
                let qtyl = []
                for (let i = 0; i < ttlrows; i++) {
                    const item = tableku2.rows[i].cells[1].innerText
                    const qty = numeral(tableku2.rows[i].cells[2].innerText).value()
                    iteml.push(item)
                    qtyl.push(qty)
                }
                tblfifo.innerHTML = 'Please wait'
                tblfifo.disabled = true
                $.ajax({
                    type: "POST",
                    url: "<?=base_url('RCV/simulatefifo')?>",
                    data: {
                        iteml: iteml,
                        qtyl: qtyl
                    },
                    dataType: "JSON",
                    success: function(response) {
                        tblfifo.innerHTML = 'FIFO'
                        tblfifo.disabled = false
                        const ttlrows = response.data.length
                        let mydes = document.getElementById("retrm_out_donprc_div");
                        let myfrag = document.createDocumentFragment();
                        let mtabel = document.getElementById("retrm_out_donprc_tbl");
                        let cln = mtabel.cloneNode(true);
                        myfrag.appendChild(cln);
                        let tabell = myfrag.getElementById("retrm_out_donprc_tbl");
                        let tableku2 = tabell.getElementsByTagName("tbody")[0];
                        let newrow, newcell, newText;
                        tableku2.innerHTML = ''
                        for (let i = 0; i < ttlrows; i++) {
                            newrow = tableku2.insertRow(-1)
                            newcell = newrow.insertCell(0)
                            newrow.onclick = (event) => {
                                retrm_out_doprc_tbody_tr_eC(event)
                            }
                            newcell.classList.add('d-none')
                            newcell = newrow.insertCell(1)
                            newcell.classList.add('d-none')
                            newcell.innerHTML = response.data[i].NOAJU
                            newcell = newrow.insertCell(2)
                            newcell.innerHTML = response.data[i].NOPEN
                            newcell = newrow.insertCell(3)
                            newcell.innerHTML = response.data[i].DO
                            newcell = newrow.insertCell(4)
                            newcell.innerHTML = response.data[i].TGLPEN
                            newcell = newrow.insertCell(5)
                            newcell.innerHTML = response.data[i].ITMNUM
                            newcell = newrow.insertCell(6)
                            newcell.classList.add('text-end')
                            newcell.innerHTML = response.data[i].QTY
                            newcell = newrow.insertCell(7)
                            newcell.classList.add('text-end')
                            newcell.innerHTML = response.data[i].PRICE
                            newcell = newrow.insertCell(8)
                            newcell.classList.add('text-end')
                            newcell.innerHTML = response.data[i].BCTYPE
                            newcell = newrow.insertCell(9)
                            newcell.onclick = function(event) {
                                if (event.srcElement.tagName === 'SPAN') {
                                    retrm_out_inc_selected_row = event.srcElement.parentElement.parentElement.rowIndex
                                } else {
                                    retrm_out_inc_selected_row = event.srcElement.parentElement.rowIndex
                                }
                                retrm_out_doprc_remrow()
                            }
                            newcell.style.cssText = 'cursor:pointer'
                            newcell.classList.add('text-center')
                            newcell.innerHTML = `<span class="fas fa-trash text-danger"></span>`
                            newcell = newrow.insertCell(10)
                        }
                        mydes.innerHTML = ''
                        mydes.appendChild(myfrag)
                        const ttlrowsNE = response.needEXBC.length
                        if (ttlrowsNE > 0) {
                            const containeur = document.getElementById('retrm_out_div_infoAfterPost')
                            const cardTableDiv = document.createElement('div')
                            cardTableDiv.classList.add('table-responsive')
                            const cardTable = document.createElement('table')
                            cardTable.classList.add('table', 'table-sm', 'table-hover', 'table-bordered')
                            const cardTableHead = document.createElement('thead')
                            const cardTableBody = document.createElement('tbody')
                            cardTableHead.classList.add('table-light')
                            let newrow, newcell
                            newrow = cardTableHead.insertRow(-1)
                            newcell = newrow.insertCell(0)
                            newcell.innerHTML = 'Part Code'
                            newcell = newrow.insertCell(1)
                            newcell.innerHTML = 'Qty'
                            newcell.classList.add('text-end')
                            for (let i = 0; i < ttlrowsNE; i++) {
                                newrow = cardTableBody.insertRow(-1)
                                newcell = newrow.insertCell(0)
                                newcell.classList.add('bg-white')
                                newcell.innerHTML = response.needEXBC[i].ITMCD
                                newcell = newrow.insertCell(1)
                                newcell.classList.add('text-end', 'bg-white')
                                newcell.innerHTML = response.needEXBC[i].QTY
                            }
                            cardTable.appendChild(cardTableHead)
                            cardTable.appendChild(cardTableBody)
                            cardTableDiv.appendChild(cardTable)
                            containeur.innerHTML = `<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <strong><i class="fas fa-info"></i> Need More exbc</strong>
                                <br><br>
                                ${cardTableDiv.innerHTML}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>`
                        }
                    },
                    error: function(xhr, xopt, xthrow) {
                        alertify.error(xthrow)
                        tblfifo.innerHTML = 'FIFO'
                        tblfifo.disabled = false
                    }
                })
            } else {
                alertify.warning('Please enter item first')
            }
        }
    }

    function retrm_out_inc_btn_add_fromMega_eCK() {
        const reffdoc = document.getElementById('retrm_out_parentdoc')
        if (reffdoc.value.trim().length == 0) {
            alertify.warning('Reff. Doc is required')
            reffdoc.focus()
            return
        }
        const btnFRparentsys = document.getElementById('retrm_out_inc_btn_add_fromMega')
        btnFRparentsys.innerHTML = 'Please wait...'
        btnFRparentsys.disabled = true
        $.ajax({
            type: "GET",
            url: "<?=base_url('DELV/xrpr')?>",
            data: {
                doc: reffdoc.value
            },
            dataType: "JSON",
            success: function(response) {
                btnFRparentsys.innerHTML = 'Add from MEGA'
                btnFRparentsys.disabled = false
                const ttlrows = response.data.length
                let mydes = document.getElementById("retrm_out_inc_divku")
                let myfrag = document.createDocumentFragment()
                let mtabel = document.getElementById("retrm_out_inc_tbl")
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln)
                let tabell = myfrag.getElementById("retrm_out_inc_tbl")
                let tableku2 = tabell.getElementsByTagName("tbody")[0]
                let newrow, newcell, newText
                let myitmttl = 0;
                tableku2.innerHTML = ''
                for (let i = 0; i < ttlrows; i++) {
                    newrow = tableku2.insertRow(-1)
                    newcell = newrow.insertCell(0)
                    newcell.classList.add('d-none')
                    newcell.innerHTML = ''
                    newcell = newrow.insertCell(1)
                    newcell.innerHTML = response.data[i].ITRN_ITMCD
                    newcell = newrow.insertCell(2)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = numeral(response.data[i].ITRN_TRNQT).format(',')
                    newcell = newrow.insertCell(3)
                    newcell = newrow.insertCell(4)
                    newcell.innerHTML = response.data[i].MITM_ITMD1
                    newcell = newrow.insertCell(5)
                    newcell.innerHTML = response.data[i].MITM_SPTNO
                }
                mydes.innerHTML = ''
                mydes.appendChild(myfrag)
                if (ttlrows == 0) {
                    alertify.message('NOT FOUND')
                } else {
                    document.getElementById('retrm_out_cmb_locfrom').value = response.data[0].ITRN_REFNO1
                }
            },
            error(xhr, xopt, xthrow) {
                alertify.error(xthrow)
                btnFRparentsys.innerHTML = 'Add from MEGA'
                btnFRparentsys.disabled = false
            }
        })
    }

    function retrm_out_inc_btncopyFromResume_eCK() {
        let mtbl = document.getElementById('retrm_out_donprc_tbl')
        let tableku2 = mtbl.getElementsByTagName("tbody")[0]
        let mtbltr = tableku2.getElementsByTagName('tr')
        let ttlrows = mtbltr.length
        let aitem = []
        let aqty = []
        let tabeldestination = document.getElementById("retrm_out_inc_tbl_packing")
        let tabeldestination_tbody = tabeldestination.getElementsByTagName("tbody")[0]
        let newrow, newcell, newText
        for (let i = 0; i < ttlrows; i++) {
            let titem = tableku2.rows[i].cells[5].innerText
            let tqty = tableku2.rows[i].cells[6].innerText
            newrow = tabeldestination_tbody.insertRow(-1)
            newcell = newrow.insertCell(0)
            newcell.classList.add('d-none')
            newcell = newrow.insertCell(1)
            newcell.classList.add('d-none')
            newcell.innerText = '0'
            newcell = newrow.insertCell(2)
            newcell.classList.add('d-none')
            newcell.innerText = '0'
            newcell = newrow.insertCell(3)
            newcell.classList.add('d-none')
            newcell.innerText = '0'
            newcell = newrow.insertCell(4)
            newcell.innerHTML = titem
            newcell = newrow.insertCell(5)
            newcell.classList.add('text-end')
            newcell.contentEditable = true
            newcell.innerHTML = tqty
            newcell = newrow.insertCell(6)
            newcell.contentEditable = true
            newcell.innerHTML = ''
            newcell = newrow.insertCell(7)
            newcell.contentEditable = true
            newcell.innerHTML = ''
            newcell = newrow.insertCell(8)
            newcell.contentEditable = true
            newcell.innerHTML = ''
            newcell = newrow.insertCell(9)
            newcell.contentEditable = true
            newcell.innerHTML = tableku2.rows[i].cells[10].innerText
        }
    }

    function retrm_out_txt_doprc_search_eKP(e) {
        if (e.key === 'Enter') {
            const itemcd_txt = document.getElementById('retrm_out_doprc_selitem').value
            const itemcd_a = itemcd_txt.split('_')
            const itemcd = itemcd_a[0]
            const searchby = document.getElementById('retrm_out_doprc_searchby').value
            const searchval = document.getElementById('retrm_out_txt_doprc_search')
            searchval.readOnly = true
            $.ajax({
                type: "POST",
                url: "<?=base_url('RCV/searchexbc_balance')?>",
                data: {
                    itemcd: itemcd,
                    searchby: searchby,
                    searchval: searchval.value
                },
                dataType: "json",
                success: function(response) {
                    searchval.readOnly = false
                    const ttlrows = response.data.length
                    let mydes = document.getElementById("retrm_out_doprc_div")
                    let myfrag = document.createDocumentFragment()
                    let mtabel = document.getElementById("retrm_out_doprc_tbl")
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln)
                    let tabell = myfrag.getElementById("retrm_out_doprc_tbl")
                    let tableku2 = tabell.getElementsByTagName("tbody")[0]
                    let newrow, newcell, newText
                    let myitmttl = 0;
                    tableku2.innerHTML = ''

                    let mtbl_p = document.getElementById('retrm_out_donprc_tbl')
                    let tableku2_p = mtbl_p.getElementsByTagName("tbody")[0]
                    let mtbltr_p = tableku2_p.getElementsByTagName('tr')
                    let ttlrows_p = mtbltr_p.length
                    for (let i = 0; i < ttlrows; i++) {
                        let shouldContinue = true;
                        for (let p = 0; p < ttlrows_p; p++) {
                            if (tableku2_p.rows[p].cells[1].innerText == response.data[i].RPSTOCK_NOAJU &&
                                tableku2_p.rows[p].cells[2].innerText == response.data[i].RPSTOCK_BCNUM &&
                                tableku2_p.rows[p].cells[3].innerText == response.data[i].RPSTOCK_DOC &&
                                tableku2_p.rows[p].cells[5].innerText == response.data[i].ITMNUM
                            ) {
                                shouldContinue = false
                            }
                        }
                        if (shouldContinue) {
                            const stk = numeral(response.data[i].STK).value()
                            newrow = tableku2.insertRow(-1)
                            newcell = newrow.insertCell(0)
                            newcell.classList.add('d-none')
                            newcell.innerHTML = response.data[i].RPSTOCK_NOAJU
                            newcell = newrow.insertCell(1)
                            newcell.innerHTML = response.data[i].RPSTOCK_BCNUM
                            newcell = newrow.insertCell(2)
                            newcell.style.cssText = 'cursor:pointer'
                            newcell.onclick = function() {
                                if (itemcd_a[1] > 0) {
                                    $("#retrm_out_MODDOPRC").modal('hide')
                                    retrm_out_doprc_addrow({
                                        NOAJU: response.data[i].RPSTOCK_NOAJU,
                                        NOPEN: response.data[i].RPSTOCK_BCNUM,
                                        DO: response.data[i].RPSTOCK_DOC,
                                        TGLPEN: response.data[i].RCV_BCDATE,
                                        ITEMCD: response.data[i].ITMNUM,
                                        QTY: itemcd_a[1] > stk ? stk : itemcd_a[1],
                                        PRICE: response.data[i].PRICE,
                                        BCTYPE: response.data[i].RCV_BCTYPE
                                    })
                                } else {
                                    alertify.message('it is enough')
                                }
                            }
                            newcell.innerHTML = response.data[i].RPSTOCK_DOC
                            newcell = newrow.insertCell(3)
                            newcell.innerHTML = response.data[i].RCV_BCDATE
                            newcell = newrow.insertCell(4)
                            newcell.innerHTML = response.data[i].ITMNUM
                            newcell = newrow.insertCell(5)
                            newcell.innerHTML = response.data[i].MITM_ITMD1
                            newcell = newrow.insertCell(6)
                            newcell.classList.add('text-end')
                            newcell.innerHTML = response.data[i].PRICE
                            newcell = newrow.insertCell(7)
                            newcell.classList.add('text-end')
                            newcell.innerHTML = numeral(response.data[i].STK).format(',')
                            newcell = newrow.insertCell(8)
                            newcell.innerHTML = response.data[i].RCV_BCTYPE
                            newcell = newrow.insertCell(9)
                            newcell.innerHTML = response.data[i].MSUP_SUPNM
                        }
                    }
                    mydes.innerHTML = ''
                    mydes.appendChild(myfrag)
                },
                error: function(xhr, xopt, xthrow) {
                    alertify.error(xthrow)
                    searchval.readOnly = false
                }
            })
        }
    }

    function retrm_out_btn_sorm_eCK() {
        const bisgrup = document.getElementById('retrm_out_inc_cmb_bg').value
        const itemcd_txt = document.getElementById('retrm_out_soprc_selitem').value
        const itemcd_a = itemcd_txt.split('_')
        const itemcd = itemcd_a[0]
        const btnsearch = document.getElementById('retrm_out_btn_sorm')
        if (bisgrup === '-') {
            alertify.warning('business group is required')
            return
        }
        btnsearch.innerHTML = 'Please wait'
        btnsearch.disabled = true
        $.ajax({
            type: "POST",
            url: "<?=base_url('SO/outstanding_mega')?>",
            data: {
                itemcd: itemcd,
                bg: bisgrup,
                cuscd: retrm_out_inc_custcd
            },
            dataType: "JSON",
            success: function(response) {
                btnsearch.disabled = false
                btnsearch.innerHTML = 'Search'
                const ttlrows = response.data.length
                let mydes = document.getElementById("retrm_out_soprc_div");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("retrm_out_soprc_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("retrm_out_soprc_tbl");
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                let newrow, newcell, newText
                tableku2.innerHTML = ''
                for (let i = 0; i < ttlrows; i++) {
                    newrow = tableku2.insertRow(-1)
                    newcell = newrow.insertCell(0)
                    newcell.onclick = function() {
                        $('#retrm_out_MODSOPRC').modal('hide')
                        retrm_out_soprc_addrow({
                            CPO: response.data[i].SSO2_CPONO,
                            CPOLINE: response.data[i].SSO2_SOLNO,
                            ITEMCD: response.data[i].SSO2_MDLCD,
                            QTY: itemcd_a[1],
                            PRICE: response.data[i].SSO2_SLPRC
                        })
                    }
                    newcell.innerHTML = response.data[i].SSO2_CPONO
                    newcell = newrow.insertCell(1)
                    newcell.innerHTML = response.data[i].SSO2_SOLNO
                    newcell = newrow.insertCell(2)
                    newcell.innerHTML = response.data[i].SSO2_MDLCD
                    newcell = newrow.insertCell(3)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = response.data[i].SSO2_SLPRC * 1
                }
                mydes.innerHTML = ''
                mydes.appendChild(myfrag)
            },
            error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow)
                btnsearch.disabled = false
            }
        })
    }

    $("#retrm_out_MODSOPRC").on('hidden.bs.modal', function() {
        $("#retrm_out_soprc_tbl tbody").empty();
    });
    $("#retrm_out_MODDOPRC").on('hidden.bs.modal', function() {
        $("#retrm_out_doprc_tbl tbody").empty();
    });

    function retrm_out_doprc_tbody_tr_eC(e) {
        // retrm_out_inc_selected_row = e.srcElement.parentElement.rowIndex - 1
        // console.log(retrm_out_inc_selected_row)
    }

    function retrm_out_soprc_addrow(pdata) {
        let mytbody = document.getElementById('retrm_out_so_tbl').getElementsByTagName('tbody')[0]
        const dlength = mytbody.getElementsByTagName('tr').length
        let newrow, newcell

        for (let i = 0; i < dlength; i++) {
            if (mytbody.rows[i].cells[1].innerText === pdata.CPO &&
                mytbody.rows[i].cells[2].innerText === pdata.CPOLINE &&
                mytbody.rows[i].cells[3].innerText === pdata.ITEMCD
            ) {
                alertify.warning('It is already added')
                return
            }
        }
        newrow = mytbody.insertRow(-1)
        newcell = newrow.insertCell(0)
        newcell.classList.add('d-none')

        newcell = newrow.insertCell(1)
        newcell.innerHTML = pdata.CPO

        newcell = newrow.insertCell(2)
        newcell.innerHTML = pdata.CPOLINE

        newcell = newrow.insertCell(3)
        newcell.innerHTML = pdata.ITEMCD

        newcell = newrow.insertCell(4)
        newcell.classList.add('text-end')
        newcell.contentEditable = true
        newcell.innerHTML = pdata.QTY

        newcell = newrow.insertCell(5)
        newcell.classList.add('text-end')
        newcell.innerHTML = pdata.PRICE.substr(0, 1) == '.' ? '0' + pdata.PRICE : pdata.PRICE

        newcell = newrow.insertCell(6)
        newcell.onclick = function(event) {
            if (event.srcElement.tagName === 'SPAN') {
                retrm_out_inc_selected_row = event.srcElement.parentElement.parentElement.rowIndex
            } else {
                retrm_out_inc_selected_row = event.srcElement.parentElement.rowIndex
            }
            retrm_out_soprc_remrow()
        }
        newcell.style.cssText = 'cursor:pointer'
        newcell.classList.add('text-center')
        newcell.innerHTML = `<span class="fas fa-trash text-danger"></span>`
    }

    function retrm_out_soprc_remrow() {
        if (retrm_out_inc_selected_row > 0) {
            const txid = document.getElementById('retrm_out_inc_txt_DO').value
            let mytable = document.getElementById('retrm_out_so_tbl').getElementsByTagName('tbody')[0]
            const mtr = mytable.getElementsByTagName('tr')[retrm_out_inc_selected_row - 1]
            const mylineid = mtr.getElementsByTagName('td')[0].innerText.trim()
            if (mylineid !== '') {
                if (confirm("Are you sure ?")) {
                    $.ajax({
                        type: "post",
                        url: "<?=base_url('DELV/remove_delv_rmso')?>",
                        data: {
                            txid: txid,
                            line: mylineid
                        },
                        dataType: "JSON",
                        success: function(response) {
                            if (response.status[0].cd === 1) {
                                alertify.success(response.status[0].msg)
                                mtr.remove()
                            } else {
                                alertify.message(response.status[0].msg)
                            }
                        }
                    })
                }
            } else {
                mtr.remove()
            }
        } else {
            alertify.message('nothing will be deleted')
        }
    }

    function retrm_out_soeximscr_remrow(rindex) {
        if (rindex > 0) {
            const txid = document.getElementById('retrm_out_inc_txt_DO').value
            const mytable = document.getElementById('retrm_out_limbah_barang_tbl').getElementsByTagName('tbody')[0]
            const mtr = mytable.getElementsByTagName('tr')[rindex - 1]
            const mylineid = mtr.getElementsByTagName('td')[0].innerText.trim()
            if (mylineid !== '') {
                if (confirm("Are you sure ?")) {
                    $.ajax({
                        type: "post",
                        url: "<?=base_url('DELV/remove_delv_scr')?>",
                        data: {
                            txid: txid,
                            line: mylineid
                        },
                        dataType: "JSON",
                        success: function(response) {
                            if (response.status[0].cd === 1) {
                                alertify.success(response.status[0].msg)
                                mtr.remove()
                            } else {
                                alertify.message(response.status[0].msg)
                            }
                        }
                    })
                }
            } else {
                mtr.remove()
            }
        } else {
            alertify.message('nothing will be deleted')
        }
    }

    function retrm_out_doprc_selitem_eCH() {
        document.getElementById('retrm_out_txt_doprc_search').focus();
        document.getElementById('retrm_out_doprc_tbl').getElementsByTagName('tbody')[0].innerHTML = ''
    }

    function retrm_out_doprc_addrow(pdata) {
        let mytbody = document.getElementById('retrm_out_donprc_tbl').getElementsByTagName('tbody')[0]
        const dlength = mytbody.getElementsByTagName('tr').length
        let newrow, newcell

        for (let i = 0; i < dlength; i++) {
            if (mytbody.rows[i].cells[1].innerText === pdata.NOAJU &&
                mytbody.rows[i].cells[2].innerText === pdata.NOPEN &&
                mytbody.rows[i].cells[5].innerText === pdata.ITEMCD &&
                mytbody.rows[i].cells[3].innerText === pdata.DO
            ) {
                alertify.warning('It is already added')
                return
            }
        }
        newrow = mytbody.insertRow(-1)
        newrow.onclick = (event) => {
            retrm_out_doprc_tbody_tr_eC(event)
        }
        newcell = newrow.insertCell(0)
        newcell.classList.add('d-none')

        newcell = newrow.insertCell(1)
        newcell.classList.add('d-none')
        newcell.innerHTML = pdata.NOAJU

        newcell = newrow.insertCell(2)
        newcell.innerHTML = pdata.NOPEN

        newcell = newrow.insertCell(3)
        newcell.innerHTML = pdata.DO

        newcell = newrow.insertCell(4)
        newcell.innerHTML = pdata.TGLPEN

        newcell = newrow.insertCell(5)
        newcell.innerHTML = pdata.ITEMCD

        newcell = newrow.insertCell(6)
        newcell.contentEditable = true
        newcell.classList.add('text-end')
        newcell.innerHTML = pdata.QTY

        newcell = newrow.insertCell(7)
        newcell.classList.add('text-end')
        newcell.innerHTML = pdata.PRICE

        newcell = newrow.insertCell(8)
        newcell.classList.add('text-end')
        newcell.innerHTML = pdata.BCTYPE

        newcell = newrow.insertCell(9)
        newcell.onclick = function(event) {
            if (event.srcElement.tagName === 'SPAN') {
                retrm_out_inc_selected_row = event.srcElement.parentElement.parentElement.rowIndex
            } else {
                retrm_out_inc_selected_row = event.srcElement.parentElement.rowIndex
            }
            retrm_out_doprc_remrow()
        }
        newcell.style.cssText = 'cursor:pointer'
        newcell.classList.add('text-center')
        newcell.innerHTML = `<span class="fas fa-trash text-danger"></span>`
        newcell = newrow.insertCell(10)
    }

    function retrm_out_scr_addrow(pdata) {
        let mytbody = document.getElementById('retrm_out_scr_tbl').getElementsByTagName('tbody')[0]
        const dlength = mytbody.getElementsByTagName('tr').length
        let newrow, newcell

        for (let i = 0; i < dlength; i++) {
            if (mytbody.rows[i].cells[1].innerText === pdata.ID_TRANS) {
                alertify.warning('It is already added')
                return
            }
        }
        newrow = mytbody.insertRow(-1)
        newcell = newrow.insertCell(0)
        newcell.classList.add('d-none')

        newcell = newrow.insertCell(1)
        newcell.innerHTML = pdata.ID_TRANS

        newcell = newrow.insertCell(2)
        newcell.onclick = function(event) {
            if (event.srcElement.tagName === 'SPAN') {
                retrm_out_inc_selected_row = event.srcElement.parentElement.parentElement.rowIndex
            } else {
                retrm_out_inc_selected_row = event.srcElement.parentElement.rowIndex
            }
            retrm_out_scrdoc_remrow()
        }
        newcell.style.cssText = 'cursor:pointer'
        newcell.classList.add('text-center')
        newcell.innerHTML = `<span class="fas fa-trash text-danger"></span>`
    }

    function retrm_out_doprc_remrow() {
        if (retrm_out_inc_selected_row > 0) {
            const txid = document.getElementById('retrm_out_inc_txt_DO').value
            let mytable = document.getElementById('retrm_out_donprc_tbl').getElementsByTagName('tbody')[0]
            const mtr = mytable.getElementsByTagName('tr')[retrm_out_inc_selected_row - 1]
            const mylineid = mtr.getElementsByTagName('td')[0].innerText.trim()
            if (mylineid !== '') {
                if (confirm("Are you sure ?")) {
                    $.ajax({
                        type: "post",
                        url: "<?=base_url('DELV/remove_delv_rm')?>",
                        data: {
                            txid: txid,
                            line: mylineid
                        },
                        dataType: "JSON",
                        success: function(response) {
                            if (response.status[0].cd === 1) {
                                alertify.success(response.status[0].msg)
                                mtr.remove()
                            } else {
                                alertify.message(response.status[0].msg)
                            }
                        }
                    })
                }
            } else {
                mtr.remove()
            }
        } else {
            alertify.message('nothing will be deleted')
        }
    }

    function retrm_out_scrdoc_remrow() {
        if (retrm_out_inc_selected_row > 0) {
            const txid = document.getElementById('retrm_out_inc_txt_DO').value
            let mytable = document.getElementById('retrm_out_scr_tbl').getElementsByTagName('tbody')[0]
            const mtr = mytable.getElementsByTagName('tr')[retrm_out_inc_selected_row - 1]
            const mylineid = mtr.getElementsByTagName('td')[0].innerText.trim()
            if (mylineid !== '') {
                if (confirm("Are you sure ?")) {
                    $.ajax({
                        type: "post",
                        url: "<?=base_url('DELV/remove_delv_scrdoc')?>",
                        data: {
                            txid: txid,
                            line: mylineid
                        },
                        dataType: "JSON",
                        success: function(response) {
                            if (response.status[0].cd === 1) {
                                alertify.success(response.status[0].msg)
                                mtr.remove()
                            } else {
                                alertify.message(response.status[0].msg)
                            }
                        }
                    })
                }
            } else {
                mtr.remove()
            }
        } else {
            alertify.message('nothing will be deleted')
        }
    }

    function retrm_out_timer() {
        retrm_out_inc_t = setTimeout(retrm_out_add, 1000)
    }

    function retrm_out_btn_showinfo_e_click() {
        $("#retrm_out_inc_MODINFO").modal('show')
    }

    function retrm_out_btn_post_eC() {
        const mapprovby = document.getElementById("retrm_out_inc_txt_apprby").value;
        if (mapprovby.trim() == '') {
            alertify.warning("Please approve first !")
            return
        }
        if (confirm('Are you sure ?')) {
            const mymodal = new bootstrap.Modal(document.getElementById("retrm_out_inc_PROGRESS"), {
                backdrop: 'static',
                keyboard: false
            })
            mymodal.show()
        }
    }

    function retrm_out_so_tbl_add_eCK() {
        let mtbl = document.getElementById('retrm_out_inc_tbl')
        let tableku2 = mtbl.getElementsByTagName("tbody")[0]
        let mtbltr = tableku2.getElementsByTagName('tr')
        let ttlrows = mtbltr.length
        let mtbl_ = document.getElementById('retrm_out_so_tbl')
        let tableku2_ = mtbl_.getElementsByTagName("tbody")[0]
        let mtbltr_ = tableku2_.getElementsByTagName('tr')
        let ttlrows_ = mtbltr_.length
        if (ttlrows) {
            let items_str = '';
            for (let i = 0; i < ttlrows; i++) {
                const qty = numeral(tableku2.rows[i].cells[2].innerText).value()
                const item = tableku2.rows[i].cells[1].innerText
                let qty_ = 0
                for (let i = 0; i < ttlrows_; i++) {
                    const item_ = tableku2_.rows[i].cells[3].innerText
                    const itemqty_ = numeral(tableku2_.rows[i].cells[4].innerText).value()
                    if (item == item_) {
                        qty_ += itemqty_
                    }
                }
                const balance = qty - qty_
                if (balance) {
                    items_str += `<option value='${tableku2.rows[i].cells[1].innerText}_${balance}'>${tableku2.rows[i].cells[1].innerText} (${balance})</option>`
                }
            }
            if (items_str === '') {
                alertify.warning('Please enter item correctly')
                return
            }
            document.getElementById('retrm_out_soprc_selitem').innerHTML = items_str
            $("#retrm_out_MODSOPRC").modal('show')
        } else {
            alertify.warning('Please enter item first')
        }
    }

    function retrm_out_donprc_tbl_add_eCK() {
        let mtbl = document.getElementById('retrm_out_inc_tbl')
        let tableku2 = mtbl.getElementsByTagName("tbody")[0]
        let mtbltr = tableku2.getElementsByTagName('tr')
        let ttlrows = mtbltr.length
        let mtbl_ = document.getElementById('retrm_out_donprc_tbl')
        let tableku2_ = mtbl_.getElementsByTagName("tbody")[0]
        let mtbltr_ = tableku2_.getElementsByTagName('tr')
        let ttlrows_ = mtbltr_.length
        if (ttlrows) {
            let items_str = '';
            for (let i = 0; i < ttlrows; i++) {
                const qty = numeral(tableku2.rows[i].cells[2].innerText).value()
                const item = tableku2.rows[i].cells[1].innerText
                let qty_ = 0;
                for (let i = 0; i < ttlrows_; i++) {
                    const item_ = tableku2_.rows[i].cells[5].innerText
                    const itemqty_ = numeral(tableku2_.rows[i].cells[6].innerText).value()
                    if (item == item_) {
                        qty_ += itemqty_
                    }
                }
                const balance = qty - qty_
                if (balance) {
                    items_str += `<option value='${tableku2.rows[i].cells[1].innerText}_${balance}'>${tableku2.rows[i].cells[1].innerText} (${balance})</option>`
                }
            }
            if (items_str === '') {
                alertify.warning('Please enter item correctly')
                return
            }
            document.getElementById('retrm_out_doprc_selitem').innerHTML = items_str
            $("#retrm_out_MODDOPRC").modal('show')
        } else {
            alertify.warning('Please enter item first')
        }
    }

    function retrm_out_e_posting() {
        const msj = document.getElementById('retrm_out_inc_txt_DO').value
        const doctype = document.getElementById('retrm_out_cmb_bcdoc').value
        retrm_out_timer()
        $.ajax({
            type: "get",
            url: "<?=base_url("DELV/posting_rm")?>" + doctype,
            data: {
                insj: msj
            },
            dataType: "json",
            success: function(response) {
                clearTimeout(retrm_out_inc_t);
                $("#retrm_out_inc_PROGRESS").modal('hide')
                if (response.status[0].cd == '1') {
                    alertify.success(response.status[0].msg);
                    let appr = document.getElementById("footerinfo_user").innerText
                    appr = appr.substr(3, appr.length)
                    document.getElementById("retrm_out_inc_txt_postedby").value = appr
                    document.getElementById("retrm_out_inc_txt_postedtime").value = response.status[0].time
                    document.getElementById('retrm_out_div_infoAfterPost').innerHTML = `<div class="alert alert-info alert-dismissible fade show" role="alert">
                            <strong><i class="fas fa-info"></i></strong> Time consume : ${retrm_out_inc_timerspan.innerText}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`
                } else {
                    alertify.warning(response.status[0].msg)
                    document.getElementById('retrm_out_div_infoAfterPost').innerHTML = `<div class="alert alert-info alert-dismissible fade show" role="alert">
                            ${response.status[0].msg}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`
                }
                retrm_out_inc_timerspan.innerText = "00:00:00"
                retrm_out_inc_seconds = 0;
                retrm_out_inc_minutes = 0;
                retrm_out_inc_hours = 0
            },
            error: function(xhr, xopt, xthrow) {
                clearTimeout(retrm_out_inc_t)
                retrm_out_inc_timerspan.innerText = "00:00:00"
                retrm_out_inc_seconds = 0;
                retrm_out_inc_minutes = 0;
                retrm_out_inc_hours = 0
                $("#retrm_out_inc_PROGRESS").modal('hide')
                alertify.error(xthrow);
            }
        });
    }

    $("#retrm_out_inc_PROGRESS").on('shown.bs.modal', function() {
        retrm_out_e_posting()
    })
    $("#retrm_out_MODSCRDOC").on('shown.bs.modal', function() {
        document.getElementById('retrm_out_scrdoc_txtsearch').focus()
    })

    $("#retrm_out_MODSCR").on('shown.bs.modal', function() {
        document.getElementById('retrm_out_cscr_txtsearch').focus()
    })

    $("#retrm_out_MODDOPRC").on('shown.bs.modal', function() {
        document.getElementById('retrm_out_txt_doprc_search').focus()
    })

    function retrm_out_inc_z_btn_save_e_click() {
        let msj = document.getElementById('retrm_out_inc_txt_DO')
        if (msj.value.trim() == '') {
            msj.focus()
            alertify.message('Please fill the TX ID');
            return
        }
        let mbctype = document.getElementById('retrm_out_cmb_bcdoc')
        if (mbctype.value.trim() == '') {
            mbctype.focus()
            return
        }
        let mnoaju = document.getElementById('retrm_out_inc_txt_noaju')
        let mnopen = document.getElementById('retrm_out_inc_txt_nopen').value;
        let mtglpen = document.getElementById('retrm_out_inc_txt_tglpen').value;
        let mjenis_tpb_asal = document.getElementById('retrm_out_inc_cmb_jenisTPB').value;
        let mjenis_tpb_tujuan = document.getElementById('retrm_out_inc_cmb_jenisTPBtujuan').value;
        let mfromoffice = document.getElementById('retrm_out_inc_fromoffice').value;
        let mdestoffice = document.getElementById('retrm_out_inc_destoffice').value;
        let mpurpose = document.getElementById('retrm_out_inc_cmb_tujuanpengiriman').value;
        const mcona = document.getElementById('retrm_out_inc_txt_nokontrak').value.trim()
        const namaPemberitahu = document.getElementsByClassName('retrm_nama_pemberitahu')[0].value
        const jabatanPemberitahu = document.getElementsByClassName('retrm_jabatan_pemberitahu')[0].value
        if (mnoaju.value.includes(".")) {
            document.getElementById('retrm_out_inc_txt_noaju').focus()
            alertify.warning(`Nomor pengajuan is not valid`)
            return
        }
        if (mnoaju.value.trim().length < 6) {
            alertify.warning("NO AJU is not valid")
            mnoaju.focus()
            return;
        }
        if (confirm("Are You sure ?")) {
            $.ajax({
                type: "get",
                url: "<?=base_url('DELV/change27')?>",
                data: {
                    inid: msj.value,
                    innopen: mnopen,
                    inaju: mnoaju.value,
                    infromoffice: mfromoffice,
                    indestoffice: mdestoffice,
                    inpurpose: mpurpose,
                    injenis_tpb_asal: mjenis_tpb_asal,
                    injenis_tpb_tujuan: mjenis_tpb_tujuan,
                    intgldaftar: mtglpen,
                    incona: mcona,
                    inPemberitahu : namaPemberitahu,
                    inJabatan : jabatanPemberitahu
                },
                dataType: "json",
                success: function(response) {
                    if (response[0].cd = '11') {
                        alertify.success(response[0].msg);
                    } else {
                        //alertify.error(response[0].msg);
                    }
                },
                error: function(xhr, xopt, xthrow) {
                    alertify.error(xthrow);
                }
            });
        }
    }

    function retrm_out_inc_z_btn_save41_e_click() {
        const msj = document.getElementById('retrm_out_inc_txt_DO')
        if (msj.value.trim() == '') {
            msj.focus()
            return
        }
        const mbctype = document.getElementById('retrm_out_cmb_bcdoc')
        if (mbctype.value.trim() == '') {
            mbctype.focus()
            return
        }
        const mnoaju = document.getElementById('retrm_out_inc_txt_noaju41')
        const mnopen = document.getElementById('retrm_out_inc_txt_nopen41').value;
        const mtglpen = document.getElementById('retrm_out_inc_txt_tglpen41').value;
        const mfromoffice = document.getElementById('retrm_out_inc_fromoffice41').value;
        const mjenis_tpb_asal = document.getElementById('retrm_out_inc_cmb_jenisTPB41').value;
        const mpurpose = document.getElementById('retrm_out_inc_cmb_tujuanpengiriman41').value;
        const mcona = document.getElementById('retrm_out_inc_txt_nokontrak41').value
        let namaPemberitahu = document.getElementsByClassName('retrm_nama_pemberitahu')[0].value
        let jabatanPemberitahu = document.getElementsByClassName('retrm_jabatan_pemberitahu')[0].value
        if (mnoaju.value.trim().length < 6) {
            alertify.warning("NO AJU is not valid")
            mnoaju.focus()
            return
        }
        if (confirm("Are You sure ?")) {
            $.ajax({
                type: "get",
                url: "<?=base_url('DELV/change41')?>",
                data: {
                    inid: msj.value,
                    innopen: mnopen,
                    inaju: mnoaju.value,
                    infromoffice: mfromoffice,
                    inpurpose: mpurpose,
                    injenis_tpb_asal: mjenis_tpb_asal,
                    intgldaftar: mtglpen,
                    incona: mcona,
                    inPemberitahu : namaPemberitahu,
                    inJabatan : jabatanPemberitahu
                },
                dataType: "json",
                success: function(response) {
                    if (response[0].cd = '11') {
                        alertify.success(response[0].msg);
                    } else {
                        //alertify.error(response[0].msg);
                    }
                },
                error: function(xhr, xopt, xthrow) {
                    alertify.error(xthrow);
                }
            })
        }
    }

    function retrm_out_inc_z_btn_save261_e_click() {
        const msj = document.getElementById('retrm_out_inc_txt_DO')
        if (msj.value.trim() == '') {
            msj.focus()
            return
        }
        const mbctype = document.getElementById('retrm_out_cmb_bcdoc')
        if (mbctype.value.trim() == '') {
            mbctype.focus()
            return
        }
        const mnoaju = document.getElementById('retrm_out_inc_txt_noaju261')
        const mnopen = document.getElementById('retrm_out_inc_txt_nopen261').value;
        const mtglpen = document.getElementById('retrm_out_inc_txt_tglpen261').value;
        const mfromoffice = document.getElementById('retrm_out_inc_fromoffice261').value
        const mpurpose = document.getElementById('retrm_out_inc_cmb_tujuanpengiriman261').value
        const mcona = document.getElementById('retrm_out_inc_txt_nokontrak261').value
        const mejenis_sarana_angkut = document.getElementById('retrm_out_inc_jenis_saranapengangkut261').value
        const namaPemberitahu = document.getElementsByClassName('retrm_nama_pemberitahu')[0].value
        const jabatanPemberitahu = document.getElementsByClassName('retrm_jabatan_pemberitahu')[0].value
        if (mnoaju.value.trim().length < 6) {
            alertify.warning("NO AJU is not valid")
            mnoaju.focus()
            return
        }
        if (confirm("Are You sure ?")) {
            $.ajax({
                type: "get",
                url: "<?=base_url('DELV/change261')?>",
                data: {
                    inid: msj.value,
                    innopen: mnopen,
                    inaju: mnoaju.value,
                    infromoffice: mfromoffice,
                    inpurpose: mpurpose,
                    intgldaftar: mtglpen,
                    incona: mcona,
                    injenis_sarana: mejenis_sarana_angkut,
                    inPemberitahu : namaPemberitahu,
                    inJabatan : jabatanPemberitahu
                },
                dataType: "json",
                success: function(response) {
                    if (response[0].cd = '11') {
                        alertify.success(response[0].msg);
                    } else {
                        //alertify.error(response[0].msg);
                    }
                },
                error: function(xhr, xopt, xthrow) {
                    alertify.error(xthrow);
                }
            });
        }
    }

    function retrm_out_inc_z_btn_save30_e_click() {
        const msj = document.getElementById('retrm_out_inc_txt_DO')
        if (msj.value.trim() == '') {
            msj.focus()
            return
        }
        const mbctype = document.getElementById('retrm_out_cmb_bcdoc')
        if (mbctype.value.trim() == '') {
            mbctype.focus()
            return
        }
        const mnoaju = document.getElementById('retrm_out_inc_txt_noaju30')
        const mnopen = document.getElementById('retrm_out_inc_txt_nopen30').value;
        const mtglpen = document.getElementById('retrm_out_inc_txt_tglpen30').value;
        const namaPemberitahu = document.getElementsByClassName('retrm_nama_pemberitahu')[0].value
        const jabatanPemberitahu = document.getElementsByClassName('retrm_jabatan_pemberitahu')[0].value
        if (mnoaju.value.trim().length != 26) {
            alertify.warning("NO AJU is not valid")
            mnoaju.focus()
            return
        }
        if (confirm("Are You sure ?")) {
            $.ajax({
                type: "get",
                url: "<?=base_url('DELV/change30')?>",
                data: {
                    inid: msj.value,
                    innopen: mnopen,
                    inaju: mnoaju.value,
                    intgldaftar: mtglpen,
                    inPemberitahu : namaPemberitahu,
                    inJabatan : jabatanPemberitahu
                },
                dataType: "json",
                success: function(response) {
                    if (response[0].cd = '11') {
                        alertify.success(response[0].msg);
                    } else {
                        //alertify.error(response[0].msg);
                    }
                },
                error: function(xhr, xopt, xthrow) {
                    alertify.error(xthrow);
                }
            });
        }
    }

    function retrm_out_inc_z_btn_save25_e_click() {
        const msj = document.getElementById('retrm_out_inc_txt_DO')
        if (msj.value.trim() === '') {
            msj.focus()
            alertify.message('Please fill the TX ID')
            return
        }
        const mbctype = document.getElementById('retrm_out_cmb_bcdoc').value
        if (mbctype.trim() === '') {
            document.getElementById('retrm_out_cmb_bcdoc').focus()
            return
        }
        const mnoaju = document.getElementById('retrm_out_inc_txt_noaju25')
        const mjenis_tpb_asal = document.getElementById('retrm_out_inc_cmb_jenisTPB25').value
        const mnopen = document.getElementById('retrm_out_inc_txt_nopen25').value
        const mtglpen = document.getElementById('retrm_out_inc_txt_tglpen25').value
        const mfromoffice = document.getElementById('retrm_out_inc_fromoffice25').value
        const mSKB_pph = document.getElementById('retrm_out_inc_txt_noskb').value
        const mSKB_pph_tgl = document.getElementById('retrm_out_inc_txt_noskb_tgl').value
        const mjenis_sarana_pengangkut = document.getElementById('retrm_out_inc_jenis_saranapengangkut25').value
        const namaPemberitahu = document.getElementsByClassName('retrm_nama_pemberitahu')[0].value
        const jabatanPemberitahu = document.getElementsByClassName('retrm_jabatan_pemberitahu')[0].value
        if (mnoaju.value.trim().length < 6) {
            alertify.warning("NO AJU is not valid")
            mnoaju.focus()
            return
        }
        if (confirm("Are You sure ?")) {
            $.ajax({
                type: "get",
                url: "<?=base_url('DELV/change25')?>",
                data: {
                    inid: msj.value,
                    innopen: mnopen,
                    inaju: mnoaju.value,
                    infromoffice: mfromoffice,
                    injenis_tpb_asal: mjenis_tpb_asal,
                    inskb: mSKB_pph,
                    inskb_tgl: mSKB_pph_tgl,
                    injenis_sarana: mjenis_sarana_pengangkut,
                    innopen: mnopen,
                    intglpen: mtglpen,
                    inPemberitahu : namaPemberitahu,
                    inJabatan : jabatanPemberitahu
                },
                dataType: "json",
                success: function(response) {
                    if (response[0].cd = '11') {
                        alertify.success(response[0].msg)
                    } else {
                        //alertify.error(response[0].msg);
                    }
                },
                error: function(xhr, xopt, xthrow) {
                    alertify.error(xthrow)
                }
            });
        }
    }

    function retrm_out_btn_new_eC() {
        const txtDONum = document.getElementById('retrm_out_inc_txt_DO')
        txtDONum.value = ''
        txtDONum.focus()
        $("#retrm_out_inc_customs_date").datepicker('update', new Date())
        $("#retrm_out_txt_DOdate").datepicker('update', new Date())
        document.getElementById('retrm_out_txt_invno').value = ''
        document.getElementById('retrm_out_txt_invsmt').value = ''
        const cmbbg = document.getElementById('retrm_out_inc_cmb_bg')
        cmbbg.value = '-'
        cmbbg.disabled = false
        retrm_out_btn_save.disabled = false
        retrm_out_inc_btnfindmodcust.disabled = false
        retrm_out_btn_appr.disabled = false
        retrm_out_btn_tpb.disabled = false
        retrm_out_inc_z_btn_save.disabled = false
        retrm_out_inc_z_btn_save41.disabled = false
        retrm_out_inc_z_btn_save261.disabled = false
        retrm_out_inc_z_btn_save30.disabled = false
        retrm_out_inc_z_btn_save25.disabled = false
        retrm_out_inc_btn_shortcut.disabled = false
        retrm_out_inc_btncopyFromResume.disabled = false
        retrm_out_inc_btn_add_packing.disabled = false
        retrm_out_inc_btn_min_packing.disabled = false
        retrm_out_donprc_fifo.disabled = false
        retrm_out_donprc_tbl_add.disabled = false
        retrm_out_so_tbl_add.disabled = false
        retrm_out_inc_btn_add.disabled = false
        retrm_out_inc_btn_minus.disabled = false

        document.getElementById('retrm_out_inc_custname').value = ''
        document.getElementById('retrm_out_inc_curr').value = ''
        document.getElementById('retrm_out_inc_consignee').value = '-'
        document.getElementById('retrm_out_inc_txt_transport').value = '-'
        document.getElementById('retrm_out_cmb_bcdoc').value = '-'
        document.getElementById('retrm_out_rprdoc').value = ''
        document.getElementById('retrm_out_inc_tbl').getElementsByTagName('tbody')[0].innerHTML = ''
        document.getElementById('retrm_out_inc_tbl_packing').getElementsByTagName('tbody')[0].innerHTML = ''
        document.getElementById('retrm_out_donprc_tbl').getElementsByTagName('tbody')[0].innerHTML = ''
        document.getElementById('retrm_out_so_tbl').getElementsByTagName('tbody')[0].innerHTML = ''
        retrm_out_inc_custcd = '-'

        document.getElementById('retrm_out_inc_txt_noaju').value = ''
        document.getElementById('retrm_out_inc_txt_nopen').value = ''
        document.getElementById('retrm_out_inc_txt_tglpen').value = ''

        document.getElementById('retrm_out_inc_txt_noaju25').value = ''
        document.getElementById('retrm_out_inc_txt_nopen25').value = ''
        document.getElementById('retrm_out_inc_txt_tglpen25').value = ''

        document.getElementById('retrm_out_inc_txt_noaju41').value = ''
        document.getElementById('retrm_out_inc_txt_nopen41').value = ''
        document.getElementById('retrm_out_inc_txt_tglpen41').value = ''

        document.getElementById('retrm_out_cpk_tblpk').getElementsByTagName('tbody')[0].innerHTML = ''
        document.getElementById('retrm_out_scr_tbl').getElementsByTagName('tbody')[0].innerHTML = ''
    }

    function retrm_out_txtxtsearch_eKP(e) {
        if (e.key === 'Enter') {
            const mkeys = document.getElementById('retrm_out_txtxtsearch').value.trim()
            let ms_by = document.getElementById('retrm_out_txsrchby').value
            $("#retrm_out_txtbl tbody").empty();
            document.getElementById("retrm_out_txtbl_lblinfo").innerText = "Please wait...";
            let searchperiod = document.getElementById('retrm_out_ckperiod').checked ? 1 : 0;
            let monthperiod = document.getElementById('retrm_out_monthfilter').value;
            let yearperiod = document.getElementById('retrm_out_year').value;
            $.ajax({
                type: "get",
                url: "<?=base_url('DELV/search')?>",
                data: {
                    inkey: mkeys,
                    insearchby: ms_by,
                    insearchperiod: searchperiod,
                    inmonth: monthperiod,
                    inyear: yearperiod,
                    insearchtype: '0'
                },
                dataType: "json",
                success: function(response) {
                    let ttlrows = response.data.length;
                    document.getElementById("retrm_out_txtbl_lblinfo").innerText = "";
                    if (ttlrows > 0) {
                        let mydes = document.getElementById("retrm_out_txdivku");
                        let myfrag = document.createDocumentFragment();
                        let mtabel = document.getElementById("retrm_out_txtbl");
                        let cln = mtabel.cloneNode(true);
                        myfrag.appendChild(cln);
                        let tabell = myfrag.getElementById("retrm_out_txtbl");
                        let tableku2 = tabell.getElementsByTagName("tbody")[0];
                        let newrow, newcell, newText;
                        tableku2.innerHTML = ''
                        for (let i = 0; i < ttlrows; i++) {
                            newrow = tableku2.insertRow(-1)
                            newrow.title = `BC ${response.data[i].DLV_BCTYPE}`
                            newcell = newrow.insertCell(0)
                            newcell.innerHTML = response.data[i].DLV_ID

                            newcell = newrow.insertCell(1)
                            newcell.classList.add('d-none')
                            newcell.innerHTML = response.data[i].DLV_DATE

                            newcell = newrow.insertCell(2)
                            newcell.innerHTML = response.data[i].DLV_DSCRPTN

                            newcell = newrow.insertCell(3);
                            newcell.style.cssText = 'display:none';
                            newcell.innerHTML = response.data[i].DLV_CUSTCD

                            newcell = newrow.insertCell(4);
                            newcell.innerHTML = response.data[i].MCUS_CUSNM

                            newcell = newrow.insertCell(5)
                            newcell.innerHTML = response.data[i].DLV_INVNO

                            newcell = newrow.insertCell(6)
                            newcell.innerHTML = response.data[i].DLV_SMTINVNO

                            newcell = newrow.insertCell(7)
                            newcell.innerHTML = response.data[i].DLV_TRANS

                            newcell = newrow.insertCell(8);
                            newcell.innerHTML = response.data[i].DLV_RMRK

                            newcell = newrow.insertCell(9);
                            newcell.style.cssText = 'display:none'
                            newcell.innerHTML = response.data[i].MCUS_CURCD

                            newcell = newrow.insertCell(10);
                            newcell.style.cssText = 'display:none';
                            newcell.innerHTML = response.data[i].MSTTRANS_TYPE

                            newcell = newrow.insertCell(11);
                            newcell.innerHTML = response.data[i].DLV_CONSIGN

                            newcell = newrow.insertCell(12)
                            newcell.style.cssText = 'display:none'
                            newcell.innerHTML = response.data[i].DLV_RPLCMNT

                            newcell = newrow.insertCell(13);
                            newcell.style.cssText = 'display:none';
                            newcell.innerHTML = response.data[i].DLV_VAT

                            newcell = newrow.insertCell(14);
                            newcell.style.cssText = 'display:none';
                            newcell.innerHTML = response.data[i].DLV_KNBNDLV

                            newcell = newrow.insertCell(15)
                            newcell.style.cssText = 'display:none'
                            newcell.innerHTML = response.data[i].DLV_FROMOFFICE

                            newcell = newrow.insertCell(16)
                            newcell.style.cssText = 'display:none'
                            newcell.innerHTML = response.data[i].DLV_CRTD

                            newcell = newrow.insertCell(17)
                            newcell.style.cssText = 'display:none'
                            newcell.innerHTML = response.data[i].DLV_CRTDTM

                            newcell = newrow.insertCell(18)
                            newcell.style.cssText = 'display:none'
                            newcell.innerHTML = response.data[i].DLV_USRID

                            newcell = newrow.insertCell(19)
                            newcell.style.cssText = 'display:none'
                            newcell.innerHTML = response.data[i].DLV_LUPDT

                            newcell = newrow.insertCell(20)
                            newcell.style.cssText = 'display:none'
                            newcell.innerHTML = response.data[i].DLV_APPRV

                            newcell = newrow.insertCell(21)
                            newcell.style.cssText = 'display:none'
                            newcell.innerHTML = response.data[i].DLV_APPRVTM

                            newcell = newrow.insertCell(22)
                            newcell.style.cssText = 'display:none'
                            newcell.innerHTML = response.data[i].DLV_POST

                            newcell = newrow.insertCell(23)
                            newcell.style.cssText = 'display:none'
                            newcell.innerHTML = response.data[i].DLV_POSTTM

                            newcell = newrow.insertCell(24)
                            newcell.style.cssText = 'display:none'
                            newcell.innerHTML = response.data[i].DLV_NOAJU

                            newcell = newrow.insertCell(25);
                            newcell.style.cssText = 'display:none';
                            newcell.innerHTML = response.data[i].DLV_BCTYPE

                            newcell = newrow.insertCell(26)
                            newcell.style.cssText = 'display:none'
                            newcell.innerHTML = response.data[i].DLV_NOPEN

                            newcell = newrow.insertCell(27)
                            newcell.style.cssText = 'display:none'
                            newcell.innerHTML = response.data[i].DLV_DESTOFFICE

                            newcell = newrow.insertCell(28)
                            newcell.style.cssText = 'display:none'
                            newcell.innerHTML = response.data[i].DLV_PURPOSE

                            newcell = newrow.insertCell(29)
                            newcell.style.cssText = 'display:none';
                            newcell.innerHTML = response.data[i].DLV_INVDT

                            newcell = newrow.insertCell(30)
                            newcell.innerHTML = response.data[i].DLV_BSGRP

                            newcell = newrow.insertCell(31)
                            newcell.innerHTML = response.data[i].DLV_CUSTDO

                            newcell = newrow.insertCell(32)
                            newcell.style.cssText = 'display:none'
                            newcell.innerHTML = response.data[i].DLV_ZJENIS_TPB_ASAL

                            newcell = newrow.insertCell(33)
                            newcell.style.cssText = 'display:none'
                            newcell.innerHTML = response.data[i].DLV_ZJENIS_TPB_TUJUAN

                            newcell = newrow.insertCell(34)
                            newcell.innerHTML = response.data[i].DLV_BCDATE

                            newcell = newrow.insertCell(35)
                            newcell.style.cssText = 'display:none'
                            newcell.innerHTML = response.data[i].DLV_ZSKB

                            newcell = newrow.insertCell(36)
                            newcell.style.cssText = 'display:none'
                            newcell.innerHTML = response.data[i].DLV_ZKODE_CARA_ANGKUT

                            newcell = newrow.insertCell(37)
                            newcell.style.cssText = 'display:none'
                            newcell.innerHTML = response.data[i].DLV_ZTANGGAL_SKB

                            let mstatus = ''
                            let mposted = String(response.data[i].DLV_POST)
                            let mapproved = String(response.data[i].DLV_APPRV)
                            let mcreated = String(response.data[i].DLV_CRTD)
                            if ((mposted == 'null') || (mposted.trim() == '')) {
                                if (response.data[i].RPSTOCK_REMARK) {
                                    mstatus = "Booked"
                                } else {
                                    if ((mapproved == 'null') || (mapproved.trim() == '')) {
                                        if ((mcreated == 'null') || (mcreated.trim() == '')) {

                                        } else {
                                            mstatus = "Saved"
                                        }
                                    } else {
                                        mstatus = "Approved"
                                    }

                                }
                            } else {
                                if (response.data[i].DLV_NOPEN != '') {
                                    mstatus = "Closed"
                                } else {
                                    mstatus = "Posted"
                                }
                            }

                            newcell = newrow.insertCell(38)
                            newcell.innerHTML = mstatus

                            let pdata = {
                                ctxid: response.data[i].DLV_ID,
                                ctxdt: response.data[i].DLV_DATE,
                                cajudt: response.data[i].DLV_BCDATE,
                                cdescription: response.data[i].DLV_DSCRPTN,
                                ccustcd: response.data[i].DLV_CUSTCD,
                                ccustnm: response.data[i].MCUS_CUSNM,
                                cinv: response.data[i].DLV_INVNO,
                                cinvsmt: response.data[i].DLV_SMTINVNO,
                                ctrans: response.data[i].DLV_TRANS,
                                cremark: response.data[i].DLV_RMRK,
                                ccurrency: response.data[i].MCUS_CURCD,
                                ctranstype: response.data[i].MSTTRANS_TYPE,
                                cconsign: response.data[i].DLV_CONSIGN,
                                cfromoffice: (response.data[i].DLV_FROMOFFICE == '-' ? '050900' : response.data[i].DLV_FROMOFFICE),
                                ccreatedby: response.data[i].DLV_CRTD,
                                ccreateddt: response.data[i].DLV_CRTDTM,
                                cupdatedby: response.data[i].DLV_USRID,
                                cupdateddt: response.data[i].DLV_LUPDT,
                                capprovedby: response.data[i].DLV_APPRV,
                                capproveddt: response.data[i].DLV_APPRVTM,
                                cpostedby: response.data[i].DLV_POST,
                                cposteddt: response.data[i].DLV_POSTTM,
                                cnoaju: response.data[i].DLV_NOAJU,
                                cbctype: response.data[i].DLV_BCTYPE,
                                cnopen: response.data[i].DLV_NOPEN,
                                cTglpen: response.data[i].DLV_RPDATE,
                                cdestoffice: response.data[i].DLV_DESTOFFICE,
                                cpurpose: response.data[i].DLV_PURPOSE,
                                cinvdt: response.data[i].DLV_INVDT,
                                cbg: response.data[i].DLV_BSGRP,
                                ccustdo: response.data[i].DLV_CUSTDO,
                                ctpb_asal: response.data[i].DLV_ZJENIS_TPB_ASAL,
                                ctpb_tujuan: response.data[i].DLV_ZJENIS_TPB_TUJUAN,
                                cskb: response.data[i].DLV_ZSKB,
                                cnamapengangkut: response.data[i].DLV_ZKODE_CARA_ANGKUT,
                                cskbdt: response.data[i].DLV_ZTANGGAL_SKB,
                                ccona: response.data[i].DLV_CONA,
                                clocfrom: response.data[i].DLV_LOCFR,
                                crprdoc: response.data[i].DLV_RPRDOC,
                                crparentdoc: response.data[i].DLV_PARENTDOC,
                                cdocbcout: response.data[i].RPSTOCK_REMARK,
                                DLV_ZNOMOR_AJU: response.data[i].DLV_ZNOMOR_AJU,
                                cstatus: mstatus,
                                cPemberitahu: response.data[i].DLVH_PEMBERITAHU,
                                cJabatan: response.data[i].DLVH_JABATAN
                            }
                            newrow.onclick = () => {
                                retrm_out_cclick_hnd(pdata)
                            }
                        }
                        mydes.innerHTML = ''
                        mydes.appendChild(myfrag)
                    } else {
                        alertify.warning('Transaction is not found')
                    }
                },
                error: function(xhr, xopt, xthrow) {
                    alertify.error(xthrow);
                }
            });
        }
    }

    function retrm_out_cclick_hnd(mrow) {
        retrm_out_inc_custcd = mrow.ccustcd
        let mtrans = mrow.ctrans
        let mremark = mrow.cremark
        let mtrans_type = mrow.ctranstype
        let mfromoffice = mrow.cfromoffice
        let mcreated = mrow.ccreatedby
        let mupdated = mrow.cupdatedby ? mrow.cupdatedby : ''
        let mupdatedtime = mrow.cupdateddt ? mrow.cupdateddt : ''
        let mapproved = mrow.capprovedby ? mrow.capprovedby : ''
        let mapprovedtime = mrow.capproveddt ? mrow.capproveddt : ''
        let mposted = mrow.cpostedby ? mrow.cpostedby : ''
        let mpostedtime = mrow.cposteddt ? mrow.cposteddt : ''
        let mnoaju = mrow.cnoaju
        let mbctype = mrow.cbctype
        let mnopen = mrow.cnopen ? mrow.cnopen : ''
        let mdestoffice = mrow.cdestoffice
        let mpurpose = mrow.cpurpose
        let minvdt = mrow.cinvdt
        let mtpb_asal = mrow.ctpb_asal
        let mtpb_tujuan = mrow.ctpb_tujuan
        let mskb = mrow.cskb
        let mnama_pengangkut = mrow.cnamapengangkut
        let mskb_tgl = mrow.cskbdt
        const mcona = mrow.ccona

        if (mrow.cstatus == 'Closed') {
            retrm_out_btn_save.disabled = true
            retrm_out_inc_btnfindmodcust.disabled = true
            retrm_out_btn_appr.disabled = true
            retrm_out_btn_tpb.disabled = true
            retrm_out_inc_z_btn_save.disabled = true
            retrm_out_inc_z_btn_save41.disabled = true
            retrm_out_inc_z_btn_save261.disabled = true
            retrm_out_inc_z_btn_save30.disabled = true
            retrm_out_inc_z_btn_save25.disabled = true
            retrm_out_inc_btn_shortcut.disabled = true
            retrm_out_inc_btn_add.disabled = true
            retrm_out_inc_btn_minus.disabled = true
            retrm_out_inc_btncopyFromResume.disabled = true
            retrm_out_inc_btn_add_packing.disabled = true
            retrm_out_inc_btn_min_packing.disabled = true
            retrm_out_donprc_fifo.disabled = true
            retrm_out_donprc_tbl_add.disabled = true
            retrm_out_so_tbl_add.disabled = true
        } else {
            retrm_out_btn_save.disabled = false
            retrm_out_inc_btnfindmodcust.disabled = false
            retrm_out_btn_appr.disabled = false
            retrm_out_btn_tpb.disabled = false
            retrm_out_inc_z_btn_save.disabled = false
            retrm_out_inc_z_btn_save41.disabled = false
            retrm_out_inc_z_btn_save261.disabled = false
            retrm_out_inc_z_btn_save30.disabled = false
            retrm_out_inc_z_btn_save25.disabled = false
            retrm_out_inc_btn_shortcut.disabled = false
            retrm_out_inc_btn_add.disabled = false
            retrm_out_inc_btn_minus.disabled = false
            retrm_out_inc_btncopyFromResume.disabled = false
            retrm_out_inc_btn_add_packing.disabled = false
            retrm_out_inc_btn_min_packing.disabled = false
            retrm_out_donprc_fifo.disabled = false
            retrm_out_donprc_tbl_add.disabled = false
            retrm_out_so_tbl_add.disabled = false
        }

        document.getElementById("retrm_out_inc_txt_DO").focus()
        document.getElementById("retrm_out_inc_txt_DO").value = mrow.ctxid
        document.getElementById("retrm_out_inc_txt_customerDO").value = mrow.ccustdo
        document.getElementById("retrm_out_parentdoc").value = mrow.crparentdoc
        document.getElementById('retrm_out_inc_custname').value = mrow.ccustnm
        document.getElementById("retrm_out_inc_curr").value = mrow.ccurrency
        document.getElementById('retrm_out_txt_invno').value = mrow.cinv
        document.getElementById('retrm_out_txt_invsmt').value = mrow.cinvsmt
        document.getElementById('retrm_out_inc_txt_transport').value = mtrans + '_' + mtrans_type
        document.getElementById('retrm_out_inc_txt_transporttype').value = mtrans_type
        document.getElementById('retrm_out_inc_cmb_bg').value = mrow.cbg
        document.getElementById('retrm_out_inc_cmb_bg').disabled = true
        $("#retrm_out_inc_customs_date").datepicker('update', mrow.cajudt)
        $("#retrm_out_txt_DOdate").datepicker('update', mrow.ctxdt)
        document.getElementById('retrm_out_inc_consignee').value = mrow.cconsign
        document.getElementById('retrm_out_cmb_bcdoc').value = mbctype
        document.getElementById("retrm_out_inc_destoffice").value = mdestoffice;

        document.getElementById('retrm_out_inc_txt_createdby').value = mcreated
        document.getElementById('retrm_out_inc_txt_createdtime').value = mrow.ccreateddt;
        document.getElementById('retrm_out_inc_txt_luby').value = ((mupdated == 'null') || (mupdated.trim() == '') ? '' : mupdated);
        document.getElementById('retrm_out_inc_txt_lutime').value = ((mupdatedtime == 'null') || (mupdatedtime.trim() == '') ? '' : mupdatedtime);
        document.getElementById('retrm_out_inc_txt_apprby').value = ((mapproved == 'null') || (mapproved.trim() == '') ? '' : mapproved);
        document.getElementById('retrm_out_inc_txt_apprtime').value = ((mapprovedtime == 'null') || (mapprovedtime.trim() == '') ? '' : mapprovedtime);
        document.getElementById('retrm_out_inc_txt_postedby').value = ((mposted == 'null') || (mposted.trim() == '') ? '' : mposted);
        document.getElementById('retrm_out_inc_txt_postedtime').value = ((mpostedtime == 'null') || (mpostedtime.trim() == '') ? '' : mpostedtime);
        if (mrow.cPemberitahu) {
            let elNamaPemberitahu = document.getElementsByClassName('retrm_nama_pemberitahu')
            for(let el of elNamaPemberitahu)
            {
                el.value = mrow.cPemberitahu
            }
        }
        if (mrow.cJabatan) {
            let elJabatanPemberitahu = document.getElementsByClassName('retrm_jabatan_pemberitahu')
            for(let el of elJabatanPemberitahu)
            {
                el.value = mrow.cJabatan
            }
        }
        if (mrow.cstatus != 'Posted') {
            if (mrow.cdocbcout) {
                document.getElementById('retrm_out_status').value = mrow.cstatus;
                document.getElementById('retrm_out_btn_post_cancel').classList.remove('disabled')
            } else {
                document.getElementById('retrm_out_btn_post_cancel').classList.add('disabled')
                if ((mapproved == 'null') || (mapproved.trim() == '')) {
                    if ((mcreated == 'null') || (mcreated.trim() == '')) {

                    } else {
                        document.getElementById('retrm_out_status').value = mrow.cstatus;
                    }
                } else {
                    document.getElementById('retrm_out_status').value = mrow.cstatus;
                }
            }
        } else {
            document.getElementById('retrm_out_status').value = mrow.cstatus
            document.getElementById('retrm_out_btn_post_cancel').classList.remove('disabled')
        }
        document.getElementById('retrm_out_inc_txt_nopen').value = ''
        document.getElementById('retrm_out_inc_txt_tglpen').value = ''

        document.getElementById('retrm_out_inc_txt_nopen25').value = ''
        document.getElementById('retrm_out_inc_txt_tglpen25').value = ''

        document.getElementById('retrm_out_inc_txt_nopen41').value = ''
        document.getElementById('retrm_out_inc_txt_tglpen41').value = ''


        if (mbctype == '27') {
            document.getElementById('retrm_out_inc_txt_noaju').value = mnoaju.trim()
            document.getElementById('retrm_out_inc_fromoffice').value = mfromoffice
            document.getElementById('retrm_out_inc_cmb_jenisTPB').value = mtpb_asal
            document.getElementById('retrm_out_inc_cmb_jenisTPBtujuan').value = mtpb_tujuan
            document.getElementById('retrm_out_inc_cmb_tujuanpengiriman').value = mpurpose
            document.getElementById('retrm_out_inc_txt_nokontrak').value = mcona
            if (mrow.cTglpen) {
                document.getElementById('retrm_out_inc_txt_nopen').value = mnopen.trim()
                document.getElementById('retrm_out_inc_txt_tglpen').value = mrow.cTglpen
            }
        } else if (mbctype == '25') {
            document.getElementById('retrm_out_inc_txt_noaju25').value = mnoaju.trim()
            document.getElementById('retrm_out_inc_fromoffice25').value = mfromoffice
            document.getElementById('retrm_out_inc_cmb_jenisTPB25').value = mtpb_asal
            document.getElementById('retrm_out_inc_txt_noskb').value = mskb
            $("#retrm_out_inc_txt_noskb_tgl").datepicker('update', mskb_tgl)
            document.getElementById('retrm_out_inc_jenis_saranapengangkut25').value = mnama_pengangkut
            if (mrow.cTglpen) {
                document.getElementById('retrm_out_inc_txt_nopen25').value = mnopen.trim()
                document.getElementById('retrm_out_inc_txt_tglpen25').value = mrow.cTglpen
            }
        } else if (mbctype == '41') {
            document.getElementById('retrm_out_inc_txt_noaju41').value = mnoaju.trim()
            document.getElementById('retrm_out_inc_fromoffice41').value = mfromoffice
            document.getElementById('retrm_out_inc_cmb_jenisTPB41').value = mtpb_asal
            document.getElementById('retrm_out_inc_cmb_tujuanpengiriman41').value = mpurpose
            document.getElementById('retrm_out_inc_txt_nokontrak41').value = mcona
            if (mrow.cTglpen) {
                document.getElementById('retrm_out_inc_txt_nopen41').value = mnopen.trim()
                document.getElementById('retrm_out_inc_txt_tglpen41').value = mrow.cTglpen
            }
        } else if (mbctype == '261') {
            document.getElementById('retrm_out_inc_txt_noaju261').value = mnoaju.trim()
            document.getElementById('retrm_out_inc_fromoffice261').value = mfromoffice
            document.getElementById('retrm_out_inc_cmb_tujuanpengiriman261').value = mpurpose
            document.getElementById('retrm_out_inc_txt_nokontrak261').value = mcona
            document.getElementById('retrm_out_inc_jenis_saranapengangkut261').value = mnama_pengangkut
            if (mrow.cTglpen) {
                document.getElementById('retrm_out_inc_txt_nopen261').value = mnopen.trim()
                document.getElementById('retrm_out_inc_txt_tglpen261').value = mrow.cTglpen
            }
        } else if (mbctype == '30') {
            document.getElementById('retrm_out_inc_txt_noaju30').value = mrow.DLV_ZNOMOR_AJU
            if (mrow.cTglpen) {
                document.getElementById('retrm_out_inc_txt_nopen30').value = mnopen.trim()
                document.getElementById('retrm_out_inc_txt_tglpen30').value = mrow.cTglpen
            }
        }

        document.getElementById('retrm_out_rprdoc').value = mrow.crprdoc
        document.getElementById('retrm_out_cmb_locfrom').value = mrow.clocfrom
        document.getElementById('retrm_out_description').value = mrow.cdescription
        $("#retrm_out_MODSAVED").modal('hide')
        document.getElementById('retrm_out_div_infoAfterPost').innerHTML = ''
        retrm_out_f_getdetail(mrow.ctxid)
    }
    $("#retrm_out_MODSAVED").on('shown.bs.modal', function() {
        document.getElementById('retrm_out_txtxtsearch').focus()
    })

    function retrm_out_f_getdetail(ptxid) {
        $.ajax({
            type: "get",
            url: "<?=base_url('DELV/getdetails')?>",
            data: {
                intxid: ptxid,
                intype: '0'
            },
            dataType: "json",
            success: function(response) {
                let ttlrows = response.data.length
                let mydes = document.getElementById("retrm_out_inc_divku");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("retrm_out_inc_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("retrm_out_inc_tbl");
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                let newrow, newcell, newText;
                tableku2.innerHTML = '';
                for (let i = 0; i < ttlrows; i++) {
                    newrow = tableku2.insertRow(-1)
                    newrow.onclick = (event) => {
                        retrm_out_inc_tbl_tbody_tr_eC(event)
                    }
                    newcell = newrow.insertCell(0)
                    newcell.classList.add('d-none')
                    newcell.innerHTML = response.data[i].DLV_LINE
                    newcell = newrow.insertCell(1)
                    newcell.contentEditable = true
                    newcell.innerHTML = response.data[i].DLV_ITMCD
                    newcell = newrow.insertCell(2)
                    newcell.contentEditable = true
                    newcell.classList.add('text-end')
                    newcell.innerHTML = numeral(response.data[i].DLV_QTY).format('0,0.00')
                    newcell = newrow.insertCell(3)
                    newcell.contentEditable = true
                    newcell.innerHTML = response.data[i].DLV_RMRK
                    newcell = newrow.insertCell(4)
                    newcell.contentEditable = true
                    newcell.innerHTML = response.data[i].DLV_ITMD1
                    newcell = newrow.insertCell(5)
                    newcell.contentEditable = true
                    newcell.innerHTML = response.data[i].DLV_ITMSPTNO
                }
                mydes.innerHTML = ''
                mydes.appendChild(myfrag)

                ttlrows = response.data_pkg.length
                mydes = document.getElementById("retrm_out_inc_divku_packing");
                myfrag = document.createDocumentFragment();
                mtabel = document.getElementById("retrm_out_inc_tbl_packing");
                cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                tabell = myfrag.getElementById("retrm_out_inc_tbl_packing");
                tableku2 = tabell.getElementsByTagName("tbody")[0];
                tableku2.innerHTML = ''
                for (let i = 0; i < ttlrows; i++) {
                    newrow = tableku2.insertRow(-1)
                    newrow.onclick = (event) => {
                        retrm_out_inc_tbl1_tbody_tr_eC(event)
                    }
                    newcell = newrow.insertCell(0)
                    newcell.classList.add('d-none')
                    newcell.innerHTML = response.data_pkg[i].DLV_PKG_LINE
                    newcell = newrow.insertCell(1)
                    newcell.classList.add('text-end', 'd-none')
                    newcell.contentEditable = true
                    newcell.innerHTML = response.data_pkg[i].DLV_PKG_P
                    newcell = newrow.insertCell(2)
                    newcell.contentEditable = true
                    newcell.classList.add('text-end', 'd-none')
                    newcell.innerHTML = response.data_pkg[i].DLV_PKG_L
                    newcell = newrow.insertCell(3)
                    newcell.classList.add('text-end', 'd-none')
                    newcell.contentEditable = true
                    newcell.innerHTML = response.data_pkg[i].DLV_PKG_T
                    newcell = newrow.insertCell(4)
                    newcell.contentEditable = true
                    newcell.innerHTML = response.data_pkg[i].DLV_PKG_ITM
                    newcell = newrow.insertCell(5)
                    newcell.classList.add('text-end')
                    newcell.contentEditable = true
                    newcell.innerHTML = response.data_pkg[i].DLV_PKG_QTY
                    newcell = newrow.insertCell(6)
                    newcell.classList.add('text-end')
                    newcell.contentEditable = true
                    newcell.innerHTML = response.data_pkg[i].DLV_PKG_NWG
                    newcell = newrow.insertCell(7)
                    newcell.classList.add('text-end')
                    newcell.contentEditable = true
                    newcell.innerHTML = response.data_pkg[i].DLV_PKG_GWG
                    newcell = newrow.insertCell(8)
                    newcell.contentEditable = true
                    newcell.innerHTML = response.data_pkg[i].DLV_PKG_MEASURE
                    newcell = newrow.insertCell(9)
                    newcell.contentEditable = true
                    newcell.innerHTML = response.data_pkg[i].DLV_PKG_ITMTYPE
                }
                mydes.innerHTML = ''
                mydes.appendChild(myfrag)

                ttlrows = response.data_rmdo.length
                mydes = document.getElementById("retrm_out_donprc_div");
                myfrag = document.createDocumentFragment();
                mtabel = document.getElementById("retrm_out_donprc_tbl");
                cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                tabell = myfrag.getElementById("retrm_out_donprc_tbl");
                tableku2 = tabell.getElementsByTagName("tbody")[0];
                tableku2.innerHTML = ''
                for (let i = 0; i < ttlrows; i++) {
                    newrow = tableku2.insertRow(-1)
                    newcell = newrow.insertCell(0)
                    newcell.classList.add('d-none')
                    newcell.innerHTML = response.data_rmdo[i].DLVRMDOC_LINE
                    newcell = newrow.insertCell(1)
                    newcell.classList.add('d-none')
                    newcell.innerHTML = response.data_rmdo[i].DLVRMDOC_AJU
                    newcell = newrow.insertCell(2)
                    newcell.innerHTML = response.data_rmdo[i].DLVRMDOC_NOPEN
                    newcell = newrow.insertCell(3)
                    newcell.innerHTML = response.data_rmdo[i].DLVRMDOC_DO
                    newcell = newrow.insertCell(4)
                    newcell.innerHTML = response.data_rmdo[i].RCV_BCDATE
                    newcell = newrow.insertCell(5)
                    newcell.innerHTML = response.data_rmdo[i].DLVRMDOC_ITMID
                    newcell = newrow.insertCell(6)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = response.data_rmdo[i].DLVRMDOC_ITMQT
                    newcell = newrow.insertCell(7)
                    newcell.classList.add('text-end')
                    newcell.contentEditable = true
                    newcell.innerHTML = response.data_rmdo[i].DLVRMDOC_PRPRC.substr(0, 1) == '.' ? '0' + response.data_rmdo[i].DLVRMDOC_PRPRC : response.data_rmdo[i].DLVRMDOC_PRPRC
                    newcell = newrow.insertCell(8)
                    newcell.innerHTML = response.data_rmdo[i].RCV_BCTYPE
                    newcell.classList.add('text-center')
                    newcell = newrow.insertCell(9)
                    newcell.onclick = function(event) {
                        if (event.srcElement.tagName === 'SPAN') {
                            retrm_out_inc_selected_row = event.srcElement.parentElement.parentElement.rowIndex
                        } else {
                            retrm_out_inc_selected_row = event.srcElement.parentElement.rowIndex
                        }
                        retrm_out_doprc_remrow()
                    }
                    newcell.style.cssText = 'cursor:pointer'
                    newcell.classList.add('text-center')
                    newcell.innerHTML = `<span class="fas fa-trash text-danger"></span>`
                    newcell = newrow.insertCell(10)
                    newcell.contentEditable = true
                    newcell.innerHTML = response.data_rmdo[i].DLVRMDOC_TYPE
                }
                mydes.innerHTML = ''
                mydes.appendChild(myfrag)


                ////Price from SO
                ttlrows = response.data_rmso.length
                mydes = document.getElementById("retrm_out_so_div");
                myfrag = document.createDocumentFragment();
                mtabel = document.getElementById("retrm_out_so_tbl");
                cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                tabell = myfrag.getElementById("retrm_out_so_tbl");
                tableku2 = tabell.getElementsByTagName("tbody")[0];
                tableku2.innerHTML = ''
                for (let i = 0; i < ttlrows; i++) {
                    newrow = tableku2.insertRow(-1)
                    newcell = newrow.insertCell(0)
                    newcell.classList.add('d-none')
                    newcell.innerHTML = response.data_rmso[i].DLVRMSO_LINE
                    newcell = newrow.insertCell(1)
                    newcell.innerHTML = response.data_rmso[i].DLVRMSO_CPO
                    newcell = newrow.insertCell(2)
                    newcell.innerHTML = response.data_rmso[i].DLVRMSO_CPOLINE
                    newcell = newrow.insertCell(3)
                    newcell.innerHTML = response.data_rmso[i].DLVRMSO_ITMID
                    newcell = newrow.insertCell(4)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = response.data_rmso[i].DLVRMSO_ITMQT
                    newcell = newrow.insertCell(5)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = response.data_rmso[i].DLVRMSO_PRPRC.substr(0, 1) == '.' ? '0' + response.data_rmso[i].DLVRMSO_PRPRC : response.data_rmso[i].DLVRMSO_PRPRC
                    newcell = newrow.insertCell(6)
                    newcell.onclick = function(event) {
                        retrm_out_inc_selected_row = event.srcElement.tagName === 'SPAN' ? event.srcElement.parentElement.parentElement.rowIndex :
                            event.srcElement.parentElement.rowIndex
                        retrm_out_soprc_remrow()
                    }
                    newcell.style.cssText = 'cursor:pointer'
                    newcell.classList.add('text-center')
                    newcell.innerHTML = `<span class="fas fa-trash text-danger"></span>`
                }
                mydes.innerHTML = ''
                mydes.appendChild(myfrag)

                ttlrows = response.data_scr.length
                mydes = document.getElementById("retrm_out_limbah_barang_div");
                myfrag = document.createDocumentFragment();
                mtabel = document.getElementById("retrm_out_limbah_barang_tbl");
                cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                tabell = myfrag.getElementById("retrm_out_limbah_barang_tbl");
                tableku2 = tabell.getElementsByTagName("tbody")[0];
                tableku2.innerHTML = ''
                for (let i = 0; i < ttlrows; i++) {
                    newrow = tableku2.insertRow(-1)
                    newcell = newrow.insertCell(0)
                    newcell.classList.add('d-none')
                    newcell.innerHTML = response.data_scr[i].DLVSCR_LINE
                    newcell.contentEditable = true
                    newcell = newrow.insertCell(1)
                    newcell.innerHTML = response.data_scr[i].DLVSCR_ITMID
                    newcell.contentEditable = true
                    newcell = newrow.insertCell(2)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = response.data_scr[i].DLVSCR_ITMQT
                    newcell.contentEditable = true
                    newcell = newrow.insertCell(3)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = numeral(response.data_scr[i].DLVSCR_PRPRC).format(',')
                    newcell.contentEditable = true
                    newcell = newrow.insertCell(4)
                    newcell.onclick = (event) => {
                        let gindex = event.srcElement.tagName === 'SPAN' ? event.srcElement.parentElement.parentElement.rowIndex :
                            event.srcElement.parentElement.rowIndex
                        retrm_out_soeximscr_remrow(gindex)
                    }
                    newcell.style.cssText = 'cursor:pointer'
                    newcell.classList.add('text-center')
                    newcell.innerHTML = '<span class="fas fa-trash text-danger"></span>'
                }
                mydes.innerHTML = ''
                mydes.appendChild(myfrag)
            },
            error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow)
            }
        })
    }

    function retrm_out_btn_toxls_e_click() {
        let txid = document.getElementById('retrm_out_inc_txt_DO').value;
        if (txid.trim() == '') {
            alertify.warning('Please fill TX ID first');
            document.getElementById('retrm_out_inc_txt_DO').focus();
            return;
        }
        Cookies.set('CKPDLV_NO', txid, {
            expires: 365
        })

        window.open("<?=base_url('printdocs_2')?>", '_blank');
    }

    function retrm_out_btn_customs_eC() {
        const txid = document.getElementById('retrm_out_inc_txt_DO').value;
        if (txid.length < 3) {
            alertify.message('TX ID is required');
            return
        }
        const mdokumenpab = document.getElementById('retrm_out_cmb_bcdoc')
        switch (mdokumenpab.value) {
            case '-':
                alertify.message('please select <b>Customs Document</b> first !');
                mdokumenpab.focus();
                break;
            case '25':
                $("#retrm_out_inc_CUSTOMSMOD25").modal('show');
                break;
            case '27':
                $("#retrm_out_inc_CUSTOMSMOD").modal('show');
                break;
            case '41':
                $("#retrm_out_inc_CUSTOMSMOD41").modal('show');
                break;
            case '261':
                $("#retrm_out_inc_CUSTOMSMOD261").modal('show');
                break;
            case '30':
                $("#retrm_out_inc_CUSTOMSMOD30").modal('show');
                break;
        }
    }

    function retrm_out_inc_e_pastecol1(event) {
        let datapas = event.clipboardData.getData('text/html')
        const mcona_tbllength = document.getElementById('retrm_out_inc_tbl').getElementsByTagName('tbody')[0].rows.length;
        if (datapas === "") {
            datapas = event.clipboardData.getData('text')
            let adatapas = datapas.split('\n')
            let ttlrowspasted = 0;
            for (let c = 0; c < adatapas.length; c++) {
                if (adatapas[c].trim() != '') {
                    ttlrowspasted++
                }
            }
            let table = $(`#retrm_out_inc_tbl tbody`)
            let incr = 0
            if ((mcona_tbllength - retrm_out_inc_selected_row) < ttlrowspasted) {
                const needRows = ttlrowspasted - (mcona_tbllength - retrm_out_inc_selected_row)
                for (let i = 0; i < needRows; i++) {
                    retrm_out_inc_addrow()
                }
            }
            for (let i = 0; i < ttlrowspasted; i++) {
                const mcol = adatapas[i].split('\t')
                const ttlcol = mcol.length
                for (let k = 0;
                    (k < ttlcol) && (k < 4); k++) {
                    table.find('tr').eq((i + retrm_out_inc_selected_row)).find('td').eq((k + retrm_out_inc_selected_col)).text(mcol[k].trim())
                }
            }
        } else {
            let tmpdom = document.createElement('html')
            tmpdom.innerHTML = datapas
            let myhead = tmpdom.getElementsByTagName('head')[0]
            let myscript = myhead.getElementsByTagName('script')[0]
            let mybody = tmpdom.getElementsByTagName('body')[0]
            let mytable = mybody.getElementsByTagName('table')[0]
            let mytbody = mytable.getElementsByTagName('tbody')[0]
            let mytrlength = mytbody.getElementsByTagName('tr').length
            let table = $(`#retrm_out_inc_tbl tbody`)
            let incr = 0
            let startin = 0

            if (typeof(myscript) != 'undefined') { //check if clipboard from IE
                startin = 3
            }
            if ((mcona_tbllength - retrm_out_inc_selected_row) < (mytrlength - startin)) {
                let needRows = (mytrlength - startin) - (mcona_tbllength - retrm_out_inc_selected_row);
                for (let i = 0; i < needRows; i++) {
                    retrm_out_inc_addrow()
                }
            }

            let b = 0
            for (let i = startin; i < (mytrlength); i++) {
                let ttlcol = mytbody.getElementsByTagName('tr')[i].getElementsByTagName('td').length
                for (let k = 0;
                    (k < ttlcol) && (k < 4); k++) {
                    let dkol = mytbody.getElementsByTagName('tr')[i].getElementsByTagName('td')[k].innerText
                    table.find('tr').eq((b + retrm_out_inc_selected_row)).find('td').eq((k + retrm_out_inc_selected_col)).text(dkol.trim())
                }
                b++
            }
        }
        event.preventDefault()
    }

    function retrm_out_inc_tbl_tbody_tr_eC(e) {
        retrm_out_inc_selected_row = e.srcElement.parentElement.rowIndex - 1
        retrm_out_inc_selected_col = e.srcElement.cellIndex
    }

    function retrm_out_inc_tbl1_tbody_tr_eC(e) {
        retrm_out_inc_selected_row1 = e.srcElement.parentElement.rowIndex - 1
        retrm_out_inc_selected_col1 = e.srcElement.cellIndex
    }

    function retrm_out_inc_addrow() {
        let mytbody = document.getElementById('retrm_out_inc_tbl').getElementsByTagName('tbody')[0]
        let newrow, newcell
        newrow = mytbody.insertRow(-1)
        newrow.onclick = (event) => {
            retrm_out_inc_tbl_tbody_tr_eC(event)
        }

        newcell = newrow.insertCell(0)
        newcell.classList.add('d-none')
        newcell.innerHTML = ''

        newcell = newrow.insertCell(1)
        newcell.contentEditable = true
        newcell.focus()

        newcell = newrow.insertCell(2)
        newcell.contentEditable = true
        newcell.classList.add('text-end')
        newcell.innerHTML = '0'

        newcell = newrow.insertCell(3)
        newcell.title = 'Remark'
        newcell.contentEditable = true
        newcell.innerHTML = ''

        newcell = newrow.insertCell(4)
        newcell.title = 'Item Name'
        newcell.contentEditable = true
        newcell.innerHTML = ''
        newcell = newrow.insertCell(5)
        newcell.title = 'Item Type'
        newcell.contentEditable = true
        newcell.innerHTML = ''
    }

    function retrm_out_inc_addrow1() {
        let mytbody = document.getElementById('retrm_out_inc_tbl_packing').getElementsByTagName('tbody')[0]
        let newrow, newcell
        newrow = mytbody.insertRow(-1)
        newrow.onclick = (event) => {
            retrm_out_inc_tbl1_tbody_tr_eC(event)
        }

        newcell = newrow.insertCell(0)
        newcell.classList.add('d-none')
        newcell.innerHTML = ''

        newcell = newrow.insertCell(1)
        newcell.classList.add('text-end', 'd-none')
        newcell.contentEditable = true
        newcell.focus()

        newcell = newrow.insertCell(2)
        newcell.contentEditable = true
        newcell.classList.add('text-end', 'd-none')
        newcell.innerHTML = '0'

        newcell = newrow.insertCell(3)
        newcell.classList.add('text-end', 'd-none')
        newcell.contentEditable = true
        newcell.innerHTML = ''

        newcell = newrow.insertCell(4)
        newcell.contentEditable = true
        newcell.title = 'Item Id'
        newcell.innerHTML = ''
        newcell = newrow.insertCell(5)
        newcell.classList.add('text-end')
        newcell.contentEditable = true
        newcell.title = 'Qty'
        newcell.innerHTML = ''
        newcell = newrow.insertCell(6)
        newcell.classList.add('text-end')
        newcell.contentEditable = true
        newcell.title = 'Net Weight'
        newcell.innerHTML = ''
        newcell = newrow.insertCell(7)
        newcell.classList.add('text-end')
        newcell.contentEditable = true
        newcell.title = 'Gross Weight'
        newcell.innerHTML = ''
        newcell = newrow.insertCell(8)
        newcell.contentEditable = true
        newcell.title = 'Measurement'
        newcell.innerHTML = 'Box'
        newcell = newrow.insertCell(9)
        newcell.contentEditable = true
        newcell.title = 'Item type'
    }

    function retrm_out_inc_btnadd() {
        retrm_out_inc_addrow1()
        const mytbody = document.getElementById('retrm_out_inc_tbl').getElementsByTagName('tbody')[0]
        retrm_out_inc_selected_row = mytbody.rows.length - 1
        retrm_out_inc_selected_col = 1
    }
    $("#retrm_out_inc_customs_date").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    })
    $("#retrm_out_txt_DOdate").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    })
    $("#retrm_out_scr_period_search").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    })
    $("#retrm_out_inc_txt_tglpen30").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    })
    $("#retrm_out_inc_customs_date").datepicker('update', new Date());
    $("#retrm_out_txt_DOdate").datepicker('update', new Date())
    $("#retrm_out_scr_period_search").datepicker('update', new Date())
    $("#retrm_out_inc_txt_tglpen30").datepicker('update', new Date())

    $("#retrm_out_inc_txt_transport").change(function(e) {
        let mdataplat = $(this).val();
        let adata = mdataplat.split("_");
        document.getElementById("retrm_out_inc_txt_transporttype").value = adata[1] ? adata[1] : '';
    });

    function retrm_out_btn_save_eC() {
        const docno = document.getElementById('retrm_out_inc_txt_DO').value
        const docnoDate = document.getElementById('retrm_out_txt_DOdate').value
        const customdate = document.getElementById('retrm_out_inc_customs_date').value
        const invno = document.getElementById('retrm_out_txt_invno').value
        const invnosmt = document.getElementById('retrm_out_txt_invsmt').value
        const bisgrup = document.getElementById('retrm_out_inc_cmb_bg').value
        const consignee = document.getElementById('retrm_out_inc_consignee')
        const transportNum = document.getElementById('retrm_out_inc_txt_transport')
        const mdokumenpab = document.getElementById('retrm_out_cmb_bcdoc')
        const mdescription = document.getElementById('retrm_out_description').value
        const mlocfrom = document.getElementById('retrm_out_cmb_locfrom')
        const rprdoc = document.getElementById('retrm_out_rprdoc').value
        const megadoc = document.getElementById('retrm_out_parentdoc').value
        const custDO = document.getElementById('retrm_out_inc_txt_customerDO').value
        const pknum41 = document.getElementById('retrm_out_inc_txt_nokontrak41').value

        if (consignee.value === '-') {
            alertify.warning(`Please select consignee first`)
            consignee.focus()
            return
        }
        const aTransport = transportNum.value.split('_')
        if (retrm_out_inc_custcd === '-') {
            alertify.warning(`Please select customer first`)
            return
        }
        let mtbl = document.getElementById('retrm_out_inc_tbl')
        let tableku2 = mtbl.getElementsByTagName("tbody")[0]
        let mtbltr = tableku2.getElementsByTagName('tr')
        let ttlrows = mtbltr.length

        let aitem = [];
        let aqty = []
        let aremark = [];
        let arowid = [];
        let aitemdesc = [];
        let aitemsptno = [];

        let apkg_line = []
        let apkg_p = []
        let apkg_l = []
        let apkg_t = []
        let apkg_item = []
        let apkg_qty = []
        let apkg_netw = []
        let apkg_grossw = []
        let apkg_measure = []
        let apkg_itmtype = []

        let armdoc_itmID = []
        let armdoc_itmQT = []
        let armdoc_itmDO = []
        let armdoc_itmNOAJU = []
        let armdoc_itmNOPEN = []
        let armdoc_itmPRC = []
        let armdoc_itmLINE = []
        let armdoc_TYPE = []

        let armso_itmID = []
        let armso_itmQT = []
        let armso_itmCPO = []
        let armso_itmCPOLINE = []
        let armso_itmPRC = []
        let armso_itmLINE = []

        let armscr_itmID = []
        let armscr_itmQT = []
        let armscr_itmPRC = []
        let armscr_itmLINE = []

        let iscontainScrap = false
        let mitemcode = ''
        for (let i = 0; i < ttlrows; i++) {
            mitemcode = tableku2.rows[i].cells[1].innerText.trim().toUpperCase()
            if (mitemcode.includes('SCRAP')) {
                iscontainScrap = true
            }
            arowid.push(tableku2.rows[i].cells[0].innerText)
            aitem.push(mitemcode)
            aqty.push(numeral(tableku2.rows[i].cells[2].innerText).value())
            aremark.push(tableku2.rows[i].cells[3].innerText.replace(/\s+/g, ' ').trim())
            aitemdesc.push(tableku2.rows[i].cells[4].innerText.replace(/\s+/g, ' ').trim())
            aitemsptno.push(tableku2.rows[i].cells[5].innerText.replace(/\s+/g, ' ').trim())
        }

        if (aitem.length == 0) {
            alertify.warning(`there is no item will be processed`)
            return
        }
        mtbl = document.getElementById('retrm_out_inc_tbl_packing')
        tableku2 = mtbl.getElementsByTagName("tbody")[0]
        mtbltr = tableku2.getElementsByTagName('tr')
        ttlrows = mtbltr.length
        for (let i = 0; i < ttlrows; i++) {
            if (isNaN(tableku2.rows[i].cells[1].innerText) ||
                isNaN(tableku2.rows[i].cells[2].innerText) ||
                isNaN(tableku2.rows[i].cells[3].innerText)
            ) {
                alertify.message('numeric value is required')
                return
            }
            apkg_line.push(tableku2.rows[i].cells[0].innerText)
            apkg_p.push(isNaN(tableku2.rows[i].cells[1].innerText) ? 0 : tableku2.rows[i].cells[1].innerText)
            apkg_l.push(isNaN(tableku2.rows[i].cells[2].innerText) ? 0 : tableku2.rows[i].cells[2].innerText)
            apkg_t.push(isNaN(tableku2.rows[i].cells[3].innerText) ? 0 : tableku2.rows[i].cells[3].innerText)
            apkg_item.push(tableku2.rows[i].cells[4].innerText)
            apkg_qty.push(tableku2.rows[i].cells[5].innerText.replace(/\n+/g, ''))
            apkg_netw.push(tableku2.rows[i].cells[6].innerText.replace(/\n+/g, ''))
            apkg_grossw.push(tableku2.rows[i].cells[7].innerText.replace(/\n+/g, ''))
            apkg_measure.push(tableku2.rows[i].cells[8].innerText)
            apkg_itmtype.push(tableku2.rows[i].cells[9].innerText)
        }

        mtbl = document.getElementById('retrm_out_donprc_tbl')
        tableku2 = mtbl.getElementsByTagName("tbody")[0]
        mtbltr = tableku2.getElementsByTagName('tr')
        ttlrows = mtbltr.length

        for (let i = 0; i < ttlrows; i++) {
            armdoc_itmLINE.push(tableku2.rows[i].cells[0].innerText)
            armdoc_itmNOAJU.push(tableku2.rows[i].cells[1].innerText)
            armdoc_itmNOPEN.push(tableku2.rows[i].cells[2].innerText)
            armdoc_itmDO.push(tableku2.rows[i].cells[3].innerText)
            armdoc_itmID.push(tableku2.rows[i].cells[5].innerText)
            armdoc_itmQT.push(tableku2.rows[i].cells[6].innerText)
            armdoc_itmPRC.push(tableku2.rows[i].cells[7].innerText)
            armdoc_TYPE.push(tableku2.rows[i].cells[10].innerText)
        }

        mtbl = document.getElementById('retrm_out_so_tbl')
        tableku2 = mtbl.getElementsByTagName("tbody")[0]
        mtbltr = tableku2.getElementsByTagName('tr')
        ttlrows = mtbltr.length


        for (let i = 0; i < ttlrows; i++) {
            armso_itmLINE.push(tableku2.rows[i].cells[0].innerText)
            armso_itmCPO.push(tableku2.rows[i].cells[1].innerText)
            armso_itmCPOLINE.push(tableku2.rows[i].cells[2].innerText)
            armso_itmID.push(tableku2.rows[i].cells[3].innerText)
            armso_itmQT.push(numeral(tableku2.rows[i].cells[4].innerText).value())
            armso_itmPRC.push(tableku2.rows[i].cells[5].innerText)
        }

        mtbl = document.getElementById('retrm_out_limbah_barang_tbl')
        tableku2 = mtbl.getElementsByTagName("tbody")[0]
        mtbltr = tableku2.getElementsByTagName('tr')
        ttlrows = mtbltr.length

        for (let i = 0; i < ttlrows; i++) {
            armscr_itmLINE.push(tableku2.rows[i].cells[0].innerText)
            armscr_itmID.push(tableku2.rows[i].cells[1].innerText)
            armscr_itmQT.push(numeral(tableku2.rows[i].cells[2].innerText).value())
            armscr_itmPRC.push(numeral(tableku2.rows[i].cells[3].innerText).value())
        }

        if (mlocfrom.value === '-' && !iscontainScrap) {
            alertify.warning('Location from is required')
            mlocfrom.focus()
            return
        }

        if (confirm("Are you sure ?")) {
            document.getElementById('retrm_out_btn_save').innerHTML = `<i class="fas fa-spinner fa-spin"></i>`
            document.getElementById('retrm_out_btn_save').disabled = true
            $.ajax({
                type: "post",
                url: "<?=base_url('DELV/set_rm')?>",
                data: {
                    indoc: docno,
                    indocdate: docnoDate,
                    indescription: mdescription,
                    incustomsdate: customdate,
                    inbisgrup: bisgrup,
                    incuscd: retrm_out_inc_custcd,
                    ininvno: invno,
                    ininvnosmt: invnosmt,
                    inconsign: consignee.value,
                    intransportNum: aTransport[0],
                    indokumenpab: mdokumenpab.value,
                    inlocfrom: mlocfrom.value,
                    inrprdoc: rprdoc,
                    megadoc: megadoc,
                    incustDO: custDO,
                    pknum41: pknum41,
                    initem: aitem,
                    inqty: aqty,
                    inremark: aremark,
                    aitemdesc: aitemdesc,
                    aitemsptno: aitemsptno,
                    inrowid: arowid,
                    inpkg_line: apkg_line,
                    inpkg_p: apkg_p,
                    inpkg_l: apkg_l,
                    inpkg_t: apkg_t,
                    armdoc_itmLINE: armdoc_itmLINE,
                    armdoc_itmNOAJU: armdoc_itmNOAJU,
                    armdoc_itmNOPEN: armdoc_itmNOPEN,
                    armdoc_itmDO: armdoc_itmDO,
                    armdoc_itmID: armdoc_itmID,
                    armdoc_itmQT: armdoc_itmQT,
                    armdoc_itmPRC: armdoc_itmPRC,
                    armdoc_TYPE: armdoc_TYPE,
                    apkg_item: apkg_item,
                    apkg_qty: apkg_qty,
                    apkg_netw: apkg_netw,
                    apkg_grossw: apkg_grossw,
                    apkg_measure: apkg_measure,
                    apkg_itmtype: apkg_itmtype,
                    armso_itmID: armso_itmID,
                    armso_itmQT: armso_itmQT,
                    armso_itmCPO: armso_itmCPO,
                    armso_itmCPOLINE: armso_itmCPOLINE,
                    armso_itmPRC: armso_itmPRC,
                    armso_itmLINE: armso_itmLINE,
                    armscr_itmID: armscr_itmID,
                    armscr_itmQT: armscr_itmQT,
                    armscr_itmPRC: armscr_itmPRC,
                    armscr_itmLINE: armscr_itmLINE
                },
                dataType: "json",
                success: function(response) {
                    document.getElementById('retrm_out_btn_save').innerHTML = `<i class="fas fa-save"></i>`
                    document.getElementById('retrm_out_btn_save').disabled = false
                    if (response.status[0].cd === '1') {
                        alertify.success(response.status[0].msg);
                        document.getElementById('retrm_out_inc_txt_DO').value = response.status[0].dono

                        mtbl = document.getElementById('retrm_out_inc_tbl')
                        tableku2 = mtbl.getElementsByTagName("tbody")[0]
                        mtbltr = tableku2.getElementsByTagName('tr')
                        ttlrows = mtbltr.length
                        for (let i = 0; i < ttlrows; i++) {
                            tableku2.rows[i].cells[0].innerText = i
                        }

                        mtbl = document.getElementById('retrm_out_inc_tbl_packing')
                        tableku2 = mtbl.getElementsByTagName("tbody")[0]
                        mtbltr = tableku2.getElementsByTagName('tr')
                        ttlrows = mtbltr.length
                        for (let i = 0; i < ttlrows; i++) {
                            tableku2.rows[i].cells[0].innerText = i
                        }
                        retrm_out_f_getdetail(response.status[0].dono)
                    } else if (response.status[0].cd === '11') {
                        alertify.message(response.status[0].msg)
                        retrm_out_f_getdetail(docno)
                    } else {
                        alertify.warning(response.status[0].msg);
                    }
                },
                error: function(xhr, ajaxOptions, throwError) {
                    alert(throwError);
                    document.getElementById('retrm_out_btn_save').innerHTML = `<i class="fas fa-save"></i>`
                    document.getElementById('retrm_out_btn_save').disabled = false
                }
            })
        }
    }

    function retrm_out_e_finddoc() {
        $("#retrm_out_DTLMOD").modal('show');
    }

    function retrm_out_e_get_added_qty(pitem) {
        let mtbl = document.getElementById('retrm_out_inc_tbl');
        let tableku2 = mtbl.getElementsByTagName("tbody")[0];
        let mtbltr = tableku2.getElementsByTagName('tr');
        let ttlrows = mtbltr.length;
        let added_Qty = 0;
        for (let i = 0; i < ttlrows; i++) {
            if (tableku2.rows[i].cells[0].innerText == pitem) {
                added_Qty += numeral(tableku2.rows[i].cells[2].innerText).value();
            }
        }
        return added_Qty;
    }

    function retrm_out_inc_btn_add_e_click() {
        retrm_out_inc_addrow()
        let mytbody = document.getElementById('retrm_out_inc_tbl').getElementsByTagName('tbody')[0]
        retrm_out_inc_selected_row = mytbody.rows.length - 1
        retrm_out_inc_selected_col = 1
    }

    function retrm_out_inc_minusrow() {
        let mytable = document.getElementById('retrm_out_inc_tbl').getElementsByTagName('tbody')[0]
        const mtr = mytable.getElementsByTagName('tr')[retrm_out_inc_selected_row]
        const mylineid = mtr.getElementsByTagName('td')[0].innerText.trim()
        if (mylineid !== '') {
            if (confirm("Are you sure ?")) {
                const docnum = document.getElementById('retrm_out_inc_txt_DO').value
                $.ajax({
                    type: "post",
                    url: "<?=base_url('DELV/remove_rm')?>",
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
        }
    }

    function retrm_out_inc_minusrow1() {
        let mytable = document.getElementById('retrm_out_inc_tbl_packing').getElementsByTagName('tbody')[0]
        const mtr = mytable.getElementsByTagName('tr')[retrm_out_inc_selected_row1]
        const mylineid = mtr.getElementsByTagName('td')[0].innerText.trim()
        if (mylineid !== '') {
            if (confirm("Are you sure ?")) {
                const docnum = document.getElementById('retrm_out_inc_txt_DO').value
                $.ajax({
                    type: "post",
                    url: "<?=base_url('DELV/remove_pkg')?>",
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
        }
    }

    function retrm_out_txtsearchSUP_eKP(e) {
        if (e.key === 'Enter') {
            const txt = document.getElementById('retrm_out_txtsearchSUP')
            txt.readOnly = true
            document.getElementById('retrm_out_tblsup').getElementsByTagName('tbody')[0].innerHTML = ''
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
                        let mydes = document.getElementById("retrm_out_tblsup_div")
                        let myfrag = document.createDocumentFragment()
                        let mtabel = document.getElementById("retrm_out_tblsup")
                        let cln = mtabel.cloneNode(true);
                        myfrag.appendChild(cln)
                        let tabell = myfrag.getElementById("retrm_out_tblsup")
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
                                $("#retrm_out_SUPPLIER").modal('hide');
                                document.getElementById('retrm_out_inc_custname').value = response.data[i].MSUP_SUPNM
                                document.getElementById('retrm_out_inc_curr').value = response.data[i].MSUP_SUPCR
                                retrm_out_inc_custcd = response.data[i].MSUP_SUPCD
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

    function retrm_out_inc_cmb_bg_e_change() {
        document.getElementById('retrm_out_inc_custname').value = ''
        document.getElementById('retrm_out_inc_curr').value = ''
        $('#retrm_out_tblcus tbody').empty()
        document.getElementById('retrm_out_inc_btnfindmodcust').focus()
    }

    function retrm_out_inc_btnfindmodcust_eC() {
        const cmbdescription = document.getElementById('retrm_out_description')
        const Conditions = ['DIKEMBALIKAN','DIKEMBALIKAN NG','DISPOSE', 'DIPERBAIKI']
        if (Conditions.includes(cmbdescription.value)) {
            $("#retrm_out_SUPPLIER").modal('show')
        } else {
            $("#retrm_out_MODCUS").modal('show')
        }
    }

    $("#retrm_out_MODCUS").on('shown.bs.modal', function() {
        $("#retrm_out_txtsearchcus").focus();
    })

    function retrm_out_txtsearchcus_eKP(e) {
        if (e.key === 'Enter') {
            const mkey = document.getElementById('retrm_out_txtsearchcus').value
            const msearchby = document.getElementById("retrm_out_srchby").value
            const mbg = document.getElementById('retrm_out_inc_cmb_bg').value
            if (mbg === "-") {
                alertify.message('Please select business group first')
                return
            }
            $('#retrm_out_tblcus tbody').empty()
            $.ajax({
                type: "get",
                url: "<?=base_url('DELV/searchcustomer_si')?>",
                data: {
                    cid: mkey,
                    csrchby: msearchby,
                    cbg: mbg
                },
                dataType: "json",
                success: function(response) {
                    if (response.status[0].cd != '0') {
                        const ttlrows = response.data.length
                        let mydes = document.getElementById("retrm_out_tblcus_div")
                        let myfrag = document.createDocumentFragment()
                        let mtabel = document.getElementById("retrm_out_tblcus")
                        let cln = mtabel.cloneNode(true)
                        myfrag.appendChild(cln)
                        let tabell = myfrag.getElementById("retrm_out_tblcus")
                        let tableku2 = tabell.getElementsByTagName("tbody")[0]
                        let newrow, newcell, newText;
                        tableku2.innerHTML = ''
                        for (let i = 0; i < ttlrows; i++) {
                            newrow = tableku2.insertRow(-1)
                            newrow.onclick = () => {
                                retrm_out_inc_custcd = response.data[i].MCUS_CUSCD.trim()
                                document.getElementById('retrm_out_inc_custname').value = response.data[i].MCUS_CUSNM
                                document.getElementById('retrm_out_inc_curr').value = response.data[i].MCUS_CURCD
                                $("#retrm_out_MODCUS").modal('hide')
                            }
                            newcell = newrow.insertCell(0)
                            newcell.innerHTML = response.data[i].MCUS_CUSCD.trim()
                            newcell = newrow.insertCell(1)
                            newcell.innerHTML = response.data[i].MCUS_CURCD
                            newcell = newrow.insertCell(2)
                            newcell.innerHTML = response.data[i].MCUS_CUSNM
                            newcell = newrow.insertCell(3)
                            newcell.innerHTML = response.data[i].MCUS_ADDR1
                        }
                        mydes.innerHTML = ''
                        mydes.appendChild(myfrag)
                    } else {
                        alertify.message(response.status[0].msg);
                    }
                }
            });
        }
    }

    function retrm_out_btn_print_eC() {
        // document.getElementById('retrm_out_div_infoAfterPost').innerHTML = `<div class="alert alert-info alert-dismissible fade show" role="alert">
        //     <strong><i class="fas fa-info"></i></strong> Total time consume
        //     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        // </div>`
    }

    function retrm_out_btn_appr_eC() {
        const mtxid = document.getElementById('retrm_out_inc_txt_DO')
        if (mtxid.value.trim().length == 0) {
            mtxid.focus()
            return
        }
        const minvno = document.getElementById('retrm_out_txt_invno')
        const wh = document.getElementById('retrm_out_cmb_locfrom').value
        if (minvno.value.trim().length <= 3 && wh != 'PSIEQUIP') {
            alertify.warning('Invoice Number is required')
            minvno.focus()
            return
        }
        if (confirm('Are you sure ?')) {
            $.ajax({
                type: "post",
                url: "<?=base_url('DELV/approve')?>",
                data: {
                    inid: mtxid.value
                },
                dataType: "json",
                success: function(response) {
                    if (response[0].cd == "0") {
                        alertify.warning(response[0].msg)
                    } else {
                        alertify.success(response[0].msg)
                        let appr = document.getElementById("footerinfo_user").innerText
                        appr = appr.substr(3, appr.length)
                        document.getElementById("retrm_out_inc_txt_apprby").value = appr
                        document.getElementById("retrm_out_inc_txt_apprtime").value = response[0].approved_time
                    }
                },
                error: function(xhr, xopt, xthrow) {
                    alertify.error(xthrow);
                }
            })
        }
    }

    function retrm_out_btn_print_eCK() {
        let mtxid = document.getElementById('retrm_out_inc_txt_DO').value;
        let mapprovedby = document.getElementById('retrm_out_inc_txt_apprby').value;
        if (mtxid.trim() == '') {
            alertify.warning('Delivery Note could not be empty');
            document.getElementById('retrm_out_inc_txt_DO').focus();
            return;
        }
        if (mapprovedby.trim() == '') {
            alertify.warning('Please approve first !');
            document.getElementById('retrm_out_inc_txt_apprby').focus();
            return;
        }
        $("#retrm_out_MODPRINT").modal('show');
    }

    function retrm_out_btn_book_eC() {
        const txid = document.getElementById('retrm_out_inc_txt_DO')
        const bctype = document.getElementById('retrm_out_cmb_bcdoc')
        if (txid.value.trim().length == 0) {
            txid.focus()
            alertify.warning('TXID is required')
            return
        }
        if (bctype.value.trim() == '-') {
            bctype.focus()
            alertify.warning('Customs Document is required')
            return
        }
        if (!confirm("Are you sure ?")) {
            return
        }
        let mymodal = new bootstrap.Modal(document.getElementById("retrm_out_inc_PROGRESS_BOOK"), {
            backdrop: 'static',
            keyboard: false
        });
        mymodal.show()

    }

    $("#retrm_out_inc_PROGRESS_BOOK").on('shown.bs.modal', function() {
        const txid = document.getElementById('retrm_out_inc_txt_DO')
        const bctype = document.getElementById('retrm_out_cmb_bcdoc')
        if (txid.value.trim().length == 0) {
            txid.focus()
            alertify.warning('TXID is required')
            return
        }
        if (bctype.value.trim() == '-') {
            bctype.focus()
            alertify.warning('Customs Document is required')
            return
        }
        $.ajax({
            type: "GET",
            url: "<?=base_url('DELV/book_rm')?>" + bctype.value,
            data: {
                insj: txid.value.trim()
            },
            dataType: "JSON",
            success: function(response) {
                if (response.status[0].cd == '1') {
                    alertify.success(response.status[0].msg);
                    document.getElementById('retrm_out_div_infoAfterPost').innerHTML = `<div class="alert alert-info alert-dismissible fade show" role="alert">
                            <strong><i class="fas fa-info"></i></strong> Booked
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`
                } else if (response.status[0].cd == '11') {
                    alertify.success(response.status[0].msg);
                    document.getElementById('retrm_out_div_infoAfterPost').innerHTML = `<div class="alert alert-info alert-dismissible fade show" role="alert">
                            <strong><i class="fas fa-info"></i></strong> ${response.status[0].msg}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`
                } else if (response.status[0].cd == '100') {
                    alertify.success(response.status[0].msg);
                    document.getElementById('retrm_out_div_infoAfterPost').innerHTML = `<div class="alert alert-info alert-dismissible fade show" role="alert">
                            <strong><i class="fas fa-info"></i></strong> ${response.status[0].msg}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`
                } else if (response.status[0].cd == '110') {
                    alertify.success(response.status[0].msg);
                    document.getElementById('retrm_out_div_infoAfterPost').innerHTML = `<div class="alert alert-info alert-dismissible fade show" role="alert">
                            <strong><i class="fas fa-info"></i></strong> ${response.status[0].msg}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`
                } else if (response.status[0].cd == '120') {
                    alertify.success(response.status[0].msg);
                    document.getElementById('retrm_out_div_infoAfterPost').innerHTML = `<div class="alert alert-info alert-dismissible fade show" role="alert">
                            <strong><i class="fas fa-info"></i></strong> ${response.status[0].msg}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`
                } else {
                    alertify.success(response.status[0].msg);
                    document.getElementById('retrm_out_div_infoAfterPost').innerHTML = `<div class="alert alert-info alert-dismissible fade show" role="alert">
                            <strong><i class="fas fa-info"></i></strong> ${response.status[0].msg}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`
                }
                $("#retrm_out_inc_PROGRESS_BOOK").modal('hide')
            },
            error: function(xhr, xopt, xthrow) {
                $("#retrm_out_inc_PROGRESS_BOOK").modal('hide')
                alertify.error(xthrow)
            }
        })
    });

    function retrm_out_btnprintseldocs_eCK() {
        let txid = document.getElementById('retrm_out_inc_txt_DO').value;
        let mckdo = (document.getElementById('retrm_out_ckDO').checked) ? '1' : '0';
        let mckinv = (document.getElementById('retrm_out_ckINV').checked) ? '1' : '0';
        let mckpl = (document.getElementById('retrm_out_ckPL').checked) ? '1' : '0';
        const txcurrency = document.getElementById('retrm_out_inc_curr').value
        const txTGLAJU = document.getElementById('retrm_out_inc_customs_date').value
        if ((mckdo + mckinv + mckpl) == '000') {
            alertify.message('Please select document first');
            return;
        }
        if (txid.trim() == '') {
            alertify.warning('Please fill TX ID first');
            $("#retrm_out_MODPRINT").modal('hide');
            document.getElementById('retrm_out_inc_txt_DO').focus();
            return;
        }
        Cookies.set('CKPDLV_NO', txid, {
            expires: 365
        });
        Cookies.set('CKPDLV_FORMS', (mckdo + mckinv + mckpl), {
            expires: 365
        });
        Cookies.set('CKPDLV_CURRENCY', txcurrency, {
            expires: 365
        })
        Cookies.set('CKPDLV_TGLAJU', txTGLAJU, {
            expires: 365
        })
        window.open("<?=base_url('printdeliverydocs_rm')?>", '_blank');
        $("#retrm_out_MODPRINT").modal('hide');
    }

    function retrm_out_inc_btn_add_fromPK_eCK() {
        $("#retrm_out_MODPK").modal('show')
    }

    function retrm_out_inc_btn_add_fromSCR_eCK() {
        $("#retrm_out_MODSCR").modal('show')
    }

    $("#retrm_out_MODPK").on('shown.bs.modal', function() {
        document.getElementById('retrm_out_cpk_txtpk').focus()
    })

    function retrm_out_cpk_btnConfirm_eCK() {
        if (!confirm("Are you sure ?")) {
            return
        }
        let mtbl = document.getElementById('retrm_out_cpk_tblpk')
        let tableku2 = mtbl.getElementsByTagName("tbody")[0]
        let mtbltr = tableku2.getElementsByTagName('tr')
        let ttlrows = mtbltr.length

        let mtbl_ = document.getElementById('retrm_out_inc_tbl')
        let tableku2_ = mtbl_.getElementsByTagName("tbody")[0]
        let aItem = []
        let aItemQTY = []
        let aItem_d = []
        let aItemQTY_d = []
        let aAJU = []
        let aNOPEN = []
        let aDONUM = []
        let aDODT = []
        let aPrice = []
        let aBCTYPE = []
        let bctype = ''
        let wh = ''
        for (let i = 0; i < ttlrows; i++) {
            let balqty = numeral(tableku2.rows[i].cells[2].innerText).value()
            let actqty = numeral(tableku2.rows[i].cells[3].innerText).value()
            if (actqty > 0) {
                if (balqty < actqty) {
                    tableku2.rows[i].cells[3].focus()
                    alertify.warning(`ReturnQTY > BalanceQTY !`)
                    return
                }
                let isfound = false
                for (let s = 0; s < aItem.length; s++) {
                    if (aItem[s] == tableku2.rows[i].cells[0].innerText) {
                        aItemQTY[s] += actqty
                        isfound = true
                        break
                    }
                }
                if (!isfound) {
                    aItem.push(tableku2.rows[i].cells[0].innerText)
                    aItemQTY.push(actqty)
                }
                aItem_d.push(tableku2.rows[i].cells[0].innerText)
                aItemQTY_d.push(actqty)
                aAJU.push(tableku2.rows[i].cells[4].innerText)
                aNOPEN.push(tableku2.rows[i].cells[5].innerText)
                aDONUM.push(tableku2.rows[i].cells[6].innerText)
                aDODT.push(tableku2.rows[i].cells[7].innerText)
                aPrice.push(tableku2.rows[i].cells[8].innerText)
                aBCTYPE.push(tableku2.rows[i].cells[9].innerText)
                bctype = tableku2.rows[i].cells[9].innerText == '40' ? '41' : tableku2.rows[i].cells[9].innerText
                wh = tableku2.rows[i].cells[10].innerText
            }
        }
        document.getElementById('retrm_out_cmb_bcdoc').value = bctype
        document.getElementById('retrm_out_cmb_locfrom').value = wh
        let pknum = document.getElementById('retrm_out_cpk_txtpk').value
        if (bctype == '41') {
            document.getElementById('retrm_out_inc_txt_nokontrak41').value = pknum
        }
        let newrow, newcell
        let ttlrows_resume = aItem.length
        for (let i = 0; i < ttlrows_resume; i++) {
            newrow = tableku2_.insertRow(-1)
            newcell = newrow.insertCell(0)
            newcell.classList.add('d-none')
            newcell = newrow.insertCell(1)
            newcell.innerHTML = aItem[i]
            newcell = newrow.insertCell(2)
            newcell.classList.add('text-end')
            newcell.innerHTML = aItemQTY[i]
            newcell = newrow.insertCell(3)
            newcell.contentEditable = true
            newcell = newrow.insertCell(4)
            newcell.contentEditable = true
            newcell = newrow.insertCell(5)
            newcell.contentEditable = true
        }
        mtbl_ = document.getElementById('retrm_out_donprc_tbl')
        tableku2_ = mtbl_.getElementsByTagName("tbody")[0]

        let mydes = document.getElementById("retrm_out_donprc_div");
        let myfrag = document.createDocumentFragment();
        let mtabel = document.getElementById("retrm_out_donprc_tbl");
        let cln = mtabel.cloneNode(true);
        myfrag.appendChild(cln);
        tabell = myfrag.getElementById("retrm_out_donprc_tbl");
        tableku2 = tabell.getElementsByTagName("tbody")[0];

        tableku2.innerHTML = ''
        ttlrows = aItem_d.length
        for (let i = 0; i < ttlrows; i++) {
            newrow = tableku2.insertRow(-1)
            newcell = newrow.insertCell(0)
            newrow.onclick = (event) => {
                retrm_out_doprc_tbody_tr_eC(event)
            }
            newcell.classList.add('d-none')
            newcell = newrow.insertCell(1)
            newcell.classList.add('d-none')
            newcell.innerHTML = aAJU[i]
            newcell = newrow.insertCell(2)
            newcell.innerHTML = aNOPEN[i]
            newcell = newrow.insertCell(3)
            newcell.innerHTML = aDONUM[i]
            newcell = newrow.insertCell(4)
            newcell.innerHTML = aDODT[i]
            newcell = newrow.insertCell(5)
            newcell.innerHTML = aItem_d[i]
            newcell = newrow.insertCell(6)
            newcell.classList.add('text-end')
            newcell.innerHTML = aItemQTY_d[i]
            newcell = newrow.insertCell(7)
            newcell.classList.add('text-end')
            newcell.innerHTML = aPrice[i]
            newcell = newrow.insertCell(8)
            newcell.classList.add('text-end')
            newcell.innerHTML = aBCTYPE[i]
            newcell = newrow.insertCell(9)
            newcell.onclick = function(event) {
                if (event.srcElement.tagName === 'SPAN') {
                    retrm_out_inc_selected_row = event.srcElement.parentElement.parentElement.rowIndex
                } else {
                    retrm_out_inc_selected_row = event.srcElement.parentElement.rowIndex
                }
                retrm_out_doprc_remrow()
            }
            newcell.style.cssText = 'cursor:pointer'
            newcell.classList.add('text-center')
            newcell.innerHTML = `<span class="fas fa-trash text-danger"></span>`
            newcell = newrow.insertCell(10)
        }
        mydes.innerHTML = ''
        mydes.appendChild(myfrag)

        $("#retrm_out_MODPK").modal('hide')
    }

    function retrm_out_btn_tostx_xls() {
        const txid = document.getElementById('retrm_out_inc_txt_DO').value.trim()
        if (txid.length == 0) {
            alertify.message('TXID is required')
            return
        }
        Cookies.set('CKPDLV_NO', txid, {
            expires: 365
        })
        Cookies.set('CKPDLV_CURRENCY', retrm_out_inc_curr.value, {
            expires: 365
        })
        Cookies.set('CKPDLV_TGLAJU', retrm_out_inc_customs_date.value, {
            expires: 365
        })
        window.open("<?=base_url('delivery_doc_rm_as_xls')?>", '_blank')
    }

    function retrm_out_cpk_txtpk_eKP(e) {
        if (e.key == 'Enter') {
            let pkno = document.getElementById('retrm_out_cpk_txtpk')
            pkno.readOnly = true
            $.ajax({
                type: "GET",
                url: "<?=base_url('RCV/balancePK')?>",
                data: {
                    pkno: pkno.value
                },
                dataType: "json",
                success: function(response) {
                    pkno.readOnly = false
                    const ttlrows = response.data.length
                    let mydes = document.getElementById("retrm_out_cpk_tblpk_div");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("retrm_out_cpk_tblpk");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("retrm_out_cpk_tblpk");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    let newrow, newcell, newText;
                    tableku2.innerHTML = ''
                    for (let i = 0; i < ttlrows; i++) {
                        newrow = tableku2.insertRow(-1)
                        newcell = newrow.insertCell(0)
                        newcell.classList.add('table-secondary')
                        newcell.innerHTML = response.data[i].ITMNUM
                        newcell = newrow.insertCell(1)
                        newcell.classList.add('table-secondary')
                        newcell.innerHTML = response.data[i].MITM_ITMD1
                        newcell = newrow.insertCell(2)
                        newcell.classList.add('text-end', 'table-secondary')
                        newcell.innerHTML = numeral(response.data[i].STK).format(',')
                        newcell = newrow.insertCell(3)
                        newcell.contentEditable = true
                        newcell.classList.add('text-end')
                        newcell.innerHTML = numeral(response.data[i].STK).format(',')
                        newcell = newrow.insertCell(4)
                        newcell.classList.add('d-none')
                        newcell.innerHTML = response.data[i].RPSTOCK_NOAJU
                        newcell = newrow.insertCell(5)
                        newcell.classList.add('d-none')
                        newcell.innerHTML = response.data[i].RPSTOCK_BCNUM
                        newcell = newrow.insertCell(6)
                        newcell.classList.add('d-none')
                        newcell.innerHTML = response.data[i].RPSTOCK_DOC
                        newcell = newrow.insertCell(7)
                        newcell.classList.add('d-none')
                        newcell.innerHTML = response.data[i].RCV_BCDATE
                        newcell = newrow.insertCell(8)
                        newcell.classList.add('d-none')
                        newcell.innerHTML = response.data[i].PRICE
                        newcell = newrow.insertCell(9)
                        newcell.classList.add('d-none')
                        newcell.innerHTML = response.data[i].RCV_BCTYPE
                        newcell = newrow.insertCell(10)
                        newcell.classList.add('d-none')
                        newcell.innerHTML = response.data[i].RCV_WH
                    }
                    mydes.innerHTML = ''
                    mydes.appendChild(myfrag)
                },
                error: function(xhr, xopt, xthrow) {
                    pkno.readOnly = false
                    alertify.error(xthrow)
                }
            })
        }
    }

    function retrm_out_cscr_txtsearch_eKP(e) {
        if (e.key === 'Enter') {
            e.target.readOnly = true
            const date0 = document.getElementById('retrm_out_scr_period_search').value
            $.ajax({
                type: "GET",
                url: "<?=base_url('ITH/scrap_balance')?>",
                data: {
                    search: e.target.value,
                    date0: date0
                },
                dataType: "json",
                success: function(response) {
                    e.target.readOnly = false
                    const ttlrows = response.data.length
                    let mydes = document.getElementById("retrm_out_cscr_tbl_div")
                    let myfrag = document.createDocumentFragment()
                    let mtabel = document.getElementById("retrm_out_cscr_tbl")
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln)
                    let tabell = myfrag.getElementById("retrm_out_cscr_tbl")
                    let tableku2 = tabell.getElementsByTagName("tbody")[0]
                    let newrow, newcell, newText
                    let myitmttl = 0;
                    tableku2.innerHTML = ''
                    let ttlamount = 0
                    for (let i = 0; i < ttlrows; i++) {
                        newrow = tableku2.insertRow(-1)
                        newcell = newrow.insertCell(0)
                        newcell.innerHTML = response.data[i].ITH_ITMCD
                        newcell = newrow.insertCell(1)
                        newcell.innerHTML = response.data[i].ITMNM
                        newcell = newrow.insertCell(2)
                        newcell.classList.add('text-end')
                        newcell.innerHTML = numeral(response.data[i].STKQTY).format(',')
                        newcell = newrow.insertCell(3)
                        newcell.contentEditable = true
                        newcell.classList.add('text-end')
                        newcell.innerHTML = numeral(response.data[i].STKQTY).format(',')
                    }
                    mydes.innerHTML = ''
                    mydes.appendChild(myfrag)
                },
                error: function(xhr, xopt, xthrow) {
                    e.target.readOnly = false
                    alertify.error(xthrow)
                }
            })
        }
    }

    function retrm_out_cscr_btnConfirm_eCK() {
        if (!confirm("Are you sure ?")) {
            return
        }
        let mtbl = document.getElementById('retrm_out_cscr_tbl')
        let tableku2 = mtbl.getElementsByTagName("tbody")[0]
        let mtbltr = tableku2.getElementsByTagName('tr')
        let ttlrows = mtbltr.length

        let mtbl_ = document.getElementById('retrm_out_inc_tbl')
        let tableku2_ = mtbl_.getElementsByTagName("tbody")[0]
        let aItem = []
        let aItemQTY = []
        for (let i = 0; i < ttlrows; i++) {

            let balqty = numeral(tableku2.rows[i].cells[2].innerText).value()
            let actqty = numeral(tableku2.rows[i].cells[3].innerText).value()
            if (balqty < actqty) {
                tableku2.rows[i].cells[3].focus()
                alertify.warning(`ReturnQTY > BalanceQTY !`)
                return
            }
            aItem.push(tableku2.rows[i].cells[0].innerText)
            aItemQTY.push(actqty)
        }

        let newrow, newcell
        for (let i = 0; i < ttlrows; i++) {
            newrow = tableku2_.insertRow(-1)
            newcell = newrow.insertCell(0)
            newcell.classList.add('d-none')
            newcell = newrow.insertCell(1)
            newcell.innerHTML = aItem[i]
            newcell = newrow.insertCell(2)
            newcell.classList.add('text-end')
            newcell.innerHTML = aItemQTY[i]
            newcell = newrow.insertCell(3)
            newcell.contentEditable = true
            newcell = newrow.insertCell(4)
            newcell.contentEditable = true
            newcell = newrow.insertCell(5)
            newcell.contentEditable = true
        }

        $("#retrm_out_MODSCR").modal('hide')
    }

    function retrm_out_scr_tbl_add_eCK() {
        $("#retrm_out_MODSCRDOC").modal('show')
    }

    function retrm_out_scrdoc_txtsearch_eKP(e) {
        if (e.key === 'Enter') {
            e.target.readOnly = true
            document.getElementById('retrm_out_scrdoc_tbl').getElementsByTagName('tbody')[0].innerHTML = '<tr><td colspan="1" class="text-center"><i>Please wait...</i></td></tr>'
            $.ajax({
                type: "GET",
                url: "<?=base_url('SCR/scrapreport_search')?>",
                data: {
                    search: e.target.value
                },
                dataType: "json",
                success: function(response) {
                    e.target.readOnly = false
                    const ttlrows = response.data.length
                    let mydes = document.getElementById("retrm_out_scrdoc_tbl_div")
                    let myfrag = document.createDocumentFragment()
                    let mtabel = document.getElementById("retrm_out_scrdoc_tbl")
                    let cln = mtabel.cloneNode(true)
                    myfrag.appendChild(cln)
                    let tabell = myfrag.getElementById("retrm_out_scrdoc_tbl")
                    let tableku2 = tabell.getElementsByTagName("tbody")[0]
                    let newrow, newcell, newText
                    let myitmttl = 0;
                    tableku2.innerHTML = ''

                    let mtbl_p = document.getElementById('retrm_out_scr_tbl')
                    let tableku2_p = mtbl_p.getElementsByTagName("tbody")[0]
                    let mtbltr_p = tableku2_p.getElementsByTagName('tr')
                    let ttlrows_p = mtbltr_p.length

                    for (let i = 0; i < ttlrows; i++) {
                        let shouldContinue = true;
                        for (let p = 0; p < ttlrows_p; p++) {
                            if (tableku2_p.rows[p].cells[1].innerText == response.data[i].ID_TRANS) {
                                shouldContinue = false
                            }
                        }
                        if (shouldContinue) {
                            newrow = tableku2.insertRow(-1)
                            newcell = newrow.insertCell(0)
                            newcell.onclick = () => {
                                $("#retrm_out_MODSCRDOC").modal('hide')
                                retrm_out_scr_addrow({
                                    ID_TRANS: response.data[i].ID_TRANS,
                                })
                                document.getElementById('retrm_out_scrdoc_tbl').getElementsByTagName('tbody')[0].innerHTML = ''
                            }
                            newcell.style.cssText = 'cursor:pointer'
                            newcell.innerHTML = response.data[i].ID_TRANS
                        }
                    }
                    if (ttlrows == 0) {
                        newrow = tableku2.insertRow(-1)
                        newcell = newrow.insertCell(0)
                        newcell.innerHTML = 'not found'
                        newcell.classList.add('text-center')
                    }
                    mydes.innerHTML = ''
                    mydes.appendChild(myfrag)

                },
                error: function(xhr, xopt, xthrow) {
                    e.target.readOnly = false
                    alertify.error(xthrow)
                    document.getElementById('retrm_out_scrdoc_tbl').getElementsByTagName('tbody')[0].innerHTML = ''
                }
            })
        }
    }

    function retrm_out_inc_addrow_scr() {
        let mytbody = document.getElementById('retrm_out_limbah_barang_tbl').getElementsByTagName('tbody')[0]
        let newrow, newcell
        newrow = mytbody.insertRow(-1)

        newcell = newrow.insertCell(0)
        newcell.classList.add('d-none')
        newcell.innerHTML = ''

        newcell = newrow.insertCell(1)
        newcell.contentEditable = true
        newcell.focus()

        newcell = newrow.insertCell(2)
        newcell.contentEditable = true
        newcell.classList.add('text-end')
        newcell.innerHTML = '0'

        newcell = newrow.insertCell(3)
        newcell.classList.add('text-end')
        newcell.contentEditable = true
        newcell.innerHTML = ''

        newcell = newrow.insertCell(4)
        newcell.classList.add('text-center')
        newcell.innerHTML = `<span class="fas fa-trash text-danger"></span>`

    }

    function retrm_out_limbah_barang_tbl_add_eCK() {
        retrm_out_inc_addrow_scr()
    }

    function retrm_out_inc_z_btn_relink_e_click(pThis) {
        pThis.innerHTML = `Please wait`
        pThis.disabled = true
        const txid = retrm_out_inc_txt_DO.value.trim()
        if (txid.length === 0) {
            alertify.warning('TX ID is required')
            return
        }
        $.ajax({
            type: "POST",
            url: "<?=base_url('DELV/relink_it_inventory')?>",
            data: {
                doc: txid
            },
            dataType: "json",
            success: function(response) {
                pThis.innerHTML = `Re-link IT Inventory`
                pThis.disabled = false
                alertify.message(response.status[0].msg)
            },
            error: function(xhr, ajaxOptions, throwError) {
                pThis.innerHTML = `Re-link IT Inventory`
                pThis.disabled = false
                alert(throwError);
            }
        });
    }

    function retrm_out_limbah_barang_tbl_copy_eCK() {
        const source_tbody = retrm_out_inc_tbl.getElementsByTagName('tbody')[0]
        const destin_tbody = retrm_out_limbah_barang_tbl.getElementsByTagName('tbody')[0]
        const source_total = source_tbody.rows.length
        let newrow, newcell
        for (let i = 0; i < source_total; i++) {
            newrow = destin_tbody.insertRow(-1)
            const itemcode = source_tbody.rows[i].cells[1].innerText
            const itemqty = source_tbody.rows[i].cells[2].innerText
            newcell = newrow.insertCell(0)
            newcell.classList.add('d-none')
            newcell.innerHTML = ''

            newcell = newrow.insertCell(1)
            newcell.innerText = itemcode
            newcell.contentEditable = true

            newcell = newrow.insertCell(2)
            newcell.classList.add('text-end')
            newcell.innerText = itemqty
            newcell.contentEditable = true

            newcell = newrow.insertCell(3)
            newcell.classList.add('text-end')
            newcell.contentEditable = true

            newcell = newrow.insertCell(4)
            newcell.classList.add('text-center')
            newcell.innerHTML = `<span class="fas fa-trash text-danger"></span>`
        }
    }

    function retrm_out_btn_to_ceisa40(p){
        const doc = retrm_out_inc_txt_DO.value
        const docType = retrm_out_cmb_bcdoc.value
        if(doc.length<=3)
        {
            alertify.warning('TX ID is required')
            retrm_out_inc_txt_DO.focus()
            return
        }
        p.classList.add('disabled')
        p.innerHTML = 'Please wait'
        const div_alert = document.getElementById('retrm-div-alert')
        div_alert.innerHTML = `<div class="alert alert-info alert-dismissible fade show" role="alert">
                                <i class="fas fa-paper-plane fa-bounce"></i> Please wait
                                            </div>`
        $.ajax({
            type: "POST",
            url: `DELV/postingCEISA40BC${docType}rm`,
            data: {doc: doc},
            dataType: "json",
            success: function (response) {
                p.classList.remove('disabled')
                p.innerHTML = 'CEISA 4.0'
                alertify.message(response.message)
                try {
                    const respon = Object.keys(response)
                    let msg = ''
                    for (const item of respon) {
                        msg += `<p>${response[item]}</p>`
                    }
                    div_alert.innerHTML = `<div class="alert alert-info alert-dismissible fade show" role="alert">
                                            ${msg}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>`
                } catch (ex) {
                    div_alert.innerHTML = `<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                            ${xhr.responseText}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>`
                }
            }, error: function(xhr, ajaxOptions, throwError) {
                p.classList.remove('disabled')
                alert(throwError);
                p.innerHTML = 'CEISA 4.0'
                try {
                    const respon = Object.keys(xhr.responseJSON)
                    let msg = ''
                    for (const item of respon) {
                        msg += `<p>${xhr.responseJSON[item]}</p>`
                    }
                    div_alert.innerHTML = `<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                            ${msg}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>`
                } catch (ex) {
                    div_alert.innerHTML = `<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                            ${xhr.responseText}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>`
                }
            }
        })
    }
</script>