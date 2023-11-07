<style type="text/css">
	.tagbox-remove{
		display: none;
	}
    .txfg_cell:hover{
        font-weight: 900;
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
        <div class="row" id="mst_dlvcode_stack0">
            <div class="col-md-6 mb-1">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" type="button" id="mst_dlvcode_btn_save" onclick="mst_dlvcode_btn_save_e_click()"><i class="fas fa-save"></i></button>
                </div>
            </div>
            <div class="col-md-6 mb-1 text-end">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary" type="button" id="mst_dlvcode_btn_plus" onclick="mst_dlvcode_btn_plus_e_click()" title="add new"><i class="fas fa-plus"></i></button>
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <span id="mst_dlvcode_lblinfo" class="badge bg-info"></span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <table id="mst_dlvcode_tbl" class="table table-striped table-bordered table-sm table-hover" style="font-size:75%">
                    <thead class="table-light">
                        <tr class="first">
                            <th  class="align-middle"></th>
                            <th  class="align-middle">Delivery Code</th>
                            <th  class="align-middle">CustCode</th>
                            <th  class="align-middle">Customer Name</th>
                            <th  class="align-middle">Address</th>
                            <th  class="align-middle">Tax</th>
                            <th  class="align-middle">Nomor SKEP</th>
                            <th  class="align-middle">Tanggal SKEP</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="mst_dlvcode_modplus">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Add new data</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >Delivery Code</span>
                        <input type="text" class="form-control" id="mst_dlvcode_plus_txt_dlvcode"  maxlength="11" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >Customer Name</span>
                        <input type="text" class="form-control" id="mst_dlvcode_plus_txt_custname"  maxlength="100" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >Address</span>
                        <input type="text" class="form-control" id="mst_dlvcode_plus_txt_addr"  maxlength="150" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="mst_dlvcode_plus_btn_save" onclick="mst_dlvcode_plus_btn_save_eCK()">Save changes</button>
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="mst_dlvcode_modedit">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Edit</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6 mb-1">
                    <label for="mst_dlvcode_plus_txt_dlvcode_edit" class="form-label" >Delivery Code</label>
                    <input type="text" class="form-control form-control-sm" id="mst_dlvcode_plus_txt_dlvcode_edit" readonly disabled>
                </div>
                <div class="col-md-6 mb-1">
                    <label for="mst_dlvcode_plus_txt_custcode_edit" class="form-label" >Customer Code</label>
                    <input type="text" class="form-control form-control-sm" id="mst_dlvcode_plus_txt_custcode_edit">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-1">
                    <label for="mst_dlvcode_plus_txt_custname_edit" class="form-label" >Customer Name</label>
                    <input type="text" class="form-control form-control-sm" id="mst_dlvcode_plus_txt_custname_edit"  maxlength="100" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-1">
                    <label for="mst_dlvcode_plus_txt_addr_edit" class="form-label" >Address</label>
                    <input type="text" class="form-control form-control-sm" id="mst_dlvcode_plus_txt_addr_edit"  maxlength="150" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-1">
                    <label for="mst_dlvcode_plus_txt_tax_edit" class="form-label" >Tax</label>
                    <input type="text" class="form-control form-control-sm" id="mst_dlvcode_plus_txt_tax_edit"  maxlength="150" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-1">
                    <label for="mst_dlvcode_plus_txt_nomor_skep_edit" class="form-label" >Nomor SKEP</label>
                    <input type="text" class="form-control form-control-sm" id="mst_dlvcode_plus_txt_nomor_skep_edit"  maxlength="150" required>
                </div>
                <div class="col-md-6 mb-1">
                    <label for="mst_dlvcode_plus_txt_tanggal_skep_edit" class="form-label" >Tanggal SKEP</label>
                    <input type="text" class="form-control form-control-sm" id="mst_dlvcode_plus_txt_tanggal_skep_edit"  readonly >
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="mst_dlvcode_plus_btn_confirm_edit" onclick="mst_dlvcode_plus_btn_confirm_edit_on_click(this)">Save changes</button>
        </div>
      </div>
    </div>
</div>
<script>

    $("#mst_dlvcode_plus_txt_tanggal_skep_edit").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });
    mst_dlvcode_initData()
    
    function mst_dlvcode_plus_btn_confirm_edit_on_click(p) {
        const dlvCD = mst_dlvcode_plus_txt_dlvcode_edit.value
        const txcode = mst_dlvcode_plus_txt_custcode_edit.value
        const cusNM = mst_dlvcode_plus_txt_custname_edit.value
        const addr = mst_dlvcode_plus_txt_addr_edit.value
        const tax = mst_dlvcode_plus_txt_tax_edit.value
        const tpbno = mst_dlvcode_plus_txt_nomor_skep_edit.value
        const tpbDate = mst_dlvcode_plus_txt_tanggal_skep_edit.value
        if(confirm("Are you sure ?")) {
            p.disabled = true
            $.ajax({
                type: "POST",
                url: "<?=base_url('MDEL/save')?>",
                data: {dlvCD: dlvCD, cusNM: cusNM, addr: addr, tax: tax, tpbno: tpbno, txcode: txcode, tpbDate : tpbDate },
                dataType: "json",
                success: function (response) {
                    p.disabled = false
                    if(response.status[0].cd===1) {
                        alertify.success(response.status[0].msg)
                    } else {
                        alertify.success(response.message[0].msg)
                    }
                    $("#mst_dlvcode_modedit").modal('hide')
                    mst_dlvcode_initData()
                }, error: function(xhr, xopt, xthrow){
                    p.disabled = false
                    alertify.error(xthrow)
                }
            })
        }
    }

    var DTMain;

    function mst_dlvcode_initData() {
        DTMain = $('#mst_dlvcode_tbl').DataTable({
            destroy: true,
            searching: true,
            ajax: {
                url: '<?=base_url('MDEL/master')?>',
                type: 'get',
                dataSrc: function(json) {
                    return json.data;
                }
            },
            columns: [{
                    "data": 'MDEL_DELCD',
                    render: function(data, type, row, meta) {
                        return '<button class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-pencil"></i></button>';
                    },
                    createdCell: function(cell, cellData, rowData, rowIndex, colIndex) {
                        $(cell).on("click", "button", rowData, () => {
                            mst_dlvcode_plus_txt_dlvcode_edit.value = rowData['MDEL_DELCD']
                            mst_dlvcode_plus_txt_custname_edit.value = rowData['MDEL_ZNAMA']
                            mst_dlvcode_plus_txt_addr_edit.value = rowData['MDEL_ADDRCUSTOMS']
                            mst_dlvcode_plus_txt_tax_edit.value = rowData['MDEL_ZTAX']
                            mst_dlvcode_plus_txt_nomor_skep_edit.value = rowData['MDEL_ZSKEP']
                            mst_dlvcode_plus_txt_tanggal_skep_edit.value = rowData['MDEL_ZSKEP_DATE']
                            mst_dlvcode_plus_txt_custcode_edit.value = rowData['MDEL_TXCD']
                            $("#mst_dlvcode_modedit").modal('show')
                        });
                    }
                },
                {
                    "data": 'MDEL_DELCD'
                },
                {
                    "data": 'MDEL_TXCD'
                },
                {
                    "data": 'MDEL_ZNAMA'
                },
                {
                    "data": 'MDEL_ADDRCUSTOMS'
                },
                {
                    "data": 'MDEL_ZTAX'
                },
                {
                    "data": 'MDEL_ZSKEP'
                },
                {
                    "data": 'MDEL_ZSKEP_DATE'
                }
            ]
        });
    }
    function mst_dlvcode_btn_plus_e_click(){
        $("#mst_dlvcode_modplus").modal('show')
    }

    $("#mst_dlvcode_modplus").on('shown.bs.modal', function(){
        document.getElementById('mst_dlvcode_plus_txt_dlvcode').focus()
    })

    function mst_dlvcode_plus_btn_save_eCK(){
        const delcode = document.getElementById('mst_dlvcode_plus_txt_dlvcode')
        const delname = document.getElementById('mst_dlvcode_plus_txt_custname')
        const deladdr = document.getElementById('mst_dlvcode_plus_txt_addr')
        if(delcode.value.includes(" ")){
            alertify.message('it should not contain space')
            delcode.focus()
            return
        }
        if(delname.value.trim().length<=3){
            alertify.message('name is required')
            return
        }
        if(confirm("Are you sure ?")) {
            $.ajax({
                type: "POST",
                url: "<?=base_url('MDEL/set')?>",
                data: {delcode: delcode.value.trim(), delname: delname.value, deladdr: deladdr.value},
                dataType: "json",
                success: function (response) {
                    if(response.status[0].cd==='1'){
                        alertify.success(response.status[0].msg)
                        $("#mst_dlvcode_modplus").modal('hide')
                        mst_dlvcode_initData()
                    } else {
                        alertify.warning(response.status[0].msg)
                    }
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow)
                }
            })
        }
    }
</script>