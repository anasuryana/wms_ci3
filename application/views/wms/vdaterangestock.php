<div style="padding: 10px">
	<div class="col-md-12 order-md-1">
        <div class="row">
            <div class="col-md-2 mb-1">
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <label class="input-group-text">Periode</label>
                    </div>
                    <input type="text" class="form-control" id="cus_txtcuscd">
                    <div class="input-group-append">
                        <button title="Find Customer" class="btn btn-outline-secondary" type="button" id="cus_btnformodcus"><i class="fas fa-search"></i></button>                        
                    </div> 
                </div>
            </div>
            <div class="col-md-2 mb-1">
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <label class="input-group-text">Currency</label>
                    </div>
                    <input type="text" class="form-control" id="cus_txtcur" maxlength="4">
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <label class="input-group-text">Name</label>
                    </div>
                    <input type="text" class="form-control" id="cus_txtnm">
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">
                    <div class="input-group-prepend">
                        <label class="input-group-text">Abbr Name</label>
                    </div>
                    <input type="text" class="form-control" id="cus_txtabbrnm">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <div class="input-group-prepend">
                        <label class="input-group-text">TPB Type</label>
                    </div>
                    <select id="cus_typetpb" class="form-control">
                        <?php foreach($ltpb_type as $r) {?>
                            <option value="<?=$r['KODE_JENIS_TPB']?>"><?=$r['URAIAN_JENIS_TPB']?></option>
                        <?php }?>
                    </select>
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <div class="input-group-prepend">
                        <label class="input-group-text">TPB No.</label>
                    </div>
                    <input type="text" class="form-control" id="cus_txttpbno">             
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <div class="input-group-prepend">
                        <label class="input-group-text">NPWP</label>
                    </div>
                    <input type="text" class="form-control" id="cus_txtnpwp">             
                </div>
            </div>
        </div>
        <div class="row">            
            <div class="col-md-12 mb-3">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Address</span>
                    </div>
                    <textarea class="form-control" id="cus_taaddr" aria-label="Address"></textarea>
                </div>
            </div>
        </div>
        <div class="row">				
            <div class="col-md-12 mb-3 text-center">                
                <div class="btn-group btn-group-sm">
                    <button title="New" id="cus_btnnew" class="btn btn-primary" ><i class="fas fa-file"></i></button>
                    <button title="Save" id="cus_btnsave" class="btn btn-primary" ><i class="fas fa-save"></i></button>
                </div>
            </div>
        </div>
        <div class="row">				
            <div class="col-md-12 mb-1">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4 class="my-0 font-weight-normal">Consignee</h4>
                    </div>
                    <div class="card-body">
                        <div class="col-md-12 order-md-0">
                            <div class="row">
                                <div class="col-md-12 mb-1">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Name</span>
                                        </div>
                                        <input type="text" class="form-control" id="cusconsig_txtname" required >                                       
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-1">
                                    <div class="btn-group btn-group-sm">
                                        <button title="New" class="btn btn-primary" type="button" id="cusconsig_btnnew"><i class="fas fa-file"></i></button>
                                        <button title="Save" class="btn btn-primary" type="button" id="cusconsig_btnsave"><i class="fas fa-save"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-0">
                                    <div class="table-responsive">
                                        <table id="cusconsig_tbl" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer">
                                            <thead class="thead-light">
                                                <tr>                                                    
                                                    <th>Name</th>
                                                    <th>...</th>                                                    
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
<div class="modal fade" id="CUS_MODCUS">
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
                        <input type="text" class="form-control" id="cus_txtsearch" maxlength="15" required placeholder="...">                        
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-prepend">
                            <span class="input-group-text" >Search by</span>
                        </div>
                        <select id="cus_srchby" class="form-control">
                            <option value="cd">Code</option>
                            <option value="nm">Name</option>                            
                            <option value="ab">Abbr Name</option>
                            <option value="ad">Address</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col text-right">
                    <span class="badge badge-info" id="lblinfo_tblcus"></span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive">
                        <table id="cus_tbl" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer">
                            <thead class="thead-light">
                                <tr>
                                    <th>Customer Code</th>
                                    <th>Currency</th>
                                    <th>Name</th>
                                    <th>Abbr Name</th>
                                    <th>Address</th>
                                    <th class="d-none">TPB Type ID</th>
                                    <th>TPB Type</th>
                                    <th>NPWP</th>
                                    <th>TPB No.</th>
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