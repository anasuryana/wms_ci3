<div style="padding: 5px" >
	<div class="container-fluid">
        <div class="row">
            <div class="col-md-2 mb-1">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary" id="mpuror_btn_new" onclick="mpuror_btn_new_eC()"><i class="fas fa-file"></i></button>
                    <button class="btn btn-outline-primary" id="mpuror_btn_save" onclick="mpuror_btn_save_eC()"><i class="fas fa-save"></i></button>
                    <button class="btn btn-outline-primary" id="mpuror_btn_print" onclick="mpuror_btn_print_eC()"><i class="fas fa-print"></i></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">PO No</label>
                    <input type="text" class="form-control" id="mpuror_txt_doc" readonly title="autonumber">
                    <button class="btn btn-primary" id="mpuror_btnmod" onclick="mpuror_btnmod_eC()"><i class="fas fa-search"></i></button>
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Remark</label>
                    <input type="text" class="form-control" id="mpuror_txt_remark">
                </div>
            </div>
            <div class="col-md-2 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">PPh</label>
                    <input type="text" class="form-control" id="mpuror_txt_pph">
                    <label class="input-group-text">%</label>
                </div>
            </div>
            <div class="col-md-2 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">VAT</label>
                    <input type="text" class="form-control" id="mpuror_txt_VAT">
                    <label class="input-group-text">%</label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">                                        
                    <span class="input-group-text">Vendor</span>                                        				
                    <input type="text" class="form-control" id="mpuror_vendname" required readonly>	                                        
                    <button class="btn btn-primary" id="mpuror_btnfindmodvend" onclick="mpuror_btnfindmodvend_eC()"><i class="fas fa-search"></i></button>                                        	
                </div>
            </div>
            <div class="col-md-2 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Currency</span>
                    <input type="text" readonly class="form-control" id="mpuror_curr">
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Requested by</span>
                    <input type="text" class="form-control" id="mpuror_reqstby">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Payment Term</label>
                    <input type="text" class="form-control" id="mpuror_cmb_payterm" list="mpuror_cmb_payterm_dl">
                    <datalist id="mpuror_cmb_payterm_dl">
                        <option value="EOM 10">
                        <option value="EOM 15">
                        <option value="EOM 30">
                        <option value="Cash">
                    </datalist>                    
                </div>
            </div>
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Delivery Req. Date</label>
                    <input type="text" class="form-control" id="mpuror_txt_dlvreqdate" readonly>
                </div>
            </div>
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text" title="issue date">Created Date</label>
                    <input type="text" class="form-control" id="mpuror_txt_issue" readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Shipment</label>
                    <input type="text" class="form-control" id="mpuror_txt_shipdoc" list="mpuror_txt_shipdocl">
                    <datalist id="mpuror_txt_shipdocl">
                        <option value="Hand Carry">
                        <option value="FOB">
                        <option value="Exwork">
                        <option value="DUD">
                    </datalist>
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Shipment Cost</label>
                    <input type="text" class="form-control" id="mpuror_txt_shpcost">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="mpuror_home-tab" data-bs-toggle="tab" data-bs-target="#mpuror_tabRM" type="button" role="tab" aria-controls="home" aria-selected="true">BC</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="mpuror_profile-tab" data-bs-toggle="tab" data-bs-target="#mpuror_tabFG" type="button" role="tab" aria-controls="profile" aria-selected="false">Non BC</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="mpuror_profile-tab" data-bs-toggle="tab" data-bs-target="#mpuror_tabSpecial" type="button" role="tab" aria-controls="profile" aria-selected="false">Special Discount</button>
                </li>
            </ul>
                <div class="tab-content" id="mpuror_myTabContent">
                    <div class="tab-pane fade show active" id="mpuror_tabRM" role="tabpanel" aria-labelledby="home-tab">
                        <div class="container-fluid">
                            <div class="row mt-1">
                                <div class="col-md-12 mb-1">
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-primary" id="mpuror_btnplus" onclick="mpuror_btnplus_eC()"><i class="fas fa-plus"></i></button>
                                        <button class="btn btn-warning" id="mpuror_btnmins" onclick="mpuror_minusrow()"><i class="fas fa-minus"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-1">
                                    <div class="table-responsive" id="mpuror_divku" >
                                        <table id="mpuror_tbl" class="table table-sm table-hover table-bordered caption-top" style="width:100%;font-size:0.9em">                                            
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="d-none">idLine</th>
                                                    <th>Item Code</th>
                                                    <th>Item Name</th>
                                                    <th class="text-end">Total QTY</th>
                                                    <th class="text-end">Unit Price</th>
                                                    <th class="text-end">Disc (%)</th>
                                                    <th>Department</th>
                                                    <th>Section</th>
                                                    <th>Subject</th>
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
                    <div class="tab-pane fade" id="mpuror_tabFG" role="tabpanel" aria-labelledby="contact-tab">
                        <div class="container-fluid">                            
                            <div class="row">
                                <div class="col-md-12 mb-1 table-responsive">
                                    <div id="mpuror_ss_nonitem"></div>
                                </div>
                            </div>
                        </div>
                    </div>                    
                    <div class="tab-pane fade" id="mpuror_tabSpecial" role="tabpanel" aria-labelledby="contact-tab">
                        <div class="container-fluid">
                            <div class="row mt-1">
                                <div class="col-md-12 mb-1">
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-primary" id="mpuror_btnplus_disc" onclick="mpuror_btnplus_disc_eCK()"><i class="fas fa-plus"></i></button>
                                        <button class="btn btn-warning" id="mpuror_btnmin_disc" onclick="mpuror_btnmin_disc_eCK()"><i class="fas fa-minus"></i></button>
                                    </div>
                                </div> 
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-1">
                                    <div class="table-responsive" id="mpuror_tbl_special_div" >
                                        <table id="mpuror_tbl_special" class="table table-sm table-striped table-hover table-bordered caption-top" style="width:100%;font-size:0.9em">                                            
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="d-none">idLine</th>
                                                    <th>Description</th>
                                                    <th class="text-end">Disc (%)</th>
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
<div class="modal fade" id="mpuror_SUPPLIER">
    <div class="modal-dialog">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Supplier List</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >Search</span>
                        <input type="text" class="form-control" id="mpuror_txtsearchSUP" onkeypress="mpuror_txtsearchSUP_eKP(event)" maxlength="15" required placeholder="...">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col text-end mb-1">
                    <span class="badge bg-info" id="mpuror_tblsup_lblinfo"></span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="mpuror_tblsup_div">
                        <table id="mpuror_tblsup" class="table table-sm table-striped table-bordered table-hover" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th>Code</th>
                                    <th>Name</th>
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
<div class="modal fade" id="mpuror_Department">
    <div class="modal-dialog">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Department List</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">                     
            <div class="row">
                <div class="col" onclick="mpuror_tbl_dept_div_eCK(event)">
                    <div class="table-responsive" id="mpuror_tbldept_div">
                        <table id="mpuror_tbldept" class="table table-sm table-striped table-bordered table-hover" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th>Code</th>
                                    <th>Name</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?=$deptl?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="mpuror_SavedDataMod">
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
                <div class="col mb-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Search</span>
                        <select id="mpuror_srchby" class="form-select" onchange="document.getElementById('mpuror_txtsearch').focus()">
                            <option value="0">Document Number</option>
                            <option value="1">Supplier</option>
                        </select>
                        <input type="text" class="form-control" id="mpuror_txtsearch" onkeypress="mpuror_txtsearch_eKP(event)" maxlength="44" required placeholder="...">                        
                    </div>
                </div>
            </div>            
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-text">
                        <input title="use period" type="checkbox" class="form-check-input" id="mpuror_ck_1" onclick="document.getElementById('mpuror_txtsearch').focus()">
                        </div>
                        <input type="text" class="form-control" id="mpuror_saved_date_1" readonly onchange="document.getElementById('mpuror_txtsearch').focus()">
                        <input type="text" class="form-control" id="mpuror_saved_date_2" readonly onchange="document.getElementById('mpuror_txtsearch').focus()">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col mb-1">
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-success" id="mpuror_btnexport" onclick="mpuror_btnexport_eCK()" title="Save as Xls file"><i class="fas fa-file-excel"></i></button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col text-end">
                    <span class="badge bg-info" id="mpuror_saved_lblinfo"></span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="mpuror_saved_div">
                        <table id="mpuror_saved_tbl" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer">
                            <thead class="table-light">
                                <tr>
                                    <th rowspan="2" class="align-middle">Document Number</th>
                                    <th rowspan="2" class="align-middle">Supplier</th>
                                    <th colspan="2" class="text-center">Date</th>                                 
                                </tr>
                                <tr>
                                    <th>Created</th>
                                    <th>Required</th>
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
<div class="modal fade" id="mpuror_INTERNALITEM">
    <div class="modal-dialog modal-xl">
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
                        <span class="input-group-text" >Search</span>
                        <select id="mpuror_NALITM_searchby" class="form-select" onchange="document.getElementById('mpuror_txtsearchNALITM').focus()">
                            <option value="ic">Internal Code</option>
                            <option value="cc">Customer Code</option>
                            <option value="in">Item Name</option>
                        </select>
                        <input type="text" class="form-control" id="mpuror_txtsearchNALITM" onkeypress="mpuror_txtsearchNALITM_eKP(event)" maxlength="40" required placeholder="...">
                    </div>
                </div>
            </div>  
                     
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="mpuror_tblNALITM_div">
                        <table id="mpuror_tblNALITM" class="table table-sm table-striped table-bordered table-hover" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th>Internal Code</th>
                                    <th>Customer Code</th>
                                    <th>Item Name</th>
                                    <th>Unit Measurement</th>
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
    mpuror_initadditional_data()
    function mpuror_initadditional_data(){
        const dl = document.getElementById('mpuror_cmb_payterm_dl')
        const dl_length = dl.options.length
        const dl2 = document.getElementById('mpuror_txt_shipdocl')
        const dl2_length = dl2.options.length
        let opt = document.createElement('option')                
        $.ajax({            
            url: "<?=base_url('PO/additional')?>",            
            dataType: "json",
            success: function (response) {
                const pay_length = response.payment_term.length
                const shp_length = response.shipment.length
                for(let r=0; r<pay_length; r++){
                    let isfound = false                    
                    for(let i=0;i<dl_length; i++){
                        if(response.payment_term[r].PO_PAYTERM==dl.options[i].value){
                            isfound = true;break
                        }                        
                    }
                    if(!isfound){
                        opt = document.createElement('option')
                        opt.value = response.payment_term[r].PO_PAYTERM
                        dl.appendChild(opt)
                    }
                }
                for(let r=0; r<shp_length; r++){
                    let isfound = false                    
                    for(let i=0;i<dl2_length; i++){
                        if(response.shipment[r].PO_SHPDLV==dl2.options[i].value){
                            isfound = true;break
                        }                        
                    }
                    if(!isfound){
                        opt = document.createElement('option')
                        opt.value = response.shipment[r].PO_SHPDLV
                        dl2.appendChild(opt)
                    }
                }
            }
        })
    }
    var mpurordata = [
       [''],
       [''],
       [''],
       [''],
       [''],
    ];    

var mpuror_sso_nonitem = jspreadsheet(document.getElementById('mpuror_ss_nonitem'), {
    data:mpurordata,
    columns: [
        {
            type: 'text',
            title:'line',
            width:'2',
            readOnly: true
        },
        {
            type: 'text',
            title:'Item Name',
            width:150,
            align: 'left'
        },        
        {
            type: 'text',
            title:'Unit Measure',
            width:120
        },        
        {
            type: 'numeric',
            title:'QTY',
            mask:'#,##.00',
            width:100,
            align: 'right'
        },
        {
            type: 'numeric',
            title:'Unit Price',
            mask:'#,##.00',
            width:90,
            align: 'right'
        },
        {
            type: 'numeric',
            title:'Discount (%)',
            mask:'#,##.00',
            width:80,
            align: 'right',
            decimal:'.'
        },
        {
            type: 'autocomplete',
            title:'Department',            
            width:100,
            source: [<?=$deptl_1?>]
        },
        {
            type: 'text',
            title:'Section',            
            width:100,            
        },
        {
            type: 'text',
            title:'Subject',            
            width:100,            
        },
        
     ],
    ondeleterow : function(instance,y1,xnumrow,xdom,xrowdata) {
        const docnum = document.getElementById('mpuror_txt_doc').value
        if(mpuror_selected_row.toString().length>0 && docnum.length>0){
            $.ajax({
                type: "post",
                url: "<?=base_url('PO/remove')?>",
                data: {lineId: mpuror_selected_row, docNum: docnum },
                dataType: "json",
                success: function (response) {
                    mpuror_get_detail(docnum)
                    if (response.status[0].cd==='1') {
                        alertify.success(response.status[0].msg)                    
                    } else {
                        alertify.message(response.status[0].msg)
                    }
                }, error:function(xhr,xopt,xthrow){
                    alertify.error(xthrow)
                }
            })
        }
    },
    onbeforedeleterow: function(instance,y1) {        
        let lineID = mpuror_sso_nonitem.getValueFromCoords(0,y1)        
        mpuror_selected_row = lineID
    }
});
    var mpuror_vencd = ''
    var mpuror_selected_row = ''
    var mpuror_selected_col = ''
    function mpuror_btn_print_eC() {
        let msi = document.getElementById('mpuror_txt_doc').value;
        if(msi.trim()==''){
            alertify.message('document is required');
            document.getElementById('mpuror_txt_doc').focus();
            return;
        }
        Cookies.set('PONUM', msi, {expires:365});    
        window.open("<?=base_url('printPO_doc')?>",'_blank');
    }    

    function mpuror_btnfindmodvend_eC() {
        $("#mpuror_SUPPLIER").modal('show')
    }

    $("#mpuror_SUPPLIER").on('shown.bs.modal', function(){
        document.getElementById('mpuror_txtsearchSUP').focus()
    })

    function mpuror_txtsearchSUP_eKP(e){
        if(e.key==='Enter'){
            const txt = document.getElementById('mpuror_txtsearchSUP')
            txt.readOnly = true
            document.getElementById('mpuror_tblsup').getElementsByTagName('tbody')[0].innerHTML = ''
            $.ajax({
                type: "GET",
                url: "<?=base_url('MSTSUP/search_union')?>",
                data: {searchKey: txt.value },
                dataType: "JSON",
                success: function (response) {
                    txt.readOnly = false
                    if(response.status[0].cd===1){
                        const ttlrows = response.data.length
                        let mydes = document.getElementById("mpuror_tblsup_div")
                        let myfrag = document.createDocumentFragment()
                        let mtabel = document.getElementById("mpuror_tblsup")
                        let cln = mtabel.cloneNode(true);
                        myfrag.appendChild(cln)
                        let tabell = myfrag.getElementById("mpuror_tblsup")
                        let tableku2 = tabell.getElementsByTagName("tbody")[0]
                        let newrow, newcell, newText
                        let myitmttl = 0;
                        tableku2.innerHTML=''
                        for (let i = 0; i<ttlrows; i++){                            
                            newrow = tableku2.insertRow(-1)
                            newcell = newrow.insertCell(0)
                            newcell.style.cssText = "cursor:pointer"
                            newcell.innerHTML = response.data[i].MSUP_SUPCD
                            newcell.onclick = () => {
                                $("#mpuror_SUPPLIER").modal('hide'); 
                                document.getElementById('mpuror_vendname').value = response.data[i].MSUP_SUPNM
                                document.getElementById('mpuror_curr').value = response.data[i].MSUP_SUPCR
                                mpuror_vencd = response.data[i].MSUP_SUPCD
                            }
                            newcell = newrow.insertCell(1)
                            newcell.innerHTML = response.data[i].MSUP_SUPNM 
                        }
                        mydes.innerHTML='';
                        mydes.appendChild(myfrag);
                    } else {
                        alertify.message(response.status[0].msg)
                    }
                },  error:function(xhr,ajaxOptions, throwError) {
                    txt.readOnly = false
                }
            })
        }
    }

    function mpuror_btn_new_eC() {
        if(!confirm("Are you sure want to create new document ?")) {
            return
        }
        document.getElementById('mpuror_txt_doc').value = ''
        document.getElementById('mpuror_txt_remark').value = ''
        document.getElementById('mpuror_txt_pph').value = ''
        document.getElementById('mpuror_txt_VAT').value = ''
        document.getElementById('mpuror_vendname').value = ''
        document.getElementById('mpuror_curr').value = ''
        document.getElementById('mpuror_reqstby').value = ''
        $("#mpuror_txt_dlvreqdate").datepicker('update', new Date())
        $("#mpuror_txt_issue").datepicker('update', new Date())
        document.getElementById('mpuror_txt_shipdoc').value = ''
        document.getElementById('mpuror_txt_shpcost').value = ''
        document.getElementById('mpuror_tbl').getElementsByTagName('tbody')[0].innerHTML = ''        
        document.getElementById('mpuror_tbl_special').getElementsByTagName('tbody')[0].innerHTML = ''
        mpuror_vencd = ''
        mpuror_sso_nonitem.setData([[],[],[],[],[]])
    }
    $("#mpuror_txt_dlvreqdate").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    })
    $("#mpuror_txt_issue").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    })
    $("#mpuror_saved_date_1").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    })
    $("#mpuror_saved_date_2").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    })
    $("#mpuror_txt_dlvreqdate").datepicker('update', new Date())
    $("#mpuror_txt_issue").datepicker('update', new Date())
    $("#mpuror_saved_date_1").datepicker('update', new Date())
    $("#mpuror_saved_date_2").datepicker('update', new Date())
    function mpuror_tbl_tbody_tr_eC(e){
        mpuror_selected_row = e.srcElement.parentElement.rowIndex - 1        
        mpuror_selected_col = e.srcElement.cellIndex
    }
    function mpuror_addrow(){
        let mytbody = document.getElementById('mpuror_tbl').getElementsByTagName('tbody')[0]
        let newrow , newcell        
        newrow = mytbody.insertRow(-1)
        newrow.onclick = (event) => {mpuror_tbl_tbody_tr_eC(event)}
        newrow.oncontextmenu = (e) => {
            mpuror_selected_row = e.srcElement.parentElement.rowIndex - 1            
            e.preventDefault()
        }
        
        newcell = newrow.insertCell(0)
        newcell.classList.add('d-none')
        newcell.innerHTML = ''

        newcell = newrow.insertCell(1)        
        newcell.classList.add('table-secondary')
        newcell.onclick = () => {
            $("#mpuror_INTERNALITEM").modal('show')
        }
        newcell.style.cssText = 'cursor:pointer'
        newcell.focus()

        newcell = newrow.insertCell(2)  
        newcell.classList.add('table-secondary')

        newcell = newrow.insertCell(3)
        newcell.contentEditable = true
        newcell.title = 'qty'
        newcell.classList.add('text-end')
        newcell.onkeypress = (e) => {
            if(e.key==='Enter'){
                e.target.nextElementSibling.focus()
                e.preventDefault()
            }
        }

        newcell = newrow.insertCell(4)
        newcell.contentEditable = true
        newcell.title = 'price'
        newcell.classList.add('text-end')        
        newcell.onkeypress = (e) => {
            if(e.key==='Enter'){
                e.target.nextElementSibling.focus()
                e.preventDefault()
            }
        }
        
        newcell = newrow.insertCell(5)
        newcell.title = 'discount'
        newcell.classList.add('text-end')
        newcell.contentEditable = true
        newcell.innerHTML = '0'
        newcell.onkeypress = (e) => {
            if(e.key==='Enter'){
                e.target.nextElementSibling.focus()
                e.preventDefault()
            }
        }

        newcell = newrow.insertCell(6)
        newcell.title = 'department'
        newcell.contentEditable = true
        newcell.onkeypress = (e) => {
            if(e.key==='Enter'){
                e.target.nextElementSibling.focus()
                e.preventDefault()
            }
        }


        newcell = newrow.insertCell(7)
        newcell.title = 'section'
        newcell.contentEditable = true
        newcell.onkeypress = (e) => {
            if(e.key==='Enter'){
                e.target.nextElementSibling.focus()
                e.preventDefault()
            }
        }

        newcell = newrow.insertCell(8)
        newcell.title = 'subject'
        newcell.contentEditable = true
        newcell.onkeypress = (e) => {
            if(e.key==='Enter'){                
                e.preventDefault()
            }
        }
    }
    function mpuror_btnplus_eC(){
        mpuror_addrow()
        let mytbody = document.getElementById('mpuror_tbl').getElementsByTagName('tbody')[0]
        mpuror_selected_row = mytbody.rows.length - 1
        mpuror_selected_col = 1
    }
    function mpuror_minusrow(){
        let mytable = document.getElementById('mpuror_tbl').getElementsByTagName('tbody')[0]
        const mtr = mytable.getElementsByTagName('tr')[mpuror_selected_row]
        const mylineid = mtr.getElementsByTagName('td')[0].innerText.trim()
        if(mylineid!==''){
            if(confirm("Are you sure ?")){
                const docnum = document.getElementById('mpuror_txt_doc').value
                $.ajax({
                    type: "post",
                    url: "<?=base_url('PO/remove')?>",
                    data: {lineId: mylineid, docNum: docnum },
                    dataType: "json",
                    success: function (response) {
                        if (response.status[0].cd==='1') {
                            alertify.success(response.status[0].msg)
                            mtr.remove()
                        } else {
                            alertify.message(response.status[0].msg)
                        }
                    }, error:function(xhr,xopt,xthrow){
                        alertify.error(xthrow)
                    }
                })
            }
        } else {
            mtr.remove()
        }        
    }
    

    function mpuror_ni_addrow(){
        let mytbody = document.getElementById('mpuror_tbl_nonitem').getElementsByTagName('tbody')[0]
        let newrow , newcell        
        newrow = mytbody.insertRow(-1)
        newrow.onclick = (event) => {mpuror_tbl_tbody_tr_eC(event)}
        
        newcell = newrow.insertCell(0)
        newcell.classList.add('d-none')
        newcell.innerHTML = ''

        newcell = newrow.insertCell(1)
        newcell.title = 'Item Name'
        newcell.contentEditable = true        
        newcell.onkeypress = (e) => {
            if(e.key==='Enter'){
                e.target.nextElementSibling.focus()
                e.preventDefault()
            }
        }       
        
        newcell.focus()        
        
        newcell = newrow.insertCell(2)
        newcell.title = 'unitmeasure'        
        newcell.contentEditable = true
        newcell.onkeypress = (e) => {
            if(e.key==='Enter'){
                e.target.nextElementSibling.focus()
                e.preventDefault()
            }
        }

        newcell = newrow.insertCell(3)
        newcell.title = 'qty'
        newcell.contentEditable = true
        newcell.classList.add('text-end')    
        newcell.onkeypress = (e) => {
            if(e.key==='Enter'){
                e.target.nextElementSibling.focus()
                e.preventDefault()
            }
        }    
        
        newcell = newrow.insertCell(4)
        newcell.classList.add('text-end')
        newcell.title = 'price'
        newcell.contentEditable = true        
        newcell.onkeypress = (e) => {
            if(e.key==='Enter'){
                e.target.nextElementSibling.focus()
                e.preventDefault()
            }
        }

        newcell = newrow.insertCell(5)
        newcell.classList.add('text-end')
        newcell.title = 'discount'
        newcell.contentEditable = true
        newcell.innerHTML = '0'
        newcell.onkeypress = (e) => {
            if(e.key==='Enter'){
                e.target.nextElementSibling.focus()
                e.preventDefault()
            }
        }

        newcell = newrow.insertCell(6)        
        newcell.title = 'department'
        newcell.contentEditable = true
        newcell.onkeypress = (e) => {
            if(e.key==='Enter'){
                e.target.nextElementSibling.focus()
                e.preventDefault()
            }
        }

        newcell = newrow.insertCell(7)
        newcell.title = 'section'
        newcell.contentEditable = true
        newcell.onkeypress = (e) => {
            if(e.key==='Enter'){
                e.target.nextElementSibling.focus()
                e.preventDefault()
            }
        }

        newcell = newrow.insertCell(8)
        newcell.title = 'subject'
        newcell.contentEditable = true
        newcell.onkeypress = (e) => {
            if(e.key==='Enter'){
                e.preventDefault()
            }
        }
    }    

    function mpuror_disc_addrow(){
        let mytbody = document.getElementById('mpuror_tbl_special').getElementsByTagName('tbody')[0]
        let newrow , newcell        
        newrow = mytbody.insertRow(-1)
        newrow.onclick = (event) => {mpuror_tbl_tbody_tr_eC(event)}
        
        newcell = newrow.insertCell(0)
        newcell.classList.add('d-none')
        newcell.innerHTML = ''

        newcell = newrow.insertCell(1)
        newcell.title = 'discount description'
        newcell.contentEditable = true
        newcell.focus()

        newcell = newrow.insertCell(2)
        newcell.classList.add('text-end')
        newcell.contentEditable = true
        newcell.innerText = '0'
        newcell.title = 'discount'
    }

    function mpuror_btnplus_disc_eCK(){
        mpuror_disc_addrow()
        let mytbody = document.getElementById('mpuror_tbl_special').getElementsByTagName('tbody')[0]
        mpuror_selected_row = mytbody.rows.length - 1
        mpuror_selected_col = 1
    }

    function mpuror_btnmin_disc_eCK(){
        let mytable = document.getElementById('mpuror_tbl_special').getElementsByTagName('tbody')[0]
        const mtr = mytable.getElementsByTagName('tr')[mpuror_selected_row]
        const mylineid = mtr.getElementsByTagName('td')[0].innerText.trim()
        if(mylineid!==''){
            if(confirm("Are you sure ?")){
                const docnum = document.getElementById('mpuror_txt_doc').value
                $.ajax({
                    type: "post",
                    url: "<?=base_url('PO/remove_discount')?>",
                    data: {lineId: mylineid, docNum: docnum },
                    dataType: "json",
                    success: function (response) {
                        if (response.status[0].cd==='1') {
                            alertify.success(response.status[0].msg)
                            mtr.remove()
                        } else {
                            alertify.message(response.status[0].msg)
                        }
                    }, error:function(xhr,xopt,xthrow){
                        alertify.error(xthrow)
                    }
                })
            }
        } else {
            mtr.remove()
        }
    }

    function mpuror_btn_save_eC(){        
        const btnsave = document.getElementById('mpuror_btn_save')
        const txt_po = document.getElementById('mpuror_txt_doc')
        const txt_remark = document.getElementById('mpuror_txt_remark')
        const txt_pph = document.getElementById('mpuror_txt_pph')
        const txt_vat = document.getElementById('mpuror_txt_VAT')
        const txt_reqby = document.getElementById('mpuror_reqstby')
        const cmb_payterm = document.getElementById('mpuror_cmb_payterm')
        const txt_reqdate = document.getElementById('mpuror_txt_dlvreqdate').value
        const txt_issudate = document.getElementById('mpuror_txt_issue').value
        const txt_shp = document.getElementById('mpuror_txt_shipdoc')
        const txt_shp_cost = document.getElementById('mpuror_txt_shpcost')        

        let mtbl = document.getElementById('mpuror_tbl')
        let tableku2 = mtbl.getElementsByTagName("tbody")[0]
        let mtbltr = tableku2.getElementsByTagName('tr')
        let ttlrows = mtbltr.length

        let arowid = []
        let aitem = []
        let aqty = []
        let aprice = []
        let adisc = []
        let adept = []
        let asection = []
        let asubject = []

        for(let i=0; i<ttlrows; i++){
            if(tableku2.rows[i].cells[1].innerText.length>2){
                arowid.push(tableku2.rows[i].cells[0].innerText)
                aitem.push(tableku2.rows[i].cells[1].innerText)
                aqty.push(numeral(tableku2.rows[i].cells[3].innerText).value())
                aprice.push(numeral(tableku2.rows[i].cells[4].innerText).value())
                adisc.push(tableku2.rows[i].cells[5].innerText.trim())
                adept.push(tableku2.rows[i].cells[6].innerText)
                asection.push(tableku2.rows[i].cells[7].innerText)
                asubject.push(tableku2.rows[i].cells[8].innerText)
            }
        }
        let datanya_nonitem = mpuror_sso_nonitem.getData()
        ttlrows = datanya_nonitem.length

        let an_rowid = []
        let an_itemnm = []        
        let an_umeasure = []
        let an_qty = []
        let an_price = []
        let an_disc = []
        let an_section = []
        let an_dept = []
        let an_subject = []

        for(let i=0; i<ttlrows; i++){
            if(datanya_nonitem[i][1].length>2){
                if(datanya_nonitem[i][6].trim().length==0 || datanya_nonitem[i][7].trim().length==0){
                    alertify.warning("Department or Section should not be empty")
                    return
                }
                an_rowid.push(datanya_nonitem[i][0])
                an_itemnm.push(datanya_nonitem[i][1])
                an_umeasure.push(datanya_nonitem[i][2])
                an_qty.push(numeral(datanya_nonitem[i][3]).value())
                an_price.push(numeral(datanya_nonitem[i][4]).value())
                an_disc.push(numeral(datanya_nonitem[i][5].trim()).value())
                an_dept.push(datanya_nonitem[i][6])
                an_section.push(datanya_nonitem[i][7])
                an_subject.push(datanya_nonitem[i][8])
            }
        }

        mtbl = document.getElementById('mpuror_tbl_special')
        tableku2 = mtbl.getElementsByTagName("tbody")[0]
        mtbltr = tableku2.getElementsByTagName('tr')
        ttlrows = mtbltr.length

        let ad_rowid = []
        let ad_description = []
        let ad_disc = []
        for(let i=0; i<ttlrows; i++){
            ad_rowid.push(tableku2.rows[i].cells[0].innerText)
            ad_description.push(tableku2.rows[i].cells[1].innerText)
            ad_disc.push(tableku2.rows[i].cells[2].innerText)
        }
        if(mpuror_vencd==''){
            alertify.warning('Vendor is required')
            document.getElementById('mpuror_btnfindmodvend').focus()
            return
        }
        if(arowid.length>0 || an_rowid.length>0){
            if(!confirm("Are you sure ?")){
                return
            }
            btnsave.disabled = true
            $.ajax({
                type: "POST",
                url: "<?=base_url('PO/save')?>",
                data: {
                    h_po : txt_po.value,
                    h_remark : txt_remark.value,
                    h_pph : txt_pph.value,
                    h_vat : txt_vat.value,
                    h_request_by : txt_reqby.value,
                    h_payterm : cmb_payterm.value,
                    h_req_date : txt_reqdate,
                    h_issu_date : txt_issudate,
                    h_shp : txt_shp.value,
                    h_shp_cost : numeral(txt_shp_cost.value).value(),
                    h_supplier : mpuror_vencd,
                    di_rowid : arowid,
                    di_item : aitem,
                    di_qty : aqty,
                    di_disc : adisc,
                    di_dept : adept,
                    di_section : asection,
                    di_subject : asubject,
                    di_price : aprice,
                    dni_rowid : an_rowid,
                    dni_item : an_itemnm,                    
                    dni_measure : an_umeasure,
                    dni_qty : an_qty,
                    dni_price : an_price,
                    dni_disc : an_disc,
                    dni_dept : an_dept,
                    dni_section : an_section,
                    dni_subject : an_subject,
                    dd_rowid : ad_rowid,
                    dd_description : ad_description,
                    dd_disc : ad_disc
                },
                dataType: "json",
                success: function (response) {
                    btnsave.disabled = false
                    if(response.status[0].cd==='1'){
                        alertify.success(response.status[0].msg)
                        document.getElementById('mpuror_txt_doc').value = response.status[0].doc                        
                        mpuror_get_detail(response.status[0].doc)
                    } else {
                        alertify.message(response.status[0].msg)                        
                    }
                }, error:function(xhr,xopt,xthrow){
                    alertify.error(xthrow)
                    btnsave.disabled = false
                }
            })
        } else {
            alertify.message('nothing to be saved')
        }
    }
    $("#mpuror_SavedDataMod").on('shown.bs.modal', function(){
        document.getElementById('mpuror_txtsearch').focus()
    })
    $("#mpuror_INTERNALITEM").on('shown.bs.modal', function(){
        document.getElementById('mpuror_txtsearchNALITM').focus()
    })
    function mpuror_btnmod_eC(){            
        $("#mpuror_SavedDataMod").modal('show')
    }
    function mpuror_txtsearch_eKP(e){
        if(e.key==='Enter') {
            const search = e.target.value
            const searchby = document.getElementById("mpuror_srchby").value
            const date0 = document.getElementById("mpuror_saved_date_1").value
            const date1 = document.getElementById("mpuror_saved_date_2").value
            const useperiod = document.getElementById('mpuror_ck_1').checked ? '1' : '0'
            e.target.readOnly = true
            $.ajax({
                type: "GET",
                url: "<?=base_url('PO/search')?>",
                data: {search : search, searchby: searchby, date0: date0, date1: date1, useperiod: useperiod},
                dataType: "json",
                success: function (response) {
                    e.target.readOnly = false
                    const ttlrows = response.data.length                    
                    let mydes = document.getElementById("mpuror_saved_div")
                    let myfrag = document.createDocumentFragment()
                    let mtabel = document.getElementById("mpuror_saved_tbl")
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln)
                    let tabell = myfrag.getElementById("mpuror_saved_tbl")
                    let tableku2 = tabell.getElementsByTagName("tbody")[0]
                    let newrow, newcell, newText                    
                    tableku2.innerHTML=''
                    for (let i = 0; i<ttlrows; i++){
                        newrow = tableku2.insertRow(-1)
                        newrow.onclick = (event) => {
                            $("#mpuror_SavedDataMod").modal('hide')
                            document.getElementById('mpuror_txt_remark').value = response.data[i].PO_RMRK
                            document.getElementById('mpuror_txt_pph').value = response.data[i].PO_PPH*1
                            document.getElementById('mpuror_txt_VAT').value = response.data[i].PO_VAT*1
                            document.getElementById('mpuror_vendname').value = response.data[i].MSUP_SUPNM
                            mpuror_vencd = response.data[i].PO_SUPCD
                            document.getElementById('mpuror_curr').value = response.data[i].MSUP_SUPCR
                            document.getElementById('mpuror_reqstby').value = response.data[i].PO_RQSTBY
                            document.getElementById('mpuror_cmb_payterm').value = response.data[i].PO_PAYTERM
                            document.getElementById('mpuror_txt_dlvreqdate').value = response.data[i].PO_REQDT
                            document.getElementById('mpuror_txt_issue').value = response.data[i].PO_ISSUDT
                            document.getElementById('mpuror_txt_shipdoc').value = response.data[i].PO_SHPDLV
                            document.getElementById('mpuror_txt_shpcost').value = response.data[i].PO_SHPCOST*1                            
                            document.getElementById('mpuror_txt_doc').value = response.data[i].PO_NO
                            document.getElementById('mpuror_tbl').getElementsByTagName('tbody')[0].innerHTML = ''
                            mpuror_get_detail(response.data[i].PO_NO)
                            document.getElementById('mpuror_saved_tbl').getElementsByTagName('tbody')[0].innerHTML = ''
                        }
                        newcell = newrow.insertCell(0)
                        newcell.innerHTML = response.data[i].PO_NO
                        newcell = newrow.insertCell(1)
                        newcell.innerHTML = response.data[i].MSUP_SUPNM
                        newcell = newrow.insertCell(2)
                        newcell.innerHTML = response.data[i].PO_ISSUDT
                        newcell = newrow.insertCell(3)
                        newcell.innerHTML = response.data[i].PO_REQDT
                    }
                    mydes.innerHTML=''
                    mydes.appendChild(myfrag)
                }, error:function(xhr,xopt,xthrow){
                    alertify.error(xthrow)
                    e.target.readOnly = false
                }
            })
        }        
    }

    function mpuror_get_detail(pdoc) {
        $.ajax({            
            url: "<?=base_url('PO/detail')?>",
            data: {doc: pdoc},
            dataType: "json",
            success: function (response) {
                let ttlrows = response.data.length
                let tableuse = {}
                if(ttlrows>0){
                    if(response.data[0].PO_ITMCD){
                        tableuse.tableid = 'mpuror_tbl'
                        tableuse.divid = 'mpuror_divku'
                    } else {
                        tableuse.tableid = 'mpuror_tbl_nonitem'
                        tableuse.divid = 'mpuror_tbl_nonitem_div'
                    }
                }                                
                if(tableuse.tableid=='mpuror_tbl'){
                    mpuror_sso_nonitem.setData([[],[],[],[],[]])
                    let mydes = document.getElementById(tableuse.divid)
                    let myfrag = document.createDocumentFragment()
                    let mtabel = document.getElementById(tableuse.tableid)
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln)
                    let tabell = myfrag.getElementById(tableuse.tableid)
                    let tableku2 = tabell.getElementsByTagName("tbody")[0]
                    let newrow, newcell, newText                    
                    tableku2.innerHTML=''
                    for (let i = 0; i<ttlrows; i++){
                        newrow = tableku2.insertRow(-1)
                        newrow.onclick = (event) => {mpuror_tbl_tbody_tr_eC(event)}
                        newcell = newrow.insertCell(0)
                        newcell.classList.add('d-none')
                        newcell.innerHTML = response.data[i].PO_LINE
                        newcell = newrow.insertCell(1)
                        newcell.classList.add('table-secondary')
                        newcell.onclick = () => {
                            $("#mpuror_INTERNALITEM").modal('show')
                        }                        
                        newcell.innerHTML = response.data[i].PO_ITMCD
                        newcell = newrow.insertCell(2)
                        newcell.classList.add('table-secondary')
                        newcell.innerHTML = response.data[i].MITM_ITMD1
                        newcell = newrow.insertCell(3)
                        newcell.contentEditable = true
                        newcell.classList.add('text-end')
                        newcell.innerHTML = response.data[i].PO_QTY
                        newcell = newrow.insertCell(4)
                        newcell.contentEditable = true
                        newcell.classList.add('text-end')
                        newcell.innerHTML = response.data[i].PO_PRICE
                        newcell = newrow.insertCell(5)
                        newcell.contentEditable = true
                        newcell.classList.add('text-end')
                        newcell.innerHTML = response.data[i].PO_DISC
                        newcell = newrow.insertCell(6)
                        newcell.contentEditable = true
                        newcell.innerHTML = response.data[i].PO_DEPT
                        newcell = newrow.insertCell(7)
                        newcell.contentEditable = true
                        newcell.innerHTML = response.data[i].PO_SECTION
                        newcell = newrow.insertCell(8)
                        newcell.contentEditable = true
                        newcell.innerHTML = response.data[i].PO_SUBJECT
                    }
                    let firstTabEl = document.querySelector('#myTab button[data-bs-target="#mpuror_tabRM"]')
                    let thetab = new bootstrap.Tab(firstTabEl)
                    thetab.show()
                    mydes.innerHTML=''
                    mydes.appendChild(myfrag)
                } else {
                    let datanya =  []
                    for (let i = 0; i<ttlrows; i++){
                        datanya.push( [
                            response.data[i].PO_LINE,
                            response.data[i].PO_ITMNM,
                            response.data[i].PO_UM,
                            response.data[i].PO_QTY,
                            response.data[i].PO_PRICE,
                            response.data[i].PO_DISC,
                            response.data[i].PO_DEPT,
                            response.data[i].PO_SECTION,
                            response.data[i].PO_SUBJECT
                        ])                  
                    }
                    mpuror_sso_nonitem.setData(datanya)
                    let firstTabEl = document.querySelector('#myTab button[data-bs-target="#mpuror_tabFG"]')
                    let thetab = new bootstrap.Tab(firstTabEl)
                    thetab.show()
                }


                //load discount
                ttlrows = response.data_discount.length
                mydes = document.getElementById('mpuror_tbl_special_div')
                myfrag = document.createDocumentFragment()
                mtabel = document.getElementById('mpuror_tbl_special')
                cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln)
                tabell = myfrag.getElementById('mpuror_tbl_special')
                tableku2 = tabell.getElementsByTagName("tbody")[0]
                tableku2.innerHTML=''
                for (let i = 0; i<ttlrows; i++){
                    newrow = tableku2.insertRow(-1)
                    newrow.onclick = (event) => {mpuror_tbl_tbody_tr_eC(event)}
                    newcell = newrow.insertCell(0)
                    newcell.classList.add('d-none')
                    newcell.innerHTML = response.data_discount[i].PODISC_LINE
                    newcell = newrow.insertCell(1)
                    newcell.contentEditable = true
                    newcell.innerHTML = response.data_discount[i].PODISC_DESC
                    newcell = newrow.insertCell(2)
                    newcell.classList.add('text-end')
                    newcell.contentEditable = true
                    newcell.innerHTML = response.data_discount[i].PODISC_DISC
                }
                mydes.innerHTML=''
                mydes.appendChild(myfrag)
            }, error:function(xhr,xopt,xthrow){
                alertify.error(xthrow)
            }
        })
    }

    function mpuror_tbl_dept_div_eCK(e){
        if(e.target.tagName.toLowerCase()=='td'){
            if(e.target.cellIndex==0){
                $("#mpuror_Department").modal('hide')
            }
        }
    }

    function mpuror_tbl_nonitem_div_eCK(e){
        if(e.ctrlKey){   
            if(e.target.tagName.toLowerCase()=='td'){
                if(e.target.cellIndex==7){
                    $("#mpuror_Department").modal('show')
                } else if(e.target.cellIndex==8) {
                    
                }
            }
        }
    }

    function mpuror_txtsearchNALITM_eKP(e){
        if(e.key === 'Enter') {
            const searchBy = document.getElementById('mpuror_NALITM_searchby').value
            const search = e.target.value
            const lblwait = '<tr><td colspan="4" class="text-center">Please wait</td></tr>'
            document.getElementById('mpuror_tblNALITM').getElementsByTagName('tbody')[0].innerHTML = lblwait            
            $.ajax({
                type: "GET",
                url: "<?=base_url('MSTITM/search_internal_item')?>",
                data: {searchBy: searchBy, search: search },
                dataType: "JSON",
                success: function (response) {
                    const ttlrows = response.data.length                    
                    if( ttlrows > 0){
                        let mydes = document.getElementById("mpuror_tblNALITM_div")
                        let myfrag = document.createDocumentFragment()
                        let mtabel = document.getElementById("mpuror_tblNALITM")
                        let cln = mtabel.cloneNode(true);
                        myfrag.appendChild(cln)
                        let tabell = myfrag.getElementById("mpuror_tblNALITM")
                        let tableku2 = tabell.getElementsByTagName("tbody")[0]
                        let newrow, newcell, newText
                        let myitmttl = 0;
                        tableku2.innerHTML=''
                        for (let i = 0; i<ttlrows; i++){
                            newrow = tableku2.insertRow(-1)
                            newcell = newrow.insertCell(0)
                            newcell.style.cssText = "cursor:pointer"
                            newcell.onclick = () => {
                                let isfound = false
                                let ttlrowstarget = document.getElementById('mpuror_tbl').getElementsByTagName('tbody')[0].rows.length
                                for(let k=0;k<ttlrowstarget;k++) {
                                    if(document.getElementById('mpuror_tbl').getElementsByTagName('tbody')[0].rows[k].cells[3].innerHTML===response.data[i].MITM_ITMCD) {
                                        isfound = true
                                        break
                                    }
                                }
                                if(!isfound) {
                                    document.getElementById('mpuror_tbl').getElementsByTagName('tbody')[0].rows[mpuror_selected_row].cells[1].innerHTML = response.data[i].MITM_ITMCD.trim()
                                    document.getElementById('mpuror_tbl').getElementsByTagName('tbody')[0].rows[mpuror_selected_row].cells[2].innerHTML = response.data[i].MITM_ITMD1
                                    document.getElementById('mpuror_tbl').getElementsByTagName('tbody')[0].rows[mpuror_selected_row].cells[3].focus()
                                    $("#mpuror_INTERNALITEM").modal('hide')
                                } else {
                                    alertify.message('already added')
                                }
                            }
                            newcell.innerHTML = response.data[i].MITM_ITMCD.trim()
                            newcell = newrow.insertCell(1)
                            newcell.innerHTML = response.data[i].MITM_ITMCDCUS
                            newcell = newrow.insertCell(2)
                            newcell.innerHTML = response.data[i].MITM_ITMD1
                            newcell = newrow.insertCell(3)
                            newcell.innerHTML = response.data[i].MITM_STKUOM
                        }
                        mydes.innerHTML=''
                        mydes.appendChild(myfrag)
                    } else {
                        document.getElementById('mpuror_tblNALITM').getElementsByTagName('tbody')[0].innerHTML = `<tr><td colspan="4" class="text-center">Not found</td></tr>`
                    }
                }, error: function (xhr,ajaxOptions, throwError) {
                    alertify.error(xthrow)
                }
            })
        }
    }

    function mpuror_btnexport_eCK(){
        const date1 = document.getElementById('mpuror_saved_date_1').value
        const date2 = document.getElementById('mpuror_saved_date_2').value
        Cookies.set('PO_DATE1', date1 , {expires:365})
        Cookies.set('PO_DATE2', date2 , {expires:365})
        window.open("<?=base_url('po_list')?>" ,'_blank');
    }
</script>