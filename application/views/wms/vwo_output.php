<div style="padding: 5px" >
	<div class="container-fluid">
        <div class="row">
            <div class="col mb-1" id="div-alert">

            </div>
        </div>
        <div class="row">
            <div class="col-md-2 mb-1">
                <div class="btn-group">
                    <button class="btn btn-outline-primary" id="wopr_btn_new" title="New" onclick="wopr_btn_new_eC()"><i class="fas fa-file"></i></button>
                    <button class="btn btn-outline-primary" id="wopr_btn_save" onclick="wopr_btn_save_eC(this)"><i class="fas fa-save"></i></button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Line</label>
                    <input type="text" style="text-transform:uppercase" class="form-control" id="wopr_line_input" onfocusout="wopr_line_input_efocusout()" maxlength="15">
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Production Date</label>
                    <input type="text" class="form-control" id="wopr_date_input" onchange="wopr_date_input_eChange()" readonly>
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Shift</label>
                    <select class="form-select" id="wopr_shift_input" onchange="wopr_shift_input_eChange(event)" required>
                        <option value="M">Morning</option>
                        <option value="N">Night</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Assy Code</label>
                    <input type="text" class="form-control" id="wopr_assycode_input" onfocusout="wopr_assycode_input_efocusout()" maxlength="15">
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text" title="Work Order">WO</label>
                    <select class="form-select" id="wopr_wo_input" onchange="wopr_wo_input_eChange(event)" required>
                        <option value="-">-</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Lot Size</label>
                    <input type="text" class="form-control" id="wopr_wo_size_input" readonly disabled>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Process</label>
                    <select class="form-select" id="wopr_process_input" onchange="wopr_process_input_eChange()" required>
                        <option value="-">-</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Input-QTY</label>
                    <input type="text" class="form-control" id="wopr_inputqty_input">
                </div>
            </div>
            <div class="col-md-4 mb-1 text-end">
                <span id="wopr_lblinfo" class="badge bg-info">-</span>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 mb-1">
            <ul class="nav nav-tabs" id="wopr_myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="wopr_home-tab" data-bs-toggle="tab" data-bs-target="#wopr_input_tab" type="button" role="tab" aria-controls="home" aria-selected="true">Output</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="wopr_down_time-tab" data-bs-toggle="tab" data-bs-target="#wopr_input_downtime_tab" type="button" role="tab" aria-controls="home" aria-selected="true">Downtime</button>
                </li>
            </ul>
                <div class="tab-content border" id="wopr_myTabContent">
                    <div class="tab-pane fade show active" id="wopr_input_tab" role="tabpanel">
                        <div class="container-fluid p-1">
                            <div class="row">
                                <div class="col-md-12 mb-3 table-responsive">
                                    <div id="wopr_spreasheet"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-1">
                                    <div class="table-responsive" id="wopr_tbl_div">
                                        <table id="wopr_tbl" class="table table-bordered table-sm caption-top border-primary text-center table-hover">
                                            <caption>Per WO Summary</caption>
                                            <thead>
                                                <tr>
                                                    <th class="align-middle" rowspan="2">Process</th>
                                                    <!-- <th class="align-middle" colspan="2">Output</th>
                                                    <th class="align-middle" rowspan="2">Total</th>
                                                    <th class="align-middle" rowspan="2">Balance</th> -->
                                                </tr>
                                                <tr>
                                                    <!-- <th class="table-success">OK</th>
                                                    <th class="table-danger">NG</th> -->
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
                    <div class="tab-pane fade" id="wopr_input_downtime_tab" role="tabpanel">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    Inputmask({
        'alias': 'decimal',
        'groupSeparator': ',',
    }).mask(document.getElementById("wopr_inputqty_input"));

    $("#wopr_date_input").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });
    $("#wopr_date_input").datepicker('update', new Date());
    $('#wopr_wo_input' ).select2( {
        theme: "bootstrap-5",
        width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
        placeholder: $( this ).data( 'placeholder' ),
    } );

    function wopr_assycode_input_efocusout() {
        if( wopr_assycode_input.value.trim().length > 3) {
            wopr_wo_input.innerHTML = `<option value="-">Please wait</option>`
            wopr_load_at()
            $.ajax({
                type: "GET",
                url: "<?=$_ENV['APP_INTERNAL_API']?>work-order/outstanding",
                data: {item_code : wopr_assycode_input.value},
                dataType: "json",
                success: function (response) {
                    wopr_wo_input.innerHTML = `<option value="-">-</option>`
                    let inputs = '<option value="-">-</option>';
                    response.data.forEach((arrayItem) => {
                        inputs += `<option value="${arrayItem['PDPP_WONO']}#${arrayItem['PDPP_WORQT']}#${arrayItem['PDPP_BOMRV']}">${arrayItem['PDPP_WONO']}</option>`
                    })
                    wopr_wo_input.innerHTML = inputs
                }, error: function(xhr, xopt, xthrow) {
                    wopr_wo_input.innerHTML = `<option value="-">-</option>`
                    alertify.error(xthrow)
                }
            });
        }
    }

    function wopr_wo_input_eChange(e) {
        if(wopr_assycode_input.value.trim().length > 3) { // validasi inputan assy code
            let input = e.target.value.split('#')
            wopr_wo_size_input.value = input.length === 3 ? numeral(input[1]).format(',') : '-'
            wopr_process_input.innerHTML = `<option value="-">Please wait</option>`
            wopr_load_resume()
            $.ajax({
                type: "GET",
                url: "<?=$_ENV['APP_INTERNAL_API']?>work-order/process",
                data: {item_code: wopr_assycode_input.value, bomRev: input[2] },
                dataType: "json",
                success: function (response) {
                    let inputs = '<option value="-">-</option>'
                    response.data.forEach((arrayItem) => {
                        inputs += `<option value="${arrayItem['MBO2_PROCD']}#${arrayItem['MBO2_SEQNO']}">${arrayItem['MBO2_PROCD']}</option>`
                    })
                    wopr_process_input.innerHTML = inputs
                }, error: function(xhr, xopt, xthrow) {
                    alertify.error(xthrow)
                    wopr_process_input.innerHTML = `<option value="-">-</option>`
                }
            });
        }
    }

    function wopr_shift_input_eChange(e) {
        wopr_sso.setData([
            [],
            [],
            [],
            [],
            [],
            [],
            [],
            [],
            [],
            [],
            [],
            [],
            [],
        ])
        wopr_sso.setData(e.target.value === 'M' ? wopr_data_morning.slice() : wopr_data_night.slice())
        wopr_load_at()
    }

    var wopr_data_morning = [
        ['Hour', 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18],
        ['', 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19],
        ['Total', '=B4+B5', '=C4+C5', '=D4+D5', '=E4+E5', '=F4+F5', '=G4+G5', '=H4+H5', '=I4+I5', '=J4+J5', '=K4+K5', '=L4+L5', '=M4+M5'],
        ['OK', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
        ['NG', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
    ]
    var wopr_data_night = [
        ['Hour', 19, 20, 21, 22, 23, 00, 1, 2, 3, 4, 5, 6],
        ['', 20, 21, 22, 23, 00, 1, 2, 3, 4, 5, 6, 7],
        ['Total', '=B4+B5', '=C4+C5', '=D4+D5', '=E4+E5', '=F4+F5', '=G4+G5', '=H4+H5', '=I4+I5', '=J4+J5', '=K4+K5', '=L4+L5', '=M4+M5'],
        ['OK', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
        ['NG', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
    ]

    var wopr_sso = jspreadsheet(wopr_spreasheet, {
        columns : [
            {readOnly : true},
            {
                type: 'numeric',
                mask: '#,##',
            },
            {
                type: 'numeric',
                mask: '#,##',
            },
            {
                type: 'numeric',
                mask: '#,##',
            },
            {
                type: 'numeric',
                mask: '#,##',
            },
            {
                type: 'numeric',
                mask: '#,##',
            },
            {
                type: 'numeric',
                mask: '#,##',
            },
            {
                type: 'numeric',
                mask: '#,##',
            },
            {
                type: 'numeric',
                mask: '#,##',
            },
            {
                type: 'numeric',
                mask: '#,##',
            },
            {
                type: 'numeric',
                mask: '#,##',
            },
            {
                type: 'numeric',
                mask: '#,##',
            },
            {
                type: 'numeric',
                mask: '#,##',
            },
        ],
        allowInsertRow : false,
        allowInsertColumn : false,
        allowDeleteColumn : false,
        allowDeleteRow : false,
        data: wopr_data_morning.slice(),
        updateTable: function(el, cell, x, y, source, value, id) {
            if (Array.from({length: 12}, (_, i) => i + 1).includes(x) && [0,1, 2].includes(y)) {
                cell.classList.add('readonly');
                cell.style.cssText = "font-weight: bold; text-align:center"
            }
            if(Array.from({length: 13}, (_, i) => i + 0).includes(x) && y===3) {
                cell.style.cssText = "background-color:#d1e7dd;font-weight: bold; text-align:center"
            }
            if(Array.from({length: 13}, (_, i) => i + 0).includes(x) && y===4) {
                cell.style.cssText = "background-color:#f8d7da;font-weight: bold; text-align:center"
            }
        },
        onchange : function(instance, cell, x, y, value) {
            const cellName = jspreadsheet.getColumnNameFromId([x,y]);
            console.log('New change on cell ' + cellName + ' to: ' + value + '');
        },
        copyCompatibility:true,
        mergeCells:{
            A1:[1,2]
        },
    });

    function wopr_btn_save_eC(pThis) {
        const lineCode = wopr_line_input.value.trim()
        if(lineCode.length<=1) {
            alertify.warning(`Please input valid line`)
            wopr_line_input.focus()
            return
        }

        const itemCode = wopr_assycode_input.value.trim()
        if(itemCode.length<=3) {
            itemCode.warning(`Please input valid Assy Code`)
            wopr_assycode_input.focus()
            return
        }

        const inputWO = wopr_wo_input.value.split('#')
        const woCode = inputWO[0]
        const woSize = numeral(inputWO[1]).value()
        const inputPCB = numeral(wopr_inputqty_input.value).value()

        const inputProcess = wopr_process_input.value.split('#')
        const processCode = inputProcess[0]
        const processSeq = inputProcess[1]

        let inputSS = wopr_sso.getData()

        let outputQty = []
        let totalOutputQty = 0

        for(let c=1; c<13; c++) {
            let _theDate = wopr_date_input.value
            if(wopr_shift_input.value === 'N' && c>5) {
                let oMoment = moment(_theDate)
                _theDate = oMoment.add(1, 'days').format('YYYY-MM-DD')
            }

            const _valueOK = numeral(wopr_sso.getValueFromCoords(c, 3, true)).value()
            const _valueNG = numeral(wopr_sso.getValueFromCoords(c, 4, true)).value()

            outputQty.push({
                output_at : _theDate + ' ' + inputSS[0][c] + ':00:00',
                outputOK : _valueOK ,
                outputNG : _valueNG ,
            })

            totalOutputQty += (_valueOK + _valueNG)
        }

        if(totalOutputQty > inputPCB) {
            alertify.warning(`Please check total output (OK+NG) & Input-Qty`)
            return
        }

        const dataInput = {
            line_code : lineCode,
            item_code : itemCode,
            item_bom_rev : inputWO[2],
            wo_code : woCode,
            wo_size : woSize,
            input_qty : inputPCB,
            shift_code : wopr_shift_input.value,
            production_date : wopr_date_input.value,
            process_code : processCode,
            process_seq : processSeq,
            output : outputQty,
            user_id: uidnya
        }

        if(confirm(`Are you sure ?`)) {
            const div_alert = document.getElementById('div-alert')
            div_alert.innerHTML = ''
            pThis.disabled = true
            $.ajax({
                type: "POST",
                url: "<?=$_ENV['APP_INTERNAL_API']?>work-order",
                data: JSON.stringify({data : dataInput}),
                dataType: "json",
                contentType: "application/json; charset=utf-8",
                success: function (response) {
                    pThis.disabled = false
                    alertify.success(response.message)
                    wopr_load_resume()
                }, error: function(xhr, xopt, xthrow) {
                    wopr_load_at()
                    alertify.error(xthrow)
                    pThis.disabled = false

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
    }

    function wopr_load_resume() {
        const inputWO = wopr_wo_input.value.split('#')
        if(inputWO.length>1) {
            $.ajax({
                type: "GET",
                url: "<?=$_ENV['APP_INTERNAL_API']?>work-order/resume",
                data: {wo_code : inputWO[0]},
                dataType: "json",
                success: function (response) {
                    let myContainer = document.getElementById("wopr_tbl_div");
                    let myfrag = document.createDocumentFragment();
                    let cln = wopr_tbl.cloneNode(true);
                    myfrag.appendChild(cln);
                    let myTable = myfrag.getElementById("wopr_tbl");
                    let myTableBody = myTable.getElementsByTagName("tbody")[0];
                    myTableBody.innerHTML = ''

                    let headRow1Line = ''
                    let headRow2Line = ''
                    response.lines.forEach((arrayItem) => {
                        headRow1Line += `<th class="align-middle" colspan="2">${arrayItem['line_code']} Output</th>`
                        headRow2Line += `<th class="table-success">OK</th>
                                                    <th class="table-danger">NG</th>`
                    })
                    myTable.getElementsByTagName('thead')[0].getElementsByTagName('tr')[0].innerHTML = `<th class="align-middle" rowspan="2">Process</th>${headRow1Line}<th class="align-middle" rowspan="2">Total</th>`
                    const headTR2 = myTable.getElementsByTagName('thead')[0].getElementsByTagName('tr')[1]
                    headTR2.innerHTML = headRow2Line

                    const countLine = response.lines.length
                    const countOutput = response.output.length
                    response.data.forEach((arrayItem) => {
                        newrow = myTableBody.insertRow(-1)
                        newcell = newrow.insertCell(0)
                        newcell.innerHTML = arrayItem['process_code']
                        let _totalPerProcess = 0
                        for(let i =0; i<countLine; i++ ) {
                            let _isFound = false
                            for(let r=0; r<countOutput; r++) {
                                if (arrayItem['process_code'] == response.output[r].process_code &&
                                response.lines[i].line_code == response.output[r].line_code
                                ) {
                                    const _ok = numeral(response.output[r].ok_qty).value()
                                    const _ng = numeral(response.output[r].ng_qty).value()
                                    newcell = newrow.insertCell(-1)
                                    newcell.innerHTML = numeral(_ok).format(',')
                                    newcell = newrow.insertCell(-1)
                                    newcell.innerHTML = numeral(_ng).format(',')
                                    _totalPerProcess += numeral((_ok + _ng )).format(',')
                                    _isFound = true
                                }
                            }
                            if(!_isFound) {
                                newcell = newrow.insertCell(-1)
                                newcell.classList.add('table-secondary')
                                newcell.innerHTML = '-'
                                newcell = newrow.insertCell(-1)
                                newcell.classList.add('table-secondary')
                                newcell.innerHTML = '-'
                            }
                        }
                        newcell = newrow.insertCell(-1)
                        newcell.innerHTML = numeral(_totalPerProcess).format(',')
                    })
                    myContainer.innerHTML = ''
                    myContainer.appendChild(myfrag)
                }, error: function(xhr, xopt, xthrow) {
                    alertify.error(xthrow)
                }
            });
        }
    }

    function wopr_btn_new_eC() {
        wopr_shift_input.value = 'M'
        wopr_sso.setData([
            ['Hour', 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18],
            ['', 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19],
            ['Total', '=B4+B5', '=C4+C5', '=D4+D5', '=E4+E5', '=F4+F5', '=G4+G5', '=H4+H5', '=I4+I5', '=J4+J5', '=K4+K5', '=L4+L5', '=M4+M5'],
            ['OK', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            ['NG', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
        ])
        wopr_line_input.value = ''
        wopr_assycode_input.value = ''
        wopr_wo_input.value = '-'
        wopr_wo_size_input.value = ''
        wopr_process_input.value = '-'

        wopr_tbl.getElementsByTagName("tbody")[0].innerHTML = ''
        wopr_tbl.getElementsByTagName('thead')[0].getElementsByTagName('tr')[0].innerHTML = `<th class="align-middle" rowspan="2">Process</th><th class="align-middle" rowspan="2">Total</th>`
        wopr_tbl.getElementsByTagName('thead')[0].getElementsByTagName('tr')[1].innerHTML = ``
    }

    function wopr_load_at() {
        const inputWO = wopr_wo_input.value.split('#')
        const inputLine = wopr_line_input.value.trim()
        const inputPartCode = wopr_assycode_input.value.trim()
        const inputProcessCode = wopr_process_input.value.split('#')

        if(inputWO.length>1 && inputLine.length > 1 && inputPartCode.length > 5 && inputProcessCode.length>1 && wopr_wo_size_input.value.length >0) {
            const data = {
                wo_code : inputWO[0],
                process_code: inputProcessCode[0],
                line_code: inputLine,
                shift_code: wopr_shift_input.value,
                production_date: wopr_date_input.value,
            }
            wopr_lblinfo.innerText = 'Please wait'
            $.ajax({
                type: "GET",
                url: "<?=$_ENV['APP_INTERNAL_API']?>work-order/output",
                data: data,
                dataType: "json",
                success: function (response) {
                    let inputSS = []
                    if(wopr_shift_input.value == 'N') {
                        inputSS = [
                            ['Hour', 19, 20, 21, 22, 23, 00, 1, 2, 3, 4, 5, 6],
                            ['', 20, 21, 22, 23, 00, 1, 2, 3, 4, 5, 6, 7],
                            ['Total', '=B4+B5', '=C4+C5', '=D4+D5', '=E4+E5', '=F4+F5', '=G4+G5', '=H4+H5', '=I4+I5', '=J4+J5', '=K4+K5', '=L4+L5', '=M4+M5'],
                            ['OK', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                            ['NG', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                        ]
                    } else {
                        inputSS = [
                            ['Hour', 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18],
                            ['', 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19],
                            ['Total', '=B4+B5', '=C4+C5', '=D4+D5', '=E4+E5', '=F4+F5', '=G4+G5', '=H4+H5', '=I4+I5', '=J4+J5', '=K4+K5', '=L4+L5', '=M4+M5'],
                            ['OK', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                            ['NG', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                        ]
                    }
                    wopr_sso.setData(inputSS)
                    wopr_lblinfo.innerText = ''

                    const totalRows = response.data.length
                    for(let c=1; c<13; c++) {
                        for(let i=0; i<totalRows; i++) {
                            const _jam = response.data[i].running_at.substring(11,13)*1
                            if(_jam == inputSS[0][c]) {
                                inputSS[3][c] =response.data[i].ok_qty
                                inputSS[4][c] =response.data[i].ng_qty
                                break;
                            }
                        }
                    }
                    wopr_sso.setData(inputSS)

                    wopr_inputqty_input.value = response.inputPCB
                }, error: function(xhr, xopt, xthrow) {
                    wopr_lblinfo.innerText = ''
                    alertify.error(xthrow)

                    const div_alert = document.getElementById('div-alert')
                    div_alert.innerHTML = ''
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
    }

    function wopr_line_input_efocusout() {
        wopr_load_at()
    }

    function wopr_process_input_eChange() {
        wopr_load_at()
    }

    function wopr_date_input_eChange() {
        wopr_load_at()
    }
</script>
