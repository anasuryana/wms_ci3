<div style="padding: 10px">
	<div class="container-fluid">
        <div class="row">
            <div class="col-md-12 mb-1" id="div-alert">

            </div>
        </div>
        <div class="row">
            <div class="col-md-9 mb-2">
                <div class="btn-group btn-group-sm">
                    <button title="New" id="itm_process_btnnew" class="btn btn-primary" onclick="itm_process_btnnew_eClick()"><i class="fas fa-file"></i></button>
                    <button title="Save" id="itm_process_btnsave" class="btn btn-primary" onclick="itm_process_btnsave_eClick(this)"><i class="fas fa-save"></i></button>
                    <button title="History" class="btn btn-outline-secondary" type="button" id="itm_process_btnformoditem" onclick="itm_process_btnformoditem_eClick()"><i class="fas fa-search"></i></button>
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
                        <select id="itm_process_srchby" class="form-select">
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
<script>
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
                url: "<?=$_ENV['APP_INTERNAL_API']?>process-master/history",
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
        console.log(inputSS)
        let dataMaster = [];
        const inputLength = inputSS.length
        for(let i=0; i < inputLength; i++) {
            dataMaster.push({
                line_code : inputSS[i][0],
                assy_code : inputSS[i][2],
                model_code : inputSS[i][1],
                model_type : inputSS[i][3],
                process_code : inputSS[i][4],
                cycle_time : numeral(inputSS[i][5]).value(),
            })
        }

        const dataInput = {
            user_id: uidnya,
            master : dataMaster
        }

        if(!confirm('Are you sure ?')) {
            return
        }
        const div_alert = document.getElementById('div-alert')
        div_alert.innerHTML = ''
        pThis.disabled = true
        $.ajax({
            type: "POST",
            url: "<?=$_ENV['APP_INTERNAL_API']?>process-master",
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
</script>
