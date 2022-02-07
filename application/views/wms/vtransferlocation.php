<div style="padding: 10px">
	<div class="container-xxl">        
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">Document</label>                    
                    <input type="text" class="form-control" id="tfid_txt_doc" placeholder="<<may auto number>>">                    
                </div>
            </div>           
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">Date</label>                    
                    <input type="text" class="form-control" id="tfid_txt_date" >                    
                </div>
            </div>           
        </div>       
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">ID</label>                    
                    <input type="text" class="form-control" id="tfid_txt_ID" >
                </div>
            </div>           
        </div>       
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">QTY</label>                    
                    <input type="text" class="form-control" id="tfid_txt_qty" readonly >
                </div>
            </div>           
        </div>       
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">From Rack</label>                    
                    <input type="text" class="form-control" id="tfid_txt_fromrack" readonly >
                </div>
            </div>           
        </div>       
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">To Warehouse</label>                    
                    <input type="text" class="form-control" id="tfid_txt_to_wh">
                </div>
            </div>           
        </div>       
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">To Rack</label>                    
                    <input type="text" class="form-control" id="tfid_txt_to_rack">
                </div>
            </div>           
        </div>       
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="btn-group btn-block btn-group-sm">                    
                    <button class="btn btn-success" id="tfid_btn_save"><i class="fas fa-save"></i> Save</button>                    
                </div>
            </div>            
        </div>      
    </div>
</div>
<script>
    $("#tfid_txt_date").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });
    $("#tfid_txt_date").datepicker('update', moment().format("YYYY-MM-DD"));
    
</script>