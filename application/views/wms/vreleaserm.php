<div style="padding: 10px">
	<div class="container-fluid">
        <div class="row">
            <div class="col-md-2 mb-1">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" id="rlsrm_btn_new" onclick="rlsrm_btn_new_e_click()"><i class="fas fa-file"></i> </button>
                    <button class="btn btn-primary" id="rlsrm_btn_save" onclick="rlsrm_btn_save_e_click()"><i class="fas fa-save"></i> </button>
                    <button class="btn btn-primary" id="rlsrm_btn_print" onclick="rlsrm_btn_print_e_click()"><i class="fas fa-print"></i> </button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Document</label>
                    <input type="text" class="form-control" id="rlsrm_txt_doc" placeholder="<<auto number>>" readonly>                    
                    <button class="btn btn-primary" id="rlsrm_btnmod" onclick="rlsrm_btnmod_e_click()"><i class="fas fa-search"></i></button>                    
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Date</label>
                    <input type="text" class="form-control" id="rlsrm_txt_date" readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-1">
                <span id="rlsrm_tbl_lblinfo" class="badge bg-info"></span>
            </div>
            <div class="col-md-6 mb-1 text-end">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" id="rlsrm_btnplus" onclick="rlsrm_btnadd()"><i class="fas fa-plus"></i></button>
                    <button class="btn btn-warning" id="rlsrm_btnmins" onclick="rlsrm_btnmins_e_click()"><i class="fas fa-minus"></i></button>
                </div>
            </div>
        </div>       
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="rlsrm_divku">
                    <table id="rlsrm_tbl" class="table table-sm table-hover table-bordered" style="width:100%;cursor:pointer">
                        <thead class="table-light">
                            <tr>                                
                                <th>Pending Doc. Number</th>
                                <th>Item Code</th>
                                <th>Lot Number</th>
                                <th class="text-end">QTY</th>
                                <th class="d-none">line</th>
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
<div class="modal fade" id="rlsrm_MOD">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Release Document List</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >Search</span>
                        <select id="rlsrm_itm_srchby" onchange="rlsrm_e_fokus()" class="form-select">
                            <option value="no">Document No</option>
                            <!-- <option value="rmrk">Remarks</option> -->
                        </select>
                        <input type="text" class="form-control" id="rlsrm_txtsearch" onkeypress="rlsrm_e_search(event)" maxlength="15" onfocus="this.select()" required placeholder="...">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col mb-1 text-right">
                    <span class="badge bg-info" id="rlsrm_lblinfo"></span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="rlsrm_divkurls">
                        <table id="rlsrm_tblsaved" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer">
                            <thead class="table-light">
                                <tr>
                                    <th>Document</th>
                                    <th>Date</th>
                                    <th class="text-right">Total Item</th>
                                    <th>Remark</th>
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
<div class="modal fade" id="rlsrm_PNDMOD">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Document of Pending-RM List</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Search</span>
                        <select id="rlsrm_sel_pnd_itm_srchby" onchange="rlsrm_sel_pnd_e_fokus()" class="form-select">
                            <option value="no">Document No</option>
                            <option value="lot">Lot No.</option>
                        </select>
                        <input type="text" class="form-control" id="rlsrm_txtsearch_pnd" onkeypress="rlsrm_e_search_pnd_doc(event)" maxlength="15" onfocus="this.select()" required placeholder="...">                        
                    </div>
                </div>
            </div>            
            <div class="row">
                <div class="col mb-1 text-right">
                    <span class="badge bg-info" id="rlsrm_search_pnd_doc_lblinfo"></span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="rlsrm_divku_searchpnd">
                        <table id="rlsrm_pnd_tbl" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer">
                            <thead class="table-light">
                                <tr>
                                    <th rowspan="2" class="align-middle">Document No.</th>
                                    <th rowspan="2" class="align-middle">Date</th>
                                    <th rowspan="2" class="align-middle">Item Code</th>
                                    <th rowspan="2" class="align-middle">Lot Number</th>
                                    <th colspan="3" class="text-center">Qty</th>
                                </tr>
                                <tr>
                                    <th class="text-end">Pending</th>
                                    <th class="text-end">Released</th>
                                    <th class="text-end">Balance</th>
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
    var rlsrm_selrow = '';

    function rlsrm_btn_new_e_click() {
        document.getElementById('rlsrm_tbl').getElementsByTagName('tbody')[0].innerHTML = ''
        document.getElementById('rlsrm_txt_doc').value = ''
    }

    function rlsrm_btn_print_e_click(){
        let pendingdoc = document.getElementById('rlsrm_txt_doc').value;
        if(pendingdoc.trim().length>0){
            Cookies.set('CKRLSRMDOC_NO', pendingdoc , {expires:365});
            window.open("<?=base_url('printreleaserm_doc')?>" ,'_blank');
        } else {
            alertify.message("Please select document first");
        }
    }

    function rlsrm_e_bind_table(){
        $('#rlsrm_tbl tbody').on( 'click', 'tr', function () {  
            rlsrm_selrow = $(this).index();        
            if ($(this).hasClass('table-active') ) {
                $(this).removeClass('table-active');
            } else {                    			
                $('#rlsrm_tbl tbody tr.table-active').removeClass('table-active');
                $(this).addClass('table-active');
            }
        });
    }
    rlsrm_e_bind_table();

    function rlsrm_btnmins_e_click(){        
        if(rlsrm_selrow.toString().length>0){
            let fel_body = document.getElementById('rlsrm_tbl').getElementsByTagName('tbody')[0];
            let lotno = fel_body.rows[rlsrm_selrow].cells[2].innerText;
            let line = fel_body.rows[rlsrm_selrow].cells[4].innerText;
            let rlsdoc = document.getElementById('rlsrm_txt_doc').value;            
            if(line.trim().length>0){
                if(confirm("Delete "+lotno+", are You sure ?")){
                    $.ajax({
                        type: "post",
                        url: "<?=base_url('RLS/removerm')?>",
                        data: {inrlsdoc: rlsdoc, inline: line},
                        dataType: "json",
                        success: function (response) {
                            if(response.status[0].cd==1){
                                alertify.success(response.status[0].msg);
                                document.getElementById('rlsrm_tbl').getElementsByTagName('tbody')[0].deleteRow(rlsrm_selrow);
                            } else {
                                alertify.message(response.status[0].msg);
                            }
                        }, error: function(xhr, xopt, xthrow){
                            alertify.error(xthrow);
                        }
                    });
                }
            } else {
                document.getElementById('rlsrm_tbl').getElementsByTagName('tbody')[0].deleteRow(rlsrm_selrow);
            }
            document.getElementById("rlsrm_tbl_lblinfo").innerText = "";
        }
    }
    
    function rlsrm_e_search(e){
        if(e.which==13){
            let searchv = document.getElementById('rlsrm_txtsearch').value;
            document.getElementById('rlsrm_lblinfo').innerText = "Please wait...";
            $.ajax({
                type: "get",
                url: "<?=base_url('RLS/search')?>",
                data: {insearch: searchv},
                dataType: "json",
                success: function (response) {
                    let ttlrows = response.data.length;
                    document.getElementById('rlsrm_lblinfo').innerText = ttlrows + " row(s) found";
                    let mydes = document.getElementById("rlsrm_divkurls");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("rlsrm_tblsaved");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("rlsrm_tblsaved");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];                        
                    let newrow, newcell, newText;
                    tableku2.innerHTML='';
                    if(ttlrows>0){
                        for (let i = 0; i<ttlrows; i++){
                            newrow = tableku2.insertRow(-1);
                            newrow.onclick = function(){
                                document.getElementById('rlsrm_txt_doc').value = response.data[i].RLS_DOC;
                                rlsrm_e_loadsaved();
                                $("#rlsrm_MOD").modal('hide');
                            }
                            newcell = newrow.insertCell(0);
                            newText = document.createTextNode(response.data[i].RLS_DOC);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(1);
                            newText = document.createTextNode(response.data[i].RLS_DT);
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(2);
                            newText = document.createTextNode(numeral(response.data[i].ITEMCOUNT).format(','));
                            newcell.appendChild(newText);
                            newcell = newrow.insertCell(3);
                            newText = document.createTextNode('');
                            newcell.appendChild(newText);
                        }
                    }                    
                    mydes.innerHTML='';
                    mydes.appendChild(myfrag);
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            });
        }
    }
    function rlsrm_e_fokus(){
        document.getElementById('rlsrm_txtsearch').focus();
    }
    function rlsrm_btnmod_e_click(){
        $("#rlsrm_MOD").modal('show');
    }

    $("#rlsrm_txt_date").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });
    $("#rlsrm_txt_date").datepicker('update', new Date());

    function rlsrm_btnadd(){
        $("#rlsrm_PNDMOD").modal('show');
    }

    $("#rlsrm_PNDMOD").on('shown.bs.modal', function(){
        $("#rlsrm_pnd_tbl tbody").empty();
        $("#rlsrm_txtsearch_pnd").focus();
    });
    $("#rlsrm_MOD").on('shown.bs.modal', function(){       
        $("#rlsrm_txtsearch").focus();
    });

    function rlsrm_e_search_pnd_doc(e){
        if(e.which==13){
            let search = document.getElementById('rlsrm_txtsearch_pnd').value;
            let search_by = document.getElementById('rlsrm_sel_pnd_itm_srchby').value;
            $("#rlsrm_pnd_tbl tbody").empty();
            document.getElementById('rlsrm_search_pnd_doc_lblinfo').innerText = "Please wait...";
            $.ajax({
                type: "get",
                url: "<?=base_url('RLS/search_pnd_doc_balance')?>",
                data: {insearchby: search_by, insearch: search},
                dataType: "json",
                success: function (response) {
                    let ttlrows = response.data.length;
                    document.getElementById('rlsrm_search_pnd_doc_lblinfo').innerText = ttlrows + " row(s) found";
                    let mydes = document.getElementById("rlsrm_divku_searchpnd");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("rlsrm_pnd_tbl");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("rlsrm_pnd_tbl");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];                        
                    let newrow, newcell, newText;
                    tableku2.innerHTML='';
                    for (let i = 0; i<ttlrows; i++){
                        newrow = tableku2.insertRow(-1);
                        newrow.style.cssText = 'cursor:pointer';  
                        if(response.data[i].BALQTY>0){
                            newrow.onclick = function(){
                                if(rlsrm_prepare_add({
                                    PNDDOC: response.data[i].PND_DOC
                                    ,ITMCD: response.data[i].PND_ITMCD
                                    ,LOTNO: response.data[i].PND_ITMLOT
                                    ,BALQTY: numeral(response.data[i].BALQTY).value()
                                })
                                ){
                                    $("#rlsrm_PNDMOD").modal('hide');
                                }                                
                            };
                        } else {
                            newrow.classList.add('table-success');
                        }
                        newcell = newrow.insertCell(0);
                        newText = document.createTextNode(response.data[i].PND_DOC);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(1);
                        newText = document.createTextNode(response.data[i].MINPND_DT);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(2);
                        newText = document.createTextNode(response.data[i].PND_ITMCD);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(3);
                        newText = document.createTextNode(response.data[i].PND_ITMLOT);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(4);
                        newcell.classList.add('text-end');
                        newText = document.createTextNode(numeral(response.data[i].PNDQTY).format(','));
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(5);
                        newcell.classList.add('text-end');
                        newText = document.createTextNode(numeral(response.data[i].RLSQTY).format(','));
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(6);
                        newcell.classList.add('text-end');
                        newText = document.createTextNode(numeral(response.data[i].BALQTY).format(','));
                        newcell.appendChild(newText);
                    }
                    mydes.innerHTML='';
                    mydes.appendChild(myfrag);
                }
            });
        }
    }

    function rlsrm_prepare_add(pdata){
        let tabell = document.getElementById("rlsrm_tbl");
        let tableku2 = tabell.getElementsByTagName("tbody")[0];
        let newrow, newcell, newText;
        let mtbltr = tableku2.getElementsByTagName('tr');
        let ttlrows = mtbltr.length;
        let isok = true;
        if(ttlrows>0){
            for(let i=0;i<ttlrows;i++){
                if(
                    tableku2.rows[i].cells[0].innerText==pdata.PNDDOC 
                    && tableku2.rows[i].cells[1].innerText==pdata.ITMCD 
                    && tableku2.rows[i].cells[2].innerText==pdata.LOTNO 
                    ){
                    isok = false;
                    break;
                }
            }
        }
        if(isok){
            newrow = tableku2.insertRow(-1);
            newcell = newrow.insertCell(0);
            newText = document.createTextNode(pdata.PNDDOC);
            newcell.appendChild(newText);
            newcell = newrow.insertCell(1);
            newText = document.createTextNode(pdata.ITMCD);
            newcell.appendChild(newText);
            newcell = newrow.insertCell(2);
            newText = document.createTextNode(pdata.LOTNO);
            newcell.appendChild(newText);
            newcell = newrow.insertCell(3);
            newcell.contentEditable = true;
            newcell.classList.add('text-end');
            newText = document.createTextNode('');
            newcell.appendChild(newText);
            newcell = newrow.insertCell(4);     
            newcell.classList.add('d-none');
            newText = document.createTextNode('');
            newcell.appendChild(newText);
        } else {
            alertify.warning('the data is already added');
        }
        return isok;
    }

    function rlsrm_sel_pnd_e_fokus(){
        document.getElementById('rlsrm_txtsearch_pnd').focus();
    }

    function rlsrm_btn_save_e_click(){
        let releasedoc = document.getElementById('rlsrm_txt_doc').value;
        let releasedate = document.getElementById('rlsrm_txt_date').value;
        let aPNDDoc = [];
        let aPNDItem = [];
        let aPNDLot = [];
        let aPNDQty = [];
        let aLine = [];
        let tabell = document.getElementById("rlsrm_tbl");
        let tableku2 = tabell.getElementsByTagName("tbody")[0];
        let mtbltr = tableku2.getElementsByTagName('tr');
        let ttlrows = mtbltr.length;

        for(let i=0;i<ttlrows;i++){
            if(tableku2.rows[i].cells[3].innerText.trim()==''){
                alertify.message('Qty could not be empty');
                return;
            }
            aPNDDoc.push(tableku2.rows[i].cells[0].innerText);
            aPNDItem.push(tableku2.rows[i].cells[1].innerText);
            aPNDLot.push(tableku2.rows[i].cells[2].innerText);
            aPNDQty.push(numeral(tableku2.rows[i].cells[3].innerText).value());
            aLine.push(tableku2.rows[i].cells[4].innerText);
        }
        document.getElementById('rlsrm_btn_save').disabled = true;
        if(aPNDDoc.length==0){
            alertify.message("nothing will be proceed");
            return;
        }
        if(confirm("Are you sure ?")){
            $.ajax({
                type: "post",
                url: "<?=base_url('RLS/setrm')?>",
                data: {inrlsdoc: releasedoc, inrlsdt: releasedate, inapnddoc: aPNDDoc, inaitem: aPNDItem, inalot: aPNDLot, inaqty: aPNDQty, inline: aLine},
                dataType: "json",
                success: function (response) {
                    document.getElementById('rlsrm_btn_save').disabled = false;
                    if(response.status[0].cd==1){
                        alertify.success(response.status[0].msg);
                        document.getElementById('rlsrm_txt_doc').value = response.status[0].doc;
                        rlsrm_e_loadsaved();
                    } else {
                        alertify.message(response.status[0].msg);
                        alertify.warning('Doc. No. : '+ response.datanotok.PNDDOC
                        + '<br> Item : '+response.datanotok.PNDITEM);
                    }
                }, error: function(xhr, xopt, xthrow){
                    document.getElementById('rlsrm_btn_save').disabled = false;
                    alertify.error(xthrow);
                }
            });
        }        
    }

    function rlsrm_e_loadsaved(){
        let releasedoc = document.getElementById('rlsrm_txt_doc').value;
        document.getElementById('rlsrm_tbl_lblinfo').innerText = "Please wait...";
        $.ajax({
            type: "get",
            url: "<?=base_url('RLS/getrm_bydoc')?>",
            data: {indoc: releasedoc},
            dataType: "json",
            success: function (response) {
                let ttlrows = response.data.length;
                document.getElementById('rlsrm_tbl_lblinfo').innerText = ttlrows + " row(s) found";
                let mydes = document.getElementById("rlsrm_divku");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("rlsrm_tbl");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("rlsrm_tbl");
                let tableku2 = tabell.getElementsByTagName("tbody")[0];                        
                let newrow, newcell, newText;
                tableku2.innerHTML='';
                if(ttlrows>0){
                    document.getElementById('rlsrm_txt_doc').value = response.data[0].RLS_DOC;
                    document.getElementById('rlsrm_txt_date').value = response.data[0].RLS_DT;
                    for (let i = 0; i<ttlrows; i++){
                        newrow = tableku2.insertRow(-1);
                        newcell = newrow.insertCell(0);
                        newText = document.createTextNode(response.data[i].RLS_REFFDOC);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(1);
                        newText = document.createTextNode(response.data[i].RLS_ITMCD);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(2);
                        newText = document.createTextNode(response.data[i].RLS_ITMLOT);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(3);
                        newcell.classList.add('text-end');
                        newcell.contentEditable = true;
                        newText = document.createTextNode(response.data[i].RLS_QTY);
                        newcell.appendChild(newText);
                        newcell = newrow.insertCell(4);
                        newcell.classList.add('d-none')
                        newText = document.createTextNode(response.data[i].RLS_LINE);
                        newcell.appendChild(newText);
                    }
                }
                mydes.innerHTML='';
                mydes.appendChild(myfrag);
                rlsrm_selrow = '';
                rlsrm_e_bind_table();
            }, error: function(xhr, xopt, xthrow){                
                alertify.error(xthrow);
            }
        });
    }
</script>