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
            <div class="col-md-12 mb-1 text-end">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary" type="button" id="mst_dlvcode_btn_plus" onclick="mst_dlvcode_btn_plus_e_click()" title="add new"><i class="fas fa-plus"></i></button>
                </div>
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
                <div class="col-md-4 mb-1">
                    <label for="mst_dlvcode_plus_txt_dlvcode_edit" class="form-label" >Delivery Code</label>
                    <input type="text" class="form-control form-control-sm" id="mst_dlvcode_plus_txt_dlvcode_edit" readonly disabled>
                </div>
                <div class="col-md-4 mb-1">
                    <label for="mst_dlvcode_plus_txt_custcode_edit" class="form-label" >Customer Code</label>
                    <input type="text" class="form-control form-control-sm" id="mst_dlvcode_plus_txt_custcode_edit">
                </div>
                <div class="col-md-4 mb-1">
                    <label for="mst_dlvcode_plus_txt_custcode_edit" class="form-label" >NIB</label>
                    <input type="text" class="form-control form-control-sm" id="mst_dlvcode_plus_txt_custnib_edit">
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
                <div class="col-md-4 mb-1">
                    <label for="mst_dlvcode_plus_txt_tax_edit" class="form-label" >Tax</label>
                    <input type="text" class="form-control form-control-sm" id="mst_dlvcode_plus_txt_tax_edit"  maxlength="150" required>
                </div>
                <div class="col-md-4 mb-1">
                    <label for="mst_dlvcode_plus_txt_nomor_skep_edit" class="form-label" >Nomor SKEP</label>
                    <input type="text" class="form-control form-control-sm" id="mst_dlvcode_plus_txt_nomor_skep_edit"  maxlength="150" required>
                </div>
                <div class="col-md-4 mb-1">
                    <label for="mst_dlvcode_plus_txt_tanggal_skep_edit" class="form-label" >Tanggal SKEP</label>
                    <input type="text" class="form-control form-control-sm" id="mst_dlvcode_plus_txt_tanggal_skep_edit"  readonly >
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-1">
                    <label for="mst_dlvcode_consignee_parent" class="form-label" >Parent Delivery Code</label>
                    <select id="mst_dlvcode_consignee_parent" class="form-select">
                        <option value="-">-</option>
                        <?php
                            foreach ($ldeliverycode as $r) {
                                ?>
                                    <option value="<?=trim($r->MDEL_DELCD)?>"><?=$r->MDEL_DELCD?></option>
                                <?php
                            }
                        ?>
                    </select>
                </div>
                <div class="col-md-6 mb-1">
                    <label for="mst_dlvcode_consignee_child" class="form-label" >Sub Delivery Code</label>
                    <div class="input-group input-group-sm mb-1">
                        <select id="mst_dlvcode_consignee_child" class="form-select">
                        </select>
                        <button class="btn btn-outline-primary" id="mst_dlvcode_btn_default_setter" onclick="mst_dlvcode_btn_default_setter_eclick(this)" title="For ASP/KD">Set as Default Sub</button>
                    </div>
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
        const cusNIB = mst_dlvcode_plus_txt_custnib_edit.value
        const parentConsignment = mst_dlvcode_consignee_parent.value
        if(confirm("Are you sure ?")) {
            p.disabled = true
            $.ajax({
                type: "POST",
                url: "<?=base_url('MDEL/save')?>",
                data: {dlvCD: dlvCD, cusNM: cusNM, addr: addr, tax: tax, tpbno: tpbno, txcode: txcode, 
                    tpbDate : tpbDate, cusNIB : cusNIB, parentConsignment : parentConsignment },
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
                            mst_dlvcode_plus_txt_custnib_edit.value = rowData['MDEL_NIB']
                            mst_dlvcode_plus_txt_custcode_edit.value = rowData['MDEL_TXCD']
                            mst_dlvcode_consignee_parent.value = rowData['PARENT_DELCD']
                            mst_dlvcode_init_child({parent_code: rowData['MDEL_DELCD']})
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

    function mst_dlvcode_init_child(data) {
        const dataInput = {
            parent_code : data.parent_code
        }
        let inputs = '<option value="-">Please wait</option>';
        mst_dlvcode_consignee_child.innerHTML = inputs
        $.ajax({
            type: "GET",
            url: "<?php echo $_ENV['APP_INTERNAL3_API'] ?>consignment/children",
            data: dataInput,
            dataType: "json",
            success: function (response) {
                inputs = '<option value="-">-</option>';
                response.data.forEach((arrayItem) => {
                    const activeSign = arrayItem['as_default'] ? '✔' : ''
                    inputs += `<option value="${arrayItem['MDEL_DELCD'].trim()}">${arrayItem['MDEL_DELCD'].trim()} 👉 ${arrayItem['MDEL_ADDRCUSTOMS'].trim()} ${activeSign}</option>`
                })
                mst_dlvcode_consignee_child.innerHTML = inputs
            }, error: function(xhr, xopt, xthrow){
                inputs = '<option value="-">-</option>';
                mst_dlvcode_consignee_child.innerHTML = inputs
                alertify.error(xthrow)
            }
        });
    }

    function mst_dlvcode_btn_default_setter_eclick(pThis) {
        const data = {
            parent_code : mst_dlvcode_plus_txt_dlvcode_edit.value,
            child_code : mst_dlvcode_consignee_child.value,
            user_id : uidnya,
        }
        $.ajax({
            type: "POST",
            url: "<?php echo $_ENV['APP_INTERNAL3_API'] ?>consignment/default-child",
            data: data,
            dataType: "json",
            success: function (response) {
                alertify.message(response.message)
                mst_dlvcode_init_child({parent_code : mst_dlvcode_plus_txt_dlvcode_edit.value})
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow)
            }
        });
    }
</script>