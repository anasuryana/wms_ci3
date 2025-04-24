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
            <div class="col-md-12 mb-1 table-responsive">
                <div id="mfg_wip_spreadsheet"></div>
            </div>
        </div>
    </div>
</div>
<script>
    $("#mfg_wip_date_input").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });

    $("#mfg_wip_date_input").datepicker('update', new Date());

    var mfg_wip_spreadsheet_sso = jspreadsheet(mfg_wip_spreadsheet, {
        columns : [
            {
                title:'Line',
                type: 'dropdown',
                width:135,
                source: [
                    'A3',
                    'B3',
                    'C3',
                    'D3',
                    'D3-1',
                    'E3',
                    'F3',
                    'H3-1',
                    'H3-2',
                    'H3-3',
                    'J3-1',
                    'J3-2',
                    'K3',
                    'L3',
                    'M3',
                    'OFFLINE 1',
                    'OFFLINE 2',
                    'OFFLINE 3',
                    'PS2',
                    'PS3',
                ]
            },
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
        tableHeight: ($(window).height()-mfg_stack1.offsetHeight - 150) + 'px',
    });

    function mfg_wip_date_input_on_change() {
        mfg_wip_load_at()
    }

    function mfg_wip_btn_save_eC(pThis) {
        let dataDetail = []
        let inputSS = mfg_wip_spreadsheet_sso.getData().filter((data) => data[2].length && data[7].length >= 1)
        const inputSSCount = inputSS.length
        for(let i=0; i<inputSSCount;i++) {
            let _line = inputSS[i][0].trim()
            let _assyCode = inputSS[i][1].trim()
            let _wo_code = inputSS[i][2].trim()
            let _output = inputSS[i][7].trim()
            dataDetail.push({
                line_code : _line,
                item_code : _assyCode,
                wo_code : _wo_code,
                output : numeral(_output).value(),
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
            shift : mfg_wip_shift_input.value
        }
        $.ajax({
            type: "GET",
            url: "<?php echo $_ENV['APP_INTERNAL_API'] ?>work-order/output-wip",
            data: data,
            dataType: "json",
            success: function (response) {
                let theData = [];
                response.data.forEach((arrayItem, index) => {
                    theData.push([                        
                        arrayItem['line_code'],
                        arrayItem['item_code'],
                        arrayItem['wo_code'],
                        arrayItem['model_code'],
                        arrayItem['type'],
                        arrayItem['specs'],
                        0,
                        arrayItem['ok_qty'],
                    ])
                })

                if(theData.length > 0) {
                    mfg_wip_spreadsheet_sso.setData(theData)
                }
            }
        });
    }
</script>