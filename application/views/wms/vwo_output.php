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
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Line</label>
                    <select class="form-select" id="wopr_line_input" required onchange="wopr_line_input_efocusout()">
                        <option value="-">-</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Production Date</label>
                    <input type="text" class="form-control" id="wopr_date_input" onchange="wopr_date_input_eChange()" readonly>
                </div>
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
                                <div class="col-md-6 mt-2 mb-1">
                                    <div class="btn-group">
                                        <button class="btn btn-primary" id="wopr_btn_save" onclick="wopr_btn_save_eC(this)"><i class="fas fa-save"></i></button>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-1 text-end">
                                    <span id="wopr_lblinfo" class="badge bg-info">-</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 mb-1">
                                    <div class="input-group input-group-sm mb-1">
                                        <label class="input-group-text">Shift</label>
                                        <select class="form-select" id="wopr_shift_input" onchange="wopr_shift_input_eChange(event)" required>
                                            <option value="M">Morning</option>
                                            <option value="N">Night</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-1">
                                    <div class="input-group input-group-sm mb-1">
                                        <label class="input-group-text">Assy Code</label>
                                        <input type="text" class="form-control" id="wopr_assycode_input" maxlength="15" disabled>
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
                                <div class="col-md-3 mb-1">
                                    <div class="input-group input-group-sm mb-1">
                                        <label class="input-group-text">Lot Size</label>
                                        <input type="text" class="form-control" id="wopr_wo_size_input" readonly disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-md-3 mb-1">
                                    <div class="input-group input-group-sm mb-1">
                                        <label class="input-group-text">Process</label>
                                        <select class="form-select" id="wopr_process_input" onchange="wopr_process_input_eChange()" required>
                                            <option value="-">-</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-1">
                                    <div class="input-group input-group-sm mb-1">
                                        <label class="input-group-text" title="Cycle Time">CT</label>
                                        <input type="text" class="form-control" id="wopr_input_ct" readonly disabled value="-">
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="wopr_ss_container1">
                                <div class="col-md-12 mb-3 table-responsive">
                                    <div id="wopr_spreasheet"></div>
                                </div>
                            </div>
                            <div class="row d-none" id="wopr_ss_container2">
                                <div class="col-md-12 mb-3 table-responsive">
                                    <div id="wopr_spreasheet2"></div>
                                </div>
                            </div>
                            <div class="row d-none">
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
                        <div class="container-fluid p-1">
                            <div class="row">
                                <div class="col-md-12 mt-2 mb-1">
                                    <div class="btn-group">
                                        <button class="btn btn-primary" id="wopr_btn_save_downtime" onclick="wopr_btn_save_downtime_eC(this)"><i class="fas fa-save"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3 table-responsive">
                                    <div id="wopr_downtime_spreasheet"></div>
                                </div>
                            </div>
                            <div class="row">
                                <small class="text-body-secondary">
                                    <p>Downtime value is in minute</p>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="wopr_modal_input_history">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Inputs-Qty List : <strong><span id="wopr_modal_span_wo"></span></strong></h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive" id="wopr_modal_tbl_div">
                        <table id="wopr_modal_tbl" class="table table-sm table-striped table-bordered table-hover text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>Production Date</th>
                                    <th>Shift</th>
                                    <th>Line</th>
                                    <th>Process</th>
                                    <th>Input</th>
                                    <th>Created at</th>
                                    <th>Updated at</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr class="table-info">
                                    <td colspan="4"><strong>Total</strong></td>
                                    <td><strong><span id="wopr_modal_span_total"></span></strong></td>
                                    <td colspan="2"></td>
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
<script>

    $("#wopr_date_input").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });
    $("#wopr_date_input").datepicker('update', new Date());
    var wopr_wo_o = $('#wopr_wo_input').select2( {
        theme: "bootstrap-5",
        width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
        placeholder: $( this ).data( 'placeholder' ),
    });

    function wopr_wo_input_eChange(e) {
        let input = e.target.value.split('#')
        wopr_wo_size_input.value = input.length > 1 ? numeral(input[1]).format(',') : '-'
        wopr_process_input.innerHTML = `<option value="-">Please wait</option>`
        wopr_assycode_input.value = input[3] ? input[3] : ''
        wopr_load_resume()
        $.ajax({
            type: "GET",
            url: "<?=$_ENV['APP_INTERNAL_API']?>work-order/process",
            data: {item_code: input[3], bomRev: input[2] },
            dataType: "json",
            success: function (response) {
                let inputs = '<option value="-">-</option>'
                response.data.forEach((arrayItem) => {
                    inputs += `<option value="${arrayItem['MBO2_PROCD']}#${arrayItem['MBO2_SEQNO']}">${arrayItem['MBO2_PROCD']} (P${arrayItem['MBO2_SEQNO']})</option>`
                })
                wopr_process_input.innerHTML = inputs
            }, error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow)
                wopr_process_input.innerHTML = `<option value="-">-</option>`
            }
        });
        wopr_load_process_ct()
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
        ['OUTPUT', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
        ['MRB', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
        ['Total', '=B3+B4', '=C3+C4', '=D3+D4', '=E3+E4', '=F3+F4', '=G3+G4', '=H3+H4', '=I3+I4', '=J3+J4', '=K3+K4', '=L3+L4', '=M3+M4'],
    ]
    var wopr_data_morning2 = [
        ['Hour', 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18],
        ['', 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19],
        ['INPUT', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
        ['OUTPUT', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
        ['MRB', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
        ['Total', '=B3+B5', '=C3+C5', '=D3+D5', '=E3+E5', '=F3+F5', '=G3+G5', '=H3+H5', '=I3+I5', '=J3+J5', '=K3+K5', '=L3+L5', '=M3+M5'],
       
    ]
    var wopr_data_night = [
        ['Hour', 19, 20, 21, 22, 23, 00, 1, 2, 3, 4, 5, 6],
        ['', 20, 21, 22, 23, 00, 1, 2, 3, 4, 5, 6, 7],
        ['OUTPUT', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
        ['MRB', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
        ['Total', '=B3+B4', '=C3+C4', '=D3+D4', '=E3+E4', '=F3+F4', '=G3+G4', '=H3+H4', '=I3+I4', '=J3+J4', '=K3+K4', '=L3+L4', '=M3+M4'],
    ]
    var wopr_data_night2 = [
        ['Hour', 19, 20, 21, 22, 23, 00, 1, 2, 3, 4, 5, 6],
        ['', 20, 21, 22, 23, 00, 1, 2, 3, 4, 5, 6, 7],
        ['INPUT', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
        ['OUTPUT', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
        ['MRB', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
        ['Total', '=B3+B5', '=C3+C5', '=D3+D5', '=E3+E5', '=F3+F5', '=G3+G5', '=H3+H5', '=I3+I5', '=J3+J5', '=K3+K5', '=L3+L5', '=M3+M5'],
    ]

    var wopr_sso = jspreadsheet(wopr_spreasheet, {
        columns : [
            {
                readOnly : true,
                width:90,
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
            {
                type: 'numeric',
                mask: '#,##',
            },
        ],
        allowInsertRow : false,
        allowInsertColumn : false,
        allowDeleteColumn : false,
        allowDeleteRow : false,
        rowDrag:false,
        data: wopr_data_morning.slice(),
        updateTable: function(el, cell, x, y, source, value, id) {
            if (Array.from({length: 12}, (_, i) => i + 1).includes(x) && [0,1, 4].includes(y)) {
                cell.classList.add('readonly');
                cell.style.cssText = "font-weight: bold; text-align:center"
            }
            if(Array.from({length: 13}, (_, i) => i + 0).includes(x) && y===2) {
                cell.style.cssText = "background-color:#d1e7dd;font-weight: bold; text-align:center"
            }
            if(Array.from({length: 13}, (_, i) => i + 0).includes(x) && y===3) {
                cell.style.cssText = "background-color:#f8d7da;font-weight: bold; text-align:center"
            }

        },
        onchange : function(instance, cell, x, y, value) {
            const cellName = jspreadsheet.getColumnNameFromId([x,y]);
        },
        copyCompatibility:true,
        columnSorting:false,
        mergeCells:{
            A1:[1,2]
        },
    });
    var wopr_sso2 = jspreadsheet(wopr_spreasheet2, {
        columns : [
            {
                readOnly : true,
                width:90,},
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
        rowDrag:false,
        data: wopr_data_morning2.slice(),
        updateTable: function(el, cell, x, y, source, value, id) {
            if (Array.from({length: 12}, (_, i) => i + 1).includes(x) && [0,1, 5].includes(y)) {
                cell.classList.add('readonly');
                cell.style.cssText = "font-weight: bold; text-align:center"
            }
            if(Array.from({length: 13}, (_, i) => i + 0).includes(x) && [2,3].includes(y)) {
                cell.style.cssText = "background-color:#d1e7dd;font-weight: bold; text-align:center"
            }
            if(Array.from({length: 13}, (_, i) => i + 0).includes(x) && [4].includes(y)) {
                cell.style.cssText = "background-color:#f8d7da;font-weight: bold; text-align:center"
            }

        },
        onchange : function(instance, cell, x, y, value) {
            const cellName = jspreadsheet.getColumnNameFromId([x,y]);
        },
        copyCompatibility:true,
        columnSorting:false,
        mergeCells:{
            A1:[1,2]
        },
    });



    var wopr_downtime_sso = jspreadsheet(wopr_downtime_spreasheet, {
        columns : [
            {
                type: 'text',
                title:'Shift',
                width:50,
                readOnly : true
            },
            {
                type: 'numeric',
                title:'Working Hours',
                mask: '#,##.00',
                width:100,
                align: 'right'
            },
            {
                type: 'numeric',
                title:'Working Time',
                mask: '#,##.00',
                width:100,
                align: 'right',
                readOnly : true
            },
            {
                type: 'text',
                title:'Efficiency Biasa',
                width:100,
                readOnly : true
            },
            {
                type: 'text',
                title:'Efficiency Aktual',
                width:100,
                readOnly : true
            },
            {
                type: 'numeric',
                title:'Maintenance',
                mask: '#,##.00',
                width:90,
                align: 'right'
            },
            {
                type: 'numeric',
                title:'M/C Trouble',
                mask: '#,##.00',
                width:90,
                align: 'right'
            },
            {
                type: 'numeric',
                title:'Change model',
                mask: '#,##.00',
                width:100,
                align: 'right'
            },
            {
                type: 'numeric',
                title:'4M (New model )',
                mask: '#,##.00',
                width:120,
                align: 'right'
            },
            {
                type: 'numeric',
                title:'Not Production 15 minutes',
                mask: '#,##.00',
                width:170,
                align: 'right'
            },
            {
                type: 'numeric',
                title:'Not Production',
                mask: '#,##.00',
                width:100,
                align: 'right'
            },
            {
                type: 'numeric',
                title:'Not Production Plan',
                mask: '#,##.00',
                width:130,
                align: 'right'
            },
            {
                type: 'numeric',
                title:'Balance',
                mask: '#,##.00',
                width:60,
                align: 'right',
                readOnly : true
            },
        ],
        allowInsertRow : false,
        allowInsertColumn : false,
        allowDeleteColumn : false,
        allowDeleteRow : false,
        rowDrag:false,
        defaultColWidth : 150,
        data: [
            ['M',0,0,'=ROUND(C1/B1*100,2)&"%"','=ROUND(C1/(B1-((F1+G1+H1+I1+J1+K1)/60))*100,2)&"%"',0,0,0,0,0,0,0,'=C1+((F1+G1+H1+I1+J1+K1+L1)/60)'],
            ['N',0,0,'=ROUND(C2/B2*100,2)&"%"','=ROUND(C2/(B2-((F2+G2+H2+I2+J2+K2)/60))*100,2)&"%"',0,0,0,0,0,0,0,'=C2+((F2+G2+H2+I2+J2+K2+L2)/60)'],
        ],
        copyCompatibility:true,
        columnSorting:false,
        updateTable: function(el, cell, x, y, source, value, id) {
            if([2,3,4].includes(x)) {
                cell.style.cssText = "background-color:#f5fba3; text-align:center"
            }
            if([5].includes(x)) {
                cell.style.cssText = "background-color:#f4b084; text-align:center"
            }
            if([6,7,8].includes(x)) {
                cell.style.cssText = "background-color:#ffd966; text-align:center"
            }
            if([9,10,11].includes(x)) {
                cell.style.cssText = "background-color:#66ffff; text-align:center"
            }
            if([12].includes(x)) {
                cell.style.cssText = "font-weight: bold; text-align:center"
            }
            if([0].includes(x)) {
                cell.style.cssText = "font-weight: bold; text-align:center"
            }
            if([1].includes(x)) {
                cell.style.cssText = "background-color:#ccffcc; text-align:center"
            }
        }
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

        if(wopr_process_input.value==='-') {
            alertify.warning(`Please select a process`)
            wopr_process_input.focus()
            return
        }

        if(['-','--'].includes(wopr_input_ct.value)) {
            alertify.warning(`Cycle Time is required`)
            return
        }

        const inputProcess = wopr_process_input.value.split('#')
        const processCode = inputProcess[0]
        const processSeq = inputProcess[1]

        let inputSS = wopr_sso.getData()
        let inputSS2 = wopr_sso2.getData()

        let outputQty = []
        let totalOutputQty = 0
        let totalInputQty = 0

        if(!processCode.includes('HW')) { 
            for(let c=1; c<13; c++) {
                let _theDate = wopr_date_input.value
                if(wopr_shift_input.value === 'N' && c>5) {
                    let oMoment = moment(_theDate)
                    _theDate = oMoment.add(1, 'days').format('YYYY-MM-DD')
                }
    
                const _valueOK = numeral(wopr_sso.getValueFromCoords(c, 2, true)).value()
                const _valueNG = numeral(wopr_sso.getValueFromCoords(c, 3, true)).value()
    
                outputQty.push({
                    output_at : _theDate + ' ' + inputSS[0][c] + ':00:00',
                    outputOK : _valueOK ,
                    outputNG : _valueNG ,
                })
    
                totalOutputQty += (_valueOK + _valueNG)
            }

            totalInputQty = totalOutputQty
    
            if(totalInputQty > woSize) {
                alertify.warning(`Please check Output & Lot Size`)
                return
            }
        } else {
            for(let c=1; c<13; c++) {
                let _theDate = wopr_date_input.value
                if(wopr_shift_input.value === 'N' && c>5) {
                    let oMoment = moment(_theDate)
                    _theDate = oMoment.add(1, 'days').format('YYYY-MM-DD')
                }
    
                const _valueOK = numeral(wopr_sso2.getValueFromCoords(c, 2, true)).value()
                const _valueOUTPUT = numeral(wopr_sso2.getValueFromCoords(c, 3, true)).value()
                const _valueNG = numeral(wopr_sso2.getValueFromCoords(c, 4, true)).value()
    
                outputQty.push({
                    output_at : _theDate + ' ' + inputSS2[0][c] + ':00:00',
                    outputOK : _valueOUTPUT ,
                    outputNG : _valueNG ,
                })
    
                totalInputQty += _valueOK
            }

            if(totalInputQty > woSize) {
                alertify.warning(`Please check INPUT & Lot Size`)
                return
            }
        }

        const dataInput = {
            line_code : lineCode,
            item_code : itemCode,
            item_bom_rev : inputWO[2],
            wo_code : woCode,
            wo_size : woSize,
            input_qty : totalInputQty,
            shift_code : wopr_shift_input.value,
            production_date : wopr_date_input.value,
            process_code : processCode,
            process_seq : processSeq,
            output : outputQty,
            user_id: uidnya,
            cycle_time : numeral(wopr_input_ct.value).value(),
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

    function wopr_btn_save_downtime_eC(pThis) {
        const lineCode = wopr_line_input.value.trim()
        if(lineCode.length<=1) {
            alertify.warning(`Please input valid line`)
            wopr_line_input.focus()
            return
        }

        let inputDowntimeSS = wopr_downtime_sso.getData()

        let downtimeMinute = []
        let productionTime = []

        productionTime.push({
            working_hours : numeral(wopr_downtime_sso.getValueFromCoords(1, 0, true)).value(),
            shift_code : 'M'
        })
        productionTime.push({
            working_hours : numeral(wopr_downtime_sso.getValueFromCoords(1, 1, true)).value(),
            shift_code : 'N'
        })

        for(let c=5; c<12; c++) {
            downtimeMinute.push({
                downtime_code : (c-4),
                req_minutes : numeral(wopr_downtime_sso.getValueFromCoords(c, 0, true)).value(),
                shift_code : 'M'
            })
            downtimeMinute.push({
                downtime_code : (c-4),
                req_minutes : numeral(wopr_downtime_sso.getValueFromCoords(c, 1, true)).value(),
                shift_code : 'N'
            })
        }

        const dataInput = {
            line_code : lineCode,
            shift_code : wopr_shift_input.value,
            production_date : wopr_date_input.value,
            user_id: uidnya,
            downtimeMinute : downtimeMinute,
            productionTime : productionTime
        }

        if(confirm(`Are you sure ?`)) {
            const div_alert = document.getElementById('div-alert')
            div_alert.innerHTML = ''
            pThis.disabled = true
            $.ajax({
                type: "POST",
                url: "<?=$_ENV['APP_INTERNAL_API']?>work-order/downtime",
                data: JSON.stringify({data : dataInput}),
                dataType: "json",
                contentType: "application/json; charset=utf-8",
                success: function (response) {
                    pThis.disabled = false
                    alertify.success(response.message)
                    wopr_load_resume()
                }, error: function(xhr, xopt, xthrow) {
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
                data: {
                    wo_code : inputWO[0]
                },
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
                                    newcell.title = response.output[r].max_production_date
                                    newcell.innerHTML = numeral(_ok).format(',')
                                    
                                    newcell = newrow.insertCell(-1)
                                    newcell.title = response.output[r].max_production_date
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

    function wopr_load_downTime() {
        if(wopr_line_input.value.length>1) {
            $.ajax({
                type: "GET",
                url: "<?=$_ENV['APP_INTERNAL_API']?>work-order/downtime",
                data: {
                    line_code: wopr_line_input.value,
                    production_date : wopr_date_input.value
                },
                dataType: "json",
                success: function (response) {

                    let inputSS = [
                        ['M',0,0,'=ROUND(C1/B1*100,2)&"%"','=ROUND(C1/(B1-((F1+G1+H1+I1+J1+K1)/60))*100,2)&"%"',0,0,0,0,0,0,0,'=C1+((F1+G1+H1+I1+J1+K1+L1)/60)'],
                        ['N',0,0,'=ROUND(C2/B2*100,2)&"%"','=ROUND(C2/(B2-((F2+G2+H2+I2+J2+K2)/60))*100,2)&"%"',0,0,0,0,0,0,0,'=C2+((F2+G2+H2+I2+J2+K2+L2)/60)'],
                    ]

                    for(let c=5; c<12; c++) {
                        response.data.forEach((arrayItem) => {
                            if((c-4)==arrayItem['downtime_code']) {
                                switch(arrayItem['shift_code']) {
                                    case 'M':
                                        inputSS[0][c] = arrayItem['req_minutes']
                                        break;
                                    case 'N':
                                        inputSS[1][c] = arrayItem['req_minutes']
                                        break;
                                }
                            }
                        })
                    }
                    response.workingTime.forEach((arrayItem) => {
                        switch(arrayItem['shift_code']) {
                            case 'M':
                                inputSS[0][2] = arrayItem['working_time_total']
                                break;
                            case 'N':
                                inputSS[1][2] = arrayItem['working_time_total']
                                break;
                        }
                    })
                    wopr_downtime_sso.setData(inputSS)
                }, error: function(xhr, xopt, xthrow) {
                    alertify.error(xthrow)
                }
            });
        }
    }

    function wopr_load_productionTime() {
        if(wopr_line_input.value.trim().length>1) {
            $.ajax({
                type: "GET",
                url: "<?=$_ENV['APP_INTERNAL_API']?>work-order/production-time",
                data: {
                    production_date : wopr_date_input.value,
                    line_code : wopr_line_input.value
                },
                dataType: "json",
                success: function (response) {
                    response.data.forEach((arrayItem) => {
                        if(arrayItem['shift_code']=='M') {
                            wopr_downtime_sso.setValue('B1', arrayItem['working_hours'])
                        } else {
                            wopr_downtime_sso.setValue('B2', arrayItem['working_hours'])
                        }
                    })
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
        wopr_downtime_sso.setData([
            ['M',0,0,'=ROUND(C1/B1*100,2)&"%"','=ROUND(C1/(B1-((F1+G1+H1+I1+J1+K1)/60))*100,2)&"%"',0,0,0,0,0,0,0,'=C1+((F1+G1+H1+I1+J1+K1+L1)/60)'],
            ['N',0,0,'=ROUND(C2/B2*100,2)&"%"','=ROUND(C2/(B2-((F2+G2+H2+I2+J2+K2)/60))*100,2)&"%"',0,0,0,0,0,0,0,'=C2+((F2+G2+H2+I2+J2+K2+L2)/60)'],
        ])
        wopr_line_input.value = ''
        wopr_assycode_input.value = ''
        wopr_wo_size_input.value = ''
        wopr_process_input.value = '-'

        wopr_tbl.getElementsByTagName("tbody")[0].innerHTML = ''
        wopr_tbl.getElementsByTagName('thead')[0].getElementsByTagName('tr')[0].innerHTML = `<th class="align-middle" rowspan="2">Process</th><th class="align-middle" rowspan="2">Total</th>`
        wopr_tbl.getElementsByTagName('thead')[0].getElementsByTagName('tr')[1].innerHTML = ``

        wopr_wo_o.val('-').trigger("change");
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
                    wopr_lblinfo.innerText = ''
                    let inputSS = []
                    if(wopr_shift_input.value == 'N') {
                        inputSS = [
                            ['Hour', 19, 20, 21, 22, 23, 00, 1, 2, 3, 4, 5, 6],
                            ['', 20, 21, 22, 23, 00, 1, 2, 3, 4, 5, 6, 7],
                            ['OUTPUT', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                            ['MRB', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                            ['Total', '=B3+B4', '=C3+C4', '=D3+D4', '=E3+E4', '=F3+F4', '=G3+G4', '=H3+H4', '=I3+I4', '=J3+J4', '=K3+K4', '=L3+L4', '=M3+M4'],
                        ]
                    } else {
                        inputSS = [
                            ['Hour', 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18],
                            ['', 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19],
                            ['OUTPUT', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                            ['MRB', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                            ['Total', '=B3+B4', '=C3+C4', '=D3+D4', '=E3+E4', '=F3+F4', '=G3+G4', '=H3+H4', '=I3+I4', '=J3+J4', '=K3+K4', '=L3+L4', '=M3+M4'],
                        ]
                    }
                    wopr_sso.setData(inputSS)

                    let inputSS2 = []
                    if(wopr_shift_input.value == 'N') {
                        inputSS2 = [
                            ['Hour', 19, 20, 21, 22, 23, 00, 1, 2, 3, 4, 5, 6],
                            ['', 20, 21, 22, 23, 00, 1, 2, 3, 4, 5, 6, 7],
                            ['INPUT', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                            ['OUTPUT', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                            ['MRB', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                            ['Total', '=B3+B5', '=C3+C5', '=D3+D5', '=E3+E5', '=F3+F5', '=G3+G5', '=H3+H5', '=I3+I5', '=J3+J5', '=K3+K5', '=L3+L5', '=M3+M5'],
                        ]
                    } else {
                        inputSS2 = [
                            ['Hour', 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18],
                            ['', 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19],
                            ['INPUT', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                            ['OUTPUT', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                            ['MRB', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                            ['Total', '=B3+B5', '=C3+C5', '=D3+D5', '=E3+E5', '=F3+F5', '=G3+G5', '=H3+H5', '=I3+I5', '=J3+J5', '=K3+K5', '=L3+L5', '=M3+M5'],
                        ]
                    }
                    wopr_sso2.setData(inputSS2)
                    

                    
                    if(!inputProcessCode[0].includes('HW')) {
                        const totalRows = response.data.length
                        for(let c=1; c<13; c++) {
                            for(let i=0; i<totalRows; i++) {
                                const _jam = response.data[i].running_at.substring(11,13)*1
                                if(_jam == inputSS[0][c]) {
                                    inputSS[2][c] =response.data[i].ok_qty
                                    inputSS[3][c] =response.data[i].ng_qty
                                    break;
                                }
                            }
                        }
                        wopr_sso.setData(inputSS)

                    } else {
                        inputSS2[2][1] = response.inputPCB

                        const totalRows = response.data.length
                        for(let c=1; c<13; c++) {
                            for(let i=0; i<totalRows; i++) {
                                const _jam = response.data[i].running_at.substring(11,13)*1
                                if(_jam == inputSS2[0][c]) {
                                    inputSS2[3][c] = response.data[i].ok_qty
                                    inputSS2[4][c] = response.data[i].ng_qty
                                    break;
                                }
                            }
                        }
                        wopr_sso2.setData(inputSS2)
                    }
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
        wopr_process_input.value = '-'
        wopr_input_ct.value = ''
        wopr_load_at()
        wopr_load_downTime()
        wopr_load_process_ct()
        wopr_load_productionTime()
        wopr_load_keikaku_data()
    }

    function wopr_load_keikaku_data() {
        if(wopr_line_input.value === '-') {
            return
        }
        wopr_assycode_input.value = ''
        $.ajax({
            type: "GET",
            url: "<?=$_ENV['APP_INTERNAL_API']?>keikaku",
            data: {line_code : wopr_line_input.value, production_date : wopr_date_input.value},
            dataType: "json",
            success: function (response) {
                wopr_wo_input.innerHTML = `<option value="-">-</option>`
                let inputs = '<option value="-">-</option>';
                response.data.forEach((arrayItem) => {
                    inputs += `<option value="${arrayItem['wo_full_code']}#${arrayItem['lot_size']}#${arrayItem['PDPP_BOMRV']}#${arrayItem['item_code']}">${arrayItem['wo_full_code']}</option>`
                })
                wopr_wo_input.innerHTML = inputs
            }
        });
    }

    function wopr_process_input_eChange() {
        if(wopr_process_input.value !='-') {
            const inputProcessCode = wopr_process_input.value.split('#')
            if(inputProcessCode[0].includes('HW')) {
                wopr_ss_container1.classList.add('d-none')
                wopr_ss_container2.classList.remove('d-none')
            } else {
                wopr_ss_container1.classList.remove('d-none')
                wopr_ss_container2.classList.add('d-none')
            }
        }
        wopr_load_at()
        wopr_load_process_ct()
    }

    function wopr_date_input_eChange() {
        wopr_load_at()
        wopr_load_downTime()
        wopr_load_productionTime()
        wopr_load_keikaku_data()
    }

    function wopr_load_process_ct() {
        if(wopr_line_input.value.trim().length > 1
        && wopr_assycode_input.value.trim().length > 2
        && wopr_process_input.value != '-'
        ) {
            const inputProcess = wopr_process_input.value.split('#')
            const processCode = inputProcess[0]
            wopr_input_ct.value = 'Please wait'
            $.ajax({
                type: "GET",
                url: "<?=$_ENV['APP_INTERNAL_API']?>process-master/cycle-time",
                data: {
                    line_code : wopr_line_input.value,
                    assy_code: wopr_assycode_input.value,
                    process_code: processCode,
                    production_date: wopr_date_input.value,
                },
                dataType: "json",
                success: function (response) {
                    wopr_input_ct.value = '-'
                    if(response.data) {
                        wopr_input_ct.value = response.data.cycle_time
                    }
                }, error: function(xhr, xopt, xthrow) {
                    wopr_input_ct.value = '--'
                }
            });
        }
    }

    function wopr_btn_info_input_eC() {
        if(wopr_wo_input.value != '-' && wopr_process_input.value != '-') {
            wopr_modal_span_total.innerText = '0'
            const inputWO = wopr_wo_input.value.split('#')
            wopr_modal_span_wo.innerText = inputWO[0]

            const inputProcess = wopr_process_input.value.split('#')                      

            wopr_modal_tbl.getElementsByTagName("tbody")[0].innerHTML = `<tr><td colspan="7">Please wait</td></tr>`
            $.ajax({
                type: "GET",
                url: "<?=$_ENV['APP_INTERNAL_API']?>work-order/input",
                data: {wo_code : inputWO[0], process_code : inputProcess[0]},
                dataType: "json",
                success: function (response) {
                    let myContainer = document.getElementById("wopr_modal_tbl_div");
                    let myfrag = document.createDocumentFragment();
                    let cln = wopr_modal_tbl.cloneNode(true);
                    myfrag.appendChild(cln);
                    let myTable = myfrag.getElementById("wopr_modal_tbl");
                    let mySpan = myfrag.getElementById("wopr_modal_span_total");
                    let myTableBody = myTable.getElementsByTagName("tbody")[0];
                    myTableBody.innerHTML = ''
                    
                    let totalQty = 0
                    response.data.forEach((arrayItem) => {
                        newrow = myTableBody.insertRow(-1)
                        newcell = newrow.insertCell(-1)
                        newcell.innerText = arrayItem['production_date']
                        newcell = newrow.insertCell(-1)
                        newcell.innerText = arrayItem['shift_code']
                        newcell = newrow.insertCell(-1)
                        newcell.innerText = arrayItem['line_code']
                        newcell = newrow.insertCell(-1)
                        newcell.innerText = arrayItem['process_code']
                        newcell = newrow.insertCell(-1)
                        newcell.innerText = numeral(arrayItem['input_qty']).format(',')
                        newcell = newrow.insertCell(-1)
                        newcell.innerText = arrayItem['created_at']
                        newcell = newrow.insertCell(-1)
                        newcell.innerText = arrayItem['updated_at']
                        totalQty += numeral(arrayItem['input_qty']).value()
                    })
                    mySpan.innerText = numeral(totalQty).format(',')
                    myContainer.innerHTML = ''
                    myContainer.appendChild(myfrag)
                }
            });
            $("#wopr_modal_input_history").modal('show')
        }
    }

    function wopr_load_line_code() {
        $.ajax({
            type: "GET",
            url: "<?=$_ENV['APP_INTERNAL_API']?>process-master/line-code",
            dataType: "json",
            success: function (response) {
                wopr_line_input.innerHTML = `<option value="-">-</option>`
                let inputs = '<option value="-">-</option>';
                response.data.forEach((arrayItem) => {
                    inputs += `<option value="${arrayItem['line_code']}">${arrayItem['line_code']}</option>`
                })
                wopr_line_input.innerHTML = inputs
            }
        });
    }

    wopr_load_line_code()
</script>
