<div style="padding: 10px">
	<div class="container-fluid">
        <div class="row">
            <div class="col-md-4 mb-1">
                <div class="mb-3">
                    <label for="platNomor" class="form-label" id="go_platNomor_label">Vehicle Registration Number</label>
                    <div class="input-group">
                        <select class="form-control" id="go_platNomor" onchange="go_platNomor_e_change()">
                            <option value="-">-</option>
                            <?=$ltrans?>
                        </select>
                        <button class="btn btn-sm btn-outline-primary" id="go_conf_btn_sync" onclick="go_conf_btn_sync_e_click()"><i class="fas fa-sync"></i></button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="mb-3">
                    <label for="go_driver_name" class="form-label" id="go_driver_name_label">Driver Name</label>
                    <input type="text" class="form-control" id="go_driver_name">
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="mb-3">
                    <label for="go_co_driver_name" class="form-label" id="go_co_driver_name_label">Co Driver Name</label>
                    <input type="text" class="form-control" id="go_co_driver_name">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-1 text-end">
                <button class="btn btn-sm btn-outline-primary" id="go_conf_btn_confirm" onclick="go_conf_btn_confirm_e_click(this)">Confirm</button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="go_conf_resume_tbl_div">
                    <table id="go_conf_tbl" class="table table-sm table-striped table-bordered table-hover compact" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">TX ID</th>
                                <th class="text-center">Consignee</th>
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

function go_conf_btn_sync_e_click() {
    go_conf_btn_sync.disabled = true
    go_platNomor.innerHTML = `<option value="-">Please wait</option>`
    $.ajax({
        type: "get",
        url: `<?=$_ENV['APP_INTERNAL_API']?>delivery/gate-out`,
        dataType: "json",
        success: function (response) {
            go_conf_btn_sync.disabled = false
            let strTemp = '<option value="-">-</option>'
            if(response.data) {
                response.data.forEach((arrayItem) => {
                    strTemp += `<option value="${arrayItem['DLVH_ACT_TRANS']}">${arrayItem['DLVH_ACT_TRANS']}</option>`
                })
            }
            go_platNomor.innerHTML = strTemp

        }, error: function(xhr, xopt, xthrow){
            go_platNomor.innerHTML = `<option value="-"></option>`
            alertify.error(xthrow);
            go_conf_btn_sync.disabled = false
        }
    });
}

go_conf_btn_sync_e_click()

function go_platNomor_e_change() {
    let mtabel = document.getElementById("go_conf_tbl");
    mtabel.getElementsByTagName("tbody")[0].innerHTML = '<tr><td class="text-center" colspan=4>Please wait</td></tr>'
    $.ajax({
        type: "get",
        url: `<?=$_ENV['APP_INTERNAL_API']?>delivery/gate-out/${btoa(go_platNomor.value)}`,
        dataType: "json",
        success: function (response) {
            let mydes = document.getElementById("go_conf_resume_tbl_div");
            let myfrag = document.createDocumentFragment();

            let cln = mtabel.cloneNode(true);
            myfrag.appendChild(cln);
            let tabell = myfrag.getElementById("go_conf_tbl");
            let tableku2 = tabell.getElementsByTagName("tbody")[0];
            let newrow, newcell;
            tableku2.innerHTML = ''
            response.data.forEach((arrayItem) => {
                newrow = tableku2.insertRow(-1);
                newcell = newrow.insertCell(-1);
                newcell.innerText = arrayItem['DLV_ID']
                newcell.classList.add('text-center')
                newcell = newrow.insertCell(-1);
                newcell.innerText = arrayItem['PLANT']
                newcell.classList.add('text-center')
            })
            mydes.innerHTML='';
            mydes.appendChild(myfrag);
            go_driver_name.focus()
        }, error: function(xhr, xopt, xthrow){
            alertify.error(xthrow);
            mtabel.getElementsByTagName("tbody")[0].innerHTML = '<tr><td class="text-center" colspan=4>-</td></tr>'
        }
    });
}

function go_conf_btn_confirm_e_click(pThis) {
    if(go_platNomor.value === '-') {
        alertify.message(`${go_platNomor_label.innerText} is required`)
        go_platNomor.focus()
        return
    }

    let driverName = go_driver_name.value.trim()
    let codriverName = go_co_driver_name.value.trim()

    if(driverName.length <= 1) {
        alertify.message(`${go_driver_name_label.innerText} is required`)
        go_driver_name.focus()
        return
    }
    if( codriverName.length <= 1) {
        go_co_driver_name.focus()
        alertify.message(`${go_co_driver_name_label.innerText} is required`)
        return
    }

    pThis.disabled = true
    $.ajax({
        type: "post",
        url: `<?=$_ENV['APP_INTERNAL_API']?>delivery/gate-out`,
        data : {regNumber : btoa(go_platNomor.value),driverName: driverName, codriverName: codriverName,
            user_id: uidnya
        },
        dataType: "json",
        success: function (response) {
            pThis.disabled = false
            alertify.success(response.message)
        }, error: function(xhr, xopt, xthrow){
            pThis.disabled = false
            alertify.error(xhr.responseJSON.message);
        }
    });
}

</script>