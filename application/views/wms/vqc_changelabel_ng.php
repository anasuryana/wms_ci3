<div style="padding: 5px">
	<div class="col-md-12 order-md-1">        
        <div class="row">   
            <div class="col-md-5 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text" >Old Reff No</span>
                    </div>
                    <input type="text" class="form-control" id="qc_cng_lbl_oldreff" required>
                   
                </div>
            </div> 
            <div class="col-md-5 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text" >Job Number</span>
                    </div>
                    <input type="text" class="form-control" id="qc_cng_lbl_oldjob" required readonly>
                </div>
            </div>
            <div class="col-md-2 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text" >Qty</span>
                    </div>
                    <input type="text" class="form-control" id="qc_cng_lbl_oldqty" required readonly>
                </div>
            </div>
        </div>
        <div class="row">            
            <div class="col-md-12 mb-3 pr-1">
                <h3>Transaction History</h3>
                <div class="table-responsive" id="qc_cng_lbl_divku">
                    <table id="qc_cng_lbl_tbl" class="table table-striped table-bordered table-sm table-hover" style="font-size:75%">
                        <thead class="thead-light">
                            <tr>
                                <th  class="align-middle">Reff No</th>
                                <th  class="align-middle">Item Code</th>
                                <th  class="align-middle">FORM</th>
                                <th  class="align-middle">Document</th>
                                <th  class="text-right">Qty</th>
                                <th  class="align-middle">Line ID</th>                                
                                <th  class="align-middle">Time</th>
                                <th  class="align-middle">PIC</th>
                            </tr>                           
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">            
            <div class="col-md-5 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text" >New Reff No</span>
                    </div>
                    <input type="text" class="form-control" id="qc_cng_lbl_newreff" required>                   
                </div>
            </div>
            <div class="col-md-5 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text" >Job Number</span>
                    </div>
                    <input type="text" class="form-control" id="qc_cng_lbl_newjob" required readonly>
                </div>
            </div>
            <div class="col-md-2 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text" >Qty</span>
                    </div>
                    <input type="text" class="form-control" id="qc_cng_lbl_newqty" required readonly>
                </div>
            </div>           
        </div>
        <div class="row">            
            <div class="col-md-5 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text" >New Reff No 2</span>
                    </div>
                    <input type="text" class="form-control" id="qc_cng_lbl_newreff2" required>                   
                </div>
            </div>
            <div class="col-md-5 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text" >Job Number</span>
                    </div>
                    <input type="text" class="form-control" id="qc_cng_lbl_newjob2" required readonly>
                </div>
            </div>
            <div class="col-md-2 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text" >Qty</span>
                    </div>
                    <input type="text" class="form-control" id="qc_cng_lbl_newqty2" required readonly>
                </div>
            </div>           
        </div>
        <div class="row">
            <div class="col-md-12 mb-1 text-center">
                <div class="btn-group btn-group-sm">
                    <button title="New Entry" type="button" class="btn btn-outline-primary" id="qc_cng_lbl_btn_new"><i class="fas fa-file"></i></button>                    
                    <button title="Save" type="button" class="btn btn-outline-primary" id="qc_cng_lbl_btn_save"><i class="fas fa-save"></i></button>                                        
                </div>
            </div>
        </div>        
    </div>
</div>
<script>
    $("#qc_cng_lbl_btn_new").click(function (e) {         
        document.getElementById('qc_cng_lbl_oldreff').value='';
        document.getElementById('qc_cng_lbl_oldjob').value='';
        document.getElementById('qc_cng_lbl_oldqty').value='';
        document.getElementById('qc_cng_lbl_newreff').value='';
        document.getElementById('qc_cng_lbl_newjob').value='';
        document.getElementById('qc_cng_lbl_newqty').value='';
        document.getElementById('qc_cng_lbl_newreff2').value='';
        document.getElementById('qc_cng_lbl_newjob2').value='';
        document.getElementById('qc_cng_lbl_newqty2').value='';
    });
    $("#qc_cng_lbl_oldreff").keypress(function (e) { 
        if(e.which==13){
            let moldreff = $(this).val();
            $.ajax({
                type: "get",
                url: "<?=base_url('SER/getproperties')?>",
                data: {inid:moldreff },
                dataType: "json",
                success: function (response) {
                    
                }, error(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    });
</script>