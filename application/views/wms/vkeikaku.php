<style>
    .keikakuBlueColor {
        background-color : #daeef3;
    }
    .keikakuVioletColor {
        background-color : #e4dfec;
    }
    .keikakuGreenColor {
        background-color : #d8e4bc;
    }
    .keikakuGreenOldColor {
        background-color : #ebf1de;
    }
    .keikakuGrayOldColor {
        background-color : #f2f2f2;
    }
    .keikakuFontColorRegular {
        color: black !important
    }
    .keikakuRedColor {
        color : red !important
    }
</style>
<div style="padding: 5px" >
	<div class="container-fluid">
        <div class="row" id="keikaku_stack1">
            <div class="col-md-6">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Line</label>
                    <select class="form-select" id="keikaku_line_input" required onchange="keikaku_line_input_on_change()">
                        <option value="-">-</option>
                    </select>
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-bars"></i></button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="keikakuShowLineModal()">Open Multiple Line</a></li>
                    </ul>
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
            <ul class="nav nav-tabs nav-pills ">
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-5" id="keikaku_asprova-tab" data-bs-toggle="tab" data-bs-target="#keikaku_tab_asprova" type="button" role="tab" aria-controls="home" aria-selected="true">Asprova</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link active rounded-5" id="keikaku_home-tab" data-bs-toggle="tab" data-bs-target="#keikaku_tabRM" type="button" role="tab" aria-controls="home" aria-selected="true">Data</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-5" id="keikaku_checksim-tab" data-bs-toggle="tab" data-bs-target="#keikaku_tab_checksim" type="button" role="tab" aria-controls="home" aria-selected="true">Calculation</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-5" id="keikaku_prodplan-tab" data-bs-toggle="tab" data-bs-target="#keikaku_tab_prodplan" type="button" role="tab" aria-controls="home" aria-selected="true"><i class="fas fa-chart-gantt"></i> Production Plan</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-5" id="keikaku_downtime-tab" data-bs-toggle="tab" data-bs-target="#keikaku_tab_downtime" type="button" role="tab" aria-controls="home" aria-selected="true"><i class="fas fa-down-long text-warning"></i> Down Time</button>
                </li>
            </ul>
                <div class="tab-content" id="keikaku_myTabContent">
                    <div class="tab-pane fade" id="keikaku_tab_asprova" role="tabpanel">
                        <div class="container-fluid p-1">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="input-group input-group-sm mb-1">
                                        <label class="input-group-text">Year</label>
                                        <input type="number" class="form-control" id="keikaku_asprova_year">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group input-group-sm mb-1">
                                        <label class="input-group-text">Month</label>
                                        <select class="form-select" id="keikaku_asprova_month" required onchange="keikaku_asprova_month_onchange()">
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
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group input-group-sm mb-1">
                                        <label class="input-group-text">Revision</label>
                                        <select class="form-select" id="keikaku_asprova_input_rev" required onchange="keikaku_asprova_input_rev_onchange()">
                                            <option value="-">-</option>
                                        </select>
                                        <button class="btn btn-outline-primary" type="button" title="Refresh Revisons List" id="keikaku_btn_refresh_revision" onclick="keikaku_refresh_revisions_on_click()"><i class="fas fa-sync"></i></button>
                                        <button class="btn btn-primary" type="button" title="Upload Asprova ProdPlan" data-bs-toggle="modal" data-bs-target="#keikakuUploadProdplanModal"><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-1 table-responsive">
                                    <div id="keikaku_draft_data_spreadsheet"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade show active" id="keikaku_tabRM" role="tabpanel" aria-labelledby="home-tab">
                        <div class="container-fluid p-1">
                            <div class="row" id="keikaku_stack2">
                                <div class="col-md-6 mb-1">
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary" id="keikaku_btn_new" title="New" onclick="keikaku_btn_new_eC()"><i class="fas fa-file"></i></button>
                                        <button class="btn btn-outline-primary" id="keikaku_btn_save" title="Save" onclick="keikaku_btn_save_eC(this)"><i class="fas fa-save"></i></button>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-1 text-end">
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary" id="keikaku_btn_run_data" title="Run formula" onclick="keikaku_btn_run_data_eC(this)">Run</button>
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
                            <div class="col-md-6 mb-1 text-end">
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" id="keikaku_btn_run_calculation" title="Run formula" onclick="keikaku_btn_run_calculation_eC(this)">Run</button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-1 table-responsive">
                                <div id="keikaku_calculation_spreadsheet"></div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="keikaku_tab_prodplan" role="tabpanel">
                        <div class="row mt-1 mb-1">
                            <div class="col-md-6 mb-1">

                            </div>
                            <div class="col-md-6 mb-1 text-end">
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" id="keikaku_btn_run_prodplan" title="Run formula" onclick="keikaku_btn_run_prodplan_eC(this)">Run</button>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-1 table-responsive" id="keikakuProdplanContainer">
                                <div id="keikaku_prodplan_spreadsheet" style="font-size:80%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade p-1" id="keikaku_tab_downtime" role="tabpanel">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="input-group input-group-sm mb-1">
                                    <label class="input-group-text">Shift</label>
                                    <select class="form-select" id="keikaku_shift_input" onchange="keikaku_shift_input_on_change()">
                                        <option value="M">Morning</option>
                                        <option value="N">Night</option>
                                    </select>
                                    <button class="btn btn-primary" id="keikaku_btn_save_downtime" title="Save" onclick="keikaku_btn_save_downtime_eC(this)"><i class="fas fa-save"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-1 table-responsive">
                                <div id="keikaku_downtime_spreadsheet"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="keikakuLineListModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5">Line selection</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive" id="keikakuLineTblDiv">
                        <table id="keikakuLineTbl" class="table table-sm table-striped table-bordered table-hover">
                            <thead class="table-light text-center">
                                <tr>
                                    <th><input id="keikakuCheckAll" class="form-check-input" type="checkbox"></th>
                                    <th>Line</th>
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
        <button type="button" class="btn btn-primary" id="keikakuBtnOpen" onclick="keikakuBtnOpenOnClick(this)">Open</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="keikakuUploadProdplanModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5">Upload Data</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col mb-1" id="keikaku-modal-div-alert">

                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <input class="form-control" type="file" id="keikakuformFile" onchange="keikakuformFile_on_change()" accept=".xlsx">
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="keikakuBtnUpload" onclick="keikakuBtnUploadOnClick(this)">Upload</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="keikakuWOListFilterModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5">WO selection</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive" id="keikakuWOFilterTblDiv">
                        <table id="keikakuWOFilterTbl" class="table table-sm table-striped table-bordered table-hover">
                            <thead class="table-light text-center">
                                <tr>
                                    <th></th>
                                    <th>Process</th>
                                    <th>WO</th>
                                    <th>Model</th>
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

      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="keikakuEditActualModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5">Editing</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12" id="keikakuEditAlert">

                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label for="platNomor" class="form-label">WO</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="keikakuEditWO" disabled>
                        <button class="btn btn-outline-primary" type="button" title="Copy" onclick="keikakuCopyWO()"><i class="fas fa-copy"></i></button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="keikakuEditHour" disabled>
                        <span class="input-group-text">o'clock</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="platNomor" class="form-label">Actual Output</label>
                        <input type="text" class="form-control" id="keikakuEditOutput" onkeypress="keikakuEditOutputOnKeyPress(event)">
                    </div>
                </div>
            </div>
        </div>
      </div>
      <input type="hidden" id="keikakuEditDate">
      <input type="hidden" id="keikakuEditXCoordinate">
      <input type="hidden" id="keikakuEditSide">
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="keikakuBtnEditActual" onclick="keikakuBtnEditActualOnClick(this)"><i class="fas fa-save"></i></button>
      </div>
    </div>
  </div>
</div>
<script>
    function keikakuCopyWO() {
        navigator.clipboard.writeText(document.getElementById('keikakuEditWO').value)
        alertify.message('copied')
    }
    $("#keikakuEditActualModal").on('shown.bs.modal', function(){
        $("#keikakuEditOutput").focus();
        keikaku_prodplan_sso.resetSelection(true);
        keikakuEditAlert.innerHTML = ''
    });
    Inputmask({
        'alias': 'decimal',
        'groupSeparator': ',',
    }).mask(keikakuEditOutput);
    keikaku_asprova_year.value = new Date().toISOString().substring(0, 4)
    var keikakuModelUnique = []
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
                title:'Spec Side',
                type: 'dropdown',
                source: ['A','B'],
            },
            {
                title:'CT',
                type: 'numeric',
                mask: '#,##.000',
                decimal: '.',
                readOnly: true
            },
            {
                title:'Production Result',
                type: 'numeric',
                mask: '#,##',
                decimal: '.',
                readOnly: true,
                width:100,
                align: 'right'
            },
            {
                title:'Difference',
                type: 'numeric',
                mask: '#,##',
                decimal: '.',
                readOnly: true,
                width:90,
                align: 'right'
            },
        ],
        allowInsertColumn : false,
        allowDeleteColumn : false,
        allowRenameColumn : false,
        allowDeleteRow : false,
        rowDrag:false,
        data: [
            [,,,,,,,,,'A',,],
            [,,,,,,,,,'A',,],
            [,,,,,,,,,'A',,],
            [,,,,,,,,,'A',,],
        ],
        copyCompatibility:true,
        columnSorting:false,
        tableOverflow:true,
        tableHeight: ($(window).height()-keikaku_stack1.offsetHeight-keikaku_stack2.offsetHeight - 150) + 'px',
        updateTable: function(el, cell, x, y, source, value, id) {
            if(x === 10) {
                const _ct = numeral(value).value() ?? 0
                const _job = el.jspreadsheet.getValueFromCoords(2, y, true).trim()
                const _assy_code = el.jspreadsheet.getValueFromCoords(7, y, true).trim()
                if(_ct === 0 && _job.length > 0 && _assy_code.length > 0 ) {
                    cell.style.cssText = "background-color:#8aedff"
                } else {
                    cell.style.cssText = "background-color:#fafafa"
                }
            }

            if(x === 12) {
                const _diff = numeral(value).value() ?? 0
                if(_diff<0) {
                    cell.style.cssText = "color:#ff0000"
                } else {
                    cell.style.cssText = "color:#d3d3d3"
                }
            }
        }
    });
    var keikaku_draft_data_sso = jspreadsheet(keikaku_draft_data_spreadsheet, {
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
                title:'Spec Side',
                type: 'dropdown',
                source: ['A','B'],
            },
            {
                title:'CT',
                type: 'numeric',
                mask: '#,##.000',
                decimal: '.',
                readOnly: true
            },
            {
                title:'Plan Date',
                type: 'text',
                readOnly: true
            },
            {
                title:'Shift',
                type: 'text',
                readOnly: true
            },
            {
                title:'Simulation',
                type: 'text',
                readOnly: true,
                width:150,
            },
        ],
        allowInsertColumn : false,
        allowDeleteColumn : false,
        allowRenameColumn : false,
        allowDeleteRow : false,
        rowDrag:false,
        data: [
            [,,,,,,,,'A'],
            [,,,,,,,,'A'],
            [,,,,,,,,'A'],
            [,,,,,,,,'A'],
        ],
        copyCompatibility:true,
        columnSorting:false,
        tableOverflow:true,
        tableHeight: ($(window).height()-keikaku_stack1.offsetHeight-keikaku_stack2.offsetHeight - 150) + 'px',
    });

    var keikaku_calculation_sso = jspreadsheet(keikaku_calculation_spreadsheet, {
        columns : [
            {
                readOnly : true,
                type : 'text',
                width:120,
                align : 'left',
            }, ...Array.from({length: 36}, (_, i) => Object.create({
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
            ['NON OT', ...Array.from({length: 36}, (_, i) => null)],
            ['OT1', ...Array.from({length: 36}, (_, i) => null)],
            ['MENT NON OT', ...Array.from({length: 36}, (_, i) => null)],
            ['MENT OT', ...Array.from({length: 36}, (_, i) => null)],
            ['MENT NON OT', ...Array.from({length: 36}, (_, i) => null)],
            ['MENT OT', ...Array.from({length: 36}, (_, i) => null)],
            ['Change Model', ...Array.from({length: 36}, (_, i) => null)],
            ['Change Model/OT', ...Array.from({length: 36}, (_, i) => null)],
            ['Retention Time',
            `=IF(UPPER($B$8)="M", B3, B2)`,
            `=IF(UPPER($B$8)="M", C3, C2)`,
            `=IF(UPPER($B$8)="M", D3, D2)`,
            `=IF(UPPER($B$8)="M", E3, E2)`,
            `=IF(UPPER($B$8)="M", F3, F2)`,
            `=IF(UPPER($B$8)="M", G3, G2)`,
            `=IF(UPPER($B$8)="M", H3, H2)`,
            `=IF(UPPER($B$8)="M", I3, I2)`,
            `=IF(UPPER($J$8)="OT", J4, J3)`,
            `=IF(UPPER($J$8)="OT", K4, K3)`,
            `=IF(UPPER($J$8)="OT", L2, L1)`,
            `=IF(UPPER($J$8)="OT", M2, M1)`,

            `=IF(UPPER($B$8)="M", N3, N2)`,
            `=IF(UPPER($B$8)="M", O3, O2)`,
            `=IF(UPPER($B$8)="M", P3, P2)`,
            `=IF(UPPER($B$8)="M", Q3, Q2)`,
            `=IF(UPPER($B$8)="M", R3, R2)`,
            `=IF(UPPER($B$8)="M", S3, S2)`,
            `=IF(UPPER($B$8)="M", T3, T2)`,
            `=IF(UPPER($B$8)="M", U3, U2)`,
            `=IF(UPPER($V$8)="OT", V2, V1)`,
            `=IF(UPPER($V$8)="OT", W2, W1)`,
            `=IF(UPPER($V$8)="OT", X2, X1)`,
            `=IF(UPPER($V$8)="OT", Y2, Y1)`,
            ],
            ['Hour', ...Array.from({length: 16}, (_, i) => i + 8), ...Array.from({length: 8}, (_, i) => i), ...Array.from({length: 16}, (_, i) => i + 8)],
            ['Efficiency','0.85' , '=B11', '=C11','=D11', '=E11', '=F11', '=G11', '=H11', '=I11', '=J11', '=K11', '=L11',
                '=M11','=N11', '=O11', '=P11', '=Q11', '=R11', '=S11', '=T11', '=U11', '=V11', '=W11', '=X11',
                '=Y11','=Z11','=AA11','=AB11','=AC11','=AD11','=AE11', '=AF11', '=AG11', '=AH11', '=AI11', '=AJ11'
            ],
        ],
        copyCompatibility:true,
        columnSorting:false,
        allowInsertRow : false,
        updateTable: function(el, cell, x, y, source, value, id) {
            if (Array.from({length: 36}, (_, i) => i + 1).includes(x) && [6,8,9].includes(y)) {
                cell.classList.add('readonly');
                cell.style.cssText = "font-weight: bold; text-align:center"
            }
            if([1,2,3,4,5,6,7,8,9,25,26,27,28,29,30,31,32,33].includes(x) && [0,1,2,3,4,5].includes(y)) {
                cell.style.cssText = "background-color:#daeef3"
            }
            if([10,11,12,22,23,24,34,35,36].includes(x) && [0,1,2,3,4,5].includes(y)) {
                cell.style.cssText = "background-color:#e4dfec"
            }
            if([13,14,15,16,17,18,19,20,21].includes(x) && [0,1,2,3,4,5].includes(y)) {
                cell.style.cssText = "background-color:#ebf1de"
            }
        },
        tableOverflow:true,
        freezeColumns: 1,
        minDimensions: [50,10],
        tableWidth: '1000px',
    });
    var keikakuTableWidthObserver = 0

    resizeObserverO = new ResizeObserver(() => {
        if(typeof keikakuProdplanContainer != 'undefined') {
            keikakuTableWidthObserver = keikakuProdplanContainer.offsetWidth
            keikaku_prodplan_sso.table.parentNode.style.width = (keikakuTableWidthObserver-30)+'px'
            keikaku_calculation_sso.table.parentNode.style.width = (keikakuTableWidthObserver-30)+'px'
        }
    }).observe(keikakuProdplanContainer)

    var keikaku_prodplan_sso = jspreadsheet(keikaku_prodplan_spreadsheet, {
        columns : [
            ...Array.from({length: 9+36}, (_, i) => {
                            const objek = Object.create({
                                type : 'text',
                                readOnly : true
                                })
                            switch(i) {
                                case 0 :
                                    Object.defineProperty(objek, "width", {value : 30})
                                    break;
                                case 2 :
                                    Object.defineProperty(objek, "width", {value : 155})
                                    break;
                                case 3 :
                                    Object.defineProperty(objek, "width", {value : 60})
                                    break;
                                case 4 :
                                    Object.defineProperty(objek, "width", {value : 60})
                                    break;
                                case 5 :
                                    Object.defineProperty(objek, "width", {value : 95})
                                    break;
                                case 6 :
                                    Object.defineProperty(objek, "width", {value : 70})
                                    break;
                                case 7 :
                                    Object.defineProperty(objek, "width", {value : 105})
                                    break;
                                case 8 :
                                    Object.defineProperty(objek, "width", {value : 90})
                                    break;
                            }
                            return objek
                        }
                )
        ],
        allowInsertColumn : false,
        allowDeleteColumn : false,
        allowRenameColumn : false,
        allowDeleteRow : false,
        rowDrag:false,
        rowResize:true,
        columnResize:false,
        data: [
            ['', '', '' ,'','', '','','Date','','','','','','','','','','','','','',  '','','','','','','','','','','' ,'','','','','','','','','','','','' ,''],
            ['', '', '' ,'','', '','','Time','','7','8','9','10','11','12','13','14','15','16','17','18',  '19','20','21','22','23','0','1','2','3','4','5' ,'6', '7','8','9','10','11','12','13','14','15','16','17','18'],
            ['', '', '' ,'','', '','','Change Model','','','','','','','','','','','','','',  '','','','','','','','','','','' ,'', '','','','','','','','','','','' ,''],
            ['SEQ', '', 'MODEL' ,'WO No','LOT', 'Production','S/T','Time', '','8','9','10','11','12','13','14','15','16','17','18',  '19','20','21','22','23','0','1','2','3','4','5' ,'6','7','8','9','10','11','12','13','14','15','16','17','18','19'],
            ['#', '', '' ,'','', 'Quantity','(H)','Working Time','Retention Time','','','','','','','','','','','','',  '','','','','','','','','','','' ,'', '','','','','','','','','','','' ,''],
            ['', 'Side', 'Type' ,'Spec','', 'Assy Code','Lot S/T','Efficiency','%','','','','','','','','','','','','',  '','','','','','','','','','','' ,'', '','','','','','','','','','','' ,''],
            ['', '', '' ,'','', '','(H)','Shift','TOTAL','Morning','','','','','','','','','','','',  'Night','','','','','','','','','','' ,'', 'Morning','','','','','','','','','','',''],
        ],
        copyCompatibility:true,
        columnSorting:false,
        allowInsertRow : false,
        updateTable: function(el, cell, x, y, source, value, id) {

            if (Array.from({length: 42}, (_, i) => i).includes(x) && [0,1,2,3,4,5,6].includes(y)) {
                cell.classList.add('readonly');
                cell.style.cssText = "font-weight: bold; text-align:center"
            }
            if(Array.from({length: 10}, (_, i) => i+9).includes(x) && y===1) {
                cell.classList.add('keikakuBlueColor')
            }
            if(Array.from({length: 2}, (_, i) => i+19).includes(x) && y===1) {
                cell.classList.add('keikakuVioletColor')
            }
            if(Array.from({length: 10}, (_, i) => i+21).includes(x) && y===1) {
                cell.classList.add('keikakuGreenColor')
            }
            if(Array.from({length: 2}, (_, i) => i+31).includes(x) && y===1) {
                cell.classList.add('keikakuVioletColor')
            }
            if(Array.from({length: 10}, (_, i) => i+33).includes(x) && y===1) {
                cell.classList.add('keikakuBlueColor')
            }
            if(Array.from({length: 2}, (_, i) => i+43).includes(x) && y===1) {
                cell.classList.add('keikakuVioletColor')
            }

            if(Array.from({length: 9}, (_, i) => i+9).includes(x) && y===3) {
                cell.classList.add('keikakuBlueColor')
            }
            if(Array.from({length: 3}, (_, i) => i+18).includes(x) && y===3) {
                cell.classList.add('keikakuVioletColor')
            }
            if(Array.from({length: 9}, (_, i) => i+21).includes(x) && y===3) {
                cell.classList.add('keikakuGreenColor')
            }
            if(Array.from({length: 3}, (_, i) => i+30).includes(x) && y===3) {
                cell.classList.add('keikakuVioletColor')
            }
            if(Array.from({length: 9}, (_, i) => i+33).includes(x) && y===3) {
                cell.classList.add('keikakuBlueColor')
            }
            if(Array.from({length: 3}, (_, i) => i+42).includes(x) && y===3) {
                cell.classList.add('keikakuVioletColor')
            }

            if(Array.from({length: 9}, (_, i) => i+9).includes(x) && y===4) {
                cell.classList.add('keikakuBlueColor')
            }
            if(Array.from({length: 3}, (_, i) => i+18).includes(x) && y===4) {
                cell.classList.add('keikakuVioletColor')
            }
            if(Array.from({length: 9}, (_, i) => i+21).includes(x) && y===4) {
                cell.classList.add('keikakuGreenColor')
            }
            if(Array.from({length: 3}, (_, i) => i+30).includes(x) && y===4) {
                cell.classList.add('keikakuVioletColor')
            }
            if(Array.from({length: 9}, (_, i) => i+33).includes(x) && y===4) {
                cell.classList.add('keikakuBlueColor')
            }
            if(Array.from({length: 3}, (_, i) => i+42).includes(x) && y===4) {
                cell.classList.add('keikakuVioletColor')
            }
            if(x===7) {
                let cellName = '';
                let theCells = ''
                switch(value) {
                    case 'Actual':
                        cellName = jspreadsheet.getColumnNameFromId([x-1,y]);
                        theCells = cell.parentNode.cells
                        for(let i=0; i <theCells.length; i++) {
                            const theCell = theCells[i]
                            theCell.classList.add('keikakuGreenOldColor')
                        }
                        break;
                    case 'Total':
                        cellName = jspreadsheet.getColumnNameFromId([x-1,y]);
                        theCells = cell.parentNode.cells
                        for(let i=0; i <theCells.length; i++) {
                            const theCell = theCells[i]
                            theCell.classList.add('keikakuGrayOldColor')
                        }
                        break;
                    case 'Progress' :
                        cellName = jspreadsheet.getColumnNameFromId([x-1,y]);
                        theCells = cell.parentNode.cells
                        for(let i=0; i <theCells.length; i++) {
                            const theCell = theCells[i]
                            if(numeral(theCell.innerText).value() < 0) {
                                theCell.classList.add('keikakuRedColor')
                            }
                        }
                        break;
                    case 'Total.' :
                        cellName = jspreadsheet.getColumnNameFromId([x-1,y]);
                        theCells = cell.parentNode.cells
                        for(let i=0; i <theCells.length; i++) {
                            const theCell = theCells[i]
                            if(numeral(theCell.innerText).value() < 0) {
                                theCell.classList.add('keikakuRedColor')
                            }
                        }
                        break;
                }
            }

            cell.classList.add('keikakuFontColorRegular')
        },
        tableOverflow:true,
        tableHeight: ($(window).height()-keikaku_stack1.offsetHeight-keikaku_stack2.offsetHeight - 160) + 'px',
        mergeCells:{
            J7:[12,1],V7:[12,1], AH7:[12,1]
        },
        freezeColumns: 9,
        minDimensions: [50,10],
        tableWidth: '1000px',
        onselection : function(instance, x1, y1, x2, y2, origin) {
            let aRow = instance.jspreadsheet.getRowData(y2)
            let aRowSibling = instance.jspreadsheet.getRowData(y2-1)
            let aRowSibling2 = instance.jspreadsheet.getRowData(y2-2)
            if(aRow[7] === 'Actual' && x2 >=9 && aRowSibling[7] === 'TOTAL') {
                let aRowTime = instance.jspreadsheet.getRowData(1)
                keikakuEditHour.value = aRowTime[x2]
                keikakuEditXCoordinate.value = x2
                keikaku_get_wo({
                    prefWO : aRowSibling2[3],
                    procWO : aRowSibling[1],
                    itemWO : aRowSibling[5],
                })
                keikakuEditOutput.value = aRow[x2]
                keikakuEditSide.value = aRowSibling[1]
                instance.jspreadsheet.resetSelection(true);
                $("#keikakuEditActualModal").modal('show')
            }
        }
    });

    function keikaku_get_wo(data) {
        let ttlRows = _temp_asProdpan.length
        for(let i=4; i<ttlRows;i+=2) {
            if(_temp_asProdpan[i][0] == data.itemWO
            && _temp_asProdpan[i][3].includes(data.prefWO)
            && _temp_asProdpan[i][5].substr(0,1) == data.procWO) {
                keikakuEditWO.value = _temp_asProdpan[i][3]
                break;
            }
        }
    }


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


                let mydes = document.getElementById("keikakuLineTblDiv");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("keikakuLineTbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("keikakuLineTbl");
                let checkBox = myfrag.getElementById("keikakuCheckAll");
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                let newrow, newcell;

                tableku2.innerHTML='';
                response.data.forEach((arrayItem) => {
                    let _EleInput = document.createElement('input')
                    _EleInput.type = 'checkbox'
                    _EleInput.classList.add('form-check-input')

                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0);
                    newcell.classList.add('text-center')
                    newcell.append(_EleInput)

                    newcell = newrow.insertCell(1);
                    newcell.classList.add('text-center')
                    newcell.innerText = arrayItem['line_code']
                })
                mydes.innerHTML='';
                mydes.appendChild(myfrag);


                checkBox.onclick = () => {
                    let ttlrows = tableku2.rows.length
                    for (let i = 0; i<ttlrows; i++)
                    {
                        tableku2.rows[i].cells[0].getElementsByTagName('input')[0].checked = checkBox.checked
                    }
                }

                checkBox.checked = false
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
        if(keikaku_line_input.value === '-') {
            keikaku_line_input.focus()
            alertify.warning('Line is required')
            return
        }

        if(confirm('Are you sure want to create new empty data ?')) {
            const div_alert = document.getElementById('keikaku-div-alert')
            div_alert.innerHTML = ''
            $.ajax({
                type: "GET",
                url: "<?=$_ENV['APP_INTERNAL_API']?>keikaku",
                data: {line_code : keikaku_line_input.value, production_date : keikaku_date_input.value},
                dataType: "json",
                success: function (response) {
                    if(response.data.length === 0) {
                        if(confirm('Create new data based on previous balance ?')) {
                            const dataInput = {
                                line_code : keikaku_line_input.value,
                                production_date : keikaku_date_input.value,
                                user_id: uidnya
                            }

                            $.ajax({
                                type: "POST",
                                url: "<?=$_ENV['APP_INTERNAL_API']?>keikaku/from-balance",
                                data: JSON.stringify(dataInput),
                                dataType: "json",
                                success: function (response) {
                                    if(response.data.length > 0) {
                                        keikaku_data_sso.setData(keikakuSetdata(response.data))
                                    } else {
                                        alertify.message(response.message)
                                    }
                                }
                            });
                        }
                    } else {
                        if(response.data.length > 0) {
                            keikaku_data_sso.setData(keikakuSetdata(response.data))
                        }
                    }
                }
            });
        }
    }

    function keikakuSetdata(data) {
        let theData = [];
        data.forEach((arrayItem) => {
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
                ''
            ])
        })
        return theData
    }

    function keikaku_reset_data() {
        keikaku_data_sso.setData([
            [,,,,,,,,,'A',,],
            [,,,,,,,,,'A',,],
            [,,,,,,,,,'A',,],
            [,,,,,,,,,'A',,],
        ])

    }

    function keikaku_reset_calculation() {
        keikaku_calculation_sso.setData([
            ['NON OT', ...Array.from({length: 36}, (_, i) => null)],
            ['OT1', ...Array.from({length: 36}, (_, i) => null)],
            ['MENT NON OT', ...Array.from({length: 36}, (_, i) => null)],
            ['MENT OT', ...Array.from({length: 36}, (_, i) => null)],
            ['MENT NON OT', ...Array.from({length: 36}, (_, i) => null)],
            ['MENT OT', ...Array.from({length: 36}, (_, i) => null)],
            ['Change Model', ...Array.from({length: 36}, (_, i) => null)],
            ['Change Model/OT', ...Array.from({length: 36}, (_, i) => null)],
            ['Retention Time',
            `=IF(OR(UPPER(B8)="N", UPPER(B8)="M", UPPER(B8)="4M"),0,IF($K$8="OT",B6,IF(B8>0,B8,B5)))`,
            `=IF(OR(UPPER(C8)="N", UPPER(C8)="M", UPPER(C8)="4M"),0,IF($K$8="OT",C6,IF(C8>0,C8,C5)))`,
            `=IF(OR(UPPER(D8)="N", UPPER(D8)="M", UPPER(D8)="4M"),0,IF($K$8="OT",D6,IF(D8>0,D8,D5)))`,
            `=IF(OR(UPPER(E8)="N", UPPER(E8)="M", UPPER(E8)="4M"),0,IF($K$8="OT",E6,IF(E8>0,E8,E5)))`,
            `=IF(OR(UPPER(F8)="N", UPPER(F8)="M", UPPER(F8)="4M"),0,IF($K$8="OT",F6,IF(F8>0,F8,F5)))`,
            `=IF(OR(UPPER(G8)="N", UPPER(G8)="M", UPPER(G8)="4M"),0,IF($K$8="OT",G6,IF(G8>0,G8,G5)))`,
            `=IF(OR(UPPER(H8)="N", UPPER(H8)="M", UPPER(H8)="4M"),0,IF($K$8="OT",H6,IF(H8>0,H8,H5)))`,
            `=IF(OR(UPPER(I8)="N", UPPER(I8)="M", UPPER(I8)="4M"),0,IF($K$8="OT",I6,IF(I8>0,I8,I5)))`,
            `=IF(OR(UPPER(J8)="N", UPPER(J8)="M", UPPER(J8)="4M"),0,IF($K$8="OT",J6,IF(J8>0,J8,J5)))`,
            `=IF(OR(UPPER(K8)="N", UPPER(K8)="M", UPPER(K8)="4M"),0,IF($K$8="OT",K6,IF(K8>0,K8,K5)))`,
            `=IF(OR(UPPER(L8)="N", UPPER(L8)="M", UPPER(L8)="4M"),0,IF($K$8="OT",L6,IF(L8>0,L8,L5)))`,
            `=IF(OR(UPPER(M8)="N", UPPER(M8)="M", UPPER(M8)="4M"),0,IF($K$8="OT",M6,IF(M8>0,M8,M5)))`,

            `=IF(OR(UPPER(N8)="N", UPPER(N8)="M", UPPER(N8)="4M"),0,IF($W$8="OT",N6,IF(N8>0,N8,N5)))`,
            `=IF(OR(UPPER(O8)="N", UPPER(O8)="M", UPPER(O8)="4M"),0,IF($W$8="OT",O6,IF(O8>0,O8,O5)))`,
            `=IF(OR(UPPER(P8)="N", UPPER(P8)="M", UPPER(P8)="4M"),0,IF($W$8="OT",P6,IF(P8>0,P8,P5)))`,
            `=IF(OR(UPPER(Q8)="N", UPPER(Q8)="M", UPPER(Q8)="4M"),0,IF($W$8="OT",Q6,IF(Q8>0,Q8,Q5)))`,
            `=IF(OR(UPPER(R8)="N", UPPER(R8)="M", UPPER(R8)="4M"),0,IF($W$8="OT",R6,IF(R8>0,R8,R5)))`,
            `=IF(OR(UPPER(S8)="N", UPPER(S8)="M", UPPER(S8)="4M"),0,IF($W$8="OT",S6,IF(S8>0,S8,S5)))`,
            `=IF(OR(UPPER(T8)="N", UPPER(T8)="M", UPPER(T8)="4M"),0,IF($W$8="OT",T6,IF(T8>0,T8,T5)))`,
            `=IF(OR(UPPER(U8)="N", UPPER(U8)="M", UPPER(U8)="4M"),0,IF($W$8="OT",U6,IF(U8>0,U8,U5)))`,
            `=IF(OR(UPPER(V8)="N", UPPER(V8)="M", UPPER(V8)="4M"),0,IF($W$8="OT",V6,IF(V8>0,V8,V5)))`,
            `=IF(OR(UPPER(W8)="N", UPPER(W8)="M", UPPER(W8)="4M"),0,IF($W$8="OT",W6,IF(W8>0,W8,W5)))`,
            `=IF(OR(UPPER(X8)="N", UPPER(X8)="M", UPPER(X8)="4M"),0,IF($W$8="OT",X6,IF(X8>0,X8,X5)))`,
            `=IF(OR(UPPER(Y8)="N", UPPER(Y8)="M", UPPER(Y8)="4M"),0,IF($W$8="OT",Y6,IF(Y8>0,Y8,Y5)))`,

            `=IF(OR(UPPER(Z8)="N", UPPER(Z8)="M", UPPER(Z8)="4M"),0,IF($AI$8="OT",Z6,IF(Z8>0,Z8,Z5)))`,
            `=IF(OR(UPPER(AA8)="N", UPPER(AA8)="M", UPPER(AA8)="4M"),0,IF($AI$8="OT",AA6,IF(AA8>0,AA8,AA5)))`,
            `=IF(OR(UPPER(AB8)="N", UPPER(AB8)="M", UPPER(AB8)="4M"),0,IF($AI$8="OT",AB6,IF(AB8>0,AB8,AB5)))`,
            `=IF(OR(UPPER(AC8)="N", UPPER(AC8)="M", UPPER(AC8)="4M"),0,IF($AI$8="OT",AC6,IF(AC8>0,AC8,AC5)))`,
            `=IF(OR(UPPER(AD8)="N", UPPER(AD8)="M", UPPER(AD8)="4M"),0,IF($AI$8="OT",AD6,IF(AD8>0,AD8,AD5)))`,
            `=IF(OR(UPPER(AE8)="N", UPPER(AE8)="M", UPPER(AE8)="4M"),0,IF($AI$8="OT",AE6,IF(AE8>0,AE8,AE5)))`,
            `=IF(OR(UPPER(AF8)="N", UPPER(AF8)="M", UPPER(AF8)="4M"),0,IF($AI$8="OT",AF6,IF(AF8>0,AF8,AF5)))`,
            `=IF(OR(UPPER(AG8)="N", UPPER(AG8)="M", UPPER(AG8)="4M"),0,IF($AI$8="OT",AG6,IF(AG8>0,AG8,AG5)))`,
            `=IF(OR(UPPER(AH8)="N", UPPER(AH8)="M", UPPER(AH8)="4M"),0,IF($AI$8="OT",AH6,IF(AH8>0,AH8,AH5)))`,
            `=IF(OR(UPPER(AI8)="N", UPPER(AI8)="M", UPPER(AI8)="4M"),0,IF($AI$8="OT",AI6,IF(AI8>0,AI8,AI5)))`,
            `=IF(OR(UPPER(AJ8)="N", UPPER(AJ8)="M", UPPER(AJ8)="4M"),0,IF($AI$8="OT",AJ6,IF(AJ8>0,AJ8,AJ5)))`,
            `=IF(OR(UPPER(AK8)="N", UPPER(AK8)="M", UPPER(AK8)="4M"),0,IF($AI$8="OT",AK6,IF(AK8>0,AK8,AK5)))`,
            ],
            ['Hour', ...Array.from({length: 16}, (_, i) => i + 8), ...Array.from({length: 8}, (_, i) => i), ...Array.from({length: 16}, (_, i) => i + 8)],
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

        keikaku_btn_run_data_eC(keikaku_btn_run_data)

        const dataDetail = []
        let JobUnique = []

        let inputSS = keikaku_data_sso.getData().filter((data) => data[2].length && data[7].length > 1)
        const inputSSCount = inputSS.length

        for(let i=0; i<inputSSCount;i++) {
            let _job = inputSS[i][2].trim() + inputSS[i][7].trim() + inputSS[i][9].trim()
            let _cycleTime = numeral(inputSS[i][10].trim()).value()
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
                    cycle_time :  numeral(inputSS[i][10]).value()
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
                    keikaku_load_data()
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

        for(let c=1; c<37; c++) {
            let _theShift = c<13 ? 'M' : 'N'
            let _theDate = keikaku_date_input.value

            if(_theShift === 'N' && c>17) {
                let oMoment = moment(_theDate)
                _theDate = oMoment.add(1, 'days').format('YYYY-MM-DD')
            }

            if(c>24) {
                _theShift = 'M'
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
                'plan_worktime' : numeral(inputSS[8][c]).value(),
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
        keikaku_load_asprova()
        keikakuGetDownTime()
    }

    function keikaku_calc_friday(hourAt) {
        const currentDay = new Date().getDay();
        let _defaultWorkHour = 0
        switch(hourAt) {
            case 12:
                _defaultWorkHour = (currentDay == 5 ? 0.25 : 0.5)
                break;
            case 15:
                _defaultWorkHour = (currentDay == 5 ? 1 : 0.83)
                break;
            case 16:
                _defaultWorkHour = (currentDay == 5 ? 0.67 : 1)
                break;
            case 18:
                _defaultWorkHour = (currentDay == 5 ? 0.33 : 0.42)
                break;
        }
        return _defaultWorkHour
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
                if(response.data.length > 0) {
                    response.data.forEach((arrayItem) => {
                        worktype1.push(Number.parseFloat(arrayItem['worktype1']).toFixed(2))
                        worktype2.push(Number.parseFloat(arrayItem['worktype2']).toFixed(2))
                        worktype3.push(Number.parseFloat(arrayItem['worktype3']).toFixed(2))
                        worktype4.push(Number.parseFloat(arrayItem['worktype4']).toFixed(2))
                        worktype5.push(Number.parseFloat(arrayItem['worktype5']).toFixed(2))
                        worktype6.push(Number.parseFloat(arrayItem['worktype6']).toFixed(2))
                        flag_mot.push(arrayItem['flag_mot'])
                    })
                } else {
                    worktype1.push(...[0.75, 1,	0.75, 1, 1, keikaku_calc_friday(12) , 1, 1, keikaku_calc_friday(15), 0, 0,0, 0.75, 1, 0.67, 1, 1, 0.33, 1, 1, 1, 0,	0,0	,0.75,	1, 0.75, 1, 1, 0.50, 1, 1, 0.75,0,0,0])
                    worktype2.push(...[0.75 ,1 ,0.75 ,1 ,1 ,keikaku_calc_friday(12) ,1 ,1 ,keikaku_calc_friday(15) ,keikaku_calc_friday(16) ,1 ,keikaku_calc_friday(18) ,0.75 ,1 ,0.67 ,1 ,1 ,0.33 ,1.00 ,1.00 ,1.00 ,0.75 ,1.00 ,0.75 ,0.75 ,1.00 ,0.75 ,1.00 ,1.00 ,0.50 ,1.00 ,1.00 ,0.83 ,1.00 ,1.00 ,0.42])
                    worktype3.push(...[0    ,0 ,0.75 ,1 ,1 ,keikaku_calc_friday(12) ,1 ,1 ,keikaku_calc_friday(15) ,0    ,0    ,0    ,0.75 ,1 ,0.67 ,1 ,1 ,0.33 ,1 ,1 ,1 ,0    ,0    ,0    ,0.75 ,1.00 ,0.75 ,1.00 ,1.00 ,0.50 ,1.00 ,1.00 ,0.75 ,0    ,0    ,0])
                    worktype4.push(...[0,0,0.75 ,1 ,1 ,keikaku_calc_friday(12) ,1 ,1 ,keikaku_calc_friday(15) ,keikaku_calc_friday(16) ,1 ,keikaku_calc_friday(18) ,0.75 ,1 ,0.67 ,1 ,1 ,0.33 ,1.00 ,1.00 ,1.00 ,0.75 ,1.00 ,0.75 ,0.75 ,1.00 ,0.75 ,1.00 ,1.00 ,0.50 ,1.00 ,1.00 ,0.83 ,1.00 ,1.00 ,0.42])
                    worktype5.push(...[0.75 ,1 ,0.75 ,1 ,1 ,keikaku_calc_friday(12) ,1 ,1 ,keikaku_calc_friday(15) ,0,0,0,0.75 ,1 ,0.67 ,1 ,1 ,0.33 ,1 ,1 ,1 ,0,0,0,0.75 ,1 ,0.75 ,1 ,1 ,0.50 ,1.00 ,1.00 ,0.75,0,0,0])
                    worktype6.push(...[0.75 ,1 ,0.75 ,1 ,1 ,keikaku_calc_friday(12) ,1 ,1 ,keikaku_calc_friday(15) ,keikaku_calc_friday(16) ,1 ,keikaku_calc_friday(18) ,0.75 ,1 ,0.67 ,1 ,1 ,0.33 ,1.00 ,1.00 ,1.00 ,0.75 ,1.00 ,0.75 ,0.75 ,1.00 ,0.75 ,1.00 ,1.00 ,0.50 ,1.00 ,1.00 ,0.83 ,1.00 ,1.00 ,0.42])
                    for(let i=0;i<36;i++) {
                        flag_mot.push('')
                    }
                }

                keikaku_calculation_sso.setData([
                    worktype1,
                    worktype2,
                    worktype3,
                    worktype4,
                    worktype5,
                    worktype6,
                    ['Change Model', ...Array.from({length: 36}, (_, i) => null)],
                    flag_mot,
                    ['Retention Time',
                    `=IF(OR(UPPER(B8)="N", UPPER(B8)="M", UPPER(B8)="4M"),0,IF($K$8="OT",B6,IF(B8>0,B8,B5)))`,
                    `=IF(OR(UPPER(C8)="N", UPPER(C8)="M", UPPER(C8)="4M"),0,IF($K$8="OT",C6,IF(C8>0,C8,C5)))`,
                    `=IF(OR(UPPER(D8)="N", UPPER(D8)="M", UPPER(D8)="4M"),0,IF($K$8="OT",D6,IF(D8>0,D8,D5)))`,
                    `=IF(OR(UPPER(E8)="N", UPPER(E8)="M", UPPER(E8)="4M"),0,IF($K$8="OT",E6,IF(E8>0,E8,E5)))`,
                    `=IF(OR(UPPER(F8)="N", UPPER(F8)="M", UPPER(F8)="4M"),0,IF($K$8="OT",F6,IF(F8>0,F8,F5)))`,
                    `=IF(OR(UPPER(G8)="N", UPPER(G8)="M", UPPER(G8)="4M"),0,IF($K$8="OT",G6,IF(G8>0,G8,G5)))`,
                    `=IF(OR(UPPER(H8)="N", UPPER(H8)="M", UPPER(H8)="4M"),0,IF($K$8="OT",H6,IF(H8>0,H8,H5)))`,
                    `=IF(OR(UPPER(I8)="N", UPPER(I8)="M", UPPER(I8)="4M"),0,IF($K$8="OT",I6,IF(I8>0,I8,I5)))`,
                    `=IF(OR(UPPER(J8)="N", UPPER(J8)="M", UPPER(J8)="4M"),0,IF($K$8="OT",J6,IF(J8>0,J8,J5)))`,
                    `=IF(OR(UPPER(K8)="N", UPPER(K8)="M", UPPER(K8)="4M"),0,IF($K$8="OT",K6,IF(K8>0,K8,K5)))`,
                    `=IF(OR(UPPER(L8)="N", UPPER(L8)="M", UPPER(L8)="4M"),0,IF($K$8="OT",L6,IF(L8>0,L8,L5)))`,
                    `=IF(OR(UPPER(M8)="N", UPPER(M8)="M", UPPER(M8)="4M"),0,IF($K$8="OT",M6,IF(M8>0,M8,M5)))`,

                    `=IF(OR(UPPER(N8)="N", UPPER(N8)="M", UPPER(N8)="4M"),0,IF($W$8="OT",N6,IF(N8>0,N8,N5)))`,
                    `=IF(OR(UPPER(O8)="N", UPPER(O8)="M", UPPER(O8)="4M"),0,IF($W$8="OT",O6,IF(O8>0,O8,O5)))`,
                    `=IF(OR(UPPER(P8)="N", UPPER(P8)="M", UPPER(P8)="4M"),0,IF($W$8="OT",P6,IF(P8>0,P8,P5)))`,
                    `=IF(OR(UPPER(Q8)="N", UPPER(Q8)="M", UPPER(Q8)="4M"),0,IF($W$8="OT",Q6,IF(Q8>0,Q8,Q5)))`,
                    `=IF(OR(UPPER(R8)="N", UPPER(R8)="M", UPPER(R8)="4M"),0,IF($W$8="OT",R6,IF(R8>0,R8,R5)))`,
                    `=IF(OR(UPPER(S8)="N", UPPER(S8)="M", UPPER(S8)="4M"),0,IF($W$8="OT",S6,IF(S8>0,S8,S5)))`,
                    `=IF(OR(UPPER(T8)="N", UPPER(T8)="M", UPPER(T8)="4M"),0,IF($W$8="OT",T6,IF(T8>0,T8,T5)))`,
                    `=IF(OR(UPPER(U8)="N", UPPER(U8)="M", UPPER(U8)="4M"),0,IF($W$8="OT",U6,IF(U8>0,U8,U5)))`,
                    `=IF(OR(UPPER(V8)="N", UPPER(V8)="M", UPPER(V8)="4M"),0,IF($W$8="OT",V6,IF(V8>0,V8,V5)))`,
                    `=IF(OR(UPPER(W8)="N", UPPER(W8)="M", UPPER(W8)="4M"),0,IF($W$8="OT",W6,IF(W8>0,W8,W5)))`,
                    `=IF(OR(UPPER(X8)="N", UPPER(X8)="M", UPPER(X8)="4M"),0,IF($W$8="OT",X6,IF(X8>0,X8,X5)))`,
                    `=IF(OR(UPPER(Y8)="N", UPPER(Y8)="M", UPPER(Y8)="4M"),0,IF($W$8="OT",Y6,IF(Y8>0,Y8,Y5)))`,

                    `=IF(OR(UPPER(Z8)="N", UPPER(Z8)="M", UPPER(Z8)="4M"),0,IF($AI$8="OT",Z6,IF(Z8>0,Z8,Z5)))`,
                    `=IF(OR(UPPER(AA8)="N", UPPER(AA8)="M", UPPER(AA8)="4M"),0,IF($AI$8="OT",AA6,IF(AA8>0,AA8,AA5)))`,
                    `=IF(OR(UPPER(AB8)="N", UPPER(AB8)="M", UPPER(AB8)="4M"),0,IF($AI$8="OT",AB6,IF(AB8>0,AB8,AB5)))`,
                    `=IF(OR(UPPER(AC8)="N", UPPER(AC8)="M", UPPER(AC8)="4M"),0,IF($AI$8="OT",AC6,IF(AC8>0,AC8,AC5)))`,
                    `=IF(OR(UPPER(AD8)="N", UPPER(AD8)="M", UPPER(AD8)="4M"),0,IF($AI$8="OT",AD6,IF(AD8>0,AD8,AD5)))`,
                    `=IF(OR(UPPER(AE8)="N", UPPER(AE8)="M", UPPER(AE8)="4M"),0,IF($AI$8="OT",AE6,IF(AE8>0,AE8,AE5)))`,
                    `=IF(OR(UPPER(AF8)="N", UPPER(AF8)="M", UPPER(AF8)="4M"),0,IF($AI$8="OT",AF6,IF(AF8>0,AF8,AF5)))`,
                    `=IF(OR(UPPER(AG8)="N", UPPER(AG8)="M", UPPER(AG8)="4M"),0,IF($AI$8="OT",AG6,IF(AG8>0,AG8,AG5)))`,
                    `=IF(OR(UPPER(AH8)="N", UPPER(AH8)="M", UPPER(AH8)="4M"),0,IF($AI$8="OT",AH6,IF(AH8>0,AH8,AH5)))`,
                    `=IF(OR(UPPER(AI8)="N", UPPER(AI8)="M", UPPER(AI8)="4M"),0,IF($AI$8="OT",AI6,IF(AI8>0,AI8,AI5)))`,
                    `=IF(OR(UPPER(AJ8)="N", UPPER(AJ8)="M", UPPER(AJ8)="4M"),0,IF($AI$8="OT",AJ6,IF(AJ8>0,AJ8,AJ5)))`,
                    `=IF(OR(UPPER(AK8)="N", UPPER(AK8)="M", UPPER(AK8)="4M"),0,IF($AI$8="OT",AK6,IF(AK8>0,AK8,AK5)))`,
                    ],
                    ['Hour', ...Array.from({length: 16}, (_, i) => i + 8), ...Array.from({length: 8}, (_, i) => i), ...Array.from({length: 12}, (_, i) => i + 8)],
                    ['Efficiency','0.85' , '=B11', '=C11','=D11', '=E11', '=F11', '=G11', '=H11', '=I11', '=J11', '=K11', '=L11',
                        '=M11','=N11', '=O11', '=P11', '=Q11', '=R11', '=S11', '=T11', '=U11', '=V11', '=W11', '=X11',
                        '=Y11','=Z11','=AA11','=AB11','=AC11','=AD11','=AE11', '=AF11', '=AG11', '=AH11', '=AI11', '=AJ11'
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
                response.data.forEach((arrayItem, index) => {
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
                        arrayItem['cycle_time'],
                        arrayItem['ok_qty'],
                        `=IF(L${index+1}=0,0,L${index+1}-E${index+1})`,
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
        keikaku_load_prodplan()
        keikakuGetDownTime()
    }

    function keikaku_btn_run_data_eC(pThis) {
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
                    item_code : inputSS[i][7].trim(),
                })
            }
        }

        const dataInput = {
            line_code : keikaku_line_input.value,
            detail : dataDetail,
            production_date : keikaku_date_input.value
        }
        pThis.disabled = true
        $.ajax({
            type: "POST",
            url: "<?=$_ENV['APP_INTERNAL_API']?>process-master/search",
            data: JSON.stringify(dataInput),
            dataType: "JSON",
            success: function (response) {
                pThis.disabled = false
                let dataLength = keikaku_data_sso.getData().length
                let responseDataLength = response.data.length
                for(let i=0; i < dataLength; i++) {
                    let _itemCode = keikaku_data_sso.getValueFromCoords(7, i, true).trim()
                    keikaku_data_sso.setValue('K'+(i+1), 0)
                    for(let s=0;s<responseDataLength; s++) {
                        if(_itemCode == response.data[s].assy_code.trim()) {
                            keikaku_data_sso.setValue('K'+(i+1), response.data[s].cycle_time, true)
                            break;
                        }
                    }
                }

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

    function _importProdPlanDraft(data) {
        keikakuformFile.disabled = true
        //Read the Excel File data.
        const workbook = XLSX.read(data, {
            type: 'binary'
        });

        //Fetch the name of First Sheet.
        let firstSheet = workbook.SheetNames[0];
        if(workbook.SheetNames.includes('A1') || workbook.SheetNames.includes('B1')) {
            const formData = new FormData()
            formData.append('user_id', uidnya)
            formData.append('template_file', keikakuformFile.files[0])


            const div_alert = document.getElementById('keikaku-modal-div-alert')
            keikakuBtnUpload.disabled = true
            keikakuBtnUpload.innerHTML = 'Please wait'
            $.ajax({
                type: "POST",
                url: "<?=$_ENV['APP_INTERNAL_API']?>production-plan/import",
                data: formData,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function (response) {
                    keikakuformFile.disabled = false
                    keikakuBtnUpload.disabled = false
                    keikakuBtnUpload.innerHTML = 'Upload'
                    keikakuformFile.value = ''
                    div_alert.innerHTML = `<div class="alert alert-info alert-dismissible fade show" role="alert">
                    <strong>${response.message}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>`
                },
                error: function(xhr, xopt, xthrow) {
                    keikakuformFile.disabled = false
                    keikakuBtnUpload.disabled = false
                    keikakuBtnUpload.innerHTML = 'Upload'
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
        } else {
            keikakuformFile.disabled = false
            alertify.warning('Please use a valid template')
        }
    }

    function keikakuBtnUploadOnClick() {
        if (document.getElementById('keikakuformFile').files.length == 0) {
            alert('please select file to upload');
        } else {
            let fileUpload = $("#keikakuformFile")[0];
            //Validate whether File is valid Excel file.
            const regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
            if (regex.test(fileUpload.value.toLowerCase())) {
                if (typeof(FileReader) != "undefined") {
                    const reader = new FileReader();
                    //For Browsers other than IE.
                    if (reader.readAsBinaryString) {
                        console.log('saya perambaan selain IE');
                        reader.onload = function(e) {
                            _importProdPlanDraft(e.target.result);
                        };
                        reader.readAsBinaryString(fileUpload.files[0]);
                    } else {
                        //For IE Browser.
                        reader.onload = function(e) {
                            let data = "";
                            const bytes = new Uint8Array(e.target.result);
                            for (let i = 0; i < bytes.byteLength; i++) {
                                data += String.fromCharCode(bytes[i]);
                            }
                            _importProdPlanDraft(data);
                        };
                        reader.readAsArrayBuffer(fileUpload.files[0]);
                    }
                } else {
                    alert("This browser does not support HTML5.");
                }
            } else {
                alert("Please upload a valid Excel file.");
            }
        }
    }

    function keikaku_load_asprova() {
        keikaku_btn_refresh_revision.disabled = true
        keikaku_asprova_input_rev.innerHTML = `<option value='-'>Please wait</option>`
        $.ajax({
            type: "GET",
            url: "<?=$_ENV['APP_INTERNAL_API']?>production-plan/revisions",
            data: {
                file_year : keikaku_asprova_year.value, file_month : keikaku_asprova_month.value
            },
            dataType: "json",
            success: function (response) {
                keikaku_btn_refresh_revision.disabled = false
                let strTemp = `<option value='-'>-</option>`
                strTemp += response.data.map((item, index) => {
                    return `<option value="${item.revision}">${item.revision}</option>`
                })
                keikaku_asprova_input_rev.innerHTML = strTemp
            }, error: function(xhr, xopt, xthrow) {
                keikaku_asprova_input_rev.innerHTML = `<option value='-'>-</option>`
                keikaku_btn_refresh_revision.disabled = false
            }
        });
    }

    function keikaku_load_prodplan() {
        const revision = keikaku_asprova_input_rev.value
        if(keikaku_asprova_input_rev.value == '-') {
            return
        }

        $.ajax({
            type: "GET",
            url: `<?=$_ENV['APP_INTERNAL_API']?>production-plan/revisions/${btoa(revision)}`,
            data: {
                file_year : keikaku_asprova_year.value,
                file_month : keikaku_asprova_month.value,
                line_code : keikaku_line_input.value
            },
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
                        arrayItem['specs_side'],
                        arrayItem['cycle_time'],
                        arrayItem['production_date'],
                        arrayItem['shift'],
                        '',
                    ])
                })
                if(theData.length == 0) {
                    theData = [
                        [,,,,,,,,'A'],
                        [,,,,,,,,'A'],
                        [,,,,,,,,'A'],
                        [,,,,,,,,'A'],
                    ]
                }
                keikaku_draft_data_sso.setData(theData)
            }, error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow)
            }
        });
    }

    function keikaku_asprova_input_rev_onchange() {
        keikaku_load_prodplan()
    }

    function keikaku_refresh_revisions_on_click() {
        keikaku_load_asprova()
    }

    function keikakuformFile_on_change() {
        const el = document.getElementById('keikaku-modal-div-alert')
        el.innerHTML = ''
    }

    initFileMonth()
    function initFileMonth() {
        const dateArray = new Date().toISOString().substring(0, 10).split('-')
        keikaku_asprova_month.value = dateArray[1]
    }

    function keikaku_asprova_month_onchange() {
        keikaku_load_asprova()
    }

    var _temp_asProdpan = [];

    function keikaku_btn_run_prodplan_eC(pThis) {

        const inputLine = keikaku_line_input.value
        if(inputLine==='-') {
            alertify.warning('Line is required')
            keikaku_line_input.focus()
            return
        }
        const data = {
            line_code: inputLine,
            production_date: keikaku_date_input.value,
        }
        pThis.disabled = true
        keikakuEditDate.value = keikaku_date_input.value

        $.ajax({
            type: "GET",
            url: "<?=$_ENV['APP_INTERNAL_API']?>keikaku/production-plan",
            data: data,
            dataType: "json",
            success: function (response) {
                pThis.disabled = false

                _temp_asProdpan = response.asProdplan

                // populate filter data
                let mydes = document.getElementById("keikakuWOFilterTblDiv");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("keikakuWOFilterTbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("keikakuWOFilterTbl");
                let checkBox = myfrag.getElementById("keikakuCheckAllWO");
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                let newrow, newcell;

                _temp_asProdpan.forEach((currentValue, index) => {
                    if(index>3 && currentValue[3]) {
                        let _EleInput = document.createElement('input')
                        _EleInput.type = 'checkbox'
                        _EleInput.classList.add('form-check-input')
                        _EleInput.checked = true

                        let _concatedColumn = currentValue[5].split('#')

                        newrow = tableku2.insertRow(-1);
                        newcell = newrow.insertCell(0);
                        newcell.classList.add('text-center')
                        newcell.append(_EleInput)

                        newcell = newrow.insertCell(-1);
                        newcell.classList.add('text-center')
                        newcell.innerText = _concatedColumn[0]

                        newcell = newrow.insertCell(-1);
                        newcell.classList.add('text-center')
                        newcell.innerText = currentValue[3]

                        newcell = newrow.insertCell(-1);
                        newcell.classList.add('text-center')
                        newcell.innerText = _concatedColumn[1]
                    }
                })
                mydes.innerHTML='';
                mydes.appendChild(myfrag);

                // display prodplan to grid
                keikakuDisplayProdplan(response.asProdplan, response.dataSensor)

            }, error: function(xhr, xopt, xthrow) {
                pThis.disabled = false
                alertify.error(xthrow)
            }
        });
    }

    function keikakuDisplayProdplan(data, dataS) {
        let inputSS = [
                        ['', '', '' ,'','', '','','Date','','','','','','','','','','','','','',  '','','','','','','','','','','' ,'',  '','','','','','','','','','','' ,''],
                        ['', '', '' ,'','', '','','Time','','7','8','9','10','11','12','13','14','15','16','17','18',  '19','20','21','22','23','0','1','2','3','4','5' ,'6',  '7','8','9','10','11','12','13','14','15','16','17','18'],
                        ['', '', '' ,'','', '','','Change Model','','','','','','','','','','','','','',  '','','','','','','','','','','' ,'',  '','','','','','','','','','','' ,''],
                        ['SEQ', '', 'MODEL' ,'WO No','LOT', 'Production','S/T','Time', '','8','9','10','11','12','13','14','15','16','17','18',  '19','20','21','22','23','0','1','2','3','4','5' ,'6','7', '8','9','10','11','12','13','14','15','16','17','18','19'],
                        ['#', '', '' ,'','', 'Quantity','(H)','Working Time','Retention Time','','','','','','','','','','','','',  '','','','','','','','','','','' ,'',  '','','','','','','','','','','' ,''],
                        ['', 'Side', 'Type' ,'Spec','', 'Assy Code','Lot S/T','Efficiency','%','','','','','','','','','','','','',  '','','','','','','','','','','' ,'',  '','','','','','','','','','','' ,''],
                        ['', '', '' ,'','', '','(H)','Shift','TOTAL','Morning','','','','','','','','','','','',  'Night','','','','','','','','','','' ,'',  'Morning','','','','','','','','','','' ,'' ],
                    ];
        keikaku_prodplan_sso.setData(inputSS)
        const totalRowsMatrix = data.length
        const totalRowsSensor = dataS.length


        let nomorUrut = 1;
        for(let i=3; i<totalRowsMatrix; i++) {
            let _newRow1 = []
            let _newRow2 = []
            let _newRow3 = []
            let _newRow4 = []
            let _newRow5 = []
            let _newRow6 = []
            let _newRow7 = []
            let _newRow8 = []
            if (data[i][0]) {

                const _tempA = data[i][5].split('#')
                const _model = _tempA[1]
                const _wo_no = _tempA[2]
                const _lot_size = _tempA[3]
                const _production_qty = _tempA[4]
                const _st = data[i][4]
                _newRow3.push(nomorUrut)
                _newRow3.push('')
                _newRow3.push(_model)
                _newRow3.push(_wo_no)
                _newRow3.push(_lot_size)
                _newRow3.push(_production_qty)
                _newRow3.push(Number(_st).toFixed(4))
                _newRow3.push('PLAN PROD')
                _newRow3.push(0)

                _newRow4.push('')
                _newRow4.push(_tempA[0])
                _newRow4.push(_tempA[5])
                _newRow4.push(_tempA[6])
                _newRow4.push('')
                _newRow4.push(data[i][0])
                _newRow4.push('')
                _newRow4.push('TOTAL')
                _newRow4.push('')

                let totalQtyRun = 0
                for(let c=9; c<(9+12+12+12); c++) {
                    if(inputSS[1][c] == Number(data[0][(c-3)])) {
                        inputSS[4][c] = Number(data[2][(c-3)]).toFixed(2)
                        if(c<33) {
                            _newRow3[8]+=Number(data[i][c-3])
                        }
                        _newRow3.push(data[i][c-3])

                        totalQtyRun += Number(data[i][c-3])

                        if(Number(data[i][c-3])==0) {
                            _newRow4.push('')
                        } else {
                            _newRow4.push(totalQtyRun)
                        }
                    }
                }
                inputSS.push(_newRow3)
                inputSS.push(_newRow4)

                _newRow5.push('')
                _newRow5.push('')
                _newRow5.push('')
                _newRow5.push('')
                _newRow5.push('')
                _newRow5.push('')
                _newRow5.push('')
                _newRow5.push('Actual')
                _newRow5.push(0)

                _newRow6.push('')
                _newRow6.push('')
                _newRow6.push('')
                _newRow6.push('')
                _newRow6.push('')
                _newRow6.push('')
                _newRow6.push('')
                _newRow6.push('Total')
                _newRow6.push('')


                _newRow7.push('')
                _newRow7.push('')
                _newRow7.push('')
                _newRow7.push('')
                _newRow7.push('')
                _newRow7.push('')
                _newRow7.push('')
                _newRow7.push('Progress')
                _newRow7.push(0)

                _newRow8.push('')
                _newRow8.push('')
                _newRow8.push('')
                _newRow8.push('')
                _newRow8.push('')
                _newRow8.push('')
                _newRow8.push('')
                _newRow8.push('Total.')
                _newRow8.push('')

                let totalQtySensor = 0
                for(let r=0; r<totalRowsSensor; r++) {
                    if(data[i][3] == dataS[r][3]) {
                        for(let c=9; c<(9+12+12+12); c++) {
                            _newRow5.push(dataS[r][c-3])
                            const _output = Number(dataS[r][c-3])
                            if(c<33) {
                                _newRow5[8]+=_output
                            }

                            totalQtySensor += _output

                            if(_output==0) {
                                _newRow6.push('')
                                _newRow7.push('')
                                _newRow8.push('')
                            } else {
                                let _totalLastPlan = 0
                                for(let d=c;d>=9;d--) {
                                    if(_newRow4[d]) {
                                        _totalLastPlan = Number(_newRow4[d])
                                        break;
                                    }
                                }
                                _newRow6.push(totalQtySensor)
                                _newRow7.push(_output-_newRow3[c])
                                _newRow8.push(totalQtySensor-_totalLastPlan)
                            }

                        }
                        break;
                    }
                }
                _newRow7[8] = numeral(_newRow5[8] - _newRow3[5]).format(',')
                inputSS.push(_newRow5)
                inputSS.push(_newRow6)
                inputSS.push(_newRow7)
                inputSS.push(_newRow8)

            } else {

                let ChangeModelLabel = ''
                let ChangeModelTime = ''
                if(data[i][1]) {
                    ChangeModelLabel = 'CHANGE MODEL'
                    ChangeModelTime = data[i][2]
                } else {
                    ChangeModelLabel = ''
                    ChangeModelTime = ''
                }

                _newRow1.push(nomorUrut)
                _newRow1.push('')
                _newRow1.push(ChangeModelLabel)
                _newRow1.push('')
                _newRow1.push('')
                _newRow1.push('')
                _newRow1.push(ChangeModelTime)
                _newRow1.push('PLAN')
                _newRow1.push(0)

                if(data[i][1]) {
                    for(let c=9; c<(9+12+12+12); c++) {
                        if(inputSS[1][c] == Number(data[0][(c-3)])) {
                            if(data[i][c-3] >0) {
                                _newRow1.push(1)
                                _newRow1[8]+=1
                                inputSS[2][c] = 'C1'
                            } else {
                                _newRow1.push('')
                            }
                        }
                    }
                }

                _newRow2.push('')
                _newRow2.push('')
                _newRow2.push('')
                _newRow2.push('')
                _newRow2.push('')
                _newRow2.push('')
                _newRow2.push('')
                _newRow2.push('Actual')
                _newRow2.push(0)

                inputSS.push(_newRow1)
                inputSS.push(_newRow2)
            }

            nomorUrut++
        }
        if(totalRowsMatrix>0) {
            keikaku_prodplan_sso.setData(inputSS)
        }
    }

    function keikakuShowLineModal() {
        $("#keikakuLineListModal").modal('show')
    }

    function keikakuBtnOpenOnClick() {
        let tableku2 = keikakuLineTbl.getElementsByTagName("tbody")[0];
        let ttlrows = tableku2.rows.length
        for (let i = 0; i<ttlrows; i++)
        {
            if(tableku2.rows[i].cells[0].getElementsByTagName('input')[0].checked) {
                const _line = tableku2.rows[i].cells[1].innerText
                const endPoint = '<?=base_url('Keikaku')?>' + '?line=' + btoa(_line) + '&date=' + keikaku_date_input.value
                window.open(endPoint)
            }
        }
    }

    var keikaku_downtime_sso = jspreadsheet(keikaku_downtime_spreadsheet, {
        data : [
            ['19', '7', '', '', '', '', '', '', '', ''],
            ['20', '8', '', '', '', '', '', '', '', ''],
            ['21', '9', '', '', '', '', '', '', '', ''],
            ['22', '10', '', '', '', '', '', '', '', ''],
            ['23', '11', '', '', '', '', '', '', '', ''],
            ['0', '12', '', '', '', '', '', '', '', ''],
            ['1', '13', '', '', '', '', '', '', '', ''],
            ['2', '14', '', '', '', '', '', '', '', ''],
            ['3', '15', '', '', '', '', '', '', '', ''],
            ['4', '16', '', '', '', '', '', '', '', ''],
            ['5', '17', '', '', '', '', '', '', '', ''],
            ['6', '18', '', '', '', '', '', '', '', ''],
            ['7', '19', '', '', '', '', '', '', '', ''],
            ['Sub Total', '', '=C1+C2+C3+C4+C5+C6+C7+C8+C9+C10+C11+C12+C13', '', '=E1+E2+E3+E4+E5+E6+E7+E8+E9+E10+E11+E12+E13', '', '=G1+G2+G3+G4+G5+G6+G7+G8+G9+G10+G11+G12+G13', '', '=I1+I2+I3+I4+I5+I6+I7+I8+I9+I10+I11+I12+I13', '','=K1+K2+K3+K4+K5+K6+K7+K8+K9+K10+K11+K12+K13','=L1+L2+L3+L4+L5+L6+L7+L8+L9+L10+L11+L12+L13','=M1+M2+M3+M4+M5+M6+M7+M8+M9+M10+M11+M12+M13'],
            ['Total', '', '=C14+E14+G14+I14+K14+L14+M14', '', '', '', '', '', '', ''],
        ],
        columns : [
            {
                title : '_',
                type : 'text',
                readOnly : true,
            },
            {
                title : '_',
                type : 'text',
                readOnly : true,
            },
            {
                title : 'Duration',
                type : 'numeric',
                width:65,
                mask: '#,##.00',
            },
            {
                title : 'Remark',
                type : 'text',
                width:150,
            },
            {
                title : 'Duration',
                type : 'numeric',
                width:65,
                mask: '#,##.00',
            },
            {
                title : 'Remark',
                type : 'text',
                width:150,
            },
            {
                title : 'Duration',
                type : 'numeric',
                width:65,
                mask: '#,##.00',
            },
            {
                title : 'Remark',
                type : 'text',
                width:150,
            },
            {
                title : 'Duration',
                type : 'numeric',
                width:65,
                mask: '#,##.00',
            },
            {
                title : 'Remark',
                type : 'text',
                width:150,
            },
            {
                title : 'Duration',
                type : 'numeric',
                width:100,
                mask: '#,##.00',
            },
            {
                title : 'Duration',
                type : 'numeric',
                width:160,
                mask: '#,##.00',
            },
            {
                title : 'Duration',
                type : 'numeric',
                width:160,
                mask: '#,##.00',
            },
        ],
        allowInsertColumn : false,
        allowDeleteColumn : false,
        allowRenameColumn : false,
        allowDeleteRow : false,
        rowDrag:false,

        nestedHeaders : [
            [
                {
                    title : 'Jam',
                    colspan : '1',
                },
                {
                    title : '_',
                    colspan : '1'
                },
                {
                    title : 'M/C Trouble',
                    colspan : '2',
                },
                {
                    title : 'Change model',
                    colspan : '2',
                },
                {
                    title : '4M (New model )',
                    colspan : '2',
                },
                {
                    title : 'Other',
                    colspan : '2',
                },
                {
                    title : 'Maintenance',
                    colspan : '1',
                },
                {
                    title : 'Not Production 15 minutes',
                    colspan : '1',
                },
                {
                    title : 'Not Production No Plan',
                    colspan : '1',
                },
            ],
        ],
        mergeCells : {
            A14 : [2,1],
            A15 : [2,1],
            C15 : [11,1],
        },
        updateTable: function(el, cell, x, y, source, value, id) {
            if(y===13 ) {
                cell.classList.add('readonly');
            }
            if(y===14) {
                cell.classList.add('readonly');
            }
        }
    })

    function keikaku_btn_save_downtime_eC(pThis) {

        if(keikaku_line_input.value === '-') {
            alertify.warning(`Line is required`)
            keikaku_line_input.focus()
            return
        }

        let inputSS = keikaku_downtime_sso.getData()
        const inputSSCount = inputSS.length

        let dataDetail = []
        let columnTime = keikaku_shift_input.value === 'M' ? 1 : 0
        for(let i=0; i<inputSSCount-2;i++) {
            // machine trouble
            if(inputSS[i][2].length > 0) {
                dataDetail.push({
                    runningAt : inputSS[i][columnTime].trim(),
                    reqMinutes : inputSS[i][2],
                    downTimeCode : 2,
                    remark : inputSS[i][3]
                })
            }
            // change model
            if(inputSS[i][4].length > 0) {
                dataDetail.push({
                    runningAt : inputSS[i][columnTime].trim(),
                    reqMinutes : inputSS[i][4],
                    downTimeCode : 3,
                    remark : inputSS[i][5]
                })
            }
            // new model
            if(inputSS[i][6].length > 0) {
                dataDetail.push({
                    runningAt : inputSS[i][columnTime].trim(),
                    reqMinutes : inputSS[i][6],
                    downTimeCode : 4,
                    remark : inputSS[i][7]
                })
            }
            // other
            if(inputSS[i][8].length > 0) {
                dataDetail.push({
                    runningAt : inputSS[i][columnTime].trim(),
                    reqMinutes : inputSS[i][8],
                    downTimeCode : 8,
                    remark : inputSS[i][9]
                })
            }
            // maintenance
            if(inputSS[i][10].length > 0) {
                dataDetail.push({
                    runningAt : inputSS[i][columnTime].trim(),
                    reqMinutes : inputSS[i][10],
                    downTimeCode : 1,
                    remark : ''
                })
            }
            // not production 15 min
            if(inputSS[i][11].length > 0) {
                dataDetail.push({
                    runningAt : inputSS[i][columnTime].trim(),
                    reqMinutes : inputSS[i][11],
                    downTimeCode : 5,
                    remark : ''
                })
            }
            // not production no plan
            if(inputSS[i][12].length > 0) {
                dataDetail.push({
                    runningAt : inputSS[i][columnTime].trim(),
                    reqMinutes : inputSS[i][12],
                    downTimeCode : 7,
                    remark : ''
                })
            }
        }

        const data = {
            lineCode : keikaku_line_input.value,
            productionDate : keikaku_date_input.value,
            shift : keikaku_shift_input.value,
            user_id : uidnya,
            detail : dataDetail,
        }

        if(!confirm(`Are you sure ?`)) {
            return
        }
        const containerInfo = document.getElementById('keikaku-div-alert')
        pThis.disabled = true
        $.ajax({
            type: "POST",
            url: "<?=$_ENV['APP_INTERNAL_API']?>keikaku/downtime",
            data: JSON.stringify(data),
            dataType: "json",
            success: function (response) {
                pThis.disabled = false
                containerInfo.innerHTML = ``
                alertify.success(response.message)
            }, error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow)
                pThis.disabled = false

                const respon = Object.keys(xhr.responseJSON)

                let msg = ''
                for (const item of respon) {
                    msg += `<p>${xhr.responseJSON[item]}</p>`
                }
                containerInfo.innerHTML = `<div class="alert alert-warning alert-dismissible fade show" role="alert">
                ${msg}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`
            }
        });
    }

    function keikakuBtnEditActualOnClick(pThis) {
        let qty = numeral(keikakuEditOutput.value).value()

        if(qty<0) {
            alertify.warning('Quantity is required')
            keikakuEditOutput.focus()
            return
        }

        const data = {
            line : keikaku_line_input.value,
            job : keikakuEditWO.value,
            side : keikakuEditSide.value,
            productionDate : keikakuEditDate.value,
            runningAtTime : keikakuEditHour.value,
            quantity : qty,
            user_id : uidnya,
            XCoordinate : keikakuEditXCoordinate.value
        }
        pThis.disabled = true
        $.ajax({
            type: "POST",
            url: "<?=$_ENV['APP_INTERNAL_API']?>keikaku/output",
            data: data,
            dataType: "json",
            success: function (response) {
                pThis.disabled = false
                alertify.success(response.message)
                $('#keikakuEditActualModal').modal('hide')
                keikaku_btn_run_prodplan_eC(keikaku_btn_run_prodplan)
                keikaku_load_data()
            }, error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow)
                pThis.disabled = false

                const respon = Object.keys(xhr.responseJSON)

                let msg = ''
                for (const item of respon) {
                    msg += `<p>${xhr.responseJSON[item]}</p>`
                }
                keikakuEditAlert.innerHTML = `<div class="alert alert-warning alert-dismissible fade show" role="alert">
                ${msg}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`
            }
        });
    }

    function keikakuEditOutputOnKeyPress(e) {
        if(e.key === 'Enter') {
            keikakuBtnEditActual.focus()
        }
    }

    function keikakuGetDownTime() {
        const data = {
            lineCode : keikaku_line_input.value,
            productionDate : keikaku_date_input.value,
            shift : keikaku_shift_input.value,
        }
        $.ajax({
            type: "GET",
            url: "<?=$_ENV['APP_INTERNAL_API']?>keikaku/downtime",
            data: data,
            dataType: "json",
            success: function (response) {
                let columnTime = keikaku_shift_input.value === 'M' ? 1 : 0
                let inputSS = keikaku_downtime_sso.getData()
                let dataLength = inputSS.length -2
                let responseDataLength = response.data.length

                for(let i=0; i < dataLength; i++) {
                    keikaku_downtime_sso.setValue('C'+(i+1), '', true)
                    keikaku_downtime_sso.setValue('D'+(i+1), '', true)
                    keikaku_downtime_sso.setValue('E'+(i+1), '', true)
                    keikaku_downtime_sso.setValue('F'+(i+1), '', true)
                    keikaku_downtime_sso.setValue('G'+(i+1), '', true)
                    keikaku_downtime_sso.setValue('H'+(i+1), '', true)
                    keikaku_downtime_sso.setValue('I'+(i+1), '', true)
                    keikaku_downtime_sso.setValue('J'+(i+1), '', true)
                    keikaku_downtime_sso.setValue('K'+(i+1), '', true)
                    keikaku_downtime_sso.setValue('L'+(i+1), '', true)
                    keikaku_downtime_sso.setValue('M'+(i+1), '', true)
                }

                for(let i=0; i < dataLength; i++) {
                    let _itemTime = inputSS[i][columnTime]
                    for(let s=0;s<responseDataLength; s++) {
                        const _itemTime2 = response.data[s].running_at.split(' ')
                        const _jam = _itemTime2[1].split(':')
                        const _jam2 = numeral(_jam[0]).value()
                        if(_itemTime == _jam2) {
                            if(response.data[s].downtime_code == 2) {
                                keikaku_downtime_sso.setValue('C'+(i+1), response.data[s].req_minutes, true)
                                keikaku_downtime_sso.setValue('D'+(i+1), response.data[s].remark, true)
                            } else if(response.data[s].downtime_code == 3) {
                                keikaku_downtime_sso.setValue('E'+(i+1), response.data[s].req_minutes, true)
                                keikaku_downtime_sso.setValue('F'+(i+1), response.data[s].remark, true)
                            } else if(response.data[s].downtime_code == 4) {
                                keikaku_downtime_sso.setValue('G'+(i+1), response.data[s].req_minutes, true)
                                keikaku_downtime_sso.setValue('H'+(i+1), response.data[s].remark, true)
                            } else if(response.data[s].downtime_code == 8) {
                                keikaku_downtime_sso.setValue('I'+(i+1), response.data[s].req_minutes, true)
                                keikaku_downtime_sso.setValue('J'+(i+1), response.data[s].remark, true)
                            } else if(response.data[s].downtime_code == 1) {
                                keikaku_downtime_sso.setValue('K'+(i+1), response.data[s].req_minutes, true)
                            } else if(response.data[s].downtime_code == 5) {
                                keikaku_downtime_sso.setValue('L'+(i+1), response.data[s].req_minutes, true)
                            } else if(response.data[s].downtime_code == 7) {
                                keikaku_downtime_sso.setValue('M'+(i+1), response.data[s].req_minutes, true)
                            }
                            break;
                        }
                    }
                }
            }
        });
    }

    function keikaku_shift_input_on_change() {
        keikakuGetDownTime()
    }

</script>
