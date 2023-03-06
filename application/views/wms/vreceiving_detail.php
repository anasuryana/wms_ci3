<style type="text/css">
    .tagbox-remove {
        display: none;
    }

    .txfg_cell:hover {
        font-weight: 900;
    }

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
<div style="padding: 5px">
    <div class="container-fluid">
        <div class="row" id="rcvdet_stack1">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">PO Number</label>
                    <input type="text" class="form-control" id="rcvdet_txt_doc" readonly maxlength="100" disabled>
                    <button class="btn btn-primary" id="rcvdet_btnmod" data-bs-toggle="modal" data-bs-target="#rcvdet_ModDocumentList"><i class="fas fa-search"></i></button>
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <button class="btn btn-primary btn-sm" id="rcvdet_btnmod" onclick="rcvdet_btnsave_eC(this)"><i class="fas fa-save"></i></button>
            </div>
        </div>
        <div class="row" id="rcvdet_stack1">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">DO Number</label>
                    <input type="text" class="form-control" id="rcvdet_do" readonly maxlength="100" disabled>
                </div>
            </div>
            <div class="col-md-6 mb-1">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-1">
                <div class="table-responsive" id="rcvdet_divku_1">
                    <table id="rcvdet_tbl_1" class="table table-sm table-hover table-bordered" style="width:100%;font-size:75%">
                        <thead class="table-light">
                            <tr class="first">
                                <th>Item Code</th> <!-- 1 -->
                                <th>Item Name</th> <!-- 2 -->
                                <th class="text-end">QTY</th> <!-- 3 -->
                                <th title="Unit Measurement">UM</th> <!-- 4 -->
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div id="rcvdet_ss"></div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="rcvdet_ModDocumentList">
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
                            <select id="rcvdet_searchby" class="form-select" onchange="document.getElementById('rcvdet_search').focus()">
                                <option value="ic">Item Code</option>
                                <option value="sup">Supplier</option>
                                <option value="in">Item Name</option>
                            </select>
                            <input type="text" class="form-control" id="rcvdet_search" onkeypress="rcvdet_search_eKP(event)" maxlength="40" required placeholder="Press 'Enter' to search">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="table-responsive" id="rcvdet_tblDocumentList_div">
                            <table id="rcvdet_tblDocumentList" class="table table-sm table-striped table-bordered table-hover" style="width:100%">
                                <thead class="table-light">
                                    <tr>
                                        <th>PO Number</th>
                                        <th>DO Number</th>
                                        <th>Supplier</th>
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
    var rcvdet_pub_data = [
        [''],
        [''],
        [''],
    ];
    var rcvdet_global_var = '';
    var rcvdet_global_var_item_code = '';
    var rcvdet_global_var_item_qty = 0;
    var rcvdet_pub_ss = jspreadsheet(document.getElementById('rcvdet_ss'), {
        data: rcvdet_pub_data,
        columns: [{
                type: 'text',
                title: 'line',
                width: '2',
                readOnly: true
            },
            {
                type: 'text',
                title: 'Serial Number',
                width: 150,
                align: 'left'
            },
            {
                type: 'text',
                title: 'Asset Number',
                width: 150
            }
        ]
    });
    $("#rcvdet_ModDocumentList").on('shown.bs.modal', function() {
        document.getElementById('rcvdet_search').focus()
    })

    function rcvdet_btnsave_eC(p)
    {
        if(rcvdet_global_var_item_code.trim().length === 0)
        {
            alertify.message('Nothing to be saved')
            return
        }
        alertify.confirm('Are you sure ?', `Save ${rcvdet_global_var_item_code}`,
        function(){
            let datanya = rcvdet_pub_ss.getData()
            let TotalRows = datanya.length
            let SaveID = []
            let SaveSerialNumber = []
            let SaveAssetNumber = []
            for(let i=0;i<TotalRows; i++)
            {
                // abaikan baris baru yang tidak ada datanya
                if(datanya[i][0].length>0 || (datanya[i][1].trim().length>0 || datanya[i][2].trim().length>0)){
                    SaveID.push(datanya[i][0])
                    SaveSerialNumber.push(datanya[i][1])
                    SaveAssetNumber.push(datanya[i][2])
                }
            }
            if(rcvdet_global_var_item_qty === TotalRows) {
                p.disabled = true                
                $.ajax({
                    type: "POST",
                    url: "<?=base_url('PO/DOPOSerialSave')?>",
                    data: {po : rcvdet_txt_doc.value, do: rcvdet_do.value, item_code: rcvdet_global_var_item_code
                     , SaveID : SaveID,SaveSerialNumber : SaveSerialNumber, SaveAssetNumber : SaveAssetNumber},
                    dataType: "JSON",
                    success: function (response) {
                        p.disabled = false
                        alertify.message(response.status[0].msg)
                    },error: function(xhr, xopt, xthrow) {
                        alertify.error(xthrow);
                        p.disabled = false
                    }
                });
            } else {
                alertify.message(`maximum rows is ${numeral(rcvdet_global_var_item_qty).format(',')}`)
            }
        }
        , function(){

        });
    }
    function rcvdet_search_eKP(e)
    {
        if (e.key === 'Enter')
        {
            e.disabled = true
            let mtabel = document.getElementById("rcvdet_tblDocumentList");
            mtabel.getElementsByTagName('tbody')[0].innerHTML = `<tr><td colspan="3" class="text-center">Please wait...</td></tr>`
            $.ajax({
                url: "<?=base_url('PO/DOPO')?>",
                data: {searchBy: rcvdet_searchby.value, search : rcvdet_search.value},
                dataType: "JSON",
                success: function (response) {
                    e.disabled = false
                    const ttlrows = response.data.length
                    let mydes = document.getElementById("rcvdet_tblDocumentList_div");
                    let myfrag = document.createDocumentFragment();
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("rcvdet_tblDocumentList");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    tableku2.innerHTML = '';
                    response.data.forEach((arrayItem) => {
                        newrow = tableku2.insertRow(-1)
                        newcell = newrow.insertCell(0)
                        newcell.onclick = () => {
                            rcvdet_txt_doc.value = arrayItem['RCV_PO']
                            rcvdet_do.value = arrayItem['RCV_DONO']
                            rcvdet_detail({po: arrayItem['RCV_PO'], do: arrayItem['RCV_DONO']})
                            $("#rcvdet_ModDocumentList").modal('hide')
                        }
                        newcell.innerHTML = arrayItem['RCV_PO']
                        newcell.style.cssText = 'cursor: pointer'
                        newcell = newrow.insertCell(1)
                        newcell.innerHTML = arrayItem['RCV_DONO']
                        newcell = newrow.insertCell(2)
                        newcell.innerHTML = arrayItem['MSUP_SUPNM']
                    })
                    mydes.innerHTML = ''
                    mydes.appendChild(myfrag)
                },
                error: function(xhr, xopt, xthrow) {
                    alertify.error(xthrow);
                    e.disabled = false
                    mtabel.getElementsByTagName('tbody')[0].innerHTML = `<tr><td colspan="3" class="text-center">Please try again</td></tr>`
                }
            })
        }
    }

    function rcvdet_detail(param)
    {
        rcvdet_global_var = ''
        let mtabel = document.getElementById("rcvdet_tbl_1");
        mtabel.getElementsByTagName('tbody')[0].innerHTML = `<tr><td colspan="4" class="text-center">Please wait...</td></tr>`
        $.ajax({
            url: "<?=base_url('PO/DOPODetail')?>",
            data: {do: param.do, po : param.po},
            dataType: "JSON",
            success: function (response) {
                const ttlrows = response.data.length
                let mydes = document.getElementById("rcvdet_divku_1");
                let myfrag = document.createDocumentFragment();
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("rcvdet_tbl_1");
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                tableku2.innerHTML = '';
                let i = 0
                response.data.forEach((arrayItem) => {
                    newrow = tableku2.insertRow(-1)
                    newcell = newrow.insertCell(0)
                    newcell.innerHTML = arrayItem['ITMCD']
                    newcell.style.cssText = 'cursor: pointer'
                    newcell.onclick = function(event) {
                        rcvdet_global_var_item_code = arrayItem['ITMCD']
                        rcvdet_global_var_item_qty = arrayItem['RQT']*1
                        rcvdetGetSerial({po: rcvdet_txt_doc.value, do: rcvdet_do.value, item_code: arrayItem['ITMCD'], item_qty : arrayItem['RQT']})
                        if(rcvdet_global_var!==''){
                            tableku2.rows[rcvdet_global_var].classList.remove('table-info')
                        }
                        tableku2.rows[event.target.parentNode.rowIndex-1].classList.add('table-info')
                        rcvdet_global_var = event.target.parentNode.rowIndex-1
                    }
                    newcell = newrow.insertCell(1)
                    newcell.innerHTML = arrayItem['ITMD1']
                    newcell = newrow.insertCell(2)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = numeral(arrayItem['RQT']).format(',')
                    newcell = newrow.insertCell(3)
                    newcell.innerHTML = arrayItem['UM']
                    i++
                })
                mydes.innerHTML = ''
                mydes.appendChild(myfrag)
            },
            error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow);
                mtabel.getElementsByTagName('tbody')[0].innerHTML = `<tr><td colspan="4" class="text-center">Please try again</td></tr>`
            }
        })
    }

    function rcvdetGetSerial(param)
    {
        let datanya = [];
        datanya.push(['', 'please wait...', ''])
        rcvdet_pub_ss.setData(datanya)
        $.ajax({
            url: "<?=base_url('PO/DOPOSerial')?>",
            data: {do: param.do, po : param.po, item_code: param.item_code, item_qty : param.item_qty},
            dataType: "JSON",
            success: function (response) {
                if(response.status){
                    alertify.warning(response.status[0].msg)
                } else {
                    datanya = [];
                    response.data.forEach((arrayItem) => {
                        datanya.push([
                            arrayItem['RCVD_ID'],
                            arrayItem['RCVD_SERNNUM'],
                            arrayItem['RCVD_ASSETNUM'],
                        ])
                    })
                    rcvdet_pub_ss.setData(datanya)
                }
            },
            error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow);
            }
        })
    }
</script>
