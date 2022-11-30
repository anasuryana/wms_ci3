<style>
    .anastylesel_sim {
        background: red;
        animation: anamove 1s infinite;
    }

    @keyframes anamove {
        from {
            background: #7FDBFF;
        }

        to {
            background: #01FF70;
        }
    }

    thead tr.first th,
    thead tr.first td {
        position: sticky;
        top: 0;
    }

    thead tr.second th,
    thead tr.second td {
        position: sticky;
        top: 25px;
    }
</style>

<div style="padding: 10px">
    <div class="container-fluid">
        <div class="row" id="rsumsupinv_stack1">
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">From</span>
                    <input type="text" class="form-control" id="rsumsupinv_txt_dt" onchange="rsumsupinv_dt_echange()" readonly title="Receive date">
                </div>
            </div>
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">To</span>
                    <input type="text" class="form-control" id="rsumsupinv_txt_dt2" onchange="rsumsupinv_dt_echange()" readonly title="Receive date">
                </div>
            </div>
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Business Group</span>
                    <input type="text" class="form-control" id="rsumsupinv_cmb_bg" readonly onclick="rsumsupinv_bisgrup_eC()">
                </div>
            </div>
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Supplier</span>
                    <input type="text" class="form-control" id="rsumsupinv_cmb_supplier" readonly onclick="rsumsupinv_supplier_eC()">
                </div>
            </div>
        </div>

        <div class="row" id="rsumsupinv_stack2">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Search by</span>
                    <select class="form-select" id="rsumsupinv_searchby">
                        <option value="DO">DO Number</option>
                        <option value="INV">Invoice Number</option>
                    </select>
                    <input type="text" class="form-control" id="rsumsupinv_txt_search">
                    <button class="btn btn-primary" type="button" id="rsumsupinv_btn_gen" onclick="rsumsupinv_btn_gen_eCK()">Search</button>
                </div>
            </div>
            <div class="col-md-6 mb-1 text-end">
                <div class="btn-group btn-group-sm">
                    <div class="btn-group btn-group-sm" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Export to
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <li><a class="dropdown-item" href="#" id="rsumsupinv_btn_xls" onclick="rsumsupinv_btn_xls_eCK()"><span style="color: MediumSeaGreen"><i class="fas fa-file-excel"></i></span> XLS</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <ul class="nav nav-tabs" id="rsumsupinv_stack3" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#rsumsupinv_itemdetail" type="button" role="tab" aria-controls="home" aria-selected="true">Detail</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#rsumsupinv_resume" type="button" role="tab" aria-controls="profile" aria-selected="false">Resume</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="persupplier-tab" data-bs-toggle="tab" data-bs-target="#rsumsupinv_resume_supplier" type="button" role="tab" aria-controls="supplier" aria-selected="false">Per Supplier</button>
                    </li>
                </ul>
                <div class="tab-content" id="rsumsupinv_myTabContent">
                    <div class="tab-pane fade show active" id="rsumsupinv_itemdetail" role="tabpanel" aria-labelledby="home-tab">
                        <div class="container-fluid mt-2">
                            <div class="row">
                                <div class="col">
                                    <div class="table-responsive" id="rsumsupinv_divku">
                                        <table id="rsumsupinv_tbl" class="table table-bordered table-sm table-hover" style="font-size:75%">
                                            <thead class="table-light">
                                                <tr class="first">
                                                    <th class="align-middle">Supplier Code</th>
                                                    <th class="align-middle">Supplier Name</th>
                                                    <th class="align-middle">GRN No</th>
                                                    <th class="align-middle">DO No</th>
                                                    <th class="align-middle">Invoice No.</th>
                                                    <th class="align-middle">PO No</th>
                                                    <th class="align-middle">Parts Code</th>
                                                    <th class="align-middle">Maker Parts Code</th>
                                                    <th class="align-middle">Parts Name</th>
                                                    <th class="align-middle">Receive Date</th>
                                                    <th class="align-middle text-end">Qty</th>
                                                    <th class="align-middle">Unit</th>
                                                    <th class="align-middle">Currency</th>
                                                    <th class="align-middle text-end">Unit Price</th>
                                                    <th class="align-middle text-end">Amount</th>
                                                    <th class="align-middle">Warehouse</th>
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
                    <div class="tab-pane fade" id="rsumsupinv_resume" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="container-fluid mt-2">
                            <div class="row">
                                <div class="col">
                                    <div class="table-responsive" id="rsumsupinv_resume_tbl_divku">
                                        <table id="rsumsupinv_resume_tbl" class="table table-bordered table-sm table-hover" style="font-size:75%">
                                            <thead class="table-light">
                                                <tr class="first">
                                                    <th class="align-middle">No</th>
                                                    <th class="align-middle">Supplier Code</th>
                                                    <th class="align-middle">Supplier Name</th>
                                                    <th class="align-middle">Invoice No.</th>                                                    
                                                    <th class="align-middle">Receive Date</th>
                                                    <th class="align-middle">Currency</th>
                                                    <th class="align-middle text-end">Amount</th>
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
                    <div class="tab-pane fade" id="rsumsupinv_resume_supplier" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="container-fluid mt-2">
                            <div class="row">
                                <div class="col">
                                    <div class="table-responsive" id="rsumsupinv_resume_sup_tbl_divku">
                                        <table id="rsumsupinv_resume_sup_tbl" class="table table-bordered table-sm table-hover" style="font-size:75%">
                                            <thead class="table-light">
                                                <tr class="first">
                                                    <th class="align-middle">Supplier Code</th>
                                                    <th class="align-middle">Supplier Name</th>
                                                    <th class="align-middle">Invoice No.</th>                                                    
                                                    <th class="align-middle">Receive Date</th>
                                                    <th class="align-middle">Currency</th>
                                                    <th class="align-middle text-end">Amount</th>
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
<div class="modal fade" id="rsumsupinv_BG">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Business Group List</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col" onclick="rsumsupinv_selectBG_eC(event)">
                        <div class="table-responsive" id="rsumsupinv_tblbg_div">
                            <table id="rsumsupinv_tblbg" class="table table-sm table-striped table-bordered table-hover" style="width:100%">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center d-none">BG</th>
                                        <th class="text-center">Business</th>
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
<div class="modal fade" id="rsumsupinv_SUP">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Supplier List</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col" onclick="rsumsupinv_selectSUP_eC(event)">
                        <div class="table-responsive" id="rsumsupinv_tblsup_div">
                            <table id="rsumsupinv_tblsup" class="table table-sm table-striped table-bordered table-hover" style="width:100%">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center d-none">Supplier Code</th>
                                        <th class="text-center">Supplier</th>
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
<script>
    var rsumsupinv_a_BG = [];
    var rsumsupinv_a_BG_NM = [];
    var rsumsupinv_a_SUP = [];
    var rsumsupinv_a_SUP_NM = [];

    function rsumsupinv_dt_echange() {
        let mtabel = document.getElementById("rsumsupinv_tblsup")
        mtabel.getElementsByTagName('tbody')[0].innerHTML = '<tr><td class="d-none"></td><td>Please wait</td></tr>'
        $.ajax({
            url: "<?= base_url('RCV/supplier_period') ?>",
            data: {
                date1: rsumsupinv_txt_dt.value,
                date2: rsumsupinv_txt_dt2.value
            },
            dataType: "json",
            success: function(response) {
                const ttlrows = response.data.length;
                let mydes = document.getElementById("rsumsupinv_tblsup_div");
                let myfrag = document.createDocumentFragment();
                
                let cln = mtabel.cloneNode(true)
                myfrag.appendChild(cln)
                let tabell = myfrag.getElementById("rsumsupinv_tblsup")
                let tableku2 = tabell.getElementsByTagName("tbody")[0]
                let newrow, newcell, newText;
                tableku2.innerHTML = ''
                for (let i = 0; i < ttlrows; i++) {
                    newrow = tableku2.insertRow(-1)
                    newrow.style.cssText = 'cursor:pointer'
                    newcell = newrow.insertCell(0);
                    newcell.classList.add('d-none')
                    newcell.innerHTML = response.data[i].PGRN_SUPCD
                    newcell = newrow.insertCell(1)
                    newcell.innerHTML = response.data[i].MSUP_SUPNM
                }
                mydes.innerHTML = ''
                mydes.appendChild(myfrag)
            },
            error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow);
            }
        });
    }

    function rsumsupinv_btn_xls_eCK() {
        let mdate1 = document.getElementById('rsumsupinv_txt_dt').value;
        let mdate2 = document.getElementById('rsumsupinv_txt_dt2').value;
        let bgroup = rsumsupinv_a_BG
        let sbg = '';
        let ssupplier = '';
        for (let i = 0; i < bgroup.length; i++) {
            sbg += bgroup[i] + "','";
        }
        if (sbg != '') {
            sbg = "'" + sbg + "'";
        }
        for (let i = 0; i < rsumsupinv_a_SUP.length; i++) {
            ssupplier += rsumsupinv_a_SUP[i] + "','";
        }
        if (ssupplier != '') {
            ssupplier = "'" + ssupplier + "'";
        }
        Cookies.set('CKPSI_BG', sbg, {
            expires: 365
        });
        Cookies.set('CKPSI_SUPPLIER', ssupplier, {
            expires: 365
        });
        Cookies.set('CKPSI_DATE1', mdate1, {
            expires: 365
        });
        Cookies.set('CKPSI_DATE2', mdate2, {
            expires: 365
        });
        window.open("<?= base_url('RCV/report_summary_inv_as_xls') ?>", '_blank');
    }

    $("#rsumsupinv_divku").css('height', $(window).height() -
        document.getElementById('rsumsupinv_stack1').offsetHeight -
        document.getElementById('rsumsupinv_stack2').offsetHeight -
        document.getElementById('rsumsupinv_stack3').offsetHeight -
        107);
    $("#rsumsupinv_resume_tbl_divku").css('height', $(window).height() -
        document.getElementById('rsumsupinv_stack1').offsetHeight -
        document.getElementById('rsumsupinv_stack2').offsetHeight -
        document.getElementById('rsumsupinv_stack3').offsetHeight -
        107);
    $("#rsumsupinv_resume_sup_tbl_divku").css('height', $(window).height() -
        document.getElementById('rsumsupinv_stack1').offsetHeight -
        document.getElementById('rsumsupinv_stack2').offsetHeight -
        document.getElementById('rsumsupinv_stack3').offsetHeight -
        107);

    function rsumsupinv_btn_gen_eCK() {
        const date1 = document.getElementById('rsumsupinv_txt_dt').value
        const date2 = document.getElementById('rsumsupinv_txt_dt2').value
        let bgroup = rsumsupinv_a_BG
        const searchby = document.getElementById('rsumsupinv_searchby').value
        const search = document.getElementById('rsumsupinv_txt_search').value
        const btnprc = document.getElementById('rsumsupinv_btn_gen')
        btnprc.disabled = true
        btnprc.innerHTML = 'Please wait'
        $.ajax({
            type: "POST",
            url: "<?= base_url('RCV/report_summary_inv') ?>",
            data: {
                date1: date1,
                date2: date2,
                bsgrp: bgroup,
                supplier: rsumsupinv_a_SUP,
                searchby: searchby,
                search: search
            },
            dataType: "json",
            success: function(response) {
                btnprc.disabled = false
                btnprc.innerHTML = 'Search'
                let ttlrows = response.data.length;
                let mydes = document.getElementById("rsumsupinv_divku");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("rsumsupinv_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("rsumsupinv_tbl");
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                let newrow, newcell, newText;
                let myitmttl = 0;
                tableku2.innerHTML = '';
                for (let i = 0; i < ttlrows; i++) {
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0)
                    newcell.innerHTML = response.data[i].PGRN_SUPCD
                    newcell = newrow.insertCell(1)
                    newcell.innerHTML = response.data[i].MSUP_SUPNM
                    newcell = newrow.insertCell(2)
                    newcell.innerHTML = response.data[i].PGRN_GRLNO
                    newcell = newrow.insertCell(3)
                    newcell.innerHTML = response.data[i].PGRN_SUPNO
                    newcell = newrow.insertCell(4)
                    newcell.innerHTML = response.data[i].PNGR_INVNO
                    newcell = newrow.insertCell(5)
                    newcell.innerHTML = response.data[i].PGRN_PONO
                    newcell = newrow.insertCell(6)
                    newcell.innerHTML = response.data[i].PGRN_ITMCD
                    newcell = newrow.insertCell(7)
                    newcell.innerHTML = response.data[i].MITM_SPTNO
                    newcell = newrow.insertCell(8)
                    newcell.innerHTML = response.data[i].MITM_ITMD1
                    newcell = newrow.insertCell(9)
                    newcell.innerHTML = response.data[i].PGRN_RCVDT
                    newcell = newrow.insertCell(10)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = numeral(response.data[i].PGRN_ROKQT).format(',')
                    newcell = newrow.insertCell(11)
                    newcell.innerHTML = response.data[i].MITM_STKUOM
                    newcell = newrow.insertCell(12)
                    newcell.innerHTML = response.data[i].PGRN_CURCD
                    newcell = newrow.insertCell(13)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = response.data[i].PGRN_PRPRC * 1
                    newcell = newrow.insertCell(14)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = response.data[i].PGRN_AMT
                    newcell = newrow.insertCell(15)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = response.data[i].PGRN_LOCCD
                }
                mydes.innerHTML = '';
                mydes.appendChild(myfrag);

                //resume
                ttlrows = response.data_r.length;
                mydes = document.getElementById("rsumsupinv_resume_tbl_divku");
                myfrag = document.createDocumentFragment();
                mtabel = document.getElementById("rsumsupinv_resume_tbl");
                cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                tabell = myfrag.getElementById("rsumsupinv_resume_tbl");
                tableku2 = tabell.getElementsByTagName("tbody")[0];
                myitmttl = 0;
                tableku2.innerHTML = '';
                for (let i = 0; i < ttlrows; i++) {
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0)
                    newcell.innerHTML = (i+1)
                    newcell = newrow.insertCell(1)
                    newcell.innerHTML = response.data_r[i].PGRN_SUPCD
                    newcell = newrow.insertCell(2)
                    newcell.innerHTML = response.data_r[i].MSUP_SUPNM
                    newcell = newrow.insertCell(3)
                    newcell.innerHTML = response.data_r[i].PNGR_INVNO
                    newcell = newrow.insertCell(4)
                    newcell.innerHTML = response.data_r[i].PGRN_RCVDT
                    newcell = newrow.insertCell(5)
                    newcell.innerHTML = response.data_r[i].PGRN_CURCD
                    newcell = newrow.insertCell(6)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = response.data_r[i].PGRN_AMT
                }
                mydes.innerHTML = '';
                mydes.appendChild(myfrag);

                //resume supplier
                ttlrows = response.data_r_supplier.length;
                mydes = document.getElementById("rsumsupinv_resume_sup_tbl_divku");
                myfrag = document.createDocumentFragment();
                mtabel = document.getElementById("rsumsupinv_resume_sup_tbl");
                cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                tabell = myfrag.getElementById("rsumsupinv_resume_sup_tbl");
                tableku2 = tabell.getElementsByTagName("tbody")[0];
                myitmttl = 0;
                tableku2.innerHTML = '';
                for (let i = 0; i < ttlrows; i++) {
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0)
                    newcell.innerHTML = response.data_r_supplier[i].PGRN_SUPCD
                    newcell = newrow.insertCell(1)
                    newcell.innerHTML = response.data_r_supplier[i].MSUP_SUPNM
                    newcell = newrow.insertCell(2)
                    newcell.innerHTML = response.data_r_supplier[i].PNGR_INVNO
                    newcell = newrow.insertCell(3)
                    newcell.innerHTML = response.data_r_supplier[i].PGRN_RCVDT
                    newcell = newrow.insertCell(4)
                    newcell.innerHTML = response.data_r_supplier[i].PGRN_CURCD
                    newcell = newrow.insertCell(5)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = response.data_r_supplier[i].PGRN_AMT
                }
                mydes.innerHTML = '';
                mydes.appendChild(myfrag);
            },
            error: function(xhr, xopt, xthrow) {
                btnprc.disabled = false
                btnprc.innerHTML = 'Search'
                alertify.error(xthrow);
            }
        });
    }

    function rsumsupinv_selectBG_eC(e) {
        if (e.target.tagName.toLowerCase() === 'td') {
            if (e.target.cellIndex == 1) {
                const mtbl = document.getElementById('rsumsupinv_tblbg')
                const tableku2 = mtbl.getElementsByTagName("tbody")[0]
                const mtbltr = tableku2.getElementsByTagName('tr')
                const ttlrows = mtbltr.length
                if (e.target.classList.contains('anastylesel_sim')) {
                    e.target.classList.remove('anastylesel_sim')
                    const getINDX = rsumsupinv_a_BG.indexOf(e.target.previousElementSibling.innerText)
                    if (getINDX > -1) {
                        rsumsupinv_a_BG.splice(getINDX, 1)
                        rsumsupinv_a_BG_NM.splice(getINDX, 1)
                    }
                } else {
                    if (e.target.textContent.length != 0) {
                        rsumsupinv_a_BG.push(e.target.previousElementSibling.innerText)
                        rsumsupinv_a_BG_NM.push(e.target.innerText)
                        e.target.classList.add('anastylesel_sim')
                    }
                }
            }
        }
    }

    function rsumsupinv_selectSUP_eC(e) {
        if (e.target.tagName.toLowerCase() === 'td') {
            if (e.target.cellIndex == 1) {
                const mtbl = document.getElementById('rsumsupinv_tblsup')
                const tableku2 = mtbl.getElementsByTagName("tbody")[0]
                const mtbltr = tableku2.getElementsByTagName('tr')
                const ttlrows = mtbltr.length
                if (e.target.classList.contains('anastylesel_sim')) {
                    e.target.classList.remove('anastylesel_sim')
                    const getINDX = rsumsupinv_a_SUP.indexOf(e.target.previousElementSibling.innerText)
                    if (getINDX > -1) {
                        rsumsupinv_a_SUP.splice(getINDX, 1)
                        rsumsupinv_a_SUP_NM.splice(getINDX, 1)
                    }
                } else {
                    if (e.target.textContent.length != 0) {
                        rsumsupinv_a_SUP.push(e.target.previousElementSibling.innerText)
                        rsumsupinv_a_SUP_NM.push(e.target.innerText)
                        e.target.classList.add('anastylesel_sim')
                    }
                }
            }
        }
    }

    function rsumsupinv_bisgrup_eC() {
        $("#rsumsupinv_BG").modal('show')
    }

    function rsumsupinv_supplier_eC() {
        $("#rsumsupinv_SUP").modal('show')
    }
    $("#rsumsupinv_BG").on('hidden.bs.modal', function() {
        let strDisplay = ''
        rsumsupinv_a_BG_NM.forEach((item, index) => {
            strDisplay += item + ', '
        })
        document.getElementById('rsumsupinv_cmb_bg').value = strDisplay.substr(0, strDisplay.length - 2)
    })
    $("#rsumsupinv_SUP").on('hidden.bs.modal', function() {
        let strDisplay = ''
        rsumsupinv_a_SUP_NM.forEach((item, index) => {
            strDisplay += item + ', '
        })
        document.getElementById('rsumsupinv_cmb_supplier').value = strDisplay.substr(0, strDisplay.length - 2)
    })
    rsumsupinv_e_getBG()

    function rsumsupinv_e_getBG() {
        $.ajax({
            url: "<?= base_url('ITH/get_bs_group') ?>",
            dataType: "JSON",
            success: function(response) {
                const ttlrows = response.data.length;
                let mydes = document.getElementById("rsumsupinv_tblbg_div");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("rsumsupinv_tblbg")
                let cln = mtabel.cloneNode(true)
                myfrag.appendChild(cln)
                let tabell = myfrag.getElementById("rsumsupinv_tblbg")
                let tableku2 = tabell.getElementsByTagName("tbody")[0]
                let newrow, newcell, newText;
                tableku2.innerHTML = ''
                for (let i = 0; i < ttlrows; i++) {
                    newrow = tableku2.insertRow(-1)
                    newrow.style.cssText = 'cursor:pointer'
                    newcell = newrow.insertCell(0);
                    newcell.classList.add('d-none')
                    newcell.innerHTML = response.data[i].id
                    newcell = newrow.insertCell(1)
                    newcell.innerHTML = response.data[i].text
                }
                mydes.innerHTML = ''
                mydes.appendChild(myfrag)
            },
            error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow);
            }
        });
    }
    $("#rsumsupinv_txt_dt").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });
    $("#rsumsupinv_txt_dt2").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });
    $("#rsumsupinv_txt_dt").datepicker('update', new Date());
    $("#rsumsupinv_txt_dt2").datepicker('update', new Date());
</script>