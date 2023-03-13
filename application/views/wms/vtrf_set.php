<div style="padding: 10px">
    <div class="container-fluid">
        <div class="row" id="trfset_stack0">
            <div class="col-md-6 mb-1 ">
                <div class="card">
                    <div class="card-header text-center">Warehouse Code</div>
                    <div class="card-body d-grid">
                        <div class="btn-group-vertical" role="group" aria-label="Basic radio toggle button group" id="trfset_wh_container">
                            <input type="radio" class="btn-check" name="trfset_radio" id="btnradio1" autocomplete="off" value="RM" checked>
                            <label class="btn btn-outline-success" for="btnradio1">Material</label>
                            <input type="radio" class="btn-check" name="trfset_radio" id="btnradio2" autocomplete="off" value="FG">
                            <label class="btn btn-outline-success" for="btnradio2">Fresh Finished Goods</label>
                            <input type="radio" class="btn-check" name="trfset_radio" id="btnradio3" autocomplete="off" value="FG-RTN">
                            <label class="btn btn-outline-success" for="btnradio3">Return Finished Goods</label>
                            <input type="radio" class="btn-check" name="trfset_radio" id="btnradio4" autocomplete="off" value="SCR">
                            <label class="btn btn-outline-success" for="btnradio4">Scrap</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-1 d-grid text-center">
                <div class="card">
                    <div class="card-header text-center">Person In Charge</div>
                    <div class="card-body d-grid">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col">
                                    <div class="input-group input-group-sm mb-1">
                                        <label class="input-group-text">Name</label>
                                        <input type="text" class="form-control" id="trfset_txt_name" disabled readonly>
                                        <input type="hidden" class="form-control" id="trfset_txt_id" disabled readonly>
                                        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#trfset_ModPersonList"><i class="fas fa-search"></i></button>
                                        <button class="btn btn-outline-primary" id="trfset_btn_save" onclick="trfset_btn_save_eClick(this)"><i class="fas fa-save"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="table-responsive" id="trfset_tblPerson_div">
                                        <table id="trfset_tblPerson" class="table table-sm table-striped table-bordered table-hover" style="width:100%">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="d-none">Line</th>
                                                    <th>ID</th>
                                                    <th>Name</th>
                                                    <th class="text-center">...</th>
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
        </div>
    </div>
</div>
<div class="modal fade" id="trfset_ModPersonList">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">User List</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div class="input-group input-group-sm mb-1">
                            <span class="input-group-text">Search</span>
                            <select id="trfset_srchby" class="form-select" onchange="trfset_txtsearch.focus()">
                                <option value="nm">User Name</option>
                                <option value="id">User Id</option>
                            </select>
                            <input type="text" class="form-control" id="trfset_txtsearch" maxlength="45" onkeypress="trfset_txtsearch_eKP(event)" required placeholder="...">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="table-responsive" id="trfset_Usertbl_div">
                            <table id="trfset_Usertbl" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer">
                                <thead class="table-light">
                                    <tr>
                                        <th>User Id</th>
                                        <th>User Name</th>
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
    trfset_init_wh()

    function trfset_init_wh(){
        trfset_wh_container.innerHTML = 'Please wait...'
        $.ajax({
            url: "<?=base_url('MSTLOC/EquipmentWarehouse')?>",
            dataType: "JSON",
            success: function (response) {
                trfset_wh_container.innerHTML = ''
                response.data.forEach((arrayItem) => {
                    let _EleInput = document.createElement('input')
                    _EleInput.id = arrayItem['MSTLOCG_ID']
                    _EleInput.type = 'radio'
                    _EleInput.autocomplete = 'off'
                    _EleInput.name = 'trfset_radio'
                    _EleInput.value = arrayItem['MSTLOCG_ID']
                    _EleInput.classList.add('btn-check')
                    _EleInput.onclick = () => {
                        trfsetLoadDetail(arrayItem['MSTLOCG_ID'])
                    }
                    trfset_wh_container.append(_EleInput)
                    let _EleLabel = document.createElement('label')
                    _EleLabel.classList.add('btn', 'btn-outline-primary')
                    _EleLabel.innerHTML = arrayItem['MSTLOCG_NM']
                    _EleLabel.setAttribute('for', arrayItem['MSTLOCG_ID'])
                    trfset_wh_container.append(_EleLabel)
                })
            }, error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow);
            }
        });
    }

    function trfsetLoadDetail(warehouse){
        trfset_tblPerson.getElementsByTagName('tbody')[0].innerHTML = `<tr><td colspan="3" class="text-center">Please wait</td></tr>`
        $.ajax({
            url: "<?=base_url('TRF/ApprovalList')?>",
            data: {warehouse : warehouse},
            dataType: "json",
            success: function (response) {
                trfsetPopulateTable(response)
            }, error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow);
                trfset_tblPerson.getElementsByTagName('tbody')[0].innerHTML = `<tr><td colspan="3" class="text-center">Please try again</td></tr>`
            }
        })
    }

    function trfsetPopulateTable(respons){
        let mydes = document.getElementById("trfset_tblPerson_div");
        let myfrag = document.createDocumentFragment();
        let cln = trfset_tblPerson.cloneNode(true);
        myfrag.appendChild(cln);
        let tabell = myfrag.getElementById("trfset_tblPerson");
        let tableku2 = tabell.getElementsByTagName("tbody")[0];
        tableku2.innerHTML = '';
        respons.data.forEach((arrayItem) => {
            newrow = tableku2.insertRow(-1)
            newcell = newrow.insertCell(0)
            newcell.classList.add('d-none')
            newcell.innerHTML = arrayItem['TRFSET_LINE']
            newcell = newrow.insertCell(1)
            newcell.innerHTML = arrayItem['MSTEMP_ID']
            newcell = newrow.insertCell(2)
            newcell.innerHTML = arrayItem['APPROVER']
            newcell = newrow.insertCell(3)
            newcell.onclick = () => {
                let elem = document.getElementsByName('trfset_radio');
                let wh = ''
                for(let x of elem.values()){
                    if(x.checked){
                        wh = x.value
                    }
                }
                if(confirm(`Are you sure want to revoke ${arrayItem['APPROVER']} from ${wh} ?`))
                {
                    $.ajax({
                        type: "POST",
                        url: "<?=base_url('TRF/revoke')?>",
                        data: {id: arrayItem['TRFSET_LINE'], wh: wh},
                        dataType: "json",
                        success: function (response) {
                            alertify.message(response.status[0].msg)
                            trfsetPopulateTable(response)
                        }, error: function(xhr, xopt, xthrow) {
                            alertify.error('Please try again');
                        }
                    });
                }
            }
            newcell.style.cssText = 'cursor:pointer'
            newcell.innerHTML = `<span class="fas fa-trash text-danger"></span>`
        })
        mydes.innerHTML = ''
        mydes.appendChild(myfrag)
    }

    $("#trfset_ModPersonList").on('shown.bs.modal', function() {
        document.getElementById('trfset_txtsearch').focus()
    })

    function trfset_txtsearch_eKP(e){
        if(e.key === 'Enter') {
            trfset_Usertbl.getElementsByTagName("tbody")[0].innerHTML = '<tr><td class="text-center" colspan="2">Please wait</td></tr>'
            $.ajax({
                url: "<?=base_url('User/search_registered')?>",
                data: {search : e.target.value, searchby : trfset_srchby.value},
                dataType: "json",
                success: function (response) {
                    let mydes = document.getElementById("trfset_Usertbl_div");
                    let myfrag = document.createDocumentFragment();
                    let cln = trfset_Usertbl.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("trfset_Usertbl");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    tableku2.innerHTML = '';
                    response.data.forEach((arrayItem) => {
                        newrow = tableku2.insertRow(-1)
                        newrow.onclick = () => {
                            $("#trfset_ModPersonList").modal('hide')
                            trfset_txt_name.value = arrayItem['user_nicename']
                            trfset_txt_id.value = arrayItem['ID']
                        }                        
                        newcell = newrow.insertCell(0)
                        newcell.innerHTML = arrayItem['ID']
                        newcell = newrow.insertCell(1)
                        newcell.innerHTML = arrayItem['user_nicename']
                    })
                    mydes.innerHTML = ''
                    mydes.appendChild(myfrag)
                }, error: function(xhr, xopt, xthrow) {
                    alertify.error(xthrow);
                    trfset_Usertbl.getElementsByTagName("tbody")[0].innerHTML = '<tr><td class="text-center" colspan="2">Please wait</td></tr>'
                }
            });
        }
    }

    function trfset_btn_save_eClick(p){
        let elem = document.getElementsByName('trfset_radio');
        let wh = ''
        for(let x of elem.values()){
            if(x.checked){
                wh = x.value
            }
        }
        if(trfset_txt_id.value.trim().length === 0){
            alertify.warning('nothing to be saved')
            return
        }
        if(wh.length === 0){
            alertify.warning('Please select a warehouse first')
            return
        }
        if(confirm('Are you sure ?')){
            p.disabled = true
            $.ajax({
                type: "POST",
                url: "<?=base_url('TRF/ApprovalSet')?>",
                data: {id : trfset_txt_id.value, wh: wh },
                dataType: "json",
                success: function (response) {
                    p.disabled = false
                    alertify.message(response.status[0].msg)
                    trfsetPopulateTable(response)
                }, error: function(xhr, xopt, xthrow) {
                    alertify.error('please try again');
                    p.disabled = false
                }
            });
        }
    }

</script>