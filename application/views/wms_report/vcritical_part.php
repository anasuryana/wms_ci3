<div style="padding: 5px" >
	<div class="container-fluid">
        <div class="row">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text" title="issue date">Created Date</label>
                    <input type="text" class="form-control" id="criticpart_txt_date" readonly>
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary" id="criticpart_btn_new" title="New" onclick="criticpart_btn_new_eC()"><i class="fas fa-file"></i></button>
                    <button class="btn btn-outline-success" id="criticpart_btn_save" title="Save as Sepreadsheet" onclick="criticpart_btn_save_eC()"><i class="fas fa-file-excel"></i></button>                    
                </div>
            </div>            
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="criticpart_home-tab" data-bs-toggle="tab" data-bs-target="#criticpart_tabRM" type="button" role="tab" aria-controls="home" aria-selected="true">Raw Material</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="criticpart_profile-tab" data-bs-toggle="tab" data-bs-target="#criticpart_tabFG" type="button" role="tab" aria-controls="profile" aria-selected="false">Finished Goods</button>
                </li>                
            </ul>
                <div class="tab-content" id="criticpart_myTabContent">
                    <div class="tab-pane fade show active" id="criticpart_tabRM" role="tabpanel" aria-labelledby="home-tab">
                        <div class="container-fluid">                        
                            <div class="row">
                                <div class="col-md-12 mb-1 table-responsive">
                                    <div id="vcritical_partcd"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="criticpart_tabFG" role="tabpanel" aria-labelledby="contact-tab">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12 mb-1 table-responsive">
                                    <div id="vcritical_fg"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>
<script>
    function criticpart_btn_new_eC() {
        $("#criticpart_txt_date").datepicker('update', new Date())
        criticpart_sso_part.setData([[],[],[],[],[]])
        criticpart_sso_fg.setData([[],[],[],[],[]])
    }
    $("#criticpart_txt_date").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    })
    $("#criticpart_txt_date").datepicker('update', new Date())
    var mpurordata = [
       [''],       
    ];  
    var criticpart_sso_part = jspreadsheet(document.getElementById('vcritical_partcd'), {
        data:mpurordata,
        columns: [       
            {
                type: 'text',
                title:'Part Code',
                width:150,
                align: 'left'
            },         
            
        ],    
    });    
    var criticpart_sso_fg = jspreadsheet(document.getElementById('vcritical_fg'), {
        data:mpurordata,
        columns: [       
            {
                type: 'text',
                title:'Assy code',
                width:150,
                align: 'left'
            },         
            
        ],    
    });    
</script>