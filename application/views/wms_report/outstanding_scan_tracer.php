<style>
    .anastylesel_sim{
        background: red;
        animation: anamove 1s infinite;
    }
    @keyframes anamove {
        from {background: #7FDBFF;}
        to {background: #01FF70;}
    }
    thead tr.first th, thead tr.first td {
        position: sticky;
        top: 0;
    }

    thead tr.second th, thead tr.second td {
        position: sticky;
        top: 26px;
    }
</style>
<div style="padding: 5px">
	<div class="container-fluid">
        <div class="row" id="itm_tracer_stack1">
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm">
                    <label class="input-group-text">Job</label>
                    <input type="text" class="form-control" id="itm_tracer_doc" onkeypress="itm_tracer_doc_e_keypress(event)" maxlength="5">
                </div>
            </div>
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm">
                    <label class="input-group-text">Assy Code</label>
                    <input type="text" class="form-control" id="itm_tracer_assycode" maxlength="15">
                </div>
            </div>
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm">
                    <label class="input-group-text">Closing Qty</label>
                    <input type="text" class="form-control" id="itm_tracer_qty" onkeypress="itm_tracer_qty_e_keypress(event)">
                </div>
            </div>
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm">
                    <label class="input-group-text">Line</label>
                    <input type="text" class="form-control" id="itm_tracer_line" maxlength="5">
                </div>
            </div>
        </div>
        <div class="row" id="itm_tracer_stack2">
            <div class="col-md-12 mb-1 text-center">
                <button id="itm_tracer_btn_check" onclick="itm_tracer_btn_check_on_click(this)" class="btn btn-primary btn-sm">Check</button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <ul class="nav nav-tabs nav-pills mb-3">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active rounded-5" id="itm_tracer_asprova-tab" data-bs-toggle="tab" data-bs-target="#itm_tracer_tab_asprova" type="button" role="tab" aria-controls="home" aria-selected="true">Outstanding</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-5" id="itm_tracer_home-tab" data-bs-toggle="tab" data-bs-target="#itm_tracer_tabRM" type="button" role="tab" aria-controls="home" aria-selected="true">Supplied</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-5" id="itm_tracer_closing-tab" data-bs-toggle="tab" data-bs-target="#itm_tracer_tab_closing" type="button" role="tab" aria-controls="home" aria-selected="true">Related Job Status</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-5" id="itm_tracer_tlws-tab" data-bs-toggle="tab" data-bs-target="#itm_tracer_tab_tlws" type="button" role="tab" aria-controls="home" aria-selected="true">TLWS</button>
                    </li>
                </ul>
                <div class="tab-content" id="itm_tracer_myTabContent">
                    <div class="tab-pane active show" id="itm_tracer_tab_asprova" role="tabpanel">
                        <div class="container-fluid p-1">
                            <div class="table-responsive" id="itm_tracer_table_container">
                                <table id="itm_tracer_tbl" class="table table-bordered border-primary table-sm table-hover">
                                    <thead class="table-light text-center align-middle">
                                        <tr class="first">
                                            <th >Item Code</th>
                                            <th >Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="itm_tracer_tabRM" role="tabpanel">
                        <div class="container-fluid p-1">
                            <div class="table-responsive" id="itm_tracer_supplied_table_container">
                                <table id="itm_tracer_supplied_tbl" class="table table-bordered border-primary table-sm table-hover">
                                    <thead class="table-light text-center align-middle">
                                        <tr class="first">
                                            <th rowspan="2">Item</th>
                                            <th rowspan="2">Unique Key</th>
                                            <th colspan="2">Qty </th>
                                            <th rowspan="2">PSN Number</th>
                                        </tr>
                                        <tr class="second">
                                            <th>Before Calculation</th>
                                            <th>After Calculation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="itm_tracer_tab_closing" role="tabpanel">
                        <div class="container-fluid p-1">
                            <div class="table-responsive" id="itm_tracer_closing_table_container">
                                <table id="itm_tracer_closing_tbl" class="table table-bordered border-primary table-sm table-hover">
                                    <thead class="table-light text-center align-middle">
                                        <tr class="first">
                                            <th>Job</th>
                                            <th>Process</th>
                                            <th>Assy Code</th>
                                            <th>Bom Revision</th>
                                            <th>Closing Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="itm_tracer_tab_tlws" role="tabpanel">
                        <div class="container p-1">
                            <div class="row">
                                <div class="col-md-12 mb-1 text-center" id="itm_tracer_div_alert">
                                    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-1">
                                    <div class="table-responsive" id="itm_tracer_tlws_table_container">
                                        <table id="itm_tracer_tlws_tbl" class="table table-bordered border-primary table-sm table-hover">
                                            <thead class="table-light text-center align-middle">
                                                <tr class="first">
                                                    <th class="d-none">SPID</th>
                                                    <th >Process</th>
                                                    <th >Assy Code</th>
                                                    <th >Job Code</th>
                                                    <th >Last Update</th>
                                                    <th >Last Update By</th>
                                                    <th >...</th>
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
<div class="modal fade" id="itm_tracer_adj_modal">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Adjustment</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6 mb-1">
                    <div class="input-group input-group-sm">
                        <label class="input-group-text">Item Code</label>
                        <input type="text" class="form-control" id="itm_tracer_item_code" disabled>
                    </div>
                </div>
                <div class="col-md-6 mb-1">
                    <div class="input-group input-group-sm">
                        <label class="input-group-text">Item Name</label>
                        <input type="text" class="form-control" id="itm_tracer_item_name" disabled>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive" id="itm_tracer_adj_tbl_div">
                        <table id="itm_tracer_adj_tbl" class="table table-sm table-striped table-bordered table-hover">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>PSN</th>
                                    <th>ID</th>
                                    <th>Item Code</th>
                                    <th>Item Name</th>
                                    <th>Lot</th>
                                    <th>Qty</th>
                                    <th>Process</th>
                                    <th>...</th>
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
    function itm_tracer_btn_check_on_click(pThis) {
        const doc = itm_tracer_doc.value.trim()
        const assyCode = itm_tracer_assycode.value.trim()
        if(doc.length<=3) {
            itm_tracer_doc.focus()
            return
        }
        if(assyCode.length<7) {
            itm_tracer_assycode.focus()
            return
        }

        const line = itm_tracer_line.value.trim()
        if(line.length<2) {
            itm_tracer_line.focus()
            return
        }

        const qty = numeral(itm_tracer_qty.value).value()

        if(qty<1) {
            itm_tracer_qty.focus()
            return
        }

        const data = {
            doc : doc,
            itemCode : assyCode,
            qty : qty,
            lineCode : 'SMT-' + itm_tracer_line.value,
            isFromWeb : 1
        }

        pThis.disabled = true

        document.getElementById("itm_tracer_table_container").getElementsByTagName('tbody')[0].innerHTML = '<tr><td colspan="5">Please wait</td></tr>';
        $.ajax({
            type: "GET",
            url: "<?php echo $_ENV['APP_INTERNAL3_API'] ?>production/supply-status",
            data: data,
            dataType: "json",
            success: function (response) {
                pThis.disabled = false
                let mydes = document.getElementById("itm_tracer_table_container");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("itm_tracer_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("itm_tracer_tbl");
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                tableku2.innerHTML='';

                response.data.forEach((arrayItem) => {
                    newrow = tableku2.insertRow(-1)
                    newcell = newrow.insertCell(0)
                    newcell.classList.add('text-center')
                    newcell.innerText = arrayItem['partCode']
                    newcell.ondblclick = function() {
                        if(['ADMIN', 'MSPV'].includes(wms_usergroupid)) {
                            $("#itm_tracer_adj_modal").modal('show')
                            itm_tracer_item_code.value = arrayItem['partCode']
                            itm_tracer_item_name.value = ''
                            itm_tracer_get_details({doc : doc, item_code : arrayItem['partCode']})
                        }
                    }
                    newcell = newrow.insertCell(-1)
                    newcell.classList.add('text-end')
                    newcell.innerText = numeral(arrayItem['outstandingQty']).format(',')
                })

                if(response.data.length === 0) {
                    newrow = tableku2.insertRow(-1)
                    newrow.innerHTML = `<td colspan="5" class="table-success text-center">${response.status.message}</td>`
                }

                mydes.innerHTML='';
                mydes.appendChild(myfrag);

                if(response.dataSupplied) {
                    mydes = document.getElementById("itm_tracer_supplied_table_container");
                    myfrag = document.createDocumentFragment();
                    mtabel = document.getElementById("itm_tracer_supplied_tbl");
                    cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    tabell = myfrag.getElementById("itm_tracer_supplied_tbl");
                    tableku2 = tabell.getElementsByTagName("tbody")[0];
                    tableku2.innerHTML='';

                    response.dataSupplied.forEach((arrayItem) => {
                        newrow = tableku2.insertRow(-1)
                        newcell = newrow.insertCell(0)
                        newcell.innerText = arrayItem['ITMCD']
                        newcell = newrow.insertCell(-1)
                        newcell.innerText = arrayItem['UNQ']
                        newcell = newrow.insertCell(-1)
                        newcell.classList.add('text-end')
                        newcell.innerText = numeral(arrayItem['BAKQTY']).format(',')
                        newcell = newrow.insertCell(-1)
                        newcell.classList.add('text-end')
                        newcell.innerText = numeral(arrayItem['QTY']).format(',')
                        newcell = newrow.insertCell(-1)
                        newcell.innerText = arrayItem['PSNNO']
                        newcell.classList.add('text-center')
                    })

                    mydes.innerHTML='';
                    mydes.appendChild(myfrag);


                    mydes = document.getElementById("itm_tracer_closing_table_container");
                    myfrag = document.createDocumentFragment();
                    mtabel = document.getElementById("itm_tracer_closing_tbl");
                    cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    tabell = myfrag.getElementById("itm_tracer_closing_tbl");
                    tableku2 = tabell.getElementsByTagName("tbody")[0];
                    tableku2.innerHTML='';

                    response.dataJob.forEach((arrayItem) => {
                        newrow = tableku2.insertRow(-1)
                        newcell = newrow.insertCell(0)
                        newcell.innerText = arrayItem['CLS_JOBNO']
                        newcell = newrow.insertCell(-1)
                        newcell.innerText = arrayItem['CLS_PROCD']
                        newcell = newrow.insertCell(-1)
                        newcell.classList.add('text-end')
                        newcell.innerText = arrayItem['CLS_MDLCD']
                        newcell = newrow.insertCell(-1)
                        newcell.classList.add('text-center')
                        newcell.innerText = arrayItem['CLS_BOMRV']
                        newcell = newrow.insertCell(-1)
                        newcell.classList.add('text-end')
                        newcell.innerText = numeral(arrayItem['CLSQTY']).format(',')
                    })

                    mydes.innerHTML='';
                    mydes.appendChild(myfrag);

                }
            }, error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow)
                pThis.disabled = false
                document.getElementById("itm_tracer_table_container").getElementsByTagName('tbody')[0].innerHTML = `<tr><td colspan="5">${xthrow}</td></tr>`;
            }
        });

        itm_tracer_btn_tlws_find_on_click()
    }

    function itm_tracer_get_details(data) {
        document.getElementById("itm_tracer_adj_tbl").getElementsByTagName('tbody')[0].innerHTML = `<tr><td colspan="8">Please wait</td></tr>`;
        $.ajax({
            type: "GET",
            url: "<?php echo $_ENV['APP_INTERNAL_API'] ?>item-tracer/outstanding-scan-detail",
            data: data,
            dataType: "JSON",
            success: function (response) {
                let mydes = document.getElementById("itm_tracer_adj_tbl_div");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("itm_tracer_adj_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("itm_tracer_adj_tbl");
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                tableku2.innerHTML='';

                response.dataPSN.forEach((arrayItem) => {
                    newrow = tableku2.insertRow(-1)
                    newrow.classList.add('align-middle')
                    newcell = newrow.insertCell(0)
                    newcell.innerText = arrayItem['SPLSCN_DOC']
                    newcell = newrow.insertCell(-1)
                    newcell.innerText = arrayItem['SPLSCN_UNQCODE']
                    newcell = newrow.insertCell(-1)
                    newcell.innerText = arrayItem['SPLSCN_ITMCD']
                    newcell = newrow.insertCell(-1)
                    newcell.innerText = arrayItem['SPTNO']
                    newcell = newrow.insertCell(-1)
                    newcell.innerText = arrayItem['SPLSCN_LOTNO']
                    newcell = newrow.insertCell(-1)
                    newcell.classList.add('text-end')
                    newcell.innerText = numeral(arrayItem['SPLSCN_QTY']).format(',')
                    newcell = newrow.insertCell(-1)
                    newcell.classList.add('text-center')
                    newcell.innerText = arrayItem['SPLSCN_PROCD']
                    newcell = newrow.insertCell(-1)
                    newcell.classList.add('text-center')

                    let _EleBTN = document.createElement('button')
                    _EleBTN.classList.add('btn','btn-sm', 'btn-primary')
                    _EleBTN.onclick = function() {

                        if(confirm('Are you sure ?')) {
                            data.procd = arrayItem['SPLSCN_PROCD']
                            data.lot_code = arrayItem['SPLSCN_LOTNO']
                            data.item_qty = numeral(arrayItem['SPLSCN_QTY']).value()
                            data.unique_code = arrayItem['SPLSCN_UNQCODE']
                            data.user_id = uidnya
                            itm_tracer_insert_detail(data, _EleBTN)
                        }
                    }
                    _EleBTN.innerText = 'Add'
                    newcell.append(_EleBTN)
                })

                mydes.innerHTML='';
                mydes.appendChild(myfrag);
            }, error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow)
                document.getElementById("itm_tracer_adj_tbl").getElementsByTagName('tbody')[0].innerHTML = `<tr><td colspan="8">${xthrow}</td></tr>`;
            }
        });
    }

    Inputmask({
        'alias': 'decimal',
        'groupSeparator': ',',
    }).mask(itm_tracer_qty);

    function itm_tracer_doc_e_keypress(e) {
        if(e.key === 'Enter') {
            itm_tracer_qty.focus()
        }
    }

    function itm_tracer_qty_e_keypress(e) {
        if(e.key === 'Enter') {
            itm_tracer_btn_check.focus()
        }
    }

    $("#itm_tracer_table_container").css('height', $(window).height()
        -document.getElementById('itm_tracer_stack1').offsetHeight
        -document.getElementById('itm_tracer_stack2').offsetHeight
        -150);
    $("#itm_tracer_supplied_table_container").css('height', $(window).height()
        -document.getElementById('itm_tracer_stack1').offsetHeight
        -document.getElementById('itm_tracer_stack2').offsetHeight
        -150);

    function itm_tracer_insert_detail(data, triggerComponent) {
        triggerComponent.disabled = true
        triggerComponent.innerText = 'Please wait'
        $.ajax({
            type: "POST",
            url: "<?php echo $_ENV['APP_INTERNAL_API'] ?>item-tracer/adjust-detail",
            data: data,
            dataType: "json",
            success: function (response) {
                alertify.success(response.message)
                $("#itm_tracer_adj_modal").modal('hide')

                triggerComponent.innerText = 'Added'
            }, error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow)
                triggerComponent.disabled = false
                triggerComponent.innerText = 'Add'
            }
        });
    }

    function itm_tracer_btn_tlws_find_on_click() {
        const doc = itm_tracer_doc.value.trim()
        const assyCode = itm_tracer_assycode.value.trim()

        if(doc.length<=3) {
            itm_tracer_doc.focus()
            return
        }
        if(assyCode.length<=8) {
            itm_tracer_assycode.focus()
            return
        }

        const data = {
            doc : doc,
            itemCode : assyCode,
        }

        
        const div_alert = document.getElementById('itm_tracer_div_alert')
        div_alert.innerHTML = ``
        $.ajax({
            type: "GET",
            url: "<?php echo $_ENV['APP_INTERNAL3_API'] ?>production/active-tlws",
            data: data,
            dataType: "json",
            success: function (response) {
                
                let mydes = document.getElementById("itm_tracer_tlws_table_container");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("itm_tracer_tlws_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("itm_tracer_tlws_tbl");
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                tableku2.innerHTML='';

                response.data.forEach((arrayItem) => {
                    newrow = tableku2.insertRow(-1)
                    newrow.classList.add('align-middle')
                    newcell = newrow.insertCell(0)
                    newcell.classList.add('d-none')
                    newcell.innerText = arrayItem['TLWS_SPID']
                    newcell = newrow.insertCell(-1)
                    newcell.classList.add('text-center')
                    newcell.innerText = arrayItem['TLWS_PROCD']
                    newcell = newrow.insertCell(-1)
                    newcell.classList.add('text-center')
                    newcell.innerText = arrayItem['TLWS_MDLCD']
                    newcell = newrow.insertCell(-1)
                    newcell.classList.add('text-center')
                    newcell.innerText = arrayItem['TLWS_JOBNO']
                    newcell = newrow.insertCell(-1)
                    newcell.classList.add('text-center')
                    newcell.innerText = arrayItem['TLWS_LUPDT']
                    newcell = newrow.insertCell(-1)
                    newcell.classList.add('text-center')
                    newcell.innerText = arrayItem['TLWS_LUPBY']
                    newcell = newrow.insertCell(-1)
                    newcell.classList.add('text-center')
                    const bsButton = document.createElement('button')
                    bsButton.innerText = 'Set complete flag'
                    bsButton.classList.add('btn', 'btn-primary', 'btn-sm')
                    
                    bsButton.onclick = function() {
                        const data = {
                            doc : arrayItem['TLWS_SPID'],
                            itemCode : arrayItem['TLWS_MDLCD'],
                            groupId : wms_usergroupid
                        }
                        bsButton.disabled = true
                        
                        $.ajax({
                            type: "PUT",
                            url: "<?php echo $_ENV['APP_INTERNAL3_API'] ?>production/active-tlws",
                            data: data,
                            dataType: "JSON",
                            success: function (response) {
                                alertify.success(response.message)
                            }, error: function(xhr, xopt, xthrow) {
                                alertify.error(xthrow)
                                bsButton.disabled = false
                                const respon = Object.keys(xhr.responseJSON)

                                let msg = ''
                                for (const item of respon) {
                                    msg += `<p>${xhr.responseJSON[item]}</p>`
                                }
                                div_alert.innerHTML = `<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    ${msg}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>`
                            }
                        });
                    }
                    newcell.appendChild(bsButton)
                })

                mydes.innerHTML='';
                mydes.appendChild(myfrag);
            }, error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow)
            }
        });
    }
</script>