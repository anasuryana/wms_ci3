<style type="text/css">
	.tagbox-remove{
		display: none;
	}
    .txfg_cell:hover{
        font-weight: 900;
    }
    thead tr.first th, thead tr.first td {
        position: sticky;
        top: 0;        
    }

    thead tr.second th, thead tr.second td {
        position: sticky;
        top: 26px;
    }    
</style>
<div style="padding: 5px" >
	<div class="container-fluid">
        <div class="row" id="rcvnonitem_stack0">
            <div class="col-md-2 mb-1">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary" id="rcvnonitem_btn_new" onclick="rcvnonitem_btn_new_eC()"><i class="fas fa-file"></i></button>
                    <button class="btn btn-outline-primary" id="rcvnonitem_btn_save" onclick="rcvnonitem_btn_save_eC()"><i class="fas fa-save"></i></button>
                </div>
            </div>
        </div>
        <div class="row" id="rcvnonitem_stack1">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">DO No</label>
                    <input type="text" class="form-control" id="rcvnonitem_txt_doc" maxlength="50">
                    <button class="btn btn-primary" id="rcvnonitem_btnmod" onclick="rcvnonitem_btnmod_eC()"><i class="fas fa-search"></i></button>
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Receive Date</label>
                    <input type="text" class="form-control" id="rcvnonitem_txt_rcvdate" readonly>
                </div>
            </div>
        </div>
        <div class="row" id="rcvnonitem_stack2">
            <div class="col-md-12 mb-1" >
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary" id="rcvnonitem_plus_1" title="Add from PO" onclick="rcvnonitem_plus_PO_1_eC()">Add from PO</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1" >  
                <div class="table-responsive" id="rcvnonitem_divku_1">
                    <table id="rcvnonitem_tbl_1" class="table table-sm table-hover table-bordered" style="width:100%;font-size:75%">
                        <thead class="table-light">
                            <tr class="first">
                                <th class="d-none">GRLNO</th> <!-- 0 -->                                
                                <th>PO No</th> <!-- 1 -->                                
                                <th>Item Name</th> <!-- 2 -->
                                <th class="text-end">QTY</th> <!-- 3 -->
                                <th title="Unit Measurement">UM</th> <!-- 4 -->
                                <th class="text-end">Price</th> <!-- 5 -->
                                <th class="text-end">Amount</th> <!-- 6 -->                                
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
<div class="modal fade" id="rcvnonitem_POBAL_Mod">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
      <div class="modal-content"> 
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">PO List</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >PO No</span>                        
                        <input type="text" class="form-control" id="rcvnonitem_po_txtsearch" onkeypress="rcvnonitem_po_txtsearch_eKP(event)" maxlength="44" required placeholder="...">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="rcvnonitem_po_tbl_div">
                        <table id="rcvnonitem_po_tbl" class="table table-sm table-striped table-bordered table-hover" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th rowspan="2" class="align-middle">Document Number</th>
                                    <th rowspan="2" class="align-middle">Supplier</th>
                                    <th colspan="2" class="text-center">Date</th>                                    
                                    <th rowspan="2" class="align-middle">Item Name</th>
                                    <th colspan="2" class="align-middle text-center">Qty</th>
                                    <th rowspan="2" class="align-middle text-center">UM</th>
                                    <th rowspan="2" class="align-middle text-center">Price</th>
                                    <th rowspan="2" class="align-middle text-center d-none">SUPPLIERCODE</th>
                                </tr>
                                <tr>
                                    <th class="text-center">Issue</th>
                                    <th class="text-center">Delivery</th>
                                    <th class="text-center">Required</th>
                                    <th class="text-center">Balance</th>
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
            <button type="button" class="btn btn-primary btn-sm" data-bs-dismiss="modal" id="rcvnonitem_po_btnuse" onclick="rcvnonitem_po_btnuse_eCK()">Use</button>
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="rcvnonitem_SavedDataMod">
    <div class="modal-dialog modal-lg">
      <div class="modal-content"> 
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Document List</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col mb-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Search</span>
                        <select id="rcvnonitem_srchby" class="form-select" onchange="document.getElementById('rcvnonitem_txtsearch').focus()">
                            <option value="0">Document Number</option>
                            <option value="1">Supplier</option>
                        </select>
                        <input type="text" class="form-control" id="rcvnonitem_txtsearch" onkeypress="rcvnonitem_txtsearch_eKP(event)" maxlength="44" required placeholder="...">                        
                    </div>
                </div>
            </div>            
            <div class="row">
                <div class="col-md-12 mb-1">
                    <div class="input-group input-group-sm mb-1">
                        <div class="input-group-text">
                        <input title="use period" type="checkbox" class="form-check-input" id="rcvnonitem_ck_1" onclick="document.getElementById('rcvnonitem_txtsearch').focus()">
                        </div>
                        <input type="text" class="form-control" id="rcvnonitem_saved_date_1" readonly onchange="document.getElementById('rcvnonitem_txtsearch').focus()">
                        <input type="text" class="form-control" id="rcvnonitem_saved_date_2" readonly onchange="document.getElementById('rcvnonitem_txtsearch').focus()">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col text-end">
                    <span class="badge bg-info" id="rcvnonitem_saved_lblinfo"></span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="rcvnonitem_saved_div">
                        <table id="rcvnonitem_saved_tbl" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer">
                            <thead class="table-light">
                                <tr>
                                    <th class="align-middle">Document Number</th>
                                    <th class="align-middle">Supplier</th>
                                    <th class="text-center">Date</th>
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
    $("#rcvnonitem_divku_1").css('height', $(window).height()   
    -document.getElementById('rcvnonitem_stack0').offsetHeight 
    -document.getElementById('rcvnonitem_stack1').offsetHeight 
    -document.getElementById('rcvnonitem_stack2').offsetHeight
    -100);
    $("#rcvnonitem_txt_rcvdate").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    })
    $("#rcvnonitem_txt_rcvdate").datepicker('update', new Date())

    function rcvnonitem_plus_PO_1_eC(){
        $("#rcvnonitem_POBAL_Mod").modal('show')
    }

    $("#rcvnonitem_POBAL_Mod").on('shown.bs.modal', function(){
        document.getElementById('rcvnonitem_po_txtsearch').focus()
    })
    

    function rcvnonitem_po_txtsearch_eKP(e) {
        if(e.key==='Enter'){
            e.target.readOnly = true
            $.ajax({
                url: "<?=base_url('PO/search_balance')?>",
                data: {search : e.target.value, searchtype: '0'},
                dataType: "json",
                success: function (response) {
                    e.target.readOnly = false
                    const ttlrows = response.data.length
                    let mydes = document.getElementById("rcvnonitem_po_tbl_div")
                    let myfrag = document.createDocumentFragment()
                    let mtabel = document.getElementById("rcvnonitem_po_tbl")
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln)
                    let tabell = myfrag.getElementById("rcvnonitem_po_tbl")
                    let tableku2 = tabell.getElementsByTagName("tbody")[0]
                    let newrow, newcell
                    let myitmttl = 0;
                    tableku2.innerHTML=''
                    for (let i = 0; i<ttlrows; i++){
                        let qtyreq = numeral(response.data[i].PO_QTY).value()
                        let qtybal = qtyreq-numeral(response.data[i].RCVQTY).value()
                        newrow = tableku2.insertRow(-1)
                        newcell = newrow.insertCell(0)                        
                        newcell.innerHTML = response.data[i].PO_NO                        
                        newcell = newrow.insertCell(1)
                        newcell.innerHTML = response.data[i].MSUP_SUPNM 
                        newcell = newrow.insertCell(2)
                        newcell.innerHTML = response.data[i].PO_ISSUDT
                        newcell = newrow.insertCell(3)
                        newcell.innerHTML = response.data[i].PO_REQDT
                        newcell = newrow.insertCell(4)
                        newcell.innerHTML = response.data[i].MITM_ITMD1
                        newcell = newrow.insertCell(5)
                        newcell.classList.add('text-end')
                        newcell.innerHTML = numeral(qtyreq).format('0,0.00')
                        newcell = newrow.insertCell(6)
                        newcell.classList.add('text-end')
                        newcell.innerHTML = numeral(qtybal).format('0,0.00')
                        newcell = newrow.insertCell(7)
                        newcell.innerHTML = response.data[i].PO_UM
                        newcell = newrow.insertCell(8)
                        newcell.classList.add('text-end')
                        newcell.innerHTML = response.data[i].PO_PRICE
                        newcell = newrow.insertCell(9)
                        newcell.classList.add('d-none')
                        newcell.innerHTML = response.data[i].PO_SUPCD
                    }
                    mydes.innerHTML=''
                    mydes.appendChild(myfrag)
                }, error:function(xhr,xopt,xthrow){
                    e.target.readOnly = false
                    alertify.error(xthrow)                 
                }
            })
        }
    }

    function rcvnonitem_po_btnuse_eCK(){
        let mtbl = document.getElementById('rcvnonitem_po_tbl')
        let tableku2 = mtbl.getElementsByTagName("tbody")[0]
        let mtbltr = tableku2.getElementsByTagName('tr')
        let ttlrows = mtbltr.length
        let aPO = []        
        let aItemName = []
        let aqty = []
        let aUM = []
        let aprice = []
        let isexist = false
        
        for(let i=0; i<ttlrows; i++){
            isexist = true
            aPO.push(tableku2.rows[i].cells[0].innerText)            
            aItemName.push(tableku2.rows[i].cells[4].innerText)
            aqty.push(numeral(tableku2.rows[i].cells[6].innerText).value())
            aUM.push(tableku2.rows[i].cells[7].innerText)
            aprice.push(numeral(tableku2.rows[i].cells[8].innerText).value())
        }
        if(isexist) {                        
            let tabell = document.getElementById("rcvnonitem_tbl_1")
            let tableku2 = tabell.getElementsByTagName("tbody")[0]
            let newrow, newcell
            tableku2.innerHTML = ''
            for(let i=0; i<ttlrows; i++){
                newrow = tableku2.insertRow(-1)               
                newcell = newrow.insertCell(0)
                newcell.classList.add('d-none')                                
                
                newcell = newrow.insertCell(1)                
                newcell.classList.add('table-info')                                
                newcell.innerHTML = aPO[i]                

                newcell = newrow.insertCell(2)
                newcell.classList.add('table-info')
                newcell.innerHTML = aItemName[i]
                
                newcell = newrow.insertCell(3)
                newcell.contentEditable = true
                newcell.classList.add('text-end')
                newcell.innerHTML = aqty[i]

                newcell = newrow.insertCell(4)
                newcell.classList.add('table-info')
                newcell.innerHTML = aUM[i]

                newcell = newrow.insertCell(5)
                newcell.classList.add('text-end','table-info')                
                newcell.innerHTML = aprice[i]

                newcell = newrow.insertCell(6)
                newcell.classList.add('text-end')  
                newcell.classList.add('table-info')       
                newcell.innerHTML = '-'               
            }
        }
    }

    function rcvnonitem_btn_new_eC(){
        if(confirm("Are you sure ?")){
            $("#rcvnonitem_txt_rcvdate").datepicker('update', new Date())
            document.getElementById('rcvnonitem_txt_doc').value = ''
            document.getElementById('rcvnonitem_tbl_1').getElementsByTagName('tbody')[0].innerHTML = ''
            document.getElementById('rcvnonitem_txt_doc').readOnly = false
        }
    }

    function rcvnonitem_btn_save_eC(){
        const donum = document.getElementById('rcvnonitem_txt_doc')
        const rcvdate = document.getElementById('rcvnonitem_txt_rcvdate').value
        let mtbl = document.getElementById('rcvnonitem_tbl_1')
        let tableku2 = mtbl.getElementsByTagName("tbody")[0]
        let mtbltr = tableku2.getElementsByTagName('tr')
        let ttlrows = mtbltr.length
        let isready = false        
        if(donum.value.trim().length<3){
            alertify.warning('DO No is required')
            donum.focus()
            return
        }
        let apo = []
        let aitem = []
        let arowid = []
        let aqty = []
        for(let i=0; i<ttlrows; i++){
            let qty = numeral(tableku2.rows[i].cells[3].innerText).value()
            if(qty>0){
                isready = true
                arowid.push(tableku2.rows[i].cells[0].innerText)
                apo.push(tableku2.rows[i].cells[1].innerText)
                aitem.push(tableku2.rows[i].cells[2].innerText)
                aqty.push(qty)
            }
        }
        if(isready){
            if(confirm("Are you sure ?")){
                $.ajax({
                    type: "POST",
                    url: "<?=base_url('RCV/set_nonitem')?>",
                    data: {donum: donum.value, rcvdate: rcvdate, arowid: arowid, apo: apo, aitem: aitem, aqty:aqty},
                    dataType: "json",
                    success: function (response) {
                        if(response.status[0].cd=='1'){
                            alertify.success(response.status[0].msg)
                        } else {
                            alertify.warning(response.status[0].msg)
                        }
                    }, error: function (xhr,ajaxOptions, throwError) {
                        alertify.error(xthrow)
                    }
                })
            }
        } else {
            alertify.message('nothing will be saved')
        }
    }

    function rcvnonitem_btnmod_eC(){
        $("#rcvnonitem_SavedDataMod").modal('show')
    }
    $("#rcvnonitem_SavedDataMod").on('shown.bs.modal', function(){
        document.getElementById('rcvnonitem_txtsearch').focus()
    })

    function rcvnonitem_txtsearch_eKP(e){
        if(e.key==='Enter'){
            const searchby = document.getElementById("rcvnonitem_srchby").value
            e.target.readOnly = true
            $.ajax({
                type: "GET",
                url: "<?=base_url('RCV/search_doni')?>",
                data: {search: e.target.value, searchBY: searchby},
                dataType: "json",
                success: function (response) {
                    e.target.readOnly = false
                    const ttlrows = response.data.length
                    let mydes = document.getElementById("rcvnonitem_saved_div")
                    let myfrag = document.createDocumentFragment()
                    let mtabel = document.getElementById("rcvnonitem_saved_tbl")
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln)
                    let tabell = myfrag.getElementById("rcvnonitem_saved_tbl")
                    let tableku2 = tabell.getElementsByTagName("tbody")[0]
                    let newrow, newcell
                    let myitmttl = 0;
                    tableku2.innerHTML=''
                    for (let i = 0; i<ttlrows; i++){
                        console.log(i)
                        newrow = tableku2.insertRow(-1)
                        newcell = newrow.insertCell(0)
                        newcell.style.cssText = 'cursor:pointer'
                        newcell.onclick = () => {
                            $("#rcvnonitem_SavedDataMod").modal('hide')
                            document.getElementById('rcvnonitem_txt_doc').value=response.data[i].RCVNI_DO
                            document.getElementById('rcvnonitem_txt_doc').readOnly = true
                            rcvnonitem_getdetail(response.data[i].RCVNI_DO)
                        }
                        newcell.innerHTML = response.data[i].RCVNI_DO
                        newcell = newrow.insertCell(1)
                        newcell.innerHTML = response.data[i].MSUP_SUPNM 
                        newcell = newrow.insertCell(2)
                        newcell.innerHTML = response.data[i].RCVDT
                    }
                    mydes.innerHTML=''
                    mydes.appendChild(myfrag)
                }, error: function (xhr,ajaxOptions, throwError) {
                    alertify.error(xthrow)
                    e.target.readOnly = false
                }
            })
        }
    }
    function rcvnonitem_getdetail(pdo){
        $.ajax({            
            url: "<?=base_url('RCV/doni')?>",
            data: {doc: pdo},
            dataType: "json",
            success: function (response) {
                
            }, error: function (xhr,ajaxOptions, throwError) {
                alertify.error(xthrow)                
            }
        })
    }
</script>