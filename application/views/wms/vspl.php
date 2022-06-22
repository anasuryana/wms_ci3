<style type="text/css">
	.tagbox-remove{
		display: none;
	}
</style>
<div style="padding: 5px">
	<div class="container-fluid"> 
        <div class="row">   
            <div class="col-md-5 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >PSN No</span>                    
                    <input type="text" class="form-control" id="psn_txt_psn" required onfocusout="psn_e_psn()">                   
                </div>
            </div> 
            <div class="col-md-3 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Category</span>                    
                    <select class="form-select" id="psn_txt_cat" required>
                        <option value="-">-</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Line</span>                    
                    <select class="form-select" id="psn_txt_line" required>
                        <option value="-">-</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >Feeder</span>                    
                    <select class="form-select" id="psn_txt_fedr" required>
                        <option value="-">-</option>
                    </select>
                </div>
            </div>           
        </div>
        <div class="row">
            <div class="col-md-12 mb-1 " id="psn_txt_jobno" title="Job Number">
            
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >MCZ</span>
                    <select id="psn_sel_order" class="form-select" >
                        <option value="-">-</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Rack</span>                    
                    <input type="text" class="form-control" id="psn_txt_rackcd" required>
                </div>
            </div>
            <div class="col-md-3 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Item Code</span>                    
                    <input type="text" class="form-control" id="psn_txt_itmcd" required>
                </div>
            </div>
            <div class="col-md-2 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Qty</span>                    
                    <input type="text" class="form-control" id="psn_txt_itmqty" required>
                </div>
            </div>
            <div class="col-md-2 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Lot No</span>                    
                    <input type="text" class="form-control" id="psn_txt_itmlot" required readonly>
                </div>
            </div>           
        </div>
        <div class="row">
            <div class="col-md-4 mb-1" >
                Selected warehouse : <strong><span id="spl_selwh" class="text-info"></span></strong>
            </div>
            <div class="col-md-4 mb-1 text-center">
                <div class="btn-group btn-group-sm">
                    <button title="Cancel per PSN" type="button" class="btn btn-warning" id="psn_btn_cancel" onclick="psn_btn_cancel_eCK(this)">Cancel per PSN</button>
                </div>
            </div>
            <div class="col-md-4 mb-1 text-end">
                <div class="btn-group btn-group-sm">
                    <button title="New Entry" type="button" class="btn btn-outline-primary" id="psn_btn_new"><i class="fas fa-file"></i></button>
                    <button title="Synchronize" type="button" class="btn btn-outline-success" id="psn_btn_msync"><i class="fas fa-sync"></i></button>
                    <button title="Save" type="button" class="btn btn-outline-primary" id="psn_btn_save"><i class="fas fa-save"></i></button>                    
                    <button title="Print" type="button" class="btn btn-outline-secondary" id="psn_btn_print"><i class="fas fa-print"></i></button>
                    <button title="Export to Spreadsheet" type="button" class="btn btn-success" id="psn_btn_export"><i class="fas fa-file-excel"></i></button>
                    <button title="Document Info" type="button" class="btn btn-info" id="psn_btn_info" onclick="psn_btn_info_eC()"><i class="fas fa-info"></i></button>                    
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1" id="psn_div_notif">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="spl_divku">
                    <table id="spl_tbl" class="table table-sm table-striped table-hover table-bordered" style="width:100%;font-size:80%">
                        <thead class="table-light">
                            <tr>
                                <th>Machine No</th>
                                <th>No. Rak</th>
                                <th>Part Code</th>
                                <th>Part Name</th>
                                <th class="text-center">Qty Use</th>
                                <th>M/S</th>
                                <th class="text-end">Req. Qty</th>
                                <th class="text-end">Total Issue</th>
                                <th class="text-end">Total Saved Issue</th>
                                <th>Detail Issue</th>
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
<div class="modal fade" id="SPL_DETISSU">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Detail Issue</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >No. Urut</span>                        
                        <input type="text" class="form-control" id="spl_txtsel_urut" readonly>
                    </div>
                </div>
                <div class="col">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Part Code</span>                        
                        <input type="text" class="form-control" id="spl_txtsel_Item" readonly>
                    </div>
                </div>
            </div>
                        
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="spl_divkudet">
                        <table id="spl_tbldetissu" class="table table-hover table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>                                    
                                    <th>QTY</th>
                                    <th>Lot No</th>
                                    <th>Last Update</th>
                                    <th>Status</th>
                                    <th>.</th>
                                    <th><i class="fas fa-trash"></i></th>
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
<div class="modal" id="SPL_DETISSU_UNFIXED" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Unfixed Data</h4>            
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col mb-1">
                    <div class="btn-group btn-group-sm">
                        <button title="Fix the data below" type="button" class="btn btn-warning" id="psn_btn_fix" onclick="psn_btn_fix_eCK(this)">Try to fix</button>
                        <button type="button" class="btn btn-primary" id="psn_btn_close" onclick="psn_btn_close_eCK(this)">Close</button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="spl_tblunfixed_div">
                        <table id="spl_tblunfixed" class="table table-hover table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Item Code</th>
                                    <th>QTY</th>
                                    <th>Lot No</th>
                                    <th>Last Update</th>
                                    <th>.</th>
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
<div class="modal fade" id="SPL_MODPRINT">
    <div class="modal-dialog">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Document Type</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col mb-1">
                    <ul class="list-group text-center">
                        <li class="list-group-item"> 
                            <input class="form-check-input" name="spl_doctype" type="radio" value="KIT" id="spl_rdKIT">
                            <label class="form-check-label" for="spl_rdKIT">Picking</label>          
                        </li>
                        <li class="list-group-item"> 
                            <input class="form-check-input" name="spl_doctype" type="radio" value="KITALL" id="spl_rdKITALL" checked>
                            <label class="form-check-label" for="spl_rdKITALL">Picking All</label>
                        </li>
                        <li class="list-group-item"> 
                            <input class="form-check-input" name="spl_doctype" type="radio" value="RES" id="spl_rdRES">
                            <label class="form-check-label" for="spl_rdRES">Result</label>
                        </li>                          
                    </ul>                    
                </div>
            </div>
            <div class="row">
                <div class="col mb-1 text-center">
                    <button class="btn btn-sm btn-primary" title="Print" id="spl_btnprintseldocs"><i class="fas fa-print"></i></button>
                </div>
            </div>           
        </div>             
      </div>
    </div>

</div>
<div class="modal fade" id="SPL_PROGRESS">
    <div class="modal-dialog">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title"><i class="fas fa-info text-info"></i></h4>
            
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">  
            <div class="row">
                <div class="col-md-12 mb-1 text-center">
                    Please wait ...
                </div>                
            </div>            
            <div class="row">
                <div class="col-md-12 mb-1 text-center">
                    <i class="fas fa-spinner fa-spin fa-7x text-info"></i>
                </div>                
            </div>
        </div>             
      </div>
    </div>
</div>
<div class="modal fade" id="SPL_EXPORTISSU">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Export Part Request Data</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col-md-4 mb-1 text-center">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Business Group</span>                                        				
                        <select class="form-select" id="psn_businessgroup" required>
                        <?=$lbg?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 mb-1">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text" >From</span>
                        <input type="text" class="form-control" id="spl_txt_dt" readonly>
                    </div>
                </div>
                <div class="col-md-4 mb-1">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text" >To</span>
                        <input type="text" class="form-control" id="spl_txt_dt2" readonly>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-1 text-center">       
                    <div class="btn-group btn-group-sm">                    
                        <button title="Search data" type="button" class="btn btn-outline-success" id="psn_btnexp_getdata" onclick="psn_btnexp_getdata_eClick()"><i class="fas fa-search"></i></button>                        
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="spl_divexpissu">
                        <table id="spl_tblexpissu" class="table table-hover table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Part Request No.</th>
                                    <th>Item Code</th>
                                    <th class="text-end">Transfer QTY</th>
                                    <th>Reff. Document</th>
                                    <th>Remark</th>
                                    <th>Line</th>
                                    <th>Lot No.</th>
                                    <th>Model</th>
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
<div class="modal fade" id="FOOTER_MODWHKITTING">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Set Warehouse</h4>
            <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col mb-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Warehouse</span>                        
                        <select id="FOOTER_MODWH_selwhkitting" class="form-select">
                           <option value="ARWH1">Warehouse Material For IEI</option>
                           <option value="ARWH2">Warehouse Material For Other</option>
                           <option value="NRWH2">Warehouse Material Non Asset</option>
                        </select>                  
                    </div>
                </div>
            </div>           
            <div class="row">
                <div class="col mb-1">
                    <button type="button" class="btn btn-sm btn-primary" id="FOOTER_MODWH_btnsavekitting"><i class="fas fa-save"></i></button>
                </div>
            </div>           
        </div>                   
      </div>
    </div>
</div>
<div class="modal fade" id="SPL_DOCINFO">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Document Info</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >Created By</span>
                        <input type="text" class="form-control" id="spl_txt_createdBy" readonly>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Approved By</span>
                        <input type="text" class="form-control" id="spl_txt_approvedBy" readonly>
                    </div>
                </div>
            </div>                                   
        </div>             
      </div>
    </div>
</div>
<div id='spl_contextmenu'></div>
<input type="hidden" id="spl_hid_idx">
<script>
    var spl_rowsObj = {}
    var spl_contextMenu = jSuites.contextmenu(document.getElementById('spl_contextmenu'), {
        items:[
            {
                title:'<span class="fas fa-trash text-warning"></span> Delete',                
                onclick:function() { 
                    if(spl_rowsObj.itemcd.length>0){
                        if(!confirm("Are you sure ?")){
                            return
                        }
                        
                        const txtdoc = document.getElementById('psn_txt_psn')
                        const txtcat = document.getElementById('psn_txt_cat')
                        const txtline = document.getElementById('psn_txt_line')
                        const txtfedr = document.getElementById('psn_txt_fedr')
                        if(txtdoc.value.trim().length<8){
                            alertify.warning('PSN No could not be blank')
                            txtdoc.focus()
                            return
                        }
                        if(txtcat.value=='-'){
                            alertify.warning("Category could not blank")
                            txtcat.focus()
                            return
                        }
                        if(txtline.value=='-'){
                            alertify.warning("Line could not blank")
                            txtline.focus()
                            return
                        }
                        if(txtfedr.value=='-'){
                            alertify.warning("Feeder could not blank")
                            txtfedr.focus()
                            return
                        }
                        $.ajax({
                            type: "POST",
                            url: "<?=base_url('SPL/remove')?>",
                            data: {spl: txtdoc.value, category: txtcat.value, line: txtline.value
                                ,fr: txtfedr.value, itemcd: spl_rowsObj.itemcd, qty:spl_rowsObj.reqqt
                                ,tbl: spl_rowsObj.tbl },
                            dataType: "json",
                            success: function (response) {
                                if(response.status[0].cd==1){
                                    document.getElementById('spl_tbl').rows[spl_rowsObj.ridx].remove()
                                    alertify.success(response.status[0].msg)
                                } else {
                                    alertify.message(response.status[0].msg)
                                }
                            }, error: function(xhr, xopt, xthrow){
                                alertify.error(xthrow)                                
                            }
                        })
                    } else {
                        document.getElementById('spl_tbl').rows[spl_rowsObj.ridx].remove()
                    }
                },
                tooltip: 'Delete selected item',
            }       
        ],
        onclick:function() {
            spl_contextMenu.close(false);
        }
    })

    function psn_btn_fix_eCK(p){
        const tbl = document.getElementById('spl_tblunfixed').getElementsByTagName('tbody')[0]
        const tbl_rows = tbl.getElementsByTagName('tr').length
        let idrows = []
        for(let i=0; i<tbl_rows; i++) {
            let idrow = tbl.rows[i].cells[0].innerHTML            
            idrows.push(idrow)
        }
        if(idrows.length>0) {
            p.disabled = true
            $.ajax({
                type: "POST",
                url: "<?=base_url('SPL/cancel_kitting_per_document')?>",
                data: {inidscan: idrows},
                dataType: "JSON",
                success: function (response) {
                    p.disabled = false
                    const ttlrows = response.status.length
                    for(let i=0; i<tbl_rows; i++) {
                        let idrow = tbl.rows[i].cells[0].innerHTML 
                        for(let x=0; x<ttlrows; x++) {
                            let responseId = response.status[x].reff
                            if(idrow==responseId){
                                tbl.rows[i].cells[5].innerHTML = response.status[x].msg
                                break
                            }
                        }
                    }
                }, error: function(xhr, xopt, xthrow){
                    p.disabled = false
                    alertify.error(xthrow)
                }
            })            
        }
    }  

    function psn_btn_cancel_eCK(p) {
        const txtPSN = document.getElementById('psn_txt_psn')
        if(txtPSN.value.trim().length<7) {
            txtPSN.focus()
            alertify.warning('PSN is required')
            return
        }
        if(confirm("Are you sure ?")) {
            if(confirm("Are you sure want to CANCEL the document ? ")) {
                p.disabled = true
                $.ajax({
                    type: "POST",
                    url: "<?=base_url('SPL/cancel_kitting_per_psn')?>",
                    data: {doc : txtPSN.value},
                    dataType: "json",
                    success: function (response) {
                        p.disabled = false
                        alertify.message(response.status[0].msg)
                    }, error: function(xhr, xopt, xthrow){
                        p.disabled = false
                        alertify.error(xthrow)
                    }
                })
            }
        }
    }
    function psn_btn_info_eC(){
        const txpsn = document.getElementById('psn_txt_psn')
        if(txpsn.value.trim().length==0){
            txpsn.focus()
            alertify.message(`PSN Number is required`)
            return
        }
        $.ajax({
            type: "GET",
            url: "<?=base_url('SPL/info')?>",
            data: {psnno: txpsn.value},
            dataType: "JSON",
            success: function (response) {
                $('#SPL_DOCINFO').modal('show')
                let ttlrows = response.createdBy.length
                let strTemp = ''
                for(let i=0; i<ttlrows; i++){
                    strTemp += `${response.createdBy[i].MSTEMP_FNM} ${response.createdBy[i].MSTEMP_LNM} (${response.createdBy[i].MSTEMP_ID}),`
                }
                strTemp = strTemp.substr(0,strTemp.length-1)
                document.getElementById('spl_txt_createdBy').value = strTemp

                ttlrows = response.approvedBy.length
                strTemp = ''
                for(let i=0; i<ttlrows; i++){
                    strTemp += `${response.approvedBy[i].MSTEMP_FNM} ${response.approvedBy[i].MSTEMP_LNM} (${response.approvedBy[i].MSTEMP_ID}),`
                }
                strTemp = strTemp.substr(0,strTemp.length-1)
                document.getElementById('spl_txt_approvedBy').value = strTemp
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    }
    $("#FOOTER_MODWH_btnsavekitting").click(function (e) { 
        e.preventDefault();
        let mwh = document.getElementById("FOOTER_MODWH_selwhkitting").value;
        Cookies.set('CKPSI_WH', mwh, {expires:365});
        $("#FOOTER_MODWHKITTING").modal('hide'); 
        alertify.message('Saved');
        document.getElementById('spl_selwh').innerText = mwh;
    });
    var mymodal = new bootstrap.Modal(document.getElementById("FOOTER_MODWHKITTING"), {backdrop: 'static', keyboard: false});
    mymodal.show();      
    
    $("#psn_btn_new").click(function (e) { 
        e.preventDefault();
        document.getElementById("psn_div_notif").innerHTML='';
        document.getElementById("psn_txt_psn").value='';
        document.getElementById("psn_txt_cat").innerHTML = "<option value='-'>-</option>";
        document.getElementById("psn_txt_cat").value='-';
        document.getElementById("psn_txt_line").innerHTML = "<option value='-'>-</option>";
        document.getElementById("psn_txt_line").value='-';
        document.getElementById('psn_txt_jobno').innerHTML = '';
        document.getElementById("psn_txt_itmcd").value='';
        document.getElementById("psn_txt_itmqty").value='';
        document.getElementById("psn_txt_itmlot").value='';
        document.getElementById("psn_txt_fedr").innerHTML = "<option value='-'>-</option>";
        document.getElementById("psn_txt_fedr").value='-';
        document.getElementById("psn_txt_psn").focus();
        $("#spl_tbl tbody").empty();
    });
    $("#spl_divku").css('height', $(window).height()*60/100);
    
    // $("#psn_txt_psn").keydown(function (e) {
    //     if(e.keyCode==9){           
    //         psn_e_psn();               
    //     }
    // });
    $("#psn_txt_psn").keypress(function (e) {         
        if(e.which==13){
            psn_e_psn();
        }
    });
    
    function  psn_e_psn(){
        let mval = document.getElementById("psn_txt_psn").value
        $("#psn_txt_cat").html("<option value='-'>-</option>");     
        if(mval.trim()!=''){            
            $("#psn_txt_line").html("<option value='-'>-</option>");
            $("#psn_txt_fedr").html("<option value='-'>-</option>");
            $.ajax({
                type: "get",
                url: "<?=base_url('SPL/checkPSN')?>",
                data: {inpsn: mval},
                dataType: "json",
                success: function (response) {
                    if(response.status[0].cd==0){
                        alertify.warning(response.status[0].msg);                        
                    } else {                        
                        let ttlrows = response.data.length;
                        let tohtml = "<option value='-'>-</option>";
                        for(let i = 0; i<ttlrows;i++){
                            tohtml += '<option value="'+response.data[i].SPL_CAT+'">'+response.data[i].SPL_CAT+'</option>';
                        }
                        $("#psn_txt_cat").html(tohtml);
                        $("#psn_txt_cat").focus();
                    }
                    const ttlrows = response.data_unfixed.length                    
                    if(ttlrows>0) {
                        $("#SPL_DETISSU_UNFIXED").modal('show');
                        let mydes = document.getElementById("spl_tblunfixed_div");
                        let myfrag = document.createDocumentFragment();
                        let mtabel = document.getElementById("spl_tblunfixed");
                        let cln = mtabel.cloneNode(true);
                        myfrag.appendChild(cln);                
                        let tabell = myfrag.getElementById("spl_tblunfixed");                    
                        let tableku2 = tabell.getElementsByTagName("tbody")[0];
                        let newrow, newcell, newText;
                        tableku2.innerHTML='';
                        for (let i = 0; i<ttlrows; i++){
                            newrow = tableku2.insertRow(-1)
                            newcell = newrow.insertCell(0)
                            newcell.innerHTML = response.data_unfixed[i].SPLSCN_ID
                            newcell = newrow.insertCell(1)
                            newcell.innerHTML = response.data_unfixed[i].SPLSCN_ITMCD
                            newcell = newrow.insertCell(2)
                            newcell.classList.add('text-end')
                            newcell.innerHTML = numeral(response.data_unfixed[i].SPLSCN_QTY).format(',')
                            newcell = newrow.insertCell(3)
                            newcell.innerHTML = response.data_unfixed[i].SPLSCN_LOTNO
                            newcell = newrow.insertCell(4)
                            newcell.innerHTML = response.data_unfixed[i].SPLSCN_LUPDT                            
                            newcell = newrow.insertCell(5)
                        }
                        mydes.innerHTML='';
                        mydes.appendChild(myfrag);
                    }
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        } else {
            alertify.warning('should not be empty');
        }
    }
    $("#psn_txt_cat").change(function (e) {         
        let mpsn = $("#psn_txt_psn").val();
        let mval = $(this).val();
        if(mval.trim()!='-' && mpsn.trim()!=''){
            $("#psn_txt_line").html("<option value='-'>-</option>");
            $("#psn_txt_fedr").html("<option value='-'>-</option>");
            $.ajax({
                type: "get",
                url: "<?=base_url('SPL/checkPSNCAT')?>",
                data: {inpsn: mpsn, incat: mval},
                dataType: "json",
                success: function (response) {
                    if(response.status[0].cd=='0'){
                        alertify.warning(response.status[0].msg);
                    } else {
                        let ttlrows = response.data.length;
                        let tohtml = "<option value='-'>-</option>";
                        for(let i = 0; i<ttlrows;i++){
                            tohtml += '<option value="'+response.data[i].SPL_LINE+'">'+response.data[i].SPL_LINE+'</option>';
                        }
                        $("#psn_txt_line").html(tohtml);                        
                        $("#psn_txt_line").focus();
                    }
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        } else {
            alertify.warning('should not be empty');
        }        
    });
    $("#psn_txt_line").change(function (e) {         
        let mpsn = $("#psn_txt_psn").val();
        let mcat = $("#psn_txt_cat").val();
        let mval = $(this).val();
        if(mval.trim()!='' && mpsn.trim()!='' && mcat.trim()!='-'){
            $("#psn_txt_fedr").html("<option value='-'>-</option>");
            $.ajax({
                type: "get",
                url: "<?=base_url('SPL/checkPSNCATLINE')?>",
                data: {inpsn: mpsn, incat: mcat, inline: mval},
                dataType: "json",
                success: function (response) {
                    if(response.status[0].cd=='0'){
                        alertify.warning(response.status[0].msg);
                    } else {                        
                        let ttlrows = response.data.length;
                        let tohtml = "<option value='-'>-</option>";
                        for(let i = 0; i<ttlrows;i++){
                            tohtml += `<option value="${response.data[i].SPL_FEDR}">${response.data[i].SPL_FEDR}</option>`;
                        }
                        $("#psn_txt_fedr").html(tohtml);
                        $("#psn_txt_fedr").focus();
                    }
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        } else {
            alertify.warning('should not be empty');
        }        
    });
    $("#psn_txt_fedr").change(function (e) {
        psn_getdata();
    });

    $("#psn_txt_rackcd").keypress(function (e) { 
        if(e.which==13){
            let mpsn = $("#psn_txt_psn").val();
            let mcat = $("#psn_txt_cat").val();
            let mline = $("#psn_txt_line").val();
            let mfedr = $("#psn_txt_fedr").val();
            let mrack = $(this).val();
            $.ajax({
                type: "get",
                url: "<?=base_url('SPL/checkPSN_rack')?>",
                data: {inpsn: mpsn, incat: mcat, inline:mline, infr: mfedr, inrack: mrack},
                dataType: "json",
                success: function (response) {
                    if(response.data[0].cd==0){
                        alertify.warning(response.data[0].msg); document.getElementById('psn_txt_rackcd').value='';
                    } else {
                        document.getElementById('psn_txt_itmcd').focus();
                    }
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    });
    $("#psn_txt_itmcd").keypress(function (e) { 
        if(e.which==13){
            let mpsn = $("#psn_txt_psn").val();
            let mcat = $("#psn_txt_cat").val();
            let mline = $("#psn_txt_line").val();
            let mfedr = $("#psn_txt_fedr").val();   
            let morder = $('#psn_sel_order').val();
            let mrack = document.getElementById('psn_txt_rackcd').value;
            let mval = $(this).val();
            
            if(mpsn.trim()==''){
                document.getElementById('psn_txt_psn').focus();
                alertify.message('Please fill PSN No');
                return;
            }
            // if(mcat.trim()==''){
            //     document.getElementById('psn_txt_cat').focus();
            //     alertify.message('Please fill Category');
            //     return;
            // }
            if(mline.trim()==''){
                document.getElementById('psn_txt_line').focus();
                alertify.message('Please fill Line');
                return;
            }
            if(mfedr.trim()==''){
                document.getElementById('psn_txt_fedr').focus();
                alertify.message('Please fill Feeder');
                return;
            }
            if(mrack.trim()==''){
                document.getElementById('psn_txt_fedr').focus();
                alertify.warning('Please fill Rack code');
                return;
            }
            if(mval.trim()==''){                
                alertify.message('Please fill Item Code');
                return;
            }
            if(mval.substring(0,3)!='3N1'){
                // alertify.warning('Unknown Format C3 Label');                
            } else {
                mval = mval.substring(3, mval.length);
            }
            
            $(this).val(mval);
            $.ajax({
                type: "get",
                url: "<?=base_url('SPL/checkPSN_itm')?>",
                data: {inpsn: mpsn, incat: mcat, inline: mline, infr: mfedr, incode : mval, inrack: mrack},
                dataType: "json",
                success: function (response) {
                    if(response.data[0].cd==0){
                        alertify.warning(response.data[0].msg); document.getElementById('psn_txt_itmcd').value='';
                    } else {
                        document.getElementById('psn_txt_itmqty').focus();
                    }
                }, error:function(xhr,xopt,xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    });
    $("#psn_txt_itmqty").keypress(function (e) { 
        if(e.which==13){
            let mpsn = $("#psn_txt_psn").val();
            let mcat = $("#psn_txt_cat").val();
            let mline = $("#psn_txt_line").val();
            let mfedr = $("#psn_txt_fedr").val();
            let mitemcd = $("#psn_txt_itmcd").val();    
            let morder = $('#psn_sel_order').val();
            let mval = $(this).val();
            if(mpsn.trim()==''){
                document.getElementById('psn_txt_psn').focus();
                alertify.message('Please fill PSN No');
                return;
            }
            // if(mcat.trim()==''){
            //     document.getElementById('psn_txt_cat').focus();
            //     alertify.message('Please fill Category');
            //     return;
            // }
            if(mline.trim()==''){
                document.getElementById('psn_txt_line').focus();
                alertify.message('Please fill Line');
                return;
            }
            if(mfedr.trim()==''){
                document.getElementById('psn_txt_fedr').focus();
                alertify.message('Please fill Feeder');
                return;
            }
            if(mitemcd.trim()==''){
                document.getElementById('psn_txt_itmcd').focus();
                alertify.message('Please fill Item Code');
                return;
            }
            if(mval.trim()==''){
                alertify.message('Please fill Qty');
                return;
            } else {
                let mthis_ar    = mval.split(' ');
                let mqty        = 0;
                let mlot        ='';
                if(mval.substring(0,3)=='3N2'){                    
                    if(!isNaN(mthis_ar[1])){
                        mqty = Number(mthis_ar[1]);
                    } else {
                        alertify.warning('qty value must be numerical !');
                        return;
                    }
                    $(this).val(mthis_ar[1]) 
                    for(var i=2;i<mthis_ar.length;i++){
                        mlot += mthis_ar[i] + ' ';
                    }                    
                                        
                } else {
                    if(!isNaN(mthis_ar[0])){
                        mqty = Number(mthis_ar[0]);
                    } else {
                        alertify.warning('qty value must be numerical !');
                        return;
                    }
                    $(this).val(mthis_ar[0]) 
                    for(var i=1;i<mthis_ar.length;i++){
                        mlot += mthis_ar[i] + ' ';
                    }
                }
                if(mlot.trim()==''){
                    alertify.warning('lot must be not empty');
                    return;
                }
                document.getElementById('psn_txt_itmlot').value=mlot;
                $.ajax({
                    type: "post",
                    url: "<?=base_url('SPL/scn_set')?>",
                    data: {inpsn: mpsn, incat: mcat, inline: mline, infr: mfedr, incode : mitemcd, inqty: mqty, inlot:mlot, inorder: morder},
                    dataType: "text",
                    success: function (response) {
                        switch(response){
                            case '111':
                                alertify.message('OK');
                                document.getElementById('psn_txt_itmqty').value=''; 
                                document.getElementById('psn_txt_itmlot').value=''; 
                                psn_getdata();
                                break;
                            case '110':
                                alertify.warning('Sorry');
                                break;
                            case '10':
                                alertify.warning('It is enough');
                                document.getElementById('psn_txt_itmcd').value='';
                                document.getElementById('psn_txt_itmcd').focus();
                                document.getElementById('psn_txt_itmqty').value=''; 
                                document.getElementById('psn_txt_itmlot').value=''; 
                                break;
                            case '11':
                                alertify.message('OK');
                                document.getElementById('psn_txt_itmqty').value=''; 
                                document.getElementById('psn_txt_itmlot').value=''; 
                                psn_getdata();
                                break;
                        }                            
                    }, error:function(xhr,xopt,xthrow){
                        alertify.error(xthrow);
                    }
                });
            }            
        }
    });

    function psn_getdata(){
        let mpsn = $("#psn_txt_psn").val();
        let mcat = $("#psn_txt_cat").val();
        let mline = $("#psn_txt_line").val();
        let mval = $("#psn_txt_fedr").val();
        if(mval.trim()!='' && mpsn.trim()!='' && mcat.trim()!='-' && mline.trim()!=''){
            $.ajax({
                type: "get",
                url: "<?=base_url('SPL/checkPSNCATLINEFEEDR')?>",
                data: {inpsn: mpsn, incat: mcat, inline: mline, infr: mval},
                dataType: "json",
                success: function (response) {
                    if(response.data[0].cd=='0'){
                        alertify.warning(response[0].msg);
                    } else {                                                                  
                        //per JOBS
                        let mjobs =[];
                        let strjob = '';
                        for(let x=0;x<response.datahead.length; x++){
                            if(mjobs.length==0){
                                mjobs.push(response.datahead[x].PPSN1_WONO.trim());
                                strjob += `<span class="badge rounded-pill bg-info">${response.datahead[x].PPSN1_WONO.trim()}@${numeral(response.datahead[x].PPSN1_SIMQT).format(',')}</span> `
                            } else {
                                if(!mjobs.includes(response.datahead[x].PPSN1_WONO.trim()) ){
                                    mjobs.push(response.datahead[x].PPSN1_WONO.trim());
                                    strjob += `<span class="badge rounded-pill bg-info">${response.datahead[x].PPSN1_WONO.trim()}@${numeral(response.datahead[x].PPSN1_SIMQT).format(',')}</span> `
                                }
                            }                            
                        }
                        
                        document.getElementById('psn_txt_jobno').innerHTML = strjob
                        document.getElementById("psn_txt_itmcd").focus();
                        //end per JOBS

                        //init SAVEDQTY
                        let ttlrowsaved = response.datasaved.length;                       

                        let ttlrows = response.datav.length;
                        let mydes = document.getElementById("spl_divku");
                        let myfrag = document.createDocumentFragment();
                        let mtabel = document.getElementById("spl_tbl");
                        let cln = mtabel.cloneNode(true);
                        myfrag.appendChild(cln);
                        let tabell = myfrag.getElementById("spl_tbl");
                        let tableku2 = tabell.getElementsByTagName("tbody")[0];
                        let newrow, newcell, newText;
                        tableku2.innerHTML='';
                        let tominqty = 0;
                        let tempqty = 0;
                        let todisqty = 0;                        
                        let strOrderno = '<option value="-">-</option>';
                        for (let i = 0; i<ttlrows; i++){
                            strOrderno += `<option values="${response.datav[i].SPL_ORDERNO}">${response.datav[i].SPL_ORDERNO}</option>`                            
                            tomin =Number(response.datav[i].TTLSCN);
                            for(let y=0;y<ttlrowsaved;y++){
                                if(response.datav[i].SPL_ITMCD.trim()==response.datasaved[y].SPLSCN_ITMCD.trim() &&  
                                 response.datav[i].SPL_ORDERNO.trim().toUpperCase()== response.datasaved[y].SPLSCN_ORDERNO.trim().toUpperCase()){
                                    todisqty = response.datasaved[y].TTLSAVED;break       
                                }
                            }
                            newrow = tableku2.insertRow(-1);
                             
                            newcell = newrow.insertCell(0);
                            newcell.style.cssText = "cursor:pointer"
                            newcell.onclick = function() {                                
                                document.getElementById('psn_sel_order').value = response.datav[i].SPL_ORDERNO
                                document.getElementById('psn_txt_rackcd').value = response.datav[i].SPL_RACKNO
                                document.getElementById('psn_txt_itmcd').value = response.datav[i].SPL_ITMCD
                                let currentidx = document.getElementById('spl_hid_idx')
                                if(currentidx.value!=''){
                                    tableku2.rows[currentidx.value].classList.remove('table-info')
                                }
                                tableku2.rows[i].classList.add('table-info')
                                currentidx.value = i
                            }
                            newcell.innerHTML = response.datav[i].SPL_ORDERNO

                            newcell = newrow.insertCell(1)
                            newcell.innerHTML = response.datav[i].SPL_RACKNO

                            newcell = newrow.insertCell(2);
                            if(numeral(response.datav[i].TTLSCN).value()==0){
                                newrow.addEventListener("contextmenu", function(e){
                                    spl_rowsObj.ridx = e.target.parentNode.rowIndex
                                    spl_rowsObj.tbl = e.target.parentNode.cells[0].innerText
                                    spl_rowsObj.itemcd = e.target.parentNode.cells[2].innerText
                                    spl_rowsObj.reqqt = numeral(e.target.parentNode.cells[6].innerText).value()
                                    spl_contextMenu.open(e)
                                    e.preventDefault()
                                })
                            }
                            newcell.innerHTML = response.datav[i].SPL_ITMCD

                            newcell = newrow.insertCell(3)
                            newcell.innerHTML = response.datav[i].MITM_SPTNO

                            newcell = newrow.insertCell(4)
                            newcell.innerHTML = Number(response.datav[i].SPL_QTYUSE)                           
                            newcell.style.cssText = 'text-align: center';

                            newcell = newrow.insertCell(5)
                            newcell.innerHTML = response.datav[i].SPL_MS
                            newcell.style.cssText = 'text-align: center';

                            newcell = newrow.insertCell(6);
                            newText = document.createTextNode(numeral(response.datav[i].TTLREQ).format(','));
                            newcell.appendChild(newText);
                            newcell.style.cssText = 'text-align: right';

                            newcell = newrow.insertCell(7);
                            newText = document.createTextNode(numeral(response.datav[i].TTLSCN).format(','));
                            newcell.appendChild(newText);
                            newcell.style.cssText = 'text-align: right';

                            newcell = newrow.insertCell(8);
                            newText = document.createTextNode(numeral(response.datav[i].TTLSCN).format(','));
                            newcell.appendChild(newText);
                            newcell.style.cssText = 'text-align: right';

                            newcell = newrow.insertCell(9);
                            newText = document.createElement('i');
                            newText.classList.add("fas");
                            newText.classList.add("fa-question-circle")    
                            newcell.appendChild(newText);
                            newcell.style.cssText = ''.concat('cursor: pointer;','text-align:center;');
                            todisqty=0;
                        }
                        $('#psn_sel_order').html(strOrderno);
                        
                        let mrows = tableku2.getElementsByTagName("tr");      
                        // let crow, ccell ,chandler;       
                        function cgetval(prow){
                            let tcell0 = prow.getElementsByTagName("td")[0];
                            let tcell2 = prow.getElementsByTagName("td")[2];
                            document.getElementById("spl_txtsel_urut").value=tcell0.innerText;
                            document.getElementById("spl_txtsel_Item").value=tcell2.innerText;
                            getdetailissue(tcell2.innerText, tcell0.innerText);
                            $('#SPL_DETISSU').modal('show');
                        }
                        for(let x=0;x<mrows.length;x++){
                            for(let h=0; h<tableku2.rows[x].cells.length; h++){
                                if(h==9){
                                    tableku2.rows[x].cells[h].onclick = function(){cgetval(tableku2.rows[x])};
                                }
                            }                            
                        }
                        mydes.innerHTML=''
                        mydes.appendChild(myfrag);
                    }
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        } else {
            alertify.warning('should not be empty');
        }
    }
    function getdetailissue(pitem,purut){
        let mpsn = $("#psn_txt_psn").val();
        let mcat = $("#psn_txt_cat").val();
        let mline = $("#psn_txt_line").val();
        let mfedr = $("#psn_txt_fedr").val();
        $.ajax({
            type: "get",
            url: "<?=base_url('SPL/getdetailissue')?>",
            data: {inpsn: mpsn, incat: mcat, inline: mline, infr: mfedr, incode: pitem, inurut: purut},
            dataType: "json",
            success: function (response) {
                let ttlrows = response.data.length;
                let mydes = document.getElementById("spl_divkudet");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("spl_tbldetissu");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("spl_tbldetissu");
                let tableku2 = tabell.getElementsByTagName("tbody")[0]
                let newrow, newcell, newText;
                tableku2.innerHTML='';
                let msts='';
                for (let i = 0; i<ttlrows; i++){
                    if(response.data[i].SPLSCN_SAVED){
                        if(response.data[i].SPLSCN_SAVED=='1'){
                            msts='saved';
                        } else {
                            msts='not saved yet';
                        }                        
                    } else {
                        msts='not saved yet';
                    }
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0);            
                    newText = document.createTextNode(response.data[i].SPLSCN_ID);            
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(1);
                    newText = document.createTextNode(numeral(response.data[i].SPLSCN_QTY).format('0,0'));
                    newcell.appendChild(newText);
                    newcell.style.cssText = 'text-align: right';
                    newcell = newrow.insertCell(2);
                    newText = document.createTextNode(response.data[i].SPLSCN_LOTNO);
                    newcell.appendChild(newText);
                    newcell.style.cssText = 'text-align: center';
                    newcell = newrow.insertCell(3);
                    newText = document.createTextNode(response.data[i].SPLSCN_LUPDT);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(4);
                    newText = document.createTextNode(msts);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(5);
                    newText = document.createElement('i');
                    newText.classList.add("fas");
                    if(msts.charAt(0)=="s"){
                        newText.classList.add("fa-check"); newText.classList.add("text-success");
                    } else {
                        newText.classList.add("fa-trash"); newText.classList.add("text-warning");
                    }                                                    
                    newcell.appendChild(newText);
                    newcell.style.cssText = ''.concat('cursor: pointer;','text-align:center;');
                    newcell = newrow.insertCell(6);
                    if(msts.charAt(0)=="s"){                        
                        newText = document.createElement('i');
                        newText.classList.add("fas");
                        newText.classList.add("fa-trash"); 
                        newText.classList.add("text-danger");
                    } else {
                        newText = document.createTextNode('');
                    }
                    newcell.appendChild(newText);
                }
                let mrows = tableku2.getElementsByTagName("tr");                  
                function cgetvaldet(prow){
                    let tcell0 = prow.getElementsByTagName("td")[0]            
                    let tcell4 = prow.getElementsByTagName("td")[4];
                    if(tcell4.innerText.charAt(0)=='n'){
                        let konf = confirm("Are you sure want to delete the transaction ["+ tcell0.innerText + "] ?");
                        if(konf){
                            spl_removeunsaved(tcell0.innerText); $('#SPL_DETISSU').modal('hide');
                        }
                    }
                }
                function spl_e_delete(prow){
                    if(wms_usergroupid=="PCC" || wms_usergroupid=="ADMIN"){
                        
                    } else {
                        alertify.warning("Sorry, we could not process, this function is only for specific user group");
                        return;
                    }
                    let tcell0 = prow.getElementsByTagName("td")[0];
                    let tcell4 = prow.getElementsByTagName("td")[4];
                    if(tcell4.innerText.charAt(0)!='n'){
                        let konf = confirm("Are you sure want to cancel kitting ["+ tcell0.innerText + "] ?");
                        if(konf){
                            $.ajax({
                                type: "get",
                                url: "<?=base_url('SPL/cancel_kitting')?>",
                                data: {inidscan: tcell0.innerText },
                                dataType: "json",
                                success: function (response) {
                                    if(response.status[0].cd!='0'){
                                        alertify.success(response.status[0].msg);
                                        $('#SPL_DETISSU').modal('hide');
                                    } else {
                                        alertify.message(response.status[0].msg);
                                    }
                                }, error: function(xhr, xopt, xthrow){
                                    alertify.error(xthrow);
                                }
                            })
                        }
                    }
                }
                for(let x=0;x<mrows.length;x++){
                    for(let h=0; h<tableku2.rows[x].cells.length; h++){
                        if(h==5){
                            tableku2.rows[x].cells[h].onclick = () => {cgetvaldet(tableku2.rows[x])};
                        } else if (h==6) {
                            tableku2.rows[x].cells[h].onclick = () => {spl_e_delete(tableku2.rows[x])};
                        }
                    }    
                }
                mydes.innerHTML='';
                mydes.appendChild(myfrag);
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    }
    function spl_removeunsaved(pid){
        $.ajax({
            type: "get",
            url: "<?=base_url('SPL/removeunsaved')?>",
            data: {inid: pid},
            dataType: "text",
            success: function (response) {
                alertify.message(response);psn_getdata()
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    }
    $("#psn_btn_save").click(function (e) {        
        let mpsn = $("#psn_txt_psn").val();
        let mcat = $("#psn_txt_cat").val();
        let mline = $("#psn_txt_line").val();
        let mfedr = $("#psn_txt_fedr").val();
        let konf = confirm('Are you sure ?');
        if(konf)
        {
            $.ajax({
                type: "post",
                url: "<?=base_url('SPL/save')?>",
                data: {inpsn: mpsn, incat:mcat, inline:mline, infr: mfedr},
                dataType: "text",
                success: function (response) {
                    alertify.message(response);
                    psn_getdata();
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    });

    $("#spl_txt_dt").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });
    $("#spl_txt_dt2").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });
    $("#spl_txt_dt").datepicker('update', new Date());
    $("#spl_txt_dt2").datepicker('update', new Date());

    $("#psn_btn_export").click(function (e) {
        $("#SPL_EXPORTISSU").modal('show');        
    });
    $("#psn_btn_print").click(function (e) { 
        $("#SPL_MODPRINT").modal('show');
        
    });
    $("#spl_btnprintseldocs").click(function (e) { 
        let mpsn = document.getElementById("psn_txt_psn").value;
        let mcat = document.getElementById("psn_txt_cat").value;
        let mline = document.getElementById("psn_txt_line").value;
        let mfedr = document.getElementById("psn_txt_fedr").value;
        if(mpsn.trim()===''){
            document.getElementById("psn_txt_psn").focus();return;
        }
       
        Cookies.set('CKPSI_DPSN', mpsn, {expires:365});
        Cookies.set('CKPSI_DCAT', mcat, {expires:365});
        Cookies.set('CKPSI_DLNE', mline, {expires:365});
        Cookies.set('CKPSI_DFDR', mfedr, {expires:365});
        let radioValue = $("input[name='spl_doctype']:checked").val()        
        if(radioValue=='KIT'){
            if(mcat.trim()=='-'){
                alertify.message('Please choose Category')
                document.getElementById("psn_txt_cat").focus();return;
            } 
            if(mline.trim() == '-'){
                alertify.message('Please choose line')
                document.getElementById("psn_txt_line").focus();return;
            } 
            if(mfedr.trim=='-'){
                alertify.message('please choose Feeder')
                document.getElementById("psn_txt_fedr").focus();return;
            }
            
            window.open("<?=base_url('SPL/printkit')?>",'_blank');
        } else if(radioValue=='KITALL') {
            window.open("<?=base_url('SPL/printkit_all')?>",'_blank');
        } else {
            if(mcat.trim()=='-'){
                alertify.message('Please choose Category')
                document.getElementById("psn_txt_cat").focus();return;
            } 
            if(mline.trim() == '-'){
                alertify.message('Please choose line')
                document.getElementById("psn_txt_line").focus();return;
            } 
            if(mfedr.trim=='-'){
                alertify.message('please choose Feeder')
                document.getElementById("psn_txt_fedr").focus();return;
            }
            window.open("<?=base_url('splresult_doc')?>",'_blank');
        }
    });
    $("#psn_btn_msync").click(function (e) { 
        let mpsn = document.getElementById("psn_txt_psn").value; 
        if(mpsn.trim()!='' ){
            $("#SPL_PROGRESS").modal('show');
        }
        
    });
    $("#SPL_PROGRESS").on('shown.bs.modal', function(){
        let mpsn = document.getElementById("psn_txt_psn").value;
        $.ajax({
            type: "get",
            url: "<?=base_url('SPL/sync_mega')?>",  
            data: {inpsn: mpsn},
            dataType: "json",
            success: function (response) {
                $("#SPL_PROGRESS").modal('hide');      
                $("#spl_tbl tbody").empty();                
                if(response.status[0].cd!='0'){
                    alertify.success(response.status[0].msg);
                    document.getElementById('psn_div_notif').innerHTML = '';
                    psn_e_psn();                    
                } else {
                    document.getElementById('psn_div_notif').innerHTML = '<div class="alert alert-warning" role="alert">'+
                    '<h4 class="alert-heading">Warning !</h4>'+
                    '<p class="mb-0">'+response.status[0].msg+'</p>'+
                    '<p class="mb-0">'+response.status[0].msgdetail+'</p>' +                    
                    '<hr><p class="mb-0">We could not continue the synchronization until this warning solved</p>' +
                    '</div>'         
                }
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
                $("#SPL_PROGRESS").modal('hide');
            }
        });
    });

    function psn_btnexp_getdata_eClick(){
        let dt1 = document.getElementById('spl_txt_dt').value;
        let dt2 = document.getElementById('spl_txt_dt2').value;
        let business = document.getElementById('psn_businessgroup').value;
        document.getElementById('psn_btnexp_getdata').innerHTML = "<i class='fas fa-spinner fa-spin'></i>";
        document.getElementById('psn_btnexp_getdata').disabled = true;
        $("#spl_tblexpissu tbody").empty();
        $.ajax({
            type: "get",
            url: "<?=base_url('SPL/getPRListperiod')?>",
            data:{dt1: dt1, dt2: dt2, business: business},
            dataType: "json",
            success: (response) => {
                document.getElementById('psn_btnexp_getdata').innerHTML = '<i class="fas fa-search"></i>';
                document.getElementById('psn_btnexp_getdata').disabled = false;
                let ttlrows = response.data.length;                
                let mydes = document.getElementById("spl_divexpissu");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("spl_tblexpissu");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("spl_tblexpissu");
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                let newrow, newcell, newText;
                let myitmttl = 0;
                tableku2.innerHTML='';            
                for (let i = 0; i<ttlrows; i++){                    
                    newrow = tableku2.insertRow(-1)
                    newcell = newrow.insertCell(0)                
                    newcell.innerHTML = response.data[i].SPLSCN_DATE
                    newcell = newrow.insertCell(1)                
                    newcell.innerHTML = response.data[i].SPLSCN_DOC
                    newcell = newrow.insertCell(2)                
                    newcell.innerHTML = response.data[i].SPLSCN_ITMCD
                    newcell = newrow.insertCell(3)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = numeral(response.data[i].SCNQTY).format(',')
                    newcell = newrow.insertCell(4)                    
                    newcell.innerHTML = response.data[i].SPL_REFDOCNO
                    newcell = newrow.insertCell(5)                    
                    newcell.innerHTML = response.data[i].RQSRMRK_DESC
                    newcell = newrow.insertCell(6)                    
                    newcell.innerHTML = response.data[i].SPL_LINE
                    newcell = newrow.insertCell(7)
                    newcell.innerHTML = response.data[i].SPLSCN_LOTNO
                    newcell = newrow.insertCell(8)
                    newcell.innerHTML = response.data[i].SPL_FMDL
                }
                mydes.innerHTML='';
                mydes.appendChild(myfrag);
                cmpr_selectElementContents(document.getElementById('spl_tblexpissu'))
                document.execCommand("copy");                
            }, error: (xhr, xopt, xthrow) => {
                alertify.error(xthrow);
                document.getElementById('psn_btnexp_getdata').innerHTML = '<i class="fas fa-search"></i>';
                document.getElementById('psn_btnexp_getdata').disabled = false;
            }
        });
    }

    function psn_btn_close_eCK() {        
        $("#SPL_DETISSU_UNFIXED").modal('hide')
    }
</script>