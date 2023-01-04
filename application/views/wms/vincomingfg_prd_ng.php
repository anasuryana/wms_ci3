<div style="padding: 10px">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 mb-1 text-center">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="rcvfgprd_abn_typefg" id="rcvfgprd_abn_typereg" value="" checked>
                    <label class="form-check-label" for="rcvfgprd_abn_typereg">Regular</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="rcvfgprd_abn_typefg" id="rcvfgprd_abn_typekd" value="KD">
                    <label class="form-check-label" for="rcvfgprd_abn_typekd">KD</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="rcvfgprd_abn_typefg" id="rcvfgprd_abn_typeasp" value="ASP">
                    <label class="form-check-label" for="rcvfgprd_abn_typeasp">ASP</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="rcvfgprd_abn_typefg" id="rcvfgprd_abn_typenm" value="NM">
                    <label class="form-check-label" for="rcvfgprd_abn_typenm">New Model</label>
                </div>
            </div>
        </div>
        <div class="d-none" id="rcvfgprd_abn_div_ka">
            <div class="row">
                <div class="col-md-4 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text">Item Code</span>
                        <input type="text" class="form-control" id="rcvfgprd_abn_txt_itemcode" maxlength=50 required readonly>
                        <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                    </div>
                </div>
                <div class="col-md-4 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text">Lot No</span>
                        <input type="text" class="form-control" id="rcvfgprd_abn_txt_lotno" maxlength=50 required readonly>
                        <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                    </div>
                </div>
                <div class="col-md-4 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text">Qty</span>
                        <input type="text" class="form-control" id="rcvfgprd_abn_txt_qty" maxlength="5" required style="text-align: right" readonly>
                        <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text">Remark</span>
                        <input type="text" class="form-control" id="rcvfgprd_abn_txt_newmodel_ka" maxlength="12" required style="text-align: right">
                        <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row d-none" id="rcvfgprd_abn_div_nm">
            <div class="col-md-12 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text">Remark</span>
                    <input type="text" class="form-control" id="rcvfgprd_abn_txt_newmodel" maxlength="12" required style="text-align: right">
                    <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text">Job No</span>
                    <select class="form-select" id="rcvfgprd_abn_cmb_job">
                        <option value="-">-</option>
                        <?php
                        $todis = "";
                        foreach ($rsjob as $r) {
                            $todis .= "<option value='" . trim($r['PDPP_WONO']) . "'>" . $r['PDPP_WONO'] . "</option>";
                        }
                        echo $todis;
                        ?>
                    </select>
                    <button class="btn btn-success" id="rcvfgprd_abn_refreshjob"><i class="fas fa-sync-alt"></i></button>
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text">Reason</span>
                    <select class="form-select" id="rcvfgprd_abn_cmb_reason">
                        <option value="-">-</option>
                        <option value="SCRAP">SCRAP</option>
                        <option value="WAITING QA CONFIRMATION">WAITING QA CONFIRMATION</option>
                        <option value="WAITING REPAIR">WAITING REPAIR</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text">ID</span>
                    <input type="text" class="form-control" id="rcvfgprd_abn_txt_code" ondblclick="rcvfgprd_abn_txt_code_edck(this)" maxlength="100" readonly placeholder="code here..." required style="text-align:center">
                    <span class="input-group-text"><i class="fas fa-barcode" id="lbltes_simul"></i></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <span class="badge bg-info" id="rcvfgprd_abn_progress"></span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="rcvfgprd_abn_divku">
                    <table id="rcvfgprd_abn_tbl" class="table table-sm table-striped table-hover table-bordered" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Doc No</th>
                                <th>Item Code</th>
                                <th>Item Name</th>
                                <th class="text-end">Qty</th>
                                <th>Unit Measure</th>
                                <th>User</th>
                                <th>ID</th>
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

<div class="modal fade" id="rcvfgprd_abn_modprevent">
    <div class="modal-dialog modal-lg bg-danger">
        <div class="modal-content bg-danger text-white">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Oops</h4>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col mb-1" id="rcvfgprd_abn_divreff">

                    </div>
                </div>
                <div class="row">
                    <div class="col mb-1">
                        <blockquote class="blockquote">
                            <p class="mb-0" id="rcvfgprd_abn_divmodal"></p>
                        </blockquote>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-primary" id="rcvfgprd_abn_closee">OK</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var rcvfgprd_abn_ismodalshown = false;
    var rcvfgprd_abn_strkeys = '';

    function rcvfgprd_abn_showinfoofus() {
        let mymodal = new bootstrap.Modal(document.getElementById("rcvfgprd_abn_modprevent"), {
            backdrop: 'static',
            keyboard: false
        });
        mymodal.show();
    }
    $("#rcvfgprd_abn_closee").click(function(e) {
        rcvfgprd_abn_ismodalshown = false;
        $("#rcvfgprd_abn_modprevent").modal('hide');
    });
    $('#rcvfgprd_abn_cmb_job').change(function() {
        document.getElementsByTagName("body")[0].focus();
    });

    function rcvfgprd_abn_f_refreshjob() {
        $.ajax({
            type: "get",
            url: "<?= base_url('INCFG/get_joblbl_ost') ?>",
            dataType: "json",
            success: function(response) {
                let ttlrows = response.length;
                let tohtml = '<option value="-">-</option>';
                for (let i = 0; i < ttlrows; i++) {
                    tohtml += "<option value='" + response[i].PDPP_WONO + "'>" + response[i].PDPP_WONO + "</option>";
                }
                $("#rcvfgprd_abn_cmb_job").html(tohtml);
                alertify.success("refreshed");
            },
            error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow);
            }
        });
    }
    $("#rcvfgprd_abn_refreshjob").click(function(e) {
        rcvfgprd_abn_f_refreshjob();
    });

    function rcvfgprd_abn_f_send(pkey) {
        let mkey = pkey;
        let mjob = document.getElementById('rcvfgprd_abn_cmb_job').value;
        let mtypefg = $("input[name='rcvfgprd_abn_typefg']:checked").val();
        let mitem = document.getElementById("rcvfgprd_abn_txt_itemcode").value;
        let mlotno = document.getElementById("rcvfgprd_abn_txt_lotno").value;
        let mqty = document.getElementById("rcvfgprd_abn_txt_qty").value;
        let mremark = document.getElementById("rcvfgprd_abn_txt_newmodel").value;
        let mremark_ka = document.getElementById("rcvfgprd_abn_txt_newmodel_ka").value
        const mreason = document.getElementById('rcvfgprd_abn_cmb_reason')
        if (mreason.value == '-') {
            alertify.warning("Reason is required")
            document.getElementById('rcvfgprd_abn_txt_code').value = ''
            mreason.focus()
            return
        }
        let ismanualselect = '0';
        if (mtypefg != '' && mkey.length != 10 && mtypefg != 'NM') { // VALIDASI KD ATAU ASP
            if (mitem.trim() == '') {
                alertify.warning("Please enter item code!");
                document.getElementById("rcvfgprd_abn_txt_itemcode").focus();
                return;
            }
            if (mlotno.trim() == '') {
                alertify.warning("Please enter lot no!");
                document.getElementById("rcvfgprd_abn_txt_lotno").focus();
                return;
            }
            if (mqty.trim() == '') {
                alertify.warning("Please enter qty!");
                document.getElementById("rcvfgprd_abn_txt_qty").focus();
                return;
            }
            if (isNaN(mqty)) {
                alertify.warning("the number is invalid, Please enter right qty!");
                document.getElementById("rcvfgprd_abn_txt_qty").focus();
                return;
            }
        }
        if (mtypefg == 'NM') {
            if (mremark.trim() == '') {
                alertify.warning('Remark is required');
                document.getElementById("rcvfgprd_abn_txt_newmodel").focus();
                return;
            }
        }
        if (mjob.trim() == '-' && mkey.length != 10) { // VALIDASI JOB UNTUK EPSON
            ismanualselect = '0';
            //ADDITIONAL VALIDATION REGARDING JOB FROM QC
            if (mkey.includes("|")) {
                let myar = mkey.split('|');
                if (!myar[0].includes("&")) { // HANDLE REGULAR PART EPSON
                    let myitem = myar[0].substr(2, myar[0].length);
                    let myjob = myar[2].substr(5, 5);
                    if (myjob.substr(0, 1) == '0') {
                        myjob = myar[2].substr(6, 4);
                    }
                    mjob = moment().format('YY') + '-' + myjob + '-' + myitem;
                }
            } else {
                let myjob = mlotno.substr(3, 5);
                if (myjob.substr(0, 1) == '0') {
                    myjob = mlotno.substr(4, 4);
                }
                if (mtypefg != '') { // HANDLE KD OR ASP
                    mjob = moment().format('YY') + '-' + myjob + '-' + mitem;
                }
                document.getElementById('rcvfgprd_abn_cmb_job').focus();
            }
        } else if (mjob.trim() != '-' && mkey.length != 10) { // IF USER CHOOSE JOB MANUALY CHECK LABEL LOT AND JOB
            ismanualselect = '1';
            if (mkey.includes("|")) { // HANDLE REGULAR PART
                let myar = mkey.split('|');
                if (!myar[0].includes("&")) { // HANDLE REGULAR PART EPSON
                    let myitem = myar[0].substr(2, myar[0].length);
                    let myjob = myar[2].substr(5, 5);
                    let ajob = mjob.split('-');
                    if (myjob.substr(0, 1) == '0') {
                        myjob = myar[2].substr(6, 4);
                    }

                    if (myjob != ajob[1]) {
                        document.getElementById('rcvfgprd_abn_divmodal').innerHTML = "Lot and Job does not match";
                        document.getElementById('rcvfgprd_abn_divreff').innerHTML = mkey;
                        rcvfgprd_abn_ismodalshown = true;
                        rcvfgprd_abn_showinfoofus();
                        return;
                    }
                }
            }
        }
        rcvfgprd_abn_strkeys = pkey;
        if (mkey.trim() != '' && rcvfgprd_abn_ismodalshown == false) {
            document.getElementById('rcvfgprd_abn_progress').innerHTML = 'Please wait... <i class="fas fa-spinner fa-spin"></i>';
            $.ajax({
                type: "post",
                url: "<?= base_url("INCFG/setprd") ?>",
                data: {
                    inkey: mkey,
                    injob: mjob,
                    infgtype: mtypefg,
                    initemcd: mitem,
                    inlot: mlotno,
                    inqty: mqty,
                    inremark: mremark,
                    inremark_ka: mremark_ka,
                    inismanual: ismanualselect,
                    inreason: mreason.value
                },
                dataType: "json",
                success: function(response) {
                    document.getElementById('rcvfgprd_abn_progress').innerHTML = '';
                    rcvfgprd_abn_evt_gettodayscan();
                    document.getElementById("rcvfgprd_abn_txt_code").value = "";
                    if (response[0].cd == "0") {
                        alertify.warning(response[0].msg);
                        document.getElementById('rcvfgprd_abn_divmodal').innerHTML = response[0].msg;
                        document.getElementById('rcvfgprd_abn_divreff').innerHTML = rcvfgprd_abn_strkeys;
                        rcvfgprd_abn_ismodalshown = true;
                        rcvfgprd_abn_showinfoofus();
                    } else {
                        alertify.message(response[0].msg);
                        if (response[0].typefg != "") {
                            document.getElementById("rcvfgprd_abn_txt_itemcode").value = "";
                            document.getElementById("rcvfgprd_abn_txt_code").focus();
                            document.getElementById("rcvfgprd_abn_txt_lotno").value = "";
                            document.getElementById("rcvfgprd_abn_txt_qty").value = "";
                        }
                    }
                },
                error: function(xhr, xopt, xthrow) {
                    document.getElementById("rcvfgprd_abn_txt_code").value = "";
                    document.getElementById('rcvfgprd_abn_progress').innerHTML = '';
                    alertify.error(xthrow);
                }
            });
        } else {
            alertify.warning("please enter serial fg first !");
            document.getElementById("rcvfgprd_abn_txt_code").value = "";
            return;
        }
    }

    function rcvfgprd_abn_e_clearinput() {
        document.getElementById('rcvfgprd_abn_txt_itemcode').value = '';
        document.getElementById('rcvfgprd_abn_txt_lotno').value = '';
        document.getElementById('rcvfgprd_abn_txt_qty').value = '';
        document.getElementById('rcvfgprd_abn_txt_newmodel_ka').value = '';
        document.getElementById("rcvfgprd_abn_txt_newmodel").value = '';
    }

    $("input[name='rcvfgprd_abn_typefg']").change(function() {
        document.getElementById('rcvfgprd_abn_txt_code').focus();
        let curv = $(this).val();
        rcvfgprd_abn_e_clearinput();
        if (curv == 'KD' || curv == 'ASP') {
            $("#rcvfgprd_abn_div_ka").removeClass("d-none");
            $("#rcvfgprd_abn_div_nm").addClass("d-none");
            document.getElementById("rcvfgprd_abn_txt_code").focus();
            document.getElementById('rcvfgprd_abn_txt_code').readOnly = false;
            try {
                onScan.detachFrom(document);
            } catch (err) {
                console.log("detached" + err);
            }
        } else if (curv == '') {
            $("#rcvfgprd_abn_div_ka").addClass("d-none");
            $("#rcvfgprd_abn_div_nm").addClass("d-none");
            document.getElementById("rcvfgprd_abn_txt_code").focus();
            document.getElementById('rcvfgprd_abn_txt_code').readOnly = true;
            rcvfgprd_abn_initOnScan();
        } else {
            $("#rcvfgprd_abn_div_ka").addClass("d-none");
            $("#rcvfgprd_abn_div_nm").removeClass("d-none");
            document.getElementById("rcvfgprd_abn_txt_newmodel").focus();
            document.getElementById('rcvfgprd_abn_txt_code').readOnly = true;
            rcvfgprd_abn_initOnScan();
        }
    });

    $("#rcvfgprd_abn_txt_itemcode").keypress(function(e) {
        if (e.which == 13) {
            let mitem = $(this).val();
            if (mitem.trim() == '') {
                alertify.warning("could not be empty");
                return;
            }
            $.ajax({
                type: "get",
                url: "<?= base_url("MSTITM/checkexist") ?>",
                data: {
                    initem: mitem
                },
                dataType: "json",
                success: function(response) {
                    if (response.data[0].cd == "11") {
                        let ttlwo = response.datahead.length;
                        let tohtml = '<option value="-">-</option>';
                        for (let i = 0; i < ttlwo; i++) {
                            tohtml += "<option value='" + response.datahead[i].PDPP_WONO.trim() + "'>" + response.datahead[i].PDPP_WONO + "</option>";
                        }
                        $("#rcvfgprd_abn_cmb_job").html(tohtml);
                        if (ttlwo == 0) {
                            alertify.warning("there is no Job for this item");
                            document.getElementById("rcvfgprd_abn_txt_itemcode").value = "";
                        } else {
                            document.getElementById("rcvfgprd_abn_txt_lotno").focus();
                        }
                    } else {
                        document.getElementById("rcvfgprd_abn_txt_itemcode").value = "";
                        alertify.warning(response.data[0].msg);
                    }
                },
                error: function(xhr, xopt, xthrow) {
                    alertify.error(xthrow);
                }
            });
        }
    });
    $("#rcvfgprd_abn_txt_lotno").keypress(function(e) {
        if (e.which == 13) {
            let cval = $(this).val();
            if (cval.trim() == "") {
                alertify.warning("Please enter lot no");
            } else {
                if (cval.substr(0, 3) == '070') {
                    document.getElementById("rcvfgprd_abn_txt_qty").focus();
                } else {
                    alertify.warning('Lot Number is not valid');
                    $(this).val('');
                }
            }
        }
    });
    $("#rcvfgprd_abn_txt_qty").keypress(function(e) {
        if (e.which == 13) {
            let cval = $(this).val();
            if (cval.trim() == '') {
                alertify.warning("Please enter valid number !");
                $(this).val('');
                return;
            } else {
                if (cval.length < 5) {
                    if (numeral(cval).value() > 0) {
                        $("#rcvfgprd_abn_txt_qty").blur();
                        // alertify.success('lanjuutkannn');
                        document.getElementById('rcvfgprd_abn_txt_code').focus();
                    } else {
                        document.getElementById("rcvfgprd_abn_txt_qty").value = "";
                    }
                } else {
                    alertify.warning('QTY is not valid');
                    $(this).val('');
                }

            }
        }
    });
    $("#rcvfgprd_abn_txt_newmodel").keypress(function(e) {
        if (e.which == 13) {
            let cval = $(this).val();
            if (cval.trim() == "") {
                alertify.warning("Please enter Remark !");
            } else {
                $("#rcvfgprd_abn_txt_newmodel").blur();
            }
        }
    });
    $("#rcvfgprd_abn_txt_newmodel_ka").keypress(function(e) {
        if (e.which == 13) {
            $("#rcvfgprd_abn_txt_newmodel_ka").blur();
        }
    });

    rcvfgprd_abn_evt_gettodayscan();

    function rcvfgprd_abn_evt_gettodayscan() {
        $.ajax({
            type: "get",
            url: "<?= base_url('INCFG/gettodayscanprd') ?>",
            dataType: "json",
            success: function(response) {
                let ttlrows = response.data.length;
                let mydes = document.getElementById("rcvfgprd_abn_divku");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("rcvfgprd_abn_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("rcvfgprd_abn_tbl");
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                let newrow, newcell, newText;
                tableku2.innerHTML = '';
                let msts = '';
                for (let i = 0; i < ttlrows; i++) {
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0);
                    newText = document.createTextNode((i + 1));
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(1);
                    newText = document.createTextNode(response.data[i].ITH_DOC);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(2);
                    newText = document.createTextNode(response.data[i].ITH_ITMCD.trim());
                    newcell.appendChild(newText);
                    newcell.style.cssText = 'text-align: center';
                    newcell = newrow.insertCell(3);
                    newText = document.createTextNode(response.data[i].MITM_ITMD1);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(4);
                    newText = document.createTextNode(numeral(response.data[i].ITH_QTY).format(','));
                    newcell.appendChild(newText);
                    newcell.style.cssText = 'text-align: right';

                    newcell = newrow.insertCell(5);
                    newText = document.createTextNode(response.data[i].MITM_STKUOM);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(6);
                    newText = document.createTextNode(response.data[i].MSTEMP_FNM);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(7);
                    newText = document.createTextNode(response.data[i].ITH_SER);
                    newcell.appendChild(newText);
                }
                mydes.innerHTML = '';
                mydes.appendChild(myfrag);
            },
            error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow);
            }
        });
    }

    function scanHandler(e) {
        console.log("[scanHandler]: Code: " + e.detail.code);
    }

    function scanErrorHandler(e) {
        var sFormatedErrorString = "Error Details: {\n";
        for (var i in e.detail) {
            sFormatedErrorString += '    ' + i + ': ' + e.detail[i] + ",\n";
        }
        sFormatedErrorString = sFormatedErrorString.trim().replace(/,$/, '') + "\n}";
        console.log("[scanErrorHandler]: " + sFormatedErrorString);
    }

    function rcvfgprd_abn_initOnScan() {
        let options = {
            // timeBeforeScanTest: 100, 
            // avgTimeByChar: 60,
            // minLength: 5, 			
            // scanButtonLongPressTime: 500, 
            // stopPropagation: false, 
            // preventDefault: false,
            // reactToPaste: true,
            // reactToKeyDown: true,
            // singleScanQty: 1
            suffixKeyCodes: [13], // enter-key expected at the end of a scan
            reactToPaste: true, // Compatibility to built-in scanners in paste-mode (as opposed to keyboard-mode)
            minLength: 5,
        }

        options.onScan = function(barcode, qty) {
            let tesnya = barcode;
            let ates = tesnya.split(String.fromCharCode(16));
            let e = $.Event('keypress');
            e.which = 13;
            tesnya = '';
            for (let i = 0; i < ates.length; i++) {
                tesnya += ates[i];
            }
            tesnya = tesnya.replace(/Ü/g, "|");
            let mactiveEl = document.activeElement.id;
            let mactiveTag = document.activeElement.tagName;
            let mis_selpickershow = $("[role=combobox]").hasClass("show");
            if (mis_selpickershow) {

            } else {
                if (mactiveEl == "rcvfgprd_abn_txt_itemcode" || mactiveEl == "rcvfgprd_abn_txt_lotno" || mactiveEl == "rcvfgprd_abn_txt_qty" || mactiveEl == "rcvfgprd_abn_txt_newmodel") {
                    document.getElementById(mactiveEl).value = tesnya;
                    $("#" + mactiveEl).trigger(e);
                } else {
                    document.getElementById('rcvfgprd_abn_txt_code').value = tesnya;
                    rcvfgprd_abn_f_send(tesnya);
                }
            }
        };
        options.keyCodeMapper = function(oEvent) {
            console.log("mapper: " + oEvent.key)
            if (oEvent.key == '|') {
                return '|';
            }
            if (oEvent.key == 'Enter') {
                return ' ';
            }
            if (oEvent.key == '_') {
                return '_';
            }
            return oEvent.key
            // Fall back to the default decoder in all other cases
            // return onScan.decodeKeyEvent(oEvent);
        }
        options.onScanError = function(err) {
            let sFormatedErrorString = "Error Details: {\n";
            for (let i in err) {
                sFormatedErrorString += '    ' + i + ': ' + err[i] + ",\n";
            }
            sFormatedErrorString = sFormatedErrorString.trim().replace(/,$/, '') + "\n}";
            // console.log("[onScanError]: " + sFormatedErrorString);
        };

        options.onKeyProcess = function(iKey, oEvent) {
            // console.log("[onKeyProcess]: Processed key code: " + iKey);
        };
        // options.onKeyDetect = function(iKey, oEvent){
        //     console.log("[onKeyDetect]: Detected key code: " + iKey);
        // };				
        options.onKeyDetect = function(iKeyCode) { // output all potentially relevant key events - great for debugging!
            // console.log('Pressed: ' + iKeyCode);
        }
        options.onScanButtonLongPress = function() {
            // console.log("[onScanButtonLongPress]: ScanButton has been long-pressed");
        };

        options.onPaste = function(sPasteString) {
            // console.log("[onPaste]: Data has been pasted: " + sPasteString);
        }

        document.addEventListener('scan', scanHandler);
        document.addEventListener('scanError', scanErrorHandler);

        try {
            onScan.attachTo(document, options);
            // console.log("onScan Started!");
        } catch (e) {
            onScan.setOptions(document, options);
            // console.log("onScansettings changed!");
        }
    }
    rcvfgprd_abn_initOnScan();
    $("#rcvfgprd_abn_typereg").click(function(e) {
        rcvfgprd_abn_f_refreshjob();
    });
    $("#lbltes_simul").click(function(e) {
        document.getElementById("rcvfgprd_abn_txt_code").value = "";
    });
    $("#rcvfgprd_abn_txt_code").keypress(function(e) {
        if (e.which == 13) {
            let kurkey = $(this).val();
            if (kurkey.length >= 16) {
                let mtypefg = $("input[name='rcvfgprd_abn_typefg']:checked").val()
                if (kurkey.includes('|') && mtypefg != '') {
                    const akurkey = kurkey.split("|")
                    document.getElementById('rcvfgprd_abn_txt_itemcode').value = akurkey[0].substr(2, 9)
                    document.getElementById('rcvfgprd_abn_txt_lotno').value = akurkey[2].substr(2, akurkey[2].length)
                    document.getElementById('rcvfgprd_abn_txt_qty').value = akurkey[3].substr(2, akurkey[3].length)
                    document.getElementById('rcvfgprd_abn_txt_code').value = akurkey[5].substr(2, akurkey[5].length)
                    kurkey = akurkey[5].substr(2, akurkey[5].length)
                }
                rcvfgprd_abn_f_send(kurkey);
            } else {
                $(this).val('');
                alertify.warning('Please scan Reff. No ');
            }
        }
    });

    function rcvfgprd_abn_txt_code_edck(p) {
        if (p.readOnly) {
            p.readOnly = false
            onScan.detachFrom(document)
        } else {
            p.readOnly = true
            rcvfgprd_abn_initOnScan()
        }
    }
</script>