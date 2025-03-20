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
    .keikakuGrayColorLight {
        background-color : #f3f3f3 !important
    }
    .keikakuBorderTop {
        border-top-color: #000000 !important;
        border-width:medium !important;
        border-style:solid;
    }
    .keikakuBorderRight {
        border-right-color: #000000 !important;
        border-width:medium !important;
        border-style:solid !important;
    }
    .keikakuBorderLeft {
        border-left-color: #000000 !important;
        border-width:medium !important;
        border-style:solid !important;
    }
</style>
<div style="padding: 5px" >
	<div class="container-fluid">
        <div class="row" id="keikaku_stack1">
            <div class="col-md-4">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Line</label>
                    <select class="form-select" id="keikaku_line_input" required onchange="keikaku_line_input_on_change()">
                        <option value="-">-</option>
                    </select>
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-bars"></i></button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="keikakuShowLineModal()">Open Multiple Line</a></li>
                        <li><a class="dropdown-item" href="#" id="keikaku_rpt_btn_summary" onclick="keikaku_rpt_btn_summary_e_click()"><i class="fas fa-file-excel text-success"></i> Summary</a></li>
                        <li><a class="dropdown-item" href="#" id="keikaku_rpt_btn_prod_output" onclick="keikaku_rpt_btn_prod_output_e_click()"><i class="fas fa-file-excel text-success"></i> Production Output & Downtime</a></li>
                        <li><a class="dropdown-item" href="#" id="keikaku_rpt_btn_wo_history" onclick="keikaku_rpt_btn_wo_history_e_click()">Job History</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Production Date</label>
                    <input type="text" class="form-control" id="keikaku_date_input" readonly onchange="keikaku_date_input_on_change()">
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text"><i class="fas fa-user-pen"></i></label>
                    <input type="text" class="form-control" id="keikaku_user_first_active" readonly title="first open by">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1" id="keikaku-div-alert">

            </div>
        </div>
        <div class="row">
            <div class="col-md-12" id="keikakuEditAlert">

            </div>
        </div>
        <div class="row">
            <div class="col-md-12" id="keikakuEditINPUTHWAlert">

            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
            <ul class="nav nav-tabs nav-pills" id="keikaku_main_tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-5" id="keikaku_asprova-tab" data-bs-toggle="tab" data-bs-target="#keikaku_tab_asprova" type="button" role="tab" aria-controls="home" aria-selected="true">Asprova</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link active rounded-5" id="keikaku_home-tab" data-bs-toggle="tab" data-bs-target="#keikaku_tabRM" type="button" role="tab" aria-controls="home" aria-selected="true" onclick="keikaku_data_sheet_on_click()">Data</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-5" id="keikaku_checksim-tab" data-bs-toggle="tab" data-bs-target="#keikaku_tab_checksim" type="button" role="tab" aria-controls="home" aria-selected="true">Calculation</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-5" id="keikaku_prodplan-tab" data-bs-toggle="tab" data-bs-target="#keikaku_tab_prodplan" type="button" role="tab" aria-controls="home" aria-selected="true" onclick="keikaku_prodplan_sheet_on_click()"><i class="fas fa-chart-gantt"></i> Production Plan</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-5" id="keikaku_downtime-tab" data-bs-toggle="tab" data-bs-target="#keikaku_tab_downtime" type="button" role="tab" aria-controls="home" aria-selected="true"><i class="fas fa-down-long text-warning"></i> Down Time</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-5" id="keikaku_report-tab" data-bs-toggle="tab" data-bs-target="#keikaku_tab_report" type="button" role="tab" aria-controls="home" aria-selected="true">Report-GRAPH</button>
                </li>
                <li class="nav-item" role="presentation">
                    <h4><span class="nav-link badge text-bg-info" id="keikaku_info_tab" data-bs-toggle="tab" type="button" role="tab" aria-selected="true" disabled>...</span></h4>
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
                    <div class="tab-pane fade show active p-1" id="keikaku_tabRM" role="tabpanel" aria-labelledby="home-tab">
                        <div class="row" id="keikaku_stack2">
                            <div class="col-md-6 mb-1">
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" id="keikaku_btn_new" title="New" onclick="keikaku_btn_new_eC()"><i class="fas fa-file"></i></button>
                                    <button class="btn btn-outline-primary" id="keikaku_btn_save" title="Save" onclick="keikaku_btn_save_eC(this)"><i class="fas fa-save"></i></button>
                                </div>
                            </div>
                            <div class="col-md-6 mb-1 text-end">
                                <div class="btn-group btn-group-sm">
                                    <div class="btn-group btn-group-sm dropend" role="group">
                                        <button title="Functions" class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-bars"></i></button>
                                        <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                            <li>
                                                <h6 class="dropdown-header">Coloring Selected Cell</h6>
                                            </li>
                                            <li><a class="dropdown-item" href="#" onclick="keikaku_btn_set_blue()"><span class="fa-stack" style="vertical-align: top;">
                                                        <i class="fa-solid fa-circle fa-stack-2x" style="color: #cfcccc"></i>
                                                        <i class="fa-solid fa-flag fa-stack-1x fa-inverse" style="color: #26f0fe"></i>
                                                        </span> Blue</a></li>
                                            <li><a class="dropdown-item" href="#" onclick="keikaku_btn_set_yellow()">
                                            <span class="fa-stack" style="vertical-align: top;">
                                                        <i class="fa-solid fa-circle fa-stack-2x" style="color: #cfcccc"></i>
                                                        <i class="fa-solid fa-flag fa-stack-1x fa-inverse" style="color: #fafe0d"></i>
                                                        </span>
                                                Yellow
                                            </a>
                                            </li>
                                            <li><a class="dropdown-item" href="#" onclick="keikaku_btn_set_white()">
                                                <span class="fa-stack" style="vertical-align: top;">
                                                        <i class="fa-solid fa-circle fa-stack-2x" style="color: #cfcccc"></i>
                                                        <i class="fa-solid fa-flag fa-stack-1x fa-inverse"></i>
                                                        </span> White</a>
                                            </li>
                                            <li>
                                                <h6 class="dropdown-header">Mode</h6>
                                            </li>
                                            <li><a class="dropdown-item" href="#" onclick="keikaku_btn_mode(this)">User Mode</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-1 table-responsive">
                                <div id="keikaku_data_spreadsheet"></div>
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
                            <div class="col-md-6 text-end">
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" id="keikaku_btn_trigger_template" title="Template" onclick="keikaku_btn_trigger_template_eClick()">Template</button>
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
                            <div class="col-md-8">
                                <div class="input-group input-group-sm mb-1">
                                    <label class="input-group-text"><i class="fas fa-asterisk"></i></label>
                                    <input type="text" class="form-control" id="keikaku_downtime_remark" readonly title="Formula Bar">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-1 table-responsive">
                                <div id="keikaku_downtime_spreadsheet"></div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade p-1" id="keikaku_tab_report" role="tabpanel">
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
                        <div class="row">
                            <div class="col-md-12 mb-1">
                                <div class="card">
                                    <div class="card-header">
                                        <i class="fas fa-table"></i>
                                    </div>
                                    <div class="card-body">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-12 mb-1">
                                                    <div class="table-responsive" id="keikaku_rpt_tbl_div">
                                                        <table id="keikaku_rpt_tbl" class="table table-bordered table-sm table-hover">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th colspan="2" class="align-middle" style="border-left-color: black; border-left-width: medium ;border-top-color: black; border-top-width: medium; border-right-color: black; border-right-width: medium"></th>
                                                                    <th class="align-middle text-center" style="background-color : #daeef3; border-top-color: black; border-top-width: medium; border-right-color: black; border-right-width: medium">Morning  shift</th>
                                                                    <th class="align-middle text-center" style="background-color : #fde9d9;border-right-width: medium;border-right-color: black; border-top-color: black; border-top-width: medium">Night  shift</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td class="align-middle" style="border-left-color: black;border-left-width: medium; border-top-color: black; border-top-width: medium">Time</td>
                                                                    <td class="align-middle " style="border-right-width: medium; border-right-color: black; border-top-color: black; border-top-width: medium">Plan</td>
                                                                    <td class="align-middle text-center" id="keikaku_rpt_tbl_lbl_time_morning_plan" style="border-right-width: medium; border-right-color: black; border-top-color: black; border-top-width: medium"></td>
                                                                    <td class="align-middle text-center" id="keikaku_rpt_tbl_lbl_time_night_plan" style="border-right-width: medium;border-right-color: black; border-top-color: black; border-top-width: medium"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="align-middle" style="border-left-color: black;border-left-width: medium"></td>
                                                                    <td class="align-middle " style="border-right-width: medium; border-right-color: black">Actual</td>
                                                                    <td class="align-middle text-center" id="keikaku_rpt_tbl_lbl_time_morning_actual" style="border-right-width: medium; border-right-color: black"></td>
                                                                    <td class="align-middle text-center" id="keikaku_rpt_tbl_lbl_time_night_actual" style="border-right-width: medium;border-right-color: black"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="align-middle" style="border-left-color: black;border-left-width: medium"></td>
                                                                    <td class="align-middle " style="border-right-width: medium; border-right-color: black">Difference</td>
                                                                    <td class="align-middle text-center" id="keikaku_rpt_tbl_lbl_time_morning_difference" style="border-right-width: medium; border-right-color: black; border-top-color: black; border-top-width: medium"></td>
                                                                    <td class="align-middle text-center" id="keikaku_rpt_tbl_lbl_time_night_difference" style="border-right-width: medium;border-right-color: black; border-top-color: black; border-top-width: medium"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="align-middle" style="border-left-color: black;border-left-width: medium"></td>
                                                                    <td class="align-middle " style="border-right-width: medium; border-right-color: black">%</td>
                                                                    <td class="align-middle text-center" id="keikaku_rpt_tbl_lbl_time_morning_percentage" style="border-right-width: medium; border-right-color: black"></td>
                                                                    <td class="align-middle text-center" id="keikaku_rpt_tbl_lbl_time_night_percentage" style="border-right-width: medium;border-right-color: black"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="align-middle" style="border-left-color: black;border-left-width: medium; border-top-color: black; border-top-width: medium">Quantity</td>
                                                                    <td class="align-middle " style="border-right-width: medium; border-right-color: black; border-top-color: black; border-top-width: medium">Plan</td>
                                                                    <td class="align-middle text-center" id="keikaku_rpt_tbl_lbl_qty_morning_plan" style="border-right-width: medium; border-right-color: black; border-top-color: black; border-top-width: medium"></td>
                                                                    <td class="align-middle text-center" id="keikaku_rpt_tbl_lbl_qty_night_plan" style="border-right-width: medium;border-right-color: black; border-top-color: black; border-top-width: medium"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="align-middle" style="border-left-color: black;border-left-width: medium"></td>
                                                                    <td class="align-middle " style="border-right-width: medium; border-right-color: black">Actual</td>
                                                                    <td class="align-middle text-center" id="keikaku_rpt_tbl_lbl_qty_morning_actual" style="border-right-width: medium; border-right-color: black"></td>
                                                                    <td class="align-middle text-center" id="keikaku_rpt_tbl_lbl_qty_night_actual" style="border-right-width: medium;border-right-color: black"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="align-middle" style="border-left-color: black;border-left-width: medium"></td>
                                                                    <td class="align-middle" style="border-right-width: medium; border-right-color: black">Difference</td>
                                                                    <td class="align-middle text-center" id="keikaku_rpt_tbl_lbl_qty_morning_difference" style="border-right-width: medium; border-right-color: black; border-top-color: black; border-top-width: medium"></td>
                                                                    <td class="align-middle text-center" id="keikaku_rpt_tbl_lbl_qty_night_difference" style="border-right-width: medium;border-right-color: black; border-top-color: black; border-top-width: medium"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="align-middle" style="border-left-color: black;border-left-width: medium"></td>
                                                                    <td class="align-middle " style="border-right-width: medium; border-right-color: black">%</td>
                                                                    <td class="align-middle text-center" id="keikaku_rpt_tbl_lbl_qty_morning_percentage" style="border-right-width: medium; border-right-color: black"></td>
                                                                    <td class="align-middle text-center" id="keikaku_rpt_tbl_lbl_qty_night_percentage" style="border-right-width: medium;border-right-color: black"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="align-middle" style="border-left-color: black;border-left-width: medium; border-top-color: black; border-top-width: medium">Points</td>
                                                                    <td class="align-middle " style="border-right-width: medium; border-right-color: black; border-top-color: black; border-top-width: medium">Plan</td>
                                                                    <td class="align-middle text-center" id="keikaku_rpt_tbl_lbl_poin_morning_plan" style="border-right-width: medium; border-right-color: black; border-top-color: black; border-top-width: medium"></td>
                                                                    <td class="align-middle text-center" id="keikaku_rpt_tbl_lbl_poin_night_plan" style="border-right-width: medium;border-right-color: black; border-top-color: black; border-top-width: medium"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="align-middle" style="border-left-color: black;border-left-width: medium"></td>
                                                                    <td class="align-middle " style="border-right-width: medium; border-right-color: black">Actual</td>
                                                                    <td class="align-middle text-center" id="keikaku_rpt_tbl_lbl_poin_morning_actual" style="border-right-width: medium; border-right-color: black"></td>
                                                                    <td class="align-middle text-center" id="keikaku_rpt_tbl_lbl_poin_night_actual" style="border-right-width: medium;border-right-color: black"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="align-middle" style="border-left-color: black;border-left-width: medium"></td>
                                                                    <td class="align-middle " style="border-right-width: medium; border-right-color: black">Difference</td>
                                                                    <td class="align-middle text-center" id="keikaku_rpt_tbl_lbl_poin_morning_difference" style="border-right-width: medium; border-right-color: black; border-top-color: black; border-top-width: medium"></td>
                                                                    <td class="align-middle text-center" id="keikaku_rpt_tbl_lbl_poin_night_difference" style="border-right-width: medium;border-right-color: black; border-top-color: black; border-top-width: medium"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="align-middle" style="border-left-color: black;border-left-width: medium"></td>
                                                                    <td class="align-middle " style="border-right-width: medium; border-right-color: black">%</td>
                                                                    <td class="align-middle text-center" id="keikaku_rpt_tbl_lbl_poin_morning_percentage" style="border-right-width: medium; border-right-color: black"></td>
                                                                    <td class="align-middle text-center" id="keikaku_rpt_tbl_lbl_poin_night_percentage" style="border-right-width: medium;border-right-color: black"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="align-middle" style="border-left-color: black;border-left-width: medium; border-top-color: black; border-top-width: medium">Change Model</td>
                                                                    <td class="align-middle " style="border-right-width: medium; border-right-color: black; border-top-color: black; border-top-width: medium">Plan</td>
                                                                    <td class="align-middle text-center" id="keikaku_rpt_tbl_lbl_cm_morning_plan" style="border-right-width: medium; border-right-color: black; border-top-color: black; border-top-width: medium"></td>
                                                                    <td class="align-middle text-center" id="keikaku_rpt_tbl_lbl_cm_night_plan" style="border-right-width: medium;border-right-color: black; border-top-color: black; border-top-width: medium"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="align-middle" style="border-left-color: black;border-left-width: medium"></td>
                                                                    <td class="align-middle" style="border-right-width: medium;border-right-color: black">Actual</td>
                                                                    <td class="align-middle text-center" id="keikaku_rpt_tbl_lbl_cm_morning_actual" style="border-bottom-width: medium;border-bottom-color: black;border-right-width: medium; border-right-color: black"></td>
                                                                    <td class="align-middle text-center" style="border-bottom-color: black;border-bottom-width: medium;border-right-width: medium;border-right-color: black" id="keikaku_rpt_tbl_lbl_cm_night_actual"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="align-middle" style="border-left-color: black;border-left-width: medium"></td>
                                                                    <td class="align-middle " style="border-right-width: medium; border-right-color: black">Difference</td>
                                                                    <td class="align-middle text-center" id="keikaku_rpt_tbl_lbl_cm_morning_difference" style="border-right-width: medium; border-right-color: black"></td>
                                                                    <td class="align-middle text-center" id="keikaku_rpt_tbl_lbl_cm_night_difference" style="border-right-width: medium;border-right-color: black"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="align-middle" style="border-left-color: black;border-left-width: medium;border-bottom-color: black;border-bottom-width: medium;"></td>
                                                                    <td class="align-middle" style="border-bottom-color: black;border-bottom-width: medium; border-right-width: medium; border-right-color: black">%</td>
                                                                    <td class="align-middle text-center" style="border-bottom-color: black;border-bottom-width: medium;border-right-width: medium; border-right-color: black" id="keikaku_rpt_tbl_lbl_cm_morning_percentage"></td>
                                                                    <td class="align-middle text-center" style="border-bottom-color: black;border-bottom-width: medium;border-right-width: medium;border-right-color: black" id="keikaku_rpt_tbl_lbl_cm_night_percentage"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="align-middle" style="border-left-width: medium; border-left-color: black">Operation Rate</td>
                                                                    <td class="align-middle" style="border-bottom-color: black; border-right-width: medium; border-right-color: black">Plan</td>
                                                                    <td class="align-middle text-center" id="keikaku_rpt_tbl_lbl_or_morning_plan" style="border-right-width: medium; border-right-color: black"></td>
                                                                    <td class="align-middle text-center" id="keikaku_rpt_tbl_lbl_or_night_plan" style="border-right-width: medium; border-right-color: black"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="align-middle" style="border-left-color: black;border-left-width: medium;border-bottom-color: black;border-bottom-width: medium;"></td>
                                                                    <td class="align-middle" style="border-bottom-color: black;border-bottom-width: medium; border-right-width: medium; border-right-color: black">Actual</td>
                                                                    <td class="align-middle text-center" id="keikaku_rpt_tbl_lbl_or_morning_actual" style="border-bottom-color: black;border-bottom-width: medium;border-right-width: medium;border-right-color: black"></td>
                                                                    <td class="align-middle text-center" id="keikaku_rpt_tbl_lbl_or_night_actual" style="border-bottom-color: black;border-bottom-width: medium;border-right-width: medium;border-right-color: black"></td>
                                                                </tr>
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
                <div class="col-md-12">
                    <label for="platNomor" class="form-label">WO</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="keikakuEditWO" readonly>
                        <button class="btn btn-outline-primary" type="button" title="Copy" onclick="keikakuCopyWO()"><i class="fas fa-copy"></i></button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="keikakuEditHour" disabled />
                        <span class="input-group-text">~</span>
                        <input type="text" class="form-control" id="keikakuEditHourTo" disabled />
                        <span class="input-group-text">o'clock</span>
                    </div>
                </div>
            </div>
            <div class="row" id="keikakuEditOutputContainer">
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
      <input type="hidden" id="keikakuEditHeadSeq">
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="keikakuBtnEditActual" onclick="keikakuBtnEditActualOnClick(this)"><i class="fas fa-save"></i></button>
        <button type="button" class="btn btn-primary" id="keikakuBtnEditActualChangeModel" onclick="keikakuBtnEditActualChangeModelOnClick(this)">Set change model</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="keikakuEditINPUTHWModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5">Editing ...</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
            
            <div class="row">
                <div class="col-md-12">
                    <label for="platNomor" class="form-label">WO</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="keikakuEditINPUTHW_WO" readonly>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="keikakuEditINPUTHW_Hour" disabled />
                        <span class="input-group-text">~</span>
                        <input type="text" class="form-control" id="keikakuEditINPUTHW_HourTo" disabled />
                        <span class="input-group-text">o'clock</span>
                    </div>
                </div>
            </div>
            <div class="row" id="keikakuEditINPUTHWOutputContainer">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="platNomor" class="form-label">Qty</label>
                        <input type="text" class="form-control" id="keikakuEditINPUTHWOutput" onkeypress="keikakuEditINPUTHWOutputOnKeyPress(event)">
                    </div>
                </div>
            </div>
            <div class="row" id="keikakuEditINPUTHWCommentContainer">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="platNomor" class="form-label">Comment</label>
                        <input type="text" class="form-control" id="keikakuEditINPUTHWComment" maxlength="55">
                    </div>
                </div>
            </div>
        </div>
      </div>
      
      <input type="hidden" id="keikakuEditINPUTHW_XCoordinate">
      <input type="hidden" id="keikakuEditINPUTHW_Side">
      <input type="hidden" id="keikakuEditINPUTHW_HeadSeq">
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="keikakuBtnEditINPUTHW_Actual" onclick="keikakuBtnEditINPUTHW_ActualOnClick(this)"><i class="fas fa-save"></i></button>
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
      <input type="hidden" id="keikaku_rpt_type" value="-" />
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="keikakuRptBtnExportSummary" onclick="keikakuRptBtnExportSummary(this)">Export</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="keikaku_rpt_wo_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5">Job History</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">Job Number</label>
                        <input type="text" class="form-control" id="keikaku_rpt_wo_no" maxlength="25" onkeypress="keikaku_rpt_wo_no_e_keypress(event)" title="Press Enter to search">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="table-responsive" id="keikaku_rpt_wo_tbl_div">
                        <table id="keikaku_rpt_wo_tbl" class="table table-sm table-striped table-bordered table-hover" style="width:100%;font-size:91%">
                            <thead class="table-light text-center align-middle">
                                <tr>
                                    <th rowspan="2">Production date</th>
                                    <th rowspan="2">Line</th>
                                    <th rowspan="2">Job Number</th>
                                    <th rowspan="2">Spec Side</th>
                                    <th colspan="3">QTY</th>
                                </tr>
                                <tr>
                                    <th>Lot Size</th>
                                    <th>Plan</th>
                                    <th>Actual</th>
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
<div class="modal fade" id="keikaku_template_time_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5">Working Time Template</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">Template Name</label>
                        <select class="form-select" id="keikaku_template_name" onchange="keikaku_template_name_on_change()">
                        </select>
                        <button class="btn btn-outline-primary" id="keikaku_btn_new_template" title="Create new" onclick="keikaku_btn_new_template_eclick()">Add new</button>
                        <button class="btn btn-outline-primary" id="keikaku_btn_activate_template" title="Activate template" onclick="keikaku_btn_activate_template_eclick(this)">Set as Default</button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-1">
                    <h3><span class="badge text-bg-info">Friday</span></h3>
                    <div id="keikaku_calculation_friday_temp_spreadsheet"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-1">
                    <h3><span class="badge text-bg-info">Non Friday</span></h3>
                    <div id="keikaku_calculation_non_friday_temp_spreadsheet"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-1" id="keikaku-div-template-alert">

                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="keikaku_template_btn_save" onclick="keikaku_template_btn_save_eclick(this)">Save</button>
      </div>
    </div>
  </div>
</div>
<input type="hidden" id="keikaku_cell_name">
<script>
    var tempX1 = 0
    var tempX2 = 0
    var tempY1 = 0
    var tempY2 = 0
    var keikakuCommentBefore = ''

    function keikakuCopyWO() {
        keikakuEditWO.focus()
        keikakuEditWO.select()
        document.execCommand("copy");
        alertify.message('copied')
    }
    $("#keikakuEditActualModal").on('shown.bs.modal', function(){
        $("#keikakuEditOutput").focus();
        keikaku_prodplan_sso.resetSelection(true);
        keikakuEditAlert.innerHTML = ''
    });
    $("#keikaku_rpt_wo_modal").on('shown.bs.modal', function(){
        $("#keikaku_rpt_wo_no").focus();
    });
    $("#keikakuEditINPUTHWModal").on('shown.bs.modal', function(){
        $("#keikakuEditINPUTHWOutput").focus();
        keikaku_prodplan_sso.resetSelection(true);
        keikakuEditINPUTHWAlert.innerHTML = ''
    });
    $("#keikakuEditActualModal").on('hidden.bs.modal', function() {
        keikaku_prodplan_sso.updateSelectionFromCoords(tempX1, tempY1-1, tempX2, tempY2-1);
    });
    $("#keikakuEditINPUTHWModal").on('hidden.bs.modal', function() {
        keikaku_prodplan_sso.updateSelectionFromCoords(tempX1, tempY1-1, tempX2, tempY2-1);        
    });
    Inputmask({
        'alias': 'decimal',
        'groupSeparator': ',',
    }).mask(keikakuEditOutput);
    Inputmask({
        'alias': 'decimal',
        'groupSeparator': ',',
    }).mask(keikakuEditINPUTHWOutput);
    keikaku_asprova_year.value = new Date().toISOString().substring(0, 4)
    var keikakuModelUnique = []
    var keikaku_data_sso = jspreadsheet(keikaku_data_spreadsheet, {
        columns : [
            {
                title:'Seq.',
                type: 'numeric',
                width:30,
            },
            {
                title:'Model',
                type: 'text',
                width:175,
            },
            {
                title:'Job',
                type: 'text',
                width:90,
            },
            {
                title:'Lot Size',
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
                title:'Remark',
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
                width:80,
                align: 'right'
            },
            {
                title:'Previous Plan/Actual',
                type: 'numeric',
                mask: '#,##',
                decimal: '.',
                readOnly: true,
                width:90,
                align: 'right'
            },
            {
                title:'Total Run',
                type: 'numeric',
                mask: '#,##',
                decimal: '.',
                readOnly: true,
                width:90,
                align: 'right'
            },
            {
                title:'OS Job',
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
            [,,,,,,,,,'A',,,],
            [,,,,,,,,,'A',,,],
            [,,,,,,,,,'A',,,],
            [,,,,,,,,,'A',,,],
        ],
        minDimensions: [13,41],
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
                    cell.style.cssText = "background-color: #8aedff"
                } else {
                    cell.style.cssText = "background-color: #fafafa"
                }
            }

            if(x === 12) {
                const _diff = numeral(value).value() ?? 0
                if(_diff<0) {
                    cell.style.cssText = "color: #ff0000"
                } else {
                    cell.style.cssText = "color: #d3d3d3"
                }
            }
            if(x === 15) {
                const _diff = numeral(value).value() ?? 0
                if(_diff<0) {
                    cell.style.cssText = "color: #ff0000"
                } else {
                    cell.style.cssText = "color: #d3d3d3"
                }
            }
        },
        onselection: function(instance, x1, y1, x2, y2, origin) {
            tempX1 = x1
            tempX2 = x2
            tempY1 = y1
            tempY2 = y2
        },
        onpaste: function(data, data2) {
            if(data2[0].length>4) {
                keikaku_btn_run_data_eC()
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
        oneditionend : function(el, cell, x, y, value, flag) {
            if(y==7 && x>0) {
                const cellNO_OT_value = keikaku_calculation_sso.getValueFromCoords(x,4);
                const cellOT_value = keikaku_calculation_sso.getValueFromCoords(x,5);
                const constValue = keikaku_calculation_sso.getValueFromCoords((x<13 ? 10 : 22),7);
                const retValue = keikaku_calc_function({value : value, valueConstStartOT : constValue, valueNO_OT : cellNO_OT_value, valueOT : cellOT_value  })
                el.jspreadsheet.setValueFromCoords(x,8, retValue, true)
                keikaku_calc_make_sure(el)
            }
        },
        onblur : function (instance, cell, x, y, value) {
            keikaku_calc_make_sure(instance)
        },
        onload : function (instance) {
            keikaku_calc_make_sure(instance)
        },
        tableOverflow:true,
        freezeColumns: 1,
        minDimensions: [37,10],
        tableWidth: '1000px',
    });

    var keikaku_calculation_friday_temp_sso = jspreadsheet(keikaku_calculation_friday_temp_spreadsheet, {
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
            ['Hour', ...Array.from({length: 16}, (_, i) => i + 8), ...Array.from({length: 8}, (_, i) => i), ...Array.from({length: 16}, (_, i) => i + 8)],
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
        minDimensions: [37,6],
        tableWidth: '1000px',
    });

    var keikaku_calculation_non_friday_temp_sso = jspreadsheet(keikaku_calculation_non_friday_temp_spreadsheet, {
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
            ['Hour', ...Array.from({length: 16}, (_, i) => i + 8), ...Array.from({length: 8}, (_, i) => i), ...Array.from({length: 16}, (_, i) => i + 8)],
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
        minDimensions: [37,6],
        tableWidth: '1000px',
    });

    function keikaku_calc_make_sure(el) {
        try {
            for(let x=1;x<=36;x++) {
                const value = el.jspreadsheet.getValueFromCoords(x, 7)
                const cellNO_OT_value = el.jspreadsheet.getValueFromCoords(x,4);
                const cellOT_value = el.jspreadsheet.getValueFromCoords(x,5);
                const constValue = el.jspreadsheet.getValueFromCoords((x<13 ? 10 : 22),7);
                const retValue = keikaku_calc_function({value : value, valueConstStartOT : constValue, valueNO_OT : cellNO_OT_value, valueOT : cellOT_value  })
                el.jspreadsheet.setValueFromCoords(x,8, retValue, true)
            }
        } catch (err) {
            console.log({'keikaku_calc_make_sure' : err})
        }
    }

    function keikaku_calc_function(data) {
        if(['N','M','4M'].includes(data.value)) {
            return 0
        }

        if(data.valueConstStartOT == 'OT') {
            return data.valueOT
        } else {
            if(data.value>0) {
                return data.value
            } else {
                return data.valueNO_OT
            }
        }

    }
    var keikakuTableWidthObserver = 0

    resizeObserverO = new ResizeObserver(() => {
        if(typeof keikakuProdplanContainer != 'undefined') {
            keikakuTableWidthObserver = keikakuProdplanContainer.offsetWidth
            keikaku_prodplan_sso.table.parentNode.style.width = (keikakuTableWidthObserver-30)+'px'
            keikaku_calculation_sso.table.parentNode.style.width = (keikakuTableWidthObserver-30)+'px'
            keikaku_calculation_friday_temp_sso.table.parentNode.style.width = (keikakuTableWidthObserver-30)+'px'
            keikaku_calculation_non_friday_temp_sso.table.parentNode.style.width = (keikakuTableWidthObserver-30)+'px'
        }
    }).observe(keikakuProdplanContainer)

    var keikaku_prodplan_sso = jspreadsheet(keikaku_prodplan_spreadsheet, {
        columns : [
            ...Array.from({length: 9+36}, (_, i) => {
                            const objek = Object.create({
                                type : 'text',
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
                            if(i>=9 && i<=25) {
                                Object.defineProperty(objek, "title", {value : `${i-2} ~ ${i-1}`})
                            }
                            if(i>=26) {
                                Object.defineProperty(objek, "title", {value : `${i-26} ~ ${i-25}`})
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

            if (Array.from({length: 42}, (_, i) => i).includes(x) && [0,1,2,3,4,5,6,7,8].includes(y)) {
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
                    case 'PLAN':
                        cellName = jspreadsheet.getColumnNameFromId([x-1,y]);
                        theCells = cell.parentNode.cells
                        for(let i=0; i <theCells.length; i++) {
                            const theCell = theCells[i]
                            theCell.classList.add('readonly')
                        }
                        break;
                    case 'PLAN PROD':
                        cellName = jspreadsheet.getColumnNameFromId([x-1,y]);
                        theCells = cell.parentNode.cells
                        for(let i=0; i <theCells.length; i++) {
                            const theCell = theCells[i]
                            theCell.classList.add('readonly')
                        }
                        break;
                    case 'Actual':
                        cellName = jspreadsheet.getColumnNameFromId([x-1,y]);
                        theCells = cell.parentNode.cells
                        for(let i=0; i <theCells.length; i++) {
                            const theCell = theCells[i]
                            theCell.classList.add('keikakuGreenOldColor')
                        }
                        break;
                    case 'Total':
                        theCells = cell.parentNode.cells
                        for(let i=0; i <theCells.length; i++) {
                            const theCell = theCells[i]
                            theCell.classList.add('keikakuGrayOldColor')
                            theCell.classList.add('readonly')
                        }
                        break;
                    case 'TOTAL':
                        theCells = cell.parentNode.cells
                        for(let i=0; i <theCells.length; i++) {
                            const theCell = theCells[i]
                            theCell.classList.add('readonly')
                        }
                        break;
                    case 'Progress' :
                        theCells = cell.parentNode.cells
                        for(let i=0; i <theCells.length; i++) {
                            const theCell = theCells[i]
                            if(numeral(theCell.innerText).value() < 0) {
                                theCell.classList.add('keikakuRedColor')
                                } else {
                                    theCell.classList.remove('keikakuRedColor')
                                }
                                theCell.classList.add('readonly')
                        }
                        break;
                    case 'Total.' :
                        theCells = cell.parentNode.cells
                        for(let i=0; i <theCells.length; i++) {
                            const theCell = theCells[i]
                            if(numeral(theCell.innerText).value() < 0) {
                                theCell.classList.add('keikakuRedColor')
                            } else {                                
                                theCell.classList.remove('keikakuRedColor')
                            }
                            theCell.classList.add('readonly')
                        }
                        break;
                    case 'INPUT1' :
                        theCells = cell.parentNode.cells
                        for(let i=0; i <theCells.length; i++) {
                            const theCell = theCells[i]
                            theCell.classList.add('keikakuGreenOldColor')
                        }
                        break;
                    case 'INPUT2' :
                        theCells = cell.parentNode.cells
                        for(let i=0; i <theCells.length; i++) {
                            const theCell = theCells[i]
                            theCell.classList.add('keikakuGreenOldColor')
                        }
                        break;
                    case 'OUTPUT' :
                        theCells = cell.parentNode.cells
                        for(let i=0; i <theCells.length; i++) {
                            const theCell = theCells[i]
                            theCell.classList.add('keikakuGreenOldColor')
                        }
                        break;
                }
            }
            if(x>7 && y==7) {
                if(['MTN','N'].includes(value)) {
                    const theDataCount = keikaku_prodplan_sso.getData().length
                    for(let _y=y; _y<theDataCount;_y++) {
                        cellName = jspreadsheet.getColumnNameFromId([x,_y]);
                        const theCell = keikaku_prodplan_sso.getCell(cellName)
                        theCell.classList.add('keikakuGrayColorLight')
                    }
                }
            }
            if(value=='PLAN' && x==7) {
                for(let _x=0; _x<45;_x++) {
                    cellName = jspreadsheet.getColumnNameFromId([_x,y]);
                    const theCell = keikaku_prodplan_sso.getCell(cellName)
                    theCell.classList.add('keikakuBorderTop')
                }
            }
            if(value=='PLAN' && x==7 && y==9) {
                const theDataCount = keikaku_prodplan_sso.getData().length
                for(let _y=0; _y<theDataCount;_y++) {
                    cellName = jspreadsheet.getColumnNameFromId([x,_y]);
                    let theCell = keikaku_prodplan_sso.getCell(cellName)
                    theCell.classList.add('keikakuBorderRight')
                    theCell.classList.add('keikakuBorderLeft')

                    cellName = jspreadsheet.getColumnNameFromId([x+1,_y]);
                    theCell = keikaku_prodplan_sso.getCell(cellName)
                    theCell.classList.add('keikakuBorderRight')

                    cellName = jspreadsheet.getColumnNameFromId([x+13,_y]);
                    theCell = keikaku_prodplan_sso.getCell(cellName)
                    theCell.classList.add('keikakuBorderRight')

                    cellName = jspreadsheet.getColumnNameFromId([(x+13)+12,_y]);
                    theCell = keikaku_prodplan_sso.getCell(cellName)
                    theCell.classList.add('keikakuBorderRight')

                    cellName = jspreadsheet.getColumnNameFromId([(x+13)+24,_y]);
                    theCell = keikaku_prodplan_sso.getCell(cellName)
                    theCell.classList.add('keikakuBorderRight')
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
        minDimensions: [45,9],
        tableWidth: '1000px',
        onselection : function(instance, x1, y1, x2, y2, origin) {
            let aRow = instance.jspreadsheet.getRowData(y2)
            let aRowSibling = instance.jspreadsheet.getRowData(y2-1)
            let aRowSibling2 = instance.jspreadsheet.getRowData(y2-2)
            if(aRow[7] === 'Actual' && x2 >=9 ) {
                let aRowTime = instance.jspreadsheet.getRowData(1)
                keikakuEditHour.value = aRowTime[x2]
                keikakuEditHourTo.value = Number(aRowTime[x2]) + 1
                keikakuEditXCoordinate.value = x2


                keikakuEditOutput.value = aRow[x2]
                keikakuBeforeValue = aRow[x2]
                tempX1 = x1
                tempX2 = x2
                tempY1 = y1
                tempY2 = y2

                keikakuPrepareComment(x1, (y1+1))
                if (aRowSibling[7] === 'TOTAL') {
                    keikakuEditSide.value = aRowSibling[1]
                    keikakuEditHeadSeq.value = aRowSibling2[0]/2
                    keikaku_get_wo({
                        prefWO : aRowSibling2[3],
                        procWO : aRowSibling[1],
                        itemWO : aRowSibling[5],
                    })
                    keikakuEditOutputContainer.classList.remove('d-none')
                    keikakuBtnEditActual.classList.remove('d-none')
                    keikakuBtnEditActualChangeModel.classList.add('d-none')
                    keikakuISHWEditingMode = '4'
                } else {
                    aRowSibling = instance.jspreadsheet.getRowData(y2+2)
                    aRowSibling2 = instance.jspreadsheet.getRowData(y2+1)
                    keikakuEditHeadSeq.value = aRowSibling2[0]/2
                    keikakuEditSide.value = aRowSibling[1]
                    keikaku_get_wo({
                        prefWO : aRowSibling2[3],
                        procWO : aRowSibling[1],
                        itemWO : aRowSibling[5],
                    })
                    keikakuEditOutputContainer.classList.add('d-none')
                    keikakuBtnEditActual.classList.add('d-none')
                    keikakuBtnEditActualChangeModel.classList.remove('d-none')
                    keikakuISHWEditingMode = '5'
                }
                if(aRow[x2] == 1) {
                    keikakuBtnEditActualChangeModel.innerText = 'Remove Change Model'
                    keikakuBtnEditActualChangeModel.classList.remove('btn-primary')
                    keikakuBtnEditActualChangeModel.classList.add('btn-warning')
                } else {
                    keikakuBtnEditActualChangeModel.innerText = 'Set change model'
                    keikakuBtnEditActualChangeModel.classList.add('btn-primary')
                    keikakuBtnEditActualChangeModel.classList.remove('btn-warning')
                }
            }

            if(aRow[7] === 'INPUT1' && x2 >=9 ) { 
                keikakuISHWEditingMode = '1'
                let aRowTime = instance.jspreadsheet.getRowData(1)
                keikakuEditINPUTHW_Hour.value = aRowTime[x2]
                keikakuEditINPUTHW_HourTo.value = Number(aRowTime[x2]) + 1
                keikakuEditINPUTHW_XCoordinate.value = x2

                keikakuEditINPUTHWOutput.value = aRow[x2]
                keikakuBeforeValue = aRow[x2]
                tempX1 = x1
                tempX2 = x2
                tempY1 = y1
                tempY2 = y2

                keikakuPrepareComment(x1, (y1+1))

                if (aRowSibling[7] === 'TOTAL') {
                    keikakuEditINPUTHW_Side.value = aRowSibling[1]
                    keikakuEditINPUTHW_HeadSeq.value = aRowSibling2[0]/2
                    keikaku_get_wo({
                        prefWO : aRowSibling2[3],
                        procWO : aRowSibling[1],
                        itemWO : aRowSibling[5],
                    })
                }
            }

            if(aRow[7] === 'INPUT2' && x2 >=9 ) { 
                keikakuISHWEditingMode = '3'
                aRowSibling = instance.jspreadsheet.getRowData(y2-3)
                aRowSibling2 = instance.jspreadsheet.getRowData(y2-4)
                let aRowTime = instance.jspreadsheet.getRowData(1)
                keikakuEditINPUTHW_Hour.value = aRowTime[x2]
                keikakuEditINPUTHW_HourTo.value = Number(aRowTime[x2]) + 1
                keikakuEditINPUTHW_XCoordinate.value = x2

                keikakuEditINPUTHWOutput.value = aRow[x2]
                keikakuBeforeValue = aRow[x2]
                tempX1 = x1
                tempX2 = x2
                tempY1 = y1
                tempY2 = y2
            
                keikakuPrepareComment(x1, (y1+1))

                if (aRowSibling[7] === 'TOTAL') {
                    keikakuEditINPUTHW_Side.value = aRowSibling[1]
                    keikakuEditINPUTHW_HeadSeq.value = aRowSibling2[0]/2
                    keikaku_get_wo({
                        prefWO : aRowSibling2[3],
                        procWO : aRowSibling[1],
                        itemWO : aRowSibling[5],
                    })
                }
            }

            if(aRow[7] === 'OUTPUT' && x2 >=9 ) { 
                aRowSibling = instance.jspreadsheet.getRowData(y2-5)
                aRowSibling2 = instance.jspreadsheet.getRowData(y2-6)
                keikakuISHWEditingMode = '2'
                let aRowTime = instance.jspreadsheet.getRowData(1)
                keikakuEditINPUTHW_Hour.value = aRowTime[x2]
                keikakuEditINPUTHW_HourTo.value = Number(aRowTime[x2]) + 1
                keikakuEditINPUTHW_XCoordinate.value = x2

                keikakuEditINPUTHWOutput.value = aRow[x2]
                keikakuBeforeValue = aRow[x2]
                tempX1 = x1
                tempX2 = x2
                tempY1 = y1
                tempY2 = y2

                keikakuPrepareComment(x1, (y1+1))
                
                if (aRowSibling[7] === 'TOTAL') {
                    keikakuEditINPUTHW_Side.value = aRowSibling[1]
                    keikakuEditINPUTHW_HeadSeq.value = aRowSibling2[0]/2
                    keikaku_get_wo({
                        prefWO : aRowSibling2[3],
                        procWO : aRowSibling[1],
                        itemWO : aRowSibling[5],
                    })
                }
            }
        },
        oneditionend : function(a,b,x,y,newValue,f) {
            if(numeral(keikakuBeforeValue).value!=numeral(newValue).value() && x>8) {
                if(["1","2","3"].includes(keikakuISHWEditingMode)) {
                    if(!isNaN(newValue)) {
                        keikakuEditINPUTHWOutput.value = numeral(newValue).value()
                        keikakuBtnEditINPUTHW_ActualOnClick(keikakuBtnEditINPUTHW_Actual)
                    } else {
                        alert('Number is required')
                        keikaku_prodplan_sso.setValueFromCoords(tempX1, tempY1, keikakuBeforeValue, true)
                    }
                } else {
                    if(keikakuISHWEditingMode == "4") {
                        if(!isNaN(newValue)) {
                            keikakuEditOutput.value = numeral(newValue).value()
                            keikakuBtnEditActualOnClick(keikakuBtnEditActual)
                        } else {
                            alert('Number is required')
                            keikaku_prodplan_sso.setValueFromCoords(tempX1, tempY1, keikakuBeforeValue, true)
                        }
                    } else {
                        if(['-','1'].includes(newValue)) {
                            keikakuBtnEditActualChangeModelOnClick(keikakuBtnEditActualChangeModel, newValue)
                        } else {
                            alert('please fill with - or 1')
                            keikaku_prodplan_sso.setValueFromCoords(tempX1, tempY1, keikakuBeforeValue, true)
                        }
                    }
                }
            }
        }, contextMenu: function(obj, x, y, e) {
            let items = [];

            if (x) {
                items.push({ type:'line' });

                const title = keikakuCommentBefore;

                items.push({
                    title: title ? obj.options.text.editComments : obj.options.text.addComments,
                    onclick:function() {
                        const _comment = prompt(obj.options.text.comments, title) ?? ''
                        obj.setComments([ x, y ], _comment);
                        keikakuSetDBComment(_comment)
                    }
                });

                if (title) {
                    items.push({
                        title:obj.options.text.clearComments,
                        onclick:function() {
                            obj.setComments([ x, y ], '');
                            keikakuSetDBComment('')
                        }
                    });
                }                
            }

           
            return items
        }
    });

    function keikakuPrepareComment(x, y) {
        const theCellName = keikakugetColumnName(x) + (y)
        keikaku_cell_name.value = theCellName
        keikakuCommentBefore = keikakuGetComment(theCellName)
        keikakuEditINPUTHWComment.value = keikakuGetComment(theCellName)
    }

    var keikakuISHWEditingMode = '-'
    var keikakuBeforeValue = '-'
    function keikaku_get_wo(data) {
        let ttlRows = _temp_asProdpan.length
        for(let i=4; i<ttlRows;i+=2) {
            if(_temp_asProdpan[i][0] == data.itemWO
            && _temp_asProdpan[i][3].includes(data.prefWO)
            && _temp_asProdpan[i][5].substr(0,1) == data.procWO) {
                keikakuEditWO.value = _temp_asProdpan[i][3]
                keikakuEditINPUTHW_WO.value = _temp_asProdpan[i][3]
                break;
            }
        }
    }


    function keikaku_load_line_code() {
        $.ajax({
            type: "GET",
            url: "<?php echo $_ENV['APP_INTERNAL_API'] ?>process-master/line-code",
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
                url: "<?php echo $_ENV['APP_INTERNAL_API'] ?>keikaku",
                data: {line_code : keikaku_line_input.value, production_date : keikaku_date_input.value, user_id: uidnya},
                dataType: "json",
                success: function (response) {
                    keikaku_user_first_active.value = response.currentActiveUser.MSTEMP_ID + ':' + response.currentActiveUser.MSTEMP_FNM
                    if(response.data.length === 0) {
                        if(confirm('Create new data based on previous balance ?')) {
                            const dataInput = {
                                line_code : keikaku_line_input.value,
                                production_date : keikaku_date_input.value,
                                user_id: uidnya
                            }

                            $.ajax({
                                type: "POST",
                                url: "<?php echo $_ENV['APP_INTERNAL_API'] ?>keikaku/from-balance",
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
            ``,
            ``,
            ``,
            ``,
            ``,
            ``,
            ``,
            ``,
            ``,
            ``,
            ``,
            ``,

            ``,
            ``,
            ``,
            ``,
            ``,
            ``,
            ``,
            ``,
            ``,
            ``,
            ``,
            ``,

            ``,
            ``,
            ``,
            ``,
            ``,
            ``,
            ``,
            ``,
            ``,
            ``,
            ``,
            ``,
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
        const div_alert = document.getElementById('keikaku-div-alert')
        const theStyle = keikaku_data_sso.getStyle();
        if(keikaku_line_input.value === '-') {
            alertify.warning(`Line is required`)
            keikaku_line_input.focus()
            return
        }

        keikaku_btn_run_data_eC()

        const dataDetail = []
        let JobUnique = []
        let JobUniqueDetail = []

        let inputSS = keikaku_data_sso.getData().filter((data) => data[2].length && data[7].length > 1)
        const inputSSCount = inputSS.length

        // check wo proses unique
        for(let i=0; i<inputSSCount;i++) {
            let _job = inputSS[i][2].trim()
            let _assyCode = inputSS[i][7].trim()
            let _process = inputSS[i][9].trim()
            let _rowK = _job + _assyCode + _process
            let _qty = numeral(inputSS[i][4]).value()
            let _size = numeral(inputSS[i][3]).value()
            let JobUniqueDetailLength = JobUniqueDetail.length

            if(_qty>_size) {
                alertify.warning(`Production Qty > Lot Size !.`)
                return
            }

            let isFound = false
            for(let s=0; s<JobUniqueDetailLength; s++) {
                if(JobUniqueDetail[s].job == _job
                    && JobUniqueDetail[s].assyCode == _assyCode
                    && JobUniqueDetail[s].process == _process
                ) {
                    JobUniqueDetail[s].qty += _qty
                    isFound = true
                    break;
                }
            }

            if(!isFound) {
                JobUniqueDetail.push({
                    job : _job,
                    size : _size,
                    assyCode : _assyCode,
                    process : _process,
                    qty : _qty,
                    rowK : _rowK
                })
            }
        }

        for(let i=0; i<inputSSCount;i++) {
            let _job = inputSS[i][2].trim() + inputSS[i][7].trim() + inputSS[i][9].trim()
            let _jobHumanize = inputSS[i][2].trim() + '-' + inputSS[i][7].trim() + '-' + inputSS[i][9].trim()
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
                    cycle_time : numeral(inputSS[i][10]).value()
                })
            } else {
                let JobUniqueDetailLength = JobUniqueDetail.length

                let isOK = false
                for(let s=0; s<JobUniqueDetailLength; s++) {
                    if(JobUniqueDetail[s].rowK == _job && JobUniqueDetail[s].qty <= JobUniqueDetail[s].size
                    ) {
                        isOK = true
                        break;
                    }
                }

                if(isOK) {
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
                        cycle_time : numeral(inputSS[i][10]).value()
                    })
                } else {
                    div_alert.innerHTML = `<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    Production Qty > Lot Size ! ${_jobHumanize}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>`
                    return
                }
            }
        }

        const dataKalkulasi = keikaku_prepare_save_calculation({detailOnly : false})

        const dataInput = {
            line_code : keikaku_line_input.value,
            production_date : keikaku_date_input.value,
            user_id: uidnya,
            detail : dataDetail,
            style : theStyle,
            detail_calc : dataKalkulasi.detail,
            isOverTimeMorning : dataKalkulasi.isOverTimeMorning,
            isOverTimeNight : dataKalkulasi.isOverTimeNight,
            totalWorkingTimeMorning : dataKalkulasi.totalWorkingTimeMorning,
            totalWorkingTimeNight : dataKalkulasi.totalWorkingTimeNight
        }
        

        if(confirm('Are you sure want to save ?')) {

            pThis.disabled = true
            $.ajax({
                type: "POST",
                url: "<?php echo $_ENV['APP_INTERNAL_API'] ?>keikaku",
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

    function keikaku_prepare_save_calculation(pOptions) {
        const dataDetail = []
        let inputSS = keikaku_calculation_sso.getData()
        const inputSSCount = inputSS.length

        let totalWorkingTimeMorning = 0.25;
        let totalWorkingTimeNight = 0.25;
        let isOverTimeMorning = 0;
        let isOverTimeNight = 0;

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

            if(c<13) {
                if(inputSS[7][c]=='OT') {
                    isOverTimeMorning = true
                }
            } else {
                if(c<=24) {
                    if(inputSS[7][c]=='OT') {
                        isOverTimeNight = true
                    }
                }
            }

        }

        for(let c=1; c<37; c++) {
            if(c<13) {
                if(isOverTimeMorning) {
                    totalWorkingTimeMorning += numeral(inputSS[5][c]).value()
                } else {
                    totalWorkingTimeMorning += numeral(inputSS[4][c]).value()
                }
            }

            if(c>=13 && c<=24) {
                if(isOverTimeNight) {
                    totalWorkingTimeNight += numeral(inputSS[5][c]).value()
                } else {
                    totalWorkingTimeNight += numeral(inputSS[4][c]).value()
                }
            }
        }

        const dataInput = {
            line_code : keikaku_line_input.value,
            production_date : keikaku_date_input.value,
            user_id: uidnya,
            detail : dataDetail,
            isOverTimeMorning : isOverTimeMorning,
            isOverTimeNight : isOverTimeNight,
            totalWorkingTimeMorning : totalWorkingTimeMorning,
            totalWorkingTimeNight : totalWorkingTimeNight
        }

        return pOptions.detailOnly ? dataDetail : dataInput
    }

    function keikaku_btn_save_calculation_eClick(pThis) {
        if(keikaku_line_input.value === '-') {
            alertify.warning(`Line is required`)
            keikaku_line_input.focus()
            return
        }

        const dataInput = keikaku_prepare_save_calculation({detailOnly : false})

        if(confirm('Are you sure want to save ?')) {
            const div_alert = document.getElementById('keikaku-div-alert')
            pThis.disabled = true
            $.ajax({
                type: "POST",
                url: "<?php echo $_ENV['APP_INTERNAL_API'] ?>keikaku/calculation",
                data: JSON.stringify(dataInput),
                dataType: "JSON",
                success: function (response) {
                    alertify.success(response.message)
                    pThis.disabled = false
                    div_alert.innerHTML = ''
                    keikaku_btn_run_prodplan_eC(keikaku_btn_run_prodplan)
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

    function keikaku_calc_friday(hourAt, isOT) {
        const currentDay = new Date(keikaku_date_input.value).getDay();
        let _defaultWorkHour = 0
        switch(hourAt) {
            case 12:
                _defaultWorkHour = (currentDay == 5 ? 0.25 : 0.5)
                break;
            case 15:
                if(isOT) {
                    _defaultWorkHour = (currentDay == 5 ? 1 : 0.83)
                } else {
                    _defaultWorkHour = (currentDay == 5 ? 1 : 0.75)
                }
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
            url: "<?php echo $_ENV['APP_INTERNAL_API'] ?>keikaku/calculation",
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
                    if(response.dataDefault.length > 0) {
                        response.dataDefault.forEach((arrayItem) => {
                            worktype1.push(Number.parseFloat(arrayItem['worktype1']).toFixed(2))
                            worktype2.push(Number.parseFloat(arrayItem['worktype2']).toFixed(2))
                            worktype3.push(Number.parseFloat(arrayItem['worktype3']).toFixed(2))
                            worktype4.push(Number.parseFloat(arrayItem['worktype4']).toFixed(2))
                            worktype5.push(Number.parseFloat(arrayItem['worktype5']).toFixed(2))
                            worktype6.push(Number.parseFloat(arrayItem['worktype6']).toFixed(2))
                            flag_mot.push('')
                        })
                    } else {
                        // kalau data kosong
                        worktype1.push(...[0.75, 1,	0.75, 1, 1, keikaku_calc_friday(12, false) , 1, 1, keikaku_calc_friday(15,false), 0, 0,0, 0.75, 1, 0.67, 1, 1, 0.33, 1, 1, 1, 0,	0,0	,0.75,	1, 0.75, 1, 1, 0.50, 1, 1, 0.75,0,0,0])
                        worktype2.push(...[0.75 ,1 ,0.75 ,1 ,1 ,keikaku_calc_friday(12, true) ,1 ,1 ,keikaku_calc_friday(15, true) ,keikaku_calc_friday(16, true) ,1 ,keikaku_calc_friday(18,true) ,0.75 ,1 ,0.67 ,1 ,1 ,0.33 ,1.00 ,1.00 ,1.00 ,0.75 ,1.00 ,0.75 ,0.75 ,1.00 ,0.75 ,1.00 ,1.00 ,0.50 ,1.00 ,1.00 ,0.83 ,1.00 ,1.00 ,0.42])
                        worktype3.push(...[0    ,0 ,0.75 ,1 ,1 ,keikaku_calc_friday(12, false) ,1 ,1 ,keikaku_calc_friday(15,false) ,0    ,0    ,0    ,0.75 ,1 ,0.67 ,1 ,1 ,0.33 ,1 ,1 ,1 ,0    ,0    ,0    ,0.75 ,1.00 ,0.75 ,1.00 ,1.00 ,0.50 ,1.00 ,1.00 ,0.75 ,0    ,0    ,0])
                        worktype4.push(...[0,0,0.75 ,1 ,1 ,keikaku_calc_friday(12,true) ,1 ,1 ,keikaku_calc_friday(15, true) ,keikaku_calc_friday(16, true) ,1 ,keikaku_calc_friday(18, true) ,0.75 ,1 ,0.67 ,1 ,1 ,0.33 ,1.00 ,1.00 ,1.00 ,0.75 ,1.00 ,0.75 ,0.75 ,1.00 ,0.75 ,1.00 ,1.00 ,0.50 ,1.00 ,1.00 ,0.83 ,1.00 ,1.00 ,0.42])
                        worktype5.push(...[0.75 ,1 ,0.75 ,1 ,1 ,keikaku_calc_friday(12,false) ,1 ,1 ,keikaku_calc_friday(15,false) ,0,0,0,0.75 ,1 ,0.67 ,1 ,1 ,0.33 ,1 ,1 ,1 ,0,0,0,0.75 ,1 ,0.75 ,1 ,1 ,0.50 ,1.00 ,1.00 ,0.75,0,0,0])
                        worktype6.push(...[0.75 ,1 ,0.75 ,1 ,1 ,keikaku_calc_friday(12,true) ,1 ,1 ,keikaku_calc_friday(15,true) ,keikaku_calc_friday(16,true) ,1 ,keikaku_calc_friday(18,true) ,0.75 ,1 ,0.67 ,1 ,1 ,0.33 ,1.00 ,1.00 ,1.00 ,0.75 ,1.00 ,0.75 ,0.75 ,1.00 ,0.75 ,1.00 ,1.00 ,0.50 ,1.00 ,1.00 ,0.83 ,1.00 ,1.00 ,0.42])
                        for(let i=0;i<36;i++) {
                            flag_mot.push('')
                        }
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
                    ``,
                    ``,
                    ``,
                    ``,
                    ``,
                    ``,
                    ``,
                    ``,
                    ``,
                    ``,
                    ``,
                    ``,

                    ``,
                    ``,
                    ``,
                    ``,
                    ``,
                    ``,
                    ``,
                    ``,
                    ``,
                    ``,
                    ``,
                    ``,

                    ``,
                    ``,
                    ``,
                    ``,
                    ``,
                    ``,
                    ``,
                    ``,
                    ``,
                    ``,
                    ``,
                    ``,
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
        keikaku_line_input.disabled = true
        const div_alert = document.getElementById('keikaku-div-alert')
        div_alert.innerHTML = ``
        $.ajax({
            type: "GET",
            url: "<?php echo $_ENV['APP_INTERNAL_API'] ?>keikaku",
            data: {line_code : keikaku_line_input.value, production_date : keikaku_date_input.value, user_id : uidnya},
            dataType: "json",
            success: function (response) {
                keikaku_line_input.disabled = false
                keikaku_user_first_active.value = response.currentActiveUser.MSTEMP_ID + ':' + response.currentActiveUser.MSTEMP_FNM
                let theData = [];
                let theIndexRed = [];
                let messageO = {
                    job: '-',
                    item: '-',
                    specs_side: '-',
                }
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
                        arrayItem['previousRun'],
                        `=IF(L${index+1}=0,0,L${index+1}+N${index+1})`,
                        `=O${index+1}-D${index+1}`,
                    ])

                    if(arrayItem['wo_full_code'].includes('?')) {
                        theIndexRed.push(index+1)
                    }

                    if(numeral(arrayItem['previousRun']).value()+numeral(arrayItem['plan_qty']).value()>numeral(arrayItem['lot_size']).value() ) {
                        messageO.job = arrayItem['wo_code']
                        messageO.item = arrayItem['item_code']
                        messageO.specs_side = arrayItem['specs_side']
                    }
                })

                if(theData.length > 0) {
                    keikaku_data_sso.setData(theData)
                }
                const theIndexRedLength = theIndexRed.length

                if(response.dataStyle) {
                    keikaku_data_sso.setStyle(response.dataStyle)
                }

                for(let i=0;i<theIndexRedLength;i++) {
                    keikaku_data_sso.setStyle('A'+theIndexRed[i], 'background-color', '#fb5252')
                    keikaku_data_sso.setStyle('B'+theIndexRed[i], 'background-color', '#fb5252')
                    keikaku_data_sso.setStyle('C'+theIndexRed[i], 'background-color', '#fb5252')
                    keikaku_data_sso.setStyle('D'+theIndexRed[i], 'background-color', '#fb5252')
                    keikaku_data_sso.setStyle('E'+theIndexRed[i], 'background-color', '#fb5252')
                    keikaku_data_sso.setStyle('F'+theIndexRed[i], 'background-color', '#fb5252')
                    keikaku_data_sso.setStyle('G'+theIndexRed[i], 'background-color', '#fb5252')
                    keikaku_data_sso.setStyle('H'+theIndexRed[i], 'background-color', '#fb5252')
                }
                
                if(messageO.job!='-') {
                    div_alert.innerHTML = `<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    Production Qty > Lot Size ! 👉 ${messageO.job}-${messageO.item} specs side ${messageO.specs_side}
                    
                    </div>`
                }
            }, error: function(xhr, xopt, xthrow) {
                keikaku_line_input.disabled = false
            }
        });
    }

    function keikaku_line_input_on_change() {
        keikaku_load_calcultion()
        keikaku_load_data()
        keikaku_load_prodplan()
        keikakuGetDownTime()
    }

    function keikaku_btn_run_data_eC() {

        const dataDetail = []
        let JobUnique = []
        let inputSS = keikaku_data_sso.getData().filter((data) => data[2].length && data[7].length > 1)
        const inputSSCount = inputSS.length

        for(let i=0; i<inputSSCount;i++) {
            let _job = inputSS[i][2].trim() + inputSS[i][7].trim()
            if(!JobUnique.includes(_job)) {
                JobUnique.push(_job)
                if(inputSS[i][7].trim().includes('ASP') || inputSS[i][7].trim().includes('KD')) {
                    dataDetail.push({
                        item_code : inputSS[i][7].trim().substring(0,9),
                    })
                } else {
                    dataDetail.push({
                        item_code : inputSS[i][7].trim(),
                    })
                }
            }
        }

        const dataInput = {
            line_code : keikaku_line_input.value,
            detail : dataDetail,
            production_date : keikaku_date_input.value
        }
        
        $.ajax({
            async: false,
            type: "POST",
            url: "<?php echo $_ENV['APP_INTERNAL_API'] ?>process-master/search",
            data: JSON.stringify(dataInput),
            dataType: "JSON",
            success: function (response) {
                
                let dataLength = keikaku_data_sso.getData().length
                let responseDataLength = response.data.length
                if(keikakuIsHW()) {
                    for(let i=0; i < dataLength; i++) {
                        let _itemCode = keikaku_data_sso.getValueFromCoords(7, i, true).trim()
                        let _processCode = keikaku_data_sso.getValueFromCoords(9, i, true).trim().substring(0,9)
                        keikaku_data_sso.setValue('K'+(i+1), 0, true)
                        for(let s=0;s<responseDataLength; s++) {
                            const _responseProcess = response.data[s].process_code.trim()
                            if(_itemCode.includes('ASP') || _itemCode.includes('KD')) {
                                if(_itemCode.substring(0,9) == response.data[s].assy_code.trim()
                                        && _responseProcess.includes('HW')
                                    ) {
                                        keikaku_data_sso.setValue('K'+(i+1), response.data[s].cycle_time, true)
                                        break;
                                }
                            } else {
                                if(_itemCode == response.data[s].assy_code.trim()
                                    &&  _responseProcess.includes('HW')
                                ) {
                                        keikaku_data_sso.setValue('K'+(i+1), response.data[s].cycle_time, true)
                                        break;
                                    }
                            }

                        }
                    }
                } else {
                    if(keikaku_line_input.value.includes('AT')) {
                        for(let i=0; i < dataLength; i++) {
                            let _itemCode = keikaku_data_sso.getValueFromCoords(7, i, true).trim()
                            let _processCode = keikaku_data_sso.getValueFromCoords(9, i, true).trim()
                            keikaku_data_sso.setValue('K'+(i+1), 0, true)
                            for(let s=0;s<responseDataLength; s++) {
                                const _responseProcess = response.data[s].process_code.trim()

                                if(_itemCode == response.data[s].assy_code.trim()
                                &&  _processCode === _responseProcess.substr(_responseProcess.length-1, 1)
                            ) {
                                    keikaku_data_sso.setValue('K'+(i+1), response.data[s].cycle_time, true)
                                    break;
                                }
                            }
                        }
                    } else {
                        for(let i=0; i < dataLength; i++) {
                            let _itemCode = keikaku_data_sso.getValueFromCoords(7, i, true).trim()
                            let _processCode = keikaku_data_sso.getValueFromCoords(9, i, true).trim().substring(0,9)
                            keikaku_data_sso.setValue('K'+(i+1), 0, true)
                            for(let s=0;s<responseDataLength; s++) {
                                const _responseProcess = response.data[s].process_code.trim()
                                if(_itemCode.includes('ASP') || _itemCode.includes('KD')) {
                                    if(_itemCode.substring(0,9) == response.data[s].assy_code.trim()
                                            &&  _processCode === _responseProcess.substr(_responseProcess.length-1, 1)
                                        ) {
                                            keikaku_data_sso.setValue('K'+(i+1), response.data[s].cycle_time, true)
                                            break;
                                    }
                                } else {
                                    if(_itemCode == response.data[s].assy_code.trim()
                                        &&  _processCode === _responseProcess.substr(_responseProcess.length-1, 1)
                                    ) {
                                            keikaku_data_sso.setValue('K'+(i+1), response.data[s].cycle_time, true)
                                            break;
                                        }
                                }

                            }
                        }
                    }
                }
            }, error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow)

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
                url: "<?php echo $_ENV['APP_INTERNAL_API'] ?>production-plan/import",
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
            url: "<?php echo $_ENV['APP_INTERNAL_API'] ?>production-plan/revisions",
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
            url: `<?php echo $_ENV['APP_INTERNAL_API'] ?>production-plan/revisions/${btoa(revision)}`,
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
    var _temp_dataSensor = [];
    var _temp_dataCalculation = [];
    var _temp_dataChangesModel = [];
    var _temp_dataInputHW = [];
    var _temp_dataOutputHW = [];
    var _temp_dataInput2HW = [];
    var _temp_dataComment = [];

    function keikakuGetComment(cellCode) {
        const totalRows = _temp_dataComment.length
        for(let i=0;i<totalRows;i++) {
            if(_temp_dataComment[i].cell_code === cellCode) {
                return _temp_dataComment[i].comment
            }
        }
        return ''
    }

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
        const containerInfo = document.getElementById('keikaku-div-alert')
        $.ajax({
            type: "GET",
            url: "<?php echo $_ENV['APP_INTERNAL_API'] ?>keikaku/production-plan",
            data: data,
            dataType: "json",
            success: function (response) {
                pThis.disabled = false
                keikaku_cell_name.value = ''
                _temp_asProdpan = response.asProdplan
                _temp_dataComment = response.dataComment

                // display prodplan to grid
                keikakuDisplayProdplan(response.asProdplan, response.dataSensor, response.dataCalculation, response.dataChangesModel, response.dataInputHW, response.dataOutputHW, response.dataInput2HW)                

                // report graph

                let plan = [0]
                let actual = [0]
                let planTime = [0]
                let actualTime = [0]
                let progressTime = [0]
                let prodPlanLength = response.asProdplan.length
                let outputLength = response.dataSensor.length
                let inputLength = response.dataInputHW.length
                let timeLength = response.asMatrix.length
                let _totalPlan = 0
                let _totalActual = 0
                let _totalPlanTime = 0
                let _totalActualTime = 0
                let _totalProgressTime = 0

                let _totalPlanTimeMorning = 0;
                let _totalPlanTimeNight = 0;
                let _totalActualTimeMorning = 0;
                let _totalActualTimeNight = 0;
                let _totalTimePRCMorning = 0;
                let _totalTimePRCNight = 0;

                let _totalPlanQtyMorning = 0;
                let _totalPlanQtyNight = 0;
                let _totalActualQtyMorning = 0;
                let _totalActualQtyNight = 0;

                if(keikakuIsHW()) {
                    for(let i=6; i<=29; i++) {
                        for(let r=4;r<prodPlanLength;r++) {
                            if(response.asProdplan[r][0]) {
                                _totalPlan += Number(response.asProdplan[r][i])
                                if(i>=18) {
                                    _totalPlanQtyNight += Number(response.asProdplan[r][i])
                                } else {
                                    _totalPlanQtyMorning += Number(response.asProdplan[r][i])
                                }
                            }
                        }

                        for(let r=0;r<inputLength;r++) {
                            _totalActual += Number(response.dataInput2HW[r][i])
                            _totalActualTime += (response.dataInput2HW[r][5] *Number(response.dataInput2HW[r][i]) )

                            if(i>=18) {
                                _totalActualTimeNight += (response.dataInput2HW[r][5] *Number(response.dataInput2HW[r][i]) )
                                _totalActualQtyNight += Number(response.dataInput2HW[r][i])

                            } else {
                                _totalActualTimeMorning += (response.dataInput2HW[r][5] *Number(response.dataInput2HW[r][i]) )
                                _totalActualQtyMorning += Number(response.dataInput2HW[r][i])
                            }
                        }

                        for(let r=0;r<timeLength;r++) {
                            if(response.asMatrix[r][0]) {
                                _totalPlanTime += Number(response.asMatrix[r][i])
                            }
                        }


                        for(let r=4;r<timeLength;r+=2) {
                            if(i>=18) {
                                _totalPlanTimeNight += Number(response.asMatrix[r][i])
                            } else {
                                _totalPlanTimeMorning += Number(response.asMatrix[r][i])
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

                } else {
                    for(let i=6; i<=29; i++) {
                        for(let r=4;r<prodPlanLength;r++) {
                            if(response.asProdplan[r][0]) {
                                _totalPlan += Number(response.asProdplan[r][i])
                                if(i>=18) {
                                    _totalPlanQtyNight += Number(response.asProdplan[r][i])
                                } else {
                                    _totalPlanQtyMorning += Number(response.asProdplan[r][i])
                                }
                            }
                        }

                        for(let r=0;r<outputLength;r++) {
                            _totalActual += Number(response.dataSensor[r][i])
                            _totalActualTime += (response.dataSensor[r][5] *Number(response.dataSensor[r][i]) )

                            if(i>=18) {
                                _totalActualTimeNight += (response.dataSensor[r][5] *Number(response.dataSensor[r][i]) )
                                _totalActualQtyNight += Number(response.dataSensor[r][i])

                            } else {
                                _totalActualTimeMorning += (response.dataSensor[r][5] *Number(response.dataSensor[r][i]) )
                                _totalActualQtyMorning += Number(response.dataSensor[r][i])
                            }
                        }

                        for(let r=0;r<timeLength;r++) {
                            if(response.asMatrix[r][0]) {
                                _totalPlanTime += Number(response.asMatrix[r][i])
                            }
                        }


                        for(let r=4;r<timeLength;r+=2) {
                            if(i>=18) {
                                _totalPlanTimeNight += Number(response.asMatrix[r][i])
                            } else {
                                _totalPlanTimeMorning += Number(response.asMatrix[r][i])
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

                _totalTimePRCMorning = _totalPlanTimeMorning == 0 ? 0 : (_totalActualTimeMorning/_totalPlanTimeMorning*100).toFixed(0)
                _totalTimePRCNight = _totalPlanTimeNight == 0 ? "" : (_totalActualTimeNight/_totalPlanTimeNight*100).toFixed(0)

                keikaku_rpt_tbl_lbl_time_morning_plan.innerText = _totalPlanTimeMorning.toFixed(2)
                keikaku_rpt_tbl_lbl_time_night_plan.innerText = _totalPlanTimeNight.toFixed(2)
                keikaku_rpt_tbl_lbl_time_morning_actual.innerText = _totalActualTimeMorning.toFixed(2)
                keikaku_rpt_tbl_lbl_time_night_actual.innerText = _totalActualTimeNight.toFixed(2)
                const _totalTimeMorningDifference = _totalActualTimeMorning-_totalPlanTimeMorning
                const _totalTimeNightDifference = _totalActualTimeNight-_totalPlanTimeNight
                keikaku_rpt_tbl_lbl_time_morning_difference.style.color = _totalTimeMorningDifference < 0 ? 'red' : 'black'
                keikaku_rpt_tbl_lbl_time_night_difference.style.color = _totalTimeNightDifference < 0 ? 'red' : 'black'
                keikaku_rpt_tbl_lbl_time_morning_difference.innerText = (_totalTimeMorningDifference).toFixed(2)
                keikaku_rpt_tbl_lbl_time_night_difference.innerText = (_totalTimeNightDifference).toFixed(2)
                keikaku_rpt_tbl_lbl_time_morning_percentage.innerText = _totalTimePRCMorning == 0 ? "" : _totalTimePRCMorning + '%'
                keikaku_rpt_tbl_lbl_time_night_percentage.innerText = _totalTimePRCNight == 0 ? "" : _totalTimePRCNight + '%'


                keikaku_rpt_tbl_lbl_qty_morning_plan.innerText = _totalPlanQtyMorning
                keikaku_rpt_tbl_lbl_qty_night_plan.innerText = _totalPlanQtyNight
                keikaku_rpt_tbl_lbl_qty_morning_actual.innerText = _totalActualQtyMorning
                keikaku_rpt_tbl_lbl_qty_night_actual.innerText = _totalActualQtyNight
                const _totalQtyMorningDifference = _totalActualQtyMorning-_totalPlanQtyMorning
                const _totalQtyNightDifference = _totalActualQtyNight-_totalPlanQtyNight
                keikaku_rpt_tbl_lbl_qty_morning_difference.style.color = _totalQtyMorningDifference < 0 ? 'red' : 'black'
                keikaku_rpt_tbl_lbl_qty_night_difference.style.color = _totalQtyNightDifference < 0 ? 'red' : 'black'
                keikaku_rpt_tbl_lbl_qty_morning_difference.innerText = (_totalQtyMorningDifference).toFixed(0)
                keikaku_rpt_tbl_lbl_qty_night_difference.innerText = (_totalQtyNightDifference).toFixed(0)

                keikaku_rpt_tbl_lbl_qty_morning_percentage.innerText = _totalPlanQtyMorning == 0 ? "" : (_totalActualQtyMorning/_totalPlanQtyMorning*100).toFixed(0) + '%'
                keikaku_rpt_tbl_lbl_qty_night_percentage.innerText = _totalPlanQtyNight == 0 ? "" : (_totalActualQtyNight/_totalPlanQtyNight*100).toFixed(0) + '%'


                keikaku_rpt_tbl_lbl_or_morning_plan.innerText = response.morningEfficiency	* 100  + '%'
                keikaku_rpt_tbl_lbl_or_night_plan.innerText = response.nightEfficiency	* 100  + '%'
                keikaku_rpt_tbl_lbl_or_morning_actual.innerText = _totalActualTimeMorning == 0 ? "" : ((_totalTimePRCMorning/100*(response.morningEfficiency)) *100).toFixed(0) + '%'
                keikaku_rpt_tbl_lbl_or_night_actual.innerText = _totalActualTimeNight == 0 ? "" : ((_totalTimePRCNight/100*(response.nightEfficiency)) *100).toFixed(0) + '%'

                let _totalPlanPointMorning = 0
                let _totalPlanPointNight = 0
                let _totalActualPointMorning = 0
                let _totalActualPointNight = 0
                response.dataMount.forEach((arrayItem) => {
                    _totalPlanPointMorning += Number(arrayItem['plan_morning_qty']*arrayItem['baseMount']);
                    _totalPlanPointNight += Number(arrayItem['plan_night_qty']*arrayItem['baseMount']);
                    _totalActualPointMorning += Number(arrayItem['morningOutput']*arrayItem['baseMount']);
                    _totalActualPointNight += Number(arrayItem['nightOutput']*arrayItem['baseMount']);
                })

                keikaku_rpt_tbl_lbl_poin_morning_plan.innerText = numeral(_totalPlanPointMorning).format(',')
                keikaku_rpt_tbl_lbl_poin_night_plan.innerText = numeral(_totalPlanPointNight).format(',')
                keikaku_rpt_tbl_lbl_poin_morning_actual.innerText = numeral(_totalActualPointMorning).format(',')
                keikaku_rpt_tbl_lbl_poin_night_actual.innerText = numeral(_totalActualPointNight).format(',')
                const _totalPointMorningDifference = _totalActualPointMorning-_totalPlanPointMorning
                const _totalPointNightDifference = _totalActualPointNight-_totalPlanPointNight
                keikaku_rpt_tbl_lbl_poin_morning_difference.style.color = _totalPointMorningDifference < 0 ? 'red' : 'black'
                keikaku_rpt_tbl_lbl_poin_night_difference.style.color = _totalPointNightDifference < 0 ? 'red' : 'black'
                keikaku_rpt_tbl_lbl_poin_morning_difference.innerText = numeral(_totalPointMorningDifference).format(',')
                keikaku_rpt_tbl_lbl_poin_night_difference.innerText = numeral(_totalPointNightDifference).format(',')

                keikaku_rpt_tbl_lbl_poin_morning_percentage.innerText = _totalPlanPointMorning == 0 ? "" : (_totalActualPointMorning/_totalPlanPointMorning*100).toFixed(0) + '%'
                keikaku_rpt_tbl_lbl_poin_night_percentage.innerText = _totalPlanPointNight == 0 ? "" : (_totalActualPointNight/_totalPlanPointNight*100).toFixed(0) + '%'

                let prodplanData = keikaku_prodplan_sso.getData()
                let prodplanDataLength = prodplanData.length - 5
                let _totalPlanChangeMorning=0
                let _totalPlanChangeNight=0
                let _totalActualChangeMorning=0
                let _totalActualChangeNight=0
                if(keikakuIsHW()) {
                    for(let i=21; i<prodplanDataLength; i+=12) {                    
                        if(prodplanData[i][2].includes('CHANGE MODEL') || prodplanData[i][2].includes('CHANGE TYPE')) {
                            for(let c=9; c<33; c++) {
                                if(prodplanData[i][c] > 0) {
                                    if(c<21) {
                                        _totalPlanChangeMorning++
                                    } else {
                                        _totalPlanChangeNight++
                                    }
                                }
                                if(prodplanData[i+1][c] > 0) {
                                    if(c<21) {
                                        _totalActualChangeMorning++
                                    } else {
                                        _totalActualChangeNight++
                                    }
                                }
                            }
                        }
                    }
                } else {
                    for(let i=17; i<prodplanDataLength; i+=8) {                    
                        if(prodplanData[i][2].includes('CHANGE MODEL')) {
                            for(let c=9; c<33; c++) {
                                if(prodplanData[i][c] > 0) {
                                    if(c<21) {
                                        _totalPlanChangeMorning++
                                    } else {
                                        _totalPlanChangeNight++
                                    }
                                }
                                if(prodplanData[i+1][c] > 0) {
                                    if(c<21) {
                                        _totalActualChangeMorning++
                                    } else {
                                        _totalActualChangeNight++
                                    }
                                }
                            }
                        }
                    }
                }
                
                keikaku_rpt_tbl_lbl_cm_morning_plan.innerText = _totalPlanChangeMorning
                keikaku_rpt_tbl_lbl_cm_night_plan.innerText = _totalPlanChangeNight
                keikaku_rpt_tbl_lbl_cm_morning_actual.innerText = _totalActualChangeMorning
                keikaku_rpt_tbl_lbl_cm_night_actual.innerText = _totalActualChangeNight
                keikaku_rpt_tbl_lbl_cm_morning_difference.innerText = (_totalActualChangeMorning-_totalPlanChangeMorning)
                keikaku_rpt_tbl_lbl_cm_night_difference.innerText = (_totalActualChangeNight-_totalPlanChangeNight)
                keikaku_rpt_tbl_lbl_cm_morning_percentage.innerText = _totalPlanChangeMorning == 0 ? "" : (_totalActualChangeMorning/_totalPlanChangeMorning*100).toFixed(0) + '%'
                keikaku_rpt_tbl_lbl_cm_night_percentage.innerText = _totalPlanChangeNight == 0 ? "" : (_totalActualChangeNight/_totalPlanChangeNight*100).toFixed(0) + '%'

            }, error: function(xhr, xopt, xthrow) {
                pThis.disabled = false
                alertify.error(xthrow)

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

    function keikakuDisplayProdplan(data, dataS, dataCalculation, dataModelChanges, dataInputHW, dataOutputHW, dataInput2HW) {
        let _newRowH = []
        _newRowH.push('')
        _newRowH.push('')
        _newRowH.push('')
        _newRowH.push('')
        _newRowH.push('')
        _newRowH.push('')
        _newRowH.push('')
        _newRowH.push('')
        _newRowH.push('')
        for(let c=9; c<(9+12+12+12); c++) {
            _newRowH.push(dataCalculation[c-3] == 'M' ? 'MTN' : dataCalculation[c-3])
        }
        let inputSS = [
                        ['', '', '' ,'','', '','','Date','','','','','','','','','','','','','',  '','','','','','','','','','','' ,'',  '','','','','','','','','','','' ,''],
                        ['', '', '' ,'','', '','','Time','','7','8','9','10','11','12','13','14','15','16','17','18',  '19','20','21','22','23','0','1','2','3','4','5' ,'6',  '7','8','9','10','11','12','13','14','15','16','17','18'],
                        ['', '', '' ,'','', '','','Change Model','','','','','','','','','','','','','',  '','','','','','','','','','','' ,'',  '','','','','','','','','','','' ,''],
                        ['SEQ', '', 'MODEL' ,'WO No','LOT', 'Production','S/T','Time', '','8','9','10','11','12','13','14','15','16','17','18',  '19','20','21','22','23','0','1','2','3','4','5' ,'6','7', '8','9','10','11','12','13','14','15','16','17','18','19'],
                        ['#', '', '' ,'','', 'Quantity','(H)','Working Time','Retention Time','','','','','','','','','','','','',  '','','','','','','','','','','' ,'',  '','','','','','','','','','','' ,''],
                        ['', 'Side', 'Type' ,'Spec','', 'Assy Code','Lot S/T','Efficiency','%','','','','','','','','','','','','',  '','','','','','','','','','','' ,'',  '','','','','','','','','','','' ,''],
                        ['', '', '' ,'','', '','(H)','Shift','TOTAL','Morning','','','','','','','','','','','',  'Night','','','','','','','','','','' ,'',  'Morning','','','','','','','','','','' ,'' ],
                        _newRowH
                    ];
        keikaku_prodplan_sso.setData(inputSS)
        const totalRowsMatrix = data.length
        const totalRowsSensor = dataS.length
        const totalRowsModelChanges = dataModelChanges.length
        const totalRowsInputHW = dataInputHW.length
        const totalRowsOutputHW = dataOutputHW.length

        let nomorUrut = 1;
        let nextRow = 10
        const _columnHeadActual = keikakugetColumnName(8)
        const _columnEndActual = keikakugetColumnName(32)
        const _columnStartActual = keikakugetColumnName(9)
        if(keikakuIsHW()) {
            for(let i=3; i<totalRowsMatrix; i++) {
                let _newRow1 = []
                let _newRow2 = []
                let _newRow3 = []
                let _newRow4 = []
                let _newRow5 = [] // input hw
                let _newRow6 = [] // input total hw
                let _newRow7 = [] // output hw
                let _newRow8 = [] // output total hw
                let _newRow9 = [] // Progress hw
                let _newRow10 = [] // progress total hw
                let _newRow11 = [] // input2 hw
                let _newRow12 = [] // input2 total hw
                if (data[i][0]) {
                    const _tempA = data[i][5].split('#')
                    const _model = _tempA[1]
                    const _wo_no = _tempA[2]
                    const _lot_size = _tempA[3]
                    const _production_qty = _tempA[4]
                    const _st = data[i][4]
                    const _specsSide = _tempA[0]
                    const _seq = _tempA[7]

                    _newRow2.push('')
                    _newRow2.push('')
                    _newRow2.push('')
                    _newRow2.push('')
                    _newRow2.push('')
                    _newRow2.push('')
                    _newRow2.push('')
                    _newRow2.push('Actual')
                    _newRow2.push(`=SUM(${_columnStartActual+(nextRow)}:${_columnEndActual+(nextRow)})`)

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
                    _newRow4.push(_specsSide)
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

                    for(let r=0; r<totalRowsModelChanges; r++) {
                        if(data[i][3] == dataModelChanges[r][3] && _specsSide == dataModelChanges[r][4] && _seq == dataModelChanges[r][1]) { // by job & seq
                            for(let c=9; c<(9+12+12+12); c++) {
                                const _theflag = dataModelChanges[r][c-3]
                                _newRow2.push(_theflag == '-' ? '' : _theflag)
                            }
                        }
                    }

                    inputSS.push(_newRow2) // actual change model
                    nextRow++
                    inputSS.push(_newRow3) //  plan prod
                    nextRow++
                    inputSS.push(_newRow4) // total plan prod
                    nextRow++

                    let _cellStartActual = _columnStartActual + (nextRow)
                    let _cellEndActual = _columnEndActual + (nextRow)
                    let _cellHeadActual = _columnHeadActual + (nextRow)
                    let _cellHeadProdplan = _columnHeadActual + (nextRow-2)

                    _newRow5.push('')
                    _newRow5.push('')
                    _newRow5.push('')
                    _newRow5.push('')
                    _newRow5.push('')
                    _newRow5.push('')
                    _newRow5.push('')
                    _newRow5.push('INPUT1')
                    _newRow5.push(`=IF(SUM(${_cellStartActual}:${_cellEndActual})="0","0", SUM(${_cellStartActual}:${_cellEndActual}))`)

                    _newRow6.push('')
                    _newRow6.push('')
                    _newRow6.push('')
                    _newRow6.push('')
                    _newRow6.push('')
                    _newRow6.push('')
                    _newRow6.push('')
                    _newRow6.push('Total,,')
                    _newRow6.push('')


                    for(let c=9; c<(9+12+12+12); c++) {
                        const _column = keikakugetColumnName(c)
                        const _cellActual = `${_column}${nextRow}`
                        const _columnStart = keikakugetColumnName(9) + (nextRow)
                        _newRow6.push(`=IF(${_cellActual}=0,"-",SUM(${_columnStart}:${_cellActual}))`)
                    }
                    nextRow+=2

                    _cellStartActual = _columnStartActual + (nextRow)
                    _cellEndActual = _columnEndActual + (nextRow)
                    _cellHeadActual = _columnHeadActual + (nextRow)
                    _cellHeadProdplan = _columnHeadActual + (nextRow-2)

                    _newRow11.push('')
                    _newRow11.push('')
                    _newRow11.push('')
                    _newRow11.push('')
                    _newRow11.push('')
                    _newRow11.push('')
                    _newRow11.push('')
                    _newRow11.push('INPUT2')
                    _newRow11.push(`=IF(SUM(${_cellStartActual}:${_cellEndActual})="0","0", SUM(${_cellStartActual}:${_cellEndActual}))`)
                    nextRow++

                    _newRow12.push('')
                    _newRow12.push('')
                    _newRow12.push('')
                    _newRow12.push('')
                    _newRow12.push('')
                    _newRow12.push('')
                    _newRow12.push('')
                    _newRow12.push('Total2')
                    _newRow12.push('')
                    for(let c=9; c<(9+12+12+12); c++) {
                        const _column = keikakugetColumnName(c)
                        const _cellActual = `${_column}${nextRow-1}`
                        const _columnStart = keikakugetColumnName(9) + (nextRow-1)
                        _newRow12.push(`=IF(${_cellActual}=0,"-",SUM(${_columnStart}:${_cellActual}))`)
                    }
                    nextRow++

                    _cellStartActual = _columnStartActual + (nextRow)
                    _cellEndActual = _columnEndActual + (nextRow)
                    _cellHeadActual = _columnHeadActual + (nextRow)
                    _cellHeadProdplan = _columnHeadActual + (nextRow-2)

                    _newRow7.push('')
                    _newRow7.push('')
                    _newRow7.push('')
                    _newRow7.push('')
                    _newRow7.push('')
                    _newRow7.push('')
                    _newRow7.push('')
                    _newRow7.push('OUTPUT')
                    _newRow7.push(`=IF(SUM(${_cellStartActual}:${_cellEndActual})="0","0", SUM(${_cellStartActual}:${_cellEndActual}))`)
                    nextRow++

                    _newRow8.push('')
                    _newRow8.push('')
                    _newRow8.push('')
                    _newRow8.push('')
                    _newRow8.push('')
                    _newRow8.push('')
                    _newRow8.push('')
                    _newRow8.push('Total,')
                    _newRow8.push('')
                    for(let c=9; c<(9+12+12+12); c++) {
                        const _column = keikakugetColumnName(c)
                        const _cellActual = `${_column}${nextRow-1}`
                        const _columnStart = keikakugetColumnName(9) + (nextRow-1)
                        _newRow8.push(`=IF(${_cellActual}=0,"-",SUM(${_columnStart}:${_cellActual}))`)
                    }
                    nextRow++

                   
                    _cellHeadActual = _columnHeadActual + (nextRow-4)
                    _cellHeadProdplan = _columnHeadActual + (nextRow-8)

                    _newRow9.push('')
                    _newRow9.push('')
                    _newRow9.push('')
                    _newRow9.push('')
                    _newRow9.push('')
                    _newRow9.push('')
                    _newRow9.push('')
                    _newRow9.push('Progress')
                    _newRow9.push(`=IF(${_cellHeadActual}="0","0",${_cellHeadActual}-${_cellHeadProdplan})`)
                    for(let c=9; c<(9+12+12+12); c++) {
                        const _column = keikakugetColumnName(c)
                        const _cellActual = `${_column}${nextRow-4}`
                        const _cellProdplan = `${_column}${(nextRow-9)}` 
                        _newRow9.push(`=IF(${_cellActual}="-","-", ${_cellActual}-${_cellProdplan})`)
                    }
                    nextRow++

                    _newRow10.push('')
                    _newRow10.push('')
                    _newRow10.push('')
                    _newRow10.push('')
                    _newRow10.push('')
                    _newRow10.push('')
                    _newRow10.push('')
                    _newRow10.push('Total.')
                    _newRow10.push('')
                    for(let c=9; c<(9+12+12+12); c++) {
                        const _column = keikakugetColumnName(c)
                        const _cellActual = `${_column}${nextRow-5}`                        
                        const _cellActualTotal = `${_column}${(nextRow-4)}`
                        const _columnStartProdplan = keikakugetColumnName(9) + (nextRow-9)
                        const _cellProdplan = `${_column}${(nextRow-9)}` 
                        _newRow10.push(`=IF(${_cellActual}=0,"-", ${_cellActualTotal}-(SUM(${_columnStartProdplan}:${_cellProdplan})))`)
                    }

                    nextRow++
                                        
                    for(let r=0; r<totalRowsInputHW; r++) {
                        if(data[i][3] == dataInputHW[r][3] && _specsSide == dataInputHW[r][4] && _seq == dataInputHW[r][1]) { // by job & seq
                            for(let c=9; c<(9+12+12+12); c++) {
                                _newRow5.push(dataInputHW[r][c-3])
                                _newRow11.push(dataInput2HW[r][c-3])
                            }
                            break;
                        }
                    }

                    inputSS.push(_newRow5)
                    inputSS.push(_newRow6)
                    inputSS.push(_newRow11)
                    inputSS.push(_newRow12)
                    
                    for(let r=0; r<totalRowsOutputHW; r++) {
                        if(data[i][3] == dataOutputHW[r][3] && _specsSide == dataOutputHW[r][4] && _seq == dataOutputHW[r][1]) { // by job & seq
                            for(let c=9; c<(9+12+12+12); c++) {
                                _newRow7.push(dataOutputHW[r][c-3])
                            }
                            break;
                        }
                    }

                    inputSS.push(_newRow7)
                    inputSS.push(_newRow8)
                    inputSS.push(_newRow9)
                    inputSS.push(_newRow10)
                } else {
                    let ChangeModelLabel = ''
                    let ChangeModelTime = ''
                    if(data[i][1]) {
                        ChangeModelLabel = keikakuCaptionChangesGenerator(data, i)
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
                                    const theMostPossibleColumn = keikakuMostUseTimeChangeMold(data[i]);
                                    if(_newRow1[8]==0 && theMostPossibleColumn == (c-3) ) {
                                        _newRow1.push(1)
                                        _newRow1[8]+=1
                                        inputSS[2][c] = 'C1'
                                    } else {
                                        _newRow1.push('')
                                    }
                                } else {
                                    _newRow1.push('')
                                }
                            }
                        }
                    }
                    inputSS.push(_newRow1)
                    nextRow++
                }
                nomorUrut++
            }
        } else {
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
                    const _specsSide = _tempA[0]
                    const _seq = _tempA[7]
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
                    _newRow4.push(_specsSide)
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
                    

                    _newRow2.push('')
                    _newRow2.push('')
                    _newRow2.push('')
                    _newRow2.push('')
                    _newRow2.push('')
                    _newRow2.push('')
                    _newRow2.push('')
                    _newRow2.push('Actual')
                    _newRow2.push(`=SUM(${_columnStartActual+(nextRow)}:${_columnEndActual+(nextRow)})`)

                    for(let r=0; r<totalRowsModelChanges; r++) {
                        if(data[i][3] == dataModelChanges[r][3] && _specsSide == dataModelChanges[r][4] && _seq == dataModelChanges[r][1]) { // by job & seq
                            for(let c=9; c<(9+12+12+12); c++) {
                                const _theflag = dataModelChanges[r][c-3]
                                _newRow2.push(_theflag == '-' ? '' : _theflag)
                            }
                        }
                    }

                    inputSS.push(_newRow2) // actual change model
                    nextRow++
                    inputSS.push(_newRow3) //  plan prod
                    nextRow++
                    inputSS.push(_newRow4) // total plan prod
                    nextRow++

                    const _cellStartActual = _columnStartActual + (nextRow)
                    const _cellEndActual = _columnEndActual + (nextRow)
                    const _cellHeadActual = _columnHeadActual + (nextRow)
                    const _cellHeadProdplan = _columnHeadActual + (nextRow-2)
                    _newRow5.push('')
                    _newRow5.push('')
                    _newRow5.push('')
                    _newRow5.push('')
                    _newRow5.push('')
                    _newRow5.push('')
                    _newRow5.push('')
                    _newRow5.push('Actual')
                    _newRow5.push(`=IF(SUM(${_cellStartActual}:${_cellEndActual})="0","0", SUM(${_cellStartActual}:${_cellEndActual}))`)

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
                    _newRow7.push(`=IF(${_cellHeadActual}="0","0",${_cellHeadActual}-${_cellHeadProdplan})`)

                    _newRow8.push('')
                    _newRow8.push('')
                    _newRow8.push('')
                    _newRow8.push('')
                    _newRow8.push('')
                    _newRow8.push('')
                    _newRow8.push('')
                    _newRow8.push('Total.')
                    _newRow8.push('')
                    
                    for(let c=9; c<(9+12+12+12); c++) {
                        const _column = keikakugetColumnName(c)
                        const _cellActual = `${_column}${nextRow}`
                        const _columnStart = keikakugetColumnName(9) + (nextRow)
                        if(c==9) {
                            _newRow6.push(`=${_cellActual}`)
                        } else {
                            _newRow6.push(`=IF(${_cellActual}=0,"-",SUM(${_columnStart}:${_cellActual}))`)
                        }

                        const _cellProdplan = `${_column}${(nextRow-2)}` 
                        _newRow7.push(`=IF(${_cellActual}=0,"-", ${_cellActual}-${_cellProdplan})`)

                        const _columnStartProdplan = keikakugetColumnName(9) + (nextRow-2)
                        const _cellActualTotal = `${_column}${(nextRow+1)}`
                        _newRow8.push(`=IF(${_cellActual}=0,"-", ${_cellActualTotal}-(SUM(${_columnStartProdplan}:${_cellProdplan})))`) 
                    }                  
                    
                    for(let r=0; r<totalRowsSensor; r++) {
                        if(data[i][3] == dataS[r][3] && _specsSide == dataS[r][4] && _seq == dataS[r][1]) { // by job & seq
                            for(let c=9; c<(9+12+12+12); c++) {
                                _newRow5.push(dataS[r][c-3])
                            }
                            break;
                        }
                    }                    
                    inputSS.push(_newRow5)
                    inputSS.push(_newRow6)
                    inputSS.push(_newRow7)
                    inputSS.push(_newRow8)
                    nextRow+=4
                } else {
                    let ChangeModelLabel = ''
                    let ChangeModelTime = ''
                    if(data[i][1]) {
                        ChangeModelLabel = keikakuCaptionChangesGenerator(data, i)
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
                                    const theMostPossibleColumn = keikakuMostUseTimeChangeMold(data[i]);
                                    if(_newRow1[8]==0 && theMostPossibleColumn == (c-3) ) {
                                        _newRow1.push(1)
                                        _newRow1[8]+=1
                                        inputSS[2][c] = 'C1'
                                    } else {
                                        _newRow1.push('')
                                    }
                                } else {
                                    _newRow1.push('')
                                }
                            }
                        }
                    }

                    inputSS.push(_newRow1)
                    nextRow++
                }

                nomorUrut++
            }
        }
        if(totalRowsMatrix>0) {
            keikaku_prodplan_sso.setData(inputSS)
        }
        _temp_dataComment.forEach((ai) => {
            if(ai['comment']) {
                keikaku_prodplan_sso.setComments(ai['cell_code'], ai['comment'])
            }
        })
    }

    function keikakuIsHW() {
        return (keikaku_line_input.value.substr(keikaku_line_input.value.length-1)=='3' && !keikaku_line_input.value.includes('AT')) || keikaku_line_input.value=='PS2' ? true : false
    }

    function keikakuCaptionChangesGenerator(paramData, paramY) {
        const currentRawInfo = paramData[paramY+1][5].split('#')
        const previousRawInfo = paramData[paramY-1][5].split('#')
        const currentAssyCode = paramData[paramY+1][0]
        const previousAssyCode = paramData[paramY-1][0]

        if(currentRawInfo[6].substr(0,4) == previousRawInfo[6].substr(0,4) && currentRawInfo[0]==previousRawInfo[0]) {
            return currentAssyCode == previousAssyCode ? '0' : 'CHANGE TYPE'
        } else {
            return 'CHANGE MODEL'
        }
    }

    function keikakuMostUseTimeChangeMold(data) {
        let _possibleColumn = 0
        let _tempMax = 0
        for(let i=6; i<=41; i++) {
            if(data[i]>_tempMax) {
                _tempMax = data[i]
                _possibleColumn = i
            }
        }
        return _possibleColumn
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
                const endPoint = '<?php echo base_url('Keikaku') ?>' + '?line=' + btoa(_line) + '&date=' + keikaku_date_input.value
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
            ['Sub Total', '', '=C1+C2+C3+C4+C5+C6+C7+C8+C9+C10+C11+C12+C13', '', '=E1+E2+E3+E4+E5+E6+E7+E8+E9+E10+E11+E12+E13', '', '=G1+G2+G3+G4+G5+G6+G7+G8+G9+G10+G11+G12+G13', '', '=I1+I2+I3+I4+I5+I6+I7+I8+I9+I10+I11+I12+I13', '','=K1+K2+K3+K4+K5+K6+K7+K8+K9+K10+K11+K12+K13',''],
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
                title : 'Remark',
                type : 'text',
                width:150,
            },
        ],
        allowInsertColumn : false,
        allowDeleteColumn : false,
        allowRenameColumn : false,
        allowDeleteRow : false,
        rowDrag:false,
        rowResize:true,
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
                    colspan : '2',
                },

            ],
        ],
        mergeCells : {
            A14 : [2,1],
            A15 : [2,1],
            C15 : [10,1],
        },
        updateTable: function(el, cell, x, y, source, value, id) {
            if(y===13 ) {
                cell.classList.add('readonly');
            }
            if(y===14) {
                cell.classList.add('readonly');
            }
        },
        onselection: function(instance, x1, y1, x2, y2, origin) {
            if([3,5,7,9].includes(x1) && y1<13) {
                let aRow = instance.jspreadsheet.getRowData(y1)
                keikaku_downtime_remark.value = aRow[x1]
            } else {
                keikaku_downtime_remark.value = '-'
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
                    remark : inputSS[i][11]
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
            url: "<?php echo $_ENV['APP_INTERNAL_API'] ?>keikaku/downtime",
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
            XCoordinate : keikakuEditXCoordinate.value,
            seq_data : keikakuEditHeadSeq.value
        }
        pThis.disabled = true
        $.ajax({
            type: "POST",
            url: "<?php echo $_ENV['APP_INTERNAL_API'] ?>keikaku/output",
            data: data,
            dataType: "json",
            success: function (response) {
                pThis.disabled = false
                alertify.success(response.message)
            }, error: function(xhr, xopt, xthrow) {
                keikaku_prodplan_sso.setValueFromCoords(tempX1, tempY1, keikakuBeforeValue, true)
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

    function keikakuBtnEditINPUTHW_ActualOnClick(pThis) {
        let qty = numeral(keikakuEditINPUTHWOutput.value).value()

        if(qty<0) {
            alertify.warning('Quantity is required')
            keikakuEditINPUTHWOutput.focus()
            return
        }

        const data = {
            line : keikaku_line_input.value,
            job : keikakuEditINPUTHW_WO.value,
            side : keikakuEditINPUTHW_Side.value,
            productionDate : keikakuEditDate.value,
            runningAtTime : keikakuEditINPUTHW_Hour.value,
            quantity : qty,
            user_id : uidnya,
            XCoordinate : keikakuEditINPUTHW_XCoordinate.value,
            seq_data : keikakuEditINPUTHW_HeadSeq.value
        }
        let theUrl = '-'
        switch(keikakuISHWEditingMode){
            case '1':
                theUrl = "<?php echo $_ENV['APP_INTERNAL_API'] ?>keikaku/input-hw";break
            case '2':
                theUrl = "<?php echo $_ENV['APP_INTERNAL_API'] ?>keikaku/output-hw";break
            case '3':
                theUrl = "<?php echo $_ENV['APP_INTERNAL_API'] ?>keikaku/input2-hw";break
        }
        pThis.disabled = true
        $.ajax({
            type: "POST",
            url: theUrl,
            data: data,
            dataType: "json",
            success: function (response) {
                pThis.disabled = false
                alertify.success(response.message)
                keikaku_load_data()
            }, error: function(xhr, xopt, xthrow) {
                keikaku_prodplan_sso.setValueFromCoords(tempX1, tempY1, keikakuBeforeValue, true)
                alertify.error(xthrow)
                pThis.disabled = false

                const respon = Object.keys(xhr.responseJSON)

                let msg = ''
                for (const item of respon) {
                    msg += `<p>${xhr.responseJSON[item]}</p>`
                }
                keikakuEditINPUTHWAlert.innerHTML = `<div class="alert alert-warning alert-dismissible fade show" role="alert">
                ${msg}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`
            }
        });
    }

    function keikakuSetDBComment(theComment) {
        
        const data = {
            line : keikaku_line_input.value,
            comment : theComment,
            cell_code : keikaku_cell_name.value,
            productionDate : keikakuEditDate.value,
            user_id : uidnya,
        }
        $.ajax({
            type: "POST",
            url: "<?php echo $_ENV['APP_INTERNAL_API'] ?>keikaku/comment",
            data: data,
            dataType: "json",
            success: function (response) {
                
            }, error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow)                   

                const respon = Object.keys(xhr.responseJSON)

                let msg = ''
                for (const item of respon) {
                    msg += `<p>${xhr.responseJSON[item]}</p>`
                }
                keikakuEditINPUTHWAlert.innerHTML = `<div class="alert alert-warning alert-dismissible fade show" role="alert">
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
    function keikakuEditINPUTHWOutputOnKeyPress(e) {
        if(e.key === 'Enter') {
            keikakuBtnEditINPUTHW_Actual.focus()
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
            url: "<?php echo $_ENV['APP_INTERNAL_API'] ?>keikaku/downtime",
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
                                keikaku_downtime_sso.setValue('L'+(i+1), response.data[s].remark, true)
                            }
                        }
                    }
                }
            }
        });
    }

    function keikaku_shift_input_on_change() {
        keikakuGetDownTime()
    }

    function keikaku_btn_set_blue() {
        for(let _y=tempY1; _y<=tempY2;_y++) {
            for(let _x=tempX1; _x<=tempX2; _x++) {
                let theCell = keikaku_data_sso.getCellFromCoords(_x,_y);
                theCell.style.cssText = 'background-color : #26f0fe;text-align: center'
            }
        }
    }

    function keikaku_btn_set_yellow() {
        for(let _y=tempY1; _y<=tempY2;_y++) {
            for(let _x=tempX1; _x<=tempX2; _x++) {
                let theCell = keikaku_data_sso.getCellFromCoords(_x,_y);
                theCell.style.cssText = 'background-color : #fafe0d;text-align: center'
            }
        }
    }

    function keikaku_btn_set_white() {
        for(let _y=tempY1; _y<=tempY2;_y++) {
            for(let _x=tempX1; _x<=tempX2; _x++) {
                let theCell = keikaku_data_sso.getCellFromCoords(_x,_y);
                theCell.style.cssText = 'background-color : #ffffff;text-align: center'
            }
        }
    }

    function keikakuBtnEditActualChangeModelOnClick(pThis,newValue) {
        const changeModelFlag = newValue
        const data = {
            line : keikaku_line_input.value,
            job : keikakuEditWO.value,
            side : keikakuEditSide.value,
            productionDate : keikakuEditDate.value,
            runningAtTime : keikakuEditHour.value,
            change_flag : changeModelFlag,
            user_id : uidnya,
            XCoordinate : keikakuEditXCoordinate.value,
            seq_data : keikakuEditHeadSeq.value
        }
        pThis.disabled = true
        $.ajax({
            type: "POST",
            url: "<?php echo $_ENV['APP_INTERNAL_API'] ?>keikaku/model-changes",
            data: data,
            dataType: "json",
            success: function (response) {
                pThis.disabled = false
                alertify.success(response.message)
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

    function keikaku_rpt_randomNum () {
        return Math.floor(Math.random() * (235 - 52 + 1) + 52)
    }

    function keikaku_rpt_randomRGB() {
        return `rgb(${keikaku_rpt_randomNum()}, ${keikaku_rpt_randomNum()}, ${keikaku_rpt_randomNum()})`
    }

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
        minDimensions: [26,5],
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
        minDimensions: [26,5],
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

    function keikaku_rpt_btn_summary_e_click() {
        keikaku_rpt_type.value = 'summary'
        $("#keikaku_rpt_summary_modal").modal('show')
    }

    function keikakuRptBtnExportSummary(pThis) {
        let theUrl = ''
        switch(keikaku_rpt_type.value) {
            case 'summary':
                theUrl = `<?php echo $_ENV['APP_INTERNAL_API'] ?>report/keikaku`
                break
            case 'production_output':
                theUrl = `<?php echo $_ENV['APP_INTERNAL_API'] ?>report/production-output`
                break
        }
        pThis.disabled = true
        $.ajax({
            type: "GET",
            url: theUrl,
            data: { dateFrom : keikaku_rpt_date_from.value , dateTo:keikaku_rpt_date_to.value },
            success: function (response) {
                pThis.disabled = false
                let waktuSekarang = moment().format('YYYY MMM DD, h_mm')
                const blob = new Blob([response], { type: "application/vnd.ms-excel" })
                const fileName = `${keikaku_rpt_type.value} from ${keikaku_rpt_date_from.value} to ${keikaku_rpt_date_to.value}.xlsx`
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

    function keikaku_rpt_btn_prod_output_e_click() {
        keikaku_rpt_type.value = 'production_output'
        $("#keikaku_rpt_summary_modal").modal('show')
    }

    function keikaku_rpt_btn_wo_history_e_click() {
        $("#keikaku_rpt_wo_modal").modal('show')
    }

    function keikaku_rpt_wo_no_e_keypress(e) {
        if(e.key==='Enter') {
            e.target.readOnly = true
            if(e.target.value.trim().length < 4) {
                e.target.readOnly = false
                alertify.warning(`At least 4 characters required`)
                return
            }
            $.ajax({
                type: "GET",
                url: "<?php echo $_ENV['APP_INTERNAL_API'] ?>keikaku/wo-run",
                data: {doc : e.target.value},
                dataType: "json",
                success: function (response) {
                    e.target.readOnly = false
                    let mydes = document.getElementById("keikaku_rpt_wo_tbl_div");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("keikaku_rpt_wo_tbl");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("keikaku_rpt_wo_tbl");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    let newrow, newcell, newText;
                    tableku2.innerHTML = '';
                    response.data.forEach((r, i) => {
                        newrow = tableku2.insertRow(-1);
                        newcell = newrow.insertCell(0);
                        newcell.innerHTML = r['production_date']
                        newcell.classList.add('text-center')
                        newcell = newrow.insertCell(1)
                        newcell.classList.add('text-center')
                        newcell.innerHTML = r['line_code']
                        newcell = newrow.insertCell(2)
                        newcell.classList.add('text-center')
                        newcell.innerHTML = r['wo_full_code']
                        newcell = newrow.insertCell(-1)
                        newcell.classList.add('text-center')
                        newcell.innerHTML = r['specs_side']
                        newcell = newrow.insertCell(-1)
                        newcell.classList.add('text-end')
                        newcell.innerHTML = numeral(r['lot_size']).format(',')
                        newcell = newrow.insertCell(-1)
                        newcell.classList.add('text-end')
                        newcell.innerHTML = numeral(r['plan_qty']).format(',')
                        newcell = newrow.insertCell(-1)
                        newcell.classList.add('text-end')
                        newcell.innerHTML = numeral(r['ok_qty']).format(',')
                    })
                    mydes.innerHTML = '';
                    mydes.appendChild(myfrag);
                }, error: function(xhr, xopt, xthrow) {
                    alertify.error(xthrow)
                    e.target.readOnly = false
                }
            });
        }
    }

    vkeikakuOperationMode = 0
    vkeikakuActiveTab = '#keikaku_tabRM'

    function keikaku_data_sheet_on_click() {
        vkeikakuActiveTab == '#keikaku_tabRM'
    }

    function keikaku_prodplan_sheet_on_click() {
        vkeikakuActiveTab == '#keikaku_tab_prodplan'
        if(vkeikakuOperationMode) {
            vkeikakuSimulate(keikaku_data_sso.getData().filter((data) => data[2].length && data[7].length > 1), keikaku_calculation_sso.getData())
        }
    }

    function keikaku_btn_trigger_template_eClick() {
        $("#keikaku_template_time_modal").modal('show')
    }

    function keikaku_btn_new_template_eclick() {
        let item = prompt("Please enter template name", "Ramadan/Normal");

        let currentOption = keikaku_template_name.innerHTML
        if (item != null) {
            currentOption += `<option value="${item}">${item}</option>`
            keikaku_template_name.innerHTML = currentOption
        }
    }

    function keikaku_template_btn_save_eclick(pThis) {
        const dataDetail = []
        let inputSS = keikaku_calculation_friday_temp_sso.getData()
        let inputSSCount = inputSS.length
        const templateName = keikaku_template_name.value.trim()
        if(templateName.length <=0) {
            alertify.warning('Template name is required')
            keikaku_template_name.focus()
            return 
        }

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

            let _theTime = c === 17 ? '23' : numeral(inputSS[6][c]).value()-1
            dataDetail.push({
                'calculation_at' : _theDate + ' ' + _theTime + ':00:00',
                'worktype1' : numeral(inputSS[0][c]).value(),
                'worktype2' : numeral(inputSS[1][c]).value(),
                'worktype3' : numeral(inputSS[2][c]).value(),
                'worktype4' : numeral(inputSS[3][c]).value(),
                'worktype5' : numeral(inputSS[4][c]).value(),
                'worktype6' : numeral(inputSS[5][c]).value(),
                'category' : 'f',
            })
        }

        inputSS = keikaku_calculation_non_friday_temp_sso.getData()
        inputSSCount = inputSS.length
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

            let _theTime = c === 17 ? '23' : numeral(inputSS[6][c]).value()-1
            dataDetail.push({
                'calculation_at' : _theDate + ' ' + _theTime + ':00:00',
                'worktype1' : numeral(inputSS[0][c]).value(),
                'worktype2' : numeral(inputSS[1][c]).value(),
                'worktype3' : numeral(inputSS[2][c]).value(),
                'worktype4' : numeral(inputSS[3][c]).value(),
                'worktype5' : numeral(inputSS[4][c]).value(),
                'worktype6' : numeral(inputSS[5][c]).value(),
                'category' : 'nf',
            })
        }

        const dataInput = {
            line_code : 'all',
            name : keikaku_template_name.value,
            user_id: uidnya,
            detail : dataDetail,            
        }        

        if(!confirm("Are you sure ?")) {
            return
        }
        pThis.disabled = true
        const div_alert = document.getElementById('keikaku-div-template-alert')
        $.ajax({
            type: "POST",
            url: "<?php echo $_ENV['APP_INTERNAL_API'] ?>keikaku/working-time",
            data: JSON.stringify(dataInput),
            dataType: "json",
            success: function (response) {
                pThis.disabled = false
                alertify.success(response.message)
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

    function keikaku_get_template_name() {
        $.ajax({
            type: "GET",
            url: "<?php echo $_ENV['APP_INTERNAL_API'] ?>keikaku/working-time-name",
            dataType: "json",
            success: function (response) {
                keikaku_template_name.innerHTML = ``
                let inputs = '';
                response.data.forEach((arrayItem) => {
                    inputs += `<option value="${arrayItem['name']}">${arrayItem['name']} ${arrayItem['status'] == '1' ? '(Active)' : ''}</option>`
                })
                keikaku_template_name.innerHTML = inputs
                keikaku_get_template(keikaku_template_name.value)
            }
        });
    }

    keikaku_get_template_name()

    function keikaku_get_template(name) {
        $.ajax({
            type: "GET",
            url: "<?php echo $_ENV['APP_INTERNAL_API'] ?>keikaku/working-time",
            data : { name : name },
            dataType: "json",
            success: function (response) {
                let worktype1 = ['NON OT'];
                let worktype2 = ['OT1'];
                let worktype3 = ['MENT NON OT'];
                let worktype4 = ['MENT OT'];
                let worktype5 = ['MENT NON OT'];
                let worktype6 = ['MENT OT'];

                let worktype1nf = ['NON OT'];
                let worktype2nf = ['OT1'];
                let worktype3nf = ['MENT NON OT'];
                let worktype4nf = ['MENT OT'];
                let worktype5nf = ['MENT NON OT'];
                let worktype6nf = ['MENT OT'];
                
                if(response.data.length > 0) {
                    response.data.forEach((arrayItem) => {
                        if(arrayItem['category'] == 'f') {
                            worktype1.push(Number.parseFloat(arrayItem['worktype1']).toFixed(2))
                            worktype2.push(Number.parseFloat(arrayItem['worktype2']).toFixed(2))
                            worktype3.push(Number.parseFloat(arrayItem['worktype3']).toFixed(2))
                            worktype4.push(Number.parseFloat(arrayItem['worktype4']).toFixed(2))
                            worktype5.push(Number.parseFloat(arrayItem['worktype5']).toFixed(2))
                            worktype6.push(Number.parseFloat(arrayItem['worktype6']).toFixed(2))
                        }
                        if(arrayItem['category'] == 'nf') {
                            worktype1nf.push(Number.parseFloat(arrayItem['worktype1']).toFixed(2))
                            worktype2nf.push(Number.parseFloat(arrayItem['worktype2']).toFixed(2))
                            worktype3nf.push(Number.parseFloat(arrayItem['worktype3']).toFixed(2))
                            worktype4nf.push(Number.parseFloat(arrayItem['worktype4']).toFixed(2))
                            worktype5nf.push(Number.parseFloat(arrayItem['worktype5']).toFixed(2))
                            worktype6nf.push(Number.parseFloat(arrayItem['worktype6']).toFixed(2))
                        }
                    })
                } else {

                }

                keikaku_calculation_friday_temp_sso.setData([
                        worktype1,
                        worktype2,
                        worktype3,
                        worktype4,
                        worktype5,
                        worktype6,
                        ['Hour', ...Array.from({length: 16}, (_, i) => i + 8), ...Array.from({length: 8}, (_, i) => i), ...Array.from({length: 12}, (_, i) => i + 8)],                        
                    ])
                keikaku_calculation_non_friday_temp_sso.setData([
                        worktype1nf,
                        worktype2nf,
                        worktype3nf,
                        worktype4nf,
                        worktype5nf,
                        worktype6nf,
                        ['Hour', ...Array.from({length: 16}, (_, i) => i + 8), ...Array.from({length: 8}, (_, i) => i), ...Array.from({length: 12}, (_, i) => i + 8)],                        
                    ])
            }
        });
    }

    function keikaku_template_name_on_change() {
        keikaku_get_template(keikaku_template_name.value)
    }

    function keikaku_btn_activate_template_eclick(pThis) {
        if(!confirm("Are you sure ?")) {
            return
        }
        pThis.disabled = true
        const div_alert = document.getElementById('keikaku-div-template-alert')
        $.ajax({
            type: "PUT",
            url: `<?php echo $_ENV['APP_INTERNAL_API'] ?>keikaku/working-time/${btoa(keikaku_template_name.value)}`,
            dataType: "json",
            success: function (response) {
                pThis.disabled = false
                alertify.success(response.message)
                keikaku_get_template_name()                                 
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

</script>
