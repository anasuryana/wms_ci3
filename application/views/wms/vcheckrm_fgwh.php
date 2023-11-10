<style type="text/css">
    thead tr.first th,
    thead tr.first td {
        position: sticky;
        top: 0;
    }

    thead tr.second th,
    thead tr.second td {
        position: sticky;
        top: 26px;
    }
</style>
<div style="padding: 10px">
    <div class="container-fluid">
        <div class="row" id="checksbb_stack1">
            <div class="col-md-12 mb-1 text-center">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" >Search by</span>
                    <select class="form-select" id="checksbb_cmb_filter" onchange="checksbb_cmb_filter_eChange()">
                        <option value="wh">AFWH3 Only</option>
                        <option value="tx">TX ID</option>
                        <option value="job">Job</option>
                        <option value="us_qc">Unscanned QC</option>
                    </select>
                    <input type="text" class="form-control" id="checksbb_txt_search" readonly>
                </div>
            </div>
        </div>
        <div class="row" id="checksbb_stack2">
            <div class="col-md-4 mb-1">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary" type="button" id="checksbb_btn_sync" onclick="checksbb_btn_sync_e_click()" title="Refresh"><i class="fas fa-sync"></i></button>
                    <button class="btn btn-primary" type="button" id="checksbb_btn_calc" onclick="checksbb_btn_calc_e_click()" >Calculate selected data</button>
                </div>
                <span id="checksbb_lblinfo" class="badge bg-info"></span>
            </div>
            <div class="col-md-4 mb-1 text-center">
                <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bars"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" id="checksbb_btn_reset_job" href="#" onclick="checksbb_btn_reset_job_eClick()">Reset Calculation per Job</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4 mb-1 text-end">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-warning" type="button" id="checksbb_btn_simulate" onclick="checksbb_btn_simulate_e_click()" >Resimulation</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="checksbb_divku">
                    <table id="checksbb_tbl" class="table table-striped table-bordered table-sm table-hover" style="font-size:85%">
                        <thead class="table-light">
                            <tr class="first">
                                <th style="cursor:pointer" class="align-middle" onclick="checksbb_e_filterjob()">Job Number <i class="fas fa-filter float-end text-secondary"></i></th>
                                <th style="cursor:pointer" class="align-middle" onclick="checksbb_e_filteritem()">Item Code <i class="fas fa-filter float-end text-secondary"></i></th>
                                <th class="align-middle">Item Name</th>
                                <th class="align-middle text-center">ID</th>
                                <th class="align-middle text-end">Del. Qty</th>
                                <th class="align-middle">Raw Material Status</th>
                                <th class="align-middle"></th>
                                <th class="align-middle" title="reset"></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Total</strong></td>
                                <td class="text-end"><strong id="checksbbTotalQty"></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="checksbb_w_jobreq"  class="easyui-window" title="Simulation"
    data-options="modal:false,closed:true,iconCls:'icon-analyze',collapsible:true,minimizable:false,
    top: 305,
    left: 0,
    onClose:function(){
        $('#checksbb_reqtbl_rm tbody').empty();
        }"
    style="width:600px;height:350px;padding:5px;" >
    <div style="padding:1px" >
        <div class="container">
            <div class="row">
                <div class="col-md-6 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >Job Number</span>
                        <input type="text" class="form-control" id="checksbb_req_job" readonly>
                    </div>
                </div>
                <div class="col-md-3 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >Lot Size</span>
                        <input type="text" class="form-control" id="checksbb_req_itmqty" readonly>
                    </div>
                </div>
                <div class="col-md-3 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text">Sim QT</span>
                        <input type="text" class="form-control" id="checksbb_req_simqt" readonly>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-5 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >Assy Code</span>
                        <input type="text" class="form-control" id="checksbb_req_itmcd" readonly>
                    </div>
                </div>
                <div class="col-md-5 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >Assy Name</span>
                        <input type="text" class="form-control" id="checksbb_req_itmnm" readonly>
                    </div>
                </div>
                <div class="col-md-2 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >Rev</span>
                        <input type="text" class="form-control" id="checksbb_req_bomrev" readonly>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="table-responsive" id="checksbb_reqdiv_rm">
                        <table id="checksbb_reqtbl_rm" class="table table-hover table-sm table-bordered" style="width:100%;font-size:81%">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-end" colspan="5">Total :</th>
                                    <th class="text-end"><b><span id="checksbb_req_ttl_per"></span></b></th>
                                    <th class="text-end" colspan="4">
                                        <div class="btn-group btn-group" role="group" aria-label="Filter">
                                            <button type="button" class="btn btn-outline-secondary" id="checksbb_req_btn_all" onclick="checksbb_req_btn_all_e_click()">All</button>
                                            <button type="button" class="btn btn-outline-secondary" id="checksbb_req_btn_discrep" onclick="checksbb_req_btn_discrep_e_click()">Discrepancy</button>
                                        </div>
                                    </th>
                                    <th rowspan="2" class="align-middle">Action</th>
                                </tr>
                                <tr>
                                    <th>Line No</th>
                                    <th>Process</th>
                                    <th class="text-center">F/R</th>
                                    <th class="text-center">MC</th>
                                    <th class="text-center">MCZ</th>
                                    <th class="text-center">PER</th>
                                    <th>Item Code</th>
                                    <th>Item Name</th>
                                    <th class="text-end">QTY</th>
                                    <th class="text-center">Kind</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="table-responsive" id="checksbb_reqdiv_rm_resume">
                        <table id="checksbb_reqtbl_rm_resume" class="table table-hover table-sm table-bordered" style="width:100%;font-size:81%">
                            <thead class="table-light">
                                <tr>
                                    <th colspan="5">Resume</th>
                                </tr>
                                <tr>
                                    <th>Line</th>
                                    <th>Process</th>
                                    <th>MC</th>
                                    <th>MCZ</th>
                                    <th class="text-end">Req. Per</th>
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
<div id="checksbb_w_psnjob"  class="easyui-window" title="Detail of PSN"
    data-options="modal:false,closed:true,iconCls:'icon-analyze',collapsible:true, minimizable:false,
    right: 0,
    top: 0,
    onClose:function(){
        $('#checksbb_psn_list').tagbox('setValues', []);
    }"
    style="width:100%;height:300px;padding:5px;">
    <div style="padding:1px" >
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 mb-1">
                    <input type="text" style="width:100%" id="checksbb_psn_list" readonly>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="table-responsive" id="checksbb_divdetailpsn">
                        <table id="checksbb_tbldetailpsn" class="table table-hover table-sm table-bordered" style="width:100%;font-size:81%">
                            <thead class="table-light">
                                <tr>
                                    <th>DOC NO</th>
                                    <th>PSN NO</th>
                                    <th>LINE NO</th>
                                    <th>Process</th>
                                    <th>FR</th>
                                    <th>Category</th>
                                    <th class="text-center">MC</th>
                                    <th class="text-center">MCZ</th>
                                    <th class="text-center">S/M</th>
                                    <th class="text-center">Item Code</th>
                                    <th class="text-center">Item Name</th>
                                    <th class="text-center">Kind</th>
                                    <th class="text-end">REQ QTY</th>
                                    <th class="text-end">ACT QTY</th>
                                    <th class="text-end">RTN QTY</th>
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
<div id="checksbb_w_calculation"  class="easyui-window" title="Calculation Result"
    data-options="modal:false,closed:true,iconCls:'icon-analyze',collapsible:true,minimizable:false,
    right:601,
    top:305,
    onClose:function(){
        $('#checksbb_caltbl_rm tbody').empty();
        $('#checksbb_w_psnjob').window('close');
        $('#checksbb_w_jobreq').window('close');
        }"
    style="width:600px;height:350px;padding:10px;" >
    <div style="padding:1px" >
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >ID Sample</span>
                        <input type="text" class="form-control" id="checksbb_cal_ID" readonly>
                    </div>
                </div>
                <div class="col-md-6 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >Qty</span>
                        <input type="text" class="form-control" id="checksbb_cal_ID_qty" readonly>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="btn-group btn-group-sm">
                        <button title="Copy to clipboard" onclick="checksbb_cal_btnclip()" class="btn btn-success" ><i class="fas fa-clipboard"></i></button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="table-responsive" id="checksbb_caldiv_rm">
                        <table id="checksbb_caltbl_rm" class="table table-hover table-sm table-bordered" style="width:100%;font-size:81%">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-end" colspan="5">Total :</th>
                                    <th class="text-end"><b><span id="checksbb_cal_ttl_per"></span></b></th>
                                    <th colspan="2"></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th>Line No</th>
                                    <th>Process</th>
                                    <th class="text-center">F/R</th>
                                    <th class="text-center">MC</th>
                                    <th class="text-center">MCZ</th>
                                    <th class="text-center">PER</th>
                                    <th>Item Code</th>
                                    <th>Item Name</th>
                                    <th class="text-end">DLV QTY</th>
                                    <th class="text-center">Kind</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="table-responsive" id="checksbb_caldiv_rm_resume">
                        <table id="checksbb_caltbl_rm_resume" class="table table-hover table-sm table-bordered" style="width:100%;font-size:81%">
                            <thead class="table-light">
                                <tr>
                                    <th colspan="5">Resume</th>
                                </tr>
                                <tr>
                                    <th>Line</th>
                                    <th>Process</th>
                                    <th>MC</th>
                                    <th>MCZ</th>
                                    <th class="text-end">Result. PER</th>
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
<div id="checksbb_w_psnfilter"  class="easyui-window" title="Action"
    data-options="modal:true,closed:true,iconCls:'icon-analyze',collapsible:true, minimizable:false,
    right: 0,
    top: 0,
    cls: 'c6',
    onClose:function(){
        $('#checksbb_alert').html('');
        }
    "
    style="width:100%;height:500px;padding:5px;">
    <div style="padding:1px" >
        <div class="container-fluid">
            <input type="hidden" id="checksbb_txt_line">
            <input type="hidden" id="checksbb_txt_mc">
            <input type="hidden" id="checksbb_txt_mcz">
            <input type="hidden" id="checksbb_txt_per">
            <div class="row">
                <div class="col-md-12 mb-1 text-center">
                    <button class="btn btn-sm btn-primary" id="checksbb_add" onclick="checksbb_e_addto_cal()">Add selected data to calculation</button>
                    <button class="btn btn-sm btn-outline-primary" onclick="checksbb_e_flagok()">Flag as OK</button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-1 text-center" id="checksbb_alert">

                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-1">
                    <table id="checksbb_tblpsnfilter" class="table table-sm table-striped table-bordered" style="width:100%;cursor:pointer">
                        <thead class="table-light">
                            <tr>
                                <th>Doc. No</th><!-- 0 -->
                                <th>PSN No</th><!-- 1 -->
                                <th>Process</th><!-- 2 -->
                                <th>Line</th><!-- 3 -->
                                <th>FR</th><!-- 4 -->
                                <th>Category</th><!-- 5 -->
                                <th>MC</th><!-- 6 -->
                                <th>MCZ</th><!-- 7 -->
                                <th>Item Code</th><!-- 8 -->
                                <th>Item Name</th><!-- 9 -->
                                <th>Kind</th><!-- 10 -->
                                <th>ACT QTY</th><!-- 11 -->
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
<div id="checksbb_w_psnfilter_special"  class="easyui-window" title="Special Acceptance"
    data-options="modal:true,closed:true,iconCls:'icon-analyze',collapsible:true, minimizable:false,
    right: 0,
    top: 0,
    cls: 'c6',
    onClose:function(){
        $('#checksbb_alert_special').html('');
        }
    "
    style="width:100%;height:500px;padding:5px;">
    <div style="padding:1px" >
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 mb-1">
                    <button class="btn btn-sm btn-primary" id="checksbb_add_special" onclick="checksbb_e_addto_cal_special()">Add selected data to calculation</button>
                </div>
                <div class="col-md-6 mb-1">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Qty</span>
                        <input type="text" readonly class="form-control" id="checksbb_txt_qty_special">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-1 text-center" id="checksbb_alert_special">

                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-1">
                    <table id="checksbb_tblpsnfilter_special" class="table table-sm table-striped table-bordered" style="width:100%;cursor:pointer">
                        <thead class="table-light">
                            <tr>
                                <th>Doc. No</th><!-- 0 -->
                                <th>PSN No</th><!-- 1 -->
                                <th>Process</th><!-- 2 -->
                                <th>Line</th><!-- 3 -->
                                <th>FR</th><!-- 4 -->
                                <th>Category</th><!-- 5 -->
                                <th>MC</th><!-- 6 -->
                                <th>MCZ</th><!-- 7 -->
                                <th>Item Code</th><!-- 8 -->
                                <th>Item Name</th><!-- 9 -->
                                <th>Kind</th><!-- 10 -->
                                <th>ACT QTY</th><!-- 11 -->
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
<div class="modal fade" id="checksbb_MODFILTERJOB">
    <div class="modal-dialog">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Filter</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12 mb-1 p-1">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text" >Job Number</span>
                        <select class="form-select" id="checksbb_cmb_job" multiple>

                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" onclick="checksbb_e_filter_data()">OK</button>
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="checksbb_MODEDITQTYCAL">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Editing Calculation QTY</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="alert alert-warning" role="alert">
                        Authorized user only. Just be careful
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-1 p-1 text-center">
                    <span class="badge bg-info" id="checksbb_caltbl_rm_edit_lblinfo"></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-1 p-1">
                    <div class="table-responsive" id="checksbb_caltbl_rm_edit_div">
                        <table id="checksbb_caltbl_rm_edit" class="table table-hover table-sm table-bordered" style="width:100%;font-size:81%">
                            <thead class="table-light">
                                <tr>
                                    <th>Line No</th>
                                    <th>Process</th>
                                    <th class="text-center">F/R</th>
                                    <th class="text-center">MC</th>
                                    <th class="text-center">MCZ</th>
                                    <th class="text-center">PER</th>
                                    <th>Item Code</th>
                                    <th>Item Name</th>
                                    <th class="text-end">DLV QTY</th>
                                    <th class="text-center">Kind</th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-1 p-1">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text" >Old Qty</span>
                        <input type="text" class="form-control" id="checksbb_oldqty" readonly>
                    </div>
                </div>
                <div class="col-md-6 mb-1 p-1">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text" >New Qty</span>
                        <input type="text" class="form-control" id="checksbb_newqty">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" onclick="checksbb_e_concfirm_edit_qtycal()">OK</button>
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="checksbb_MODFILTERITEM">
    <div class="modal-dialog">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Filter</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12 mb-1 p-1">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text" >Item Code</span>
                        <select class="form-select" id="checksbb_cmb_item" multiple>

                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" onclick="checksbb_e_filter_data()">OK</button>
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="checksbb_MODRESIMULATE">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Resimulation</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col text-center">
                    <h1>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-emoji-frown" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path d="M4.285 12.433a.5.5 0 0 0 .683-.183A3.498 3.498 0 0 1 8 10.5c1.295 0 2.426.703 3.032 1.75a.5.5 0 0 0 .866-.5A4.498 4.498 0 0 0 8 9.5a4.5 4.5 0 0 0-3.898 2.25.5.5 0 0 0 .183.683zM7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5zm4 0c0 .828-.448 1.5-1 1.5s-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5z"/>
                        </svg>
                    </h1>
                    Sorry this function is no longer available. We still need to display this view as history that it used to be available.
                    <br>
                    (for customs purpose and also as an evidence)
                </div>
            </div>
            
        </div>
        <div class="modal-footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 mb-1">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text" >Reason</span>
                            <select class="form-select" id="checksbb_cmb_remark">
                                <option value="-">-</option>
                                <option value="SIMULATION WAS DELETED">SIMULATION WAS DELETED</option>
                                <option value="CIMS DATA UPDATE">CIMS DATA UPDATE</option>
                                <option value="OTHER">OTHER</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 mb-1">
                        
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="checksbb_Emergency">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Emergency</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col-md-4 mb-1 p-1">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text" >ID</span>
                        <input type="text" class="form-control" id="checksbb_txtemer_ID" readonly>
                    </div>
                </div>
                <div class="col-md-4 mb-1 p-1">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text" >Job</span>
                        <input type="text" class="form-control" id="checksbb_txtemer_JOB" readonly>
                    </div>
                </div>
                <div class="col-md-4 mb-1 p-1">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text" >Qty</span>
                        <input type="text" class="form-control" id="checksbb_txtemer_QTY" readonly>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-1 p-1">
                <textarea class="form-control" id="checksbb_txtemer_msg" readonly></textarea>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary btn-sm" id="checksbb_btnemer_calculate" onclick="checksbb_btnemer_calculate_eCK(this)">Calculate</button>
            <button type="button" class="btn btn-warning btn-sm" id="checksbb_btnemer_remove_calculation" onclick="checksbb_btnemer_remove_calculation_eCK(this)">Remove Calculation</button>
        </div>
      </div>
    </div>
</div>
<script>
    var checksbb_DTABLE_psn;
    var checksbb_docno;
    var checksbb_procd;
    var checksbb_req_rows;

    function checksbb_cal_btnclip() {
        cmpr_selectElementContents(document.getElementById('checksbb_caltbl_rm'))
        document.execCommand("copy")
        alert("Copied")
    }
    function checksbb_e_filterjob(){
        $("#checksbb_MODFILTERJOB").modal('show');
    }
    function checksbb_e_filteritem(){
        $("#checksbb_MODFILTERITEM").modal('show');
    }
    function checksbb_cmb_filter_eChange(){
        const txtsearch = document.getElementById('checksbb_txt_search');
        const cmbsearch = document.getElementById('checksbb_cmb_filter');
        txtsearch.value='';
        if(cmbsearch.value=='wh' || cmbsearch.value=='us_qc'){
            txtsearch.readOnly = true;
        } else {
            txtsearch.readOnly = false;
            txtsearch.focus()
        }
    }
    function checksbb_ck_recalcualte(e, pjob){
        let tabel_PLOT = document.getElementById("checksbb_tbl");
        let tabel_PLOT_body0 = tabel_PLOT.getElementsByTagName("tbody")[0];
        const ttlrows = tabel_PLOT_body0.getElementsByTagName('tr').length;
        let serlist = [];
        let joblist = [];
        let qtylist = [];
        for(let i=0;i<ttlrows;i++){
            let sjob = tabel_PLOT_body0.rows[i].cells[0].innerText;
            if(sjob==pjob){
                if(tabel_PLOT_body0.rows[i].cells[6].getElementsByTagName('input')[0].disabled){
                    serlist.push(tabel_PLOT_body0.rows[i].cells[3].innerText);
                    joblist.push(pjob);
                    qtylist.push(numeral(tabel_PLOT_body0.rows[i].cells[4].innerText).value());
                }
            }
        }
        if(serlist.length>0){
            if(confirm("Are you sure ?")){
                document.getElementById('checksbb_lblinfo').innerText = "Please wait.,,";

                $.ajax({
                    type: "post",
                    url: "<?=base_url('SER/resetcalculation')?>",
                    data: {injob: joblist, inser: serlist, inqty: qtylist},
                    dataType: "json",
                    success: function (response) {
                        if(response.status[0].cd=='1'){
                            checksbb_btn_sync_e_click();
                        } else {
                            alertify.message(response.status[0].msg);
                            document.getElementById('checksbb_lblinfo').innerText = "";
                        }
                    }, error(xhr, xopt, xthrow){
                        alertify.error(xthrow);
                    }
                });
            }
        }
    }

    $("#checksbb_divku").css('height', $(window).height()
    -document.getElementById('checksbb_stack1').offsetHeight
    -document.getElementById('checksbb_stack2').offsetHeight
    -100);
    function checksbb_cksi_e_click(e,elem,pjob){
        if(elem.checked){
            let tabel_PLOT = document.getElementById("checksbb_tbl");
            let tabel_PLOT_body0 = tabel_PLOT.getElementsByTagName("tbody")[0];
            let ttlrows = tabel_PLOT_body0.getElementsByTagName('tr').length;
            let ttlchecked =0;
            for(let i=0;i<ttlrows;i++){
                let sjob = tabel_PLOT_body0.rows[i].cells[0].innerText;
                let qty = numeral(tabel_PLOT_body0.rows[i].cells[4].innerText).value();
                if(sjob==pjob && qty >0){
                    if(!tabel_PLOT_body0.rows[i].cells[6].getElementsByTagName('input')[0].disabled && ttlchecked <67){
                        tabel_PLOT_body0.rows[i].cells[6].getElementsByTagName('input')[0].checked=true;
                        ttlchecked++;
                    }
                }
            }
        }
    }

    function checksbb_e_filter_data(){
        let mjobs = $('#checksbb_cmb_job').val();
        let mitems = $('#checksbb_cmb_item').val();
        $("#checksbb_tbl tbody").empty();
        document.getElementById('checksbb_lblinfo').innerText="Please wait";
        $("#checksbb_MODFILTERJOB").modal('hide');
        $("#checksbb_MODFILTERITEM").modal('hide');
        $.ajax({
            type: "get",
            url: "<?=base_url('SER/get_check_susuan_bb_filter')?>",
            data: {injobs: mjobs, initems: mitems},
            dataType: "json",
            success: function (response) {
                let ttlrows = response.data.length;
                let mydes = document.getElementById("checksbb_divku");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("checksbb_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("checksbb_tbl");
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                let newrow, newcell, newText;
                tableku2.innerHTML='';
                document.getElementById('checksbb_lblinfo').innerText= ttlrows+" row(s) found";
                let joblist = [];
                let itemcdlist = [];
                let totalQty = 0
                for(let i=0; i<ttlrows; i++){
                    totalQty += response.data[i].SER_QTY*1
                    if(!joblist.includes(response.data[i].SER_DOC)){
                        joblist.push(response.data[i].SER_DOC);
                    }
                    if(!itemcdlist.includes(response.data[i].SER_ITMID)){
                        itemcdlist.push(response.data[i].SER_ITMID);
                    }
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0);
                    newcell.ondblclick = function(){

                    };
                    newcell.innerHTML = response.data[i].SER_DOC
                    newcell = newrow.insertCell(1);
                    newcell.innerHTML = response.data[i].SER_ITMID
                    newcell = newrow.insertCell(2);
                    newcell.innerHTML = response.data[i].MITM_ITMD1
                    newcell = newrow.insertCell(3)
                    newcell.style.cssText = "text-align:center";
                    newcell.innerHTML = response.data[i].ITH_SER
                    newcell.ondblclick = () => {
                        alertify.message('weellll')
                    }
                    newcell = newrow.insertCell(4);
                    newcell.style.cssText = "text-align:right";
                    newcell.innerHTML = numeral(response.data[i].SER_QTY).format(',')
                    newcell = newrow.insertCell(5);
                    if(Number(response.data[i].CALPER)==0){
                        newText = document.createTextNode('Not calculated yet');
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(6);
                        newText = document.createElement('input');
                        newText.setAttribute('type', 'checkbox');
                        newText.onclick = function(){checksbb_cksi_e_click(event, this,response.data[i].SER_DOC )};
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(7);
                        newText = document.createTextNode('');
                        newcell.appendChild(newText);
                    } else {
                        newText = document.createTextNode('Edit required');
                        newcell.onclick = function(){checksbb_e_compare(response.data[i].SER_DOC, response.data[i].SER_ITMID, response.data[i].MITM_ITMD1, response.data[i].ITH_SER,response.data[i].SER_QTY );};
                        newcell.style.cssText = "cursor:pointer";
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(6);
                        newText = document.createElement('input');
                        newText.setAttribute('type', 'checkbox');
                        newText.disabled = true;
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(7);
                        newcell.onclick = function(){checksbb_ck_recalcualte(event,response.data[i].SER_DOC)};
                        newcell.style.cssText = "cursor:pointer";
                        newText = document.createElement('span');
                        newText.classList.add('badge','bg-warning');
                        newText.innerText = "Reset calculation ?";
                        newcell.appendChild(newText);
                    }
                }
                checksbbTotalQty.innerText = totalQty
                mydes.innerHTML='';
                mydes.appendChild(myfrag);
                let tohtml = '<option value="-">-</option>';
                let joblist_c = joblist.length;
                let itemcdlist_c = itemcdlist.length;
                for(let i=0; i< joblist_c; i++){
                    tohtml += "<option value='"+joblist[i]+"'>"+joblist[i]+"</option>";
                }
                $("#checksbb_cmb_job").html(tohtml);
                tohtml = '<option value="-">-</option>';
                for(let i=0; i< itemcdlist_c; i++){
                    tohtml += "<option value='"+itemcdlist[i]+"'>"+itemcdlist[i]+"</option>";
                }
                $("#checksbb_cmb_item").html(tohtml);
            }, error(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    }

    function checksbb_e_addto_cal_special() {
        const datanya = checksbb_DTABLE_psn.rows( { selected: true } ).nodes()[0];
        if (typeof datanya == 'undefined'){
            alert("there is no selected data");
        } else {
            const ttluse = numeral(document.getElementById('checksbb_cal_ttl_per').innerText).value();
            const idsample = document.getElementById("checksbb_cal_ID").value;
            const job = document.getElementById("checksbb_req_job").value;
            const line = document.getElementById("checksbb_txt_line").value;
            const mc = document.getElementById("checksbb_txt_mc").value;
            const mcz = document.getElementById("checksbb_txt_mcz").value;
            const per = document.getElementById("checksbb_txt_per").value;
            const psn = datanya.cells[1].innerText.trim();
            const itmcat = datanya.cells[5].innerText.trim();
            const fr = datanya.cells[4].innerText.trim();
            const itemcd = datanya.cells[8].innerText.trim();
            const actqty = numeral(datanya.cells[11].innerText).value()
            const qtytosave = document.getElementById('checksbb_txt_qty_special').value
            const qtyFG = document.getElementById('checksbb_cal_ID_qty').value
            const strdis = "Add below data <br>"+
                "<b>PSN</b>: "+psn+
                "<br><b>Process</b>: "+checksbb_procd+
                "<br><b>Line</b>: "+line+
                "<br><b>Category</b>: "+itmcat+
                "<br><b>F/R</b>: "+fr+
                "<br><b>Job</b>: "+job+
                "<br><b>PER</b>: "+per+
                "<br><b>MC</b>: "+mc+
                "<br><b>MCZ</b>: "+mcz+
                "<br><b>Item Code</b>: "+itemcd+
                "<br><b>Qty</b>: "+qtytosave+
                "<br>=======TO======="+
                "<br><b>ID</b>: "+idsample;

            $.messager.confirm('Decide', strdis, function(r){
                if (r){
                    document.getElementById('checksbb_add_special').disabled=true;
                    $("#checksbb_alert").html('<span class="badge bg-info">Please wait</span>');
                    $.ajax({
                        type: "post",
                        url: "<?=base_url('SER/add_rm_to_boxID_special')?>",
                        data: {inpsn: psn, inprocd: checksbb_procd, inline: line, initmcat: itmcat
                        ,infr: fr, injob: job, inactqt: actqty, inper: per, inmc: mc, inmcz: mcz
                        ,initmcd: itemcd, inttluse: ttluse, inqtytosave: qtytosave
                        ,inser: idsample, inserqty: qtyFG},
                        dataType: "json",
                        success: function (response) {
                            document.getElementById('checksbb_add_special').disabled=false;
                            if(response.status[0].cd=='1'){
                                $("#checksbb_alert_special").html('<div class="alert alert-success alert-dismissible" role="alert">'+
                                response.status[0].msg+
                                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">'+
                                    '</button>'+
                                '</div>');
                            } else {
                                $("#checksbb_alert_special").html('<div class="alert alert-warning alert-dismissible" role="alert">'+
                                response.status[0].msg+
                                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">'+
                                    '</button>'+
                                '</div>');
                            }
                        }, error(xhr, xopt, xthrow){
                            alertify.error(xthrow);
                            document.getElementById('checksbb_add_special').disabled=false
                        }
                    });
                }
            });
        }
    }

    function checksbb_e_addto_cal(){
        const datanya = checksbb_DTABLE_psn.rows( { selected: true } ).nodes()[0];
        if (typeof datanya == 'undefined'){
            alert("there is no selected data");
        } else {
            const ttluse = numeral(document.getElementById('checksbb_cal_ttl_per').innerText).value();
            const idsample = document.getElementById("checksbb_cal_ID").value;
            const job = document.getElementById("checksbb_req_job").value;
            const line = document.getElementById("checksbb_txt_line").value;
            const mc = document.getElementById("checksbb_txt_mc").value;
            const mcz = document.getElementById("checksbb_txt_mcz").value;
            const per = document.getElementById("checksbb_txt_per").value;
            const psn = datanya.cells[1].innerText.trim();
            const itmcat = datanya.cells[5].innerText.trim();
            const fr = datanya.cells[4].innerText.trim();
            const itemcd = datanya.cells[8].innerText.trim();
            const actqty = numeral(datanya.cells[11].innerText).value()
            const strdis = "Add below data <br>"+
                "<b>PSN</b>: "+psn+
                "<br><b>Process</b>: "+checksbb_procd+
                "<br><b>Line</b>: "+line+
                "<br><b>Category</b>: "+itmcat+
                "<br><b>F/R</b>: "+fr+
                "<br><b>Job</b>: "+job+
                "<br><b>PER</b>: "+per+
                "<br><b>MC</b>: "+mc+
                "<br><b>MCZ</b>: "+mcz+
                "<br><b>Item Code</b>: "+itemcd+
                "<br>=======TO======="+
                "<br><b>ID</b>: "+idsample+" alike";

            $.messager.confirm('Decide', strdis, function(r){
                if (r){
                    document.getElementById('checksbb_add').disabled=true;
                    $("#checksbb_alert").html('<span class="badge bg-info">Please wait</span>');
                    $.ajax({
                        type: "post",
                        url: "<?=base_url('SER/add_rm_to_boxID')?>",
                        data: {inpsn: psn, inprocd: checksbb_procd, inline: line, initmcat: itmcat
                        ,infr: fr, injob: job, inactqt: actqty, inper: per, inmc: mc, inmcz: mcz
                        ,initmcd: itemcd, inttluse: ttluse },
                        dataType: "json",
                        success: function (response) {
                            document.getElementById('checksbb_add').disabled=false;
                            if(response.status[0].cd=='1'){
                                $("#checksbb_alert").html('<div class="alert alert-success alert-dismissible" role="alert">'+
                                response.status[0].msg+
                                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">'+
                                    '</button>'+
                                '</div>');
                            } else {
                                $("#checksbb_alert").html('<div class="alert alert-warning alert-dismissible" role="alert">'+
                                response.status[0].msg+
                                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">'+
                                    '</button>'+
                                '</div>');
                            }
                        }, error(xhr, xopt, xthrow){
                            alertify.error(xthrow);
                            document.getElementById('checksbb_add').disabled=false;
                        }
                    });
                }
            });
        }
    }

    function checksbb_e_flagok(){
        if(confirm("Are you sure ?")){
            let sjob = document.getElementById('checksbb_req_job').value;
            let ttluse = numeral(document.getElementById('checksbb_cal_ttl_per').innerText).value();
            $.ajax({
                type: "get",
                url: "<?=base_url('SER/flag_rmuse_ok')?>",
                data: {injob: sjob, inttluse: ttluse},
                dataType: "json",
                success: function (response) {
                    if(response.status[0].cd==1){
                        $("#checksbb_alert").html('<div class="alert alert-success alert-dismissible" role="alert">'+
                        response.status[0].msg+
                            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">'+
                            '</button>'+
                        '</div>');
                    } else {
                        $("#checksbb_alert").html('<div class="alert alert-warning alert-dismissible" role="alert">'+
                        response.status[0].msg+
                            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">'+
                            '</button>'+
                        '</div>');
                    }
                }, error(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                    document.getElementById('checksbb_btn_filter').disabled = false;
                }
            });
        }
    }

    function checksbb_btn_calc_e_click(){
        let tabel_PLOT = document.getElementById("checksbb_tbl");
        let tabel_PLOT_body0 = tabel_PLOT.getElementsByTagName("tbody")[0];
        let ttlrows = tabel_PLOT_body0.getElementsByTagName('tr').length;
        let aser = [];
        let aserqty = [];
        let aserjob = [];
        for(let i=0;i<ttlrows;i++){
            if(tabel_PLOT_body0.rows[i].cells[6].getElementsByTagName('input')[0].checked){
                aser.push(tabel_PLOT_body0.rows[i].cells[3].innerText);
                aserqty.push(numeral(tabel_PLOT_body0.rows[i].cells[4].innerText).value());
                aserjob.push(tabel_PLOT_body0.rows[i].cells[0].innerText);
                tabel_PLOT_body0.rows[i].cells[5].innerText = "Please wait...";
            }
        }
        if(aser.length>0){
            if(aser.length<=67){
                if(confirm("Are you sure ?")){
                    $.ajax({
                        type: "post",
                        url: "<?=base_url('DELV/calculate_raw_material_resume')?>",
                        data: {inunique :aser, inunique_qty : aserqty, inunique_job: aserjob },
                        dataType: "json",
                        success: function (response) {
                            alertify.success("Done");
                            let ttlrows_sts = response.status.length;
                            let tabel_PLOT = document.getElementById("checksbb_tbl");
                            let tabel_PLOT_body0 = tabel_PLOT.getElementsByTagName("tbody")[0];
                            let ttlrows = tabel_PLOT_body0.getElementsByTagName('tr').length;
                            for(let i=0; i<ttlrows_sts; i++){
                                for(let u=0;u<ttlrows; u++){
                                    if(tabel_PLOT_body0.rows[u].cells[3].innerText==response.status[i].reffno){
                                        tabel_PLOT_body0.rows[u].cells[5].innerText=response.status[i].msg;
                                        tabel_PLOT_body0.rows[u].cells[6].getElementsByTagName('input')[0].checked=false;
                                        tabel_PLOT_body0.rows[u].cells[6].getElementsByTagName('input')[0].disabled=true;
                                        break;
                                    }
                                }
                            }
                        }, error: function(xhr, xopt, xthrow){
                            alertify.error(xthrow);
                        }
                    });
                }
            } else {
                alertify.warning("Maximum data to be calculated is 67");
            }
        } else {
            alertify.message('there is no selected data')
        }
    }

    function checksbb_req_btn_all_e_click(){
        let tabell = document.getElementById("checksbb_reqtbl_rm");
        let obody = checksbb_req_rows.getElementsByTagName("tbody")[0];
        tabell.getElementsByTagName("tbody")[0].innerHTML = obody.innerHTML;
    }

    function checksbb_req_btn_discrep_e_click(){
        $("#checksbb_reqtbl_rm tbody").empty();
        let tabell = document.getElementById("checksbb_reqtbl_rm");
        let tableku2 = tabell.getElementsByTagName("tbody")[0];
        let obody = checksbb_req_rows.getElementsByTagName("tbody")[0];
        let obody_row = obody.getElementsByTagName("tr");
        let obody_row_length = obody_row.length;
        let newrow, newcell, newText;
        for(let i=0; i<obody_row_length; i++){
            if(!obody.rows[i].classList.contains("table-success")){
                newrow = tableku2.insertRow(-1);
                newrow.innerHTML = obody.rows[i].innerHTML;
                let objk = {
                    docno: checksbb_docno
                    ,line: newrow.cells[0].innerText
                    ,process: newrow.cells[1].innerText
                    ,mc: newrow.cells[3].innerText
                    ,mcz: newrow.cells[4].innerText
                    ,fr: newrow.cells[2].innerText
                    ,per: newrow.cells[5].innerText
                    ,mpart: newrow.cells[6].innerText
                    ,mpartname: newrow.cells[9].innerText
                };
                newrow.cells[10].onclick = function(){checksbb_show_possible_supply(objk)};
            }
        }
    }

    function checksbb_show_possible_supply(pdata){
        document.getElementById("checksbb_txt_line").value = pdata.line;
        document.getElementById("checksbb_txt_mc").value = pdata.mc;
        document.getElementById("checksbb_txt_mcz").value = pdata.mcz;
        document.getElementById("checksbb_txt_per").value = pdata.per;
        checksbb_procd = pdata.process;
        let assycode = document.getElementById('checksbb_req_itmcd').value;
        let mjob = document.getElementById('checksbb_req_job').value;
        $('#checksbb_w_psnfilter').window('open');
        checksbb_DTABLE_psn =  $('#checksbb_tblpsnfilter').DataTable({
            select: true,
            fixedHeader: true,
            destroy: true,
            scrollX: true,
            scrollY: true,
            ajax: {
                url : '<?=base_url("SPL/get_psn_process")?>',
                type: 'get',
                data: {indocno: pdata.docno, inprc: pdata.process, infr: pdata.fr
                , inmpart: pdata.mpart, inassy: assycode, injob: mjob, inmcz: pdata.mcz, initemname: btoa(pdata.mpartname)
                 }
            },
            columns:[
                { "data": 'PPSN2_DOCNO'},
                { "data": 'PPSN2_PSNNO'},
                { "data": 'PPSN2_PROCD'},
                { "data": 'PPSN2_LINENO'},
                { "data": 'PPSN2_FR'},
                { "data": 'PPSN2_ITMCAT'},
                { "data": 'PPSN2_MC'},
                { "data": 'PPSN2_MCZ'},
                { "data": 'PPSN2_SUBPN'},
                { "data": 'MITM_SPTNO'},
                { "data": 'MITM_ITMD1'},
                { "data": 'PPSN2_ACTQT', render: $.fn.dataTable.render.number(',', '.', 0,'')}
            ],
            columnDefs: [
                {
                    targets: 10,
                    className: 'text-end'
                }
            ]
        });
    }
    function checksbb_show_possible_supply_special(pdata){
        document.getElementById("checksbb_txt_line").value = pdata.line;
        document.getElementById("checksbb_txt_mc").value = pdata.mc;
        document.getElementById("checksbb_txt_mcz").value = pdata.mcz;
        document.getElementById("checksbb_txt_per").value = pdata.per;
        checksbb_procd = pdata.process;
        const assycode = document.getElementById('checksbb_req_itmcd').value;
        const mjob = document.getElementById('checksbb_req_job').value;
        const tblcalc_body = document.getElementById('checksbb_caltbl_rm').getElementsByTagName('tbody')[0]
        const tblcalc_body_rowscount = tblcalc_body.getElementsByTagName('tr').length
        const ttlreq = document.getElementById('checksbb_cal_ID_qty').value * pdata.per
        let ttlbal = 0
        for(let i=0;i<tblcalc_body_rowscount; i++) {
            ttlbal = ttlreq - numeral(tblcalc_body.rows[i].cells[8].innerText).value()
            if(tblcalc_body.rows[i].cells[0].innerText === pdata.line
                && tblcalc_body.rows[i].cells[1].innerText === pdata.process
                && tblcalc_body.rows[i].cells[2].innerText === pdata.fr
                && tblcalc_body.rows[i].cells[3].innerText === pdata.mc
                && tblcalc_body.rows[i].cells[4].innerText === pdata.mcz
                && ttlbal > 0
                ) {
                document.getElementById('checksbb_txt_qty_special').value = ttlbal; break
            }
        }
        $('#checksbb_w_psnfilter_special').window('open');
        checksbb_DTABLE_psn =  $('#checksbb_tblpsnfilter_special').DataTable({
            select: true,
            fixedHeader: true,
            destroy: true,
            scrollX: true,
            scrollY: true,
            ajax: {
                url : '<?=base_url("SPL/get_psn_process_special")?>',
                type: 'get',
                data: {indocno: pdata.docno, inprc: pdata.process, infr: pdata.fr
                , inmpart: pdata.mpart, inassy: assycode, injob: mjob, inmcz: pdata.mcz, initemname: btoa(pdata.mpartname)
                 }
            },
            columns:[
                { "data": 'PPSN2_DOCNO'},
                { "data": 'PPSN2_PSNNO'},
                { "data": 'PPSN2_PROCD'},
                { "data": 'PPSN2_LINENO'},
                { "data": 'PPSN2_FR'},
                { "data": 'PPSN2_ITMCAT'},
                { "data": 'PPSN2_MC'},
                { "data": 'PPSN2_MCZ'},
                { "data": 'PPSN2_SUBPN'},
                { "data": 'MITM_SPTNO'},
                { "data": 'MITM_ITMD1'},
                { "data": 'PPSN2_ACTQT', render: $.fn.dataTable.render.number(',', '.', 0,'')}
            ],
            columnDefs: [
                {
                    targets: 10,
                    className: 'text-end'
                }
            ]
        });
    }

    function checksbb_e_compare(pjob, passycd, passynm, pid, pqty){
        document.getElementById('checksbb_req_job').value=pjob;
        document.getElementById('checksbb_req_itmcd').value=passycd;
        document.getElementById('checksbb_req_itmnm').value=passynm;
        document.getElementById('checksbb_cal_ID').value  = "Please wait"
        document.getElementById('checksbb_cal_ID_qty').value  = "Please wait"
        checksbb_req_bomrev.value = `please wait`
        $("#checksbb_caltbl_rm tbody").empty();
        $.ajax({
            type: "get",
            url: "<?=base_url('DELV/getjobstatus_compare_byser')?>",
            data: {inid: pid, injob: pjob},
            dataType: "json",
            success: function (response) {
                let ttlrows_req = response.datareq.length;
                let ttlrows_psn = response.datapsn.length;
                let ttlrows_cal = response.datacal.length;
                let ttlrows_msp = response.datamsp.length;
                checksbb_docno = response.datadoc.docno
                let mydes_req = document.getElementById("checksbb_reqdiv_rm");
                let myfrag_req = document.createDocumentFragment();
                let mtabel_req = document.getElementById("checksbb_reqtbl_rm");
                let cln_req = mtabel_req.cloneNode(true);
                myfrag_req.appendChild(cln_req);
                let tabell_req = myfrag_req.getElementById("checksbb_reqtbl_rm");
                let percontainer_req = myfrag_req.getElementById("checksbb_req_ttl_per");
                let tableku2_req = tabell_req.getElementsByTagName("tbody")[0];
                let newrow, newcell, newText;
                tableku2_req.innerHTML='';
                let lotsize = 0
                let simqt = 0
                let ttlper_req = 0
                if(ttlrows_req>0){
                    checksbb_req_bomrev.value = response.datareq[0].PDPP_BOMRV
                    lotsize = response.datareq[0].PDPP_WORQT;
                    document.getElementById('checksbb_req_simqt').value = numeral(response.datareq[0].SIMQT).format(',');
                }
                document.getElementById('checksbb_req_itmqty').value = numeral(lotsize).format(',');
                let ca_resumereq = [];
                for(let i=0; i<ttlrows_req; i++){
                    ttlper_req+=numeral(response.datareq[i].MYPER).value();
                    newrow = tableku2_req.insertRow(-1);
                    for(let n=0; n< ttlrows_cal; n++){
                        if(response.datareq[i].PIS3_LINENO.trim() == response.datacal[n].SERD2_LINENO
                        && response.datareq[i].PIS3_PROCD.trim() == response.datacal[n].SERD2_PROCD
                        && response.datareq[i].PIS3_FR.trim() == response.datacal[n].SERD2_FR
                        && response.datareq[i].PIS3_MC.trim() == response.datacal[n].SERD2_MC
                        && response.datareq[i].PIS3_MCZ.trim() == response.datacal[n].SERD2_MCZ
                        && numeral(response.datareq[i].MYPER).format('0.00') == numeral(response.datacal[n].SERD2_QTPER).format('0.00')
                        ){
                            newrow.classList.add("table-success");
                            break;
                        }
                    }

                    let isfound = false;
                    for(let c in ca_resumereq){
                        if(ca_resumereq[c].process == response.datareq[i].PIS3_PROCD.trim()
                        && ca_resumereq[c].mc == response.datareq[i].PIS3_MC.trim()
                        && ca_resumereq[c].mcz == response.datareq[i].PIS3_MCZ.trim()
                        && ca_resumereq[c].lineno == response.datareq[i].PIS3_LINENO.trim() ){
                            ca_resumereq[c].per += numeral(response.datareq[i].MYPER).value();
                            isfound = true;
                            break;
                        }
                    }
                    if(!isfound){
                        let newobku = {
                            process : response.datareq[i].PIS3_PROCD.trim(),
                            mc : response.datareq[i].PIS3_MC.trim(),
                            mcz : response.datareq[i].PIS3_MCZ.trim(),
                            per: numeral(response.datareq[i].MYPER).value(),
                            lineno: response.datareq[i].PIS3_LINENO.trim(),
                            match: false
                        };
                        ca_resumereq.push(newobku);
                    }
                    newcell = newrow.insertCell(0);
                    newText = document.createTextNode(response.datareq[i].PIS3_LINENO.trim());
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(1);
                    newText = document.createTextNode(response.datareq[i].PIS3_PROCD.trim());
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(2);
                    newText = document.createTextNode(response.datareq[i].PIS3_FR.trim());
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(3);
                    newText = document.createTextNode(response.datareq[i].PIS3_MC.trim());
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(4);
                    newText = document.createTextNode(response.datareq[i].PIS3_MCZ.trim());
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(5);
                    newcell.style.cssText = "text-align:right";
                    newText = document.createTextNode(numeral(response.datareq[i].MYPER).format('0.00'));
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(6);
                    newText = document.createTextNode(response.datareq[i].PIS3_MPART.trim());
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(7);
                    newText = document.createTextNode(response.datareq[i].MITM_SPTNO);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(8);
                    newcell.style.cssText = "text-align:right";
                    newText = document.createTextNode(numeral(response.datareq[i].PIS3_REQQTSUM).format(','));
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(9);
                    newText = document.createTextNode(response.datareq[i].MITM_ITMD1);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(10);
                    newText = document.createTextNode('');
                    newcell.appendChild(newText);
                }
                percontainer_req.innerText = ttlper_req;
                let mrows_req = tableku2_req.getElementsByTagName("tr");
                let mrows_req_length = mrows_req.length;


                //Calculation result
                let mydes = document.getElementById("checksbb_caldiv_rm");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("checksbb_caltbl_rm");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("checksbb_caltbl_rm");
                let percontainer = myfrag.getElementById("checksbb_cal_ttl_per");
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                tableku2.innerHTML='';
                if(ttlrows_cal>0){
                    document.getElementById('checksbb_cal_ID').value = pid
                    document.getElementById('checksbb_cal_ID_qty').value = numeral(pqty).value()
                }
                let ttlper_cal = 0;
                let ca_resumecal = [];
                for(let i=0; i<ttlrows_cal; i++){
                    let isfound = false;
                    for(let c in ca_resumecal){
                        if(ca_resumecal[c].process == response.datacal[i].SERD2_PROCD
                        && ca_resumecal[c].mc == response.datacal[i].SERD2_MC
                        && ca_resumecal[c].mcz == response.datacal[i].SERD2_MCZ
                        && ca_resumecal[c].lineno == response.datacal[i].SERD2_LINENO ){
                            ca_resumecal[c].per += numeral(response.datacal[i].SERD2_QTPER).value();
                            isfound = true;
                            break;
                        }
                    }
                    if(!isfound){
                        let newobku = {
                            process : response.datacal[i].SERD2_PROCD,
                            mc: response.datacal[i].SERD2_MC,
                            mcz: response.datacal[i].SERD2_MCZ,
                            per: numeral(response.datacal[i].SERD2_QTPER).value(),
                            lineno: response.datacal[i].SERD2_LINENO
                            };
                        ca_resumecal.push(newobku);
                    }
                    ttlper_cal+=numeral(response.datacal[i].SERD2_QTPER).value();
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0);
                    newcell.innerHTML = response.datacal[i].SERD2_LINENO
                    newcell = newrow.insertCell(1);
                    newcell.innerHTML = response.datacal[i].SERD2_PROCD
                    newcell = newrow.insertCell(2);
                    newcell.innerHTML = response.datacal[i].SERD2_FR
                    newcell = newrow.insertCell(3);
                    newcell.innerHTML = response.datacal[i].SERD2_MC
                    newcell = newrow.insertCell(4);
                    newcell.innerHTML = response.datacal[i].SERD2_MCZ
                    newcell = newrow.insertCell(5);
                    newcell.style.cssText = "text-align: right";
                    newcell.innerHTML = numeral(response.datacal[i].SERD2_QTPER).format('0.00')
                    newcell = newrow.insertCell(6);
                    newcell.innerHTML = response.datacal[i].SERD2_ITMCD
                    newcell = newrow.insertCell(7);
                    newcell.innerHTML = response.datacal[i].MITM_SPTNO
                    newcell = newrow.insertCell(8);
                    newcell.style.cssText = "text-align: right";
                    newcell.ondblclick = () =>  {
                        checksbb_e_showRowsDetail({reffno: pid, itemCD: response.datacal[i].SERD2_ITMCD, mcz: response.datacal[i].SERD2_MCZ})
                        $("#checksbb_MODEDITQTYCAL").modal('show')
                        document.getElementById('checksbb_newqty').value = ''
                        document.getElementById('checksbb_newqty').focus()
                    }
                    newcell.innerHTML = numeral(response.datacal[i].SERD2_QTY).format('0.00')
                    newcell = newrow.insertCell(9)
                    newcell.innerHTML = response.datacal[i].MITM_ITMD1
                }
                percontainer.innerText = ttlper_cal;
                mydes.innerHTML='';
                mydes.appendChild(myfrag);

                //req. resume
                mydes = document.getElementById("checksbb_reqdiv_rm_resume");
                myfrag = document.createDocumentFragment();
                mtabel = document.getElementById("checksbb_reqtbl_rm_resume");
                cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                tabell = myfrag.getElementById("checksbb_reqtbl_rm_resume");
                tableku2 = tabell.getElementsByTagName("tbody")[0];
                tableku2.innerHTML='';
                for(let c in ca_resumereq){
                    newrow = tableku2.insertRow(-1);
                    for(let i in ca_resumecal){
                        if(ca_resumereq[c].process.trim()==ca_resumecal[i].process.trim()
                        && ca_resumereq[c].mc.trim()==ca_resumecal[i].mc.trim()
                        && ca_resumereq[c].mcz.trim()==ca_resumecal[i].mcz.trim()
                        && ca_resumereq[c].lineno==ca_resumecal[i].lineno
                        && ca_resumereq[c].per==ca_resumecal[i].per
                        ){
                            newrow.classList.add("table-success");
                            ca_resumereq[c].match = true;
                            break;
                        }
                    }
                    newcell = newrow.insertCell(0);
                    newText = document.createTextNode(ca_resumereq[c].lineno);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(1);
                    newText = document.createTextNode(ca_resumereq[c].process);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(2);
                    newText = document.createTextNode(ca_resumereq[c].mc);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(3);
                    newText = document.createTextNode(ca_resumereq[c].mcz);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(4);
                    newcell.style.cssText = "text-align: right";
                    newText = document.createTextNode(numeral(ca_resumereq[c].per).format('0.00'));
                    newcell.appendChild(newText);
                }
                mydes.innerHTML='';
                mydes.appendChild(myfrag);

                let ca_unmatch = [];
                let ca_unmatch_2 = [];
                for(let r in ca_resumereq){
                    if(!ca_resumereq[r].match){
                        ca_unmatch.push(ca_resumereq[r]);
                    }
                }
                for(let u in ca_unmatch){
                    for(let n=0; n<ttlrows_cal; n++){
                        if(
                        response.datacal[n].SERD2_PROCD==ca_unmatch[u].process
                        && response.datacal[n].SERD2_MC==ca_unmatch[u].mc
                        && response.datacal[n].SERD2_MCZ==ca_unmatch[u].mcz
                        && response.datacal[n].SERD2_LINENO==ca_unmatch[u].lineno
                        )
                        {
                            ca_unmatch[u].itemcode = response.datacal[n].SERD2_ITMCD;
                            break;
                        }
                    }
                }
                for(let u in ca_unmatch){
                    for(let n=0; n<ttlrows_cal; n++){
                        if(
                        response.datacal[n].SERD2_PROCD==ca_unmatch[u].process
                        && response.datacal[n].SERD2_MC==ca_unmatch[u].mc
                        && response.datacal[n].SERD2_MCZ==ca_unmatch[u].mcz
                        && response.datacal[n].SERD2_LINENO==ca_unmatch[u].lineno
                        )
                        {
                            let nob = {
                                        process: ca_unmatch[u].process
                                        ,mc: ca_unmatch[u].mc
                                        ,mcz: ca_unmatch[u].mcz
                                        ,lineno: ca_unmatch[u].lineno
                                        ,itemcode: response.datacal[n].SERD2_ITMCD
                                        ,fr: response.datacal[n].SERD2_FR
                                    };
                            ca_unmatch_2.push(nob);
                        }
                    }
                }
                //show modified req. table
                for(let u in ca_unmatch){
                    for(let i = 0; i<mrows_req_length; i++ ){
                        if(ca_unmatch[u].process==tableku2_req.rows[i].cells[1].innerText
                        && ca_unmatch[u].mc==tableku2_req.rows[i].cells[3].innerText
                        && ca_unmatch[u].mcz==tableku2_req.rows[i].cells[4].innerText
                        && ca_unmatch[u].lineno==tableku2_req.rows[i].cells[0].innerText)
                        {
                            if(ca_unmatch[u].itemcode!=tableku2_req.rows[i].cells[6].innerText){
                                tableku2_req.rows[i].classList.remove('table-success');
                            }
                        }
                    }
                }

                //new marking logic
                // reset specific data display first, per mcz
                for(let u in ca_unmatch){
                    for(let i = 0; i<mrows_req_length; i++ ){
                        if(ca_unmatch[u].process==tableku2_req.rows[i].cells[1].innerText
                        && ca_unmatch[u].mc==tableku2_req.rows[i].cells[3].innerText
                        && ca_unmatch[u].mcz==tableku2_req.rows[i].cells[4].innerText
                        && ca_unmatch[u].lineno==tableku2_req.rows[i].cells[0].innerText)
                        {
                            tableku2_req.rows[i].classList.remove('table-success');
                        }
                    }
                }

                for(let u in ca_unmatch_2){
                    for(let i = 0; i<mrows_req_length; i++ ){
                        if(ca_unmatch_2[u].process==tableku2_req.rows[i].cells[1].innerText
                        && ca_unmatch_2[u].mc==tableku2_req.rows[i].cells[3].innerText
                        && ca_unmatch_2[u].mcz==tableku2_req.rows[i].cells[4].innerText
                        && ca_unmatch_2[u].itemcode.trim()==tableku2_req.rows[i].cells[6].innerText.trim()
                        && ca_unmatch_2[u].fr.trim()==tableku2_req.rows[i].cells[2].innerText.trim()
                        && ca_unmatch_2[u].lineno.trim()==tableku2_req.rows[i].cells[0].innerText.trim())
                        {
                            tableku2_req.rows[i].classList.add('table-success');
                        }
                    }
                }

                // sub checking
                for(let u in ca_unmatch_2){
                    for(let i = 0; i<mrows_req_length; i++ ){
                        if(ca_unmatch_2[u].process==tableku2_req.rows[i].cells[1].innerText
                        && ca_unmatch_2[u].mc==tableku2_req.rows[i].cells[3].innerText
                        && ca_unmatch_2[u].mcz==tableku2_req.rows[i].cells[4].innerText
                        && ca_unmatch_2[u].lineno==tableku2_req.rows[i].cells[0].innerText
                        )
                        {
                            for(let n =0 ; n<ttlrows_cal; n++){
                                let issubpriority = false;
                                for(let s=0; s<ttlrows_msp; s++ ){
                                    if(response.datamsp[s].MSPP_BOMPN==tableku2_req.rows[i].cells[6].innerText.trim()
                                    && response.datamsp[s].MSPP_SUBPN==response.datacal[n].SERD2_ITMCD){
                                        issubpriority = true;
                                        break;
                                    }
                                }
                                if(ca_unmatch_2[u].process==response.datacal[n].SERD2_PROCD
                                && ca_unmatch_2[u].mc==response.datacal[n].SERD2_MC
                                && ca_unmatch_2[u].mcz==response.datacal[n].SERD2_MCZ
                                && ca_unmatch_2[u].lineno==response.datacal[n].SERD2_LINENO
                                && issubpriority){
                                    tableku2_req.rows[i].classList.add('table-success');
                                }
                            }
                        }
                    }
                }
                // n sub checking

                for(let i = 0; i<mrows_req_length; i++ ){
                    let objk = {
                        docno: checksbb_docno
                        ,line: tableku2_req.rows[i].cells[0].innerText
                        ,process: tableku2_req.rows[i].cells[1].innerText
                        ,mc: tableku2_req.rows[i].cells[3].innerText
                        ,mcz: tableku2_req.rows[i].cells[4].innerText
                        ,fr: tableku2_req.rows[i].cells[2].innerText
                        ,per: tableku2_req.rows[i].cells[5].innerText
                        ,mpart: tableku2_req.rows[i].cells[6].innerText
                        ,mpartname: tableku2_req.rows[i].cells[9].innerText
                    };
                    if(!tableku2_req.rows[i].classList.contains('table-success')){
                        tableku2_req.rows[i].cells[10].innerHTML = "<span class='fas fa-screwdriver text-warning'></span>";
                        tableku2_req.rows[i].cells[10].style.cssText = "cursor:pointer;text-align:center";
                        tableku2_req.rows[i].cells[10].title = "action is required";
                        tableku2_req.rows[i].cells[10].onclick = function(){checksbb_show_possible_supply(objk)}
                    } else {
                        tableku2_req.rows[i].cells[0].ondblclick = function(){checksbb_show_possible_supply_special(objk)}
                    }
                }
                // end new marking logic
                checksbb_req_rows =  tabell_req.cloneNode(true);

                mydes_req.innerHTML='';
                mydes_req.appendChild(myfrag_req);

                //Calculation result resume
                mydes = document.getElementById("checksbb_caldiv_rm_resume");
                myfrag = document.createDocumentFragment();
                mtabel = document.getElementById("checksbb_caltbl_rm_resume");
                cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                tabell = myfrag.getElementById("checksbb_caltbl_rm_resume");
                tableku2 = tabell.getElementsByTagName("tbody")[0];
                tableku2.innerHTML='';
                for(let c in ca_resumecal){
                    newrow = tableku2.insertRow(-1);
                    for(let i in ca_resumereq){
                        if(ca_resumereq[i].process.trim()==ca_resumecal[c].process.trim()
                        && ca_resumereq[i].mc.trim()==ca_resumecal[c].mc.trim()
                        && ca_resumereq[i].mcz.trim()==ca_resumecal[c].mcz.trim()
                        && ca_resumereq[i].lineno.trim()==ca_resumecal[c].lineno.trim()
                        && ca_resumereq[i].per==ca_resumecal[c].per
                        ){
                            newrow.classList.add("table-success");
                            break;
                        }
                    }
                    newcell = newrow.insertCell(0);
                    newText = document.createTextNode(ca_resumecal[c].lineno);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(1);
                    newText = document.createTextNode(ca_resumecal[c].process);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(2);
                    newText = document.createTextNode(ca_resumecal[c].mc);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(3);
                    newText = document.createTextNode(ca_resumecal[c].mcz);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(4);
                    newcell.style.cssText = "text-align: right";
                    newText = document.createTextNode(numeral(ca_resumecal[c].per).format('0.00'));
                    newcell.appendChild(newText);
                }
                mydes.innerHTML='';
                mydes.appendChild(myfrag);


                //PSN
                mydes = document.getElementById("checksbb_divdetailpsn");
                myfrag = document.createDocumentFragment();
                mtabel = document.getElementById("checksbb_tbldetailpsn");
                cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                tabell = myfrag.getElementById("checksbb_tbldetailpsn");
                tableku2 = tabell.getElementsByTagName("tbody")[0];
                tableku2.innerHTML = '';
                let apsn = [];
                for(let i=0; i<ttlrows_psn; i++){
                    if(!apsn.includes(response.datapsn[i].PPSN2_PSNNO.trim())){
                        apsn.push(response.datapsn[i].PPSN2_PSNNO.trim());
                    }
                    newrow = tableku2.insertRow(-1);
                    for(let n=0; n< ttlrows_req; n++){
                        if(response.datareq[n].PIS3_LINENO.trim() == response.datapsn[i].PPSN2_LINENO.trim()
                        && response.datareq[n].PIS3_PROCD.trim() == response.datapsn[i].PPSN2_PROCD.trim()
                        && response.datareq[n].PIS3_FR.trim() == response.datapsn[i].PPSN2_FR.trim()
                        && response.datareq[n].PIS3_MC.trim() == response.datapsn[i].PPSN2_MC.trim()
                        && response.datareq[n].PIS3_MCZ.trim() == response.datapsn[i].PPSN2_MCZ.trim()
                        ){
                            newrow.classList.add("table-success");
                            break;
                        }
                    }
                    newcell = newrow.insertCell(0);
                    newText = document.createTextNode(response.datapsn[i].PPSN2_DOCNO);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(1);
                    newText = document.createTextNode(response.datapsn[i].PPSN2_PSNNO);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(2);
                    newText = document.createTextNode(response.datapsn[i].PPSN2_LINENO);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(3);
                    newText = document.createTextNode(response.datapsn[i].PPSN2_PROCD);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(4);
                    newText = document.createTextNode(response.datapsn[i].PPSN2_FR);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(5);
                    newText = document.createTextNode(response.datapsn[i].PPSN2_ITMCAT);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(6);
                    newText = document.createTextNode(response.datapsn[i].PPSN2_MC);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(7);
                    newText = document.createTextNode(response.datapsn[i].PPSN2_MCZ);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(8);
                    newText = document.createTextNode(response.datapsn[i].PPSN2_MSFLG);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(9);
                    newText = document.createTextNode(response.datapsn[i].PPSN2_SUBPN);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(10);
                    newText = document.createTextNode(response.datapsn[i].MITM_SPTNO);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(11);
                    newText = document.createTextNode(response.datapsn[i].MITM_ITMD1);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(12);
                    newcell.style.cssText = "text-align: right";
                    newText = document.createTextNode(numeral(response.datapsn[i].PPSN2_REQQT).format(','));
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(13);
                    newcell.style.cssText = "text-align: right";
                    newText = document.createTextNode(numeral(response.datapsn[i].PPSN2_ACTQT).format(','));
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(14);
                    newcell.style.cssText = "text-align: right";
                    newText = document.createTextNode(numeral(response.datapsn[i].PPSN2_RTNQT).format(','));
                    newcell.appendChild(newText);
                }
                $('#checksbb_psn_list').tagbox('setValues', apsn);
                mydes.innerHTML='';
                mydes.appendChild(myfrag);
            }, error(xhr, xopt, xthrow){
                alertify.error(xthrow);
                alertify.message('Please try again...');
                checksbb_req_bomrev.value = ``
            }
        });
        $('#checksbb_w_jobreq').window('open');
        $('#checksbb_w_psnjob').window('open');
        $('#checksbb_w_calculation').window('open');
    }

    function checksbb_e_showRowsDetail(pdata) {
        const lblinfo = document.getElementById('checksbb_caltbl_rm_edit_lblinfo')
        lblinfo.innerHTML = 'Please wait...'
        $.ajax({
            type: "GET",
            url: "<?=base_url('SER/showCalculationPerItem')?>",
            data: pdata,
            dataType: "JSON",
            success: function (response) {
                lblinfo.innerHTML = ''
                const ttlrows = response.data.length;
                if(ttlrows>0){
                    let mydes = document.getElementById("checksbb_caltbl_rm_edit_div");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("checksbb_caltbl_rm_edit");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("checksbb_caltbl_rm_edit");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    let newrow, newcell, newText;
                    tableku2.innerHTML=''
                    for (let i = 0; i<ttlrows; i++) {
                        newrow = tableku2.insertRow(-1)
                        newrow.onclick = () => {
                            document.getElementById('checksbb_oldqty').value = numeral(response.data[i].SERD2_QTY).value()
                        }
                        newcell = newrow.insertCell(0)
                        newcell.innerHTML = response.data[i].SERD2_LINENO
                        newcell = newrow.insertCell(1)
                        newcell.innerHTML = response.data[i].SERD2_PROCD
                        newcell = newrow.insertCell(2)
                        newcell.innerHTML = response.data[i].SERD2_FR
                        newcell = newrow.insertCell(3)
                        newcell.innerHTML = response.data[i].SERD2_MC
                        newcell = newrow.insertCell(4)
                        newcell.innerHTML = response.data[i].SERD2_MCZ
                        newcell = newrow.insertCell(5)
                        newcell.innerHTML = response.data[i].SERD2_QTPER
                        newcell = newrow.insertCell(6)
                        newcell.innerHTML = response.data[i].SERD2_ITMCD
                        newcell = newrow.insertCell(7)
                        newcell.innerHTML = response.data[i].MITM_SPTNO
                        newcell = newrow.insertCell(8)
                        newcell.classList.add('text-end')
                        newcell.innerHTML = response.data[i].SERD2_QTY
                        newcell = newrow.insertCell(9)
                        newcell.innerHTML = response.data[i].MITM_ITMD1
                        newcell = newrow.insertCell(10)
                        newcell.style.cssText = "cursor:pointer"
                        newcell.innerHTML = `<i class="fas fa-trash text-danger"></i>`
                        newcell.onclick = () => {checksbb_e_remove({
                                reffno: pdata.reffno,
                                itemCD: response.data[i].SERD2_ITMCD,
                                mcz: response.data[i].SERD2_MCZ,
                                qty: response.data[i].SERD2_QTY,
                                luptd: response.data[i].SERD2_LUPDT
                            })}
                    }
                    mydes.innerHTML=''
                    mydes.appendChild(myfrag)
                }
            }, error(xhr, xopt, xthrow){
                alertify.error(xthrow)
            }
        })
    }

    function checksbb_e_remove(pdataRemove) {
        if (confirm("Are you sure ?")) {
            $.ajax({
                type: "POST",
                url: "<?=base_url('SER/removerowcalculation')?>",
                data: pdataRemove,
                dataType: "JSON",
                success: function (response) {
                    if ( response.status[0].cd==='1' ){
                        alertify.success(response.status[0].msg)
                    } else {
                        alertify.message(response.status[0].msg)
                    }
                    $("#checksbb_MODEDITQTYCAL").modal('hide')
                }, error(xhr, xopt, xthrow){
                    alertify.error(xthrow)
                }
            })
        }
    }

    $('#checksbb_psn_list').tagbox({
        label: 'PSN No',
        onRemoveTag :function(e) {
            e.preventDefault()
        }
    });
    function checksbb_btn_sync_e_click(){
        document.getElementById('checksbb_btn_sync').disabled = true;
        $("#checksbb_tbl tbody").empty();
        document.getElementById('checksbb_lblinfo').innerText="Please wait";
        const filterby = document.getElementById('checksbb_cmb_filter').value;
        const filterval = document.getElementById('checksbb_txt_search').value;
        checksbbTotalQty.innerText = 0
        $.ajax({
            type: "get",
            url: "<?=base_url('SER/get_check_susuan_bb')?>",
            data: {insearchby: filterby, insearchval: filterval},
            dataType: "json",
            success: function (response) {
                document.getElementById('checksbb_btn_sync').disabled = false;
                const ttlrows = response.data.length;
                let mydes = document.getElementById("checksbb_divku");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("checksbb_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("checksbb_tbl");
                let totalQtyContainer = myfrag.getElementById("checksbbTotalQty");
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                let newrow, newcell, newText;

                tableku2.innerHTML='';
                document.getElementById('checksbb_lblinfo').innerText= ttlrows+" row(s) found";

                let itemcdlist = new Set()
                let newjob = new Set()
                let totalQty = 0

                totalQtyContainer.classList.add('strong')

                for(let i=0; i<ttlrows; i++){
                    totalQty += response.data[i].SER_QTY*1
                    newjob.add(response.data[i].SER_DOC)
                    itemcdlist.add(response.data[i].SER_ITMID)
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0);
                    newcell.ondblclick = () => {
                        checksbb_calculate_from_oldata(i);
                    }
                    newcell.innerHTML = response.data[i].SER_DOC
                    newcell = newrow.insertCell(1);
                    newcell.innerHTML = response.data[i].SER_ITMID
                    newcell = newrow.insertCell(2);
                    newcell.innerHTML = response.data[i].MITM_ITMD1
                    newcell = newrow.insertCell(3);
                    newcell.style.cssText = "text-align:center";
                    newcell.innerHTML = response.data[i].ITH_SER
                    newcell.ondblclick = () => {
                        document.getElementById('checksbb_txtemer_ID').value = response.data[i].ITH_SER
                        document.getElementById('checksbb_txtemer_JOB').value = response.data[i].SER_DOC
                        document.getElementById('checksbb_txtemer_QTY').value = numeral(response.data[i].SER_QTY).format(',')
                        document.getElementById('checksbb_txtemer_msg').value = ''
                        $.ajax({
                            url: "<?=base_url('SER/validate_emergency')?>",
                            data: {id : response.data[i].ITH_SER},
                            dataType: "json",
                            success: function (response) {
                                if(response.data.length>0){
                                    if(response.data[0].DLVSER){
                                        alertify.message('the ID is already delivered')
                                    } else {
										document.getElementById('checksbb_txtemer_QTY').value = response.data[0].TQTY
                                        if(!response.data[0].SERD2_SER){
                                            document.getElementById('checksbb_btnemer_calculate').disabled = false
                                            document.getElementById('checksbb_btnemer_remove_calculation').disabled = true
                                        } else {
                                            document.getElementById('checksbb_btnemer_calculate').disabled = true
                                            document.getElementById('checksbb_btnemer_remove_calculation').disabled = false
                                        }
                                        $("#checksbb_Emergency").modal('show')
                                    }
                                }
                            }
                        })
                    }
                    newcell = newrow.insertCell(4);
                    newcell.style.cssText = "text-align:right";
                    newcell.innerHTML = numeral(response.data[i].SER_QTY).format(',')
                    newcell = newrow.insertCell(5);
                    if(Number(response.data[i].CALPER)==0){
                        newcell.innerHTML = 'Not calculated yet'
                        newcell = newrow.insertCell(6);
                        newText = document.createElement('input');
                        newText.setAttribute('type', 'checkbox');
                        newText.classList.add('form-check-input')
                        newText.onclick = function() {checksbb_cksi_e_click(event, this,response.data[i].SER_DOC )};
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(7);
                        newcell.innerHTML = ''
                    } else {
                        if(Number(response.data[i].CALPER)>=Number(response.data[i].MYPER) || response.data[i].FLG === 'flg_ok'){
                            newcell.innerHTML = 'OK';
                            newcell = newrow.insertCell(6);
                            newText = document.createElement('input');
                            newText.setAttribute('type', 'checkbox')
                            newText.classList.add('form-check-input');
                            newText.disabled = true;
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(7);
                            newcell.onclick = () => {checksbb_e_compare(response.data[i].SER_DOC, response.data[i].SER_ITMID, response.data[i].MITM_ITMD1, response.data[i].ITH_SER, response.data[i].SER_QTY);};
                            newcell.style.cssText = "cursor:pointer";
                            newText = document.createElement('span');
                            newText.classList.add('badge','bg-success');
                            newText.innerText = "Show Result";
                            newcell.appendChild(newText);
                        } else {
                            newcell.onclick = () => {checksbb_e_compare(response.data[i].SER_DOC, response.data[i].SER_ITMID, response.data[i].MITM_ITMD1, response.data[i].ITH_SER, response.data[i].SER_QTY);};
                            newcell.style.cssText = "cursor:pointer";
                            newcell.innerHTML = 'Edit required';
                            newcell = newrow.insertCell(6);
                            newText = document.createElement('input');
                            newText.setAttribute('type', 'checkbox')
                            newText.classList.add('form-check-input');
                            newText.disabled = true;
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(7);
                            newcell.onclick = () => {checksbb_ck_recalcualte(event,response.data[i].SER_DOC)};
                            newcell.style.cssText = "cursor:pointer";
                            newText = document.createElement('span');
                            newText.classList.add('badge','bg-warning');
                            newText.innerText = "Reset calculation ?";
                            newcell.appendChild(newText);

                        }
                    }
                }
                totalQtyContainer.innerText = totalQty
                mydes.innerHTML=''
                mydes.appendChild(myfrag)
                let tohtml = '<option value="-">-</option>'
                for(let i of newjob){
                    tohtml += "<option value='"+i+"'>"+i+"</option>"
                }
                $("#checksbb_cmb_job").html(tohtml);
                tohtml = '<option value="-">-</option>';
                for(let i of itemcdlist){
                    tohtml += "<option value='"+i+"'>"+i+"</option>"
                }
                $("#checksbb_cmb_item").html(tohtml);
            }, error(xhr, xopt, xthrow){
                alertify.error(xthrow);
                document.getElementById('checksbb_btn_sync').disabled = false;
            }
        });
    }

    function checksbb_calculate_from_oldata(pRowIndex){
        let tabel_PLOT = document.getElementById("checksbb_tbl");
        let tabel_PLOT_body0 = tabel_PLOT.getElementsByTagName("tbody")[0];
        let ttlrows = tabel_PLOT_body0.getElementsByTagName('tr').length;
        let aser = [];
        let aserqty = [];
        let aserjob = [];
        let flagJob = tabel_PLOT_body0.rows[pRowIndex].cells[0].innerText;
        let flagReffno = tabel_PLOT_body0.rows[pRowIndex].cells[3].innerText;
        let flagRemark = tabel_PLOT_body0.rows[pRowIndex].cells[5].innerText;
        if(flagRemark.includes("not complete")){
            for(let i=0;i<ttlrows;i++){
                if(tabel_PLOT_body0.rows[i].cells[0].innerText.includes(flagJob)){
                    aser.push(tabel_PLOT_body0.rows[i].cells[3].innerText);
                    aserqty.push(numeral(tabel_PLOT_body0.rows[i].cells[4].innerText).value());
                    aserjob.push(tabel_PLOT_body0.rows[i].cells[0].innerText);
                }
            }
            if(aser.length>0){
                if(aser.length<=67){
                    if(confirm("Calculate RM directly from MEGA ?")){
                        for(let i=0;i<ttlrows;i++){
                            if(tabel_PLOT_body0.rows[i].cells[0].innerText.includes(flagJob)){
                                tabel_PLOT_body0.rows[i].cells[5].innerText = "Please wait...";
                            }
                        }

                        $.ajax({
                            type: "post",
                            url: "<?=base_url('SER/calculate_raw_material_oldata_resume')?>",
                            data: {inunique :aser, inunique_qty : aserqty, inunique_job: aserjob },
                            dataType: "json",
                            success: function (response) {
                                alertify.success("Done");
                                let ttlrows_sts = response.status.length;
                                let tabel_PLOT = document.getElementById("checksbb_tbl");
                                let tabel_PLOT_body0 = tabel_PLOT.getElementsByTagName("tbody")[0];
                                let ttlrows = tabel_PLOT_body0.getElementsByTagName('tr').length;
                                for(let i=0; i<ttlrows_sts; i++){
                                    for(let u=0;u<ttlrows; u++){
                                        if(tabel_PLOT_body0.rows[u].cells[3].innerText==response.status[i].reffno){
                                            tabel_PLOT_body0.rows[u].cells[5].innerText=response.status[i].msg;
                                            tabel_PLOT_body0.rows[u].cells[6].getElementsByTagName('input')[0].checked=false;
                                            tabel_PLOT_body0.rows[u].cells[6].getElementsByTagName('input')[0].disabled=true;
                                            break;
                                        }
                                    }
                                }
                            }, error: function(xhr, xopt, xthrow){
                                alertify.error(xthrow)
                            }
                        });
                    }
                }
            }
        }
    }

    function checksbb_e_concfirm_edit_qtycal(){
        const itemcode = document.getElementById('checksbb_caltbl_rm_edit').getElementsByTagName('tbody')[0].rows[0].cells[6].innerText
        const oldqty = numeral(document.getElementById('checksbb_oldqty').value).value()
        const newqty = numeral(document.getElementById('checksbb_newqty').value).value()
        if(confirm("Are you sure ?") && oldqty){
            const txtid = document.getElementById('checksbb_cal_ID').value
            $.ajax({
                type: "post",
                url: "<?=base_url('SER/updatecalculation')?>",
                data: {reffid: txtid,itemcode: itemcode , oldqty: oldqty, newqty: newqty},
                dataType: "json",
                success: function (response) {
                    alertify.message(response.status[0].msg)
                    $("#checksbb_MODEDITQTYCAL").modal('hide')
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow)
                    $("#checksbb_MODEDITQTYCAL").modal('hide')
                }
            });
        } else {
            $("#checksbb_MODEDITQTYCAL").modal('hide')
        }
    }

    function checksbb_btnemer_calculate_eCK(pthis){
        let reffno = []
        let job = []
        let qty = []
        reffno.push(document.getElementById('checksbb_txtemer_ID').value)
        job.push(document.getElementById('checksbb_txtemer_JOB').value)
        qty.push(document.getElementById('checksbb_txtemer_QTY').value)
        pthis.disabled = true
        pthis.innerText = `Please wait`
        $.ajax({
            type: "POST",
            url: "<?=base_url('DELV/calculate_raw_material_resume')?>",
            data: {inunique: reffno, inunique_qty: qty,inunique_job: job },
            dataType: "JSON",
            success: function (response) {
                pthis.innerText = `Calculate`
                pthis.disabled = false
                document.getElementById('checksbb_txtemer_msg').value = JSON.stringify(response)
            }, error:function(xhr,ajaxOptions, throwError) {
                pthis.innerText = `Calculate`
                pthis.disabled = false
                alert(throwError);
            }
        });
    }

    function checksbb_btnemer_remove_calculation_eCK(pthis){
        const id = document.getElementById("checksbb_txtemer_ID").value
        if(confirm("Are you sure ?")){
            const mpin = prompt("PIN")
            pthis.disabled = true
            pthis.innerText = 'Please wait...'
            $.ajax({
                url: "<?=base_url('SER/removecalculation_byid')?>",
                data: {id: id,pin : mpin},
                dataType: "json",
                success: function (response) {
                    pthis.disabled = false
                    pthis.innerText = 'Remove Calculation'
                    document.getElementById('checksbb_txtemer_msg').value = JSON.stringify(response)
                }, error:function(xhr,ajaxOptions, throwError) {
                    alert(throwError)
                    pthis.disabled = false
                    pthis.innerText = 'Remove Calculation'
                }
            })
        }
    }

    function checksbb_btn_simulate_e_click()
    {
        $("#checksbb_MODRESIMULATE").modal('show')
    }

    function checksbb_e_resimulate(p)
    {
        if(checksbb_cmb_remark.value==='-')
        {
            alertify.message('Reason is required')
            checksbb_cmb_remark.focus()
            return
        }

        let tabel_PLOT_body0 = checksbb_tbl2.getElementsByTagName("tbody")[0];
        let ttlrows = tabel_PLOT_body0.getElementsByTagName('tr').length;
        let reffno_l = []
        let qty_l = []
        for(let i=0; i<ttlrows; i++)
        {
            let qty = numeral(tabel_PLOT_body0.rows[i].cells[4].innerText).value()
            if(qty>0 && tabel_PLOT_body0.rows[i].cells[5].getElementsByTagName('input')[0].checked)
            {
                reffno_l.push(tabel_PLOT_body0.rows[i].cells[3].innerText)
                qty_l.push(qty)
            }
        }

        if(checksbb_jobno.value.trim().length===0)
        {
            checksbb_jobno.focus()
            alertify.message('Job is required')
            return
        }
        if(reffno_l.length===0)
        {
            alertify.message('Please select ID first')
            return
        }
        if( confirm('Are you sure ?') )
        {
            p.innerHTML = `<i class="fas fa-spinner fa-spin-pulse"></i>`
            p.disabled = true
            $.ajax({
                type: "post",
                url: "<?=base_url('SER/resimulate')?>",
                data: {reffno: reffno_l, wono : checksbb_jobno.value, qty:qty_l,remark: checksbb_cmb_remark.value },
                dataType: "json",
                success: function (response) {
                    checksbb_tbl2.getElementsByTagName('tbody')[0].innerHTML = ''
                    p.innerHTML = 'Resimulate'
                    p.disabled = false
                    alertify.message(response.status[0].msg)
                }, error:function(xhr,ajaxOptions, throwError) {
                    alert(throwError)
                    p.disabled = false
                    p.innerHTML = 'Resimulate'
                }
            })
        }
    }

    function checksbb_resimulate_wo_sync(p)
    {
        p.disabled = true
        $.ajax({
            type: "get",
            url: "<?=base_url('SER/get_check_susuan_bb')?>",
            data: {insearchby: 'job', insearchval: checksbb_jobno.value},
            dataType: "json",
            success: function (response) {
                p.disabled = false;
                const ttlrows = response.data.length;
                let mydes = document.getElementById("checksbb_tbl2_div");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("checksbb_tbl2");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("checksbb_tbl2");
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                let newrow, newcell;
                let ck_all = myfrag.getElementById('checksbb_ckall')
                ck_all.onclick = () => {
                    let ttlrows = tableku2.getElementsByTagName('tr').length;
                    for(let i=0;i<ttlrows;i++){
                        if(numeral(tableku2.rows[i].cells[4].innerText).value()>0)
                        {
                            tableku2.rows[i].cells[5].getElementsByTagName('input')[0].checked = ck_all.checked
                        }
                    }
                }
                tableku2.innerHTML=''
                for(let i=0; i<ttlrows; i++){
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0);
                    newcell.innerHTML = response.data[i].SER_DOC
                    newcell = newrow.insertCell(1);
                    newcell.innerHTML = response.data[i].SER_ITMID
                    newcell = newrow.insertCell(2);
                    newcell.innerHTML = response.data[i].MITM_ITMD1
                    newcell = newrow.insertCell(3);
                    newcell.style.cssText = "text-align:center";
                    newcell.innerHTML = response.data[i].ITH_SER
                    newcell = newrow.insertCell(4);
                    newcell.style.cssText = "text-align:right";
                    newcell.innerHTML = numeral(response.data[i].SER_QTY).format(',')
                    newcell = newrow.insertCell(5)
                    newcell.classList.add('text-center')
                    newcell.innerHTML = `<input type="checkbox" class="form-check-input">`
                }
                mydes.innerHTML=''
                mydes.appendChild(myfrag)
            }, error(xhr, xopt, xthrow){
                alertify.error(xthrow);
                document.getElementById('checksbb_btn_sync').disabled = false;
            }
        });
    }
    $("#checksbb_MODRESIMULATE").on('shown.bs.modal', function(){
        $("#checksbb_jobno").focus();
    });

    function checksbb_jobno_eKP(e)
    {
        if (e.key==='Enter')
        {
            checksbb_btn_prepare_resim.focus()
        }
    }

    function checksbb_btn_reset_job_eClick(){
        if(checksbb_cmb_filter.value === 'job'){
            if(checksbb_txt_search.value.trim().length<=6 || !checksbb_txt_search.value.includes('-')){
                checksbb_txt_search.focus()
                alertify.message(`Please enter valid job number`)
                return 
            }
            if(confirm(`Are you sure ?`))
            {
                checksbb_btn_reset_job.classList.add('disabled')
                $.ajax({
                    type: "POST",
                    url: "<?=base_url('SER/removeCalculationPerJob')?>",
                    data: {job : checksbb_txt_search.value},
                    dataType: "json",
                    success: function (response) {
                        alertify.message(response.msg)
                        checksbb_btn_reset_job.classList.remove('disabled')
                    }, error(xhr, xopt, xthrow){
                        alertify.error(xthrow);
                        checksbb_btn_reset_job.classList.remove('disabled')
                    }
                });
            }
        } else {
            checksbb_cmb_filter.focus()
            alertify.message('Job is required as a confirmation')
        }
    }
</script>