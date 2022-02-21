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
                <div class="table-responsive" id="mst_dlvcode_divku">
                    <table id="mst_dlvcode_tbl" class="table table-striped table-bordered table-sm table-hover" style="font-size:75%">
                        <thead class="table-light">
                            <tr class="first">
                                <th  class="align-middle">Delivery Code</th>
                                <th  class="align-middle">CustCode</th>
                                <th  class="align-middle">Customer Name</th>
                                <th  class="align-middle">Address</th>
                                <th  class="align-middle">Tax</th>
                                <th  class="align-middle">TPB No.</th>
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
<script>
    $("#mst_dlvcode_divku").css('height', $(window).height()   
    -document.getElementById('mst_dlvcode_stack0').offsetHeight    
    -100);
    mst_dlvcode_initData()
    mst_dlvcode_selected_row = 1
    function mst_dlvcode_btn_save_e_click() {
        const tbl = document.getElementById('mst_dlvcode_tbl').getElementsByTagName('tbody')[0]
        const dlvCD = tbl.rows[mst_dlvcode_selected_row].cells[0].innerText
        const txcode = tbl.rows[mst_dlvcode_selected_row].cells[1].innerText
        const cusNM = tbl.rows[mst_dlvcode_selected_row].cells[2].innerText
        const addr = tbl.rows[mst_dlvcode_selected_row].cells[3].innerText
        const tax = tbl.rows[mst_dlvcode_selected_row].cells[4].innerText
        const tpbno = tbl.rows[mst_dlvcode_selected_row].cells[5].innerText
        if(confirm("Are you sure ?")) {
            $.ajax({
                type: "POST",
                url: "<?=base_url('MDEL/save')?>",
                data: {dlvCD: dlvCD, cusNM: cusNM, addr: addr, tax: tax, tpbno: tpbno, txcode: txcode },
                dataType: "json",
                success: function (response) {
                    if(response.status[0].cd===1) {
                        alertify.success(response.status[0].msg)
                    } else {
                        alertify.success(response.message[0].msg)
                    }
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow)                    
                }
            })
        }
    }

    function mst_dlvcode_tbl_tbody_tr_eC(e){
        mst_dlvcode_selected_row = e.srcElement.parentElement.rowIndex - 1         
    }
    function mst_dlvcode_initData() {
        document.getElementById('mst_dlvcode_lblinfo').innerHTML = 'Please wait...'
        $.ajax({
            type: "GET",
            url: "<?=base_url('MDEL/master')?>",
            dataType: "json",
            success: function (response) {
                const ttlrows = response.data.length
                let mydes = document.getElementById("mst_dlvcode_divku");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("mst_dlvcode_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);                
                let tabell = myfrag.getElementById("mst_dlvcode_tbl");
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                tableku2.innerHTML='';
                for (let i = 0; i<ttlrows; i++){ 
                    newrow = tableku2.insertRow(-1)
                    newrow.onclick = (event) => {mst_dlvcode_tbl_tbody_tr_eC(event)}
                    newcell = newrow.insertCell(0);
                    newcell.innerHTML = response.data[i].MDEL_DELCD
                    newcell = newrow.insertCell(1)                    
                    newcell.innerHTML = response.data[i].MDEL_TXCD
                    newcell = newrow.insertCell(2);
                    newcell.contentEditable = true
                    newcell.innerHTML = response.data[i].MDEL_ZNAMA
                    newcell = newrow.insertCell(3);
                    newcell.style.cssText = "white-space:nowrap"
                    newcell.contentEditable = true
                    newcell.innerHTML = response.data[i].MDEL_ADDRCUSTOMS
                    newcell = newrow.insertCell(4);
                    newcell.contentEditable = true
                    newcell.style.cssText = "white-space:nowrap"
                    newcell.innerHTML = response.data[i].MDEL_ZTAX
                    newcell = newrow.insertCell(5);
                    newcell.contentEditable = true
                    newcell.style.cssText = "white-space:nowrap"
                    newcell.innerHTML = response.data[i].MDEL_ZSKEP
                }
                mydes.innerHTML='';
                mydes.appendChild(myfrag);
                document.getElementById('mst_dlvcode_lblinfo').innerHTML = ''
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
                document.getElementById('mst_dlvcode_lblinfo').innerHTML = ''
            }
        })
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