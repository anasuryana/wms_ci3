<div style="padding: 10px" >
	<div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Line</label>
                    <select class="form-select" id="keikaku_rpt_line_input" required>
                        <option value="-">-</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Production Date</label>
                    <input type="text" class="form-control" id="keikaku_rpt_date_input" readonly>
                </div>
            </div>
            <div class="col-md-4">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" type="button" id="keikaku_rpt_btn_gen" onclick="keikaku_rpt_btn_gen_e_click(this)">Generate</button>
                    <!-- <button class="btn btn-success" type="button" id="keikaku_rpt_btn_export" onclick="keikaku_rpt_btn_export_e_click(this)">Export to <i class="fas fa-file-excel"></i></button> -->
                    <div class="btn-group btn-group-sm dropend" role="group">
                        <button title="Export to" class="btn btn-outline-primary dropdown-toggle" type="button" id="keikaku_rpt_btn_export" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-file-export"></i></button>
                        <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <li><a class="dropdown-item" href="#" id="keikaku_rpt_btn_summary" onclick="keikaku_rpt_btn_summary_e_click()"><i class="fas fa-file-excel text-success"></i> Summary</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-chart-simple"></i>
                    </div>
                    <div class="card-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12 mb-1" id="keikaku_rpt_div_chart_qty">
                                    <div  style="margin: auto;height: 77vh; width: 70vw;">
                                        <canvas id="keikaku_rpt_chart_qty"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-1 table-responsive">
                                    <div id="keikaku_rpt_qty_spreadsheet"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-chart-simple"></i>
                    </div>
                    <div class="card-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12 mb-1" id="keikaku_rpt_div_chart_time">
                                    <div  style="margin: auto;height: 77vh; width: 70vw;">
                                        <canvas id="keikaku_rpt_chart_time"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-1 table-responsive">
                                    <div id="keikaku_rpt_time_spreadsheet"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="keikaku_rpt_summary_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5">Report Filter</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12" id="keikaku_rpt_summart_Alert">

                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">From</label>
                        <input type="text" class="form-control" id="keikaku_rpt_date_from" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">To</label>
                        <input type="text" class="form-control" id="keikaku_rpt_date_to" readonly>
                    </div>
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="keikakuRptBtnExportSummary" onclick="keikakuRptBtnExportSummary(this)">Export</button>
      </div>
    </div>
  </div>
</div>
<script>
    function keikaku_rpt_randomNum () {
        return Math.floor(Math.random() * (235 - 52 + 1) + 52)
    }

    function keikaku_rpt_randomRGB() {
        return `rgb(${keikaku_rpt_randomNum()}, ${keikaku_rpt_randomNum()}, ${keikaku_rpt_randomNum()})`
    }
    $("#keikaku_rpt_date_input").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });
    $("#keikaku_rpt_date_input").datepicker('update', new Date());
    $("#keikaku_rpt_date_from").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });
    $("#keikaku_rpt_date_from").datepicker('update', new Date());
    $("#keikaku_rpt_date_to").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });
    $("#keikaku_rpt_date_to").datepicker('update', new Date());

    function keikaku_rpt_load_line_code() {
        $.ajax({
            type: "GET",
            url: "<?php echo $_ENV['APP_INTERNAL_API'] ?>process-master/line-code",
            dataType: "json",
            success: function (response) {
                keikaku_rpt_line_input.innerHTML = `<option value="-">-</option>`
                let inputs = '<option value="-">-</option>';
                response.data.forEach((arrayItem) => {
                    inputs += `<option value="${arrayItem['line_code']}">${arrayItem['line_code']}</option>`
                })
                keikaku_rpt_line_input.innerHTML = inputs

            }
        });
    }

    keikaku_rpt_load_line_code()

    var keikaku_rpt_qty_sso = jspreadsheet(keikaku_rpt_qty_spreadsheet, {
        columns : [
            {
                readOnly : true,
                type : 'text',
                width:85,
                align : 'left',
            }, ...Array.from({length: 25}, (_, i) => Object.create({
                    type : 'numeric',
                    readOnly : true,
                    }))
        ],
        allowInsertColumn : false,
        allowDeleteColumn : false,
        allowRenameColumn : false,
        allowDeleteRow : false,
        rowDrag:false,
        data: [
            ['Hour', ...Array.from({length: 17}, (_, i) => i + 7), ...Array.from({length: 8}, (_, i) => i)],
            ['QTY Plan',...Array.from({length: 17}, (_, i) => 0), ...Array.from({length: 8}, (_, i) => 0) ],
            ['QTY Actual',...Array.from({length: 17}, (_, i) => 0), ...Array.from({length: 8}, (_, i) => 0) ],
            ['Progress', '0', '=INT(C3/C2*100)' , '=INT(D3/D2*100)' , '=INT(E3/E2*100)' , '=INT(F3/F2*100)' , '=INT(G3/G2*100)' , '=INT(H3/H2*100)', '=INT(I3/I2*100)', '=INT(J3/J2*100)', '=INT(K3/K2*100)', '=INT(L3/L2*100)', '=INT(M3/M2*100)', '=INT(N3/N2*100)', '=INT(O3/O2*100)', '=INT(P3/P2*100)', '=INT(Q3/Q2*100)', '=INT(R3/R2*100)', '=INT(S3/S2*100)', '=INT(T3/T2*100)', '=INT(U3/U2*100)', '=INT(V3/V2*100)', '=INT(W3/W2*100)', '=INT(X3/X2*100)', '=INT(Y3/Y2*100)', '=INT(Z3/Z2*100)'],
            ['Progress.', '0', '=IF(AND(C2>0,C3=0),0,C3-C2)' , '=IF(AND(D2>0,D3=0),0,D3-D2)', '=IF(AND(E2>0,E3=0),0,E3-E2)', '=IF(AND(F2>0,F3=0),0,F3-F2)', '=IF(AND(G2>0,G3=0),0,G3-G2)', '=IF(AND(H2>0,H3=0),0,H3-H2)', '=IF(AND(I2>0,I3=0),0,I3-I2)', '=IF(AND(J2>0,J3=0),0,J3-J2)', '=IF(AND(K2>0,K3=0),0,K3-K2)', '=IF(AND(L2>0,L3=0),0,L3-L2)', '=IF(AND(M2>0,M3=0),0,M3-M2)', '=IF(AND(N2>0,N3=0),0,N3-N2)', '=IF(AND(O2>0,O3=0),0,O3-O2)', '=IF(AND(P2>0,P3=0),0,P3-P2)', '=IF(AND(Q2>0,Q3=0),0,Q3-Q2)', '=IF(AND(R2>0,R3=0),0,R3-R2)',  '=IF(AND(S2>0,S3=0),0,S3-S2)', '=IF(AND(T2>0,T3=0),0,T3-T2)', '=IF(AND(U2>0,U3=0),0,U3-U2)', '=IF(AND(V2>0,V3=0),0,V3-V2)', '=IF(AND(W2>0,W3=0),0,W3-W2)', '=IF(AND(X2>0,X3=0),0,X3-X2)', '=IF(AND(Y2>0,Y3=0),0,Y3-Y2)', '=IF(AND(Z2>0,Z3=0),0,Z3-Z2)'],
        ],
        copyCompatibility:true,
        columnSorting:false,
        allowInsertRow : false,
        tableOverflow:true,
        freezeColumns: 1,
        minDimensions: [50,5],
        tableWidth: '1000px',
    });

    var keikaku_rpt_time_sso = jspreadsheet(keikaku_rpt_time_spreadsheet, {
        columns : [
            {
                readOnly : true,
                type : 'text',
                width:85,
                align : 'left',
            },...Array.from({length: 25}, (_, i) => Object.create({
                    type : 'numeric',
                    readOnly : true,
                    }))
        ],
        allowInsertColumn : false,
        allowDeleteColumn : false,
        allowRenameColumn : false,
        allowDeleteRow : false,
        rowDrag:false,
        data: [
            ['Hour', ...Array.from({length: 17}, (_, i) => i + 7), ...Array.from({length: 8}, (_, i) => i)],
            ['QTY Plan',...Array.from({length: 17}, (_, i) => 0), ...Array.from({length: 8}, (_, i) => 0) ],
            ['QTY Actual',...Array.from({length: 17}, (_, i) => 0), ...Array.from({length: 8}, (_, i) => 0) ],
            ['Progress', '0', '=INT(C3/C2*100)', '=INT(D3/D2*100)' , '=INT(E3/E2*100)' , '=INT(F3/F2*100)' , '=INT(G3/G2*100)' , '=INT(H3/H2*100)', '=INT(I3/I2*100)', '=INT(J3/J2*100)', '=INT(K3/K2*100)', '=INT(L3/L2*100)', '=INT(M3/M2*100)', '=INT(N3/N2*100)', '=INT(O3/O2*100)', '=INT(P3/P2*100)', '=INT(Q3/Q2*100)', '=INT(R3/R2*100)', '=INT(S3/S2*100)', '=INT(T3/T2*100)', '=INT(U3/U2*100)', '=INT(V3/V2*100)', '=INT(W3/W2*100)', '=INT(X3/X2*100)', '=INT(Y3/Y2*100)', '=INT(Z3/Z2*100)'],
            ['Progress.',0,'=IF(C3-C2>0,0,C3-C2)' , '=IF(D3-D2>0,0,D3-D2)', '=IF(E3-E2>0,0,E3-E2)', '=IF(F3-F2>0,0,F3-F2)', '=IF(G3-G2>0,0,G3-G2)', '=IF(H3-H2>0,0,H3-H2)', '=IF(I3-I2>0,0,I3-I2)', '=IF(J3-J2>0,0,J3-J2)', '=IF(K3-K2>0,0,K3-K2)', '=IF(L3-L2>0,0,L3-L2)', '=IF(M3-M2>0,0,M3-M2)', '=IF(N3-N2>0,0,N3-N2)', '=IF(O3-O2>0,0,O3-O2)', '=IF(P3-P2>0,0,P3-P2)', '=IF(Q3-Q2>0,0,Q3-Q2)', '=IF(R3-R2>0,0,R3-R2)',  '=IF(S3-S2>0,0,S3-S2)', '=IF(T3-T2>0,0,T3-T2)', '=IF(U3-U2>0,0,U3-U2)', '=IF(V3-V2>0,0,V3-V2)', '=IF(W3-W2>0,0,W3-W2)', '=IF(X3-X2>0,0,X3-X2)', '=IF(Y3-Y2>0,0,Y3-Y2)', '=IF(Z3-Z2>0,0,Z3-Z2)' ],
        ],
        copyCompatibility:true,
        columnSorting:false,
        allowInsertRow : false,
        tableOverflow:true,
        freezeColumns: 1,
        minDimensions: [50,5],
        tableWidth: '1000px',
    });

    var ctx = document.getElementById('keikaku_rpt_chart_qty');
    var labels = [...Array.from({length: 17}, (_, i) => i + 7),...Array.from({length: 8}, (_, i) => i)]
    var data = {
        labels: labels,
        datasets: [
            {
                label: 'Qty Plan',
                data: [20,33,40],
                borderColor: `rgb(0, 71, 171)`,
                tension: 0.1,
                type: 'line',
            },
            {
                label: 'Qty Actual',
                data: [20,30,22],
                backgroundColor: `rgb(124,252,0)`,
                borderColor: `rgb(0, 71, 171)`,
            },
        ]
    };
    var config = {
        type: 'bar',
        data: data,
        options: {
            maintainAspectRatio: true,
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Production Quantity'
                }
            }
        },
    };
    var myChart = new Chart(ctx, config);


    ////
    var ctx2 = document.getElementById('keikaku_rpt_chart_time');
    var labels2 = [...Array.from({length: 17}, (_, i) => i + 7),...Array.from({length: 8}, (_, i) => i)]
    var data2 = {
        labels: labels2,
        datasets: [

            {
                label: 'Time Progress',
                data: [0,-3,-4],
                backgroundColor: `rgb(255, 0, 0)`,
                tension: 0.3,
            },
            {
                label: 'Time Plan',
                data: [20,33,40],
                borderColor: `rgb(0, 71, 171)`,
                tension: 0.1,
                type: 'line',
            },
            {
                label: 'Time Actual',
                data: [20,30,22],
                backgroundColor: `rgb(8, 181, 118)`,
                borderColor: `rgb(0, 71, 171)`,
            },
        ]
    };
    var config2 = {
        type: 'bar',
        data: data2,
        options: {
            maintainAspectRatio: true,
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Production Time'
                }
            },
            scales: {
                x: {
                    stacked: true,
                },
                y: {
                    stacked: true
                }
            }
        },
    };

    var myChart2 = new Chart(ctx2, config2);

    function keikaku_rpt_btn_gen_e_click(pThis) {

        const data = {
            line_code: keikaku_rpt_line_input.value,
            production_date: keikaku_rpt_date_input.value,
        }
        pThis.disabled = true
        $.ajax({
            type: "GET",
            url: "<?php echo $_ENV['APP_INTERNAL_API'] ?>keikaku/production-plan",
            data: data,
            dataType: "JSON",
            success: function (response) {
                pThis.disabled = false
                let plan = [0]
                let actual = [0]
                let planTime = [0]
                let actualTime = [0]
                let progressTime = [0]
                let prodPlanLength = response.asProdplan.length
                let outputLength = response.dataSensor.length
                let timeLength = response.asMatrix.length
                let _totalPlan = 0
                let _totalActual = 0
                let _totalPlanTime = 0
                let _totalActualTime = 0
                let _totalProgressTime = 0
                for(let i=6; i<=29; i++) {

                    for(let r=4;r<prodPlanLength;r++) {
                        if(response.asProdplan[r][0]) {
                            _totalPlan += Number(response.asProdplan[r][i])
                        }
                    }

                    for(let r=0;r<outputLength;r++) {
                        _totalActual += Number(response.dataSensor[r][i])
                        _totalActualTime += (response.dataSensor[r][5] *Number(response.dataSensor[r][i]) )
                    }

                    for(let r=0;r<timeLength;r++) {
                        if(response.asMatrix[r][0]) {
                            _totalPlanTime += Number(response.asMatrix[r][i])
                        }
                    }

                    plan.push(_totalPlan)
                    planTime.push(_totalPlanTime.toFixed(2))
                    actual.push(_totalActual)
                    actualTime.push(_totalActualTime.toFixed(2))

                    _totalProgressTime = _totalActualTime - _totalPlanTime > 0 ? 0 : _totalActualTime - _totalPlanTime
                    progressTime.push(_totalProgressTime)

                    keikaku_rpt_qty_sso.setValueFromCoords(i-4, 1, _totalPlan, true)
                    keikaku_rpt_qty_sso.setValueFromCoords(i-4, 2, _totalActual, true)
                    keikaku_rpt_time_sso.setValueFromCoords(i-4, 1, _totalPlanTime.toFixed(2), true)
                    keikaku_rpt_time_sso.setValueFromCoords(i-4, 2, _totalActualTime.toFixed(2), true)
                }
                myChart.data.datasets = [
                    {
                        label: 'Qty Plan',
                        data: plan,
                        borderColor: `rgb(0, 71, 171)`,
                        tension: 0.1,
                        type: 'line',
                    },
                    {
                        label: 'Qty Actual',
                        data: actual,
                        backgroundColor: `rgb(124,252,0)`,
                        borderColor: `rgb(0, 71, 171)`,
                    },
                ]
                myChart.update()

                myChart2.data.datasets = [
                    {
                        label: 'Time Progress',
                        data: progressTime,
                        backgroundColor: `rgb(255, 0, 0)`,
                        tension: 0.3,
                    },
                    {
                        label: 'Time Plan',
                        data: planTime,
                        borderColor: `rgb(0, 71, 171)`,
                        tension: 0.1,
                        type: 'line',
                    },
                    {
                        label: 'Time Actual',
                        data: actualTime,
                        backgroundColor: `rgb(8, 181, 118)`,
                        borderColor: `rgb(0, 71, 171)`,
                    },
                ]
                myChart2.update()
            }, error: function(xhr, xopt, xthrow) {
                pThis.disabled = false
                alertify.error(xthrow)
            }
        });
    }

    function keikaku_rpt_btn_summary_e_click() {
        $("#keikaku_rpt_summary_modal").modal('show')
    }

    function keikakuRptBtnExportSummary(pThis) {
        pThis.disabled = true
        $.ajax({
            type: "GET",
            url: `<?php echo $_ENV['APP_INTERNAL_API'] ?>report/keikaku`,
            data: { dateFrom : keikaku_rpt_date_from.value , dateTo:keikaku_rpt_date_to.value },
            success: function (response) {
                pThis.disabled = false
                let waktuSekarang = moment().format('YYYY MMM DD, h_mm')
                const blob = new Blob([response], { type: "application/vnd.ms-excel" })
                const fileName = `keikaku from ${keikaku_rpt_date_from.value} to ${keikaku_rpt_date_to.value}.xlsx`
                saveAs(blob, fileName)

                alertify.success('Done')
            },
            xhr: function () {
                const xhr = new XMLHttpRequest()
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 2) {
                        pThis.disabled = false
                        if (xhr.status == 200) {
                            xhr.responseType = "blob";
                        } else {
                            xhr.responseType = "text";
                        }
                    }
                }
                return xhr
            },
        })
    }
</script>