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
            let _wo = arrayItem[0]+'-'+arrayItem[1]
            if( !WOList.includes(_wo) ) {
                WOList.push(_wo)
                ReffList.push(arrayItem[0])
                AssyList.push(arrayItem[1])
                ProdQtyList.push(arrayItem[2].replaceAll(',',''))
            }
        })
        if(ReffList.length>0 && ReffList.length === AssyList.length) {
            p.disabled = true
            p.innerHTML = '<i class="fas fa-spinner fa-spin"></i>'
            $.ajax({
                type: "POST",
                url: "<?=base_url('WO/suggestProcess')?>",
                data: { woReff: ReffList, woAssy: AssyList,woProdQty:  ProdQtyList,  outputType:'spreadsheet' },
                success: function (response) {
                    let waktuSekarang = moment().format('YYYY MMM DD, h_mm')
                    const blob = new Blob([response], { type: "application/vnd.ms-excel" })
                    const fileName = `wo ${waktuSekarang}.xlsx`
                    saveAs(blob, fileName)
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
</script>