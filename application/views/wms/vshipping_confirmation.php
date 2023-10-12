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
  <div class="modal-dialog">
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
                        <select class="form-control" id="platNomor">
                            <option value="-">-</option>
                            <?=$ltrans?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="si_conf_btn_confirm_on_click(event)">Confirm</button>
      </div>
    </div>
  </div>
</div>
<script>
    var si_conf_DTABLE_psn;
    function si_conf_btn_confirm_on_click(){
        if(platNomor.value === '-'){
            alertify.warning('Vehicle Registration Number is required')
            platNomor.focus()
            return
        }
        if(confirm("Is this DO ("+inputTXID.value+") delivered ?")){
            $.ajax({
                type: "get",
                url: "<?=base_url('DELV/confirm_delivery')?>",
                data: {indo: event.data.DLV_ID, DLVH_TRANS : platNomor.value },
                dataType: "json",
                success: function (response) {
                    if(response.status[0].cd==1){
                        alertify.success(response.status[0].msg);
                        si_conf_btn_sync_e_click();
                    } else {
                        alertify.message(response.status[0].msg);
                    }
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    }
    function si_conf_on_GridActionButton_Click(event) {
        inputTXID.value = event.data.DLV_ID
        $('#modalPlatNomor').modal('show')

    }
    function si_conf_btn_sync_e_click(){
        document.getElementById('si_conf_btn_sync').disabled=true;
        si_conf_DTABLE_psn =  $('#si_conf_tbl').DataTable({
            select: true,
            fixedHeader: true,
            destroy: true,
            scrollX: true,
            scrollY: true,
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
                    targets: 4,
                    className: 'text-center'
                }
            ]
        });
    }
</script>