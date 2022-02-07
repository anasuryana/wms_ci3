<div style="padding: 5px">
	<div class="container-fluid"> 
        <div class="row">
            <div class="col">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >PSN Number</span>
                    <input type="text" class="form-control" id="psnreff_psnnum" onkeypress="psnreff_psnnum_eKP(event)" autocomplete="off">
                    <button title="New" class="btn btn-primary btn-sm" id="psnreff_btnnew" onclick="psnreff_btnnew_eCK()"><i class="fas fa-file"></i></button>
                </div>
            </div>            
        </div>
        <div class="row">
            <div class="col">
                <div class="table-responsive" id="psnreff_tbl_main_div">
                    <table id="psnreff_tbl_main" class="table table-hover table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th rowspan="2" class="text-center align-middle">Part Code</th>
                                <th rowspan="2" class="text-center align-middle">Part Name</th>
                                <th rowspan="2" class="text-center align-middle">Category</th>
                                <th rowspan="2" class="text-center align-middle">Line</th>
                                <th rowspan="2" class="text-center align-middle">F/R</th>
                                <th rowspan="2" class="text-center align-middle">MCZ</th>
                                <th colspan="3" class="text-center">QTY</th>
                            </tr>
                            <tr>
                                <th class="text-end">Requirement</th>
                                <th class="text-end">Supplied</th>
                                <th class="text-end">Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >Req. Part Code</span>
                    <input type="text" class="form-control" id="psnreff_req_partcode" readonly>
                    <input type="hidden" id="psnreff_req_partname">
                    <input type="hidden" id="psnreff_req_qty">
                    <input type="hidden" id="psnreff_req_category">
                    <input type="hidden" id="psnreff_req_line">
                    <input type="hidden" id="psnreff_req_fedr">
                    <input type="hidden" id="psnreff_req_mcz">
                </div>
            </div>
            <div class="col">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text"  >Act. Part Code</span>
                    <input type="text" class="form-control" id="psnreff_act_partcode" title="3N1" autocomplete="off" onkeypress="psnreff_act_partcode_eKP(event)">
                </div>
            </div>
            <div class="col">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" title="" >Act. Qty</span>
                    <input type="text" class="form-control" id="psnreff_act_qty" title="3N2" onkeypress="psnreff_act_qty_eKP(event)">
                </div>
            </div>
            <div class="col">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >Lot</span>
                    <input type="text" class="form-control" id="psnreff_act_lot" readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="table-responsive" id="psnreff_tbl_res_div">
                    <table id="psnreff_tbl_res" class="table table-hover table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th colspan="13" class="text-center">Resume</th>
                            </tr>
                            <tr>
                                <th colspan="7" class="text-center">Requirement</th>
                                <th colspan="4" class="text-center">Actual</th>
                                <th rowspan="2" class="d-none">line</th>
                                <th rowspan="2" class="text-center">...</th>
                            </tr>
                            <tr>
                                <th>Part Code</th>
                                <th>Part Name</th>
                                <th>Category</th>
                                <th>Line</th>
                                <th>F/R</th>
                                <th>MCZ</th>
                                <th class="text-end">Qty</th>
                                <th>Part Code</th>
                                <th>Part Name</th>
                                <th class="text-end">Qty</th>
                                <th>Lot Number</th>                                
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col text-center">
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text" >Date</span>
                    <input type="text" class="form-control" id="psnreff_act_date" readonly>
                    <button title="Save" type="button" class="btn btn-outline-primary" id="psnreff_btn_save" onclick="psnreff_btn_save_eCK()"><i class="fas fa-save"></i></button>
                </div>                
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="psnreff_hid_idx">

<script>
    var psnreff_g_qty = ''
    var psnreff_g_itemname = ''
    $("#psnreff_act_date").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    })
    function psnreff_act_qty_eKP(e) {
        if(e.key==='Enter') {
            const txt = document.getElementById('psnreff_act_qty').value.toUpperCase()
            if(txt.includes("3N1")) {
                alertify.warning('Please scan 3N2')
                return
            }
            if(!txt.includes("3N2")) {
                alertify.warning('3N2 is required')
                return
            }
            const atxt = txt.split(" ")
            if(psnreff_g_qty!='') {
                document.getElementById('psnreff_act_qty').value = psnreff_g_qty
                document.getElementById('psnreff_act_lot').value = atxt[1]
            } else {
                document.getElementById('psnreff_act_qty').value = atxt[1]
                document.getElementById('psnreff_act_lot').value = atxt[2]
            }
            const r_part = document.getElementById('psnreff_req_partcode')
            const r_partname = document.getElementById('psnreff_req_partname')
            const r_cat = document.getElementById('psnreff_req_category')
            const r_line = document.getElementById('psnreff_req_line')
            const r_fedr = document.getElementById('psnreff_req_fedr')
            const r_mcz = document.getElementById('psnreff_req_mcz')
            const r_qty = document.getElementById('psnreff_req_qty')
            const s_part = document.getElementById('psnreff_act_partcode')
            const s_qty = document.getElementById('psnreff_act_qty')
            const s_lot = document.getElementById('psnreff_act_lot')
            psnreff_add_toresume({r_part: r_part.value, r_partname: r_partname.value, r_qty: r_qty.value
                , r_cat: r_cat.value, r_line: r_line.value, r_fedr: r_fedr.value, r_mcz: r_mcz.value
            , s_part: s_part.value, s_partname: psnreff_g_itemname,s_qty : s_qty.value, s_lot: s_lot.value,s_line : '' })
            s_part.value = ''
            s_part.readOnly = false
            s_part.focus()
            psnreff_g_itemname = ''
            s_qty.value = ''
            s_lot.value = ''
        }
    }

    function psnreff_add_toresume(pdata) {
        let tabell = document.getElementById("psnreff_tbl_res")
        let tableku2 = tabell.getElementsByTagName("tbody")[0]
        let newrow, newcell, newText
        newrow = tableku2.insertRow(-1)
        newcell = newrow.insertCell(0)
        newcell.innerHTML = pdata.r_part
        newcell = newrow.insertCell(1)
        newcell.innerHTML = pdata.r_partname
        newcell = newrow.insertCell(2)
        newcell.innerHTML = pdata.r_cat
        newcell = newrow.insertCell(3)
        newcell.innerHTML = pdata.r_line
        newcell = newrow.insertCell(4)
        newcell.innerHTML = pdata.r_fedr
        newcell = newrow.insertCell(5)
        newcell.innerHTML = pdata.r_mcz
        newcell = newrow.insertCell(6)
        newcell.classList.add('text-end')
        newcell.innerHTML = numeral(pdata.r_qty).format(',')
        newcell = newrow.insertCell(7)
        newcell.innerHTML = pdata.s_part
        newcell = newrow.insertCell(8)
        newcell.innerHTML = pdata.s_partname
        newcell = newrow.insertCell(9)
        newcell.classList.add('text-end')
        newcell.innerHTML = numeral(pdata.s_qty).format(',')
        newcell = newrow.insertCell(10)
        newcell.innerHTML = pdata.s_lot
        newcell = newrow.insertCell(11)
        newcell.classList.add('d-none')
        newcell.innerHTML = pdata.s_line
        newcell = newrow.insertCell(12)
        newcell.onclick = (event) => {
            if(event.target.nodeName==='SPAN') {                
                const rowI = event.target.parentElement.parentElement.rowIndex-3
                if(confirm("Are you sure ?")) {
                    const psn_num = document.getElementById('psnreff_psnnum').value
                    let mytable = document.getElementById('psnreff_tbl_res').getElementsByTagName('tbody')[0]
                    const idline = mytable.getElementsByTagName('tr')[rowI].cells[11].innerHTML
                    mytable.getElementsByTagName('tr')[rowI].remove()
                    if(idline.trim().length) {
                        $.ajax({
                            type: "POST",
                            url: "<?=base_url('SPL/remove_reference')?>",
                            data: {psn:psn_num, idline: idline },
                            dataType: "json",
                            success: function (response) {
                                if(response.status[0].cd==1) {
                                    alertify.success(response.status[0].msg)
                                } else {
                                    alertify.message(response.status[0].msg)
                                }
                            }, error: function(xhr, xopt, xthrow){
                                alertify.error(xthrow)                                
                            }
                        })
                    }
                }
            }
        }
        newcell.classList.add('text-center')
        newcell.style.cssText = 'cursor:pointer'
        newcell.innerHTML = "<span class='fas fa-trash text-warning'></span>"
    }
    function psnreff_act_partcode_eKP(e) {
        if(e.key==='Enter') {
            const reqpartcode = document.getElementById('psnreff_req_partcode').value.trim()
            if(reqpartcode.length===0) {
                alertify.warning('Req. Part Code is required')
                document.getElementById('psnreff_req_partcode').focus()
                return
            }
            const txt = document.getElementById('psnreff_act_partcode').value.toUpperCase()
            if(txt.includes("3N2")) {
                alertify.warning('Please scan item code')
                return
            }
            let itemcode = ''
            if(txt.includes(" ")) { // handle C3 with qty inside 3N1
                const arr3n1 = txt.split(" ")
                psnreff_g_qty = arr3n1[1]
                itemcode = arr3n1[0].toUpperCase().includes("3N1") ? arr3n1[0].substr(3,arr3n1[0].length-3) : arr3n1[0]
            } else {
                itemcode = txt.includes("3N1") ? txt.substr(3,txt.length-3) : txt
                psnreff_g_qty =''
            }
            
            document.getElementById('psnreff_act_partcode').value = itemcode
            
            $.ajax({                
                url: "<?=base_url('MSTITM/getbyid')?>",
                data: {cid: itemcode},
                dataType: "json",
                success: function (response) {
                    if(response.data.length>0) {
                        document.getElementById('psnreff_act_partcode').readOnly = true
                        document.getElementById('psnreff_act_qty').focus()
                        psnreff_g_itemname = response.data[0].MITM_SPTNO
                    }
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow)                    
                }
            })
        }
    }
    function psnreff_btnnew_eCK() {
        const txtpsn = document.getElementById('psnreff_psnnum')
        txtpsn.readOnly = false
        txtpsn.value = ''
        txtpsn.focus()
        document.getElementById('psnreff_tbl_main').getElementsByTagName('tbody')[0].innerHTML = ""
        document.getElementById('psnreff_tbl_res').getElementsByTagName('tbody')[0].innerHTML = ""
        document.getElementById('psnreff_req_partcode').value = ''
        psnreff_g_qty = ''
    }

    function psnreff_psnnum_eKP(e) {
        if(e.key==='Enter') {
            const psnnum = document.getElementById('psnreff_psnnum').value.trim().toUpperCase()
            if(psnnum.includes('SP') || psnnum.includes('PR') && psnnum.length>3) {
                document.getElementById('psnreff_tbl_main').getElementsByTagName('tbody')[0].innerHTML = "<tr><td colspan='3' class='text-center'>Please wait</td></tr>"
                document.getElementById('psnreff_psnnum').readOnly = true
                $.ajax({
                    type: "GET",
                    url: "<?=base_url('SPL/outstanding')?>",
                    data: {psnnum: psnnum},
                    dataType: "json",
                    success: function (response) {
                        const ttlrows = response.data.length
                        if(!ttlrows) {
                            document.getElementById('psnreff_psnnum').readOnly = false
                        }
                        let mydes = document.getElementById("psnreff_tbl_main_div")
                        let myfrag = document.createDocumentFragment()
                        let mtabel = document.getElementById("psnreff_tbl_main")
                        let cln = mtabel.cloneNode(true)
                        myfrag.appendChild(cln)
                        let tabell = myfrag.getElementById("psnreff_tbl_main")
                        let tableku2 = tabell.getElementsByTagName("tbody")[0]
                        let newrow, newcell, newText
                        let balance = 0                        
                        tableku2.innerHTML=''
                        for (let i = 0; i<ttlrows; i++){
                            const ttlscn = numeral(response.data[i].TTLREFF).value()+numeral(response.data[i].TTLSCN).value()
                            balance = numeral(response.data[i].TTLREQ).value()-numeral(response.data[i].TTLSCN).value()-numeral(response.data[i].TTLREFF).value()
                            newrow = tableku2.insertRow(-1)
                            newcell = newrow.insertCell(0)
                            newcell.innerHTML = response.data[i].SPL_ITMCD
                            newcell.title = 'Part Code'
                            newcell.onclick = function() {
                                document.getElementById('psnreff_req_partcode').value = response.data[i].SPL_ITMCD
                                document.getElementById('psnreff_req_partname').value = response.data[i].MITM_SPTNO
                                document.getElementById('psnreff_req_category').value = response.data[i].SPL_CAT
                                document.getElementById('psnreff_req_line').value = response.data[i].SPL_LINE
                                document.getElementById('psnreff_req_fedr').value = response.data[i].SPL_FEDR
                                document.getElementById('psnreff_req_mcz').value = response.data[i].SPL_ORDERNO
                                document.getElementById('psnreff_req_qty').value = response.data[i].TTLREQ
                                let currentidx = document.getElementById('psnreff_hid_idx')
                                if(currentidx.value!=''){
                                    tableku2.rows[currentidx.value].classList.remove('table-info')
                                }
                                tableku2.rows[i].classList.add('table-info')
                                currentidx.value = i
                                const txtspartcode = document.getElementById('psnreff_act_partcode')
                                document.getElementById('psnreff_act_qty').value = ''
                                document.getElementById('psnreff_act_lot').value = ''
                                txtspartcode.focus()
                                txtspartcode.value =''
                                txtspartcode.readOnly = false
                            }
                            newcell = newrow.insertCell(1)
                            newcell.innerHTML = response.data[i].MITM_SPTNO
                            newcell.title = 'Part Name'
                            newcell = newrow.insertCell(2)
                            newcell.innerHTML = response.data[i].SPL_CAT
                            newcell.title = 'Category'
                            newcell = newrow.insertCell(3)
                            newcell.innerHTML = response.data[i].SPL_LINE
                            newcell.title = 'Line'
                            newcell = newrow.insertCell(4)                            
                            newcell.innerHTML = response.data[i].SPL_FEDR
                            newcell.title = 'F/R'
                            newcell = newrow.insertCell(5)
                            newcell.innerHTML = response.data[i].SPL_ORDERNO
                            newcell.title = 'MCZ'
                            newcell = newrow.insertCell(6)
                            newcell.classList.add('text-end')
                            newcell.innerHTML = numeral(response.data[i].TTLREQ).format(',')
                            newcell.title = 'Requirement'
                            newcell = newrow.insertCell(7)
                            newcell.innerHTML = numeral(ttlscn).format(',')
                            newcell.classList.add('text-end')
                            newcell.title = 'Supplied'
                            newcell = newrow.insertCell(8)
                            newcell.innerHTML = numeral(balance).format(',')
                            newcell.classList.add('text-end')
                            newcell.title = 'Balance'
                        }
                        mydes.innerHTML=''
                        mydes.appendChild(myfrag)
                    }, error: function(xhr, xopt, xthrow){
                        alertify.error(xthrow)
                        document.getElementById('psnreff_tbl_main').getElementsByTagName('tbody')[0].innerHTML = "<tr><td colspan='3' class='text-center'></td></tr>"
                    }
                })
                $.ajax({
                    type: "GET",
                    url: "<?=base_url('SPL/load_reference')?>",
                    data: {psnnum: psnnum},
                    dataType: "json",
                    success: function (response) {
                        const ttlrows = response.data.length
                        document.getElementById('psnreff_tbl_res').getElementsByTagName('tbody')[0].innerHTML = ''
                        for(let i=0;i<ttlrows;i++) {
                            psnreff_add_toresume({r_part: response.data[i].SPLREFF_REQ_PART
                                , r_partname: response.data[i].ADESC
                                , r_qty: response.data[i].SPLREFF_REQ_QTY
                                , r_cat: response.data[i].SPLREFF_ITMCAT
                                , r_line: response.data[i].SPLREFF_LINEPRD
                                , r_fedr: response.data[i].SPLREFF_FEDR
                                , r_mcz: response.data[i].SPLREFF_MCZ
                                , s_part: response.data[i].SPLREFF_ACT_PART
                                , s_partname: response.data[i].BDESC
                                , s_qty : response.data[i].SPLREFF_ACT_QTY
                                , s_lot: response.data[i].SPLREFF_ACT_LOTNUM
                                ,s_line : response.data[i].SPLREFF_LINE })
                        }
                    }, error: function(xhr, xopt, xthrow){
                        alertify.error(xthrow)
                        document.getElementById('psnreff_tbl_main').getElementsByTagName('tbody')[0].innerHTML = "<tr><td colspan='3' class='text-center'></td></tr>"
                    }
                }) 
            } else {
                document.getElementById('psnreff_psnnum').focus()
                alertify.message('PSN Number is required')
            }
        }
    }

    function psnreff_btn_save_eCK() {
        const rmtable = document.getElementById('psnreff_tbl_res').getElementsByTagName('tbody')[0]
        const rmtablecount = rmtable.getElementsByTagName('tr').length
        if(rmtablecount) {
            const psnnum = document.getElementById('psnreff_psnnum').value
            const confirmdate = document.getElementById('psnreff_act_date').value            
            let a_r_part = []
            let a_r_category = []
            let a_r_lineprd = []
            let a_r_fedr = []
            let a_r_mcz = []
            let a_r_qty = []
            let a_r_line = []
            let a_s_part = []
            let a_s_qty = []
            let a_s_lot = []
            if(confirmdate.length<10) {
                document.getElementById('psnreff_act_date').focus()
                alertify.warning('Date is required')
                return 
            }
            for(let i = 0; i<rmtablecount; i++) { 
                a_r_part.push(rmtable.rows[i].cells[0].innerText)
                a_r_category.push(rmtable.rows[i].cells[2].innerText)
                a_r_lineprd.push(rmtable.rows[i].cells[3].innerText)
                a_r_fedr.push(rmtable.rows[i].cells[4].innerText)
                a_r_mcz.push(rmtable.rows[i].cells[5].innerText)
                a_r_qty.push(numeral(rmtable.rows[i].cells[6].innerText).value())

                a_s_part.push(rmtable.rows[i].cells[7].innerText)
                a_s_qty.push(numeral(rmtable.rows[i].cells[9].innerText).value())
                a_s_lot.push(rmtable.rows[i].cells[10].innerText)
                a_r_line.push(rmtable.rows[i].cells[11].innerText)
            }            
            if(confirm("Are you sure ?")) {
                $.ajax({
                    type: "POST",
                    url: "<?=base_url('SPL/savereference')?>",
                    data: {psnnum: psnnum, confirmdate: confirmdate, a_r_part: a_r_part, a_r_qty: a_r_qty, a_r_line: a_r_line
                        ,a_r_category: a_r_category, a_r_lineprd: a_r_lineprd, a_r_fedr: a_r_fedr, a_r_mcz: a_r_mcz
                    ,a_s_part: a_s_part, a_s_qty: a_s_qty, a_s_lot: a_s_lot},
                    dataType: "JSON", 
                    success: function (response) {
                        if(response.status[0].cd===1) {
                            alertify.success(response.status[0].msg)
                            rmtable.innerHTML = ''
                        } else {
                            alertify.message(response.status[0].msg)
                        }
                    }
                })
            }
        } else {
            alertify.message('nothing to be saved')
        }
    }
</script>