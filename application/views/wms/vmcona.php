<div style="padding: 5px" >
	<div class="container-fluid">
        <div class="row">
            <div class="col-md-2 mb-1">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" id="mcona_btn_new" onclick="mcona_btn_new_eC()"><i class="fas fa-file"></i></button>
                    <button class="btn btn-primary" id="mcona_btn_save" onclick="mcona_btn_save_eC()"><i class="fas fa-save"></i></button>                    
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Business Group</span>                                        				
                    <select class="form-select" id="mcona_businessgroup" onchange="mcona_businessgroup_e_onchange()" required> 
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
                    <span class="input-group-text">Customer</span>                                        				
                    <input type="text" class="form-control" id="mcona_custname" required readonly>	                                        
                    <button class="btn btn-outline-primary" id="mcona_btnfindmodcust" onclick="mcona_btnfindmodcust_eC()"><i class="fas fa-search"></i></button>                                        	
                </div>
            </div>
            <div class="col-md-2 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Currency</span>
                    <input type="text" readonly class="form-control" id="mcona_curr">
                </div>
            </div>
        </div>        
        <div class="row">
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Contract No</label>
                    <input type="text" class="form-control" id="mcona_txt_doc">
                    <button class="btn btn-primary" id="mcona_btnmod" onclick="mcona_btnmod_eC()"><i class="fas fa-search"></i></button>
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">                    
                    <label class="input-group-text">Contract Date</label>                    
                    <input type="text" class="form-control" id="mcona_txt_date" readonly>
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Due Date</label>
                    <input type="text" class="form-control" id="mcona_txt_duedate" readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Job</span>
                    <input class="form-control" id="mcona_job" list="mcona_job_datalist">
                    <datalist id="mcona_job_datalist">
                    <?php
                    foreach($ljob as $r){
                        ?>
                        <option value="<?=trim($r->MCONA_KNDJOB)?>">
                        <?php
                    }
                    ?>
                    </datalist>                   
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Permit No.</label>
                    <input type="text" class="form-control" id="mcona_txt_licenceNo">
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="input-group input-group-sm mb-1">
                    <label class="input-group-text">Permit Date</label>
                    <input type="text" class="form-control" id="mcona_txt_licenceDate" readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="mcona_home-tab" data-bs-toggle="tab" data-bs-target="#mcona_tabRM" type="button" role="tab" aria-controls="home" aria-selected="true">Raw Material</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="mcona_profile-tab" data-bs-toggle="tab" data-bs-target="#mcona_tabFG" type="button" role="tab" aria-controls="profile" aria-selected="false">Finished Goods</button>
                    </li>                   
                </ul>
                <div class="tab-content" id="mcona_myTabContent">
                    <div class="tab-pane fade show active" id="mcona_tabRM" role="tabpanel" aria-labelledby="home-tab">
                        <div class="container-fluid">
                            <div class="row mt-1">
                                <div class="col-md-12 mb-1 text-center">
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-primary" id="mcona_btnplus" onclick="mcona_btnplus_eC()"><i class="fas fa-plus"></i></button>
                                        <button class="btn btn-warning" id="mcona_btnmins" onclick="mcona_minusrow('mcona_tbl')"><i class="fas fa-minus"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-1">
                                    <div class="table-responsive" id="mcona_divku" onpaste="mcona_e_pastecol1(event)">
                                        <table id="mcona_tbl" class="table table-sm table-striped table-hover table-bordered caption-top" style="width:100%">                                            
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="d-none">idLine</th>
                                                    <th>Item Code</th>
                                                    <th class="text-end">Total QTY</th>
                                                    <th class="text-end">Balance QTY</th>
                                                    <th class="text-end">Price /PCS</th>
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
                    <div class="tab-pane fade" id="mcona_tabFG" role="tabpanel" aria-labelledby="contact-tab">
                        <div class="container-fluid">
                            <div class="row mt-1">            
                                <div class="col-md-12 mb-1 text-center">
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-primary" id="mcona_btnplus2" onclick="mcona_btnplus2_eC()"><i class="fas fa-plus"></i></button>
                                        <button class="btn btn-warning" id="mcona_btnmins2" onclick="mcona_minusrow('mcona_tbl_fg')"><i class="fas fa-minus"></i></button>
                                    </div>
                                </div> 
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-1">
                                    <div class="table-responsive" id="mcona_divku_fg" onpaste="mcona_e_pastecol1(event)">
                                        <table id="mcona_tbl_fg" class="table table-sm table-striped table-hover table-bordered caption-top" style="width:100%">                                            
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="d-none">idLine</th>
                                                    <th>Item Code</th>
                                                    <th class="text-end">QTY</th>                                
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
        </div>                
    </div>
</div>
<div class="modal fade" id="mcona_MODITM">
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
                <div class="col">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Search</span>
                        <select id="mcona_srchby" class="form-select">
                            <option value="0">Document Number</option>                            
                        </select>
                        <input type="text" class="form-control" id="mcona_txtsearch" onkeypress="mcona_txtsearch_eKP(event)" maxlength="44" required placeholder="...">                        
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col text-end">
                    <span class="badge bg-info" id="mcona_tblitm_lblinfo"></span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="mcona_tblitm_div">
                        <table id="mcona_tblitm" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer">
                            <thead class="table-light">
                                <tr>
                                    <th>Document Number</th>
                                    <th>Document Date</th>
                                    <th>Due Date</th>
                                    <th class="d-none">BusinessGroup</th>
                                    <th class="d-none">CustomerCode</th>
                                    <th class="d-none">CustomerName</th>
                                    <th class="d-none">Job</th>
                                    <th class="d-none">LicenseNum</th>
                                    <th class="d-none">LicenseDate</th>
                                    <th class="d-none">Currency</th>
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
<div class="modal fade" id="mcona_MODJOB">
    <div class="modal-dialog modal-sm">
      <div class="modal-content"> 
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">New Job</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Job</span>
                        <input type="text" class="form-control" id="mcona_txtnewjob" maxlength="50" required onkeypress="mcona_txtnewjob_eKP(event)"> 
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" onclick="mcona_conform_newjob_eC()"><i class="fas fa-save"></i></button>
        </div>
      </div>
    </div>
</div>
<div class="modal fade" id="mcona_MODCUS">
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
                        <input type="text" class="form-control" id="mcona_txtsearchcus" maxlength="15" required placeholder="..." onkeypress="mcona_txtsearchcus_eKP(event)">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col mb-1">
                    <div class="input-group input-group-sm mb-1">                        
                        <span class="input-group-text" >Search by</span>                        
                        <select id="mcona_srch_cust_by" class="form-select">
                            <option value="nm">Name</option>
                            <option value="cd">Code</option>
                            <option value="ad">Address</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="mcona_tblcus_div">
                        <table id="mcona_tblcus" class="table table-sm table-striped table-bordered table-hover" style="width:100%;cursor:pointer">
                            <thead class="table-light">
                                <tr>
                                    <th>Customer Code</th>
                                    <th>Currency</th>
                                    <th>Name</th>
                                    <th>Abbr Name</th>
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
    var mcona_selected_row = 1;
    var mcona_selected_col = 1;
    var mcona_selected_table = ''
    var mcona_pub_cuscd = ''
    function mcona_btnfindmodcust_eC() {
        if(document.getElementById('mcona_businessgroup').value==='-'){
            alertify.message('Please select business group first')
        } else {
            mcona_load_customer()
            $("#mcona_MODCUS").modal('show')
        }
    }

    $("#mcona_MODJOB").on('shown.bs.modal', function(){
        $("#mcona_txtnewjob").focus()
    })
    $("#mcona_MODCUS").on('shown.bs.modal', function(){
        $("#mcona_txtsearchcus").focus()
    })

    function mcona_txtnewjob_eKP(e) {
        if(e.key === 'Enter') {
            mcona_conform_newjob_eC()
        }
    }

    function mcona_conform_newjob_eC() {
        const newdata = document.getElementById('mcona_txtnewjob').value.trim()
        let cmbJob = document.getElementById('mcona_job')
        let cmbJobinner = cmbJob.innerHTML
        let currentList = cmbJob.getElementsByTagName('option')
        const currentListCount = currentList.length        
        let isAny = false

        for(let i=0; i < currentListCount; i++) {
            if( currentList[i].value.toLowerCase()===newdata.toLowerCase() ) {
                isAny = true
                break
            }
        }
        if( !isAny ) {
            cmbJobinner += `<option value="${newdata}">${newdata}</option>`
            cmbJob.innerHTML = cmbJobinner
        }
        cmbJob.value = newdata
        $("#mcona_MODJOB").modal('hide')
    }

    function mcona_job_new_eC() {
        $("#mcona_MODJOB").modal('show')
    }
    function mcona_businessgroup_e_onchange() {
        document.getElementById('mcona_custname').value='';
        document.getElementById('mcona_curr').value='';    
        $('#mcona_tblcus tbody').empty();
        document.getElementById('mcona_btnfindmodcust').focus();
    }
    $("#mcona_txt_date").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    })
    $("#mcona_txt_date").datepicker('update', new Date());
    $("#mcona_txt_duedate").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    })
    $("#mcona_txt_duedate").datepicker('update', new Date())
    $("#mcona_txt_licenceDate").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    })
    $("#mcona_txt_licenceDate").datepicker('update', new Date())    
    function mcona_btnplus_eC(){
        mcona_addrow('mcona_tbl')
        let mytbody = document.getElementById('mcona_tbl').getElementsByTagName('tbody')[0]
        mcona_selected_row = mytbody.rows.length - 1
        mcona_selected_col = 1
    }
    function mcona_btnplus2_eC(){
        mcona_addrow('mcona_tbl_fg')
        let mytbody = document.getElementById('mcona_tbl_fg').getElementsByTagName('tbody')[0]
        mcona_selected_row = mytbody.rows.length - 1
        mcona_selected_col = 1
    }

    function mcona_addrow(ptable){
        let mytbody = document.getElementById(ptable).getElementsByTagName('tbody')[0]
        let newrow , newcell        
        newrow = mytbody.insertRow(-1)
        newrow.onclick = (event) => {mcona_tbl_tbody_tr_eC(event)}
        newcell = newrow.insertCell(0)
        newcell.classList.add('d-none')
        newcell.innerHTML = ''

        newcell = newrow.insertCell(1)
        newcell.contentEditable = true
        newcell.focus()

        if(ptable==='mcona_tbl') {
            newcell = newrow.insertCell(2)
            newcell.contentEditable = true
            newcell.classList.add('text-end')
            newcell.innerHTML = '0'

            newcell = newrow.insertCell(3)
            newcell.contentEditable = true
            newcell.classList.add('text-end')
            newcell.innerHTML = '0'

            newcell = newrow.insertCell(4)
            newcell.contentEditable = true
            newcell.classList.add('text-end')
            newcell.innerHTML = '0'
            
            newcell = newrow.insertCell(5)
            newcell.contentEditable = true
            newcell.innerHTML = ''
        } else {
            newcell = newrow.insertCell(2)
            newcell.contentEditable = true
            newcell.classList.add('text-end')
            newcell.innerHTML = '0'
            
            newcell = newrow.insertCell(3)
            newcell.contentEditable = true
            newcell.innerHTML = ''
        }                        
        mcona_selected_table = ptable        
    }

    function mcona_tbl_tbody_tr_eC(e){
        mcona_selected_row = e.srcElement.parentElement.rowIndex - 1
        const ptablefocus = e.srcElement.parentElement.parentElement.offsetParent.id
        mcona_selected_table = ptablefocus
        mcona_selected_col = e.srcElement.cellIndex
    }

    function mcona_minusrow(ptable){
        if(mcona_selected_table!==''){
            if(ptable!=mcona_selected_table){
                alertify.message(`wrong button`)
            } else {
                let mytable = document.getElementById(ptable).getElementsByTagName('tbody')[0]
                const mtr = mytable.getElementsByTagName('tr')[mcona_selected_row]
                const mylineid = mtr.getElementsByTagName('td')[0].innerText.trim()
                if(mylineid!==''){
                    if(confirm("Are you sure ?")){
                        const docnum = document.getElementById('mcona_txt_doc').value
                        $.ajax({
                            type: "post",
                            url: "<?=base_url('MCONA/remove')?>",
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
    }

    function mcona_btn_new_eC(){
        document.getElementById('mcona_txt_doc').value = ''
        document.getElementById('mcona_tbl').getElementsByTagName('tbody')[0].innerHTML = ''
        document.getElementById('mcona_tbl_fg').getElementsByTagName('tbody')[0].innerHTML = ''
        

        document.getElementById('mcona_businessgroup').value = '-'
        document.getElementById('mcona_custname').value = ''
        document.getElementById('mcona_job').value = '-'
        document.getElementById('mcona_txt_licenceNo').value = '-'
    }

    function mcona_btn_save_eC(){
        const docNum = document.getElementById('mcona_txt_doc').value.trim()
        const docDate = document.getElementById('mcona_txt_date').value
        const docDueDate = document.getElementById('mcona_txt_duedate').value        
        const docBG = document.getElementById('mcona_businessgroup').value        
        const jobDesc = document.getElementById('mcona_job').value
        const licenseNum = document.getElementById('mcona_txt_licenceNo').value
        const licenseDate = document.getElementById('mcona_txt_licenceDate').value
        if(docNum.length==0){
            document.getElementById('mcona_txt_doc').focus()
            alertify.warning("Document number must not be empty")
            return
        }
        let arm_itemCd = []
        let arm_itemQty = []
        let arm_itemPrice = []
        let arm_itemRemark = []
        let arm_itemLine = []
        let arm_itemBalance = []


        let afg_itemCd = []
        let afg_itemQty = []
        let afg_itemRemark = []
        let afg_itemLine = []
        

        const rmtable = document.getElementById('mcona_tbl').getElementsByTagName('tbody')[0]
        const rmtablecount = rmtable.getElementsByTagName('tr').length
        const fgtable = document.getElementById('mcona_tbl_fg').getElementsByTagName('tbody')[0]
        const fgtablecount = fgtable.getElementsByTagName('tr').length   
        for(let i = 0; i<rmtablecount; i++) {
            const tmpitemCd = rmtable.rows[i].cells[1].innerText.replace(/\n+/g, '')
            let tmpitemQty = rmtable.rows[i].cells[2].innerText.replace(/\n+/g, '')
            let tmpitemPrice = rmtable.rows[i].cells[4].innerText.replace(/\n+/g, '')
            let tmpitemBalance = rmtable.rows[i].cells[3].innerText.replace(/\n+/g, '')
            tmpitemQty = tmpitemQty.replace(',', '')
            tmpitemPrice = tmpitemPrice.replace(',', '')
            tmpitemBalance = tmpitemBalance.replace(',', '')
            if(tmpitemCd.trim().length==0){
                alertify.message(`Item code should not be empty`)
                rmtable.rows[i].cells[1].focus()
                return
            }
            if (isNaN(tmpitemQty)){
                alertify.warning('Qty must have numeric value')
                rmtable.rows[i].cells[2].focus()
                return
            }
            if(numeral(tmpitemQty).value() <= 0){
                alertify.warning('Qty must be not zero')
                rmtable.rows[i].cells[2].focus()
                return
            }
            arm_itemCd.push(tmpitemCd)
            arm_itemQty.push(tmpitemQty)
            arm_itemPrice.push(tmpitemPrice)
            arm_itemBalance.push(tmpitemBalance)
            arm_itemRemark.push(rmtable.rows[i].cells[5].innerText.replace(/\n+/g, ''))
            arm_itemLine.push(rmtable.rows[i].cells[0].innerText.replace(/\n+/g, ''))
        }
        for(let i =0; i<fgtablecount; i++) {
            const tmpitemCd = fgtable.rows[i].cells[1].innerText.replace(/\n+/g, '')
            let tmpitemQty = fgtable.rows[i].cells[2].innerText.replace(/\n+/g, '')
            tmpitemQty = tmpitemQty.replace(',', '')
            // validate item code
            if(tmpitemCd.trim().length==0){
                alertify.message(`Item code [FG] should not be empty`)
                fgtable.rows[i].cells[1].focus()
                return
            }
            // .
            // validate qty
            if (isNaN(tmpitemQty)){
                alertify.warning('Qty must have numeric value')
                fgtable.rows[i].cells[2].focus()
                return
            }
            if(numeral(tmpitemQty).value() <= 0){
                alertify.warning('Qty must be not zero')
                fgtable.rows[i].cells[2].focus()
                return
            }
            // .
            afg_itemCd.push(tmpitemCd)
            afg_itemQty.push(tmpitemQty)
            afg_itemRemark.push(fgtable.rows[i].cells[3].innerText.replace(/\n+/g, ''))
            afg_itemLine.push(fgtable.rows[i].cells[0].innerText.replace(/\n+/g, ''))
        }
        if(rmtablecount > 0 || fgtablecount > 0 ) {
            if(confirm("Are you sure ?")){
                $.ajax({
                    type: "post",
                    url: "<?=base_url('MCONA/save')?>",
                    data: {
                        docNum: docNum,
                        docDate: docDate,
                        docDueDate: docDueDate,
                        docBG: docBG,
                        docCuscd : mcona_pub_cuscd,
                        jobDesc : jobDesc,
                        licenseNum: licenseNum,
                        licenseDate: licenseDate,
                        arm_itemCd: arm_itemCd,
                        arm_itemQty: arm_itemQty,
                        arm_itemRemark: arm_itemRemark,
                        arm_itemLine: arm_itemLine,
                        arm_itemBalance: arm_itemBalance,
                        afg_itemCd: afg_itemCd,
                        afg_itemQty: afg_itemQty,
                        afg_itemRemark: afg_itemRemark,
                        afg_itemLine: afg_itemLine,
                        arm_itemPrice: arm_itemPrice
                    },
                    dataType: "json",
                    success: function (response) {
                        if(response.status[0].cd==='1'){
                            alertify.success(response.status[0].msg)
                            mcona_load_data(docNum)
                        } else {
                            alertify.message(response.status[0].msg)
                        }
                    }, error:function(xhr,xopt,xthrow){
                        alertify.error(xthrow)
                    }
                })
            }
        } else {
            alertify.message(`nothing will be processed`)
        }
    }
    function mcona_e_pastecol1(event){
        let datapas = event.clipboardData.getData('text/html')        
        const mcona_tbllength = document.getElementById(mcona_selected_table).getElementsByTagName('tbody')[0].rows.length
        const columnLength = document.getElementById(mcona_selected_table).getElementsByTagName('tbody')[0].rows[0].cells.length        
        if(datapas===""){
            datapas = event.clipboardData.getData('text')
            let adatapas = datapas.split('\n')
            let ttlrowspasted = 0
            for(let c=0;c<adatapas.length;c++){
                if(adatapas[c].trim()!=''){
                    ttlrowspasted++
                }
            }
            let table = $(`#${mcona_selected_table} tbody`)
            let incr = 0
            if ((mcona_tbllength-mcona_selected_row)<ttlrowspasted) {       
                const needRows = ttlrowspasted - (mcona_tbllength-mcona_selected_row)
                for (let i = 0;i<needRows;i++) {
                    mcona_addrow(mcona_selected_table)
                }
            }            
            for(let i=0;i<ttlrowspasted;i++){                
                const mcol = adatapas[i].split('\t')
                const ttlcol = mcol.length                
                for(let k=0;(k<ttlcol) && (k<columnLength);k++){             
                    table.find('tr').eq((i+mcona_selected_row)).find('td').eq((k+mcona_selected_col)).text(mcol[k].trim())
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
            let table = $(`#${mcona_selected_table} tbody`)
            let incr = 0
            let startin = 0
            
            if(typeof(myscript) != 'undefined'){ //check if clipboard from IE
                startin = 3
            }
            if((mcona_tbllength-mcona_selected_row)<(mytrlength-startin)){
                let needRows = (mytrlength-startin) - (mcona_tbllength-mcona_selected_row);                
                for(let i = 0;i<needRows;i++){
                    mcona_addrow(mcona_selected_table);
                }
            }
            
            let b = 0
            for(let i=startin;i<(mytrlength);i++){
                let ttlcol = mytbody.getElementsByTagName('tr')[i].getElementsByTagName('td').length
                for(let k=0;(k<ttlcol) && (k<columnLength);k++){
                    let dkol = mytbody.getElementsByTagName('tr')[i].getElementsByTagName('td')[k].innerText
                    table.find('tr').eq((b+mcona_selected_row)).find('td').eq((k+mcona_selected_col)).text(dkol.trim())
                } 
                b++
            }
        }
        event.preventDefault()
    }
    function mcona_btnmod_eC(){
        $("#mcona_MODITM").modal('show')
    }
    $("#mcona_MODITM").on('shown.bs.modal', function(){
        $("#mcona_txtsearch").focus()
    });
    function mcona_txtsearch_eKP(e){
        if(e.key==='Enter'){
            const msearchBy = document.getElementById('mcona_srchby').value
            const msearchKey = document.getElementById('mcona_txtsearch').value
            document.getElementById('mcona_tblitm').getElementsByTagName('tbody')[0].innerHTML = ''
            document.getElementById('mcona_tblitm_lblinfo').innerHTML = "Please wait..."
            $.ajax({
                type: "GET",
                url: "<?=base_url('MCONA/search')?>",
                data: {searchBy: msearchBy, searchKey: msearchKey},
                dataType: "JSON",
                success: function (response) {
                    const ttlrows = response.data.length
                    document.getElementById('mcona_tblitm_lblinfo').innerHTML = `${ttlrows} row(s) found`
                    if( response.status[0].cd===1){
                        let mydes = document.getElementById("mcona_tblitm_div")
                        let myfrag = document.createDocumentFragment()
                        let mtabel = document.getElementById("mcona_tblitm")
                        let cln = mtabel.cloneNode(true);
                        myfrag.appendChild(cln)
                        let tabell = myfrag.getElementById("mcona_tblitm")
                        let tableku2 = tabell.getElementsByTagName("tbody")[0]
                        let newrow, newcell, newText
                        let myitmttl = 0;
                        tableku2.innerHTML=''
                        for (let i = 0; i<ttlrows; i++){
                            newrow = tableku2.insertRow(-1)
                            newcell = newrow.insertCell(0)
                            newcell.style.cssText = "cursor:pointer"
                            newcell.onclick = () => {
                                mcona_load_data(response.data[i].MCONA_DOC)
                                document.getElementById('mcona_businessgroup').value = response.data[i].MCONA_BSGRP
                                document.getElementById('mcona_custname').value = response.data[i].MCUS_CUSNM
                                document.getElementById('mcona_curr').value = response.data[i].MCUS_CURCD
                                mcona_pub_cuscd = response.data[i].MCONA_CUSCD
                                document.getElementById('mcona_job').value = response.data[i].MCONA_KNDJOB
                                document.getElementById('mcona_txt_licenceNo').value = response.data[i].MCONA_LCNSNUM
                                document.getElementById('mcona_txt_licenceDate').value = response.data[i].MCONA_LCNSDT
                            }
                            newcell.innerHTML = response.data[i].MCONA_DOC
                            newcell = newrow.insertCell(1)
                            newcell.innerHTML = response.data[i].MCONA_DATE
                            newcell = newrow.insertCell(2)
                            newcell.innerHTML = response.data[i].MCONA_DUEDATE                          

                            newcell = newrow.insertCell(3)
                            newcell.classList.add('d-none')
                            newcell.innerHTML = response.data[i].MCONA_BSGRP
                            newcell = newrow.insertCell(4)
                            newcell.classList.add('d-none')
                            newcell.innerHTML = response.data[i].MCONA_CUSCD
                            newcell = newrow.insertCell(5)
                            newcell.classList.add('d-none')
                            newcell.innerHTML = response.data[i].MCUS_CUSNM
                            newcell = newrow.insertCell(6)
                            newcell.classList.add('d-none')
                            newcell.innerHTML = response.data[i].MCONA_KNDJOB
                            newcell = newrow.insertCell(7)
                            newcell.classList.add('d-none')
                            newcell.innerHTML = response.data[i].MCONA_LCNSNUM
                            newcell = newrow.insertCell(8)
                            newcell.classList.add('d-none')
                            newcell.innerHTML = response.data[i].MCONA_LCNSDT
                            newcell = newrow.insertCell(9)
                            newcell.classList.add('d-none')
                            newcell.innerHTML = response.data[i].MCUS_CURCD
                        }
                        mydes.innerHTML='';
                        mydes.appendChild(myfrag);
                    } else {
                        alertify.message(response.status[0].msg)
                    }
                }, error:function(xhr,xopt,xthrow){
                    alertify.error(xthrow)
                    document.getElementById('mcona_tblitm_lblinfo').innerHTML = xthrow
                }
            });
        }
    }
    function mcona_load_data(pdoc){
        document.getElementById('mcona_txt_doc').value = pdoc        
        $.ajax({
            type: "GET",
            url: "<?=base_url('MCONA/getdata')?>",
            data: {docNum: pdoc},
            dataType: "JSON",
            success: function (response) {
                if( response.status[0].cd===1 ) {
                    const ttlrows = response.data.length                    
                    let mydes = document.getElementById("mcona_divku")
                    let myfrag = document.createDocumentFragment()
                    let mtabel = document.getElementById("mcona_tbl")
                    let cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln)
                    let tabell = myfrag.getElementById("mcona_tbl")
                    let tableku2 = tabell.getElementsByTagName("tbody")[0]
                    let newrow, newcell, newText                    
                    tableku2.innerHTML=''
                    document.getElementById('mcona_txt_date').value = response.data[0].MCONA_DATE
                    document.getElementById('mcona_txt_duedate').value = response.data[0].MCONA_DUEDATE
                    
                    for (let i = 0; i<ttlrows; i++){
                        if(response.data[i].MCONA_ITMTYPE==='0'){
                            newrow = tableku2.insertRow(-1)
                            newrow.onclick = (event) => {mcona_tbl_tbody_tr_eC(event)}
                            newcell = newrow.insertCell(0)
                            newcell.classList.add('d-none')
                            newcell.innerHTML = response.data[i].MCONA_LINE

                            newcell = newrow.insertCell(1)
                            newcell.contentEditable = true
                            newcell.innerHTML = response.data[i].MCONA_ITMCD

                            newcell = newrow.insertCell(2)
                            newcell.contentEditable = true
                            newcell.classList.add('text-end')
                            newcell.innerHTML = numeral(response.data[i].MCONA_QTY).format(',')

                            newcell = newrow.insertCell(3)
                            newcell.contentEditable = true
                            newcell.classList.add('text-end')
                            newcell.innerHTML = numeral(response.data[i].MCONA_BALQTY).format(',')
                            
                            newcell = newrow.insertCell(4)
                            newcell.contentEditable = true
                            newcell.classList.add('text-end')
                            newcell.innerHTML = numeral(response.data[i].MCONA_PERPRC).format('0,0.0000')
                            
                            newcell = newrow.insertCell(5)
                            newcell.contentEditable = true
                            newcell.innerHTML = response.data[i].MCONA_REMARK
                        }
                    }
                    mydes.innerHTML=''
                    mydes.appendChild(myfrag)

                    //FG
                    mydes = document.getElementById("mcona_divku_fg")
                    myfrag = document.createDocumentFragment()
                    mtabel = document.getElementById("mcona_tbl_fg")
                    cln = mtabel.cloneNode(true);
                    myfrag.appendChild(cln)
                    tabell = myfrag.getElementById("mcona_tbl_fg")
                    tableku2 = tabell.getElementsByTagName("tbody")[0]                    
                    tableku2.innerHTML=''
                    for (let i = 0; i<ttlrows; i++){
                        if(response.data[i].MCONA_ITMTYPE==='1'){
                            newrow = tableku2.insertRow(-1)
                            newrow.onclick = (event) => {mcona_tbl_tbody_tr_eC(event)}
                            newcell = newrow.insertCell(0)
                            newcell.classList.add('d-none')                            
                            newcell.innerHTML = response.data[i].MCONA_LINE

                            newcell = newrow.insertCell(1)
                            newcell.contentEditable = true
                            newcell.innerHTML = response.data[i].MCONA_ITMCD

                            newcell = newrow.insertCell(2)
                            newcell.contentEditable = true
                            newcell.classList.add('text-end')
                            newcell.innerHTML = numeral(response.data[i].MCONA_QTY).format(',')

                            newcell = newrow.insertCell(3)
                            newcell.contentEditable = true
                            newcell.innerHTML = response.data[i].MCONA_REMARK
                        }
                    }
                    mydes.innerHTML=''
                    mydes.appendChild(myfrag)


                    $("#mcona_MODITM").modal('hide')
                } else {
                    alertify.message(response.status[0].msg)
                }
            }, error:function(xhr,xopt,xthrow){
                alertify.error(xthrow)                
            }
        });
    }

    function mcona_txtsearchcus_eKP(e) {
        if( e.key === 'Enter') {
            mcona_load_customer()
        }
    }

    function mcona_load_customer() {
        const searchby = document.getElementById('mcona_srch_cust_by').value
        const searchvalue = document.getElementById('mcona_txtsearchcus').value
        const bisgrup = document.getElementById('mcona_businessgroup').value
        document.getElementById('mcona_tblcus').getElementsByTagName('tbody')[0].innerHTML = `<tr><td colspan="5" class="text-center">Please wait...</td></tr>`
        $.ajax({
            type: "GET",
            url: "<?=base_url('MCONA/searchCustomer')?>",
            data: {searchby: searchby, searchvalue: searchvalue, bisgrup: bisgrup},
            dataType: "JSON",
            success: function (response) {
                const ttlrows = response.data.length
                let mydes = document.getElementById("mcona_tblcus_div")
                let myfrag = document.createDocumentFragment()
                let mtabel = document.getElementById("mcona_tblcus")
                let cln = mtabel.cloneNode(true);
                myfrag.appendChild(cln)
                let tabell = myfrag.getElementById("mcona_tblcus")
                let tableku2 = tabell.getElementsByTagName("tbody")[0]
                let newrow, newcell, newText
                let myitmttl = 0;
                tableku2.innerHTML=''
                for (let i = 0; i<ttlrows; i++){ 
                    newrow = tableku2.insertRow(-1)
                    newrow.onclick = () => {
                        $("#mcona_MODCUS").modal('hide')
                        document.getElementById('mcona_custname').value = response.data[i].MCUS_CUSNM
                        document.getElementById('mcona_curr').value = response.data[i].MCUS_CURCD
                        mcona_pub_cuscd = response.data[i].PGRN_CUSCD
                        document.getElementById('mcona_txt_doc').focus()
                    }
                    newcell = newrow.insertCell(0)
                    newcell.style.cssText = "cursor:pointer"                        
                    newcell.innerHTML = response.data[i].PGRN_CUSCD
                    newcell = newrow.insertCell(1)
                    newcell.innerHTML = response.data[i].MCUS_CURCD
                    newcell = newrow.insertCell(2)
                    newcell.innerHTML = response.data[i].MCUS_CUSNM
                    newcell = newrow.insertCell(3)
                    newcell.innerHTML = response.data[i].MCUS_ABBRV
                    newcell = newrow.insertCell(4)
                    newcell.innerHTML = response.data[i].MCUS_ADDR1
                }
                mydes.innerHTML=''
                mydes.appendChild(myfrag)
            }, error:function(xhr,xopt,xthrow){
                alertify.error(xthrow)
            }
        })
    }
</script>