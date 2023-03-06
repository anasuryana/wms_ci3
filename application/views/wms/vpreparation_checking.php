<div style="padding: 5px" >
	<div class="container-fluid">
        <div class="row" id="prepcheck_statck0">
            <div class="col-md-2 mb-1">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" id="prepcheck_btn_new" onclick="prepcheck_btn_new_eC()"><i class="fas fa-file"></i></button>
                    <button class="btn btn-primary" id="prepcheck_btn_save" onclick="prepcheck_btn_save_eC()"><i class="fas fa-save"></i></button>
                </div>
            </div>
        </div>
        <div class="row" id="prepcheck_statck1">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Customer DO</label>
                    <input type="text" class="form-control" id="prepcheck_txt_doc">
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">TX ID</label>
                    <input type="text" class="form-control" id="prepcheck_txt_txid">
                    <button class="btn btn-primary" id="prepcheck_btnmod" onclick="prepcheck_btnmod_eC()"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </div>
        <div class="row" id="prepcheck_statck2">
            <div class="col-md-12 mb-1 text-center">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" id="prepcheck_btnplus" onclick="prepcheck_btnplus_eC()"><i class="fas fa-plus"></i></button>
                    <button class="btn btn-warning" id="prepcheck_btnmins" onclick="prepcheck_minusrow('prepcheck_tbl')"><i class="fas fa-minus"></i></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="prepcheck_divku" onpaste="prepcheck_e_pastecol1(event)">
                    <table id="prepcheck_tbl" class="table table-sm table-striped table-hover table-bordered caption-top" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th class="d-none">idLine</th>
                                <th>No</th>
                                <th>Item Code</th>
                                <th>Item Name</th>
                                <th>QTY</th>
                                <th>Remark</th>
                                <th>Packing</th>
                                <th>Status</th>
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
<div class="modal fade" id="prepcheck_MODITM">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Document List</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">
                        <span class="input-group-text" >Search</span>
                        <select id="prepcheck_srchby" class="form-select">
                            <option value="0">Document Number</option>
                        </select>
                        <input type="text" class="form-control" id="prepcheck_txtsearch" onkeypress="prepcheck_txtsearch_eKP(event)" maxlength="44" required placeholder="...">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="prepcheck_tblitm_div">
                        <table id="prepcheck_tblitm" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer">
                            <thead class="table-light">
                                <tr>
                                    <th>TXID</th>
                                    <th>Customer DO</th>
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
    function prepcheck_btnmod_eC(){
        $("#prepcheck_MODITM").modal('show')
    }

    function prepcheck_reinitnumber(){
        const txid = document.getElementById('prepcheck_txt_txid')
        let mtbl = document.getElementById('prepcheck_tbl')
        let tableku2 = mtbl.getElementsByTagName("tbody")[0]
        let ttlrows = tableku2.getElementsByTagName('tr').length
        for(let i=0; i<ttlrows; i++){
            tableku2.rows[i].cells[1].innerText = (i+1)
        }
    }

    function prepcheck_txtsearch_eKP(e){
        if(e.key=='Enter'){
            const searchval = document.getElementById('prepcheck_txtsearch').value
            document.getElementById('prepcheck_tblitm').getElementsByTagName('tbody')[0].innerHTML = `<tr><td colspan="2" class="text-center">Please wait</td></tr>`
            $.ajax({
                type: "GET",
                url: "<?=base_url('DELV/getck_h')?>",
                data: {inval : searchval},
                dataType: "json",
                success: function (response) {
                    const ttlrows = response.data.length
                    let mydes = document.getElementById("prepcheck_tblitm_div")
                    let myfrag = document.createDocumentFragment()
                    let mtabel = document.getElementById("prepcheck_tblitm")
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln)
                    let tabell = myfrag.getElementById("prepcheck_tblitm")
                    let tableku2 = tabell.getElementsByTagName("tbody")[0]
                    let newrow, newcell, newText
                    let myitmttl = 0;
                    tableku2.innerHTML=''
                    for (let i = 0; i<ttlrows; i++){
                        newrow = tableku2.insertRow(-1)
                        newcell = newrow.insertCell(0)
                        newcell.style.cssText = 'cursor:pointer'
                        newcell.onclick = () => {
                            prepcheck_load_data(response.data[i].DLVCK_TXID)
                            document.getElementById('prepcheck_txt_doc').value = response.data[i].DLVCK_CUSTDO
                            document.getElementById('prepcheck_txt_txid').value = response.data[i].DLVCK_TXID
                            $("#prepcheck_MODITM").modal('hide')
                        }
                        newcell.innerHTML = response.data[i].DLVCK_TXID
                        newcell = newrow.insertCell(1)
                        newcell.innerHTML = response.data[i].DLVCK_CUSTDO
                    }
                    mydes.innerHTML=''
                    mydes.appendChild(myfrag)
                }, error:function(xhr,xopt,xthrow){
                    alertify.error(xthrow)
                    document.getElementById('prepcheck_tblitm').getElementsByTagName('tbody')[0].innerHTML =''
                }
            })
        }
    }

    $("#prepcheck_MODITM").on('shown.bs.modal', function(){
        $("#prepcheck_txtsearch").focus()
    })

    $("#prepcheck_divku").css('height', $(window).height()
    -document.getElementById('prepcheck_statck0').offsetHeight
    -document.getElementById('prepcheck_statck1').offsetHeight
    -document.getElementById('prepcheck_statck2').offsetHeight
    -100);
    var prepcheck_selected_row = 1;
    var prepcheck_selected_col = 1;
    var prepcheck_selected_table = ''

    function prepcheck_btnplus_eC(){
        prepcheck_addrow('prepcheck_tbl')
        let mytbody = document.getElementById('prepcheck_tbl').getElementsByTagName('tbody')[0]
        prepcheck_selected_row = mytbody.rows.length - 1
        prepcheck_selected_col = 1
    }

    function prepcheck_tbl_tbody_tr_eC(e){
        prepcheck_selected_row = e.srcElement.parentElement.rowIndex - 1
        // console.log({prepcheck_selected_row: prepcheck_selected_row})
        const ptablefocus = e.srcElement.parentElement.parentElement.offsetParent.id
        prepcheck_selected_table = ptablefocus
        prepcheck_selected_col = e.srcElement.cellIndex
    }

    function prepcheck_load_data(pdoc){
        $.ajax({
            type: "GET",
            url: "<?=base_url('DELV/getck')?>",
            data: {txid: pdoc},
            dataType: "JSON",
            success: function (response) {
                const ttlrows = response.data.length
                let mydes = document.getElementById("prepcheck_divku")
                let myfrag = document.createDocumentFragment()
                let mtabel = document.getElementById("prepcheck_tbl")
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln)
                let tabell = myfrag.getElementById("prepcheck_tbl")
                let tableku2 = tabell.getElementsByTagName("tbody")[0]
                let newrow, newcell, newText
                tableku2.innerHTML=''

                for (let i = 0; i<ttlrows; i++){
                    newrow = tableku2.insertRow(-1)
                    newrow.onclick = (event) => {prepcheck_tbl_tbody_tr_eC(event)}
                    newcell = newrow.insertCell(0)
                    newcell.classList.add('d-none')
                    newcell.innerHTML = response.data[i].DLVCK_LINE

                    newcell = newrow.insertCell(1)
                    newcell.innerHTML = (i+1)

                    newcell = newrow.insertCell(2)
                    newcell.contentEditable = true
                    newcell.innerHTML = response.data[i].DLVCK_ITMCD

                    newcell = newrow.insertCell(3)

                    newcell = newrow.insertCell(4)
                    newcell.contentEditable = true
                    newcell.classList.add('text-end')
                    newcell.innerHTML = numeral(response.data[i].DLVCK_QTY).format(',')

                    newcell = newrow.insertCell(5)
                    newcell = newrow.insertCell(6)
                    newcell = newrow.insertCell(7)
                    newcell.innerHTML = response.data[i].STATUS
                }
                let nom = ttlrows+1
                const ttlrowsbase = response.rsbase.length
                for (let i = 0; i<ttlrowsbase; i++){
                    if(numeral(response.rsbase[i].DLVQT).value()>0){
                        newrow = tableku2.insertRow(-1)
                        newrow.onclick = (event) => {prepcheck_tbl_tbody_tr_eC(event)}
                        newcell = newrow.insertCell(0)
                        newcell.classList.add('d-none')

                        newcell = newrow.insertCell(1)
                        newcell.innerHTML = nom

                        newcell = newrow.insertCell(2)
                        newcell.contentEditable = true
                        newcell.innerHTML = response.rsbase[i].SER_ITMID

                        newcell = newrow.insertCell(3)

                        newcell = newrow.insertCell(4)
                        newcell.contentEditable = true
                        newcell.classList.add('text-end')
                        newcell.innerHTML = numeral(response.rsbase[i].DLVQT).format(',')

                        newcell = newrow.insertCell(5)
                        newcell = newrow.insertCell(6)
                        newcell = newrow.insertCell(7)
                        newcell.innerHTML = 'NOT OK'
                        nom++
                    }
                }
                mydes.innerHTML=''
                mydes.appendChild(myfrag)
            }, error:function(xhr,xopt,xthrow){
                alertify.error(xthrow)
            }
        })
    }

    function prepcheck_btn_save_eC() {
        const cust_do = document.getElementById('prepcheck_txt_doc')
        const txid = document.getElementById('prepcheck_txt_txid')
        let mtbl = document.getElementById('prepcheck_tbl')
        let tableku2 = mtbl.getElementsByTagName("tbody")[0]
        let mtbltr = tableku2.getElementsByTagName('tr')
        let ttlrows = mtbltr.length
        let aitem = []
        let aqty = []
        let arowid = []

        const cust_do_val = cust_do.value.trim()
        const txid_val = txid.value.trim()

        if(cust_do_val.length==0){
            alertify.warning('Customer DO is required')
            cust_do.focus()
            return
        }
        if(txid_val.length==0){
            alertify.warning('TX ID is required')
            txid.focus()
            return
        }

        for(let i=0; i<ttlrows; i++){
            arowid.push(tableku2.rows[i].cells[0].innerText)
            aitem.push(tableku2.rows[i].cells[2].innerText)
            aqty.push(numeral(tableku2.rows[i].cells[4].innerText).value())
        }

        if(aitem.length==0){
            alertify.warning(`there is no item will be processed`)
            return
        }
        if(confirm("Are you sure ?")){
            const btnsave = document.getElementById('prepcheck_btn_save')
            btnsave.innerHTML  = `<i class="fas fa-spinner fa-spin"></i>`
            btnsave.disabled = true
            $.ajax({
                type: "post",
                url: "<?=base_url('DELV/setck')?>",
                data: {indoc: cust_do_val
                    ,intxid: txid_val
                    ,initem: aitem
                    ,inqty: aqty
                    ,inline: arowid
                },
                dataType: "json",
                success: function (response) {
                    btnsave.innerHTML  = `<i class="fas fa-save"></i>`
                    btnsave.disabled = false
                    if(response.status[0].cd==='1'){
                        alertify.success(response.status[0].msg)
                        prepcheck_load_data(txid_val)
                    }  else {
                        alertify.warning(response.status[0].msg)
                    }
                }, error:function(xhr,ajaxOptions, throwError) {
                    alert(throwError);
                    btnsave.innerHTML  = `<i class="fas fa-save"></i>`
                    btnsave.disabled = false
                }
            })
        }
    }

    function prepcheck_minusrow(ptable){
        if(prepcheck_selected_table!==''){
            if(ptable!=prepcheck_selected_table){
                alertify.message(`wrong button`)
            } else {
                let mytable = document.getElementById(ptable).getElementsByTagName('tbody')[0]
                const mtr = mytable.getElementsByTagName('tr')[prepcheck_selected_row]
                const mylineid = mtr.getElementsByTagName('td')[0].innerText.trim()
                if(mylineid!==''){
                    if(confirm("Are you sure ?")){
                        const docnum = document.getElementById('prepcheck_txt_txid').value
                        $.ajax({
                            type: "post",
                            url: "<?=base_url('DELV/remove_ck')?>",
                            data: {lineId: mylineid, docNum: docnum },
                            dataType: "json",
                            success: function (response) {
                                if (response.status[0].cd==='1') {
                                    alertify.success(response.status[0].msg)
                                    mtr.remove()
                                } else {
                                    alertify.message(response.status[0].msg)
                                }
                            }, error:function(xhr,xopt,xthrow){
                                alertify.error(xthrow)
                            }
                        })
                    }
                } else {
                    mtr.remove()
                }
            }
        } else {
            alertify.message('nothing to be deleted')
        }
        prepcheck_reinitnumber()
    }

    function prepcheck_addrow(ptable){
        let mytbody = document.getElementById(ptable).getElementsByTagName('tbody')[0]
        let newrow , newcell
        newrow = mytbody.insertRow(-1)
        newrow.onclick = (event) => {prepcheck_tbl_tbody_tr_eC(event)}
        newcell = newrow.insertCell(0)
        newcell.classList.add('d-none')
        newcell.innerHTML = ''

        newcell = newrow.insertCell(1)
        newcell.contentEditable = true
        newcell.focus()

        newcell = newrow.insertCell(2)
        newcell.contentEditable = true
        newcell.innerHTML = '0'

        newcell = newrow.insertCell(3)
        newcell.contentEditable = true
        newcell.innerHTML = '0'

        newcell = newrow.insertCell(4)
        newcell.contentEditable = true
        newcell.classList.add('text-end')
        newcell.innerHTML = '0'

        newcell = newrow.insertCell(5)
        newcell.contentEditable = true
        newcell.innerHTML = ''

        newcell = newrow.insertCell(6)
        newcell.contentEditable = true
        newcell.innerHTML = ''
        newcell = newrow.insertCell(7)
        newcell.contentEditable = true
        newcell.innerHTML = ''
        prepcheck_selected_table = ptable
    }

    function prepcheck_e_pastecol1(event){
        let datapas = event.clipboardData.getData('text/html')
        const prepcheck_tbllength = document.getElementById(prepcheck_selected_table).getElementsByTagName('tbody')[0].rows.length
        const columnLength = document.getElementById(prepcheck_selected_table).getElementsByTagName('tbody')[0].rows[0].cells.length
        if(datapas===""){
            datapas = event.clipboardData.getData('text')
            let adatapas = datapas.split('\n')
            let ttlrowspasted = 0
            for(let c=0;c<adatapas.length;c++){
                if(adatapas[c].trim()!=''){
                    ttlrowspasted++
                }
            }
            let table = $(`#${prepcheck_selected_table} tbody`)
            let incr = 0
            if ((prepcheck_tbllength-prepcheck_selected_row)<ttlrowspasted) {
                const needRows = ttlrowspasted - (prepcheck_tbllength-prepcheck_selected_row)
                for (let i = 0;i<needRows;i++) {
                    prepcheck_addrow(prepcheck_selected_table)
                }
            }
            for(let i=0;i<ttlrowspasted;i++){
                const mcol = adatapas[i].split('\t')
                const ttlcol = mcol.length
                for(let k=0;(k<ttlcol) && (k<columnLength);k++){
                    table.find('tr').eq((i+prepcheck_selected_row)).find('td').eq((k+prepcheck_selected_col)).text(mcol[k].trim())
                }
            }
        } else {
            // console.log('sini1')
            let tmpdom = document.createElement('html')
            tmpdom.innerHTML = datapas
            let myhead = tmpdom.getElementsByTagName('head')[0]
            let myscript = myhead.getElementsByTagName('script')[0]
            let mybody = tmpdom.getElementsByTagName('body')[0]
            let mytable = mybody.getElementsByTagName('table')[0]
            let mytbody = mytable.getElementsByTagName('tbody')[0]
            let mytrlength = mytbody.getElementsByTagName('tr').length
            let table = $(`#${prepcheck_selected_table} tbody`)
            let incr = 0
            let startin = 0
            let addition_index = 0
            if(typeof mytbody.getElementsByTagName('tr')[0].getElementsByTagName('td')[1]
                .getElementsByTagName('table')[0] == 'undefined') {

            } else if(typeof mytbody.getElementsByTagName('tr')[0].getElementsByTagName('td')[1]
                .getElementsByTagName('table')[0]
                .getElementsByTagName('tbody')[0]
                .getElementsByTagName('tr')[1]
                .getElementsByTagName('td')[0]
                .getElementsByTagName('table')[0] == 'undefined') {
                    // console.log('it is from  table')
                addition_index = 0
            } else {
                let txid_ = mytbody.getElementsByTagName('tr')[0].getElementsByTagName('td')[1]
                    .getElementsByTagName('table')[0]
                    .getElementsByTagName('tbody')[0]
                    .getElementsByTagName('tr')[1]
                    .getElementsByTagName('td')[0]
                    .getElementsByTagName('table')[0]
                    .getElementsByTagName('tbody')[0]
                    .getElementsByTagName('tr')[0]
                    .children[1]
                    .getElementsByTagName('table')[0]
                    .getElementsByTagName('tbody')[0]
                    .getElementsByTagName('tr')[2]
                    .children[1].innerText
                    document.getElementById('prepcheck_txt_txid').value = txid_
                    addition_index=16
            }
            // console.log({addition_index:addition_index})

            if(typeof(myscript) != 'undefined'){ //check if clipboard from IE
                startin = 3
            }

            if((prepcheck_tbllength-prepcheck_selected_row)<(mytrlength-startin)){
                let needRows = (mytrlength-startin) - (prepcheck_tbllength-prepcheck_selected_row);
                for(let i = 0;i<needRows;i++){
                    prepcheck_addrow(prepcheck_selected_table);
                }
            }

            let b = 0
            let flagend = false
            for(let i=startin;i<(mytrlength);i++){
                if(typeof mytbody.getElementsByTagName('tr')[i+addition_index] == 'undefined'){

                } else {
                    if(typeof mytbody.getElementsByTagName('tr')[i+addition_index].getElementsByTagName('td')[5] == 'undefined') {
                        let ttlcol = mytbody.getElementsByTagName('tr')[i+addition_index].getElementsByTagName('td').length
                        for(let k=0;(k<ttlcol) && (k<columnLength);k++){
                            let dkol = mytbody.getElementsByTagName('tr')[i+addition_index].getElementsByTagName('td')[k].innerText
                            table.find('tr').eq((b+prepcheck_selected_row)).find('td').eq((k+prepcheck_selected_col)).text(dkol.trim())
                        }
                    } else {
                        if(flagend){
                            break;
                        }
                        let ttlcol = mytbody.getElementsByTagName('tr')[i+addition_index].getElementsByTagName('td').length
                        let akol = mytbody.getElementsByTagName('tr')[i+addition_index].getElementsByTagName('td')[5].innerText
                        for(let k=0;(k<ttlcol) && (k<columnLength);k++){
                            let dkol = mytbody.getElementsByTagName('tr')[i+addition_index].getElementsByTagName('td')[k].innerText
                            table.find('tr').eq((b+prepcheck_selected_row)).find('td').eq((k+prepcheck_selected_col)).text(dkol.trim())
                        }

                        if(typeof akol == 'undefined') {
                            break;
                        } else {
                            // if(akol.toUpperCase().includes("RFID")){
                            //     flagend = true
                            // }
                        }
                    }
                }
                b++
            }
            const rmtable = document.getElementById('prepcheck_tbl').getElementsByTagName('tbody')[0]
            const rmtablecount = rmtable.getElementsByTagName('tr').length
            let rmdata = []
            let itemcode = ''
            for(let i=0;i<rmtablecount;i++){
                let isfound = false
                if(rmtable.rows[i].cells[2].innerText!=''){
                    if(rmtable.rows[i].cells[2].innerText.substr(0,1)=='0'){

                    } else {
                        if(rmtable.rows[i].cells[2].innerText.length==9){
                            itemcode=rmtable.rows[i].cells[2].innerText
                        }
                    }
                }
                if(rmtable.rows[i].cells[5].innerText=='TOTAL') {

                } else {
                    for(let c in rmdata) {
                        if(itemcode==rmdata[c].itemcode){
                            let thenum = numeral(rmtable.rows[i].cells[4].innerText).value()
                            if(!isNaN(thenum)){
                                rmdata[c].qty+=thenum
                            }
                            isfound =true;break;
                        }
                    }
                    if(!isfound){
                        let newobku = {itemcode: itemcode, qty: numeral(rmtable.rows[i].cells[4].innerText).value()}
                        rmdata.push(newobku)
                    }
                }
            }

            rmtable.innerHTML = ''
            let nom = 1
            for(let c in rmdata){
                if(rmdata[c].qty>0) {
                    newrow = rmtable.insertRow(-1)
                    newrow.onclick = (event) => {prepcheck_tbl_tbody_tr_eC(event)}
                    newcell = newrow.insertCell(0)
                    newcell.classList.add('d-none')
                    newcell = newrow.insertCell(1)
                    newcell.innerHTML = nom
                    newcell = newrow.insertCell(2)
                    newcell.innerHTML = rmdata[c].itemcode
                    newcell = newrow.insertCell(3)
                    newcell.innerHTML = ''
                    newcell = newrow.insertCell(4)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = rmdata[c].qty
                    newcell = newrow.insertCell(5)
                    newcell = newrow.insertCell(6)
                    newcell = newrow.insertCell(7)
                    nom++
                }
            }
        }
        event.preventDefault()
    }

    function prepcheck_btn_new_eC(){
        document.getElementById('prepcheck_tbl').getElementsByTagName('tbody')[0].innerHTML = ''
        document.getElementById('prepcheck_txt_doc').value = ''
        document.getElementById('prepcheck_txt_txid').value = ''
    }
</script>