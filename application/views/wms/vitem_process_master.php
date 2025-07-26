<div style="padding: 10px">
	<div class="container-fluid">
        <div class="row">
            <div class="col-md-12 mb-1" id="itm-process-div-alert">

            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-2">
                <div class="btn-group btn-group-sm">
                    <button title="New" id="itm_process_btnnew" class="btn btn-primary" onclick="itm_process_btnnew_eClick()"><i class="fas fa-file"></i></button>
                    <button title="Save" id="itm_process_btnsave" class="btn btn-primary" onclick="itm_process_btnsave_eClick(this)"><i class="fas fa-save"></i></button>
                    <button title="History" class="btn btn-outline-secondary" type="button" id="itm_process_btnformoditem" onclick="itm_process_btnformoditem_eClick()"><i class="fas fa-search"></i></button>
                    <button title="Edit" class="btn btn-outline-primary" type="button" onclick="itm_process_btn_modal_edit_t_eClick()"><i class="fas fa-pen-to-square"></i></button>
                </div>
            </div>
            <div class="col-md-6 mb-2">
                <div class="input-group input-group-sm mb-3">
                    <label for="itm_process_date_time" class="input-group-text">Valid from</label>
                    <input type="text" class="form-control" id="itm_process_date_time" autocomplete="off" data-td-toggle="datetimepicker" data-td-target="#go_date_time" readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-3 table-responsive">
                <div id="itm_process_spreasheet"></div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="ITM_PROCESS_MODITM">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Item List</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >Search By</span>
                        <select id="itm_process_srchby" class="form-select" onchange="itm_process_txtsearch.focus()">
                            <option value="model_code">Model Code</option>
                            <option value="assy_code">Assy Code</option>
                            <option value="model_type">Type</option>
                        </select>
                        <input type="text" class="form-control" id="itm_process_txtsearch" maxlength="45" onkeypress="itm_process_txtsearch_eKP(event)" required placeholder="...">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col text-end">
                    <span class="badge bg-info" id="lblinfo_tblitm_process"></span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="itm_process_tbl_div">
                        <table id="itm_process_tbl" class="table table-sm table-striped table-bordered table-hover text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>Created at</th>
                                    <th>Line</th>
                                    <th>Model Code</th>
                                    <th>Assy Code</th>
                                    <th>Type</th>
                                    <th>Process</th>
                                    <th>Cycle Time</th>
                                    <th>Valid from</th>
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
<div class="modal fade" id="ITM_PROCESS_MOD_EDIT">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Edit</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >Search By</span>
                        <select id="itm_process_srchby_edit" class="form-select" onchange="itm_process_txtsearch_edit.focus()">
                            <option value="assy_code">Assy Code</option>
                            <option value="model_code">Model Code</option>
                            <option value="model_type">Type</option>
                        </select>
                        <input type="text" class="form-control" id="itm_process_txtsearch_edit" maxlength="45" onkeypress="itm_process_txtsearch_edit_eKP(event)" required placeholder="...">
                        <div class="input-group-text">
                            <input class="form-check-input mt-0" type="checkbox" id="itm_process_ck_show_deleted" onclick="itm_process_ck_show_deleted_on_click()">
                        </div>
                        <label class="input-group-text">Show deleted data</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="itm_process_edit_tbl_div">
                        <table id="itm_process_edit_tbl" class="table table-sm table-striped table-bordered table-hover text-center">
                            <thead class="table-light">
                                <tr>
                                    <th rowspan="2">Id</th>
                                    <th rowspan="2">Created at</th>
                                    <th colspan="2">Line</th>
                                    <th rowspan="2">Assy Code</th>
                                    <th rowspan="2">Process</th>
                                    <th rowspan="2">Cycle Time</th>
                                    <th rowspan="2">Valid from</th>
                                    <th rowspan="2">Sequence</th>
                                </tr>
                                <tr>
                                    <th>Code</th>
                                    <th>Category</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row border-top">
                <div class="col-md-4">
                    <label for="itm_process_edit_seq" class="form-label">Sequence</label>
                    <input type="text" class="form-control" id="itm_process_edit_seq" maxlength="1">
                </div>
                <div class="col-md-4">
                    <label for="itm_process_edit_seq" class="form-label">Line Category</label>
                    <input type="text" class="form-control" id="itm_process_edit_category" maxlength="3">
                </div>
                <div class="col-md-4">

                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-1" id="itm-process-edit-div-alert">

                </div>
            </div>
        </div>
        <div class="modal-footer">
            <input type="hidden" id="itm_process_edit_id" value="">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <button type="button" class="btn btn-danger" id="itm_process_edit_btn_delete" onclick="itm_process_edit_btn_delete_on_click(this)"><span class="fas fa-trash"></span></button>
                    </div>
                    <div class="col text-end">
                        <button type="button" class="btn btn-primary" id="itm_process_edit_btn_save" onclick="itm_process_edit_btn_save_on_click(this)"><span class="fas fa-save"></span></button>
                    </div>
                </div>
            </div>
            
        </div>
      </div>
    </div>
</div>
<script>

    var itm_process_date_time_pub = new tempusDominus.TempusDominus(itm_process_date_time,
        {
            localization: {
                locale : 'en',
                format : 'yyyy-MM-dd HH:mm:ss'
            },
            restrictions : {
                maxDate: new Date
            }
        }
    )

    function itm_process_btnnew_eClick() {
        itm_process_sso.setData([
            ['','','','','',0],
            ['','','','','',0],
        ])
    }

    var itm_process_sso = jspreadsheet(itm_process_spreasheet, {
        columns : [
            {
                type: 'text',
                title:'Line',
                width:50,
            },
            {
                type: 'text',
                title:'Model Code',
                width:100,
            },
            {
                type: 'text',
                title:'Assy Code',
                width:100,
            },
            {
                type: 'text',
                title:'Type',
                width:100,
            },
            {
                type: 'text',
                title:'Process',
                width:100,
            },
            {
                type: 'numeric',
                title:'Cycle Time',
                mask: '#,##.00',
                width:120,
                align: 'right'
            },
            {
                type: 'numeric',
                title:'Sequence',
                mask: '#,##',
                width:120,
                align: 'right'
            },
            {
                type: 'dropdown',
                title:'Line Category',
                source: ['MC','HW','MI','SP','AX','RG'],
                width:120,
            },
        ],
        allowDeleteColumn : false,
        rowDrag:false,
        defaultColWidth : 150,
        data: [
            ['','','','','',0],
            ['','','','','',0],
        ],
        copyCompatibility:true,
        columnSorting:false,
        updateTable: function(el, cell, x, y, source, value, id) {

        }
    });

    function itm_process_btnformoditem_eClick() {
        $("#ITM_PROCESS_MODITM").modal('show');
    }

    function itm_process_txtsearch_eKP(e) {
        if(e.key === 'Enter') {
            itm_process_txtsearch.disabled = true
            $.ajax({
                type: "GET",
                url: "<?php echo $_ENV['APP_INTERNAL_API'] ?>process-master/history",
                data: {searchBy : itm_process_srchby.value, searchValue : itm_process_txtsearch.value },
                dataType: "json",
                success: function (response) {
                    itm_process_txtsearch.disabled = false

                    let myContainer = document.getElementById("itm_process_tbl_div");
                    let myfrag = document.createDocumentFragment();
                    let cln = itm_process_tbl.cloneNode(true);
                    myfrag.appendChild(cln);
                    let myTable = myfrag.getElementById("itm_process_tbl");
                    let myTableBody = myTable.getElementsByTagName("tbody")[0];
                    myTableBody.innerHTML = ''
                    response.data.forEach((arrayItem) => {
                        newrow = myTableBody.insertRow(-1)
                        newcell = newrow.insertCell(-1)
                        newcell.innerHTML = arrayItem['created_at']
                        newcell = newrow.insertCell(-1)
                        newcell.innerHTML = arrayItem['line_code']
                        newcell = newrow.insertCell(-1)
                        newcell.innerHTML = arrayItem['model_code']
                        newcell = newrow.insertCell(-1)
                        newcell.innerHTML = arrayItem['assy_code']
                        newcell = newrow.insertCell(-1)
                        newcell.innerHTML = arrayItem['model_type']
                        newcell = newrow.insertCell(-1)
                        newcell.innerHTML = arrayItem['process_code']
                        newcell = newrow.insertCell(-1)
                        newcell.innerHTML = arrayItem['cycle_time']
                        newcell = newrow.insertCell(-1)
                        newcell.innerHTML = arrayItem['valid_date_time']
                    })
                    myContainer.innerHTML = ''
                    myContainer.appendChild(myfrag)
                    if(response.data.length===0) {
                        alertify.message('not found')
                    }
                }, error: function(xhr, xopt, xthrow) {
                    itm_process_txtsearch.disabled = false
                }
            });
        }
    }

    function itm_process_btnsave_eClick(pThis) {
        let inputSS = itm_process_sso.getData()

        let dataMaster = [];

        const inputLength = inputSS.length

        if(itm_process_date_time.value === '') {
            itm_process_date_time.focus()
            alertify.warning('Please fill [valid from]')
            return
        }

        for(let i=0; i < inputLength; i++) {
            if(inputSS[i][6]=='') {
                alertify.warning('Sequence is required')
                return
            }
            if(inputSS[i][7]=='') {
                alertify.warning('Line category is required')
                return
            }

            dataMaster.push({
                line_code : inputSS[i][0],
                assy_code : inputSS[i][2],
                model_code : inputSS[i][1],
                model_type : inputSS[i][3],
                process_code : inputSS[i][4],
                cycle_time : numeral(inputSS[i][5]).value(),
                process_seq : inputSS[i][6],
                line_category : inputSS[i][7],
            })
        }

        const dataInput = {
            user_id: uidnya,
            valid_from : itm_process_date_time.value,
            master : dataMaster
        }

        if(!confirm('Are you sure ?')) {
            return
        }

        const div_alert = document.getElementById('itm-process-div-alert')
        div_alert.innerHTML = ''
        pThis.disabled = true
        $.ajax({
            type: "POST",
            url: "<?php echo $_ENV['APP_INTERNAL_API'] ?>process-master",
            data: JSON.stringify({data : dataInput}),
            dataType: "json",
            contentType: "application/json; charset=utf-8",
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

    $("#ITM_PROCESS_MODITM").on('shown.bs.modal', function(){
        itm_process_txtsearch.focus()
    })

    $("#ITM_PROCESS_MOD_EDIT").on('shown.bs.modal', function(){
        itm_process_txtsearch_edit.focus()
    })

    function itm_process_btn_modal_edit_t_eClick() {
        $("#ITM_PROCESS_MOD_EDIT").modal('show');
    }

    function itm_process_ck_show_deleted_on_click() {
        itm_process_show_process()
    }

    function itm_process_show_process() {
        $.ajax({
            type: "GET",
            url: "<?php echo $_ENV['APP_INTERNAL_API'] ?>process-master/history",
            data: {searchBy : itm_process_srchby_edit.value, searchValue : itm_process_txtsearch_edit.value, show_deleted : itm_process_ck_show_deleted.checked ? 'Y' : 'N' },
            dataType: "JSON",
            success: function (response) {
                itm_process_txtsearch_edit.disabled = false

                let myContainer = document.getElementById("itm_process_edit_tbl_div");
                let myfrag = document.createDocumentFragment();
                let cln = itm_process_edit_tbl.cloneNode(true);
                myfrag.appendChild(cln);
                let myTable = myfrag.getElementById("itm_process_edit_tbl");
                let myTableBody = myTable.getElementsByTagName("tbody")[0];
                myTableBody.innerHTML = ''
                response.data.forEach((arrayItem) => {
                    newrow = myTableBody.insertRow(-1)
                    newrow.style.cssText = 'cursor:pointer'
                    if(arrayItem['deleted_at']) {
                        newrow.classList.add('table-danger')
                        newrow.title = `this data already deleted`
                    }
                    newrow.onclick = function() {
                        itm_process_edit_id.value = arrayItem['id']
                        itm_process_edit_seq.value = arrayItem['process_seq'] ?? 0
                        itm_process_edit_seq.focus()
                        itm_process_edit_category.value = arrayItem['line_category']
                    }
                    newcell = newrow.insertCell(-1)
                    newcell.innerHTML = arrayItem['id']
                    newcell = newrow.insertCell(-1)
                    newcell.innerHTML = arrayItem['created_at']
                    newcell = newrow.insertCell(-1)
                    newcell.innerHTML = arrayItem['line_code']
                    newcell = newrow.insertCell(-1)
                    newcell.innerHTML = arrayItem['line_category']
                    newcell = newrow.insertCell(-1)
                    newcell.innerHTML = arrayItem['assy_code']
                    newcell = newrow.insertCell(-1)
                    newcell.innerHTML = arrayItem['process_code']
                    newcell = newrow.insertCell(-1)
                    newcell.innerHTML = arrayItem['cycle_time']
                    newcell = newrow.insertCell(-1)
                    newcell.innerHTML = arrayItem['valid_date_time']
                    newcell = newrow.insertCell(-1)
                    newcell.innerHTML = arrayItem['process_seq']
                })
                myContainer.innerHTML = ''
                myContainer.appendChild(myfrag)
                if(response.data.length===0) {
                    alertify.message('not found')
                }
            }
        });
    }

    function itm_process_txtsearch_edit_eKP(e) {
        if(e.key == 'Enter') {
            itm_process_show_process()
        }
    }

    Inputmask({
        'alias': 'decimal',
        'groupSeparator': ',',
    }).mask(itm_process_edit_seq);

    function itm_process_edit_btn_save_on_click(pThis) {
        if(itm_process_edit_id.value.length==0) {
            alertify.warning(`select the data first !`)
            return
        }
        if(!confirm(`Are you sure ?`)) {
            return
        }
        pThis.disabled = true
        const div_alert = document.getElementById('itm-process-edit-div-alert')
        div_alert.innerHTML = ''
        $.ajax({
            type: "PUT",
            url: "<?php echo $_ENV['APP_INTERNAL_API'] ?>process-master/process/"+ btoa(itm_process_edit_id.value),
            data: {process_seq : itm_process_edit_seq.value, 
                line_category : itm_process_edit_category.value,
                user_id: uidnya,
            },
            dataType: "JSON",
            success: function (response) {
                pThis.disabled = false
                alertify.message(response.message)
                itm_process_show_process()
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

    function itm_process_edit_btn_delete_on_click(pThis) {
        if(itm_process_edit_id.value.trim().length == 0) {
            alertify.message('nothing to be deleted')
            return
        }

        if(confirm(`Are you sure want to DELETE id ${itm_process_edit_id.value} ?`)) {
            pThis.disabled = true
            $.ajax({
                type: "DELETE",
                url: "<?php echo $_ENV['APP_INTERNAL_API'] ?>process-master/process/"+ btoa(itm_process_edit_id.value),    
                data : {user_id: uidnya},
                dataType: "JSON",
                success: function (response) {
                    pThis.disabled = false
                    alertify.message(response.message)
                    itm_process_show_process()
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
</script>
