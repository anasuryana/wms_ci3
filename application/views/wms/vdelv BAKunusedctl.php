<div style="padding:5px" >
    <div class="col-md-12 order-md-1">
        <div class="row">
            <div class="col-sm-9 mb-1 pr-1 pl-1">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="col-md-12 order-md-0 p-0">
                            <div class="row ">
                                <div class="col-md-6 mb-1 p-1">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">TX ID</span>
                                        </div>							
                                        <input type="text" class="form-control" id="txfg_txt_id" required >
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-primary" type="button" id="txfg_btn_findmod"><i class="fas fa-search"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-1 p-1 ">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Customs date</span>
                                        </div>							
                                        <input type="text" class="form-control" id="txfg_txt_custdate" required readonly>                                        
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 mb-1 p-1">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">TX Status</span>
                                        </div>							
                                        <input type="text" class="form-control" id="txfg_status" required readonly>	
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="txfg_lbl_status">
                                                <!-- <i class="fas fa-check text-success"></i> -->
                                            </span>
                                        </div>							
                                    </div>
                                </div>
                                <div class="col-md-3 mb-1 p-1 ">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" title="Invoice Date">Inv date</span>
                                        </div>							
                                        <input type="text" class="form-control" id="txfg_txt_invdate" required readonly>                                        
                                    </div>
                                </div>
                                <div class="col-md-3 mb-1 p-1">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Inv No</span>
                                        </div>							
                                        <input type="text" class="form-control" id="txfg_txt_invno" required>								
                                    </div>
                                </div>
                                <div class="col-md-3 mb-1 p-1">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">SMT INV.NO</span>
                                        </div>							
                                        <input type="text" class="form-control" id="txfg_txt_invsmt" required>                                        
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-9 mb-1 p-1">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Customer</span>
                                        </div>							
                                        <input type="text" class="form-control" id="txfg_custname" required readonly>	
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-primary" id="txfg_btnfindmodcust"><i class="fas fa-search"></i></button>
                                        </div>							
                                    </div>
                                </div>
                                <div class="col-md-3 mb-1 p-1">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Currency</span>
                                        </div>							
                                        <input type="text" readonly class="form-control" id="txfg_curr">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-1 p-1">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Consignee</span>
                                        </div>                                        
                                        <select id="txfg_consignee"	class="form-control">
                                            <option value="-">-</option>
                                        </select>
                                        <div class="input-group-append">                                            
                                            <div class="input-group-text">
                                                <input type="checkbox" id="txfg_ckreplacement">
                                            </div>
                                            <div class="input-group-text">
                                                Replacement DO
                                            </div>
                                        </div>                                                                            
                                    </div>
                                </div>
                                <div class="col-md-6 mb-1 p-1">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Transport</span>
                                        </div>				
                                        <input type="text" class="form-control" id="txfg_txt_transport" required readonly>	
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-primary" id="txfg_btnfindmodtrans"><i class="fas fa-search"></i></button>                                            
                                        </div>
                                        <input type="text" class="form-control" id="txfg_txt_transporttype" required readonly>                                                                    
                                    </div>
                                </div>
                            </div>                         
                            <div class="row">
                                <div class="col-md-6 mb-1 p-1">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Description</span>
                                        </div>							
                                        <input type="text" class="form-control" id="txfg_txt_description" required readonly>                                                           
                                    </div>
                                </div>
                                <div class="col-md-6 mb-1 p-1">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Remark</span>
                                        </div>							
                                        <input type="text" class="form-control" id="txfg_txt_remark">                                                           
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-1 p-1">
                                    <div class="btn-group btn-group-sm">
                                        <button title="Add new" class="btn btn-outline-primary" type="button" id="txfg_btn_new"><i class="fas fa-file"></i></button>
                                        <button title="Save" class="btn btn-primary" type="button" id="txfg_btn_save"><i class="fas fa-save"></i></button>
                                        <button title="Approve" class="btn btn-success" type="button" id="txfg_btn_appr">Approve</button>
                                        <button title="Posting" class="btn btn-outline-primary" type="button" id="txfg_btn_post">Posting</button>
                                        <button title="Print" class="btn btn-outline-primary" type="button" id="txfg_btn_print"><i class="fas fa-print"></i></button>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-1 p-1">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="txfg_ck_VAT" value="VAT">
                                        <label class="form-check-label" for="txfg_ck_VAT">VAT</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="txfg_ck_kanban" value="KANBAN DELIVERY">
                                        <label class="form-check-label" for="txfg_ck_kanban">Kanban Delivery</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="txfg_ck_noncom" value="NON COMMERCIAL">
                                        <label class="form-check-label" for="txfg_ck_noncom">Non Commercial</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-7 mb-1">
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-primary" id="txfg_btn_addsi">Add from SI</button>                                        
                                    </div>                                 
                                </div>
                                <div class="col-md-5 mb-0 text-right">
                                    <button class="btn btn-primary btn-sm" id="txfg_btn_customs">Customs DOC</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>	
            <div class="col-sm-3 mb-1 pr-1 pl-1">
                <div class="card mb-9 shadow-sm">                   
                    <div class="card-body">
                        <div class="col-md-12 order-md-0 pl-0 pr-0">
                            <div class="row">
                                <div class="col-md-12 mb-1 p-1">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Created by</span>
                                        </div>
                                        <input type="text" id="txfg_txt_createdby" class="form-control" readonly>
                                    </div>
                                </div>	                               	
                            </div>                           
                            <div class="row">
                                <div class="col-md-12 mb-1 p-1">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Created Time</span>
                                        </div>
                                        <input type="text" id="txfg_txt_createdtime" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-1 p-1">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" title="Last Update by">LU by</span>
                                        </div>
                                        <input type="text" id="txfg_txt_luby" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-1 p-1">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" title="Last Update Time">LU Time</span>
                                        </div>
                                        <input type="text" id="txfg_txt_lutime" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-1 p-1">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Approved by</span>
                                        </div>
                                        <input type="text" id="txfg_txt_apprby" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-1 p-1">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" >Approved Time</span>
                                        </div>
                                        <input type="text" id="txfg_txt_apprtime" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-1 p-1">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" >Posted by</span>
                                        </div>
                                        <input type="text" id="txfg_txt_postedby" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-1 p-1">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" >Posted Time</span>
                                        </div>
                                        <input type="text" id="txfg_txt_postedtime" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>					
                    </div>			
                </div>
            </div>
        </div>  
        <div class="row">
            <div class="col-sm-12  mb-1 pr-1 pl-1">
                <div class="table-responsive" id="txfg_divku">
                    <table id="txfg_tbltx" class="table table-hover table-sm table-bordered" style="width:100%;font-size:81%">
                        <thead class="thead-light">
                            <tr>                                
                                <th>SO No</th>
                                <th>SO Date</th>
                                <th>Item Code</th>
                                <th>Item Name</th>
                                <th>Cust Part Number</th>
                                <th class="text-right">Box</th>
                                <th class="text-right">@Box</th>                                
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
<div class="modal fade" id="TXFG_TRANSMOD">
    <div class="modal-dialog">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Transportation List</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">  
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <label class="input-group-text">Search</label>
                        </div>
                        <input type="text" class="form-control" id="txfg_searchtrans" maxlength="11">
                    </div>
                </div>                
            </div>            
            <div class="row">
                <div class="col">
                    <table id="txfg_tbltrans" class="table table-hover table-sm table-bordered">
                        <thead class="thead-light">
                            <tr>                               
                                <th>Plate No</th>
                                <th>Type</th>
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
<div class="modal fade" id="TXFG_MODCUS">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Customer List</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text" >Search</span>
                        </div>
                        <input type="text" class="form-control" id="txfg_txtsearchcus" maxlength="15" required placeholder="...">                        
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text" >Search by</span>
                        </div>
                        <select id="txfg_srchby" class="form-control">
                            <option value="nm">Name</option>
                            <option value="cd">Code</option>
                            <option value="ab">Abbr Name</option>
                            <option value="ad">Address</option>
                        </select>                  
                    </div>
                </div>
            </div>                 
            <div class="row">
                <div class="col">
                    <div class="table-responsive">
                        <table id="txfg_tblcus" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer">
                            <thead class="thead-light">
                                <tr>
                                    <th>Customer Code</th>
                                    <th>Currency</th>
                                    <th>Name</th>
                                    <th>Abbr Name</th>
                                    <th>Address</th>
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

<div class="modal fade" id="TXFG_CUSTOMSMOD">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title text-info">Customs Document</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <label class="input-group-text">Category</label>
                        </div>
                        <select class="form-control" id="txfg_cmb_cat">
                            <option value="SALES">SALES</option>
                            <option value="KB">KB</option>
                            <option value="RE-EXPORT">RE-EXPORT</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <label class="input-group-text">Tujuan</label>
                        </div>
                        <select class="form-control" id="txfg_cmb_purpose">
                            <option value="-">-</option>
                        </select>
                    </div>
                </div>                
            </div>
            <div class="row">
                <div class="col mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <label class="input-group-text">Document</label>
                        </div>
                        <select class="form-control" id="txfg_cmb_bcdoc">
                            <option value="25">BC 2.5</option>
                            <option value="27">BC 2.7</option>
                            <option value="41">BC 4.1</option>
                        </select>
                    </div>
                </div>
                <div class="col mb-1 d-none" id="txfg_div_officedest">
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <label class="input-group-text">Kantor Tujuan</label>
                        </div>
                        <select class="form-control" id="txfg_destoffice">
                           <?php
                           $tohtml ="<option value='-'>-</option>"; 
                            foreach($ldestoffice as  $r){
                                $tohtml .= "<option value='$r[KODE_KANTOR]'>$r[URAIAN_KANTOR]</option>";
                            }
                            echo $tohtml;
                           ?>
                        </select>
                    </div>
                </div>
                <div class="col mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <label class="input-group-text">NOPEN</label>
                        </div>
                        <input type="text" id="txfg_txt_nopen" class="form-control" maxlength="10">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <label class="input-group-text">NOAJU</label>
                        </div>
                        <input type="text" id="txfg_txt_noaju" class="form-control" maxlength="10">
                    </div>
                </div>
                <div class="col-md-6 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <label class="input-group-text">Tgl. Surat</label>
                        </div>
                        <input type="text" id="txfg_txt_tglsurat" class="form-control" readonly>
                    </div>
                </div>                
            </div>
            <div class="row">
                <div class="col-md-6 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <label class="input-group-text">Contract No</label>
                        </div>
                        <input type="text" id="txfg_txt_nokontrak" class="form-control" maxlength="50">
                    </div>
                </div>
                <div class="col-md-6 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <label class="input-group-text">Contract Date</label>
                        </div>
                        <input type="text" id="txfg_txt_tglkontrak" class="form-control" readonly>
                    </div>
                </div>                
            </div>
            <div class="row">
                <div class="col-md-6 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <label class="input-group-text">Cara Angkut</label>
                        </div>
                        <select id="txfg_cmb_carang" class="form-control">
                        <?php 
                            $toec = '';
                            foreach($lwaytransport as $r){
                                $toec .= '<option value="'.$r['KODE_CARA_ANGKUT'].'">'.$r['URAIAN_CARA_ANGKUT'].'</option>';
                            }
                            echo $toec;
                        ?>
                        </select>
                        
                    </div>
                </div>
                <div class="col-md-6 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <label class="input-group-text">EX. Bc No</label>
                        </div>
                        <input type="text" id="txfg_txt_exbcno" class="form-control" >
                    </div>
                </div>                
            </div>            
        </div>             
      </div>
    </div>
</div>

<div class="modal fade" id="TXFG_MODSI">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">SI List</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text" >Search</span>
                        </div>
                        <input type="text" class="form-control" id="txfg_sitxtsearch" maxlength="15" required placeholder="...">                        
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text" >Search by</span>
                        </div>
                        <select id="txfg_sisrchby" class="form-control" onchange="document.getElementById('txfg_sitxtsearch').focus()">
                            <option value="si">SI</option>
                            <option value="cd">Item Code</option>
                            <option value="nm">Item Name</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col mb-1">
                    <button class="btn btn-sm btn-primary btn-block" id="txfg_sibtngetselected">Get selected rows</button>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="txfg_sidivku">                        
                        <table id="txfg_sitbl" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center"><input type="checkbox" id="txfg_sickall" ></th>
                                    <th>SI No</th>
                                    <th title="Kanband / PO">Document No</th>
                                    <th>Item Code</th>
                                    <th>Item Name</th>
                                    <th class="text-right">@ Box</th>
                                    <th class="text-center">ID</th>
                                    <th class="d-none">Model</th>
                                    <th class="d-none">Doc date</th>
                                    <th class="text-right">Price</th>
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
<div class="modal fade" id="TXFG_MODSAVED">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Transaction List</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text" >Search</span>
                        </div>
                        <input type="text" class="form-control" id="txfg_txtxtsearch" maxlength="15" required placeholder="...">                        
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text" >Search by</span>
                        </div>
                        <select id="txfg_txsrchby" class="form-control" onchange="document.getElementById('txfg_txtxtsearch').focus()">
                            <option value="tx">TX ID</option>
                            <option value="txdate">TX Date</option>
                            <option value="cusnm">Customer</option>
                        </select>
                    </div>
                </div>
            </div>            
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="txfg_txdivku">                        
                        <table id="txfg_txtbl" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer;font-size:81%">
                            <thead class="thead-light">
                                <tr>
                                    <th>TX ID</th>
                                    <th>TX Date</th>
                                    <th>Description</th>
                                    <th class="d-none">Customer ID</th>
                                    <th>Customer Name</th>
                                    <th>Invoice</th>
                                    <th>Invoice SMT</th>
                                    <th>Transportation</th>                                    
                                    <th>Remark</th>
                                    <th>Currency</th>
                                    <th class="d-none">Transportation Type</th>
                                    <th class="d-none">Consignee</th>
                                    <th class="d-none">is_replacement</th>
                                    <th class="d-none">is_vat</th>
                                    <th class="d-none">is_kanbandelivery</th>
                                    <th class="d-none">is_noncom</th>
                                    <th class="d-none">Created By</th>
                                    <th class="d-none">Created Time</th>
                                    <th class="d-none">Last update By</th>
                                    <th class="d-none">Last update Time</th>
                                    <th class="d-none">Approved By</th>
                                    <th class="d-none">Approved Time</th>
                                    <th class="d-none">Posted By</th>
                                    <th class="d-none">Posted Time</th>
                                    <th class="d-none">NOAJU</th>
                                    <th class="d-none">BCTYPE</th>
                                    <th class="d-none">NOPEN</th>
                                    <th class="d-none">KANTORTUJUAN</th>
                                    <th class="d-none">TUJUANPENGIRIMAN</th>
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
<div class="modal fade" id="TXFG_MODPRINT">
    <div class="modal-dialog">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Document Type</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col mb-1">
                    <ul class="list-group text-center">
                        <li class="list-group-item"> 
                            <input class="form-check-input" type="checkbox" value="1" id="txfg_ckDO">
                            <label class="form-check-label" for="txfg_ckDO">Delivery Order</label>          
                        </li>
                        <li class="list-group-item"> 
                            <input class="form-check-input" type="checkbox" value="1" id="txfg_ckINV">
                            <label class="form-check-label" for="txfg_ckINV">Invoice</label>
                        </li>
                        <li class="list-group-item"> 
                            <input class="form-check-input" type="checkbox" value="1" id="txfg_ckPL"> 
                            <label class="form-check-label" for="txfg_ckPL">Packing List</label>
                        </li>     
                    </ul>                    
                </div>
            </div>
            <div class="row">
                <div class="col mb-1 text-center">
                    <button class="btn btn-sm btn-primary" title="Print" id="txfg_btnprintseldocs"><i class="fas fa-print"></i></button>
                </div>
            </div>           
        </div>             
      </div>
    </div>
</div>
<div class="modal fade" id="TXFG_PROGRESS">
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

<div class="modal fade" id="TXFG_DETAILSER">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title"><i class="fas fa-info text-info"></i></h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">  
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text" >SO</span>
                        </div>
                        <input type="text" class="form-control" id="txfg_detser_so" readonly>                        
                    </div>
                </div>                
            </div>
            <div class="row">
                <div class="col-md-4 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text" >Item Code</span>
                        </div>
                        <input type="text" class="form-control" id="txfg_detser_itmcd" readonly>
                    </div>
                </div>
                <div class="col-md-4 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text" >Item Name</span>
                        </div>
                        <input type="text" class="form-control" id="txfg_detser_itmnm" readonly>
                    </div>
                </div>
                <div class="col-md-4 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text" >QTY</span>
                        </div>
                        <input type="text" class="form-control" id="txfg_detser_itmqty" readonly>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="table-responsive" >
                        <table id="txfg_tbldetailser" class="table table-hover table-sm table-bordered" style="width:100%;font-size:91%">
                            <thead class="thead-light">
                                <tr>                                
                                    <th>ID</th>                                                                                             
                                    <th class="text-right">QTY</th>
                                    <th class="text-center">...</th>
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
jQuery(document).ready(function(){
    var scr_txfg_cust = '';
    var txfg_isedit_mode = false;
    var txfg_ttlcek = 0;
    var txfg_ar_item_ser = [];
    var txfg_ar_item_cd = [];
    var txfg_ar_item_nm = [];
    var txfg_ar_item_qty = [];
    var txfg_ar_item_model = [];
    var txfg_ar_si = [];
    var txfg_ar_so = [];
    var txfg_ar_sodt = [];
    $("#txfg_btnfindmodtrans").click(function (e) { 
        e.preventDefault();
        $("#TXFG_TRANSMOD").modal('show');
    });
$("#txfg_searchtrans").keypress(function (e) { 
    if(e.which==13){
        var mval = $(this).val();
        if(mval.trim()!=''){
            $.ajax({
                type: "get",
                url: "<?=base_url('Trans/search')?>",
                data: {inkey: mval},
                dataType: "json",
                success: function (response) {
                    var ttlrows = response.length;
                    var tohtml = '';
                    for(var i=0;i<ttlrows;i++){
                        tohtml += '<tr style="cursor:pointer">'+
                        '<td>'+response[i].MSTTRANS_ID+'</td>'+
                        '<td>'+response[i].MSTTRANS_TYPE+'</td>'+
                        '</tr>';
                    }
                    $("#txfg_tbltrans tbody").html(tohtml);
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }        
    }
});
$("#TXFG_TRANSMOD").on('shown.bs.modal', function(){
    $("#txfg_searchtrans").focus();
});
$("#TXFG_PROGRESS").on('shown.bs.modal', function(){
    txfg_e_posting();
});
$('#txfg_tbltrans tbody').on( 'click', 'tr', function () { 
    if ( $(this).hasClass('table-active') ) {			
        $(this).removeClass('table-active');
    } else {                    			
        $('#txfg_tbltrans tbody tr.table-active').removeClass('table-active');
        $(this).addClass('table-active');
    }                
    var mcd     = $(this).closest("tr").find('td:eq(0)').text();
    var mtype    = $(this).closest("tr").find('td:eq(1)').text();    
    $("#txfg_txt_transport").val(mcd);
    $("#txfg_txt_transporttype").val(mtype);
    $("#TXFG_TRANSMOD").modal('hide');    
});

$('#txfg_tblcus tbody').on( 'click', 'tr', function () { 
    if ( $(this).hasClass('table-active') ) {			
        $(this).removeClass('table-active');
    } else {                    			
        $('#txfg_tblcus tbody tr.table-active').removeClass('table-active');
        $(this).addClass('table-active');
    }                
    scr_txfg_cust = $(this).closest("tr").find('td:eq(0)').text();
    var mcuscurr  = $(this).closest("tr").find('td:eq(1)').text();
    var mcusnm    = $(this).closest("tr").find('td:eq(2)').text();

    $("#txfg_custname").val(mcusnm);
    $("#txfg_curr").val(mcuscurr);
    $("#TXFG_MODCUS").modal('hide');
    txfg_getconsignee(scr_txfg_cust, '');
    $("#txfg_sitbl tbody").empty();
});

function txfg_getconsignee(ppar, selval){
    $.ajax({
        type: "get",
        url: "<?=base_url('MCNSGN/getbyparent')?>",
        data: {inpar: ppar},
        dataType: "json",
        success: function (response) {
            var ttlr = response.length;
            var tohtml = '';
            for(let i=0;i<ttlr;i++){
                tohtml += '<option value="'+response[i].MCNSGN_NM+'">'+
                response[i].MCNSGN_NM+
                '</option>';
            }
            $("#txfg_consignee").html(tohtml);
            if(selval!=''){
                $("#txfg_consignee").val(selval);
            }
        }, error: function(xhr, xopt, xthrow){
            alertify.error(xthrow);
        }
    });
}


$("#txfg_txt_invdate").datepicker({
    format: 'yyyy-mm-dd',
    autoclose:true
});
$("#txfg_txt_custdate").datepicker({
    format: 'yyyy-mm-dd',
    autoclose:true
});
$("#txfg_txt_tglsurat").datepicker({
    format: 'yyyy-mm-dd',
    autoclose:true
});
$("#txfg_txt_tglkontrak").datepicker({
    format: 'yyyy-mm-dd',
    autoclose:true
});

$("#txfg_txt_custdate").datepicker('update', '<?php echo date('Y-m-d'); ?>');
$("#txfg_txt_tglsurat").datepicker('update', '<?php echo date('Y-m-d'); ?>');
$("#txfg_txt_tglkontrak").datepicker('update', '<?php echo date('Y-m-d'); ?>');
$("#txfg_txt_invdate").datepicker('update', '<?php echo date('Y-m-d'); ?>');
$("#txfg_btnfindmodcust").click(function (e) { 
    e.preventDefault();
    $("#TXFG_MODCUS").modal('show');
});
$("#TXFG_MODCUS").on('shown.bs.modal', function(){
    $("#txfg_txtsearchcus").focus();
});
$("#txfg_txtsearchcus").keypress(function (e) { 
    if(e.which==13){
        var mkey = $(this).val();
        var msearchby = $("#txfg_srchby").val();        
        $.ajax({
            type: "get",
            url: "<?=base_url('MSTCUS/search')?>",
            data: {cid : mkey, csrchby: msearchby},
            dataType: "json",
            success: function (response) {
                var ttlrows = response.length;
                var tohtml ='';
                for(var i=0;i<ttlrows;i++){
                    
                    tohtml += '<tr style="cursor:pointer">'+
                    '<td style="white-space:nowrap">'+response[i].MCUS_CUSCD.trim()+'</td>'+
                    '<td style="white-space:nowrap">'+response[i].MCUS_CURCD+'</td>'+
                    '<td style="white-space:nowrap">'+response[i].MCUS_CUSNM+'</td>'+
                    '<td style="white-space:nowrap">'+response[i].MCUS_ABBRV+'</td>'+
                    '<td>'+response[i].MCUS_ADDR1+'</td>'+
                    '</tr>';
                }                
                $('#txfg_tblcus tbody').html(tohtml);
            }
        });
    }
});
$("#txfg_srchby").change(function(){
    $("#txfg_txtsearchcus").focus();
});
$("#txfg_btn_customs").click(function (e) { 
    e.preventDefault();
    $("#TXFG_CUSTOMSMOD").modal('show');
});
$("#txfg_cmb_bcdoc").change(function (e) { 
    e.preventDefault();
    let mval = $(this).val();
    if(mval=="27"){
        $("#txfg_div_officedest").removeClass("d-none");
    } else {
        $("#txfg_div_officedest").addClass("d-none");
    }
    $.ajax({
        type: "get",
        url: "<?=base_url('refceisa/MPurposeDLV/getbyPar')?>",
        data: {inid: mval},
        dataType: "json",
        success: function (response) {
            var tohtml = '';
            let ttlrows = response.length;
            if(ttlrows>1){
                for(let i=0;i<ttlrows;i++){
                    tohtml += '<option value="'+response[i].KODE_TUJUAN_PENGIRIMAN+'">'+response[i].URAIAN_TUJUAN_PENGIRIMAN+'</option>';
                }
            } else {                
                tohtml = '<option value="-">-</option>';
            }
            $("#txfg_cmb_purpose").html('');
            $("#txfg_cmb_purpose").html(tohtml);

        }, error: function(xhr, xopt, xthrow){
            alertify.error(xthrow);
        }
    });
});

$("#txfg_btn_new").click(function (e) { 
    e.preventDefault();
    $("#txfg_txt_id").val('');$("#txfg_txt_id").prop('readonly',false); $("#txfg_txt_id").focus();    
    $("#txfg_txt_custdate").datepicker('update', '<?php echo date('Y-m-d'); ?>');
    $("#txfg_status").val('');
    $("#txfg_lbl_status").html('');
    $("#txfg_txt_invno").val();
    $("#txfg_custname").val();
    scr_txfg_cust=''; 
    $("#txfg_curr").val('');
    $("#txfg_consignee").html('<option value="-">-</option>');
    $("#txfg_ckreplacement").prop('checked', false);
    $("#txfg_txt_transport").val("");
    $("#txfg_txt_transporttype").val("");
    $("#txfg_txt_description").val("");
    $("#txfg_txt_invsmt").val("");
    document.getElementById('txfg_txt_invno').value='';
    $("#txfg_txt_remark").val("");
    $("#txfg_ck_VAT").prop('checked', false);
    $("#txfg_ck_kanban").prop('checked', false);
    $("#txfg_ck_noncom").prop('checked', false);

    //RIGHT PANE
    $("#txfg_txt_createdby").val("");   $("#txfg_txt_createdtime").val("");
    $("#txfg_txt_luby").val("");        $("#txfg_txt_lutime").val("");
    $("#txfg_txt_apprby").val("");      $("#txfg_txt_apprtime").val("");
    $("#txfg_txt_postedby").val("");    $("#txfg_txt_postedtime").val("");

    //reset
    scr_txfg_cust = '';
    txfg_ar_item_ser = [];
    txfg_ar_item_cd = [];
    txfg_ar_item_nm = [];
    txfg_ar_item_qty = [];
    txfg_ar_item_model = [];
    txfg_ar_si = [];
    txfg_ar_so = [];
    txfg_ar_sodt = [];
    document.getElementById("txfg_destoffice").value='-';
    $("#txfg_tbltx tbody").empty();
    txfg_isedit_mode = false;
});
$("#txfg_ck_noncom").click(function(){
    if($(this).prop("checked")){
        $("#txfg_txt_description").val("Non Commercial Value For Customs");
    } else {
        $("#txfg_txt_description").val("");
    }
});
$("#txfg_btn_addsi").click(function (e) {
    let mcus = document.getElementById('txfg_custname').value;
    if(mcus.trim()==''){
        alertify.warning('Please select customer first !');
        document.getElementById('txfg_btnfindmodcust').focus();
        return;
    }
    $('#TXFG_MODSI').modal('show');
});
$("#txfg_sitxtsearch").keypress(function (e) { 
    if(e.which==13){
        let mkey = $(this).val();
        let msearchby = document.getElementById('txfg_sisrchby').value;        
        $.ajax({
            type: "get",
            url: "<?=base_url('SI/getsi')?>",
            data: {inkey: mkey, insearchby : msearchby, incus:  scr_txfg_cust},
            dataType: "json",
            success: function (response) {
                let ttlrows = response.data.length;
                if(ttlrows>0){
                    let mydes = document.getElementById("txfg_sidivku");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("txfg_sitbl");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let mckall = myfrag.getElementById("txfg_sickall");
                    let tabell = myfrag.getElementById("txfg_sitbl");                    
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];//document.getElementById("rprod_tblwo").getElementsByTagName("tbody")[0];
                    let newrow, newcell, newText;
                    tableku2.innerHTML='';
                    let tominqty = 0;
                    let tempqty = 0;
                    let todisqty = 0;  
                    for (let i = 0; i<ttlrows; i++){                            
                        newrow = tableku2.insertRow(-1);
                        newcell = newrow.insertCell(0);            
                        newText = document.createElement('input');
                        newText.setAttribute("type", "checkbox");
                        newText.disabled = (numeral(response.data[i].SI_DOCREFFPRC).value()==0) ? true:false;                                                   
                        newcell.appendChild(newText);
                        newcell.style.cssText = ''.concat('cursor: pointer;','text-align:center;');
                        newcell = newrow.insertCell(1);
                        newText = document.createTextNode(response.data[i].SI_DOC);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(2);
                        newText = document.createTextNode(response.data[i].SI_DOCREFF);
                        newcell.appendChild(newText);

                        newcell = newrow.insertCell(3);
                        newText = document.createTextNode(response.data[i].SER_ITMID);
                        newcell.appendChild(newText);

                        newcell = newrow.insertCell(4);
                        newText = document.createTextNode(response.data[i].MITM_ITMD1);
                        newcell.appendChild(newText);

                        newcell = newrow.insertCell(5);
                        newText = document.createTextNode(numeral(response.data[i].SISCN_SERQTY).format(','));
                        newcell.appendChild(newText);  
                        newcell.style.cssText = 'text-align: right';

                        newcell = newrow.insertCell(6);
                        newText = document.createTextNode(response.data[i].SISCN_SER);
                        newcell.appendChild(newText);

                        newcell = newrow.insertCell(7);
                        newText = document.createTextNode(response.data[i].SI_MDL);
                        newcell.appendChild(newText);
                        newcell.style.cssText = 'display:none';

                        newcell = newrow.insertCell(8);
                        newText = document.createTextNode(response.data[i].SI_DOCREFFDT);
                        newcell.appendChild(newText);
                        newcell.style.cssText = 'display:none';

                        newcell = newrow.insertCell(9);
                        newText = document.createTextNode(response.data[i].SI_DOCREFFPRC);
                        newcell.appendChild(newText);
                        newcell.style.cssText = 'text-align: right';
                    }
                    let mrows = tableku2.getElementsByTagName("tr");
                    function clooptable(){
                        let cktemp, txtprice ;
                        for(let x=0;x<mrows.length;x++){
                            cktemp = tableku2.rows[x].cells[0].getElementsByTagName('input')[0];
                            txtprice = tableku2.rows[x].cells[9].innerText;                            
                            if(numeral(txtprice).value()>0){ // validate if price > 0
                                cktemp.checked=mckall.checked;
                            }                            
                        }                    
                    }
                    mckall.onclick = function(){clooptable()};
                    mydes.innerHTML='';                            
                    mydes.appendChild(myfrag);                
                } else {
                    alertify.warning('SI Doc not found');
                }
            }, error: function(xhr,xopt,xthrow){
                alertify.error(xthrow);
            }
        });
    }
});
$("#txfg_divku").css('height', $(window).height()*27/100);
$("#txfg_sidivku").css('height', $(window).height()*53/100);
$("#TXFG_MODSI").on('shown.bs.modal', function(){
    document.getElementById('txfg_sitxtsearch').focus();
});
$("#txfg_sibtngetselected").click(function (e) {    
    let tabell = document.getElementById("txfg_sitbl");                    
    let tableku2 = tabell.getElementsByTagName("tbody")[0];
    let mrows = tableku2.getElementsByTagName("tr");
    let cktemp,ttlcek;    
    ttlcek= 0;
    if(txfg_isedit_mode){

    } else {
        txfg_ar_item_ser.splice(0, txfg_ar_item_ser.length);
        txfg_ar_item_cd.splice(0, txfg_ar_item_cd.length);
        txfg_ar_item_nm.splice(0, txfg_ar_item_nm.length);
        txfg_ar_item_qty.splice(0, txfg_ar_item_qty.length);
        txfg_ar_item_model.splice(0, txfg_ar_item_model.length);
        txfg_ar_so.splice(0, txfg_ar_so.length);
        txfg_ar_sodt.splice(0, txfg_ar_sodt.length);
    }
    
    for(let x=0;x<mrows.length;x++){
        cktemp = tableku2.rows[x].cells[0].getElementsByTagName('input')[0];
        if(cktemp.checked){            
            txfg_ar_item_ser.push(tableku2.rows[x].cells[6].innerText);
            txfg_ar_item_cd.push(tableku2.rows[x].cells[3].innerText);
            txfg_ar_item_nm.push(tableku2.rows[x].cells[4].innerText);
            txfg_ar_item_qty.push(tableku2.rows[x].cells[5].innerText);
            txfg_ar_item_model.push(tableku2.rows[x].cells[7].innerText);
            txfg_ar_so.push(tableku2.rows[x].cells[2].innerText);
            txfg_ar_sodt.push(tableku2.rows[x].cells[8].innerText);
            ttlcek++;
        }
    }
    if(ttlcek>0){
        if(txfg_isedit_mode){
            ttlcek += txfg_ttlcek;
        }
        $("#TXFG_MODSI").modal('hide');
        tabell = document.getElementById("txfg_tbltx");                    
        tableku2 = tabell.getElementsByTagName("tbody")[0];
        let newrow, newcell, newText;        
        let tmp_ar_item_box = [];
        let tmp_ar_item_cd = [];
        let tmp_ar_item_nm = [];
        let tmp_ar_item_qty = [];
        let tmp_ar_item_model = [];
        let tmp_ar_si = [];
        let tmp_ar_so = [];
        let tmp_ar_sodt = [];
        tableku2.innerHTML='';
        let flagisexist =false;
        for (let i = 0; i<ttlcek; i++){            
            if(tmp_ar_item_cd.length==0){
                tmp_ar_item_box.push(1);
                tmp_ar_item_cd.push(txfg_ar_item_cd[i]);
                tmp_ar_item_nm.push(txfg_ar_item_nm[i]);
                tmp_ar_item_qty.push(numeral(txfg_ar_item_qty[i]).value());
                tmp_ar_item_model.push(txfg_ar_item_model[i]);
                tmp_ar_si.push(txfg_ar_si[i]);
                tmp_ar_so.push(txfg_ar_so[i]);
                tmp_ar_sodt.push(txfg_ar_sodt[i]);
            } else {
                flagisexist =false;
                for(let k=0;k<tmp_ar_item_cd.length;k++){                    
                    if( (tmp_ar_so[k]==txfg_ar_so[i]) && (tmp_ar_item_cd[k]==txfg_ar_item_cd[i]) && (tmp_ar_si[k]==txfg_ar_si[i]) && (numeral(tmp_ar_item_qty[k]).value()==numeral(txfg_ar_item_qty[i]).value()) ){
                        tmp_ar_item_box[k]=tmp_ar_item_box[k]+1;
                        flagisexist=true;break;
                    }
                }
                if(flagisexist){                                       
                } else {
                    tmp_ar_item_box.push(1);
                    tmp_ar_item_cd.push(txfg_ar_item_cd[i]);
                    tmp_ar_item_nm.push(txfg_ar_item_nm[i]);
                    tmp_ar_item_qty.push(numeral(txfg_ar_item_qty[i]).value());
                    tmp_ar_item_model.push(txfg_ar_item_model[i]);
                    tmp_ar_si.push(txfg_ar_si[i]);
                    tmp_ar_so.push(txfg_ar_so[i]);
                    tmp_ar_sodt.push(txfg_ar_sodt[i]);
                }                
            }            
        }
        let tempso, tempitem, tempitem2; 
        let dsptempso, dspitem,dspsodt, dspitemnm, dspcustpart; 
        for(let i=0;i<tmp_ar_item_cd.length;i++){
            if(tempso!=tmp_ar_so[i] && tempitem != tmp_ar_item_cd[i]){
                tempso=tmp_ar_so[i]; tempitem = tmp_ar_item_cd[i];
                dsptempso = tmp_ar_so[i];
                dspitem =tmp_ar_item_cd[i];
                dspsodt=tmp_ar_sodt[i];
                dspitemnm=tmp_ar_item_nm[i];
                dspcustpart=tmp_ar_item_model[i];
                tempitem2='';
            } else {
                dsptempso = '';
                dspitem ='';
                dspsodt='';
                dspitemnm='';
                dspcustpart='';
                if( (tempitem != tmp_ar_item_cd[i]) && tempitem2=='' ){
                    dspitem =tmp_ar_item_cd[i]; 
                    dspitemnm=tmp_ar_item_nm[i];
                    dspcustpart=tmp_ar_item_model[i];
                    tempitem2=dspitem;
                }
            }
            newrow = tableku2.insertRow(-1);
            newcell = newrow.insertCell(0);            
            newText = document.createTextNode(dsptempso);
            newcell.appendChild(newText);         
            newcell = newrow.insertCell(1);
            newText = document.createTextNode(dspsodt);
            newcell.appendChild(newText);
            newcell = newrow.insertCell(2);
            newText = document.createTextNode(dspitem);
            newcell.appendChild(newText);

            newcell = newrow.insertCell(3);
            newText = document.createTextNode(dspitemnm);
            newcell.appendChild(newText);

            newcell = newrow.insertCell(4);
            newText = document.createTextNode(dspcustpart);
            newcell.appendChild(newText);

            newcell = newrow.insertCell(5);
            newText = document.createTextNode(numeral(tmp_ar_item_box[i]).format(','));
            newcell.appendChild(newText);  
            newcell.style.cssText = ''.concat('text-align: right;','cursor: pointer;');

            newcell = newrow.insertCell(6);
            newText = document.createTextNode(tmp_ar_item_qty[i]);
            newcell.appendChild(newText);
            newcell.style.cssText = 'text-align: right';
        }   
        let mrows = tableku2.getElementsByTagName("tr");   
    } else {
        alertify.message('No data selected');
    }    
});

$("#txfg_btn_save").click(function (e) {
    let mtxid = document.getElementById('txfg_txt_id').value;
    let mtxdt = document.getElementById('txfg_txt_custdate').value;
    let mtxinv = document.getElementById('txfg_txt_invno').value;
    let mtxinvdt = document.getElementById('txfg_txt_invdate').value;
    let mtxconsig = document.getElementById('txfg_consignee').value;
    let mtxreplace = document.getElementById('txfg_ckreplacement').checked;
    if(mtxreplace){
        mtxreplace=1;
    } else {
        mtxreplace=0;
    }
    let mtxtransport = document.getElementById('txfg_txt_transport').value;
    let mtxdescription = document.getElementById('txfg_txt_description').value;
    let mtxinvsmt = document.getElementById('txfg_txt_invsmt').value;
    let mtxremark = document.getElementById('txfg_txt_remark').value;
    let mtxvat = document.getElementById('txfg_ck_VAT').checked;
    let mtxkanbandlv = document.getElementById('txfg_ck_kanban').checked;
    let mtxnoncom = document.getElementById('txfg_ck_noncom').checked;
    if(mtxvat){
        mtxvat=1;
    } else {
        mtxvat=0;
    }
    if(mtxkanbandlv){
        mtxkanbandlv=1;
    } else {
        mtxkanbandlv=0;
    }
    if(mtxnoncom){
        mtxnoncom=1;
    } else {
        mtxnoncom=0;
    }
    if(mtxid.trim()==''){
        document.getElementById('txfg_txt_id').focus();
        alertify.warning('TX ID should not be blank');
        return;
    }
    if(scr_txfg_cust.trim()==''){
        alertify.warning('Please select Customer first');
        return;
    }
    if(mtxtransport.trim()==''){
        alertify.warning('Please select Transportation');
        return;
    }
    let ttlserials = txfg_ar_item_ser.length;
    if(ttlserials<=0){
        alertify.message('nothing to be saved');
        return;
    }
    if(txfg_isedit_mode){
        $.ajax({
            type: "post",
            url: "<?=base_url('DELV/edit')?>",
            data: {intxid: mtxid, intxdt: mtxdt, ininv: mtxinv, ininvsmt: mtxinvsmt, inconsig: mtxconsig, incus: scr_txfg_cust,
            inisreplace: mtxreplace, intrans: mtxtransport, indescr: mtxdescription, inremark: mtxremark, inisvat: mtxvat, inisknbndlv: mtxkanbandlv, inisnoncom: mtxnoncom,
            ina_ser: txfg_ar_item_ser, ina_qty: txfg_ar_item_qty, ina_si: txfg_ar_si, ina_so: txfg_ar_so, ininvdt:  mtxinvdt},
            dataType: "json",
            success: function (response) {
                switch(response[0].cd){
                    case '00':
                        alertify.warning(response[0].msg);
                        break;
                    case '11':
                        alertify.success(response[0].msg);
                        break;
                }
            }, error: function(xhr,xopt,xthrow){
                alertify.error(xthrow);
            }
        });
    } else {
        $.ajax({
            type: "post",
            url: "<?=base_url('DELV/set')?>",
            data: {intxid: mtxid, intxdt: mtxdt, ininv: mtxinv, ininvsmt: mtxinvsmt, inconsig: mtxconsig, incus: scr_txfg_cust,
            inisreplace: mtxreplace, intrans: mtxtransport, indescr: mtxdescription, inremark: mtxremark, inisvat: mtxvat, inisknbndlv: mtxkanbandlv, inisnoncom: mtxnoncom,
            ina_ser: txfg_ar_item_ser, ina_qty: txfg_ar_item_qty, ina_si: txfg_ar_si, ina_so: txfg_ar_so, ininvdt:  mtxinvdt},
            dataType: "json",
            success: function (response) {
                switch(response[0].cd){
                    case '00':
                        alertify.warning(response[0].msg);
                        break;
                    case '11':
                        alertify.success(response[0].msg);
                        break;
                }
            }, error: function(xhr,xopt,xthrow){
                alertify.error(xthrow);
            }
        });
    }
    
});
$("#txfg_btn_findmod").click(function (e) {    
    $('#TXFG_MODSAVED').modal('show');
});
$("#TXFG_MODSAVED").on('shown.bs.modal', function(){
    $("#txfg_txtxtsearch").focus();
});
$("#TXFG_MODSAVED").on('hidden.bs.modal', function(){
    $("#txfg_txtbl tbody").empty();
});
$("#txfg_txtxtsearch").keypress(function (e) { 
    if(e.which==13){
        let mkeys = $(this).val();
        let ms_by = document.getElementById('txfg_txsrchby').value;
        $("#txfg_txtbl tbody").empty();
        $.ajax({
            type: "get",
            url: "<?=base_url('DELV/search')?>",
            data: {inkey: mkeys, insearchby: ms_by  },
            dataType: "json",
            success: function (response) {
                let ttlrows = response.data.length;
                if(ttlrows>0){
                    let mydes = document.getElementById("txfg_txdivku");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("txfg_txtbl");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);                    
                    let tabell = myfrag.getElementById("txfg_txtbl");                    
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];//document.getElementById("rprod_tblwo").getElementsByTagName("tbody")[0];
                    let newrow, newcell, newText;
                    tableku2.innerHTML='';
                    let tominqty = 0;
                    let tempqty = 0;
                    let todisqty = 0;  
                    for (let i = 0; i<ttlrows; i++){                            
                        newrow = tableku2.insertRow(-1);
                        newcell = newrow.insertCell(0);            
                        newText = document.createTextNode(response.data[i].DLV_ID);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(1);
                        newText = document.createTextNode(response.data[i].DLV_DATE);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(2);
                        newText = document.createTextNode(response.data[i].DLV_DSCRPTN);
                        newcell.appendChild(newText);

                        newcell = newrow.insertCell(3);
                        newText = document.createTextNode(response.data[i].DLV_CUSTCD);
                        newcell.style.cssText = 'display:none';
                        newcell.appendChild(newText);                        

                        newcell = newrow.insertCell(4);
                        newText = document.createTextNode(response.data[i].MCUS_CUSNM);
                        newcell.appendChild(newText);

                        newcell = newrow.insertCell(5);
                        newText = document.createTextNode(response.data[i].DLV_INVNO);
                        newcell.appendChild(newText);                          

                        newcell = newrow.insertCell(6);
                        newText = document.createTextNode(response.data[i].DLV_SMTINVNO);
                        newcell.appendChild(newText);

                        newcell = newrow.insertCell(7);
                        newText = document.createTextNode(response.data[i].DLV_TRANS);
                        newcell.appendChild(newText);                        

                        newcell = newrow.insertCell(8);
                        newText = document.createTextNode(response.data[i].DLV_RMRK);
                        newcell.appendChild(newText); 

                        newcell = newrow.insertCell(9);
                        newText = document.createTextNode(response.data[i].MCUS_CURCD);
                        newcell.appendChild(newText); 

                        newcell = newrow.insertCell(10);
                        newText = document.createTextNode(response.data[i].MSTTRANS_TYPE);
                        newcell.style.cssText = 'display:none';
                        newcell.appendChild(newText);

                        newcell = newrow.insertCell(11);
                        newText = document.createTextNode(response.data[i].DLV_CONSIGN);
                        newcell.style.cssText = 'display:none';
                        newcell.appendChild(newText);

                        newcell = newrow.insertCell(12);
                        newText = document.createTextNode(response.data[i].DLV_RPLCMNT);
                        newcell.style.cssText = 'display:none';
                        newcell.appendChild(newText);

                        newcell = newrow.insertCell(13);
                        newText = document.createTextNode(response.data[i].DLV_VAT);
                        newcell.style.cssText = 'display:none';
                        newcell.appendChild(newText);

                        newcell = newrow.insertCell(14);
                        newText = document.createTextNode(response.data[i].DLV_KNBNDLV);
                        newcell.style.cssText = 'display:none';
                        newcell.appendChild(newText);

                        newcell = newrow.insertCell(15);
                        newText = document.createTextNode(response.data[i].DLV_NONCOM);
                        newcell.style.cssText = 'display:none';
                        newcell.appendChild(newText);

                        newcell = newrow.insertCell(16);
                        newText = document.createTextNode(response.data[i].DLV_CRTD);
                        newcell.style.cssText = 'display:none';
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(17);
                        newText = document.createTextNode(response.data[i].DLV_CRTDTM);
                        newcell.style.cssText = 'display:none';
                        newcell.appendChild(newText);

                        newcell = newrow.insertCell(18);
                        newText = document.createTextNode(response.data[i].DLV_USRID);
                        newcell.style.cssText = 'display:none';
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(19);
                        newText = document.createTextNode(response.data[i].DLV_LUPDT);
                        newcell.style.cssText = 'display:none';
                        newcell.appendChild(newText);

                        newcell = newrow.insertCell(20);
                        newText = document.createTextNode(response.data[i].DLV_APPRV);
                        newcell.style.cssText = 'display:none';
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(21);
                        newText = document.createTextNode(response.data[i].DLV_APPRVTM);
                        newcell.style.cssText = 'display:none';
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(22);
                        newText = document.createTextNode(response.data[i].DLV_POST);
                        newcell.style.cssText = 'display:none';
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(23);
                        newText = document.createTextNode(response.data[i].DLV_POSTTM);
                        newcell.style.cssText = 'display:none';
                        newcell.appendChild(newText);                        
                        newcell = newrow.insertCell(24);
                        newText = document.createTextNode(response.data[i].DLV_NOAJU);
                        newcell.style.cssText = 'display:none';
                        newcell.appendChild(newText);                        
                        newcell = newrow.insertCell(25);
                        newText = document.createTextNode(response.data[i].DLV_BCTYPE);
                        newcell.style.cssText = 'display:none';
                        newcell.appendChild(newText);    
                        newcell = newrow.insertCell(26);
                        newText = document.createTextNode(response.data[i].DLV_NOPEN);
                        newcell.style.cssText = 'display:none';
                        newcell.appendChild(newText);                
                        newcell = newrow.insertCell(27);
                        newText = document.createTextNode(response.data[i].DLV_DESTOFFICE);
                        newcell.style.cssText = 'display:none';
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(28);
                        newText = document.createTextNode(response.data[i].DLV_PURPOSE);
                        newcell.style.cssText = 'display:none';
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(29);
                        newText = document.createTextNode(response.data[i].DLV_INVDT);
                        newcell.style.cssText = 'display:none';
                        newcell.appendChild(newText);
                    }
                    function cclick_hnd(mrow){                        
                        let mtxid = mrow.getElementsByTagName('td')[0].innerText;                                                
                        let mtxdt = mrow.getElementsByTagName('td')[1].innerText;
                        let mdescript = mrow.getElementsByTagName('td')[2].innerText; 
                        scr_txfg_cust = mrow.getElementsByTagName('td')[3].innerText;
                        let mcusnm    = mrow.getElementsByTagName('td')[4].innerText;
                        let minv    = mrow.getElementsByTagName('td')[5].innerText;
                        let minvsmt    = mrow.getElementsByTagName('td')[6].innerText;                        
                        let mtrans    = mrow.getElementsByTagName('td')[7].innerText;                        
                        let mremark    = mrow.getElementsByTagName('td')[8].innerText;
                        let mcurrency    = mrow.getElementsByTagName('td')[9].innerText;
                        let mtrans_type  = mrow.getElementsByTagName('td')[10].innerText;
                        let mconsign  = mrow.getElementsByTagName('td')[11].innerText;
                        let mis_rpl  = mrow.getElementsByTagName('td')[12].innerText;
                        let mis_vat  = mrow.getElementsByTagName('td')[13].innerText;
                        let mis_knbndelv  = mrow.getElementsByTagName('td')[14].innerText;
                        let mis_noncom  = mrow.getElementsByTagName('td')[15].innerText;
                        let mcreated  = mrow.getElementsByTagName('td')[16].innerText;
                        let mcreatedtime  = mrow.getElementsByTagName('td')[17].innerText;
                        let mupdated  = mrow.getElementsByTagName('td')[18].innerText;
                        let mupdatedtime  = mrow.getElementsByTagName('td')[19].innerText;
                        let mapproved  = mrow.getElementsByTagName('td')[20].innerText;
                        let mapprovedtime  = mrow.getElementsByTagName('td')[21].innerText;
                        let mposted  = mrow.getElementsByTagName('td')[22].innerText;
                        let mpostedtime  = mrow.getElementsByTagName('td')[23].innerText;
                        let mnoaju  = mrow.getElementsByTagName('td')[24].innerText;
                        let mbctype  = mrow.getElementsByTagName('td')[25].innerText;
                        let mnopen  = mrow.getElementsByTagName('td')[26].innerText;
                        let mdestoffice  = mrow.getElementsByTagName('td')[27].innerText;
                        let mpurpose  = mrow.getElementsByTagName('td')[28].innerText;
                        let minvdt  = mrow.getElementsByTagName('td')[29].innerText;

                        document.getElementById("txfg_txt_id").focus();
                        document.getElementById("txfg_txt_id").value=mtxid;
                        document.getElementById("txfg_txt_id").readOnly=true;
                        document.getElementById('txfg_custname').value=mcusnm;
                        document.getElementById("txfg_curr").value=mcurrency;
                        document.getElementById("txfg_txt_description").value=mdescript;                        
                        document.getElementById('txfg_txt_invno').value=minv;
                        document.getElementById('txfg_txt_invsmt').value=minvsmt;
                        document.getElementById('txfg_txt_transport').value=mtrans;
                        document.getElementById('txfg_txt_transporttype').value=mtrans_type;
                        $("#txfg_txt_custdate").datepicker('update', mtxdt );
                        $("#txfg_txt_invdate").datepicker('update', minvdt );
                        if(mis_rpl=='1'){ document.getElementById('txfg_ckreplacement').checked=true; } else { document.getElementById('txfg_ckreplacement').checked=false; }
                        if(mis_vat=='1'){ document.getElementById('txfg_ck_VAT').checked=true; } else { document.getElementById('txfg_ck_VAT').checked=false; }
                        if(mis_knbndelv=='1'){ document.getElementById('txfg_ck_kanban').checked=true; } else { document.getElementById('txfg_ck_kanban').checked=false; }
                        if(mis_noncom=='1'){ document.getElementById('txfg_ck_noncom').checked=true; } else { document.getElementById('txfg_ck_noncom').checked=false; }
                        document.getElementById('txfg_txt_createdby').value=mcreated;
                        document.getElementById('txfg_txt_createdtime').value=mcreatedtime;
                        document.getElementById('txfg_txt_luby').value=( (mupdated=='null') || (mupdated.trim()=='') ? '':mupdated);
                        document.getElementById('txfg_txt_lutime').value=( (mupdatedtime=='null') || (mupdatedtime.trim()=='') ? '':mupdatedtime);
                        document.getElementById('txfg_txt_apprby').value=( (mapproved=='null') || (mapproved.trim()=='') ? '':mapproved );
                        document.getElementById('txfg_txt_apprtime').value=( (mapprovedtime=='null') || (mapprovedtime.trim()=='') ? '':mapprovedtime);
                        document.getElementById('txfg_txt_postedby').value=( (mposted=='null') || (mposted.trim()=='') ? '': mposted);
                        document.getElementById('txfg_txt_postedtime').value=( (mpostedtime=='null') || (mpostedtime.trim()=='') ? '': mpostedtime);
                        document.getElementById('txfg_txt_noaju').value=mnoaju.trim();
                        document.getElementById('txfg_cmb_bcdoc').value=mbctype;
                        
                        document.getElementById('txfg_txt_nopen').value=mnopen.trim();
                        document.getElementById("txfg_destoffice").value=mdestoffice;
                        if(mbctype=="27"){
                            $("#txfg_div_officedest").removeClass("d-none");
                        } else {
                            $("#txfg_div_officedest").addClass("d-none");
                        }
                        if((mposted=='null') || (mposted.trim()=='')){
                            if((mapproved=='null') || (mapproved.trim()=='')){
                                if((mcreated=='null') || (mcreated.trim()=='')){

                                } else {
                                    document.getElementById('txfg_status').value="Saved";
                                }
                            } else {
                                document.getElementById('txfg_status').value="Approved";
                            }
                        } else {
                            document.getElementById('txfg_status').value="Posted";
                        }
                        reninit_purposedlv(mbctype, mpurpose);
                        $("#TXFG_MODSAVED").modal('hide');
                        txfg_getconsignee(scr_txfg_cust,mconsign);
                        txfg_f_getdetail(mtxid);
                    }
                    let mrows = tableku2.getElementsByTagName("tr");
                    for(let x=0;x<mrows.length;x++){
                        tableku2.rows[x].onclick= function(){cclick_hnd(tableku2.rows[x])};
                    }
                    mydes.innerHTML='';                            
                    mydes.appendChild(myfrag);                
                } else {
                    alertify.warning('Transaction is not found');
                }
            }, error: function(xhr,xopt,xthrow){
                alertify.error(xthrow);
            }
        });
    }
});

function reninit_purposedlv(pbctype, ppurpose){
    if(pbctype=="27"){
        $("#txfg_div_officedest").removeClass("d-none");
    } else {
        $("#txfg_div_officedest").addClass("d-none");
    }
    $.ajax({
        type: "get",
        url: "<?=base_url('refceisa/MPurposeDLV/getbyPar')?>",
        data: {inid: pbctype},
        dataType: "json",
        success: function (response) {
            var tohtml = '';
            let ttlrows = response.length;
            if(ttlrows>1){
                for(let i=0;i<ttlrows;i++){
                    tohtml += '<option value="'+response[i].KODE_TUJUAN_PENGIRIMAN+'">'+response[i].URAIAN_TUJUAN_PENGIRIMAN+'</option>';
                }
            } else {                
                tohtml = '<option value="-">-</option>';
            }
            $("#txfg_cmb_purpose").html('');
            $("#txfg_cmb_purpose").html(tohtml);
            $("#txfg_cmb_purpose").val(ppurpose);
        }, error: function(xhr, xopt, xthrow){
            alertify.error(xthrow);
        }
    });
}

$("#txfg_btn_print").click(function (e) {
    let mtxid  = document.getElementById('txfg_txt_id').value;
    if(mtxid.trim()==''){
        document.getElementById('txfg_txt_id').focus();
        return;
    }
    $("#TXFG_MODPRINT").modal('show');
});
$("#txfg_btnprintseldocs").click(function (e) { 
    let txid = document.getElementById('txfg_txt_id').value;
    let mckdo = (document.getElementById('txfg_ckDO').checked) ? '1' : '0';
    let mckinv = (document.getElementById('txfg_ckINV').checked) ? '1' : '0';
    let mckpl = (document.getElementById('txfg_ckPL').checked) ? '1' : '0';
    
    if((mckdo+mckinv+mckpl)=='000'){
        alertify.message('Please select document first');return;
    }
    if(txid.trim()==''){
        alertify.warning('Please fill TX ID first');
        $("#TXFG_MODPRINT").modal('hide');
        document.getElementById('txfg_txt_id').focus();
        return;
    }
    Cookies.set('CKPDLV_NO', txid , {expires:365});
    Cookies.set('CKPDLV_FORMS', (mckdo+mckinv+mckpl) , {expires:365});
    window.open("<?=base_url('printdeliverydocs')?>" ,'_blank'); 
    $("#TXFG_MODPRINT").modal('hide');
});
function txfg_f_getdetail (ptxid) {
    txfg_isedit_mode =true;
    $.ajax({
        type: "get",
        url: "<?=base_url('DELV/getdetails')?>",
        data: {intxid: ptxid},
        dataType: "json",
        success: function (response) {
            let ttlcek = response.data.length;
            txfg_ttlcek = ttlcek;
            txfg_ar_item_ser.splice(0, txfg_ar_item_ser.length);
            txfg_ar_item_cd.splice(0, txfg_ar_item_cd.length);
            txfg_ar_item_nm.splice(0, txfg_ar_item_nm.length);
            txfg_ar_item_qty.splice(0, txfg_ar_item_qty.length);
            txfg_ar_item_model.splice(0, txfg_ar_item_model.length);
            txfg_ar_so.splice(0, txfg_ar_so.length);
            txfg_ar_sodt.splice(0, txfg_ar_sodt.length);
            for(let i=0;i<ttlcek;i++){
                txfg_ar_item_ser.push(response.data[i].DLV_SER);
                txfg_ar_item_cd.push(response.data[i].SER_ITMID);
                txfg_ar_item_nm.push(response.data[i].MITM_ITMD1);
                txfg_ar_item_qty.push(response.data[i].SISCN_SERQTY);
                txfg_ar_item_model.push(response.data[i].SI_MDL);
                txfg_ar_so.push(response.data[i].SISCN_DOCREFF);
                txfg_ar_sodt.push(response.data[i].SI_DOCREFFDT);                            
            }
            if(ttlcek>0){                
                let tabell = document.getElementById("txfg_tbltx");                    
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                let newrow, newcell, newText;        
                let tmp_ar_item_box = [];
                let tmp_ar_item_cd = [];
                let tmp_ar_item_nm = [];
                let tmp_ar_item_qty = [];
                let tmp_ar_item_model = [];
                let tmp_ar_si = [];
                let tmp_ar_so = [];
                let tmp_ar_sodt = [];
                tableku2.innerHTML='';
                let flagisexist =false;
                for (let i = 0; i<ttlcek; i++){            
                    if(tmp_ar_item_cd.length==0){
                        tmp_ar_item_box.push(1);
                        tmp_ar_item_cd.push(txfg_ar_item_cd[i]);
                        tmp_ar_item_nm.push(txfg_ar_item_nm[i]);
                        tmp_ar_item_qty.push(txfg_ar_item_qty[i]);
                        tmp_ar_item_model.push(txfg_ar_item_model[i]);
                        tmp_ar_si.push(txfg_ar_si[i]);
                        tmp_ar_so.push(txfg_ar_so[i]);
                        tmp_ar_sodt.push(txfg_ar_sodt[i]);
                    } else {
                        flagisexist =false;
                        for(let k=0;k<tmp_ar_so.length;k++){                    
                            if(tmp_ar_so[k]==txfg_ar_so[i] && tmp_ar_item_cd[k]==txfg_ar_item_cd[i] && tmp_ar_si[k]==txfg_ar_si[i] && tmp_ar_item_qty[k]==txfg_ar_item_qty[i] ){                        
                                tmp_ar_item_box[k]=tmp_ar_item_box[k]+1;                        
                                flagisexist=true;break;
                            }
                        }
                        if(flagisexist){                                       
                        } else {
                            tmp_ar_item_box.push(1);
                            tmp_ar_item_cd.push(txfg_ar_item_cd[i]);
                            tmp_ar_item_nm.push(txfg_ar_item_nm[i]);
                            tmp_ar_item_qty.push(txfg_ar_item_qty[i]);
                            tmp_ar_item_model.push(txfg_ar_item_model[i]);
                            tmp_ar_si.push(txfg_ar_si[i]);
                            tmp_ar_so.push(txfg_ar_so[i]);
                            tmp_ar_sodt.push(txfg_ar_sodt[i]);
                        }                
                    }            
                }
                let tempso, tempitem,tempitem2; 
                let dsptempso, dspitem,dspsodt, dspitemnm, dspcustpart; 
                for(let i=0;i<tmp_ar_item_cd.length;i++){
                    if(tempso!=tmp_ar_so[i] && tempitem != tmp_ar_item_cd[i]){
                        tempso=tmp_ar_so[i]; tempitem = tmp_ar_item_cd[i];
                        dsptempso = tmp_ar_so[i];
                        dspitem =tmp_ar_item_cd[i];
                        dspsodt=tmp_ar_sodt[i];
                        dspitemnm=tmp_ar_item_nm[i];
                        dspcustpart=tmp_ar_item_model[i];
                        tempitem2='';
                    } else {
                        dsptempso = '';
                        dspitem ='';
                        dspsodt='';
                        dspitemnm='';
                        dspcustpart='';                                                
                        if( (tempitem != tmp_ar_item_cd[i]) && tempitem2=='' ){
                            dspitem =tmp_ar_item_cd[i]; 
                            dspitemnm=tmp_ar_item_nm[i];
                            dspcustpart=tmp_ar_item_model[i];
                            tempitem2=dspitem;                            
                        }
                    }
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0);            
                    newText = document.createTextNode(dsptempso);
                    newcell.appendChild(newText);         
                    newcell = newrow.insertCell(1);
                    newText = document.createTextNode(dspsodt);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(2);
                    newText = document.createTextNode(dspitem);
                    newcell.appendChild(newText);

                    newcell = newrow.insertCell(3);
                    newText = document.createTextNode(dspitemnm);
                    newcell.appendChild(newText);

                    newcell = newrow.insertCell(4);
                    newText = document.createTextNode(dspcustpart);
                    newcell.appendChild(newText);

                    newcell = newrow.insertCell(5);
                    newText = document.createTextNode(numeral(tmp_ar_item_box[i]).format(','));
                    newcell.style.cssText = ''.concat('text-align: right;','cursor: pointer;' , 'border: 1px solid #00C935;','border-style:double;');
                    newcell.title="See detail data"
                    newcell.appendChild(newText);  
                    

                    newcell = newrow.insertCell(6);
                    newText = document.createTextNode(Number(tmp_ar_item_qty[i]));
                    newcell.appendChild(newText);
                    newcell.style.cssText = 'text-align: right';
                }
                let mrows = tableku2.getElementsByTagName("tr");      
                // let crow, ccell ,chandler;       
                function cgetval(prow, pc_y){
                    let tc_so = prow.getElementsByTagName("td")[0].innerText; //tc = table cells
                    let tc_itmcd = prow.getElementsByTagName("td")[2].innerText;
                    let tc_itmnm = prow.getElementsByTagName("td")[3].innerText;
                    let tc_itmqty = prow.getElementsByTagName("td")[6].innerText;
                    if(tc_so.trim()==''){
                        let tmptxt,tmptxt2,tmptxt3;  
                        for(let b=pc_y;b>=0;b--){
                            tmptxt =tableku2.rows[b].cells[0].innerText;                            
                            tmptxt2 =tableku2.rows[b].cells[2].innerText;
                            tmptxt3 =tableku2.rows[b].cells[3].innerText;
                            if(tc_itmcd.trim()=='' && tmptxt2.trim()!=''){
                                tc_itmcd=tmptxt2.trim();
                                tc_itmnm=tmptxt3.trim();
                            }
                            if(tmptxt.trim()!=''){                                
                                tc_so= tmptxt.trim();
                                break;
                            }
                        }
                    }
                    document.getElementById('txfg_detser_so').value=tc_so;
                    document.getElementById('txfg_detser_itmcd').value=tc_itmcd;
                    document.getElementById('txfg_detser_itmnm').value=tc_itmnm;
                    document.getElementById('txfg_detser_itmqty').value=tc_itmqty;
                    let ttldetail = txfg_ar_so.length;
                    let tbldet = document.getElementById("txfg_tbldetailser");                    
                    let tbldet_b = tbldet.getElementsByTagName("tbody")[0];
                    tbldet_b.innerHTML="";
                    for(let b=0;b<ttldetail;b++){                        
                        if(txfg_ar_so[b].trim()==tc_so && txfg_ar_item_cd[b].trim() == tc_itmcd && numeral(txfg_ar_item_qty[b]).value() == tc_itmqty){ //
                            newrow = tbldet_b.insertRow(-1);
                            newcell = newrow.insertCell(0);            
                            newText = document.createTextNode(txfg_ar_item_ser[b]);
                            newcell.appendChild(newText);         
                            newcell = newrow.insertCell(1);
                            newText = document.createTextNode(numeral(txfg_ar_item_qty[b]).format('0,0'));
                            newcell.style.cssText="text-align:right";
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(2);
                            newText = document.createElement('I');
                            newText.classList.add("fas", "fa-trash","text-danger");
                            newcell.style.cssText = "cursor:pointer;text-align:center";
                            newcell.appendChild(newText);
                        }
                    }
                    function cgetvaldetser(prow){
                        let apprby = document.getElementById("txfg_txt_apprby").value;
                        // if(apprby.trim()!=''){
                        //     alertify.warning("Could not be deleted, because it has been approved");
                        //     return;
                        // }
                        let mser = prow.getElementsByTagName("td")[0].innerText;
                        let konfr = confirm(`Are you sure want to cancel ${mser} ? `);                        
                        if(konfr){
                            $.ajax({
                                type: "post",
                                url: "<?=base_url('DELV/removeun')?>",
                                data: {inser: mser},
                                dataType: "json",
                                success: function (response) {
                                    if(response.status[0].cd=='0'){
                                        alertify.warning(response.status[0].msg);
                                    } else {
                                        alertify.message(response.status[0].msg);
                                        $('#TXFG_DETAILSER').modal('hide');
                                        let mtxid = document.getElementById("txfg_txt_id").value;
                                        txfg_f_getdetail(mtxid);
                                    }
                                }, error: function(xhr,xopt,xthrow){
                                    alertify.error(xthrow);
                                }
                            });
                        }
                    }
                    let mrowsdet = tbldet_b.getElementsByTagName("tr");
                    for(let x=0;x<mrowsdet.length;x++){
                        tbldet_b.rows[x].cells[2].onclick = function(){ cgetvaldetser(tbldet_b.rows[x]) };
                    }
                    $('#TXFG_DETAILSER').modal('show');
                }
                for(let x=0;x<mrows.length;x++){
                    tableku2.rows[x].cells[5].onclick = function(){ cgetval(tableku2.rows[x] , x) };
                }
            } else {
                alertify.message('No data selected');
            }  
        }, error: function(xhr,xopt,xthrow){
            alertify.error(xthrow);
        }
    });
}
});
$("#TXFG_CUSTOMSMOD").on('hidden.bs.modal', function(){
    let msj = document.getElementById('txfg_txt_id').value;
    if(msj.trim()== ''){
        document.getElementById('txfg_txt_id').focus();
        //alertify.message('Please fill the TX ID');
        return;   
    }
    let mbctype = document.getElementById('txfg_cmb_bcdoc').value;
    if(mbctype.trim()==''){
        document.getElementById('txfg_cmb_bcdoc').focus();
        return;
    }
    let mnoaju =document.getElementById('txfg_txt_noaju').value;
    let mnopen = document.getElementById('txfg_txt_nopen').value;    
    let mdestoffice = document.getElementById('txfg_destoffice').value;    
    let mpurpose = document.getElementById('txfg_cmb_purpose').value;        
    $.ajax({
        type: "get",
        url: "<?=base_url('DELV/change')?>",
        data: {inid : msj, inbctype : mbctype, innopen: mnopen, inaju: mnoaju, indestoffice: mdestoffice , inpurpose: mpurpose },
        dataType: "json",
        success: function (response) {
            if(response[0].cd='11'){
                alertify.success(response[0].msg);
            } else {
                //alertify.error(response[0].msg);
            }
        }, error: function(xhr, xopt, xthrow){
            alertify.error(xthrow);
        }
    });
});
$("#txfg_btn_appr").click(function (e) { 
    let mtxid = document.getElementById('txfg_txt_id').value;
    if(mtxid.trim()==''){
        document.getElementById('txfg_txt_id').focus();
        return;
    }
    let konf = confirm('Are you sure ?');
    if(!konf){        
        return;
    }
    $.ajax({
        type: "post",
        url: "<?=base_url('DELV/approve')?>",
        data: {inid: mtxid },
        dataType: "json",
        success: function (response) {
            if(response[0].cd=="0"){
                alertify.warning(response[0].msg);
            } else {
                alertify.success(response[0].msg);
                let appr = document.getElementById("footerinfo_user").innerText;
                appr = appr.substr(3,appr.length);
                document.getElementById("txfg_txt_apprby").value=appr;
                document.getElementById("txfg_txt_apprtime").value=response[0].approved_time;
            }
        }, error: function(xhr,xopt,xthrow){
            alertify.error(xthrow);
        }
    });    
});
function txfg_e_posting(){
    let msj = document.getElementById('txfg_txt_id').value;    
    let mcustomsdate = document.getElementById("txfg_txt_custdate").value;    
    $.ajax({
        type: "post",
        url: "DELV/posting",
        data: {insj: msj, incustdate: mcustomsdate},
        dataType: "json",
        success: function (response) {
            $("#TXFG_PROGRESS").modal('hide');
            if(response.status[0].cd=='1'){
                alertify.success(response.status[0].msg);
                let appr = document.getElementById("footerinfo_user").innerText;
                appr = appr.substr(3,appr.length);
                document.getElementById("txfg_txt_postedby").value = appr;
                document.getElementById("txfg_txt_postedtime").value=response.status[0].time;
            } else if(response.status[0].cd=='11') {
                alertify.success(response.status[0].msg);
                document.getElementById("txfg_txt_postedby").value = response.status[0].user;
                document.getElementById("txfg_txt_postedtime").value=response.status[0].time;
            } else {
                alertify.warning(response.status[0].msg);
            }
        }, error: function(xhr, xopt, xthrow){
            alertify.error(xthrow);
        }
    });
}

$("#txfg_btn_post").click(function (e) {    
    let mapprovby = document.getElementById("txfg_txt_apprby").value;
    if(mapprovby.trim()==''){
        alertify.warning("Please approve first !");
        return;
    }
    $("#TXFG_PROGRESS").modal({backdrop: 'static', keyboard: false});        
});
</script>