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
                    <input type="text" class="form-control" id="rcvfg_rtn_rcoth_txt_serahterima" placeholder="Autonumber"  readonly>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#rcvfg_rtn_rcoth_MOD_serahterima"><i class="fas fa-search"></i></button>
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >Date</span>
                    <input type="text" class="form-control" id="rcvfg_rtn_rcoth_txt_date" readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-7 mb-1">
                <h3 ><span class="badge bg-info">1 <i class="fas fa-hand-point-right" ></i></span> <span class="badge bg-info">Status Label</span></h3>
            </div>
            <div class="col-md-5 mb-1 text-end">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="rcvfg_rtn_rcoth_typefg" id="rcvfg_rtn_rcoth_withjm" value="JM" checked>
                    <label class="form-check-label" for="rcvfg_rtn_rcoth_withjm">With JM</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="rcvfg_rtn_rcoth_typefg" id="rcvfg_rtn_rcoth_withoutjm" value="NOJM">
                    <label class="form-check-label" for="rcvfg_rtn_rcoth_withoutjm">Without JM</label>
                </div>                
            </div>
        </div>
        <div class="row">            
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >Assy Code</span>
                    <input type="text" class="form-control" id="rcvfg_rtn_rcoth_txt_assyno" readonly>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#rcvfg_rtn_rcoth_MOD_rasel"><i class="fas fa-search"></i></button>
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >Assy Name</span>
                    <input type="text" class="form-control" id="rcvfg_rtn_rcoth_txt_assynm" readonly>
                </div>
            </div>
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >Document</span>
                    <input type="text" class="form-control" id="rcvfg_rtn_rcoth_txt_docno" readonly>
                </div>
            </div>
            <div class="col-md-2 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >Qty</span>
                    <input type="text" class="form-control" id="rcvfg_rtn_rcoth_txt_assyqty" readonly>
                </div>
            </div>
        </div>
        <div id="rcvfg_rtn_rcoth_div_jm">
            <div class="row">
                <div class="col-md-2 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >JM</span>
                        <input type="text" class="form-control" id="rcvfg_rtn_rcoth_txt_jmcode" maxlength="9" placeholder="scan JMCode here..." required style="text-align:center" onkeypress="rcvfg_rtn_rcoth_txt_jmcode_e_keypress(event)">
                        <span class="input-group-text" ><i class="fas fa-barcode"></i></span>
                    </div>
                </div>           
                <div class="col-md-5 mb-1">                
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" onclick="rcvfg_rtn_rcoth_showpartreq()" style="cursor: pointer;">Item Code</span>
                        <input type="text" class="form-control" id="rcvfg_rtn_rcoth_txt_itemcode" onkeypress="rcvfg_rtn_rcoth_txt_itemcode_e_keypress(event)" > 
                    </div>                
                    <input type="hidden" id="rcvfg_rtn_rcoth_txt_itemname">
                </div>
                <div class="col-md-3 mb-1">
                    <div class="input-group input-group-sm mb-1">                    
                        <span class="input-group-text" >Lot No</span>
                        <input type="text" class="form-control font-monospace" id="rcvfg_rtn_rcoth_g_lotno"  onkeypress="rcvfg_rtn_rcoth_g_lotno_e_keypress(event)" > 
                    </div>
                </div>
                <div class="col-md-2 mb-1">
                    <div class="input-group input-group-sm mb-1"> 
                        <span class="input-group-text" >Location</span>
                        <input type="text" class="form-control" id="rcvfg_rtn_rcoth_txt_itemcode_loc" onkeypress="rcvfg_rtn_rcoth_txt_itemcode_loc_e_keypress(event)" > 
                    </div>
                </div>
            </div> 
            <div class="row">
                <div class="col-md-12 mb-1 text-end">
                    <div class="btn-group btn-group-sm" role="group" >
                        <button type="button" class="btn btn-outline-primary" id="rcvfg_rtn_rcoth_btn_add" onclick="rcvfg_rtn_rcoth_btn_add_e_click()">Next JM</button>
                        <button type="button" class="btn btn-outline-primary" id="rcvfg_rtn_rcoth_btn_add_id" onclick="rcvfg_rtn_rcoth_btn_add_id_e_click()">Next ID</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="rcvfg_rtn_rcoth_div_wjm" class="d-none">
            <div class="row">
                <div class="col-md-3 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >Item Code</span>
                        <input type="text" class="form-control" id="rcvfg_rtn_rcoth_txt_wjm_itemcode" onkeypress="rcvfg_rtn_rcoth_txt_wjm_itemcode_e_keypress(event)"  > 
                    </div>
                    <input type="hidden" id="rcvfg_rtn_rcoth_txt_wjm_itemname">
                </div>
                <div class="col-md-3 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >Qty</span>
                        <input type="text" class="form-control" id="rcvfg_rtn_rcoth_txt_wjm_qty" onkeypress="rcvfg_rtn_rcoth_txt_wjm_qty_e_keypress(event)">
                        <span class="input-group-text" ><i class="fas fa-barcode"></i></span>
                    </div>
                </div>
                <div class="col-md-3 mb-1">
                    <div class="input-group input-group-sm mb-1">                    
                        <span class="input-group-text" >Lot No</span>
                        <input type="text" class="form-control font-monospace" id="rcvfg_rtn_rcoth_wjm_lotno" onkeypress="rcvfg_rtn_rcoth_wjm_lotno_e_keypress(event)" > 
                    </div>
                </div>
                <div class="col-md-3 mb-1">
                    <div class="input-group input-group-sm mb-1"> 
                        <span class="input-group-text" >Location</span>
                        <input type="text" class="form-control" id="rcvfg_rtn_rcoth_txt_wjm_itemcode_loc"  > 
                    </div>
                </div>
            </div> 
            <div class="row">
                <div class="col-md-12 mb-1 text-end">
                    <div class="btn-group btn-group-sm" role="group" >
                        <button type="button" class="btn btn-outline-primary" id="rcvfg_rtn_rcoth_btn_wjm_add" onclick="rcvfg_rtn_rcoth_btn_wjm_add_e_click()">Add</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1" onclick="rcvfg_rtn_rcoth_e_click(event)">
                <div class="table-responsive" id="rcvfg_rtn_rcoth_divsubku" >
                    <table id="rcvfg_rtn_rcoth_tblsub" class="table table-sm table-striped table-hover table-bordered" style="width:100%">
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
                    <input type="text" class="form-control" id="rcvfg_rtn_rcoth_txt_codeext" onkeypress="rcvfg_rtn_rcoth_txt_codeext_e_keypress(event)" placeholder="scan it here..." required style="text-align:center">                      
                    <span class="input-group-text" ><i class="fas fa-qrcode"></i></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="rcvfg_rtn_rcoth_divext">
                    <table id="rcvfg_rtn_rcoth_tblext" class="table table-sm table-striped table-hover table-bordered" style="width:100%">
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
                    <button type="button" class="btn btn-primary" id="rcvfg_rtn_rcoth_btn_new" onclick="rcvfg_rtn_rcoth_btn_new_e_click()"><i class="fas fa-file"></i></button>  
                    <button type="button" class="btn btn-primary" id="rcvfg_rtn_rcoth_btn_save" onclick="rcvfg_rtn_rcoth_btn_save_e_click()"><i class="fas fa-save"></i></button>                
                    <button type="button" class="btn btn-outline-primary" id="rcvfg_rtn_rcoth_btn_print" onclick="rcvfg_rtn_rcoth_btn_print_e_click()"><i class="fas fa-print"></i></button>
                </div>
            </div>
        </div>     
    </div>
</div>

<div id="rcvfg_rtn_rcoth_context_menu" class="easyui-menu" style="width:120px;">         
    <div data-options="iconCls:'icon-cancel'" onclick="rcvfg_rtn_rcoth_e_cancelitem()">Cancel</div>     
</div>
<div id="rcvfg_rtn_rcoth_context_menu_custlabel" class="easyui-menu" style="width:120px;">         
    <div data-options="iconCls:'icon-cancel'" onclick="rcvfg_rtn_rcoth_e_cancelitem_custlabel()">Cancel</div>     
</div>
<div id="rcvfg_rtn_rcoth_ctx_menu" class="easyui-menu" style="width:120px;">    
    <div onclick="rcvfg_rtn_rcoth_e_copylot()">Copy Lot</div> 
</div>
<input type="hidden" id="rcvfg_rtn_rcoth_g_id">
<input type="hidden" id="rcvfg_rtn_rcoth_g_assycd">
<input type="hidden" id="rcvfg_rtn_rcoth_txt_itemcode_qty">
<div class="modal fade" id="rcvfg_rtn_rcoth_MOD_serahterima">
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
                        <select class="form-select" id="rcvfg_rtn_rcoth_cmb_searchby" onchange="rcvfg_rtn_rcoth_cmb_searchby_e_onchange()" required>
                        <option value="ST">Serah Terima Doc.</option>
                        <option value="DO">RA Doc.</option>
                        <option value="SL">Status Label</option>
                        </select>
                        <input type="text" class="form-control" id="rcvfg_rtn_rcoth_txt_search_serahterima" title="press enter to start searching" onkeypress="rcvfg_rtn_rcoth_txt_search_serahterima_e_keypress(event)">
                    </div>
                </div>
            </div>
            <div class="row">               
                <div class="col-md-12 mb-1 text-end">
                    <span class="badge bg-info" id="rcvfg_rtn_rcoth_tbl_serahterima_lblinfo"></span>
                </div>                
            </div>           
            <div class="row">
                <div class="col" id="rcvfg_rtn_rcoth_divku_search_serahterima">
                    <table id="rcvfg_rtn_rcoth_tbl_serahterima" class="table table-hover table-sm table-bordered" style="width:100%;cursor:pointer;font-size:75%">
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
<div class="modal fade" id="rcvfg_rtn_rcoth_MOD_rasel">
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
                        <select class="form-select" id="rcvfg_rtn_rcoth_cmb_searchby_rasel" onchange="rcvfg_rtn_rcoth_cmb_searchby_e_onchange()" required>                        
                        <option value="AC">Assy Code</option>
                        <option value="DO">RA Doc.</option>
                        </select>
                        <input type="text" class="form-control" id="rcvfg_rtn_rcoth_txt_search_rasel" title="press enter to start searching" onkeypress="rcvfg_rtn_rcoth_txt_search_rasel_e_keypress(event)">
                    </div>
                </div>
            </div>
            <div class="row">               
                <div class="col-md-12 mb-1 text-end">
                    <span class="badge bg-info" id="rcvfg_rtn_rcoth_tbl_rasel_lblinfo"></span>
                </div>
            </div>
            <div class="row">
                <div class="col" id="rcvfg_rtn_rcoth_divku_search_rasel">
                    <table id="rcvfg_rtn_rcoth_tbl_rasel" class="table table-hover table-sm table-bordered" style="width:100%;cursor:pointer;font-size:75%">
                        <thead class="table-light">
                            <tr>
                                <th colspan="2" class="text-center">Assy</th>
                                <th rowspan="2" class="text-center align-middle">Date</th>
                                <th rowspan="2" class="text-center align-middle">RA Doc.</th>
                                <th rowspan="2" class="text-center align-middle">PIC</th>
                            </tr>
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
<div class="modal fade" id="rcvfg_rtn_rcoth_MODlot">
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
                        <table id="rcvfg_rtn_rcoth_tbllot" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer;font-size:91%">
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
<div class="modal fade" id="rcvfg_rtn_rcoth_MODPartreq">
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
                    <div class="table-responsive" id="rcvfg_rtn_rcoth_divpartreq">
                        <table id="rcvfg_rtn_rcoth_tblpartreq" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer;font-size:91%">
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
    $("#rcvfg_rtn_rcoth_MOD_rasel").on('shown.bs.modal', function(){
        $("#rcvfg_rtn_rcoth_txt_search_rasel").focus();
    });
</script>