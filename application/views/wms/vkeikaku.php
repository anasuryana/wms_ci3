<div style="padding: 5px" >
	<div class="container-fluid">
        <div class="row" id="keikaku_stack1">
            <div class="col-md-6">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Line</label>
                    <select class="form-select" id="keikaku_line_input" required>
                        <option value="-">-</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Production Date</label>
                    <input type="text" class="form-control" id="keikaku_date_input" readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="keikaku_home-tab" data-bs-toggle="tab" data-bs-target="#keikaku_tabRM" type="button" role="tab" aria-controls="home" aria-selected="true">Data</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="keikaku_checksim-tab" data-bs-toggle="tab" data-bs-target="#keikaku_tab_checksim" type="button" role="tab" aria-controls="home" aria-selected="true">Calculation</button>
                </li>
            </ul>
                <div class="tab-content" id="keikaku_myTabContent">
                    <div class="tab-pane fade show active" id="keikaku_tabRM" role="tabpanel" aria-labelledby="home-tab">
                        <div class="container-fluid p-1">
                            <div class="row" id="keikaku_stack2">
                                <div class="col-md-2 mb-1">
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary" id="keikaku_btn_new" title="New" onclick="keikaku_btn_new_eC()"><i class="fas fa-file"></i></button>
                                        <button class="btn btn-outline-success" id="keikaku_btn_save" title="Save" onclick="keikaku_btn_save_eC(this)"><i class="fas fa-save"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-1 table-responsive">
                                    <div id="keikaku_data_spreadsheet"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="keikaku_tab_checksim" role="tabpanel">
                        <div class="row mt-1 mb-1">
                            <div class="col-md-6">
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" id="keikaku_btn_new_calculation" title="New" onclick="keikaku_btn_new_calculation_eClick()"><i class="fas fa-file"></i></button>
                                    <button class="btn btn-outline-primary" id="keikaku_btn_save_calculation" onclick="keikaku_btn_save_calculation_eClick()"><i class="fas fa-save"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-1 table-responsive">
                                <div id="keikaku_calculation_spreadsheet"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var keikaku_data_sso = jspreadsheet(keikaku_data_spreadsheet, {
        columns : [
            {
                title:'id',
                readOnly : true,
                width:30,
            },
            {
                title:'Seq.',
                type: 'numeric',
                width:50,
            },
            {
                title:'Model',
                type: 'text',
                width:100,
            },
            {
                title:'Job',
                type: 'text',
                width:100,
            },
            {
                title:'Lot',
                type: 'numeric',
                mask: '#,##',
                width:75,
            },
            {
                title:'Production',
                type: 'numeric',
                mask: '#,##',
                width:75,
            },
            {
                title:'Type',
                type: 'text',
                width:100,
            },
            {
                title:'Spec',
                type: 'text',
                width:100,
            },
            {
                title:'Assy Code',
                type: 'text',
                width:100,
            },
            {
                title:'ASP/KD',
                type: 'text',
                width:100,
            },
            {
                title:'Spec',
                type: 'dropdown',
                source: ['A', 'B']
            },
        ],
        allowInsertColumn : false,
        allowDeleteColumn : false,
        allowRenameColumn : false,
        allowDeleteRow : false,
        rowDrag:false,
        data: [
            [,],
            [,],
            [,],
            [,]
        ],
        copyCompatibility:true,
        columnSorting:false,
        oninsertrow : function() {
            console.log('sini insert')
            // console.log(keikaku_data_spreadsheet.clientHeight)
        },
        tableOverflow:true,
        tableHeight: ($(window).height()-keikaku_stack1.offsetHeight-keikaku_stack2.offsetHeight - 140) + 'px',
    });

    var keikaku_calculation_sso = jspreadsheet(keikaku_calculation_spreadsheet, {
        columns : [
            {
                readOnly : true,
                type : 'text',
                width:120,
                align : 'left',
            }, ...Array.from({length: 24}, (_, i) => Object.create({
                    type : 'numeric',
                    mask: '#,##.00',
                    }))
        ],
        allowInsertColumn : false,
        allowDeleteColumn : false,
        allowRenameColumn : false,
        allowDeleteRow : false,
        rowDrag:false,
        data: [
            ['NON OT', ...Array.from({length: 24}, (_, i) => null)],
            ['OT1', ...Array.from({length: 24}, (_, i) => null)],
            ['MENT NON OT', ...Array.from({length: 24}, (_, i) => null)],
            ['MENT OT', ...Array.from({length: 24}, (_, i) => null)],
            ['MENT NON OT', ...Array.from({length: 24}, (_, i) => null)],
            ['MENT OT', ...Array.from({length: 24}, (_, i) => null)],
            ['Hour', ...Array.from({length: 16}, (_, i) => i + 8), ...Array.from({length: 8}, (_, i) => i)],
        ],
        copyCompatibility:true,
        columnSorting:false,
        allowInsertRow : false,
        updateTable: function(el, cell, x, y, source, value, id) {
            if (Array.from({length: 24}, (_, i) => i + 1).includes(x) && [6].includes(y)) {
                cell.classList.add('readonly');
                cell.style.cssText = "font-weight: bold; text-align:center"
            }
        },
        
    });

    function keikaku_load_line_code() {
        $.ajax({
            type: "GET",
            url: "<?=$_ENV['APP_INTERNAL_API']?>process-master/line-code",
            dataType: "json",
            success: function (response) {
                keikaku_line_input.innerHTML = `<option value="-">-</option>`
                let inputs = '<option value="-">-</option>';
                response.data.forEach((arrayItem) => {
                    inputs += `<option value="${arrayItem['line_code']}">${arrayItem['line_code']}</option>`
                })
                keikaku_line_input.innerHTML = inputs
            }
        });
    }

    keikaku_load_line_code()

    $("#keikaku_date_input").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });
    $("#keikaku_date_input").datepicker('update', new Date());
</script>
