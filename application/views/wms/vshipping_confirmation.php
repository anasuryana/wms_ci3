<div style="padding: 10px">
	<div class="container-fluid">
        <div class="row">
            <div class="col-md-12 mb-1 text-center">
                <button class="btn btn-sm btn-outline-primary" id="si_conf_btn_sync" onclick="si_conf_btn_sync_e_click()"><i class="fas fa-sync"></i></button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <table id="si_conf_tbl" class="table table-sm table-striped table-bordered table-hover compact" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">TX ID</th>
                            <th class="text-center">Customer DO</th>
                            <th class="text-center">Customs Date</th>
                            <th class="text-center">Remark</th>
                            <th class="text-center align-middle">Str.Loc</th>
                            <th ></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
	</div>
</div>
<!-- Modal -->
<div class="modal fade" id="modalPlatNomor" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Confirmation</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="platNomor" class="form-label">TX ID</label>
                        <input type="text" class="form-control" id="inputTXID" disabled>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="platNomor" class="form-label">Vehicle Registration Number</label>
                        <div class="input-group input-group-sm">
                            <select class="form-control" id="platNomor">
                                <option value="-">-</option>
                                <?=$ltrans?>
                            </select>
                            <span class="input-group-text"><a class="link-opacity-75" href="#" onclick="si_conf_show_hide()">Not found ?</a></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row d-none" id="si_conf_stack1">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="platNomor" class="form-label">Other Vehicle Registration Number</label>
                        <input type="text" class="form-control" id="platNomorOther">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive" id="si_conf_resume_tbl_div">
                        <table id="si_conf_resume_tbl" class="table table-sm table-hover table-bordered caption-top">
                            <caption>Detail Items</caption>
                            <thead class="table-light text-center">
                                <tr>
                                    <th>Item Code</th>
                                    <th>Description</th>
                                    <th>Total Qty</th>
                                    <th>Total Box</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr class="table-light">
                                    <td colspan=2><strong>Grand Total</strong></td>
                                    <td class="text-end" id="si_conf_resume_tbl_total_qty"></td>
                                    <td class="text-center" id="si_conf_resume_tbl_total_box"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="si_conf_btn_confirm" onclick="si_conf_btn_confirm_on_click(this)">Save</button>
      </div>
    </div>
  </div>
</div>
<script>
    var si_conf_DTABLE_psn;

    function si_conf_show_hide() {
        if(si_conf_stack1.classList.contains('d-none')) {
            si_conf_stack1.classList.remove('d-none')
            platNomorOther.focus()
        } else {
            si_conf_stack1.classList.add('d-none')
        }
    }

    function si_conf_btn_confirm_on_click(pThis){

        let otherPlatNo = platNomorOther.value.trim()

        if(platNomor.value === '-' && otherPlatNo.length == 0) {
            alertify.warning('Vehicle Registration Number is required')
            platNomor.focus()
            return
        }

        let finalPlatNo = platNomor.value === '-' ? otherPlatNo : platNomor.value

        const doc = inputTXID.value
        if(confirm("Are you sure ?")) {
            pThis.disabled = true
            $.ajax({
                type: "PUT",
                url: `<?=$_ENV['APP_INTERNAL_API']?>delivery/vehicle/${btoa(doc)}`,
                data: {DLVH_TRANS : finalPlatNo, user_id: uidnya },
                dataType: "json",
                success: function (response) {
                    alertify.success(response.message);
                    si_conf_btn_sync_e_click();
                }, error: function(xhr, xopt, xthrow) {
                    pThis.disabled = false
                    alertify.error(xhr.responseJSON.message);
                }
            });
        }
    }
    function si_conf_on_GridActionButton_Click(event) {
        inputTXID.value = event.data.DLV_ID
        $('#modalPlatNomor').modal('show')
        platNomorOther.value = ''

        if(!si_conf_stack1.classList.contains('d-none')) {
            si_conf_stack1.classList.add('d-none')
        }

        si_conf_get_resume(event.data.DLV_ID)
        si_conf_btn_confirm.disabled = false
    }

    function si_conf_btn_sync_e_click(){
        document.getElementById('si_conf_btn_sync').disabled=true;
        si_conf_DTABLE_psn =  $('#si_conf_tbl').DataTable({
            responsive: true,
            select: true,
            destroy: true,
            ajax: {
                url : '<?=base_url("DELV/getunconfirmed")?>',
                dataSrc: function(json){
                    document.getElementById('si_conf_btn_sync').disabled=false;
                    return json.data;
                }
            },
            columns:[
                { "data": 'DLV_ID'},
                { "data": 'CUSTDO'},
                { "data": 'DLV_BCDATE'},
                { "data": 'DLV_RMRK'},
                { "data": 'PLANT'},
                { "data": 'PLANT',
                    render : function(data, type, row){
                        return '<input type="button" class="btn btn-sm btn-success" value="Confirm">';
                    },
                    createdCell: function(cell, cellData, rowData, rowIndex, colIndex) {
                        $(cell).on("click", "input", rowData, si_conf_on_GridActionButton_Click);
                    }
                }
            ],
            columnDefs: [
                {
                    targets: [0,2,4],
                    className: 'text-center'
                }
            ]
        });
    }

    function si_conf_get_resume(doc) {
        let mtabel = document.getElementById("si_conf_resume_tbl");
        mtabel.getElementsByTagName("tbody")[0].innerHTML = '<tr><td class="text-center" colspan=4>Please wait</td></tr>'

        si_conf_resume_tbl_total_qty.innerText = '-'
        si_conf_resume_tbl_total_box.innerText = '-'
        $.ajax({
            type: "GET",
            url: `<?=$_ENV['APP_INTERNAL_API']?>delivery/regular/${btoa(doc)}`,
            dataType: "json",
            success: function (response) {
                let mydes = document.getElementById("si_conf_resume_tbl_div");
                let myfrag = document.createDocumentFragment();

                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("si_conf_resume_tbl");
                let ttlQtyContainer = myfrag.getElementById("si_conf_resume_tbl_total_qty");
                let ttlBoxContainer = myfrag.getElementById("si_conf_resume_tbl_total_box");
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                let newrow, newcell;
                tableku2.innerHTML = ''
                ttlQtyContainer.innerText = ''
                ttlBoxContainer.innerText = ''

                let totalQty = 0
                let totalBox = 0
                response.data.forEach((arrayItem) => {
                    totalQty += numeral(arrayItem['QTY']).value()
                    totalBox += numeral(arrayItem['COUNTQT']).value()

                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0);
                    newcell.innerText = arrayItem['ITMCD']

                    newcell = newrow.insertCell(-1);
                    newcell.innerText = arrayItem['ITMD1']

                    newcell = newrow.insertCell(-1);
                    newcell.classList.add('text-end')
                    newcell.innerText = numeral(arrayItem['QTY']).format(',')

                    newcell = newrow.insertCell(-1);
                    newcell.classList.add('text-center')
                    newcell.innerText = arrayItem['COUNTQT']
                })
                mydes.innerHTML='';
                mydes.appendChild(myfrag);

                ttlQtyContainer.innerText = numeral(totalQty).format(',')
                ttlBoxContainer.innerText = totalBox
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
                mtabel.getElementsByTagName("tbody")[0].innerHTML = `<tr><td class="text-center" colspan=4>${xthrow}</td></tr>`
                si_conf_resume_tbl_total_qty.innerText = ''
                si_conf_resume_tbl_total_box.innerText = ''
            }
        });
    }
</script>