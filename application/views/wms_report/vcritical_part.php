<div style="padding: 5px" >
	<div class="container-fluid">
        <div class="row">
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text" title="Business Group">BG</label>
                    <select class="form-select" id="criticalpart_cmb_bg">
                        <?php foreach($lgroup as $r) { ?>
                            <option value="<?=trim($r->MBSG_BSGRP)?>"><?=trim($r->MBSG_DESC)?></option>

                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-md-3 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Date</label>
                    <input type="text" class="form-control" id="criticpart_txt_date" readonly>
                </div>
            </div>
            <div class="col-md-3 mb-1">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="criticpart_ck">
                    <label class="form-check-label" for="criticpart_ck">
                        Consider WO's completion flag
                    </label>
                </div>
            </div>
            <div class="col-md-2 mb-1">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary" id="criticpart_btn_new" title="New" onclick="criticpart_btn_new_eC()"><i class="fas fa-file"></i></button>
                    <button class="btn btn-outline-success" id="criticpart_btn_save" title="Save as Sepreadsheet" onclick="criticpart_btn_save_eC(this)"><i class="fas fa-file-excel"></i></button>
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
    function criticpart_btn_save_eC(p) {
        const ck = document.getElementById('criticpart_ck').checked ? 'o' : 'a'
        const cutoffdate = document.getElementById('criticpart_txt_date').value
        const business = document.getElementById('criticalpart_cmb_bg').value
        let datanya_FG = criticpart_sso_fg.getData()
        let datanya_RM = criticpart_sso_part.getData()        
        let FGList = datanya_FG.filter((data) => data[0].length > 1)
        let RMList = datanya_RM.filter((data) => data[0].length > 1)
        RMList = [...new Set(RMList.map(data => data[0]))]
        FGList = [...new Set(FGList.map(data => data[0]))]
        if(business==='PSI1PPZIEP') {
            if(FGList.length == 0) {
                alertify.message('Assy Code is required')
                let firstTabEl = document.querySelector('#myTab button[data-bs-target="#criticpart_tabFG"]')
                let thetab = new bootstrap.Tab(firstTabEl)
                thetab.show()
                return
            }
            if(RMList.length == 0) {
                alertify.message('Part Code is required')
                let firstTabEl = document.querySelector('#myTab button[data-bs-target="#criticpart_tabRM"]')
                let thetab = new bootstrap.Tab(firstTabEl)
                thetab.show()
                return
            }
        }
        p.disabled = true
        p.innerHTML = '<i class="fas fa-spinner fa-spin"></i>'
        $.ajax({
            type: "POST",
            url: "<?=base_url('ITH/breakdown_estimation')?>",
            data: {fg : FGList, rm: RMList, date : cutoffdate, wostatus: ck, bg : business},
            success: function (response) {                
                const blob = new Blob([response], { type: "application/vnd.ms-excel" })
                saveAs(blob, `Critical Part ${business} On ${cutoffdate}.xlsx`)
                p.innerHTML = '<i class="fas fa-file-excel"></i>'
                p.disabled = false
                alertify.success('Done')
            },
            xhr: function () {
                const xhr = new XMLHttpRequest()
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 2) {
                        if (xhr.status == 200) {
                            xhr.responseType = "blob";
                        } else {
                            p.innerHTML = '<i class="fas fa-file-excel"></i>'
                            p.disabled = false
                            xhr.responseType = "text";
                        }
                    }
                }
                return xhr
            },
        })
    }
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
    var mpurordataFG = [
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
        data:mpurordataFG,
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