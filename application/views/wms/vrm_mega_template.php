<div style="padding: 10px" >
	<div class="container-fluid">
        <div class="row">
            <div class="col-md-2 mb-1">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" id="gen_template_btn_new" onclick="gen_template_btn_new_eC()"><i class="fas fa-file"></i></button>
                    <button class="btn btn-success" id="gen_template_btn_save" onclick="gen_template_btn_save_eC()"><i class="fas fa-file-excel"></i></button>
                </div>
            </div>
            <div class="col-md-10 mb-1">
                <span class="badge bg-info" id="gen_template_lblexport"></span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Business Group</span>                                        				
                    <select class="form-select" id="gen_template_businessgroup" onchange="gen_template_businessgroup_e_onchange()" required> 
                    <option value="-">-</option>
                    <?php
                    foreach($lbg as $r){
                        ?>
                        <option value="<?=trim($r->MBSG_BSGRP)?>"><?=$r->MBSG_DESC?></option>
                        <?php
                    }
                    ?>
                    </select>
                </div>
            </div>
            <div class="col-md-5 mb-1">
                <div class="input-group input-group-sm">                                        
                    <span class="input-group-text">Supplier</span>
                    <input type="text" class="form-control" id="gen_template_custname" required readonly>
                    <button class="btn btn-outline-primary" id="gen_template_btnfindmodcust" onclick="gen_template_btnfindmodcust_eC()"><i class="fas fa-search"></i></button>
                </div>
            </div>
            <div class="col-md-2 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Currency</span>
                    <input type="text" readonly class="form-control" id="gen_template_curr">
                </div>
            </div>
        </div> 
        <div class="row">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Invoice No</label>
                    <input type="text" class="form-control" id="gen_template_txt_doc">                    
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">Invoice Date</label>
                    <input type="text" class="form-control" id="gen_template_txt_date" readonly>
                </div>
            </div>           
        </div>
        <div class="row">
            <div class="col-md-6 mb-1">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" id="gen_template_btnplus" onclick="gen_template_btnplus_eC()"><i class="fas fa-plus"></i></button>
                    <button class="btn btn-warning" id="gen_template_btnmins" onclick="gen_template_minusrow('gen_template_tbl')"><i class="fas fa-minus"></i></button>
                </div>
            </div>
            <div class="col-md-6 mb-1 text-end">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" id="gen_template_btn_generate" onclick="gen_template_btn_generate_eC()">Generate</button>                   
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="gen_template_divku" onpaste="gen_template_e_pastecol1(event)">
                    <table id="gen_template_tbl" class="table table-sm table-striped table-hover table-bordered caption-top" style="width:100%">                        
                        <thead class="table-light">
                            <tr>
                                <th colspan="5" class="text-end">Total</th>
                                <th class="text-end"><span id="gen_template_tbl_totalqty"></span></th>
                                <th colspan="5" class="text-end"></th>
                            </tr>
                            <tr>
                                <th>PO No</th>
                                <th>Invoice Date</th>
                                <th>Part No</th>
                                <th>Part Name</th>
                                <th class="text-end">SPQ</th>
                                <th class="text-end">Delivery QTY</th>
                                <th class="text-end">Unit Price</th>
                                <th>Amount</th>
                                <th>Currency</th>
                                <th>Invoice No</th>
                                <th class="d-none">MasterPrice</th>
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
<div class="modal fade" id="gen_template_MODCUS">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">      
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Customer List</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Search</span>
                        <input type="text" class="form-control" id="gen_template_txtsearchcus" maxlength="15" required placeholder="..." onkeypress="gen_template_txtsearchcus_eKP(event)">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col mb-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Search by</span>                        
                        <select id="gen_template_srch_cust_by" class="form-select">
                            <option value="nm">Name</option>
                            <option value="cd">Code</option>
                            <option value="ad">Address</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="gen_template_tblcus_div">
                        <table id="gen_template_tblcus" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer">
                            <thead class="table-light">
                                <tr>
                                    <th>Customer Code</th>
                                    <th>Currency</th>
                                    <th>Name</th>                                    
                                    <th>Address</th>
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
    var gen_template_selected_row = 1;
    var gen_template_selected_col = 1;
    var gen_template_selected_table = ''
    var gen_template_pub_cuscd = ''

    function gen_template_btn_new_eC() {
        document.getElementById('gen_template_tbl').getElementsByTagName('tbody')[0].innerHTML = ''
        document.getElementById('gen_template_lblexport').innerHTML = ""
    }

    function gen_template_btn_save_eC() {
        
        let a_PO = []
        let a_ItemCD = []
        let a_ItemNM = []
        let a_SPQ = []
        let a_QTY = []
        let a_Price = []
        let a_PriceM = []
        const bisgrup = document.getElementById('gen_template_businessgroup')
        const supcd = document.getElementById('gen_template_custname')
        const invoicenumber = document.getElementById('gen_template_txt_doc')
        const invoicedate = document.getElementById('gen_template_txt_date').value
        const currency = document.getElementById('gen_template_curr').value

        const rmtable = document.getElementById('gen_template_tbl').getElementsByTagName('tbody')[0]
        const rmtablecount = rmtable.getElementsByTagName('tr').length
        for(let i=0; i<rmtablecount; i++) {
            let tmpPO = rmtable.rows[i].cells[0].innerText
            let tmpitemQty = rmtable.rows[i].cells[5].innerText.replace(/\n+/g, '')
            let tmpitemPrice = rmtable.rows[i].cells[6].innerText.replace(/\n+/g, '')
            let tmpitemPriceM = rmtable.rows[i].cells[10].innerText.replace(/\n+/g, '')
            tmpitemQty = tmpitemQty.replace(/,/g, '')
            tmpitemPrice = tmpitemPrice.replace(/,/g, '')
            tmpitemPriceM = tmpitemPriceM.replace(/,/g, '')

            if(isNaN(tmpitemQty)) {
                alertify.warning(`qty should be numerical value on line ${i}`)
                return
            }
            if(tmpitemQty.length===0){
                alertify.warning('qty could not be empty')
                rmtable.rows[i].cells[5].focus()
                return
            }

            a_PO.push(tmpPO)
            a_ItemCD.push(rmtable.rows[i].cells[2].innerText)
            a_ItemNM.push(rmtable.rows[i].cells[3].innerText)
            a_SPQ.push(rmtable.rows[i].cells[4].innerText.replace(',', ''))
            a_QTY.push(tmpitemQty)
            a_Price.push(tmpitemPrice)
            a_PriceM.push(tmpitemPriceM)
        }
        if(a_PO.length>0) {
            document.getElementById('gen_template_lblexport').innerHTML = "Please wait"
            $.ajax({
                type: "POST",
                url: "<?=base_url('RCV/savesimulatePO')?>",
                data: {invoiceno: invoicenumber.value, invoicedate : invoicedate, currency: currency
                    , ina_po: a_PO
                    , ina_itemcd: a_ItemCD
                    , ina_itemnm: a_ItemNM
                    , ina_spq: a_SPQ
                    , ina_qty: a_QTY
                    , ina_price: a_Price
                    , ina_pricem: a_PriceM
                },
                xhr: function () {
                    const xhr = new XMLHttpRequest()
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState == 2) {
                            if (xhr.status == 200) {
                                xhr.responseType = "blob";
                            } else {
                                xhr.responseType = "text";
                            }
                        }
                    }
                    return xhr
                },
                success: function (response) {                    
                    //Convert the Byte Data to BLOB object.
                    const blob = new Blob([response], { type: "application/vnd.ms-excel" })
                    saveAs(blob, invoicenumber.value+".xlsx")
                    document.getElementById('gen_template_lblexport').innerHTML = ""
                }
            });
        }
    }

    function gem_template_recalculate() {
        const rmtable = document.getElementById('gen_template_tbl').getElementsByTagName('tbody')[0]
        const rmtablecount = rmtable.getElementsByTagName('tr').length
        let ttlqty = 0
        for(let i = 0; i<rmtablecount; i++) { 
            const itemcd = rmtable.rows[i].cells[2].innerText.trim()
            let tmpitemQty = rmtable.rows[i].cells[5].innerText.replace(/\n+/g, '')
            let tmpitemPrice = rmtable.rows[i].cells[6].innerText.replace(/\n+/g, '')
            if(itemcd.length > 0) {                
                tmpitemQty = tmpitemQty.replace(',', '')
                tmpitemPrice = tmpitemPrice.replace(',', '')
                if(isNaN(tmpitemQty)) {
                    return
                }
                if(tmpitemQty.length===0){
                    return
                }
                ttlqty += numeral(tmpitemQty).value()
            }
        }
        document.getElementById('gen_template_tbl_totalqty').innerText = numeral(ttlqty).format(',')
    }

    function gen_template_btn_generate_eC() {
        const bisgrup = document.getElementById('gen_template_businessgroup')
        const supcd = document.getElementById('gen_template_custname')
        const invoicenumber = document.getElementById('gen_template_txt_doc')
        const invoicedate = document.getElementById('gen_template_txt_date').value
        const currency = document.getElementById('gen_template_curr').value
        if(bisgrup.value === '-') {
            alertify.message('Please select a business')
            bisgrup.focus()
            return
        }
        if(supcd.value === '-') {
            alertify.message('Please select a supplier')
            supcd.focus()
            return
        }

        let arm_itemCd = []
        let arm_itemQty = []
        let arm_itemPrice = []
        const rmtable = document.getElementById('gen_template_tbl').getElementsByTagName('tbody')[0]
        const rmtablecount = rmtable.getElementsByTagName('tr').length
        for(let i = 0; i<rmtablecount; i++) { 
            const itemcd = rmtable.rows[i].cells[2].innerText.trim()
            let tmpitemQty = rmtable.rows[i].cells[5].innerText.replace(/\n+/g, '')
            let tmpitemPrice = rmtable.rows[i].cells[6].innerText.replace(/\n+/g, '')
            if(itemcd.length > 0) {                
                tmpitemQty = tmpitemQty.replace(',', '')
                tmpitemPrice = tmpitemPrice.replace(',', '')
                if(isNaN(tmpitemQty)) {
                    alertify.warning('qty should be numerical value')
                    return
                }
                if(tmpitemQty.length===0){
                    alertify.warning('qty could not be empty')
                    rmtable.rows[i].cells[5].focus()
                    return
                }
                arm_itemCd.push(itemcd)
                arm_itemQty.push(tmpitemQty)
                arm_itemPrice.push(tmpitemPrice)
            } else {
                alertify.warning('Part no must not be empty')
                return
            }
        }
        if(arm_itemCd.length==0) {
            alertify.message('there is no data will be processsed')
            return
        }
        document.getElementById('gen_template_lblexport').innerHTML = "Please wait.."
        $.ajax({
            type: "POST",
            url: "<?=base_url('RCV/simulatePO')?>",
            data: {bisgrup: bisgrup.value, supcd: gen_template_pub_cuscd
            , item: arm_itemCd,itemqty:arm_itemQty, itemprice:arm_itemPrice  },
            dataType: "json",
            success: function (response) {
                const ttlrows = response.data.length
                let mydes = document.getElementById("gen_template_divku")
                let myfrag = document.createDocumentFragment()
                let mtabel = document.getElementById("gen_template_tbl")
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln)
                let tabell = myfrag.getElementById("gen_template_tbl")
                let tableku2 = tabell.getElementsByTagName("tbody")[0]
                let newrow, newcell, newText
                let myitmttl = 0;
                tableku2.innerHTML=''
                for (let i = 0; i<ttlrows; i++){ 
                    myitmttl += numeral(response.data[i].QTY).value()
                    newrow = tableku2.insertRow(-1)
                    newrow.onclick = (event) => {gen_template_tbl_tbody_tr_eC(event)}
                    newcell = newrow.insertCell(0)                    
                    newcell.innerHTML = response.data[i].PO

                    newcell = newrow.insertCell(1)
                    newcell.innerHTML = invoicedate

                    newcell = newrow.insertCell(2)
                    newcell.innerHTML = response.data[i].ITEMCD

                    newcell = newrow.insertCell(3)
                    newcell.innerHTML = response.data[i].ITEMNM

                    newcell = newrow.insertCell(4)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = 0

                    newcell = newrow.insertCell(5)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = numeral(response.data[i].QTY).format(',')
                    
                    newcell = newrow.insertCell(6)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = response.data[i].PRICE

                    newcell = newrow.insertCell(7)
                    newcell.classList.add('text-end')
                    newcell.innerHTML = numeral(response.data[i].PRICE*response.data[i].QTY).format('0,0.00')

                    newcell = newrow.insertCell(8)
                    newcell.innerHTML = currency

                    newcell = newrow.insertCell(9)
                    newcell.innerHTML = invoicenumber.value

                    newcell = newrow.insertCell(10)
                    newcell.classList.add('d-none')
                    newcell.innerHTML = response.data[i].PRICEMEGA

                }
                mydes.innerHTML=''
                mydes.appendChild(myfrag)
                document.getElementById('gen_template_lblexport').innerHTML = ""
                document.getElementById('gen_template_tbl_totalqty').innerText = numeral(myitmttl).format(',')
            }, error:function(xhr,xopt,xthrow){
                alertify.error(xthrow)
                document.getElementById('gen_template_lblexport').innerHTML = ""
            }
        });
    }

    function gen_template_btnplus_eC(){
        gen_template_addrow('gen_template_tbl')
        let mytbody = document.getElementById('gen_template_tbl').getElementsByTagName('tbody')[0]
        gen_template_selected_row = mytbody.rows.length - 1
        gen_template_selected_col = 1
    }
    $("#gen_template_txt_date").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    })
    $("#gen_template_txt_date").datepicker('update', new Date());
    function gen_template_txtsearchcus_eKP(e) {
        if( e.key === 'Enter') {
            gen_template_load_customer()
        }
    }

    function gen_template_e_pastecol1(event){
        let datapas = event.clipboardData.getData('text/html')
        const gen_template_tbllength = document.getElementById(gen_template_selected_table).getElementsByTagName('tbody')[0].rows.length
        const columnLength = document.getElementById(gen_template_selected_table).getElementsByTagName('tbody')[0].rows[0].cells.length        
        if(datapas===""){
            datapas = event.clipboardData.getData('text')
            let adatapas = datapas.split('\n')
            let ttlrowspasted = 0
            for(let c=0;c<adatapas.length;c++){
                if(adatapas[c].trim()!=''){
                    ttlrowspasted++
                }
            }
            let table = $(`#${gen_template_selected_table} tbody`)
            let incr = 0
            if ((gen_template_tbllength-gen_template_selected_row)<ttlrowspasted) {       
                const needRows = ttlrowspasted - (gen_template_tbllength-gen_template_selected_row)
                for (let i = 0;i<needRows;i++) {
                    gen_template_addrow(gen_template_selected_table)
                }
            }            
            for(let i=0;i<ttlrowspasted;i++){                
                const mcol = adatapas[i].split('\t')
                const ttlcol = mcol.length                
                for(let k=0;(k<ttlcol) && (k<columnLength);k++){             
                    table.find('tr').eq((i+gen_template_selected_row)).find('td').eq((k+gen_template_selected_col)).text(mcol[k].trim())
                }                
            }                            
        } else {            
            let tmpdom = document.createElement('html')
            tmpdom.innerHTML = datapas
            let myhead = tmpdom.getElementsByTagName('head')[0]
            let myscript = myhead.getElementsByTagName('script')[0]
            let mybody = tmpdom.getElementsByTagName('body')[0]
            let mytable = mybody.getElementsByTagName('table')[0]
            let mytbody = mytable.getElementsByTagName('tbody')[0]
            let mytrlength = mytbody.getElementsByTagName('tr').length
            let table = $(`#${gen_template_selected_table} tbody`)
            let incr = 0
            let startin = 0
            
            if(typeof(myscript) != 'undefined'){ //check if clipboard from IE
                startin = 3
            }
            if((gen_template_tbllength-gen_template_selected_row)<(mytrlength-startin)){
                let needRows = (mytrlength-startin) - (gen_template_tbllength-gen_template_selected_row);                
                for(let i = 0;i<needRows;i++){
                    gen_template_addrow(gen_template_selected_table);
                }
            }
            
            let b = 0
            for(let i=startin;i<(mytrlength);i++){
                let ttlcol = mytbody.getElementsByTagName('tr')[i].getElementsByTagName('td').length
                for(let k=0;(k<ttlcol) && (k<columnLength);k++){
                    let dkol = mytbody.getElementsByTagName('tr')[i].getElementsByTagName('td')[k].innerText
                    table.find('tr').eq((b+gen_template_selected_row)).find('td').eq((k+gen_template_selected_col)).text(dkol.trim())
                } 
                b++
            }
        }
        gem_template_recalculate()
        event.preventDefault()
    }

    function gen_template_minusrow(ptable){
        if(gen_template_selected_table!==''){
            if(ptable!=gen_template_selected_table){
                alertify.message(`wrong button`)
            } else {
                let mytable = document.getElementById(ptable).getElementsByTagName('tbody')[0]
                const mtr = mytable.getElementsByTagName('tr')[gen_template_selected_row]
                const mylineid = mtr.getElementsByTagName('td')[0].innerText.trim()                
                mtr.remove()
                gem_template_recalculate()
            }
        } else {
            alertify.message('nothing to be deleted')
        }
    }

    function gen_template_tbl_tbody_tr_eC(e){
        gen_template_selected_row = e.srcElement.parentElement.rowIndex - 2
        const ptablefocus = e.srcElement.parentElement.parentElement.offsetParent.id
        gen_template_selected_table = ptablefocus
        gen_template_selected_col = e.srcElement.cellIndex        
    }

    

    function gen_template_addrow(ptable){
        let mytbody = document.getElementById(ptable).getElementsByTagName('tbody')[0]
        let newrow , newcell        
        newrow = mytbody.insertRow(-1)
        newrow.onclick = (event) => {gen_template_tbl_tbody_tr_eC(event)}
        newcell = newrow.insertCell(0)        
        newcell.innerHTML = ''

        newcell = newrow.insertCell(1)        
        
        newcell = newrow.insertCell(2)   
        newcell.contentEditable = true             
        newcell.innerHTML = ''

        newcell = newrow.insertCell(3)        
        newcell.innerHTML = '0'

        newcell = newrow.insertCell(4)
        newcell.classList.add("text-end")
        newcell.innerHTML = '0'
        
        newcell = newrow.insertCell(5)
        newcell.classList.add("text-end")
        newcell.contentEditable = true
        newcell.innerHTML = ''        
        newcell = newrow.insertCell(6)   
        newcell.classList.add("text-end")     
        newcell.innerHTML = ''        
        newcell = newrow.insertCell(7)        
        newcell.innerHTML = ''        
        newcell = newrow.insertCell(8)        
        newcell.innerHTML = ''        
        newcell = newrow.insertCell(9)        
        newcell.innerHTML = ''        
        newcell = newrow.insertCell(10)
        newcell.classList.add('d-none')
        newcell.innerHTML = ''
                            
        gen_template_selected_table = ptable        
    }

    function gen_template_btnfindmodcust_eC() {
        if(document.getElementById('gen_template_businessgroup').value==='-'){
            alertify.message('Please select business group first')
        } else {
            gen_template_load_customer()
            $("#gen_template_MODCUS").modal('show')
        }
    }
    $("#gen_template_MODCUS").on('shown.bs.modal', function(){
        $("#gen_template_txtsearchcus").focus()
    })

    function gen_template_load_customer() {
        const searchby = document.getElementById('gen_template_srch_cust_by').value
        const searchvalue = document.getElementById('gen_template_txtsearchcus').value
        const bisgrup = document.getElementById('gen_template_businessgroup').value
        document.getElementById('gen_template_tblcus').getElementsByTagName('tbody')[0].innerHTML = `<tr><td colspan="5" class="text-center">Please wait...</td></tr>`
        $.ajax({
            type: "GET",
            url: "<?=base_url('RCV/osPO')?>",
            data: {searchby: searchby, searchvalue: searchvalue, bisgrup: bisgrup},
            dataType: "JSON",
            success: function (response) {
                const ttlrows = response.data.length
                let mydes = document.getElementById("gen_template_tblcus_div")
                let myfrag = document.createDocumentFragment()
                let mtabel = document.getElementById("gen_template_tblcus")
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln)
                let tabell = myfrag.getElementById("gen_template_tblcus")
                let tableku2 = tabell.getElementsByTagName("tbody")[0]
                let newrow, newcell, newText
                let myitmttl = 0;
                tableku2.innerHTML=''
                for (let i = 0; i<ttlrows; i++){ 
                    newrow = tableku2.insertRow(-1)
                    newrow.onclick = () => {
                        $("#gen_template_MODCUS").modal('hide')
                        document.getElementById('gen_template_custname').value = response.data[i].MSUP_SUPNM
                        document.getElementById('gen_template_curr').value = response.data[i].MSUP_SUPCR
                        gen_template_pub_cuscd = response.data[i].MSUP_SUPCD
                        document.getElementById('gen_template_txt_doc').focus()
                    }
                    newcell = newrow.insertCell(0)
                    newcell.style.cssText = "cursor:pointer"                        
                    newcell.innerHTML = response.data[i].MSUP_SUPCD
                    newcell = newrow.insertCell(1)
                    newcell.innerHTML = response.data[i].MSUP_SUPCR
                    newcell = newrow.insertCell(2)
                    newcell.innerHTML = response.data[i].MSUP_SUPNM
                    newcell = newrow.insertCell(3)
                    newcell.innerHTML = response.data[i].MSUP_ADDR1
                }
                mydes.innerHTML=''
                mydes.appendChild(myfrag)
            }, error:function(xhr,xopt,xthrow){
                alertify.error(xthrow)
            }
        })
    }

    function gen_template_businessgroup_e_onchange() {
        document.getElementById('gen_template_custname').value='';
        document.getElementById('gen_template_curr').value='';    
        $('#gen_template_tblcus tbody').empty();
        document.getElementById('gen_template_btnfindmodcust').focus();
    }
</script>