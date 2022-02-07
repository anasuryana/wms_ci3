<div style="padding: 5px">
	<div class="container-fluid">  
        <div class="row">
            <div class="col-md-3 mb-1">
                <h2 ><span class="badge bg-info">1<i class="fas fa-hand-point-right" ></i></span> <span class="badge bg-info">From</span></h2>
            </div>
        </div>      
        <div class="row">   
            <div class="col-md-3 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Old Reff No</span>                    
                    <input type="text" class="form-control" id="fgtowip_oldreff" onkeypress="fgtowip_oldreff_eKP(event)" required>                   
                </div>
            </div> 
            <div class="col-md-4 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Job Number</span>                    
                    <input type="text" class="form-control" id="fgtowip_oldjob" required readonly>
                </div>
            </div>
            <div class="col-md-3 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Item Code</span>                    
                    <input type="text" class="form-control" id="fgtowip_olditemcd" required readonly>
                </div>
            </div>
            <div class="col-md-2 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >Qty</span>
                    <input type="text" class="form-control" id="fgtowip_oldqty" required readonly>
                </div>                
            </div>
        </div>        
        <div class="row">
            <div class="col-md-12 mb-3 pr-1">
                <h3>Transaction History</h3>
                <div class="table-responsive" id="fgtowip_divku">
                    <table id="fgtowip_tbl" class="table table-striped table-bordered table-sm table-hover" style="font-size:75%">
                        <thead class="table-light">
                            <tr>                               
                                <th  class="align-middle">FORM</th>
                                <th  class="align-middle">Warehouse</th>
                                <th  class="align-middle">Location</th>
                                <th  class="align-middle">Document</th>
                                <th  class="text-end">Qty</th>
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
            <div class="col-md-3 mb-1">
                <h2 ><span class="badge bg-info">2 <i class="fas fa-hand-point-right" ></i></span> <span class="badge bg-info">To</span></h2>
            </div>           
        </div>      
        <div id="fgtowip_div_rnm">
            <div class="row">
                <div class="col-md-4 mb-1 pr-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >SPQ</span>
                        <input type="text" class="form-control" id="fgtowip_spq" required>                        
                    </div>
                </div>                              
                <div class="col-md-8 mb-1 pr-1">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >Total Label</span>
                        <input type="text" class="form-control" id="fgtowip_newreff" required readonly>                        
                    </div>
                </div>
            </div>           
        </div>        
       
        <div class="row">
            <div class="col-md-12 mb-1 text-center">
                <div class="btn-group btn-group-sm">
                    <button title="New Entry" type="button" class="btn btn-outline-primary" id="fgtowip_btn_new" onclick="fgtowip_btn_new_eC()"><i class="fas fa-file"></i></button>                    
                    <button title="Save" type="button" class="btn btn-outline-primary" id="fgtowip_btn_save"><i class="fas fa-save"></i></button>                                        
                    <button title="Print" type="button" class="btn btn-outline-primary" id="fgtowip_btn_print"><i class="fas fa-print"></i></button>                                        
                </div>
            </div>
        </div>        
    </div>
</div>
<input type="hidden" id="fgtowip_temp_oldreff">
<script>
    function fgtowip_oldreff_eKP(e){
        if(e.which==13){
            const txtreffno = document.getElementById('fgtowip_oldreff')
            txtreffno.readOnly = true
            $.ajax({
                type: "get",
                url: "<?=base_url('SER/prepare_fgtowip')?>",
                data: {reffno : txtreffno.value},
                dataType: "json",
                success: function (response) {
                    if(response.data.length>0){
                        if(response.data[0].SER_ITMID.includes('KD') || response.data[0].SER_ITMID.includes('ASP')){
                            if(response.lastwh.code==='AFWH3'){
                                document.getElementById('fgtowip_oldjob').value = response.data[0].SER_DOC
                                document.getElementById('fgtowip_olditemcd').value = response.data[0].SER_ITMID
                                document.getElementById('fgtowip_oldqty').value = numeral(response.data[0].SER_QTY).format(',')
                                document.getElementById('fgtowip_spq').focus()
                                const ttlrowstx = response.tx.length;
                            } else {
                                txtreffno.readOnly = false
                                alertify.warning("The item is not found in AFWH3, we could not process")
                            }
                        } else {
                            alertify.warning("Could not process, KD or ASP only")
                            txtreffno.readOnly = false
                            txtreffno.value = ''
                        }
                    } else {
                        alertify.message(response.status[0].msg)
                    }
                }, error : function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                    txtreffno.readOnly = false
                }
            });
        }
    }

    function fgtowip_btn_new_eC(){
        const txtoldreff = document.getElementById('fgtowip_oldreff')
        txtoldreff.value = ''
        txtoldreff.readOnly = false
        txtoldreff.focus()
        document.getElementById('fgtowip_oldjob').value = ''
        document.getElementById('fgtowip_olditemcd').value = ''
        document.getElementById('fgtowip_oldqty').value = ''        
    }
</script>