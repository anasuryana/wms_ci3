<div style="padding: 10px">
    <div class="container-fluid">        
        <div class="row" id="invo_stack1">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" >Supplier Code</span>
                    <input type="text" class="form-control" id="invo_txt_supnm" readonly >
                    <button class="btn btn-primary" id="invo_btn_find_supplier" onclick="invo_btn_find_supplier_eCK()"><i class="fas fa-search"></i></button>
                </div>
            </div>            
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" >Invoice NO.</span>
                    <input type="text" class="form-control" id="invo_txt_no" readonly >
                    <button class="btn btn-primary" id="invo_btn_find_invoiced_modal" onclick="invo_btn_find_invoiced_modal_eCK()"><i class="fas fa-search"></i></button>
                    <button title="Save" id="invo_btnsave" class="btn btn-outline-primary" onclick="invo_btnsave_eCK()" ><i class="fas fa-save"></i></button>
                </div>
            </div>            
        </div>        
        <div class="row">
            <div class="col-md-6 mb-1">
                <div class="table-responsive" id="invo_divku">
                    <table id="invo_tbl" class="table table-bordered table-sm table-hover caption-top" style="font-size:75%">
                        <caption>Outstanding</caption>
                        <thead class="table-light">
                            <tr>
                                <th class="align-middle text-center">Business</th>
                                <th class="align-middle">DO. NO.</th>                                
                                <th class="align-middle">Receive Date</th>
                                <th class="align-middle text-center">...</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="table-responsive" id="invoiced_divku">
                    <table id="invoiced_tbl" class="table table-bordered table-sm table-hover caption-top" style="font-size:75%">
                        <caption>Invoiced</caption>
                        <thead class="table-light">
                            <tr>
                                <th  class="align-middle text-center">Business</th>
                                <th  class="align-middle">DO. NO.</th>                                
                                <th  class="align-middle d-none">Receive Date</th>
                                <th  class="align-middle">Invoice NO.</th>
                                <th  class="align-middle">...</th>
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
<div class="modal fade" id="invo_SUPPLIER">
    <div class="modal-dialog">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Supplier List</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >Search</span>
                        <input type="text" class="form-control" id="invo_txtsearchSUP" onkeypress="invo_txtsearchSUP_eKP(event)" maxlength="15" required placeholder="...">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col text-end mb-1">
                    <span class="badge bg-info" id="invo_tblsup_lblinfo"></span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="invo_tblsup_div">
                        <table id="invo_tblsup" class="table table-sm table-striped table-bordered table-hover" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th>Code</th>
                                    <th>Name</th>
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

<div class="modal fade" id="invo_SAVED">
    <div class="modal-dialog">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Invoice List</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >Search</span>
                        <input type="text" class="form-control" id="invo_txtsearchINVO" onkeypress="invo_txtsearchINVO_eKP(event)" maxlength="15" required placeholder="...">
                    </div>
                </div>
            </div>           
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="invo_tblINVOSAVED_div">
                        <table id="invo_tblINVOSAVED" class="table table-sm table-striped table-bordered table-hover" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th>Invoice Number</th>                                    
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
    var invo_suppliercode = ''

    function invo_btn_find_invoiced_modal_eCK(){
        $("#invo_SAVED").modal('show')
    }
    $("#invo_SAVED").on('shown.bs.modal', function(){
        document.getElementById('invo_txtsearchINVO').focus()
    })

    function invo_btnsave_eCK(){
        if(invo_suppliercode=='') {
            return 
        }
        const txtinvno = document.getElementById('invo_txt_no')
        if(txtinvno.value.trim().length==0){
            alertify.warning('Invoice Number is required')
            txtinvno.focus()
            return
        }
        const plt_tbl = document.getElementById('invoiced_tbl').getElementsByTagName('tbody')[0]
        const plt_tbl_rows = plt_tbl.getElementsByTagName('tr').length
        if(plt_tbl_rows<=0){
            return
        }
        let bisgrup_l = []        
        let supno_l = []
        for(let i = 0; i<plt_tbl_rows; i++) {            
            bisgrup_l.push(plt_tbl.rows[i].cells[0].innerText)
            supno_l.push(plt_tbl.rows[i].cells[1].innerText)
        }
        if(confirm("Are you sure ?")) {
            $.ajax({
                type: "POST",
                url: "<?=base_url('Invoice/save')?>",
                data: { bisgrup_l: bisgrup_l,supno_l: supno_l, invno: txtinvno.value.trim(), supcd: invo_suppliercode },
                dataType: "JSON",
                success: function (response) {
                    if(response.status[0].cd=='1'){
                        alertify.success(response.status[0].msg)
                    } else {
                        alertify.message(response.status[0].msg)
                    }
                }
            })
        }
    }
    function invo_btn_find_supplier_eCK(){
        $("#invo_SUPPLIER").modal('show')
    }
    $("#invo_SUPPLIER").on('shown.bs.modal', function(){
        document.getElementById('invo_txtsearchSUP').focus()
    })

    function invo_txtsearchSUP_eKP(e){
        if(e.key==='Enter'){
            const txt = document.getElementById('invo_txtsearchSUP')
            txt.readOnly = true
            document.getElementById('invo_tblsup').getElementsByTagName('tbody')[0].innerHTML = ''
            $.ajax({
                type: "GET",
                url: "<?=base_url('MSTSUP/search_union')?>",
                data: {searchKey: txt.value },
                dataType: "JSON",
                success: function (response) {
                    txt.readOnly = false
                    if(response.status[0].cd===1){
                        const ttlrows = response.data.length
                        let mydes = document.getElementById("invo_tblsup_div")
                        let myfrag = document.createDocumentFragment()
                        let mtabel = document.getElementById("invo_tblsup")
                        let cln = mtabel.cloneNode(true);
                        myfrag.appendChild(cln)
                        let tabell = myfrag.getElementById("invo_tblsup")
                        let tableku2 = tabell.getElementsByTagName("tbody")[0]
                        let newrow, newcell, newText
                        let myitmttl = 0;
                        tableku2.innerHTML=''
                        for (let i = 0; i<ttlrows; i++){                            
                            newrow = tableku2.insertRow(-1)
                            newcell = newrow.insertCell(0)
                            newcell.style.cssText = "cursor:pointer"
                            newcell.innerHTML = response.data[i].MSUP_SUPCD
                            newcell.onclick = () => {
                                document.getElementById('invo_txt_no').value=''
                                document.getElementById('invo_txt_no').readOnly = false
                                invo_get_ostdata({supcd: response.data[i].MSUP_SUPCD, bisgrup: ''})
                                $("#invo_SUPPLIER").modal('hide')
                                invo_suppliercode = response.data[i].MSUP_SUPCD
                                document.getElementById('invo_txt_supnm').value = response.data[i].MSUP_SUPNM 
                                document.getElementById('invoiced_tbl').getElementsByTagName('tbody')[0].innerHTML =''
                            }
                            newcell = newrow.insertCell(1)
                            newcell.innerHTML = response.data[i].MSUP_SUPNM 
                        }
                        mydes.innerHTML='';
                        mydes.appendChild(myfrag);
                    } else {
                        alertify.message(response.status[0].msg)
                    }
                },  error:function(xhr,ajaxOptions, throwError) {
                    txt.readOnly = false
                }
            })
        }
    }

    function invo_txtsearchINVO_eKP(e){
        if(e.key=='Enter') {
            const search = document.getElementById('invo_txtsearchINVO').value.trim()
            document.getElementById("invo_tblINVOSAVED").getElementsByTagName('tbody')[0].innerHTML = `<tr><td>Please wait...</td></tr>`
            $.ajax({
                type: "GET",
                url: "<?=base_url('Invoice/get_inc')?>",
                data: {search: search },
                dataType: "JSON",
                success: function (response) {
                    const ttlrows = response.data.length
                    let mydes = document.getElementById("invo_tblINVOSAVED_div")
                    let myfrag = document.createDocumentFragment()
                    let mtabel = document.getElementById("invo_tblINVOSAVED")
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln)
                    let tabell = myfrag.getElementById("invo_tblINVOSAVED")
                    let tableku2 = tabell.getElementsByTagName("tbody")[0]
                    let newrow, newcell, newText
                    let myitmttl = 0;
                    tableku2.innerHTML=''
                    for (let i = 0; i<ttlrows; i++){
                        newrow = tableku2.insertRow(-1)
                        newcell = newrow.insertCell(0)
                        newcell.onclick = () => {
                            $("#invo_SAVED").modal('hide')
                            document.getElementById('invo_txt_no').value = response.data[i].RCV_INVNOACT
                            invo_suppliercode = response.data[i].MSUP_SUPCD;                            
                            document.getElementById('invo_txt_supnm').value = response.data[i].MSUP_SUPNM 
                            invo_get_ostdata({supcd: response.data[i].MSUP_SUPCD, bisgrup: ''})
                            invo_get_pltdata({supcd: response.data[i].MSUP_SUPCD, bisgrup: '', invno: response.data[i].RCV_INVNOACT})
                        }
                        newcell.style.cssText = 'cursor:pointer'
                        newcell.innerHTML = response.data[i].RCV_INVNOACT
                    }
                    mydes.innerHTML=''
                    mydes.appendChild(myfrag)
                }, error:function(xhr,ajaxOptions, throwError) {
                    alert(throwError)
                    document.getElementById("invo_tblINVOSAVED").getElementsByTagName('tbody')[0].innerHTML = `<tr><td>${throwError}</td></tr>`
                }
            })
        }
    }

    function invo_get_pltdata(p) {
        $.ajax({
            type: "GET",
            url: "<?=base_url('RCV/pltinv')?>",
            data: {supcd: p.supcd, bisgrup: p.bisgrup, invno: p.invno},
            dataType: "JSON",
            success: function (response) {
                const ttlrows = response.data.length
                let mydes = document.getElementById("invoiced_divku")
                let myfrag = document.createDocumentFragment()
                let mtabel = document.getElementById("invoiced_tbl")
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln)
                let tabell = myfrag.getElementById("invoiced_tbl")
                let tableku2 = tabell.getElementsByTagName("tbody")[0]
                let newrow, newcell, newText
                let myitmttl = 0;
                tableku2.innerHTML=''
                for (let i = 0; i<ttlrows; i++){                            
                    newrow = tableku2.insertRow(-1)
                    newcell = newrow.insertCell(0)
                    newcell.style.cssText = "cursor:pointer"
                    newcell.innerHTML = response.data[i].RCV_BSGRP
                    newcell = newrow.insertCell(1)
                    newcell.innerHTML = response.data[i].RCV_DONO
                    newcell = newrow.insertCell(2)
                    newcell.classList.add('d-none')
                    newcell.innerHTML = response.data[i].RCV_BCDATE
                    newcell = newrow.insertCell(3)
                    newcell.innerText = p.invno
                    newcell = newrow.insertCell(4)
                    newcell.onclick = ()=>{
                        if(confirm("Are you sure ?")) {
                            $.ajax({
                                type: "POST",
                                url: "<?=base_url('Invoice/cancel')?>",
                                data: {invno: p.invno, dono: response.data[i].RCV_DONO },
                                dataType: "JSON",
                                success: function (response) {
                                    alertify.message(response.status[0].msg)
                                }, error:function(xhr,ajaxOptions, throwError) {
                                    alert(throwError)                                    
                                }
                            })
                            invo_to_ost_table({bisgrup: response.data[i].RCV_BSGRP, donum: response.data[i].RCV_DONO, date:response.data[i].RCV_BCDATE })
                        }
                    }
                    newcell.style.cssText = 'cursor:pointer'
                    newcell.classList.add('text-center')
                    newcell.innerHTML = `<span class="fas fa-hand-point-left" style="font-size: 2em; color: Dodgerblue;"></span>`
                }
                mydes.innerHTML=''
                mydes.appendChild(myfrag)
            }, error:function(xhr,ajaxOptions, throwError) {
                alert(throwError)                
            }
        })
    }

    function invo_get_ostdata(p){
        $.ajax({
            type: "GET",
            url: "<?=base_url('RCV/ostinv')?>",
            data: {supcd: p.supcd, bisgrup: p.bisgrup},
            dataType: "JSON",
            success: function (response) {
                const ttlrows = response.data.length
                let mydes = document.getElementById("invo_divku")
                let myfrag = document.createDocumentFragment()
                let mtabel = document.getElementById("invo_tbl")
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln)
                let tabell = myfrag.getElementById("invo_tbl")
                let tableku2 = tabell.getElementsByTagName("tbody")[0]
                let newrow, newcell, newText
                let myitmttl = 0;
                tableku2.innerHTML=''
                for (let i = 0; i<ttlrows; i++){                            
                    newrow = tableku2.insertRow(-1)
                    newcell = newrow.insertCell(0)
                    newcell.style.cssText = "cursor:pointer"
                    newcell.innerHTML = response.data[i].RCV_BSGRP
                    newcell = newrow.insertCell(1)
                    newcell.innerHTML = response.data[i].RCV_DONO
                    newcell = newrow.insertCell(2)
                    newcell.innerHTML = response.data[i].RCV_BCDATE
                    newcell = newrow.insertCell(3)
                    newcell.onclick = () => {
                        invo_to_plot_table({bisgrup: response.data[i].RCV_BSGRP
                            , donum: response.data[i].RCV_DONO
                            , date: response.data[i].RCV_BCDATE})
                    }
                    newcell.style.cssText = 'cursor:pointer'
                    newcell.classList.add('text-center')
                    newcell.innerHTML = `<span class="fas fa-hand-point-right" style="font-size: 2em; color: Dodgerblue;"></span>`
                }
                mydes.innerHTML=''
                mydes.appendChild(myfrag)
            }
        })
    }

    function invo_to_plot_table(p){
        const ost_tbl = document.getElementById('invo_tbl').getElementsByTagName('tbody')[0]
        const ost_tbl_rows = ost_tbl.getElementsByTagName('tr').length
        for(let i = 0; i<ost_tbl_rows; i++) { 
            const bisgrup = ost_tbl.rows[i].cells[0].innerText
            const donum = ost_tbl.rows[i].cells[1].innerText
            if(p.bisgrup==bisgrup && p.donum==donum){
                document.getElementById('invo_tbl').getElementsByTagName('tbody')[0].getElementsByTagName('tr')[i].remove()
                break;
            }
        }
        const plt_tbl = document.getElementById('invoiced_tbl').getElementsByTagName('tbody')[0]
        let newrow, newcell 
        newrow = plt_tbl.insertRow(-1)       
        newcell = newrow.insertCell(0)
        newcell.innerHTML = p.bisgrup
        newcell = newrow.insertCell(1)
        newcell.innerHTML = p.donum
        newcell = newrow.insertCell(2)
        newcell.classList.add('d-none')
        newcell.innerHTML = p.date
        newcell = newrow.insertCell(3)
        newcell.innerHTML = ''
        newcell = newrow.insertCell(4)
        newcell.classList.add('text-center')
        newcell.style.cssText = 'cursor:pointer'
        newcell.innerHTML = `<span class="fas fa-hand-point-left" style="font-size: 2em; color: Dodgerblue;"></span>`
        newcell.onclick = function(){
            invo_to_ost_table({bisgrup: p.bisgrup, donum: p.donum, date:p.date})
        }
    }


    function invo_to_ost_table(p){
        const plt_tbl = document.getElementById('invoiced_tbl').getElementsByTagName('tbody')[0]
        const plt_tbl_rows = plt_tbl.getElementsByTagName('tr').length
        for(let i = 0; i<plt_tbl_rows; i++) { 
            const bisgrup = plt_tbl.rows[i].cells[0].innerText
            const donum = plt_tbl.rows[i].cells[1].innerText
            if(p.bisgrup==bisgrup && p.donum==donum){
                document.getElementById('invoiced_tbl').getElementsByTagName('tbody')[0].getElementsByTagName('tr')[i].remove()
                break;
            }
        }
        const ost_tbl = document.getElementById('invo_tbl').getElementsByTagName('tbody')[0]
        let newrow, newcell 
        newrow = ost_tbl.insertRow(-1)       
        newcell = newrow.insertCell(0)
        newcell.innerHTML = p.bisgrup
        newcell = newrow.insertCell(1)
        newcell.innerHTML = p.donum
        newcell = newrow.insertCell(2)        
        newcell.innerHTML = p.date
        newcell = newrow.insertCell(3)
        newcell.style.cssText = 'cursor:pointer'
        newcell.classList.add('text-center')
        newcell.innerHTML = `<span class="fas fa-hand-point-right" style="font-size: 2em; color: Dodgerblue;"></span>`
        newcell.onclick = function(){
            invo_to_plot_table({bisgrup: p.bisgrup
                            , donum: p.donum
                            , date: p.date})
        }
    }


</script>