<div style="padding: 10px">
	<div class="container-fluid">
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="input-group input-group-sm">
                    <label class="input-group-text">Part Request Number</label>
                    <input type="text" class="form-control" id="pareq_txtPRN" readonly disabled>
                    <button class="btn btn-primary" onclick="pareq_btnshow_saved()"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">
                    <label class="input-group-text">Category</label>
                    <select class="form-select" id="pareq_category" onchange="pareq_category_eChange(this)">
                        <option value="PSN">PSN</option>
                        <option value="RAD">Reject Advice</option>
                        <option value="OTH">Other</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">
                    <label class="input-group-text">Line</label>
                    <input type="text" class="form-control" id="pareq_txtline" maxlength="10">
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">
                    <label class="input-group-text">Department</label>
                    <select class="form-select" id="pareq_txtdept">
                        <option value="MF1">Manufacturing 1</option>
                        <option value="MF2">Manufacturing 2</option>
                        <option value="QA1">QA Manufacturing 1</option>
                        <option value="QA2">QA Manufacturing 2</option>
                        <option value="RCt">Repair Center</option>
                        <option value="ENG">Engineering</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">
                    <label class="input-group-text">Reason</label>
                    <select class="form-select" id="pareq_txtRMRK">
                        <?=$remarklist?>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-2">
                <div class="btn-group btn-group-sm">
                    <button title="New" id="pareq_btnnew" onclick="pareq_btnnew()" class="btn btn-primary" ><i class="fas fa-file"></i></button>
                    <button title="Save" id="pareq_btnsave" onclick="pareq_btnsave()" class="btn btn-primary" ><i class="fas fa-save"></i></button>
                </div>
            </div>
            <div class="col-md-4 mb-2 text-center">
                <span class="badge bg-info" id="lblinfo_pareq_tbl"></span>
            </div>
            <div class="col-md-4 mb-2 text-end">
                <div class="btn-group btn-group-sm">
                    <button title="Add" id="pareq_btnplus" class="btn btn-primary" onclick="pareq_btnadd()"><i class="fas fa-plus"></i></button>
                    <button title="Delete" id="pareq_btnminus" class="btn btn-warning" onclick="pareq_btnminus()" ><i class="fas fa-minus"></i></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12" id="pareq_alert_container">

            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-2">
                <div class="table-responsive" id="pareq_divku">
                    <table id="pareq_tbl" class="table table-sm table-hover table-bordered" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th>Reff. Document</th>
                                <th>Part Code</th>
                                <th class="text-end">Req. Qty</th>
                                <th class="d-none">line</th>
                                <th>Remark</th>
                                <th>Model</th>
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
<div class="modal fade" id="PR_MOD">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Document List</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <div class="row mb-1 d-none" id="pareqPeriodContainer">
                <div class="col-md-6">
                    <label for="pareqPeriod1" class="form-label">From</label>
                    <input type="text" class="form-control form-control-sm" id="pareqPeriod1" readonly onchange="pareqPeriod1OnChange()">
                </div>
                <div class="col-md-6">
                    <label for="pareqPeriod2" class="form-label">To</label>
                    <input type="text" class="form-control form-control-sm" id="pareqPeriod2" readonly onchange="pareqPeriod2OnChange()">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >Search</span>
                        <select id="pareq_srchby" class="form-select" onchange="pareq_srchbyOnChange(this)">
                            <option value="D1">Document No.</option>
                            <option value="D2">Part Code</option>
                        </select>
                        <input type="text" class="form-control" id="pareq_txtsearch" onkeypress="pareq_txtsearch_e_keypress(event)" maxlength="25" required placeholder="...">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col mb-1 text-end">
                    <span class="badge bg-info" id="lblinfo_pareq_tbl_saved"></span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="pareq_div_saved">
                        <table id="pareq_tbl_saved" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer;font-size:75%">
                            <thead class="table-light">
                                <tr class="text-center">
                                    <th>Document No.</th>
                                    <th>Date</th>
                                    <th>Category</th>
                                    <th>Reff. Document No.</th>
                                    <th>PIC</th>
                                    <th>Reason</th>
                                    <th class="d-none">USRGRP</th>
                                    <th>Line</th>
                                    <th>Model</th>
                                    <th>Req. Qty</th>
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
<div class="modal fade" id="PR_MODPSN">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">PSN Data <span id="lblinfo_headerpsn"></span> </h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >PSN</span>
                        <input type="text" class="form-control" id="pareq_txtpsnsel" readonly>
                    </div>
                </div>
                <div class="col">
                    <button title="New" id="pareq_btnconform" class="btn btn-primary btn-sm" onclick="pareq_btnconform_eClick()">Conform</button>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="pareq_div_psn">
                        <table id="pareq_tbl_psn" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer;font-size:85%">
                            <thead class="table-light">
                                <tr>
                                    <th>Table</th>
                                    <th>Machine</th>
                                    <th>Item Code</th>
                                    <th>Item Name</th>
                                    <th class="text-end">Qty</th>
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
<script>
    var pareq_tbllength = 0;
    var pareq_tblrowindexsel = '';

    $("#pareqPeriod1").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    })

    $("#pareqPeriod2").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    })

    $("#pareqPeriod1").datepicker('update', new Date());
    $("#pareqPeriod2").datepicker('update', new Date());

    function pareqPeriod1OnChange() {
        pareq_txtsearch.focus()
    }

    function pareqPeriod2OnChange() {
        pareq_txtsearch.focus()
    }

    $('#pareq_tbl tbody').on( 'click', 'tr', function () {
        pareq_tblrowindexsel = $(this).index();
        if ($(this).hasClass('table-active') ) {
            $(this).removeClass('table-active');
        } else {
            $('#pareq_tbl tbody tr.table-active').removeClass('table-active');
            $(this).addClass('table-active');
        }
        $(this).find('td').eq(pareq_tblrowindexsel).addClass('mycellactive');
    });
    $("#PR_MOD").on('shown.bs.modal', function(){
        $("#pareq_txtsearch").focus();
    });
    function pareq_btnminus(){
        if(confirm("Are you sure want to delete ?")){
            let msi = document.getElementById('pareq_txtPRN').value;
            let table = $("#pareq_tbl tbody");
            let mline = table.find('tr').eq(pareq_tblrowindexsel).find('td').eq(3).text();
            if(mline.trim()==''){
                table.find('tr').eq(pareq_tblrowindexsel).remove();
            } else {
                let docno = document.getElementById('pareq_txtPRN').value;
                $.ajax({
                    type: "POST",
                    url: "<?=base_url('SPL/remove_partreq')?>",
                    data: {docno : docno, line: mline},
                    dataType: "json",
                    success: function (response) {
                        if(response.status[0].cd==1){
                            table.find('tr').eq(pareq_tblrowindexsel).remove();
                            if(table.find('tr').length==0){
                                document.getElementById('pareq_txtPRN').value = '';
                            }
                            alertify.success(response.status[0].msg);
                        } else {
                            alertify.message(response.status[0].msg);
                        }
                    }, error: (xhr, xopt, xthrow) => {
                        alertify.error(xthrow);
                    }
                });
            }
        }
    }

    function pareq_load_saved_data(pdata){
        document.getElementById('lblinfo_pareq_tbl').innerText = "Please wait...";
        $("#pareq_tbl tbody").empty();
        const div_alert = document.getElementById('pareq_alert_container')
        div_alert.innerHTML = ''
        $.ajax({
            type: "get",
            url: "<?=base_url('SPL/get_saved_partreq')?>",
            data: {indoc: pdata.pdoc},
            dataType: "json",
            success: function (response) {
                document.getElementById('lblinfo_pareq_tbl').innerText = "";
                let ttlrows = response.data.length;
                let tabell = document.getElementById("pareq_tbl");
                let tableku2 = tabell.getElementsByTagName("tbody")[0];
                let newrow, newcell, newText;
                tableku2.innerHTML='';

                for (let i = 0; i<ttlrows; i++){
                    newrow = tableku2.insertRow(-1);
                    newcell = newrow.insertCell(0);
                    newcell.contentEditable = true;
                    // newText = document.createTextNode();
                    newcell.innerHTML = response.data[i].SPL_REFDOCNO;
                    newcell = newrow.insertCell(1);
                    newcell.contentEditable = true;
                    newText = document.createTextNode(response.data[i].SPL_ITMCD);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(2);
                    newcell.contentEditable = true;
                    newcell.classList.add("text-end");
                    newText = document.createTextNode(numeral(response.data[i].SPL_QTYREQ).format(','));
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(3);
                    newcell.classList.add("d-none");
                    newText = document.createTextNode(response.data[i].SPL_LINEDATA);
                    newcell.appendChild(newText);
                    newcell = newrow.insertCell(4);
                    newcell.contentEditable = true;
                    newcell.classList.add('font-monospace')
                    newcell.innerHTML = response.data[i].SPL_ITMRMRK
                    newcell = newrow.insertCell(5);
                    newcell.contentEditable = true;
                    newcell.classList.add('font-monospace')
                    newcell.innerHTML = response.data[i].SPL_FMDL
                }

                if(pdata.reffData) {
                    const msg = `Data on ${pdata.reffData[0].lineData + smtGetOrdinal(pdata.reffData[0].lineData)} row could not be edited`
                    div_alert.innerHTML = `<div class="alert alert-warning alert-dismissible fade show" role="alert">
                    ${msg}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>`
                }
            }, error: function(xhr, xopt, xthrow){
                alertify.error(xthrow);
                document.getElementById('lblinfo_pareq_tbl').innerText = "";
            }
        });
    }
    function pareq_txtsearch_e_keypress(e){
        if(e.key=== 'Enter') {
            let searchval = document.getElementById('pareq_txtsearch').value;
            let searchby = document.getElementById('pareq_srchby').value;
            document.getElementById('lblinfo_pareq_tbl_saved').innerText ="Please wait...";
            $.ajax({
                type: "get",
                url: "<?=base_url('SPL/searchdok_partreq')?>",
                data: {indoc: searchval, indoctype: searchby, period1 : pareqPeriod1.value , period2 : pareqPeriod2.value},
                dataType: "json",
                success: function (response) {
                    let ttlrows = response.data.length;
                    document.getElementById('lblinfo_pareq_tbl_saved').innerText = ttlrows + " row(s) found";
                    if(ttlrows>0){
                        let mydes = document.getElementById("pareq_div_saved");
                        let myfrag = document.createDocumentFragment();
                        let mtabel = document.getElementById("pareq_tbl_saved");
                        let cln = mtabel.cloneNode(true);
                        myfrag.appendChild(cln);
                        let tabell = myfrag.getElementById("pareq_tbl_saved");
                        let tableku2 = tabell.getElementsByTagName("tbody")[0];
                        let newrow, newcell, newText;
                        tableku2.innerHTML='';
                        for (let i = 0; i<ttlrows; i++){
                            newrow = tableku2.insertRow(-1);
                            newcell = newrow.insertCell(0);
                            newcell.onclick = () => {
                                document.getElementById('pareq_txtPRN').value = response.data[i].SPL_DOC;
                                document.getElementById('pareq_category').value = response.data[i].CTG;
                                document.getElementById('pareq_txtRMRK').value = response.data[i].SPL_RMRK;
                                document.getElementById('pareq_txtdept').value = response.data[i].SPL_USRGRP;
                                document.getElementById('pareq_txtline').value = response.data[i].SPL_LINE;
                                document.getElementById('pareq_category').disabled = true;
                                document.getElementById('pareq_txtdept').disabled = true;
                                $("#PR_MOD").modal('hide');
                                pareq_load_saved_data({pdoc: response.data[i].SPL_DOC});
                            };
                            newText = document.createTextNode(response.data[i].SPL_DOC);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(1);
                            newText = document.createTextNode(response.data[i].DT);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(2);
                            newText = document.createTextNode(response.data[i].CTG);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(3);
                            newText = document.createTextNode(response.data[i].REFFDOC);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(4);
                            newText = document.createTextNode(response.data[i].FNM);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(5);
                            newcell.innerHTML = response.data[i].RQSRMRK_DESC
                            newcell = newrow.insertCell(6);
                            newcell.classList.add('d-none')
                            newcell.innerHTML = response.data[i].SPL_USRGRP
                            newcell = newrow.insertCell(7)
                            newcell.innerHTML = response.data[i].SPL_LINE
                            newcell = newrow.insertCell(8)
                            newcell.innerHTML = response.data[i].SPL_FMDL
                            newcell = newrow.insertCell(9)
                            newcell.classList.add('text-end')
                            newcell.innerHTML = numeral(response.data[i].REQQT).format(',')
                        }
                        mydes.innerHTML='';
                        mydes.appendChild(myfrag);
                    }
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                    document.getElementById('lblinfo_pareq_tbl_saved').innerText ="";
                }
            });
        }
    }
    function pareq_btnshow_saved(){
        $("#PR_MOD").modal('show');
    }
    function pareq_btnadd(){
        $('#pareq_tbl > tbody:last-child').append(`<tr style="cursor:pointer">
        <td contenteditable="true" onkeypress="pareq_kol1(event)"></td>
        <td contenteditable="true"></td>
        <td class="text-end" contenteditable="true"></td>
        <td class="d-none"></td>
        <td contenteditable="true" class="font-monospace">-</td>
        <td contenteditable="true" class="font-monospace"></td>
        </tr>`);
        pareq_tbllength = $('#pareq_tbl tbody > tr').length;
    }

    function pareq_kol1(e){
        if(e.key==='Enter'){
            let refftyped = e.srcElement.innerText.trim();
            let doc_category = document.getElementById('pareq_category').value;
            if(doc_category=="PSN"){
                document.getElementById('pareq_txtpsnsel').value = refftyped
                pareq_loadpsn(refftyped);
                $("#PR_MODPSN").modal('show');
            }
            e.preventDefault();
        }
    }

    function pareq_loadpsn(ppsn){
        document.getElementById('lblinfo_headerpsn').innerHTML = "<i class='fas fa-spinner fa-spin'></i>";
        pareq_tbl_psn.getElementsByTagName('tbody')[0].innerHTML = ''
        $.ajax({
            type: "get",
            url: "<?=base_url('SPL/getppsn2')?>",
            data: {ppsn: ppsn},
            dataType: "json",
            success: function (response) {
                document.getElementById('lblinfo_headerpsn').innerHTML = '';
                let ttlrows = response.data.length;
                if(ttlrows>0){
                    let mydes = document.getElementById("pareq_div_psn");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("pareq_tbl_psn");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("pareq_tbl_psn");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    let newrow, newcell, newText;
                    tableku2.innerHTML='';
                    for (let i = 0; i<ttlrows; i++){
                        newrow = tableku2.insertRow(-1)
                        newcell = newrow.insertCell(0)
                        newcell.innerHTML = response.data[i].PPSN2_MCZ
                        newcell = newrow.insertCell(1)
                        newcell.innerHTML = response.data[i].PPSN2_MC
                        newcell = newrow.insertCell(2)
                        newcell.innerHTML = response.data[i].PPSN2_SUBPN
                        newcell = newrow.insertCell(3)
                        newcell.innerHTML = response.data[i].MITM_SPTNO
                        newcell = newrow.insertCell(4)
                        newcell.classList.add('text-end')
                        newcell.contentEditable = true
                    }
                    mydes.innerHTML='';
                    mydes.appendChild(myfrag);
                }
            }, error: (xhr, xopt, xthrow) => {
                alertify.error(xthrow);
                document.getElementById('lblinfo_headerpsn').innerHTML = '';
            }
        });
    }

    function pareq_btnnew(){
        document.getElementById('pareq_txtPRN').value = "";
        $("#pareq_tbl tbody").empty();
        document.getElementById('pareq_category').disabled = false;
        document.getElementById('pareq_txtdept').disabled = false;
        pareq_btnadd();
    }

    function pareq_category_eChange(pthis){
        if(pthis.value=='PSN'){
            document.getElementById('pareq_txtline').focus()
        }
    }

    function pareq_btnsave(){
        let docno = document.getElementById('pareq_txtPRN').value;
        let dept = document.getElementById('pareq_txtdept').value;
        let category = document.getElementById('pareq_category').value;
        let remark = document.getElementById('pareq_txtRMRK').value;
        let line = document.getElementById('pareq_txtline').value;
        let a_reffdoc = [];
        let a_partCode = [];
        let a_qty = [];
        let a_line = [];
        let a_partRemark = [];
        let a_model = [];
        let mtbl = document.getElementById('pareq_tbl');
        let tableku2 = mtbl.getElementsByTagName("tbody")[0];
        let mtbltr = tableku2.getElementsByTagName('tr');
        let ttlrows = mtbltr.length;
        let partcode, qty, mmodel;

        

        if(category=='PSN' && line.trim().length==0) {
            alertify.warning('Line is required')
            document.getElementById('pareq_txtline').focus()
            return;
        }
        let isReffDocInputFilled = false
        for(let i=0; i<ttlrows; i++){
            let _reffDoc = tableku2.rows[i].cells[0].innerText.trim()

            partcode = tableku2.rows[i].cells[1].innerText.trim();
            qty = tableku2.rows[i].cells[2].innerText.trim().replace(/,/g, '');
            mmodel = tableku2.rows[i].cells[5].innerText.trim()
            if(partcode.length==0){
                alertify.warning("Part Code could not be empty");
                return;
            }
            if(qty.length==0){
                alertify.warning("QTY could not be empty");
                return;
            } else {
                if(isNaN(qty)){
                    alertify.warning("QTY should be numerical");
                    return;
                }
                if(numeral(qty).value<=0){
                    alertify.message('qty must be greater than zero');
                    return;
                }
            }
            if(mmodel.trim().length==0){
                alertify.warning("Model should not be empty")
                return
            }
            a_reffdoc.push(_reffDoc);
            a_partCode.push(partcode);
            a_qty.push(isNaN(tableku2.rows[i].cells[2].innerText) ? 0 : numeral(tableku2.rows[i].cells[2].innerText).value());
            a_line.push(tableku2.rows[i].cells[3].innerText.trim());
            a_partRemark.push(tableku2.rows[i].cells[4].innerText.trim());
            a_model.push(mmodel);

            if(_reffDoc.length>3) {
                isReffDocInputFilled = true
            }
        }
        if (remark == 2 && !isReffDocInputFilled) {
            alertify.warning('Please fill PSN or Job Number on Reff. Document column')
            return
        }
        if(a_reffdoc.length==0){
            alertify.message("Nothing will be saved"); return;
        }
        if(confirm("Are you sure ?")){
            pareq_btnsave.disabled = true
            $.ajax({
                type: "post",
                url: "<?=base_url('SPL/requestpart')?>",
                data: {docno: docno,  dept: dept, a_model: a_model
                , a_reffdoc: a_reffdoc, a_partCode: a_partCode, a_qty: a_qty, a_line: a_line
                , category: category, remark: remark, line: line, a_partRemark: a_partRemark},
                dataType: "json",
                success: function (response) {
                    pareq_btnsave.disabled = false
                    if(response.status[0].cd==1) {
                        alertify.success(response.status[0].msg);
                        document.getElementById('pareq_txtPRN').value = response.status[0].doc;
                        pareq_load_saved_data({pdoc : response.status[0].doc, reffData: response.status[0].data});
                    } else {
                        alertify.warning(response.status[0].msg);
                    }
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                    pareq_btnsave.disabled = false
                }
            });
        }
    }

    function pareq_btnconform_eClick(){
        let mtbl = document.getElementById('pareq_tbl_psn');
        let tableku2 = mtbl.getElementsByTagName("tbody")[0];
        let ttlrows = tableku2.getElementsByTagName('tr').length;
        let a_itemcode = [];
        let a_qty = [];
        for(let i=0; i<ttlrows; i++){
            let tQty = tableku2.rows[i].cells[4].innerText.trim();
            if(tQty.length>0){
                a_itemcode.push(tableku2.rows[i].cells[2].innerText)
                a_qty.push(numeral(tQty).value())
            }
        }
        let a_length = a_itemcode.length;
        let psnno = document.getElementById('pareq_txtpsnsel').value;
        for(let i=0;i<a_length; i++){
            $('#pareq_tbl > tbody:last-child').append(`<tr style="cursor:pointer">
            <td contenteditable="true" onkeypress="pareq_kol1(event)">${psnno}</td>
            <td contenteditable="true">${a_itemcode[i]}</td>
            <td class="text-end" contenteditable="true">${a_qty[i]}</td>
            <td class="d-none"></td>
            <td contenteditable="true"></td>
            <td contenteditable="true"></td>
            </tr>`);
        }
        $("#PR_MODPSN").modal('hide');
        let table = $("#pareq_tbl tbody");
        let psnCol,itemCol;
        ttlrows = table.find('tr').length;

        for(let i=0; i<ttlrows;i++){
            psnCol = table.find('tr').eq(i).find('td').eq(0).text()
            itemCol = table.find('tr').eq(i).find('td').eq(1).text()
            if(psnCol.length>0 && itemCol.length==0) {
                table.find('tr').eq(i).remove();
            }
        }
        pareq_tbllength = table.find('tr').length;
    }

    function pareq_srchbyOnChange(p) {
        if(p.value === 'D1') {
            pareqPeriodContainer.classList.add('d-none')
        } else {
            pareqPeriodContainer.classList.remove('d-none')
        }
        pareq_txtsearch.focus()
    }
</script>