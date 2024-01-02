<div style="padding: 5px" >
	<div class="container-fluid">
        
        <div class="row">
            <div class="col-md-12 mb-1">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="wosuggester_home-tab" data-bs-toggle="tab" data-bs-target="#wosuggester_tabRM" type="button" role="tab" aria-controls="home" aria-selected="true">Input</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="wosuggester_checksim-tab" data-bs-toggle="tab" data-bs-target="#wosuggester_tab_checksim" type="button" role="tab" aria-controls="home" aria-selected="true">Simulation Checker</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="wosuggester_check_line-tab" data-bs-toggle="tab" data-bs-target="#wosuggester_tab_check_line" type="button" role="tab" aria-controls="home" aria-selected="true">Check Production Line</button>
                </li>
            </ul>
                <div class="tab-content" id="wosuggester_myTabContent">
                    <div class="tab-pane fade show active" id="wosuggester_tabRM" role="tabpanel" aria-labelledby="home-tab">
                        <div class="container-fluid p-1">
                            <div class="row">
                                <div class="col-md-2 mb-1">
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary" id="wosuggester_btn_new" title="New" onclick="wosuggester_btn_new_eC()"><i class="fas fa-file"></i></button>
                                        <button class="btn btn-outline-success" id="wosuggester_btn_save" title="Save as Sepreadsheet" onclick="wosuggester_btn_save_eC(this)"><i class="fas fa-file-excel"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-1 table-responsive">
                                    <div id="vcritical_partcd"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="wosuggester_tab_checksim" role="tabpanel">
                        <div class="row mt-1 mb-1">
                            <div class="col-md-6">
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" id="wosuggester_btn_new" title="New" onclick="wosuggester_btn_new2_eC()"><i class="fas fa-file"></i></button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-sm mb-1">
                                    <label class="input-group-text">Line</label>
                                    <input type="text" class="form-control" id="wosuggester_line_input" maxlength="15">                                    
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
                    <div class="tab-pane fade" id="wosuggester_tab_check_line" role="tabpanel">
                        <div class="row mt-1 mb-1">
                            <div class="col-md-6">
                                <label for="wosuggester_sim_code" class="form-label">Simulation Code</label>
                                <div class="input-group input-group-sm mb-1">
                                    <input type="text" id="wosuggester_sim_code" class="form-control" placeholder="2023.." maxlength="25" onclick="this.select()">
                                    <button class="btn btn-primary" type="button" onclick="wosuggesterBtnCheckSIMLineOnClick(this)"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-1">
                                <div class="table-responsive" id="wosuggester_lineTableContainer">
                                    <table id="wosuggester_lineTable" class="table table-sm table-hover table-bordered caption-top">
                                        <caption>List of line</caption>
                                        <thead class="table-light text-center">
                                            <tr>
                                                <th>Process</th>
                                                <th>Line</th>
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

    function wosuggester_btn_new2_eC() {
        wosuggester_sso_simcheck.setData([[]])
        wosuggester_line_input.value = ''
        wosuggester_line_input.focus()
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
                title:'Model',
                width:100,
                align: 'left'
            },
            {
                type: 'text',
                title:'Job',
                width:70,
                align: 'left'
            },
            {
                type: 'numeric',
                title:'LOT',
                mask: '#,##.00',
                width:100,
                align: 'right'
            },
            {
                type: 'numeric',
                title:'Production QTY',
                mask: '#,##.00',
                width:100,
                align: 'right'
            },
            {
                type: 'text',
                title:'Type',
                width:130,
                align: 'left'
            },
            {
                type: 'text',
                title:'Spec',
                width:90,
                align: 'left'
            },
            {
                type: 'text',
                title:'Assy Code',
                width:100,
                align: 'left'
            },
            {
                type: 'text',
                title:'ASP/KD',
                width:60,
                align: 'left'
            },
            {
                type: 'text',
                title:'Simulation',
                width:130,
                align: 'left',
                readOnly : true
            },

        ],
    });

    function wosuggester_btn_checker_eC(p) {
        if(wosuggester_line_input.value.trim().length===0) {
            wosuggester_line_input.focus()
            alertify.message('Line is required');
            return
        }
        let datanya_RM = wosuggester_sso_simcheck.getData()
        let dataList = datanya_RM.filter((data) => data[1].length > 1)
        let ReffList = []
        let JobList = []
        let AssyCodeList = []

        dataList.forEach((arrayItem) => {
            let aspKD = arrayItem[7].trim().replace(/[\u0000-\u0008,\u000A-\u001F,\u007F-\u00A0]+/g, "")
            let _wo = arrayItem[1].trim() + '-' + arrayItem[6].trim() + aspKD
            if( !ReffList.includes(_wo) ) {
                ReffList.push(_wo)
                JobList.push(arrayItem[1].trim())
                console.log(arrayItem[7].trim().substring())
                AssyCodeList.push(arrayItem[6].trim() + aspKD)
            }
        })

        p.disabled = true
        p.innerHTML = 'Please wait'

        $.ajax({
            type: "POST",
            url: "<?=base_url('WO/checkSimulation')?>",
            data: {ReffList: ReffList, Line : wosuggester_line_input.value, JobList : JobList, AssyCodeList : AssyCodeList},
            dataType: "json",
            success: function (response) {
                p.innerHTML = 'Check'
                p.disabled = false
                for(let i=0; i<datanya_RM.length; i++) {
                    let jobInput = wosuggester_sso_simcheck.getCell( 'B'+(i+1) ).innerText.toUpperCase()
                    let aspKD = wosuggester_sso_simcheck.getCell( 'H'+(i+1) ).innerText.trim().replace(/[\u0000-\u0008,\u000A-\u001F,\u007F-\u00A0]+/g, "").toUpperCase()
                    let assyCodeInput = wosuggester_sso_simcheck.getCell( 'G'+(i+1) ).innerText.toUpperCase() + aspKD
                    response.data.forEach((arrayItem) => {
                        if(arrayItem['Job'].toUpperCase() === jobInput && arrayItem['AssyCode'].toUpperCase() === assyCodeInput) {
                            switch(arrayItem['Status']) {
                                case 'OK':
                                    wosuggester_sso_simcheck.setComments('A'+(i+1), '')
                                    wosuggester_sso_simcheck.setStyle('A'+(i+1), 'background-color', '#91ffb4')
                                    wosuggester_sso_simcheck.setStyle('B'+(i+1), 'background-color', '#91ffb4')
                                    wosuggester_sso_simcheck.setStyle('C'+(i+1), 'background-color', '#91ffb4')
                                    wosuggester_sso_simcheck.setStyle('D'+(i+1), 'background-color', '#91ffb4')
                                    wosuggester_sso_simcheck.setStyle('E'+(i+1), 'background-color', '#91ffb4')
                                    wosuggester_sso_simcheck.setStyle('F'+(i+1), 'background-color', '#91ffb4')
                                    wosuggester_sso_simcheck.setStyle('G'+(i+1), 'background-color', '#91ffb4')
                                    wosuggester_sso_simcheck.setStyle('H'+(i+1), 'background-color', '#91ffb4')
                                    wosuggester_sso_simcheck.setValue('I'+(i+1), arrayItem['SimCode'], true)

                                    wosuggester_sso_simcheck.setStyle('A'+(i+1), 'color', '#000000')
                                    wosuggester_sso_simcheck.setStyle('B'+(i+1), 'color', '#000000')
                                    wosuggester_sso_simcheck.setStyle('C'+(i+1), 'color', '#000000')
                                    wosuggester_sso_simcheck.setStyle('D'+(i+1), 'color', '#000000')
                                    wosuggester_sso_simcheck.setStyle('E'+(i+1), 'color', '#000000')
                                    wosuggester_sso_simcheck.setStyle('F'+(i+1), 'color', '#000000')
                                    wosuggester_sso_simcheck.setStyle('G'+(i+1), 'color', '#000000')
                                    wosuggester_sso_simcheck.setStyle('H'+(i+1), 'color', '#000000')
                                    break;
                                case 'FOUND BUT DIFFERENT LINE':
                                    wosuggester_sso_simcheck.setComments('A'+(i+1), 'FOUND BUT DIFFERENT LINE, Registered Line is ' + arrayItem['PlannedLine'])
                                    wosuggester_sso_simcheck.setStyle('A'+(i+1), 'background-color', '#ffd700')
                                    wosuggester_sso_simcheck.setStyle('B'+(i+1), 'background-color', '#ffd700')
                                    wosuggester_sso_simcheck.setStyle('C'+(i+1), 'background-color', '#ffd700')
                                    wosuggester_sso_simcheck.setStyle('D'+(i+1), 'background-color', '#ffd700')
                                    wosuggester_sso_simcheck.setStyle('E'+(i+1), 'background-color', '#ffd700')
                                    wosuggester_sso_simcheck.setStyle('F'+(i+1), 'background-color', '#ffd700')
                                    wosuggester_sso_simcheck.setStyle('G'+(i+1), 'background-color', '#ffd700')
                                    wosuggester_sso_simcheck.setStyle('H'+(i+1), 'background-color', '#ffd700')
                                    wosuggester_sso_simcheck.setValue('I'+(i+1), arrayItem['SimCode'], true)

                                    wosuggester_sso_simcheck.setStyle('A'+(i+1), 'color', '#000000')
                                    wosuggester_sso_simcheck.setStyle('B'+(i+1), 'color', '#000000')
                                    wosuggester_sso_simcheck.setStyle('C'+(i+1), 'color', '#000000')
                                    wosuggester_sso_simcheck.setStyle('D'+(i+1), 'color', '#000000')
                                    wosuggester_sso_simcheck.setStyle('E'+(i+1), 'color', '#000000')
                                    wosuggester_sso_simcheck.setStyle('F'+(i+1), 'color', '#000000')
                                    wosuggester_sso_simcheck.setStyle('G'+(i+1), 'color', '#000000')
                                    wosuggester_sso_simcheck.setStyle('H'+(i+1), 'color', '#000000')
                                    break;
                                case 'NOT FOUND':
                                    wosuggester_sso_simcheck.setComments('A'+(i+1), 'NOT FOUND')
                                    wosuggester_sso_simcheck.setStyle('A'+(i+1), 'background-color', '#b22222')
                                    wosuggester_sso_simcheck.setStyle('B'+(i+1), 'background-color', '#b22222')
                                    wosuggester_sso_simcheck.setStyle('C'+(i+1), 'background-color', '#b22222')
                                    wosuggester_sso_simcheck.setStyle('D'+(i+1), 'background-color', '#b22222')
                                    wosuggester_sso_simcheck.setStyle('E'+(i+1), 'background-color', '#b22222')
                                    wosuggester_sso_simcheck.setStyle('F'+(i+1), 'background-color', '#b22222')
                                    wosuggester_sso_simcheck.setStyle('G'+(i+1), 'background-color', '#b22222')
                                    wosuggester_sso_simcheck.setStyle('H'+(i+1), 'background-color', '#b22222')

                                    wosuggester_sso_simcheck.setStyle('A'+(i+1), 'color', '#ffffff')
                                    wosuggester_sso_simcheck.setStyle('B'+(i+1), 'color', '#ffffff')
                                    wosuggester_sso_simcheck.setStyle('C'+(i+1), 'color', '#ffffff')
                                    wosuggester_sso_simcheck.setStyle('D'+(i+1), 'color', '#ffffff')
                                    wosuggester_sso_simcheck.setStyle('E'+(i+1), 'color', '#ffffff')
                                    wosuggester_sso_simcheck.setStyle('F'+(i+1), 'color', '#ffffff')
                                    wosuggester_sso_simcheck.setStyle('G'+(i+1), 'color', '#ffffff')
                                    wosuggester_sso_simcheck.setStyle('H'+(i+1), 'color', '#ffffff')
                                    break;
                                default:
                                    console.log('ke sini')
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

    function wosuggesterBtnCheckSIMLineOnClick(pthis) {
        if(wosuggester_sim_code.value.trim().length > 8) {
            pthis.disabled = true
            $.ajax({
                type: "GET",
                url: `http://192.168.0.29:8080/ems-glue/api/simulation/document/${wosuggester_sim_code.value}`,
                dataType: "json",
                success: function (response) {
                    pthis.disabled = false
                    let myContainer = document.getElementById("wosuggester_lineTableContainer");
                    let myfrag = document.createDocumentFragment();
                    let cln = wosuggester_lineTable.cloneNode(true);
                    myfrag.appendChild(cln);
                    let myTable = myfrag.getElementById("wosuggester_lineTable");
                    let myTableBody = myTable.getElementsByTagName("tbody")[0];
                    myTableBody.innerHTML = ''
                    response.data.forEach((arrayItem) => {
                        newrow = myTableBody.insertRow(-1)
                        newcell = newrow.insertCell(-1)
                        newcell.classList.add('text-center')
                        newcell.innerHTML = arrayItem['PIS1_PROCD']                                        
                        newcell = newrow.insertCell(-1)
                        newcell.classList.add('text-center')
                        newcell.innerHTML = arrayItem['PIS1_LINENO']                        
                    })
                    myContainer.innerHTML = ''
                    myContainer.appendChild(myfrag)
                    
                    if(response.data.length===0) {
                        alertify.message('not found')
                    }
                },
                error: function(xhr, xopt, xthrow) {
                    alertify.warning(xthrow);
                    pthis.disabled = false
                }
            });
        } else {
            alertify.warning('Simulation Code is required')
            wosuggester_sim_code.focus()
        }
    }
</script>