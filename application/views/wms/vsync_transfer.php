<div style="padding: 10px">
	<div class="container-fluid">
        <div class="row" id="sync_trf_stack1">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Document</span>
                    <input type="text" class="form-control" id="sync_trf_txt_doc" maxlength="15">
                    <button class="btn btn-primary btn-sm" id="sync_trf_btn_search" onclick="sync_trf_btn_search_eCK(this)">Retrieve</button>
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Issue Date</span>
                    <input type="text" class="form-control" id="sync_trf_txt_issuedate" disabled>
                </div>
            </div>
        </div>
        <div class="row" id="sync_trf_stack0">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" >From Location</span>
                    <input type="text" class="form-control" id="sync_trf_cmb_wh0" disabled>
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" >To Location</span>
                    <input type="text" class="form-control" id="sync_trf_cmb_wh1" disabled>
                </div>
            </div>
        </div>
        <div class="row" id="sync_trf_stack2">
            <div class="col-md-12 mb-3 text-center">
                <button class="btn btn-primary btn-sm" id="sync_trf_btn_sync" onclick="sync_trf_btn_sync_eCK(this)">Synchronize later</button>
            </div>
        </div>
        <div class="row">
            <div class="col mb-1" id="div-alert">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="sync_trf_divku">
                    <table id="sync_trf_tbl" class="table table-sm table-striped table-bordered table-hover">
                        <thead class="table-light">
                            <tr class="first text-center">
                                <th>Item Code</th>
                                <th>Item Name</th>
                                <th>Description</th>
                                <th>Qty</th>
                                <th>UM</th>
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
<script>

    $("#sync_trf_divku").css('height', $(window).height()
        -document.getElementById('sync_trf_stack0').offsetHeight
        -document.getElementById('sync_trf_stack1').offsetHeight
        -document.getElementById('sync_trf_stack2').offsetHeight
        -100);

    function sync_trf_btn_search_eCK(p) {
        const docNum = sync_trf_txt_doc.value.trim()
        if(docNum.length<4) {
            sync_trf_txt_doc.focus()
            alertify.message('document is required')
            return
        }

        p.disabled = true
        $.ajax({
            type: "GET",
            url: `<?=$_ENV['APP_INTERNAL_API']?>x-transfer/document/${btoa(docNum)}`,
            dataType: "JSON",
            success: function (response) {
                p.disabled = false
                let mydes = document.getElementById("sync_trf_divku");
                let myfrag = document.createDocumentFragment();
                let cln = sync_trf_tbl.cloneNode(true);
                myfrag.appendChild(cln);

                let tabell = myfrag.getElementById("sync_trf_tbl");
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                tableku2.innerHTML = '';
                if(response.data.length > 0) {
                    sync_trf_txt_issuedate.value = response.data[0].ISUDT
                    sync_trf_cmb_wh0.value = response.data[0].LOCCDFR
                    sync_trf_cmb_wh1.value = response.data[0].LOCCDTO

                    response.data.forEach((arrayItem) => {
                        newrow = tableku2.insertRow(-1)
                        newcell = newrow.insertCell(-1)
                        newcell.classList.add('text-center')
                        newcell.innerText = arrayItem['ITMCD']
                        newcell = newrow.insertCell(-1)
                        newcell.classList.add('text-center')
                        newcell.innerText = arrayItem['SPTNO']
                        newcell = newrow.insertCell(-1)
                        newcell.classList.add('text-center')
                        newcell.innerText = arrayItem['ITMD1']
                        newcell = newrow.insertCell(-1)
                        newcell.classList.add('text-end')
                        newcell.innerText = numeral(arrayItem['STKTRND2_TRNQT']).format(',')
                        newcell = newrow.insertCell(-1)
                        newcell.classList.add('text-center')
                        newcell.innerText = arrayItem['STKUOM']
                    })
                } else {
                    sync_trf_txt_issuedate.value = ""
                    sync_trf_cmb_wh0.value = ""
                    sync_trf_cmb_wh1.value = ""
                }
                mydes.innerHTML = ''
                mydes.appendChild(myfrag)
            }, error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow)
                p.disabled = false
                sync_trf_txt_issuedate.value = ""
                sync_trf_cmb_wh0.value = ""
                sync_trf_cmb_wh1.value = ""
            }
        });
    }

    function sync_trf_btn_sync_eCK(p) {
        const docNum = sync_trf_txt_doc.value.trim()
        if(docNum.length<4) {
            sync_trf_txt_doc.focus()
            alertify.message('document is required')
            return
        }

        const data = {document : docNum, userId : uidnya}
        if(!confirm("Are you sure ?")) {
            return
        }

        p.disabled = true
        const div_alert = document.getElementById('div-alert')
        $.ajax({
            type: "POST",
            url: `<?=$_ENV['APP_INTERNAL_API']?>x-transfer/document`,
            data: data,
            dataType: "JSON",
            success: function (response) {
                alertify.success(response.message)
                p.disabled = false
                div_alert.innerHTML = ``
            }, error: function(xhr, xopt, xthrow) {
                alertify.error(xthrow)
                p.disabled = false   
                
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
</script>