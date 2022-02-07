<div style="padding: 5px">
	<div class="container-fluid">  
        <div class="row">
            <div class="col-md-3 mb-1">
                <h2 ><span class="badge bg-info">1 <i class="fas fa-hand-point-right" ></i></span> <span class="badge bg-info">From</span></h2>
            </div>            
        </div>      
        <div class="row">   
            <div class="col-md-3 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Old Reff No</span>                    
                    <input type="text" class="form-control" id="change_rank_oldreff" onkeypress="change_rank_oldreff_eKP(event)" required>
                </div>
            </div> 
            <div class="col-md-4 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Job Number</span>                    
                    <input type="text" class="form-control" id="change_rank_oldjob" required readonly>
                </div>
            </div>
            <div class="col-md-3 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" >Item Code</span>                    
                    <input type="text" class="form-control" id="change_rank_olditemcd" required readonly>
                </div>
            </div>
            <div class="col-md-2 mb-1 pr-1">
                <div class="input-group input-group-sm mb-1">                    
                    <span class="input-group-text" title="Production Date" >Rank</span>
                    <input type="text" class="form-control" id="change_rank_grade" required readonly>
                </div>
            </div>
        </div>       
        <div class="row">
            <div class="col-md-3 mb-1">
                <h2 ><span class="badge bg-info">2 <i class="fas fa-hand-point-right" ></i></span> <span class="badge bg-info">To</span></h2>
            </div>           
        </div>      
        <div id="change_rank_div_rnm">
            <div class="row">
                <div class="col-md-12 mb-1 pr-1">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >New Rank</span>
                        <input type="text" class="form-control" id="change_rank_newrank" required maxlength="10">
                    </div>
                </div>
            </div>
        </div>
       
        <div class="row">
            <div class="col-md-12 mb-1 text-center">
                <div class="btn-group btn-group-sm">
                    <button title="New Entry" type="button" class="btn btn-outline-primary" id="change_rank_btn_new" onclick="change_rank_btn_new_eCK()"><i class="fas fa-file"></i></button>                    
                    <button title="Save" type="button" class="btn btn-outline-primary" id="change_rank_btn_save" onclick="change_rank_btn_save_eCK()"><i class="fas fa-save"></i></button>                                        
                    <button title="Print" type="button" class="btn btn-outline-primary" id="change_rank_btn_print"><i class="fas fa-print"></i></button>                                        
                </div>
            </div>
        </div>        
    </div>
</div>
<script>
    function change_rank_btn_save_eCK() {
        const oldreff = document.getElementById('change_rank_oldreff').value.trim()
        const olditem = document.getElementById('change_rank_olditemcd').value.trim()
        const oldrank = document.getElementById('change_rank_grade').value.trim()
        const newrank = document.getElementById('change_rank_newrank').value.trim()
        if(olditem.length<=3) {
            document.getElementById('change_rank_oldreff').focus()
            alertify.message('Reff No is required')
            return
        }
        document.getElementById('change_rank_btn_save').disabled = true
        if (confirm("Are you sure ?")) {            
            $.ajax({
                type: "POST",
                url: "<?=base_url('SER/convert_rank')?>",
                data: {oldreff: oldreff, newrank: newrank, oldrank: oldrank},
                dataType: "JSON",
                success: function (response) {
                    if (response.status[0].cd===1) {
                        alertify.success(response.status[0].msg)   
                        change_rank_btn_new_eCK()
                    } else {
                        alertify.message(response.status[0].msg)
                    }
                }, error:function(xhr,xopt,xthrow){
                    alertify.error(xthrow)
                    document.getElementById('change_rank_btn_save').disabled = false
                }
            })
        }
    }
    function change_rank_btn_new_eCK() {
        const oldreff = document.getElementById('change_rank_oldreff')
        oldreff.value = ''
        oldreff.readOnly = false
        oldreff.focus()
        document.getElementById('change_rank_oldjob').value = ''
        document.getElementById('change_rank_olditemcd').value = ''
        document.getElementById('change_rank_grade').value = ''
        document.getElementById('change_rank_newqty').value = ''
    }
    function change_rank_oldreff_eKP(e) {
        if ( e.key === 'Enter') {
            const inid = document.getElementById('change_rank_oldreff').value.trim()
            if( inid.length <5 ) {
                alertify.warning(`invalid reff no`)
                return
            }
            document.getElementById('change_rank_oldreff').readOnly = true
            $.ajax({
                type: "GET",
                url: "<?=base_url('SER/properties_c_rank')?>",
                data: {inid : inid},
                dataType: "JSON",
                success: function (response) {
                    if(response.status[0].cd === '1') {
                        document.getElementById('change_rank_oldjob').value = response.data[0].SER_DOC
                        document.getElementById('change_rank_olditemcd').value = response.data[0].SER_ITMID
                        document.getElementById('change_rank_grade').value = response.data[0].SER_GRADE
                        document.getElementById('change_rank_newrank').focus()
                    } else {
                        alertify.message(response.status[0].msg)
                        document.getElementById('change_rank_oldreff').readOnly = false
                    }
                }, error:function(xhr,xopt,xthrow){
                    alertify.error(xthrow)
                    document.getElementById('change_rank_oldreff').readOnly = false
                }
            });
        }
    }
</script>