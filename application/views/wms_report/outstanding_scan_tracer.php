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
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">
                    <label class="input-group-text">PSN</label>
                    <input type="text" class="form-control" id="itm_tracer_doc" onkeypress="itm_tracer_doc_e_keypress(event)" maxlength="50" title="Press Enter to get job list">
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">
                    <label class="input-group-text">Part Code</label>
                    <input type="text" class="form-control" id="itm_tracer_part_code">
                </div>
            </div>            
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="item_tracer_divku">
                    <table id="item_tracer_tbl" class="table table-striped table-bordered table-sm table-hover">
                        <thead class="table-light text-center">
                            <tr class="first">
                                <th rowspan="2" class="align-middle">Job Number</th>
                                <th rowspan="2" class="align-middle">Process</th>
                                <th colspan="3" class="text-center">Qty</th>
                            </tr>
                            <tr class="second">
                                <th class="align-middle">Lot Size</th>
                                <th class="align-middle">Previous Output</th>
                                <th class="align-middle">Current Output</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row" id="itm_tracer_stack2">
            <div class="col-md-12 mb-1 text-center">
                <button id="itm_tracer_btn_check" onclick="itm_tracer_btn_check_on_click(this)" class="btn btn-primary btn-sm">Check</button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1" id="itm_tracer-div-alert">

            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="item_tracer_result_divku">
                    <table id="item_tracer_result_tbl" class="table table-striped table-bordered table-sm table-hover">
                        <thead class="table-light text-center" >
                            <tr class="first">
                                <th rowspan="2">No</th>
                                <th colspan="3">Component Issue</th>
                                <th colspan="5">Traceability Scan</th>
                            </tr>
                            <tr class="second">
                                <th class="align-middle">Part Code</th>
                                <th class="align-middle">Unique Code</th>
                                <th class="align-middle">Qty</th>
                                <th class="align-middle">Line</th>
                                <th class="align-middle">Date</th>
                                <th class="align-middle">Calculate Use</th>
                                <th class="align-middle">Balance</th>
                                <th class="align-middle">Result</th>
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
<script>
    function itm_tracer_btn_check_on_click(pThis) {
        const doc = itm_tracer_doc.value.trim()
        const partCode = itm_tracer_part_code.value.trim()
        if(doc.length<=3) {
            itm_tracer_doc.focus()
            return
        }

        if(partCode.length<7) {
            itm_tracer_part_code.focus()
            return
        }

        let mtbl = document.getElementById('item_tracer_tbl')
        let tableku2 = mtbl.getElementsByTagName("tbody")[0]
        let mtbltr = tableku2.getElementsByTagName('tr')
        let ttlrows = mtbltr.length

        let detail = [];


        for (let i = 0; i < ttlrows; i++) {
            if (tableku2.rows[i].cells[3].innerText.length > 0) {
                let prevQty = numeral(tableku2.rows[i].cells[3].innerText).value()
                let newQty = numeral(tableku2.rows[i].cells[4].innerText).value()
                if(newQty<prevQty){
                    alertify.warning(`Please fill properly`)
                    tableku2.rows[i].cells[3].focus()
                    return
                }
                detail.push({
                    job : tableku2.rows[i].cells[0].innerText,
                    qty : newQty,
                    process : tableku2.rows[i].cells[1].innerText.trim()
                })
            } else {
                tableku2.rows[i].cells[2].focus()
                return
            }
        }

        if(detail.length == 0) {
            alertify.warning('There is no Job Number to be checked')
            alertify.message('Try press Enter on PSN')
            itm_tracer_doc.focus()
            return
        }

        const data = {
            doc : doc,
            partCode : partCode,
            detail : detail
        }

        pThis.disabled = true

        document.getElementById("item_tracer_result_divku").getElementsByTagName('tbody')[0].innerHTML = '<tr><td colspan="9">Please wait</td></tr>';
        const div_alert = document.getElementById('itm_tracer-div-alert')
        div_alert.innerHTML = ''
        $.ajax({
            type: "POST",
            url: "<?php echo $_ENV['APP_INTERNAL3_API'] ?>production/supply-status-by-psn",
            data: JSON.stringify(data),
            dataType: "json",
            success: function (response) {
                pThis.disabled = false
                let mydes = document.getElementById("item_tracer_result_divku");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("item_tracer_result_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("item_tracer_result_tbl");
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                tableku2.innerHTML='';
                let nomor = 1
                response.data.forEach((arrayItem) => {
                    let resultHTML = ''
                    if(!arrayItem['CLS_LUPDT']) {
                        resultHTML = `<span class="badge bg-danger">Not Yet Scan</span>`
                    } else {
                        if(arrayItem['BALANCE_LABEL']==0){
                            resultHTML = `<span class="badge bg-info">Scan Complete</span>`
                        } else {
                            resultHTML = `<span class="badge bg-danger">Not Complete</span>`
                        }
                    }
                    newrow = tableku2.insertRow(-1)
                    newcell = newrow.insertCell(0)
                    newcell.classList.add('text-center')
                    newcell.innerText = nomor++
                    newcell = newrow.insertCell(-1)
                    newcell.classList.add('text-center')
                    newcell.innerText = arrayItem['ITMCD']
                    newcell = newrow.insertCell(-1)
                    newcell.innerText = arrayItem['UNQ']
                    newcell = newrow.insertCell(-1)
                    newcell.innerText = arrayItem['QTY']
                    newcell = newrow.insertCell(-1)
                    newcell.innerText = arrayItem['LINE']
                    newcell = newrow.insertCell(-1)
                    newcell.innerText = arrayItem['CLS_LUPDT']
                    newcell = newrow.insertCell(-1)
                    newcell.classList.add('text-end')
                    newcell.innerText = arrayItem['CALCULATE_USE']
                    newcell = newrow.insertCell(-1)
                    newcell.classList.add('text-end')
                    newcell.innerText = arrayItem['BALANCE_LABEL']
                    newcell = newrow.insertCell(-1)
                    newcell.classList.add('text-center')
                    newcell.innerHTML = resultHTML
                    if(arrayItem['IS_COMPLETED'] == 1) {
                        newrow.classList.add('table-success')
                    }
                })

                response.dataReff.forEach((arrayItem) => {
                    let resultHTML = ''
                    if(!arrayItem['CLS_LUPDT']) {
                        resultHTML = `<span class="badge bg-danger">Not Yet Scan</span>`
                    } else {
                        if(arrayItem['BALANCE_LABEL']==0){
                            resultHTML = `<span class="badge bg-info">Scan Complete</span>`
                        } else {
                            resultHTML = `<span class="badge bg-danger">Not Complete</span>`
                        }
                    }
                    newrow = tableku2.insertRow(-1)
                    newcell = newrow.insertCell(0)
                    newcell.classList.add('text-center')
                    newcell.innerText = nomor++
                    newcell = newrow.insertCell(-1)
                    newcell.classList.add('text-center')
                    newcell.innerText = arrayItem['ITMCD']
                    newcell = newrow.insertCell(-1)
                    newcell.innerText = arrayItem['UNQ']
                    newcell = newrow.insertCell(-1)
                    newcell.innerText = arrayItem['QTY']
                    newcell = newrow.insertCell(-1)
                    newcell.innerText = arrayItem['LINE']
                    newcell = newrow.insertCell(-1)
                    newcell.innerText = arrayItem['CLS_LUPDT']
                    newcell = newrow.insertCell(-1)
                    newcell.classList.add('text-end')
                    newcell.innerText = arrayItem['CALCULATE_USE']
                    newcell = newrow.insertCell(-1)
                    newcell.classList.add('text-end')
                    newcell.innerText = arrayItem['BALANCE_LABEL']
                    newcell = newrow.insertCell(-1)
                    newcell.classList.add('text-center')
                    newcell.innerHTML = resultHTML
                })

                response.dataFreshReff.forEach((arrayItem) => {
                    let resultHTML = ''
                    if(!arrayItem['CLS_LUPDT']) {
                        if(arrayItem['UNQX']) {
                            resultHTML = `<span class="badge bg-warning">Just already scanned</span>`
                        } else {
                            resultHTML = `<span class="badge bg-danger">Not Yet Scan</span>`
                        }
                    } else {
                        if(arrayItem['BALANCE_LABEL']==0){
                            resultHTML = `<span class="badge bg-info">Scan Complete</span>`
                        } else {
                            resultHTML = `<span class="badge bg-danger">Not Complete</span>`
                        }
                    }
                    newrow = tableku2.insertRow(-1)
                    newcell = newrow.insertCell(0)
                    newcell.classList.add('text-center')
                    newcell.innerText = nomor++
                    newcell = newrow.insertCell(-1)
                    newcell.classList.add('text-center')
                    newcell.innerText = arrayItem['ITMCD']
                    newcell = newrow.insertCell(-1)
                    newcell.innerText = arrayItem['UNQ']
                    newcell = newrow.insertCell(-1)
                    newcell.innerText = numeral(arrayItem['QTY']).value()
                    newcell = newrow.insertCell(-1)
                    newcell.innerText = arrayItem['LINE']
                    newcell = newrow.insertCell(-1)
                    newcell.innerText = arrayItem['CLS_LUPDT']
                    newcell = newrow.insertCell(-1)
                    newcell.classList.add('text-end')
                    newcell.innerText = arrayItem['CALCULATE_USE']
                    newcell = newrow.insertCell(-1)
                    newcell.classList.add('text-end')
                    newcell.innerText = arrayItem['BALANCE_LABEL']
                    newcell = newrow.insertCell(-1)
                    newcell.classList.add('text-center')
                    newcell.innerHTML = resultHTML
                })

                mydes.innerHTML='';
                mydes.appendChild(myfrag);

                if(response.message!='OK') {
                    div_alert.innerHTML = `<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        ${response.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`
                } else {
                    div_alert.innerHTML = `<div class="alert alert-success alert-dismissible fade show" role="alert">
                        ${response.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`
                }
            }, error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow)
                pThis.disabled = false
                document.getElementById("itm_tracer_table_container").getElementsByTagName('tbody')[0].innerHTML = `<tr><td colspan="9">${xthrow}</td></tr>`;
            }
        });
    }

    function itm_tracer_doc_e_keypress(e) {
        if(e.key === 'Enter') {
            itm_tracer_doc.disabled = true
            const doc = itm_tracer_doc.value.trim()
            if(doc.length<7) {
                alertify.warning('PSN is required')
                itm_tracer_doc.disabled = false
                itm_tracer_doc.focus()
                return
            }
            const data = {
                doc : doc
            }
            itm_tracer_doc.disabled = true
            $.ajax({
                type: "GET",
                url: "<?php echo $_ENV['APP_INTERNAL_API'] ?>work-order",
                data: data,
                dataType: "JSON",
                success: function (response) {
                    itm_tracer_doc.disabled = false
                    let ttlrows = response.data.length;
                    let mydes = document.getElementById("item_tracer_divku");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("item_tracer_tbl");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("item_tracer_tbl");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    let newrow, newcell;
                    tableku2.innerHTML='';
                    for (let i = 0; i<ttlrows; i++){

                        newrow = tableku2.insertRow(-1);
                        newcell = newrow.insertCell(0);
                        newcell.classList.add('text-center')
                        newcell.innerHTML = response.data[i].WONO

                        newcell = newrow.insertCell(-1);
                        newcell.classList.add('text-center')
                        newcell.innerHTML = response.data[i].PROCD
                        
                        newcell = newrow.insertCell(-1);
                        newcell.classList.add('text-end')
                        newcell.innerHTML = numeral(response.data[i].PDPP_WORQT).format(',')
                        
                        newcell = newrow.insertCell(-1);
                        newcell.classList.add('text-end')
                        newcell.innerHTML = numeral(response.data[i].CLS_QTY).format(',')
                        
                        newcell = newrow.insertCell(-1);
                        newcell.classList.add('receive-detail-nw', 'form-control')
                        newcell.contentEditable = true
                        newcell.innerHTML = numeral(response.data[i].CLS_QTY).format(',')
                    }
                    
                    mydes.innerHTML='';
                    mydes.appendChild(myfrag);

                    Inputmask({
                        'alias': 'decimal',
                        'groupSeparator': ',',
                    }).mask(document.getElementsByClassName("receive-detail-nw"));
                }, error: function(xhr, xopt, xthrow) {
                    itm_tracer_doc.disabled = false
                    alertify.error(xthrow)
                }
            });
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

    
</script>