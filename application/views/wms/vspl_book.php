<div style="padding: 5px" >
	<div class="container-fluid">
        <div class="row" id="splbook_stack0">
            <div class="col-md-2 mb-1">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary" id="splbook_btn_new" onclick="splbook_btn_new_eC()"><i class="fas fa-file"></i></button>
                    <button class="btn btn-outline-primary" id="splbook_btn_save" onclick="splbook_btn_save_eC()"><i class="fas fa-save"></i></button>
                </div>
            </div>
        </div>
        <div class="row" id="splbook_stack1">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Book ID</label>
                    <input type="text" class="form-control" id="splbook_txt_doc" maxlength="50" readonly>
                    <button class="btn btn-primary" id="splbook_btnmod" onclick="splbook_btnmod_eC()"><i class="fas fa-search"></i></button>
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Date</label>
                    <input type="text" class="form-control" id="splbook_txt_date" readonly>
                </div>
            </div>
        </div>
        <div class="row" id="splbook_stack2">
            <div class="col-md-12 mb-1" >
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary" id="splbook_plus_1" title="Add data based on PSN" onclick="splbook_plus_PO_1_eC()">Add PSN</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1" >  
                <div class="table-responsive" id="splbook_divku_1">
                    <table id="splbook_tbl_1" class="table table-sm table-hover table-bordered" style="width:100%;font-size:80%">
                        <thead class="table-light">
                            <tr class="first">
                                <th class="d-none">id</th> <!-- 0 -->                                
                                <th>PSN</th> <!-- 1 -->
                                <th>Category</th> <!-- 2 -->
                                <th>Part Code</th> <!-- 3 -->
                                <th>Part Name</th> <!-- 4 -->
                                <th class="text-end">QTY</th> <!-- 5 -->
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
<div class="modal fade" id="splbookBAL_Mod">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
      <div class="modal-content"> 
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">PSN List</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >Search by</span>
                        <select class="form-select" id="splbook_searchby" onchange="document.getElementById('splbook_txtsearch').focus()">
                            <option value="PSN">PSN</option>
                            <option value="WO">WO</option>
                        </select>
                        <input type="text" class="form-control" id="splbook_txtsearch" onkeypress="splbook_txtsearch_eKP(event)" maxlength="44" required placeholder="..." onfocus="this.setSelectionRange(0, this.value.length)">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="splbook_tbl_div">
                        <table id="splbook_tbl" class="table table-sm table-striped table-bordered table-hover" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th class="align-middle">PSN</th>
                                    <th class="align-middle">Category</th>
                                    <th class="align-middle">Part Code</th>
                                    <th class="align-middle">Part Name</th>
                                    <th class="align-middle text-end">Qty</th>
                                    <th class="align-middle text-center"><input type="checkbox" class="form-check-input" id="splbook_ck"></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary btn-sm" data-bs-dismiss="modal" id="splbook_btnuse" onclick="splbook_btnuse_eCK()">Use</button>
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="splbookSaved_Mod">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
      <div class="modal-content"> 
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Booking List</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >Search by</span>
                        <select class="form-select" id="splbook_svd_searchby" onchange="document.getElementById('splbook_svd_txtsearch').focus()">
                            <option value="ID">ID</option>
                            <option value="PSN">PSN</option>
                        </select>
                        <input type="text" class="form-control" id="splbook_svd_txtsearch" onkeypress="splbook_svd_txtsearch_eKP(event)" maxlength="44" required placeholder="..." onfocus="this.setSelectionRange(0, this.value.length)">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="splbook_svd_tbl_div">
                        <table id="splbook_svd_tbl" class="table table-sm table-striped table-bordered table-hover" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th class="align-middle">Book ID</th>
                                    <th class="align-middle">PSN</th>
                                    <th class="align-middle">Category</th>
                                    <th class="align-middle">Date</th>
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
    $("#splbook_txt_date").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    })
    $("#splbook_txt_date").datepicker('update', new Date())
    function splbook_plus_PO_1_eC(){
        $("#splbookBAL_Mod").modal('show')
    }
    $("#splbookBAL_Mod").on('shown.bs.modal', function(){
        document.getElementById('splbook_txtsearch').focus()
    })
    function splbook_txtsearch_eKP(e){
        if(e.key==='Enter'){
            const searchby = document.getElementById('splbook_searchby').value
            e.target.readOnly = true
            $.ajax({                
                url: "<?=base_url('SPL/search_ready_to_book')?>",
                data: {search: e.target.value, searchby: searchby},
                dataType: "json",
                success: function (response) {
                    e.target.readOnly = false
                    let ttlrows = response.data.length;
                    let mydes = document.getElementById("splbook_tbl_div");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("splbook_tbl");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("splbook_tbl");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];
                    const ck = myfrag.getElementById('splbook_ck')                    
                    let newrow, newcell, newText;
                    tableku2.innerHTML=''                                        
                    for (let i = 0; i<ttlrows; i++){
                        newrow = tableku2.insertRow(-1)
                        newcell = newrow.insertCell(0)
                        newcell.innerHTML = response.data[i].SPL_DOC
                        newcell = newrow.insertCell(1)
                        newcell.innerHTML = response.data[i].SPL_CAT
                        newcell = newrow.insertCell(2)
                        newcell.innerHTML = response.data[i].SPL_ITMCD
                        newcell = newrow.insertCell(3)
                        newcell.innerHTML = response.data[i].ITMD1
                        newcell = newrow.insertCell(4)
                        newcell.classList.add('text-end')
                        newcell.innerHTML = numeral(response.data[i].BALQT).format(',')
                        newcell = newrow.insertCell(5)
                        newcell.classList.add('text-center')
                        newText = document.createElement('input')
                        newText.type = "checkbox"
                        newText.classList.add('form-check-input')
                        newcell.appendChild(newText)
                    }
                    ck.onclick = (e) => {                        
                        for (let i = 0; i<ttlrows; i++){
                            tableku2.rows[i].cells[5].getElementsByTagName('input')[0].checked = e.target.checked
                        }
                    }
                    mydes.innerHTML=''
                    mydes.appendChild(myfrag)
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow)
                    e.target.readOnly = false
                }
            })
        }
    }
    function splbook_btnuse_eCK(){
        const tbl = document.getElementById('splbook_tbl').getElementsByTagName('tbody')[0]
        const ttlrows = tbl.getElementsByTagName('tr').length
        let data = [];
        for(let i=0;i<ttlrows;i++){
            if(tbl.rows[i].cells[5].getElementsByTagName('input')[0].checked){
                data.push({
                    PSN: tbl.rows[i].cells[0].innerText,
                    CAT: tbl.rows[i].cells[1].innerText,
                    PC: tbl.rows[i].cells[2].innerText,
                    PN: tbl.rows[i].cells[3].innerText,
                    QT: tbl.rows[i].cells[4].innerText
                })
            }
        }
        let mydes = document.getElementById("splbook_divku_1");
        let myfrag = document.createDocumentFragment();
        let mtabel = document.getElementById("splbook_tbl_1");
        let cln = mtabel.cloneNode(true);
        myfrag.appendChild(cln);
        let tabell = myfrag.getElementById("splbook_tbl_1");
        let tableku2 = tabell.getElementsByTagName("tbody")[0]                        
        let newrow, newcell;
        for(let c in data){
            newrow = tableku2.insertRow(-1)
            newcell = newrow.insertCell(0)
            newcell.classList.add('d-none')            
            newcell = newrow.insertCell(1)
            newcell.innerHTML = data[c].PSN
            newcell = newrow.insertCell(2)
            newcell.innerHTML = data[c].CAT
            newcell = newrow.insertCell(3)
            newcell.innerHTML = data[c].PC
            newcell = newrow.insertCell(4)
            newcell.innerHTML = data[c].PN
            newcell = newrow.insertCell(5)
            newcell.classList.add('text-end')
            newcell.innerHTML = data[c].QT
        }
        mydes.innerHTML=''
        mydes.appendChild(myfrag)
        tbl.innerHTML = ''
    }

    function splbook_btn_save_eC(){
        const tbl = document.getElementById('splbook_tbl_1').getElementsByTagName('tbody')[0]
        const ttlrows = tbl.getElementsByTagName('tr').length
        const bookid = document.getElementById('splbook_txt_doc')
        const bookdate = document.getElementById('splbook_txt_date').value
        let inid = []
        let inpsn = []
        let incat = []
        let inpc = []
        let inqt = []
        for(let i=0;i<ttlrows;i++){            
            inid.push(tbl.rows[i].cells[0].innerText)
            inpsn.push(tbl.rows[i].cells[1].innerText)
            incat.push(tbl.rows[i].cells[2].innerText)
            inpc.push(tbl.rows[i].cells[3].innerText)
            inqt.push(numeral(tbl.rows[i].cells[5].innerText).value())
        }
        if(inpsn.length>0){
            if(!confirm("Are you sure ?")){
                return                
            }
            $.ajax({
                type: "POST",
                url: "<?=base_url('SPL/book')?>",
                data: {dinid: inid, dinpsn: inpsn, dincat: incat, dinpc: inpc, dinqt: inqt,
                hbookid: bookid.value, hbookdate: bookdate},
                dataType: "json",
                success: function (response) {
                    if(response.status[0].cd==1){
                        alertify.success(response.status[0].msg)
                        bookid.value = response.status[0].doc
                        splbook_load(response.status[0].doc)
                    } else {
                        alertify.warning(response.status[0].msg)
                    }
                }, error(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            })
        }
    }

    function splbook_load(pdoc){
        $.ajax({            
            url: "<?=base_url('SPL/book_detail')?>",
            data: {doc: pdoc},
            dataType: "json",
            success: function (response) {
                let ttlrows = response.data.length;
                let mydes = document.getElementById("splbook_divku_1");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("splbook_tbl_1");
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln);
                let tabell = myfrag.getElementById("splbook_tbl_1");
                let tableku2 = tabell.getElementsByTagName("tbody")[0];                                    
                let newrow, newcell, newText;
                tableku2.innerHTML=''                                        
                for (let i = 0; i<ttlrows; i++){
                    newrow = tableku2.insertRow(-1)
                    newcell = newrow.insertCell(0)
                    newcell.classList.add('d-none')
                    newcell.innerHTML = response.data[i].SPLBOOK_LINE
                    newcell = newrow.insertCell(1)                    
                    newcell.innerHTML = response.data[i].SPLBOOK_SPLDOC
                    newcell = newrow.insertCell(2)
                    newcell.innerHTML = response.data[i].SPLBOOK_CAT
                    newcell = newrow.insertCell(3)
                    newcell.innerHTML = response.data[i].SPLBOOK_ITMCD
                    newcell = newrow.insertCell(4)
                    newcell.innerHTML = response.data[i].ITMD1
                    newcell = newrow.insertCell(5)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = numeral(response.data[i].SPLBOOK_QTY).format(',')
                }
                mydes.innerHTML=''
                mydes.appendChild(myfrag)
            }
        });
    }

    function splbook_btnmod_eC(){
        $("#splbookSaved_Mod").modal('show')
    }

    $("#splbookSaved_Mod").on('shown.bs.modal', function(){
        document.getElementById('splbook_svd_txtsearch').focus()
    })

    function splbook_svd_txtsearch_eKP(e){
        if(e.key==='Enter'){
            const searchby = document.getElementById('splbook_svd_searchby').value
            e.target.readOnly = true
            $.ajax({                
                url: "<?=base_url('SPL/book_header')?>",
                data: {search: e.target.value, searchby: searchby},
                dataType: "json",
                success: function (response) {
                    e.target.readOnly = true
                    let ttlrows = response.data.length;
                    let mydes = document.getElementById("splbook_svd_tbl_div");
                    let myfrag = document.createDocumentFragment();
                    let mtabel = document.getElementById("splbook_svd_tbl");
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln);
                    let tabell = myfrag.getElementById("splbook_svd_tbl");
                    let tableku2 = tabell.getElementsByTagName("tbody")[0];                                    
                    let newrow, newcell, newText;
                    tableku2.innerHTML=''                                        
                    for (let i = 0; i<ttlrows; i++){
                        newrow = tableku2.insertRow(-1)
                        newcell = newrow.insertCell(0)
                        newcell.style.cssText = 'cursor:pointer'
                        newcell.onclick = () => {
                            splbook_load(response.data[i].SPLBOOK_DOC)
                            $("#splbookSaved_Mod").modal('hide')
                        }
                        newcell.innerHTML = response.data[i].SPLBOOK_DOC
                        newcell = newrow.insertCell(1)
                        newcell.innerHTML = response.data[i].SPLBOOK_SPLDOC
                        newcell = newrow.insertCell(2)
                        newcell.innerHTML = response.data[i].SPLBOOK_CAT
                        newcell = newrow.insertCell(3)
                        newcell.innerHTML = response.data[i].SPLBOOK_DATE
                    }
                    mydes.innerHTML=''
                    mydes.appendChild(myfrag)
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow)
                    e.target.readOnly = false
                }
            })
        }
    }
</script>