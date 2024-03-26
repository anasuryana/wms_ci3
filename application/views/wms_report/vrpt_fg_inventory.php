<style>
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
        <div class="row">
            <div class="col-md-12">
                <div id="fg-inventory-div-alert">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-1 text-center">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" type="button" id="fginventory_btn_gen"><i class="fas fa-sync"></i></button>
                    <button class="btn btn-outline-primary" type="button" id="fginventory_btn_edit" title="Edit" data-bs-toggle="modal" data-bs-target="#fginventoryModal"><i class="fas fa-pen"></i></button>
                    <button class="btn btn-success" type="button" id="fginventory_btn_export" onclick="fginventory_btn_export_on_click()"><i class="fas fa-file-excel"></i></button>
                </div>
            </div>
            <div class="col-md-6 mb-1 text-center border-start">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-danger" type="button" id="fginventory_btn_trash"><i class="fas fa-trash"></i></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <span id="fginventory_lblinfo" class="badge bg-info"></span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="fginventory_divku">
                    <table id="fginventory_tbl" class="table table-striped table-bordered table-sm table-hover" style="font-size:85%">
                        <thead class="table-light">
                            <tr class="first">
                                <th  class="align-middle">Assy Number</th>
                                <th  class="align-middle">Model</th>
                                <th  class="align-middle">Lot</th>
                                <th  class="align-middle">Job Number</th>
                                <th  class="text-end">Qty</th>
                                <th  class="text-center">Reff. Number</th>
                                <th  class="text-center">PIC</th>
                                <th  class="text-center">Time</th>
                                <th  class="text-center">Location</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-1 text-end">
            </div>
            <div class="col-md-6 mb-1 text-end">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" >TOTAL</span>
                    <input type="text" class="form-control" id="fginventory_txt_total" readonly>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="fginventoryModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Details</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group input-group-sm mb-1">
                            <span class="input-group-text">Item Code</span>
                            <input type="text" class="form-control" id="fginventoryModalItemFilter" onkeypress="fginventorFilter(event)" maxlength="25">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group input-group-sm mb-1">
                            <span class="input-group-text">Location</span>
                            <input type="text" class="form-control" id="fginventoryModalLocationFilter" onkeypress="fginventorFilter(event)" maxlength="25">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-1">
                        <div class="table-responsive" id="fginventoryModalTableContainer">
                            <table id="fginventoryModalTable" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer">
                                <thead class="table-light">
                                    <tr>
                                        <th>Item Code</th>
                                        <th>Model</th>
                                        <th>ID</th>
                                        <th>Location</th>
                                        <th class="text-end">Qty</th>
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
                        <div class="input-group input-group-sm mb-1">
                            <span class="input-group-text">New Location</span>
                            <input type="text" class="form-control" id="fginventoryModalLocationNew" maxlength="40">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="container">
                    <div class="row">
                        <div class="col text-center">
                            <button class="btn btn-primary btn-sm" onclick="fginventoryModalSaveChanges(this)">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="fginventorySelectedID" value="">
<div id="FGInventoryContextmenu"></div>
<script>
    function fginventory_btn_export_on_click(){
        window.open("http://192.168.0.29:8080/ems-glue/api/export/inventory-fg", '_blank');
    }
    $("#fginventory_divku").css('height', $(window).height()*72/100);
    $("#fginventory_btn_trash").click(function (e) {
        if(confirm("Are You sure ?")){
            const pw = prompt("PIN");
            $.ajax({
                type: "get",
                url: "<?=base_url('Inventory/clearFG')?>",
                data: {inpin: pw},
                dataType: "json",
                success: function (response) {
                    if(response.status[0].cd=='1'){
                        alertify.message(response.status[0].msg);
                        $("#fginventory_tbl tbody").empty();
                    } else {
                        alertify.warning(response.status[0].msg);
                    }
                }, error : function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    });
    $("#fginventory_btn_gen").click(function (e) {
        fginventory_btn_gen.innerHTML = 'Please wait...'
        fginventory_btn_gen.disabled = true
        $.ajax({
            type: "get",
            url: "<?=base_url('Inventory/getlist')?>",
            dataType: "json",
            success: function (response) {
                fginventory_btn_gen.innerHTML = `<i class="fas fa-sync"></i>`
                fginventory_btn_gen.disabled = false
                let ttlrows = response.data.length;
                let mydes = document.getElementById("fginventory_divku");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("fginventory_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("fginventory_tbl");
                let tableku2 = tabell.getElementsByTagName("tbody")[0]
                let newrow, newcell, newText;
                tableku2.innerHTML='';
                let tmpnomor = '';
                let mnomor =0;
                let ttlqty = 0;
                for (let i = 0; i<ttlrows; i++){
                    ttlqty += numeral(response.data[i].CQTY).value();
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0);
                    newcell.innerHTML = response.data[i].CASSYNO;

                    newcell = newrow.insertCell(1);
                    newcell.innerHTML = response.data[i].CMODEL

                    newcell = newrow.insertCell(2);
                    newcell.style.cssText= "white-space: nowrap";
                    newcell.innerHTML = response.data[i].CLOTNO

                    newcell = newrow.insertCell(3);
                    newcell.style.cssText= "white-space: nowrap";
                    newcell.innerHTML = response.data[i].SER_DOC

                    newcell = newrow.insertCell(4);
                    newcell.style.cssText= "white-space: nowrap;text-align:right";
                    newcell.innerHTML = numeral(response.data[i].CQTY).format('0,0')

                    newcell = newrow.insertCell(5);
                    newcell.style.cssText= 'text-align:center';
                    newcell.innerHTML = response.data[i].REFNO
                    newcell.addEventListener("contextmenu", function(e) {
                        FGInventoryPublicVarIndex = e.target.parentNode.rowIndex
                        FGInventoryPublicVar = e.target.innerText
                        FGInventorycontextMenuObj.open(e);
                        e.preventDefault();
                    })

                    newcell = newrow.insertCell(6);
                    newcell.style.cssText= 'text-align:center';
                    newcell.innerHTML = response.data[i].FULLNAME

                    newcell = newrow.insertCell(7);
                    newcell.style.cssText= 'text-align:center';
                    newcell.innerHTML = response.data[i].CDATE

                    newcell = newrow.insertCell(8);
                    newcell.style.cssText= 'text-align:center';
                    newcell.innerHTML = response.data[i].CLOC
                }
                mydes.innerHTML='';
                mydes.appendChild(myfrag);
                document.getElementById('fginventory_lblinfo').innerHTML = ttlrows>0 ? '': 'data not found';
                document.getElementById('fginventory_txt_total').value = numeral(ttlqty).format('0,0');
            }, error : function(xhr, xopt, xthrow){
                fginventory_btn_gen.disabled = false
                fginventory_btn_gen.innerHTML = `<i class="fas fa-sync"></i>`
                alertify.error(xthrow);
            }
        });
    });

    function fginventorFilter(e){
        if(e.key === 'Enter') {
            if(fginventoryModalItemFilter.value.trim().length<=3)
            {
                alertify.warning('Please fill properly')
                fginventoryModalItemFilter.focus()
                return
            }
            if(fginventoryModalLocationFilter.value.trim().length===0)
            {
                alertify.warning('Please fill properly')
                fginventoryModalLocationFilter.focus()
                return
            }
            const data = {
                itemCode : fginventoryModalItemFilter.value,
                itemLocation : fginventoryModalLocationFilter.value,
            }
            $.ajax({
                type: "GET",
                url: "<?=base_url('Inventory/filter-fg')?>",
                data: data,
                dataType: "json",
                success: function (response) {
                    let myContainer = document.getElementById("fginventoryModalTableContainer");
                    let myfrag = document.createDocumentFragment();
                    let cln = fginventoryModalTable.cloneNode(true);
                    myfrag.appendChild(cln);
                    let myTable = myfrag.getElementById("fginventoryModalTable");
                    let myTableBody = myTable.getElementsByTagName("tbody")[0];
                    myTableBody.innerHTML = ''
                    response.data.forEach((arrayItem) => {
                        newrow = myTableBody.insertRow(-1)

                        newrow.onclick = (event) => {
                            const selrow = fginventoryModalTable.rows[event.target.parentElement.rowIndex]
                            if (selrow.title === 'selected') {
                                selrow.title = 'not selected'
                                selrow.classList.remove('table-info')
                                fginventoryModalLocationOld.value = ''
                                fginventoryModalLocationNew.value = ''
                                fginventorySelectedID.value = ''
                            } else {
                                const ttlrows = fginventoryModalTable.rows.length
                                for (let i = 1; i < ttlrows; i++) {
                                    fginventoryModalTable.rows[i].classList.remove('table-info')
                                    fginventoryModalTable.rows[i].title = 'not selected'
                                }
                                selrow.title = 'selected'
                                selrow.classList.add('table-info')
                                fginventoryModalLocationNew.value = arrayItem['CLOC']
                                fginventorySelectedID.value = arrayItem['REFNO']
                            }
                        }

                        newcell = newrow.insertCell(0)
                        newcell.innerHTML = arrayItem['CASSYNO']
                        newcell.style.cssText = 'cursor:pointer'
                        newcell = newrow.insertCell(-1)
                        newcell.innerHTML = arrayItem['CMODEL']
                        newcell = newrow.insertCell(-1)
                        newcell.innerHTML = arrayItem['REFNO']
                        newcell = newrow.insertCell(-1)
                        newcell.innerHTML = arrayItem['CLOC']
                        newcell = newrow.insertCell(-1)
                        newcell.classList.add('text-end')
                        newcell.innerHTML = numeral(arrayItem['CQTY']).format(',')
                    })
                    myContainer.innerHTML = ''
                    myContainer.appendChild(myfrag)
                }, error : function(xhr, xopt, xthrow) {
                    alertify.error(xthrow);
                }
            });
        }
    }

    function fginventoryModalSaveChanges(p) {
        if (fginventoryModalLocationNew.value.trim().length === 0) {
            alertify.warning('Location should not be empty')
            fginventoryModalLocationNew.focus()
            return
        }
        if (fginventorySelectedID.value.trim().length == 0) {
            alertify.message('Please select a row from the table above')
            return
        }
        const data = {
            REFNO : fginventorySelectedID.value,
            CLOC : fginventoryModalLocationNew.value,
        }
        if(confirm('Are you sure ?')) {
            p.disabled = true
            p.innerHTML = 'Please wait'
            $.ajax({
                type: "POST",
                url: "<?=base_url('Inventory/update-fg')?>",
                data: data,
                dataType: "json",
                success: function (response) {
                    p.innerHTML = 'Save changes'
                    p.disabled = false
                    fginventoryModalTable.getElementsByTagName("tbody")[0].innerHTML =''
                    fginventoryModalLocationNew.value = ''
                    alertify.message(response.message);
                }, error : function(xhr, xopt, xthrow) {
                    alertify.error(xthrow);
                    p.disabled = false
                    fginventoryModalTable.getElementsByTagName("tbody")[0].innerHTML =''
                    fginventoryModalLocationNew.value = ''
                }
            })
        }
    }

    var FGInventoryPublicVar, FGInventoryPublicVarIndex

    var FGInventorycontextMenuObj = jSuites.contextmenu(document.getElementById('FGInventoryContextmenu'), {
        items:[
            {
                title:'Remove',
                onclick:function(element, event) {
                    if(confirm("Are you sure want to remove ?")) {
                        const div_alert = document.getElementById('fg-inventory-div-alert')
                        const pw = prompt("PIN");
                        $.ajax({
                            type: "DELETE",
                            url: "<?=$_ENV['APP_INTERNAL_API']?>" + `inventory/keys/${FGInventoryPublicVar}`,
                            data: {pin : pw },
                            dataType: "JSON",
                            success: function (response) {
                                fginventory_tbl.deleteRow(FGInventoryPublicVarIndex)
                                div_alert.innerHTML = ''
                                alertify.success(response.message)
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
                                alertify.warning(xthrow);
                            }
                        });
                    }
                },
                tooltip: 'Remove a line',
            },
            {
                type:'line'
            },
        ],
        onclick:function() {
            FGInventorycontextMenuObj.close(false);
        }
    });
</script>