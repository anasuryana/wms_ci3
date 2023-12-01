<div style="padding: 5px" >
	<div class="container-fluid">
        <div class="row">
            <div class="col-md-2 mb-1">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary" id="wosuggester_btn_new" title="New" onclick="wosuggester_btn_new_eC()"><i class="fas fa-file"></i></button>
                    <button class="btn btn-outline-success" id="wosuggester_btn_save" title="Save as Sepreadsheet" onclick="wosuggester_btn_save_eC(this)"><i class="fas fa-file-excel"></i></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="wosuggester_home-tab" data-bs-toggle="tab" data-bs-target="#wosuggester_tabRM" type="button" role="tab" aria-controls="home" aria-selected="true">Input</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="wosuggester_checksim-tab" data-bs-toggle="tab" data-bs-target="#wosuggester_tab_checksim" type="button" role="tab" aria-controls="home" aria-selected="true">Simulation Checker</button>
                </li>
            </ul>
                <div class="tab-content" id="wosuggester_myTabContent">
                    <div class="tab-pane fade show active" id="wosuggester_tabRM" role="tabpanel" aria-labelledby="home-tab">
                        <div class="container-fluid">                            
                            <div class="row">
                                <div class="col-md-12 mb-1 table-responsive">
                                    <div id="vcritical_partcd"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="wosuggester_tab_checksim" role="tabpanel">
                        <div class="row mt-1 mb-1">
                            <div class="col-md-12">
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" id="wosuggester_btn_check" title="Checker" onclick="wosuggester_btn_checker_eC(this)">Check</button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-1 table-responsive">
                                <div id="wosuggester_sim_container"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function wosuggester_btn_save_eC(p) {
        let datanya_RM = wosuggester_sso_part.getData()
        let dataList = datanya_RM.filter((data) => data[0].length > 1)
        let ReffList = []
        let AssyList = []
        let WOList = []
        let ProdQtyList = []

        dataList.forEach((arrayItem) => {
            let _wo = arrayItem[0].trim()+'-'+arrayItem[1].trim()
            if( !WOList.includes(_wo) ) {
                WOList.push(_wo)
                ReffList.push(arrayItem[0].trim())
                AssyList.push(arrayItem[1].trim())
                ProdQtyList.push(arrayItem[2].replaceAll(',',''))
            }
        })
        if(ReffList.length>0 && ReffList.length === AssyList.length) {
            p.disabled = true
            p.innerHTML = '<i class="fas fa-spinner fa-spin"></i>'
            $.when(
                $.ajax({
                    type: "POST",
                    url: "<?=base_url('WO/suggestProcess')?>",
                    data: { woReff: ReffList, woAssy: AssyList,woProdQty:  ProdQtyList,  outputType:'spreadsheet' },
                    success: function (response) {
                        let waktuSekarang = moment().format('YYYY MMM DD, h_mm')
                        const blob = new Blob([response], { type: "application/vnd.ms-excel" })
                        const fileName = `wo ${waktuSekarang}.xlsx`
                        saveAs(blob, fileName)

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
            ).then(function(data, textStatus, jqXHR){
                let assyCodeUnique = [...new Set(AssyList)]
                $.ajax({
                    type: "POST",
                    url: "<?=base_url('WO/ProcessHistory')?>",
                    data: { woAssy: assyCodeUnique,  outputType:'spreadsheet' },
                    success: function (response) {
                        let waktuSekarang = moment().format('YYYY MMM DD, h_mm')
                        const blob = new Blob([response], { type: "application/vnd.ms-excel" })
                        const fileName = `process history ${waktuSekarang}.xlsx`
                        saveAs(blob, fileName)

                        alertify.success('Done')
                    },
                    xhr: function () {
                        const xhr = new XMLHttpRequest()
                        xhr.onreadystatechange = function () {
                            if (xhr.readyState == 2) {
                                if (xhr.status == 200) {
                                    xhr.responseType = "blob";
                                    p.innerHTML = '<i class="fas fa-file-excel"></i>'
                                    p.disabled = false
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
            })
        } else {
            alertify.message('could not continue')
        }
    }
    function wosuggester_btn_new_eC() {
        wosuggester_sso_part.setData([[],[],[],[],[]])
    }
    var mpurordata = [
       [''],
    ];
    var wosuggester_sso_part = jspreadsheet(document.getElementById('vcritical_partcd'), {
        data:mpurordata,
        columns: [
            {
                type: 'text',
                title:'Reference No',
                width:150,
                align: 'left'
            },
            {
                type: 'text',
                title:'Assy Code',
                width:150,
                align: 'left'
            },
            {
                type: 'numeric',
                mask: '#,##.00',
                title:'Production Qty.',
                width:150,
                align: 'right'
            },

        ],
    });
    var wosuggester_sso_simcheck = jspreadsheet(wosuggester_sim_container, {
        data: [,],
        columns: [
            {
                type: 'text',
                title:'Work Order',
                width:250,
                align: 'left'
            },
            {
                type: 'text',
                title:'Line',
                width:150,
                align: 'left'
            },

        ],
    });

    function wosuggester_btn_checker_eC(p) {
        let datanya_RM = wosuggester_sso_simcheck.getData()
        let dataList = datanya_RM.filter((data) => data[0].length > 1)
        let ReffList = []
        let LineList = []        

        dataList.forEach((arrayItem) => {
            let _wo = arrayItem[0].trim()
            if( !ReffList.includes(_wo) ) {
                ReffList.push(_wo)
                LineList.push(arrayItem[1].trim())
            }
        })

        p.disabled = true
        p.innerHTML = 'Please wait'
        // console.log(wosuggester_sso_simcheck)
        console.log(datanya_RM)
        console.log(datanya_RM.length)
        $.ajax({
            type: "POST",
            url: "<?=base_url('WO/checkSimulation')?>",
            data: {ReffList: ReffList, LineList : LineList},
            dataType: "json",
            success: function (response) {
                p.innerHTML = 'Check'
                p.disabled = false
                for(let i=0; i<datanya_RM.length; i++) {
                    let woInput = wosuggester_sso_simcheck.getCell( 'A'+(i+1) ).innerText
                    response.data.forEach((arrayItem) => {
                        if(arrayItem['WO'] === woInput) {
                            switch(arrayItem['Status']) {
                                case 'OK':
                                    wosuggester_sso_simcheck.setStyle('A'+(i+1), 'background-color', '#91ffb4')
                                    wosuggester_sso_simcheck.setStyle('B'+(i+1), 'background-color', '#91ffb4')
                                    break;
                                case 'FOUND BUT DIFFERENT LINE':
                                    wosuggester_sso_simcheck.setStyle('A'+(i+1), 'background-color', '#ffd700')
                                    wosuggester_sso_simcheck.setStyle('B'+(i+1), 'background-color', '#ffd700')
                                    break;
                                case 'NOT FOUND':
                                    wosuggester_sso_simcheck.setStyle('A'+(i+1), 'background-color', '#b22222')
                                    wosuggester_sso_simcheck.setStyle('B'+(i+1), 'background-color', '#b22222')
                                    break;
                            }                        
                        }
                    })                    
                }
            },
            error: function(xhr, xopt, xthrow) {
                p.innerHTML = 'Check'
                alertify.error(xthrow)
                p.disabled = false
            }
        });
    }
</script>