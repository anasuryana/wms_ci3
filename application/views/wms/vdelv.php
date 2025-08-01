<style type="text/css">
    .tagbox-remove {
        display: none;
    }

    .txfg_cell:hover {
        font-weight: 900;
    }

    thead tr.first th,
    thead tr.first td {
        position: sticky;
        top: 0;
    }

    thead tr.second th,
    thead tr.second td {
        position: sticky;
        top: 26px;
    }
</style>
<div style="padding:5px">
    <div class="container-fluid">
        <div class="row">
            <div class="col" id="txfg-div-alert">

            </div>
        </div>
        <div class="row" id="txfg_stack0">
            <div class="col-sm-12 mb-1 pr-1 pl-1">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="col-md-12 order-md-0 p-0">
                            <div class="row ">
                                <div class="col-md-4 mb-1 p-1">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">TX ID</span>
                                        <input type="text" class="form-control" id="txfg_txt_id" required readonly>
                                        <button class="btn btn-outline-primary" type="button" id="txfg_btn_findmod"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-1 p-1">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Customer DO</span>
                                        <input type="text" class="form-control" id="txfg_txt_customerDO" required title="Customer Delivery Order">
                                    </div>
                                </div>
                                <div class="col-md-4 mb-1 p-1 ">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Customs date</span>
                                        <input type="text" class="form-control" id="txfg_txt_custdate" required readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 mb-1 p-1">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">TX Status</span>
                                        <input type="text" class="form-control" id="txfg_status" required readonly>
                                        <span class="input-group-text" id="txfg_lbl_status">
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-1 p-1 ">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text" title="Do Date">DO date</span>
                                        <input type="text" class="form-control" id="txfg_txt_DOdate" required readonly title="Delivery Order Date">
                                    </div>
                                </div>
                                <div class="col-md-3 mb-1 p-1">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Inv No</span>
                                        <input type="text" class="form-control" id="txfg_txt_invno" required title="Invoice Number">
                                    </div>
                                </div>
                                <div class="col-md-3 mb-1 p-1">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">SMT INV.NO</span>
                                        <input type="text" class="form-control" id="txfg_txt_invsmt" required title="SMT Invoice Number">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 mb-1 p-1">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Business Group</span>
                                        <select class="form-select" id="txfg_businessgroup" onchange="txfg_businessgroup_e_onchange()" required>
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
                                <div class="col-md-5 mb-1 p-1">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Customer</span>
                                        <input type="text" class="form-control" id="txfg_custname" required readonly>
                                        <button class="btn btn-outline-primary" id="txfg_btnfindmodcust"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                                <div class="col-md-2 mb-1 p-1">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Currency</span>
                                        <input type="text" readonly class="form-control" id="txfg_curr">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-1 p-1">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Consignee</span>
                                        <select id="txfg_consignee" class="form-select">
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
                                <div class="col-md-6 mb-1 p-1">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Transport</span>
                                        <select class="form-select" id="txfg_txt_transport" required>
                                            <option value="-">-</option>
                                            <?php
$todis = "";
foreach ($lplatno as $r) {
    $todis .= "<option value='" . $r->MSTTRANS_ID . "_" . $r->MSTTRANS_TYPE . "'>$r->MSTTRANS_ID</option>";
}
echo $todis;
?>
                                        </select>
                                        <input type="text" class="form-control" id="txfg_txt_transporttype" required readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-1 p-1">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Remark</span>
                                        <input type="text" class="form-control" id="txfg_txt_remark" maxlength="50">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-1 p-1">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Customs Document</span>
                                        <select class="form-select" id="txfg_cmb_bcdoc">
                                            <option value="-">-</option>
                                            <option value="25">BC 2.5</option>
                                            <option value="27">BC 2.7</option>
                                            <option value="41">BC 4.1</option>
                                        </select>
                                        <button class="btn btn-primary btn-sm" id="txfg_btn_customs"><i class="fas fa-book text-warning"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-1 p-1">
                                    <div class="btn-group btn-group-sm">
                                        <button title="Add new" class="btn btn-outline-primary" type="button" id="txfg_btn_new"><i class="fas fa-file"></i></button>
                                        <button title="Save" class="btn btn-primary" type="button" id="txfg_btn_save"><i class="fas fa-save"></i></button>
                                        <button title="Approve" class="btn btn-success" type="button" id="txfg_btn_appr">Approve</button>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button title="TPB Operations" class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">TPB</button>
                                            <ul class="dropdown-menu" aria-labelledby="txfg_tpb_btn">
                                                <li><a id="txfg_btn_post" class="dropdown-item" href="#"><i class="fas fa-clone"></i> Posting</a></li>
                                                <li><a id="txfg_btn_showExbcList" onclick="txfg_btn_showExbcList_eCK()" class="dropdown-item" href="#"><i class="fas fa-list-alt"></i> EX-BC List</a></li>
                                                <li><a id="txfg_btn_post_cancel" onclick="txfg_btn_post_cancel_eCK()" class="dropdown-item disabled" href="#"><i class="fas fa-ban" style="color: red"></i> Cancel</a></li>
                                            </ul>
                                        </div>
                                        <button title="Print" class="btn btn-outline-primary" type="button" id="txfg_btn_print"><i class="fas fa-print"></i></button>
                                        <div class="btn-group btn-group-sm dropend" role="group">
                                            <button title="Export to ..." class="btn btn-outline-primary dropdown-toggle" type="button" id="txfg_btn_export" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-file-export"></i></button>
                                            <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                <li>
                                                    <h6 class="dropdown-header">External System Support</h6>
                                                </li>
                                                <li><a class="dropdown-item" href="#" onclick="txfg_btn_torfid_e_click()"><i class="fas fa-copy"></i> RFID Clipboard</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="txfg_btn_tocustomscontrol_e_click()"><i class="fas fa-copy"></i> AKB Clipboard</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="txfg_btn_toepro_e_click()">EPRO</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="txfg_btn_toceisa_e_click(this)">CEISA 4.0</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="txfg_btn_to_konversi_bahan_baku_e_click(this)">Konversi Bahan Baku</a></li>
                                                <li>
                                                    <h6 class="dropdown-header">Miscellaneous</h6>
                                                </li>
                                                <li><a class="dropdown-item" href="#" onclick="txfg_btn_tobom_e_click()"><i class="fas fa-file-excel text-success"></i> BOM</a></li>
                                                <li>
                                                    <h6 class="dropdown-header">Internal System Support</h6>
                                                </li>
                                                <li><a class="dropdown-item" href="#" onclick="txfg_btn_tomega_e_click()"><i class="fas fa-file-excel text-success"></i> to MEGA</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="txfg_btn_tostx_xls()"><i class="fas fa-file-excel text-success"></i> Invoice,PL,DO</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="txfg_btn_toomi_xls()"><i class="fas fa-file-excel text-success"></i> OMI</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-1 p-1 text-end">
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-info" id="txfg_btn_rmstatus" title="Raw Material Status" onclick="txfg_btn_rmstatus_eC()"><i class="fas fa-box"></i></button>
                                        <button class="btn btn-outline-info" id="txfg_btn_showprice" onclick="txfg_btn_showprice_e_click()" title="Detail Price"><i class="fas fa-money-check"></i></button>
                                        <button class="btn btn-outline-info" id="txfg_btn_showweight" onclick="txfg_btn_showweight_e_click()" title="Detail Weight"><i class="fas fa-weight"></i></button>
                                        <button class="btn btn-outline-info" id="txfg_btn_showinfo" onclick="txfg_btn_showinfo_e_click()" title="User's log"><i class="fas fa-info-circle"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-7 mb-1">
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-primary" id="txfg_btn_addsi">Add from SI</button>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button class="btn btn-outline-primary dropdown-toggle" id="txfg_btn_SO" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Sales Order</button>
                                            <ul class="dropdown-menu" aria-labelledby="txfg_tpb_btn">
                                                <li><a class="dropdown-item" href="#" onclick="txfg_btn_changeprice_e_click()"><i class="fas fa-money-check text-success"></i> Change price</a></li>
                                                <li><a id="txfg_btn_SO" onclick="txfg_btn_SO_eCK()" class="dropdown-item" href="#">MEGA</a></li>
                                                <li><a id="txfg_btn_SOOther" onclick="txfg_btn_SOOther_eCK()" class="dropdown-item" href="#">Non-MEGA</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5 mb-0 text-end">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12  mb-1 pr-1 pl-1">
                <div class="table-responsive" id="txfg_divku">
                    <table id="txfg_tbltx" class="table table-hover table-sm table-bordered" style="width:100%;font-size:81%">
                        <thead class="table-light">
                            <tr class="first">
                                <th colspan="4" class="text-end">Resume</th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th class="text-end"><span id="txfg_gt_rm" title="count of components" class="badge bg-info"></span></th>
                            </tr>
                            <tr class="second">
                                <th>Box Type</th>
                                <th>SO Date</th>
                                <th>Item Code</th>
                                <th>Item Name</th>
                                <th class="text-end">Delivered QTY</th>
                                <th class="text-end">Box</th>
                                <th class="text-end">@Box</th>
                                <th class="text-end">Total RM</th>
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

<div class="modal fade" id="TXFG_MODCUS">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Customer List</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div class="input-group input-group-sm mb-1">
                            <span class="input-group-text">Search</span>
                            <input type="text" class="form-control" id="txfg_txtsearchcus" maxlength="15" required placeholder="...">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <span class="input-group-text">Search by</span>
                            <select id="txfg_srchby" class="form-select">
                                <option value="nm">Name</option>
                                <option value="cd">Code</option>
                                <option value="ab">Abbr Name</option>
                                <option value="ad">Address</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="table-responsive">
                            <table id="txfg_tblcus" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer">
                                <thead class="table-light">
                                    <tr>
                                        <th>Customer Code</th>
                                        <th>Currency</th>
                                        <th>Name</th>
                                        <th>Abbr Name</th>
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

<div class="modal fade" id="TXFG_CUSTOMSMOD">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title text-info">Dokumen BC 2.7</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                 <!-- offCanvas -->
                <div class="offcanvas offcanvas-end" tabindex="-1" id="TXFGsploffcanvasBottom" >
                    <div class="offcanvas-header bg-info shadow">
                        <h5 class="offcanvas-title text-body-secondary" id="offcanvasBottomLabel">CEISA 4.0 Responses</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body small">
                        <div class="container-fluid" id="TXFGsploffcanvasBottomContainer">
                            
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Nomor Pengajuan</label>
                            <input type="text" id="txfg_txt_noaju" class="form-control" maxlength="6">
                        </div>
                    </div>
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">SPPB Document</label>
                            <input type="text" id="txfg_txt_sppb27" class="form-control">
                            <button class="btn btn-outline-primary" onclick="TXFGOffcanvas27.show()" title="not correct value ?"><i class="fas fa-circle-question"></i></button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Nomor Pendaftaran</label>
                            <input type="text" id="txfg_txt_nopen" class="form-control" maxlength="6">
                            <button class="btn btn-primary" id="txfg_btn_sync_pendaftaran" onclick="txfg_btn_sync_pendaftaran_e_click(this)" title="Get Nomor & Tanggal Pendaftaran from CEISA"><i class="fas fa-sync"></i></button>
                        </div>
                    </div>
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Tanggal Pendaftaran</label>
                            <input type="text" id="txfg_txt_tglpen" class="form-control" readonly >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Kantor Asal</label>
                            <select class="form-select" id="txfg_fromoffice">
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
                            <select class="form-select" id="txfg_destoffice">
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
                            <select class="form-select" id="txfg_cmb_jenisTPB" disabled>
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
                            <select class="form-select" id="txfg_cmb_jenisTPBtujuan" disabled>
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
                            <select class="form-select" id="txfg_cmb_tujuanpengiriman">
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
                            <input type="text" id="txfg_txt_nokontrak" class="form-control" maxlength="50">
                        </div>
                    </div>
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Contract Date</label>
                            <input type="text" id="txfg_txt_tglkontrak" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Pemberitahu</label>
                            <input type="text" id="txfg_txt_pemberitahu27" class="form-control" maxlength="50" value="GUSTI AYU KETUT Y">
                        </div>
                    </div>
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Jabatan</label>
                            <input type="text" id="txfg_txt_jabatan27" class="form-control" maxlength="50" value="J.SUPERVISOR">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col">
                            <button type="button" class="btn btn-primary btn-sm" id="txfg_z_btn_relink" onclick="txfg_z_btn_relink_e_click(this)">Re-link IT Inventory</button>
                        </div>
                        <div class="col text-end">
                            <button type="button" class="btn btn-primary btn-sm" id="txfg_z_btn_save" onclick="txfg_z_btn_save_e_click(this)">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="TXFG_CUSTOMSMOD41">
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
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Nomor Pengajuan</label>
                            <input type="text" id="txfg_txt_noaju41" class="form-control" maxlength="6">
                        </div>
                    </div>
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">SPPB Document</label>
                            <input type="text" id="txfg_txt_sppb41" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Nomor Pendaftaran</label>
                            <input type="text" id="txfg_txt_nopen41" class="form-control" maxlength="6" readonly>
                            <button class="btn btn-primary" id="txfg_btn_sync_pendaftaran41" onclick="txfg_btn_sync_pendaftaran41_e_click(this)" title="Get Nomor & Tanggal Pendaftaran from CEISA"><i class="fas fa-sync"></i></button>
                        </div>
                    </div>
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Tanggal Pendaftaran</label>
                            <input type="text" id="txfg_txt_tglpen41" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Kantor Pabean</label>
                            <select class="form-select" id="txfg_fromoffice41">
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
                            <select class="form-select" id="txfg_cmb_jenisTPB41">
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
                            <select class="form-select" id="txfg_cmb_tujuanpengiriman41">
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
                            <input type="text" id="txfg_txt_nokontrak41" class="form-control" list="txfg_txt_nokontrak41_dl">
                            <datalist id="txfg_txt_nokontrak41_dl">
                            </datalist>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Pemberitahu</label>
                            <input type="text" id="txfg_txt_pemberitahu41" class="form-control" maxlength="50" value="GUSTI AYU KETUT Y">
                        </div>
                    </div>
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Jabatan</label>
                            <input type="text" id="txfg_txt_jabatan41" class="form-control" maxlength="50" value="J.SUPERVISOR">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-sm" id="txfg_z_btn_save41" onclick="txfg_z_btn_save41_e_click()">Save changes</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="TXFG_CUSTOMSMOD25">
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
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Nomor Pengajuan</label>
                            <input type="text" id="txfg_txt_noaju25" class="form-control" maxlength="6">
                        </div>
                    </div>
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">SPPB Document</label>
                            <input type="text" id="txfg_txt_sppb25" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Nomor Pendaftaran</label>
                            <input type="text" id="txfg_txt_nopen25" class="form-control" maxlength="6">
                            <button class="btn btn-primary" id="txfg_btn_sync_pendaftaran25" onclick="txfg_btn_sync_pendaftaran25_e_click(this)" title="Get Nomor & Tanggal Pendaftaran from CEISA"><i class="fas fa-sync"></i></button>
                        </div>
                    </div>
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Tanggal Pendaftaran</label>
                            <input type="text" id="txfg_txt_tglpen25" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Kantor Pabean</label>
                            <select class="form-select" id="txfg_fromoffice25">
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
                            <select class="form-select" id="txfg_cmb_jenisTPB25">
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
                            <select class="form-select" id="txfg_cmb_tujuanpengiriman25">
                                <option value="-">-</option>
                                <option value="1">PENYERAHAN BKP</option>
                                <option value="2">PENYERAHAN JKP</option>
                                <option value="3">RETUR</option>
                                <option value="4">NON PENYERAHAN</option>
                                <option value="5">LAINNYA</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Surat Keterangan Bebas (SKB) PPh</label>
                            <input type="text" id="txfg_txt_noskb" class="form-control" maxlength="100">
                        </div>
                    </div>
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Tanggal SKB</label>
                            <input type="text" id="txfg_txt_noskb_tgl" class="form-control" maxlength="10" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Jenis Saran Pengangkut</label>
                            <select class="form-select" id="txfg_jenis_saranapengangkut25">
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
                            <input type="text" id="txfg_txt_pemberitahu25" class="form-control" maxlength="50" value="GUSTI AYU KETUT Y">
                        </div>
                    </div>
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">Jabatan</label>
                            <input type="text" id="txfg_txt_jabatan25" class="form-control" maxlength="50" value="J.SUPERVISOR">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-sm" id="txfg_z_btn_save25" onclick="txfg_z_btn_save25_e_click()">Save changes</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="TXFG_MODSI">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">SI List</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="offcanvas offcanvas-start" tabindex="-1" id="txfg_oc_info_si">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvasExampleLabel">Information</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul>
                            <li>Make sure scanned label is already saved by Handy Terminal or Web</li>
                            <li>Make sure item price is available</li>
                        </ul>                        
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="input-group input-group-sm mb-1">
                            <span class="input-group-text">Search by</span>
                            <select id="txfg_sisrchby" class="form-select" onchange="document.getElementById('txfg_sitxtsearch').focus()">
                                <option value="si">SI</option>
                                <option value="cd">Item Code</option>
                                <option value="nm">Item Name</option>
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="input-group input-group-sm mb-1">
                            <span class="input-group-text">Search</span>
                            <input type="text" class="form-control" id="txfg_sitxtsearch" maxlength="15" required placeholder="...">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="input-group input-group-sm mb-1">
                            <span class="input-group-text">Str.Loc</span>
                            <input type="text" class="form-control" id="txfg_strloc" onkeypress="txfg_strloc_e_keypress(event)">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-1">
                        <button class="btn btn-sm btn-primary btn-block" id="txfg_sibtngetselected">Get selected rows</button>
                    </div>
                    <div class="col mb-1 text-end">
                        <a href="#txfg_oc_info_si" class="link-underline-info" data-bs-toggle="offcanvas">Some item missing ?</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <span class="badge bg-info" id="txfg_lbl_wait_si"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="table-responsive" id="txfg_sidivku">
                            <table id="txfg_sitbl" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer;font-size:81%">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center"><input type="checkbox" id="txfg_sickall"></th>
                                        <th>SI No</th>
                                        <th>Box Type</th><!-- used to be SO/Kanban -->
                                        <th>Item Code</th>
                                        <th>Item Name</th>
                                        <th class="text-end">@ Box</th>
                                        <th class="text-center">ID</th>
                                        <th class="d-none">Model</th>
                                        <th class="d-none">Doc date</th>
                                        <th class="text-end">Price</th>
                                        <th>Job</th>
                                        <th>Str.Loc</th>
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
<div class="modal fade" id="TXFG_MODSAVED">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
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
                            <select id="txfg_txsrchby" class="form-select" onchange="document.getElementById('txfg_txtxtsearch').focus()">
                                <option value="tx">TX ID</option>
                                <option value="txdate">TX Date</option>
                                <option value="cusnm">Customer</option>
                            </select>
                            <input type="text" class="form-control" id="txfg_txtxtsearch" maxlength="25" required placeholder="...">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-1">
                        <div class="input-group input-group-sm mb-1">
                            <label class="input-group-text">
                                <input type="checkbox" class="form-check-input" checked id="txfg_ckperiod" onclick="document.getElementById('txfg_txtxtsearch').focus()">
                            </label>
                            <select id="txfg_monthfilter" class="form-select" onchange="document.getElementById('txfg_txtxtsearch').focus()">
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
                            <input type="number" class="form-control" id="txfg_year" maxlength="4">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <span class="badge bg-info" id="txfg_lbl_wait_saved_tx"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="table-responsive" id="txfg_txdivku">
                            <table id="txfg_txtbl" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer;font-size:81%">
                                <thead class="table-light">
                                    <tr>
                                        <th>TX ID</th><!-- 0 -->
                                        <th>TX Date</th><!-- 1 -->
                                        <th class="d-none">Description</th><!-- 2 -->
                                        <th class="d-none">Customer ID</th><!-- 3 -->
                                        <th class="d-none">Customer Name</th><!-- 4 -->
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
                                        <th>BC Type</th><!-- 25 -->
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
<div class="modal fade" id="TXFG_MODPRINT">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Print</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-1">
                        <div class="card">
                            <div class="card-header">
                                Document Type
                            </div>
                            <div class="card-body">
                                <ul class="list-group text-center">
                                    <li class="list-group-item">
                                        <input class="form-check-input" type="checkbox" value="1" id="txfg_ckDO">
                                        <label class="form-check-label" for="txfg_ckDO">Delivery Order</label>
                                    </li>
                                    <li class="list-group-item">
                                        <input class="form-check-input" type="checkbox" value="1" id="txfg_ckINV">
                                        <label class="form-check-label" for="txfg_ckINV">Invoice</label>
                                    </li>
                                    <li class="list-group-item">
                                        <input class="form-check-input" type="checkbox" value="1" id="txfg_ckPL">
                                        <label class="form-check-label" for="txfg_ckPL">Packing List</label>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col mb-1">
                        <div class="card">
                            <div class="card-header">
                                Additional Setting
                            </div>
                            <div class="card-body">
                                <ul class="list-group text-center">
                                    <li class="list-group-item">
                                        <input class="form-check-input" type="checkbox" value="1" id="txfg_ckBarcode">
                                        <label class="form-check-label" for="txfg_ckBarcode">Show TX ID Barcode</label>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col mb-1 text-center">
                    <button class="btn btn-sm btn-primary" title="Print" id="txfg_btnprintseldocs"><i class="fas fa-print"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="TXFG_MODRM">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title" id="txfg_label_rm_txid"></h4>
                <button type="button" class="btn-close" onclick="txfg_btn_close_modrm_eCK()"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-1" id="txfg_divalertrm">

                    </div>
                </div>
                <div class="row">
                    <div class="col mb-1">
                        <div class="table-responsive" id="txfg_divkurm">
                            <table id="txfg_tbltxrm" class="table table-hover table-sm table-bordered table-warning" style="width:100%;font-size:81%">
                                <thead class="table-light">
                                    <th>Job Number</th>
                                    <th>Item Code</th>
                                    <th>Item Name</th>
                                    <th>ID</th>
                                    <th class="text-end">Dlv. Qty</th>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="txfg_btn_recalculate" onclick="txfg_btn_recalculate_eCK()">Recalculate</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="TXFG_PROGRESS">
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
                        <h3> <span id="txfg_span_timer">00:00:00</span> </h3>
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
<div class="modal fade" id="TXFG_PROGRESS_CNCL">
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

<div class="modal fade" id="TXFG_DETAILSER">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title"><i class="fas fa-info text-info"></i></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">


            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="TXFG_MODINFO">
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
                            <input type="text" id="txfg_txt_createdby" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-1 p-1">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Created Time</span>
                            <input type="text" id="txfg_txt_createdtime" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-1 p-1">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text" title="Last Update by">LU by</span>
                            <input type="text" id="txfg_txt_luby" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-1 p-1">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text" title="Last Update Time">LU Time</span>
                            <input type="text" id="txfg_txt_lutime" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-1 p-1">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Approved by</span>
                            <input type="text" id="txfg_txt_apprby" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-1 p-1">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Approved Time</span>
                            <input type="text" id="txfg_txt_apprtime" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-1 p-1">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Posted by</span>
                            <input type="text" id="txfg_txt_postedby" class="form-control" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-1 p-1">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Posted Time</span>
                            <input type="text" id="txfg_txt_postedtime" class="form-control" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="TXFG_MODINFO_PRICE">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Detail Price</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row" id="txfg_div_price" style="display:none">
                    <div class="col-md-12 mb-1 p-1">
                        <div class="alert alert-warning" role="alert" id="txfg_alert_price">

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-1 p-1">
                        <span class="badge bg-info" id="txfg_lblinfo_price"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-1 p-1">
                        <div class="table-responsive" id="txfg_divinfo_price">
                            <table id="txfg_tblinfo_price" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer;font-size:91%">
                                <thead class="table-light">
                                    <tr>
                                        <th>Item Code</th>
                                        <th class="text-end">Scan QTY</th>
                                        <th class="text-end">Price</th>
                                        <th class="text-end">Total Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td class="text-end font-weight-bold">Grand Total</td>
                                        <td class="text-end font-weight-bold"><span id="txfg_tblinfo_price_footer_qty"></span></td>
                                        <td></td>
                                        <td class="text-end font-weight-bold"><span id="txfg_tblinfo_price_footer_price"></span></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="TXFG_MODINFO_WEIGTH">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Detail Weight</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-1 p-1">
                        <div class="table-responsive" id="txfg_divinfo_weight">
                            <table id="txfg_tblinfo_weight" class="table table-sm table-striped table-bordered table-hover" style="width:100%;font-size:91%">
                                <thead class="table-light">
                                    <tr>
                                        <th>Item Code</th>
                                        <th class="text-end">QTY</th>
                                        <th class="text-end">Net Weight</th>
                                        <th class="text-end">Gross Weight</th>
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
<div class="modal fade" id="TXFG_MODCHANGEPRICE">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Price Changes : <span id="txfg_modchangeprice_txid"></span></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12 mb-1">
                            <h3><span class="badge bg-info">1 <i class="fas fa-hand-point-right"></i></span> <span class="badge bg-info">select Item Code</span></h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-1">
                            <div class="input-group input-group-sm mb-1">
                                <span class="input-group-text">Item Code</span>
                                <select class="form-select" id="txfg_modchangeprice_txt_itemcode" onchange="txfg_modchangeprice_txt_itemcode_eChange(event)">
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-1">
                            <h3><span class="badge bg-info">2 <i class="fas fa-hand-point-right"></i></span> <span class="badge bg-info">select old price</span></h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-1">
                            <div class="table-responsive" id="txfg_modchangeprice_tbl_div">
                                <table id="txfg_modchangeprice_tbl" class="table table-sm table-striped table-bordered table-hover" style="width:100%;font-size:91%">
                                    <thead class="table-light">
                                        <tr>
                                            <th>CPO</th>
                                            <th>SO Line</th>
                                            <th class="text-end">Price</th>
                                            <th class="d-none">FLine</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-1">
                            <h3><span class="badge bg-info">3 <i class="fas fa-hand-point-right"></i></span> <span class="badge bg-info">select new price</span></h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-1">
                            <div class="table-responsive" id="txfg_modchangeprice_new_tbl_div">
                                <table id="txfg_modchangeprice_new_tbl" class="table table-sm table-striped table-bordered table-hover" style="width:100%;font-size:91%">
                                    <thead class="table-light">
                                        <tr>
                                            <th>CPO</th>
                                            <th>SO Line</th>
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
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="txfg_modchangeprice_btn_save_eClick(this)">Save changes</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="TXFG_MODRFID">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">RFID Data</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-1 p-1">
                        <div class="table-responsive" id="txfg_divrfid">
                            <table id="txfg_tblrfid" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer;font-size:91%">
                                <thead class="table-light">
                                    <tr>
                                        <th>Item Code</th>
                                        <th>QTY</th>
                                        <th>Remark</th>
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
<div class="modal fade" id="TXFG_MODEXIM">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">EXIM Data</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-1 p-1">
                        <div class="table-responsive">
                            <table id="txfg_tblexim" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer;font-size:91%">
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

<div id="txfg_w_detailser" class="easyui-window" title="Detail of Reff No" data-options="modal:false,closed:true,iconCls:'icon-analyze',collapsible:true,minimizable:false,
    onClose:function(){
        $('#txfg_tbldetailser_rm tbody').empty();
        document.getElementById('txfg_tbldetailser_rm_lblinfo').innerText ='';
        document.getElementById('txfg_tbldetailser_rm_hinfo').innerText ='';
        }" style="width:500px;height:200px;padding:10px;">
    <div style="padding:1px">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text">SO</span>
                        <input type="text" class="form-control" id="txfg_detser_so" readonly>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text">Assy Code</span>
                        <input type="text" class="form-control" id="txfg_detser_itmcd" readonly>
                    </div>
                </div>
                <div class="col-md-4 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text">Item Name</span>
                        <input type="text" class="form-control" id="txfg_detser_itmnm" readonly>
                    </div>
                </div>
                <div class="col-md-4 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text">QTY</span>
                        <input type="text" class="form-control" id="txfg_detser_itmqty" readonly>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="table-responsive" id="txfg_divdetailser">
                        <table id="txfg_tbldetailser" class="table table-hover table-sm table-bordered" style="width:100%;font-size:91%">
                            <thead class="table-light">
                                <tr class="first">
                                    <th title="click the ID below to view RM usage">ID</th>
                                    <th>Job</th>
                                    <th class="text-end">QTY</th>
                                    <th class="text-center">...</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="table-responsive" id="txfg_divdetailser_rm">
                        <table id="txfg_tbldetailser_rm" class="table table-hover table-sm table-bordered" style="width:100%;font-size:81%">
                            <thead class="table-light">
                                <tr>
                                    <th colspan="8">Raw Material Usage per ID <span id="txfg_tbldetailser_rm_lblinfo" class="badge bg-info"></span> </th>
                                    <th colspan="4" class="text-end"><span id="txfg_tbldetailser_rm_hinfo" class="badge bg-info"></span></th>
                                </tr>
                                <tr>
                                    <th>PSN</th>
                                    <th>Line No</th>
                                    <th>Process</th>
                                    <th>Category</th>
                                    <th class="text-center">F/R</th>
                                    <th class="text-center">MC</th>
                                    <th class="text-center">MCZ</th>
                                    <th class="text-center">PER</th>
                                    <th>Item Code</th>
                                    <th>Item Name</th>
                                    <th class="text-end">QTY</th>
                                    <th class="text-center">Kind</th>
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
<div id="txfg_w_rmnull" class="easyui-window" title="Detail of RM Null" data-options="modal:false,closed:true,iconCls:'icon-tip',collapsible:true, minimizable:false,
    cls:'c7'" style="width:500px;height:200px;padding:5px;">
    <div style="padding:1px">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="alert alert-warning" role="alert" id="txfg_alert_rmnull">

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="table-responsive" id="txfg_divrmnull">
                        <table id="txfg_tblrmnull" class="table table-hover table-sm table-bordered" style="width:100%;font-size:81%">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Assy Code</th>
                                    <th>Job</th>
                                    <th class="text-end">Qty</th>
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
<div id="txfg_w_rmexbcnull" class="easyui-window" title="Warning" data-options="modal:false,closed:true,iconCls:'icon-tip',collapsible:true, minimizable:false,
    cls:'c7'" style="width:500px;height:200px;padding:5px;">
    <div style="padding:1px">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="alert alert-warning" role="alert" id="txfg_alert_rmexbcnull">

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="table-responsive" id="txfg_divrmexbcnull">
                        <table id="txfg_tblrmexbcnull" class="table table-hover table-sm table-bordered" style="width:100%;font-size:81%">
                            <thead class="table-light">
                                <tr>
                                    <th>Item code</th>
                                    <th class="text-end">QTY</th>
                                    <th>Lot Number</th>
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
<div id="txfg_w_rmhscdnull" class="easyui-window" title="Warning" data-options="modal:false,closed:true,iconCls:'icon-tip',collapsible:true, minimizable:false,
    cls:'c7'" style="width:500px;height:200px;padding:5px;">
    <div style="padding:1px">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="alert alert-warning" role="alert" id="txfg_alert_rmhscdnull">

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="table-responsive" id="txfg_div_rmhscd_null">
                        <table id="txfg_tbl_rmhscd_null" class="table table-hover table-sm table-bordered" style="width:100%;font-size:81%">
                            <thead class="table-light">
                                <tr>
                                    <th>Nomor Aju</th>
                                    <th>Surat Jalan</th>
                                    <th>Item Code</th>
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
<div id="txfg_context_menu"></div>
<div class="modal fade" id="TXFG_MODSO">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Sales Order</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-1">
                        <div class="table-responsive" id="txfg_tblso_div">
                            <table id="txfg_tblso" class="table table-hover table-sm table-bordered" style="width:100%;font-size:81%">
                                <thead class="table-light">
                                    <tr>
                                        <th colspan="6" class="text-center">Outstanding SO</th>
                                    </tr>
                                    <tr>
                                        <th rowspan="2" class="align-middle text-center">SO</th>
                                        <th rowspan="2" class="align-middle text-center">Assy Code</th>
                                        <th rowspan="2" class="align-middle text-center">Price</th>
                                        <th colspan="2" class="text-center">Qty</th>
                                        <th rowspan="2">LINE</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">Order</th>
                                        <th class="text-center">Ost</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-1 text-center">
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-primary" id="txfg_btn_fifo_so" onclick="txfg_btn_fifo_so_eCK()">FIFO</button>
                            <button class="btn btn-warning" id="txfg_btn_resetfifo_so" onclick="txfg_btn_resetfifo_so_eCK()">Reset</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="table-responsive" id="txfg_tblso_plot_div">
                            <table id="txfg_tblso_plot" class="table table-sm table-striped table-bordered table-hover" style="width:100%;font-size:75%">
                                <thead class="table-light">
                                    <tr>
                                        <th colspan="6" class="text-center">Plotted SO</th>
                                    </tr>
                                    <tr>
                                        <th>SO</th> <!-- 0-->
                                        <th>LINE</th> <!-- 1-->
                                        <th>Assy Code</th><!-- 2-->
                                        <th class="text-center">Price</th> <!-- 3-->
                                        <th class="text-center">Qty Req</th><!-- 4-->
                                        <th class="text-center">Qty SO Plot</th><!-- 5-->
                                        <th class="d-none">idsi</th> <!-- 6-->
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
<div class="modal fade" id="TXFG_MODSOOTHER">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Sales Order</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-1">
                        <div class="table-responsive" id="txfg_tblsoother_div">
                            <table id="txfg_tblsoother" class="table table-hover table-sm table-bordered" style="width:100%;font-size:89%">
                                <thead class="table-light">
                                    <tr>
                                        <th colspan="5" class="text-center">Outstanding SO</th>
                                    </tr>
                                    <tr>
                                        <th rowspan="2" class="align-middle text-center">SO</th>
                                        <th rowspan="2" class="align-middle text-center">Order Date</th>
                                        <th rowspan="2" class="align-middle text-center">Assy Code</th>
                                        <th colspan="2" class="text-center">Qty</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">Order</th>
                                        <th class="text-center">Ost</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col mb-1 text-center">
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-primary" id="txfg_btn_fifo_so_oth" onclick="txfg_btn_fifo_so_oth_eCK()">FIFO</button>
                            <button class="btn btn-warning" id="txfg_btn_resetfifo_so_oth" onclick="txfg_btn_resetfifo_so_oth_eCK()">Reset</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="table-responsive" id="txfg_tblso_plotother_div">
                            <table id="txfg_tblsoother_plot" class="table table-sm table-striped table-bordered table-hover" style="width:100%;font-size:85%">
                                <thead class="table-light">
                                    <tr>
                                        <th colspan="4" class="text-center">Plotted SO</th>
                                    </tr>
                                    <tr>
                                        <th>SO</th>
                                        <th>Assy Code</th>
                                        <th class="text-end">Qty Req</th>
                                        <th class="text-end">Qty Plot</th>
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
<div class="modal fade" id="TXFG_MODEXBCLIST">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">EXBC List</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-1">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">TX ID</span>
                            <input type="text" class="form-control" id="TXFG_MODEXBCLIST_txid" required readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-1 p-1">
                        <div class="table-responsive" id="txfg_tblEXBCdiv">
                            <table id="txfg_tblEXBC" class="table table-sm table-striped table-bordered table-hover" style="width:100%;font-size:91%">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">TIPE</th>
                                        <th class="text-center">NOMOR PENDAFTARAN</th>
                                        <th class="text-center">TANGGAL PENDAFTARAN</th>
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
<div class="modal fade" id="TXFG_MOD_EXBC_HELPER">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">EXBC Helper <span id="TXFG_MOD_EXBC_HELPER_txid"></span></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3 mb-1">
                        <h3><span class="badge bg-info">1 <i class="fas fa-hand-point-right"></i></span> <span class="badge bg-info">PSN</span> <span class="badge bg-info" style="cursor:pointer" id="TXFG_MOD_EXBC_HELPER_PSN_toggler" onclick="TXFG_MOD_EXBC_HELPER_PSN_toggler_onclick(this)"><span class="fas fa-chevron-right"></span></span></h3>
                    </div>
                </div>
                <div class="row d-none" id="TXFG_MOD_EXBC_HELPER_PSN_Row_container">
                    <div class="col-md-12 mb-1 p-1" >
                        <div class="table-responsive" id="TXFG_MOD_EXBC_HELPER_PSN_Table_div" style="height: 30vh; overflow-y: auto;">
                            <table id="TXFG_MOD_EXBC_HELPER_PSN_Table" class="table table-sm table-striped table-bordered table-hover" style="width:100%;font-size:91%">
                                <thead class="table-light">
                                    <tr>
                                        <th>DOC NO</th>
                                        <th>PSN NO</th>
                                        <th>LINE NO</th>
                                        <th>Process</th>
                                        <th>FR</th>
                                        <th>Category</th>
                                        <th class="text-center">MC</th>
                                        <th class="text-center">MCZ</th>
                                        <th class="text-center">S/M</th>
                                        <th class="text-center">Item Code</th>
                                        <th class="text-center">Item Name</th>
                                        <th class="text-center">Kind</th>
                                        <th class="text-end">REQ QTY</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-1">
                        <h3><span class="badge bg-info">2 <i class="fas fa-hand-point-right"></i></span> <span class="badge bg-info">Decision</span></h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-1 p-1">
                        <h5>Please select alternative part for <strong><span id="TXFG_MOD_EXBC_HELPER_part"></span></strong> : </h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-1 p-1">
                        <div class="table-responsive" id="TXFG_MOD_EXBC_HELPER_Alternative_Table_div">
                            <table id="TXFG_MOD_EXBC_HELPER_Alternative_Table" class="table table-sm table-striped table-bordered table-hover">
                                <thead class="table-light text-center">
                                    <tr>
                                        <th>Item Code</th>
                                        <th>Item Name</th>
                                        <th>Remain EXBC</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-1">
                        <h3><span class="badge bg-info">3 <i class="fas fa-hand-point-right"></i></span> <span class="badge bg-info">Confirmation</span></h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-1 p-1">
                        <h5 id="TXFG_MOD_EXBC_HELPER_Statement"></h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col" id="txfg-mod-exbc-div-alert">

                    </div>
                </div>
            </div>
            <input type="hidden" id="TXFG_MOD_EXBC_HELPER_part_new">
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" disabled id="TXFG_MOD_EXBC_HELPER_Alternative_Button_confirm" onclick="TXFG_MOD_EXBC_HELPER_Alternative_Button_confirm_onclick(this)">I confirm</button>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="TXFG_hid_idx">
<input type="hidden" id="TXFG_hid_idx2">
<script>
    var txfg_context_menu_o = jSuites.contextmenu(txfg_context_menu, {
        items : [
            {
                title : 'Cancel',
                onclick : function() {
                    txfg_e_cancelitem()
                },
                tooltip : 'Method to cancel transaction per row'
            }
        ],
        onclick : function() {
            txfg_context_menu_o.close(false)
        }
    })
    var txfg_starttime = moment().format('HH:mm:ss')
    var scr_txfg_cust = '';
    var txfg_isedit_mode = false;
    var txfg_ttlcek = 0;
    var txfg_ar_item_ser = [];
    var txfg_ar_item_cd = [];
    var txfg_ar_item_nm = [];
    var txfg_ar_item_qty = [];
    var txfg_ar_item_model = [];
    var txfg_ar_item_job = [];
    var txfg_ar_si = [];
    var txfg_ar_so = [];
    var txfg_ar_sodt = [];
    var txfg_ar_cnt_rm = [];
    var txfg_g_string = '';
    var txfg_seconds = 0,
        txfg_minutes = 0,
        txfg_hours = 0;
    var txfg_t;
    var txfg_timerspan = document.getElementById('txfg_span_timer')
    var txfg_isRecalculateFunAlreadytried = false

    function txfg_btn_tostx_xls() {
        const txid = document.getElementById('txfg_txt_id').value.trim()
        if (txid.length == 0) {
            alertify.message('TXID is required')
            return
        }
        Cookies.set('CKPDLV_NO', txid, {
            expires: 365
        })
        window.open("<?=base_url('delivery_doc_as_xls')?>", '_blank');
    }

    function txfg_btn_flagOK_eCK() {
        const sekarang = moment()
        const batas1 = moment('07:00:00', 'HH:mm:ss')
        const batas2 = moment('16:00:00', 'HH:mm:ss')
        if (!txfg_isRecalculateFunAlreadytried) {
            alertify.warning('Please try recalculate first')
            return
        }
        const btnCK = document.getElementById('txfg_btn_recalculate')
        let tabel_PLOT = document.getElementById("txfg_tbltxrm")
        let tabel_PLOT_body0 = tabel_PLOT.getElementsByTagName("tbody")[0]
        const ttlrows = tabel_PLOT_body0.getElementsByTagName('tr').length
        let serlist = []
        for (let i = 0; i < ttlrows; i++) {
            serlist.push(tabel_PLOT_body0.rows[i].cells[3].innerText)
        }
        if (serlist.length > 0) {
            if (confirm("Are you sure ?")) {
                const mpin = prompt("PIN")
                if (mpin !== '' && mpin !== null) {
                    btnCK.disabled = true
                    btnFLG.disabled = true
                    btnFLG.innerHTML = "Please wait"
                    $.ajax({
                        type: "POST",
                        url: "<?=base_url('DELV/setflag')?>",
                        data: {
                            inser: serlist,
                            inpin: mpin
                        },
                        dataType: "JSON",
                        success: function(response) {
                            btnFLG.innerHTML = "Set Flag OK"
                            btnCK.disabled = false
                            btnFLG.disabled = false
                            if (response.status[0].cd === 0) {
                                alertify.warning(response.status[0].msg)
                            } else {
                                alertify.message(response.status[0].msg)
                                $("#TXFG_MODRM").modal('hide')
                                const mtxid = document.getElementById("txfg_txt_id").value
                                txfg_f_getdetail(mtxid)
                            }
                        },
                        error(xhr, xopt, xthrow) {
                            btnFLG.innerHTML = "Set Flag OK"
                            btnCK.disabled = false
                            btnFLG.disabled = false
                            alertify.error(xthrow)
                        }
                    })
                }
            }
        }
    }

    function txfg_btn_recalculate_eCK() {
        const btnCK = document.getElementById('txfg_btn_recalculate')
        let tabel_PLOT = document.getElementById("txfg_tbltxrm")
        let tabel_PLOT_body0 = tabel_PLOT.getElementsByTagName("tbody")[0]
        const ttlrows = tabel_PLOT_body0.getElementsByTagName('tr').length
        let serlist = []
        let joblist = []
        let qtylist = []
        for (let i = 0; i < ttlrows; i++) {
            joblist.push(tabel_PLOT_body0.rows[i].cells[0].innerText)
            serlist.push(tabel_PLOT_body0.rows[i].cells[3].innerText)
            qtylist.push(numeral(tabel_PLOT_body0.rows[i].cells[4].innerText).value())
        }
        if (serlist.length > 0) {
            if (confirm("Are you sure ?")) {
                btnCK.disabled = true
                btnCK.innerHTML = "Please wait"
                txfg_isRecalculateFunAlreadytried = true
                $.ajax({
                    type: "POST",
                    url: "<?=base_url('SER/resetcalculation')?>",
                    data: {
                        injob: joblist,
                        inser: serlist,
                        inqty: qtylist
                    },
                    dataType: "JSON",
                    success: function(response) {
                        btnCK.innerHTML = "Recalculate"
                        btnCK.disabled = false
                        $("#TXFG_MODRM").modal('hide')
                        const mtxid = document.getElementById("txfg_txt_id").value
                        txfg_f_getdetail(mtxid)
                    },
                    error(xhr, xopt, xthrow) {
                        btnCK.innerHTML = "Recalculate"
                        btnCK.disabled = false
                        alertify.error(xthrow)
                    }
                })
            }
        }
    }

    function txfg_setsoother_manually(pdata) {
        const txid = document.getElementById('txfg_txt_id').value.trim()
        if (txid.length < 5) {
            alertify.message('TX ID is required');
            return
        }
        $.ajax({
            type: "GET",
            url: "<?=base_url('SO/plot_so_manually')?>",
            data: {
                doc: txid,
                so_no: pdata.so_no,
                so_item: pdata.so_item
            },
            dataType: "json",
            success: function(response) {
                const ttlrows = response.data.length
                if (ttlrows > 0) {
                    let tabell = document.getElementById("txfg_tblsoother_plot");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    let ttlrowscurrent = tableku2.getElementsByTagName('tr').length
                    let newrow, newcell, newText;
                    for (let i = 0; i < ttlrows; i++) {
                        let isExist = false
                        for (let a = 0; a < ttlrowscurrent; a++) {
                            const crt_so = tableku2.rows[a].cells[0].innerText
                            const crt_item = tableku2.rows[a].cells[1].innerText
                            if (response.data[i].SO_NO === crt_so && response.data[i].ASSYCODE === crt_item) {
                                isExist = true;
                                break;
                            }
                        }
                        if (!isExist) {
                            newrow = tableku2.insertRow(-1)
                            newcell = newrow.insertCell(0)
                            newcell.innerHTML = response.data[i].SO_NO
                            newcell = newrow.insertCell(1)
                            newcell.innerHTML = response.data[i].ASSYCODE
                            newcell = newrow.insertCell(2)
                            newcell.classList.add('text-end')
                            newcell.innerHTML = numeral(response.data[i].REQQT).format(',')
                            newcell = newrow.insertCell(3)
                            newcell.classList.add('text-end')
                            newcell.innerHTML = response.data[i].PLTQT
                        } else {
                            alertify.message('the SO Number is already plotted')
                        }
                    }
                }
            },
            error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow)
            }
        })
    }

    function txfg_setsomega_manually(pdata) {
        const txid = document.getElementById('txfg_txt_id').value.trim()
        if (txid.length < 5) {
            alertify.message('TX ID is required');
            return
        }
        const so_tbl = document.getElementById('txfg_tblso_plot').getElementsByTagName('tbody')[0]
        const so_tbl_rows = so_tbl.getElementsByTagName('tr').length
        let plotedqty = 0
        for (let i = 0; i < so_tbl_rows; i++) {
            if (so_tbl.rows[i].cells[2].innerText == pdata.so_item) {
                const qty = numeral(so_tbl.rows[i].cells[5].innerText.trim()).value()
                plotedqty += qty
            }
        }
        $.ajax({
            type: "GET",
            url: "<?=base_url('SO/plot_somega_manually')?>",
            data: {
                doc: txid,
                so_no: pdata.so_no,
                so_item: pdata.so_item,
                so_line: pdata.so_line,
                plotqty: plotedqty
            },
            dataType: "json",
            success: function(response) {
                const ttlrows = response.data.length
                if (ttlrows > 0) {
                    let tabell = document.getElementById("txfg_tblso_plot");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    let ttlrowscurrent = tableku2.getElementsByTagName('tr').length
                    let newrow, newcell, newText;
                    for (let i = 0; i < ttlrows; i++) {
                        let isExist = false
                        for (let a = 0; a < ttlrowscurrent; a++) {
                            const crt_so = tableku2.rows[a].cells[0].innerText
                            const crt_item = tableku2.rows[a].cells[2].innerText
                            const crt_soline = tableku2.rows[a].cells[1].innerText
                            if (response.data[i].SO_NO === crt_so && response.data[i].ASSYCODE === crt_item && response.data[i].SO_NO.trim() == crt_soline) {
                                isExist = true;
                                break;
                            }
                        }
                        if (!isExist) {
                            newrow = tableku2.insertRow(-1)
                            newcell = newrow.insertCell(0)
                            newcell.innerHTML = response.data[i].SO_NO
                            newcell = newrow.insertCell(1)
                            newcell.innerHTML = response.data[i].SO_LINE
                            newcell.ondblclick = function(e) {
                                document.getElementById('txfg_tblso_plot').deleteRow(e.srcElement.parentElement.rowIndex)
                            }

                            newcell = newrow.insertCell(2)
                            newcell.innerHTML = response.data[i].ASSYCODE
                            newcell = newrow.insertCell(3)
                            newcell.classList.add('text-end')
                            newcell.innerHTML = response.data[i].SO_PRICE
                            newcell = newrow.insertCell(4)
                            newcell.classList.add('text-end')
                            newcell.innerHTML = numeral(response.data[i].REQQT).format(',')
                            newcell = newrow.insertCell(5)
                            newcell.classList.add('text-end')
                            newcell.innerHTML = response.data[i].PLTQT
                            newcell = newrow.insertCell(6)
                            newcell.classList.add('d-none')
                            newcell.innerHTML = response.data[i].SISCN_LINE
                        } else {
                            alertify.message('the SO Number is already plotted')
                        }
                    }
                }
            },
            error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow)
            }
        })
    }

    function txfg_btn_fifo_so_eCK() {
        const txid = document.getElementById('txfg_txt_id').value.trim()
        if (txid.length < 5) {
            alertify.message('TX ID is required');
            return
        }
        document.getElementById('txfg_tblso_plot').getElementsByTagName('tbody')[0].innerHTML = `<tr><td colpsan="7" class="text-center">Please wait</td></tr>`
        $.ajax({
            type: "GET",
            url: "<?=base_url('SO/plot_so_mega')?>",
            data: {
                doc: txid
            },
            dataType: "json",
            success: function(response) {
                const ttlrows = response.data.length
                if (ttlrows > 0) {
                    let mydes = document.getElementById("txfg_tblso_plot_div");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("txfg_tblso_plot");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("txfg_tblso_plot");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    let newrow, newcell, newText;
                    tableku2.innerHTML = ''
                    for (let i = 0; i < ttlrows; i++) {
                        newrow = tableku2.insertRow(-1)
                        newcell = newrow.insertCell(0)
                        newcell.innerHTML = response.data[i].SO_NO
                        newcell = newrow.insertCell(1)
                        newcell.innerHTML = response.data[i].SO_LINE
                        newcell = newrow.insertCell(2)
                        newcell.innerHTML = response.data[i].ASSYCODE
                        newcell = newrow.insertCell(3)
                        newcell.classList.add('text-end')
                        newcell.innerHTML = response.data[i].SO_PRICE
                        newcell = newrow.insertCell(4)
                        newcell.classList.add('text-end')
                        newcell.innerHTML = numeral(response.data[i].REQQT).format(',')
                        newcell = newrow.insertCell(5)
                        newcell.classList.add('text-end')
                        newcell.innerHTML = response.data[i].PLTQT
                        newcell = newrow.insertCell(6)
                        newcell.classList.add('d-none')
                        newcell.innerHTML = response.data[i].SISCN_LINE
                    }
                    mydes.innerHTML = ''
                    mydes.appendChild(myfrag)
                } else {
                    document.getElementById('txfg_tblso_plot').getElementsByTagName('tbody')[0].innerHTML = ``
                }
            },
            error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow)
            }
        });
    }

    function txfg_btn_fifo_so_oth_eCK() {
        const txid = document.getElementById('txfg_txt_id').value.trim()
        if (txid.length < 5) {
            alertify.message('TX ID is required');
            return
        }
        document.getElementById('txfg_tblsoother_plot').getElementsByTagName('tbody')[0].innerHTML = `<tr><td colpsan="4" class="text-center">Please wait</td></tr>`
        $.ajax({
            type: "GET",
            url: "<?=base_url('SO/plot_so')?>",
            data: {
                doc: txid
            },
            dataType: "json",
            success: function(response) {
                const ttlrows = response.data.length
                if (ttlrows > 0) {
                    let mydes = document.getElementById("txfg_tblso_plotother_div");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("txfg_tblsoother_plot");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("txfg_tblsoother_plot");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    let newrow, newcell, newText;
                    tableku2.innerHTML = ''
                    for (let i = 0; i < ttlrows; i++) {
                        newrow = tableku2.insertRow(-1)
                        newcell = newrow.insertCell(0)
                        newcell.innerHTML = response.data[i].SO_NO
                        newcell = newrow.insertCell(1)
                        newcell.innerHTML = response.data[i].ASSYCODE
                        newcell = newrow.insertCell(2)
                        newcell.classList.add('text-end')
                        newcell.innerHTML = numeral(response.data[i].REQQT).format(',')
                        newcell = newrow.insertCell(3)
                        newcell.classList.add('text-end')
                        newcell.innerHTML = response.data[i].PLTQT
                    }
                    mydes.innerHTML = ''
                    mydes.appendChild(myfrag)
                } else {
                    document.getElementById('txfg_tblsoother_plot').getElementsByTagName('tbody')[0].innerHTML = ``
                }
            },
            error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow)
            }
        });
    }

    function txfg_btn_resetfifo_so_eCK() {
        if (confirm("Are you sure ?")) {
            document.getElementById('txfg_tblso_plot').getElementsByTagName('tbody')[0].innerHTML = ''
        }
    }

    function txfg_btn_resetfifo_so_oth_eCK() {
        if (confirm("Are you sure ?")) {
            document.getElementById('txfg_tblsoother_plot').getElementsByTagName('tbody')[0].innerHTML = ''
        }
    }

    function txfg_btn_SOOther_eCK() {
        const items = [...new Set(txfg_ar_item_cd)]
        const bg = document.getElementById('txfg_businessgroup').value
        const consignee = document.getElementById('txfg_consignee').value
        document.getElementById('txfg_tblsoother').getElementsByTagName('tbody')[0].innerHTML = `<tr><td colspan="5" class="text-center">Please wait</td></tr>`
        $.ajax({
            type: "POST",
            url: "<?=base_url('SO/outstanding')?>",
            data: {
                bg: bg,
                cuscd: scr_txfg_cust,
                consignee: consignee,
                itemcd: items
            },
            dataType: "JSON",
            success: function(response) {
                const ttlrows = response.data.length
                if (ttlrows > 0) {
                    let mydes = document.getElementById("txfg_tblsoother_div");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("txfg_tblsoother");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("txfg_tblsoother");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    let newrow, newcell, newText;
                    tableku2.innerHTML = ''
                    for (let i = 0; i < ttlrows; i++) {
                        newrow = tableku2.insertRow(-1)
                        newcell = newrow.insertCell(0)
                        newcell.innerHTML = response.data[i].SO_NO
                        newcell.style.cssText = 'cursor:pointer'
                        newcell.onclick = () => {
                            txfg_setsoother_manually({
                                so_no: response.data[i].SO_NO,
                                so_item: response.data[i].SO_ITEMCD
                            })
                        }
                        newcell = newrow.insertCell(1)
                        newcell.innerHTML = response.data[i].SO_ORDRDT
                        newcell = newrow.insertCell(2)
                        newcell.innerHTML = response.data[i].SO_ITEMCD
                        newcell = newrow.insertCell(3)
                        newcell.innerHTML = numeral(response.data[i].ORDQT).format(',')
                        newcell.classList.add('text-end')
                        newcell = newrow.insertCell(4)
                        newcell.classList.add('text-end')
                        newcell.innerHTML = response.data[i].BALQT
                    }
                    mydes.innerHTML = ''
                    mydes.appendChild(myfrag)
                } else {
                    document.getElementById('txfg_tblsoother').getElementsByTagName('tbody')[0].innerHTML = `<tr><td colspan="5" class="text-center">Not found</td></tr>`
                }
            },
            error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow)
            }
        })
        document.getElementById('txfg_tblsoother_plot').getElementsByTagName('tbody')[0].innerHTML = ''
        const txid = document.getElementById('txfg_txt_id').value.trim()
        $.ajax({
            type: "GET",
            url: "<?=base_url('DELV/so_dlv')?>",
            data: {
                doc: txid
            },
            dataType: "json",
            success: function(response) {
                const ttlrows = response.data.length
                if (ttlrows > 0) {
                    let mydes = document.getElementById("txfg_tblso_plotother_div");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("txfg_tblsoother_plot");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("txfg_tblsoother_plot");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    let newrow, newcell, newText;
                    tableku2.innerHTML = ''
                    for (let i = 0; i < ttlrows; i++) {
                        newrow = tableku2.insertRow(-1)
                        newcell = newrow.insertCell(0)
                        newcell.innerHTML = response.data[i].DLVSO_CPONO
                        newcell = newrow.insertCell(1)
                        newcell.innerHTML = response.data[i].DLVSO_ITMCD
                        newcell = newrow.insertCell(2)
                        newcell.classList.add('text-end')
                        newcell.innerHTML = numeral(response.data[i].DLVQTY).format(',')
                        newcell = newrow.insertCell(3)
                        newcell.classList.add('text-end')
                        newcell.innerHTML = numeral(response.data[i].PLOTQTY).format(',')
                    }
                    mydes.innerHTML = ''
                    mydes.appendChild(myfrag)
                } else {
                    document.getElementById('txfg_tblsoother_plot').getElementsByTagName('tbody')[0].innerHTML = ``
                }
            },
            error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow)
            }
        })
        $("#TXFG_MODSOOTHER").modal('show')
    }

    function txfg_btn_showExbcList_eCK() {
        const txid = document.getElementById('txfg_txt_id').value.trim()
        document.getElementById('TXFG_MODEXBCLIST_txid').value = txid
        document.getElementById('txfg_tblEXBC').getElementsByTagName("tbody")[0].innerHTML = `<tr><td colspan="3" class="text-center">Please wait</td></tr>`
        $.ajax({
            type: "GET",
            url: "<?=base_url('DELV/exbclist')?>",
            data: {
                txid: txid
            },
            dataType: "json",
            success: function(response) {
                let ttlrows = response.data.length
                if (ttlrows > 0) {
                    let mydes = document.getElementById("txfg_tblEXBCdiv");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("txfg_tblEXBC");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("txfg_tblEXBC");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    let newrow, newcell, newText;
                    tableku2.innerHTML = ''
                    for (let i = 0; i < ttlrows; i++) {
                        newrow = tableku2.insertRow(-1)
                        newcell = newrow.insertCell(0)
                        newcell.classList.add('text-center')
                        newcell.innerHTML = response.data[i].RPSTOCK_BCTYPE
                        newcell = newrow.insertCell(1)
                        newcell.classList.add('text-center')
                        newcell.innerHTML = response.data[i].RPSTOCK_BCNUM
                        newcell = newrow.insertCell(2)
                        newcell.classList.add('text-center')
                        newcell.innerHTML = response.data[i].RPSTOCK_BCDATE
                    }
                    mydes.innerHTML = ''
                    mydes.appendChild(myfrag)
                } else {
                    document.getElementById('txfg_tblEXBC').getElementsByTagName("tbody")[0].innerHTML = `<tr><td colspan="3" class="text-center">not found</td></tr>`
                }
            },
            error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow);
                document.getElementById('txfg_tblEXBC').getElementsByTagName("tbody")[0].innerHTML = `<tr><td colspan="3" class="text-center">${xthrow}</td></tr>`
            }
        });
        $("#TXFG_MODEXBCLIST").modal('show')
    }

    function txfg_btn_SO_eCK() {
        const items = [...new Set(txfg_ar_item_cd)]
        const bg = document.getElementById('txfg_businessgroup').value
        document.getElementById('txfg_tblso').getElementsByTagName('tbody')[0].innerHTML = `<tr><td colspan="5" class="text-center">Please wait</td></tr>`
        const btnFifo = document.getElementById('txfg_btn_fifo_so')
        btnFifo.innerHTML = 'Please wait'
        btnFifo.disabled = true
        $.ajax({
            type: "POST",
            url: "<?=base_url('SO/outstanding_mega')?>",
            data: {
                bg: bg,
                cuscd: scr_txfg_cust,
                itemcd: items
            },
            dataType: "JSON",
            success: function(response) {
                const ttlrows = response.data.length
                if (ttlrows > 0) {
                    let mydes = document.getElementById("txfg_tblso_div");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("txfg_tblso");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("txfg_tblso");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    let newrow, newcell, newText;
                    tableku2.innerHTML = ''
                    for (let i = 0; i < ttlrows; i++) {
                        let bal = numeral(response.data[i].SSO2_ORDQT).value() - numeral(response.data[i].SSO2_DELQT).value()
                        newrow = tableku2.insertRow(-1)
                        newcell = newrow.insertCell(0)
                        newcell.innerHTML = response.data[i].SSO2_CPONO
                        newcell.style.cssText = 'cursor:pointer'
                        newcell.onclick = () => {
                            txfg_setsomega_manually({
                                so_no: response.data[i].SSO2_CPONO,
                                so_line: response.data[i].SSO2_SOLNO,
                                so_item: response.data[i].SSO2_MDLCD
                            })
                        }
                        newcell = newrow.insertCell(1)
                        newcell.innerHTML = response.data[i].SSO2_MDLCD
                        newcell = newrow.insertCell(2)
                        newcell.innerHTML = response.data[i].SSO2_SLPRC
                        newcell = newrow.insertCell(3)
                        newcell.innerHTML = numeral(response.data[i].SSO2_ORDQT).format(',')
                        newcell.classList.add('text-end')
                        newcell = newrow.insertCell(4)
                        newcell.classList.add('text-end')
                        newcell.innerHTML = numeral(bal).format(',')
                        newcell = newrow.insertCell(5)
                        newcell.innerHTML = response.data[i].SSO2_SOLNO
                    }
                    mydes.innerHTML = ''
                    mydes.appendChild(myfrag)
                } else {
                    document.getElementById('txfg_tblsoother').getElementsByTagName('tbody')[0].innerHTML = `<tr><td colspan="5" class="text-center">Not found</td></tr>`
                }
            },
            error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow)
            }
        })
        document.getElementById('txfg_tblso_plot').getElementsByTagName('tbody')[0].innerHTML = ''
        const txid = document.getElementById('txfg_txt_id').value.trim()
        $.ajax({
            type: "GET",
            url: "<?=base_url('DELV/so_mega_dlv')?>",
            data: {
                doc: txid
            },
            dataType: "json",
            success: function(response) {
                btnFifo.innerHTML = 'FIFO'
                btnFifo.disabled = false
                const ttlrows = response.data.length
                if (ttlrows > 0) {
                    let mydes = document.getElementById("txfg_tblso_plot_div");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("txfg_tblso_plot");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln)
                    let tabell = myfrag.getElementById("txfg_tblso_plot");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    let newrow, newcell, newText;
                    tableku2.innerHTML = ''
                    for (let i = 0; i < ttlrows; i++) {
                        newrow = tableku2.insertRow(-1)
                        newcell = newrow.insertCell(0)
                        newcell.innerHTML = response.data[i].SISO_CPONO
                        newcell = newrow.insertCell(1)
                        newcell.innerHTML = response.data[i].SISO_SOLINE
                        newcell.ondblclick = function(e) {
                            document.getElementById('txfg_tblso_plot').deleteRow(e.srcElement.parentElement.rowIndex)
                        }
                        newcell = newrow.insertCell(2)
                        newcell.innerHTML = response.data[i].SER_ITMID
                        newcell = newrow.insertCell(3)
                        newcell.classList.add('text-end')
                        newcell.innerHTML = response.data[i].SSO2_SLPRC
                        newcell = newrow.insertCell(4)
                        newcell.classList.add('text-end')
                        newcell.innerHTML = numeral(response.data[i].DLVTQT).format(',')
                        newcell = newrow.insertCell(5)
                        newcell.classList.add('text-end')
                        newcell.innerHTML = numeral(response.data[i].SISTQT).format(',')
                        newcell = newrow.insertCell(6)
                        newcell.classList.add('d-none')
                        newcell.innerHTML = response.data[i].SISCN_LINENO
                    }
                    mydes.innerHTML = ''
                    mydes.appendChild(myfrag)
                } else {
                    document.getElementById('txfg_tblsoother_plot').getElementsByTagName('tbody')[0].innerHTML = ``
                }
            },
            error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow)
                btnFifo.innerHTML = 'FIFO'
                btnFifo.disabled = false
            }
        })
        let mymodal = new bootstrap.Modal(document.getElementById("TXFG_MODSO"), {
            backdrop: 'static',
            keyboard: false
        })
        mymodal.show()
    }

    function txfg_btn_rmstatus_eC() {
        let ttlrows = document.getElementById('txfg_tbltxrm').getElementsByTagName('tbody')[0].getElementsByTagName('tr').length
        if (ttlrows > 0) {
            document.getElementById('txfg_label_rm_txid').innerText = document.getElementById('txfg_txt_id').value
            let mymodal = new bootstrap.Modal(document.getElementById("TXFG_MODRM"), {
                backdrop: 'static',
                keyboard: false
            })
            mymodal.show()
        } else {
            alertify.success("Nothing to be checked");
        }
    }

    function txfg_btn_close_modrm_eCK() {
        const btnCal = document.getElementById('txfg_btn_recalculate')
        if (!btnCal.disabled) {
            $("#TXFG_MODRM").modal('hide')
        } else {
            alertify.message('The calculation is running, please wait')
        }
    }
    document.getElementById('txfg_year').value = new Date().getFullYear();
    txfg_g_string = (new Date().getMonth() + 1).toString();
    if (txfg_g_string < 10) {
        txfg_g_string = '0' + txfg_g_string;
    } else {
        txfg_g_string = txfg_g_string.toString();
    }
    document.getElementById('txfg_monthfilter').value = txfg_g_string;

    function txfg_centerRight() {
        $.messager.show({
            title: 'Info',
            msg: `Time Consume : ${txfg_timerspan.innerHTML} <br>
            Start : ${txfg_starttime} <br>
            Finish: ${moment().format('HH:mm:ss')}`,
            showType: 'fade',
            timeout: 0,
            style: {
                left: 0,
                right: '',
                bottom: ''
            }
        });
    }

    function txfg_add() {
        txfg_seconds++;
        if (txfg_seconds >= 60) {
            txfg_seconds = 0;
            txfg_minutes++;
            if (txfg_minutes >= 60) {
                txfg_minutes = 0;
                txfg_hours++;
            }
        }

        txfg_timerspan.innerHTML = (txfg_hours ? (txfg_hours > 9 ? txfg_hours : "0" + txfg_hours) : "00") +
            ":" + (txfg_minutes ? (txfg_minutes > 9 ? txfg_minutes : "0" + txfg_minutes) : "00") +
            ":" + (txfg_seconds > 9 ? txfg_seconds : "0" + txfg_seconds);

        txfg_timer();
    }

    function txfg_timer() {
        txfg_t = setTimeout(txfg_add, 1000);
    }

    function txfg_e_cancelitem() {
        if (confirm("Are you sure want to cancel [" + txfg_g_string + "] ?")) {
            let ctxid = document.getElementById('txfg_txt_id').value;
            $.ajax({
                type: "post",
                url: "<?=base_url('DELV/removeun_by_txid_item')?>",
                data: {
                    txid: ctxid,
                    itemid: txfg_g_string
                },
                dataType: "json",
                success: function(response) {
                    txfg_g_string = '';
                    if (response.status[0].cd == '0') {
                        alertify.warning(response.status[0].msg);
                    } else {
                        alertify.message(response.status[0].msg);
                        let mtxid = document.getElementById("txfg_txt_id").value;
                        txfg_f_getdetail(mtxid);
                    }
                },
                error: function(xhr, xopt, xthrow) {
                    alertify.error(xthrow);
                }
            });
        }
    }

    function txfg_btn_tobom_e_click() {
        const dono = document.getElementById('txfg_txt_id').value;
        if (dono.trim() == '') {
            alertify.warning('DO Number could not be empty');
            document.getElementById('txfg_txt_id').focus();
            return;
        }
        Cookies.set('CKPSI_DOBOM', dono, {
            expires: 365
        });
        window.open("<?=base_url('ex_do_bom')?>", '_blank');

    }

    function txfg_btn_tomega_e_click() {
        let dono = document.getElementById('txfg_txt_id').value;
        if (dono.trim() == '') {
            alertify.warning('DO Number could not be empty');
            document.getElementById('txfg_txt_id').focus();
            return;
        }
        Cookies.set('CKPSI_DOBOM', dono, {
            expires: 365
        });
        window.open("<?=base_url('ex_shipping_mega')?>", '_blank');

    }

    function txfg_e_calculate_raw_material_resume() {
        if (txfg_ar_item_ser.length > 0) {
            document.getElementById('txfg_gt_rm').innerText = "Initializing RM";
            document.getElementById('txfg_btn_post').classList.add('disabled')
            $.ajax({
                type: "post",
                url: "<?=base_url('DELV/calculate_raw_material_resume')?>",
                data: {
                    inunique: txfg_ar_item_ser,
                    inunique_qty: txfg_ar_item_qty,
                    inunique_job: txfg_ar_item_job
                },
                dataType: "json",
                success: function(response) {
                    document.getElementById('txfg_btn_post').classList.remove('disabled')
                    try {
                        const ttlrows = response.data.length;
                        const ttlselected = txfg_ar_item_ser.length;
                        for (let i = 0; i < ttlrows; i++) {
                            for (let u = 0; u < ttlselected; u++) {
                                if (response.data[i].SERD2_SER == txfg_ar_item_ser[u]) {
                                    txfg_ar_cnt_rm[u] = response.data[i].COUNTRM;
                                }
                            }
                        }

                        let tbldet = document.getElementById("txfg_tbltx");
                        let tbldet_b = tbldet.getElementsByTagName("tbody")[0];
                        const ttlmainrow = tbldet_b.getElementsByTagName('tr').length;
                        let ttldis = 0;
                        for (let k = 0; k < ttlmainrow; k++) {
                            let item0 = tbldet_b.rows[k].cells[2].innerText.trim();
                            if (item0.length > 0) { //handle/pass distincted data
                                let isfilled = false;
                                for (let i = 0; i < ttlmainrow; i++) {
                                    let itemvirtual = tbldet_b.rows[i].cells[2].innerText.trim();
                                    let itemrm = tbldet_b.rows[i].cells[7].innerText.trim();
                                    if (item0 == itemvirtual && itemrm.length > 0) {
                                        isfilled = true;
                                        break;
                                    }
                                }
                                if (!isfilled) {
                                    for (let c = 0; c < ttlselected; c++) {
                                        if (item0 == txfg_ar_item_cd[c].trim()) {
                                            tbldet_b.rows[k].cells[7].innerText = txfg_ar_cnt_rm[c];
                                            ttldis += txfg_ar_cnt_rm[c];
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                        document.getElementById('txfg_gt_rm').innerText = numeral(ttldis).format(',');
                    } catch (err) {
                        document.getElementById('txfg_gt_rm').innerText = 0
                    }

                },
                error: function(xhr, xopt, xthrow) {
                    alertify.error(xthrow);
                    document.getElementById('txfg_gt_rm').innerText = "please try again";
                }
            });
        }
    }

    function txfg_btn_showprice_e_click() {
        if (txfg_ar_item_ser.length > 0) {
            document.getElementById('txfg_lblinfo_price').innerText = "Please wait...";
            $("#txfg_tblinfo_price tbody").empty();
            $.ajax({
                type: "post",
                url: "<?=base_url('SI/getsobyreffno')?>",
                data: {
                    inser: txfg_ar_item_ser
                },
                dataType: "json",
                success: function(response) {
                    if (response.status[0].cd != '0') {
                        let ttlrows = response.data.length;
                        document.getElementById('txfg_lblinfo_price').innerText = ttlrows + " row(s) found";
                        let tabel_PLOT = document.getElementById("txfg_tblinfo_price");
                        let tabel_PLOT_body0 = tabel_PLOT.getElementsByTagName("tbody")[0];
                        let newrow;
                        let gttlqty = 0;
                        let gttlprice = 0;
                        let ttlprice = 0;
                        for (let i = 0; i < ttlrows; i++) {
                            let price = response.data[i].SSO2_SLPRC.substr(0, 1) == "." ? "0" + response.data[i].SSO2_SLPRC : response.data[i].SSO2_SLPRC;
                            gttlqty += Number(response.data[i].SCNQTY);
                            ttlprice = price * Number(response.data[i].SCNQTY);
                            gttlprice += ttlprice;
                            newrow = tabel_PLOT_body0.insertRow(-1)

                            newcell = newrow.insertCell(0);
                            newcell.innerHTML = response.data[i].SI_ITMCD

                            newcell = newrow.insertCell(1);
                            newcell.style.cssText = "text-align:right";
                            newcell.innerHTML = numeral(response.data[i].SCNQTY).format(',')

                            newcell = newrow.insertCell(2);
                            newcell.style.cssText = "text-align:right";
                            newcell.innerHTML = price

                            newcell = newrow.insertCell(3);
                            newcell.style.cssText = "text-align:right";
                            newcell.innerHTML = ttlprice
                        }

                        let count_multiprice = response.datamultiprice.length
                        if (count_multiprice > 0) {
                            document.getElementById('txfg_div_price').style.cssText = "display:block";
                            let itemlist = '';
                            for (let i = 0; i < count_multiprice; i++) {
                                itemlist += '<b>' + response.datamultiprice[i].RSI_ITMCD + '</b>,';
                            }
                            itemlist = itemlist.substr(0, itemlist.length - 1);
                            document.getElementById('txfg_alert_price').innerHTML = "Multiple price detected, please confirm price for the item " + itemlist;
                        } else {
                            document.getElementById('txfg_div_price').style.cssText = "display:none";
                        }
                        document.getElementById('txfg_tblinfo_price_footer_qty').innerText = numeral(gttlqty).format(',');
                        document.getElementById('txfg_tblinfo_price_footer_price').innerText = gttlprice;
                    } else {
                        alertify.message('no Sales Order found');
                    }
                },
                error: function(xhr, xopt, xthrow) {
                    alertify.error(xthrow);
                }
            });
            $("#TXFG_MODINFO_PRICE").modal('show');
        }
    }

    function txfg_btn_sync_pendaftaran_e_click(pElement) {
        pElement.disabled = true
        pElement.innerHTML = `<i class="fas fa-sync fa-spin"></i>`
        let itemcode = document.getElementById('txfg_txt_id').value;
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
                    const nopen = response.data[0].NOMOR_DAFTAR?? ''
                    if (nopen.length == 6) {
                        document.getElementById('txfg_txt_nopen').value = response.data[0].NOMOR_DAFTAR;
                        document.getElementById('txfg_txt_tglpen').value = response.data[0].TANGGAL_DAFTAR.substr(0, 10);
                        if (response.data2.length > 0) {
                            document.getElementById('txfg_txt_sppb27').value = response.data2[0].NOMOR_RESPON
                        }
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
        });
    }

    function txfg_btn_sync_pendaftaran25_e_click(pElement) {
        pElement.disabled = true
        pElement.innerHTML = `<i class="fas fa-sync fa-spin"></i>`
        let itemcode = document.getElementById('txfg_txt_id').value;
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
                        document.getElementById('txfg_txt_nopen25').value = response.data[0].NOMOR_DAFTAR;
                        document.getElementById('txfg_txt_tglpen25').value = response.data[0].TANGGAL_DAFTAR.substr(0, 10);
                        if (response.data2.length > 0) {
                            document.getElementById('txfg_txt_sppb25').value = response.data2[0].NOMOR_RESPON
                        }
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
        });
    }

    function txfg_btn_sync_pendaftaran41_e_click(pElement) {
        pElement.disabled = true
        pElement.innerHTML = `<i class="fas fa-sync fa-spin"></i>`
        let itemcode = document.getElementById('txfg_txt_id').value;
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
                        document.getElementById('txfg_txt_nopen41').value = response.data[0].NOMOR_DAFTAR;
                        document.getElementById('txfg_txt_tglpen41').value = response.data[0].TANGGAL_DAFTAR.substr(0, 10);
                        if (response.data2.length > 0) {
                            document.getElementById('txfg_txt_sppb41').value = response.data2[0].NOMOR_RESPON
                        }
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
        });
    }

    function txfg_btn_showinfo_e_click() {
        $("#TXFG_MODINFO").modal('show');
    }

    $("#txfg_searchtrans").keypress(function(e) {
        if (e.which == 13) {
            var mval = $(this).val();
            if (mval.trim() != '') {
                $.ajax({
                    type: "get",
                    url: "<?=base_url('Trans/search')?>",
                    data: {
                        inkey: mval
                    },
                    dataType: "json",
                    success: function(response) {
                        var ttlrows = response.length;
                        var tohtml = '';
                        for (var i = 0; i < ttlrows; i++) {
                            tohtml += '<tr style="cursor:pointer">' +
                                '<td>' + response[i].MSTTRANS_ID + '</td>' +
                                '<td>' + response[i].MSTTRANS_TYPE + '</td>' +
                                '</tr>';
                        }
                        $("#txfg_tbltrans tbody").html(tohtml);
                    },
                    error: function(xhr, xopt, xthrow) {
                        alertify.error(xthrow);
                    }
                });
            }
        }
    });

    $("#TXFG_PROGRESS").on('shown.bs.modal', function() {
        txfg_e_posting();
    });

    $("#TXFG_MODRFID").on('shown.bs.modal', function() {
        document.execCommand("copy");
        alertify.success('ok just paste to your application');
    });
    $("#TXFG_MODEXIM").on('shown.bs.modal', function() {
        document.execCommand("copy")
        alertify.success('ok, just paste to your application')
    })

    $('#txfg_tblcus tbody').on('click', 'tr', function() {
        if ($(this).hasClass('table-active')) {
            $(this).removeClass('table-active');
        } else {
            $('#txfg_tblcus tbody tr.table-active').removeClass('table-active');
            $(this).addClass('table-active');
        }
        scr_txfg_cust = $(this).closest("tr").find('td:eq(0)').text();
        let mcuscurr = $(this).closest("tr").find('td:eq(1)').text();
        let mcusnm = $(this).closest("tr").find('td:eq(2)').text();

        $("#txfg_custname").val(mcusnm);
        $("#txfg_curr").val(mcuscurr);
        $("#TXFG_MODCUS").modal('hide');
        $("#txfg_sitbl tbody").empty();
    });

    function txfg_getconsignee(ppar, selval) {
        $("#txfg_consignee").val(selval);
    }

    $("#txfg_txt_noskb_tgl").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });
    $("#txfg_txt_tglpen25").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });
    $("#txfg_txt_tglpen").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });

    $("#txfg_txt_DOdate").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });
    $("#txfg_txt_custdate").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });
    $("#txfg_txt_tglsurat").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });
    $("#txfg_txt_tglkontrak").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });



    $("#txfg_txt_custdate").datepicker('update', new Date());
    $("#txfg_txt_tglsurat").datepicker('update', new Date());
    $("#txfg_txt_tglkontrak").datepicker('update', new Date());

    $("#txfg_txt_DOdate").datepicker('update', new Date());
    $("#txfg_btnfindmodcust").click(function(e) {
        e.preventDefault();
        $("#TXFG_MODCUS").modal('show');
    });
    $("#TXFG_MODCUS").on('shown.bs.modal', function() {
        $("#txfg_txtsearchcus").focus();
    });

    function txfg_businessgroup_e_onchange() {
        document.getElementById('txfg_custname').value = '';
        document.getElementById('txfg_curr').value = '';
        $('#txfg_tblcus tbody').empty();
        document.getElementById('txfg_btnfindmodcust').focus();
    }
    $("#txfg_txtsearchcus").keypress(function(e) {
        if (e.which == 13) {
            let mkey = $(this).val();
            let msearchby = $("#txfg_srchby").val();
            let mbg = document.getElementById('txfg_businessgroup').value;
            if (mbg.trim() == "-") {
                alertify.message('Please select business group first');
                return;
            }
            $('#txfg_tblcus tbody').empty();
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
                        let ttlrows = response.data.length;
                        let tohtml = '';
                        for (let i = 0; i < ttlrows; i++) {

                            tohtml += '<tr style="cursor:pointer">' +
                                '<td style="white-space:nowrap">' + response.data[i].MCUS_CUSCD.trim() + '</td>' +
                                '<td style="white-space:nowrap">' + response.data[i].MCUS_CURCD + '</td>' +
                                '<td style="white-space:nowrap">' + response.data[i].MCUS_CUSNM + '</td>' +
                                '<td style="white-space:nowrap">' + response.data[i].MCUS_ABBRV + '</td>' +
                                '<td>' + response.data[i].MCUS_ADDR1 + '</td>' +
                                '</tr>';
                        }
                        $('#txfg_tblcus tbody').html(tohtml);
                    } else {
                        alertify.message(response.status[0].msg);
                    }
                }
            });
        }
    });
    $("#txfg_srchby").change(function() {
        $("#txfg_txtsearchcus").focus();
    });
    $("#txfg_btn_customs").click(function(e) {
        e.preventDefault();
        let txid = document.getElementById('txfg_txt_id').value;
        if (txid.length < 3) {
            alertify.message('TX ID is required');
            return;
        }
        let mdokumenpab = document.getElementById('txfg_cmb_bcdoc').value;
        switch (mdokumenpab) {
            case '-':
                alertify.message('please select <b>Customs Document</b> first !');
                document.getElementById('txfg_cmb_bcdoc').focus();
                break;
            case '25':
                $("#TXFG_CUSTOMSMOD25").modal('show');
                break;
            case '27':
                if (txfg_txt_id.value.includes('RTN')) {
                    txfg_cmb_tujuanpengiriman.disabled = false
                } else {
                    txfg_cmb_tujuanpengiriman.disabled = true
                    txfg_cmb_tujuanpengiriman.value = '1'
                }
                txfg_fromoffice.value = '050900' //bekasi
                txfg_fromoffice.disabled = true
                $("#TXFG_CUSTOMSMOD").modal('show');
                break;
            case '41':
                $("#TXFG_CUSTOMSMOD41").modal('show')
                txfg_findContract_41()
                break
        }
    })

    function txfg_findContract_41() {
        const bisgrup = document.getElementById('txfg_businessgroup').value
        $.ajax({
            type: "GET",
            url: "<?=base_url('MCONA/plot')?>",
            data: {
                bisgrup: bisgrup,
                cuscd: scr_txfg_cust
            },
            dataType: "JSON",
            success: function(response) {
                const ttlrows = response.data.length
                const conctractList = document.getElementById('txfg_txt_nokontrak41_dl')
                conctractList.innerHTML = ''
                for (let i = 0; i < ttlrows; i++) {
                    const list = document.createElement('option')
                    list.value = response.data[i].MCONA_DOC
                    conctractList.appendChild(list)
                }
            },
            error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow);
            }
        })
    }
    $("#txfg_btn_new").click(function(e) {
        e.preventDefault();
        txfg_isRecalculateFunAlreadytried = false
        $("#txfg_txt_id").val('')
        $("#txfg_txt_id").focus()
        document.getElementById('txfg_tblsoother_plot').getElementsByTagName('tbody').innerHTML = ''
        document.getElementById('txfg_tbltx').getElementsByTagName('tbody')[0].innerHTML = "";
        document.getElementById('txfg_divalertrm').innerHTML = ''
        $("#txfg_txt_custdate").datepicker('update', new Date());
        $("#txfg_txt_DOdate").datepicker('update', new Date());
        $("#txfg_status").val('');
        $("#txfg_lbl_status").html('');
        $("#txfg_txt_invno").val('');
        $("#txfg_custname").val('');
        scr_txfg_cust = '';
        $("#txfg_curr").val('');
        document.getElementById('txfg_btn_SO').disabled = true

        document.getElementById("txfg_txt_transport").value = "-";
        document.getElementById('txfg_cmb_bcdoc').value = '-';
        document.getElementById('txfg_txt_invsmt').value = '';
        document.getElementById('txfg_txt_invno').value = '';
        document.getElementById("txfg_txt_remark").value = '';
        document.getElementById("txfg_txt_customerDO").value = '';


        //RIGHT PANE
        $("#txfg_txt_createdby").val("");
        $("#txfg_txt_createdtime").val("");
        $("#txfg_txt_luby").val("");
        $("#txfg_txt_lutime").val("");
        $("#txfg_txt_apprby").val("");
        $("#txfg_txt_apprtime").val("");
        $("#txfg_txt_postedby").val("");
        $("#txfg_txt_postedtime").val("");

        //reset
        scr_txfg_cust = '';
        txfg_ar_item_ser = [];
        txfg_ar_item_cd = [];
        txfg_ar_item_nm = [];
        txfg_ar_item_qty = [];
        txfg_ar_item_model = [];
        txfg_ar_si = [];
        txfg_ar_so = [];
        txfg_ar_sodt = [];
        txfg_ar_cnt_rm = [];
        document.getElementById("txfg_destoffice").value = '-';
        $("#txfg_tbltx tbody").empty();
        txfg_isedit_mode = false;
        document.getElementById('txfg_gt_rm').innerText = '';


        //DOKUMEN BC27
        document.getElementById('txfg_txt_noaju').value = "";
        document.getElementById('txfg_txt_nopen').value = "";
        document.getElementById('txfg_txt_tglpen').value = "";
        document.getElementById('txfg_fromoffice').value = "-";
        document.getElementById('txfg_destoffice').value = "-";
        document.getElementById('txfg_cmb_jenisTPB').value = "1"
        document.getElementById('txfg_cmb_jenisTPBtujuan').value = "1"
        document.getElementById('txfg_cmb_tujuanpengiriman').value = "-"
        document.getElementById('txfg_txt_nokontrak').value = "";
        document.getElementById('txfg_txt_tglkontrak').value = "";
        document.getElementById('txfg_txt_sppb27').value = ""
        document.getElementById('txfg_txt_pemberitahu27').value = ""
        document.getElementById('txfg_txt_jabatan27').value = ""

        //DOKUMEN BC25
        document.getElementById('txfg_txt_noaju25').value = "";
        document.getElementById('txfg_txt_nopen25').value = "";
        document.getElementById('txfg_txt_tglpen25').value = "";
        document.getElementById('txfg_fromoffice25').value = "-";
        document.getElementById('txfg_cmb_jenisTPB25').value = "-";
        document.getElementById('txfg_txt_noskb').value = "";
        document.getElementById('txfg_jenis_saranapengangkut25').value = "-";
        document.getElementById('txfg_cmb_tujuanpengiriman25').value = "-"
        document.getElementById('txfg_txt_sppb25').value = ""
        document.getElementById('txfg_businessgroup').disabled = false;
        document.getElementById('txfg_businessgroup').value = '-';
        document.getElementById('txfg_custname').value = '';
        document.getElementById('txfg_txt_pemberitahu25').value = ''
        document.getElementById('txfg_txt_jabatan25').value = ''

        scr_txfg_cust = "-";
        document.getElementById('txfg_consignee').value = '-'

        //DOKUMEN BC41
        document.getElementById('txfg_txt_nopen41').value = ''
        document.getElementById('txfg_txt_sppb41').value = ""
        document.getElementById('txfg_txt_pemberitahu41').value = ''
        document.getElementById('txfg_txt_jabatan41').value = ''


        let btnrm = document.getElementById('txfg_btn_rmstatus');
        btnrm.classList.remove(...btnrm.classList)
        btnrm.classList.add('btn', 'btn-outline-info')

        document.getElementById('txfg_btn_addsi').disabled = false
    });

    $("#txfg_btn_addsi").click(function(e) {
        let mcus = document.getElementById('txfg_custname').value;
        if (mcus.trim() == '') {
            alertify.warning('Please select customer first !');
            document.getElementById('txfg_btnfindmodcust').focus();
            return;
        }
        $("#txfg_sitbl tbody").empty();
        $('#TXFG_MODSI').modal('show');
    });

    function txfg_cksi_e_click(e, elem, pitem, pstrloc) {
        // if(e.ctrlKey){
        if (elem.checked) {
            let tabel_PLOT = document.getElementById("txfg_sitbl");
            let tabel_PLOT_body0 = tabel_PLOT.getElementsByTagName("tbody")[0];
            let ttlrows = tabel_PLOT_body0.getElementsByTagName('tr').length;
            let selectedloc = '';
            for (let i = 0; i < ttlrows; i++) {
                if (tabel_PLOT_body0.rows[i].cells[0].getElementsByTagName('input')[0].checked) {
                    selectedloc = tabel_PLOT_body0.rows[i].cells[11].innerText;
                    break;
                }
            }
            if (selectedloc == pstrloc) {
                for (let i = 0; i < ttlrows; i++) {
                    let sitem = tabel_PLOT_body0.rows[i].cells[3].innerText;
                    if (sitem == pitem) {
                        tabel_PLOT_body0.rows[i].cells[0].getElementsByTagName('input')[0].checked = true;
                    }
                }
            } else {
                alertify.message('Please check same str.Loc for a DO');
                elem.checked = false;
            }
        }
        // } else {
        //     // alertify.message('tidak cari');
        // }
    }

    function txfg_strloc_e_keypress(e) {
        if (e.which == 13) {
            txfg_e_searchSI();
        }
    }

    function txfg_e_searchSI() {
        let mkey = document.getElementById('txfg_sitxtsearch').value;
        let msearchby = document.getElementById('txfg_sisrchby').value
        let mbg = document.getElementById('txfg_businessgroup').value;
        let mstrloc = document.getElementById('txfg_strloc').value;
        if (msearchby == 'si') {
            if (mkey.length < 10) {
                alertify.message('please enter more specific SI Number')
                return
            }
        }
        document.getElementById('txfg_lbl_wait_si').innerText = 'Please wait ..';
        $.ajax({
            type: "get",
            url: "<?=base_url('SI/getsi')?>",
            data: {
                inkey: mkey,
                insearchby: msearchby,
                incus: scr_txfg_cust,
                inbg: mbg,
                instrloc: mstrloc
            },
            dataType: "json",
            success: function(response) {
                document.getElementById("txfg_sitbl").getElementsByTagName("tbody")[0].innerHTML = '';
                let ttlrows = response.data.length;
                document.getElementById('txfg_lbl_wait_si').innerText = ttlrows + ' row(s) found';
                if (ttlrows > 0) {
                    let mydes = document.getElementById("txfg_sidivku");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("txfg_sitbl");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let mckall = myfrag.getElementById("txfg_sickall");
                    let tabell = myfrag.getElementById("txfg_sitbl");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    let newrow, newcell, newText;
                    tableku2.innerHTML = '';
                    let tominqty = 0;
                    let tempqty = 0;
                    let todisqty = 0;
                    let surat_jalan = document.getElementById('txfg_txt_id').value.trim();
                    let surat_jalan_cc = surat_jalan.length;

                    for (let i = 0; i < ttlrows; i++) {
                        newrow = tableku2.insertRow(-1);
                        newcell = newrow.insertCell(0);
                        newText = document.createElement('input');
                        newText.setAttribute("type", "checkbox");
                        newText.disabled = (numeral(response.data[i].SI_DOCREFFPRC).value() == 0) ? true : false;
                        if (response.data[i].SER_DOC.includes('RTN')) {
                            newText.disabled = false
                        }
                        if (surat_jalan_cc > 3) {
                            newText.onclick = function(event) {
                                txfg_cksi_e_click(event, this, response.data[i].SER_ITMID.trim(), txfg_g_string)
                            };
                        } else {
                            newText.onclick = function(event) {
                                txfg_cksi_e_click(event, this, response.data[i].SER_ITMID.trim(), response.data[i].SI_OTHRMRK)
                            };
                        }
                        newcell.appendChild(newText);
                        newcell.style.cssText = ''.concat('cursor: pointer;', 'text-align:center;');
                        newcell = newrow.insertCell(1);
                        newcell.innerHTML = response.data[i].SI_DOC

                        newcell = newrow.insertCell(2);
                        newcell.innerHTML = response.data[i].MITM_BOXTYPE

                        newcell = newrow.insertCell(3);
                        newcell.innerHTML = response.data[i].SER_ITMID.trim()

                        newcell = newrow.insertCell(4);
                        newcell.innerHTML = response.data[i].MITM_ITMD1

                        newcell = newrow.insertCell(5);
                        newcell.style.cssText = 'text-align: right';
                        newcell.innerHTML = numeral(response.data[i].SISCN_SERQTY).format(',')

                        newcell = newrow.insertCell(6);
                        newcell.innerHTML = response.data[i].SISCN_SER

                        newcell = newrow.insertCell(7);
                        newcell.style.cssText = 'display:none';
                        newcell.innerHTML = response.data[i].SI_MDL

                        newcell = newrow.insertCell(8);
                        newcell.style.cssText = 'display:none';
                        newcell.innerHTML = response.data[i].SI_DOCREFFDT

                        newcell = newrow.insertCell(9);
                        newcell.style.cssText = 'text-align: right';
                        newcell.innerHTML = (response.data[i].SI_DOCREFFPRC.substr(0, 1) == '.' ? '0' : '') + response.data[i].SI_DOCREFFPRC

                        newcell = newrow.insertCell(10);
                        newcell.innerHTML = response.data[i].SER_DOC

                        newcell = newrow.insertCell(11);
                        newcell.innerHTML = response.data[i].SI_OTHRMRK
                    }
                    let mrows = tableku2.getElementsByTagName("tr");

                    function clooptable() {
                        let cktemp, txtprice, txtstrloc;
                        let astrloc = [];
                        for (let x = 0; x < mrows.length; x++) {
                            cktemp = tableku2.rows[x].cells[0].getElementsByTagName('input')[0];
                            txtprice = tableku2.rows[x].cells[9].innerText;
                            txtstrloc = tableku2.rows[x].cells[11].innerText.trim();
                            if (numeral(txtprice).value() > 0) { // validate if price > 0
                                cktemp.checked = mckall.checked;
                            }
                            if (!astrloc.includes(txtstrloc)) {
                                astrloc.push(txtstrloc);
                            }
                        }
                        if (astrloc.length > 1) {
                            for (let x = 0; x < mrows.length; x++) {
                                tableku2.rows[x].cells[0].getElementsByTagName('input')[0].checked = false;
                            }
                            alertify.warning('Only 1 Str. Loc allowed');
                        }
                    }
                    mckall.onclick = function() {
                        clooptable()
                    };
                    mydes.innerHTML = ''
                    mydes.appendChild(myfrag)
                } else {
                    alertify.warning('SI Doc not found');
                }
            },
            error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow);
            }
        });
    }
    $("#txfg_sitxtsearch").keypress(function(e) {
        if (e.which == 13) {
            txfg_e_searchSI()
        }
    })
    $("#txfg_divku").css('height', (screen.height * 30 / 100));
    $("#txfg_sidivku").css('height', $(window).height() * 53 / 100);
    $("#txfg_divdetailser").css('height', $(window).height() * 30 / 100);
    $("#txfg_divku").css('height', $(window).height() -
        document.getElementById('txfg_stack0').offsetHeight -
        100);
    $("#TXFG_MODSI").on('shown.bs.modal', function() {
        document.getElementById('txfg_sitxtsearch').focus();
    });
    $("#txfg_sibtngetselected").click(function(e) {
        let tabell = document.getElementById("txfg_sitbl");
        let tableku2 = tabell.getElementsByTagName("tbody")[0];
        let mrows = tableku2.getElementsByTagName("tr");
        let cktemp, ttlcek;
        ttlcek = 0;
        if (txfg_isedit_mode) {

        } else {
            txfg_ar_item_ser = [];
            txfg_ar_item_cd = [];
            txfg_ar_item_nm = [];
            txfg_ar_item_qty = [];
            txfg_ar_item_model = [];
            txfg_ar_so = [];
            txfg_ar_sodt = [];
            txfg_ar_item_job = [];
            txfg_ar_cnt_rm = [];
        }

        for (let x = 0; x < mrows.length; x++) {
            cktemp = tableku2.rows[x].cells[0].getElementsByTagName('input')[0];
            if (cktemp.checked) {
                txfg_ar_item_ser.push(tableku2.rows[x].cells[6].innerText);
                txfg_ar_item_cd.push(tableku2.rows[x].cells[3].innerText);
                txfg_ar_item_nm.push(tableku2.rows[x].cells[4].innerText);
                txfg_ar_item_qty.push(tableku2.rows[x].cells[5].innerText);
                txfg_ar_item_model.push(tableku2.rows[x].cells[7].innerText);
                txfg_ar_so.push(tableku2.rows[x].cells[2].innerText);
                txfg_ar_sodt.push(tableku2.rows[x].cells[8].innerText);
                txfg_ar_item_job.push(tableku2.rows[x].cells[10].innerText);
                txfg_ar_cnt_rm.push(0);
                ttlcek++;
            }
        }

        if (ttlcek > 0) {
            if( txfg_ar_item_cd[0].toUpperCase().includes('ASP') ) {
                txfg_consignee.value = 'IEI-ASP'
            }
            if (txfg_isedit_mode) {
                ttlcek += txfg_ttlcek;
            }
            $("#TXFG_MODSI").modal('hide');
            tabell = document.getElementById("txfg_tbltx");
            tableku2 = tabell.getElementsByTagName("tbody")[0];
            let newrow, newcell, newText;
            let tmp_ar_item_box = [];
            let tmp_ar_item_cd = [];
            let tmp_ar_item_nm = [];
            let tmp_ar_item_qty = [];
            let tmp_ar_item_model = [];
            let tmp_ar_si = [];
            let tmp_ar_so = [];
            let tmp_ar_sodt = [];
            tableku2.innerHTML = '';
            let flagisexist = false;
            for (let i = 0; i < ttlcek; i++) {
                if (tmp_ar_item_cd.length == 0) {
                    tmp_ar_item_box.push(1);
                    tmp_ar_item_cd.push(txfg_ar_item_cd[i]);
                    tmp_ar_item_nm.push(txfg_ar_item_nm[i]);
                    tmp_ar_item_qty.push(numeral(txfg_ar_item_qty[i]).value());
                    tmp_ar_item_model.push(txfg_ar_item_model[i]);
                    tmp_ar_si.push(txfg_ar_si[i]);
                    tmp_ar_so.push(txfg_ar_so[i]);
                    tmp_ar_sodt.push(txfg_ar_sodt[i]);
                } else {
                    flagisexist = false;
                    for (let k = 0; k < tmp_ar_item_cd.length; k++) {
                        if ((tmp_ar_so[k] == txfg_ar_so[i]) && (tmp_ar_item_cd[k] == txfg_ar_item_cd[i]) && (tmp_ar_si[k] == txfg_ar_si[i]) && (numeral(tmp_ar_item_qty[k]).value() == numeral(txfg_ar_item_qty[i]).value())) {
                            tmp_ar_item_box[k] = tmp_ar_item_box[k] + 1;
                            flagisexist = true;
                            break;
                        }
                    }
                    if (flagisexist) {} else {
                        tmp_ar_item_box.push(1);
                        tmp_ar_item_cd.push(txfg_ar_item_cd[i]);
                        tmp_ar_item_nm.push(txfg_ar_item_nm[i]);
                        tmp_ar_item_qty.push(numeral(txfg_ar_item_qty[i]).value());
                        tmp_ar_item_model.push(txfg_ar_item_model[i]);
                        tmp_ar_si.push(txfg_ar_si[i]);
                        tmp_ar_so.push(txfg_ar_so[i]);
                        tmp_ar_sodt.push(txfg_ar_sodt[i]);
                    }
                }
            }

            let tempso, tempitem, tempitem2;
            let dsptempso, dspitem, dspsodt, dspitemnm, dspcustpart;
            for (let i = 0; i < tmp_ar_item_cd.length; i++) {
                if (tempso != tmp_ar_so[i]) {
                    tempso = tmp_ar_so[i];
                    tempitem = tmp_ar_item_cd[i];
                    dsptempso = tmp_ar_so[i];
                    dspsodt = tmp_ar_sodt[i];
                    dspitem = tmp_ar_item_cd[i];
                    dspitemnm = tmp_ar_item_nm[i];
                    dspcustpart = tmp_ar_item_model[i];
                } else {
                    dsptempso = '';
                    dspsodt = '';
                    if (tempitem != tmp_ar_item_cd[i]) {
                        tempitem = tmp_ar_item_cd[i];
                        dspitem = tmp_ar_item_cd[i];
                        dspitemnm = tmp_ar_item_nm[i];
                        dspcustpart = tmp_ar_item_model[i];
                    } else {
                        dspitem = '';
                        dspitemnm = '';
                        dspcustpart = '';
                    }
                }
                newrow = tableku2.insertRow(-1);
                newcell = newrow.insertCell(0);
                newcell.innerHTML = dsptempso
                newcell = newrow.insertCell(1);
                newcell.innerHTML = dspsodt
                newcell = newrow.insertCell(2);
                newcell.innerHTML = dspitem

                newcell = newrow.insertCell(3);
                newcell.innerHTML = dspitemnm

                newcell = newrow.insertCell(4);
                newcell.style.cssText = 'text-align: right';

                newcell = newrow.insertCell(5);
                newcell.style.cssText = ''.concat('text-align: right;', 'cursor: pointer;');
                newcell.innerHTML = numeral(tmp_ar_item_box[i]).format(',')

                newcell = newrow.insertCell(6);
                newcell.style.cssText = 'text-align: right';
                newcell.innerHTML = tmp_ar_item_qty[i]

                newcell = newrow.insertCell(7);
                newcell.style.cssText = 'text-align: right';

            }
            txfg_e_calculate_raw_material_resume();
            txfg_e_last_resume();
        } else {
            alertify.message('No data selected');
        }
    });

    $("#txfg_btn_save").click(function(e) {
        if (txfg_gt_rm.innerText == 'Please wait') {
            alertify.warning('Please wait')
            return
        } else {
            if (numeral(txfg_gt_rm.innerText).value() > 900) {
                alertify.warning('Maximum RM 900 per Document')
                return
            }
        }
        let mtxid = document.getElementById('txfg_txt_id').value;
        let mtxcustomerDO = document.getElementById('txfg_txt_customerDO').value;
        let mtxdt = document.getElementById('txfg_txt_custdate').value;
        let mtxinv = document.getElementById('txfg_txt_invno').value;
        let mtxdodt = document.getElementById('txfg_txt_DOdate').value;
        let mtxconsig = document.getElementById('txfg_consignee').value;
        let mtxtransport = document.getElementById('txfg_txt_transport').value;
        let mtxinvsmt = document.getElementById('txfg_txt_invsmt').value;
        let mtxremark = document.getElementById('txfg_txt_remark').value;
        let mbg = document.getElementById('txfg_businessgroup').value;
        let mcustomdoc = document.getElementById('txfg_cmb_bcdoc').value;

        if (mcustomdoc == '-') {
            alertify.message('Please select customs document first!');
            document.getElementById('txfg_cmb_bcdoc').focus();
            return;
        }

        if (mcustomdoc == '25' && mtxconsig != 'STX') {
            alertify.warning('For now BC2.5 FG is only for dedicated consignee')
            return
        }

        if (mbg == '-') {
            alertify.message('Please select business group!');
            document.getElementById('txfg_businessgroup').focus();
            return;
        }

        if (scr_txfg_cust.trim() == '') {
            alertify.warning('Please select Customer first');
            return;
        }
        if (mtxtransport.trim() == '-') {
            alertify.warning('Please select Transportation');
            return;
        }
        let atransport = mtxtransport.split('_');
        mtxtransport = atransport[0];
        let ttlserials = txfg_ar_item_ser.length;
        if (ttlserials <= 0) {
            alertify.message('nothing to be saved');
            return;
        }

        if (mtxconsig == '-') {
            alertify.message("Please select conignee");
            document.getElementById('txfg_consignee').focus();
            return;
        }

        //PLOT SO
        const so_tbl = document.getElementById('txfg_tblso_plot').getElementsByTagName('tbody')[0]
        const so_tbl_rows = so_tbl.getElementsByTagName('tr').length
        const so_oth_tbl = document.getElementById('txfg_tblsoother_plot').getElementsByTagName('tbody')[0]
        const so_oth_tbl_rows = so_oth_tbl.getElementsByTagName('tr').length
        let aXSO = []
        let aXSOLine = []
        let aXQty = []
        let aXSILine = []


        let aSO = []
        let aItem = []
        let aQty = []

        for (let i = 0; i < so_oth_tbl_rows; i++) {
            const qty = numeral(so_oth_tbl.rows[i].cells[3].innerText.trim()).value()
            aQty.push(qty)
            aSO.push(so_oth_tbl.rows[i].cells[0].innerText.trim())
            aItem.push(so_oth_tbl.rows[i].cells[1].innerText.trim())
        }
        for (let i = 0; i < so_tbl_rows; i++) {
            const qty = numeral(so_tbl.rows[i].cells[5].innerText.trim()).value()
            aXQty.push(qty)
            aXSO.push(so_tbl.rows[i].cells[0].innerText.trim())
            aXSOLine.push(so_tbl.rows[i].cells[1].innerText.trim())
            aXSILine.push(so_tbl.rows[i].cells[6].innerText)
        }

        if (txfg_isedit_mode) {
            if (confirm("Are you sure ?")) {
                txfg_btn_save.disabled = true
                $.ajax({
                    type: "POST",
                    url: "<?=base_url('DELV/edit')?>",
                    data: {
                        intxid: mtxid,
                        intxdt: mtxdt,
                        ininv: mtxinv,
                        ininvsmt: mtxinvsmt,
                        inconsig: mtxconsig,
                        incus: scr_txfg_cust,
                        incustdo: mtxcustomerDO,
                        iconfirmation: 'n',
                        intrans: mtxtransport,
                        inremark: mtxremark,
                        inbg: mbg,
                        incustoms_doc: mcustomdoc,
                        ina_ser: txfg_ar_item_ser,
                        ina_qty: txfg_ar_item_qty,
                        ina_si: txfg_ar_si,
                        ina_so: txfg_ar_so,
                        indodt: mtxdodt,
                        ina_sono: aSO,
                        ina_soitem: aItem,
                        ina_soqty: aQty,
                        xa_qty: aXQty,
                        xa_so: aXSO,
                        xa_soline: aXSOLine,
                        xa_siline: aXSILine
                    },
                    dataType: "json",
                    success: function(response) {
                        txfg_btn_save.disabled = false
                        switch (response[0].cd) {
                            case '00':
                                alertify.warning(response[0].msg);
                                break;
                            case '11':
                                alertify.success(response[0].msg);
                                break;
                            case '22':
                                if (confirm(response[0].msg)) {
                                    txfg_btn_save.disabled = true
                                    $.ajax({
                                        type: "POST",
                                        url: "<?=base_url('DELV/edit')?>",
                                        data: {
                                            intxid: mtxid,
                                            intxdt: mtxdt,
                                            ininv: mtxinv,
                                            ininvsmt: mtxinvsmt,
                                            inconsig: mtxconsig,
                                            incus: scr_txfg_cust,
                                            incustdo: mtxcustomerDO,
                                            iconfirmation: 'y',
                                            intrans: mtxtransport,
                                            inremark: mtxremark,
                                            inbg: mbg,
                                            incustoms_doc: mcustomdoc,
                                            ina_ser: txfg_ar_item_ser,
                                            ina_qty: txfg_ar_item_qty,
                                            ina_si: txfg_ar_si,
                                            ina_so: txfg_ar_so,
                                            indodt: mtxdodt,
                                            ina_sono: aSO,
                                            ina_soitem: aItem,
                                            ina_soqty: aQty,
                                            xa_qty: aXQty,
                                            xa_so: aXSO,
                                            xa_soline: aXSOLine,
                                            xa_siline: aXSILine
                                        },
                                        dataType: "json",
                                        success: function(response) {
                                            txfg_btn_save.disabled = false
                                            switch (response[0].cd) {
                                                case '00':
                                                    alertify.warning(response[0].msg);
                                                    break;
                                                case '11':
                                                    alertify.success(response[0].msg);
                                                    break;
                                                default:
                                                    alertify.warning(response[0].msg);
                                            }
                                        },
                                        error: function(xhr, xopt, xthrow) {
                                            alertify.error(xthrow)
                                            txfg_btn_save.disabled = false
                                        }
                                    })
                                }
                                break;
                        }
                    },
                    error: function(xhr, xopt, xthrow) {
                        alertify.error(xthrow)
                        txfg_btn_save.disabled = false
                    }
                })
            }
        } else {
            if (confirm("Are You sure ?")) {
                txfg_btn_save.disabled = true
                $.ajax({
                    type: "POST",
                    url: "<?=base_url('DELV/set')?>",
                    data: {
                        intxid: mtxid,
                        intxdt: mtxdt,
                        ininv: mtxinv,
                        ininvsmt: mtxinvsmt,
                        inconsig: mtxconsig,
                        incus: scr_txfg_cust,
                        incustdo: mtxcustomerDO,
                        incustoms_doc: mcustomdoc,
                        iconfirmation: 'n',
                        intrans: mtxtransport,
                        inremark: mtxremark,
                        inbg: mbg,
                        ina_ser: txfg_ar_item_ser,
                        ina_qty: txfg_ar_item_qty,
                        ina_si: txfg_ar_si,
                        ina_so: txfg_ar_so,
                        indodt: mtxdodt,
                        ina_sono: aSO,
                        ina_soitem: aItem,
                        ina_soqty: aQty
                    },
                    dataType: "json",
                    success: function(response) {
                        txfg_btn_save.disabled = false
                        switch (response[0].cd) {
                            case '00':
                                alertify.warning(response[0].msg);
                                break;
                            case '11':
                                document.getElementById('txfg_txt_id').value = response[0].dono;
                                txfg_f_getdetail(response[0].dono);
                                alertify.success(response[0].msg);
                                break;
                            case '22':
                                if (confirm(response[0].msg)) {
                                    txfg_btn_save.disabled = true
                                    $.ajax({
                                        type: "POST",
                                        url: "<?=base_url('DELV/set')?>",
                                        data: {
                                            intxid: mtxid,
                                            intxdt: mtxdt,
                                            ininv: mtxinv,
                                            ininvsmt: mtxinvsmt,
                                            inconsig: mtxconsig,
                                            incus: scr_txfg_cust,
                                            incustdo: mtxcustomerDO,
                                            incustoms_doc: mcustomdoc,
                                            iconfirmation: 'y',
                                            intrans: mtxtransport,
                                            inremark: mtxremark,
                                            inbg: mbg,
                                            ina_ser: txfg_ar_item_ser,
                                            ina_qty: txfg_ar_item_qty,
                                            ina_si: txfg_ar_si,
                                            ina_so: txfg_ar_so,
                                            indodt: mtxdodt,
                                            ina_sono: aSO,
                                            ina_soitem: aItem,
                                            ina_soqty: aQty
                                        },
                                        dataType: "json",
                                        success: function(response) {
                                            txfg_btn_save.disabled = false
                                            switch (response[0].cd) {
                                                case '00':
                                                    alertify.warning(response[0].msg);
                                                    break;
                                                case '11':
                                                    document.getElementById('txfg_txt_id').value = response[0].dono;
                                                    txfg_f_getdetail(response[0].dono);
                                                    alertify.success(response[0].msg);
                                                    break;
                                                default:
                                                    alertify.warning(response[0].msg);
                                            }
                                        },
                                        error: function(xhr, xopt, xthrow) {
                                            alertify.error(xthrow)
                                            txfg_btn_save.disabled = false
                                        }
                                    })
                                }
                                break;
                        }
                    },
                    error: function(xhr, xopt, xthrow) {
                        alertify.error(xthrow)
                        txfg_btn_save.disabled = false
                    }
                })
            }
        }

    });
    $("#txfg_btn_findmod").click(function(e) {
        $('#TXFG_MODSAVED').modal('show');
    });
    $("#TXFG_MODSAVED").on('shown.bs.modal', function() {
        $("#txfg_txtxtsearch").focus();
    });
    $("#TXFG_MODSAVED").on('hidden.bs.modal', function() {
        $("#txfg_txtbl tbody").empty();
    });

    $("#txfg_txtxtsearch").keypress(function(e) {
        if (e.which == 13) {
            let mkeys = $(this).val().trim();
            let ms_by = document.getElementById('txfg_txsrchby').value;
            $("#txfg_txtbl tbody").empty();
            document.getElementById("txfg_lbl_wait_saved_tx").innerText = "Please wait...";
            let searchperiod = document.getElementById('txfg_ckperiod').checked ? 1 : 0;
            let monthperiod = document.getElementById('txfg_monthfilter').value;
            let yearperiod = document.getElementById('txfg_year').value;
            $.ajax({
                type: "get",
                url: "<?=base_url('DELV/search')?>",
                data: {
                    inkey: mkeys,
                    insearchby: ms_by,
                    insearchperiod: searchperiod,
                    inmonth: monthperiod,
                    inyear: yearperiod,
                    insearchtype: '1'
                },
                dataType: "json",
                success: function(response) {
                    let ttlrows = response.data.length;
                    document.getElementById("txfg_lbl_wait_saved_tx").innerText = "";
                    if (ttlrows > 0) {
                        let mydes = document.getElementById("txfg_txdivku");
                        let myfrag = document.createDocumentFragment();
                        let mtabel = document.getElementById("txfg_txtbl");
                        let cln = mtabel.cloneNode(true);
                        myfrag.appendChild(cln);
                        let tabell = myfrag.getElementById("txfg_txtbl");
                        let tableku2 = tabell.getElementsByTagName("tbody")[0];
                        let newrow, newcell, newText;
                        tableku2.innerHTML = '';
                        let tominqty = 0;
                        let tempqty = 0;
                        let todisqty = 0;
                        for (let i = 0; i < ttlrows; i++) {
                            newrow = tableku2.insertRow(-1);
                            newcell = newrow.insertCell(0);
                            newcell.innerHTML = response.data[i].DLV_ID
                            newcell = newrow.insertCell(1);
                            newcell.innerHTML = response.data[i].DLV_DATE
                            newcell = newrow.insertCell(2);
                            newcell.style.cssText = 'display:none';
                            newcell.innerHTML = response.data[i].DLV_DSCRPTN

                            newcell = newrow.insertCell(3);
                            newcell.style.cssText = 'display:none';
                            newcell.innerHTML = response.data[i].DLV_CUSTCD

                            newcell = newrow.insertCell(4);
                            newcell.classList.add('d-none')
                            newcell.innerHTML = response.data[i].MCUS_CUSNM

                            newcell = newrow.insertCell(5);
                            newcell.innerHTML = response.data[i].DLV_INVNO

                            newcell = newrow.insertCell(6);
                            newcell.innerHTML = response.data[i].DLV_SMTINVNO

                            newcell = newrow.insertCell(7);
                            newcell.innerHTML = response.data[i].DLV_TRANS

                            newcell = newrow.insertCell(8);
                            newcell.innerHTML = response.data[i].DLV_RMRK

                            newcell = newrow.insertCell(9);
                            newcell.style.cssText = 'display:none';
                            newcell.innerHTML = response.data[i].MCUS_CURCD

                            newcell = newrow.insertCell(10);
                            newcell.style.cssText = 'display:none';
                            newcell.innerHTML = response.data[i].MSTTRANS_TYPE

                            newcell = newrow.insertCell(11);
                            newcell.innerHTML = response.data[i].DLV_CONSIGN

                            newcell = newrow.insertCell(12);
                            newcell.style.cssText = 'display:none';
                            newcell.innerHTML = response.data[i].DLV_RPLCMNT

                            newcell = newrow.insertCell(13);
                            newcell.style.cssText = 'display:none';
                            newcell.innerHTML = response.data[i].DLV_VAT

                            newcell = newrow.insertCell(14);
                            newcell.style.cssText = 'display:none';
                            newcell.innerHTML = response.data[i].DLV_KNBNDLV

                            newcell = newrow.insertCell(15);
                            newcell.style.cssText = 'display:none';
                            newcell.innerHTML = response.data[i].DLV_FROMOFFICE

                            newcell = newrow.insertCell(16);
                            newcell.style.cssText = 'display:none';
                            newcell.innerHTML = response.data[i].DLV_CRTD
                            newcell = newrow.insertCell(17);
                            newcell.style.cssText = 'display:none';
                            newcell.innerHTML = response.data[i].DLV_CRTDTM

                            newcell = newrow.insertCell(18);
                            newcell.style.cssText = 'display:none';
                            newcell.innerHTML = response.data[i].DLV_USRID
                            newcell = newrow.insertCell(19);
                            newcell.style.cssText = 'display:none';
                            newcell.innerHTML = response.data[i].DLV_LUPDT

                            newcell = newrow.insertCell(20);
                            newcell.style.cssText = 'display:none';
                            newcell.innerHTML = response.data[i].DLV_APPRV
                            newcell = newrow.insertCell(21);
                            newcell.style.cssText = 'display:none';
                            newcell.innerHTML = response.data[i].DLV_APPRVTM
                            newcell = newrow.insertCell(22);
                            newcell.style.cssText = 'display:none';
                            newcell.innerHTML = response.data[i].DLV_POST
                            newcell = newrow.insertCell(23);
                            newcell.style.cssText = 'display:none';
                            newcell.innerHTML = response.data[i].DLV_POSTTM
                            newcell = newrow.insertCell(24);
                            newcell.style.cssText = 'display:none';
                            newcell.innerHTML = response.data[i].DLV_NOAJU
                            newcell = newrow.insertCell(25);
                            newcell.innerHTML = response.data[i].DLV_BCTYPE
                            newcell = newrow.insertCell(26);
                            newcell.style.cssText = 'display:none';
                            newcell.innerHTML = response.data[i].DLV_NOPEN
                            newcell = newrow.insertCell(27);
                            newcell.style.cssText = 'display:none';
                            newcell.innerHTML = response.data[i].DLV_DESTOFFICE
                            newcell = newrow.insertCell(28);
                            newcell.style.cssText = 'display:none';
                            newcell.innerHTML = response.data[i].DLV_PURPOSE
                            newcell = newrow.insertCell(29);
                            newcell.style.cssText = 'display:none';
                            newcell.innerHTML = response.data[i].DLV_INVDT
                            newcell = newrow.insertCell(30);
                            newcell.innerHTML = response.data[i].DLV_BSGRP
                            newcell = newrow.insertCell(31);
                            newcell.innerHTML = response.data[i].DLV_CUSTDO
                            newcell = newrow.insertCell(32);
                            newcell.style.cssText = 'display:none';
                            newcell.innerHTML = response.data[i].DLV_ZJENIS_TPB_ASAL
                            newcell = newrow.insertCell(33);
                            newcell.style.cssText = 'display:none';
                            newcell.innerHTML = response.data[i].DLV_ZJENIS_TPB_TUJUAN
                            newcell = newrow.insertCell(34);
                            newcell.innerHTML = response.data[i].DLV_BCDATE
                            newcell = newrow.insertCell(35);
                            newcell.style.cssText = 'display:none';
                            newcell.innerHTML = response.data[i].DLV_ZSKB
                            newcell = newrow.insertCell(36);
                            newcell.style.cssText = 'display:none';
                            newcell.innerHTML = response.data[i].DLV_ZKODE_CARA_ANGKUT

                            newcell = newrow.insertCell(37);
                            newcell.style.cssText = 'display:none';
                            newcell.innerHTML = response.data[i].DLV_ZTANGGAL_SKB

                            let mstatus = '';
                            let mposted = String(response.data[i].DLV_POST);
                            let mapproved = String(response.data[i].DLV_APPRV);
                            let mcreated = String(response.data[i].DLV_CRTD);
                            if ((mposted == 'null') || (mposted.trim() == '')) {
                                if ((mapproved == 'null') || (mapproved.trim() == '')) {
                                    if ((mcreated == 'null') || (mcreated.trim() == '')) {

                                    } else {
                                        mstatus = "Saved";
                                    }
                                } else {
                                    mstatus = "Approved";
                                }
                            } else {
                                mstatus = "Posted";
                            }

                            newcell = newrow.insertCell(38);
                            newcell.innerHTML = mstatus

                            let pdata = {
                                ctxid: response.data[i].DLV_ID,
                                ctxdt: response.data[i].DLV_DATE,
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
                                cajudt: response.data[i].DLV_BCDATE,
                                cskb: response.data[i].DLV_ZSKB,
                                cnamapengangkut: response.data[i].DLV_ZKODE_CARA_ANGKUT,
                                cskbdt: response.data[i].DLV_ZTANGGAL_SKB,
                                ccona: response.data[i].DLV_CONA,
                                csppbdoc: response.data[i].DLV_SPPBDOC,
                                cPemberitahu: response.data[i].DLVH_PEMBERITAHU,
                                cJabatan: response.data[i].DLVH_JABATAN
                            }
                            newrow.onclick = () => cclick_hnd(pdata)
                        }

                        function cclick_hnd(mrow) {
                            txfg_isRecalculateFunAlreadytried = false
                            let mtxid = mrow.ctxid;
                            let mtxdt = mrow.ctxdt;
                            let mdescript = mrow.cdescription;
                            scr_txfg_cust = mrow.ccustcd;
                            let mcusnm = mrow.ccustnm;
                            let minv = mrow.cinv;
                            let minvsmt = mrow.cinvsmt;
                            let mtrans = mrow.ctrans;
                            let mremark = mrow.cremark;
                            let mcurrency = mrow.ccurrency;
                            let mtrans_type = mrow.ctranstype;
                            let mconsign = mrow.cconsign;
                            let mfromoffice = mrow.cfromoffice;
                            let mcreated = mrow.ccreatedby;
                            let mcreatedtime = mrow.ccreateddt;
                            let mupdated = mrow.cupdatedby ? mrow.cupdatedby : '';
                            let mupdatedtime = mrow.cupdateddt ? mrow.cupdateddt : '';
                            let mapproved = mrow.capprovedby ? mrow.capprovedby : '';
                            let mapprovedtime = mrow.capproveddt ? mrow.capproveddt : '';
                            let mposted = mrow.cpostedby ? mrow.cpostedby : '';
                            let mpostedtime = mrow.cposteddt ? mrow.cposteddt : '';
                            let mnoaju = mrow.cnoaju;
                            let mbctype = mrow.cbctype;
                            let mnopen = mrow.cnopen ? mrow.cnopen : '';
                            let mdestoffice = mrow.cdestoffice;
                            let mpurpose = mrow.cpurpose;
                            let minvdt = mrow.cinvdt;
                            let mbg = mrow.cbg;
                            let mcustdo = mrow.ccustdo;
                            let mtpb_asal = mrow.ctpb_asal;
                            let mtpb_tujuan = mrow.ctpb_tujuan;
                            let mtanggalAju = mrow.cajudt;
                            let mskb = mrow.cskb;
                            let mnama_pengangkut = mrow.cnamapengangkut;
                            let mskb_tgl = mrow.cskbdt;
                            const mcona = mrow.ccona;
                            txfg_tblsoother_plot.getElementsByTagName('tbody').innerHTML = ''
                            txfg_txt_remark.value = mremark
                            txfg_txt_id.focus();
                            txfg_txt_id.value = mtxid;
                            txfg_modchangeprice_txid.innerText = mtxid;
                            txfg_txt_id.readOnly = true;
                            txfg_custname.value = mcusnm;
                            txfg_custname.readOnly = true;
                            txfg_curr.value = mcurrency;
                            txfg_txt_invno.value = minv;
                            txfg_txt_invsmt.value = minvsmt;
                            txfg_txt_transport.value = mtrans + '_' + mtrans_type;
                            txfg_txt_transporttype.value = mtrans_type;
                            txfg_businessgroup.value = mbg;
                            txfg_businessgroup.disabled = true;
                            txfg_txt_customerDO.value = mcustdo;
                            $("#txfg_txt_custdate").datepicker('update', mtanggalAju);
                            $("#txfg_txt_DOdate").datepicker('update', mtxdt);

                            const postedtime = ((mpostedtime == 'null') || (mpostedtime.trim() == '') ? '' : mpostedtime)
                            txfg_txt_createdby.value = mcreated;
                            txfg_txt_createdtime.value = mcreatedtime;
                            txfg_txt_luby.value = ((mupdated == 'null') || (mupdated.trim() == '') ? '' : mupdated);
                            txfg_txt_lutime.value = ((mupdatedtime == 'null') || (mupdatedtime.trim() == '') ? '' : mupdatedtime);
                            txfg_txt_apprby.value = ((mapproved == 'null') || (mapproved.trim() == '') ? '' : mapproved);
                            txfg_txt_apprtime.value = ((mapprovedtime == 'null') || (mapprovedtime.trim() == '') ? '' : mapprovedtime);
                            txfg_txt_postedby.value = ((mposted == 'null') || (mposted.trim() == '') ? '' : mposted);
                            txfg_txt_postedtime.value = postedtime

                            txfg_cmb_bcdoc.value = mbctype;
                            txfg_destoffice.value = mdestoffice;

                            if (postedtime != '') {
                                txfg_btn_post_cancel.classList.remove('disabled')
                            } else {
                                txfg_btn_post_cancel.classList.add('disabled')
                            }

                            if ((mposted == 'null') || (mposted.trim() == '')) {
                                txfg_btn_addsi.disabled = false
                                if ((mapproved == 'null') || (mapproved.trim() == '')) {
                                    if ((mcreated == 'null') || (mcreated.trim() == '')) {

                                    } else {
                                        txfg_status.value = "Saved";
                                    }
                                } else {
                                    txfg_status.value = "Approved";
                                }
                            } else {
                                txfg_btn_addsi.disabled = true
                                txfg_status.value = "Posted";
                            }

                            if (mbctype == '27') {
                                txfg_txt_noaju.value = mnoaju.trim();
                                txfg_fromoffice.value = mfromoffice;
                                txfg_cmb_jenisTPB.value = (mtpb_asal === '-') ? '1' : mtpb_asal;
                                txfg_cmb_jenisTPBtujuan.value = (mtpb_tujuan === '-') ? '1' : mtpb_tujuan
                                txfg_cmb_tujuanpengiriman.value = mpurpose;
                                txfg_txt_nopen.value = mnopen.trim()
                                txfg_txt_nokontrak.value = mcona
                                txfg_txt_sppb27.value = mrow.csppbdoc
                                txfg_txt_tglpen.value = mrow.cTglpen ? mrow.cTglpen : ''
                                if (mrow.cPemberitahu) {
                                    txfg_txt_pemberitahu27.value = mrow.cPemberitahu
                                }
                                if (mrow.cJabatan) {
                                    txfg_txt_jabatan27.value = mrow.cJabatan
                                }
                            } else if (mbctype == '25') {
                                document.getElementById('txfg_txt_noaju25').value = mnoaju.trim();
                                document.getElementById('txfg_fromoffice25').value = mfromoffice;
                                document.getElementById('txfg_cmb_jenisTPB25').value = mtpb_asal;
                                document.getElementById('txfg_txt_noskb').value = mskb;
                                document.getElementById('txfg_cmb_tujuanpengiriman25').value = mpurpose;
                                $("#txfg_txt_noskb_tgl").datepicker('update', mskb_tgl);
                                document.getElementById('txfg_jenis_saranapengangkut25').value = mnama_pengangkut;
                                document.getElementById('txfg_txt_nopen25').value = mnopen.trim()
                                document.getElementById('txfg_txt_sppb25').value = mrow.csppbdoc
                                document.getElementById('txfg_txt_tglpen25').value = mrow.cTglpen ? mrow.cTglpen : ''
                                if (mrow.cPemberitahu) {
                                    document.getElementById('txfg_txt_pemberitahu25').value = mrow.cPemberitahu
                                }
                                if (mrow.cJabatan) {
                                    document.getElementById('txfg_txt_jabatan25').value = mrow.cJabatan
                                }
                            } else if (mbctype == '41') {
                                document.getElementById('txfg_txt_noaju41').value = mnoaju.trim();
                                document.getElementById('txfg_fromoffice41').value = mfromoffice;
                                document.getElementById('txfg_cmb_jenisTPB41').value = mtpb_asal;
                                document.getElementById('txfg_cmb_tujuanpengiriman41').value = mpurpose;
                                document.getElementById('txfg_txt_nokontrak41').value = mcona
                                document.getElementById('txfg_txt_nopen41').value = mnopen.trim()
                                document.getElementById('txfg_txt_sppb41').value = mrow.csppbdoc
                                document.getElementById('txfg_txt_tglpen41').value = mrow.cTglpen ? mrow.cTglpen : ''
                                if (mrow.cPemberitahu) {
                                    document.getElementById('txfg_txt_pemberitahu41').value = mrow.cPemberitahu
                                }
                                if (mrow.cJabatan) {
                                    document.getElementById('txfg_txt_jabatan41').value = mrow.cJabatan
                                }
                            }
                            $("#TXFG_MODSAVED").modal('hide');
                            txfg_getconsignee(scr_txfg_cust, mconsign);
                            txfg_f_getdetail(mtxid);
                            document.getElementById('txfg_tblsoother_plot').getElementsByTagName('tbody')[0].innerHTML = ``
                        }
                        mydes.innerHTML = '';
                        mydes.appendChild(myfrag);
                    } else {
                        alertify.warning('Transaction is not found');
                    }
                },
                error: function(xhr, xopt, xthrow) {
                    alertify.error(xthrow);
                    document.getElementById("txfg_lbl_wait_saved_tx").innerText = "";
                }
            });
        }
    });


    $("#txfg_btn_print").click(function(e) {
        let mtxid = document.getElementById('txfg_txt_id').value;
        let mapprovedby = document.getElementById('txfg_txt_apprby').value;
        if (mtxid.trim() == '') {
            alertify.warning('Delivery Note could not be empty');
            document.getElementById('txfg_txt_id').focus();
            return;
        }
        if (mapprovedby.trim() == '') {
            alertify.warning('Please approve first !');
            document.getElementById('txfg_txt_apprby').focus();
            return;
        }
        $("#TXFG_MODPRINT").modal('show');
    })


    $("#txfg_btnprintseldocs").click(function(e) {
        let txid = document.getElementById('txfg_txt_id').value;
        let mckdo = (document.getElementById('txfg_ckDO').checked) ? '1' : '0';
        let mckinv = (document.getElementById('txfg_ckINV').checked) ? '1' : '0';
        let mckpl = (document.getElementById('txfg_ckPL').checked) ? '1' : '0';
        let mckBarcode = (document.getElementById('txfg_ckBarcode').checked) ? '1' : '0';

        if ((mckdo + mckinv + mckpl) == '000') {
            alertify.message('Please select document first');
            return;
        }
        if (txid.trim() == '') {
            alertify.warning('Please fill TX ID first');
            $("#TXFG_MODPRINT").modal('hide');
            document.getElementById('txfg_txt_id').focus();
            return;
        }
        Cookies.set('CKPDLV_NO', txid, {
            expires: 365
        });
        Cookies.set('CKPDLV_BARCODE', mckBarcode, {
            expires: 365
        });
        Cookies.set('CKPDLV_FORMS', (mckdo + mckinv + mckpl), {
            expires: 365
        });
        window.open("<?=base_url('printdeliverydocs')?>", '_blank');
        $("#TXFG_MODPRINT").modal('hide');
    });

    function txfg_f_getdetail(ptxid) {
        const btnSO = document.getElementById('txfg_btn_SO')
        txfg_isedit_mode = true
        document.getElementById('txfg_gt_rm').innerText = "Please wait"
        let btnrm = document.getElementById('txfg_btn_rmstatus');
        btnrm.classList.remove(...btnrm.classList)
        btnrm.classList.add('btn', 'btn-outline-info')
        document.getElementById('txfg_tbltx').getElementsByTagName('tbody')[0].innerHTML = "";
        document.getElementById('txfg_tbltxrm').getElementsByTagName('tbody')[0].innerHTML = "";
        document.getElementById('txfg_divalertrm').innerHTML = "";
        $.ajax({
            type: "get",
            url: "<?=base_url('DELV/getdetails')?>",
            data: {
                intxid: ptxid,
                intype: '1'
            },
            dataType: "json",
            success: function(response) {
                let ttlcek = response.data.length;
                const ttldatafocus = response.datafocus.length
                if (ttldatafocus > 0) {
                    btnrm.classList.remove(...btnrm.classList)
                    btnrm.classList.add('btn', 'btn-outline-danger')
                    let rm_tbl_body = document.getElementById("txfg_tbltxrm").getElementsByTagName("tbody")[0];
                    rm_tbl_body.innerHTML = '';
                    document.getElementById('txfg_divalertrm').innerHTML = `<div class="alert alert-warning" role="alert">Raw material of finished goods on the table below need to be fixed</div>`
                    for (let i = 0; i < ttldatafocus; i++) {
                        newrow = rm_tbl_body.insertRow(-1);
                        newcell = newrow.insertCell(0);
                        newcell.innerHTML = response.datafocus[i].SER_DOC
                        newcell = newrow.insertCell(1);
                        newcell.innerHTML = response.datafocus[i].SER_ITMID
                        newcell = newrow.insertCell(2);
                        newcell.innerHTML = response.datafocus[i].MITM_ITMD1
                        newcell = newrow.insertCell(3);
                        newcell.innerHTML = response.datafocus[i].ITH_SER
                        newcell = newrow.insertCell(4);
                        newcell.classList.add('text-end')
                        newcell.innerHTML = numeral(response.datafocus[i].SER_QTY).format(',')
                    }
                } else {
                    btnrm.classList.remove(...btnrm.classList)
                    btnrm.classList.add('btn', 'btn-outline-success')
                }
                txfg_ttlcek = ttlcek;
                txfg_ar_item_ser = [];
                txfg_ar_item_cd = [];
                txfg_ar_item_nm = [];
                txfg_ar_item_qty = [];
                txfg_ar_item_model = [];
                txfg_ar_so = [];
                txfg_ar_sodt = [];
                txfg_ar_item_job = [];
                txfg_ar_cnt_rm = [];
                let isASPItemCD = false;
                let ar_itmid = ['-']
                let str_itmid = ''
                for (let i = 0; i < ttlcek; i++) {
                    txfg_g_string = response.data[i].SI_OTHRMRK;
                    txfg_ar_item_ser.push(response.data[i].DLV_SER);
                    txfg_ar_item_cd.push(response.data[i].SER_ITMID);
                    txfg_ar_item_nm.push(response.data[i].MITM_ITMD1);
                    txfg_ar_item_qty.push(Number(response.data[i].SISCN_SERQTY));
                    txfg_ar_item_model.push(response.data[i].SI_MDL);
                    txfg_ar_item_job.push(response.data[i].SER_DOC);
                    txfg_ar_so.push(response.data[i].MITM_BOXTYPE);
                    txfg_ar_sodt.push(response.data[i].SI_DOCREFFDT);
                    txfg_ar_cnt_rm.push(response.data[i].COUNTRM);
                    if (response.data[i].SER_ITMID.includes("ASP")) {
                        isASPItemCD = true;
                    }
                    if (!ar_itmid.includes(response.data[i].SER_ITMID)) {
                        ar_itmid.push(response.data[i].SER_ITMID)
                    }
                }

                ar_itmid.forEach((r) => {
                    str_itmid += `<option value='${r}'>${r}</option>`
                })

                txfg_modchangeprice_txt_itemcode.innerHTML = str_itmid
                if (ttlcek > 0) {
                    btnSO.disabled = false
                    let tabell = document.getElementById("txfg_tbltx")
                    let tableku2 = tabell.getElementsByTagName("tbody")[0]
                    let tmp_ar_item_box = [];
                    let tmp_ar_item_cd = [];
                    let tmp_ar_item_nm = [];
                    let tmp_ar_item_qty = [];
                    let tmp_ar_item_model = [];
                    let tmp_ar_si = [];
                    let tmp_ar_so = [];
                    let tmp_ar_sodt = [];
                    tableku2.innerHTML = '';
                    let flagisexist = false;
                    if (isASPItemCD) {
                        for (let i = 0; i < ttlcek; i++) {
                            flagisexist = false;
                            for (let k = 0; k < tmp_ar_so.length; k++) {
                                if (tmp_ar_item_cd[k] == txfg_ar_item_cd[i]) {
                                    let qty_ = numeral(txfg_ar_item_qty[i]).value();
                                    tmp_ar_item_box[k] += qty_
                                    flagisexist = true;
                                    break;
                                }
                            }
                            if (!flagisexist) {
                                let qty_ = numeral(txfg_ar_item_qty[i]).value();
                                tmp_ar_item_box.push(qty_);
                                tmp_ar_item_cd.push(txfg_ar_item_cd[i]);
                                tmp_ar_item_nm.push(txfg_ar_item_nm[i]);
                                tmp_ar_item_qty.push(qty_);
                                tmp_ar_item_model.push(txfg_ar_item_model[i]);
                                tmp_ar_si.push(txfg_ar_si[i]);
                                tmp_ar_so.push(txfg_ar_so[i]);
                                tmp_ar_sodt.push(txfg_ar_sodt[i]);
                            }
                        }
                    } else {
                        for (let i = 0; i < ttlcek; i++) {
                            if (tmp_ar_item_cd.length == 0) {
                                tmp_ar_item_box.push(1);
                                tmp_ar_item_cd.push(txfg_ar_item_cd[i]);
                                tmp_ar_item_nm.push(txfg_ar_item_nm[i]);
                                tmp_ar_item_qty.push(txfg_ar_item_qty[i]);
                                tmp_ar_item_model.push(txfg_ar_item_model[i]);
                                tmp_ar_si.push(txfg_ar_si[i]);
                                tmp_ar_so.push(txfg_ar_so[i]);
                                tmp_ar_sodt.push(txfg_ar_sodt[i]);
                            } else {
                                flagisexist = false;
                                for (let k = 0; k < tmp_ar_so.length; k++) {
                                    if (tmp_ar_so[k] == txfg_ar_so[i] && tmp_ar_item_cd[k] == txfg_ar_item_cd[i] && tmp_ar_si[k] == txfg_ar_si[i] && tmp_ar_item_qty[k] == txfg_ar_item_qty[i]) {
                                        tmp_ar_item_box[k] = tmp_ar_item_box[k] + 1;
                                        flagisexist = true;
                                        break;
                                    }
                                }
                                if (flagisexist) {} else {
                                    tmp_ar_item_box.push(1);
                                    tmp_ar_item_cd.push(txfg_ar_item_cd[i]);
                                    tmp_ar_item_nm.push(txfg_ar_item_nm[i]);
                                    tmp_ar_item_qty.push(txfg_ar_item_qty[i]);
                                    tmp_ar_item_model.push(txfg_ar_item_model[i]);
                                    tmp_ar_si.push(txfg_ar_si[i]);
                                    tmp_ar_so.push(txfg_ar_so[i]);
                                    tmp_ar_sodt.push(txfg_ar_sodt[i]);
                                }
                            }
                        }
                    }
                    let tempso, tempitem, tempitem2;
                    let dsptempso, dspitem, dspsodt, dspitemnm, dspcustpart;
                    for (let i = 0; i < tmp_ar_item_cd.length; i++) {
                        if (tempso != tmp_ar_so[i]) {
                            tempso = tmp_ar_so[i];
                            tempitem = tmp_ar_item_cd[i];
                            dsptempso = tmp_ar_so[i];
                            dspitem = tmp_ar_item_cd[i];
                            dspsodt = tmp_ar_sodt[i];
                            dspitemnm = tmp_ar_item_nm[i];
                            dspcustpart = tmp_ar_item_model[i];
                        } else {
                            dsptempso = '';
                            dspsodt = '';
                            if (tempitem != tmp_ar_item_cd[i]) {
                                dspitem = tmp_ar_item_cd[i];
                                dspitemnm = tmp_ar_item_nm[i];
                                dspcustpart = tmp_ar_item_model[i];
                                tempitem = dspitem;
                            } else {
                                dspitem = '';
                                dspitemnm = '';
                                dspcustpart = '';
                            }
                        }
                        newrow = tableku2.insertRow(-1);
                        newcell = newrow.insertCell(0);
                        newText = document.createTextNode(dsptempso);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(1);
                        newText = document.createTextNode(dspsodt);
                        newcell.appendChild(newText);

                        newcell = newrow.insertCell(2);
                        if (dspitem != '') {
                            newcell.oncontextmenu = function(event) {
                                event.preventDefault();
                                let str = document.getElementById('txfg_gt_rm').innerText;
                                if (str.substr(0, 1) == 'I') {
                                    alertify.warning('Please wait while RM is being initialized');
                                } else {
                                    txfg_g_string = event.target.innerText;
                                    txfg_context_menu_o.open(event)
                                    event.preventDefault()
                                }
                            };
                            newcell.onclick = (event) => {
                                if (event.ctrlKey) {
                                    if (event.target.tagName.toLowerCase() == 'td') {
                                        alertify.message('coming soon, insyaAlloh')
                                    }
                                }
                            }
                            newcell.classList.add('txfg_cell');
                        }
                        newText = document.createTextNode(dspitem);
                        newcell.appendChild(newText);

                        newcell = newrow.insertCell(3);
                        newText = document.createTextNode(dspitemnm);
                        newcell.appendChild(newText);

                        newcell = newrow.insertCell(4);
                        newText = document.createTextNode('');
                        newcell.style.cssText = 'text-align: right';
                        newcell.appendChild(newText);

                        newcell = newrow.insertCell(5);
                        newcell.onclick = function() {
                            cgetval(tmp_ar_so[i], tmp_ar_item_cd[i], tmp_ar_item_nm[i], Number(tmp_ar_item_qty[i]))
                        };
                        newText = document.createTextNode(numeral(tmp_ar_item_box[i]).format(','));
                        newcell.style.cssText = ''.concat('text-align: right;', 'cursor: pointer;', 'border: 1px solid #00C935;', 'border-style:double;');
                        newcell.title = "See detail data"
                        newcell.appendChild(newText)


                        newcell = newrow.insertCell(6)
                        newText = document.createTextNode(isASPItemCD ? 1 : Number(tmp_ar_item_qty[i]))
                        newcell.appendChild(newText);
                        newcell.style.cssText = 'text-align: right';

                        newcell = newrow.insertCell(7);
                        newText = document.createTextNode('');
                        newcell.appendChild(newText);
                        newcell.style.cssText = 'text-align: right';
                    }

                    function cgetval(pso, pitem, pitemname, pitemqty) {
                        let tc_so = pso;
                        let tc_itmcd = pitem;
                        let tc_itmnm = pitemname;
                        let tc_itmqty = pitemqty;
                        document.getElementById('txfg_detser_so').value = tc_so;
                        document.getElementById('txfg_detser_itmcd').value = tc_itmcd;
                        document.getElementById('txfg_detser_itmnm').value = tc_itmnm;
                        document.getElementById('txfg_detser_itmqty').value = tc_itmqty;
                        let ttldetail = txfg_ar_so.length;
                        let tbldet = document.getElementById("txfg_tbldetailser");
                        let tbldet_b = tbldet.getElementsByTagName("tbody")[0];
                        tbldet_b.innerHTML = "";
                        let exASPitem = tc_itmcd.includes("ASP");
                        if (exASPitem) {
                            for (let b = 0; b < ttldetail; b++) {
                                if (txfg_ar_so[b].trim() == tc_so && txfg_ar_item_cd[b].trim() == tc_itmcd) { //
                                    newrow = tbldet_b.insertRow(-1);
                                    newcell = newrow.insertCell(0);
                                    newcell.style.cssText = "cursor:pointer;"
                                    newcell.onclick = function() {
                                        if (txfg_ar_item_job[b].includes("-C")) {
                                            document.getElementById('txfg_divdetailser_rm').getElementsByTagName('tbody')[0].innerHTML = '<tr><td colspan="12" class="text-center">It is a combined job, for now we could not show its detail.</td></tr>'
                                            document.getElementById('txfg_tbldetailser_rm_lblinfo').innerText = txfg_ar_item_ser[b]
                                            document.getElementById('txfg_tbldetailser_rm_hinfo').innerText = ""
                                        } else {
                                            txfg_e_calculate_raw_material(txfg_ar_item_ser[b], txfg_ar_item_job[b], numeral(txfg_ar_item_qty[b]).value())
                                        }
                                    }
                                    newText = document.createTextNode(txfg_ar_item_ser[b]);
                                    newcell.appendChild(newText);
                                    newcell = newrow.insertCell(1);
                                    newText = document.createTextNode(txfg_ar_item_job[b]);
                                    newcell.appendChild(newText);
                                    newcell = newrow.insertCell(2);
                                    newText = document.createTextNode(numeral(txfg_ar_item_qty[b]).format('0,0'));
                                    newcell.style.cssText = "text-align:right";
                                    newcell.appendChild(newText);
                                    newcell = newrow.insertCell(3);
                                    newcell.onclick = function() {
                                        cgetvaldetser(txfg_ar_item_ser[b])
                                    };
                                    newText = document.createElement('I');
                                    newText.classList.add("fas", "fa-trash", "text-danger");
                                    newcell.style.cssText = "cursor:pointer;text-align:center";
                                    newcell.appendChild(newText);
                                }
                            }
                        } else {
                            for (let b = 0; b < ttldetail; b++) {
                                if (txfg_ar_so[b].trim() == tc_so && txfg_ar_item_cd[b].trim() == tc_itmcd && numeral(txfg_ar_item_qty[b]).value() == tc_itmqty) { //
                                    newrow = tbldet_b.insertRow(-1);
                                    newcell = newrow.insertCell(0);
                                    newcell.style.cssText = "cursor:pointer;";
                                    newcell.onclick = function() {
                                        if (txfg_ar_item_job[b].includes("-C")) {
                                            document.getElementById('txfg_divdetailser_rm').getElementsByTagName('tbody')[0].innerHTML = '<tr><td colspan="12" class="text-center">It is a combined job, for now we could not show its detail.</td></tr>'
                                            document.getElementById('txfg_tbldetailser_rm_lblinfo').innerText = txfg_ar_item_ser[b]
                                            document.getElementById('txfg_tbldetailser_rm_hinfo').innerText = ""
                                        } else {
                                            txfg_e_calculate_raw_material(txfg_ar_item_ser[b], txfg_ar_item_job[b], numeral(txfg_ar_item_qty[b]).value())
                                        }
                                    };
                                    newText = document.createTextNode(txfg_ar_item_ser[b]);
                                    newcell.appendChild(newText);
                                    newcell = newrow.insertCell(1);
                                    newText = document.createTextNode(txfg_ar_item_job[b]);
                                    newcell.appendChild(newText);
                                    newcell = newrow.insertCell(2);
                                    newText = document.createTextNode(numeral(txfg_ar_item_qty[b]).format('0,0'));
                                    newcell.style.cssText = "text-align:right";
                                    newcell.appendChild(newText);
                                    newcell = newrow.insertCell(3);
                                    newcell.onclick = function() {
                                        cgetvaldetser(txfg_ar_item_ser[b])
                                    };
                                    newText = document.createElement('I');
                                    newText.classList.add("fas", "fa-trash", "text-danger");
                                    newcell.style.cssText = "cursor:pointer;text-align:center";
                                    newcell.appendChild(newText);
                                }
                            }

                        }

                        function cgetvaldetser(prow) {
                            let apprby = document.getElementById("txfg_txt_apprby").value;
                            let mser = prow;
                            if (confirm(`Are you sure want to cancel ${mser} ? `)) {
                                $.ajax({
                                    type: "post",
                                    url: "<?=base_url('DELV/removeun')?>",
                                    data: {
                                        inser: mser
                                    },
                                    dataType: "json",
                                    success: function(response) {
                                        if (response.status[0].cd == '0') {
                                            alert(response.status[0].msg)
                                        } else {
                                            alertify.message(response.status[0].msg);
                                            $('#txfg_w_detailser').window('close');
                                            let mtxid = document.getElementById("txfg_txt_id").value;
                                            txfg_f_getdetail(mtxid);
                                        }
                                    },
                                    error: function(xhr, xopt, xthrow) {
                                        alertify.error(xthrow);
                                    }
                                });
                            }
                        }
                        $('#txfg_w_detailser').window('open');
                        $('#txfg_w_detailser').window('maximize');
                    }
                    txfg_e_last_resume();
                } else {
                    btnSO.disabled = false
                    alertify.message('No data selected');
                }


                ///get detail
                if (txfg_ar_item_ser.length > 0) {
                    $("#txfg_tblrfid tbody").empty();
                    $("#txfg_tblexim tbody").empty();
                    let dataresume = [];
                    let itemcodelist = [];
                    for (let i = 0; i < txfg_ar_item_ser.length; i++) {
                        let isfound = false;
                        for (let p in dataresume) {
                            if (dataresume[p].itemcode == txfg_ar_item_cd[i]) {
                                dataresume[p].qty += Number(txfg_ar_item_qty[i]);
                                dataresume[p].box++;
                                isfound = true;
                            }
                        }
                        if (!isfound) {
                            let newobj = {
                                itemcode: txfg_ar_item_cd[i],
                                qty: Number(txfg_ar_item_qty[i]),
                                box: 1
                            };
                            dataresume.push(newobj);
                            itemcodelist.push(txfg_ar_item_cd[i]);
                        }
                    }
                    itemcodelist.sort();
                    let tabel_PLOT = document.getElementById("txfg_tblrfid");
                    let tabel_PLOT_body0 = tabel_PLOT.getElementsByTagName("tbody")[0];
                    for (let p in dataresume) {
                        newrow = tabel_PLOT_body0.insertRow(-1);
                        newcell = newrow.insertCell(0);
                        newText = document.createTextNode(dataresume[p].itemcode);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(1);
                        newText = document.createTextNode(dataresume[p].qty);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(2);
                        newcell.style.cssText = 'text-align:center';
                        newText = document.createTextNode(dataresume[p].box + ' BOX');
                        newcell.appendChild(newText);
                    }
                    tabel_PLOT = document.getElementById("txfg_tblexim");
                    tabel_PLOT_body0 = tabel_PLOT.getElementsByTagName("tbody")[0];
                    let ttlk = itemcodelist.length;
                    for (let k = 0; k < ttlk; k++) {
                        for (let p in dataresume) {
                            if (itemcodelist[k] == dataresume[p].itemcode) {
                                newrow = tabel_PLOT_body0.insertRow(-1);
                                newcell = newrow.insertCell(0);
                                newText = document.createTextNode(dataresume[p].itemcode);
                                newcell.appendChild(newText);
                                newcell = newrow.insertCell(1);
                                newText = document.createTextNode('');
                                newcell.appendChild(newText);
                                newcell = newrow.insertCell(2);
                                newcell.style.cssText = 'text-align:center';
                                newText = document.createTextNode(dataresume[p].qty);
                                newcell.appendChild(newText);
                                break;
                            }
                        }
                    }
                    // txfg_e_calculate_raw_material_resume();
                    //genarate rm
                    let tbldet = document.getElementById("txfg_tbltx");
                    let tbldet_b = tbldet.getElementsByTagName("tbody")[0];
                    const ttlmainrow = tbldet_b.getElementsByTagName('tr').length;
                    let ttldis = 0;
                    for (let k = 0; k < ttlmainrow; k++) {
                        let item0 = tbldet_b.rows[k].cells[2].innerText.trim();
                        if (item0.length > 0) { //handle/pass distincted data
                            let isfilled = false;
                            for (let i = 0; i < ttlmainrow; i++) {
                                let itemvirtual = tbldet_b.rows[i].cells[2].innerText.trim();
                                let itemrm = tbldet_b.rows[i].cells[7].innerText.trim();
                                if (item0 == itemvirtual && itemrm.length > 0) {
                                    isfilled = true;
                                    break;
                                }
                            }
                            if (!isfilled) {
                                for (let c = 0; c < ttlcek; c++) {
                                    if (item0 == txfg_ar_item_cd[c].trim()) {
                                        tbldet_b.rows[k].cells[7].innerText = txfg_ar_cnt_rm[c];
                                        ttldis += txfg_ar_cnt_rm[c];
                                        break;
                                    }
                                }
                            }
                        }
                    }
                    document.getElementById('txfg_gt_rm').innerText = numeral(ttldis).format(',')
                    //end
                }
            },
            error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow);
            }
        });
    }

    function txfg_e_last_resume() {
        //purpose: to display formated data (total delivery qty) per-itemcode
        let aitemresume = [];
        let ttldata = txfg_ar_item_cd.length;
        for (let i = 0; i < ttldata; i++) {
            let isfound = false;
            for (let c in aitemresume) {
                if (aitemresume[c].itemcode == txfg_ar_item_cd[i]) {
                    aitemresume[c].ttlqty += numeral(txfg_ar_item_qty[i]).value();
                    isfound = true;
                }
            }
            if (!isfound) {
                let newobku = {
                    itemcode: txfg_ar_item_cd[i],
                    ttlqty: numeral(txfg_ar_item_qty[i]).value(),
                    isused: false
                };
                aitemresume.push(newobku);
            }
        }

        let tabel_PLOT = document.getElementById("txfg_tbltx");
        let tabel_PLOT_body0 = tabel_PLOT.getElementsByTagName("tbody")[0];
        let ttlrows_PLOT = tabel_PLOT_body0.getElementsByTagName("tr").length;
        for (let i = 0; i < ttlrows_PLOT; i++) {
            tabel_PLOT_body0.rows[i].cells[4].innerHTML = '';
        }
        for (let i = 0; i < ttlrows_PLOT; i++) {
            let citemcode = tabel_PLOT_body0.rows[i].cells[2].innerText;
            for (let c in aitemresume) {
                if (citemcode == aitemresume[c].itemcode && !aitemresume[c].isused) {
                    tabel_PLOT_body0.rows[i].cells[4].innerText = numeral(aitemresume[c].ttlqty).format(',');
                    aitemresume[c].isused = true;
                }
            }
        }
    }

    function txfg_btn_toomi_xls() {
        const txid = document.getElementById('txfg_txt_id').value.trim()
        if (txid.length == 0) {
            alertify.message('TXID is required')
            return
        }
        const consign = document.getElementById('txfg_consignee').value
        if (consign != 'OMI') {
            alertify.warning('OMI Only')
            return
        }
        Cookies.set('CKPDLV_NO', txid, {
            expires: 365
        })
        window.open("<?=base_url('delivery_doc_as_omi_xls')?>", '_blank')
    }

    function txfg_e_calculate_raw_material(preffno, pjob, pqty) {
        document.getElementById('txfg_tbldetailser_rm_lblinfo').innerText = preffno;
        document.getElementById('txfg_tbldetailser_rm_hinfo').innerText = "Please wait";
        $("txfg_divdetailser_rm tbody").empty();
        $.ajax({
            type: "get",
            url: "<?=base_url('DELV/calculate_raw_material')?>",
            data: {
                inunique: preffno,
                inunique_job: pjob,
                inunique_qty: pqty
            },
            dataType: "json",
            success: function(response) {
                if (response.status[0].cd != 0) {
                    document.getElementById('txfg_tbldetailser_rm_hinfo').innerText = response.status[0].msg;
                    let ttlrows = response.data.length;
                    let mydes = document.getElementById("txfg_divdetailser_rm");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("txfg_tbldetailser_rm");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("txfg_tbldetailser_rm");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    let newrow, newcell, newText;
                    tableku2.innerHTML = '';
                    for (let i = 0; i < ttlrows; i++) {
                        let myper = response.data[i].MYPER;
                        newrow = tableku2.insertRow(-1);
                        newcell = newrow.insertCell(0);
                        newcell.innerHTML = response.data[i].SERD2_PSNNO;
                        newcell = newrow.insertCell(1);
                        newcell.innerHTML = response.data[i].SERD2_LINENO
                        newcell = newrow.insertCell(2);
                        newcell.innerHTML = response.data[i].SERD2_PROCD
                        newcell = newrow.insertCell(3);
                        newcell.innerHTML = response.data[i].SERD2_CAT
                        newcell = newrow.insertCell(4);
                        newcell.style.cssText = "text-align:center";
                        newcell.innerHTML = response.data[i].SERD2_FR
                        newcell = newrow.insertCell(5);
                        newcell.style.cssText = "text-align:center";
                        newcell.innerHTML = response.data[i].SERD2_MC
                        newcell = newrow.insertCell(6);
                        newcell.style.cssText = "text-align:center";
                        newcell.innerHTML = response.data[i].SERD2_MCZ
                        newcell = newrow.insertCell(7);
                        newcell.style.cssText = "text-align:right";
                        newcell.innerHTML = myper
                        newcell = newrow.insertCell(8);
                        newcell.innerHTML = response.data[i].SERD2_ITMCD
                        newcell = newrow.insertCell(9);
                        newcell.innerHTML = response.data[i].MITM_SPTNO
                        newcell = newrow.insertCell(10);
                        newcell.style.cssText = "text-align:right";
                        newcell.innerHTML = response.data[i].SERD2_QTY
                        newcell = newrow.insertCell(11);
                        newcell.innerHTML = response.data[i].MITM_ITMD1
                    }
                    mydes.innerHTML = '';
                    mydes.appendChild(myfrag);
                } else {
                    document.getElementById('txfg_tbldetailser_rm_hinfo').innerText = response.status[0].msg;
                }
            },
            error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow);
            }
        });
    }

    function txfg_z_btn_save25_e_click() {
        const msj = document.getElementById('txfg_txt_id').value;
        if (msj.trim() === '') {
            document.getElementById('txfg_txt_id').focus();
            alertify.message('Please fill the TX ID');
            return
        }
        const mbctype = document.getElementById('txfg_cmb_bcdoc').value;
        if (mbctype.trim() === '') {
            document.getElementById('txfg_cmb_bcdoc').focus();
            return;
        }
        const mnoaju = document.getElementById('txfg_txt_noaju25').value
        const mjenis_tpb_asal = document.getElementById('txfg_cmb_jenisTPB25').value
        const mnopen = document.getElementById('txfg_txt_nopen25').value.trim()
        const mtglpen = document.getElementById('txfg_txt_tglpen25').value
        const mfromoffice = document.getElementById('txfg_fromoffice25').value
        const mSKB_pph = document.getElementById('txfg_txt_noskb').value
        const mSKB_pph_tgl = document.getElementById('txfg_txt_noskb_tgl').value
        const mjenis_sarana_pengangkut = document.getElementById('txfg_jenis_saranapengangkut25').value
        const sppbdoc = document.getElementById('txfg_txt_sppb25').value
        const mpurpose = document.getElementById('txfg_cmb_tujuanpengiriman25').value;
        if (mnoaju.includes(".")) {
            alertify.warning(`Nomor pengajuan is not valid`)
            document.getElementById('txfg_txt_noaju25').focus()
            return;
        }
        if (mnoaju.trim().length < 6) {
            alertify.warning("Nomor pengajuan is also not valid");
            document.getElementById('txfg_txt_noaju25').focus();
            return;
        }

        if(mnopen.length > 0 && mtglpen.length == 0) {
            alertify.warning('Tanggal Pendaftaran is required')
            return
        }

        if (confirm("Are You sure ?")) {
            $.ajax({
                type: "get",
                url: "<?=base_url('DELV/change25')?>",
                data: {
                    inid: msj,
                    innopen: mnopen,
                    inaju: mnoaju,
                    infromoffice: mfromoffice,
                    injenis_tpb_asal: mjenis_tpb_asal,
                    inskb: mSKB_pph,
                    inskb_tgl: mSKB_pph_tgl,
                    injenis_sarana: mjenis_sarana_pengangkut,
                    innopen: mnopen,
                    intglpen: mtglpen,
                    sppbdoc: sppbdoc,
                    inPemberitahu: txfg_txt_pemberitahu25.value,
                    inJabatan: txfg_txt_jabatan25.value,
                    inpurpose: mpurpose,
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

    document.getElementById('txfg_cmb_jenisTPBtujuan').value = '1'
    document.getElementById('txfg_cmb_tujuanpengiriman').value = '1'

    function txfg_z_btn_save_e_click(pThis) {
        pThis.innerText = 'Please wait'
        let msj = document.getElementById('txfg_txt_id').value;
        if (msj.trim() == '') {
            document.getElementById('txfg_txt_id').focus()
            return;
        }
        let mbctype = document.getElementById('txfg_cmb_bcdoc').value;
        if (mbctype.trim() == '') {
            document.getElementById('txfg_cmb_bcdoc').focus();
            return;
        }
        let mnoaju = document.getElementById('txfg_txt_noaju').value;
        let mnopen = document.getElementById('txfg_txt_nopen').value.trim();
        let mtglpen = document.getElementById('txfg_txt_tglpen').value;
        let mjenis_tpb_asal = document.getElementById('txfg_cmb_jenisTPB').value;
        let mjenis_tpb_tujuan = document.getElementById('txfg_cmb_jenisTPBtujuan').value;
        let mfromoffice = document.getElementById('txfg_fromoffice').value;
        let mdestoffice = document.getElementById('txfg_destoffice').value;
        let mpurpose = document.getElementById('txfg_cmb_tujuanpengiriman').value;
        const mcona = document.getElementById('txfg_txt_nokontrak').value.trim()
        const sppbdoc = document.getElementById('txfg_txt_sppb27').value
        if (mnoaju.includes(".")) {
            document.getElementById('txfg_txt_noaju').focus()
            alertify.warning(`Nomor pengajuan is not valid`)
            return
        }
        if (mnoaju.trim().length < 6) {
            alertify.warning("Nomor pengajuan is also not valid");
            document.getElementById('txfg_txt_noaju').focus();
            return;
        }
        if (mjenis_tpb_tujuan != '1') {
            if (confirm("Currently only for KAWASAN BERIKAT, are you sure want to continue ?")) {

            } else {
                return
            }
        }
        if (!msj.includes('RTN')) {
            if (mpurpose != '1') {
                if (confirm("Currently only for DIJUAL, are you sure want to continue ?")) {

                } else {
                    return
                }
            }
        }

        if(mnopen.length > 0 && mtglpen.length == 0) {
            alertify.warning('Tanggal Pendaftaran is required')
            return
        }

        if (confirm("Are You sure ?")) {
            pThis.disabled = true
            $.ajax({
                type: "get",
                url: "<?=base_url('DELV/change27')?>",
                data: {
                    inid: msj,
                    innopen: mnopen,
                    inaju: mnoaju,
                    infromoffice: mfromoffice,
                    indestoffice: mdestoffice,
                    inpurpose: mpurpose,
                    injenis_tpb_asal: mjenis_tpb_asal,
                    injenis_tpb_tujuan: mjenis_tpb_tujuan,
                    intgldaftar: mtglpen,
                    incona: mcona,
                    sppbdoc: sppbdoc,
                    inPemberitahu: txfg_txt_pemberitahu27.value,
                    inJabatan: txfg_txt_jabatan27.value
                },
                dataType: "json",
                success: function(response) {
                    pThis.innerText = 'Save changes'
                    pThis.disabled = false
                    if (response[0].cd == '11') {
                        alertify.success(response[0].msg);
                    } else {
                        alertify.error(response[0].msg);
                    }
                },
                error: function(xhr, xopt, xthrow) {
                    pThis.innerText = 'Save changes'
                    pThis.disabled = false
                    alertify.error(xthrow);
                }
            });
        }
    }

    function txfg_z_btn_save41_e_click() {
        const msj = document.getElementById('txfg_txt_id').value;
        if (msj.trim() == '') {
            document.getElementById('txfg_txt_id').focus();
            return;
        }
        const mbctype = document.getElementById('txfg_cmb_bcdoc').value;
        if (mbctype.trim() == '') {
            document.getElementById('txfg_cmb_bcdoc').focus();
            return;
        }
        const mnoaju = document.getElementById('txfg_txt_noaju41').value;
        const mnopen = document.getElementById('txfg_txt_nopen41').value.trim();
        const mtglpen = document.getElementById('txfg_txt_tglpen41').value;
        const mfromoffice = document.getElementById('txfg_fromoffice41').value;
        const mjenis_tpb_asal = document.getElementById('txfg_cmb_jenisTPB41').value;
        const mpurpose = document.getElementById('txfg_cmb_tujuanpengiriman41').value;
        const mcona = document.getElementById('txfg_txt_nokontrak41').value.trim()
        const sppbdoc = document.getElementById('txfg_txt_sppb41').value
        if (mpurpose === '1' && mcona.length != 0) {

        }
        if (mnoaju.includes(".")) {
            document.getElementById('txfg_txt_noaju41').focus()
            alertify.warning(`Nomor pengajuan is not valid`)
            return
        }
        if (mnoaju.trim().length < 6) {
            alertify.warning("NO AJU is not valid");
            document.getElementById('txfg_txt_noaju41').focus();
            return;
        }

        if(mnopen.length>0 && mtglpen.length == 0) {
            alertify.warning('Tanggal Pendaftaran is required')
            return
        }
        
        if (confirm("Are You sure ?")) {
            $.ajax({
                type: "get",
                url: "<?=base_url('DELV/change41')?>",
                data: {
                    inid: msj,
                    innopen: mnopen,
                    inaju: mnoaju,
                    infromoffice: mfromoffice,
                    inpurpose: mpurpose,
                    injenis_tpb_asal: mjenis_tpb_asal,
                    intgldaftar: mtglpen,
                    incona: mcona,
                    sppbdoc: sppbdoc,
                    inPemberitahu: txfg_txt_pemberitahu41.value,
                    inJabatan: txfg_txt_jabatan41.value
                },
                dataType: "json",
                success: function(response) {
                    if (response[0].cd = '11') {
                        alertify.success(response[0].msg);
                    }
                },
                error: function(xhr, xopt, xthrow) {
                    alertify.error(xthrow);
                }
            });
        }
    }

    $("#TXFG_CUSTOMSMOD").on('hidden.bs.modal', function() {

    });
    $("#txfg_btn_appr").click(function(e) {
        let mtxid = document.getElementById('txfg_txt_id').value;
        if (mtxid.trim() == '') {
            document.getElementById('txfg_txt_id').focus();
            return;
        }
        let minvno = document.getElementById('txfg_txt_invno').value;
        if (minvno.trim().length <= 3) {
            alertify.warning('Invoice Number is required');
            document.getElementById('txfg_txt_invno').focus();
            return;
        }
        let mbg = document.getElementById('txfg_businessgroup').value;
        let mcustname = document.getElementById('txfg_custname').value.toLowerCase();
        if (mbg == 'PSI1PPZIEP' && mcustname.includes('sumit')) {
            let msmtinv = document.getElementById('txfg_txt_invsmt').value;
            if (msmtinv.trim().length <= 3) {
                document.getElementById('txfg_txt_invsmt').focus();
                alertify.message('SMT Invoice is required');
                return;
            }
        }
        let konf = confirm('Are you sure ?');
        if (!konf) {
            return;
        }
        $.ajax({
            type: "post",
            url: "<?=base_url('DELV/approve')?>",
            data: {
                inid: mtxid
            },
            dataType: "json",
            success: function(response) {
                if (response[0].cd == "0") {
                    alertify.warning(response[0].msg);
                } else {
                    alertify.success(response[0].msg);
                    let appr = document.getElementById("footerinfo_user").innerText;
                    appr = appr.substr(3, appr.length);
                    document.getElementById("txfg_txt_apprby").value = appr;
                    document.getElementById("txfg_txt_apprtime").value = response[0].approved_time;
                }
            },
            error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow);
            }
        })
    });

    function txfg_e_posting() {
        txfg_starttime = moment().format('HH:mm:ss')
        let msj = document.getElementById('txfg_txt_id').value
        let remark = msj.includes('RTN') ? 'rtn' : ''
        let doctype = document.getElementById('txfg_cmb_bcdoc').value + remark
        txfg_timer();
        $.ajax({
            type: "get",
            url: "DELV/posting" + doctype,
            data: {
                insj: msj
            },
            dataType: "json",
            success: function(response) {
                clearTimeout(txfg_t);
                $("#TXFG_PROGRESS").modal('hide');
                $("#txfg_tblrmnull tbody").empty();
                $("#txfg_tblrmexbcnull tbody").empty();
                if (response.status[0].cd == '1') {
                    alertify.success(response.status[0].msg);
                    let appr = document.getElementById("footerinfo_user").innerText;
                    appr = appr.substr(3, appr.length);
                    document.getElementById("txfg_txt_postedby").value = appr;
                    document.getElementById("txfg_txt_postedtime").value = response.status[0].time;
                    txfg_centerRight();
                    document.getElementById('txfg_btn_addsi').disabled = true
                    document.getElementById('txfg_status').value = "Posted";
                } else if (response.status[0].cd == '100') {
                    document.getElementById('txfg_alert_rmnull').innerHTML = response.status[0].msg;
                    let mydes = document.getElementById("txfg_divrmnull");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("txfg_tblrmnull");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("txfg_tblrmnull");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    let ttlrows = response.data.length;
                    for (let i = 0; i < ttlrows; i++) {
                        newrow = tableku2.insertRow(-1);
                        newcell = newrow.insertCell(0);
                        newcell.innerHTML = response.data[i].DLV_SER
                        newcell = newrow.insertCell(1);
                        newcell.innerHTML = response.data[i].SER_ITMID
                        newcell = newrow.insertCell(2);
                        newcell.innerHTML = response.data[i].SER_DOC
                        newcell = newrow.insertCell(3);
                        newcell.style.cssText = "text-align:right";
                        newcell.innerHTML = numeral(response.data[i].DLV_QTY).format(',')
                    }
                    mydes.innerHTML = '';
                    mydes.appendChild(myfrag);
                    $('#txfg_w_rmnull').window('open')
                } else if (response.status[0].cd == '110') {
                    document.getElementById('txfg_alert_rmexbcnull').innerText = response.status[0].msg;
                    let mydes = document.getElementById("txfg_divrmexbcnull");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("txfg_tblrmexbcnull");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("txfg_tblrmexbcnull");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    let ttlrows = response.data.length;
                    tableku2.innerHTML = ''
                    for (let i = 0; i < ttlrows; i++) {
                        newrow = tableku2.insertRow(-1);
                        newcell = newrow.insertCell(0);
                        newcell.innerHTML = response.data[i].ITMCD
                        newcell = newrow.insertCell(1);
                        newcell.style.cssText = "text-align:right";
                        newcell.innerHTML = numeral(response.data[i].QTY).format(',')
                        newcell = newrow.insertCell(2);
                        newcell.innerHTML = response.data[i].LOTNO
                    }
                    mydes.innerHTML = '';
                    mydes.appendChild(myfrag);
                    $('#txfg_w_rmexbcnull').window('open');
                } else if (response.status[0].cd == '120') {
                    document.getElementById('txfg_alert_rmhscdnull').innerText = response.status[0].msg;
                    let mydes = document.getElementById("txfg_div_rmhscd_null");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("txfg_tbl_rmhscd_null");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("txfg_tbl_rmhscd_null");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    let ttlrows = response.data.length;
                    tableku2.innerHTML = ''
                    for (let i = 0; i < ttlrows; i++) {
                        newrow = tableku2.insertRow(-1)
                        newcell = newrow.insertCell(0)
                        newcell.innerHTML = response.data[i].RCV_RPNO
                        newcell = newrow.insertCell(1)
                        newcell.innerHTML = response.data[i].RCV_DONO
                        newcell = newrow.insertCell(2)
                        newcell.innerHTML = response.data[i].RCV_ITMCD
                    }
                    mydes.innerHTML = '';
                    mydes.appendChild(myfrag);
                    $('#txfg_w_rmhscdnull').window('open');
                } else {
                    alertify.warning(response.status[0].msg);
                }
                txfg_timerspan.innerText = "00:00:00";
                txfg_seconds = 0;
                txfg_minutes = 0;
                txfg_hours = 0;
            },
            error: function(xhr, xopt, xthrow) {
                clearTimeout(txfg_t)
                txfg_timerspan.innerText = "00:00:00";
                txfg_seconds = 0;
                txfg_minutes = 0;
                txfg_hours = 0;
                $("#TXFG_PROGRESS").modal('hide')
                alertify.error(xthrow);
            }
        });
    }

    $("#txfg_btn_post").click(function(e) {
        if (txfg_gt_rm.innerText == 'Please wait') {
            alertify.warning('Please wait')
            return
        } else {
            if (numeral(txfg_gt_rm.innerText).value() > 900) {
                alertify.warning('Maximum RM 900 per Document')
                return
            }
        }
        let mapprovby = document.getElementById("txfg_txt_apprby").value;
        const txid = document.getElementById('txfg_txt_id').value
        if (mapprovby.trim() == '') {
            alertify.warning("Please approve first !");
            return;
        }
        if (!document.getElementById('txfg_btn_rmstatus').classList.contains('btn-outline-success') && !txid.includes("RTN")) {
            alertify.warning("Raw material need to be fixed first !");
            return
        }

        if (!confirm('Are you sure ?')) {
            return;
        }
        let mymodal = new bootstrap.Modal(document.getElementById("TXFG_PROGRESS"), {
            backdrop: 'static',
            keyboard: false
        });
        mymodal.show()
    })

    function txfg_btn_post_cancel_eCK() {
        let msj = document.getElementById('txfg_txt_id')
        let mapprovby = document.getElementById("txfg_txt_apprby").value
        if (msj.value.trim().length === 0) {
            msj.focus()
            alertify.message('TX ID is required')
            return
        }

        if (mapprovby.trim() == '') {
            alertify.warning("Please approve first !");
            return;
        }
        if (!document.getElementById('txfg_btn_rmstatus').classList.contains('btn-outline-success')) {
            alertify.warning("Raw material need to be fixed first !");
            return
        }
        $.ajax({
            type: "GET",
            url: "<?=base_url('MEXRATE/lastupdate')?>",
            data: {
                tanggal_aju: txfg_txt_custdate.value
            },
            dataType: "json",
            success: function(response) {
                if (response.status.cd === 1) {
                    if (!confirm('Are you sure ?')) {
                        return;
                    }
                    let mymodal = new bootstrap.Modal(document.getElementById("TXFG_PROGRESS_CNCL"), {
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
                            document.getElementById('txfg_btn_post_cancel').classList.add('disabled')
                            document.getElementById('txfg_status').value = "Approved"
                            mymodal.hide()
                        },
                        error: function(xhr, xopt, xthrow) {
                            document.getElementById('txfg_btn_post_cancel').classList.add('disabled')
                            mymodal.hide()
                            alertify.error(xthrow)
                        }
                    })
                } else {
                    alertify.message(response.status.msg)
                }
            },
            error: function(xhr, xopt, xthrow) {
                document.getElementById('txfg_btn_post_cancel').classList.add('disabled')
                alertify.error(xthrow)
            }
        });

    }
    $("#txfg_txt_transport").change(function(e) {
        let mdataplat = $(this).val();
        let adata = mdataplat.split("_");
        document.getElementById("txfg_txt_transporttype").value = adata[1] ? adata[1] : '';
    });

    function txfg_btn_torfid_e_click() {
        let txid = document.getElementById('txfg_txt_id').value;
        if (txfg_ar_item_ser.length > 0 && txid.trim() != '') {
            $("#TXFG_MODRFID").modal('show');
        }
        txfg_selectElementContents(document.getElementById('txfg_tblrfid'));
    }

    function txfg_btn_tocustomscontrol_e_click() {
        let txid = document.getElementById('txfg_txt_id').value;
        if (txfg_ar_item_ser.length > 0 && txid.trim() != '') {
            $("#TXFG_MODEXIM").modal('show');
        }
        txfg_selectElementContents(document.getElementById('txfg_tblexim'));
    }

    function txfg_btn_toepro_e_click() {
        let msi = document.getElementById('txfg_txt_id').value;
        if (msi.trim() == '') {
            document.getElementById('txfg_txt_id').focus();
            alertify.warning('Please select document first');
            return;
        }
        let mbg = document.getElementById('txfg_businessgroup').value.trim();
        if (mbg == 'PSI1PPZIEP') {
            Cookies.set('PRINTLABEL_SI', msi, {
                expires: 365
            });
            window.open("<?=base_url('printdeliveryepro')?>", '_blank');
        } else {
            alertify.message('EPSON Only');
        }
    }

    function txfg_btn_showweight_e_click() {
        const txtid = document.getElementById('txfg_txt_id').value
        if (txtid.length == 0) {
            alertify.warning('TXID is required')
            return
        }
        $.ajax({
            type: "GET",
            url: "<?=base_url('DELV/weight')?>",
            data: {
                txid: txtid
            },
            dataType: "json",
            success: function(response) {
                let ttlrows = response.data.length;
                let mydes = document.getElementById("txfg_divinfo_weight");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("txfg_tblinfo_weight");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("txfg_tblinfo_weight");
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                let newrow, newcell, newText;
                tableku2.innerHTML = '';
                let tominqty = 0;
                let tempqty = 0;
                let todisqty = 0;
                for (let i = 0; i < ttlrows; i++) {
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0);
                    newcell.innerHTML = response.data[i].ITEM
                    newcell = newrow.insertCell(1);
                    newcell.classList.add('text-end')
                    newcell.innerHTML = numeral(response.data[i].QTY).format(',')
                    newcell = newrow.insertCell(2);
                    newcell.classList.add('text-end')
                    newcell.innerHTML = response.data[i].NWG * 1
                    newcell = newrow.insertCell(3);
                    newcell.classList.add('text-end')
                    newcell.innerHTML = response.data[i].GWG * 1
                }
                mydes.innerHTML = '';
                mydes.appendChild(myfrag);
            },
            error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow);
            }
        })
        $("#TXFG_MODINFO_WEIGTH").modal('show')
    }
    document.getElementById('txfg_fromoffice').value = '050900';
    document.getElementById('txfg_cmb_jenisTPB').value = '1';

    function txfg_btn_changeprice_e_click() {
        if (txfg_ar_item_ser.length > 0) {
            if (txfg_gt_rm.innerText == 'Please wait') {
                alertify.warning('Please wait')
            } else {
                $("#TXFG_MODCHANGEPRICE").modal('show')
            }
        } else {
            txfg_txt_id.focus()
            alertify.message('there is no data to be processed')
        }
    }

    function txfg_modchangeprice_txt_itemcode_eChange(e) {
        txfg_modchangeprice_tbl.getElementsByTagName('tbody')[0].innerHTML = `<tr><td colspan="4" class="text-center"><i>Please wait</i></td></tr>`
        txfg_modchangeprice_new_tbl.getElementsByTagName('tbody')[0].innerHTML = `<tr><td colspan="4" class="text-center"><i>Please wait</i></td></tr>`
        $.ajax({
            type: "POST",
            url: "<?=base_url('DELVHistory/priceFG')?>",
            data: {
                itemcd: e.target.value,
                reffno: txfg_ar_item_ser
            },
            dataType: "json",
            success: function(response) {
                let mydes = document.getElementById("txfg_modchangeprice_tbl_div");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("txfg_modchangeprice_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("txfg_modchangeprice_tbl");
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                let newrow, newcell, newText;
                tableku2.innerHTML = '';
                response.data.forEach((r, i) => {
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0);
                    newcell.innerHTML = r['SISO_CPONO']
                    newcell = newrow.insertCell(1)
                    newcell.innerHTML = r['SISO_SOLINE']
                    newcell = newrow.insertCell(2)
                    newcell.classList.add('text-end')
                    newcell.style.cssText = 'cursor:pointer'
                    newcell.innerHTML = `<strong>${r['SSO2_SLPRC']*1}</strong>`
                    newcell.onclick = () => {
                        if (TXFG_hid_idx.value != '') {
                            tableku2.rows[TXFG_hid_idx.value].classList.remove('table-info')
                        }
                        tableku2.rows[i].classList.add('table-info')
                        TXFG_hid_idx.value = i
                    }
                    newcell = newrow.insertCell(3)
                    newcell.classList.add('d-none')
                    newcell.innerHTML = r['SISO_FLINE']
                })
                mydes.innerHTML = '';
                mydes.appendChild(myfrag);
            },
            error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow);
            }
        })

        $.ajax({
            type: "POST",
            url: "<?=base_url('SO/outstanding_mega')?>",
            data: {
                bg: txfg_businessgroup.value,
                cuscd: scr_txfg_cust,
                itemcd: [e.target.value]
            },
            dataType: "JSON",
            success: function(response) {
                let mydes = document.getElementById("txfg_modchangeprice_new_tbl_div");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("txfg_modchangeprice_new_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("txfg_modchangeprice_new_tbl");
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                let newrow, newcell, newText;
                tableku2.innerHTML = '';
                response.data.forEach((r, i) => {
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0);
                    newcell.innerHTML = r['SSO2_CPONO']
                    newcell = newrow.insertCell(1)
                    newcell.innerHTML = r['SSO2_SOLNO']
                    newcell = newrow.insertCell(2)
                    newcell.classList.add('text-end')
                    newcell.style.cssText = 'cursor:pointer'
                    newcell.innerHTML = `<strong>${r['SSO2_SLPRC']*1}</strong>`
                    newcell.onclick = () => {
                        if (TXFG_hid_idx2.value != '') {
                            tableku2.rows[TXFG_hid_idx2.value].classList.remove('table-info')
                        }
                        tableku2.rows[i].classList.add('table-info')
                        TXFG_hid_idx2.value = i
                    }
                })
                mydes.innerHTML = '';
                mydes.appendChild(myfrag);
            },
            error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow)
            }
        })
    }

    function txfg_modchangeprice_btn_save_eClick(p) {
        if (txfg_modchangeprice_txt_itemcode.value === '-') {
            alertify.warning('Please select Item Code')
            txfg_modchangeprice_txt_itemcode.focus()
            return
        }
        if (txfg_modchangeprice_new_tbl.getElementsByTagName('tbody')[0].rows === 0) {
            alertify.message('there is no new price selected')
            return
        }
        let ttlrows = txfg_modchangeprice_tbl.getElementsByTagName('tbody')[0].rows.length
        let isOneSelected = false
        for (let i = 0; i < ttlrows; i++) {
            if (txfg_modchangeprice_tbl.getElementsByTagName('tbody')[0].rows[i].classList.contains('table-info')) {
                isOneSelected = true
            }
        }
        if (!isOneSelected) {
            alertify.message('please select old price first')
            return
        }
        ttlrows = txfg_modchangeprice_new_tbl.getElementsByTagName('tbody')[0].rows.length
        isOneSelected = false
        for (let i = 0; i < ttlrows; i++) {
            if (txfg_modchangeprice_new_tbl.getElementsByTagName('tbody')[0].rows[i].classList.contains('table-info')) {
                isOneSelected = true
            }
        }
        if (!isOneSelected) {
            alertify.message('please select new price first')
            return
        }
        if (confirm('Are you sure ?')) {
            const fline = txfg_modchangeprice_tbl.getElementsByTagName('tbody')[0].rows[TXFG_hid_idx.value].cells[3].innerText
            const oldCPO = txfg_modchangeprice_tbl.getElementsByTagName('tbody')[0].rows[TXFG_hid_idx.value].cells[0].innerText
            const oldSOLine = txfg_modchangeprice_tbl.getElementsByTagName('tbody')[0].rows[TXFG_hid_idx.value].cells[1].innerText
            const newCPO = txfg_modchangeprice_new_tbl.getElementsByTagName('tbody')[0].rows[TXFG_hid_idx2.value].cells[0].innerText
            const newSOLine = txfg_modchangeprice_new_tbl.getElementsByTagName('tbody')[0].rows[TXFG_hid_idx2.value].cells[1].innerText
            p.disabled = true
            $.ajax({
                type: "POST",
                url: "<?=base_url('SO/change_so_manually')?>",
                data: {
                    fline: fline,
                    newCPO: newCPO,
                    newSOLine: newSOLine,
                    txid: txfg_txt_id.value,
                    oldSOLine: oldSOLine
                },
                dataType: "JSON",
                success: function(response) {
                    txfg_modchangeprice_txt_itemcode.value = '-'
                    p.disabled = false
                    alertify.message(response.status.msg)
                },
                error: function(xhr, xopt, xthrow) {
                    p.disabled = false
                    alertify.error(xthrow)
                }
            })
        }
    }

    function txfg_z_btn_relink_e_click(pThis) {
        pThis.innerHTML = `Please wait`
        pThis.disabled = true
        const txid = txfg_txt_id.value.trim()
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

    function txfg_btn_toceisa_e_click(p) {
        const doc = txfg_txt_id.value
        const docType = txfg_cmb_bcdoc.value + (doc.includes('RTN') ? 'rtn' : '')
        if(doc.length<=3)
        {
            alertify.warning('TX ID is required')
            txfg_txt_id.focus()
            return
        }
        if (!document.getElementById('txfg_btn_rmstatus').classList.contains('btn-outline-success') && !doc.includes("RTN")) {
            alertify.warning("Raw material need to be fixed first !");
            return
        }
        p.classList.add('disabled')
        p.innerHTML = 'Please wait'
        const div_alert = document.getElementById('txfg-div-alert')
        div_alert.innerHTML = `<div class="alert alert-info alert-dismissible fade show" role="alert">
                                <i class="fas fa-paper-plane fa-bounce"></i> Please wait
                                            </div>`
        $.ajax({
            type: "POST",
            url: "DELV/postingCEISA40BC" + docType,
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
                                            ${ex}
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
                    const response = xhr.responseJSON
                    if(response.status) {
                        if (response.status.cd == '110') {
                            document.getElementById('txfg_alert_rmexbcnull').innerText = response.status.msg;
                            let mydes = document.getElementById("txfg_divrmexbcnull");
                            let myfrag = document.createDocumentFragment();
                            let mtabel = document.getElementById("txfg_tblrmexbcnull");
                            let cln = mtabel.cloneNode(true);
                            myfrag.appendChild(cln);
                            let tabell = myfrag.getElementById("txfg_tblrmexbcnull");
                            let tableku2 = tabell.getElementsByTagName("tbody")[0];
                            let ttlrows = response.data.length;
                            tableku2.innerHTML = ''
                            for (let i = 0; i < ttlrows; i++) {
                                newrow = tableku2.insertRow(-1);
                                newcell = newrow.insertCell(0);
                                newcell.innerHTML = response.data[i].ITMCD
                                newcell.style.cssText = 'cursor:pointer'
                                newcell.onclick = function() {
                                    txfg_get_spl_delivery({doc : doc, part_code : response.data[i].ITMCD})
                                    TXFG_MOD_EXBC_HELPER_txid.innerText = doc
                                    TXFG_MOD_EXBC_HELPER_part.innerText = response.data[i].ITMCD
                                    $('#TXFG_MOD_EXBC_HELPER').modal('show')
                                }
                                newcell = newrow.insertCell(1);
                                newcell.style.cssText = "text-align:right";
                                newcell.innerHTML = numeral(response.data[i].QTY).format(',')
                                newcell = newrow.insertCell(2);
                                newcell.innerHTML = response.data[i].LOTNO
                            }
                            mydes.innerHTML = '';
                            mydes.appendChild(myfrag);
                            $('#txfg_w_rmexbcnull').window('open');
                        }
                    }
                } catch (ex) {
                    div_alert.innerHTML = `<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                            ${xhr.responseText}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>`
                }
            }
        })
    }

    function txfg_get_spl_delivery(data) {
        TXFG_MOD_EXBC_HELPER_PSN_Table.getElementsByTagName("tbody")[0].innerHTML = `<tr><td colspan="13" class="text-center bg-info">Please wait</td></tr>`
        TXFG_MOD_EXBC_HELPER_Alternative_Table.getElementsByTagName("tbody")[0].innerHTML = `<tr><td colspan="3" class="text-center bg-info">Please wait</td></tr>`
        TXFG_MOD_EXBC_HELPER_Statement.innerHTML = ''
        TXFG_MOD_EXBC_HELPER_Alternative_Button_confirm.disabled = true
        document.getElementById('txfg-mod-exbc-div-alert').innerHTML = ''
        $.ajax({
            type: "GET",
            url: "<?=$_ENV['APP_INTERNAL_API']?>supply/document-delivery",
            data: data,
            dataType: "json",
            success: function (response) {
                let ttlrows = response.data.length;
                let mydes = document.getElementById("TXFG_MOD_EXBC_HELPER_PSN_Table_div");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("TXFG_MOD_EXBC_HELPER_PSN_Table")
                let cln = mtabel.cloneNode(true)
                myfrag.appendChild(cln)
                let tabell = myfrag.getElementById("TXFG_MOD_EXBC_HELPER_PSN_Table")
                let tableku2 = tabell.getElementsByTagName("tbody")[0]
                let newrow, newcell, newText;
                tableku2.innerHTML=''
                for (let i = 0; i<ttlrows; i++){
                    newrow = tableku2.insertRow(-1)
                    newcell = newrow.insertCell(0);
                    newcell.innerText = response.data[i].SPL_DOCNO
                    newcell = newrow.insertCell(-1);
                    newcell.innerText = response.data[i].SPL_DOC
                    newcell = newrow.insertCell(-1);
                    newcell.innerText = response.data[i].SPL_LINE
                    newcell = newrow.insertCell(-1);
                    newcell.innerText = response.data[i].SPL_PROCD
                    newcell = newrow.insertCell(-1);
                    newcell.innerText = response.data[i].SPL_FEDR
                    newcell = newrow.insertCell(-1);
                    newcell.innerText = response.data[i].SPL_CAT
                    newcell = newrow.insertCell(-1);
                    newcell.innerText = response.data[i].SPL_MC
                    newcell = newrow.insertCell(-1);
                    newcell.innerText = response.data[i].SPL_ORDERNO
                    newcell = newrow.insertCell(-1);
                    newcell.innerText = response.data[i].SPL_MS
                    newcell = newrow.insertCell(-1);
                    newcell.innerText = response.data[i].SPL_ITMCD
                    newcell = newrow.insertCell(-1);
                    newcell.innerText = response.data[i].SPTNO
                    newcell = newrow.insertCell(-1);
                    newcell.innerText = response.data[i].ITMD1
                    newcell = newrow.insertCell(-1);
                    newcell.classList.add('text-end')
                    newcell.innerText = numeral(response.data[i].SPL_QTYREQ).format(',')
                }
                mydes.innerHTML=''
                mydes.appendChild(myfrag)


                ttlrows = response.CustomsParts.length;
                mydes = document.getElementById("TXFG_MOD_EXBC_HELPER_Alternative_Table_div");
                myfrag = document.createDocumentFragment();
                mtabel = document.getElementById("TXFG_MOD_EXBC_HELPER_Alternative_Table")
                cln = mtabel.cloneNode(true)
                myfrag.appendChild(cln)
                tabell = myfrag.getElementById("TXFG_MOD_EXBC_HELPER_Alternative_Table")
                tableku2 = tabell.getElementsByTagName("tbody")[0]

                tableku2.innerHTML=''
                for (let i = 0; i<ttlrows; i++){
                    newrow = tableku2.insertRow(-1)
                    newrow.onclick = function() {
                        let currentidx = TXFG_hid_idx
                        if(currentidx.value!=''){
                            tableku2.rows[currentidx.value].classList.remove('table-info')
                        }
                        tableku2.rows[i].classList.add('table-info')
                        currentidx.value = i

                        TXFG_MOD_EXBC_HELPER_Statement.innerHTML = `Are you sure that <strong>${response.CustomsParts[i].RPSTOCK_ITMNUM}</strong> is the alternative one ?`
                        TXFG_MOD_EXBC_HELPER_part_new.value = response.CustomsParts[i].RPSTOCK_ITMNUM
                        TXFG_MOD_EXBC_HELPER_Alternative_Button_confirm.disabled = false
                    }
                    newrow.style.cssText = 'cursor:pointer'
                    newcell = newrow.insertCell(0);
                    newcell.innerText = response.CustomsParts[i].RPSTOCK_ITMNUM
                    newcell = newrow.insertCell(-1);
                    newcell.innerText = response.CustomsParts[i].SPTNO
                    newcell = newrow.insertCell(-1);
                    newcell.classList.add('text-end')
                    newcell.innerText = numeral(response.CustomsParts[i].RMQT).format(',')
                }
                mydes.innerHTML=''
                mydes.appendChild(myfrag)
            }, error: function(xhr, xopt, xthrow) {
                alertify.error(`${xthrow}, please try again`);
                TXFG_MOD_EXBC_HELPER_PSN_Table.getElementsByTagName("tbody")[0].innerHTML = `<tr><td colspan="13" class="text-center bg-info">empty</td></tr>`
                TXFG_MOD_EXBC_HELPER_Alternative_Table.getElementsByTagName("tbody")[0].innerHTML = `<tr><td colspan="3" class="text-center bg-info">empty</td></tr>`
            }
        });
    }

    function isJson(str) {
        try {
            JSON.parse(str);
        } catch (e) {
            return false;
        }
        return true;
    }

    function txfg_btn_to_konversi_bahan_baku_e_click() {
        if(txfg_txt_id.value.length===0) {
            alertify.warning('TX ID is required')
            return
        }
        switch(txfg_cmb_bcdoc.value) {
            case '25':
                if(txfg_txt_nopen25.value.trim().length===0) {
                    alertify.warning('Nomor Pendaftaran is required')
                    return
                }
                break;
            case '27':
                if(txfg_txt_nopen.value.trim().length===0) {
                    alertify.warning('Nomor Pendaftaran is required')
                    return
                }
                break;
            case '41':
                if(txfg_txt_nopen41.value.trim().length===0) {
                    alertify.warning('Nomor Pendaftaran is required')
                    return
                }
                break;
            default:
                txfg_cmb_bcdoc.focus()
                return
        }
        window.open("<?=$_ENV['APP_INTERNAL_API']?>report/konversi-bahan-baku?doc="+txfg_txt_id.value, '_blank');
    }

    function TXFG_MOD_EXBC_HELPER_PSN_toggler_onclick(pthis) {
        if(TXFG_MOD_EXBC_HELPER_PSN_Row_container.classList.contains('d-none')) {
            pthis.innerHTML = `<span class="fas fa-chevron-down"></span>`
            TXFG_MOD_EXBC_HELPER_PSN_Row_container.classList.remove('d-none')
        } else {
            pthis.innerHTML = `<span class="fas fa-chevron-right"></span>`
            TXFG_MOD_EXBC_HELPER_PSN_Row_container.classList.add('d-none')
        }
    }

    function TXFG_MOD_EXBC_HELPER_Alternative_Button_confirm_onclick(pthis) {
        if(confirm('Are you sure ?')) {
            const data = {
                doc : txfg_txt_id.value,
                part_code_before : TXFG_MOD_EXBC_HELPER_part.innerText,
                part_code_after : TXFG_MOD_EXBC_HELPER_part_new.value,
                user_id : uidnya
            }
            pthis.disabled = true

            const div_alert = document.getElementById('txfg-mod-exbc-div-alert')
            div_alert.innerHTML = `<div class="alert alert-info alert-dismissible fade show" role="alert">
                                <i class="fas fa-paper-plane fa-bounce"></i> Please wait
                                            </div>`
            $.ajax({
                type: "PUT",
                url: "<?=$_ENV['APP_INTERNAL_API']?>bom-calculation/delivery",
                data: data,
                dataType: "JSON",
                success: function (response) {
                    div_alert.innerHTML = `<div class="alert alert-success alert-dismissible fade show" role="alert">
                                            ${response.message}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>`
                }, error: function(xhr, xopt, xthrow) {
                    alertify.error(`${xthrow}, please try again`);
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
            });
        }
    }

    var TXFGOffcanvas27 = new bootstrap.Offcanvas('#TXFGsploffcanvasBottom')
    TXFGsploffcanvasBottom.addEventListener('shown.bs.offcanvas', event => {
        TXFGsploffcanvasBottomContainer.innerHTML = 'Please wait'
        $.ajax({
            type: "GET",
            url: "<?=base_url('DELV/getCEISA40Status')?>",
            data: {doc : txfg_txt_id.value},
            dataType: "json",
            success: function (response) {
                TXFGsploffcanvasBottomContainer.innerHTML = ''
                let mydes = document.getElementById("TXFGsploffcanvasBottomContainer");
                let myfrag = document.createDocumentFragment();                

                if(response.data.status) {
                    if(response.data.dataRespon) {
                        response.data.dataRespon.forEach((arrayItem, i) => {
                            let _EleRow = document.createElement('div')
                            _EleRow.classList.add('row')
                            let _EleDot = document.createElement('div')
                            _EleDot.classList.add('col-auto', 'text-center', 'flex-column', 'd-none', 'd-sm-flex')
                            
                            let strTemp = ''
                            let strTemp0 = ''
                            switch(i) {
                                case 0:
                                    strTemp = 'border-end order';
                                    strTemp0 = ''
                                    break;
                                case response.data.length-1:
                                    strTemp = '';
                                    strTemp0 = 'border-end'
                                    break;
                                default:
                                    strTemp = 'border-end'
                                    strTemp0 = 'border-end'
                            }
    
                            let _EleDotRow1 = document.createElement('div')
                            _EleDotRow1.classList.add('row' ,'h-50')
                            let _EleDotRow1Col1 = document.createElement('div')
                            _EleDotRow1Col1.classList.add('col')
                            if(strTemp0) {
                                _EleDotRow1Col1.classList.add(strTemp0)
                            }
    
                            _EleDotRow1Col1.innerHTML = '&nbsp;'
                            let _EleDotRow1Col2 = document.createElement('div')
                            _EleDotRow1Col2.classList.add('col')
                            _EleDotRow1Col2.innerHTML = '&nbsp;'
    
                            _EleDotRow1.appendChild(_EleDotRow1Col1)
                            _EleDotRow1.appendChild(_EleDotRow1Col2)
                            _EleDot.appendChild(_EleDotRow1)
    
    
                            let _EleDotM = document.createElement('div')
                            _EleDotM.classList.add('m-2')
                            let _EleDotMSpan = document.createElement('span')
                            _EleDotMSpan.classList.add('badge', 'rounded-pill' ,'bg-light', 'border')
                            _EleDotMSpan.innerHTML = '&nbsp;'
                            _EleDotM.appendChild(_EleDotMSpan)
    
                            _EleDot.appendChild(_EleDotM)
    
                            let _EleDotRow2 = document.createElement('div')
                            
                            
                            _EleDotRow2.classList.add('row', 'h-50')
                            _EleDotRow2.innerHTML = `<div class="col ${strTemp}">&nbsp;</div>
                                            <div class="col">&nbsp;</div>`
    
                            _EleDot.appendChild(_EleDotRow2)
                            _EleRow.appendChild(_EleDot)
    
                            let _EleCol = document.createElement('div')
                            _EleCol.classList.add('col', 'py-2')
    
                            let _EleColCard = document.createElement('div')
                            _EleColCard.classList.add('card')
                            let _EleColCardBody = document.createElement('div')
                            _EleColCardBody.classList.add('card-body')
                            let _EleColCardBodyFloat = document.createElement('div')
                            _EleColCardBodyFloat.classList.add('float-end', 'text-muted')
                            _EleColCardBodyFloat.innerHTML = arrayItem['tanggalRespon']
    
                            let _EleColCardBodyTitle = document.createElement('h4')
                            _EleColCardBodyTitle.classList.add('card-title', 'text-muted')
                            _EleColCardBodyTitle.innerHTML = arrayItem['keterangan']
    
                            let _EleColCardBodyText = document.createElement('p')
                            _EleColCardBodyText.classList.add('card-title', 'text-muted')
                            _EleColCardBodyText.innerHTML = arrayItem['nomorRespon']
    
                            
                            _EleColCardBody.appendChild(_EleColCardBodyFloat)
                            _EleColCardBody.appendChild(_EleColCardBodyTitle)
                            _EleColCardBody.appendChild(_EleColCardBodyText)
                            if(arrayItem['keterangan'].includes('SPPB')) {
                                let _EleColCardBodyButton = document.createElement('button')
                                _EleColCardBodyButton.classList.add('btn', 'btn-primary', 'btn-sm')
                                _EleColCardBodyButton.innerHTML = 'Select'
                                _EleColCardBodyButton.onclick = function() {
                                    if(confirm('Are you sure ?')) {
                                        txfg_txt_sppb27.value = arrayItem['nomorRespon']
                                        TXFGOffcanvas27.hide()
                                    }
                                }
                                _EleColCardBody.appendChild(_EleColCardBodyButton)
                            }
                            _EleColCard.appendChild(_EleColCardBody)
                            _EleCol.appendChild(_EleColCard)                        
    
                            _EleRow.appendChild(_EleCol)
    
                            myfrag.appendChild(_EleRow)
                        });
                    } else {
                        response.data.data.forEach((arrayItem, i) => {
                            let _EleRow = document.createElement('div')
                            _EleRow.classList.add('row')
                            let _EleDot = document.createElement('div')
                            _EleDot.classList.add('col-auto', 'text-center', 'flex-column', 'd-none', 'd-sm-flex')
                            
                            let strTemp = ''
                            let strTemp0 = ''
                            switch(i) {
                                case 0:
                                    strTemp = 'border-end order';
                                    strTemp0 = ''
                                    break;
                                case response.data.length-1:
                                    strTemp = '';
                                    strTemp0 = 'border-end'
                                    break;
                                default:
                                    strTemp = 'border-end'
                                    strTemp0 = 'border-end'
                            }
    
                            let _EleDotRow1 = document.createElement('div')
                            _EleDotRow1.classList.add('row' ,'h-50')
                            let _EleDotRow1Col1 = document.createElement('div')
                            _EleDotRow1Col1.classList.add('col')
                            if(strTemp0) {
                                _EleDotRow1Col1.classList.add(strTemp0)
                            }
    
                            _EleDotRow1Col1.innerHTML = '&nbsp;'
                            let _EleDotRow1Col2 = document.createElement('div')
                            _EleDotRow1Col2.classList.add('col')
                            _EleDotRow1Col2.innerHTML = '&nbsp;'
    
                            _EleDotRow1.appendChild(_EleDotRow1Col1)
                            _EleDotRow1.appendChild(_EleDotRow1Col2)
                            _EleDot.appendChild(_EleDotRow1)
    
    
                            let _EleDotM = document.createElement('div')
                            _EleDotM.classList.add('m-2')
                            let _EleDotMSpan = document.createElement('span')
                            _EleDotMSpan.classList.add('badge', 'rounded-pill' ,'bg-light', 'border')
                            _EleDotMSpan.innerHTML = '&nbsp;'
                            _EleDotM.appendChild(_EleDotMSpan)
    
                            _EleDot.appendChild(_EleDotM)
    
                            let _EleDotRow2 = document.createElement('div')
                            
                            
                            _EleDotRow2.classList.add('row', 'h-50')
                            _EleDotRow2.innerHTML = `<div class="col ${strTemp}">&nbsp;</div>
                                            <div class="col">&nbsp;</div>`
    
                            _EleDot.appendChild(_EleDotRow2)
                            _EleRow.appendChild(_EleDot)
    
                            let _EleCol = document.createElement('div')
                            _EleCol.classList.add('col', 'py-2')
    
                            let _EleColCard = document.createElement('div')
                            _EleColCard.classList.add('card')
                            let _EleColCardBody = document.createElement('div')
                            _EleColCardBody.classList.add('card-body')
                            let _EleColCardBodyFloat = document.createElement('div')
                            _EleColCardBodyFloat.classList.add('float-end', 'text-muted')
                            _EleColCardBodyFloat.innerHTML = arrayItem['tanggalRespon']
    
                            let _EleColCardBodyTitle = document.createElement('h4')
                            _EleColCardBodyTitle.classList.add('card-title', 'text-muted')
                            _EleColCardBodyTitle.innerHTML = arrayItem['namaRespon']
    
                            let _EleColCardBodyText = document.createElement('p')
                            _EleColCardBodyText.classList.add('card-title', 'text-muted')
                            _EleColCardBodyText.innerHTML = arrayItem['nomorRespon']
    
                            
                            _EleColCardBody.appendChild(_EleColCardBodyFloat)
                            _EleColCardBody.appendChild(_EleColCardBodyTitle)
                            _EleColCardBody.appendChild(_EleColCardBodyText)
                            if(arrayItem['namaRespon'].includes('SPPB')) {
                                let _EleColCardBodyButton = document.createElement('button')
                                _EleColCardBodyButton.classList.add('btn', 'btn-primary', 'btn-sm')
                                _EleColCardBodyButton.innerHTML = 'Select'
                                _EleColCardBodyButton.onclick = function() {
                                    if(confirm('Are you sure ?')) {
                                        txfg_txt_sppb27.value = arrayItem['nomorRespon']
                                        TXFGOffcanvas27.hide()
                                    }
                                }
                                _EleColCardBody.appendChild(_EleColCardBodyButton)
                            }
                            _EleColCard.appendChild(_EleColCardBody)
                            _EleCol.appendChild(_EleColCard)                        
    
                            _EleRow.appendChild(_EleCol)
    
                            myfrag.appendChild(_EleRow)
                        });
                    }
                    mydes.innerHTML = ''
                    mydes.appendChild(myfrag)
                }
            }, error:function(xhr,ajaxOptions, throwError) {
                TXFGsploffcanvasBottomContainer.innerHTML = ''
            }
        });
    })
</script>