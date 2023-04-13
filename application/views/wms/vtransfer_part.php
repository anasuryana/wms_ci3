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
        <div class="row" id="trfnonref_stack1">
            <div class="col-md-6 mb-1">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary" id="trfnonref_btn_new" onclick="trfnonref_btn_new_eC()"><i class="fas fa-file"></i></button>
                    <button class="btn btn-outline-primary" id="trfnonref_btn_save" onclick="trfnonref_btn_save_eC(this)"><i class="fas fa-save"></i></button>
                </div>
            </div>
            <div class="col-md-6 mb-1 text-end">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-info" id="trfnonref_btn_info" data-bs-toggle="modal" data-bs-target="#trfnonref_ModDocumentProperties" title="Document properties"><i class="fas fa-info"></i></button>
                </div>
            </div>
        </div>
        <div class="row" id="trfnonref_stack2">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Document Number</label>
                    <input type="text" class="form-control" id="trfnonref_txt_doc" disabled readonly title="autonumber">
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#trfnonref_ModDocumentList"><i class="fas fa-search"></i></button>
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Document Date</label>
                    <input type="text" class="form-control" id="trfnonref_txt_date" readonly>
                </div>
            </div>
        </div>
        <div class="row" id="trfnonref_stack3">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">From Location</label>
                    <select id="trfnonref_fr_loc" class="form-select" onchange="document.getElementById('trfnonref_to_loc').focus()">
                    <?=$fwh?>
                    </select>
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">To Location</label>
                    <select id="trfnonref_to_loc" class="form-select"><?=$lwh?></select>
                </div>
            </div>
        </div>
        <div class="row" id="trfnonref_stack4">
            <div class="col-md-12 mb-1">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" id="trfnonref_btn_plus" onclick="trfnonref_btn_plus_eC()"><i class="fas fa-plus"></i></button>
                    <button class="btn btn-warning" id="trfnonref_btn_minus" onclick="trfnonref_btn_minus_eC()"><i class="fas fa-minus"></i></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="trfnonref_tblcontainer">
                    <table id="trfnonref_tbl" class="table table-sm table-striped table-bordered table-hover" style="width:100%">
                        <thead class="table-light">
                            <tr class="first">
                                <th class="d-none">LineID</th>
                                <th>Item Code</th>
                                <th class="text-end">QTY</th>
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
<div class="modal fade" id="trfnonref_ModItemList">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
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
                            <span class="input-group-text">Search</span>
                            <select id="trfnonref_searchby" class="form-select" onchange="trfnonref_search.focus()">
                                <option value="ic">Item Code</option>
                                <option value="in">Description</option>
                                <option value="po">PO Number</option>
                                <option value="do">DO Number</option>
                            </select>
                            <input type="text" class="form-control" id="trfnonref_search" onkeypress="trfnonref_search_eKP(event)" maxlength="40" required placeholder="Press 'Enter' to search">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="table-responsive" id="trfnonref_tblItemList_div">
                            <table id="trfnonref_tblItemList" class="table table-sm table-striped table-bordered table-hover" style="width:100%">
                                <thead class="table-light">
                                    <tr>
                                        <th>Item Code</th>
                                        <th>Description</th>
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
<div class="modal fade" id="trfnonref_ModDocumentList">
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
                            <select id="trfnonref_search_document_by" class="form-select" onchange="trfnonref_search_document.focus()">
                                <option value="ic">Item Code</option>
                                <option value="in">Description</option>
                            </select>
                            <input type="text" class="form-control" id="trfnonref_search_document" onkeypress="trfnonref_search_document_eKP(event)" maxlength="40" required placeholder="Press 'Enter' to search">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="table-responsive" id="trfnonref_tblDocumentList_div">
                            <table id="trfnonref_tblDocumentList" class="table table-sm table-striped table-bordered table-hover" style="width:100%">
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
<div class="modal fade" id="trfnonref_ModDocumentProperties">
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
                            <input type="text" class="form-control" id="trfnonref_property_created_by" readonly disabled>
                        </div>
                    </div>
                    <div class="col">
                        <div class="input-group input-group-sm mb-1">
                            <span class="input-group-text">Created at</span>
                            <input type="text" class="form-control" id="trfnonref_property_created_at" readonly disabled>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="input-group input-group-sm mb-1">
                            <span class="input-group-text">Updated by</span>
                            <input type="text" class="form-control" id="trfnonref_property_updated_by" readonly disabled>
                        </div>
                    </div>
                    <div class="col">
                        <div class="input-group input-group-sm mb-1">
                            <span class="input-group-text">Updated at</span>
                            <input type="text" class="form-control" id="trfnonref_property_updated_at" readonly disabled>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var trfnonref_pub_index = ''

    function trfnonref_search_document_eKP(e){
        if(e.key === 'Enter'){
            e.target.readOnly = true
            trfnonref_tblDocumentList.getElementsByTagName('tbody')[0].innerHTML = '<tr><td colspan="5" class="text-center">Please wait...</td></tr>'
            $.ajax({
                url: "<?=base_url('TRFHistory/search')?>",
                data: {search : e.target.value, searchBy : trfnonref_search_document_by.value},
                dataType: "json",
                success: function (response) {
                    e.target.readOnly = false
                    let mydes = document.getElementById("trfnonref_tblDocumentList_div");
                    let myfrag = document.createDocumentFragment();
                    let cln = trfnonref_tblDocumentList.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("trfnonref_tblDocumentList");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    tableku2.innerHTML = '';
                    response.data.forEach((arrayItem) => {
                        newrow = tableku2.insertRow(-1)
                        newcell = newrow.insertCell(0)
                        newcell.innerHTML = arrayItem['TRFH_DOC']
                        newcell.style.cssText = 'cursor: pointer'
                        newcell.classList.add('bg-info')
                        newcell.onclick = () => {
                            $("#trfnonref_ModDocumentList").modal('hide')
                            trfnonref_txt_doc.value = arrayItem['TRFH_DOC']
                            trfnonref_txt_date.value = arrayItem['TRFH_ISSUE_DT']
                            trfnonref_fr_loc.value = arrayItem['TRFH_LOC_FR']
                            trfnonref_to_loc.value = arrayItem['TRFH_LOC_TO']
                            trfnonref_property_created_by.value = arrayItem['PIC']
                            trfnonref_property_created_at.value = arrayItem['TRFH_CREATED_DT']
                            let isShouldReadonly = false
                            if(arrayItem['TRFH_CREATED_BY']!=uidnya){
                                isShouldReadonly = true
                            }
                            trfnonref_getSavedData({doc:arrayItem['TRFH_DOC'], readonly: isShouldReadonly })
                        }
                        newcell = newrow.insertCell(1)
                        newcell.innerHTML = arrayItem['TRFH_ISSUE_DT']
                        newcell = newrow.insertCell(2)
                        newcell.innerHTML = arrayItem['PIC']
                        newcell = newrow.insertCell(3)
                        newcell.innerHTML = arrayItem['TRFH_LOC_FR']
                        newcell = newrow.insertCell(4)
                        newcell.innerHTML = arrayItem['TRFH_LOC_TO']
                    })
                    mydes.innerHTML = ''
                    mydes.appendChild(myfrag)
                }, error: function(xhr, xopt, xthrow) {
                    alertify.error(xthrow);
                    e.target.readOnly = false
                    trfnonref_tblDocumentList.getElementsByTagName('tbody')[0].innerHTML = '<tr><td colspan="5" class="text-center">please try again...</td></tr>'
                }
            });
        }
    }

    function trfnonref_getSavedData(param){
        $.ajax({
            url: "<?=base_url('TRFHistory/getDetail')?>",
            data: {doc : param.doc},
            dataType: "json",
            success: function (response) {
                if(param.readonly){
                    trfnonref_loadSavedDataRedOnly(response)
                    trfnonref_btn_minus.disabled = true
                    trfnonref_btn_plus.disabled = true
                    trfnonref_btn_save.disabled = true
                } else {
                    trfnonref_btn_minus.disabled = false
                    trfnonref_btn_plus.disabled = false
                    trfnonref_btn_save.disabled = false
                    trfnonref_loadSavedData(response)
                }
            }, error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow);
            }
        });
    }

    function trfnonref_btn_new_eC(){
        trfnonref_txt_doc.value = ''
        $("#trfnonref_txt_date").datepicker('update', new Date())
        trfnonref_tbl.getElementsByTagName('tbody')[0].innerHTML = ''
        trfnonref_btn_minus.disabled = false
        trfnonref_btn_plus.disabled = false
        trfnonref_btn_save.disabled = false
        trfnonref_property_created_by.value = ''
        trfnonref_property_created_at.value = ''
    }

    $("#trfnonref_txt_date").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    })

    $("#trfnonref_txt_date").datepicker('update', new Date())
    function trfnonref_btn_save_eC(p){
        if(trfnonref_txt_doc.value.length === 0)
        {
            let tableku2 = trfnonref_tbl.getElementsByTagName("tbody")[0]
            let mtbltr = tableku2.getElementsByTagName('tr')
            let ttlrows = mtbltr.length
            let LineID = []
            let LineItemCode = []
            let LineItemQty = []
            for (let i = 0; i < ttlrows; i++) {
                let _itemCode = tableku2.rows[i].cells[1].innerText.trim()
                let _itemQty = numeral(tableku2.rows[i].cells[2].innerText.trim()).value()
                if(_itemCode.length>0 && _itemQty > 0)
                {
                    LineID.push(tableku2.rows[i].cells[0].innerText)
                    LineItemCode.push(_itemCode)
                    LineItemQty.push(_itemQty)
                } else {
                    alertify.message('please recheck the data')
                    tableku2.rows[i].cells[2].focus()
                    return
                }
            }
            if(LineItemCode.length === 0)
            {
                alertify.message('nothing to be saved')
                return
            }
            if(trfnonref_fr_loc.value === trfnonref_to_loc.value){
                alertify.warning('could not transfer to same location')
                return
            }

            if(trfnonref_to_loc.value === 'ENGLINEEQUIP' && trfnonref_fr_loc.value!= 'ENGEQUIP'){
                alertify.warning('Engineering line Equipment Location is only from Engineering Equipment Location')
                return
            }
            alertify.confirm('Are you sure ?', `Transfer from ${trfnonref_fr_loc.value} to ${trfnonref_to_loc.value}`,
            function(){
                p.disabled = true
                $.ajax({
                    type: "POST",
                    url: "<?=base_url('TRF/saveDraft')?>",
                    data: {doc : trfnonref_txt_doc.value, frLoc : trfnonref_fr_loc.value
                        , toLoc : trfnonref_to_loc.value, docDate : trfnonref_txt_date.value
                        , LineID : LineID
                        , LineItemCode : LineItemCode
                        , LineItemQty : LineItemQty
                    },
                    dataType: "json",
                    success: function (response) {
                        p.disabled = false
                        if(response.status[0].cd === '1'){
                            alertify.message(response.status[0].msg)
                            trfnonref_txt_doc.value = response.status[0].reff
                            trfnonref_loadSavedData(response)
                        } else {                            
                            alertify.warning(response.status[0].msg)
                        }
                    }, error: function(xhr, xopt, xthrow) {
                        alertify.error(xthrow);
                        p.disabled = false
                    }
                });
            }, function(){

            })
        } else {
            let LineID = '-'
            let LineItemCode = ''
            let LineItemQty = ''
            if(trfnonref_pub_index === ''){

            } else {
                LineID = trfnonref_tbl.getElementsByTagName('tbody')[0].getElementsByTagName('tr')[trfnonref_pub_index-1].cells[0].innerText
                LineItemCode = trfnonref_tbl.getElementsByTagName('tbody')[0].getElementsByTagName('tr')[trfnonref_pub_index-1].cells[1].innerText
                LineItemQty = trfnonref_tbl.getElementsByTagName('tbody')[0].getElementsByTagName('tr')[trfnonref_pub_index-1].cells[2].innerText*1
            }
            alertify.confirm('Are you sure want to update ?', `Transfer from ${trfnonref_fr_loc.value} to ${trfnonref_to_loc.value}`,
            function(){
                p.disabled = true
                $.ajax({
                    type: "POST",
                    url: "<?=base_url('TRF/updateDraft')?>",
                    data: {doc : trfnonref_txt_doc.value, frLoc : trfnonref_fr_loc.value
                        , toLoc : trfnonref_to_loc.value, docDate : trfnonref_txt_date.value
                        , LineID : LineID
                        , LineItemCode : LineItemCode
                        , LineItemQty : LineItemQty
                    },
                    dataType: "json",
                    success: function (response) {
                        p.disabled = false
                        alertify.message(response.status[0].msg)
                        trfnonref_loadSavedData(response)
                    }, error: function(xhr, xopt, xthrow) {
                        alertify.error('Please try again');
                        p.disabled = false
                    }
                });
            }, function(){

            })
        }
    }

    function trfnonref_loadSavedData(SavedData){
        let mydes = document.getElementById("trfnonref_tblcontainer");
        let myfrag = document.createDocumentFragment();
        let cln = trfnonref_tbl.cloneNode(true);
        myfrag.appendChild(cln);
        let tabell = myfrag.getElementById("trfnonref_tbl");
        let tableku2 = tabell.getElementsByTagName("tbody")[0];
        tableku2.innerHTML = '';
        SavedData.data.forEach((arrayItem) => {
            newrow = tableku2.insertRow(-1)
            newrow.onclick = function(event) {
                trfnonref_pub_index = event.target.parentNode.rowIndex
            }
            newcell = newrow.insertCell(0)
            newcell.innerHTML = arrayItem['TRFD_LINE']
            newcell.classList.add('d-none')
            newcell = newrow.insertCell(1)
            newcell.innerHTML = arrayItem['TRFD_ITEMCD']
            newcell.style.cssText = 'cursor: pointer'
            newcell.classList.add('bg-info')
            newcell.onclick = (event) => {
                $("#trfnonref_ModItemList").modal('show')
            }
            newcell = newrow.insertCell(2)
            newcell.innerHTML = arrayItem['TRFD_QTY']*1
            newcell.contentEditable = true
            newcell.classList.add('text-end')
        })
        mydes.innerHTML = ''
        mydes.appendChild(myfrag)
    }
    function trfnonref_loadSavedDataRedOnly(SavedData){
        let mydes = document.getElementById("trfnonref_tblcontainer");
        let myfrag = document.createDocumentFragment();
        let cln = trfnonref_tbl.cloneNode(true);
        myfrag.appendChild(cln);
        let tabell = myfrag.getElementById("trfnonref_tbl");
        let tableku2 = tabell.getElementsByTagName("tbody")[0];
        tableku2.innerHTML = '';
        SavedData.data.forEach((arrayItem) => {
            newrow = tableku2.insertRow(-1)
            newcell = newrow.insertCell(0)
            newcell.innerHTML = arrayItem['TRFD_LINE']
            newcell.classList.add('d-none')
            newcell = newrow.insertCell(1)
            newcell.innerHTML = arrayItem['TRFD_ITEMCD']
            newcell = newrow.insertCell(2)
            newcell.innerHTML = arrayItem['TRFD_QTY']*1
            newcell.classList.add('text-end')
        })
        mydes.innerHTML = ''
        mydes.appendChild(myfrag)
    }

    $("#trfnonref_tblcontainer").css('height', $(window).height()
        -document.getElementById('trfnonref_stack1').offsetHeight
        -document.getElementById('trfnonref_stack2').offsetHeight
        -document.getElementById('trfnonref_stack3').offsetHeight
        -document.getElementById('trfnonref_stack4').offsetHeight
        -100);
    function trfnonref_btn_plus_eC(){
        let mytbody = trfnonref_tbl.getElementsByTagName('tbody')[0]
        let newrow, newcell
        newrow = mytbody.insertRow(-1)
        newrow.onclick = function(event) {
            trfnonref_pub_index = event.target.parentNode.rowIndex
        }
        newcell = newrow.insertCell(0)
        newcell.classList.add('d-none')

        newcell = newrow.insertCell(1)
        newcell.onclick = (event) => {
            $("#trfnonref_ModItemList").modal('show')
        }
        newcell.classList.add('bg-info')
        newcell.style.cssText = 'cursor: pointer'
        newcell.focus()
        newcell.innerHTML = ''

        newcell = newrow.insertCell(2)
        newcell.contentEditable = true
        newcell.innerHTML = '0'
        newcell.classList.add('text-end')
    }

    function trfnonref_btn_minus_eC(){
        if(trfnonref_txt_doc.value.length===0)
        {
            trfnonref_tbl.getElementsByTagName('tbody')[0].getElementsByTagName('tr')[trfnonref_pub_index-1].remove()
        } else {
            let lineId = trfnonref_tbl.getElementsByTagName('tbody')[0].getElementsByTagName('tr')[trfnonref_pub_index-1].cells[0].innerText
            let ItemCode = trfnonref_tbl.getElementsByTagName('tbody')[0].getElementsByTagName('tr')[trfnonref_pub_index-1].cells[1].innerText
            if(confirm(`Are you sure want to delete ${ItemCode} ?`)){
                $.ajax({
                    type: "POST",
                    url: "<?=base_url('TRF/removeDraft')?>",
                    data: {doc : trfnonref_txt_doc.value, lineId : lineId },
                    dataType: "JSON",
                    success: function (response) {
                        alertify.message(response.status[0].msg)
                        trfnonref_tbl.getElementsByTagName('tbody')[0].getElementsByTagName('tr')[trfnonref_pub_index-1].remove()
                    }, error: function(xhr, xopt, xthrow) {
                        alertify.error('Sorry, something went wrong, please recall the document number');
                    }
                });
            }
        }
    }

    $("#trfnonref_ModItemList").on('shown.bs.modal', function() {
        document.getElementById('trfnonref_search').focus()
    })
    $("#trfnonref_ModDocumentList").on('shown.bs.modal', function() {
        trfnonref_tblDocumentList.getElementsByTagName('tbody')[0].innerHTML = ''
        document.getElementById('trfnonref_search_document').focus()
    })

    function trfnonref_search_eKP(e)
    {
        if(e.key === 'Enter')
        {
            e.target.readOnly = true
            let mtabel = document.getElementById("trfnonref_tblItemList");
            mtabel.getElementsByTagName('tbody')[0].innerHTML = `<tr><td colspan="2" class="text-center">Please wait...</td></tr>`
            $.ajax({
                url: "<?=base_url('MSTITM/search_internal_item')?>",
                data: {searchBy : trfnonref_searchby.value, search : trfnonref_search.value},
                dataType: "json",
                success: function (response) {
                    e.target.readOnly = false
                    let mydes = document.getElementById("trfnonref_tblItemList_div");
                    let myfrag = document.createDocumentFragment();
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("trfnonref_tblItemList");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    tableku2.innerHTML = '';
                    response.data.forEach((arrayItem) => {
                        newrow = tableku2.insertRow(-1)
                        newcell = newrow.insertCell(0)
                        newcell.onclick = () => {
                            trfnonref_tbl.getElementsByTagName('tbody')[0].rows[trfnonref_pub_index-1].cells[1].innerHTML = arrayItem['MITM_ITMCD']
                            $("#trfnonref_ModItemList").modal('hide')
                        }
                        newcell.classList.add('bg-info')
                        newcell.style.cssText = 'cursor: pointer'
                        newcell.innerHTML = arrayItem['MITM_ITMCD']
                        newcell = newrow.insertCell(1)
                        newcell.innerHTML = arrayItem['MITM_ITMD1']
                    })
                    mydes.innerHTML = ''
                    mydes.appendChild(myfrag)
                }, error: function(xhr, xopt, xthrow) {
                    alertify.error(xthrow);
                    e.target.readOnly = false
                    mtabel.getElementsByTagName('tbody')[0].innerHTML = `<tr><td colspan="2" class="text-center">${xthrow}, try again or contact administrator</td></tr>`
                }
            })
        }
    }
</script>