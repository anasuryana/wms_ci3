<div style="padding: 5px">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 mb-1">
                <h2><span class="badge bg-info">1 <i class="fas fa-hand-point-right"></i></span> <span class="badge bg-info">From</span></h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text">Old Reff No</span>
                    <input type="text" class="form-control" id="relable_oldreff" required>
                </div>
            </div>
            <div class="col-md-4 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text">Job Number</span>
                    <input type="text" class="form-control" id="relable_oldjob" required readonly>
                </div>
            </div>
            <div class="col-md-4 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text">Item Code</span>
                    <input type="text" class="form-control" id="relable_olditemcd" required readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text">SPQ</span>
                    <input type="text" class="form-control" id="relable_spq" required readonly>
                </div>
            </div>
            <div class="col-md-6 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text">Qty</span>
                    <input type="text" class="form-control" id="relable_oldqty" required readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-3 pr-1">
                <h3>Transaction History</h3>
                <div class="table-responsive" id="relable_divku">
                    <table id="relable_tbl" class="table table-striped table-bordered table-sm table-hover" style="font-size:75%">
                        <thead class="table-light">
                            <tr>
                                <th class="align-middle">FORM</th>
                                <th class="align-middle">Warehouse</th>
                                <th class="align-middle">Location</th>
                                <th class="align-middle">Document</th>
                                <th class="text-right">Qty</th>
                                <th class="align-middle">Time</th>
                                <th class="align-middle">PIC</th>
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
                <h2><span class="badge bg-info">2 <i class="fas fa-hand-point-right"></i></span> <span class="badge bg-info">To</span></h2>
            </div>
        </div>
        <div id="relable_div_rnm">
            <div class="row">
                <div class="col-md-3 mb-1 pr-1">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text">New Reff No</span>
                        <input type="text" class="form-control" id="relable_newreff" required>
                        <input type="hidden" id="relable_rawtxt1">
                    </div>
                </div>
                <div class="col-md-4 mb-1 pr-1">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text">Job Number</span>
                        <input type="text" class="form-control" id="relable_newjob" required readonly>
                    </div>
                </div>
                <div class="col-md-3 mb-1 pr-1">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text">Item Code</span>
                        <input type="text" class="form-control" id="relable_newitem" required readonly>
                    </div>
                </div>
                <div class="col-md-2 mb-1 pr-1">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text">Qty</span>
                        <input type="text" class="form-control" id="relable_newqty" required readonly>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 mb-1 text-center">
                <div class="btn-group btn-group-sm">
                    <button title="New Entry" type="button" class="btn btn-outline-primary" id="relable_btn_new"><i class="fas fa-file"></i></button>
                    <button title="Save" type="button" class="btn btn-outline-primary" id="relable_btn_save"><i class="fas fa-save"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function relable_e_clear() {
        document.getElementById('relable_oldreff').value = '';
        document.getElementById('relable_oldjob').value = '';
        document.getElementById('relable_olditemcd').value = '';
        document.getElementById('relable_spq').value = '';
        document.getElementById('relable_oldqty').value = '';
        document.getElementById('relable_newreff').value = '';
        document.getElementById('relable_newjob').value = '';
        document.getElementById('relable_newitem').value = '';
        document.getElementById('relable_newqty').value = '';
        document.getElementById('relable_oldreff').readOnly = false;
        $("#relable_tbl tbody").empty();
    }
    $("#relable_btn_new").click(function(e) {
        relable_e_clear();
        document.getElementById('relable_oldreff').readOnly = false;
        document.getElementById('relable_newreff').readOnly = false;
        document.getElementById('relable_oldreff').focus();
    });
    $("#relable_oldreff").keypress(function(e) {
        if (e.which == 13) {
            let moldreff = $(this).val();
            if (moldreff.trim() == '') {
                alertify.warning('Please entry reff no !');
                return;
            }
            if (moldreff.includes("|")) {
                let ar = moldreff.split("|");
                moldreff = ar[5].substr(2, ar[5].length - 2);
                $(this).val(moldreff);
            }
            $.ajax({
                type: "get",
                url: "<?= base_url('SER/getproperties_n_tx') ?>",
                data: {
                    inid: moldreff
                },
                dataType: "json",
                success: function(response) {
                    if (response.status[0].cd == '1') {
                        if (!response.data[0].SER_LOTNO) {
                            alertify.warning('the label is not valid for this function')
                            document.getElementById('relable_oldreff').value = '';
                            return
                        }
                        document.getElementById('relable_oldreff').readOnly = true;
                        document.getElementById('relable_oldjob').value = response.data[0].SER_DOC;
                        document.getElementById('relable_olditemcd').value = response.data[0].SER_ITMID.trim();
                        document.getElementById('relable_spq').value = numeral(response.data[0].MITM_SPQ).format(',');
                        document.getElementById('relable_oldqty').value = numeral(response.data[0].SER_QTY).format(',');

                        ///load tx history
                        let ttlrows = response.tx.length;
                        let mydes = document.getElementById("relable_divku")
                        let myfrag = document.createDocumentFragment();
                        let mtabel = document.getElementById("relable_tbl");
                        let cln = mtabel.cloneNode(true);
                        myfrag.appendChild(cln);
                        let tabell = myfrag.getElementById("relable_tbl");
                        let tableku2 = tabell.getElementsByTagName("tbody")[0]
                        let newrow, newcell, newText;
                        tableku2.innerHTML = '';
                        for (let i = 0; i < ttlrows; i++) {
                            newrow = tableku2.insertRow(-1);
                            newcell = newrow.insertCell(0);
                            newText = document.createTextNode(response.tx[i].ITH_FORM);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(1);
                            newText = document.createTextNode(response.tx[i].ITH_WH);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(2);
                            newText = document.createTextNode(response.tx[i].ITH_LOC);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(3);
                            newText = document.createTextNode(response.tx[i].ITH_DOC);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(4);
                            newText = document.createTextNode(numeral(response.tx[i].ITH_QTY).format(','));
                            newcell.style.cssText = 'text-align: right';
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(5);
                            newText = document.createTextNode(response.tx[i].ITH_LUPDT);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(6);
                            newText = document.createTextNode(response.tx[i].PIC);
                            newcell.appendChild(newText);
                        }
                        mydes.innerHTML = '';
                        mydes.appendChild(myfrag);
                        ///end load               
                        document.getElementById('relable_newreff').focus();
                    } else {
                        alertify.message(response.status[0].msg);
                        document.getElementById('relable_oldreff').value = '';
                    }
                },
                error(xhr, xopt, xthrow) {
                    alertify.error(xthrow);
                }
            })
        }
    });

    $("#relable_newreff").keypress(function(e) {
        if (e.which == 13) {
            let nreff = $(this).val();
            let rawtext = nreff.toUpperCase();
            let oldreff = document.getElementById('relable_oldreff').value;
            let olditem = document.getElementById('relable_olditemcd').value;
            let oldqty = document.getElementById('relable_oldqty').value;
            let oldjob = document.getElementById('relable_oldjob').value.toUpperCase().trim();
            let oldyear = oldjob.substr(0, 2);
            oldqty = numeral(oldqty).value();
            let tempoldjob = oldjob.split('-')
            tempoldjob = tempoldjob[1]
            if (nreff.includes("|")) {
                document.getElementById('relable_rawtxt1').value = nreff;
                let ar = nreff.split("|");
                let newitem = ar[0].substr(2, ar[0].length - 2);
                let newqty = ar[3].substr(2, ar[3].length - 2);
                let thelot = ar[2].substr(2, ar[2].length - 2);
                let tempjob = thelot.substr(3, 5);
                if (tempjob.substr(0, 1) == '0') {
                    tempjob = tempjob.substr(1, 4);
                }
                let newjob = oldyear + '-' + tempjob + '-' + newitem

                ///#1 check item code
                let olditem_ = olditem.substr(0, 9)
                let newitem_ = newitem
                if (newitem_ != olditem_) {
                    alertify.warning('New Item is not same with old item, please compare the label !');
                    $(this).val('');
                    return;
                }
                ///# check qty
                if (numeral(newqty).value() != oldqty) {
                    alertify.warning('qty must be same !');
                    $(this).val('');
                    return;
                }
                let com_oldjob = oldjob.replace('KD', '')
                com_oldjob = com_oldjob.replace('ES2', '')
                com_oldjob = com_oldjob.replace('ES', '')
                com_oldjob = com_oldjob.replace('ASP', '')



                let com_newjob = newjob.replace('ES2', '')
                com_newjob = com_newjob.replace('ES', '')
                ///# check job

                let com_oldjob_ = com_oldjob.toUpperCase()
                let com_newjob_ = com_newjob.toUpperCase()
                if (com_oldjob_ != com_newjob_) {
                    if (tempjob === tempoldjob) {} else {
                        alertify.warning('job is not same, please check the label again ' + com_oldjob_ + ' != ' + com_newjob_);
                        $(this).val('')
                        return;
                    }
                }
                nreff = ar[5].substr(2, ar[5].length - 2);
                if (nreff == oldreff) {
                    alertify.warning('could not transfer to the same reff no')
                    $(this).val('')
                    return;
                }
                $(this).val(nreff)
                $.ajax({
                    type: "get",
                    url: "<?= base_url('SER/validate_newreff_relable') ?>",
                    data: {
                        inoldreff: oldreff,
                        inolditem: olditem,
                        inoldjob: oldjob,
                        inoldqty: oldqty,
                        inrawtext: rawtext
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.status[0].cd != '0') {
                            let newreff = response.status[0].rawtext;
                            let ar = newreff.split("|");
                            let newitem = ar[0].substr(2, ar[0].length - 2);
                            let newqty = ar[3].substr(2, ar[3].length - 2);
                            let thelot = ar[2].substr(2, ar[2].length - 2);
                            let tempjob = thelot.substr(3, 5);
                            if (tempjob.substr(0, 1) == '0') {
                                tempjob = tempjob.substr(1, 4);
                            }
                            let newjob = oldyear + '-' + tempjob + '-' + newitem;
                            document.getElementById('relable_newreff').readOnly = true;
                            document.getElementById('relable_newjob').value = oldjob;
                            document.getElementById('relable_newitem').value = olditem;
                            document.getElementById('relable_newqty').value = newqty;
                            document.getElementById('relable_btn_save').focus();
                        } else {
                            alertify.warning(response.status[0].msg);
                            document.getElementById('relable_newreff').value = '';
                        }
                    },
                    error(xhr, xopt, xthrow) {
                        alertify.error(xthrow);
                    }
                });
            } else {
                alertify.warning('label is not recognized');
            }
        }
    });
    $("#relable_btn_save").click(function(e) {
        let oldreff = document.getElementById('relable_oldreff').value;
        let oldjob = document.getElementById('relable_oldjob').value;
        let olditemcd = document.getElementById('relable_olditemcd').value;
        let rawtxt1 = document.getElementById('relable_rawtxt1').value;
        if (document.getElementById('relable_oldreff').readOnly &&
            document.getElementById('relable_newreff').readOnly) {
            let konf = confirm('Are You sure ?');
            if (!konf) {
                alertify.message('You are not sure');
                return;
            }
            $.ajax({
                type: "post",
                url: "<?= base_url('SER/validate_relable') ?>",
                data: {
                    inoldreff: oldreff,
                    innewreff: rawtxt1
                },
                dataType: "json",
                success: function(response) {
                    if (response.status[0].cd != '0') {
                        alertify.success(response.status[0].msg);
                        relable_e_clear();
                    } else {
                        alertify.warning(response.status[0].msg);
                    }
                },
                error(xhr, xopt, xthrow) {
                    alertify.error(xthrow);
                }
            });
        }
    });
</script>