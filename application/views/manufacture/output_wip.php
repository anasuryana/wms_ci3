<div style="padding: 5px">
    <div class="container-fluid">
        
        <div class="row" id="mfg_stack1">
            <div class="col-md-4">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Production Date</label>
                    <input type="text" class="form-control" id="mfg_wip_date_input" readonly onchange="mfg_wip_date_input_on_change()">
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Shift</label>
                    <select class="form-select" id="mfg_wip_shift_input" onchange="mfg_wip_shift_input_on_change()">
                        <option value="M">Morning</option>
                        <option value="N">Night</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <button class="btn btn-primary btn-sm" id="mfg_wip_btn_save" title="Save" onclick="mfg_wip_btn_save_eC(this)"><i class="fas fa-save"></i></button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1" id="mfg-div-alert">

            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <ul class="nav nav-tabs nav-pills" id="mfg_wip_main_tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active rounded-5" data-bs-toggle="tab" data-bs-target="#mfg_wip_tab_master" type="button" role="tab" aria-controls="home" aria-selected="true">Data</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-5" data-bs-toggle="tab" data-bs-target="#mfg_wip_tab_resume" type="button" role="tab" aria-controls="home" aria-selected="true">Resume</button>
                    </li>
                </ul>
                <div class="tab-content" id="mfg_wip_myTabContent">
                    <div class="tab-pane show active" id="mfg_wip_tab_master" role="tabpanel">
                        <div class="container-fluid p-1">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="input-group input-group-sm mb-1">
                                        <label class="input-group-text">Line</label>
                                        <select class="form-select" id="mfg_wip_line_input" onchange="mfg_wip_line_input_on_change()">
                                            <option value='A3'>A3</option>
                                            <option value='B3'>B3</option>
                                            <option value="C3">C3</option>
                                            <option value="D3">D3</option>
                                            <option value="D3-1">D3-1</option>
                                            <option value="E3">E3</option>
                                            <option value="F3">F3</option>
                                            <option value="H3-1">H3-1</option>
                                            <option value="H3-2">H3-2</option>
                                            <option value="H3-3">H3-3</option>
                                            <option value="J3-1">J3-1</option>
                                            <option value="J3-2">J3-2</option>
                                            <option value="K3">K3</option>
                                            <option value="L3">L3</option>
                                            <option value="M3">M3</option>
                                            <option value="OFFLINE 1">OFFLINE 1</option>
                                            <option value="OFFLINE 2">OFFLINE 2</option>
                                            <option value="OFFLINE 3">OFFLINE 3</option>
                                            <option value="PS2">PS2</option>
                                            <option value="PS3">PS3</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-1 table-responsive">
                                    <div id="mfg_wip_data_spreadsheet"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="mfg_wip_tab_resume" role="tabpanel">
                        <div class="container-fluid p-1">
                            <div class="row">
                                <div class="col-md-12 mb-1 table-responsive">
                                    <div id="mfg_wip_spreadsheet"></div>
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
    var wip_tempX1 = 0


    $("#mfg_wip_date_input").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });

    $("#mfg_wip_date_input").datepicker('update', new Date());

    var mfg_wip_data_spreadsheet_sso = jspreadsheet(mfg_wip_data_spreadsheet, {
        columns : [
            {
                title:'Assy Code',
                type: 'text',
                width:150,
            },
            {
                title:'Job',
                type: 'text',
                width:100,
            },
            {
                title:'Model',
                type: 'text',
                width:75,
                readOnly: true
            },
            {
                title:'Type',
                type: 'text',
                width:125,
                readOnly: true
            },
            {
                title:'Spec',
                type: 'text',
                width:100,
                readOnly: true
            },
            {
                title:'OS Lot Size',
                type: 'numeric',
                mask: '#,##',
                width:100,
                readOnly: true
            },
            {
                title:'Output',
                type: 'numeric',
                mask: '#,##',
                width:100,
            },
        ],
        allowInsertColumn : false,
        allowDeleteColumn : false,
        allowRenameColumn : false,
        allowDeleteRow : false,
        rowDrag:false,
        data: [
            [,,,,,,,],
            [,,,,,,,],
            [,,,,,,,],
            [,,,,,,,],
            [,,,,,,,],
        ],
        copyCompatibility:true,
        columnSorting:false,
        tableOverflow:true,
        onselection: function(instance, x1, y1, x2, y2, origin) {
            if(wip_tempX1 == 1) {
                //sync name
                mfg_wip_get_description()
            }

            if(wip_tempX1 == 2) {
                mfg_wip_get_outstanding()
            }

            wip_tempX1 = x1
            console.log({
                x1 : x1, y1 : y1, x2 : x2, y2:y2
            })
        },
        tableHeight: ($(window).height()-mfg_stack1.offsetHeight - 150) + 'px',
    });
    var mfg_wip_spreadsheet_sso = jspreadsheet(mfg_wip_spreadsheet, {
        columns : [
            {
                title:'Line',
                type: 'text',
                width:135,
                readOnly: true
            },
            {
                title:'Assy Code',
                type: 'text',
                width:150,
                readOnly: true
            },
            {
                title:'Job',
                type: 'text',
                width:100,
                readOnly: true
            },
            {
                title:'Model',
                type: 'text',
                width:75,
                readOnly: true
            },
            {
                title:'Type',
                type: 'text',
                width:125,
                readOnly: true
            },
            {
                title:'Spec',
                type: 'text',
                width:100,
                readOnly: true
            },
            {
                title:'OS Lot Size',
                type: 'numeric',
                mask: '#,##',
                width:100,
                readOnly: true
            },
            {
                title:'Output',
                type: 'numeric',
                mask: '#,##',
                width:100,
                readOnly: true
            },
        ],
        allowInsertColumn : false,
        allowDeleteColumn : false,
        allowRenameColumn : false,
        allowDeleteRow : false,
        rowDrag:false,
        data: [
            [,,,,,,,],
            [,,,,,,,],
            [,,,,,,,],
            [,,,,,,,],
            [,,,,,,,],
        ],
        copyCompatibility:true,
        columnSorting:false,
        tableOverflow:true,
        
        tableHeight: ($(window).height()-mfg_stack1.offsetHeight - 150) + 'px',
    });

    function mfg_wip_get_description() {
        let dataDetail = []
        let inputSS = mfg_wip_data_spreadsheet_sso.getData().filter((data) => data[1].length)
        const inputSSCount = inputSS.length
        for(let i=0; i<inputSSCount;i++) {

            let _assyCode = inputSS[i][1].trim()
            dataDetail.push({
                item_code : _assyCode,

            })
        }

        const data = {
            production_date : mfg_wip_date_input.value,
            shift : mfg_wip_shift_input.value,
            user_id : uidnya,
            detail : dataDetail
        }

        $.ajax({
            type: "POST",
            url: "<?php echo $_ENV['APP_INTERNAL_API'] ?>work-order/item-description",
            data: JSON.stringify(data),
            dataType: "JSON",
            success: function (response) {
                let dataLength = mfg_wip_data_spreadsheet_sso.getData().length
                let responseDataLength = response.data.length
                for(let i=0; i < dataLength; i++) {
                    let _itemCode = mfg_wip_data_spreadsheet_sso.getValueFromCoords(1, i, true).trim()
                    for(let s=0;s<responseDataLength; s++) {
                        if(_itemCode == response.data[s].item_code.trim()) {
                            mfg_wip_data_spreadsheet_sso.setValue('D'+(i+1), response.data[s].model_code, true)
                            mfg_wip_data_spreadsheet_sso.setValue('E'+(i+1), response.data[s].type, true)
                            mfg_wip_data_spreadsheet_sso.setValue('F'+(i+1), response.data[s].specs, true)
                            break;
                        }
                    }
                }
            }, error: function(xhr, xopt, xthrow) {

            }
        });
    }

    function mfg_wip_get_outstanding() {
        let dataDetail = []
        let inputSS = mfg_wip_data_spreadsheet_sso.getData().filter((data) => data[1].length && data[2].length)
        const inputSSCount = inputSS.length
        for(let i=0; i<inputSSCount;i++) {
            let _wo_code = inputSS[i][2].trim()
            let _assyCode = inputSS[i][1].trim()
            dataDetail.push({
                item_code : _assyCode,
                wo_code : _wo_code,
            })
        }

        const data = {
            production_date : mfg_wip_date_input.value,
            shift : mfg_wip_shift_input.value,
            user_id : uidnya,
            detail : dataDetail
        }

        $.ajax({
            async : false,
            type : "POST",
            url: "<?php echo $_ENV['APP_INTERNAL_API'] ?>work-order/item-outstanding-lotsize",
            data: JSON.stringify(data),
            dataType: "JSON",
            success: function (response) {
                let dataLength = mfg_wip_data_spreadsheet_sso.getData().length
                let responseDataLength = response.data.length
                for(let i=0; i < dataLength; i++) {
                    let _itemCode = mfg_wip_data_spreadsheet_sso.getValueFromCoords(1, i, true).trim()
                    for(let s=0;s<responseDataLength; s++) {
                        if(_itemCode == response.data[s].item_code.trim()) {

                            mfg_wip_data_spreadsheet_sso.setValue('G'+(i+1), response.data[s].ost_qty, true)
                            break;
                        }
                    }
                }
            }, error: function(xhr, xopt, xthrow) {

            }
        });
    }

    function mfg_wip_date_input_on_change() {
        mfg_wip_load_at()
    }

    function mfg_wip_btn_save_eC(pThis) {

        mfg_wip_get_outstanding()

        let dataDetail = []
        let inputSS = mfg_wip_data_spreadsheet_sso.getData().filter((data) => data[2].length && data[7].length >= 1)
        const inputSSCount = inputSS.length
        for(let i=0; i<inputSSCount;i++) {
            let _assyCode = inputSS[i][0].trim()
            let _wo_code = inputSS[i][1].trim()
            let _currentOst = numeral(inputSS[i][5].trim()).value()
            let _output = numeral(inputSS[i][6].trim()).value()

            if(_currentOst > 0) {
                alertify.warning(`There is no outstanding`)
                return
            } else {
                if(Math.abs(_currentOst)<_output) {
                    alertify.warning(`output greater than outstanding !`)
                    return
                }
            }

            dataDetail.push({
                item_code : _assyCode,
                wo_code : _wo_code,
                output : _output,
            })
        }

        if(dataDetail.length == 0) {
            alertify.message(`Nothing to be processed`)
            return
        }

        const data = {
            production_date : mfg_wip_date_input.value,
            shift : mfg_wip_shift_input.value,
            user_id : uidnya,
            line_code : mfg_wip_line_input.value,
            detail : dataDetail
        }

        pThis.disabled = true
        const div_alert = document.getElementById('mfg-div-alert')
        div_alert.innerHTML = ''

        $.ajax({
            type: "POST",
            url: "<?php echo $_ENV['APP_INTERNAL_API'] ?>work-order/output-wip",
            data: JSON.stringify(data),
            dataType: "JSON",
            success: function (response) {
                pThis.disabled = false
                alertify.message(response.message)
            }, error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow)
                pThis.disabled = false

                const respon = Object.keys(xhr.responseJSON)

                let msg = ''
                for (const item of respon) {
                    msg += `<p>${xhr.responseJSON[item]}</p>`
                }
                div_alert.innerHTML = `<div class="alert alert-warning alert-dismissible fade show" role="alert">
                ${msg}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`
            }
        });
    }

    function mfg_wip_load_at() {
        const data = {
            production_date : mfg_wip_date_input.value,
            shift : mfg_wip_shift_input.value,
        }
        $.ajax({
            type: "GET",
            url: "<?php echo $_ENV['APP_INTERNAL_API'] ?>work-order/output-wip",
            data: data,
            dataType: "json",
            success: function (response) {
                let theData = [];
                let theDataInput = []
                response.data.forEach((arrayItem, index) => {
                    if(arrayItem['line_code'] == mfg_wip_line_input.value) {
                        theDataInput.push([
                            arrayItem['item_code'],
                            arrayItem['wo_code'],
                            arrayItem['model_code'],
                            arrayItem['type'],
                            arrayItem['specs'],
                            arrayItem['ostLotSize'],
                            arrayItem['ok_qty'],
                        ])
                    }
                    theData.push([
                            arrayItem['line_code'],
                            arrayItem['item_code'],
                            arrayItem['wo_code'],
                            arrayItem['model_code'],
                            arrayItem['type'],
                            arrayItem['specs'],
                            arrayItem['ostLotSize'],
                            arrayItem['ok_qty'],
                        ])
                })

                if(theDataInput.length == 0) {
                    theDataInput.push([                       
                        ,
                        ,
                        ,
                        ,
                        ,
                        0,
                        ,
                    ])
                }
                if(theData.length == 0) {
                    theData.push([
                       ,
                        ,
                        ,
                        ,
                        ,
                        ,
                        0,
                        ,
                    ])
                }
                mfg_wip_data_spreadsheet_sso.setData(theDataInput)
                mfg_wip_spreadsheet_sso.setData(theData)
            }
        });
    }

    function mfg_wip_line_input_on_change() {
        mfg_wip_load_at()
    }
</script>