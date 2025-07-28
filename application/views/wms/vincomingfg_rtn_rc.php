<style>   
    .anastylesel_rc{
        background: red;
        animation: anamove 1s infinite;
    }
    @keyframes anamove {
        from {background: #7FDBFF;}
        to {background: #01FF70;}
    }
</style>
<div style="padding: 10px">
	<div class="container-fluid">
        <div class="row">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >Serah Terima Dok.</span>
                    <input type="text" class="form-control" id="rcvfg_rtn_rc_txt_serahterima" placeholder="Autonumber"  readonly>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#rcvfg_rtn_rc_MOD_serahterima"><i class="fas fa-search"></i></button>
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >Date</span>
                    <input type="text" class="form-control" id="rcvfg_rtn_rc_txt_date" readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-7 mb-1">
                <h3 ><span class="badge bg-info">1 <i class="fas fa-hand-point-right" ></i></span> <span class="badge bg-info">Status Label</span></h3>
            </div>
            <div class="col-md-5 mb-1 text-end">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="rcvfg_rtn_rc_typefg" id="rcvfg_rtn_rc_withjm" value="JM" checked>
                    <label class="form-check-label" for="rcvfg_rtn_rc_withjm">With JM</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="rcvfg_rtn_rc_typefg" id="rcvfg_rtn_rc_withoutjm" value="NOJM">
                    <label class="form-check-label" for="rcvfg_rtn_rc_withoutjm">Without JM</label>
                </div>                
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >ID</span>
                    <input type="text" class="form-control" id="rcvfg_rtn_rc_txt_code" onkeypress="rcvfg_rtn_rc_txt_code_e_keypress(event)" placeholder="scan it here..." required style="text-align:center" autocomplete="off">
                    <span class="input-group-text" ondblclick="cancelconfirmID_e_dblclick()"><i class="fas fa-barcode"></i></span>
                </div>
            </div>
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >Assy Code</span>
                    <input type="text" class="form-control" id="rcvfg_rtn_rc_txt_assyno" readonly>
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >Assy Name</span>
                    <input type="text" class="form-control" id="rcvfg_rtn_rc_txt_assynm" readonly>
                </div>
            </div>
            <div class="col-md-2 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >Qty</span>
                    <input type="text" class="form-control" id="rcvfg_rtn_rc_txt_assyqty" readonly>
                </div>
            </div>
        </div> 
        <div class="row">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >Document</span>
                    <input type="text" class="form-control" id="rcvfg_rtn_rc_txt_docno" readonly>
                </div>
            </div>
        </div>  
        <div id="rcvfg_rtn_rc_div_jm">
            <div class="row">
                <div class="col-md-2 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >JM</span>
                        <input type="text" class="form-control" id="rcvfg_rtn_rc_txt_jmcode" maxlength="9" placeholder="scan JMCode here..." required style="text-align:center" onkeypress="rcvfg_rtn_rc_txt_jmcode_e_keypress(event)">
                        <span class="input-group-text" ><i class="fas fa-barcode"></i></span>
                    </div>
                </div>           
                <div class="col-md-5 mb-1">                
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" onclick="rcvfg_rtn_rc_showpartreq()" style="cursor: pointer;">Item Code</span>
                        <input type="text" class="form-control" id="rcvfg_rtn_rc_txt_itemcode" onkeypress="rcvfg_rtn_rc_txt_itemcode_e_keypress(event)" > 
                    </div>                
                    <input type="hidden" id="rcvfg_rtn_rc_txt_itemname">
                </div>
                <div class="col-md-3 mb-1">
                    <div class="input-group input-group-sm mb-1">                    
                        <span class="input-group-text" >Lot No</span>
                        <input type="text" class="form-control font-monospace" id="rcvfg_rtn_rc_g_lotno"  onkeypress="rcvfg_rtn_rc_g_lotno_e_keypress(event)" > 
                    </div>
                </div>
                <div class="col-md-2 mb-1">
                    <div class="input-group input-group-sm mb-1"> 
                        <span class="input-group-text" >Location</span>
                        <input type="text" class="form-control" id="rcvfg_rtn_rc_txt_itemcode_loc" onkeypress="rcvfg_rtn_rc_txt_itemcode_loc_e_keypress(event)" > 
                    </div>
                </div>
            </div> 
            <div class="row">
                <div class="col-md-12 mb-1 text-end">
                    <div class="btn-group btn-group-sm" role="group" >
                        <button type="button" class="btn btn-outline-primary" id="rcvfg_rtn_rc_btn_add" onclick="rcvfg_rtn_rc_btn_add_e_click()">Next JM</button>
                        <button type="button" class="btn btn-outline-primary" id="rcvfg_rtn_rc_btn_add_id" onclick="rcvfg_rtn_rc_btn_add_id_e_click()">Next ID</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="rcvfg_rtn_rc_div_wjm" class="d-none">
            <div class="row">
                <div class="col-md-3 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >Item Code</span>
                        <input type="text" class="form-control" id="rcvfg_rtn_rc_txt_wjm_itemcode" onkeypress="rcvfg_rtn_rc_txt_wjm_itemcode_e_keypress(event)"  > 
                    </div>
                    <input type="hidden" id="rcvfg_rtn_rc_txt_wjm_itemname">
                </div>
                <div class="col-md-3 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >Qty</span>
                        <input type="text" class="form-control" id="rcvfg_rtn_rc_txt_wjm_qty" onkeypress="rcvfg_rtn_rc_txt_wjm_qty_e_keypress(event)">
                        <span class="input-group-text" ><i class="fas fa-barcode"></i></span>
                    </div>
                </div>
                <div class="col-md-3 mb-1">
                    <div class="input-group input-group-sm mb-1">                    
                        <span class="input-group-text" >Lot No</span>
                        <input type="text" class="form-control font-monospace" id="rcvfg_rtn_rc_wjm_lotno" onkeypress="rcvfg_rtn_rc_wjm_lotno_e_keypress(event)" > 
                    </div>
                </div>
                <div class="col-md-3 mb-1">
                    <div class="input-group input-group-sm mb-1"> 
                        <span class="input-group-text" >Location</span>
                        <input type="text" class="form-control" id="rcvfg_rtn_rc_txt_wjm_itemcode_loc"  > 
                    </div>
                </div>
            </div> 
            <div class="row">
                <div class="col-md-12 mb-1 text-end">
                    <div class="btn-group btn-group-sm" role="group" >
                        <button type="button" class="btn btn-outline-primary" id="rcvfg_rtn_rc_btn_wjm_add" onclick="rcvfg_rtn_rc_btn_wjm_add_e_click()">Add</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1" onclick="rcvfg_rtn_rc_e_click(event)">
                <div class="table-responsive" id="rcvfg_rtn_rc_divsubku" >
                    <table id="rcvfg_rtn_rc_tblsub" class="table table-sm table-striped table-hover table-bordered" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Assy Code</th>
                                <th>Assy Qty</th>
                                <th>JM</th>
                                <th>Item Code</th>
                                <th>Item Name</th>
                                <th class="text-end">Qty</th>
                                <th >Location</th>
                                <th class="d-none">Line</th>
                                <th class="d-none">Lotno</th>
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
                <h3 ><span class="badge bg-info">2 <i class="fas fa-hand-point-right" ></i></span> <span class="badge bg-info">Customer Label</span></h3>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >Reff No.</span>
                    <input type="text" class="form-control" id="rcvfg_rtn_rc_txt_codeext" onkeypress="rcvfg_rtn_rc_txt_codeext_e_keypress(event)" placeholder="scan it here..." required style="text-align:center">                      
                    <span class="input-group-text" ondblclick="rcvfg_rtn_rc_save_oth_eCK()" ><i class="fas fa-qrcode"></i></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="rcvfg_rtn_rc_divext">
                    <table id="rcvfg_rtn_rc_tblext" class="table table-sm table-striped table-hover table-bordered" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th>Reff No</th>
                                <th>ID</th>
                                <th>Assy Code</th>
                                <th>Qty</th>
                                <th class="d-none">RawText</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1 text-center">
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-primary" id="rcvfg_rtn_rc_btn_new" onclick="rcvfg_rtn_rc_btn_new_e_click()"><i class="fas fa-file"></i></button>  
                    <button type="button" class="btn btn-primary" id="rcvfg_rtn_rc_btn_save" onclick="rcvfg_rtn_rc_btn_save_e_click()"><i class="fas fa-save"></i></button>                
                    <button type="button" class="btn btn-outline-primary" id="rcvfg_rtn_rc_btn_print" onclick="rcvfg_rtn_rc_btn_print_e_click()"><i class="fas fa-print"></i></button>
                </div>
            </div>
        </div>     
    </div>
</div>

<div id="rcvfg_rtn_rc_context_menu">
</div>
<div id="rcvfg_rtn_rc_context_menu_custlabel">
</div>
<div id="rcvfg_rtn_rc_ctx_menu">
</div>
<input type="hidden" id="rcvfg_rtn_rc_g_id">
<input type="hidden" id="rcvfg_rtn_rc_g_assycd">
<input type="hidden" id="rcvfg_rtn_rc_txt_itemcode_qty">
<div class="modal fade" id="rcvfg_rtn_rc_MOD_serahterima">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Document List</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>        
        <!-- Modal body -->
        <div class="modal-body">              
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">Search by</label>
                        <select class="form-select" id="rcvfg_rtn_rc_cmb_searchby" onchange="rcvfg_rtn_rc_cmb_searchby_e_onchange()" required>
                        <option value="ST">Serah Terima Doc.</option>
                        <option value="DO">RA Doc.</option>
                        <option value="SL">Status Label</option>
                        </select>
                        <input type="text" class="form-control" id="rcvfg_rtn_rc_txt_search_serahterima" title="press enter to start searching" onkeypress="rcvfg_rtn_rc_txt_search_serahterima_e_keypress(event)">
                    </div>
                </div>
            </div>
            <div class="row">               
                <div class="col-md-12 mb-1 text-end">
                    <span class="badge bg-info" id="rcvfg_rtn_rc_tbl_serahterima_lblinfo"></span>
                </div>                
            </div>           
            <div class="row">
                <div class="col" id="rcvfg_rtn_rc_divku_search_serahterima">
                    <table id="rcvfg_rtn_rc_tbl_serahterima" class="table table-hover table-sm table-bordered" style="width:100%;cursor:pointer;font-size:75%">
                        <thead class="table-light">
                            <tr>
                                <th>Serah Terima Doc.</th>
                                <th>Date</th>
                                <th>RA Doc.</th>
                                <th>PIC</th>
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
<div class="modal fade" id="rcvfg_rtn_rc_MODlot">
    <div class="modal-dialog">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title"><i class="fas fa-copy"></i></h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">            
            <div class="row">
                <div class="col-md-12 mb-1 p-1">
                    <div class="table-responsive">
                        <table id="rcvfg_rtn_rc_tbllot" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer;font-size:91%">
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
<div class="modal fade" id="rcvfg_rtn_rc_MODPartreq">
    <div class="modal-dialog">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title"><i class="fas fa-list"></i></h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">            
            <div class="row">
                <div class="col-md-12 mb-1 p-1">
                    <div class="table-responsive" id="rcvfg_rtn_rc_divpartreq">
                        <table id="rcvfg_rtn_rc_tblpartreq" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer;font-size:91%">
                            <thead>
                                <th>Item Code</th>
                                <th>Item Name</th>
                                <th class="text-end">QTY</th>
                                <th>Lot Number</th>
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
    var rcvfg_rtn_context_menu = jSuites.contextmenu(rcvfg_rtn_rc_context_menu, {
        items : [
            {
                title : 'Cancel',
                onclick : function() {
                    rcvfg_rtn_rc_e_cancelitem()
                },
                tooltip : 'Method to cancel transaction per row'
            }
        ],
        onclick : function() {
            rcvfg_rtn_context_menu.close(false)
        }
    })
    var rcvfg_rtn_context_menu_custlabel = jSuites.contextmenu(rcvfg_rtn_rc_context_menu_custlabel, {
        items : [
            {
                title : 'Cancel',
                onclick : function() {
                    rcvfg_rtn_rc_e_cancelitem_custlabel()
                },
                tooltip : 'Method to cancel transaction per row'
            }
        ],
        onclick : function() {
            rcvfg_rtn_context_menu_custlabel.close(false)
        }
    })
    var rcvfg_rtn_context_menu_lot = jSuites.contextmenu(rcvfg_rtn_rc_ctx_menu, {
        items : [
            {
                title : 'Copy Lot',
                onclick : function() {
                    rcvfg_rtn_rc_e_copylot()
                },
                tooltip : 'Method to copy transaction per row'
            }
        ],
        onclick : function() {
            rcvfg_rtn_context_menu_lot.close(false)
        }
    })
    function rcvfg_rtn_rc_showpartreq(){
        const radoc = document.getElementById('rcvfg_rtn_rc_txt_docno').value;
        if(radoc.trim().length==0){
            return
        }
        $("#rcvfg_rtn_rc_tblpartreq tbody").empty()
        $.ajax({
            type: "get",
            url: "<?=base_url('SPL/getPartReqRA')?>",
            data: {inRA: radoc},
            dataType: "json",
            success: function (response) {
                const ttlrows = response.data.length;
                if(ttlrows>0){
                    let mydes = document.getElementById("rcvfg_rtn_rc_divpartreq");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("rcvfg_rtn_rc_tblpartreq");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("rcvfg_rtn_rc_tblpartreq");                    
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    let newrow, newcell, newText;
                    tableku2.innerHTML='';
                    for (let i = 0; i<ttlrows; i++){
                        newrow = tableku2.insertRow(-1);  
                        newrow.onclick = () => {
                            document.getElementById('rcvfg_rtn_rc_txt_itemcode').value = response.data[i].SPLSCN_ITMCD
                            document.getElementById('rcvfg_rtn_rc_g_lotno').value = response.data[i].SPLSCN_LOTNO
                            document.getElementById('rcvfg_rtn_rc_txt_itemname').value = response.data[i].MITM_ITMD1
                            document.getElementById('rcvfg_rtn_rc_txt_itemcode_loc').focus()
                            $("#rcvfg_rtn_rc_MODPartreq").modal('hide');
                        }
                        newcell = newrow.insertCell(0); 
                        newcell.innerHTML = response.data[i].SPLSCN_ITMCD
                        newcell = newrow.insertCell(1); 
                        newcell.innerHTML = response.data[i].MITM_ITMD1
                        newcell = newrow.insertCell(2); 
                        newcell.classList.add('text-end')
                        newcell.innerHTML = numeral(response.data[i].SCNQTY).format(',')
                        newcell = newrow.insertCell(3); 
                        newcell.innerHTML = response.data[i].SPLSCN_LOTNO
                    }   
                    mydes.innerHTML='';
                    mydes.appendChild(myfrag);
                    $("#rcvfg_rtn_rc_MODPartreq").modal('show');
                } else {
                    alertify.message("Part Request for this RA is not found")
                }
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
        
    }

    function rcvfg_rtn_rc_is_INTEXT_paired(plabel){
        let mtbl = document.getElementById('rcvfg_rtn_rc_tblext');
        let tableku2 = mtbl.getElementsByTagName("tbody")[0];
        let mtbltr = tableku2.getElementsByTagName('tr'); 
        let ttlrows = mtbltr.length;
        let ispaired = false;
        for(let n=0;n<ttlrows; n++){
            if (tableku2.rows[n].cells[1].innerText == plabel.internal && tableku2.rows[n].cells[0].innerText == plabel.external){
                ispaired = true;
                break;
            }
        }
        return ispaired;
    }
    function rcvfg_rtn_rc_txt_wjm_itemcode_e_keypress(e){
        if(e.which==13){
            let enteredVal = document.getElementById('rcvfg_rtn_rc_txt_wjm_itemcode').value.toUpperCase();
            if(enteredVal.includes("3N1")){
                enteredVal = enteredVal.substr(3,enteredVal.length);
                document.getElementById('rcvfg_rtn_rc_txt_wjm_itemcode').value = enteredVal;
            }
            if(rcvfg_rtn_rc_a_rmcode.length>0){
                let ttlrows = rcvfg_rtn_rc_a_rmcode.length;
                let isfound = false;
                for(let i=0; i< ttlrows; i++){
                    if(rcvfg_rtn_rc_a_rmcode[i].id==enteredVal){
                        isfound = true;
                        document.getElementById('rcvfg_rtn_rc_txt_itemname').value = rcvfg_rtn_rc_a_rmcode[i].description;
                        break;
                    }
                }
                if(isfound){
                    document.getElementById('rcvfg_rtn_rc_txt_wjm_qty').focus();
                } else {
                    alertify.message('Item code is not valid for the model');
                }
            } else {
                alertify.message('Item Code List is empty');
                document.getElementById('rcvfg_rtn_rc_txt_itemname').value = "";
            }
        }
    }
    $("input[name='rcvfg_rtn_rc_typefg']").change(function(){        
        let curv = $(this).val();
        if(curv=='JM'){
            $("#rcvfg_rtn_rc_div_jm").removeClass("d-none");
            $("#rcvfg_rtn_rc_div_wjm").addClass("d-none");            
        } else {
            $("#rcvfg_rtn_rc_div_jm").addClass("d-none");
            $("#rcvfg_rtn_rc_div_wjm").removeClass("d-none");            
        }    
        document.getElementById('rcvfg_rtn_rc_txt_code').focus();
    });
    var rcvfg_rtn_rc_a_rmcode = [];
    function rcvfg_rtn_rc_selectElementContents(el) {
        let body = document.body, range, sel;
        if (document.createRange && window.getSelection) {
            range = document.createRange();
            sel = window.getSelection();
            sel.removeAllRanges();
            try {
                range.selectNodeContents(el);
                sel.addRange(range);
            } catch (e) {
                range.selectNode(el);
                sel.addRange(range);
            }
        } else if (body.createTextRange) {
            range = body.createTextRange();
            range.moveToElementText(el);
            range.select();
        }
    }
    $("#rcvfg_rtn_rc_MODlot").on('shown.bs.modal', function(){
        document.execCommand("copy");
        alertify.success('ok just paste to your application');
    });
    function rcvfg_rtn_rc_e_copylot(){
        let doc = document.getElementById('rcvfg_rtn_rc_txt_serahterima').value;
        let jmcode = document.getElementById('rcvfg_rtn_rc_g_id').value;
        $.ajax({
            type: "get",
            url: "<?=base_url('SER/getlotno_rc')?>",
            data: {indoc : doc, injm: jmcode},
            dataType: "json",
            success: function (response) {
                let ttlrows = response.data.length;
                let tabel_PLOT = document.getElementById("rcvfg_rtn_rc_tbllot");
                let tabel_PLOT_body0 = tabel_PLOT.getElementsByTagName("tbody")[0];
                let strdata = '';
                tabel_PLOT_body0.innerHTML = "";
                for(let i=0;i<ttlrows; i++){
                    strdata += response.data[i].SERRC_LOTNO + ',';
                }
                strdata = strdata.substr(0, strdata.length-1);
                newrow = tabel_PLOT_body0.insertRow(-1);
                newcell = newrow.insertCell(0);
                newText = document.createTextNode(strdata);
                newcell.appendChild(newText);
                $("#rcvfg_rtn_rc_MODlot").modal('show');
                rcvfg_rtn_rc_selectElementContents( document.getElementById('rcvfg_rtn_rc_tbllot'));
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
        
    }
    function rcvfg_rtn_rc_btn_add_id_e_click(){
        
        let docno = document.getElementById('rcvfg_rtn_rc_txt_serahterima').value;
        let qalabel = document.getElementById('rcvfg_rtn_rc_txt_code');
        let jmcode = document.getElementById("rcvfg_rtn_rc_txt_jmcode");
        let bompn = document.getElementById('rcvfg_rtn_rc_txt_itemcode').value;
        let bompn_nm = document.getElementById("rcvfg_rtn_rc_txt_itemname").value;            
        let bompn_loc = document.getElementById("rcvfg_rtn_rc_txt_itemcode_loc");
        let assycode = document.getElementById('rcvfg_rtn_rc_txt_assyno').value;
        let assyqty = document.getElementById('rcvfg_rtn_rc_txt_assyqty').value;
        let rmqty = numeral(document.getElementById('rcvfg_rtn_rc_txt_itemcode_qty').value).value();
        let lotno = document.getElementById('rcvfg_rtn_rc_g_lotno').value;        
        if(qalabel.readOnly && qalabel.value.trim().length>3){
            if (jmcode.value.trim().length!=9){
                alertify.message('JM Code is not valid');
                jmcode.focus();
                return;
            }
            if(rmqty<=0 || !rmqty ){
                if(bompn.trim().length>0){
                    alertify.warning("QTY is required");
                    document.getElementById('rcvfg_rtn_rc_txt_itemcode_qty').focus();
                    return;
                }                              
            } else {
                //qty exist
                if(bompn.trim().length>0){

                } else {
                    alertify.warning("Part Code is required");
                    return;
                }
            }           
            if(!rcvfg_rtn_rc_e_checktempkeys({p1: jmcode.value.toUpperCase(), p2: bompn_loc.value.toUpperCase(), p3: qalabel.value.toUpperCase()})){
                alertify.warning("a location is only for 1 item code");
                return;
            }
            if(!rcvfg_rtn_rc_e_checkuniquejm({p1: jmcode.value.toUpperCase(), p2 : qalabel.value.toUpperCase()})){
                alertify.warning("OVER");
                return;
            }
            document.getElementById('rcvfg_rtn_rc_btn_add_id').disabled = true;
            //server side checking
            $.ajax({
                type: "get",
                url: "<?=base_url('SER/checkbalanceqalabel')?>",
                data: {inqalabel: qalabel.value, indoc_st: docno },
                dataType: "json",
                success: function (response) {
                    let maxqty =  numeral(document.getElementById('rcvfg_rtn_rc_txt_assyqty').value).value();
                    let ttlrows = response.data.length;                    
                    let qalabel_qty = numeral(document.getElementById('rcvfg_rtn_rc_txt_assyqty').value).value();
                    let mtbl = document.getElementById('rcvfg_rtn_rc_tblsub');
                    let tableku2 = mtbl.getElementsByTagName("tbody")[0];                                        
                    if(ttlrows < qalabel_qty){
                        //validate temp vs saved on database
                        let mtbltr = tableku2.getElementsByTagName('tr');
                        let ttltemprows = mtbltr.length;
                        let ajm = [];
                        for(let i=0;i<ttltemprows;i++){
                            if(tableku2.rows[i].cells[0].innerText == qalabel.value) {
                                let adata = tableku2.rows[i].cells[3].innerText;
                                if(!ajm.includes(adata)){
                                    ajm.push(adata);
                                }
                            }
                        }
                        let isok = true;
                        if((ajm.length+ttlrows)>=maxqty){
                            if(!ajm.includes(jmcode.value)){
                                isok = false;
                            }            
                        }
                        if(isok){
                            rcvfg_rtn_addrows(tableku2, {qalabel: qalabel.value
                                , assycode: assycode
                                , assyqty: assyqty
                                , jmcode: jmcode.value
                                , bompn: bompn
                                , bompn_nm: bompn_nm
                                , rmqty: rmqty
                                , bompn_loc: bompn_loc.value
                                , lotno: lotno
                            });
                            jmcode.value = "";
                            bompn_loc.value = "";                                        
                        } else {
                            alertify.warning("OVER..");
                        }
                    } else {
                        let isfound = false;
                        for(let i=0;i<ttlrows; i++){
                            if(response.data[i].SERRC_JM == jmcode.value){
                                isfound = true;
                                break;
                            }
                        }
                        if(isfound){    
                            rcvfg_rtn_addrows(tableku2, {qalabel: qalabel.value
                                , assycode: assycode
                                , assyqty: assyqty
                                , jmcode: jmcode.value
                                , bompn: bompn
                                , bompn_nm: bompn_nm
                                , rmqty: rmqty
                                , bompn_loc: bompn_loc.value
                                , lotno: lotno
                            });
                            jmcode.value = "";
                            bompn_loc.value = "";                            
                        } else {
                            alertify.warning("OVER...");
                        }
                    }
                    document.getElementById('rcvfg_rtn_rc_txt_itemcode').value ="";
                    qalabel.value = "";
                    qalabel.readOnly = false;
                    document.getElementById('rcvfg_rtn_rc_txt_assyno').value='';
                    document.getElementById('rcvfg_rtn_rc_txt_assynm').value='';
                    document.getElementById('rcvfg_rtn_rc_txt_assyqty').value='';
                    document.getElementById('rcvfg_rtn_rc_txt_docno').value='';
                    document.getElementById('rcvfg_rtn_rc_txt_itemcode_qty').value ="0";
                    document.getElementById('rcvfg_rtn_rc_g_lotno').value ="";
                    qalabel.focus();                    
                    document.getElementById('rcvfg_rtn_rc_btn_add_id').disabled = false;
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                    document.getElementById('rcvfg_rtn_rc_btn_add_id').disabled = false;
                }
            });            
        } else {
            qalabel.focus();
            alertify.warning("Please scan status label first !");
        }
        document.getElementById('rcvfg_rtn_rc_txt_itemname').value = "";
    }
    function rcvfg_rtn_rc_cmb_searchby_e_onchange(){
        document.getElementById('rcvfg_rtn_rc_txt_search_serahterima').focus();
    }
    function rcvfg_rtn_rc_vdt_itemcode(){
        let enteredVal = document.getElementById('rcvfg_rtn_rc_txt_itemcode').value.toUpperCase();
        if(enteredVal.includes("3N1")){
            enteredVal = enteredVal.substr(3,enteredVal.length);
            document.getElementById('rcvfg_rtn_rc_txt_itemcode').value = enteredVal;
        }
        if(rcvfg_rtn_rc_a_rmcode.length>0){
            let ttlrows = rcvfg_rtn_rc_a_rmcode.length;
            let isfound = false;                
            for(let i=0; i< ttlrows; i++){
                if(rcvfg_rtn_rc_a_rmcode[i].id==enteredVal){
                    isfound = true;
                    document.getElementById('rcvfg_rtn_rc_txt_itemname').value = rcvfg_rtn_rc_a_rmcode[i].description;
                    break;
                }
            }
            if(isfound){
                document.getElementById('rcvfg_rtn_rc_g_lotno').focus();
            } else {
                alertify.message('Item code is not valid for the model');
            }
        } else {
            alertify.message('Item Code List is empty');
            document.getElementById('rcvfg_rtn_rc_txt_itemname').value = "";
        }
    }
    function rcvfg_rtn_rc_txt_itemcode_e_keypress(e){
        if(e.which==13){
            rcvfg_rtn_rc_vdt_itemcode();            
        }
    }
    function rcvfg_rtn_rc_g_lotno_e_keypress(e){
        if (e.which==13){
            let thisval = document.getElementById('rcvfg_rtn_rc_g_lotno').value.toUpperCase();
            if(thisval.includes(" ") && thisval.includes("3N2")){
                let aval = thisval.split(" ");
                document.getElementById('rcvfg_rtn_rc_txt_itemcode_qty').value = 1;
                document.getElementById('rcvfg_rtn_rc_g_lotno').value = aval[2];
            } else {
                document.getElementById('rcvfg_rtn_rc_g_lotno').value = '';                
                document.getElementById('rcvfg_rtn_rc_txt_itemcode_qty').value = 0;
            }
            document.getElementById('rcvfg_rtn_rc_txt_itemcode_loc').focus();
        }
    }
    function rcvfg_rtn_rc_get_radio_val(){
        let radios = document.getElementsByName("rcvfg_rtn_rc_typefg");
        let selectedvalue = '';
        for(let i=0, lg = radios.length; i <lg; i++ ){
            if(radios[i].checked){
                selectedvalue = radios[i].value;
                break;
            }
        }
        return selectedvalue        
    }

    function rcvfg_rtn_rc_save_oth_eCK(){
        let mtbl = document.getElementById('rcvfg_rtn_rc_tblsub')
        let tableku2 = mtbl.getElementsByTagName("tbody")[0]
        let mtbltr = tableku2.getElementsByTagName('tr')
        let ttlrows = mtbltr.length;

        let mtbl_ = document.getElementById('rcvfg_rtn_rc_tblext')
        let tableku2_ = mtbl_.getElementsByTagName("tbody")[0]
        let mtbltr_ = tableku2_.getElementsByTagName('tr')
        
        //validate qty qa label vs JM
        let selected_qalabel = []
        let selected_qalabel_qty = 0
        let sampleassycode = ''
        let newrow, newcell;
        for(let i=0; i<ttlrows; i++){                        
            if(tableku2.rows[i].cells[0].classList.contains('anastylesel_rc')){                
                newrow = tableku2_.insertRow(-1)
                newcell = newrow.insertCell(0);
                newcell.oncontextmenu = function(event){
                    event.preventDefault();                                
                    document.getElementById('rcvfg_rtn_rc_g_id').value = event.target.parentElement.rowIndex;
                    rcvfg_rtn_context_menu_custlabel.open(event)
                    event.preventDefault()
                };
                newcell.innerHTML = tableku2.rows[i].cells[0].innerText
                newcell = newrow.insertCell(1)
                newcell.innerHTML = tableku2.rows[i].cells[0].innerText
                newcell = newrow.insertCell(2)
                newcell.innerHTML = tableku2.rows[i].cells[1].innerText
                newcell = newrow.insertCell(3)
                newcell.innerHTML = tableku2.rows[i].cells[2].innerText
                newcell = newrow.insertCell(4)
                newcell.classList.add('d-none')
            }
        }
    }

    function rcvfg_rtn_rc_txt_codeext_e_keypress(e){        
        if(e.which==13){
            let jmoption = rcvfg_rtn_rc_get_radio_val();         
            let mval = document.getElementById('rcvfg_rtn_rc_txt_codeext').value;
            document.getElementById('rcvfg_rtn_rc_txt_codeext').value = "";
            let therealcode = '';
            if(mval.indexOf("|")>-1){
                let aval = mval.split("|");
                if (aval.length>5){
                    therealcode = aval[5];
                    therealcode = therealcode.substr(2,therealcode.length);
                    let theqty = aval[3];
                    theqty = numeral(theqty.substr(2, theqty.length)).value();
                    let theassycode = aval[0].substr(2, aval[0].length);
                    //validate unsaved JM
                    let mtbl = document.getElementById('rcvfg_rtn_rc_tblsub');    
                    let tableku2 = mtbl.getElementsByTagName("tbody")[0];
                    let mtbltr = tableku2.getElementsByTagName('tr');
                    let ttlrows = mtbltr.length;                    

                    //validate qty qa label vs JM
                    let selected_qalabel = [];
                    let selected_qalabel_qty = 0;
                    let sampleassycode = '';
                    for(let i=0; i<ttlrows; i++){                        
                        if(tableku2.rows[i].cells[0].classList.contains('anastylesel_rc')){
                            sampleassycode = tableku2.rows[i].cells[1].innerText;
                            selected_qalabel.push(tableku2.rows[i].cells[0].innerText);
                            selected_qalabel_qty += numeral(tableku2.rows[i].cells[2].innerText).value();
                        }
                    }
                    if(sampleassycode!=theassycode){
                        if(!confirm(sampleassycode + " ==> "+theassycode +" are you sure ?")){
                            return;
                        }
                    }
                    let ttl_selected_qalabel = selected_qalabel.length;
                    let jmlist = [];
                    for(let i=0; i<ttlrows; i++){       
                        for(let k=0;k<ttl_selected_qalabel;k++){
                            if(tableku2.rows[i].cells[0].innerText==selected_qalabel[k]){
                                if(!jmlist.includes(tableku2.rows[i].cells[3].innerText)){
                                    jmlist.push(tableku2.rows[i].cells[3].innerText);
                                }
                            }
                        }
                    }
                   
                    if(jmoption=="JM"){
                        if(jmlist.length==theqty){    
                            mtbl = document.getElementById('rcvfg_rtn_rc_tblext');
                            tableku2 = mtbl.getElementsByTagName("tbody")[0];
                            mtbltr = tableku2.getElementsByTagName('tr'); 
                            ttlrows = mtbltr.length;
                            // validate if the ID is already plotted or not
                            if(ttlrows>0){
                                for(let i=0; i<ttlrows; i++){
                                    for(let k=0;k<ttl_selected_qalabel;k++){
                                        if(tableku2.rows[i].cells[1].innerText.trim() == selected_qalabel[i]){
                                            alertify.warning("the ID is already added");
                                            return;
                                        }
                                    }
                                }
                            }
    
                            // validate if the reff no is already plotted or no
                            if(ttlrows>0){
                                for(let i=0; i<ttlrows; i++){
                                    if(tableku2.rows[i].cells[0].innerText.trim() == therealcode){
                                        alertify.warning("the Reff No is already added");
                                        return;
                                    }
                                }
                            }
                            for(let i=0;i<ttl_selected_qalabel;i++){
                                newrow = tableku2.insertRow(-1);
                                newcell = newrow.insertCell(0);
                                newcell.oncontextmenu = function(event){
                                    event.preventDefault();                                
                                    document.getElementById('rcvfg_rtn_rc_g_id').value = event.target.parentElement.rowIndex;
                                    rcvfg_rtn_context_menu_custlabel.open(event)
                                    event.preventDefault()
                                };
                                newText = document.createTextNode(therealcode);
                                newcell.appendChild(newText);
                                newcell = newrow.insertCell(1);
                                newText = document.createTextNode(selected_qalabel[i]);
                                newcell.appendChild(newText);
                                newcell = newrow.insertCell(2);
                                newText = document.createTextNode(theassycode);
                                newcell.appendChild(newText);
                                newcell = newrow.insertCell(3);
                                newcell.classList.add('text-end');
                                newText = document.createTextNode(theqty);
                                newcell.appendChild(newText);
                                newcell = newrow.insertCell(4);
                                newcell.classList.add('d-none');
                                newText = document.createTextNode(mval);
                                newcell.appendChild(newText);
                            }
                        } else {                        
                            alertify.warning("QTY should be match !");
                        }
                    } else {
                        if(ttl_selected_qalabel<=0){
                            alertify.message("Please select ID first");
                            return;
                        }
                        let aEQALabel = [];
                        let EQALabelbal = 0;
                        for(let i=0; i<ttlrows; i++){    
                            if(tableku2.rows[i].cells[0].classList.contains('anastylesel_rc')){
                                let isfound = false;
                                for(let c in aEQALabel){
                                    if(aEQALabel[c].internalid = tableku2.rows[i].cells[0].innerText){
                                        isfound = true;
                                        break;
                                    }
                                }
                                if(!isfound){
                                    aEQALabel.push({internalid: tableku2.rows[i].cells[0].innerText
                                        , balqty: numeral(tableku2.rows[i].cells[2].innerText).value() });
                                }
                            }
                        }
                        

                        for(let c in aEQALabel){
                            EQALabelbal += aEQALabel[c].balqty;                            
                        }

                        if(EQALabelbal<theqty){
                            alertify.warning('Over...');
                            return;
                        }
                        
                        mtbl = document.getElementById('rcvfg_rtn_rc_tblext');
                        tableku2 = mtbl.getElementsByTagName("tbody")[0];
                        mtbltr = tableku2.getElementsByTagName('tr'); 
                        ttlrows = mtbltr.length;

                        for(let i=0; i<ttlrows; i++){
                            for(let k=0;k<ttl_selected_qalabel;k++){
                                if(tableku2.rows[i].cells[1].innerText.trim() == selected_qalabel[i]){
                                    alertify.warning("the ID is already added (#x54)");
                                    return;
                                }
                            }
                        }

                        for(let i=0; i<ttlrows; i++){                            
                            if(tableku2.rows[i].cells[0].innerText.trim() == therealcode){
                                alertify.warning("the reff no is already added..(#x55)");
                                return;
                            }
                        } 
                         
                        $.ajax({
                            type: "get",
                            url: "<?=base_url('SER/check_reg_extlabel')?>",
                            data: {inid:therealcode },
                            dataType: "json",
                            success: function (response) {
                                if(response.status[0].cd==1){
                                    for(let i=0;i<ttl_selected_qalabel;i++){
                                        if (rcvfg_rtn_rc_is_INTEXT_paired({internal: selected_qalabel[i], external: therealcode})){
                                            continue;
                                        }
                                        newrow = tableku2.insertRow(-1);
                                        newcell = newrow.insertCell(0);
                                        newcell.oncontextmenu = function(event){
                                            event.preventDefault();                                
                                            document.getElementById('rcvfg_rtn_rc_g_id').value = event.target.parentElement.rowIndex;
                                            rcvfg_rtn_context_menu_custlabel.open(event)
                                            event.preventDefault()
                                        };
                                        newText = document.createTextNode(therealcode);
                                        newcell.appendChild(newText);
                                        newcell = newrow.insertCell(1);
                                        newText = document.createTextNode(selected_qalabel[i]);
                                        newcell.appendChild(newText);
                                        newcell = newrow.insertCell(2);
                                        newText = document.createTextNode(theassycode);
                                        newcell.appendChild(newText);
                                        newcell = newrow.insertCell(3);
                                        newcell.classList.add('text-end');
                                        newText = document.createTextNode(theqty);
                                        newcell.appendChild(newText);
                                        newcell = newrow.insertCell(4);
                                        newcell.classList.add('d-none');
                                        newText = document.createTextNode(mval);
                                        newcell.appendChild(newText);
                                    }

                                      //clear style
                                    mtbl = document.getElementById('rcvfg_rtn_rc_tblsub');
                                    tableku2 = mtbl.getElementsByTagName("tbody")[0];
                                    mtbltr = tableku2.getElementsByTagName('tr'); 
                                    ttlrows = mtbltr.length; 
                                    for(let i=0;i<ttlrows;i++){
                                        tableku2.rows[i].cells[0].classList.remove('anastylesel_rc');                                   
                                    }
                                    document.getElementById('rcvfg_rtn_rc_txt_wjm_itemcode').value = "";
                                    document.getElementById('rcvfg_rtn_rc_txt_wjm_qty').value = "";
                                    document.getElementById('rcvfg_rtn_rc_wjm_lotno').value = "";
                                    document.getElementById('rcvfg_rtn_rc_txt_wjm_itemcode_loc').value = "";
                                    document.getElementById('rcvfg_rtn_rc_txt_code').focus();
                                } else {
                                    alertify.warning(response.status[0].msg);
                                }
                            }, error: function(xhr, xopt, xthrow){
                                alertify.error(xthrow);
                            }
                        });
                    }
                   
                } else {
                    alertify.warning("the serial is also not valid");
                }                    
            } else {
                alertify.warning("Please scan the QR Code");
            }
        }
    }
    function rcvfg_rtn_rc_e_click(e){          
        if(e.ctrlKey){   
            if(e.target.tagName.toLowerCase()=='td'){                
                if(e.target.cellIndex==0){
                    let mtbl = document.getElementById('rcvfg_rtn_rc_tblsub');
                    let tableku2 = mtbl.getElementsByTagName("tbody")[0];                        
                    let mtbltr = tableku2.getElementsByTagName('tr');
                    let ttlrows = mtbltr.length;
                    let beforeassy = document.getElementById('rcvfg_rtn_rc_g_assycd').value;        
                    if(e.target.classList.contains('anastylesel_rc')){
                        e.target.classList.remove('anastylesel_rc');
                        let isanyselected = '';                        
                        for(let i=0;i<ttlrows;i++){                            
                            if(tableku2.rows[i].cells[0].classList.contains("anastylesel_rc")){
                                isanyselected = tableku2.rows[i].cells[1].innerText;
                                break;
                            }
                        }
                        if(isanyselected==''){
                            //reset global string assy code
                            document.getElementById('rcvfg_rtn_rc_g_assycd').value = '';
                        }                        
                    } else {
                        let itemcd = e.target.innerText;                        
                        let mitem ='';
                        let mcnt_sel = 0;
                        let currentassy ='';   
                        let assybeingsel = tableku2.rows[e.target.parentElement.rowIndex-1].cells[1].innerText;                     
                        if(ttlrows>0){
                            for(let i=0;i<ttlrows;i++){
                                //get current selected assy code
                                if(tableku2.rows[i].cells[0].classList.contains("anastylesel_rc")){
                                    currentassy = tableku2.rows[i].cells[1].innerText;
                                    break;
                                }
                            }
                            if(currentassy!=''){                                
                                if(beforeassy!='' ){
                                    if(beforeassy!= assybeingsel){ //check assy being selected
                                        alertify.warning("Different assy code could not be joined");
                                    } else {
                                        e.target.classList.add('anastylesel_rc');
                                        document.getElementById('rcvfg_rtn_rc_g_assycd').value = currentassy;
                                    }
                                } else {
                                    if(beforeassy == currentassy){
                                        e.target.classList.add('anastylesel_rc');
                                        document.getElementById('rcvfg_rtn_rc_g_assycd').value = currentassy;
                                    }
                                }
                            } else {
                                e.target.classList.add('anastylesel_rc');
                                document.getElementById('rcvfg_rtn_rc_g_assycd').value = tableku2.rows[e.target.parentElement.rowIndex-1].cells[1].innerText;
                            }
                        }                                                
                        document.getElementById('rcvfg_rtn_rc_txt_codeext').focus();                        
                    }                   
                }
            }
        }        
    }
    function rcvfg_rtn_rc_btn_print_e_click(){
        let mtbl = document.getElementById('rcvfg_rtn_rc_tblsub');    
        let tableku2 = mtbl.getElementsByTagName("tbody")[0];
        let mtbltr = tableku2.getElementsByTagName('tr');
        let ttlrows = mtbltr.length;
        for(let i=0; i<ttlrows; i++){
            if(tableku2.rows[i].cells[8].innerText.trim().length==0){
                alertify.warning("Please save first");
                return;
            }
        }
        let doc = document.getElementById('rcvfg_rtn_rc_txt_serahterima').value;
        Cookies.set('PRINTDOC_DOCNO', doc , {expires:365});
        window.open("<?=base_url('print_serahterima_rcqc')?>",'_blank');
    }

    function rcvfg_rtn_rc_txt_search_serahterima_e_keypress(e){
        if(e.which==13){
            let doc = document.getElementById('rcvfg_rtn_rc_txt_search_serahterima').value;
            let doctype = document.getElementById('rcvfg_rtn_rc_cmb_searchby').value;
            $("#rcvfg_rtn_rc_tbl_serahterima tbody").empty();
            document.getElementById('rcvfg_rtn_rc_tbl_serahterima_lblinfo').innerText = "Please wait...";
            $.ajax({
                type: "get",
                url: "<?=base_url('SER/searchdok_rcqc')?>",
                data: {indoc: doc, indoctype: doctype},
                dataType: "json",
                success: function (response) {
                    let ttlrows = response.data.length;
                    document.getElementById('rcvfg_rtn_rc_tbl_serahterima_lblinfo').innerText = ttlrows + " row(s) found";
                    if(ttlrows>0){
                        let mydes = document.getElementById("rcvfg_rtn_rc_divku_search_serahterima");
                        let myfrag = document.createDocumentFragment();
                        let mtabel = document.getElementById("rcvfg_rtn_rc_tbl_serahterima");
                        let cln = mtabel.cloneNode(true);
                        myfrag.appendChild(cln);
                        let tabell = myfrag.getElementById("rcvfg_rtn_rc_tbl_serahterima");                    
                        let tableku2 = tabell.getElementsByTagName("tbody")[0];
                        let newrow, newcell, newText;
                        tableku2.innerHTML='';
                        for (let i = 0; i<ttlrows; i++){
                            newrow = tableku2.insertRow(-1);
                            newrow.onclick =  function(){
                                rcvfg_rtn_rc_e_getsaved_byst(response.data[i].SERRC_DOCST); 
                                $("#rcvfg_rtn_rc_MOD_serahterima").modal('hide');
                                document.getElementById('rcvfg_rtn_rc_txt_serahterima').value= response.data[i].SERRC_DOCST;
                                $("#rcvfg_rtn_rc_txt_date").datepicker('update', response.data[i].DT );
                             };
                            newcell = newrow.insertCell(0);                            
                            newText = document.createTextNode(response.data[i].SERRC_DOCST);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(1);
                            newText = document.createTextNode(response.data[i].DT);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(2);
                            newText = document.createTextNode(response.data[i].RADOC);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(3);
                            newText = document.createTextNode(response.data[i].FNM);
                            newcell.appendChild(newText);
                        }
                        mydes.innerHTML='';
                        mydes.appendChild(myfrag);
                    }
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    }
    $("#rcvfg_rtn_rc_MOD_serahterima").on('shown.bs.modal', function(){
        $("#rcvfg_rtn_rc_txt_search_serahterima").focus();
    });
    $("#rcvfg_rtn_rc_txt_date").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });
    $("#rcvfg_rtn_rc_txt_date").datepicker('update', new Date());
    function rcvfg_rtn_rc_btn_new_e_click(){
        rcvfg_rtn_rc_a_rmcode = [];
        let txtlblqa = document.getElementById('rcvfg_rtn_rc_txt_code');
        txtlblqa.value="";
        txtlblqa.readOnly = false;
        txtlblqa.focus();
        document.getElementById('rcvfg_rtn_rc_txt_assyno').value= "";
        document.getElementById('rcvfg_rtn_rc_txt_assynm').value= "";
        document.getElementById('rcvfg_rtn_rc_txt_assyqty').value= "";
        document.getElementById('rcvfg_rtn_rc_txt_docno').value= "";
        document.getElementById('rcvfg_rtn_rc_txt_serahterima').value= "";
        document.getElementById('rcvfg_rtn_rc_g_assycd').value= "";
        document.getElementById('rcvfg_rtn_rc_txt_jmcode').value= "";
        document.getElementById('rcvfg_rtn_rc_txt_itemcode').value= "";
        document.getElementById('rcvfg_rtn_rc_txt_itemcode_qty').value= "";
        document.getElementById('rcvfg_rtn_rc_txt_itemcode_loc').value= "";
        $("#rcvfg_rtn_rc_tblsub tbody").empty();
        $("#rcvfg_rtn_rc_tblext tbody").empty();        
    }

    function rcvfg_rtn_rc_btn_save_e_click(){
        let jmopt = rcvfg_rtn_rc_get_radio_val();
        let qalabel = document.getElementById('rcvfg_rtn_rc_txt_code').value;        
        let mtbl = document.getElementById('rcvfg_rtn_rc_tblsub');    
        let tableku2 = mtbl.getElementsByTagName("tbody")[0];
        let mtbltr = tableku2.getElementsByTagName('tr');
        let ttlrows = mtbltr.length;
        let aid = [];
        let ajm = [];
        let aitem = [];
        let aqty = [];
        let alotno = [];
        let aloc = [];
        let aline = [];
        let aext_reffno = [];
        let aext_id = [];
        let aext_itemcode = [];
        let aext_qty = [];
        let aext_rawtxt = [];
        let dok = document.getElementById('rcvfg_rtn_rc_txt_serahterima').value;
        let thedate = document.getElementById('rcvfg_rtn_rc_txt_date').value;

        if(ttlrows==0){
            alertify.message('nothing to be saved');
            return;
        }

        for(let i=0; i<ttlrows; i++){
            aid.push(tableku2.rows[i].cells[0].innerText);
            ajm.push(tableku2.rows[i].cells[3].innerText);
            aitem.push(tableku2.rows[i].cells[4].innerText);
            aqty.push(isNaN(tableku2.rows[i].cells[6].innerText) ? 0 : numeral(tableku2.rows[i].cells[6].innerText).value());
            aloc.push(tableku2.rows[i].cells[7].innerText.trim().replace(/[\r\n]+/gm, ""));
            aline.push(tableku2.rows[i].cells[8].innerText);
            alotno.push(tableku2.rows[i].cells[9].innerText);
        }

        mtbl = document.getElementById('rcvfg_rtn_rc_tblext');    
        tableku2 = mtbl.getElementsByTagName("tbody")[0];
        mtbltr = tableku2.getElementsByTagName('tr');
        ttlrows = mtbltr.length;

        for(let i=0; i<ttlrows; i++){
            aext_reffno.push(tableku2.rows[i].cells[0].innerText);
            aext_id.push(tableku2.rows[i].cells[1].innerText);
            aext_itemcode.push(tableku2.rows[i].cells[2].innerText);
            aext_qty.push(tableku2.rows[i].cells[3].innerText);
            aext_rawtxt.push(tableku2.rows[i].cells[4].innerText);
        }

        if(confirm("Are You sure ?")){
            $.ajax({
                type: "post",
                url: "<?=base_url('SER/set_rc_bom')?>",
                data: {qalabel: aid, jmcode: ajm, bompn: aitem, loc: aloc, line: aline, indate: thedate
                , indok: dok, inext_reffno: aext_reffno, inext_id: aext_id, qty: aqty
                ,inext_itemcode: aext_itemcode, inext_qty: aext_qty, inext_rawtxt: aext_rawtxt
                ,lotno: alotno,jmopt : jmopt },
                dataType: "json",
                success: function (response) {
                    if(response.status[0].cd=='1'){
                        alertify.success(response.status[0].msg);                        
                        document.getElementById('rcvfg_rtn_rc_txt_serahterima').value = response.status[0].reff;
                        rcvfg_rtn_rc_e_getsaved_byst(response.status[0].reff);
                        document.getElementById('rcvfg_rtn_rc_txt_code').value='';
                        document.getElementById('rcvfg_rtn_rc_txt_code').readOnly = false;
                        document.getElementById('rcvfg_rtn_rc_txt_assyno').value='';
                        document.getElementById('rcvfg_rtn_rc_txt_assynm').value='';
                        document.getElementById('rcvfg_rtn_rc_txt_assyqty').value='';
                        document.getElementById('rcvfg_rtn_rc_txt_docno').value='';
                    } else {
                        alertify.message(response.status[0].msg);
                    }
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    } 

    function rcvfg_rtn_rc_e_getsaved_byst(pdoc){        
        $.ajax({
            type: "get",
            url: "<?=base_url('SER/get_rm_rc_by_st')?>",
            data: {doc : pdoc},
            dataType: "json",
            success: function (response) {
                let ttlrows = response.data.length;
                let mydes = document.getElementById("rcvfg_rtn_rc_divsubku");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("rcvfg_rtn_rc_tblsub");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("rcvfg_rtn_rc_tblsub");                    
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                let newrow, newcell, newText;
                tableku2.innerHTML='';
                for(let i=0; i<ttlrows; i++){
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0);
                    newcell.oncontextmenu = function(event){
                        event.preventDefault();                                
                        document.getElementById('rcvfg_rtn_rc_g_id').value = event.target.parentElement.rowIndex;
                        rcvfg_rtn_context_menu.open(event)
                        event.preventDefault()
                    };
                    newText = document.createTextNode(response.data[i].SERRC_SER);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(1);
                    newcell.title = response.data[i].ASSYD1;
                    newText = document.createTextNode(response.data[i].SER_ITMID);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(2);
                    newcell.classList.add("text-end");
                    newText = document.createTextNode(numeral(response.data[i].SER_QTY).value());
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(3);
                    newcell.oncontextmenu = function(event){
                        event.preventDefault();                        
                        document.getElementById('rcvfg_rtn_rc_g_id').value = event.target.innerText;
                        rcvfg_rtn_context_menu_lot.open(event)
                        event.preventDefault()
                    };
                    newcell.classList.add("fw-bold", "font-monospace");
                    newText = document.createTextNode(response.data[i].SERRC_JM);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(4);
                    newcell.contentEditable = true;
                    newText = document.createTextNode(response.data[i].SERRC_BOMPN);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(5);
                    newText = document.createTextNode(response.data[i].MITM_ITMD1);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(6);                    
                    newcell.classList.add("text-end");
                    newcell.contentEditable = true;
                    newText = document.createTextNode(response.data[i].SERRC_BOMPNQTY);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(7);
                    newcell.contentEditable = true;
                    newText = document.createTextNode(response.data[i].SERRC_LOC);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(8);
                    newcell.classList.add('d-none');
                    newText = document.createTextNode(response.data[i].SERRC_LINE);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(9);
                    newcell.classList.add('d-none');
                    newText = document.createTextNode(response.data[i].SERRC_LOTNO);
                    newcell.appendChild(newText);
                }
                mydes.innerHTML='';
                mydes.appendChild(myfrag);

                ttlrows = response.data_ex.length;
                mydes = document.getElementById("rcvfg_rtn_rc_divext");
                myfrag = document.createDocumentFragment();
                mtabel = document.getElementById("rcvfg_rtn_rc_tblext");
                cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);                    
                tabell = myfrag.getElementById("rcvfg_rtn_rc_tblext");                    
                tableku2 = tabell.getElementsByTagName("tbody")[0];                
                tableku2.innerHTML='';
                for(let i=0; i<ttlrows; i++){
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0);
                    newcell.oncontextmenu = function(event){
                        event.preventDefault();                                
                        document.getElementById('rcvfg_rtn_rc_g_id').value = event.target.parentElement.rowIndex;
                        rcvfg_rtn_context_menu_custlabel.open(event)
                        event.preventDefault()
                    };
                    newText = document.createTextNode(response.data_ex[i].SERRC_SERX);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(1);
                    newText = document.createTextNode(response.data_ex[i].SERRC_SER);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(2);
                    newText = document.createTextNode(response.data_ex[i].SERRC_NASSYCD);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(3);
                    newcell.classList.add('text-end');
                    newText = document.createTextNode(response.data_ex[i].SERRC_SERXQTY);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(4);
                    newcell.classList.add('d-none');
                    newText = document.createTextNode(response.data_ex[i].SERRC_SERXRAWTXT);
                    newcell.appendChild(newText);
                }
                mydes.innerHTML='';
                mydes.appendChild(myfrag);
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    }

    function rcvfg_rtn_rc_txt_itemcode_loc_e_keypress(e){
        if(e.which==13){            
            $('#rcvfg_rtn_rc_btn_add').focus();
        }
    }
   
    function rcvfg_rtn_rc_txt_jmcode_e_keypress(e){
        if(e.which==13){
            let jmcode = document.getElementById("rcvfg_rtn_rc_txt_jmcode");
            if(jmcode.value.trim().length==9){
                document.getElementById('rcvfg_rtn_rc_txt_itemcode').focus();
            } else {
                alertify.warning("JMCode is not valid");
            }
        }
    }

    function rcvfg_rtn_rc_e_checkunique_nojm(pqalabel){
        let maxqty =  numeral(document.getElementById('rcvfg_rtn_rc_txt_assyqty').value).value();
        let mtbl = document.getElementById('rcvfg_rtn_rc_tblsub');
        let tableku2 = mtbl.getElementsByTagName("tbody")[0];
        let mtbltr = tableku2.getElementsByTagName('tr');
        let ttlrows = mtbltr.length;
        let isok = true;
        let ajm = [];
        for(let i=0;i<ttlrows;i++){
            if(tableku2.rows[i].cells[0].innerText == pqalabel) {
                isok = false;
                break;
            }
        }

        return isok;
    }
    function rcvfg_rtn_rc_e_checkunique_nojm_onadd(pdata){
        let maxqty =  numeral(document.getElementById('rcvfg_rtn_rc_txt_assyqty').value).value();
        let mtbl = document.getElementById('rcvfg_rtn_rc_tblsub');
        let tableku2 = mtbl.getElementsByTagName("tbody")[0];
        let mtbltr = tableku2.getElementsByTagName('tr');
        let ttlrows = mtbltr.length;        
        let aEItem = [];
        let shouldContinue = true;
        //if Item Code is not valid or empty
        if(pdata.bompn.length<=5){
            for(let i=0;i<ttlrows;i++){
                if(tableku2.rows[i].cells[0].innerText == pdata.qalabel) {
                    shouldContinue = false;
                    break;
                }
            }
        } else {
            if(ttlrows>0 && tableku2.rows[0].cells[4].innerText.length==0){
                shouldContinue = false;
            } else {
                for(let i=0;i<ttlrows;i++){
                    if(tableku2.rows[i].cells[0].innerText == pdata.qalabel) {
                        let isFound = false;
                        for(let c in aEItem) {
                            if(aEItem[c].itemcode==tableku2.rows[i].cells[4].innerText){
                                aEItem[c].qty += numeral(tableku2.rows[i].cells[6].innerText).value()
                                isFound = true; break;
                            }
                        }
                        if(!isFound){
                            aEItem.push({
                                itemcode : tableku2.rows[i].cells[4].innerText,
                                qty : numeral(tableku2.rows[i].cells[6].innerText).value()
                            });
                        }
                    }
                }
                
                for(let c in aEItem) {
                    if(aEItem[c].itemcode==pdata.bompn && (aEItem[c].qty+pdata.rmqty)>pdata.assyqty ){
                    shouldContinue = false; break;
                    }
                }                
            }            
        }        
        return shouldContinue;
    }

    function rcvfg_rtn_rc_e_checkuniquejm(pkeys){
        let maxqty =  numeral(document.getElementById('rcvfg_rtn_rc_txt_assyqty').value).value();
        let mtbl = document.getElementById('rcvfg_rtn_rc_tblsub');
        let tableku2 = mtbl.getElementsByTagName("tbody")[0];
        let mtbltr = tableku2.getElementsByTagName('tr');
        let ttlrows = mtbltr.length;
        let isok = true;
        let ajm = [];
        for(let i=0;i<ttlrows;i++){
            if(tableku2.rows[i].cells[0].innerText == pkeys.p2) {
                let adata = tableku2.rows[i].cells[3].innerText;
                if(!ajm.includes(adata)){
                    ajm.push(adata);
                }
            }
        }
        if(ajm.length>=maxqty){
            if(!ajm.includes(pkeys.p1)){
                isok = false;
            }            
        }
        return isok;
    }

    function rcvfg_rtn_addrows(ptable, pdata){
        let newrow, newcell, newText;
        newrow = ptable.insertRow(-1);
        newcell = newrow.insertCell(0);
        newcell.oncontextmenu = function(event){
            event.preventDefault();                                
            document.getElementById('rcvfg_rtn_rc_g_id').value = event.target.parentElement.rowIndex;
            rcvfg_rtn_context_menu.open(event)
            event.preventDefault()
        };
        newText = document.createTextNode(pdata.qalabel);
        newcell.appendChild(newText);
        newcell = newrow.insertCell(1);
        newText = document.createTextNode(pdata.assycode);
        newcell.appendChild(newText);
        newcell = newrow.insertCell(2);
        newcell.classList.add("text-end");
        newText = document.createTextNode(pdata.assyqty);
        newcell.appendChild(newText);
        newcell = newrow.insertCell(3);
        newcell.classList.add("fw-bold", "font-monospace");
        newText = document.createTextNode(pdata.jmcode.toUpperCase());
        newcell.appendChild(newText);
        newcell = newrow.insertCell(4);
        newText = document.createTextNode(pdata.bompn);
        newcell.appendChild(newText);
        newcell = newrow.insertCell(5);
        newText = document.createTextNode(pdata.bompn_nm);
        newcell.appendChild(newText);
        newcell = newrow.insertCell(6);                
        newcell.classList.add("text-end");
        newText = document.createTextNode(pdata.rmqty);
        newcell.appendChild(newText);
        newcell = newrow.insertCell(7);
        newcell.contentEditable = true;
        newText = document.createTextNode(pdata.bompn_loc.toUpperCase());
        newcell.appendChild(newText);
        newcell = newrow.insertCell(8);
        newcell.classList.add("d-none");
        newText = document.createTextNode("");
        newcell.appendChild(newText);
        newcell = newrow.insertCell(9);
        newcell.classList.add("d-none");
        newText = document.createTextNode(pdata.lotno);
        newcell.appendChild(newText);
    }
    function rcvfg_rtn_rc_btn_add_e_click(){
        
        let docno = document.getElementById('rcvfg_rtn_rc_txt_serahterima').value;
        let qalabel = document.getElementById('rcvfg_rtn_rc_txt_code');
        if(qalabel.readOnly && qalabel.value.trim().length>3){
            let jmcode = document.getElementById("rcvfg_rtn_rc_txt_jmcode");
            let bompn = document.getElementById('rcvfg_rtn_rc_txt_itemcode').value;
            let bompn_nm = document.getElementById("rcvfg_rtn_rc_txt_itemname").value;            
            let bompn_loc = document.getElementById("rcvfg_rtn_rc_txt_itemcode_loc");
            let assycode = document.getElementById('rcvfg_rtn_rc_txt_assyno').value;
            let assyqty = document.getElementById('rcvfg_rtn_rc_txt_assyqty').value;
            let rmqty = numeral(document.getElementById('rcvfg_rtn_rc_txt_itemcode_qty').value).value();
            let lotno = document.getElementById('rcvfg_rtn_rc_g_lotno').value;
            if (jmcode.value.trim().length!=9){
                alertify.message('JM Code is not valid');
                jmcode.focus();
                return;
            }
            if(rmqty<=0 || !rmqty ){
                if(bompn.trim().length>0){
                    alertify.warning("QTY is required");
                    document.getElementById('rcvfg_rtn_rc_txt_itemcode_qty').focus();
                    return;
                }
            } else {
                //qty exist
                if(bompn.trim().length>0){

                } else {
                    alertify.warning("Part Code is required");
                    return;
                }
            }           
            if(!rcvfg_rtn_rc_e_checktempkeys({p1: jmcode.value.toUpperCase(), p2: bompn_loc.value.toUpperCase(), p3: qalabel.value.toUpperCase()})){
                alertify.warning("a location is only for 1 item code");
                return;
            }
            if(!rcvfg_rtn_rc_e_checkuniquejm({p1: jmcode.value.toUpperCase(), p2 : qalabel.value.toUpperCase()})){
                alertify.warning("OVER");
                return;
            }
            document.getElementById('rcvfg_rtn_rc_btn_add').disabled =true;
            //server side checking            
            $.ajax({
                type: "get",
                url: "<?=base_url('SER/checkbalanceqalabel')?>",
                data: {inqalabel: qalabel.value, indoc_st: docno },
                dataType: "json",
                success: function (response) {
                    let maxqty =  numeral(document.getElementById('rcvfg_rtn_rc_txt_assyqty').value).value();
                    let ttlrows = response.data.length;                    
                    let qalabel_qty = numeral(document.getElementById('rcvfg_rtn_rc_txt_assyqty').value).value();
                    let mtbl = document.getElementById('rcvfg_rtn_rc_tblsub');
                    let tableku2 = mtbl.getElementsByTagName("tbody")[0];
                    if(ttlrows < qalabel_qty){
                        //validate temp vs saved on database
                        let mtbltr = tableku2.getElementsByTagName('tr');
                        let ttltemprows = mtbltr.length;
                        let ajm = [];
                        for(let i=0;i<ttltemprows;i++){
                            if(tableku2.rows[i].cells[0].innerText == qalabel.value) {
                                let adata = tableku2.rows[i].cells[3].innerText;
                                if(!ajm.includes(adata)){
                                    ajm.push(adata);
                                }
                            }
                        }
                        let isok = true;
                        if((ajm.length+ttlrows)>=maxqty){
                            if(!ajm.includes(jmcode.value.toUpperCase())){
                                isok = false;
                            }            
                        }
                        if(isok){
                            rcvfg_rtn_addrows(tableku2, {qalabel: qalabel.value
                                , assycode: assycode
                                , assyqty: assyqty
                                , jmcode: jmcode.value
                                , bompn: bompn
                                , bompn_nm: bompn_nm
                                , rmqty: rmqty
                                , bompn_loc: bompn_loc.value
                                , lotno: lotno
                            });
                            jmcode.value = "";
                            bompn_loc.value = "";
                            jmcode.focus();                            
                        } else {
                            alertify.warning("OVER..");
                        }
                    } else {
                        let isfound = false;
                        for(let i=0;i<ttlrows; i++){
                            if(response.data[i].SERRC_JM == jmcode.value){
                                isfound = true;
                                break;
                            }
                        }
                        if(isfound){    
                            rcvfg_rtn_addrows(tableku2, {qalabel: qalabel.value
                                , assycode: assycode
                                , assyqty: assyqty
                                , jmcode: jmcode.value
                                , bompn: bompn
                                , bompn_nm: bompn_nm
                                , rmqty: rmqty
                                , bompn_loc: bompn_loc.value
                                , lotno: lotno
                            });
                            jmcode.value = "";
                            bompn_loc.value = "";                
                            jmcode.focus();
                        } else {
                            alertify.warning("OVER...");
                        }
                    }
                    document.getElementById('rcvfg_rtn_rc_txt_itemcode').value ="";
                    document.getElementById('rcvfg_rtn_rc_txt_itemcode_qty').value ="0";
                    document.getElementById('rcvfg_rtn_rc_g_lotno').value ="";
                    document.getElementById('rcvfg_rtn_rc_btn_add').disabled = false;
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                    document.getElementById('rcvfg_rtn_rc_btn_add').disabled = false;
                }
            });            
        } else {
            qalabel.focus();
            alertify.warning("Please scan status label first !");
        }
        document.getElementById('rcvfg_rtn_rc_txt_itemname').value = "";
    }

    function rcvfg_rtn_rc_e_cancelitem(){
        if(!confirm("Are You sure ?")){
            return;
        }
        let mid = document.getElementById('rcvfg_rtn_rc_g_id').value;     
        let thetable = document.getElementById('rcvfg_rtn_rc_tblsub');           
        let line = thetable.rows[mid].cells[8].innerText;
        let qalabel = thetable.rows[mid].cells[0].innerText;
        let docno = document.getElementById('rcvfg_rtn_rc_txt_serahterima').value;
        
        if(line!=''){            
            $.ajax({
                type: "post",
                url: "<?=base_url('SER/remove_rm_rc')?>",
                data: {qalabel: qalabel, line: line, docno: docno},
                dataType: "json",
                success: function (response) {
                    if(response.status[0].cd==1){
                        alertify.success(response.status[0].msg);
                        thetable.deleteRow(mid);
                        if(thetable.rows.length==1){
                            document.getElementById('rcvfg_rtn_rc_txt_serahterima').value ="";
                        }
                    } else {
                        alertify.message(response.status[0].msg);
                    }
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        } else {
            let mtbl = document.getElementById('rcvfg_rtn_rc_tblext');
            let tableku2 = mtbl.getElementsByTagName("tbody")[0];
            let mtbltr = tableku2.getElementsByTagName('tr'); 
            ttlrows = mtbltr.length;
            // validate if the ID is already plotted or not
            if(ttlrows>0){
                for(let i=0; i<ttlrows; i++){                    
                    if(tableku2.rows[i].cells[1].innerText.trim() == qalabel){
                        alertify.warning("Please remove external label first");
                        return;
                    }                    
                }
            }
            thetable.deleteRow(mid);
        }  
    }

    function rcvfg_rtn_rc_e_cancelitem_custlabel(){
        let mid = document.getElementById('rcvfg_rtn_rc_g_id').value;     
        let thetable = document.getElementById('rcvfg_rtn_rc_tblext');           
        let extlabel = thetable.rows[mid].cells[0].innerText;
        let intlabel = thetable.rows[mid].cells[1].innerText;                
        if(confirm("Are you sure want to cancel ?")){
            $.ajax({
                type: "get",
                url: "<?=base_url('SER/cancel_confirmed_rc')?>",
                data: {inext_label: extlabel, inint_label: intlabel},
                dataType: "json",
                success: function (response) {
                    alertify.message(response.status[0].msg);
                    thetable.deleteRow(mid);
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    }

    function rcvfg_rtn_rc_e_checktempkeys(pkeys){
        let mtbl = document.getElementById('rcvfg_rtn_rc_tblsub');
        let tableku2 = mtbl.getElementsByTagName("tbody")[0];
        let mtbltr = tableku2.getElementsByTagName('tr');
        let ttlrows = mtbltr.length;
        let isok = true;
        for(let i=0;i<ttlrows;i++){            
            let posi = tableku2.rows[i].cells[7].innerText.replace(/[\r\n]+/gm, "");            
            if(tableku2.rows[i].cells[3].innerText==pkeys.p1 && posi==pkeys.p2){
                isok = false; break;
            }
        }
        return isok;
    }

    function rcvfg_rtn_rc_btn_wjm_add_e_click(){
        let midsub = document.getElementById('rcvfg_rtn_rc_txt_code').value.trim();
        document.getElementById('rcvfg_rtn_rc_txt_code').value= "";
        let docst = document.getElementById('rcvfg_rtn_rc_txt_serahterima').value;
        let assyno = document.getElementById('rcvfg_rtn_rc_txt_assyno').value;
        if (assyno.trim().length==0){
            document.getElementById('rcvfg_rtn_rc_txt_code').focus();
            alertify.warning("Press enter first");
            return;
        }
        if(midsub.length>9){
            let therealcode = '';
            if(midsub.indexOf("|")>-1){
                let aid = midsub.split("|");
                if (aid.length>5){
                    therealcode = aid[5];
                    therealcode = therealcode.substr(2,therealcode.length);                        
                } else {
                    alertify.warning('the data is not valid,');                        
                    return;
                }
            } else {
                therealcode = midsub;
            }                
            if(therealcode.substr(0,1)!='4'){
                alertify.warning('it was not valid ID');
                return;
            }
            let bompn = document.getElementById("rcvfg_rtn_rc_txt_wjm_itemcode").value;
            let bomqty = numeral(document.getElementById("rcvfg_rtn_rc_txt_wjm_qty").value).value();
            let bomlot = document.getElementById("rcvfg_rtn_rc_wjm_lotno").value;
            let bomloc = document.getElementById("rcvfg_rtn_rc_txt_wjm_itemcode_loc").value;
            $.ajax({
                type: "get",
                url: "<?=base_url('SER/checkbalanceqalabel_wjm')?>",
                data: {inqalabel: therealcode, indoc_st: docst },
                dataType: "json",
                success: function (response) {
                    if(response.dataser.length>0){
                        let newrowobj = {qalabel: therealcode
                                    , assycode: (response.data.length >0 ? response.data[0].SER_ITMID : response.dataser[0].SER_ITMID)
                                    , assyqty: (response.data.length>0 ? numeral(response.data[0].BALQTY).value() : numeral(response.dataser[0].SER_QTY).value())
                                    , jmcode: ''
                                    , bompn: bompn
                                    , bompn_nm: ''
                                    , rmqty: bomqty
                                    , bompn_loc: bomloc
                                    , lotno: bomlot};
                        let mtbl = document.getElementById('rcvfg_rtn_rc_tblsub');
                        let tableku2 = mtbl.getElementsByTagName("tbody")[0];
                        if(response.data.length>0){
                            if(numeral(response.data[0].BALQTY).value() == 0 ){
                                alertify.warning("Balance is zero");
                                return;
                            }
                            document.getElementById('rcvfg_rtn_rc_txt_code').readOnly = false;
                            document.getElementById('rcvfg_rtn_rc_txt_wjm_itemcode').focus();
                            //handle without JM QA Label
                                                                            
                            if(!rcvfg_rtn_rc_e_checkunique_nojm_onadd(newrowobj)){
                                alertify.warning("OVER (#x01)");
                                return;
                            }
                            document.getElementById('rcvfg_rtn_rc_txt_codeext').focus();
                            rcvfg_rtn_addrows(tableku2,newrowobj);                                                                                                         
                        } else {
                            rcvfg_rtn_addrows(tableku2,newrowobj);
                        }
                        document.getElementById('rcvfg_rtn_rc_txt_code').readOnly = false;
                        document.getElementById('rcvfg_rtn_rc_txt_codeext').focus();
                        document.getElementById('rcvfg_rtn_rc_txt_code').value = "";
                        document.getElementById('rcvfg_rtn_rc_txt_assyno').value = "";
                        document.getElementById('rcvfg_rtn_rc_txt_assynm').value = "";
                        document.getElementById('rcvfg_rtn_rc_txt_assyqty').value = "";
                        document.getElementById('rcvfg_rtn_rc_txt_docno').value = "";

                        document.getElementById('rcvfg_rtn_rc_txt_wjm_itemcode').value = "";
                        document.getElementById('rcvfg_rtn_rc_txt_wjm_qty').value = "";
                        document.getElementById('rcvfg_rtn_rc_wjm_lotno').value = "";
                        document.getElementById('rcvfg_rtn_rc_txt_wjm_itemcode_loc').value = "";
                        document.getElementById('rcvfg_rtn_rc_txt_code').focus(); 
                    } else {
                        alertify.warning("ID is not found");
                    }                    
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    }    

    function rcvfg_rtn_rc_txt_code_e_keypress(e){
        if(e.which==13){
            let midsub = document.getElementById('rcvfg_rtn_rc_txt_code').value.trim();
            document.getElementById('rcvfg_rtn_rc_txt_code').value= "";
            let docst = document.getElementById('rcvfg_rtn_rc_txt_serahterima').value;
            if(midsub.length>9){
                let therealcode = '';
                if(midsub.indexOf("|")>-1){
                    let aid = midsub.split("|");                    
                    if (aid.length>5){
                        therealcode = aid[5];
                        therealcode = therealcode.substr(2,therealcode.length);                        
                    } else {
                        alertify.warning('the data is not valid,');                        
                        return;
                    }
                } else {
                    therealcode = midsub;
                }                
                if(therealcode.substr(0,1)!='4'){
                    alertify.warning('it was not valid ID');
                    return;
                }
                $.ajax({
                    type: "get",
                    url: "<?=base_url('SER/get_fgser_rtn')?>",
                    data: {inid: therealcode, indocst: docst },
                    dataType: "json",
                    success: function (response) {
                        if(response.status[0].cd==1){
                            document.getElementById('rcvfg_rtn_rc_txt_code').readOnly = true;
                            document.getElementById('rcvfg_rtn_rc_txt_code').value = therealcode;                            
                            document.getElementById('rcvfg_rtn_rc_txt_assyno').value = response.data[0].SER_ITMID;
                            document.getElementById('rcvfg_rtn_rc_txt_assynm').value = response.data[0].MITM_ITMD1;
                            document.getElementById('rcvfg_rtn_rc_txt_assyqty').value = numeral(response.data[0].SER_QTY).value();
                            document.getElementById('rcvfg_rtn_rc_txt_docno').value = response.data[0].SER_DOC;
                            if(response.data_bal.length>0){
                                if(response.data_bal[0].BALQTY>0){                                    
                                    rcvfg_rtn_rc_e_getRM();   
                                    if(rcvfg_rtn_rc_get_radio_val()=="JM"){
                                        document.getElementById('rcvfg_rtn_rc_txt_code').readOnly = true;
                                        document.getElementById('rcvfg_rtn_rc_txt_jmcode').focus();
                                    } else {
                                        //handle without JM QA Label
                                        document.getElementById('rcvfg_rtn_rc_txt_wjm_itemcode').focus();
                                    }                                                            
                                } else {
                                    alertify.message('it is enough');
                                    document.getElementById('rcvfg_rtn_rc_txt_code').readOnly = false;
                                    document.getElementById('rcvfg_rtn_rc_txt_assyno').value = "";
                                    document.getElementById('rcvfg_rtn_rc_txt_assynm').value = "";
                                    document.getElementById('rcvfg_rtn_rc_txt_assyqty').value = "";
                                    document.getElementById('rcvfg_rtn_rc_txt_docno').value = "";
                                    document.getElementById('rcvfg_rtn_rc_txt_code').value= ""; 
                                }
                            } else {                                
                                rcvfg_rtn_rc_e_getRM();   
                                if(rcvfg_rtn_rc_get_radio_val()=="JM"){
                                    document.getElementById('rcvfg_rtn_rc_txt_code').readOnly = true;
                                    document.getElementById('rcvfg_rtn_rc_txt_jmcode').focus();
                                } else {
                                    //handle without JM QA Label
                                    document.getElementById('rcvfg_rtn_rc_txt_wjm_itemcode').focus();
                                }
                            }
                        } else {
                            alertify.warning(response.status[0].msg);
                            document.getElementById('rcvfg_rtn_rc_txt_assyno').value = "";
                            document.getElementById('rcvfg_rtn_rc_txt_assynm').value = "";
                            document.getElementById('rcvfg_rtn_rc_txt_assyqty').value = "";
                            document.getElementById('rcvfg_rtn_rc_txt_docno').value = "";
                            $("#rcvfg_rtn_rc_tblsub tbody").empty();
                        }
                    }, error: function(xhr, xopt, xthrow){
                        alertify.error(xthrow);
                    }
                });
            }
        }
    }
    function rcvfg_rtn_rc_e_getRM(){
        let assyno = document.getElementById('rcvfg_rtn_rc_txt_assyno').value;
        $.ajax({
            type: "post",
            url: "<?=base_url('SER/get_rm_eu')?>",
            data: {initem: assyno },
            dataType: "json",
            success: function (response) {
                if(response.length>0){
                    rcvfg_rtn_rc_a_rmcode = response;
                } else {
                    rcvfg_rtn_rc_a_rmcode = response;
                }                
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    }
    $("#rcvfg_rtn_rc_divku").css('height', $(window).height()*65/100); 
    $("#rcvfg_rtn_rc_REMARK").on('shown.bs.modal', function(){
        $("#rcvfg_rtn_rc_txt_remark").focus();
        document.getElementById('rcvfg_rtn_rc_txt_remark').select();
    });        

    function rcvfg_rtn_rc_e_remark(prow, pirow ,pikol){
        let tcell = prow.getElementsByTagName("td")[pikol];
        let tcellremark = prow.getElementsByTagName("td")[pikol+1];
        let curval  = tcell.innerText;
        document.getElementById('rcvfg_rtn_rc_txt_remark').value=tcellremark.innerText;
        document.getElementById('rcvfg_rtn_rc_txt_reff').value=curval;
        $("#rcvfg_rtn_rc_REMARK").modal('show');
    }

    function cancelconfirmID_e_dblclick(){
        let txtcode = document.getElementById('rcvfg_rtn_rc_txt_code');
        txtcode.readOnly = false;
        txtcode.value="";
        txtcode.focus();
    }

    function rcvfg_rtn_rc_wjm_lotno_e_keypress(e){
        if(e.which==13){
            let enteredVal = document.getElementById('rcvfg_rtn_rc_wjm_lotno').value.toUpperCase();
            if(enteredVal.includes("3N2")){
                let aVal = enteredVal.split(" ");
                enteredVal = aVal[2];
                document.getElementById('rcvfg_rtn_rc_wjm_lotno').value = enteredVal;
            }
            document.getElementById('rcvfg_rtn_rc_txt_wjm_itemcode_loc').focus();
        }
    }

    function rcvfg_rtn_rc_txt_wjm_qty_e_keypress(e){
        if(e.which==13){
            document.getElementById('rcvfg_rtn_rc_wjm_lotno').focus();
        }
    }
</script>