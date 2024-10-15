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
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">
                    <label class="input-group-text">PSN</label>
                    <input type="text" class="form-control" id="itm_tracer_doc" onkeypress="itm_tracer_doc_e_keypress(event)">
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">
                    <label class="input-group-text">Qty</label>
                    <input type="text" class="form-control" id="itm_tracer_qty" onkeypress="itm_tracer_qty_e_keypress(event)">
                </div>
            </div>
            <div class="col-md-4 mb-1 text-end">
                <button id="itm_tracer_btn_check" onclick="itm_tracer_btn_check_on_click(this)" class="btn btn-primary btn-sm">Check</button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="itm_tracer_table_container">
                    <table id="itm_tracer_tbl" class="table table-bordered border-primary table-sm table-hover">
                        <thead class="table-light text-center align-middle">
                            <tr class="first">
                                <th colspan="3">Item</th>
                                <th rowspan="2">Qty</th>
                                <th rowspan="2">UOM</th>
                            </tr>
                            <tr class="second">
                                <th>Code</th>
                                <th>Name</th>
                                <th>Description</th>
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
        if(doc.length<=5) {
            itm_tracer_doc.focus()
            return
        }
        const data = {
            PSNDoc : doc,
            qty : numeral(itm_tracer_qty.value).value()
        }

        pThis.disabled = true

        document.getElementById("itm_tracer_table_container").getElementsByTagName('tbody')[0].innerHTML = '<tr><td colspan="5">Please wait</td></tr>';
        $.ajax({
            type: "GET",
            url: "<?=$_ENV['APP_INTERNAL_API']?>item-tracer/outstanding-scan",
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
                    newcell.style.cssText = 'cursor:pointer'
                    newcell.innerText = arrayItem['Item_code']
                    newcell.onclick = function() {
                        if(['ADMIN', 'MSPV'].includes(wms_usergroupid)) {
                            $("#itm_tracer_adj_modal").modal('show')
                            itm_tracer_item_code.value = arrayItem['Item_code']
                            itm_tracer_item_name.value = arrayItem['SPTNO']
                            itm_tracer_get_details({doc : doc, item_code : arrayItem['Item_code']})
                        }
                    }
                    newcell = newrow.insertCell(-1)
                    newcell.innerText = arrayItem['SPTNO']
                    newcell = newrow.insertCell(-1)
                    newcell.innerText = arrayItem['ITMD1']
                    newcell = newrow.insertCell(-1)
                    newcell.classList.add('text-end')
                    newcell.innerText = numeral(arrayItem['qty']).format(',')
                    newcell = newrow.insertCell(-1)
                    newcell.innerText = arrayItem['UOM']
                    newcell.classList.add('text-center')
                })

                if(response.data.length === 0) {
                    newrow = tableku2.insertRow(-1)
                    newrow.innerHTML = '<td colspan="5" class="table-success text-center">OK</td>'
                }

                mydes.innerHTML='';
                mydes.appendChild(myfrag);
            }, error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow)
                pThis.disabled = false
                document.getElementById("itm_tracer_table_container").getElementsByTagName('tbody')[0].innerHTML = `<tr><td colspan="5">${xthrow}</td></tr>`;
            }
        });
    }

    function itm_tracer_get_details(data) {
        document.getElementById("itm_tracer_adj_tbl").getElementsByTagName('tbody')[0].innerHTML = `<tr><td colspan="8">Please wait</td></tr>`;
        $.ajax({
            type: "GET",
            url: "<?=$_ENV['APP_INTERNAL_API']?>item-tracer/outstanding-scan-detail",
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
        -100);

    function itm_tracer_insert_detail(data, triggerComponent) {
        triggerComponent.disabled = true
        triggerComponent.innerText = 'Please wait'
        $.ajax({
            type: "POST",
            url: "<?=$_ENV['APP_INTERNAL_API']?>item-tracer/adjust-detail",
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
</script>