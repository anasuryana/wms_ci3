<style>
    .anastylesel_sim{
        background: red;
        animation: anamove 1s infinite;
    }
    @keyframes anamove {
        from {background: #7FDBFF;}
        to {background: #01FF70;}
    }    
</style>
<div style="padding: 10px">
    <div class="container-fluid">        
        <div class="row" id="stinventory_stack1">
            <div class="col-md-12 mb-1">
                <div class="input-group input-group-sm">                    
                    <span class="input-group-text" >Location</span>                    
                    <input type="text" class="form-control" id="stinventory_cmb_bg" readonly onclick="stinventory_bisgrup_eC()">
                </div>
            </div>
            
        </div>
        <div class="row" id="stinventory_stack4">
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" >Physical Inventory Date</span>
                    <input type="text" class="form-control" id="stinventory_txt_date" readonly onchange="stinventory_txt_date_eChange()">
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="input-group input-group-sm">                    
                    <span class="input-group-text" >Stock Opname Date</span>
                    <input type="text" class="form-control" id="stinventory_txtopname_date" disabled>
                </div>
            </div>
        </div>
       
        <div class="row" id="stinventory_stack2">
            <div class="col-md-12 mb-1 text-center">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-primary" type="button" id="stinventory_btn_gen" onclick="stinventory_e_upload(this)">Upload</button>
                </div>
            </div>
        </div>
        <div class="row" id="stinventory_stack3">
            <div class="col-md-12 mb-1">
                <div class="table-responsive" id="stinventory_divku">
                    <table id="stinventory_tbl" class="table table-striped table-bordered table-sm table-hover">
                        <thead class="table-light">
                            <tr>
                                <th rowspan="3" class="align-middle text-center">Location</th>
                                <th colspan="4" class="text-center">Operation</th>
                            </tr>
                            <tr>
                                <th colspan="2" class="text-center">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="stinventory_ck_phy">
                                        <label class="form-check-label" for="stinventory_ck_phy">
                                        Physical Inventory
                                        </label>
                                    </div>
                                </th>
                                <th colspan="2" class="text-center">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="stinventory_ck_opname" onclick="stinventory_ck_opname_eClick(this)">
                                        <label class="form-check-label" for="stinventory_ck_opname">
                                        Stock Opname
                                        </label>
                                    </div>                                    
                                </th>
                            </tr>
                            <tr>
                                <th class="text-center">
                                    Adjustment
                                </th>
                                <th class="text-center">
                                    Upload
                                </th>
                                <th class="text-center">
                                    Adjustment
                                </th>
                                <th class="text-center">
                                    Upload
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-1">
                <div id="stinventory_div_infoAfterPost">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="stinventory_BG">
    <div class="modal-dialog modal-sm modal-dialog-scrollable">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Location List</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col" onclick="stinventory_selectBG_eC(event)">
                    <div class="table-responsive" id="stinventory_tblbg_div">
                        <table id="stinventory_tblbg" class="table table-sm table-striped table-bordered table-hover" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center d-none">ID</th>
                                    <th class="text-center">Location Code</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th class="text-center d-none">-</th>
                                    <th class="text-center">Please wait. . .</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="stinventory_DATE">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
            <h4 class="modal-title">Warning</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="alert alert-warning" role="alert" id="stinventory_alert">
                        
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive" id="stinventory_tblperiod_div">
                        <table id="stinventory_tblperiod" class="table table-sm table-striped table-bordered table-hover" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center d-none"><span id="stinventory_fix_id"></span></th>
                                    <th class="text-center"><span id="stinventory_fix_str"></span></th>
                                    <th class="text-center">.</th>
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

    function stinventory_initOpname()
    {
        const date = new Date()
        const lastDay = new Date(date.getFullYear(), date.getMonth()+1,0)
        let omom = moment(lastDay)        
        stinventory_txtopname_date.value = omom.format('YYYY-MM-DD')
    }

    function stinventory_txt_date_eChange()
    {
        const date = new Date(stinventory_txt_date.value)
        const lastDay = new Date(date.getFullYear(), date.getMonth()+1,0)
        let omom = moment(lastDay)        
        stinventory_txtopname_date.value = omom.format('YYYY-MM-DD')
    }

    stinventory_initOpname()

    function stinventory_bisgrup_eC() 
    {
        $("#stinventory_BG").modal('show')
    }
    $("#stinventory_txt_date").datepicker({
        format: 'yyyy-mm-dd',
        autoclose:true
    });   
    $("#stinventory_txt_date").datepicker('update', new Date());    
    stinventory_e_getBG()
    function stinventory_e_getBG()
    {
        $.ajax({            
            url: "<?=base_url('ITHHistory/getLocations')?>",
            dataType: "JSON",
            success: function (response) {
                const ttlrows = response.data.length;
                let mydes = document.getElementById("stinventory_tblbg_div");
                let myfrag = document.createDocumentFragment();
                let mtabel = document.getElementById("stinventory_tblbg")
                let cln = mtabel.cloneNode(true)
                myfrag.appendChild(cln)
                let tabell = myfrag.getElementById("stinventory_tblbg")
                let tableku2 = tabell.getElementsByTagName("tbody")[0]
                let newrow, newcell, newText;
                tableku2.innerHTML=''
                for (let i = 0; i<ttlrows; i++){
                    newrow = tableku2.insertRow(-1)
                    newrow.style.cssText = 'cursor:pointer'
                    newcell = newrow.insertCell(0);
                    newcell.classList.add('d-none')
                    newcell.innerHTML = response.data[i].LOCATIONCD
                    newcell = newrow.insertCell(1)
                    newcell.innerHTML = response.data[i].LOCATIONCD
                    newcell.title = response.data[i].LOCATIONNM
                }
                mydes.innerHTML=''
                mydes.appendChild(myfrag)
            }, error : function(xhr, xopt, xthrow){
                alertify.error(xthrow);
            }
        });
    }
    var stinventory_a_BG = [];
    var stinventory_a_BG_NM = [];
    function stinventory_selectBG_eC(e){
        if(e.target.tagName.toLowerCase()==='td'){
            if(e.target.cellIndex==1){
                const mtbl = document.getElementById('stinventory_tblbg')
                const tableku2 = mtbl.getElementsByTagName("tbody")[0]
                const mtbltr = tableku2.getElementsByTagName('tr')
                const ttlrows = mtbltr.length
                if(e.target.classList.contains('anastylesel_sim'))
                {
                    e.target.classList.remove('anastylesel_sim')
                    const getINDX = stinventory_a_BG.indexOf(e.target.previousElementSibling.innerText)
                    if(getINDX > -1){
                        stinventory_a_BG.splice(getINDX, 1)
                        stinventory_a_BG_NM.splice(getINDX, 1)
                    }
                } else 
                {
                    if(e.target.textContent.length!=0){
                        stinventory_a_BG.push(e.target.previousElementSibling.innerText)
                        stinventory_a_BG_NM.push(e.target.innerText)
                        e.target.classList.add('anastylesel_sim')
                    }
                }
            }
        }        
    }
    $("#stinventory_BG").on('hidden.bs.modal', function(){
        let strDisplay = ''
        stinventory_a_BG_NM.forEach((item, index) => {
            strDisplay += item + ', '
        })
        document.getElementById('stinventory_cmb_bg').value = strDisplay.substr(0,strDisplay.length-2)
    })

    function stinventory_e_upload(p)
    {
        if(stinventory_a_BG.length===0){
            alertify.warning('select location first')
            return
        }

        if(!stinventory_ck_phy.checked && !stinventory_ck_opname.checked)
        {
            alertify.message('nothing to process')
            return
        }

        const currentDate = new Date()
        const opnameDate = new Date(stinventory_txtopname_date.value)
        if(currentDate<opnameDate)
        {
            alertify.warning(`sorry, we have to pass the 'opname date' first`)
            return
        }

        // 1. 1 month 1 stock taking adjustment        
        if(stinventory_ck_phy.checked)
        {
            $.ajax({
                type: "GET",
                url: "<?=base_url('ITHHistory/check_inventory_it_inventory')?>",
                data: {date: stinventory_txt_date.value, type:'P'},
                dataType: "JSON",
                success: function (response) {
                    if(response.status[0].cd==='1')
                    {
                        if(confirm('Are you sure ?')) 
                        {
                            if(confirm('If any adjustment it will be adjusted on ' +  stinventory_txt_date.value + ' ?')) 
                            {
                                let tableku2 = stinventory_tbl.getElementsByTagName("tbody")[0]
                                let newrow, newcell
                                tableku2.innerHTML=''
                                stinventory_a_BG.forEach((item, index) => {
                                    newrow = tableku2.insertRow(-1)
                                    newcell = newrow.insertCell(0);
                                    newcell.innerHTML = item
                                    newcell = newrow.insertCell(1)
                                    newcell.classList.add('text-center')
                                    newcell.innerHTML = 'Please wait'
                                    newcell = newrow.insertCell(2)
                                    newcell.classList.add('text-center')
                                    newcell.innerHTML = stinventory_ck_phy.checked ? 'is waiting Adjustment Process' : ''
                                    newcell = newrow.insertCell(3)
                                    newcell.innerHTML = stinventory_ck_opname.checked ? 'Please wait' : ''
                                    newcell = newrow.insertCell(4)
                                    newcell.innerHTML = stinventory_ck_opname.checked ? 'Please wait' : ''                                    
                                })
                                let functionListPhysic_adj = []                                                                                                
                                stinventory_a_BG.forEach((item, index) => {
                                    functionListPhysic_adj.push(
                                        $.ajax({
                                            type: "POST",
                                            url: "<?=base_url('ITH/adjustment_ParentBased')?>",
                                            data: {date: stinventory_txt_date.value, location : item, adjtype: 'P'},
                                            dataType: "JSON",
                                            success: function (response) {
                                                for(let i=0;i<stinventory_a_BG.length; i++) {
                                                    if(tableku2.rows[i].cells[0].innerText===response.status.reff) {
                                                        tableku2.rows[i].cells[1].innerHTML = `<span class="badge bg-success">${response.status.msg}</span>`
                                                        break;
                                                    }
                                                }
                                            }, error : function(xhr, xopt, xthrow){
                                                alertify.error(xthrow)
                                                const thedata = new URLSearchParams(this.data)
                                                for(let i=0;i<stinventory_a_BG.length; i++) {
                                                    if(tableku2.rows[i].cells[0].innerText===thedata.get('location')) {
                                                        tableku2.rows[i].cells[1].innerHTML = `<span class="badge bg-danger">${xthrow}</span>`
                                                        tableku2.rows[i].cells[2].innerHTML = ``
                                                        break;
                                                    }
                                                }
                                            }
                                        })
                                    )
                                })
                                $.when.apply($,functionListPhysic_adj).then(function() {
                                    let functionListPhysic_upl = []
                                    stinventory_a_BG.forEach((item, index) => {
                                        tableku2.rows[index].cells[2].innerHTML = 'Please wait'
                                        functionListPhysic_upl.push(
                                            $.ajax({
                                                type: "POST",
                                                url: "<?=base_url('ITH/upload_to_itinventory')?>",
                                                data: {date: stinventory_txt_date.value, location : item, upltype: 'P'},
                                                dataType: "JSON",
                                                success: function (response) {
                                                    p.disabled = false
                                                    p.innerHTML = 'Upload'
                                                    for(let i=0;i<stinventory_a_BG.length; i++) {
                                                        if(tableku2.rows[i].cells[0].innerText===response.status.reff) {
                                                            tableku2.rows[i].cells[2].innerHTML = `<span class="badge bg-success">${response.status.msg}</span>`
                                                            break;
                                                        }
                                                    }
                                                }, error : function(xhr, xopt, xthrow){
                                                    alertify.error(xthrow)
                                                    p.disabled = false
                                                    p.innerHTML = 'Upload'
                                                    const thedata = new URLSearchParams(this.data)
                                                    for(let i=0;i<stinventory_a_BG.length; i++) {
                                                        if(tableku2.rows[i].cells[0].innerText===thedata.get('location')) {
                                                            tableku2.rows[i].cells[2].innerHTML = `<span class="badge bg-danger">${xthrow}</span>`
                                                            break;
                                                        }
                                                    }
                                                }
                                            })
                                        )
                                    })
                                    $.when.apply($,functionListPhysic_upl).then(function() {
                                        //opname stock after physical stock
                                        if(stinventory_ck_opname.checked)
                                        {
                                            $.ajax({
                                                type: "GET",
                                                url: "<?=base_url('ITHHistory/check_inventory_it_inventory')?>",
                                                data: {date: stinventory_txtopname_date.value, type:'O'},
                                                dataType: "JSON",
                                                success: function (response) {
                                                    if(response.status[0].cd==='1')
                                                    {                                                                                                                  
                                                        let functionListOpname_adj = []                                    
                                                        stinventory_a_BG.forEach((item, index) => {
                                                            functionListOpname_adj.push(
                                                                $.ajax({
                                                                    type: "POST",
                                                                    url: "<?=base_url('ITH/adjustment_ParentBased')?>",
                                                                    data: {date: stinventory_txtopname_date.value, location : item, adjtype: 'O'},
                                                                    dataType: "JSON",
                                                                    success: function (response) {
                                                                        for(let i=0;i<stinventory_a_BG.length; i++) {
                                                                            if(tableku2.rows[i].cells[0].innerText===response.status.reff) {
                                                                                tableku2.rows[i].cells[3].innerHTML = `<span class="badge bg-success">${response.status.msg}</span>`
                                                                                break;
                                                                            }
                                                                        }
                                                                    }, error : function(xhr, xopt, xthrow){
                                                                        alertify.error(xthrow)
                                                                        const thedata = new URLSearchParams(this.data)
                                                                        for(let i=0;i<stinventory_a_BG.length; i++) {
                                                                            if(tableku2.rows[i].cells[0].innerText===thedata.get('location')) {
                                                                                tableku2.rows[i].cells[3].innerHTML = `<span class="badge bg-danger">${xthrow}</span>`
                                                                                tableku2.rows[i].cells[4].innerHTML = ``
                                                                                break;
                                                                            }
                                                                        }
                                                                    }
                                                                })
                                                            )
                                                        })
                                                        $.when.apply($,functionListOpname_adj).then(function() {
                                                            let functionListOpname_upl = []
                                                            stinventory_a_BG.forEach((item, index) => {
                                                                tableku2.rows[index].cells[4].innerHTML = 'Please wait'
                                                                functionListOpname_upl.push(
                                                                    $.ajax({
                                                                        type: "POST",
                                                                        url: "<?=base_url('ITH/upload_to_itinventory')?>",
                                                                        data: {date: stinventory_txtopname_date.value, location : item, upltype: 'O'},
                                                                        dataType: "JSON",
                                                                        success: function (response) {
                                                                            p.disabled = false
                                                                            p.innerHTML = 'Upload'
                                                                            for(let i=0;i<stinventory_a_BG.length; i++) {
                                                                                if(tableku2.rows[i].cells[0].innerText===response.status.reff) {
                                                                                    tableku2.rows[i].cells[4].innerHTML = `<span class="badge bg-success">${response.status.msg}</span>`
                                                                                    break;
                                                                                }
                                                                            }
                                                                        }, error : function(xhr, xopt, xthrow){
                                                                            alertify.error(xthrow)
                                                                            p.disabled = false
                                                                            p.innerHTML = 'Upload'
                                                                            const thedata = new URLSearchParams(this.data)
                                                                            for(let i=0;i<stinventory_a_BG.length; i++) {
                                                                                if(tableku2.rows[i].cells[0].innerText===thedata.get('location')) {
                                                                                    tableku2.rows[i].cells[3].innerHTML = `<span class="badge bg-danger">${xthrow}</span>`
                                                                                    tableku2.rows[i].cells[4].innerHTML = ``
                                                                                    break;
                                                                                }
                                                                            }
                                                                        }
                                                                    })
                                                                )
                                                            })
                                                            $.when.apply($,functionListOpname_upl).then(function() {
                                                                alertify.message('Done..')
                                                            })
                                                        })
                                                    } else 
                                                    {
                                                        document.getElementById('stinventory_alert').innerHTML = 'the data is already exist but in different date'
                                                        $("#stinventory_DATE").modal('show')
                                                        stinventory_fix_id.innerText = response.reff_type
                                                        stinventory_fix_str.innerText = response.reff_type === 'P' ? 'Physical Date' : 'Opname Date'
                                                        const ttlrows = response.data.length
                                                        let newrow, newcell;
                                                        stinventory_tblperiod.getElementsByTagName('tbody')[0].innerHTML = ''
                                                        for(let i=0;i<ttlrows; i++)
                                                        {
                                                            newrow = stinventory_tblperiod.getElementsByTagName('tbody')[0].insertRow(-1)
                                                            newcell = newrow.insertCell(0);
                                                            newcell.classList.add('d-none')
                                                            newcell.innerText = response.reff_type
                                                            newcell = newrow.insertCell(1)
                                                            newcell.classList.add('text-center')
                                                            newcell.innerHTML = response.data[i][response.reff_type === 'P' ? 'INV_PHY_DATE': 'INV_DATE']
                                                            newcell = newrow.insertCell(2)
                                                            newcell.classList.add('text-center')
                                                            newcell.innerHTML = `<span class="fas fa-trash text-danger"></span>`
                                                            newcell.style.cssText = 'cursor:pointer'
                                                            newcell.onclick = (event) => {
                                                                let idRows;
                                                                if(event.target.nodeName==='TD')
                                                                {
                                                                    idRows = event.target.parentNode.rowIndex
                                                                } else {
                                                                    idRows = event.target.parentNode.parentNode.rowIndex
                                                                }
                                                                const thedate = stinventory_tblperiod.rows[idRows].cells[1].innerText
                                                                stinventory_remove({thedate: thedate, reff_type:response.reff_type, idRows: idRows })
                                                            }
                                                        }
                                                    }
                                                }, error: function(xhr, xopt, xthrow){
                                                    alertify.error(xthrow);
                                                }
                                            });
                                        } else {
                                            alertify.success('Done.')
                                        }
                                    })
                                })
                            }
                        }
                    } else 
                    {
                        document.getElementById('stinventory_alert').innerHTML = 'the data is already exist but in different date'
                        $("#stinventory_DATE").modal('show')
                        stinventory_fix_id.innerText = response.reff_type
                        stinventory_fix_str.innerText = response.reff_type === 'P' ? 'Physical Date' : 'Opname Date'
                        const ttlrows = response.data.length
                        let newrow, newcell;
                        stinventory_tblperiod.getElementsByTagName('tbody')[0].innerHTML = ''
                        for(let i=0;i<ttlrows; i++)
                        {                            
                            newrow = stinventory_tblperiod.getElementsByTagName('tbody')[0].insertRow(-1)
                            newcell = newrow.insertCell(0);
                            newcell.classList.add('d-none')
                            newcell = newrow.insertCell(1)
                            newcell.classList.add('text-center')
                            newcell.innerHTML = response.data[i][response.reff_type === 'P' ? 'INV_PHY_DATE': 'INV_DATE']
                            newcell = newrow.insertCell(2)
                            newcell.classList.add('text-center')
                            newcell.innerHTML = `<span class="fas fa-trash text-danger"></span>`
                            newcell.style.cssText = 'cursor:pointer'
                            newcell.onclick = (event) => {
                                let idRows;
                                if(event.target.nodeName==='TD')
                                {
                                    idRows = event.target.parentNode.rowIndex
                                } else {
                                    idRows = event.target.parentNode.parentNode.rowIndex
                                }
                                const thedate = stinventory_tblperiod.rows[idRows].cells[1].innerText
                                stinventory_remove({thedate: thedate, reff_type:response.reff_type, idRows: idRows })
                            }
                        }                        
                    }                    
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);                    
                }
            });
        } else {
            if(stinventory_ck_opname.checked)
            {
                $.ajax({
                    type: "GET",
                    url: "<?=base_url('ITHHistory/check_inventory_it_inventory')?>",
                    data: {date: stinventory_txtopname_date.value, type:'O'},
                    dataType: "JSON",
                    success: function (response) {
                        if(response.status[0].cd==='1')
                        {
                            if(confirm('Are you sure ?')) 
                            {
                                if(confirm('If any adjustment it will be adjusted on ' +  stinventory_txtopname_date.value + ' ?')) 
                                {                                
                                    let tableku2 = stinventory_tbl.getElementsByTagName("tbody")[0]
                                    let newrow, newcell
                                    tableku2.innerHTML=''
                                    stinventory_a_BG.forEach((item, index) => {
                                        newrow = tableku2.insertRow(-1)
                                        newcell = newrow.insertCell(0);
                                        newcell.innerHTML = item
                                        newcell = newrow.insertCell(1)
                                        newcell.classList.add('text-center')
                                        newcell.innerHTML = ''
                                        newcell = newrow.insertCell(2)
                                        newcell.classList.add('text-center')
                                        newcell.innerHTML = ''
                                        newcell = newrow.insertCell(3)
                                        newcell.innerHTML = stinventory_ck_opname.checked ? 'Please wait' : ''
                                        newcell = newrow.insertCell(4)
                                        newcell.innerHTML = 'Waiting Opname Adjustment'
                                    })                                    
                                    
                                    let functionListOpname_adj = []                                    
                                    stinventory_a_BG.forEach((item, index) => {
                                        functionListOpname_adj.push(
                                            $.ajax({
                                                type: "POST",
                                                url: "<?=base_url('ITH/adjustment_ParentBased')?>",
                                                data: {date: stinventory_txtopname_date.value, location : item, adjtype: 'O'},
                                                dataType: "JSON",
                                                success: function (response) {
                                                    for(let i=0;i<stinventory_a_BG.length; i++) {
                                                        if(tableku2.rows[i].cells[0].innerText===response.status.reff) {
                                                            tableku2.rows[i].cells[3].innerHTML = `<span class="badge bg-success">${response.status.msg}</span>`
                                                            break;
                                                        }
                                                    }
                                                }, error : function(xhr, xopt, xthrow){
                                                    alertify.error(xthrow)
                                                    const thedata = new URLSearchParams(this.data)
                                                    for(let i=0;i<stinventory_a_BG.length; i++) {
                                                        if(tableku2.rows[i].cells[0].innerText===thedata.get('location')) {
                                                            tableku2.rows[i].cells[3].innerHTML = `<span class="badge bg-danger">${xthrow}</span>`
                                                            tableku2.rows[i].cells[4].innerHTML = ``
                                                            break;
                                                        }
                                                    }
                                                }
                                            })
                                        )
                                    })
                                    $.when.apply($,functionListOpname_adj).then(function() {
                                        let functionListOpname_upl = []
                                        stinventory_a_BG.forEach((item, index) => {
                                            tableku2.rows[index].cells[4].innerHTML = 'Please wait'
                                            functionListOpname_upl.push(
                                                $.ajax({
                                                    type: "POST",
                                                    url: "<?=base_url('ITH/upload_to_itinventory')?>",
                                                    data: {date: stinventory_txtopname_date.value, location : item, upltype: 'O'},
                                                    dataType: "JSON",
                                                    success: function (response) {
                                                        p.disabled = false
                                                        p.innerHTML = 'Upload'
                                                        for(let i=0;i<stinventory_a_BG.length; i++) {
                                                            if(tableku2.rows[i].cells[0].innerText===response.status.reff) {
                                                                tableku2.rows[i].cells[4].innerHTML = `<span class="badge bg-success">${response.status.msg}</span>`
                                                                break;
                                                            }
                                                        }
                                                    }, error : function(xhr, xopt, xthrow){
                                                        alertify.error(xthrow)
                                                        p.disabled = false
                                                        p.innerHTML = 'Upload'
                                                        const thedata = new URLSearchParams(this.data)
                                                        for(let i=0;i<stinventory_a_BG.length; i++) {
                                                            if(tableku2.rows[i].cells[0].innerText===thedata.get('location')) {
                                                                tableku2.rows[i].cells[3].innerHTML = `<span class="badge bg-danger">${xthrow}</span>`
                                                                tableku2.rows[i].cells[4].innerHTML = ``
                                                                break;
                                                            }
                                                        }
                                                    }
                                                })
                                            )
                                        })
                                        $.when.apply($,functionListOpname_upl).then(function() {
                                            alertify.success('Done')
                                        })
                                    })
                                } else 
                                {
                                    alertify.message('You are not sure')
                                }
                            }
                        } else 
                        {
                            document.getElementById('stinventory_alert').innerHTML = 'the data is already exist but in different date'
                            $("#stinventory_DATE").modal('show')
                            stinventory_fix_id.innerText = response.reff_type
                            stinventory_fix_str.innerText = response.reff_type === 'P' ? 'Physical Date' : 'Opname Date'
                            const ttlrows = response.data.length
                            let newrow, newcell;
                            stinventory_tblperiod.getElementsByTagName('tbody')[0].innerHTML = ''
                            for(let i=0;i<ttlrows; i++)
                            {
                                newrow = stinventory_tblperiod.getElementsByTagName('tbody')[0].insertRow(-1)
                                newcell = newrow.insertCell(0);
                                newcell.classList.add('d-none')
                                newcell.innerText = response.reff_type
                                newcell = newrow.insertCell(1)
                                newcell.classList.add('text-center')
                                newcell.innerHTML = response.data[i][response.reff_type === 'P' ? 'INV_PHY_DATE': 'INV_DATE']
                                newcell = newrow.insertCell(2)
                                newcell.classList.add('text-center')
                                newcell.innerHTML = `<span class="fas fa-trash text-danger"></span>`
                                newcell.style.cssText = 'cursor:pointer'
                                newcell.onclick = (event) => {
                                    let idRows;
                                    if(event.target.nodeName==='TD')
                                    {
                                        idRows = event.target.parentNode.rowIndex
                                    } else {
                                        idRows = event.target.parentNode.parentNode.rowIndex
                                    }
                                    const thedate = stinventory_tblperiod.rows[idRows].cells[1].innerText
                                    stinventory_remove({thedate: thedate, reff_type:response.reff_type, idRows: idRows })
                                }
                            }
                        }
                    }, error: function(xhr, xopt, xthrow){
                        alertify.error(xthrow);
                    }
                });
            }
        }       
    }

    function stinventory_ck_opname_eClick(pThis){
        const currentDate = new Date()
        const opnameDate = new Date(stinventory_txtopname_date.value)
        if(currentDate<opnameDate)
        {
            pThis.checked = false
            alertify.warning(`sorry, we have to pass the 'opname date' first`)
        }
    }

    function stinventory_remove(param)
    {
        if(confirm(`Are you sure want to delete ${param.thedate} ?`))
        {
            $.ajax({
                type: "POST",
                url: "<?=base_url('ITH/remove_uploaded_stock_it_inventory')?>",
                data: {date: param.thedate, stocktype : param.reff_type},
                dataType: "JSON",
                success: function (response) {
                    if(response.status.cd==='1')
                    {
                        stinventory_tblperiod.rows[param.idRows].remove()
                        alertify.message(response.status.msg)
                    } else {
                        alertify.message(response.status.msg)
                    }
                }, error: function(xhr, xopt, xthrow){
                    alertify.error(xthrow);
                }
            })
        }
    }
</script>