<div style="padding: 5px" >
	<div class="container-fluid">
        <div class="row" id="keikaku_stack1">
            <div class="col-md-6">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Line</label>
                    <select class="form-select" id="keikaku_line_input" required onchange="keikaku_line_input_on_change()">
                        <option value="-">-</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Production Date</label>
                    <input type="text" class="form-control" id="keikaku_date_input" readonly onchange="keikaku_date_input_on_change()">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col mb-1" id="keikaku-div-alert">

            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="keikaku_home-tab" data-bs-toggle="tab" data-bs-target="#keikaku_tabRM" type="button" role="tab" aria-controls="home" aria-selected="true">Data</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="keikaku_checksim-tab" data-bs-toggle="tab" data-bs-target="#keikaku_tab_checksim" type="button" role="tab" aria-controls="home" aria-selected="true">Calculation</button>
                </li>
            </ul>
                <div class="tab-content" id="keikaku_myTabContent">
                    <div class="tab-pane fade show active" id="keikaku_tabRM" role="tabpanel" aria-labelledby="home-tab">
                        <div class="container-fluid p-1">
                            <div class="row" id="keikaku_stack2">
                                <div class="col-md-2 mb-1">
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary" id="keikaku_btn_new" title="New" onclick="keikaku_btn_new_eC()"><i class="fas fa-file"></i></button>
                                        <button class="btn btn-outline-primary" id="keikaku_btn_save" title="Save" onclick="keikaku_btn_save_eC(this)"><i class="fas fa-save"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-1 table-responsive">
                                    <div id="keikaku_data_spreadsheet"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="keikaku_tab_checksim" role="tabpanel">
                        <div class="row mt-1 mb-1">
                            <div class="col-md-6">
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" id="keikaku_btn_new_calculation" title="New" onclick="keikaku_btn_new_calculation_eClick()"><i class="fas fa-file"></i></button>
                                    <button class="btn btn-outline-primary" id="keikaku_btn_save_calculation" onclick="keikaku_btn_save_calculation_eClick(this)"><i class="fas fa-save"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-1 table-responsive">
                                <div id="keikaku_calculation_spreadsheet"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var keikaku_data_sso = jspreadsheet(keikaku_data_spreadsheet, {
        columns : [
            {
                title:'Seq.',
                type: 'numeric',
                width:50,
            },
            {
                title:'Model',
                type: 'text',
                width:100,
            },
            {
                title:'Job',
                type: 'text',
                width:100,
            },
            {
                title:'Lot',
                type: 'numeric',
                mask: '#,##',
                width:75,
            },
            {
                title:'Production',
                type: 'numeric',
                mask: '#,##',
                width:75,
            },
            {
                title:'Type',
                type: 'text',
                width:100,
            },
            {
                title:'Spec',
                type: 'text',
                width:100,
            },
            {
                title:'Assy Code',
                type: 'text',
                width:100,
            },
            {
                title:'ASP/KD',
                type: 'text',
                width:100,
            },
            {
                title:'Spec',
                type: 'dropdown',
                source: ['A', 'B']
            },
        ],
        allowInsertColumn : false,
        allowDeleteColumn : false,
        allowRenameColumn : false,
        allowDeleteRow : false,
        rowDrag:false,
        data: [
            [,],
            [,],
            [,],
            [,]
        ],
        copyCompatibility:true,
        columnSorting:false,
        oninsertrow : function() {
            console.log('sini insert')
            // console.log(keikaku_data_spreadsheet.clientHeight)
        },
        tableOverflow:true,
        tableHeight: ($(window).height()-keikaku_stack1.offsetHeight-keikaku_stack2.offsetHeight - 140) + 'px',
    });

    var keikaku_calculation_sso = jspreadsheet(keikaku_calculation_spreadsheet, {
        columns : [
            {
                readOnly : true,
                type : 'text',
                width:120,
                align : 'left',
            }, ...Array.from({length: 24}, (_, i) => Object.create({
                    type : 'numeric',
                    // mask: '#,##.00',
                    }))
        ],
        allowInsertColumn : false,
        allowDeleteColumn : false,
        allowRenameColumn : false,
        allowDeleteRow : false,
        rowDrag:false,
        data: [
            ['NON OT', ...Array.from({length: 24}, (_, i) => null)],
            ['OT1', ...Array.from({length: 24}, (_, i) => null)],
            ['MENT NON OT', ...Array.from({length: 24}, (_, i) => null)],
            ['MENT OT', ...Array.from({length: 24}, (_, i) => null)],
            ['MENT NON OT', ...Array.from({length: 24}, (_, i) => null)],
            ['MENT OT', ...Array.from({length: 24}, (_, i) => null)],
            ['Change Model', ...Array.from({length: 24}, (_, i) => null)],
            ['Change Model/OT', ...Array.from({length: 24}, (_, i) => null)],
            ['Retention Time',
            `=IF($B$8="M", B3, B2)`,
            `=IF($B$8="M", C3, C2)`,
            `=IF($B$8="M", D3, D2)`,
            `=IF($B$8="M", E3, E2)`,
            `=IF($B$8="M", F3, F2)`,
            `=IF($B$8="M", G3, G2)`,
            `=IF($B$8="M", H3, H2)`,
            `=IF($B$8="M", I3, I2)`,
            `=IF($J$8="OT", J4, J3)`,
            `=IF($J$8="OT", K4, K3)`,
            `=IF($J$8="OT", L2, L1)`,
            `=IF($J$8="OT", M2, M1)`,

            `=IF($B$8="M", N3, N2)`,
            `=IF($B$8="M", O3, O2)`,
            `=IF($B$8="M", P3, P2)`,
            `=IF($B$8="M", Q3, Q2)`,
            `=IF($B$8="M", R3, R2)`,
            `=IF($B$8="M", S3, S2)`,
            `=IF($B$8="M", T3, T2)`,
            `=IF($B$8="M", U3, U2)`,
            `=IF($V$8="OT", V2, V1)`,
            `=IF($V$8="OT", W2, W1)`,
            `=IF($V$8="OT", X2, X1)`,
            `=IF($V$8="OT", Y2, Y1)`,
            ],
            ['Hour', ...Array.from({length: 16}, (_, i) => i + 8), ...Array.from({length: 8}, (_, i) => i)],
            ['Efficiency','0.85' , '=B11', '=C11','=D11', '=E11', '=F11', '=G11', '=H11', '=I11', '=J11', '=K11', '=L11',
                '=M11','=N11', '=O11', '=P11', '=Q11', '=R11', '=S11', '=T11', '=U11', '=V11', '=W11', '=X11'
            ],
        ],
        copyCompatibility:true,
        columnSorting:false,
        allowInsertRow : false,
        updateTable: function(el, cell, x, y, source, value, id) {
            if (Array.from({length: 24}, (_, i) => i + 1).includes(x) && [6,8,9].includes(y)) {
                cell.classList.add('readonly');
                cell.style.cssText = "font-weight: bold; text-align:center"
            }
            if([1,2,3,4,5,6,7,8,9].includes(x) && [0,1,2,3,4,5].includes(y)) {
                cell.style.cssText = "background-color:#daeef3"
            }
            if([10,11,12,22,23,24].includes(x) && [0,1,2,3,4,5].includes(y)) {
                cell.style.cssText = "background-color:#e4dfec"
            }
            if([13,14,15,16,17,18,19,20,21].includes(x) && [0,1,2,3,4,5].includes(y)) {
                cell.style.cssText = "background-color:#ebf1de"
            }
        },

    });

    function keikaku_load_line_code() {
        $.ajax({
            type: "GET",
            url: "<?=$_ENV['APP_INTERNAL_API']?>process-master/line-code",
            dataType: "json",
            success: function (response) {
                keikaku_line_input.innerHTML = `<option value="-">-</option>`
                let inputs = '<option value="-">-</option>';
                response.data.forEach((arrayItem) => {
                    inputs += `<option value="${arrayItem['line_code']}">${arrayItem['line_code']}</option>`
                })
                keikaku_line_input.innerHTML = inputs
            }
        });
    }

    keikaku_load_line_code()

    $("#keikaku_date_input").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });
    $("#keikaku_date_input").datepicker('update', new Date());

    function keikaku_btn_new_eC() {
        if(confirm('Are you sure want to create new empty file ?')) {
            keikaku_reset_data()
        }
    }

    function keikaku_reset_data() {
        keikaku_data_sso.setData([
            [,],
            [,],
            [,],
            [,]
        ])
    }

    function keikaku_reset_calculation() {
        keikaku_calculation_sso.setData([
            ['NON OT', ...Array.from({length: 24}, (_, i) => null)],
            ['OT1', ...Array.from({length: 24}, (_, i) => null)],
            ['MENT NON OT', ...Array.from({length: 24}, (_, i) => null)],
            ['MENT OT', ...Array.from({length: 24}, (_, i) => null)],
            ['MENT NON OT', ...Array.from({length: 24}, (_, i) => null)],
            ['MENT OT', ...Array.from({length: 24}, (_, i) => null)],
            ['Change Model', ...Array.from({length: 24}, (_, i) => null)],
            ['Change Model/OT', ...Array.from({length: 24}, (_, i) => null)],
            ['Retention Time',
            `=IF($B$8="M", B3, B2)`,
            `=IF($B$8="M", C3, C2)`,
            `=IF($B$8="M", D3, D2)`,
            `=IF($B$8="M", E3, E2)`,
            `=IF($B$8="M", F3, F2)`,
            `=IF($B$8="M", G3, G2)`,
            `=IF($B$8="M", H3, H2)`,
            `=IF($B$8="M", I3, I2)`,
            `=IF($J$8="OT", J4, J3)`,
            `=IF($J$8="OT", K4, K3)`,
            `=IF($J$8="OT", L2, L1)`,
            `=IF($J$8="OT", M2, M1)`,

            `=IF($B$8="M", N3, N2)`,
            `=IF($B$8="M", O3, O2)`,
            `=IF($B$8="M", P3, P2)`,
            `=IF($B$8="M", Q3, Q2)`,
            `=IF($B$8="M", R3, R2)`,
            `=IF($B$8="M", S3, S2)`,
            `=IF($B$8="M", T3, T2)`,
            `=IF($B$8="M", U3, U2)`,
            `=IF($V$8="OT", V2, V1)`,
            `=IF($V$8="OT", W2, W1)`,
            `=IF($V$8="OT", X2, X1)`,
            `=IF($V$8="OT", Y2, Y1)`,
            ],
            ['Hour', ...Array.from({length: 16}, (_, i) => i + 8), ...Array.from({length: 8}, (_, i) => i)],
            ['Efficiency','0.85' , '=B11', '=C11','=D11', '=E11', '=F11', '=G11', '=H11', '=I11', '=J11', '=K11', '=L11',
                '=M11','=N11', '=O11', '=P11', '=Q11', '=R11', '=S11', '=T11', '=U11', '=V11', '=W11', '=X11'
            ],
        ])
    }

    function keikaku_btn_new_calculation_eClick() {
        if(confirm('Are you sure want to create new empty file... ?')) {
            keikaku_reset_calculation()
        }
    }

    function keikaku_btn_save_eC(pThis) {
        if(keikaku_line_input.value === '-') {
            alertify.warning(`Line is required`)
            keikaku_line_input.focus()
            return
        }

        const dataDetail = []
        let JobUnique = []

        let inputSS = keikaku_data_sso.getData().filter((data) => data[2].length && data[7].length > 1)
        const inputSSCount = inputSS.length

        for(let i=0; i<inputSSCount;i++) {
            let _job = inputSS[i][2].trim() + inputSS[i][7].trim()
            if(!JobUnique.includes(_job)) {
                JobUnique.push(_job)

                dataDetail.push({
                    seq : inputSS[i][0],
                    model_code : inputSS[i][1].trim(),
                    wo_code : inputSS[i][2].trim(),
                    item_code : inputSS[i][7].trim(),
                    lot_size : numeral(inputSS[i][3]).value(),
                    plan_qty : numeral(inputSS[i][4]).value(),
                    type : inputSS[i][5].trim(),
                    specs : inputSS[i][6].trim(),
                    packaging : inputSS[i][8].trim(),
                    specs_side : inputSS[i][9].trim(),
                })
            }
        }

        const dataInput = {
            line_code : keikaku_line_input.value,
            production_date : keikaku_date_input.value,
            user_id: uidnya,
            detail : dataDetail
        }

        if(confirm('Are you sure want to save ?')) {
            const div_alert = document.getElementById('keikaku-div-alert')
            pThis.disabled = true
            $.ajax({
                type: "POST",
                url: "<?=$_ENV['APP_INTERNAL_API']?>keikaku",
                data: JSON.stringify(dataInput),
                dataType: "JSON",
                success: function (response) {
                    alertify.success(response.message)
                    pThis.disabled = false
                    div_alert.innerHTML = ''
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

    function keikaku_btn_save_calculation_eClick(pThis) {
        if(keikaku_line_input.value === '-') {
            alertify.warning(`Line is required`)
            keikaku_line_input.focus()
            return
        }

        const dataDetail = []
        let inputSS = keikaku_calculation_sso.getData()
        const inputSSCount = inputSS.length

        for(let c=1; c<25; c++) {
            let _theShift = c<13 ? 'M' : 'N'
            let _theDate = keikaku_date_input.value

            if(_theShift === 'N' && c>17) {
                let oMoment = moment(_theDate)
                _theDate = oMoment.add(1, 'days').format('YYYY-MM-DD')
            }

            let _theTime = c === 17 ? '23' : numeral(inputSS[9][c]).value()-1
            dataDetail.push({
                'shift_code' : _theShift,
                'calculation_at' : _theDate + ' ' + _theTime + ':00:00',
                'worktype1' : numeral(inputSS[0][c]).value(),
                'worktype2' : numeral(inputSS[1][c]).value(),
                'worktype3' : numeral(inputSS[2][c]).value(),
                'worktype4' : numeral(inputSS[3][c]).value(),
                'worktype5' : numeral(inputSS[4][c]).value(),
                'worktype6' : numeral(inputSS[5][c]).value(),
                'flag_mot' : inputSS[7][c],
                'efficiency' : numeral(inputSS[10][c]).value()
            })
        }

        const dataInput = {
            line_code : keikaku_line_input.value,
            production_date : keikaku_date_input.value,
            user_id: uidnya,
            detail : dataDetail
        }

        if(confirm('Are you sure want to save ?')) {
            const div_alert = document.getElementById('keikaku-div-alert')
            pThis.disabled = true
            $.ajax({
                type: "POST",
                url: "<?=$_ENV['APP_INTERNAL_API']?>keikaku/calculation",
                data: JSON.stringify(dataInput),
                dataType: "JSON",
                success: function (response) {
                    alertify.success(response.message)
                    pThis.disabled = false
                    div_alert.innerHTML = ''
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

    function keikaku_date_input_on_change() {
        keikaku_load_calcultion()
        keikaku_load_data()
    }

    function keikaku_load_calcultion() {
        keikaku_reset_calculation()
        $.ajax({
            type: "GET",
            url: "<?=$_ENV['APP_INTERNAL_API']?>keikaku/calculation",
            data: {line_code : keikaku_line_input.value, production_date : keikaku_date_input.value},
            dataType: "json",
            success: function (response) {
                let worktype1 = ['NON OT'];
                let worktype2 = ['OT1'];
                let worktype3 = ['MENT NON OT'];
                let worktype4 = ['MENT OT'];
                let worktype5 = ['MENT NON OT'];
                let worktype6 = ['MENT OT'];
                let flag_mot = ['Change Model/OT'];
                response.data.forEach((arrayItem) => {
                    worktype1.push(Number.parseFloat(arrayItem['worktype1']).toFixed(2))
                    worktype2.push(Number.parseFloat(arrayItem['worktype2']).toFixed(2))
                    worktype3.push(Number.parseFloat(arrayItem['worktype3']).toFixed(2))
                    worktype4.push(Number.parseFloat(arrayItem['worktype4']).toFixed(2))
                    worktype5.push(Number.parseFloat(arrayItem['worktype5']).toFixed(2))
                    worktype6.push(Number.parseFloat(arrayItem['worktype6']).toFixed(2))
                    flag_mot.push(arrayItem['flag_mot'])
                })

                keikaku_calculation_sso.setData([
                    worktype1,
                    worktype2,
                    worktype3,
                    worktype4,
                    worktype5,
                    worktype6,
                    ['Change Model', ...Array.from({length: 24}, (_, i) => null)],
                    flag_mot,
                    ['Retention Time',
                    `=IF($B$8="M", B3, B2)`,
                    `=IF($B$8="M", C3, C2)`,
                    `=IF($B$8="M", D3, D2)`,
                    `=IF($B$8="M", E3, E2)`,
                    `=IF($B$8="M", F3, F2)`,
                    `=IF($B$8="M", G3, G2)`,
                    `=IF($B$8="M", H3, H2)`,
                    `=IF($B$8="M", I3, I2)`,
                    `=IF($J$8="OT", J4, J3)`,
                    `=IF($J$8="OT", K4, K3)`,
                    `=IF($J$8="OT", L2, L1)`,
                    `=IF($J$8="OT", M2, M1)`,

                    `=IF($B$8="M", N3, N2)`,
                    `=IF($B$8="M", O3, O2)`,
                    `=IF($B$8="M", P3, P2)`,
                    `=IF($B$8="M", Q3, Q2)`,
                    `=IF($B$8="M", R3, R2)`,
                    `=IF($B$8="M", S3, S2)`,
                    `=IF($B$8="M", T3, T2)`,
                    `=IF($B$8="M", U3, U2)`,
                    `=IF($V$8="OT", V2, V1)`,
                    `=IF($V$8="OT", W2, W1)`,
                    `=IF($V$8="OT", X2, X1)`,
                    `=IF($V$8="OT", Y2, Y1)`,
                    ],
                    ['Hour', ...Array.from({length: 16}, (_, i) => i + 8), ...Array.from({length: 8}, (_, i) => i)],
                    ['Efficiency','0.85' , '=B11', '=C11','=D11', '=E11', '=F11', '=G11', '=H11', '=I11', '=J11', '=K11', '=L11',
                        '=M11','=N11', '=O11', '=P11', '=Q11', '=R11', '=S11', '=T11', '=U11', '=V11', '=W11', '=X11'
                    ],
                ])
            }
        });
    }

    function keikaku_load_data() {
        keikaku_reset_data()
        $.ajax({
            type: "GET",
            url: "<?=$_ENV['APP_INTERNAL_API']?>keikaku",
            data: {line_code : keikaku_line_input.value, production_date : keikaku_date_input.value},
            dataType: "json",
            success: function (response) {
                let theData = [];
                response.data.forEach((arrayItem) => {
                    theData.push([
                        '',
                        arrayItem['model_code'],
                        arrayItem['wo_code'],
                        arrayItem['lot_size'],
                        arrayItem['plan_qty'],
                        arrayItem['type'],
                        arrayItem['specs'],
                        arrayItem['item_code'],
                        arrayItem['packaging'],
                        arrayItem['specs_side'],
                    ])
                })
                if(theData.length > 0) {
                    keikaku_data_sso.setData(theData)
                }
            }
        });
    }

    function keikaku_line_input_on_change() {
        keikaku_load_calcultion()
        keikaku_load_data()
    }
</script>
