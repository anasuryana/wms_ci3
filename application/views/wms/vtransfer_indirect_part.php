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
<div style="padding: 10px">
    <div class="container-fluid">
        <div id="div-alert">

        </div>
        <div class="row" id="trf_indirect_rm_stack1">
            <div class="col-md-6 mb-1">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary" id="trf_indirect_rm_btn_new" onclick="trf_indirect_rm_btn_new_eC()"><i class="fas fa-file"></i></button>
                    <button class="btn btn-primary" id="trf_indirect_rm_btn_save" onclick="trf_indirect_rm_btn_save_eC(this)"><i class="fas fa-save"></i></button>

                    <button id="btnGroupDrop1" type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Export to..">
                        <i class="fas fa-file-export"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <li><a class="dropdown-item" href="#" id="ith_btn_xls" onclick="trf_indirect_rm_btn_xls_head_e_click(this)"><span style="color: MediumSeaGreen"><i class="fas fa-file-excel"></i></span> Spreadsheet</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6 mb-1 text-end">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-info" id="trf_indirect_rm_btn_info" data-bs-toggle="modal" data-bs-target="#trf_indirect_rm_ModDocumentProperties" title="Document properties"><i class="fas fa-info"></i></button>
                </div>
            </div>
        </div>
        <div class="row" id="trf_indirect_rm_stack2">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Document Number</label>
                    <input type="text" class="form-control" id="trf_indirect_rm_txt_doc" disabled readonly title="autonumber">
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#trf_indirect_rm_ModDocumentList"><i class="fas fa-search"></i></button>
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Document Date</label>
                    <input type="text" class="form-control" id="trf_indirect_rm_txt_date" readonly>
                </div>
            </div>
        </div>
        <div class="row" id="trf_indirect_rm_stack3">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">From Location</label>
                    <select id="trf_indirect_rm_fr_loc" class="form-select" onchange="document.getElementById('trf_indirect_rm_to_loc').focus()">
                    <?=$lwh?>
                    </select>
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">To Location</label>
                    <select id="trf_indirect_rm_to_loc" class="form-select"><?=$lwh?></select>
                </div>
            </div>
        </div>
        <div class="row" id="trf_indirect_rm_stack4">
            <div class="col-md-12 mb-1 table-responsive">
                <div id="trf_indirect_rm_table_container"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="trf_indirect_rm_ModDocumentList">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Document List</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div class="input-group input-group-sm mb-1">
                            <span class="input-group-text">Search</span>
                            <select id="trf_indirect_rm_search_document_by" class="form-select" onchange="trf_indirect_rm_search_document.focus()">
                                <option value="0">Item Code</option>
                                <option value="1">Description</option>
                                <option value="2">Document</option>
                            </select>
                            <input type="text" class="form-control" id="trf_indirect_rm_search_document" onkeypress="trf_indirect_rm_search_document_eKP(event)" maxlength="40" required placeholder="Press 'Enter' to search">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="table-responsive" id="trf_indirect_rm_tblDocumentList_div">
                            <table id="trf_indirect_rm_tblDocumentList" class="table table-sm table-striped table-bordered table-hover" style="width:100%">
                                <thead class="table-light">
                                    <tr>
                                        <th>Document Number</th>
                                        <th>Document Date</th>
                                        <th>Created By</th>
                                        <th>Location From</th>
                                        <th>Location To</th>
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
<div class="modal fade" id="trf_indirect_rm_ModDocumentProperties">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Document Properties</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div class="input-group input-group-sm mb-1">
                            <span class="input-group-text">Created by</span>
                            <input type="text" class="form-control" id="trf_indirect_rm_property_created_by" readonly disabled>
                        </div>
                    </div>
                    <div class="col">
                        <div class="input-group input-group-sm mb-1">
                            <span class="input-group-text">Created at</span>
                            <input type="text" class="form-control" id="trf_indirect_rm_property_created_at" readonly disabled>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="input-group input-group-sm mb-1">
                            <span class="input-group-text">Updated by</span>
                            <input type="text" class="form-control" id="trf_indirect_rm_property_updated_by" readonly disabled>
                        </div>
                    </div>
                    <div class="col">
                        <div class="input-group input-group-sm mb-1">
                            <span class="input-group-text">Updated at</span>
                            <input type="text" class="form-control" id="trf_indirect_rm_property_updated_at" readonly disabled>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="trf_indirect_rm_hidden_id">
<script>

    function trf_indirect_rm_search_document_eKP(e) {
        e.readOnly = true
        if(e.key === 'Enter') {
            const data = {
                searchBy : trf_indirect_rm_search_document_by.value,
                searchValue : trf_indirect_rm_search_document.value
            }
            trf_indirect_rm_tblDocumentList.getElementsByTagName('tbody')[0].innerHTML = '<tr><td colspan="5">Please wait...</td></tr>'
            $.ajax({
                type: "GET",
                url: "<?=$_ENV['APP_INTERNAL_API']?>"+"transfer-indirect-rm/form",
                data: data,
                dataType: "json",
                success: function (response) {
                    e.readOnly = false
                    let myContainer = document.getElementById("trf_indirect_rm_tblDocumentList_div");
                    let myfrag = document.createDocumentFragment();
                    let cln = trf_indirect_rm_tblDocumentList.cloneNode(true);
                    myfrag.appendChild(cln);
                    let myTable = myfrag.getElementById("trf_indirect_rm_tblDocumentList");
                    let myTableBody = myTable.getElementsByTagName("tbody")[0];
                    myTableBody.innerHTML = ''
                    response.data.forEach((arrayItem) => {
                        newrow = myTableBody.insertRow(-1)
                        newcell = newrow.insertCell(-1)
                        newcell.style.cssText = 'cursor:pointer'
                        newcell.onclick = () => {
                            $("#trf_indirect_rm_ModDocumentList").modal('hide')
                            trf_indirect_rm_details({doc: arrayItem['id'], readOnly : arrayItem['submitted_at'] ? true : false})
                            trf_indirect_rm_txt_doc.value = arrayItem['doc_code']
                            $("#trf_indirect_rm_txt_date").datepicker('update', arrayItem['issue_date'])

                            trf_indirect_rm_fr_loc.value = arrayItem['location_from']
                            trf_indirect_rm_to_loc.value = arrayItem['location_to']

                            // document properties
                            trf_indirect_rm_property_created_by.value = arrayItem['MSTEMP_FNM']
                            trf_indirect_rm_property_created_at.value = arrayItem['created_at']
                            trf_indirect_rm_property_updated_by.value = arrayItem['WHO_UPDATE']
                            trf_indirect_rm_property_updated_at.value = arrayItem['updated_at']

                            trf_indirect_rm_hidden_id.value = arrayItem['id']
                        }
                        newcell.innerHTML = arrayItem['doc_code']
                        newcell = newrow.insertCell(-1)
                        newcell.innerHTML = arrayItem['issue_date']
                        newcell = newrow.insertCell(-1)
                        newcell.innerHTML = arrayItem['MSTEMP_FNM']
                        newcell = newrow.insertCell(-1)
                        newcell.innerHTML = arrayItem['location_from']
                        newcell = newrow.insertCell(-1)
                        newcell.innerHTML = arrayItem['location_to']
                    })
                    myContainer.innerHTML = ''
                    myContainer.appendChild(myfrag)
                },
                error: function(xhr, ajaxOptions, throwError) {
                    alertify.error(xthrow)
                    e.readOnly = false
                }
            });
        }
    }


    trf_indirect_rm_ModDocumentList.addEventListener('shown.bs.modal', () => {
        trf_indirect_rm_search_document.focus()
    })

    function trf_indirect_rm_btn_new_eC() {
        let datanya_RM = trf_indirect_rm_sso_part.getData()
        let dataList = datanya_RM.filter((data) => data[2].length > 1)

        $("#trf_indirect_rm_txt_date").datepicker('update', new Date())

        trf_indirect_rm_txt_doc.value = ''
        trf_indirect_rm_hidden_id.value = ''

        if(dataList.length > 0) {
            if(confirm(`Create a new document ?`)) {
                trf_indirect_rm_sso_part.setData([
                    [],
                    [],
                    [],
                    [],
                    []
                ])
            }
        }
        trf_indirect_rm_btn_save.disabled = false
    }

    $("#trf_indirect_rm_txt_date").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    })

    $("#trf_indirect_rm_txt_date").datepicker('update', new Date())
    var emptyData = [
       [''],
    ];
    var trf_indirect_rm_sso_part = jspreadsheet(trf_indirect_rm_table_container, {
        contextMenu: function(o, x, y, e, items, section) {
            items = [];
        },
        data: emptyData,
        columns: [
            {
                type: 'text',
                title:'idrow',
                width:2,
                align: 'left',
                readOnly : true
            },
            {
                type: 'text',
                title:'Model',
                width:150,
                align: 'left'
            },
            {
                type: 'text',
                title:'Assy Code',
                width:150,
                align: 'left'
            },
            {
                type: 'text',
                title:'Part Code',
                width:150,
                align: 'left'
            },
            {
                type: 'text',
                title:'Part Name',
                width:150,
                align: 'left'
            },
            {
                type: 'numeric',
                mask: '#,##0.0000',
                title:'Usage',
                width:50,
                align: 'right'
            },
            {
                type: 'numeric',
                mask: '#,##0',
                title:'Req. Qty',
                width:70,
                align: 'right'
            },
            {
                type: 'text',
                title:'JOB',
                width:70,
                align: 'left'
            },
            {
                type: 'numeric',
                mask: '#,##0',
                title:'Supply Qty',
                width:80,
                align: 'right'
            },

        ],
    });

    function trf_indirect_rm_btn_save_eC(pthis) {
        if(trf_indirect_rm_fr_loc.value!=trf_indirect_rm_to_loc.value) {
            let datanya_RM = trf_indirect_rm_sso_part.getData()
            let dataList = datanya_RM.filter((data) => data[2].length > 1)

            let model = [];
            let assyCode = [];
            let partCode = [];
            let partName = [];
            let usage = [];
            let requiredQty = [];
            let supplyQty = [];
            let job = [];

            dataList.forEach((arrayItem) => {
                let _model = arrayItem[1].trim().replace(/[\u0000-\u0008,\u000A-\u001F,\u007F-\u00A0]+/g, "")
                let _assyCode = arrayItem[2].trim().replace(/[\u0000-\u0008,\u000A-\u001F,\u007F-\u00A0]+/g, "")
                let _partCode = arrayItem[3].trim().replace(/[\u0000-\u0008,\u000A-\u001F,\u007F-\u00A0]+/g, "")
                let _partName = arrayItem[4].trim().replace(/[\u0000-\u0008,\u000A-\u001F,\u007F-\u00A0]+/g, "")
                let _usage = arrayItem[5].trim().replace(/[\u0000-\u0008,\u000A-\u001F,\u007F-\u00A0]+/g, "")
                let _requiredQty = arrayItem[6].trim().replace(/[\u0000-\u0008,\u000A-\u001F,\u007F-\u00A0]+/g, "")
                let _job = arrayItem[7].trim().replace(/[\u0000-\u0008,\u000A-\u001F,\u007F-\u00A0]+/g, "")
                let _supplyQty = arrayItem[8].trim().replace(/[\u0000-\u0008,\u000A-\u001F,\u007F-\u00A0]+/g, "")

                model.push(_model)
                assyCode.push(_assyCode)
                partCode.push(_partCode)
                partName.push(_partName)
                usage.push(_usage)
                requiredQty.push(_requiredQty)
                job.push(_job)
                supplyQty.push(_supplyQty)
            })

            const div_alert = document.getElementById('div-alert')

            if(partCode.length > 0) {
                if(trf_indirect_rm_txt_doc.value.length === 0) {

                    const data = {
                        issue_date : trf_indirect_rm_txt_date.value,
                        location_from : trf_indirect_rm_fr_loc.value,
                        location_to : trf_indirect_rm_to_loc.value,
                        userid : uidnya,
                        model : model,
                        assy_code : assyCode,
                        part_code : partCode,
                        part_name : partName,
                        usage_qty : usage,
                        req_qty : requiredQty,
                        job : job,
                        sup_qty : supplyQty,
                    }

                    if(!confirm('Are you sure want to save ?')) {
                        return
                    }

                    pthis.innerHTML = `<i class="fas fa-spinner fa-spin"></i>`
                    pthis.disabled = true
                    $.ajax({
                        type: "POST",
                        url: "<?=$_ENV['APP_INTERNAL_API']?>"+"transfer-indirect-rm/form",
                        data: data,
                        dataType: "json",
                        success: function (response) {
                            pthis.innerHTML = `<i class="fas fa-save"></i>`
                            pthis.disabled = false

                            alertify.success(response.message)
                            document.getElementById('div-alert').innerHTML = ''
                            trf_indirect_rm_txt_doc.value = response.new_document
                            trf_indirect_rm_hidden_id.value = response.new_document_id

                            let datanya = []
                            response.data.forEach((arrayItem) => {
                                datanya.push([
                                    arrayItem['id'],
                                    arrayItem['model'],
                                    arrayItem['assy_code'],
                                    arrayItem['part_code'],
                                    arrayItem['part_name'],
                                    arrayItem['usage_qty'],
                                    arrayItem['req_qty'],
                                    arrayItem['job'],
                                    arrayItem['sup_qty'],
                                ])
                            })
                            trf_indirect_rm_sso_part.setData(datanya)
                        }, error: function(xhr, xopt, xthrow) {
                            const respon = Object.keys(xhr.responseJSON)

                            let msg = ''
                            for (const item of respon) {
                                msg += `<p>${xhr.responseJSON[item]}</p>`
                            }
                            div_alert.innerHTML = `<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                ${msg}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>`
                            pthis.innerHTML = `<i class="fas fa-save"></i>`
                            alertify.warning(xthrow);
                            pthis.disabled = false
                        }
                    });
                } else {
                    let dataBefore = JSON.parse(localStorage.getItem('transfer_ind_part'))
                    let dataAfter = trf_indirect_rm_sso_part.getData()

                    const isSameIDRP = (a, b) => a[3] === b[3] && a[8] === b[8];

                    const onlyInA = onlyInLeft(dataBefore, dataAfter, isSameIDRP);
                    const onlyInB = onlyInLeft(dataAfter, dataBefore, isSameIDRP);

                    const result = [...onlyInA, ...onlyInB]

                    let model = [];
                    let assyCode = [];
                    let partCode = [];
                    let partName = [];
                    let usage = [];
                    let requiredQty = [];
                    let supplyQty = [];
                    let job = [];
                    let rows_id = [];

                    result.forEach((arrayItem) => {
                        let _rows_id = arrayItem[0]
                        let _model = arrayItem[1].trim().replace(/[\u0000-\u0008,\u000A-\u001F,\u007F-\u00A0]+/g, "")
                        let _assyCode = arrayItem[2].trim().replace(/[\u0000-\u0008,\u000A-\u001F,\u007F-\u00A0]+/g, "")
                        let _partCode = arrayItem[3].trim().replace(/[\u0000-\u0008,\u000A-\u001F,\u007F-\u00A0]+/g, "")
                        let _partName = arrayItem[4].trim().replace(/[\u0000-\u0008,\u000A-\u001F,\u007F-\u00A0]+/g, "")
                        let _usage = arrayItem[5].trim().replace(/[\u0000-\u0008,\u000A-\u001F,\u007F-\u00A0]+/g, "")
                        let _requiredQty = arrayItem[6].trim().replace(/[\u0000-\u0008,\u000A-\u001F,\u007F-\u00A0]+/g, "")
                        let _job = arrayItem[7].trim().replace(/[\u0000-\u0008,\u000A-\u001F,\u007F-\u00A0]+/g, "")
                        let _supplyQty = arrayItem[8].trim().replace(/[\u0000-\u0008,\u000A-\u001F,\u007F-\u00A0]+/g, "")

                        model.push(_model)
                        assyCode.push(_assyCode)
                        partCode.push(_partCode)
                        partName.push(_partName)
                        usage.push(_usage)
                        requiredQty.push(_requiredQty)
                        job.push(_job)
                        supplyQty.push(_supplyQty)
                        rows_id.push(_rows_id)
                    })
                    
                    const data = {
                        issue_date : trf_indirect_rm_txt_date.value,
                        location_from : trf_indirect_rm_fr_loc.value,
                        location_to : trf_indirect_rm_to_loc.value,
                        userid : uidnya,
                        rows_id : rows_id,
                        model : model,
                        assy_code : assyCode,
                        part_code : partCode,
                        part_name : partName,
                        usage_qty : usage,
                        req_qty : requiredQty,
                        job : job,
                        sup_qty : supplyQty,
                    }
                    
                    if(confirm('Are you sure want to update ?')) {
                        
                        pthis.innerHTML = `<i class="fas fa-spinner fa-spin"></i>`
                        pthis.disabled = true
                        $.ajax({
                            type: "PUT",
                            url: "<?=$_ENV['APP_INTERNAL_API']?>"+`transfer-indirect-rm/form/${trf_indirect_rm_hidden_id.value}`,
                            data: data,
                            dataType: "JSON",
                            success: function (response) {
                                pthis.innerHTML = `<i class="fas fa-save"></i>`
                                pthis.disabled = false
                                alertify.success(response.message)
                            }, error: function(xhr, xopt, xthrow) {
                                pthis.innerHTML = `<i class="fas fa-save"></i>`
                                pthis.disabled = false
                                const respon = Object.keys(xhr.responseJSON)

                                let msg = ''
                                for (const item of respon) {
                                    msg += `<p>${xhr.responseJSON[item]}</p>`
                                }
                                div_alert.innerHTML = `<div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    ${msg}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>`
                                pthis.innerHTML = `<i class="fas fa-save"></i>`
                                alertify.warning(xthrow);
                                pthis.disabled = false
                            }
                        });
                    }
                }
            } else {
                alertify.warning('nothing to be saved')
            }
        } else {
            alertify.message('Could not transfer to same location')
        }
    }

    function trf_indirect_rm_details(data) {
        const div_alert = document.getElementById('div-alert')
        div_alert.innerHTML = `<div class="alert alert-info alert-dismissible fade show" role="alert">
                    Please wait
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>`;
        $.ajax({
            type: 'GET',
            url: "<?=$_ENV['APP_INTERNAL_API']?>" + `transfer-indirect-rm/form/${data.doc}`,
            dataType: "json",
            success: function (response) {
                div_alert.innerHTML = ''
                let datanya = []
                response.data.forEach((arrayItem) => {
                    datanya.push([
                        arrayItem['id'],
                        arrayItem['model'],
                        arrayItem['assy_code'],
                        arrayItem['part_code'],
                        arrayItem['part_name'],
                        arrayItem['usage_qty'],
                        arrayItem['req_qty'],
                        arrayItem['job'],
                        arrayItem['sup_qty'],
                    ])
                })
                trf_indirect_rm_sso_part.setData(datanya)
                localStorage.setItem('transfer_ind_part', JSON.stringify(trf_indirect_rm_sso_part.getData()))

                trf_indirect_rm_btn_save.disabled = data.readOnly

            }, error: function(xhr, xopt, xthrow) {
                const respon = Object.keys(xhr.responseJSON)
                let msg = ''
                for (const item of respon) {
                    msg += `<p>${xhr.responseJSON[item]}</p>`
                }
                div_alert.innerHTML = `<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    ${msg}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>`
                pthis.innerHTML = `<i class="fas fa-save"></i>`
                alertify.warning(xthrow);
                pthis.disabled = false
            }
        });
    }

    function trf_indirect_rm_btn_xls_head_e_click(pthis) {
        if(trf_indirect_rm_hidden_id.value.length === 0) {
            alertify.message('nothing to be exported')
            return
        }
        pthis.classList.add('disabled')
        pthis.innerHTML = 'Please wait'
        $.ajax({
            type: "GET",
            data: {
                userid : uidnya
            },
            url: "<?=$_ENV['APP_INTERNAL_API']?>" + `transfer-indirect-rm/export/${trf_indirect_rm_hidden_id.value}`,
            success: function(response) {
                trf_indirect_rm_btn_save.disabled = true
                const blob = new Blob([response], {
                    type: "application/vnd.ms-excel"
                })
                const fileName = `Transfer ${trf_indirect_rm_txt_date.value}.xlsx`
                saveAs(blob, fileName)
                pthis.innerHTML = `<span style="color: MediumSeaGreen"><i class="fas fa-file-excel"></i></span> Spreadsheet`
                pthis.classList.remove('disabled')
                alertify.success('Done')
            },
            xhr: function() {
                const xhr = new XMLHttpRequest()
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 2) {
                        if (xhr.status == 200) {
                            xhr.responseType = "blob";
                        } else {
                            pthis.innerHTML = `<span style="color: MediumSeaGreen"><i class="fas fa-file-excel"></i></span> Spreadsheet`
                            pthis.classList.remove('disabled')
                            xhr.responseType = "text";
                        }
                    }
                }
                return xhr
            },
            error: function(xhr, xopt, xthrow) {
                pthis.innerHTML = `<span style="color: MediumSeaGreen"><i class="fas fa-file-excel"></i></span> Spreadsheet`
                pthis.classList.remove('disabled')
                const respon = Object.keys(xhr.responseJSON)
                const div_alert = document.getElementById('div-alert')
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

    $("#trf_indirect_rm_ModDocumentList").on('hidden.bs.modal', function() {
        $("#trf_indirect_rm_tblDocumentList tbody").empty();
    });
</script>